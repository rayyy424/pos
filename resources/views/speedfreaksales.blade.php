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
	<script type="text/javascript" language="javascript" src="https://cdn.datatables.net/plug-ins/1.10.19/api/sum().js"></script>

	<script type="text/javascript" language="javascript" src="{{ asset('/plugin/js/dataTables.editor.min.js') }}"></script>
	{{-- chart js --}}
	<script type="text/javascript" language="javascript" src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.4.0/Chart.bundle.min.js"></script>

    <script type="text/javascript" language="javascript" class="init">

	var editor; // use a global for the submit and return data rendering in the examples
	var asInitVals = new Array();
	var oTable;
	var userid;
	var inventory;

	$(document).ready(function() {

		 // editor = new $.fn.dataTable.Editor( {
   //                              ajax: "{{ asset('/Include/speedfreakinventory.php') }}",
   //                               table: "#inventorytable",
   //                               idSrc: "speedfreakinventory.Id",
   //                               fields: [
   //                                       {
   //                                               label: "Id",
   //                                               name: "speedfreakinventory.Id",
   //                                               type: "hidden"
   //                                       },
   //                                       {
   //                                               label: "Name:",
   //                                               name: "speedfreakinventory.name"
   //                                       },
   //                                       {
   //                                               label: "Type:",
   //                                               name: "speedfreakinventory.type"
   //                                       },
   //                                       {
   //                                               label: "Barcode:",
   //                                               name: "speedfreakinventory.barcode"
   //                                       },
   //                                       {
   //                                               label: "Model:",
   //                                               name: "speedfreakinventory.model"
   //                                       },
   //                                       {
   //                                               label: "Price:",
   //                                               name: "inventorypricehistory.price"
   //                                       },
   //                                       {
   //                                               label: "Supplier",
   //                                               name: "speedfreakinventory.supplier",
   //                                               type:  'select2',
   //                                               options: [
   //                                              	@foreach($supplier as $sup)
   //                                                  { label :"{{$sup->Company_Name}}", value: "{{$sup->Id}}"},
   //                                              	@endforeach
   //                                              ],
   //                                       },
   //                                       {
   //                                               label: "Balance",
   //                                               name: "speedfreakinventory.qty_balance"
   //                                       },
   //                                       {
   //                                               label: "Status",
   //                                               name: "speedfreakinventory.status",
   //                                               type:"hidden"
   //                                       }
   //                               ]
   //                       } );
		   // editor.on( 'preSubmit', function ( e, o, action ) {
		   // 			var name = this.field('speedfreakinventory.name');
		   // 			var type = this.field('speedfreakinventory.type');
		   // 			var barcode = this.field('speedfreakinventory.barcode');

		   // 			if( name.val() == "" || name.val() == null )
		   // 			{
		   // 				name.error("Please insert the name");
		   // 			}
		   // 			else if( type.val() == "" || type.val() == null )
		   // 			{
		   // 				type.error("Please insert the type");
		   // 			}
		   // 			else if( barcode.val() == "" || barcode.val() == null )
		   // 			{
		   // 				barcode.error("Please insert the barcode");
		   // 			}
		   // 			if ( this.inError() ) {
     //                      return false;
     //                  }
		   // });
		oTable=$('#inventorytable').dataTable( {
			columnDefs: [
				{ "visible": false, "targets": [1,11] },
				// {"className": "dt-left", "targets": [4]},
				{"className": "dt-center", "targets": "_all"},
				{ "width": "40%", "targets": [2,7] }
			],
			responsive: false,
            // dom: "Bltp",
            sScrollX: "100%",
            sScrollY: "100%",
            scrollCollapse: true,
            iDisplayLength:100,
            bAutoWidth: true,
            iDisplayLength:10,
             fnInitComplete: function(oSettings, json) {
                        var api = this.api();
                        var total = 0.0;
                        var grand = 0.0;
                        api.rows().every( function (){
                       	var d = this.data();
                    	console.log(d);
                        total = d.inventorysalesprice.price * d.speedfreakinventory_history.qty;
                        grand = grand + total;
                    	});
                        console.log(grand);
                       $("#total").html("RM" + parseFloat(grand).toLocaleString("en"));

             },
            "drawCallback":  function( settings ) {
            			var api = this.api();
                        var total = 0.0;
                        var grand = 0.0;
                        api.rows({search:"applied"}).every( function (){
                       	var d = this.data();
                        total = d.inventorysalesprice.price * d.speedfreakinventory_history.qty;
                        grand = grand + total;
                    	});
                        console.log(grand);
                       $("#total").html("RM" + parseFloat(grand).toLocaleString("en"));
                        
                },
			columns: [
				{ data: null,"render":"", title:"No"},
				{ data:'speedfreakinventory.Id',title:'Id'},
				{ data:'speedfreakinventory.name',title:'Name'},
				{ data:'speedfreakinventory.type',title:'Type'},
				{ data:'speedfreakinventory.barcode',title:'Barcode'},
				{ data:'speedfreakinventory.model',title:'Model'},
				{ data:'inventorysalesprice.price',title:'Price (RM)'},
				{ data:'companies.Company_Name',title:'Supplier', editField:'speedfreakinventory.supplier'},
				// { data:'speedfreakinventory.branch',title:'Branch'},
				{ data:'speedfreakinventory_history.qty',title:'Sale'},
				// { data:'users.Name',title:'Technician'},
				{ data:'speedfreakinventory.status',title:'Status',
					render: function ( data, type, full, meta ) {
						if(full.speedfreakinventory.status == "Active")
						{
							return '<span class="label label-success">'+data+'</span>';
						}
						else
						{
							return '<span class="label label-danger">'+data+'</span>'
						}
					}
				},
				{ title:'Total Amount(RM)',
					render: function ( data, type, full, meta ) {
						var price = full.inventorysalesprice.price;
						var qty = full.speedfreakinventory_history.qty;
						var total = price * qty;

						return parseFloat(total).toFixed(2);
					}
				},
				{ data:null, title:"Action",
					render: function ( data, type, full, meta ) {
							return '<a  href="{{ url("speedfreak/inventorydetails")}}/'+full.speedfreakinventory.Id+'"" target="_blank"><button class="btn btn-default btn-xs" title="Details" style="width:unset"><i class="fa fa-edit"></i></button></a>'+ 
								'<button class="btn btn-default btn-xs" onclick="OpenModal('+meta.row+')" title="Stock Management" style="width:unset"><i class="fa fa-cog"></i></button>'+
								// '<a  href="{{ url("speedfreak/inventorydetails")}}/'+full.speedfreakinventory.Id+'"" target="_blank"><button class="btn btn-default btn-xs" title="Stock-out" style="width:unset"><i class="fa fa-minus-circle"></i></button></a>'+
								'<button class="btn btn-default btn-xs" title="Delete" style="width:unset" onclick="OpenDeleteDialog('+full.speedfreakinventory.Id+')"><i class="fa fa-close"></i></button></a>'
							;
						}
				}
			],
			autoFill: {
				editor : editor
			},
			select: {
                style:    'os',
                selector: 'td'
            },
    //         buttons: [
				// {
    //                              text: 'New Item',
    //                              action: function ( e, dt, node, config ) {
    //                               // clearing all select/input options
    //         //                       editor
    //         //                          .create('Create New Item', {
				// 				    //    label: "Submit",
				// 				    //    fn: function () { this.submit(); }
				// 				    // }, true )
    //         //                          .set('speedfreakinventory.status',"Active")
    //         						$('#NewItemModal').modal('show');
    //                               },
    //             },            
    //                              ],
		});

		$('#inventorytable').on( 'click', 'tr', function () {
			// Get the rows id value
			 var row=$(this).closest("tr");
			 // var oTable = row.closest('table').dataTable();
			// var rowid = oTable.api().row( this ).data().speedfreakinventory.Id;
		});

		oTable.api().on( 'order.dt search.dt', function () {
			oTable.api().column(0, {search:'applied', order:'applied'}).nodes().each( function (cell, i) {
				cell.innerHTML = i+1;
			} );
		} ).draw();

		$("thead input").keyup ( function () {

			/* Filter on the column (the index) of this element */
			if ($('#inventorytable').length > 0)
			{
				var colnum=document.getElementById('inventorytable').rows[0].cells.length;
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
	} );
	</script>

@endsection

@section('content')

<div class="content-wrapper">
	<!-- Content Header (Page header) -->
	<section class="content-header">
		<h1>Speed Freak Inventory Management<small>SpeedFreak</small></h1>

		<ol class="breadcrumb">
			<li><a href="{{ url('/home') }}"><i class="fa fa-dashboard"></i>Home</a></li>
			<li><a href="#">Speed Freak</a></li>
			<li class="active">Speed Freak Inventory Management</li>
		</ol>
	</section>

	<br>

	<section class="content">

	 @if (session('alert'))
	 <div id="alert" class="alert alert-success">
	    {{ session('alert') }} <i class="fa fa-times" aria-hidden="true" onclick="$('#alert').hide()" style="float:right;"></i>
	 </div>
     @endif

     @if (session('error'))
     <div id="warning" class="alert alert-danger">
        {{ session('error') }} <i class="fa fa-times" aria-hidden="true" onclick="$('#warning').hide()" style="float:right;"></i>
     </div>
      @endif

      <div class="modal fade" id="Delete" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
            <div class="modal-dialog" role="document">
              <div class="modal-content">
                <div class="modal-header">
                  <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                  <h4 class="modal-title" id="myModalLabel">Delete</h4>
                </div>
                <div class="modal-body">
                  <div class="form-group" id="deleteitem">

                  </div>
                  Are you sure you want to delete this item?
                </div>
                <div class="modal-footer">
                  <!-- <center><img src="{{ URL::to('/') . "/img/ajax-loader.gif" }}" width="50px" height="50px" alt="Loading" name='ajaxloader3' id="ajaxloader3"></center> -->
                  <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                  <button type="button" class="btn btn-primary" onclick="deleteitem()">Delete</button>
                </div>
              </div>
            </div>
      </div>

      <div class="modal fade" id="NewItemModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
            <div class="modal-dialog" role="document">
              <div class="modal-content">
                <div class="modal-header">
                  <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                  <h4 class="modal-title" id="myModalLabel">Create</h4>
                </div>
                <div class="modal-body">
                	<form id="newitemform">
                  	<label>Name</label>
	                  	<input type="text" class="form-control" name="name" required="">
	                  	<label>Type</label>
	                  	<select name="type" class="form-control" required="">
	                  		<option></option>
	                  		@foreach($speedfreakinventorytype as $git)
	                  		<option value="{{$git->Option}}">{{$git->Option}}</option>
	                  		@endforeach
	                  	</select>
	                  	<label>Barcode / QR Code</label>
	                  	<input type="text" class="form-control" name="barcode" required="">
	                  	<label>Model</label>
	                  	<select name="model" class="form-control" required="" >
	                  		<option></option>
	                  		@foreach($speedfreakinventorymodel as $gim)
	                  		<option value="{{$gim->Option}}">{{$gim->Option}}</option>
	                  		@endforeach
	                  	</select>
	                  	<label>Price</label>
	                  	<input type="number" class="form-control" name="price">
	                  	<label>Supplier</label>
	                  	<select class="form-control" name="supplier">
	                  		@foreach($supplier as $s)
	                  		<option value="{{$s->Id}}">{{$s->Company_Name}}</option>
	                  		@endforeach
	                  	</select>
	                  	<label>Balance</label>
	                  	<input type="number" name="balance" class="form-control">
	                  	<label>Max Order Quantity</label>
	                  	<input type="number" name="maxOrder" class="form-control">
	                  	<label>Max Technician Hold</label>
	                  	<input type="number" name="maxTechhold" class="form-control">
                  	</form>
                </div>
                <div class="modal-footer">
                  <!-- <center><img src="{{ URL::to('/') . "/img/ajax-loader.gif" }}" width="50px" height="50px" alt="Loading" name='ajaxloader3' id="ajaxloader3"></center> -->
                  <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                  <button type="button" class="btn btn-primary" onclick="itemcreate()">Create</button>
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
		{{-- stock modal --}}
		<div class="modal fade" id="UpdateStockModal" role="dialog" aria-labelledby="myModalLabel">
            <div class="modal-dialog" role="document">
            	<div class="modal-content">
	                <div class="modal-header">
	                  <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
	                  <h4 class="modal-title" id="myModalLabel">Update Stock</h4>
	                </div>
	                <div class="modal-body" >
		                <div id="success-alert_updatestock" class="alert alert-success alert-dismissible" style="display:none;">
		                    <button type="button" class="close" onclick="$('#success-alert_updatestock').fadeOut()" aria-hidden="true">&times;</button>
		                    <h4><i class="icon fa fa-check"></i> Alert!</h4>
		                    <ul></ul>
		                </div>
		                <div id="warning-alert_updatestock" class="alert alert-warning alert-dismissible" style="display:none;">
		                    <button type="button" class="close" onclick="$('#warning-alert_updatestock').fadeOut()">&times;</button>
		                    <h4><i class="icon fa fa-ban"></i> Alert!</h4>
		                    <ul></ul>
		                </div>
		                {{-- <center>
		                	<img src="{{ URL::to('/') ."/img/ajax-loader.gif" }}" width="50px" height="50px" alt="Loading" name='ajaxloader5' id="ajaxloader5">
		                </center> --}}
		                <form class="form-horizontal" id="formUpdateStock">
		                    <input type="hidden" name="IntId" id="IntId" class="form-control input-sm">
		                    <input type="hidden" name="StockId" id="StockId" class="form-control input-sm">

		                    <div class="col-sm-12">
			                    <div class="form-group">
			                        <label>Process: </label>
			                        <select class="form-control select2" id="Process" name="Process" style="width: 100%;">
			                            <option></option>
			                            <option>Stock In</option>
			                            <option>Stock Out</option>
			                            <option>Stock Return</option>
			                            <option>Stock Out Branch</option>
		                            </select>
			                    </div>
		                    </div>

		                    <div class="col-sm-12">
		                    	<div class="form-group">
			                        <label>Item Name:</label>
			                        <input type="text" id="item_name" name="item_name" placeholder="Item Name" class="form-control input-sm" readonly="">
			                    </div>
			                </div>

			                <div class="col-sm-12">
		                    	<div class="form-group" id="qb">
			                        <label>Quantity Balance:</label>
			                        <input type="text" id="qty_balance" name="qty_balance" placeholder="Quantity Balance" class="form-control input-sm" readonly="">
			                    </div>
			                </div>

			                <div class="col-sm-12">
		                    	<div class="form-group" id="bob">
			                        <label>Branch Out Balance:</label>
			                        <input type="text" id="branch_out_balance" name="branch_out_balance" placeholder="Branch Out Balance" class="form-control input-sm" readonly="">
			                    </div>
			                </div>
			                
			                <div class="col-sm-12">
		                    	<div class="form-group" id="bib">
			                        <label>Branch In Balance:</label>
			                        <input type="text" id="branch_in_balance" name="branch_in_balance" placeholder="Branch In Balance" class="form-control input-sm" readonly="">
			                    </div>
			                </div>

		                    <div class="col-sm-12" id="branch_sec">
			                    <div class="form-group">
			                        <label>Branch: </label>
			                        <select class="form-control select2" name="branch" id="branch" style="width: 100%">
			                        	<option value="">Select Branch</option>
				                        @foreach ($branch as $branches)
				                        <option value="{{$branches->Option}}">{{$branches->Option}}</option>
				                        @endforeach
				                    </select>
				                </div>
				            </div>

		                    <div class="col-sm-12" id="qty_in">
		                    	<div class="form-group">
		                    		<label>Quantity In: </label>
			                        <input type="text" name="qty_in" placeholder="Quantity In" class="form-control input-sm">
		                    		{{-- <select class="form-control select2" id="quantity_in" name="quantity_in" style="width: 100%;">
			                            <option></option>
			                            <option value="">Please select</option>
			                            <option value=""></option>
			                        </select> --}}
			                    </div>
			                </div>

			                <div class="col-sm-12" id="technician">
			                	<div class="form-group">
			                		<label>Technician: </label>
			                		<select class="form-control select2" id="technician" name="technician" style="width: 100%;">
			                            <option value="">Technician</option>
			                            @foreach($technician as $t)
		                                <option value="{{$t->Id}}">{{$t->Name}}</option>
		                                @endforeach
		                            </select>
		                        </div>
		                    </div>

		                    <div class="col-sm-12" id="qty_out">
		                    	<div class="form-group">
		                    		<label>Quantity Out: </label>
			                        <input type="text" name="qty_out" placeholder="Quantity Out" class="form-control input-sm">
		                    		{{-- <select class="form-control select2" id="quantity_out" name="quantity_out" style="width: 100%;">
			                            <option></option>
			                            <option>Lost</option>
			                            <option>Extra</option>
		                            </select> --}}
			                    </div>
			                </div>

			                <div class="col-sm-12" id="qty_return">
			                	<div class="form-group">
			                        <label>Quantity Return:</label>
			                        <input type="text" name="qty_return" placeholder="Quantity Return" class="form-control input-sm">
			                    </div>
			                </div>

				            <div class="col-sm-12" id="branch_out">
			                    <div class="form-group">
			                        <label>Branch Out: </label>
			                        <select class="form-control select2" name="branch_out" id="branchout" style="width: 100%">
			                        	<option value="">Select Branch Out</option>
				                        @foreach ($branch as $b)
				                        <option value="{{$b->Option}}">{{$b->Option}}</option>
				                        @endforeach
				                    </select>
				                </div>
				            </div>
				            
			                <div class="col-sm-12" id="branch_in">
			                    <div class="form-group">
			                        <label>Branch In: </label>
			                        <select class="form-control select2" name="branch_in" id="branchin" style="width: 100%">
			                        <option value="">Select Branch In</option>
				                        @foreach ($branch as $b)
				                        <option value="{{$b->Option}}">{{$b->Option}}</option>
				                        @endforeach
				                    </select>
				                </div>
				            </div>


				            <div class="col-sm-12" id="qty_branch_out">
			                    <div class="form-group">
			                        <label>Stock Out Quantity: </label>
			                        <input type="number" name="qty_branch_out" placeholder="Stock Out Quantity" class="form-control input-sm">
				                </div>
				            </div>

			                <div class="col-sm-12" id="remark">
			                    <div class="form-group">
			                        <label>Remark:</label>
			                        <textarea type="text" name="remark" placeholder="Remark" class="form-control input-sm"></textarea>
			                    </div>
			                </div>

			                <button type="button" class="btn btn-success" id="btnStockCheckAdd" onClick="updatestockcount()">Update</button>
			                <button type="button" class="btn btn-default pull-right" data-dismiss="modal">Close</button>
			            </form>
			        </div>
	            </div>
            </div>
        </div>
		{{-- ./stock modal --}}

		<div class="modal fade" id="importModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
			<div class="modal-dialog" role="document">
				<div class="modal-content">
					<form action="{{ url('importspeedfreakinventory') }}" method="POST" enctype="multipart/form-data">
						{{ csrf_field() }}
						<div class="modal-header">
							<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
					        <h4 class="modal-title" id="myModalLabel">Import Inventory Items</h4>
					    </div>
					    <div class="modal-body">
					    	<a href="{{url('exportspeedfreakinventory')}}"><i class="fa fa-file-excel-o"> Export Template</i></a>
					        <div class="form-group">
					            <label class="control-label">CSV File:</label>
					            <input type="hidden" name="importbranch" id="importbranch" value="{{$currentbranch}}">
					            <input type="file" name="import" class="form-control" required>
					        </div>
					        <p class="help-block">This will import CSV data into inventory table.</p>
					    </div>
					    <div class="modal-footer">
					    	<button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
					    	<button type="submit" class="btn btn-success" >Import</button>
					    </div>
					</form>
				</div>
			</div>
		</div>

		<div class="row">
	        <div class="col-md-10"> 
	            <div class="col-md-3">
	               	<div class="input-group">
		               	<label>Branch:</label>
		               	<select class="form-control" name="filter" id="filter">
		               		<option value="">All</option>
		               		@foreach ($branch as $b)
		               		<option value="{{$b->Option}}" <?php if($b->Option == $currentbranch) echo "selected";?> >{{$b->Option}}</option>
		               		@endforeach
		               	</select>
	               	</div>
	            </div>
		        <div class="col-md-3">
			        <div class="input-group">
			          	<label>Date:</label>
			            <!-- <div class="input-group-addon">
			              <i class="fa fa-clock-o"></i>
			            </div> -->
			            <input type="text" class="form-control" id="range" name="range">
			        </div>
		        </div>
	            <div class="col-md-2">
	                <div class="input-group">
	                 	<br>
	                   	<button type="button" class="btn btn-success btn" data-toggle="modal" onclick="refresh();">Refresh</button>
	                </div>
	            </div>
	            <br>
		        <div class="col-md-3">
					<h4 class="pull-right">Total: <span id="total">RM 0.00</span> </h4>
				</div>
			</div>
		</div>
        <br><br>
		<div class="row">
	        <div class="box">
	          <div class="box-body">
	            <div class="col-md-12">
	            	{{-- <button class="btn btn-success" type="button">Penang Store</button>
	            	<button class="btn btn-success" type="button">HQ Store</button>
	            	<button class="btn btn-warning" type="button">Reset</button> --}}
		        	<button class="btn btn-success pull-right" type="button" data-toggle="modal" data-target="#importModal">Import</button><br><br>
	            	<table id="inventorytable" class="inventorytable" cellspacing="0" width="100%" padding="30px" style="font-size: 13px; white-space: pre-line;">
		              	<thead>
		              		<tr class="search">
		              		@foreach($speedfreaksales as $key=>$value)

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
                                    @foreach($speedfreaksales as $key=>$value)

                                        @if ($key==0)
                                        <td></td>

                                        @foreach($value as $field=>$value)
                                        <td>
                                            {{ $field }}
                                        </td>
                                        @endforeach
                                        <td></td>
                                        <td></td>
                                        @endif

                                    @endforeach
                                </tr>
                            </thead>
                            <tbody>
                                <?php $i = 0; ?>
                                @foreach($speedfreaksales as $delivery)

                                <tr id="{{$i}}">
                                    <td></td>      

                                    @foreach($delivery as $key=>$value)
                                      <td>
                                        {{ $value }}
                                      </td>
                                    @endforeach
                                    <td></td>
                                    <td></td>
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

<footer class="main-footer">
	<div class="pull-right hidden-xs">
		<b>Version</b> 1.0.0
	</div>
	<strong>Copyright &copy; 2014-2016 <a href="http://www.softoya.com">TrackerOnTheGo</a>.</strong> All rights reserved.
</footer>

<script type="text/javascript">
	$(function () {

	    //Initialize Select2 Elements
	    $(".select2").select2();
	    $("#branch_sec").hide();
	    $("#qty_in").hide();
	    $("#technician").hide();
	    $("#qty_out").hide();
	    $("#qty_return").hide();
	    $("#remark").hide();
	    $("#branch_in").hide();
	    $("#branch_out").hide();
	    $("#qty_branch_out").hide();
	    $("#bob").hide();
	    $("#bib").hide();
        $('#range').daterangepicker({locale: {
	        format: 'DD-MMM-YYYY'
	        },startDate: '{{$start}}',
	        endDate: '{{$end}}'});
	});

	$('#Process').on("select2:select", function(e) {
	// what you would like to happen

		if($('#Process').val()=="Stock In") 
		{
			$("#branch_sec").show();
		    $("#qty_in").show();
		    $("#technician").hide();
		    $("#qty_out").hide();
		    $("#qty_return").hide();
		    $("#remark").show();
		    $("#branch_in").hide();
		    $("#branch_out").hide();
		    $("#qty_branch_out").hide();
		}
		else if($('#Process').val()=="Stock Out")
		{
				$("#branch_sec").show();
			    $("#qty_in").hide();
			    $("#technician").show();
			    $("#qty_out").show();
			    $("#qty_return").hide();
			    $("#remark").show();
			    $("#branch_in").hide();
			    $("#branch_out").hide();
			    $("#qty_branch_out").hide();
		}
		else if($('#Process').val()=="Stock Return")
		{
				$("#branch_sec").show();
			    $("#qty_in").hide();
			    $("#technician").show();
			    $("#qty_out").hide();
			    $("#qty_return").show();
			    $("#remark").show();
			    $("#branch_in").hide();
			    $("#branch_out").hide();
			    $("#qty_branch_out").hide();
		}
		else if ($('#Process').val()=="Stock Out Branch")
		{
			$("#branch_sec").hide();
		    $("#qty_in").hide();
		    $("#technician").hide();
		    $("#qty_out").hide();
		    $("#qty_return").hide();
		    $("#qb").hide();
		    $("#remark").show();
		    $("#branch_in").show();
		    $("#branch_out").show();
		    $("#qty_branch_out").show();
		    $("#bib").show();
		    $("#bob").show();
		}
	});

var stockcheckTable;

	$(document).ready(function() {
        $(document).on('change', '#branch_in', function(e) {
			var branchin = $('#branchin').val();
			var id = $('#StockId').val();
		    var branchout = $('#branch_out').val();
		    $.ajaxSetup({
			headers: { 'X-CSRF-Token' : $('meta[name=_token]').attr('content') }
			});

			$.ajax({
			url: "{{ url('/branchtransfergetquantity/in') }}",
			method: "GET",
			data : {in:branchin,id:id},
			success: function(response){
					if(response.balance[0].count == null)
					{
						response.balance[0].count = 0;
					}
					$('#branch_in_balance').val(response.balance[0].count)
				}
			});
		});
    });

    $(document).ready(function() {
        $(document).on('change', '#branch_out', function(e) {
			var branchin = $('#branchin').val();
			var id = $('#StockId').val();
		    var branchout = $('#branchout').val();
		    $.ajaxSetup({
			headers: { 'X-CSRF-Token' : $('meta[name=_token]').attr('content') }
			});

			$.ajax({
			url: "{{ url('/branchtransfergetquantity/out') }}",
			method: "GET",
			data : {out:branchout,id:id},
			success: function(response){
					if(response.balance[0].count == null)
					{
						response.balance[0].count = 0;
					}
					$('#branch_out_balance').val(response.balance[0].count)
				}
			});
		});
    });

    $(document).ready(function() {
        $(document).on('change', '#branch', function(e) {
			var branch = $('#branch').val();
			var id = $('#StockId').val();
		    $.ajaxSetup({
			headers: { 'X-CSRF-Token' : $('meta[name=_token]').attr('content') }
			});

			$.ajax({
			url: "{{ url('/branchtransfergetquantity') }}",
			method: "GET",
			data : {branch:branch,id:id},
			success: function(response){
					if(response.balance[0].count == null)
					{
						response.balance[0].count = 0;
					}
						$('#qty_balance').val(response.balance[0].count)
				}
			});
		});
    });

 function OpenModal(row)
  {
 	  var table = $('#inventorytable').DataTable();
 	  $('#item_name').val(oTable.api().row(row).data().speedfreakinventory.name);
 	  $('#qty_balance').val(oTable.api().row(row).data().speedfreakinventory_history.qty)
 	  $("#StockId").val(oTable.api().row(row).data().speedfreakinventory.Id);
 	  $('#UpdateStockModal').modal('show');
  }
    function stockcheck()
    {
		$.ajaxSetup({
			headers: { 'X-CSRF-Token' : $('meta[name=_token]').attr('content') }
		});

		$("#ajaxloader2").show();
		$("#stockcheckactivity").html("");

		$.ajax({
			url: "{{ url('/inventory/stockcheck') }}",
			method: "GET",
			success: function(response){
				if (response==0)
				{
					var message ="Failed to retrieve stockcheck information!";
	                $("#warning-alert_stockcheck ul").html(message);
	                $("#warning-alert_stockcheck").show();
	                window.setTimeout(function () {
	                	$("#warning-alert_stockcheck").fadeOut();;
	                }, 2000);
	                $("#ajaxloader2").fadeOut();
	            }
	            else 
	            {
	            	$("#exist-alert").fadeOut();
	            	var myObject = response;
		        	var display='<table id="stockcheckTable" class="table table-bordered table-condensed" width="100%" padding="30px" style="font-size: 13px;">';
	            	display+='<thead><tr class="roomsheader"><th>Title</th><th>Date</th></tr></thead>';
	            	display+='<tbody>';
	            	$.each(myObject, function(i,item){
	            		display+="<tr>";
	            		display+='<td>'+item.Title+'</td><td>'+ item.Date +'<button name="btnDelStockcheck" class="btn btn-xs btn-link" data-stockcheck-id="'+ item.Id +'">x</button></td>';
	            		display+="</tr>";
	            	});
	            	display+='</tbody>';
	            	display+="</table>";

	            	//Delete the datable object first
	            	if (stockcheckTable != null)
	                stockcheckTable.destroy();

	                $("#stockcheckactivity").html(display);
	                stockcheckTable = $('#stockcheckTable').DataTable({
	                	iDisplayLength: 5,
	                	"lengthChange": false,
	                	columns: [
	                	{ title: "Title", data:"Title" },
	                	{ title: "Date", data:"Date" }
	                	]
	                });
	                $("#ajaxloader2").fadeOut();
	            }
	        }
	    });
	}


    function inventorystockcheck(Inventory_Id)
    {
      $.ajaxSetup({
         headers: { 'X-CSRF-Token' : $('meta[name=_token]').attr('content') }
      });

      $("#ajaxloader4").show();
      $("#inventorystockcheck").html("");
      $('#InventoryStockCheckModal').modal('show');
      $("#inventoryid").val(Inventory_Id);

      $.ajax({
                  url: "{{ url('/inventory/stockcheck/list') }}",
                  method: "GET",
                  data : {
                    Inventory_Id:Inventory_Id
                  },
                  success: function(response){
                    if (response==0)
                    {
                      var message ="Failed to retrieve stockcheck information!";
                      $("#warning-alert_inventorystockcheck ul").html(message);
                      $("#warning-alert_inventorystockcheck").show();
                      window.setTimeout(function () {
                        $("#warning-alert_inventorystockcheck").fadeOut();;
                      }, 2000);
                      $("#ajaxloader4").fadeOut();
                    }
                    else {
                      $("#ajaxloader4").fadeOut();
                      $("#exist-alert").fadeOut();
                      var myObject = response;
                          var display='<table id="inventorystockcheckTable" class="table table-bordered table-condensed" width="100%" padding="30px" style="font-size: 13px;">';
                          display+='<thead><tr class="roomsheader"><th>Location</th><th>Title [Date]</th><th>Quantity</th></tr></thead>';

                          $.each(myObject, function(i,item){
                                  display+="<tr>";
                                  display+='<td>'+ item.WarehouseCode + ' ' + item.WarehouseName + ' (' + item.RoomCode + ' ' + item.RoomName + ')</td><td>'+item.Title+' ['+ item.Date +'] <button name="btnDelInventoryStockcheck" class="btn btn-xs btn-link" data-stockcheckinventory-id="'+ item.Id +'" data-inventory-id="'+ Inventory_Id +'">x</button></td><td>'+ item.Quantity +'</td>';
                                  display+="</tr>";
                          });
                          display+='</tbody>';
                      display+="</table>";

                      //Delete the datable object first
                      if (stockcheckTable != null)
                        stockcheckTable.destroy();



                      $("#inventorystockcheck").html(display);

                      stockcheckTable = $('#inventorystockcheckTable').DataTable({
                          iDisplayLength: 5,
                          "lengthChange": false,
                          columns: [
                              { title: "Location", data:"Location" },
                              { title: "Title [Date]", data:"Date" },
                              { title: "Quantity", data:"Quantity" }
                          ]
                      });


                    }
          }
      });

    }

    function RoomsQuantity(StockId)
    {
      $('#RoomsQuantityModal').modal('show');
      $("#rooms").html("");

      $.ajaxSetup({
         headers: { 'X-CSRF-Token' : $('meta[name=_token]').attr('content') }
      });

      $("#ajaxloader3").show();

      $.ajax({
                  url: "{{ url('/inventory/rooms/quantity') }}",
                  method: "POST",
                  data: {
                    StockId:StockId
                  },
                  success: function(response){
                    if (response==0)
                    {
                      var message ="Failed to retrieve inventory quantity!";
                      $("#warning-alert ul").html(message);
                      $("#warning-alert").show();

                      $("#ajaxloader3").fadeOut();
                    }
                    else {
                      $("#exist-alert").fadeOut();
                      var myObject = response;
                          var display='<table class="table table-bordered" width="100%" padding="30px" style="font-size: 13px;">';
                          display+='<tr class="roomsheader"><th>Bay</th><th>Region</th><th>Ownership</th><th>Site</th><th>Action</th><th>Quantity</th><th>Date</th></tr>';

                          $.each(myObject, function(i,item){

                                  if (item.RoomName===null)
                                  {
                                    item.RoomName=" - ";
                                  }

                                  display+="<tr>";
                                  display+='<td>'+item.WarehouseCode+' '+item.RoomCode+'</td><td>'+item.Region+'</td><td>'+item.Ownership+'</td><td>'+item.Site+'-'+item.Site_Name+'</td><td>'+item.Action+'</td><td>'+item.Quantity+'</td><td>'+item.Created_At+'</td>';
                                  display+="</tr>";
                          });

                      display+="</table>";

                      $("#rooms").html(display);

                      $("#ajaxloader3").fadeOut();
                    }
          }
      });

    }





    $( "#stockcheckactivity" ).on( "click", "[name=btnDelStockcheck]", function( event ) {
          var StockcheckId = $(this).data("stockcheck-id");

          $.ajax({
            url: "{{ url("/inventory/stockcheck/remove") }}",
            method: "POST",
            data:{
                Id: StockcheckId
            },
            success: function(response){

              var message ="Stockcheck removed!";
                          $("#success-alert_stockcheck ul").html(message);
                          $("#success-alert_stockcheck").show();
              setTimeout(function() {
                $("#success-alert_stockcheck").fadeOut();
              }, 3000);
              stockcheck();
            },
            error: function(data){
              var errors = data.responseJSON;
              for (var first in errors) break;
                var message = errors[first][0];
                          $("#warning-alert_stockcheck ul").html(message);
                          $("#warning-alert_stockcheck").show();
              setTimeout(function() {
                $("#warning-alert_stockcheck").fadeOut();
              }, 3000);
            }
        });

    });


    $( "#inventorystockcheck" ).on( "click", "[name=btnDelInventoryStockcheck]", function( event ) {
          var Id = $(this).data("stockcheckinventory-id");
          var Inventory_Id = $(this).data("inventory-id");
          $.ajax({
            url: "{{ url("/inventory/stockcheckinventory/remove") }}",
            method: "POST",
            data:{
                Id: Id
            },
             success: function(response){

              var message ="Stockcheck removed!";
                          $("#success-alert_inventorystockcheck ul").html(message);
                          $("#success-alert_inventorystockcheck").show();
              setTimeout(function() {
                $("#success-alert_inventorystockcheck").fadeOut();
                inventorystockcheck(Inventory_Id);
              }, 3000);
            },
            error: function(data){
              var errors = data.responseJSON;
              for (var first in errors) break;
                var message = errors[first][0];
                          $("#warning-alert_inventorystockcheck ul").html(message);
                          $("#warning-alert_inventorystockcheck").show();
              setTimeout(function() {
                $("#warning-alert_inventorystockcheck").fadeOut();
              }, 3000);
            }
        });

    });

    function stockcheckAdd() {
      var formData = $('#formStockcheck').serialize();

      $.ajax({
        url: "{{ url("/inventory/stockcheck/add") }}",
        method: "POST",
        data: formData,
        success: function(response){

          var message ="Stockcheck Added!";
                      $("#success-alert_stockcheck ul").html(message);
                      $("#success-alert_stockcheck").show();
                      stockcheck();
          setTimeout(function() {
            $("#success-alert_stockcheck").fadeOut();
          }, 3000);
        },
        error: function(data) {
          var errors = data.responseJSON;
          for (var first in errors) break;
            var message = errors[first][0];
                      $("#warning-alert_stockcheck ul").html(message);
                      $("#warning-alert_stockcheck").show();
          setTimeout(function() {
            $("#warning-alert_stockcheck").fadeOut();
          }, 3000);
        }
      });
    }

    function stockcheckInventoryAdd() {
      var formData = $('#formInventoryStockcheck').serialize();
      var Inventory_Id = $("#inventoryid").val();

      $.ajax({
        url: "{{ url("/inventory/stockcheck/inventorycheck") }}",
        method: "POST",
        data: formData,
        success: function(response){

          var message ="Stockcheck success!";
                      $("#success-alert_inventorystockcheck ul").html(message);
                      $("#success-alert_inventorystockcheck").show();
          setTimeout(function() {
            $("#success-alert_stockcheck").fadeOut();
            inventorystockcheck(Inventory_Id);
          }, 3000);
        },
        error: function(data) {
          var errors = data.responseJSON;
          for (var first in errors) break;
            var message = errors[first][0];
                      $("#warning-alert_inventorystockcheck ul").html(message);
                      $("#warning-alert_inventorystockcheck").show();
          setTimeout(function() {
            $("#warning-alert_inventorystockcheck").fadeOut();
          }, 3000);
        }
      });
    }

    var months = [
    'Jan', 'Feb', 'Mar', 'Apr', 'May',
    'Jun', 'Jul', 'Aug', 'Sep',
    'Oct', 'Nov', 'Dec'
    ];

    function monthNumToShortName(num) {
        return months[num - 1] || '';
    }

    function updatestock($inventoryid,$stockid)
    {
        $("#ajaxloader5").hide();
        $("#IntId").val($inventoryid);
        $("#StockId").val($stockid);

        $("#UpdateStockModal").modal("show");

    }

    function OpenDeleteDialog(id)
  	{
    var hiddeninput='<input type="hidden" class="form-control" id="deleteid" name="deleteid" value="'+id+'">';
     $( "#deleteitem" ).html(hiddeninput);
     $('#Delete').modal('show');
  	}

    function deleteitem() {
      $.ajaxSetup({
         headers: { 'X-CSRF-Token' : $('meta[name=_token]').attr('content') }
      });
      var id = $('#deleteid').val();
      $.ajax({
        url: "{{ url('/inventory/delete') }}",
        method: "POST",
        data: {Id:id},
        success: function(response){
        	if(response == 1)
        	{
        		alert('Successfully Deleted the Item');
        		window.location.reload();
        	}
        	else
        	{
        		alert('Failed to Delete Item');
        	}

        }
    });

  	}
    function updatestockcount() {
      $.ajaxSetup({
         headers: { 'X-CSRF-Token' : $('meta[name=_token]').attr('content') }
      });

      var formData = $('#formUpdateStock').serialize();

      $.ajax({
        url: "{{ url('/inventory/updatestock') }}",
        method: "POST",
        data: formData,
        success: function(response){
        	$('#UpdateStockModal').modal('hide');
        	if(response == 1)
        	{
	          var message ="Stock Updated!";
	                  $("#update-alert ul").html(message);
                      $("#update-alert").modal('show');

	          setTimeout(function() {
	            $("#update-alert").fadeOut();
	            window.location.reload();

	          }, 1500);
        	}
        	else
        	{	
        		 var message = response;
                  $("#error-alert ul").html(message);
                  $("#error-alert").modal('show');
        	}
        }
      });
    }

    function itemcreate()
    {
    	var formData = $('#newitemform').serialize();
    	$.ajaxSetup({
         headers: { 'X-CSRF-Token' : $('meta[name=_token]').attr('content') }
      	});

      	$.ajax({
        url: "{{ url('/speedfreakinventorycreate') }}",
        method: "POST",
        data: formData,
        success: function(response){
        	$('#NewItemModal').modal('hide');
        	if(response == 1)
        	{
	          var message ="Stock Created!";
	                  $("#update-alert ul").html(message);
                      $("#update-alert").modal('show');

	          setTimeout(function() {
	            $("#update-alert").fadeOut();
	            window.location.reload();

	          }, 1500);
        	}
        	else
        	{	
        		 var message = response;
                  $("#error-alert ul").html(message);
                  $("#error-alert").modal('show');
        	}
        }
      });
    }
    

	function refresh()
{
      var d=$('#range').val();
      var arr = d.split(" - ");
      var b = $('#filter').val();
      if(b == "")
      {
      	window.location.href ="{{ url('/speedfreak/sales') }}/"+arr[0]+"/"+arr[1];
      }
      else
      {
      	window.location.href ="{{ url('/speedfreak/sales') }}/"+arr[0]+"/"+arr[1] + b;
      }
}

  </script>

@endsection