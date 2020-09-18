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

      .profile-user-dt-img{
        width: 80px;
        height: 100px;
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
                                  ajax: "{{ asset('/Include/salary.php') }}",
                                 table: "#usertable",
                                 idSrc: "salary.Id",
                                 fields: [
                                        {
                                              label: "Name:",
                                              name: "users.Name",
                                              type: "readonly",
                                              attr: {disabled: true}
                                        },{
                                              label: "Salary:",
                                              name: "salary.Salary"
                                        },{
                                              label: "Remarks:",
                                              name: "salary.Remarks"
                                        },{
                                              label: "Created By:",
                                              name: "us.Name",
                                              type: "readonly",
                                              attr: {disabled: true}
                                        },{
                                              label: "Adjustment Date:",
                                              name: "salary.created_at",
                                              type: "readonly",
                                              attr: {disabled: true}
                                        }

                                 ]
                         } );


                         // Activate an inline edit on click of a table cell
                               $('#usertable').on( 'click', 'tbody td:not(:first-child)', function (e) {
                                     editor.inline( this, {
                                          onBlur: 'submit'
                                   } );
                               } );


                               oTable=$('#usertable').dataTable( {
                                       ajax: {
                                          "url": "{{ asset('/Include/salary.php') }}",
                                          "type": 'POST'
                                        },

                                       columnDefs: [{"className": "dt-center", "targets": "_all"}],
                                       responsive: false,
                                       sScrollX: "100%",
                                       language : {
                                          sLoadingRecords : 'Loading data...',
                                          processing: 'Loading data...'
                                        },
                                       bAutoWidth: true,
                                       rowId: 'salary.Id',
                                       sScrollY: "100%",
                                       dom: "Bfrtip",
                                       bScrollCollapse: true,
                                       columns: [
                                                { data: null, "render":"", title:"No"},
                                                { data: "users.Name", title: "Staff Name"},
                                                { data: "salary.Salary", title: "Salary"},
                                                { data: "salary.Remarks", title: "Remarks"},
                                                { data: "us.Name", title:"Created By"},
                                                { data: "salary.created_at"},
                                       ],
                                       autoFill: {
                                          editor:  editor
                                      },
                                      // keys: {
                                      //     columns: ':not(:first-child)',
                                      //     editor:  editor
                                      // },
                                       select: true,
                                       buttons: [
                                         { extend: "remove", editor: editor },
                                       ],

                           });

                           $("thead input").keyup ( function () {

                                   /* Filter on the column (the index) of this element */

                                   if ($('#usertable').length > 0)
                                   {

                                       var colnum=document.getElementById('usertable').rows[0].cells.length;

                                       if (this.value=="[empty]")
                                       {

                                          oTable.fnFilter( '^$', this.name,true,false );
                                       }
                                       else if (this.value=="[nonempty]")
                                       {

                                          oTable.fnFilter( '^(?=\\s*\\S).*$', this.name,true,false );
                                       }
                                       else if (this.value.startsWith("!")==true && this.value.length>1)
                                       {

                                          oTable.fnFilter( '^(?'+ this.value +').*', this.name,true,false );
                                       }
                                       else if (this.value.startsWith("!")==false)
                                       {
                                           oTable.fnFilter( this.value, this.name,true,false );
                                       }
                                   }


                           } );


                            oTable.api().on( 'order.dt search.dt', function () {
                                oTable.api().column(0, {search:'applied', order:'applied'}).nodes().each( function (cell, i) {
                                    cell.innerHTML = i+1;
                                } );
                            } ).draw();

                            $.fn.dataTable.tables( {visible: true, api: true} ).columns.adjust();

                             $('a[data-toggle="tab"]').on( 'shown.bs.tab', function (e) {
                               var target = $(e.target).attr("href") // activated tab



                             } );

                             $("#maincontent").css("zoom",0.9);
                             $.fn.dataTable.tables( {visible: true, api: true} ).columns.adjust();

                       } );


                </script>

@endsection

@section('content')

  <!-- Content Wrapper. Contains page content -->
    {{-- <div class="content-wrapper">
        <!-- Content Header (Page header) -->
        <section class="content-header">
            @foreach ($users as $user)
                <p>
                    {{ $user->Name }}
                </p>
            @endforeach
        </section>
    </div> --}}

    <div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
      <h1>
        User Profile
        <small>Human Resource</small>
      </h1>
      <ol class="breadcrumb">
        <li><a href="{{ url('/home') }}"><i class="fa fa-dashboard"></i> Home</a></li>
        <li><a href="#">Human Resource</a></li>
        <li class="active">User Profile</li>
      </ol>
    </section>

    <!-- Main content -->
    <section class="content">
      <div class="row" id="maincontent">
        <div class="col-xs-12">
          <div class="box">
            <!-- /.box-header -->
            <div class="box-body">
                <table id="usertable" class="usertable" cellspacing="0" width="100%" padding="30px" style="font-size: 13px;">
                    <thead>

                      <tr class="search">

                        @foreach($salary as $key=>$values)

                          @if ($key==0)

                            <?php $i = 0; ?>


                            @foreach($values as $field=>$a)
                                @if ($i==0 || $i==3 || $i==4 || $i==5)
                                  <th align='center'><input type='hidden' class='search_init' name='{{$i}}' placemark='{{$a}}'></th>
                                @else
                                  <th align='center'><input type='text' class='search_init' name='{{$i}}'  placemark='{{$a}}'></th>
                                @endif

                                <?php $i ++; ?>
                            @endforeach


                          @endif

                        @endforeach

                      </tr>


                        <tr>
                          @foreach($salary as $key=>$value)

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
      <b>Version</b> 1.0.0
    </div>
    <strong>Copyright &copy; 2014-2016 <a href="http://www.softoya.com">TrackerOnTheGo</a>.</strong> All rights
    reserved.
  </footer>

@endsection
