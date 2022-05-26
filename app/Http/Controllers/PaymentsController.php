<?php

namespace App\Http\Controllers;

use App\Models\Payment;
use App\Models\Registration;
use App\Models\Student;
use Illuminate\Http\Request;

class PaymentsController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $payments = Payment::all();
        return view('payments.index', compact('payments'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $students = Student::all();
        return view('payments.create', compact('students'));
    }

    public function pay(Student $student)
    {
        return view('payments.create', compact($student));
    }
    public function getstudentID(Request $request){

        
        $data2=Student::where(['stude'=>$request->id])->first();
        // $data1 = User::where(['id'=>$data2->user_id])->first();
        // $data = $data2->concat($data);

    return response()->json($data);

    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        // dd($request->all());
        dd('#' . str_pad($this->id, 8, "0", STR_PAD_LEFT));
        $p=Student::where('studentID',$request->studentID)->first();
        $registration = Registration::where('student_id', $p->id)->first();
        $payment = Payment::create([
            'amount' => $request->input('amount'),
            'academic_year' => $request->input('academic_year'),
            'semster' => $request->input('semster'),
            'currency' => "Ugx",
            'studentID' => $request->input('studentID'),
            'registration_id'=>$request->input('registration_id'),
        'receipt_id'=>$request->input('registration_id'),
        ]);

        return redirect()->route('payments.create',compact('payment'));
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Payment  $payment
     * @return \Illuminate\Http\Response
     */
    public function show(Payment $payment)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Payment  $payment
     * @return \Illuminate\Http\Response
     */
    public function edit(Payment $payment)
    {
        return view('payments.edit', compact('payment'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Payment  $payment
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Payment $payment)
    {
        $payment->amount = $payment->amount + $request->input('amount');
        $payment->update();
        return redirect()->route('payments.index', ['student' => $payment->registration->student]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Payment  $payment
     * @return \Illuminate\Http\Response
     */
    public function destroy(Payment $payment)
    {
        //
    }

    public function studentPayment(Student $student)
    {
        $registrations = $student->registration;

        return view('payments.show', compact('registrations'));
    }
}
