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
			font-size: 16px;
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

		.right
		{
			position: absolute;
    		right: 5px;
    		/*top: 5px;*/
		}

	</style>
</head>

<body onload="window.print();">
	<header>	
		<h3><b>{{$inv->Company_Name}}</b></h3>
		<p class="no-margin">{{$inv->Address}}</p>
		<p>Tel: {{$inv->Office_No}}  Fax: {{$inv->Fax_No}}</p>
		<br>
		<hr>
	</header>
	<section class="content">
		<div class="row">
			<div class="col-xs-7">
				<h4 style="text-align: right"><b>E-CREDIT NOTE</b></h4>
			</div>
			<div class="col-xs-5">
				<div style="padding-top: 10px" class="row">
					<div class="col-xs-4">
						<p align="right"><b>No</b></p>
					</div>
					<div class="col-xs-8">
						<p><b>:&nbsp&nbsp{{$inv->cn_no}}</b></p>
					</div>
				</div>
			</div>
		</div>
		<div class="row">
			<div class="col-xs-7">
				<div class="row">
					<div class="col-xs-1">
						<p><b></b></p>
					</div> 
					<div class="col-xs-10">
						<p><b></b></p>
					</div>
				</div>
			</div>
			<div class="col-xs-5">
				<div class="row">
					<div class="col-xs-4">
						<p><b></b></p>
					</div>
					<div class="col-xs-8">
						<p><b></b></p>
					</div>
				</div>
			</div>
		</div>
		<div class="row">

			<!-- TO -->
			<div class="col-xs-7">
				<div class="row">
					<div class="col-xs-1">
						<p><b></b></p>
					</div> 
					<div class="col-xs-10">
						<p>{{$inv->client_company}}</p>
					</div>
				</div>
				<div class="row">
					<div class="col-xs-1">
						<p><b></b></p>
					</div>
					<div class="col-xs-6">
						<p>{{$inv->client_address}}</p>
					</div>
				</div>
				<div class="row">
					<div class="col-xs-1">
						<p><b></b></p>
					</div>
					<div class="col-xs-10">
						<p>TEL: {{$inv->client_no}}&nbsp&nbspFAX:  {{$inv->client_fax}}</p>
					</div>
				</div>
			</div>

			<div class="col-xs-5">
				<div class="row">
					<div class="col-xs-4">
						<p>Invoice No</p>
					</div>
					<div class="col-xs-8">
						<p>:&nbsp&nbsp{{$inv->invoice_number}}</p>
					</div>
				</div>
				<div class="row">
					<div class="col-xs-4">
						<p>Invoice Date</p>
					</div>
					<div class="col-xs-8">
						<p>:&nbsp&nbsp{{$inv->invoice_date}}</p>
					</div>
				</div>
				<div class="row">
					<div class="col-xs-4">
						<p>Attention</p>
					</div>
					<div class="col-xs-8">
						<p>:&nbsp&nbsp{{$inv->attn}}</p>
					</div>
				</div>
				<div class="row">
					<div class="col-xs-4">
						<p>Terms</p>
					</div>
					<div class="col-xs-8">
						<p>:&nbsp&nbsp{{$inv->term}}</p>
					</div>
				</div>
				 <div class="row">
					<div class="col-xs-4">
						<p>Date</p>
					</div>
					<div class="col-xs-8">
						<p>:&nbsp&nbsp{{$inv->date}}</p>
					</div>
				</div>
			</div>
		</div>
		<br><hr><br>

		<p>This following are the knock-off documents.</p>
		<br>
		<div class="row">
			<div class="col-xs-12">
				<table>
					<thead>
						<tr>
							<th style="text-align:center;">Item</th>
		                    <th class="col-xs-1" style="text-align:center;">Tax Code</th>
		                    <th style="text-align:center;">Description</th>
							<th class="col-xs-1" style="text-align:center;">Org. Amount</th>
							<th class="col-xs-1" style="text-align:center;">Knock-Off Amount</th>
						</tr>
					</thead>
					<tbody>
						<?php $i = 1 ?>
						@foreach($item as $ikey => $ivalue)
						<tr>
							<td style="text-align:center;">{{$i}}</td>
							<td style="text-align:center;">{{$ivalue->item_no}}</td>
							<td style="text-align:left;">{{$ivalue->description}}</td>
							<td style="text-align:center;">{{$ivalue->amount}}</td>
							<td style="text-align:center;">{{$ivalue->knockoff}}</td>
						</tr>
						<?php $i++ ?>
						@endforeach
					</tbody>
				</table>
				<br><br><br><br><br><br><br>
				<br><hr><br>
				<div class="row">
					<div class="col-xs-7">
						<div class="row">
							<div class="col-xs-12">
								<p>RINGGIT MALAYSIA {{$totaltext}} ONLY</p>
							</div> 
						</div>
					</div>
					<div class="col-xs-5">
						<div class="row" align="right">
							<div class="col-xs-8">
								<b>Total</b>
							</div>
							<div class="col-xs-4">
								<b><input type="textarea" value="{{$inv->amount}}" readonly="" style="text-align: right; width: 100%"></b>
							</div>
						</div>
					</div>
				</div>

				<br><hr><br>
				<div class="row">
					<div class="col-xs-1">
						<p>Reason&nbsp&nbsp:</p>
					</div> 
					<div class="col-xs-5">
						<p>{{$inv->reason}}</p>
					</div>
				</div>
					<br>
					<br>
					<br>
					<br>
					<br>
					<br>
					This is computer generated. No signature is required.
				</div>
			</div>
		</div>
		</div>

	</section>
</body>
</html>
