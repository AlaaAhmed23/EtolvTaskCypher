<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Services\SchoolService;

class SchoolViewController extends Controller
{
    protected $schoolService;

    public function __construct(SchoolService $schoolService)
    {
        $this->schoolService = $schoolService;
    }

    public function index()
    {
        $schools = $this->schoolService->getAll();
        return view('schools.index', compact('schools'));
    }

    public function create()
    {
        return view('schools.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
        ]);

        $this->schoolService->create($request->only('name'));
        return redirect()->route('schools.index')->with('success', 'School created successfully.');
    }

    public function show($id)
    {
        $school = $this->schoolService->getById($id);
        if (!$school) {
            abort(404);
        }
        return view('schools.show', compact('school'));
    }

    public function edit($id)
    {
        $school = $this->schoolService->getById($id);
        if (!$school) {
            abort(404);
        }
        return view('schools.edit', compact('school'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'name' => 'required|string|max:255',
        ]);

        $this->schoolService->update($id, $request->only('name'));
        return redirect()->route('schools.index')->with('success', 'School updated successfully.');
    }

    public function destroy($id)
    {
        $this->schoolService->delete($id);
        return redirect()->route('schools.index')->with('success', 'School deleted successfully.');
    }
}