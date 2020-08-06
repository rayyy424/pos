<?php namespace App\Http\Controllers\Api;

use JWTAuth;
use Tymon\JWTAuth\Exceptions\JWTException;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\Facades\Hash;
use File;
class ServiceController extends Controller {

    public function getServiceTicketCompleted(Request $request){
        $me = JWTAuth::parseToken()->authenticate();

        $end=date('t-M-Y');
        $start=date('d-M-Y',strtotime('first day of previous month'));

        $ticket = DB::table('serviceticket')
        ->select('serviceticket.Id','serviceticket.service_type','serviceticket.service_id','serviceticket.genset_no','serviceticket.UserId','serviceticket.service_date','gensetservice.Status','serviceticket.status','serviceticket.technicianId','rad.Latitude as Lat','rad.Longitude as Long','rad.Location_Name as Loc_Name','gensetinventory.model','gensetinventory.capacity')
        ->leftjoin('gensetservice','serviceticket.Id','=','gensetservice.ServiceId','gensetservice.Status')
        ->leftjoin('radius as rad','serviceticket.site','=','rad.Id')
        ->leftjoin('gensetinventory','serviceticket.genset_no','=','gensetinventory.barcode')
        ->where('serviceticket.technicianId','=',$me->Id)
        ->where('gensetservice.Status','=','Completed')
        ->orderBy(DB::raw('str_to_date(serviceticket.service_date,"%d-%M-%Y")'),'asc')
        ->whereRaw('serviceticket.service_date between "'.$start.'" And "'.$end.'"')
        ->get();

        // dd($end);
        // dd($start);

        return json_encode($ticket);
    }

    public function getItemInv(Request $request){
        $me=  JWTAuth::parsetoken()->authenticate();
        // $data=DB::Table('gensetinventory')
        // ->select('Id','name','type','model')
        // ->where('type','!=','GENSET')
        // ->where('type','!=','ATS')
        // ->where('type','!=','VEHICLE')
        // ->where('type','!=','TANK')
        // ->get();


        $data=DB::table('techbag')
        ->select('techbag.Balance','techbag.InvId','gensetinventory.model','gensetinventory.capacity','gensetinventory.Id','gensetinventory.name')
        ->join('gensetinventory','techbag.InvId','=','gensetinventory.Id')
        ->where('techbag.UserId',$me->Id)
        ->where('techbag.Balance','!=','')
        ->get();
        return json_encode($data);
    }

    public function returnInv(Request $request){
        $me=  JWTAuth::parsetoken()->authenticate();
        $input=$request->all();



        $qty=$request->Qty;
        foreach($request->Id as $key=>$i){
            $test=$qty[$key];


            $return = DB::table('techbag')
            ->select('techbag.Id','techbag.InvId','techbag.Balance')
            ->where('techbag.UserId','=',$me->Id)
            ->where('techbag.InvId','=',$i['InvId'])
            ->update([
                'Balance' => (int)$i['Balance'] - $test,
            ]);

            $genInv=DB::table('gensetinventory')
            ->select('Id','qty_balance')
            ->where('Id','=',$i['InvId'])
            ->first();

            $u=DB::table('gensetinventory')
            ->where('Id','=',$i['InvId'])
            ->update([
                'qty_balance' => $genInv->qty_balance + $test
                ]);

            $user=DB::table('users')
            ->where('Id','=',$me->Id)
            ->select('Name','Id')
            ->first();

            $a=DB::table('gensetinventory')
            ->select('Id','branch')
            ->where('Id','=',$i['InvId'])
            ->first();

            DB::table('gensetinventory_history')
            ->insertGetId([
                'activity' => "Stock-return from " . $user->Name,
                'branch' => "Store HQ",
                'qty' => $test,
                'type' => "Stock-return",
                'gensetinventoryId' => $i['InvId'],
                'userId' => $me->Id,
                'created_at' => DB::raw('now()'),
                'technicianId' => $me->Id
            ]);

        }


        // $balance = $input['qty_balance']+$input['qty_return'];
        //     $technician = DB::table('users')
        //     ->where('Id','=',$input['technician'])
        //     ->select('Name')
        //     ->first();
        //     DB::table('gensetinventory')
        //     ->where('Id','=',$input['StockId'])
        //     ->update([
        //             'qty_balance' => $balance
        //     ]);
            // DB::table('gensetinventory_history')
            // ->insertGetId([
            //     'activity' => $input['Process']." from ".$technician->Name,
            //     'branch' => $input['branch'],
            //     'qty' => $input['qty_return'],
            //     'type' => "Stock-return",
            //     'gensetinventoryId' => $input['StockId'],
            //     'userId' => $me->UserId,
            //     'created_at' => Carbon::now(),
            //     'technicianId' => $input['technician']
            // ]);


        return json_encode($test);

    }

	public function getServiceTicket(Request $request){
        $me = JWTAuth::parseToken()->authenticate();
            // dd($request->date);
        if($request->date){
            $service=$this->serviceTicket($me->Name,$me->Id)
            ->addSelect(DB::Raw('CASE WHEN serviceticket.sequence = 0 THEN serviceticket.Id ELSE MIN(serviceticket.Id) END as Id'))
            ->groupBy(DB::Raw('CASE WHEN serviceticket.sequence <> 0 THEN serviceticket.parent ELSE serviceticket.Id END'))
            ->where(function($q){
                $q->where('gensetservice.Status','In-Progress');
                $q->orWhere('gensetservice.Status','Repair');
                $q->orWhere('gensetservice.Status','Completed');

            })
             ->whereRaw('serviceticket.service_date = "'.$request->date.'"')
            ->get();
        }
// <<<<<<< HEAD

//         return json_encode($service);
// =======
    //     //Get service ticket by sequence
    //     $data=DB::select('
    //     Select
    //         Min(serviceticket.Id) as Id,serviceticket.service_type,serviceticket.service_id,tracker.Region,serviceticket.genset_no
    //         ,radius.Location_Name,radius.Latitude,radius.Longitude,gensetservice.Status,tracker.Site_Name,gensetservice.Id as serviceId,tracker.Site_Name,
    //         gensetservice.timeIn,gensetservice.timeOut,requisition.Id as reqId,gensetinventory.capacity,serviceticket.service_date,
    //         "" as user,serviceticket.UserId,serviceticket.technicianId
    //     FROM
    //         serviceticket
    //     left join
    //         gensetservice
    //             on gensetservice.ServiceId = serviceticket.Id
    //     left join
    //         deliveryform
    //             on deliveryform.Id = serviceticket.DeliveryId
    //     left join
    //         salesorder
    //             on salesorder.Id = deliveryform.salesorderid
    //     left join
    //         tracker
    //             on salesorder.trackerid = tracker.Id
    //     left join
    //         radius
    //             on radius.Id = deliveryform.Location
    //     left join
    //         requisition
    //             on requisition.gensetServiceId = gensetservice.Id
    //     left join
    //         gensetinventory
    //             on gensetinventory.machinery_no=serviceticket.genset_no
    //     where
    //         gensetservice.Status = "In-Progress" AND serviceticket.sequence <> 0
    //         AND serviceticket.service_date = "'.$request->date.'"
    //     group by
    //         serviceticket.parent
    //     having
    //         serviceticket.technicianId = '.$me->Id.'');
    //    $service=array_merge($data,$service);
        $service=collect($service)->transform(function ($item, $key) use($me) {
            if($me->Id == $item->UserId)
                $item->user=true;
            else $item->user=false;
            return $item;
        });

        return response()->json(['service'=>$service]);
// >>>>>>> 210b701f4b9b6f1f4a744a70ae7a7d95a661f5cf
    }

    /**
     * Get service ticket
     * @return response
     */
    private function serviceTicket($name=null,$id=null){
        $data=DB::Table('serviceticket')
        ->select('serviceticket.Id','serviceticket.service_type','rad.Location_Name as Loc_Name','serviceticket.site','serviceticket.service_id','tracker.Region','serviceticket.genset_no','serviceticket.status','serviceticket.remarks','radius.Location_Name',
        'radius.Latitude','radius.Longitude',
        'rad.Latitude as Lat','rad.Longitude as Long','gensetservice.Status','gen.model','gen.capacity','tracker.Site_Name','gensetservice.Id as serviceId','tracker.Site_Name'
        ,'gensetservice.timeIn','gensetservice.timeOut','requisition.Id as reqId','gensetinventory.capacity','serviceticket.service_date',DB::raw('"" as user'),'requisitionhistory.status as reqStatus',
        'serviceticket.UserId','serviceticket.technicianId','gensetservice.Status')
        ->leftjoin('deliveryform','deliveryform.Id','=','serviceticket.DeliveryId')
        ->leftjoin('salesorder','salesorder.Id','=','deliveryform.salesorderid')
        ->leftjoin('tracker','salesorder.trackerid','=','tracker.Id')
        ->leftjoin('radius','radius.Id','=','deliveryform.Location')
        ->leftjoin('radius as rad','serviceticket.site','=','rad.Id')
        ->leftjoin(DB::raw('(SELECT Max(Id) as maxid,ServiceId from gensetservice group by ServiceId) as max'),'max.ServiceId','=','serviceticket.Id')
        ->leftjoin('gensetservice','gensetservice.Id','=','max.maxid')
        ->leftjoin('gensetinventory as gen','serviceticket.genset_no','=','gen.machinery_no')

        ->leftjoin('requisition','requisition.gensetServiceId','=','gensetservice.Id')
        ->leftjoin(DB::raw('(SELECT Max(Id) as maxid,requisition_Id from requisitionhistory group by requisition_Id) as max1'),'max1.requisition_Id','=','requisition.Id')
        ->leftjoin('requisitionhistory','requisitionhistory.Id','=','max1.maxid')
        ->leftjoin('gensetinventory','gensetinventory.machinery_no','=','serviceticket.genset_no')
        ->orderBy('Id','asc');
        if($name != null || $id != null){
            // $data=$data->havingRaw('serviceticket.technicianId = ? OR serviceticket.UserId = ?',[$id,$id]);
            $data=$data->whereRaw("(serviceticket.technicianId = ".$id." OR serviceticket.UserId = ".$id.")");
        }
        // ->where('serviceticket.sequence',0);
        // if($name != null && $id != null){
        //     $data=$data->where(function($q) use ($name,$id){
        //         $q->where('tracker.Team','=',$name);
        //         $q->orWhere('serviceticket.technicianId',$id);
        //         $q->orWhere('serviceticket.UserId',$id);
        //     });
        // }
        return $data;
    }
    public function order(Request $request){
        $me=  JWTAuth::parsetoken()->authenticate();

        $id=DB::table('gensetinventory_history')
        ->insertGetId([
            'acitivity'=>'Ordered by '.$me->Name,
            'qty'=>$request->qty,
            'type'=>'Order',
            'technicainId'=>$me->Id,
            'userid'=>$me->Id,
        ]);
        return $id;
    }
    public function replacement(Request $request){
        $me=  JWTAuth::parsetoken()->authenticate();
        $count=1;
        $path = public_path() . "/private/upload/Genset/".$request->type;
        if(!file_exists($path)){
            File::makeDirectory($path, 0777, true, true);
        }
        foreach($request->data as $data){

            $orig=explode('/',$data['new']['file']);
            $orig=end($orig);

            $fileName = time().'_'.$count.".jpg";
            $path = public_path() . "/private/upload/Genset/".$request->type."/" . $fileName;
            $count ++;
            $old="";
            if(isset($data['replace']['file'])){
                $oldOri=explode('/',$data['replace']['file']);
                $oldOri=end($oldOri);
                $oldPath=public_path() . "/private/upload/Genset/".$request->type."/" . time().'_'.$count.".jpg";
                $oldImg=$data['replace']['view']['changingThisBreaksApplicationSecurity'];
                $oldImg=substr($oldImg,strpos($oldImg,",")+1);
                $oldImg=base64_decode($oldImg);
                file_put_contents($oldPath,$oldImg); //replace
                $old=DB::table('files')
                ->insertGetId([
                    'Type'=>'Before '.$request->type,
                    'TargetId'=>$request->id,
                    'File_Name'=>$oldOri,
                    'Web_Path'=>"/private/upload/Genset/".$request->type."/". time().'_'.$count.".jpg",

                ]);
            }

            $img = $data['new']['view']['changingThisBreaksApplicationSecurity'];
            $img = substr($img, strpos($img, ",")+1);
            $data1 = base64_decode($img);

            file_put_contents($path, $data1); // new

            // $old=DB::table('files')
            // ->insertGetId([
            //     'Type'=>'Before '.$request->type,
            //     'TargetId'=>$request->id,
            //     'File_Name'=>$oldOri,
            //     'Web_Path'=>"/private/upload/Genset/".$request->type."/". time().'_'.$count.".jpg",

            // ]);

            $new=DB::table('files')
            ->insertGetId([
                'Type'=>'After '.$request->type,
                'TargetId'=>$request->id,
                'File_Name'=>$orig,
                'Web_Path'=>"/private/upload/Genset/".$request->type."/".$fileName,
            ]);

            $count++;

            DB::Table('gensetserviceimg')
            ->insert([
                'ServiceId'=>$request->id,
                'previousFile'=>$old != "" ?$old:"",
                'previousMachinery'=>!isset($data['replace']['id']) ? :$data['replace']['id'],
                'previousQty'=>!isset($data['replace']['qty']) ? :$data['replace']['qty'],
                'newFile'=>$new,
                'newMachinery'=>!isset($data['new']['id']) ? :$data['new']['id'],
                'newQty'=>!isset($data['new']['qty']) ? :$data['new']['qty'],
            ]);
            $tech=DB::table('techbag')
            ->select('techbag.Balance','techbag.Id')
            ->where('techbag.UserId',$me->Id)
            ->where('techbag.InvId','=',$data['new']['id'])
            ->first();
            // return json_encode($tech);

            $bal=( (float) $tech->Balance) - ( (float) $data['new']['qty']);
            $update=0;
            if($tech)
                $update=DB::table('techbag')
                ->where('Id',$tech->Id)
                ->update([
                    'Balance'=>$bal
                ]);
        }
        return 1;
    }
    public function updateService(Request $request){
        $me=  JWTAuth::parsetoken()->authenticate();
        if($request->status == 'verify'){

            $inst=DB::Table('gensetservice')
            ->insertGetId([
                'Status'=>'Verified',
                'ServiceId'=>$request->id,
            ]);
            return $inst;
        }
         if(strpos($request->type,'PMO') !== false){
           DB::Table('gensetservice')
            ->where('Id',$request->serId)
            ->update([
                'Status'=>"Completed",
                'timeOut'=>DB::raw('now()'),
                'Remarks'=>$request->remarks
            ]);
            $today=date("d-M-Y");
            $insert=DB::table('gensetservice')
            ->insertGetId([
                'ServiceId'=>$request->id,
                'last_service'=>$today,
                'upcoming_service'=>date('d-M-Y', strtotime($today."+45 days")),
                'Status'=>"In-Progress"
            ]);
        }
        else{
            if($request->serId && $request->status != "repair"){
               DB::Table('gensetservice')
                ->where('Id',$request->serId)
                ->update([
                    'Status'=>"Completed",
                    'timeOut'=>DB::raw('now()'),
                    'Remarks'=>$request->remarks
                ]);

                $thisticket = DB::table('serviceticket')
                ->select('sequence','parent')
                ->where('Id','=',$request->id)
                ->first();

                $nextticket = DB::table('serviceticket')
                ->where('sequence','=',$thisticket->sequence+1)
                ->where('parent','=',$thisticket->parent)
                ->select('technicianId')
                ->first();

                $playerids = array();
                if($nextticket)
                {
                    $player = DB::table('users')
                    ->select('Player_Id')
                    ->where('Id','=',$nextticket->technicianId)
                    ->get();

                    foreach ($player as $key => $value) {
                        array_push($playerids,$value->Id);
                    }
                }

                $this->triggernextnotification($playerids);

            }else if($request->status == "repair"){
                DB::Table('gensetservice')
                ->insert([
                    'ServiceId'=>$request->id,
                    'Status'=>'Repair'
                ]);
            }

        }

        $client=DB::table('users')
                ->select('users.Id','users.User_Type','users.Player_Id')
                ->where('users.User_Type','=','Client')
                ->get();

        $notifylist=array();
        $notifyplayerid=array();

        foreach ($client as $d)
        {
            array_push($notifylist, $d->Id);
            array_push($notifyplayerid,$d->Player_Id);
            $notifyplayerid = array_filter($notifyplayerid);
        }

        if($notifyplayerid)
        {
            $this->sendComplete($notifyplayerid);
        }

        return 1;
    }
    /**
     * Get item based on Qr code - Item Code
     * @return response
     */
    public function getItem(Request $request){
        $me=  JWTAuth::parsetoken()->authenticate();
        $data=DB::table('techbag')
        ->select('techbag.Balance','techbag.InvId','gensetinventory.model','gensetinventory.capacity','gensetinventory.Id')
        ->join('gensetinventory','techbag.InvId','=','gensetinventory.Id')
        ->where('techbag.UserId',$me->Id)
        ->where('gensetinventory.barcode',$request->code)
        ->first();

        return json_encode($data);
    }
    function startTask(Request $request){
        $me=JWTAuth::parsetoken()->authenticate();
        $update=DB::Table('gensetservice')
        ->where('Id',$request->id)
        ->update([
            'timeIn'=>DB::raw('now()')
        ]);
        return $update;
    }

    function requestItem(Request $request){
        $me=  JWTAuth::parsetoken()->authenticate();
        if($request->req){
            $rqId=$request->req;
        }
        else{
            $rqo=DB::table('requisition')
            ->select('Req_No')
            ->orderBy('Id','desc')
            ->first();
            $num=$rqo ? 'RQO-'.sprintf('%05s',(int)(substr($rqo->Req_No,strpos($rqo->Req_No,'-')+1))+1):'RQO-'.sprintf('%05s',1);
            $rqId=DB::Table('requisition')
            ->insertGetId([
                'Req_No'=>$num,
                'gensetServiceId'=>$request->serId,
                'created_by'=>$me->Id
            ]);
        }

        // return $request->item;
        foreach($request->item as $item){
            $item['name']=explode(' ',$item['name'])[1];
            $invId=DB::table('gensetinventory')
            ->select('Id')
            ->where('barcode','=',$item['name'])
            ->first();
            $historyId=DB::Table('requisitionhistory')
            ->insertGetId([
                'requisition_Id'=>$rqId,
                'user_Id'=>$me->Id,
                'status'=>"Pending",
                'status_details'=>'Technician Request',
            ]);
            $id=DB::Table('requisitionitem')
            ->insertGetId([
                'InvId'=>$invId->Id,
                'Qty'=>$item['qty'],
                'historyId'=>$historyId,
                'reqId'=>$rqId
            ]);
            DB::Table('requisitionhistory')->where('Id',$historyId)->update(['reqItemId'=>$id,'Qty'=>$item['qty']]);
        }



        $notify = DB::table('users')
        ->select('Id','Player_Id')
        ->where('StaffId','like','%HN%')
        ->get();

        $notifyplayerid=array();
        $notifylist=array();

        foreach ($notify as $d)
        {
            array_push($notifylist, $d->Id);

            // if ($d->Player_Id)
            // {

            array_push($notifyplayerid,$d->Player_Id);
            $notifyplayerid = array_filter($notifyplayerid);

            // }
        }


        if($notifyplayerid)
        {
            $this->sendRequestItem($notifyplayerid);
        }


        return 1;
    }

    function getItemOption(Request $request){
        $me=  JWTAuth::parsetoken()->authenticate();

        $data=DB::Table('gensetinventory')
        ->select('Id','name','type')
        ->where('type','!=','GENSET')
        ->where('type','!=','ATS')
        ->where('type','!=','VEHICLE')
        ->where('type','!=','TANK');

        // ->where('capacity',$request->capacity)
        if(isset($request->type)){
            $data->where('type',$request->type);
        }
        return json_encode($data->get());
    }

    function getRequisitionItems(Request $request){
        $me=  JWTAuth::parsetoken()->authenticate();
        $input=$request->all();
        // $data=DB::Table('requisitionitem')
        // ->select('gensetinventory.*',DB::raw('"true" as checked'),'requisitionitem.Qty','requisitionitem.Id as reqItemId')
        // ->leftjoin('gensetinventory','gensetinventory.Id','=','requisitionitem.InvId')
        // ->leftjoin('requisitionhistory','requisitionhistory.Id','=','requisitionitem.historyId')
        // ->leftjoin('requisition','requisition.Id','=','requisitionitem.reqId')
        // ->where('requisition.Id',$input['reqId'])
        // ->where('requisitionhistory.status','Prepared')
        // ->get();
        $data=$this->requisitionData()->where('requisition.Id',$input['reqId'])
        ->where('requisitionhistory.status',$request->status)->get();
        return json_encode($data);
    }
    function createTicket(Request $request){
        $me=JWTAuth::parsetoken()->authenticate();

        $ser=DB::table('serviceticket')
        ->select(DB::raw('Max(service_id) as service_id'))
        // ->where('Id','desc')
        // ->max('service_id');
        ->first();
        if($ser)
            $ser=sprintf('%05s',(int)explode('-',$ser->service_id)[1]+1);
        else $ser=sprintf('%05s',1);
        $id=DB::table('serviceticket')
        ->insertGetId([
            'service_type'=>$request->type,
            'service_id'=>'SVT-'.$ser,
            'UserId'=>$me->Id,
            'genset_no'=>$request->genset,
            'service_date'=>date("d-M-Y")
        ]);
        DB::table('gensetservice')
        ->insert([
            'ServiceId'=>$id,
            'Status'=>'In-Progress'
        ]);
        return json_encode($this->serviceTicket()->where('serviceticket.Id',$id)->first());
    }
    function getServiceDate(Request $request){
        $me=JWTAuth::parsetoken()->authenticate();

        return json_encode($this->serviceTicket($me->Name,$me->Id)->groupBy('service_date')
            ->get());
    }
    function delivery(Request $request){
        $me=JWTAuth::parsetoken()->authenticate();
        if($request->type == 'repair'){
            DB::Table('gensetservice')
            ->insertGetId([
                'last_service'=>DB::raw('now()'),
                'Status'=>'In-Progress'
            ]);
        }else{
            DB::table('gensetservice')
            ->insertGetId([
                'ServiceId'=>$request->id,
                'status'=>'Verified',
            ]);
        }
    }
    function getRequisitionDate(Request $request){
        $me=JWTAuth::parsetoken()->authenticate();
        // $data=DB::Table('requisition')
        // ->select('requisition.Req_No','serviceticket.service_date')
        // ->leftjoin('gensetservice','gensetservice.Id','=','requisition.gensetServiceId')
        // ->leftjoin('serviceticket','serviceticket.Id','=','gensetservice.ServiceId')
        // ->groupBy('serviceticket.service_date');
        $data=$this->requisitionData($me->Name,$me->Id)->addSelect('serviceticket.service_date')
        // ->where('serviceTicket.service_date','=','$request->date')
        ->leftjoin('users as tech','tech.Id','=','requisition.created_by')
        ->where('requisitionhistory.status','=','Pending')
        ->where('tech.HolidayTerritoryId','=',$me->HolidayTerritoryId)
        ->groupBy('serviceticket.service_date')->get();
        return $data;
    }
    function updateRequisition(Request $request){
        $me=JWTAuth::parsetoken()->authenticate();
        foreach($request->arr as $arr){
            $id=DB::table('requisitionhistory')
            ->insertGetId([
                'user_Id'=>$me->Id,
                'status'=>$request->type,
                'status_details'=>$request->type == "Confirmed" ? 'Technician Confirm':"Store Prepare",
                'reqItemId'=>$arr['reqItemId'],
                'Qty'=>$arr['qty'],
                'requisition_Id'=>$arr['reqId']
            ]);

            DB::Table('requisitionitem')
            ->where('Id',$arr['reqItemId'])
            ->update([
                'historyId'=>$id,
                'Qty'=>$arr['qty']
            ]);
            if($request->type == "Confirmed"){

                $exist = DB::table('techbag')
                ->where('UserId','=',$me->Id)
                ->where('InvId','=',$arr['id'])
                ->select('Id','Balance')
                ->first();

                $getbal = DB::table('gensetinventory')
                ->select('Id','qty_balance')
                ->where('Id','=',$arr['id'])
                ->first();
                $sbalance = $getbal->qty_balance - $arr['qty'];
                DB::table('gensetinventory')
                ->where('Id','=',$arr['id'])
                ->update([
                        'qty_balance' => $sbalance
                ]);

                $storecheck = DB::table('holidayterritories')
                ->select('Name')
                ->where('Id','=',$me->HolidayTerritoryId)
                ->first();

                if( $storecheck )
                {
                    $branchcheck = DB::table('options')
                    ->whereRaw("options.Table = 'inventory' AND options.Field ='Branch'")
                    ->where('options.Option','LIKE',"%".$storecheck->Name."%")
                    ->select('Option')
                    ->first();
                }

                if($branchcheck)
                {
                    $store = $branchcheck->Option;
                }
                else
                {
                    $store = "Store HQ";
                }
                $bal = DB::table('gensetinventory_history')
                ->where('branch',$store)
                ->where('gensetinventoryId',$arr['id'])
                ->select(DB::raw('SUM(qty) as qty_balance'))
                ->first();

                $balance = $bal->qty_balance - $arr['qty'];
                dd($balance);
                if($balance < 0)
                {
                    return 0;
                }
                else
                {
                    DB::table('gensetinventory_history')
                    ->insertGetId([
                        'activity' => "Stock Out to ".$me->Name,
                        'branch' => $store,
                        'qty' => "-".$arr['qty'],
                        'type' => "Stock-out",
                        'gensetinventoryId' => $arr['id'],
                        'userId' => $me->Id,
                        'created_at' => DB::raw('Now()'),
                        'technicianId' => $me->Id
                    ]);
                }



        $notify = DB::table('users')
        ->select('users.Id','users.Player_Id','requisitionhistory.Id')
        ->leftjoin('requisitionhistory','users.Id','=','requisitionhistory.user_Id')
        ->where('requisitionhistory.user_Id','=',$me->Id)
        ->get();

        $notifyplayerid=array();
        $notifylist=array();

        foreach ($notify as $d)
        {
            array_push($notifylist, $d->Id);

            // if ($d->Player_Id)
            // {

            array_push($notifyplayerid,$d->Player_Id);
            $notifyplayerid = array_filter($notifyplayerid);

            // }
        }


        if($notifyplayerid)
        {
            $this->sendRequestItemComplete($notifyplayerid);
        }

                if($exist)
                {
                $balance = $exist->Balance + $arr['qty'];
                    DB::table('techbag')
                    ->where('Id','=',$exist->Id)
                    ->update([
                        'Balance' => $balance
                    ]);
                }
                else
                {
                    DB::table('techbag')
                    ->insert([
                        'UserId'=>$me->Id,
                        'Balance'=>$arr['qty'],
                        'InvId'=>$arr['id'],
                        'reqId'=>$arr['reqId']
                    ]);
                }
            }

        }
        return 1;
    }
    function requisitionData($name=null,$id=null){
        $data= DB::Table('requisitionitem')
        ->select('gensetinventory.*',DB::raw('"false" as checked'),'requisitionitem.Qty','requisitionitem.Id as reqItemId','requisitionitem.reqId')
        ->leftjoin('gensetinventory','gensetinventory.Id','=','requisitionitem.InvId')
        ->leftjoin(DB::raw('(SELECT Max(Id) as maxid,reqItemId FROM requisitionhistory group by reqItemId) as max'),'max.reqItemId','=','requisitionitem.Id')
        ->leftjoin('requisitionhistory','requisitionhistory.Id','=','max.maxid')
        ->leftjoin('requisition','requisition.Id','=','requisitionitem.reqId')
        ->leftjoin('gensetservice','gensetservice.Id','=','requisition.gensetServiceId')
        ->leftjoin('serviceticket','serviceticket.Id','=','gensetservice.ServiceId')
        ;

         $notify = DB::table('users')
            ->select('Id')
            ->where('StaffId','like','%HN%')
            ->get();

        $notify=collect($notify)->transform(function($item){
            return $item->Id;
        });

        // $data->whereRaw('(serviceticket.technicianId,servicetick)')
        // if($name != null && $id != null){
        //     $data=$data->where(function($q) use ($notify){
        //         // $q->where('tracker.Team','=',$name);
        //         $q->orWhereIn('serviceticket.technicianId',$notify);
        //         $q->orWhereIn('serviceticket.UserId',$notify);
        //     });
        // }
        return $data;
    }
    function getRequisition(Request $request){
        $me=JWTAuth::parsetoken()->authenticate();
        $data=$this->requisitionData($me->Name,$me->Id)->addSelect('requisition.Req_No','tech.Name as techName','st.service_id as ServiceName')
        ->leftjoin('users as tech','tech.Id','=','requisition.created_by')
        ->leftjoin('requisition as re','re.gensetServiceId','=','gensetservice.Id')
        ->leftjoin('serviceticket as st','st.Id','=','gensetservice.ServiceId')
        ->where('requisitionhistory.status','Pending')
        ->where('serviceticket.service_date','=',$request->date)
        ->where('tech.HolidayTerritoryId','=',$me->HolidayTerritoryId)
        ->groupBy('requisitionitem.reqId')->get();
        return json_encode($data);
    }

    function getRequisitionNoti(Request $request){
        $me=JWTAuth::parsetoken()->authenticate();
        $data=$this->requisitionData($me->Name,$me->Id)->addSelect('serviceticket.service_date')
        // ->where('serviceTicket.service_date','=','$request->date')
        ->leftjoin('users as tech','tech.Id','=','requisition.created_by')
        ->where('requisitionhistory.status','=','Pending')
        ->where('tech.HolidayTerritoryId','=',$me->HolidayTerritoryId)
        ->groupBy('serviceticket.service_date')->get();
        return json_encode(['data'=>$data, 'count'=>count($data)]);
    }


    //----------------------------------OnCAll-------------------------------------

    public function getoncallprogress(Request $request){
        $me = JWTAuth::parseToken()->authenticate();
        if($request->date){
            $service=$this->serviceTicket($me->Name,$me->Id)
            ->whereRaw('serviceticket.service_date = "'.$request->date.'"')->get();
            // ->where('serviceticket.status','like','%Pending%')
            // ->get();
        }else{
            $service=$this->serviceTicket($me->Name,$me->Id)
            // ->whereRaw('serviceticket.service_date = "'.$request->date.'"')->get();
            ->where('gensetservice.Status','like','%In-Progress%')
            ->get();
        }
        // $service=$this->serviceTicket($me->Name,$me->Id)->get();
        return json_encode($service);
    }

    public function getoncallpending(Request $request){
        $me = JWTAuth::parseToken()->authenticate();
        if($request->date){
            $service=$this->serviceTicket($me->Name,$me->Id)
            ->whereRaw('serviceticket.service_date = "'.$request->date.'"')->get();
            // ->where('serviceticket.status','like','%Pending%')
            // ->get();
        }else{
            $service=$this->serviceTicket($me->Name,$me->Id)
            // ->whereRaw('serviceticket.service_date = "'.$request->date.'"')->get();
            // ->where('gensetservice.ServiceId','like','serviceticket.Id')
            ->where('gensetservice.Status','like','%In-Progress%')
            ->get();
        }
        // $service=$this->serviceTicket($me->Name,$me->Id)->get();
        return json_encode($service);
    }

    public function getoncallcompleted(Request $request){
        $me = JWTAuth::parseToken()->authenticate();
        if($request->date){
            $service=$this->serviceTicket($me->Name,$me->Id)
            ->whereRaw('serviceticket.service_date = "'.$request->date.'"')->get();
            // ->where('serviceticket.status','like','%Pending%')
            // ->get();
        }else{
            $service=$this->serviceTicket($me->Name,$me->Id)
            // ->whereRaw('serviceticket.service_date = "'.$request->date.'"')->get();
            ->where('serviceticket.status','like','%Completed%')
            ->get();
        }
        // $service=$this->serviceTicket($me->Name,$me->Id)->get();
        return json_encode($service);
    }

    public function markcompleted(Request $request){
        $me = JWTAuth::parseToken()->authenticate();
        $update=DB::Table('serviceticket')
        ->where('Id',$request->Id)
        ->update([
            'status'=>"Service Completed",
        ]);
        return $update;
    }

    public function createServiceTicket(Request $request){
        $me=JWTAuth::parsetoken()->authenticate();

        $ser=DB::table('serviceticket')
        ->select(DB::raw('Max(service_id) as service_id'))
        // ->where('Id','desc')
        ->first();
        // ->max('service_id');
        if($ser)
            $ser=sprintf('%05s',(int)explode('-',$ser->service_id)[1]+1);
        else $ser=sprintf('%05s',1);
        $id=DB::table('serviceticket')
        ->insertGetId([
            'service_type'=>$request->type,
            'service_id'=>'SVT-'.$ser,
            // 'technicianId'=>$me->Id,
            'UserId'=>$me->Id,
            'genset_no'=>$request->genset,
            'service_date'=>date("d-M-Y"),
            // 'status'=>'Pending Approval',
            // 'remarks'=>$request->Reason,
            'site'=>$request->SiteName,
            'client'=>$request->Company
        ]);
        DB::table('gensetservice')
        ->insert([
            'ServiceId'=>$id,
            'Status'=>'In-Progress'
        ]);
        return json_encode($this->serviceTicket()->where('serviceticket.Id',$id)->first());
    }

    function getServiceDateOncall(Request $request){
        $me=JWTAuth::parsetoken()->authenticate();
        return json_encode($this->serviceTicket($me->Name,$me->Id)
            ->where('serviceticket.status','=','Pending Approval')
            ->groupBy('service_date')
            ->get());
    }

    function oncallstartTask(Request $request){
        $me=JWTAuth::parsetoken()->authenticate();
        $update=DB::Table('gensetservice')
        ->where('Id',$request->id)
        ->update([
            'timeIn'=>DB::raw('now()')
        ]);
        return $update;
    }

    public function getcompany()
    {

        $me = JWTAuth::parseToken()->authenticate();

        // $trackers = DB::table('tracker')
        // ->select(DB::raw('tracker.`Client Name`'),'tracker.Id')
        // ->orderBy('Id','Asc')
        // ->groupBy(DB::raw('`Client Name`'))
        // ->get();


        if($me->Company)
        {
            $trackers = DB::table('companies')
        ->orderBy('Id','Asc')
            // ->where(DB::raw('tracker.`Client Name`'), '=',$me->Company)
        ->where('companies.Company_Name','=',$me->Company)
        ->get();
        }

        else
        {
          $trackers = DB::table('companies')
        ->orderBy('Id','Asc')
            // ->where(DB::raw('tracker.`Client Name`'), '=',$me->Company)
        ->where('companies.Company_Name','=',$me->Company)
        ->get();
        }

        return json_encode($trackers);

    }

    public function getsitename()
    {

        $me = JWTAuth::parseToken()->authenticate();

        // $sitename = DB::table('tracker')
        // ->select(DB::raw('tracker.`Site Name`'),'tracker.Id')
        // ->where(DB::raw('tracker.`Client Name`'), '=', $me->Company )

        $sitename = DB::table('radius')
        ->select('radius.Location_Name','radius.Id')
        ->where('radius.ProjectId','=',146)
        ->where('radius.Client', '=',$me->Company)
        ->get();

        return json_encode($sitename);
    }


    public function getcall(Request $request)
    {

        $me = JWTAuth::parseToken()->authenticate();

        $calls = DB::table('users')
        ->select('users.Contact_No_1','users.Contact_No_2','users.Id','users.Name')
        ->where('users.Id','=',$request->UserId)
        ->first();

        return json_encode($calls);
    }

    public function getrequisitonhistory()
    {
        $me = JWTAuth::parseToken()->authenticate();

        $history = DB::table('requisitionhistory')
        ->select('requisitionhistory.Id','requisitionhistory.requisition_Id','requisitionhistory.user_Id','requisitionhistory.status','requisitionhistory.reqItemId','requisitionhistory.Qty','requisitionitem.InvId','inventories.Item_Code')
        ->leftJoin('requisitionitem','requisitionhistory.reqItemId','=','requisitionitem.Id')
        ->leftJoin('inventories','requisitionitem.InvId','=','inventories.Id')
        ->get();

        return json_encode($history);

    }


    function sendRequestItem($playerids)
    {

        $me = JWTAuth::parseToken()->authenticate();
        //
        // $playerId = DB::table('users')
        //  ->select('users.Player_Id')
        //  ->get();


        $content = array(
            "en" => 'Request Items'
        );

        $fields = array(
            'app_id' => "b22a7a60-2cfa-4641-a309-4720c564fddf",
            // 'included_segments' => array("All"),
            'include_player_ids' => $playerids,
            'data' => array("type" => "Leave"),
            'contents' => $content,
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

        function triggernextnotification($playerids)
    {

        $me = JWTAuth::parseToken()->authenticate();
        //
        // $playerId = DB::table('users')
        //  ->select('users.Player_Id')
        //  ->get();


        $content = array(
            "en" => 'Previous Task Completed'
        );

        $fields = array(
            'app_id' => "b22a7a60-2cfa-4641-a309-4720c564fddf",
            // 'included_segments' => array("All"),
            'include_player_ids' => $playerids,
            'data' => array("type" => "Service Ticket"),
            'contents' => $content,
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

    function sendRequestItemComplete($playerids)
    {

        $me = JWTAuth::parseToken()->authenticate();
        //
        // $playerId = DB::table('users')
        //  ->select('users.Player_Id')
        //  ->get();


        $content = array(
            "en" => 'Request Items Received'
        );

        $fields = array(
            'app_id' => "b22a7a60-2cfa-4641-a309-4720c564fddf",
            // 'included_segments' => array("All"),
            'include_player_ids' => $playerids,
            'data' => array("type" => "Leave"),
            'contents' => $content,
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

    function sendComplete($playerids)
    {

        $me = JWTAuth::parseToken()->authenticate();
        //
        // $playerId = DB::table('users')
        //  ->select('users.Player_Id')
        //  ->get();


        $content = array(
            "en" => 'Genset Service Completed'
        );

        $fields = array(
            'app_id' => "b22a7a60-2cfa-4641-a309-4720c564fddf",
            // 'included_segments' => array("All"),
            'include_player_ids' => $playerids,
            'data' => array("type" => "Leave"),
            'contents' => $content,
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

}
