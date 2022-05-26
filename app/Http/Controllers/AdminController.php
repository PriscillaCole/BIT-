<?php

namespace App\Http\Controllers;

use App\Models\Admin;
use App\Models\User;
use App\Models\Course;
use App\Models\Announcement;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class AdminController extends Controller
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
        $this->authorize('viewAny', Admin::class);
        
        $admins = Admin::all();
        return view('admin.index', compact('admins'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //Return form to create admin
        return view('admin.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //Store Admin
        //Store admin details
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

        $admin = Admin::create([
            'user_id' => $user->id,
        ]);
         // Add activity logs
         $userlog = Auth::user();
         activity('admin')
         ->performedOn($admin)
         ->causedBy($userlog)
         //->withProperties(['customProperty' => 'customValue'])
         ->log('admin saved by ' . $userlog->name); 

        return redirect()->route('admin.create');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Admin  $admin
     * @return \Illuminate\Http\Response
     */
    public function show(Admin $admin)
    {
        $students = User::where('role', 'Student')->get();
        $accountants = User::where('role', 'Accountant')->get();
        $courses = Course::all();
        $announcements = Announcement::latest()->get();
        return view('admin.show', compact('students','accountants','courses', 'announcements'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Admin  $admin
     * @return \Illuminate\Http\Response
     */
    public function edit(Admin $admin)
    {
        return view('admin.edit', compact('admin'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Admin  $admin
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Admin $admin)
    {
        $admin->user->name = $request->input('name');
        $admin->user->email = $request->input('email');
        $admin->user->phone_1 = $request->input('phone_1');
        $admin->user->phone_2 = $request->input('phone_2');
        $admin->user->gender = $request->input('gender');
        $admin->user->religion = $request->input('religion');
        $admin->user->marital_status = $request->input('marital_status');
        $admin->user->spouse_name = $request->input('spouse_name');
        $admin->user->spouse_contact = $request->input('spouse_contact');
        $admin->user->disability = $request->input('disability');
        $admin->user->nature_of_disability = $request->input('nature_of_disability');
        $admin->user->father_name = $request->input('father_name');
        $admin->user->father_contact = $request->input('father_contact');
        $admin->user->mother_name = $request->input('mother_name');
        $admin->user->mother_contact = $request->input('mother_contact');

        if($request->file('file')) {
            $old_image = public_path('images').'/'.$admin->user->profileImage;
            if (file_exists($old_image) & $admin->user->profileImage != 'default.jpg') {
                unlink($old_image);
            }
            $image = $request->file('file');
            $imageName = time().'.'.$image->extension();
            $image->move(public_path('images'),$imageName);
            $admin->user->profileImage = $imageName;
        }

        $admin->user->update();

         

        $admin->update();

        // Add activity logs
        $userlog = Auth::user();
        activity('admin')
        ->performedOn($admin)
        ->causedBy($userlog)
        //->withProperties(['customProperty' => 'customValue'])
        ->log('admin details updated by ' . $userlog->name); 

        return redirect()->route('admin.index');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Admin  $admin
     * @return \Illuminate\Http\Response
     */
    public function destroy(Admin $admin)
    {
        $admin->user->delete();
        $admin->delete();

        return redirect()->route('admin.index');
    }
}
