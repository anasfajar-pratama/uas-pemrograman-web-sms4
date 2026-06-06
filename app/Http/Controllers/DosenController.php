<?php

namespace App\Http\Controllers;

use App\Models\Answer;
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

        $totalQuestions  = Question::count();
        $totalMahasiswa  = $mahasiswas->count();
        $sudahSelesai    = $mahasiswas->filter(fn($m) => $m->examSession?->isFinished())->count();
        $sedangUjian     = $mahasiswas->filter(fn($m) => $m->examSession && !$m->examSession->isFinished())->count();
        $belumMulai      = $mahasiswas->filter(fn($m) => !$m->examSession)->count();

        return view('dosen.dashboard', compact(
            'mahasiswas',
            'totalQuestions',
            'totalMahasiswa',
            'sudahSelesai',
            'sedangUjian',
            'belumMulai'
        ));
    }

    public function detailMahasiswa(int $id)
    {
        $mahasiswa = User::where('role', 'mahasiswa')->findOrFail($id);
        $session   = $mahasiswa->examSession;

        $answers = Answer::with('question')
            ->where('user_id', $id)
            ->orderBy('question_id')
            ->get()
            ->keyBy('question_id');

        $questions = Question::orderBy('number')->get();

        $totalEstimated = $answers->sum('estimated_score');
        $maxTotal       = Question::sum('points');

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

        $questions = Question::orderBy('number')->get();

        $headers = [
            'Content-Type'        => 'text/csv; charset=UTF-8',
            'Content-Disposition' => 'attachment; filename="hasil_uas_' . date('Ymd_His') . '.csv"',
        ];

        $callback = function () use ($mahasiswas, $questions) {
            $file = fopen('php://output', 'w');
            // BOM UTF-8 untuk Excel
            fputs($file, "\xEF\xBB\xBF");

            // Header
            $header = ['No', 'NIM', 'Nama', 'Status', 'Waktu (menit)', 'Estimasi Nilai', 'Nilai Harapan', 'Alasan'];
            foreach ($questions as $q) {
                $header[] = "Soal {$q->number}";
            }
            fputcsv($file, $header);

            // Data
            $no = 1;
            foreach ($mahasiswas as $mhs) {
                $session = $mhs->examSession;
                $row = [
                    $no++,
                    $mhs->nim,
                    $mhs->name,
                    $session ? ($session->isFinished() ? 'Selesai' : 'Belum Selesai') : 'Belum Mulai',
                    $session ? round($session->elapsed_seconds / 60, 1) : '-',
                    $session?->estimated_grade ?? '-',
                    $session?->expected_grade  ?? '-',
                    $session?->grade_reason    ?? '-',
                ];

                $answerMap = $mhs->answers->keyBy('question_id');
                foreach ($questions as $q) {
                    $row[] = $answerMap[$q->id]?->answer_text ?? '';
                }

                fputcsv($file, $row);
            }

            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }
}
