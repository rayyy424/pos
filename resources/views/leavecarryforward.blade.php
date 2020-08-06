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

      var editor; // use a global for the submit and return data rendering in the examples
      var oTable;

      $(document).ready(function() {

            editor = new $.fn.dataTable.Editor( {
                  ajax: {
                     "url": "{{ asset('/Include/leavecarryforward.php') }}",
                     "data": {
                         "year": "{{ $year }}"
                     }
                   },
                    table: "#leavetable",
                    idSrc: "leavecarryforwards.Id",
                    fields: [
                            {
                                    label: "Days:",
                                    name: "leavecarryforwards.Days",
                                    attr: {
                                      type: "number"
                                    }

                            },
                            {
                                    label: "Burnt:",
                                    name: "leavecarryforwards.Burnt",
                                    attr: {
                                      type: "number"
                                    }

                            }

                    ]
            } );

            // Activate an inline edit on click of a table cell
            $('#leavetable').on( 'click', 'tbody td', function (e) {
                  editor.inline( this, {
                 onBlur: 'submit'
                } );
            } );

            oTable=$('#leavetable').dataTable( {
                    ajax: {
                       "url": "{{ asset('/Include/leavecarryforward.php') }}",
                       "data": {
                           "year": "{{ $year }}"
                       }
                     },
                    columnDefs: [{ "visible": false, "targets": [1,2] },{"className": "dt-center", "targets": "_all"}],
                    responsive: false,
                    colReorder: true,
                    stateSave:false,
                    dom: "Blfrtip",
                    bAutoWidth: true,
                    rowId:"leavecarryforwards.Id",
                    // aaSorting: [[3,"asc"]],
                    columns: [
                      { data:null, render:"", title: "No"},
                      { data: "leavecarryforwards.Id"},
                      { data: "users.Id" },
                      { data: "users.StaffId" },
                      { data: "users.Name" },
                      { data: "leavecarryforwards.Days" }
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
                            // {
                            //   text: 'New Row',
                            //   action: function ( e, dt, node, config ) {
                            //       // clearing all select/input options
                            //       editor
                            //          .create( false )
                            //          .submit();
                            //   },
                            // },
                            // { extend: "remove", editor: editor },
                    ],

        });

        //display number in first column
        oTable.api().on( 'order.dt search.dt', function () {
            oTable.api().column(0, {search:'applied', order:'applied'}).nodes().each( function (cell, i) {
                cell.innerHTML = i+1;
            } );
        } ).draw();



        $("thead input").keyup ( function () {

                /* Filter on the column (the index) of this element */

                if ($('#leavetable').length > 0)
                {

                  var colnum=document.getElementById('leavetable').rows[0].cells.length;

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
          Leave Carry Forward
          <small>Resource Management</small>
        </h1>
        <ol class="breadcrumb">
         <li><a href="{{ url('/home') }}"><i class="fa fa-dashboard"></i> Home</a></li>
         <li><a href="#">Resource Management</a></li>
         <li><a href="#">Leave</a></li>
         <li class="active">Leave Carry Forward</li>
        </ol>
      </section>

    <!-- Main content -->
    <section class="content">

      <div class="row">
          <div class="col-md-12">
            <div class="box">
                <div class="box-body">
                  @if ($errors->any())
                      <div class="alert alert-danger">
                          <ul>
                              @foreach ($errors->all() as $error)
                                  <li>{{ $error }}</li>
                              @endforeach
                          </ul>
                      </div>
                  @endif
                  @if (session('success'))
                      <div class="alert alert-success">
                          {{ session('success') }}
                      </div>
                  @endif

                  <div class="row">
                    <div class="col-md-2">
                        <div class="form-group"><label class="control-label">Select year:</label></div>
                    </div>
                     <div class="col-md-4">
                        <div class="form-group">


                          <select class="form-control select2" onchange="getval(this);">
                            @foreach($years as $y)

                              @if ($y==$year)

                                <option value="{{$y}}" selected>{{$y}}</option>
                              @else
                                <option value="{{$y}}">{{$y}}</option>
                              @endif
                            @endforeach
                          </select>
                        </div>

                      </div>
                      <div class="col-md-6 col-xs-12">
                        <div class="form-group">
                          <button class="btn btn-success pull-right" type="button" data-toggle="modal" data-target="#importModal">Import</button>
                        </div>
                      </div>
                    </div>
                  <br><br>

                    <table id="leavetable" class="display" cellspacing="0" width="100%" padding="30px" style="font-size: 13px;">
                      <thead>
                        <tr class="search">
                          <td align='center'><input type='hidden' class='search_init' /></td>
                          @foreach($carryforward as $key=>$values)
                            @if ($key==0)

                            @foreach($values as $field=>$value)

                                <td align='center'><input type='text' class='search_init' /></td>

                            @endforeach

                            @endif

                          @endforeach
                        </tr>
                          <tr>
                            @foreach($carryforward as $key=>$value)

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
                        @foreach($carryforward as $carry)

                          <tr id="row_{{ $i }}">
                              <td></td>
                              @foreach($carry as $key=>$value)
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

    <div class="modal fade" id="importModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
      <div class="modal-dialog" role="document">
        <div class="modal-content">
          <form action="{{ url('importleavecarryforward') }}" method="POST" enctype="multipart/form-data">
            {{ csrf_field() }}
          <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
            <h4 class="modal-title" id="myModalLabel">Import Leave Carry Forward</h4>
          </div>
          <div class="modal-body">
            <div class="form-group">
                <label class="control-label">CSV File:</label>
                <input type="file" name="importfile" class="form-control" required>
            </div>
            <p class="help-block">This will import CSV data into carry forward table. Please make sure the format is in this order: StaffId, Year, Days</p>

          </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
            <button type="submit" class="btn btn-success" >Import</button>
          </div>
          </form>
        </div>
      </div>
    </div>
  </div>
  <!-- /.content-wrapper -->
  <footer class="main-footer">
    <div class="pull-right hidden-xs">
      <b>Version</b> 2.0.1
    </div>
    <strong>Copyright &copy; 2014-2016 <a href="http://www.softoya.com">TrackerOnTheGo</a>.</strong> All rights
    reserved.
  </footer>


  <script>

  function getval(sel)
  {
    window.location.href ="{{ url("/leavecarryforward") }}/"+sel.value;

  }
  </script>

@endsection
