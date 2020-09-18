@extends('app')

@section('content')
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
		<h1>Dashboard</h1>
		<ol class="breadcrumb">
	        <li><a href="#"><i class="fa fa-dashboard"></i> Home</a></li>
	        <li>SpeedFreak Management</li>
	        <li class="active">SpeedFreak Dashboard</li>
	    </ol>
    </section>
    <br>
    <!-- Main content -->
    <section class="content">
    	<div class="col-md-6">
          <div class="input-group">
            <div class="input-group-addon">
              <i class="fa fa-clock-o"></i>
            </div>
            <input type="text" class="form-control" id="range" name="range">
          </div>
        </div>
        <div class="col-md-6">
            <div class="input-group">
              <button type="button" class="btn btn-success btn" data-toggle="modal" onclick="refresh();">Refresh</button>
            </div>
        </div>
    	<div class="row">
    		<div class="col-lg-3 col-xs-3">
	          	<div class="small-box bg-blue">
	          		<div class="inner">
		                <h3>@if($inventorycost->Total!=null) 
		                	RM {{$inventorycost->Total}}
		                	@else
		                	RM 0.00
		                	@endif
		                </h3>
		                <p>Inventory Cost</p>
	                </div>
	                <a href="{{ url('/speedfreak/inventory')}}" class="small-box-footer">More info <i class="fa fa-arrow-circle-right"></i></a>
				</div>
	        </div>
	        <div class="col-lg-3 col-xs-3">
	          	<div class="small-box bg-red">
	          		<div class="inner">
		                <h3>@if($expenses!=null)
		                	RM {{$expenses}}
		                	@else
		                	RM 0.00
		                	@endif
		                </h3>
		                <p>Expenses</p>
	                </div>
	                <a href="{{ url('/utilitytracker/Water') }}" class="small-box-footer">More info <i class="fa fa-arrow-circle-right"></i></a>
				</div>
	        </div>
	        <div class="col-lg-3 col-xs-3">
	          	<div class="small-box bg-red">
	          		<div class="inner">
		                <h3>@if($utilitybill->Total!=null)
		                	RM {{$utilitybill->Total}}
		                	@else
		                	RM 0.00
		                	@endif
		                </h3>
		                <p>Utility Bills</p>
	                </div>
	                <a href="{{ url('/utilitytracker/Water') }}" class="small-box-footer">More info <i class="fa fa-arrow-circle-right"></i></a>
				</div>
	        </div>
	        <div class="col-lg-3 col-xs-3">
	          	<div class="small-box bg-red">
	          		<div class="inner">
		                <h3>@if($loanpaid->loanpaid!=null)
		                	RM {{$loanpaid->loanpaid}}
		                	@else
		                	RM 0.00
		                	@endif</h3>
		                <p>Loan</p>
	                </div>
	                <a href="{{ url('/companyloan') }}" class="small-box-footer">More info <i class="fa fa-arrow-circle-right"></i></a>
				</div>
	        </div>
    	</div>
    	<div class="row">
	        <div class="col-lg-3 col-xs-3">
	          	<div class="small-box bg-red">
	          		<div class="inner">
		                <h3>@if($salary->Total!=null)
		                	RM {{$salary->Total}}
		                	@else
		                	RM 0.00
		                	@endif</h3>
		                <p>Salary</p>
	                </div>
	                <a href="{{ url('/salary') }}" class="small-box-footer">More info <i class="fa fa-arrow-circle-right"></i></a>
				</div>
	        </div>
	        <div class="col-lg-3 col-xs-3">
	          	<div class="small-box bg-green">
	          		<div class="inner">
		                <h3>@if($sales->Total!=null)
		                	RM {{$sales->Total}}
		                	@else
		                	RM 0.00
		                	@endif</h3>
		                <p>Sales</p>
	                </div>
	                <a href="{{ url('/speedfreak/sales')}}" class="small-box-footer">More info <i class="fa fa-arrow-circle-right"></i></a>
				</div>
	        </div>
	        <div class="col-lg-3 col-xs-3">
	          	<div class="small-box bg-green">
	          		<div class="inner">
		                <h3>@if($profitfinal!=null)
		                	RM {{$profitfinal}}
		                	@else
		                	RM 0.00
		                	@endif</h3>
		                <p>Profit</p>
	                </div>
	                <a href="{{ url('/speedfreak/sales')}}" class="small-box-footer">More info <i class="fa fa-arrow-circle-right"></i></a>
				</div>
	        </div>
	        <div class="col-lg-3 col-xs-3">
	          	<div class="small-box bg-green">
	          		<div class="inner">
		                <h3>@if($averagesalesfinal!=null)
		                	RM {{$averagesalesfinal}}
		                	@else
		                	RM 0.00
		                	@endif</h3>
		                <p>Average Sale Per Month</p>
	                </div>
	                <a href="{{ url('/averagesale') }}/{{$year}}" class="small-box-footer">More info <i class="fa fa-arrow-circle-right"></i></a>
				</div>
	        </div>
	        <div class="col-lg-3 col-xs-3">
	          	<div class="small-box bg-yellow">
	          		<div class="inner">
		                <h3>Wage Contribution</h3>
		                <p>Visit EPF</p>
	                </div>
	                <a href="https://secure.kwsp.gov.my/employer/employer/login"  target="_blank" class="small-box-footer">Visit Website <i class="fa fa-arrow-circle-right"></i></a>
				</div>
	        </div>
	        <div class="col-lg-3 col-xs-3">
	          	<div class="small-box bg-blue">
	          		<div class="inner">
		                <h3>Generate Invoice</h3>
		                <p>Visit Generator</p>
	                </div>
	                <a href="https://invoice-generator.com/#/1"  target="_blank" class="small-box-footer">Visit Website <i class="fa fa-arrow-circle-right"></i></a>
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

	      window.location.href ="{{ url("/speedfreak/dashboard") }}/"+arr[0]+"/"+arr[1];

	    }

    </script>
@endsection