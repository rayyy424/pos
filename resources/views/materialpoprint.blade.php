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
		/* @page { 
			margin: 50px 50px; 
		} */

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

 <body onload="window.print();"> 
	<body>
	<header>	
		<h3><b>{{$company->Company_Name}}</b></h3>
		<p class="no-margin">{{$company->Address}}</p>
		<p>Tel: {{$company->Office_No}}  Fax: {{$company->Fax_No}}</p>
		<br>
		<hr>
	</header>

	<section class="content">
		<h4 class="text-center" style=""><b>E-PURCHASE ORDER</b></h4>
		<br>
		<div class="row">

			<!-- TO -->
			<div class="col-xs-7">
				<div class="row">
					<div class="col-xs-1">
						<p><b></b></p>
					</div> 
					<div class="col-xs-10">
						<p><b>{{$detail->clientCompany}}</b></p>
					</div>
				</div>
			
				<div class="row">
					<div class="col-xs-1">
						<p><b></b></p>
					</div>
					<div class="col-xs-10">
						<p>{{$detail->clientAddress}}</p>
					</div>
				</div>
				<div class="row">
					<div class="col-xs-1">
						<p><b></b></p>
					</div>
					<div class="col-xs-10">
						<p>Tel :{{$detail->clientOffice}}         &nbsp&nbsp Fax:  {{$detail->clientFax}}</p>
					</div>
                </div>
                <br>
                <div class="row">
					<div class="col-xs-1">
						<p><b></b></p>
					</div> 
					<div class="col-xs-10">
						<p>Attn :{{$detail->Person_In_Charge}} &nbsp;&nbsp; Contact No :{{$detail->Contact_No}}</p>
					</div>
				</div>
			</div>

			<!-- DO details -->
			<div class="col-xs-5">
				<div class="row">
					<div class="col-xs-6">
						<p><b>NO:</b></p>
					</div>
					<div class="col-xs-6">
						<p><b>&nbsp&nbsp{{$detail->PO_No}}</b></p>
					</div>
				</div>
				<div class="row">
					<div class="col-xs-6">
						<p>Date:</p>
					</div>
					<div class="col-xs-6">
						<p>&nbsp&nbsp{{date('d-m-Y',strtotime($detail->created_at))}}</p>
					</div>
				</div>
				<div class="row">
					<div class="col-xs-6">
						<p>MR NO:</p>
					</div>
					<div class="col-xs-6">
						<p>&nbsp&nbsp{{$detail->MR_No}}</p>
					</div>
                </div>
                <div class="row">
					<div class="col-xs-6">
						<p>Terms:</p>
					</div>
					<div class="col-xs-6">
						<p>&nbsp&nbsp {{$detail->Terms}}</p>
					</div>
				</div>
				 <div class="row">
					<div class="col-xs-6">
						<p>DEPT:</p>
					</div>
					<div class="col-xs-6">
						<p>&nbsp&nbsp CME</p>
					</div>
				</div>
				<div class="row">
					<div class="col-xs-6">
						<p>Delivery Date:</p>
					</div>
					<div class="col-xs-6">
						<p>&nbsp&nbsp {{$detail->Delivery_Date == null ? "-":$detail->Delivery_Date}}</p>
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
                            <th style="text-align:center;">Description</th>
                            <th style="text-align:center">Acc No</th>
		                    <th style="text-align:center;">Qty</th>
		                    <th style="text-align:center;">UOM</th>
		                    <th style="text-align:center;">U/Price (RM)</th>
		                    <th style="text-align:center;">Total (RM)</th>
						</tr>
					</thead>
					<tbody>
                        <?php $i=1;?>
						@foreach($items as $item)
						<tr>
							<td valign="top" style="text-align:center">{{$i}}</td>
                            <td style="text-align:left;">
                                <p>{!!$item->Description!!}</p>
                                @if($item->Add_Description != "")
                                <p style="white-space: pre-wrap;">{!!$item->Add_Description!!}</p>
                                @endif
                            </td>
                            <td valign="top" style="text-align:center;">{{$item->AccNo}}</td>
							<td valign="top" style="text-align:center;">{{$item->Qty}}</td>
							<td valign="top" style="text-align:center;">{{$item->Unit}}</td>
							<td valign="top" style="text-align:center;">{{$item->Price}}</td>
							<td valign="top" style="text-align:center;">{{number_format($item->total,2)}}</td>
						</tr>
						<?php $i+=1;?>
						@endforeach
                    </tbody>
                    {{-- <tfoot style="border: none">
                        <tr>
                            <td></td>
                            <td colspan="6"></td>
                        </tr>
                    </tfoot> --}}
				</table>
				@if($extra)
				<br>
				{{-- <div class="row">
					<div class="col-xs-4">
						<p>{{$detail->Extra}}</p>
					</div>
					div.col
				</div> --}}
				<table>
					<tbody>
						@foreach($extra as $ext)
						<tr>
							@if($ext->OptionsId == 0)
							<td style="white-space:pre-wrap;">{{$ext->Value}}</td>
							@else
							<td>{{$ext->Option}}</td>
							@endif
						</tr>
						@endforeach
					</tbody>
				</table>
				@endif
				<br>
				@if($payment)
				<br>
				<table>
					<tbody>
						<tr>
							<th><u>TERM OF PAYMENT</u></th>
						</tr>
						@foreach($payment as $pay)
						<tr>
							@if($pay->OptionsId == 0)
							<td style="white-space:pre-wrap;">{{$pay->Value}}</td>
							@else
							<td>{{$pay->Option}}</td>
							@endif
						</tr>
						@endforeach
					</tbody>
				</table>
				@endif
				
                {{-- <div class="row">
                    <div class="col-xs-12">
						<p>SITE ID: {{$detail->SiteId}}</p>
                        <p>SITE NAME: {{$detail->SiteName}}</p>
                    </div>
                </div> --}}
				<br>
				<div class="row">
				<div class="col-xs-6">
					{{-- <p style="text-align: center;"><b>Site Name</b><br>{{$so->site}}</p> --}}
				</div>
				<div class="col-xs-6">
					{{-- <p style="text-align: center;"><b>Rental Period</b><br>{{$so->rental_start}} to {{$so->rental_end}}</p> --}}
				</div>
				</div>
				<br><hr><br>
				<div class="row">
				<div class="col-xs-8">
					<p>RINGGIT MALAYSIA {{$words}} ONLY</p>
				</div>
				<div class="col-xs-4">
				<p align="right">Total: <input type="textarea" value="{{number_format($detail->total,2)}}" readonly="" style="text-align: right"></p>
				</div>
				</div>

				<br><hr><br>
			
				{{-- <div class="row signature">
			<div class="col-xs-4">
				<div class="sign">
				</div>
				<div class="row">
					<div class="sign-name">
						<p align="center"><b>Authorised Signature</b></p>
					</div>
				</div>
			</div>
		</div> --}}
		</div>

	</section>
</body>
</html>
