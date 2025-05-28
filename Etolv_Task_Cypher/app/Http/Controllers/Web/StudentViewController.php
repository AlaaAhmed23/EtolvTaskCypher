<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Services\StudentService;
use App\Services\SchoolService;
use App\Services\SubjectService;
use Symfony\Component\HttpFoundation\StreamedResponse;

class StudentViewController extends Controller
{
    protected $studentService;
    protected $schoolService;
    protected $subjectService;

    public function __construct(StudentService $studentService, SchoolService $schoolService, SubjectService $subjectService)
    {
        $this->studentService = $studentService;
        $this->schoolService = $schoolService;
        $this->subjectService = $subjectService;
    }

    public function index()
    {
        $students = $this->studentService->getAll();
        return view('students.index', compact('students'));
    }

    public function create()
    {
        $schools = $this->schoolService->getAll();
        $subjects = $this->subjectService->getAll();
        return view('students.create', compact('schools', 'subjects'));
    }
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string',
            'school_id' => 'required|int',  
            'subject_ids' => 'nullable|array',
            'subject_ids.*' => 'int',
        ]);
        $this->studentService->create($validated);
        return redirect()->route('students.index')->with('success', 'Student created.');
    }
    public function show($id)
    {
        $student = $this->studentService->getById($id);
        if (!$student) {
            abort(404);
        }

        return view('students.show', compact('student'));
    }
    public function edit($id)
    {
        $student = $this->studentService->getById($id);
        if (!$student) {
            return redirect()->route('students.index')->withErrors('Student not found.');
        }
        $schools = $this->schoolService->getAll();
        $subjects = $this->subjectService->getAll();
        return view('students.edit', compact('student', 'schools', 'subjects'));
    }

    public function update(Request $request, $id)
    {
        
        $data = $request->validate([
            'name' => 'required|string',
            'school_id' => 'required|int',
            'subject_ids' => 'array',
            'subject_ids.*' => 'int', 
        ]);
        
        $data['school_id'] = (int) $data['school_id'] ;
        $data['subject_ids'] = array_map('intval', $data['subject_ids']);
        
        $this->studentService->update($id, $data);    
        return redirect()->route('students.index', $id)
                         ->with('success', 'Student updated successfully.');
    }

    public function destroy($id)
    {
        $this->studentService->delete($id);
        return redirect()->route('students.index')->with('success', 'Student deleted successfully.');
    }

    public function report(Request $request)
    {
        $school = $request->input('school');
        $subject = $request->input('subject');

        if ($request->has('export') && $request->input('export') === 'csv') {
            return $this->exportCsv($school, $subject);
        }
        
        $students = $this->studentService->getStudentsWithSubjectsAndSchool($school, $subject);
        $allSchools = $this->schoolService->getAll();
        $allSubjects = $this->subjectService->getAll();

        return view('students.report', compact('students', 'allSchools', 'allSubjects', 'school', 'subject'));
    
    }

    public function exportCsv($school, $subject)
    {
        $students = $this->studentService->getStudentsWithSubjectsAndSchool($school, $subject);

        $response = new StreamedResponse(function () use ($students) {
            $handle = fopen('php://output', 'w');
            fputcsv($handle, ['Student Name', 'School', 'Subjects']);

            foreach ($students as $student) {
                $subjectNames = collect($student['subjects'])->pluck('name')->join(', ');
                fputcsv($handle, [
                    $student['name'],
                    $student['school']['name'] ?? 'N/A',
                    $subjectNames,
                ]);
            }

            fclose($handle);
        });

        $filename = 'students_report_' . date('Y-m-d_H-i-s') . '.csv';
        $response->headers->set('Content-Type', 'text/csv');
        $response->headers->set('Content-Disposition', "attachment; filename=\"$filename\"");

        return $response;
    }

}