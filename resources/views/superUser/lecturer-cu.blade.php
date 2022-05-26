@extends('baselayout')

@section('content')
<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
  <!-- Content Header (Page header) -->
  <section class="content-header">
    
  </section>
 

  <!-- Main content -->
  <section class="content">
    <div class="container-fluid">
    @if(session()->has('message'))
    <div class="alert alert-success">
        {{ session()->get('message') }}
    </div>
@endif
      <!-- SELECT2 EXAMPLE -->
      <div class="card card-default">
        <div class="card-header">
          <h3 class="card-title">Student Marks</h3>

        </div>
        <!-- /.card-header -->
        <?php
        $courses=App\Models\User::where(['role'=>'Lecturer'])->get();
        $course_code=App\Models\Course_unit::all();
        ?>
        <form action="{{ route ('storelecturer_cu') }}" method="post">
          @csrf
          <div class="card-body">
            <div class="row">
              <div class="col-md-12">
              <div class="form-group">
                  <label for="course">Lecturer Email:</label>
                  <select required class="form-control select2" placeholder="Select student ID" name="studentID" style="width: 100%;">
                  
                  <option value=''> Select Lecturer Email</option>  
                  @foreach ($courses as $course)
                      <option>{{ $course->email }}</option>
                    @endforeach
                  </select>
                </div>
                <div class="form-group">
                  <label for="CourseUnitCode">Course Unit Code:</label>
                  <select required class="form-control select2" placeholder="Select Course Unit Code" name="CourseUnitCode" style="width: 100%;">
                  
                  <option value=''> Select Course Unit Code</option>  
                  @foreach ($course_code as $course)
                      <option>{{ $course->course_unit_code }}</option>
                    @endforeach
                  </select>
                </div>


                </div>
               <!-- <div class="row">
               <div class=" col-md-6 from-group">
                  <label for="CH">CH:</label>
                  <input type="number" class="form-control" name="CH" id="CH" required>
                </div>
                <div class=" col-md-6 from-group">
                  <label for="CU">CU:</label>
                  <input type="number" class="form-control" name="CU" id="CU" required>
                </div>
               </div> -->
            <button style="margin-left: 100px; margin-top:20px;margin-bottom:15px" class="btn btn-primary" type="submit"><i class="fa fa-fw fa-lg fa-check-circle"></i>Register</button>
            <button style="margin-left: 50px; margin-top:20px;margin-bottom:15px" class="btn btn-success" type="">
            <a style="color:white;" href="{{ route('lecturers.index') }}"> <i class="fa fa-fw fa-lg fa-eye"></i>View Lecture List</a> </button>

          </div>
          <!-- /.card-body -->
        </form>
        
      </div>
      <!-- /.card -->

    </div>
    <!-- /.container-fluid -->
  </section>
  <!-- /.content -->
</div>
<!-- /.content-wrapper -->
@endsection