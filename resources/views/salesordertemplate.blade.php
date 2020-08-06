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

	</style>
</head>
@if($history == null)
<body onload="window.print();">
@else
<body>
@endif
	<!-- <body> -->
	<header>	
		<h3><b>{{$so->Company_Name}}</b></h3>
		<p class="no-margin">{{$so->Address}}</p>
		<p>Tel: {{$so->Office_No}}  Fax: {{$so->Fax_No}}</p>
		<br>
		<hr>
	</header>

	<section class="content">
		<h4 class="text-center" style=""><b>SALES ORDER</b></h4>
		<br>
		<div class="row">

			<!-- TO -->
			<div class="col-xs-7">
				<div class="row">
					<div class="col-xs-1">
						<p><b></b></p>
					</div> 
					<div class="col-xs-10">
						<p><b>{{$so->client_company}}</b></p>
					</div>
				</div>
				<div class="row">
					<div class="col-xs-1">
						<p><b></b></p>
					</div> 
					<div class="col-xs-10">
						<p>{{$so->attention}}</p>
					</div>
				</div>
				<div class="row">
					<div class="col-xs-1">
						<p><b></b></p>
					</div>
					<div class="col-xs-10">
						<p>{{$so->client_address}}</p>
					</div>
				</div>
				<div class="row">
					<div class="col-xs-1">
						<p><b></b></p>
					</div>
					<div class="col-xs-10">
						<p>Tel :{{$so->client_no}}         &nbsp&nbsp Fax:  {{$so->client_fax}}</p>
					</div>
				</div>
			</div>

			<!-- DO details -->
			<div class="col-xs-5">
				<div class="row">
					<div class="col-xs-4">
						<p><b>NO</b></p>
					</div>
					<div class="col-xs-8">
						<p><b>:&nbsp&nbsp{{$so->SO_Number}}</b></p>
					</div>
				</div>
				<div class="row">
					<div class="col-xs-4">
						<p>Date</p>
					</div>
					<div class="col-xs-8">
						<p>:&nbsp&nbsp{{date('d-m-Y',strtotime($so->date))}}</p>
					</div>
				</div>
				<div class="row">
					<div class="col-xs-4">
						<p>P.O NO.</p>
					</div>
					<div class="col-xs-8">
						<p>:&nbsp&nbsp {{$so->po}}</p>
					</div>
				</div>
				 <div class="row">
					<div class="col-xs-4">
						<p>DEPT</p>
					</div>
					<div class="col-xs-8">
						<p>:&nbsp&nbsp GENSET</p>
					</div>
				</div>
				<div class="row">
					<div class="col-xs-4">
						<p>PROJECT NO</p>
					</div>
					<div class="col-xs-8">
						<p>:&nbsp&nbsp {{$so->type}}</p>
					</div>
				</div>
				<div class="row">
					<div class="col-xs-4">
						<p>TERM</p>
					</div>
					<div class="col-xs-8">
						<p>:&nbsp&nbsp {{$so->term}}</p>
					</div>
				</div>
				
					</div>
		</div>
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
						@foreach($item as $item)
						<tr>
							<td style="text-align:center">{{$i}}</td>
							<td style="text-align:left;"><p>{{$item->description}}</p>
							</td>
							<td style="text-align:center;">{{$item->qty}}</td>
							<td style="text-align:center;">{{$item->unit}}</td>
							<td style="text-align:center;">{{$item->price}}</td>
							<td style="text-align:center;">{{$item->total}}</td>
						</tr>
						<?php $i+=1;?>
						@endforeach
					</tbody>
				</table>
				<br>
				<div class="row">
				<div class="col-xs-6">
					<p style="text-align: center;"><b>Site Name</b><br>{{$so->site}}</p>
				</div>
				<div class="col-xs-6">
					<p style="text-align: center;"><b>Rental Period</b><br>{{$so->rental_start}} to {{$so->rental_end}}</p>
				</div>
				</div>
				<br><hr><br>
				<div class="row">
				<div class="col-xs-6">
					<p>RINGGIT MALAYSIA {{$words}} ONLY</p>
				</div>
				<div class="col-xs-6">
				<p align="right">Total: <input type="textarea" value="{{$grandtotal}}" readonly="" style="text-align: right"></p>
				</div>
				</div>

				<br><hr><br>
				<div class="row">
					<div class="col-xs-4">
					Notes:
					<br>
					1. All cheques should be crossed and made payable to {{$so->Company_Name}}
					<br>
					2. Goods sold are neither returnable nor refundable. Otherwise a cancellation  fee of 20% on purchase price will be imposed.
				</div>
				</div>
				<div class="row signature">
			<div class="col-xs-4">
				<div class="sign">
				</div>
				<div class="row">
					<div class="sign-name">
						<p align="center"><b>Authorised Signature</b></p>
					</div>
				</div>
			</div>
		</div>
		</div>

	</section>
</body>
</html>
