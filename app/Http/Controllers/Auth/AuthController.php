<?php namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Contracts\Auth\Guard;
use Illuminate\Contracts\Auth\Registrar;
use Illuminate\Foundation\Auth\AuthenticatesAndRegistersUsers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Validator;
use Illuminate\Support\Facades\Mail;
use App\Http\Controllers\CommonController;

use Session;


class AuthController extends Controller {

	/*
	|--------------------------------------------------------------------------
	| Registration & Login Controller
	|--------------------------------------------------------------------------
	|
	| This controller handles the registration of new users, as well as the
	| authentication of existing users. By default, this controller uses
	| a simple trait to add these behaviors. Why don't you explore it?
	|
	*/
	protected $loginPath = '/auth/login';

	    protected $redirectPath = '/';

	    protected $redirectAfterLogout = '/auth/login';

	use AuthenticatesAndRegistersUsers;

	/**
	 * Create a new authentication controller instance.
	 *
	 * @param  \Illuminate\Contracts\Auth\Guard  $auth
	 * @param  \Illuminate\Contracts\Auth\Registrar  $registrar
	 * @return void
	 */
	public function __construct(Guard $auth, Registrar $registrar)
	{

		$this->auth = $auth;
		$this->registrar = $registrar;

		$this->middleware('guest', ['except' => ['getLogout']]);

	}

	public function getLogout()
    {

    	DB::table('logintrackings')
			->insert(array(
			'Event' => 'Logout',
			'UserId' => $this->auth->id()
		));

        $this->auth->logout();
		Session::flush();
        return redirect('/auth/login');
    }

	public function getRegister()
    {

				$options= DB::table('options')
				->whereIn('Table', ["users"])
				->orderBy('Table','asc')
				->orderBy('Option','asc')
				->get();

				//Requested by Ravi 28/4/2020
				$holiday = DB::table('holidayterritories')
				->select('Id','Name')
				->get();

        return view('auth.register', ['options' =>$options, 'holiday'=>$holiday]);
    }

	public function postLogin(Request $request)
	{
	    $this->validate($request, [
	        'StaffId' => 'required',
	       	'Password' => 'required|min:6',
	    ]);

	    $credentials = array(
			    'StaffId' => $request->input('StaffId'),
			    'password' => $request->input('Password'),
			);

			$check = DB::table('users')
			->where('users.StaffId', '=',$request->input('StaffId'))
			->first();

			if ($check)
			{
					if ($check->Active==0)
					{
						return redirect($this->loginPath())
												->withInput($request->only('StaffId', 'remember'))
												->withErrors([
														'StaffId' => 'This account is in-active.',
												]);
					}
			}

	    if ($this->auth->attempt($credentials, $request->has('remember')))
	    {

				$me = (new CommonController)->get_current_user();

				$loginlog= DB::table('logintrackings')
							->insert(array(
							'Event' => "Login",
							'UserId' => $me->UserId
						));

	        return redirect($this->redirectPath());
	    }

	    return redirect($this->loginPath())
	                ->withInput($request->only('StaffId', 'remember'))
	                ->withErrors([
	                    'StaffId' => 'This credentials do not match our records.',
	                ]);
	}

	public function postRegister(Request $request)
	{

		$emails = array();

		$messages = [
    	'Name.required' => 'The Name field is required.',
		'Company_Email.required' => 'The Email field is required.',
		'Personal_Email.required' => 'The Email field is required.',
		'User_Type.required' => 'The User Type field is required.',
		'Contact_No_1.required' => 'The Contact No field is required.',
		// 'Country_Base.required' => 'The Country Base field is required.',
		'NRIC.required' => 'The NRIC field is required.',
		'NRIC.unique' => 'The NRIC has already been taken.',
		'Passport_No.required' => 'The Passport No field is required.',
		'Company.required' => 'The Company field is required.',
		'Passport_No.unique' => 'The Passport No has already been taken.',
		];

		$rules = array(
			'Name'             => 'required',
			'Email_Type' 			 => 'required',                     // just a normal required validation
			'Company_Email'            => 'required_if:Email_Type,==,"Company"|email|unique:users',     // required and must be unique in the ducks table
			'Personal_Email'            => 'required_if:Email_Type,==,"Personal"|email|unique:users',     // required and must be unique in the ducks table
			'User_Type' 			 => 'required',
			// 'Country_Base' 			 => 'required',
			'Institution' 			 => 'required_if:User_Type,==,"Assistant Engineer"',
			'Internship_Start_Date' 			 => 'required_if:User_Type,==,"Assistant Engineer"',
			'Internship_End_Date' 			 => 'required_if:User_Type,==,"Assistant Engineer"',
			'Contact_No_1' => 'required',           // required and has to match the password field
			'Company' 			 => 'required',
			'Identity_Type' 			 => 'required',
			'NRIC' => 'required_if:Identity_Type,==,"NRIC"|unique:users|',           // required and has to match the password field
			'Passport_No' => 'required_if:Identity_Type,==,"Passport No"|unique:users'           // required and has to match the password field
    );

		// $validator=$this->validate($request, [
		// 	'Name'             => 'required',                        // just a normal required validation
		// 	'Email'            => 'required|email|unique:users',     // required and must be unique in the ducks table
		// 	'User_Type' 			 => 'required',
		// 	'Contact_No' => 'required',           // required and has to match the password field
		// 	'IC_No' => 'required'           // required and has to match the password field
		// ]);

		$validator = Validator::make($request->all(), $rules,$messages);

		if ($validator->fails()) {

        $this->throwValidationException(
            $request, $validator
        );
    }

		$input = $request->all();

		$user=$this->registrar->create($request->all());

		$userid=$user->Id;

		$template= DB::table('accesscontroltemplates')
		->where('Template_Name','=','Default Template')
		->first();

		$templateid=$template->Id;

		DB::table('users')
					->where('Id', $userid)
					->update(array(
					'AccessControlTemplateId' =>  $templateid
				));

		if ($input["Company_Email"]!="")
		{
			array_push($emails,$input["Company_Email"]);
		}

		if ($input["Personal_Email"]!="")
		{
			array_push($emails,$input["Personal_Email"]);
		}

		if ($input["User_Type"]=="Contractor")
		{
			DB::table('contractorreferences')->insert(
				['UserId' => $userid
				]
			);

			$notify = DB::table('users')
			->where('Admin', "=",1)
			->get();

		}
		elseif ($input["User_Type"]=="Assistant Engineer")
		{
			DB::table('qualifications')->insert(
				['UserId' => $userid,
				 'Institution' => $input["Institution"]
				]
			);

			DB::table('users')
						->where('Id', $userid)
						->update(array(
						'Internship_Start_Date' =>  $input["Internship_Start_Date"],
						'Internship_End_Date' => $input["Internship_End_Date"],
						'Internship_Status' => 'Accepted'
					));

					$notify = DB::table('users')
					->where('Admin', "=",1)
					->get();
		}
		else {
			$notify = DB::table('users')
			->where('Admin', "=",1)
			->get();
		}

		foreach ($notify as $user) {
			if ($user->Company_Email!="")
			{
				array_push($emails,$user->Company_Email);
			}

			if ($user->Personal_Email!="")
			{
				array_push($emails,$user->Personal_Email);
			}

		}

		// Mail::send('emails.register', ['detail'=>$input], function($message) use ($emails)
		// {
		// 		$message->to($emails)->subject('New Account Registered Pending Approval');
		// });

		return redirect($this->loginPath())->with('status', 'Success!');
	}

}
