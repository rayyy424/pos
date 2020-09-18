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

      .profile-user-dt-img{
        width: 80px;
        height: 100px;
      }

    </style>

    <script type="text/javascript" language="javascript" class="init">
        var oTable;

        $(document).ready(function() {
          oTable=$('#oTable').dataTable( {
                  columnDefs: [{ "visible": false, "targets": [1] },{"className": "dt-center", "targets": "_all"}],
                  responsive: false,
                  colReorder: false,
                  sScrollX: "100%",
                  iDisplayLength:10,
                  bAutoWidth: false,
                  rowId: 'users.Id',
                  sScrollY: "100%",
                  dom: "Bfrtip",
                  columns: [
                        { data: null,"render":"", title:"No"},
                        { data: "logistics.Id"},
                        { data: "logistics.Lorry_Type", title:"Lorry Type" },
                        { data: "logistics.distance1", title:"Minimum Distance (KM)" },
                        { data: "logistics.distance2", title:"Maximum Distance (KM)" },
                        { data: "logsitics.rate", title:"Rate (RM)" },
                        { data: "logsitics.type", title:"Type" },
                        { data: "users.Name", title:"Created By" },
                        { data: "logsitics.created_at", title:"Created At" },
                        { data : null, title:"Action",
                            "render": function ( data, type, full, meta ) {
                              return '<button class="btn btn-default btn-xs" title="DELETE" style="width:unset" onclick="DeleteProp('+full.logistics.Id+')"><i class="fa fa-trash"></i></button>'
                            }
                        }
                  ],
                  autoFill: {
                 },
                  select: true,
                  buttons: [
                        // { extend: "create", editor: editor},
                        // { extend: "remove", editor: editor },
                          {
                            text: 'New',
                            action: function ( e, dt, node, config ) {
                              OpenModal();
                            }
                          },
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

          oTable.api().on( 'order.dt search.dt', function () {
          oTable.api().column(0, {search:'applied', order:'applied'}).nodes().each( function (cell, i) {
            cell.innerHTML = i+1;
          } );
        } ).draw();

          $("thead input").keyup ( function () {

                /* Filter on the column (the index) of this element */
                if ($('#oTable').length > 0)
                {

                    var colnum=document.getElementById('oTable').rows[0].cells.length;

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


@endsection

@section('content')

    <div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
      <h1>
        Logistics Rate
        <small>Logistics</small>
      </h1>
      <ol class="breadcrumb">
        <li><a href="{{ url('/home') }}"><i class="fa fa-dashboard"></i> Home</a></li>
        <li><a href="#">Logistics</a></li>
        <li class="active">Logistics Rate</li>
      </ol>
    </section>

    <!-- Main content -->
    <section class="content">
      <div class="row">
        <div class="col-xs-12">
          <div class="box">
            <!-- /.box-header -->
            <div class="box-body">
                   <div class="modal modal-danger fade" id="error-alert">
            <div class="modal-dialog">
              <div class="modal-content">
                <div class="modal-header">
                  <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span></button>
                  <h4 class="modal-title">Alert!</h4>
                </div>
                <div class="modal-body">
                <ul></ul>
                </div>
                <div class="modal-footer">
                  <button type="button" class="btn btn-outline" data-dismiss="modal">Close</button>
                </div>
              </div>
            </div>
      </div>

      <div class="modal modal-success fade" id="update-alert">
            <div class="modal-dialog">
              <div class="modal-content">
                <div class="modal-header">
                  <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span></button>
                  <h4 class="modal-title">Alert!</h4>
                </div>
                <div class="modal-body">
                <ul></ul>
                </div>
                <div class="modal-footer">
                  <button type="button" class="btn btn-outline" data-dismiss="modal">Close</button>
                </div>
              </div>
            </div>
      </div>

      <div class="modal modal-danger fade" id="deleteModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
        <div class="modal-dialog" role="document">
          <div class="modal-content">
            <div class="modal-header">
              <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
              <h4 class="modal-title" id="myModalLabel">Delete File</h4>
            </div>
            <div class="modal-body">
              <div class="form-group" id="deleteconfirm">

              </div>
              Are you sure you want to remove / delete this file?
            </div>
            <div class="modal-footer">
              <button type="button" class="btn btn-default" id="btn-delete">Remove</button>
              <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
            </div>
          </div>
        </div>
      </div>

      <div class="modal fade" id="CreateModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
            <div class="modal-dialog" role="document">
              <div class="modal-content">
                <div class="modal-header">
                  <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                  <h4 class="modal-title" id="myModalLabel">Create</h4>
                </div>
                <div class="modal-body">
                  <div class="row">
                    <div class="col-md-10">
                      <form id="upload_form">
                        {{ csrf_field() }}
                        <input type="hidden" name="logisticsid" value="0">
                        <label>Lorry Type</label>
                        <select name="lorry" class="form-control" required="required">
                          <option value="">NONE</option>
                          @foreach($lorrysize as $ls)
                          <option value="{{$ls->Lorry_Size}}">{{$ls->Lorry_Size}}</option>
                          @endforeach
                        </select>
                        <label>Minimum Distance (KM)</label>
                        <input type="number" name="min" class="form-control" required="required">
                        <label>Maximum Distance (KM)</label>
                        <input type="number" name="max" class="form-control" required="required">
                        <label>Rate (RM)</label>
                        <input type="number" name="rate" class="form-control" required="required">
                        <label>Type</label>
                        <select name="type" class="form-control" required="required">
                          <option value="">NONE</option>
                          @foreach($ratetype as $rt)
                          <option value="{{$rt->Option}}">{{$rt->Option}}</option>
                          @endforeach
                        </select>
                      </form>
                    </div>
                  </div>
                </div>
                <div class="modal-footer">
                  <!-- <center><img src="{{ URL::to('/') . "/img/ajax-loader.gif" }}" width="50px" height="50px" alt="Loading" name='ajaxloader3' id="ajaxloader3"></center> -->
                  <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                  <button type="button" class="btn btn-primary" id="confirmbtn" onclick="submit()">Confirm</button>
                </div>
              </div>
            </div>
      </div>

                <table id="oTable" class="display" cellspacing="0" width="100%" padding="30px" style="font-size: 13px;">
                  <thead>
                      <tr>
                        <td align='center'></td>
                        @foreach($rates as $key=>$values)

                          @if ($key==0)

                            @foreach($values as $field=>$value)
                                <td  align='center'>{{ $field }}</td>
                            @endforeach
                              <td></td>
                          @endif

                        @endforeach
                      </tr>

                  </thead>
                  <tbody>

                    <?php $i = 0; ?>
                    @foreach($rates as $rate)

                      <tr id="row_{{ $i }}">
                          <td></td>
                          @foreach($rate as $key=>$value)
                            <td>
                              {{ $value }}
                            </td>
                          @endforeach
                          <td></td>
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
      <b>Version</b> 1.0.0
    </div>
    <strong>Copyright &copy; 2014-2016 <a href="http://www.softoya.com">TrackerOnTheGo</a>.</strong> All rights
    reserved.
  </footer>

<script type="text/javascript">

  $(function () {
    $( "#upload_form" ).submit(function( event ) {
      event.preventDefault();
      return false;
    });
  });
  function OpenModal()
  {
    $(".form-control").val("")
    $('#CreateModal').modal('show');
  }
  function DeleteProp(id)
  {
     var hiddeninput='<input type="hidden" class="form-control" id="deleteid" name="deleteid" value="'+id+'">';
     $( "#deleteconfirm" ).html(hiddeninput);
     $('#deleteModal').modal('show');
  }
  function submit()
  {
    $.ajaxSetup({
           headers: { 'X-CSRF-Token' : $('meta[name=_token]').attr('content') }
          });
          $.ajax({
                  url: "{{ url('/createlogisticsrate') }}",
                  method: "POST",
                  contentType: false,
                  processData: false,
                  data:new FormData($("#upload_form")[0]),
                  success: function(response){
                    $('#CreateModal').modal('hide');
                    if (response==1)
                    {
                         $(".form-control").val("")
                         var message="New logistics rate has been created";
                         $("#update-alert ul").html(message);
                         $("#update-alert").modal('show');
                         window.location.reload();
                    }
                    else 
                    {
                        $('html, body').animate({scrollTop: '0px'}, 500);
                        var errormessage = "Failed to create new logistics rate";
                        $("#error-alert ul").html(errormessage);
                        $("#error-alert").modal('show');
                    }
                  }
                });
  }

  $(document).ready(function() {
    $(document).on('click', '#btn-delete', function(e) {
      var id = $('#deleteid').val();
      $.ajaxSetup({
          headers: { 'X-CSRF-Token' : $('meta[name=_token]').attr('content') }
        });
      $.ajax({
            url: "{{ url('logisticsrate/delete') }}" + "/" + id,
            type: 'POST',
            success: function (response) {
              $('#deleteModal').modal('hide');
              if(response==1)
              {
                var message="New logistics rate has been created";
                $("#update-alert ul").html(message);
                $("#update-alert").modal('show');
                window.location.reload();
              }
              else
              {
                var errormessage = "Failed to create new logistics rate";
                $("#error-alert ul").html(errormessage);
                $("#error-alert").modal('show');
              }
            }
          });
  });
});
</script>
@endsection
