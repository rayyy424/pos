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

	    img:hover{
	    	transform: scale(3);
	    }

	    /* .dataTables_wrapper .dataTables_scroll div.dataTables_scrollBody th, .dataTables_wrapper .dataTables_scroll div.dataTables_scrollBody td {
	      	white-space: pre-wrap;
	    } */
        table.dataTable tbody th,table.dataTable tbody td 
        {
        white-space: nowrap;
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
	<script>
		$(function () {
			$("#appoint_details").dataTable();
		});
	</script>
@endsection
@section('content')

<div class="content-wrapper">

    <!-- Content Header (Page header) -->
    <section class="content-header">
        <h1>Service Ticket Details<small>Genset Management</small></h1>

        <ol class="breadcrumb">
            <li><a href="{{ url('/home') }}"><i class="fa fa-dashboard"></i> Home</a></li>
            <li><a href="#">Genset Management</a></li>
            <li class="active">Service Ticket Details</li>
        </ol>
    </section>

    <br>

    <section class="content">
    	<div class="col-md-12">
	        <div class="nav-tabs-custom">
	        	<ul class="nav nav-tabs">
		            <li class="active"><a href="#serviceticket" data-toggle="tab" id="servicetickettab">Service Ticket Details</a></li>
		            <li><a href="#replacementinfo" data-toggle="tab" id="replacementinfotab">Replacement Information</a></li>
					<li><a href="#attendance" data-toggle="tab" id="attendancetab">Attendance</a></li>
		            <li><a href="#fuel" data-toggle="tab" id="fueltab">Fuel Reading</a></li>
					<li><a href="#appointment" data-toggle="tab" id="appointmenttab">Service Appointment</a></li>
					<li><a href="#pending" data-toggle="tab" id="pendingtab">Pending Sparepart</a></li>
		        </ul>
		        <div class="tab-content">

		        	<div class="active tab-pane" id="serviceticket">
						<div class="row">
							<div class="col-md-4">
								<div class="box-header">
									<h4>Service Ticket Details</h4>
								</div>
		                        <table class="table table-bordered">
		                            <tbody>
		                            	<tr>
		                                    <th>Service Ticket ID</th>
		                                    <td>{{$ser->service_id}}</td>
		                                </tr>
		                                <tr>
		                                    <th>Service Ticket Date</th>
		                                    <td>{{$ser->service_date}}</td>
		                                </tr>
		                                <tr>
		                                    <th>Onsite GENSET</th>
		                                    <td>{{$ser->genset_no}}</td>
		                                </tr>
		                                <tr>
		                                    <th>Technician Name</th>
		                                    <td>{{$ser->Name}}</td>
		                                </tr>
		                                <tr>
		                                    <th>Technician No</th>
		                                    <td>{{$ser->Contact_No_1}}</td>
		                                </tr>
		                                <tr>
		                                    <th>Remarks</th>
		                                    <td>{{$ser->remarks}}</td>
		                                </tr>
		                            </tbody>
		                        </table>
		                    </div>
		                    <div class="col-md-4">
								<div class="box-header">
									<h4>Client Details</h4>
								</div>
		                        <table class="table table-bordered">
		                            <tbody>
		                            	<tr>
		                                    <th>Company Name</th>
		                                    <td>{{$ser->Company_Name}}</td>
		                                </tr>
		                                <tr>
		                                    <th>Main Contact Name</th>
		                                    <td>{{$ser->clientPic}}</td>
		                                </tr>
		                                <tr>
		                                    <th>Main Contact No</th>
		                                    <td>{{$ser->clientContact}}</td>
		                                </tr>
		                                <tr>
		                                    <th>Main Contact Email</th>
		                                    <td>{{$ser->clientEmail}}</td>
		                                </tr>
		                            </tbody>
		                        </table>
		                    </div>
		                    <div class="col-md-4">
		                    	<div class="box-header">
									<h4>Branch Details</h4>
								</div>
		                        <table class="table table-bordered">
		                            <tbody>
		                            	<tr>
		                                    <th>Branch Name</th>
		                                    <td>{{$ser->Location_Name}}</td>
		                                </tr>
		                                <tr>
		                                    <th>Branch Address</th>
		                                    <td>JOHOR</td>
		                                </tr	>
		                                <tr>
		                                    <th>Branch Region</th>
		                                    <td></td>
		                                </tr>
		                                <tr>
		                                    <th>Branch Latitude</th>
		                                    <td>{{$ser->Latitude}}</td>
		                                </tr>
		                                <tr>
		                                    <th>Branch Longitude</th>
		                                    <td>{{$ser->Longitude}}</td>
		                                </tr>
		                                <tr>
		                                    <th>Operating Hours</th>
		                                    <td></td>
		                                </tr>
		                                <tr>
		                                    <th>Contact Name</th>
		                                    <td></td>
		                                </tr>
		                                <tr>
		                                    <th>Contact No</th>
		                                    <td></td>
		                                </tr>
		                            </tbody>
		                        </table>
		                    </div>
		                </div>
		        	</div>

		        	<div class="tab-pane" id="replacementinfo">
		        		<div class="row">
							<div class="col-md-12">
								<div class="box-header">
									<h4>Replacement Information Details</h4>
								</div>
		                        <table class="table table-bordered">
		                        	<thead>
		                        		<tr>
		                        			<th>No</th>
		                        			<th>Running Item</th>
		                        			<th>Take Out Quantity</th>
		                        			<th>New Item</th>
		                        			<th>Replace Quantity</th>
		                        			<th>Remarks</th>
		                        			<!-- <th>Action</th> -->
		                        		</tr>
		                        	</thead>
		                            <tbody>
										<?php $i=1;?>
										@foreach($replacement as $r)
		                            	<tr>
		                            		<td>{{$i}}</td>
		                            		<td>
		                            			<p>Item Name: {{$r->name}}<br>Type: {{$r->type}}<br>Model: {{$r->model}}<br>Barcode: {{$r->barcode}}</p>
												<a href="{{$r->beforeImg}}" download=""><img src="{{$r->beforeImg}}" height='150' width='150'></a>
											</td>
		                            		<td>{{$r->previousQty}}</td>
		                            		<td>
		                            			<p>Item Name: {{$r->name2}}<br>Type: {{$r->type2}}<br>Model: {{$r->model2}}<br>Barcode: {{$r->barcode2}}</p>
												<a href="{{$r->afterImg}}" download=""><img src="{{$r->afterImg}}" height='150' width='150'></a>
		                            		</td>
		                            		<td>{{$r->newQty}}</td>
		                            		<td>{{$r->Remarks}}</td>
		                            		<!-- <td>
		                            			<a class='btn btn-default btn-sm' href=''>View</a>
		                            		</td> -->
										</tr>
										<?php $i++?>
										@endforeach
		                            </tbody>
		                        </table>
		                    </div>
		                </div>
		        	</div>

		        	<div class="tab-pane" id="attendance">
		        		<div class="row">
							<div class="col-md-12">
								<div class="box-header">
									<h4>Attendance Information</h4>
									<br>
									<table class="table table-bordered">
		                            <tbody>
		                            	<tr>
		                                    <th>1st Time In</th>
		                                    <td>{{$att2->timeIn}}</td>
		                                </tr>
		                                <tr>
		                                    <th>Total Time</th>
		                                    <td>{{$att2->totalTime}}</td>
		                                </tr>
										<tr>
		                                    <th>Status</th>
		                                    <td>{{$att2->Status}}</td>
		                                </tr>
										<tr>
		                                    <th>Remarks</th>
		                                    <td>{{$att2->Remarks}}</td>
		                                </tr>
		                            </tbody>
		                        </table>
									<!-- <p><b>1st Time In :</b> {{$att2->timeIn}}</p>
									<p><b>Total Time :</b> {{$att2->totalTime}}</p> -->
								</div>
		                        <table class="table table-bordered">
		                        	<thead>
		                        		<tr>
		                        		 <th>Name</th>
		                        		 <!-- <th>Status</th> -->
										 <!-- <th>1st Time In</th> -->
		                        		 <th>Time In</th>
		                        		 <th>Time Out</th>
										 <th>Time Diff</th>
		                        		 <!-- <th>Total Time</th> -->
		                        		 <!-- <th>Remarks</th> -->
		                        		</tr>
		                        	</thead>
		                            <tbody>
		                            	@foreach($att as $a)
		                        		<tr>
		                        			@foreach($a as $atte => $attendance)
		                        			<td>{{$attendance}}</td>
		                        			@endforeach
		                        		</tr>
		                        		@endforeach
		                            </tbody>
		                        </table>
		                    </div>
		                </div>
		        	</div>

		        	<div class="tab-pane" id="fuel">
		        		<div class="row">
							<div class="col-md-12">
								<div class="box-header">
									<h4>Fuel Reading Details</h4>
								</div>
		                        <table class="table table-bordered">
		                        	<thead>
		                        		<tr>
		                        			<th>No</th>
		                        			<th>Reading Date Time</th>
		                        			<th>Read Before</th>
		                        			<th>Read Photo (Before)</th>
		                        			<th>Read After</th>
		                        			<th>Read Photo (After)</th>
		                        		</tr>
		                        	</thead>
		                            <tbody>
		                            	<tr>
		                            		<td>1</td>
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

		        	<div class="tab-pane" id="appointment">
		        		<div class="row">
							<div class="col-md-12">
								<div class="box-header">
									<h4>Service Appointment Details</h4>
								</div>
		                        <table id="appoint_details" class="table table-bordered">
		                        	<thead>
		                        		<tr>
		                        			<th>No</th>
		                        			<th>Last Service Date</th>
		                        			<th>Upcoming Service Date</th>
		                        			<th>Status</th>
		                        		</tr>
		                        	</thead>
		                            <tbody>
		                            	<?php $i=1;?>
		                            	@foreach($ser_appoint as $appoint)
		                            	<tr>
		                            		<td>{{$i}}</td>
		                            		<td>{{$appoint->last_service ? $appoint->last_service:"-"}}</td>
		                            		<td>{{$appoint->upcoming_service ? $appoint->upcoming_service:"-"}}</td>
		                            		<td>{{$appoint->Status}}</td>
		                            	</tr>
		                            	<?php $i++;?>
		                            	@endforeach
		                            </tbody>
		                        </table>
		                    </div>
		                </div>
		        	</div>

		        	<div class="tab-pane" id="pending">
		        		<div class="row">
		        			<div class="col-md-12">
		        				<div class="box-header">
									<h4>Pending Sparepart</h4>
									 <table id="appoint_details" class="table table-bordered">
			                        	<thead>
			                        		<tr>
			                        			<th>No</th>
			                        			<th>Item Name</th>
			                        			<th>Barcode</th>
			                        			<th>Quantity</th>
			                        			<th>Status</th>
			                        			<th>Status Details</th>
			                        		</tr>
			                        	</thead>
			                            <tbody>
			                            	<?php $i=1;?>
			                            	@foreach($pending as $p)
			                            	<tr>
			                            		<td>{{$i}}</td>
			                            		<td>{{$p->name}}</td>
			                            		<td>{{$p->barcode}}</td>
			                            		<td>{{$p->Qty}}</td>
			                            		<td>{{$p->status}}</td>
			                            		<td>{{$p->status_details}}</td>
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
	</section>
</div>


@endsection