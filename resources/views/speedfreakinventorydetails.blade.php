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

	    .dataTables_wrapper .dataTables_scroll div.dataTables_scrollBody th, .dataTables_wrapper .dataTables_scroll div.dataTables_scrollBody td {
	      	white-space: pre-wrap;
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
      		padding-right: 20px;
      		position: absolute;
      		z-index: 999999;
	    	transform: scale(2);
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
      <script type="text/javascript" language="javascript" src="{{ asset('/plugin/datatables/extensions/Select2/editor.select2.js') }}"></script>

      <script type="text/javascript" language="javascript" src="{{ asset('/plugin/js/dataTables.editor.min.js') }}"></script>
      {{-- chart js --}}
      <script type="text/javascript" language="javascript" src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.4.0/Chart.bundle.min.js"></script>

    <script type="text/javascript" language="javascript" class="init">
    var editor;
    var asInitVals = new Array();
	var oTable;
	var balancetable;
	var userid;
	var inventory;

    $(document).ready(function() {

		balancetable =$('#balancetable').dataTable( {
			columnDefs: [
				{ "visible": false, "targets": [1] },
				// {"className": "dt-left", "targets": [4]},
				{"className": "dt-center", "targets": "_all"}
			],
			responsive: false,
			colReorder: false,
			sScrollX: "100%",
			bScrollCollapse: true,
			bAutoWidth: true,
			sScrollY: "100%",
			dom: "frtip",
			iDisplayLength:25,
			columns: [
				{ data: null,"render":"", title:"No"},
				// { data:"speedfreakinventory_history.speedfreakInventoryId", title:'speedfreakId'},
				{ data:"speedfreakinventory_history.Id", title:'Id'},
				{ data:"speedfreakinventory_history.branch", title:'Branch'},
				{ data:"speedfreakinventory_history.total", title:'Balance',
					"render": function ( data, type, full, meta ) {
					return '<a href="/speedfreak/inventoryhistory/'+full.speedfreakinventory_history.branch+'/{{$id}}" target="_blank"><button class="btn btn-default btn-xs" title="History" style="width:unset">'+data+'</button></a>';
					}
				}
			],
			autoFill: {
				editor:  editor,
			},
			select: {
                style:    'os',
                selector: 'td'
            },
		});

		$('#balancetable').on( 'click', 'tr', function () {
			// Get the rows id value
			//  var row=$(this).closest("tr");
			//  var oTable = row.closest('table').dataTable();
			// userid = oTable.api().row( this ).data().speedfreakinventory.Id;
		});

		balancetable.api().on( 'order.dt search.dt', function () {
			balancetable.api().column(0, {search:'applied', order:'applied'}).nodes().each( function (cell, i) {
				cell.innerHTML = i+1;
			} );
		} ).draw();

		$("thead input").keyup ( function () {

			/* Filter on the column (the index) of this element */
			if ($('#balancetable').length > 0)
			{
				var colnum=document.getElementById('balancetable').rows[0].cells.length;
				if (this.value=="[empty]")
				{
					balancetable.fnFilter( '^$', this.name,true,false );
				}
				else if (this.value=="[nonempty]")
				{
					balancetable.fnFilter( '^(?=\\s*\\S).*$', this.name,true,false );
				}
				else if (this.value.startsWith("!")==true && this.value.length>1)
				{
					balancetable.fnFilter( '^(?'+ this.value +').*', this.name,true,false );
				}
				else if (this.value.startsWith("!")==false)
				{
					balancetable.fnFilter( this.value, this.name,true,false );
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
		<h1>SPEED FREAK Inventory Management<small>SPEEDFREAK</small></h1>
		<ol class="breadcrumb">
			<li><a href="{{ url('/home') }}"><i class="fa fa-dashboard"></i>Home</a></li>
			<li><a href="#">SPEEDFREAK</a></li>
            <li>SPEED FREAK Inventory Management</li>
            <li class="active">SPEED FREAK Inventory Details</li>
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
		{{-- stock modal --}}
		<div class="modal fade" id="UpdateStockModal" role="dialog" aria-labelledby="myModalLabel">
            <div class="modal-dialog" role="document">
            	<div class="modal-content">
	                <div class="modal-header">
	                  <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
	                  <h4 class="modal-title" id="myModalLabel">Tools Branch History ([Branch Name])</h4>
	                </div>
	                <div class="modal-body">
		                <table id="stocktable" class="table table-bordered" cellspacing="0" width="100%" padding="30px" style="font-size: 13px; white-space: pre-line;">
			              	<thead>
			                    <tr>
			              			<th></th>
			              			<th></th>
			                    	<th></th>
			              			<th></th>
			              			<th></th>
			              			<th></th>
			              			<th></th>
		                        </tr>
			                </thead>
			                <tbody>
			                	<tr>
			                		<td></td>
			                		<td></td>
			                		<td></td>
			                		<td></td>
			                		<td></td>
			                		<td></td>
			                		<td></td>
			                	</tr>
		                    </tbody>
			            </table>
			        </div>
	            </div>
            </div>
        </div>
		{{-- ./stock modal --}}
		<div class="box">
			<div class="box-body">
				<div class="row">
					<div class="col-lg-12">
						<div class="box-header">
							<h4>SPEED FREAK Details</h4>
						</div>
						<div class="box-body">
							<div class="row">
								<form enctype="multipart/form-data" id="upload_form" role="form" method="POST" action="" >
								@foreach($item as $item)
								<input type="hidden" name="id" value="{{$item->Id}}">
			                    <div class="col-lg-4">
			                        <table class="table table-bordered">
			                            <tbody>
			                                <tr>
			                                    <th>Item Name<span class="red-star">*</span></th>
			                                    <td><input type="text" class="form-control" value="{{$item->name}}" name="item_name" id="item_name"></td>
			                                </tr>
			                                <tr>
			                                    <th>Type<span class="red-star">*</span></th>
			                                    <td>
			                                    <select id="type" name="type" class="form-control">
			                                    	<option></option>
							                  		@foreach($speedfreakinventorytype as $git)
							                  		<option value="{{$git->Option}}" <?php if($git->Option == $item->type) echo "selected"; ?> >{{$git->Option}}</option>
							                  		@endforeach
							                  	</select>
							                  	</td>
			                                    <!-- <td><input type="text" class="form-control" value="{{$item->type}}" name="type" id="type"></td> -->
			                                </tr>
			                                <tr>
			                                    <th>Model<span class="red-star">*</span></th>
			                                    <td>
			                                    	<select name="model" id="model" class="form-control" >
			                                    		<option></option>
								                  		@foreach($speedfreakinventorymodel as $gim)
								                  		<option value="{{$gim->Option}}" <?php if($gim->Option == $item->model) echo "selected"; ?> >{{$gim->Option}}</option>
								                  		@endforeach
								                  	</select>
			                                    </td>
			                                    <!-- <td><input type="text" class="form-control" value="{{$item->model}}" name="model" id="model"></td> -->
			                                </tr>
			                                <tr>
			                                    <th>Chinese Yuan (RMB)</th>
			                                    <td><input type="text" class="form-control" value="{{$item->price_yuan}}" name="price_yuan" id="price_yuan"></td>
			                                </tr>
			                                <tr>
			                                    <th>Supplier</th>
			                                    <td>
			                                    <select class="form-control" name="supplier" id="supplier">
			                              		@foreach($supplier as $s)
			                              		@if($item->supplier == $s->Id)
			                              		<option value="{{$s->Id}}" selected="">{{$s->Company_Name}}
			                              		@else
			                              		<option value="{{$s->Id}}">{{$s->Company_Name}}</option>
			                              		@endif
			                              		@endforeach
			                                    </select>
			                                    	</td>
			                                </tr>
			                                <tr>
			                                	<th>Techician Maximum Hold</th>
			                                	<td>
			                                		<input type="number" name="maxTechhold" value="{{$item->maxTechhold}}" class="form-control">
			                                	</td>
			                                </tr>
			                                <tr>
			                                	<th>Maximum Order</th>
			                                	<td>
			                                		<input type="number" name="maxOrder" value="{{$item->maxOrder}}" class="form-control">
			                                	</td>
			                                </tr>
			                            </tbody>
			                        </table>
			                    </div>
			                    <div class="col-lg-4">
			                    	<table class="table table-bordered">
			                            <tbody>
			                                <tr>
			                                    <th>Website Link</th>
			                                    <td><input type="text" class="form-control" value="{{$item->oem}}" name="oem" id="oem"></td>
			                                </tr>
			                                <tr>
			                                    <th>QR Code<span class="red-star">*</span></th>
			                                    <td><input type="text" class="form-control" value="{{$item->barcode}}" name="barcode" id="barcode"></td>
			                                </tr>
			                                <tr>
			                                    <th>Description<span class="red-star">*</span></th>
			                                    <td><input type="text" class="form-control" value="{{$item->description}}" name="description" id="description"></td>
			                                </tr>
			                                <tr>
			                                    <th>Cost Price (RM)</th>
			                                    <td><input type="text" class="form-control" value="{{$item->latest_price}}" name="price" id="price"></td>
			                                </tr>
			                                <tr>
			                                    <th>Sale Price (RM)</th>
			                                    <td><input type="text" class="form-control" value="{{$item->latest_saleprice}}" name="saleprice" id="saleprice"></td>
			                                </tr>
			                                <tr>
			                                    <th>Balance Quantity</th>
			                                    <td><input type="text" class="form-control" value="{{$item->qty_balance}}" name="qty_balance" id="qty_balance" readonly></td>
			                                </tr>
			                                <tr>
			                                    <th>Low Balance Treshold</th>
			                                    <td><input type="text" class="form-control" value="{{$item->balance_treshold}}" name="balance_treshold" id="balance_treshold"></td>
			                                </tr>
			                                <!-- <tr>
			                                    <th>Rack NO (Location)</th>
			                                    <td><input type="text" class="form-control" value="{{$item->rack_no}}" name="rack_no" id="rack_no"></td>
			                                </tr> -->
			                                <tr>
			                                    <th>Status</th>
			                                    <td><select class="form-control" name="status" id="status">
			                                    	<option value="Active" <?php if ($item->status=="Active") echo "selected";?> >Active</option>
			                                    	<option value="Deactivate" <?php if ($item->status=="Deactivate") echo "selected";?> >Deactivate</option>
			                                    </select>
			                                    </td>
			                                </tr>
			                            </tbody>
			                        </table>
			                        @if($me->Update_Inventory)
			                        <button type="submit" class="btn btn-primary" id="update">Update</button>
			                        @endif
			                    </div>
			                    <div class="col-lg-3">
			                    	<label>Item Image</label>
			                    	@if($image != null)
			                    	<div class="box-tools text-center">
							            <a href="{{ url($image->Web_Path) }}" class="image" alt="QR Image" download=""><img src="{{ url($image->Web_Path) }}" class="image" alt="Item Image"></a>
							        </div>
							        @endif
							        <input type="file" name="image" id="image" accept=".png,.jpg,.jpeg">
			                    </div>
			                    <div class="col-lg-3">
			                    	<label>QR Code Image</label>
			                    	@if($qrcode != null)
			                    	<div class="box-tools text-center">
							            <a href="{{ url($qrcode->Web_Path) }}" class="image" alt="QR Image" download=""><img src="{{ url($qrcode->Web_Path) }}" class="image" alt="QR Image"></a>
							        </div>
							        @endif
			                    	<input type="file" name="qrcode" id="qrcode" accept=".png,.jpg,.jpeg">
			                    </div>
			                    <div class="col-lg-3">
			                    	<label>Store Image</label>
			                    	@if($store != null)
			                    	<div class="box-tools text-center">
							            <a href="{{ url($store->Web_Path) }}" class="image" alt="QR Image" download=""><img src="{{ url($store->Web_Path) }}" class="image" alt="Item Image"></a>
							        </div>
							        @endif
			                    	<input type="file" name="store" id="store" accept=".png,.jpg,.jpeg">
			                    </div>
			                    @endforeach
			                </div>
			                </form>
			<div class="row">
			<div class="col-lg-12">
			<div class="box-header">
			<h4>Branch Balance</h4>
			</div>
			<table id="balancetable" class="balancetable" cellspacing="0" width="100%" padding="30px" style="font-size: 13px; white-space: pre-line;">
			<thead>
			<tr class="search">
		    		@foreach($balance as $key=>$value)

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
                    @foreach($balance as $key=>$value)

                    @if ($key==0)
                    <td></td>

                    @foreach($value as $field=>$value)
                    <td>{{ $field }}</td>
                    @endforeach
                    @endif
                    @endforeach
            </tr>
                            </thead>
                            <tbody>
                                <?php $i = 0; ?>
                                @foreach($balance as $delivery)

                                <tr>
                                    <td></td>

                                    @foreach($delivery as $key=>$value)
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
				</div>
			</div>
		</div>
	</section>
</div>
<footer class="main-footer">
	<div class="pull-right hidden-xs">
		<b>Version</b> 1.0.0
	</div>
	<strong>Copyright &copy; 2014-2016 <a href="http://www.softoya.com">TrackerOnTheGo</a>.</strong> All rights reserved.
</footer>
<script type="text/javascript">
	function updatestock()
    {
        // $("#ajaxloader5").hide();
        // $("#IntId").val($inventoryid);
        // $("#StockId").val($stockid);

        $("#UpdateStockModal").modal("show");
    }
 $(function () {
    $( "#upload_form" ).submit(function( event ) {
      event.preventDefault();
      return false;
    });

    $('#supplier').select2();
});
  $(document).ready(function(){
    $(document).on('click','#update',function(){
    	$("#update").prop('disabled',true);
  $.ajaxSetup({
         headers: { 'X-CSRF-Token' : $('meta[name=_token]').attr('content') }
      });
      $.ajax({
                  url: "{{ url('/speedfreakinventory/edit') }}",
                  method: "POST",
                  contentType: false,
                  processData: false,
                  data:new FormData($("#upload_form")[0]),
                  success: function(response){
                  	$("#update").prop('disabled',false);
                  	if(response == 1)
                  	{
                  		var message="Details Updated!";
                        $("#update-alert ul").html(message);
                        $("#update-alert").modal('show');
                  	}
                  	else
                  	{
                  		errormessage="Failed to Update Details, Please Fill-in All Mandatory Fields";
                        $("#error-alert ul").html(errormessage);
                        $("#error-alert").modal('show');
                  	}

                  }
              });
  });
});
</script>

@endsection
