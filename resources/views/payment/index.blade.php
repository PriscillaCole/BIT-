@extends('baselayout')

@section('content')
    <!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <?php
  
  $course=App\Models\Course::all();
  $user= App\Models\User::all();

  ?>
    <section class="content-header">
      <div class="container-fluid">

        
        @if(session()->has('message'))
    <div class="alert alert-success">
        {{ session()->get('message') }}
    </div>
@endif


@if(session()->has('error'))
    <div class="alert alert-danger">
        {{ session()->get('error') }}
    </div>
@endif
        <div class="row mb-2">
          <div class="col-sm-6">
          <div class="col-sm-6">
            <a href="{{ route('payment.create')}}" ><button class="btn btn-primary">Make Payment</button></a>
          
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
                    <th>Registration Number</th>
                    <th>Student Name</th>
                    <th>Course</th>
                    <th>Current Year</th>
                    <th>Semester</th>
                    <th>Total Fees</th>
                    <th>Fees Paid</th>
                    <th>Status</th>
                    <th>Details</th>
                    <!-- <th>Action</th> -->
                  </tr>
                  </thead>
                  <tbody>
                    @foreach ($payments as $payment)
                        <tr>
                            <td>{{ $payment->studentID }}</td>
                             <td>{{ $payment->name }}</td>
                            <td>{{ $payment->course }}</td>
                            <td>{{ $payment->academic_years }}</td>
                            <td>{{ $payment->semester }}</td>
                            <td>
                              @if($payment->semester == "I")
                              <p>{{ $payment->semester_1}}</p>
                              @endif

                              @if($payment->semester == "II")
                              <p>{{ $payment->semester_2}}</p>
                              @endif
                            </td> 
                              
                            <td>{{ $payment->amount }}</td>

                              
                            <td>
                              @if($payment->payment_status == 0)
                                <p style="color:red">Partially Paid</p>
                              @endif
                              @if($payment->payment_status == 1)
                                <p style="color:green">Fully Paid</p>
                              @endif
                            </td>

                            <td>
                              <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#exampleModal" id="detailsButton" value="">
                                Details</button>
                            </td>
                            {{-- <td>
                            <a href="{{ route('payment.edit', ['payment' => $payment]) }}"  class="btn btn-primary">Make Payment</a>

                            </td> --}}
                        </tr>
                    @endforeach
                  </tbody>
                  <tfoot>
                  <tr>
                    <th>Registration Number</th>
                    <th>Student Name</th>
                    <th>Course</th>
                    <th>Current Year</th>
                    <th>Semester</th>
                    <th>Total Fees</th>
                    <th>Fees Paid</th>
                    <th>Status</th>
                    <th>Details</th>
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

      <div class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog">
          <div class="modal-content">
            <div class="modal-header">
              <h5 class="modal-title" id="exampleModalLabel">Payments Details</h5>
              <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
              </button>
            </div>
            <div class="modal-body">
              <div class="row">
                <div class="col-md-4">
        
                  <!-- Profile Image -->
                  <div class="card card-primary card-outline" style="border-top-color: #f4a02e; width:460px;">
                    <div class="card-body box-profile">
                     
        
                      <h3 class="profile-username text-center" id="studentDetailsName"></h3>
                      <p class="text-center"  id="studentDetailsStudentId"></p>
                      <p class="text-muted text-center" id="studentDetailsCourse"></p>
                    </div>
                    <!-- /.card-body -->
                  </div>
                  <!-- /.card -->
                  <!-- About Me Box -->
                  <div class="card card-primary" style="width:460px;" >
                    <div class="card-body" >
                      <strong>Intake</strong>
        
                      <p class="text-muted" id="studentDetailsIntake"></p>
        
                      <hr>
        
                      <strong></i>Academic Year</strong>
        
                      <p class="text-muted" id="studentDetailsYear"></p>
        
                      <hr>
             
                      <strong>Sponsorship</strong>
        
                      <p class="text-muted" id="studentDetailsSponsorship"></p>
                    </div>
                    <!-- /.card-body -->
                  </div>
                  <!-- /.card -->
                </div>
                <!-- /.col -->
                <div style="width:463px;">
                  <div class="card card-default">
                    <div class="card-header">
                      <h3 class="card-title">
                        <i class="fas fa-user"></i>
                        Personal Information
                      </h3>
                    </div>
                    <!-- /.card-header -->
                    <div class="card-body"  style="width:460px;">
                      <div class="callout">
                        <div class="row">
                          <div class="col-md-6">
                            <h5>Name:</h5>
                          </div>
                          <div class="col-md-6">
                            <p id="studentDetailsNames"></p>
                          </div>
                        </div>
                        
                        <div class="row">
                          <div class="col-md-6">
                            <h5>Email:</h5>
                          </div>
                          <div class="col-md-6">
                            <p id="studentDetailsEmail"></p>
                          </div>
                        </div>
                        <div class="row">
                          <div class="col-md-6">
                            <h5>Phone Numbers:</h5>
                          </div>
                          <div class="col-md-6">
                            <p id="studentDetailsPhone_1"></p>
                            <p id="studentDetailsPhone_2"></p>
                          </div>
                        </div>  
                        <div class="row">
                          <div class="col-md-6">
                            <h5>Course:</h5>
                          </div>
                          <div class="col-md-6">
                            <p id="studentDetailsCourses"></p>
                          </div>
                        </div>  
                        
                        <div class="row">
                          <div class="col-md-6">
                            <h5 id="semester_fee"></h5>
                          </div>
                          <div class="col-md-6">
                            <p id="studentDetailsCourse_fees1"></p>
                          </div>
                        </div> 
                        
                        <div class="row">
                          <div class="col-md-6">
                            <h5 id="semester_amount"></h5>
                          </div>
                          <div class="col-md-6">
                            <p id="studentDetailsAmount_1"></p>
                          </div>
                        </div>
                        
                        
                        {{-- <div class="row">
                          <div class="col-md-6">
                            <h5>Course Fees Semester_2:</h5>
                          </div>
                          <div class="col-md-6">
                            <p id="studentDetailsCourse_fees2"></p>
                          </div>
                        </div>
                        
                        <div class="row">
                          <div class="col-md-6">
                            <h5>Amount Paid for Semester_2:</h5>
                          </div>
                          <div class="col-md-6">
                            <p id="studentDetailsAmount_2"></p>
                          </div>
                        </div> --}}
                        
                        </div> 
                        </div>
                      </div>
                    <!-- /.card-body -->
                  </div>
                  <!-- /.card -->
                </div>
                <!-- /.col -->
              </div>
              <!-- /.row -->
        
            <div class="modal-footer">
              <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
            </div>  
            </div>
          </div>
          </div>
        </div>
      </div>
    </section>
    <!-- /.content -->
  </div>
  <!-- /.content-wrapper -->

  <script src="{{ asset('plugins/jquery/jquery.min.js') }}"></script>

  <script src="https://cdnjs.cloudflare.com/ajax/libs/sweetalert/2.1.0/sweetalert.min.js"></script>
  <script type="text/javascript">
  	$(document).ready(function(){

    $(document).on('click', "#financeDetailsButton", function(e) {
     
      $.ajax({
				type:'get',
				url:'{!!URL::to('payment_details')!!}',
				data:{'id':e.target.value},
				dataType:'json',//return data will be json
				success:function(data){
          console.log(data)
            document.getElementById("studentDetailsName").innerHTML=data.data.name
            document.getElementById("studentDetailsNames").innerHTML=data.data.name
            document.getElementById("studentDetailsStudentId").innerHTML=data.data.studentID
            document.getElementById("studentDetailsIntake").innerHTML=data.data.intake
            document.getElementById("studentDetailsYear").innerHTML=data.data.academic_year
            document.getElementById("studentDetailsSponsorship").innerHTML=data.data.sponsorship
            document.getElementById("studentDetailsEmail").innerHTML=data.data.email
            document.getElementById("studentDetailsPhone_1").innerHTML=data.data.phone_1
            document.getElementById("studentDetailsPhone_2").innerHTML=data.data.phone_2
            document.getElementById("studentDetailsCourse").innerHTML=data.data.course
            document.getElementById("studentDetailsCourses").innerHTML=data.data.course
            document.getElementById("studentDetailsAmount_1").innerHTML=data.data.amount
            console.log(data.data);
            if(data.data.semster == "I"){
              document.getElementById("semester_fee").innerHTML="Course fees semester 1 :"
              document.getElementById("semester_amount").innerHTML="Amount semester 1:"
              document.getElementById("studentDetailsCourse_fees1").innerHTML=data.data.semester_1
            }else{
              document.getElementById("semester_fee").innerHTML="Course fees semester 2 :"
              document.getElementById("semester_amount").innerHTML="Amount semester 2:"
              document.getElementById("studentDetailsCourse_fees1").innerHTML=data.data.semester_2
            }
            document.getElementById("studentDetailsCourse_fees2").innerHTML=data.data.semester_2         

        },
				error: function(e){
            console.log(e.responseText);
          }
			});
    })
  })
  </script>

@endsection