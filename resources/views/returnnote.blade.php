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
			max-height: 60px;
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
		<img src="{{$return->Web_Path}}" class="text-center">
		<h3><b>{{$return->Company_Name}}</b></h3>
		<p class="no-margin">{{$return->address}}</p>
		<p>TEL: {{$return->Contact_No}} &nbsp&nbsp&nbsp&nbsp&nbsp FAX: {{$return->Fax_No}}</p>
		<hr>
	</header>

	<section class="content">
		<h4 class="text-center" style=""><b>RETURN NOTE</b></h4>

		<!-- address -->
		<div class="row">

			<!-- TO -->
			<div class="col-xs-7">
				<div class="row">
					<div class="col-xs-1">
						<p><b>TO:</b></p>
					</div>
					<div class="col-xs-10">
						<p><b>{{$return->clientName}}</b></p>
					</div>
				</div>
				<div class="row">
					<div class="col-xs-1">
						<p><b></b></p>
					</div>
					<div class="col-xs-10">
						<p>{{$return->clientAddress}}<!--Network Operation Center, Wimax Data Center, Jalan Strachan off Jalan Ipoh, Sentul, 55100 Kuala Lumpur, Malaysia.--></p>
					</div>
				</div>
				<div class="row">
					<div class="col-xs-1">
						<p><b></b></p>
					</div>
					<div class="col-xs-10">
						<p>TEL:  {{$return->clientNum}}</p>
					</div>
				</div>
				<div class="row">
					<div class="col-xs-1">
						<p><b></b></p>
					</div>
					<div class="col-xs-10">
						<p>FAX:  {{$return->clientFax}}</p>
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
						<p><b>:&nbsp&nbsp{{$return->DO_No}}</b></p>
					</div>
				</div>
				<div class="row">
					<div class="col-xs-4">
						<p>Date</p>
					</div>
					<div class="col-xs-8">
						<p>:&nbsp&nbsp{{date('d-m-Y',strtotime($return->delivery_date))}}</p>
					</div>
				</div>
				<div class="row">
					<div class="col-xs-4">
						<p>Term</p>
					</div>
					<div class="col-xs-8">
						<p>:&nbsp&nbsp{{$return->term}}</p>
					</div>
				</div>
				<div class="row">
					<div class="col-xs-4">
						<p>P.O NO.</p>
					</div>
					<div class="col-xs-8">
						<p>:&nbsp&nbsp{{$return->po}}</p>
					</div>
				</div>
				<div class="row">
					<div class="col-xs-4">
						<p>Site</p>
					</div>
					<div class="col-xs-8">
						<p>:&nbsp&nbsp{{$return->Location_Name}}</p>
					</div>
				</div>
				<div class="row">
					<div class="col-xs-4">
						<p>Contact</p>
					</div>
					<div class="col-xs-8">
						<p>:&nbsp&nbsp{{$return->PIC_Name}}  {{$return->PIC_Contact}}</p>
					</div>
				</div>
				{{-- <div class="row">
					<div class="col-xs-4">
						<p></p>
					</div>
					<div class="col-xs-8">
						<p>:&nbsp&nbsp</p>
					</div>
				</div> --}}
				<div class="row">
					<div class="col-xs-4">
						<p>Lat/Long</p>
					</div>
					<div class="col-xs-8">
						<p>:&nbsp&nbsp{{$return->Latitude}}&nbsp&nbsp&nbsp&nbsp{{$return->Longitude}}</p>
					</div>
				</div>
				<div class="row">
					<div class="col-xs-4">
						<p>M.R Ref. No</p>
					</div>
					<div class="col-xs-8">
						<p>:&nbsp&nbsp</p>
					</div>
				</div>
			</div>
		</div>

		<br><br>

		<!-- table item -->
		<div class="row">
			<div class="col-xs-12 list">
				<table border="1">
					<thead>
						<tr>
							<th style="text-align:center;">No.</th>
							<th style="text-align:center;">Genset No.</th>
							<th style="text-align:center;">Quantity</th>
		                    <th style="text-align:center;">Description</th>
		                    <th style="text-align:center;">Additional Description</th>
						</tr>
					</thead>
					<tbody>
						<?php $i=1;?>
						@foreach($items as $item)
						<tr>
							<td style="text-align:center">{{$i}}</td>
							<td style="text-align:center">{{$item->Item_Code}}</td>
							<td style="text-align:center">{{$item->Qty_request}}</td>
							<td>
								<p style="white-space: pre-wrap;">{{$item->Description}}</p>
							</td>
							<td style="white-space: pre-wrap;">{{$item->add_desc}}</td>
						</tr>
						<?php $i+=1;?>
						@endforeach
						<!--
						<tr>
								<td style="width:20px;text-align:center">1</td>
								<td style="width:300px;text-align:left;">
									<b>Rental of Silent Type Generator Set</b>
									<div class="row">
										<div class="col-xs-4">
											<p>Capacity</p>
										</div>
										<div class="col-xs-8">
											<p>:&nbsp&nbsp10&nbsp&nbspKPA</p>
										</div>
									</div>
									<div class="row">
										<div class="col-xs-4">
											<p>Canopy No.</p>
										</div>
										<div class="col-xs-8">
											<p>:&nbsp&nbspSC475</p>
										</div>
									</div>
									<div class="row">
										<div class="col-xs-4">
											<p>Engine No.</p>
										</div>
										<div class="col-xs-8">
											<p>:&nbsp&nbsp1611166</p>
										</div>
									</div>
									1 Units N70 Battery
								</td>
								<td style="width:100px;text-align:center;"></td>
								<td style="width:200px;text-align:left;">Exchange Genset SC097</td>
							</tr>
						<tr>
							<td style="width:20px;text-align:center">2</td>
							<td style="width:300px;text-align:left;">
								<b>Rental of Silent Type Generator Set</b>
								<div class="row">
									<div class="col-xs-4">
										<p>Capacity</p>
									</div>
									<div class="col-xs-8">
										<p>:&nbsp&nbsp10&nbsp&nbspKPA</p>
									</div>
								</div>
								<div class="row">
									<div class="col-xs-4">
										<p>Canopy No.</p>
									</div>
									<div class="col-xs-8">
										<p>:&nbsp&nbspSC475</p>
									</div>
								</div>
								<div class="row">
									<div class="col-xs-4">
										<p>Engine No.</p>
									</div>
									<div class="col-xs-8">
										<p>:&nbsp&nbsp1611166</p>
									</div>
								</div>
								1 Units N70 Battery
							</td>
							<td style="width:100px;text-align:center;"></td>
							<td style="width:200px;text-align:left;">Exchange Genset SC097</td>
						</tr>
						<tr>
							<td style="width:20px;text-align:center">3</td>
							<td style="width:300px;text-align:left;">
								<div class="row">
									<div class="col-xs-4">
										<p><b>Diesel Tank</b></p>
									</div>
									<div class="col-xs-8">
										<p>: &nbsp&nbsp B132</p>
									</div>
								</div>
							</td>
							<td style="width:100px;text-align:center;"></td>
							<td style="width:200px;text-align:left;"></td>
						</tr>
						<tr>
							<td style="width:20px;text-align:center">4</td>
							<td style="width:300px;text-align:left;">
								<div class="row">
									<div class="col-xs-4">
										<p><b>Diesel Quantity</b></p>
									</div>
									<div class="col-xs-8">
										<p>: &nbsp&nbsp 10 &nbsp Litre</p>
									</div>
								</div>
							</td>
							<td style="width:100px;text-align:center;"></td>
							<td style="width:200px;text-align:left;"></td>
						</tr>
						<tr>
							<td style="width:20px;text-align:center">5</td>
							<td style="width:300px;text-align:left;">
								<b>Others</b>
								<div class="row">
									<div class="col-xs-4">
										<p>Cable Length</p>
									</div>
									<div class="col-xs-8">
										<p>:</p>
									</div>
								</div>
								<div class="row">
									<div class="col-xs-4">
										<p>Oil Tray</p>
									</div>
									<div class="col-xs-8">
										<p>:</p>
									</div>
								</div>
								<div class="row">
									<div class="col-xs-4">
										<p>Padlock</p>
									</div>
									<div class="col-xs-8">
										<p>:</p>
									</div>
								</div>
							</td>
							<td style="width:100px;text-align:center;"></td>
							<td style="width:200px;text-align:left;"></td>
						</tr>
						<tr>
							<td style="width:20px;text-align:center">6</td>
							<td style="width:300px;text-align:left;">
								<div class="row">
									<div class="col-xs-4">
										<p><b>ATS</b></p>
									</div>
									<div class="col-xs-8">
										<p>:</p>
									</div>
								</div>
							</td>
							<td style="width:100px;text-align:center;"></td>
							<td style="width:200px;text-align:left;"></td>
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
				<p><b>Remarks</b>:<i>{{$return->Remarks}}</i></p>
				<br>
				<hr>
				<p>The parties hereto agree the goods & item status are receive in good condition and in order</p>
			</div>
		</div>

		<br>
		<div class="row signature">
			<div class="col-xs-3">
				<div class="sign">
				</div>
				<div class="row">
					<div class="sign-name">
						<p><b>Issued By</b></p>
						<p><b>Name:</b></p>
						<p><b>Date:</b></p>
					</div>
				</div>
			</div>
			<div class="col-xs-3">
				<div class="sign">
				</div>
				<div class="row">
					<div class="sign-name">
						<p><b>Check By</b></p>
						<p><b>Name:</b></p>
						<p><b>Date:</b></p>
					</div>
				</div>
			</div>
			<div class="col-xs-3">
				<div class="sign">
				</div>
				<div class="row">
					<div class="sign-name">
						<p><b>Deliver By</b></p>
						<p><b>Name:</b></p>
						<p><b>Date:</b></p>
					</div>
				</div>
			</div>
			<div class="col-xs-3">
				<div class="sign">
				</div>
				<div class="row">
					<div class="sign-name">
						<p><b>Verify By</b></p>
						<p><b>Name:</b></p>
						<p><b>Date:</b></p>
					</div>
				</div>
			</div>
		</div>
	</section>
</body>
</html>