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
                                 columnDefs: [{ "visible": false, "targets": [0,1,2] },{"className": "dt-center", "targets": "_all"}],
                                 bScrollCollapse: true,
                                 sScrollX: "100%",
                                 bAutoWidth: true,
                                 sScrollY: "100%",
                                 bPaginate:true,
                                 dom: "lBfrtpi",
                                //  columns:[
                                //   //  {
                                //   //    sortable: false,
                                //   //    "render": function ( data, type, full, meta ) {
                                //    //
                                //   //        return '<input type="checkbox" name="selectrow" id="selectrow" class="selectrow" value="'+full.users.Id+'" onclick="uncheck()">';
                                //    //
                                //   //    }
                                //    //
                                //   //  },
                                //    {data:'users.Id', name:'users.Id'},
                                //    {data:'users.Name', name:'users.Name'},
                                //    {data:'users.StaffId', name:'users.StaffId'},
                                //    {data:'Total_Submitted', name:'Total_Submitted'},
                                //    {data:'Pending_Approval', name:'Pending_Approval'},
                                //    {data:'Total_Approved', name:'Total_Approved'},
                                //    {data:'Total_Rejected', name:'Total_Rejected'},
                                //    {data:'Total_On_Duty', name:'Total_On_Duty'},
                                //    {data:'Total_On_Leave', name:'Total_On_Leave'},
                                //    {data:'Total_Weekend', name:'Total_Weekend'},
                                //    {data:'Total_Standby', name:'Total_Standby'}
                                 //
                                //  ],
                                 select: {
                                         style:    'os',
                                         selector: 'td:first-child'
                                 },
                                 buttons: [

                                 ],

                     });

                     $("thead input").keyup ( function () {

                             /* Filter on the column (the index) of this element */
                             if ($('#summary').length > 0)
                             {

                                 var colnum=document.getElementById('summary').rows[0].cells.length;

                                 if (this.value=="[empty]")
                                 {

                                    summary.fnFilter( '^$', $("thead input").index(this)-colnum,true,false );
                                 }
                                 else if (this.value=="[nonempty]")
                                 {

                                    summary.fnFilter( '^(?=\\s*\\S).*$', $("thead input").index(this)-colnum,true,false );
                                 }
                                 else if (this.value.startsWith("!")==true && this.value.length>1)
                                 {

                                    summary.fnFilter( '^(?'+ this.value +').*', $("thead input").index(this)-colnum,true,false );
                                 }
                                 else if (this.value.startsWith("!")==false)
                                 {

                                     summary.fnFilter( this.value, $("thead input").index(this)-colnum,true,false );
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
        Claim & Timesheet Summary
        <small>Claim & Timesheet Details</small>
      </h1>
      <ol class="breadcrumb">
        <li><a href="{{ url('/home') }}"><i class="fa fa-dashboard"></i> Home</a></li>
        <li><a href="#">Management Tool</a></li>
        <li><a href="#">HR Management</a></li>
        <li class="active">Claim & Timesheet Summary</li>
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

         <div class="modal fade" id="UpdateClaimRemark" role="dialog" aria-labelledby="myModalLabel">
           <div class="modal-dialog">
             <div class="modal-content">
               <div class="modal-header">
                 <div id="assign-alert" class="alert alert-warning alert-dismissible" style="display:none;">
                   <h4><i class="icon fa fa-check"></i> Alert!</h4>
                   <div id="assignmessage"></div>
                 </div>
                 <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                 <h4 class="modal-title" id="myModalLabel">Claim Remark</h4>

               </div>

               <div class="modal-body">
                 <input type="hidden" id="claimstatusid" name="claimstatusid" value=0>
                 <input type="hidden" id="claimsheetid" name="claimsheetid" value=0>

                 <input type="text" class="form-control" id="claimremark" name="claimremark" placeholder="Enter Remark...">

               </div>
               <div class="modal-footer">

                 <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                 <button type="button" class="btn btn-primary" onclick="updateclaimremark()">Update</button>
               </div>
             </div>
           </div>
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

      <div class="col-md-12">

           <?php

            $date1 = new DateTime($start);
            $date2 = new DateTime($end);
            $diff = $date2->diff($date1)->format("%a")+1; ?>

           <h5><b>Number of days : {{$diff}} Days&nbsp;&nbsp;&nbsp;&nbsp;[{{$start}} to {{$end}}]</b></h5>

           <table id="summary" class="display" cellspacing="0" width="100%" padding="30px" style="font-size: 13px;">
             <thead>

                <tr class="search">

                  @foreach($summary as $key=>$value)

                    @if ($key==0)

                      @foreach($value as $field=>$value)
                          <td align='center'><input type='text' class='search_init' /></td>
                      @endforeach

                    @endif

                  @endforeach
                </tr>
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

                             @if($key=="Timesheet Submitted" && $value=="Yes")
                               <td>
                                 <a href="{{ url("/timesheet") }}/{{$timesheet->Id}}/true/{{$start}}/{{$end}}" target="_blank">{{$value}}</a>
                               </td>
                            @elseif($key=="Claim Submitted" && $value=="Yes")
                                 <td>
                                   <a href="{{ url("/claim") }}/{{$timesheet->ClaimsheetId}}/{{$timesheet->Id}}" target="_blank">{{$value}}</a>
                                 </td>
                            @elseif($key=="Claim_Remarks")
                                  <td>
                                    <i>{{$value}}</i><br>

                                    <button type="button" class="btn btn-primary btn" data-toggle="modal" onclick="callclaimremarks('{{$timesheet->ClaimsheetId}}','{{$timesheet->ClaimsheetStatusesId}}','{{$timesheet->Claim_Remarks}}')">Add Remark</button>
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

     $('#range').daterangepicker({locale: {
        format: 'DD-MMM-YYYY'
      },startDate: '{{$start}}',
      endDate: '{{$end}}'});

    });

    function refresh()
    {
      var d=$('#range').val();
      var arr = d.split(" - ");

      window.location.href ="{{ url("/claimtimesheetsummary") }}/"+arr[0]+"/"+arr[1];

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
                        $('#ReturnedModal').modal('hide')
                        $("#warning-alert ul").html(message);
                        $("#warning-alert").modal('show');


                        $("#ajaxloader4").hide();
                      }
                      else {

                        $("#exist-alert").hide();


                        var myObject = JSON.parse(response);

                            var display='<table border="1" align="center" class="historytable" cellspacing="0" width="100%" padding="30px" style="font-size: 13px;">';
                            display+='<tr class="historyheader"><td>Name</td></tr>';

                            $.each(myObject, function(i,item){

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
                          $("#update-alert").modal('show');


                          $('#Submit').modal('hide');

                          $("#ajaxloader").hide();

                      }
                      else {

                        var errormessage="Failed to submit!";
                        $("#error-alert ul").html(errormessage);
                        $("#error-alert").modal('show');

                        $('#Submit').modal('hide');

                        $("#ajaxloader").hide();

                      }

            }
        });

    }
    else {
      var errormessage="No user selected!";
      $("#error-alert ul").html(errormessage);
      $("#error-alert").modal('show');

      $('#Submit').modal('hide');

      $("#ajaxloader").hide();
    }
  }

  function callclaimremarks(ClaimsheetId,ClaimstatusId,Remark)
  {
    $('#claimsheetid').val(ClaimsheetId);
    $('#claimstatusid').val(ClaimstatusId);
    $('#claimremark').val(Remark);

    $('#UpdateClaimRemark').modal('show');

  }

  function updateclaimremark()
  {

    $.ajaxSetup({
       headers: { 'X-CSRF-Token' : $('meta[name=_token]').attr('content') }
    });

    claimsheetid=$('[name="claimsheetid"]').val();
    claimstatusid=$('[name="claimstatusid"]').val();
    claimremark=$('[name="claimremark"]').val();

    $.ajax({
                url: "{{ url('claimsheet/updateremark') }}",
                method: "POST",
                data: {ClaimsheetId:claimsheetid,
                ClaimstatusId:claimstatusid,
                Remark:claimremark},

                success: function(response){

                  if (response>0)
                  {

                      var message="Remarks Updated !";
                      $("#update-alert ul").html(message);
                      $("#update-alert").modal('show');

                        window.location.reload();

                      $('#UpdateClaimRemark').modal('hide');

                  }
                  else {

                    var errormessage="Failed to update remarks!";
                    $("#error-alert ul").html(errormessage);
                    $("#error-alert").modal('show');



                    $('#UpdateClaimRemark').modal('hide');

                  }

        }
    });

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
                        $("#update-alert").modal('show');


                        $('#SubmitIncomplete').modal('hide');

                        $("#ajaxloader2").hide();

                    }
                    else {

                      var errormessage="Failed to submit!";
                      $("#error-alert ul").html(errormessage);
                      $("#error-alert").modal('show');


                      $('#SubmitIncomplete').modal('hide');

                      $("#ajaxloader2").hide();

                    }

          }
      });

  }
  else {
    var errormessage="No user selected!";
    $("#error-alert ul").html(errormessage);
    $("#error-alert").modal('show');


    $('#SubmitIncomplete').modal('hide');

    $("#ajaxloader2").hide();
  }
}



  </script>



@endsection
