<?php namespace App\Http\Controllers;
use DB;
use Illuminate\Http\Request;
use Mail;
class MaterialController extends Controller {

    public function __construct()
	{
		$this->middleware('auth');
	}

    function materialRequest($trackerid = null)
    {
        $me = (new CommonController)->get_current_user();

        $item=DB::table('inventories')
        ->select('inventories.Type','inventoryvendor.Id as vendorId','inventories.Id','Description','Item_Code','Unit','inventoryvendor.Item_Price','inventoryvendor.CompanyId as companyid')
        ->leftjoin(DB::raw('(select Max(Id) as maxId,InventoryId from inventoryvendor Group By CompanyId,InventoryId) as max'),'max.InventoryId','=','inventories.Id')
        ->leftjoin('inventoryvendor','inventoryvendor.Id','=',DB::raw('max.maxId'))
        ->where('inventoryvendor.Item_Price','>','0')
        ->get();
        
        $type=DB::table('options')
		->distinct('options.Option')
		->select('options.Option')
		->where('options.Table', '=','inventory')
        ->where('options.Field', '=','Type')
        ->get();
        $type1=DB::table('options')
		->distinct('options.Option')
		->select('options.Option')
		->where('options.Table', '=','inventory')
        ->where('options.Field', '=','Type')
        // ->where('options.Option','<>','MPSB')
        ->get();
        $save=DB::Table('savemr')
        ->leftjoin('users','users.Id','=','savemr.created_by')
        ->select('savemr.Id','savemr.created_at','users.Name')
        ->where('savemr.created_by',$me->UserId)
        ->get();
    
        $approve=$this->getAllMr("%Approved%");
        $trackerid == null ? 0:$trackerid;
        return view('materialrequest',['me'=>$me,'items'=>$item,'trackerid'=>$trackerid,'type'=>$type,'approve'=>$approve,'type1'=>$type1,'save'=>$save]);
    }
    /**
     * Get all mr based on status
     * @return response
     */
    private function getAllMR($status)
    {
        return DB::Table('material')
        ->select('material.Id','material.MR_No','users.Name',DB::raw('tracker.`Site Name`'),'materialstatus.Status'
        ,'material.created_at','material.Total',DB::raw('tracker.`Unique Id`'))
        ->leftjoin('tracker','tracker.Id','=','material.TrackerId')
        ->leftjoin('users','users.Id','=','material.UserId')
        ->leftjoin(DB::raw('(select Max(Id) as maxid,MaterialId from materialstatus group by MaterialId) as max'),'max.MaterialId','=','material.id')
        ->leftjoin('materialstatus','materialstatus.Id','=','max.maxid')
        ->where('materialstatus.Status','LIKE',$status)
        ->get();
    }
    function materialRequest2($trackerid = null)
    {
        $me=(new CommonController)->get_current_user();

        $item=DB::table('inventories')
        ->select('inventories.Type','inventoryvendor.Id as vendorId','inventories.Id','Description','Item_Code','Unit','inventoryvendor.Item_Price','inventoryvendor.CompanyId as companyid')
        ->leftjoin(DB::raw('(select Max(Id) as maxId,InventoryId from inventoryvendor Group By CompanyId,InventoryId) as max'),'max.InventoryId','=','inventories.Id')
        ->leftjoin('inventoryvendor','inventoryvendor.Id','=',DB::raw('max.maxId'))
        ->where('inventoryvendor.Item_Price','>','0')
        ->get();
        
        $type=DB::table('options')
		->distinct('options.Option')
		->select('options.Option')
		->where('options.Table', '=','inventory')
        ->where('options.Field', '=','Type')
        ->get();
        $type1=DB::table('options')
		->distinct('options.Option')
		->select('options.Option')
		->where('options.Table', '=','inventory')
        ->where('options.Field', '=','Type')
        // ->where('options.Option','<>','MPSB')
        ->get();
        $approve=$this->getAllMr("%Approved%");
        $trackerid == null ? 0:$trackerid;
        return view('materialrequest2',['me'=>$me,'items'=>$item,'trackerid'=>$trackerid,'type'=>$type,'approve'=>$approve,'type1'=>$type1]);
    }
    /**
     * Get lastest MR NO
     * @return response
     */
    private function checkMr($val)
    {
        return DB::table('material')
        ->select('MR_No')
        ->where('MR_No','LIKE',$val.'%')
        // ->orderBy('MR_No','DESC')
        // ->orderByRaw('length(MR_No) desc,MR_No desc')
        // ->orderByRaw('cast(MR_No as unsigned)')
        ->orderBy('Id','DESC')
        ->first();
    }

    private function getItem($id)
    {
        return DB::Table('materialrequest')
        ->select('materialrequest.Qty','materialrequest.Price','inventories.Item_Code','inventories.Description','inventories.Unit','inventories.Type'
        ,DB::Raw('round(materialrequest.Qty*materialrequest.Price,2) as total'),'materialrequest.InventoryId','materialrequest.vendorId')
        ->leftjoin('inventories','inventories.Id','=','materialrequest.InventoryId')
        ->where('materialrequest.MaterialId',$id)
        ->get();
    }

    private function getLog($id)
    {
        return DB::table('materialstatus')
        ->select('materialstatus.Status','approver.Name','materialstatus.Reason')
        ->leftjoin('users as approver','approver.Id','=','materialstatus.ApproverId')
        ->where('materialstatus.MaterialId',$id)
        ->get();
    }

    function getMaterial(Request $request)
    {
        $arr="";
        if(isset($request->mpsb)){
            if($request->mpsb == "1"){
                $arr="AND inventories.Type = 'MPSB'";
            }
        }
        $price=DB::select("SELECT inventories.Type,SUM(materialrequest.Qty*materialrequest.Price) as total from materialrequest 
        left join inventories on materialrequest.InventoryId = inventories.Id
        where materialrequest.MaterialId = ".$request->id." ".$arr."
        GROUP BY inventories.Type");
        
        $detail=DB::table('materialrequest')
        ->select('deliveryform.Id as deliveryId','deliveryform.DO_No','materialrequest.Id','inventories.Item_Code','inventories.Description','materialrequest.Qty','materialrequest.Price','inventories.Type','po.sum'
        ,'roadtax.Vehicle_No','roadtax.Type as vehicType')
        ->leftjoin('inventories','inventories.Id','=','materialrequest.InventoryId')
        ->leftjoin('deliveryform','deliveryform.Id','=','materialrequest.DeliveryId')
        ->leftjoin(DB::raw('(SELECT MaterialId,SUM(Qty*Price) as sum from materialpoitem group by MaterialId) as po'),'po.MaterialId','=','materialrequest.MaterialId')
        // ->where('materialrequest.MaterialId',$request->id)
        ->leftjoin('roadtax','deliveryform.roadtaxId','=','roadtax.Id')
        ->whereRaw('materialrequest.MaterialId = '.$request->id.' '.$arr)
        ->get();
        return response()->json([
            "price"=>$price,
            "detail"=>$detail
        ]);
    }
    function materialApproval()
    {
        $me= (new CommonController)->get_current_user();
        $pending=DB::table('material')
        ->leftjoin(DB::raw('(select Max(Id) as maxid,MaterialId from materialstatus group by MaterialId) as max'),'max.MaterialId','=','material.Id')
        ->leftjoin('materialstatus','materialstatus.Id','=',DB::raw('max.maxid'))
        ->where('materialstatus.ApproverId','=',$me->UserId)
        ->get();

        return view("materialapproval",['me'=>$me]);
    }
    /**
     * -> materialdetails page
     * @return response
     */
    function materialDetails(Request $request)
    {
        $me= (new CommonController)->get_current_user();

        $data=DB::table('material')
        ->select('requestor.Name','material.Total','material.Id',DB::raw('tracker.`Site Name` as site'),'materialstatus.Status','materialstatus.ApproverId')
        ->leftjoin('tracker','tracker.Id','=','material.TrackerId')
        ->leftjoin('users as requestor','requestor.Id','=','material.UserId')
        ->leftjoin(DB::raw('(select Max(Id) as maxid,MaterialId from materialstatus group by MaterialId) as max'),'max.MaterialId','=','material.Id')
        ->leftjoin('materialstatus',DB::raw('max.maxid'),'=','materialstatus.Id')
        ->where('material.Id',$request->id)
        ->first();

        return view('materialdetails',['me'=>$me,'data'=>$data]);
    }
    function updateStatus(Request $request)
    {
        $me = (new CommonController)->get_current_user();

        $materialdetail=$this->getMaterialDetail($request->id);
        $emaillist=array();
        $status="";
        $succ=false;
        if($request->status != "reject")
        {
            $approver = DB::table('users')
            ->select('users.Id','users.Name')
            ->leftJoin('accesscontroltemplates', 'accesscontroltemplates.Id', '=', 'users.AccessControlTemplateId')
            ->orderBy('Name','asc')
            ->where('accesscontroltemplates.Approve_Leave', '=','1')
            ->get();
            $temp="";
            $tempApprId=0;
            $check=false;
            $succ=false;

            array_push($emaillist,$me->UserId);
            if($approver)
            {

                foreach($approver as $appr)
                {
                    $tempcheck=$this->checkStatusForApprover($request->id,substr($appr->Level,0,3) . " Level Approved");

                    if($appr->UserId == $me->UserId && !$tempcheck && $appr->Level != "Final Approval")
                    {
                        $temp=$appr->Level;
                        $status=substr($appr->Level,0,3) . " Level Approved";
                        $check=true;
                    }
                    else if($check)
                    {
                        if($appr->Level == "Final Approval")
                        {
                                $appr->Level="6 Approval";
                        }
                        if($temp < filter_var($appr->Level, FILTER_SANITIZE_NUMBER_INT))
                        {
                            $tempApprId=$appr->UserId;
                            break;
                        }
                    }
                    if($appr->Level == "Final Approval" && $appr->UserId == $me->UserId)
                    {
                        $status="Final Approved";
                        break;
                    }

                }
                DB::table('materialstatus')
                ->insert([
                    'ApproverId'=>$me->UserId,
                    'Status'=>$status,
                    'MaterialId'=>$request->id
                ]);
                if($status != 'Final Approved' && $tempApprId != 0)
                {
                    DB::table('materialstatus')
                    ->insert([
                        'ApproverId'=>$tempApprId,
                        'Status'=>"Pending Approval",
                        'MaterialId'=>$request->id
                    ]);
                }
                $succ=true;
                array_push($emaillist,$tempApprId);
            }else{
                $approver=$this->getApprover(0);

                foreach($approver as $appr)
                {
                    $tempcheck=$this->checkStatusForApprover($request->id,substr($appr->Level,0,3) . " Level Approved");

                    if($appr->UserId == $me->UserId && !$tempcheck && $appr->Level != "Final Approval")
                    {
                        $temp=$appr->Level;
                        $status=substr($appr->Level,0,3) . " Level Approved";
                        $check=true;
                    }
                    else if($check)
                    {
                        if($appr->Level == "Final Approval")
                        {
                                $appr->Level="6 Approval";
                        }
                        if($temp < filter_var($appr->Level, FILTER_SANITIZE_NUMBER_INT))
                        {
                            $tempApprId=$appr->UserId;
                            break;
                        }
                    }
                    if($appr->Level == "Final Approval" && $appr->UserId == $me->UserId)
                    {
                        $status="Final Approved";
                        break;
                    }
                }
                DB::table('materialstatus')
                ->insert([
                    'ApproverId'=>$me->UserId,
                    'Status'=>$status,
                    'MaterialId'=>$request->id
                ]);
                if($status != 'Final Approved' && $tempApprId != 0)
                {
                    DB::table('materialstatus')
                    ->insert([
                        'ApproverId'=>$tempApprId,
                        'Status'=>"Pending Approval",
                        'MaterialId'=>$request->id
                    ]);
                }
                $succ=true;
                array_push($emaillist,$tempApprId);
            }
        }
        else{
            DB::table('materialstatus')
            ->insert([
                'ApproverId'=>$me->UserId,
                'Status'=>"Rejected",
                'MaterialId'=>$request->id,
                'Reason'=>$request->reason
            ]);
            array_push($emaillist,$me->UserId);
            $succ=true;
        }

        array_push($emaillist,$materialdetail->UserId);
        $notify=DB::table('users')
        ->whereIn("Id",$emaillist)
        ->get();
        $emails=array();

        if($status == "Final Approved")
        {
            $subscribers = DB::table('notificationtype')
            ->leftJoin('notificationsubscriber','notificationtype.Id','=','notificationsubscriber.NotificationTypeId')
            ->leftJoin('users','users.Id','=','notificationsubscriber.UserId')
            ->where('notificationtype.Id','=',80)
            ->get();
        }
        else if($request->status == "reject"){
            $subscribers = DB::table('notificationtype')
            ->leftJoin('notificationsubscriber','notificationtype.Id','=','notificationsubscriber.NotificationTypeId')
            ->leftJoin('users','users.Id','=','notificationsubscriber.UserId')
            ->where('notificationtype.Id','=',81)
            ->get();
        }
        else {
            $subscribers = DB::table('notificationtype')
            ->leftJoin('notificationsubscriber','notificationtype.Id','=','notificationsubscriber.NotificationTypeId')
            ->leftJoin('users','users.Id','=','notificationsubscriber.UserId')
            ->where('notificationtype.Id','=',80)
            ->get();
        }
        foreach($notify as $n)
        {
            if($n->Company_Email != ""){
                array_push($emails,$n->Company_Email);
            }
            else{
                array_push($emails,$n->Personal_Email);
            }
        }
        foreach($subscribers as $subscriber)
        {
            $NotificationSubject=$subscriber->Notification_Subject;
            if($subscriber->Company_Email != ""){
                array_push($emails,$subscriber->Company_Email);
            }
            else{
                array_push($emails,$subscriber->Personal_Email);
            }
        }

        $item=$this->getItem($request->id);
        $log=$this->getLog($request->id);

        // Mail::send('emails.materialstatus', ['me' => $me,'title'=>"Material Status Updated!",'materialdetail' => $materialdetail,'item'=>$item,'log'=>$log], function($message) use ($emails,$materialdetail,$NotificationSubject)
        // {
        //     array_push($emails,env('MAIL_DEFAULT_RECIPIENT'));
        //     $emails = array_filter($emails);
        //     $message->to($emails)->subject($NotificationSubject.' ['.$materialdetail->Name.']');

        // });

       return $succ == true ? 1:0;
    }
    function getApprover($pid)
    {
        return DB::table('approvalsettings')
        ->leftJoin('users', 'users.Id', '=', 'approvalsettings.UserId')
        ->select('users.Id as UserId','users.Name','approvalsettings.Level')
        ->where('approvalsettings.Type', '=', 'MR')
        ->orderByRaw("FIELD(approvalsettings.Level , '1st Approval','2nd Approval','3rd Approval','4th Approval','5th Approval','Final Approval') ASC")
        ->groupBy('approvalsettings.Country','users.Id')
        ->get();
    }
    /**
     * Get MR based on Id
     * @return response
     */
    private function getMaterialDetail($id)
    {
        return DB::table('material')
        ->select('material.MR_No',DB::raw('tracker.`Site Name` as site'),'requestor.Name','material.UserId','material.MR_No','material.created_at','material.Total',
        DB::raw('tracker.`Unique Id` as uniqueId'))
        ->leftjoin('users as requestor','requestor.Id','=','material.UserId')
        ->leftjoin('tracker','material.TrackerId','=','tracker.Id')
        ->where('material.Id',$id)
        ->first();
    }
    function recall(Request $request)
    {
        $me=(new CommonController)->get_current_user();
        DB::table('materialstatus')
        ->insert([
            "Status"=>"Recalled",
            "MaterialId"=>$request->id
        ]);

        $detail=$this->getMaterialDetail($request->id);
        $checkrev=strpos($detail->MR_No,'_rev');

        $mr_no="";
        if($checkrev === false)
            $mr_no=$detail->MR_No."_rev1";
        else
        {
            $conv=(int) substr($detail->MR_No,$checkrev+4) + 1;
            $mr_no.=substr($detail->MR_No,0,$checkrev+4).$conv;
        }
        DB::table('material')
        ->where('Id',$request->id)
        ->update([
            'MR_No'=>$mr_no
        ]);
        $emaillist=array();
        array_push($emaillist,$me->UserId);
        $approverId=$this->findApprover($request->id);
        $approverId == null ? "":array_push($emaillist,$approverId->ApproverId);
        $notify=DB::table('users')
        ->whereIn("Id",$emaillist)
        ->get();

        $subscribers = DB::table('notificationtype')
        ->leftJoin('notificationsubscriber','notificationtype.Id','=','notificationsubscriber.NotificationTypeId')
        ->leftJoin('users','users.Id','=','notificationsubscriber.UserId')
        ->where('notificationtype.Id','=',82)
        ->get();
        $emails=array();
        foreach($notify as $n)
        {
            if($n->Company_Email != ""){
                array_push($emails,$n->Company_Email);
            }
            else{
                array_push($emails,$n->Personal_Email);
            }
        }
        foreach($subscribers as $subscriber)
        {
            $NotificationSubject=$subscriber->Notification_Subject;
            if($subscriber->Company_Email != ""){
                array_push($emails,$subscriber->Company_Email);
            }
            else{
                array_push($emails,$subscriber->Personal_Email);
            }
        }

        $item=$this->getItem($request->id);
        $log=$this->getLog($request->id);

        // Mail::send('emails.materialstatus', ['me' => $me,'title'=>"Material Status Updated!",'materialdetail' => $detail,'item'=>$item,'log'=>$log], function($message) use ($emails,$detail,$NotificationSubject)
        // {
        //     array_push($emails,env('MAIL_DEFAULT_RECIPIENT'));
        //     $emails = array_filter($emails);
        //     $message->to($emails)->subject($NotificationSubject.' ['.$detail->Name.']');

        // });

        return 1;
    }

    private function findApprover($id)
    {
        return DB::table('materialstatus')
        ->select("ApproverId")
        ->where('materialstatus.MaterialId',$id)
        ->where('materialstatus.Status',"=","Pending Approval")
        ->orderBy('Id','DESC')
        ->first();
    }
    function getItemDetail(Request $request)
    {
        return DB::table('materialrequest')
        ->select('materialrequest.Id','materialrequest.Qty','materialrequest.Price','materialrequest.vendorId','materialrequest.InventoryId',
        'inventories.Item_Code','inventories.Description','inventoryvendor.Item_Price','inventoryvendor.CompanyId','inventories.Type')
        ->leftjoin('inventoryvendor','materialrequest.vendorId','=','inventoryvendor.Id')
        ->leftjoin('inventories','inventories.Id','=','inventoryvendor.InventoryId')
        ->where('materialrequest.Id',$request->id)
        ->get();
    }
    /**
     * Save details for MR when recalled
     * @return response
     */
    function saveDetails(Request $request)
    {
        $newVid=0;
        $tempmr_no=DB::Table('material')
        ->select('MR_No')
        ->where('Id',$request->id)
        ->first();
        $me=(new CommonController)->get_current_user();
        $checkhistory=DB::table('mrhistory')
        ->select('Id')
        ->where("MR_No",$tempmr_no->MR_No)
        ->first();
        if($checkhistory){
            $historyId=$checkhistory->Id;
        }else{
           $historyId=DB::table('mrhistory')
            ->insertGetId([
                'MaterialId'=>$request->id,
                'MR_No'=>$tempmr_no->MR_No,
                'created_by'=>$me->UserId
            ]); 
        }
        
        if($request->new != null){
            foreach($request->new as $new)
            {
                if($new['update'] == "true")
                {
                    $newVid=DB::table('inventoryvendor')
                    ->insertGetId([
                        "CompanyId"=>$new['company'],
                        "InventoryId"=>$new['item'],
                        'Item_Price'=>$new['price']
                    ]);
                }
                DB::table('materialrequest')
                ->insert([
                    'MaterialId'=>$request->id,
                    "InventoryId"=>$new['item'],
                    'Qty'=>$new['qty'],
                    'Price'=>$new['price'],
                    'vendorId'=>$newVid != 0 ? $newVid:$new['vendor']
                ]);
                DB::Table('mrhistoryitem')
                ->insert([
                    'HistoryId'=>$historyId,
                    'InventoryId'=>$new['item'],
                    'Qty'=>$new['qty'],
                    'Price'=>$new['price'],
                    'VendorId'=>$newVid != 0 ? $newVid:$new['vendor'],
                    'Reason'=>$new['reason'],
                    'Type'=>"New"
                ]);
            }
        }
        $newid=0;
        if($request->edit != null){
            foreach($request->edit as $edit){
                if($edit['update'] == "true")
                {
                    $newid=DB::table('inventoryvendor')
                    ->insertGetId([
                        "CompanyId"=>$edit['company'],
                        "InventoryId"=>$edit['item'],
                        'Item_Price'=>$edit['price']
                    ]);
                }
                $old=DB::table('materialrequest')->select('Qty','Price')
                ->where('Id',$edit['id'])->first();
                DB::table('materialrequest')
                ->where('Id',$edit['id'])
                ->update([
                    'InventoryId'=>$edit["item"],
                    'Qty'=>$edit['qty'],
                    'Price'=>$edit['price'],
                    'vendorId'=>$newid != 0 ? $newid:$edit['vendor']
                ]);
                $temp=$this->getMRItem($edit['id']);
                DB::table('deliveryitem')
                ->where('Id',$temp->DeliveryitemId)
                ->update([
                    'Qty_request'=>$edit['qty']
                ]);
                DB::Table('mrhistoryitem')
                ->insert([
                    'HistoryId'=>$historyId,
                    'InventoryId'=>$edit['item'],
                    'Qty'=>$edit['qty'],
                    'Price'=>$edit['price'],
                    'VendorId'=>$newid != 0 ? $newid:$edit['vendor'],
                    'Type'=>"Edit",
                    'OldPrice'=>$old->Price,
                    'OldQty'=>$old->Qty
                ]);
            }
        }
        if($request->remove != null){
            foreach($request->remove as $remove)
            {
                $temp=$this->getMRItem($remove['id']);
                DB::Table('mrhistoryitem')
                ->insert([
                    'HistoryId'=>$historyId,
                    'InventoryId'=>$temp->InventoryId,
                    'Qty'=>$temp->Qty,
                    'Price'=>$temp->Price,
                    'VendorId'=>$temp->vendorId,
                    'Reason'=>$remove['reason'],
                    'Type'=>"Delete"
                ]);
                DB::table('deliveryitem')
                ->where('Id',$temp->DeliveryitemId)
                ->delete();
                DB::table('materialrequest')
                ->where('Id',$remove['id'])
                ->delete();   
            }
        }
        $cal=$this->getItem($request->id);
        $total=0;
        foreach($cal as $c)
        {
            $total+=($c->Qty * $c->Price);
        }
        DB::table('material')
        ->where('Id',$request->id)
        ->update([
            'Total'=>$total
        ]);

        return 1;
    }
    function resubmit(Request $request)
    {
        $me= (new CommonController)->get_current_user();
        $appr=$this->findApprover($request->id);
        $detail=$this->getMaterialDetail($request->id);
        $emaillist=array();
   
        
            DB::table('materialstatus')
            ->insert([
               'Status'=>"Approved",
               'MaterialId'=>$request->id
           ]);
        

        $emaillist=array();

        array_push($emaillist,$me->UserId);

        $notify=DB::table('users')
        ->whereIn('Id',$emaillist)
        ->get();

        $subscribers = DB::table('notificationtype')
        ->leftJoin('notificationsubscriber','notificationtype.Id','=','notificationsubscriber.NotificationTypeId')
        ->leftJoin('users','users.Id','=','notificationsubscriber.UserId')
        ->where('notificationtype.Id','=',80)
        ->get();
        $emails=array();

        foreach($notify as $n)
        {
            if($n->Company_Email != ""){
                array_push($emails,$n->Company_Email);
            }
            else{
                array_push($emails,$n->Personal_Email);
            }
        }
        foreach($subscribers as $subscriber)
        {
            $NotificationSubject=$subscriber->Notification_Subject;
            if($subscriber->Company_Email != ""){
                array_push($emails,$subscriber->Company_Email);
            }
            else{
                array_push($emails,$subscriber->Personal_Email);
            }
        }
        $materialdetail=$this->getMaterialDetail($request->id);
        $item=$this->getItem($request->id);
        $log=$this->getLog($request->id);

        // Mail::send('emails.materialstatus', ['me' => $me,'title'=>"Material Status Updated!",'materialdetail' => $materialdetail,'item'=>$item,'log'=>$log], function($message) use ($emails,$materialdetail,$NotificationSubject)
        // {
        //     array_push($emails,env('MAIL_DEFAULT_RECIPIENT'));
        //     $emails = array_filter($emails);
        //     $message->to($emails)->subject($NotificationSubject.' ['.$materialdetail->Name.']');

        // });

        return 1;
    }
    function materialPrint($id)
    {
        
        $items=$this->getItem($id);
        $detail=$this->getMaterialDetail($id);

        return view('materialprint',['items'=>$items,'detail'=>$detail]);
    }
    private function checkStatusForApprover($id,$status)
    {
        return DB::select('SELECT (CASE WHEN materialstatus.Id IS NULL THEN 0 ELSE 1 END) AS val from materialstatus
        where materialstatus.MaterialId='.$id.' AND materialstatus.Status = "'.$status.'"
        ');
    }
    public function generatePO(Request $request)
    {
        $me=(new CommonController )->get_current_user();
        $check=DB::select('SELECT SUM(materialrequest.Qty*materialrequest.Price) as total,po.sum from materialrequest
        LEFT JOIN inventories on materialrequest.InventoryId = inventories.Id
        LEFT JOIN (Select materialpoitem.MaterialId,SUM(Qty*Price) as sum from materialpoitem left join materialpo on materialpo.Id = materialpoitem.PoId where 
        materialpo.Status <> "Cancelled" group by materialpoitem.MaterialId) as po on po.MaterialId=materialrequest.MaterialId
        where  materialrequest.MaterialId = '.$request->id.'
        group by materialrequest.MaterialId');
        //  where inventories.Type <> "MPSB" AND materialrequest.MaterialId = '.$request->id.'
        
        if($check[0]->sum == null){
            return 1;
        }else if($check[0]->sum >= $check[0]->total){
            return 0;
        }else{
            return 1;
        };
    }
    private function getPO($po)
    {
        return DB::table('materialpo')
        ->select('PO_No')
        ->where('PO_No','LIKE',$po.'%')
        ->where('Status','<>','Cancelled')
        ->orderBy('PO_No',"DESC")
        ->where('created_at','>','2019-07-01 17:28:06')
        ->first();
    }
    /**
     * Return View materialpo.blade
     * @return response
     */
    public function materialPO($mid=0,$start=null,$end=null)
    {
        $me =(new CommonController)->get_current_user();
        if($start == null){
            // $start=date('d-M-Y', strtotime('first day of this month'));
        }
        if($end == null){
            // $end=date('d-M-Y', strtotime('today'));
        }
        $companies=DB::Table('companies')
        ->where('Subsidiary','Yes')
        ->get();
        $terms=DB::table('options')
        ->select('Option')
        ->where('Table','materialpo')
        ->where('Field','Terms')
        ->get();
        $payment=DB::table('options')
        ->select('Option')
        ->where('Table','materialpo')
        ->where('Field','Payment Terms')
        ->get();

        return view('materialpo',['me'=>$me,'details'=>$this->getPODetails($mid,$start,$end),'companies'=>$companies,'terms'=>$terms,'payment'=>$payment,
        'mid'=>$mid,'start'=>$start,'end'=>$end]);
    }
    /**
     * Get all PO data between start and end date
     * 
     * @return response
     */
    private function getPODetails($mid,$start,$end)
    {
        return DB::table('materialpo')
        ->select('materialpo.Id','materialpo.PO_No','material.MR_No','request.Type','company.Company_Name as companyName','vendor.Company_Name as vendorName','materialpo.Terms',
        'request.total','users.Name','materialpo.created_at')
        ->leftjoin('material','material.Id','=','materialpo.MaterialId')
        ->leftjoin('inventoryvendor','inventoryvendor.Id','=','materialpo.VendorId')
        ->leftjoin('companies as vendor','vendor.Id','=','inventoryvendor.CompanyId')
        // ->leftjoin(DB::raw('(SELECT MaterialId,vendorId, SUM(materialrequest.Qty*inventoryvendor.Price) as total from materialrequest
        // group by materialrequest.vendorId,materialrequest.MaterialId) as request'),function($join){
        //     $join->on('request.vendorId','=','materialpo.VendorId');
        //     $join->on('request.MaterialId','=','materialpo.MaterialId');
        // })
        ->leftjoin(DB::raw('(SELECT inventories.Type,inventoryvendor.CompanyId,MaterialId,vendorId, SUM(materialrequest.Qty*inventoryvendor.Item_Price) as total from materialrequest
        left join inventoryvendor on materialrequest.vendorId = inventoryvendor.Id
        left join inventories on materialrequest.InventoryId = inventories.Id
        where inventories.Type <> "MPSB"
        group by inventoryvendor.CompanyId,materialrequest.MaterialId) as request'),function($join){
            
            $join->on('request.CompanyId','=','materialpo.VendorId');
            $join->on('request.MaterialId','=','materialpo.MaterialId');
        })
        ->leftjoin('companies as company','company.Id','=','materialpo.CompanyId')
        ->leftjoin('users','users.Id','=','materialpo.created_by')
        ->where(function($q) use ($mid){
            if($mid != 0)
                $q->where('materialpo.MaterialId',$mid);  
            else $q->where('materialpo.MaterialId','<>',$mid);  
        })
        ->get();
    }
    
    public function materialPoPrint($id)
    {
        $detail=$this->getPOData($id);
        $items=$this->getPOItem($id);
        $company=DB::table('companies')
        ->where('Company_Name',$detail->Company_Account)
        ->first();
        $words = $this->convertNumber(number_format($detail->total,2));
        $words = strtoupper($words);
        
        $payment=$this->getOption($id,"Payment Terms");
        $extra=$this->getOption($id,"Extra");
        return view('materialpoprint',['detail'=>$detail,'items'=>$items,'words'=>$words,'payment'=>$payment,'extra'=>$extra,'company'=>$company]);
    }
    /**
     * Get PO details based on PO Id
     * use inventoryvendor.Item_Price for po price
     * @return response
     */
    private function getPOData($id)
    {
        return DB::Table('materialpo')
        ->select('company.Company_Name','company.Address','company.Office_No','company.Fax_No','client.Company_Name as clientCompany','client.Address as clientAddress',
        'client.Office_No as clientOffice','client.Fax_No as clientFax','client.Person_In_Charge','materialpo.PO_No','materialpo.created_at',
        'materialpo.VendorId','materialpo.MaterialId',DB::raw('tracker.`Site Name` as SiteName'),'item.total','material.MR_No','users.Name','materialpo.created_at',
        'materialpo.Id','client.Contact_No','materialpo.Terms','materialpo.Delivery_Date',DB::raw('tracker.`Site ID` as SiteId'),'materialpo.Status',
        'client.Company_Account')
        ->leftjoin('companies as company','company.Id','=','materialpo.CompanyId')
        // ->leftjoin('inventoryvendor','inventoryvendor.Id','=','materialpo.VendorId')
        ->leftjoin('companies as client','client.Id','=','materialpo.VendorId')
        ->leftjoin('material','material.Id','=','materialpo.MaterialId')
        ->leftjoin('tracker','tracker.Id','=','material.TrackerId')
        ->leftjoin(DB::raw('(SELECT PoId,SUM(Round(Qty*Price,2)) as total from materialpoitem group by PoId) as item'),'item.PoId','=','materialpo.Id')
        // ->leftjoin(DB::raw('(SELECT inventories.Type,inventoryvendor.CompanyId,MaterialId,vendorId, SUM(materialrequest.Qty*inventoryvendor.Item_Price) as total from materialrequest
        // left join inventoryvendor on materialrequest.vendorId = inventoryvendor.Id
        // left join inventories on materialrequest.InventoryId = inventories.Id
        // where inventories.Type <> "Transport" and inventories.Type <> "MPSB"
        // group by inventoryvendor.CompanyId,materialrequest.MaterialId) as request'),function($join){
            
        //     $join->on('request.CompanyId','=','materialpo.VendorId');
        //     $join->on('request.MaterialId','=','materialpo.MaterialId');
        // })
        ->leftjoin('users','users.Id','=','materialpo.created_by')
        ->where('materialpo.Id',$id)
        ->first();
    }
    /**
     * Get All Item without MPSB and Transport
     * mid->Material Id 
     * vid-> Company Id
     * @return response
     */
    private function getPOItem($pid)
    {
        return DB::table('materialpoitem')
        ->select('materialpoitem.Description','materialpoitem.Add_Description','materialpoitem.Qty','materialpoitem.Unit','materialpoitem.Price',
        DB::raw('(materialpoitem.Qty*materialpoitem.Price) as total'),'materialpoitem.AccNo')
        ->where('materialpoitem.PoId',$pid)
        ->get();
    }
    function convertNumber($num = false)
    {
        $tempNum = explode( '.' , $num );
        $num = str_replace(array(',', ''), '' , trim($num));
        if(! $num) {
            return false;
        }
        $num = (int) $num;
        $words = array();
        $list1 = array('', 'one', 'two', 'three', 'four', 'five', 'six', 'seven', 'eight', 'nine', 'ten', 'eleven',
            'twelve', 'thirteen', 'fourteen', 'fifteen', 'sixteen', 'seventeen', 'eighteen', 'nineteen'
        );
        $list2 = array('', 'ten', 'twenty', 'thirty', 'forty', 'fifty', 'sixty', 'seventy', 'eighty', 'ninety', 'hundred');
        $list3 = array('', 'thousand', 'million', 'billion', 'trillion', 'quadrillion', 'quintillion', 'sextillion', 'septillion',
            'octillion', 'nonillion', 'decillion', 'undecillion', 'duodecillion', 'tredecillion', 'quattuordecillion',
            'quindecillion', 'sexdecillion', 'septendecillion', 'octodecillion', 'novemdecillion', 'vigintillion'
        );
        $num_length = strlen($num);
        $levels = (int) (($num_length + 2) / 3);
        $max_length = $levels * 3;
        $num = substr('00' . $num, -$max_length);
        $num_levels = str_split($num, 3);
        for ($i = 0; $i < count($num_levels); $i++) {
            $levels--;
            $hundreds = (int) ($num_levels[$i] / 100);
            $hundreds = ($hundreds ? ' ' . $list1[$hundreds] . ' hundred' . ( $hundreds == 1 ? '' : '' ) . ' ' : '');
            $tens = (int) ($num_levels[$i] % 100);
            $singles = '';
            if ( $tens < 20 ) {
                $tens = ($tens ? ' and ' . $list1[$tens] . ' ' : '' );
            } elseif ($tens >= 20) {
                $tens = (int)($tens / 10);
                $tens = ' and ' . $list2[$tens] . ' ';
                $singles = (int) ($num_levels[$i] % 10);
                $singles = ' ' . $list1[$singles] . ' ';
            }
            $words[] = $hundreds . $tens . $singles . ( ( $levels && ( int ) ( $num_levels[$i] ) ) ? ' ' . $list3[$levels] . ' ' : '' );
        } 
        $commas = count($words);
        if ($commas > 1) {
            $commas = $commas - 1;
        }
        $words = implode(' ',  $words);
        $words = preg_replace('/^\s\b(and)/', '', $words );
        $words = trim($words);
        $words = ucfirst($words);
        
        $cents = "";
        if(strpos($tempNum[1],'0') !== false)
        {
            if(strpos($tempNum[1],'0')==0)
            {
            $place = substr($tempNum[1],1,1);
            $cents = $list1[$place];
            }
            else if(strpos($tempNum[1],'0')== 1)
            {
            $place = substr($tempNum[1],0,1);
            $cents = $list2[$place];
            } 

        }
        else if((strpos($tempNum[1],'1') !== false))
        {
            if (strpos($tempNum[1],'1')==0)
            {
                $cents = $list1[$tempNum[1]];
            }  
            else
            {
                $place = substr($tempNum[1],1,1);
                $cents = $list1[$place];
                $place2 = substr($tempNum[1],0,1);
                $cents2 = $list2[$place2];
                $cents = $cents2."-".$cents;
            }
        }
        else
        {
            $place = substr($tempNum[1],1,1);
            $cents = $list1[$place];
            $place2 = substr($tempNum[1],0,1);
            $cents2 = $list2[$place2];
            $cents = $cents2."-".$cents;    
        }

        if($cents =="" || $cents =="-")
        {
            return $words;
        }
        else
        {
            return $words." and ".$cents." cents";
        }
    }
    function materialPoDetails($id)
    {
        $me=(new CommonController)->get_current_user();
        $data=$this->getPOData($id);
        $paymentOption = DB::table('options')
        ->select('Id','Option')
        ->where('Table','materialpo')
        ->where('Field',"Payment Terms")
        ->get();
        $payment=$this->getOption($id,"Payment Terms");
        $extra=$this->getOption($id,'Extra');
        $extraOp=DB::table('options')
        ->select('Id','Option')
        ->where('Table','materialpo')
        ->where('Field',"Extra")
        ->get();
        $companies=DB::Table('companies')
        ->select('Id','Company_Account','Company_Name')
        ->where('Company_Name','<>','')
        ->where('Company_Account','<>','')
        ->get();
        $balance=DB::select("SELECT inventories.Type,
        (CASE WHEN po.sum IS NULL THEN Round(SUM(materialrequest.Qty*materialrequest.Price),2)
        ELSE Round(SUM((materialrequest.Qty*materialrequest.Price))-po.sum,2) END) as total from materialrequest 
        left join inventories on materialrequest.InventoryId = inventories.Id
        left join (SELECT Type,SUM(Qty*Price) as sum,materialpo.Status,materialpoitem.MaterialId from materialpoitem left join materialpo on materialpoitem.PoId = materialpo.Id where materialpoitem.MaterialId=".$data->MaterialId." AND Status <> 'Cancelled' group by Type) as po on po.Type = inventories.Type
        where materialrequest.MaterialId = ".$data->MaterialId." AND inventories.Type In (Select Type from materialpoitem where PoId = ".$id." group by Type)
        GROUP BY inventories.Type");
        return view('materialpodetails',['me'=>$me,'data'=>$data,'paymentOption'=>$paymentOption,'payment'=>$payment,'extra'=>$extra,'extraOp'=>$extraOp,'balance'=>$balance,'companies'=>$companies]);
    }
    /**
     * Get options based on po id and type
     * @return response
     */
    private function getOption($id,$type)
    {
        return DB::table('materialpoextra')
        ->select('materialpoextra.Id','options.Option','materialpoextra.Value','materialpoextra.OptionsId')
        ->leftjoin('options','options.Id','=','materialpoextra.OptionsId')
        ->where('materialpoextra.Type',$type)
        ->where('materialpoextra.MaterialPoId',$id)
        ->get();
    }
    function savePaymentTerms(Request $request)
    {
        $store=array();
        $val=[];
        if(isset($request->new))
        {
            foreach($request->new as $n)
            {   
                if($n['id'] == 0)
                {
                    $id=DB::table('materialpoextra')
                    ->insertGetId([
                        'Value'=>$n['option'],
                        'MaterialPoId'=>$request->id,
                        'Type'=>"Payment Terms"
                    ]);
                }
                else
                {
                    $id=DB::table('materialpoextra')
                    ->insertGetId([
                        'OptionsId'=>$n['id'],
                        'MaterialPoId'=>$request->id,
                        'Type'=>"Payment Terms"
                    ]);
                }
                $obj[]=[
                    'id'=>$id,
                    'option'=>$n['option'],
                    'opId'=>$n['id']
                ];
            }
            array_push($val,(object)[
                'type'=>'new',
                'obj'=>$obj
            ]);
        }
        if(isset($request->edit))
        {
            foreach($request->edit as $edit)
            {
                if($edit['paymentid'] == 0)
                {
                    DB::table('materialpoextra')
                    ->where('Id','=',$edit['id'])
                    ->where('Type','Payment Terms')
                    ->update([
                        'Value'=>$edit['option']
                    ]);
                }
                else
                {
                    DB::table('materialpoextra')
                    ->where('Id','=',$edit['id'])
                    ->where('Type','Payment Terms')
                    ->update([
                        'OptionsId'=>$edit['paymentid']
                    ]);
                }
            }
        }
        if($request->remove != null)
        {
            foreach($request->remove as $r)
            {
                DB::table('materialpoextra')
                ->where('Id','=',$r['id'])
                ->delete();
            }
        }
        
        if($request->new != null || $request->remove != null || $request->edit != null)
        {
            $data=$this->getOption($request->id,"Payment Terms");
            array_push($val,(object)[
                'type'=>'data',
                'obj'=>$data
            ]);
            return $val;
            // return 1;
        }
        return 1;
    }
    function saveExtra(Request $request)
    {
        $store=array();
        $val=[];
        if(isset($request->new))
        {
            foreach($request->new as $n)
            {   
                if($n['id'] == 0)
                {
                    $id=DB::table('materialpoextra')
                    ->insertGetId([
                        'Value'=>$n['option'],
                        'MaterialPoId'=>$request->id,
                        'Type'=>"Extra"
                    ]);
                }
                else
                {
                    $id=DB::table('materialpoextra')
                    ->insertGetId([
                        'OptionsId'=>$n['id'],
                        'MaterialPoId'=>$request->id,
                        'Type'=>"Extra"
                    ]);
                }
                $obj[]=[
                    'id'=>$id,
                    'option'=>$n['option'],
                    'opId'=>$n['id']
                ];
            }
            array_push($val,(object)[
                'type'=>'new',
                'obj'=>$obj
            ]);
        }
        if(isset($request->edit))
        {
            foreach($request->edit as $edit)
            {
                if($edit['extraid'] == 0)
                {
                    DB::table('materialpoextra')
                    ->where('Id','=',$edit['id'])
                    ->where('Type','Extra')
                    ->update([
                        'Value'=>$edit['option']
                    ]);
                }
                else
                {
                    DB::table('materialpoextra')
                    ->where('Id','=',$edit['id'])
                    ->where('Type','Extra')
                    ->update([
                        'OptionsId'=>$edit['extraid']
                    ]);
                }
            }
        }
        if($request->remove != null)
        {
            foreach($request->remove as $r)
            {
                DB::table('materialpoextra')
                ->where('Id','=',$r['id'])
                ->delete();
            }
        }
        
        if($request->new != null || $request->remove != null || $request->edit != null)
        {
            $data=$this->getOption($request->id,"Extra");
            array_push($val,(object)[
                'type'=>'data',
                'obj'=>$data
            ]);
            return $val;
            // return 1;
        }
        return 1;
    }
    public function PoConfirmation($mid)
    {
        $me = (new CommonController)->get_current_user();
        $data=DB::table('materialrequest')
        ->select('materialrequest.vendorId','materialrequest.MaterialId')
        ->leftjoin('inventoryvendor','materialrequest.vendorId','=','inventoryvendor.Id')
        ->leftjoin('inventories','inventories.Id','=','inventoryvendor.InventoryId')
        ->where('materialrequest.MaterialId',$mid)
        ->get();
    
        $detail=DB::table('material')
        ->select('request.total','material.Id','material.MR_No','companies.Company_Name')
        ->leftjoin('tracker','tracker.Id','=','material.TrackerId')
        ->leftjoin(DB::raw('(SELECT MaterialId,vendorId, SUM(materialrequest.Qty*materialrequest.Price) as total from materialrequest
        left join inventories on inventories.Id = materialrequest.InventoryId
        where inventories.Type <> "MPSB" 
        group by materialrequest.MaterialId) as request'),function($join){
            $join->on('request.MaterialId','=','material.Id');
        })
        ->leftjoin('inventoryvendor','inventoryvendor.Id','=','request.vendorId')
        ->leftjoin('companies','companies.Id','=','inventoryvendor.CompanyId')
        ->where('material.Id',$mid)
        ->first();
    
        $companies=DB::Table('companies')
        ->select('Company_Account')
        ->where('Company_Account','<>','')
        ->groupBy('Company_Account')
        ->get();
        $files=DB::table('quotationfile')
        ->select('material.UserId','files.File_Name','files.Web_Path','quotationfile.Type','quotationfile.Amount','quotationfile.Status','quotationfile.Id','quotationfile.Reason')
        ->leftjoin('files','files.Id','=','quotationfile.FileId')
        ->leftjoin('material','material.Id','=','quotationfile.MaterialId')
        ->where('quotationfile.MaterialId',$mid)
        ->where(function($q){
            $q->where('quotationfile.Status',"");
            $q->orWhere('quotationfile.Status','Approved');
        })
        ->get();
        $vendor=DB::Table('companies')
        ->select('companies.Id','companies.Company_Name')
        ->whereRaw('Supplier = "Yes" OR subcon = "Yes"')
        ->get();
        $price=DB::select("SELECT inventories.Type,
        (CASE WHEN po.sum IS NULL THEN SUM(materialrequest.Qty*materialrequest.Price)
        ELSE SUM((materialrequest.Qty*materialrequest.Price))-po.sum END) as total from materialrequest 
        left join inventories on materialrequest.InventoryId = inventories.Id
        left join (SELECT PoId,Type,SUM(Qty*Price) as sum from materialpoitem left join materialpo on materialpo.Id = materialpoitem.PoId
        where materialpoitem.MaterialId=".$mid." AND materialpo.Status = '' group by Type) as po on po.Type = inventories.Type
        where materialrequest.MaterialId = ".$mid." 
        GROUP BY inventories.Type");
        $filePrice=DB::select("SELECT SUM(Amount) as total,Type from quotationfile 
        where MaterialId = ".$mid." AND (Status = '' OR Status= 'Approved') group by MaterialId,Type ");
    // where inventories.Type <> 'MPSB' AND materialrequest.MaterialId = ".$mid." 
        $terms=DB::table('options')
        ->select('Option')
        ->where('Table','materialpo')
        ->where('Field','Terms')
        ->get();
        return view('materialpoconfirmation',['me'=>$me,'data'=>$data,'detail'=>$detail,'mid'=>$mid,'companies'=>$companies,'vendor'=>$vendor,
        'files'=>$files,'price'=>$price,'filePrice'=>$filePrice,'terms'=>$terms]);
    }
    public function confirmPO(Request $request)
    {
        $me=(new CommonController)->get_current_user();
        
        $today=date("ym"); 
        $vendorArr=array();
        if(isset($request->arr)){
            foreach($request->arr as $arr){
                if(!in_array($arr['vendorId'],$vendorArr)){
                    array_push($vendorArr,$arr['vendorId']);
                }
                // $vendorid=DB::table('inventoryvendor')
                // ->insertGetId([
                //     'CompanyId'=>$arr['vendorId'],
                //     'InventoryId'=>$temp[2],
                //     'Item_Price'=>$vendor['price']
                // ]);
            }
        }
        $count=0;
        $poid=array();
        $detail=$request->detail;
        if(isset($vendorArr)){
            foreach($vendorArr as $arr)
            {
                $po = "PO-".$today;
                $p = $this->getPO($po);
                $p == null ? $po.="001":$po.=sprintf("%03s",(int)substr($p->PO_No,strpos($p->PO_No,$today)+4)+1);
                $id = DB::table('materialpo')
                ->insertGetId([
                    'VendorId'=>$arr,
                    'MaterialId'=>$request->MaterialId,
                    'created_by'=>$me->UserId,
                    'PO_No'=>$po,
                    // 'CompanyId'=>!$detail ? 0:$detail['company'] != "0" ? $detail['company']:"0",
                    'Terms'=>!$detail ? "":$detail['term'] != "0" ? $detail['term']:"",
                    'Delivery_Date'=>!$detail ? "":$detail['date'] != "0" ? $detail['date']:"",
                ]);
                foreach($request->arr as $data){
                    if($data['vendorId'] == $arr){
                       DB::table('materialpoitem')
                        ->insert([
                            "MaterialId"=>$request->MaterialId,
                            "PoId"=>$id,
                            "Description"=>$data['description'],
                            "Add_Description"=>$data['add_desc'],
                            "Unit"=>$data['unit'],
                            "Qty"=>$data["qty"],
                            "Price"=>$data["price"],
                            "Type"=>$data['type'],
                            'AccNo'=>$data['acc']
                        ]);  
                    }
                }
                array_push($poid,$id);
            }
        }

        // DB::table('material')
        // ->where('Id',$request->MaterialId)
        // ->update([
        //     // "generatePO"=>$get." PO"
        //     "generatePO"=>"0 PO"
        // ]);
        return $poid;
    }
    // private function checkPO($mid,$vid)
    // {
    //     return DB::table('materialpo')
    //     ->where('MaterialId',$mid)
    //     ->where('VendorId',$vid)
    //     ->first();
    // }
    public function getItemBasedOnType(Request $request)
    {
        return DB::table('inventories')
        ->select('inventories.Type','inventoryvendor.Id as vendorId','inventories.Id','Description','Item_Code','Unit','inventoryvendor.Item_Price','inventoryvendor.CompanyId as companyid')
        ->leftjoin(DB::raw('(select Max(Id) as maxId,InventoryId from inventoryvendor Group By CompanyId,InventoryId) as max'),'max.InventoryId','=','inventories.Id')
        ->leftjoin('inventoryvendor','inventoryvendor.Id','=',DB::raw('max.maxId'))
        ->where('inventoryvendor.Item_Price','>','0')
        ->where('inventories.Type',$request->type)
        ->get();
    }
    private function checkFileExist($id,$type)
    {
        return DB::table('files')
        ->select("Web_Path")
        ->where('Type',$type)
        ->where('TargetId',$id)
        ->first();
    }
    /**
     * Get File based on type and target id
     * @return response
     */
    public function getFile(Request $request)
    {
        $mr=$this->getMaterialDetail($request->id);
        $data= DB::table('quotationfile')
        ->select('quotationfile.created_by','users.Name','quotationfile.Requestor_Reason','material.UserId','files.File_Name','files.Web_Path','quotationfile.Type','quotationfile.Amount','quotationfile.Status','quotationfile.Id','quotationfile.Reason')
        ->leftjoin('files','files.Id','=','quotationfile.FileId')
        ->leftjoin('material','material.Id','=','quotationfile.MaterialId')
        ->leftjoin('users','users.Id','=','quotationfile.created_by')
        ->where('files.Type',$request->type)
        ->where('files.TargetId',$request->id)
        ->get();
        return response()->json([
            'mr'=>$mr,
            'data'=>$data
        ]);
    }
    /**
     * Upload file for Quotation
     * @return response
     */
    public function uploadQuotation(Request $request)
    {
        $uploadcount=1;
        $input=$request->all();
        $me=(new CommonController)->get_current_user();
        if($input['type'] == 'material'){
            $type="PO Quotation";
        }else if($input['type'] == 'item'){
            $type="Item Quotation";
        }
    
        // $exist=$this->checkFileExist($input['id'],$type);
        // if($exist){
        //     unlink(public_path().$exist->Web_Path);
        //     DB::table('files')
        //     ->where('Id',$exist->Id)
		// 	->delete();
        // }
        
        if ($request->hasFile('quotation')) {
            $file = $request->file('quotation');
            $destinationPath=public_path()."/private/upload/PO Quotation";
            if (!file_exists($destinationPath) ) {
                File::makeDirectory($destinationPath, 0777, true, true);
            }
            $extension = $file->getClientOriginalExtension();
            $originalName=$file->getClientOriginalName();
            $fileSize=$file->getSize();
            $fileName=time()."_".$uploadcount.".".$extension;
            $upload_success = $file->move($destinationPath, $fileName);
            $id=DB::table('files')->insertGetId(
                [
                    'Type' => $type,
                    'TargetId' => $input['id'],
                    'File_Name' => $originalName,
                    'File_Size' => $fileSize,
                    'Web_Path' => '/private/upload/PO Quotation/'.$fileName
                ]
            );
            $detail=$this->getMaterialDetail($input['id']);
            $chk=$this->checkAmount($input['id'],$input['quotation_type'],$input['amount'],84);//80 need to change ..notificationtype id
            if($chk){
                $id=DB::table('quotationfile')
                ->insertGetId([
                    'FileId'=>$id,
                    'MaterialId'=>$input['id'],
                    'Type'=>$input['quotation_type'],
                    'Amount'=>$input['amount'],
                    'Status'=>"Pending Approval",
                    "Requestor_Reason"=>$input['reqReason'],
                    'created_by'=>$me->UserId
                ]);
                $this->sendNotification("emails.quotationapproval","Pending Approval",$detail,84,$id); //84 notificationtype id
            }
            else{
                $id=DB::table('quotationfile')
                ->insertGetId([
                    'FileId'=>$id,
                    'MaterialId'=>$input['id'],
                    'Type'=>$input['quotation_type'],
                    'Amount'=>$input['amount'],
                    'created_by'=>$me->UserId
                ]);
            }
            $this->sendNotification("emails.newquotation","New Quotation",$detail,86,$id); //86 notificationtype id
            $detail=DB::table('quotationfile')
            ->select('users.Name','quotationfile.Requestor_Reason','files.File_Name','files.Web_Path','quotationfile.Type','quotationfile.Amount','quotationfile.Status','quotationfile.Id','quotationfile.Reason')
            ->leftjoin('files','files.Id','=','quotationfile.FileId')
            ->leftjoin('users','users.Id','=','quotationfile.created_by')
            ->where('quotationfile.Id',$id)
            ->first();
            return response()->json([
                'url'=>url('/private/upload/PO Quotation/'.$fileName),
                'file'=>$originalName,
                'detail'=>$detail
            ]);
        }else return 0;
    }
    private function checkAmount($mid,$type,$amount,$subId)
    {
        $data=DB::select('SELECT SUM(materialrequest.Qty*materialrequest.Price) as total,quotation.totalamount from materialrequest 
        LEFT JOIN (SELECT quotationfile.Status,quotationfile.Type,quotationfile.MaterialId,SUM(quotationfile.Amount) as totalamount from quotationfile
        where quotationfile.Status = "" OR quotationfile.Status = "Approved"
        group by quotationfile.Type,quotationfile.MaterialId) as quotation ON quotation.MaterialId = materialrequest.MaterialId and quotation.Type = "'.$type.'"
        LEFT JOIN inventories ON inventories.Id = materialrequest.InventoryId 
        WHERE materialrequest.MaterialId = "'.$mid.'" AND inventories.Type="'.$type.'" 
        GROUP BY inventories.Type,materialrequest.MaterialId');
        $detail=$this->getMaterialDetail($mid);
        if($data){
            if($amount+$data[0]->totalamount > $data[0]->total){
                // $this->sendNotification("emails.quotationapproval","Pending Approval",$detail,$subId);//subs id need to change
                return true;
            }else return false;
        }
    }
    /**
     * $page = Email template 
     * $title Email title
     * $detail email content
     * subscId Notificationtype Id
     * @return response
     */
    private function sendNotification($page,$title,$detail,$subscId,$qid)
    {
        $me=(new CommonController)->get_current_user();
        $subscribers = DB::table('notificationtype')
        ->leftJoin('notificationsubscriber','notificationtype.Id','=','notificationsubscriber.NotificationTypeId')
        ->leftJoin('users','users.Id','=','notificationsubscriber.UserId')
        ->where('notificationtype.Id','=',$subscId)
        ->get();
        $emails=array();
        foreach($subscribers as $subscriber)
        {
            $NotificationSubject=$subscriber->Notification_Subject;
            if($subscriber->Company_Email != ""){
                array_push($emails,$subscriber->Company_Email);
            }
            else{
                array_push($emails,$subscriber->Personal_Email);
            }
        }

        $d=DB::table('quotationfile')
        ->select('files.File_Name','files.Web_Path','quotationfile.Type','quotationfile.Amount','quotationfile.Status','quotationfile.Id','quotationfile.Reason','quotationfile.Requestor_Reason')
        ->leftjoin('files','files.Id','=','quotationfile.FileId')
        ->where('quotationfile.Id','=',$qid)
        ->first();

        if($title == "New Quotation")
        {
            $NotificationSubject="New Quotation Submitted!";
            // Mail::send($page,['me' => $me,'title'=>"New Quotation Submitted",'detail' => $detail, 'd' => $d], function($message) use ($emails,$me,$NotificationSubject)
            //     {
            // array_push($emails,env('MAIL_DEFAULT_RECIPIENT'));
            // $emails = array_filter($emails);
            // $message->to($emails)->subject($NotificationSubject.' ['.$me->Name.']');

            // });
        }
        else
        {
            $NotificationSubject="Quotation Status Updated!";
            // Mail::send($page,['me' => $me,'title'=>"Pending Approval",'detail' => $detail,'d'=>$d], function($message) use ($emails,$me,$NotificationSubject)
            //     {
            // array_push($emails,env('MAIL_DEFAULT_RECIPIENT'));
            // $emails = array_filter($emails);
            // $message->to($emails)->subject($NotificationSubject.' ['.$me->Name.']');

            // });
        }
        
    }
    function quotationApproval(Request $request){
        $me=(new CommonController)->get_current_user();
        if($request->status == "approve"){
            $status="Approved";
            DB::table('quotationfile')
            ->where('Id',$request->id)
            ->update([
                'Status'=>$status
            ]);
        }
        else if($request->status == "reject"){
            $status="Rejected";
            DB::table('quotationfile')
            ->where('Id',$request->id)
            ->update([
                'Status'=>$status,
                "Reason"=>$request->reason
            ]);
        }
        $emails=array();
        array_push($emails,$me->Company_Email != ""? $me->Company_Email:$me->Personal_Email);
        $detail=$this->getMaterialDetail($request->mrId);
        $d=DB::table('quotationfile')
        ->select('files.File_Name','files.Web_Path','quotationfile.Type','quotationfile.Amount','quotationfile.Status','quotationfile.Id','quotationfile.Reason')
        ->leftjoin('files','files.Id','=','quotationfile.FileId')
        ->where('quotationfile.Id',$request->id)
        ->first();
        $NotificationSubject="Quotation Status Updated";
        // Mail::send("emails.quotationapproval",['me' => $me,'title'=>$status,'detail' => $detail,'d'=>$d], function($message) use ($emails,$me,$NotificationSubject)
        // {
        //     array_push($emails,env('MAIL_DEFAULT_RECIPIENT'));
        //     $emails = array_filter($emails);
        //     $message->to($emails)->subject($NotificationSubject.' ['.$me->Name.']');

        // });
        return 1;
    }

    function checkQuotationExceed(Request $request)
    {
        // $data= DB::select('SELECT SUM(materialrequest.Qty*materialrequest.Price) as total from materialrequest 
        // LEFT JOIN inventories on inventories.Id = materialrequest.InventoryId
        // WHERE materialrequest.MaterialId = '.$request->id.' AND inventories.Type = "'.$request->type.'"
        // GROUP BY inventories.Type');
        $data=DB::select('SELECT SUM(materialrequest.Qty*materialrequest.Price)-(CASE WHEN quotation.totalamount IS NULL THEN 0 ELSE quotation.totalamount END) as total from materialrequest 
        LEFT JOIN (SELECT quotationfile.Status,quotationfile.Type,quotationfile.MaterialId,SUM(quotationfile.Amount) as totalamount from quotationfile
        where quotationfile.Status = "" or quotationfile.Status="Approved"
        group by quotationfile.Type,quotationfile.MaterialId) as quotation ON quotation.MaterialId = materialrequest.MaterialId and quotation.Type = "'.$request->type.'"
        LEFT JOIN inventories ON inventories.Id = materialrequest.InventoryId 
        WHERE materialrequest.MaterialId = "'.$request->id.'" AND inventories.Type="'.$request->type.'" 
        GROUP BY inventories.Type,materialrequest.MaterialId');
        if($data){
            if($data[0]->total < $request->amount){
                return 1;
            }else return 0;
        }else{
            return -1;
        }
    }
    /**
     * Get MR item based on Materialrequest Id
     * @return response
     */
    private function getMRItem($id){
        return DB::table('materialrequest')
        ->select('materialrequest.DeliveryId','materialrequest.DeliveryitemId','materialrequest.InventoryId','materialrequest.Qty','materialrequest.Price',
        'materialrequest.vendorId')
        ->where('materialrequest.Id',$id)
        ->first();
    }
    function savePoItem(Request $request)
    {
        $me=(new CommonController)->get_current_user();
        if(isset($request->data)){
            foreach($request->data as $data){
                DB::Table('materialpoitem')
                ->where('Id',$data['id'])
                ->update([
                    'Description'=>$data['desc'],
                    'Add_Description'=>$data['add_desc'],
                    'Qty'=>$data['qty'],
                    'Price'=>$data['price'],
                ]);
                DB::Table('editpo')
                ->insert([
                    'PoItemId'=>$data['id'],
                    'created_by'=>$me->UserId,
                    'Reason'=>$data['reason']
                ]);
            }
        }
        return 1;
    }
    function cancel(Request $request){
        $me=(new CommonController)->get_current_user();
        if(isset($request->id)){
            $update=DB::Table('materialpo')
            ->where('Id',$request->id)
            ->update([
                'Status'=>"Cancelled",
                'Reason'=>$request->reason,
                'cancel_by'=>$me->UserId,
                'updated_at'=>DB::raw('now()')
            ]);
            return $update;
        }
        return 0;
    }
    function getAllPO(Request $request){

        return DB::select("SELECT poType.Type,po.Id,po.PO_No,vendor.Company_Name as vendorName,poType.total from materialpo po
            left join material on material.Id = po.MaterialId
            left join companies as vendor on vendor.Id = po.VendorId
            left join (Select Type,PoId,SUM(Qty*Price) as total from materialpoitem group by Type,PoId) as poType on poType.PoId = po.Id
            where (poType.Type) In (SELECT inventories.Type from materialrequest left join inventories on inventories.Id = materialrequest.InventoryId where materialrequest.MaterialId = ".$request->id." group by materialrequest.Type)
        ");
        
    }
    function PoItem(Request $request){
        
        return DB::select('SELECT materialpoitem.Id,materialpoitem.Description,materialpoitem.Add_Description,materialpoitem.Qty,materialpoitem.Price,
        materialpoitem.Qty*materialpoitem.Price as total,materialpoitem.Type,materialpoitem.AccNo,materialpoitem.Unit,companies.Company_Name as vendorName,
        companies.Id as vendorId
        from materialpoitem
        left join materialpo on materialpo.Id = materialpoitem.PoId
        left join companies on companies.Id = materialpo.VendorId
        where materialpoitem.PoId = '.$request->id.'
        ');
    }
    function previewPo(Request $request){
    
        $company=DB::table('companies')
        ->where('Company_Name',$request->detail['company'])
        ->first();
        $extra=$request->detail;
        $vendor=array();
        $returnHtml=array();
        $detail=DB::table('material')
        ->leftjoin('tracker','tracker.Id','=','material.TrackerId')
        ->where('material.Id',$request->id)->first();
        $today=date("ym"); 
        $po = "PO-".$today;
        $p = $this->getPO($po);
        $p == null ? $po.="001":$po.=sprintf("%03s",(int)substr($p->PO_No,strpos($p->PO_No,$today)+4)+1);
        $count=0;
        $items=array();
        $total=0.00;
        $test=array();
        foreach($request->arr as $arr){
            if(!in_array($arr['vendorId'],$vendor)){
                array_push($vendor,$arr['vendorId']);
                $client=DB::table('companies')
                ->where('Id',$arr['vendorId'])
                ->first();
            }
            $total+=round($arr['qty']*$arr['price'],2);
        }
        $word=$this->convertNumber(number_format($total,2,'.',','));
        $word=strtoupper($word);
        $html=view('materialpopreview')->with(['words'=>$word,'total'=>$total,'po'=>$po,'company'=>$company,'items'=>$request->arr,'client'=>$client,'detail'=>$detail,'extra'=>$extra])->render();
        return response()->json(array('success' => true, 'html'=>$html));
    }
    function removePoItem(Request $request){

        $me=(new CommonController)->get_current_user();
        
        foreach($request->id as $arr){
            $temp=DB::Table('materialpoitem')
            ->where('Id',$arr)
            ->first();
            DB::table('removepo')
            ->insert([
                'PoId'=>$temp->PoId,
                'Reason'=>$request->reason,
                'Description'=>$temp->Description,
                'Add_Description'=>$temp->Add_Description,
                'Qty'=>$temp->Qty,
                'Price'=>$temp->Price,
                'Acc'=>$temp->AccNo, 
                'Unit'=>$temp->Unit,
                'created_by'=>$me->UserId,

            ]);
            DB::table('materialpoitem')
            ->where('Id',$arr)->delete();
        }
        return 1;
    }

    function saveMR(Request $request){
        $me=(new CommonController)->get_current_user();
        if($request->savemr){
            DB::table('savemr')
            ->where('Id',$request->savemr)->delete();
            DB::table('savemritem')
            ->where('SaveId',$request->savemr)->delete();
        }
        $id=DB::Table('savemr')
        ->insertGetId([
            'TrackerId'=>$request->site ? $request->site:0,
            'created_by'=>$me->UserId
        ]);
        foreach($request->arr as $item){
            DB::table('savemritem')
            ->insert([
                "SaveId"=>$id,
                "InventoryId"=>$item['item'],
                "Price"=>(float) $item['price'],
                "Qty"=>$item['qty'],
                'vendorId'=>$item['vendor']
            ]);
        }
        return $id;
    }
    function saveMrPage($id){

        $me=(new CommonController)->get_current_user();
        $mr=DB::table('savemr')
        ->where('Id',$id)
        ->first();

        $item=DB::table('inventories')
        ->select('inventories.Type','inventoryvendor.Id as vendorId','inventories.Id','Description','Item_Code','Unit','inventoryvendor.Item_Price','inventoryvendor.CompanyId as companyid')
        ->leftjoin(DB::raw('(select Max(Id) as maxId,InventoryId from inventoryvendor Group By CompanyId,InventoryId) as max'),'max.InventoryId','=','inventories.Id')
        ->leftjoin('inventoryvendor','inventoryvendor.Id','=',DB::raw('max.maxId'))
        ->where('inventoryvendor.Item_Price','>','0')
        ->get();
        
        $type=DB::table('options')
		->distinct('options.Option')
		->select('options.Option')
		->where('options.Table', '=','inventory')
        ->where('options.Field', '=','Type')
        ->get();
        $type1=DB::table('options')
		->distinct('options.Option')
		->select('options.Option')
		->where('options.Table', '=','inventory')
        ->where('options.Field', '=','Type')
        ->where('options.Option','<>','MPSB')
        ->get();

        return view('savemr',['me'=>$me,'mr'=>$mr,'type'=>$type,'type1'=>$type1,'items'=>$item]);
    }
    function getSaveMrItem(Request $request){
        return DB::Table('savemritem')
        ->select('inventories.Type','inventories.Item_Code','inventories.Description','inventories.Unit','savemritem.Qty','savemritem.Price',
        'inventories.Unit',DB::raw('round(savemritem.Qty*savemritem.Price,2) as total'),'savemritem.InventoryId','savemritem.vendorId')
        ->leftjoin('inventories','inventories.Id','=','savemritem.InventoryId')
        ->where('savemritem.SaveId',$request->id)
        ->get();
    }
    function getMr(Request $request){
        return $this->getAllMr("%Approved%");
    }
    function getImportMRItem(Request $request){
        return json_encode($this->getItem($request->id));
    }
    function removeFile(Request $request){
        
        $temp=DB::Table('quotationfile')->select('FileId')->where('Id',$request->id)->first();
        $file=DB::table('files')
        ->select("Web_Path")
        ->where('Id',$temp->FileId)
        ->first();
        if (file_exists(public_path().$file->Web_Path) ) {
            unlink(public_path().$file->Web_Path);
        }
        DB::table('files')->where('Id',$temp->FileId)->where('Type','PO Quotation')->delete();
        DB::table('quotationfile')->where('Id',$request->id)->delete();
        return 1;
    }
    function mrHistory($id){
        $me=(new CommonController)->get_current_user();
        return view('mrhistory',['me'=>$me,'id'=>$id]);
    }
    function getCancelledPo(){
        return $po=DB::select('SELECT PO_No,Status from materialpo where Po_No Not In (SELECT PO_No from materialpo where Status = "") AND Status="Cancelled" group by PO_No');
    }
    function savePoNo(Request $request){
        DB::table('materialpo')
        ->where('Id',$request->id)
        ->update([
            'PO_No'=>$request->po
        ]);
        return 1;
    }
    public function filterClient(Request $request){
        return DB::Table('companies')
        ->select('Id','Company_Name','CreditorCode')
        ->where('Company_Account',$request->name)
        ->whereRaw('(Supplier = "Yes" OR subcon = "Yes")')
        ->get();
    }
   function saveVendor(Request $request){
       $upd=DB::Table('materialpo')
       ->where('Id',$request->id)
       ->update([
            'VendorId'=>$request->vendor
       ]);
       return $upd;
   }
}
