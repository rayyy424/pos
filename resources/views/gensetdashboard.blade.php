@extends('app')

@section('content')
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
		<h1>Dashboard</h1>
		<ol class="breadcrumb">
	        <li><a href="#"><i class="fa fa-dashboard"></i> Home</a></li>
	        <li>GENSET Management</li>
	        <li class="active">GENSET Dashboard</li>
	    </ol>
    </section>
    <br>
    <!-- Main content -->
    <section class="content">
    	<div class="row">
    		<div class="col-lg-3 col-xs-3">
	          	<div class="small-box bg-red">
	          		<div class="inner">
		                <h3></h3>
		                <p>GENSET Repairing</p>
	                </div>
	                <a href="{{ url('/deliveryapproval') }}" class="small-box-footer">More info <i class="fa fa-arrow-circle-right"></i></a>
				</div>
	        </div>
	        <div class="col-lg-3 col-xs-3">
	          	<div class="small-box bg-yellow">
	          		<div class="inner">
		                <h3></h3>
		                <p>GENSET On Site</p>
	                </div>
	                <a href="{{ url('/deliveryapproval') }}" class="small-box-footer">More info <i class="fa fa-arrow-circle-right"></i></a>
				</div>
	        </div>
	        <div class="col-lg-3 col-xs-3">
	          	<div class="small-box bg-green">
	          		<div class="inner">
		                <h3></h3>
		                <p>GENSET Idle</p>
	                </div>
	                <a href="{{ url('/deliveryapproval') }}" class="small-box-footer">More info <i class="fa fa-arrow-circle-right"></i></a>
				</div>
	        </div>
	        <div class="col-lg-3 col-xs-3">
	          	<div class="small-box bg-blue">
	          		<div class="inner">
		                <h3></h3>
		                <p>Open Support Tickets</p>
	                </div>
	                <a href="{{ url('/deliveryapproval') }}" class="small-box-footer">More info <i class="fa fa-arrow-circle-right"></i></a>
				</div>
	        </div>
    	</div>
    	<div class="row">
	        <div class="col-lg-3 col-xs-3">
	          	<div class="small-box bg-yellow">
	          		<div class="inner">
		                <h3></h3>
		                <p>Tank On Site</p>
	                </div>
	                <a href="{{ url('/deliveryapproval') }}" class="small-box-footer">More info <i class="fa fa-arrow-circle-right"></i></a>
				</div>
	        </div>
	        <div class="col-lg-3 col-xs-3">
	          	<div class="small-box bg-green">
	          		<div class="inner">
		                <h3></h3>
		                <p>Tank Idle</p>
	                </div>
	                <a href="{{ url('/deliveryapproval') }}" class="small-box-footer">More info <i class="fa fa-arrow-circle-right"></i></a>
				</div>
	        </div>
	        <div class="col-lg-3 col-xs-3">
	          	<div class="small-box bg-red">
	          		<div class="inner">
		                <h3>{{$invoice->count}}</h3>
		                <p>Pending To Invoice</p>
	                </div>
	                <a href="{{ url('/pendinginvoice') }}" class="small-box-footer">More info <i class="fa fa-arrow-circle-right"></i></a>
				</div>
	        </div>
    	</div>
    	<div class="row">
    		@foreach($branches as $b)
    			@foreach($treshold as $h)
    			@if($b->Option == $h->branch)
	    		<div class="col-lg-3 col-xs-3">
		          	<div class="small-box bg-red">
		          		<div class="inner">
			                <h3>{{$h->count}}</h3>
			                <p>Low Balance (Treshold) [{{$h->branch}}]</p>
		                </div>
		                <a href="{{ url('/lowtresholdlist') }}" class="small-box-footer">More info <i class="fa fa-arrow-circle-right"></i></a>
					</div>
		        </div>
	        	@endif
	        	@endforeach
	        @endforeach
    	</div>
    </section>
</div>
<footer class="main-footer">
	<div class="pull-right hidden-xs">
		<b>Version</b> 2.0.1
	</div>
	<strong>Copyright &copy; 2014-2016 <a href="http://www.softoya.com">TrackerOnTheGo</a>.</strong> All rights reserved.
</footer>
@endsection