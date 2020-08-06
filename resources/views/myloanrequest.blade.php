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
      table td input {
        padding: 2px;
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

      var loantable;
      var approvedtable;
      var rejectedtable;

      $(document).ready(function() {
        $("#ajaxloader2").hide();
          var $total = $('#Sum_Meal'),
              $value = $('#value');
          $value.on('input', function (e) {
              var total = 1;
              $value.each(function (index, elem) {
                  if (!Number.isNaN(parseInt(this.value, 10))) total = total * parseInt(this.value, 10);
              });
              $total.val(total);
          });

          loantable=$('#loantable').DataTable( {
                  columnDefs: [{ "visible": false, "targets": [1] },{"className": "dt-center", "targets": "_all"}],
                  responsive: false,
                  ajax: {
                     "url": "{{ asset('/Include/staffloans.php') }}",
                     "data": {
                         "UserId": {{ $me->UserId }},
                         "Status": "%Pending Approval%"
                     }
                  },
                   columns: [
                         {
                            sortable: false,title:"Action",
                            "render": function ( data, type, full, meta ) {

                              if (full.staffloanstatuses.Status!=="Cancelled" && full.staffloanstatuses.Status!=="Final Approved")
                              {
                                return '<button type="button" class="btn btn-danger btn-sm" data-toggle="modal" onclick="CancelDialog('+full.staffloans.Id+')">Cancel Loan	</button> <a class="btn btn-sm btn-danger" href="{{ url('/myloan') }}/' + full.staffloans.Id + '" target="_blank">View</a>';

                              }
                              else {
                                // return '<button type="button" class="btn btn-success btn-sm" data-toggle="modal" onclick="OpenRedirectDialog('+full.request.Id+','+full.requeststatuses.UserId+')">Redirect</button>';
                                return '';
                              }

                            }
                        },
                         { data: "staffloans.Id",title:"Id"},
                         // { data: "staffloanstatuses.Id",title:"Id"},
                         { data: "staffloanstatuses.Status",title:"Status"},
                         // { data: "staffloans.Type",title:"Type",editfield:"staffloans.Type"},
                         { data: "staffloans.Date",title:"Date",editfield:"staffloans.Date"},
                         { data: "staffloans.Purpose",title:"Purpose",editfield:"staffloans.Purpose"},
                         { data: "staffloans.Total_Requested",title:"Total Requested",editfield:"staffloans.Total_Requested"},
                         // { data: "staffloans.Total_Approved",title:"Total Approved",editfield:"staffloans.Total_Approved"},
                         { data: "projects.Project_Name",title:"Project_Name"},
                         { data: "approver.Name",title:"Approver", editfield:"request.Approver"}
                  ],
                  fnInitComplete: function(oSettings, json) {

                   $('#mypendingloantab').html("My Pending Loan" + " [" + loantable.rows().count() +"]")

                  },
                  colReorder: false,
                  dom: "fBrtip",
                  sScrollX: "100%",
                  bAutoWidth: true,
                  sScrollY: "100%",
                  scrollCollapse: true,
                  select: {
                          style:    'os',
                          selector: 'tr'
                        },
                  buttons: [

                    {
                            extend: 'collection',
                            text: 'Export',
                            buttons: [
                                    'excel',
                                    'csv',
                                    'pdf'
                            ]
                    }

                  ],

      });

      approvedtable=$('#approvedtable').DataTable( {
              columnDefs: [{ "visible": false, "targets": [1] },{"className": "dt-center", "targets": "_all"}],
              ajax: {
                 "url": "{{ asset('/Include/staffloans.php') }}",
                 "data": {
                     "UserId": {{ $me->UserId }},
                     "Status": "%Approved%"
                 }
              },
               columns: [
                     {
                        sortable: false,title:"Action",
                        "render": function ( data, type, full, meta ) {

                          if (full.staffloanstatuses.Status!=="Cancelled" && full.staffloanstatuses.Status!=="Final Approved")
                          {
                            return '<button type="button" class="btn btn-danger btn-sm" data-toggle="modal" onclick="CancelDialog('+full.staffloans.Id+')">Cancel Loan	</button>';

                          }
                          else {
                            // return '<button type="button" class="btn btn-success btn-sm" data-toggle="modal" onclick="OpenRedirectDialog('+full.request.Id+','+full.requeststatuses.UserId+')">Redirect</button>';
                            return '<a class="btn btn-sm btn-danger" href="{{ url('/myloan') }}/' + full.staffloans.Id + '" target="_blank">View</a>';
                          }

                        }
                    },
                     { data: "staffloans.Id",title:"Id"},
                     // { data: "staffloanstatuses.Id",title:"Id"},
                     { data: "staffloanstatuses.Status",title:"Status"},
                     // { data: "staffloans.Type",title:"Type",editfield:"staffloans.Type"},
                     { data: "staffloans.Date",title:"Date",editfield:"staffloans.Date"},
                     { data: "staffloans.Purpose",title:"Purpose",editfield:"staffloans.Purpose"},
                     { data: "staffloans.Total_Requested",title:"Total Requested",editfield:"staffloans.Total_Requested"},
                     // { data: "staffloans.Total_Approved",title:"Total Approved",editfield:"staffloans.Total_Approved"},
                     { data: "projects.Project_Name",title:"Project_Name"},
                     { data: "approver.Name",title:"Approver", editfield:"request.Approver"}
              ],
              fnInitComplete: function(oSettings, json) {

               $('#myapprovedloantab').html("My Approved Loan" + " [" + approvedtable.rows().count() +"]")

              },
              responsive: false,
              colReorder: false,
              dom: "fBrtip",
              sScrollX: "100%",
              bAutoWidth: true,
              sScrollY: "100%",
              scrollCollapse: true,
              select: {
                      style:    'os',
                      selector: 'tr'
                    },
              buttons: [

                {
                        extend: 'collection',
                        text: 'Export',
                        buttons: [
                                'excel',
                                'csv',
                                'pdf'
                        ]
                }

              ],

  });

  rejectedtable=$('#rejectedtable').DataTable( {
          columnDefs: [{ "visible": false, "targets": [1] },{"className": "dt-center", "targets": "_all"}],
          responsive: false,
          colReorder: false,
          dom: "fBrtip",
          ajax: {
             "url": "{{ asset('/Include/staffloans.php') }}",
             "data": {
                 "UserId": {{ $me->UserId }},
                 "Status": "%Rejected%"
             }
          },
           columns: [
                 {
                    sortable: false,title:"Action",
                    "render": function ( data, type, full, meta ) {

                      if (full.staffloanstatuses.Status!=="Cancelled" && full.staffloanstatuses.Status!=="Final Approved")
                      {
                        // return '<a class="btn btn-sm btn-danger" href="{{ url('/myloan') }}/' + full.staffloans.Id + '" target="_blank">View</a>';
                        return '';
                      }
                      else {
                        // return '<button type="button" class="btn btn-success btn-sm" data-toggle="modal" onclick="OpenRedirectDialog('+full.request.Id+','+full.requeststatuses.UserId+')">Redirect</button>';
                        return '';
                      }

                    }
                },
                 { data: "staffloans.Id",title:"Id"},
                 // { data: "staffloanstatuses.Id",title:"Id"},
                 { data: "staffloanstatuses.Status",title:"Status"},
                 // { data: "staffloans.Type",title:"Type",editfield:"staffloans.Type"},
                 { data: "staffloans.Date",title:"Date",editfield:"staffloans.Date"},
                 { data: "staffloans.Purpose",title:"Purpose",editfield:"staffloans.Purpose"},
                 { data: "staffloans.Total_Requested",title:"Total Requested",editfield:"staffloans.Total_Requested"},
                 // { data: "staffloans.Total_Approved",title:"Total Approved",editfield:"staffloans.Total_Approved"},
                 { data: "projects.Project_Name",title:"Project_Name"},
                 { data: "approver.Name",title:"Approver", editfield:"request.Approver"}
          ],
          fnInitComplete: function(oSettings, json) {

           $('#myrejectedloantab').html("My Rejected Loan" + " [" + rejectedtable.rows().count() +"]")

          },
          sScrollX: "100%",
          bAutoWidth: true,
          sScrollY: "100%",
          scrollCollapse: true,
          select: {
                  style:    'os',
                  selector: 'tr'
                },
          buttons: [

            {
                    extend: 'collection',
                    text: 'Export',
                    buttons: [
                            'excel',
                            'csv',
                            'pdf'
                    ]
            }

          ],

});

      $('a[data-toggle="tab"]').on( 'shown.bs.tab', function (e) {
        var target = $(e.target).attr("href") // activated tab

          $.fn.dataTable.tables( {visible: true, api: true} ).columns.adjust();

      } );

      });

      </script>

      @endsection

@section('content')

  <div class="content-wrapper">
      <!-- Content Header (Page header) -->
    <section class="content-header">
      <h1>
      My Loan
      <small>My Workplace</small>
      </h1>

      <ol class="breadcrumb">
      <li><a href="{{ url('/home') }}"><i class="fa fa-dashboard"></i> Home</a></li>
      <li><a href="#">My Workplace</a></li>
      <li class="active">My Loan</li>
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
          <div class="nav-tabs-custom">
            <ul class="nav nav-tabs">
              <li class="active"><a href="#loanapplicationform" data-toggle="tab">My Loan Application Form</a></li>
              <li><a href="#mypendingloan" data-toggle="tab" id="mypendingloantab">My Pending Loan</a></li>
              <li><a href="#myapprovedloan" data-toggle="tab" id="myapprovedloantab">My Approved Loan</a></li>
              <li><a href="#myrejectedloan" data-toggle="tab" id="myrejectedloantab">My Rejected Loan</a></li>
            </ul>

          <div class="tab-content">

            <div class="tab-pane" id="mypendingloan">

              <table id="loantable" class="display" cellspacing="0" width="100%" padding="30px" style="font-size: 13px;">
              </table>

            </div>

            <div class="tab-pane" id="myapprovedloan">

              <table id="approvedtable" class="display" cellspacing="0" width="100%" padding="30px" style="font-size: 13px;">
              </table>

            </div>

            <div class="tab-pane" id="myrejectedloan">

              <table id="rejectedtable" class="display" cellspacing="0" width="100%" padding="30px" style="font-size: 13px;">
              </table>

            </div>

            <div class="active tab-pane" id="loanapplicationform">




                  <form enctype="multipart/form-data" id="upload_form" role="form" method="POST" action="" >
                    <input type="hidden" name="Type" value="personal">

                    <div class="box-body">
                    <h3>EMPLOYEE LOAN FORM</h3>


                        <h4>Staff Details</h4>

                        <input type="hidden" name="UserId" value="{{ $me->UserId }}">

                        <div class="row">
                          <div class="form-group">

                            <div class="col-lg-4">
                              <label>Name : </label>
                                 <input type="text" class="form-control" id="Name" name="Name" value="{{$user->Name}}" disabled>
                                 <input type="hidden" class="form-control" id="UserId" name="UserId" value="{{$user->Id}}" disabled>
                            </div>

                            <div class="col-lg-4">
                               <label> Bank Account : </label>
                               <select class="form-control select2" id="Bank_Account_No" name="Bank_Account_No" style="width: 100%;">
                                  <option value="{{ $me->Bank_Account_No }}">{{   $me->Bank_Name }} - {{  $me->Bank_Account_No }}</option>
                               </select>
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
                               <label> Repayment (month) : </label>
                               <select class="form-control select2" id="Repayment" name="Repayment" style="width: 100%;">
                                  <option value=""></option>
                                  <option value="1">1</option>
                                  <option value="2">2</option>
                                  <option value="3">3</option>
                                  <option value="4">4</option>

                               </select>
                            </div>

                        </div>
                      </div>
                      @if($type=='site')
                      <div class="row">
                        <div class="form-group">

                            <div class="col-lg-4">
                                <label>Site Name : </label>
                                <input type="text" class="form-control" id="SiteName" name="SiteName" style="width: 100%;">
                            </div>

                        </div>
                      </div>
                      @endif


                      <br>


                      <br>

                      <h4>Loan Required</h4>

                      <div class="row">

                          <div class="col-lg-8">

                            <table class="table table-bordered">
                               <tr>
                                 <th></th>
                                 <th style="width:80px;">Amount (RM)</th>
                               </tr>
                               <tr>
                                 <td ><input type="text" class="form-control" id="Personal" name="Purpose" placeholder="Purpose of loan.." value=""></td>
                                 <td><input type="number" class="form-control" id="Sum7" name="Sum7" style="padding: 3px;" value="0.00"></td>
                               </tr>
                               <tr>
                                 <td  style="text-align:right">Total Loan Requested : </td>
                                 <td><input type="number" class="form-control" id="Total_Requested" name="Total_Requested" style="padding: 3px;" placeholder="0.00" readonly></td>
                               </tr>
                             </table>
                          </div>


                      </div>


                    </div>

                  </form>

                  <div class="box-footer">
                    <button type="submit" class="btn btn-primary" id="applyloanbutton" onclick="applyloan()">Submit</button>
                  </div>



            </div>

          </div>

        </div>

      </div>

      </div>


        <div class="modal fade" id="Cancel" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
          <div class="modal-dialog" role="document">
            <div class="modal-content">
              <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title" id="myModalLabel">Cancel Requst</h4>
              </div>
              <div class="modal-body">
                <div class="form-group" id="cancelloan">

                </div>
                  Are you sure you wish to cancel this loan?
              </div>
              <div class="modal-footer">
                <center><img src="{{ URL::to('/') ."/img/ajax-loader.gif" }}" width="50px" height="50px" alt="Loading" name='ajaxloader2' id="ajaxloader2"></center>
                <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                <button type="button" class="btn btn-primary" onclick="cancelloan()">Cancel Loan	</button>
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

  // $('#Purpose').hide();
  //Initialize Select2 Elements
  $(".select2").select2();

  //Flat red color scheme for iCheck
  $('input[type="checkbox"].flat-green, input[type="radio"].flat-green').iCheck({
    checkboxClass: 'icheckbox_flat-green',
    radioClass: 'iradio_flat-green'
  });

  //Repayment
  $('#Repayment').val();

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

function applyloan() {

    $.ajaxSetup({
       headers: { 'X-CSRF-Token' : $('meta[name=_token]').attr('content') }
    });

    $("#ajaxloader").show();
    $("#applyloanbutton").prop('disabled', true);


    $.ajax({
                url: "{{ url('/myloan/apply') }}",
                method: "POST",
                contentType: false,
                processData: false,
                data:new FormData($("#upload_form")[0]),

                success: function(response){

                  if (response==1)
                  {

                      var message="Loan application submitted!";
                      $("#update-alert ul").html(message);
                      $("#update-alert").modal('show');
                      $("#error-alert").modal('hide');
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
                      $("#applyloanbutton").prop('disabled', false);


                  }

        }
    });

}


$('#Sum7').change(function() {
   //do stuff

   var getVal = Number($('#Sum7').val());
   var type = "{{$type}}"

   if(type=="Monthly Personal Loan"){
     if(getVal>300){
       $("#error-alert ul").html('Montly Personal Loan allowed to loan maximum RM300!');
       $("#error-alert").modal('show');
     }
   }

   $('#Total_Requested').val((parseFloat($('#Sum7').val())).toFixed(2));

});

function CancelDialog(id)
  {

    var hiddeninput='<input type="hidden" class="form-control" id="cancelloanid" name="cancelloanid" value="'+id+'">';

      $( "#cancelloan" ).html(hiddeninput);
      $('#Cancel').modal('show');

  }

  function cancelloan() {

          $.ajaxSetup({
             headers: { 'X-CSRF-Token' : $('meta[name=_token]').attr('content') }
          });

          $("#ajaxloader2").show();

          loanid=$('[name="cancelloanid"]').val();

          $.ajax({
                      url: "{{ url('/myloan/cancel') }}",
                      method: "POST",
                      data: {Id:loanid},

                      success: function(response){

                        if (response==1)
                        {
                            // loantable.ajax.reload();
                            // loan2table.ajax.reload();
                            // loan3table.ajax.reload();
                            // loan4table.ajax.reload();
                            var message="Loan cancelled!";
                            $("#update-alert ul").html(message);
                            $("#update-alert").modal('show');

                            $('#Cancel').modal('hide');

                            $("#ajaxloader2").hide();

                        }
                        else {

                          var errormessage="Failed to cancel loan!";
                          $("#error-alert ul").html(errormessage);
                          $("#error-alert").modal('show');


                          $('#Cancel').modal('hide');

                          $("#ajaxloader2").hide();

                        }

              }
          });
  }
</script>

@endsection
