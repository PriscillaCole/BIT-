<?php

namespace App\Http\Controllers;

use App\Models\Student;
use App\Models\Registration;
use App\Models\Course;
use App\Models\Payment;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use App\Models\Course_unit;
use Database\Seeders\SuperUserSeeder;
use Illuminate\Http\Request;
use Carbon\Carbon;
use App\Models\Lecturer;
use App\Models\Announcement;
use App\Models\Lecture_Course_units;
use Auth;

class LecturerController extends Controller
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

        $students = Lecturer::all();
        // dd($students);
        return view('lecturers.index', compact('students'));
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
        $courses = Course_unit::all();
        return view('lecturers.create', compact('courses'));
    }

    public function my_courses(){
        $asign=Lecture_Course_units::where(['user_id'=>auth()->user()->id])->get();
        $coursesunit = Course_unit::all();
        $courses=Course::all();
        return view('lecturers.Lecture_courses', compact('coursesunit','asign','courses'));
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
        $delivery = $request->delivery;
        $sponsorship = $request->sponsorship;

        //Save Student Details
        $student = new Lecturer();
        $student->user_id = $user_id;
        $student->intake = $intake;
        // $student->academic_year = (new StudentController)->semster($student)[0];
        $student->delivery = $delivery;
        $student->EmployID = "BIT" .'/'.rand(2,'9999');
        $student->save();

        //Save assigned course unit Details
        $lecturer = new Lecture_Course_units();
        $lecturer->user_id = $user_id;
        $lecturer->course_unit_code = $request->course;
        $lecturer->email = $request->email;
        $lecturer->save();

         // Add activity logs
         $userlog = Auth::user();
         activity('Lecturer')
         ->performedOn($student)
         ->causedBy($userlog)
         //->withProperties(['customProperty' => 'customValue'])
         ->log('Lecturer details saved by ' . $userlog->name);


        return back()->with('student_added','Lecture record has been saved');

    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Lecturer  $student
     * @return \Illuminate\Http\Response
     */
    public function show(Lecturer $accountant)
    {

        $students = User::where('role', 'Lecturer')->get();
        $accountants = User::where('role', 'Accountant')->get();
        $courses = Course::all();
        $announcements = Announcement::latest()->get();
        return view('lecturers.show', compact('students','accountants','courses', 'announcements'));
    }


    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Lecturer  $student
     * @return \Illuminate\Http\Response
     */
    public function edit(Lecturer $student,$id)
    {
        //Display edit form for students

        $lect = Lecturer::findOrFail($id);
        $admin = User::findOrFail($lect['user_id']);
        // dd($id,$admin);
        return view('lecturers.edit', compact('student','admin'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Lecturer  $student
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Lecturer $student)
    {
        //Update Lecturer Record
        $id = $student->user_id;
        $user = User::find($request->id);
        // dd($request->all());

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


         // Add activity logs
         $userlog = Auth::user();
         activity('lecturer')
         ->performedOn($user)
         ->causedBy($userlog)
         //->withProperties(['customProperty' => 'customValue'])
         ->log('lecturer details updated by ' . $userlog->name);



        return redirect()->back()
            ->with('success', 'Lecturer updated successfully.');
    }


    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Lecturer  $student
     * @return \Illuminate\Http\Response
     */
    public function destroy(Lecturer $lecturer)
    {
        //Delete Lecturer
        // dd($lecturer->user);
        $image = public_path('images').'/'.$lecturer->user->profileImage;
        if (file_exists($image) & $lecturer->profileImage != 'default.jpg') {
            unlink($image);
        }
        $lecturer->delete();
        $lecturer->user->delete();


        return redirect()->route('lecturers.index')->with('success','Lecturer account deleted successfully.');
    }

    public function Lecture_courses()
    {
        $courses = Course::all();
        return view('course.index', compact('courses'));
    }

    public function semster(Lecturer $student)
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


    public function studentID(Lecturer $student)
    {
        //Generating student id
        //Count the number of a students in a given academic year and intake
        $intake_students = Lecturer::where('academic_year',$student->academic_year)
            ->where('intake', $student->intake)->get()->count();

        $student_number = sprintf("%03d", $intake_students + 1);

        $year = substr($student->academic_year, 2, 2);

        if ($student->intake == 'January') {
            $month = '01';
        } else if ($student->intake == 'May') {
            $month = '05';
        } else {
            $month = '09';
        }

        $studentID = "BIT/".$year."/".$month."/".$student_number;
        return $studentID;
    }
}
