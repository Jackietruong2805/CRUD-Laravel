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
            'photo' => 'required|image|mimes:jpg,png,gif,jpeg|max:2048',
            'name' => 'required',
            'email' => 'required|email',
        ]);

        $ext = $request->file('photo')->extension();

        $final_name = date('YmdHis').'.'.$ext;
       
        $request->file('photo')->move(public_path('uploads/'), $final_name);

        // add data to the table of database
        $student = new Student();
        $student->photo = $final_name;
        $student->name = $request->name;
        $student->email = $request->email;
        $student->save();

        // return successfull notification
        return redirect()->route('home')->with('success', "Data is added successfully");
    }

    public function edit($id){
       $student = Student::where('id', $id)->first();

       return view('edit', compact('student'));
    }

    public function update(Request $request, $id){
        $student = Student::where('id', $id)->first();
        $request->validate([
            'name' => 'required',
            'email' => 'required|email',
        ]);
        if($request->hasFile('photo')){
            $request->validate([
                'photo' => 'image|mimes:jpg,jpeg,png,gif|max:2048'
            ]);
            
            if(file_exists(public_path('uploads/'.$student->photo)) AND !empty($student->photo)){
                unlink(public_path('uploads/'.$student->photo));
            };

            $ext = $request->file('photo')->extension();
            $final_name = date('YmdHis').'.'.$ext;

            $request->file('photo')->move(public_path('uploads/'), $final_name);
            $student->photo = $final_name;
            
        }
        $student->name = $request->name;
        $student->email = $request->email;
        $student->update();

        return redirect()->route('home')->with('success', "Data is updated successfully");
    }

    public function delete($id){
        $student = Student::where('id', $id)->first();
        $student->delete();
        return redirect()->back()->with('success', "Data is deleted successfully");
    }
}
