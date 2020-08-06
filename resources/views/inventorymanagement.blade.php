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
				"url": "{{ asset('/Include/inventory.php') }}"
			},
			idSrc: "inventories.Id",
			table: "#inventorytable",
			fields: [
			{
				label: "Category:",
				name: "inventories.Categories",
				type:  'select2',
				options: [
					{ label :"", value: "" },
					@foreach($categories as $c)
					{ label : "{{ $c->Option }}", value:  "{{ $c->Option }}"},
					@endforeach
				],
			},{
				label:"Type",
				name:"inventories.Type",
				type:'select2',
				options:[
					{label:"",value:""},
					@foreach($type as $t)
						{label:"{{$t->Option}}",value:"{{$t->Option}}"},
					@endforeach
				]
			},
			{
				label: "Item Code:",
                name: "inventories.Item_Code"
			},
			{
				label: "Item Description:",
				name: "inventories.Description",
				type: "textarea"
			},
			{
				label: "Additional Description:",
				name: "inventories.Add_Description",
				type: "textarea"
			},{
				label:"Acc No:",
				name:"inventories.Acc_No"
			},
			{
				label: "Remark:",
				name: "inventories.Remark",
				type:"textarea"
            },
            {
                label: "Unit:",
	            name: "inventories.Unit",
	            type:  'select2',
	            options: [
		            { label :"", value: "" },
		            @foreach($unit as $c)
		            { label : "{{ $c->Option }}", value:  "{{ $c->Option }}"},
		            @endforeach
	            ],
            },
            {
                label: "Warehouse:",
	            name: "inventories.Warehouse",
	            type:  'select2',
	            options: [
		            { label :"", value: "" },
		            @foreach($warehouses as $c)
		            { label : "{{ $c->Option }}", value:  "{{ $c->Option }}"},
		            @endforeach
	            ],
            },
			{
				label: "Dimension:",
				name: "inventories.dimension",
				attr:{
					type:"number"
				}
            },
			]
        });

		//Activate an inline edit on click of a table cell
		$('#inventorytable').on( 'click', 'tbody td', function (e) {
			editor.inline( this, {
				onBlur: 'submit'
			} );
		} );

		oTable=$('#inventorytable').dataTable( {
			columnDefs: [
				{ "visible": false, "targets": [2] },
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
				{ data:null,
					title:"Action",
					sortable: false,
					render: function ( data, type, row, meta ) {
							return "<a class='btn btn-default btn-sm' href='{{url('inventorymanagement/inventorydetails')}}/"+ data.inventories.Id + "'>Vendor</a>";
						}
				},
				{ data: "inventories.Id"},
				{ data: "inventories.Categories", title:'Categories'},
				{ data: "inventories.Item_Code", title:'Item Code'},
				{ data: "inventories.Description", title:'Item Description'},
				{ data: "inventories.Add_Description", title:'Additional Description'},			
				{	data:"inventories.Type",title:"Type"},
				{ data:"inventories.Acc_No",title:"Acc No"},
				{ data: "inventories.Remark", title:'Item Remark'},
				{ data: "inventories.Unit", title:'Unit'},
				{ data: "inventories.Warehouse", title:'Warehouse'},
				{ data: "inventories.dimension", title:'Dimension (&#13217;)'}
			],
			autoFill: {
				editor:  editor,
			},
			select: {
                style:    'os',
                selector: 'td'
            },
			// select: true,
			buttons: [{ 
				extend: "create", editor: editor 
			},
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
			userid = oTable.api().row( this ).data().inventories.Id;
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
		<h1>Inventory Management<small>Admin</small></h1>

		<ol class="breadcrumb">
			<li><a href="{{ url('/home') }}"><i class="fa fa-dashboard"></i> Home</a></li>
			<li><a href="#">Admin</a></li>
			<li class="active">Inventory Management</li>
		</ol>
	</section>

	<section class="content">
		<div class="modal fade" id="importModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
		  <div class="modal-dialog" role="document">
		    <div class="modal-content">
		      <form action="{{ url('importinventory') }}" method="POST" enctype="multipart/form-data">
		        {{ csrf_field() }}
		      <div class="modal-header">
		        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
		        <h4 class="modal-title" id="myModalLabel">Import Inventory Items</h4>
		      </div>
		      <div class="modal-body">
		        <div class="form-group">
		            <label class="control-label">CSV File:</label>
		            <input type="file" name="importfile" class="form-control" required>
		        </div>
		        <p class="help-block">This will import CSV data into inventory table. Please make sure the format is in this order: Categories, Item Code, Item Description, Unit, Item Remark and Warehouse</p>

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
	        <div class="box">
	          <div class="box-body">
	            <div class="col-md-12">
		        	<button class="btn btn-success pull-right" type="button" data-toggle="modal" data-target="#importModal">Import</button><br><br>
	            	<table id="inventorytable" class="inventorytable" cellspacing="0" width="100%" padding="30px" style="font-size: 13px; white-space: pre-line;">
		              	<thead>
		              		<tr class="search">
		              			@foreach($inventories as $key=>$value)

			              			@if ($key==0)
				              			<?php $i = 0; ?>
					              			@foreach($value as $field=>$a)
		                                        @if ($i==0 || $i==1 || $i == 2)
		                                          <th align='center'><input type='hidden' class='search_init' name='{{$i}}' placemark='{{$a}}'></th>
		                                        @else
		                                          <th align='center'><input type='text' class='search_init' name='{{$i}}'  placemark='{{$a}}'></th>
		                                        @endif
		                                        <?php $i ++; ?>
											@endforeach
										<th align='center'><input type='text' class='search_init' name='11'  placemark='{{$a}}'></th>
	                                    <th align='center'><input type='text' class='search_init' name='12'  placemark='{{$a}}'></th>
									@endif

								@endforeach
		                    </tr>
		                    <tr>
	                          @foreach($inventories as $key=>$value)

	                            @if ($key==0)
		                            <td></td>
																<td></td>
		                            @foreach($value as $field=>$value)
	                                <td>
	                                    {{ $field }}
	                                </td>
	                                @endforeach

	                            @endif

	                          @endforeach
	                        </tr>
		                </thead>
		                <tbody>

		                	<?php $i = 0; ?>
		                	@foreach($inventories as $inventory)

		                	<tr id="row_{{ $i }}">
		                		<td></td>      
												<td></td>
	                            @foreach($inventory as $key=>$value)
	                              <td>
	                                {{ $value }}
	                              </td>
	                            @endforeach
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



@endsection