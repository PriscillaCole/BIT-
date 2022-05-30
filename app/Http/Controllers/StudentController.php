<?php

namespace App\Http\Controllers;

use App\Models\Student;
use App\Models\Registration;
use App\Models\Course;
use App\Models\Marks;
use App\Models\Payment;
use App\Models\Course_unit;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use Database\Seeders\SuperUserSeeder;
use Illuminate\Http\Request;
use Carbon\Carbon;
use App\Helper\Helper;
use Auth;

class StudentController extends Controller
{
    public function __construct() {
        $this->middleware('auth');
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $this->authorize('viewAny', Student::class);

        #$intake_students = Student::where('academic_year','2021/2022')
        #    ->where('intake', 'May')->get()->count();

        //dd(substr('2021/2022', 2, 2));
        $students = Student::all();
        return view('students.index', compact('students'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $this->authorize('create', Student::class);
        //create a new students
        $courses = Course::all();
        return view('students.create', compact('courses'));
    }

    public function Stud_marks(Student $student)
    {
        // $this->authorize('view', $student);

        //Show Student
        $studentd=Student::where('user_id', auth()->user()->id)->first();

        $registrations = Registration::where('student_id', $studentd->id)->first();
        $Course = Course::where(['id'=>$studentd->course_id])->first();
        $courses = Course_unit::where(['course_code'=>$Course->code])->get();
        $marks = Marks::all();
        $stud_marks = Marks::where(['studentID'=>$studentd->studentID])->get();

        // dd($stud_marks,$courses);
        return view('students.marks', compact('student','marks','stud_marks', 'registrations','Course','studentd','courses'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //Store Student
        // dd($request->all());

        // Form validation
      $this->validate($request, [
        'gender' => 'required',
        'phone_1' => 'required|regex:/^([0-9\s\-\+\(\)]*)$/|min:10',
        'phone_2' => 'required|regex:/^([0-9\s\-\+\(\)]*)$/|min:10',
        'disability'=>'required',
        'intake' => 'required',
        'delivery'=> 'required',
        'marital_status'=> 'required',
        'sponsorship'=> 'required',
     ]);

       $course_id = Course::firstWhere('name', $request->input('course'))->id;

       $image = $request->file('file');
        if ($image == null) {
            $imageName = 'default.jpg';
        } else {
            $imageName = time().'.'.$image->extension();
            $image->move(public_path('images'),$imageName);
        }

        $user = User::create([
            'name' => $request->input('name'),
            'email' => $request->input('email'),
            'postal'=>$request->input('postal'),
            'Town'=>$request->input('Town'),
            'nationality'=>$request->input('nationality'),
            'country'=>$request->input('country'),
            'district'=>$request->input('district'),
            'phone_1' => $request->input('phone_1'),
            'phone_2' => $request->input('phone_2'),
            'gender' => $request->input('gender'),
            'date_of_birth' => $request->input('date_of_birth'),
            'religion' => $request->input('religion'),
            'marital_status'=> $request->input('marital_status'),
            'spouse_name' => $request->input('spouse_name'),
            'spouse_contact' => $request->input('spouse_contact'),
            'disability'  => $request->input('disability'),
            'nature_of_disability' => $request->input('nature_of_disability'),
            'role' => $request->input('role'),
            'father_name' => $request->input('father_name'),
            'father_contact' => $request->input('father_contact'),
            'mother_name' => $request->input('mother_name'),
            'mother_contact' => $request->input('mother_contact'),
            'password' => Hash::make($request->input('password')),
            'profileImage' => $imageName,

        ]);

        $user_id =  $user->id;
        $intake = $request->intake;
        $course_id = $course_id;
        $optional_course = $request->optional_course;
        $delivery = $request->delivery;
        $sponsorship = $request->sponsorship;

        //Save Student Details
        $student = new Student();
        $student->user_id = $user_id;
        $student->intake = $intake;
        $student->academic_year = (new StudentController)->semster($student)[0];
        $student->course_id = $course_id;
        $student->optional_course = $optional_course;
        $student->delivery = $delivery;
        $student->sponsorship = $sponsorship;
       //$student->studentID = Helper::IDGenerator(new Student, 'studentID', 2, 'BIT');
        $student->studentID = (new StudentController)->studentID($student);
        $student->save();
         // Add activity logs
         $stud_id = Student::latest()->first();
         // dd($stud_id,$user_id,$stud_id->user_id,User::latest()->first());
         $user_studentid = User::find($stud_id->user_id);
         $user_studentid->studentID=$stud_id->studentID;
         $user_studentid->update();
         $userlog = Auth::user();
         activity('student')
         ->performedOn($student)
         ->causedBy($userlog)
         //->withProperties(['customProperty' => 'customValue'])
         ->log('student details saved by ' . $userlog->name);

        $registration = Registration::create([
            'student_id' => $student->id,
            'academic_year' => $student->academic_year,
            'semster' => 1,
        ]);
         // Add activity logs
         $userlog = Auth::user();
         activity('registration')
         ->performedOn($registration)
         ->causedBy($userlog)
         //->withProperties(['customProperty' => 'customValue'])
         ->log('registration updated by ' . $userlog->name);

       /* $payment = Payment::create([
            'registration_id' =>$registration->id,
            'amount' => 0,
            'course_id' => $registration->student->course->id,
            'accountant_id' => Auth()->user()->id,
            'receipt_id'=> 1,
            'currency'=>'Ugx',
            'semster' => 1,
            'academic_year' => $student->academic_year,
            'studentID'=>$student->studentID,
        ]);*/
         // Add activity logs
         $userlog = Auth::user();
         activity('student')
         ->performedOn($student)
         ->causedBy($userlog)
         //->withProperties(['customProperty' => 'customValue'])
         ->log('first payment made as student details are being saved by ' . $userlog->name);

        return back()->with('student_added','Student record has been saved');

    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Student  $student
     * @return \Illuminate\Http\Response
     */
    public function show(Student $student)
    {
        $this->authorize('view', $student);

        //Show Student
        if (is_null(Payment::where(['studentID'=>$student->studentID])->latest()->first())) {
            # code...
             dd($student,Course::where(['id'=>$student->course_id])->latest()->first()->fees);
           $amt=0;
            $rep="BIT/000";
        }else {
            dd($student);
           $amt=Payment::where(['studentID'=>$student->studentID])->latest()->first()->amount;
           $rep=Payment::where(['studentID'=>$student->studentID])->latest()->first()->receipt_id;
        }
        
        
        $registrations = Registration::where('student_id', $student->id)->get();
       return view('students.show', compact('student', 'registrations','amt','rep'));
    }


    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Student  $student
     * @return \Illuminate\Http\Response
     */
    public function edit(Student $student)
    {
        //Display edit form for students
        return view('students.edit', compact('student'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Student  $student
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Student $student)
    {
        //Update Student Record
        // dd($request->all());
        $id = $student->user_id;
        $user = User::find($request->id);
        $stu=Student::where([ 'user_id'=>$request->id])->first();
        Student::where([ 'user_id'=>$request->id])->update(['course_id'=>$request->course1]);
        $up=Student::where([ 'user_id'=>$request->id])->first();
        // Payment::where([ 'studentID'=>$request->id])->update(['course_id'=>$request->course1)]);
        // dd($stu,Payment::where(['studentID'=>$stu->studentID])->first(),$request->all(),$up);

        $user->name = $request->input('name');
        $user->email = $request->input('email');
        $user->phone_1 = $request->input('phone_1');
        $user->phone_2 = $request->input('phone_2');
        $user->gender = $request->input('gender');
        $user->date_of_birth = $request->input('date_of_birth');
        $user->religion = $request->input('religion');
        $user->marital_status= $request->input('marital_status');
        $user->spouse_name = $request->input('spouse_name');
        $user->spouse_contact = $request->input('spouse_contact');
        $user->disability  = $request->input('disability');
        $user->nature_of_disability = $request->input('nature_of_disability');
        $user->father_name = $request->input('father_name');
        $user->father_contact = $request->input('father_contact');
        $user->mother_name = $request->input('mother_name');
        $user->mother_contact = $request->input('mother_contact');
        $user->Town = $request->input('Town');
         $user->postal = $request->input('postal');
         $user->district = $request->input('district');
         $user->country = $request->input('country');
         $user->nationality = $request->input('nationality');

        if($request->file('file')) {
            $old_image = public_path('images').'/'.$user->profileImage;
            if (file_exists($old_image) & $user->profileImage != 'default.jpg') {
                unlink($old_image);
            }
            $image = $request->file('file');
            $imageName = time().'.'.$image->extension();
            $image->move(public_path('images'),$imageName);
            $user->profileImage = $imageName;
        }

        $user->update();

        $intake = $request->intake;
        $optional_course = $request->optional_course;
        $delivery = $request->delivery;
        $sponsorship = $request->sponsorship;

        $student->intake = $intake;
        $student->optional_course = $optional_course;
        $student->delivery = $delivery;
        $student->sponsorship = $sponsorship;

        $student->update();

         // Add activity logs
         $userlog = Auth::user();
         activity('student')
         ->performedOn($student)
         ->causedBy($userlog)
         //->withProperties(['customProperty' => 'customValue'])
         ->log('student details updated by ' . $userlog->name);


        return redirect()->route('student.show', ['student'=>$student])
            ->with('success', 'Student updated successfully.');
    }


    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Student  $student
     * @return \Illuminate\Http\Response
     */
    public function destroy(Student $student)
    {
        //Delete Student
        $image = public_path('images').'/'.$student->user->profileImage;
        if (file_exists($image) & $student->profileImage != 'default.jpg') {
            unlink($image);
        }
        foreach ($student->registration as $registration) {
            if(!is_null($registration->payment)){
                $registration->payment->delete();
            }
        }

        $student->registration()->delete();
        User::find($student->user_id)->delete();
        $student->delete();

         // Add activity logs
         $userlog = Auth::user();
         activity('student')
         ->performedOn($student)
         ->causedBy($userlog)
         //->withProperties(['customProperty' => 'customValue'])
         ->log('student deleted by ' . $userlog->name);
        return redirect()->route('student.index')->with('success','Student account deleted successfully.');
    }

    public function semster(Student $student)
    {
        $new_year = new Carbon('first day of January');
        $end_of_year = new Carbon('last day of December');
        $current_date = Carbon::now();

        if ($student->intake == 'January') {
            $sem_1 = new Carbon('first day of January');
            $sem_2 = new Carbon('first day of July');
            if ($current_date >= $sem_1 & $current_date < $sem_2) {
                $academic_year = $sem_1->year."/".$sem_2->addYear(1)->year;
                $semster = 1;
                return [$academic_year, $semster];
            } else {
                $academic_year = $current_date->year."/".$current_date->addYear(1)->year;
                $semster = 2;
                return [$academic_year, $semster];
            }

        } else if ($student->intake == 'May') {
            $sem_1 = new Carbon('first day of May');
            $sem_2 = new Carbon('first day of November');
            if ($current_date >= $sem_1 & $current_date < $sem_2) {
                $academic_year = $sem_1->year."/".$sem_1->addYear(1)->year;
                $semster = 1;
                return [$academic_year, $semster];
            } else {
                if ($current_date->month >= $new_year->month) {
                    $academic_year = $current_date->subYear(1)->year."/".$current_date->year;
                } else {
                    $academic_year = $current_date->year."/".$current_date->addYear(1)->year;
                }
                $semster = 2;
                return [$academic_year, $semster];
            }

        } else if ($student->intake == 'September') {
            $sem_1 = new Carbon('first day of September');
            $sem_2 = (new Carbon('first day of February'));
            if ($current_date >= $sem_1 & $current_date < $end_of_year) {
                $academic_year = $sem_1->year."/".$sem_1->addYear(1)->year;
                $semster = 1;
                return [$academic_year, $semster];
            } elseif($current_date->month == 1) {
                $academic_year = $sem_1->subYear(1)->year."/".$sem_1->year;
                $semster = 1;
            } else {
                $academic_year = $sem_2->subYear(1)->year."/".$sem_2->year;
                $semster = 2;
                return [$academic_year, $semster];
            }

        }
    }
    //students unique id


    public function studentID(Student $student)
    {
        //Generating student id
        $model = new Student;
        $trow = 'studentID';
        $prefix = 'BIT';
        $length = 2;
        $count =  $model->count();
        $data = $model::orderBy('id', 'desc')->first();
        error_log($count);

        // error_log(print_r($_REQUEST, true));
        $year = Carbon::now()->format('y');
       
        if(!$data){
            $og_length = $length;
            $last_number = '001';
        }else{
            $code = substr($data->$trow, strlen($prefix)+1);
            $actual_last_number = ((int)$code/1)*1;
            // $last_number_length = strlen($increment_last_number);
            // $og_length = $length - $last_number_length;


            $increment_last_number = $count + 1;
            $last_number = sprintf("%03d", $increment_last_number);
        }

        if ($student->intake == 'January') {
            $month = '01';
        } else if ($student->intake == 'May') {
            $month = '05';
        } else {
            $month = '09';
        }

        $studentID = "BIT/".$year."/".$month."/".$last_number;
        return $studentID;
    }
} 

