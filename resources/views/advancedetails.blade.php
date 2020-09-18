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

      h5{
        border: 1px solid #aba2a2;
        padding: 5px;
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

        <div class="modal fade" id="Submit" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
          <div class="modal-dialog" role="document">
            <div class="modal-content">
              <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title" id="myModalLabel">Submit and Notify</h4>
              </div>
              <div class="modal-body">
                  Are you sure you wish to submit advance for next action?
              </div>
              <div class="modal-footer">
                <center><img src="{{ URL::to('/') ."/img/ajax-loader.gif" }}" width="50px" height="50px" alt="Loading" name='ajaxloader' id="ajaxloader"></center>
                <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                <button type="button" class="btn btn-primary" onclick="submit({{$advanceid}})">Yes</button>
              </div>
            </div>
          </div>
        </div>

        <div class="modal fade" id="Submit" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
          <div class="modal-dialog" role="document">
            <div class="modal-content">
              <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title" id="myModalLabel">Submit and Notify</h4>
              </div>
              <div class="modal-body">
                  Are you sure you wish to submit the advance for next action?
              </div>
              <div class="modal-footer">
                <center><img src="{{ URL::to('/') ."/img/ajax-loader.gif" }}" width="50px" height="50px" alt="Loading" name='ajaxloader' id="ajaxloader"></center>
                <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                <button type="button" class="btn btn-primary" onclick="submit({{ $advanceid }})">Yes</button>
              </div>
            </div>
          </div>
        </div>

        <div class="modal fade" id="Redirect" role="dialog" aria-labelledby="myModalLabel">
          <div class="modal-dialog" role="document">
            <div class="modal-content">
              <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title" id="myModalLabel">Redirect</h4>
              </div>
              <div class="modal-body">
                <div class="form-group" id="redirectleavestatus">

                </div>
                <div class="form-group">

                    <label>Approver : </label>

                    <select class="form-control select2" id="NewApprover" name="NewApprover" style="width: 100%;">

                      @if ($approver)
                        @foreach ($approver as $app)

                            <option  value="{{$app->Id}}">{{$app->Name}}</option>

                        @endforeach

                      @endif

                      </select>

                </div>
              </div>
              <div class="modal-footer">
                <center><img src="{{ URL::to('/') ."/img/ajax-loader.gif" }}" width="50px" height="50px" alt="Loading" name='ajaxloader2' id="ajaxloader2"></center>
                <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                <button type="button" class="btn btn-primary" onclick="redirect()">Redirect</button>
              </div>
            </div>
          </div>
        </div>

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

        <div class="modal modal-warning fade" id="warning-alert">
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
                @foreach ($advance as $advances)
                <div class="box-body">

                    <h4>Staff Details</h4>

                    <input type="hidden" name="UserId" value="{{ $me->UserId }}">

                    <div class="row">
                      <div class="form-group">

                        <div class="col-lg-4">
                          <label>Name : </label>
                            <h5>{{$advances->Name}}</h5>
                        </div>


                       <div class="col-lg-4">
                         <label> Bank Account : </label>

                         @if($advances->Bank_Account_No=="")
                           <h5>-</h5>

                         @else

                           <h5>{{$advances->Bank_Account_No}}</h5>

                         @endif

                       </div>

                     </div>
                  </div>

                  <div class="row">
                    <div class="form-group">

                        <div class="col-lg-4">
                          <label> Position : </label>
                          @if($advances->Position=="")
                            <h5>-</h5>

                          @else

                            <h5>{{$advances->Position}}</h5>

                          @endif

                        </div>

                    </div>
                  </div>

                  <div class="row">
                    <div class="form-group">

                      <div class="col-lg-8">
                          <label>Purpose : </label>
                          <h5>{{$advances->Purpose}}</h5>
                      </div>
                    </div>
                  </div>

                  <br>

                  <h4>Travelling Details</h4>


                  <div class="row">
                    <div class="form-group">

                      <div class="col-lg-8">
                          <label>Destination : </label>
                          <h5>{{$advances->Destination}}</h5>
                      </div>
                    </div>
                  </div>

                  <div class="row">
                    <div class="form-group">

                      <div class="col-lg-4">
                        <label>Start Date : </label>

                        <h5>{{$advances->Start_Date}}</h5>
                      </div>

                      <div class="col-lg-4">
                        <label>End Date : </label>

                        <h5>{{$advances->End_Date}}</h5>
                      </div>

                    </div>
                  </div>

                  <div class="row">
                    <div class="form-group">

                      <div class="col-lg-4">
                        <label>Mode Of Transport : </label>

                        <h5>{{$advances->Mode_Of_Transport}}</h5>
                      </div>

                      <div class="col-lg-4">
                        <label>Car Plate No : </label>

                        <h5>{{$advances->Car_No}}</h5>

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
                             <th>#</th>
                             <th>No of Days</th>
                             <th>Allowance (Per Day)</th>
                             <th style="width:100px;">Total (RM)</th>
                           </tr>


                          <?php $i = 0; ?>
                           @foreach($advancedetails as $advancedetail)

                             <tr id="row_{{ $i }}">
                                 @foreach($advancedetail as $key=>$value)
                                   <td>
                                     {{ $value }}
                                   </td>
                                 @endforeach
                             </tr>
                             <?php $i++; ?>

                           @endforeach

                           <tr>
                             <td></td>
                             <td colspan="2" style="text-align:right">Total Advance Requested : </td>
                             <td><input type="text" class="form-control" id="" name="" value="{{$advances->Total_Requested}}" disabled=""></td>
                           </tr>


                           <tr>
                             <td></td>
                             <td colspan="2" style="text-align:right">Total Advance Approved : </td>
                               <input type="hidden" class="form-control" id="AdvanceId" name="AdvanceId" value="{{$advanceid}}">
                             <td><input type="text" class="form-control" id="Total_Approved" name="Total_Approved"></td>
                           </tr>

                         </table>
                      </div>

                    </div>
                  </div>


                </div>

                @endforeach
              </form>

              <div class="box-footer">
                <button type="submit" class="btn btn-primary btn-lg" data-toggle="modal" data-target="#Submit">Submit & Notify</button>
                <button type="submit" class="btn btn-success" onclick="approve()">Approve</button>
                <button type="submit" class="btn btn-danger" onclick="reject({{$advanceid}})">Reject</button>
                <button type="submit" class="btn btn-warning" data-toggle="modal" data-target="#Redirect">Redirect</button>
              </div>

            </div>
          </div>
        </div>
      </div>


    </section>

</div>
<footer class="main-footer">
  <div class="pull-right hidden-xs">
    <b>Version</b> 1.0.0
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

function submit(advanceid) {

    $.ajaxSetup({
       headers: { 'X-CSRF-Token' : $('meta[name=_token]').attr('content') }
    });

      // $("#ajaxloader").show();
    $.ajax({
                url: "{{ url('/advance/submit') }}",
                method: "POST",
                  data: {AdvanceId:advanceid},

                success: function(response){

                  if (response==1)
                  {

                      var message="Submitted for next action!";
                      $("#update-alert ul").html(message);
                      $("#update-alert").modal('show');

                      $('#Submit').modal('hide');

                      $('#submitbtn').show();
                      $('#recallbtn').show();

                      // editor.disable();
                      // claimtable.api().autoFill().disable();
                      $('#status').html("Submitted for Next Approval");

                      $("#ajaxloader").hide();

                  }
                  else {

                    var errormessage="Failed to submit for next action!";
                    $("#error-alert ul").html(errormessage);
                    $("#error-alert").modal('show');

                    $('#Submit').modal('hide');

                    $("#ajaxloader").hide();

                  }

        }
    });

  }


function approve() {

    $.ajaxSetup({
       headers: { 'X-CSRF-Token' : $('meta[name=_token]').attr('content') }
    });

    $("#ajaxloader").show();

    var totalapproved=$('#Total_Approved').val();
    var advanceid={{$advanceid}};

    status="Approved";


    @if($mylevel)
      @if ($mylevel->Level=="1st Approval")
        status="1st Approved";
      @endif

      @if ($mylevel->Level=="2nd Approval")
        status="2nd Approved";
      @endif

      @if ($mylevel->Level=="3rd Approval")
        status="3rd Approved";
      @endif

      @if ($mylevel->Level=="4th Approval")
        status="4th Approved";
      @endif

      @if ($mylevel->Level=="5th Approval")
        status="5th Approved";
      @endif

      @if ($mylevel->Level=="Final Approval")
        status="Final Approved";
      @endif

  @endif

    $.ajax({
                url: "{{ url('/advance/approve') }}",
                method: "POST",
                data:{
                  Status: status,
                  Total_Approved: totalapproved,
                  AdvanceId : advanceid

                },

                success: function(response){

                  if (response==1)
                  {

                      var message="Advance application approved!";
                      $("#update-alert ul").html(message);
                      $("#update-alert").modal('show');
                      //
                      // setTimeout(function() {
                      //   $("#update-alert").fadeOut();
                      // }, 6000);

                      $("#error-alert").modal('hide');
                      $("#ajaxloader").hide();

                      window.location.href ="{{ url('/advancemanagement') }}";


                  }
                  else {
                      var obj = jQuery.parseJSON(response);
                      var errormessage ="";

                      for (var item in obj) {
                        errormessage=errormessage + "<li> " + obj[item] + "</li>";
                      }

                      $("#error-alert ul").html(errormessage);
                      $("#error-alert").modal('show');

                      // setTimeout(function() {
                      //   $("#error-alert").fadeOut();
                      // }, 6000);

                      $("#ajaxloader").hide();

                  }

        }
    });

}

function reject(advanceid) {

    $.ajaxSetup({
       headers: { 'X-CSRF-Token' : $('meta[name=_token]').attr('content') }
    });

    var status = "Rejected";

    @if($mylevel)
      @if ($mylevel->Level=="1st Approval")
        status="1st Rejected";
      @endif

      @if ($mylevel->Level=="2nd Approval")
        status="2nd Rejected";
      @endif

      @if ($mylevel->Level=="3rd Approval")
        status="3rd Rejected";
      @endif

      @if ($mylevel->Level=="4th Approval")
        status="4th Rejected";
      @endif

      @if ($mylevel->Level=="5th Approval")
        status="5th Rejected";
      @endif

      @if ($mylevel->Level=="Final Approval")
        status="Final Rejected";
      @endif

    @endif

    // $("#ajaxloader").show();

    $.ajax({

                url: "{{ url('/advance/reject') }}",
                method: "POST",
                data:{
                  AdvanceId : advanceid,
                  Status : status
                },

                success: function(response){

                  if (response==1)
                  {

                      var message="Advance application rejected!";
                      $("#update-alert ul").html(message);
                      $("#update-alert").modal('show');

                      $("#error-alert").modal('hide');
                      $("#ajaxloader").hide();

                      window.location.href ="{{ url('/advancemanagement') }}";


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

function redirect() {

    $.ajaxSetup({
       headers: { 'X-CSRF-Token' : $('meta[name=_token]').attr('content') }
    });

    // $("#ajaxloader").show();

    newapprover=$('[name="NewApprover"]').val();
    advanceid = {{$advanceid}};

    $.ajax({
                url: "{{ url('/advance/redirect') }}",
                method: "POST",
                data: {AdvanceId:advanceid,Approver:newapprover},

                success: function(response){

                  if (response==1)
                  {
                      var message="Claim redirected!";
                      $("#update-alert ul").html(message);
                      $("#update-alert").modal('show');


                      $('#Redirect').modal('hide');

                      $("#ajaxloader2").hide();

                     window.location.href ="{{ url('/advancemanagement') }}";

                  }
                  else {

                    var errormessage="Failed to redirect claim!";
                    $("#error-alert ul").html(errormessage);
                    $("#error-alert").modal('show');

                    $('#Redirect').modal('hide');

                    $("#ajaxloader2").hide();

                  }

        }
    });



}

</script>



@endsection
