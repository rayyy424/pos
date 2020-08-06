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
		editor = new $.fn.dataTable.Editor( {
			ajax: {
				"url": "{{ asset('/Include/gensetinventory.php') }}"
			},
			idSrc: "gensetinventory.Id",
			table: "#inventorytable",
			fields: [
			{
				label:"Type",
				name:"gensetinventory.type",
				type: 'select2',
				options: [ 
				{ label :"GENSET", value: "GENSET" },
				{ label :"TANK", value: "TANK" },
				{ label :"ATS", value: "ATS" }, 
				{ label :"VEHICLE", value: "VEHICLE" },
				],
			},
			{
				label: "Machinery No:",
				name: "gensetinventory.machinery_no",
			},
            {
                label: "Status:",
	            name: "gensetinventory.status",
	            type: 'select2',
	            options: [ 
	            { label :"Idle", value: "Idle" },
	            { label :"Occupied", value: "Occupied" },
	            { label :"Repair", value: "Repair" },
	            { label :"Returned", value: "Returned" },
	            { label :"Verified", value: "Verified" },
	            ],
            }]
        });

        editor.on( 'preSubmit', function (e, data, action) {
        	var machinery_no = this.field( 'gensetinventory.machinery_no' );
        	var model = this.field( 'gensetinventory.model' );
        	var type = this.field( 'gensetinventory.type' );
        	var supplier = this.field( 'gensetinventory.supplier' );
        	var status = this.field( 'gensetinventory.status' );
        	 if ( action !== 'remove' )
        	 {

        	 	if ( ! machinery_no.val() ) {
                    machinery_no.error( 'Machinery No Must Not Empty' );
                }
                if ( ! status.val() ) {
                    status.error( 'Status Must Not Empty' );
                }
                if ( ! type.val() ) {
                    status.error( 'Type Must Not Empty' );
                }
        	 	if ( this.inError() ) {
                return false;
            	}
        	 }
        });

		oTable=$('#inventorytable').dataTable( {
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
			dom: "lBfrtip",
			iDisplayLength: 25,
			"drawCallback":  function( settings ) {
					$('#pagelengthcontrol').on("change", function(e) {
					var length = $('#pagelengthcontrol').val()
					// oTable.api().draw();
					oTable.api().page.len(length).draw();
				});
            },
			columns: [
				{ data: null,"render":"", title:"No"},
				{ data: "gensetinventory.Id", title:'Id'},
				{ data: "gensetinventory.machinery_no", title:'Machinery No',
					render: function ( data, type, full, meta ) {
						return "<b>"+data+"</b>";
					}
				},
				{ data: "gensetinventory.type", title:'Type'},
				{ data: "company.Company_Name", title:'Client',
					render: function ( data, type, full, meta ) {
						if(data == "" || data == null)
						{
							return "-";
						}
						else
						{
							return data;
						}
					}
				},
				{ data: "radius.Location_Name",title:"Site",
					render: function ( data, type, full, meta ) {
						if(data == "" || data == null)
						{
							return "-";
						}
						else
						{
							return data;
						}
					}
				},
				{ data: "users.Name", title:'Technician',
					render: function ( data, type, full, meta ) {
						if(data == "" || data == null)
						{
							return "-";
						}
						else
						{
							return data;
						}
					}
				},			
				{ data: "deliveryform.project_type",title:"Client Category",
					render: function ( data, type, full, meta ) {
							if(data == "" || data == null)
							{
								return "-";
							}
							else
							{
								return data;
							}
						}
				},
				{ data: "tracker.hiredate", title:'Hire Date',
					render: function ( data, type, full, meta ) {
						if(data == "" || data == null)
						{
							return "-";
						}
						else
						{
							return data;
						}
					}
				},
				{ data: "deliveryform.offhire_date", title:'Off-Hire Date',
						render: function ( data, type, full, meta ) {
						if(data == "" || data == null)
						{
							return "-";
						}
						else
						{
							return data;
						}
					}
				},
				{ data: "gensetinventory.status", title:'Status',
						render: function ( data, type, full, meta ) {
						if(full.gensetinventory.status == "Idle")
						{
							return '<span class="label label-primary">'+data+'</span>';
						}
						else if (full.gensetinventory.status == "Occupied")
						{
							return '<span class="label label-info">'+data+"-"+full.deliveryform.DO_No+'</span>';
						}
						else if (full.gensetinventory.status == "Repair")
						{
							return '<span class="label label-danger">'+data+'</span>';
						}
						else if (full.gensetinventory.status == "Returned")
						{
							return '<span class="label label-warning">'+data+'</span>';
						}
						else if (full.gensetinventory.status == "Verified")
						{
							return '<span class="label label-success">'+data+'</span>';
						}
						else
						{
							return "-";
						}
					}

				},
				{ data: "deliveryform.DO_No", title:'DO Number',
					render: function ( data, type, full, meta ) {
						if(data == "" || data == null)
						{
							return "-";
						}
						else
						{
							return data;
						}
					}
				},
				{ data:null, title:"Action",
					render: function ( data, type, row, meta ) {
							return "<a class='btn btn-default btn-sm' target='_blank' href='{{url('asset/inventorydetails')}}/"+ data.gensetinventory.Id + "'>View</a>" + 
								"<a class='btn btn-default btn-sm' href='/assetdetails/"+data.gensetinventory.Id+"' target='_blank'>Edit</a>";
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
			// select: true,
			buttons: [
			{ extend: "create", editor: editor },
			// { extend: "edit", editor: editor },
			// {
			// 	text: 'Stockcheck',
			// 	action: function ( e, dt, node, config ) {
			// 		$('#StockCheckActivityModal').modal('show');
			// 		stockcheck();
			// 	}
			// },
			{ extend: "remove", editor: editor },
			{
				extend: 'collection',
				text: 'Export',
				buttons: [
				'excel',
				'csv',
				'pdf'
				]
			},
			],
		});

		$('#inventorytable').on( 'click', 'tr', function () {
			// Get the rows id value
			//  var row=$(this).closest("tr");
			//  var oTable = row.closest('table').dataTable();
			userid = oTable.api().row( this ).data().gensetinventory.Id;
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
		<h1>Asset Management<small>GENSET</small></h1>

		<ol class="breadcrumb">
			<li><a href="{{ url('/home') }}"><i class="fa fa-dashboard"></i>Home</a></li>
			<li><a href="#">GENSET</a></li>
			<li class="active">Asset Management</li>
		</ol>
	</section>

	<br>

	<section class="content">

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
			                        <input type="text" name="Item Name" placeholder="Item Name" class="form-control input-sm" readonly="">
			                    </div>
			                </div>

			                <div class="col-sm-12">
		                    	<div class="form-group">
			                        <label>Quantity Balance:</label>
			                        <input type="text" name="qty_balance" placeholder="Quantity Balance" class="form-control input-sm" readonly="">
			                    </div>
			                </div>


		                    <div class="col-sm-12" id="branch">
			                    <div class="form-group">
			                        <label>Branch: </label>
			                        <select class="form-control select2" name="branch" id="branch" style="width: 100%">
				                        <option value="">Please select</option>
				                        <option value=""></option>
				                    </select>
				                </div>
				            </div>

		                    <div class="col-sm-12" id="qty_in">
		                    	<div class="form-group">
		                    		<label>Quantity In: </label>
			                        <input type="text" name="qtg_in" placeholder="Quantity In" class="form-control input-sm">
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
			                            <option></option>
		                                <option value=""></option>
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

			                <div class="col-sm-12" id="branch_in">
			                    <div class="form-group">
			                        <label>Current Branch: </label>
			                        <select class="form-control select2" name="branch_in" id="branch_in" style="width: 100%">
				                        <option value="">Please select</option>
				                        <option value=""></option>
				                    </select>
				                </div>
				            </div>

				            <div class="col-sm-12" id="branch_out">
			                    <div class="form-group">
			                        <label>Stock Out Branch: </label>
			                        <select class="form-control select2" name="branch_out" id="branch_out" style="width: 100%">
				                        <option value="">Please select</option>
				                        <option value=""></option>
				                    </select>
				                </div>
				            </div>

				            <div class="col-sm-12" id="qty_branch_out">
			                    <div class="form-group">
			                        <label>Stock Out Quantity: </label>
			                        <input type="text" name="qty_branch_out" placeholder="Stock Out Quantity" class="form-control input-sm">
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

		<div class="row">
	        <div class="box">
	          <div class="box-body">
	            <div class="col-md-12">
					<div class="row">
						<div class="col-md-3">
							<label>Type</label>
							<select class="select2 form-control" id="type" onchange="Refresh()">
								<option></option>
								@foreach($type as $t)
								<option <?php if($typefilter == $t) echo "selected"; ?> >{{$t}}</option>
								@endforeach
							</select>
						</div>
					</div>	
	            	<table id="inventorytable" class="inventorytable" cellspacing="0" width="100%" padding="30px" style="font-size: 13px; white-space: pre-line;">
		              	<thead>
		              		<tr class="search">
		              			@foreach($gensetinventory as $key=>$value)

			              			@if ($key==0)
				              			<?php $i = 0; ?>
					              			@foreach($value as $field=>$a)
		                                        @if ($i==0 || $i==1)
		                                          <th align='center'><input type='hidden' class='search_init' name='{{$i}}' placemark='{{$a}}'></th>
		                                        @else
		                                          <th align='center'><input type='text' class='search_init' name='{{$i}}'  placemark='{{$a}}'></th>
		                                        @endif
		                                        <?php $i ++; ?>
	                                        @endforeach
                                        <th align='center'><input type='text' class='search_init' name='{{$i}}'  placemark='{{$a}}'></th>
                                        <th align='center'><input type='text' class='search_init' name='{{$i}}'  placemark='{{$a}}'></th>
									@endif

								@endforeach
		                    </tr>
		                    <tr>
		                    	@foreach($gensetinventory as $key=>$value)
		                            @if ($key==0)
			                            <td></td>
			                            @foreach($value as $field=>$value)
			                                <td>{{ $field }}</td>
		                                @endforeach
		                                <td></td>
		                            @endif
	                            @endforeach
	                        </tr>
		                </thead>
		                <tbody>
		                	<?php $i = 0; ?>
			                	@foreach($gensetinventory as $inventory)
				                	<tr id="row_{{ $i }}">
				                		<td></td>
			                            @foreach($inventory as $key=>$value)
				                            <td>{{ $value }}</td>
			                            @endforeach
			                            <td></td>
			                        </tr>
		                        <?php $i++; ?>
		                    @endforeach
	                    </tbody>
		            </table>
		        </div>
		    </div>
		</div>
	</div>
</div>

<footer class="main-footer">
	<div class="pull-right hidden-xs">
		<b>Version</b> 2.0.1
	</div>
	<strong>Copyright &copy; 2014-2016 <a href="http://www.softoya.com">TrackerOnTheGo</a>.</strong> All rights reserved.
</footer>

<script type="text/javascript">
	$(function () {

	    //Initialize Select2 Elements
	    $(".select2").select2();
	    $("#branch").hide();
	    $("#qty_in").hide();
	    $("#technician").hide();
	    $("#qty_out").hide();
	    $("#qty_return").hide();
	    $("#remark").hide();
	    $("#branch_in").hide();
	    $("#branch_out").hide();
	    $("#qty_branch_out").hide();
	});

	$('#Process').on("select2:select", function(e) {
	// what you would like to happen

		if($('#Process').val()=="Stock In") 
		{
			$("#branch").show();
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
			// if($("#StockId").val())
			// {
				$("#branch").show();
			    $("#qty_in").hide();
			    $("#technician").show();
			    $("#qty_out").show();
			    $("#qty_return").hide();
			    $("#remark").show();
			    $("#branch_in").hide();
			    $("#branch_out").hide();
			    $("#qty_branch_out").hide();
			// }
			// else 
			// {
				// $("#ProjectSection").show();
				// $("#RegionSection").show();
				// $("#OwnershipSection").show();
				// $("#QuantitySection").show();
				// $("#ActionSection").show();
				// $("#BaySection").show();
				// $("#SiteSection").hide();
			// }
		}
		else if($('#Process').val()=="Stock Return")
		{
			// if($("#StockId").val())
			// {
				$("#branch").show();
			    $("#qty_in").hide();
			    $("#technician").show();
			    $("#qty_out").hide();
			    $("#qty_return").show();
			    $("#remark").show();
			    $("#branch_in").hide();
			    $("#branch_out").hide();
			    $("#qty_branch_out").hide();
			// }
			// else 
			// {
				// $("#ProjectSection").hide();
				// $("#RegionSection").hide();
				// $("#OwnershipSection").hide();
				// $("#QuantitySection").hide();
				// $("#ActionSection").hide();
				// $("#BaySection").hide();
				// $("#SiteSection").hide();
			// }
		}
		else if ($('#Process').val()=="Stock Out Branch")
		{
			$("#branch").hide();
		    $("#qty_in").hide();
		    $("#technician").hide();
		    $("#qty_out").hide();
		    $("#qty_return").hide();
		    $("#remark").show();
		    $("#branch_in").show();
		    $("#branch_out").show();
		    $("#qty_branch_out").show();
		}
	});

	function Refresh()
	{
		var type = $('#type').val();
		window.location.href = "{{url('asset/inventory')}}/" + type;
	}

	var stockcheckTable;
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
                          display+='<tr class="roomsheader"><th>Bay</th><th>Project</th><th>Region</th><th>Ownership</th><th>Site</th><th>Action</th><th>Quantity</th><th>Date</th></tr>';

                          $.each(myObject, function(i,item){

                                  if (item.RoomName===null)
                                  {
                                    item.RoomName=" - ";
                                  }

                                  display+="<tr>";
                                  display+='<td>'+item.WarehouseCode+' '+item.RoomCode+'</td><td>'+item.Project_Name+'</td><td>'+item.Region+'</td><td>'+item.Ownership+'</td><td>'+item.Site+'-'+item.Site_Name+'</td><td>'+item.Action+'</td><td>'+item.Quantity+'</td><td>'+item.Created_At+'</td>';
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

    function updatestockcount() {
      $.ajaxSetup({
         headers: { 'X-CSRF-Token' : $('meta[name=_token]').attr('content') }
      });

      var formData = $('#formUpdateStock').serialize();

      $.ajax({
        url: "{{ url("/inventory/stockcheck/updatestock") }}",
        method: "POST",
        data: formData,
        success: function(response){

          var message ="Stock Updated!";
                      $("#success-alert_updatestock ul").html(message);
                      $("#success-alert_updatestock").show();

          setTimeout(function() {
            $("#success-alert_stockcheck").fadeOut();
            window.location.reload();

          }, 1500);
        },
        error: function(data) {
          var errors = data.responseJSON;
          for (var first in errors) break;
            var message = errors[first][0];
                      $("#warning-alert_updatestock ul").html(message);
                      $("#warning-alert_updatestock").show();
          setTimeout(function() {
            $("#warning-alert_updatestock").fadeOut();
          }, 1500);
        }
      });
    }

  </script>

@endsection