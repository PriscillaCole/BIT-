<?php

namespace App\Http\Controllers;

use App\Models\Course;
use Illuminate\Http\Request;
use Auth;

class CourseController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $courses = Course::all();
        return view('course.index', compact('courses'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('course.create');
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
        $course = Course::create([
            'name' => $request->input('name'),
            'code' => $request->input('code'),
            'Period' => $request->input('Period'),
            'LevelOfStudy' => $request->input('LevelOfStudy'),
            'duration' => $request->input('number'),
            'fees' => $request->input('fees'),
        ]);

         // Add activity logs
         $userlog = Auth::user();
         activity('course')
         ->performedOn($course)
         ->causedBy($userlog)
         //->withProperties(['customProperty' => 'customValue'])
         ->log('course saved by ' . $userlog->name);

        return redirect()->route('course.create')->with('message','Course record has been saved');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Course  $course
     * @return \Illuminate\Http\Response
     */
    public function show(Course $course)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Course  $course
     * @return \Illuminate\Http\Response
     */
    public function edit(Course $course)
    {
        return view('course.edit', compact('course'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Course  $course
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request)
    {
        // dd($request->all());
        $course = Course::find($request->id);
        $course->name = $request->input('name');
        $course->code = $request->input('code');
        $course->duration = $request->input('number');
        $course->Period= $request->input('Period');
        $course->fees = $request->input('fees');

        $course->update();


         // Add activity logs
         $userlog = Auth::user();
         activity('course')
         ->performedOn($course)
         ->causedBy($userlog)
         //->withProperties(['customProperty' => 'customValue'])
         ->log('course updated by ' . $userlog->name);

        return redirect()->back()->with('success', 'Course updated successfully');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Course  $course
     * @return \Illuminate\Http\Response
     */
    public function destroy(Course $course)
    {
        foreach ($course->student as $student) {
            (new StudentController)->destroy($student);
        }
        $course->delete();

         // Add activity logs
         $userlog = Auth::user();
         activity('course')
         ->performedOn($course)
         ->causedBy($userlog)
         //->withProperties(['customProperty' => 'customValue'])
         ->log('course details deleted by ' . $userlog->name);

        return redirect()->route('course.index')->with('success','Course delted successfully.');
    }
}
