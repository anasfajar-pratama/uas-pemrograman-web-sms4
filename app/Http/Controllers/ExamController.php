<?php

namespace App\Http\Controllers;

use App\Models\Answer;
use App\Models\ExamSession;
use App\Models\Question;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ExamController extends Controller
{
    public function index()
    {
        $user    = Auth::user();
        $session = $user->examSession;

        // Buat sesi jika belum ada (fallback)
        if (!$session) {
            $session = ExamSession::create([
                'user_id'         => $user->id,
                'started_at'      => now(),
                'last_active_at'  => now(),
                'elapsed_seconds' => 0,
            ]);
        }

        // Redirect jika sudah selesai
        if ($session->isFinished()) {
            return redirect()->route('ujian.hasil');
        }

        // Redirect ke form selesai jika waktu habis
        if ($session->isTimeUp()) {
            return redirect()->route('ujian.selesai')
                ->with('warning', 'Waktu ujian Anda telah habis! Silakan submit.');
        }

        $questions = Question::orderBy('number')->get();

        // Ambil semua jawaban user ini
        $answers = Answer::where('user_id', $user->id)
            ->pluck('answer_text', 'question_id');

        return view('exam.index', compact('session', 'questions', 'answers'));
    }

    /**
     * Simpan atau update satu jawaban (AJAX).
     */
    public function saveAnswer(Request $request)
    {
        $request->validate([
            'question_id' => 'required|exists:questions,id',
            'answer_text' => 'nullable|string|max:5000',
        ]);

        $user    = Auth::user();
        $session = $user->examSession;

        if (!$session || $session->isFinished()) {
            return response()->json(['success' => false, 'message' => 'Sesi tidak valid.'], 400);
        }

        $answer = Answer::updateOrCreate(
            ['user_id' => $user->id, 'question_id' => $request->question_id],
            ['answer_text' => $request->answer_text ?? '']
        );

        // Hitung estimasi skor
        $answer->estimated_score = $answer->calculateEstimatedScore();
        $answer->save();

        return response()->json([
            'success'         => true,
            'message'         => 'Jawaban disimpan.',
            'estimated_score' => $answer->estimated_score,
        ]);
    }

    /**
     * Sync timer dari client (AJAX setiap 30 detik).
     */
    public function syncTimer(Request $request)
    {
        $request->validate([
            'elapsed' => 'required|integer|min:0',
        ]);

        $user    = Auth::user();
        $session = $user->examSession;

        if (!$session || $session->isFinished()) {
            return response()->json(['success' => false], 400);
        }

        $newElapsed = min((int) $request->elapsed, ExamSession::EXAM_DURATION);

        // Hanya update jika nilai baru lebih besar (mencegah manipulasi)
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

    /**
     * Tampilkan halaman form selesai.
     */
    public function showFinish()
    {
        $user    = Auth::user();
        $session = $user->examSession;

        if (!$session) {
            return redirect()->route('ujian.index');
        }

        if ($session->isFinished()) {
            return redirect()->route('ujian.hasil');
        }

        $answeredCount = Answer::where('user_id', $user->id)
            ->whereNotNull('answer_text')
            ->where('answer_text', '!=', '')
            ->count();

        $totalQuestions = Question::count();

        return view('exam.finish', compact('session', 'answeredCount', 'totalQuestions'));
    }

    /**
     * Submit form selesai ujian.
     */
    public function submitFinish(Request $request)
    {
        $request->validate([
            'expected_grade' => 'required|integer|min:0|max:100',
            'grade_reason'   => 'required|string|min:10|max:1000',
        ], [
            'expected_grade.required' => 'Nilai yang diharapkan wajib diisi.',
            'expected_grade.min'      => 'Nilai minimal 0.',
            'expected_grade.max'      => 'Nilai maksimal 100.',
            'grade_reason.required'   => 'Penjelasan wajib diisi.',
            'grade_reason.min'        => 'Penjelasan minimal 10 karakter.',
        ]);

        $user    = Auth::user();
        $session = $user->examSession;

        if (!$session || $session->isFinished()) {
            return redirect()->route('ujian.hasil');
        }

        // Hitung estimasi nilai dari semua jawaban
        $answers = Answer::with('question')
            ->where('user_id', $user->id)
            ->get();

        $totalEstimated = $answers->sum('estimated_score');
        $maxTotal       = Question::sum('points'); // harusnya 100
        $estimatedGrade = $maxTotal > 0
            ? round(($totalEstimated / $maxTotal) * 100)
            : 0;

        $session->update([
            'finished_at'    => now(),
            'expected_grade' => $request->expected_grade,
            'grade_reason'   => $request->grade_reason,
            'estimated_grade'=> $estimatedGrade,
        ]);

        return redirect()->route('ujian.hasil')
            ->with('success', 'Ujian berhasil dikumpulkan!');
    }

    /**
     * Halaman hasil setelah ujian selesai.
     */
    public function hasil()
    {
        $user    = Auth::user();
        $session = $user->examSession;

        if (!$session || !$session->isFinished()) {
            return redirect()->route('ujian.index');
        }

        $answers = Answer::with('question')
            ->where('user_id', $user->id)
            ->orderBy('question_id')
            ->get();

        return view('exam.hasil', compact('session', 'answers'));
    }
}
