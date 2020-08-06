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

     .fa-close{
      color: red;
     }

     .fa-edit{
      color:blue;
     }

     #itemtable td:nth-child(1) , #itemtable th:nth-child(1) {
      display:none;
     }

     .paddingtop{
      padding-top: 10px;
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
        var oTable;

        $(document).ready(function() {
          
        });
      </script>

@endsection

@section('content')

<div class="content-wrapper">
  <!-- Content Header (Page header) -->
  <section class="content-header">
    <h1>Credit Note Details<small>Credit Note</small></h1>
    <ol class="breadcrumb">
      <li><a href="{{ url('/home') }}"><i class="fa fa-dashboard"></i> Home</a></li>
      <li><a href="#">Credit Note</a></li>
      <li class="active">Credit Ndte Details</li>
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
                  <div id="modaltext">
                    
                  </div>
                </div>
                <div class="modal-footer">
                  <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                  <button type="button" class="btn btn-primary" onclick="ConfirmSave()">Confirm</button>
                </div>
              </div>
            </div>
      </div> 

            <div class="modal fade" id="ActionModal" role="dialog" aria-labelledby="editModalLabel" style="display: none;">
            <div class="modal-dialog" role="document">
              <div class="modal-content">
                <div class="modal-header">
                  <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true"></span></button>
                  <h4 class="modal-title" id="ItemListModalLabel">Details</h4>
                </div>
                <div class="modal-body">
                  <input type="hidden" name="rowid" id="rowid" value="-1">
                  <input type="hidden" name="cnitemid_edit" id="cnitemid_edit">
                  <label>Tax Code</label>
                  <input type="text" name="tax_edit" id="tax_edit" class="formdata form-control">
                  <label>Description</label>
                  <input type="text" name="desc_edit" id="desc_edit" class="formdata form-control">
                  <label>Original Amount(RM)</label>
                  <input type="number" name="amount_edit" id="amount_edit" class="formdata form-control">
                  <label>Knockoff Amount(RM)</label>
                  <input type="number" name="knockoff_edit" id="knockoff_edit" class="formdata form-control">
                </div>
                <div class="modal-footer">
                  <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                  <button type="button" class="btn btn-primary" id="update" onclick="Insert()">Update</button>
                </div>
              </div>
            </div>
      </div>

      <div class="box">
        <div class="box-body">
          <form id="upload_form">
            {!! csrf_field() !!}
              <div class="row">
                <div class="col-md-6">
                  <input type="hidden" name="cnid" value="{{$inv->Id}}">
                  <input type="hidden" name="soid" value="{{$id}}">
                  <div class="col-md-6">
                    <label>Credit Note No</label>
                    <input type="text" name="cn_no" id="cn_no" class="form-control" value="{{$inv->cn_no}}" <?php if(!$inv->Id) echo "readonly"; ?> >
                  </div>
                  <div class="col-md-6">
                    <label>Date</label>
                    <input type="text" name="cn_date" id="cn_date" class="date form-control" value="{{$inv->date}}">
                  </div>
                  <div class="col-md-6">
                    <label>Attention To</label>
                    <input type="text" name="attn" id="attn" class="form-control" value="{{$inv->attn}}">
                  </div>
                  <div class="col-md-6">
                    <label>Term</label>
                    <input type="text" name="term" id="term" class="form-control" value="{{$inv->term}}">
                  </div>
                  <div class="col-md-12">
                  <label>Reason</label>
                  <textarea class="form-control" name="reason" id="reason">{{$inv->reason}}</textarea>
                  </div>

                </div>
                <div class="col-md-6">

                  <div class="col-md-6">
                    <label>Invoice No</label>
                    <input type="text" name="invoice_no" id="invoice_no" class="form-control" value="{{$inv->invoice_number}}" readonly="">
                  </div>
                  <div class="col-md-6">
                    <label>Invoice Date</label>
                    <input type="text" name="invoice_date" id="invoice_date" class="form-control" value="{{$inv->invoice_date}}" readonly="">
                  </div>
                  <div class="col-md-6">
                    <label>Company</label>
                    <input type="text" name="company" id="company" class="form-control" value="{{$inv->Company_Name}}" readonly="">
                  </div>
                  <div class="col-md-6">
                    <label>Client</label>
                    <input type="text" name="client" id="client" class="form-control" value="{{$inv->client}}" readonly="">
                  </div>

                </div>
              </div>


              <button type="button" class="btn btn-primary" style="margin-top: 10px" onclick="showModal()">Add Item</button>
              <div class="paddingtop row">
                <div class="col-md-12">
                  <table id="itemtable" class="table table-bordered">
                    <col width="15%">
                    <col width="50%">
                    <col width="15%">
                    <col width="15%">
                    <col width="5%">
                    <thead>
                      <th></th>
                      <th>Tax Code</th>
                      <th>Description</th>
                      <th>Org Amount</th>
                      <th>Knock-Off Amount</th>
                      <th>Action</th>
                    </thead>
                    <tbody>
                      @foreach($item as $item)
                      <tr>
                        <td>{{$item->Id}} <input type="hidden" name="cnitemid[]" value="{{$item->Id}}"> </td>
                        <td>{{$item->item_no}} <input type="hidden" name="tax[]" value="{{$item->item_no}}"> </td>
                        <td>{{$item->description}} <input type="hidden" name="desc[]" value="{{$item->description}}"> </td>
                        <td>{{$item->amount}} <input type="hidden" name="amount[]" value="{{$item->amount}}"> </td>
                        <td>{{$item->knockoff}} <input type="hidden" name="knockoff[]" value="{{$item->knockoff}}"> </td>
                        <td><span><i class="fa fa-edit" onclick="Edit(this)"></i></span> &nbsp <span><i class="fa fa-close" onclick="Delete(this)"></i></span></td>
                      </tr>
                      @endforeach
                    </tbody>
                    <tfoot>
                      
                    </tfoot>
                  </table>
                </div>
              </div>
                <button type="button" class="btn btn-success" onclick="Save()">Save</button>
                @if($inv->creditnote)
                <a href="{{url('creditnotetemplate')}}/{{$inv->Id}}" target="_blank"><button type="button" class="btn btn-success">Print</button></a>
                @endif
          </form>
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
        $( "form" ).submit(function( event ) {
          event.preventDefault();
          return false;
        });
        $('.date').datepicker({
          autoclose: true,
          format: 'dd-M-yyyy',
        });
    });
    function showModal()
    {
      $('.formdata').val('');
      $('#rowid').val(-1);
      $('#cnitemid_edit').val(0);
      $('#ActionModal').modal('show');
    }

    function Calculate()
    {
      var qty = $('#qty_edit').val() ? $('#qty_edit').val() : 0;
      var price = $('#price_edit').val() ? $('#price_edit').val() : 0;
      var disc = $('#disc_edit').val() ? $('#disc_edit').val() : 0;
      var amount =  (qty * (price - disc) );
      $('#amount_edit').val(amount);
    }

    function Edit(ele)
    {
      var row = $(ele).closest('tr');
      $('#rowid').val(row.index());
      $('#cnitemid_edit').val(trimme(row,0));
      $('#tax_edit').val(trimme(row,1));
      $('#desc_edit').val(trimme(row,2));
      $('#amount_edit').val( parseFloat(trimme(row,3)) );
      $('#knockoff_edit').val( parseFloat(trimme(row,4)) );
      $('#ActionModal').modal('show');
    }

    function trimme(row,index)
    {
      var value = $.trim( $('td',row).eq(index).text() );
      return value;
    }

    function Insert()
    {
      $('#ActionModal').modal('hide');
      var rowid = $('#rowid').val();
      var cnitemid = $('#cnitemid_edit').val();
      var desc = $('#desc_edit').val();
      var tax = $('#tax_edit').val();
      var amount = parseFloat($('#amount_edit').val()).toFixed(2);
      var knockoff = parseFloat($('#knockoff_edit').val()).toFixed(2);

      var tBody = $("#itemtable > TBODY")[0];
      console.log(rowid);
      if(rowid > -1)
      {
        $('#itemtable > Tbody').find('tr').eq(rowid).remove();
      }

      row = tBody.insertRow(rowid);

      var cell = $(row.insertCell(-1));
      cell.html( cnitemid + "<input type='hidden' name='cnitemid[]' value="+cnitemid+">" )

      var cell = $(row.insertCell(-1));
      cell.html( tax + "<input type='hidden' name='tax[]' value="+tax+">" )

      var cell = $(row.insertCell(-1));
      cell.html( desc + "<input type='hidden' name='desc[]' value='"+desc+"'>" )

      var cell = $(row.insertCell(-1));
      cell.html( amount + "<input type='hidden' name='amount[]' value="+amount+">" )

      var cell = $(row.insertCell(-1));
      cell.html( knockoff + "<input type='hidden' name='knockoff[]' value="+knockoff+">" )

      var cell = $(row.insertCell(-1));
      cell.html('<span><i class="fa fa-edit" onclick="Edit(this)"></i></span> &nbsp <span><i class="fa fa-close" onclick="Delete(this)"></i></span>');
    }

    function Delete(ele)
    {
      var row = $(ele).closest('tr').index() + 1;
      var Id = parseInt($('#itemtable').find('tr').eq(row).find('td:first').text());

      if(Id > 0)
      {
        $.ajaxSetup({
           headers: { 'X-CSRF-Token' : $('meta[name=_token]').attr('content') }
        });
        $.ajax({
            url: "{{url('deletecreditnoteitem')}}/" + Id ,
            method: "POST"
          });
      }

      $('#itemtable').find('tr').eq(row).remove();
    }

    function Save()
    {
      var text = "Save the changes?"
      $('#modaltext').html(text)
      $('#Confirm').modal('show');
    }

    function ConfirmSave()
    {
      $('#Confirm').modal('hide');
      var myurl = "{{url('savecreditnote')}}";
      var form = "upload_form";
      var modal = "ActionModal";
      var button = "";
      var type = "update";
      PostAjax(myurl,form,modal,button,type);
    }

    function PostAjax(myurl,form,modal,button,type)
    {
      var message = type == "create" ? "Item Created" : "Item Updated";
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
                    if(response == 1)
                    {
                      $("#"+button).prop('disabled',false);
                      $("#update-alert ul").html(message);
                      $("#update-alert").modal('show');
                      window.location.reload();
                    }
                    else
                    {
                      $('html, body').animate({scrollTop: '0px'}, 500);
                      var obj = jQuery.parseJSON(response);
                      var errormessage ="";
                      for (var item in obj) {
                        errormessage=errormessage + "<li> " + obj[item] + "</li>";
                      }
                      $("#error-alert ul").html(errormessage);
                      $("#error-alert").modal('show');
                    }
                  },
                  error: function(response)
                  {
                        $('html, body').animate({scrollTop: '0px'}, 500);
                        var obj = jQuery.parseJSON(response);
                        var errormessage ="";
                        for (var item in obj) {
                          errormessage=errormessage + "<li> " + obj[item] + "</li>";
                        }
                        $("#error-alert ul").html(errormessage);
                        $("#error-alert").modal('show');
                  }
          });
    }
  </script>

@endsection