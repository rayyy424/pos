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
      <script type="text/javascript" language="javascript" src="https://cdn.datatables.net/plug-ins/1.10.13/api/sum().js"></script>


      <script type="text/javascript" language="javascript" src="{{ asset('/plugin/js/dataTables.editor.min.js') }}"></script>

      {{-- chart js --}}
      <script type="text/javascript" language="javascript" src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.5.0/Chart.bundle.min.js"></script>

      <script type="text/javascript">

        $(document).ready(function() {
          var oTable;
          oTable=$('#oTable').dataTable({
                          columnDefs: [{ "visible": false, "targets":[1]}],
                            responsive: false,
                            dom: "Bltp",
                            sScrollX: "100%",
                            sScrollY: "100%",
                            scrollCollapse: true,
                            iDisplayLength:100,
                            bAutoWidth: true,
                            iDisplayLength:10,
                            columns: [
                            { data : null, "render":"", title: "No"},
                            { data : 'requisition.Id', title: "Id"},
                            { data : 'requisition.Req_No', title: "Requisition Number"}, 
                            { data : 'requisitionhistory.status', title: "Status"}, 
                            { data : 'requisitionhistory.status_details', title: "Status Details"},
                            { data : 'serviceticket.service_id', title: "Service Ticket Id"}, 
                            { data : 'users.Name', title: "Created_By"}, 
                            { data : 'requisition.created_at', title: "Created_At"}, 
                            { data : null, title: "Action",
                              "render": function ( data, type, full, meta ) {
                                    return '<button class="item btn btn-default btn-xs" title="View Item" style="width:unset; color:#0619BA" id="'+full.requisition.Id+'"><i class="fa fa-eye"></i></button>'
                              }
                            }
                    ],
                                 select: true,
                                 buttons: [
                                 ],
                     });

                        oTable.api().on( 'order.dt search.dt', function () {
                          oTable.api().column(0, {search:'applied', order:'applied'}).nodes().each( function (cell, i) {
                              cell.innerHTML = i+1;
                          } );
                          } ).draw();

                    $(".display thead input").keyup ( function () {

                    /* Filter on the column (the index) of this element */
                    if ($('#oTable').length > 0)
                    {

                        var colnum=document.getElementById('oTable').rows[0].cells.length;

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
                          $("#ajaxloader3").hide();
                          $("#ajaxloader4").hide();
        });
      </script>

@endsection

@section('content')

<div class="content-wrapper">
  <!-- Content Header (Page header) -->
  <section class="content-header">
    <h1>Storekeeper<small>Service Ticket Management</small></h1>

    <ol class="breadcrumb">
      <li><a href="{{ url('/home') }}"><i class="fa fa-dashboard"></i>Home</a></li>
      <li><a href="#">Service Ticket Management</a></li>
      <li class="active">Storekeeper</li>
    </ol>
  </section>

  <br>

  <section class="content">
    <div class="row">
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

      <div class="modal fade" id="viewItemListModal" role="dialog" aria-labelledby="myItemListModalLabel" style="display: none;">
            <div class="modal-dialog" role="document">
              <div class="modal-content">
                <div class="modal-header">
                  <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true"></span></button>
                  <h4 class="modal-title" id="ItemListModalLabel">Item List</h4>
                </div>
                <div class="modal-body">
                  <table id="itemlisttable" class="table table-condensed">
                <thead>
                    <tr>
                      <th>Requsition Number</th>
                      <th>Machinery Number</th>
                      <th>Item Name</th>
                      <th>Quantity</th>
                    </tr>
                  </thead>
                  <tbody>
                  </tbody>
                </table>
              </div>
                <div class="modal-footer">
                  <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                </div>
              </div>
            </div>
      </div>

      <div class="modal fade" id="Delete" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
            <div class="modal-dialog" role="document">
              <div class="modal-content">
                <div class="modal-header">
                  <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                  <h4 class="modal-title" id="myModalLabel">Delete</h4>
                </div>
                <div class="modal-body">
                  <div class="form-group" id="deleteticket">

                  </div>
                  Are you sure you want to delete this ticket?
                </div>
                <div class="modal-footer">
                  <!-- <center><img src="{{ URL::to('/') . "/img/ajax-loader.gif" }}" width="50px" height="50px" alt="Loading" name='ajaxloader2' id="ajaxloader2"></center> -->
                  <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                  <button type="button" class="btn btn-primary" id="confirmdelete">Delete</button>
                </div>
              </div>
            </div>
      </div>

     
      <div class="box">
        <br>
    <div class="row">
      <div class="col-md-11">
          <div class="col-md-3">
              <div class="input-group">
               <label>Date:</label>
               <input type="text" class="form-control" id="range" name="range">
              </div>
          </div>
          <div class="col-md-2">
               <div class="input-group">
                <label>Refresh</label>
                <button type="button" class="btn btn-success btn" data-toggle="modal" onclick="refresh();">Refresh</button>
                </div>
          </div>
      </div>
    </div>
    <br>
        <br><br>
    <table id="oTable" class="display" cellspacing="0" width="100%" padding="30px" style="font-size: 13px;">
                       <thead>
                                <tr class="search">
                                @foreach($list as $key=>$value)

                                    @if ($key==0)
                                        <?php $i = 0; ?>
                                            @foreach($value as $field=>$a)
                                                @if ($i==0)
                                                  <th align='center'><input type='hidden' class='search_init' name='{{$i}}' placemark='{{$a}}'></th>
                                                @else
                                                  <th align='center'><input type='text' class='search_init' name='{{$i}}'  placemark='{{$a}}'></th>
                                                @endif
                                                <?php $i ++; ?>
                                            @endforeach
                                        <th align='center'><input type='text' class='search_init' name='{{$i}}'  placemark='{{$a}}'></th>
                                    @endif
                                @endforeach
                                </tr>
                                <tr>
                                    @foreach($list as $key=>$value)

                                        @if ($key==0)
                                        <td></td>

                                        @foreach($value as $field=>$value)
                                        <td>
                                            {{ $field }}
                                        </td>
                                        @endforeach
                                        <td></td>
                                        @endif

                                    @endforeach
                                </tr>
                            </thead>
                            <tbody>
                                <?php $i = 0; ?>
                                @foreach($list as $delivery)

                                <tr>
                                    <td></td>      

                                    @foreach($delivery as $key=>$value)
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

  </section>

  <footer class="main-footer">
    <div class="pull-right hidden-xs">
      <b>Version</b> 2.0.1
    </div>
    <strong>Copyright &copy; 2014-2016 <a href="http://www.softoya.com">TrackerOnTheGo</a>.</strong> All rights reserved.
  </footer>
</div>

<script type="text/javascript">
   $(function () {
        $('#range').daterangepicker({locale: {
        format: 'DD-MMM-YYYY'
        },startDate: '{{$start}}',
        endDate: '{{$end}}'});

        $(".select2").select2();
        $('.date').datepicker({
          autoclose: true,
          format: 'dd-M-yyyy',
        });

    });

   function refresh()
    {

      var d=$('#range').val();
      var arr = d.split(" - ");
       window.location.href ="{{ url("/storekeeper") }}/"+arr[0]+"/"+arr[1];
    }

    $(document).ready(function() {
        $(document).on('click', '.item', function(e) {
            var id = $(this).attr('id');
            $('#viewItemListModal').modal('show');
         $.ajaxSetup({
         headers: { 'X-CSRF-Token' : $('meta[name=_token]').attr('content') }
         });
          $.ajax({
          url: "{{ url("/storeGetItemList/") }}" + "/" + id,
          method: "GET",
          success: function(response){
            $('#itemlisttable > tbody').empty();
            response.item.forEach(function(element) {
                $('#itemlisttable > tbody').append(`<tr>
                  <td>${element.Req_No}</td>
                  <td>${element.machinery_no}</td>
                  <td>${element.name}</td>
                  <td>${element.Qty}</td>
                </tr>`);
              });
            },
          error: function(response){
          }
          });
        });
    });
</script>

@endsection