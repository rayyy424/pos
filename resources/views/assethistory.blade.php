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

      .dtr-data:before {
        display:inline-block;
        content: "\200D";
      }

      .dtr-data {
        display:inline-block;
        min-width: 100px;
      }

      .action {
        width:100%;
        text-align: left;
      }

      .historytable{
        text-align: center;
      }

      .historyheader{
        background-color: gray;
      }
      .btn{
        width:200px;
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

      <script type="text/javascript" language="javascript" src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.8.4/moment.min.js"></script>
      <script type="text/javascript" language="javascript" src="https://cdn.datatables.net/plug-ins/1.10.15/sorting/datetime-moment.js"></script>


      <script type="text/javascript" language="javascript" src="{{ asset('/plugin/js/dataTables.editor.min.js') }}"></script>
      <script type="text/javascript" language="javascript" src="{{ asset('/plugin/datatables/extensions/Select2/editor.select2.js') }}"></script>


      <script type="text/javascript" language="javascript" class="init">

      $(document).ready(function() {

        $.fn.dataTable.moment( 'DD-MMM-YYYY' );


                          var history=$('#history').dataTable( {
                                   columnDefs: [{ "visible": false, "targets": [] },{"className": "dt-center", "targets": "_all"}],
                                   colReorder: false,
                                   sScrollX: "100%",
                                   bAutoWidth: true,
                                   sScrollY: "100%",
                                   iDisplayLength:20,
                                   bScrollCollapse: true,
                                   dom: "lBfrtip",
                                   aaSorting: [[0,"DESC"]],
                                   columns: [
                                     { data: "Date",title: "Date"},
                                      { data: "Type",title: "Category"},
                                      { data: "Rental_End_Date",title: "Type"},
                                      { data: "Name",title: "Holder"},
                                      { data: "Label",title: "Label"},
                                      { data: "Serial_No",title: "Serial_No"},
                                      { data: "Status",title: "Status"},


                                   ],
                                  select: true,
                                   buttons: [
                                      {
                                          text: 'Export',
                                          extend: 'excelHtml5'
                                      },
                                   ],

                       });

                       $("thead input").keyup ( function () {

                               /* Filter on the column (the index) of this element */

                               if ($('#history').length > 0)
                               {

                                   var colnum=document.getElementById('history').rows[0].cells.length;

                                   if (this.value=="[empty]")
                                   {

                                      history.fnFilter( '^$', this.name,true,false );
                                   }
                                   else if (this.value=="[nonempty]")
                                   {

                                      history.fnFilter( '^(?=\\s*\\S).*$', this.name,true,false );
                                   }
                                   else if (this.value.startsWith("!")==true && this.value.length>1)
                                   {

                                      history.fnFilter( '^(?'+ this.value +').*', this.name,true,false );
                                   }
                                   else if (this.value.startsWith("!")==false)
                                   {
                                       history.fnFilter( this.value, this.name,true,false );
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
        Asset History
        <small>Administration</small>
      </h1>
      <ol class="breadcrumb">
        <li><a href="{{ url('/home') }}"><i class="fa fa-dashboard"></i> Home</a></li>
        <li><a href="#">Administration</a></li>
        <li class="active">Asset History</li>
      </ol>
    </section>

    <!-- Main content -->
    <section class="content">


      <div class="row">
        <div class="col-md-12">

                  <table id="history" class="history" cellspacing="0" width="100%" padding="30px" style="font-size: 13px;">
                      <thead>

                        <tr class="search">

                          @foreach($history as $key=>$values)

                            @if ($key==0)

                              <?php $i = 0; ?>

                              @foreach($values as $field=>$a)

                                    <th align='center'><input type='text' class='search_init' name='{{$i}}'  placemark='{{$a}}'></th>

                                  <?php $i ++; ?>
                              @endforeach


                            @endif

                          @endforeach

                        </tr>

                          <tr>
                            @foreach($history as $key=>$value)

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
                        @foreach($history as $item)

                              <tr id="row_{{ $i }}" >
                                  @foreach($item as $key=>$value)
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

      <!-- /.row -->
    </section>
    <!-- /.content -->
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
