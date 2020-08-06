@extends('app')

@section('content')
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
		<h1>On Leave Today Dashboard</h1>
		<ol class="breadcrumb">
	        <li><a href="#"><i class="fa fa-dashboard"></i> Home</a></li>
	        <li>Human Resource</li>
	        <li>Human Resource Dashboard</li>
	        <li class="active">On Leave Dashboard</li>
	    </ol>
    </section>
    <br>
    <!-- Main content -->
    <section class="content">
    	<div class="row">
    		<?php $i = 0; ?>
    		@foreach($finalapprovedleave as $k => $v)
		        @if($v)
		        	<?php $i++; ?>
		           	@if($k != "count")
			    		<div class="col-lg-3 col-xs-3">
				          	<div class="small-box bg-red">
				          		<div class="inner">
					                <h3 align="center" id="finalapprovedleave">{{$v}}</h3>
					                <p align="center">{{$k}}</p>
				                </div>
				                <a href="{{ url('/onleavetoday') }}/{{$start}}/{{$end}}/{{$k}}" class="small-box-footer" target="_blank">More info <i class="fa fa-arrow-circle-right"></i></a>
							</div>
				        </div>
	        		@endif
	        	@endif
		    @endforeach

		    @if($i == 0)
		    <div class="col-lg-3 col-xs-3">
				<div class="small-box bg-red">
				    <div class="inner">
					    <h3 align="center">0</h3>
					    <p align="center">Leave Today</p>
				    </div>
                <a href="{{ url('/onleavetoday') }}/{{$start}}/{{$end}}" class="small-box-footer" target="_blank">More info <i class="fa fa-arrow-circle-right"></i></a>
				</div>
	        </div>
		    @endif
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

@section('scripts')
    <!-- Include Required Prerequisites -->
    <script type="text/javascript" src="//cdn.jsdelivr.net/momentjs/latest/moment.min.js"></script>

    <script type="text/javascript">
        function getQueryParameter(name) {
            const url = window.location.href;
            name = name.replace(/[\[\]]/g, "\\$&");
            const regex = new RegExp("[?&]" + name + "(=([^&#]*)|&|#|$)"),
                results = regex.exec(url);
            if (!results) return null;
            if (!results[2]) return '';
            return decodeURIComponent(results[2].replace(/\+/g, " "));
        }

    </script>

@endsection