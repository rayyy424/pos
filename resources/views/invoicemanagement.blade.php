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
          var asInitVals = new Array();
          var oTable;
          var userid;

          $(document).ready(function() {

                         editor = new $.fn.dataTable.Editor( {
                                  ajax: "{{ asset('/Include/invoice.php') }}",
                                 table: "#invoicetable",
                                 idSrc: "invoices.Id",
                                 fields: [
                                         {
                                                label: "Invoice No:",
                                                name: "invoices.Invoice_No"
                                        },{
                                                label: "Project Name:",
                                                name: "invoices.ProjectId",
                                                type:  'select',
                                                options: [
                                                   { label :"", value: "0" },
                                                   @foreach($projects as $project)
                                                       { label :"{{$project->Project_Name}}", value: "{{$project->Id}}" },
                                                   @endforeach

                                               ],

                                       },{
                                               label: "Company:",
                                               name: "invoices.Company"
                                       },{
                                                label: "Invoice Type:",
                                                name: "invoices.Invoice_Type",
                                                type: "select",
                                                options: [
                                                    { label :"", value: "" },
                                                    @foreach($options as $option)
                                                      @if ($option->Field=="Invoice_Type")
                                                        { label :"{{$option->Option}}", value: "{{$option->Option}}" },
                                                      @endif
                                                    @endforeach
                                                ],
                                       },{
                                                 label: "Invoice Date:",
                                                 name: "invoices.Invoice_Date",
                                                 type:   'datetime',
                                                 def:    function () { return new Date(); },
                                                 format: 'DD-MMM-YYYY'
                                         },{
                                                   label: "Invoice Amount:",
                                                   name: "invoices.Invoice_Amount",
                                                   attr: {
                                                      type: "number"
                                                    }

                                           },{
                                                 label: "Invoice Description:",
                                                 name: "invoices.Invoice_Description",
                                                 type: "textarea"
                                         },{
                                                 label: "Invoice Status:",
                                                 name: "invoices.Invoice_Status",
                                                 type: "select",
                                                 options: [
                                                     { label :"", value: "" },
                                                     @foreach($options as $option)
                                                       @if ($option->Field=="Invoice_Status")
                                                         { label :"{{$option->Option}}", value: "{{$option->Option}}" },
                                                       @endif
                                                     @endforeach
                                                 ],
                                         }

                                 ]
                         } );


                         //Activate an inline edit on click of a table cell
                               $('#invoicetable').on( 'click', 'tbody td', function (e) {
                                     editor.inline( this, {
               			                onBlur: 'submit'
                                   } );
                               } );


                               oTable=$('#invoicetable').dataTable( {
                                       columnDefs: [{ "visible": false, "targets": [2] },{"className": "dt-center", "targets": "_all"}],
                                       responsive: false,
                                       colReorder: false,
                                       sScrollX: "100%",
                                       bScrollCollapse: true,
                                       bAutoWidth: true,
                                       sScrollY: "100%",
                                       dom: "Bfrtip",
                                       iDisplayLength:10,
                                       columns: [
                                               { data: null,"render":"", title:"No"},
                                               {
                                                   sortable: false,
                                                   "render": function ( data, type, full, meta ) {
                                                          return '<a href="Invoice/'+full.invoices.Id+'" >View</a>';
                                                   }
                                               },
                                               { data: "invoices.Id"},
                                               { data: "invoices.Invoice_No" },
                                               { data: "projects.Project_Name", editField: "invoices.ProjectId" },
                                               { data: "invoices.Company" },
                                               { data: "invoices.Invoice_Type" },
                                               { data: "invoices.Invoice_Date" },
                                               { data: "invoices.Invoice_Description" },
                                               { data: "invoices.Invoice_Amount",
                                               "render": function ( data, type, full, meta ) {

                                                   return parseFloat(full.invoices.Invoice_Amount).toFixed(2);
                                               } },
                                               { data: "invoices.Invoice_Status" }


                                       ],
                                       autoFill: {
                                          editor:  editor,
                                      },
                                      // keys: {
                                      //     columns: ':not(:first-child)',
                                      //     editor:  editor
                                      // },
                                       select: true,
                                       buttons: [
                                               { extend: "create", editor: editor },
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

                           $('#invoicetable').on( 'click', 'tr', function () {
                             // Get the rows id value
                            //  var row=$(this).closest("tr");
                            //  var oTable = row.closest('table').dataTable();
                             userid = oTable.api().row( this ).data().invoices.Id;
                           });

                           oTable.api().on( 'order.dt search.dt', function () {
                               oTable.api().column(0, {search:'applied', order:'applied'}).nodes().each( function (cell, i) {
                                   cell.innerHTML = i+1;
                               } );
                           } ).draw();

                             $("thead input").keyup ( function () {

                                     /* Filter on the column (the index) of this element */
                                     if ($('#invoicetable').length > 0)
                                     {

                                         var colnum=document.getElementById('invoicetable').rows[0].cells.length;

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
        Invoice Management
        <small>Sales Management</small>
      </h1>
      <ol class="breadcrumb">
        <li><a href="{{ url('/home') }}"><i class="fa fa-dashboard"></i> Home</a></li>
        <li><a href="#">Sales Management</a></li>
        <li><a href="#">Invoice Management</a></li>
      </ol>
    </section>

    <!-- Main content -->
    <section class="content">
      <div class="row">
        <div class="col-xs-12">
          <div class="box">
            <!-- /.box-header -->
            <div class="box-body">
                <table id="invoicetable" class="display" cellspacing="0" width="100%" padding="30px" style="font-size: 13px;">
				            <thead>

                      <tr class="search">
                        <td align='center'><input type='hidden' class='search_init' /></td>
                        <td align='center'><input type='hidden' class='search_init' /></td>
                        @foreach($invoices as $key=>$values)

                          @if ($key==0)

                            @foreach($values as $field=>$value)
                              @if ($field=="Web_Path")
                                <td align='center'><input type='hidden' class='search_init' /></td>
                              @else
                                <td align='center'><input type='text' class='search_init' /></td>
                              @endif

                            @endforeach

                          @endif

                        @endforeach
                      </tr>

                        <tr>
                          @foreach($invoices as $key=>$value)

                            @if ($key==0)
                                  <td></td>
                                  <td></td>
                              @foreach($value as $field=>$value)
                                <td>
                                    {{ $field }}
                                </td>
                              @endforeach

                            @endif

                          @endforeach
                        </tr>

                    </thead>
                    <tbody>

                      <?php $i = 0; ?>
                      @foreach($invoices as $invoice)

                        <tr id="row_{{ $i }}">
                            <td></td>
                            <td></td>
                            @foreach($invoice as $key=>$value)
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
            <!-- /.box-body -->
          </div>
          <!-- /.box -->
          <!-- /.box -->
        </div>
        <!-- /.col -->
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

@endsection
