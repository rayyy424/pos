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
      <script type="text/javascript" language="javascript" src="https://cdn.datatables.net/plug-ins/1.10.13/api/sum().js"></script>


      <script type="text/javascript" language="javascript" src="{{ asset('/plugin/js/dataTables.editor.min.js') }}"></script>

      {{-- chart js --}}
      <script type="text/javascript" language="javascript" src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.5.0/Chart.bundle.min.js"></script>

@endsection

@section('content')

<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <h1>Warehouse KPI<small>Delivery Management</small></h1>

        <ol class="breadcrumb">
            <li><a href="{{ url('/home') }}"><i class="fa fa-dashboard"></i> Home</a></li>
            <li><a href="#">Delivery Management</a></li>
            <li class="active">Warehouse KPI</li>
        </ol>
    </section>

    <br>

    <section class="content">

        <div class="row">
            <div class="col-md-12">
                <div class="box">
                    <div class="box-body">
                        <div class="row">
                            <div class="col-md-4">
                                <div class="input-group">
                                    <div class="input-group-addon">
                                        <i class="fa fa-clock-o"></i>
                                    </div>
                                    <input type="text" class="form-control" id="range" name="range">
                                </div>
                            </div>
                            <div class="col-md-2">
                                <div class="input-group">
                                    <button type="button" class="btn btn-success btn" data-toggle="modal" onclick="refresh();">Refresh</button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-md-6">
                <div class="box box-primary">
                    <div class="box-header with-border">
                        <h4 class="box-title">Graph</h4>
                    </div>
                    <div class="box-body">
                        <div class="chart">
                            <canvas id="lineChart_overall" style="height:300px;"></canvas>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-6">
                <div class="box box-primary">
                    <div class="box-header with-border">
                        <table id="deliveryreview" class="table table-hover table-bordered" cellspacing="0" width="100%" padding="30px" style="font-size: 13px;">
                            <thead>
                                <tr>
                                    <td>No</td>
                                    <td>Staff ID </td>
                                    <td>Staff Name</td>
                                    <td>Request Time</td>
                                    <td>Recall Time</td>
                                </tr>
                            </thead>
                            <tbody>
                               
                            </tbody>
                            <tfoot></tfoot>
                        </table>
                </div>
            </div>
        </div>
    </section>

    <footer class="main-footer">
        <div class="pull-right hidden-xs">
            <b>Version</b> 1.0.0
        </div>
        <strong>Copyright &copy; 2014-2016 <a href="http://www.softoya.com">TrackerOnTheGo</a>.</strong> All rights reserved.
    </footer>
</div>

    <script>
    $(function () {

        $(".select2").select2();
    });

      $(function () {

      $('#range').daterangepicker({locale: {
        format: 'DD-MMM-YYYY'
      },
      startDate: '{{date("d-M-Y",strtotime($start))}}',
      endDate: '{{date("d-M-Y",strtotime($end))}}'});

    });

    function refresh()
    {
      var d=$('#range').val();
      var arr = d.split(" - ");
      var requestor = document.getElementById("requestor").value;
      window.location.href ="{{ url("/requestorkpi") }}/"+arr[0]+"/"+arr[1]+"/"+requestor;

    }

    $(function () {

       var line_all = document.getElementById("lineChart_overall");
       var lineChart_overall = new Chart(line_all, {
           type: 'bar',
           data: {
             labels:["Summary"],
             datasets: [{
             label: 'Request',
             data: [{{ $totalrequest }}],
             borderColor: "#00ff00",
             pointBackgroundColor: "#004d00",
             backgroundColor: "#00ff00",
             fill:false,
            },{
            label: 'Recall',
             data: [{{ $totalrecall }}],
             borderColor: "#ff0000",
             pointBackgroundColor: "#660000",
             backgroundColor: "#ff0000",
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
   });

    </script>

@endsection