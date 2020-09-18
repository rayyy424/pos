<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="utf-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<meta name="_token" content="{!! csrf_token() !!}"/>
	<title>{{ env('APP_NAME') }}</title>


	<link href="{{ asset('/css/app.css') }}" rel="stylesheet">

	<!-- Fonts -->
	<link href='https://fonts.googleapis.com/css?family=Poppins' rel='stylesheet'>
	<link href='https://fonts.googleapis.com/css?family=Poppins:300,400,500,600,700' rel='stylesheet' type='text/css'>

	<link rel="stylesheet" href="//code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">

	<!-- jQuery 2.2.3 -->
	<script src="{{ asset('/plugin//jQuery/jquery-2.2.3.min.js') }}"></script>
	<!-- jQuery UI 1.11.4 -->
	<script src="https://code.jquery.com/ui/1.11.4/jquery-ui.js"></script>


        <link href="{{ asset('/plugin/bootstrap/css/bootstrap.min.css') }}" rel="stylesheet">
        <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.5.0/css/font-awesome.min.css" rel="stylesheet">
        <link href="https://cdnjs.cloudflare.com/ajax/libs/ionicons/2.0.1/css/ionicons.min.css" rel="stylesheet">
		<link href="{{ asset('/plugin/daterangepicker/daterangepicker.css') }}" rel="stylesheet">
		<link href="{{ asset('/plugin/datepicker/datepicker3.css') }}" rel="stylesheet">
				{{-- <link href="{{ asset('/plugin/iCheck/flat/blue.css') }}" rel="stylesheet"> --}}
				<!-- Bootstrap time Picker -->
  			<link href="{{ asset('/plugin/timepicker/bootstrap-timepicker.min.css') }}" rel="stylesheet">
				<link href="{{ asset('/plugin/select2/select2.min.css') }}" rel="stylesheet">
        <link href="{{ asset('/plugin/dist/css/AdminLTE.min.css') }}" rel="stylesheet">
        <link href="{{ asset('/plugin/dist/css/skins/_all-skins.min.css') }}" rel="stylesheet">
        <link href="{{ asset('/plugin/iCheck/flat/green.css') }}" rel="stylesheet">
        <link href="{{ asset('/plugin/morris/morris.css') }}" rel="stylesheet">
        <link href="{{ asset('/plugin/jvectormap/jquery-jvectormap-1.2.2.css') }}" rel="stylesheet">

        <link href="{{ asset('/plugin/bootstrap-wysihtml5/bootstrap3-wysihtml5.min.css') }}" rel="stylesheet">

				<!-- fullCalendar 2.2.5-->
				<link rel="stylesheet" href="{{ asset('/plugin/fullcalendar/fullcalendar.min.css') }}">
				<link rel="stylesheet" href="{{ asset('/plugin/fullcalendar/fullcalendar.print.css') }}" media="print">

				<!-- Firdaus 20180622 - dm-uploader/drag and drop -->
				<link href="{{ asset('/plugin/dmuploader/css/jquery.dm-uploader.css') }}" rel="stylesheet">
				<!-- Firdaus 20180622 - dm-uploader/drag and drop -->

	@yield('scripts')

	@yield('datatable-css')

	@yield('datatable-js')

	@yield('orgchart-css')

	@yield('orgchart-js')

	<!-- HTML5 shim and Respond.js for IE8 support of HTML5 elements and media queries -->
	<!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
	<!--[if lt IE 9]>
		<script src="https://oss.maxcdn.com/html5shiv/3.7.2/html5shiv.min.js"></script>
		<script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
	<![endif]-->
	<style>
			body {
		    font-family: 'Poppins';
		}

    .select2-results__group {

        border-top:1px solid #d2d6de;
    }
    div.DTED_Lightbox_Background {
        z-index: 1030;
    }

    div.DTED_Lightbox_Wrapper {
        z-index: 1031;
    }

	.skin-blue .main-header .navbar {
			/*min-height: 70px;*/
		font-size:13px;
	    background-color: #000000;
	}
	.navbar-nav>li>a {
	    padding-left: 10px;
	    padding-right: 10px;
	}
	.logo{
		    background-color: #a1a6a9;
	}

	.skin-blue .sidebar-menu>li.active>a {
    color: #fff;
    border-left-color: #000e27;
	}

	.skin-blue .main-header li.user-header {
    background-color: #000000;
	}

	.skin-blue .sidebar-menu>li:hover>a, .skin-blue .sidebar-menu>li.active>a{
		background: #011b4a;
	}

	.skin-blue .sidebar-menu>li>.treeview-menu {

		background: #012975;
	}

	.btn-danger, .btn-danger:focus {
	    background-color: #011844;
	    border-color: #011844;
	}
	.btn-success, .btn-success:focus {
	    background-color: #044acc;
	    border-color: #044acc;
	}

	.btn-success:hover,.btn-danger:hover {
		background-color: #011844;
		border-color: #011844;
	}

	.box{
	  border-top: 3px solid #011844;
	}
	.box.box-primary {
	    border-top-color: #011844;
	}

	a {
	  color: #011844;
	  text-decoration: none;
	}


	@media (max-width: 767px)
	{

		/* comment out to enable handsontable to display
.mobile {
		    display: none;
			}
 */
	}

	@media (max-width: 991px)
	{
		.navbar-nav>.user-menu>.dropdown-menu>.user-footer .btn-default {

		    background: white;
		}
	}

	.nav-tabs-custom>.nav-tabs>li:hover
		 {

			color:black;

			border-top: 3px solid red;
		}


		table.dataTable tbody td {
			white-space: nowrap;
			text-align: left;
				padding : 5px;

				border:1px solid #DDDDE2;
	}


	/*table.dataTable thead td {
    padding : 5px;
    border-bottom: 1px solid #111;
}*/

	table.dataTable thead th, table.dataTable thead td {
	    padding: 3px;
	    border-bottom: 1px solid #111;
	}

	table.dataTable thead td {
	    padding-right: 15px;
	}


	table.dataTable thead {
		background-color: #0066ff;
		color: white;
	}

	.search{
		color: black;
    background-color: #f9f9f9;
	}

	table.dataTable thead td{

		border:1px solid #DDDDE2;

	}

	table.dataTable th{
		text-align: left;
			padding-left : 3px;
			padding-top : 2px;
			padding-bottom : 2px;

	}

	.handsontable th {
	background-color: #0066FF;
	color: white;
	font-weight: bold;
	font-family: Arial;
	font-size: 9px;
	height: 12px;
	text-align: center;
	vertical-align: middle;

}

.content {
    padding-top: 0px;
		padding-left:35px;
		padding-right:35px;
		padding-bottom: 0px;
}

.main-footer {
     padding: 1.5px;
		 font-size: 10px;
		 font-weight: bold;
}

/*.handsontable td {
	font-family: Arial;
  font-size: 9px;
	vertical-align: middle;
	/*max-width: 150px;*/
	min-height:12px;
	height: 12px;

}*/

.nav-tabs-custom {
    margin-bottom: 0px;
}

.ht_clone_left th {
	background-color: #BBBFC2;
}

	.ht_master tr:nth-of-type(even) > td {
	background-color: #D6EAF8;
	}

	.htCore td.customClass {
		color: #F45E1D;
		font-weight: bold;
	}

	.search_init{
		width:100%;
	}

	.container{
		width:100%;
	}
	li.sub-dropdown a{
		font-family: verdana;
		font-weight: bold;
	}
	li a{
		font-family: 'Poppins';

	}

	.dropdown-menu>.active>a, .dropdown-menu>.active>a:focus, .dropdown-menu>.active>a:hover{
		background-color:#000000;
	}



	.dropdown-menu>.divider {
    background-color: #000000;
}
/*	.dropdown{
		font-size:15px;
		text-align: center;
		padding-top: 25px;
		padding-left:38px;"
	}*/

	</style>

</head>
<body class="hold-transition skin-blue layout-top-nav">
	<header class="main-header">
    <nav class="navbar navbar-static-top">
      <div class="container">
        <div class="navbar-header">
          <a href='{{url("/")}}'><img src="{{ URL::to('/') ."/img/speedfreak.png"  }}" width="100" height="50" alt="Speed Freak Sdn Bhd"></a>
          <!-- <a href='{{ url("/") }}' class="navbar-brand" style="font-size:27px; /*padding-top: 25px; padding-left:38px;*/"><b>TOTG</b></a> -->
          <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#navbar-collapse">
            <i class="fa fa-bars"></i>
          </button>
        </div>

        <!-- Collect the nav links, forms, and other content for toggling -->
        <div class="collapse navbar-collapse pull-left" id="navbar-collapse">
          <ul class="nav navbar-nav">

						@if (Auth::guest())

            @else

									@if($me->User_Type!="External")
									<li class="dropdown">
			              <a href="#" class="dropdown-toggle" data-toggle="dropdown">My Workplace<span class="caret"></span></a>
			              <ul class="dropdown-menu" role="menu">
			                <li><a href="{{ url('/myprofile') }}" >My Profile</a></li>
											<li><a href="{{ url('/myleave') }}">My Leave</a></li>
											<!-- <li><a href="https://payroll.autocountsoft.com/" target="_blank">My Payslip</a></li> -->
											<li><a href="{{ url('/mytimesheet') }}">My Timesheet</a></li>
											<li><a href="{{ url('/myloan') }}">My Loan</a></li>
											<li><a href="{{ url('/myrequest') }}">My Request</a></li>
											<li><a href="{{ url('/myadvancerequest') }}">My Advance Request</a></li>
											<!-- <li><a href="{{ url('/mydeliveryrequest') }}">My Delivery Request</a></li> -->
											@if($me->Todolist_Dashboard || $me->View_Todolist)
											<li class="divider"></li>
											<li class="sub-dropdown"><a href="#">To-Do List</a></li>
											<li class="divider"></li>
											@if($me->Todolist_Dashboard)
											<li><a href="{{ url('/tododashboard') }}">To-Do Dashboard</a></li>
											@endif
											<li><a href="{{ url('/todolist') }}">To-Do List</a></li>
											@endif
			              </ul>
			            </li>
								 @endif

								 @if($me->View_User_Profile || $me->View_All_Leave ||$me->Approve_Leave ||$me->Staff_Monitoring || $me->View_Incentive_Summary || $me->View_Medical_Claim_Summary || $me->Leave_Entitlement || $me->Payslip_Management || $me->OT_Management_HR || $me->OT_Management_HOD || $me->Holiday_Management || $me->Driver_Incentive_Summary || $me->Deduction)

									<li class="dropdown">
						              <a href="#" class="dropdown-toggle" data-toggle="dropdown">Human Resource<span class="caret"></span></a>
						              <ul class="dropdown-menu" role="menu">
						              		@if($me->Staff_Monitoring)
						              		<li><a href="{{ url('humanresource/dashboard') }}">Human Resource Dashboard</a></li>
											<li><a href="{{ url('/engineerlocationtracking') }}">Attendance Tracker</a></li>
											@endif
											<!-- @if($me->View_Incentive_Summary)
											<li><a href="{{ url('/incentivesummary') }}">Incentive Summary</a></li>
											@endif
											@if($me->Driver_Incentive_Summary)
											<li><a href="{{ url('/driverincentivesummary') }}">Driver Incentive Summary</a></li>
											@endif -->
											@if($me->View_Medical_Claim_Summary)
											<li><a href="{{ url('/medicalclaimsummary') }}">Medical Claim Summary</a></li>
											<!-- @if($me->View_MIA)
											<li><a href="{{ url('/mialist') }}" >MIA List</a></li>
											@endif -->
											@endif
											<li class="divider"></li>
											@if($me->View_All_Leave || $me->Approve_Leave || $me->Update_Medical_Claim || $me->Leave_Entitlement || $me->Holiday_Management)
											<li class="sub-dropdown"><a href="#">Leave</a></li>
											<li class="divider"></li>
												@if($me->View_All_Leave || $me->Approve_Leave || $me->Update_Medical_Claim)
												<li><a href="{{ url('/leavemanagement2') }}">Leave Management</a></li>
												@endif
												@if($me->Leave_Adjustment)
												<li><a href="{{ url('/leavebatch') }}">Leave Batch Adjustment</a></li>
												@endif
												@if($me->View_Leave_Summary)
												<li><a href="{{ url('/leavesummary') }}">Leave Summary</a></li>
												<!-- <li><a href="{{ url('/timeoffreport') }}">Time-off Report</a></li> -->
												@endif
												@if($me->Leave_Entitlement)
												<li><a href="{{ url('/leaveentitlement') }}">Leave Entitlement</a></li>
												@endif
												@if($me->Holiday_Management)
												<!-- <li><a href="{{ url('/leavecarryforward') }}">Leave Carry Forward</a></li> -->
												<!-- <li><a href="{{ url('/holidaymanagement') }}/{{date("Y")}}">Holiday Management</a></li> -->
												<li><a href="{{ url('/holidaymanagement') }}/territories">Holiday Territory Management</a></li>
												@endif
											<li class="divider"></li>
											@endif
											<!-- @if($me->Payroll || $me->Payslip_Management)
											<li class="sub-dropdown"><a href="#">Payroll</a></li>
											<li class="divider"></li>
												@if($me->Payroll)
													<li><a href="{{ url('/sgsimport') }}">SGS Import</a></li>
												@endif
												@if($me->Payslip_Management)
													<li><a href="{{ url('/payslipmanagement') }}">Payslip Management</a></li>
												@endif
											<li class="divider"></li>
											@endif -->

											@if($me->View_All_Timesheet ||$me->Approve_Timesheet)
											<li><a href="{{ url('/timesheetmanagement') }}">Timesheet Management</a></li>
											@endif

											@if($me->Deduction)
											<li><a href="{{ url('/staff/dashboard') }}">Staff Dashboard</a></li>
											<li><a href="{{ url('/staffdeductions') }}">Staff Deductions</a></li>
											@endif

											@if($me->Staff_Loan || $me->View_All_Staff_Loan || $me->Approve_Staff_Loan)
											<li><a href="{{ url('/staffloanmanagement') }}">Staff Loan Management</a></li>
											<li><a href="{{ url('/staffloan') }}">Staff Loan</a></li>
											<!-- <li><a href="{{ url('/staffloan-old') }}">Staff Loan(Old)</a></li> -->
											@endif

											@if($me->Request_Management)
											<li><a href="{{ url('/requestmanagement') }}">Request Management</a></li>
											@endif

											@if($me->View_User_Profile)
											<li><a href="{{ url('/user') }}" >User Profile</a></li>
											@endif
											<li><a href="{{ url('/contractor') }}" >Contractor Profile</a></li>
						              </ul>
						            </li>
									@endif

										@if($me->Agreement || $me->Credit_Card || $me->Filing_System || $me->Asset_Tracking || $me->Insurance ||$me->License || $me->Notice_Board_Management || $me->Phone_Bills || $me->Property || $me->Utility_Bills)

											<li class="dropdown">
												<a href="#" class="dropdown-toggle" data-toggle="dropdown">Administration<span class="caret"></span></a>
												<ul class="dropdown-menu" role="menu">
													@if($me->UserId == 562)
													<li><a href="{{ url('/property/RENTAL') }}">Asset Management</a></li>
													@endif
													@if($me->Agreement)
													<li><a href="{{ url('/agreement') }}/IT">Agreement</a></li>
													@endif
													<!-- @if($me->Credit_Card)
													<li class="divider"></li>
													<li class="sub-dropdown"><a href="#">Credit Card</a></li>
													<li class="divider"></li>
													<li><a href="{{ url('/creditcardtracker/ALVIN') }}">Credit Card</a></li>
													<li><a href="{{ url('/creditcardsummary') }}">Credit Card Summary</a></li>
													<li class="divider"></li>
													@endif -->
													@if($me->Filing_System)
													<li><a href="{{ url('/filingsystem') }}/Contract">Filing System</a></li>
													@endif
													@if($me->Asset_Tracking)
														<li><a href="{{ url('/assettracking') }}/IT APPLIANCES">Fixed Asset</a></li>
													@endif
													@if($me->Insurance)
														<li><a href="{{ url('/insurancetracker/PROPERTY') }}">Insurance</a></li>
													@endif
													@if($me->License)
														<li><a href="{{ url('/licensetracker') }}/Company">License and Card</a></li>
													@endif
													@if($me->Notice_Board_Management)
														<li><a href="{{ url('/noticemanagement') }}">Notice Board Management</a></li>
													@endif
													<li class="divider"></li>
													@if($me->Phone_Bills)
													<li class="sub-dropdown"><a href="#">Phone Bills</a></li>
													<li class="divider"></li>
														<li><a href="{{ url('/phonebilltracker/Celcom') }}">Phone Bills</a></li>
														<!-- <li><a href="{{ url('/phonebillsummary') }}">Phone Bills Summary</a></li> -->
													<li class="divider"></li>
													@endif
													<!-- @if($me->Property)
													<li><a href="{{ url('/property') }}/RENTAL">Property</a></li>

													<li class="divider"></li>
													@endif -->
													@if($me->Utility_Bills)
														<li class="sub-dropdown"><a href="#">Utility Bills</a></li>
														<li class="divider"></li>
														<li><a href="{{ url('/utilitytracker/Water') }}">Utility Bills</a></li>
														<!-- <li><a href="{{ url('/utilitysummary') }}">Utility Bills Summary</a></li> -->
													@endif

												</ul>
					            			</li>

										@endif

										<!-- @if($me->Motor_Vehicle || $me->Shell_Cards || $me->Summon || $me->TouchNGo || $me->Transport_Charges)
											<li class="dropdown">
												<a href="#" class="dropdown-toggle" data-toggle="dropdown">Logistic<span class="caret"></span></a>
												<ul class="dropdown-menu" role="menu">

													@if($me->Motor_Vehicle)
													<li><a href="{{ url('/roadtaxmanagement') }}/VEHICLE%20LIST">Motor Vehicle</a></li>
													@endif
													@if($me->Shell_Cards)
														<li><a href="{{ url('/shellcardtracker') }}">Shell Cards</a></li>
													@endif
													@if($me->Summon)
														<li><a href="{{ url('/summontracker') }}">Summon</a></li>
													@endif
													@if($me->TouchNGo)
														<li><a href="{{ url('/touchngo') }}">Touch N Go</a></li>
													@endif
													@if($me->TouchNGo && $me->Shell_Cards && $me->Motor_Vehicle)
														<li><a href="{{ url('/vehicleexpensessummary') }}">Vehicle Expenses Summary</a></li>
													@endif
														<li><a href="{{ url('/inventorymanagement') }}">Inventory Management</a></li>
														<li><a href="{{ url('/customers/All') }}">Company Management</a></li>

													@if($me->Transport_Charges)
													<li><a href="{{url('logisticschargesincentive')}}">Logistics Charges & Incentive</a></li>
													<li><a href="{{url('transportcharges')}}">Transport Charges</a></li>
													<li><a href="{{url('logisticsrate')}}">Logistic Rate</a></li>
													@endif

												@if($me->Delivery_Approval || $me->Delivery_Tracking || $me->Site_Delivery_Summary || $me->Warehouse_Checklist
													|| $me->Requestor_KPI ||  $me->Driver_KPI || $me->Warehouse_KPI || $me->Delivery_Dashboard)
													<li class="divider"></li>
													<li class="sub-dropdown"><a href="#">Delivery Management</a></li>
													<li class="divider"></li>
													@if($me->Delivery_Dashboard)
												<li><a href="{{url('/deliverydashboard')}}">Delivery Dashboard</a></li>
												@endif
												@if( $me->Delivery_Approval)
												<li><a href="{{ url('/deliveryapproval') }}">Delivery Approval</a></li>
												@endif
												@if($me->Delivery_Tracking)
												<li><a href="{{ url('/deliverytracking') }}">Delivery Tracking</a></li>
												@endif
												@if($me->Site_Delivery_Summary)
												<li><a href="{{ url('/sitedeliverysummary') }}">Site Delivery Summary</a></li>
												@endif
												@if($me->Warehouse_Checklist)
												<li><a href="{{ url('/warehousechecklist') }}">Warehouse Checklist</a></li>
												@endif

												@if($me->Requestor_KPI)
												<li class="divider"></li>
												<li class="sub-dropdown"><a href="#">KPI</a></li>
												<li class="divider"></li>
												@endif
												@if($me->Requestor_KPI)
												<li><a href="{{ url('/requestorkpi') }}">Requestor KPI</a></li>
												@endif
												@if($me->Driver_KPI)
												<li><a href="{{ url('/driverkpi') }}">Driver KPI</a></li>
												@endif
												@if($me->Warehouse_KPI)
												<li><a href="{{ url('/warehousekpi') }}">Warehouse KPI</a></li>
												@endif
												</ul>
												@endif
											</li>
										@endif -->

										@if($me->Speedfreak || $me->Speedfreak_Summary)
										<li class="dropdown">
											<a href="#" class="dropdown-toggle" data-toggle="dropdown">SpeedFreak Management<span class="caret"></span></a>
											<ul class="dropdown-menu" role="menu">
												{{-- <li class="divider"></li>
												<li class="sub-dropdown"><a href="#">SpeedFreak Management</a></li>
												<li class="divider"></li> --}}
												<li class="sub-dropdown"><a href="#">Company Report</a></>
												<li class="divider"></li>
												<li><a href="{{url('/speedfreak/dashboard')}}">SpeedFreak Dashboard</a></li>
												<!-- @if($me->Speedfreak_Summary)
												<li><a href="{{ url('speedfreaksummarydashboard')}}">SpeedFreak Summary Dashboard</a></li>
												@endif -->
												<li class="divider"></li>
												<li class="sub-dropdown"><a href="#">Inventory Management</a></li>
												<li class="divider"></li>
												<!-- <li><a href="{{ url('svt/dashboard')}}">Service Ticket Dashboard</a></li>
												<li><a href="{{ url('/servicemanagement')}}">Service Management</a></li>
												<li><a href="{{ url('/ticketflow')}}">Ticket Flow</a></li> -->
												<li><a href="{{ url('/speedfreak/inventory')}}">SpeedFreak Inventory Management</a></li>
												<!-- <li><a href="{{ url('/asset/inventory')}}">Asset Management</a></li>
												<li><a href="{{ url('technicianbag')}}">Technician Inventory Bag</a></li>
												<li><a href="{{ url('/requisitionform')}}">Requisition Form</a></li>
												<li><a href="{{ url('/requisitionmanagement')}}">Requisition Management</a></li>
												<li><a href="{{url('/svtreport')}}">SVT Replacement Report</a></li>
												<li><a href="{{ url('inventorypricehistory')}}">Price History</a></li>
												@if($me->Storekeeper)
												<li><a href="{{ url('/storekeeper')}}">Storekeeper Management</a></li>
												@endif
												@if($me->Sales_Order)
												<li class="divider"></li>
												<li class="sub-dropdown"><a href="#">Sales Order Management</a></li>
												<li class="divider"></li>
												<li><a href="{{ url('/salesorder') }}">Sales Order</a></li>
												@if($me->Sales_Summary)
												<li><a href="{{ url('/salessummary') }}">Sales Summary</a></li>
												<li><a href="{{ url('/jdnisummary') }}">Stock Cost Summary</a></li>
												@endif
												@endif -->
												
												<!-- <li><a href="{{url('/invoice')}}">Invoice Management</a></li> -->
												
												<li class="divider"></li>
												<li class="sub-dropdown"><a href="#">Company Loan</a></li>
												<li class="divider"></li>
												<li><a href="{{ url('/companyloanmanagement') }}">SpeedFreak Loan Management</a></li>
												<li><a href="{{ url('/companyloan') }}">SpeedFreak Loan</a></li>
												<li class="divider"></li>
												<li class="sub-dropdown"><a href="#">Salary</a></li>
												<li class="divider"></li>
												<li><a href="{{ url('/salary') }}">SpeedFreak Staff Salary</a></li>
												<li class="divider"></li>
												<li class="sub-dropdown"><a href="#">Company Loan</a></li>
												<li class="divider"></li>
												<li><a href="{{ url('/speedfreak/sales')}}">SpeedFreak Sales</a></li>
											</ul>
										</li>
										@endif

										@if($me->Access_Control || $me->Approval_Control || $me->View_Login_Tracking ||$me->IT_Services||$me->Notification_Maintenance||$me->Option_Control||$me->Printer||$me->Service_Contact)

										<li class="dropdown">
											<a href="#" class="dropdown-toggle" data-toggle="dropdown">IT Support<span class="caret"></span></a>
											<ul class="dropdown-menu" role="menu">
												@if($me->Access_Control)
												<li><a href="{{ url('/accesscontrol') }}">Access Control</a></li>
												@endif
												@if($me->Approval_Control)
												<li><a href="{{ url('/approvalcontrol') }}/Claim">Approval Control</a></li>
												@endif
												@if($me->View_Login_Tracking)
												<li><a href="{{ url('/logintracker') }}">Login Tracker</a></li>
												@endif
												<!-- @if($me->IT_Services)
												<li><a href="{{ url('/ITservice') }}">IT Services</a></li>
												@endif -->
												@if($me->Notification_Maintenance)
												<li><a href="{{ url('/notificationmaintenance') }}">Notification Maintenance</a></li>
												@endif
												@if($me->Option_Control)
												<li><a href="{{ url('/optioncontrol') }}/users">Option Control</a></li>
												@endif
												 @if($me->Printer)
												<li><a href="{{ url('/printer') }}">Printer</a></li>
												@endif
												@if($me->Service_Contact)
												<li><a href="{{ url('/servicecontact') }}">Service Contact</a></li>
												@endif
												<!-- @if($me->radius)
												<li><a href="{{ url('/radiusmanagement') }}">Radius Management</a></li>
												@endif
												<li><a href="{{ url('/deliverylocation') }}">Delivery Location Management</a></li>
												<li><a href="{{ url('/scopeofwork') }}">Scope of Work Management</a></li> -->
											</ul>
										</li>

										@endif

<!-- 										@if($me->UserId == 562 || $me->UserId == 659)
										<li class="dropdown">
											<a href="#" class="dropdown-toggle" data-toggle="dropdown">Testing<span class="caret"></span></a>
											<ul class="dropdown-menu" role="menu">
												<li class="sub-dropdown"><a href="#">Testing</a></>
												<li class="divider"></li>
												<li><a href="{{ url('replacementhistory')}}">Replacement History</a></li>
												<li class="divider"></li>
												<li class="sub-dropdown"><a href="#">Developing</a></>
												<li class="divider"></li>
												<li><a href="{{ url('/testpdf') }}">DO PDF</a></li>
												<li><a href="{{ url('deliveryorderpdf')}}/599">Auto Create DO pdf</a></li>
											</ul>
										</li>
										@endif -->


						@endif

			          </ul>

        </div>
        <!-- /.navbar-collapse -->
        <!-- Navbar Right Menu -->
        <div class="navbar-custom-menu">
          <ul class="nav navbar-nav">

						@if (Auth::guest())

            @else

							@if(strlen($me->NoticeId)>0)

								<?php
									$noticeid=explode("|",$me->NoticeId);
									$noticetitle=explode("|",$me->NoticeTitle);
								?>

								<li class="dropdown notifications-menu">
		            <a href="#" class="dropdown-toggle" data-toggle="dropdown">
		              <i class="fa fa-bell-o" style="font-size:18px;"></i>
		              <span class="label label-warning">{{sizeof($noticeid)}}</span>
		            </a>
		            <ul class="dropdown-menu">
		              <!-- <li class="header">You have 10 notifications</li> -->
		              <li>
		                <ul class="menu">

												<?php $i=0; ?>
												@foreach ($noticeid as $item)

													<li>
														<a href="{{url('notice')}}/{{$noticeid[$i]}}">
				                      <i class="fa fa-newspaper-o text-green"></i> {{$noticetitle[$i]}}
				                    </a>
				                  </li>
													<?php $i++; ?>
												@endforeach

		                </ul>
		              </li>
		              <!-- <li class="footer"><a href="#">View all</a></li> -->
		            </ul>
		          </li>

							@endif



								<li class="dropdown user user-menu" style="font-size:15px; text-align: center; /*padding-top: 15px;*/">
			            <a href="#" class="dropdown-toggle" data-toggle="dropdown">
			              <img src="{{ url($me->Web_Path) }}" class="user-image" alt="User Image">
			              <span class="hidden-xs" style="font-size: 13px;">{{ $me -> Name }}</span>
			            </a>
			            <ul class="dropdown-menu">
			              <!-- User image -->
			              <li class="user-header">
			                <img src="{{ url($me->Web_Path) }}" class="img-circle" alt="User Image">

			                <p>
			                  {{ $me -> Name }} - {{ $me -> Position }}
			                </p>
			              </li>
			              <!-- Menu Body -->
			<!--              <li class="user-body">
			                <div class="row">
			                  <div class="col-xs-4 text-center">
			                    <a href="#">Followers</a>
			                  </div>
			                  <div class="col-xs-4 text-center">
			                    <a href="#">Sales</a>
			                  </div>
			                  <div class="col-xs-4 text-center">
			                    <a href="#">Friends</a>
			                  </div>
			                </div>
			                 /.row
			              </li>-->
			              <!-- Menu Footer-->
			              <li class="user-footer">
							@if($me->User_Type!="External")
				                <div class="pull-left" >
				                  <a href="{{ url('/myprofile') }}" class="btn btn-default btn-flat" style=" font-family: 'Poppins'; font-family: 'Poppins';width: auto">Profile</a>
				                </div>
							@endif
			                <div class="pull-right">
								<!-- <a href="{{ url('/auth/logout') }}" class="btn btn-default btn-flat" onclick="updatelog('{{$me->UserId}}','Logout')">Logout</a> -->
								<a href="{{ url('/auth/logout') }}" class="btn btn-default btn-flat" style=" font-family: 'Poppins'; font-family: 'Poppins';width: auto">Logout</a>
			                </div>
			              </li>
			            </ul>
			          </li>

								@endif

		          </ul>
        </div>
        <!-- /.navbar-custom-menu -->
      </div>
      <!-- /.container-fluid -->
    </nav>
  </header>


	@yield('content')

       <!--original included js-->
        <!--	 Scripts
	<script src="//cdnjs.cloudflare.com/ajax/libs/jquery/2.1.3/jquery.min.js"></script>
	<script src="//cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/3.3.1/js/bootstrap.min.js"></script>-->



        <!-- Resolve conflict in jQuery UI tooltip with Bootstrap tooltip -->
				<script>
				  var AdminLTEOptions = {
				    sidebarExpandOnHover: true,
				    //BoxRefresh Plugin
				    enableBoxRefresh: true,
				    //Bootstrap.js tooltip
				    enableBSToppltip: true
				  };
				</script>
				<!-- <script src="{{ asset('/plugin/dist/js/app.js') }}"></script> -->
        <script>
          // $.widget.bridge('uibutton', $.ui.button);
        </script>
        <!--Bootstrap 3.3.6 -->
        <script src="{{ asset('/plugin/bootstrap/js/bootstrap.min.js') }}"></script>
				<!-- Select2 -->
				<script src="{{ asset('/plugin/select2/select2.full.min.js') }}"></script>
<!--        Morris.js charts
        <script src="https://cdnjs.cloudflare.com/ajax/libs/raphael/2.1.0/raphael-min.js"></script>
        <script src="{{ asset('/plugin/morris/morris.min.js') }}"></script>-->
        <!-- Sparkline -->
        <script src="{{ asset('/plugin/sparkline/jquery.sparkline.min.js') }}"></script>
        <!-- jvectormap -->
        <script src="{{ asset('/plugin/jvectormap/jquery-jvectormap-1.2.2.min.js') }}"></script>
        <script src="{{ asset('/plugin/jvectormap/jquery-jvectormap-world-mill-en.js') }}"></script>
        <!-- jQuery Knob Chart -->
        <script src="{{ asset('/plugin/knob/jquery.knob.js') }}"></script>
        <!-- daterangepicker -->
        <script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.11.2/moment.min.js"></script>
        <script src="{{ asset('/plugin/daterangepicker/daterangepicker.js') }}"></script>
				<script src="{{ asset('/plugin/fullcalendar/fullcalendar.min.js') }}"></script>
        <!-- datepicker -->
        <script src="{{ asset('/plugin/datepicker/bootstrap-datepicker.js') }}"></script>
        <!-- Bootstrap WYSIHTML5 -->
        <script src="{{ asset('/plugin/bootstrap-wysihtml5/bootstrap3-wysihtml5.all.min.js') }}"></script>
				<!-- bootstrap time picker -->
				<script src="{{ asset('/plugin/timepicker/bootstrap-timepicker.min.js') }}"></script>
        <!-- Slimscroll -->
        <script src="{{ asset('/plugin/slimScroll/jquery.slimscroll.min.js') }}"></script>
				<!-- icheck js -->
				<script src="{{ asset('/plugin/iCheck/icheck.js') }}"></script>
        <!-- FastClick -->
        <script src="{{ asset('/plugin/fastclick/fastclick.js') }}"></script>

				<!-- Firdaus 20180622 - dm-uploader/drag and drop -->
				<script src="{{ asset('/plugin/dmuploader/js/jquery.dm-uploader.js') }}"></script>
				<script src="{{ asset('/plugin/dmuploader/js/drag&drop.js') }}"></script>
				<!-- Firdaus 20180622 - dm-uploader/drag and drop -->

        <!-- AdminLTE App -->
        <script src="{{ asset('/plugin/dist/js/app.js') }}"></script>


        <!--detect current page and set active node in side bar-->
        <script>
            <!--Remove active for all items.-->
            $('.sidebar-menu li').removeClass('active');

            <!--highlight submenu item-->
           $('li a[href="' + this.location + '"]').addClass('active');

            <!--Highlight parent menu item.-->
           $('ul a[href="' + this.location + '"]').parents('li').addClass('treeview active');

					 //update logs in logintrackings

					 function updatelog(userid,status)
				   {
				      $.ajaxSetup({
				         headers: { 'X-CSRF-Token' : $('meta[name=_token]').attr('content') }
				      });

				      $.ajax({
				                  url: "{{ url('loginlog') }}",
				                  method: "POST",
				                  data: {
				                    UserId:userid,
				                    Event:status
				                  },
				                  success: function(response){
				                    // if (response==0)
				                    // {
														//
				                    // alert("failed");
				                    // }
				                    // else {
				                    // 	alert("success");
				                    // }
				          				}
				      });

				    }

						@if( ! empty($me))
						function logout()
				    {
				      staffid=$('[name="StaffID"]').val();
				      password=$('[name="Password"]').val();

				     if (staffid=="" || password=="")
				      {
				        $("#exist-alert").show();
				        $("#changepasswordmessage").html("Staff ID and Password cannot be empty!");
				      }
				      else {
				        $("#exist-alert").hide();
				        $.ajaxSetup({
				           headers: { 'X-CSRF-Token' : $('meta[name=_token]').attr('content') }
				        });
				        $.ajax({
				                    url: "{{ url('/user/logout') }}",
				                    method: "POST",
				                    data: {StaffID:staffid,
																UserId:{{$me->UserId}},
				                    Password:password},
				                    success: function(response){
				                      if (response==1)
				                      {
				                        window.location.replace("{{ url('/auth/logout') }}");
				                      }
				                      else {
				                        $("#exist-alert").show();
				                        $("#changepasswordmessage").html("Invalid admin credential!");
				                      }
				            }
				        });
				      }
				    }
						@endif

        </script>

</body>
</html>
