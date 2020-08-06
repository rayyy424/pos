<?php namespace App\Http\Controllers;
use Swatkins\LaravelGantt\Gantt;
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

class GanttController extends Controller {

  public function gantt($trackerid=null, $projectid=null, $siteid=null)
  {
    $me = (new CommonController)->get_current_user();


    $projectids = explode("|",$me->ProjectIds);

    if ($projectid==null)
    {
        $projectid=12;
    }

    if ($trackerid==null)
    {
      $trackerid=58;
    }

    if ($siteid==null)
    {
      $siteid=150;
    }

    $trackerlist = DB::table('trackertemplate')
    ->leftJoin('projects', 'projects.Id', '=', 'trackertemplate.ProjectId')
    ->select('projects.Project_Name', 'trackertemplate.Tracker_Name','trackertemplate.Id')
    ->where('trackertemplate.ProjectId','=',$projectid)
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
    ->where('trackercolumn.type','=','Date')
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

    $columns=$columns;
    $columns=rtrim($columns,",");

    $trackerview = DB::table('tracker')
    ->select(DB::raw($columns))
    ->where('tracker.ProjectID', '=', $projectid)
    ->where('tracker.Id','=',$siteid)
    ->get();

    //$arr = [];

    foreach($trackerview as $sasa){

      foreach($sasa as $key=>$value){

        $arr[] = array('label' => $key, 'start' => '1-Mar-2015', 'end' => $value);;


      }

    }

  //  dd($arr);

  $data = array();

  $data[] = array(
    'label' => 'Project 1',
    'start' => '2012-04-20',
    'end'   => '2012-05-12'
  );

  $data[] = array(
    'label' => 'Project 2',
    'start' => '2012-04-22',
    'end'   => '2012-05-22',
    'class' => 'important',
  );

  $data[] = array(
    'label' => 'Project 3',
    'start' => '2012-05-25',
    'end'   => '2012-06-20',
    'class' => 'urgent',
  );

  $data[] = array(
    'label' => 'Project 1',
    'start' => '2012-04-20',
    'end'   => '2012-05-12'
  );

  $data[] = array(
    'label' => 'Project 2',
    'start' => '2012-04-22',
    'end'   => '2012-05-22',
    'class' => 'important',
  );

  $data[] = array(
    'label' => 'Project 3',
    'start' => '2012-05-25',
    'end'   => '2012-06-20',
  );

  $data[] = array(
    'label' => 'Project 1',
    'start' => '2012-04-20',
    'end'   => '2012-05-12'
  );



  $data[] = array(
    'label' => 'Project 3',
    'start' => '2012-05-25',
    'end'   => '2012-06-20',
  );

  $data[] = array(
    'label' => 'Project 1',
    'start' => '2012-04-20',
    'end'   => '2012-05-12'
  );

  $data[] = array(
    'label' => 'Project 2',
    'start' => '2012-04-22',
    'end'   => '2012-05-22',
  );

  $data[] = array(
    'label' => 'Project 3',
    'start' => '2012-05-25',
    'end'   => '2012-06-20',
  );



    $gantt = new Gantt($arr, array(
        'title'      => 'Demo',
        'cellwidth'  => 25,
        'cellheight' => 35
    ));

    //dd($arr);


  return view('gantt')->with([ 'gantt' => $gantt, 'me'=>$me ]);


  }

}
