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

      <script type="text/javascript" language="javascript" class="init">

      var editor; // use a global for the submit and return data rendering in the examples
      var oTable;

      $(document).ready(function() {

            oTable=$('#reporttable').dataTable( {

                    columnDefs: [{ "visible": false, "targets": [2,3,4] },{"className": "dt-center", "targets": "_all"}],
                    responsive: false,
                    colReorder: true,
                    stateSave:false,
                    dom: "Bftp",
                    iDisplayLength:20,
                    bAutoWidth: true,
                    aaSorting:[],
                    autoFill: {
                       editor:  editor
                   },
                   columns:[
                     {data: null, render:"", title:"No"},
                     {render:"", title:"Action"},
                     {data:'tracker.Id'},
                     {data:'files.File_Name', title:'File Name'},
                     {data:'files.Web_Path', title:'File'},
                     {data:'tracker.Project_Name'},
                     {data:'tracker.Unique ID'},
                     {data:'tracker.Site_ID',title:'Site ID / Site LRD'},
                     {data:'tracker.Site_Name'},
                     {data:'Submitter'},
                     {data:'Submitted_Date'},

                   ],
                  //  keys: {
                  //      columns: ':not(:first-child)',
                  //      editor:  editor
                  //  },
                   select: true,
                    buttons: [

                    ],

        });

        oTable.api().on( 'order.dt search.dt', function () {
            oTable.api().column(0, {search:'applied', order:'applied'}).nodes().each( function (cell, i) {
                cell.innerHTML = i+1;
            } );
        } ).draw();

        $("thead input").keyup ( function () {

              /* Filter on the column (the index) of this element */
          if ($('#reporttable').length > 0)
          {

              var colnum=document.getElementById('reporttable').rows[0].cells.length;

              if (this.value=="[empty]")
              {

                 oTable.fnFilter( '^$', $("thead input").index(this)-colnum,true,false );
              }
              else if (this.value=="[nonempty]")
              {

                 oTable.fnFilter( '^(?=\\s*\\S).*$', $("thead input").index(this)-colnum,true,false );
              }
              else if (this.value.startsWith("!")==true && this.value.length>1)
              {

                 oTable.fnFilter( '^(?'+ this.value +').*', $("thead input").index(this)-colnum,true,false );
              }
              else if (this.value.startsWith("!")==false)
              {

                oTable.fnFilter( this.value, $("thead input").index(this)-colnum,true,false );
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
          Report Repository
          <small>Project Management</small>
        </h1>
        <ol class="breadcrumb">
          <li><a href="{{ url('/home') }}"><i class="fa fa-dashboard"></i> Home</a></li>
        <li><a href="#">Project Management</a></li>
          <li class="active">Report Repository</li>
        </ol>
      </section>

    <!-- Main content -->
    <section class="content">
      <div class="row">
          <div class="col-md-12">
            <div class="box box-success">
                <div class="box-body">

                  @foreach($options as $option)

                    @if ($option->Option==$type)
                      <a href="{{ url('/reportrepository') }}/{{$option->Option}}"><button type="button" class="btn btn-danger btn-lg">{{$option->Option}}</button></a>
                    @else
                      <a href="{{ url('/reportrepository') }}/{{$option->Option}}"><button type="button" class="btn btn-success btn-lg">{{$option->Option}}</button></a>
                    @endif

                  @endforeach

                  <br><br>

                    <table id="reporttable" class="display" cellspacing="0" width="100%" padding="30px" style="font-size: 13px;">
                      <thead>
                        <tr class="search">
                          <td align='center'><input type='hidden' class='search_init' /></td>
                            <td align='center'><input type='hidden' class='search_init' /></td>
                            @if($reports)
                              @foreach($reports as $key=>$value)

                              @if ($key==0)

                                @foreach($value as $field=>$value)

                                  @if ($field=="Web_Path")
                                    <td align='center'><input type='hidden' class='search_init' /></td>
                                  @else
                                    <td align='center'><input type='text' class='search_init' /></td>
                                  @endif

                                @endforeach

                              @endif

                              @endforeach
                          @endif
                        </tr>
                          <tr>
                            @if($reports)
                              @foreach($reports as $key=>$value)

                                @if ($key==0)
                                  <td></td>
                                  <td>Action</td>

                                  @foreach($value as $field=>$value)
                                      <td/>{{ $field }}</td>
                                  @endforeach

                                @endif

                              @endforeach
                            @endif
                          </tr>
                      </thead>
                      <tbody>

                        @if($reports)

                          <?php $i = 0; ?>
                          @foreach($reports as $report)

                            <tr id="row_{{ $i }}">
                              <td></td>
                              @if ($report->Web_Path)
                                  <td><a download="{{$report->File_Name}}" href="{{ url($report->Web_Path) }}">Download</a></td>
                              @else
                                  <td></td>
                              @endif

                                @foreach($report as $key=>$value)
                                  <td>
                                    {{ $value }}
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

@endsection
