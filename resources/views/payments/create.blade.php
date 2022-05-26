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
          <h3 class="card-title">Register Payments</h3>

        </div>
        <!-- /.card-header -->
        <form action="{{ route ('payment.store') }}" method="post">
          @csrf
          <div class="card-body">
            <div class="row">
            <?php
            $student=App\Models\Student::all();
            ?>
              <div class="col-md-12">
                <div class="form-group">
                  <label for="name">Name:</label>
                  <input type="text" class="form-control productname" name="name" id="name" value="{{$student->user->name}}" disabled>
                <div class="form-group">
                  <label for="student_number">Student Numbera:</label>
                  <input type="text" class="form-control" name="student_number" id="student_number" value="20/BIT/003" disabled>
                </div>
                <div class="form-group">
                  <label for="amount">Amount:</label>
                  <input type="number" class="form-control" step="1" min="100000" name="amount" id="amount">
                </div>
                <div class="form-group">
                  <label for="academic_year">Academic Year:</label>
                  <input type="text" class="form-control" name="academic_year" id="academic_year" value="{{session('academic_year')}}" >
                </div>
                <div class="form-group">
                  <label for="semster">Semster:</label>
                  <div class="form-check">
                    <input class="form-check-input" type="radio" name="semster" id="name" value=1 {{ session('semster') == 1 ? 'checked' : ''}} disabled>
                    <label class="form-check-label">I</label>
                  </div>
                  <div class="form-check">
                    <input class="form-check-input" type="radio" name="semster" id="name" value=2 {{ session('semster') == 2 ? 'checked' : ''}} disabled>
                    <label class="form-check-label">II</label>
                  </div>
                </div>
                <div class="form-group">
                  <label for="receipt_id">Receipt ID:</label>
                  <input type="text" class="form-control" name="receipt_id" id="receipt_id">
                </div>
            </div>
            <button class="btn btn-primary" type="submit">Register</button>
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