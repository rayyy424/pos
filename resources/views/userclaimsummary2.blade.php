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
      <script type="text/javascript" language="javascript" src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.4.0/Chart.bundle.min.js"></script>

       <script type="text/javascript" language="javascript" class="init">

          $(document).ready(function() {

            $('#all').dataTable( {

                    dom: "Brt",
                    bAutoWidth: true,
                    aaSorting:false,
                    bPaginate:false,
                    columnDefs: [{ "visible": false, "targets": [] },{"className": "dt-left", "targets": 0},{"className": "dt-right", "targets": [1,2]}],
                    bScrollCollapse: true,
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
        Claim Summary
        <small>Finance Management</small>
      </h1>
      <ol class="breadcrumb">
        <li><a href="{{ url('/home') }}"><i class="fa fa-dashboard"></i> Home</a></li>
        <li><a href="#">Finance Management</a></li>
        <li><a href="#">Claim Summary</a></li>
        <li class="active">Claim Details</li>
      </ol>
    </section>

    <!-- Main content -->
    <section class="content">

      <div class="row">

          <div class="col-md-4">

            <div class="box box-success">

              <div class="box-body box-profile">

              <br>
              <div class="row">
                <div class="form-group">
                  @if ($user->Web_Path)

                    <img class="profile-user-img img-responsive img-circle" name="profileimage" id="profileimage" src="{{ url($user->Web_Path) }}" alt="User profile picture">
                  @else
                      <img class="profile-user-img img-responsive img-circle" name="profileimage" id="profileimage" src="{{ URL::to('/') ."/img/default-user.png"  }}" alt="User profile picture">
                  @endif
                </div>
              </div>

              <div class="row">

                <div class="form-group">
                  <div class="col-lg-6">
                    <label>StaffId : <i>{{$user->StaffId}}</i></label>
                  </div>

                  <div class="col-lg-6">
                    <label>Name : <i>{{$user->Name}}</i></label>
                  </div>

                </div>
              </div>

              <div class="row">
                <div class="form-group">

                  <div class="col-lg-6">

                    <label>Position : <i>{{$user->Position}}</i></label>
                  </div>

                </div>
              </div>

            <div class="row">
              <div class="form-group">
                <div class="col-lg-6">
                  <label>Nationality : <i>{{$user->Nationality}}</i></label>

                </div>

                <div class="col-lg-6">
                  <label>Home Base : <i>{{$user->Home_Base}}</i></label>
                </div>

              </div>
            </div>

          </div>

            {{-- <a href="#" class="btn btn-primary btn-block"><b>Update</b></a> --}}
          </div>
          <!-- /.box-body -->
        </div>


        <div class="col-md-4">

          <div class="box box-success">

            <div class="box-body box-profile">

                <div class="box-header with-border">
                  <h3 class="box-title">Claim Sheets</h3>
                </div>

                <ul class="list-group list-group-unbordered">

                  @foreach ($claimsheets as $claimsheet)

                    <li class="list-group-item">
                      <a href="{{ url('/claim') }}/{{$claimsheet->Id}}/{{$claimsheet->UserId}}/true/{{$start}}/{{$end}}" target="_blank" >{{$claimsheet->Claim_Sheet_Name}}</a><br>
                    </li>

                  @endforeach

                </ul>

              </div>

          </div>
        </div>

      </div>

      <div class="row">

        <div class="box box-success">
          <br>

          {{-- <div class="col-md-6">
           <div class="input-group">
            <div class="input-group-addon">
              <i class="fa fa-clock-o"></i>
            </div>
            <input type="text" class="form-control" id="range" name="range">

           </div>
          </div> --}}

          <div class="col-sm-1">
            <div class="form-group">
             <select class="form-control select2" id="Month" name="Month" style="width: 120%;">
               <option <?php if($month == 'January') echo ' selected="selected" '; ?>>January</option>
               <option <?php if($month == 'February') echo ' selected="selected" '; ?>>February</option>
               <option <?php if($month == 'March') echo ' selected="selected" '; ?>>March</option>
               <option <?php if($month == 'April') echo ' selected="selected" '; ?>>April</option>
               <option <?php if($month == 'May') echo ' selected="selected" '; ?>>May</option>
               <option <?php if($month == 'June') echo ' selected="selected" '; ?>>June</option>
               <option <?php if($month == 'July') echo ' selected="selected" '; ?>>July</option>
               <option <?php if($month == 'August') echo ' selected="selected" '; ?>>August</option>
               <option <?php if($month == 'September') echo ' selected="selected" '; ?>>September</option>
               <option <?php if($month == 'October') echo ' selected="selected" '; ?>>October</option>
               <option <?php if($month == 'November') echo ' selected="selected" '; ?>>November</option>
               <option <?php if($month == 'December') echo ' selected="selected" '; ?>>December</option>
             </select>
           </div>
         </div>

         <div class="col-sm-1">
           <div class="form-group">

             <select class="form-control select2" id="Year" name="Year" style="width: 100%;">
               <option <?php if($year == '2017') echo ' selected="selected" '; ?>>2017</option>
             </select>
           </div>
          </div>

          <div class="col-md-6">
            <div class="input-group">
              <button type="button" class="btn btn-success btn" data-toggle="modal" onclick="refresh();">Refresh</button>
            </div>
          </div>

        <br><br>
      </div>

      <div class="row">
        <div class="col-md-6">
          <div class="box box-primary">
            <div class="box-header with-border">
              <h3 class="box-title">Expenses Type</h3>
            </div>
            <div class="box-body">
              <canvas id="pieChart"  ></canvas>
            </div>
          </div>
        </div>

        <div class="col-md-6">
          <div class="box box-primary">

            <div class="box-body">

              <table id="all" class="display" cellspacing="0" width="100%" padding="30px" style="font-size: 13px;">
                  <thead>
                      {{-- prepare header search textbox --}}
                      <tr>

                        @foreach($chartdata as $key=>$value)

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
                    @foreach($chartdata as $type)

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

                    @foreach($total as $type)

                          <tr id="row_{{ $i }}" style="background-color:yellow">

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
      month=$('[name="Month"]').val();
      year=$('[name="Year"]').val();

      window.location.href ="{{ url("/userclaimbreakdown2") }}/{{$user->Id}}/"+month+"/"+year;

    }



  $(function () {
     //pie chart js

    var pie = document.getElementById("pieChart");
    var pieOptions = {
      events: false,
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
              ctx.fillText("RM " + dataset.data[i].toFixed(2), model.x + x, model.y + y);
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
      options: pieOptions
     });

  });

  </script>

@endsection
