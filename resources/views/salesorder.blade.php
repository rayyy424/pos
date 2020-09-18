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

      .fa-warning{
        color:red;
      }

      .fa-wrench{
        color:blue;
      }

      .fa-folder-open-o{
        color: brown;
      }

      .fa-reply, .fa-exchange{
        color:orange;
      }

      .fa-file{
        color:green;
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

      <script type="text/javascript" language="javascript" class="init">
        var oTable1;
        var oTable2;

        $(document).ready(function() {
             oTable1=$('#salesordertable').dataTable({
                          columnDefs: [{ "visible": false, "targets":[1,2]},{"className": "dt-center", "targets": "_all"}],
                            responsive: false,
                            dom: "Bltp",
                            sScrollX: "100%",
                            sScrollY: "100%",
                            scrollCollapse: true,
                            iDisplayLength:100,
                            bAutoWidth: true,
                            iDisplayLength:10,
                            rowId:"tracker.Id",
                            columns: [
                            { data : null, "render":"", title: "No"},
                            { data : 'tracker.Id', title: "Id"},
                            { data : 'soId', title: "salesorderid"},
                            { data : 'salesorder.po', title: "PO Number"},
                            { data : 'salesorder.SO_Number', title: "SO Number"},
                            { data : 'salesorder.hiredate', title: "Hire Date"},
                            { data : 'salesorder.rental_start', title: "Rental Start"},
                            { data : 'salesorder.rental_end', title: "Rental End"},
                            { data : 'companies.Company_Name', title: "Company"},
                            { data : 'client.Company_Name', title: "Client"},
                            { data : "tracker.Region", title:"Region"},
                            { data : "tracker.State", title:"State"},
                            { data : "tracker.SiteName", title:"Site Name"},
                            { data : "tracker.sales_order", title:"SO Status",
                            "render": function ( data, type, full, meta ) {
                                if(full.tracker.sales_order == 0)
                                {
                                  return '<span class="label label-danger">NO</span>';
                                }
                                else
                                {
                                    return '<span class="label label-success">YES</span>';
                                }
                            }
                            },
                            { data : "salesorder.do", title:"DO Status",
                            "render": function ( data, type, full, meta ) {
                                if(full.salesorder.do == 0)
                                {
                                  return '<span class="label label-danger">NO</span>';
                                }
                                else
                                {
                                    return '<span class="label label-success">YES</span>';
                                }
                            }
                            },
                            { data : "tracker.recurring", title:"Recurring Active",
                            "render": function ( data, type, full, meta ) {
                                if(full.tracker.recurring == 0)
                                {
                                  return '<span class="label label-danger">NO</span>';
                                }
                                else
                                {
                                    if({{$me->Recur_SO}})
                                    {
                                      return '<button class="recur btn btn-default btn-xs" title="Recur Sales Order" id="'+full.soId+'" style="width:unset; background-color:green; color:white"><i class="fa fa-repeat"></i></button>'
                                    }
                                    else
                                    {
                                      return '<span class="label label-success">YES</span>';
                                    }
                                }
                            }
                            },
                            { data : "salesorder.invoice", title:"Invoice Status",
                            "render": function ( data, type, full, meta ) {
                                if(full.salesorder.invoice == 0)
                                {
                                  return '<span class="label label-danger">NO</span>';
                                }
                                else
                                {
                                    return '<span class="label label-success">YES</span>';
                                }
                            }
                            },
                            { data : null, title:"Action",
                            "render": function ( data, type, full, meta ) {
                                var display = "";
                                if (!full.salesorder.SO_Number)
                                {
                                   display = '<a href="/salesorderdetails2/'+full.tracker.Id+'" target="_blank" ><button class="btn btn-default btn-xs" title="View" style="width:unset"><i class="fa fa-wrench"></i></button></a>'
                                }
                                //with SO
                                else
                                {
                                  if(full.salesorder.do == 0)
                                  {

                                    display += '<a href="/salesorderdetails/'+full.salesorder.SO_Number+'" target="_blank" ><button class="btn btn-default btn-xs" title="View" style="width:unset"><i class="fa fa-wrench"></i></button></a>'+
                                      '<a href="/salesorderhistory/'+full.tracker.Id+'" target="_blank"><button class="btn btn-default btn-xs" title="Sales Order" style="width:unset"><i class="fa fa-folder-open-o"></i></button></a>'+
                                      '<a href="/mydeliveryrequest/'+full.tracker.Id+'" target="_blank"><button class="btn btn-default btn-xs" title="Generate DO" style="width:unset"><i class="fa fa-paper-plane"></i></button></a>'
                                  }
                                  // else with do
                                  else 
                                  {
                                    display += '<a href="/salesorderdetails/'+full.salesorder.SO_Number+'" target="_blank"><button class="btn btn-default btn-xs" title="View" style="width:unset"><i class="fa fa-wrench"></i></button></a>'+
                                    '<a href="/mydeliveryrequest/'+full.tracker.Id+'/terminate" target="_blank"><button class="btn btn-default btn-xs" title="Terminate" style="width:unset"><i class="fa fa-warning"></i></button></a>'+
                                    '<a href="/mydeliveryrequest/'+full.tracker.Id+'/exchange" target="_blank"><button class="btn btn-default btn-xs" title="Exchange" style="width:unset"><i class="fa fa-exchange"></i></button></a>'+
                                    '<a href="/salesorderhistory/'+full.tracker.Id+'" target="_blank"><button class="btn btn-default btn-xs" title="Sales Order" style="width:unset"><i class="fa fa-folder-open-o"></i></button></a>'+
                                    '<a href="/deliveryorderhistory/'+full.tracker.Id+'" target="_blank"><button class="btn btn-default btn-xs" title="Delivery Order" style="width:unset"><i class="fa fa-truck"></i></button></a>'
                                  }
                                }

                                if(full.salesorder.invoice > 0)
                                {
                                  display += '<a href="/invoicetemplate/'+full.salesorder.SO_Number+'" target="_blank"><button class="btn btn-default btn-xs" title="Invoice" style="width:unset"><i class="fa fa-file"></i></button></a>';
                                  @if($me->Credit_Note)
                                  {
                                   display += '<a href="{{url("creditnotedetail")}}/'+full.soId+'" target="_blank"><button class="btn btn-default btn-xs" title="Credit Note" style="width:unset"><i class="fa fa-reply"></i></button></a>'
                                  }
                                  @endif
                                }


                                return display;
                              }
                            }
                    ],
                                 select: true,
                                 buttons: [
                                 ],
                     });

          oTable1.on( 'order.dt search.dt', function () {
          oTable1.api().column(0, {search:'applied', order:'applied'}).nodes().each( function (cell, i) {
            cell.innerHTML = i+1;
          } );
          } ).api().draw();

            $(".salesordertable thead input").keyup ( function () {
            if ($('#salesordertable').length > 0)
            {
                var colnum=document.getElementById('salesordertable').rows[0].cells.length;
                if (this.value=="[empty]")
                {
                    oTable1.fnFilter( '^$', this.name,true,false );
                }
                else if (this.value=="[nonempty]")
                {
                    oTable1.fnFilter( '^(?=\\s*\\S).*$', this.name,true,false );
                }
                else if (this.value.startsWith("!")==true && this.value.length>1)
                {
                    oTable1.fnFilter( '^(?'+ this.value +').*', this.name,true,false );
                }
                else if (this.value.startsWith("!")==false)
                {
                    oTable1.fnFilter( this.value, this.name,true,false );
                }
            }
        });
        });
      </script>

@endsection

@section('content')

<div class="content-wrapper">
  <!-- Content Header (Page header) -->
  <section class="content-header">
    <h1>Sales Order List<small>Sales Order</small></h1>

    <ol class="breadcrumb">
      <li><a href="{{ url('/home') }}"><i class="fa fa-dashboard"></i> Home</a></li>
      <li><a href="#">Sales Order</a></li>
      <li class="active">Sales Order List</li>
    </ol>
  </section>

  <br>

  <section class="content">
    <div class="row">
      <div class="modal fade" id="Confirm" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
            <div class="modal-dialog" role="document">
              <div class="modal-content">
                <div class="modal-header">
                  <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                  <h4 class="modal-title" id="myModalLabel">Confirm</h4>
                </div>
                <div class="modal-body">
                  <div class="form-group" id="generateinvoice">

                  </div>
                  Are you sure you want to generate Invoice?
                </div>
                <div class="modal-footer">
                  <center><img src="{{ URL::to('/') . "/img/ajax-loader.gif" }}" width="50px" height="50px" alt="Loading" name='ajaxloader3' id="ajaxloader3"></center>
                  <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                  <button type="button" class="btn btn-primary" onclick="generate()">Generate</button>
                </div>
              </div>
            </div>
      </div>

      <div class="modal fade" id="Recurring" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
            <div class="modal-dialog" role="document">
              <div class="modal-content">
                <div class="modal-header">
                  <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                  <h4 class="modal-title" id="myModalLabel">Confirm</h4>
                </div>
                <div class="modal-body">
                  <input type="hidden" id="recurid">
                  Are you sure you want to recur this SO?
                </div>
                <div class="modal-footer">
                  <!-- <center><img src="{{ URL::to('/') . "/img/ajax-loader.gif" }}" width="50px" height="50px" alt="Loading" name='ajaxloader3' id="ajaxloader3"></center> -->
                  <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                  <button type="button" class="btn btn-primary" id="confirmrecur">Confirm</button>
                </div>
              </div>
            </div>
      </div>

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

     <div class="col-md-3">
        <label>Client</label>
      <select class="form-control select2" id="client" name="client">
        <option value="">Select</option>
        @foreach($clients as $c)
          <option value="{{$c->Id}}">{{$c->Company_Name}}</option>
        @endforeach
      </select>
    </div>
    <div class="col-md-3">
        <label>Type</label>
      <select class="form-control select2" id="type" name="type">
        <option value="">Select</option>
        <option value="GSC">GSC</option>
        <option value="GST">GST</option>
      </select>
    </div>
    @if($me->SO_Details)
    <div class="col-md-2">
      <label>All Details</label>
     <div class="input-group">
     <label><input type="checkbox" name="all" id="all" <?php if($detail) echo "checked";?> ></label>
     </div>
    </div>
    @endif
     <div class="col-md-2">
      <label>Refresh</label>
     <div class="input-group">
     <button type="button" id="refresh" class="btn btn-success btn" data-toggle="modal">Refresh</button>
     </div>
     </div>
    </div>
    <br>
    <div class="box">
    <table id="salesordertable" class="salesordertable" cellspacing="0" width="100%" padding="30px" style="font-size: 13px;">
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
     $(".select2").select2();
    $(document).ready(function() {
        $(document).on('click', '#refresh', function(e) {
           var client = $('#client').val() ? $('#client').val() : null;
           var type = $('#type').val() ? $('#type').val() : null;
           var detail = $('#all').is(':checked') ? "true" : "";

            window.location.href ="{{ url("/salesorder") }}/"+ client + "/" + type + "/" + detail;
        });
      });

    $(document).ready(function() {
        $(document).on('click', '.recur', function(e) {
      var id = $(this).attr('id');
      $('#recurid').val(id);
      $('#Recurring').modal('show');

        });
      });

    $(document).ready(function() {
        $(document).on('click', '#confirmrecur', function(e) {
              var id = $('#recurid').val();
              $.ajaxSetup({
                headers: { 'X-CSRF-Token' : $('meta[name=_token]').attr('content') }
                });
              $.ajax({
                  url: "{{ url('/salesorder/manualRecur') }}" + "/" +id ,
                  method: "POST",
                  contentType: false,
                  processData: false,
                  success: function(response){
                    $('#Recurring').modal('hide');
                    if (response==1)
                    {
                      var message="Sales Order Successfully Recurred";
                      $("#update-alert ul").html(message);
                      $("#update-alert").modal('show');
                      window.location.reload();
                    }
                    else
                    {
                      var errormessage="Failed to Recur Sales Order";
                    $("#error-alert ul").html(errormessage);
                    $("#error-alert").modal('show');
                    }
                  }
                });
            });
      });

    $(document).ready(function() {
        $(document).on('click', '#autocreatepdf', function(e) {
              $.ajaxSetup({
                headers: { 'X-CSRF-Token' : $('meta[name=_token]').attr('content') }
                });
              $.ajax({
                  url: "{{ url('/autopdf') }}",
                  method: "Get",
                  success: function(response){
                    if (response==1)
                    {

                    }
                  }

                });
        });
      });

    $(document).ready(function() {
        $(document).on('click', '.generate', function(e) {
      var id = $(this).attr('id');
      var hiddeninput='<input type="hidden" class="form-control" id="generateinvoiceid" value="'+id+'">';
      $( "#generateinvoice" ).html(hiddeninput);
      $('#Confirm').modal('show');

        });
      });

    function generate()
          {
          $("#ajaxloader3").show();
          var rowid = $('#generateinvoiceid').val();
          $.ajaxSetup({
           headers: { 'X-CSRF-Token' : $('meta[name=_token]').attr('content') }
          });
          $.ajax({
                  url: "{{ url('/salesorder/generate') }}" + "/" + rowid,
                  method: "POST",
                  contentType: false,
                  processData: false,
                  // data:rowid,
                  success: function(response){
                    if (response==1)
                    {
                      $('#Confirm').modal('hide');
                      $("#ajaxloader3").hide();
                      window.location.reload();
                    }
                    else
                    {
                      $('#Confirm').modal('hide');
                      $("#ajaxloader3").hide();
                      alert("Failed to generate Invoice");
                    }
        }
      });
        }
      });



  </script>

@endsection
