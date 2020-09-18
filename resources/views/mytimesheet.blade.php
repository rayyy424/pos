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

      .dtr-data:before {
        display:inline-block;
        content: "\200D";
      }

      .dtr-data {
        display:inline-block;
        min-width: 100px;
      }

      .weekendrow.even {
        background-color: #FADBD8;
      }

      table.dataTable.display tbody tr.weekendrow.odd {
        background-color: #FADBD8;
      }

      .modal-dialog {
        width: 600px;
        margin: 20% auto;
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

      <script type="text/javascript" language="javascript" src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.8.4/moment.min.js"></script>
      <script type="text/javascript" language="javascript" src="https://cdn.datatables.net/plug-ins/1.10.15/sorting/datetime-moment.js"></script>

      {{-- <script type="text/javascript" language="javascript" src="{{ asset('/plugin/examples/resources/syntax/shCore.js') }}"></script>
      <script type="text/javascript" language="javascript" src="{{ asset('/plugin/examples/resources/demo.js') }}"></script>
      <script type="text/javascript" language="javascript" src="{{ asset('/plugin/examples/resources/editor-demo.js') }}"></script> --}}

      <script type="text/javascript" language="javascript" class="init">

          var timesheetseditor;
          var timesheettable;

          var asInitVals = new Array();

          $(document).ready(function() {

              $.fn.dataTable.moment( 'DD-MMM-YYYY' );

                         timesheetseditor = new $.fn.dataTable.Editor( {
                                 ajax: {
                                    "url": "{{ asset('/Include/timesheet.php') }}",
                                    "data": {
                                      "UserId": {{ $me->UserId }},
                                      "Start": "{{ $start }}",
                                      "End": "{{ $end }}"
                                    }
                                  },
                                 table: "#timesheettable",
                                 idSrc: "timesheets.Id",
                                 fields: [
                                   {
                                           name: "timesheets.TimesheetId",
                                           type: "hidden"
                                   },{
                                           name: "timesheets.UserId",
                                           type: "hidden"
                                   },{
                                          label: "Date Time:",
                                          name: "timesheets.Date",
                                          type:   'datetime',
                                          def:    function () { return new Date(); },
                                          format: 'DD-MMM-YYYY'
                                   },{
                                          label: "Leader_Member:",
                                          name: "timesheets.Leader_Member",
                                          type:  'select',
                                          options: [
                                              { label :"", value: "" },
                                              @foreach($options as $option)
                                                @if ($option->Field=="Leader_Member")
                                                  { label :"{{$option->Option}}", value: "{{$option->Option}}" },
                                                @endif
                                              @endforeach

                                          ],
                                   },{
                                          label: "Next Person:",
                                          name: "timesheets.Next_Person"
                                   },{
                                          label: "Site Name:",
                                          name: "timesheets.Site_Name"
                                   },{
                                          label: "State:",
                                          name: "timesheets.State",
                                          type:  'select',
                                          options: [
                                              { label :"", value: "" },
                                              @foreach($options as $option)
                                                @if ($option->Field=="State")
                                                  { label :"{{$option->Option}}", value: "{{$option->Option}}" },
                                                @endif
                                              @endforeach

                                          ],
                                   },{
                                          label: "Check In Type:",
                                          name: "timesheets.Check_In_Type",
                                          type:  'select',
                                          options: [
                                              { label :"", value: "" },
                                              @foreach($options as $option)
                                                @if ($option->Field=="Check_In_Type")
                                                  { label :"{{$option->Option}}", value: "{{$option->Option}}" },
                                                @endif
                                              @endforeach

                                          ],

                                   },{
                                          label: "Work Description:",
                                          name: "timesheets.Work_Description",
                                          type:"textarea"
                                   },{
                                          label: "Remarks:",
                                          name: "timesheets.Remarks",
                                          type:"textarea"
                                   }

                                 ]
                         } );

                         timesheettable=$('#timesheettable').dataTable( {
                                //  ajax: {
                                //     "url": "{{ asset('/Include/timesheet.php') }}",
                                //     "data": {
                                //         "UserId": {{ $me->UserId }},
                                //         "Start": "{{ $start }}",
                                //         "End": "{{ $end }}"
                                //     }
                                //   },

                                 createdRow: function ( row, data, index ) {
                                   var days = ['Sun','Mon','Tue','Wed','Thu','Fri','Sat'];
                                   var d = new Date(data.timesheets.Date)
                                   var n = days[d.getDay()];

                                   if (n=="Sun" || n=="Sat")
                                   {
                                     $(row).removeClass('odd');
                                     $(row).addClass('weekendrow');

                                   }
                                 },
                                 columnDefs: [{ "visible": false, "targets": [0,1,2,3,4,5,6,16,17,18,20,21,22,23,24,25] },{"className": "dt-center", "targets": "_all"}],
                                //  rowId: 'timesheets.Id',
                                colReorder: false,
                                sScrollX: "100%",
                                bAutoWidth: true,
                                sScrollY: "100%",
                                 bPaginate:true,
                                 iDisplayLength:10,
                                 dom: "Blrtpi",
                                 aaSorting: [[7,"asc"]],
                                 columns: [
                                         {
                                           sortable: false,
                                           "render": function ( data, type, full, meta ) {
                                             if (full.timesheetstatuses.Status=="Pending Approval" || String(full.timesheetstatuses.Status).includes("Approved"))
                                             {
                                               return '-';

                                             }
                                             else {
                                               return '<input type="checkbox" name="selectrow" id="selectrow" class="selectrow" value="'+full.timesheets.Id+'" onclick="uncheck()">';

                                             }

                                           }

                                         },
                                         { data: "timesheets.Id",title:'Id'},
                                         { data: "timesheets.UserId",title:'UserId'},
                                         { data: "timesheets.Latitude_In",title:'Latitude_In' },
                                         { data: "timesheets.Longitude_In",title:'Longitude_In' },
                                         { data: "timesheets.Latitude_Out",title:'Latitude_Out' },
                                         { data: "timesheets.Longitude_Out",title:'Longitude_Out' },
                                         { data: "timesheets.Date",title:'Date'},
                                         {
                                            title:"Day",
                                            sortable: false,
                                            "render": function ( data, type, full, meta ) {
                                              var days = ['Sun','Mon','Tue','Wed','Thu','Fri','Sat'];
                                              var dateSplit = full.timesheets.Date.split("-");
                                              objDate = new Date(dateSplit[1] + " " + dateSplit[0] + ", " + dateSplit[2]);
                                              var d = new Date(objDate)
                                              var n = days[d.getDay()];

                                              if (n=="Sun" || n=="Sat")
                                              {
                                                return "<span class='weekend'>"+n+"</span>"
                                              }

                                                return n;
                                            }
                                        },
                                        { data: "holidays.Holiday",title:'Holiday'},
                                        { data: "timesheets.Site_Name",title:'Site Name'},
                                        { data: "timesheets.Check_In_Type",title:'Status' },
                                        { data: "leaves.Leave_Type",title:'Leave_Type' },
                                        { data: "leavestatuses.Leave_Status",title:'Leave_Status' },
                                        { data: "timesheets.Time_In",title:'Time In'},
                                        { data: "timesheets.Time_Out",title:'Time Out' },
                                         { data: "timesheets.Leader_Member",title:'Leader_Member'},
                                         { data: "timesheets.Next_Person",title:'Next_Person'},

                                         { data: "timesheets.State",title:'State' },//18
                                         { data: "timesheets.Work_Description",title:'Work_Description' },
                                         { data: "timesheets.Remarks",title:'Remarks' },
                                         { data: "approver.Name",title:'Name' },
                                         { data: "timesheetstatuses.Status",title:'Status1'},
                                         { data: "timesheetstatuses.Comment",title:'Comment'},
                                         { data: "timesheetstatuses.updated_at",title:'Review_Date'},
                                         { data: "files.Web_Path",
                                            render: function ( url, type, row ) {
                                                 if (url)
                                                 {
                                                   return '<a href="'+ url +'">Download</a>';

                                                 }
                                                 else {
                                                   return ' - ';
                                                 }

                                             },
                                             title: "File"
                                           }


                                 ],
                                //  autoFill: {
                                //     editor:  timesheetseditor,
                                //     columns: [ 0,1,2, 3, 4, 5,6,7,8,9,10,11,12,13,14,15,16,17,18,19]
                                // },
                                // keys: {
                                //     columns: ':not(:first-child)',
                                //     editor:  timesheetseditor
                                // },
                                select: true,
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

             // Activate an inline edit on click of a table cell
             $('#timesheettable').on( 'click', 'tbody td:not(.child), tbody', function (e) {

                   timesheetseditor.inline(this, {
                  onBlur: 'submit'
                 } );

                //  timesheettable.keys.disable();

                //  timesheetseditor.inline( this );
             } );

             timesheetseditor.on( 'preSubmit', function ( e, o, action ) {

               if ( action == 'edit' ) {

                 for (var key in o.data) {

                   for (var a in o.data[key])
                   {

                     for (var field in o.data[key].timesheets)
                     {

                       if (field=="Time_In" )
                       {
                         var timein = this.field( 'timesheets.Time_In' );
                         var regex = /^([0-9]|0[0-9]|1[0-9]|2[0-3]):[0-5][0-9]?\s?(?:AM|PM|am|pm|aM|pM|Am|Pm)$/i;

                         // Only validate user input values - different values indicate that
                         // the end user has not entered a value
                         if (timein.val()!="")
                         {

                           if(!regex.test(timein.val()))
                           {
                             timein.error( 'Invalid time!' );
                             timein.val("");
                             return false;
                           }

                         }

                       }
                       else if ( field=="Time_Out")
                       {
                         var timeout = this.field( 'timesheets.Time_Out' );
                         var regex = /^([0-9]|0[0-9]|1[0-9]|2[0-3]):[0-5][0-9]?\s?(?:AM|PM|am|pm|aM|pM|Am|Pm)$/i;

                         // Only validate user input values - different values indicate that
                         // the end user has not entered a value

                         if (timeout.val()!="")
                         {

                           if(!regex.test(timeout.val()))
                           {
                             timeout.error( 'Invalid time!' );
                             timeout.val("");
                             return false;
                           }

                         }

                       }
                       else {
                         return true;
                       }

                     }

                   }

                 }

                   return true;

               }
           } );

             timesheetseditor.on( 'postEdit', function ( e, json, data ) {
                  $(this.modifier()).addClass('data-changed')

                  $.ajaxSetup({
                     headers: { 'X-CSRF-Token' : $('meta[name=_token]').attr('content') }
                  });

                  Id=data.timesheets.Id;
                  d=data.timesheets.Date;
                  Time_In=data.timesheets.Time_In;
                  Time_Out=data.timesheets.Time_Out;
                  State=data.timesheets.State;
                  Check_In_Type=data.timesheets.Check_In_Type;

                  $.ajax({
                              url: "{{ url('/timesheet/calculateallowance') }}",
                              method: "POST",
                              data: {TimesheetId:Id,D:d,Time_In:Time_In,Time_Out:Time_Out,State:State,Check_In_Type:Check_In_Type},

                              success: function(response){
                      }
                  });

              } );

             // Activate an inline edit on click of a table cell
             $('#timesheet2table').on(  'click', 'tbody td:not(.child), tbody span.dtr-data', function (e) {
                   timesheets2editor.inline( this, {
                  onBlur: 'submit'
                 } );
             } );

             // Activate an inline edit on click of a table cell
             $('#timesheet3table').on(  'click', 'tbody td:not(.child), tbody span.dtr-data', function (e) {
                   timesheets3editor.inline( this, {
                  onBlur: 'submit'
                 } );
             } );

             $("#ajaxloader").hide();

             $("thead input").keyup ( function () {

                     /* Filter on the column (the index) of this element */
                 if ($('#timesheettable').length > 0)
                 {

                     var colnum=document.getElementById('timesheettable').rows[0].cells.length;

                     if (this.value=="[empty]")
                     {

                        timesheettable.fnFilter( '^$', this.name,true,false );
                     }
                     else if (this.value=="[nonempty]")
                     {

                        timesheettable.fnFilter( '^(?=\\s*\\S).*$', this.name,true,false );
                     }
                     else if (this.value.startsWith("!")==true && this.value.length>1)
                     {

                        timesheettable.fnFilter( '^(?'+ this.value +').*', this.name,true,false );
                     }
                     else if (this.value.startsWith("!")==false)
                     {
                         timesheettable.fnFilter( this.value, this.name,true,false );
                     }
                 }


             } );

        } );

      </script>

@endsection

@section('content')

    <div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
      <h1>
        My Timesheet
        <small>My Workplace</small>
      </h1>
      <ol class="breadcrumb">
        <li><a href="{{ url('/home') }}"><i class="fa fa-dashboard"></i> Home</a></li>
        <li><a href="#">My Workplace</a></li>
        <li class="active">My Timesheet</li>
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

        <div class="box-body">
          {{-- <button type="button" class="btn btn-danger btn-small" data-toggle="modal" data-target="#ExportPDF" style="background: #d9534f; border-color: #d9534f;">Export</button> --}}
        </div>

        <div class="modal fade" id="ExportPDF" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
          <div class="modal-dialog">
           <div class="modal-content">
             <div class="modal-header">

               <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
               <h4 class="modal-title" id="myModalLabel">Export Timesheet</h4>

             </div>

             <div class="modal-body">
                 Are you sure you wish to export this timesheet?
             </div>
             <div class="modal-footer">
               <a class="btn btn-primary btn-lg" href="{{ url('/exporttimesheet') }}/{{$me->UserId}}/{{$start}}/{{$end}}" target="_blank">PDF</a>
               <a class="btn btn-primary btn-lg" href="{{ url('/excelTimesheet') }}/{{$me->UserId}}/{{$start}}/{{$end}}/{{$start}}-{{$end}}/{{$start}}-{{$end}}" target="_blank">Excel</a>
             </div>
           </div>
          </div>
         </div>

        <div class="modal fade" id="Submitforapproval" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
          <div class="modal-dialog" role="document">
            <div class="modal-content">
              <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title" id="myModalLabel">Submit for approval</h4>
              </div>
              <div class="modal-body">
                  Are you sure you wish to submit the selected timesheet for approval?
              </div>
              <div class="modal-footer">
                <center><img src="{{ URL::to('/') ."/img/ajax-loader.gif" }}" width="50px" height="50px" alt="Loading" name='ajaxloader' id="ajaxloader"></center>
                <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                <button type="button" class="btn btn-primary" onclick="Submitforapproval({{$me->UserId}})">Yes</button>
              </div>
            </div>
          </div>
        </div>

        <div class="col-md-12">

          <div class="box box-body">
            <br>

            <div class="row">

              <div class="form-group">
                <div class="col-lg-6">
                  <label>StaffId : <i>{{$user->StaffId}}</i></label>
                </div>

                <div class="col-lg-6">
                  <label>Name : <i>{{$user->Name}}</i></label>
                </div>

              </div>
            </div>

            <div class="row">
              <div class="form-group">

                <div class="col-lg-6">

                  <label>Position : <i>{{$user->Position}}</i></label>
                </div>

              </div>
            </div>

            <br>

            <div class="row">

              <div class="col-md-6">
                <div class="input-group">
                  <div class="input-group-addon">
                    <i class="fa fa-clock-o"></i>
                  </div>
                  <input type="text" class="form-control" id="range" name="range">

                </div>
              </div>

              <div class="col-md-6">
                  <div class="input-group">
                    <button type="button" class="btn btn-danger btn-small" style="background: #d9534f; border-color: #d9534f;" data-toggle="modal" onclick="refresh();"><i class="fa fa-refresh fa-2"></i></button>
                  </div>
              </div>

            </div>

            <br>

            <div class="row">

              <div class="col-md-12">

                  <table id="timesheettable" class="display" cellspacing="0" width="100%" padding="30px" style="font-size: 13px;">
                      <thead>
                          {{-- prepare header search textbox --}}
                          <tr class="search">

                            @foreach($mytimesheet as $key=>$values)
                              @if ($key==0)
                                <?php $i = 0; ?>

                                @foreach($values as $field=>$a)
                                    @if ($i==0|| $i==1 ||$i==2 ||$i==3||$i==4||$i==5||$i==6||$i==16||$i==17||$i==18||$i==19||$i==20||$i==22||$i==23||$i==24||$i==25||$i==26||$i==27)

                                      <th align='center'><input type='hidden' class='search_init' name='{{$i}}' placemark='{{$a}}'></th>
                                    @else
                                      <th align='center'><input type='text' class='search_init' name='{{$i}}'  placemark='{{$a}}'></th>
                                    @endif

                                    <?php $i ++; ?>
                                @endforeach

                                <th align='center'><input type='hidden' class='search_init' name='{{$i}}'  placemark='{{$a}}'></th>

                              @endif

                            @endforeach
                          </tr>

                          <tr>
                            @foreach($mytimesheet as $key=>$value)

                              @if ($key==0)

                                <td><input type="checkbox" name="selectall" id="selectall" value="all" onclick="checkall()"></td>
                                @foreach($value as $field=>$value)
                                    <td/>{{ $field }}</td>
                                @endforeach

                              @endif

                            @endforeach
                          </tr>
                      </thead>
                      <tbody>

                        <?php $i = 0; ?>
                        @foreach($mytimesheet as $timesheet)

                          <tr id="row_{{ $i }}">
                            <td></td>
                              @foreach($timesheet as $key=>$value)
                                <td>
                                  {{ $value }}
                                </td>
                              @endforeach
                          </tr>
                          <?php $i++; ?>
                        @endforeach

                    </tbody>
                      <tfoot>


                      </tfoot>
                  </table>

              </div>

            </div>



          </div>
        <!-- /.box-body -->
        </div>


      </div>
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

function Submitforapproval(id)
{

  var boxes = $('input[type="checkbox"]:checked', timesheettable.fnGetNodes() );

  var ids="";
  var index=0;

  timesheettable.api().rows().every( function ( rowIdx, tableLoop, rowLoop ) {
    var data = this.data();

    if ($('input[type=checkbox][value='+data.timesheets.Id+']').is(':checked'))
      // if ($('input[type="checkbox"]', timesheettable.fnGetNodes() )[index].checked)
    {
      

    }

    index=index+1;
    // ... do something with data(), or this.node(), etc
  } );

    if (boxes.length>0)
    {
      for (var i = 0; i < boxes.length; i++) {
        ids+=boxes[i].value+",";
      }

      ids=ids.substring(0, ids.length-1);

      $.ajaxSetup({
         headers: { 'X-CSRF-Token' : $('meta[name=_token]').attr('content') }
      });

      $("#ajaxloader").show();

      Id=id;

      $.ajax({
                  url: "{{ url('/mytimesheet/submitforapproval') }}",
                  method: "POST",
                  data: {Id:id,TimesheetIds:ids},

                  success: function(response){

                    if (response==1)
                    {
                        timesheettable.api().ajax.url("{{ asset('/Include/timesheet.php') }}").load();
                        var message="Timesheet submitted for approval!";
                        $("#update-alert ul").html(message);
                        $("#update-alert").modal('show');

                        $('#Submitforapproval').modal('hide');

                        $("#ajaxloader").hide();

                    }
                    else {

                      var errormessage="Failed to submit timesheet!";
                      $("#error-alert ul").html(errormessage);
                      $("#error-alert").modal('show');

                      $('#Submitforapproval').modal('hide');

                      $("#ajaxloader").hide();

                    }

          }
      });
    }
    else {
      var errormessage="No timesheet selected!";
      $("#error-alert ul").html(errormessage);
      $("#error-alert").modal('show');


      $('#Submitforapproval').modal('hide');

      $("#ajaxloader").hide();
    }

}

  function checkall()
  {
    var allPages = timesheettable.fnGetNodes();
// alert(document.getElementById("selectall").checked);
    if ($("#selectall").is(':checked')) {

       $('input[type="checkbox"]', allPages).prop('checked', true);
        // $(".selectrow").prop("checked", true);
         $(".selectrow").trigger("change");
         timesheettable.api().rows().select();
    } else {

        $('input[type="checkbox"]', allPages).prop('checked', false);
        $(".selectrow").trigger("change");
         timesheettable.api().rows().deselect();
    }
  }

  function uncheck()
  {

    if (!$("#selectrow").is(':checked')) {
      $("#selectall").prop("checked", false)
    }

  }

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

    $('#range').daterangepicker({locale: {
      format: 'DD-MMM-YYYY'
    },startDate: '{{$start}}',
    endDate: '{{$end}}'});

  });

  function refresh()
  {
    var d=$('#range').val();
    var arr = d.split(" - ");

    window.location.href ="{{ url("/mytimesheet") }}/"+arr[0]+"/"+arr[1];

  }

</script>

@endsection
