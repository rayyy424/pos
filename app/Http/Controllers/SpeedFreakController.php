<?php namespace App\Http\Controllers;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\Facades\Mail;
use App\Http\Controllers\CommonController;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Schema;
use Illuminate\Http\Request;
use DateTime;
use Carbon\Carbon;
use Excel;
use Response;

use Dompdf\Dompdf;

class SpeedFreakController extends Controller {

	/*
	|--------------------------------------------------------------------------
	| Home Controller
	|--------------------------------------------------------------------------
	|
	| This controller renders your application's "dashboard" for users that
	| are authenticated. Of course, you are free to change or remove the
	| controller as you wish. It is just here to get your app started!
	|
	*/

	/**
	 * Create a new controller instance.
	 *
	 * @return void
	 */
	public function __construct()
	{
		$this->middleware('auth');
	}

	/**
	 * Show the application dashboard to the user.
	 *
	 * @return Response
	 */
	public function index($start = null, $end = null)
	{
		$me = (new CommonController)->get_current_user();
		$today = date('d-M-Y', strtotime('today'));
		$year = Date('Y');
		if($start == null)
		{
			$start = date('d-M-Y',strtotime('first day of this month'));

		}
		if($end == null)
		{
			$end = date('d-M-Y',strtotime('last day of this month'));
		}

		$inventorycost=DB::table('speedfreakinventory')
			// ->leftJoin('users','users.Id','=','speedfreakinventory.technicianId')
			->leftJoin('companies','companies.Id','=','speedfreakinventory.supplier')
			->leftJoin(DB::Raw('(SELECT Max(Id) as maxid, inventoryId from inventorypricehistory group by inventoryId) as max'),'max.inventoryId','=','speedfreakinventory.Id')
			->leftJoin('inventorypricehistory','inventorypricehistory.Id','=',DB::raw('max.maxid'))
			// ->select('speedfreakinventory.Id', 'speedfreakinventory.name', 'speedfreakinventory.type','speedfreakinventory.barcode','speedfreakinventory.model','inventorypricehistory.price','companies.Company_Name','speedfreakinventory.qty_balance','speedfreakinventory.status')
			->select(DB::raw('SUM(speedfreakinventory.qty_balance * inventorypricehistory.price) as Total'))
			// ->whereRaw("speedfreakinventory_history.`created_at`>=str_to_date('".$start."','%d-%M-%Y') AND speedfreakinventory_history.`created_at`<=str_to_date('".$end."','%d-%M-%Y')")
			->first();

		$utilitybill = DB::table('utility')
				  ->select(DB::raw('SUM(utility.Total_Amount) as Total'))
				  ->where('Date_Paid','=',"")
				  ->whereRaw('(str_to_date(Bill_Date,"%d-%M-%Y")>=str_to_date("'.$start.'","%d-%M-%Y") AND str_to_date(Bill_Date,"%d-%M-%Y")<=str_to_date("'.$end.'","%d-%M-%Y"))')
				  ->first();

		$paidutilitybill = DB::table('utility')
				  ->select(DB::raw('SUM(utility.Paid_Amount) as Total'))
				  ->where('Date_Paid','!=',"")
				  ->whereRaw('(str_to_date(Bill_Date,"%d-%M-%Y")>=str_to_date("'.$start.'","%d-%M-%Y") AND str_to_date(Bill_Date,"%d-%M-%Y")<=str_to_date("'.$end.'","%d-%M-%Y"))')
				  ->first();

		$loanpaid = DB::table('companyloaninstallments')
				  ->select(DB::raw('SUM(companyloaninstallments.Amount) as loanpaid'))
				  ->where('companyloaninstallments.Paid','=',1)
				  ->first();

		$loanexpenses = DB::table('companyloaninstallments')
				  ->select(DB::raw('SUM(companyloaninstallments.Amount) as loanexpenses'))
				  ->where('companyloaninstallments.Paid','=',1)
				  ->whereRaw('(str_to_date(created_at,"%d-%M-%Y")>=str_to_date("'.$start.'","%d-%M-%Y") AND str_to_date(created_at,"%d-%M-%Y")<=str_to_date("'.$end.'","%d-%M-%Y"))')
				  ->first();

		$salary = DB::table('salary')
				  ->select(DB::raw('SUM(salary.Salary) as Total'))
				  ->first();

		$sales = DB::table('speedfreakinventory_history')
			->leftJoin(DB::Raw('(SELECT Max(Id) as maxid, inventoryId from inventorysalesprice group by inventoryId) as max'),'max.inventoryId','=','speedfreakinventory_history.speedfreakInventoryId')
			->leftJoin('inventorysalesprice','inventorysalesprice.Id','=',DB::raw('max.maxid'))
			->select(DB::raw('SUM(speedfreakinventory_history.qty * inventorysalesprice.price) as Total'))
			->where('speedfreakinventory_history.type','like','%Stock-Out%')
			->whereRaw("speedfreakinventory_history.`created_at`>=str_to_date('".$start."','%d-%M-%Y') AND speedfreakinventory_history.`created_at`<=str_to_date('".$end."','%d-%M-%Y')")
			->first();

		$costprice = DB::table('speedfreakinventory_history')
			->leftJoin(DB::Raw('(SELECT Max(Id) as maxid, inventoryId from inventorypricehistory group by inventoryId) as max'),'max.inventoryId','=','speedfreakinventory_history.speedfreakInventoryId')
			->leftJoin('inventorypricehistory','inventorypricehistory.Id','=',DB::raw('max.maxid'))
			->select(DB::raw('SUM(speedfreakinventory_history.qty * inventorypricehistory.price) as Total'))
			->where('speedfreakinventory_history.type','like','%Stock-Out%')
			->whereRaw("speedfreakinventory_history.`created_at`>=str_to_date('".$start."','%d-%M-%Y') AND speedfreakinventory_history.`created_at`<=str_to_date('".$end."','%d-%M-%Y')")
			->first();

		$stockexpenses = DB::table('speedfreakinventory_history')
			->leftJoin(DB::Raw('(SELECT Max(Id) as maxid, inventoryId from inventorypricehistory group by inventoryId) as max'),'max.inventoryId','=','speedfreakinventory_history.speedfreakInventoryId')
			->leftJoin('inventorypricehistory','inventorypricehistory.Id','=',DB::raw('max.maxid'))
			->select(DB::raw('SUM(speedfreakinventory_history.qty * inventorypricehistory.price) as Total'))
			->where('speedfreakinventory_history.type','like','%Stock-In%')
			->whereRaw("speedfreakinventory_history.`created_at`>=str_to_date('".$start."','%d-%M-%Y') AND speedfreakinventory_history.`created_at`<=str_to_date('".$end."','%d-%M-%Y')")
			->first();

		$profit = $sales->Total - $costprice->Total;
		$profitfinal = number_format((float)$profit, 2, '.', '');

		$totalsales = DB::table('speedfreakinventory_history')
			->leftJoin(DB::Raw('(SELECT Max(Id) as maxid, inventoryId from inventorysalesprice group by inventoryId) as max'),'max.inventoryId','=','speedfreakinventory_history.speedfreakInventoryId')
			->leftJoin('inventorysalesprice','inventorysalesprice.Id','=',DB::raw('max.maxid'))
			->select(DB::raw('SUM(speedfreakinventory_history.qty * inventorysalesprice.price) as Total'))
			->where('speedfreakinventory_history.type','like','%Stock-Out%')
			// ->whereRaw("speedfreakinventory_history.`created_at`>=str_to_date('".$start."','%d-%M-%Y') AND speedfreakinventory_history.`created_at`<=str_to_date('".$end."','%d-%M-%Y')")
			->first();

		$averagesales = $totalsales->Total / 12;
		$averagesalesfinal = number_format((float)$averagesales, 2, '.', '');


		$expenses = $paidutilitybill->Total + $loanexpenses->loanexpenses + $salary->Total + $stockexpenses->Total;
		$expenses = number_format((float)$expenses, 2, '.', '');

		$treshold = DB::select('select count(*) as count,branch from (select `speedfreakinventory_history`.`branch`, `speedfreakinventory`.`balance_treshold`, `speedfreakinventory`.`type`, COUNT(speedfreakinventory.Id) as count from `speedfreakinventory_history` left join `speedfreakinventory` on `speedfreakinventory`.`Id` = `speedfreakinventory_history`.`speedfreakInventoryId` group by `speedfreakinventory_history`.`branch`, `speedfreakInventoryId` having SUM(speedfreakinventory_history.qty) <= speedfreakinventory.balance_treshold AND type NOT IN ("GENSET","TANK","ATS") AND balance_treshold > 0) b GROUP by b.branch');


		$invoice = DB::table('salesorder')
		->select(DB::raw('COUNT(invoice) as count'))
		->where('invoice','=','0')
		->where('rental_end','<',$today)
		->first();

		if($me->View_All_Branch)
		{
			$branches = DB::table('options')
			->select('Option')
			->whereRaw("options.Table = 'inventory' AND options.Field ='Branch'")
			->get();
		}
		else
		{
			$branchset = DB::table('holidayterritories')
			->select('Name')
			->where('Id','=',$me->HolidayTerritoryId)
			->first();

			$branches = DB::table('options')
			->select('Option')
			->whereRaw("options.Table = 'inventory' AND options.Field ='Branch'")
			->where('Option','LIKE',"%".$branchset->Name)
			->get();

			if(!$branches)
			{
				$branches = $branches = DB::table('options')
				->select('Option')
				->whereRaw("options.Table = 'inventory' AND options.Field ='Branch'")
				->where('Option','LIKE',"%HQ%")
				->get();
			}
		}

		return view('speedfreakdashboard', ['me'=>$me, 'start'=>$start , 'end'=>$end ,'today' => $today, 'year'=>$year , 'inventorycost'=>$inventorycost, 'utilitybill'=>$utilitybill, 'expenses'=>$expenses , 'loanpaid'=>$loanpaid , 'salary'=>$salary , 'sales'=>$sales, 'profitfinal'=>$profitfinal , 'averagesalesfinal'=>$averagesalesfinal ,'treshold'=> $treshold, 'invoice'=>$invoice, 'branches'=>$branches]);
	}

	

	public function technicianbag($id = null)
	{
		$me = (new CommonController)->get_current_user();
		if($id == null)
		{
			$list = DB::table('speedfreakinventory')
			->leftJoin('techbag','techbag.InvId','=','speedfreakinventory.Id')
			->select('speedfreakinventory.Id', 'speedfreakinventory.name', 'speedfreakinventory.type','speedfreakinventory.barcode','speedfreakinventory.model','speedfreakinventory.price','speedfreakinventory.price_yuan','speedfreakinventory.maxTechhold',DB::raw('SUM(techbag.Balance)as balance'))
			->groupby('speedfreakinventory.Id')
			->whereRAW('speedfreakinventory.type NOT IN ("GENSET","TANK","ATS","VEHICLE")')
			->get();
		}
		else
		{
			$list = DB::table('speedfreakinventory')
			->leftJoin('techbag','techbag.InvId','=','speedfreakinventory.Id')
			->select('speedfreakinventory.Id', 'speedfreakinventory.name', 'speedfreakinventory.type','speedfreakinventory.barcode','speedfreakinventory.model','speedfreakinventory.price','speedfreakinventory.price_yuan','speedfreakinventory.maxTechhold',DB::raw('SUM(techbag.Balance)as balance'))
			->groupby('speedfreakinventory.Id')
			->where('techbag.UserId','=',$id)
			->whereRAW('speedfreakinventory.type NOT IN ("GENSET","TANK","ATS","VEHICLE")')
			->get();
		}
		
		if(strpos($me->Position,'technician') !== false){
			$items=DB::Table('techbag')
			->select('techbag.Id','speedfreakinventory.name','speedfreakinventory.type','speedfreakinventory.barcode','speedfreakinventory.Model','speedfreakinventory.Price'
			,'speedfreakinventory.price_yuan','speedfreakinventory.maxTechhold','techbag.Balance')
			->join('speedfreakinventory','speedfreakinventory.Id','=','techbag.InvId')
			->where('techbag.UserId',$me->UserId)
			->get();
			 
			return view('technicianbag1',['me'=>$me,'items'=>$items]);
		}
		$status =DB::table('options')
		->distinct('options.Option')
		->select('options.Option')
		->where('options.Table', '=','inventory')
		->where('options.Field', '=','Status')
		->get();

		$supplier = DB::table('companies')
		->distinct('companies.Company_Name')
		->select('companies.Company_Name')
		->where('companies.Supplier', '=','Yes')
		->get();

		$type=DB::table('options')
		->distinct('options.Option')
		->select('options.Option')
		->where('options.Table', '=','inventory')
		->where('options.Field', '=','Type')
		->get();

		$tech = DB::table('users')
		->select('Id','Name')
		->where('Position','=','Technician')
		->where('Active',1)
		->get();

		return view('technicianbag', ['me'=>$me, 'list'=>$list, 'status'=>$status, 'supplier' => $supplier,'type'=>$type,'tech'=>$tech, 'id'=>$id]);
	}

	public function technicianbagdetails($id)
	{
		$me = (new CommonController)->get_current_user();

		$item = DB::table('speedfreakinventory')
		->select('speedfreakinventory.*')
		->where('speedfreakinventory.Id','=',$id)
		->first();

		// $history =DB::table('speedfreakinventory_history')
		// ->leftjoin('speedfreakinventory', 'speedfreakinventory.Id', '=', 'speedfreakinventory_history.speedfreakInventoryId')
		// ->select('speedfreakinventory_history.Id','speedfreakinventory.name','speedfreakinventory_history.qty')
		// ->where('speedfreakinventory_history.speedfreakInventoryId','=',$id)
		// ->get();

		$history = DB::table('techbag')
		->leftjoin('users','users.Id','=','techbag.UserId')
		->leftJoin('speedfreakinventory','speedfreakinventory.Id','=','techbag.InvId')
		->leftjoin('requisition','requisition.Id','=','techbag.ReqId')
		->leftjoin(DB::raw('(SELECT Max(Id) as maxid,requisition_Id from requisitionhistory group by requisition_Id) as max'),'max.requisition_Id','=','requisition.Id')
		->leftJoin('requisitionhistory', 'requisitionhistory.Id', '=', DB::raw('max.`maxid`'))
		->select('speedfreakinventory.name','users.Name as user','requisitionhistory.Qty','requisition.Req_No','requisition.Id')
		->where('techbag.InvId','=',$id)
		->get();
		if(!$history)
		{
			$history = array();
		}

		$photo = DB::table('files')
		->select('Web_Path')
		->orderBy('Id','DESC')
		->where('Type','=','SpeedFreakInventory')
		->where('TargetId','=',$id)
		->first();

		return view('technicianbagdetails', ['me'=>$me,'item'=>$item,'history'=>$history ,'photo'=>$photo]);
	}

	public function importinventory(Request $request)
	{
	    $this->validate($request, [
	        'importfile' => 'required|mimes:csv,txt'
	    ]);

	    $filename = $request->file('importfile')->getRealPath();
	    $delimiter = ',';

	    if (!file_exists($filename) || !is_readable($filename)) {
	        return false;
	    }

	    $header = null;
	    $dataArr = array();
	    if (($handle = fopen($filename, 'r')) !== false)
	    {
	        while (($row = fgetcsv($handle, 1000, $delimiter)) !== false)
	        {
	            if (!$header)
	                $header = $row;
	            else
	            	$dataArr[] = $row;
	        }
	        fclose($handle);
	    }

	    $categories = DB::table('options')->where('Table', 'inventory')->where('Field', 'Categories')->lists('Option');
	    $warehouses = DB::table('options')->where('Table', 'inventory')->where('Field', 'Warehouse')->lists('Option');
	    $units = DB::table('options')->where('Table', 'inventory')->where('Field', 'Unit')->lists('Option');

	    for ($i = 0; $i < count($dataArr); $i ++)
	    {
	    	$categoryExist = array_filter($categories, function ($category) use ($dataArr, $i) { 
	    		return $dataArr[$i][0] == $category;
	    	});

	    	if (empty($categoryExist)) {
	    		DB::table('options')->insert([
	    			'Table' => 'inventory',
	    			'Field' => 'Categories',
	    			'Option' => $dataArr[$i][0]
	    		]);

	    		array_push($categories, $dataArr[$i][0]);	    		
	    	}

	    	$warehouseExist = array_filter($warehouses, function ($warehouse) use ($dataArr, $i) { 
	    		return $dataArr[$i][5] == $warehouse;
	    	});

	    	if (empty($warehouseExist)) {
	    		DB::table('options')->insert([
	    			'Table' => 'inventory',
	    			'Field' => 'Warehouse',
	    			'Option' => $dataArr[$i][5]
	    		]);

	    		array_push($warehouses, $dataArr[$i][5]);	    		
	    	}

	    	$unitExist = array_filter($units, function ($unit) use ($dataArr, $i) { 
	    		return $dataArr[$i][3] == $unit;
	    	});

	    	if (empty($unitExist)) {
	    		DB::table('options')->insert([
	    			'Table' => 'inventory',
	    			'Field' => 'Unit',
	    			'Option' => $dataArr[$i][3]
	    		]);

	    		array_push($units, $dataArr[$i][3]);	    		
	    	}
	        DB::table('inventories')
	        ->insert([
	            'Categories' => $dataArr[$i][0],
	            'Item_Code' => $dataArr[$i][1],
	            'Description' => $dataArr[$i][2],
	            'Unit' => $dataArr[$i][3],
	            'Remark' => $dataArr[$i][4],
	            'Warehouse' => $dataArr[$i][5],
	        ]);
	    }

	    return redirect('speedfreakinventory')->with('success', 'Data successfully imported!');

	}

	public function requisitionmanagement($start = null , $end = null)
	{
		if($start == null)
		{
			$start = date('d-M-Y',strtotime('first day of last month'));
		}

		if($end == null)
		{
			$end = date('d-M-Y',strtotime('last day of this month'));
		}

		$me = (new CommonController)->get_current_user();
		$list=DB::table('requisition')
		->leftjoin(DB::raw('(SELECT Max(Id) as maxid,requisition_Id from requisitionhistory group by requisition_Id) as max'),'max.requisition_Id','=','requisition.Id')
		->leftJoin('requisitionhistory', 'requisitionhistory.Id', '=', DB::raw('max.`maxid`'))
		->Leftjoin('speedfreakservice','speedfreakservice.Id','=','requisition.speedfreakServiceId')
		->Leftjoin('serviceticket','serviceticket.Id','=','speedfreakservice.ServiceId')
		->Leftjoin('users','users.Id','=','serviceticket.technicianId')
		->Leftjoin('radius','radius.Id','=','serviceticket.site')
		->select('requisition.Id','requisition.Req_No','serviceticket.service_id','requisition.created_at','users.Name','requisitionhistory.status','radius.Location_Name')
		->whereRaw(' STR_TO_DATE(requisition.created_at,"%Y-%m-%d") BETWEEN STR_TO_DATE("'.$start.'","%d-%M-%Y") AND STR_TO_DATE("'.$end.'","%d-%M-%Y")')
		->get();

		return view('requisitionmanagement',['me'=>$me,'list'=>$list, 'start'=>$start, 'end'=>$end]);
	}

	public function requisitionmanagementdetails($id)
	{
		$me = (new CommonController)->get_current_user();

		$list = DB::table('requisition')
		->leftjoin(DB::raw('(SELECT Max(Id) as maxid,requisition_Id from requisitionhistory group by requisition_Id) as max'),'max.requisition_Id','=','requisition.Id')
		->leftjoin('requisitionhistory', 'requisitionhistory.Id', '=', DB::raw('max.`maxid`'))
		->Leftjoin('speedfreakservice','speedfreakservice.Id','=','requisition.speedfreakServiceId')
		->Leftjoin('serviceticket','serviceticket.Id','=','speedfreakservice.ServiceId')
		->Leftjoin('users','users.Id','=','serviceticket.technicianId')
		->select('users.Name','users.StaffId','users.Contact_No_1','users.Company_Email','requisitionhistory.status','requisition.Req_No','serviceticket.service_id')
		->where('requisition.Id','=',$id)
		->first();
		
		// $item = DB::table('requisitionhistory')
		// ->leftjoin('requisition','requisition.Id','=','requisitionhistory.requisition_Id')
		// ->leftjoin('requisitionitem','requisitionitem.historyId','=','requisitionhistory.Id')
		// ->leftjoin('speedfreakinventory','speedfreakinventory.Id','=','requisitionitem.InvId')
		// ->leftjoin('users','users.Id','=','requisitionhistory.user_Id')
		// ->select('speedfreakinventory.name','speedfreakinventory.barcode','speedfreakinventory.model','requisitionitem.Qty as requested','requisitionhistory.status','requisitionhistory.Qty','requisitionhistory.status_details','users.Name as user','requisitionhistory.created_at')
		// ->where('requisition.Id',$id)
		// ->orderBy('requisitionhistory.Id','ASC')
		// ->get();

		$item = DB::table('requisitionhistory')
		->leftjoin('requisition','requisition.Id','=','requisitionhistory.requisition_Id')
		->leftjoin('requisitionitem','requisitionitem.Id','=','requisitionhistory.reqItemId')
		->leftjoin('speedfreakinventory','speedfreakinventory.Id','=','requisitionitem.InvId')
		->leftjoin('users','users.Id','=','requisitionhistory.user_Id')
		->select('speedfreakinventory.name','speedfreakinventory.barcode','speedfreakinventory.model','requisitionitem.Qty as requested','requisitionhistory.status','requisitionhistory.Qty','requisitionhistory.status_details','users.Name as user','requisitionhistory.created_at')
		->where('requisition.Id',$id)
		->orderBy('requisitionhistory.Id','ASC')
		->get();


// dd($item);

		return view('requisitionmanagementdetails',['me'=>$me,'list'=>$list,'id'=>$id,'item'=>$item]);
	}

	public function deleterequisition($id)
	{
		$me = (new CommonController)->get_current_user();
		
		$before = DB::table('requisition')
		->where('Id',$id)
		->first();

		DB::Table('actionhistory')
	    ->insertGetId([
	    	'Type' => 'RQO',
	    	'action' => "Delete",
	    	'UserId' => $me->UserId,
	    	'created_at' => DB::raw('NOW()'),
	    	'datajson' => json_encode($before)
	    ]);

	    DB::table('requisition')
	    ->where('Id',$id)
	    ->delete();

		return 1;
	}
	
	public function requisitionform($technicians=null , $branches=null)
	{
		$me = (new CommonController)->get_current_user();
		$branch = DB::table('options')
		->select('Id','Option')
		->where('options.Table', '=','inventory')
		->where('options.Field', '=','Branch')
		->get();
		if(strpos($me->Position,'technician') !== false){
			if($branches == null){
				$items=[];
				$branches=0;
			}else{
				$items=DB::Table('speedfreakinventory')
				->select('history.Id','speedfreakinventory.name','history.qty','history.speedfreakInventoryId')
				->leftjoin(DB::raw('(SELECT Max(Id) as maxid,speedfreakInventoryId from speedfreakinventory_history group by speedfreakInventoryId) as max'),'max.speedfreakInventoryId','=','speedfreakinventory.Id')
				->leftjoin('speedfreakinventory_history as history','history.Id','=','max.maxid')
				->where('history.technicianId',$me->UserId)
				->where('history.type','Prepare')
				->where('history.branch',$branches)
				->get();
			}
			return view('technicianrequisitionform',['me'=>$me,'items'=>$items,'branch'=>$branch,'branches'=>$branches]);
		}
		if($technicians == null || $branches == null)
		{
			$list = array();
		}
		else
		{
			$list = DB::table('speedfreakinventory')
			->select('speedfreakinventory.Id','speedfreakinventory.name','speedfreakinventory.qty_balance')
			->where('speedfreakinventory.branch','LIKE',"%".$branches."%")
			->get();
		}	

		$technician = DB::table('users')
		->select('Id','Name')
		->where('Position','LIKE','%technician%')
		->get();

		
		return view('requisitionform',['me'=>$me,'list'=>$list,'technician'=>$technician,'branch'=>$branch,'tech'=>$technicians,'branches'=>$branches]);
	}
	 public function fetchItemList(Request $request)
    {
    	$me = (new CommonController)->get_current_user();
    	$input = $request->all();
    	DB::table('speedfreakinventory')
    	->where('Id','=',$input['invenid'])
    	->update([
    		'qty' => $input['stockout']
    	]);

    	return 1;
    }
     public function getitemlist(Request $request)
    {
    	$me = (new CommonController)->get_current_user();
		$input = $request->all();
    	$inputid = json_decode(json_encode($input['inventoryid']),true);
    	$item = DB::table('speedfreakinventory')
    	->select('Id','name','barcode','qty')
		->whereIn('Id',$inputid)
		->where('qty','<>',0)
    	->get();

        return response()->json(['Item' => $item]);
    }
	private function generateReq(){
		$userid=(new CommonController)->get_current_user()->UserId;
		$temp=DB::Table('requisition')
		->select('Req_No')
		->orderBy('Id','desc')
		->first();
		
		$temp= $temp ? sprintf("%05s",(int)substr($temp->Req_No,strpos($temp->Req_No,'-')+1)+1):sprintf('%05s',1);
		$id=DB::Table('requisition')
		->insertGetId([
			'Req_No'=>"RQO-".$temp,
			'created_by'=>$userid
		]);
		return $id;
	}
	public function confirmStockOut(Request $request)
    {
    	$me = (new CommonController)->get_current_user();
		$input = $request->all();

		$reqid=$this->generateReq();
		foreach($request->data as $data){

 			DB::table('techbag')
			->insert([
				'UserId'=>$me->UserId,
				'InvId'=>$data['history']['speedfreakInventoryId'],
				'Balance'=>$data['history']['qty'],
				'ReqId'=>$reqid
			]);
			DB::table('speedfreakinventory_history')
			->insert([
				'activity' => 'Stock Out to '.$me->Name,
				'branch' => $request->branch,
				'qty' => $data['history']['qty'],
				'speedfreakInventoryId'=> $data['history']['speedfreakInventoryId'],
				'type' => "Stock-out",
				'userId' => $me->UserId,
				'created_at' => Carbon::now(),
				'technicianId' => $me->UserId
	    	]);
		}
    	// $name = DB::table('users')
    	// ->select('Name')
    	// ->where('Id','=',$input['tech'])
    	// ->first();
    	// foreach ($input['confirmid'] as $key => $value) {
    	// 	$balance = DB::table('speedfreakinventory')
    	// 	->select('qty_balance')
    	// 	->where('Id','=',$value)
    	// 	->first();
    	// 	DB::table('speedfreakinventory_history')
	    // 		->insert([
	    // 			'activity' => 'Stock Out to '.$name->Name,
	    // 			'branch' => $input['stockoutbranch'],
	    // 			'qty' => "-".$input['confirmqty'][$key],
	    // 			'speedfreakInventoryId'=> $value,
	    // 			'type' => "Stock-out",
	    // 			'userId' => $me->UserId,
	    // 			'created_at' => Carbon::now(),
	    // 			'technicianId' => $input['tech']
	    // 	]);
    	// }

        return 1;
    }

    function approveRequisition($id){
		$me=(new CommonController)->get_current_user();
		
		$newid = DB::table('requisitionhistory')
		->insertGetId([
			'requisition_Id' => $id,
			'user_Id' => $me->UserId,
			'status' => "Approved",
			'status_details' => "Approved by Admin",
			'created_at' => Carbon::now()
		]);

		return 1;
	}

	function prepare(Request $request){
		$me=(new CommonController)->get_current_user();
		$input=$request->all();
		foreach ($input['confirmid'] as $key => $value) {
    		$balance = DB::table('speedfreakinventory')
    		->select('qty_balance')
    		->where('Id','=',$value)
    		->first();

    		$newbalance = $balance->qty_balance - $input['confirmqty'][$key];

    		DB::table('speedfreakinventory')
    		->where('Id','=',$value)
    		->update([
    			'qty_balance' => $newbalance,
    			'qty' => 0
    		]);

    		DB::table('speedfreakinventory_history')
			->insert([
				'activity' => 'Prepared by '.$me->Name,
				'branch' =>$request->stockoutbranch,
				'qty' => $input['confirmqty'][$key],
				'speedfreakInventoryId'=> $value,
				'type' => "Prepare",
				'userId' => $me->UserId,
				'created_at' => Carbon::now(),
				'technicianId' => $input['tech']
			]);
    	}
		return 1;
	}

	public function importspeedfreakinventory(Request $request)
	{
		if(!$request->importbranch)
		{
			return back()->with('error','Please Select Branch First');
		}

		$me = (new CommonController)->get_current_user();
        $insert = null;
        set_time_limit(3000);
        if($request->hasFile('import')){

            $path = $request->file('import')->getRealPath();
            $data = Excel::load($path, function($reader) {})->get();
            if(!empty($data) && $data->count()){
                foreach ($data->toArray() as $key => $value) {
                    if(!empty($value)){
                        if($value['name'] != "example")
                        {
                            $insert[] = [
                                'Id' => $value['id'],
                                'name' => $value['name'],
                                'barcode' => $value['barcode'],
                                'type' => $value['type'],
                                'model' => $value['model'],
                                'qty' => $value['qty'],
                                'qty_balance' => $value['qty_balance'] == "NULL" ? 0 : $value['qty_balance'],
                                'price' => $value['price'] == "NULL" ? 0 : $value['price'],
                                'price_yuan' => $value['price_yuan'],
                                'supplier' => $value['supplier'],
                                'branch' => $value['branch'],
                                'status' => $value['status'],
                                'technicianId' => $value['technicianid'],
                                'machinery_no' => $value['machinery_no'],
                                'replace_capacity' => $value['replace_capacity'],
                                'purchase_date' => $value['purchase_date'],
                                'rental_rate' => $value['rental_rate'],
                                'engine_model' => $value['engine_model'],
                                'serial_no' => $value['serial_no'],
                                'alternator_serial_no' => $value['alternator_serial_no'],
                                'capacity' => $value['capacity'],
                                'min_litre' => $value['min_litre'],
                                'consumption' => $value['consumption'],
                                'brand' => $value['brand'],
                                'engine_no' => $value['engine_no'],
                                'width' => $value['width'],
                                'length' => $value['length'],
                                'height' =>  $value['height'],
                                'oem' => $value['oem'],
                                'description' => $value['description'],
                                'balance_treshold' => $value['balance_treshold'],
                                'rack_no' => $value['rack_no'],
                                'maxTechhold' => $value['maxtechhold'],
                                'maxOrder' => $value['maxorder']
                            ];
                        }
                    }

                }

                if($insert)
                {
                	$before = DB::table('speedfreakinventory')
                	->select('*')
                	->get();
        
                	// DB::table('speedfreakinventory')
                	// ->delete();

                	foreach (array_chunk($insert, 1000) as $t) {
                		foreach ($t as $tkey => $tvalue) {
                			if($tvalue['Id'])
                			{
                				DB::table('speedfreakinventory')
                				->where('Id',(int)$tvalue['Id'])
			                    ->update(
			                        $tvalue
			                    );
                			}
                			else
                			{
                				$id = DB::table('speedfreakinventory')
			                    ->insertGetId(
			                        $tvalue
			                    );

            					DB::table('inventorypricehistory')
            					->insertGetId([
            						'inventoryId' => $id,
            						'price' => $tvalue['price'],
            						'created_at' => DB::raw('NOW()'),
            						'userId' => $me->UserId,
            						'stockin' => $tvalue['qty_balance']
            					]);
            					
			                    DB::table('speedfreakinventory_history')
            					->insert([
            						'activity'=> "Stock In",
            						'branch' => $request->importbranch,
            						'qty' => $tvalue['qty_balance'],
            						'type' => 'Stock-in',
            						'speedfreakInventoryId' => $id,
            						'userId' => $me->UserId,
            						'created_at' => DB::raw('NOW()')
            					]);

                			}
                		}
                	}

                	foreach ($before as $key => $value) {
                		foreach ($insert as $k => $v) {
                			if($value->Id == (int)$v['Id'])
                			{
                				$qtydiff = $v['qty_balance'] - $value->qty_balance;

                				if($value->price != $v['price'] || $qtydiff >= 0 )
                				{
                					DB::table('inventorypricehistory')
                					->insert([
                						'inventoryId' => $v['Id'],
                						'price' => $v['price'],
                						'created_at' => DB::raw('NOW()'),
                						'userId' => $me->UserId,
                						'stockin' => $qtydiff	
                					]);
                				}

                				if($value->qty_balance != $v['qty_balance'])
                				{
                					$type = $qtydiff > 0 ? "Stock-in" : "Stock-out";

                					DB::table('speedfreakinventory_history')
                					->insert([
                						'activity'=> "Quantity Sync",
                						'branch' => $request->importbranch,
                						'qty' => $qtydiff,
                						'type' => $type,
                						'speedfreakInventoryId' => $v['Id'],
                						'userId' => $me->UserId,
                						'created_at' => DB::raw('NOW()')
                					]);

                					DB::table('speedfreakinventory')
                					->where('Id',$v['Id'])
                					->update([
                						'qty_balance' => $v['qty_balance']
                					]);
                				}
                			}
                		}
                	}

                    //before the insert backup data
                    $beforeid = DB::Table('actionhistory')
                    ->insertGetId([
                    	'Type' => 'SpeedFreakInventory',
                    	'action' => "Import Backup",
                    	'UserId' => $me->UserId,
                    	'created_at' => DB::raw('NOW()'),
                    	'datajson' => json_encode($before)
                    ]);

                    //after the insert
                	DB::Table('actionhistory')
                	->insert([
                		'Type' => 'SpeedFreakInventory',
                		'action' => "Import",
                		'ActionId' => $beforeid,
                		'UserId' => $me->UserId,
                		'created_at' => DB::raw('NOW()'),
                		'datajson' => json_encode($insert)
                	]);
                }

                    return back()->with('alert','Insert Record successfully.');
            }
        }

        return back()->with('error','Please Check your file, Something is wrong there.');
	}

	public function exportspeedfreakinventory()
	{
		$speedfreakinventory = DB::table('speedfreakinventory')
		->get();
    	$filename = "speedfreakinventory.csv";
    	$title = array();
    	ob_clean();
    	$handle = fopen($filename, 'w+');

	    foreach($speedfreakinventory as $a => $row) {
    		$body = array();
	    	foreach ($row as $key => $value) {
	    		if($a == 0)
	    		{
	    			array_push($title,$key);
	    		}

	    		array_push($body, $value);
	    	}

	    	if($a == 0)
	    	{
	    		fputcsv($handle, $title);
	    	}

	       	fputcsv($handle, $body);
	    }

    	fclose($handle);

	    $headers = array(
	        'Content-Type' => 'text/csv',
	    );

   		 return Response::download($filename, 'speedfreakinventory.csv', $headers);

		// return Response::download($speedfreakinventory,'speedfreakinventory.csv');
	}

	public function summarybasequery($cond,$regarr,$scoarr)
	{
		$basequery = DB::table('salesorder')
		->leftJoin('companies', 'companies.Id', '=', 'salesorder.companyId')
		->leftJoin('companies as client','client.Id','=','salesorder.clientId')
		->leftJoin('tracker','tracker.Id','=','salesorder.trackerid')
		->leftJoin('creditnote','creditnote.salesorderId','=','salesorder.Id')
		// ->where('salesorder.invoice',1)
		->where('tracker.Id','>',0)
		->whereRaw($cond);
		
		if($regarr)
		{
			$basequery = $basequery->whereIN('tracker.Region',$regarr);
		}

		if($scoarr)
		{
			$basequery = $basequery->whereIN(DB::Raw('tracker.`Job Scope`'),$scoarr);
		}

		return $basequery;
	}

	public function speedfreaksummarydashboard($year=null, $cat=null, $cus=null, $sco=null, $reg=null)
	{
		$me = (new CommonController)->get_current_user();
		$cond = "1";
		$time = "today";
		$regarr = [];
		$scoarr = [];
		$cusarr = [];
		if($year && $year != "All")
		{
			$cond = $cond." AND rental_start LIKE '%".$year."%' ";
			$time = "Dec-" . $year;
		}
		if($cat && $cat != "All")
		{
			$cond = $cond." AND client.type = '".$cat."' ";
		}
		if($cus && $cus != "All" && $cus != "null")
		{
			// $cond = $cond." AND salesorder.clientId = ".$cus;
			$cond = $cond." AND salesorder.clientId IN (".$cus.")";
			$cusarr = explode(',',$cus);
		}

		if($sco && $sco != "All" && $sco != "null")
		{
			$scoarr = explode(',',$sco);
		}

		if($reg && $reg != "All" && $reg != "null")
		{
			$scoarr = explode(',', $reg);
		}
		
		$bycompany = $this->summarybasequery($cond,$regarr,$scoarr)
		->select(DB::raw('SUM(total_amount - IFNULL(creditnote.amount,0)) as sum'),'companies.Company_Code')
		->groupby('salesorder.companyId')
		->get();

		$piecharttitle = array();
		$piechartdata = array();
		foreach($bycompany as $bvalue)
		{
			array_push($piecharttitle,$bvalue->Company_Code);
			array_push($piechartdata,$bvalue->sum);
		}

		$bycategory = $this->summarybasequery($cond,$regarr,$scoarr)
		->select('client.type',DB::raw('SUBSTRING(rental_start,4) as month'),DB::raw('SUM(total_amount - IFNULL(creditnote.amount,0)) as sum'))
		->orderbyRaw('STR_TO_DATE(month,"%M-%Y") ASC')
		->groupby('month')
		->groupby('client.type')
		->get();
		
		$range = [];
		$categorydata = [];
		$linechartdata = [];
		for($i=11; $i>=0; $i--)
		{

			$date = date('M-Y',strtotime($time.'-'.$i.'month'));
			array_push($range,$date);
		}
	
		foreach($bycategory as $bckey => $bcvalue)
		{
			foreach($range as $rkey => $ranges)
			{
				if($bcvalue->month == $ranges)
				{	
					$categorydata[$bcvalue->type][$bcvalue->month] = $bcvalue->sum;
				}
			}
		}

		foreach($categorydata as $cdkey => $cdvalue)
		{
			foreach($range as $ranges)
			{
				if(!isset($categorydata[$cdkey][$ranges]))
				{
					$categorydata[$cdkey][$ranges] = 0;
				}
			}
		}

		foreach($categorydata as $ck => $cv)
		{
			uksort($cv, array($this,"compare_date_keys"));
			foreach($cv as $cvi)
			{
				$linechartdata[$ck][] = $cvi;
			}
		}
		
		$byclient = $this->summarybasequery($cond,$regarr,$scoarr)
		->select(DB::raw('SUM(total_amount - IFNULL(creditnote.amount,0)) as sum'),'client.Company_Name','client.type')
		->groupby('salesorder.clientId')
		->get();

		$clientchartdata = [];
		$clientcharttitle = [];
		$clientchartlabel = [];
		$clientgroup = collect($byclient)->groupby('Company_Name')->toArray();
	
		foreach($clientgroup as $clkey => $clvalue)
		{
			array_push($clientcharttitle,$clkey);
			foreach($clvalue as $clivalue)
			{
				if(!in_array($clivalue->type,$clientchartlabel))
				{
					array_push($clientchartlabel,$clivalue->type);
				}
				$clientchartdata[$clivalue->type][$clivalue->Company_Name] = [$clivalue->sum];
			}
		}

		foreach($clientchartdata as $cckey => $ccvalue)
		{
			foreach($clientcharttitle as $clientvalue)
			{
				if(!isset($clientchartdata[$cckey][$clientvalue]))
				{
					$clientchartdata[$cckey][$clientvalue] = [0];
				} 
			}
		}

		$clientchart = array_merge_recursive(isset($clientchartdata['GSC']) ? $clientchartdata['GSC'] : [] ,isset($clientchartdata['GST']) ? $clientchartdata['GST'] : [] );
		
		$byjobscope = $this->summarybasequery($cond,$regarr,$scoarr)
		->select(DB::raw('SUM(total_amount - IFNULL(creditnote.amount,0)) as sum'),'client.type',DB::raw(' IF(tracker.`Job Scope` IS NULL or tracker.`Job Scope` = "", "NO SCOPE", tracker.`Job Scope`) as scope'))
		// ->groupby(DB::raw('tracker.`Job Scope`'))
		->groupby(DB::raw(' IF(tracker.`Job Scope` IS NULL or tracker.`Job Scope` = "", "NO SCOPE", tracker.`Job Scope`)'))
		// ->groupb)
		->groupby('client.type')
		->get();

		// dd($byjobscope);

		$scopecharttitle = [];
		$scopechartlabel = [];
		$scopechartdata = [];
		$scopegroup = collect($byjobscope)->groupby('scope')->toArray();
		// group and insert to an array in array[type][scope][sum] manner
		foreach ($scopegroup as $key => $value) {
			array_push($scopecharttitle,$key);
			foreach($value as $vk => $vv)
			{
				if(!in_array($vv->type, $scopechartlabel))
				{
					array_push($scopechartlabel,$vv->type);
				}

				$scopechartdata[$vv->type][$vv->scope] = [$vv->sum];
			}
		}

		//if the scope doesnt exist insert 0
		foreach($scopechartdata as $sckey => $scvalue)
		{
			foreach($scopecharttitle as $scopevalue)
			{
				if(!isset($scopechartdata[$sckey][$scopevalue]))
				{
					$scopechartdata[$sckey][$scopevalue] = [0];
				} 
			}
		}

		//merge to GSC & GST
		$scopechart = array_merge_recursive(isset($scopechartdata['GSC']) ? $scopechartdata['GSC'] : [] ,isset($scopechartdata['GST']) ? $scopechartdata['GST'] : []);


		$byregion = $this->summarybasequery($cond,$regarr,$scoarr)
		->select(DB::raw('SUM(total_amount - IFNULL(creditnote.amount,0))  as sum'),'client.type',DB::raw(' IF(tracker.Region IS NULL or tracker.Region = "", "NO REGION", tracker.Region) as reg'))
		->groupby('reg')
		->get();

		$regioncharttitle = [];
		$regionchartlabel = [];
		$regionchartdata = [];
		$regiongroup = collect($byregion)->groupby('reg')->toArray();
		foreach($regiongroup as $rkey => $rvalue)
		{
			array_push($regioncharttitle,$rkey);
			foreach($rvalue as $rikey => $rivalue)
			{
				if(!in_array($rivalue->type,$regionchartlabel))
				{
					array_push($regionchartlabel,$rivalue->type);
				}
				$regionchartdata[$rivalue->type][$rivalue->reg] = [$rivalue->sum];
			}
		}

		foreach($regionchartdata as $rckey => $rcvalue)
		{
			foreach($regioncharttitle as $rtkey => $rtvalue)
			{
				if(!isset($regionchartdata[$rckey][$rtvalue]))
				{
					$regionchartdata[$rckey][$rtvalue] = [0];
				}
			}
		}

		$regionchart =  array_merge_recursive(isset($regionchartdata['GSC']) ? $regionchartdata['GSC'] : [],isset($regionchartdata['GST']) ? $regionchartdata['GST'] : []);

		$yearfilter = DB::table('salesorder')
		->select(DB::Raw('SUBSTRING(date,8) as year'))
		->groupby(DB::Raw('SUBSTRING(date,8)'))
		->get();
		
		$catfilter = ['GSC','GST'];

		$customerfilter = DB::table('companies')
		->select('Id','Company_Name')
		->where('Client','Yes')
		->get();

		$scopefilter = DB::table('tracker')
		->select(DB::raw('`Job Scope` as scope'))
		->groupby(DB::raw('`Job Scope`'))
		->whereRaw('`Job Scope` != ""')
		->get();

		$regionfilter = DB::table('tracker')
		->select('Region')
		->groupby('Region')
		->whereRaw('Region != ""')
		->get();

		return view('speedfreaksummarydashboard',['me'=>$me,'piechartdata'=>$piechartdata,'piecharttitle'=>$piecharttitle,'clientchartdata'=>$clientchartdata,'clientchartlabel'=>$clientchartlabel,'clientcharttitle'=>$clientcharttitle,'regionchart'=>$regionchart,'regionchartlabel'=>$regionchartlabel,'scopechartlabel'=>$scopechartlabel,'scopegroup'=>$scopegroup,'scopechart'=>$scopechart,'clientchart'=>$clientchart,'categorydata'=>$categorydata,'range'=>$range,'linechartdata'=>$linechartdata,'yearfilter'=>$yearfilter,'catfilter'=>$catfilter,'customerfilter'=>$customerfilter,'scopefilter'=>$scopefilter,'regionfilter'=>$regionfilter,'year'=>$year,'cat'=>$cat,'cus'=>$cus,'sco'=>$sco,'reg'=>$reg,'regarr'=>$regarr, 'scoarr'=>$scoarr, 'cusarr'=>$cusarr]);
	}

	public function compare_date_keys($dt1, $dt2)
	{
		$tm1 = strtotime($dt1);
		$tm2 = strtotime($dt2);
		return ($tm1 < $tm2) ? -1 : (($tm1 > $tm2) ? 1 : 0);
	}

	public function averagesale($year = null)
	{
		$me = (new CommonController)->get_current_user();

		// if ($start==null)
		// {
		// 	$start=date('d-M-Y', strtotime('today'));
		// }
		// if ($end==null)
		// {
		// 	$end=date('d-M-Y', strtotime('tomorrow'));
		// }

		if ($year==null)
		{
			$year=Date("Y");
		}

			# code...
			$sales = DB::table("speedfreakinventory")
			->leftjoin('speedfreakinventory_history','speedfreakinventory_history.speedfreakInventoryId','=','speedfreakinventory.Id')
			->leftJoin('companies','companies.Id','=','speedfreakinventory.supplier')
			->leftJoin(DB::Raw('(SELECT Max(Id) as maxid, inventoryId from inventorysalesprice group by inventoryId) as max'),'max.inventoryId','=','speedfreakinventory.Id')
			->leftJoin('inventorysalesprice','inventorysalesprice.Id','=',DB::raw('max.maxid'))
			->select(DB::raw('LEFT(speedfreakinventory_history.created_at,7) as Work_Month'),DB::raw('SUM(speedfreakinventory_history.qty * inventorysalesprice.price) as Total'))
			->where('speedfreakinventory_history.type','like','%Stock-Out%')
			->where(DB::raw('LEFT(speedfreakinventory_history.created_at,4)'), '=',$year)
			// ->whereRaw("speedfreakinventory_history.`created_at`>=str_to_date('".$start."','%d-%M-%Y') AND speedfreakinventory_history.`created_at`<=str_to_date('".$end."','%d-%M-%Y')")
			->groupBy(DB::raw('LEFT(speedfreakinventory_history.created_at,7)'))
			->orderByRaw(DB::raw('LEFT(speedfreakinventory_history.created_at,9)'))
			->get();

			// ->select(DB::raw('RIGHT(timesheets.Date,8) as Work_Month'),DB::raw('COUNT(DISTINCT timesheets.Date) AS Day_Attend'))
			// ->where('timesheets.Time_In','!=','')
			// ->where(DB::raw('RIGHT(timesheets.Date,4)'), '=',$year)
			// ->whereRaw('timesheets.UserId = "'.$userid.'"')
			// ->groupBy(DB::raw('RIGHT(timesheets.Date,8)'))
			// ->orderByRaw(DB::raw('LEFT(str_to_date(timesheets.Date,"%d-%M-%Y"),7)'))
			// ->get();
			// dd($sales);
		$sale = "";
		$amount="";
		$months=array();

		foreach($sales as $key => $quote){
			$g[]=$quote->Work_Month;
			$sale = implode(',', $g);

			$h[]=$quote->Total;
			$amount = implode(',', $h);
		}

		$user = DB::table('users')
		->select('Id','Name')
		->get();

		return view('averagesale',['me'=>$me,'user'=>$user ,'year'=>$year, 'months'=>$months ,'sale'=>$sale,'sales'=>$sales,'amount'=>$amount]);
	}
}