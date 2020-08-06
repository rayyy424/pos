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

		.right
		{
			position: absolute;
    		right: 10px;
    		/*top: 5px;*/
		}
		.right-center
		{
			position: absolute;
    		right: 50px;
    		/*top: 5px;*/
		}
		.left-center
		{
			position: absolute;
    		left: 120px;
    		/*top: 5px;*/
		}
		.right2
		{
			position: absolute;
    		right: 5px;
    		/*text-align: center;*/
    		/*top: 5px;*/
		}
		.title
		{
			position: absolute;
    		/*left: 10px;*/
    		/*top: 2px;*/
		}
		.data
		{
			position: absolute;
    		left: 70px;
    		/*top: 0px;*/
		}
		.title2
		{
			position: absolute;
    		/*left: 10px;*/
    		top: 13px;
		}
		.data2
		{
			position: absolute;
    		left: 70px;
    		top: 13px;
		}
		.title3
		{
			position: absolute;
    		/*left: 10px;*/
    		top: 26px;
		}
		.data3
		{
			position: absolute;
    		left: 70px;
    		top: 26px;
		}
		.title4
		{
			position: absolute;
    		/*left: 10px;*/
    		top: 39px;
		}
		.data4
		{
			position: absolute;
    		left: 70px;
    		top: 39px;
		}
		.title5
		{
			position: absolute;
    		/*left: 10px;*/
    		top: 52px;
		}
		.data5
		{
			position: absolute;
    		left: 70px;
    		top: 52px;
		}
		.title6
		{
			position: absolute;
    		/*left: 10px;*/
    		top: 65px;
		}
		.data6
		{
			position: absolute;
    		left: 70px;
    		top: 65px;
		}
		.title7
		{
			position: absolute;
    		/*left: 10px;*/
    		top: 78px;
		}
		.data7
		{
			position: absolute;
    		left: 70px;
    		top: 78px;
		}

	</style>
</head>

<body onload="window.print();">
	<!-- <body> -->
	<header>	
		<h3><b>{{$invoice->Company_Name}}</b></h3>
		<p class="no-margin">{{$invoice->Address}}</p>
		<p>Tel: {{$invoice->Office_No}}  Fax: {{$invoice->Fax_No}}</p>
		<br>
		<hr>
	</header>
	<section class="content">
		<br><br><br>
		<h4 class="text-center" style=""><b>INVOICE</b></h4>
		<br>
		<div class="row">

			<!-- TO -->
			<div class="col-xs-7">
				<div class="row">
					<div class="col-xs-1">
						<p><b></b></p>
					</div> 
					<div class="col-xs-10">
						<p><b>{{$invoice->client_company}}</b></p>
					</div>
				</div>
				<br>
				<div class="row">
					<div class="col-xs-1">
						<p><b></b></p>
					</div>
					<div class="col-xs-10">
						<p>{{$invoice->client_address}}</p>
					</div>
				</div>
				<br><br><br><br><br><br>
				<div class="row">
					<div class="col-xs-1">
						<p><b></b></p>
					</div>
					<div class="col-xs-10">
						<p>TEL: {{$invoice->client_no}} FAX:  {{$invoice->client_fax}}</p>
					</div>
				</div>
				<br>
				<div class="row">
					<div class="col-xs-1">
						<p><b></b></p>
					</div> 
					<div class="col-xs-10">
						<p>ATTN: {{$invoice->attention}}</p>
					</div>
				</div>

			</div>

			<div class="col-xs-5">
				<div class="row">
					<div class="title">
						<b>NO </b>
					</div>
					<div class="data">
						<b>: {{$invoice->invoice_number}}</b>
					</div>
				</div>
				<div class="row">
					<div class="title2">
						DATE
					</div>
					<div class="data2">
						: {{date('d-m-Y',strtotime($invoice->invoice_date))}}
					</div>
				</div>
				<div class="row">
					<div class="title3">
						P.O NO.
					</div>
					<div class="data3">
						: {{$invoice->po}}
					</div>
				</div>
				<div class="row">
					<div class="title4">
						D.O NO.
					</div>
					<div class="data4">
						: {{$invoice->DO_No}}
					</div>
				</div>
				<div class="row">
					<div class="title5">
						DEPT
					</div>
					<div class="data5">
						: GENSET
					</div>
				</div>
				<div class="row">
					<div class="title6">
						PROJ CODE
					</div>
					<div class="data6">
						: {{$invoice->type}}
					</div>
				</div>
				<div class="row">
					<div class="title7">
						TERM
					</div>
					<div class="data7">
						: {{$invoice->term}}
					</div>
				</div>
			</div>
		</div>
		<br><br><br><br><br><br><br>
		<br><hr><br>

		<div class="row">
			<div class="col-xs-12 list">
				<table border="1" >
					<thead>
						<tr>
							<th style="text-align:center;">No.</th>
		                    <th style="text-align:center;">Item Description</th>
		                    <th style="text-align:center;">Qty</th>
		                    <th style="text-align:center;">Unit</th>
		                    <th style="text-align:center;">Unit Price (RM)</th>
		                    <th style="text-align:center;">Total (RM)</th>
						</tr>
					</thead>
					<tbody>
						<?php $i=1;?>
						@foreach($soitem as $soitem)
						<tr>
							<td style="text-align:center">{{$soitem->item_no}}</td>
							<td style="text-align:left;">
								{{$soitem->description}}
								@if($invoice->type == "GSC")
									@if(strpos($soitem->description,'KVA')!= false)
									@if($item != null)
									<p>MACHINE NO:<b>{{$item->Item_Code}}</b></p>
									<p>ENGINE NO:<b>{{$item->engine_no}}</b></p>
									@endif
									@if($tray != null)
									<p>TRAY : <b>OIL TRAY</b></p>
									@endif
									@endif
								@endif
							</td>
							<td style="text-align:center;">{{$soitem->qty}}</td>
							<td style="text-align:center;">{{$soitem->unit}}</td>
							<td style="text-align:center;">{{$soitem->price}}</td>
							<td style="text-align:center;">{{$soitem->total}}</td>
						</tr>
						<?php $i+=1;?>
						@endforeach
					</tbody>
				</table>
				<br>
					<div class="row">
						<div class="col-xs-6">
							<p style="text-align: center;"><b>Site Name</b><br>{{$invoice->site}}</p>
						</div>
						<div class="col-xs-10">
						<div class="right-center"><b>Rental Period</b></div>
						<br>
						<div class="right">{{$invoice->rental_start}} to {{$invoice->rental_end}}</div>
						</div>
					</div>
			
				<br><br>
				@if($invoice->remarks != "" || $invoice->remarks != NULL)
				<br>
					<div class="row">
					<div class="col-xs-12">
						<p> Remarks : {{$invoice->remarks}} </p>
					</div>
				</div>
				<br>
				@endif
				<br><hr><br>
				<div class="row">
					<div class="col-xs-1">
					</div>
					<div class="col-xs-10">
					RINGGIT MALAYSIA {{$words}} ONLY
					<div class="right2">Total: {{$invoice->total_amount}}</div>				
					</div>
				</div>
				<br>
				<br><hr><br><br>
				<div class="row">
					<div class="col-xs-6">
					Notes:
					<br>
					1. Please officially notify us within 7 days if you have dispute to this invoice, otherwise it will be treated as correct & effective
					<br>
					2. We reserve the right to charge 1.5% interest per month on any outstanding amount due after the stipulated terms of payment.
					<br>
					3. All cheques should be crossed and made payable to 
					<br>
					<br>{{$invoice->Company_Name}}{{$invoice->bank}} A/C NO: {{$invoice->bank_acct}}
					<br>
					<br>
					<b>This is computer generated. No signature is required.</b>
				</div>
				</div>
			</div>
		</div>
		</div>

	</section>
</body>
</html>
