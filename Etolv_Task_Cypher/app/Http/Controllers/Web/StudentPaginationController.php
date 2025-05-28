<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Services\StudentService;
use Illuminate\Http\Request;

class StudentPaginationController extends Controller
{
    protected $studentService;

    public function __construct(StudentService $studentService)
    {
        $this->studentService = $studentService;
    }

    public function index(Request $request)
    {
        $result = $this->studentService->paginate([
        'per_page' => $request->per_page ?? 5,
        'page' => $request->page ?? 1,
        'filters' => [ 'search' => $request->search ]
        ]);
        return view('students.paginated-index', [
        'students' => $result['data'],
        'pagination' => [
            'total' => $result['total'],
            'per_page' => $result['per_page'],
            'current_page' => $result['current_page']
        ]
        ]);
        
        }
}