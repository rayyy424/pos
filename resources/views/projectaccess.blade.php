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

      var editor1;
      var editor2;
      var bystaff;
      var byproject;
      $(document).ready(function() {

        editor1 = new $.fn.dataTable.Editor( {
              ajax: {
                 "url": "{{ asset('/Include/projectaccess.php') }}"
               },
                table: "#bystaff",
                fields: [

                        {
                                label: "Project List:",
                                name: "projects[].Id",
                                type:  'checkbox'

                        }


                ]
        } );

        editor2 = new $.fn.dataTable.Editor( {
              ajax: {
                 "url": "{{ asset('/Include/projectaccess1.php') }}"
               },
                table: "#byproject",
                fields: [

                        {
                                label: "User List:",
                                name: "users[].Id",
                                type:  'checkbox'

                        }


                ]
        } );


                 bystaff = $('#bystaff').dataTable( {

                     ajax: {
                        "url": "{{ asset('/Include/projectaccess.php') }}"
                      },
                     dom: "Bfrtp",
                     bAutoWidth: false,
                    //  rowId:"userability.Id",
                     //aaSorting:false,
                     columnDefs: [{ "visible": false, "targets": [1] },{"className": "dt-center", "targets": [0,1,2,3]}],
                     bScrollCollapse: true,
                     columns: [
                             { data: null, "render":"", title: "No"},
                             { data: "users.Id" },
                             { data: "users.StaffId" },
                             { data: "users.Name"},
                             { data: "projects", render: "[<br> ].Project_Name" }

                     ],

                     select: {
                             style:    'os',
                             selector: 'td'
                     },
                     autoFill: {
                        editor:  editor1
                    },
                    buttons: [
                            { extend: "edit", editor: editor1 },
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

                byproject = $('#byproject').dataTable( {

                    ajax: {
                       "url": "{{ asset('/Include/projectaccess1.php') }}"
                     },
                    dom: "Bfrtp",
                    bAutoWidth: false,
                   //  rowId:"userability.Id",
                    //aaSorting:false,
                    columnDefs: [{ "visible": false, "targets": [1] },{"className": "dt-center", "targets": [0,1,2]}],
                    bScrollCollapse: true,
                    columns: [
                            { data: null, "render":"", title: "No"},
                            { data: "projects.Id" },
                            { data: "projects.Project_Name" },
                            { data: "users", render: "[<br> ].Name" }

                    ],

                    select: {
                            style:    'os',
                            selector: 'td'
                    },
                    autoFill: {
                       editor:  editor2
                   },
                   buttons: [
                           { extend: "edit", editor: editor2 },
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


                bystaff.api().on( 'order.dt search.dt', function () {
                  bystaff.api().column(0, {search:'applied', order:'applied'}).nodes().each( function (cell, i) {
                      cell.innerHTML = i+1;
                  } );
                } ).draw();

                byproject.api().on( 'order.dt search.dt', function () {
                  byproject.api().column(0, {search:'applied', order:'applied'}).nodes().each( function (cell, i) {
                      cell.innerHTML = i+1;
                  } );
                } ).draw();

              $(".bystaff thead input").keyup ( function () {

                      /* Filter on the column (the index) of this element */
                  if ($('#bystaff').length > 0)
                  {

                      var colnum=document.getElementById('bystaff').rows[0].cells.length;

                      if (this.value=="[empty]")
                      {

                         bystaff.fnFilter( '^$', $("thead input").index(this)-colnum,true,false );
                      }
                      else if (this.value=="[nonempty]")
                      {

                         bystaff.fnFilter( '^(?=\\s*\\S).*$', $("thead input").index(this)-colnum,true,false );
                      }
                      else if (this.value.startsWith("!")==true && this.value.length>1)
                      {

                         bystaff.fnFilter( '^(?'+ this.value +').*', $("thead input").index(this)-colnum,true,false );
                      }
                      else if (this.value.startsWith("!")==false)
                      {

                        bystaff.fnFilter( this.value, $("thead input").index(this)-colnum,true,false );
                      }
                  }


              } );

              $(".byproject thead input").keyup ( function () {

                      /* Filter on the column (the index) of this element */
                  if ($('#byproject').length > 0)
                  {

                      var colnum=document.getElementById('byproject').rows[0].cells.length;

                      if (this.value=="[empty]")
                      {

                         byproject.fnFilter( '^$', $("thead input").index(this)-colnum,true,false );
                      }
                      else if (this.value=="[nonempty]")
                      {

                         byproject.fnFilter( '^(?=\\s*\\S).*$', $("thead input").index(this)-colnum,true,false );
                      }
                      else if (this.value.startsWith("!")==true && this.value.length>1)
                      {

                         byproject.fnFilter( '^(?'+ this.value +').*', $("thead input").index(this)-colnum,true,false );
                      }
                      else if (this.value.startsWith("!")==false)
                      {

                        byproject.fnFilter( this.value, $("thead input").index(this)-colnum,true,false );
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
      Project Access
      <small>Admin</small>
      </h1>

      <ol class="breadcrumb">
      <li><a href="{{ url('/home') }}"><i class="fa fa-dashboard"></i> Home</a></li>
      <li><a href="#">Admin</a></li>
      <li class="active">Project Access</li>
      </ol>
    </section>

    <section class="content">
      <div class="row">
        <div class="box box-primary">
          <div class="box-body">
            <div class="col-md-12">

              <div class="nav-tabs-custom">
                 <ul class="nav nav-tabs">
                   <li class="active"><a href="#staff" data-toggle="tab" id="stafftab">Staff</a></li>
                   <li><a href="#project" data-toggle="tab" id="projecttab">Project</a></li>
                 </ul>
                 <br>

                 <div class="tab-content">
                   <div class="active tab-pane" id="staff">

                     <table id="bystaff" class="bystaff" cellspacing="0" width="100%" padding="30px" style="font-size: 13px;">
                       <thead>
                         <tr class="search">
                           <td align='center'><input type='hidden' class='search_init' /></td>
                           @foreach($bystaff as $key=>$values)
                             @if ($key==0)

                             @foreach($values as $field=>$value)

                                 <td align='center'><input type='text' class='search_init' /></td>

                             @endforeach

                             @endif

                           @endforeach
                         </tr>
                           <tr>
                             @foreach($bystaff as $key=>$value)

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




                     <tfoot></tfoot>
                   </table>

                   </div>

                   <div class="tab-pane" id="project">

                     <table id="byproject" class="byproject" cellspacing="0" width="100%" padding="30px" style="font-size: 13px;">
                       <thead>
                         <tr class="search">
                           <td align='center'><input type='hidden' class='search_init' /></td>
                           @foreach($byproject as $key=>$values)
                             @if ($key==0)

                             @foreach($values as $field=>$value)

                                 <td align='center'><input type='text' class='search_init' /></td>

                             @endforeach

                             @endif

                           @endforeach
                         </tr>
                           <tr>
                             @foreach($byproject as $key=>$value)

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




                     <tfoot></tfoot>

                   </table>

                    </div>


                 </div>

               </div>

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
