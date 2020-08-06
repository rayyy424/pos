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

      .weekend {
        color: red;
      }
      .historytable{
        text-align: center;
      }

      .historyheader{
        background-color: gray;
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
      <script type="text/javascript" language="javascript" src="https://cdn.datatables.net/plug-ins/1.10.13/api/sum().js"></script>

      <script type="text/javascript" language="javascript" src="{{ asset('/plugin/js/dataTables.editor.min.js') }}"></script>
      {{-- <script type="text/javascript" language="javascript" src="{{ asset('/plugin/examples/resources/syntax/shCore.js') }}"></script>
      <script type="text/javascript" language="javascript" src="{{ asset('/plugin/examples/resources/demo.js') }}"></script>
      <script type="text/javascript" language="javascript" src="{{ asset('/plugin/examples/resources/editor-demo.js') }}"></script> --}}

      <script type="text/javascript" language="javascript" class="init">
          var summary;
          var summary2;
          var summary3;
          var selectedtabindex;
          var totalallowance;
          $(document).ready(function() {
                        summary =  $('#summary').dataTable( {

                                 dom: "fpBrt",
                                 bAutoWidth: true,
                                 rowId:"monthDate.Date",
                                 //aaSorting:false,
                                 columnDefs: [{ "visible": false, "targets": [0] },{"className": "dt-center", "targets": "_all"}],
                                 bScrollCollapse: true,
                                 sScrollX: "100%",
                                 bAutoWidth: true,
                                 sScrollY: "100%",
                                 bPaginate:true,
                                 dom: "lBfrtpi",
                                 columns:[
                                  //  {
                                  //    sortable: false,
                                  //    "render": function ( data, type, full, meta ) {
                                   //
                                  //        return '<input type="checkbox" name="selectrow" id="selectrow" class="selectrow" value="'+full.users.Id+'" onclick="uncheck()">';
                                   //
                                  //    }
                                   //
                                  //  },
                                   {data:'users.Id', name:'users.Id'},
                                   {data:'users.Name', name:'users.Name'},
                                   {data:'users.StaffId', name:'users.StaffId'},
                                   {data:'Total_Submitted', name:'Total_Submitted'},
                                   {data:'Pending_Approval', name:'Pending_Approval'},
                                   {data:'Total_Approved', name:'Total_Approved'},
                                   {data:'Total_Rejected', name:'Total_Rejected'},
                                   {data:'Total_On_Duty', name:'Total_On_Duty'},
                                   {data:'Total_On_Leave', name:'Total_On_Leave'},
                                   {data:'Total_Weekend', name:'Total_Weekend'},
                                   {data:'Total_Standby', name:'Total_Standby'}

                                 ],
                                 select: {
                                         style:    'os',
                                         selector: 'td:first-child'
                                 },
                                 buttons: [

                                 ],

                     });

                    summary2 = $('#summary2').dataTable( {


                             bAutoWidth: true,
                             rowId:"monthDate.Date",
                             //aaSorting:false,
                             columnDefs: [{ "visible": false, "targets": [1] },{"className": "dt-center", "targets": "_all"}],
                             bScrollCollapse: true,
                             sScrollX: "100%",
                             bAutoWidth: true,
                             sScrollY: "100%",
                             bPaginate:true,
                             dom: "Blfrtpi",
                             columns:[
                               {
                                 sortable: false,
                                 "render": function ( data, type, full, meta ) {

                                     return '<input type="checkbox" name="selectrow0" id="selectrow0" class="selectrow0" value="'+full.users.Id+'" onclick="uncheck(0)">';

                                 }

                               },
                               {data:'users.Id', name:'users.Id'},
                               {data:'users.Name', name:'users.Name'},
                               {data:'users.StaffId', name:'users.StaffId'},
                               {data:'Total_Submitted', name:'Total_Submitted'},
                               {data:'Pending_Approval', name:'Pending_Approval'},
                               {data:'Total_Approved', name:'Total_Approved'},
                               {data:'Total_Rejected', name:'Total_Rejected'},
                               {data:'Total_On_Duty', name:'Total_On_Duty'},
                               {data:'Total_On_Leave', name:'Total_On_Leave'},
                               {data:'Total_Weekend', name:'Total_Weekend'},
                               {data:'Total_Standby', name:'Total_Standby'}

                             ],
                             select: {
                                     style:    'os',
                                     selector: 'td:first-child'
                             },
                             buttons: [
                               {
                                 text: 'Notify',
                                 action: function ( e, dt, node, config ) {
                                     // clearing all select/input options
                                     $('#Submit').modal('show');
                                     selectedtabindex=0;
                                 },
                               },
                             ],

                 });

                    summary3 = $('#summary3').dataTable( {

                               bAutoWidth: true,
                               rowId:"monthDate.Date",
                               //aaSorting:false,
                               columnDefs: [{ "visible": false, "targets": [1] },{"className": "dt-center", "targets": "_all"}],
                               bScrollCollapse: true,
                               sScrollX: "100%",
                               bAutoWidth: true,
                               sScrollY: "100%",
                               bPaginate:true,
                               dom: "Blfrtpi",
                               columns:[
                                 {
                                   sortable: false,
                                   "render": function ( data, type, full, meta ) {

                                       return '<input type="checkbox" name="selectrow1" id="selectrow1" class="selectrow1" value="'+full.users.Id+'" onclick="uncheck(1)">';

                                   }

                                 },
                                 {data:'users.Id', name:'users.Id'},
                                 {data:'users.Name', name:'users.Name'},
                                 {data:'users.StaffId', name:'users.StaffId'},
                                 {data:'Total_Submitted', name:'Total_Submitted'},
                                 {data:'Pending_Approval', name:'Pending_Approval'},
                                 {data:'Total_Approved', name:'Total_Approved'},
                                 {data:'Total_Rejected', name:'Total_Rejected'},
                                 {data:'Total_On_Duty', name:'Total_On_Duty'},
                                 {data:'Total_On_Leave', name:'Total_On_Leave'},
                                 {data:'Total_Weekend', name:'Total_Weekend'},
                                 {data:'Total_Standby', name:'Total_Standby'}

                               ],
                               select: {
                                       style:    'os',
                                       selector: 'td:first-child'
                               },
                               buttons: [
                                 {
                                   text: 'Notify',
                                   action: function ( e, dt, node, config ) {
                                       // clearing all select/input options
                                       $('#SubmitIncomplete').modal('show');
                                       selectedtabindex=1;
                                   },
                                 },
                               ],

                   });

                   totalallowance = $('#totalallowance').dataTable( {

                              bAutoWidth: true,
                              //rowId:"monthDate.Date",
                              //aaSorting:false,
                              columnDefs: [{ "visible": false, "targets": [] },{"className": "dt-center", "targets": "_all"}],
                              bScrollCollapse: true,
                              sScrollX: "100%",
                              bAutoWidth: true,
                              sScrollY: "100%",
                              bPaginate:true,
                              dom: "Blfrtpi",
                              // columns:[
                              //   {
                              //     sortable: false,
                              //     "render": function ( data, type, full, meta ) {
                              //
                              //         return '<input type="checkbox" name="selectrow1" id="selectrow1" class="selectrow1" value="'+full.users.Id+'" onclick="uncheck(1)">';
                              //
                              //     }
                              //
                              //   },
                              //   {data:'users.Id', name:'users.Id'},
                              //   {data:'users.Name', name:'users.Name'},
                              //   {data:'users.StaffId', name:'users.StaffId'},
                              //   {data:'Total_Submitted', name:'Total_Submitted'},
                              //   {data:'Pending_Approval', name:'Pending_Approval'},
                              //   {data:'Total_Approved', name:'Total_Approved'},
                              //   {data:'Total_Rejected', name:'Total_Rejected'},
                              //   {data:'Total_On_Duty', name:'Total_On_Duty'},
                              //   {data:'Total_On_Leave', name:'Total_On_Leave'},
                              //   {data:'Total_Weekend', name:'Total_Weekend'},
                              //   {data:'Total_Standby', name:'Total_Standby'}
                              //
                              // ],
                              select: {
                                      style:    'os',
                                      selector: 'td:first-child'
                              },
                              buttons: [
                                // {
                                //   text: 'Notify',
                                //   action: function ( e, dt, node, config ) {
                                //       // clearing all select/input options
                                //       $('#SubmitIncomplete').modal('show');
                                //       selectedtabindex=1;
                                //   },
                                // },
                              ],

                  });

                   $('a[data-toggle="tab"]').on( 'shown.bs.tab', function (e) {

                     var target = $(e.target).attr("href") // activated tab

                       $.fn.dataTable.tables( {visible: true, api: true} ).columns.adjust();

                   } );

              } );

      </script>


@endsection

@section('content')

    <div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
      <h1>
        Timesheet Summary
        <small>Timesheet Details</small>
      </h1>
      <ol class="breadcrumb">
        <li><a href="{{ url('/home') }}"><i class="fa fa-dashboard"></i> Home</a></li>
        <li><a href="#">Resource Management</a></li>
        <li><a href="#">Timesheet</a></li>
        <li class="active">Timesheet Summary</li>
      </ol>
    </section>

    <!-- Main content -->
    <section class="content">

      <div class="row">
        <div id="update-alert" class="alert alert-success alert-dismissible" style="display:none;">
          <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
          <h4><i class="icon fa fa-check"></i> Alert!</h4>
          <ul>

          </ul>
        </div>

         <div id="error-alert" class="alert alert-danger alert-dismissible" style="display:none;">
           <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
           <h4><i class="icon fa fa-ban"></i> Alert!</h4>
           <ul>

           </ul>
         </div>

        <div class="box box-success">
          <br>

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
              <button type="button" class="btn btn-success btn" data-toggle="modal" onclick="refresh();">Refresh</button>
            </div>
          </div>

        <label></label>
      </div>

      <div class="modal fade" id="Submit" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
        <div class="modal-dialog" role="document">
          <div class="modal-content">
            <div class="modal-header">
              <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
              <h4 class="modal-title" id="myModalLabel">Submit for Reminder</h4>
            </div>
            <div class="modal-body">
                Are you sure you wish submit the pending timesheets?
            </div>
            <div class="modal-footer">
              <center><img src="{{ URL::to('/') ."/img/ajax-loader.gif" }}" width="50px" height="50px" alt="Loading" name='ajaxloader' id="ajaxloader"></center>
              <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
              <button type="button" class="btn btn-primary" onclick="submit()">Yes</button>
            </div>
          </div>
        </div>
      </div>

      <div class="modal fade" id="SubmitIncomplete" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
        <div class="modal-dialog" role="document">
          <div class="modal-content">
            <div class="modal-header">
              <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
              <h4 class="modal-title" id="myModalLabel">Submit for Reminder</h4>
            </div>
            <div class="modal-body">
                Are you sure you wish submit the incomplete timesheets?
            </div>
            <div class="modal-footer">
              <center><img src="{{ URL::to('/') ."/img/ajax-loader.gif" }}" width="50px" height="50px" alt="Loading" name='ajaxloader' id="ajaxloader2"></center>
              <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
              <button type="button" class="btn btn-primary" onclick="submitincomplete()">Yes</button>
            </div>
          </div>
        </div>
      </div>

      <div class="col-md-12">

         <div class="modal fade" id="ViewList" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
               <div class="modal-dialog" role="document">
                 <div class="modal-content">
                   <div class="modal-header">
                     <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                     <h4 class="modal-title" id="myModalLabel">Name List</h4>
                   </div>
                   <div class="modal-body" name="list" id="list">

                   </div>
                   <div class="modal-footer">
                     <center><img src="{{ URL::to('/') ."/img/ajax-loader.gif" }}" width="50px" height="50px" alt="Loading" name='ajaxloader3' id="ajaxloader4"></center>
                     <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                   </div>
                 </div>
               </div>
             </div>

             <div class="nav-tabs-custom">
               <ul class="nav nav-tabs">

                 <li class="active"><a href="#alltimesheet" data-toggle="tab">All</a></li>
                 <li><a href="#pending" data-toggle="tab">Pending Submission</a></li>
                 <li><a href="#incomplete" data-toggle="tab">Incomplete Submission</a></li>
                 <li><a href="#allowance" data-toggle="tab">Total Allowance</a></li>
               </ul>

               <div class="tab-content">

                 <div class="active tab-pane" id="alltimesheet">

                   <?php

                    $date1 = new DateTime($start);
                    $date2 = new DateTime($end);
                    $diff = $date2->diff($date1)->format("%a")+1; ?>

                   <h5><b>Number of days : {{$diff}} Days&nbsp;&nbsp;&nbsp;&nbsp;[{{$start}} to {{$end}}]</b></h5>

                   <table id="summary" class="display" cellspacing="0" width="100%" padding="30px" style="font-size: 13px;">
                       <thead>
                           {{-- prepare header search textbox --}}
                           <tr>

                             @foreach($summary as $key=>$value)

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
                         @foreach($summary as $timesheet)

                               <tr id="row_{{ $i }}" >
                                   @foreach($timesheet as $key=>$value)

                                     @if($key=="Name")
                                       <td>
                                         <a href="{{ url("/timesheet") }}/{{$timesheet->Id}}/true/{{$start}}/{{$end}}" target="_blank">{{$value}}</a>
                                       </td>
                                     @else
                                        <td>
                                          {{ $value }}
                                        </td>
                                      @endif

                                   @endforeach
                               </tr>
                               <?php $i++; ?>

                         @endforeach

                     </tbody>
                       <tfoot></tfoot>
                   </table>

                 </div>

                 <div class="tab-pane" id="pending">

                   <h5><b>Number of days : {{$diff}} Days&nbsp;&nbsp;&nbsp;&nbsp;[{{$start}} to {{$end}}]</b></h5>

                   <table id="summary2" class="display" cellspacing="0" width="100%" padding="30px" style="font-size: 13px;">
                       <thead>
                           {{-- prepare header search textbox --}}
                           <tr>

                             @foreach($summary as $key=>$value)

                               @if ($key==0)
                               <td><input type="checkbox" name="selectall0" id="selectall0" value="all" onclick="checkall(0)"></td>

                                 @foreach($value as $field=>$value)
                                     <td/>{{ $field }}</td>
                                 @endforeach

                               @endif

                             @endforeach
                           </tr>
                       </thead>
                       <tbody>

                         <?php $i = 0; ?>
                         @foreach($summary as $timesheet)

                           @if ($timesheet->Total_Submitted==0)

                               <tr id="row_{{ $i }}" >
                                 <td></td>
                                   @foreach($timesheet as $key=>$value)
                                     <td>
                                       {{ $value }}
                                     </td>
                                   @endforeach
                               </tr>
                               <?php $i++; ?>
                            @endif

                         @endforeach

                     </tbody>
                       <tfoot></tfoot>
                   </table>

                 </div>

                 <div class="tab-pane" id="incomplete">

                   <h5><b>Number of days : {{$diff}} Days&nbsp;&nbsp;&nbsp;&nbsp;[{{$start}} to {{$end}}]</b></h5>

                   <table id="summary3" class="display" cellspacing="0" width="100%" padding="30px" style="font-size: 13px;">
                       <thead>
                           {{-- prepare header search textbox --}}
                           <tr>

                             @foreach($summary as $key=>$value)

                               @if ($key==0)
                               <td><input type="checkbox" name="selectall1" id="selectall1" value="all" onclick="checkall(1)"></td>

                                 @foreach($value as $field=>$value)
                                     <td/>{{ $field }}</td>
                                 @endforeach

                               @endif

                             @endforeach
                           </tr>
                       </thead>
                       <tbody>

                         <?php $i = 0; ?>
                         @foreach($summary as $timesheet)

                           @if ($timesheet->Total_Submitted>0 && $timesheet->Total_Submitted<$diff)

                               <tr id="row_{{ $i }}" >
                                  <td></td>

                                   @foreach($timesheet as $key=>$value)
                                     <td>
                                       {{ $value }}
                                     </td>
                                   @endforeach
                               </tr>
                               <?php $i++; ?>

                            @endif

                         @endforeach

                     </tbody>
                       <tfoot></tfoot>
                   </table>

                 </div>

                 <div class="tab-pane" id="allowance">

                   <h5><b>Number of days : {{$diff}} Days&nbsp;&nbsp;&nbsp;&nbsp;[{{$start}} to {{$end}}]</b></h5>

                   <table id="totalallowance" class="display" cellspacing="0" width="100%" padding="30px" style="font-size: 13px;">
                       <thead>
                           {{-- prepare header search textbox --}}
                           <tr>

                             @foreach($totalallowance as $key=>$value)

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
                         @foreach($totalallowance as $total)

                               <tr id="row_{{ $i }}" >

                                   @foreach($total as $key=>$value)
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

     $('#range').daterangepicker({locale: {
        format: 'DD-MMM-YYYY'
      },startDate: '{{$start}}',
      endDate: '{{$end}}'});

    });

    function refresh()
    {
      var d=$('#range').val();
      var arr = d.split(" - ");

      window.location.href ="{{ url("/timesheetsummary") }}/"+arr[0]+"/"+arr[1];

    }

    function viewlist(date,status)
    {
        $('#ViewList').modal('show');
        $("#list").html("");

        $.ajaxSetup({
          headers: { 'X-CSRF-Token' : $('meta[name=_token]').attr('content') }
        });

        $("#ajaxloader4").show();

        $.ajax({
                    url: "{{ url('/timesheetsummary/viewlist') }}",
                    method: "POST",
                    data: {
                      Date:date, Status:status
                    },
                    success: function(response){
                      if (response==0)
                      {
                        var message ="Failed to retrieve asset list!";
                        $("#warning-alert ul").html(message);
                        $("#warning-alert").show();
                        $('#ReturnedModal').modal('hide')

                        $("#ajaxloader4").hide();
                      }
                      else {

                        $("#exist-alert").hide();


                        var myObject = JSON.parse(response);

                            var display='<table border="1" align="center" class="historytable" cellspacing="0" width="100%" padding="30px" style="font-size: 13px;">';
                            display+='<tr class="historyheader"><td>Name</td></tr>';

                            $.each(myObject, function(i,item){

                                    if (item.Project_Name===null)
                                    {
                                      item.Project_Name=" - ";
                                    }

                                    display+="<tr>";
                                    display+='<td>'+item.Name+'</td>';
                                    display+="</tr>";
                            });

                        display+="</table>";

                        $("#list").html(display);

                        $("#ajaxloader4").hide();
                      }
            }
        });

    }

    function uncheck(index)
    {

      if (!$("#selectrow"+index).is(':checked')) {
        $("#selectall"+index).prop("checked", false)
      }

    }

    function checkall(index)
    {
  // alert(document.getElementById("selectall").checked);

      if ($("#selectall"+index).is(':checked')) {

          $(".selectrow"+index).prop("checked", true);
           $(".selectrow"+index).trigger("change");
           if (index==0)
           {
                summary2.api().rows().select();
           }else if (index==1)
           {
                summary3.api().rows().select();
           }

      } else {

          $(".selectrow"+index).prop("checked", false);
          $(".selectrow"+index).trigger("change");
           //leavetable.rows().deselect();
           if (index==0)
           {
                summary2.api().rows().deselect();
           }else if (index==1) {
                summary3.api().rows().deselect();
           }
      }
    }

    function submit() {

      var boxes = $(".selectrow"+selectedtabindex+":checkbox:checked");
      var ids="";
      var start="{{$start}}";
      var end="{{$end}}";

      if (boxes.length>0)
      {
        for (var i = 0; i < boxes.length; i++) {
          ids+=boxes[i].value+",";
        }

        ids=ids.substring(0, ids.length-1);
        //console.log(ids);

        $.ajaxSetup({
           headers: { 'X-CSRF-Token' : $('meta[name=_token]').attr('content') }
        });

          $("#ajaxloader").show();

        $.ajax({
                    url: "{{ url('timesheetsummary/submit') }}",
                    method: "POST",
                    data: {UserIds:ids,
                    Start:start,
                    End:end},


                    success: function(response){

                      if (response==1)
                      {


                          var message="Pending timesheets are submitted !";
                          $("#update-alert ul").html(message);
                          $("#update-alert").show();

                          setTimeout(function() {
                            $("#update-alert").fadeOut();
                          }, 6000);

                          $('#Submit').modal('hide');

                          $("#ajaxloader").hide();

                      }
                      else {

                        var errormessage="Failed to submit!";
                        $("#error-alert ul").html(errormessage);
                        $("#error-alert").show();

                        setTimeout(function() {
                          $("#error-alert").fadeOut();
                        }, 6000);

                        $('#Submit').modal('hide');

                        $("#ajaxloader").hide();

                      }

            }
        });

    }
    else {
      var errormessage="No user selected!";
      $("#error-alert ul").html(errormessage);
      $("#error-alert").show();

      setTimeout(function() {
        $("#error-alert").fadeOut();
      }, 6000);

      $('#Submit').modal('hide');

      $("#ajaxloader").hide();
    }
  }

  function submitincomplete() {

    var boxes = $(".selectrow"+selectedtabindex+":checkbox:checked");
    var ids="";
    var start="{{$start}}";
    var end="{{$end}}";

    if (boxes.length>0)
    {
      for (var i = 0; i < boxes.length; i++) {
        ids+=boxes[i].value+",";
      }

      ids=ids.substring(0, ids.length-1);
      //console.log(ids);

      $.ajaxSetup({
         headers: { 'X-CSRF-Token' : $('meta[name=_token]').attr('content') }
      });

        $("#ajaxloader2").show();

      $.ajax({
                  url: "{{ url('timesheetsummary/submit2') }}",
                  method: "POST",
                  data: {UserIds:ids,
                  Start:start,
                  End:end},


                  success: function(response){

                    if (response==1)
                    {


                        var message="Pending timesheets are submitted !";
                        $("#update-alert ul").html(message);
                        $("#update-alert").show();

                        setTimeout(function() {
                          $("#update-alert").fadeOut();
                        }, 6000);

                        $('#SubmitIncomplete').modal('hide');

                        $("#ajaxloader2").hide();

                    }
                    else {

                      var errormessage="Failed to submit!";
                      $("#error-alert ul").html(errormessage);
                      $("#error-alert").show();

                      setTimeout(function() {
                        $("#error-alert").fadeOut();
                      }, 6000);

                      $('#SubmitIncomplete').modal('hide');

                      $("#ajaxloader2").hide();

                    }

          }
      });

  }
  else {
    var errormessage="No user selected!";
    $("#error-alert ul").html(errormessage);
    $("#error-alert").show();

    setTimeout(function() {
      $("#error-alert").fadeOut();
    }, 6000);

    $('#SubmitIncomplete').modal('hide');

    $("#ajaxloader2").hide();
  }
}



  </script>



@endsection
