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
	</header>

	<section class="content">
		<h4 class="text-center" style=""><b>Material Request</b></h4>

		<div class="row">
			<div class="col-xs-12">
				<table border="1">
					<thead>
						<th style="width:25%;">Midascom Network Sdn Bhd</th>
						<th style="text-align:center;"><span >MR COSTING</span>
							<span style="float:right;">Date {{date('d/m/Y',strtotime($detail->created_at))}}</span>
						</th>
					</thead>
				</table>
			</div>
		</div>
		<br><hr><br>
		<br>
		<div class="row">
			<div class="col-xs-12">
				<b>MR NO : </b> {{$detail->MR_No}}
			</div>
		</div>
		<div class="row">
			<div class="col-xs-12">
				<b>Requestor Name: </b> {{$detail->Name}}
			</div>
		</div>
		
		<div class="row">
			<div class="col-xs-12">
				<b>Site Name: </b>{{$detail->site}}
			</div>
		</div>
		<br>
		<!-- table item -->
		<div class="row">
			<div class="col-xs-12 list">
				<table border="1" >
					<thead>
						<tr>
							<th rowspan="2" style="text-align:center;">No.</th>
		                    <th rowspan="2" style="text-align:center;">Item Code</th>
		                    <th rowspan="2" style="text-align:center;">Item Description</th>
                            <th colspan="2" style="text-align:center;">Pre-Con</th>
                            <th colspan="2" style="text-align:center;">Budget Costing</th>
                        </tr>
                        <tr>
                            <th style="text-align:center;">Qty</th>
                            <th style="text-align:center;">Unit</th>
                            <th style="text-align:center;">Unit Price</th>
                            <th style="text-align:center;">Amount</th>

                        </tr>
					</thead>
					<tbody>
						<?php $i=1;$total=0;?>
						@foreach($items as $item)
						<tr>
							<td style="width:20px;text-align:center">{{$i}}</td>
							<td style="width:40px;text-align:center;">{{$item->Item_Code}}</td>
							<td style="width:120px;text-align:left;">
								<p>{{$item->Description}}</p>
                            </td>
							<td style="width:30px;text-align:center;">{{$item->Qty}}</td>
                            <td style="width:30px;text-align:center;">{{$item->Unit}}</td>
                            <td style="width:50px;text-align:center;">RM {{$item->Price}}</td>
							<td style="width:50px;text-align:center;">RM {{number_format($item->Price * $item->Qty,2)}}</td>
							<?php $total+= $item->Price * $item->Qty;?>
						</tr>
						<?php $i+=1;?>
						@endforeach
						<tr>
							<td colspan="6" style="text-align:center;">Total</td>
							<td style="text-align:center;width:50px;">RM {{number_format($total,2)}}</td>
						</tr>
					</tbody>
				</table>
            </div>
		</div>
	</section>
</body>
</html>