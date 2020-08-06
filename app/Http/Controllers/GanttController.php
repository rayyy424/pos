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

  public function gantt($projectid=null,$trackerid=null,$siteid=null)
  {
    $me = (new CommonController)->get_current_user();


    $projectids = explode("|",$me->ProjectIds);

    if ($projectid==null)
    {
        $projectid=12;
    }

    if ($trackerid==null)
    {
      $trackerid=19;
    }

    if ($siteid==null)
    {
      $siteid=826;
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

    if($columns){

        $trackerview = DB::table('tracker')
        ->select(DB::raw($columns))
        ->where('tracker.ProjectID', '=', $projectid)
        ->where('tracker.Id', '=', $siteid)
        ->get();

        //dd($trackerview);

        //$arr = [];

        $startdate = '1-Mar-2017';


        foreach($trackerview as $trackerviews){

          foreach($trackerviews as $key=>$value){

            if(stristr($key, 'Actual'))
            {
              $arr[] = array('label' => $key, 'start' => $startdate, 'end' => $value, 'class'=> 'important'); //important for blue color

            }
            elseif(stristr($key, 'Plan'))
            {
              $arr[] = array('label' => $key, 'start' => $startdate, 'end' => $value, 'class'=> 'urgent'); //urgent for red color
            }
            else
            {
              $arr[] = array('label' => $key, 'start' => $startdate, 'end' => $value); //default is yellow color
            }

          }

        }

        $trackertitle = DB::table('tracker')
        ->select('Site_ID')
        ->where('tracker.Id','=',$trackerid)
        ->get();

        foreach($trackertitle as $tracker){

          $title = $tracker->Site_ID;
        }


        $gantt = new Gantt($arr, array(
            'title'      => $title,
            'cellwidth'  => 25,
            'cellheight' => 35
        ));

    //dd($arr);

   return view('gantt')->with([ 'gantt' => $gantt,'me'=>$me ]);
 }



  }

}
