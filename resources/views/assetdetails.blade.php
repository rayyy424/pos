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

    <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/fixedcolumns/3.2.2/css/fixedColumns.dataTables.min.css">
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
      .red-star{
        color:red;
      }

      .image
        {
          width: 200px;
          height: 150px;
        }

        img:hover{
          margin-right: 200px;
          /*position: absolute;*/
          z-index: 999999;
          transform: scale(2);
      }
      th
      {
        white-space: normal;
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

      <script type="text/javascript" language="javascript" src="https://cdn.datatables.net/fixedcolumns/3.2.2/js/dataTables.fixedColumns.min.js"></script>
      <script type="text/javascript" language="javascript" src="{{ asset('/plugin/js/dataTables.editor.min.js') }}"></script>
      <script type="text/javascript" language="javascript" src="{{ asset('/plugin/datatables/extensions/Autocomplete/editor.autoComplete.js') }}"></script>
      <script type="text/javascript" language="javascript" src="{{ asset('/plugin/datatables/extensions/Select2/editor.select2.js') }}"></script>
      <script type="text/javascript" language="javascript" src="https://cdn.datatables.net/plug-ins/1.10.19/api/sum().js"></script>


      {{-- <script type="text/javascript" language="javascript" src="{{ asset('/plugin/examples/resources/syntax/shCore.js') }}"></script>
      <script type="text/javascript" language="javascript" src="{{ asset('/plugin/examples/resources/demo.js') }}"></script>
      <script type="text/javascript" language="javascript" src="{{ asset('/plugin/examples/resources/editor-demo.js') }}"></script> --}}

      <script type="text/javascript" language="javascript" class="init">

          var asInitVals = new Array();
          var ewallettable;
          var editor
          var userid;

          $(document).ready(function() {
          });
               	</script>

@endsection

@section('content')

    <div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
      <h1>
        Spare Part Details
        <small>Spare Part Management</small>
      </h1>
      <ol class="breadcrumb">
        <li><a href="{{ url('/home') }}"><i class="fa fa-dashboard"></i> Home</a></li>
        <li><a href="#">Spare Part Management</a></li>
        <li class="active">Spare Part Details</li>
      </ol>
    </section>

    <!-- Main content -->
    <section class="content">
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
      <div class="row">
        <div class="col-md-12">
          <div class="box">
            <!-- /.box-header -->
            <div class="box-body">
            	<form enctype="multipart/form-data" id="upload_form" role="form" method="POST" action="" >
                <div class="col-md-12">
              		<span class="label label-primary">{{$item->type}}</span>
              		<input type="hidden" name="id" value="{{$id}}">
              		<input type="hidden" name="type" value="{{$item->type}}">
                </div>
            	<br><br>
            	@if($item->type == "Genset" || $item->type == "GENSET")

               <div class="col-md-4">
                    <table class="table table-bordered">
                        <tbody>
                            <tr>
                                <th>Machinery No:<span class="red-star">*</span></th>
                                <td><input type="text" class="form-control" value="{{$item->machinery_no}}" name="machinery_no" id="machinery_no" required=""></td>
                            </tr>
                            <tr>
                                <th>Supplier:</th>
                                <td>
                                  <select class="select2 form-control" name="supplier" id="supplier">
                                    <option></option>
                                    @foreach($companies as $suppliers)
                                    <option value="{{$suppliers->Id}}" <?php if($suppliers->Id == $item->supplier) ?> >{{$suppliers->Company_Name}}</option>
                                    @endforeach
                                  </select>
                                </td>
                            </tr>
                            <tr>
                                <th>Owner:</th>
                                <td>
                                  <select class="select2 form-control" name="owner" id="owner">
                                    <option></option>
                                    @foreach($internal as $inte)
                                    <option value="{{$inte->Id}}" <?php if($inte->Id == $item->owner) ?> >{{$inte->Company_Name}} - {{$inte->Company_Code}}</option>
                                    @endforeach
                                  </select>
                                </td>
                            </tr>
                            <tr>
                              <th>Engine Model:<span class="red-star">*</span></th>
                              <td>
                                <input type="text" class="form-control" value="{{$item->engine_model}}" name="engine_model" id="engine_model" required="">
                              </td>
                            </tr>
                            <tr>
                              <th>Engine No:<span class="red-star">*</span></th>
                              <td>
                                <input type="text" class="form-control" value="{{$item->engine_no}}" name="engine_no" id="engine_no" required="">
                              </td>
                            </tr>
                            <tr>
                                <th>Width</th>
                                <td><input type="text" class="dimension form-control" value="{{$item->width}}" name="width" id="width"></td>
                            </tr>
                            <tr>
                                <th>Height</th>
                                <td><input type="text" class="dimension form-control" value="{{$item->height}}" name="height" id="height"></td>
                            </tr>
                            <tr>
                                <th>Length</th>
                                <td><input type="text" class="dimension form-control" value="{{$item->length}}" name="length" id="length"></td>
                            </tr>
                        </tbody>
                    </table>
                </div>
                <div class="col-md-4">
                    <table class="table table-bordered">
                        <tbody>
                            <tr>
                                <th>Purchase Date:<span class="red-star">*</span></th>
                                <td><input type="text" class="form-control" value="{{$item->purchase_date}}" name="purchase_date" id="purchase_date" required=""></td>
                            </tr>
                            <tr>
                                <th>Capacity:<span class="red-star">*</span></th>
                                <td><input type="text" class="form-control" value="{{$item->capacity}}" name="capacity" id="capacity" required=""></td>
                            </tr>
                            <tr>
                                <th>Replace Capacity:</th>
                                <td>
                                  <input type="text" class="form-control" value="{{$item->replace_capacity}}" name="replace_capacity" id="replace_capacity">
                                </td>
                            </tr>
                            <tr>
                                <th>Serial No</th>
                                <td>
                                  <input type="text" class="form-control" value="{{$item->serial_no}}" name="serial_no" id="serial_no">
                                </td>
                            </tr>
                            <tr>
                                <th>Alternator Serial No:</th>
                                <td> <input type="text" class="form-control" value="{{$item->alternator_serial_no}}" name="alternator_serial_no" id="alternator_serial_no"></td>
                            </tr>
                            <tr>
                                <th>Rental Rate (Per Month):</th>
                                <td><input type="text" class="form-control" value="{{$item->rental_rate}}" name="rental_rate" id="rental_rate"></td>
                            </tr>
                            <tr>
                              <th>Dimension (&#x33a5;)</th>
                                <td><input type="text" class="dimension form-control" name="dimension" id="dimension"></td>
                            </tr>
                        </tbody>
                    </table>
                </div>

                <div class="col-md-4">
                    <table class="table table-bordered">
                        <tbody>
                            <tr>
                                <th>QR Code:</th>
                                <td style="text-align: center;">
                                  <input type="text" class="form-control" value="{{$item->barcode}}" name="barcode" id="barcode">
                                  @if($file)
                                  <br>
                                  <a href="{{ url($file->Web_Path) }}" class="image" alt="QR Image" download=""><img src="{{ url($file->Web_Path) }}" class="image" alt="Item Image"></a>
                                  <br><br>
                                  @endif
                                  <input type="file" name="qrcode" id="qrcode" accept=".png,.jpg,.jpeg">
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
              @elseif($item->type == "Ats" || $item->type == "ATS")
              <div class="col-md-4">
                    <table class="table table-bordered">
                        <tbody>
                            <tr>
                                <th>Machinery No:<span class="red-star">*</span></th>
                                <td><input type="text" class="form-control" value="{{$item->machinery_no}}" name="machinery_no" id="machinery_no" required=""></td>
                            </tr>
                            <tr>
                                <th>Purchase Date:<span class="red-star">*</span></th>
                                <td><input type="text" class="form-control" value="{{$item->purchase_date}}" name="purchase_date" id="purchase_date" required=""></td>
                            </tr>
                            <tr>
                                <th>Supplier:</th>
                                <td>
                                  <select class="select2 form-control" name="supplier" id="supplier">
                                    <option></option>
                                    @foreach($companies as $suppliers)
                                    <option value="{{$suppliers->Id}}" <?php if($suppliers->Id == $item->supplier) ?> >{{$suppliers->Company_Name}}</option>
                                    @endforeach
                                  </select>
                                </td>
                            </tr>
                            <tr>
                                <th>Owner:</th>
                                <td>
                                  <select class="select2 form-control" name="owner" id="owner">
                                    <option></option>
                                    @foreach($internal as $inte)
                                    <option value="{{$inte->Id}}" <?php if($inte->Id == $item->owner) ?> >{{$inte->Company_Name}} - {{$inte->Company_Code}}</option>
                                    @endforeach
                                  </select>
                                </td>
                            </tr>
                            <tr>
                                <th>Serial No</th>
                                <td>
                                  <input type="text" class="form-control" value="{{$item->serial_no}}" name="serial_no" id="serial_no">
                                </td>
                            </tr>
                            <tr>
                              <th>Engine Model:<span class="red-star">*</span></th>
                              <td>
                                <input type="text" class="form-control" value="{{$item->engine_model}}" name="engine_model" id="engine_model" required="">
                              </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
                <div class="col-md-4">
                    <table class="table table-bordered">
                        <tbody>
                            <tr>
                                <th>QR Code:</th>
                                <td><input type="text" class="form-control" value="{{$item->barcode}}" name="barcode" id="barcode"></td>
                            </tr>
                            <tr>
                                <th>Width</th>
                                <td><input type="text" class="dimension form-control" value="{{$item->width}}" name="width" id="width"></td>
                            </tr>
                            <tr>
                                <th>Height</th>
                                <td><input type="text" class="dimension form-control" value="{{$item->height}}" name="height" id="height"></td>
                            </tr>
                            <tr>
                                <th>Length</th>
                                <td><input type="text" class="dimension form-control" value="{{$item->length}}" name="length" id="length"></td>
                            </tr>
                            <tr>
                              <th>Dimension &#x33a5;</th>
                                <td><input type="text" class="dimension form-control" name="dimension" id="dimension"></td>
                            </tr>
                        </tbody>
                    </table>
                </div>
                <div class="col-md-4">
                    <table class="table table-bordered">
                        <tbody>
                          <tr>
                                <th>QR Image:</th>
                                <td style="text-align: center;">
                                  <!-- <input type="text" class="form-control" value="{{$item->barcode}}" name="barcode" id="barcode"> -->
                                  @if($file)
                                  <br>
                                  <a href="{{ url($file->Web_Path) }}" class="image" alt="QR Image" download=""><img src="{{ url($file->Web_Path) }}" class="image" alt="Item Image"></a>
                                  <br><br>
                                  @endif
                                  <input type="file" name="qrcode" id="qrcode" accept=".png,.jpg,.jpeg">
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            	@elseif($item->type == "Tank" || $item->type == "TANK")
            	<div class="col-md-6 form-group">
            		<div class="col-md-6">
            		<label>Machinery No:</label>
            		<input type="text" class="form-control" value="{{$item->machinery_no}}" name="machinery" id="machinery">
            		</div>
            	</div>
            	<div class="col-md-6 form-group">
            		<div class="col-md-6">
            		<label>Width:</label>
            		<input type="text" class="dimension form-control" value="{{$item->width}}" name="width" id="width">
            		</div>
            	</div>
            	<div class="col-md-6 form-group">
            		<div class="col-md-6">
            		<label>Length:</label>
            		<input type="text" class="dimension form-control" value="{{$item->length}}" name="length" id="length">
            		</div>
            	</div>
            	<div class="col-md-6 form-group">
            		<div class="col-md-6">
            		<label>Height:</label>
            		<input type="text" class="dimension form-control" value="{{$item->height}}" name="height" id="height">
            		</div>
            	</div>
            	<div class="col-md-6 form-group">
            		<div class="col-md-6">
            		<label>Capacity (Litre):</label>
            		<input type="text" class="form-control" name="lit_capacity" id="lit_capacity" readonly="">
            		</div>
            	</div>
            	<div class="col-md-6 form-group">
            		<div class="col-md-6">
            		<label>Litre (Per cm):</label>
            		<input type="text" class="form-control" name="cm_litre" id="cm_litre" readonly="">
            		</div>
            	</div>
            	@endif
            	<br><br>
            	<div class="col-md-6 form-group">
            		<button class="btn btn-primary" id="update" name="update" type="submit">Update</button>
            		<a href="/tools/inventory" class="btn btn-danger">Back</a>
            	</div>
            </form>
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
      <b>Version</b> 2.0.1
    </div>
    <strong>Copyright &copy; 2014-2016 <a href="http://www.softoya.com">TrackerOnTheGo</a>.</strong> All rights
    reserved.
  </footer>

  <script>

    $(function () {

      $(".select2").select2({width:"310px"});
      $('#purchase_date').datepicker({
      autoclose: true,
        format: 'dd-M-yyyy',
    	});
      $( "#upload_form" ).submit(function( event ) {
      event.preventDefault();
      return false;
      });
       var width = $("#width").val();
      var length = $("#length").val();
      var height = $("#height").val();
      var cap = (width*length*height)/1000;
      var cm = (width*length)/1000;
      var di = (width*length*height)/1000000;
      $('#lit_capacity').val(cap);
      $('#cm_litre').val(cm);
      $('#dimension').val(di);
  });

 $(document).ready(function(){
    $(document).on('keyup','.dimension',function(){
      var width = $("#width").val();
      var length = $("#length").val();
      var height = $("#height").val();
      var cap = (width*length*height)/1000;
      var cm = (width*length)/1000;
      var di = (width*length*height)/1000000;
      $('#lit_capacity').val(cap);
      $('#cm_litre').val(cm);
      $('#dimension').val(di);
    });
});

  $(document).ready(function(){
    $(document).on('click','#update',function(){
    	$.ajaxSetup({
         headers: { 'X-CSRF-Token' : $('meta[name=_token]').attr('content') }
      });
    	$("#update").prop('disabled',true);

    	$.ajax({
                  url: "{{ url('/toolsupdate') }}",
                  method: "POST",
                  contentType: false,
                  processData: false,
                  data:new FormData($("#upload_form")[0]),
                  success: function(response){
                    $("#update").prop('disabled',false);
                    if (response==1)
                    {
                    	var message="Details Updated";
                        $("#update-alert ul").html(message);
                        $("#update-alert").modal('show');
                        window.location.reload();
                    }
                    else
                    {
                    	var errormessage=response;
                    	$("#error-alert ul").html(errormessage);
                        $("#error-alert").modal('show');
                    }
                }
        });
    });
});

</script>


@endsection
