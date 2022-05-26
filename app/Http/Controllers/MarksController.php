<?php

namespace App\Http\Controllers;

use App\Models\Course_unit;
use App\Models\Marks;
use Illuminate\Http\Request;
use Auth;
use App\Models\Student;
use App\Models\Course;
use DB;

class MarksController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $courses = Marks::all();
        return view('marks.index', compact('courses'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $students=Student::all();
        // dd($student);
        return view('marks.create',compact('students'));
    }
    public function createTest(){
        return view('marks.createTest');
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
        if($request->test=='test'){
            $course = Marks::create([
                'studentID' => $request->input('studentID'),
                'test' => $request->input('score'),
                'course_unit_code'=>$request->input('CourseUnitCode'),
            ]);
             $userlog = Auth::user();
             activity('test marks')
             ->performedOn($course)
             ->causedBy($userlog)
             ->log('student test marks saved by ' . $userlog->name);
             return redirect()->back()->with('message','student test mark has been saved');
        }else

        {
           $final=Marks::where(['studentID'=>$request->input('studentID'),'course_unit_code'=>$request->input('CourseUnitCode')])->first();
           $categories =DB::table('marks')->where(['studentID'=>$request->input('studentID'),'course_unit_code'=>$request->input('CourseUnitCode')])->latest()->first();
        //    dd($categories);
           if ($categories) {
               # code...
            //    dd('categories');
               $Exa=((Integer)$request->input('score')/100)*60;
               $Tes=($final['test']/100)*40;
               $toal=$Exa+$Tes;

               // dd($Tes,$Exa,(Integer)$toal);
               $courseh = Marks::where([ 'studentID'=>$request->input('studentID'),'course_unit_code'=>$request->input('CourseUnitCode')])->update(['exam'=>$request->input('score'),'total_score'=>(Integer)$toal]);

           }else{
            // dd($categories);
            $course = Marks::create([
                'studentID' => $request->input('studentID'),
                'exam' => $request->input('score'),
                'course_unit_code'=>$request->input('CourseUnitCode'),
            ]);
             // Add activity logs
         $userlog = Auth::user();
         activity('exam marks')
         ->performedOn($course)
         ->causedBy($userlog)
         ->log('student exam marks saved by ' . $userlog->name);
            // $coursej = Marks::where([ 'studentID'=>$request->input('studentID'),'course_unit_code'=>$request->input('CourseUnitCode')])->update(['exam'=>$request->input('score')]);

           }


    }

        return redirect()->back()->with('message','student Exam mark has been saved');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Course  $course
     * @return \Illuminate\Http\Response
     */
    public function show(marks $course)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Course  $course
     * @return \Illuminate\Http\Response
     */
    public function edit(marks $coursew,$id)
    {
        $course= marks::find($id);
        // dd($course);
        return view('marks.edit', compact('course'));
    }
    public function marksing_u($id)
    {
        $course= marks::find($id);
        // dd($course);
        return view('marks.edit', compact('course'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Course  $course
     * @return \Illuminate\Http\Response
     */
    public function marksing_update(Request $request)
    {
        $course = marks::find($request->input('id'));
        // dd($course,$request->all());/
        if ($request->input('test') && $request->input('exam'))
        {        //     # code...

            $Exa=((Integer)$request->input('exam')/100)*60;
            $Tes=((Integer)$request->input('test')/100)*40;
            $toal=$Exa+$Tes;
            $course->total_score = $toal;
        }
        $course->exam = $request->input('exam');
        $course->test = $request->input('test');

        $course->update();

         // Add activity logs
         $userlog = Auth::user();
         activity('marks')
         ->performedOn($course)
         ->causedBy($userlog)
         //->withProperties(['customProperty' => 'customValue'])
         ->log('student marks updated by ' . $userlog->name);

        return redirect()->route('marks.index')->with('edit_added',$course->studentID.''.''.' Marks updated successfully');
    }

    public function update(Request $request, marks $course)
    {
        // dd($request->all());

        $course->course_name = $request->input('name');
        $course->course_code = $request->input('code');
        $course->L = $request->input('L');
        $course->P = $request->input('P');
        $course->CH = $request->input('CH');
        $course->CU = $request->input('CU');

        $course->update();

         // Add activity logs
         $userlog = Auth::user();
         activity('marks')
         ->performedOn($course)
         ->causedBy($userlog)
         //->withProperties(['customProperty' => 'customValue'])
         ->log('student marks updated by ' . $userlog->name);

        return redirect()->route('marks.index')->with('success', 'Course unit updated successfully');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Course  $course
     * @return \Illuminate\Http\Response
     */
    public function destroy( Request $req, Marks $course)
    {
        // dd($req->all());
        Marks::find($req->id)->delete();
        // foreach ($course->student as $student) {
        //     (new StudentController)->destroy($student);
        // }
        // $course->delete();

         // Add activity logs
         $userlog = Auth::user();
         activity('marks')
         ->performedOn($course)
         ->causedBy($userlog)
         //->withProperties(['customProperty' => 'customValue'])
         ->log('student mark deleted by ' . $userlog->name);

        return redirect()->route('marks.index')->with('success', 'Student mark deleted successfully');
    }
}
