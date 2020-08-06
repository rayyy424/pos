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



use Dompdf\Dompdf;
use File;
use Input;
use DateTime;

class ChartController extends Controller {

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

    $chartview = DB::table('chartviews')
		->select('chartviews.Id','projects.Project_Name', 'chartviews.Chart_View_Name','chartviews.Chart_View_Type','users.Name')
		->leftJoin('users', 'users.Id', '=', 'chartviews.Created_By')
		->leftJoin('projects', 'projects.Id', '=', 'chartviews.ProjectId')
		->get();

		$projects = DB::table('projects')
		->get();

    $options= DB::table('options')
		->whereIn('Table', ["chartviews"])
		->orderBy('Table','asc')
		->orderBy('Option','asc')
		->get();

    return view('chartmanagement',['me'=>$me, 'chartview'=>$chartview, 'projects'=>$projects, 'projects'=>$projects, 'options'=>$options]);

  }

  public function chartcolumn($chartviewid,$projectid)
  {
    $me = (new CommonController)->get_current_user();



		$chartview = DB::table('chartviews')
		->select('chartviews.Id','chartviews.ProjectId','projects.Project_Name','chartviews.Chart_View_Name','chartviews.Chart_View_Type','users.Name','chartviews.created_at')
		->leftJoin('users', 'users.Id', '=', 'chartviews.Created_By')
		->leftJoin('projects', 'projects.Id', '=', 'chartviews.ProjectId')
		->where('chartviews.Id', '=',$chartviewid)
		->first();


    $chartcolumn = DB::table('chartcolumns')
		->select('chartcolumns.Id','chartcolumns.ChartViewId', 'chartcolumns.Column_Name','chartcolumns.Display_Name','chartcolumns.Condition','chartcolumns.Count_Type','chartcolumns.Series_Type','chartcolumns.Series_Color','chartcolumns.created_at')
		->leftJoin('chartviews', 'chartviews.Id', '=', 'chartcolumns.ChartViewId')
    ->where('chartcolumns.ChartViewId','=',$chartviewid)
    ->orderBy('Sequence','ASC')
		->get();

		$columns = DB::table('trackercolumn')
		->select(DB::raw('Distinct Column_Name'))
		->whereIn('TrackerTemplateId', function($query) use($projectid)
	    {
	        $query->select('Id')
	              ->from('trackertemplate')
	              ->where('ProjectId','=',$projectid);
	    })
		->orderBy('trackercolumn.Column_Name','ASC')
		->get();

		$projects = DB::table('projects')
		->get();

    return view('chartcolumn',['me'=>$me, 'chartview' =>$chartview,'chartcolumn'=>$chartcolumn, 'projects'=>$projects, 'chartviewid'=>$chartviewid,'columns'=>$columns]);

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

				$blsuccess=DB::table('chartcolumns')
				->where('Id', '=',$iditem[1])
				->update(['Sequence'=>$index]);

				$index++;
			}

			return $blsuccess;
}

	public function chartpreview($chartviewid,$projectid)
  {
    $me = (new CommonController)->get_current_user();

		$chartview = DB::table('chartviews')
		->select('chartviews.Id','chartviews.Chart_View_Name','chartviews.Chart_View_Type','users.Name','projects.Project_Name','chartviews.created_at')
		->leftJoin('users', 'users.Id', '=', 'chartviews.Created_By')
		->leftJoin('projects', 'projects.Id', '=', 'chartviews.ProjectId')
		->where('chartviews.Id', '=',$chartviewid)
		->first();

    $chartcolumn = DB::table('chartcolumns')
		->select('chartcolumns.Id','chartcolumns.ChartViewId', 'chartcolumns.Column_Name','chartcolumns.Display_Name','chartcolumns.Condition','chartcolumns.Count_Type','chartcolumns.Series_Type','chartcolumns.created_at','chartcolumns.Series_Color')
		->leftJoin('chartviews', 'chartviews.Id', '=', 'chartcolumns.ChartViewId')
    ->where('chartcolumns.ChartViewId','=',$chartviewid)
    ->orderBy('Sequence','ASC')
		->get();



		$weekno=$this->Get_Week_No($this->GetDateString());

		$chartlabel=array();
		$chartdata=array();
		$seriestype=array();
		$serieslabel=array();
		$seriescolor=array();

		if($chartview->Chart_View_Type=="Weekly")
		{

			foreach ($chartcolumn as $column) {
				# code...

				if($column->Count_Type=="Count Empty")
				{
					$chart = DB::select('SELECT
					SUM(IF(tracker.`'.$column->Column_Name.'`="",1,0)) as "Week '. ($weekno-8) .'",
					SUM(IF(tracker.`'.$column->Column_Name.'`="",1,0)) as "Week '. ($weekno-7) .'",
					SUM(IF(tracker.`'.$column->Column_Name.'`="",1,0)) as "Week '. ($weekno-6) .'",
					SUM(IF(tracker.`'.$column->Column_Name.'`="",1,0)) as "Week '. ($weekno-5) .'",
					SUM(IF(tracker.`'.$column->Column_Name.'`="",1,0)) as "Week '. ($weekno-4) .'",
					SUM(IF(tracker.`'.$column->Column_Name.'`="",1,0)) as "Week '. ($weekno-3) .'",
					SUM(IF(tracker.`'.$column->Column_Name.'`="",1,0)) as "Week '. ($weekno-2) .'",
					SUM(IF(tracker.`'.$column->Column_Name.'`="",1,0)) as "Week '. ($weekno-1) .'",
					SUM(IF(tracker.`'.$column->Column_Name.'`="",1,0)) as "Week '. ($weekno) .'"
					FROM tracker
					LEFT JOIN projects on tracker.ProjectId=projects.ID
					WHERE tracker.ProjectId='.$projectid);

				}
				elseif ($column->Count_Type=="Count Non Empty")
				{
					# code...
					$chart = DB::select('SELECT
					SUM(IF(WEEKOFYEAR(NOW())-9>=week(str_to_date(tracker.`'.$column->Column_Name.'`, "%d-%M-%Y")),1,0)) as "Week '. ($weekno-8) .'",
					SUM(IF(WEEKOFYEAR(NOW())-8>=week(str_to_date(tracker.`'.$column->Column_Name.'`, "%d-%M-%Y")),1,0)) as "Week '. ($weekno-7) .'",
					SUM(IF(WEEKOFYEAR(NOW())-7>=week(str_to_date(tracker.`'.$column->Column_Name.'`, "%d-%M-%Y")),1,0)) as "Week '. ($weekno-6) .'",
					SUM(IF(WEEKOFYEAR(NOW())-6>=week(str_to_date(tracker.`'.$column->Column_Name.'`, "%d-%M-%Y")),1,0)) as "Week '. ($weekno-5) .'",
					SUM(IF(WEEKOFYEAR(NOW())-5>=week(str_to_date(tracker.`'.$column->Column_Name.'`, "%d-%M-%Y")),1,0)) as "Week '. ($weekno-4) .'",
					SUM(IF(WEEKOFYEAR(NOW())-4>=week(str_to_date(tracker.`'.$column->Column_Name.'`, "%d-%M-%Y")),1,0)) as "Week '. ($weekno-3) .'",
					SUM(IF(WEEKOFYEAR(NOW())-3>=week(str_to_date(tracker.`'.$column->Column_Name.'`, "%d-%M-%Y")),1,0)) as "Week '. ($weekno-2) .'",
					SUM(IF(WEEKOFYEAR(NOW())-2>=week(str_to_date(tracker.`'.$column->Column_Name.'`, "%d-%M-%Y")),1,0)) as "Week '. ($weekno-1) .'",
					SUM(IF(WEEKOFYEAR(NOW())-1>=week(str_to_date(tracker.`'.$column->Column_Name.'`, "%d-%M-%Y")),1,0)) as "Week '. ($weekno) .'"
					FROM tracker
					LEFT JOIN projects on tracker.ProjectId=projects.ID
					WHERE tracker.ProjectId='.$projectid);
				}
				elseif ($column->Count_Type=="Count With Condition")
				{
					# code...
					$chart = DB::select('SELECT
					SUM(IF(tracker.`'.$column->Column_Name.'`="'.$column->Condition.'",1,0)) as "Week '. ($weekno-8) .'",
					SUM(IF(tracker.`'.$column->Column_Name.'`="'.$column->Condition.'",1,0)) as "Week '. ($weekno-7) .'",
					SUM(IF(tracker.`'.$column->Column_Name.'`="'.$column->Condition.'",1,0)) as "Week '. ($weekno-6) .'",
					SUM(IF(tracker.`'.$column->Column_Name.'`="'.$column->Condition.'",1,0)) as "Week '. ($weekno-5) .'",
					SUM(IF(tracker.`'.$column->Column_Name.'`="'.$column->Condition.'",1,0)) as "Week '. ($weekno-4) .'",
					SUM(IF(tracker.`'.$column->Column_Name.'`="'.$column->Condition.'",1,0)) as "Week '. ($weekno-3) .'",
					SUM(IF(tracker.`'.$column->Column_Name.'`="'.$column->Condition.'",1,0)) as "Week '. ($weekno-2) .'",
					SUM(IF(tracker.`'.$column->Column_Name.'`="'.$column->Condition.'",1,0)) as "Week '. ($weekno-1) .'",
					SUM(IF(tracker.`'.$column->Column_Name.'`="'.$column->Condition.'",1,0)) as "Week '. ($weekno) .'"
					FROM tracker
					LEFT JOIN projects on tracker.ProjectId=projects.ID
					WHERE tracker.ProjectId='.$projectid);
				}
				elseif ($column->Count_Type=="Sum")
				{
					# code...
					$chart = DB::select('SELECT
					SUM(IF(tracker.`'.$column->Column_Name.'`="'.$column->Condition.'",1,0)) as "Week '. ($weekno-8) .'",
					SUM(IF(tracker.`'.$column->Column_Name.'`="'.$column->Condition.'",1,0)) as "Week '. ($weekno-7) .'",
					SUM(IF(tracker.`'.$column->Column_Name.'`="'.$column->Condition.'",1,0)) as "Week '. ($weekno-6) .'",
					SUM(IF(tracker.`'.$column->Column_Name.'`="'.$column->Condition.'",1,0)) as "Week '. ($weekno-5) .'",
					SUM(IF(tracker.`'.$column->Column_Name.'`="'.$column->Condition.'",1,0)) as "Week '. ($weekno-4) .'",
					SUM(IF(tracker.`'.$column->Column_Name.'`="'.$column->Condition.'",1,0)) as "Week '. ($weekno-3) .'",
					SUM(IF(tracker.`'.$column->Column_Name.'`="'.$column->Condition.'",1,0)) as "Week '. ($weekno-2) .'",
					SUM(IF(tracker.`'.$column->Column_Name.'`="'.$column->Condition.'",1,0)) as "Week '. ($weekno-1) .'",
					SUM(IF(tracker.`'.$column->Column_Name.'`="'.$column->Condition.'",1,0)) as "Week '. ($weekno) .'"
					FROM tracker
					LEFT JOIN projects on tracker.ProjectId=projects.ID
					WHERE tracker.ProjectId='.$projectid);
				}

				$label=array();
				$data=array();

				$index=0;
				$chartindex=0;

				foreach($chart as $key => $row){

					foreach ($row as $key2 => $value2) {
						# code...
							array_push($label,$key2);
							array_push($data,$value2);

					}
				}

				$label= implode(',', $label);
				$data= implode(',', $data);

				array_push($chartdata,$data);
				array_push($seriestype,$column->Series_Type);
				array_push($serieslabel,$column->Column_Name);
				array_push($seriescolor,$column->Series_Color);
			}

		}
		else if($chartview->Chart_View_Type=="Total")
		{


			$columnquery="";

			foreach ($chartcolumn as $column) {
				# code...

				if($column->Count_Type=="Count Empty")
				{
					$columnquery.='SUM(IF(tracker.`'.$column->Column_Name.'`="",1,0)) as "'.$column->Display_Name.'",';

				}
				elseif ($column->Count_Type=="Count Non Empty")
				{
					# code...
					$columnquery.='SUM(IF(tracker.`'.$column->Column_Name.'`<>"",1,0)) as "'.$column->Display_Name.'",';
				}
				elseif ($column->Count_Type=="Count With Condition")
				{
					# code...
					$columnquery.='SUM(IF(tracker.`'.$column->Column_Name.'`="'.$column->Condition.'",1,0)) as "'.$column->Display_Name.'",';
				}
				elseif ($column->Count_Type=="Sum")
				{
					# code...
					$columnquery.='SUM(tracker.`'.$column->Column_Name.'`) as "'.$column->Display_Name.'",';
				}

			}

			$columnquery=substr($columnquery,0,strlen($columnquery)-1);

			$chart = DB::select('SELECT
			'.$columnquery.'
			FROM tracker
			LEFT JOIN projects on tracker.ProjectId=projects.ID
			WHERE tracker.ProjectId='.$projectid);

			$label=array();
			$data=array();

			$index=0;
			$chartindex=0;

			foreach($chart as $key => $row){

				foreach ($row as $key2 => $value2) {
					# code...
						array_push($label,$key2);
						array_push($data,$value2);

				}
			}

			$label= implode(',', $label);
			$data= implode(',', $data);

			array_push($chartdata,$data);
			array_push($seriestype,$column->Series_Type);
			array_push($serieslabel,$chartview->Chart_View_Name);
			array_push($seriescolor,'#2ecc71');

		}


    return view('chartpreview',['me'=>$me, 'chartcolumn'=>$chartcolumn,'chartview'=>$chartview,'chartlabel'=>$label,'chartdata'=>$chartdata,'serieslabel'=>$serieslabel,'seriestype'=>$seriestype,'seriescolor'=>$seriescolor]);

  }

	public function customizereport($projectid,$region=null)
  {
    $me = (new CommonController)->get_current_user();

		$chartview = DB::table('chartviews')
		->select('chartviews.Id','chartviews.Chart_View_Name','chartviews.Chart_View_Type','users.Name','projects.Project_Name','chartviews.created_at')
		->leftJoin('users', 'users.Id', '=', 'chartviews.Created_By')
		->leftJoin('projects', 'projects.Id', '=', 'chartviews.ProjectId')
		->where('chartviews.ProjectId', '=',$projectid)
		->get();

		$project= DB::table('projects')
		->where('Id', '=',$projectid)
		->first();

		$regions=DB::table('tracker')
		->select(DB::raw('DISTINCT Region'))
		->where('ProjectId', '=',$projectid)
		->where('Region', '<>','')
		->get();

		if($region!="null" && $region!=null )
		{
			$subcondition=' AND tracker.Region="'.$region.'"';
		}
		else {
			$subcondition='';
		}


		$chartarr=array();

		if($chartview)
		{
			foreach ($chartview as $chartinfo) {
				# code...

				$chartcolumn = DB::table('chartcolumns')
				->select('chartcolumns.Id','chartcolumns.ChartViewId', 'chartcolumns.Column_Name','chartcolumns.Display_Name','chartcolumns.Condition','chartcolumns.Count_Type','chartcolumns.Series_Type','chartcolumns.created_at', 'chartcolumns.Series_Color')
				->leftJoin('chartviews', 'chartviews.Id', '=', 'chartcolumns.ChartViewId')
		    ->where('chartcolumns.ChartViewId','=',$chartinfo->Id)
	      ->orderBy('Sequence','ASC')
				->get();

				$weekno=$this->Get_Week_No($this->GetDateString());

				$chartobject=array();

				$chartlabel=array();
				$chartdata=array();
				$seriestype=array();
				$serieslabel=array();
	      $seriescolor=array();

				if($chartinfo->Chart_View_Type=="Weekly")
				{

					foreach ($chartcolumn as $column) {
						# code...

						if($column->Count_Type=="Count Empty")
						{
							$chart = DB::select('SELECT
							SUM(IF(tracker.`'.$column->Column_Name.'`="",1,0)) as "Week '. ($weekno-8) .'",
							SUM(IF(tracker.`'.$column->Column_Name.'`="",1,0)) as "Week '. ($weekno-7) .'",
							SUM(IF(tracker.`'.$column->Column_Name.'`="",1,0)) as "Week '. ($weekno-6) .'",
							SUM(IF(tracker.`'.$column->Column_Name.'`="",1,0)) as "Week '. ($weekno-5) .'",
							SUM(IF(tracker.`'.$column->Column_Name.'`="",1,0)) as "Week '. ($weekno-4) .'",
							SUM(IF(tracker.`'.$column->Column_Name.'`="",1,0)) as "Week '. ($weekno-3) .'",
							SUM(IF(tracker.`'.$column->Column_Name.'`="",1,0)) as "Week '. ($weekno-2) .'",
							SUM(IF(tracker.`'.$column->Column_Name.'`="",1,0)) as "Week '. ($weekno-1) .'",
							SUM(IF(tracker.`'.$column->Column_Name.'`="",1,0)) as "Week '. ($weekno) .'"
							FROM tracker
							LEFT JOIN projects on tracker.ProjectId=projects.ID
							WHERE tracker.ProjectId='.$projectid.$subcondition);

						}
						elseif ($column->Count_Type=="Count Non Empty")
						{
							# code...
							$chart = DB::select('SELECT
							SUM(IF(WEEKOFYEAR(NOW())-9>=week(str_to_date(tracker.`'.$column->Column_Name.'`, "%d-%M-%Y")),1,0)) as "Week '. ($weekno-8) .'",
							SUM(IF(WEEKOFYEAR(NOW())-8>=week(str_to_date(tracker.`'.$column->Column_Name.'`, "%d-%M-%Y")),1,0)) as "Week '. ($weekno-7) .'",
							SUM(IF(WEEKOFYEAR(NOW())-7>=week(str_to_date(tracker.`'.$column->Column_Name.'`, "%d-%M-%Y")),1,0)) as "Week '. ($weekno-6) .'",
							SUM(IF(WEEKOFYEAR(NOW())-6>=week(str_to_date(tracker.`'.$column->Column_Name.'`, "%d-%M-%Y")),1,0)) as "Week '. ($weekno-5) .'",
							SUM(IF(WEEKOFYEAR(NOW())-5>=week(str_to_date(tracker.`'.$column->Column_Name.'`, "%d-%M-%Y")),1,0)) as "Week '. ($weekno-4) .'",
							SUM(IF(WEEKOFYEAR(NOW())-4>=week(str_to_date(tracker.`'.$column->Column_Name.'`, "%d-%M-%Y")),1,0)) as "Week '. ($weekno-3) .'",
							SUM(IF(WEEKOFYEAR(NOW())-3>=week(str_to_date(tracker.`'.$column->Column_Name.'`, "%d-%M-%Y")),1,0)) as "Week '. ($weekno-2) .'",
							SUM(IF(WEEKOFYEAR(NOW())-2>=week(str_to_date(tracker.`'.$column->Column_Name.'`, "%d-%M-%Y")),1,0)) as "Week '. ($weekno-1) .'",
							SUM(IF(WEEKOFYEAR(NOW())-1>=week(str_to_date(tracker.`'.$column->Column_Name.'`, "%d-%M-%Y")),1,0)) as "Week '. ($weekno) .'"
							FROM tracker
							LEFT JOIN projects on tracker.ProjectId=projects.ID
							WHERE tracker.ProjectId='.$projectid.$subcondition);
						}
						elseif ($column->Count_Type=="Count With Condition")
						{
							# code...
							$chart = DB::select('SELECT
							SUM(IF(tracker.`'.$column->Column_Name.'`="'.$column->Condition.'",1,0)) as "Week '. ($weekno-8) .'",
							SUM(IF(tracker.`'.$column->Column_Name.'`="'.$column->Condition.'",1,0)) as "Week '. ($weekno-7) .'",
							SUM(IF(tracker.`'.$column->Column_Name.'`="'.$column->Condition.'",1,0)) as "Week '. ($weekno-6) .'",
							SUM(IF(tracker.`'.$column->Column_Name.'`="'.$column->Condition.'",1,0)) as "Week '. ($weekno-5) .'",
							SUM(IF(tracker.`'.$column->Column_Name.'`="'.$column->Condition.'",1,0)) as "Week '. ($weekno-4) .'",
							SUM(IF(tracker.`'.$column->Column_Name.'`="'.$column->Condition.'",1,0)) as "Week '. ($weekno-3) .'",
							SUM(IF(tracker.`'.$column->Column_Name.'`="'.$column->Condition.'",1,0)) as "Week '. ($weekno-2) .'",
							SUM(IF(tracker.`'.$column->Column_Name.'`="'.$column->Condition.'",1,0)) as "Week '. ($weekno-1) .'",
							SUM(IF(tracker.`'.$column->Column_Name.'`="'.$column->Condition.'",1,0)) as "Week '. ($weekno) .'"
							FROM tracker
							LEFT JOIN projects on tracker.ProjectId=projects.ID
							WHERE tracker.ProjectId='.$projectid.$subcondition);
						}

						$label=array();
						$data=array();

						$index=0;
						$chartindex=0;

						foreach($chart as $key => $row){

							foreach ($row as $key2 => $value2) {
								# code...
									array_push($label,$key2);
									array_push($data,$value2);

							}
						}

						$label= implode(',', $label);
						$data= implode(',', $data);

						array_push($chartdata,$data);
						array_push($seriestype,$column->Series_Type);
						array_push($serieslabel,$column->Column_Name);
	          array_push($seriescolor,$column->Series_Color);

					}

					array_push($chartobject,$label);
					array_push($chartobject,$chartdata);
					array_push($chartobject,$seriestype);
	        array_push($chartobject,$serieslabel);
					array_push($chartobject,$seriescolor);


				}
				elseif($chartinfo->Chart_View_Type=="Total")
				{
					$columnquery="";

					foreach ($chartcolumn as $column) {
						# code...

						if($column->Count_Type=="Count Empty")
						{
							$columnquery.='SUM(IF(tracker.`'.$column->Column_Name.'`="",1,0)) as "'.$column->Display_Name.'",';

						}
						elseif ($column->Count_Type=="Count Non Empty")
						{
							# code...
							$columnquery.='SUM(IF(tracker.`'.$column->Column_Name.'`<>"",1,0)) as "'.$column->Display_Name.'",';
						}
						elseif ($column->Count_Type=="Count With Condition")
						{
							# code...
							$columnquery.='SUM(IF(tracker.`'.$column->Column_Name.'`="'.$column->Condition.'",1,0)) as "'.$column->Display_Name.'",';
						}
						elseif ($column->Count_Type=="Sum")
						{
							# code...
							$columnquery.='SUM(tracker.`'.$column->Column_Name.'`) as "'.$column->Display_Name.'",';
						}

					}

					$columnquery=substr($columnquery,0,strlen($columnquery)-1);

					$chart = DB::select('SELECT
					'.$columnquery.'
					FROM tracker
					LEFT JOIN projects on tracker.ProjectId=projects.ID
					WHERE tracker.ProjectId='.$projectid.$subcondition);

					$label=array();
					$data=array();

					$index=0;
					$chartindex=0;

					foreach($chart as $key => $row){

						foreach ($row as $key2 => $value2) {
							# code...
								array_push($label,$key2);
								array_push($data,$value2);

						}
					}

					$label= implode(',', $label);
					$data= implode(',', $data);

					array_push($chartdata,$data);
					array_push($seriestype,$column->Series_Type);
					array_push($serieslabel,$chartinfo->Chart_View_Name);
	        array_push($seriescolor,'#2ecc71');

					array_push($chartobject,$label);
					array_push($chartobject,$chartdata);
					array_push($chartobject,$seriestype);
					array_push($chartobject,$serieslabel);
	        array_push($chartobject,$seriescolor);


				}

				if($chartobject)
				{
					array_push($chartarr,$chartobject);
				}

			}

		}
		else {
			$chartcolumn=null;
		}

    //dd($chartarr);

		$agingrules = DB::table('agings')
		->select('agings.Id','agings.ProjectId', 'agings.Active','projects.Project_Name','agings.Title','agings.Type','agings.Start_Date','agings.End_Date','agings.Threshold','agings.Recurring_Frequency','agings.Frequency_Unit','creator.Name as Creator','users.Name as Subscriber')
		->leftjoin('agingsubscribers','agingsubscribers.AgingId','=','agings.Id')
		->leftJoin('projects', 'projects.Id', '=', 'agings.ProjectId')
		->leftJoin('users as creator', 'creator.Id', '=', 'agings.UserId')
		->leftjoin('users','users.Id','=','agingsubscribers.UserId')
		->where('ProjectId', '=',$projectid)
		->get();

		//dd($agingrules);
    return view('customizereport',['me'=>$me,'project'=>$project,'projectid'=>$projectid,'chartview'=>$chartview,'chartarr'=>$chartarr, 'chartcolumn'=>$chartcolumn, 'agingrules'=>$agingrules,'regions'=>$regions,'region'=>$region]);

  }

	public function dashboard($projectid,$region=null,$year=null,$month=null)
  {
		$me = (new CommonController)->get_current_user();

		$project= DB::table('projects')
		->where('Id', '=',$projectid)
		->first();

		$regions=DB::table('tracker')
		->select(DB::raw('DISTINCT Region'))
		->where('ProjectId', '=',$projectid)
		->where('Region', '<>','')
		->get();

		// $scopes=DB::table('tracker')
		// ->select(DB::raw('DISTINCT Region'))
		// ->where('ProjectId', '=',$projectid)
		// ->where('Region', '<>','')
		// ->get();

		$filter='1';
		$filter2=' AND 1';

		if($region!="null" && $region!=null)
		{
			$filter=' tracker.Region="'.$region.'"';
			$filter2=' AND tracker.Region="'.$region.'"';
		}

		$total=DB::table('tracker')
		->select(DB::raw('SUM(PO_Amount) as Total_PO_Amount'),DB::raw('SUM(IF(PO_Status not like "%Pending%", PO_Amount, 0)) AS PO_Received'),DB::raw('SUM(IF(PO_Status like "%Pending%", PO_Amount, 0)) AS Pending_PO'))
		->where('tracker.ProjectId', '=',$projectid)
		->whereRaw($filter)
		->first();

		$totalinvoiced=DB::table('tracker')
		->select(DB::raw('SUM(Invoiced_Amount) as Total_Invoiced_Amount'),DB::raw('SUM(IF(Invoiced_Amount="", PO_Amount, 0)) AS Pending_Invoice'),DB::raw('SUM(IF(Task_End_Date!="" AND Invoiced_Amount="", PO_Amount, 0)) AS JDNI'),DB::raw('SUM(IF((Task_Start_Date!="" AND Task_End_Date=""), PO_Amount, 0)) AS WIP'),DB::raw('SUM(IF(Task_Start_Date="", PO_Amount, 0)) AS Not_Yet_Start'))
		->where('tracker.ProjectId', '=',$projectid)
		->whereRaw($filter)
		->first();

		$totalcost=DB::table('tracker')
		->select(DB::raw('SUM(PO1_Amount+PO2_Amount+PO3_Amount+PO4_Amount+PO5_Amount+PO6_Amount+PO7_Amount+PO8_Amount) as Total_Cost'))
		->where('tracker.ProjectId', '=',$projectid)
		->whereRaw($filter)
		->first();

		$totalsites=DB::table('tracker')
		->select(DB::raw('COUNT(*) AS Total'),DB::raw('SUM(IF(Task_End_Date != "", 1, 0)) AS Completed'),DB::raw('SUM(IF(Task_Start_Date != "" AND Task_End_Date = "", 1, 0)) AS On_going'),DB::raw('SUM(IF(Task_Start_Date = "", 1, 0)) AS Not_Yet_Start'),DB::raw('SUM(IF(Work_Status = "Cancel", 1, 0)) AS Cancelled'))
		->where('tracker.ProjectId', '=',$projectid)
		->whereRaw($filter)
		->first();

		if($year==null)
		{
			$year=date("Y");
		}

		$weekno=$this->Get_Week_No($this->GetDateString());
		$query="";
		$query2="";

		for ($i=0; $i < $weekno; $i++) {
			# code...
			//word count
			$query.="SELECT 'Week ". ($i+1) ."' As Week_Number,
			SUM(IF(Week(str_to_date(`Task_Start_Date`, '%d-%M-%Y'))=".($i).", 1, 0)) AS Start,
			SUM(IF(Week(str_to_date(`Task_End_Date`, '%d-%M-%Y'))=".($i).", 1, 0)) AS Completed
			FROM tracker
			Where tracker.projectid=".$projectid."

			AND Year(str_to_date(`Task_Start_Date`, '%d-%M-%Y'))=".$year. $filter2. " UNION ALL ";

			//forecast,po closed, invoice amount
			$query2.="SELECT 'Week ". ($i+1) ."' As Week_Number,
			SUM(IF(Week(str_to_date(`Target_Completion_Date`, '%d-%M-%Y'))=".($i).", PO_Amount, 0)) AS Forecast,
			SUM(IF(Week(str_to_date(`Task_End_Date`, '%d-%M-%Y'))=".($i).", PO_Amount, 0)) AS PO_Closed,
			SUM(IF(Week(str_to_date(`Invoice_Date`, '%d-%M-%Y'))=".($i).", Invoiced_Amount, 0)) AS Invoiced
			FROM tracker
			Where tracker.projectid=".$projectid."
			" . $filter2." UNION ALL ";

		}

		$query=substr($query,0,strlen($query)-11);
		$query2=substr($query2,0,strlen($query2)-11);

		$work = DB::select($query);

		$invoice = DB::select($query2);

		// $work=DB::table('tracker')
		// ->select(DB::raw('Week(str_to_date(`Task_Start_Date`, "%d-%M-%Y"),3) AS Week_Number'),DB::raw('SUM(IF(Task_Start_Date!="", 1, 0)) AS Start'),DB::raw('SUM(IF(Task_End_Date != "", 1, 0)) AS Closed'))
		// ->where('tracker.ProjectId', '=',$projectid)
		// ->groupBy(DB::raw('Week(str_to_date(`Task_Start_Date`, "%d-%M-%Y"),1)'))
		// ->get();

		$forecast=DB::table('tracker')
		->select(DB::raw('right(`Invoice_Forecast_Date`,8) as Month'),DB::raw('SUM(PO_Amount) AS Total'))
		->where('tracker.ProjectId', '=',$projectid)
		->where('tracker.Invoice_Forecast_Date', '!=','')
		->where('tracker.Invoice_Forecast_Date', '!=','-')
		->whereRaw($filter)
		->groupBy(DB::raw('right(`Invoice_Forecast_Date`,8)'))
		->orderBy(DB::raw('str_to_date(`Invoice_Forecast_Date`, "%d-%M-%Y")'),'DESC')
		->limit(12)
		->get();

		$invoiced=DB::table('tracker')
		->select(DB::raw('right(`Invoice_Date`,8) as Month'),DB::raw('SUM(Invoiced_Amount) AS Total_Invoiced'))
		->where('tracker.ProjectId', '=',$projectid)
		->where('tracker.Invoice_Date', '!=','')
		->where('tracker.Invoice_Date', '!=','-')
		->whereRaw($filter)
		->groupBy(DB::raw('right(`Invoice_Date`,8)'))
		->orderBy(DB::raw('str_to_date(`Invoice_Date`, "%d-%M-%Y")'),'DESC')
		->limit(12)
		->get();

		// $sales=DB::table('tracker')
		// ->select(DB::raw('right(`Invoice_Date`,8) as Month'),DB::raw('SUM(Invoiced_Amount) AS Total'))
		// ->where('tracker.ProjectId', '=',$projectid)
		// ->where('tracker.Invoice_Date', '!=','')
		// ->groupBy(DB::raw('right(`Invoice_Date`,8)'))
		// ->orderBy(DB::raw('str_to_date(`Invoice_Date`, "%d-%M-%Y")'),'DESC')
		// ->limit(12)
		// ->get();

		return view('dashboard',['me'=>$me,'total'=>$total,'totalinvoiced'=>$totalinvoiced,'totalcost'=>$totalcost,'totalsites'=>$totalsites,'work'=>$work,'invoice'=>$invoice,'invoiced'=>$invoiced,'project'=>$project,'projectid'=>$projectid,'regions'=>$regions,'region'=>$region,'year'=>$year,'month'=>$month,'forecast'=>$forecast]);

	}

	public function projectdashboard($projectid,$region=null)
  {
    $me = (new CommonController)->get_current_user();

		$chartview = DB::table('chartviews')
		->select('chartviews.Id','chartviews.Chart_View_Name','chartviews.Chart_View_Type','users.Name','projects.Project_Name','chartviews.created_at')
		->leftJoin('users', 'users.Id', '=', 'chartviews.Created_By')
		->leftJoin('projects', 'projects.Id', '=', 'chartviews.ProjectId')
		->where('chartviews.ProjectId', '=',$projectid)
		->get();

		$project= DB::table('projects')
		->where('Id', '=',$projectid)
		->first();

		$regions=DB::table('tracker')
		->select(DB::raw('DISTINCT Region'))
		->where('ProjectId', '=',$projectid)
		->where('Region', '<>','')
		->get();

		if($region!="null" && $region!=null )
		{
			$subcondition=' AND tracker.Region="'.$region.'"';
		}
		else {
			$subcondition='';
		}


		$chartarr=array();

		if($chartview)
		{
			foreach ($chartview as $chartinfo) {
				# code...

				$chartcolumn = DB::table('chartcolumns')
				->select('chartcolumns.Id','chartcolumns.ChartViewId', 'chartcolumns.Column_Name','chartcolumns.Display_Name','chartcolumns.Condition','chartcolumns.Count_Type','chartcolumns.Series_Type','chartcolumns.created_at', 'chartcolumns.Series_Color')
				->leftJoin('chartviews', 'chartviews.Id', '=', 'chartcolumns.ChartViewId')
		    ->where('chartcolumns.ChartViewId','=',$chartinfo->Id)
	      ->orderBy('Sequence','ASC')
				->get();

				$weekno=$this->Get_Week_No($this->GetDateString());

				$chartobject=array();

				$chartlabel=array();
				$chartdata=array();
				$seriestype=array();
				$serieslabel=array();
	      $seriescolor=array();

				if($chartinfo->Chart_View_Type=="Weekly")
				{

					foreach ($chartcolumn as $column) {
						# code...

						if($column->Count_Type=="Count Empty")
						{
							$chart = DB::select('SELECT
							SUM(IF(tracker.`'.$column->Column_Name.'`="",1,0)) as "Week '. ($weekno-8) .'",
							SUM(IF(tracker.`'.$column->Column_Name.'`="",1,0)) as "Week '. ($weekno-7) .'",
							SUM(IF(tracker.`'.$column->Column_Name.'`="",1,0)) as "Week '. ($weekno-6) .'",
							SUM(IF(tracker.`'.$column->Column_Name.'`="",1,0)) as "Week '. ($weekno-5) .'",
							SUM(IF(tracker.`'.$column->Column_Name.'`="",1,0)) as "Week '. ($weekno-4) .'",
							SUM(IF(tracker.`'.$column->Column_Name.'`="",1,0)) as "Week '. ($weekno-3) .'",
							SUM(IF(tracker.`'.$column->Column_Name.'`="",1,0)) as "Week '. ($weekno-2) .'",
							SUM(IF(tracker.`'.$column->Column_Name.'`="",1,0)) as "Week '. ($weekno-1) .'",
							SUM(IF(tracker.`'.$column->Column_Name.'`="",1,0)) as "Week '. ($weekno) .'"
							FROM tracker
							LEFT JOIN projects on tracker.ProjectId=projects.ID
							WHERE tracker.ProjectId='.$projectid.$subcondition);

						}
						elseif ($column->Count_Type=="Count Non Empty")
						{
							# code...
							$chart = DB::select('SELECT
							SUM(IF(WEEKOFYEAR(NOW())-9>=week(str_to_date(tracker.`'.$column->Column_Name.'`, "%d-%M-%Y")),1,0)) as "Week '. ($weekno-8) .'",
							SUM(IF(WEEKOFYEAR(NOW())-8>=week(str_to_date(tracker.`'.$column->Column_Name.'`, "%d-%M-%Y")),1,0)) as "Week '. ($weekno-7) .'",
							SUM(IF(WEEKOFYEAR(NOW())-7>=week(str_to_date(tracker.`'.$column->Column_Name.'`, "%d-%M-%Y")),1,0)) as "Week '. ($weekno-6) .'",
							SUM(IF(WEEKOFYEAR(NOW())-6>=week(str_to_date(tracker.`'.$column->Column_Name.'`, "%d-%M-%Y")),1,0)) as "Week '. ($weekno-5) .'",
							SUM(IF(WEEKOFYEAR(NOW())-5>=week(str_to_date(tracker.`'.$column->Column_Name.'`, "%d-%M-%Y")),1,0)) as "Week '. ($weekno-4) .'",
							SUM(IF(WEEKOFYEAR(NOW())-4>=week(str_to_date(tracker.`'.$column->Column_Name.'`, "%d-%M-%Y")),1,0)) as "Week '. ($weekno-3) .'",
							SUM(IF(WEEKOFYEAR(NOW())-3>=week(str_to_date(tracker.`'.$column->Column_Name.'`, "%d-%M-%Y")),1,0)) as "Week '. ($weekno-2) .'",
							SUM(IF(WEEKOFYEAR(NOW())-2>=week(str_to_date(tracker.`'.$column->Column_Name.'`, "%d-%M-%Y")),1,0)) as "Week '. ($weekno-1) .'",
							SUM(IF(WEEKOFYEAR(NOW())-1>=week(str_to_date(tracker.`'.$column->Column_Name.'`, "%d-%M-%Y")),1,0)) as "Week '. ($weekno) .'"
							FROM tracker
							LEFT JOIN projects on tracker.ProjectId=projects.ID
							WHERE tracker.ProjectId='.$projectid.$subcondition);
						}
						elseif ($column->Count_Type=="Count With Condition")
						{
							# code...
							$chart = DB::select('SELECT
							SUM(IF(tracker.`'.$column->Column_Name.'`="'.$column->Condition.'",1,0)) as "Week '. ($weekno-8) .'",
							SUM(IF(tracker.`'.$column->Column_Name.'`="'.$column->Condition.'",1,0)) as "Week '. ($weekno-7) .'",
							SUM(IF(tracker.`'.$column->Column_Name.'`="'.$column->Condition.'",1,0)) as "Week '. ($weekno-6) .'",
							SUM(IF(tracker.`'.$column->Column_Name.'`="'.$column->Condition.'",1,0)) as "Week '. ($weekno-5) .'",
							SUM(IF(tracker.`'.$column->Column_Name.'`="'.$column->Condition.'",1,0)) as "Week '. ($weekno-4) .'",
							SUM(IF(tracker.`'.$column->Column_Name.'`="'.$column->Condition.'",1,0)) as "Week '. ($weekno-3) .'",
							SUM(IF(tracker.`'.$column->Column_Name.'`="'.$column->Condition.'",1,0)) as "Week '. ($weekno-2) .'",
							SUM(IF(tracker.`'.$column->Column_Name.'`="'.$column->Condition.'",1,0)) as "Week '. ($weekno-1) .'",
							SUM(IF(tracker.`'.$column->Column_Name.'`="'.$column->Condition.'",1,0)) as "Week '. ($weekno) .'"
							FROM tracker
							LEFT JOIN projects on tracker.ProjectId=projects.ID
							WHERE tracker.ProjectId='.$projectid.$subcondition);
						}

						$label=array();
						$data=array();

						$index=0;
						$chartindex=0;

						foreach($chart as $key => $row){

							foreach ($row as $key2 => $value2) {
								# code...
									array_push($label,$key2);
									array_push($data,$value2);

							}
						}

						$label= implode(',', $label);
						$data= implode(',', $data);

						array_push($chartdata,$data);
						array_push($seriestype,$column->Series_Type);
						array_push($serieslabel,$column->Column_Name);
	          array_push($seriescolor,$column->Series_Color);

					}

					array_push($chartobject,$label);
					array_push($chartobject,$chartdata);
					array_push($chartobject,$seriestype);
	        array_push($chartobject,$serieslabel);
					array_push($chartobject,$seriescolor);


				}
				elseif($chartinfo->Chart_View_Type=="Total")
				{
					$columnquery="";

					foreach ($chartcolumn as $column) {
						# code...

						if($column->Count_Type=="Count Empty")
						{
							$columnquery.='SUM(IF(tracker.`'.$column->Column_Name.'`="",1,0)) as "'.$column->Display_Name.'",';

						}
						elseif ($column->Count_Type=="Count Non Empty")
						{
							# code...
							$columnquery.='SUM(IF(tracker.`'.$column->Column_Name.'`<>"",1,0)) as "'.$column->Display_Name.'",';
						}
						elseif ($column->Count_Type=="Count With Condition")
						{
							# code...
							$columnquery.='SUM(IF(tracker.`'.$column->Column_Name.'`="'.$column->Condition.'",1,0)) as "'.$column->Display_Name.'",';
						}

					}

					$columnquery=substr($columnquery,0,strlen($columnquery)-1);

					$chart = DB::select('SELECT
					'.$columnquery.'
					FROM tracker
					LEFT JOIN projects on tracker.ProjectId=projects.ID
					WHERE tracker.ProjectId='.$projectid.$subcondition);

					$label=array();
					$data=array();

					$index=0;
					$chartindex=0;

					foreach($chart as $key => $row){

						foreach ($row as $key2 => $value2) {
							# code...
								array_push($label,$key2);
								array_push($data,$value2);

						}
					}

					$label= implode(',', $label);
					$data= implode(',', $data);

					array_push($chartdata,$data);
					array_push($seriestype,$column->Series_Type);
					array_push($serieslabel,$chartinfo->Chart_View_Name);
	        array_push($seriescolor,'#2ecc71');

					array_push($chartobject,$label);
					array_push($chartobject,$chartdata);
					array_push($chartobject,$seriestype);
					array_push($chartobject,$serieslabel);
	        array_push($chartobject,$seriescolor);


				}

				if($chartobject)
				{
					array_push($chartarr,$chartobject);
				}

			}

		}
		else {
			$chartcolumn=null;
		}

    //dd($chartarr);

		$agingrules = DB::table('agings')
		->select('agings.Id','agings.ProjectId', 'agings.Active','projects.Project_Name','agings.Title','agings.Type','agings.Start_Date','agings.End_Date','agings.Threshold','agings.Recurring_Frequency','agings.Frequency_Unit','creator.Name as Creator','users.Name as Subscriber')
		->leftjoin('agingsubscribers','agingsubscribers.AgingId','=','agings.Id')
		->leftJoin('projects', 'projects.Id', '=', 'agings.ProjectId')
		->leftJoin('users as creator', 'creator.Id', '=', 'agings.UserId')
		->leftjoin('users','users.Id','=','agingsubscribers.UserId')
		->where('ProjectId', '=',$projectid)
		->get();

		//dd($agingrules);
    return view('projectdashboard',['me'=>$me,'project'=>$project,'projectid'=>$projectid,'chartview'=>$chartview,'chartarr'=>$chartarr, 'chartcolumn'=>$chartcolumn, 'agingrules'=>$agingrules,'regions'=>$regions,'region'=>$region]);

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

}
