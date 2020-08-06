<?php namespace App\Http\Controllers\Api;

use JWTAuth;
use Tymon\JWTAuth\Exceptions\JWTException;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\Facades\Hash;
use File;
use Dompdf\Dompdf;
use Storage;
use Input;
use DateTime;
use PDO;
use Zipper;
use Carbon\Carbon;

class LoanController extends Controller {

	public function getallloan(Request $request){
		$me=JWTAuth::parseToken()->authenticate();

		$allloan=DB::table('staffloans')
		->select('staffloans.Id','staffloans.UserId','staffloans.Purpose','staffloans.Date','staffloans.Total_Requested','staffloanstatuses.Status','users.Department','staffloans.Total_Approved')
		->leftJoin('staffloanstatuses','staffloans.Id','=','staffloanstatuses.StaffLoanId')
		->leftJoin('users','staffloans.UserId','=','users.Id')
		->where('staffloans.UserId',$me->Id)
        ->orderBy(DB::raw('str_to_date(staffloans.Date,"%d-%M-%Y")'),'desc')

		->get();

		// dd($me);

		return json_encode($allloan);
	}

	public function submitloan(Request $request){
		$me=JWTAuth::parseToken()->authenticate();
        $input=$request->all();
		$today=date("d-M-Y");

		$thisyear=date("Y");

		$allloan=DB::table('staffloans')
		->select('staffloans.Id','staffloans.UserId','staffloans.Purpose','staffloans.Date','staffloans.Total_Requested','staffloanstatuses.Status','users.Department','staffloans.Total_Approved')
		->leftJoin('staffloanstatuses','staffloans.Id','=','staffloanstatuses.StaffLoanId')
		->leftJoin('users','staffloans.UserId','=','users.Id')
		->where('staffloans.UserId',$me->Id)
		->where('staffloans.Date','like','%'.$thisyear.'%')
		->where('staffloanstatuses.Status','not like','%Rejected%')
		->get();

		if(sizeof($allloan)>=3)
		{
			//cannot proceed
			return "Exceeded maximum loan application per year!";
		}
		else {
			// code...
			// dd($request['ProjectId']);
			$loan=DB::table('staffloans')
			->insertGetId(
				[
					'Type' =>'Personal',
					'UserId' => $me->Id,
					'ProjectId' => $input['ProjectId'],
					'Date' => $today,
					'Total_Requested' => $input['Amount'],
					'Bank_Account_No' => $input['Bank'],
					'Purpose' => $input['Reason'],
					'Repayment' => $input['Repayment']
				]
			);

			// $aa=DB::table('staffloans')
			// ->select('Id')
			// ->where('')
			// dd($loan);

			$loan2 = DB::table('staffloanstatuses')
			->insert([
				'UserId'=>$me->Id,
				'StaffLoanId'=>$loan,
				'Status'=>'Pending Approval'
			]);
			return 1;
		}
	}

    public function getbank()
    {
        $me = JWTAuth::parseToken()->authenticate();

        $user = DB::table('users')
        ->select('Id','Bank_Name','Bank_Account_No')
        ->where('Id','=',$me->Id)
        ->get();

        return json_encode($user);
    }

	public function getsitedepartment()
	{

    	$auth = JWTAuth::parseToken()->authenticate();
		$me = (new AuthController)->get_current_user($auth->Id);

	    if($me->Department)
	    {
	  		$projects = DB::table('projects')
			->orderBy('Id','Asc')
			// ->select('Id','projects.Customer')
	      	// ->where('projects.Project_Name', '=',$me->Department)
	      	->where('projects.Customer', 'NOT LIKE','%Department%')

			->get();
	    }

	    else
	    {
	  		$projects = DB::table('projects')
			->orderBy('Id','Asc')
			->select('Id','Project_Name')
	      	->where('projects.Project_Name', 'not like','%Department%')
			->get();
	    }

		return json_encode($projects);
	}

	public function getsiteprojectcodes($id = null)
	{

		$me = JWTAuth::parseToken()->authenticate();

		if ($id) {
		$track = DB::table('tracker')
		->orderBy('Id','Asc')
		->select('Id','Site_Name','Site_Id','Project_Code','Unique ID','Site_Name as Site_Name_Original')
		// ->where('tracker.Project_Code', '!=', '')
		->where(function($q) {
			$q->where('tracker.Project_Code', '!=', '');
			$q->orWhere('tracker.Site_Name', '!=', '');

		})
		->where('tracker.ProjectId', $id)
		// ->groupBy('tracker.Site_Name')
		->get();

		} else {

			$track = DB::table('tracker')
			->orderBy('Id','Asc')
			->select('Id','Site_Name','Site_Id','Project_Code','Site_Name as Site_Name_Original','Unique ID')
			->where('tracker.Site_Name', '!=', '')
			// ->where('tracker.ProjectId', $id)
			// ->groupBy('tracker.Site_Name')
			->get();


		}

		return json_encode($track);
	}

	public function getewalletoptions()
	{

		$me = JWTAuth::parseToken()->authenticate();

		$options= DB::table('options')
		->whereIn('Table', ["users","ewallet"])
		->orderBy('Table','asc')
		->orderBy('Option','asc')
		->get();

		return json_encode($options);
	}

}
