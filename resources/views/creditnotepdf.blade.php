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
			position: absolute;
    		left: 450px;
    		top: 134px;
		}
		.data
		{
			position: absolute;
    		left: 520px;
    		top: 134px;
		}
		.title2
		{
			position: absolute;
    		left: 450px;
    		top: 154px;
		}
		.data2
		{
			position: absolute;
    		left: 520px;
    		top: 154px;
		}
		.title3
		{
			position: absolute;
    		left: 450px;
    		top: 174px;
		}
		.data3
		{
			position: absolute;
    		left: 520px;
    		top: 174px;
		}
		.title4
		{
			position: absolute;
    		left: 450px;
    		top: 194px;
		}
		.data4
		{
			position: absolute;
    		left: 520px;
    		top: 194px;
		}
		.title5
		{
			position: absolute;
    		left: 450px;
    		top: 214px;
		}
		.data5
		{
			position: absolute;
    		left: 520px;
    		top: 214px;
		}
        .money
        {
            position: absolute;
            left: 13px;
    		top: 250px;
        }
        .total
        {
            position: absolute;
            right: 13px;
    		top: 230px;
        }
		.reason
		{
			position: absolute;
    		left: 70px;
    		top: 305px;
            width: 50%;
		}
		.reason2
		{
			position: absolute;
            left: 13px;
    		top: 305px;
		}

	</style>
</head>
<body onload="window.print();">
	<header>	
		<h3><b>{{$inv->Company_Name}}</b></h3>
		<p class="no-margin">{{$inv->Address}}</p>
		<p>Tel: {{$inv->Office_No}}  Fax: {{$inv->Fax_No}}</p>
	</header>
	<section class="content">
		<br><br>
        <hr>
		<h4 style="text-align:center"><b>E-CREDIT NOTE</b></h4>
		<div class="row">
			<div class="cno">
				<b>No</b>
			</div>
			<div class="cdata">
				<b>: {{$inv->cn_no}}</b>
			</div>
		</div>
		<br><br>
		<div class="row">

			<!-- TO -->
				<div class="row">
					<div class="tocompany">
						<p>{{$inv->client_company}}</p>
					</div>
				</div>
				<div class="row">
					<div class="toaddress">
						<p>
							{{$inv->client_address}}
						</p>
					</div>
				</div>
				<div class="row">
					<div class="tophone">
						<p>TEL : {{$inv->client_no}}	FAX : {{$inv->client_fax}}</p>
					</div>
				</div>

				<div class="row">
					<div class="title">
						Invoice No
					</div>
					<div class="data">
						:  {{$inv->invoice_number}}
					</div>
				</div>
				<div class="row">
					<div class="title2">
						Invoice Date
					</div>
					<div class="data2">
						:  {{$inv->invoice_date}}
					</div>
				</div>
				<div class="row">
					<div class="title3">
						Attention
					</div>
					<div class="data3">
						:  {{$inv->attn}}
					</div>
				</div>
				<div class="row">
					<div class="title4">
						Terms
					</div>
					<div class="data4">
						:  {{$inv->term}}
					</div>
				</div>
				 <div class="row">
					<div class="title5">
						Date
					</div>
					<div class="data5">
						:  {{$inv->date}}
					</div>
				</div>	
		</div>
        <br><br><br><br>
		<br><hr><br>

		<p>This following are the knock-off documents.</p>
		<br>
		<div class="row">
			<div class="col-xs-12">
				<table>
					<thead>
						<tr>
							<th style="text-align:center;">Item</th>
		                    <th style="text-align:center;">Tax Code</th>
		                    <th style="text-align:center;">Description</th>
							<th style="text-align:center;">Org. Amount</th>
							<th style="text-align:center;">Knock-Off Amount</th>
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
				<br><br><br><br><br><br>
				<br><hr>
                <div class="col-xs-7">
                RINGGIT MALAYSIA {{$totaltext}} ONLY
                </div> 
                <div align="right" class="col-xs-12">
                <b>Total : {{$inv->amount}}</b>
                </div>
                <br>
				<br><hr><br>
                <div class="col-xs-1">
                Reason :
                </div>
                <div class="col-xs-5" style="margin-left:55px">
                {{$inv->reason}}
                </div>
                <br>
                <br>
                <br>
                <br>
                <br>
                <br>
                <br>
                <br>
                <div class="row">
                    <div class="col-xs-6">
                        This is computer generated. No signature is required.
                    </div>
                </div>
			</div>
		</div>
	</section>
</body>
</html>