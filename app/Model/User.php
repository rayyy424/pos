<?php namespace App\Model;

use Illuminate\Auth\Authenticatable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Auth\Passwords\CanResetPassword;
use Illuminate\Contracts\Auth\Authenticatable as AuthenticatableContract;
use Illuminate\Contracts\Auth\CanResetPassword as CanResetPasswordContract;

class User extends Model implements AuthenticatableContract, CanResetPasswordContract {

	use Authenticatable, CanResetPassword;

	/**
	 * The database table used by the model.
	 *
	 * @var string
	 */
	protected $table = 'users';

	protected $primaryKey = 'Id';

	/**
	 * The attributes that are mass assignable.
	 *
	 * @var array
	 */
	protected $fillable = 
	[
		'Name', 
		'Company_Email',
		'Personal_Email', 
		'Password',
		'User_Type',
		'Country_Base',
		'Company',
		'Contact_No_1',
		'NRIC',
		'Passport_No',
		'DOB',
		'Union_No',
		'Contact_No_2',
		'House_Phone_No',
		'Nationality',
		'Permanent_Address',
		'Marital_Status',
		'Race',
		'Gender',
		'Religion',
		'Emergency_Contact_Person',
		'Emergency_Contact_No',
		'Emergency_Contact_Relationship',
		'Emergency_Contact_Person_2',
		'Emergency_Contact_No_2',
		'Emergency_Contact_Relationship_2',
		'Bank_Name',
		'Bank_Account_No',
		'EPF_No',
		'SOCSO_No',
		'Income_Tax_No',
		'StaffId',
		'Department',
		'HolidayTerritoryId',
		'Working_Days',
		'Joining_Date',
		'Confirmation_Date',
		'Resignation_Date'
	];

	/**
	 * The attributes excluded from the model's JSON form.
	 *
	 * @var array
	 */
	protected $hidden = ['Password', 'remember_token'];

        /**
        * Run the migrations.
        *
        * @return void
        */
        public function up()
        {
            Schema::create('users', function(Blueprint $table)
            {
                $table->increments('Id');
								$table->int('AccessControlTemplateId');
								$table->int('AllowanceSchemeId');
								$table->string('StaffId');
                $table->string('Name');
								$table->string('Nick_Name');
								$table->string('Company_Email');
                $table->string('Personal_Email');
								$table->string('Contact_No_1');
								$table->string('Contact_No_2');
								$table->string('Permanent_Address');
								$table->string('Current_Address');
								$table->string('Home_Base');
								$table->string('Country_Base');
								$table->string('Nationality');
                $table->string('Password');
								$table->string('User_Type');
                $table->string('DOB');
								$table->string('NRIC');
								$table->string('Passport_No');
								$table->string('Gender');
								$table->string('Marital_Status');
								$table->integer('SuperiorId');
								$table->string('Company');
								$table->string('Department');
								$table->string('Position');
								$table->string('Joining_Date');
								$table->string('Resignation_Date');
								$table->string('Internship_Start_Date');
								$table->string('Internship_End_Date');
								$table->string('Internship_Status');
								$table->string('Emergency_Contact_Person');
								$table->string('Emergency_Contact_No');
								$table->string('Emergency_Contact_Relationship');
								$table->string('Emergency_Contact_Address');
                $table->string('remember_token')->nullable();
								$table->string('Note')->nullable();
								$table->boolean('Active');
								$table->boolean('Admin');
								$table->boolean('Approved');
								$table->boolean('First_Change');
								$table->timestamp('Detail_Approved_On');
								$table->timestamp('Status');
								$table->string('Comment');
                $table->timestamps();
            });
        }

        /**
        * Reverse the migrations.
        *
        * @return void
        */
        public function down()
        {
             Schema::drop('users');
        }

				// public function getAuthPassword()
				// {
				//      return $this->attributes['Password'];//change the 'passwordFieldinYourTable' with the name of your field in the table
				// }

				public function getAuthPassword() {
    			return $this->Password;
				}

			public function getEmailForPasswordReset() {
				if($this->Company_Email)
				{
					return $this->Company_Email;
				}
				else {
					return $this->Personal_Email;

				}
			}

}
