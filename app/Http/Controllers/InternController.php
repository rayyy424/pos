<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\Facades\Mail;

class InternController extends Controller {

    	public function __construct()
	{
		$this->middleware('auth');
	}

	/**
	 * Show the application dashboard to the user.
	 *
	 * @return Response
	 */
	public function index()
	{
		$me = (new CommonController)->get_current_user();
		return view('Intern' , ['me' => $me]);
	}

	public function intern($start = null,$end = null)
	{
		$me = (new CommonController)->get_current_user();


		if ($start==null)
		{

			$start=date('d-M-Y', strtotime('first day of this month'));
		}

		if ($end==null)
		{
			$end=date('d-M-Y', strtotime($end . "last day of this month"));
		}

		$startTime = strtotime($start);
		$endTime = strtotime($end);
		$query="";

		$startTime=strtotime("+1 days",$startTime);

		 while ($startTime <= $endTime){

		 	$query.="SELECT '" . date('d-M-Y', $startTime) . "' UNION ALL ";

			$startTime=strtotime("+1 days",$startTime);
		 }

		$query=substr($query,0,strlen($query)-10);

		$approved = DB::select("
				SELECT MonthDate.Date, count(*) as Total, SUM(case when intern.Internship_Status = 'Accepted' then 1 else 0 end) as Accepted,SUM(case when intern.Internship_Status = 'Pending' then 1 else 0 end) as Pending
				FROM (SELECT '".$start."' AS Date UNION ALL
					".$query.") AS MonthDate
				INNER JOIN users AS intern
				ON (str_to_date(MonthDate.Date,'%d-%M-%Y') BETWEEN str_to_date(intern.Internship_Start_Date,'%d-%M-%Y') AND str_to_date(intern.Internship_End_Date,'%d-%M-%Y'))
				WHERE intern.User_Type = 'Assistant Engineer'
				GROUP BY MonthDate.Date
		");

		if ($approved==null){
			$data1 = "";
      $data2 = "";
      $data3 = "";
			$title = "";
		}

		else {

			foreach($approved as $key => $quote){
				$a[]=$quote->Total;
				$data1= implode(',', $a);
			}
      foreach($approved as $key => $quote){
				$b[]=$quote->Accepted;
				$data2= implode(',', $b);
			}
      foreach($approved as $key => $quote){
				$c[]=$quote->Pending;
				$data3= implode(',', $c);
			}

		}

		foreach($approved as $key => $quote){
			$r[]=$quote->Date;
			$title = implode(',', $r);
		}

    //dd($approved);

	 return view('intern', ['me' => $me, 'start' => $start,'end' =>$end, 'approved' => $approved, 'data1' => $data1, 'data2' => $data2, 'data3' => $data3, 'title' => $title]);

	}

	public function internlist(Request $request)
	{
		$input = $request->all();

		if ($input["Type"]=="Total")
		{
  		$internlist = DB::table('users')
      ->select('users.Name as name','qualifications.Institution','qualifications.Major','qualifications.Qualification_Level','users.Internship_Start_Date','users.Internship_End_Date')
			->leftJoin('qualifications','qualifications.UserId' ,'=', 'users.Id')
  		->where('User_Type', '=', 'Assistant Engineer')
  		->where('Internship_Status', '=', 'Accepted')
  		->where(DB::raw('str_to_date(users.Internship_Start_Date,"%d-%M-%Y")'),"<=",DB::raw('str_to_date("'.$input["Date"].'","%d-%M-%Y")'))
  		->where(DB::raw('str_to_date(users.Internship_End_Date,"%d-%M-%Y")'),">=",DB::raw('str_to_date("'.$input["Date"].'","%d-%M-%Y")'))
  		->get();

		}
		elseif ($input["Type"]=="Pending")
		{

	    $internlist = DB::table('users')
			->select('users.Name as name','qualifications.Institution','qualifications.Major','qualifications.Qualification_Level','users.Internship_Start_Date','users.Internship_End_Date')
			->leftJoin('qualifications','qualifications.UserId' ,'=', 'users.Id')
			->where('User_Type', '=', 'Assistant Engineer')
			->where('Internship_Status', '=', 'Pending')
			->where(DB::raw('str_to_date(users.Internship_Start_Date,"%d-%M-%Y")'),"<=",DB::raw('str_to_date("'.$input["Date"].'","%d-%M-%Y")'))
			->where(DB::raw('str_to_date(users.Internship_End_Date,"%d-%M-%Y")'),">=",DB::raw('str_to_date("'.$input["Date"].'","%d-%M-%Y")'))

			->get();
		}
    elseif ($input["Type"]=="Accepted")
    {
      $internlist = DB::table('users')
			->select('users.Name as name','qualifications.Institution','qualifications.Major','qualifications.Qualification_Level','users.Internship_Start_Date','users.Internship_End_Date')
			->leftJoin('qualifications','qualifications.UserId' ,'=', 'users.Id')
			->where('User_Type', '=', 'Assistant Engineer')
      ->where('Internship_Status', '=', 'Accepted')
			->where(DB::raw('str_to_date(users.Internship_Start_Date,"%d-%M-%Y")'),"<=",DB::raw('str_to_date("'.$input["Date"].'","%d-%M-%Y")'))
			->where(DB::raw('str_to_date(users.Internship_End_Date,"%d-%M-%Y")'),">=",DB::raw('str_to_date("'.$input["Date"].'","%d-%M-%Y")'))

			->get();
    }

		return json_encode($internlist);

	}


}
