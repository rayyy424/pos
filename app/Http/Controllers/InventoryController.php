<?php 
namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use DB;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Validator;
use DateTime;
use Dompdf\Dompdf;

class InventoryController extends Controller
{

	public function __construct()
	{
		$this->middleware('auth');
	}

	public function importinventory(Request $request)
	{
	    // dd($request->file('csvfile'));
	    $this->validate($request, [
	        'importfile' => 'required|mimes:csv,txt'
	    ]);

	    $filename = $request->file('importfile')->getRealPath();
	    $delimiter = ',';
	    // dd($filename);

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
	    	// dd($dataArr[$i][0]);
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

	    return redirect('inventorymanagement')->with('success', 'Data successfully imported!');

	}
	public function lowtresholdlist()
	{
		$me = (new CommonController)->get_current_user();
		$item = DB::table('speedfreakinventory')
		->leftJoin(DB::Raw('(SELECT Max(Id) as maxid, inventoryId from inventorypricehistory group by inventoryId) as max'),'max.inventoryId','=','speedfreakinventory.Id')
		->leftJoin('inventorypricehistory','inventorypricehistory.Id','=',DB::raw('max.maxid'))
		->select('speedfreakinventory.Id','speedfreakinventory.name','speedfreakinventory.type','speedfreakinventory.barcode','speedfreakinventory.model','inventorypricehistory.price','speedfreakinventory.balance_treshold','speedfreakinventory.qty_balance')
		->whereRaw('qty_balance <= balance_treshold AND type NOT IN ("GENSET","TANK","ATS","VEHICLE") AND balance_treshold > 0')
		->get();
		return view ('lowtresholdlist', ['me' => $me, 'item' => $item]);
	}
	public function getpricehistory($id)
	{
		$item = DB::table('speedfreakinventory')
		->leftJoin('inventorypricehistory','inventorypricehistory.inventoryId','=','speedfreakinventory.Id')
		->select('speedfreakinventory.name','inventorypricehistory.price','inventorypricehistory.created_at')
		->where('speedfreakinventory.Id','=',$id)
		->get();

		return response()->json(['Item' => $item]);
	}
	public function inventorymanagement()
	{
		$me = (new CommonController)->get_current_user();

		$inventories=DB::table('inventories')
		->select('inventories.Id', 'inventories.Categories', 'inventories.Item_Code','inventories.Description','inventories.Add_Description','inventories.Type','inventories.Acc_No','inventories.Remark','inventories.Unit' ,'inventories.Warehouse','inventories.dimension')
		->get();

		$categories =DB::table('options')
		->distinct('options.Option')
		->select('options.Option')
		->where('options.Table', '=','inventory')
		->where('options.Field', '=','Categories')
		->get();

		$unit=DB::table('options')
		->distinct('options.Option')
		->select('options.Option')
		->where('options.Table', '=','inventory')
		->where('options.Field', '=','Unit')
		->get();

		$warehouses=DB::table('options')
		->distinct('options.Option')
		->select('options.Option')
		->where('options.Table', '=','inventory')
		->where('options.Field', '=','Warehouse')
		->get();

		$type=DB::table('options')
		->distinct('options.Option')
		->select('options.Option')
		->where('options.Table', '=','inventory')
		->where('options.Field', '=','Type')
		->get();

		return view ('inventorymanagement', ['me' => $me, 'inventories' => $inventories, 'categories' => $categories, 'unit' => $unit, 'warehouses' => $warehouses,'type'=>$type]);
	}
	public function vendor(Request $request)
	{
		$me=(new CommonController)->get_current_user();

		$item=DB::table('inventories')
		->where('Id',$request->id)
		->first();
		$company=DB::table('companies')
		->select('Id','Company_Name')
		->where('Supplier','Yes')
		->get();
		$data=DB::table('inventoryvendor')
		->select('inventoryvendor.Id','companies.Company_Name')
		->leftjoin('companies','companies.Id','=','inventoryvendor.CompanyId')
		->where('inventoryvendor.InventoryId','=',$request->id)
		->get();
		
		return view('inventorydetails',['me'=>$me,'item'=>$item,'company'=>$company,'data'=>$data]);
	}

	public function assetinventory($typefilter = null)
	{
		$me = (new CommonController)->get_current_user();
		$cond = "speedfreakinventory.type in ('GENSET','TANK','ATS','VEHICLE')";
		if($typefilter)
		{
			$cond = "speedfreakinventory.type = '".$typefilter."' ";
		}
		$speedfreakinventory=DB::table('speedfreakinventory')
		->leftJoin('inventories','inventories.Item_Code','=','speedfreakinventory.machinery_no')
		// ->leftJoin('deliveryitem','deliveryitem.inventoryId','=','inventories.Id')
		// ->leftJoin('deliveryform','deliveryform.Id','=','deliveryitem.formId')
		->leftJoin( DB::raw('(select Max(Id) as maxid,Max(formId) as maxformId,inventoryId from deliveryitem Group By inventoryId) as max'), 'max.inventoryId', '=', 'inventories.Id')
        ->leftJoin('deliveryform', 'deliveryform.Id', '=', DB::raw('max.`maxformId`'))
        ->leftjoin('radius','radius.Id','=','deliveryform.Location')
		->leftJoin('users','users.Id','=','speedfreakinventory.technicianId')
		->leftJoin('companies','companies.Id','=','deliveryform.client')
		->leftJoin('salesorder','salesorder.Id','=','deliveryform.salesorderid')
		->leftjoin('tracker','tracker.Id','=','salesorder.trackerid')
		->select('speedfreakinventory.Id','speedfreakinventory.machinery_no','speedfreakinventory.type','companies.Company_Name','radius.Location_Name','users.Name','deliveryform.project_type',DB::raw('tracker.`Hire Date` as hiredate'),'deliveryform.offhire_date','speedfreakinventory.status','deliveryform.DO_No')
		->whereRaw($cond)
		->orderBy('speedfreakinventory.machinery_no')
		->get();

		$supplier = DB::table('companies')
		->distinct('companies.Company_Name')
		->select('Id','companies.Company_Name')
		->where('companies.Supplier', '=','Yes')
		// ->where('options.Field', '=','Warehouse')
		->get();

		$type= ["GENSET","ATS","TANK","VEHICLE"];

		return view ('assetinventory', ['me' => $me, 'speedfreakinventory' => $speedfreakinventory,'supplier' => $supplier,'type'=>$type, 'typefilter'=>$typefilter]);
	}

	public function assetinventorydetails($id)
	{
		$me = (new CommonController)->get_current_user();

		$item = DB::table('speedfreakinventory')
		->select('speedfreakinventory.*')
		->where('speedfreakinventory.Id','=',$id)
		->first();

		$deliveryitems = DB::table('deliveryitem')
		->leftJoin('inventories','inventories.Id','=','deliveryitem.InventoryId')
		->select('inventories.Item_Code','deliveryitem.formId')
		->where('inventories.Item_Code','LIKE','%'.$item->machinery_no.'%')
		->get();
		$formid = json_decode(json_encode($deliveryitems),true);
		$formids = array();
		for($i=0; $i<Count($formid); $i++)
		{
			array_push($formids, $formid[$i]['formId']);
		}

		$history = DB::Table('deliveryform')
		->leftJoin('radius','radius.Id','=','deliveryform.Location')
		->leftJoin('options','options.Id','=','deliveryform.Purpose')
		->leftJoin('companies as client','client.id','=','deliveryform.client')
		->select('deliveryform.Id','deliveryform.delivery_date','options.Option','client.Company_Name','radius.Location_Name','deliveryform.DO_No')
		->whereIn('deliveryform.Id',$formids)
		->get();

		return view('assetinventorydetails', ['me'=>$me,'item'=>$item,'history'=>$history,'id'=>$id]);
	}

		public function assetdetails($id)
	{
		$me = (new CommonController)->get_current_user();

		$type = DB::Table('speedfreakinventory')
		->select('speedfreakinventory.type')
		->where('speedfreakinventory.Id','=',$id)
		->first();

		$item = DB::table('speedfreakinventory')
		->leftJoin('companies','companies.Id','=','speedfreakinventory.supplier')
		->select('speedfreakinventory.type','speedfreakinventory.machinery_no','speedfreakinventory.replace_capacity','speedfreakinventory.purchase_date','speedfreakinventory.rental_rate','speedfreakinventory.engine_model','speedfreakinventory.serial_no','speedfreakinventory.alternator_serial_no','speedfreakinventory.capacity','speedfreakinventory.min_litre','speedfreakinventory.consumption','speedfreakinventory.supplier','companies.Company_Name','speedfreakinventory.brand','speedfreakinventory.engine_no','speedfreakinventory.barcode','speedfreakinventory.width','speedfreakinventory.length','speedfreakinventory.height',DB::raw('"" as owner'))
		->where('speedfreakinventory.Id','=',$id)
		->first();

		$companies = DB::table('companies')
		->select('Id','Company_Name')
		->where('Supplier','=','Yes')
		->get();

		$file = DB::table('files')
		->select('Web_Path')
		->where('TargetId','=',$id)
		->where('Type','=','SpeedFreakInventoryQRCode')
		->orderBy('Id','desc')
		->first();

		$internal = DB::table('companies')
        ->select('Id','Company_Name','Company_Code')
        ->whereIn('Id',[1,2,3,66,259,335])
        ->get();

		return view('assetdetails', ['me'=>$me,'item'=>$item,'id'=>$id,'companies'=>$companies,'file'=>$file, 'internal'=>$internal]);
	}

	public function assetupdate(Request $request)
	{
		$me = (new CommonController)->get_current_user();
		$input = $request->all();
		if($input['type'] == "SpeedFreak" || $input['type'] == "SPEEDFREAK")
		{
			if( !($input['machinery_no']  && $input['capacity'] && $input['purchase_date'] && $input['engine_no'] && $input['engine_model']) )
			{
				return "Please fill-in mandatory fields";
			}
		}

		DB::table('speedfreakinventory')
		->where('speedfreakinventory.Id','=',$input['id'])
		->update([
			'machinery_no' => $input['machinery_no'],
			'capacity' => ISSET($input['capacity']) ? $input['capacity'] : "",
			'replace_capacity' => ISSET($input['replace_capacity']) ? $input['replace_capacity'] : "",
			// 'min_litre' => $input['min_litre'],
			'purchase_date' => ISSET($input['purchase_date']) ? $input['purchase_date'] : "",
			// 'consumption' => $input['consumption'],
			'rental_rate' => ISSET($input['rental_rate']) ? $input['rental_rate'] : "",
			'supplier' => ISSET($input['supplier']) ? $input['supplier'] : "",
			'engine_model' => ISSET($input['engine_model']) ? $input['engine_model'] : "",
			'brand' => ISSET($input['owner']) ? $input['owner'] : "",
			'serial_no' => ISSET($input['serial_no']) ? $input['serial_no'] : "",
			'engine_no' => ISSET($input['engine_no']) ? $input['engine_no'] : "",
			'alternator_serial_no' => ISSET($input['alternator_serial_no']) ? $input['alternator_serial_no'] : "",
			'width'=> ISSET($input['width']) ? $input['width'] : 0,
			'length'=> ISSET($input['length']) ? $input['length'] : 0,
			'height'=> ISSET($input['height']) ? $input['height'] : 0,
			'barcode' => ISSET($input['barcode']) ? $input['barcode'] : ""
		]);

		if (ISSET($input['qrcode'])) {
          		$path = "/private/upload/SpeedFreakInventoryQRCode/";
                $file = $input['qrcode'];
                $destinationPath=public_path().$path;
                $extension = $file->getClientOriginalExtension();
                $originalName=$file->getClientOriginalName();
                $fileSize=$file->getSize();
                $fileName=time()."_.".$extension;
                $upload_success = $file->move($destinationPath, $fileName);
                $insert=DB::table('files')->insertGetId(
                    ['Type' => "SpeedFreakInventoryQRCode",
                     'TargetId' => $input['id'],
                     'File_Name' => $originalName,
                     'File_Size' => $fileSize,
                     'Web_Path' => $path."/".$fileName
                    ]
                );
          }

          return 1;
	}
	public function speedfreakinventory($branches = null)
	{
		$me = (new CommonController)->get_current_user();

		$categories = ['Genset','Tank','ATS','Vehicle'];
			if($branches == null)
			{
			$speedfreakinventory=DB::table('speedfreakinventory')
			// ->leftJoin('users','users.Id','=','speedfreakinventory.technicianId')
			->leftJoin('companies','companies.Id','=','speedfreakinventory.supplier')
			->leftJoin(DB::Raw('(SELECT Max(Id) as maxid, inventoryId from inventorypricehistory group by inventoryId) as max'),'max.inventoryId','=','speedfreakinventory.Id')
			->leftJoin('inventorypricehistory','inventorypricehistory.Id','=',DB::raw('max.maxid'))
			->leftJoin(DB::Raw('(SELECT Max(Id) as maxid2, inventoryId from inventorysalesprice group by inventoryId) as max2'),'max2.inventoryId','=','speedfreakinventory.Id')
			->leftJoin('inventorysalesprice','inventorysalesprice.Id','=',DB::raw('max2.maxid2'))
			->select('speedfreakinventory.Id', 'speedfreakinventory.name', 'speedfreakinventory.type','speedfreakinventory.barcode','speedfreakinventory.model','inventorypricehistory.price','inventorysalesprice.price as saleprice','companies.Company_Name','speedfreakinventory.qty_balance','speedfreakinventory.status')
			->whereNotIn('speedfreakinventory.type',$categories)
			// ->whereRaw("speedfreakinventory_history.`created_at`>=str_to_date('".$start."','%d-%M-%Y') AND speedfreakinventory_history.`created_at`<=str_to_date('".$end."','%d-%M-%Y')")
			->get();
			}
			else
			{
				 $speedfreakinventory=DB::table('speedfreakinventory')
				// ->leftJoin('users','users.Id','=','speedfreakinventory.technicianId')
				 ->leftjoin('speedfreakinventory_history','speedfreakinventory_history.speedfreakInventoryId','=','speedfreakinventory.Id')
				->leftJoin('companies','companies.Id','=','speedfreakinventory.supplier')
				->leftJoin(DB::Raw('(SELECT Max(Id) as maxid, inventoryId from inventorypricehistory group by inventoryId) as max'),'max.inventoryId','=','speedfreakinventory.Id')
				->leftJoin('inventorypricehistory','inventorypricehistory.Id','=',DB::raw('max.maxid'))
				->leftJoin(DB::Raw('(SELECT Max(Id) as maxid2, inventoryId from inventorysalesprice group by inventoryId) as max2'),'max2.inventoryId','=','speedfreakinventory.Id')
				->leftJoin('inventorysalesprice','inventorysalesprice.Id','=',DB::raw('max2.maxid2'))
				->select('speedfreakinventory.Id', 'speedfreakinventory.name', 'speedfreakinventory.type','speedfreakinventory.barcode','speedfreakinventory.model','inventorypricehistory.price','inventorysalesprice.price as saleprice','companies.Company_Name',DB::raw("SUM(speedfreakinventory_history.qty) as qty_balance"),'speedfreakinventory.status')
				->where('speedfreakinventory_history.branch','LIKE','%'.$branches.'%')
				->where('speedfreakinventory_history.type','LIKE','%Stock-in%')
				->whereNotIn('speedfreakinventory.type',$categories)
				->groupBy('speedfreakinventory.Id')
				->get();
			}
			
		$status =DB::table('options')
		->distinct('options.Option')
		->select('options.Option')
		->where('options.Table', '=','inventory')
		->where('options.Field', '=','Status')
		->get();

		$supplier = DB::table('companies')
		->distinct('companies.Company_Name')
		->select('Id','companies.Company_Name')
		->where('companies.Supplier', '=','Yes')
		// ->where('options.Field', '=','Warehouse')
		->get();

		$type=DB::table('options')
		->distinct('options.Option')
		->select('options.Option')
		->where('options.Table', '=','inventory')
		->where('options.Field', '=','Type')
		->get();

		$branch=DB::table('options')
		->distinct('options.Option')
		->select('options.Option')
		->where('options.Table', '=','inventory')
		->where('options.Field', '=','Branch')
		->get();
		
		$technician = DB::table('users')
		->select('Id','Name')
		->whereRaw('Position LIKE "%Technician%" OR Position LIKE "%Foreman%" OR Position LIKE "%General Worker%"')
		->get();

		$speedfreakinventorymodel = DB::table('options')
		->select('Id','Option')
		->where('Table','SpeedFreak')
		->where('Field','Model')
		->orderBy('Option','ASC')
		->get();

		$speedfreakinventorytype = DB::table('options')
		->select('Id','Option')
		->where('Table','SpeedFreak')
		->where('Field','Type')
		->orderBy('Option','ASC')
		->get();


		return view ('speedfreakinventory', ['me' => $me, 'speedfreakinventory' => $speedfreakinventory, 'status' => $status, 'supplier' => $supplier,'type'=>$type, 'branch'=>$branch,'technician'=>$technician, 'speedfreakinventorytype'=>$speedfreakinventorytype, 'speedfreakinventorymodel'=>$speedfreakinventorymodel, 'currentbranch'=>$branches]);
	}

	public function branchtransfergetquantity(Request $request, $type = null)
	{
		$input = $request->all();

		if($type == null)
		{
			$balance = DB::table('speedfreakinventory_history')
			->select(DB::Raw('SUM(qty) as count'))
			->where('branch','=',$input['branch'])
			->where('speedfreakInventoryId','=',$input['id'])
			->get();
		}
		else if($type == "in")
		{
			$balance = DB::table('speedfreakinventory_history')
			->select(DB::Raw('SUM(qty) as count'))
			->where('branch','=',$input['in'])
			->where('speedfreakInventoryId','=',$input['id'])
			->get();
		}
		else
		{
			$balance = DB::table('speedfreakinventory_history')
			->select(DB::Raw('SUM(qty) as count'))
			->where('branch','=',$input['out'])
			->where('speedfreakInventoryId','=',$input['id'])
			->get();
		}

		return response()->json(['balance' => $balance]);
	} 

	public function speedfreakupdate(Request $request)
	{
		$me = (new CommonController)->get_current_user();
		$input = $request->all();
		if($input['Process']== "Stock Out Branch")
		{
			if( !$input['branch_in'] || !$input['branch_out'])
			{
				return "Branch is empty";
			}
		}
		else
		{
			if($input['branch'] == "")
			{
				return "Branch is empty";
			}
		}

		if($input['Process'] == "Stock In")
		{
			$bal = DB::table('speedfreakinventory')
			->where('Id','=',$input['StockId'])
			->select('qty_balance')
			->first();

			$balance = $bal->qty_balance + $input['qty_in'];

			DB::table('speedfreakinventory')
			->where('Id','=',$input['StockId'])
			->update([
					'qty_balance' => $balance
			]);
			DB::table('speedfreakinventory_history')
			->insertGetId([
				'activity' => $input['Process'],
				'branch' => $input['branch'],
				'qty' => $input['qty_in'],
				'type' => "Stock-in",
				'speedfreakInventoryId' => $input['StockId'],
				'userId' => $me->UserId,
				'created_at' => Carbon::now(),
				'technicianId' => $input['technician']
			]);
		}
		else if($input['Process'] == "Stock Out")
		{
			$bal = DB::table('speedfreakinventory')
			->where('Id','=',$input['StockId'])
			->select('qty_balance')
			->first();

			$balance = $bal->qty_balance - $input['qty_out'];
			if($balance < 0)
			{
				return "not enough balance";
			}

			$technician = DB::table('users')
			->where('Id','=',$input['technician'])
			->select('Name')
			->first();
			DB::table('speedfreakinventory')
			->where('Id','=',$input['StockId'])
			->update([
					'qty_balance' => $balance
			]);
			DB::table('speedfreakinventory_history')
			->insertGetId([
				'activity' => $input['Process']." to ".$technician->Name,
				'branch' => $input['branch'],
				'qty' => $input['qty_out'],
				'type' => "Stock-out",
				'speedfreakInventoryId' => $input['StockId'],
				'userId' => $me->UserId,
				'created_at' => Carbon::now(),
				'technicianId' => $input['technician']
			]);
		}
		else if($input['Process'] == "Stock Return")
		{
			$bal = DB::table('speedfreakinventory')
			->where('Id','=',$input['StockId'])
			->select('qty_balance')
			->first();

			$balance = $bal->qty_balance + $input['qty_return'];

			$technician = DB::table('users')
			->where('Id','=',$input['technician'])
			->select('Name')
			->first();
			DB::table('speedfreakinventory')
			->where('Id','=',$input['StockId'])
			->update([
					'qty_balance' => $balance
			]);

			$balance = DB::table('techbag')
			->select('Balance')
			->where('UserId','=',$input['technician'])
			->where('InvId','=',$input['StockId'])
			->first();

			$tbalance = $balance->Balance + $input['qty_return'];

			DB::table('techbag')
			->where('UserId','=',$input['technician'])
			->where('InvId','=',$input['StockId'])
			->update([
				'Balance' => $tbalance
			]);

			DB::table('speedfreakinventory_history')
			->insertGetId([
				'activity' => $input['Process']." from ".$technician->Name,
				'branch' => $input['branch'],
				'qty' => $input['qty_return'],
				'type' => "Stock-return",
				'speedfreakInventoryId' => $input['StockId'],
				'userId' => $me->UserId,
				'created_at' => Carbon::now(),
				'technicianId' => $input['technician']
			]);
		}
		else if($input['Process'] == "Stock Out Branch")
		{
			// $balance = $input['qty_balance'] - $input['qty_branch_out'];
			// DB::table('speedfreakinventory')
			// ->where('Id','=',$input['StockId'])
			// ->update([
			// 		'qty_balance' => $balance
			// ]);
			$bal = DB::table('speedfreakinventory_history')
			->where('speedfreakInventoryId','=',$input['StockId'])
			->where('branch','=',$input['branch_out'])
			->select(DB::raw('sum(qty) as qty_balance'))
			->first();

			if(!$bal->qty_balance)
			{
				$bal->qty_balance = 0;
			}
			$balance = $bal->qty_balance - $input['qty_branch_out'];
			if($balance < 0 )
			{
				return "not enough balance";
			}

			DB::table('speedfreakinventory_history')
			->insertGetId([
				'activity' => $input['Process']." from ".$input['branch_out'],
				'branch' => $input['branch_in'],
				'qty' => $input['qty_branch_out'],
				'type' => "Stock-in",
				'speedfreakInventoryId' => $input['StockId'],
				'userId' => $me->UserId,
				'created_at' => Carbon::now(),
			]);
			DB::table('speedfreakinventory_history')
			->insertGetId([
				'activity' => $input['Process']." to ".$input['branch_in'],
				'branch' => $input['branch_out'],
				'qty' => "-".$input['qty_branch_out'],
				'type' => "Stock-out",
				'speedfreakInventoryId' => $input['StockId'],
				'userId' => $me->UserId,
				'created_at' => Carbon::now(),
			]);

		}
		return 1;
	}

	public function speedfreakdelete(Request $request)
	{
		$input = $request->all();
		DB::table('speedfreakinventory')
		->where('Id','=',$input['Id'])
		->delete();

		DB::table('speedfreakinventory_history')
		->where('speedfreakInventoryId','=',$input['Id'])
		->delete();
		return 1;
	}
	public function speedfreakinventorydetails($id = null)
	{
		$me = (new CommonController)->get_current_user();

		$item = DB::table('speedfreakinventory')
		->leftJoin('companies','companies.Id','=','speedfreakinventory.supplier')
		->leftJoin(DB::Raw('(SELECT Max(Id) as maxid, inventoryId from inventorypricehistory group by inventoryId) as max'),'max.inventoryId','=','speedfreakinventory.Id')
		->leftJoin('inventorypricehistory','inventorypricehistory.Id','=',DB::raw('max.maxid'))
		->leftJoin(DB::Raw('(SELECT Max(Id) as maxid2, inventoryId from inventorysalesprice group by inventoryId) as max2'),'max2.inventoryId','=','speedfreakinventory.Id')
		->leftJoin('inventorysalesprice','inventorysalesprice.Id','=',DB::raw('max2.maxid2'))
		->select('speedfreakinventory.*','companies.Company_Name','inventorypricehistory.price as latest_price','inventorysalesprice.price as latest_saleprice')
		->where('speedfreakinventory.Id','=',$id)
		->get();

		$history =DB::table('speedfreakinventory_history')
		->leftjoin('speedfreakinventory', 'speedfreakinventory.Id', '=', 'speedfreakinventory_history.speedfreakInventoryId')
		// ->leftjoin('options', 'options.Id', '=', 'speedfreakinventory_history.branch')
		->leftJoin('users','users.Id','=','speedfreakinventory_history.userId')
		->select('speedfreakinventory_history.Id','speedfreakinventory_history.activity','speedfreakinventory_history.branch','speedfreakinventory_history.qty','users.Name','speedfreakinventory_history.created_at')
		->where('speedfreakinventory_history.speedfreakInventoryId','=',$id)
		->get();

		$balance = DB::table('speedfreakinventory_history')
		->leftjoin('speedfreakinventory', 'speedfreakinventory.Id', '=', 'speedfreakinventory_history.speedfreakInventoryId')
		->select('speedfreakinventory_history.Id','speedfreakinventory_history.branch',DB::raw('SUM(speedfreakinventory_history.qty)as total'))
		->where('speedfreakinventory_history.speedfreakInventoryId','=',$id)
		->groupBy('speedfreakinventory_history.branch')
		->get();
		// dd($balance);

		$supplier = DB::table('companies')
		->select('Id','Company_Name')
		->where('Supplier','=','Yes')
		->get();

		$image = DB::table('files')
		->select('Web_Path')
		->where('TargetId','=',$id)
		->where('Type','=','SpeedFreakInventory')
		->orderBy('Id','desc')
		->first();

		$qrcode = DB::table('files')
		->select('Web_Path')
		->where('TargetId','=',$id)
		->where('Type','=','SpeedFreakInventoryQRCode')
		->orderBy('Id','desc')
		->first();

		$store = DB::table('files')
		->select('Web_Path')
		->where('TargetId','=',$id)
		->where('Type','=','SpeedFreakInventoryStore')
		->orderBy('Id','desc')
		->first();

		$speedfreakinventorymodel = DB::table('options')
		->select('Id','Option')
		->where('Table','SpeedFreak')
		->where('Field','Model')
		->get();

		$speedfreakinventorytype = DB::table('options')
		->select('Id','Option')
		->where('Table','SpeedFreak')
		->where('Field','Type')
		->get();

		return view('speedfreakinventorydetails', ['me'=>$me,'item'=>$item,'history'=>$history,'balance'=>$balance,'id'=>$id,'supplier'=>$supplier,'image'=>$image,'qrcode'=>$qrcode,'store'=>$store,'speedfreakinventorytype'=>$speedfreakinventorytype,'speedfreakinventorymodel'=>$speedfreakinventorymodel]);
	}

	public function speedfreakinventoryhistory($branch,$id)
	{
		$me = (new CommonController)->get_current_user();

		$history =DB::table('speedfreakinventory_history')
		->leftjoin('speedfreakinventory', 'speedfreakinventory.Id', '=', 'speedfreakinventory_history.speedfreakInventoryId')
		// ->leftjoin('options', 'options.Id', '=', 'speedfreakinventory_history.branch')
		->leftJoin('users','users.Id','=','speedfreakinventory_history.userId')
		->select('speedfreakinventory_history.Id','speedfreakinventory_history.activity','speedfreakinventory_history.branch','speedfreakinventory_history.qty','users.Name','speedfreakinventory_history.created_at')
		->where('speedfreakinventory_history.speedfreakInventoryId','=',$id)
		->where('speedfreakinventory_history.branch','LIKE',"%".$branch."%")
		->get();

		return view('speedfreakinventoryhistory', ['me'=>$me,'history'=>$history]);
	}

	public function speedfreakinventoryedit(Request $request)
	{
		$input = $request->all();
		$me = (new CommonController)->get_current_user();
		if($input['item_name'] == "" || $input['type'] == ""|| $input['model'] == "" || $input['barcode'] == "" || $input['description'] == "")
		{
			return 0;
		}
		DB::table('speedfreakinventory')
		->where('Id','=',$input['id'])
		->update([
			'name' => $input['item_name'],
			'type' => $input['type'],
			'model' => $input['model'],
			'price_yuan' => $input['price_yuan'],
			'supplier' => $input['supplier'],
			'oem' => $input['oem'],
			'barcode' => $input['barcode'],
			'description' => $input['description'],
			// 'price' => $input['price'],
			'qty_balance' => $input['qty_balance'],
			'balance_treshold' => $input['balance_treshold'],
			'status' => $input['status'],
			'maxOrder' => $input['maxOrder'],
			'maxTechhold' => $input['maxTechhold']
		]);

		$latestprice = DB::Table('inventorypricehistory')
		->select('price','supplier')
		->orderby('Id','DESC')
		->where('inventoryId',$input['id'])
		->first();

		if( !$latestprice || $input['price'] != $latestprice->price || $input['supplier'] != $latestprice->supplier )
		{
			DB::table('inventorypricehistory')
			->insert([
				'inventoryId' => $input['id'],
				'price' => $input['price'],
				'supplier' => $input['supplier'],
				'created_at' => Carbon::now(),
				'userId' => $me->UserId
			]);
		}

		$latestsalesprice = DB::Table('inventorysalesprice')
		->select('price','supplier')
		->orderby('Id','DESC')
		->where('inventoryId',$input['id'])
		->first();

		if( !$latestsalesprice || $input['saleprice'] != $latestsalesprice->price || $input['supplier'] != $latestsalesprice->supplier )
		{
			DB::table('inventorysalesprice')
			->insert([
				'inventoryId' => $input['id'],
				'price' => $input['saleprice'],
				'supplier' => $input['supplier'],
				'created_at' => Carbon::now(),
				'userId' => $me->UserId
			]);
		}

		$filenames="";
        $attachmentUrl = null;
		 if(isset($input['image']))
        {
          if ($input['image'] != null || $input['image'] != "") {
          		$path = "/private/upload/SpeedFreakInventory/";
                $file = $input['image'];
                $destinationPath=public_path().$path;
                $extension = $file->getClientOriginalExtension();
                $originalName=$file->getClientOriginalName();
                $fileSize=$file->getSize();
                $fileName=time()."_".$extension;
                $upload_success = $file->move($destinationPath, $fileName);
                $insert=DB::table('files')->insertGetId(
                    ['Type' => "SpeedFreakInventory",
                     'TargetId' => $input['id'],
                     'File_Name' => $originalName,
                     'File_Size' => $fileSize,
                     'Web_Path' => $path."/".$fileName
                    ]
                );
                $attachmentUrl = url($path."/".$fileName);
                $filenames.= $insert."|".$attachmentUrl."|" .$originalName.",";
          }
        }

		if(isset($input['qrcode']))
        {
          if ($input['qrcode'] != null || $input['qrcode'] != "") {
          		$path = "/private/upload/SpeedFreakInventoryQRCode/";
                $file = $input['qrcode'];
                $destinationPath=public_path().$path;
                $extension = $file->getClientOriginalExtension();
                $originalName=$file->getClientOriginalName();
                $fileSize=$file->getSize();
                $fileName=time()."_.".$extension;
                $upload_success = $file->move($destinationPath, $fileName);
                $insert=DB::table('files')->insertGetId(
                    ['Type' => "SpeedFreakInventoryQRCode",
                     'TargetId' => $input['id'],
                     'File_Name' => $originalName,
                     'File_Size' => $fileSize,
                     'Web_Path' => $path."/".$fileName
                    ]
                );
                $attachmentUrl = url($path."/".$fileName);
                $filenames.= $insert."|".$attachmentUrl."|" .$originalName.",";
          }
        }

        if(isset($input['store']))
        {
          if ($input['store'] != null || $input['store'] != "") {
          		$path = "/private/upload/SpeedFreakInventoryStore/";
                $file = $input['store'];
                $destinationPath=public_path().$path;
                $extension = $file->getClientOriginalExtension();
                $originalName=$file->getClientOriginalName();
                $fileSize=$file->getSize();
                $fileName=time()."_".$extension;
                $upload_success = $file->move($destinationPath, $fileName);
                $insert=DB::table('files')->insertGetId(
                    ['Type' => "SpeedFreakInventoryStore",
                     'TargetId' => $input['id'],
                     'File_Name' => $originalName,
                     'File_Size' => $fileSize,
                     'Web_Path' => $path."/".$fileName
                    ]
                );
                $attachmentUrl = url($path."/".$fileName);
                $filenames.= $insert."|".$attachmentUrl."|" .$originalName.",";
          }
        }

		return 1;
	}

	public function speedfreakinventorycreate(Request $request)
	{
		$me = (new CommonController)->get_current_user();

		$id = DB::Table('speedfreakinventory')
		->insertGetId([
			'name' => $request->name,
			'type' => $request->type,
			'barcode' => $request->barcode,
			'qty_balance' => $request->balance,
			'supplier' => $request->supplier, 
			'model' => $request->model,
			'maxOrder' => $request->maxOrder,
			'maxTechhold' => $request->maxTechhold
		]);

		DB::table('inventorypricehistory')
		->insert([
			'inventoryId' => $id,
			'price' => $request->price,
			'created_at' => Carbon::now(),
			'userId' => $me->UserId,
			'stockin' => $request->balance,
			'supplier' => $request->supplier
		]);

		DB::table('inventorysalesprice')
		->insert([
			'inventoryId' => $id,
			'price' => $request->saleprice,
			'created_at' => Carbon::now(),
			'userId' => $me->UserId,
			'stockin' => $request->balance,
			'supplier' => $request->supplier
		]);

		DB::table('speedfreakinventory_history')
		->insert([
			'activity' => 'Stock In',
			'branch' => 'Store Jenjarom',
			'qty' => $request->balance,
			'type' => "Stock-in",
			'speedfreakInventoryId' => $id,
			'userId' => $me->UserId,
			'created_at' => Carbon::now()
		]);

		return 1;
	}

	public function inventorypricehistory($id = null)
	{
		$me = (new CommonController)->get_current_user();

		$cond = "1";
		if($id)
		{
			$cond = "speedfreakinventory.Id = ".$id;
		}

		$list = DB::table('inventorypricehistory')
		->leftjoin('speedfreakinventory','speedfreakinventory.Id','=','inventorypricehistory.inventoryId')
		->leftjoin('companies','companies.Id','=','inventorypricehistory.supplier')
		->leftjoin('users','users.Id','=','inventorypricehistory.userId')
		->select('inventorypricehistory.Id','speedfreakinventory.name','speedfreakinventory.barcode','companies.Company_Name','inventorypricehistory.price','inventorypricehistory.stockin','inventorypricehistory.stockout','users.Name as user','inventorypricehistory.created_at')
		->orderby('inventorypricehistory.inventoryId','ASC')
		->orderby('inventorypricehistory.Id','ASC')
		->whereRaw($cond)
		->get();

		$inventory = DB::table('speedfreakinventory')
		->select('Id','barcode')
		->whereNotIN('type',['GENSET','ATS','VEHICLE','TANK'])
		->get();

		return view('pricehistory',['me'=>$me,'list'=>$list,'inventory'=>$inventory,'id'=>$id]);
	}

	public function speedfreaksales($start = null, $end = null,$branches = null)
	{
		$me = (new CommonController)->get_current_user();

		$today = date('d-M-Y', strtotime('today'));

		if($start == null)
		{
			$start = date('d-M-Y',strtotime('today'));

		}
		if($end == null)
		{
			$end = date('d-M-Y',strtotime('tomorrow'));
		}

		$categories = ['Genset','Tank','ATS','Vehicle'];
			if($branches == null)
			{
			$speedfreaksales=DB::table('speedfreakinventory')
			// ->leftJoin('users','users.Id','=','speedfreakinventory.technicianId')
			->leftjoin('speedfreakinventory_history','speedfreakinventory_history.speedfreakInventoryId','=','speedfreakinventory.Id')
			->leftJoin('companies','companies.Id','=','speedfreakinventory.supplier')
			->leftJoin(DB::Raw('(SELECT Max(Id) as maxid, inventoryId from inventorysalesprice group by inventoryId) as max'),'max.inventoryId','=','speedfreakinventory.Id')
			->leftJoin('inventorysalesprice','inventorysalesprice.Id','=',DB::raw('max.maxid'))
			->select('speedfreakinventory.Id', 'speedfreakinventory.name', 'speedfreakinventory.type','speedfreakinventory.barcode','speedfreakinventory.model', 'inventorysalesprice.price' ,'companies.Company_Name','speedfreakinventory_history.qty','speedfreakinventory.status')
			->whereNotIn('speedfreakinventory.type',$categories)
			->where('speedfreakinventory_history.type','like','%Stock-Out%')
			->whereRaw("speedfreakinventory_history.`created_at`>=str_to_date('".$start."','%d-%M-%Y') AND speedfreakinventory_history.`created_at`<=str_to_date('".$end."','%d-%M-%Y')")
			->get();
			}
			else
			{
				 $speedfreaksales=DB::table('speedfreakinventory')
				// ->leftJoin('users','users.Id','=','speedfreakinventory.technicianId')
				->leftjoin('speedfreakinventory_history','speedfreakinventory_history.speedfreakInventoryId','=','speedfreakinventory.Id')
				->leftJoin('companies','companies.Id','=','speedfreakinventory.supplier')
				->leftJoin(DB::Raw('(SELECT Max(Id) as maxid, inventoryId from inventorysalesprice group by inventoryId) as max'),'max.inventoryId','=','speedfreakinventory.Id')
				->leftJoin('inventorysalesprice','inventorysalesprice.Id','=',DB::raw('max.maxid'))
				->select('speedfreakinventory.Id', 'speedfreakinventory.name', 'speedfreakinventory.type','speedfreakinventory.barcode','speedfreakinventory.model','inventorysalesprice.price','companies.Company_Name',DB::raw("SUM(speedfreakinventory_history.qty) as qty_balance"),'speedfreakinventory.status')
				->where('speedfreakinventory_history.branch','LIKE','%'.$branches.'%')
				->where('speedfreakinventory_history.type','like','%Stock-Out%')
				->whereNotIn('speedfreakinventory.type',$categories)
				->whereRaw("speedfreakinventory_history.`created_at`>=str_to_date('".$start."','%d-%M-%Y') AND speedfreakinventory_history.`created_at`<=str_to_date('".$end."','%d-%M-%Y')")
				->groupBy('speedfreakinventory.Id')
				->get();
			}
			// dd($speedfreaksales);
		$status =DB::table('options')
		->distinct('options.Option')
		->select('options.Option')
		->where('options.Table', '=','inventory')
		->where('options.Field', '=','Status')
		->get();

		$supplier = DB::table('companies')
		->distinct('companies.Company_Name')
		->select('Id','companies.Company_Name')
		->where('companies.Supplier', '=','Yes')
		// ->where('options.Field', '=','Warehouse')
		->get();

		$type=DB::table('options')
		->distinct('options.Option')
		->select('options.Option')
		->where('options.Table', '=','inventory')
		->where('options.Field', '=','Type')
		->get();

		$branch=DB::table('options')
		->distinct('options.Option')
		->select('options.Option')
		->where('options.Table', '=','inventory')
		->where('options.Field', '=','Branch')
		->get();
		
		$technician = DB::table('users')
		->select('Id','Name')
		->whereRaw('Position LIKE "%Mechanic%"')
		->get();

		$speedfreakinventorymodel = DB::table('options')
		->select('Id','Option')
		->where('Table','SpeedFreak')
		->where('Field','Model')
		->orderBy('Option','ASC')
		->get();

		$speedfreakinventorytype = DB::table('options')
		->select('Id','Option')
		->where('Table','SpeedFreak')
		->where('Field','Type')
		->orderBy('Option','ASC')
		->get();


		return view ('speedfreaksales', ['me' => $me,'start'=>$start , 'end'=>$end ,'today' => $today, 'speedfreaksales' => $speedfreaksales, 'status' => $status, 'supplier' => $supplier,'type'=>$type, 'branch'=>$branch,'technician'=>$technician, 'speedfreakinventorytype'=>$speedfreakinventorytype, 'speedfreakinventorymodel'=>$speedfreakinventorymodel, 'currentbranch'=>$branches]);
	}
}
