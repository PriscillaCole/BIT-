<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Payment;
use App\Models\PaymentSummary;
use App\Models\Registration;
use App\Models\Student;
use Illuminate\Http\Request;
use App\Models\Finances;
use App\Models\AcademicYear;

use Auth;
use DB;

class PaymentController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $payments = DB::table('payment_summaries')
        ->select(DB::raw("SUM(`payments`.`amount`) as amount"), 'users.name', 'students.studentID','students.course', 'payment_summaries.semester', 'finances.semester_1', 'finances.semester_2', 'academic_years.academic_years', 'payment_summaries.payment_status', 'students.course_id', 'finances.course_id')
        ->join('registrations','payment_summaries.registration_id','=','registrations.id')
        ->join('students', 'registrations.student_id', '=', 'students.id')
        ->join('finances','finances.course_id','=','students.course_id')
        ->join('academic_years', 'finances.academic_year_id', '=', 'academic_years.id')
        ->join('users', 'students.user_id', '=', 'users.id')
        ->join('payments', 'payment_summaries.id', '=', 'payments.payment_summaries_id')
        ->groupBy('users.name', 'students.studentID', 'students.course', 'payment_summaries.semester', 'finances.semester_1', 'finances.semester_2', 'academic_years.academic_years', 'payment_summaries.payment_status', 'students.course_id', 'finances.course_id')
        ->get();

        // $payments = PaymentSummary::select('registrations.*', 'finances.*', 'payment_summaries.*', 'academic_years.academic_years', 'students.studentID', 'users.name' )
        // ->join('registrations', 'registrations.id', '=', 'payment_summaries.registration_id')
        // ->join('finances', 'finances.id', '=', 'payment_summaries.finance_id')
        // ->join('academic_years', 'finances.academic_year_id', '=', 'academic_years.id')
        // ->join('students', 'registrations.student_id', '=', 'students.id')
        // ->join('users', 'students.user_id', '=', 'users.id')->get();

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
        
        $p=Student::where('studentID',$request->studentID)->first();
        $registration = Registration::where('student_id', $p->id)->first();
        $academic_year_id = AcademicYear::where('academic_years', $request->input('academic_year'))->first();

        $finance_id = Finances::where('academic_year_id', $academic_year_id->id)->first();

        $existing_payment=PaymentSummary::where([['registration_id', '=', $registration->id], ['finance_id', '=', $finance_id->id], ['semester', '=', $request->input('semster')]])->first();

        if($existing_payment) { 
            $sum = Payment::where('payment_summaries_id',  $existing_payment->id)->sum('amount');            

            if($request->input('semster') == 'I'){
                $fees =  $finance_id->semester_1;
                
            }else{
                $fees =  $finance_id->semester_2;
            }

            if(intval($sum) + intval($request->input('amount')) <= intval($fees)){
                PaymentSummary::findOrFail($existing_payment->id)->update(['payment_status'=>1]);
                $payment = Payment::create([
                    'amount' => $request->input('amount'),
                    'mode' => $request->input('mode'),
                    'reason' => $request->input('reason'),
                    'currency' => "Ugx",
                    'receipt_id'=>$request->input('receipt_id'),
                    'balance'=>0,
                    'payment_summaries_id'=>$existing_payment->id,
                    'received_by' =>1
                ]);
            }else{
                return back()->with('message', 'Payment for exceeds balance amount');
            }       


        }else {
            
            if($request->input('semster') == 'I'){
                $fees =  $finance_id->semester_1;
                
            }else{
                $fees =  $finance_id->semester_2;
                
            }

            if(intval($fees) > intval($request->input('amount'))){
                $payment_status=0;
            }else{
                $payment_status=1;
            }

            $payment_summary = PaymentSummary::create([
                'registration_id'=>$registration->id,
                'finance_id'=>$finance_id->id,
                'semester'=>$request->input('semster'),
                'date_of_first_payment'=>$request->input('Receipt_Date'),
                'payment_status'=>$payment_status
            ]);

            $payment = Payment::create([
                'amount' => $request->input('amount'),
                'mode' => $request->input('mode'),
                'reason' => $request->input('reason'),
                'currency' => "Ugx",
                'receipt_id'=>$request->input('receipt_id'),
                'balance'=>0,
                'payment_summaries_id'=>$payment_summary->id,
                'received_by' =>1
            ]);
        }

    // }
        


        // Add activity logs
        $userlog = Auth::user();
        activity('payment')
        ->performedOn($payment)
        ->causedBy($userlog)
        //->withProperties(['customProperty' => 'customValue'])
        ->log('payment updated by ' . $userlog->name); 

        
        

        return redirect()->route('payment.index', compact('payment'))->with('message', 'Payment for '.'' .$request->input('amount').''.' saved successfully.');
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

    public function findPaymentDetails(Request $request){
        $query = Registration::select('users.*', 'registrations.*','students.*','payments.*' , 'finances.*')
        ->where('payments.id',$request->id)
        ->join('payments', 'registrations.id', '=', 'payments.registration_id')
        ->join('students', 'registrations.student_id', '=', 'students.id')
        ->join('users', 'students.user_id', '=', 'users.id')
        ->join('finances', 'finances.academic_year', '=', 'students.academic_year')
        ->first();
 
         $data = array(
             'data'  => $query,
            );
 
         return response()->json($data);

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

    public function studentPayment(User $user)
    {
        $student = Student::where('user_id', $user->id)->first();

        $registration =  Registration::where('student_id', $student->id)->first();

        $payments = DB::table('payment_summaries')
        ->select(DB::raw("SUM(`payments`.`amount`) as amount"), 'users.name', 'students.studentID','students.course', 'payment_summaries.semester', 'finances.semester_1', 'finances.semester_2', 'academic_years.academic_years', 'payment_summaries.payment_status', 'students.course_id', 'finances.course_id')
        ->where('registration_id', $registration->id)
        ->join('registrations','payment_summaries.registration_id','=','registrations.id')
        ->join('students', 'registrations.student_id', '=', 'students.id')
        ->join('finances','finances.course_id','=','students.course_id')
        ->join('academic_years', 'finances.academic_year_id', '=', 'academic_years.id')
        ->join('users', 'students.user_id', '=', 'users.id')
        ->join('payments', 'payment_summaries.id', '=', 'payments.payment_summaries_id')
        ->groupBy('users.name', 'students.studentID', 'students.course', 'payment_summaries.semester', 'finances.semester_1', 'finances.semester_2', 'academic_years.academic_years', 'payment_summaries.payment_status', 'students.course_id', 'finances.course_id')
        ->get();

        return view('students.payment', compact('payments'));
    }

    public function findSemesterFees(Request $request){
        $academic_year_id = AcademicYear::where('academic_years', $request->year)->first();
        
        $query=Finances::where([['academic_year_id', '=', $academic_year_id->id], ['course_name', '=', $request->course]])->first();
    
        $data = array(
            'data'  => $query,
           );
		// $p=Course_unit::where('course_code',$request->id)->first();

        
    	return response()->json($data);
	}
}
