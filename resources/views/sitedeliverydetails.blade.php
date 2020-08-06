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

    .red{
      color:red;
      font-size: 12px;
    }

    .photobox{
      width: 300px;
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

  <script type="text/javascript" language="javascript" class="init">

    $(document).ready(function() {
      deliverysummary = $('#deliverysummarytable').dataTable( {
        columnDefs: [{ 
          "visible": false, 
          "targets": [2] 
        },
        {
          "className": "dt-center", "targets": "_all"
        }],
        colReorder: false,
        sScrollX: "100%",
        bAutoWidth: true,
        sScrollY: "100%",
        iDisplayLength:10,
        bScrollCollapse: true,
        dom: "lBfrtip",
        columns: [
          { data: null, render:"", title:"No"},
          { title:'Action', 
            "render": function ( data, type, full, meta ) {
              return '<a href="{{ URL::to('/deliverydetails2')}}/'+full.deliveryform.Id+'" target="_blank">Preview</a>';
            }
          },
          { data: 'deliveryform.Id', title:'ID'},
          { data:'deliveryform.DO_No', title:'DO No'},
          { data:'projects.Project_Name', title:'Project Name'},
          { data:'deliveryform.delivery_date', title:'Delivery Date'},
          { data: 'companies.Company_Name', title:'Company Name'},
          { data:'deliveryform.Purpose', title:'Purpose'},
          { data:'deliverystatuses.delivery_status', title:'Status'},
          { data:'deliverystatuses.delivery_status_delivery', title:'Activity'},
        ],
        autoFill: {
      },
      buttons: [{
        extend: 'collection',
        text: 'Export',
        buttons: [
          'excel','csv','pdf'
        ]
      }],
    });

    deliverysummary.api().on( 'order.dt search.dt', function () {
        deliverysummary.api().column(0, {search:'applied', order:'applied'}).nodes().each( function (cell, i) {
            cell.innerHTML = i+1;
        } );
    } ).draw();

  } );
  </script>
@endsection

@section('content')

  <div class="content-wrapper">

    <!-- Content Header (Page header) -->
    <section class="content-header">
      <h1>
        Site Delivery Details
        <small>Delivery Management</small>
      </h1>
      <ol class="breadcrumb">
        <li><a href="{{ url('/home') }}"><i class="fa fa-dashboard"></i> Home</a></li>
        <li><a href="#">Delivery Management</a></li>
        <li class="active">Site Delivery Details</li>
      </ol>
    </section>

    <!-- Main content -->
    <section class="content">
      <br>
      <div class="row">
        <div class="col-md-12">
          <div class="box box-primary">
            <div class="box-header with-border">
              <table id="deliverysummarytable" class="deliverysummarytable" cellspacing="0" width="100%" padding="30px" style="font-size: 13px;">
                <thead>
                  @if($summary)
                  <tr class="search">
                    @foreach($summary as $key=>$value)
                      @if ($key==0)
                      <?php $i = 0; ?>

                        @foreach($value as $field=>$a)
                          @if ($i==0 || $i==1)
                          <th align='center'><input type='hidden' class='search_init' name='{{$i}}' placemark='{{$a}}'></th>
                          @else
                          <th align='center'><input type='text' class='search_init' name='{{$i}}'  placemark='{{$a}}'></th>
                          @endif
                          <?php $i ++; ?>
                        @endforeach
                      <th align='center'><input type='text' class='search_init' name='{{$i}}'  placemark='{{$a}}'></th>
                      <th align='center'><input type='text' class='search_init' name='{{$i}}' placemark='{{$a}}'></th>
                      @endif
                    @endforeach
                  </tr>
                  @endif

                  {{-- prepare header search textbox --}}
                  <tr>
                    @foreach($summary as $key=>$value)

                      @if ($key==0)
                      <td></td>
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
                    @foreach($summary as $s)

                    <tr id="row_{{ $i }}">
                      <td></td>
                      <td></td>

                      @foreach($s as $key=>$value)
                      <td>{{ $value }}</td>
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
    </section>

  </div>
  <!-- /.content-wrapper -->
  
  <footer class="main-footer">
      <div class="pull-right hidden-xs">
        <b>Version</b> 2.0.1
      </div>
      <strong>Copyright &copy; 2014-2016 <a href="http://www.softoya.com">TrackerOnTheGo</a>.</strong> All rights reserved.
  </footer>
@endsection