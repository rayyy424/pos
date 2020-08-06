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
      div.DTE_Body div.DTE_Body_Content div.DTE_Field {
            padding: 15px;
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

      var editor;
      $(document).ready(function() {
                editor = new $.fn.dataTable.Editor( {
                      ajax: {
                         "url": "{{ asset('/Include/targetmaintenance.php') }}",
                         @if($projectid)
                         "data": {
                             "ProjectId": "{{ $projectid }}"
                         }
                         @endif
                       },
                       idSrc:"targets.Id",
                        table: "#targettable",
                        fields: [
                                {
                                        label: "Id",
                                        name: "targets.Id",

                                        type: "hidden"
                                },{
                                        name: "targets.UserId",

                                        type: "hidden"
                                },
                                {
                                        label: "Active :",
                                        name: "targets.Active",
                                        type: "select",
                                        options: [
                                          { label :"Yes", value: "1" },
                                          { label :"No", value: "0" }
                                        ]
                                },
                                {
                                        label: "Project Name:",
                                        name: "targets.ProjectId",
                                        type:  'select',
                                        options: [
                                            { label :"", value: "" },
                                            @foreach($projects as $project)
                                                { label :"{{$project->Project_Name}}", value: "{{$project->Id}}" },
                                            @endforeach
                                        ]

                                },
                                {
                                        label: "Title :",
                                        name: "targets.Title"
                                },
                                {
                                        label: "Target Field:",
                                        name: "targets.Target_Field",
                                        type:  'select',
                                        options: [
                                            { label :"", value: "" },
                                            @foreach($columns as $column)
                                                { label :"{{$column->Column_Name}}", value: "{{$column->Column_Name}}" },
                                            @endforeach

                                        ]

                                },
                                {
                                        label: "Target Date:",
                                        name: "targets.Target_Date",
                                        type:   'datetime',
                                        format: 'DD-MMM-YYYY'

                                },{
                                        label: "Target:",
                                        name: "targets.Target",
                                        attr: {
                                           type: "number"
                                         }
                                },
                                {
                                        label: "Users List:",
                                        name: "users[].Id",
                                        type:  'checkbox'

                                }

                        ]
                 } );

                 editor.on('open', function () {
                    $('div.DTE_Footer').css( 'text-indent', -1 );
                });

                editor.on( 'preSubmit', function ( e, o, action ) {
                  if ( action == 'edit' ) {
                      var ProjectId = this.field( 'targets.ProjectId' );
                      var Title = this.field( 'targets.Title' );
                      var Target_Field = this.field( 'targets.Target_Field' );
                      var Target_Date = this.field( 'targets.Target_Date' );
                      var Target = this.field( 'targets.Target' );

                      // Only validate user input values - different values indicate that
                      // the end user has not entered a value
                      if ( ProjectId.val()==="") {

                            ProjectId.error( 'Project is required!' );
                            return false;
                      }

                      if ( Title.val()==="") {

                            Title.error( 'Title is required!' );
                            return false;
                      }

                        if ( Target_Field.val()==="") {

                              Target_Field.error( 'Target_Field is required!' );
                              return false;
                        }

                        if ( Target_Date.val()==="") {

                              Target_Date.error( 'Target_Date is required!' );
                              return false;
                        }

                        if ( Target.val()==="0") {

                              Target.error( 'Target is required!' );
                              return false;
                        }

                      return true;

                  }
              } );

                 var notify = $('#targettable').dataTable( {

                   ajax: {
                      "url": "{{ asset('/Include/targetmaintenance.php') }}",
                      @if($projectid)
                      "data": {
                          "ProjectId": "{{ $projectid }}"
                      }
                      @endif
                    },
                   dom: "Bfrtp",
                   bAutoWidth: true,
                   responsive: false,
                   colReorder: false,
                   sScrollX: "100%",
                   rowId:"targets.Id",
                   sScrollY: "100%",
                   columnDefs: [{ "visible": false, "targets": [2,3] }],
                   bScrollCollapse: true,
                   columns: [
                           { title:"Action",
                           "render": function ( data, type, full, meta ) {
                              return '<a href="targetpreview/'+full.targets.Id+'" target="_blank">Preview</a>';

                           }},
                           { data: null, render:"", title:"No"},
                           { data: "targets.Id" },
                           { data: "targets.ProjectId" },
                           { data: "targets.Active",
                           "render": function ( data, type, full, meta ) {
                               if (full.targets.Active==1)
                                  return 'Yes';
                               else
                                  return 'No';
                               endif

                           }},
                           { data: "projects.Project_Name",Title:"Project_Name", editField: "targets.ProjectId"},
                           { data: "targets.Title",Title:"Title"},
                           { data: "targets.Target_Field" ,Title:"Target_Field"},
                           { data: "targets.Target_Date" ,Title:"Target_Date"},
                           { data: "targets.Target" ,Title:"Target"},
                           { data: "creator.Name",Title:"Creator" },
                           { data: "users", render: "[<br> ].Name",Title:"Subscriber"}

                   ],

                   select: {
                           style:    'os',
                           selector: 'td'
                   },
                  buttons: [
                          { text: 'New Row',
                            action: function ( e, dt, node, config ) {
                                editor
                                   .create( false )
                                   .set( 'targets.UserId', {{$me->UserId}})
                                   @if($projectid!=0)
                                     .set( 'targets.ProjectId', {{$projectid}})
                                   @endif
                                   .submit();
                            },
                          },
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

                notify.api().on( 'order.dt search.dt', function () {
                  notify.api().column(1, {search:'applied', order:'applied'}).nodes().each( function (cell, i) {
                      cell.innerHTML = i+1;
                  } );
              } ).draw();

              $("thead input").keyup ( function () {

                      /* Filter on the column (the index) of this element */
                      if ($('#targettable').length > 0)
                      {

                          var colnum=document.getElementById('targettable').rows[0].cells.length;

                          if (this.value=="[empty]")
                          {

                             notify.fnFilter( '^$', $("thead input").index(this)-colnum,true,false );
                          }
                          else if (this.value=="[nonempty]")
                          {

                             notify.fnFilter( '^(?=\\s*\\S).*$', $("thead input").index(this)-colnum,true,false );
                          }
                          else if (this.value.startsWith("!")==true && this.value.length>1)
                          {

                             notify.fnFilter( '^(?'+ this.value +').*', $("thead input").index(this)-colnum,true,false );
                          }
                          else if (this.value.startsWith("!")==false)
                          {

                            notify.fnFilter( this.value, $("thead input").index(this)-colnum,true,false );
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
      Target Rules Maintenance
      <small>Project Management</small>
      </h1>

      <ol class="breadcrumb">
      <li><a href="{{ url('/home') }}"><i class="fa fa-dashboard"></i> Home</a></li>
      <li><a href="#">Project Management</a></li>
      <li class="active">Target Rules Maintenance</li>
      </ol>
    </section>

    <section class="content">
      <div class="row">
        <div class="box box-primary">
          <div class="box-body">
            <div class="col-md-12">

              <table id="targettable" class="display" cellspacing="0" width="100%" padding="30px" style="font-size: 13px;">
                  <thead>
                    <tr class="search">
                      <td align='center'><input type='hidden' class='search_init' /></td>
                      <td align='center'><input type='hidden' class='search_init' /></td>
                      @foreach($targetrules as $key=>$values)
                        @if ($key==0)

                        @foreach($values as $field=>$value)

                            <td align='center'><input type='text' class='search_init' /></td>

                        @endforeach

                        @endif

                      @endforeach
                    </tr>
                      <tr>
                        @foreach($targetrules as $key=>$value)

                          @if ($key==0)
                            <td>Action</td>
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
