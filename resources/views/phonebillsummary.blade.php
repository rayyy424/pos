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

      .box-header .box-title{
        font-size: 14px;
      }
      span#Grand_Total{
        color:red;
      }

      tr.header{
        color: #0e42b3;
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
      <h1>
        Phone Bill Summary
        <small>Administration</small>
      </h1>
      <ol class="breadcrumb">
       <li><a href="{{ url('/home') }}"><i class="fa fa-dashboard"></i> Home</a></li>
       <li><a href="#">Administration</a></li>
       <li class="active">Phone Bill Summary</li>
      </ol>
    </section>

    <!-- Main content -->
    <section class="content">

      <div class="row>">

        <div class="col-md-2">
          <select class="form-control" id="year">
            <option <?php if($year == '2017') echo ' selected="selected" '; ?>>2017</option>
            <option <?php if($year == '2018') echo ' selected="selected" '; ?>>2018</option>

          </select>
        </div>

        <div class="col-md-2">
          <select class="form-control" id="operator">

            <option></option>
            @foreach($category as $category1)

              <option <?php if($category1->Option == $operator) echo ' selected="selected" '; ?>>{{$category1->Option}}</option>

            @endforeach

          </select>
        </div>

        <div class="col-md-2">
          <select class="form-control" id="number">
            <option></option>
            @foreach($numbers as $num)

              <option <?php if($num->Phone_No == $number) echo ' selected="selected" '; ?>>{{$num->Phone_No}}</option>

            @endforeach

          </select>
        </div>

        <div class="col-md-2">

          <div class="input-group">
            <button type="button" class="btn btn-success btn" data-toggle="modal" onclick="refresh();">Refresh</button>
          </div>

        </div>

      </div>

      <div class="row">

        <div class="col-md-12">
          <!-- <div class="box">
            <div class="box-body"> -->

                          <div class="box-body">

                            <div class="col-md-8">
                              <div class="box box-primary">
                                <div class="box-header with-border">
                                  <h4 class="box-title">Phone Bill {{$operator}} {{$number}}</h4>

                                </div>
                                <div class="box-body">
                                  <div class="chart">
                                    <canvas id="lineChart_overall" style="height:55px;"></canvas>
                                  </div>
                                </div>
                              </div>
                            </div>

                            <div class="col-md-4">
                              <div class="box box-primary">
                                <div class="box-header with-border">
                                  <table class="table table-bordered">
                                    <tr class="header">
                                      <td>Paid Month</td>
                                      <td>Total (RM)</td>
                                    </tr>
                                    <?php $i = 0; ?>
                                    @foreach($bill as $first)

                                      <tr>
                                          @foreach($first as $key=>$value)
                                            <td>
                                              {{ $value }}
                                            </td>
                                          @endforeach
                                      </tr>
                                      <?php $i++; ?>
                                    @endforeach

                                  </table>
                                </div>
                              </div>
                            </div>

                          </div>

            <!-- </div>
          </div> -->

          <!-- /.nav-tabs-custom -->
        </div>
        <!-- /.col -->
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

function refresh() {
    var year = document.getElementById("year").value;
    var operator = document.getElementById("operator").value;
    var number = document.getElementById("number").value;

    if(operator=="")
    {

      window.location.href ="{{ url("/phonebillsummary") }}/"+year;
    }
    else if(number=="")
    {

      window.location.href ="{{ url("/phonebillsummary") }}/"+year+"/"+operator;
    }
    else {

      window.location.href ="{{ url("/phonebillsummary") }}/"+year+"/"+operator+"/"+number;
    }

}

//line chart js
    $(function () {

      var str_all = "{{ $bills }}";
         str_all = str_all.split(",").map(function(str_all){
               return str_all;
           })
       var a_all=[str_all];
       var line_all = document.getElementById("lineChart_overall");
       var lineChart_overall = new Chart(line_all, {
           type: 'line',
           data: {
             labels:a_all[0],
             datasets: [{
             label: 'Paid Amount',
             data: [{{ $amount }}],
             borderColor: "#3c8dbc",
             pointBackgroundColor: "#3c8dbc",
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
