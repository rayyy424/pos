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

    <script type="text/javascript" language="javascript" src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.5.0/Chart.bundle.min.js"></script>

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
      div.DTE_Body div.DTE_Body_Content div.DTE_Field {
            padding: 15px;
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

      $(document).ready(function() {

                 var table = $('#targettable').dataTable( {
                   dom: "Bfrtp",
                   bAutoWidth: false,
                   //aaSorting:false,
                   columnDefs: [{ "visible": false, "targets": [] }],
                   bScrollCollapse: true,
                   select: {
                           style:    'os',
                           selector: 'td'
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

                table.api().on( 'order.dt search.dt', function () {
                  table.api().column(0, {search:'applied', order:'applied'}).nodes().each( function (cell, i) {
                      cell.innerHTML = i+1;
                  } );
              } ).draw();

              $("thead input").keyup ( function () {

                      /* Filter on the column (the index) of this element */
                      if ($('#targettable').length > 0)
                      {

                          var colnum=document.getElementById('targettable').rows[0].cells.length;

                          if (this.value=="[empty]")
                          {

                             table.fnFilter( '^$', $("thead input").index(this)-colnum,true,false );
                          }
                          else if (this.value=="[nonempty]")
                          {

                             table.fnFilter( '^(?=\\s*\\S).*$', $("thead input").index(this)-colnum,true,false );
                          }
                          else if (this.value.startsWith("!")==true && this.value.length>1)
                          {

                             table.fnFilter( '^(?'+ this.value +').*', $("thead input").index(this)-colnum,true,false );
                          }
                          else if (this.value.startsWith("!")==false)
                          {

                            table.fnFilter( this.value, $("thead input").index(this)-colnum,true,false );
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
      Target Preview
      <small>Target Rules Maintenance</small>
      </h1>

      <ol class="breadcrumb">
      <li><a href="{{ url('/home') }}"><i class="fa fa-dashboard"></i> Home</a></li>
      <li><a href="#">Project Management</a></li>
      <li><a href="#">Target Rules Maintenance</a></li>
      <li class="active">Target Preview</li>
      </ol>
    </section>

    <section class="content">

      <div class="row">
        <div class="box box-primary">
          <div class="box-body">

            <div class="col-md-12">

              <h4 class="box-title"><b>Target Rule : {{$rule->Title}}<b></h4>
              <h5 class="box-title"><b>Target Date : {{$rule->Target_Date}} [Week {{$targetweek}}]<b></h5>
              <h5 class="box-title"><b>Target : {{$rule->Target}}<b></h5>

            </div>

            <div class="box-body">
              <div class="chart">
                <canvas id="lineChart" style="height:300px;"></canvas>
              </div>
            </div>

            <div class="col-md-12">

              <table id="targettable" class="display" cellspacing="0" width="100%" padding="30px" style="font-size: 13px;">
                  <thead>
                    <tr class="search">
                      @foreach($targets as $key=>$values)
                        @if ($key==0)

                          <td align='center'><input type='hidden' class='search_init' /></td>

                        @foreach($values as $field=>$value)

                            <td align='center'><input type='text' class='search_init' /></td>

                        @endforeach

                        @endif

                      @endforeach
                    </tr>
                      <tr>
                        @foreach($targets as $key=>$value)

                          @if ($key==0)
                            <td>No</td>
                            @foreach($value as $field=>$value)
                                <td/>{{ $field }}</td>
                            @endforeach

                          @endif

                        @endforeach
                      </tr>
                  </thead>
                  <tbody>

                    @foreach($targets as $key=>$value)

                      <tr>
                        <td></td>
                        @foreach($value as $field=>$value)
                            <td/>{{ $value }}</td>
                        @endforeach
                      </tr>

                    @endforeach

                </tbody>
                  <tfoot></tfoot>
              </table>

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
  var str = "{{$label}}";
     str = str.split(",").map(function(str){
           return str;
       })
   var a=[str];
   var line = document.getElementById("lineChart");

   var originalLineDraw = Chart.controllers.line.prototype.draw;
  Chart.helpers.extend(Chart.controllers.line.prototype, {
    draw: function() {
      originalLineDraw.apply(this, arguments);

      var chart = this.chart;
      var ctx = chart.chart.ctx;

      var index = chart.config.data.lineAtIndex;
      if (index) {
        var xaxis = chart.scales['x-axis-0'];
        var yaxis = chart.scales['y-axis-0'];

        ctx.save();
        ctx.beginPath();
        ctx.moveTo(xaxis.getPixelForValue(undefined, index), yaxis.getPixelForValue({{$rule->Target}}));
        ctx.strokeStyle = '#ff0000';
        ctx.setLineDash([10,5]);
        ctx.lineTo(xaxis.getPixelForValue(undefined, index), yaxis.getPixelForValue(0));
        ctx.stroke();
        ctx.restore();

      }
    }
  });

   var linechart = new Chart(line, {
       type: 'line',
       data: {
         labels:a[0],
         datasets: [{
           label: 'Total {{$rule->Target_Field}}',
           data: [{{$data}}],
           borderColor: "#3c8dbc",
           pointBackgroundColor: "#3c8dbc",
           fill:false,
         },{
           label: 'Target {{$rule->Target_Field}}',
           data: [{{$targetdata}}],
           borderColor: "red",
           borderWidth: 3,
           radius: 0,
           pointBackgroundColor: "red",
           borderDash: [10,5],
           fill:false,
         },],
         lineAtIndex: {{$targetindex}},
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
