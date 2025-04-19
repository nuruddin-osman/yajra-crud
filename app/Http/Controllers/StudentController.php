<?php

namespace App\Http\Controllers;

use App\Models\Student;
use Illuminate\Http\Request;
use Yajra\DataTables\DataTables;


class StudentController extends Controller
{
    public function index()
    {
        return view('students.index');
    }

    public function getStudents(Request $request)
    {
        if ($request->ajax()) {
            $data = Student::latest()->get();
            return DataTables::of($data)
                ->addIndexColumn()
                ->addColumn('action', function ($row) {
                    $btn = '<button data-id="'.$row->id.'" class="btn btn-sm btn-primary editBtn">Edit</button>';
                    $btn .= ' <button data-id="'.$row->id.'" class="btn btn-sm btn-danger deleteBtn">Delete</button>';
                    return $btn;
                })
                ->rawColumns(['action'])
                ->make(true);
        }
    }

    public function store(Request $request)
    {
        $request->validate([
            'name'  => 'required',
            'email' => 'required|email|unique:students,email',
            'phone' => 'required',
        ]);

        Student::create($request->all());
        return response()->json(['success' => 'Student added successfully']);
    }

    public function edit($id)
    {
        $student = Student::findOrFail($id);
        return response()->json($student);
    }

    public function update(Request $request, $id)
    {
        $student = Student::findOrFail($id);

        $request->validate([
            'name'  => 'required',
            'email' => 'required|email|unique:students,email,' . $id,
            'phone' => 'required',
        ]);

        $student->update($request->all());
        return response()->json(['success' => 'Student updated successfully']);
    }

    public function destroy($id)
    {
        Student::destroy($id);
        return response()->json(['success' => 'Student deleted successfully']);
    }
}
