<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\URL;
use App\Http\Controllers\CommonController;

class ReportController extends Controller {

  public function __construct()
	{
		$this->middleware('auth');
	}

	/**
	 * Show the application dashboard to the user.
	 *
	 * @return Response
	 */
	public function Index($type = null)
	{
      $me = (new CommonController)->get_current_user();

      $options = DB::table('documenttypeaccess')
      ->leftJoin('options','documenttypeaccess.Document_Type','=','options.Option')
      ->select('options.Id','options.Table','options.Field','options.Option')
      ->where('options.Field', '=','Document_Type')
      ->where('AccessControlTemplateId','=', $me->AccessControlTemplateId)
      ->where('documenttypeaccess.Read','=', 1)
      ->groupBY('documenttypeaccess.Document_Type')
      ->orderBy('options.Option', 'asc')
      ->get();



      // $documentaccess = DB::table('documenttypeaccess')
      // ->where('AccessControlTemplateId','=', $me->AccessControlTemplateId)
      // ->where('Document_type','=', $option)
      // ->orderBy('documenttypeaccess.Document_Type','asc')
      // ->first();

      if($type==null)
      {
        if($options)
        {
          $type=$options[0]->Option;
        }


      }

      $reports="";

      $reports = DB::table('tracker')
      ->select('tracker.Id','files.File_Name','files.Web_Path','tracker.Unique ID',
      DB::raw("CASE
        WHEN Site_ID='' THEN `Site LRD`
      ELSE
        Site_ID
      END as 'Site_ID'"),
      'tracker.Site_Name',DB::raw('submitter.Name as Submitter'),DB::raw('files.created_at as Submitted_Date'))
      ->leftJoin('files', 'tracker.Id', '=',  DB::raw('files.TargetId AND files.Type="Tracker" AND files.Document_Type="'.$type.'"'))
      ->leftJoin('users as submitter', 'files.UserId', '=', 'submitter.Id')
      ->whereNotNull('files.Id')
      ->groupBy('files.Id')
      ->orderBy('tracker.Site_Name', 'asc')
      ->get();

      return view('report', ['me' => $me, 'type'=>$type,'options' => $options,'reports'=>$reports]);

	}


}
