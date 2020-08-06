@extends('app')

@section('datatable-css')

    <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.10.12/css/jquery.dataTables.min.css">
    <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/buttons/1.2.1/css/buttons.dataTables.min.css">
    <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/responsive/2.1.0/css/responsive.dataTables.min.css">
    <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/select/1.2.0/css/select.dataTables.min.css">
    <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/keytable/2.1.2/css/keyTable.dataTables.min.css">
    <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/autofill/2.1.2/css/autoFill.dataTables.min.css">
    <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/colreorder/1.3.2/css/colReorder.dataTables.min.css">
    <link rel="stylesheet" type="text/css" href="{{ asset('/plugin/css/editor.dataTables.min.css') }}">
    {{-- <link rel="stylesheet" type="text/css" href="{{ asset('/plugin/examples/resources/syntax/shCore.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('/Plugin/examples/resources/demo.css') }}"> --}}

    <style type="text/css" class="init">
      a.buttons-collection {
        margin-left: 1em;
      }
      .container1{
              width: 1200px;
              margin-left: 50px;
              padding: 10px;
      }
      .tableheader{
        background-color: gray;
      }

      .interntable{
        text-align: center;
      }
    </style>

@endsection

@section('datatable-js')

      <script type="text/javascript" language="javascript" src="https://cdn.datatables.net/1.10.12/js/jquery.dataTables.min.js"></script>
      <script type="text/javascript" language="javascript" src="https://cdn.datatables.net/buttons/1.2.1/js/dataTables.buttons.min.js"></script>
      <script type="text/javascript" language="javascript" src="https://cdn.datatables.net/select/1.2.0/js/dataTables.select.min.js"></script>
      <script type="text/javascript" language="javascript" src="https://cdn.datatables.net/autofill/2.1.2/js/dataTables.autoFill.min.js"></script>
      <script type="text/javascript" language="javascript" src="https://cdn.datatables.net/colreorder/1.3.2/js/dataTables.colReorder.min.js"></script>
      <script type="text/javascript" language="javascript" src="https://cdn.datatables.net/responsive/2.1.0/js/dataTables.responsive.min.js"></script>
      <script type="text/javascript" language="javascript" src="https://cdnjs.cloudflare.com/ajax/libs/jszip/2.5.0/jszip.min.js"></script>
      <script type="text/javascript" language="javascript" src="https://cdn.datatables.net/keytable/2.1.2/js/dataTables.keyTable.min.js"></script>
      <script type="text/javascript" language="javascript" src="https://cdn.rawgit.com/bpampuch/pdfmake/0.1.18/build/pdfmake.min.js"></script>
      <script type="text/javascript" language="javascript" src="https://cdn.rawgit.com/bpampuch/pdfmake/0.1.18/build/vfs_fonts.js"></script>
      <script type="text/javascript" language="javascript" src="https://cdn.datatables.net/buttons/1.2.0/js/buttons.html5.min.js"></script>
      <script type="text/javascript" language="javascript" src="https://cdn.datatables.net/buttons/1.2.0/js/buttons.print.min.js"></script>

      <script type="text/javascript" language="javascript" src="{{ asset('/plugin/js/dataTables.editor.min.js') }}"></script>
      {{-- chart js --}}
      <script type="text/javascript" language="javascript" src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.4.0/Chart.bundle.min.js"></script>

      <script type="text/javascript" language="javascript" class="init">

      $(document).ready(function() {
          var $total = $('#Sum_Meal'),
              $value = $('#value');
          $value.on('input', function (e) {
              var total = 1;
              $value.each(function (index, elem) {
                  if (!Number.isNaN(parseInt(this.value, 10))) total = total * parseInt(this.value, 10);
              });
              $total.val(total);
          });
      });

      </script>

      @endsection

@section('content')

  <div class="content-wrapper">
      <!-- Content Header (Page header) -->
    <section class="content-header">
      <h1>
      My Request
      <small>My Workplace</small>
      </h1>

      <ol class="breadcrumb">
      <li><a href="{{ url('/home') }}"><i class="fa fa-dashboard"></i> Home</a></li>
      <li><a href="#">My Workplace</a></li>
      <li class="active">My Request</li>
      </ol>
    </section>

    <section class="content">

      <div class="row">

        <div class="modal modal-danger fade" id="error-alert">
          <div class="modal-dialog">
            <div class="modal-content">
              <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                  <span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title">Alert!</h4>
              </div>
              <div class="modal-body">
              <ul></ul>
              </div>
              <div class="modal-footer">
                <button type="button" class="btn btn-outline" data-dismiss="modal">Close</button>
              </div>
            </div>
          </div>
        </div>

        <div class="modal modal-success fade" id="update-alert">
          <div class="modal-dialog">
            <div class="modal-content">
              <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                  <span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title">Alert!</h4>
              </div>
              <div class="modal-body">
              <ul></ul>
              </div>
              <div class="modal-footer">
                <button type="button" class="btn btn-outline" data-dismiss="modal">Close</button>
              </div>
            </div>
          </div>
        </div>
        <div class="col-md-12">
          <div class="box box-info">
            <div class="box-body">

              <form enctype="multipart/form-data" id="upload_form" role="form" method="POST" action="" >

                <h3>EMPLOYEE ADVANCE FORM</h3>

                <div class="box-body">

                    <h4>Staff Details</h4>

                    <input type="hidden" name="UserId" value="{{ $me->UserId }}">

                    <div class="row">
                      <div class="form-group">

                        <div class="col-lg-4">
                          <label>Name : </label>
                          @foreach ($users as $user)
                             <input type="text" class="form-control" id="Name" name="Name" value="{{$user->Name}}" disabled>
                             <input type="hidden" class="form-control" id="UserId" name="UserId" value="{{$user->Id}}" disabled>
                          @endforeach
                        </div>


                       <div class="col-lg-4">
                         <label> Bank Account : </label>
                         @foreach ($users as $user)
                         <input type="text" class="form-control" id="Bank_Account_No" name="Bank_Account_No" value="{{$user->Bank_Account_No}}" disabled>
                         @endforeach
                       </div>

                     </div>
                  </div>

                  <div class="row">
                    <div class="form-group">

                        <div class="col-lg-4">
                            <label>Project : </label>

                            <select class="form-control select2" id="ProjectId" name="ProjectId" style="width: 100%;">
                              <option></option>
                              @foreach ($projects as $project)
                                  <option  value="{{$project->Id}}">{{$project->Project_Name}}</option>
                              @endforeach
                            </select>
                        </div>

                        <div class="col-lg-4">
                          <label> Position : </label>
                          @foreach ($users as $user)
                            <input type="text" class="form-control" id="Position" name="Position" value="{{$user->Position}}" disabled>
                          @endforeach
                        </div>

                    </div>
                  </div>

                  <div class="row">
                    <div class="form-group">

                      <div class="col-lg-8">
                          <label>Purpose : </label>
                            <textarea class="form-control" id="Purpose" name="Purpose" row="3" placeholder="Please Enter.." ></textarea>
                      </div>
                    </div>
                  </div>

                  <br>

                  <h4>Travelling Details</h4>


                  <div class="row">
                    <div class="form-group">

                      <div class="col-lg-8">
                          <label>Destination : </label>
                          <input type="text" class="form-control" id="Destination" name="Destination" value="">
                      </div>
                    </div>
                  </div>

                  <div class="row">
                    <div class="form-group">

                      <div class="col-lg-4">
                        <label>Start Date : </label>

                        <div class="input-group date">
                          <div class="input-group-addon">
                            <i class="fa fa-calendar"></i>
                          </div>
                          <input type="text" class="form-control pull-right" id="Start_Date" name="Start_Date" value="{!! old('End_Date') !!}">
                        </div>
                      </div>

                      <div class="col-lg-4">
                        <label>End Date : </label>

                        <div class="input-group date">
                          <div class="input-group-addon">
                            <i class="fa fa-calendar"></i>
                          </div>
                          <input type="text" class="form-control pull-right" id="End_Date" name="End_Date" value="{!! old('End_Date') !!}">
                        </div>
                      </div>

                    </div>
                  </div>

                  <div class="row">
                    <div class="form-group">

                      <div class="col-lg-4">
                        <label>Mode Of Transport : </label>

                        <select class="form-control select2" id="Mode_Of_Transport" name="Mode_Of_Transport" style="width: 100%;">
                          <option></option>
                          @foreach ($options as $key => $option)
                            @if ($option->Field=="Mode_Of_Transport")

                              <option  value="{{$option->Option}}">{{$option->Option}}</option>

                            @endif
                          @endforeach
                        </select>

                      </div>

                      <div class="col-lg-4">
                        <label>Car Plate No : </label>

                        <input type="text" class="form-control" id="Car_No" name="Car_No" value="">

                      </div>

                    </div>
                  </div>

                  <br>

                  <h4>Advanced Required</h4>

                  <div class="row">
                    <div class="form-group">
                      <div class="col-lg-8">

                        <table class="table table-bordered">
                           <tr>
                             <th></th>
                             <th>No of Days</th>
                             <th>Allowance (Per Day)</th>
                             <th style="width:70px;">Total (RM)</th>
                           </tr>
                           <tr>
                             <td>Meal Allowance</td>
                             <td><input type="text" class="form-control" id="Meal_Days" name="Meal_Days" style="padding: 3px;"></td>
                             <td><input type="text" class="form-control" id="Meal_Per_Day" name="Meal_Per_Day" style="padding: 3px;"></td>
                             <td><input type="text" class="form-control" id="Sum1" name="Sum1" style="padding: 3px;"></td>
                           </tr>

                           <tr>
                             <td>Accomodation/ Hotel</td>
                             <td><input type="text" class="form-control" id="Accomodation_Days" name="Accomodation_Days" style="padding: 3px;"></td>
                             <td><input type="text" class="form-control" id="Accomodation_Per_Day" name="Accomodation_Per_Day"style="padding: 3px;"></td>
                             <td><input type="text" class="form-control" id="Sum2" name="Sum2" style="padding: 3px;"></td>
                           </tr>

                           <tr>
                             <td>Mileage/Petrol</td>
                             <td><input type="text" class="form-control" id="Mileage_Days" name="Mileage_Days" style="padding: 3px;" style="padding: 3px;"></td>
                             <td><input type="text" class="form-control" id="Mileage_Per_Day" name="Mileage_Per_Day" value="" style="padding: 3px;"></td>
                             <td><input type="text" class="form-control" id="Sum3" name="Sum3" style="padding: 3px;"></td>
                           </tr>

                           <tr>
                             <td>Parking/Tolls</td>
                             <td><input type="text" class="form-control" id="Parking_Days" name="Parking_Days" value="" style="padding: 3px;"></td>
                             <td><input type="text" class="form-control" id="Parking_Per_Day" name="Parking_Per_Day" value="" style="padding: 3px;"></td>
                             <td><input type="text" class="form-control" id="Sum4" name="Sum4" style="padding: 3px;"></td>
                           </tr>

                           <tr>
                             <td>Fare/Ticket</td>
                             <td><input type="text" class="form-control" id="Ticket_Days" name="Ticket_Days" value="" style="padding: 3px;"></td>
                             <td><input type="text" class="form-control" id="Ticket_Per_Day" name="Ticket_Per_Day" value="" style="padding: 3px;"></td>
                             <td><input type="text" class="form-control" id="Sum5" name="Sum5" style="padding: 3px;" ></td>
                           </tr>

                           <tr>
                             <td>Other Purposes</td>
                             <td><input type="text" class="form-control" id="Other_Days" name="Other_Days" value="" style="padding: 3px;"></td>
                             <td><input type="text" class="form-control" id="Other_Per_Day" name="Other_Per_Day" value="" style="padding: 3px;"></td>
                             <td><input type="text" class="form-control" id="Sum6" name="Sum6" style="padding: 3px;"></td>
                           </tr>

                           <tr>
                             <td></td>
                             <td colspan="2" style="text-align:right">Total Advanced Requested : </td>
                             <td><input type="text" class="form-control" id="Total_Requested" name="Total_Requested" style="padding: 3px;"></td>
                           </tr>



                         </table>
                      </div>

                    </div>
                  </div>


                </div>

              </form>

              <div class="box-footer">
                <button type="submit" class="btn btn-primary" onclick="applyadvance()">Submit</button>
              </div>


            </div>
          </div>
        </div>
      </div>


    </section>

</div>
<footer class="main-footer">
  <div class="pull-right hidden-xs">
    <b>Version</b> 2.0.1
  </div>
  <strong>Copyright &copy; 2014-2016 <a href="http://www.softoya.com">TrackerOnTheGo</a>.</strong> All rights
  reserved.
</footer>

<script>

$(function () {

  //Initialize Select2 Elements
  $(".select2").select2();

  //Flat red color scheme for iCheck
  $('input[type="checkbox"].flat-green, input[type="radio"].flat-green').iCheck({
    checkboxClass: 'icheckbox_flat-green',
    radioClass: 'iradio_flat-green'
  });

  //Date picker
  $('#Start_Date').datepicker({
    autoclose: true,
      format: 'dd-M-yyyy',
  });

  $('#End_Date').datepicker({
    autoclose: true,
      format: 'dd-M-yyyy',
  });

});




function applyadvance() {

    $.ajaxSetup({
       headers: { 'X-CSRF-Token' : $('meta[name=_token]').attr('content') }
    });

    $("#ajaxloader").show();

    // leave_type=$('[name="Leave_Type"]').val();
    // leave_term=$($('input[name=Leave_Term]:checked')).val();
    // start_date=$('[name="Start_Date"]').val();
    // end_date=$('[name="End_Date"]').val();
    // reason=$('[name="Reason"]').val();
    // project=$('[name="Project"]').val();
    // approver=$('[name="Approver"]').val();



    $.ajax({
                url: "{{ url('api/myadvance/apply?token=') }}{{$token}}",
                method: "POST",
                contentType: false,
                processData: false,
                data:new FormData($("#upload_form")[0]),

                success: function(response){

                  if (response==1)
                  {

                      var message="Advance application submitted!";
                      $("#update-alert ul").html(message);
                      $("#update-alert").modal('show');

                      $("#ajaxloader").hide();

                      location.reload();

                  }
                  else {
                      var obj = jQuery.parseJSON(response);
                      var errormessage ="";

                      for (var item in obj) {
                        errormessage=errormessage + "<li> " + obj[item] + "</li>";
                      }

                      $("#error-alert ul").html(errormessage);
                      $("#error-alert").modal('show');

                      $("#ajaxloader").hide();


                  }

        }
    });

}

</script>



@endsection
