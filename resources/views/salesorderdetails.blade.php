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
       .select-editable {
         position:relative;
         background-color:white;
         border:solid grey 1px;
         width:120px;
         height:18px;
     }
     .select-editable select {
         position:absolute;
         top:0px;
         left:0px;
         font-size:14px;
         border:none;
         width:120px;
         margin:0;
     }
     .select-editable input {
         position:absolute;
         top:0px;
         left:0px;
         width:100px;
         padding:1px;
         font-size:12px;
         border:none;
     }
     .select-editable select:focus, .select-editable input:focus {
         outline:none;
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
        var logtable;

        $(document).ready(function() {

          logtable=$('#logtable').DataTable({
                          columnDefs: [{ "visible": false, "targets":[1]},{"className": "dt-center", "targets": "_all"}],
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
                            { data : 'salesorderdetails.Id', title: "Id"},
                            { data : 'salesorderdetails.details', title: "Details"},
                            { data : 'salesorderdetails.created_at', title: "Action Time"},
                            { data : 'users.Name', title: "Action Taken By"}
                           
                    ],
                                 select: true,
                                 buttons: [
                                 ],
                     });

                          logtable.on( 'order.dt search.dt', function () {
                          logtable.column(0, {search:'applied', order:'applied'}).nodes().each( function (cell, i) {
                              cell.innerHTML = i+1;
                          } );
                          } ).draw();

                          $("#ajaxloader3").hide();
                          $("#ajaxloader4").hide();
          
        });
      </script>

@endsection

@section('content')

<div class="content-wrapper">
  <!-- Content Header (Page header) -->
  <section class="content-header">
    <h1>Sales Order Details<small>Sales Order</small></h1>
    <ol class="breadcrumb">
      <li><a href="{{ url('/home') }}"><i class="fa fa-dashboard"></i> Home</a></li>
      <li><a href="#">Sales Order</a></li>
      <li class="active">Sales Order Details</li>
    </ol>
  </section>

  <br>

  <section class="content">
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
       <div class="modal fade" id="Confirm" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
            <div class="modal-dialog" role="document">
              <div class="modal-content">
                <div class="modal-header">
                  <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                  <h4 class="modal-title" id="myModalLabel">Confirm</h4>
                </div>
                <div class="modal-body">
                  <div class="form-group" id="generateso">

                  </div>
                  Are you sure you want to generate Sales Order?
                </div>
                <div class="modal-footer">
                  <center><img src="{{ URL::to('/') . "/img/ajax-loader.gif" }}" width="50px" height="50px" alt="Loading" name='ajaxloader3' id="ajaxloader3"></center>
                  <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                  <button type="button" class="btn btn-primary" id="generatebutton">Generate</button>
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
                  Are you sure you want to delete this?
                </div>
                <div class="modal-footer">
                  <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                  <button type="button" class="btn btn-primary" id="confirmDelete">Delete</button>
                </div>
              </div>
            </div>
      </div> 
      <div class="modal fade" id="ConfirmInvoice" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
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
                  <center><img src="{{ URL::to('/') . "/img/ajax-loader.gif" }}" width="50px" height="50px" alt="Loading" name='ajaxloader4' id="ajaxloader4"></center>
                  <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                  <button type="button" class="btn btn-primary" onclick="generate()">Generate</button>
                </div>
              </div>
            </div>
      </div>
        <div class="box box-solid">

                <div class="box-header with-border">
                  <div class="box-body">
                <form enctype="multipart/form-data" id="upload_form" role="form" method="POST" action="" >
                    <div class="row">
                      <input type="hidden" id="trackerid" name="trackerid" value="{{$trackerid}}">
                      @if($details != null)
                      <input type="hidden" id="salesorderid" name="salesorderid" value="{{$details->Id}}">
                      @endif
                      <div class="col-md-4">
                        <label>Site:</label>
                        <input class="form-control" type="textarea" name="site" value="{{$site->site}}" readonly="">
                        </select>
                    </div>
                    <div class="col-md-4">
                        <label>Company:</label>
                        <select class="form-control select2" name="company" id="company">
                          @if($details != null)
                          @foreach ($company as $c)
                            @if($c->Id == $details->companyId)
                            <option value="{{$c->Id}}" selected="">{{$c->Company_Name}}-{{$c->Company_Code}}</option>
                            @else
                            <option value="{{$c->Id}}">{{$c->Company_Name}}-{{$c->Company_Code}}</option>
                            @endif
                          @endforeach
                          @else
                          <option value="" selected="">None</option>
                          @foreach ($company as $c)
                          <option value="{{$c->Id}}">{{$c->Company_Name}}-{{$c->Company_Code}}</option>
                          @endforeach
                          @endif
                        </select>
                    </div>
                    <div class="col-md-4">
                        <label>Client:</label>
                        <select class="form-control select2" name="client" id="client">
                          @if($details !=null)
                          @foreach ($client as $cli)
                            @if($cli->Id == $details->clientId)
                            <option value="{{$cli->Id}}" selected="">{{$cli->Company_Name}}-{{$cli->Company_Code}}</option>
                            @else
                            <option value="{{$cli->Id}}">{{$cli->Company_Name}}-{{$cli->Company_Code}}</option>
                            @endif
                          @endforeach
                          @else
                          <option value="" selected="">None</option>
                          @foreach ($client as $cli)
                          <option value="{{$cli->Id}}">{{$cli->Company_Name}}-{{$cli->Company_Code}}</option>
                          @endforeach
                          @endif
                        </select>
                    </div>
                    <div class="col-md-4">
                        <label>Date:</label>
                        <div class="input-group date">
                            <div class="input-group-addon">
                              <i class="fa fa-calendar"></i>
                            </div>
                            @if($details != null)
                             <input type="text" autocomplete="off" class="form-control pull-right" id="date" name="date" value="{{$details->date ? : 0}}">
                             @else
                             <input type="text" autocomplete="off" class="form-control pull-right" id="date" name="date">  
                             @endif
                          </div>
                    </div>
                    <div class="col-md-4">
                        <label>Rental Start:</label>
                        <div class="input-group date">
                            <div class="input-group-addon">
                              <i class="fa fa-calendar"></i>
                            </div>
                            @if($details != null)
                             <input type="text" autocomplete="off" class="form-control pull-right" id="rentalstart" name="rentalstart" value="{{$details->rental_start ? : 0}}">
                             @else
                             <input type="text" autocomplete="off" class="form-control pull-right" id="rentalstart" name="rentalstart">  
                             @endif
                          </div>
                    </div>
                    <div class="col-md-4">
                        <label>Rental End:</label>
                        <div class="input-group date">
                            <div class="input-group-addon">
                              <i class="fa fa-calendar"></i>
                            </div>
                            @if($details != null)
                             <input type="text" autocomplete="off" class="form-control pull-right" id="rentalend" name="rentalend" value="{{$details->rental_end ? : 0}}">
                             @else
                             <input type="text" autocomplete="off" class="form-control pull-right" id="rentalend" name="rentalend">  
                             @endif
                          </div>
                    </div>
                    <div class="col-md-4">
                        <label>PO Number:</label>
                        @if($details != null)
                             <input type="textarea" class="form-control" name="po" id="po" value="{{$details->po}}">
                             @else
                             <input type="textarea" class="form-control" name="po" id="po""> 
                             @endif
                    </div>
                    <div class="col-md-4">
                        <label>Term:</label>
                        @if($details != null)
                             <select  class="form-control" name="term" id="term">
                              @foreach($term as $t)
                                @if($details->term == $t->Option)
                                <option value="{{$details->term}}" selected="">{{$details->term}}</option>
                                @else
                                <option value="{{$t->Option}}">{{$t->Option}}</option>
                                @endif
                              @endforeach
                             </select>
                             @else
                             <select  class="form-control" name="term" id="term">
                              @foreach($term as $t)
                               <option value="{{$t->Option}}">{{$t->Option}}</option>
                              @endforeach
                             </select>
                             @endif
                    </div>
                    <div class="col-md-4">
                        <label>Remarks:</label>
                        @if($details != null)
                        <input  class="form-control" type="text" name="remarks" value="{{$details->remarks ? : ''}}">
                        @else
                        <input  class="form-control" type="text" name="remarks" value="">
                        @endif
                    </div>
                  </div>
                  <div class="form-group">
                        <label>Items:</label>
                        <table id="itemtable" class="table table-bordered table-hover">
                          <thead align="center">
                            <tr bgcolor="#0d2244" style="color:white;" >
                              <th><label>Item No.</label></th>
                              <th><label>Item Description</label></th>
                              <th><label>Item Unit</label></th>
                              <th><label>Quantity</label></th>
                              <th><label>Price</label></th>
                              <th><label>Action</label></th>
                            </tr>
                          </thead>
                          <tbody>
                            @if($details != null)
                            @foreach($existitem as $k)
                            <tr>
                              <td><input type="text" class="form-control" name="item_no[]" value="{{$k->item_no}}"></td>
                              <td><input type="text" class="form-control" name="description[]" value="{{$k->description}}"></td>
                              <td><input type="text" class="form-control" name="unit[]" value="{{$k->unit}}"></td>
                              <td><input type="text" class="form-control" name="quantity[]" value="{{$k->qty}}"></td>
                              <td><input type="text" class="form-control" name="price[]" value="{{$k->price}}"></td>
                              <td><input type="text" class="remove btn btn-danger" value="Remove"></td>
                            </tr>
                            @endforeach
                            @endif
                          </tbody>
                          <tfoot>
                            <tr>
                              <td><input type="text" class="item_no form-control" placeholder="Item Number" id="item_no"></td>
                              <td>
                                <select class="description form-control select2tag" id="description">
                                  <option value="" disabled selected>None</option>
                                  @foreach ($item as $items)
                                      <option value="{{$items->description}}" data-unit="{{$items->unit}}" data-price="{{$items->price}}" data-no="{{$items->item_no}}" >{{$items->description}}</option>
                                  @endforeach
                                </select>
                                <i class="fa fa-trash" style="color:red" title="Delete" id="deleteoption"></i>
                              </td>
                              <td>
                                <input type="text" class="unit form-control" id="unit">
                              </td>
                              <td>
                                <input type="text" class="form-control" placeholder="Qty" id="quantity">
                              </td>
                              <td>
                                <input type="text" class="price form-control" id="price">
                              </td>
                              <td>
                                <div>
                            <input type="button" id="addItem" class="btn btn-success" value="Add">
                                </div>
                              </td>
                            </tr>
                          </tfoot>
                        </table>
                      </div>
                        <div class="form-group">
                          @if($details == null || $details == "")
                          <button type="submit" class="btn btn-primary" id="save">Save Details</button>
                          @else
                          <button type="submit" class="btn btn-primary" id="save2">Save Details</button>
                          @endif
                        </div>
                        </div>
                      <!-- /.box-body -->
            </form>
            <hr>
            @if($details != null)
                          <!-- @if($details->sales_order > 0 && $details->do == 1 && $details->invoice == 0)
                          <button class="generateinvoice btn btn-primary" id="{{$details->trackerid}}">Generate Invoice</button>
                          @else
                          <a href="{{url('/invoicetemplate')}}/{{$details->Id}}" target="_blank" class="btn btn-primary">Print Invoice</a>
                          <br><br>
                          @endif -->
                          @if($recur->recurring == 0)
                          <button class="btn btn-primary" id="activate">Re-Activate SO</button>
                          @endif
                          @if($details->sales_order == 0)
                          <button class="generate btn btn-primary" id="{{$details->trackerid}}">Generate SO</button>
                          <br><br>
                          @else
                          <a href="{{url('/salesordertemplate')}}/{{$details->Id}}" target="_blank" class="btn btn-primary">Print SO</a>
                          <br><br>
            @endif
            <table id="logtable" class="display" cellspacing="0" width="100%" padding="30px" style="font-size: 13px;">
                    <thead>
                        {{-- prepare header search textbox --}}
                          <tr>
                            @foreach($log as $key=>$value)

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
                        @if(count($log))

                        <?php $i = 0; ?>
                        @foreach($log as $mydeliveries)

                          <tr id="row_{{ $i }}">
                              <td></td>
                              @foreach($mydeliveries as $key=>$value)
                                <td>
                                  {{ $value }}
                                </td>
                              @endforeach

                          </tr>
                          <?php $i++; ?>
                        @endforeach

                        @endif

                    </tbody>
                      <tfoot></tfoot>
                  </table>
                @endif
                </div>
                </div>

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
      $( "#upload_form" ).submit(function( event ) {
      event.preventDefault();
      return false;
    });
    $('#date').datepicker({
      autoclose: true,
        format: 'dd-M-yyyy',
    });
    $('#rentalstart').datepicker({
      autoclose: true,
        format: 'dd-M-yyyy',
    });
    $('#rentalend').datepicker({
      autoclose: true,
        format: 'dd-M-yyyy',
    });
    $(".select2").select2();
    $(".select2tag").select2({
          tags: true
        });
    if($('#date').val() == null || $('#date').val() == "" || $('#date').val() == 0)
    {
    $('#date').datepicker('setDate', 'today');
    }


        $(document).ready(function() {
        $(document).on('change', '.Item', function(e) {
        var element = $(this).find('option:selected');

        var Id = $(this).val();
        var unit = element.data("unit");
        var price = element.data("price");
        var itemno = element.data("no");
         $('#unit').val(unit);
         $('#price').val(price);
         $('#item_no').val(itemno);
         var desc = element.data("description");
         if($('#description').val()!= Id){
         $('#description').val(Id).change();
          }
         e.stopPropagation();
    });
});
    $(document).ready(function() {
    $(document).on('change', '.description', function(e) {
        var element = $(this).find('option:selected');
        var Id = $(this).val(); 
         var code = element.data("code");
         var unit = element.data("unit");
         var price = element.data("price");
         var itemno = element.data("no");
         $('#unit').val(unit);
         $('#price').val(price);
         $('#item_no').val(itemno);
         if($('#item').val()!=Id){
         $('#item').val(Id).change();
        }
         e.stopPropagation();
    });
});

    $(document).ready(function() {
    $(document).on('click', '#addItem', function(e) {

            AddRow($("#item_no").val(),$("#description :selected").text(),$("#unit").val(),$("#quantity").val(),$("#price").val());
            $("#item_no").val("");
            $("#item").val("").change();
            $("#description").val("");
            $("#unit").val("");
            $("#price").val("");
            $("#quantity").val("");
            // $("#purpose"+numId).val("");
    });
});
        function AddRow(itemno,description,unit,quantity,price) {
            var tBody = $("#itemtable > TBODY")[0];
            //Add Row.
            row = tBody.insertRow(-1);
            //Add Item cell.
            var cell = $(row.insertCell(-1));
            cell.html("<input type='type' class='form-control' name='item_no[]' value='"+itemno+"'>");
            //Add Description cell.
            cell = $(row.insertCell(-1));
            cell.html("<input type='type' class='form-control' name='description[]' value='"+description+"'>");
            //Add Unit cell
            cell = $(row.insertCell(-1));
            cell.html("<input type='type' class='form-control' name='unit[]' value='"+unit+"'>");
            //Add Quantity cell.
            cell = $(row.insertCell(-1));
            cell.html("<input type='type' class='form-control' name='quantity[]' value='"+quantity+"'>");
            //Add Price cell.
            cell = $(row.insertCell(-1));
            cell.html("<input type='type' class='form-control' name='price[]' value='"+price+"'>");
            cell = $(row.insertCell(-1));
            var btnRemove = $("<input />");
            btnRemove.attr("type", "button");
            btnRemove.attr("class","remove btn btn-danger");
            // btnRemove.attr("onclick", "Remove(this);");
            btnRemove.val("Remove");
            cell.append(btnRemove);
        };
        // function Remove(button) {
        //     var row = $(button).closest("TR");
        //     var name = $("TD", row).eq(0).html();
        //     if (confirm("Do you want to delete: " + name)) {
        //         $(row).remove();
        //     }
        // };
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
    $(document).on('change', '#client', function(e) {
        var element = $(this).find('option:selected');
        var Id = $(this).val(); 
         $.ajaxSetup({
           headers: { 'X-CSRF-Token' : $('meta[name=_token]').attr('content') }
          });
          $.ajax({
                  url: "{{ url('/getsalesorderitem') }}",
                  method: "GET",
                  data: {clientId:Id},
                  success: function(response){
                    $('#description').empty();
                  response.item.forEach(function(element) {
                   $('#description').append("<option value='"+element.description+"' data-unit='"+element.unit+"' data-price='"+element.price+"'>"+element.description+"</option>");
              // }
            });
        }
      });
    });
});
//     $(document).ready(function() {
//     $(document).on('change', '#description', function(e) {
//         // var element = $(this).find('option:selected');
//         var Id = $('#client').val(); 
//          $.ajaxSetup({
//            headers: { 'X-CSRF-Token' : $('meta[name=_token]').attr('content') }
//           });
//           $.ajax({
//                   url: "{{ url('/getsalesorderitem') }}",
//                   method: "GET",
//                   data: {clientId:Id},
//                   success: function(response){
//                     $('#description').empty();
//                   response.item.forEach(function(element) {
//                    $('#description').append("<option value='"+element.description+"' data-unit='"+element.unit+"' data-price='"+element.price+"'>"+element.description+"</option>");
//               // }
//             });
//         }
//       });
//     });
// });

 $(document).ready(function() {
        $(document).on('click', '#save', function(e) {

          $("#save").prop('disabled',true);
          $.ajaxSetup({
           headers: { 'X-CSRF-Token' : $('meta[name=_token]').attr('content') }
          });
          $.ajax({
                  url: "{{ url('/salesorderdetails/save') }}",
                  method: "POST",
                  contentType: false,
                  processData: false,
                  data:new FormData($("#upload_form")[0]),
                  success: function(response){
                    $("#save").prop('disabled',false);
                    if (response==1)
                    {
                         $("#company").val("").change();
                         $("#client").val("").change();
                         document.getElementById("po").value = ''
                         document.getElementById("date").value = ''
                         document.getElementById("rentalstart").value = ''
                         document.getElementById("rentalend").value = ''
                         $('#itemtable > tbody').empty();
                         var message="Sales Order Details Saved";
                         $("#update-alert ul").html(message);
                         $("#update-alert").modal('show');
                         window.location.reload();
                    }
                    else {
                        $('html, body').animate({scrollTop: '0px'}, 500);
                        // var stringified = JSON.stringified(response)
                        // console.log(response)
                        var obj = jQuery.parseJSON(response);
                        // console.log(obj);
                        var errormessage ="";
                        for (var item in obj) {
                          errormessage=errormessage + "<li> " + obj[item] + "</li>";
                        }
                        $("#error-alert ul").html(errormessage);
                        $("#error-alert").modal('show');
                        // $("#ajaxloader4").hide();
                    }
        }
      });
        });
      });

  $(document).ready(function() {
        $(document).on('click', '.generateinvoice', function(e) {
      var id = $(this).attr('id');
      var hiddeninput='<input type="hidden" class="form-control" id="generateinvoiceid" value="'+id+'">';
      $( "#generateinvoice" ).html(hiddeninput);
      $('#ConfirmInvoice').modal('show');

        });
      });

    function generate()
          {
          $("#ajaxloader4").show();
          var rowid = $('#generateinvoiceid').val();
          $.ajaxSetup({
           headers: { 'X-CSRF-Token' : $('meta[name=_token]').attr('content') }
          });
          $.ajax({
                  url: "{{ url('/salesorder/generateinvoice') }}" + "/" + rowid,
                  method: "POST",
                  contentType: false,
                  processData: false,
                  // data:rowid,
                  success: function(response){
                    if (response==1)
                    {
                      $('#ConfirmInvoice').modal('hide');
                      $("#ajaxloader4").hide();
                      window.location.reload();
                    }
                    else
                    {
                      $('#ConfirmInvoice').modal('hide');
                      $("#ajaxloader4").hide();
                      alert("Failed to generate Invoice");
                    }
        }
      });
        }
    $(document).ready(function() {
        $(document).on('click', '.generate', function(e) {
      var id = $(this).attr('id');
      var hiddeninput='<input type="hidden" class="form-control" id="generatesoid" name="generatesoid" value="'+id+'">';
      $( "#generateso" ).html(hiddeninput);
      $('#Confirm').modal('show');

        });
      });

    $(document).ready(function() {
        $(document).on('click', '#generatebutton', function(e) {
          $("#ajaxloader3").show();
          var rowid = $('[name="generatesoid"]').val();
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
                      alert("Sales Order Generated");
                      $('#Confirm').modal('hide');
                      $("#ajaxloader3").hide();
                      window.location.reload();
                    }
                    else
                    {
                      $('#Confirm').modal('hide');
                      $("#ajaxloader3").hide();
                      alert("Failed to generate Sales Order");
                    }
        }
      });
        });
      });

     $(document).ready(function() {
        $(document).on('click', '#activate', function(e) {
          $("#activate").prop('disabled',true);
          var id = $("#trackerid").val();
          var soid = $("#salesorderid").val();
          $.ajaxSetup({
           headers: { 'X-CSRF-Token' : $('meta[name=_token]').attr('content') }
          });
          $.ajax({
              url: "{{ url('/salesorderdetails/activate') }}",
              method: "POST",
              data: {Id:id, soid: soid},
              success: function(response){
                  if(response == 1)
                  {
                    $("#activate").prop('disabled',false);
                    var message="Sales Order Recurring Re-Activated!";
                    $("#update-alert ul").html(message);
                    $("#update-alert").modal('show');
                    window.location.reload();
                  }
                  else
                  {
                    $("#activate").prop('disabled',false);
                    var errormessage = "Failed to Re-Activate Recurring"
                    $("#error-alert ul").html(errormessage);
                    $("#error-alert").modal('show');
                  }
              }
          });

        });
      });
$(document).ready(function() {
        $(document).on('click', '#deleteoption', function(e) {
            var desc = $('#description :selected').val();
            if(desc != "")
            {
              $('#Delete').modal('show');
            }
        });
      });
$(document).ready(function() {
        $(document).on('click', '#confirmDelete', function(e) {
             var desc = $('#description :selected').val();
             $.ajaxSetup({
              headers: { 'X-CSRF-Token' : $('meta[name=_token]').attr('content') }
              });
              $.ajax({
                  url: "{{ url('/salesorderdetails/deleteoption') }}",
                  method: "POST",
                  data: {desc:desc},
                  success: function(response){
                    $('#Delete').modal('hide');
                    if(response == 1)
                    {
                      $("#description option[value='" + desc + "']").remove();
                    }
                  } 
                });
        });
      });
 $(document).ready(function() {
        $(document).on('click', '#save2', function(e) {

          $("#save2").prop('disabled',true);
          $.ajaxSetup({
           headers: { 'X-CSRF-Token' : $('meta[name=_token]').attr('content') }
          });
          $.ajax({
                  url: "{{ url('/salesorderdetails/save2') }}",
                  method: "POST",
                  contentType: false,
                  processData: false,
                  data:new FormData($("#upload_form")[0]),
                  success: function(response){
                    $("#save2").prop('disabled',false);
                    if (response==1)
                    {
                         $("#company").val("").change();
                         $("#client").val("").change();
                         document.getElementById("po").value = ''
                         document.getElementById("date").value = ''
                         document.getElementById("rentalstart").value = ''
                         document.getElementById("rentalend").value = ''
                         $('#itemtable > tbody').empty();
                         var message="Sales Order Details Saved";
                         $("#update-alert ul").html(message);
                         $("#update-alert").modal('show');
                         window.location.reload();
                    }
                    else {
                        $('html, body').animate({scrollTop: '0px'}, 500);
                        // var obj = jQuery.parseJSON(response);
                        // // console.log(obj);
                        // var errormessage ="";
                        // for (var item in obj) {
                        //   errormessage=errormessage + "<li> " + obj[item] + "</li>";
                        // }
                        var errormessage = response;
                        $("#error-alert ul").html(errormessage);
                        $("#error-alert").modal('show');
                        // $("#ajaxloader4").hide();
                    }
        }
      });
        });
      });
  });
  </script>

@endsection