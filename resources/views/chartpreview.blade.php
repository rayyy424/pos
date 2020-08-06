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

@endsection

@section('content')

  <div class="content-wrapper">
      <!-- Content Header (Page header) -->
    <section class="content-header">
      <h1>
      Chart Preview
      <small>{{$chartview->Chart_View_Name}}</small>
      </h1>

      <ol class="breadcrumb">
      <li><a href="{{ url('/home') }}"><i class="fa fa-dashboard"></i> Home</a></li>
      <li><a href="#">Chart Preview</a></li>
      <li class="active">{{$chartview->Chart_View_Name}}</li>
      </ol>
    </section>

    <section class="content">
      <div class="row">
        <div class="box box-primary">
          <div class="box-body">
            <div class="col-md-12">

              <h4 class="box-title"><b>Chart View Name : {{$chartview->Chart_View_Name}}<b></h4>
              <h5 class="box-title"><b>Chart View Type : {{$chartview->Chart_View_Type}}<b></h5>
              <h5 class="box-title"><b>Project Name : {{$chartview->Project_Name}}<b></h5>

            </div>

            <div class="box-body">
              <div class="chart">
                <canvas id="lineChart" style="height:300px;"></canvas>
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

// function getColor(index) {
//     var col=[];
//     var c=0;
//
//     @foreach($chartcolumn as $chart)
//
//       col.push("{{$chart->Series_Color}}");
//       c=c+1;
//
//     @endforeach
//
//     col.push("#2ecc71");
//     col.push("#3498db");
//     col.push("#95a5a6");
//     col.push("#9b59b6");
//     col.push("#f1c40f");
//     col.push("#e74c3c");
//     col.push("#34495e");
//
//     var i=(index % (c+7));
//
//     return col[i];
// }

$(function () {
  var str = "{{$chartlabel}}";
     str = str.split(",").map(function(str){
           return str;
       })
   var a=[str];
   var line = document.getElementById("lineChart");

   var linechart = new Chart(line, {
       type: "bar",
       data: {
         labels:a[0],
         datasets: [
           @for ($i=0; $i <count($serieslabel) ; $i++)
           {
             label: '{{$serieslabel[$i]}}',
             data: [{{$chartdata[$i]}}],
             borderColor: '{{$seriescolor[$i]}}',
             pointBackgroundColor: '{{$seriescolor[$i]}}',
             backgroundColor: '{{$seriescolor[$i]}}',
             fill:false,
             type:"{{$seriestype[$i]}}"

           },

           @endfor
         ],
       },

       options : {
         scales : {
             xAxes : [ {
                 gridLines : {
                     display : true
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
                     display : true
                 }
             } ]
         }
     }
     });

});

</script>

@endsection
