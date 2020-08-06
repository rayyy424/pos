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

      table.dataTable .dt-left {
       /* max-width: 400px; */
       white-space: pre-wrap;
      /* overflow:auto;
      text-overflow:inherit; */

      /* min-width: 150px;
      max-width: 150px; */

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

      <script type="text/javascript" language="javascript" src="https://cdn.datatables.net/fixedcolumns/3.2.2/js/dataTables.fixedColumns.min.js"></script>
      <script type="text/javascript" language="javascript" src="{{ asset('/plugin/js/dataTables.editor.min.js') }}"></script>
      {{-- <script type="text/javascript" language="javascript" src="{{ asset('/plugin/examples/resources/syntax/shCore.js') }}"></script>
      <script type="text/javascript" language="javascript" src="{{ asset('/plugin/examples/resources/demo.js') }}"></script>
      <script type="text/javascript" language="javascript" src="{{ asset('/plugin/examples/resources/editor-demo.js') }}"></script> --}}

      <script type="text/javascript" language="javascript" class="init">

      var editor; // use a global for the submit and return data rendering in the examples
      var oTable;

      $(document).ready(function() {

            editor = new $.fn.dataTable.Editor( {
                  ajax: {
                     "url": "{{ asset('/Include/scopeofwork.php') }}"
                   },
                    table: "#scopeofworktable",
                    idSrc: "scopeofwork.Id",
                    fields: [
                            {
                                   name: "scopeofwork.UserId",
                                   type: "hidden"
                            },{
                                    label: "Type:",
                                    name: "scopeofwork.Type",
                                    type:  'select',
                                    options: [
                                        { label :"", value: "" },
                                        @foreach($type as $op)
                                            { label :"{{$op->Option}}", value: "{{$op->Option}}" },
                                        @endforeach
                                    ],
                            },{
                                   label: "Code:",
                                   name: "scopeofwork.Code"
                            },{
                                   label: "Scope_Of_Work:",
                                   name: "scopeofwork.Scope_Of_Work"
                            },{
                                   label: "KPI:",
                                   name: "scopeofwork.KPI",
                                   attr: {
                                     type: "number"
                                   }
                            },{
                                   label: "Incentive_1:",
                                   name: "scopeofwork.Incentive_1",
                                   attr: {
                                     type: "number"
                                   }
                            },{
                                   label: "Incentive_2:",
                                   name: "scopeofwork.Incentive_2",
                                   attr: {
                                     type: "number"
                                   }
                            },{
                                   label: "Incentive_3:",
                                   name: "scopeofwork.Incentive_3",
                                   attr: {
                                     type: "number"
                                   }
                            },{
                                   label: "Incentive_4:",
                                   name: "scopeofwork.Incentive_4",
                                   attr: {
                                     type: "number"
                                   }
                            },{
                                   label: "Incentive_5:",
                                   name: "scopeofwork.Incentive_5",
                                   attr: {
                                     type: "number"
                                   }
                            }

                    ]
            } );

            // Activate an inline edit on click of a table cell
            $('#scopeofworktable').on( 'click', 'tbody td', function (e) {
                  editor.inline( this, {
                 onBlur: 'submit'
                } );
            } );

            oTable=$('#scopeofworktable').dataTable( {
                    ajax: {
                       "url": "{{ asset('/Include/scopeofwork.php') }}"
                     },
                    columnDefs: [{ "visible": false, "targets": [1] },{"className": "dt-left", "targets": "_all"}],

                    sScrollX: "100%",
                    bScrollCollapse: true,
                    bAutoWidth: true,
                    sScrollY: "100%",

                    responsive: false,
                    colReorder: true,
                    stateSave:false,
                    dom: "Blftp",
                    iDisplayLength:10,
                    bAutoWidth: true,
                    rowId:"scopeofwork.Id",
                    order: [[ 2, "asc" ],[ 3, "asc" ]],
                    columns: [
                            { data: null, "render":"", title:"No"},
                            { data: "scopeofwork.Id", title:"Id"},

                            { data: "scopeofwork.Type",title:"Type" ,width:"200px"},

                            { data: "scopeofwork.Code",title:"Code" },
                            { data: "scopeofwork.Scope_Of_Work" , title:"Scope_Of_Work",width:"250px"},
                            { data: "scopeofwork.KPI",title:"KPI",width:"50px" },

                            { data: "scopeofwork.Incentive_1",title:"Incentive" },
                            { data: "scopeofwork.Incentive_2",title:"> 1 Days" },
                            { data: "scopeofwork.Incentive_3",title:"> 2 Days" },
                            { data: "scopeofwork.Incentive_4",title:"> 3 Days" },
                            { data: "scopeofwork.Incentive_5",title:"> 4 Days" },
                            { data: "users.Name",title:"Created_By" }
                    ],
                    autoFill: {
                       editor:  editor
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
                                     .set( 'scopeofwork.UserId', {{ $me->UserId }} )
                                     .submit();
                              },
                            },
                            { extend: "remove", editor: editor },
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

        oTable.api().on( 'order.dt search.dt', function () {
          oTable.api().column(0, {search:'applied', order:'applied'}).nodes().each( function (cell, i) {
              cell.innerHTML = i+1;
          } );
      } ).draw();

      $("thead input").keyup ( function () {

              /* Filter on the column (the index) of this element */

              if ($('#scopeofworktable').length > 0)
              {

                var colnum=document.getElementById('scopeofworktable').rows[0].cells.length;

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
          Scope of Work Management
          <small>Admin</small>
        </h1>
        <ol class="breadcrumb">
          <li><a href="{{ url('/home') }}"><i class="fa fa-dashboard"></i> Home</a></li>
        <li><a href="#">Admin</a></li>
          <li class="active">Scope of Work Management</li>
        </ol>
      </section>

    <!-- Main content -->
    <section class="content">
      <div class="row">
          <div class="col-md-12">
            <div class="box box-success">
                <div class="box-body">

                  <br><br>

                    <table id="scopeofworktable" class="display" cellspacing="0" width="100%" padding="30px" style="font-size: 13px;">
                      <thead>
                        <tr class="search">
                          <td align='center'><input type='hidden' class='search_init' /></td>
                          <!-- <td align='center'><input type='hidden' class='search_init' /></td> -->
                          @foreach($scopeofwork as $key=>$values)
                            @if ($key==0)

                            <?php $i = 1; ?>

                            @foreach($values as $field=>$value)

                                @if ($i==1)
                                  <td align='center'><input type='hidden' class='search_init' /></td>
                                @else
                                  <td align='center'><input type='text' class='search_init' /></td>
                                @endif
                                <?php $i ++; ?>
                            @endforeach

                            @endif

                          @endforeach
                        </tr>
                          <tr>
                            @foreach($scopeofwork as $key=>$value)

                              @if ($key==0)
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
                        @foreach($scopeofwork as $option)

                          <tr id="row_{{ $i }}">
                                <td></td>
                              @foreach($option as $key=>$value)
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
      <b>Version</b> 2.0.1
    </div>
    <strong>Copyright &copy; 2014-2016 <a href="http://www.softoya.com">TrackerOnTheGo</a>.</strong> All rights
    reserved.
  </footer>

@endsection
