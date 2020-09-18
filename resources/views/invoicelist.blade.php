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

      .modal-success{
        z-index: 9999999;
      }

      .modal-danger{
        z-index: 9999999;
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
        var invoicetable;
        $(document).ready(function() {
           invoicetable=$('#invoicetable').dataTable({
                          columnDefs: [{ "visible": false, "targets":[1]},{"className": "dt-right", "targets": [-2]},{"className": "dt-center", "targets": [2]}],
                            responsive: false,
                            dom: "Bltp",
                            sScrollX: "100%",
                            sScrollY: "100%",
                            scrollCollapse: true,
                            iDisplayLength:100,
                            bAutoWidth: true,
                            iDisplayLength:10,
                            rowId:"salesorder.Id",
                            columns: [
                            { data : null, "render":"", title: "No"},
                            { data : 'max.maxid', title: "DO Id"}, 
                            { data : 'salesorder.Id', title: "Checkbox",
                              "render": function ( data, type, full, meta ) {
                                  return '<input type="checkbox" class="printcheck" name="printcheck[]" value="'+data+'">';
                              }
                            }, 
                            { data : 'salesorder.invoice_number', title: "Invoice Number"},
                            { data : 'salesorder.combined_invoice_num', title: "Combined Invoice Number"},  
                            { data : 'salesorder.SO_Number', title: "SO Number"}, 
                            { data : 'max.DO_NO', title: "DO Number"}, 
                            { data : 'max.temp_DO', title: "Temporary DO"}, 
                            { data : 'tracker.site', title: "Site"}, 
                            { data : 'companies.Company_Name', title: "Company"},  
                            { data : 'client.Company_Name', title: "Client"},   
                            { data : 'client.type', title: "Type"},  
                            { data : 'salesorder.invoice_date', title: "Invoice Date"},
                            { data : 'salesorder.combined_invoice_date', title: "Combine Invoice Date"},
                            { data : 'salesorder.total_amount', title: "Total (RM)",
                                "render": function ( data, type, full, meta ) {
                                  return parseFloat(data).toFixed(2);
                                }
                            },
                            { data : 'salesorder.combine_remarks', title: "Remarks"},
                            { data : null, title: "Action",
                                "render": function ( data, type, full, meta ) {
                                    return '<a href="/invoicetemplate/'+full.salesorder.SO_Number+'" target="_blank"><button class="btn btn-default btn-xs" title="SO" style="width:unset"><i class="fa fa-paper-plane"></i></button></a>' + 
                                    '<button class="update btn btn-default btn-xs" title="Update" id="'+full.salesorder.Id+'" style="width:unset"><i class="fa fa-edit"></i></button>'+'<button class="donum btn btn-default btn-xs" title="Temp DO" id="'+full.max.maxid+'" do="'+full.max.DO_NO+'" style="width:unset"><i class="fa fa-cogs"></i></button>' + 
                                      '<a href="/invoicetemplate2/'+full.salesorder.Id+'" target="_blank"><button class="btn btn-default btn-xs" title="Print Temp DO" style="width:unset"><i class="fa fa-print"></i></button></a>' +
                                      '<a href="/combineinvoicetemplate/'+full.salesorder.Id+'" target="_blank"><button class="btn btn-default btn-xs" title="Combined Invoice" style="width:unset"><i class="fa fa-sitemap"></i></button></a>'
                                  }
                            }
                             ],
                                 select: true,
                                 buttons: [
                                    {
                                        extend: 'excelHtml5',
                                        exportOptions: {
                                            columns: ':visible'
                                        }
                                    },
                                 ],
                     });

                          invoicetable.api().on( 'order.dt search.dt', function () {
                          invoicetable.api().column(0, {search:'applied', order:'applied'}).nodes().each( function (cell, i) {
                              cell.innerHTML = i+1;
                          } );
                          } ).draw();

                          $(".display thead input").keyup ( function () {

                    /* Filter on the column (the index) of this element */
                    if ($('#invoicetable').length > 0)
                    {

                        var colnum=document.getElementById('invoicetable').rows[0].cells.length;

                        if (this.value=="[empty]")
                        {

                           invoicetable.fnFilter( '^$', this.name,true,false );
                        }
                        else if (this.value=="[nonempty]")
                        {

                           invoicetable.fnFilter( '^(?=\\s*\\S).*$', this.name,true,false );
                        }
                        else if (this.value.startsWith("!")==true && this.value.length>1)
                        {

                           invoicetable.fnFilter( '^(?'+ this.value +').*', this.name,true,false );
                        }
                        else if (this.value.startsWith("!")==false)
                        {

                            invoicetable.fnFilter( this.value, this.name,true,false );
                        }
                    }



            } );
        });
      </script>

@endsection

@section('content')

<div class="content-wrapper">
  <!-- Content Header (Page header) -->
  <section class="content-header">
    <h1>Invoice Lists<small>Invoice Lists</small></h1>

    <ol class="breadcrumb">
      <li><a href="{{ url('/home') }}"><i class="fa fa-dashboard"></i>Home</a></li>
      <li><a href="#">Invoice Lists</a></li>
      <li class="active">Invoice Lists</li>
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
                  <div class="form-group" id="soid">

                  </div>
                  @if($me->Update_Inv_Num)
                  <div class="form-group">
                  <label>Invoice Number:</label>
                  <input type="text" id="invoicenumber" class="form-control">
                  </div>
                  <div class="form-group">
                  <label>Invoice Date:</label>
                  <input type="text" id="invoicedate" class="form-control date">
                  </div>
                  <div class="form-group">
                  <label>Combined Invoice Number:</label>
                  <input type="text" id="cominvoicenumber" class="form-control">
                  </div>
                  <div class="form-group">
                  <label>Combine Invoice Date:</label>
                  <input type="text" id="cominvoicedate" class="form-control date">
                  </div>
                  @endif
                  <div class="form-group">
                  <label>Combined Remarks:</label>
                  <input type="text" id="remarks" class="form-control">
                  </div>
                </div>
                <div class="modal-footer">
                  <!-- <center><img src="{{ URL::to('/') . "/img/ajax-loader.gif" }}" width="50px" height="50px" alt="Loading" name='ajaxloader3' id="ajaxloader3"></center> -->
                  <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                  @if($me->Update_Inv)
                  <button type="button" class="btn btn-primary" id="updatebutton">Update</button>
                  @endif
                </div>
              </div>
            </div>
      </div>

       <div class="modal fade" id="CombineModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
            <div class="modal-dialog" role="document">
              <div class="modal-content">
                <div class="modal-header">
                  <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                  <h4 class="modal-title" id="myModalLabel">Confirm</h4>
                </div>
                <div class="modal-body">
                  Are You Sure You Want to Combine the Invoices?
                </div>
                <div class="modal-footer">
                  <!-- <center><img src="{{ URL::to('/') . "/img/ajax-loader.gif" }}" width="50px" height="50px" alt="Loading" name='ajaxloader3' id="ajaxloader3"></center> -->
                  <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                  <button type="button" class="btn btn-primary" id="confirmcombine">Combine</button>
                </div>
              </div>
            </div>
      </div>

      <div class="modal fade" id="TempDOModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
            <div class="modal-dialog" role="document">
              <div class="modal-content">
                <div class="modal-header">
                  <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                  <h4 class="modal-title" id="myModalLabel">Temporary DO</h4>
                </div>
                <div class="modal-body">
                  <form enctype="multipart/form-data" id="upload_form" role="form" method="POST" action="" >
                  <input type="hidden" id="doid" name="doid" class="form-control">
                  <div class="form-group">
                  <label>DO Number:</label>
                  <input type="text" id="donum" name="donum" readonly class="form-control">
                  <label>Temporary DO Number:</label>
                  <input type="text" id="tempdo" name="tempdo" class="form-control">
                  </div>
                  </form>
                </div>
                <div class="modal-footer">
                  <!-- <center><img src="{{ URL::to('/') . "/img/ajax-loader.gif" }}" width="50px" height="50px" alt="Loading" name='ajaxloader3' id="ajaxloader3"></center> -->
                  <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                  <button type="button" class="btn btn-primary" id="updatetempdo">Update</button>
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

       <div class="row">
            <div class="col-md-4">
                <div class="input-group">
                    <div class="input-group-addon">
                        <i class="fa fa-clock-o"></i>
                    </div>
                    <input type="text" class="form-control" id="range" name="range">
                </div>
            </div>
            <div class="col-md-2">
                <div class="input-group">
                    <button type="button" class="btn btn-success btn" data-toggle="modal" onclick="refresh();">Refresh</button>
                </div>
            </div>
        </div>
        <br>
        <div class="row">
        <div class="col-md-2">
      <a class="form-group" href="{{route('ARInvoiceExcel.excel')}}" title="Export"><button><i class="fa fa-upload" aria-hidden="true"></i>  Export</button></a>
      </div>
        <!-- </div> -->
      <form action="{{ url('ARInvoiceExcel/import') }}" method="POST" enctype="multipart/form-data">
                {{ csrf_field() }}
          <div class="row">
            <div class="col-md-4">
              <input type="file" name="file">
              <button id="import" title="Import"><i class="fa fa-download" aria-hidden="true"></i>  Import</button>
            </div>
          </div>
        </div>
     </form>
     <br>
     <div class="row">
            <div class="col-md-4">
              <button class="btn" id="print" name="print" title="Batch Print"><i class="fa fa-print" aria-hidden="true"></i> Print</button>

              <button class="btn" id="combine" name="combine" title="Combine"><i class="fa fa-plus" aria-hidden="true"></i> Combine</button>
            </div>
      </div>
      <br>
      <table id="invoicetable" class="display" cellspacing="0" width="100%" padding="30px" style="font-size: 13px;">
                       <thead>
                                <tr class="search">
                                @foreach($details as $key=>$value)

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
                                    @foreach($details as $key=>$value)

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
                                @foreach($details as $delivery)

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

    $( "#upload_form" ).submit(function( event ) {
      event.preventDefault();
      return false;
    });

      $('.date').datepicker({
      autoclose: true,
        format: 'dd-M-yyyy',
      });

      $('#range').daterangepicker({locale: {
      format: 'DD-MMM-YYYY'
    }
      @if($start && $end)
    ,startDate: '{{$start}}',
    endDate: '{{$end}}'
        @endif
        });
  });

  function refresh()
{
    var d=$('#range').val();
    var arr = d.split(" - ");

    window.location.href ="{{ url('/invoicelist') }}/"+arr[0]+"/"+arr[1];
}

$(document).ready(function() {
    $(document).on('click', '.update', function(e) {
        var id = $(this).attr('id');
        $.ajaxSetup({
           headers: { 'X-CSRF-Token' : $('meta[name=_token]').attr('content') }
          });
          $.ajax({
                  url: "{{ url('/invoicelist/getInvoiceNumber') }}" + "/" + id,
                  method: "POST",
                  contentType: false,
                  processData: false,
                  success: function(response){
                    $('#invoicenumber').val(response.item.invoice_number);
                    $('#cominvoicenumber').val(response.item.combined_invoice_num);
                    $('#remarks').val(response.item.combine_remarks);
                    $('#invoicedate').val(response.item.invoice_date);
                    $('#cominvoicedate').val(response.item.combined_invoice_date);
                  }

                });

        var hiddeninput='<input type="hidden" class="form-control" id="updatesoid" value="'+id+'">';
        $( "#soid" ).html(hiddeninput);
        $('#Confirm').modal('show');
    });
  });

$(document).ready(function() {
    $(document).on('click', '#updatebutton', function(e) {
          var id = $('#updatesoid').val();
          var inv = $('#invoicenumber').val();
          var cinv = $('#cominvoicenumber').val();
          var remarks = $('#remarks').val();
          var date = $('#invoicedate').val();
          var cdate = $('#cominvoicedate').val();
          $.ajaxSetup({
           headers: { 'X-CSRF-Token' : $('meta[name=_token]').attr('content') }
          });
          $.ajax({
                  url: "{{ url('/invoicelist/updateInvoiceNumber') }}",
                  method: "POST",
                  data: {Id:id , Inv:inv, Cinv:cinv, remarks:remarks, date:date, cdate:cdate},
                  success: function(response){
                    $('#Confirm').modal('hide');
                    if(response == 1)
                    {
                      var message="Invoice Number Updated!";
                      $("#update-alert ul").html(message);
                      $("#update-alert").modal('show');
                      window.location.reload();
                    }
                    else
                    {
                      var errormessage="Failed to Update Invoice Number!";
                      $("#error-alert ul").html(errormessage);
                      $("#error-alert").modal('show');
                    }
                    
                  }
          });          

         });
  });

$(document).ready(function() {
    $(document).on('click', '.donum', function(e) {
        $('#doid').val($(this).attr('id'));
        $('#donum').val($(this).attr('do'));
        $('#TempDOModal').modal('show');
    });
  });

$(document).ready(function() {
    $(document).on('click', '#print', function(e) {

            var checked = [];
            var table = $('#invoicetable').dataTable();
            var rowcollection = table.$(".printcheck:checked", {"page": "all"});
            rowcollection.each(function(index,elem){
                checked.push($(elem).val());
            });

            // $.each($("input[name='printcheck[]']:checked"), function(){
            //     checked.push($(this).val());
            // });
            $.ajaxSetup({
             headers: { 'X-CSRF-Token' : $('meta[name=_token]').attr('content') }
            });
            $.ajax({
                    url: "{{ url('/invoicelist/batchprint') }}",
                    method: "POST",
                    data: {Id:checked},
                    success: function(response){
                        response.files.forEach(function(element) {
                          window.open(''+element.Web_Path+'','_blank').print();
                        });
                    }

                  });
    
    });
  });
$(document).ready(function() {
    $(document).on('click', '#combine', function(e) {
        $('#CombineModal').modal('show');
    });
  });
$(document).ready(function() {
    $(document).on('click', '#confirmcombine', function(e) {

            var checked = [];
            var table = $('#invoicetable').dataTable();
            var rowcollection = table.$(".printcheck:checked", {"page": "all"});
            rowcollection.each(function(index,elem){
                checked.push($(elem).val());
            });

            $.ajaxSetup({
             headers: { 'X-CSRF-Token' : $('meta[name=_token]').attr('content') }
            });
            $.ajax({
                    url: "{{ url('/invoicelist/combineinvoice') }}",
                    method: "POST",
                    data: {Id:checked},
                    success: function(response){
                      $('CombineModal').modal('hide');
                    if(response == 1)
                    {
                      var message="Invoices Combined!";
                      $("#update-alert ul").html(message);
                      $("#update-alert").modal('show');
                      window.location.reload();
                    }
                    else
                    {
                      var errormessage="Failed to Combine Invoices!";
                      $("#error-alert ul").html(errormessage);
                      $("#error-alert").modal('show');
                    }
                    }

                  });
    });
  });

$(document).ready(function() {
    $(document).on('click', '#updatetempdo', function(e) {
          var data = $('#upload_form').serialize();
            $.ajaxSetup({
             headers: { 'X-CSRF-Token' : $('meta[name=_token]').attr('content') }
            });
            $.ajax({
                    url: "{{ url('/invoicelist/updateTempDONumber') }}",
                    method: "POST",
                    data: data,
                  success: function(response){
                    $('#TempDOModal').modal('hide');
                      if(response == 1)
                      {
                        var message="Invoice Number Updated!";
                        $("#update-alert ul").html(message);
                        $("#update-alert").modal('show');
                        window.location.reload();
                      }
                    else
                      {
                        $("#error-alert ul").html(response);
                        $("#error-alert").modal('show');
                      }
                  }
                });
        });
  });
</script>

@endsection