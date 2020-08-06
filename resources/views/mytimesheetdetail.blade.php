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
      {{-- <script type="text/javascript" language="javascript" src="{{ asset('/plugin/examples/resources/syntax/shCore.js') }}"></script>
      <script type="text/javascript" language="javascript" src="{{ asset('/plugin/examples/resources/demo.js') }}"></script>
      <script type="text/javascript" language="javascript" src="{{ asset('/plugin/examples/resources/editor-demo.js') }}"></script> --}}

      <script>

      var editor;
      var table;

      $(document).ready(function() {

                     editor = new $.fn.dataTable.Editor( {
                         ajax: {
                            "url": "{{ asset('/Include/timesheetitem.php') }}",
                            "data": {
                                "TimesheetId": {{ $mytimesheet[0]->Id }}
                            }
                          },
                             table: "#pendingtable",
                             idSrc: "timesheetitems.Id",
                             fields: [
                               {
                                       name: "timesheetitems.TimesheetId",
                                       type: "hidden",
                                       default: {{ $mytimesheet[0]->Id }}
                               },{
                                      label: "Date Time:",
                                      name: "timesheetitems.Date_Time",
                                      type:   'datetime',
                                      def:    function () { return new Date(); },
                                      format: 'DD-MMM-YYYY'
                               },{
                                      label: "Leader/Member:",
                                      name: "timesheetitems.Leader_Member",
                                      type:  'select',
                                        options: [
                                            { label :"Leader", value: "Leader" },
                                            { label :"Member", value: "Member" },

                                        ],
                               },{
                                      label: "Project Code:",
                                      name: "timesheetitems.Project_Code_Id",
                                      type:  'select',
                                      options: [
                                          { label :"", value: "0" },
                                          @foreach($projectcodes as $projectcode)
                                              { label :"{{$projectcode->Project_Code}}", value: "{{$projectcode->Id}}" },
                                          @endforeach

                                      ],
                               },{
                                      label: "Project Name:",
                                      name: "timesheetitems.ProjectId",
                                      type:  'select',
                                      options: [
                                          { label :"", value: "0" },
                                          @foreach($projects as $project)
                                              { label :"{{$project->Project_Name}}", value: "{{$project->Id}}" },
                                          @endforeach

                                      ],
                               },{
                                      label: "Site Name:",
                                      name: "timesheetitems.Site_Name"
                               },{
                                      label: "State:",
                                      name: "timesheetitems.State",
                                      type:  'select',
                                      options: [
                                          { label :"", value: "" },
                                          { label :"Kuala Lumpur", value: "Kuala Lumpur" },
                                          { label :"Labuan", value: "Labuan" },
                                          { label :"Putrajaya", value: "Putrajaya" },
                                          { label :"Johor", value: "Johor" },
                                          { label :"Kedah", value: "Kedah" },
                                          { label :"Kelantan", value: "Kelantan" },
                                          { label :"Malacca", value: "Malacca" },
                                          { label :"Negeri Sembilan", value: "Negeri Sembilan" },
                                          { label :"Pahang", value: "Pahang" },
                                          { label :"Perak", value: "Perak" },
                                          { label :"Perlis", value: "Perlis" },
                                          { label :"Penang", value: "Penang" },
                                          { label :"Sabah", value: "Sabah" },
                                          { label :"Sarawak", value: "Sarawak" },
                                          { label :"Selangor", value: "Selangor" },
                                          { label :"Terengganu", value: "Terengganu" }

                                      ],
                               },{
                                      label: "Check In Type:",
                                      name: "timesheetitems.Check_In_Type",
                                      type:  "radio",
                                      options: [
                                          { label :"On Duty", value: "On Duty" },
                                          { label :"Standby", value: "Standby" }
                                      ],
                               },{
                                      label: "Time In:",
                                      name: "timesheetitems.Time_In",
                                      type:      'datetime',
                                      def:       function () { return new Date(); },
                                      format:    'DD-MMM-YYYY h:mm A'
                               },{
                                      label: "Time Out:",
                                      name: "timesheetitems.Time_Out",
                                      type:      'datetime',
                                      def:       function () { return new Date(); },
                                      format:    'DD-MMM-YYYY h:mm A'
                               },{
                                      label: "Reason:",
                                      name: "timesheetitems.Reason"
                               },{
                                      label: "Remarks:",
                                      name: "timesheetitems.Remarks"
                               }

                             ]
                     } );


                     // Activate an inline edit on click of a table cell
                           $('#pendingtable').on( 'click', 'tbody td', function (e) {
                                 editor.inline( this, {
                                onBlur: 'submit'
                               } );
                           } );

                           editor.on( 'postEdit', function ( e, json, data ) {
                                $(this.modifier()).addClass('data-changed')

                                inserttimesheetstatus(data);
                            } );

                           table=$('#pendingtable').DataTable( {
                                   ajax: {
                                      "url": "{{ asset('/Include/timesheetitem.php') }}",
                                      "data": {
                                          "TimesheetId": {{ $mytimesheet[0]->Id }}
                                      }
                                    },
                                   rowId: 'timesheetitems.Id',
                                   columnDefs: [{ "visible": false, "targets": [0] },{"className": "dt-center", "targets": "_all"}],
                                   responsive: false,
                                   colReorder: true,
                                   bStateSave:false,
                                   sScrollX: "100%",
                                   bAutoWidth: true,
                                   sScrollY: "100%",
                                   dom: "Bfrtip",
                                   bPaginate:false,
                                   //aaSorting: [[1,"asc"]],
                                  //  iDisplayLength:50,
                                   columns: [
                                           { data: "timesheetitems.Id"},
                                           { data: "timesheetitems.Date_Time"},
                                           { data: "timesheetitems.Leader_Member"},
                                           { data: "projectcodes.Project_Code", editField: "timesheetitems.Project_Code_Id" },
                                           { data: "projects.Project_Name", editField: "timesheetitems.ProjectId" },
                                           { data: "timesheetitems.Site_Name"},
                                           { data: "timesheetitems.State"},
                                           //Field::inst( 'timesheetitems.Project_Manager' ),
                                           { data: "pm.Name"},
                                           { data: "timesheetitems.Check_In_Type"},
                                           { data: "timesheetitems.Time_In"},
                                           { data: "timesheetitems.Time_Out"},
                                           { data: "timesheetitems.Reason"},
                                           { data: "timesheetitems.Remarks"},
                                           { data: "approver.Name"},
                                           { data: "timesheetitemstatuses.Status"},
                                           { data: "timesheetitemstatuses.Comment"}

                                   ],
                                   autoFill: {
                                      editor:  editor
                                  },
                                  // keys: {
                                  //     columns: ':not(:first-child)',
                                  //     editor:  editor
                                  // },
                                  select: true,
                                   buttons: [
                                           {
                                             text: 'New',
                                             action: function ( e, dt, node, config ) {
                                                 // clearing all select/input options
                                                 editor
                                                    .create( false )
                                                    .set( 'timesheetitems.TimesheetId', {{ $mytimesheet[0]->Id }} )
                                                    .submit();
                                             },
                                           },
                                           { extend: "edit",   editor: editor },
                                           { extend: "remove", editor: editor },
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

            // // Activate an inline edit on click of a table cell
            // $('#pendingtable').on( 'click', 'tbody td:not(:first-child)', function (e) {
            //     editor.inline( this );
            // } );
            //
            // // Disable KeyTable while the main editing form is open
            // editor
            //     .on( 'open', function ( e, mode, action ) {
            //         if ( mode === 'main' ) {
            //             table.keys.disable();
            //         }
            //     } )
            //     .on( 'close', function () {
            //         table.keys.enable();
            //     } );

          } );

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
                   right: 'month'
                 },
                 buttonText: {
                   today: 'Today',
                   month: 'Month'
                 },
                 //Random default events
                 events: [
                 ],

                 editable: false,
                 droppable: false, // this allows things to be dropped onto the calendar !!!
               });
             });

      </script>
@endsection

@section('content')

    <div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
      <h1>
        Timesheet Detail
        <small>My Workplace</small>
      </h1>
      <ol class="breadcrumb">
        <li><a href="{{ url('/home') }}"><i class="fa fa-dashboard"></i> Home</a></li>
        <li><a href="#">My Workplace</a></li>
        <li><a href="{{ url('/mytimesheet') }}">My Timesheet</a></li>
        <li class="active">Timesheet Detail</li>
      </ol>
    </section>

    <!-- Main content -->
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


        <div class="col-md-4">
          <div class="box box-primary">

            <div class="box-body box-profile">

              <ul class="list-group list-group-unbordered">
                <li class="list-group-item">
                  <b>Timesheet Name</b> : <p class="pull-right"><i>{{ $mytimesheet[0]->Timesheet_Name }}</i></p>
                </li>
                <li class="list-group-item">
                  <b>Date</b> : <p class="pull-right"><i>{{ $mytimesheet[0]->Date }}</i></p>
                </li>
                <li class="list-group-item">
                  <b>Remarks</b> : <p class="pull-right"><i>{{ $mytimesheet[0]->Remarks }}</i></p>
                </li>
                <li class="list-group-item">
                  <b>Submitted By</b> : <p class="pull-right"><i>{{ $mytimesheet[0]->Name }}</i></p>
                </li>
              </ul>

              {{-- <a href="#" class="btn btn-primary btn-block"><b>Update</b></a> --}}
            </div>

          </div>
        </div>

        <div class="col-md-4">
          <div class="box box-default">

            <div class="box-header">
              Timesheet Approver
            </div>
            <div class="box-body">

              <div class="callout callout-warning">
                <h5>1st Approval</h5>

                @foreach($hierarchy as $superior)

                  @if($superior->L21st==1)
                      {{$superior->L2Name}}<br>
                  @endif

                  @if($superior->L31st==1)
                      {{$superior->L3Name}}<br>
                  @endif

                @endforeach

              </div>

              <div class="callout callout-info">
                <h5>2nd Approval</h5>

                @foreach($hierarchy as $superior)

                  @if($superior->L22nd==1)
                      {{$superior->L2Name}}<br>
                  @endif

                  @if($superior->L32nd==1)
                      {{$superior->L3Name}}<br>
                  @endif

                @endforeach

              </div>

              <div class="callout callout-success">
                <h5>Final Approval</h5>

                @foreach($final as $superior)

                  {{$superior->Name}} <br>

                @endforeach

              </div>
            </div>

          </div>
        </div>

        <div class="col-md-4">
          <div class="box box-default">
              <div class="box-header with-border">
                  <div id="calendar"></div>
              </div>
          </div>
        </div>

      </div>

      <div class="row">
        <div class="col-md-12">
          <div class="box box-success">
            <br>
            <div class="nav-tabs-custom">

              <div class="tab-content">
                <div class="active tab-pane" id="pending">

                  <table id="pendingtable" class="display" cellspacing="0" width="100%" padding="30px" style="font-size: 13px;">
                      <thead>
                          {{-- prepare header search textbox --}}
                          <tr>
                            @foreach($mytimesheetdetail as $key=>$value)

                              @if ($key==0)
                                @foreach($value as $field=>$value)
                                    <td/>{{ $field }}</td>
                                @endforeach

                              @endif

                            @endforeach
                          </tr>
                      </thead>
                      <tbody>

                        <?php $i = 0; ?>
                        @foreach($mytimesheetdetail as $timesheet)

                              <tr id="row_{{ $i }}" >
                                  @foreach($timesheet as $key=>$value)
                                    <td>
                                      {{ $value }}
                                    </td>
                                  @endforeach
                              </tr>
                              <?php $i++; ?>

                        @endforeach

                    </tbody>
                      <tfoot></tfoot>
                  </table>

                </div>

                {{-- <div class="tab-pane" id="approved">

                </div>

                <div class="tab-pane" id="rejected">

                </div> --}}
              </div>
            </div>
          </div>
        </div>
      </div>

      <!-- /.row -->
    </section>
    <!-- /.content -->
  </div>
  <!-- /.content-wrapper -->
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
    $('input[type="checkbox"].flat-blue, input[type="radio"].flat-blue').iCheck({
      checkboxClass: 'icheckbox_flat-blue',
      radioClass: 'iradio_flat-blue'
    });

    //Date picker
    $('#Date').datepicker({
      autoclose: true
    });

    //Timepicker
    $(".timepicker").timepicker({
      showInputs: false
    });

    // document.getElementById("Time_In").value = '';
    // document.getElementById("Time_Out").value = '';

  });

  function inserttimesheetstatus(data)
  {
    $.ajaxSetup({
       headers: { 'X-CSRF-Token' : $('meta[name=_token]').attr('content') }
    });

    timesheetitemid=data.timesheetitems.Id;
    userid=data.projects.Project_Manager;
    status="Pending Review";

    if (userid)
    {
      $.ajax({
                  url: "{{ url('/timesheet/newtimesheetitemstatus') }}",
                  method: "POST",
                  data: {TimesheetItemId:timesheetitemid,UserId:userid,Status:status},

                  success: function(response){

                    if (response==1)
                    {
                        //success

                    }

          }
      });

    }

  }

  function newtimesheet() {

      $.ajaxSetup({
         headers: { 'X-CSRF-Token' : $('meta[name=_token]').attr('content') }
      });

      date=$('[name="Date"]').val();
      timein=$('[name="Time_In"]').val();
      timeout=$('[name="Time_Out"]').val();
      leadermember=$($('input[name=Leader_Member]:checked')).val();
      projectcode=$('[name="Project_Code"]').val();
      project=$('[name="Project"]').val();
      sitename=$('[name="Site_Name"]').val();
      state=$('[name="State"]').val();
      projectmanager=$('[name="Project_Manager"]').val();
      checkintype=$('[name="Check_In_Type"]').val();
      reason=$($('input[name=Rush_Report]:checked')).val() + " " + $($('input[name=KPI_Test]:checked')).val() + " " +$($('input[name=Tuning_Verify]:checked')).val();
      remarks=$('[name="Remarks"]').val();

      $.ajax({
                  url: "{{ url('/mytimesheet/new') }}",
                  method: "POST",
                  data: {Date:date,Time_In:timein,Time_Out:timeout,Leader_Member:leadermember,Project_Code:projectcode,Project:project,Site_Name:sitename,State:state,Project_Manager:projectmanager,Check_In_Type:checkintype,Reason:reason,Remarks:remarks},

                  success: function(response){

                    if (response==1)
                    {

                        $("#update-alert").modal('show');


                        document.getElementById("Date").value = ''
                        document.getElementById("Leader_Member_1").checked=true;

                        document.getElementById("Time_In").value = ''
                        document.getElementById("Time_Out").value = ''

                        document.getElementById("Project_Code").value = ''
                        document.getElementById("Project").value = ''
                        document.getElementById("Site_Name").value = ''
                        document.getElementById("State").value = ''
                        $("#Project_Manager").val("").change();
                        $("#Check_In_Type").val("").change();

                        document.getElementById("Rush_Report").checked=false;
                        document.getElementById("KPI_Test").checked=false;
                        document.getElementById("Tuning_Verify").checked=false;

                        document.getElementById("Remarks").value = ''
                    }
                    else {
                        var obj = jQuery.parseJSON(response);
                        var errormessage ="";

                        for (var item in obj) {
                          errormessage=errormessage + "<li> " + obj[item] + "</li>";
                        }

                        $("#error-alert ul").html(errormessage);
                        $("#error-alert").modal('show');



                    }

          }
      });

  }
</script>

@endsection
