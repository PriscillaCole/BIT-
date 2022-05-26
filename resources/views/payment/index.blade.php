@extends('baselayout')

@section('content')
    <!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <?php
  $payment= App\Models\Payment::all();
  ?>
    <section class="content-header">
      <div class="container-fluid">
        <div class="row mb-2">
          <div class="col-sm-6">
          <div class="col-sm-6">
            <a href="{{ route('payment.create')}}" ><button class="btn btn-primary">Make payment</button></a>
          
          </div>         
          </div>
          <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
            </ol>
          </div>
        </div>
      </div><!-- /.container-fluid -->
    </section>

    <!-- Main content -->
    <section class="content">
      <div class="container-fluid">
        <div class="row">
          <div class="col-12">
            <div class="card">
              <div class="card-header">
                <h3 class="card-title"> Students Payment List</h3>
              </div>
              <!-- /.card-header -->
              <div class="card-body">
                <table id="example1" class="table table-bordered table-striped">
                  <thead>
                  <tr>
                    <th>Student Name</th>
                    <th>Course</th>
                    <th>Delivery</th>
                    <th>Intake</th>
                    <th>Current Year</th>
                    <th>Semster</th>
                    <th>Balance</th>
                    <th>Status</th>
                    <!-- <th>Action</th> -->
                  </tr>
                  </thead>
                  <tbody>
                    @foreach ($payments as $payment)
                        <tr>
                            <td><a href="{{ route('student.show', ['student'=>$payment->registration]) }}">{{ $payment->registration->student->user->name }}</a></td>
                            <td>{{ $payment->registration->student->course->name }}</td>
                            <td>{{ $payment->registration->student->delivery }}</td>
                            <td>{{ $payment->registration->student->intake }}</td>
                            <td>{{ $payment->registration->academic_year }}</td>
                            <td>{{ $payment->registration->semster }}</td>
                            <td>{{ $payment->registration->student->course->fees - $payment->amount }}</td>
                            <td>
                              @if (($payment->registration->student->course->fees - $payment->amount) == 0)
                                <button disabled class="btn btn-success">Fully Paid</button>
                              @elseif ($payment->amount == 0)
                                <a href=""  class="btn btn-danger">Not Paid</a>
                                @elseif ($payment->registration->student->course->fees - $payment->amount<=0)
                                <a href=""  class="btn btn-secondary">Fully Paid</a>
                              @else
                              <a href=""  class="btn btn-primary">Partially Paid</a>
                              @endif
                            </td>
                            <!-- <td>
                            <a href="{{ route('payment.edit', ['payment' => $payment]) }}"  class="btn btn-primary">Make Payment</a>

                            </td> -->
                        </tr>
                    @endforeach
                  </tbody>
                  <tfoot>
                  <tr>
                    <th>Student Name</th>
                    <th>Course</th>
                    <th>Delivery</th>
                    <th>Intake</th>
                    <th>Current Year</th>
                    <th>Semster</th>
                    <th>Balance</th>
                    <th>Status</th>
                  </tr>
                  </tfoot>
                </table>
              </div>
              <!-- /.card-body -->
            </div>
            <!-- /.card -->
          </div>
          <!-- /.col -->
        </div>
        <!-- /.row -->
      </div>
      <!-- /.container-fluid -->
    </section>
    <!-- /.content -->
  </div>
  <!-- /.content-wrapper -->

@endsection