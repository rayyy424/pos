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

    <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/fixedcolumns/3.2.2/css/fixedColumns.dataTables.min.css">

  <link data-jsfiddle="common" rel="stylesheet" media="screen" href="{{ asset('/plugin/handsontable/dist/handsontable.full.min.css') }}">
  <link data-jsfiddle="common" rel="stylesheet" media="screen" href="{{ asset('/plugin/handsontable/dist/pikaday/pikaday.css') }}">

  <link rel="stylesheet" type="text/css" href="{{ asset('/plugin/css/editor.dataTables.min.css') }}">
  <link rel="stylesheet" type="text/css" href="{{ asset('/plugin/ReorderDiv/CSS/jquery-ui.css') }}">

  <script data-jsfiddle="common" src="{{ asset('/plugin/handsontable/dist/pikaday/pikaday.js') }}"></script>
  <script data-jsfiddle="common" src="{{ asset('/plugin/handsontable/dist/moment/moment.js') }}"></script>
  <script data-jsfiddle="common" src="{{ asset('/plugin/handsontable/dist/numbro/numbro.js') }}"></script>
  <script data-jsfiddle="common" src="{{ asset('/plugin/handsontable/dist/handsontable.full.min.js') }}"></script>
  <script data-jsfiddle="common" src="{{ asset('/plugin/handsontable/dist/select2-editor.js') }}"></script>
  <script data-jsfiddle="common" src="{{ asset('/plugin/handsontable/dist/customrenderer.js') }}"></script>
  <script data-jsfiddle="common" src="{{ asset('/plugin/jscolor/jscolor2.js') }}"></script>

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

  <script type="text/javascript" language="javascript" src="https://cdn.datatables.net/fixedcolumns/3.2.2/js/dataTables.fixedColumns.min.js"></script>
  <script type="text/javascript" language="javascript" src="{{ asset('/plugin/js/dataTables.editor.min.js') }}"></script>

  <script type="text/javascript" language="javascript" src="{{ asset('/plugin/datatables/extensions/Autocomplete/editor.autoComplete.js') }}"></script>

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

    table.dataTable tbody th,table.dataTable tbody td
    {
        white-space: nowrap;
    }
</style>
<script>
    var table;
    $(function () {
        table=$("#detailTable").dataTable({
            paging:false,
            dom:'Bfti',
            buttons: [
              {
                      extend: 'collection',
                      text: 'Export',
                      buttons: [
                              'excel',
                              'csv'
                      ]
              }

            ],
        });
        table.on( 'order.dt search.dt', function () {
			table.api().column(0, {search:'applied', order:'applied'}).nodes().each( function (cell, i) {
				cell.innerHTML = i+1;
			} );
		} ).api().draw();

        $(".detailTable thead input").keyup ( function () {
            if ($('#detailTable').length > 0)
            {
                var colnum=document.getElementById('detailTable').rows[0].cells.length;
                if (this.value=="[empty]")
                {
                    table.fnFilter( '^$', this.name,true,false );
                }
                else if (this.value=="[nonempty]")
                {
                    table.fnFilter( '^(?=\\s*\\S).*$', this.name,true,false );
                }
                else if (this.value.startsWith("!")==true && this.value.length>1)
                {
                    table.fnFilter( '^(?'+ this.value +').*', this.name,true,false );
                }
                else if (this.value.startsWith("!")==false)
                {
                    table.fnFilter( this.value, this.name,true,false );
                }
            }
        });
    });
</script>
@endsection

@section('content')

  <div class="content-wrapper">
  <!-- Content Header (Page header) -->
  <section class="content-header">
    <h1>
      Manday Details
      <small>Project Management</small>
    </h1>
    <ol class="breadcrumb">
      <li><a href="{{ url('/home') }}"><i class="fa fa-dashboard"></i> Home</a></li>
      <li><a href="#">Project Management</a></li>
      <li><a href="#">Manday Details</a></li>
    </ol>
  </section>

  <!-- Main content -->
  <section class="content">
    <div class="row">



      <div id="divLoading" style="display:none;">

      </div>

            <div class="col-md-12" id="zoomdiv">

              <div class="box-body">

                  <div class="box-body">
                    <table id="detailTable" class='detailTable' cellspacing="0" width="100%" padding="30px" style="font-size: 13px;">
                        <thead>
                            <tr class="search">
                                @foreach($data as $key=>$d)
                                    @if ($key==0)
                                    <?php $i = 0; ?>
                                    @foreach($d as $field=>$v)
                                        @if ($i==0)
                                        <th align='center'><input type='hidden' class='search_init' name='{{$i}}' placemark='{{$v}}'></th>
                                        <th align='center'><input type='text' class='search_init' name='{{$i+1}}' placemark='{{$v}}'></th>

                                        @else
                                        <th align='center'><input type='text' class='search_init' name='{{$i+1}}'  placemark='{{$v}}'></th>
                                        @endif
                                        <?php $i ++; ?>
                                        @endforeach
                                    @endif
                                @endforeach
                            </tr>
                            <tr>
                                <td>No</td>
                                <td>Date</td>
                                <td>Name</td>
                                <td>Code</td>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($data as $d)
                                <tr>
                                    <td></td>
                                    <td>{{$d->Date}}</td>
                                    <td>{{$d->Name}}</td>
                                    <td>{{$d->Code}}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
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
@endsection
