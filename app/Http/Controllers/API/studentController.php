<?php

namespace App\Http\Controllers\API;

use App\Models\Student;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;
use PHPUnit\Framework\MockObject\Builder\Stub;

class studentController extends Controller
{
    //
    public function index() {
        $students = Student::all();

        if($students-> count() > 0)
        {
            return response()-> json([
                'Status'=> 200,
                'Students' => $students
            ], 200);
        } else {
            return response()-> json([
                'Status' => 404,
                'Message' => 'No record found'
            ], 404);
        }


        return 'I am here, working!';
    }

    public function store(Request $request) {
        $validator = Validator::make($request->all(), [
            'Name' => ['required', 'max:191'],
            'Email' => ['required', 'email', 'max:191', 'unique:students'],
            'Course' => ['required', 'max:191', 'unique:students'],
            'Phone' => ['required', 'digits:10', 'unique:students'],
        ]);        
        

        if($validator->fails()){
            return response()->json([
                'Status' => 422,
                'Error' => $validator->messages()
            ],422);
        } 
        
        else {

            $student = Student::create([
                'Name' => $request->Name,
                'Email' => $request->Email,
                'Course' => $request->Course,
                'Phone' => $request->Phone,
            ]);
        }

        if($student) {
            return response() -> json([
                'Status' => 200,
                'Message' => 'Student created succesfully!'
            ], 200);
        } else {
            return response() -> json([
                'Status' => 500,
                'Message' => 'Internal server error!'
            ], 500);
        }
    }

    public function show($id){
        $student = Student::find($id);

        if($student){
            return response() -> json([
                'Status' => 200,
                'Student' => $student
            ], 200);
        } else {
            return response() -> json([
                'Status' => 404,
                'Message' => 'No record found for the id!'
            ], 404);
        }
    }

    public function edit($id){
        $student = Student::find($id);

        if($student){
            return response() -> json([
                'Status' => 200,
                'Student' => $student
            ], 200);
        } else {
            return response() -> json([
                'Status' => 404,
                'Message' => 'No record found for the id!'
            ], 404);
        }
    }

    public function update(Request $request, $id) {
        $validator = Validator::make($request->all(), [
            'Name' => ['required', 'max:191'],
            'Email' => ['required', 'email', 'max:191', Rule::unique('students')->ignore($request->id)],
            'Course' => 'required|max:191', // Review the need for 'unique' here
            'Phone' => ['required', 'digits:10', Rule::unique('students')->ignore($request->id)],
        ]);
        

        if($validator->fails()){
            return response()->json([
                'Status' => 422,
                'Error' => $validator->messages()
            ],422);
        } 
        
        else {

            $student = Student::find($id);
        }

        if($student) {

            $student -> update([
                'Name' => $request->Name,
                'Email' => $request->Email,
                'Course' => $request->Course,
                'Phone' => $request->Phone,
            ]);

            return response() -> json([
                'Status' => 200,
                'Message' => 'Student record updated succesfully!'
            ], 200);
        } else {
            return response() -> json([
                'Status' => 404,
                'Error' => 'No such record found!'
            ], 404);
        }
    }

    public function destroy($id){
        $student = Student::find($id);

        if($student){
            $student->delete();
            return response() -> json([
                'Status' => 200,
                'Message' => 'Student record deleted succesfully!'
            ], 200);
        } else {
            return response() -> json([
                'Status' => 404,
                'Error' => 'No such record found!'
            ], 404);
        }
    }
}
