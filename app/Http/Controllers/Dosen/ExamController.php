<?php

namespace App\Http\Controllers\Dosen;

use App\Http\Controllers\Controller;
use App\Models\Exam;
use App\Models\ExamSession;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ExamController extends Controller
{
    public function index()
    {
        $exams = Exam::where('created_by', Auth::id())
            ->withCount(['questions', 'sessions'])
            ->latest()
            ->get();

        return view('dosen.ujian.index', compact('exams'));
    }

    public function create()
    {
        return view('dosen.ujian.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name'             => 'required|string|max:200',
            'duration_minutes' => 'required|integer|min:1|max:600',
            'starts_at'        => 'nullable|date',
            'ends_at'          => 'nullable|date|after:starts_at',
        ], [
            'name.required'             => 'Nama ujian wajib diisi.',
            'duration_minutes.required' => 'Durasi wajib diisi.',
            'duration_minutes.min'      => 'Durasi minimal 1 menit.',
            'duration_minutes.max'      => 'Durasi maksimal 600 menit.',
            'ends_at.after'             => 'Waktu berakhir harus setelah waktu mulai.',
        ]);

        $exam = Exam::create([
            'name'             => $request->name,
            'code'             => Exam::generateCode(),
            'duration_minutes' => $request->duration_minutes,
            'is_active'        => true,
            'created_by'       => Auth::id(),
            'starts_at'        => $request->starts_at,
            'ends_at'          => $request->ends_at,
        ]);

        return redirect()->route('dosen.ujian.show', $exam->id)
            ->with('success', "Ujian \"{$exam->name}\" berhasil dibuat! Kode ujian: {$exam->code}");
    }

    public function show(int $id)
    {
        $exam = Exam::where('created_by', Auth::id())->findOrFail($id);

        $sessions = ExamSession::where('exam_id', $exam->id)
            ->with('user')
            ->orderBy('started_at', 'desc')
            ->get();

        $totalParticipants = $sessions->count();
        $finished          = $sessions->filter(fn($s) => $s->isFinished())->count();
        $inProgress        = $sessions->filter(fn($s) => !$s->isFinished())->count();
        $avgGrade          = $sessions->whereNotNull('estimated_grade')->avg('estimated_grade') ?? 0;

        $questions = $exam->questions()->orderBy('number')->get();

        return view('dosen.ujian.show', compact('exam', 'sessions', 'totalParticipants', 'finished', 'inProgress', 'avgGrade', 'questions'));
    }

    public function edit(int $id)
    {
        $exam = Exam::where('created_by', Auth::id())->findOrFail($id);
        return view('dosen.ujian.edit', compact('exam'));
    }

    public function update(Request $request, int $id)
    {
        $exam = Exam::where('created_by', Auth::id())->findOrFail($id);

        $request->validate([
            'name'             => 'required|string|max:200',
            'duration_minutes' => 'required|integer|min:1|max:600',
            'starts_at'        => 'nullable|date',
            'ends_at'          => 'nullable|date|after:starts_at',
        ]);

        $exam->update([
            'name'             => $request->name,
            'duration_minutes' => $request->duration_minutes,
            'starts_at'        => $request->starts_at,
            'ends_at'          => $request->ends_at,
        ]);

        return redirect()->route('dosen.ujian.show', $exam->id)
            ->with('success', 'Ujian berhasil diperbarui.');
    }

    public function destroy(int $id)
    {
        $exam = Exam::where('created_by', Auth::id())->findOrFail($id);
        $name = $exam->name;
        $exam->delete();

        return redirect()->route('dosen.ujian.index')
            ->with('success', "Ujian \"{$name}\" berhasil dihapus.");
    }

    public function toggleActive(int $id)
    {
        $exam = Exam::where('created_by', Auth::id())->findOrFail($id);
        $exam->update(['is_active' => !$exam->is_active]);

        $status = $exam->is_active ? 'diaktifkan' : 'dinonaktifkan';
        return back()->with('success', "Ujian berhasil {$status}.");
    }
}
