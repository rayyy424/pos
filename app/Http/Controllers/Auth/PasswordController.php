<?php namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Contracts\Auth\Guard;
use Illuminate\Contracts\Auth\PasswordBroker;
use Illuminate\Foundation\Auth\ResetsPasswords;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Mail\Message;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Password;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class PasswordController extends Controller {

	/*
	|--------------------------------------------------------------------------
	| Password Reset Controller
	|--------------------------------------------------------------------------
	|
	| This controller is responsible for handling password reset requests
	| and uses a simple trait to include this behavior. You're free to
	| explore this trait and override any methods you wish to tweak.
	|
	*/

protected $redirectTo = 'home';

	use ResetsPasswords;

	/**
	 * Create a new password controller instance.
	 *
	 * @param  \Illuminate\Contracts\Auth\Guard  $auth
	 * @param  \Illuminate\Contracts\Auth\PasswordBroker  $passwords
	 * @return void
	 */
	public function __construct(Guard $auth, PasswordBroker $passwords)
	{
		$this->auth = $auth;
		$this->passwords = $passwords;

		$this->middleware('guest');
	}

	public function getEmail()
	{
			return view('auth.password');
	}

	/**
	 * Send a reset link to the given user.
	 *
	 * @param  \Illuminate\Http\Request  $request
	 * @return \Illuminate\Http\Response
	 */

	 public function postEmail(Request $request)
	 {
			 $this->validate($request, ['Company_Email' => 'required|email']);

			 $response = Password::sendResetLink($request->only('Company_Email'), function (Message $message) {
            $message->subject($this->getEmailSubject());
        });

				$input = $request->all();

				$credential=array();
				$credential["Personal_Email"]=$input["Company_Email"];

				if($response==Password::INVALID_USER)
				{

					$response = Password::sendResetLink($credential, function (Message $message) {
							 $message->subject($this->getEmailSubject());
					 });

				}
			//  $check = DB::table('users')
		 	// 		->where('users.Company_Email', '=',$request->input('email'))
		 	// 		->first();
			//
			// dd($check);

			 switch ($response) {
					 case Password::RESET_LINK_SENT:
							 return redirect()->back()->with('status', trans($response));
					 case Password::INVALID_USER:
 				 				return redirect()->back()->withErrors(['Company_Email' => trans($response)]);
			 }

	 }
	 protected function getEmailSubject()
	 {
			 return property_exists($this, 'subject') ? $this->subject : 'Your Password Reset Link';
	 }

	 /**
		* Display the password reset view for the given token.
		*
		* @param  string  $token
		* @return \Illuminate\Http\Response
		*/
	 public function getReset($token = null)
	 {
			 if (is_null($token)) {
					 throw new NotFoundHttpException;
			 }

			 return view('auth.reset')->with('token', $token);
	 }

	 /**
		* Reset the given user's password.
		*
		* @param  \Illuminate\Http\Request  $request
		* @return \Illuminate\Http\Response
		*/
	 public function postReset(Request $request)
	 {
			 $this->validate($request, [
					 'token' => 'required',
					 'Company_Email' => 'required|email',
					 'password' => 'required|confirmed|min:6',
			 ]);

			 $credentials = $request->only(
					 'Company_Email', 'password', 'password_confirmation', 'token'
			 );

			 $response = Password::reset($credentials, function ($user, $password) {
					 $this->resetPassword($user, $password);
			 });

			 switch ($response) {
					 case Password::PASSWORD_RESET:
							 return redirect($this->redirectPath())->with('status', trans($response));
					 default:
							 return redirect()->back()
													 ->withInput($request->only('Company_Email'))
													 ->withErrors(['Company_Email' => trans($response)]);
			 }
	 }

	 /**
		* Reset the given user's password.
		*
		* @param  \Illuminate\Contracts\Auth\CanResetPassword  $user
		* @param  string  $password
		* @return void
		*/
	 protected function resetPassword($user, $password)
	 {
			 $user->password = bcrypt($password);

			 $user->save();

			 Auth::login($user);
	 }

	 public function getEmailForPasswordReset()
{
    return $this->Company_Email;
}



}
