@extends('baselayout')

@section('content')
    <!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
      <div class="container-fluid">
        <div class="row mb-2">
          <div class="col-sm-6">
            
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
        <div class="col-md-12">
          <div class="card card-default">
            <div class="card-header">
              <h3 class="card-title">
                <i class="fas fa-user"></i>
                Registration Form
              </h3>
            </div>
            <!-- /.card-header -->
            <div class="card-body">
              <div class="callout">
                <strong>Welcome to on-line Registration </strong>
                <h4><strong>Academic Year: </strong>{{ $academic_year }} <strong>Course:</strong> {{ $student->course }}</h4>
                {{-- <p>
                  NOTE: <br>
                  Registration is Mandatory! <br><br>
                </p> --}}
                <form style="display: flex; justify-content: center;" id="ajaxform">
                  @csrf
                  <div class="row">
                    <div class="container">
                      <div class="form-group col-md-2">
                        <label for="semester">Semester:</label>
                      </div>
                      <div class="form-group col-md-3">
                        <select class="form-control select2" placeholder="Select a Semester" name="semester" style="width: 100%;" id="semesterSelect" required>
                          <option value="">Select Semester</option>
                          <option value="Semester 1">I</option>
                          <option value="Semester 2">II</option>
                        </select>
                      </div>
                      
                      <div class="form-group col-md-1">
                        <label for="year">Year:</label>
                      </div>
                      <div class="form-group col-md-3">
                        <select class="form-control select2" placeholder="Select Year" name="year" style="width: 100%;" id="yearSelect" required>
                            <option value="">Select Year of Study</option>
                          <option value="Year 1">1</option>
                          <option value="Year 2">2</option>
                          <option value="Year 3">3</option>
                          <option value="Year 4">4</option>
                        </select>
                      </div>
                      <div class="form-group col-md-3">
                        
                        <button class="btn btn-primary" disabled id="submitButton" value={{ $student->course_id }}>Continue</button>
                      </div>
                    </div>
                 </div>
                </form>
              </div>
            <!-- /.card-body -->
           <div>

            <table id="courseUnitAddTable" class="table table-bordered table-striped tableAdd">
              <thead>
              <tr>
                  <th>Course Unit Name</th>
                  <th>Course Unit Code</th>
                  <th>Enrolment Mode</th>
                  <th>CU</th>
                  <th>Add</th>
                  
              </tr>
              </thead>
              <tbody>
                 

              </tbody>
              <tfoot>
              <tr>    
                <th>Course Unit Name</th>
                  <th>Course Unit Code</th>
                  <th>Enrolment Mode</th>
                  <th>CU</th>
                  <th>Add</th>
              </tr>
              </tfoot>
          </table>
           </div>
           <div>
          <table id="courseUnitConfirmTable" class="table table-bordered table-striped">
            <thead>
            <tr>
                <th>Course Unit Name</th>
                <th>Course Unit Code</th>
                <th>Enrolment Mode</th>
                <th>CU</th>
                <th>Remove</th>
                
            </tr>
            </thead>
            <tbody>
               {{-- <tr>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>    
               </tr> --}}
            </tbody>
            <tfoot>
            <tr>   
              <th>Course Unit Name</th>
                <th>Course Unit Code</th>
                <th>Enrolment Mode</th>
                <th>CU</th>
                <th>Remove</th>
            </tr>
            </tfoot>
        </table>
      </div>




          </div>
          <!-- /.card -->
        </div>
        <!-- /.col -->
      </div>
      <!-- /.container-fluid -->
    </section>
    <!-- /.content -->
  </div>
  <!-- /.content-wrapper -->

  <script type="text/javascript">
  $(document).ready(function(){
    let semester
    let year
    $(document).on('change', '#semesterSelect', function(e){ 
      semester = e.target.value
    })

    $(document).on('change', '#yearSelect', function(e){ 
      year = e.target.value
      $('#submitButton').prop('disabled', false);
    })


    $(document).on('click', '#submitButton', function(e){
      e.preventDefault();
      let _token   = $('meta[name="csrf-token"]').attr('content');
      $.ajax({
        type: "POST",
        url:'{!!URL::to('courseUnits')!!}',
        data:{'course_id':e.target.value, 'year':year, 'semester':semester, '_token':_token},
        dataType:'json',
        success: function(data){
          let items = data.data

          $("#courseUnitAddTable tbody tr").remove();
          
          if(items.length > 0){
            let rows = "";
            $.each(items, function(){
                rows += "<tr><td>" + this.course_name + "</td><td>" + this.course_unit_code + "</td><td class='selectArea'><select class='form-control select2 selectMode' placeholder='Select Enrolment Mode' name='semester' style='width: 100%;' id='modeSelect' required><option value=''>Select Semester</option><option value='Normal'>Normal</option><option value='Retake'>Retake</option></select></td><td>" + this.CU + "</td> <td> <button class='btn btn-success btn-enroll' id="+this.id+" disabled>Enroll</button> </td></tr>";
            });

            $( rows ).appendTo( "#courseUnitAddTable tbody" );
          }else{
            let rows = "";
            rows += "<tr><td colspan=5>There are no courseunits added for the course, year of study and semester you have selected. Contact an administrator for support</td></tr>"

            $( rows ).appendTo( "#courseUnitAddTable tbody" );

          }
        },
        error: function(e){
          console.log(e)
        }
      })
    })

    $('#courseUnitAddTable').on('click', '.btn-enroll', function(e){
      // let tr = event.target.parentNode.parentNode
      // const row = $(this).closest('tr')
      $(this).html("Unenroll")
      $(this).css('background-color','red');
      $(this).closest('tr').find('.selectMode').find(':selected').text()

      var row = $(this).closest('tr').clone();
      
      $('#courseUnitConfirmTable tbody').append(row)
      $(this).closest('tr').remove()
    })

    $('#courseUnitConfirmTable').on('click', '.btn-enroll', function(e){
      // let tr = event.target.parentNode.parentNode
      // const row = $(this).closest('tr')
      $(this).html("Enroll")
      $(this).css('background-color','#5cb85c');
      $(this).prop("disabled", true);
      var row = $(this).closest('tr').clone();
      
      $('#courseUnitAddTable tbody').append(row)
      $(this).closest('tr').remove()
    })

    $('#courseUnitAddTable').on('change', '.selectMode',function(e) {  
      $(this).closest('tr').find('.btn-enroll').removeAttr('disabled')    
    })
  })

  </script>
@endsection