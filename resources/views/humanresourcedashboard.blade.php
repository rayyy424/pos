@extends('app')

@section('content')
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
		<h1>Dashboard</h1>
		<ol class="breadcrumb">
	        <li><a href="#"><i class="fa fa-dashboard"></i> Home</a></li>
	        <li>Human Resource</li>
	        <li class="active">Human Resource Dashboard</li>
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
	          	<div class="small-box bg-red">
	          		<div class="inner">
		                <h3 id="finalapprovedleave">{{$finalapprovedleave->count}}</h3>
		                <p>On Leave Today</p>
	                </div>
	                <a href="{{ url('/onleavetodaydashboard') }}/{{$start}}/{{$end}}" class="small-box-footer" target="_blank">More info <i class="fa fa-arrow-circle-right"></i></a>
				</div>
	        </div>
	        <div class="col-lg-3 col-xs-3">
	          	<div class="small-box bg-yellow">
	          		<div class="inner">
		                <h3 id="staffconfirm">{{$staffconfirm->count}}</h3>
		                <p>Staff Confirmed</p>
	                </div>
	                <a href="{{ url('user')}}/{{$start}}/{{$end}}/Confirm" class="small-box-footer" target="_blank">More info <i class="fa fa-arrow-circle-right"></i></a>
				</div>
	        </div>
	        <div class="col-lg-3 col-xs-3">
	          	<div class="small-box bg-green">
	          		<div class="inner">
		                <h3 id="newstaffjoin">{{$newstaffjoin->count}}</h3>
		                <p>New Staff Join</p>
	                </div>
	                <a href="{{ url('/user') }}/{{$start}}/{{$end}}/New" class="small-box-footer" target="_blank">More info <i class="fa fa-arrow-circle-right"></i></a>
				</div>
	        </div>
	        <div class="col-lg-3 col-xs-3">
	          	<div class="small-box bg-blue">
	          		<div class="inner">
		                <h3 id="staffresigned">{{$staffresigned->count}}</h3>
		                <p>Staff Resigned</p>
	                </div>
	                <a href="{{ url('/user/resigned') }}/{{$start}}/{{$end}}/Resigned" class="small-box-footer" target="_blank">More info <i class="fa fa-arrow-circle-right"></i></a>
				</div>
	        </div>
    	</div>
    	<!-- <div class="row">
	        <div class="col-lg-3 col-xs-3">
	          	<div class="small-box bg-yellow">
	          		<div class="inner">
		                <h3 id="staffloanpending">{{$staffloanpending->count}}</h3>
		                <p>Staff Loan Pending Approval</p>
	                </div>
	                <a href="{{ url('/staffloanmanagement') }}" class="small-box-footer" target="_blank">More info <i class="fa fa-arrow-circle-right"></i></a>
				</div>
	        </div>
	        <div class="col-lg-3 col-xs-3">
	          	<div class="small-box bg-green">
	          		<div class="inner">
		                <h3 id="approvedloan">{{$approvedloan->count}}</h3>
		                <p>Approved Loan</p>
	                </div>
	                <a href="{{ url('/staffloanmanagement') }}" class="small-box-footer" target="_blank">More info <i class="fa fa-arrow-circle-right"></i></a>
				</div>
	        </div>
	        <div class="col-lg-3 col-xs-3">
	          	<div class="small-box bg-red">
	          		<div class="inner">
		                <h3 id="totalstaffloan">${{$totalstaffloan->sum}}</h3>
		                <p>Total Staff Loan</p>
	                </div>
	                <a href="#" class="small-box-footer" target="_blank">More info <i class="fa fa-arrow-circle-right"></i></a>
				</div>
	        </div>
	    	<div class="col-lg-3 col-xs-3">
		        <div class="small-box bg-red">
		          	<div class="inner">
			            <h3 id="totalrepayment">${{$totalrepayment->sum}}</h3>
			            <p>Total Repayment</p>
		            </div>
		            <a href="{{ url('/staffdeductions') }}" class="small-box-footer" target="_blank">More info <i class="fa fa-arrow-circle-right"></i></a>
				</div>
		    </div>
    	</div> -->
    </section>
</div>
<footer class="main-footer">
	<div class="pull-right hidden-xs">
		<b>Version</b> 1.0.0
	</div>
	<strong>Copyright &copy; 2014-2016 <a href="http://www.softoya.com">TrackerOnTheGo</a>.</strong> All rights reserved.
</footer>
@endsection

@section('scripts')
    <!-- Include Required Prerequisites -->
    <script type="text/javascript" src="//cdn.jsdelivr.net/momentjs/latest/moment.min.js"></script>

    <!-- Include Date Range Picker -->
    <script type="text/javascript" src="//cdn.jsdelivr.net/bootstrap.daterangepicker/2/daterangepicker.js"></script>
    <link rel="stylesheet" type="text/css" href="//cdn.jsdelivr.net/bootstrap.daterangepicker/2/daterangepicker.css"/>

    <script type="text/javascript">
        $(function () {
            // let dateInterval = getQueryParameter('date_filter');
            // let start = moment().startOf('isoWeek');
            // let end = moment().endOf('isoWeek');
            // if (dateInterval) {
            //     dateInterval = dateInterval.split(' - ');
            //     start = dateInterval[0];
            //     end = dateInterval[1];
            // }
            $('#range').daterangepicker({locale: {
		        format: 'DD-MMM-YYYY'
		        },startDate: '{{$start}}',
		        endDate: '{{$end}}'});

            // $('#date_filter').daterangepicker({
            //     "showDropdowns": true,
            //     "showWeekNumbers": true,
            //     "alwaysShowCalendars": true,
            //     startDate: {{$start}},
            //     endDate: {{$end}},
            //     locale: {
            //         format: 'dd-M-YYYY',
            //         firstDay: 1,
            //     }
            // });
        });
        function getQueryParameter(name) {
            const url = window.location.href;
            name = name.replace(/[\[\]]/g, "\\$&");
            const regex = new RegExp("[?&]" + name + "(=([^&#]*)|&|#|$)"),
                results = regex.exec(url);
            if (!results) return null;
            if (!results[2]) return '';
            return decodeURIComponent(results[2].replace(/\+/g, " "));
        }

        function refresh()
    {
      var d=$('#range').val();
      var arr = d.split(" - ");

      window.location.href ="{{ url("/humanresource/dashboard") }}/"+arr[0]+"/"+arr[1];

    }

    </script>

@endsection