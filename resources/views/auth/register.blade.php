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
  max-width: 1200px;
  max-height: 1500px;
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
  right: 0;
  left: 0;
  background: #3c8dbc;
  width: 1200px;
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
  width: 20%;
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

			$("#contractorinfo").hide();
			$("#interninfo").hide();
			$("#companyemailfield").hide();
			$("#personalemailfield").hide();
			$("#nricfield").hide();
			$("#unionfield").hide();
			$("#passportfield").hide();
			$('#EPF_No_Div').hide();
			$('#SOCSO_No_Div').hide();
			$('#Income_Tax_No_Div').hide();
			$('#Country_Base_Div').hide();

			@if (Input::old('User_Type') == 'Assistant Engineer')
				$("#interninfo").show();
				$("#contractorinfo").hide();
			@elseif (Input::old('User_Type') == 'Contractor')
				$("#interninfo").hide();
				$("#contractorinfo").show();
			@else
					$("#interninfo").hide();
					$("#contractorinfo").hide();
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
				$('#unionfield').hide();
			@elseif (Input::old('Identity_Type') == 'Passport No')
				$("#nricfield").hide();
				$("#passportfield").show();
				$('#unionfield').hide();
			@elseif (Input::old('Identity_Type') == 'Union No')
				$("#nricfield").hide();
				$("#passportfield").hide();
				$('#unionfield').show();
			@endif

			@if (Input::old('Project') != '')
				@foreach ($project as $item)

					if ($('#Project').val()=="{{$item->ProjectId}}")
					{
						$('#Project_Manager').append($("<option/>", {
							value: "{{$item->UserId}}",
							text: "{{$item->Name}}"
					}));
					}

				@endforeach

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
				<div class="row">
					<div class="col-md-6">
						<div class="form-group">
							<label class="col-md-4 control-label">Name</label>
							<div class="col-md-6">
								<input type="text" class="form-control" name="Name" placeholder="Your full name as per IC" value="{{ old('Name') }}">
							</div>
						</div>

						<div class="form-group">
							<label class="col-md-4 control-label">Staff ID</label>
							<div class="col-md-6">
								<input type="text" class="form-control" name="StaffId" value="{{ old('StaffId') }}">
							</div>
						</div>

						<div class="form-group">
							<label class="col-md-4 control-label">Department</label>
							<div class="col-md-6">
								<select class="select2 form-control" name="Department" value="{{ old('Department') }}">
									<option></option>
									@foreach ($project as $p)
                                       <option <?php if(Input::old('Department') == $p->Project_Name) echo 'selected="selected" '; ?>>{{$p->Project_Name}}</option>
                                    @endforeach
								</select> 
							</div>
						</div>

						<div class="form-group">
							<label class="col-md-4 control-label">Holiday Territory</label>
							<div class="col-md-6">
								<select class="select2 form-control" name="HolidayTerritoryId" value="{{ old('HolidayTerritoryId') }}">
									<option></option>
									@foreach ($holiday as $h)
                                       <option value="{{$h->Id}}" <?php if(Input::old('HolidayTerritoryId') == $h->Id) echo 'selected="selected" '; ?>>{{$h->Name}}</option>
                                    @endforeach
								</select> 
							</div>
						</div>

						<div class="form-group">
							<label class="col-md-4 control-label">Identity Type</label>
							<div class="col-md-6">
								 <select class="form-control select2" id="Identity_Type" name="Identity_Type" style="width: 100%;">
									 <option></option>
									 <option <?php if(Input::old('Identity_Type') == 'NRIC') echo ' selected="selected" '; ?>>NRIC</option>
									 <option <?php if(Input::old('Identity_Type') == 'Passport No') echo ' selected="selected" '; ?>>Passport No</option>
									 <option <?php if(Input::old('Identity_Type') == 'Union No') echo ' selected="selected" '; ?>>Union No</option>
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

						<div id="unionfield">
							<div class="form-group">
								<label class="col-md-4 control-label">Union No</label>
								<div class="col-md-6">
		                  <input type="text" class="form-control" name="Union_No" placeholder="Enter your union no" value="{{ old('Union_No') }}">
								</div>
							</div>
						</div>

						<div class="form-group">
							<label class="col-md-4 control-label">Date of Birth</label>
							<div class="col-md-6">
								<input type="text" class="date form-control" name="DOB" placeholder="Date of birth as per IC" value="{{ old('DOB') }}">
							</div>
						</div>

						<div class="form-group">
							<label class="col-md-4 control-label">Marital Status</label>
							<div class="col-md-6">
								<select class="select2 form-control" name="Marital_Status" value="{{ old('Marital_Status') }}">
									<option></option>
									 @foreach ($options as $key => $option)
                                      @if ($option->Field=="Marital_Status")

                                        <option <?php if(Input::old('Marital_Status') == $option->Option) echo ' selected="selected" '; ?>>{{$option->Option}}</option>

                                      @endif
                                    @endforeach
								</select> 
							</div>
						</div>

						<div class="form-group">
							<label class="col-md-4 control-label">Gender</label>
							<div class="col-md-6">
								<select class="select2 form-control" name="Gender" value="{{ old('Gender') }}">
									<option></option>
									@foreach ($options as $key => $option)
                                      @if ($option->Field=="Gender")

                                        <option <?php if(Input::old('Gender') == $option->Option) echo ' selected="selected" '; ?>>{{$option->Option}}</option>

                                      @endif
                                    @endforeach
								</select> 
							</div>
						</div>

						<div class="form-group">
							<label class="col-md-4 control-label">Nationality</label>
							<div class="col-md-6">
								<select class="select2 form-control" name="Nationality" value="{{ old('Nationality') }}">
									<option></option>
									@foreach ($options as $key => $option)
                                    @if ($option->Field=="Nationality")

                                      <option <?php if(Input::old('Nationality') == $option->Option) echo ' selected="selected" '; ?>>{{$option->Option}}</option>

                                    @endif
                                  @endforeach
								</select> 
							</div>
						</div>

						<div class="form-group">
							<label class="col-md-4 control-label">Race</label>
							<div class="col-md-6">
								<select class="select2 form-control" name="Race" value="{{ old('Race') }}">
									<option></option>
									@foreach ($options as $key => $option)
                                    @if ($option->Field=="Race")

                                      <option <?php if(Input::old('Race') == $option->Option) echo ' selected="selected" '; ?>>{{$option->Option}}</option>

                                    @endif
                                  @endforeach
								</select> 
							</div>
						</div>

						<div class="form-group">
							<label class="col-md-4 control-label">Religion</label>
							<div class="col-md-6">
								<select class="select2 form-control" name="Religion" value="{{ old('Religion') }}">
									<option></option>
									@foreach ($options as $key => $option)
                                    @if ($option->Field=="Religion")

                                      <option <?php if(Input::old('Religion') == $option->Option) echo ' selected="selected" '; ?>>{{$option->Option}}</option>

                                    @endif
                                  @endforeach
								</select> 
							</div>
						</div>

						<div class="form-group">
							<label class="col-md-4 control-label">Permanent Address</label>
							<div class="col-md-6">
								<textarea class="form-control" id="Permanent_Address" name="Permanent_Address" placeholder="" value="{{old('Permanent_Address')}}"></textarea>
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
									 <option value="Contractor" <?php if(Input::old('User_Type') == 'Contractor') echo ' selected="selected" '; ?>>Contractor (Project Basis)</option>
								 </select>
							</div>
						</div>

						<div class="form-group" id="Country_Base_Div">
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

						<div id="contractorinfo">
							<div class="form-group">
								<label class="col-md-4 control-label">Project</label>
								<div class="col-md-6">
									 <select class="form-control select2" id="Project" name="Project" style="width: 100%;">
										 <option></option>
										 @foreach ($project as $item)
												<option <?php if(Input::old('Project') == $item->ProjectId) echo ' selected="selected" '; ?> value="{{$item->ProjectId}}">{{$item->Project_Name}}</option>

										 @endforeach

									 </select>
								</div>
							</div>

							<div class="form-group">
								<label class="col-md-4 control-label">Project Manager</label>
								<div class="col-md-6">
									 <select class="form-control select2" id="Project_Manager" name="Project_Manager" style="width: 100%;">


									 </select>
								</div>
							</div>



						</div>

					</div><!-- div ended -->
					<div class="row col-md-6">

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
							<label class="col-md-4 control-label">Working Days</label>
							<div class="col-md-6">
								<select class="form-control select2" id="Working_Days" name="Working_Days" style="width: 100%;">
									<option></option>
									<option <?php if(Input::old('Working_Days') == 5.0) echo ' selected="selected" '; ?>>5.0</option>
                                    <option <?php if(Input::old('Working_Days') == 5.5) echo ' selected="selected" '; ?>>5.5</option>
                                    <option <?php if(Input::old('Working_Days') == 6.0) echo ' selected="selected" '; ?>>6.0</option>
								</select>

							</div>
						</div>

						<div class="form-group">
							<label class="col-md-4 control-label">Joined Date</label>
							<div class="col-md-6">
		                  		<input type="text" class="date form-control" id="Joining_Date" name="Joining_Date" value="{{ old('Joining_Date') }}">
							</div>
						</div>

						<div class="form-group">
							<label class="col-md-4 control-label">Confirmation Date</label>
							<div class="col-md-6">
		                  		<input type="text" class="date form-control" id="Confirmation_Date" name="Confirmation_Date" value="{{ old('Confirmation_Date') }}">
							</div>
						</div>

						<div class="form-group">
							<label class="col-md-4 control-label">Resignation Date</label>
							<div class="col-md-6">
		                  		<input type="text" class="date form-control" id="Resignation_Date" name="Resignation_Date" value="{{ old('Resignation_Date') }}">
							</div>
						</div>

						<div class="form-group">
							<label class="col-md-4 control-label">Contact No 1</label>

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
							<label class="col-md-4 control-label">Contact No 2</label>

							<div class="col-md-6">
								<div class="input-group">
	                  				<div class="input-group-addon">
	                    				<i class="fa fa-phone"></i>
	                  				</div>
	                  			<input type="text" class="form-control" name="Contact_No_2" placeholder="+60123456789" value="{{ old('Contact_No_2') }}">
	               				 </div>
							</div>
						</div>

						<div class="form-group">
							<label class="col-md-4 control-label">House Phone No</label>

							<div class="col-md-6">
								<div class="input-group">
	                  				<div class="input-group-addon">
	                    				<i class="fa fa-phone"></i>
	                  				</div>
	                  			<input type="text" class="form-control" name="House_Phone_No" placeholder="+60123456789" value="{{ old('House_Phone_No') }}">
	               				 </div>
							</div>
						</div>

						<div class="form-group">
							<label class="col-md-4 control-label">Emergency Contact</label>
							<div class="col-md-6">
		                  		<input type="text" class="form-control" name="Emergency_Contact_Person" placeholder="Name" value="{{ old('Emergency_Contact_Person') }}">
							</div>
						</div>

						<div class="form-group">
							<label class="col-md-4 control-label">Emergency Contact No</label>

							<div class="col-md-6">
								<div class="input-group">
	                  				<div class="input-group-addon">
	                    				<i class="fa fa-phone"></i>
	                  				</div>
	                  			<input type="text" class="form-control" name="Emergency_Contact_No" placeholder="+60123456789" value="{{ old('Emergency_Contact_No') }}">
	               				 </div>
							</div>
						</div>

						<div class="form-group">
							<label class="col-md-4 control-label">Relationship</label>
							<div class="col-md-6">
		                  		<input type="text" class="form-control" name="Emergency_Contact_Relationship" placeholder="Relationship to Emergency Contact" value="{{ old('Emergency_Contact_Relationship') }}">
							</div>
						</div>

						<div class="form-group">
							<label class="col-md-4 control-label">Emergency Contact 2</label>
							<div class="col-md-6">
		                  		<input type="text" class="form-control" name="Emergency_Contact_Person_2" placeholder="Name" value="{{ old('Emergency_Contact_Person_2') }}">
							</div>
						</div>

						<div class="form-group">
							<label class="col-md-4 control-label">Emergency Contact No</label>

							<div class="col-md-6">
								<div class="input-group">
	                  				<div class="input-group-addon">
	                    				<i class="fa fa-phone"></i>
	                  				</div>
	                  			<input type="text" class="form-control" name="Emergency_Contact_No_2" placeholder="+60123456789" value="{{ old('Emergency_Contact_No_2') }}">
	               				 </div>
							</div>
						</div>

						<div class="form-group">
							<label class="col-md-4 control-label">Relationship</label>
							<div class="col-md-6">
		                  		<input type="text" class="form-control" name="Emergency_Contact_Relationship_2" placeholder="Relationship to Emergency Contact2" value="{{ old('Emergency_Contact_Relationship_2') }}">
							</div>
						</div>

						<div class="form-group">
							<label class="col-md-4 control-label">Bank Name</label>
							<div class="col-md-6">
								 <select class="form-control select2" id="Bank_Name" name="Bank_Name" style="width: 100%;">
									 <option></option>
									 @foreach ($options as $option)
										  @if ($option->Field=="Bank_Name")
												<option <?php if(Input::old('Bank_Name') == $option->Option) echo ' selected="selected" '; ?> value="{{$option->Option}}">{{$option->Option}}</option>
											@endif
									 @endforeach

								 </select>
							</div>
						</div>

						<div class="form-group">
							<label class="col-md-4 control-label">Bank Account No</label>
							<div class="col-md-6">
		                  		<input type="text" class="form-control" name="Bank_Account_No" value="{{ old('Bank_Account_No') }}">
							</div>
						</div>

						<div id="EPF_No_Div" class="form-group">
							<label class="col-md-4 control-label">EPF No</label>
							<div class="col-md-6">
		                  		<input type="text" class="form-control" name="EPF_No" value="{{ old('EPF_No') }}">
							</div>
						</div>

						<div id="SOCSO_No_Div" class="form-group">
							<label class="col-md-4 control-label">SOCSO No</label>
							<div class="col-md-6">
		                  		<input type="text" class="form-control" name="SOCSO_No" value="{{ old('SOCSO_No') }}">
							</div>
						</div>

						<div id="Income_Tax_No_Div" class="form-group">
							<label class="col-md-4 control-label">Income Tax No</label>
							<div class="col-md-6">
		                  		<input type="text" class="form-control" name="Income_Tax_No" value="{{ old('Income_Tax_No') }}">
							</div>
						</div>

					</div><!-- dov ended -->
				</div> <!-- row ended -->
						<input type="hidden" class="form-control" name="Password" value="123@abc">

						<div class="row">
							<div class="form-group">
								<!-- <div class="col-md-6 col-md-offset-4"> -->
									<button type="submit" class="btn btn-primary">
										Register
									</button>
									&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
									<button type="button" class="btn btn-primary" onclick="location.href = '{{ url('auth/login') }}';">
										Go to Login Page
									</button>
								</div>
							<!-- </div> -->
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
  	$('.date').datepicker({
  	  format: 'dd-M-yyyy',
      autoclose: true
  	});
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
		  if (this.value=="Contractor")
			{
				$("#contractorinfo").show();
				$("#interninfo").hide();

			}else if (this.value=="Assistant Engineer") {
				$("#contractorinfo").hide();
				$("#interninfo").show();
			}
			else {
				$("#contractorinfo").hide();
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
					$('#unionfield').hide();

				}else if (this.value=="Passport No") {
					$("#nricfield").hide();
					$("#passportfield").show();
					$('#unionfield').hide();
				}else if(this.value=="Union No") {
					$("#nricfield").hide();
					$("#passportfield").hide();
					$('#unionfield').show();
				}

			});

			$('#Project').on('change', function() {
				$('#Project_Manager')
			    .find('option')
			    .remove();

				@foreach ($project as $item)

					if ($('#Project').val()=="{{$item->ProjectId}}")
					{
						$('#Project_Manager').append($("<option/>", {
			        value: "{{$item->UserId}}",
			        text: "{{$item->Name}}"
			    }));
					}

				@endforeach

			});

  });
</script>
@endsection
