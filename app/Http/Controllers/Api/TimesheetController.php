<?php namespace App\Http\Controllers\Api;

use JWTAuth;
use Tymon\JWTAuth\Exceptions\JWTException;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use DateTime;
use Input;
use App\Http\Controllers\LeaveController;

class TimesheetController extends Controller {

	public function repeattask()
	{
		$tasklist = DB::Table('tasks')
		 ->leftJoin(DB::raw('(SELECT Max(Id) as maxid, TaskId FROM taskstatuses GROUP BY TaskId) as max'),'tasks.Id','=','max.TaskId')
        ->leftJoin('taskstatuses','taskstatuses.Id','=','max.maxid')
		->whereRaw('tasks.type = "Todo" AND tasks.taskrepeat = 1')
		->select('tasks.*','taskstatuses.Status')
		->get();

		foreach ($tasklist as $key => $value) {

			$time1 = DB::table('tasks')
			->select('repeattype','assign_date',DB::raw('COUNT(tasks.parentid) as count'))
			->where('parentId','=',$value->parentId)
			->groupBy('parentId')
			->first();

			$type = $time1->repeattype;
			$count = $time1->count;
			$time2 = date('d-M-Y',strtotime($time1->assign_date.'+'.$count.$type));
			$explode = explode("-",$time2);
			if(date('d', strtotime($time1->assign_date)) > date('d', strtotime($time2)))
			{
				$exp = $explode[1]."-".$explode[2];
				$time2 = date('d-M-Y',strtotime('last day of '.$exp.''));
			}

			$today=date('d-M-Y', strtotime('today'));
			if($today == $time2)
			{
				$id = DB::table('tasks')
				->insertGetId([
					'Current_Task' => $value->Current_Task,
					'UserId' => $value->UserId,
					'created_at' => DB::raw('Now()'),
					'assign_by' => $value->assign_by,
					'assign_date' => date('d-M-Y', strtotime('today')),
					'type' => $value->type,
					'reminder' => $value->reminder,
					'repeattype' => $value->repeattype,
					'taskrepeat' => $value->taskrepeat,
					'parentId' => $value->parentId
				]);

				DB::table('taskstatuses')
				->insert([
					'TaskId' => $id,
					'Status' => "Assigned",
					'UserId' => $value->UserId,
					'created_at' => DB::raw('Now()')
				]);
			}
		}
		return 1;
	}

	function test(Request $request){
		$me = JWTAuth::parseToken()->authenticate();

		DB::Table('timesheetotw')
		->insert([
			'Latitude'=>$request->lat,
			'Longitude'=>$request->long,
			'Date'=>DB::raw('now()'),
		]);
		return json_encode('value');

	}
	public function otw(Request $request)
	{
		$me = JWTAuth::parseToken()->authenticate();

		$input = $request->all();

		// $input["Time_In"]=date("g:i A");
		$input["Date"]=date('d-M-Y');
		$input["Time_Out"]=date("g:i A");
		// $input['lat'][0]->time
		// return json_encode($input['lat'][0]['time']);
		$timesheet=DB::table('timesheets')
		->select('Id','UserId','Time_In','Date')
		->where('UserId','=',$me->Id)
		->where('Date','=',$input["Date"])
		->where('Time_In','=','')
		->first();




		$x=0;
		foreach ($request->lat as $lat) {
			# code...
			foreach($lat['path'] as $l){
				if($x==0){
					$first=$l;
					$last=$request->lat;
					$last=end($last);
					// return json_encode($last);
					$insertid=DB::table('timesheets')
						// ->where('Id', $insertid)
						->insertGetId(array(
							'Check_In_Type' => "OTW",
							'UserId' => $me->Id,
							'Date'	=>	$input['Date'],
							'Time_In' =>  date('g:i A',strtotime($first['time'])),
							'Time_Out' => $input["Time_Out"],
							'ProjectId' => $input["ProjectId"],
							'Site_Name' => $input["Site_Name"],
							'Latitude_In' => $first['lat'],
							'Longitude_In' => $first['lng'],
							'Latitude_Out' => $last['path'][0]['lat'],
							'Longitude_Out' => $last['path'][0]['lng'],
							'Code' => "OTW",
							'Source' => "mobile",
						));
					$x++;
				}
				DB::table('timesheetotw')
				->insert([
					'TimesheetId' => $insertid,
					'UserId' => $me->Id,
					'Time_In' => date('g:i A',strtotime($lat['time'])),
					'Time_Out' => date('g:i A',strtotime($input["Time_Out"])),
					'Date'	=>	$input['Date'],
					'ProjectId' => $input["ProjectId"],
					'Site_Name' => $input["Site_Name"],
					'Latitude' => $l['lat'],
					'Longitude' => $l['lng'],
					'Time' => date('g:i A',strtotime($l['time'])),
				]);
			}

		}
		// $first=$request->lat[0]['path'][0];
		// 		$input['lat'][0]['time']=date("g:i A",strtotime($input['lat'][0]['time']));


		$this->calculateDistance($insertid);
		return 1;
	}

	public function getradius(Request $request)
	{

    	$me = JWTAuth::parseToken()->authenticate();
		// $me = JWTAuth::parseToken()->authenticate();
		$input = $request->all();

		//check location within radius
		$radius = DB::table('radius')
		->select('radius.Id','radius.Location_Name','radius.Latitude','radius.Longitude')
		->where('Location_Name','!=','')
		->get();



		$valid=0;
		// $locationname=$input["Location"];
		$locations = [];
		foreach ($radius as $location) {
			# code...
			if($this->distance($location->Latitude,$location->Longitude,$input["Latitude_In"],$input["Longitude_In"],"METER")<=1000)
			{
				// $locationname=$location->Location_Name;
				// $valid=1;
				array_push($locations, $location);
			}
		}

		return json_encode($locations);

	}

	public function newtimesheet(Request $request)
	{

		$me = JWTAuth::parseToken()->authenticate();

		if (! $me->Active) {
			return response()->json(['error' => 'Unauthorized action.'], 403);
		}

		$leaveTerms = DB::select('SELECT leave_terms.Leave_Date, leave_terms.Leave_Period FROM leaves
			LEFT JOIN
			(SELECT MAX(Id) as maxid, LeaveId FROM leavestatuses GROUP BY LeaveId) as max ON max.LeaveId = leaves.Id
			LEFT JOIN leavestatuses ON leavestatuses.Id = max.maxid
			LEFT JOIN leave_terms ON leave_terms.Leave_Id = leaves.Id
			WHERE (leavestatuses.Leave_Status LIKE "%Approved%" OR leavestatuses.Leave_Status LIKE "%Pending Approval%")
			AND leaves.UserId = :User_Id AND (GREATEST(STR_TO_DATE(:Date_1, "%d-%b-%Y"), STR_TO_DATE(leaves.Start_Date, "%d-%b-%Y")) <= LEAST(STR_TO_DATE(:Date_2, "%d-%b-%Y"), STR_TO_DATE(leaves.End_Date, "%d-%b-%Y")))
			AND leave_terms.Leave_Date = :Date_3',
        [
            'Date_1' => date('d-M-Y'),
            'Date_2'   => date('d-M-Y'),
            'Date_3'   => date('d-M-Y'),
            'User_Id'    => $me->Id
        ]);

        if (count($leaveTerms)) {

        	// condition:
        	$morning = false;
        	$afternoon = false;

    		foreach ($leaveTerms as $term) {
    			if ($term->Leave_Period == 'Full') {
    				return response()->json(['error' => 'User is on leave.'], 403);
    			} elseif ($term->Leave_Period == 'AM') {
    				$morning = true;
    			} elseif ($term->Leave_Period == 'PM') {
    				$afternoon = true;
    			}
    		}

    		if ($morning && $afternoon) {
    			return response()->json(['error' => 'User is on leave.'], 403);
    		}

    		if ($morning) {
    			// if now is 1PM or before
    			if (strtotime('now') <= strtotime('1 PM')) {
    				return response()->json(['error' => 'User is on leave.'], 403);
    			}
    		}

    		if ($afternoon) {
    			// if now is after 1PM
    			if (strtotime('now') > strtotime('1 PM')) {
					return response()->json(['error' => 'User is on leave.'], 403);
    			}
    		}

        }


		$input = $request->all();

		$input["Time_In"]=date("g:i A",strtotime($input["Time"]));
		$input["Date"]=date('d-M-Y');

		$timesheet=DB::table('timesheets')
		->where('UserId', '=', $me->Id)
		->where('Date', '=', $input["Date"])
		->where('Time_In','=','')
		->first();

		if(!isset($input['Code']))
		{
			$input['Code']=array();
		}

		//added by hau , Input["ProjectId"] not define error
		// if(!isset($input['ProjectId']))
		// {
		// 	$input['ProjectId']=0;
		// }

		//check location within radius
		$radius = DB::table('radius')
		->select('radius.Id','radius.Location_Name','radius.Latitude','radius.Longitude')
		->get();

			// $active = DB::table('users')
			// ->where('Active','=',1)
			// ->get();

		$valid=0;
		$locationname=$input["Site_Name"];
		$notinradiusdeduction = 0;
		$arrlocationname=array();
		if (strpos(strtoupper($locationname), 'OFFICE') !== false) {

		    foreach ($radius as $location) {
		    	# code...
		    	if($this->distance($location->Latitude,$location->Longitude,$input["Latitude_In"],$input["Longitude_In"],"METER")<=1000)
		    	{
		    		// $locationname=$location->Location_Name;
		    		$valid=1;
		    	}
		    }

				//if selected office related location but all out of 105m then deduct RM5
				if($valid==0)
				{
					$notinradiusdeduction = 10;
				}else {
					// code...
					$notinradiusdeduction = 0;
				}
		} else {

			foreach ($radius as $location) {
				# code...
				if($this->distance($location->Latitude,$location->Longitude,$input["Latitude_In"],$input["Longitude_In"],"METER")<=1000)
				{
					// $locationname=$location->Location_Name;
					$valid=1;
				}
			}
		}

		//added by hau , Input["Remarks"] not define error
		if(isset($input["Remarks"]))
		{
			$remarks=$input["Remarks"];
			$input["Remarks"]=$remarks;
		}
		else {
			// code...
			$remarks="";
		}

		// new function add by afiq 07-02-2020
		if($valid==0){

			return json_encode("Location is not in radius");
		}

		if($valid==0)
		{
			$remarks=$remarks."[Location not in Radius Management List.]";
			$input["Remarks"]=$remarks;
		}

		$arrCode = $input['Code'];
		// dd($arrCode);
		$arrCode2 = array();
		foreach ($arrCode as $key => $value) {
// dd($value);
			array_push($arrCode2, $value['Code']);

		}



		if($timesheet)
		{

			// check for already time in with the date
			$exist = DB::table('timesheets')
				->where('UserId', '=', $me->Id)
				->where('Date', '=', $input["Date"])
				->where('Time_In','<>','')
				->first();

			$insertid=$timesheet->Id;

			DB::table('timesheets')
			->where('Id', $insertid)
			->update(array(
				'Check_In_Type' => $input["Check_In_Type"],
				'Time_In' => $input["Time_In"],
				'Leader_Member' => $input["Leader_Member"],
				// 'Next_Person' => $input["Next_Person"],
				'ProjectId' => $input["ProjectId"],
				'Site_Name' => $locationname,
				'Latitude_In' => $input["Latitude_In"],
				'Longitude_In' => $input["Longitude_In"],
				// 'State' => $input["State"],
				// 'Work_Description' => $input["Work_Description"],
				// 'Reason' => $input["Reason"],
				'Remarks' => isset($input["Remarks"])? $input["Remarks"]:'',
				'Source' => "mobile",
				'Code' => implode("+", $arrCode2),
				// 'Scope' => $input["Scope"],
				// 'Project_Code' => $input["Project_Code"],
				// 'Deduction' => $deduction
			));

			// if previous time in not exist, then calculate and insert deduction
			if (! $exist) {
				$deduction = $this->calculatededuction($input);
				if ($deduction > 0) {

					// $this->insertdeduction($input, $me, $insertid, $deduction);
					DB::table('timesheets')
					->where('Id', $insertid)
					->update([
						'Deduction' => $deduction
					]);

					$deducted = DB::table('staffdeductions')
						->where('UserId', '=', $me->Id)
						->where('Date', '=', $input["Date"])
						->where('Type', '=', 'Late')
						->first();

						if(!$deducted)
						{
							DB::table('staffdeductions')->insert([
								'UserId' => $me->Id,
								'Type'	=> 'Late',
								'Description' => 'Late Time-In: ' . $input['Time_In'],
								'Month' => date("F Y", strtotime($input['Date'])),
								'Date' => $input['Date'],
								'Amount' => $deduction,
								'FinalAmount' => $deduction,
								'created_by' => $me->Id,
								'created_at' => date('Y-m-d H:i:s')
							]);
						}
						else {
							// code...
						}

				}

				if (strtotime("now") >= strtotime("1st april 2019")) {
					if (strtoupper($me->Department) == 'MY_DEPARTMENT_CMEOSU' ||
						strtoupper($me->Department) == 'MY_DEPARTMENT_FAB' ||
						strtoupper($me->Department) == 'MY_DEPARTMENT_IT' ||
						strtoupper($me->Department) == 'DEPARTMENT_GST_HQ_STORE_SUP' ||
						strtoupper($me->Department) == 'DEPARTMENT_GST_HQ_STORE01' ||
						strtoupper($me->Department) == 'DEPARTMENT_GST_HQ_STORE02' ||
						strtoupper($me->Department) == 'DEPARTMENT_GST_HQ_STORE03'
					) {

						if ($notinradiusdeduction > 0) {

							$notInRadiusExist = DB::table('staffdeductions')->where('UserId', $me->Id)->where('Type','Not In Radius')->where('Date', $input['Date'])->first();
							if (! $notInRadiusExist) {

								DB::table('timesheets')
								->where('Id', $insertid)
								->update([
									'Deduction' => $deduction + $notinradiusdeduction
									// 'Deduction' => $deduction
								]);

								DB::table('staffdeductions')->insert([
									'UserId' => $me->Id,
									'Type'	=> 'Not In Radius',
					 				'Description' => '[Location not in Radius Management List.] Site: ' . $locationname . ' | Time-In: ' . $input['Time_In'],
									'Month' => date("F Y", strtotime($input['Date'])),
									'Date' => $input['Date'],
									'Amount' => $notinradiusdeduction,
									'FinalAmount' => $notinradiusdeduction,
									'created_by' => $me->Id,
									'created_at' => date('Y-m-d H:i:s')
								]);
							}
						}

					}
				}
			}
		}

		else {

			$timesheet=DB::table('timesheets')
			->where('UserId', '=', $me->Id)
			->where('Date', '=', $input["Date"])
			->where('Time_In','=',$input["Time_In"])
			->first();

			if($timesheet)
			{
				$insertid=$timesheet->Id;

				DB::table('timesheets')
				->where('Id', $insertid)
				->update(array(
					'Check_In_Type' => $input["Check_In_Type"],
					'Time_In' => $input["Time_In"],
					'Leader_Member' => $input["Leader_Member"],
					// 'Next_Person' => $input["Next_Person"],
					'ProjectId' => $input["ProjectId"],
					'Site_Name' => $locationname,
					'Latitude_In' => $input["Latitude_In"],
					'Longitude_In' => $input["Longitude_In"],
					// 'State' => $input["State"],
					// 'Work_Description' => $input["Work_Description"],
					// 'Reason' => $input["Reason"],
					'Remarks' => isset($input["Remarks"])? $input["Remarks"]:'',
					'Source' => "mobile",
					'Code' => implode("+", $arrCode2),
					// 'Scope' => $input["Scope"],
					// 'Project_Code' => $input["Project_Code"],
					// 'Deduction' => $deduction
				));
			}

			else
			{

				// check for already time in with the date
				$exist = DB::table('timesheets')
					->where('UserId', '=', $me->Id)
					->where('Date', '=', $input["Date"])
					->where('Time_In','<>','')
					->first();

				// code...
				$insertid=DB::table('timesheets')->insertGetId([
					'UserId'  => $me->Id,
					'Date' => $input["Date"],
					'Check_In_Type' => $input["Check_In_Type"],
					'Time_In' => $input["Time_In"],
					'Leader_Member' => $input["Leader_Member"],
					// 'Next_Person' => $input["Next_Person"],
					'ProjectId' => $input["ProjectId"],
					'Site_Name' => $locationname,
					'Latitude_In' => $input["Latitude_In"],
					'Longitude_In' => $input["Longitude_In"],
					// 'State' => $input["State"],
					// 'Work_Description' => $input["Work_Description"],
					// 'Reason' => $input["Reason"],
					'Remarks' => isset($input["Remarks"])? $input["Remarks"]:'',
					'Source' => "mobile",
					'Code' => implode("+", $arrCode2),
					'created_at' => DB::raw('now()'),
					// 'Scope' => $input["Scope"],
					// 'Project_Code' => $input["Project_Code"],
					// 'Deduction' => $deduction
			 	]);

			 	// if previous time in not exist, then calculate and insert deduction
			 	if (! $exist) {

			 		$deduction = $this->calculatededuction($input);

			 		if ($deduction > 0) {
			 			// $this->insertdeduction($input, $me, $insertid, $deduction);

						DB::table('timesheets')
						->where('Id', $insertid)
						->update([
							'Deduction' => $deduction
						]);

						$deducted = DB::table('staffdeductions')
							->where('UserId', '=', $me->Id)
							->where('Date', '=', $input["Date"])
							->where('Type', '=', 'Late')
							->first();

							if(!$deducted)
							{

								DB::table('staffdeductions')->insert([
					 				'UserId' => $me->Id,
					 				'Type'	=> 'Late',
					 				'Description' => 'Late Time-In: ' . $input['Time_In'],
					 				'Month' => date("F Y", strtotime($input['Date'])),
					 				'Date' => $input['Date'],
					 				'Amount' => $deduction,
					 				'FinalAmount' => $deduction,
					 				'created_by' => $me->Id,
					 				'created_at' => date('Y-m-d H:i:s')
					 			]);
							}
							else {
								// code...
							}

			 		}

			 		if (strtotime("now") >= strtotime("1st april 2019")) {
						if (strtoupper($me->Department) == 'MY_DEPARTMENT_CMEOSU' ||
							strtoupper($me->Department) == 'MY_DEPARTMENT_FAB' ||
							strtoupper($me->Department) == 'MY_DEPARTMENT_IT' ||
							strtoupper($me->Department) == 'DEPARTMENT_GST_HQ_STORE_SUP' ||
							strtoupper($me->Department) == 'DEPARTMENT_GST_HQ_STORE01' ||
							strtoupper($me->Department) == 'DEPARTMENT_GST_HQ_STORE02' ||
							strtoupper($me->Department) == 'DEPARTMENT_GST_HQ_STORE03'
						) {
							if ($notinradiusdeduction > 0) {
								$notInRadiusExist = DB::table('staffdeductions')->where('UserId', $me->Id)->where('Type','Not In Radius')->where('Date', $input['Date'])->first();

								if (! $notInRadiusExist) {
									DB::table('timesheets')
									->where('Id', $insertid)
									->update([
										'Deduction' => $deduction + $notinradiusdeduction
										// 'Deduction' => $deduction
									]);

									DB::table('staffdeductions')->insert([
										'UserId' => $me->Id,
										'Type'	=> 'Not In Radius',
										'Description' => '[Location not in Radius Management List.] Site: ' . $locationname . ' | Time-In: ' . $input['Time_In'],
										'Month' => date("F Y", strtotime($input['Date'])),
										'Date' => $input['Date'],
										'Amount' => $notinradiusdeduction,
										'FinalAmount' => $notinradiusdeduction,
										'created_by' => $me->Id,
										'created_at' => date('Y-m-d H:i:s')
									]);
								}
							}
						}
					}

			 	}
			}
		}

		if(Input::has('File')) {

			$files = Input::file('File');

			$file_count = count($files);
			$uploadcount = 1;

			foreach($files as $file) {

				$destinationPath=public_path()."/private/upload/Timesheet";
				$extension = $file->getClientOriginalExtension();
				$originalName=$file->getClientOriginalName();
				$fileSize=$file->getSize();
				$fileName=time()."_".$uploadcount.".".$extension;
				$upload_success = $file->move($destinationPath, $fileName);
				$uploadcount ++;

				DB::table('files')->insert([
					'Type' => "Timesheet",
					'TargetId' => $insertid,
					'File_Name' => $originalName,
					'File_Size' => $fileSize,
					'Web_Path' => realpath(__DIR__ . '/..').'/private/upload/Timesheet/'.$fileName
					]
				);
			}
		}

		if (strtoupper($me->Department) != 'MY_DEPARTMENT_FAB' &&
			strtoupper($me->Department) != 'MY_DEPARTMENT_MDO' &&
			strtoupper($me->Department) != 'MY_DEPARTMENT_GST' &&
			strtoupper($me->Department) != 'MY_DEPARTMENT_IT'
		) {
			//apply timein replacement leave request on Sunday
			$current = date("d-M-Y");
			$start = strtotime($current);
			$day_type = date("w", $start);

			$holiday = (new LeaveController)->getCurrentDateHoliday($current, $me->HolidayTerritoryId);
			if (count($holiday) > 0 || $day_type == 0) {



			     $approver = DB::table('approvalsettings')
		        ->leftJoin('projects', 'projects.Id', '=', 'approvalsettings.ProjectId')
		        ->select('approvalsettings.UserId','approvalsettings.Level','approvalsettings.ProjectId')
		        ->where('approvalsettings.Type', '=', 'Request')
		        ->where('approvalsettings.ProjectId', '<>', '0')
		        ->where('projects.Project_Name','MY_DEPARTMENT_HRA')
		        ->orderBy('approvalsettings.Country','asc')
		        ->orderBy('projects.Project_Name','asc')
		        ->orderByRaw("FIELD(approvalsettings.Level , '1st Approval','2nd Approval','3rd Approval','4th Approval','5th Approval','Final Approval') ASC")
		        ->first();

		        $exist = DB::table('request')
		   			->leftJoin( DB::raw('(select Max(Id) as maxid,RequestId from requeststatuses Group By RequestId) as max'), 'max.RequestId', '=', 'request.Id')
					->leftJoin('requeststatuses', 'requeststatuses.Id', '=', DB::raw('max.`maxid`'))
		        	->where('request.Request_Type', 'Replacement Leave')
		        	->where('request.Start_Date', $input["Date"])
		        	->where('request.End_Date', $input["Date"])
		        	->where('request.UserId', $me->Id)
		        	->where('requeststatuses.Request_Status', '<>', 'Cancelled')
		        	->where('requeststatuses.Request_Status', 'NOT LIKE', '%Rejected%')
		        	->first();

		        if (! $exist) {
			        if (count($holiday)) {

					    $id=DB::table('request')->insertGetId([
					    	'UserId' => $me->Id,
							'ProjectId' => $approver->ProjectId,
					        'Request_Type' => 'Replacement Leave',
					        'Others' => '',
							'Start_Date' => $input["Date"],
							'End_Date' => $input["Date"],
							'Remarks' => 'Working on Public Holiday [' . $holiday[0]->Holiday . '] | ' . $locationname
					    ]);
			        } else {
		        	    $id=DB::table('request')->insertGetId([
		        	    	'UserId' => $me->Id,
		        			'ProjectId' => $approver->ProjectId,
		        	        'Request_Type' => 'Replacement Leave',
		        	        'Others' => '',
		        			'Start_Date' => $input["Date"],
		        			'End_Date' => $input["Date"],
		        			'Remarks' => 'Working on Sunday | ' . $locationname
		        	    ]);
			        }

			        DB::table('requeststatuses')->insert([
			        	'RequestId' => $id,
			            'UserId' => $approver->UserId,
			            'Request_Status' =>"Pending Approval"
			        ]);

			        $requestdetail = DB::table('request')
			          	->leftJoin('requeststatuses', 'request.Id', '=', 'requeststatuses.RequestId')
			          	->leftJoin('users as applicant', 'request.UserId', '=', 'applicant.Id')
			          	->leftJoin('users as approver', 'requeststatuses.UserId', '=', 'approver.Id')
			          	// ->leftJoin('projects', 'request.ProjectId', '=', 'projects.Id')
			          	->select('applicant.Name','request.Request_Type','request.Others','request.Start_Date','request.End_Date','approver.Name as Approver', 'request.Remarks as Remarks')
			          	->orderBy('requeststatuses.Id','desc')
			          	->where('request.Id', '=',$id)
			          	->first();

			        $notify = DB::table('users')
			          	->whereIn('Id', [$me->UserId, $approver->UserId])
			          	->get();

			        $subscribers = DB::table('notificationtype')
			          	->leftJoin('notificationsubscriber','notificationtype.Id','=','notificationsubscriber.NotificationTypeId')
			          	->leftJoin('users','users.Id','=','notificationsubscriber.UserId')
			          	->where('notificationtype.Id','=',62)
			          	->get();

			        $emails = array();

			        foreach ($subscribers as $subscriber) {
			        	$NotificationSubject=$subscriber->Notification_Subject;
			            if ($subscriber->Company_Email!="")
			            {
			              	array_push($emails,$subscriber->Company_Email);
			            }
			            else
			            {
			              	array_push($emails,$subscriber->Personal_Email);
			            }

			        }

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

			        // Mail::send('emails.requestapplication', ['requestdetail' => $requestdetail], function($message) use ($emails,$me,$NotificationSubject)
			        // {
			        //     array_push($emails,env('MAIL_DEFAULT_RECIPIENT'));
			        //     $emails = array_filter($emails);
			        //     $message->to($emails)->subject($NotificationSubject.' ['.$me->Name.']');
			        // });
		        }

		        // dd($approver);

			}
		}
		//apply timeinreplacement leave request on sunday

		// $this->calculateovertime($me, $input['Date']);

		return $insertid;
	}

	protected function calculateovertime($me, $date)
	{
		$userId = $me->Id;

        $sameDateTimesheets = DB::table('timesheets')
        ->select('Id', 'Date', 'UserId', 'Time_In', 'Time_Out', DB::raw("CASE
	            WHEN Time_Out = '' OR Time_In = '' OR Time_Out = NULL or Time_In = NULL
	                THEN 0
	            WHEN str_to_date(timesheets.Time_Out, '%l:%i %p') < str_to_date(timesheets.Time_In, '%l:%i %p')
	                THEN TIME_TO_SEC(ADDTIME(TIMEDIFF(str_to_date(timesheets.Time_Out, '%l:%i %p'),str_to_date(timesheets.Time_In, '%l:%i %p')),'24:00:00'))
	            WHEN str_to_date(timesheets.Time_Out, '%l:%i %p') >= str_to_date(timesheets.Time_In, '%l:%i %p')
	                THEN TIME_TO_SEC(TIMEDIFF(str_to_date(timesheets.Time_Out, '%l:%i %p'),str_to_date(timesheets.Time_In, '%l:%i %p')))
	        END as Duration"))
    	->where('timesheets.Date', $date)
    	->where('timesheets.UserId', $userId)
    	->get();

    	if (strtoupper($me->Department) == 'MY_DEPARTMENT_FAB') {
	    	$earlyTimeIn = DB::table('timesheets')
	        ->select(DB::raw("SUM(
	        	CASE WHEN str_to_date(timesheets.Time_Out, '%l:%i %p') < str_to_date('9:00 AM', '%l:%i %p')
	        		THEN TIME_TO_SEC(TIMEDIFF(str_to_date(timesheets.Time_Out, '%l:%i %p'),str_to_date(timesheets.Time_In, '%l:%i %p')))
	        	WHEN str_to_date(timesheets.Time_Out, '%l:%i %p') >= str_to_date('9:00 AM', '%l:%i %p')
	        		THEN TIME_TO_SEC(TIMEDIFF(str_to_date('9:00 AM', '%l:%i %p'),str_to_date(timesheets.Time_In, '%l:%i %p')))
	        	ELSE
	        		0
	        	END
	        ) as Total_Early"))
	    	->where('timesheets.Date', $date)
	    	->where('timesheets.UserId', $userId)
	    	->first();

	    	if ($earlyTimeIn->Total_Early > 0) {
		    	$earlyHours = floor($earlyTimeIn->Total_Early / 3600);
				$earlyMins = floor($earlyTimeIn->Total_Early / 60 % 60);
				$earlySecs = floor($earlyTimeIn->Total_Early % 60);
				$totalEarlyHours = $earlyHours + (($earlyMins / 60) * 1);
	    	} else {
	    		$totalEarlyHours = 0;
	    	}
    	} else {
			$totalEarlyHours = 0;
    	}




		$timesheetId = collect($sameDateTimesheets)->min('Id');

    	$totalDuration = 0;
		foreach ($sameDateTimesheets as $sameDateTimesheet) {
		    $totalDuration = $totalDuration + $sameDateTimesheet->Duration;
		}

		$hours = floor($totalDuration / 3600);
		$mins = floor($totalDuration / 60 % 60);
		$secs = floor($totalDuration % 60);

		$current = date("d-M-Y");

		$day_type = date("w", strtotime($current));

		// FAB and MDO only 6 wd
		// saturday 6, sunday 0
		if ($day_type == 6 || $day_type == 0) {
			if ($day_type == "6") {
			    // if($me->Working_Days == 5.0) {
			    // 	// full restday
			    // 	$standardWorkingHours = 0;
			    // } elseif($me->Working_Days == 5.5) {
			    //     // half restday
			    //     $standardWorkingHours = 4;
			    // } elseif($me->Working_Days == 6.0) {
			    	// working day on saturday
			    	$standardWorkingHours = 9 + $totalEarlyHours;

	    			// $totalWorkingHours = $hours + (($mins / 60) * 1);
	    			$totalWorkingHours = $hours + (($mins / 60) * 1);

	    	    	$OT = $totalWorkingHours - $standardWorkingHours;

	    	    	if ($OT > 0) {
	    	    		// minus 9 hours of standard work hour
	    	    		$OT = $totalWorkingHours - $standardWorkingHours;
	    	    		$OT = $OT >= 0 ? $OT : 0;
	    	    	} else {
	    	    		$OT = 0;
	    	    	}

	    	    	list($otHours, $otMins) = explode('.',number_format((float)$OT, 2, '.', ''));
	    	    	$update = DB::table('timesheets')
	    	    	->where('timesheets.Id', $timesheetId)
	    	    	// ->where('timesheets.UserId', $userId)
	    	    	// ->where('timesheets.Date', $date)
	    	    	->update([
	    	    		'OT1' => $otHours + ((substr($otMins, 0, 2)/100)*0.6),
	    	    		'updated_at' => DB::raw('now()')
	    	    	]);
			    // }
			} elseif ($day_type=="0") {
		    	// full restday
		    	$standardWorkingHours = 0 + $totalEarlyHours;

    			// $totalWorkingHours = $hours + (($mins / 60) * 1);
    			$totalWorkingHours = $hours + (($mins / 60) * 1);

    	    	$OT = $totalWorkingHours - $standardWorkingHours;

    	    	if ($OT > 0) {
    	    		// minus 9 hours of standard work hour
    	    		$OT = $totalWorkingHours - $standardWorkingHours;
    	    		$OT = $OT >= 0 ? $OT : 0;
    	    	} else {
    	    		$OT = 0;
    	    	}

    	    	list($otHours, $otMins) = explode('.',number_format((float)$OT, 2, '.', ''));
    	    	$update = DB::table('timesheets')
    	    	->where('timesheets.Id', $timesheetId)
    	    	// ->where('timesheets.UserId', $userId)
    	    	// ->where('timesheets.Date', $date)
    	    	->update([
    	    		'OT2' => $otHours + ((substr($otMins, 0, 2)/100)*0.6),
    	    		'updated_at' => DB::raw('now()')
    	    	]);
			}


	    	return;
		}


    	$holiday = (new LeaveController)->getCurrentDateHoliday($current, $me->HolidayTerritoryId);

		// today is holiday, calculate OT3
		if (count($holiday) > 0) {
	    	$standardWorkingHours = 0 + $totalEarlyHours;
			// $totalWorkingHours = $hours + (($mins / 60) * 1);

			$totalWorkingHours = $hours + (($mins / 60) * 1);
	    	$OT = $totalWorkingHours - $standardWorkingHours;
	    	if ($OT > 0) {
	    		// minus 9 hours of standard work hour
	    		$OT = $totalWorkingHours - $standardWorkingHours;
	    		$OT = $OT >= 0 ? $OT : 0;
	    	} else {
	    		$OT = 0;
	    	}

	    	list($otHours, $otMins) = explode('.',number_format((float)$OT, 2, '.', ''));
	    	$update = DB::table('timesheets')
	    	->where('timesheets.Id', $timesheetId)
	    	// ->where('timesheets.UserId', $userId)
	    	// ->where('timesheets.Date', $date)
	    	->update([
	    		'OT3' => $otHours + ((substr($otMins, 0, 2)/100)*0.6),
	    		'updated_at' => DB::raw('now()')
	    	]);

	    	return;
		}
		// not holiday and not restday, calculate OT1.5
    	$standardWorkingHours = 9 + $totalEarlyHours;
		// $totalWorkingHours = $hours + (($mins / 60) * 1);
		$totalWorkingHours = $hours + (($mins / 60) * 1);
    	$OT = $totalWorkingHours - $standardWorkingHours;
    	if ($OT > 0) {
    		// minus 9 hours of standard work hour
    		$OT = $totalWorkingHours - $standardWorkingHours;
    		$OT = $OT >= 0 ? $OT : 0;
    	} else {
    		$OT = 0;
    	}

    	list($otHours, $otMins) = explode('.',number_format((float)$OT, 2, '.', ''));
    	$update = DB::table('timesheets')
    	->where('timesheets.Id', $timesheetId)
    	// ->where('timesheets.UserId', $userId)
    	// ->where('timesheets.Date', $date)
    	->update([
    		'OT1' => $otHours + ((substr($otMins, 0, 2)/100)*0.6),
    		'updated_at' => DB::raw('now()')
    	]);

    	return;
	}

	private function calculatededuction(array $input)
	{

		$auth = JWTAuth::parseToken()->authenticate();
		$me = (new AuthController)->get_current_user($auth->Id);

		$date_time_in = DateTime::createFromFormat('d-M-Y g:i A', $input["Date"] . ' ' . $input["Time_In"]);
		$date_time_in_limit_1 = DateTime::createFromFormat('d-M-Y g:i A', $input["Date"] . ' 9:02 AM'); // more than will deduct rm 10
		$date_time_in_limit_2 = DateTime::createFromFormat('d-M-Y g:i A', $input["Date"] . ' 9:16 AM');  // more than will deduct rm 20

		$current = date("d-M-Y");

		//check leave taken today for this staff
		$leaves = DB::table('leaves')
		->leftJoin( DB::raw('(select Max(Id) as maxid,LeaveId from leavestatuses Group By LeaveId) as max'), 'max.LeaveId', '=', 'leaves.Id')
		->leftJoin('leavestatuses', 'leavestatuses.Id', '=', DB::raw('max.`maxid`'))
		->where('leaves.UserId','=',$me->UserId)
		->whereRaw('leavestatuses.Leave_Status!="Cancelled" AND leavestatuses.Leave_Status not like "%Rejected%"')
		->whereRaw('str_to_date("'.$current.'","%d-%M-%Y") between str_to_date(leaves.Start_Date,"%d-%M-%Y") AND str_to_date(leaves.End_Date,"%d-%M-%Y")')
		->get();

		if (count($leaves) > 0) {
				return 0;
		}

		//check leave taken today for this staff

		//check is today holiday for this staff(holiday no deduct)
		$holiday = (new LeaveController)->getCurrentDateHoliday($current, $me->HolidayTerritoryId);

		if (count($holiday) > 0) {
				return 0;
		}

		//check is today holiday for this staff

		//check is today sunday(sunday no deduct)
		$start = strtotime($current);
		$day_type = date("w", $start);

		if ($day_type == 0) {

			return 0;
		}
		//check is today sunday
		$deduction=0;
		if ($date_time_in < $date_time_in_limit_1) {
			$deduction = 0;
		} elseif ($date_time_in < $date_time_in_limit_2) {

			if(strtoupper($me->Department)=="MY_DEPARTMENT_CMEPRO" ||
				starts_with(strtoupper($me->Department),"DEPARTMENT_CME_PRO")==1 ||
				strtoupper($me->Department)=="MY_DEPARTMENT_FAB")
			{
				$deduction = 5;
			}
			elseif(strtoupper($me->Department)=="MY_DEPARTMENT_ACCT" ||
				starts_with(strtoupper($me->Department),"DEPARTMENT_ACCTS")==1 ||
				strtoupper($me->Department)=="MY_DEPARTMENT_CME" ||
				starts_with(strtoupper($me->Department),"DEPARTMENT_CME")==1 ||
				strtoupper($me->Department)=="MY_DEPARTMENT_CMEOSU" ||
				strtoupper($me->Department)=="MY_DEPARTMENT_CMEPMO" ||
				strtoupper($me->Department)=="MY_DEPARTMENT_HOD" ||
				strtoupper($me->Department)=="MY_DEPARTMENT_IT")
			{
					$deduction = 10;
			}
			elseif(strtoupper($me->Department)=="MY_DEPARTMENT_GST" ||
			starts_with(strtoupper($me->Department),"DEPARTMENT_GST")==1 ||
			strtoupper($me->Department)=="MY_DEPARTMENT_HRA" ||
			strtoupper($me->Department)=="MY_DEPARTMENT_LOG" ||
			strtoupper($me->Department)=="MY_DEPARTMENT_MDO")
			{

				if(
				strtoupper($me->Position)=="PROJECT COORDINATOR" && (strtoupper($me->Department)=="MY_DEPARTMENT_GST" ||
				starts_with(strtoupper($me->Department),"DEPARTMENT_GST")==1))
				{
					$deduction = 10;
				}
				else if(strtoupper($me->Position)=="TECHNICIAN" ||
				strtoupper($me->Position)=="STOR EXECUTIVE" ||
				strtoupper($me->Position)=="GENERAL WORKER" ||
				strtoupper($me->Position)=="FOREMAN" ||
				strtoupper($me->Position)=="WIREMAN" ||
				strtoupper($me->Position)=="LORRY DRIVER" ||
				strtoupper($me->Position)=="ASSISTANT FOREMAN" ||
				strtoupper($me->Position)=="POOL DRIVER" ||
				strtoupper($me->Position)=="TEAM LEADER" ||
				strtoupper($me->Position)=="SITE MANAGER" ||
				strtoupper($me->Position)=="TI INSTALLER" ||
				strtoupper($me->Position)=="PROJECT COORDINATOR" ||
				strtoupper($me->Position)=="PROJECT ADMIN ASSISTANT" ||
				strtoupper($me->Position)=="ASSISTANT SUPERVISOR" ||
				strtoupper($me->Position)=="TI TEAM LEADER" ||
				strtoupper($me->Position)=="PROJECT ADMIN ASSISTANT" ||
				strtoupper($me->Position)=="WELDER")
				{
					$deduction = 5;
				}

			}
			else {
				// code...
				$deduction = 10;
			}
		} else {

			if(strtoupper($me->Department)=="MY_DEPARTMENT_CMEPRO" ||
				strtoupper($me->Department)=="MY_DEPARTMENT_CMEPRO" ||
				starts_with(strtoupper($me->Department),"DEPARTMENT_CME_PRO")==1 ||
				strtoupper($me->Department)=="MY_DEPARTMENT_FAB")
			{
				$deduction = 10;
			}
			elseif(strtoupper($me->Department)=="MY_DEPARTMENT_ACCT" ||
				starts_with(strtoupper($me->Department),"DEPARTMENT_ACCTS")==1 ||
				strtoupper($me->Department)=="MY_DEPARTMENT_CME" ||
				starts_with(strtoupper($me->Department),"DEPARTMENT_CME")==1 ||
				strtoupper($me->Department)=="MY_DEPARTMENT_CMEOSU" ||
				strtoupper($me->Department)=="MY_DEPARTMENT_CMEPMO" ||
				strtoupper($me->Department)=="MY_DEPARTMENT_HOD" ||
				strtoupper($me->Department)=="MY_DEPARTMENT_IT")
			{
					$deduction = 20;
			}
			elseif(strtoupper($me->Department)=="MY_DEPARTMENT_GST" ||
			starts_with(strtoupper($me->Department),"DEPARTMENT_GST")==1 ||
			strtoupper($me->Department)=="MY_DEPARTMENT_HRA" ||
			strtoupper($me->Department)=="MY_DEPARTMENT_LOG" ||
			strtoupper($me->Department)=="MY_DEPARTMENT_MDO")
			{

				if(
				strtoupper($me->Position)=="PROJECT COORDINATOR" && (strtoupper($me->Department)=="MY_DEPARTMENT_GST" ||
				starts_with(strtoupper($me->Department),"DEPARTMENT_GST")==1))
				{
					$deduction = 20;
				}
				else if(strtoupper($me->Position)=="TECHNICIAN" ||
				strtoupper($me->Position)=="STOR EXECUTIVE" ||
				strtoupper($me->Position)=="GENERAL WORKER" ||
				strtoupper($me->Position)=="FOREMAN" ||
				strtoupper($me->Position)=="WIREMAN" ||
				strtoupper($me->Position)=="LORRY DRIVER" ||
				strtoupper($me->Position)=="ASSISTANT FOREMAN" ||
				strtoupper($me->Position)=="POOL DRIVER" ||
				strtoupper($me->Position)=="TEAM LEADER" ||
				strtoupper($me->Position)=="SITE MANAGER" ||
				strtoupper($me->Position)=="TI INSTALLER" ||
				strtoupper($me->Position)=="PROJECT COORDINATOR" ||
				strtoupper($me->Position)=="PROJECT ADMIN ASSISTANT" ||
				strtoupper($me->Position)=="ASSISTANT SUPERVISOR" ||
				strtoupper($me->Position)=="TI TEAM LEADER" ||
				strtoupper($me->Position)=="WELDER")
				{
					$deduction = 10;
				}

			}
			else {
				// code...
				$deduction = 20;
			}
		}

		return $deduction;
	}

	private function insertdeduction(array $input, $me, $timesheetid, $deduction) {


		// $deductionexists = DB::table('timesheets')
		// ->where('UserId', '=', $me->Id)
		// ->where('Date', '=', $input["Date"])
		// ->whereNotNull('Deduction')
		// ->first();

		// $deductionexist = DB::table('staffdeductions')->where('Date', $input['Date'])->where('UserId', $me->Id)->first();

		// if not exist
		// if (! $deductionexists) {

			// $timesheetid = DB::table('timesheets')
			// ->where('Date', '=', $input["Date"])
			// ->min('Id');

			// DB::table('timesheets')
			// ->where('Id', $timesheetid)
			// ->update([
			// 	'Deduction' => $deduction
			// ]);

			// DB::table('staffdeductions')->insert([
			// 	'UserId' => $me->Id,
			// 	'Type'	=> 'Late',
			// 	'Description' => 'Late Time-In: ' . $input['Time_In'],
			// 	'Month' => date("F Y", strtotime($input['Date'])),
			// 	'Date' => $input['Date'],
			// 	'Amount' => $deduction,
			// 	'FinalAmount' => $deduction,
			// 	'created_by' => $me->Id,
			// 	'created_at' => date('Y-m-d H:i:s')
			// ]);
		// }
	}


	public function calculateallowance(Request $request)
	{

		$me = JWTAuth::parseToken()->authenticate();

		$me = DB::table('users')
		->leftJoin( DB::raw('(select Max(Id) as maxid,TargetId from files where Type="User" Group By Type,TargetId) as max'), 'max.TargetId', '=', 'users.Id')
		->leftJoin('files', 'files.Id', '=', DB::raw('max.`maxid` and files.`Type`="User"'))
		->leftJoin('accesscontroltemplates', 'accesscontroltemplates.Id', '=', 'users.AccessControlTemplateId')
		->select('users.Id as UserId','users.AccessControlTemplateId','users.StaffId as Staff_ID','users.Name','users.Nick_Name','users.Company_Email','users.Personal_Email','users.Contact_No_1','users.Contact_No_2','users.Permanent_Address','users.Current_Address','users.Country_Base','users.Home_Base','users.Password','users.User_Type','users.Nationality','users.DOB','users.NRIC','users.Passport_No','users.Gender','users.Marital_Status','users.SuperiorId','users.Company','users.Department','users.Position','users.Joining_Date','users.Resignation_Date','users.Emergency_Contact_Person','users.Emergency_Contact_No','users.Emergency_Contact_Relationship','users.Emergency_Contact_Address','users.Note','users.Active','users.Admin','users.Approved','users.First_Change','files.Web_Path','Admin','Create_User','Template_Name','Admin','Create_User','Read_User','Update_User','Delete_User','Engineer_Monitoring','Create_CV','Read_CV','Update_CV','Delete_CV','Create_Contractor_Vendor','Read_Contractor_Vendor','Update_Contractor_Vendor','Delete_Contractor_Vendor','Read_Org_Chart','Update_Org_Chart','Read_Leave','Show_Leave_To_Public','Read_Timesheet','Read_Claim','Project_Manager','Create_Project','Create_Project_Code','Access_Control','Approval_Control','Allowance_Control','Asset_Tracking','Option_Control','Holiday_Management','Notice_Board_Management','Detail_Approved_On','Status')

		->where('users.Id', '=',$me->Id)
	    ->first();

		$input = $request->all();

		$Id=$input["TimesheetId"];
		$Date=$input["D"];
		$Time_In=$input["Time_In"];
		$Time_Out=$input["Time_Out"];
		$State=$input["State"];
		$Check_In_Type=$input["Check_In_Type"];
		$ProjectId=$input["ProjectId"];
		$Home_Base=$input["State"];

		$holiday=DB::table('holidays')
		->where(DB::raw('str_to_date(holidays.Start_Date,"%d-%M-%Y")'),"<=",DB::raw('str_to_date("'.$Date.'","%d-%M-%Y")'))
		->where(DB::raw('str_to_date(holidays.End_Date,"%d-%M-%Y")'),">=",DB::raw('str_to_date("'.$Date.'","%d-%M-%Y")'))
		->get();

		$timesheetdate=new DateTime($Date);

		if (count($holiday)>0)
		{
			$day_type="Public Holiday";
		}

		else
		{

			$day_type=$timesheetdate->format('l');
		}

		$allowance=0;

		switch ($day_type)
		{

			case "Monday":
			case "Tuesday":
			case "Wednesday":
			case "Thursday":

			case "Friday":
			$schemeitems = DB::table('users')
			->leftJoin('allowanceschemes', 'users.AllowanceSchemeId', '=', 'allowanceschemes.Id')
			->leftJoin('allowanceschemeitems', 'allowanceschemes.Id', '=', 'allowanceschemeitems.AllowanceSchemeId')
			->select('allowanceschemeitems.Id','allowanceschemeitems.AllowanceSchemeId','allowanceschemeitems.Day_Type','allowanceschemeitems.Start','allowanceschemeitems.End','allowanceschemeitems.Minimum_Hour','allowanceschemeitems.Currency','allowanceschemeitems.Home_Base','allowanceschemeitems.Outstation','allowanceschemeitems.Subsequent_Home_Base','allowanceschemeitems.Subsequent_Outstation','allowanceschemeitems.Remarks')
			->orderBy('allowanceschemeitems.Day_Type','asc')
			->orderBy(db::raw('str_to_date(allowanceschemeitems.Start,"%h:%i:%s %p")'),'asc')
			->where('allowanceschemeitems.Day_Type', '=',"Weekday")
			->where('users.Id', '=',$me->UserId)
			->get();
			break;

			case "Saturday":
			$schemeitems = DB::table('users')
			->leftJoin('allowanceschemes', 'users.AllowanceSchemeId', '=', 'allowanceschemes.Id')
			->leftJoin('allowanceschemeitems', 'allowanceschemes.Id', '=', 'allowanceschemeitems.AllowanceSchemeId')
			->select('allowanceschemeitems.Id','allowanceschemeitems.AllowanceSchemeId','allowanceschemeitems.Day_Type','allowanceschemeitems.Start','allowanceschemeitems.End','allowanceschemeitems.Minimum_Hour','allowanceschemeitems.Currency','allowanceschemeitems.Home_Base','allowanceschemeitems.Outstation','allowanceschemeitems.Subsequent_Home_Base','allowanceschemeitems.Subsequent_Outstation','allowanceschemeitems.Remarks')
			->orderBy('allowanceschemeitems.Day_Type','asc')
			->orderBy(db::raw('str_to_date(allowanceschemeitems.Start,"%h:%i:%s %p")'),'asc')
			->where('users.Id', '=',$me->UserId)
			->where('allowanceschemeitems.Day_Type', '=',"Saturday")
			->get();
			break;

			case "Sunday":
			$schemeitems = DB::table('users')
			->leftJoin('allowanceschemes', 'users.AllowanceSchemeId', '=', 'allowanceschemes.Id')
			->leftJoin('allowanceschemeitems', 'allowanceschemes.Id', '=', 'allowanceschemeitems.AllowanceSchemeId')
			->select('allowanceschemeitems.Id','allowanceschemeitems.AllowanceSchemeId','allowanceschemeitems.Day_Type','allowanceschemeitems.Start','allowanceschemeitems.End','allowanceschemeitems.Minimum_Hour','allowanceschemeitems.Currency','allowanceschemeitems.Home_Base','allowanceschemeitems.Outstation','allowanceschemeitems.Subsequent_Home_Base','allowanceschemeitems.Subsequent_Outstation','allowanceschemeitems.Remarks')
			->orderBy('allowanceschemeitems.Day_Type','asc')
			->orderBy(db::raw('str_to_date(allowanceschemeitems.Start,"%h:%i:%s %p")'),'asc')
			->where('users.Id', '=',$me->UserId)
			->where('allowanceschemeitems.Day_Type', '=',"Sunday")
			->get();
			break;

			case "Public Holiday":
			$schemeitems = DB::table('users')
			->leftJoin('allowanceschemes', 'users.AllowanceSchemeId', '=', 'allowanceschemes.Id')
			->leftJoin('allowanceschemeitems', 'allowanceschemes.Id', '=', 'allowanceschemeitems.AllowanceSchemeId')
			->select('allowanceschemeitems.Id','allowanceschemeitems.AllowanceSchemeId','allowanceschemeitems.Day_Type','allowanceschemeitems.Start','allowanceschemeitems.End','allowanceschemeitems.Minimum_Hour','allowanceschemeitems.Currency','allowanceschemeitems.Home_Base','allowanceschemeitems.Outstation','allowanceschemeitems.Subsequent_Home_Base','allowanceschemeitems.Subsequent_Outstation','allowanceschemeitems.Remarks')
			->orderBy('allowanceschemeitems.Day_Type','asc')
			->orderBy(db::raw('str_to_date(allowanceschemeitems.Start,"%h:%i:%s %p")'),'asc')
			->where('users.Id', '=',$me->UserId)
			->where('allowanceschemeitems.Day_Type', '=',"Public Holiday")
			->get();
			break;
		}

		$In = strtotime($Date ." ".$Time_In);

		// $Out = strtotime($Date ." ".$Time_Out);
		if (strpos(strtoupper($Time_In), 'PM') !== false && strpos(strtoupper($Time_Out), 'AM') !== false)
		{
			$day2=date('d-M-Y',strtotime($Date . "+1 days"));
			$Out = strtotime($day2 ." ".$Time_Out);
		}

		else
		{
			# code...
			$day2=$Date ." ".$Time_Out;
			$Out = strtotime($Date ." ".$Time_Out);
		}

		$interval = abs($Out - $In);
		$minutes   = round($interval / 60);
		$duration = floor($minutes/30) * 0.5;

		foreach ($schemeitems as $item)
		{

			$start= new DateTime($item->Start);
		 	$end= new DateTime($item->End);

			$startampm=(string)($start->format('A'));
			$endampm=(string)($end->format('A'));

			$starttime=date("d-M-Y H:i:s", mktime($start->format('H'), $start->format('i'), 0, $timesheetdate->format('m'), $timesheetdate->format('d'), $timesheetdate->format('Y')));
			$endtime=date("d-M-Y H:i:s", mktime($end->format('H'), $end->format('i'), 0, $timesheetdate->format('m'), $timesheetdate->format('d'), $timesheetdate->format('Y')));

			if (strcmp($startampm,"PM")==0 && strcmp($endampm,"AM")==0)
			{

				$endtime=date('d-M-Y H:i:s',strtotime($endtime . "+1 days"));
			}

			elseif (strcmp($startampm,"AM")==0 && strcmp($endampm,"AM")==0)
			{

				$starttime=date('d-M-Y H:i:s',strtotime($starttime . "+1 days"));
				$endtime=date('d-M-Y H:i:s',strtotime($endtime . "+1 days"));
			}

			$start=strtotime($starttime);
			$end=strtotime($endtime);

			echo $starttime." ".$endtime."---".$Date ." ".$Time_In." ".$day2 ." ".$Time_Out."<br>";

			if ($In<=$start && $Out>=$end)
			{

				echo "yes<br>";

				if ($Home_Base==$me->Home_Base)
				{

					$allowance=$allowance+$item->Home_Base;
				}

				else {
					$allowance=$allowance+$item->Outstation;
				}
			}
		}

		$result= DB::table('timesheets')
		->where('Id', $input["TimesheetId"])
		->update(array(
			'Allowance' =>  $allowance,
		));

		return $allowance;
	}

	// public function newtimesheet(Request $request)
	// {
	//
	// 		$me = JWTAuth::parseToken()->authenticate();
	// 		$input = $request->all();
	//
	// 		$insertid=DB::table('timesheets')->insertGetId(
	// 			['UserId' => $input["UserId"],
	// 			 'Timesheet_Name' => $input["Timesheet_Name"],
	// 			 'Date' => $input["Date"],
	// 			 'Remarks' => $input["Remarks"]
	// 		 	]
	// 		);
	//
	// 		return $insertid;
	//
	// }


	public function timeout(Request $request)
	{

		$me = JWTAuth::parseToken()->authenticate();
		$input = $request->all();

		$input["Time_Out"]=date("g:i A",strtotime($input["Time_Out"]));

		$timesheet = DB::table('timesheets')
		->where('Id', '=', $input["Id"])
		->first();

		$start="7:00 am";
		// $end="10:30 am";

		// $in=DateTime::createFromFormat('d-M-Y g:i A', $timesheet->Date ." ".$timesheet->Time_In);
		//
		$date1 = DateTime::createFromFormat('g:i A', $input["Time_Out"]);
		$date2 = DateTime::createFromFormat('g:i A', $start);
		// // $date3 = DateTime::createFromFormat('g:i A', $end);
		//
		// $diff=$date1->diff($in);
		//
		// $hours = $diff->h;
		// $hours = $hours + ($diff->days*24);

		if ($date1 >= $date2 && $timesheet->Date!=date('d-M-Y'))
		{
			DB::table('timesheets')
			->where('Id', $input["Id"])
			->update(array(
				'Remarks' => DB::raw("CONCAT(Remarks, 'Forgot to check-out[".date('d-M-Y')." ".$input["Time_Out"]."]')")
			));
		}

		else if($timesheet->Date!=date('d-M-Y'))
		{

			DB::table('timesheets')
			->where('Id', $input["Id"])
			->update(array(
				'Time_Out' =>  DB::raw('now()'),
				'Latitude_Out' =>  $input["Latitude_Out"],
				'Longitude_Out' =>  $input["Longitude_Out"]
			));

		}

		else {
			# code...

			DB::table('timesheets')
            ->where('Id', $input["Id"])
			->update(array(
				'Time_Out' =>  $input["Time_Out"],
				'Latitude_Out' =>  $input["Latitude_Out"],
				'Longitude_Out' =>  $input["Longitude_Out"]
			));
		}

		// if (strtoupper($me->Department) == 'MY_DEPARTMENT_FAB' || strtoupper($me->Department) == 'MY_DEPARTMENT_MDO') {
			$this->calculateovertime($me, $timesheet->Date);
		// }

			$datetime1 = new DateTime($timesheet->Time_In);
			$datetime2 = new DateTime($input["Time_Out"]);
			$interval = $datetime1->diff($datetime2);
			DB::table('timesheets')
			->where('Id',$input["Id"])
			->update([
				"Time_Diff" => $interval->format("%h:%i")
			]);

		return 1;
	}

	// public function gettimesheets()
	// {
	//
	// 		$me = JWTAuth::parseToken()->authenticate();
	//
	// 		$timesheets = DB::table('timesheets')
	// 		->where('UserId', '=', $me->Id)
	// 		->orderBy('timesheets.Id','desc')
	// 		->get();
	//
	// 		return json_encode($timesheets);
	//
	// }


	public function gettimesheets(Request $request)
	{

		$me = JWTAuth::parseToken()->authenticate();

		$input = $request->all();

		$timesheets = DB::table('timesheets')
		->orderBy('Date','Asc')
		->get();

		return json_encode($timesheets);
	}


	public function getoptions()
	{

		$me = JWTAuth::parseToken()->authenticate();

		$options= DB::table('options')
		->whereIn('Table', ["users","timesheets"])
		->orderBy('Table','asc')
		->orderBy('Option','asc')
		->get();

		return json_encode($options);
	}


	public function mytimesheet(Request $request)
	{

		$me = JWTAuth::parseToken()->authenticate();

		$input = $request->all();
		$d=date('d');

		$start=$input["Start_Date"];
		$end=$input["End_Date"];

		$mytimesheet = DB::table('timesheets')
		->select('timesheets.Id','timesheets.UserId','timesheets.Latitude_In','timesheets.Longitude_In','timesheets.Latitude_Out','timesheets.Longitude_Out','timesheets.Date',DB::raw('"" as Day'),'timesheets.Check_In_Type',
		'timesheets.Time_In','timesheets.Time_Out','timesheets.Leader_Member','timesheets.Next_Person','projectcodes.Project_Code','projects.Project_Name','timesheets.Site_Name','timesheets.State','timesheets.Work_Description','timesheets.Remarks','approver.Name as Approver','timesheetstatuses.Status','timesheetstatuses.Comment','timesheetstatuses.updated_at as Updated_At','files.Web_Path','timesheets.Code')
		->leftJoin( DB::raw('(select Max(Id) as maxid,TargetId from files where Type="Timesheet" Group By TargetId) as maxfile'), 'maxfile.TargetId', '=', 'timesheets.Id')
		->leftJoin('files', 'files.Id', '=', DB::raw('maxfile.`maxid` and files.`Type`="Timesheet"'))
		->leftJoin('projectcodes', 'timesheets.Project_Code_Id', '=', 'projectcodes.Id')
		->leftJoin('projects', 'timesheets.ProjectId', '=', 'projects.Id')
		->leftJoin( DB::raw('(select Max(Id) as maxid,TimesheetId from timesheetstatuses Group By TimesheetId) as max'), 'max.TimesheetId', '=', 'timesheets.Id')
		->leftJoin('timesheetstatuses', 'timesheetstatuses.Id', '=', DB::raw('max.`maxid`'))
		->leftJoin('users as approver', 'timesheetstatuses.UserId', '=', 'approver.Id')
		->where('timesheets.UserId', '=', $me->Id)
		->where(DB::raw('str_to_date(timesheets.Date,"%d-%M-%Y")'), '>=', DB::raw('str_to_date("'.$start.'","%d-%M-%Y")'))
		->where(DB::raw('str_to_date(timesheets.Date,"%d-%M-%Y")'), '<=', DB::raw('str_to_date("'.$end.'","%d-%M-%Y")'))
		->orderBy('timesheets.Id','desc')
		->get();

		$update = DB::table('updatetracking')
		->select('updatetracking.UpdateId')
		->leftJoin('timesheets', 'updatetracking.UpdateId', '=', 'timesheets.Id')
		->where('updatetracking.Type', '=', "Timesheet")
		->where('timesheets.UserId', '=', $me->UserId)
		->get();

		$arrUpdate = array();

		foreach ($update as $row) {
			# code...
			array_push($arrUpdate,$row->UpdateId);
		}

		$arrDate = array();
		$arrDate2 = array();
		$arrInsert = array();

		foreach ($mytimesheet as $timesheet) {
			# code...
			array_push($arrDate,$timesheet->Date);
		}

		$startTime = strtotime($start);
		$endTime = strtotime($end);

		// Loop between timestamps, 1 day at a time
		do {

			if (!in_array(date('d-M-Y', $startTime),$arrDate))
			{
				array_push($arrDate2,date('d-M-Y', $startTime));
			}

			$startTime = strtotime('+1 day',$startTime);
		}

		while ($startTime <= $endTime);

		foreach ($arrDate2 as $date) {
			# code...
			array_push($arrInsert,array('UserId'=>$me->UserId, 'Date'=> $date, 'updated_at'=>date('Y-m-d H:i:s')));
		}

		DB::table('timesheets')->insert($arrInsert);

		$projects = DB::table('projects')
		->select('projects.Id','projects.Project_Name','users.Name')
		->leftJoin('users','users.Id','=','projects.Project_Manager')
		->get();

		$projectcodes = DB::table('projectcodes')
		->get();

		$options= DB::table('options')
		->whereIn('Table', ["users","timesheets","tracker"])
		->orderBy('Table','asc')
		->orderBy('Option','asc')
		->get();

		return json_encode($mytimesheet);
	}


	public function getsitelist()
	{
		$me = JWTAuth::parseToken()->authenticate();
		$site = DB::select("
		SELECT DISTINCT Site_ID,Site_Name FROM tracker UNION all
		SELECT DISTINCT LRD,Site_Name FROM tracker
		WHERE
		ORDER By Site_ID
		");
		return json_encode($site);
	}


	public function populatetimesheet()
	{
		$start=date('d-M-Y', strtotime('first day of this month'));
		$end=date('d-M-Y', strtotime('last day of this month'));
		$users= DB::table('users')
		// ->where('Active','=','1')
		->orderBy('Name','asc')
		->get();
		foreach ($users as $user) {
			# code...
			$arrDate = array();
			$arrDate2 = array();
			$arrInsert = array();
			$mytimesheet = DB::table('timesheets')
			->where('timesheets.UserId', '=', $user->Id)
			->where(DB::raw('str_to_date(timesheets.Date,"%d-%M-%Y")'), '>=', DB::raw('str_to_date("'.$start.'","%d-%M-%Y")'))
			->where(DB::raw('str_to_date(timesheets.Date,"%d-%M-%Y")'), '<=', DB::raw('str_to_date("'.$end.'","%d-%M-%Y")'))
			->orderBy('timesheets.Id','desc')
			->get();

			foreach ($mytimesheet as $timesheet) {
				# code...
				array_push($arrDate,$timesheet->Date);
			}

			$startTime = strtotime($start);
			$endTime = strtotime($end);

			// Loop between timestamps, 1 day at a time
			do
			{
				if (!in_array(date('d-M-Y', $startTime),$arrDate))
				{
					array_push($arrDate2,date('d-M-Y', $startTime));
				}

				$startTime = strtotime('+1 day',$startTime);
			}

			while ($startTime <= $endTime);
			foreach ($arrDate2 as $date)
			{
				# code...
				array_push($arrInsert,array('UserId'=>$user->Id, 'Date'=> $date, 'updated_at'=>date('Y-m-d H:i:s')));
			}

			DB::table('timesheets')->insert($arrInsert);
		}
		return 1;
	}

	public function latenotification()
	{
		$current=date("d-M-Y");
		$holiday=DB::table('holidays')
		->where(DB::raw('str_to_date(holidays.Start_Date,"%d-%M-%Y")'),"<=",DB::raw('str_to_date("'.$current.'","%d-%M-%Y")'))
		->where(DB::raw('str_to_date(holidays.End_Date,"%d-%M-%Y")'),">=",DB::raw('str_to_date("'.$current.'","%d-%M-%Y")'))
		->get();

		if (count($holiday)==0)
		{
			$department= DB::table('projects')
			->where('Project_Name','like','%Department%')
			->orderBy('Project_Name','asc')
			->get();

			foreach ($department as $depart)
			{
				# code...
				$emaillist=array();
				array_push($emaillist,$depart->Project_Manager);
	 			$today=date('d-M-Y');
	 			$late = DB::table('users')
	 			->select('users.Name','users.Position','timesheets.Site_Name','timesheets.Check_In_Type','timesheets.Time_In','timesheets.Time_Out','timesheets.Remarks','leaves.Leave_Type','leavestatuses.Leave_Status')
	 			->leftJoin( DB::raw('(select Min(Id) as minid,UserId from timesheets WHERE str_to_date(timesheets.Date,"%d-%M-%Y")=str_to_date("'.$today.'","%d-%M-%Y") Group By UserId) as min'), 'min.UserId', '=', 'users.Id')
	 			->leftJoin('timesheets', 'timesheets.Id', '=', 'min.minid')
				->leftJoin('leaves','leaves.UserId','=',DB::raw('users.Id AND str_to_date(timesheets.Date,"%d-%M-%Y") Between str_to_date(leaves.Start_Date,"%d-%M-%Y") and str_to_date(leaves.End_Date,"%d-%M-%Y")'))
				->leftJoin( DB::raw('(select Max(Id) as maxid,LeaveId from leavestatuses Group By LeaveId) as maxleave'), 'maxleave.LeaveId', '=', 'leaves.Id')
				->leftJoin('leavestatuses', 'leavestatuses.Id', '=', DB::raw('maxleave.`maxid`'))
				->leftJoin( DB::raw('(select Max(Id) as maxid,TargetId from files where Type="User" Group By Type,TargetId) as maxuser'), 'maxuser.TargetId', '=', 'users.Id')
	 			->where('users.Department','=',$depart->Project_Name)
	 			->whereRaw('(str_to_date(timesheets.Time_In,"%l:%i %p")>str_to_date("9:00 AM","%l:%i %p") OR timesheets.Time_In="")')
	 			->where('users.Resignation_Date','=','')
	 			->whereNotIn('users.Id',array(855, 883,902))
	 			->orderBy('users.Name','asc')
	 			->get();

	 			if($late)
	 			{
	 				$admin= DB::table('users')
	 				->where('Admin','=','1')
					->where('Id','!=','855')
	 				->orderBy('Name','asc')
	 				->get();

	 				foreach ($admin as $user)
	 				{
	 					# code...
	 					array_push($emaillist,$user->Id);
	 				}

	 				$notify = DB::table('users')
	 				->whereIn('Id', $emaillist)
	 				->get();

	 				$emails = array();

	 				foreach ($notify as $email)
	 				{
 						array_push($emails,$email->Company_Email);
	 				}

	 				$dep=$depart->Project_Name;
	 				// Mail::send('emails.latenotification', ['late' => $late,'dep'=>$dep,'today'=>$today], function($message) use ($emails,$today,$dep)
	 				// {
 					// 	$emails = array_filter($emails);
 					// 	array_push($emails,env('MAIL_DEFAULT_RECIPIENT'));
 					// 	$message->to($emails)->subject("($dep) Late Check-In ".' ['.$today.']');
	 				// });
	 			}
	 		}
		}
	}


	function distance($lat1, $lon1, $lat2, $lon2, $unit)
	{

		$theta = $lon1 - $lon2;
		$dist = sin(deg2rad($lat1)) * sin(deg2rad($lat2)) +  cos(deg2rad($lat1)) * cos(deg2rad($lat2)) * cos(deg2rad($theta));
		$dist = acos($dist);
		$dist = rad2deg($dist);
		$miles = $dist * 60 * 1.1515;
		$unit = strtoupper($unit);

		if ($unit == "K")
		{
			return ($miles * 1.609344);
		}

		else if ($unit == "METER")
		{

			return ($miles * 1.609344)*1000;
		}

		else if ($unit == "N")
		{
			return ($miles * 0.8684);
		}

		else
		{
			return $miles;
		}
	}

	public function incentivesummary($month=null,$year=null){

		$me = JWTAuth::parseToken()->authenticate();

		if ($month==null)
		{
			$month=date('F');
		}

		if ($year==null)
		{
			$year=date('Y');
		}

		$months = array (1=>'January',2=>'February',3=>'March',4=>'April',5=>'May',6=>'June',7=>'July',8=>'August',9=>'September',10=>'October',11=>'November',12=>'December');
		$monthindex=array_search($month,$months);

		$start=date('d-M-Y', strtotime(date($year.'-'.$monthindex.'-01')));
		$end=date('t-M-Y', strtotime(date($year.'-'.$monthindex.'-01')));

		$summary = DB::table('users')
		->select('users.Id','users.StaffID','users.Name','radius.Location_Name as Site_Name','scopeofwork.Code','scopeofwork.KPI',DB::raw('"" as Incentive_Entitled'),
		DB::raw('(SELECT COUNT(distinct a.Date) FROM timesheets a WHERE a.Site_Name=timesheets.Site_Name AND a.Code=timesheets.Code AND str_to_date(a.Date,"%d-%M-%Y") Between str_to_date(radius.Start_Date,"%d-%M-%Y") and str_to_date(radius.Completion_Date,"%d-%M-%Y")) as "Visit"'),
		DB::raw('(SELECT COUNT(distinct a.Date) FROM timesheets a WHERE a.Site_Name=timesheets.Site_Name and a.UserId=timesheets.UserId AND a.Code=timesheets.Code AND str_to_date(a.Date,"%d-%M-%Y") Between str_to_date(radius.Start_Date,"%d-%M-%Y") and str_to_date(radius.Completion_Date,"%d-%M-%Y")) as "OwnVisit"'),
		DB::raw('(SELECT COUNT(distinct a.UserId,a.Date) FROM timesheets a WHERE a.Site_Name=timesheets.Site_Name AND a.Code=timesheets.Code AND str_to_date(a.Date,"%d-%M-%Y") Between str_to_date(radius.Start_Date,"%d-%M-%Y") and str_to_date(radius.Completion_Date,"%d-%M-%Y")) as "VisitCount"'),
		DB::raw('"Incentive" as Incentive'),'scopeofwork.Incentive_1','scopeofwork.Incentive_2','scopeofwork.Incentive_3','scopeofwork.Incentive_4','scopeofwork.Incentive_5')
		->leftJoin('timesheets','users.Id','=','timesheets.UserId')
		->leftJoin('radius','timesheets.Site_Name','=',DB::raw("radius.Location_Name AND timesheets.Code like CONCAT('%', radius.Code ,'%')"))
		->leftJoin('scopeofwork','timesheets.Code','like',DB::raw('CONCAT("%",scopeofwork.Code,"%")'))
		->whereRaw("MONTHNAME(str_to_date(radius.Completion_Date,'%d-%M-%Y'))='".$month."'")

		->whereRaw('timesheets.Code !=""')
		->where('users.StaffId','=',$me->StaffId)
		// ->where('OwnVisit','>','0')
		->groupBy('timesheets.UserId')
		->groupBy('timesheets.Site_Name')
		->groupBy('timesheets.Code')

		->get();

		// ->where('users.StaffId','=','MN0289')
		// ->groupBy('users.Id')
		// ->groupBy('scopeofwork.Code')
		// ->get();

		return json_encode(['me'=>$me, 'month'=>$month, 'year'=>$year,'summary'=>$summary]);
	}


	public function getSite()
	{

		$me = JWTAuth::parseToken()->authenticate();
		// $me = (new AuthController)->get_current_user($auth->Id);

		$projects = DB::table('timesheets')
		->orderBy('Id','Asc')
		->groupBy('timesheets.Site_Name')
		->get();

		return json_encode($projects);
	}

	public function getdeduction($month,$year)
	{
			$auth = JWTAuth::parseToken()->authenticate();
			$me = (new AuthController)->get_current_user($auth->Id);

			$end=date('20-M-Y', strtotime("20" . $month." ".$year));
			$start=date('21-M-Y', strtotime("21" . $month." ".$year));

			$datestring=$end. 'first day of last month';
			$dt=date_create($datestring);
			$start=$dt->format('21-M-Y'); //2011-02

			$deduction = DB::table('staffdeductions')
			->where('staffdeductions.UserId','=',$me->UserId)
			// ->where('staffdeductions.UserId','=',756)
			->whereRaw('str_to_date(staffdeductions.`Date`,"%d-%M-%Y") between str_to_date("'.$start.'","%d-%M-%Y") and str_to_date("'.$end.'","%d-%M-%Y")')
			->whereRaw('staffdeductions.Description not like "%PRE-SAVING%"')
			->orderBy('staffdeductions.Id','Asc')
			->get();

			return json_encode($deduction);

	}

	public function getincentive($month,$year)
	{
			$auth = JWTAuth::parseToken()->authenticate();
			$me = (new AuthController)->get_current_user($auth->Id);


			$start=date('01-M-Y', strtotime($month." ".$year));
			$end=date('t-M-Y', strtotime($month." ".$year));

			// $start=$end. 'first day of this month';
			// $dt=date_create($datestring);
			// $start=$dt->format('01-M-Y'); //2011-02

			$summary = DB::table('users')
			->select('users.Id','users.StaffID','users.Name','users.Category','radius.Location_Name as Site_Name','radius.Code','scopeofwork.KPI',DB::raw('"" as Incentive_Entitled'),
			DB::raw('(SELECT COUNT(distinct a.Date) FROM timesheets a WHERE a.Site_Name=timesheets.Site_Name AND a.Code=timesheets.Code AND str_to_date(a.Date,"%d-%M-%Y") Between str_to_date(radius.Start_Date,"%d-%M-%Y") and str_to_date(radius.Completion_Date,"%d-%M-%Y")) as "Total Visit"'),
			DB::raw('(SELECT COUNT(distinct a.Date) FROM timesheets a WHERE a.Site_Name=timesheets.Site_Name and a.UserId=timesheets.UserId AND a.Code=timesheets.Code AND str_to_date(a.Date,"%d-%M-%Y") Between str_to_date(radius.Start_Date,"%d-%M-%Y") and str_to_date(radius.Completion_Date,"%d-%M-%Y")) as "Number of Own Visit"'),
			DB::raw('(SELECT COUNT(distinct concat(a.UserId,"|",a.Date)) FROM timesheets a WHERE a.Site_Name=timesheets.Site_Name AND a.Code=timesheets.Code AND str_to_date(a.Date,"%d-%M-%Y") Between str_to_date(radius.Start_Date,"%d-%M-%Y") and str_to_date(radius.Completion_Date,"%d-%M-%Y")) as "Visit Count"'),
			DB::raw('"Incentive" as Incentive'),'scopeofwork.Incentive_1','scopeofwork.Incentive_2','scopeofwork.Incentive_3','scopeofwork.Incentive_4','scopeofwork.Incentive_5')
			->leftJoin('timesheets','users.Id','=','timesheets.UserId')
			->leftJoin('radius','timesheets.Site_Name','=',DB::raw("radius.Location_Name AND timesheets.Code like CONCAT('%', radius.Code ,'%')"))
			->leftJoin('scopeofwork','radius.Code','=','scopeofwork.Code')
			->whereRaw("str_to_date(radius.Completion_Date,'%d-%M-%Y') between str_to_date('".$start."','%d-%M-%Y') and str_to_date('".$end."','%d-%M-%Y')")
			->whereRaw("str_to_date(timesheets.Date,'%d-%M-%Y') Between str_to_date(radius.Start_Date,'%d-%M-%Y') and str_to_date(radius.Completion_Date,'%d-%M-%Y')")

			// ->whereRaw("str_to_date(timesheets.Date,'%d-%M-%Y') Between str_to_date(radius.Start_Date,'%d-%M-%Y') and str_to_date(radius.Completion_Date,'%d-%M-%Y')")
			->whereRaw('timesheets.Code !=""')
			->whereRaw("users.Position LIKE '%TEAM LEADER%'")
			->where('timesheets.UserId','=',$me->UserId)
			// ->where('users.Id','=',750)
			->groupBy('timesheets.UserId')
			->groupBy('timesheets.Site_Name')
			->groupBy('radius.Code')
			->get();

			foreach($summary as $row)
			{
				if($row->{'Total Visit'}>$row->{'KPI'}+3)
				{
					$row->{'Incentive'}+=$row->Incentive_5/$row->{'Visit Count'}*$row->{'Number of Own Visit'};
					$row->{'Incentive_Entitled'}=$row->Incentive_5;
				}
				elseif($row->{'Total Visit'}>$row->{'KPI'}+2)
				{
					$row->{'Incentive'}+=$row->Incentive_4/$row->{'Visit Count'}*$row->{'Number of Own Visit'};
					$row->{'Incentive_Entitled'}=$row->Incentive_4;
				}
				elseif($row->{'Total Visit'}>$row->{'KPI'}+1)
				{
					$row->{'Incentive'}+=$row->Incentive_3/$row->{'Visit Count'}*$row->{'Number of Own Visit'};
					$row->{'Incentive_Entitled'}=$row->Incentive_3;
				}
				elseif($row->{'Total Visit'}>$row->{'KPI'})
				{
					if($row->{'Visit Count'}==0)
					{
						$row->{'Incentive'}+=0;
						$row->{'Incentive_Entitled'}=$row->Incentive_2;
					}
					else {
						// code...
						$row->{'Incentive'}+=$row->Incentive_2/$row->{'Visit Count'}*$row->{'Number of Own Visit'};
						$row->{'Incentive_Entitled'}=$row->Incentive_2;
					}

				}
				else {
					// code...
					if($row->{'Visit Count'}==0)
					{
						$row->{'Incentive'}+=0;
						$row->{'Incentive_Entitled'}=$row->Incentive_1;
					}
					else {
						// code...
						$row->{'Incentive'}+=$row->Incentive_1/$row->{'Visit Count'}*$row->{'Number of Own Visit'};
						$row->{'Incentive_Entitled'}=$row->Incentive_1;
					}


				}
				$row->{'Incentive'}=number_format($row->{'Incentive'},2);
			}

			return json_encode($summary);

	}

		public function yesterdaytimesheet()
		{

			$start=date('d-M-Y', strtotime('yesterday'));
			$end=date('d-M-Y', strtotime('yesterday'));

			$approver = DB::table('users')
		        ->select('users.Id','users.Company_Email','users.Personal_Email')
		        ->where('users.Id', 562)
		        ->first();


			// exclusion
			// user with no holidayterritory id set
			// using raw query bcs laravel 5.1 doesnt have cross join
			$publicholidaysUserIds = array_pluck(DB::select('
				SELECT users.Id
				FROM users
				CROSS JOIN (
					SELECT Id FROM holidays WHERE str_to_date(\'' . $start . '\',"%d-%M-%Y")
					BETWEEN str_to_date(holidays.Start_Date,"%d-%M-%Y")
					AND str_to_date(holidays.End_Date,"%d-%M-%Y")
				) as h
				WHERE users.HolidayTerritoryId = 0
				AND (str_to_date(users.Resignation_Date,"%d-%M-%Y") > str_to_date("'.$end.'","%d-%M-%Y") OR users.Resignation_Date = \'\')
			'),'Id');

			// exclusion
			// user with holiday territory id set
			$publicholidaysUserIds2 = DB::table('users')
			->select('users.Id')
			->leftJoin('holidayterritorydays', 'holidayterritorydays.HolidayTerritoryId', '=','users.HolidayTerritoryId')
			->whereRaw('str_to_date(\'' . $start . '\',"%d-%M-%Y") Between str_to_date(holidayterritorydays.Start_Date,"%d-%M-%Y") and str_to_date(holidayterritorydays.End_Date,"%d-%M-%Y")')
			->where(function ($query) use ($end) {
				$query->whereRaw('str_to_date(users.Resignation_Date,"%d-%M-%Y") > str_to_date("'.$end.'","%d-%M-%Y")');
				$query->orWhere('users.Resignation_Date', '');
			})
			->lists('users.Id');

			// exclusion
			// we need to do this incase when there are multiple leave such as rejected and / or final approved on the same day
			$onLeaveUserIds = DB::table('users')
			->select('users.Id')
			->leftJoin('leaves','leaves.UserId','=','users.Id')
			->leftJoin( DB::raw('(select Max(Id) as maxid,LeaveId from leavestatuses Group By LeaveId) as maxleave'), 'maxleave.LeaveId', '=', 'leaves.Id')
			->leftJoin('leavestatuses', 'leavestatuses.Id', '=', DB::raw('maxleave.`maxid`'))
			->leftJoin('leave_terms', 'leave_terms.Leave_Id', '=', DB::raw("leaves.`Id` AND leave_terms.Leave_Date = '$start'"))
			->whereRaw('str_to_date(\'' . $start . '\',"%d-%M-%Y") Between str_to_date(leaves.Start_Date,"%d-%M-%Y") and str_to_date(leaves.End_Date,"%d-%M-%Y")')
			// ->where('leavestatuses.Leave_Status','Final Approved')
			->where(function ($query) {
				$query->where('leavestatuses.Leave_Status','Like','%Approved%');
				$query->orWhere('leavestatuses.Leave_Status','Like','%Pending%');
			})
			->where(function ($query) use ($end) {
				$query->whereRaw('str_to_date(users.Resignation_Date,"%d-%M-%Y") > str_to_date("'.$end.'","%d-%M-%Y")');
				$query->orWhere('users.Resignation_Date', '');
			})
			->where(function ($query) use ($approver, $start) {

				$query->where('leave_terms.Leave_Period','Full');
				$query->orWhere('leaves.Leave_Term', 'Full Day');

				// to check if system already apply for halfday
				$query->orWhere(function ($q) use ($approver) {
					$q->whereIn('leave_terms.Leave_Period',['AM','PM']);
					$q->where('leaves.Reason','like','[System Auto Apply]%');
					$q->where('leavestatuses.UserId', $approver->Id);
				});

				// to check saturday 5.5 working days users
				$query->orWhere(function ($q) use ($start) {
					$q->where('users.Working_Days','5.5');
					$q->where(DB::raw('DAYOFWEEK(str_to_date("'. $start .'","%d-%M-%Y"))'),'7');
					$q->where(function ($r) {
						$r->where('leave_terms.Leave_Period','AM');
						$r->orWhere('leave_terms.Leave_Period','like','%Half Day%');
					});
				});
			})
			->lists('users.Id');

			//exclude deactivated account
			$deactivatedIds2 = DB::table('users')
			->select('users.Id')
			->where('Active','=','0')
			->lists('users.Id');

			// combined exclusion ids
			$combinedUserIds = array_collapse([$publicholidaysUserIds, $publicholidaysUserIds2, $onLeaveUserIds, $deactivatedIds2]);

			$halfdayLeaveUsers = DB::table('users')
			->select('users.Id','leaves.Start_Date','leaves.End_Date',DB::raw('COUNT(leaves.Id) as Counter'),'leave_terms.Leave_Period','leave_terms.Leave_Date','leaves.Leave_Term')
			->leftJoin('timesheets', 'timesheets.UserId', '=', DB::raw('users.Id AND str_to_date(timesheets.Date,"%d-%M-%Y")>=str_to_date("'.$start.'","%d-%M-%Y") AND str_to_date(timesheets.Date,"%d-%M-%Y")<=str_to_date("'.$end.'","%d-%M-%Y")'))
			->leftJoin('leaves','leaves.UserId','=','users.Id')
			->leftJoin( DB::raw('(select Max(Id) as maxid,LeaveId from leavestatuses Group By LeaveId) as maxleave'), 'maxleave.LeaveId', '=', 'leaves.Id')
			->leftJoin('leavestatuses', 'leavestatuses.Id', '=', DB::raw('maxleave.`maxid`'))
			->leftJoin('leave_terms', 'leave_terms.Leave_Id', '=', DB::raw("leaves.`Id` AND leave_terms.Leave_Date = '$start'"))
			->whereRaw('str_to_date(\'' . $start . '\',"%d-%M-%Y") Between str_to_date(leaves.Start_Date,"%d-%M-%Y") and str_to_date(leaves.End_Date,"%d-%M-%Y")')
			// ->where('leavestatuses.Leave_Status','Final Approved')
			->where(function ($query) {
				$query->where('leavestatuses.Leave_Status','Like','%Approved%');
				$query->orWhere('leavestatuses.Leave_Status','Like','%Pending%');
			})
			->where(function ($query) use ($end) {
				$query->whereRaw('str_to_date(users.Resignation_Date,"%d-%M-%Y") > str_to_date("'.$end.'","%d-%M-%Y")');
				$query->orWhere('users.Resignation_Date', '');
			})
			->where(function ($query) use ($start) {

				// fetch all 6.0 working days user leaves
				$query->where('users.Working_Days','>=','6');

				// we dont want to fetch 5.5 working days users leaves on saturday
				$query->orWhere(function ($q) use ($start) {
					$q->where(DB::raw('DAYOFWEEK(str_to_date("'.$start.'","%d-%M-%Y"))'),'<>','7');
					$q->where('users.Working_Days','<','6');
				});
			})
			->where(function ($query) {
				$query->whereIn('leave_terms.Leave_Period',['AM','PM']);
				$query->orWhere('leaves.Leave_Term', 'like' ,'%Half Day%');
			})
			->where(function ($query) {
				// no timesheet record for the day
				$query->whereNull('timesheets.Id');
				// timesheet record with empty time in and time out
				$query->orWhere(function ($q) {
					$q->where('Time_In', '=', '');
					$q->where('Time_Out', '=', '');
				});
			})
			// ->having('Counter', '<=', '1')
			->groupBy('users.Id')
			->whereNotIn('users.Id', $combinedUserIds)
			->get();

			// get the ids for next exclusion, since we dont want to retrieve
			// the users on halfdays leave
			$halfdayLeaveUserIds = collect($halfdayLeaveUsers)->pluck('Id')->all();
			// dd($halfdayLeaveUsers);
			$combinedUserIds = array_collapse([$combinedUserIds, $halfdayLeaveUserIds]);


			// no time in no time out
			$users = DB::table('users')
			->select('users.Id','users.Working_Days','users.HolidayTerritoryId','users.Company_Email','users.Personal_Email')
			->leftJoin('timesheets', 'timesheets.UserId', '=', DB::raw('users.Id AND str_to_date(timesheets.Date,"%d-%M-%Y")>=str_to_date("'.$start.'","%d-%M-%Y") AND str_to_date(timesheets.Date,"%d-%M-%Y")<=str_to_date("'.$end.'","%d-%M-%Y")'))
			->where('users.Name','<>','')
			->where(function ($query) use ($end) {
				$query->whereRaw('str_to_date(users.Resignation_Date,"%d-%M-%Y") > str_to_date("'.$end.'","%d-%M-%Y")');
				$query->orWhere('users.Resignation_Date', '');
			})
			// ->where(function ($query) {
				// $query->where(function ($q) {
				// 	$q->where('Time_In', '<>', '');
				// 	$q->where('Time_Out', '=', '');
				// });
				// $query->orWhere(function ($q) {
				// 	$q->where('Time_In', '=', '');
				// 	$q->where('Time_Out', '=', '');
				// });
			// })
			->where(function ($query) {
				// no timesheet record for the day
				$query->whereNull('timesheets.Id');
				// timesheet record with empty time in and time out
				$query->orWhere(function ($q) {
					$q->where('Time_In', '=', '');
					$q->where('Time_Out', '=', '');
				});
			})
			->whereNotIn('users.Id', $combinedUserIds)
			->groupBy('users.Id')
			->get();

			$day_type = date('w', strtotime('yesterday'));
			$leaveService = new LeaveController();
			$id = null;
			$emails = array();
			$leaveIds = array();
			$leaveTerms = array();
			$leaveStatuses = array();
			$period = 'Full';
			// dd(collect($users)->whereLoose('Working_Days','0'));

			foreach($users as $user) {

				// 0 for sunday, 6 for saturday
				if ($day_type == 0 || $day_type == 6) {

				    // if working days is 5.5 or 6 on saturday
				    if ($day_type == 6 && $user->Working_Days > 5) {
			            if ($user->Working_Days >= 6) {
			                // do apply full day leave
			                // if (! $leaveService->isLeaveOutOfBalance($user->Id,'Annual Leave', 1)) {
			                // 	$leaveType = 'Annual Leave';
			                // } else {
			                	$leaveType = 'Unpaid Leave';
			                // }

			                $No_Of_Days = 1;
			                $period = 'Full';

			            } else {
			                // do apply halfday
			                // if (! $leaveService->isLeaveOutOfBalance($user->Id,'Annual Leave', 0.5)) {
			                // 	$leaveType = 'Annual Leave';
			                // } else {
			                	$leaveType = 'Unpaid Leave';
			                // }

			                $No_Of_Days = 0.5;
			                $period = 'AM';
			            }


		                $id = DB::table('leaves')->insertGetId([
		                    'UserId' => $user->Id,
		                    'Leave_Type' => $leaveType,
		                    'Start_Date' => $start,
		                    'End_Date' => $end,
		                    'ProjectId' => 0,
		                    'No_Of_Days' => $No_Of_Days,
		                    'Reason' => '[System Auto Apply] No Time In for the day ' . $start
		                ]);

		                array_push($leaveIds, $id);
		                array_push($leaveTerms, [
                    		'Leave_Date' => $start,
    		                'Leave_Period' => $period,
    		                'Leave_Id' => $id
                    	]);
                    	array_push($leaveStatuses, [
                    	    'LeaveId' => $id,
                    	    'UserId' => $approver->Id,
                    	    'Leave_Status' => 'Final Approved',
                    	]);

				    } else {
				        // saturday andsunday skip
				        continue;
				    }
				} else {

	                // if (! $leaveService->isLeaveOutOfBalance($user->Id,'Annual Leave', 1)) {
	                // 	$leaveType = 'Annual Leave';
	                // } else {
	                	$leaveType = 'Unpaid Leave';
	                // }

	                $No_Of_Days = 1;
	                $period = 'Full';

	                $id = DB::table('leaves')->insertGetId([
	                    'UserId' => $user->Id,
	                    'Leave_Type' => $leaveType,
	                    'Start_Date' => $start,
	                    'End_Date' => $end,
	                    'ProjectId' => 0,
	                    'No_Of_Days' => $No_Of_Days,
	                    'Reason' => '[System Auto Apply] No Time In for the day ' . $start
	                ]);

	                array_push($leaveIds, $id);
	                array_push($leaveTerms, [
                		'Leave_Date' => $start,
		                'Leave_Period' => $period,
		                'Leave_Id' => $id
                	]);
                	array_push($leaveStatuses, [
                	    'LeaveId' => $id,
                	    'UserId' => $approver->Id,
                	    'Leave_Status' => 'Final Approved',
                	]);

				}

			}

			// Remove users which have 2 halfday same date but on different leave
			$halfdayLeaveUsers = collect($halfdayLeaveUsers)->reject(function ($user) {
			    return $user->Counter >= 2;
			})->all();

			foreach($halfdayLeaveUsers as $user) {

				// 0 for sunday, 6 for saturday
				if ($day_type == 0) {
			        // sunday skip
			        continue;
				} else {

	                // if (! $leaveService->isLeaveOutOfBalance($user->Id,'Annual Leave', 0.5)) {
	                // 	$leaveType = 'Annual Leave';
	                // } else {
	                	$leaveType = 'Unpaid Leave';
	                // }

	                $No_Of_Days = 0.5;
                    if ($user->Leave_Period == 'AM' || strpos($user->Leave_Term, 'Morning') !== false) {
                    	$period = 'PM';
                    } else {
    	                $period = 'AM';
                    }

	                $id = DB::table('leaves')->insertGetId([
	                    'UserId' => $user->Id,
	                    'Leave_Type' => $leaveType,
	                    'Start_Date' => $start,
	                    'End_Date' => $end,
	                    'ProjectId' => 0,
	                    'No_Of_Days' => $No_Of_Days,
	                    'Reason' => '[System Auto Apply] No Time In for the day ' . $start
	                ]);

	                array_push($leaveIds, $id);
	                array_push($leaveTerms, [
                		'Leave_Date' => $start,
		                'Leave_Period' => $period,
		                'Leave_Id' => $id
                	]);
                	array_push($leaveStatuses, [
                	    'LeaveId' => $id,
                	    'UserId' => $approver->Id,
                	    'Leave_Status' => 'Final Approved',
                	]);

	            	// DB::table('leave_terms')->insert([
	            	// 	'Leave_Date' => $start,
		            //     'Leave_Period' => 'Full',
		            //     'Leave_Id' => $id
	            	// ]);

	            	// DB::table('leavestatuses')->insert([
	            	//     'LeaveId' => $id,
	            	//     'UserId' => '562',
	            	//     'Leave_Status' => 'Final Approved',
	            	// ]);

	            	// if ($user->Company_Email!="") {
	            	//     array_push($emails,$user->Company_Email);
	            	// } else {
	            	//     array_push($emails,$user->Personal_Email);
	            	// }
				}

			}

			if (count($leaveIds)) {

				// multiple insert to prevent multiple db call
	        	DB::table('leave_terms')->insert($leaveTerms);
	        	DB::table('leavestatuses')->insert($leaveStatuses);

				if ($approver->Company_Email!="") {
				    array_push($emails,$approver->Company_Email);
				} else {
				    array_push($emails,$approver->Personal_Email);
				}

		        $subscribers = DB::table('notificationtype')
		        ->leftJoin('notificationsubscriber','notificationtype.Id','=','notificationsubscriber.NotificationTypeId')
		        ->leftJoin('users','users.Id','=','notificationsubscriber.UserId')
		        ->where('notificationtype.Id','=',25)
		        ->get();


		        foreach ($subscribers as $subscriber) {
		            $NotificationSubject=$subscriber->Notification_Subject;
		            if ($subscriber->Company_Email!="")
		            {
		                array_push($emails,$subscriber->Company_Email);
		            }

		            else
		            {
		                array_push($emails,$subscriber->Personal_Email);
		            }
		        }

		        // select one of the leave's
		        // $periods = DB::table('leave_terms')->where('leave_terms.Leave_Id', $id)->get();

		        $leavedetails = DB::table('leaves')
		        ->select('leaves.Id',DB::raw('applicant.Name as Applicant'),'applicant.Company_Email','applicant.Personal_Email','leaves.Leave_Type','leaves.Start_Date','leaves.End_Date','leaves.No_of_Days','leaves.Reason','leaves.created_at as Application_Date','approver.Name as Approver','leavestatuses.Leave_Status')
		        ->leftJoin('leavestatuses', 'leaves.Id', '=', 'leavestatuses.LeaveId')
		        ->leftJoin('users as applicant', 'leaves.UserId', '=', 'applicant.Id')
		        ->leftJoin('users as approver', 'leavestatuses.UserId', '=', 'approver.Id')
		        ->whereIn('leaves.Id',$leaveIds)
		        ->get();

		        $allperiods = DB::table('leave_terms')->whereIn('leave_terms.Leave_Id', $leaveIds)->get();

		        $attachmentUrl = null;

		        ini_set('max_execution_time', 600); //600 seconds = 10 minutes

		        foreach($leavedetails as $leavedetail) {
		        	//reset email receiver
		        	$receiverEmails = array();

		        	if ($leavedetail->Company_Email!="") {
	            	    array_push($receiverEmails,$leavedetail->Company_Email);
	            	} else {
	            	    array_push($receiverEmails,$leavedetail->Personal_Email);
	            	}

            	    array_push($receiverEmails,env('MAIL_DEFAULT_RECIPIENT'));
            		$receiverEmails = array_merge($receiverEmails,$emails);

            	    $receiverEmails = array_filter($receiverEmails);
            	    $periods = collect($allperiods)->where('Leave_Id',$leavedetail->Id)->all();

            	    unset($leavedetail->Id);
			        // Mail::send('emails.leaveapplicationwithperiod', ['leavedetail' => $leavedetail,'periods'=> $periods, 'attachmentUrl' => $attachmentUrl], function($message) use ($receiverEmails,$NotificationSubject)
			        // {
			        //     $message->to($receiverEmails)->subject($NotificationSubject.' [System Auto Apply]');
			        // });
		        }

				return 1;
			}

			return 0;
		}

		public function getMIA ()
		{
			set_time_limit(3000);
            // Get date 2 days before today
            $threedaysbefore= date('d-M-Y', strtotime("today -3 days"));
            // $today= date('d-M-Y', strtotime("today"));
            // today
            $today= date('d-M-Y', strtotime("today"));

            $holidays = DB::table('holidayterritorydays')
            ->select('holidayterritorydays.Id','holidayterritorydays.Holiday','holidayterritorydays.Start_Date','holidayterritorydays.End_Date','holidayterritorydays.State','holidayterritorydays.HolidayTerritoryId')
            ->whereRaw('right(Start_Date,4)='.date('Y'))
            ->get();

            $holidaylist = json_decode(json_encode($holidays),true);

            //Last time in
            // $lasttimein = DB::table('users')
            // ->leftJoin(DB::raw('(SELECT Id,UserId,MAX(STR_TO_DATE(Date,"%d-%M-%Y")) FROM timesheets GROUP BY UserId) as max'),'max.UserId','=','users.Id')
           	// ->leftJoin('timesheets','timesheets.Id','=',DB::raw('max.Id'))
           	// ->select('timesheets.Id','timesheets.UserId','timesheets.Date','users.Working_Days')
           	// ->whereRaw('STR_TO_DATE(timesheets.date,"%d-%M-%Y") <= STR_TO_DATE("'.$twodaysbefore.'","%d-%M-%Y")')
           	// ->get();

           	$lasttimein = DB::Table('timesheets')
           	->leftJoin('users','users.Id','=','timesheets.UserId')
           	->select('timesheets.Id','timesheets.UserId',DB::raw('MAX(STR_TO_DATE(timesheets.Date,"%d-%M-%Y")) as Date'),'users.Working_Days','users.HolidayTerritoryId')
           	->whereRaw('timesheets.time_in != "" AND users.User_Type = "Staff" AND users.mia = 0 AND users.Active = 1')
           	->whereNotIn('timesheets.UserId',[562,855])
           	->groupBy('timesheets.UserId')
           	->HavingRaw('Date <= STR_TO_DATE("'.$threedaysbefore.'","%d-%M-%Y")')
			->get();
			   
            $last = json_decode(json_encode($lasttimein),true);

            foreach ($last as $key => $value) {
            	$daycount = 0;
            	$date = date('d-M-Y',strtotime("".$value['Date']."+ 1 days"));
            	$holidayarray = [];
            	for ($x=2; strtotime($date) <= strtotime($today); $x ++)
            	{
            		$isHoliday = 0;
            		foreach ($holidaylist as $k => $v) {
            			if( (strtotime($date) >= strtotime($v['Start_Date']) && strtotime($date) <= strtotime($v['End_Date']) && $value['HolidayTerritoryId'] == $v['HolidayTerritoryId'])!== false)
            			{
            				$isHoliday = 1;
            			}
            		}

            		if($value['Working_Days'] == 5)
            		{
            			$day = date("N", strtotime("".$date.""));
	            		if ( $day >= 6 )
	            		{
	            			$isHoliday = 1;
	            		}
            		}
            		else
            		{
	            		// Check if it was Sunday (if 7)
	            		$day = date("N", strtotime("".$date.""));
	            		if ( $day == 7 )
	            		{
	            			$isHoliday = 1;
	            		}
	            	}

            		$leave = DB::table('leaves')
            		->select('Id','Start_Date','End_Date','Reason')
            		->where('leaves.UserId','=',$value['UserId'])
            		->get();
            		$leavelist = json_decode(json_encode($leave),true);

            		foreach ($leavelist as $a => $b) {
            			if( strtotime($date) >= strtotime($b['Start_Date']) && strtotime($date) <= strtotime($b['End_Date']) != false)
            			{
            				if(strpos($b['Reason'],"System Auto Apply") === false)
            				{
            					$isHoliday = 1;
            				}
            			}
            		}

            		if($isHoliday == 0)
            		{
            			$daycount = $daycount + 1;
            		}
            		$date = date('d-M-Y',strtotime("".$value['Date']."+ ".$x." days"));
				}
				
            	if($daycount >= 2)
            	{
            		// Check Account Status
            		$status = DB::table('users')
            		->select('Active')
            		->where('Id','=',$value['UserId'])
            		->first();

            		if($status != null)
            		{
            			// Deactivate Account
	            		if( $status->Active )
	            		{
	            			DB::table('users')
	            			->where('Id','=',$value['UserId'])
	            			->update([
	            				'mia' => 1
	            			]);

	            			// Insert to MIA table
		            		DB::table('mia')
		            		->insert([
		            			'UserId' => $value['UserId'],
		            			'Ban_Date' => $today
		            		]);
	            		}
	            	}
            	}
            }
            return 1;
		}

		public function calculateDistance ($timesheetid)
	{

		$otw = DB::table('timesheetotw')
		->where('TimesheetId','=',$timesheetid)
		->select('Id','TimesheetId','Latitude','Longitude')
		->get();
		$totaldistance = 0.0;
		for($x=0 ; $x < Count($otw) ; $x ++)
		{

			// $theta = $lon1 - $lon2;
			// $dist = sin(deg2rad($lat1)) * sin(deg2rad($lat2)) +  cos(deg2rad($lat1)) * cos(deg2rad($lat2)) * cos(deg2rad($theta));
			if($x != (Count($otw)-1) )
			{
				$theta = $otw[$x]->Longitude - $otw[$x+1]->Longitude;
				$dist = sin(deg2rad($otw[$x]->Latitude)) * sin(deg2rad($otw[$x+1]->Latitude)) +  cos(deg2rad($otw[$x]->Latitude)) * cos(deg2rad($otw[$x+1]->Latitude)) * cos(deg2rad($theta));
			}
			else
			{
				$dist = 1;
			}
			$dist = acos($dist);
			$dist = rad2deg($dist);
			$miles = $dist * 60 * 1.1515;
			$distance =  ($miles * 1.609344)*1000;
			$distance = round($distance,2);
			$totaldistance = $totaldistance + $distance;

			DB::table('timesheetotw')
			->where('Id','=',$otw[$x]->Id)
			->update([
				'distance' => $distance
			]);
		}

		DB::table('timesheets')
		->where('Id','=',$timesheetid)
		->update([
			'total_distance' => $totaldistance
		]);

		return 1;

		// $api = file_get_contents("https://maps.googleapis.com/maps/api/distancematrix/json?units=metric&origins=".$origin."&destinations=".$destination."&key=AIzaSyCcgvUqVVCxcnMWWC6BNNL5wqpvNWMAxgQ");
		// $data = json_decode($api,true);
		// Distance
		// $data['rows'][0]['elements'][0]['distance']['text']
		// return $data;
	}

	    public function timediffoftimesheet()
    {
    	$time = DB::table('timesheets')
    	->select("Id",DB::raw("TIME_FORMAT(STR_TO_DATE(Time_In, '%h:%i %p'), '%h:%i %p') as time1"),DB::raw("TIME_FORMAT(STR_TO_DATE(Time_Out, '%h:%i %p'), '%h:%i %p') as time2"))
    	->where('Time_In','<>',"")
    	->where('Time_Out','<>',"")
    	->get();

    	foreach ($time as $key => $value) {
    		$datetime1 = new DateTime($value->time1);
			$datetime2 = new DateTime($value->time2);
			$interval = $datetime1->diff($datetime2);
			DB::table('timesheets')
			->where('Id',$value->Id)
			->update([
				"Time_Diff" => $interval->format("%h:%i")
			]);
			// dd($value->Id,$interval->format("%h:%i"));
    	}
    	return 1;
    }
}
