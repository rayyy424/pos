@extends('app')
<style>
.pen-title {
  padding: 50px 0;
  text-align: center;

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
  max-width: 600px;
  max-height: 900px;
  min-height:500px;
  width: 100%;
  border-top: 5px solid #3c8dbc;
  box-shadow: 0 0 3px rgba(0, 0, 0, 0.1);
  margin: 50 auto;
  margin-top:20px;
}
.form-module .toggle {

  position: absolute;
  top: -0;
  right: -0;
  background: #3c8dbc;
  width: 600px;
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
  border: 1px solid #d9d9d9;
  margin: 0 0 0px;
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
  width: 45%;
  border: 0;
  padding: 10px 5px;
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
</style>
@section('datatable-css')

	<script type="text/javascript" language="javascript" class="init">
	  $(document).ready(function() {

			$("#interninfo").hide();
			$("#companyemailfield").hide();
			$("#personalemailfield").hide();
			$("#nricfield").hide();
			$("#passportfield").hide();

			@if (Input::old('User_Type') == 'Assistant Engineer')
				$("#interninfo").show();
			@else
				$("#interninfo").hide();
			@endif

			@if (Input::old('Email_Type') == 'Company')
				$("#companyemailfield").show();
				$("#personalemailfield").hide();
			@elseif (Input::old('Email_Type') == 'Personal')
				$("#companyemailfield").hide();
				$("#personalemailfield").show();
			@endif

			@if (Input::old('Identity_Type') == 'NRIC')
				$("#nricfield").show();
				$("#passportfield").hide();
			@elseif (Input::old('Identity_Type') == 'Passport No')
				$("#nricfield").hide();
				$("#passportfield").show();
			@endif

		} );

	</script>

@endsection


@section('content')
<div class="container-fluid">
	<div class="row">

	<div class="panel-body">
				<div class="module form-module">

					<div class="pen-title">

  					<div class="toggle">REGISTER NEW ACCOUNT</i></div>
  					<br><br>

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

					<form class="form-horizontal" role="form" method="POST" action="{{ url('auth/register') }}">
						<input type="hidden" name="_token" value="{{ csrf_token() }}">


						<div class="form-group">
							<label class="col-md-4 control-label">Name</label>
							<div class="col-md-6">
								<input type="text" class="form-control" name="Name" placeholder="Your full name as per IC" value="{{ old('Name') }}">
							</div>
						</div>

						<div class="form-group">
							<label class="col-md-4 control-label">Email Type</label>
							<div class="col-md-6">
								 <select class="form-control select2" id="Email_Type" name="Email_Type" style="width: 100%;">
									 <option></option>
									 <option <?php if(Input::old('Email_Type') == 'Company') echo ' selected="selected" '; ?>>Company</option>
									 <option <?php if(Input::old('Email_Type') == 'Personal') echo ' selected="selected" '; ?>>Personal</option>
								 </select>
							</div>
						</div>

						<div id="companyemailfield">
							<div class="form-group">
								<label class="col-md-4 control-label">Company Email Address</label>
								<div class="col-md-6">
									<input type="email" class="form-control" name="Company_Email" placeholder="xyz@pronetwork.com.my" value="{{ old('Company_Email') }}">
								</div>
							</div>
						</div>

						<div id="personalemailfield">
							<div class="form-group">
								<label class="col-md-4 control-label">Personal Email Address</label>
								<div class="col-md-6">
									<input type="email" class="form-control" name="Personal_Email" placeholder="xyz@hotmail.com" value="{{ old('Personal_Email') }}">
								</div>
							</div>
						</div>

						<div class="form-group">
							<label class="col-md-4 control-label">User Type</label>
							<div class="col-md-6">
								 <select class="form-control select2" id="User_Type" name="User_Type" style="width: 100%;">
									 <option></option>
									 <option <?php if(Input::old('User_Type') == 'Staff') echo ' selected="selected" '; ?>>Staff</option>
									 <option <?php if(Input::old('User_Type') == 'Assistant Engineer') echo ' selected="selected" '; ?>>Assistant Engineer</option>
								 </select>
							</div>
						</div>

						<div class="form-group">
							<label class="col-md-4 control-label">Country Base</label>
							<div class="col-md-6">
								 <select class="form-control select2" id="Country_Base" name="Country_Base" style="width: 100%;">
									 <option></option>
									 @foreach ($options as $option)
										  @if ($option->Field=="Country")
												<option <?php if(Input::old('Country_Base') == $option->Option) echo ' selected="selected" '; ?> value="{{$option->Option}}">{{$option->Option}}</option>
											@endif
									 @endforeach

								 </select>
							</div>
						</div>

						<div id="interninfo">
							<div class="form-group">
								<label class="col-md-4 control-label">Institution</label>

								<div class="col-md-6">
		                  <input type="text" class="form-control" name="Institution" placeholder="Inti College" value="{{ old('Institution') }}">
								</div>
							</div>

							<div class="form-group">
								<label class="col-md-4 control-label">Internship Start Date</label>

								<div class="col-md-6">
		                  <input type="text" class="form-control" id="Internship_Start_Date" name="Internship_Start_Date" value="{{ old('Internship_Start_Date') }}">
								</div>
							</div>

							<div class="form-group">
								<label class="col-md-4 control-label">Internship End Date</label>

								<div class="col-md-6">
											<input type="text" class="form-control" id="Internship_End_Date" name="Internship_End_Date" value="{{ old('Internship_End_Date') }}">
								</div>
							</div>

						</div>

						<div class="form-group">
							<label class="col-md-4 control-label">Company</label>
							<div class="col-md-6">
								<select class="form-control select2" id="Company" name="Company" style="width: 100%;">
									<option></option>
									@foreach ($options as $option)
										 @if ($option->Field=="Company")
											 <option <?php if(Input::old('Company') == $option->Option) echo ' selected="selected" '; ?> value="{{$option->Option}}">{{$option->Option}}</option>
										 @endif
									@endforeach

								</select>

							</div>
						</div>

						<div class="form-group">
							<label class="col-md-4 control-label">Contact No</label>

							<div class="col-md-6">
								<div class="input-group">
	                  <div class="input-group-addon">
	                    <i class="fa fa-phone"></i>
	                  </div>
	                  <input type="text" class="form-control" name="Contact_No_1" placeholder="+60123456789" value="{{ old('Contact_No_1') }}">
	                </div>
							</div>
						</div>

						<div class="form-group">
							<label class="col-md-4 control-label">Identity Type</label>
							<div class="col-md-6">
								 <select class="form-control select2" id="Identity_Type" name="Identity_Type" style="width: 100%;">
									 <option></option>
									 <option <?php if(Input::old('Identity_Type') == 'NRIC') echo ' selected="selected" '; ?>>NRIC</option>
									 <option <?php if(Input::old('Identity_Type') == 'Passport No') echo ' selected="selected" '; ?>>Passport No</option>
								 </select>
							</div>
						</div>

						<div id="nricfield">
							<div class="form-group">
								<label class="col-md-4 control-label">NRIC</label>
								<div class="col-md-6">
		                  <input type="text" class="form-control" name="NRIC" placeholder="123456-12-1234" value="{{ old('NRIC') }}">
								</div>
							</div>
						</div>

						<div id="passportfield">
							<div class="form-group">
								<label class="col-md-4 control-label">Passport No</label>
								<div class="col-md-6">
		                  <input type="text" class="form-control" name="Passport_No" placeholder="Enter your passport no" value="{{ old('Passport_No') }}">
								</div>
							</div>
						</div>

						<input type="hidden" class="form-control" name="Password" value="123@abc">

						<div class="form-group">
							<div class="col-md-6 col-md-offset-4">
								<button type="submit" class="btn btn-primary">
									Register
								</button>
								&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
								<button type="button" class="btn btn-primary" onclick="location.href = '{{ url('auth/login') }}';">
									Go to Login Page
								</button>
							</div>
						</div>
					</form>
				</div>
			</div>
		</div>
	</div>
</div>
</div>
</div>
</div>


<script>
  $(function () {

    //Initialize Select2 Elements
    $(".select2").select2();

		$('#Internship_Start_Date').datepicker({
      format: 'dd-M-yyyy',
      autoclose: true
    });

		$('#Internship_End_Date').datepicker({
      format: 'dd-M-yyyy',
      autoclose: true
    });

		$('#User_Type').on('change', function() {
		    if (this.value=="Assistant Engineer") {
				$("#interninfo").show();
			}
			else {
				$("#interninfo").hide();

			}
		});

			$('#Email_Type').on('change', function() {
			  if (this.value=="Company")
				{
					$("#companyemailfield").show();
					$("#personalemailfield").hide();

				}else if (this.value=="Personal") {
					$("#companyemailfield").hide();
					$("#personalemailfield").show();
				}
			});

			$('#Identity_Type').on('change', function() {
			  if (this.value=="NRIC")
				{
					$("#nricfield").show();
					$("#passportfield").hide();

				}else if (this.value=="Passport No") {
					$("#nricfield").hide();
					$("#passportfield").show();
				}

			});

  });
</script>
@endsection
