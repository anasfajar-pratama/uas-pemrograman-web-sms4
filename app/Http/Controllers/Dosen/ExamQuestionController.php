<?php

namespace App\Http\Controllers\Dosen;

use App\Http\Controllers\Controller;
use App\Models\Exam;
use App\Models\Question;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

class ExamQuestionController extends Controller
{
    private function getExam(int $examId): Exam
    {
        return Exam::where('created_by', Auth::id())->findOrFail($examId);
    }

    public function index(int $examId)
    {
        $exam = $this->getExam($examId);
        $questions = $exam->questions()->orderBy('number')->get();

        return view('dosen.ujian.soal.index', compact('exam', 'questions'));
    }

    public function create(int $examId)
    {
        $exam = $this->getExam($examId);
        $nextNumber = ($exam->questions()->max('number') ?? 0) + 1;

        return view('dosen.ujian.soal.create', compact('exam', 'nextNumber'));
    }

    public function store(Request $request, int $examId)
    {
        $exam = $this->getExam($examId);

        $request->validate([
            'number'        => 'required|integer|min:1',
            'section'       => 'required|in:teori,logika,coding',
            'type'          => 'required|in:pilihan_ganda,isian,coding',
            'question_text' => 'required|string',
            'answer_key'    => 'required|string',
            'keywords'      => 'nullable|string',
            'points'        => 'required|integer|min:1|max:100',
            'option_a'      => 'required_if:type,pilihan_ganda|nullable|string',
            'option_b'      => 'required_if:type,pilihan_ganda|nullable|string',
            'option_c'      => 'required_if:type,pilihan_ganda|nullable|string',
            'option_d'      => 'required_if:type,pilihan_ganda|nullable|string',
        ], [
            'number.unique' => 'Nomor soal sudah digunakan di ujian ini.',
        ]);

        $data = [
            'exam_id'       => $exam->id,
            'number'        => $request->number,
            'section'       => $request->section,
            'type'          => $request->type,
            'question_text' => $request->question_text,
            'answer_key'    => $request->answer_key,
            'points'        => $request->points ?? 5,
        ];

        if ($request->type === 'pilihan_ganda') {
            $data['options'] = [
                'A' => $request->option_a,
                'B' => $request->option_b,
                'C' => $request->option_c,
                'D' => $request->option_d,
            ];
        }

        if ($request->filled('keywords')) {
            $data['keywords'] = array_map('trim', explode(',', $request->keywords));
        }

        Question::create($data);

        return redirect()->route('dosen.ujian.soal.index', $exam->id)
            ->with('success', 'Soal berhasil ditambahkan.');
    }

    public function edit(int $examId, int $questionId)
    {
        $exam = $this->getExam($examId);
        $question = $exam->questions()->findOrFail($questionId);

        return view('dosen.ujian.soal.edit', compact('exam', 'question'));
    }

    public function update(Request $request, int $examId, int $questionId)
    {
        $exam = $this->getExam($examId);
        $question = $exam->questions()->findOrFail($questionId);

        $request->validate([
            'number'        => 'required|integer|min:1',
            'section'       => 'required|in:teori,logika,coding',
            'type'          => 'required|in:pilihan_ganda,isian,coding',
            'question_text' => 'required|string',
            'answer_key'    => 'required|string',
            'keywords'      => 'nullable|string',
            'points'        => 'required|integer|min:1|max:100',
            'option_a'      => 'required_if:type,pilihan_ganda|nullable|string',
            'option_b'      => 'required_if:type,pilihan_ganda|nullable|string',
            'option_c'      => 'required_if:type,pilihan_ganda|nullable|string',
            'option_d'      => 'required_if:type,pilihan_ganda|nullable|string',
        ]);

        $data = [
            'number'        => $request->number,
            'section'       => $request->section,
            'type'          => $request->type,
            'question_text' => $request->question_text,
            'answer_key'    => $request->answer_key,
            'points'        => $request->points ?? 5,
            'options'       => null,
        ];

        if ($request->type === 'pilihan_ganda') {
            $data['options'] = [
                'A' => $request->option_a,
                'B' => $request->option_b,
                'C' => $request->option_c,
                'D' => $request->option_d,
            ];
        }

        if ($request->filled('keywords')) {
            $data['keywords'] = array_map('trim', explode(',', $request->keywords));
        } else {
            $data['keywords'] = null;
        }

        $question->update($data);

        return redirect()->route('dosen.ujian.soal.index', $exam->id)
            ->with('success', 'Soal berhasil diperbarui.');
    }

    public function destroy(int $examId, int $questionId)
    {
        $exam = $this->getExam($examId);
        $question = $exam->questions()->findOrFail($questionId);
        $question->delete();

        return redirect()->route('dosen.ujian.soal.index', $exam->id)
            ->with('success', 'Soal berhasil dihapus.');
    }

    public function showImport(int $examId)
    {
        $exam = $this->getExam($examId);
        return view('dosen.ujian.import', compact('exam'));
    }

    public function importExcel(Request $request, int $examId)
    {
        $exam = $this->getExam($examId);

        $request->validate([
            'file' => 'required|file|mimes:xlsx,xls,csv|max:5120',
        ], [
            'file.required' => 'File Excel wajib diunggah.',
            'file.mimes'    => 'Format file harus xlsx, xls, atau csv.',
        ]);

        try {
            $spreadsheet = IOFactory::load($request->file('file')->getPathname());
            $sheet = $spreadsheet->getActiveSheet();
            $rows = $sheet->toArray(null, true, true, true);

            // Skip header (row 1)
            array_shift($rows);

            $imported = 0;
            $errors = [];
            $nextNumber = ($exam->questions()->max('number') ?? 0) + 1;

            foreach ($rows as $i => $row) {
                $rowNum = $i + 2; // Excel row number (header is row 1)

                // Skip empty rows
                if (empty($row['A']) && empty($row['D'])) {
                    continue;
                }

                $number  = (int) ($row['A'] ?: $nextNumber++);
                $section = strtolower(trim($row['B'] ?? 'teori'));
                $type    = strtolower(trim($row['C'] ?? 'isian'));
                $text    = trim($row['D'] ?? '');
                $optA    = trim($row['E'] ?? '');
                $optB    = trim($row['F'] ?? '');
                $optC    = trim($row['G'] ?? '');
                $optD    = trim($row['H'] ?? '');
                $answer  = trim($row['I'] ?? '');
                $kwStr   = trim($row['J'] ?? '');
                $points  = (int) ($row['K'] ?? 5);

                // Validate
                if (empty($text)) {
                    $errors[] = "Baris {$rowNum}: Soal kosong";
                    continue;
                }
                if (empty($answer)) {
                    $errors[] = "Baris {$rowNum}: Jawaban kosong";
                    continue;
                }
                if (!in_array($section, ['teori', 'logika', 'coding'])) {
                    $section = 'teori';
                }
                if (!in_array($type, ['pilihan_ganda', 'isian', 'coding'])) {
                    $type = 'isian';
                }
                if ($type === 'pilihan_ganda' && (empty($optA) || empty($optB) || empty($optC) || empty($optD))) {
                    $errors[] = "Baris {$rowNum}: Pilihan ganda harus memiliki opsi A-D";
                    continue;
                }
                if ($type === 'pilihan_ganda' && !in_array(strtoupper($answer), ['A', 'B', 'C', 'D'])) {
                    $errors[] = "Baris {$rowNum}: Jawaban PG harus A/B/C/D";
                    continue;
                }

                $data = [
                    'exam_id'       => $exam->id,
                    'number'        => $number,
                    'section'       => $section,
                    'type'          => $type,
                    'question_text' => $text,
                    'answer_key'    => $type === 'pilihan_ganda' ? strtoupper($answer) : $answer,
                    'points'        => $points ?: 5,
                ];

                if ($type === 'pilihan_ganda') {
                    $data['options'] = ['A' => $optA, 'B' => $optB, 'C' => $optC, 'D' => $optD];
                }

                if (!empty($kwStr)) {
                    $data['keywords'] = array_map('trim', explode(',', $kwStr));
                }

                Question::create($data);
                $imported++;
            }

            $msg = "{$imported} soal berhasil diimport.";
            if (!empty($errors)) {
                $msg .= ' Peringatan: ' . implode('; ', $errors);
            }

            return redirect()->route('dosen.ujian.soal.index', $exam->id)
                ->with('success', $msg);

        } catch (\Exception $e) {
            return back()->withErrors(['file' => 'Gagal membaca file: ' . $e->getMessage()])->withInput();
        }
    }

    public function downloadTemplate(int $examId)
    {
        $exam = $this->getExam($examId);

        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();
        $sheet->setTitle('Template Soal');

        $headers = ['No', 'Section', 'Tipe', 'Soal', 'Opsi A', 'Opsi B', 'Opsi C', 'Opsi D', 'Jawaban', 'Keywords', 'Poin'];
        $sheet->fromArray([$headers], null, 'A1');

        $examples = [
            [1, 'teori', 'pilihan_ganda', 'Apa itu REST API?', 'Arsitektur untuk web service', 'Bahasa pemrograman', 'Framework JavaScript', 'Database', 'A', 'rest,api,web,service', 5],
            [2, 'teori', 'isian', 'Jelaskan konsep MVC', '', '', '', '', 'Model View Controller', 'mvc,model,view,controller', 5],
            [3, 'coding', 'coding', 'Buat function Laravel untuk menampilkan data', '', '', '', '', 'Route::get...', 'route,get,controller', 5],
        ];
        $sheet->fromArray($examples, null, 'A2');

        $headerStyle = [
            'font' => ['bold' => true],
            'fill' => ['fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID, 'startColor' => ['rgb' => 'DBEAFE']],
        ];
        $sheet->getStyle('A1:K1')->applyFromArray($headerStyle);

        foreach (range('A', 'K') as $col) {
            $sheet->getColumnDimension($col)->setAutoSize(true);
        }

        $filename = "template_soal_{$exam->code}.xlsx";

        return response()->streamDownload(function () use ($spreadsheet) {
            $writer = new Xlsx($spreadsheet);
            $writer->save('php://output');
        }, $filename, [
            'Content-Type' => 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
        ]);
    }
}
