<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="utf-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<title>{{ env('APP_NAME') }}</title>
	<link href="{{ asset('/css/app.css') }}" rel="stylesheet">

	<!-- Fonts -->
	<link href='//fonts.googleapis.com/css?family=Roboto:400,300' rel='stylesheet' type='text/css'>

	<link rel="stylesheet" href="//code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">

	<!-- jQuery 2.2.3 -->
	<script src="{{ asset('/plugin//jQuery/jquery-2.2.3.min.js') }}"></script>
	<!-- jQuery UI 1.11.4 -->
	<script src="https://code.jquery.com/ui/1.11.4/jquery-ui.js"></script>

	<link href="{{ asset('/plugin/bootstrap/css/bootstrap.min.css') }}" rel="stylesheet">

	<style type="text/css">
		@page { 
			margin: 50px 50px; 
		}

		body {
			/*padding-left: 10px;
			padding-right: 10px;*/
			font-size: 12px;
		}

		img {
			max-height: 50px;
			max-width: 80px;
		}

		h3 {
			margin-top: 5px;
			margin-bottom: 5px;
			font-size: 17px;
		}

		header {
			/*padding: 1px;*/
			padding-bottom: 0px;
			text-align: center;
			width: 100%;
			height: 5%;	
			display: block;
		}

		h4 {
			font-size: 15px;
			margin: 0px !important;
			padding-top: 5px;
			padding-bottom: 5px;
		}

		p {
			white-space: pre-line;
			margin-bottom: 0;
		}

		hr {
			margin: 0;
			border-top: 1px solid black;
		}

		.head-note {
			text-align: center;
			font-size: 30px;
		}

		.content {
			min-height: 250px;
			padding: 10px;
			margin-right: auto;
			margin-left: auto;
			padding-left: 10px;
			padding-right: 10px;
			padding-top: 0px;
		}

		table {
		    width:100%;
		    font-size:12px;
		    page-break-inside:auto;
		    border:1;
		    border-collapse: collapse;
		}

		tr { 
		    page-break-inside:avoid; 
		    page-break-after:auto 
		}

		table#t01 tr:nth-child(even) {
		    background-color: #eee;
		}

		table#t01 tr:nth-child(odd) {
		   background-color:#fff;
		}

		table#t01 th {
		    background-color: #3b3d3e;
		    color: white;
		}

		.sign {
			border-bottom: 1px solid black;
			width: 100%;
			height: 40px;
		}

		.sign-name {
			padding-left: 15px;
		}

		.note {
			font-size: 11px;
		}

		.total {
			padding-left: 16px;
		}

		.list {
			/*min-height: 650px;*/
		}

		.signature {
			margin-bottom: 30px;
			margin-top: 30px;
		}

	</style>
</head>

<body onload="window.print();">
	<header>
		<!-- <img src="{{$order->Web_Path}}" alt="midascom_perkasa" class="text-center" width="250px" height="250px"> -->
		@if($comp)
		<h3><b>{{$comp[0]->Company_Name}}</b></h3>
		<p class="no-margin">{{$comp[0]->Address}}</p>
		<p>Tel: {{$comp[0]->Office_No}}  Fax: {{$comp[0]->Fax_No}}</p>
		@else
		<h3><b>{{$order->Company_Name}}</b></h3>
		<p class="no-margin">{{$order->address}}</p>
		<p>Tel: {{$order->Company_No}}  Fax: {{$order->Fax_No}}</p>
		@endif
		<hr>
	</header>

	<section class="content">
		<h4 class="text-center" style=""><b>E-DELIVERY ORDER</b></h4>

		<!-- address -->
		<div class="row">

			<!-- TO -->
			<div class="col-xs-7">
				@if($comp)
				<div class="row">
						<div class="col-xs-1">
							<p><b>TO:</b></p>
						</div>
						<div class="col-xs-10">
							<p><b>{{$comp[1]->Company_Name}}</b></p>
						</div>
					</div>
					<div class="row">
						<div class="col-xs-1">
							<p><b></b></p>
						</div>
						<div class="col-xs-10">
							<p>{{$comp[1]->Address}}<!--No. 5, Jalan P4/6, Seksyen 4, Bandar Teknologi Kajang, 43500 Semenyih, Selangor Darul Ehsan, Malaysia--></p>
						</div>
					</div>
					<div class="row">
						<div class="col-xs-1">
							<p><b></b></p>
						</div>
						<div class="col-xs-10">
							<p>Tel :{{$comp[1]->Office_No}}         &nbsp&nbsp Fax:  {{$comp[1]->Fax_No}}</p>
						</div>
					</div>	
				@else
				<div class="row">
					<div class="col-xs-1">
						<p><b>TO:</b></p>
					</div>
					<div class="col-xs-10">
						<p><b>{{$order->clientName}}</b></p>
					</div>
				</div>
				<div class="row">
					<div class="col-xs-1">
						<p><b></b></p>
					</div>
					<div class="col-xs-10">
						<p>{{$order->clientAddress}}<!--No. 5, Jalan P4/6, Seksyen 4, Bandar Teknologi Kajang, 43500 Semenyih, Selangor Darul Ehsan, Malaysia--></p>
					</div>
				</div>
				<div class="row">
					<div class="col-xs-1">
						<p><b></b></p>
					</div>
					<div class="col-xs-10">
						<p>Tel :{{$order->clientNum}}         &nbsp&nbsp Fax:  {{$order->clientFax}}</p>
					</div>
				</div>
				@endif
				<!-- <div class="row">
					<div class="col-xs-1">
						<p><b></b></p>
					</div>
					<div class="col-xs-10">
						<p> </p>
					</div>
				</div> -->
			</div>

			<!-- DO details -->
			<div class="col-xs-5">
				<div class="row">
					<div class="col-xs-4">
						<p><b>NO</b></p>
					</div>
					<div class="col-xs-8">
						<p><b>:&nbsp&nbsp{{$order->DO_No}}</b></p>
					</div>
				</div>
				<div class="row">
					<div class="col-xs-4">
						<p>Date</p>
					</div>
					<div class="col-xs-8">
						<p>:&nbsp&nbsp{{date('d-m-Y',strtotime($order->delivery_date))}}</p>
					</div>
				</div>
				@if($order->term)
				<div class="row">
					<div class="col-xs-4">
						<p>Term</p>
					</div>
					<div class="col-xs-8">
						<p>:&nbsp&nbsp{{$order->term}}</p>
					</div>
				</div>
				@endif
				@if($order->po)
				<div class="row">
					<div class="col-xs-4">
						<p>P.O NO.</p>
					</div>
					<div class="col-xs-8">
						<p>:&nbsp&nbsp{{$order->po}}</p>
					</div>
				</div>
				@endif
				<div class="row">
					<div class="col-xs-4">
						<p>M.R Ref. No</p>
					</div>
					<div class="col-xs-8">
						<p>:&nbsp&nbsp</p>
					</div>
				</div>
				<div class="row">
					<div class="col-xs-4">
						<p>Site</p>
					</div>
					<div class="col-xs-8">
						@if(!$location1)
						<p>:&nbsp&nbsp{{$order->Location_Name}}</p>
						@else
						<p>:&nbsp&nbsp{{$location1}}</p>
						<p> &nbsp&nbsp&nbsp{{$location2}}</p>
						@endif
					</div>
				</div>
				 <div class="row">
					<div class="col-xs-4">
						<p>Contact</p>
					</div>
					<div class="col-xs-8">
						<p>:&nbsp&nbsp{{$order->PIC_Name}}  {{$order->PIC_Contact}}</p>
					</div>
				</div>
				{{-- <div class="row">
					<div class="col-xs-4">
						<p></p>
					</div>
					<div class="col-xs-8">
						<p>:&nbsp&nbspContact no 2</p>
					</div>
				</div>  --}}
				 <div class="row">
					<div class="col-xs-4">
						<p>Lat/Long</p>
					</div>
					<div class="col-xs-8">
						<p>:&nbsp&nbsp{{$order->Latitude}}&nbsp&nbsp&nbsp&nbsp{{$order->Longitude}}</p>
					</div>
				</div>
			</div>
		</div>

		<br><hr><br>

		<!--
		<div class="row">
			<div class="col-xs-2">
				<p><b>DELIVER TO:</b></p>
			</div>
			<div class="col-xs-6" style="text-align: left;">
				<p><b>4962C Taman Bukit Minyak</b></p>
			</div>
			<div class="col-xs-4" style="text-align: right;">
				<p>20.3.2019</p>
			</div>
		</div>
			-->
		<br>

		<!-- table item -->
		<div class="row">
			<div class="col-xs-12 list">
				<table border="1" >
					<thead>
						<tr>
							<th style="text-align:center;">No.</th>
		                    <th style="text-align:center;">Item Code</th>
		                    <th style="text-align:center;">Item Description</th>
		                    <th style="text-align:center;">Qty</th>
		                    <th style="text-align:center;">Unit</th>
						</tr>
					</thead>
					<tbody>
						<?php $i=1;?>
						@foreach($items as $item)
						<tr>
							<td style="width:20px;text-align:center">{{$i}}</td>
							<td style="width:80px;text-align:center;">{{$item->Item_Code}}</td>
							<td style="width:300px;text-align:left;">
								<br>
								<p>{{$item->Description}}</p>
								<p style="white-space: pre-wrap;">{{$item->add_desc}}</p>
								<br>
							</td>
							<td style="width:50px;text-align:center;">{{$item->Qty_request}}</td>
							<td style="width:50px;text-align:center;">{{$item->Unit}}</td>
						</tr>
						<?php $i+=1;?>
						@endforeach
						<!--
						<tr>
							<td style="width:20px;text-align:center">2</td>
							<td style="width:100px;text-align:left;"></td>
							<td style="width:300px;text-align:left;"></td>
							<td style="width:50px;text-align:center;"></td>
							<td style="width:50px;text-align:center;"></td>
						</tr>
						<tr>
							<td style="width:20px;text-align:center">3</td>
							<td style="width:100px;text-align:left;">PW010</td>
							<td style="width:300px;text-align:left;">IPC connector /ABC connector</td>
							<td style="width:50px;text-align:center;">8</td>
							<td style="width:50px;text-align:center;">NOS</td>
						</tr>-->
					</tbody>
				</table>
				@if($cond != null || $note != null)
				<?php $i=1;?>
					<table>
						<tbody>
							@foreach($cond as $c)
							@if($c->Option != "" || $c->Option != null)
							@if($i == 1)
							<tr>
								<td>Condition</td>
								<td>{{$i}}) {{$c->Option}}</td>
							</tr>
							@else
							<tr>
								<td></td>
								<td>{{$i}}) {{$c->Option}}</td>
							</tr>
							@endif
							@endif

							<?php $i++;?>
							@endforeach
							<?php $count=0;?>
							@foreach($note as $n)
								@if($n->options_Id == 0)
								<tr>
									@if($count == 0)
									<td>*Note  </td>
									@else
									<td></td>
									@endif
									<td>
										<br>
										<p style="white-space:pre-wrap;">{{$n->note}}</p>
									</td>
								</tr>
								@elseif($n->options_Id != 0)
									<tr>
										@if($count == 0)
										<td>*Note  </td>
										@else
										<td></td>
										@endif
										<td>
											<br>
											<p>{{$n->Option}}</p>
										</td>
									</tr>	
								@endif
								<?php $count ++;?>
							@endforeach
							 
						</tbody>
					</table>
				@endif
				<br>
				<p><b>Remarks</b>:<i>{{$order->Remarks}}</i></p>
			</div>
		</div>

		<br>
		<div class="row signature">
			<div class="col-xs-4">
				<div class="sign">
				</div>
				<div class="row">
					<div class="sign-name">
						<p><b>Issued By</b></p>
						<p><b>Name:</b>{{$order->requestor ? $order->requestor : ""}} </p> 
						<p><b>StaffId:</b> {{$order->requestorId ? $order->requestorId : ""}} </p> 
						<p><b>Date:</b> {{$order->requestdate ? $order->requestdate : ""}} </p> 
					</div>
				</div>
			</div>
			<div class="col-xs-4">
				<div class="sign">
				</div>
				<div class="row">
					<div class="sign-name">
						@if($order->Type == "Truck")
						<p><b>Truck's Driver</b></p>
						@else
						<p><b>Lorry's Driver</b></p>
						@endif
						<p><b>Name:</b> {{$order->driver ? $order->driver : ""}} </p> 
						<p><b>StaffId:</b> {{$order->driverId ? $order->driverId : ""}} </p> 
						<p><b>Date:</b></p>
					</div>
				</div>
			</div>
			<div class="col-xs-4">
				<div class="sign">
				</div>
				<div class="row">
					<div class="sign-name">
						<p><b>Site Receiver</b></p>
						<p><b>Name:</b></p>
						<p><b>StaffId:</b> {{$order->qr ? $order->qr : ""}} </p> 
						<p><b>Date:</b></p>
					</div>
				</div>
			</div>
		</div>

		<div class="row">
			<div class="col-xs-4">
				<b>Site Name:</b> {{$order->driverlocation ? $order->driverlocation : "UNKNOWN"}}
			</div>
		</div>
		<div class="row">
			<div class="col-xs-4">
				<b>Lat Long:</b> {{$order->latitude1 ? $order->latitude1." ," : ""}}  {{$order->longitude1 ? $order->longitude1 : ""}}
			</div>
		</div>
	</section>
</body>
</html>