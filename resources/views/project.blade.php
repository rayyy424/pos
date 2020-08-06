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
               ajax: "{{ asset('/Include/project.php') }}",
                table: "#projecttable",
                idSrc: "projects.Id",
                formOptions: {
                     bubble: {
                         submit: 'allIfChanged'
                     }
                 },
                fields: [
                        {
                               name: "projects.Created_By",
                               type: "hidden"
                        },{
                                 label: "Project Name:",
                                 name: "projects.Project_Name"
                        },{
                                 label: "Country:",
                                 name: "projects.Country",
                                 type: "select",
                                 options: [
                                     { label :"", value: "" },
                                     @foreach($options as $option)
                                       @if ($option->Field=="Country")
                                         { label :"{{$option->Option}}", value: "{{$option->Option}}" },
                                       @endif
                                     @endforeach
                                 ],
                        },{
                                 label: "Customer:",
                                 name: "projects.Customer",
                                 type: "select",
                                 options: [
                                     { label :"", value: "" },
                                     @foreach($options as $option)
                                       @if ($option->Field=="Customer")
                                         { label :"{{$option->Option}}", value: "{{$option->Option}}" },
                                       @endif
                                     @endforeach
                                 ],
                        },{
                                 label: "Operator:",
                                 name: "projects.Operator",
                                 type: "select",
                                 options: [
                                     { label :"", value: "" },
                                     @foreach($options as $option)
                                       @if ($option->Field=="Operator")
                                         { label :"{{$option->Option}}", value: "{{$option->Option}}" },
                                       @endif
                                     @endforeach
                                 ],
                        },{
                                 label: "Region:",
                                 name: "projects.Region",
                                 type: "select",
                                 options: [
                                     { label :"", value: "" },
                                     @foreach($options as $option)
                                       @if ($option->Field=="Region")
                                         { label :"{{$option->Option}}", value: "{{$option->Option}}" },
                                       @endif
                                     @endforeach
                                 ],
                        },{
                                 label: "Type:",
                                 name: "projects.Type",
                                 type: "select",
                                 options: [
                                     { label :"", value: "" },
                                     @foreach($options as $option)
                                       @if ($option->Field=="Type")
                                         { label :"{{$option->Option}}", value: "{{$option->Option}}" },
                                       @endif
                                     @endforeach
                                 ],
                        },{
                                 label: "Scope:",
                                 name: "projects.Scope",
                                 type: "select",
                                 options: [
                                     { label :"", value: "" },
                                     @foreach($options as $option)
                                       @if ($option->Field=="Scope")
                                         { label :"{{$option->Option}}", value: "{{$option->Option}}" },
                                       @endif
                                     @endforeach
                                 ],
                        },{
                                label: "HOD:",
                                name: "projects.Project_Manager",
                                type:  "select",
                                options: [
                                    { label: "", value: "" },
                                    @foreach ($projectmanagers as $projectmanager)

                                      { label: "{{ $projectmanager->Name }}", value: "{{ $projectmanager->Id }}" },

                                    @endforeach
                                ],
                        },{
                                label: "Project Description:",
                                name: "projects.Project_Description",
                                type: "textarea"
                        },{
                                label: "Remarks:",
                                name: "projects.Remarks",
                                type: "textarea"
                        },{
                                label: "Active:",
                                name: "projects.Active",
                                type:  'select',
                                options: [
                                  { label :"Yes", value: "1" },
                                  { label :"No", value: "0" }
                                ],
                        }

                ]
        } );

        //Activate an inline edit on click of a table cell
              $('#projecttable').on( 'click', 'tbody td', function (e) {
                    editor.inline( this, {
                   onBlur: 'submit',
                   submit: 'allIfChanged'
                  } );
              } );

             var projects= $('#projecttable').dataTable( {
                    ajax: "{{ asset('/Include/project.php') }}",
                    columnDefs: [{ "visible": false, "targets": [1] },{"className": "dt-center", "targets": "_all"}],
                    fnRowCallback: function (nRow, aData, iDisplayIndex, iDisplayIndexFull) {

                      if(aData.projects.Active=="0")
                      {
                         $('td', nRow).closest('tr').css('color', 'red');
                      }

                      else
                      {
                         $('td', nRow).closest('tr').css('color', 'black');
                      }

                     return nRow;
                   },
                    responsive: false,
                    colReorder: false,
                    bAutoWidth: true,
                    dom: "Blfrtp",
                    scrollY: "100%",
                    scrollX: "100%",
                    scrollCollapse: true,
                    bAutoWidth: true,
                    iDisplayLength:10,
                    rowId: 'projects.Id',
                    columns: [
                            { data: null, "render":"", title:"No"},
                            { data: "projects.Id"},
                            { data: "projects.Project_Name" },
                            { data: "projects.Country" },
                            { data: "projects.Customer" },
                            { data: "projects.Operator" },
                            { data: "projects.Region" },
                            { data: "projects.Type" },
                            { data: "projects.Scope" },
                            { data: "users.Name", editField: "projects.Project_Manager",title:"HOD" },
                            { data: "projects.Project_Description"},
                            { data: "projects.Remarks"},
                            { data: "projects.Active",
                            "render": function ( data, type, full, meta ) {
                                if (full.projects.Active==1)
                                   return 'Yes';
                                else
                                   return 'No';
                                endif

                            }}
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
                            {
                              text: 'New Row',
                              action: function ( e, dt, node, config ) {
                                  // clearing all select/input options
                                  editor
                                     .create( false )
                                     .set( 'projects.Created_By', {{ $me->UserId }} )
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

        projects.api().on( 'order.dt search.dt', function () {
          projects.api().column(0, {search:'applied', order:'applied'}).nodes().each( function (cell, i) {
              cell.innerHTML = i+1;
          } );
      } ).draw();

      $("thead input").keyup ( function () {

              /* Filter on the column (the index) of this element */

              if ($('#projecttable').length > 0)
              {

                var colnum=document.getElementById('projecttable').rows[0].cells.length;

                if (this.value=="[empty]")
                {

                   projects.fnFilter( '^$', $("thead input").index(this)-colnum,true,false );
                }
                else if (this.value=="[nonempty]")
                {

                   projects.fnFilter( '^(?=\\s*\\S).*$', $("thead input").index(this)-colnum,true,false );
                }
                else if (this.value.startsWith("!")==true && this.value.length>1)
                {

                   projects.fnFilter( '^(?'+ this.value +').*', $("thead input").index(this)-colnum,true,false );
                }
                else if (this.value.startsWith("!")==false)
                {

                  projects.fnFilter( this.value, $("thead input").index(this)-colnum,true,false );
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
        Project
        <small>Project Management</small>
      </h1>
      <ol class="breadcrumb">
       <li><a href="{{ url('/home') }}"><i class="fa fa-dashboard"></i> Home</a></li>
       <li><a href="#">Admin</a></li>
       <li><a href="#">Project Management</a></li>
       <li class="active">Project List</li>
      </ol>
    </section>

    <!-- Main content -->
    <section class="content">
      <div class="row">

        <div class="col-md-12">
            <div class="box-body">
                    <table id="projecttable" class="display" cellspacing="0" width="100%" padding="30px" style="font-size: 13px;">
                    <thead>
                      <tr class="search">
                        <td align='center'><input type='hidden' class='search_init' /></td>
                        @foreach($projects as $key=>$values)
                          @if ($key==0)

                          @foreach($values as $field=>$value)

                              <td align='center'><input type='text' class='search_init' /></td>

                          @endforeach

                          @endif

                        @endforeach
                      </tr>

                        <tr>
                          @foreach($projects as $key=>$value)

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
                      @foreach($projects as $project)

                        <tr id="row_{{ $i }}">
                          <td></td>
                            @foreach($project as $key=>$value)
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
