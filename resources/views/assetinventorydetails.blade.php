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
    var editor;
    var asInitVals = new Array();
	var oTable;
	var userid;
	var inventory;
   $(document).ready(function() {
		
	} );
</script>

@endsection

@section('content')

<div class="content-wrapper">
	<!-- Content Header (Page header) -->
	<section class="content-header">
		<h1>Asset Management<small>SPEEDFREAK</small></h1>
		<ol class="breadcrumb">
			<li><a href="{{ url('/home') }}"><i class="fa fa-dashboard"></i>Home</a></li>
			<li><a href="#">SPEEDFREAK</a></li>
            <li>Asset Management</li>
            <li class="active">Asset Inventory Branch Details</li>
		</ol>
	</section>

	<br>

	<section class="content">
		<div class="box">
			<div class="box-body">
				<div class="row">
					<div class="col-lg-12">
						<div class="box-header">
							<h4>Asset Details</h4>
						</div>
						<div class="box-body">
		                    <div class="col-lg-4">
		                        <table class="table table-bordered">
		                            <tbody>
		                                <tr>
		                                    <th>Name </th>
		                                    <td>{{$item->name}}</td>
		                                </tr>
		                                <tr>
		                                    <th>Type</th>
		                                    <td>{{$item->type}}</td>
		                                </tr>
		                                <tr>
		                                    <th>Brand</th>
		                                    <td>{{$item->brand}}</td>
		                                </tr>
		                                <tr>
		                                    <th>Barcode</th>
		                                    <td>{{$item->barcode}}</td>
		                                </tr>
		                            </tbody>
		                        </table>
		                    </div>
		                    <div class="col-lg-4">
		                    	<table class="table table-bordered">
		                            <tbody>
		                                <tr>
		                                    <th>Engine Model</th>
		                                    <td>{{$item->engine_model}}</td>
		                                </tr>
		                                <tr>
		                                    <th>Engine No</th>
		                                    <td>{{$item->engine_no}}</td>
		                                </tr>
		                                <tr>
		                                    <th>Rental Rate</th>
		                                    <td>{{$item->rental_rate}}</td>
		                                </tr>
		                                <tr>
		                                    <th>Capacity</th>
		                                    <td>{{$item->capacity}}</td>
		                                </tr>
		                            </tbody>
		                        </table>
		                    </div>
		                    <!-- <div class="col-lg-3">
		                    	<div class="box-tools text-center">
						            <img src="{{ url($me->Web_Path) }}" class="user-image" alt="User Image">
						        </div>
		                    </div> -->
		                </div>
	                </div>
	            </div>
	            <!-- <div class="row"> -->
	            	<table id="inventorytable" class="table table-bordered table-hover" cellspacing="0" width="100%" padding="30px" style="font-size: 13px; white-space: pre-line;">
		              	<thead>
                                <tr>
                                    <th>Date</th>
                                    <th>Purpose</th>
                                    <th>SpeedFreak</th>
                                    <th>Client</th>
                                    <th>Site</th>
                                    <th>DO Number</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php $i = 0; ?>
                                @foreach($history as $delivery)
                                <tr>
                                	<td>{{$delivery->delivery_date}}</td>
                                	<td>{{$delivery->Option}}</td>
                                	<td>{{$item->barcode}}</td>
                                	<td>{{$delivery->Company_Name}}</td>
                                	<td>{{$delivery->Location_Name}}</td>
                                	@if(strpos($delivery->DO_No,'DO') != false)
                                	<td><a href="/deliveryorder/{{$delivery->Id}}" target="_blank"><span class="label label-primary">{{$delivery->DO_No}}</span></span></a></td>
                                	@else
                                	<td><a href="/returnnote/{{$delivery->Id}}" target="_blank"><span class="label label-primary">{{$delivery->DO_No}}</span></span></a></td>
                                	@endif
                                </tr>
                                <?php $i++; ?>
                                @endforeach
                            </tbody>
                            <tfoot></tfoot>
                        </table>
		        <!-- </div> -->
               <!--  <div class="row">
                	<div class="col-lg-12">
	                	<div class="box-header">
	                		<h4>Tools Branch History ([Branch Name])</h4>
	                	</div>
	                	<div class="box-body">
		                	<div class="col-lg-12">
		                        <ul class="timeline"> -->
						            <!-- timeline time label -->
						            <!-- <li class="time-label">
						            	<span class="bg-red">10 Feb. 2014</span>
						            </li> -->
						            <!-- /.timeline-label -->
						            <!-- timeline item -->
						            <!-- <li>
							            <i class="fa fa-plus bg-green"></i>
							            <div class="timeline-item">
							                <span class="time"><i class="fa fa-clock-o"></i>[time]</span>
							                <p class="timeline-header"><b>[5 Quantity]</b> stock in by [Technician Name] to [branch name]</p>
							                <div class="timeline-footer">
								                <button class="btn btn-danger btn-xs">Delete Log</button>
							                </div>
							            </div>
						            </li> -->
						            <!-- END timeline item -->
						            <!-- timeline item -->
						            <!-- <li>
						            	<i class="fa fa-refresh bg-aqua"></i>
						            	<div class="timeline-item">
						            		<span class="time"><i class="fa fa-clock-o"></i>[time]</span>
						            		<h3 class="timeline-header"><b>[Quantity]</b> stock return by [Technician Name] to [branch name]</h3>
						            		<div class="timeline-footer">
								                <a class="btn btn-danger btn-xs">Delete Log</a>
							                </div>
						            	</div>
						            </li> -->
						            <!-- END timeline item -->
						            <!-- timeline item -->
						            <!-- <li>
							            <i class="fa fa-minus bg-red"></i>
							            <div class="timeline-item">
							                <span class="time"><i class="fa fa-clock-o"></i>[time]</span>
							                <h3 class="timeline-header"><b>[5 Quantity]</b> stock out by [Technician Name] to [branch name]</h3>
							                <div class="timeline-footer">
								                <a class="btn btn-danger btn-xs">Delete Log</a>
							                </div>
							            </div>
						            </li> -->
						            <!-- END timeline item -->
						    <!--     </ul>
		                    </div>
		                </div>
		            </div> -->
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