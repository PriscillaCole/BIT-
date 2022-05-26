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
          <h3 class="card-title">Register Lecturer</h3>
        </div>
        <!-- /.card-header -->
        @if(Session::has('student_added'))
        <div class="alert alert-success" role="alert">
          <div> Lecturer has been succesfully added.</div>
          </div>
        </div>
        @endif
        <form action="{{ route ('lecturer.store') }}" enctype="multipart/form-data" method="post">
          @csrf
          <div class="card-body">
           
            <h5>SECTION 1</h5>
            <div class="row">
              <div class="col-md-12">
                <div class="form-group">
                  <label for="course">Course Unit(s) to lecture:</label>
                  <select class="form-control select2" placeholder="Select a course" name="course" style="width: 100%;" required>
                    @foreach ($courses as $course)
                      <option value="{{$course->course_code}}" >{{ $course->course_name }}</option>
                    @endforeach
                  </select>
                </div>
               
                <label for="delivery">Mode of Delivery:</label>
                <div class="form-group">
                  <div class="form-check">
                    <input class="form-check-input" type="radio" name="delivery" id="name" value="Weekend" required>
                    <label style="margin-left: 20px;" class="form-check-label">Physical</label>
                  </div>
                  <div class="form-check">
                    <input class="form-check-input" type="radio" name="delivery" id="name" value="Distance Learning" required>
                    <label  style="margin-left: 20px;"class="form-check-label">Distance Learning</label>
                  </div>
                </div>
              </div>
            </div>

            <h5>1.1: LECTURE’S PERSONAL INFORMATION</h5>
            <div class="row">
              <div class="col-md-12">
                <div class="form-group">
                  <label for="course">Name:</label>
                  <input type="text" class="form-control" name="name" id="name" required>
                </div>
                <div class="form-group">
                  <label for="gender">Gender:</label>
                  <div class="form-check">
                    <input class="form-check-input" type="radio" name="gender" id="gender" value="Male" required>
                    <label style="margin-left: 20px;" class="form-check-label">Male</label>
                  </div>
                  <div class="form-check">
                    <input class="form-check-input" type="radio" name="gender" id="gender" value="Female" required>
                    <label style="margin-left: 20px;" class="form-check-label">Female</label>
                  </div>
                </div>
                <div class="form-group">
                  <label for="date_of_birth">Date Of Birth:</label>
                  <input type="date" class="form-control" name="date_of_birth" id="date_of_birth" required>
                </div>
                <div class="form-group">
                  <label for="country">Country of residence:</label>
                  <select class="form-control select2" placeholder="Select a Country" name="country" style="width: 100%;" required>
                    <!-- @foreach ($courses as $course)
                      <option>{{ $course->name }}</option>
                    @endforeach -->
                    <option value="">select Country</option>
                    <option>Uganda<n/option>
                    <option>Kenya</option>
                    <option>S.Sudan</option>
                    <option>Rwanda</option>
                    <option>Tanzania</option>
                    <option>Burundian/option>
                    <option>Eritrea</option>
                    <option>DRC</option>
                  </select>
                </div>
                <div class="form-group">
                  <label for="nationality">Nationality:</label>
                  <select class="form-control select2" placeholder="Select a Nationality" name="nationality" style="width: 100%;" required>
                    <!-- @foreach ($courses as $course)
                      <option>{{ $course->name }}</option>
                    @endforeach -->
                    <option value="">select Nationality</option>
                    <option>Ugandan<n/option>
                    <option>Kenyan</option>
                    <option>S.Sudanise</option>
                    <option>Rwandan</option>
                    <option>Tanzanian</option>
                    <option>Burundian</option>
                    <option>Eritrean</option>
                    <option>DRC</option>
                  </select>
                </div>
                <div class="form-group">
                  <label for="district">Home District:</label>
                  <input type="text" class="form-control" name="district" id="district" required>
                </div>
                <div class="form-group">
                  <label for="Town">Town:</label>
                  <input type="text" class="form-control" name="Town" id="Town" required>
                </div>
                <div class="form-group">
                  <label for="postal">P.O BOX:</label>
                  <input type="text" class="form-control" name="postal" id="postal" required>
                </div>
                <div class="form-group">
                  <label for="religion">Religion:</label>
                  <input type="text" class="form-control" name="religion" id="religion" required>
                </div>
                <div class="form-group">
                  <label for="marital_status">Marital Status:</label>
                  <div class="form-check">
                    <input class="form-check-input" type="radio" name="marital_status" id="marital_status" value="Single" required>
                    <label style="margin-left: 20px;" class="form-check-label">Single</label>
                  </div>
                  <div class="form-check">
                    <input class="form-check-input" type="radio" name="marital_status" id="marital_status" value="Married" required>
                    <label style="margin-left: 20px;" class="form-check-label">Married</label>
                  </div>
                  <div class="form-check">
                    <input class="form-check-input" type="radio" name="marital_status" id="marital_status" value="Others" required>
                    <label style="margin-left: 20px;" class="form-check-label">Others</label>
                  </div>
                  <!-- <input type="text" class="form-control" name="marital_status" id="marital_status"> -->
                </div>
                <div class="form-group">
                  <label for="spouse_name">Spouse Name:</label>
                  <input type="text" class="form-control" name="spouse_name" id="spouse_name" required>
                </div>
        
                <div class="form-group">
                  <label for="spouse_contact">Spouse Contact:</label>
                  <input type="tel" class="form-control" name="spouse_contact" id="spouse_contact" required>
                </div>
              </div>
            </div>

            <h5>1.2: DISABILITY</h5>
            <div class="row">
              <div class="col-md-12">
                <div class="form-group">
                  <label for="disability">Do you have any disability?</label>
                  <div class="form-check">
                    <input class="form-check-input" type="radio" name="disability" id="name" value="Yes" required>
                    <label style="margin-left: 20px;" class="form-check-label">Yes</label>
                  </div>
                  <div class="form-check">
                    <input class="form-check-input" type="radio" name="disability" id="name" value="No" required>
                    <label style="margin-left: 20px;" class="form-check-label">No</label>
                  </div>
                </div>
                <div class="form-group" disabled>
                  <label  for="nature_of_disability">Nature Of Disability:</label>
                  <input type="text" class="form-control" name="nature_of_disability" id="nature_of_disability">
                </div>
              </div>
            </div>

            <h5>1.3: LECTURE’S CONTACT</h5>
            <div class="row">
              <div class="col-md-12">
                <div class="form-group">
                  <label for="email">Email:</label>
                  <input type="email" class="form-control" name="email" id="email" required>
                </div>
                <div class="form-group">
                  <label for="phone_1">Phone Number:</label>
                  <input type="tel" class="form-control" name="phone_1" id="phone_1" required>                
                </div>
                <div class="form-group">
                  <label for="phone_2">Alt Phone Number:</label>
                  <input type="tel" class="form-control" name="phone_2" id="phone_2" required>                 
                </div>
              </div>
            </div>

            <h5>1.4 PARENTS/GUARDIAN’S (next of kin) CONTACT</h5>
            <div class="row">
              <div class="col-md-6">
                <div class="form-group">
                  <label for="father_name">Father's (Guardian) Name:</label>
                  <input type="father_name" class="form-control" name="father_name" id="father_name" required>
                </div>
        
                <div class="form-group">
                  <label for="father_contact">Father's (Guardian) Contact:</label>
                  <input type="tel" class="form-control" name="father_contact" id="father_contact" required>
                </div>
              </div>
              <div class="col-md-6">
                <div class="form-group">
                  <label for="mother_name">Mother's (Guardian) Name:</label>
                  <input type="text" class="form-control" name="mother_name" id="mother_name" required>
                </div>
        
                <div class="form-group">
                  <label for="mother_contact">Mother (Guardian) Contact:</label>
                  <input type="tel" class="form-control" name="mother_contact" id="mother_contact" required>
                </div>
              </div>
            </div>

                <div class="form-group">
                  <label for="image">Choose Profile Image</label>
                  <div style="margin-left:5px;" class="row">
                    <input type="file" class="btn btn-default col-md-10" name="file" onchange="previewFile(this)" required>
                    <img id="previewImg" alt="" style="max-width:130px; margin-top:20px; margin-bottom:20px;"/>
                    @if($errors->has('profileImage'))
                      <strong>{{ $errors->first('profileImage') }}</strong>
                    @endif
                  </div>
                </div>
              </div>
            </div>
            <input type="hidden" name="role" value="Lecturer">
            <input type="hidden" name="password" value="password">
            
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
<script src="https://cdnjs.cloudflare.com/ajax/libs/sweetalert/2.1.2/sweetalert.min.js" 
        integrity="sha512-AA1Bzp5Q0K1KanKKmvN/4d3IRKVlv9PYgwFPvm32nPO6QS8yH1HO7LbgB1pgiOxPtfeg5zEn2ba64MUcqJx6CA==" 
        crossorigin="anonymous" referrerpolicy="no-referrer">
</script>
<script>
  function previewFile(input){
    var file=$("input[type=file]").get(0).files[0];
    if(file){
      var reader = new FileReader();
      reader.onload = function(){
        $('#previewImg').attr("src",reader.result);
      }
      reader.readAsDataURL(file);
    }
  }
  </script>
  @if(Session::has('student_added'))
    <script>
      swal("Congratulations!","{!! Session::get('student_added') !!}","success",{
        button:"OK",
      })
    </script>
    
  @endif
<!-- /.content-wrapper -->
@endsection