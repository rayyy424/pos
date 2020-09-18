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
            var entitlement;
            var grade;

            $(document).ready(function() {

              entitlement=$('#leaveentitlement').dataTable( {
                      columnDefs: [{ "visible": false, "targets": [1] },{"className": "dt-right", "targets": [4]},{"className": "dt-left", "targets": "_all"}],
                      responsive: false,
                      colReorder: false,
                      //dom: "Brt",
                      sScrollX: "100%",
                      // order: [[ 1, "asc" ],[ 2, "asc" ],[ 3, "asc" ]],
                      //sScrollY: "100%",
                      dom: "Blrftip",
                      iDisplayLength:25,

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

          entitlement.api().on( 'order.dt search.dt', function () {
              entitlement.api().column(0, {search:'applied', order:'applied'}).nodes().each( function (cell, i) {
                  cell.innerHTML = i+1;
              } );
          } ).draw();

          $(".leaveentitlement thead input").keyup ( function () {

                  /* Filter on the column (the index) of this element */
                  if ($('#leaveentitlement').length > 0)
                  {

                      var colnum=document.getElementById('leaveentitlement').rows[0].cells.length;

                      if (this.value=="[empty]")
                      {

                         entitlement.fnFilter( '^$', this.name,true,false );
                      }
                      else if (this.value=="[nonempty]")
                      {

                         entitlement.fnFilter( '^(?=\\s*\\S).*$', this.name,true,false );
                      }
                      else if (this.value.startsWith("!")==true && this.value.length>1)
                      {

                         entitlement.fnFilter( '^(?'+ this.value +').*', this.name,true,false );
                      }
                      else if (this.value.startsWith("!")==false)
                      {

                          entitlement.fnFilter( this.value, this.name,true,false );
                      }
                  }



          } );



            });
      </script>

      @endsection

@section('content')

  <div class="content-wrapper">
      <!-- Content Header (Page header) -->
    <section class="content-header">
      <h1>
      Time-off Report
      <small>Human Resource</small>
      </h1>

      <ol class="breadcrumb">
        <li><a href="{{ url('/home') }}"><i class="fa fa-dashboard"></i> Home</a></li>
        <li><a href="#">Human Resource</a></li>
        <li><a href="#">Leave</a></li>
        <li class="active">Time-off Report</li>
      </ol>
    </section>

    <section class="content">

      <div class="modal fade" id="NameList" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
          <div class="modal-dialog" role="document">
            <div class="modal-content">
              <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title" id="myModalLabel">Name List</h4>
              </div>
              <div class="modal-body" name="list" id="list">

              </div>
              <div class="modal-footer">
                <center><img src="{{ URL::to('/') ."/img/ajax-loader.gif" }}" width="50px" height="50px" alt="Loading" name='ajaxloader3' id="ajaxloader3"></center>
                <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
              </div>
            </div>
          </div>
      </div>

      <div class="row">

        <div class="box">
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
              <button type="button" class="btn btn-danger btn-small" data-toggle="modal" onclick="refresh();"><i class="fa fa-refresh fa-2"></i></button>
            </div>
        </div>
        <label></label>
      </div>
    </div>

      <div class="row">

        <div class="col-md-12">
          <div class="box box-body">

              <div class="form-group">

                Period :

             </div>


            <!-- <button type="button" class="pull-right btn btn-primary btn-lg" data-toggle="modal" data-target="#CreateNewScheme">Create New Scheme</button>
            <button type="button" class="btn btn-danger btn-lg" data-toggle="modal" onclick="OpenRemoveDialog()">Remove Scheme</button> -->

            <div class="row">

              <div class="col-md-12">
                  <table id="leaveentitlement" class="leaveentitlement" cellspacing="0" width="100%" padding="30px" style="font-size: 13px;">

                      <thead>
                        <tr class="search">

                          @foreach($report as $key=>$value)

                            @if ($key==0)
                              <?php $i = 0; ?>

                              @foreach($value as $field=>$a)
                                  @if ($i==0)
                                    <th align='center'><input type='hidden' class='search_init' name='{{$i}}' placemark='{{$a}}'></th>
                                  @else
                                    <th align='center'><input type='text' class='search_init' name='{{$i}}'  placemark='{{$a}}'></th>
                                  @endif

                                  <?php $i ++; ?>
                              @endforeach

                              <th align='center'><input type='text' class='search_init' name='{{$i}}'  placemark='{{$a}}'></th>

                            @endif

                          @endforeach
                        </tr>
                          {{-- prepare header search textbox --}}

                          <tr>
                            @foreach($report as $key=>$value)

                              @if ($key==0)

                              <td></td>

                                @foreach($value as $field=>$value)

                                    <td/>{{ $field }}</td>

                                @endforeach

                              @endif

                            @endforeach
                          </tr>
                      </thead>

                      <tbody>

                        <?php $i = 0; ?>
                        @foreach($report as $leave)

                          <tr id="row_{{ $i }}">
                            <td></td>
                              @foreach($leave as $key=>$value)
                                <td>

                                  @if($key=="Hours")
                                    <a onclick='viewdata("{{$leave->Id}}","{{$start}}","{{$end}}")'>{{ $value }}</a>
                                  @else
                                    {{ $value }}
                                  @endif

                                </td>
                              @endforeach
                          </tr>
                          <?php $i++; ?>
                        @endforeach

                    </tbody>

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
    <b>Version</b> 1.0.0
  </div>
  <strong>Copyright &copy; 2014-2016 <a href="http://www.softoya.com">TrackerOnTheGo</a>.</strong> All rights
  reserved.
</footer>

<script>

$(function () {

  //Initialize Select2 Elements
  $(".select2").select2();

  $('#range').daterangepicker({locale: {
    format: 'DD-MMM-YYYY'
  },startDate: '{{$start}}',
  endDate: '{{$end}}'});

});

function refresh()
{
  var d=$('#range').val();
  var arr = d.split(" - ");

  window.location.href ="{{ url("/timeoffreport") }}/"+arr[0]+"/"+arr[1];

}

function viewdata(userid,start,end)
{
   $('#NameList').modal('show');
   $("#list").html("");

   $.ajaxSetup({
      headers: { 'X-CSRF-Token' : $('meta[name=_token]').attr('content') }
   });

   $("#ajaxloader3").show();

   $.ajax({
               url: "{{ url('leavesummary/viewdata3') }}",
               method: "POST",
               data: {
                 UserId:userid,
                 Start:start,
                 End:end
               },
               success: function(response){
                 if (response==0)
                 {

                   var message ="Failed to retrieve user leave details!";
                   $("#warning-alert ul").html(message);
                   $("#warning-alert").show();

                   $("#ajaxloader3").hide();
                 }
                 else {
                   $("#exist-alert").hide();

                   var myObject = JSON.parse(response);

                   var display='<table border="1" align="center" class="leavetable" cellspacing="0" width="100%" padding="30px" style="font-size: 13px;">';
                   display+='<tr class="tableheader"><td>Leave_Term</td><td>Start_Date</td><td>End_Date Date</td><td>No_of_Days</td><td>Reason</td><td>Leave_Status</td></tr>';

                   $.each(myObject, function(i,item){

                     display+="<tr>";
                     display+='<td>'+item.Leave_Term+'</td><td>'+item.Start_Date+'</td><td>'+item.End_Date+'</td><td>'+item.No_of_Days+'</td><td>'+item.Reason+'</td><td>'+item.Leave_Status+'</td>';
                     display+="</tr>";

                   });

                   $("#list").html(display);

                   $("#ajaxloader3").hide();
                 }
       }
   });

 }

</script>



@endsection
