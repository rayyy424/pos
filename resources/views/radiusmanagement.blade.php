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
      <script type="text/javascript" language="javascript" src="{{ asset('/plugin/datatables/extensions/Select2/editor.select2.js') }}"></script>

      <script type="text/javascript" language="javascript" src="{{ asset('/plugin/js/dataTables.editor.min.js') }}"></script>
      {{-- chart js --}}
      <script type="text/javascript" language="javascript" src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.4.0/Chart.bundle.min.js"></script>

      <script type="text/javascript" language="javascript" class="init">

      var editor;
      $(document).ready(function() {
                editor = new $.fn.dataTable.Editor( {
                      ajax: {
                         "url": "{{ asset('/Include/radiusmanagement.php') }}"
                       },
                        table: "#radiustable",
                        fields: [
                                {
                                        label: "Location Name :",
                                        name: "radius.Location_Name"
                                },
                                {
                                        label: "Client :",
                                        name: "radius.Client",
                                        type:  'select2',
                                        options: [
                                            { label :"", value: "" },
                                            @foreach($clients as $cl)
                                                { label :"{{$cl->Company_Name}} ({{$cl->Company_Code}})", value: "{{$cl->Company_Code}}" },
                                            @endforeach

                                        ],
                                },
                                {
                                        label: "Project :",
                                        name: "radius.ProjectId",
                                        type:  'select',
                                        options: [
                                            { label :"", value: "" },
                                            @foreach($projects as $p)
                                                { label :"{{$p->Project_Name}}", value: "{{$p->Id}}" },
                                            @endforeach

                                        ],
                                },
                                {
                                        label: "Code :",
                                        name: "radius.Code",
                                        type:  'select',
                                        options: [
                                            { label :"", value: "" },
                                            @foreach($codes as $code)
                                                { label :"{{$code->Code}} - {{$code->Scope_Of_Work}}", value: "{{$code->Code}}" },
                                            @endforeach

                                        ],
                                },
                                {
                                        label: "Area :",
                                        name: "radius.Area",
                                        type:  'select',
                                        options: [
                                            { label :"", value: "" },
                                            @foreach($area as $c)
                                                { label :"{{$c->Option}}", value: "{{$c->Option}}" },
                                            @endforeach

                                        ],
                                },
                                {
                                        label: "Latitude :",
                                        name: "radius.Latitude"
                                },
                                {
                                        label: "Longitude :",
                                        name: "radius.Longitude"
                                },
                                {
                                        label: "Start_Date :",
                                        name: "radius.Start_Date",
                                        type:   'datetime',
                                        format: 'DD-MMM-YYYY'
                                },
                                {
                                        label: "Completion_Date :",
                                        name: "radius.Completion_Date",
                                        type:   'datetime',
                                        format: 'DD-MMM-YYYY'
                                }


                        ]
                 } );

                 editor.on('open', function () {
                    $('div.DTE_Footer').css( 'text-indent', -1 );
                });


                 var radiustable = $('#radiustable').dataTable( {

                   ajax: {
                      "url": "{{ asset('/Include/radiusmanagement.php') }}"
                    },
                    dom: "lBfrtip",
                    "lengthMenu": [[10, 20, 50, 100, 500, -1], [10, 20, 50, 100,500, "All"]],
                    "iDisplayLength": 20,
                   bAutoWidth: false,
                  //  rowId:"userability.Id",
                   //aaSorting:false,
                   columnDefs: [{ "visible": false, "targets": [1] },{"className": "dt-center", "targets": "_all"}],
                   bScrollCollapse: true,
                   columns: [

                           { data: null, render:"", title:"No"},
                           { data: "radius.Id", title:"Id"},
                           { data: "radius.Client", title:"Client" },
                           { data: "projects.Project_Name", title:"Project",editField:"radius.ProjectId" },
                           { data: "radius.Code", title:"Code" },
                           { data: "radius.Location_Name", title:"Location_Name" },
                           { data: "radius.Area", title:"Area" },
                           { data: "radius.Latitude", title:"Latitude" },
                           { data: "radius.Longitude", title:"Longitude"},
                           { data: "radius.Start_Date", title:"Start_Date"},
                           { data: "radius.Completion_Date", title:"Completion_Date"}

                   ],

                   select: {
                           style:    'os',
                           selector: 'td'
                   },
                   autoFill: {
                      editor:  editor
                  },
                  buttons: [
                          // { text: 'New Row',
                          //   action: function ( e, dt, node, config ) {
                          //       editor
                          //          .create( false )
                          //          .submit();
                          //   },
                          // },
                          { extend: "create", editor: editor },
                          { extend: "edit", editor: editor },

                          { extend: "remove", editor: editor },
                             {
                                 extend: 'collection',
                                 text: 'Export',
                                 buttons: [
                                         'csv'
                                 ]
                         }
                  ],

                });

                $('#radiustable').on( 'click', 'tbody td', function (e) {
                      editor.inline( this, {
                     onBlur: 'submit'
                    } );
                } );

                radiustable.api().on( 'order.dt search.dt', function () {
                  radiustable.api().column(0, {search:'applied', order:'applied'}).nodes().each( function (cell, i) {
                      cell.innerHTML = i+1;
                  } );
              } ).draw();

              $("thead input").keyup ( function () {

                      /* Filter on the column (the index) of this element */
                      if ($('#radiustable').length > 0)
                      {

                          var colnum=document.getElementById('radiustable').rows[0].cells.length;

                          if (this.value=="[empty]")
                          {

                             radiustable.fnFilter( '^$', $("thead input").index(this)-colnum,true,false );
                          }
                          else if (this.value=="[nonempty]")
                          {

                             radiustable.fnFilter( '^(?=\\s*\\S).*$', $("thead input").index(this)-colnum,true,false );
                          }
                          else if (this.value.startsWith("!")==true && this.value.length>1)
                          {

                             radiustable.fnFilter( '^(?'+ this.value +').*', $("thead input").index(this)-colnum,true,false );
                          }
                          else if (this.value.startsWith("!")==false)
                          {

                            radiustable.fnFilter( this.value, $("thead input").index(this)-colnum,true,false );
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
      Radius Management
      <small>Admin</small>
      </h1>

      <ol class="breadcrumb">
      <li><a href="{{ url('/home') }}"><i class="fa fa-dashboard"></i> Home</a></li>
      <li><a href="#">Admin</a></li>
      <li class="active">Radius Management</li>
      </ol>
    </section>

    <section class="content">
      <div class="row">
        <div class="col-md-12">
          <div class="box">
            <div class="box-body">

              <table id="radiustable" class="radiustable" cellspacing="0" width="100%" padding="30px" style="font-size: 13px;">
                  <thead>
                    <tr class="search">


                      @foreach($radius as $key=>$values)
                        @if ($key==0)

                          <td align='center'><input type='hidden' class='search_init' /></td>

                        @foreach($values as $field=>$value)

                            <td align='center'><input type='text' class='search_init' /></td>

                        @endforeach

                        @endif

                      @endforeach
                    </tr>
                      <tr>
                        @foreach($radius as $key=>$value)

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
                    @foreach($radius as $view)

                      <tr id="row_{{ $i }}">
                          <td></td>
                          @foreach($view as $key=>$value)
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
