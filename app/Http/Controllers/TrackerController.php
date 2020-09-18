<?php namespace App\Http\Controllers;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\Facades\Mail;
use App\Http\Controllers\CommonController;
use Illuminate\Support\Facades\Validator;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\Redirect;
use Excel;



use Dompdf\Dompdf;
use File;
use Storage;
use Input;
use DateTime;
use PDO;
use Zipper;
use Carbon\Carbon;


class TrackerController extends Controller {

	public function __construct()
	{
		$this->middleware('auth');
	}

	/**
	 * Show the application dashboard to the user.
	 *
	 * @return Response
	 */




	 public function addtracker(Request $request)
   {
 		$me = (new CommonController)->get_current_user();

 		$input = $request->all();

 		//insert tracker name
 		$trackername = DB::table('trackertemplate')
 		->select('Tracker_Name')
 		->where('Tracker_Name', '=', $input["Tracker_Name"])
 		->first();

		$criteria1="";
		$criteria2="";
		$criteria3="";
		$criteria4="";
		$criteria5="";

		if($input["Column1"]!="")
		{

				$criteria1= "".$input["Column1"]." | ".$input["Condition1"]." | ".$input["Criteria1"];
		}

		if($input["Column2"]!="")
		{

				$criteria2= "".$input["Column2"]." | ".$input["Condition2"]." | ".$input["Criteria2"];
		}

		if($input["Column3"]!="")
		{

				$criteria3= "".$input["Column3"]." | ".$input["Condition3"]." | ".$input["Criteria3"];
		}

		if($input["Column4"]!="")
		{

				$criteria4= "".$input["Column4"]." | ".$input["Condition4"]." | ".$input["Criteria4"];
		}

		if($input["Column5"]!="")
		{

				$criteria5= "".$input["Column5"]." | ".$input["Condition5"]." | ".$input["Criteria5"];
		}

 		if ($trackername === null) {

 			$id= DB::table('trackertemplate')
 						->insertGetId(array(
 						'Tracker_Name' => $input["Tracker_Name"],
						'Criteria1' => $criteria1,
						'Criteria2' => $criteria2,
						'Criteria3' => $criteria3,
						'Criteria4' => $criteria4,
						'Criteria5' => $criteria5,
						'Operator1' => $input["Operator1"],
						'Operator2' => $input["Operator2"],
						'Operator3' => $input["Operator3"],
						'Operator4' => $input["Operator4"],
						'Combine'=>DB::raw('Tracker_Name'),
						'created_at'=>DB::raw('now()')
 					));
 			if ($id > 0){

 				DB::table('templateaccess')
 							->insertGetId(array(
 							'TrackerTemplateId' => $id,
 							'UserId' => $me->UserId
 						));

 				DB::table('templatewriteaccess')
 							->insertGetId(array(
 							'TrackerTemplateId' => $id,
 							'UserId' => $me->UserId
 						));

           for ($i=0; $i<count($input["Column_Name"]); $i++) {

 					// 	$input["Column_Name"][$i]=str_replace(' ','_',$input["Column_Name"][$i]);
					$input["Column_Name"][$i]=trim($input["Column_Name"][$i]);
					$input["Column_Name"][$i]=str_replace(".","",$input["Column_Name"][$i]);
					$input["Column_Name"][$i]=html_entity_decode($input["Column_Name"][$i]);

 						if (strlen($input["Column_Name"][$i])>0)
 						{
 							$index=$i+1;
 							if($input["Type"][$i]=="Textarea")
 							{
 								$tracker= DB::table('trackercolumn')
 		                        ->insert(array(
 		                        'TrackerTemplateID' => $id,
 		                        'Column_Name' => $input["Column_Name"][$i],
 		                        'Data_Type' => 'text',
 		                        'Type' => $input["Type"][$i],
 														'Sequence' => $index,
														'Color_Code' => str_replace("#","",$input["ColorCode"][$i])
 		                      ));
 							}
 							else {
 								$tracker= DB::table('trackercolumn')
 		                        ->insert(array(
 		                        'TrackerTemplateID' => $id,
 		                        'Column_Name' => $input["Column_Name"][$i],
 		                        'Data_Type' => 'text',
 		                        'Type' => $input["Type"][$i],
 														'Sequence' => $index,
														'Color_Code' => str_replace("#","",$input["ColorCode"][$i])
 		                      ));
 							}


 								//add column into tracker table
 		             $column_name_to_add=$input["Column_Name"][$i];

 		             $column = DB::select("
 		                 SELECT Column_Name
 		                       FROM INFORMATION_SCHEMA.COLUMNS
 		                      WHERE table_name = 'tracker'");

 		             $obj = (object) array('Column_Name' => $column_name_to_add);

 		             if (!in_array($obj, $column))
 		             {
 		               //not exist , create new column
 		               if (strlen($column_name_to_add)>0)
 		               {
 										 if($input["Type"][$i]=="Textarea")
 										 {
 											 Schema::table('tracker', function($table) use ($column_name_to_add) {
 												 $table->text($column_name_to_add);
 											});
 										 }
 										 else {
 											 Schema::table('tracker', function($table) use ($column_name_to_add) {

 												 $table->text($column_name_to_add);

 										 });

 										 }
 		               }

 									 if($input["Type"][$i]=="Dropdown")
 									 {

 										 DB::table('fieldproperties')
 												->insert(array(
 												'Table' => 'tracker',
 											 'Category' => 'Tracker',
 												'Field_Name' => $input["Column_Name"][$i],
 												'Data_Type' => 'Text',
 												'Field_Type' => 'Dropdown'
 											));

 									 }

 		             }
 		             else {
 		               //already exist, no need to create
 		             }
 						}

           }

         }
       }
       else{
         //already exist tracker
       }
       return 1;


     }

		 public function createnewrecord(Request $request)
 	  {

 			$me = (new CommonController)->get_current_user();

 			$input = $request->all();

			$trackerline = DB::table('tracker')
			->where('tracker.Id', '=', $input["TrackerId"])
			->first();

			if($trackerline)
			{
				$insert=DB::table('invoicelisting')->insertGetId(array(
					 'TrackerId' => $input["TrackerId"],
					 'Customer' => $trackerline->Customer,
					 'Year' => date("Y"),
					 'created_by' =>$me->UserId

				 ));

			}
			else {
				// code...
				return -1;
			}

 			return $insert;

 	   }

		 public function createnewcostingrecord(Request $request)
 	  {

 			$me = (new CommonController)->get_current_user();

 			$input = $request->all();

					$insert=DB::table('costing')->insertGetId(array(
						 'Date' => $input["Date"],
						 'Cost_Type' => $input["CostType"],
						 'Amount' => $input["Amount"],
						 'Remarks' => $input["Remarks"],
						 'created_by' =>$me->UserId,
						 'created_at' =>DB::raw('now()')

					 ));

			$this->calculatemanday($insert);

 			return $insert;

 	   }

		 public function calculatemanday($id)
		 {

			 //
			 $amountArr=Array();

				$temp=DB::table('costing')
				->select('Date','Amount')
				->where('Id',$id)
				->first();

				$m=date("M",strtotime($temp->Date));
				$d=date("d",strtotime($temp->Date));
				$y=date('Y',strtotime($temp->Date));

				$start=date('d-M-Y',strtotime('21'.'-'.$m.'-'.$y.' -1 Month'));
				$end=date('d-M-Y',strtotime('20'.'-'.$m.'-'.$y));
				array_push($amountArr,[
					'start'=>$start,
					'end'=>$end,
					'amount'=>$temp->Amount,
					'costid'=>$id
				]);

			$insertArr=Array();

			$all=DB::select("SELECT
			sum(t.total) as totalvisit
			FROM
			tracker
			inner JOIN
			(select timesheets.Site_Name,radius.Code,COUNT(DISTINCT concat(timesheets.Date,timesheets.Site_Name)) as total from `timesheets`
			inner join `radius` on `timesheets`.`Site_Name` = radius.Location_Name AND replace(timesheets.Code,' ','') like CONCAT('%', radius.Code ,'%')
			where str_to_date(timesheets.Date,'%d-%M-%Y')>=str_to_date('".$start."','%d-%M-%Y') AND str_to_date(timesheets.Date,'%d-%M-%Y')<=str_to_date('".$end."','%d-%M-%Y')
			and `timesheets`.`Site_Name` !='' and `timesheets`.`Time_In` !='' AND timesheets.Site_Name like '%(%)' and timesheets.Code!='' AND timesheets.Code not like '%speedfreak%' AND timesheets.Code not like '%meeting%' and timesheets.Code not like '%store%' and timesheets.Code not like '%Fabyard%' and timesheets.Code not like '%Office%' and timesheets.Code not like '%OTW%' group by timesheets.Site_Name,radius.Code) t and
			tracker.Incentive_Code like concat('%',t.Code,'%')");

			foreach($amountArr as $arr){

				$temp=DB::select("
				SELECT
					Round((t.total/".$all[0]->totalvisit."
					)*".$arr['amount'].",2) AS total,tracker.Id,t.total as VisitCount,
					".$all[0]->totalvisit." as totalvisit
				FROM
					tracker
						inner JOIN
					(select timesheets.Site_Name,radius.Code,COUNT(DISTINCT concat(timesheets.Date,timesheets.Site_Name)) as total from `timesheets`
					inner join `radius` on `timesheets`.`Site_Name` = radius.Location_Name AND replace(timesheets.Code,' ','') like CONCAT('%', radius.Code ,'%')
					 where str_to_date(timesheets.Date,'%d-%M-%Y')>=str_to_date('".$start."','%d-%M-%Y') AND str_to_date(timesheets.Date,'%d-%M-%Y')<=str_to_date('".$end."','%d-%M-%Y')
					 and `timesheets`.`Site_Name` !='' and `timesheets`.`Time_In` !='' AND timesheets.Site_Name like '%(%)' and timesheets.Code!='' AND timesheets.Code not like '%speedfreak%' AND timesheets.Code not like '%meeting%' and timesheets.Code not like '%store%' and timesheets.Code not like '%Fabyard%' and timesheets.Code not like '%Office%' and timesheets.Code not like '%OTW%' group by timesheets.Site_Name,radius.Code) t and
					 tracker.Incentive_Code like concat('%',t.Code,'%')

				");

				DB::Table('manday')->where('CostId',$arr['costid'])->delete();

				foreach($temp as $t){
					if($t->total>0)
					{
						array_push($insertArr,[
							'CostId'=>$arr['costid'],
							'Amount'=>$t->total,
							'TrackerId'=>$t->Id
						]);
					}

				}

				DB::table('manday')->insert($insertArr);
			}
			 //

		 }

		 public function removeitem(Request $request)
		 {

			 $me = (new CommonController)->get_current_user();

			 $input = $request->all();

			 $ids=explode(",",substr($input["Ids"],0,strlen($input["Ids"])-1));

				foreach ($ids as $id) {
					# code...

					$tracker = DB::table('tracker')
					->where('Id', '=',$id)
					->first();

					$details="Deleted=>".$id;

					DB::table('trackerupdate')
						 ->insert(array(
						 'Details' => $details,
						 'TrackerId' =>$id,
						 'Site_ID' =>$tracker->{'Site ID'},
						 'Site_Name' =>$tracker->{'Site Name'},
						 'Type' =>'Delete',
						 'UserId' =>$me->UserId
					 ));
				}

				DB::table('tracker')->whereIn('Id', $ids)->delete();

			 return 1;

			}

			public function removerecord(Request $request)
 		 {

 			 $me = (new CommonController)->get_current_user();

 			 $input = $request->all();

 			 $ids=explode(",",substr($input["Ids"],0,strlen($input["Ids"])-1));

 				DB::table('invoicelisting')->whereIn('Id', $ids)->delete();

 			 return 1;

 			}

			public function removecostingrecord(Request $request)
 		 {

 			 $me = (new CommonController)->get_current_user();

 			 $input = $request->all();

 			 $ids=explode(",",substr($input["Ids"],0,strlen($input["Ids"])-1));

 				DB::table('costing')->whereIn('Id', $ids)->delete();

				DB::Table('manday')->whereIn('CostId',$ids)->delete();

 			 return 1;

 			}

		public function updatetracker(Request $request)
	  {

				$me = (new CommonController)->get_current_user();

	      $input = $request->all();

				$input["Column"]=html_entity_decode($input["Column"]);

				DB::table('tracker')
				->where('Id', '=',$input["Id"])
				->update([$input["Column"]=>$input["Update"],
				'Manual_Update'=>date('d-M-Y'),
				'Updated_By'=>$me->Name]);

				$details="".$input["Column"]."=>".$input["Update"]."";

				$this->updatedependency($input["Column"],$input["Id"]);

				DB::table('trackerupdate')
					 ->insert(array(
					 'Details' => $details,
					 'TrackerId' =>$input["Id"],
					 'Type' =>'Update',
					 'UserId' =>$me->UserId
				 ));


	      return 1;

	    }

	    public function updatetask($col,$update,$id)
	    {
			$me = (new CommonController)->get_current_user();

	    	DB::table('tracker')
			->where('Id', '=',$id)
			->update([
				$col=>$update,
				'Manual_Update'=>date('d-M-Y'),
				'Updated_By'=>$me->Name
			]);

			//update tasks table and taskstatuses

			$targetsite = DB::table('tracker')
			->where('tracker.Id','=', $id)
			->first();

			if($targetsite){

				$completedtaskupdate = DB::table('tasks')
				->select('Id')
				->where('tasks.Current_Task', '=',$col)
				->get();

				if($completedtaskupdate){

					$arr=array();

					foreach ($completedtaskupdate as $item) {
						// code...
						array_push($arr,$item->Id);
					}

					$accept = DB::table('taskstatuses')
					->whereIn('taskstatuses.TaskId',$arr )
					->where('taskstatuses.Status','=','In Progress' )
					->first();
					if($accept){

						$result=DB::table('tasks')
						->where('tasks.Id','=',$accept->TaskId )
						->update(
							[
						 		'complete_date'=>date("d-M-Y"),
								'complete_time'=>Carbon::now()->format('H:i:s')
							]
						);

						if($result){
						 	$cur = array_search($accept->TaskId,$arr);
						 	foreach ($arr as $key => $value) {
						 		if($cur != $key)
						 		{
						 			DB::table('taskstatuses')->insert([
									'TaskId' => $value,
									'UserId' => 562,
									'Status' => 'Rejected',
									'Comment' => 'Accepted by others'
									]);
						 		}
						 	}
							DB::table('taskstatuses')->insert([
								'TaskId' => $accept->TaskId,
								'UserId' => $accept->UserId,
								'Status' => 'Completed'
							]);
						}
					}
				}
			}
	    }

		public function updatetrackerbatch(Request $request)
		{

			$me = (new CommonController)->get_current_user();

      		$input = $request->all();
// dd($input);
			for ($i=0; $i <sizeof($input["Id"]) ; $i++) {
				// code...

				$this->updatetask($input["Column"][$i],$input["Update"][$i],$input["Id"][$i]);

				// $input["Column"][$i]=html_entity_decode($input["Column"][$i]);



				//end of update task table and taskstatuses
				// prevent send notification on empty update?
				if (trim($input["Update"][$i] != '')) {
					$agingsubscribers = DB::table('agings')
					->select(
						DB::raw("CASE WHEN tracker.Site_Name='' THEN tracker.`Site Name` ELSE tracker.Site_Name END as 'Site_Name'"),
						'agingsubscribers.AgingId',
						'agings.Start_Date',
						'agings.End_Date',
						'agings.Threshold',
						'agingsubscribers.UserId',
						'users.Player_Id',
						'users.Id as UserId'
					)
					->where('Start_Date', $input["Column"][$i])
					->join('agingsubscribers', 'agingsubscribers.AgingId', '=', 'agings.Id')
					->join('users', 'users.Id', '=', 'agingsubscribers.UserId')
					->join('tracker', 'tracker.Id', '=', DB::raw($input["Id"][$i]))
					->get();
					if (count($agingsubscribers)) {
						$groups = [];

						// prepare groups
						foreach ($agingsubscribers as $subscriber) {

							if ($subscriber->Player_Id) {
								$groups[$subscriber->AgingId]['users'][] = $subscriber;
								$groups[$subscriber->AgingId]['playerids'][] = $subscriber->Player_Id;
							}

							$taskId = DB::table('tasks')->insertGetId([
		                        'Current_Task'  => $subscriber->End_Date,
		                        'Previous_Task' => $input["Column"][$i],
		                        'Previous_Task_Date' => $input["Update"][$i],
		                        'Site_Name'     => $subscriber->Site_Name,
		                        'Threshold'     => $subscriber->Threshold,
		                        'UserId' => $subscriber->UserId,
		                        'assign_by' => $me->UserId
		                    ]);

							DB::table('taskstatuses')->insert([
								'TaskId' => $taskId,
								'UserId' => $subscriber->UserId,
								'Status' => 'Assigned'
							]);
						}


						foreach ($groups as $group) {
							$playerids 	= $group['playerids'];
							$message 	= 'Task ' . $input["Column"][$i] .' completed. ' . current($group['users'])->End_Date . ' started.';
							$type 		= 'Task';
							$title  	= 'New Task Assigned';

							$this->sendNotification($playerids, $title, $message, $type);
						}
					}
				}

				$details="".$input["Column"][$i]."=>".$input["Update"][$i]."";

				$this->updatedependency($input["Column"][$i],$input["Id"][$i]);

				DB::table('trackerupdate')
				->insert(array(
					'Details' => $details,
					'TrackerId' =>$input["Id"][$i],
					'Type' =>'Update',
					'UserId' =>$me->UserId
			 	));

				//update ntp date based on site awards and survey precon cme yes not condition
				if($input["Column"][$i]=="Survey (Y/N)" || $input["Column"][$i]=="Site Award Date")
				{

					$trackerline= DB::table('tracker')
					->where('tracker.Id', '=',$input["Id"][$i])
					->first();

					if($trackerline->{'Site Award Date'} && strtoupper($trackerline->{'Survey (Y/N)'})=="YES")
					{
						DB::table('tracker')
						->where('Id', '=',$input["Id"][$i])
						->where('NTP Survey', '=',"")
						->update(['NTP Survey'=>$trackerline->{'Site Award Date'}]);
					}
					else if($trackerline->{'Site Award Date'} && strtoupper($trackerline->{'Survey (Y/N)'})=="NO")
					{
						DB::table('tracker')
						->where('Id', '=',$input["Id"][$i])
						->where('NTP Survey', '=',"")
						->update(['NTP Survey'=>'']);
					}
				}

				else if($input["Column"][$i]=="Precon (Y/N)" || $input["Column"][$i]=="Site Award Date")
				{
					$trackerline= DB::table('tracker')
					->where('tracker.Id', '=',$input["Id"][$i])
					->first();

					if($trackerline->{'Site Award Date'} && strtoupper($trackerline->{'Precon (Y/N)'})=="YES")
					{
						DB::table('tracker')
						->where('Id', '=',$input["Id"][$i])
						->where('NTP Precon', '=',"")
						->update(['NTP Precon'=>$trackerline->{'Site Award Date'}]);
					}
					else if($trackerline->{'Site Award Date'} && strtoupper($trackerline->{'Precon (Y/N)'})=="NO")
					{
						DB::table('tracker')
					 ->where('Id', '=',$input["Id"][$i])
					 ->where('NTP Precon', '=',"")
					 ->update(['NTP Precon'=>'']);
					}
				}

				else if($input["Column"][$i]=="CME (Y/N)" && strtoupper($input["Update"][$i])=="YES")
				{
					$trackerline= DB::table('tracker')
					->where('tracker.Id', '=',$input["Id"][$i])
					->first();

					if($trackerline->{'Site Award Date'} && strtoupper($trackerline->{'CME (Y/N)'})=="YES")
					{
						DB::table('tracker')
						->where('Id', '=',$input["Id"][$i])
						->where('NTP CME', '=',"")
						->update(['NTP CME'=>$trackerline->{'Site Award Date'}]);
					}
					else if($trackerline->{'Site Award Date'} && strtoupper($trackerline->{'CME (Y/N)'})=="NO")
					{
						DB::table('tracker')
						->where('Id', '=',$input["Id"][$i])
						->where('NTP CME', '=',"")
						->update(['NTP CME'=>'']);
					}
				}
				//update ntp date based on site awards and survey precon cme yes not condition

				//pull data from tracker po side to invoice listing po side
				if((str_contains(strtoupper($input["Column"][$i]), 'PO') && str_contains(strtoupper($input["Column"][$i]), 'NO')) ||
				(str_contains(strtoupper($input["Column"][$i]), 'PO') && str_contains(strtoupper($input["Column"][$i]), 'DATE')) ||
				(str_contains(strtoupper($input["Column"][$i]), 'PO') && str_contains(strtoupper($input["Column"][$i]), 'AMOUNT')) ||
				(str_contains(strtoupper($input["Column"][$i]), 'PO') && str_contains(strtoupper($input["Column"][$i]), 'VALUE'))
				)
				{

					$trackeritem= DB::table('tracker')
					->where('tracker.Id', '=',$input["Id"][$i])
					->first();

					if(starts_with(strtoupper($input["Column"][$i]), 'PO'))
					{
					 $milestone=substr($input["Column"][$i], 0, strpos($input["Column"][$i], '_'));

					 $milestone=str_replace("_","",$milestone);
					 $milestone=str_replace("-","",$milestone);
					 $milestone=trim($milestone);
					}
					else {
					 	// code...
						$milestone=substr($input["Column"][$i], 0, strpos($input["Column"][$i], 'PO'));

						$milestone=str_replace("_","",$milestone);
						$milestone=str_replace("-","",$milestone);
						$milestone=trim($milestone);
					}

					$invoiceitem = DB::table('invoicelisting')
					->where('TrackerId', '=',$input["Id"][$i])
					->where('PO_Milestone', '=',$milestone)
					->get();

					if($invoiceitem)
					{

					}
					else {
					 	// code...
						$insert=DB::table('invoicelisting')->insertGetId(array(
							'TrackerId' => $input["Id"][$i],
							'Customer' => $trackeritem->Customer,
							'Year' => date("Y"),
							'PO_Milestone'=>$milestone,
							'created_by' =>$me->UserId

						));
					}

					$updatecolumnname="";

					if(str_contains(strtoupper($input["Column"][$i]), 'PO') && str_contains(strtoupper($input["Column"][$i]), 'NO'))
					{

						$updatecolumnname='PO_No';

					}
					else if(str_contains(strtoupper($input["Column"][$i]), 'PO') && str_contains(strtoupper($input["Column"][$i]), 'DATE'))
					{

						$updatecolumnname='PO_Date';

					}
					else if(str_contains(strtoupper($input["Column"][$i]), 'PO') && str_contains(strtoupper($input["Column"][$i]), 'AMOUNT'))
					{

						$updatecolumnname='PO_Amount';

					}
					else if(str_contains(strtoupper($input["Column"][$i]), 'PO') && str_contains(strtoupper($input["Column"][$i]), 'VALUE'))
					{

						$updatecolumnname='PO_Amount';

					}

					if($updatecolumnname)
					{

						DB::table('invoicelisting')
						->where('TrackerId', '=',$input["Id"][$i])
						->where('PO_Milestone', '=',$milestone)
						->update([$updatecolumnname=>DB::raw("replace(replace('".$input["Update"][$i]."',',',''),'RM','')")]);
					}
				}

					//create radius

				$line = DB::table('tracker')
				->where('Id', '=',$input["Id"][$i])
				->first();

				$locationname="";

				if($line)
				{
				 	$split=explode(",",$line->Incentive_Code);

				 	foreach ($split as $s) {
				 		// code...
				 		$radius = DB::table('radius')
				 		->whereRaw('(Code="'.$s.'" or Code="")')
				 		->first();

						if($s)
						{
							if($radius)
					 		{
					 			if($input['Column'][$i] == "Closed Date")
					 			{
						 			DB::table('radius')
						 			->where('Id', '=',$radius->Id)
						 			->update([
						 				'Latitude' => $line->Latitude,
							 			'Longitude' => $line->Longitude,
							 			'Completion_Date'=>$line->{'Closed Date'},
										'Code' => $s
						 			]);
					 			}
					 			if($input['Column'][$i] == "CME Start Date")
					 			{
						 			DB::table('radius')
						 			->where('Id', '=',$radius->Id)
						 			->update([
							 			'Latitude' => $line->Latitude,
							 			'Longitude' => $line->Longitude,
							 			'Start_Date'=>$line->{'CME Start Date'},
										'Code' => $s
						 			]);
					 			}
					 		}
					 		else {
					 			// code...
					 			$insert=DB::table('radius')->insertGetId(
					 				[
						 				'Location_Name' => $locationname,
						 				'Latitude' => $line->Latitude,
						 				'Longitude' => $line->Longitude,
						 				'Code' => $s,
						 				'Start_Date'=>$line->{'CME Start Date'},
						 				'Completion_Date'=>$line->{'Closed Date'}
					 				]
					 			);
					 		}
						}
				 	}

					if(!$line->Incentive_Code)
					{

						$radius = DB::table('radius')
				 		->where('Location_Name','=',$locationname)
						->whereRaw('(Client="'.$line->{'Client Name'}.'" or Client="")')
				 		->first();

						if($radius)
						{

							DB::table('radius')
							->where('Id', '=',$radius->Id)
							->update([
								'Latitude' => $line->Latitude,
								'Longitude' => $line->Longitude,
								'Client'=>$line->{'Client Name'}
							]);
						}
						else {
							// code...
							$insert=DB::table('radius')->insertGetId(
								[
									'Client' => $line->{'Client Name'},
									'Location_Name' => $locationname,
									'Latitude' => $line->Latitude,
									'Longitude' => $line->Longitude
								]
							);
						}
					}

				 //end of create radius
			 	}

			 	//end of pull date from tracker po side to invoice listing po side
			} //for closing

      		return 1;
    	}

				public function updateinvoicelisting(Request $request)
			  {

						$me = (new CommonController)->get_current_user();

			      $input = $request->all();

						for ($i=0; $i <sizeof($input["Id"]) ; $i++) {

							// code...
							$input["Column"][$i]=html_entity_decode($input["Column"][$i]);

							DB::table('invoicelisting')
							->where('Id', '=',$input["Id"][$i])
							->update([$input["Column"][$i]=>$input["Update"][$i],
							'updated_at'=> DB::raw('now()')]);


							//pull invoice update to tracker
							if(str_contains(strtoupper($input["Column"][$i]), 'INVOICE_NO') ||
								str_contains(strtoupper($input["Column"][$i]), 'INVOICE_DATE') ||
								str_contains(strtoupper($input["Column"][$i]), 'INVOICE_AMOUNT'))
							{

								$invoiceitem = DB::table('invoicelisting')
								->where('Id', '=',$input["Id"][$i])
								->first();

								$trackeritem = DB::table('tracker')
								->where('Id', '=',$invoiceitem->TrackerId)
								->first();

								$updatecolumnname="";

								if(str_contains(strtoupper($input["Column"][$i]), 'INVOICE_NO'))
								{

									$columns = DB::table('trackercolumn')
									->where('Column_Name', '=', $invoiceitem->PO_Milestone."_Invoice No")
									->orWhere('Column_Name', '=', $invoiceitem->PO_Milestone." Invoice No")
									->orWhere('Column_Name', '=', $invoiceitem->PO_Milestone."_Inv No")
									->orWhere('Column_Name', '=', $invoiceitem->PO_Milestone." Inv No")
									->first();

									if($columns)
									{
										$updatecolumnname=$columns->Column_Name;
									}

								}
								else if(str_contains(strtoupper($input["Column"][$i]), 'INVOICE_DATE'))
								{
									$columns = DB::table('trackercolumn')
									->where('Column_Name', '=', $invoiceitem->PO_Milestone."_Invoice Date")
									->orWhere('Column_Name', '=', $invoiceitem->PO_Milestone." Invoice Date")
									->orWhere('Column_Name', '=', $invoiceitem->PO_Milestone."_Inv Date")
									->orWhere('Column_Name', '=', $invoiceitem->PO_Milestone." Inv Date")
									->first();

									if($columns)
									{
										$updatecolumnname=$columns->Column_Name;
									}

								}
								else if(str_contains(strtoupper($input["Column"][$i]), 'INVOICE_AMOUNT'))
								{
									$columns = DB::table('trackercolumn')
									->where('Column_Name', '=', $invoiceitem->PO_Milestone."_Invoice Amount")
									->orWhere('Column_Name', '=', $invoiceitem->PO_Milestone." Invoice Amount")
									->orWhere('Column_Name', '=', $invoiceitem->PO_Milestone."_Inv Amount")
									->orWhere('Column_Name', '=', $invoiceitem->PO_Milestone." Inv Amount")
									->orWhere('Column_Name', '=', $invoiceitem->PO_Milestone."_Invoice Value")
									->orWhere('Column_Name', '=', $invoiceitem->PO_Milestone." Invoice Value")
									->orWhere('Column_Name', '=', $invoiceitem->PO_Milestone."_Inv Value")
									->orWhere('Column_Name', '=', $invoiceitem->PO_Milestone." Inv Value")
									->first();

									if($columns)
									{
										$updatecolumnname=$columns->Column_Name;
									}

								}

								if($updatecolumnname)
								{

									DB::table('tracker')
									->where('Id', '=',$invoiceitem->TrackerId)
									->where($updatecolumnname, '=','')
									->update([$updatecolumnname=>DB::raw("replace(replace('".$input["Update"][$i]."',',',''),'RM','')")]);

								}
							}
							//end of pull invoice data to tracker
						}

			      return 1;

				}

				public function updatecosting(Request $request)
				{

					$me = (new CommonController)->get_current_user();

					$input = $request->all();
					$amountArr=Array();
					for ($i=0; $i <sizeof($input["Id"]) ; $i++) {

						$input["Column"][$i]=html_entity_decode($input["Column"][$i]);

						DB::table('costing')
						->where('Id', '=',$input["Id"][$i])
						->update([$input["Column"][$i]=>$input["Update"][$i],
						'updated_at'=> DB::raw('now()')]);

						$this->calculatemanday($input["Id"][$i]);
					}
					return 1;
				}

			public function fetchimage(Request $request)
			{

				$me = (new CommonController)->get_current_user();

	      $input = $request->all();

				$tracker = DB::table('tracker')
				->select('Site_Name')
				->where('tracker.Id', '=',$input["TrackerId"])
				->first();

				$SiteId=$tracker->Site_Name;

				$files = File::allFiles(public_path().'/private/upload/Site Document/'.$SiteId.'/');
				foreach ($files as $file)
				{
						$name=(string)$file;
						$originalName=basename($name);

						$name=str_replace(public_path(), "", $name);

						$file = DB::table('files')
						->where('TargetId', '=',$input["TrackerId"])
						->where('Document_Type', '=',$input["Type"])
						->where('File_Name', '=',$originalName)
						->get();

						if(!$file)
						{

							$insert=DB::table('files')->insertGetId(
								['Type' => "Tracker",
								 'UserId' => $me->UserId,
								 'TargetId' => $input["TrackerId"],
								 'File_Name' => $originalName,
								 'Document_Type' => $input["Type"],
								 'File_Size' => "100000",
								 'Web_Path' => $name
								]
							);

						}

				}

				return $insert;

			}

			public function assignments($name = null)
			{
				$me = (new CommonController)->get_current_user();

				$assignments = DB::table('assignments')
				->select('assignments.Status','assignments.Site_Name','assignments.Staff','assignments.Subcon','assignments.Due_Date','assignments.Completed_Date','assignments.Remarks','users.Name as Created_By','assignments.created_at','assignments.updated_at')
				->leftJoin('users', 'assignments.created_by', '=', 'users.Id')
				->orderBy('assignments.Status','desc')
				->orderBy(DB::raw('str_to_date(assignments.Due_Date,"%d-%M-%Y")'),'asc')
				->get();

				$staff= DB::table('users')
				->where('User_Type','!=','Contractor')
				->orderBy('Name','asc')
				->get();

				$contractor= DB::table('users')
				->where('User_Type','=','Contractor')
				->orderBy('Name','asc')
				->get();

		    return view('assignment', ['me' => $me,'assignments'=>$assignments,'staff'=>$staff,'contractor'=>$contractor,'name'=>$name]);

			}

			public function siteissue()
			{
				$me = (new CommonController)->get_current_user();

				$siteissue = DB::table('siteissue')
				->select('siteissue.Status','siteissue.Site_ID','siteissue.Site_Name','siteissue.Scope_Of_Work','siteissue.Issue_Description','siteissue.Person_In_Charge','siteissue.Date','siteissue.Time','siteissue.Remarks','siteissue.Solution','users.Name as Created_By','siteissue.created_at','siteissue.updated_at')
				->leftJoin('users', 'siteissue.created_by', '=', 'users.Id')
				->orderBy('siteissue.Status','desc')
				->get();

				$options= DB::table('options')
				->whereIn('Table', ["users","tracker"])
				->orderBy('Table','asc')
				->orderBy('Option','asc')
				->get();

		    return view('siteissue', ['me' => $me,'siteissue'=>$siteissue,'options'=>$options]);

			}

			public function updatetracker2(Request $request)
			{

					$me = (new CommonController)->get_current_user();

					$input = $request->all();

					$input["Ids"]=substr($input["Ids"],0,strlen($input["Ids"])-1);

					$ids=explode(",",$input["Ids"]);

					DB::table('tracker')
					->whereIn('Id', $ids)
					->update([$input["Column"]=>$input["Update"],
					'Manual_Update'=>date('d-M-Y'),
					'Updated_By'=>$me->Name]);

					foreach ($ids as $id) {
						# code...

						$details="".$input["Column"]."=>".$input["Update"]."";

						$this->updatedependency($input["Column"],$id);

						DB::table('trackerupdate')
							 ->insert(array(
							 'Details' => $details,
							 'TrackerId' =>$id,
							 'Type' =>'Update',
							 'UserId' =>$me->UserId
						 ));

					}

					return 1;

				}

				public function updatecolumn(Request $request)
			  {
			      $input = $request->all();

						$columns = DB::table('trackercolumn')
			      ->where('TrackerTemplateID', '=', $input["TrackerId"])
						->orderBy('Sequence','ASC')
			      ->get();

						for ($i=0; $i < count($columns); $i++) {
							# code...

							if($columns[$i]->Type!=$input["ColumnType"][$i])
							{
								DB::table('trackercolumn')
								->where('Id', '=',$input["ColumnId"][$i])
								->update(['Type'=>$input["ColumnType"][$i],
													'Color_Code'=>str_replace("#","",$input["ColorCode"][$i])

							]);

								if($input["ColumnType"][$i]=="Dropdown")
								{

									$col = DB::table('fieldproperties')
									->where('Table', '=', 'tracker')
									->where('Category', '=', 'Tracker')
									->where('Field_Name', '=', $columns[$i]->Column_Name)
									->where('Field_Type', '=', 'Dropdown')
									->get();

									if(!$col)
									{

										DB::table('fieldproperties')
											 ->insert(array(
											 'Table' => 'tracker',
											'Category' => 'Tracker',
											 'Field_Name' => $columns[$i]->Column_Name,
											 'Data_Type' => 'Text',
											 'Field_Type' => 'Dropdown'
										 ));

									}

								}

							}
							else {
								// code...
								DB::table('trackercolumn')
								->where('Id', '=',$input["ColumnId"][$i])
								->update(['Color_Code'=>str_replace("#","",$input["ColorCode"][$i])

							]);
							}
						}

						DB::delete("Delete from trackercolumn where Id IN(".$input["Ids"].")");

						return 1;

			}

	public function assigntask(Request $request)
	{
			$me = (new CommonController)->get_current_user();

			$input = $request->all();

			$id= DB::table('assignments')
									->insertGetId(array(
									'Site_Name' => $input["Site_Name"],
									'Remarks' => $input["Remarks"],
									'Staff' => $input["Staff"],
									'Subcon' => $input["Contractor"],
									'Due_Date' => $input["Due_Date"],
									'Status' => "Open",
									'created_by' => $me->UserId,
									'created_at' => DB::raw('now()')
								));


			return $id;

}

public function renametracker(Request $request)
{
		$input = $request->all();

		$criteria1="";
		$criteria2="";
		$criteria3="";
		$criteria4="";
		$criteria5="";

		if($input["Column1"]!="")
		{

				$criteria1= "".$input["Column1"]." | ".$input["Condition1"]." | ".$input["Criteria1"];
		}

		if($input["Column2"]!="")
		{

				$criteria2= "".$input["Column2"]." | ".$input["Condition2"]." | ".$input["Criteria2"];
		}

		if($input["Column3"]!="")
		{

				$criteria3= "".$input["Column3"]." | ".$input["Condition3"]." | ".$input["Criteria3"];
		}

		if($input["Column4"]!="")
		{

				$criteria4= "".$input["Column4"]." | ".$input["Condition4"]." | ".$input["Criteria4"];
		}

		if($input["Column5"]!="")
		{

				$criteria5= "".$input["Column5"]." | ".$input["Condition5"]." | ".$input["Criteria5"];
		}

		$blsuccess=DB::table('trackertemplate')
		->where('Id', '=',$input["TrackerId"])
		->update(['Tracker_Name'=>$input["TrackerName"],
		'Combine'=>DB::raw('Tracker_Name'),
		'Criteria1' => $criteria1,
		'Criteria2' => $criteria2,
		'Criteria3' => $criteria3,
		'Criteria4' => $criteria4,
		'Criteria5' => $criteria5,
		'Operator1' => $input["Operator1"],
		'Operator2' => $input["Operator2"],
		'Operator3' => $input["Operator3"],
		'Operator4' => $input["Operator4"]
		]);

		return 1;


	}

	public function addcolumn(Request $request)
	{
			$input = $request->all();

			$id=$input["TrackerId"];

			for ($i=0; $i<count($input["Column_Name"]); $i++) {

				// $input["Column_Name"][$i]=str_replace(' ','_',$input["Column_Name"][$i]);
				$input["Column_Name"][$i]=trim($input["Column_Name"][$i]);
				$input["Column_Name"][$i]=html_entity_decode($input["Column_Name"][$i]);
				$input["Column_Name"][$i]=str_replace(".","",$input["Column_Name"][$i]);
				if (strlen($input["Column_Name"][$i])>0)
				{

					$columns = DB::table('trackercolumn')
					->where('TrackerTemplateID', '=', $id)
					->where('Column_Name', '=', $input["Column_Name"][$i])
					->get();

					if($columns)
					{
						//already exisit
					}
					else {
						# code...
						//not exist
						if($input["Type"][$i]=="Textarea")
						{
							$tracker= DB::table('trackercolumn')
													->insert(array(
													'TrackerTemplateID' => $id,
													'Column_Name' => $input["Column_Name"][$i],
													'Data_Type' => 'text',
													'Type' => $input["Type"][$i],
													'Sequence' => 9999,
													'Color_Code' => str_replace("#","",$input["ColorCode"][$i]),
												));
						}
						else {
							$tracker= DB::table('trackercolumn')
													->insert(array(
													'TrackerTemplateID' => $id,
													'Column_Name' => $input["Column_Name"][$i],
													'Data_Type' => 'text',
													'Type' => $input["Type"][$i],
													'Sequence' => 9999,
													'Color_Code' => str_replace("#","",$input["ColorCode"][$i]),
												));
						}

							//add column into tracker table
							 $column_name_to_add=$input["Column_Name"][$i];

							 $column = DB::select("
									 SELECT Column_Name
												 FROM INFORMATION_SCHEMA.COLUMNS
												WHERE table_name = 'tracker'
													AND COLUMN_Name='".$input["Column_Name"][$i]."'");

							 if (!$column)
							 {
								 //not exist , create new column
								 if (strlen($column_name_to_add)>0)
								 {

									 if($input["Type"][$i]=="Textarea")
									 {
										 DB::statement('ALTER TABLE `tracker` add `'.$column_name_to_add.'` text not null');


										//  Schema::table('tracker', function($table) use ($column_name_to_add) {
										// 	 $table->text($column_name_to_add);
										// });
									 }
									 else {
										 DB::statement('ALTER TABLE `tracker` add `'.$column_name_to_add.'` text not null');
										//  Schema::table('tracker', function($table) use ($column_name_to_add) {
									 //
										// 	 $table->text($column_name_to_add);
									 //
									 // });

									 }

								 }

								 if($input["Type"][$i]=="Dropdown")
								 {

									 DB::table('fieldproperties')
											->insert(array(
											'Table' => 'tracker',
										 'Category' => 'Tracker',
											'Field_Name' => $input["Column_Name"][$i],
											'Data_Type' => 'Text',
											'Field_Type' => 'Dropdown'
										));

								 }

							 }
							 else {
								 //already exist, no need to create
								 if($input["Type"][$i]=="Dropdown")
								 {

									 DB::table('fieldproperties')
											->insert(array(
											'Table' => 'tracker',
										 'Category' => 'Tracker',
											'Field_Name' => $input["Column_Name"][$i],
											'Data_Type' => 'Text',
											'Field_Type' => 'Dropdown'
										));

								 }
							 }

					}


				}

			}

			return 1;
}

	public function reordercolumn(Request $request)
	{
			$input = $request->all();

			$sequence= $input["sequence"];
			$idline=explode("&",$sequence);
			$blsuccess=0;

			$index=1;
			foreach($idline as $line)
			{

				$iditem=explode("=",$line);

				DB::table('trackercolumn')
				->where('Id', '=',$iditem[1])
				->update(['Sequence'=>$index]);

				$index++;
			}

			return 1;
}

  public function trackerview($trackerid = null, $condition = null)
  {
    	$me = (new CommonController)->get_current_user();

			$today = date('d-M-Y', strtotime('today'));

  		if ($trackerid==null)
  		{
        $trackerid=0;
  		}

			$trackerlist = DB::table('trackertemplate')
			->select('trackertemplate.Tracker_Name','trackertemplate.Id')
			->whereRaw('trackertemplate.Id in (SELECT TrackerTemplateId from templateaccess where UserId='.$me->UserId.')')
			->orderBy('trackertemplate.Id','ASC')
			->get();



			if($trackerid==0)
			{
				if (count($trackerlist)>0)
				{
					$trackerid=$trackerlist[0]->Id;
				}
			}

      $trackercolumns = DB::table('trackercolumn')
  		->select()
  		->where('trackercolumn.TrackerTemplateID','=',$trackerid)
			->orderBy('trackercolumn.Sequence','ASC')
  		->get();

			$columns="";
			$arrPOColumn=array();
			$arrInvoiceColumn=array();

      foreach($trackercolumns as $key => $quote){
  			$r[]=$quote->Column_Name;
  			$columns = "`".implode("`,`", $r)."`";

				if($quote->Type=="PO List")
				{
					array_push($arrPOColumn,$quote->Column_Name);
				}
				if($quote->Type=="Invoice List")
				{
					array_push($arrInvoiceColumn,$quote->Column_Name);

				}
  		}

			$columns="`Id`,".$columns;
			$columns=rtrim($columns,",");

			// dd($condition);

			if($condition!="")
			{
				$trackerview = DB::table('tracker')
	      ->select(DB::raw($columns))
				->whereRaw($condition)
	      ->get();

			}
			else {
				$trackerview = DB::table('tracker')
	      ->select(DB::raw($columns))
	      ->get();
			}

			// dd($condition);


			$allavailablecolumns= DB::table('trackercolumn')
      ->select(DB::raw('DISTINCT (Column_Name) As Col'))
			->orderBy('Column_Name')
      ->get();

			$poitems = DB::table('po')
			->select('po.Id','po.PO_No','po.Shipment_Num','po.PO_Line_No','po.Item_Description','po.Site_ID','po.Site_Name')
			->orderBy('po.PO_No','ASC')
			->get();

			$options= DB::table('options')
			->whereIn('Table', ["users","tracker"])
			->orderBy('Table','asc')
			->orderBy('Option','asc')
			->get();

			$users= DB::table('users')
			->orderBy('Name','asc')
			->get();

			$invoices = DB::table('invoices')
			->select('invoices.Id','invoices.Invoice_No','invoices.Company','invoices.Invoice_Type','invoices.Invoice_Date','invoices.Invoice_Description','invoices.Invoice_Amount','invoices.Invoice_Status')
			->orderBy('invoices.Invoice_No','ASC')
			->get();

			$trackername = DB::table("trackertemplate")
			->where('trackertemplate.Id','=',$trackerid)
			->get();

			$leaves = DB::table('leaves')
			->leftJoin( DB::raw('(select Max(Id) as maxid,LeaveId from leavestatuses Group By LeaveId) as max'), 'max.LeaveId', '=', 'leaves.Id')
			->leftJoin('leavestatuses', 'leavestatuses.Id', '=', DB::raw('max.`maxid`'))
			->leftJoin('users as applicant', 'leaves.UserId', '=', 'applicant.Id')
			->leftJoin('users as approver', 'leavestatuses.UserId', '=', 'approver.Id')
			->select('applicant.Name','leaves.Leave_Type','leaves.Start_Date','leaves.End_Date','leavestatuses.Leave_Status as Status')
			->where(DB::raw('str_to_date(leaves.Start_Date,"%d-%M-%Y")'), '<=', DB::raw('str_to_date("'.$today.'","%d-%M-%Y")'))
			->where(DB::raw('str_to_date(leaves.End_Date,"%d-%M-%Y")'), '>=', DB::raw('str_to_date("'.$today.'","%d-%M-%Y")'))
			->orderBy('leaves.Id','desc')
			->get();

			$timesheetdetail = DB::table('timesheets')
			->select('users.Name','timesheets.Check_In_Type','timesheets.Time_In','timesheets.Time_Out')
			->leftJoin('users','users.Id','=','timesheets.UserId')
			->leftJoin( DB::raw('(select Max(Id) as maxid,TargetId from files where Type="Timesheet" Group By TargetId) as maxfile'), 'maxfile.TargetId', '=', 'timesheets.Id')
			->leftJoin('files', 'files.Id', '=', DB::raw('maxfile.`maxid` and files.`Type`="Timesheet"'))
			->leftJoin( DB::raw('(select Max(Id) as maxid,TimesheetId from timesheetstatuses Group By TimesheetId) as max'), 'max.TimesheetId', '=', 'timesheets.Id')
			->leftJoin('timesheetstatuses', 'timesheetstatuses.Id', '=', DB::raw('max.`maxid`'))
			->leftJoin('users as approver', 'timesheetstatuses.UserId', '=', 'approver.Id')
			->where(DB::raw('str_to_date(timesheets.Date,"%d-%M-%Y")'), '=', DB::raw('str_to_date("'.$today.'","%d-%M-%Y")'))
			->orderBy('timesheets.Id','desc')
			->get();

			$lastedit = DB::table('trackerupdate')
			->select('trackerupdate.Updated_At','users.Name')
			->leftJoin('users', 'users.Id', '=', 'trackerupdate.UserId')
			->orderBy('trackerupdate.Id','DESC')
			->first();

			$optionkey = DB::table('trackercolumn')
  		->select('trackercolumn.Column_Name')
  		->where('trackercolumn.TrackerTemplateID','=',$trackerid)
			->where('trackercolumn.Type','=','Dropdown')
			->orderBy('trackercolumn.Sequence','ASC')
  		->get();

			$optionvalue= DB::table('options')
			->whereIn('Table', ["users","tracker"])
			->orderBy('Table','asc')
			->orderBy('Option','asc')
			->get();

			//dd($optionvalue);

      return view('projecttracker',['me' => $me,'options'=>$options,'users'=>$users,'poitems'=>$poitems,'invoices'=>$invoices,'trackerid'=>$trackerid, 'condition'=>$condition, 'trackerlist' => $trackerlist, 'trackerview' => $trackerview, 'columns'=>$columns,
			'trackercolumns' => $trackercolumns, 'allavailablecolumns' =>$allavailablecolumns, 'trackername'=> $trackername, 'leaves'=>$leaves, 'timesheetdetail'=>$timesheetdetail, 'lastedit'=>$lastedit, 'optionkey'=>$optionkey, 'optionvalue'=>$optionvalue]);

  }

	public function handsontable($trackerid = null, $condition = null)
	{
		$me = (new CommonController)->get_current_user();

		$today = date('d-M-Y', strtotime('today'));

		if ($trackerid==null)
		{
			$trackerid=0;
		}

			$trackerlist = DB::table('trackertemplate')
			->select('trackertemplate.Tracker_Name','trackertemplate.Id')
			->whereRaw('trackertemplate.Id in (SELECT TrackerTemplateId from templateaccess where UserId='.$me->UserId.')')
			->orderBy('trackertemplate.Id','ASC')
			->get();

		if($trackerid==0)
		{
			if (count($trackerlist)>0)
			{
				$trackerid=$trackerlist[0]->Id;
			}
		}

		$current=DB::table('trackertemplate')
		->where('trackertemplate.Id','=',$trackerid)
		->first();

		$criteria="";
		$cri1=array();
		$cri2=array();
		$cri3=array();
		$cri4=array();
		$cri5=array();

		if($current)
		{

			if($current->Criteria1)
			{
				$cri1=explode(" | ",$current->Criteria1);
				if(!$current->Operator1)
				{
					$current->Operator1="AND";
				}
				array_push($cri1,$current->Operator1);

				if($cri1[1]=="like" or $cri1[1]=="not like")
				{
					$criteria.="`".$cri1[0]."`".$cri1[1]."'%".$cri1[2]."%' ".$current->Operator1." ";
				}
				elseif($cri1[1]=="in")
				{
					$criteria.="`".$cri1[0]."`".$cri1[1]."('','".str_replace(",","','",$cri1[2])."') ".$current->Operator1." ";

				}
				elseif($cri1[1]=="not in")
				{
					$criteria.="`".$cri1[0]."`".$cri1[1]."('".str_replace(",","','",$cri1[2])."') ".$current->Operator1." ";

				}
				else
				{
					$criteria.="`".$cri1[0]."`".$cri1[1]."'".$cri1[2]."' ".$current->Operator1." ";
				}

			}

			if($current->Criteria2)
			{
				$cri2=explode(" | ",$current->Criteria2);
				if(!$current->Operator2)
				{
					$current->Operator2="AND";
				}
				array_push($cri2,$current->Operator2);

				if($cri2[1]=="like" or $cri2[1]=="not like")
				{
					$criteria.="`".$cri2[0]."`".$cri2[1]."'%".$cri2[2]."%' ".$current->Operator2." ";
				}
				elseif($cri2[1]=="in")
				{
					$criteria.="`".$cri2[0]."`".$cri2[1]."('','".str_replace(",","','",$cri2[2])."') ".$current->Operator2." ";

				}
				elseif($cri2[1]=="not in")
				{
					$criteria.="`".$cri2[0]."`".$cri2[1]."('".str_replace(",","','",$cri2[2])."') ".$current->Operator2." ";

				}
				else
				{
					$criteria.="`".$cri2[0]."`".$cri2[1]."'".$cri2[2]."' ".$current->Operator2." ";
				}

			}

			if($current->Criteria3)
			{
				$cri3=explode(" | ",$current->Criteria3);
				if(!$current->Operator3)
				{
					$current->Operator3="AND";
				}
				array_push($cri3,$current->Operator3);

				if($cri3[1]=="like" or $cri3[1]=="not like")
				{
					$criteria.="`".$cri3[0]."`".$cri3[1]."'%".$cri3[2]."%' ".$current->Operator3." ";
				}
				elseif($cri3[1]=="in")
				{
					$criteria.="`".$cri3[0]."`".$cri3[1]."('','".str_replace(",","','",$cri3[2])."') ".$current->Operator3." ";

				}
				elseif($cri3[1]=="not in")
				{
					$criteria.="`".$cri3[0]."`".$cri3[1]."('".str_replace(",","','",$cri3[2])."') ".$current->Operator3." ";

				}
				else
				{
					$criteria.="`".$cri3[0]."`".$cri3[1]."'".$cri3[2]."' ".$current->Operator3." ";
				}

			}

			if($current->Criteria4)
			{
				$cri4=explode(" | ",$current->Criteria4);
				if(!$current->Operator4)
				{
					$current->Operator4="AND";
				}
				array_push($cri4,$current->Operator4);

				if($cri4[1]=="like" or $cri4[1]=="not like")
				{
					$criteria.="`".$cri4[0]."`".$cri4[1]."'%".$cri4[2]."%' ".$current->Operator4." ";
				}
				elseif($cri4[1]=="in")
				{
					$criteria.="`".$cri4[0]."`".$cri4[1]."('','".str_replace(",","','",$cri4[2])."') ".$current->Operator4." ";

				}
				elseif($cri4[1]=="not in")
				{
					$criteria.="`".$cri4[0]."`".$cri4[1]."('".str_replace(",","','",$cri4[2])."') ".$current->Operator4." ";

				}
				else
				{
					$criteria.="`".$cri4[0]."`".$cri4[1]."'".$cri4[2]."' ".$current->Operator4." ";
				}

			}

			if($current->Criteria5)
			{
				$cri5=explode(" | ",$current->Criteria5);

				if($cri5[1]=="like" or $cri5[1]=="not like")
				{
					$criteria.="`".$cri5[0]."`".$cri5[1]."'%".$cri5[2]."%' AND ";
				}
				elseif($cri5[1]=="in" )
				{
					$criteria.="`".$cri5[0]."`".$cri5[1]."('','".str_replace(",","','",$cri5[2])."') AND ";

				}
				elseif($cri5[1]=="not in")
				{
					$criteria.="`".$cri5[0]."`".$cri5[1]."('".str_replace(",","','",$cri5[2])."') AND ";

				}
				else
				{
					$criteria.="`".$cri5[0]."`".$cri5[1]."'".$cri5[2]."' AND ";
				}

			}

		}

		$criteria=substr($criteria,0,strlen($criteria)-5);

		if(!$criteria)
		{
			$criteria="1";
		}

		$trackerwriteaccess = DB::table('templatewriteaccess')
		->leftJoin('users', 'templatewriteaccess.UserId', '=', 'users.Id')
		->where('templatewriteaccess.TrackerTemplateId','=',$trackerid)
		->where('templatewriteaccess.UserId','=',$me->UserId)
		->first();

		$trackerdeleteaccess = DB::table('templatedeleteaccess')
		->leftJoin('users', 'templatedeleteaccess.UserId', '=', 'users.Id')
		->where('templatedeleteaccess.TrackerTemplateId','=',$trackerid)
		->where('templatedeleteaccess.UserId','=',$me->UserId)
		->first();

		$trackercolumns = DB::table('trackercolumn')
		->select()
		->where('trackercolumn.TrackerTemplateID','=',$trackerid)
		->orderBy('trackercolumn.Sequence','ASC')
		->get();

		$allavailablecolumns= DB::table('trackercolumn')
		->select(DB::raw('DISTINCT (Column_Name) As Col'))
		->orderBy('Column_Name')
		->get();

		$colorcodes = DB::table('trackercolumn')
		->distinct('Color_Code')
		->select('Color_Code','Column_Name')
		->where('trackercolumn.TrackerTemplateID','=',$trackerid)
		->orderBy('trackercolumn.Sequence','ASC')
		->get();

		// dd($trackercolumns);

		$columns="";
		$arrPOColumn=array();
		$arrInvoiceColumn=array();

		foreach($trackercolumns as $key => $quote){


			if($quote->Column_Name=="MR_Budget")
			{
				$columns=$columns.'(SELECT SUM(Total) FROM material
				inner join (select Max(Id) as maxmrid from material group by TrackerId) as maxmr on maxmr.maxmrid=material.Id
				left join (select Max(Id) as maxid,MaterialId from materialstatus group by MaterialId) as max on max.MaterialId=maxmr.maxmrid
				left join materialstatus on materialstatus.Id=max.maxid
				 WHERE material.TrackerId=tracker.Id and materialstatus.Status!="Recalled" GROUP BY material.TrackerId) as `'.$quote->Column_Name."`,";

			}
			else if($quote->Column_Name=="Accumulated_HDD")
			{
				$columns=$columns.'(SELECT SUM(HDD) FROM `fibrelog` WHERE fibrelog.trackerid = tracker.Id GROUP BY trackerid) as `'.$quote->Column_Name."`,";

			}
			else if($quote->Column_Name=="Accumulated_GV")
			{
				$columns=$columns.'(SELECT SUM(GV) FROM `fibrelog` WHERE fibrelog.trackerid = tracker.Id GROUP BY trackerid) as `'.$quote->Column_Name."`,";

			}
			else if($quote->Column_Name=="Accumulated_MH")
			{
				$columns=$columns.'(SELECT SUM(MH) FROM `fibrelog` WHERE fibrelog.trackerid = tracker.Id GROUP BY trackerid) as `'.$quote->Column_Name."`,";

			}
			else if($quote->Column_Name=="Accumulated_Poles")
			{
				$columns=$columns.'(SELECT SUM(Poles) FROM `fibrelog` WHERE fibrelog.trackerid = tracker.Id GROUP BY trackerid) as `'.$quote->Column_Name."`,";

			}
			else if($quote->Column_Name=="Accumulated_Subduct")
			{
				$columns=$columns.'(SELECT SUM(Subduct) FROM `fibrelog` WHERE fibrelog.trackerid = tracker.Id GROUP BY trackerid) as `'.$quote->Column_Name."`,";

			}
			else if($quote->Column_Name=="Accumulated_Cable")
			{
				$columns=$columns.'(SELECT SUM(Cables) FROM `fibrelog` WHERE fibrelog.trackerid = tracker.Id GROUP BY trackerid) as `'.$quote->Column_Name."`,";

			}
			else if($quote->Column_Name=="Overall_Progress_%")
			{
				$columns=$columns.'format(((SELECT SUM(HDD+GV+MH+Poles+Subduct+Cables) FROM `fibrelog` WHERE fibrelog.trackerid = tracker.Id GROUP BY trackerid))/((SELECT SUM(Total_HDD+Total_GV+Total_MH+Total_Poles+Total_Subduct+Total_Cable) FROM `tracker` a WHERE a.Id = tracker.Id GROUP BY a.Id))*100,2) as `'.$quote->Column_Name."`,";

			}
			else if(strtoupper($quote->Column_Name)=="INVOICE AMOUNT")
			{
				$columns=$columns.'(SELECT sum(`1st Claim Invoice Amount`+`2nd Claim Invoice Amount`+`3rd Claim Invoice Amount`+`Retention Invoice Amount`) FROM `tracker` a WHERE a.Id=tracker.Id GROUP BY a.Id) as `'.$quote->Column_Name."`,";
			}
			else if($quote->Column_Name=="Pending_Invoice")
			{
				$columns=$columns.'(SELECT COUNT(invoice) FROM `salesorder` WHERE invoice = 0 AND salesorder.trackerid = tracker.Id GROUP BY salesorder.trackerid) as `'.$quote->Column_Name."`,";
			}
			else if($quote->Column_Name=="PO Amount")
			{
				$columns=$columns.'tracker.`'.$quote->Column_Name."`,";
			}
			else if($quote->Column_Name=="Invoice Amount")
			{
				$columns=$columns.'(SELECT SUM(total_amount) FROM `salesorder` WHERE salesorder.invoice_number != "" AND salesorder.trackerid = tracker.Id GROUP BY trackerid) as `'.$quote->Column_Name."`,";
			}
			else if($quote->Column_Name=="Off Hire Date")
			{
				$columns=$columns.'(select `offhire_date` from `deliveryform`
					left join (SELECT MAX(Id) as maxid,salesorderid from deliveryform GROUP BY salesorderid) as max on `deliveryform`.`Id` = max.`maxid`
					left join (SELECT MAX(Id) as maxid,trackerid FROM salesorder GROUP BY trackerid ) as max2 on max2.`maxid` = max.`salesorderid`
					WHERE tracker.Id = max2.trackerid) as `'.$quote->Column_Name."`,";
			}
			else if($quote->Column_Name=="JDNI")
			{
				$columns=$columns.'(SELECT sum(total_amount) FROM `salesorder` where invoice_number = "" AND salesorder.trackerid = tracker.Id group by salesorder.trackerid) as `'.$quote->Column_Name."`,";
			}
			else if($quote->Column_Name=="Latest_Invoice_Status")
			{
				$columns=$columns.'(SELECT salesorder.invoice FROM tracker a
				left join (select Max(salesorder.Id) as maxid,trackerid from salesorder GROUP BY trackerid) as max on max.trackerid=a.Id
                left join salesorder on salesorder.Id = max.maxid
            	WHERE a.Id = tracker.Id) as `'.$quote->Column_Name."`,";
           	}
			else if($quote->Column_Name=="MR_Rev_Count")
			{
				$columns=$columns.'(SELECT case when material.MR_No like "%_rev%" then CAST(right(material.MR_No,1) as UNSIGNED) else 0 end from material
				inner join (select Max(Id) as maxmrid from material group by TrackerId) as maxmr on maxmr.maxmrid=material.Id
				where material.TrackerId=tracker.Id) as `'.$quote->Column_Name."`,";
			}
			else if($quote->Column_Name=="Manday")
			{
				$columns=$columns.'(select
							Format(SUM(Amount),2) from manday where manday.TrackerId=tracker.Id) as `'.$quote->Column_Name."`,";
			}
			else if($quote->Column_Name=="Incentive")
			{
				$columns=$columns.'(select SUM(Case when (SELECT COUNT(distinct tt.Date) FROM timesheets tt WHERE tt.Code!="" and tt.Site_Name like "%(%" AND tt.Site_Name=t.Site_Name AND replace(tt.Code," ","")=replace(t.Code," ","") AND str_to_date(tt.Date,"%d-%M-%Y") Between str_to_date(radius.Start_Date,"%d-%M-%Y") and str_to_date(radius.Completion_Date,"%d-%M-%Y"))>=scopeofwork.KPI+4 then Incentive_5
				when (SELECT COUNT(distinct tt.Date) FROM timesheets tt WHERE tt.Code!="" AND tt.Site_Name like "%(%" AND tt.Site_Name=t.Site_Name AND replace(tt.Code," ","")=replace(t.Code," ","") AND str_to_date(tt.Date,"%d-%M-%Y") Between str_to_date(radius.Start_Date,"%d-%M-%Y") and str_to_date(radius.Completion_Date,"%d-%M-%Y"))>=scopeofwork.KPI+3 then Incentive_4
				when (SELECT COUNT(distinct tt.Date) FROM timesheets tt WHERE tt.Code!="" AND tt.Site_Name like "%(%" AND tt.Site_Name=t.Site_Name AND replace(tt.Code," ","")=replace(t.Code," ","") AND str_to_date(tt.Date,"%d-%M-%Y") Between str_to_date(radius.Start_Date,"%d-%M-%Y") and str_to_date(radius.Completion_Date,"%d-%M-%Y"))>=scopeofwork.KPI+2 then Incentive_3
				when (SELECT COUNT(distinct tt.Date) FROM timesheets tt WHERE tt.Code!="" AND tt.Site_Name like "%(%" AND tt.Site_Name=t.Site_Name AND replace(tt.Code," ","")=replace(t.Code," ","") AND str_to_date(tt.Date,"%d-%M-%Y") Between str_to_date(radius.Start_Date,"%d-%M-%Y") and str_to_date(radius.Completion_Date,"%d-%M-%Y"))>=scopeofwork.KPI+1 then Incentive_2
				when (SELECT COUNT(distinct tt.Date) FROM timesheets tt WHERE tt.Code!="" AND tt.Site_Name like "%(%" AND tt.Site_Name=t.Site_Name AND replace(tt.Code," ","")=replace(t.Code," ","") AND str_to_date(tt.Date,"%d-%M-%Y") Between str_to_date(radius.Start_Date,"%d-%M-%Y") and str_to_date(radius.Completion_Date,"%d-%M-%Y"))<=scopeofwork.KPI then Incentive_1
				else 0 END) as Incentive
				from tracker a left join
				(select Site_Name,Code,count(distinct Date) as c from timesheets where timesheets.Code!="" and timesheets.Site_Name like "%(%" group by Site_Name,Code) t on a.Incentive_Code like CONCAT("%",replace(t.Code," ",""),"%")
				left join `radius` on `t`.`Site_Name` = radius.Location_Name AND replace(t.Code," ","") like CONCAT("%", radius.Code ,"%") left join `scopeofwork` on `radius`.`Code` = `scopeofwork`.`Code`
				AND a.Id=tracker.Id group by a.Id) as `'.$quote->Column_Name."`,";
			}
			else if($quote->Column_Name=="E-wallet")
			{
				$columns=$columns.'(SELECT SUM(FORMAT(Amount,2)) FROM ewallet WHERE ewallet.TrackerId=tracker.Id GROUP BY ewallet.TrackerId) as `'.$quote->Column_Name."`,";
			}
			else if($quote->Column_Name=="Profit")
			{
				$columns=$columns.'"-" as `'.$quote->Column_Name."`,";
			}
			else if($quote->Column_Name=="%_Profit")
			{
				$columns=$columns.'"-" as `'.$quote->Column_Name."`,";
			}
			else {
				# code...
				$columns=$columns.'tracker.`'.$quote->Column_Name."`,";
			}

		}

			$columns="(SELECT COUNT(files.id) as count FROM files WHERE TargetId=tracker.Id AND Type='Tracker' GROUP BY TargetId) as '-',tracker.`Id`,".$columns."";
			
			$columns=rtrim($columns,",");

			//prepare total claim and unclaim
			$povalue="";
			$totalclaim="";
			$additionalcolumns="";

			foreach($allavailablecolumns as $col)
			{
				if(ends_with(strtoupper($col->Col), 'PO VALUE'))
				{
					$povalue=$povalue."replace(replace(`".$col->Col."`,',',''),'RM','')+";
				}

				if(ends_with(strtoupper($col->Col), 'PO AMOUNT'))
				{
					$povalue=$povalue."replace(replace(`".$col->Col."`,',',''),'RM','')+";
				}

				if(ends_with(strtoupper($col->Col), 'VO_AMOUNT'))
				{
					$povalue=$povalue."replace(replace(`".$col->Col."`,',',''),'RM','')+";
				}

				if(str_contains(strtoupper($col->Col), 'PO VALUE'))
				{
					$povalue=$povalue."replace(replace(`".$col->Col."`,',',''),'RM','')+";
				}

				if(starts_with(strtoupper($col->Col), 'PO') && str_contains(strtoupper($col->Col), 'AMOUNT'))
				{
					$povalue=$povalue."replace(replace(`".$col->Col."`,',',''),'RM','')+";
				}

				if(starts_with(strtoupper($col->Col), 'PO') && ends_with(strtoupper($col->Col), '_AMOUNT'))
				{
					$povalue=$povalue."replace(replace(`".$col->Col."`,',',''),'RM','')+";
				}

				if(ends_with(strtoupper($col->Col), 'INVOICE AMOUNT'))
				{
					$totalclaim=$totalclaim."replace(replace(`".$col->Col."`,',',''),'RM','')+";
				}

				if(ends_with(strtoupper($col->Col), 'INV AMOUNT'))
				{
					$totalclaim=$totalclaim."replace(replace(`".$col->Col."`,',',''),'RM','')+";
				}
			}

			if($current)
			{
				if($povalue && $totalclaim && (str_contains(strtoupper($current->Tracker_Name), 'MASTER') || str_contains(strtoupper($current->Tracker_Name), 'MASTER')||str_contains(strtoupper($current->Tracker_Name), 'SALES') || str_contains(strtoupper($current->Tracker_Name), 'PO ') || str_contains(strtoupper($current->Tracker_Name), 'INVOICE')))
				{
					$povalue=substr($povalue,0,strlen($povalue)-1);
					$totalclaim=substr($totalclaim,0,strlen($totalclaim)-1);

					$additionalcolumns="FORMAT((SELECT sum(".$povalue.") FROM tracker a WHERE a.`Unique ID`=tracker.`Unique ID` GROUP BY `Unique ID`),2) as `Total PO`,
					FORMAT((SELECT sum(".$totalclaim.") FROM tracker a WHERE a.`Unique ID`=tracker.`Unique ID` GROUP BY `Unique ID`),2) as `Total Claim`,
					FORMAT((SELECT sum(".$povalue.")-SUM(".$totalclaim.") FROM tracker a WHERE a.`Unique ID`=tracker.`Unique ID` GROUP BY `Unique ID`),2) as `Total Unclaim`
					";

					$columns=$columns.",".$additionalcolumns;
				}

			}

			//end of prepare

		DB::setFetchMode(PDO::FETCH_ASSOC);

		if($current)
		{

			if($current->Region)
			{
				$condition=" tracker.Region='".$current->Region."'";
			}

			if($current->Scope)
			{
				$condition=" tracker.Scope='".$current->Scope."'";
			}

		}
		$reg=isset($_GET['region']) ? $_GET['region']:"";
		$filt="1 ";
		if($reg != ""){
			$filt=" tracker.Region = '".$reg."'";
		}
		$start=isset($_GET['start']) ? $_GET['start']:"";
		$end=isset($_GET['end']) ? $_GET['end']:"";
		$dashboard=isset($_GET['dashboard']) ? $_GET['dashboard']:0;
		if(($start != "" || $end !="") && $dashboard !=3 ){
			$filt.=' AND (str_to_date(tracker.`1st Claim Invoice Date`,"%d-%M-%Y") BETWEEN str_to_date("'.$start.'","%d-%M-%Y") AND str_to_date("'.$end.'","%d-%M-%Y") OR
			str_to_date(tracker.`2nd Claim Invoice Date`,"%d-%M-%Y") BETWEEN str_to_date("'.$start.'","%d-%M-%Y") AND str_to_date("'.$end.'","%d-%M-%Y") OR
			str_to_date(tracker.`3rd Claim Invoice Date`,"%d-%M-%Y") BETWEEN str_to_date("'.$start.'","%d-%M-%Y") AND str_to_date("'.$end.'","%d-%M-%Y") OR
			str_to_date(tracker.`Retention Invoice Date`,"%d-%M-%Y") BETWEEN str_to_date("'.$start.'","%d-%M-%Y") AND str_to_date("'.$end.'","%d-%M-%Y") OR
			str_to_date(tracker.`Invoice Date`,"%d-%M-%Y") BETWEEN str_to_date("'.$start.'","%d-%M-%Y") AND str_to_date("'.$end.'","%d-%M-%Y") )
			';
		}else if($dashboard == 3 && ($start != "" || $end != "")){
			$filt.=' AND str_to_Date(CONCAT("01-",`Closed Date`),"%d-%M-%Y")  BETWEEN str_to_date("'.$start.'","%d-%M-%Y") AND str_to_date("'.$end.'","%d-%M-%Y")';
		}

		if(isset($_GET['status'])){
			$filt.=" AND tracker.`Site Status`= '".$_GET['status']."' ";
		}
		if(isset($_GET['milestone'])){
			$filt.=' AND (`OSU Milestone` = "'.$_GET['milestone'].'" OR `Milestone`="'.$_GET['milestone'].'")';
		}
		if($condition!="")
		{
			$trackerview = DB::table('tracker')
			->select(DB::raw($columns))
			->whereRaw($condition)
			->whereRaw("(".$criteria.")")
			->whereRaw($filt)
			->orderByRaw('tracker.Id DESC')
			->get();

		}
		else {
			$trackerview = DB::table('tracker')
			->select(DB::raw($columns))
			->whereRaw("(".$criteria.")")
			->whereRaw($filt)
			->orderByRaw('tracker.Id DESC')
			->get();
		}

		DB::setFetchMode(PDO::FETCH_CLASS);

		array_walk($trackerview, function(&$value)
		{
			array_walk($value, function(&$value2)
			{
			  $value2 = strtoupper($value2);
			});
		});

		if($trackerview)
		{

			for ($i=1; $i <sizeof($trackerview) ; $i++) {
				# code...

				$povalue=0;
				$budget=0;
				$cost=0;
				$incentive=0;
				$expenses=0;
				$profit=0;

				if(array_key_exists("PO Value (RM)",$trackerview[$i]))
				{
					$povalue=str_replace(",","",str_replace("RM","",$trackerview[$i]["PO Value (RM)"]));
				}

				if(array_key_exists("MR_Budget",$trackerview[$i]))
				{
					$budget=str_replace(",","",str_replace("RM","",$trackerview[$i]["MR_Budget"]));
				}

				if(array_key_exists("Manday",$trackerview[$i]))
				{
					$cost=str_replace(",","",str_replace("RM","",$trackerview[$i]["Manday"]));
				}

				if(array_key_exists("Incentive",$trackerview[$i]))
				{
					$incentive=str_replace(",","",str_replace("RM","",$trackerview[$i]["Incentive"]));
				}

				if(array_key_exists("E-wallet",$trackerview[$i]))
				{
					$expenses=str_replace(",","",str_replace("RM","",$trackerview[$i]["E-wallet"]));
				}

				if(array_key_exists("Profit",$trackerview[$i]))
				{
					$profit=(float)$povalue-(float)$budget-(float)$cost-(float)$incentive-(float)$expenses;
					$trackerview[$i]["Profit"]=$profit;

				}

				if(array_key_exists("%_Profit",$trackerview[$i]))
				{
					if($povalue>0)
					{
						$trackerview[$i]["%_Profit"]=number_format(((float)$profit)/(float)$povalue*100,2);
					}
					else {
						// code...
						$trackerview[$i]["%_Profit"]="-";
					}

				}
			}

		}

		$staff= DB::table('users')
		->where('User_Type','!=','Contractor')
		->orderBy('Name','asc')
		->get();

		$contractor= DB::table('users')
		->where('User_Type','=','Contractor')
		->orderBy('Name','asc')
		->get();

		$trackername = DB::table("trackertemplate")
		->where('trackertemplate.Id','=',$trackerid)
		->get();

		$lastedit = DB::table('trackerupdate')
		->select('trackerupdate.Updated_At','users.Name')
		->leftJoin('users', 'users.Id', '=', 'trackerupdate.UserId')
		->orderBy('trackerupdate.Id','DESC')
		->first();

		$optionkey = DB::table('trackercolumn')
		->select('trackercolumn.Column_Name')
		->where('trackercolumn.TrackerTemplateID','=',$trackerid)
		->where('trackercolumn.Type','=','Dropdown')
		->orderBy('trackercolumn.Sequence','ASC')
		->get();

		$optionvalue= DB::table('options')
		->whereIn('Table', ["users","tracker"])
		->orderBy('Table','asc')
		->orderBy('Option','asc')
		->get();

		if($current)
		{
				$region=$current->Region;
				$scope=$current->Scope;
		}
		else {
			// code...
			$region="";
			$scope="";
		}

		$existingtracker = DB::table('trackertemplate')
		->select('trackertemplate.Id','trackertemplate.Tracker_Name')
		->orderBy('trackertemplate.Tracker_Name','ASC')
		->get();

		$arrsum=array();

		if($trackerview)
		{
			foreach ($trackerview[0] as $key => $col) {
				// code...
					if(str_contains(strtoupper($key), 'AMOUNT')||str_contains(strtoupper($key), 'VALUE')||str_contains(strtoupper($key), 'TOTAL'))
					{
						array_push($arrsum,$key);
					}
			}
		}

		return view('handsontable',['me' => $me,'staff'=>$staff,'contractor'=>$contractor,'trackerid'=>$trackerid, 'condition'=>$condition, 'trackerlist' => $trackerlist, 'trackerview' => $trackerview, 'columns'=>$columns,
		'trackercolumns' => $trackercolumns, 'allavailablecolumns' =>$allavailablecolumns, 'trackername'=> $trackername,  'lastedit'=>$lastedit, 'optionkey'=>$optionkey,'trackerwriteaccess'=>$trackerwriteaccess,'trackerdeleteaccess'=>$trackerdeleteaccess, 'optionvalue'=>$optionvalue,
		'Scope'=>$scope,'Region'=>$region,'existingtracker'=>$existingtracker,'cri1'=>$cri1,'cri2'=>$cri2,'cri3'=>$cri3,'cri4'=>$cri4,'cri5'=>$cri5,'criteria'=>$criteria,'colorcodes'=>$colorcodes,'additionalcolumns'=>$additionalcolumns,'arrsum'=>$arrsum,'current'=>$current]);

	}

	public function invoicelisting($year = null)
	{
		$me = (new CommonController)->get_current_user();


		DB::setFetchMode(PDO::FETCH_ASSOC);

		$filter="1";

		if($year==null)
		{
			$filter=$filter." AND invoicelisting.Year=".date("Y");
			$year=date("Y");
		}
		else if($year=="All")
		{

		}
		else {
			// code...
			$filter=$filter." AND invoicelisting.Year=".$year;
		}


		$invoicelisting = DB::table('invoicelisting')
		->select(DB::raw('(SELECT COUNT(files.id) as count FROM files WHERE TargetId=tracker.Id AND Type="Tracker" GROUP BY TargetId) as "-"'),'invoicelisting.Id','invoicelisting.TrackerId','invoicelisting.Customer','invoicelisting.Customer','tracker.Region',
		DB::raw('CONCAT(tracker.`Site ID / LRD`,"-",tracker.`Site ID`,"-",tracker.`LRD`,"-",tracker.`Site LRD`) as "Site_ID"'),'invoicelisting.Year','invoicelisting.Month','invoicelisting.PO_Milestone','invoicelisting.PO_No','invoicelisting.PO_Date','invoicelisting.PO_Amount','invoicelisting.Invoice_No','invoicelisting.Invoice_Date','invoicelisting.Invoice_Amount','users.Name')
		->leftJoin('tracker','invoicelisting.TrackerId','=','tracker.Id')
		->leftJoin('users','users.Id','=','invoicelisting.created_by')
		->whereRaw($filter)
		->orderBy('invoicelisting.Id','DESC')
		->get();

		DB::setFetchMode(PDO::FETCH_CLASS);

		$years= DB::select("
			SELECT Year(Now())-1 as yearname UNION ALL
			SELECT Year(Now())
			");

			$months= ["Jan","Feb","Mar","Apr","May","Jun","Jul","Aug","Sep","Oct","Nov","Dec"];

		$milestones= DB::table('options')
		->whereIn('Table', ["tracker"])
		->where('Field', '=','PO Milestone')
		->orderBy('Option','asc')
		->get();

		return view('invoicelisting',['me' => $me,'invoicelisting'=>$invoicelisting,'year'=>$year,'years'=>$years,'milestones'=>$milestones,'months'=>$months]);

	}

	public function costing()
	{
		$me = (new CommonController)->get_current_user();

		DB::setFetchMode(PDO::FETCH_ASSOC);

		$filter="1";

		$costing = DB::table('costing')
		->select('costing.Id','costing.Date','costing.Cost_Type','costing.Amount','costing.Remarks','users.Name','costing.created_at')
		->leftJoin('tracker','costing.TrackerId','=','tracker.Id')
		->leftJoin('users','users.Id','=','costing.created_by')
		->get();

		DB::setFetchMode(PDO::FETCH_CLASS);

		$costtype= DB::table('options')
		->whereIn('Table', ["costing"])
		->where('Field', '=','Cost_Type')
		->orderBy('Option','asc')
		->get();

		return view('costing',['me' => $me,'costing'=>$costing,'costtype'=>$costtype]);

	}

	public function sitetransportcharges($start=null,$end=null,$site=null)
	{
		$me = (new CommonController)->get_current_user();

		if ($start==null)
		{
			$start=date('d-M-Y', strtotime('first day of last month'));
			// $start=date('d-M-Y', strtotime($start,' +16 days'));
			$start = date('d-M-Y', strtotime($start . " +20 days"));
		}
		if ($end==null)
		{
			$end=date('d-M-Y', strtotime('first day of this month'));
			$end = date('d-M-Y', strtotime($end . " +19 days"));
		}

		$year = date('Y');


		$years= DB::select("
			SELECT Year(Now())-1 as yearname UNION ALL
			SELECT Year(Now()) UNION ALL
			SELECT Year(Now())+1
			");
		$company = DB::table('companies')
		->select('Id','Company_Name')
		->whereIn('Id',[1,2,3])
		->get();
		if($site!= null)
		{
		$summary = DB::table('deliveryform')
		->leftJoin('users','users.Id','=','deliveryform.DriverId')
		->leftJoin('radius','radius.Id','=','deliveryform.Location')
		->leftJoin('deliverylocation','deliverylocation.area','=','radius.Area')
		->leftJoin('roadtax','roadtax.Id','=','deliveryform.roadtaxId')
		->leftJoin('companies','companies.Id','=','deliveryform.company_id')
		->select('deliveryform.Id','users.Name','deliveryform.DO_No','radius.Location_Name','companies.Company_Name','deliverylocation.area','roadtax.Lorry_Size',
			DB::raw('(CASE WHEN roadtax.Lorry_Size = "10 Tan Crane" THEN price_10ton_crane WHEN roadtax.Lorry_Size = "10 Tan" THEN price_10ton WHEN roadtax.Lorry_Size = "5 Tan Crane" THEN price_5ton_crane ELSE price_2ton_to_5ton END) AS driverincentive'))
		->where('deliverylocation.type','=','charges')
		->where('radius.Client','=',$site)
		->where(DB::raw('str_to_date(deliveryform.delivery_date,"%d-%M-%Y")'),">=",DB::raw('str_to_date("'.$start.'","%d-%M-%Y")'))
        ->where(DB::raw('str_to_date(deliveryform.delivery_date,"%d-%M-%Y")'),"<=",DB::raw('str_to_date("'.$end.'","%d-%M-%Y")'))
		->get();
		}
		else{
		$summary = DB::table('deliveryform')
		->leftJoin('users','users.Id','=','deliveryform.DriverId')
		->leftJoin('radius','radius.Id','=','deliveryform.Location')
		->leftJoin('deliverylocation','deliverylocation.area','=','radius.Area')
		->leftJoin('roadtax','roadtax.Id','=','deliveryform.roadtaxId')
		->leftJoin('companies','companies.Id','=','deliveryform.company_id')
		->select('deliveryform.Id','users.Name','deliveryform.DO_No','radius.Location_Name','companies.Company_Name','deliverylocation.area','roadtax.Lorry_Size',
			DB::raw('(CASE WHEN roadtax.Lorry_Size = "10 Tan Crane" THEN price_10ton_crane WHEN roadtax.Lorry_Size = "10 Tan" THEN price_10ton WHEN roadtax.Lorry_Size = "5 Tan Crane" THEN price_5ton_crane ELSE price_2ton_to_5ton END) AS driverincentive'))
		->where('deliverylocation.type','=','charges')
		->where(DB::raw('str_to_date(deliveryform.delivery_date,"%d-%M-%Y")'),">=",DB::raw('str_to_date("'.$start.'","%d-%M-%Y")'))
        ->where(DB::raw('str_to_date(deliveryform.delivery_date,"%d-%M-%Y")'),"<=",DB::raw('str_to_date("'.$end.'","%d-%M-%Y")'))
		->get();
		}

		return view('sitetransportcharges',['me' => $me, 'summary'=>$summary, 'start'=>$start,'end'=>$end , 'company'=>$company]);

	}

	public function transportcharges($start=null,$end=null)
	{
		$me = (new CommonController)->get_current_user();

		if ($start==null)
		{
			$start=date('d-M-Y', strtotime('first day of last month'));
			// $start=date('d-M-Y', strtotime($start,' +16 days'));
			$start = date('d-M-Y', strtotime($start . " +20 days"));
		}
		if ($end==null)
		{
			$end=date('d-M-Y', strtotime('first day of this month'));
			$end = date('d-M-Y', strtotime($end . " +19 days"));
		}

		$year = date('Y');


		$years= DB::select("
			SELECT Year(Now())-1 as yearname UNION ALL
			SELECT Year(Now()) UNION ALL
			SELECT Year(Now())+1
			");
		$company = DB::table('companies')
		->select('Id','Company_Name')
		->whereIn('Id',[1,2,3])
		->get();

		$details = DB::table('deliveryform')
		->leftJoin( DB::raw('(select Max(Id) as maxid,deliveryform_Id from deliverystatuses Group By deliveryform_Id) as max'), 'max.deliveryform_Id', '=', 'deliveryform.Id')
        ->leftJoin('deliverystatuses', 'deliverystatuses.Id', '=', DB::raw('max.`maxid`'))
		->leftJoin('users','users.Id','=','deliveryform.DriverId')
		->leftJoin('radius','radius.Id','=','deliveryform.Location')
		->leftJoin('deliverylocation','deliverylocation.area','=','radius.Area')
		->leftJoin('roadtax','roadtax.Id','=','deliveryform.roadtaxId')
		->leftJoin('companies','companies.Id','=','deliveryform.company_id')
		->leftJoin('companies as client','client.Id','=','deliveryform.client')
		->select('deliveryform.Id','roadtax.Vehicle_No','deliveryform.delivery_date','users.Name','deliveryform.DO_No','radius.Location_Name','client.Company_Name as client','companies.Company_Name','deliveryform.trip','deliverylocation.area','roadtax.Lorry_Size',
			DB::raw('
					(CASE
						WHEN (deliveryform.trip LIKE "%1 Way Trip%")
							THEN
							MAX(CASE WHEN roadtax.Lorry_Size = "10 Tan Crane" THEN price_10ton_crane
									WHEN roadtax.Lorry_Size = "10 Tan" THEN price_10ton
									WHEN roadtax.Lorry_Size = "5 Tan Crane" THEN price_5ton_crane
									ELSE price_2ton_to_5ton END) / COUNT(DISTINCT deliveryform.Location)
						WHEN (deliveryform.trip LIKE "%2 Way Trip%")
							THEN
							(CASE WHEN roadtax.Lorry_Size = "10 Tan Crane" THEN price_10ton_crane
										WHEN roadtax.Lorry_Size = "10 Tan" THEN price_10ton
										WHEN roadtax.Lorry_Size = "5 Tan Crane" THEN price_5ton_crane
										ELSE price_2ton_to_5ton END)
						ELSE
								CASE
								 WHEN (deliverylocation.area LIKE "%Zone%")
									THEN
										(CASE WHEN roadtax.Lorry_Size = "10 Tan Crane" THEN price_10ton_crane
										WHEN roadtax.Lorry_Size = "10 Tan" THEN price_10ton
										WHEN roadtax.Lorry_Size = "5 Tan Crane" THEN price_5ton_crane
										ELSE price_2ton_to_5ton END)
								 WHEN (deliverylocation.area LIKE "%Outstation%")
									THEN
										MAX(CASE WHEN roadtax.Lorry_Size = "10 Tan Crane" THEN price_10ton_crane
										WHEN roadtax.Lorry_Size = "10 Tan" THEN price_10ton
										WHEN roadtax.Lorry_Size = "5 Tan Crane" THEN price_5ton_crane
										ELSE price_2ton_to_5ton END) / COUNT(DISTINCT deliveryform.Location)
								ELSE
										(CASE WHEN roadtax.Lorry_Size = "10 Tan Crane" THEN price_10ton_crane
										WHEN roadtax.Lorry_Size = "10 Tan" THEN price_10ton
										WHEN roadtax.Lorry_Size = "5 Tan Crane" THEN price_5ton_crane
										ELSE price_2ton_to_5ton END)
								END
						END
							) AS transportcharges

				'),DB::raw('"" as driverincentive'),'deliveryform.incentive',DB::raw('"" as totalincentive'))
        ->where(DB::raw('str_to_date(deliveryform.delivery_date,"%d-%M-%Y")'),">=",DB::raw('str_to_date("'.$start.'","%d-%M-%Y")'))
        ->where(DB::raw('str_to_date(deliveryform.delivery_date,"%d-%M-%Y")'),"<=",DB::raw('str_to_date("'.$end.'","%d-%M-%Y")'))
        ->whereRaw('deliverylocation.type = "charges" AND (deliverystatuses.delivery_status = "Completed" OR deliverystatuses.delivery_status = "Incomplete")')
        ->whereRaw('DO_NO NOT LIKE BINARY "%\_R%"')
        ->where('roadtax.Type','<>','TRUCK')
        ->groupby('deliveryform.Id')
		->get();

		$summary = DB::table('deliveryform')
		->leftJoin( DB::raw('(select Max(Id) as maxid,deliveryform_Id from deliverystatuses Group By deliveryform_Id) as max'), 'max.deliveryform_Id', '=', 'deliveryform.Id')
        ->leftJoin('deliverystatuses', 'deliverystatuses.Id', '=', DB::raw('max.`maxid`'))
		->leftJoin('users','users.Id','=','deliveryform.DriverId')
		->leftJoin('radius','radius.Id','=','deliveryform.Location')
		->leftJoin('deliverylocation','deliverylocation.area','=','radius.Area')
		->leftJoin('roadtax','roadtax.Id','=','deliveryform.roadtaxId')
		->leftJoin('companies','companies.Id','=','deliveryform.company_id')
		->select('deliveryform.delivery_date','deliveryform.roadtaxId','users.Name','deliveryform.DO_No','radius.Location_Name','companies.Company_Name','deliverylocation.area','roadtax.Lorry_Size',
			DB::raw('
					(CASE
						WHEN (deliveryform.trip LIKE "%1 Way Trip%")
							THEN
							MAX(CASE WHEN roadtax.Lorry_Size = "10 Tan Crane" THEN price_10ton_crane
									WHEN roadtax.Lorry_Size = "10 Tan" THEN price_10ton
									WHEN roadtax.Lorry_Size = "5 Tan Crane" THEN price_5ton_crane
									ELSE price_2ton_to_5ton END)
						WHEN (deliveryform.trip LIKE "%2 Way Trip%")
							THEN
							SUM(CASE WHEN roadtax.Lorry_Size = "10 Tan Crane" THEN price_10ton_crane
										WHEN roadtax.Lorry_Size = "10 Tan" THEN price_10ton
										WHEN roadtax.Lorry_Size = "5 Tan Crane" THEN price_5ton_crane
										ELSE price_2ton_to_5ton END)
						ELSE
								CASE
								 WHEN (deliverylocation.area LIKE "%Zone%")
									THEN
										SUM(CASE WHEN roadtax.Lorry_Size = "10 Tan Crane" THEN price_10ton_crane
										WHEN roadtax.Lorry_Size = "10 Tan" THEN price_10ton
										WHEN roadtax.Lorry_Size = "5 Tan Crane" THEN price_5ton_crane
										ELSE price_2ton_to_5ton END)
								 WHEN (deliverylocation.area LIKE "%Outstation%")
									THEN
										MAX(CASE WHEN roadtax.Lorry_Size = "10 Tan Crane" THEN price_10ton_crane
										WHEN roadtax.Lorry_Size = "10 Tan" THEN price_10ton
										WHEN roadtax.Lorry_Size = "5 Tan Crane" THEN price_5ton_crane
										ELSE price_2ton_to_5ton END)
								ELSE
										(CASE WHEN roadtax.Lorry_Size = "10 Tan Crane" THEN price_10ton_crane
										WHEN roadtax.Lorry_Size = "10 Tan" THEN price_10ton
										WHEN roadtax.Lorry_Size = "5 Tan Crane" THEN price_5ton_crane
										ELSE price_2ton_to_5ton END)
								END
						END
							) AS charges

				'),DB::raw('"" as driverincentive'),'deliveryform.incentive',DB::raw('"" as totalincentive'))
		->whereRaw('(deliverylocation.type = "charges" AND (deliverystatuses.delivery_status = "Completed" OR deliverystatuses.delivery_status = "Incomplete"))')
		->where(DB::raw('str_to_date(deliveryform.delivery_date,"%d-%M-%Y")'),">=",DB::raw('str_to_date("'.$start.'","%d-%M-%Y")'))
        ->where(DB::raw('str_to_date(deliveryform.delivery_date,"%d-%M-%Y")'),"<=",DB::raw('str_to_date("'.$end.'","%d-%M-%Y")'))
        ->where('roadtax.Type','<>','TRUCK')
        ->whereRaw('DO_NO NOT LIKE BINARY "%\_R%"')
        ->groupBy('deliveryform.delivery_date')
        ->groupBy('deliveryform.roadtaxId')
		->get();


		return view('transportcharges',['me' => $me, 'summary'=>$summary, 'start'=>$start,'end'=>$end , 'company'=>$company,'details'=>$details]);

	}

	public function transportchargesdetails($roadtaxid,$date)
	{
		$me = (new CommonController)->get_current_user();
		$details = DB::table('deliveryform')
		->leftJoin( DB::raw('(select Max(Id) as maxid,deliveryform_Id from deliverystatuses Group By deliveryform_Id) as max'), 'max.deliveryform_Id', '=', 'deliveryform.Id')
        ->leftJoin('deliverystatuses', 'deliverystatuses.Id', '=', DB::raw('max.`maxid`'))
		->leftJoin('users','users.Id','=','deliveryform.DriverId')
		->leftJoin('radius','radius.Id','=','deliveryform.Location')
		->leftJoin('deliverylocation','deliverylocation.area','=','radius.Area')
		->leftJoin('roadtax','roadtax.Id','=','deliveryform.roadtaxId')
		->leftJoin('companies','companies.Id','=','deliveryform.company_id')
		->select('deliveryform.Id','deliveryform.delivery_date','users.Name','deliveryform.DO_No','radius.Location_Name','companies.Company_Name','deliveryform.trip','deliverylocation.area','roadtax.Lorry_Size',
			DB::raw('(CASE WHEN roadtax.Lorry_Size = "10 Tan Crane" THEN price_10ton_crane WHEN roadtax.Lorry_Size = "10 Tan" THEN price_10ton WHEN roadtax.Lorry_Size = "5 Tan Crane" THEN price_5ton_crane ELSE price_2ton_to_5ton END) AS driverincentive'))
        ->where('deliveryform.roadtaxId','=',$roadtaxid)
        ->where('deliveryform.delivery_date','=',$date)
        ->whereRaw('deliverylocation.type = "charges" AND (deliverystatuses.delivery_status = "Completed" OR deliverystatuses.delivery_status = "Incomplete")')
		->get();

		return view('transportchargesdetails',['me' => $me,'details'=>$details]);
	}

	public function updateincentive($id, Request $request)
	{
		$me = (new CommonController)->get_current_user();

		DB::table('deliveryform')
		->where('Id','=',$id)
		->update([
			'incentive' => $request->value
		]);
		return 1;
	}

	public function reportpreview($trackerid)
	{
		$me = (new CommonController)->get_current_user();

		$trackerdetail = DB::table('tracker')
		->select()
		->where('tracker.Id','=',$trackerid)
		->first();

		$gallery = DB::table('files')
		->select()
		->where('files.TargetId','=',$trackerid)
		->get();

		$reportexist = DB::table('report_contents')
		->select()
		->where('report_contents.TrackerId','=',$trackerid)
		->where('report_contents.Report_Type','=','SSR Report')
		->first();

		// dd($trackerdetail);

		return view('reportpreview',['me' => $me, 'trackerid'=>$trackerid,'trackerdetail'=>$trackerdetail, 'gallery'=>$gallery,'reportexist'=>$reportexist]);
	}

	public function reportdraft($trackerid)
	{
		$me = (new CommonController)->get_current_user();

		$trackerdetail = DB::table('tracker')
		->select()
		->where('tracker.Id','=',$trackerid)
		->first();

		$gallery = DB::table('files')
		->select()
		->where('files.TargetId','=',$trackerid)
		->orderBy('files.Id','ASC')
		->get();

		$reportexist = DB::table('report_contents')
		->select()
		->where('report_contents.TrackerId','=',$trackerid)
		->where('report_contents.Report_Type','=','SSR Report')
		->first();

		// dd($trackerdetail);

		return view('reportdraft',['me' => $me, 'trackerid'=>$trackerid,'trackerdetail'=>$trackerdetail, 'gallery'=>$gallery,'reportexist'=>$reportexist]);
	}

	public function reportpdf(Request $request, $trackerid)
	{

		$me = (new CommonController)->get_current_user();

		$reportexist = DB::table('report_contents')
		->select()
		->where('report_contents.TrackerId','=',$trackerid)
		->where('report_contents.Report_Type','=','SSR Report')
		->first();


		$trackerdetail = DB::table('tracker')
		->select()
		->where('tracker.Id','=',$trackerid)
		->first();

		$html= view('reportpdf',['me'=>$me, 'trackerid'=>$trackerid,'reportexist'=>$reportexist]);

		(new ExportPDFController)->Export($html);

	}

	public function inventoryreportdraft($trackerid)
	{
		$me = (new CommonController)->get_current_user();

		$trackerdetail = DB::table('tracker')
		->select()
		->where('tracker.Id','=',$trackerid)
		->first();

		$gallery = DB::table('files')
		->select()
		->where('files.TargetId','=',$trackerid)
		->get();

		$reportexist = DB::table('report_contents')
		->select()
		->where('report_contents.TrackerId','=',$trackerid)
		->where('report_contents.Report_Type','=','Inventory Report')
		->first();

		return view("inventoryreportdraft",['me'=>$me,'gallery'=>$gallery, 'trackerid'=>$trackerid,'reportexist'=>$reportexist]);


	}

	public function inventoryreportpreview(Request $request,$trackerid)
	{
		$me = (new CommonController)->get_current_user();
		$input = $request->all();

		$reportexist = DB::table('report_contents')
		->select()
		->where('report_contents.TrackerId','=',$trackerid)
		->where('report_contents.Report_Type','=','Inventory Report')
		->first();
		$trackerdetail = DB::table('tracker')
		->select()
		->where('tracker.Id','=',$trackerid)
		->first();

		$gallery = DB::table('files')
		->select()
		->where('files.TargetId','=',$trackerid)
		->get();

		return view("inventoryreportpreview",['me'=>$me, 'trackerid'=>$trackerid,'gallery'=>$gallery,'reportexist'=>$reportexist]);


	}

	public function inventoryreportpdf($trackerid)
	{
		$me = (new CommonController)->get_current_user();

		$reportexist = DB::table('report_contents')
		->select()
		->where('report_contents.TrackerId','=',$trackerid)
		->where('report_contents.Report_Type','=','Inventory Report')
		->first();


		$trackerdetail = DB::table('tracker')
		->select()
		->where('tracker.Id','=',$trackerid)
		->first();

		return view('inventoryreportpdf',['me'=>$me, 'trackerid'=>$trackerid,'reportexist'=>$reportexist]);

		// (new ExportPDFController)->ExportLandscape($html);

	}

	public function coveragereportdraft($trackerid)
	{
		$me = (new CommonController)->get_current_user();

		$reportexist = DB::table('report_contents')
		->select()
		->where('report_contents.TrackerId','=',$trackerid)
		->where('report_contents.Report_Type','=','Coverage')
		->first();

		$trackerdetail = DB::table('tracker')
		->select()
		->where('tracker.Id','=',$trackerid)
		->first();

		$gallery = DB::table('files')
		->select()
		->where('files.TargetId','=',$trackerid)
		->get();

		// dd($gallery);

		return view("coveragereportdraft",['me'=>$me, 'trackerid'=>$trackerid,'reportexist'=>$reportexist,'gallery'=>$gallery]);


	}

	public function coveragereportpreview($trackerid)
	{
		$me = (new CommonController)->get_current_user();

		$reportexist = DB::table('report_contents')
		->select()
		->where('report_contents.TrackerId','=',$trackerid)
		->where('report_contents.Report_Type','=','Coverage')
		->first();

		$trackerdetail = DB::table('tracker')
		->select()
		->where('tracker.Id','=',$trackerid)
		->first();

		$gallery = DB::table('files')
		->select()
		->where('files.TargetId','=',$trackerid)
		->get();

		return view("coveragereportpreview",['me'=>$me, 'trackerid'=>$trackerid,'gallery'=>$gallery,'reportexist'=>$reportexist]);


	}

	public function coveragereportpdf(Request $request,$trackerid)
	{

		$me = (new CommonController)->get_current_user();
		$input = $request->all();

		$reportexist = DB::table('report_contents')
		->select()
		->where('report_contents.TrackerId','=',$trackerid)
		->where('report_contents.Report_Type','=','Coverage')
		->first();

		$trackerdetail = DB::table('tracker')
		->select()
		->where('tracker.Id','=',$trackerid)
		->first();

		return view('coveragereportpdf',['me'=>$me, 'trackerid'=>$trackerid, 'reportexist'=>$reportexist, 'input'=>$input]);

		// (new ExportPDFController)->ExportLandscape($html);


	}

	public function assetreportdraft($trackerid)
	{
		$me = (new CommonController)->get_current_user();

		$reportexist = DB::table('report_contents')
		->select()
		->where('report_contents.TrackerId','=',$trackerid)
		->where('report_contents.Report_Type','=','Asset')
		->first();

		$trackerdetail = DB::table('tracker')
		->select()
		->where('tracker.Id','=',$trackerid)
		->first();

		$gallery = DB::table('files')
		->select()
		->where('files.TargetId','=',$trackerid)
		->get();

		return view("assetreportdraft",['me'=>$me, 'trackerid'=>$trackerid,'gallery'=>$gallery,'reportexist'=>$reportexist]);


	}

	public function assetreportpreview($trackerid)
	{
		$me = (new CommonController)->get_current_user();

		$reportexist = DB::table('report_contents')
		->select()
		->where('report_contents.TrackerId','=',$trackerid)
		->where('report_contents.Report_Type','=','Asset')
		->first();

		$trackerdetail = DB::table('tracker')
		->select()
		->where('tracker.Id','=',$trackerid)
		->first();

		$gallery = DB::table('files')
		->select()
		->where('files.TargetId','=',$trackerid)
		->get();

		return view("assetreportpreview",['me'=>$me, 'trackerid'=>$trackerid,'gallery'=>$gallery,'reportexist'=>$reportexist]);


	}

	public function assetreportpdf(Request $request)
	{
		$me = (new CommonController)->get_current_user();

		$reportexist = DB::table('report_contents')
		->select()
		->where('report_contents.TrackerId','=',$trackerid)
		->where('report_contents.Report_Type','=','Asset')
		->first();


		$trackerdetail = DB::table('tracker')
		->select()
		->where('tracker.Id','=',$trackerid)
		->first();

		$html= view('assetreportpdf',['me'=>$me, 'trackerid'=>$trackerid,'reportexist'=>$reportexist]);

		(new ExportPDFController)->Export($html);

	}

	public function savereport(Request $request)
	{

		$input = $request->all();

		$TrackerId = $input["TrackerId"];
		$Report_Type = $input["Report_Type"];


		$report_content = DB::table('report_contents')
		->select('report_contents.TrackerId','report_contents.Report_Type')
		->where('report_contents.TrackerId',$TrackerId)
		->where('report_contents.Report_Type',$Report_Type)
		->get();

		if($report_content){
			$id= DB::table('report_contents')
					->where('TrackerId', $input["TrackerId"])
					->where('Report_Type', $input["Report_Type"])
						->update(array(
						'Contents' => $input["Contents"]
					));
					return 1;
		}

		else{
			$id= DB::table('report_contents')
						->insert(array(
						'TrackerId' => $input["TrackerId"],
						'Report_Type' => $input["Report_Type"],
						'Contents' => $input["Contents"]
					));
					return 1;
		}



	}


	public function logintracker($start=null,$end=null)
	{
		$me = (new CommonController)->get_current_user();



		if ($start==null)
		{
			$start=date('d-M-Y', strtotime('today'));

		}

		if ($end==null)
		{
			$end=date('d-M-Y', strtotime('today'));
			$end=date('d-M-Y', strtotime($end . " +1 days"));
		}

		$startTime = strtotime($start);
		$endTime = strtotime($end);
		$query="";

		$startTime=strtotime("+1 days",$startTime);

		 while ($startTime <= $endTime){

		 	$query.="SELECT '" . date('Y-m-d H:i:s', $startTime) . "' UNION ALL ";

			$startTime=strtotime("+1 days",$startTime);
		 }

		 $end=date('d-M-Y', strtotime($end . " +1 days"));

		$query=substr($query,0,strlen($query)-10);

		$datecreate=date_create($start);
		$first=date_format($datecreate, 'Y-m-d H:i:s');

		$logs = DB::select("
				SELECT  users.StaffId, users.Name,logintrackings.Event, logintrackings.created_at as Date_Time
				FROM logintrackings
				LEFT JOIN users on logintrackings.UserId=users.Id
				where logintrackings.created_at between str_to_date('".$start."','%d-%M-%Y') and str_to_date('".$end."','%d-%M-%Y')
		");

    return view("logintracker",['me'=>$me, 'logs'=>$logs, 'start'=>$start, 'end'=>$end]);
  }


	public function updatelogin(Request $request)
	{

		$input = $request->all();

		$id= DB::table('logintrackings')
					->insert(array(
					'Event' => $input["Event"],
					'UserId' => $input["UserId"]
				));
		return 1;

	}

	public function projectrequirement($projectid = null)
  {
    	$me = (new CommonController)->get_current_user();



		$requirement = DB::table('projectrequirements')
		->select('projectrequirements.Id','projectrequirements.Type','projectrequirements.Requirement', 'projectrequirements.Start_Date','projectrequirements.End_Date')
		->get();

		$options= DB::table('options')
		->whereIn('Table', ["users","userability"])
		->orderBy('Table','asc')
		->orderBy('Option','asc')
		->get();

		return view("projectrequirement",['me'=>$me, 'requirement'=>$requirement, 'options'=>$options]);
	}

	public function resourcecalendar($start = null, $end = null, $role = null)
	{
		$me = (new CommonController)->get_current_user();

		if ($start==null)
		{
			$start=date('d-M-Y', strtotime('first day of this month'));

		}

		if ($end==null)
		{
			// $end=date('d-M-Y', strtotime('today'));
			// $end=date('d-M-Y', strtotime($end . " +1 days"));
			$end=date('d-M-Y', strtotime('last day of this month'));

		}

		if ($role==null)
		{
			$role="All";

		}

		$startTime = strtotime($start);
		$endTime = strtotime($end);

		$query="";
		$query2="";
		$query3="";
		$query4="";
		$query5="";
		$query6="";
		$query7="";

		$first="";
		$first2="";
		$first3="";
		$first4="";
		$first5="";
		$first6="";
		$first7="";
		$daterange=[];

		$startTime=strtotime("+1 days",$startTime);

		if ($role=='All'){

			while ($startTime <= $endTime){
			$query.="SUM(CASE WHEN str_to_date('". date('d-M-Y', $startTime) ."','%d-%M-%Y') BETWEEN str_to_date(projectrequirements.Start_Date,'%d-%M-%Y') AND str_to_date(projectrequirements.End_Date,'%d-%M-%Y') THEN projectrequirements.Requirement ELSE 0 END) AS '". date('d-M-Y', $startTime) ."',";
			$query2.="COUNT(DISTINCT users.Id) AS '". date('d-M-Y', $startTime) ."',";
			$query3.="SUM(CASE WHEN str_to_date('". date('d-M-Y', $startTime) ."','%d-%M-%Y') BETWEEN str_to_date(userprojects.Start_Date,'%d-%M-%Y') AND str_to_date(userprojects.End_Date,'%d-%M-%Y') THEN 1 ELSE 0 END) AS '". date('d-M-Y', $startTime) ."',";
			$query4.="SUM(CASE WHEN str_to_date('". date('d-M-Y', $startTime) ."','%d-%M-%Y') BETWEEN str_to_date(projectrequirements.Start_Date,'%d-%M-%Y') AND str_to_date(projectrequirements.End_Date,'%d-%M-%Y') THEN projectrequirements.Requirement ELSE 0 END) AS '". date('d-M-Y', $startTime) ."',";
			$query5.="SUM(CASE WHEN str_to_date('". date('d-M-Y', $startTime) ."','%d-%M-%Y') BETWEEN str_to_date(userprojects.Start_Date,'%d-%M-%Y') AND str_to_date(userprojects.End_Date,'%d-%M-%Y') THEN 1 ELSE 0 END) AS '". date('d-M-Y', $startTime) ."',";
			$query7.="SUM(CASE WHEN str_to_date('". date('d-M-Y', $startTime) ."','%d-%M-%Y') BETWEEN str_to_date(projectrequirements.Start_Date,'%d-%M-%Y') AND str_to_date(projectrequirements.End_Date,'%d-%M-%Y') THEN projectrequirements.Requirement ELSE 0 END) AS '". date('d-M-Y', $startTime) ."',";

			$daterange[].= date('d-M-Y', $startTime);
			$startTime=strtotime("+1 days",$startTime);
		 }

		$query=substr($query,0,strlen($query)-1);
		$query2=substr($query2,0,strlen($query2)-1);
		$query3=substr($query3,0,strlen($query3)-1);
		$query4=substr($query4,0,strlen($query4)-1);
		$query5=substr($query5,0,strlen($query5)-1);
		$query6=substr($query6,0,strlen($query6)-1);
		$query7=substr($query7,0,strlen($query7)-1);

		$start1 = strtotime($start);

		$first ="SUM(CASE WHEN str_to_date('". date('d-M-Y', $start1) ."','%d-%M-%Y') BETWEEN str_to_date(projectrequirements.Start_Date,'%d-%M-%Y') AND str_to_date(projectrequirements.End_Date,'%d-%M-%Y') THEN projectrequirements.Requirement ELSE 0 END) AS '". date('d-M-Y', $start1) ."'";
		$first2 ="COUNT(DISTINCT users.Id) AS '". date('d-M-Y', $start1) ."'";
		$first3="SUM(CASE WHEN str_to_date('". date('d-M-Y', $start1) ."','%d-%M-%Y') BETWEEN str_to_date(userprojects.Start_Date,'%d-%M-%Y') AND str_to_date(userprojects.End_Date,'%d-%M-%Y') THEN 1 ELSE 0 END) AS '". date('d-M-Y', $start1) ."'";
		//$first6="(COUNT(DISTINCT users.Id))-(SUM(CASE WHEN str_to_date('". date('d-M-Y', $start1) ."','%d-%M-%Y') BETWEEN str_to_date(userprojects.Start_Date,'%d-%M-%Y') AND str_to_date(userprojects.End_Date,'%d-%M-%Y')  AND userprojects.Assigned_As='".$role."' THEN 1 ELSE 0 END)) AS '". date('d-M-Y', $startTime) ."'";
		$first4 ="SUM(CASE WHEN str_to_date('". date('d-M-Y', $start1) ."','%d-%M-%Y') BETWEEN str_to_date(projectrequirements.Start_Date,'%d-%M-%Y') AND str_to_date(projectrequirements.End_Date,'%d-%M-%Y') THEN projectrequirements.Requirement ELSE 0 END) AS '". date('d-M-Y', $start1) ."'";
		$first5="SUM(CASE WHEN str_to_date('". date('d-M-Y', $start1) ."','%d-%M-%Y') BETWEEN str_to_date(userprojects.Start_Date,'%d-%M-%Y') AND str_to_date(userprojects.End_Date,'%d-%M-%Y') THEN 1 ELSE 0 END) AS '". date('d-M-Y', $startTime) ."'";
		$first7 ="SUM(CASE WHEN str_to_date('". date('d-M-Y', $start1) ."','%d-%M-%Y') BETWEEN str_to_date(projectrequirements.Start_Date,'%d-%M-%Y') AND str_to_date(projectrequirements.End_Date,'%d-%M-%Y') THEN projectrequirements.Requirement ELSE 0 END) AS '". date('d-M-Y', $start1) ."'";

		$resourcecalendar = DB::select("
					SELECT
					".$first2.",
					".$query2."
					FROM users
					LEFT JOIN userability ON users.Id=userability.UserId
					WHERE Ability<>'' UNION ALL
					SELECT 
					".$first4.",
					".$query4."
					FROM projectrequirements
					GROUP BY projectsrequirements.Type UNION ALL
					SELECT null,'Total Required',
					".$first7.",
					".$query7."
					FROM projectrequirements
			");

		$assign = DB::select("
				SELECT 'Total Assigned',
				".$first3.",
				".$query3."
				from users
				LEFT JOIN userprojects on users.Id=userprojects.UserId

		");


		$data="";
		$ability= DB::select("SELECT `Option`
			FROM  `options`
			WHERE  `Field` LIKE  'Ability'
			");

			foreach($ability as $key => $quote){

  			$data.= $quote->Option.",";
			}
			$s=rtrim($data,",");
			$arr = explode(",", $s);


		$i=0;
		$querytype="";
		$last="";
		$querytype1="";
		$last1="";
		while ($i < count($arr)-1) {
		   $a = $arr[$i];
			 $querytype.= "SELECT '".$a."' As User_Type,".$first2.",".$query2." FROM users LEFT JOIN userability ON users.Id=userability.UserId WHERE Ability = '".$a."' UNION ALL ";
			 $querytype1.= "SELECT users.Id, '".$a."' As User_Type,".$first2.",".$query2." FROM users LEFT JOIN userability ON users.Id=userability.UserId LEFT JOIN userprojects ON users.Id=userprojects.UserId WHERE Ability = '".$a."' UNION ALL ";

		 	 $i++;
		}

		$last= "SELECT '".$arr[count($arr)-1]."' As User_Type,".$first2.",".$query2."FROM users LEFT JOIN userability ON users.Id=userability.UserId WHERE Ability = '".$arr[count($arr)-1]."'";
		$last1= "SELECT users.Id, '".$arr[count($arr)-1]."' As User_Type,".$first2.",".$query2."FROM users LEFT JOIN userability ON users.Id=userability.UserId LEFT JOIN userprojects ON users.Id=userprojects.UserId WHERE Ability = '".$arr[count($arr)-1]."' ";

		$totalavailable = DB::select("".$querytype." ".$last."");

		$totalunassigned = DB::select("".$querytype1." ".$last1."");

		$projecttype = DB::select("
				SELECT projectrequirements.Type,
				".$first4.",
				".$query4."
				FROM projectrequirements
				GROUP BY projectrequirements.Type
		");

		$totalrequired= DB::select("
				SELECT null,projectrequirements.Type as User_Type,
				".$first7.",
				".$query7."
				FROM projectrequirements
				GROUP BY projectrequirements.Type
		");

		$totalassigned = DB::select("
				SELECT userprojects.Assigned_As,
				".$first3.",
				".$query3."
				from users
				LEFT JOIN userprojects on users.Id=userprojects.UserId
				where userprojects.Assigned_As <> ''
				GROUP BY userprojects.Assigned_As

		");


		}

		else {

				while ($startTime <= $endTime){
	 			$query.="SUM(CASE WHEN str_to_date('". date('d-M-Y', $startTime) ."','%d-%M-%Y') BETWEEN str_to_date(projectrequirements.Start_Date,'%d-%M-%Y') AND str_to_date(projectrequirements.End_Date,'%d-%M-%Y')  AND projectrequirements.Type='".$role."' THEN projectrequirements.Requirement ELSE 0 END) AS '". date('d-M-Y', $startTime) ."',";
	 			$query2.="COUNT(DISTINCT users.Id) AS '". date('d-M-Y', $startTime) ."',";
	 			$query3.="SUM(CASE WHEN str_to_date('". date('d-M-Y', $startTime) ."','%d-%M-%Y') BETWEEN str_to_date(userprojects.Start_Date,'%d-%M-%Y') AND str_to_date(userprojects.End_Date,'%d-%M-%Y')  AND userprojects.Assigned_As='".$role."' THEN 1 ELSE 0 END) AS '". date('d-M-Y', $startTime) ."',";
	 			$query4.="SUM(CASE WHEN str_to_date('". date('d-M-Y', $startTime) ."','%d-%M-%Y') BETWEEN str_to_date(projectrequirements.Start_Date,'%d-%M-%Y') AND str_to_date(projectrequirements.End_Date,'%d-%M-%Y') THEN projectrequirements.Requirement ELSE 0 END) AS '". date('d-M-Y', $startTime) ."',";
	 			$query5.="SUM(CASE WHEN str_to_date('". date('d-M-Y', $startTime) ."','%d-%M-%Y') BETWEEN str_to_date(userprojects.Start_Date,'%d-%M-%Y') AND str_to_date(userprojects.End_Date,'%d-%M-%Y') THEN 1 ELSE 0 END) AS '". date('d-M-Y', $startTime) ."',";
	 			$query7.="SUM(CASE WHEN str_to_date('". date('d-M-Y', $startTime) ."','%d-%M-%Y') BETWEEN str_to_date(projectrequirements.Start_Date,'%d-%M-%Y') AND str_to_date(projectrequirements.End_Date,'%d-%M-%Y') THEN projectrequirements.Requirement ELSE 0 END) AS '". date('d-M-Y', $startTime) ."',";

	 			$daterange[].= date('d-M-Y', $startTime);
	 			$startTime=strtotime("+1 days",$startTime);
	 		 }

	 		$query=substr($query,0,strlen($query)-1);
	 		$query2=substr($query2,0,strlen($query2)-1);
	 		$query3=substr($query3,0,strlen($query3)-1);
	 		$query4=substr($query4,0,strlen($query4)-1);
	 		$query5=substr($query5,0,strlen($query5)-1);
	 		$query6=substr($query6,0,strlen($query6)-1);
	 		$query7=substr($query7,0,strlen($query7)-1);

	 		$start1 = strtotime($start);

	 		$first ="SUM(CASE WHEN str_to_date('". date('d-M-Y', $start1) ."','%d-%M-%Y') BETWEEN str_to_date(projectrequirements.Start_Date,'%d-%M-%Y') AND str_to_date(projectrequirements.End_Date,'%d-%M-%Y')  AND projectrequirements.Type='".$role."' THEN projectrequirements.Requirement ELSE 0 END) AS '". date('d-M-Y', $start1) ."'";
	 		$first2 ="COUNT(DISTINCT users.Id) AS '". date('d-M-Y', $start1) ."'";
	 		$first3="SUM(CASE WHEN str_to_date('". date('d-M-Y', $start1) ."','%d-%M-%Y') BETWEEN str_to_date(userprojects.Start_Date,'%d-%M-%Y') AND str_to_date(userprojects.End_Date,'%d-%M-%Y')  AND userprojects.Assigned_As='".$role."' THEN 1 ELSE 0 END) AS '". date('d-M-Y', $start1) ."'";
	 		$first4 ="SUM(CASE WHEN str_to_date('". date('d-M-Y', $start1) ."','%d-%M-%Y') BETWEEN str_to_date(projectrequirements.Start_Date,'%d-%M-%Y') AND str_to_date(projectrequirements.End_Date,'%d-%M-%Y') THEN projectrequirements.Requirement ELSE 0 END) AS '". date('d-M-Y', $start1) ."'";
	 		$first5="SUM(CASE WHEN str_to_date('". date('d-M-Y', $start1) ."','%d-%M-%Y') BETWEEN str_to_date(userprojects.Start_Date,'%d-%M-%Y') AND str_to_date(userprojects.End_Date,'%d-%M-%Y') THEN 1 ELSE 0 END) AS '". date('d-M-Y', $startTime) ."'";
	 		$first7 ="SUM(CASE WHEN str_to_date('". date('d-M-Y', $start1) ."','%d-%M-%Y') BETWEEN str_to_date(projectrequirements.Start_Date,'%d-%M-%Y') AND str_to_date(projectrequirements.End_Date,'%d-%M-%Y') THEN projectrequirements.Requirement ELSE 0 END) AS '". date('d-M-Y', $start1) ."'";

	 		$resourcecalendar = DB::select("
	 				SELECT 
	 				".$first2.",
	 				".$query2."
	 				FROM users
	 				LEFT JOIN userability ON users.Id=userability.UserId
	 				WHERE Ability = '".$role."' UNION ALL
	 				SELECT
	 				".$first.",
	 				".$query."
	 				FROM projectrequirements
	 				GROUP BY projectrequirements.Type UNION ALL
	 				SELECT null,'Total Required',
	 				".$first.",
	 				".$query."
	 				FROM projectrequirements
	 		");

	 		$assign = DB::select("
	 				SELECT 'Total Assigned',
	 				".$first3.",
	 				".$query3."
	 				from users
	 				LEFT JOIN userprojects on users.Id=userprojects.UserId

	 		");


			$totalassigned = "";
			$totalavailable = "";
			$totalrequired = "";
			$totalunassigned ="";

		}

		$options= DB::table('options')
		->whereIn('Table', ["users","userability"])
		->orderBy('Table','asc')
		->orderBy('Option','asc')
		->get();



		return view("resourcecalendar",['me'=>$me,'start' =>$start,'end'=>$end, 'resourcecalendar'=>$resourcecalendar, 'options'=>$options, 'role'=>$role, 'daterange'=>$daterange, 'assign'=>$assign, 'totalavailable'=>$totalavailable, 'totalrequired'=>$totalrequired, 'totalassigned'=>$totalassigned , 'totalunassigned'=>$totalunassigned]);

	}

	public function typelist(Request $request){

		$input = $request->all();

		$typelist = DB::table('userprojects')
		->select('users.Name','userprojects.Assigned_As', 'userprojects.Start_Date', 'userprojects.End_Date')
		->leftJoin('users','users.Id','=','userprojects.UserId')
		->where('userprojects.Assigned_As', '=', $input["Assigned_As"])
		->where(DB::raw('str_to_date(userprojects.Start_Date,"%d-%M-%Y")'),"<=",DB::raw('str_to_date("'.$input["Date"].'","%d-%M-%Y")'))
		->where(DB::raw('str_to_date(userprojects.End_Date,"%d-%M-%Y")'),">=",DB::raw('str_to_date("'.$input["Date"].'","%d-%M-%Y")'))
		->get();

		return json_encode($typelist);


	}

	public function getdocumentlist1(Request $request){

		$input = $request->all();

		$TrackerId=$input["TrackerId"];


		$file = DB::table('options')
		->select('files.Id','options.Option','files.created_at AS Submission_Date','users.Name as Submitted_By',DB::raw("files.Web_Path AS Download"),'files.File_Name',DB::raw("'' AS Upload"))
		->leftJoin('files','files.Document_Type','=', DB::raw('options.Option AND files.TargetId='.$TrackerId))
		->leftJoin('users','files.UserId','=','users.Id')
		->where('options.Field', '=','Document_Type')
		->whereIn('options.Option',['Panaromic Photos','Site Photos','Videos','4G Image','3G Image','2G Image','Inventory Image','Asset Image'])
		->orderBy('options.Option')
		->groupBy('options.option')
		->get();

		return json_encode($file);

	}

	public function getdocumentlist(Request $request){

			$me = (new CommonController)->get_current_user();

			$input = $request->all();

			$TrackerId=$input["TrackerId"];


		$file = DB::table('options')
		->select('files.Id','options.Option','files.created_at AS Submission_Date','users.Name as Submitted_By',DB::raw("files.Web_Path AS Download"),'files.File_Name',DB::raw("'' AS Upload"))
		->leftJoin('files','files.Document_Type','=', DB::raw('options.Option AND files.TargetId='.$TrackerId))
		->leftJoin('users','files.UserId','=','users.Id')
		->where('options.Field', '=','Document_Type')
		->whereNotIn('options.Option',['Panaromic Photos','Site Photos','Videos'])
		->orderBy('options.Option')
		->groupBy('options.option')
		->get();

		return json_encode($file);

		return view('handsontableupload',['me' => $me, 'file'=>$file]);

	}

	//11/06/2018 - Firdaus - Show document list in folder view
	public function viewdocument(Request $request){

		$me = (new CommonController)->get_current_user();

		$input = $request->all();

		$TrackerId=$input["TrackerId"];


	$file = DB::table('options')
	->select('files.Id','options.Option','files.created_at AS Submission_Date','users.Name as Submitted_By',DB::raw("files.Web_Path AS Download"),'files.File_Name',DB::raw("'' AS Upload"))
	->leftJoin('files','files.Document_Type','=', DB::raw('options.Option AND files.TargetId='.$TrackerId))
	->leftJoin('users','files.UserId','=','users.Id')
	->where('options.Field', '=','Document_Type')
	->whereNotIn('options.Option',['Panaromic Photos','Site Photos','Videos'])
	->orderBy('options.Option')
	->groupBy('options.option')
	->get();

	return json_encode($file);

	// return view('handsontableupload',['me' => $me, 'file'=>$file]);
	return view('filesubmitdocument',['me' => $me, 'file'=>$file]);

}

	// 	public function getdocumentlist(Request $request){
	//
	// 		$input = $request->all();
	//
	// 		$TrackerId=$input["TrackerId"];
	//
	//
	// 	$file = DB::table('options')
	// 	->select('files.Id','options.Option','files.created_at AS Submission_Date','users.Name as Submitted_By',DB::raw("files.Web_Path AS Download"),'files.File_Name',DB::raw("'' AS Upload"))
	// 	->leftJoin('files','files.Document_Type','=', DB::raw('options.Option AND files.TargetId='.$TrackerId))
	// 	->leftJoin('users','files.UserId','=','users.Id')
	// 	->where('options.Field', '=','Document_Type')
	// 	->whereNotIn('options.Option',['Panaromic Photos','Site Photos','Videos'])
	// 	->orderBy('options.Option')
	// 	->groupBy('options.option')
	// 	->get();
	//
	// 	return json_encode($file);
	//
	// }

	public function viewphoto(Request $request){

		$input = $request->all();

		$TargetId=$input["TargetId"];
		$Document_Type=$input["Document_Type"];

		$images = DB::table('files')
		->select('files.Id','files.TargetId','files.File_Name','files.Document_Type','files.Web_Path')
		->where('files.TargetId', '=',$TargetId)
		->where('files.Document_Type', '=',$Document_Type)
		->get();

		// dd($images);

		return json_encode($images);

	}

	public function unassignedusers($role){

		$me = (new CommonController)->get_current_user();

		$unassignedusers = DB::table('users')
		->select('userprojects.Id','users.StaffId','users.Name',  DB::raw('"" as Assign'),'userability.Ability','userprojects.Assigned_As','userprojects.Start_Date','userprojects.End_Date')
		->leftJoin('userprojects','userprojects.UserId','=','users.Id')
		->leftJoin('userability','users.Id','=','userability.UserId')
		->where('userability.Ability','=', "$role")
		->where('userprojects.Assigned_As','=',"")
		->get();

		$assigned_as = DB::table('userability')
		->get();

		$options= DB::table('options')
		 ->whereIn('Table', ["users","userability"])
		 ->orderBy('Table','asc')
		 ->orderBy('Option','asc')
		 ->get();

		return view('unassigned',['role' => $role, 'me'=>$me, 'unassignedusers'=>$unassignedusers,'options'=>$options, 'assigned_as'=>$assigned_as ]);
	}

	public function deletedocument(Request $request)
	{

		$input = $request->all();
		$Id=$input["Id"];

		return DB::table('files')->where('Id', '=',$Id)->delete();

	}

	//Firdaus 20180622 - Backend to upload image to table 'files'
	public function submitdocument(Request $request)
	{
		$me = (new CommonController)->get_current_user();

	  $filenames="";
	  $input = $request->all();

	  $UserId=$input["UserId"]; //562
	  $TrackerId=$input["selectedtrackerid"];	//27274

		$site = DB::table('tracker')
	  ->where('Id','=',$TrackerId)
	  ->first();

		if($site->Site_Name) {
			// code...
			$SiteId=$site->Site_Name;
		}
		else if($site->Site_ID)
		{
			$SiteId=$site->Site_ID;
		}
		else if($site->{'Site ID'})
		{
			$SiteId=$site->{'Site ID'};
		}
		else if($site->{'Site Name'})
		{
			$SiteId=$site->{'Site Name'};
		}
		else {
			$SiteId=$TrackerId;

		}
		// dd($site);
	  $DocumentType=$input["DocumentType"];	//BOQ, Invoice, PO etc
	  $Type="Tracker";	//Tracker

	        $file=$_FILES['file']['name'];

	        $filename=$file;
	        // $filename = $file;
	        $size=$_FILES['file']['size'];
	        if($request->file('file')->isValid()) {
						$request->file('file')->move("private/upload/Site Document/".$DocumentType."/", $filename);
	            $insert=DB::table('files')->insertGetId(
	              ['Type' => $Type,
	               'UserId' => $me->UserId,
	               'TargetId' => $TrackerId,
	               'File_Name' => $filename,
	               'Document_Type' => $DocumentType,
	               'File_Size' => $size,
	               'Web_Path' => '/private/upload/Site Document/'.$DocumentType.'/'.$filename
	              ]
	            );
	        
	        }
	        $filenames.= $insert."|".url('/private/upload/'.$DocumentType.'/'.$filename);

	      

				$options = DB::table('options')
				->select('options.Id','options.Table','options.Field','options.Option','options.Update_Column')
				->where('options.Option', '=',$DocumentType)
				->orderBy('options.Field', 'asc')
				->get();
					// dd($options);

				foreach ($options as $opt) {
					// code...

					if($opt->Update_Column)
					{

						$opt->Update_Column=htmlspecialchars_decode($opt->Update_Column);

						$updatedata = Carbon::now();
						// dd($updatedata);
						DB::table('tracker')
						->where('Id', '=',$TrackerId)
						->where(DB::raw('`'.$opt->Update_Column.'`'),'=','')
						->update([$opt->Update_Column=>$updatedata]);

						$this->updatedependency($updatedata,$TrackerId);
						$this->updatetask($opt->Update_Column,$updatedata,$TrackerId);

						if (trim($opt->Update_Column != '')) {
							$agingsubscribers = DB::table('agings')
							->select(
								DB::raw("CASE WHEN tracker.Site_Name='' THEN tracker.`Site Name` ELSE tracker.Site_Name END as 'Site_Name'"),
								'agingsubscribers.AgingId',
								'agings.Start_Date',
								'agings.End_Date',
								'agings.Threshold',
								'agingsubscribers.UserId',
								'users.Player_Id',
								'users.Id as UserId'
							)
							->where('Start_Date', $input["Column"][$i])
							->join('agingsubscribers', 'agingsubscribers.AgingId', '=', 'agings.Id')
							->join('users', 'users.Id', '=', 'agingsubscribers.UserId')
							->join('tracker', 'tracker.Id', '=',DB::raw($TrackerId))
							->get();

							// notify subscribers
							if (count($agingsubscribers)) {
								$groups = [];

								// prepare groups
								foreach ($agingsubscribers as $subscriber) {

									if ($subscriber->Player_Id) {
												array_push($groups,$subscriber->Player_Id);
									}

									$taskId = DB::table('tasks')->insertGetId([
										'Current_Task'  => $subscriber->End_Date,
										'Previous_Task' => $opt->Update_Column,
										'Previous_Task_Date' => $updatedata,
										'Site_Name'     => $subscriber->Site_Name,
										'Threshold'     => $subscriber->Threshold,
										'UserId' => $subscriber->UserId,
										'assign_by' => $me->UserId
									]);

									DB::table('taskstatuses')->insert([
										'TaskId' => $taskId,
										'UserId' => $subscriber->UserId,
										'Status' => 'Assigned'
									]);
								}

									$playerids 	= $groups;
									$message 	= 'Task ' .$opt->Update_Column.' completed. '.$subscriber->End_Date. ' has started';
									$type 		= 'Task';
									$title  	= 'Task Completed Notification';

									$this->sendNotification($playerids, $title, $message, $type);
								// }
							}
						}
					}
				}

	      return $filenames;

	}


	public function submitdocumentmanual(Request $request)
	{
			$me = (new CommonController)->get_current_user();

			$filenames="";
			$input = $request->all();

			// dd($input);

			$UserId=$input["UserId"];
			$TrackerId=$input["selectedtrackerid"];

			$site = DB::table('tracker')
		  ->where('Id','=',$TrackerId)
		  ->first();

			if($site->Site_ID)
			{
				$SiteId=$site->Site_ID;
			}
			else if($site->Site_Name) {
				// code...
				$SiteId=$site->Site_Name;
			}
			else if($site->{'Site ID'})
			{
				$SiteId=$site->{'Site ID'};
			}
			else if($site->{'Site Name'})
			{
				$SiteId=$site->{'Site Name'};
			}
			else{
				// code...
				$SiteId=$TrackerId;
			}

			$DocumentType=$input["DocumentType"];
			$Type="Tracker";
			$uploadcount=count($request->file('document'));

			$submitdate=$input["submitdate"];

			if($submitdate)
			{

				DB::table('tracker')
			  ->where('Id', '=',$TrackerId)
				->where('tracker.Site_ID','=',$SiteId)
			  ->update([$submitdate => DB::raw("DATE_FORMAT(NOW(),'%d-%b-%Y')")]);
			}


				if (!file_exists('/private/upload/Site Document/'.$DocumentType)) {

				  File::makeDirectory('/private/upload/Site Document/'.$DocumentType, 0777, true, true);

		    	File::makeDirectory('/private/upload/Site Document/'.$DocumentType, 0777, true, true);
				}

				$options = DB::table('options')
				->select('options.Id','options.Table','options.Field','options.Option','options.Update_Column')
				->where('options.Option', '=',$DocumentType)
				->orderBy('options.Field', 'asc')
				->get();

				if ($request->hasFile('document')) {

					for ($i=0; $i <$uploadcount ; $i++) {

						# code...
						$file = $request->file('document')[$i];
						$destinationPath=public_path()."/private/upload/Site Document/".$DocumentType;
						$extension = $file->getClientOriginalExtension();
						$originalName=$file->getClientOriginalName();
						$fileSize=$file->getSize();

						// $versionnumber= DB::table('files')
						// ->select(DB::raw('count(*) as count'))
						// ->where('File_Name','like',DB::raw('"%'.$SiteId."_".$DocumentType.'%"'))
						// ->first();
						//
						// $ext = pathinfo($file, PATHINFO_EXTENSION);

						$filename=$originalName;

						// $fileName=time()."_".$i.".".$extension;
						$upload_success = $file->move($destinationPath, $filename);
						$insert=DB::table('files')->insertGetId(
							['Type' => $Type,
							 'UserId' => $me->UserId,
							 'TargetId' => $TrackerId,
							 'File_Name' => $originalName,
							 'Document_Type' => $DocumentType,
							 'File_Size' => $fileSize,
							 'Web_Path' => '/private/upload/Site Document/'.$DocumentType.'/'.$filename
							]
						);


						$filenames.= $insert."|".url('/private/upload/'.$DocumentType.'/'.$filename)."|" .$originalName;

						$options = DB::table('options')
						->select('options.Id','options.Table','options.Field','options.Option','options.Update_Column')
						->where('options.Option', '=',$DocumentType)
						->orderBy('options.Field', 'asc')
						->get();
						foreach ($options as $opt) {
							// code...

							if($opt->Update_Column)
							{

								$opt->Update_Column=htmlspecialchars_decode($opt->Update_Column);

								$updatedata = Carbon::now();
								// dd($updatedata);
								DB::table('tracker')
								->where('Id', '=',$TrackerId)
								->where(DB::raw('`'.$opt->Update_Column.'`'),'=','')
								->update([$opt->Update_Column=>$updatedata]);

								$this->updatedependency($updatedata,$TrackerId);
								$this->updatetask($opt->Update_Column,$updatedata,$TrackerId);
								// dd($opt->Update_Column,$updatedata,$TrackerId);
								//
								if (trim($opt->Update_Column != '')) {
									$agingsubscribers = DB::table('agings')
									->select(
										DB::raw("CASE WHEN tracker.Site_Name='' THEN tracker.`Site Name` ELSE tracker.Site_Name END as 'Site_Name'"),
										'agingsubscribers.AgingId',
										'agings.Start_Date',
										'agings.End_Date',
										'agings.Threshold',
										'agingsubscribers.UserId',
										'users.Player_Id',
										'users.Id as UserId'
									)
									->where('Start_Date', $input["Column"][$i])
									->join('agingsubscribers', 'agingsubscribers.AgingId', '=', 'agings.Id')
									->join('users', 'users.Id', '=', 'agingsubscribers.UserId')
									->join('tracker', 'tracker.Id', '=',DB::raw($TrackerId))
									->get();


									// notify subscribers
									if (count($agingsubscribers)) {
										$groups = [];

										// prepare groups
										foreach ($agingsubscribers as $subscriber) {

											if ($subscriber->Player_Id) {
												// $groups[$subscriber->AgingId]['users'][] = $subscriber;
												// $groups[$subscriber->AgingId]['playerids'][] = $subscribe

													// $subscriber->AgingId[
														// 'UserId' => $subscriber->UserId,
														array_push($groups,$subscriber->Player_Id);
													// ]


											}

											$taskId = DB::table('tasks')->insertGetId([
												'Current_Task'  => $subscriber->End_Date,
												'Previous_Task' => $opt->Update_Column,
												'Previous_Task_Date' => $updatedata,
												'Site_Name'     => $subscriber->Site_Name,
												'Threshold'     => $subscriber->Threshold,
												'UserId' => $subscriber->UserId,
												'assign_by' => $me->UserId
											]);

											DB::table('taskstatuses')->insert([
												'TaskId' => $taskId,
												'UserId' => $subscriber->UserId,
												'Status' => 'Assigned'
											]);
										}

										// dd($groups);
										// foreach ($groups as $group => $g) {
											$playerids 	= $groups;
											$message 	= 'Task ' .$opt->Update_Column.' completed. '.$subscriber->End_Date. ' has started';
											$type 		= 'Task';
											$title  	= 'Task Completed Notification';

											$this->sendNotification($playerids, $title, $message, $type);
										// }
									}
								}
							}
						}
					}

					// return $filenames;
					return Redirect::back();

					//return '/private/upload/'.$fileName;
				}
				else {
					return Redirect::back();
				}
		

	}

	public function autodate()
	{
		$me = (new CommonController)->get_current_user();



			$columns = DB::table('trackercolumn')
			->select(DB::raw('DISTINCT Column_Name'))
			->where('Type', '=', 'Date')
			->where('Type', '=', 'Date')
			->orderBy('Column_Name','ASC')
			->get();


			$autodate = DB::table('autodate')
			->select('autodate.Id', 'autodate.Active','autodate.Date_2','autodate.Days','autodate.Type','autodate.Date_1','creator.Name as Creator')
			->leftJoin('users as creator', 'creator.Id', '=', 'autodate.UserId')
			->get();


		return view("autodate",['me'=>$me,'autodate'=>$autodate,'columns'=>$columns]);

	}

	public function agingrules()
	{
		$me = (new CommonController)->get_current_user();

			$columns = DB::table('trackercolumn')
			->select(DB::raw('DISTINCT Column_Name'))
			// ->where('Type', '=', 'Date')
			->orderBy('Column_Name','ASC')
			->get();

			$agingrules = DB::table('agings')
			->select(DB::raw('"" as button'),'agings.Id', 'agings.Active','agings.Title','agings.Type','agings.Start_Date','agings.End_Date','agings.Threshold as Threshold(days)','agings.Sequence','creator.Name as Creator','users.Name as Subscriber')
			->leftjoin('agingsubscribers','agingsubscribers.AgingId','=','agings.Id')
			->leftJoin('users as creator', 'creator.Id', '=', 'agings.UserId')
			->leftjoin('users','users.Id','=','agingsubscribers.UserId')
			->get();

		return view("agingmaintenance",['me'=>$me,'agingrules'=>$agingrules,'columns'=>$columns]);

	}

	public function dependencyrules()
	{
		$me = (new CommonController)->get_current_user();

			$columns = DB::table('trackercolumn')
			->select(DB::raw('DISTINCT Column_Name'))
			->orderBy('Column_Name','ASC')
			->get();

			$dependencyrules = DB::table('dependencyrules')
			->select('dependencyrules.Id', 'dependencyrules.Active','dependencyrules.Title','dependencyrules.Sequence','dependencyrules.Column1','dependencyrules.Column1_Status','dependencyrules.Column2','dependencyrules.Column2_Status','dependencyrules.Column3','dependencyrules.Column3_Status','dependencyrules.Target_Column','dependencyrules.Target_Status','creator.Name as Creator','notify.Name as Notify')
			->leftJoin('dependencynotification', 'dependencynotification.DependencyRulesId', '=', 'dependencyrules.Id')
			->leftJoin('users as notify', 'notify.Id', '=', 'dependencynotification.UserId')
			->leftJoin('users as creator', 'creator.Id', '=', 'dependencyrules.UserId')
			->get();

		return view("dependencymaintenance",['me'=>$me,'dependencyrules'=>$dependencyrules,'columns'=>$columns]);

	}

	public function agingpreview($agingid)
	{

		$me = (new CommonController)->get_current_user();

		$rule = DB::table('agings')
		->where('Id', '=', $agingid)
		->first();

		if($rule->Type=="Between 2 Date")
		{
			$agings = DB::table('tracker')
			->select(DB::raw('TIMESTAMPDIFF(Day,str_to_date(tracker.`'.$rule->Start_Date.'`, "%d-%M-%Y"),curdate()) as "Aging (days)"'),'Site_ID','Site_Name',DB::raw('`'.$rule->Start_Date.'`'))
			->where($rule->Start_Date, '<>', '')
			->where($rule->End_Date, '=', '')
			->orderBy(DB::raw('`Aging (days)`'),'desc')
			->get();

		}
		elseif($rule->Type=="By Period") {
			$agings = DB::table('tracker')
			->select(DB::raw('TIMESTAMPDIFF(Day,str_to_date(tracker.`'.$rule->Start_Date.'`, "%d-%M-%Y"),curdate()) as "Aging (days)"'),'Site_ID','Site_Name',DB::raw($rule->Start_Date))
			->where($rule->Start_Date, '<>', '')
			->where($rule->End_Date, '=', '')
			->orderBy(DB::raw('`Aging (days)`'),'desc')
			->get();
		}

		return view("agingpreview",['me'=>$me,'rule'=>$rule,'agings'=>$agings]);

	}

	public function targetpreview($targetid)
	{

		$me = (new CommonController)->get_current_user();

		$targetweekquery="";

		$rule = DB::table('targets')
		->where('Id', '=', $targetid)
		->first();

		$weekno=$this->Get_Week_No($this->GetDateString());
		$targetweek=$this->Get_Week_No($rule->Target_Date);
		$target=$rule->Target;

		if ($targetweek>$weekno)
		{
			$targetweekquery=',"" as "Week '. ($targetweek) .'"';
		}

		$targets = DB::select('SELECT
		"'.$rule->Title.'" as "'.$rule->Title.'",
		SUM(IF(WEEKOFYEAR(NOW())-5>=week(str_to_date(`'.$rule->Target_Field.'`, "%d-%M-%Y")),1,0)) as "Week '. ($weekno-4) .'",
		SUM(IF(WEEKOFYEAR(NOW())-4>=week(str_to_date(`'.$rule->Target_Field.'`, "%d-%M-%Y")),1,0)) as "Week '. ($weekno-3) .'",
		SUM(IF(WEEKOFYEAR(NOW())-3>=week(str_to_date(`'.$rule->Target_Field.'`, "%d-%M-%Y")),1,0)) as "Week '. ($weekno-2) .'",
		SUM(IF(WEEKOFYEAR(NOW())-2>=week(str_to_date(`'.$rule->Target_Field.'`, "%d-%M-%Y")),1,0)) as "Week '. ($weekno-1) .'",
		SUM(IF(WEEKOFYEAR(NOW())-1>=week(str_to_date(`'.$rule->Target_Field.'`, "%d-%M-%Y")),1,0)) as "Week '. ($weekno) .'"
		'.$targetweekquery.'
		FROM tracker');

		$label=array();
		$data=array();

		$targetlabel=array();
		$targetdata=array();
		$index=0;
		$targetindex=0;

		foreach($targets as $key => $target){

			foreach ($target as $key2 => $value2) {
				# code...
					$wk=filter_var($key2, FILTER_SANITIZE_NUMBER_INT);
					if(strpos($key2, "Week")!==false)
					{
						array_push($label,$key2);
						array_push($data,$value2);


						if($wk<=$targetweek)
						{

								array_push($targetlabel,$key2);
								array_push($targetdata,$rule->Target);

						}

						if($wk==$targetweek)
						{
							$targetindex=$index;
						}
						$index+=1;

					}

			}
		}

		$label= implode(',', $label);
		$data= implode(',', $data);
		$targetlabel= implode(',', $targetlabel);
		$targetdata= implode(',', $targetdata);

		return view("targetpreview",['me'=>$me,'rule'=>$rule,'targets'=>$targets,'targetweek'=>$targetweek,'label'=>$label,'data' => $data,'targetlabel'=>$targetlabel,'targetdata'=>$targetdata,'targetindex'=>$targetindex]);

	}

	public function targetrules()
	{
		$me = (new CommonController)->get_current_user();

			$columns = DB::table('trackercolumn')
			->select(DB::raw('DISTINCT Column_Name'))
			->where('Type', '=', 'Date')
			->orderBy('Column_Name','ASC')
			->get();

			$targetrules = DB::table('targets')
			->select('targets.Id', 'targets.Active','targets.Title','targets.Target_Field','targets.Target_Date','targets.Target','creator.Name as Creator','users.Name as Subscriber')
			->leftjoin('targetsubscribers','targetsubscribers.TargetId','=','targets.Id')
			->leftJoin('users as creator', 'creator.Id', '=', 'targets.UserId')
			->leftjoin('users','users.Id','=','targetsubscribers.UserId')
			->get();

		return view("targetmaintenance",['me'=>$me,'targetrules'=>$targetrules,'columns'=>$columns]);

	}

	public function templateaccess()
	{
		$me = (new CommonController)->get_current_user();

		set_time_limit(0);

		$bystaff = DB::table('users')
		->select('users.Id','users.StaffId', 'users.Name','trackertemplate.Tracker_Name','trackertemplate2.Tracker_Name as Tracker_Name_2','trackertemplate3.Tracker_Name as Tracker_Name_3')
		->leftJoin('templateaccess', 'templateaccess.UserId', '=', 'users.Id')
		->leftJoin('trackertemplate', 'trackertemplate.Id', '=', 'templateaccess.TrackerTemplateId')
		->leftJoin('templatewriteaccess', 'templatewriteaccess.UserId', '=', 'users.Id')
		->leftJoin('templatedeleteaccess', 'templatedeleteaccess.UserId', '=', 'users.Id')
		->leftJoin('trackertemplate as trackertemplate2', 'trackertemplate2.Id', '=', 'templatewriteaccess.TrackerTemplateId')
		->leftJoin('trackertemplate as trackertemplate3', 'trackertemplate3.Id', '=', 'templatedeleteaccess.TrackerTemplateId')
		->limit(1)
		->get();

		$trackertemplate = DB::table('trackertemplate')
		->get();

		$users =  DB::table('users')
		->get();

		return view("templateaccess",['me'=>$me, 'trackertemplate'=>$trackertemplate,'bystaff'=>$bystaff,  'users'=>$users]);

	}




	public function importdata(Request $request)
	{

		set_time_limit(0);

			$me = (new CommonController)->get_current_user();

			$input = $request->all();

      $file = Input::file('import');

			$destinationPath=public_path()."/private/upload/ImportData";
			$extension = $file->getClientOriginalExtension();
			$originalName=$file->getClientOriginalName();
			$fileSize=$file->getSize();
			$fileName=time().".".$extension;
			$upload_success = $file->move($destinationPath, $fileName);

			file_put_contents($destinationPath."/".$fileName,$this->removeBomUtf8(file_get_contents($destinationPath."/".$fileName)));


			$handle = fopen($destinationPath."/".$fileName, "r");
			if ($handle) {

				$index=0;

				$fields = fgetcsv($handle, 0, ",",'"');

			     while (($line = fgetcsv($handle, 0, ",",'"')) !== false) {

			        // process the line read.
							$arrRow=array();
							$insert=array();

							$id=0;

							for ($i=0; $i <count($fields) ; $i++) {
								# code...
								if($fields[$i]=="-" && strtoupper($line[0])=="REMOVE")
								{

									$id=$line[$i];

									DB::table('tracker')
									->where('Id', '=', $id)
									->delete();

									$details=$id;

									DB::table('trackerupdate')
										 ->insert(array(
										 'Details' => $details,
										 'TrackerId' =>$id,
										 'Type' =>'Delete',
										 'UserId' =>$me->UserId
									 ));

								}
								elseif($fields[$i]=="Id")
								{
									$id=$line[$i];


								}
								elseif($fields[$i]=="No" || $fields[$i]=="Action" || $fields[$i]=='-' || $fields[$i]=="Gantt")
								{


								}
								else {

									if(strlen($fields[$i])>0)
									{



										$fields[$i] = preg_replace(
										  '/
										    ^
										    [\pZ\p{Cc}\x{feff}]+
										    |
										    [\pZ\p{Cc}\x{feff}]+$
										   /ux',
										  '',
										  $fields[$i]
										);

										$fields[$i]=trim($fields[$i]);

										if(strtotime($line[$i]) && str_contains(strtoupper($fields[$i]), 'DATE'))
										{

											$date=strtotime($line[$i]);

											$line[$i]=date("d-M-Y", $date);

										}

										$arrRow=$arrRow + array($fields[$i] => $line[$i]);
									}

								}

							}

							if($id>0)
							{

								if(!empty($arrRow)){

									$arrRow=$arrRow + array('Update_Date' => date("d-M-Y"));

								 DB::table('tracker')
								 ->where('Id', '=',$id)
								 ->update($arrRow);
								}


							}
							else {

								if(!empty($arrRow)){

									$arrRow=$arrRow + array('Import_Date' => date("d-M-Y"));

								 DB::table('tracker')
								 ->insert($arrRow);
								}
							}

			    }

			    fclose($handle);
			} else {
			    // error opening the file.
			}

		return 1;

	}

	public function importhuaweipo(Request $request)
	{
		$me = (new CommonController)->get_current_user();

		$input = $request->all();

		$file = Input::file('import3');

		$destinationPath=public_path()."/private/upload/ImportData";
		$extension = $file->getClientOriginalExtension();
		$originalName=$file->getClientOriginalName();
		$fileSize=$file->getSize();
		$fileName=time().".".$extension;
		$upload_success = $file->move($destinationPath, $fileName);

		$excel = Excel::load($destinationPath."/".$fileName)->all()->toArray();

		$arrid=array();

		$arrpoitem=array();

		$arrpoupdate=array();

		$polist = DB::table('tracker')
		->select('Id','PO No','PO Line No','Shipment No','PO_Status','Quantity_Request','Due_Quantity','PO_Amount','U/Price')
		->orderBy('PO_No','ASC')
		->get();

		foreach ($polist as $key => $value) {
			# code...

			array_push($arrpoitem,$value->{"PO No"}."-".$value->{"PO Line No"}."-".$value->{"Shipment No"});
			array_push($arrpoupdate,$value->{"PO_Status"}."-".$value->{"Quantity_Request"}."-".$value->{"Due_Quantity"});
			array_push($arrid,$value->{"Id"});

		}

		$arrinsert=array();
			# code...

			foreach ($excel as $row) {
				# code...

					//import PO

					$podate=date('d-M-Y',strtotime($row["publish_date"]));

							if(array_search($row["po_no"]."-".$row["po_line_no"] ."-".$row["shipment_no"],$arrpoitem)!==false)
							{
								//already exist no need import

								$index=array_search($row["po_no"]."-".$row["po_line_no"] ."-".$row["shipment_no"],$arrpoitem);

								$content=$row["po_status"]."-".$row["requested_qty"] ."-".$row["due_qty"];
								$id=$arrid[$index];

								if($content!=$arrpoupdate[$index])
								{

									DB::table('tracker')
									->where('Id', '=',$id)
											->update(array(
												'PO_Status' => $row["po_status"],
												'Quantity_Request' => $row["requested_qty"],
												'Due_Quantity' => $row["due_qty"],
												'Description' => $row["item_description"],
												'U/Price' => $row["unit_price"],
												'PO_Amount' => $row["line_account"],
												'Updated_Date' => date('d-M-Y')

										));

								}

							}
							else {

								$insert=DB::table('tracker')->insertGetId(
									[
									'DUID' => $row["site_code"],
									'Site Name' => $row["site_name"],
									'PO_Status' => $row["po_status"],
									'PO No' => $row["po_no"],
									'PO Line No' => $row["po_line_no"],
									'Shipment No' => $row["shipment_no"],
									'Description' => $row["item_description"],
									'Quantity_Request' => $row["requested_qty"],
									'Due_Quantity' => ''.$row["due_qty"].'',
									'U/Price' => $row["unit_price"],
									'PO_Amount' => $row["line_account"],
									'PO Date' => $podate,
									'Import_Date' => date('d-M-Y')


									]
								);

								array_push($arrpoitem,$row["po_no"]."-".$row["po_line_no"] ."-".$row["shipment_no"]);

							}


					}

	return 1;
	}

	function Get_Week_No($date)
	{

			$date2 = new DateTime($date);
			return intval($date2->format("W"));

	}

	function GetDateString()
	{

			date_default_timezone_set('Asia/Kuala_Lumpur');
			return date("d-M-Y");

	}

	public function updatepocolumn(Request $request)
	{
		$me = (new CommonController)->get_current_user();

		$input = $request->all();

		$tracker = DB::table('tracker')
		->select($input["PO"].' AS PO')
		->where('Id', '=',$input["Id"])
		->first();

		if($tracker)
		{
			$arrpo=explode(" | ",$tracker->PO);
			$date=$input["Date"];
			$updatecolumn=$input["UpdateColumn"];

			if(sizeof($arrpo)>0)
			{

					$po=$arrpo[0];
					$shipment=$arrpo[1];
					$sitename=$arrpo[2];

					if($shipment=="")
					{
						$shipment=0;
					}

					DB::table('po')
					->where('Shipment_Num', '=',$shipment)
					->where('Site_Name', '=',$sitename)
					->where('PO_No', '=',$po)
					->update([$updatecolumn=>$date]);
			}
			else {
				# code...
				$po=$arrpo[0];
				$shipment=$arrpo[1];
				$sitename=$arrpo[2];
			}

		}

		return 1;

	}

	public function modelling()
	{

		$me = (new CommonController)->get_current_user();

		return view("modelling",['me'=>$me]);

	}

	public function filerenderer($category,$trackerid)
	{

		$me = (new CommonController)->get_current_user();

		$files = DB::table('files')
		->where('TargetId', '=',$trackerid)
		->where('Document_Type', '=',$category)
		->where('Type', '=','Tracker')
		->get();

		return view("filerenderer",['me'=>$me,'category'=>$category,'files'=>$files]);

	}

	public function filerenderer2($trackerid,$option)
	{
		$me = (new CommonController)->get_current_user();

		// $files = DB::table('options')
		// ->leftJoin('files','files.Document_Type','=', DB::raw('options.Option AND files.TargetId='.$trackerid))
		// ->leftJoin('users','files.UserId','=','users.Id')
		// ->where('files.TargetId', '=',$trackerid)
		// ->where('options.Option', '=',$option)
		// ->where('Type', '=','Tracker')
		// ->get();

		$documentaccess = DB::table('documenttypeaccess')
		->where('AccessControlTemplateId','=', $me->AccessControlTemplateId)
		->where('Document_type','=', $option)
		->orderBy('documenttypeaccess.Document_Type','asc')
		->first();

		$files = DB::table('options')
		->select('files.Web_Path', 'files.File_Name', 'files.UserId','users.Name','files.created_at', 'files.TargetId', 'options.Option', 'files.Id')
		->leftJoin('files','files.Document_Type','=', DB::raw('options.Option AND files.TargetId='.$trackerid))
		->leftJoin('users','files.UserId','=','users.Id')
		->leftJoin('tracker','tracker.Id', '=', 'files.TargetId')
		->where('files.TargetId', '=',$trackerid)
		->where('options.Option', '=',$option)
		->where('files.Type', '=','Tracker')
		->groupBy('files.Id')
		->get();
		

		if(!$files)
		{
			$me = (new CommonController)->get_current_user();

			$files = DB::table('tracker')
			->select(DB::raw('null as Web_Path'), DB::raw('null as File_Name'), DB::raw('"'.$option .'" as `Option`'),DB::raw('null as UserId'), DB::raw($trackerid .' as TargetId'))
			->where('tracker.Id', '=', $trackerid)
			// ->where('options.Option', '=',$option)
			->limit(1)
			->get();
		}

		// dd($files);

		//Default value
		$category2 = DB::table('options')
		->select('options.Option',DB::raw('tracker.Id AS TargetId'))
		->leftJoin('files','files.Document_Type','=', DB::raw('options.Option'))
		->leftJoin('users','files.UserId','=','users.Id')
		->where('options.Field', '=','Document_Type')
		->where('tracker.Id', '=', DB::raw($trackerid))
		->where('options.Option', '=',$option)
		->whereNotIn('options.Option',['Panaromic Photos','Site Photos','Videos'])
		->groupBy('options.Option', 'tracker.Id')
		->get();

		$files2 = DB::table('options')
		->where('options.Option', '=',$option)
		->get();

		$empty = DB::table('options')
		// ->select('files.Id')
		->select(DB::raw('CASE WHEN files.Id IS NOT NULL THEN 1 ELSE 0 END AS Id'))
		->leftJoin('files','files.Document_Type','=', DB::raw('options.Option'))
		->leftJoin('users','files.UserId','=','users.Id')
		->where('options.Field', '=','Document_Type')
		->where('tracker.Id', '=', DB::raw($trackerid))
		->where('options.Option', '=',$option)
		->whereNotIn('options.Option',['Panaromic Photos','Site Photos','Videos'])
		->groupBy('options.Option', 'tracker.Id')
		->get();

		$site = DB::table('tracker')
		->where('Id','=', $trackerid)
		->first();

		// dd($files, $category2, $empty);

		return view("filerenderer2",['me'=>$me,'files'=>$files, 'files2'=>$files2,'type'=>$option,'trackerid'=>$trackerid, 'category2'=>$category2, 'empty'=>$empty,'documentaccess'=>$documentaccess,'site'=>$site]);

	}

	public function filerenderer2delete($Id)
	{
		// $input = $request->all();

		$file = DB::table('files')
		->where('Id','=', $Id)
		->first();

		DB::table('files')
		->where('Id', '=', $Id)
		->delete();

		$options = DB::table('options')
		->select('options.Id','options.Table','options.Field','options.Option','options.Update_Column')
		->where('options.Option', '=',$file->Document_Type)
		->orderBy('options.Field', 'asc')
		->first();

		$files = DB::table('files')
		->where('Type','=', 'Tracker')
		->where('TargetId','=', $file->TargetId)
		->where('Document_Type','=', $file->Document_Type)
		->get();

		if(!$files && $options->Update_Column)
		{

			if($options->Update_Column)
			{
				$options->Update_Column=htmlspecialchars_decode($options->Update_Column);
				DB::table('tracker')
				->where('Id', '=',$file->TargetId)
				->update([$options->Update_Column=>'']);
			}

		}


		return redirect()->back()->with('message', 'File successfully removed.');

	}

	public function deleteallfiles(Request $request)
	{
		$input = $request->all();

		if($input["TrackerId"] && $input["Type"])
		{
			$result=DB::table('files')
			->where('TargetId', '=', $input["TrackerId"])
			->where('Document_Type', '=', $input["Type"])
			->delete();

		}

		return redirect()->back()->with('message', 'File successfully removed.');

	}


	public function filecategory($trackerid)
	{

		$me = (new CommonController)->get_current_user();

		$category = DB::table('files')
		->where('TargetId', '=',$trackerid)
		->where('Type', '=','Tracker')
		->distinct()->get(['Document_Type']);

		return view("filecategory",['me'=>$me,'category'=>$category,'trackerid'=>$trackerid]);

	}

	//Firdaus 20180621 - New Controller for displaying Folders in Submitted Document (Drag n Drop)
	public function filecategory2($trackerid)
	{

		$me = (new CommonController)->get_current_user();

		// $input = $request->all();
		// $TrackerId=$input["Id"];

		$category2 = DB::table('options')
		->select('files.Id','options.Option','options.Section','options.Description','files.created_at AS Submission_Date','users.Name as Submitted_By',DB::raw("files.Web_Path AS Download"),'files.File_Name',DB::raw("'' AS Upload"),DB::raw('tracker.Id AS TargetId'),'files.UserId','documenttypeaccess.Read')
		->leftJoin('files','files.Document_Type','=', DB::raw('options.Option'))
		->leftJoin('users','files.UserId','=','users.Id')
		->leftJoin('documenttypeaccess','documenttypeaccess.Document_Type','=','options.Option')
		->where('options.Field', '=','Document_Type')
		->where('tracker.Id', '=', DB::raw($trackerid))
		->whereNotIn('options.Option',['Panaromic Photos','Site Photos','Videos'])
		->where('documenttypeaccess.read', '=','1')
		->whereRaw('documenttypeaccess.AccessControlTemplateId='.$me->AccessControlTemplateId)
		// ->where('Type', '=','Tracker')
		// ->distinct()->get(['Document_Type']);
		->groupBy('options.option')
		->orderBy('options.Section')
		->orderByRaw('CAST(options.Description as SIGNED)')
		->get();

		$files = DB::table('options')
		->select(DB::raw('files.Document_Type,Count(DISTINCT files.Id) as count'))
		->leftJoin('files','files.Document_Type','=', DB::raw('options.Option AND files.TargetId='.$trackerid))
		->leftJoin('users','files.UserId','=','users.Id')
		->leftJoin('tracker','tracker.Id', '=', 'files.TargetId')
		->where('files.TargetId', '=',$trackerid)
		->where('files.Type', '=','Tracker')
		->groupBy('options.Option')
		->get();
		

		$documentaccess = DB::table('documenttypeaccess')
		->where('AccessControlTemplateId','=', $me->AccessControlTemplateId)
		->orderBy('documenttypeaccess.Document_Type','asc')
		->get();

		$site = DB::table('tracker')
		->where('Id','=', $trackerid)
		->first();

		// return view("filecategory",['me'=>$me,'category'=>$category,'trackerid'=>$trackerid]); //original
		return view('filecategory2',['me' => $me,'category2'=>$category2,'documentaccess'=>$documentaccess,'site'=>$site,'files'=>$files]);

	}

	public function sitefolder()
	{

		$me = (new CommonController)->get_current_user();

		$sitelist = DB::table('tracker')
		->get();

		return view('sitefolder',['me' => $me,'sitelist'=>$sitelist]);

	}

	public function site()
	{

		$me = (new CommonController)->get_current_user();

		return view("pannellum",['me'=>$me]);

	}

	public function farend1()
	{

		$me = (new CommonController)->get_current_user();

		return view("farend1",['me'=>$me]);

	}

	public function farend2()
	{

		$me = (new CommonController)->get_current_user();

		return view("farend2",['me'=>$me]);

	}

	public function decodeqr()
	{

		$me = (new CommonController)->get_current_user();

		return view("decodeqr",['me'=>$me]);

	}

	function removeBomUtf8($s){
	if(substr($s,0,3)==chr(hexdec('EF')).chr(hexdec('BB')).chr(hexdec('BF'))){
			 return substr($s,3);
	 }else{
			 return $s;
	 }
}

public function duplicatetracker(Request $request)
{

		$me = (new CommonController)->get_current_user();

		$this->validate($request, [
			'TrackerName' => 'required',
			'ExistingTrackerId' => 'required',
		]);


		$input = $request->all();

		$exist = DB::table('trackertemplate')
		->where('Id', '=', $input["ExistingTrackerId"])
		->get();

		if(!$exist)
		{

		}
		else {


			$insert=DB::table('trackertemplate')->insertGetId(
				[
				'Tracker_Name' => $input["TrackerName"],
				'Combine'=>$input["TrackerName"]
				]
			);

			DB::table('templateaccess')
						->insertGetId(array(
						'TrackerTemplateId' => $insert,
						'UserId' => $me->UserId
					));

			DB::table('templatewriteaccess')
						->insertGetId(array(
						'TrackerTemplateId' => $insert,
						'UserId' => $me->UserId
					));

			$columns = DB::table('trackercolumn')
			->where('TrackerTemplateID', '=', $input["ExistingTrackerId"])
			->get();

			$arrInsert=array();

			foreach ($columns as $col) {

				array_push($arrInsert,array('TrackerTemplateID'=>$insert, 'Column_Name'=> $col->Column_Name,'Data_Type'=>$col->Data_Type,'Type'=>$col->Type,'Sequence'=>$col->Sequence,'Color_Code'=>$col->Color_Code));

			}

			 DB::table('trackercolumn')->insert($arrInsert);
			 return 1;
			}

		return 0;

	}

	public function deletetracker(Request $request)
	{

			$me = (new CommonController)->get_current_user();

			$input = $request->all();

			DB::table('trackercolumn')
			->where('TrackerTemplateId', '=', $input["TrackerId"])
			->delete();

			DB::table('trackertemplate')
			->where('Id', '=', $input["TrackerId"])
			->delete();

			return 1;

		}

		public function updatedependency($column,$id)
		{

			$me = (new CommonController)->get_current_user();

			//dependency rules
			$dependencyrules = DB::table('dependencyrules')
			->where('dependencyrules.Active','=','1')
			// ->where('dependencyrules.Column1','=',$column)
			->orderBy('dependencyrules.Sequence','asc')
			->get();


			// $trackerid=$input["Id"];

			foreach ($dependencyrules as $rules) {

				$col1=$rules->Column1;
				$col2=$rules->Column2;
				$col3=$rules->Column3;

				$updatecol=$rules->Target_Column;
				$update=$rules->Target_Status;

				$line = DB::table('tracker')
				->where('Id', '=',$id)
				->first();

				$condition="1";

				if($line)
				{

					if($rules->Column1 && $rules->Column1_Status=="[nonempty]")
					{
						$condition=$condition." AND `".$rules->Column1."`!=''";
					}

					if($rules->Column2 && $rules->Column2_Status=="[nonempty]")
					{
						$condition=$condition." AND `".$rules->Column2."`!=''";
					}

					if($rules->Column3 && $rules->Column3_Status=="[nonempty]")
					{
						$condition=$condition." AND `".$rules->Column3."`!=''";
					}

				}

				if($rules->Target_Column && $rules->Target_Status && $condition!="1")
				{
					DB::table('tracker')
					->whereRaw($condition)
					->update([$rules->Target_Column =>$rules->Target_Status]);

				}

			}
			//end of dependency rules

			//notify
			$dependencyrules = DB::table('dependencyrules')
			->where('dependencyrules.Active','=','1')
			->whereRaw('(dependencyrules.Column1="'.$column.'" OR dependencyrules.Column2="'.$column.'" OR dependencyrules.Column3="'.$column.'")')
			->orderBy('dependencyrules.Sequence','asc')
			->get();

			foreach ($dependencyrules as $rule) {
				// code...
				$condition="1";
				if($rules->Column1 && $rules->Column1_Status=="[nonempty]")
				{
					$condition=$condition." AND `".$rules->Column1."`!=''";
				}

				if($rules->Column2 && $rules->Column2_Status=="[nonempty]")
				{
					$condition=$condition." AND `".$rules->Column2."`!=''";
				}

				if($rules->Column3 && $rules->Column3_Status=="[nonempty]")
				{
					$condition=$condition." AND `".$rules->Column3."`!=''";
				}

				$line = DB::table('tracker')
				->where('Id', '=',$id)
				->whereRaw($condition)
				->first();

				if($line)
				{
					$notify = DB::table('users')
					->leftJoin('dependencynotification','dependencynotification.UserId','=','users.Id')
					->where('DependencyRulesId', '=',$rules->Id)
					->get();

					$emails = array();

					foreach ($notify as $user) {
						if ($user->Company_Email!="")
						{
							array_push($emails,$user->Company_Email);
						}

						else
						{
							array_push($emails,$user->Personal_Email);
						}
					}

					$line = DB::table('tracker')
					->select('Site_ID','Site_Name',DB::raw($column.' As "Target"'))
					->where('tracker.Id', '=',$id)
					->first();

					if($emails)
					{
						// Mail::send('emails.dependencynotification', ['me' => $me,'column' => $column,'line'=>$line], function($message) use ($emails,$column)
						// {
						// 	$emails = array_filter($emails);
						// 	array_push($emails,env('MAIL_DEFAULT_RECIPIENT'));
						// 	$message->to($emails)->subject('Dependency Notification ['.$column.']');

						// });
					}

				}

			}

			//end notidy

		}

		public function gettemplateaccess(Request $request)
		{

			$me = (new CommonController)->get_current_user();

			$input = $request->all();

			$tracker = DB::table('trackertemplate')
			->select('trackertemplate.Id','trackertemplate.Tracker_Name','templateaccess.Id as Read','templatewriteaccess.Id as Write','templatedeleteaccess.Id as Delete')
			->leftJoin('templateaccess','templateaccess.TrackerTemplateId','=',DB::raw('trackertemplate.Id AND templateaccess.UserId='.$input["UserId"]))
			->leftJoin('templatewriteaccess','templatewriteaccess.TrackerTemplateId','=',DB::raw('trackertemplate.Id AND templatewriteaccess.UserId='.$input["UserId"]))
			->leftJoin('templatedeleteaccess','templatedeleteaccess.TrackerTemplateId','=',DB::raw('trackertemplate.Id AND templatedeleteaccess.UserId='.$input["UserId"]))
			->get();

			return json_encode($tracker);

		}

		public function updatetemplateaccess(Request $request)
		{

			$me = (new CommonController)->get_current_user();

			$input = $request->all();

			DB::table('templateaccess')
			->where('UserId', '=',$input["UserId"])
			->delete();

			DB::table('templatewriteaccess')
			->where('UserId', '=',$input["UserId"])
			->delete();

			DB::table('templatedeleteaccess')
			->where('UserId', '=',$input["UserId"])
			->delete();

			foreach(explode(",",$input["Read"]) as $trackerId)
			{
				if($trackerId)
				{
					DB::table('templateaccess')
		 						->insertGetId(array(
		 						'TrackerTemplateId' => $trackerId,
		 						'UserId' => $input["UserId"]
		 					));
				}

			}

			foreach(explode(",",$input["Write"]) as $trackerId)
			{
				if($trackerId)
				{
					DB::table('templatewriteaccess')
								->insertGetId(array(
								'TrackerTemplateId' => $trackerId,
								'UserId' => $input["UserId"]
							));
				}

			}

			foreach(explode(",",$input["Delete"]) as $trackerId)
			{
				if($trackerId)
				{
					DB::table('templatedeleteaccess')
								->insertGetId(array(
								'TrackerTemplateId' => $trackerId,
								'UserId' => $input["UserId"]
							));
				}


			}

			return 1;

		}

		public function updatesite(Request $request)
	 {

		 $me = (new CommonController)->get_current_user();

		 $input = $request->all();

		 $trackercolumns = DB::table('trackercolumn')
		 ->select()
		 ->where('trackercolumn.TrackerTemplateID','=',$input["TrackerId"])
		 ->orderBy('trackercolumn.Sequence','ASC')
		 ->get();

		 foreach ($input as $key => $value) {
			 # code...

			 if($key=="UpdateId")
			 {
				 $update=$input['UpdateId'];
				 $input["Id"] = $input['UpdateId'];
				 unset($input['UpdateId']);
			 }
			 else if($key=="TrackerId")
			 {
				 unset($input['TrackerId']);

			 }
			 else {
				 # code...

				 $key2=trim($key,"2");
				 $input[$key2] = $input[$key];
				 unset($input[$key]);

				 $keyname=preg_replace('/[^\p{L}\p{N}\s]/u', '', $key2);

				 foreach ($trackercolumns as $col) {
					// code...
					$colname= preg_replace('/[^\p{L}\p{N}\s]/u', '', $col->Column_Name);
					$colname=str_replace(" ","",$colname);

					if(strtoupper($colname)==strtoupper($keyname))
					{
							$input[$col->Column_Name] = $input[$key2];
							unset($input[$key2]);
							break;
					}
				 }

			 }
		 }

		 $result=DB::table('tracker')
		 ->where('Id', '=',$update)
		 ->update($input);

			DB::table('trackerupdate')
				 ->insert(array(
				 'TrackerId' =>$update,
				 'Type' =>'Update',
				 'UserId' =>$me->UserId,
				 'Details' => json_encode($input),
			 ));

		 return $result;

		}

		public function createdocumenttype(Request $request)
		{

				$me = (new CommonController)->get_current_user();

				$this->validate($request, [
					'DocumentType' => 'required'
				]);


				$input = $request->all();

				$exist = DB::table('options')
				->where('Table','=','tracker')
				->where('Field','=','Document_Type')
				->where('Option', '=', $input["DocumentType"])
				->first();

				if($exist)
				{
					$optionid=$exist->Id;

					$insert=DB::table('documenttypeaccess')->insertGetId(
						['AccessControlTemplateId' =>$me->AccessControlTemplateId,
						'Document_Type' => $input["DocumentType"],
						'Read' => 1,
						'Write' => 1
						]
					);
				}
				else {
					// code...

					$optionid=DB::table('options')->insertGetId(
						['UserId' =>$me->UserId,
						'Table' => 'tracker',
						'Field' => 'Document_Type',
						'Option' => $input["DocumentType"]
						]
					);

					$insert=DB::table('documenttypeaccess')->insertGetId(
						['AccessControlTemplateId' =>$me->AccessControlTemplateId,
						'Document_Type' => $input["DocumentType"],
						'Read' => 1,
						'Write' => 1
						]
					);

				}

				return 1;

			}

			public function downloadall(Request $request)
			{
				$input = $request->all();
				$me = (new CommonController)->get_current_user();

				$documentaccess = DB::table('documenttypeaccess')
				->where('AccessControlTemplateId','=', $me->AccessControlTemplateId)
				->where('Document_type','=', $input["Type"])
				->orderBy('documenttypeaccess.Document_Type','asc')
				->first();

				$files = DB::table('options')
				->select('files.Web_Path')
				->leftJoin('files','files.Document_Type','=', DB::raw('options.Option AND files.TargetId='.$input["TrackerId"]))
				->leftJoin('users','files.UserId','=','users.Id')
				->leftJoin('tracker','tracker.Id', '=', 'files.TargetId')

				->where('files.TargetId', '=',$input["TrackerId"])
				->where('options.Option', '=',$input["Type"])
				->where('files.Type', '=','Tracker')
				->groupBy('files.Id')
				->get();

				$filearray=array();
				foreach($files as $f)
				{
					array_push($filearray,public_path($f->Web_Path));
				}

				$headers = array(
                  'Content-Type'=> 'application/zip'
                );

				$now=Carbon::now()->timestamp;
				Zipper::make(public_path('private/zip/Download').$now.'.zip')->add($filearray)->close();
    		return url('private/zip/Download').$now.'.zip';

			}

			public function createmultiplerecord(Request $request)
			{
				$me = (new CommonController)->get_current_user();

				$input = $request->all();

				$Ids=$input["Ids"];

					if($Ids)
					{

						$split1=explode(",",$Ids);

						foreach ($split1 as $val) {
							// code...
							$split2=explode("|",$val);

							$trackerline = DB::table('tracker')
							->where('tracker.Id', '=', $split2[0])
							->first();

							if($trackerline)
							{

								if(starts_with(strtoupper($split2[1]), 'PO'))
 							 {
 								 $milestone=substr($split2[1], 0, strpos($split2[1], '_'));

 								 $milestone=str_replace("_","",$milestone);
 								 $milestone=str_replace("-","",$milestone);
 								 $milestone=trim($milestone);
 							 }
 							 else {
 							 	// code...
 								$milestone=substr($split2[1], 0, strpos($split2[1], 'PO'));

 								$milestone=str_replace("_","",$milestone);
 								$milestone=str_replace("-","",$milestone);
 								$milestone=trim($milestone);
 							 }

								$insert=DB::table('invoicelisting')->insertGetId(array(
									 'TrackerId' => $split2[0],
									 'Customer' => $trackerline->Customer,
									 'Year' => date("Y"),
									 'PO_Milestone' =>$milestone,
									 'PO_No' =>$trackerline->{$split2[1]},
									 'PO_Date' =>$trackerline->{str_replace("No","Date",$split2[1])},
									 'PO_Amount' =>str_replace("RM","",str_replace(",","",$trackerline->{str_replace("No","Amount",$split2[1])})),
									 'created_by' =>$me->UserId

								 ));

							}
							else {
								// code...
								return -1;
							}
						}

					}

					return 1;

			}

			public function searchrecord(Request $request)
			{
				$me = (new CommonController)->get_current_user();

				$input = $request->all();
				$type=$input["Type"];
				$PO=$input["PO"];
				$Site=$input["Site"];

				$filter="1 AND ";

					$allavailablecolumns= DB::table('trackercolumn')
					->select(DB::raw('DISTINCT (Column_Name) As Col'))
					->whereRaw('Column_Name like "%PO%" AND Column_Name like "%No%" AND Column_Name not like "%Invoice%"')
					->orderBy('Column_Name')
					->get();

				if($Site)
				{

					$filter.="(tracker.`Site ID / LRD` like '%".$Site."%' OR tracker.`Site ID` like '%".$Site."%' OR tracker.`LRD` like '%".$Site."%' OR tracker.`Site LRD` like '%".$Site."%') AND ";
				}

				$filter=substr($filter,0,strlen($filter)-5);

				switch ($type) {
			    case "Invoice":
					$query="";
							foreach($allavailablecolumns as $col)
							{
								if($PO)
								{
									$query.="SELECT Id,CONCAT(tracker.`Site ID / LRD`,'-',tracker.`Site ID`,'-',tracker.`LRD`,'-',tracker.`Site LRD`) as 'Site_ID','".$col->Col."' as PO_Milestone,`".$col->Col."` as PO_No FROM tracker WHERE ".$filter." AND `".$col->Col."`='".$PO."' UNION ALL ";
								}
								else {
									// code...
									$query.="SELECT Id,CONCAT(tracker.`Site ID / LRD`,'-',tracker.`Site ID`,'-',tracker.`LRD`,'-',tracker.`Site LRD`) as 'Site_ID','".$col->Col."' as PO_Milestone,`".$col->Col."` as PO_No FROM tracker WHERE ".$filter." AND `".$col->Col."`!='' UNION ALL ";
								}

							}
			        break;

				}

				if($query)
				{
					$query=substr($query,0,strlen($query)-11);
					$result = DB::select($query);
				}
				else {
					// code...
					$result="[]";
				}

				return json_encode($result);

			}

			public function trackerupdatetracker($start=null,$end=null)
			{
				$me = (new CommonController)->get_current_user();

				if ($start==null)
				{
					$start=date('d-M-Y', strtotime('today'));

				}

				if ($end==null)
				{
					$end=date('d-M-Y', strtotime('today'));
					$end=date('d-M-Y', strtotime($end . " +1 days"));
				}

				$logs = DB::select("
						SELECT trackerupdate.Type,
							tracker.`Unique ID`,
							CASE
						  	WHEN trackerupdate.Type='Delete' THEN trackerupdate.Site_ID
						  ELSE
								tracker.`Site ID`
						  END as 'Site_ID',
							CASE
						  	WHEN trackerupdate.Type='Delete' THEN trackerupdate.Site_Name
						  ELSE
								tracker.`Site Name`
						  END as 'Site_Name',

						trackerupdate.Details,users.Name As Updated_By,trackerupdate.Updated_At
						FROM trackerupdate
						LEFT JOIN tracker ON trackerupdate.TrackerId=tracker.Id
						LEFT JOIN users ON users.Id = trackerupdate.UserId
						WHERE `trackerupdate`.`Updated_At` Between str_to_date('".$start."','%d-%M-%Y') AND str_to_date('".$end."','%d-%M-%Y')
						Order By Updated_At Asc");
		    return view("trackerupdatetracker",['me'=>$me, 'logs'=>$logs, 'start'=>$start, 'end'=>$end]);
		  }

			public function opendocument($id,$col)
			{

				$me = (new CommonController)->get_current_user();

				$col=str_replace("|||","/",$col);

				$files = DB::table('options')
				->select('files.Web_Path', 'files.File_Name', 'files.UserId','users.Name','files.created_at', 'files.TargetId', 'options.Option', 'files.Id')
				->leftJoin('files','files.Document_Type','=', DB::raw('options.Option AND files.TargetId='.$id))
				->leftJoin('users','files.UserId','=','users.Id')
				->leftJoin('tracker','tracker.Id', '=', 'files.TargetId')

				->where('files.TargetId', '=',$id)
				->where('options.Update_Column', '=',$col)
				->where('files.Type', '=','Tracker')
				->groupBy('files.Id')
				->first();

				if($files)
				{
					$documentaccess = DB::table('documenttypeaccess')
					->where('AccessControlTemplateId','=', $me->AccessControlTemplateId)
					->where('Document_type','=', $files->Option)
					->orderBy('documenttypeaccess.Document_Type','asc')
					->first();


					if($documentaccess->Read)
					{
						return redirect()->to(url($files->Web_Path));
					}
					else {
						// code...
						return view("errors/nopermission");
					}

				}
				else {
					// code...

						return view("errors/nodocument");
				}



			}

	function sendNotification(array $playerids, $title, $message, $type)
	{
		$heading = array(
            "en" => $title
        );

	    $content = array(
	        "en" => $message
	    );

	    $fields = array(
	        'app_id' 				=> "b22a7a60-2cfa-4641-a309-4720c564fddf",
	        'include_player_ids' 	=> $playerids,
	        'data'					=> array("type" => $type),
	        'contents' 				=> $content,
	        'headings'              => $heading
	    );

	    $fields = json_encode($fields);

	    $ch = curl_init();
	    curl_setopt($ch, CURLOPT_URL, "https://onesignal.com/api/v1/notifications");
	    curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/json; charset=utf-8','Authorization: Basic NWU1YjI2ZmYtOTM3NS00NWRkLTk2YTYtOTM5N2Y3NGJhNDY4'));
	    curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
	    curl_setopt($ch, CURLOPT_HEADER, FALSE);
	    curl_setopt($ch, CURLOPT_POST, TRUE);
	    curl_setopt($ch, CURLOPT_POSTFIELDS, $fields);
	    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);

	    $response = curl_exec($ch);
	    curl_close($ch);
	}

	public function cashbook($start=null,$end=null,$company=null){

		$me=(new CommonController)->get_current_user();

		$cond="1";

		if($company && $company!="false")
		{
			$cond.=" AND users.Company='".$company."'";
		}

		if ($start==null)
		{
			$start=date('d-M-Y');
			// $start=date('d-M-Y', strtotime($start,' +16 days'));
			// $start = date('d-M-Y', strtotime($start . " +20 days"));
		}
		if ($end==null)
		{
			// $end=date('d-M-Y', strtotime('first day of this month'));
			$end = date('d-M-Y', strtotime($start . " +6 days"));
		}

		$condition="str_to_date(ewallet.Date,'%d-%M-%Y')>=str_to_date('".$start."','%d-%M-%Y') AND str_to_date(ewallet.Date,'%d-%M-%Y')<=str_to_date('".$end."','%d-%M-%Y')";

		$summary = DB::table('ewallet')
		->select(
			DB::raw('ewallet.DocNo'),
			DB::raw('DATE_FORMAT(str_to_date(ewallet.Date,"%d-%M-%Y"),"%d/%m/%Y") as DocDate'),
			DB::raw('"PV" as DocType'),
			DB::raw('users.Name as DealWith'),
			DB::raw('"CASH PAYMENT" as Description'),
			DB::raw('"MYR" as CurrencyCode'),
			DB::raw('"1.00000000" as CurrencyRate'),
			DB::raw('"" as Note'),
			DB::raw('case when ewallet.Expenses_Type="Hardware" then "6001/H01" when ewallet.Expenses_Type="Hotel" then "6001/A01" when ewallet.Expenses_Type="Petrol Or Tol" then "6001/P01" when ewallet.Expenses_Type="Petrol" then "6001/P01" when ewallet.Expenses_Type="Tol" then "6001/P01" when ewallet.Expenses_Type="Site Expenses" then "6001/S01" end as  AccNo'),
			DB::raw('"1.00000000" as ToAccountRate'),
			DB::raw('case when users.Company="Midascom Network Sdn Bhd" then "CME" when users.Company="OMNI AVENUE SDN BHD" then "SPEEDFREAK" when users.Company="MIDASCOM PERKASA SDN BHD" then "FABYARD" end as DeptNo'),
			DB::raw('"" as TaxType'),
			DB::raw('concat(ewallet.`Expenses_Type`,"-",ewallet.`Remarks`) as DetailDescription'),
			DB::raw('"" as FurtherDescription'),
			DB::raw('"" as SalesAgent'),
			DB::raw('ewallet.Amount as Amount'),
			DB::raw('"" as TaxableAmt'),
			DB::raw('"" as RCHQAmount'),
			DB::raw('"CASH IN HAND - FION" as PaymentMethod'),
			DB::raw('"CIH" as ChequeNo'),
			DB::raw('ewallet.Amount as PaymentAmt'),
			DB::raw('"" as BankCharge'),
			DB::raw('"1.00000000" as ToBankRate'),
			DB::raw('"CASH" as PaymentBy'),
			DB::raw('"" as FloatDay'),
			DB::raw('"" as IsRCHQ'),
			DB::raw('"" as RCHQDate'))
		->leftJoin('tracker','ewallet.TrackerId','=','tracker.Id')
		->leftJoin('users','ewallet.UserId','=','users.Id')
		->leftJoin('users as creator','ewallet.created_by','=','creator.Id')
		->whereRaw($condition)
		->whereRaw($cond)
		->where('ewallet.Expenses_Type','!=','')
		->get();

		if(!$summary)
		{
			$summary = DB::table('ewallet')
			->select(
				DB::raw('"" as DocNo'),
				DB::raw('"" as DocDate'),
				DB::raw('"" as DocType'),
				DB::raw('"" as DealWith'),
				DB::raw('"" as Description'),
				DB::raw('"" as CurrencyCode'),
				DB::raw('"" as CurrencyRate'),
				DB::raw('"" as Note'),
				DB::raw('"" as AccNo'),
				DB::raw('"" as ToAccountRate'),
				DB::raw('"" as ProjNo'),
				DB::raw('"" as DeptNo'),
				DB::raw('"" as TaxType'),
				DB::raw('"" as DetailDescription'),
				DB::raw('"" as FurtherDescription'),
				DB::raw('"" as SalesAgent'),
				DB::raw('"" as Amount'),
				DB::raw('"" as TaxableAmt'),
				DB::raw('"" as RCHQAmount'),
				DB::raw('"" as PaymentMethod'),
				DB::raw('"" as ChequeNo'),
				DB::raw('"" as PaymentAmt'),
				DB::raw('"" as BankCharge'),
				DB::raw('"" as ToBankRate'),
				DB::raw('"" as PaymentBy'),
				DB::raw('"" as FloatDay'),
				DB::raw('"" as IsRCHQ'),
				DB::raw('"" as RCHQDate'))
			->leftJoin('tracker','ewallet.TrackerId','=','tracker.Id')
			->leftJoin('users','ewallet.UserId','=','users.Id')
			->limit(1)
			->get();

		}

		$companies= DB::table('options')
		->whereIn('Table', ["users"])
		->where('Field','=','Company')
		->orderBy('Table','asc')
		->orderBy('Option','asc')
		->get();

		return view('cashbook',['me'=>$me, 'start'=>$start,'end'=>$end,'summary'=>$summary,'companies'=>$companies,'company'=>$company]);

	}

	public function apfinanceupdate($start=null, $end=null,$company=null)
	{
		$me = (new CommonController)->get_current_user();

		if ($start==null)
		{
			// $start=date('d-M-Y');
			$start = date('d-M-Y', strtotime($start . " -6 days"));
			// $start=date('d-M-Y', strtotime($start,' +16 days'));
			// $start = date('d-M-Y', strtotime($start . " +20 days"));
		}
		if ($end==null)
		{
			// $end=date('d-M-Y', strtotime('first day of this month'));
			$end = date('d-M-Y');
		}

		$condition="materialpo.`created_at`>=str_to_date('".$start."','%d-%M-%Y') AND materialpo.`created_at`<=str_to_date('".$end."','%d-%M-%Y')";

		$cond="1";

		if($company && $company!="false")
		{
			$cond.=" AND company.Company_Name like '".$company."%'";
		}

		$record = DB::table('materialpo')
		->select(
			'materialpo.Id',
			DB::raw('materialpo.PO_No as DocNo'),
			DB::raw('DATE_FORMAT(materialpo.created_at,"%d/%m/%Y") as DocDate'),
			DB::raw('vendor.CreditorCode'),
			DB::raw('materialpo.SupplierInvoiceNo'),
			DB::raw('case when company.Company_Name like "Midascom Network Sdn Bhd%" then "CME" when company.Company_Name like "OMNI AVENUE SDN BHD%" then "SPEEDFREAK" when company.Company_Name like "MIDASCOM PERKASA SDN BHD%" then "FABYARD" end as DeptNo'),
			DB::raw('users.Name as Created_By')
			)
		->leftjoin('material','material.Id','=','materialpo.MaterialId')
		->leftjoin('tracker','tracker.Id','=','material.TrackerId')
		->leftjoin('companies as vendor','company.Id','=','materialpo.VendorId')
		->leftjoin('companies as company','company.Company_Name','=','vendor.Company_Account')
		->leftjoin('users','users.Id','=','materialpo.created_by')
		->whereRaw('materialpo.Status!="Cancelled"')
		->whereRaw($condition)
		->whereRaw($cond)
		->get();

		$companies= DB::table('options')
		->whereIn('Table', ["users"])
		->where('Field','=','Company')
		->orderBy('Table','asc')
		->orderBy('Option','asc')
		->get();

		return view('apfinanceupdate', ['me' => $me,'record' => $record,'start' => $start, 'end' => $end,'companies'=>$companies,'company'=>$company]);

	}

	public function arinvoice($start=null,$end=null,$company=null){

		$me=(new CommonController)->get_current_user();

		if ($start==null)
		{
			// $start=date('d-M-Y');
			$start = date('d-M-Y', strtotime($start . " -6 days"));
			// $start=date('d-M-Y', strtotime($start,' +16 days'));
			// $start = date('d-M-Y', strtotime($start . " +20 days"));
		}
		if ($end==null)
		{
			// $end=date('d-M-Y', strtotime('first day of this month'));
			$end = date('d-M-Y');
		}
		$condition = "STR_TO_DATE(DocDate,'%d-%M-%Y') BETWEEN STR_TO_DATE('".$start."','%d-%M-%Y') AND  STR_TO_DATE('".$end."','%d-%M-%Y')";
		// $condition="salesorder.`invoice_date`>= '".$start."' AND salesorder.`invoice_date`<='".$end."'";

		$cond="1";

		if($company && $company!="null")
		{
			$cond.=" AND companies.Company_Name like '".$company."%'";
		}

		$summary = DB::table('salesorderitem')
		->select(
			DB::raw('(CASE
					WHEN salesorder.combined_invoice_num != ""
						THEN salesorder.combined_invoice_num
					ELSE
						salesorder.invoice_number
					END) as DocNo	'),
			DB::raw('(CASE
					WHEN salesorder.combined_invoice_date != ""
						THEN salesorder.combined_invoice_date
					ELSE
						salesorder.invoice_date
					END) as DocDate'),
			DB::raw('client.CreditorCode as CreditorCode'),
			DB::raw('"" as SupplierInvoiceNo'),
			DB::raw('"SALES" as JournalType'),
			DB::raw('salesorder.term as DisplayTerm'),
			DB::raw('"" as PurchaseAgent'),
			DB::raw('"SALES" as Description'),
			DB::raw('"1.00000000" as CurrencyRate'),
			DB::raw('salesorder.SO_Number as RefNo2'),
			DB::raw('"" as Note'),
			DB::raw('"5020/000" as AccNo'),
			DB::raw('"1.00000000" as ToAccountRate'),
			DB::raw('salesorderitem.description as DetailDescription'),
			DB::raw('client.type as ProjNo'),
			DB::raw('"SPEEDFREAK" as DeptNo'),
			DB::raw('"" as TaxType'),
			// DB::raw('FORMAT(salesorderitem.qty*salesorderitem.price,2) as TaxableAmt'),
			DB::raw('FORMAT(salesorder.total_amount,2) as TaxableAmt'),
			DB::raw('"" as TaxAdjustment'),
			DB::raw('FORMAT(salesorder.total_amount,2) as Amount')
			)
		->leftjoin('salesorder','salesorder.Id','=','salesorderitem.salesorderId')
		->leftJoin('companies','companies.Id','=','salesorder.companyId')
		->leftJoin('companies as client','client.Id','=','salesorder.clientId')
		->leftjoin('tracker','tracker.Id','=','salesorder.trackerid')
		->HavingRaw($condition)
		->whereRaw($cond)
		->get();
	// dd($summary);
		if(!$summary)
		{
			$summary=DB::table('salesorder')
			->select(
				DB::raw('"" as DocNo'),
				DB::raw('"" as DocDate'),
				DB::raw('"" as CreditorCode'),
				DB::raw('"" as SupplierInvoiceNo'),
				DB::raw('"" as JournalType'),
				DB::raw('"" as DisplayTerm'),
				DB::raw('"" as PurchaseAgent'),
				DB::raw('"" as Description'),
				DB::raw('"" as CurrencyRate'),
				DB::raw('"" as RefNo2'),
				DB::raw('"" as Note'),
				DB::raw('"" as AccNo'),
				DB::raw('"" as ToAccountRate'),
				DB::raw('"" as DetailDescription'),
				DB::raw('"" as ProjNo'),
				DB::raw('"" as DeptNo'),
				DB::raw('"" as TaxType'),
				DB::raw('"" as TaxableAmt'),
				DB::raw('"" as TaxAdjustment'),
				DB::raw('"" as Amount')
				)
			->limit(1)
			->get();

		}

		$companies= DB::table('companies')
		->where('Subsidiary','=','Yes')
		->orderBy('Company_Name','asc')
		->groupBy('Company_Name')
		->get();

		return view('arinvoice',['me'=>$me, 'start'=>$start,'end'=>$end,'summary'=>$summary,'companies'=>$companies,'company'=>$company]);

	}

	public function apinvoice($start=null,$end=null,$company=null){

		$me=(new CommonController)->get_current_user();

		if ($start==null)
		{
			// $start=date('d-M-Y');
			$start = date('d-M-Y', strtotime($start . " -6 days"));
			// $start=date('d-M-Y', strtotime($start,' +16 days'));
			// $start = date('d-M-Y', strtotime($start . " +20 days"));
		}
		if ($end==null)
		{
			// $end=date('d-M-Y', strtotime('first day of this month'));
			$end = date('d-M-Y');
		}

		$condition="materialpo.`created_at`>=str_to_date('".$start."','%d-%M-%Y') AND materialpo.`created_at`<=str_to_date('".$end."','%d-%M-%Y')";

		$cond="1";

		if($company && $company!="null")
		{
			$cond.=" AND company.Company_Name like '".$company."%'";
		}


		$summary = DB::table('materialpoitem')
		->select(
			DB::raw('"" as DocNo'),
			DB::raw('DATE_FORMAT(materialpo.created_at,"%d/%m/%Y") as DocDate'),
			DB::raw('vendor.CreditorCode'),
			DB::raw('materialpo.SupplierInvoiceNo'),
			DB::raw('"PURCHASE" as JournalType'),
			DB::raw('materialpo.Terms as DisplayTerm'),
			DB::raw('"" as PurchaseAgent'),
			DB::raw('"PURCHASES" as Description'),
			DB::raw('"1.00000000" as CurrencyRate'),
			DB::raw('materialpo.PO_No as RefNo2'),
			DB::raw('"" as Note'),
			DB::raw('materialpoitem.AccNo as AccNo'),
			DB::raw('"1.00000000" as ToAccountRate'),
			DB::raw('concat(materialpoitem.Description," ",materialpoitem.Add_Description) as DetailDescription'),
			DB::raw('case when company.Company_Name like "Midascom Network Sdn Bhd%" then "CME" when company.Company_Name like "OMNI AVENUE SDN BHD%" then "SPEEDFREAK" when company.Company_Name like "MIDASCOM PERKASA SDN BHD%" then "FABYARD" end as DeptNo'),
			// DB::raw('company.Company_Name as DeptNo'),
			DB::raw('"" as TaxType'),
			DB::raw('FORMAT(materialpoitem.Qty*materialpoitem.Price,2) as TaxableAmt'),
			DB::raw('"" as TaxAdjustment'),
			DB::raw('FORMAT(materialpoitem.Qty*materialpoitem.Price,2) as Amount')
			)
		->leftjoin('materialpo','materialpo.Id','=','materialpoitem.POId')
		->leftjoin('material','material.Id','=','materialpo.MaterialId')
		->leftjoin('tracker','tracker.Id','=','material.TrackerId')
		->leftjoin('inventoryvendor','inventoryvendor.Id','=','materialpo.VendorId')
		->leftjoin('companies as vendor','vendor.Id','=','materialpo.VendorId')
		->leftjoin('companies as company','company.Company_Name','=','vendor.Company_Account')
		->leftjoin('users','users.Id','=','materialpo.created_by')
		->whereRaw('materialpo.Status!="Cancelled"')
		->whereRaw($condition)
		->whereRaw($cond)
		->groupBy('materialpoitem.Id')
		->limit(500)
		->get();

		$charges = DB::table('deliveryform')
		 ->leftJoin( DB::raw('(select Max(Id) as maxid,deliveryform_Id from deliverystatuses Group By deliveryform_Id) as max'), 'max.deliveryform_Id', '=', 'deliveryform.Id')
        ->leftJoin('deliverystatuses', 'deliverystatuses.Id', '=', DB::raw('max.`maxid`'))
        ->leftJoin('companies as company','company.Id','=','deliveryform.client')
        ->leftJoin('radius','radius.Id','=','deliveryform.Location')
        ->select(
        	DB::raw('"" as DocNo'),
			DB::raw('STR_TO_DATE(deliverystatuses.created_at,"%Y-%m-%d") as DocDate'),
			DB::raw('company.CreditorCode as CreditorCode'),
			DB::raw('"" as SupplierInvoiceNo'),
			DB::raw('"PURCHASE" as JournalType'),
			DB::raw('"C.O.D" as DisplayTerm'),
			DB::raw('"" as PurchaseAgent'),
			DB::raw('"PURCHASES" as Description'),
			DB::raw('"1.00000000" as CurrencyRate'),
			DB::raw('deliveryform.DO_No as RefNo2'),
			DB::raw('"" as Note'),
			DB::raw('"6001/T01" as AccNo'),
			DB::raw('"1.00000000" as ToAccountRate'),
			DB::raw('"Transport Charges" as DetailDescription'),
			DB::raw(' TRIM( TRAILING ")" FROM TRIM( LEADING "(" FROM radius.Location_Name)) as ProjNo'),
			DB::raw('"" as TaxType'),
			DB::raw('deliveryform.charges as TaxableAmt'),
			DB::raw('"" as TaxAdjustment'),
			DB::raw('deliveryform.charges as Amount')
        )
        ->whereRaw("str_to_date(delivery_date,'%d-%M-%Y') >= str_to_date('".$start."','%d-%M-%Y') AND str_to_date(delivery_date,'%d-%M-%Y') <= str_to_date('".$end."','%d-%M-%Y') AND deliverystatuses.delivery_status = 'Completed' AND deliveryform.DO_NO NOT LIKE BINARY '%\_R%' AND deliveryform.charges != 0 ")
        ->whereRaw($cond)
        ->get();
        
        // $summary = $summary->union($charges)->get();

		if(!$summary)
		{
			$summary=DB::table('materialpo')
			->select(
				DB::raw('"" as DocNo'),
				DB::raw('"" as DocDate'),
				DB::raw('"" as CreditorCode'),
				DB::raw('"" as SupplierInvoiceNo'),
				DB::raw('"" as JournalType'),
				DB::raw('"" as DisplayTerm'),
				DB::raw('"" as PurchaseAgent'),
				DB::raw('"" as Description'),
				DB::raw('"" as CurrencyRate'),
				DB::raw('"" as RefNo2'),
				DB::raw('"" as Note'),
				DB::raw('"" as AccNo'),
				DB::raw('"" as ToAccountRate'),
				DB::raw('"" as DetailDescription'),
				DB::raw('"" as ProjNo'),
				DB::raw('"" as DeptNo'),
				DB::raw('"" as TaxType'),
				DB::raw('"" as TaxableAmt'),
				DB::raw('"" as TaxAdjustment'),
				DB::raw('"" as Amount')
				)
			->limit(1)
			->get();

		}

		$companies= DB::table('companies')
		->where('Subsidiary','=','Yes')
		->orderBy('Company_Name','asc')
		->groupBy('Company_Name')
		->get();

		return view('apinvoice',['me'=>$me, 'start'=>$start,'end'=>$end,'summary'=>$summary,'companies'=>$companies,'company'=>$company, 'charges'=>$charges]);

	}

	 public function apcreditnote($start = null, $end = null, $company = null)
    {
      $me = (new CommonController)->get_current_user();

      if(!$start)
      {
        $start = date('d-M-Y',strtotime('today - 7 days'));
      }

      if(!$end)
      {
        $end = date('d-M-Y',strtotime('today'));
      }

      $cond = "STR_TO_DATE(creditnote.date,'%d-%M-%Y') BETWEEN STR_TO_DATE('".$start."','%d-%M-%Y') AND STR_TO_DATE('".$end."','%d-%M-%Y')";
      if($company)
      {
        $cond.=" AND client.Company_Name like '%".$company."%' ";
      }

      $summary = DB::table('creditnote')
      ->leftJoin('salesorder','creditnote.salesorderId','=','salesorder.Id')
      ->leftJoin('companies','companies.Id','=','salesorder.companyId')
      ->leftJoin('companies as client','client.Id','=','salesorder.clientId')
      ->leftjoin('creditnoteitem','creditnoteitem.creditnoteId','=','creditnote.Id')
      ->select(
        	DB::raw('"" as DocNo'),
			DB::raw('STR_TO_DATE(creditnote.date,"%d-%M-%Y") as DocDate'),
			DB::raw('client.CreditorCode as DebtorCode'),
			DB::raw('"GENERAL" as JournalType'),
			DB::raw('"" as CNType'),
			DB::raw('"RM" as CurrencyCode'),
			DB::raw('"" as Description'),
			DB::raw('"" as Ref'),
			DB::raw('"" as RefNo2'),
			DB::raw('"" as Note'),
			DB::raw('"1" as CurrencyRate'),
			DB::raw('"500-0000" as AccNo'),
			DB::raw('"1.00000000" as ToAccountRate'),
			DB::raw('creditnoteitem.description as DetailDescription'),
			DB::raw('"" as ProjNo'),
			DB::raw('"" as DeptNo'),
			DB::raw('"" as TaxType'),
			DB::raw('creditnoteitem.amount as TaxableAmt'),
			DB::raw('"" as TaxAdjustment'),
			DB::raw('creditnote.amount as Amount'),
			DB::raw('"" as KnockOffDocType'),
			DB::raw('creditnote.cn_no as KnockOffDocNo'),
			DB::raw('creditnoteitem.knockoff as KnockOffAmount')
        )
      ->whereRaw($cond)
      ->get();

      if(!$summary)
      {
      		$summary = DB::table('creditnote')
      		->select(
        	DB::raw('"" as DocNo'),
			DB::raw('"" as DocDate'),
			DB::raw('"" as DebtorCode'),
			DB::raw('"" as JournalType'),
			DB::raw('"" as CNType'),
			DB::raw('"" as CurrencyCode'),
			DB::raw('"" as Description'),
			DB::raw('"" as Ref'),
			DB::raw('"" as RefNo2'),
			DB::raw('"" as Note'),
			DB::raw('"" as CurrencyRate'),
			DB::raw('"" as AccNo'),
			DB::raw('"" as ToAccountRate'),
			DB::raw('"" as DetailDescription'),
			DB::raw('"" as ProjNo'),
			DB::raw('"" as DeptNo'),
			DB::raw('"" as TaxType'),
			DB::raw('"" as TaxableAmt'),
			DB::raw('"" as TaxAdjustment'),
			DB::raw('"" as Amount'),
			DB::raw('"" as KnockOffDocType'),
			DB::raw('"" as KnockOffDocNo'),
			DB::raw('"" as KnockOffAmount')
        	)
        	->limit(1)
	       	->get();
      }

      $companies= DB::table('companies')
		->where('Subsidiary','=','Yes')
		->orderBy('Company_Name','asc')
		->groupBy('Company_Name')
		->get();

      return view('apcreditnote',['me'=>$me,'summary'=>$summary,'start'=>$start,'end'=>$end,'company'=>$company,'companies'=>$companies]);
    }

	public function mandayDetails($id){
		$me=(new CommonController)->get_current_user();
		$data=DB::select("
		SELECT
		t.Date,t.Name,t.Code
		FROM
			tracker
				inner JOIN
			(select users.Name,timesheets.Site_Name,radius.Code,timesheets.Date from `timesheets`
			inner join
				`radius` on `timesheets`.`Site_Name` = radius.Location_Name AND replace(timesheets.Code,' ','') like CONCAT('%', radius.Code ,'%')
			inner join
				users on users.Id = `timesheets`.UserId
			where  `timesheets`.`Site_Name` !='' and `timesheets`.`Time_In` !='' AND timesheets.Site_Name like '%(%)' and timesheets.Code!=''
				AND timesheets.Code not like '%SPEEDFREAK%' AND timesheets.Code not like '%meeting%' and timesheets.Code not like '%store%'
				and timesheets.Code not like '%Fabyard%' and timesheets.Code not like '%Office%' and timesheets.Code not like '%OTW%'
				) t ON 
				tracker.Incentive_Code like CONCAT('%', t.Code, '%')
		Where tracker.Id=".$id);
		return view('mandaydetails',['me'=>$me,'data'=>$data]);
	}

	public function dashboard()
  {
		$me = (new CommonController)->get_current_user();
		$start=isset($_GET['start']) ? $_GET['start']:null;
		$end=isset($_GET['end']) ? $_GET['end']:null;

		$filter="1";

		$region=isset($_GET['region']) ? $_GET['region']:false;
		if(isset($_GET['end']) && isset($_GET['start'])){
			// $filter.=' AND (str_to_date(tracker.`1st Claim Invoice Date`,"%d-%M-%Y") BETWEEN "'.$start.'" AND "'.$end.'" OR
			// str_to_date(tracker.`2nd Claim Invoice Date`,"%d-%M-%Y") BETWEEN "'.$start.'" AND "'.$end.'" OR
			// str_to_date(tracker.`3rd Claim Invoice Date`,"%d-$M-%Y") BETWEEN "'.$start.'" AND "'.$end.'" OR
			// str_to_date(tracker.`Retention Invoice Date`,"%d-%M-%Y") BETWEEN "'.$start.'" AND "'.$end.'" OR
			// str_to_date(tracker.`Invoice Date`,"%d-%M-%Y") BETWEEN "'.$start.'" AND "'.$end.'" )
			// ';
			$filter.=' AND (str_to_date(tracker.`1st Claim Invoice Date`,"%d-%M-%Y") BETWEEN str_to_date("'.$start.'","%d-%M-%Y") AND str_to_date("'.$end.'","%d-%M-%Y") OR
			str_to_date(tracker.`2nd Claim Invoice Date`,"%d-%M-%Y") BETWEEN str_to_date("'.$start.'","%d-%M-%Y") AND str_to_date("'.$end.'","%d-%M-%Y") OR
			str_to_date(tracker.`3rd Claim Invoice Date`,"%d-%M-%Y") BETWEEN str_to_date("'.$start.'","%d-%M-%Y") AND str_to_date("'.$end.'","%d-%M-%Y") OR
			str_to_date(tracker.`Retention Invoice Date`,"%d-%M-%Y") BETWEEN str_to_date("'.$start.'","%d-%M-%Y") AND str_to_date("'.$end.'","%d-%M-%Y") OR
			str_to_date(tracker.`Invoice Date`,"%d-%M-%Y") BETWEEN str_to_date("'.$start.'","%d-%M-%Y") AND str_to_date("'.$end.'","%d-%M-%Y") )
			';
			$date=true;
		}
		if(!isset($_GET['end']) && !isset($_GET['end'])){
			$start=date('d-M-Y');
			$end=date('d-M-Y');
			$date=false;
		}
		if($region){
			$totalsites=DB::table('tracker')
			->select(DB::raw('COUNT(*) AS Total'),
			DB::raw('SUM(IF(`Site Status` = "Completed", 1, 0)) AS Completed'),
			DB::raw('SUM(IF(`Site Status` = "WIP", 1, 0)) AS WIP'),
			DB::raw('SUM(IF(`Site Status` = "Not Yet Start", 1, 0)) AS Not_Yet_Start'),
			DB::raw('SUM(IF(`Site Status` = "KIV", 1, 0)) AS KIV'),
			DB::raw('SUM(IF(`Site Status` = "Cancelled", 1, 0)) AS Cancelled'))
			->whereRaw($filter)
			->groupBy('tracker.Region')
			->get();
			$total=DB::table('tracker')
			->select(
			DB::raw('FORMAT(SUM(replace(`BOQ Submission Amount`,",","")),2) as Total_PO_Amount'),
			DB::raw('FORMAT(SUM(IF(`PO No` !="", replace(`PO Value (RM)`,",",""), 0)),2) AS PO_Received'),
			DB::raw('FORMAT(SUM(IF(`PO No` = "", replace(`BOQ Approved Amount`,",",""), 0)),2) AS Pending_PO'),
			DB::raw('SUM(`1st Claim Invoice Amount` + `2nd Claim Invoice Amount` + `3rd Claim Invoice Amount` + `Retention Invoice Amount` + `Invoice Amount`) as Total_Invoiced_Amount'),
			DB::raw('FORMAT(SUM(if(`Site Status`="Completed",`PO Value (RM)`-(`1st Claim Invoice Amount` + `2nd Claim Invoice Amount` + `3rd Claim Invoice Amount` + `Retention Invoice Amount` + `Invoice Amount`),0)),2) AS JDNI'),
			DB::raw('FORMAT(SUM(if(`Site Status`="WIP",`PO Value (RM)`-(`1st Claim Invoice Amount` + `2nd Claim Invoice Amount` + `3rd Claim Invoice Amount` + `Retention Invoice Amount` + `Invoice Amount`),0)),2) AS WIP'),
			DB::raw('FORMAT(SUM(if(`Site Status`="KIV",`PO Value (RM)`-(`1st Claim Invoice Amount` + `2nd Claim Invoice Amount` + `3rd Claim Invoice Amount` + `Retention Invoice Amount` + `Invoice Amount`),0)),2) AS KIV'),
			DB::raw('FORMAT(SUM(if(`Site Status`="Not Yet Start",`PO Value (RM)`,0)),2) AS Not_Yet_Start'),
			DB::raw('FORMAT(SUM(if(`Site Status`="Cancelled",`PO Value (RM)`,0)),2) AS Cancelled'),
			DB::raw('COUNT(*) AS Total'),
			DB::raw('SUM(item.total) as Total_PO'),
			// DB::raw('COUNT(*) AS Total'),
			'tracker.Region',
			DB::raw('SUM(IF(`Site Status` = "Completed", 1, 0)) AS Completed'),
			DB::raw('SUM(IF(`Site Status` = "WIP", 1, 0)) AS WIP1'),
			DB::raw('SUM(IF(`Site Status` = "Not Yet Start", 1, 0)) AS Not_Yet_Start1'),
			DB::raw('SUM(IF(`Site Status` = "KIV", 1, 0)) AS KIV1'),
			DB::raw('SUM(IF(`Site Status` = "Cancelled", 1, 0)) AS Cancelled1'),
			DB::raw('SUM(mand.total) as Total_Manday')
			)
			->leftjoin(DB::raw('(SELECT material.TrackerId,SUM(ROUND(Qty*Price,2)) as total from materialpoitem
			left join material on material.Id = materialpoitem.MaterialId group by TrackerId
			) as item'),'item.TrackerId','=','tracker.Id')
			->leftjoin(DB::raw('(Select trackerid,SUM(manday.Amount) as total from manday group by trackerid ) as mand'),'mand.trackerid','=','tracker.Id')
			->whereRaw($filter)
			->groupBy('tracker.Region')
			->get();

			$totalewallet = DB::select("SELECT tracker.Region,SUM(ewallet.Amount) as Total
			FROM ewallet left join tracker on ewallet.TrackerId=tracker.Id
			where ".$filter." group by tracker.Region");

			$totalincentive = DB::select('select tracker.Region,SUM(Case when (SELECT COUNT(distinct tt.Date) FROM timesheets tt WHERE tt.Code!="" and tt.Site_Name like "%(%" AND tt.Site_Name=t.Site_Name AND replace(tt.Code," ","")=replace(t.Code," ","") AND str_to_date(tt.Date,"%d-%M-%Y") Between str_to_date(radius.Start_Date,"%d-%M-%Y") and str_to_date(radius.Completion_Date,"%d-%M-%Y"))>=scopeofwork.KPI+4 then Incentive_5
			when (SELECT COUNT(distinct tt.Date) FROM timesheets tt WHERE tt.Code!="" AND tt.Site_Name like "%(%" AND tt.Site_Name=t.Site_Name AND replace(tt.Code," ","")=replace(t.Code," ","") AND str_to_date(tt.Date,"%d-%M-%Y") Between str_to_date(radius.Start_Date,"%d-%M-%Y") and str_to_date(radius.Completion_Date,"%d-%M-%Y"))>=scopeofwork.KPI+3 then Incentive_4
			when (SELECT COUNT(distinct tt.Date) FROM timesheets tt WHERE tt.Code!="" AND tt.Site_Name like "%(%" AND tt.Site_Name=t.Site_Name AND replace(tt.Code," ","")=replace(t.Code," ","") AND str_to_date(tt.Date,"%d-%M-%Y") Between str_to_date(radius.Start_Date,"%d-%M-%Y") and str_to_date(radius.Completion_Date,"%d-%M-%Y"))>=scopeofwork.KPI+2 then Incentive_3
			when (SELECT COUNT(distinct tt.Date) FROM timesheets tt WHERE tt.Code!="" AND tt.Site_Name like "%(%" AND tt.Site_Name=t.Site_Name AND replace(tt.Code," ","")=replace(t.Code," ","") AND str_to_date(tt.Date,"%d-%M-%Y") Between str_to_date(radius.Start_Date,"%d-%M-%Y") and str_to_date(radius.Completion_Date,"%d-%M-%Y"))>=scopeofwork.KPI+1 then Incentive_2
			when (SELECT COUNT(distinct tt.Date) FROM timesheets tt WHERE tt.Code!="" AND tt.Site_Name like "%(%" AND tt.Site_Name=t.Site_Name AND replace(tt.Code," ","")=replace(t.Code," ","") AND str_to_date(tt.Date,"%d-%M-%Y") Between str_to_date(radius.Start_Date,"%d-%M-%Y") and str_to_date(radius.Completion_Date,"%d-%M-%Y"))=scopeofwork.KPI then Incentive_1
			else 0 END) as Total from tracker left join (select Site_Name,Code,count(distinct Date) as c from timesheets where timesheets.Code!="" and timesheets.Site_Name like "%(%" group by Site_Name,Code) t replace(t.Code," ","")=tracker.Incentive_Code left join `radius` on `t`.`Site_Name` = radius.Location_Name AND t.Code like CONCAT("%", radius.Code ,"%")
			left join `scopeofwork` on `radius`.`Code` = `scopeofwork`.`Code`
			where '.$filter." group by tracker.Region");

			$totalewallet=collect($totalewallet);

			$totalincentive=collect($totalincentive);

			$total=collect($total)->map(function($item,$key) use($totalewallet,$totalincentive){
				$totalew=$totalewallet->where('Region',$item->Region)->values();
				if(isset($totalew[0]))
					$item->Total_EWallet=$totalew[0]->Total;
				else $item->Total_EWallet=0;

				$totalincen=$totalincentive->where('Region',$item->Region)->values();
				if(isset($totalincen[0]))
					$item->Total_Incentive=$totalincen[0]->Total;
				else $item->Total_Incentive=0;
				return $item;
			});
			return view('dashboard1',['me'=>$me,'total'=>$total ,'date'=>$date,'start'=>$start,'end'=>$end]);
		}


		$total=DB::table('tracker')
		->select(DB::raw('FORMAT(SUM(replace(`BOQ Submission Amount`,",","")),2) as Total_PO_Amount'),
		DB::raw('FORMAT(SUM(IF(`PO No` !="", replace(`PO Value (RM)`,",",""), 0)),2) AS PO_Received'),
		DB::raw('FORMAT(SUM(IF(`PO No` = "", replace(`BOQ Approved Amount`,",",""), 0)),2) AS Pending_PO'))
		->whereRaw($filter)
		->first();

		$totalinvoiced=DB::table('tracker')
		->select(
			DB::raw('SUM(`1st Claim Invoice Amount` + `2nd Claim Invoice Amount` + `3rd Claim Invoice Amount` + `Retention Invoice Amount` + `Invoice Amount`) as Total_Invoiced_Amount'),
			DB::raw('FORMAT(SUM(if(`Site Status`="Completed",`PO Value (RM)`-(`1st Claim Invoice Amount` + `2nd Claim Invoice Amount` + `3rd Claim Invoice Amount` + `Retention Invoice Amount` + `Invoice Amount`),0)),2) AS JDNI'),
			DB::raw('FORMAT(SUM(if(`Site Status`="WIP",`PO Value (RM)`-(`1st Claim Invoice Amount` + `2nd Claim Invoice Amount` + `3rd Claim Invoice Amount` + `Retention Invoice Amount` + `Invoice Amount`),0)),2) AS WIP'),
			DB::raw('FORMAT(SUM(if(`Site Status`="KIV",`PO Value (RM)`-(`1st Claim Invoice Amount` + `2nd Claim Invoice Amount` + `3rd Claim Invoice Amount` + `Retention Invoice Amount` + `Invoice Amount`),0)),2) AS KIV'),
			DB::raw('FORMAT(SUM(if(`Site Status`="Not Yet Start",`PO Value (RM)`,0)),2) AS Not_Yet_Start'),
			DB::raw('FORMAT(SUM(if(`Site Status`="Cancelled",`PO Value (RM)`,0)),2) AS Cancelled')
		)
		->whereRaw($filter)
		->first();

		$totalcost=DB::table('materialpo')
		->select(DB::raw('SUM(item.Total) as Total_Cost'))
	    ->leftJoin('material','material.Id','=','materialpo.MaterialId')
	    ->leftJoin('tracker','tracker.Id','=','material.TrackerId')
	    ->leftJoin(DB::raw('(SELECT Type,PoId,SUM(Round(Qty*Price,2)) as total from materialpoitem group by PoId) as item'),'item.PoId','=','materialpo.Id')
		->whereRaw($filter)
		->first();


		$totalsites=DB::table('tracker')
		->select(DB::raw('COUNT(*) AS Total'),
		DB::raw('SUM(IF(`Site Status` = "Completed", 1, 0)) AS Completed'),
		DB::raw('SUM(IF(`Site Status` = "WIP", 1, 0)) AS WIP'),
		DB::raw('SUM(IF(`Site Status` = "Not Yet Start", 1, 0)) AS Not_Yet_Start'),
		DB::raw('SUM(IF(`Site Status` = "KIV", 1, 0)) AS KIV'),
		DB::raw('SUM(IF(`Site Status` = "Cancelled", 1, 0)) AS Cancelled'))
		->whereRaw($filter)
		->first();

		$totalmanday = DB::select("select
					SUM(manday.Amount) as Total
					from manday
					Left Join tracker on manday.trackerid=tracker.Id
					where ".$filter);
		$totalincentive = DB::select('select SUM(Case when (SELECT COUNT(distinct tt.Date) FROM timesheets tt WHERE tt.Code!="" and tt.Site_Name like "%(%" AND tt.Site_Name=t.Site_Name AND replace(tt.Code," ","")=replace(t.Code," ","") AND str_to_date(tt.Date,"%d-%M-%Y") Between str_to_date(radius.Start_Date,"%d-%M-%Y") and str_to_date(radius.Completion_Date,"%d-%M-%Y"))>=scopeofwork.KPI+4 then Incentive_5
		when (SELECT COUNT(distinct tt.Date) FROM timesheets tt WHERE tt.Code!="" AND tt.Site_Name like "%(%" AND tt.Site_Name=t.Site_Name AND replace(tt.Code," ","")=replace(t.Code," ","") AND str_to_date(tt.Date,"%d-%M-%Y") Between str_to_date(radius.Start_Date,"%d-%M-%Y") and str_to_date(radius.Completion_Date,"%d-%M-%Y"))>=scopeofwork.KPI+3 then Incentive_4
		when (SELECT COUNT(distinct tt.Date) FROM timesheets tt WHERE tt.Code!="" AND tt.Site_Name like "%(%" AND tt.Site_Name=t.Site_Name AND replace(tt.Code," ","")=replace(t.Code," ","") AND str_to_date(tt.Date,"%d-%M-%Y") Between str_to_date(radius.Start_Date,"%d-%M-%Y") and str_to_date(radius.Completion_Date,"%d-%M-%Y"))>=scopeofwork.KPI+2 then Incentive_3
		when (SELECT COUNT(distinct tt.Date) FROM timesheets tt WHERE tt.Code!="" AND tt.Site_Name like "%(%" AND tt.Site_Name=t.Site_Name AND replace(tt.Code," ","")=replace(t.Code," ","") AND str_to_date(tt.Date,"%d-%M-%Y") Between str_to_date(radius.Start_Date,"%d-%M-%Y") and str_to_date(radius.Completion_Date,"%d-%M-%Y"))>=scopeofwork.KPI+1 then Incentive_2
		when (SELECT COUNT(distinct tt.Date) FROM timesheets tt WHERE tt.Code!="" AND tt.Site_Name like "%(%" AND tt.Site_Name=t.Site_Name AND replace(tt.Code," ","")=replace(t.Code," ","") AND str_to_date(tt.Date,"%d-%M-%Y") Between str_to_date(radius.Start_Date,"%d-%M-%Y") and str_to_date(radius.Completion_Date,"%d-%M-%Y"))=scopeofwork.KPI then Incentive_1
		else 0 END) as Total from tracker left join (select Site_Name,Code,count(distinct Date) as c from timesheets where timesheets.Code!="" and timesheets.Site_Name like "%(%" group by Site_Name,Code) t replace(t.Code," ","")=tracker.Incentive_Code left join `radius` on `t`.`Site_Name` = radius.Location_Name AND t.Code like CONCAT("%", radius.Code ,"%")
		left join `scopeofwork` on `radius`.`Code` = `scopeofwork`.`Code`
		where '.$filter);
		$totalewallet = DB::select("SELECT SUM(ewallet.Amount) as Total
		FROM ewallet left join tracker on ewallet.TrackerId=tracker.Id
		where ".$filter);

		return view('dashboard',['me'=>$me,'total'=>$total,'totalinvoiced'=>$totalinvoiced,'totalcost'=>$totalcost,'totalsites'=>$totalsites,'totalmanday'=>$totalmanday,'totalincentive'=>$totalincentive,'totalewallet'=>$totalewallet,
		'start'=>$start,'end'=>$end,'date'=>$date]);
	}

	public function dashboard2()
  {
		$me = (new CommonController)->get_current_user();

		$start=isset($_GET['start']) ? $_GET['start']:"";
		$end=isset($_GET['end']) ? $_GET['end']:"";
		$date=isset($_GET['check']) ? json_decode($_GET['check']):false;
		$filter="1 ";
		if($start != ""){
			$filter.=' AND date_format(str_to_Date(CONCAT("01-",`Closed Date`),"%d-%M-%Y"),"%d-%M-%Y")  BETWEEN str_to_date("'.$start.'","%d-%M-%Y") AND str_to_date("'.$end.'","%d-%M-%Y")';
		}else{
			$start=date('d-M-Y');
			$end=date('d-M-Y');
		}

		$surveytotal=DB::table('tracker')
		->select(
			DB::raw('SUM(IF(`Survey (Y/N)` = "Yes", 1, 0)) AS Survey'),

			DB::raw('SUM(IF(`Survey (Y/N)` = "Yes" and `Subcon (Y/N)`="No", 1, 0)) AS Survey_Internal'),
			DB::raw('SUM(IF(`Survey (Y/N)` = "Yes" and `Subcon (Y/N)`="No" and (`Site Status` like "%Done%" or `Site Status` like "%Completed%"), 1, 0)) AS Survey_Done'),
			DB::raw('SUM(IF(`Survey (Y/N)` = "Yes" and `Subcon (Y/N)`="No" and `Survey NTP Date`="", 1, 0)) AS Survey_Pending_NTP'),
			DB::raw('SUM(IF(`Survey (Y/N)` = "Yes" and `Subcon (Y/N)`="No" and `WP (Access)`="", 1, 0)) AS Survey_Pending_WP'),
			DB::raw('SUM(IF(`Survey (Y/N)` = "Yes" and `Subcon (Y/N)`="No" and `Survey Date`="", 1, 0)) AS Survey_Pending_Survey'),

			DB::raw('SUM(IF(`Survey (Y/N)` = "Yes" and `Subcon (Y/N)`="Yes", 1, 0)) AS Survey_Subcon'),
			DB::raw('SUM(IF(`Survey (Y/N)` = "Yes" and `Subcon (Y/N)`="Yes" and (`Site Status` like "%Done%" or `Site Status` like "%Completed%"), 1, 0)) AS Survey_Subcon_Done'),
			DB::raw('SUM(IF(`Survey (Y/N)` = "Yes" and `Subcon (Y/N)`="Yes" and `Survey NTP Date`="", 1, 0)) AS Survey_Subcon_Pending_NTP'),
			DB::raw('SUM(IF(`Survey (Y/N)` = "Yes" and `Subcon (Y/N)`="Yes" and `WP (Access)`="", 1, 0)) AS Survey_Subcon_Pending_WP'),
			DB::raw('SUM(IF(`Survey (Y/N)` = "Yes" and `Subcon (Y/N)`="Yes" and `Survey Date`="", 1, 0)) AS Survey_Subcon_Pending_Survey')
		)
		->whereRaw($filter)
		->get();
		if(!$surveytotal[0]->Survey){
			$surveytotal=collect([
				(object)['Survey'=>0,
				'Survey_Internal'=>0,
				'Survey_Done'=>0,
				'Survey_Pending_NTP'=>0,
				'Survey_Pending_WP'=>0,
				'Survey_Pending_Survey'=>0,
				'Survey_Subcon'=>0,
				'Survey_Subcon_Done'=>0,
				'Survey_Subcon_Pending_WP'=>0,
				'Survey_Subcon_Pending_Survey'=>0,
				'Survey_Subcon_Pending_NTP'=>0
				]

			]);
		}
		$draftertotal=DB::table('tracker')
		->select(
			DB::raw('SUM(IF(`Survey (Y/N)` = "Yes", 1, 0)) AS Drafter'),
			DB::raw('SUM(IF(`Survey (Y/N)` = "Yes" and `Subcon (Y/N)`="No", 1, 0)) AS Drafter_Internal'),
			DB::raw('SUM(IF(`Survey (Y/N)` = "Yes" and `Subcon (Y/N)`="No" and Status ="FULLY APPROVAL", 1, 0)) AS Drafter_Approved'),
			DB::raw('SUM(IF(`Survey (Y/N)` = "Yes" and `Subcon (Y/N)`="No" and Status like "%FULLY APPROVAL/%", 1, 0)) AS Drafter_Approved_Comment'),
			DB::raw('SUM(IF(`Survey (Y/N)` = "Yes" and `Subcon (Y/N)`="No" and Status ="Pending Approval", 1, 0)) AS Drafter_Pending_Approval'),
			DB::raw('SUM(IF(`Survey (Y/N)` = "Yes" and `Subcon (Y/N)`="No" and Status ="Pending Revise", 1, 0)) AS Drafter_Pending_Revised'),
			DB::raw('SUM(IF(`Survey (Y/N)` = "Yes" and `Subcon (Y/N)`="No" and Status like "%Pending TSS%", 1, 0)) AS Drafter_Pending_Report'),

			DB::raw('SUM(IF(`Survey (Y/N)` = "Yes" and `Subcon (Y/N)`="Yes", 1, 0)) AS Drafter_Subcon'),
			DB::raw('SUM(IF(`Survey (Y/N)` = "Yes" and `Subcon (Y/N)`="Yes" and Status ="FULLY APPROVAL", 1, 0)) AS Drafter_Subcon_Approved'),
			DB::raw('SUM(IF(`Survey (Y/N)` = "Yes" and `Subcon (Y/N)`="Yes" and Status like "%FULLY APPROVAL/%", 1, 0)) AS Drafter_Subcon_Approved_Comment'),
			DB::raw('SUM(IF(`Survey (Y/N)` = "Yes" and `Subcon (Y/N)`="Yes" and Status ="Pending Approval", 1, 0)) AS Drafter_Subcon_Pending_Approval'),
			DB::raw('SUM(IF(`Survey (Y/N)` = "Yes" and `Subcon (Y/N)`="Yes" and Status ="Pending Revise", 1, 0)) AS Drafter_Subcon_Pending_Revised'),
			DB::raw('SUM(IF(`Survey (Y/N)` = "Yes" and `Subcon (Y/N)`="Yes" and Status like "%Pending TSS%", 1, 0)) AS Drafter_Subcon_Pending_Report')

		)
		->whereRaw($filter)
		->get();
		if(!$draftertotal[0]->Drafter){
			$draftertotal=collect([
				(object)['Drafter'=>0,
				'Drafter_Internal'=>0,
				'Drafter_Approved'=>0,
				'Drafter_Approved_Comment'=>0,
				'Drafter_Pending_Approval'=>0,
				'Drafter_Pending_Revised'=>0,
				'Drafter_Pending_Report'=>0,
				'Drafter_Subcon'=>0,
				'Drafter_Subcon_Approved'=>0,
				'Drafter_Subcon_Approved_Comment'=>0,
				'Drafter_Subcon_Pending_Approval'=>0,
				'Drafter_Subcon_Pending_Revised'=>0,
				'Drafter_Subcon_Pending_Report'=>0
				]

			]);
		}
		$drafterpo2=DB::table('tracker')
		->select(
			DB::raw('SUM(If(`Precon (Y/N)` ="Yes" OR `CME (Y/N)` ="Yes",1,0)) As Total'),
			DB::raw('SUM(If((`Precon (Y/N)` ="Yes" OR `CME (Y/N)` ="Yes") AND `Subcon (Y/N)`="No",1,0)) As Drafter_Internal'),
			DB::raw('SUM(If((`Precon (Y/N)` ="Yes" OR `CME (Y/N)` ="Yes") AND `Subcon (Y/N)`="No" AND `CONS DRAWING PO2 SUBMISSION DATE`<> "" ,1,0)) As Drafter_Drawing_Internal'),
			DB::raw('SUM(If((`Precon (Y/N)` ="Yes" OR `CME (Y/N)` ="Yes") AND `Subcon (Y/N)`="No" AND `Cons Drawing  Approved Date`<> "" ,1,0)) As Drafter_Drawing_Approved_Internal'),
			DB::raw('SUM(If((`Precon (Y/N)` ="Yes" OR `CME (Y/N)` ="Yes") AND `Subcon (Y/N)`="Yes" AND `Site Award Date`<> "" ,1,0)) As Drafter_Drawing_Subcon'),
			DB::raw('SUM(If((`Precon (Y/N)` ="Yes" OR `CME (Y/N)` ="Yes") AND `Subcon (Y/N)`="Yes" AND `CONS DRAWING PO2 SUBMISSION DATE`<> "" ,1,0)) As Drafter_Drawing_Submission_Subcon'),
			DB::raw('SUM(If((`Precon (Y/N)` ="Yes" OR `CME (Y/N)` ="Yes") AND `Subcon (Y/N)`="Yes" AND `Cons Drawing  Approved Date`<> "" ,1,0)) As Drafter_Drawing_Approved_Subcon')
			//TOTAL AS BUILT DRAWING (INTERNAL)
			// TOTAL AS BUILT DRAWING DONE (INTERNAL)
			// TOTAL AS BUILT DRAWING  (SUBCON)
			// TOTAL AS BUILT DRAWING DONE  (SUBCON)
			// DB::raw('SUM(If((`Precon (Y/N)` ="Yes" OR `CME (Y/N)` ="Yes") AND `Subcon (Y/N)`="Yes" AND `Cons Drawing  Approved Date`<> "" ,1,0)) As Drafter_Drawing_Approved_Subcon')
		)
		->whereRaw($filter)
		->get();
		if(!$drafterpo2[0]->Total){
			$drafterpo2=collect([
				(object)['Total'=>0,
				'Drafter_Internal'=>0,
				'Drafter_Drawing_Internal'=>0,
				'Drafter_Drawing_Approved_Internal'=>0,
				'Drafter_Drawing_Subcon'=>0,
				'Drafter_Drawing_Submission_Subcon'=>0,
				'Drafter_Drawing_Approved_Subcon'=>0,

				]

			]);
		}
		$qs=DB::table('tracker')
		->select(
			DB::raw('count(*) as total_site'),
			DB::raw('round((SELECT SUM(Total) FROM material
			inner join (select Max(Id) as maxmrid from material group by TrackerId) as maxmr on maxmr.maxmrid=material.Id
			left join (select Max(Id) as maxid,MaterialId from materialstatus group by MaterialId) as max on max.MaterialId=maxmr.maxmrid
			left join materialstatus on materialstatus.Id=max.maxid
			WHERE  materialstatus.Status!="Recalled"),2) as total_budget'),
			DB::raw('round(SUM(`BOQ Submission Amount`)+SUM(`BOQ Amount`)+SUM(`BOQ Submit Amount`),2) as total_submit'),
			DB::raw('round(SUM(`BOQ Approved Amount`),2) as total_approved'),
			DB::raw('round(`PO Value (RM)`,2) as total_po')

		)
		->whereRaw($filter)
		->get();
		if(!$qs[0]->total_site){
			$qs=collect([
				(object)['total_site'=>0,
				'total_budget'=>0,
				'total_submit'=>0,
				'total_approved'=>0,
				'total_po'=>0,
				]

			]);
		}
		$rollout=DB::table('tracker')
		->select(
			DB::Raw('count(*) as total_site'),
			DB::raw('SUM(IF((tracker.`BOQ Approved Amount` <> "N/A" OR tracker.`BOQ Approved Amount` <> "") AND `Site Status`="Pending NTP",1,0)) as pending_ntp'),
			DB::raw('SUM(IF((tracker.`BOQ Approved Amount` <> "N/A" OR tracker.`BOQ Approved Amount` <> "") AND `Site Status`="NOT YET START",1,0)) as not_yet_start'),
			DB::raw('SUM(IF((tracker.`BOQ Approved Amount` <> "N/A" OR tracker.`BOQ Approved Amount` <> "") AND `Site Status`="WIP",1,0)) as wip'),
			DB::raw('SUM(IF((tracker.`BOQ Approved Amount` <> "N/A" OR tracker.`BOQ Approved Amount` <> "") AND `Site Status`="COMPLETED PENDING ATP",1,0)) as pending_atp'),
			DB::raw('SUM(IF((tracker.`BOQ Approved Amount` <> "N/A" OR tracker.`BOQ Approved Amount` <> "") AND `Site Status`="PENDING PLC",1,0)) as pending_plc'),
			DB::raw('SUM(IF((tracker.`BOQ Approved Amount` <> "N/A" OR tracker.`BOQ Approved Amount` <> "") AND `Site Status`="SITE DONE",1,0)) as site_done'),
			DB::raw('SUM(IF((tracker.`BOQ Approved Amount` <> "N/A" OR tracker.`BOQ Approved Amount` <> "") AND `Site Status`="KIV",1,0)) as kiv'),
			DB::raw('SUM(IF((tracker.`BOQ Approved Amount` <> "N/A" OR tracker.`BOQ Approved Amount` <> "") AND `Site Status`="CANCELLED/DROP",1,0)) as cancelled')
		)
		->whereRaw($filter)
		->get();
		if(!$rollout[0]->total_site){
			$rollout=collect([
				(object)['total_site'=>0,
				'pending_ntp'=>0,
				'not_yet_start'=>0,
				'wip'=>0,
				'pending_atp'=>0,
				'pending_plc'=>0,
				'site_done'=>0,
				'kiv'=>0,
				'cancelled'=>0,
				]

			]);
		}
		$tnb=DB::table('tracker')
		->select(
			DB::raw('SUM(IF(`TNB` NOT IN ("No") OR `TNB (Y/N)` = "Yes",1,0)) as total_site'),
			DB::raw('SUM(IF((`TNB` NOT IN ("No") OR `TNB (Y/N)` = "Yes") AND (`Date of Application` <> "" OR `TNB/SESB Date of Application` <> "") ,1,0)) as total_application'),
			DB::raw('SUM(IF((`TNB` NOT IN ("No") OR `TNB (Y/N)` = "Yes") AND `Date of Approval (Move In Approval)` <> "" ,1,0)) as total_approval'),
			DB::raw('SUM(IF((`TNB` NOT IN ("No") OR `TNB (Y/N)` = "Yes") AND (`CSP Letter` <> "" OR `CSP/CB Attachment` <> "") ,1,0)) as total_csp'),
			DB::raw('SUM(IF((`TNB` NOT IN ("No") OR `TNB (Y/N)` = "Yes") AND (`Demand Letter` <> "" OR `Deposit Letter Attachment` <> "") ,1,0)) as total_demand'),
			DB::raw('SUM(IF((`TNB` NOT IN ("No") OR `TNB (Y/N)` = "Yes") AND (`Date Of Contribution Payment` <> "" OR `Date of CSP/CB Payment` <> "") ,1,0)) as total_csp_payment'),
			DB::raw('SUM(IF((`TNB` NOT IN ("No") OR `TNB (Y/N)` = "Yes") AND (`Date Of Deposit Payment` <> "" OR `Deposit Payment Date` <> "") ,1,0)) as total_deposit_payment'),
			DB::raw('SUM(IF((`TNB` NOT IN ("No") OR `TNB (Y/N)` = "Yes") AND (`TNB Installation Date` <> "" OR `TNB/SESB Installation Date` <> "" OR `Installation TNB Date` <> "") ,1,0)) as total_tnb_installation'),
			DB::raw('SUM(IF((`TNB` NOT IN ("No") OR `TNB (Y/N)` = "Yes") AND (`TNB Invoice Date` <> "" OR `TNB/SESB Invoice Date` <> "") ,1,0)) as total_tnb_invoice')
		)
		->whereRaw($filter)
		->get();
		if(!$tnb[0]->total_site){
			$tnb=collect([
				(object)['total_site'=>0,
				'total_application'=>0,
				'total_approval'=>0,
				'total_csp'=>0,
				'total_demand'=>0,
				'total_csp_payment'=>0,
				'total_deposit_payment'=>0,
				'total_tnb_installation'=>0,
				'total_tnb_invoice'=>0
				]

			]);
		}
		$assr=DB::Table('tracker')
		->select(
			DB::Raw('SUM(IF(`ASSR / GE Submit Date` <> "",1,0)) as total_submit'),
			DB::Raw('SUM(IF(`ASSR / GE Approval Date` <> "",1,0)) as total_approval'),
			DB::Raw('SUM(IF(`LOI Signed Date` <> "",1,0)) as total_loi'),
			DB::Raw('SUM(IF(`TA SIGNED DATE` <> "",1,0)) as total_ta'),
			DB::Raw('SUM(IF(`TA Stamp Date` <> "",1,0)) as total_stamp'),
			DB::Raw('SUM(IF(`Actual SA ATP` <> "",1,0)) as total_actual'),
			DB::Raw('SUM(IF(`Actual CSI` <> "",1,0)) as total_csi')

			)
		->whereRaw($filter)
		->get();
		if(!$assr[0]->total_site){
			$assr=collect([
				(object)['total_site'=>0,
				'total_submit'=>0,
				'total_approval'=>0,
				'total_loi'=>0,
				'total_loi'=>0,
				'total_ta'=>0,
				'total_stamp'=>0,
				'total_actual'=>0,
				'total_csi'=>0,
				]

			]);
		}
		$lc=DB::table('tracker')
		->select(
			DB::Raw('SUM(IF(`LOI Signed Date` <> "",1,0)) as total_loi'),
			DB::Raw('SUM(IF(`TA SIGNED DATE` <> "",1,0)) as total_ta'),
			DB::Raw('SUM(IF(`TA Stamp Date` <> "",1,0)) as total_stamp'),
			DB::Raw('SUM(IF(`PE Drawings Received` <> "",1,0)) as total_pe'),
			DB::Raw('SUM(IF(`Quit Rent` <> "",1,0)) as total_rent'),
			DB::Raw('SUM(IF(`Fee Permit` <> "",1,0)) as total_fee'),
			DB::Raw('SUM(IF(`Fee Process` <> "",1,0)) as total_fee_process'),
			DB::Raw('SUM(IF(`Online Submission Date` <> "",1,0)) as total_online_submission'),
			DB::Raw('SUM(IF(`Hardcopy Submission Date` <> "",1,0)) as total_hardcopy'),
			DB::Raw('SUM(IF(`MCMC APPROVAL DATE` <> "",1,0)) as total_mc'),
			DB::Raw('SUM(IF(`DCA` <> "",1,0)) as total_dca'),
			DB::Raw('SUM(IF(`OSHA Status` <> "",1,0)) as total_osha'),
			DB::Raw('SUM(IF(`Bomba Submission` <> "",1,0)) as total_bomba'),
			DB::Raw('SUM(IF(`LC/LA Approval Date` <> "",1,0)) as total_lc')
		)
		->whereRaw($filter)
		->get();
		if(!$lc[0]->total_site){
			$lc=collect([
				(object)[
					'total_site'=>0,
					'total_loi'=>0,
					'total_ta'=>0,
					'total_stamp'=>0,
					'total_pe'=>0,
					'total_rent'=>0,
					'total_fee'=>0,
					'total_fee_process'=>0,
					'total_online_submission'=>0,
					'total_hardcopy'=>0,
					'total_nc'=>0,
					'total_dca'=>0,
					'total_osha'=>0,
					'total_bomba'=>0,
					'total_lc'=>0,
					'total_mc'=>0,

				]

			]);
		}
		$renewal=DB::Table('tracker')
		->select(
			DB::raw('count(*) as total_site'),
			DB::Raw('SUM(IF(`Doc Attachment` <> "" ,1,0)) as total_doc')
		)
		->whereRaw($filter)
		->get();
		if(!$renewal[0]->total_site){
			$renewal=collect([
				(object)['total_site'=>0,
				'total_doc'=>0,
				]

			]);
		}
		$legalization=DB::Table('tracker')
		->select(
			DB::Raw('count(*) as total_site'),
			DB::raw('SUM(IF(`TNB` = "Yes",1,0)) as total_tnb'),
			DB::raw('SUM(IF(`MCMC` <> "",1,0)) as total_mcmc'),
			DB::raw('SUM(IF(`DCA` <> "",1,0)) as total_dca'),
			DB::raw('SUM(IF(`OSHA` <> "",1,0)) as total_osha'),
			DB::raw('SUM(IF(`Bomba` <> "",1,0)) as total_bomba'),
			DB::raw('SUM(IF(`LC/LA SUBMISSION` <> "",1,0)) as total_lc'),
			DB::raw('SUM(IF(`LC/LA Approval Date` <> "",1,0)) as total_approval')

		)
		->whereRaw($filter)
		->get();
		if(!$legalization[0]->total_site){
			$legalization=collect([
				(object)['total_site'=>0,
				'total_tnb'=>0,
				'total_mcmc'=>0,
				'total_dca'=>0,
				'total_osha'=>0,
				'total_bomba'=>0,
				'total_lc'=>0,
				'total_approval'=>0,
				]
			]);
		}
		$osu=DB::Table('tracker')
		->select(
			DB::raw('count(*) as total_site'),
			DB::raw('SUM(IF((`TSSR and TP Approval Date` <> "" OR `TP Approval Date RRP` <> "" OR `Doc Attachment` <> "" OR `Actual CSI` <> "" OR
			`LC/LA Approval Date` <> ""
			),1,0)) as site_done'),
			DB::Raw('SUM(IF(`SP Readiness` <> "" AND `SP Readiness` <> "N/A",1,0)) as total_site_pack'),
			DB::Raw('SUM(IF(`SP Approved` <> "" OR (`ROR Approved By Digi Admin` <> "" AND `ROR Approved By Digi Admin` <> "N/A"),1,0)) as total_to_invoice'),
			DB::Raw('SUM(IF(`SO` <> "" OR `SO Readines` <> "" ,1,0)) as total_so')
		)
		->whereRaw($filter)
		->get();
		if(!$osu[0]->total_site){
			$osu=collect([
				(object)['total_site'=>0,
				'site_done'=>0,
				'total_site_pack'=>0,
				'total_to_invoice'=>0,
				'total_so'=>0,

				]

			]);
		}
		$osu2=DB::Table('tracker')
		->select(
			DB::raw('"?" as site_done'),
			DB::Raw('SUM(IF(`SP Readiness` <> "",1,0)) as total_site_pack'),
			DB::Raw('SUM(IF((`SP Approved` <> "" OR `ROR Approved by Digi Admin` <> ""),1,0)) as total_to_invoice'),
			DB::Raw('SUM(IF( (`SO` <> "" OR `SO Readines` <> ""),1,0)) as total_so')

		)
		->whereRaw($filter)
		->get();
		if(!$osu2[0]->total_site){
			$osu2=collect([
				(object)['total_site'=>0,
				'site_done'=>'?',
				'total_site_pack'=>0,
				'total_to_invoice'=>0,
				'total_so'=>0,
				]

			]);
		}

			// dd($assr);
		return view('dashboard2',['me'=>$me,'surveytotal'=>$surveytotal,'surveytotal'=>$surveytotal,'draftertotal'=>$draftertotal,'drafterpo2'=>$drafterpo2,'qs'=>$qs,'rollout'=>$rollout,'tnb'=>$tnb,'assr'=>$assr,'renewal'=>$renewal,'lc'=>$lc,'legalization'=>$legalization,'osu'=>$osu,'osu2'=>$osu2,'start'=>$start,'end'=>$end,'date'=>$date]);
	}

	public function dashboard3()
  {
		$me = (new CommonController)->get_current_user();
		$start=isset($_GET['start']) ? $_GET['start']:"";
		$end=isset($_GET['end']) ? $_GET['end']:"";
		$date=isset($_GET['check']) ? json_decode($_GET['check']):false;
		$filter="1 ";
		$template=DB::table('trackertemplate')
		->get();
		$template=collect($template);
		if($start != ""){
			$filter.=' AND str_to_Date(CONCAT("01-",`Closed Date`),"%d-%M-%Y")  BETWEEN str_to_date("'.$start.'","%d-%M-%Y") AND str_to_date("'.$end.'","%d-%M-%Y")';
		}else{
			$start=date('d-M-Y');
			$end=date('d-M-Y');
		}

		$totalsites=DB::table('tracker')
		->select(DB::raw('COUNT(*) AS Total'),
			DB::raw('SUM(IF(`Site Status` = "Completed", 1, 0)) AS Completed'),
			DB::raw('SUM(replace(`BOQ Approved Amount`,",","")) as boq'),
			DB::raw('SUM(`Invoice Amount`)+SUM(`1st Claim Invoice Amount`)+SUM(`2nd Claim Invoice Amount`)+SUM(`3rd Claim Invoice Amount`)+SUM(`Retention Invoice Amount`) as invoice')
		)
		->whereRaw($filter)
		->get();
		if(!$totalsites[0]->Total){
			$totalsites=collect([
				(object)[
					'Total'=>0,
					'Completed'=>0,
					'boq'=>0,
					'invoice'=>0,
				]
			]);
		}
		$projected=DB::Table('tracker')
		->select(
			DB::Raw('round(SUM(replace(`BOQ Submit Amount`,",",""))+SUM(replace(`BOQ Amount`,",",""))+SUM(replace(`BOQ Submission Amount`,",","")),2) as total_boq'),
			DB::raw('SUM(replace(`BOQ Approved Amount`,",","")) as total_approved'),
			DB::raw('SUM(replace(`PO Value (RM)`,",","")) as total_po'),
			DB::Raw('SUM(IF(`BOQ Submit Amount` <> "" OR `BOQ Amount` <> "" OR `BOQ Submission Amount` <> "" ,1,0)) as boq'),
			DB::raw('SUM(IF(`BOQ Approved Amount` <> "" ,1,0)) as approved'),
			DB::raw('SUM(IF(`PO Value (RM)` <> "",1,0)) as po')
		)
		->whereRaw($filter)
		->get();
		if(!$projected[0]->total_boq){
			$projected=collect([
				(object)[
					'total_boq'=>0,
					'total_approved'=>0,
					'total_po'=>0,
					'boq'=>0,
					'approved'=>0,
					'po'=>0
				]
			]);
		}
		$renewal=DB::Table('tracker')
		->select(
			DB::raw('SUM(`BOQ Amount`) as total_boq'),
			DB::Raw('SUM(IF(`BOQ Amount` <> "",1,0)) as boq'),

			DB::raw('SUM(`BOQ Approved Amount`) as total_approved'),
			DB::Raw('SUM(IF(`BOQ Approved Amount` <> "",1,0)) as approved'),
			DB::Raw('SUM(`PO Value (RM)`) as total_po'),
			DB::Raw('SUM(IF(`PO Value (RM)`<>"",1,0)) as po')
		)
		->whereRaw($filter)
		->get();
		if(!$renewal[0]->total_boq){
			$renewal=collect([
				(object)[
					'total_boq'=>0,
					'total_approved'=>0,
					'total_po'=>0,
					'boq'=>0,
					'approved'=>0,
					'po'=>0
				]
			]);
		}
		$legalization=DB::Table('tracker')
		->select(
			DB::raw('SUM(replace(`BOQ Amount`,",","")) as total_boq'),
			DB::raw('SUM(IF(`BOQ Amount`<> "",1,0)) as boq'),
			DB::raw('SUM(replace(`BOQ Approved Amount`,",","")) as total_approved'),
			DB::Raw('SUM(replace(`PO Value (RM)`,",","")) as total_po'),
			DB::raw('SUM(IF(`BOQ Approved Amount`<>"",1,0)) as approved'),
			DB::raw('SUM(IF(`PO Value (RM)`<>"",1,0)) as po')

		)
		->whereRaw($filter)
		->get();
		if(!$legalization[0]->total_boq){
			$legalization=collect([
				(object)[
					'total_boq'=>0,
					'total_approved'=>0,
					'total_po'=>0,
					'boq'=>0,
					'approved'=>0,
					'po'=>0
				]
			]);
		}
		$project=DB::table('tracker')
		->select(
			DB::raw('SUM(If(`Site Status` = "Pending NTP",1,0)) as pending_ntp'),
			DB::raw('SUM(IF(`Site Status` = "Pending NTP",`BOQ Approved Amount`,0)) as ntp_boq'),

			DB::raw('SUM(If(`Site Status` = "Not yet start",1,0)) as not_yet'),
			DB::raw('SUM(IF(`Site Status` = "Not yet start",`BOQ Approved Amount`,0)) as not_boq'),

			DB::raw('SUM(If(`Site Status` = "WIP",1,0)) as wip'),
			DB::raw('SUM(IF(`Site Status` = "WIP",`BOQ Approved Amount`,0)) as boq_wip'),

			DB::raw('SUM(If(`Site Status` = "Completed Pending ATP",1,0)) as completed_pending_atp'),
			DB::raw('SUM(IF(`Site Status` = "Completed Pending ATP",`BOQ Approved Amount`,0)) as boq_atp'),

			DB::raw('SUM(If(`Site Status` = "Pending PLC",1,0)) as pending_plc'),
			DB::raw('SUM(IF(`Site Status` = "Pending PLC",`BOQ Approved Amount`,0)) as boq_plc'),

			DB::raw('SUM(If(`Site Status` = "Site done",1,0)) as site_done'),
			DB::raw('SUM(IF(`Site Status` = "Site done",`BOQ Approved Amount`,0)) as boq_site'),

			DB::raw('SUM(If(`Site Status` = "KIV",1,0)) as kiv'),
			DB::raw('SUM(IF(`Site Status` = "KIV",`BOQ Approved Amount`,0)) as boq_kiv'),

			DB::raw('SUM(If(`Site Status` = "Cancelled/Drop",1,0)) as cancelled'),
			DB::raw('SUM(IF(`Site Status` = "Cancelled/Drop",`BOQ Approved Amount`,0)) as boq_cancelled')
		)
		->whereRaw($filter)
		->get();
		if(!$project[0]->pending_ntp && $project[0]->pending_ntp != 0){
			$project=collect([
				(object)[
					'pending_ntp'=>0,
					'ntp_boq'=>0,
					'not_yet'=>0,
					'not_boq'=>0,
					'wip'=>0,
					'boq_wip'=>0,
					'completed_pending_atp'=>0,
					'boq_atp'=>0,
					'pending_plc'=>0,
					'boq_plc'=>0,
					'site_done'=>0,
					'boq_site'=>0,
					'kiv'=>0,
					'boq_kiv'=>0,
					'cancelled'=>0,
					'boq_cancelled'=>0
				]
			]);
		}
		$milestone=DB::Table('tracker')
		->select(
			DB::raw('SUM(IF(`OSU Milestone` = "PCC" OR `Milestone`="PCC",1,0)) as total_pcc'),
			DB::raw('SUM(IF(`OSU Milestone` = "PCC" OR `Milestone`="PCC",`BOQ Approved Amount`,0)) as pcc'),

			DB::raw('SUM(IF(`OSU Milestone` = "NIC" OR `Milestone`="NIC",1,0)) as total_nic'),
			DB::raw('SUM(IF(`OSU Milestone` = "NIC" OR `Milestone`="NIC",`BOQ Approved Amount`,0)) as nic'),

			DB::raw('SUM(IF(`OSU Milestone` = "PM" OR `Milestone`="PM",1,0)) as total_pm'),
			DB::raw('SUM(IF(`OSU Milestone` = "PM" OR `Milestone`="PM",`BOQ Approved Amount`,0)) as pm'),

			DB::raw('SUM(IF(`OSU Milestone` = "QS" OR `Milestone`="QS",1,0)) as total_qs'),
			DB::raw('SUM(IF(`OSU Milestone` = "QS" OR `Milestone`="QS",`BOQ Approved Amount`,0)) as qs'),

			DB::raw('SUM(IF(`OSU Milestone` = "TSS" OR `Milestone`="TSS",1,0)) as total_tss'),
			DB::raw('SUM(IF(`OSU Milestone` = "TSS" OR `Milestone`="TSS",`BOQ Approved Amount`,0)) as tss'),

			DB::raw('SUM(IF(`OSU Milestone` = "Rollout" OR `Milestone`="Rollout",1,0)) as total_rollout'),
			DB::raw('SUM(IF(`OSU Milestone` = "Rollout" OR `Milestone`="Rollout",`BOQ Approved Amount`,0)) as rollout'),

			DB::raw('SUM(IF(`OSU Milestone` = "OSU" OR `Milestone`="OSU",1,0)) as total_osu'),
			DB::raw('SUM(IF(`OSU Milestone` = "OSU" OR `Milestone`="OSU",`BOQ Approved Amount`,0)) as osu'),

			DB::raw('SUM(IF(`OSU Milestone` = "Closed" OR `Milestone`="Closed",1,0)) as total_closed'),
			DB::raw('SUM(IF(`OSU Milestone` = "Closed" OR `Milestone`="Closed",`BOQ Approved Amount`,0)) as closed'),

			DB::raw('SUM(IF(`OSU Milestone` = "Cancelled" OR `Milestone`="Cancelled",1,0)) as total_cancelled'),
			DB::raw('SUM(IF(`OSU Milestone` = "Cancelled" OR `Milestone`="Cancelled",`BOQ Approved Amount`,0)) as cancelled')

		)
		->whereRaw($filter)
		->get();
		if(!$milestone[0]->total_pcc){
			$milestone=collect([
				(object)[
					'total_pcc'=>0,
					'pcc'=>0,
					'total_nic'=>0,
					'nic'=>0,

					'total_pm'=>0,
					'pm'=>0,

					'total_qs'=>0,
					'qs'=>0,

					'total_tss'=>0,
					'tss'=>0,

					'total_rollout'=>0,
					'rollout'=>0,

					'total_osu'=>0,
					'osu'=>0,
					'total_closed'=>0,
					'closed'=>0,
					'total_cancelled'=>0,
					'cancelled'=>0
				]
			]);
		}
		$gross=DB::Table('tracker')
		->select(
			DB::raw('SUM(`1st Claim Invoice Amount` + `2nd Claim Invoice Amount` + `3rd Claim Invoice Amount` + `Retention Invoice Amount` + `Invoice Amount`) as Total_Invoiced_Amount'),
			DB::Raw('SUM(IF(`1st Claim Invoice Amount` <> "" OR `2nd Claim Invoice Amount` <> "" OR `3rd Claim Invoice Amount` <> "" OR `Retention Invoice Amount` <> "" OR `Invoice Amount`<>"",1,0)) as invoice_site'),
			DB::raw('round((SELECT SUM(material.Total) FROM material
			inner join (select Max(Id) as maxmrid from material group by TrackerId) as maxmr on maxmr.maxmrid=material.Id
			left join (select Max(Id) as maxid,MaterialId from materialstatus group by MaterialId) as max on max.MaterialId=maxmr.maxmrid
			left join materialstatus on materialstatus.Id=max.maxid
			left join tracker on tracker.Id = material.TrackerId
			WHERE  materialstatus.Status!="Recalled" AND '.$filter.'),2) as total_budget'),
			DB::raw('(SELECT COUNT(Distinct(TrackerId)) from material left join tracker on tracker.Id = material.TrackerId where '.$filter.') as total_tracker'),
			DB::raw('(SELECT SUM(replace(manday.`Amount`,",","")) from manday left join tracker on tracker.Id = manday.TrackerId where '.$filter.') as total_manday'),
			DB::raw('(SELECT COUNT(Distinct(TrackerId)) from manday left join tracker on tracker.Id = manday.TrackerId  where '.$filter.') as manday_site'),
			DB::raw('(select SUM(Case when (SELECT COUNT(distinct tt.Date) FROM timesheets tt WHERE tt.Code!="" and tt.Site_Name like "%(%" AND tt.Site_Name=t.Site_Name AND replace(tt.Code," ","")=replace(t.Code," ","") AND str_to_date(tt.Date,"%d-%M-%Y") Between str_to_date(radius.Start_Date,"%d-%M-%Y") and str_to_date(radius.Completion_Date,"%d-%M-%Y"))>=scopeofwork.KPI+4 then Incentive_5
			when (SELECT COUNT(distinct tt.Date) FROM timesheets tt WHERE tt.Code!="" AND tt.Site_Name like "%(%" AND tt.Site_Name=t.Site_Name AND replace(tt.Code," ","")=replace(t.Code," ","") AND str_to_date(tt.Date,"%d-%M-%Y") Between str_to_date(radius.Start_Date,"%d-%M-%Y") and str_to_date(radius.Completion_Date,"%d-%M-%Y"))>=scopeofwork.KPI+3 then Incentive_4
			when (SELECT COUNT(distinct tt.Date) FROM timesheets tt WHERE tt.Code!="" AND tt.Site_Name like "%(%" AND tt.Site_Name=t.Site_Name AND replace(tt.Code," ","")=replace(t.Code," ","") AND str_to_date(tt.Date,"%d-%M-%Y") Between str_to_date(radius.Start_Date,"%d-%M-%Y") and str_to_date(radius.Completion_Date,"%d-%M-%Y"))>=scopeofwork.KPI+2 then Incentive_3
			when (SELECT COUNT(distinct tt.Date) FROM timesheets tt WHERE tt.Code!="" AND tt.Site_Name like "%(%" AND tt.Site_Name=t.Site_Name AND replace(tt.Code," ","")=replace(t.Code," ","") AND str_to_date(tt.Date,"%d-%M-%Y") Between str_to_date(radius.Start_Date,"%d-%M-%Y") and str_to_date(radius.Completion_Date,"%d-%M-%Y"))>=scopeofwork.KPI+1 then Incentive_2
			when (SELECT COUNT(distinct tt.Date) FROM timesheets tt WHERE tt.Code!="" AND tt.Site_Name like "%(%" AND tt.Site_Name=t.Site_Name AND replace(tt.Code," ","")=replace(t.Code," ","") AND str_to_date(tt.Date,"%d-%M-%Y") Between str_to_date(radius.Start_Date,"%d-%M-%Y") and str_to_date(radius.Completion_Date,"%d-%M-%Y"))=scopeofwork.KPI then Incentive_1
			else 0 END) as Incentive from tracker a left join (select Site_Name,Code,count(distinct Date) as c from timesheets where timesheets.Code!="" and timesheets.Site_Name like "%(%" group by Site_Name,Code) t replace(t.Code," ","")=a.Incentive_Code left join `radius` on `t`.`Site_Name` = radius.Location_Name AND t.Code like CONCAT("%", radius.Code ,"%") left join `scopeofwork` on `radius`.`Code` = `scopeofwork`.`Code`) as total_incentive'),
			DB::raw('(SELECT SUM(If(TrackerId <> 0,ewallet.`Amount`,0)) from ewallet inner join tracker on ewallet.TrackerId = tracker.Id where '.$filter.') as total_ewallet'),
			DB::raw('(SELECT COUNT(Distinct(TrackerId)) from ewallet left join tracker on tracker.Id = ewallet.TrackerId where TrackerId <> 0 AND '.$filter.') as ewallet')


		)
		->whereRaw($filter)
		->get();

		if(!$gross[0]->Total_Invoiced_Amount){
			$gross=collect([
				(object)[
					'Total_Invoiced_Amount'=>0,
					'total_budget'=>0,
					'total_manday'=>0,
					'total_incentive'=>0,
					'total_ewallet'=>0,
					'ewallet'=>0,
					'manday_site'=>0,
					'invoice_site'=>0,
					'total_tracker'=>0
				]
			]);
		}

		return view('dashboard3',['me'=>$me,'totalsites'=>$totalsites,'projected'=>$projected,'renewal'=>$renewal,'legalization'=>$legalization,'project'=>$project,'milestone'=>$milestone,'date'=>$date,'end'=>$end,'start'=>$start,
		'gross'=>$gross,'template'=>$template]);
	}

	public function pieChart(){
		$me=(new CommonController)->get_current_user();

		$region=isset($_GET['region']) ? $_GET['region']:null;

		$filter="1 ";
		$group="";
		$col="";

		$start=isset($_GET['start']) ? $_GET['start']:null;
		$end=isset($_GET['end']) ? $_GET['end']:null;

		$tracId=isset($_GET['trackerId']) ? $_GET['trackerId']:0;

		if(isset($_GET['region']) && $region == ""){
			$filter.=" AND tracker.Region IS NULL OR tracker.Region= '{$region}' ";
			$group='tracker.`Unique ID`';
			$col='tracker.`Unique ID`';

		}elseif($region != ""){
			$filter.=" AND tracker.Region= '{$region}' ";
			$group='tracker.`Unique ID`';
			$col='tracker.`Unique ID`';

		}
		$tracker=DB::table('tracker')
		->select('tracker.Id',DB::raw("CONCAT(tracker.`Unique ID`,'-',tracker.`Site ID`,'-',tracker.`Site LRD`,'-',tracker.`Site Name`) as 'site'"))
		->whereRaw($filter)
		->get();

		if($tracId != 0){
			$filter.=" AND tracker.Id = {$tracId} ";
		}
		if($start && $end){
			$filter.=' AND (tracker.`1st Claim Invoice Date` BETWEEN "'.$start.'" AND "'.$end.'" OR
			tracker.`2nd Claim Invoice Date` BETWEEN "'.$start.'" AND "'.$end.'" OR
			tracker.`3rd Claim Invoice Date` BETWEEN "'.$start.'" AND "'.$end.'" OR
			tracker.`Retention Invoice Date` BETWEEN "'.$start.'" AND "'.$end.'" OR
			tracker.`Invoice Date` BETWEEN "'.$start.'" AND "'.$end.'" )
			';
		}

		$details=DB::Table('materialpoitem')
		->select('materialpoitem.Type',DB::raw('SUM(materialpoitem.Qty*materialpoitem.Price) as Total'))
		->leftjoin('materialpo','materialpo.Id','=','materialpoitem.POId')
		->leftjoin('material','material.Id','=','materialpoitem.MaterialId')
		->leftjoin('tracker','tracker.Id','=','material.TrackerId')
		->whereRaw($filter)
		;
		$total=$details->groupBy('materialpoitem.Type')->orderBy('materialpoitem.Type')->get();
		$summary=DB::Table('materialpoitem')
		->select(DB::raw($col),DB::raw('SUM(materialpoitem.Qty*materialpoitem.Price) as Total'))
		->leftjoin('material','material.Id','=','materialpoitem.MaterialId')
		->leftjoin('materialpo','materialpo.Id','=','materialpoitem.MaterialId')
		->leftjoin('tracker','tracker.Id','=','material.TrackerId')
		->whereRaw($filter)
		->groupBy(DB::raw($group))
		->orderBy(DB::raw($col),'desc')
		->get();
		if(!$total && !$summary){
			$total=collect([
				(object)[
					'Type'=>'',
					'Total'=>''
				]
			]);
			$summary=collect([
				(object)[
					''=>'',
					'Total'=>''
				]
			]);
		}
		// dd($summary);
		$data="";
		$title="";
		foreach($total as $t){
			$data.=$t->Total.",";
			$title.=$t->Type.",";
		}
		$data=substr($data,0,strlen($data)-1);
		$title=substr($title,0,strlen($title)-1);

		$start=date('d-M-Y');
		$end=date('d-M-Y');
		return view('trackerpiechart',['me'=>$me,'total'=>$total,'start'=>$start,'end'=>$end,'title'=>$title,'data'=>$data,'summary'=>$summary,'tracker'=>$tracker,
		'tracId'=>$tracId]);
		return $total;
	}

	public function updatefibre(Request $request)
	{
		$me = (new CommonController)->get_current_user();

	  $filenames="";
	  $input = $request->all();

		$id= DB::table('fibrelog')
					->insertGetId(array(
					'TrackerId' => $input["FibreId"],
					'Team' => $input["Team"],
					'HDD' => $input["HDD"],
					'GV' => $input["GV"],
					'MH' => $input["MH"],
					'Poles' => $input["Poles"],
					'Subduct' => $input["Subduct"],
					'Cables' => $input["Cables"],
					'Weather' => $input["Weather"],
					'Activity' => $input["Activity"],
					'Issue' => $input["Issue"],
					'Action' => $input["Action"],
					'Remarks' => $input["Remarks"],
					'Date'=>date("d-M-Y"),
					'UserId'=>$me->UserId
				));

				return $id;

	}

	public function diary(Request $request)
	{
		$input = $request->all();

			$diary = DB::table('fibrelog')
			->select('fibrelog.Date','users.Name as Updated_By','fibrelog.Team','fibrelog.HDD','fibrelog.GV','fibrelog.MH','fibrelog.Poles','Subduct','fibrelog.Cables','fibrelog.Weather','fibrelog.Activity','fibrelog.Issue','fibrelog.Action','fibrelog.Remarks')
			->leftJoin('users', 'fibrelog.UserId', '=', 'users.Id')
			->orderBy('fibrelog.Date','ASC')
			->where('fibrelog.TrackerId', '=', $input["TrackerId"])
			->get();

		return json_encode($diary);

	}

	public function logisticschargesincentive($start=null,$end=null,$type=null,$companyid=null)
	{
		// dd($type,$companyid);
		$me = (new CommonController)->get_current_user();;

		if ($start==null)
		{
			$start=date('d-M-Y', strtotime('first day of last month'));
			$start = date('d-M-Y', strtotime($start . " +20 days"));
		}
		if ($end==null)
		{
			$end=date('d-M-Y', strtotime('first day of this month'));
			$end = date('d-M-Y', strtotime($end . " +19 days"));
		}

		$company = DB::table('companies')
		->select('Id','Company_Name')
		->whereIn('Id',[1,2,3])
		->get();

		$projecttype = DB::Table('companies')
		->select('type')
		->where('type','<>','')
		->groupBy('type')
		->get();

		$summary = DB::table('deliveryform')
		->leftJoin( DB::raw('(select Max(Id) as maxid,deliveryform_Id from deliverystatuses Group By deliveryform_Id) as max'), 'max.deliveryform_Id', '=', 'deliveryform.Id')
        ->leftJoin('deliverystatuses', 'deliverystatuses.Id', '=', DB::raw('max.`maxid`'))
		->leftJoin('users','users.Id','=','deliveryform.DriverId')
		->leftJoin('radius','radius.Id','=','deliveryform.Location')
		->leftJoin('roadtax','roadtax.Id','=','deliveryform.roadtaxId')
		->leftJoin('companies','companies.Id','=','deliveryform.company_id')
		->select('deliveryform.Id','deliveryform.roadtaxId','deliveryform.delivery_date','users.Name','deliveryform.DO_No','radius.Location_Name','companies.Company_Name','deliveryform.distance_km','deliveryform.charges_rate','deliveryform.charges','deliveryform.incentive_rate','deliveryform.basicincentive','deliveryform.ontime',DB::raw('IFNULL(deliveryform.incentive,0)'),DB::Raw(' (IFNULL(deliveryform.basicincentive,0) + IFNULL(deliveryform.ontime,0) + IFNULL(deliveryform.incentive,0) ) as totalincentive'))
		->whereRaw('(deliverystatuses.delivery_status = "Completed" OR deliverystatuses.delivery_status = "Incomplete") AND roadtax.Type != "TRUCK" AND DO_NO NOT LIKE BINARY "%\_R%"')
		->where(DB::raw('str_to_date(deliveryform.delivery_date,"%d-%M-%Y")'),">=",DB::raw('str_to_date("'.$start.'","%d-%M-%Y")'))
        ->where(DB::raw('str_to_date(deliveryform.delivery_date,"%d-%M-%Y")'),"<=",DB::raw('str_to_date("'.$end.'","%d-%M-%Y")'))
		->get();

		return view('logisticschargesincentive',['me' => $me, 'summary'=>$summary, 'start'=>$start,'end'=>$end , 'company'=>$company , 'companyid'=>$companyid, 'projecttype'=>$projecttype,'type'=>$type]);

	}
}
