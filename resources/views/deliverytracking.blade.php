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
  <script type="text/javascript" language="javascript" src="{{ asset('/plugin/datatables/extensions/Autocomplete/editor.autoComplete.js') }}"></script>
  <script type="text/javascript" language="javascript" src="{{ asset('/plugin/datatables/extensions/Select2/editor.select2.js') }}"></script>

  {{-- chart js --}}
  <script type="text/javascript" language="javascript" src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.4.0/Chart.bundle.min.js"></script>

  <script type="text/javascript" language="javascript" class="init">

    $(document).ready(function() {
      deliverytracking = $('#deliverytrackingtable').dataTable( {
        dom: "lrftip",
        bAutoWidth: true,
        aaSorting:false,
        sScrollY: "100%",
        sScrollX: "100%",
        columnDefs: [{ "visible": false, "targets": [1] },{"className": "dt-center", "targets": "_all"}],
        bScrollCollapse: true,
        columns: [
          { data: null, title:"No" },
          { data: "deliveryform.Id", title:"Id" },
          { data: "deliveryform.DO_No", title:"DO No" },
          { data: "deliveryform.delivery_date", title:"Delivery Date" },
          { data: "deliveryform.delivery_time", title:"Delivery Time" },
          { data: "radius.Location_Name", title:"Location" },
          { data: "deliverystatuses.delivery_status", title:"Status" },
          { data: "deliverystatuses.delivery_status_details", title:"Activity" },
          { data: null, title:"Action",
            render: function (data, type, full, meta) {
              return '<a href="{{ URL::to('/deliverytrackingdetails')}}/'+full.deliveryform.Id+'" target="_blank">View</a>';
            } 
          },
        ],
      });

      deliverytracking.api().on( 'order.dt search.dt', function () {
        deliverytracking.api().column(0, {search:'applied', order:'applied'}).nodes().each(function (cell, i) {
        cell.innerHTML = i+1;
        });
      }).draw();

    });
  </script>
@endsection

@section('content')

<div class="content-wrapper">
  <!-- Content Header (Page header) -->
  <section class="content-header">
    <h1>Delivery Tracking
      <small>Delivery Management</small>
    </h1>

    <ol class="breadcrumb">
      <li><a href="{{ url('/home') }}"><i class="fa fa-dashboard"></i> Home</a></li>
      <li><a href="#">Delivery Management</a></li>
      <li class="active">Delivery Tracking</li>
    </ol>
  </section>

  <section class="content">

    <div class="row">
      <div class="col-md-12">
        <div class="box box-primary">
          <div class="box-header with-border">
            <form class="form-inline" action="" method="GET">
              <div class="form-group">
                <label>DO Number: &nbsp</label>
                <input type="text" name="do_no" class="form-control" id="do_no">
                <button type="submit" class="btn btn-default">Search</button>
              </div>
            </form>
            <br>

            <table id="deliverytrackingtable" class="deliverytrackingtable" cellspacing="0" width="100%" padding="30px" style="font-size: 13px;">
              <thead>

                @if($delivery)
                  <tr class="search">
                    @foreach($delivery as $key=>$value)
                      @if ($key==0)
                        <?php $i = 0; ?>
                        
                        @foreach($value as $field=>$a)
                          @if ($i==0)
                            <td align='center'><input type='hidden' class='search_init' name='{{$i}}' placemark='{{$a}}'></td>
                          @else
                            <td align='center'><input type='text' class='search_init' name='{{$i}}'  placemark='{{$a}}'></td>
                          @endif
                        <?php $i ++; ?>
                        @endforeach

                        <td align='center'><input type='text' class='search_init' name='{{$i}}'  placemark='{{$a}}'></td>
                        <td align='center'><input type='hidden' class='search_init' name='{{$i}}'  placemark='{{$a}}'></td>
                      @endif
                    @endforeach
                  </tr>
                @endif

                @foreach($delivery as $key=>$value)
                  @if ($key==0)
                    <td></td>
                    <!-- <td></td> -->
                      @foreach($value as $field=>$value)
                        <td/>{{ $field }}</td>
                      @endforeach
                
                    <td></td>
                  @endif
                @endforeach
              </thead>
              <tbody>
                <?php $i = 0; ?>
                @foreach($delivery as $deliverytrackings)
                  <tr id="row_{{ $i }}" >
                    <td></td>
                    <!-- <td></td> -->
  
                      @foreach($deliverytrackings as $key=>$value)
                        <td>{{ $value }}</td>
                      @endforeach
                    
                    <td></td>
                  </tr>
  
                <?php $i++; ?>
                @endforeach
              </tbody>
            </table>
          </div>
        </div>
      </div>
  </section>
</div>

<footer class="main-footer">
  <div class="pull-right hidden-xs">
    <b>Version</b> 1.0.0
  </div>
  <strong>Copyright &copy; 2014-2016 <a href="http://www.softoya.com">TrackerOnTheGo</a>.</strong> All rights reserved.
</footer>

<script>
  function search()
    {
      do_no=$('do_no').val();

      window.location.href ="{{ url("/deliverytracking") }}/"+do_no;

    }
</script>
@endsection

