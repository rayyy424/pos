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
      {{-- <script type="text/javascript" language="javascript" src="{{ asset('/plugin/examples/resources/syntax/shCore.js') }}"></script>
      <script type="text/javascript" language="javascript" src="{{ asset('/plugin/examples/resources/demo.js') }}"></script>
      <script type="text/javascript" language="javascript" src="{{ asset('/plugin/examples/resources/editor-demo.js') }}"></script> --}}

      {{-- chart js --}}
      <script type="text/javascript" language="javascript" src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.5.0/Chart.bundle.min.js"></script>

      <script type="text/javascript" language="javascript" class="init">

          $(document).ready(function() {
                         $('#interntable').dataTable( {

                                 dom: "flBrtp",
                                 bAutoWidth: true,
                                 rowId:"monthDate.Date",
                                 //aaSorting:false,
                                 columnDefs: [{ "visible": false, "targets": [] },{"className": "dt-center", "targets": "_all"}],
                                 bScrollCollapse: true,
                                 columns: [
                                   { data: "MonthDate.Date"},
                                   { data: "Total",
                                   "render": function ( data, type, full, meta ) {
                                     return "<a href='#' onclick='internlist(\""+full.MonthDate.Date+"\",\"Total\");'>"+data+"</a>";
                                    }
                                   },
                                   { data: "Accepted",
                                    "render": function ( data, type, full, meta ) {
                                      return "<a href='#' onclick='internlist(\""+full.MonthDate.Date+"\",\"Accepted\");'>"+data+"</a>";
                                    }
                                   },
                                   { data: "Pending",
                                    "render": function ( data, type, full, meta ) {
                                      return "<a href='#' onclick='internlist(\""+full.MonthDate.Date+"\",\"Pending\");'>"+data+"</a>";
                                      }
                                   }



                                 ],
                                 select: {
                                         style:    'os',
                                         selector: 'td:first-child'
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

              } );

      </script>

@endsection

@section('content')
  <div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
      <h1>
        Resource Tracker
        <small>Resource Management</small>
      </h1>
      <ol class="breadcrumb">
        <li><a href="{{ url('/home') }}"><i class="fa fa-dashboard"></i> Home</a></li>
        <li><a href="#">Resource Management</a></li>
        <li class="active">Resource Tracker</li>
      </ol>
    </section>

    <!-- Main content -->
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

      <!-- BAR CHART -->
        <div class="col-md-12">
          <div class="box box-primary">
            <div class="box-header with-border">
              <h3 class="box-title">Resource Availability</h3>

            </div>
            <div class="box-body">
              <div class="chart">
                <canvas id="lineChart" style="height:300px;"></canvas>
              </div>
            </div>
            <!-- /.box-body -->
          </div>
        </div>
          <!-- /.box -->

      <div class="col-md-12">

        <table id="interntable" class="display" cellspacing="0" width="100%" padding="30px" style="font-size: 13px;">
            <thead>
                {{-- prepare header search textbox --}}
                <tr>

                  @foreach($approved as $key=>$value)

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
              @foreach($approved as $approveduser)

                    <tr id="row_{{ $i }}" >

                        @foreach($approveduser as $key=>$value)
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

    </section>




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

     $('#range').daterangepicker({locale: {
        format: 'DD-MMM-YYYY'
      },startDate: '{{$start}}',
      endDate: '{{$end}}'});

    });

    function refresh()
    {
      var d=$('#range').val();
      var arr = d.split(" - ");

      window.location.href ="{{ url("/resourcetracker") }}/"+arr[0]+"/"+arr[1];

    }
   function internlist(date,type)
   {
      $('#NameList').modal('show');
      $("#list").html("");

      $.ajaxSetup({
         headers: { 'X-CSRF-Token' : $('meta[name=_token]').attr('content') }
      });

      $("#ajaxloader3").show();

      $.ajax({
                  url: "{{ url('list') }}",
                  method: "POST",
                  data: {
                    Date:date,
                    Type:type
                  },
                  success: function(response){
                    if (response==0)
                    {

                      var message ="Failed to retrieve assistant engineer list!";
                      $("#warning-alert ul").html(message);
                      $("#warning-alert").show();
                      $('#ReturnedModal').modal('hide')

                      $("#ajaxloader3").hide();
                    }
                    else {
                      $("#exist-alert").hide();

                      var myObject = JSON.parse(response);

                      var display='<table border="1" align="center" class="interntable" cellspacing="0" width="100%" padding="30px" style="font-size: 13px;">';
                      display+='<tr class="tableheader"><td>Name</td><td>Institution</td><td>Major</td><td>Start Date</td><td>End Date</td></tr>';

                      $.each(myObject, function(i,item){

                        display+="<tr>";
                        display+='<td>'+item.name+'</td><td>'+item.Institution+'</td><td>'+item.Major+'</td><td>'+item.Internship_Start_Date+'</td><td>'+item.Internship_End_Date+'</td>';
                        display+="</tr>";

                      });

                      $("#list").html(display);

                      $("#ajaxloader3").hide();
                    }
          }
      });

    }


 //line chart js
     $(function () {
       var str = "{{$title}}";
          str = str.split(",").map(function(str){
                return str;
            })
        var a=[str];
        var line = document.getElementById("lineChart");
        var linechart = new Chart(line, {
            type: 'line',
            data: {
              labels:a[0],
              datasets: [{
                label: 'Total',
                data: [{{$data1}}],
                borderColor: "#3c8dbc",
                pointBackgroundColor: "#3c8dbc",
                fill:false,
              },
              {
                label: 'Accepted',
                data: [{{$data2}}],
                borderColor: "#087b0d",
                pointBackgroundColor: "#087b0d",
                fill:false,
              },
              {
                label: 'Pending',
                data: [{{$data3}}],
                borderColor: "#bb3e2e",
                pointBackgroundColor: "#bb3e2e",
                fill:false,
              }]
            },
            options : {
              scales : {
                  xAxes : [ {
                      gridLines : {
                          display : false
                      }
                  } ],
                  yAxes : [ {
                      ticks: {
                       beginAtZero: true,
                       userCallback: function(label, index, labels) {
                         if (Math.floor(label) === label) {
                           return label;
                         }

                       },
                     },
                      gridLines : {
                          display : false
                      }
                  } ]
              }
          }
          });

           $("#lineChart").click(

           function(evt){
              //
              var activePoints = linechart.getElementsAtEvent(evt);
              var firstPoint = activePoints[0];
              var index = firstPoint._index;
              var dataset = linechart.data.labels[index];
              // console.log(dataset);

              var a=linechart.getDatasetAtEvent(evt);
              var b = a[0];
              var c = b._datasetIndex;
              var label = linechart.data.datasets[c].label;

              // console.log(label);
              internlist(dataset,label);

            });

     });

  </script>



@endsection
