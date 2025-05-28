<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Services\SubjectService;

class SubjectViewController extends Controller
{
    protected $subjectService;

    public function __construct(SubjectService $subjectService)
    {
        $this->subjectService = $subjectService;
    }

    public function index()
    {
        $subjects = $this->subjectService->getAll();
        return view('subjects.index', compact('subjects'));
    }

    public function create()
    {
        return view('subjects.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
        ]);

        $this->subjectService->create($request->only('name'));
        return redirect()->route('subjects.index')->with('success', 'Subject created successfully.');
    }

    public function show($id)
    {

        $subject = $this->subjectService->find($id);

        if (!$subject) {
            return redirect()->route('subjects.index')->withErrors('Subject not found.');
        }

        return view('subjects.show', compact('subject'));
    }

    public function edit($id)
    {

        $subject = $this->subjectService->find($id);

        if (!$subject) {
            return redirect()->route('subjects.index')->withErrors('Subject not found.');
        }

        return view('subjects.edit', compact('subject'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'name' => 'required|string|max:255',
        ]);

        $this->subjectService->update($id, $request->only('name'));
        return redirect()->route('subjects.index')->with('success', 'Subject updated successfully.');
    }

    public function destroy($id)
    {
        $this->subjectService->delete($id);
        return redirect()->route('subjects.index')->with('success', 'Subject deleted successfully.');
    }
}