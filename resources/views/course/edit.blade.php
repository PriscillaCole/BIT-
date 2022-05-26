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
      <!-- SELECT2 EXAMPLE -->
      <div class="card card-default">
        <div class="card-header">
          <h3 class="card-title">Edit Course</h3>
          @if(session()->has('success'))
              <div class="alert alert-success">
                  {{ session()->get('success') }}
              </div>
          @endif
        </div>
        <!-- /.card-header -->
        <form action="{{ route ('course.update', ['course'=>$course]) }}" method="post">
          @csrf
          @method('PUT')
          <div class="card-body">
            <div class="row">
              <div class="col-md-12">
                <div class="form-group">
                  <label for="name">Course Name:</label>
                  <input type="text" class="form-control" name="name" id="name" value="{{ $course->name }}">
                </div>
                <div class="form-group">
                  <label for="code">Code:</label>
                  <input type="text" class="form-control" name="code" id="code" value="{{$course->code}}">
                  <input type="hidden" value="{{$course->id}}" name="id">
                </div>
                <div class="form-group">
                  <label style="margin-left:; font-size:18px;" for="duration">Duration</label>
                  <div class='row'>

                <div class="col-md-6 form-group">
                  <label for="name">Period</label>
                  <select required class="form-control select2" {{ ($course->Period =='Years') ||($course->Period =='Months') || ($course->Period =='Weeks')|| ($course->Period =='Days')? 'selected' : '' }} placeholder="Select a Semester" name="Period" style="width: 100%;" required>
                      <option value=''> Select Period</option> 
                      <option>Years </option>
                      <option>Months</option>
                      <option>Weeks</option>
                      <option>Days</option>
                  </select>
                </div>
                

                  <div class="col-md-6 form-group">
                  <label for="name">Number:</label>
                  <select required class="form-control select2" placeholder="Select a Semester" name="number" style="width: 100%;" required>
                      <option value=''> Select Number</option> 
                      <option>1</option>
                      <option>2</option>
                      <option>3</option>
                      <option>4</option>
                      <option>5</option>
                      <option>6</option>
                      <option>7</option>
                      <option>8</option>
                      <option>9</option>
                  </select>
                </div>
                
                </div>
                <!-- <div class="form-group">
                  <label for="duration">Duration:</label>
                  <div class="form-check">
                    <input class="form-check-input" type="radio" name="duration" id="duration" value="24" {{ $course->duration == 24 ? 'checked' : ''}}>
                    <label class="form-check-label">2 years</label>
                  </div>
                  <div class="form-check">
                    <input class="form-check-input" type="radio" name="duration" id="duration" value="12" {{ $course->duration == 12 ? 'checked' : ''}}>
                    <label class="form-check-label">1 year</label>
                  </div> -->
                </div>
                <div class="from-group">
                  <label for="fees">Course fees:</label>
                  <input type="number" class="form-control" name="fees" id="fees" value="{{ $course->fees}}">
                </div>
              </div>
              <!--col-->
            </div>
            <!--row-->
            <button class="btn btn-primary" type="submit">Update</button>
            <a style="margin-left:30px;" href="{{url('/course')}}">
            <!-- <button class="btn btn-primary"><i class="fa fa-home mr-1"></i> -->
            Course List
          <!-- </button> -->
        </a>
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