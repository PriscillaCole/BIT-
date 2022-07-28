<?php

namespace App\Http\Controllers;

use App\Models\Registration;
use App\Models\Payment;
use App\Models\Student;
use App\Models\Course_unit;
use App\Models\Course;
use Illuminate\Http\Request;
use App\Http\Controllers\StudentController;
use Carbon\Carbon;

class RegistrationController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $academic_year = (new StudentController)->semster(auth()->user()->student)[0]; //Getting Student's academic year
        $semster = (new StudentController)->semster(auth()->user()->student)[1]; //Getting Student's semster
        $registration = Registration::where('student_id', auth()->user()->student->id)->latest()->first();
        $student = Student::where('id', auth()->user()->student->id)->first();
        return view('registration.index', compact('registration', 'semster', 'academic_year', 'student'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $registration = Registration::create([
            'student_id' => $request->input('student'),
            'academic_year' => (new StudentController)->semster(auth()->user()->student)[0],
            'semster' => (new StudentController)->semster(auth()->user()->student)[1],
        ]);
         // Add activity logs
         $userlog = Auth::user();
         activity('registration')
         ->performedOn($registration)
         ->causedBy($userlog)
         //->withProperties(['customProperty' => 'customValue'])
         ->log('student registration created by ' . $userlog->name); 

        $payment = Payment::create([
            'registration_id' =>$registration->id,
            'amount' => 0,
            'course_id' => $registration->student->course->id,
            'receipt_id' => '',
        ]);

         // Add activity logs
         $userlog = Auth::user();
         activity('payment')
         ->performedOn($payment)
         ->causedBy($userlog)
         //->withProperties(['customProperty' => 'customValue'])
         ->log('payment details saved by ' . $userlog->name); 

        return redirect()->route('registration.index');

    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Registration  $registration
     * @return \Illuminate\Http\Response
     */
    public function show(Registration $registration)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Registration  $registration
     * @return \Illuminate\Http\Response
     */
    public function edit(Registration $registration)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Registration  $registration
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Registration $registration)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Registration  $registration
     * @return \Illuminate\Http\Response
     */
    public function destroy(Registration $registration)
    {
        //
    }

    public function courseUnits(Request $request)
    {
        $course = Course::where('id', $request->course_id)->first();

        $course_units = Course_unit::where(['YearOfStudy'=>$request->year, 'Semester'=>$request->semester, 'course_id'=>$request->course_id])->get();

        $data = array(
            'data'  => $course_units,
           );

    	return response()->json($data);
    }
}
