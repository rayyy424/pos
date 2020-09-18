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

            editor = new $.fn.dataTable.Editor( {
                  ajax: {
                     "url": "{{ asset('/Include/holidayterritories.php') }}"
                   },
                    table: "#holidaytable",
                    idSrc: "holidayterritories.Id",
                    fields: [
                            {
                                    label: "Holiday:",
                                    name: "holidayterritories.Name"
                            },
                            {
                                    label: "Holiday:",
                                    name: "holidayterritories.Description"
                            },
                            {
                                    label: "Holiday:",
                                    name: "holidayterritories.Remark"
                            }

                    ]
            } );

            // Activate an inline edit on click of a table cell
            $('#holidaytable').on( 'click', 'tbody td', function (e) {
                  editor.inline( this, {
                 onBlur: 'submit'
                } );
            } );

            oTable=$('#holidaytable').dataTable( {
                    ajax: {
                       "url": "{{ asset('/Include/holidayterritories.php') }}"
                     },
                    columnDefs: [{ "visible": false, "targets": [2,-1,-2] },{"className": "dt-center", "targets": "_all"}],
                    responsive: false,
                    colReorder: true,
                    stateSave:false,
                    dom: "Blfrti",
                    bPaginate: false,
                    bAutoWidth: true,
                    rowId:"holidayterritories.Id",
                    aaSorting: [[3,"asc"]],
                    columns: [
                            { data:null, render:"", title: "No"},
                            { data:null,
                              render:function ( data, type, full, meta ) {
                                return '<a href="{{ url('holidaymanagement/territory') }}/'+ full.holidayterritories.Id +'/{{ date('Y') }}" class="btn btn-xs btn-danger">Manage</a>';
                              },
                              title: "Action"
                            },
                            { data: "holidayterritories.Id"},
                            { data: "holidayterritories.Name" },
                            { data: "holidayterritories.Description" },
                            { data: "holidayterritories.Remark" },
                            { data: "holidayterritories.created_at" },
                            { data: "holidayterritories.updated_at" }
                    ],
                    autoFill: {
                       editor:  editor,
                         columns: [ 2, 3, 4, 5,6 ]
                   },
                  //  keys: {
                  //      columns: ':not(:first-child)',
                  //      editor:  editor
                  //  },
                   select: true,
                    buttons: [
                            {
                              text: 'New Row',
                              action: function ( e, dt, node, config ) {
                                  // clearing all select/input options
                                  editor
                                     .create( false )
                                     .submit();
                              },
                            },
                            { extend: "remove", editor: editor },
                    ],

        });

        //display number in first column
        oTable.api().on( 'order.dt search.dt', function () {
            oTable.api().column(0, {search:'applied', order:'applied'}).nodes().each( function (cell, i) {
                cell.innerHTML = i+1;
            } );
        } ).draw();



        $("thead input").keyup ( function () {

                /* Filter on the column (the index) of this element */

                if ($('#holidaytable').length > 0)
                {

                  var colnum=document.getElementById('holidaytable').rows[0].cells.length;

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
          Holiday Management
          <small>Human Resource</small>
        </h1>
        <ol class="breadcrumb">
         <li><a href="{{ url('/home') }}"><i class="fa fa-dashboard"></i> Home</a></li>
         <li><a href="#">Human Resource</a></li>
         <li class="active">Holiday Management</li>
        </ol>
      </section>

    <!-- Main content -->
    <section class="content">
      <div class="row">
          <div class="col-md-12">
            <div class="box box-success">
                <div class="box-body">

                    <table id="holidaytable" class="display" cellspacing="0" width="100%" padding="30px" style="font-size: 13px;">
                      <thead>
                        <tr class="search">
                          <td align='center'><input type='hidden' class='search_init' /></td>
                          <td align='center'><input type='hidden' class='search_init' /></td>
                          @foreach($territories as $key=>$values)
                            @if ($key==0)

                            @foreach($values as $field=>$value)

                                <td align='center'><input type='text' class='search_init' /></td>

                            @endforeach

                            @endif



                          @endforeach
                        </tr>
                          <tr>
                            @foreach($territories as $key=>$value)

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
                        @foreach($territories as $territory)

                          <tr id="row_{{ $i }}">
                              <td></td>
                              <td></td>
                              @foreach($territory as $key=>$value)
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
          </div>
      </div>
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

@endsection
