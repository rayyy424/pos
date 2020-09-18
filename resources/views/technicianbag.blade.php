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
				"url": "{{ asset('/Include/speedfreakinventory.php') }}"
			},
			idSrc: "speedfreakinventory.Id",
			table: "#inventorytable",
			fields: [
			{
				label: "Name:",
				name: "speedfreakinventory.name",
			},{
				label:"Type",
				name:"speedfreakinventory.type",
			},
			{
				label: "Barcode:",
                name: "speedfreakinventory.barcode"
			},
			{
				label: "Model:",
				name: "speedfreakinventory.model",
			},
			{
				label: "Cost Price(RM):",
				name: "speedfreakinventory.price",
			},{
				label:"Supplier:",
				name:"speedfreakinventory.supplier",
				type: 'select2',
	            options: [ { label :"", value: "" },
		            @foreach($supplier as $c)
			            { label : "{{ $c->Company_Name }}", value:  "{{ $c->Company_Name }}"},
		            @endforeach
	            ],
			},
			{
				label: "Quantity Balance:",
				name: "speedfreakinventory.qty_balance",
            },
            {
                label: "Status:",
	            name: "speedfreakinventory.status",
	            type: 'select2',
	            options: [ { label :"", value: "" },
		            @foreach($status as $c)
			            { label : "{{ $c->Option }}", value:  "{{ $c->Option }}"},
		            @endforeach
	            ],
            }]
        });

		//Activate an inline edit on click of a table cell
		// $('#inventorytable').on( 'click', 'tbody td', function (e) {
		// 	editor.inline( this, {
		// 		onBlur: 'submit'
		// 	} );
		// } );

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
			dom: "Bfrtip",
			iDisplayLength:25,
			columns: [
				{ data: null,"render":"", title:"No"},
				{ data: "speedfreakinventory.Id", title:'Id'},
				{ data: "speedfreakinventory.name", title:'Name'},
				{ data: "speedfreakinventory.type", title:'Type'},
				{ data: "speedfreakinventory.barcode", title:'Barcode'},
				{ data: "speedfreakinventory.model",title:"Model"},
				{ data: "speedfreakinventory.price", title:'Cost Price (RM)'},			
				{ data: "speedfreakinventory.price_yuan",title:"Cost Price (&yen)"},
				{ data: "speedfreakinventory.maxTechhold", title: "Maximum Hold"},
				{ data: "speedfreakinventory.balance", title:'Quantity Balance'},
				{ title:"Action", sortable: false,
					render: function ( data, type, row, meta ) {
							return "<a class='btn btn-default btn-sm' target='_blank' href='/technicianbag/details/"+row.speedfreakinventory.Id+"'>View</a>";
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
			// { extend: "create", editor: editor },
			// { extend: "edit", editor: editor },
			// // {
			// // 	text: 'Stockcheck',
			// // 	action: function ( e, dt, node, config ) {
			// // 		$('#StockCheckActivityModal').modal('show');
			// // 		stockcheck();
			// // 	}
			// // },
			// { extend: "remove", editor: editor },
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
			userid = oTable.api().row( this ).data().speedfreakinventory.Id;
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
		<h1>Technician Inventory Bag</h1>
		<ol class="breadcrumb">
	        <li><a href="#"><i class="fa fa-dashboard"></i>Home</a></li>
	        <li>SPEED FREAK Management</li>
	        <li class="active">Technician Inventory Bag</li>
	    </ol>
    </section>
    <br>
    <!-- Main content -->
    <section class="content">
    	<div class="row">
    		<div class="box">
    			<div class="box-header">
    				<div class="col-md-4">
    					<label>Technician</label>
                        <select id="requestor" name="requestor" class="form-control select2">
                            <option value="" selected="">Select Technician Name</option>
                            @foreach($tech as $t)
                            <option value="{{$t->Id}}" <?php if($t->Id == $id) echo "selected"; ?> >{{$t->Name}}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-2 pull-center">
                    	<br>
                    	<button class="btn btn-success" type="button" id="refresh">Search</button><br><br>
                    </div>
    			</div>
		        <div class="box-body">
		            <div class="col-md-12">
		            	<table id="inventorytable" class="inventorytable" cellspacing="0" width="100%" padding="30px" style="font-size: 13px; white-space: pre-line;">
			              	<thead>
			              		<tr class="search">
			              			@foreach($list as $key=>$value)

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
			                    	@foreach($list as $key=>$value)
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
				                	@foreach($list as $inventory)
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
    </section>
</div>
<footer class="main-footer">
	<div class="pull-right hidden-xs">
		<b>Version</b> 1.0.0
	</div>
	<strong>Copyright &copy; 2014-2016 <a href="http://www.softoya.com">TrackerOnTheGo</a>.</strong> All rights reserved.
</footer>

<script type="text/javascript">

	$(function () {
		$('.select2').select2();
	});

	$(document).ready(function() {
    	$(document).on('click', '#refresh', function() {
    		var tech = $('#requestor').val();

    		if(tech != "" || tech != null)
    		{
    			window.location.href ="{{ url('/technicianbag') }}/" + tech;
    		} 
    	});
	});


</script>
@endsection