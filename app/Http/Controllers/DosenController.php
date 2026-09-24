<?php

namespace App\Http\Controllers;

use App\Models\Answer;
use App\Models\Exam;
use App\Models\ExamSession;
use App\Models\Question;
use App\Models\User;
use Illuminate\Http\Request;

class DosenController extends Controller
{
    public function dashboard()
    {
        $mahasiswas = User::where('role', 'mahasiswa')
            ->with('examSession')
            ->orderBy('name')
            ->get();

        $totalMahasiswa  = $mahasiswas->count();
        $sudahSelesai    = $mahasiswas->filter(fn($m) => $m->examSession?->isFinished())->count();
        $sedangUjian     = $mahasiswas->filter(fn($m) => $m->examSession && !$m->examSession->isFinished())->count();
        $belumMulai      = $mahasiswas->filter(fn($m) => !$m->examSession)->count();

        return view('dosen.dashboard', compact(
            'mahasiswas',
            'totalMahasiswa',
            'sudahSelesai',
            'sedangUjian',
            'belumMulai'
        ));
    }

    public function detailMahasiswa(Request $request, int $id)
    {
        $mahasiswa = User::where('role', 'mahasiswa')->findOrFail($id);

        $examId = $request->query('exam_id');
        if ($examId) {
            $exam = Exam::findOrFail($examId);
            $session = ExamSession::where('user_id', $id)->where('exam_id', $examId)->first();
        } else {
            $session = $mahasiswa->examSession;
            $exam = $session?->exam;
        }

        $questions = $exam ? $exam->questions()->orderBy('number')->get() : collect();

        $answers = Answer::with('question')
            ->where('user_id', $id)
            ->whereIn('question_id', $questions->pluck('id'))
            ->get()
            ->keyBy('question_id');

        $totalEstimated = $answers->sum('estimated_score');
        $maxTotal       = $questions->sum('points');

        return view('dosen.detail', compact(
            'mahasiswa',
            'session',
            'answers',
            'questions',
            'totalEstimated',
            'maxTotal'
        ));
    }

    public function exportCsv()
    {
        $mahasiswas = User::where('role', 'mahasiswa')
            ->with(['examSession', 'answers.question'])
            ->orderBy('name')
            ->get();

        $exams = Exam::orderBy('name')->get();

        $headers = [
            'Content-Type'        => 'text/csv; charset=UTF-8',
            'Content-Disposition' => 'attachment; filename="hasil_uas_' . date('Ymd_His') . '.csv"',
        ];

        $callback = function () use ($mahasiswas, $exams) {
            $file = fopen('php://output', 'w');
            fputs($file, "\xEF\xBB\xBF");

            $header = ['No', 'NIM', 'Nama'];
            foreach ($exams as $exam) {
                $header[] = "{$exam->name} - Status";
                $header[] = "{$exam->name} - Waktu (menit)";
                $header[] = "{$exam->name} - Estimasi Nilai";
                $header[] = "{$exam->name} - Nilai Harapan";
            }
            fputcsv($file, $header);

            $no = 1;
            foreach ($mahasiswas as $mhs) {
                $row = [$no++, $mhs->nim, $mhs->name];

                foreach ($exams as $exam) {
                    $session = $mhs->examSessions->where('exam_id', $exam->id)->first();
                    if ($session) {
                        $row[] = $session->isFinished() ? 'Selesai' : 'Belum Selesai';
                        $row[] = round($session->elapsed_seconds / 60, 1);
                        $row[] = $session->estimated_grade ?? '-';
                        $row[] = $session->expected_grade ?? '-';
                    } else {
                        $row[] = 'Belum Mulai';
                        $row[] = '-';
                        $row[] = '-';
                        $row[] = '-';
                    }
                }

                fputcsv($file, $row);
            }

            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }
}
