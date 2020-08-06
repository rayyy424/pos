<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\URL;
use App\Http\Controllers\CommonController;
use Illuminate\Support\Facades\Mail;


class DeliveryTracking extends Controller {

	public function deliverytracking(Request $request, $type = 'Requesting Lorry')
	{
		$me = (new CommonController)->get_current_user();

		if($request->has('donumber')) {
			$donumber = $request->get('donumber');
		} else {
			$donumber = null;
		}

		if ($donumber) {
		if ($type == 'Requesting Lorry') {
			$deliverytracking = DB::table('deliverytracking')
		->select('deliverytracking.Id','deliverytracking.Date','deliverytracking.Time','deliverytracking.Location','deliverytracking.Activity')
		->leftJoin( DB::raw('(select Max(Id) as maxid,TargetId from files where Type="DeliveryTracking" Group By TargetId) as max'), 'max.TargetId', '=', 'deliverytracking.Id')
		->leftJoin('files', 'files.Id', '=', DB::raw('max.`maxid` and files.`Type`="DeliveryTracking"'))
		->get();
		} else {
			$deliverytracking = DB::table('deliverytracking')
		->select('deliverytracking.Id','deliverytracking.Date','deliverytracking.Time','deliverytracking.Location','deliverytracking.Activity')
		->leftJoin( DB::raw('(select Max(Id) as maxid,TargetId from files where Type="DeliveryTracking" Group By TargetId) as max'), 'max.TargetId', '=', 'deliverytracking.Id')
		->leftJoin('files', 'files.Id', '=', DB::raw('max.`maxid` and files.`Type`="DeliveryTracking"'))
		->where('Activity', $type)
		->get();
		}
		} else {
			$deliverytracking = [];
		}
		

		// $deliverytracking = [];
		$category=DB::table('options')
			->distinct('options.Option')
			->select('options.Option')
			->where('options.Table', '=','assets')
			->where('options.Field', '=','DeliveryTracking')
			->orderBy('options.Option')
			->get();

		return view('deliverytracking',['me'=>$me,'deliverytracking'=>$deliverytracking,'type'=>$type,'category'=>$category, 'donumber'=>$donumber ]);
	}

	public function deliverytrackingdetails(Request $request)
	{
		$me = (new CommonController)->get_current_user();

		// if($request->has('donumber')) {
		// 	$donumber = $request->get('donumber');
		// } else {
		// 	$donumber = null;
		// }

		// if ($donumber) {
		// if ($type == 'Requesting Lorry') {
			$deliverytracking = DB::table('deliverytracking')
		->select('deliverytracking.Id','deliverytracking.Date','deliverytracking.Time','deliverytracking.Location','deliverytracking.Activity')
		->orderBy('deliverytracking.Date', 'desc')
		// ->leftJoin( DB::raw('(select Max(Id) as maxid,TargetId from files where Type="DeliveryTracking" Group By TargetId) as max'), 'max.TargetId', '=', 'deliverytracking.Id')
		// ->leftJoin('files', 'files.Id', '=', DB::raw('max.`maxid` and files.`Type`="DeliveryTracking"'))
		->get();
		// } else {
		// 	$deliverytracking = DB::table('deliverytracking')
		// ->select('deliverytracking.Id','deliverytracking.Date','deliverytracking.Time','deliverytracking.Location','deliverytracking.Activity')
		// ->leftJoin( DB::raw('(select Max(Id) as maxid,TargetId from files where Type="DeliveryTracking" Group By TargetId) as max'), 'max.TargetId', '=', 'deliverytracking.Id')
		// ->leftJoin('files', 'files.Id', '=', DB::raw('max.`maxid` and files.`Type`="DeliveryTracking"'))
		// ->where('Activity', $type)
		// ->get();
		// }
		// } else {
		// 	$deliverytracking = [];
		// }
		
		$deliverytrackingstatus = DB::table('deliverytracking')
		->select('deliverytracking.Id','deliverytracking.Date','deliverytracking.Time','deliverytracking.Location','deliverytracking.Activity')
		->join(DB::raw("(SELECT MAX(Id) as maxid From deliverytracking) as max"),'max.maxid','=', 'deliverytracking.Id')
		->first();

		// $deliverytracking = [];
		$category=DB::table('options')
			->distinct('options.Option')
			->select('options.Option')
			->where('options.Table', '=','assets')
			->where('options.Field', '=','DeliveryTracking')
			->orderBy('options.Option')
			->get();

		return view('deliverytrackingdetails',['me'=>$me,'deliverytracking'=>$deliverytracking,'category'=>$category, 'deliverytrackingstatus'=> $deliverytrackingstatus]);
	}
}