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

      var editor;

      $(document).ready(function() {

        editor = new $.fn.dataTable.Editor( {
               ajax: "{{ asset('/Include/servicecontact.php') }}",
                table: "#servicetable",
                idSrc: "servicecontact.Id",
                fields: [
                        {
                                label: "Company Name:",
                                name: "servicecontact.Company"
                        },{
                                label: "Services:",
                                name: "servicecontact.Services"
                        },{
                                label: "Contact Person:",
                                name: "servicecontact.Contact_Person"
                        },{
                                label: "Contact No:",
                                name: "servicecontact.Contact_No"
                        }

                ]
        } );

        //Activate an inline edit on click of a table cell
              // $('#servicetable').on( 'click', 'tbody td', function (e) {
              //       editor.inline( this, {
              //      onBlur: 'submit'
              //     } );
              // } );

             var servicetable= $('#servicetable').dataTable( {
                    ajax: "{{ asset('/Include/servicecontact.php') }}",
                    columnDefs: [{ "visible": false, "targets": [1] },{"className": "dt-center", "targets": "_all"}],
                    responsive: false,
                    colReorder: false,
                    bAutoWidth: true,
                    dom: "Blfrtp",
                    scrollY: "100%",
                    scrollX: "100%",
                    scrollCollapse: true,
                    bAutoWidth: true,
                    iDisplayLength:10,
                    rowId: 'servicecontact.Id',
                    columns: [
                            { data: null, "render":"", title:"No"},
                            { data: "servicecontact.Id"},
                            { data: "servicecontact.Company", title:"Company Name" },
                            { data: "servicecontact.Services" ,title:"Services"},
                            { data: "servicecontact.Contact_Person" , title:"Contact Person"},
                            { data: "servicecontact.Contact_No", title:"Contact No" }
                    ],
                    select: {
                            style:    'os',
                            selector: 'td:first-child'
                    },
                    autoFill: {
                       editor:  editor
                   },
                   select: true,
                    buttons: [
                            // {
                            //   text: 'New',
                            //   action: function ( e, dt, node, config ) {
                            //       // clearing all select/input options
                            //       editor
                            //          .create( false )
                            //          .submit();
                            //   },
                            // },
                            { extend: "create", text: "New", editor: editor },
                            { extend: "edit", editor: editor },

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

        servicetable.api().on( 'order.dt search.dt', function () {
          servicetable.api().column(0, {search:'applied', order:'applied'}).nodes().each( function (cell, i) {
              cell.innerHTML = i+1;
          } );
      } ).draw();

      $(".servicetable thead input").keyup ( function () {

              /* Filter on the column (the index) of this element */

              if ($('#servicetable').length > 0)
              {

                var colnum=document.getElementById('servicetable').rows[0].cells.length;

                if (this.value=="[empty]")
                {

                   servicetable.fnFilter( '^$', $("thead input").index(this)-colnum,true,false );
                }
                else if (this.value=="[nonempty]")
                {

                   servicetable.fnFilter( '^(?=\\s*\\S).*$', $("thead input").index(this)-colnum,true,false );
                }
                else if (this.value.startsWith("!")==true && this.value.length>1)
                {

                   servicetable.fnFilter( '^(?'+ this.value +').*', $("thead input").index(this)-colnum,true,false );
                }
                else if (this.value.startsWith("!")==false)
                {

                  servicetable.fnFilter( this.value, $("thead input").index(this)-colnum,true,false );
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
        Service Contact
        <small>IT Support</small>
      </h1>
      <ol class="breadcrumb">
       <li><a href="{{ url('/home') }}"><i class="fa fa-dashboard"></i> Home</a></li>
       <li><a href="#">IT Support</a></li>
       <li class="active">Service Contact</li>
      </ol>
    </section>

    <!-- Main content -->
    <section class="content">
      <div class="row">

        <div class="col-md-12">
          <div class="box">
            <div class="box-body">
                    <table id="servicetable" class="servicetable" cellspacing="0" width="100%" padding="30px" style="font-size: 13px;">
                    <thead>
                      <tr class="search">
                        <td align='center'><input type='hidden' class='search_init' /></td>
                        @foreach($servicecontact as $key=>$values)
                          @if ($key==0)

                          @foreach($values as $field=>$value)

                              <td align='center'><input type='text' class='search_init' /></td>

                          @endforeach

                          @endif

                        @endforeach
                      </tr>

                        <tr>
                          @foreach($servicecontact as $key=>$value)

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
                      @foreach($servicecontact as $service)

                        <tr id="row_{{ $i }}">
                          <td></td>
                            @foreach($service as $key=>$value)
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

          <!-- /.nav-tabs-custom -->
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
      <b>Version</b> 1.0.0
    </div>
    <strong>Copyright &copy; 2014-2016 <a href="http://www.softoya.com">TrackerOnTheGo</a>.</strong> All rights
    reserved.
  </footer>

@endsection
