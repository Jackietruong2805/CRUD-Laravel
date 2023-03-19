<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Student;

class StudentController extends Controller
{
    public function index(){
        $students = Student::get();
        return view('home', compact('students'));
    }
    public function store(Request $request){
        // Validation
        $request->validate([
            'name' => 'required',
            'email' => 'required|email',
        ]);

        // add data to the table of database
        $student = new Student();
        $student->name = $request->name;
        $student->email = $request->email;
        $student->save();

        // return successfull notification
        return redirect()->route('home')->with('success', "Data is added successfully");
    }
}
