<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\URL;

class ContractorVendorController extends Controller {

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

		$users = DB::table('users')->select('users.Id','files.Web_Path','users.StaffId','users.Name','users.User_Type','users.Company_Email','users.Personal_Email','users.Contact_No_1','users.Contact_No_2','users.Permanent_Address','users.Current_Address','users.Home_Base','users.Nationality','users.DOB','users.NRIC','users.Passport_No','users.Gender','users.Marital_Status','superior.Name as Superior','users.Position','users.Emergency_Contact_Person','users.Emergency_Contact_No','users.Emergency_Contact_Relationship','users.Emergency_Contact_Address')
		->leftJoin( DB::raw('(select Max(Id) as maxid,TargetId from files where Type="User" Group By Type,TargetId) as max'), 'max.TargetId', '=', 'users.Id')
		->leftJoin('files', 'files.Id', '=', DB::raw('max.`maxid` and files.`Type`="User"'))
		->leftJoin('users as superior','superior.Id','=','users.SuperiorId')
		->where('users.User_Type','=','Contractor')
		->orderBy('users.Id')
		->get();

		$options= DB::table('options')
		->whereIn('Table', ["users"])
		->orderBy('Table','asc')
		->orderBy('Option','asc')
		->get();

    return view('contractorvendor', ['me' => $me,'contractorvendors' =>$contractorvendors]);

//		return view('Staff');
	}

	public function contractorvendordetail($Id)
	{

		$auth = Auth::user();

		$me = DB::table('users')
		->leftJoin( DB::raw('(select Max(Id) as maxid,TargetId from files where Type="User" Group By TargetId) as max'), 'max.TargetId', '=', 'users.Id')
		->leftJoin('files', 'files.Id', '=', DB::raw('max.`maxid` and files.`Type`="User"'))
		// ->leftJoin('accesscontrols', 'accesscontrols.UserId', '=', 'users.Id')
		->where('users.Id', '=',$auth -> Id)
		->first();

		if ($me -> Web_Path=="")
		{
				$me -> Web_Path = URL::to('/') ."/img/default-user.png" ;
		}
            //$users = DB::table('users')->select('Id','Name','Email','Contact No','Address','DOB','IC No','Gender','Marital Status','Position','Emergency Contact Person','Emergency Contact No','Emergency Contact Relationship',
						//'Emergency Contact Address')
						$contractorvendor = DB::table('users')->select('users.Id','Name','Email','Contact_No','files.Web_Path')
						->leftJoin('files', 'files.TargetId', '=', DB::raw('users.`Id` and files.`Type`="User"'))
						->where('users.Id', '=', $Id)
						->first();

						$experiences = DB::table('experiences')->select('experiences.Id','Project','Role','Responsibility','Achievement','Start_Date','End_Date','files.Web_Path')
						->leftJoin( DB::raw('(select Max(Id) as maxid,TargetId from files where Type="Experience" Group By TargetId) as max'), 'max.TargetId', '=', 'experiences.Id')
						->leftJoin('files', 'files.Id', '=', DB::raw('max.`maxid` and files.`Type`="Experience"'))
						->where('UserId', '=', $Id)
						->orderBy('experiences.Id','desc')
						->get();

						$licenses = DB::table('licenses')->select('licenses.Id','License_Type','Identity_No','Issue_Date','Expiry_Date','License_Status','Start_Date','End_Date','files.Web_Path')
						->leftJoin( DB::raw('(select Max(Id) as maxid,TargetId from files where Type="License" Group By TargetId) as max'), 'max.TargetId', '=', 'licenses.Id')
						->leftJoin('files', 'files.Id', '=', DB::raw('max.`maxid` and files.`Type`="License"'))
						->where('UserId', '=', $Id)
						->orderBy('licenses.Id','desc')
						->get();

						$references = DB::table('references')->select('references.Id','Reference','Contact_No','Company','Position','Relationship','files.Web_Path')
						->leftJoin( DB::raw('(select Max(Id) as maxid,TargetId from files where Type="Reference" Group By TargetId) as max'), 'max.TargetId', '=', 'references.Id')
						->leftJoin('files', 'files.Id', '=', DB::raw('max.`maxid` and files.`Type`="Reference"'))
						->where('UserId', '=', $Id)
						->orderBy('references.Id','desc')
						->get();

						$skills = DB::table('skills')->select('skills.Id','Skill','Level','Description','files.Web_Path')
						->leftJoin( DB::raw('(select Max(Id) as maxid,TargetId from files where Type="Skill" Group By TargetId) as max'), 'max.TargetId', '=', 'skills.Id')
						->leftJoin('files', 'files.Id', '=', DB::raw('max.`maxid` and files.`Type`="Skill"'))
						->where('UserId', '=', $Id)
						->orderBy('skills.Id','desc')
						->get();

            return view('contractorvendordetail', ['UserId' => $Id , 'me' => $me, 'contractorvendor' =>$contractorvendor, 'experiences' => $experiences, 'licenses' => $licenses, 'references' => $references, 'skills' => $skills]);

//		return view('Staff');
	}

}
