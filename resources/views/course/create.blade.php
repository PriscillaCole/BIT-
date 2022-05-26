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
          <h3 class="card-title">Register Course</h3>

        </div>
        <!-- /.card-header -->
        <form action="{{ route ('course.store') }}" method="post">
          @csrf
          <div class="card-body">
            <div class="row">
              <div class="col-md-12">

              <div class="form-group">
                  <label for="name">Level Of Study:</label>
                  <select required class="form-control select2" placeholder="Select a Level" name="LevelOfStudy" style="width: 100%;" required>
                      <option value=''> Select Level Of Study</option> 
                      <option>Bachelor</option>
                      <option>Diploma</option>
                      <option>Certificate</option>
                      <option>Short course</option>
                  </select>
                </div>

                <div class='row'>
                <div class=" col-md-6 form-group">
                  <label for="name">Course Name:</label>
                  <input type="text" class="form-control" name="name" id="name" required>
                </div>
                <div class=" col-md-6 form-group">
                  <label for="code"> Course Code:</label>
                  <input type="text" class="form-control" name="code" id="code" required>
                </div>
                </div>
                
                <div class="form-group">
                  <label style="margin-left:; font-size:18px;" for="duration">Duration</label>
                  <div class='row'>

                <div class="col-md-6 form-group">
                  <label for="name">Period</label>
                  <select required class="form-control select2" placeholder="Select a Semester" name="Period" style="width: 100%;" required>
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
                  </select>
                </div>
                
                </div>
                  <!-- <div class="form-check">
                    <input class="form-check-input" type="radio" name="duration" id="duration" value="12">
                    <label class="form-check-label">1 year</label>
                  </div>
                  <div class="form-check">
                    <input class="form-check-input" type="radio" name="duration" id="duration" value="6">
                    <label class="form-check-label">6 months</label>
                  </div>
                  <div class="form-check">
                    <input class="form-check-input" type="radio" name="duration" id="duration" value="4">
                    <label class="form-check-label">4 months</label>
                  </div>
                  <div class="form-check">
                    <input class="form-check-input" type="radio" name="duration" id="duration" value="3">
                    <label class="form-check-label">3 months</label>
                  </div> -->
                </div>
                <div style="margin-bottom:20px" class="from-group">
                  <label for="fees">Course fees:</label>
                  <input type="number" class="form-control" name="fees" id="fees" required>
                </div>
            <button class="btn btn-primary" type="submit">Register</button>
            <button style="margin-left: 50px; margin-top:20px;margin-bottom:15px" class="btn btn-success" type="">
            <a style="color:white;" href="{{ route('course.index') }}"> <i class="fa fa-fw fa-lg fa-eye"></i>View Course</a> </button>

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