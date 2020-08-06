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
      /*a.buttons-collection {
        margin-left: 1em;
      }
      .container1{
              width: 1200px;
              margin-left: 50px;
              padding: 10px;
      }*/

      #map{
    	height: 300px;
    	/*width:530px;*/
    	margin: 0 auto;
    }
      a.buttons-collection {
        margin-left: 1em;
      }
      .container1{
              width: 1200px;
              margin-left: 50px;
              padding: 10px;
      }

      .weekend {
        color: red;
      }

      .red{
        color:red;
        font-size: 12px;
      }

      .photobox{
        width: 300px;
      }

      .otverified {
        background-color: #a8fbba !important;
      }

      .hodverified {
        background-color: #cde9f9 !important;
      }

      .hod-not-verified-hr-verified {
        background-color: #ffd3ce !important;
      }

    </style>

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
      <script type="text/javascript" language="javascript" src="https://cdn.datatables.net/plug-ins/1.10.13/api/sum().js"></script>

      <script type="text/javascript" language="javascript" src="{{ asset('/plugin/js/dataTables.editor.min.js') }}"></script>

      {{-- <script type="text/javascript" language="javascript" src="{{ asset('/plugin/examples/resources/syntax/shCore.js') }}"></script>
      <script type="text/javascript" language="javascript" src="{{ asset('/plugin/examples/resources/demo.js') }}"></script>
      <script type="text/javascript" language="javascript" src="{{ asset('/plugin/examples/resources/editor-demo.js') }}"></script> --}}

      <script>

      var timesheettable;
      var timesheeteditor;


            $(document).ready(function() {

                          timesheeteditor = new $.fn.dataTable.Editor( {
                                  ajax: {
                                     "url": "{{ asset('/Include/otmanagementhr.php') }}",
                                     "data": {
                                         "Start": "{{ $start }}",
                                         "End": "{{ $end }}",
                                         "IncludeResigned": "{{$includeResigned}}",
                                         "UserId": "{{ $me->UserId}}"

                                     }
                                   },
                                  table: "#engineertable",
                                  idSrc: "timesheets.Id",
                                  fields: [
                                          @if($me->Edit_Time)
                                          {
                                                 label: "Time In:",
                                                 name: "timesheets.Time_In",
                                                 type:      'datetime',
                                                 def:       function () { return new Date(); },
                                                 format:    'h:mm A'
                                          },{
                                                 label: "Time Out:",
                                                 name: "timesheets.Time_Out",
                                                 type:      'datetime',
                                                 def:       function () { return new Date(); },
                                                 format:    'h:mm A'
                                          },{
                                                 label: "Deduction:",
                                                 name: "timesheets.OT1",
                                                 attr: {
                                                   type: "number"
                                                 }
                                          },{
                                                 label: "Deduction:",
                                                 name: "timesheets.OT2",
                                                 attr: {
                                                   type: "number"
                                                 }

                                          },{
                                                 label: "Deduction:",
                                                 name: "timesheets.OT3",
                                                 attr: {
                                                   type: "number"
                                                 }
                                          },
                                          @endif
                                          @if($me->OT_Management_HR)
                                          {
                                                 label: "Verified_OT:",
                                                 name: "timesheets.OT_Verified",
                                                 type:   'select',
                                                 options: [
                                                   { label :"", value: ""},
                                                   { label :"No", value: "0"},
                                                   { label :"Verified", value: "1"},
                                                 ]
                                          },
                                          @endif

                                  ]
                          } );

                          var dtCols = [
                             { data: "timesheets.Id",title:"Id"},
                             { data: "timesheets.Id",title:"TimesheetId"},
                             { data: "users.StaffId",title:"StaffID"},
                             { data: "users.Name",title:"Name",
                             "render": function ( data, type, full, meta ) {

                                    return '<a onclick="viewphoto(\''+full.files.Web_Path+'\')">'+full.users.Name+'</a>';
                                }
                             },
                             { data: "users.Resignation_Date",title:"Resignation_Date"},
                             { data: "users.Company",title:"Company"},
                             { data: "users.Department",title:"Department"},
                             { data: "users.Category",title:"Category"},
                             { data: "users.Position",title:"Position"},
                             { data: "timesheets.Latitude_In",title:"Latitude_In"},
                             { data: "timesheets.Longitude_In",title:"Longitude_In"},
                             { data: "timesheets.Latitude_Out",title:"Latitude_Out"},
                             { data: "timesheets.Longitude_Out",title:"Longitude_Out"},
                             { data: "timesheets.Date",title:"Date"},
                             {
                                sortable: false,
                                "render": function ( data, type, full, meta ) {
                                  var days = ['Sun','Mon','Tue','Wed','Thu','Fri','Sat'];

                                  if(full.timesheets.Date=="")
                                  {
                                    return "";
                                  }
                                  var dateSplit = full.timesheets.Date.split("-");
                                  objDate = new Date(dateSplit[1] + " " + dateSplit[0] + ", " + dateSplit[2]);
                                  var d = new Date(objDate)
                                  var n = days[d.getDay()];

                                  if (n=="Sun" || n=="Sat")
                                  {
                                    return "<span class='weekend'>"+n+"</span>"
                                  }

                                    return n;
                                },
                                title:"Day"
                            },
                            { data: "timesheets.Site_Name",title:"Site_Name"},
                            { data: "timesheets.Code",title:"Code"},
                            { data: "users.Available",title:"Available",
                            "render": function ( data, type, full, meta ) {
                                if (full.users.Available==1)
                                   return 'Yes';
                                else
                                   return 'No';
                                endif

                            }},
                            { data: "timesheets.Check_In_Type",title:"Status"},
                            { data: "leaves.Leave_Type",title:"Leave_Type",
                              "render": function ( data, type, full, meta ) {

                                if(!full.leavestatuses.Leave_Status)
                                {
                                  return "";
                                }
                                else if(!full.leavestatuses.Leave_Status.includes("Rejected") && !full.leavestatuses.Leave_Status.includes("Cancel"))
                                {
                                  return full.leaves.Leave_Type;
                                }
                                else {
                                  return "";
                                }

                                  return n;
                              },
                            },
                            { data: "leavestatuses.Leave_Status",title:"Leave_Status"},
                            {
                               data: "timesheets.Time_In",title:"Time In",
                               "render": function ( data, type, full, meta ) {

                                   var timevalue=parseInt(full.timesheets.Time_In);
                                   if (timevalue>8)
                                   {
                                     return "<a href='#' style='color:red;font-size: 12px;'>"+full.timesheets.Time_In+"</a>";
                                   }
                                   else {
                                     return "<a href='#'>"+full.timesheets.Time_In+"</a>";
                                   }

                               }

                           },
                           {
                              data: "timesheets.Time_Out",title:"Time Out",
                              "render": function ( data, type, full, meta ) {
                                  return "<a href='#'>"+full.timesheets.Time_Out+"</a>";
                              }
                          },
                          {
                            data: "timesheets.OT1",
                            title:"OT_1.5",
                            render: function ( data, type, full, meta ) {
                              // if(full.users.Department.toUpperCase() == 'MY_DEPARTMENT_FAB' || full.users.Department.toUpperCase() == 'MY_DEPARTMENT_MDO') {
                                return data;
                              // }

                              // return "-";
                            }
                          },
                          { data: "timesheets.OT2",title:"OT_2.0",
                           render: function ( data, type, full, meta ) {
                             // if(full.users.Department.toUpperCase() == 'MY_DEPARTMENT_FAB' || full.users.Department.toUpperCase() == 'MY_DEPARTMENT_MDO') {
                               return data;
                             // }

                             // return "-";
                           }
                          },
                          { data: "timesheets.OT3",title:"OT_3.0",
                            render: function ( data, type, full, meta ) {
                              // if(full.users.Department.toUpperCase() == 'MY_DEPARTMENT_FAB' || full.users.Department.toUpperCase() == 'MY_DEPARTMENT_MDO') {
                                return data;
                              // }

                              // return "-";
                            }
                          },
                          { data: "timesheets.OT_HOD_Verified",title:"HOD_Verified", render: function ( data, type, full, meta ) {
                             if(full.timesheets.OT_HOD_Verified==1) {
                               return 'Verified';
                             }
                             return 'No';
                          }},
                          { data: "timesheets.OT_Verified",title:"HR_Verified", render: function ( data, type, full, meta ) {
                             if(full.timesheets.OT_Verified==1) {
                               return 'Verified';
                             }
                             return 'No';
                          }},
                            {
                              "data"  : "timesheets.Deduction",
                              "title" : "Deduction",
                              "render": function ( data, type, full, meta ) {
                                if (data) {
                                  return data;
                                }

                                return '0.00';
                              }
                            },
                            { data: "timesheets.Remarks",title:"Remarks"},

                            { data: "files.Web_Path",title:"Web_Path"},

                     ]

                           timesheettable=$('#engineertable').dataTable( {
                                   ajax: {
                                      "url": "{{ asset('/Include/otmanagementhr.php') }}",
                                      "data": {
                                          "Start": "{{ $start }}",
                                          "End": "{{ $end }}",
                                          "IncludeResigned": "{{$includeResigned}}",
                                          "UserId": "{{ $me->UserId}}"
                                      }
                                    },
                                   // columnDefs: [{ "visible": false, "targets": [0,1,4,9,10,11,12,17,20,25] },{"className": "dt-center", "targets": "_all"}],
                                   columnDefs: [{ "visible": false, "targets": [0,1,4,9,10,11,12,17,20,30] },{"className": "dt-center", "targets": "_all"}],
                                   "createdRow": function( row, data, dataIndex ) {
                                      if ( data.timesheets.OT_HOD_Verified == 1 ) {
                                         $(row).addClass( 'hodverified' );
                                       }
                                       if ( data.timesheets.OT_Verified == 1 ) {
                                         $(row).addClass( 'otverified' );
                                       }

                                       if ( data.timesheets.OT_HOD_Verified == 0 && data.timesheets.OT_Verified == 1 ) {
                                         $(row).addClass( 'hod-not-verified-hr-verified' );
                                         $(row).removeClass( 'otverified' );
                                         $(row).removeClass( 'hodverified' );
                                       }
                                   },
                                   colReorder: false,
                                   sScrollX: "100%",
                                   bAutoWidth: true,
                                   sScrollY: "100%",
                                   iDisplayLength:10,
                                   bScrollCollapse: true,
                                   aaSorting:[[3,"asc"]],
                                   dom: "lBfrtip",
                                   columns: dtCols,
                                  select: true,
                                   buttons: [
                                     // {
                                     //          extend: 'collection',
                                     //          text: 'Export',
                                     //          buttons: [
                                     //                  'excel',
                                     //                  'csv',
                                     //                  'pdf'
                                     //          ],
                                     //          exportOptions: {
                                     //              columns: ':visible'
                                     //          }
                                     //  },
                                      {
                                          text: 'Export',
                                          extend: 'excelHtml5'
                                      },
                                   ],

                       });

                       $('#engineertable').on( 'click', 'tbody td:not(.child), tbody span.dtr-data', function (e) {

                            var currentRow = timesheettable.api().row(this).data();
                            var colIndex = timesheettable.api().cell(this).index().column;
                            console.log(dtCols[colIndex]);

                            if (dtCols[colIndex].data == 'timesheets.OT_Verified') {

                              if (currentRow.timesheets.OT_HOD_Verified == 1){
                                timesheeteditor.inline( this, {
                                  onBlur: 'submit'
                                } );
                              }
                            } else {

                              timesheeteditor.inline( this, {
                                onBlur: 'submit'
                              } )
                            }

                       } );

                       timesheeteditor.on('postSubmit', function (e, json, data) {
                          timesheettable.api().ajax.reload(null,false);
                           var modifier = timesheeteditor.modifier();
                           var currentRow = timesheettable.api().row(modifier).node();
                           if(json.data.length > 0) {

                               if (json.data[0].timesheets.OT_Verified == 1) {
                                 $(currentRow).addClass('otverified');
                               } else {
                                   $(currentRow).removeClass('otverified');
                               }

                               if (json.data[0].timesheets.OT_HOD_Verified == 1) {
                                 $(currentRow).addClass('hodverified');
                               } else {
                                   $(currentRow).removeClass('hodverified');
                               }

                               if (json.data[0].timesheets.OT_HOD_Verified == 0 && json.data[0].timesheets.OT_Verified == 1) {
                                 $(currentRow).addClass('hod-not-verified-hr-verified');
                               } else {
                                   $(currentRow).removeClass('hod-not-verified-hr-verified');
                               }
                           }
                       });

                       $('#engineertable tbody').on('click', 'td', function () {


                            var data=timesheettable.api().row(this).data();
                            var json=JSON.stringify(data);


                            if(this.cellIndex==13)
                            {

                              myfunction("Out",data.timesheets.Latitude_Out,data.timesheets.Longitude_Out);
                            }
                            else if(this.cellIndex==12){

                              myfunction("In",data.timesheets.Latitude_In,data.timesheets.Longitude_In);

                            }

                        } );

                       $("#ajaxloader").hide();

                       $('a[data-toggle="tab"]').on( 'shown.bs.tab', function (e) {
                         var target = $(e.target).attr("href") // activated tab

                           $.fn.dataTable.tables( {visible: true, api: true} ).columns.adjust();

                       } );

                       $("thead input").keyup ( function () {
                               /* Filter on the column (the index) of this element */

                               if ($('#engineertable').length > 0)
                               {

                                   var colnum=document.getElementById('engineertable').rows[0].cells.length;

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
        HR OT Management
        <small>Human Resource</small>
      </h1>
      <ol class="breadcrumb">
        <li><a href="{{ url('/home') }}"><i class="fa fa-dashboard"></i> Home</a></li>
        <li><a href="#">Human Resource</a></li>
        <li class="active">HR OT Management</li>
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

        <div class="modal fade" id="ViewPhoto" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
          <div class="modal-dialog photobox"  role="document">
            <div class="modal-content">
              <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title" id="myModalLabel">Photo</h4>
              </div>
              <div class="modal-body" name="photo" id="photo">

                <center>
                  <img id='photoholder' width='180px' height='100%' src='{{ asset('img/default-user.png') }}'/>
                </center>
              </div>
              <div class="modal-footer">
                <center>
                  <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                </center>
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





    </div>



  <div class="box">
    <div class="box-body">
      <div class="row">

        <div class="col-md-6">
        <div class="input-group">
          <div class="input-group-addon">
            <i class="fa fa-clock-o"></i>
          </div>
          <input type="text" class="form-control" id="range" name="range">

        </div>
      </div>
      <div class="col-md-2">
        <div class="checkbox">
          <label><input type="checkbox" id="includeresigned" name="includeresigned" {{ $includeResigned == "true" ? 'checked' : '' }}> Include Resigned</label>
        </div>
      </div>
      <div class="col-md-4">
          <div class="input-group">
            <button type="button" class="btn btn-danger btn-small" data-toggle="modal" onclick="refresh();"><i class="fa fa-refresh fa-2"></i></button>
          </div>
      </div>
      <label></label>

      <div class="col-md-12">
        <table id="engineertable" class="display" cellspacing="0" width="100%" padding="30px" style="font-size: 13px;">
            <thead>
              <tr class="search">
                @foreach($timesheetdetail as $key=>$value)

                  @if ($key==0)
                    <?php $i = 0; ?>

                    @foreach($value as $field=>$a)
                        @if ($i==0|| $i==1 )
                          <th align='center'><input type='hidden' class='search_init' name='{{$i}}' placemark='{{$a}}'></th>
                        @else
                          <th align='center'><input type='text' class='search_init' name='{{$i}}'  placemark='{{$a}}'></th>
                        @endif

                        <?php $i ++; ?>
                    @endforeach

                    {{-- <th align='center'><input type='text' class='search_init' name='{{$i}}'  placemark='{{$a}}'></th>
                    <th align='center'><input type='text' class='search_init' name='{{$i}}'  placemark='{{$a}}'></th> --}}

                  @endif

               @endforeach
              </tr>
                {{-- prepare header search textbox --}}
                <tr>
                  @foreach($timesheetdetail as $key=>$value)

                    @if ($key==0)
                      @foreach($value as $field=>$value)
                          <td/>{{ $field }}</td>
                      @endforeach

                    @endif

                  @endforeach
                </tr>
            </thead>
            <tbody>



          </tbody>
            <tfoot></tfoot>
        </table>
      </div>
      </div>
    </div>
  </div>
  <br>



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

    $('#range').daterangepicker({locale: {
      format: 'DD-MMM-YYYY'
    },startDate: '{{$start}}',
    endDate: '{{$end}}'});

  });

      function refresh()
      {
        var d=$('#range').val();
        var includeresigned=$('#includeresigned').is(':checked');
        var arr = d.split(" - ");

        window.location.href ="{{ url("/otmanagementhr") }}/"+arr[0]+"/"+arr[1]+"/"+includeresigned;

      }

      function viewphoto(img)
      {
        $('#ViewPhoto').modal('show');

        $("#photoholder").attr("src","{{url('/')}}"+img+"");


      }

      function submit() {

        var boxes = $('input[type="checkbox"]:checked', timesheettable.fnGetNodes() );
        var ids="";

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

          $.ajax({
                      url: "{{ url('/timesheet/submit') }}",
                      method: "POST",
                      data: {TimesheetIds:ids},

                      success: function(response){

                        if (response==1)
                        {

                            timesheettable.ajax.url("{{ asset('/Include/timesheetapproval.php') }}").load();

                            $('#Submit').modal('hide');

                            var message="Submitted for next action!";
                            $("#update-alert ul").html(message);
                            $("#update-alert").modal('show');

                            $("#ajaxloader").hide();

                        }
                        else {

                          $('#Submit').modal('hide');

                          var errormessage="Failed to submit for next action!";
                          $("#error-alert ul").html(errormessage);
                          $("#error-alert").modal('show');

                          $("#ajaxloader").hide();

                        }

              }
          });

      }
      else {

        $('#Submit').modal('hide');

        var errormessage="No timesheet selected!";
        $("#error-alert ul").html(errormessage);
        $("#error-alert").modal('show');



        $("#ajaxloader").hide();
      }
    }
</script>

@endsection
