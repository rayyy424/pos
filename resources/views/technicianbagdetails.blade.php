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
	    .photo{
	    	width: 200px;
      		height: 150px;
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
	var userid;
	var inventory;
   $(document).ready(function() {
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
			dom: "frtip",
			iDisplayLength:25,
			columns: [
				{ data: null,"render":"", title:"No"},
				{ data:null, title:"Action", sortable: false,
					render: function ( data, type, row, meta ) {
							return "<a class='btn btn-default btn-sm' target='_blank' href='{{url('speedfreak/inventory/details')}}/"+ data.speedfreakinventory_history.Id + "'>View</a>";
						}
				},
				{ data: "speedfreakinventory_history.Id", title:'Id'},
				{ data: "options.Option", title:'Branch Name'},
				{ data: "speedfreakinventory_history.qty", title:'Branch Quantity'}
			],
			autoFill: {
				editor:  editor,
			},
			select: {
                style:    'os',
                selector: 'td'
            },
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
		<h1>Technician Inventory Bag<small>SPEED FREAK</small></h1>
		<ol class="breadcrumb">
			<li><a href="{{ url('/home') }}"><i class="fa fa-dashboard"></i>Home</a></li>
			<li><a href="#">SPEED FREAK</a></li>
            <li>Technician Inventory Bag</li>
            <li class="active">Inventory Bag Details</li>
		</ol>
	</section>

	<br>

	<section class="content">
		<div class="box">
			<div class="box-body">
				<div class="row">
					<div class="col-lg-12">
						<div class="box-header">
							<h4>Inventory Details</h4>
						</div>
						<div class="box-body">
							<div class="row">
			                    <div class="col-lg-4">
			                        <table class="table table-bordered">
			                            <tbody>
			                                <tr>
			                                    <th>Item Name </th>
			                                    <td>{{$item->name}}</td>
			                                </tr>
			                                <tr>
			                                    <th>QR Code</th>
			                                    <td>{{$item->barcode}}</td>
			                                </tr>
			                                <tr>
			                                    <th>Type</th>
			                                    <td>{{$item->type}}</td>
			                                </tr>
			                                <tr>
			                                    <th>Model</th>
			                                    <td>{{$item->model}}</td>
			                                </tr>
			                                <tr>
			                                    <th>Cost Price (RM)</th>
			                                    <td>{{$item->price}}</td>
			                                </tr>
			                            </tbody>
			                        </table>
			                    </div>
			                    
			                    <div class="col-lg-3">
			                    		@if($photo != null)
							            <img src="{{$photo->Web_Path}}" class="photo" alt="Image">
							            @else
							            <input type="readonly" class="photo" value="No Image" style="text-align: center;">
							            @endif
			                    </div>
			                </div>
			                <div class="row">
			                	<div class="box-header">
			                		<h4>History List</h4>
			                	</div>
			                	<div class="box-body">
				                	<div class="col-lg-12">
				                       <table class="table table-bordered">
				                       	<thead>
				                       	<tr>
				                       		<th>No</th>
				                       		<th>Item Name</th>
				                       		<th>Techinician</th>
				                       		<th>Quantity</th>
				                       		<th>Requsition Number</th>
				                       	</tr>
				                       	<thead>
				                       	<?php $i=1;?>
				                       	@foreach($history as $h)
				                       	<tbody>
				                       	<tr>
				                       		<td>{{$i}}</td>
				                       		<td>{{$h->name}}</td>
				                       		<td>{{$h->user}}</td>
				                       		<td>{{$h->Qty}}</td>
				                       		<td><a href="/requisitionmanagement/details/{{$h->Id}}" target="_blank">{{$h->Req_No}}</a></td>
				                       	</tr>
				                       	<?php $i++;?>
				                       	@endforeach
				                       	</tbody>
				                       </table>
				                    </div>
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

@endsection