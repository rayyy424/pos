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
      /*a.buttons-collection {
        margin-left: 1em;
      }
      .container1{
              width: 1200px;
              margin-left: 50px;
              padding: 10px;
      }*/

      #map{
    	height: 300px;
    	/*width:530px;*/
    	margin: 0 auto;
    }
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

      .red{
        color:red;
        font-size: 12px;
      }

      .photobox{
        width: 300px;
      }

    </style>

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

      <script>

      $(document).ready(function() {


                           timesheettable=$('#engineertable').DataTable( {
                                   columnDefs: [{ "visible": false, "targets": [] },{"className": "dt-center", "targets": "_all"}],
                                   colReorder: false,
                                   sScrollX: "100%",
                                   bAutoWidth: true,
                                   sScrollY: "100%",
                                   iDisplayLength:10,
                                   bScrollCollapse: true,
                                   dom: "lBfrtip",

                                  select: true,
                                   buttons: [
                                      {
                                          text: 'Export',
                                          extend: 'excelHtml5'
                                      },
                                   ],

                       });

                       $('#engineertable tbody').on('click', 'td', function () {


                            var data=timesheettable.row(this).data();
                            var json=JSON.stringify(data);


                            if(this.cellIndex==9)
                            {
                              myfunction("Out",data.timesheets.Latitude_Out,data.timesheets.Longitude_Out);
                            }
                            else if(this.cellIndex==8){
                              myfunction("In",data.timesheets.Latitude_In,data.timesheets.Longitude_In);

                            }

                        } );

                       $("#ajaxloader").hide();

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
        Site Visit Detail
        <small>Human Resource</small>
      </h1>
      <ol class="breadcrumb">
        <li><a href="{{ url('/home') }}"><i class="fa fa-dashboard"></i> Home</a></li>
        <li><a href="#">Human Resource</a></li>
        <li class="active">Site Visit Detail</li>
      </ol>
    </section>

    <!-- Main content -->
    <section class="content">

      <div class="row">
        <div class="col-md-12">

                  <table id="engineertable" class="display" cellspacing="0" width="100%" padding="30px" style="font-size: 13px;">
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
                                    <td>
                                      @if($key=="Leave_Type" && $value=="Annual Leave")
                                        @if($timesheet->Leave_Status=="Final Approved")
                                          {{ $value }}
                                        @else

                                        @endif
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
  $(function () {

    //Initialize Select2 Elements
    $(".select2").select2();

    //Flat red color scheme for iCheck
    $('input[type="checkbox"].flat-blue, input[type="radio"].flat-blue').iCheck({
      checkboxClass: 'icheckbox_flat-blue',
      radioClass: 'iradio_flat-blue'
    });

    //Date picker
    $('#Date').datepicker({
      autoclose: true
    });

    //Timepicker
    $(".timepicker").timepicker({
      showInputs: false
    });

  });

</script>

@endsection
