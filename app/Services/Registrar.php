<?php namespace App\Services;

use App\Model\User;
use Validator;
use Illuminate\Contracts\Auth\Registrar as RegistrarContract;

class Registrar implements RegistrarContract {

	/**
	 * Get a validator for an incoming registration request.
	 *
	 * @param  array  $data
	 * @return \Illuminate\Contracts\Validation\Validator
	 */
	public function validator(array $data)
	{
		return Validator::make($data, [
					'Name'             => 'required',                        // just a normal required validation
					'Company_Email'            => 'required|email|unique:users',     // required and must be unique in the ducks table
					'Personal_Email'            => 'required|email|unique:users',     // required and must be unique in the ducks table
					'Password' => 'required|min:6',
					'password_confirmation' => 'required|same:Password'
		]);
	}

	/**
	 * Create a new user instance after a valid registration.
	 *
	 * @param  array  $data
	 * @return User
	 */
	public function create(array $data)
	{

		$data['Name']=strtoupper($data['Name']);

		$user = User::create([
			'Name' => $data['Name'],
			'Company_Email' => $data['Company_Email'],
			'Personal_Email' => $data['Personal_Email'],
			'Password' => bcrypt($data['Password']),
			'User_Type' => $data['User_Type'],
			'Company' => $data['Company'],
			'Country_Base' => $data['Country_Base'],
			'Contact_No_1' => $data['Contact_No_1'],
			'NRIC' => $data['NRIC'],
			'Passport_No' => $data['Passport_No'],
			'DOB' => $data['DOB'],
			'Union_No' => $data['Union_No'],
			'Contact_No_2' => $data['Contact_No_2'],
			'House_Phone_No' => $data['House_Phone_No'],
			'Nationality' => $data['Nationality'],
			'Permanent_Address' => $data['Permanent_Address'],
			'Marital_Status' => $data['Marital_Status'],
			'Race' => $data['Race'],
			'Gender' => $data['Gender'],
			'Religion' => $data['Religion'],
			'Emergency_Contact_Person' => $data['Emergency_Contact_Person'],
			'Emergency_Contact_No' => $data['Emergency_Contact_No'],
			'Emergency_Contact_Relationship' => $data['Emergency_Contact_Relationship'],
			'Emergency_Contact_Person_2' => $data['Emergency_Contact_Person_2'],
			'Emergency_Contact_No_2' => $data['Emergency_Contact_No_2'],
			'Emergency_Contact_Relationship_2' => $data['Emergency_Contact_Relationship_2'],
			'Bank_Name' => $data['Bank_Name'],
			'Bank_Account_No' => $data['Bank_Account_No'],
			'EPF_No' => $data['EPF_No'],
			'SOCSO_No' => $data['SOCSO_No'],
			'Income_Tax_No' => $data['Income_Tax_No'],
			'StaffId' => $data['StaffId'],
			'Department' => $data['Department'],
			'HolidayTerritoryId' => $data['HolidayTerritoryId'],
			'Working_Days' => $data['Working_Days'],
			'Joining_Date' => $data['Joining_Date'],
			'Confirmation_Date' => $data['Confirmation_Date'],
			'Resignation_Date' => $data['Resignation_Date']
		]);

		return $user;
	}

}
