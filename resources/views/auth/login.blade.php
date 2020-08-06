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
	<link href='//fonts.googleapis.com/css?family=Roboto:400,300' rel='stylesheet' type='text/css'>

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

<style>
.compatibility{
	color:#6b6b6b;
	text-align: center;
}
.container-fluid{
	min-height: 90%;
	margin-right: 250px;
	margin-top: 100px;
}

@media (max-width: 767px)
{
	.container-fluid{
		min-height: 90%;
		margin-right: 72px;
		margin-top: 100px;
	}

}


.compatibility label{
	font-weight: 600;
	font-size:16px;
}

.imglogo{
	text-align:center;
	padding-bottom: 10px;
}

body {
  background: #e9e9e9;
  color: #666666;
  font-family: 'RobotoDraft', 'Roboto', sans-serif;
  font-size: 14px;
  -webkit-font-smoothing: antialiased;
  -moz-osx-font-smoothing: grayscale;
}

/* Pen Title */
.checkbox input[type=checkbox], .checkbox-inline input[type=checkbox], .radio input[type=radio], .radio-inline input[type=radio] {
  position: absolute;
  margin-top:-15px;
}
.rememberme{
  margin-left:160px;
  margin-top:-20px;
}
.pen-title {
  padding: 50px 0;
  text-align: center;
  letter-spacing: 0px;
}
.pen-title h1 {
  margin: 0 0 20px;
  font-size: 48px;
  font-weight: 300;
}
.pen-title span {
  font-size: 12px;
}
.pen-title span .fa {
  color: #3c8dbc;
}


/* Form Module */
.form-module {
  position: relative;
  background: #ffffff;
	min-width: 400px;
  max-width: 320px;
  max-height: 480px;
  min-height:480px;
  width: 100%;
  border-top: 0px solid #3c8dbc;
  box-shadow: 0 0 3px rgba(0, 0, 0, 0.1);
  margin: 50 auto;
	margin-right: 100;
}
.form-module .toggle {

  position: absolute;
  top: -0;
  right: -0;
  background: #3c8dbc;
  width: 320px;
  height: 40px;
  margin: 0px 0 0;
  color: #ffffff;
  font-size: 20px;
  line-height: 35px;
  text-align: center;
}
.form-module .toggle .tooltip {
  position: absolute;
  top: 5px;
  right: -65px;
  display: block;
  background: rgba(0, 0, 0, 0.6);
  width: auto;
  padding: 5px;
  font-size: 10px;
  line-height: 1;
  text-transform: uppercase;
}
.form-module .toggle .tooltip:before {
  content: '';
  position: absolute;
  top: 5px;
  left: -5px;
  display: block;
  border-top: 5px solid transparent;
  border-bottom: 5px solid transparent;
  border-right: 5px solid rgba(0, 0, 0, 0.6);
}
.form-module .form {
  display: none;
  padding: 40px;
}
.form-module .form:nth-child(2) {
  display: block;
}
.form-module h2 {
  margin: 0 0 20px;
  color: #3c8dbc;
  font-size: 18px;
  font-weight: 400;
  line-height: 1;
}
.form-module input {
  outline: none;
  display: block;
  width: 80%;
  border: 1px solid #d9d9d9;
  margin: 0 30 20px;
  padding: 10px 15px;
  box-sizing: border-box;
  font-weight: 400;
  -webkit-transition: 0.3s ease;
  transition: 0.3s ease;
}
.form-module input:focus {
  border: 1px solid #3c8dbc;
  color: #333333;
}
.form-module button {
  cursor: pointer;
  background: #3c8dbc;
  width: 25%;
  border: 0;
  padding: 10px 15px;
  color: #ffffff;
  -webkit-transition: 0.3s ease;
  transition: 0.3s ease;
}
.form-module button:hover {
  background: #178ab4;
}
.form-module .cta {
  background: #f2f2f2;
  width: 100%;
  padding: 15px 40px;
  box-sizing: border-box;
  color: #666666;
  font-size: 12px;
  text-align: center;
}
.form-module .cta a {
  color: #333333;
  text-decoration: none;
}

body {
    margin-bottom:50px;
}

#footer {
    position: fixed;
    height: 50px;
    bottom: 0px;
    left: 0px;
    right: 0px;
    margin-bottom: 0px;
}


</style>

</head>

<body style="background-color: white;">
	<img src="{{ asset('/img/homebanner.jpg') }}" style='position:fixed;bottom:0px;left:0px;width:100%;height:100%;z-index:-1;'>

<div class="container-fluid">
	<div class="row">

	<form class="form-horizontal pull-right" role="form" method="POST" action="{{ url('auth/login') }}">
	<input type="hidden" name="_token" value="{{ csrf_token() }}">

			@if (count($errors) > 0)
						<div class="alert alert-danger">
							<strong>Whoops!</strong> There were some problems with your input.<br><br>
							<ul>
								@foreach ($errors->all() as $error)
									<li>{{ $error }}</li>
								@endforeach
							</ul>
						</div>
					@endif

					@if (session('status'))
						<div class="alert alert-success">
							Application submitted.<br><br>
							<ul>
								You can only log into the system after account activated.
							</ul>
						</div>
					@endif

				<div class="panel-body">
				<div class="module form-module">

					<div class="pen-title">

						 <div class="col-md-12">
								<div class="imglogo"><img src="{{ URL::to('/') ."/img/logo.png"  }}" height="50px" alt="TOTG" style="border:0; margin:0;"></div>
								<br>
								<br>
						 </div>




					<!-- Form Module-->

					<center>

					<input type="text" placeholder="Staff ID"  name="StaffId" value="{{ old('StaffId') }}"/>
					<input type="password" placeholder="Password" name="Password"/>
					<div class="checkbox">
						<input type="hidden" name="remember" style="margin-left:55px;" value="true">
						{{-- <div class="rememberme" style="margin-left:-50px;">Remember Me</div></input> --}}
					</div>

					      <button type="submit">Login</button>
					      <a  href="{{ url('auth/register') }}">&nbsp;&nbsp;Register</a>


					</center>

					</div>


			<div class="compatibility">
				This portal is best viewed in <br><a href="https://www.google.com/chrome/browser/desktop/index.html?brand=CHBD&gclid=Cj0KCQjwq5LHBRCNARIsACE7DqbzRc8cBhH1_H7hYC3paUSc7XnfNtqQKXaB8fcfrRyWqPwFbUG28JwaAla0EALw_wcB">Google Chrome.</a>
			</div>



</div>
</div>

</form>

</div>
</div>


<footer class="main-footer" id="footer" style="margin-left:0px;bottom">
  <div class="pull-right hidden-xs">
    <b>Version</b> 2.0.1
  </div>
  <strong>Copyright &copy; 2014-2016 <a href="http://www.softoya.com">TrackerOnTheGo</a>.</strong> All rights
  reserved.
</footer>

<script type="text/javascript">

	$('.toggle').click(function(){
  // Switches the Icon
  $(this).children('i').toggleClass('fa-pencil');
  // Switches the forms
  $('.form').animate({
    height: "toggle",
    'padding-top': 'toggle',
    'padding-bottom': 'toggle',
    opacity: "toggle"
  }, "slow");
});
</script>

</body>
</html>
