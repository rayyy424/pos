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
                          columnDefs: [{ "visible": false, "targets":[1]},
                            {"className": "dt-center", "targets": "_all"}
                            ],
                            responsive: false,
                            dom: "Bltp",
                            sScrollX: "100%",
                            sScrollY: "100%",
                            scrollCollapse: true,
                            iDisplayLength:100,
                            bAutoWidth: true,
                            iDisplayLength:10,
                            rowId:"serviceticket.Id",
                            columns: [
                            { data : null, "render":"", title: "No"},
                            { data : 'ticket.Id', title: "Service Id"}, 
                            { data : 'ticket.Name' , title: "Ticket Name"},
                            { data : 'user' , title: "Created By"},
                            { data : 'ticket.created_at' , title: "Created At"},   
                            // { data : 'companies.Address', title: "Address"},  
                            { data : null, title: "Action",
                              "render": function ( data, type, full, meta ) {
                                  return '<button id="'+full.ticket.Id+'" class="editdetails btn btn-default btn-xs" title="Edit" style="width:unset"><i class="fa fa-edit"></i></button>' +
                                   // '<button class="btn btn-default btn-xs" title="View" style="width:unset"><i class="fa fa-search"></i>' +
                                    ' <button class="deleteservice btn btn-default btn-xs" id="'+full.ticket.Id+'" title="Delete" style="width:unset"><i class="fa fa-remove"></i></button>'
                              }
                            }
                    ],
                                 select: true,
                                 buttons: [
                                 ],
                     });

                        oTable.on( 'order.dt search.dt', function () {
                          oTable.api().column(0, {search:'applied', order:'applied'}).nodes().each( function (cell, i) {
                              cell.innerHTML = i+1;
                          } );
                          } ).api().draw();

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
    <h1>Ticket Flow Management<small>Service Ticket Management</small></h1>

    <ol class="breadcrumb">
      <li><a href="{{ url('/home') }}"><i class="fa fa-dashboard"></i>Home</a></li>
      <li><a href="#">Service Ticket Management</a></li>
      <li class="active">Ticket Flow Management</li>
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
  
    <div class="modal fade" id="Upload" role="dialog" aria-labelledby="myModalLabel">
            <div class="modal-dialog modal-lg" role="document">
              <div class="modal-content">
                <div class="modal-header">
                  <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                  <h4 class="modal-title" id="myModalLabel">Ticket Flow</h4>
                </div>
                <div class="modal-body">
                  <form enctype="multipart/form-data" id="upload_form" role="form" method="POST" action="" >
                    <div class="row" style="margin-left: 20%; margin-right: 20%">
                      <div class="col-md-12">
                              <input type="hidden" name="ticketid" id="ticketid">
                              <label>Ticket Name</label>
                              <input type="text" name="type" id="type" class="form-control">
                              <label>Flow</label>
                              <table id="createtable" class="table table-bordered table-hover">
                              <col width="90%">
                              <col width="10%">
                                <thead>
                                  <tr>
                                    <th>Service</th>
                                    <th>Action</th>
                                  </tr>
                                </thead>
                                <tbody>
                                </tbody>
                                <tfoot>
                                  <tr>
                                    <td>
                                      <select class="form-control select2 " id="service" style="width:100%">
                                      <option value="">None</option>
                                      @foreach($type as $t)
                                      <option value="{{$t->Option}}">{{$t->Option}}</option>
                                      @endforeach
                                      </select>
                                    </td>
                                    <td>
                                      <button class="btn btn-default" style="width:50px" id="addRow"><i class="fa fa-plus-circle" aria-hidden="true"></i></button>
                                    </td>
                                  </tr>
                                </tfoot>
                              </table>
                        </div>
                  </div>
                  <!--end-->
                  </form>
                </div>
            <!-- </div> -->
                <div class="modal-footer">
                  <center><img src="{{ URL::to('/') . "/img/ajax-loader.gif" }}" width="50px" height="50px" alt="Loading" name='ajaxloader3' id="ajaxloader3"></center>
                  <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                  <button type="button" class="btn btn-primary" id="upload">Create</button>
                  <button type="button" class="btn btn-primary" id="update">Update</button>
                </div>
              </div>
            </div>
      </div>
      <div class="box">
          <br>
          <div class="col-md-2">
              <button class="btn" id="create" name="create"><i class="fa fa-plus-circle" aria-hidden="true"></i> Create</button>
          </div>
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
        $(".select2").select2();
        $('.date').datepicker({
          autoclose: true,
          format: 'dd-M-yyyy',
        });
        $( "#upload_form" ).submit(function( event ) {
        event.preventDefault();
        return false;
        });
    });

   $(document).ready(function() {
      $(document).on('click', '.editdetails', function(e) {
        var id = this.id;
         $.ajaxSetup({
         headers: { 'X-CSRF-Token' : $('meta[name=_token]').attr('content') }
          });
        $.ajax({
            url: "{{ url('/getflowdetails/') }}" + "/" + id,
            method: "GET",
            success: function(response)
            {
              $('#ticketid').val(id);
              $('#createtable > TBODY').empty();
              $('#type').val(response.ticket.Name);
              response.item.forEach(function(ele){
                  $('#createtable > TBODY').append(`<tr>
                    <td><input type="hidden" name="flowid[]" value="${ele.Id}"><input type="hidden" name="service[]" value="${ele.service}"> ${ele.service}</td>
                    <td><button class='remove btn btn-danger' style='width:50px'><i class='fa fa-minus-circle' aria-hidden='true'></button></td>
                    </tr>`);
              });
              $('#update').show();
              $('#upload').hide();
              $('#Upload').modal('show');
            }
          });

      });
    });

    $(document).ready(function() {
      $(document).on('click', '#update', function(e) {
          $('#ajaxloader3').show();
          $("#update").prop('disabled',true);
          var myurl = "{{url('updateticketflow')}}";
          var form = "upload_form";
          var modal = "Upload";
          var button = "update";
          var type = "Update";
          PostAjax(myurl,form,modal,button,type);
      });
      });

    $(document).ready(function() {
      $(document).on('click', '#create', function(e) {
          $('#type').val("");
          $('#createtable > TBODY').empty();
          $('#update').hide();
          $('#upload').show();
          $('#Upload').modal('show');
      });

    });

    $(document).ready(function() {
      $(document).on('click', '.deleteservice', function(e) {
        var id = this.id;
        var hiddeninput='<input type="hidden" class="form-control" id="deleteticketid" name="deleteticketid" value="'+id+'">';
        $( "#deleteticket" ).html(hiddeninput);
          $('#Delete').modal('show');
      });

    });

    $(document).ready(function() {
      $(document).on('click', '#confirmdelete', function(e) {
        var id = $('#deleteticketid').val();
          $.ajaxSetup({
           headers: { 'X-CSRF-Token' : $('meta[name=_token]').attr('content') }
          });
      $.ajax({
                  url: "{{ url('/deleteticket') }}/" + id ,
                  method: "POST",
                  success: function(response){
                    if(response == 1)
                    {
                        $('#Delete').modal('hide');
                        var message="Ticket Deleted!";
                        $("#update-alert ul").html(message);
                        $("#update-alert").modal('show');
                        window.location.reload();
                    }
                    else
                    {
                        $('#Delete').modal('hide');
                        var errormessage="Failed to Delete Ticket!";
                        $("#error-alert ul").html(errormessage);
                        $("#error-alert").modal('show');
                    }
                  }
            });
      });

    });

    $(document).ready(function() {
      $(document).on('click', '#addRow', function(e) {
            InsertRow($('#service').val());
            $('#service').val("").change();
      });
    });

    function InsertRow(service)
    {
      var tBody = $('#createtable > TBODY')[0];
      row = tBody.insertRow(-1);
      var cell = $(row.insertCell(-1));
      cell.html(service + '<input type="hidden" name="flowid[]" value="0">' + "<input type='hidden' name='service[]' value='"+service+"'>");

      var cell = $(row.insertCell(-1));
      cell.html("<button class='remove btn btn-danger' style='width:50px'><i class='fa fa-minus-circle' aria-hidden='true'></button>");
    }

    $(document).ready(function() {
      $(document).on('click', '.remove', function(e) {
            var row = $(this).closest("TR");
            var closestid = $(this).parent().parent().find('input[name="flowid[]"]').val();
            var name = $("TD", row).eq(0).html();
            console.log(closestid);
            if (confirm("Do you want to delete this? ")) {
                if(closestid)
                {
                    $.ajaxSetup({
                       headers: { 'X-CSRF-Token' : $('meta[name=_token]').attr('content') }
                    });
                    $.ajax({
                        url: "{{url('deleteticketflow')}}/" + closestid,
                        method: "GET"
                    });
                }
                $(row).remove();
            }
      });
    });

    function PostAjax(myurl,form,modal,button,type)
    {
      var message = type == "Create" ? "Item Created" : "Item Updated";
      var errormessage = type == "Create" ? "Failed to create item" : "Failed to update item";
      $.ajaxSetup({
         headers: { 'X-CSRF-Token' : $('meta[name=_token]').attr('content') }
      });
      $.ajax({
          url: myurl,
          method: "POST",
          contentType: false,
          processData: false,
          data:new FormData($("#"+form)[0]),
          success: function(response){
            $("#"+button).prop('disabled',false);
            if(response == 1)
            {
                $("#ajaxloader3").hide();
                $('#'+modal).modal('hide');
                $("#update-alert ul").html(message);
                $("#update-alert").modal('show');
                window.location.reload();
            }
            else
            {
                $("#ajaxloader3").hide();
                $('#'+modal).modal('hide');
                $("#error-alert ul").html(errormessage);
                $("#error-alert").modal('show');
            }
          }
        });
    }

    $(document).ready(function() {
      $(document).on('click', '#upload', function(e) {
        $('#ajaxloader3').show();
        $("#upload").prop('disabled',true);
          var myurl = "{{url('createticketflow')}}";
          var form = "upload_form";
          var modal = "Upload";
          var button = "upload";
          var type = "Create";
          PostAjax(myurl,form,modal,button,type);
      });

    });


</script>

@endsection