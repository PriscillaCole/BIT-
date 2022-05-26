@extends('baselayout')

@section('content')
    <!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
      <div class="container-fluid">
        <div class="row mb-2">
          <div class="col-sm-6">
            @if(auth()->user()->role =='Lecturer')
            @else
              @if (auth()->user()->role != 'Accountant')
              <a href="{{ route('student.create') }}"><button class="btn btn-primary">Register Student</button></a>
              @endif
            @endif
            
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
                <h3 class="card-title">Student List</h3>
                @if(Session::has('success'))
        <div class="alert alert-success" role="alert">
          <div> {{Session::get('success')}}</div>
          </div>
        </div>
        @endif
              </div>
              <?php
              $lect=App\Models\Lecture_Course_units::where(['user_id'=>auth()->user()->id])->get();
              $unit=App\Models\Course_unit::all();
              $cours=App\Models\Course::all();
              $stud= App\Models\Student::all();
              $rec=App\Models\Payment::all();
              $payment=App\Models\Payment::all();
              $num=0; $num++; $num=sprintf('%07d',$num);
              ?>
              <!-- /.card-header -->
              <div class="card-body">
                <table id="example1" class="table table-bordered table-striped">
                  <thead>
                  <tr>
                    <th>Student ID</th>
                    <th>Student Name</th>
                    <th>Course</th>
                    @if (auth()->user()->role =='Lecturer')
                    <th>Code</th>
                    <th> CU Code</th>
                    <th>CU Name</th>
                    <th>Year of Study</th>
                    <th>Semester</th>
                    @endif
                    @if (auth()->user()->role !=='Lecturer')
                    <th>Delivery</th>
                    <th>Intake</th>
                    <th>View</th>
                    <th>Delete</th>
                    @endif
                  </tr>
                  </thead>
                  <tbody>
                  @if (auth()->user()->role !='Lecturer')
                    @foreach ($students as $student)
                        <tr>
                            <td>{{ $student->studentID }}</td>
                            <td><a href="{{ route('student.show' ,['student'=>$student]) }}">{{ $student->user->name }}</a></td>
                            <td>{{ $student->course->name }}</td>
                            <td>{{ $student->delivery }}</td>
                            <td>{{ $student->intake }}</td>
                            <td><a href="{{ route('student.edit',['student'=>$student]) }}">View</a></td>
                            <td>
                                <form action="{{ route('student.destroy',['student'=>$student]) }}" method="post">
                                    @csrf
                                    @method('DELETE')
                                    <button  class="btn btn-xs btn-danger show_confirm" type="submit" data-toggle="tooltip" title='Delete'>Delete</button>
                                </form>
                            </td>
                        </tr>
                    @endforeach
                  @else
                  @foreach ($stud as $student)
                  @foreach ($cours as $cour)
                  @foreach ($unit as $units)
                  @foreach ($lect as $lec)
                      @if($student->course_id==$cour->id)
                      @if($units->course_code==$cour->code)
                      @if($units->course_unit_code==$lec->course_unit_code)
                            <tr>
                                <td>{{ $student->studentID }}</td>
                                <td><a href="{{ route('student.show' ,['student'=>$student]) }}">{{ $student->user->name }}</a></td>
                                <td>{{ $student->course->name }}</td>

                                <td>{{ $units->course_code }}</td>
                                <td>{{ $units->course_unit_code }}</td>
                                <td>{{ $units->course_name }}</td>

                                <td>{{ $units->YearOfStudy }}</td>
                                <td>{{ $units->Semester }}</td>
                                <!-- <td><a href="{{ route('student.edit',['student'=>$student]) }}">View</a></td> -->
                                <!-- <td>
                                    <form action="{{ route('student.destroy',['student'=>$student]) }}" method="post">
                                        @csrf
                                        @method('DELETE')
                                        <button  class="btn btn-xs btn-danger show_confirm" type="submit" data-toggle="tooltip" title='Delete'>Delete</button>
                                    </form>
                                </td> -->
                            </tr>
                        @endif
                        @endif
                        @endif
                    @endforeach
                    @endforeach
                    @endforeach
                    @endforeach
                  @endif  
                  </tbody>
                  <tfoot>
                  <tr>
                    <th>Student ID</th>
                    <th>Student Name</th>
                    <th>Course</th>
                    @if (auth()->user()->role =='Lecturer')
                    <th>Code</th>
                    <th> CU Code</th>
                    <th>CU Name</th>
                    <th>Year of Study</th>
                    <th>Semester</th>
                    @endif                    
                    @if (auth()->user()->role !=='Lecturer')
                    <th>Delivery</th>
                    <th>Intake</th>
                    <th>View</th>
                    <th>Delete</th>
                    @endif
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
  <script src="//ajax.googleapis.com/ajax/libs/jquery/1.11.3/jquery.min.js"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/sweetalert/2.1.0/sweetalert.min.js"></script>
  <script type="text/javascript">
 
     $('.show_confirm').click(function(event) {
          var form =  $(this).closest("form");
          var name = $(this).data("name");
          event.preventDefault();
          swal({
              title: `Are you sure you want to delete this record?`,
              text: "If you delete this, it will be gone forever.",
              icon: "warning",
              buttons: true,
              dangerMode: true,
          })
          .then((willDelete) => {
            if (willDelete) {
              form.submit();
            }
          });
      });
  
  </script>
  <!-- /.content-wrapper -->

@endsection