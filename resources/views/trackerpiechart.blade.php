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

      table.dataTable  {
  			white-space: nowrap;
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
      {{-- <script type="text/javascript" language="javascript" src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.4.0/Chart.bundle.min.js"></script> --}}
      <script type="text/javascript" language="javascript" src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.8.0/Chart.bundle.min.js"></script>
      <script src="https://cdn.jsdelivr.net/npm/chartjs-plugin-datalabels@0.7.0"></script>
      <script type="text/javascript" language="javascript" class="init">

          var all;

          var tot;

          $(document).ready(function() {

            all = $('#all').dataTable( {

                    dom: "Brt",
                    bAutoWidth: true,
                    aaSorting: false,
                    bPaginate:false,
                    columnDefs: [{ "visible": false, "targets": [] },{"className": "dt-left", "targets": 0},{"className": "dt-right", "targets": [1]}],
                    bScrollCollapse: true,
                    columns: [
                      {data:'Expenses_Type'},
                      {data:'Total'}
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

              tot = $('#total').dataTable( {

                      dom: "Brt",
                      bAutoWidth: true,
                      aaSorting: false,
                      bPaginate:false,
                      columnDefs: [{ "visible": false, "targets": [] },{"className": "dt-left", "targets": 0},{"className": "dt-right", "targets": [1]}],
                      bScrollCollapse: true,
                      columns: [
                        {data:'Region'},
                        {data:'Total'},
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
        Region Summary
        <small>Summary</small>
      </h1>
      <ol class="breadcrumb">
        <li><a href="{{ url('/home') }}"><i class="fa fa-dashboard"></i> Home</a></li>
        <li><a href="#">Project Management</a></li>
        <li><a href="#">Summary</a></li>
        <li class="active">Region Summary</li>
      </ol>
    </section>

    <!-- Main content -->
    <section class="content">

      <div class="modal fade" id="chartpopup" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title" id="myModalLabel">Total Expenses</h4>
                </div>
                <div class="modal-body" name="chart" id="chart">

                            <div class="box box-primary">
                                <div class="box-header with-border">

                                </div>
                                <div class="box-body">
                                    <canvas id="pieChartpopup"  ></canvas>
                                </div>
                            </div>

                </div>
                <div class="modal-footer">
                <center><img src="{{ URL::to('/') ."/img/ajax-loader.gif" }}" width="50px" height="50px" alt="Loading" name='ajaxloader3' id="ajaxloader3"></center>
                <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
      </div>
<br>
      <div class="row">


      <div class="row">
        <div class="col-sm-6">
          <label class="col-sm-3">Unique ID: </label>
          <div class="col-sm-6">
            <select id="trackerId">
              @foreach($tracker as $t)
                <option value="{{$t->Id}}" {{$tracId == $t->Id ? "selected":""}}>{{$t->site}}</option>
              @endforeach
            </select>
          </div>
        </div>
        <div class="col-sm-6">
          <a  onclick="refresh()" class="btn btn-sm btn-primary">Refresh</a>
        </div>
      </div>
      <br>
      <div class="row">
        <div class="col-md-6">
          <div class="box box-primary">
            <div class="box-header with-border">
              <h3 class="box-title">Expenses Type</h3>
            </div>
            <div class="row">
              <div class="box-body" class="col-md-12">
                <canvas id="pieChart"  width="300" height="300" ></canvas>
              </div>
            </div>
            
          </div>
        </div>

        <div class="col-md-6">
          <div class="box box-primary">
            <div class="box-header with-border">
              <h3 class="box-title">Expenses Summary</h3>
            </div>
            <div class="box-body">

              <table id="all" class="display" cellspacing="0" width="100%" padding="30px" style="font-size: 13px;">
                  <thead>
                      {{-- prepare header search textbox --}}
                      <tr>

                        @foreach($total as $key=>$value)

                          @if ($key==0)

                            @foreach($value as $field=>$value)
                                <td/>{{ $field }}</td>
                            @endforeach

                          @endif

                        @endforeach
                      </tr>
                  </thead>
                  <tbody>
                    @if(isset($total[0]) && $total[0]->Total != "")
                    <?php $i = 0; ?>
                    @foreach($total as $type)

                          <tr id="row_{{ $i }}" >

                              @foreach($type as $key=>$value)
                                <td>
                                  @if(is_numeric($value))
                                    RM {{ number_format($value,2,".",",") }}
                                  @else
                                    {{ $value }}
                                  @endif
                                </td>
                              @endforeach
                          </tr>
                          <?php $i++; ?>

                    @endforeach
                  @endif

                </tbody>
                  <tfoot></tfoot>
              </table>

            </div>

            <div class="box-body">

              <table id="total" class="display" cellspacing="0" width="100%" padding="30px" style="font-size: 13px;">
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
                    @if(isset($summary[0]) && $summary[0]->Total != "")
                    <?php $i = 0; ?>
                    @foreach($summary as $type)

                          <tr id="row_{{ $i }}" >

                              @foreach($type as $key=>$value)
                                <td>
                                  @if(is_numeric($value))
                                    RM {{ number_format($value,2,".",",") }}
                                  @else
                                    {{ $value }}
                                  @endif
                                </td>
                              @endforeach
                          </tr>
                          <?php $i++; ?>

                    @endforeach
                  @endif

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
      var project=$('[name="Project"]').val();
      var site=$('[name="Site"]').val();

      if(project && project!="0")
      {
        if(site && site!="0")
        {
            window.location.href ="{{ url("/ewalletsummary") }}/"+arr[0]+"/"+arr[1]+"/"+project+"/"+site;
        }
        else {
            window.location.href ="{{ url("/ewalletsummary") }}/"+arr[0]+"/"+arr[1]+"/"+project;
        }

      }
      else {
          window.location.href ="{{ url("/ewalletsummary") }}/"+arr[0]+"/"+arr[1]
      }



    }

  $(function () {
     //pie chart js
    $("#trackerId").select2({width:'100%'});
    var pie = document.getElementById("pieChart");
    var pieOptions = {
      events: true,
    
      animation: {
        duration: 500,
        easing: "easeOutQuart",
        onComplete: function () {
          var ctx = this.chart.ctx;
          ctx.font = Chart.helpers.fontString(Chart.defaults.global.defaultFontFamily, 'normal', Chart.defaults.global.defaultFontFamily);
          ctx.textAlign = 'center';
          ctx.textBaseline = 'bottom';

          this.data.datasets.forEach(function (dataset) {

            for (var i = 0; i < dataset.data.length; i++) {
              var model = dataset._meta[Object.keys(dataset._meta)[0]].data[i]._model,
                  total = dataset._meta[Object.keys(dataset._meta)[0]].total,
                  mid_radius = model.innerRadius + (model.outerRadius - model.innerRadius)/2,
                  start_angle = model.startAngle,
                  end_angle = model.endAngle,
                  mid_angle = start_angle + (end_angle - start_angle)/2;

              var x = mid_radius * Math.cos(mid_angle);
              var y = mid_radius * Math.sin(mid_angle);

              ctx.fillStyle = '#fff';
              if (i == 3){ // Darker text color for lighter background
                ctx.fillStyle = '#fff';
              }
              var percent = String(Math.round(dataset.data[i]/total*100)) + "%";
              ctx.fillText("RM " + dataset.data[i].toLocaleString("en"), model.x + x, model.y + y);
              // Display percent in another line, line break doesn't work for fillText
              ctx.fillText(percent, model.x + x, model.y + y + 15);

            }
          });
        }
      }
    };
  var str = "{{$title}}";
    str = str.split(",").map(function(str){
          return str; // add quotes
      })
      //alert(str);

  var a = [str];
    var piechart = new Chart(pie, {
      type: 'pie',
      data: {
        labels: a[0],
        datasets: [{
          backgroundColor: [
            "#2ecc71",
            "#3498db",
            "#95a5a6",
            "#9b59b6",
            "#f1c40f",
            "#e74c3c",
            "#34495e"
          ],
          data: [{{$data}}]
        }],
      },
      // options: pieOptions,
      options:{
        responsive:true,
        plugins: {
            datalabels: {
                formatter: function(value, context) {
                  var model = context.dataset._meta[Object.keys(context.dataset._meta)[0]].data[context.dataIndex]._model,
                  total = context.dataset._meta[Object.keys(context.dataset._meta)[0]].total,
                  mid_radius = model.innerRadius + (model.outerRadius - model.innerRadius)/2,
                  start_angle = model.startAngle,
                  end_angle = model.endAngle,
                  mid_angle = start_angle + (end_angle - start_angle)/2;

                var x = mid_radius * Math.cos(mid_angle);
                var y = mid_radius * Math.sin(mid_angle);
                var percent = String(Math.round(context.dataset.data[context.dataIndex]/total*100)) + "%";
          

                  return "RM " + context.dataset.data[context.dataIndex].toLocaleString("en")+ "\n\t\t\t\t\t\t\t\t"+percent;
                },
                color:'black'
            }
        }
      }
     });
    //  piechart.canvas.parentNode.style.width="680px";

  });
  
  function refresh(){

    var url=window.location.href;
    let track=$("#trackerId").val();
    if(url.indexOf('&trackerId') !== -1){
      let index=url.indexOf('&trackerId');
      let temp=url.substring(0,index+11);
      console.log(temp);
      url=temp;
      url+=track;
    }
    else
      url+="&trackerId=" + track;
    window.location.href=url;
  }
  </script>

@endsection
