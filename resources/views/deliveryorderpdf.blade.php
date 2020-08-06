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
			padding-bottom: 0px;
			text-align: center;
			width: 100%;
			height: 5%;	
			display: block;
			margin-bottom: 0;
		}

		h4 {
			font-size: 16px;
			margin: 0px !important;
			padding-top: 5px;
			padding-bottom: 5px;
		}

		p {
			white-space: pre-line;
			margin-bottom: 0;
			margin-top: 0;
		}

		hr {
			margin-top: 5px;
            margin-bottom: 5px;
			border-top: 1px solid black;
		}

		.content {
			min-height: 250px;
			padding: 10px;
			margin-right: auto;
			margin-left: auto;
			/*padding-left: 10px;*/
			/*padding-right: 10px;*/
			padding-top: 0px;
		}

		table {
		    width:100%;
		    font-size:12px;
		    page-break-inside:auto;
		    border-collapse: collapse;
		    border-top: 1px solid black;
		    border-bottom: 1px solid black;
		}

		tr { 
		    page-break-inside:avoid; 
		    page-break-after:auto 
		}

		table th {
			font-weight: lighter;
		  	border-bottom: 1px solid black;
		}
		.cno
		{
			position: absolute;
    		left: 495px;
    		top: 100px;
		}
		.cdata
		{
			position: absolute;
    		left: 520px;
    		top: 100px;
		}
        .tocompany
		{
			position: absolute;
            left:70px;
    		top: 135px;
		}
		.toaddress
		{
			position: absolute;
            left:70px;
    		top: 148px;
		}
		.tophone
		{
			position: absolute;
            left:70px;
    		top: 213px;
		}
		.title
		{
			font-weight: bold;
			position: absolute;
    		left: 450px;
    		top: 134px;
		}
		.data
		{
			font-weight: bold;
			position: absolute;
    		left: 520px;
    		top: 134px;
		}
		.title2
		{
			position: absolute;
    		left: 450px;
    		top: 149px;
		}
		.data2
		{
			position: absolute;
    		left: 520px;
    		top: 149px;
		}
		.title3
		{
			position: absolute;
    		left: 450px;
    		top: 164px;
		}
		.data3
		{
			position: absolute;
    		left: 520px;
    		top: 164px;
		}
		.title4
		{
			position: absolute;
    		left: 450px;
    		top: 179px;
		}
		.data4
		{
			position: absolute;
    		left: 520px;
    		top: 179px;
		}
		.title5
		{
			position: absolute;
    		left: 450px;
    		top: 194px;
		}
		.data5
		{
			position: absolute;
    		left: 520px;
    		top: 194px;
		}
		.title6
		{
			position: absolute;
    		left: 450px;
    		top: 209px;
		}
		.data6
		{
			position: absolute;
    		left: 520px;
    		top: 209px;
		}
		.title7
		{
			position: absolute;
    		left: 450px;
    		top: 224px;
		}
		.data7
		{
			position: absolute;
    		left: 520px;
    		top: 224px;
		}
		.title8
		{
			position: absolute;
    		left: 450px;
    		top: 239px;
		}
		.data8
		{
			position: absolute;
    		left: 520px;
    		top: 239px;
		}
		.reason
		{
			position: absolute;
    		left: 70px;
    		top: 305px;
            width: 50%;
		}
		.col-xs-4{
			width : 33.333%;
		}
		.sign {
			border-bottom: 1px solid black;
			width: 100%;
			height: 40px;
		}
		.site{
			position: absolute;
			bottom: 360px;
		}
		.sitecor{
			position: absolute;
			bottom: 345px;
		}
		.issue{
			position: absolute;
			width: 30%;
			bottom:390px;
			/*left: 25px;*/
		}
		.driver{
			position: absolute;
			width: 30%;
			bottom:339px;
			left: 33.333%;
		}
		.receive{
			position: absolute;
			width: 30%;
			bottom:339px;
			left: 66.666%;
		}
		.sign-name1{
			left: 15px
		}
		.signatureimg
		{
			width: 150px;
			height: 100px;
			bottom: 300px;
			/*left: 70%;*/
			/*margin-bottom: 10px;*/
			/*right: 15px;*/
		}
	</style>
</head>
<body>
	<header>	
		@if($comp)
		<h3><b>{{$comp[0]->Company_Name}}</b></h3>
		<p class="no-margin">{{$comp[0]->Address}}</p>
		<p>Tel: {{$comp[0]->Office_No}}  Fax: {{$comp[0]->Fax_No}}</p>
		@else
		<h3><b>{{$order->Company_Name}}</b></h3>
		<p class="no-margin">{{$order->address}}</p>
		<p>Tel: {{$order->Company_No}}  Fax: {{$order->Fax_No}}</p>
		@endif
	</header>
	<section class="content">
		<br><br>
        <hr>
		<h4 style="text-align:center"><b>E-DELIVERY ORDER</b></h4>
		<br><br>
		<div class="row">

			<!-- TO -->
				<div class="row">
					<div class="tocompany">
						<p>{{$comp ? $comp[1]->Company_Name : $order->clientName}}</p>
					</div>
				</div>
				<div class="row">
					<div class="toaddress">
						<p>
							{{$comp ? $comp[1]->Address : $order->clientAddress}}
						</p>
					</div>
				</div>
				<div class="row">
					<div class="tophone">
						<p>TEL : {{$comp ? $comp[1]->Office_No : $order->clientNum}}	FAX : {{$comp ? $comp[1]->Fax_No : $order->clientFax}}</p>
					</div>
				</div>

				<div class="row">
					<div class="title">
						NO
					</div>
					<div class="data">
						:  {{$order->DO_No}}
					</div>
				</div>
				<div class="row">
					<div class="title2">
						Date
					</div>
					<div class="data2">
						:  {{date('d-m-Y',strtotime($order->delivery_date))}}
					</div>
				</div>
				<div class="row">
					<div class="title3">
						Term
					</div>
					<div class="data3">
						:  {{$order->term}}
					</div>
				</div>
				<div class="row">
					<div class="title4">
						P.O NO
					</div>
					<div class="data4">
						:  {{$order->po}}
					</div>
				</div>
				 <div class="row">
					<div class="title5">
						M.R Ref. No
					</div>
					<div class="data5">
						:  
					</div>
				</div>
				<div class="row">
					<div class="title6">
						Site
					</div>
					<div class="data6">  
						:  {{$order->Location_Name}}
					</div>
				</div>
				<div class="row">
					<div class="title7">
						Contact
					</div>
					<div class="data7">
						:  {{$order->PIC_Name}}  {{$order->PIC_Contact}}
					</div>
				</div>
				<div class="row">
					<div class="title8">
						Lat/Long
					</div>
					<div class="data8">
						:  {{$order->Latitude}}     {{$order->Longitude}}
					</div>
				</div>	
		</div>
        <br><br><br><br><br><br>
		<br><hr><br>

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
		<div class="issue">
			<div class="sign">
			</div>
				<div class="sign-name">
				<b>Issued By</b>
				<br>
				<b>Name:</b>{{$order->requestor ? $order->requestor : ""}}
				<br>
				<b>StaffId:</b> {{$order->requestorId ? $order->requestorId : ""}}
				<br>
				<b>Date:</b> {{$order->requestdate ? $order->requestdate : ""}}
				</div>
		</div>
		<div class="driver">
			<div class="sign">
			</div>
				<div class="sign-name">
					@if($order->Type == "Truck")
					<b>Truck's Driver</b>
					@else
					<b>Lorry's Driver</b>
					@endif
					<br>
					<b>Name:</b> {{$order->driver ? $order->driver : ""}}
					<br>
					<b>StaffId:</b> {{$order->driverId ? $order->driverId : ""}} 
					<br>
					<b>Date:</b>
				</div>
		</div>
		<div class="receive">
			@if($signature)
			<img src="{{url('')}}{{$signature->Web_Path}}" class="signatureimg">
			@endif
			<div class="sign">
			</div>
			<div class="sign-name">
				<b>Site Receiver</b>
				<br>
				<b>Name:</b>
				<br>
				<b>StaffId:</b> {{$order->qr ? $order->qr : ""}}
				<br>
				<b>Date:</b>
			</div>
		</div>
		<div class="site"> 
			<b>Site Name:</b> {{$order->driverlocation ? $order->driverlocation : "UNKNOWN"}}
		</div>
		<div class="sitecor">
			<b>Lat Long:</b> {{$order->latitude1 ? $order->latitude1." ," : ""}}  {{$order->longitude1 ? $order->longitude1 : ""}}
		</div>


	</section>
</body>
</html>