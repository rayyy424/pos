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
      .icon{
        padding-top:10px;
      }
      .pre {
     white-space: pre-line;
  }
      .tableheader{
        background-color: gray;
      }
      .assettable{
        text-align: center;
      }
    .widget-user .widget-user-username {
        font-size:16px;
      }
      .contentbox{
        width: 800px;
      }

      .alert-reminder{
        background-color: darkblue; 
        color : white;
      }
    </style>

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

          <script type="text/javascript" language="javascript" class="init">
          var oTable;
          var oTable2;
          var oTable3;
          var oTable4;
      $(document).ready(function() {
          $('#ChangePassword').modal({
            backdrop: 'static',
            keyboard: false
          })
          $('#ChangePassword').modal('show');
          oTable=$('#assettable').dataTable( {
                ajax: {
                   "url": "{{ asset('/Include/assettracking.php') }}",
                   "data": {
                       "userid": "{{ $me->UserId }}"
                   }
                 },
                  columnDefs: [{ "visible": false, "targets": [1,2,3] },{"className": "dt-center", "targets": "_all"}],
                  colReorder: false,
                  bAutoWidth: true,
                  dom: "Brt",
                  scrollY: "100%",
                  scrollX: "100%",
                  scrollCollapse: true,
                  bSort:false,
                  scrollCollapse: true,
                  rowId:"assets.Id",
                  bPaginate:false,
                  columns: [
                            {
                               sortable: false,
                               "render": function ( data, type, full, meta ) {
                                      if (full.assettrackings.Transfer_To=="{{$me->UserId}}")
                                      {
                                          return "<div class='action'><a href='#' onclick='acknowledge("+full.assets.Id+","+full.assettrackings.Id+");'>Acknowledge</a></div>";
                                      }
                                      else  (full.assettrackings.UserId=="{{$me->UserId}}")
                                      {
                                          return "<div class='action'><a href='#' onclick='transfer("+full.assettrackings.Id+");'>Transfer</a> | <a href='#' onclick='report("+full.assettrackings.Id+");'>Report</a></div>";
                                      }
                               }
                           },
                          { data: "assets.Id",title:"Id"},
                          { data: "assettrackings.Id",title:"TrackingId"},
                          { data: "assettrackings.UserId",title:"UserId"},
                          { data: "assets.Label" ,title:"Label"},
                          { data: "assets.Type" ,title:"Type"},
                          { data: "assets.Serial_No" ,title:"Serial_No"},
                          { data: "assets.IMEI" ,title:"IMEI"},
                          { data: "assets.Model_No" ,title:"Model_No"},
                          { data: "assets.Car_No" ,title:"Car_No"},
                          { data: "assets.Color" ,title:"Color"},
                          { data: "assettrackings.Date" ,title:"Taken_Date"},
                          { data: "transfer.Name" ,title:"Transfer_To"},
                          { data: "assettrackings.Transfer_Date_Time" ,title:"Transfer_Date_Time"},
                          { data: "assettrackings.Remarks" ,title:"Remarks"}
                  ],
                  buttons: [
                  ],
      });
                oTable1=$('#returnasset').dataTable( {
                        columnDefs: [{ "visible": false, "targets": [] },{"className": "dt-center", "targets": "_all"}],
                        colReorder: false,
                        bAutoWidth: true,
                        dom: "Brt",
                        scrollY: "100%",
                        scrollX: "100%",
                        scrollCollapse: true,
                        bSort:false,
                        scrollCollapse: true,
                        rowId:"assets.Id",
                        bPaginate:false,
                        columns: [
                          {data:'assets.Label', title:'Label'},
                          {data:'assets.Type', title:'Type'},
                          {data:'assets.Serial_No', title:'Serial_No'},
                          {data:'assets.IMEI', title:'IMEI'},
                          {data:'assets.Brand', title:'Brand'},
                          {data:'assets.Model_No', title:'Model_No'},
                          {data:'assets.Car_No', title:'Car_No'},
                          {data:'assets.Color', title:'Color'},
                          {data:'assets.assets.Rental_Start_Date', natitleme:'Rental_Start_Date'},
                          {data:'assets.Rental_End_Date', title:'Rental_End_Date'}
                        ],
                        buttons: [
                        ],
            });
            oTable2=$('#renewlicense').dataTable( {
                    columnDefs: [{ "visible": false, "targets": [0] },{"className": "dt-center", "targets": "_all"}],
                    colReorder: false,
                    bAutoWidth: true,
                    dom: "Brt",
                    scrollY: "100%",
                    scrollX: "100%",
                    scrollCollapse: true,
                    bSort:false,
                    scrollCollapse: true,
                    rowId:"assets.Id",
                    bPaginate:false,
                    columns: [
                      {data:'licenses.Id', title:'Id'},
                      {data:'License_Type', title:'Type'},
                      {data:'Identity_No', title:'Identity_No'},
                      {data:'Issue_Date', title:'Issue_Date'},
                      {data:'Expiry_Date', title:'Expiry_Date'},
                      {data:'License_Status', title:'License_Status'}
                    ],
                    buttons: [
                    ],
        });
        oTable3=$('#internend').dataTable( {
                columnDefs: [{ "visible": false, "targets": [0] },{"className": "dt-center", "targets": "_all"}],
                colReorder: false,
                bAutoWidth: true,
                dom: "Brt",
                scrollY: "100%",
                scrollX: "100%",
                scrollCollapse: true,
                bSort:false,
                scrollCollapse: true,
                rowId:"assets.Id",
                bPaginate:false,
                columns: [
                  {data:'Id', title:'Id'},
                  {data:'StaffId', title:'StaffId'},
                  {data:'Name', title:'Name'},
                  {data:'Internship_Start_Date', title:'Internship_Start_Date'},
                  {data:'Internship_End_Date', title:'Internship_End_Date'},
                  {data:'Internship_Status', title:'Internship_Status'}
                ],
                buttons: [
                ],
    });
            oTable4=$('#confirmationlisttable').dataTable( {
                    columnDefs: [{ "visible": false, "targets": [0,2] },{"className": "dt-center", "targets": "_all"}],
                    colReorder: false,
                    bAutoWidth: true,
                    dom: "Brt",
                    scrollY: "100%",
                    scrollX: "100%",
                    scrollCollapse: true,
                    bSort:false,
                    scrollCollapse: true,
                    rowId:"users.Id",
                    bPaginate:false,
                    columns: [
                      {
                         sortable: false,
                         "render": function ( data, type, full, meta ) {
                              return "<a href='user/"+full.Id+"'>View</a>";
                         }
                      },
                      {data:'No', title:'No'},
                      {data:'Id', title:'Id'},
                      {data:'StaffId', title:'Staff Id'},
                      {data:'Name', title:'Name'},
                      {data:'Grade', title:'Grade'},
                      {data:'Company', title:'Company'},
                      {data:'Position', title:'Position'},
                      {data:'Joining_Date', title:'Joining Date'},
                      {data:'Confirmation_Date', title:'Confirmation Date'},
                    ],
                    buttons: [
                    ],
        });
      $("#ajaxloader").hide();
      $("#ajaxloader2").hide();
      $("#ajaxloader5").hide();
      } );
    </script>

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

@endsection

@section('content')

    <div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
      <h1>
        Dashboard
      </h1>
      <ol class="breadcrumb">
        <li><a href="#"><i class="fa fa-dashboard"></i> Home</a></li>
        <li class="active">Dashboard</li>
      </ol>
    </section>

    @if($me->First_Change==0)
      <div class="modal fade" id="ChangePassword" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
        <div class="modal-dialog" role="document">
          <div class="modal-content">
            <div class="modal-header">
              <div id="exist-alert" class="alert alert-warning alert-dismissible" style="display:none;">
                <h4><i class="icon fa fa-check"></i> Alert!</h4>
                <div id="changepasswordmessage"></div>
              </div>
              <h4 class="modal-title" id="myModalLabel">Change Your Password</h4>
              <h6 class="modal-title" id="myModalLabel">The current password is a default password. Please change his password to a more secure value.</h6>
            </div>

            <div class="modal-body">
              <h6 class="modal-title" id="myModalLabel">Please enter a new password in the fields below.</h6>
              <div class="form-group" padding="10px">
                <div class="form-group">
                  <label class="col-md-4 control-label">Password</label>
                  <div class="col-md-6">
                    <input type="password" class="form-control" name="Password3">
                  </div>
                </div>
                <div class="form-group">
                  <label class="col-md-4 control-label">Confirm Password</label>
                  <div class="col-md-6">
                    <input type="password" class="form-control" name="ConfirmPassword">
                  </div>
                </div>
              </div>
            </div>

            <div class="modal-footer">
              <button type="button" class="btn btn-primary" onclick="changepassword()">Update</button>
            </div>
          </div>
        </div>
      </div>

    @endif

    <div class="modal fade" id="TransferModal" role="dialog" aria-labelledby="myModalLabel">
      <div class="modal-dialog">
        <div class="modal-content">
          <div class="modal-header">
            <div id="transfer-alert" class="alert alert-warning alert-dismissible" style="display:none;">
              <h4><i class="icon fa fa-check"></i> Alert!</h4>
              <div id="transfermessage"></div>
            </div>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
            <h4 class="modal-title" id="myModalLabel">Transfer Asset</h4>

          </div>

          <div class="modal-body">

            <div class="form-group">

              <div class="input-group date">
                <input type="hidden" class="form-control pull-right" id="Date" name="Date" value="{{ date("d-M-Y") }}">
              </div>
              <!-- /.input group -->
            </div>

            <input type="hidden" id="transfertrackingid" name="transfertrackingid" value=0>

              <div class="form-group">
                <label>Transfer to [Staff]</label>


                  <select class="form-control select2" id="UserId" name="UserId" style="width: 100%;">
                     <option value="0"></option>
                    @foreach ($users as $user)
                       <option value="{{$user->Id}}">{{$user->Name}}</option>
                    @endforeach
                  </select>

              </div>

          </div>
          <div class="modal-footer">
            <center><img src="{{ URL::to('/') ."/img/ajax-loader.gif" }}" width="50px" height="50px" alt="Loading" name='ajaxloader' id="ajaxloader"></center>
            <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
            <button type="button" class="btn btn-primary" onclick="calltransfer()">Transfer</button>
          </div>
        </div>
      </div>
    </div>

    <div class="modal fade" id="ReportModal" role="dialog" aria-labelledby="myModalLabel">
      <div class="modal-dialog">
        <div class="modal-content">
          <div class="modal-header">
            <div id="report-alert" class="alert alert-warning alert-dismissible" style="display:none;">
              <h4><i class="icon fa fa-check"></i> Alert!</h4>
              <div id="reportmessage"></div>
            </div>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
            <h4 class="modal-title" id="myModalLabel">Report Asset</h4>

          </div>

          <div class="modal-body">

            <div class="form-group">

              <div class="input-group date">
                <input type="hidden" class="form-control pull-right" id="Date" name="Date" value="{{ date("d-M-Y") }}">
              </div>
              <!-- /.input group -->
            </div>

            <input type="hidden" id="reportassetid" name="reportassetid" value=0>

              <div class="form-group">
                <label>Issue</label>
                <textarea class="form-control" id="reportdetails" name="reportdetails" rows="3" placeholder="Enter ..."></textarea>
              </div>
              <div class="form-group">
                <label>Need replacement?</label>
                <select class="form-control select2" id="replacement" name="replacement" style="width: 100%;">
                     <option value="0"></option>
                     <option value="yes">Yes</option>
                     <option value="No">No</option>
                </select>
              </div>

          </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
            <button type="button" class="btn btn-primary" onclick="callreport()">Done</button>
          </div>
        </div>
      </div>
    </div>

    <div class="modal fade" id="AcknowledgeModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
      <div class="modal-dialog" role="document">
        <div class="modal-content">
          <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
            <h4 class="modal-title" id="myModalLabel">Acknowledge</h4>
          </div>
          <div class="modal-body">
               <input type="hidden" id="acknowledgeassetid" name="acknowledgeassetid" value=0>
               <input type="hidden" id="acknowledgetrackingid" name="acknowledgetrackingid" value=0>
              Are you sure you receive of the asset?
          </div>
          <div class="modal-footer">
            <center><img src="{{ URL::to('/') ."/img/ajax-loader.gif" }}" width="50px" height="50px" alt="Loading" name='ajaxloader2' id="ajaxloader2"></center>
            <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
            <button type="button" class="btn btn-primary" onclick="callacknowledge()">Yes</button>
          </div>
        </div>
      </div>
    </div>

    <!--on leave list -->

    <div class="modal fade" id="scheduleview" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
        <div class="modal-dialog" role="document">
          <div class="modal-content">
            <div class="modal-header">
              <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
              <h4 class="modal-title" id="myModalLabel">Schedules</h4>
            </div>
            <div class="modal-body" name="schedulelabel" id="schedulelabel">

            </div>
            <div class="modal-footer">
              <center><img src="{{ URL::to('/') ."/img/ajax-loader.gif" }}" width="50px" height="50px" alt="Loading" name='ajaxloader6' id="ajaxloader6"></center>
              <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
            </div>
          </div>
        </div>
    </div>

    <div class="modal fade" id="myscheduleview" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
        <div class="modal-dialog" role="document">
          <div class="modal-content">
            <div class="modal-header">
              <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
              <h4 class="modal-title" id="myModalLabel">My Schedules</h4>
            </div>
            <div class="modal-body" name="myschedulelabel" id="myschedulelabel">

            </div>
            <div class="modal-footer">
              <center><img src="{{ URL::to('/') ."/img/ajax-loader.gif" }}" width="50px" height="50px" alt="Loading" name='ajaxloader7' id="ajaxloader7"></center>
              <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
            </div>
          </div>
        </div>
    </div>

    <div class="modal fade" id="roadtaxview" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
        <div class="modal-dialog" role="document">
          <div class="modal-content">
            <div class="modal-header">
              <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
              <h4 class="modal-title" id="myModalLabel">Roadtax List</h4>
            </div>
            <div class="modal-body" name="roadtaxlabel" id="roadtaxlabel">

            </div>
            <div class="modal-footer">
              <center><img src="{{ URL::to('/') ."/img/ajax-loader.gif" }}" width="50px" height="50px" alt="Loading" name='ajaxloader7' id="ajaxloader7"></center>
              <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
            </div>
          </div>
        </div>
    </div>

    <!-- Main content -->
    <section class="content">

      <div class="row">

        <div id="reminder-alert" class="alert alert-reminder alert-dismissible">
            <button type="button" class="close" onclick="$('#reminder-alert').hide()" style="color: yellow">&times;</button>
            <h4><i class="icon fa fa-warning"></i>Reminder</h4>
            <ul>
              Mind the spaces / special characters (i.e. Tab, %) within the crucial data.
            </ul>
          </div>

        <div id="update-alert" class="alert alert-success alert-dismissible" style="display:none;">
          <button type="button" class="close" onclick="$('#update-alert').hide()" aria-hidden="true">&times;</button>
          <h4><i class="icon fa fa-check"></i> Alert!</h4>
          <ul>

          </ul>
        </div>

        @if( $me->Status=="Account Detail Rejected")

          <div id="warning-alert" class="alert alert-warning alert-dismissible">
            <button type="button" class="close" onclick="$('#warning-alert').hide()">&times;</button>
            <h4><i class="icon fa fa-ban"></i> Alert!</h4>
            <ul>
              Account Detail Rejected<br>
              Comment : {{$me->Comment}}
            </ul>
          </div>

        @elseif( $me->Status=="Pending Account Detail Approval" )

          <div id="warning-alert" class="alert alert-warning alert-dismissible">
            <button type="button" class="close" onclick="$('#warning-alert').hide()">&times;</button>
            <h4><i class="icon fa fa-ban"></i> Alert!</h4>
            <ul>
              Pending Account Detail Approval
            </ul>
          </div>

        @elseif( $me->Status=="Initial Update Required" || $interval==-1 )

          <div id="warning-alert" class="alert alert-warning alert-dismissible">
            <button type="button" class="close" onclick="$('#warning-alert').hide()">&times;</button>
            <h4><i class="icon fa fa-ban"></i> Alert!</h4>
            <ul>
              Initial account update required. Please fill in your personal detail and submit for approval.
            </ul>
          </div>

        @elseif ($interval>=6)

          <div id="warning-alert" class="alert alert-warning alert-dismissible">
            <button type="button" class="close" onclick="$('#warning-alert').hide()">&times;</button>
            <h4><i class="icon fa fa-ban"></i> Alert!</h4>
            <ul>
              Your last profile update is more than 6 months ago. Please update your profile and submit for approval.
            </ul>
          </div>
        @else
          <div id="warning-alert" class="alert alert-warning alert-dismissible" style="display:none;">
            <button type="button" class="close" onclick="$('#warning-alert').hide()">&times;</button>
            <h4><i class="icon fa fa-ban"></i> Alert!</h4>
            <ul>

            </ul>
          </div>

        @endif

      </div>

      <!--name list -->
      <div class="modal fade" id="labelview" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
          <div class="modal-dialog" role="document">
            <div class="modal-content">
              <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title" id="myModalLabel">Asset List</h4>
              </div>
              <div class="modal-body" name="label" id="label">

              </div>
              <div class="modal-footer">
                <center><img src="{{ URL::to('/') ."/img/ajax-loader.gif" }}" width="50px" height="50px" alt="Loading" name='ajaxloader3' id="ajaxloader3"></center>
                <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
              </div>
            </div>
          </div>
      </div>

      <!--on leave list -->
      <div class="modal fade" id="onleaveview" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
          <div class="modal-dialog" role="document">
            <div class="modal-content">
              <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title" id="myModalLabel">Staff On Leave Today</h4>
              </div>
              <div class="modal-body" name="onleavelabel" id="onleavelabel">

              </div>
              <div class="modal-footer">
                <center><img src="{{ URL::to('/') ."/img/ajax-loader.gif" }}" width="50px" height="50px" alt="Loading" name='ajaxloader4' id="ajaxloader4"></center>
                <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
              </div>
            </div>
          </div>
      </div>

      <div class="modal fade" id="summons" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
          <div class="modal-dialog" role="document">
            <div class="modal-content contentbox">
              <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title" id="myModalLabel">My Summons</h4>
              </div>
              <div class="modal-body" name="summonlabel" id="summonlabel">

                @if($summons)

                  <table border="1" align="center" class="summontable" cellspacing="0" width="100%" padding="30px" style="font-size: 13px;">
                    <thead>
                        {{-- prepare header search textbox --}}
                        <tr>
                          @foreach($summons as $key=>$line)
                            @if($key==0)
                              @foreach($line as $field=>$value)
                                <th>
                                  {{$field}}
                                </th>
                              @endforeach
                            @endif
                          @endforeach
                        </tr>
                    </thead>
                    <tbody>

                      @foreach($summons as $line)
                        <tr>
                        @foreach($line as $field=>$value)
                          <td>
                            {{$value}}
                          </td>
                        @endforeach
                        </tr>
                      @endforeach
                    </tbody>
                  </table>

                @endif

              </div>
              <div class="modal-footer">

                <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
              </div>
            </div>
          </div>
      </div>

      <div class="modal fade" id="deductions" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
          <div class="modal-dialog" role="document">
            <div class="modal-content contentbox">
              <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title" id="myModalLabel">My Deduction</h4>
              </div>
              <div class="modal-body" name="deductionlabel" id="deductionlabel">

                @if($summons)

                  <table border="1" align="center" class="deductiontable" cellspacing="0" width="100%" padding="30px" style="font-size: 13px;">
                    <thead>
                        {{-- prepare header search textbox --}}
                        <tr>
                          @foreach($deductions as $key=>$line)
                            @if($key==0)
                              @foreach($line as $field=>$value)
                                <th>
                                  {{$field}}
                                </th>
                              @endforeach
                            @endif
                          @endforeach
                        </tr>
                    </thead>
                    <tbody>

                      @foreach($deductions as $line)
                        <tr>
                        @foreach($line as $field=>$value)
                          <td>
                            {{$value}}
                          </td>
                        @endforeach
                        </tr>
                      @endforeach
                    </tbody>
                  </table>

                @endif

              </div>
              <div class="modal-footer">

                <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
              </div>
            </div>
          </div>
      </div>

      <div class="row">

        <div class="col-md-3">

          <div class="row">

            <div class="col-lg-12 col-xs-12">
            <!-- Horizontal Form -->
                <div class="box box-primary">
                  <div class="box-header with-border">
                    <h3 class="box-title">Calendar</h3>
                    <div id="calendar"></div>
                  </div>

                </div>
            </div>

          </div>

        </div>

        <div class="col-md-9">

          <div class="col-lg-3 col-xs-3">
            <!-- small box -->
            @if($annualbalance<=0)
              <div class="small-box bg-red">
            @else
              <div class="small-box bg-green">
            @endif
              <div class="inner">
                <h3>{{$annualbalance}}</h3>
                <p>Annual Leave Balance</p>
              </div>

                <a href="{{ url('/myleave') }}" class="small-box-footer">More info <i class="fa fa-arrow-circle-right"></i></a>
              </div>
          </div>

          <div class="col-lg-3 col-xs-3">
            <!-- small box -->
            @if($sickbalance<=0)
              <div class="small-box bg-red">
            @else
              <div class="small-box bg-green">
            @endif
              <div class="inner">
                <h3>{{$sickbalance}}</h3>
                <p>Medical Leave Balance</p>
              </div>

                <a href="{{ url('/myleave') }}" class="small-box-footer">More info <i class="fa fa-arrow-circle-right"></i></a>
              </div>
          </div>

            @if ($pendingleaves[0]->pending>0)
                <div class="col-lg-3 col-xs-3">
                  <!-- small box -->
                  <div class="small-box bg-red">
                    <div class="inner">
                      <h3>{{$pendingleaves[0]->pending}}</h3>
                      <p>Pending Leave</p>
                    </div>

                      <a href="{{ url('/leavemanagement2') }}" class="small-box-footer">More info <i class="fa fa-arrow-circle-right"></i></a>
                    </div>
                </div>
            @endif

            @if ($rejectedleaves[0]->rejected>0)
                <div class="col-lg-3 col-xs-3">

                  <div class="small-box bg-red">
                    <div class="inner">
                      <h3>{{$rejectedleaves[0]->rejected}}</h3>
                      <p>Rejected Leave</p>
                    </div>

                      <a href="{{ url('/myleave') }}" class="small-box-footer">More info <i class="fa fa-arrow-circle-right"></i></a>
                    </div>
                </div>
            @endif

            <div class="col-lg-3 col-xs-3">
              <div class="small-box bg-red">
                <div class="inner">
                  @if($deductamount)
                    <h3>{{ $deductamount->Amount }}</h3>
                  @elseif ($deductamount)
                    <h3>RM 0.00</h3>
                  @endif
                  <p>My Deduction</p>
                </div>

                    <a href="#" onclick="deduction();" class="small-box-footer">More info <i class="fa fa-arrow-circle-right"></i></a>
                </div>
              </div>


              @if($me->Admin)
                  <div class="col-lg-3 col-xs-3">
                    <div class="small-box bg-red">
                      <div class="inner">

                          <h3>{{  $staffconfirmationcount }}</h3>

                        <p>Staff Confirmation This Month</p>
                      </div>

                          <a href="{{ url('user') }}" class="small-box-footer">More info <i class="fa fa-arrow-circle-right"></i></a>
                    </div>
                  </div>

              @endif


        </div>

      </div>

      @if($assetsummary)

        <div class="row">

          <div class="col-md-12">

            <h4 class="box-title"><b>Asset Tracking<b></h4>

          </div>

                <div class="col-lg-2 col-xs-6">
                  <!-- small box -->
                  <div class="small-box bg-green">
                    <div class="inner">
                      @foreach($assetsummary as $item)
                      <h3>@if($item -> Laptop!=null)
                            {{$item -> Laptop}} 
                          @else 
                            0 
                          @endif 
                          /
                          @if($item -> LaptopTotal!=null)
                            {{$item -> LaptopTotal}} 
                          @else 
                            0 
                          @endif
                      </h3>
                      @endforeach

                      <p>Laptop<br><br></p>
                    </div>
                    <div class="icon">
                      <i class="ion ion-android-laptop"></i>
                    </div>
                    <a href="{{ url('/assettracking') }}/IT APPLIANCES" target="_blank" class="small-box-footer">More info <i class="fa fa-arrow-circle-right"></i></a>
                  </div>
                </div>

                <div class="col-lg-2 col-xs-6">
                  <!-- small box -->
                  <div class="small-box bg-green">
                    <div class="inner">
                      @foreach($assetsummary as $item)
                      <h3>@if($item -> Desktop!=null)
                            {{$item -> Desktop}} 
                          @else 
                            0 
                          @endif 
                          /
                          @if($item -> DesktopTotal!=null)
                            {{$item -> DesktopTotal}} 
                          @else 
                            0 
                          @endif
                      </h3>
                      @endforeach

                      <p>Desktop<br><br></p>
                    </div>
                    <div class="icon">
                      <i class="ion ion-android-laptop"></i>
                    </div>
                    <a href="{{ url('/assettracking') }}/IT APPLIANCES" target="_blank" class="small-box-footer">More info <i class="fa fa-arrow-circle-right"></i></a>
                  </div>
                </div>

            </div>
    @endif

    <div class="row">
      <div class="col-xs-12 table-responsive">

        <div class="box box-primary">

          <div class="box-header with-border">
            <h3 class="box-title">Assets on Hand</h3><p class="text-muted">Please inform Admin to update "Asset Tracking" if you are not the asset holder anymore.</p>
          </div>

            <table id="assettable" class="display" cellspacing="0" width="100%" padding="30px" style="font-size: 13px;">
              <thead>
                  {{-- prepare header search textbox --}}
                  <tr>
                    <th>Action</th>
                    <th>Id</th>
                    <th>TrackingId</th>
                    <th>UserId</th>
                    <th>Label</th>
                    <th>Type</th>
                    <th>Serial_No</th>
                    <th>IMEI</th>
                    <th>Model_No</th>
                    <th>Car_No</th>
                    <th>Color</th>
                    <th>Date Taken</th>
                    <th>Transfer To</th>
                    <th>Transfer Date Time</th>
                    <th>Remarks</th>
                  </tr>
              </thead>
              <tbody>

                <?php $i = 0; ?>
                @foreach($assets as $item)

                  <tr id="row_{{ $i }}">
                      <td></td>
                      @foreach($item as $key=>$value)
                          <td>
                            {{ $value }}
                          </td>

                      @endforeach
                  </tr>

                  <?php $i++; ?>
                @endforeach

            </tbody>
              </table>

        </div>

      </div>
      <!-- /.col -->
    </div>

    {{-- <div class="row">
      <div class="col-lg-2 col-xs-6">
        <h4 class="box-title"><b>IT Support<b></h4>
        <!-- small box -->
      </div>
      <div class="col-lg-2 col-xs-6">
        <h4 class="box-title"><b>&nbsp<b></h4>
        <!-- small box -->
      </div>
    </div> --}}


    {{-- @if($mytask)
      <div class="row">
        <div class="col-md-12">
          <h4 class="box-title"><b>My Tasks<b></h4>
        </div>
            <div class="col-lg-2 col-xs-6">
              <!-- small box -->
              <div class="small-box bg-red">
                <div class="inner">
                  <h3>{{$mytask[0]->task}}</h3>
                  <p>My Tasks</p>
                </div>
                <div class="icon">
                  <i class="ion ion-clipboard"></i>
                </div>
                  <a href="{{ url('/projecttracker') }}/19" class="small-box-footer">More info <i class="fa fa-arrow-circle-right"></i></a>
                </div>
            </div>
            @if($myschedules)
              <div class="col-lg-2 col-xs-6">
                <!-- small box -->
                <div class="small-box bg-green">
                  <div class="inner">
                    <h3>{{$myschedules->schedule}}</h3>
                    <p>My Event & Training Schedules</p>
                  </div>
                  <div class="icon">
                    <i class="ion ion-ios-person"></i>
                  </div>
                  <a href="#" onclick="myscheduleview();" class="small-box-footer">More info <i class="fa fa-arrow-circle-right"></i></a>
                </div>
              </div>
            @endif
      </div>
  @endif --}}



      @if($me->Admin)
          <div class="row">
            <div class="col-xs-12 table-responsive">

              <div class="box box-primary">

                <div class="box-header with-border">
                  <h3 class="box-title">Staff Confirmation List </h3><p class="text-muted">Between {{ date("d-M-Y", strtotime("+20 day", strtotime("first day of last month"))) }} and {{ date("d-M-Y", strtotime("+19 day", strtotime("first day of this month"))) }}</p>
                </div>

                  <table id="confirmationlisttable" class="display" cellspacing="0" width="100%" padding="30px" style="font-size: 13px;">
                    <thead>
                        {{-- prepare header search textbox --}}
                        <tr>
                          <th>Action</th>
                          <th>Id</th>
                          <th>No</th>
                          <th>StaffId</th>
                          <th>Name</th>
                          <th>Grade</th>
                          <th>Company</th>
                          <th>Position</th>
                          <th>Joining Date</th>
                          <th>Confirmation Date</th>
                        </tr>
                    </thead>
                    <tbody>

                      <?php $i = 0; ?>
                      @foreach($staffconfirmationlist as $item)

                        <tr id="row_{{ $i }}">
                            <td></td>
                            <td>{{ $i+1 }}</td>
                            @foreach($item as $key=>$value)
                                <td>
                                  {{ $value }}
                                </td>

                            @endforeach
                        </tr>

                        <?php $i++; ?>
                      @endforeach

                  </tbody>
                    </table>

              </div>

            </div>
            <!-- /.col -->
          </div>

        {{-- <div class="row">
          <div class="col-md-12">
            <h4 class="box-title"><b>Human Resource<b></h4>
          </div>
          @if($leavesummary)
            <div class="col-lg-2 col-xs-6">
              <!-- small box -->
              <div class="small-box bg-yellow">
                <div class="inner">
                    <h3>{{$leavesummary->On_Leave}}</h3>
                  <p>On Leave Today<br><br></p>
                </div>
                <div class="icon">
                  <i class="ion ion-share"></i>
                </div>
                  <a href="{{ url('leavemanagement') }}"  class="small-box-footer">More info <i class="fa fa-arrow-circle-right"></i></a>
                </div>
            </div>
          @endif
          @if($schedules)
            <div class="col-lg-2 col-xs-6">
              <!-- small box -->
              <div class="small-box bg-green">
                <div class="inner">
                  <h3>{{$schedules->schedule}}</h3>
                  <p>Event & Training
                    <br>Schedules<br></p>
                </div>
                <div class="icon">
                  <i class="ion ion-ios-person"></i>
                </div>
                <a href="#" onclick="scheduleview();" class="small-box-footer">More info <i class="fa fa-arrow-circle-right"></i></a>
              </div>
            </div>
          @endif
          @if($interns)
            <div class="col-lg-2 col-xs-6">
              <!-- small box -->
              <div class="small-box bg-green">
                <div class="inner">
                  <h3>{{$interns->Interns}}</h3>
                  <p>Interns<br><br></p>
                </div>
                <div class="icon">
                  <i class="ion ion-ios-person"></i>
                </div>
                <a href="{{ url('/resourcetracker') }}" class="small-box-footer">More info <i class="fa fa-arrow-circle-right"></i></a>
              </div>
            </div>
          @endif
            @if ($accountsummary[0] -> Pending_Account_Approval>0)
              <div class="col-lg-2 col-xs-6">
                <!-- small box -->
                <div class="small-box bg-red">
                  <div class="inner">
                      <h3>{{$accountsummary[0] -> Pending_Account_Approval}}</h3>
                    <p>Account Pending <br>Approval</p>
                  </div>
                  <div class="icon">
                    <i class="ion ion-ios-person"></i>
                  </div>
                    <a href="{{ url('/accesscontrol') }}" class="small-box-footer">More info <i class="fa fa-arrow-circle-right"></i></a>
                  </div>
              </div>
            @endif
            @if ($accountsummary[0] -> Pending_Account_Detail_Update>0)
              <div class="col-lg-2 col-xs-6">
                <!-- small box -->
                <div class="small-box bg-red">
                  <div class="inner">
                      <h3>{{$accountsummary[0] -> Pending_Account_Detail_Update}}</h3>
                    <p>Account Detail <br>Pending Update</p>
                  </div>
                  <div class="icon">
                    <i class="ion ion-clipboard"></i>
                  </div>
                    <a href="{{ url('/accesscontrol') }}" class="small-box-footer">More info <i class="fa fa-arrow-circle-right"></i></a>
                  </div>
              </div>
            @endif
            @if ($accountsummary[0] -> Pending_Account_Detail_Approval>0)
              <div class="col-lg-2 col-xs-6">
                <!-- small box -->
                <div class="small-box bg-red">
                  <div class="inner">
                      <h3>{{$accountsummary[0] -> Pending_Account_Detail_Approval}}</h3>
                    <p>Account Detail <br>Pending Approval</p>
                  </div>
                  <div class="icon">
                    <i class="ion ion-clipboard"></i>
                  </div>
                    <a href="{{ url('/accesscontrol') }}" class="small-box-footer">More info <i class="fa fa-arrow-circle-right"></i></a>
                  </div>
              </div>
            @endif
      </div> --}}
    @endif

      <!-- /.row -->
    </section>
    <!-- /.content -->
  </div>
  <!-- /.content-wrapper -->
  <footer class="main-footer">
    <div class="pull-right hidden-xs">
      <b>Version</b> 1.0.0
    </div>
    <strong>Copyright &copy; 2014-2016 <a href="http://www.softoya.com">TrackerOnTheGo</a>.</strong> All rights
    reserved.
  </footer>

  <script>
  $(function () {
    /* initialize the calendar
     -----------------------------------------------------------------*/
    //Date for the calendar events (dummy data)
    var date = new Date();
    var d = date.getDate(),
        m = date.getMonth(),
        y = date.getFullYear();
    $('#calendar').fullCalendar({
      header: {
        left: 'prev,next today',
        center: 'title',
        right: 'listWeek,month,agendaWeek,agendaDay'
      },
      defaultView: 'month',
      buttonText: {
        list:  'List',
        today: 'Today',
        month: 'Month',
        week:  'Week',
        day:   'Day',
      },
      //Random default events
      events: [
        @foreach($holidays as $holiday)
            {
              title: parseHtmlEntities("{{ $holiday->Holiday}}"),
              start: new Date("{{date(DATE_ISO8601, strtotime($holiday->Start_Date))}}"),
              end: new Date("{{ date(DATE_ISO8601, strtotime("+1 day", strtotime($holiday->End_Date)))}}"),
              allDay: true,
                backgroundColor: "#848484", //gray
                borderColor: "#848484" //gray
            },
        @endforeach
        @foreach($showleave as $leave)
            @if(strpos($leave->Status,"Approved")!==false || strpos($leave->Status,"Rejected")!==false || $leave->Status="Pending Approval")
            {
              title: "{{ $leave->Name }} - {{ $leave->Leave_Term }}",
              start: new Date("{{date(DATE_ISO8601, strtotime($leave->Start_Date))}}"),
              end: new Date("{{ date(DATE_ISO8601, strtotime("+1 day", strtotime($leave->End_Date)))}}"),
              allDay: true,
              @if(strpos($leave->Status,"Final Approved")!==false)
                backgroundColor: "#00a65a", //green
                borderColor: "#00a65a" //green
              @elseif(strpos($leave->Status,"Rejected")!==false)
                  backgroundColor: "#dd4b39", //red
                  borderColor: "#dd4b39" //red
              @else
                backgroundColor: "#f39c12", //yellow
                borderColor: "#f39c12" //yellow
              @endif
            },
            @endif
        @endforeach
      ],
      eventRender: function(event, eventElement) {
          if (event.imageurl) {
              eventElement.find("div.fc-content").prepend("<img src='" + event.imageurl +"' width='20' height='20'>");
          }
      },
      eventClick: function(event) {
        if (event.url) {
            window.open(event.url);
            return false;
        }
    },
      editable: false,
      droppable: false, // this allows things to be dropped onto the calendar !!!
    });
  });
  function changepassword()
  {
    password=$('[name="Password3"]').val();
    confirmpassword=$('[name="ConfirmPassword"]').val();
    if (password!=confirmpassword)
    {
      $("#exist-alert").show();
      $("#changepasswordmessage").html("Password and Confirm Password mismatch!");
    }
    else if (password=="")
    {
      $("#exist-alert").show();
      $("#changepasswordmessage").html("Password cannot be empty!");
    }
    // else if (checkPasswordComplexity(password)!=true)
    // {
    //   $("#exist-alert").show();
    //   $("#changepasswordmessage").html(checkPasswordComplexity(password));
    // }
    else {
      $("#exist-alert").hide();
      $.ajaxSetup({
         headers: { 'X-CSRF-Token' : $('meta[name=_token]').attr('content') }
      });
      $.ajax({
                  url: "{{ url('/user/changepassword') }}",
                  method: "POST",
                  data: {UserId:{{$me->UserId}},
                  Password:password},
                  success: function(response){
                    var message="Password Changed!";
                    $('#ChangePassword').modal('hide')
                    $("#update-alert ul").html(message);
                    $("#update-alert").show();
                    setTimeout(function() {
                      $("#update-alert").fadeOut();
                    }, 6000);
          }
      });
    }
  }
  function checkPasswordComplexity(password) {
    errors="";
    var pattern = /[!@#$%^&*()_+\-=\[\]{};':"\\|,.<>\/?]/;
    if (password.length < 8) {
        errors=errors +"Your password must be at least 8 characters.<br>";
    }
    if (password.search(/[a-zA-Z]/i) < 0) {
        errors=errors +"Your password must contain at least one letter.<br>";
    }
    if (password.search(/[0-9]/) < 0) {
        errors=errors +"Your password must contain at least one digit.<br>";
    }
    if (!pattern.test(password)){
        errors=errors +"Your password must contain at least one symbol.<br>";
    }
    if (errors.length == 0) {
        return true;
    }
    return errors;
}
function parseHtmlEntities(str) {
    return str.replace(/&#([0-9]{1,3});/gi, function(match, numStr) {
        var num = parseInt(numStr, 10); // read num as normal number
        return String.fromCharCode(num);
    });
}
function transfer(TrackingId)
{
  $('#transfertrackingid').val(TrackingId);
  $('#TransferModal').modal('show');
}
function report(TrackingId)
{
  $('#reportassetid').val(TrackingId);
  $('#ReportModal').modal('show');
}
function acknowledge(AssetId,TrackingId)
{
  $('#acknowledgeassetid').val(AssetId);
  $('#acknowledgetrackingid').val(TrackingId);
  $('#AcknowledgeModal').modal('show');
}
function calltransfer()
{
  $.ajaxSetup({
     headers: { 'X-CSRF-Token' : $('meta[name=_token]').attr('content') }
  });
  trackingid=$('[name="transfertrackingid"]').val();
  userid=$('[name="UserId"]').val();
  date=$('[name="Date"]').val();
  if (userid==0)
  {
    $("#transfermessage").html('"Transfer to" field is required.');
    $("#transfer-alert").show();
  }
  else {
    $("#transfermessage").html("");
    $("#transfer-alert").hide();
    $("#ajaxloader").show();
    $.ajax({
                url: "{{ url('/asset/transfer') }}",
                method: "POST",
                data: {
                  UserId:userid,
                  Date:date,
                  TrackingId:trackingid
                },
                success: function(response){
                  if (response==0)
                  {
                    var message ="Failed to transfer asset!";
                    $("#warning-alert ul").html(message);
                    $("#warning-alert").show();
                    setTimeout(function() {
                      $("#warning-alert").fadeOut();
                    }, 6000);
                    $('#TransferModal').modal('hide')
                    $("#ajaxloader").hide();
                  }
                  else {
                    var message ="Asset Transferred!";
                    $("#update-alert ul").html(message);
                    $("#update-alert").show();
                    setTimeout(function() {
                      $("#update-alert").fadeOut();
                    }, 6000);
                    $("#UserId option:selected").val(response).change();
                    //$("#Template").val(response).change();
                    $("#exist-alert").hide();
                    $('#TransferModal').modal('hide')
                    oTable.api().ajax.reload();
                    $("#ajaxloader").hide();
                  }
        }
    });
  }
}
function callreport()
{
  $.ajaxSetup({
     headers: { 'X-CSRF-Token' : $('meta[name=_token]').attr('content') }
  });
  trackingid=$('[name="reportassetid"]').val();
  issue=$('[name="reportdetails"]').val();
  replacement=$('[name="replacement"]').val();
  if (reportdetails=="")
  {
    $("#reportmessage").html('Report details required..');
    $("#report-alert").show();
  }
  else {
    $("#reportmessage").html("");
    $("#report-alert").hide();
    $.ajax({
                url: "{{ url('/asset/report') }}",
                method: "POST",
                data: {
                  Issue:issue,
                  Replacement:replacement,
                  TrackingId:trackingid
                },
                success: function(response){
                  if (response==0)
                  {
                    var message ="Failed to report!";
                    $("#warning-alert ul").html(message);
                    $("#warning-alert").show();
                    setTimeout(function() {
                      $("#warning-alert").fadeOut();
                    }, 6000);
                    $('#ReportModal').modal('hide')
                  }
                  else {
                    var message ="Report Sent!";
                    $("#update-alert ul").html(message);
                    $("#update-alert").show();
                    setTimeout(function() {
                      $("#update-alert").fadeOut();
                    }, 6000);
                    //$("# option:selected").val(response).change();
                    //$("#Template").val(response).change();
                    $("#exist-alert").hide();
                    $('#ReportModal').modal('hide')
                    oTable.api().ajax.reload();
                  }
        }
    });
  }
}
function callacknowledge()
{
  $.ajaxSetup({
     headers: { 'X-CSRF-Token' : $('meta[name=_token]').attr('content') }
  });
  acknowledgeassetid=$('[name="acknowledgeassetid"]').val();
  acknowledgetrackingid=$('[name="acknowledgetrackingid"]').val();
  acknowledgeuserid={{$me->UserId}};
  $("#ajaxloader2").show();
  $.ajax({
              url: "{{ url('/asset/acknowledge') }}",
              method: "POST",
              data: {
                AssetId:acknowledgeassetid,
                TrackingId:acknowledgetrackingid,
                UserId:acknowledgeuserid,
              },
              success: function(response){
                if (response==0)
                {
                  var message ="Failed to acknowledge asset!";
                  $("#warning-alert ul").html(message);
                  $("#warning-alert").show();
                  setTimeout(function() {
                    $("#warning-alert").fadeOut();
                  }, 6000);
                  $('#AcknowledgeModal').modal('hide')
                  $("#ajaxloader2").hide();
                }
                else {
                  var message ="Asset Received!";
                  $("#update-alert ul").html(message);
                  $("#update-alert").show();
                  setTimeout(function() {
                    $("#update-alert").fadeOut();
                  }, 6000);
                  //$("#Template").val(response).change();
                  $("#exist-alert").hide();
                  $('#AcknowledgeModal').modal('hide')
                  oTable.api().ajax.reload();
                  $("#ajaxloader2").hide();
                }
      }
  });
}
function labelview(type)
 {
    $('#labelview').modal('show');
    $("#label").html("");
    $.ajaxSetup({
       headers: { 'X-CSRF-Token' : $('meta[name=_token]').attr('content') }
    });
    $("#ajaxloader3").show();
    $.ajax({
                url: "{{ url('type') }}",
                method: "POST",
                data: {
                  Type:type
                },
                success: function(response){
                  if (response==0)
                  {
                    var message ="Failed to retrieve asset history!";
                    $("#warning-alert ul").html(message);
                    $("#warning-alert").show();
                    $('#ReturnedModal').modal('hide')
                    $("#ajaxloader3").hide();
                  }
                  else {
                    $("#exist-alert").hide();
                    var myObject = JSON.parse(response);
                    if (type=="Car")
                    {
                      var display='List of cars available<br><br>';
                      display+='<table border="1" align="center" class="assettable" cellspacing="0" width="100%" padding="30px" style="font-size: 13px;">';
                      display+='<tr class="tableheader"><td>Label</td><td>Car No</td><td>Rental Company</td></tr>';
                      $.each(myObject, function(i,item){
                              display+="<tr>";
                              display+='<td>'+item.Label+'</td><td>'+item.Car_No+'</td><td>'+item.Rental_Company+'</td>';
                              display+="</tr>";
                      });
                      $("#label").html(display);
                      $("#ajaxloader3").hide();
                    }
                    else if (type=="Laptop")
                    {
                      var display='List of laptops available<br><br>';
                      display+='<table border="1" align="center" class="assettable" cellspacing="0" width="100%" padding="30px" style="font-size: 13px;">';
                      display+='<tr class="tableheader"><td>Label</td><td>Brand</td><td>Model No</td></tr>';
                      $.each(myObject, function(i,item){
                        display+="<tr>";
                        display+='<td>'+item.Label+'</td><td>'+item.Brand+'</td><td>'+item.Model_No+'</td>';
                        display+="</tr>";
                      });
                      $("#label").html(display);
                      $("#ajaxloader3").hide();
                    }
                    else if (type=="Scanner")
                    {
                      var display='List of scanners available<br><br>';
                      display+='<table border="1" align="center" class="assettable" cellspacing="0" width="100%" padding="30px" style="font-size: 13px;">';
                      display+='<tr class="tableheader"><td>Label</td><td>Brand</td><td>Model No</td></tr>';
                      $.each(myObject, function(i,item){
                        display+="<tr>";
                        display+='<td>'+item.Label+'</td><td>'+item.Brand+'</td><td>'+item.Model_No+'</td>';
                        display+="</tr>";
                      });
                      $("#label").html(display);
                      $("#ajaxloader3").hide();
                    }
                    else if (type=="Handphone")
                    {
                      var display='List of handphones available<br><br>';
                      display+='<table border="1" align="center" class="assettable" cellspacing="0" width="100%" padding="30px" style="font-size: 13px;">';
                      display+='<tr class="tableheader"><td>Label</td><td>Brand</td><td>Model No</td></tr>';
                      $.each(myObject, function(i,item){
                        display+="<tr>";
                        display+='<td>'+item.Label+'</td><td>'+item.Brand+'</td><td>'+item.Model_No+'</td>';
                        display+="</tr>";
                      });
                      $("#label").html(display);
                      $("#ajaxloader3").hide();
                    }
                  }
        }
    });
  }
  function summons()
  {
    $('#summons').modal('show');
  }
  function deduction()
  {
    $('#deductions').modal('show');
  }
  function onleaveview()
   {
      $('#onleaveview').modal('show');
      $("#onleavelabel").html("");
      $.ajaxSetup({
         headers: { 'X-CSRF-Token' : $('meta[name=_token]').attr('content') }
      });
      $("#ajaxloader4").show();
      $.ajax({
                  url: "{{ url('/onleavetoday') }}",
                  success: function(response){
                    if (response==0)
                    {
                      var message ="Failed to retrieve on leave list!";
                      $("#warning-alert ul").html(message);
                      $("#warning-alert").show();
                      $('#ReturnedModal').modal('hide')
                      $("#ajaxloader4").hide();
                    }
                    else {
                      var myObject = JSON.parse(response);
                      var display='List of Staff On Leave Today<br><br>';
                      display+='<table border="1" align="center" class="assettable" cellspacing="0" width="100%" padding="30px" style="font-size: 13px;">';
                      display+='<tr class="tableheader"><td>Name</td><td>Start_Date</td><td>End_Date</td></tr>';
                      $.each(myObject, function(i,item){
                        display+="<tr>";
                        display+='<td>'+item.Name+'</td><td>'+item.Start_Date+'</td><td>'+item.End_Date+'</td>';
                        display+="</tr>";
                      });
                      $("#exist-alert").hide();
                      var myObject = JSON.parse(response);
                        $("#onleavelabel").html(display);
                        $("#ajaxloader4").hide();
                      }
                    }
      });
    }

      function scheduleview()
      {
         $('#scheduleview').modal('show');
         $("#schedulelabel").html("");
         $.ajaxSetup({
            headers: { 'X-CSRF-Token' : $('meta[name=_token]').attr('content') }
         });
         $("#ajaxloader6").show();
         $.ajax({
                     url: "{{ url('/schedulereminder') }}",
                     success: function(response){
                       if (response==0)
                       {
                         var message ="Failed to retrieve on project list!";
                         $("#warning-alert ul").html(message);
                         $("#warning-alert").show();
                         $("#ajaxloader6").hide();
                       }
                       else {
                         var myObject = JSON.parse(response);
                         var display='<table border="1" align="center" class="projecttable" cellspacing="0" width="100%" padding="30px" style="font-size: 13px;">';
                         display+='<tr class="projectheader"><td>Event</td><td>Venue</td><td>Start_Date</td><td>End_Date</td><td>Time</td><td>Assinged To</td><td>Remarks</td><td>Assigned_By</td></tr>';
                         $.each(myObject, function(i,item){
                           display+="<tr>";
                           display+='<td>'+item.Event+'</td><td>'+item.Venue+'</td><td>'+item.Start_Date+'</td><td>'+item.End_Date+'</td><td>'+item.Time+'</td><td>'+item.Name+'</td><td>'+item.Remarks+'</td><td>'+item.AssignedBy+'</td>';
                           display+="</tr>";
                         });
                         $("#exist-alert").hide();
                         var myObject = JSON.parse(response);
                           $("#schedulelabel").html(display);
                           $("#ajaxloader6").hide();
                         }
                       }
         });
       }
       function myscheduleview()
       {
          $('#myscheduleview').modal('show');
          $("#myschedulelabel").html("");
          $.ajaxSetup({
             headers: { 'X-CSRF-Token' : $('meta[name=_token]').attr('content') }
          });
          $("#ajaxloader7").show();
          $.ajax({
                      url: "{{ url('/myschedulereminder') }}",
                      success: function(response){
                        if (response==0)
                        {
                          var message ="Failed to retrieve on schedule list!";
                          $("#warning-alert ul").html(message);
                          $("#warning-alert").show();
                          $("#ajaxloader7").hide();
                        }
                        else {
                          var myObject = JSON.parse(response);
                          var display='<table border="1" align="center" class="projecttable" cellspacing="0" width="100%" padding="30px" style="font-size: 13px;">';
                          display+='<tr class="projectheader"><td>Event</td><td>Venue</td><td>Start_Date</td><td>End_Date</td><td>Time</td><td>Remarks</td><td>Assigned_By</td></tr>';
                          $.each(myObject, function(i,item){
                            display+="<tr>";
                            display+='<td>'+item.Event+'</td><td>'+item.Venue+'</td><td>'+item.Start_Date+'</td><td>'+item.End_Date+'</td><td>'+item.Time+'</td><td>'+item.Remarks+'</td><td>'+item.AssignedBy+'</td>';
                            display+="</tr>";
                          });
                          $("#exist-alert").hide();
                          var myObject = JSON.parse(response);
                            $("#myschedulelabel").html(display);
                            $("#ajaxloader7").hide();
                          }
                        }
          });
        }
       function roadtaxlist()
       {
          $('#roadtaxview').modal('show');
          $("#roadtaxlabel").html("");
          $.ajaxSetup({
             headers: { 'X-CSRF-Token' : $('meta[name=_token]').attr('content') }
          });
          $("#ajaxloader7").show();
          $.ajax({
                      url: "{{ url('/roadtaxreminder') }}",
                      success: function(response){
                        if (response==0)
                        {
                          var message ="Failed to retrieve on project list!";
                          $("#warning-alert ul").html(message);
                          $("#warning-alert").show();
                          $("#ajaxloader7").hide();
                        }
                        else {
                          var myObject = JSON.parse(response);
                          var display='<table border="1" align="center" class="projecttable" cellspacing="0" width="100%" padding="30px" style="font-size: 13px;">';
                          display+='<tr class="projectheader"><td>Vehicle_No</td><td>RoadTax_Expire_Date</td><td></td><td>RoadTax_Amount</td><td>Remarks</td></tr>';
                          $.each(myObject, function(i,item){
                            display+="<tr>";
                            display+='<td>'+item.Vehicle_No+'</td><td>'+item.RoadTax_Expire_Date+'<td><td>'+item.RoadTax_Amount+'</td><td>'+item.Remarks+'</td>';
                            display+="</tr>";
                          });
                          $("#exist-alert").hide();
                          var myObject = JSON.parse(response);
                            $("#roadtaxlabel").html(display);
                            $("#ajaxloader7").hide();
                          }
                        }
          });
        }
  </script>

@endsection