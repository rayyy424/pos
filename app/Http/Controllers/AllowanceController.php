<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\URL;
use App\Http\Controllers\CommonController;

class AllowanceController extends Controller {

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

		$accounts = DB::table('users')
		->leftJoin('allowanceschemes', 'users.AllowanceSchemeId', '=', 'allowanceschemes.Id')
		->select('users.Id','users.Name','users.Department','users.Position','allowanceschemes.Scheme_Name')
		->orderBy('users.Name','asc')
		->get();

    $schemes = DB::table('allowanceschemes')
    ->orderBy('allowanceschemes.Scheme_Name','asc')
    ->get();

    $schemeitems = DB::table('allowanceschemeitems')
    ->select('allowanceschemeitems.Id','allowanceschemeitems.AllowanceSchemeId','allowanceschemeitems.Day_Type','allowanceschemeitems.Start','allowanceschemeitems.End','allowanceschemeitems.Minimum_Hour','allowanceschemeitems.Currency','allowanceschemeitems.Home_Base','allowanceschemeitems.Outstation','allowanceschemeitems.Subsequent_Home_Base','allowanceschemeitems.Subsequent_Outstation','allowanceschemeitems.Remarks')
    ->orderBy('allowanceschemeitems.Day_Type','asc')
		->orderBy(db::raw('str_to_date(allowanceschemeitems.Start,"%h:%i:%s %p")'),'asc')
    ->get();

    return view('allowancescheme', ['me' => $me,'accounts' => $accounts,'schemes' =>$schemes,'schemeitems' =>$schemeitems]);

	}

	public function allowanceschemeitem($Id)
	{
		$me = (new CommonController)->get_current_user();

		$template = DB::table('allowanceschemes')
		->get();

		return view('allowanceschemeitem', ['me' => $me,'access' => $access, 'templates' => $template]);

	}

	public function removescheme(Request $request)
	{

			$input = $request->all();

			return DB::table('allowanceschemes')->where('Id', '=', $input["Id"])->delete();

	}

  public function createnewscheme(Request $request)
	{

			$input = $request->all();

			$id = DB::table('allowanceschemes')->insertGetId(array(
						'Created_By' =>  $input["Created_By"],
						'Scheme_Name' =>  $input["Scheme_Name"]
					));

		 return $id;


	}

}
