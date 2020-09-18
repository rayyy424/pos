<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\URL;

class OrgChartController extends Controller {

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
            //$users = DB::table('users')->select('Id','Name','Email','Contact No','Address','DOB','IC No','Gender','Marital Status','Position','Emergency Contact Person','Emergency Contact No','Emergency Contact Relationship',
						//'Emergency Contact Address')
						$orgchart = DB::table('users')->select('Id','Name','Position','SuperiorId')
						->orderBy('Id')
						->get();

						//$column = $users[0];

            return view('orgchart', ['me' => $me, 'orgchart' => json_encode($orgchart), 'staffs' => $orgchart]);

	}

  public function update(Request $request)
	{

      $orgchart=$request->orgchart;
      $array = json_decode( $orgchart, true );

      foreach($array as $staff){

        if ($staff["SuperiorId"]!="undefined")
        {
            DB::table('users')
                ->where('Id', $staff["Id"])
                ->update(['SuperiorId' => $staff["SuperiorId"]]);
        }


      }

      // echo $orgchart;
      return $orgchart;

	}

}
