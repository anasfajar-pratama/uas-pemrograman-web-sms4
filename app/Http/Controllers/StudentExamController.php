<?php

namespace App\Http\Controllers;

use App\Models\Answer;
use App\Models\Exam;
use App\Models\ExamSession;
use App\Models\Question;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class StudentExamController extends Controller
{
    public function showEnterCode()
    {
        return view('exam.enter-code');
    }

    public function enterCode(Request $request)
    {
        $request->validate([
            'code' => 'required|string',
        ], [
            'code.required' => 'Kode ujian wajib diisi.',
        ]);

        $exam = Exam::where('code', strtoupper(trim($request->code)))->first();

        if (!$exam) {
            return back()->withErrors(['code' => 'Kode ujian tidak ditemukan.'])->withInput();
        }

        if ($exam->isExpired()) {
            $reason = !$exam->is_active ? 'Ujian sudah dinonaktifkan.' :
                ($exam->ends_at && $exam->ends_at->isPast() ? 'Ujian sudah berakhir.' : 'Ujian belum dimulai.');
            return back()->withErrors(['code' => $reason])->withInput();
        }

        $user = Auth::user();
        $existingSession = ExamSession::where('user_id', $user->id)
            ->where('exam_id', $exam->id)
            ->first();

        if ($existingSession && $existingSession->isFinished()) {
            return back()->withErrors(['code' => 'Anda sudah menyelesaikan ujian ini.'])->withInput();
        }

        if (!$existingSession) {
            $existingSession = ExamSession::create([
                'user_id'         => $user->id,
                'exam_id'         => $exam->id,
                'started_at'      => now(),
                'last_active_at'  => now(),
                'elapsed_seconds' => 0,
            ]);
        } else {
            $existingSession->last_active_at = now();
            $existingSession->save();
        }

        return redirect()->route('ujian.index', $exam->code)
            ->with('success', 'Selamat mengerjakan ujian: ' . $exam->name);
    }

    private function getExamSession(string $examCode)
    {
        $exam = Exam::where('code', $examCode)->firstOrFail();
        $user = Auth::user();

        $session = ExamSession::where('user_id', $user->id)
            ->where('exam_id', $exam->id)
            ->first();

        return [$exam, $session];
    }

    public function index(string $examCode)
    {
        [$exam, $session] = $this->getExamSession($examCode);
        $user = Auth::user();

        if (!$session) {
            return redirect()->route('ujian.enter-code')
                ->withErrors(['code' => 'Anda belum memiliki sesi untuk ujian ini.']);
        }

        if ($session->isFinished()) {
            return redirect()->route('ujian.hasil', $examCode);
        }

        if ($session->isTimeUp()) {
            return redirect()->route('ujian.selesai', $examCode)
                ->with('warning', 'Waktu ujian Anda telah habis! Silakan submit.');
        }

        $questions = $exam->questions()->orderBy('number')->get();

        $answers = Answer::where('user_id', $user->id)
            ->whereIn('question_id', $questions->pluck('id'))
            ->get()
            ->keyBy('question_id');

        $totalQuestions = $questions->count();
        $totalPoints = $questions->sum('points');

        return view('exam.index', compact('exam', 'session', 'questions', 'answers', 'totalQuestions', 'totalPoints'));
    }

    public function saveAnswer(Request $request, string $examCode)
    {
        $request->validate([
            'question_id'     => 'required|exists:questions,id',
            'answer_text'     => 'nullable|string|max:5000',
            'selected_option' => 'nullable|string|in:A,B,C,D',
        ]);

        [$exam, $session] = $this->getExamSession($examCode);
        $user = Auth::user();

        if (!$session || $session->isFinished()) {
            return response()->json(['success' => false, 'message' => 'Sesi tidak valid.'], 400);
        }

        $question = Question::findOrFail($request->question_id);
        if ($question->exam_id !== $exam->id) {
            return response()->json(['success' => false, 'message' => 'Soal tidak valid.'], 400);
        }

        $data = ['answer_text' => $request->answer_text ?? ''];
        if ($request->filled('selected_option')) {
            $data['selected_option'] = $request->selected_option;
            if (empty($data['answer_text'])) {
                $data['answer_text'] = $request->selected_option;
            }
        }

        $answer = Answer::updateOrCreate(
            ['user_id' => $user->id, 'question_id' => $request->question_id],
            $data
        );

        $answer->estimated_score = $answer->calculateEstimatedScore();
        $answer->save();

        return response()->json([
            'success'         => true,
            'message'         => 'Jawaban disimpan.',
            'estimated_score' => $answer->estimated_score,
        ]);
    }

    public function syncTimer(Request $request, string $examCode)
    {
        $request->validate(['elapsed' => 'required|integer|min:0']);

        [$exam, $session] = $this->getExamSession($examCode);

        if (!$session || $session->isFinished()) {
            return response()->json(['success' => false], 400);
        }

        $maxDuration = $exam->duration_minutes * 60;
        $newElapsed = min((int) $request->elapsed, $maxDuration);

        if ($newElapsed > $session->elapsed_seconds) {
            $session->elapsed_seconds = $newElapsed;
            $session->last_active_at  = now();
            $session->save();
        }

        return response()->json([
            'success'   => true,
            'remaining' => $session->remaining_seconds,
            'time_up'   => $session->isTimeUp(),
        ]);
    }

    public function showFinish(string $examCode)
    {
        [$exam, $session] = $this->getExamSession($examCode);
        $user = Auth::user();

        if (!$session) {
            return redirect()->route('ujian.enter-code');
        }

        if ($session->isFinished()) {
            return redirect()->route('ujian.hasil', $examCode);
        }

        $questionIds = $exam->questions()->pluck('id');
        $answeredCount = Answer::where('user_id', $user->id)
            ->whereIn('question_id', $questionIds)
            ->where(function ($q) {
                $q->whereNotNull('answer_text')->where('answer_text', '!=', '');
            })
            ->count();

        $totalQuestions = $exam->questions()->count();

        return view('exam.finish', compact('exam', 'session', 'answeredCount', 'totalQuestions'));
    }

    public function submitFinish(Request $request, string $examCode)
    {
        $request->validate([
            'expected_grade' => 'required|integer|min:0|max:100',
            'grade_reason'   => 'required|string|min:10|max:1000',
        ], [
            'expected_grade.required' => 'Nilai yang diharapkan wajib diisi.',
            'grade_reason.required'   => 'Penjelasan wajib diisi.',
            'grade_reason.min'        => 'Penjelasan minimal 10 karakter.',
        ]);

        [$exam, $session] = $this->getExamSession($examCode);
        $user = Auth::user();

        if (!$session || $session->isFinished()) {
            return redirect()->route('ujian.hasil', $examCode);
        }

        $questionIds = $exam->questions()->pluck('id');
        $answers = Answer::with('question')
            ->where('user_id', $user->id)
            ->whereIn('question_id', $questionIds)
            ->get();

        $totalEstimated = $answers->sum('estimated_score');
        $maxTotal       = $exam->questions()->sum('points');
        $estimatedGrade = $maxTotal > 0
            ? round(($totalEstimated / $maxTotal) * 100)
            : 0;

        $session->update([
            'finished_at'     => now(),
            'expected_grade'  => $request->expected_grade,
            'grade_reason'    => $request->grade_reason,
            'estimated_grade' => $estimatedGrade,
        ]);

        return redirect()->route('ujian.hasil', $examCode)
            ->with('success', 'Ujian berhasil dikumpulkan!');
    }

    public function hasil(string $examCode)
    {
        [$exam, $session] = $this->getExamSession($examCode);
        $user = Auth::user();

        if (!$session || !$session->isFinished()) {
            return redirect()->route('ujian.index', $examCode);
        }

        $questionIds = $exam->questions()->pluck('id');
        $answers = Answer::with('question')
            ->where('user_id', $user->id)
            ->whereIn('question_id', $questionIds)
            ->orderBy('question_id')
            ->get();

        return view('exam.hasil', compact('exam', 'session', 'answers'));
    }
}
