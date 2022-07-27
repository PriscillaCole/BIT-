@extends('baselayout')

@section('content')
<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
  <!-- Content Header (Page header) -->
  <section class="content-header">

    <section class="content">
        <div class="container-fluid">
          <div class="row">
            <div class="col-12">
              <div class="card">
                <div class="card-header">
                  <h3 class="card-title">Payments</h3>
                </div>
                <!-- /.card-header -->
                <div class="card-body">
                  <table id="example1" class="table table-bordered table-striped">
                    <thead>
                    <tr>
                      <th>Academic Year</th>
                      <th>Semester</th>
                      <th>Course</th>
                      <th>Fees (UGX)</th>
                      <th>Paid (UGX)</th>
                      <th>Balance (UGX)</th>
                      <th>Receipt ID</th>
                      <th> </th>
                    </tr>
                    </thead>
                    <tbody>
                      @foreach ($payments as $payment)
                      <tr>
                        <td>{{ $payment->academic_years}}</td>
                        <td>{{ $payment->semester}}</td>
                        <td>{{ $payment->course }}</td>
                        <td>{{ $payment->semester_1}}</td>
                        <td>{{ $payment->amount}}</td>
                        <td>{{ $payment->amount}}</td>
                        <td>
                   
                          {{-- @if (($payment->student->course->fees - $amt) == 0)
                            <button disabled class="btn btn-success">Fully Paid</button>
                          @elseif ($amt == 0)
                            <a href="{{ route('payment.edit', ['payment' => $payment->payment]) }}"  class="btn btn-danger">Not Paid</a>
                            @elseif (($payment->student->course->fees - $amt)<=0)
                                    <a href=""  class="btn btn-secondary">Fully Paid</a>
                          @else
                          <a href="{{ route('payment.edit', ['payment' => $payment->payment]) }}"  class="btn btn-primary">Partially Paid</a>
                          @endif --}}
                        </td>
                      </tr>
                      @endforeach
                    </tbody>
                    <tfoot>
                    <tr>
                      <th>Academic Year</th>
                      <th>Semster</th>
                      <th>Course</th>
                      <th>Fees (UGX)</th>
                      <th>Paid (UGX)</th>
                      <th>Balance (UGX)</th>
                      <th>Receipt ID</th>
                      <th> </th>
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
  </section>
    </div>
@endsection