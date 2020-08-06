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
      {{-- chart js --}}
      <script type="text/javascript" language="javascript" src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.4.0/Chart.bundle.min.js"></script>

      <script type="text/javascript" language="javascript" class="init">
      var unassignedusers;
      var editor;
      $(document).ready(function() {

        editor = new $.fn.dataTable.Editor( {
                ajax: {
                   "url": "{{ asset('/Include/assign.php') }}",
                   "data": {
                       "role": "{{ $role }}"
                   }
                 },

                table: "#unassignedusers",
                idSrc: "userprojects.Id",
                fields: [
                        {
                                label: "Project:",
                                name: "userprojects.ProjectId",
                                type:  'select',
                                options: [
                                    { label :"", value: "" },
                                    @foreach($projects as $project)
                                        { label :"{{$project->Project_Name}}", value: "{{$project->Id}}" },
                                    @endforeach
                                ]

                        },
                        {
                                label: "Assigned As:",
                                name: "userprojects.Assigned_As",
                                type:  'select',
                                options: [
                                  { label :"", value: "" },

                                  ]
                        },
                        {
                                label: "Start Date:",
                                name: "userprojects.Start_Date",
                                type:   'datetime',
                                def:    function () { return new Date(); },
                                format: 'DD-MMM-YYYY'

                        },
                        {
                                label: "End Date:",
                                name: "userprojects.End_Date",
                                type:   'datetime',
                                def:    function () { return new Date(); },
                                format: 'DD-MMM-YYYY'

                        }

                ]
        } );



            $('#unassignedusers').on( 'click', 'tbody td', function (e) {
                  editor.inline( this, {
                 onBlur: 'submit'
                } );
            } );

            editor.on( 'initEdit', function ( e, node, data ) {

              var ability=data.userability.Ability;

              if (ability!=null)
              {
                var split=ability.split(",");
                var arr=[];

                arr.push({
                    value: "",
                    label: ""
                });

                $.each(split, function (index) {
               arr.push({
                   value: split[index],
                   label: split[index]
               });
           });


            editor.field( 'userprojects.Assigned_As' ).update( arr );

              }

            } );

            $('#unassignedusers').on( 'click', 'a.editor_edit', function (e) {
                 e.preventDefault();

                 editor
                     .title( 'Edit record' )
                     .buttons( { "label": "Update", "fn": function () { editor.submit() } } )
                     .edit( $(this).closest('tr') );
             } );

            unassignedusers = $('#unassignedusers').DataTable( {
                ajax: {
                   "url": "{{ asset('/Include/assign.php') }}",
                   "data": {
                       "role": "{{ $role }}"
                   }
                 },
                dom: "Brtp",
                bAutoWidth: true,
                rowId:"userprojects.Id",
                aaSorting:false,
                bPaginate:false,
                columnDefs: [{ "visible": false, "targets": [1,5,6,7,8,9] },{"className": "dt-center", "targets": "_all"}],
                bScrollCollapse: true,
                scrollY: "100%",
                scrollX: "100%",
                scrollCollapse: true,
                columns: [
                  {data: null,
                    "render":""},
                  {data:'userprojects.Id', title:'Id'},
                  {data: 'users.StaffId', title: 'StaffId'},
                  {data: 'users.Name', title:'Name'},
                  {
                    data: null,
                    title: 'Assign',
                    className: "center",
                    defaultContent: '<a href="" class="editor_edit"><button type="button" class="btn btn-primary .btn-xs">Assign</button></a>'
                  },
                  {data: 'userability.Ability', title: 'Ability'},
                  {data: 'projects.Project_Name', title: 'Project_Name'},
                  {data: 'userprojects.Assigned_As', title: 'Assigned_As'},
                  {data: 'userprojects.Start_Date', title: 'Start_Date'},
                  {data: 'userprojects.End_Date', title: 'End_Date'}


                ],
                select: {
                        style:    'os',
                        selector: 'td:first-child'
                },

               buttons: [

               ],


           });

           unassignedusers.on( 'order.dt search.dt', function () {
             unassignedusers.column(0, {search:'applied', order:'applied'}).nodes().each( function (cell, i) {
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
      Unassigned Users
      <small>Resource Calendar</small>
      </h1>

      <ol class="breadcrumb">
      <li><a href="{{ url('/home') }}"><i class="fa fa-dashboard"></i>Home</a></li>
      <li><a href="#"></a>Resource Calendar</li>
      <li class="active">Unassigned Users</li>
      </ol>
    </section>

    <section class="content">

      <div class="row">
        <div class="box box-info">
          <br>

          <div class="row">
           <div class="col-md-12">
             <table id="unassignedusers" class="display" cellspacing="0" width="100%" padding="30px" style="font-size: 13px;">
                 <thead>
                     {{-- prepare header search textbox --}}
                     <tr>

                       @foreach($unassignedusers as $key=>$value)

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
                   @foreach($unassignedusers as $unassign)

                         <tr id="row_{{ $i }}" >
                           <td></td>
                             @foreach($unassign as $key=>$value)
                               <td>
                                 {{ $value }}
                               </td>
                             @endforeach
                         </tr>


                   @endforeach

               </tbody>

             </table>



           </div>
         </div>
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

<script>

</script>



@endsection
