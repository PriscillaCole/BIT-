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
          <h3 class="card-title">Student Test Marks</h3>

        </div>
        <!-- /.card-header -->
        <?php
        $courses=App\Models\Student::all();
        $course_code=App\Models\Course_unit::all();
        $asign=App\Models\Lecture_Course_units::where(['user_id'=>auth()->user()->id])->get();
        ?>
        <form action="{{ route ('marks.store') }}" method="post">
          @csrf
          <div class="card-body">
            <div class="row">
              <div class="col-md-12">
              <div class="form-group">
                  <label for="course">student ID:</label>
                  <select required class="form-control select2 studentID" placeholder="Select student ID" name="studentID" style="width: 100%;">
                  
                  <option value=''> Select student ID</option>  
                  
                  <!-- @foreach ($course_code as $coursecod) -->
                  @foreach ($courses as $course)                 
                      <option>{{ $course->studentID }}</option>
                     
                      @endforeach
                     
                    <!-- @endforeach -->
                  </select>
                  <label  style="margin-bottom:10px;margin-top:20px" for="name">Name:</label>
                <input type="text" class="form-control name" name="name" id="name"  disabled>
                </div>
                @if(Auth::user()->role !== 'Admin' && Auth::user()->role !== 'Lecturer')
                <div class="form-group">
                  <label for="CourseUnitCode">Course Unit Code:</label>
                  <select required class="form-control select2" placeholder="Select Course Unit Code" name="CourseUnitCode" style="width: 100%;">
                  
                  <option value=''> Select Course Unit Code</option>  
                  @foreach ($course_code as $course)
                      <option>{{ $course->course_unit_code }}</option>
                    @endforeach
                  </select>
                </div>
                
                @elseif(Auth::user()->role !== 'Admin' && Auth::user()->role !== 'Super User')
                <div class="form-group">
                  <label for="CourseUnitCode">Course Unit Code:</label>
                  <select required class="form-control select2" placeholder="Select Course Unit Code" name="CourseUnitCode" style="width: 100%;">
                  
                  <option value=''> Select Course Unit Code</option>  
                  @foreach ($asign as $course)
                      <option>{{ $course->course_unit_code }}</option>
                    @endforeach
                  </select>
                </div>
                @endif


                
                <div class="form-group">
                  <label for="name">Student Score:</label>
                  <input type="number" class="form-control" name="score" id="score" required>
                </div>
                </div>
                <input type="hidden" name="test" value="test">
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
            <!-- <button style="margin-left: 50px; margin-top:20px;margin-bottom:15px" class="btn btn-success" type=""> -->
            <a style="margin-left: 50px; margin-top:20px;margin-bottom:15px"  style="color:;" href="{{ route('marks.index') }}"> <i class="fa fa-fw fa-lg fa-eye"></i>View Marks</a> 
          <!-- </button> -->

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
<script type="text/javascript">
	$(document).ready(function(){


		$(document).on('change','.studentID',function () {
			var prod_id=$(this).val();

			var a=$(this).parent();
			console.log(prod_id);
			var op="";
			$.ajax({
				type:'get',
				url:'{!!URL::to('findPrice')!!}',
				data:{'id':prod_id},
				dataType:'json',//return data will be json
				success:function(data){
					console.log("price");
					console.log(data);
          console.log(data.p.intake);
          console.log(data.data2.name);


					a.find('.student_number').val(data.p.studentID);
					a.find('.academic_year').val(data.p.academic_year);
          a.find('.name').val(data.data2.name);
          a.find('.registration_id').val(data.registration.id)
          a.find('.semster').val(data.registration.semster)


				},
				error: function(e){
            console.log(e.responseText);
          }
			});


		});

	});
</script>

@endsection