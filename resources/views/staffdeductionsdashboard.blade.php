@extends('app')

@section('content')
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
		<h1>Staff Deductions Dashboard</h1>
		<ol class="breadcrumb">
	        <li><a href="#"><i class="fa fa-dashboard"></i> Home</a></li>
	        <li>Human Resource</li>
	        <li>Staff Dashboard</li>
	        <li class="active">Staff Deductions Dashboard</li>
	    </ol>
    </section>
    <br>
    <!-- Main content -->
    <section class="content">
	    <div class="row">
          	<div class="col-lg-3 col-xs-3">
              <div class="small-box bg-yellow">
                <div class="inner">
                    <h3>@if($summons->sum!=null)
                      ${{$summons->sum}}
                      @else
                      $0
                      @endif</h3>
                    <p>Summon</p>
                </div>
                <a href="{{ url('/staffdeductionsdetail') }}/{{$start}}/{{$end}}/{{$userid}}/SUMMONS" class="small-box-footer" target="_blank">More info <i class="fa fa-arrow-circle-right"></i></a>
        	  </div>
          	</div>
        	<div class="col-lg-3 col-xs-3">
              <div class="small-box bg-green">
              	<div class="inner">
                	<h3>@if($late->sum!=null)
                      ${{$late->sum}}
                      @else
                      $0
                      @endif</h3>
                	<p>Late</p>
              	</div>
                <a href="{{ url('/staffdeductionsdetail') }}/{{$start}}/{{$end}}/{{$userid}}/Late" class="small-box-footer" target="_blank">More info <i class="fa fa-arrow-circle-right"></i></a>
              </div>
        	</div>
        	<div class="col-lg-3 col-xs-3">
              <div class="small-box bg-blue">
              	<div class="inner">
                    <h3>@if($loan->sum!=null)
                      ${{$loan->sum}}
                      @else
                      $0
                      @endif</h3>
                    <p>Loan</p>
              	</div>
                <a href="{{ url('/staffdeductionsdetail') }}/{{$start}}/{{$end}}/{{$userid}}/STAFF LOAN" class="small-box-footer" target="_blank">More info <i class="fa fa-arrow-circle-right"></i></a>
              </div>
        	</div>
        	<div class="col-lg-3 col-xs-3">
              <div class="small-box bg-red">
                <div class="inner">
                  	<h3>@if($accident->sum!=null)
                      ${{$accident->sum}}
                      @else
                      $0
                      @endif</h3>
                  	<p>Accident</p>
                </div>
                <a href="{{ url('/staffdeductionsdetail') }}/{{$start}}/{{$end}}/{{$userid}}/ACCIDENT" class="small-box-footer" target="_blank">More info <i class="fa fa-arrow-circle-right"></i></a>
              </div>
        	</div>
      	</div>
        <div class="row">
          	<div class="col-lg-3 col-xs-3">
              <div class="small-box bg-green">
                <div class="inner">
                    <h3>@if($presaving->sum!=null)
                      ${{$presaving->sum}}
                      @else
                      $0
                      @endif</h3>
                    <p>Presaving</p>
                </div>
                <a href="{{ url('/staffdeductionsdetail') }}/{{$start}}/{{$end}}/{{$userid}}/PRE-SAVING SCHEME" class="small-box-footer" target="_blank">More info <i class="fa fa-arrow-circle-right"></i></a>
        	    </div>
          	</div>
          	<div class="col-lg-3 col-xs-3">
              <div class="small-box bg-blue">
                <div class="inner">
                    <h3>@if($notinradius->sum!=null)
                      ${{$notinradius->sum}}
                      @else
                      $0
                      @endif</h3>
                    <p>Not in Radius</p>
                </div>
                <a href="{{ url('/staffdeductionsdetail') }}/{{$start}}/{{$end}}/{{$userid}}/Not In Radius" class="small-box-footer" target="_blank">More info <i class="fa fa-arrow-circle-right"></i></a>
              </div>
          	</div>
            <div class="col-lg-3 col-xs-3">
              <div class="small-box bg-red">
                <div class="inner">
                    <h3>@if($advancesalary->sum!=null)
                      ${{$advancesalary->sum}}
                      @else
                      $0
                      @endif</h3>
                    <p>Advance Salary Deduction</p>
                </div>
                <a href="{{ url('/staffdeductionsdetail') }}/{{$start}}/{{$end}}/{{$userid}}/ADVANCE SALARY DEDUCTION" class="small-box-footer" target="_blank">More info <i class="fa fa-arrow-circle-right"></i></a>
              </div>
            </div>
            <div class="col-lg-3 col-xs-3">
              <div class="small-box bg-yellow">
                <div class="inner">
                    <h3>@if($drivinglicense->sum!=null)
                      ${{$drivinglicense->sum}}
                      @else
                      $0
                      @endif</h3>
                    <p>Driving License Deduction</p>
                </div>
                <a href="{{ url('/staffdeductionsdetail') }}/{{$start}}/{{$end}}/{{$userid}}/DRIVING LICENSE DEDUCTION" class="small-box-footer" target="_blank">More info <i class="fa fa-arrow-circle-right"></i></a>
              </div>
            </div>
        </div>
        <div class="row">
            <div class="col-lg-3 col-xs-3">
              <div class="small-box bg-blue">
                <div class="inner">
                    <h3>@if($lossofequipment->sum!=null)
                      ${{$lossofequipment->sum}}
                      @else
                      $0
                      @endif</h3>
                    <p>Loss Of Equipment</p>
                </div>
                <a href="{{ url('/staffdeductionsdetail') }}/{{$start}}/{{$end}}/{{$userid}}/LOSS OF EQUIPMENT" class="small-box-footer" target="_blank">More info <i class="fa fa-arrow-circle-right"></i></a>
              </div>
            </div>
            <div class="col-lg-3 col-xs-3">
              <div class="small-box bg-red">
                <div class="inner">
                    <h3>@if($paybacktoeric->sum!=null)
                      ${{$paybacktoeric->sum}}
                      @else
                      $0
                      @endif</h3>
                    <p>Pay Back To Eric</p>
                </div>
                <a href="{{ url('/staffdeductionsdetail') }}/{{$start}}/{{$end}}/{{$userid}}/PAY BACK TO ERIC" class="small-box-footer" target="_blank">More info <i class="fa fa-arrow-circle-right"></i></a>
              </div>
            </div>
            <div class="col-lg-3 col-xs-3">
              <div class="small-box bg-yellow">
                <div class="inner">
                    <h3>@if($pettycashfion->sum!=null)
                      ${{$pettycashfion->sum}}
                      @else
                      $0
                      @endif</h3>
                    <p>Petty Cash Fion</p>
                </div>
                <a href="{{ url('/staffdeductionsdetail') }}/{{$start}}/{{$end}}/{{$userid}}/PETTY CASH FION" class="small-box-footer" target="_blank">More info <i class="fa fa-arrow-circle-right"></i></a>
              </div>
            </div>
            <div class="col-lg-3 col-xs-3">
              <div class="small-box bg-green">
                <div class="inner">
                    <h3>@if($shellcard->sum!=null)
                      ${{$shellcard->sum}}
                      @else
                      $0
                      @endif</h3>
                    <p>Shell Card</p>
                </div>
                <a href="{{ url('/staffdeductionsdetail') }}/{{$start}}/{{$end}}/{{$userid}}/SHELL CARD" class="small-box-footer" target="_blank">More info <i class="fa fa-arrow-circle-right"></i></a>
              </div>
            </div>
        </div>
        <div class="row">
            <div class="col-lg-3 col-xs-3">
              <div class="small-box bg-red">
                <div class="inner">
                    <h3>@if($touchngo->sum!=null)
                      ${{$touchngo->sum}}
                      @else
                      $0
                      @endif</h3>
                    <p>Touch N Go</p>
                </div>
                <a href="{{ url('/staffdeductionsdetail') }}/{{$start}}/{{$end}}/{{$userid}}/TOUCH N GO" class="small-box-footer" target="_blank">More info <i class="fa fa-arrow-circle-right"></i></a>
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