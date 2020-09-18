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
                            { data : 'serviceticket.Id', title: "Service Id"}, 
                            // { data : 'deliveryform.Id', title: "formId"}, 
                            { data : 'serviceticket.service_id', title: "Service Ticket Id"}, 
                            // { data : 'deliveryform.DO_No', title: "DO Number"}, 
                            { data : 'serviceticket.speedfreak_no', title: "Speedfreak No"},
                            { data : 'serviceticket.service_type', title: "Service Type"},
                            { data : 'serviceticket.service_date', title: "Service Date"},  
                            { data : 'serviceticket.sequence', title: "Sequence",
                                "render": function ( data, type, full, meta ) {
                                  if(data == 0)
                                    return "-";
                                  else
                                    return data;
                                }
                            },
                            { data : 'serviceticket.parent', title: "Parent SVT Number"},
                            { data : 'speedfreakservice.Status', title: "Status",
                              "render": function ( data, type, full, meta ) {
                                var status = "{{$status}}";
                                var show = "";
                                  if(status == "Overdue")
                                  {
                                    show = "<span class='label label-danger'>Overdue</span>";
                                  }
                                  if(data == "Completed")
                                    show = show + "<span class='label label-success'>"+data+"</span>";
                                  else if (data == "Pending")
                                    show = show + "<span class='label label-warning'>"+data+"</span>"
                                  else
                                    show = show + "<span class='label label-primary'>"+data+"</span>";

                                  return show;
                                }
                            },
                            { data : 'users.Name', title: "Technician"},  
                            { data : 'companies.Company_Name', title: "Client"},  
                            { data : 'radius.Location_Name', title: "Site"},  
                            { data : 'companies.type', title: "Client Type"},  
                            // { data : 'companies.Address', title: "Address"},  
                            { data : null, title: "Action",
                              "render": function ( data, type, full, meta ) {
                                  return '<button id="'+full.serviceticket.Id+'" class="editdetails btn btn-default btn-xs" title="Edit" style="width:unset"><i class="fa fa-edit"></i></button>' +
                                   '<a href="/serviceticket/details/'+full.serviceticket.Id+'" target="_blank"><button class="btn btn-default btn-xs" title="View" style="width:unset"><i class="fa fa-search"></i></a>' +
                                    '<button class="deleteservice btn btn-default btn-xs" id="'+full.serviceticket.Id+'" title="Delete" style="width:unset"><i class="fa fa-remove"></i></button>'
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
    <h1>Service Ticket<small>Service Ticket Management</small></h1>

    <ol class="breadcrumb">
      <li><a href="{{ url('/home') }}"><i class="fa fa-dashboard"></i>Home</a></li>
      <li><a href="#">Service Ticket Management</a></li>
      <li class="active">Service Ticket</li>
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

      <div class="modal fade" id="Edit" role="dialog" aria-labelledby="editModalLabel" style="display: none;">
            <div class="modal-dialog" role="document">
              <div class="modal-content">
                <div class="modal-header">
                  <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true"></span></button>
                  <h4 class="modal-title" id="ItemListModalLabel">Details</h4>
                </div>
                <div class="modal-body">
                 <form id="editform" style="margin-left: 80px;">
                          <div class="row">
                            <div class="col-md-10">
                              <label>Service</label>
                              <input type="text" class="form-control" id="service_update" readonly="">
                              <label>Speedfreak</label>
                              <input type="text"  class="form-control" id="speedfreak_update" readonly="">
                              <label>Client</label>
                              <input type="text" class="form-control" id="client_update" readonly="">
                              <label>Site</label>
                              <input type="text" class="form-control" id="site_update" readonly="">
                              <label>Date</label>
                              <input type="text" autocomplete="off" class="date form-control" id="date_update" name="date_update">
                              <label>In-Charge</label>
                              <select class="form-control" name="tech_update" id="tech_update">
                                @foreach($pic as $pi)
                                <option value="{{$pi->Id}}">{{$pi->Name}}</option>
                                @endforeach
                              </select>
                              <label>Status</label>
                              <select name="status" id="status" class="form-control">
                                @foreach($allstatus as $as)
                                <option value="{{$as->Status}}">{{$as->Status}}</option>
                                @endforeach
                              </select>
                              <label>Remarks</label>
                              <input type="text" class="form-control" id="remarks_update" name="remarks_update">
                              <input type="hidden" name="serviceid" id="serviceid">
                            </div>
                          </div>
                 </form>
                </div>
                <div class="modal-footer">
                  <center><img src="{{ URL::to('/') . "/img/ajax-loader.gif" }}" width="50px" height="50px" alt="Loading" name='ajaxloader4' id="ajaxloader4"></center>
                  <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                   <button type="button" class="btn btn-primary" id="update">Update</button>
                </div>
              </div>
            </div>
      </div>
  
    <div class="modal fade" id="Upload" role="dialog" aria-labelledby="myModalLabel">
            <div class="modal-dialog modal-lg" role="document">
              <div class="modal-content">
                <div class="modal-header">
                  <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                  <h4 class="modal-title" id="myModalLabel">Create</h4>
                </div>
                <div class="modal-body">
                  <form enctype="multipart/form-data" id="upload_form" role="form" method="POST" action="" >
                    <div class="row">
                      <div class="col-md-12">
                        <div class="row">
                          <div class="col-md-10">
                            <div class="form-group">
                              <div class="col-md-3">
                                <label>Type:</label>
                              </div>
                              <div class="col-md-7">
                                <select style="width:100%" class="select2 form-control" name="assettype" id="assettype">
                                  <option value="">NONE</option>
                                  @foreach ($assettype as $at)
                                  <option>{{$at}}</option>
                                  @endforeach
                                </select>
                              </div>
                            </div>
                          </div>
                        </div>
                        <div class="row">
                          <div class="col-md-10">
                            <div class="form-group">
                              <div class="col-md-3">
                              <label>Code:</label>
                              </div>
                              <div class="col-md-7">
                              <select style="width:100%" class="select2 form-control" name="speedfreak_no" id="speedfreak_no">
                              <option value="">NONE</option>
                              @foreach ($sc as $sc)
                              <option value="{{$sc->machinery_no}}">{{$sc->machinery_no}}</option>
                              @endforeach
                              </select>
                              </div>
                            </div>
                          </div>
                        </div>
                        <div class="row">
                          <div class="col-md-10">
                            <div class="form-group">
                              <div class="col-md-3">
                              <label>Client:</label>
                              </div>
                              <div class="col-md-7">
                              <select  class="select2 form-control" name="client" id="client" style="width:100%">
                              <option value=""></option>
                              @foreach ($client as $c)
                              <option value="{{$c->Id}}">{{$c->Company_Name}}</option>
                              @endforeach
                              </select>
                              </div>
                            </div>
                          </div>
                        </div>
                        <div class="row">
                          <div class="col-md-10">
                            <div class="form-group">
                              <div class="col-md-3">
                              <label>Site:</label>
                              </div>
                              <div class="col-md-7">
                              <select class="select2 form-control" name="site" id="site" style="width:100%">
                              </select>
                            </div>
                            </div>
                          </div>
                        </div>
                        <div class="row">
                          <div class="col-md-10">
                            <div class="form-group">
                              <div class="col-md-3">
                              <label>Ticket:</label>
                              </div>
                              <div class="col-md-7">
                              <select class="select2 form-control" name="tickettype" id="tickettype" style="width:100%">
                                <option></option>
                                @foreach($tickettype as $tt)
                                <option value="{{$tt->Id}}">{{$tt->Name}}</option>
                                @endforeach
                              </select>
                            </div>
                            </div>
                          </div>
                        </div>
                        <br>
                        <!-- <div class="box"> -->
                        <!-- <div class="row">
                          <div class="col-md-12">
                            <div class="form-group">
                              <table id="createtable" class="table table-bordered table-hover">
                                <col width="20%">
                                <col width="20%">
                                <col width="30%">
                                <col width="20%">
                                <col width="10%">
                                <thead>
                                  <tr>
                                    <th>Service</th>
                                    <th>Date</th>
                                    <th>In Charge</th>
                                    <th>Remarks</th>
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
                                      <input type="text" autocomplete="off" class="date form-control" id="date">
                                    </td>
                                    <td>
                                      <select class="form-control select2" id="pic" style="width:100%">
                                        <option value="" disabled="">None</option>
                                      @foreach ($pic as $p)
                                      <option value="{{$p->Id}}">{{$p->Name}}</option>
                                      @endforeach
                                      </select>
                                    </td>
                                    <td>
                                      <input type="text" id="remarks" class="form-control">
                                    </td>
                                    <td>
                                      <button class="btn btn-default" style="width:50px" id="addRow"><i class="fa fa-plus-circle" aria-hidden="true"></i></button>
                                    </td>
                                  </tr>
                                </tfoot>
                              </table>
                            </div>
                          </div>
                        </div> -->
                        <!-- </div> --> 
                  </div>
                  <!--end-->
                  </div>
                </form>
              </div>
            <!-- </div> -->
                <div class="modal-footer">
                  <center><img src="{{ URL::to('/') . "/img/ajax-loader.gif" }}" width="50px" height="50px" alt="Loading" name='ajaxloader3' id="ajaxloader3"></center>
                  <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                  <button type="button" class="btn btn-primary" id="upload">Submit</button>
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
               <label>Status:</label>
               <select class="form-control" id="statusfilter" name="statusfilter">
                <option>All</option>
                <option value="Overdue" <?php if($status == "Overdue") echo "selected" ?> >Overdue</option> 
                @foreach($allstatus as $s)
                  @if($s->Status == $status)
                  <option value="{{$s->Status}}" selected="">{{$s->Status}}</option>
                  @else
                  <option value="{{$s->Status}}">{{$s->Status}}</option>
                  @endif
                @endforeach
               </select>
              </div>
          </div>
          <div class="col-md-2">
            <label>Type</label>
            <select class="form-control" id="assetfilter">
              <option value="">All</option>
              @foreach($assettype as $at)
              <option <?php if($asset == $at) echo "selected"; ?> >{{$at}}</option>
              @endforeach
            </select>
          </div>
          <div class="col-md-2">
               <div class="input-group">
                <br>
                <button type="button" class="btn btn-success btn" data-toggle="modal" onclick="refresh();">Refresh</button>
                </div>
          </div>
      </div>
    </div>
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
      <b>Version</b> 1.0.0
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
          url: "{{ url('/getTicketDetails/') }}" + "/" + id,
          method: "GET",
          success: function(response)
          {
            $('#speedfreak_update').val(response.details.speedfreak_no);
            $('#date_update').val(response.details.service_date);
            $('#service_update').val(response.details.service_type);
            $('#client_update').val(response.details.Company_Name);
            $('#site_update').val(response.details.Location_Name);
            $('#tech_update').val(response.details.technicianId);
            $('#remarks_update').val(response.details.service_summary);
            $('#serviceid').val(response.details.Id);
            $('#status').val(response.details.Status);
            $('#Edit').modal('show');
          }
        });

      });
    });

    function refresh()
    {

      var d=$('#range').val();
      var arr = d.split(" - ");
      var status = $('#statusfilter').val();
      var asset = $('#assetfilter').val();
      if(!status && !asset)
      {
        window.location.href ="{{ url("/servicemanagement") }}/"+arr[0]+"/"+arr[1];
      }
      else if(status && !asset)
      {
       window.location.href ="{{ url("/servicemanagement") }}/"+arr[0]+"/"+arr[1]+ "/" + status;
      }
      else
      {
       window.location.href ="{{ url("/servicemanagement") }}/"+arr[0]+"/"+arr[1]+ "/" + status + "/" +"All" + "/" + asset;  
      }

    }

    $(document).ready(function() {
      $(document).on('click', '#update', function(e) {
          $('#ajaxloader4').show();
      $("#update").prop('disabled',true);
      $.ajaxSetup({
         headers: { 'X-CSRF-Token' : $('meta[name=_token]').attr('content') }
      });
      $.ajax({
                  url: "{{ url('/servicemanagement/update') }}",
                  method: "POST",
                  data: $('#editform').serialize(),
                  success: function(response){
                    $("#upload").prop('disabled',false);
                    if(response == 1)
                    {
                        $("#ajaxloader4").hide();
                        $('#Edit').modal('hide');
                        var message="Service Ticket Updated!";
                        $("#update-alert ul").html(message);
                        $("#update-alert").modal('show');
                        window.location.reload();
                    }
                    else
                    {
                        $("#ajaxloader4").hide();
                        $('#Edit').modal('hide');
                        // var errormessage="Failed to Update Service Ticket!";
                        $("#error-alert ul").html(response);
                        $("#error-alert").modal('show');
                    }
                  }
                });
      });
      });

    $(document).ready(function() {
      $(document).on('click', '#create', function(e) {
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
                  url: "{{ url('/servicemanagement/delete') }}",
                  method: "POST",
                  data:{Id:id},
                  success: function(response){
                    if(response == 1)
                    {
                        $('#Delete').modal('hide');
                        var message="Service Ticket Deleted!";
                        $("#update-alert ul").html(message);
                        $("#update-alert").modal('show');
                        window.location.reload();
                    }
                    else
                    {
                        $('#Delete').modal('hide');
                        var errormessage="Failed to Delete Service Ticket!";
                        $("#error-alert ul").html(errormessage);
                        $("#error-alert").modal('show');
                    }
                  }
            });
      });

    });

    $(document).ready(function() {
      $(document).on('click', '#addRow', function(e) {
            InsertRow($('#service').val(),$('#date').val(),$('#pic').val(),$('#pic :selected').text(),$('#remarks').val());
            $('#service').val("").change();
            $('#date').val("");
            $('#pic').val("").change();
            $('#remarks').val("");
      });
    });

    function InsertRow(service,date,pic,picname,remarks)
    {
      var tBody = $('#createtable > TBODY')[0];
      row = tBody.insertRow(-1);
      var cell = $(row.insertCell(-1));
      cell.html(service + "<input type='hidden' name='service[]' value='"+service+"'>");

      var cell = $(row.insertCell(-1));
      cell.html(date + "<input type='hidden' name='date[]' value='"+date+"'>");

      var cell = $(row.insertCell(-1));
      cell.html(picname + "<input type='hidden' name='pic[]' value='"+pic+"'>");

      var cell = $(row.insertCell(-1));
      cell.html(remarks + "<input type='hidden' name='remarks[]' value='"+remarks+"'>");

      var cell = $(row.insertCell(-1));
      cell.html("<button class='remove btn btn-danger' style='width:50px'><i class='fa fa-minus-circle' aria-hidden='true'></button>");
    }

    $(document).ready(function() {
      $(document).on('click', '.remove', function(e) {
            var row = $(this).closest("TR");
            var name = $("TD", row).eq(0).html();
            if (confirm("Do you want to delete: " + name)) {
                $(row).remove();
            }
      });
    });

    $(document).ready(function() {
      $(document).on('click', '#upload', function(e) {
      $('#ajaxloader3').show();
      $("#upload").prop('disabled',true);
      $.ajaxSetup({
         headers: { 'X-CSRF-Token' : $('meta[name=_token]').attr('content') }
      });
      $.ajax({
                  url: "{{ url('/servicemanagement/create') }}",
                  method: "POST",
                  contentType: false,
                  processData: false,
                  data:new FormData($("#upload_form")[0]),
                  success: function(response){
                    $("#upload").prop('disabled',false);
                    if(response == 1)
                    {
                        $("#ajaxloader3").hide();
                        $('#Upload').modal('hide');
                        var message="Service Ticket Created!";
                        $("#update-alert ul").html(message);
                        $("#update-alert").modal('show');
                        window.location.reload();
                    }
                    else
                    {
                        $("#ajaxloader3").hide();
                        $('#Upload').modal('hide');
                        // var errormessage="Failed to Create Service Ticket!";
                        $("#error-alert ul").html(response);
                        $("#error-alert").modal('show');
                    }
                  }
                });
      });

    });

    $(document).ready(function() {
      $(document).on('change', '#speedfreak_no', function(e) {
          var speedfreak = $('#speedfreak_no').val();
          $.ajaxSetup({
          headers: { 'X-CSRF-Token' : $('meta[name=_token]').attr('content') }
          });
          $.ajax
          ({
          type: "GET",
          url: "{{ url('/servicegetsite') }}" + "/" + speedfreak,
          success: function(response){
            $("#site").empty();
            $('#client').empty();
            $("#site").append("<option value='"+response.do.Location+"' selected>"+response.do.Location_Name+"</option>");
            $("#client").append("<option value='"+response.do.client+"' selected>"+response.do.Company_Name+"</option>");
          }
        });
      });
    });

    $(document).ready(function() {
      $(document).on('change', '#assettype', function(e) {
          var selected = $(this).val();
          $.ajaxSetup({
            headers: { 'X-CSRF-Token' : $('meta[name=_token]').attr('content') }
          });
          $.ajax
          ({
            type: "GET",
            url: "{{ url('/ticketassettype') }}" + "/" + selected,
            success: function(response){
              $("#speedfreak_no").empty();
              $("#speedfreak_no").append("<option>NONE</option>");
              response.item.forEach(function(ele){
                $("#speedfreak_no").append("<option>"+ele.machinery_no+"</option>");
              });
            }
          });
      });
    });

    $(document).ready(function() {
      $(document).on('change', '#service', function(e) {
          var service = $('#service').val();
          $.ajaxSetup({
          headers: { 'X-CSRF-Token' : $('meta[name=_token]').attr('content') }
          });
          $.ajax
          ({
          type: "GET",
          url: "{{ url('/servicegetpic') }}" + "/" + service,
          success: function(response){
            $("#pic").empty();
            response.user.forEach(function(element) {
              $("#pic").append("<option value='"+element.Id+"' selected>"+element.Name+"</option>");
            });
          }
        });
      });
    });


</script>

@endsection