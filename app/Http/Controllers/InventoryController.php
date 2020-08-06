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
		$item = DB::table('gensetinventory')
		->leftJoin(DB::Raw('(SELECT Max(Id) as maxid, inventoryId from inventorypricehistory group by inventoryId) as max'),'max.inventoryId','=','gensetinventory.Id')
		->leftJoin('inventorypricehistory','inventorypricehistory.Id','=',DB::raw('max.maxid'))
		->select('gensetinventory.Id','gensetinventory.name','gensetinventory.type','gensetinventory.barcode','gensetinventory.model','inventorypricehistory.price','gensetinventory.balance_treshold','gensetinventory.qty_balance')
		->whereRaw('qty_balance <= balance_treshold AND type NOT IN ("GENSET","TANK","ATS","VEHICLE") AND balance_treshold > 0')
		->get();
		return view ('lowtresholdlist', ['me' => $me, 'item' => $item]);
	}
	public function getpricehistory($id)
	{
		$item = DB::table('gensetinventory')
		->leftJoin('inventorypricehistory','inventorypricehistory.inventoryId','=','gensetinventory.Id')
		->select('gensetinventory.name','inventorypricehistory.price','inventorypricehistory.created_at')
		->where('gensetinventory.Id','=',$id)
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
		// dd($warehouses);

		// $inventories = DB::table('inventories')
		// ->select('inventories.Id', 'inventories.Item_Code', 'inventories.Part_Name', 'inventories.Unit','inventories.Remark','inventories.Material_Service','stocks.Quantity')
		// ->select('inventories.Id','stocks.Id as StockId','projects.Project_Name','stocks.Region','stocks.Ownership','inventories.Item_Code','inventories.Part_Name','inventories.Bundled','inventories.Family','inventories.Model','inventories.Size','inventories.Color','inventories.Material','inventories.Brand','inventories.Unit','inventories.Remark','inventories.Material_Service',DB::raw('SUM(IF(`Action`="Purchase" OR `Action`="Created" or `Action`="Extra" or `Action`="Move In" or `Action`="Transfer In" or `Action`="Deposit" or `Action`="Receive", `Quantity`, -1 * `Quantity`)) AS Quantity'),'ssc.StockCheckQuantity')
		// ->leftJoin('stocks', 'stocks.Inventory_Id', '=', 'inventories.Id')
		// ->leftJoin('projects', 'stocks.ProjectId', '=', 'projects.Id')
		// ->leftJoin(DB::raw('(SELECT SUM(StockCheckQuantity) as StockCheckQuantity, IID FROM (SELECT DISTINCT Quantity as StockCheckQuantity, stock_check_inventory.Room_Id, stock_check_inventory.Inventory_Id as IID, Date FROM stock_check_inventory INNER JOIN stock_check ON stock_check.Id = stock_check_inventory.Stock_Check_Id INNER JOIN (SELECT Room_Id, Inventory_Id, MAX(Date) as MaxDate FROM stock_check_inventory LEFT JOIN stock_check ON stock_check_inventory.Stock_Check_Id = stock_check.Id GROUP BY Room_Id, Inventory_Id) b ON stock_check.Date = b.MaxDate AND b.Room_Id = stock_check_inventory.Room_Id AND b.Inventory_Id = stock_check_inventory.Inventory_Id) as sc GROUP BY IID) as ssc'), 'ssc.IID', '=', 'inventories.Id')
		// ->where('stocks.Ownership', '!=','Propel')
		// ->where('inventories.Material_Service', '=','Stocks')
		// ->groupBy('inventories.Id')
		// ->groupBy('inventories.Id','stocks.ProjectId','stocks.Region','stocks.Ownership')
		// ->get();

		// $stockcheck = DB::table('stock_check')->orderBy('Date', 'DESC')->get();

		// $projects = DB::table('projects')
		// ->get();

		// $regions= DB::table('options')
		// ->whereIn('Table', ["tracker"])
		// ->where('Field', '=','Region')
		// ->orderBy('Table','asc')
		// ->orderBy('Option','asc')
		// ->get();

		// $ownerships= DB::table('options')
		// ->whereIn('Table', ["stocks"])
		// ->where('Field', '=','Ownership')
		// ->orderBy('Table','asc')
		// ->orderBy('Option','asc')
		// ->get();

		// $locations = DB::table('rooms')
		// ->select('rooms.Id as RoomId','warehouses.Code as WarehouseCode', 'warehouses.Name as WarehouseName', 'rooms.Name as RoomName', 'rooms.Code as RoomCode')
		// ->join('warehouses', 'rooms.Warehouse_Id', '=', 'warehouses.Id')
		// ->get();

		// return view ('inventorymanagement', ['me' => $me, 'inventories' => $inventories, 'stockcheck' => $stockcheck, 'projects' => $projects, 'regions' => $regions,  'ownerships' => $ownerships, 'locations' => $locations]);

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
		$cond = "gensetinventory.type in ('GENSET','TANK','ATS','VEHICLE')";
		if($typefilter)
		{
			$cond = "gensetinventory.type = '".$typefilter."' ";
		}
		$gensetinventory=DB::table('gensetinventory')
		->leftJoin('inventories','inventories.Item_Code','=','gensetinventory.machinery_no')
		// ->leftJoin('deliveryitem','deliveryitem.inventoryId','=','inventories.Id')
		// ->leftJoin('deliveryform','deliveryform.Id','=','deliveryitem.formId')
		->leftJoin( DB::raw('(select Max(Id) as maxid,Max(formId) as maxformId,inventoryId from deliveryitem Group By inventoryId) as max'), 'max.inventoryId', '=', 'inventories.Id')
        ->leftJoin('deliveryform', 'deliveryform.Id', '=', DB::raw('max.`maxformId`'))
        ->leftjoin('radius','radius.Id','=','deliveryform.Location')
		->leftJoin('users','users.Id','=','gensetinventory.technicianId')
		->leftJoin('companies','companies.Id','=','deliveryform.client')
		->leftJoin('salesorder','salesorder.Id','=','deliveryform.salesorderid')
		->leftjoin('tracker','tracker.Id','=','salesorder.trackerid')
		->select('gensetinventory.Id','gensetinventory.machinery_no','gensetinventory.type','companies.Company_Name','radius.Location_Name','users.Name','deliveryform.project_type',DB::raw('tracker.`Hire Date` as hiredate'),'deliveryform.offhire_date','gensetinventory.status','deliveryform.DO_No')
		->whereRaw($cond)
		->orderBy('gensetinventory.machinery_no')
		->get();

		$supplier = DB::table('companies')
		->distinct('companies.Company_Name')
		->select('Id','companies.Company_Name')
		->where('companies.Supplier', '=','Yes')
		// ->where('options.Field', '=','Warehouse')
		->get();

		$type= ["GENSET","ATS","TANK","VEHICLE"];

		return view ('assetinventory', ['me' => $me, 'gensetinventory' => $gensetinventory,'supplier' => $supplier,'type'=>$type, 'typefilter'=>$typefilter]);
	}

	public function assetinventorydetails($id)
	{
		$me = (new CommonController)->get_current_user();

		$item = DB::table('gensetinventory')
		->select('gensetinventory.*')
		->where('gensetinventory.Id','=',$id)
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

		$type = DB::Table('gensetinventory')
		->select('gensetinventory.type')
		->where('gensetinventory.Id','=',$id)
		->first();

		$item = DB::table('gensetinventory')
		->leftJoin('companies','companies.Id','=','gensetinventory.supplier')
		->select('gensetinventory.type','gensetinventory.machinery_no','gensetinventory.replace_capacity','gensetinventory.purchase_date','gensetinventory.rental_rate','gensetinventory.engine_model','gensetinventory.serial_no','gensetinventory.alternator_serial_no','gensetinventory.capacity','gensetinventory.min_litre','gensetinventory.consumption','gensetinventory.supplier','companies.Company_Name','gensetinventory.brand','gensetinventory.engine_no','gensetinventory.barcode','gensetinventory.width','gensetinventory.length','gensetinventory.height',DB::raw('"" as owner'))
		->where('gensetinventory.Id','=',$id)
		->first();

		$companies = DB::table('companies')
		->select('Id','Company_Name')
		->where('Supplier','=','Yes')
		->get();

		$file = DB::table('files')
		->select('Web_Path')
		->where('TargetId','=',$id)
		->where('Type','=','GensetInventoryQRCode')
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
		if($input['type'] == "Genset" || $input['type'] == "GENSET")
		{
			if( !($input['machinery_no']  && $input['capacity'] && $input['purchase_date'] && $input['engine_no'] && $input['engine_model']) )
			{
				return "Please fill-in mandatory fields";
			}
		}

		DB::table('gensetinventory')
		->where('gensetinventory.Id','=',$input['id'])
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
          		$path = "/private/upload/GensetInventoryQRCode/";
                $file = $input['qrcode'];
                $destinationPath=public_path().$path;
                $extension = $file->getClientOriginalExtension();
                $originalName=$file->getClientOriginalName();
                $fileSize=$file->getSize();
                $fileName=time()."_.".$extension;
                $upload_success = $file->move($destinationPath, $fileName);
                $insert=DB::table('files')->insertGetId(
                    ['Type' => "GensetInventoryQRCode",
                     'TargetId' => $input['id'],
                     'File_Name' => $originalName,
                     'File_Size' => $fileSize,
                     'Web_Path' => $path."/".$fileName
                    ]
                );
          }

          return 1;
	}
	public function gensetinventory($branches = null)
	{
		$me = (new CommonController)->get_current_user();

		$categories = ['Genset','Tank','ATS','Vehicle'];
			if($branches == null)
			{
			$gensetinventory=DB::table('gensetinventory')
			// ->leftJoin('users','users.Id','=','gensetinventory.technicianId')
			->leftJoin('companies','companies.Id','=','gensetinventory.supplier')
			->leftJoin(DB::Raw('(SELECT Max(Id) as maxid, inventoryId from inventorypricehistory group by inventoryId) as max'),'max.inventoryId','=','gensetinventory.Id')
			->leftJoin('inventorypricehistory','inventorypricehistory.Id','=',DB::raw('max.maxid'))
			->select('gensetinventory.Id', 'gensetinventory.name', 'gensetinventory.type','gensetinventory.barcode','gensetinventory.model','inventorypricehistory.price','companies.Company_Name','gensetinventory.qty_balance','gensetinventory.status')
			->whereNotIn('gensetinventory.type',$categories)
			// ->whereRaw("gensetinventory_history.`created_at`>=str_to_date('".$start."','%d-%M-%Y') AND gensetinventory_history.`created_at`<=str_to_date('".$end."','%d-%M-%Y')")
			->get();
			}
			else
			{
				 $gensetinventory=DB::table('gensetinventory')
				// ->leftJoin('users','users.Id','=','gensetinventory.technicianId')
				 ->leftjoin('gensetinventory_history','gensetinventory_history.gensetinventoryId','=','gensetinventory.Id')
				->leftJoin('companies','companies.Id','=','gensetinventory.supplier')
				->leftJoin(DB::Raw('(SELECT Max(Id) as maxid, inventoryId from inventorypricehistory group by inventoryId) as max'),'max.inventoryId','=','gensetinventory.Id')
				->leftJoin('inventorypricehistory','inventorypricehistory.Id','=',DB::raw('max.maxid'))
				->select('gensetinventory.Id', 'gensetinventory.name', 'gensetinventory.type','gensetinventory.barcode','gensetinventory.model','inventorypricehistory.price','companies.Company_Name',DB::raw("SUM(gensetinventory_history.qty) as qty_balance"),'gensetinventory.status')
				->where('gensetinventory_history.branch','LIKE','%'.$branches.'%')
				->whereNotIn('gensetinventory.type',$categories)
				->groupBy('gensetinventory.Id')
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

		$gensetinventorymodel = DB::table('options')
		->select('Id','Option')
		->where('Table','Genset')
		->where('Field','Model')
		->get();

		$gensetinventorytype = DB::table('options')
		->select('Id','Option')
		->where('Table','Genset')
		->where('Field','Type')
		->get();


		return view ('gensetinventory', ['me' => $me, 'gensetinventory' => $gensetinventory, 'status' => $status, 'supplier' => $supplier,'type'=>$type, 'branch'=>$branch,'technician'=>$technician, 'gensetinventorytype'=>$gensetinventorytype, 'gensetinventorymodel'=>$gensetinventorymodel, 'currentbranch'=>$branches]);
	}

	public function branchtransfergetquantity(Request $request, $type = null)
	{
		$input = $request->all();

		if($type == null)
		{
			$balance = DB::table('gensetinventory_history')
			->select(DB::Raw('SUM(qty) as count'))
			->where('branch','=',$input['branch'])
			->where('gensetInventoryId','=',$input['id'])
			->get();
		}
		else if($type == "in")
		{
			$balance = DB::table('gensetinventory_history')
			->select(DB::Raw('SUM(qty) as count'))
			->where('branch','=',$input['in'])
			->where('gensetInventoryId','=',$input['id'])
			->get();
		}
		else
		{
			$balance = DB::table('gensetinventory_history')
			->select(DB::Raw('SUM(qty) as count'))
			->where('branch','=',$input['out'])
			->where('gensetInventoryId','=',$input['id'])
			->get();
		}

		return response()->json(['balance' => $balance]);
	} 

	public function gensetupdate(Request $request)
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
			$bal = DB::table('gensetinventory')
			->where('Id','=',$input['StockId'])
			->select('qty_balance')
			->first();

			$balance = $bal->qty_balance + $input['qty_in'];

			DB::table('gensetinventory')
			->where('Id','=',$input['StockId'])
			->update([
					'qty_balance' => $balance
			]);
			DB::table('gensetinventory_history')
			->insertGetId([
				'activity' => $input['Process'],
				'branch' => $input['branch'],
				'qty' => $input['qty_in'],
				'type' => "Stock-in",
				'gensetinventoryId' => $input['StockId'],
				'userId' => $me->UserId,
				'created_at' => Carbon::now(),
				'technicianId' => $input['technician']
			]);
		}
		else if($input['Process'] == "Stock Out")
		{
			$bal = DB::table('gensetinventory')
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
			DB::table('gensetinventory')
			->where('Id','=',$input['StockId'])
			->update([
					'qty_balance' => $balance
			]);
			DB::table('gensetinventory_history')
			->insertGetId([
				'activity' => $input['Process']." to ".$technician->Name,
				'branch' => $input['branch'],
				'qty' => "-".$input['qty_out'],
				'type' => "Stock-out",
				'gensetinventoryId' => $input['StockId'],
				'userId' => $me->UserId,
				'created_at' => Carbon::now(),
				'technicianId' => $input['technician']
			]);
		}
		else if($input['Process'] == "Stock Return")
		{
			$bal = DB::table('gensetinventory')
			->where('Id','=',$input['StockId'])
			->select('qty_balance')
			->first();

			$balance = $bal->qty_balance + $input['qty_return'];

			$technician = DB::table('users')
			->where('Id','=',$input['technician'])
			->select('Name')
			->first();
			DB::table('gensetinventory')
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

			DB::table('gensetinventory_history')
			->insertGetId([
				'activity' => $input['Process']." from ".$technician->Name,
				'branch' => $input['branch'],
				'qty' => $input['qty_return'],
				'type' => "Stock-return",
				'gensetinventoryId' => $input['StockId'],
				'userId' => $me->UserId,
				'created_at' => Carbon::now(),
				'technicianId' => $input['technician']
			]);
		}
		else if($input['Process'] == "Stock Out Branch")
		{
			// $balance = $input['qty_balance'] - $input['qty_branch_out'];
			// DB::table('gensetinventory')
			// ->where('Id','=',$input['StockId'])
			// ->update([
			// 		'qty_balance' => $balance
			// ]);
			$bal = DB::table('gensetinventory_history')
			->where('gensetInventoryId','=',$input['StockId'])
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

			DB::table('gensetinventory_history')
			->insertGetId([
				'activity' => $input['Process']." from ".$input['branch_out'],
				'branch' => $input['branch_in'],
				'qty' => $input['qty_branch_out'],
				'type' => "Stock-in",
				'gensetinventoryId' => $input['StockId'],
				'userId' => $me->UserId,
				'created_at' => Carbon::now(),
			]);
			DB::table('gensetinventory_history')
			->insertGetId([
				'activity' => $input['Process']." to ".$input['branch_in'],
				'branch' => $input['branch_out'],
				'qty' => "-".$input['qty_branch_out'],
				'type' => "Stock-out",
				'gensetinventoryId' => $input['StockId'],
				'userId' => $me->UserId,
				'created_at' => Carbon::now(),
			]);

		}
		return 1;
	}

	public function gensetdelete(Request $request)
	{
		$input = $request->all();
		DB::table('gensetinventory')
		->where('Id','=',$input['Id'])
		->delete();
		return 1;
	}
	public function gensetinventorydetails($id = null)
	{
		$me = (new CommonController)->get_current_user();

		$item = DB::table('gensetinventory')
		->leftJoin('companies','companies.Id','=','gensetinventory.supplier')
		->leftJoin(DB::Raw('(SELECT Max(Id) as maxid, inventoryId from inventorypricehistory group by inventoryId) as max'),'max.inventoryId','=','gensetinventory.Id')
		->leftJoin('inventorypricehistory','inventorypricehistory.Id','=',DB::raw('max.maxid'))
		->select('gensetinventory.*','companies.Company_Name','inventorypricehistory.price as latest_price')
		->where('gensetinventory.Id','=',$id)
		->get();

		$history =DB::table('gensetinventory_history')
		->leftjoin('gensetinventory', 'gensetinventory.Id', '=', 'gensetinventory_history.gensetInventoryId')
		// ->leftjoin('options', 'options.Id', '=', 'gensetinventory_history.branch')
		->leftJoin('users','users.Id','=','gensetinventory_history.userId')
		->select('gensetinventory_history.Id','gensetinventory_history.activity','gensetinventory_history.branch','gensetinventory_history.qty','users.Name','gensetinventory_history.created_at')
		->where('gensetinventory_history.gensetInventoryId','=',$id)
		->get();

		$balance = DB::table('gensetinventory_history')
		->leftjoin('gensetinventory', 'gensetinventory.Id', '=', 'gensetinventory_history.gensetInventoryId')
		->select('gensetinventory_history.Id','gensetinventory_history.branch',DB::raw('SUM(gensetinventory_history.qty)as total'))
		->where('gensetinventory_history.gensetInventoryId','=',$id)
		->groupBy('gensetinventory_history.branch')
		->get();
		// dd($balance);

		$supplier = DB::table('companies')
		->select('Id','Company_Name')
		->where('Supplier','=','Yes')
		->get();

		$image = DB::table('files')
		->select('Web_Path')
		->where('TargetId','=',$id)
		->where('Type','=','GensetInventory')
		->orderBy('Id','desc')
		->first();

		$qrcode = DB::table('files')
		->select('Web_Path')
		->where('TargetId','=',$id)
		->where('Type','=','GensetInventoryQRCode')
		->orderBy('Id','desc')
		->first();

		$store = DB::table('files')
		->select('Web_Path')
		->where('TargetId','=',$id)
		->where('Type','=','GensetInventoryStore')
		->orderBy('Id','desc')
		->first();

		$gensetinventorymodel = DB::table('options')
		->select('Id','Option')
		->where('Table','Genset')
		->where('Field','Model')
		->get();

		$gensetinventorytype = DB::table('options')
		->select('Id','Option')
		->where('Table','Genset')
		->where('Field','Type')
		->get();

		return view('gensetinventorydetails', ['me'=>$me,'item'=>$item,'history'=>$history,'balance'=>$balance,'id'=>$id,'supplier'=>$supplier,'image'=>$image,'qrcode'=>$qrcode,'store'=>$store,'gensetinventorytype'=>$gensetinventorytype,'gensetinventorymodel'=>$gensetinventorymodel]);
	}

	public function gensetinventoryhistory($branch,$id)
	{
		$me = (new CommonController)->get_current_user();

		$history =DB::table('gensetinventory_history')
		->leftjoin('gensetinventory', 'gensetinventory.Id', '=', 'gensetinventory_history.gensetInventoryId')
		// ->leftjoin('options', 'options.Id', '=', 'gensetinventory_history.branch')
		->leftJoin('users','users.Id','=','gensetinventory_history.userId')
		->select('gensetinventory_history.Id','gensetinventory_history.activity','gensetinventory_history.branch','gensetinventory_history.qty','users.Name','gensetinventory_history.created_at')
		->where('gensetinventory_history.gensetInventoryId','=',$id)
		->where('gensetinventory_history.branch','LIKE',"%".$branch."%")
		->get();

		return view('gensetinventoryhistory', ['me'=>$me,'history'=>$history]);
	}

	public function gensetinventoryedit(Request $request)
	{
		$input = $request->all();
		$me = (new CommonController)->get_current_user();
		if($input['item_name'] == "" || $input['type'] == ""|| $input['model'] == "" || $input['barcode'] == "" || $input['description'] == "")
		{
			return 0;
		}
		DB::table('gensetinventory')
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
			'rack_no' => $input['rack_no'],
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


		$filenames="";
        $attachmentUrl = null;
		 if(isset($input['image']))
        {
          if ($input['image'] != null || $input['image'] != "") {
          		$path = "/private/upload/GensetInventory/";
                $file = $input['image'];
                $destinationPath=public_path().$path;
                $extension = $file->getClientOriginalExtension();
                $originalName=$file->getClientOriginalName();
                $fileSize=$file->getSize();
                $fileName=time()."_".$extension;
                $upload_success = $file->move($destinationPath, $fileName);
                $insert=DB::table('files')->insertGetId(
                    ['Type' => "GensetInventory",
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
          		$path = "/private/upload/GensetInventoryQRCode/";
                $file = $input['qrcode'];
                $destinationPath=public_path().$path;
                $extension = $file->getClientOriginalExtension();
                $originalName=$file->getClientOriginalName();
                $fileSize=$file->getSize();
                $fileName=time()."_.".$extension;
                $upload_success = $file->move($destinationPath, $fileName);
                $insert=DB::table('files')->insertGetId(
                    ['Type' => "GensetInventoryQRCode",
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
          		$path = "/private/upload/GensetInventoryStore/";
                $file = $input['store'];
                $destinationPath=public_path().$path;
                $extension = $file->getClientOriginalExtension();
                $originalName=$file->getClientOriginalName();
                $fileSize=$file->getSize();
                $fileName=time()."_".$extension;
                $upload_success = $file->move($destinationPath, $fileName);
                $insert=DB::table('files')->insertGetId(
                    ['Type' => "GensetInventoryStore",
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

	public function gensetinventorycreate(Request $request)
	{
		$me = (new CommonController)->get_current_user();
		$id = DB::Table('gensetinventory')
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
			'stockin' => $request->balance
		]);

		DB::table('gensetinventory_history')
		->insert([
			'activity' => 'Stock In',
			'branch' => 'Store HQ',
			'qty' => $request->balance,
			'type' => "Stock-in",
			'gensetInventoryId' => $id,
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
			$cond = "gensetinventory.Id = ".$id;
		}

		$list = DB::table('inventorypricehistory')
		->leftjoin('gensetinventory','gensetinventory.Id','=','inventorypricehistory.inventoryId')
		->leftjoin('companies','companies.Id','=','inventorypricehistory.supplier')
		->leftjoin('users','users.Id','=','inventorypricehistory.userId')
		->select('inventorypricehistory.Id','gensetinventory.name','gensetinventory.barcode','companies.Company_Name','inventorypricehistory.price','inventorypricehistory.stockin','inventorypricehistory.stockout','users.Name as user','inventorypricehistory.created_at')
		->orderby('inventorypricehistory.inventoryId','ASC')
		->orderby('inventorypricehistory.Id','ASC')
		->whereRaw($cond)
		->get();

		$inventory = DB::table('gensetinventory')
		->select('Id','barcode')
		->whereNotIN('type',['GENSET','ATS','VEHICLE','TANK'])
		->get();

		return view('pricehistory',['me'=>$me,'list'=>$list,'inventory'=>$inventory,'id'=>$id]);
	}
}
