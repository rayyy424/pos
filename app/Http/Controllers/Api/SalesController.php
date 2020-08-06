<?php namespace App\Http\Controllers\Api;

use JWTAuth;
use Tymon\JWTAuth\Exceptions\JWTException;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class SalesController extends Controller {

    public function recurringSalesOrder()
    {
      // $me = JWTAuth::parseToken()->authenticate();
      $today = date('d-M-Y', strtotime('today'));
      $today = date('d-M-Y',strtotime($today."- 1days"));
      // $today = "24-Dec-2019";
      $allid = DB::table('tracker')
       ->leftJoin( DB::raw('(select Max(Id) as maxid,trackerid,parentId from salesorder Group By trackerid,parentId) as max'), 'max.trackerid', '=', 'tracker.Id')
      ->leftJoin('salesorder', 'salesorder.Id', '=', DB::raw('max.`maxid`'))
      ->select('salesorder.Id','salesorder.rental_end')
      ->where('tracker.sales_order','<>',0)
      ->where('tracker.recurring','=',1)
      ->where('salesorder.rental_end','=',$today)
      // ->whereIn('salesorder.Id',[2445])
      ->get();

      if($allid != "" || $allid != null)
      {
        $idlist = json_decode(json_encode($allid),true);
        for($i=0;$i<Count($idlist);$i++)
        {
          $recurringcount = DB::table('tracker')
          ->leftJoin( DB::raw('(select Max(Id) as maxid,trackerid from salesorder Group By trackerid) as max'), 'max.trackerid', '=', 'tracker.Id')
          ->leftJoin('salesorder', 'salesorder.Id', '=', DB::raw('max.`maxid`'))
          ->select('salesorder.*','tracker.sales_order')
          ->where('salesorder.Id','=',$idlist[$i]['Id'])
          ->first();

          $do = DB::table('deliveryform')
          ->select('deliveryform.*')
          ->where('deliveryform.salesorderid','=',$recurringcount->Id)
          ->where('deliveryform.Purpose','=',1814)
          ->orderby('deliveryform.Id','DESC')
          ->first();
          
          //hau check do exist
          if($do)
          {
            $item = DB::table('salesorderitem')
           ->select('salesorderitem.*')
           ->where('salesorderitem.salesorderId','=',$idlist[$i]['Id'])
           ->get();
           //get SO Number
           $allso = DB::table('salesorder')
           ->select('SO_Number')
           ->get();
           $indicator = Carbon::now()->format('y').Carbon::now()->format('m');

          $maxso = DB::table('salesorder')
          ->select('Id',DB::Raw('Max(SO_Number) as SO_Number'))
          ->where('SO_Number','LIKE','%'.$indicator.'%')
          ->first();

          if($maxso->SO_Number == "" || $maxso->SO_Number == null)
          {
            $counter = 0;
          }
          else
          {
            $counter = substr($maxso->SO_Number, 7);
            $counter = $counter + 1;
          }

             $sonumber = "SO-".Carbon::now()->format('y').Carbon::now()->format('m').str_pad($counter,3,'0',STR_PAD_LEFT);
             $rentalstart = date("d-M-Y", strtotime($recurringcount->rental_end."+ 1 day"));
             $daysinmonth = date('t',strtotime($rentalstart));
             $rentalend = date("d-M-Y", strtotime($rentalstart."+ $daysinmonth days - 1day"));
             $days = date("t",strtotime($rentalstart));
             $diff = strtotime($rentalend) - strtotime($rentalstart);
             $diff = ( $diff / (60*60*24) ) +1;
             $salesordercount = $recurringcount->sales_order + 1;
             DB::table('tracker')
             ->where('Id','=',$recurringcount->trackerid)
             ->update([
                 'sales_order' => $salesordercount
             ]);
             $date = Carbon::now();
             $date = date('d-M-Y',strtotime($today));
             $id = DB::table('salesorder')
             ->insertGetId([
               'projectid' => $recurringcount->projectId,
               'companyId' => $recurringcount->companyId,
               'clientId' => $recurringcount->clientId,
               'SO_Number' => $sonumber,
               'trackerid' => $recurringcount->trackerid,
               'date' => $date,
               'rental_start' => $rentalstart,
               'rental_end' => $rentalend,
               'po' => $recurringcount->po,
               'do' => $recurringcount->do,
               'term' => $recurringcount->term,
               'parentId'=> $recurringcount->parentId
           ]);
             DB::table('salesorderdetails')
             ->insert([
               'salesorderId' => $id,
               'details' => "Sales Order Auto Generated",
               'userId' => 562,
               'created_at' => Carbon::now()
           ]);

             foreach($item as $k => $val)
             {
             // $total = DB::table('salesorderitem')
             // ->select(DB::raw('SUM(qty * price) as sum'))
             // ->where('salesorderId','=',$id)
             // ->first();
             // $charges = (($total->sum/$days)*$diff);
               // $charges = round(($val->price/$days)*$diff);
               DB::table('salesorderitem')
               ->insert([
                   'qty'=> $val->qty,
                   'price'=> $val->price,
                   'salesorderId'=> $id,
                   'description'=> $val->description,
                   'clientId'=> $val->clientId,
                   'unit'=> $val->unit
               ]);
             }
             $total = DB::table('salesorderitem')
             ->select(DB::raw('SUM(qty * price) as total'))
             ->where('salesorderId','=',$id)
             ->groupby('salesorderid')
             ->first();

             DB::table('salesorder')
             ->where('Id','=',$id)
             ->update([
                 'total_amount' => $total->total
             ]);
             //Generate DO
             $do = DB::table('deliveryform')
             ->select('deliveryform.*')
             ->where('deliveryform.salesorderid','=',$recurringcount->Id)
             ->where('deliveryform.Purpose','=',1814)
             ->orderby('deliveryform.Id','DESC')
             ->first();

             if(str_contains($do->DO_No,"_"))
             {
               $donum = explode("_",$do->DO_No)[0];
             }
             else {
               // code...
               $donum = $do->DO_No;
             }

             $doitem = DB::table('deliveryitem')
             ->select('deliveryitem.*')
             ->where('deliveryitem.formId','=',$do->Id)
             ->get();

             if($do != null)
             {
                 $company = DB::table('companies')
                 ->select('Initial')
                 ->where('Id','=',$recurringcount->companyId)
                 ->first();

               $formid = DB::table('deliveryform')
               ->insertGetId([
                 'roadtaxId'=>$do->roadtaxId,
                 'created_at'=>$do->created_at,
                 'delivery_date'=>$do->delivery_date,
                 'delivery_time'=>$do->delivery_time,
                 'Location'=>$do->Location,
                 'project_type'=>$do->project_type,
                 'ProjectId'=>$do->ProjectId,
                 'Purpose'=>$do->Purpose,
                 'DriverId'=>$do->DriverId,
                 'RequestorId'=>$do->RequestorId,
                 'company_id'=>$do->company_id,
                 'client'=>$do->client,
                 'PIC_Name'=>$do->PIC_Name,
                 'PIC_Contact'=>$do->PIC_Contact,
                 'Remarks'=>$do->Remarks,
                 'DO_No'=>$donum."_R".$recurringcount->sales_order,
                 'pick_up_time'=>$do->pick_up_time,
                 'term'=>$do->term,
                 'po'=>$do->po,
                 'representative'=>$do->representative,
                 'pickup_date'=>$do->pickup_date,
                 'approve'=>$do->approve,
                 'incentive'=>$do->incentive,
                 'trip'=>$do->trip,
                 'salesorderid'=>$id
               ]);

               foreach ($doitem as $key => $value) {
                 DB::table('deliveryitem')
                 ->insertGetId([
                   'formId' => $formid,
                   'inventoryId' => $value->inventoryId,
                   'Purpose' => $value->Purpose,
                   'Item_name' => $value->Item_name,
                   'Qty_request' => $value->Qty_request,
                   'Qty_send' => $value->Qty_send,
                   'Qty_received' => $value->Qty_received,
                   'add_desc' => $value->add_desc
                 ]);
               }

               DB::table('deliverystatuses')
               ->insertGetId([
                  'deliveryform_Id' => $formid,
                  'user_Id' => 562,
                  'delivery_status_details' => '-',
                  'delivery_status' => 'Completed',
                  'created_at' => Carbon::now()
               ]);

               DB::table('tracker')
               ->where('Id','=',$recurringcount->trackerid)
               ->update([
                  'sales_order' => $recurringcount->sales_order +1
               ]);
             }
          }

        }

      }

      // save log


      return 1;
    }

    public function removeduplicate()
    {
      $do = DB::table('deliveryform3')
      ->select('Id',DB::raw('COUNT(DO_No) as count'),'DO_No')
      ->groupby('DO_No')
      ->where('DO_No','<>','')
      ->HavingRaw('COUNT(DO_No) > 1')
      ->get();
      $dos= json_decode(json_encode($do),true);
      $dolist = array();
      for($i=0 ; $i< count($dos) ; $i ++)
      {
        for($j=0 ; $j < $dos[$i]['count'] ; $j ++)
        {
          $getdo = DB::table('deliveryform3')
          ->select('Id','DO_No')
          ->where('DO_No','=',$dos[$i]['DO_No'])
          ->first();

          DB::table('deliveryform3')
          ->where('Id','=',$getdo->Id)
          ->update([
              'DO_No' => $getdo->DO_No."_".$j
          ]);
        }
      }

      return "DONE";
    }

}
