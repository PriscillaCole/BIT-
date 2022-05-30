<?php

namespace App\Http\Controllers;

use App\Models\Payment;
use App\Models\Registration;
use App\Models\Student;
use Illuminate\Http\Request;
use Auth;

class PaymentController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $payments = Payment::all();
        return view('payment.index', compact('payments'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $students = Student::all();
        return view('payment.create', compact('students'));
    }

    public function pay(Student $student)
    {
        return view('payment.create', compact($student));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request,Payment $payment)
    {
        $this->validate($request, [
        'receipt_id' => 'required|unique:payments',
        
     ]);
        
        // dd('BIT' . str_pad(1, 8, "0", STR_PAD_LEFT));
        $rec='BIT' . str_pad(1, 8, "0", STR_PAD_LEFT);
        $p=Student::where('studentID',$request->studentID)->first();
        $registration = Registration::where('student_id', $p->id)->first();
        $pay=Payment::orderBy('created_at','DESC')->first();
        // dd($registration);
        if ($pay) {
            $rec=str_pad($pay->receipt_id + 1, 8, "0", STR_PAD_LEFT);//'#'.
        }else{
            $rec= str_pad(1, 8, "0", STR_PAD_LEFT);//'BIT' .
        }
        // dd($registration->id);
        $courseh = Payment::where([ 'studentID'=>$request->input('studentID'),'academic_year'=>$request->input('academic_year'),'semster' => $request->input('semster')])->first();
        // ->update(['exam'=>$request->input('score'),'total_score'=>(Integer)$toal]);
        if($courseh) {
            # code...
            $courseh->amount=$courseh->amount + $request->input('amount');
        $courseh->update();
        // dd($request->all(),$courseh->amount,$coursehamount); 'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
        }else{
            
        $payment = Payment::create([
            'amount' => $request->input('amount'),
            'academic_year' => $registration->academic_year,
            'semster' => $request->input('semster'),
            'currency' => "Ugx",
            'studentID' => $request->input('studentID'),
            'registration_id'=>$registration->id,
        'receipt_id'=>$request->input('receipt_id'),
        ]);

    }
        


        // Add activity logs
        $userlog = Auth::user();
        activity('payment')
        ->performedOn($payment)
        ->causedBy($userlog)
        //->withProperties(['customProperty' => 'customValue'])
        ->log('payment updated by ' . $userlog->name); 

        
        

        return redirect()->route('payment.create', compact('payment'))->with('message', 'Payment for '.'' .$request->input('studentID').''.' saved successfully.');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Payment  $payment
     * @return \Illuminate\Http\Response
     */
    public function show(Payment $payment)
    {
        
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Payment  $payment
     * @return \Illuminate\Http\Response
     */
    public function edit(Payment $payment)
    {
        return view('payment.edit', compact('payment'));
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
        // dd($request->all(),$payment->all());
        $payment->amount = $payment->amount + $request->input('amount');
        $payment->update();

         // Add activity logs
         $userlog = Auth::user();
         activity('payment')
         ->performedOn($payment)
         ->causedBy($userlog)
         //->withProperties(['customProperty' => 'customValue'])
         ->log('payment updated by ' . $userlog->name); 
        return redirect()->route('payment.index', ['student' => $payment->registration->student]);
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

        return view('student.show', compact('registrations'));
    }
}
