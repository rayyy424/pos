@extends('app')

@section('content')
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
		<h1>Dashboard</h1>
		<ol class="breadcrumb">
	        <li><a href="#"><i class="fa fa-dashboard"></i> Home</a></li>
	        <li>Service Ticket Management</li>
	        <li class="active">Service Ticket Dashboard</li>
	    </ol>
    </section>
    <br>
    <!-- Main content -->
    <section class="content">

    	<div class="box">
    		<div class="box-body">

    			<div class="row">
	    			<div class="col-md-3">
		                <div class="input-group">
		                  <div class="input-group-addon">
		                    <i class="fa fa-clock-o"></i>
		                  </div>
		                  <input type="text" class="form-control" id="range" name="range">

		                </div>
	             	 </div>

	             	 <div class="col-md-3">
	             	 	<select name="status" id="status" class="form-control">
	             	 		<option value="All">All</option>
	             	 		@foreach($allstatus as $s)
	             	 		<option value="{{$s->Status}}" <?php if($status == $s->Status) echo "selected"; ?> >{{$s->Status}}</option>
	             	 		@endforeach
	             	 	</select>
	             	 </div>

	             	 <div class="col-md-2">
			               <div class="input-group">
			                <button type="button" class="btn btn-success btn" data-toggle="modal" onclick="refresh();">Refresh</button>
			                </div>
			          </div>
    			</div>

    			<div style="padding-top: 30px">
	    			<div class="row">
    				@foreach($count as $c)
			    		<div class="col-lg-3 col-xs-3">
				          	<div class="small-box bg-green">
				          		<div class="inner">
					                <h3>{{$c->count}}</h3>
					                <p>{{$c->service_type}}</p>
				                </div>
				                <a href="{{ url('/servicemanagement') }}/{{$start}}/{{$end}}/{{$status}}/{{$c->service_type}}" class="small-box-footer">More info <i class="fa fa-arrow-circle-right"></i></a>
							</div>
				        </div>
				    @endforeach
				    </div>

				</div>

    		</div>
    	</div>
    </section>
</div>
<footer class="main-footer">
	<div class="pull-right hidden-xs">
		<b>Version</b> 1.0.0
	</div>
	<strong>Copyright &copy; 2014-2016 <a href="http://www.softoya.com">TrackerOnTheGo</a>.</strong> All rights reserved.
</footer>

<script type="text/javascript">
	 $(function () {
        $('#range').daterangepicker({locale: {
        format: 'DD-MMM-YYYY'
        },startDate: '{{$start}}',
        endDate: '{{$end}}'});
   	 });

   	function refresh()
    {

      var d=$('#range').val();
      var arr = d.split(" - ");
      var status = $('#status').val();
      if(status == "")
      {
        window.location.href ="{{ url("/svt/dashboard") }}/"+arr[0]+"/"+arr[1];
      }
      else
      {
       window.location.href ="{{ url("/svt/dashboard") }}/"+arr[0]+"/"+arr[1]+ "/" + status;
      }

    }
</script>
@endsection