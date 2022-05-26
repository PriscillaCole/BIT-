@extends('baselayout')

@section('content')
    <!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
      <div class="container-fluid">
        <div class="row mb-2">
          <div class="col-sm-6">
          @if (auth()->user()->role != 'Accountant')
            <a href="{{ route('lecturer.create') }}"><button class="btn btn-primary">Register Lecturer</button></a>
            <a href="{{route('lecturer-cu')}}"><button class="btn btn-primary">Assign Course unit</button></a>
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
              </div>
              <!-- /.card-header -->
              <div class="card-body">
                <table id="example1" class="table table-bordered table-striped">
                  <thead>
                  <tr>
                    <th>Employ ID</th>
                    <th>Lecture Name</th>
                    <th>Email</th>
                    <th>Country</th>
                    @if (auth()->user()->role != 'Admin')
                    <th>Edit</th>
                    <th>Delete</th>
                    @endif
                  </tr>
                  </thead>
                  <tbody>
                    @foreach ($students as $student)
                        <tr>
                            <td>{{ $student->EmployID }}</td>
                            <td><a href="">{{$student->user->name }}</a></td>
                            <td>{{ $student->user->email }}</td>
                            <td>{{ $student->user->country }}</td>
                            @if (auth()->user()->role != 'Admin')
                            <td><a href="{{ route('lecturers.edit',$student->id) }}">Edit</a></td>
                            <td>
                                <form action="{{ route('lecturer.destroy',['lecturer'=>$student]) }}" method="post">
                                    @csrf
                                    @method('DELETE')
                                    <button  class="btn btn-xs btn-danger show_confirm" type="submit" data-toggle="tooltip" title='Delete'>Delete</button>
                                </form>
                            </td>
                            @endif
                        </tr>
                    @endforeach
                  </tbody>
                  <tfoot>
                  <tr>
                  <th>Employ ID</th>
                    <th>Lecture Name</th>
                    <th>Email</th>
                    <th>Country</th>
                    @if (auth()->user()->role != 'Admin')
                    <th>Edit</th>
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
