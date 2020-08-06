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
      var table;

      $(document).ready(function() {

        editor = new $.fn.dataTable.Editor( {
               ajax: "{{ asset('/Include/projectcode.php') }}",
                table: "#projectcodetable",
                idSrc: "projectcodes.Id",
                fields: [
                        {
                               name: "projectcodes.Created_By",
                               type: "hidden"
                        },{
                               label: "Project Name:",
                               name: "projectcodes.ProjectId",
                               type:  'select',
                               options: [
                                   { label :"", value: "0" },
                                   @foreach($projects as $project)
                                       { label :"{{$project->Project_Name}}", value: "{{$project->Id}}" },
                                   @endforeach

                               ],
                        },{
                                label: "Project Code:",
                                name: "projectcodes.Project_Code"
                        },{
                                label: "SiteID:",
                                name: "projectcodes.Site_ID"
                        },{
                                label: "Site Name:",
                                name: "projectcodes.Site_Name"
                        },{
                                label: "Description:",
                                name: "projectcodes.Description",
                                type:"textarea"
                        }

                ]
        } );

        //Activate an inline edit on click of a table cell
              $('#projectcodetable').on( 'click', 'tbody td', function (e) {
                    editor.inline( this, {
                   onBlur: 'submit'
                  } );
              } );

            table=$('#projectcodetable').dataTable( {
                  ajax: {
                     "url": "{{ asset('/Include/projectcode.php') }}",
                     "data": {
                         "projectids": "{{$projectids}}",
                     }
                   },
                    columnDefs: [{ "visible": false, "targets": [1] },{"className": "dt-center", "targets": "_all"}],
                    responsive: false,
                    colReorder: false,
                    sScrollX: "100%",
                    bScrollCollapse: true,
                    bAutoWidth: true,
                    sScrollY: "100%",
                    dom: "Blrtip",
                    rowId: 'projectcodes.Id',

                    //aaSorting:false,
                    columns: [
                            { data: null,"render":"", title:"No"},
                            { data: "projectcodes.Id"},
                            { data: "projects.Project_Name", editField: "projectcodes.ProjectId",title:'Project_Name' },
                            { data: "projectcodes.Project_Code" },
                            { data: "projectcodes.Site_ID" },
                            { data: "projectcodes.Site_Name" },
                            { data: "projectcodes.Description"},
                            { data: "users.Name"}
                    ],
                    select: {
                            style:    'os',
                            selector: 'tr'
                    },
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
                                     .set( 'projectcodes.Created_By', {{ $me->UserId }} )
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

        table.api().on( 'order.dt search.dt', function () {
          table.api().column(0, {search:'applied', order:'applied'}).nodes().each( function (cell, i) {
              cell.innerHTML = i+1;
          } );
      } ).draw();

      $("thead input").keyup ( function () {

              /* Filter on the column (the index) of this element */

              if ($('#projectcodetable').length > 0)
              {

                var colnum=document.getElementById('projectcodetable').rows[0].cells.length;

                if (this.value=="[empty]")
                {

                   table.fnFilter( '^$', $("thead input").index(this)-colnum,true,false );
                }
                else if (this.value=="[nonempty]")
                {

                   table.fnFilter( '^(?=\\s*\\S).*$', $("thead input").index(this)-colnum,true,false );
                }
                else if (this.value.startsWith("!")==true && this.value.length>1)
                {

                   table.fnFilter( '^(?'+ this.value +').*', $("thead input").index(this)-colnum,true,false );
                }
                else if (this.value.startsWith("!")==false)
                {

                  table.fnFilter( this.value, $("thead input").index(this)-colnum,true,false );
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
        Project Code List
        <small>Project Management</small>
      </h1>
      <ol class="breadcrumb">
        <li><a href="#"><i class="fa fa-dashboard"></i> Home</a></li>
        <li><a href="#">Project Management</a></li>
        <li class="active">Project Code List</li>
      </ol>
    </section>

    <!-- Main content -->
    <section class="content">
      <div class="row">

        <div class="col-md-12">
            <div class="box-body">
                    <table id="projectcodetable" class="display" cellspacing="0" width="100%" padding="30px" style="font-size: 13px;">
                    <thead>
                        {{-- prepare header search textbox --}}
                          <tr class="search">
                            <td align='center'><input type='hidden' class='search_init' /></td>
                            @foreach($projectcodes as $key=>$values)
                              @if ($key==0)

                              @foreach($values as $field=>$value)

                                  <td align='center'><input type='text' class='search_init' /></td>

                              @endforeach

                              @endif

                            @endforeach
                          </tr>

                          @foreach($projectcodes as $key=>$value)

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



                  </tbody>
                    <tfoot></tfoot>
                </table>
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
      <b>Version</b> 2.0.1
    </div>
    <strong>Copyright &copy; 2014-2016 <a href="http://www.softoya.com">TrackerOnTheGo</a>.</strong> All rights
    reserved.
  </footer>

@endsection
