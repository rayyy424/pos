<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\Facades\Mail;
use Carbon\Carbon;
// use Dompdf\Dompdf;
// use Dompdf\Options;
use PDF;
class SalesController extends Controller {

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

    $pnl= DB::select("
      SELECT
      po.Id,
      po.PO_No,
      FORMAT(SUM(IF(po.PO_Type='Receive',po.Amount,0)),2) AS 'Total_In',
      FORMAT(SUM(IF(po.PO_Type='Issue',po.Amount,0)),2) AS 'Total_Out',
      FORMAT((SUM(IF(po.PO_Type='Receive',po.Amount,0))-SUM(IF(po.PO_Type='Issue',po.Amount,0))),2) AS 'Profit',
      FORMAT(((SUM(IF(po.PO_Type='Receive',po.Amount,0))-SUM(IF(po.PO_Type='Issue',po.Amount,0)))/SUM(IF(po.PO_Type='Receive',po.Amount,0))*100),2) AS 'Profit %'
      FROM po
      WHERE po.PO_Type != ""
      GROUP BY po.PO_No
      ");

			return view('pnl', ['me' => $me,'pnl'=>$pnl]);

	}

  public function pnl()
    {
		$me = (new CommonController)->get_current_user();

    $pnl= DB::select("
      SELECT
      po.Site_Code,
      po.Site_Name,
      FORMAT(SUM(IF(po.PO_Type='Receive',po.Amount,0)),2) AS 'Total_In',
      FORMAT(SUM(IF(po.PO_Type='Issue',po.Amount,0)),2) AS 'Total_Out',
      FORMAT((SUM(IF(po.PO_Type='Receive',po.Amount,0))-SUM(IF(po.PO_Type='Issue',po.Amount,0))),2) AS 'Profit',
      FORMAT(((SUM(IF(po.PO_Type='Receive',po.Amount,0))-SUM(IF(po.PO_Type='Issue',po.Amount,0)))/SUM(IF(po.PO_Type='Receive',po.Amount,0))*100),2) AS 'Profit %'
      FROM po
      WHERE po.PO_No !=""
      GROUP BY po.Site_Name
      ");

      $pnl2= DB::select("
        SELECT
        po.Id,
        po.Work_Order_ID,
        po.Site_Code,
        po.Site_Name,
        FORMAT(SUM(IF(po.PO_Type='Receive',po.Amount,0)),2) AS 'Total_In',
        FORMAT(SUM(IF(po.PO_Type='Issue',po.Amount,0)),2) AS 'Total_Out',
        FORMAT((SUM(IF(po.PO_Type='Receive',po.Amount,0))-SUM(IF(po.PO_Type='Issue',po.Amount,0))),2) AS 'Profit',
        FORMAT(((SUM(IF(po.PO_Type='Receive',po.Amount,0))-SUM(IF(po.PO_Type='Issue',po.Amount,0)))/SUM(IF(po.PO_Type='Receive',po.Amount,0))*100),2) AS 'Profit %'
        FROM po
        WHERE po.PO_No !=""
        GROUP BY po.Work_Order_ID
        ");

			return view('projectpnl', ['me' => $me,'pnl'=>$pnl,'pnl2'=>$pnl2]);

	}

      public function pendinginvoice($client = null, $type = null)
    {
      $me = (new CommonController)->get_current_user();
      $today = date('d-M-Y', strtotime('today'));
      if($client != null)
      {
        $list = DB::table('salesorder')
      ->leftJoin('tracker','tracker.Id','=','trackerid')
      ->leftjoin('companies','companies.Id','=','salesorder.companyId')
      ->leftjoin('companies as client','client.Id','=','salesorder.clientId')
      ->select('tracker.Id','salesorder.po','salesorder.SO_Number',DB::raw('tracker.`Hire Date` as hiredate'),'salesorder.rental_start','salesorder.rental_end','companies.Company_Name','client.Company_Name as client','tracker.Region','tracker.State',DB::raw('tracker.`Site Name` as SiteName'),'tracker.NTP')
      ->where('salesorder.invoice','=','0')
      ->where('salesorder.rental_end','<',$today)
      ->where('client.Id','=',$client)
      ->get();
      }
      elseif ($type != null)
      {
        $list = DB::table('salesorder')
      ->leftJoin('tracker','tracker.Id','=','trackerid')
      ->leftjoin('companies','companies.Id','=','salesorder.companyId')
      ->leftjoin('companies as client','client.Id','=','salesorder.clientId')
      ->select('tracker.Id','salesorder.po','salesorder.SO_Number',DB::raw('tracker.`Hire Date` as hiredate'),'salesorder.rental_start','salesorder.rental_end','companies.Company_Name','client.Company_Name as client','tracker.Region','tracker.State',DB::raw('tracker.`Site Name` as SiteName'),'tracker.NTP')
      ->where('salesorder.invoice','=','0')
      ->where('salesorder.rental_end','<',$today)
      ->where('client.type','=',$type)
      ->get();
      }
      else{
      $list = DB::table('salesorder')
      ->leftJoin('tracker','tracker.Id','=','trackerid')
      ->leftjoin('companies','companies.Id','=','salesorder.companyId')
      ->leftjoin('companies as client','client.Id','=','salesorder.clientId')
      ->select('tracker.Id','salesorder.po','salesorder.SO_Number',DB::raw('tracker.`Hire Date` as hiredate'),'salesorder.rental_start','salesorder.rental_end','companies.Company_Name','client.Company_Name as client','tracker.Region','tracker.State',DB::raw('tracker.`Site Name` as SiteName'),'tracker.NTP')
      ->where('salesorder.invoice','=','0')
      ->where('salesorder.rental_end','<',$today)
      ->get();
      }

      $clients = DB::table('companies')
      ->select("Id",'Company_Name')
      ->get();
      return view('pendinginvoice', ['me' => $me , 'list' => $list, 'clients'=>$clients]);
   }

   public function InvoiceNumber($id)
   {
        $item = DB::table('salesorder')
        ->select('invoice_number','combined_invoice_num','combine_remarks','invoice_date','combined_invoice_date')
        ->where('Id','=',$id)
        ->first();

          return response()->json(['item'=>$item]);
   }

   public function updateInvoiceNumber(Request $request)
   {
        $input = $request->all();
        $exist = DB::table('salesorder')
        ->select('combined_invoice_date')
        ->where('combined_invoice_num','=',$input['Cinv'])
        ->first();

        if($exist != null)
        {
          if($exist->combined_invoice_date != "" || $exist->combined_invoice_date != null)
          {
              $item = DB::table('salesorder')
              ->where('Id','=',$input['Id'])
              ->update([
                  'invoice_number' => $input['Inv'],
                  'combined_invoice_num' => $input['Cinv'],
                  'combined_invoice_date' => $exist->combined_invoice_date
              ]);
          }
        }

          $item = DB::table('salesorder')
          ->where('Id','=',$input['Id'])
          ->update([
                'invoice_number' => $input['Inv'],
                'combined_invoice_num' => $input['Cinv']
          ]);

          DB::table('salesorder')
          ->where('combined_invoice_num','=',$input['Cinv'])
          ->update([
            'combine_remarks' => $input['remarks']
            ]);

          if($input['date'] != "")
          {
            DB::table('salesorder')
            ->where('Id','=',$input['Id'])
            ->update([
            'invoice_date' => $input['date']
            ]);
          }

          if($input['cdate'] != "" && $input['Cinv'] != "")
          {
            DB::table('salesorder')
            ->where('combined_invoice_num','=',$input['Cinv'])
            ->update([
            'combined_invoice_date' => $input['cdate']
            ]);
          }
        return 1;
   }

    public function updateTempDONumber(Request $request)
   {
      $input = $request->all();
      DB::table('deliveryform')
      ->where('Id','=',$input['doid'])
      ->update([
        'temp_DO' => $input['tempdo']
      ]);
      return 1;
   }

   public function salessummarydetails($company,$range,$jdni=null)
    {
      $me = (new CommonController)->get_current_user();
      
      $cond = "salesorder.clientId = ".$company;

      if($jdni)
      {
        $cond = $cond." AND salesorder.invoice = 0";
      }

        $summary = DB::table('tracker')
        ->leftJoin('salesorder', 'salesorder.trackerid', '=', 'tracker.Id')
        ->leftjoin('companies','companies.Id','=','salesorder.companyId')
        ->leftjoin('companies as client','client.Id','=','salesorder.clientId')
        ->leftJoin('creditnote','creditnote.salesorderId','=','salesorder.Id')
        ->select('salesorder.Id','salesorder.po','salesorder.SO_Number',DB::raw('tracker.`Hire Date` as hiredate'),'salesorder.rental_start','salesorder.rental_end','companies.Company_Name','client.Company_Name as client','tracker.Region','tracker.State',DB::raw('tracker.`Site Name` as SiteName'),'salesorder.total_amount','creditnote.amount')
        ->whereRaw($cond)
        ->whereRaw('STR_TO_DATE(rental_start,"%d-%M-%Y") BETWEEN STR_TO_DATE("01-'.$range.'","%d-%M-%Y") AND STR_TO_DATE("31-'.$range.'","%d-%M-%Y")')
        ->get();

        return view('salessummarydetails', ['me'=>$me, 'summary'=>$summary]);
    }

    public function salesorder($clientid = null, $companytype = null, $detail = null)
    {
      $me = (new CommonController)->get_current_user();

      $cond = "1";
      if($clientid && $clientid != "null")
      {
        $cond .= 'AND salesorder.clientId = '.$clientid;
      }

      if($companytype && $companytype != "null")
      {
        $cond .= 'AND client.type = '.$companytype;
      }

      if($detail)
      {
        $list = DB::table('tracker')
        ->leftJoin('salesorder', 'salesorder.trackerid', '=', 'tracker.Id')
        ->leftjoin('companies','companies.Id','=','salesorder.companyId')
        ->leftjoin('companies as client','client.Id','=','salesorder.clientId')
        ->select('tracker.Id','salesorder.Id as soId','salesorder.po','salesorder.SO_Number',DB::raw('tracker.`Hire Date` as hiredate'),'salesorder.rental_start','salesorder.rental_end','companies.Company_Name','client.Company_Name as client','tracker.Region','tracker.State',DB::raw('tracker.`Site Name` as SiteName'),'tracker.sales_order','salesorder.do','tracker.recurring','salesorder.invoice')
        ->whereRaw($cond)
        ->get();
      }
      else
      {
        $list = DB::table('tracker')
        ->leftJoin( DB::raw('(select Max(Id) as maxid,trackerid from salesorder Group By trackerid) as max'), 'max.trackerid', '=', 'tracker.Id')
        // ->leftJoin( DB::raw('(select Id as maxid,trackerid from salesorder) as max'), 'max.trackerid', '=', 'tracker.Id')
        ->leftJoin('salesorder', 'salesorder.Id', '=', DB::raw('max.`maxid`'))
        ->leftjoin('companies','companies.Id','=','salesorder.companyId')
        ->leftjoin('companies as client','client.Id','=','salesorder.clientId')
        ->select('tracker.Id','salesorder.Id as soId','salesorder.po','salesorder.SO_Number',DB::raw('tracker.`Hire Date` as hiredate'),'salesorder.rental_start','salesorder.rental_end','companies.Company_Name','client.Company_Name as client','tracker.Region','tracker.State',DB::raw('tracker.`Site Name` as SiteName'),'tracker.sales_order','salesorder.do','tracker.recurring','salesorder.invoice')
        ->whereRaw($cond)
        ->get();
      }

      $clients = DB::table('companies')
      // ->leftjoin('salesorder','salesorder.clientId','=','companies.Id')
      ->select('companies.Id','Company_Name')
      ->where('companies.Client','=','Yes')
      ->get();

      return view('salesorder', ['me' => $me,'list'=>$list,'clients'=>$clients,'detail'=>$detail]);

  }

  public function deleteoption(Request $request)
    {
      $input = $request->all();
      
      DB::table('salesorderitem')
      ->where('description','=',$input['desc'])
      ->update([
          'exclude' => 1
      ]);
      return 1;
    }

  public function uploadacceptance(Request $request)
    {
      $me = (new CommonController)->get_current_user();
      $input = $request->all();

      $today = date('d-M-Y', strtotime('today'));

        $filenames="";
        $attachmentUrl = null;
        $type="Acceptance_Documents";
        $path = "/private/upload/SO/".$type;
        if(isset($input['so']))
        {
          if ($input['so'] != null || $input['so'] != "") {
          $uploadcount=count($input['so']);
            for ($i=0; $i <$uploadcount ; $i++) {
                # code...
                $file = $input['so'][$i];
                $destinationPath=public_path().$path;
                $extension = $file->getClientOriginalExtension();
                $originalName=$file->getClientOriginalName();
                $fileSize=$file->getSize();
                $fileName=time()."_".$i.".".$extension;
                $upload_success = $file->move($destinationPath, $fileName);
                $insert=DB::table('files')->insertGetId(
                    ['Type' => $type,
                     'TargetId' => $input['trackerid'],
                     'salesorderid' => $input['salesorderid'],
                     'File_Name' => $originalName,
                     'File_Size' => $fileSize,
                     'Document_Type' => "SO",
                     'Web_Path' => $path."/".$fileName
                    ]
                );
                $attachmentUrl = url($path."/".$fileName);
                $filenames.= $insert."|".$attachmentUrl."|" .$originalName.",";
            }

            DB::Table('tracker')
            ->where('Id','=',$input['trackerid'])
            ->update([
                'SO' => $today
            ]);
          }
        }

        $filenames="";
        $attachmentUrl = null;
        $type="Acceptance_Documents";
        $path = "/private/upload/DO/".$type;
        if(isset($input['do']))
        {
          if ($input['do'] != null || $input['do'] != "") {
            $uploadcount=count($input['do']);
              for ($i=0; $i <$uploadcount ; $i++) {
                # code...
                $file = $input['do'][$i];
                $destinationPath=public_path().$path;
                $extension = $file->getClientOriginalExtension();
                $originalName=$file->getClientOriginalName();
                $fileSize= $file->getSize();
                $fileName=time()."_".$i.".".$extension;
                $upload_success = $file->move($destinationPath, $fileName);
                $insert=DB::table('files')->insertGetId(
                    ['Type' => $type,
                     'TargetId' => $input['trackerid'],
                     'salesorderid' => $input['salesorderid'],
                     'File_Name' => $originalName,
                     'File_Size' => $fileSize,
                     'Document_Type' => "DO",
                     'Web_Path' => $path."/".$fileName
                    ]
                );
                $attachmentUrl = url($path."/".$fileName);
                $filenames.= $insert."|".$attachmentUrl."|" .$originalName.",";
            }
            DB::Table('tracker')
            ->where('Id','=',$input['trackerid'])
            ->update([
                'DO' => $today
            ]);
        }
      }

      $filenames="";
        $attachmentUrl = null;
        $type="Acceptance_Documents";
        $path = "/private/upload/BOQ/".$type;
        if(isset($input['boq']))
        {
          if ($input['boq'] != null || $input['boq'] != "") {
            $uploadcount=count($input['boq']);
              for ($i=0; $i <$uploadcount ; $i++) {
                # code...
                $file = $input['boq'][$i];
                $destinationPath=public_path().$path;
                $extension = $file->getClientOriginalExtension();
                $originalName=$file->getClientOriginalName();
                $fileSize= $file->getSize();
                $fileName=time()."_".$i.".".$extension;
                $upload_success = $file->move($destinationPath, $fileName);
                $insert=DB::table('files')->insertGetId(
                    ['Type' => $type,
                     'TargetId' => $input['trackerid'],
                     'salesorderid' => $input['salesorderid'],
                     'File_Name' => $originalName,
                     'File_Size' => $fileSize,
                     'Document_Type' => "BOQ",
                     'Web_Path' => $path."/".$fileName
                    ]
                );
                $attachmentUrl = url($path."/".$fileName);
                $filenames.= $insert."|".$attachmentUrl."|" .$originalName.",";
            }

            DB::Table('tracker')
            ->where('Id','=',$input['trackerid'])
            ->update([
                'BOQ' => $today
            ]);
        }
      }

      $filenames="";
        $attachmentUrl = null;
        $type="Acceptance_Documents";
        $path = "/private/upload/jac/".$type;
        if(isset($input['jac']))
        {
          if ($input['jac'] != null || $input['jac'] != "") {
            $uploadcount=count($input['jac']);
              for ($i=0; $i <$uploadcount ; $i++) {
                # code...
                $file = $input['jac'][$i];
                $destinationPath=public_path().$path;
                $extension = $file->getClientOriginalExtension();
                $originalName=$file->getClientOriginalName();
                $fileSize= $file->getSize();
                $fileName=time()."_".$i.".".$extension;
                $upload_success = $file->move($destinationPath, $fileName);
                $insert=DB::table('files')->insertGetId(
                    ['Type' => $type,
                     'TargetId' => $input['trackerid'],
                     'salesorderid' => $input['salesorderid'],
                     'File_Name' => $originalName,
                     'File_Size' => $fileSize,
                     'Document_Type' => "JAC",
                     'Web_Path' => $path."/".$fileName
                    ]
                );
                $attachmentUrl = url($path."/".$fileName);
                $filenames.= $insert."|".$attachmentUrl."|" .$originalName.",";
            }

            DB::Table('tracker')
            ->where('Id','=',$input['trackerid'])
            ->update([
                'JAC' => $today
            ]);
        }
      }

      $filenames="";
        $attachmentUrl = null;
        $type="Acceptance_Documents";
        $path = "/private/upload/site_report/".$type;
        if(isset($input['site_report']))
        {
          if ($input['site_report'] != null || $input['site_report'] != "") {
            $uploadcount=count($input['site_report']);
              for ($i=0; $i <$uploadcount ; $i++) {
                # code...
                $file = $input['site_report'][$i];
                $destinationPath=public_path().$path;
                $extension = $file->getClientOriginalExtension();
                $originalName=$file->getClientOriginalName();
                $fileSize= $file->getSize();
                $fileName=time()."_".$i.".".$extension;
                $upload_success = $file->move($destinationPath, $fileName);
                $insert=DB::table('files')->insertGetId(
                    ['Type' => $type,
                     'TargetId' => $input['trackerid'],
                     'salesorderid' => $input['salesorderid'],
                     'File_Name' => $originalName,
                     'File_Size' => $fileSize,
                     'Document_Type' => "SITE REPORT",
                     'Web_Path' => $path."/".$fileName
                    ]
                );
                $attachmentUrl = url($path."/".$fileName);
                $filenames.= $insert."|".$attachmentUrl."|" .$originalName.",";
            }
            DB::Table('tracker')
            ->where('Id','=',$input['trackerid'])
            ->update([
                'Site Report' => $today
            ]);
        }
      }

      $filenames="";
        $attachmentUrl = null;
        $type="Acceptance_Documents";
        $path = "/private/upload/boq_rom/".$type;
        if(isset($input['boq_rom']))
        {
          if ($input['boq_rom'] != null || $input['boq_rom'] != "") {
            $uploadcount=count($input['boq_rom']);
              for ($i=0; $i <$uploadcount ; $i++) {
                # code...
                $file = $input['boq_rom'][$i];
                $destinationPath=public_path().$path;
                $extension = $file->getClientOriginalExtension();
                $originalName=$file->getClientOriginalName();
                $fileSize= $file->getSize();
                $fileName=time()."_".$i.".".$extension;
                $upload_success = $file->move($destinationPath, $fileName);
                $insert=DB::table('files')->insertGetId(
                    ['Type' => $type,
                     'TargetId' => $input['trackerid'],
                     'salesorderid' => $input['salesorderid'],
                     'File_Name' => $originalName,
                     'File_Size' => $fileSize,
                     'Document_Type' => "BOQ APPROVED BY ROM",
                     'Web_Path' => $path."/".$fileName
                    ]
                );
                $attachmentUrl = url($path."/".$fileName);
                $filenames.= $insert."|".$attachmentUrl."|" .$originalName.",";
            }

            DB::Table('tracker')
            ->where('Id','=',$input['trackerid'])
            ->update([
                'BOQ Approved By ROM' => $today
            ]);
        }
      }

      $filenames="";
        $attachmentUrl = null;
        $type="Acceptance_Documents";
        $path = "/private/upload/boq_hq/".$type;
        if(isset($input['boq_hq']))
        {
          if ($input['boq_hq'] != null || $input['boq_hq'] != "") {
            $uploadcount=count($input['boq_hq']);
              for ($i=0; $i <$uploadcount ; $i++) {
                # code...
                $file = $input['boq_hq'][$i];
                $destinationPath=public_path().$path;
                $extension = $file->getClientOriginalExtension();
                $originalName=$file->getClientOriginalName();
                $fileSize= $file->getSize();
                $fileName=time()."_".$i.".".$extension;
                $upload_success = $file->move($destinationPath, $fileName);
                $insert=DB::table('files')->insertGetId(
                    ['Type' => $type,
                     'TargetId' => $input['trackerid'],
                     'salesorderid' => $input['salesorderid'],
                     'File_Name' => $originalName,
                     'File_Size' => $fileSize,
                     'Document_Type' => "BOQ APPROVED BY OPERATION HQ",
                     'Web_Path' => $path."/".$fileName
                    ]
                );
                $attachmentUrl = url($path."/".$fileName);
                $filenames.= $insert."|".$attachmentUrl."|" .$originalName.",";
            }

            DB::Table('tracker')
            ->where('Id','=',$input['trackerid'])
            ->update([
                'BOQ Approved By Operation HQ' => $today
            ]);
        }
      }

      $filenames="";
        $attachmentUrl = null;
        $type="Acceptance_Documents";
        $path = "/private/upload/boq_director/".$type;
        if(isset($input['boq_director']))
        {
          if ($input['boq_director'] != null || $input['boq_director'] != "") {
            $uploadcount=count($input['boq_director']);
              for ($i=0; $i <$uploadcount ; $i++) {
                # code...
                $file = $input['boq_director'][$i];
                $destinationPath=public_path().$path;
                $extension = $file->getClientOriginalExtension();
                $originalName=$file->getClientOriginalName();
                $fileSize= $file->getSize();
                $fileName=time()."_".$i.".".$extension;
                $upload_success = $file->move($destinationPath, $fileName);
                $insert=DB::table('files')->insertGetId(
                    ['Type' => $type,
                     'TargetId' => $input['trackerid'],
                     'salesorderid' => $input['salesorderid'],
                     'File_Name' => $originalName,
                     'File_Size' => $fileSize,
                     'Document_Type' => "BOQ APPROVED BY DIRECTOR OF OPERATION",
                     'Web_Path' => $path."/".$fileName
                    ]
                );
                $attachmentUrl = url($path."/".$fileName);
                $filenames.= $insert."|".$attachmentUrl."|" .$originalName.",";
            }

            DB::Table('tracker')
            ->where('Id','=',$input['trackerid'])
            ->update([
                'BOQ Approved By Director Of Operation' => $today
            ]);
        }
      }

      $filenames="";
        $attachmentUrl = null;
        $type="Acceptance_Documents";
        $path = "/private/upload/po/".$type;
        if(isset($input['po']))
        {
          if ($input['po'] != null || $input['po'] != "") {
            $uploadcount=count($input['po']);
              for ($i=0; $i <$uploadcount ; $i++) {
                # code...
                $file = $input['po'][$i];
                $destinationPath=public_path().$path;
                $extension = $file->getClientOriginalExtension();
                $originalName=$file->getClientOriginalName();
                $fileSize= $file->getSize();
                $fileName=time()."_".$i.".".$extension;
                $upload_success = $file->move($destinationPath, $fileName);
                $insert=DB::table('files')->insertGetId(
                    ['Type' => $type,
                     'TargetId' => $input['trackerid'],
                     'salesorderid' => $input['salesorderid'],
                     'File_Name' => $originalName,
                     'File_Size' => $fileSize,
                     'Document_Type' => "PO",
                     'Web_Path' => $path."/".$fileName
                    ]
                );
                $attachmentUrl = url($path."/".$fileName);
                $filenames.= $insert."|".$attachmentUrl."|" .$originalName.",";
            }

            DB::Table('tracker')
            ->where('Id','=',$input['trackerid'])
            ->update([
                'PO' => $today
            ]);
        }
      }

      $filenames="";
        $attachmentUrl = null;
        $type="Acceptance_Documents";
        $path = "/private/upload/work_order/".$type;
        if(isset($input['work_order']))
        {
          if ($input['WORK ORDER'] != null || $input['work_order'] != "") {
            $uploadcount=count($input['work_order']);
              for ($i=0; $i <$uploadcount ; $i++) {
                # code...
                $file = $input['work_order'][$i];
                $destinationPath=public_path().$path;
                $extension = $file->getClientOriginalExtension();
                $originalName=$file->getClientOriginalName();
                $fileSize= $file->getSize();
                $fileName=time()."_".$i.".".$extension;
                $upload_success = $file->move($destinationPath, $fileName);
                $insert=DB::table('files')->insertGetId(
                    ['Type' => $type,
                     'TargetId' => $input['trackerid'],
                     'salesorderid' => $input['salesorderid'],
                     'File_Name' => $originalName,
                     'File_Size' => $fileSize,
                     'Document_Type' => "WORK ORDER",
                     'Web_Path' => $path."/".$fileName
                    ]
                );
                $attachmentUrl = url($path."/".$fileName);
                $filenames.= $insert."|".$attachmentUrl."|" .$originalName.",";
            }

            DB::Table('tracker')
            ->where('Id','=',$input['trackerid'])
            ->update([
                'Work Order' => $today
            ]);
        }
      }

      $filenames="";
        $attachmentUrl = null;
        $type="Acceptance_Documents";
        $path = "/private/upload/coc/".$type;
        if(isset($input['coc']))
        {
          if ($input['coc'] != null || $input['coc'] != "") {
            $uploadcount=count($input['coc']);
              for ($i=0; $i <$uploadcount ; $i++) {
                # code...
                $file = $input['coc'][$i];
                $destinationPath=public_path().$path;
                $extension = $file->getClientOriginalExtension();
                $originalName=$file->getClientOriginalName();
                $fileSize= $file->getSize();
                $fileName=time()."_".$i.".".$extension;
                $upload_success = $file->move($destinationPath, $fileName);
                $insert=DB::table('files')->insertGetId(
                    ['Type' => $type,
                     'TargetId' => $input['trackerid'],
                     'salesorderid' => $input['salesorderid'],
                     'File_Name' => $originalName,
                     'File_Size' => $fileSize,
                     'Document_Type' => "COC",
                     'Web_Path' => $path."/".$fileName
                    ]
                );
                $attachmentUrl = url($path."/".$fileName);
                $filenames.= $insert."|".$attachmentUrl."|" .$originalName.",";
            }

            DB::Table('tracker')
            ->where('Id','=',$input['trackerid'])
            ->update([
                'COC' => $today
            ]);
        }
      }


      $filenames="";
        $attachmentUrl = null;
        $type="Acceptance_Documents";
        $path = "/private/upload/payment_checklist/".$type;
        if(isset($input['payment_checklist']))
        {
          if ($input['payment_checklist'] != null || $input['payment_checklist'] != "") {
            $uploadcount=count($input['payment_checklist']);
              for ($i=0; $i <$uploadcount ; $i++) {
                # code...
                $file = $input['payment_checklist'][$i];
                $destinationPath=public_path().$path;
                $extension = $file->getClientOriginalExtension();
                $originalName=$file->getClientOriginalName();
                $fileSize= $file->getSize();
                $fileName=time()."_".$i.".".$extension;
                $upload_success = $file->move($destinationPath, $fileName);
                $insert=DB::table('files')->insertGetId(
                    ['Type' => $type,
                     'TargetId' => $input['trackerid'],
                     'salesorderid' => $input['salesorderid'],
                     'File_Name' => $originalName,
                     'File_Size' => $fileSize,
                     'Document_Type' => "PAYMENT CHECKLIST",
                     'Web_Path' => $path."/".$fileName
                    ]
                );
                $attachmentUrl = url($path."/".$fileName);
                $filenames.= $insert."|".$attachmentUrl."|" .$originalName.",";
            }

            DB::Table('tracker')
            ->where('Id','=',$input['trackerid'])
            ->update([
                'Payment Checklist' => $today
            ]);

        }
      }

      $filenames="";
        $attachmentUrl = null;
        $type="Acceptance_Documents";
        $path = "/private/upload/audit_form/".$type;
        if(isset($input['audit_form']))
        {
          if ($input['audit_form'] != null || $input['audit_form'] != "") {
            $uploadcount=count($input['audit_form']);
              for ($i=0; $i <$uploadcount ; $i++) {
                # code...
                $file = $input['audit_form'][$i];
                $destinationPath=public_path().$path;
                $extension = $file->getClientOriginalExtension();
                $originalName=$file->getClientOriginalName();
                $fileSize= $file->getSize();
                $fileName=time()."_".$i.".".$extension;
                $upload_success = $file->move($destinationPath, $fileName);
                $insert=DB::table('files')->insertGetId(
                    ['Type' => $type,
                     'TargetId' => $input['trackerid'],
                     'salesorderid' => $input['salesorderid'],
                     'File_Name' => $originalName,
                     'File_Size' => $fileSize,
                     'Document_Type' => "AUDIT FORM",
                     'Web_Path' => $path."/".$fileName
                    ]
                );
                $attachmentUrl = url($path."/".$fileName);
                $filenames.= $insert."|".$attachmentUrl."|" .$originalName.",";
            }

            DB::Table('tracker')
            ->where('Id','=',$input['trackerid'])
            ->update([
                'Audit Form' => $today
            ]);
        }
      }


        return 1;
    }

   public function generate($trackerid =null)
    {
      $me = (new CommonController)->get_current_user();

      $so = DB::table('salesorder')
      ->select('Id','SO_Number')
      ->where('trackerid','=',$trackerid)
      ->first();

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

      if($trackerid == null)
      {
        return 0;
      }
      else
      {
        DB::table('salesorder')
        ->where('Id','=',$so->Id)
        ->update(['SO_Number'=>$sonumber,'parentId'=>$so->Id]);

         DB::table('salesorderdetails')
        ->insert([
        'salesorderId' => $so->Id,
        'details' => "Sales Order Generated",
        'userId' => $me->UserId,
        'created_at' => Carbon::Now()
        ]);
        
        DB::table('tracker')
        ->where('Id','=',$trackerid)
        ->update(['sales_order'=>1]);

        return 1;
      }
    }

  public function fetchfiles($salesorderid,$trackerid)
  {
    $item = DB::table('files')
    ->select('Type','Web_Path')
    ->where('TargetId','=',$trackerid)
    ->where('salesorderid','=',$salesorderid)
    ->get();

    return response()->json(['Item' => $item]);
  }

   public function salesorderdetails(Request $request, $sonumber=null)
    {
      $me = (new CommonController)->get_current_user();


      $details = DB::table('tracker')
      // ->leftJoin( DB::raw('(select Max(Id) as maxid,trackerid from salesorder Group By trackerid) as max'), 'max.trackerid', '=', 'tracker.Id')
      ->leftJoin('salesorder', 'salesorder.trackerid', '=', 'tracker.Id')
      // ->leftjoin('tracker','tracker.Id','=','salesorder.trackerid')
      ->leftjoin('companies','companies.Id','=','salesorder.companyId')
      ->leftjoin('companies as client','client.Id','=','salesorder.clientId')
      ->select('salesorder.*','companies.Company_Name','tracker.sales_order','client.Company_Name as client')
      ->where('salesorder.SO_Number','=',$sonumber)
      ->first();

			$trackerid=$details->trackerid;

      $site = DB::table('tracker')
      ->select(DB::raw('tracker.`Site Name` as site'))
      ->where('Id','=',$trackerid)
      ->first();

      //  $item = DB::table('inventories')
      // ->leftjoin('inventoryvendor','inventoryvendor.InventoryId','=','inventories.Id')
      // ->select('inventories.Id','inventories.Item_Code','inventories.Description','inventories.Unit','inventoryvendor.Item_Price')
      // ->where('inventories.Categories','=','SPEEDFREAK')
      // ->get();

      //  $item = DB::table('inventories')
      // ->leftjoin('inventoryvendor','inventoryvendor.InventoryId','=','inventories.Id')
      // ->select('inventories.Id','inventories.Item_Code','inventories.Description','inventories.Unit','inventoryvendor.Item_Price')
      // ->where('inventories.Categories','=','SPEEDFREAK')
      // ->get();

      $item = array();

      $company = DB::table('companies')
      ->select('Id','Company_Name','Company_Code')
      ->whereIn('Id',[1,2,3,66,259,335])
      ->get();

      $client = DB::table('companies')
      ->select('Id','Company_Name','Company_Code')
      ->where('Client','=','Yes')
      ->get();

      if($details == null || $details == "")
      {
      $existitem = array();
      $log = "";
      }
      else
      {
      // $existitem = DB::table('inventories')
      // ->leftjoin('salesorderitem','salesorderitem.inventoryId','=','inventories.Id')
      // ->leftjoin('inventoryvendor','inventoryvendor.InventoryId','=','inventories.Id')
      // ->select('inventories.Id','inventories.Item_Code','inventories.Description','inventories.Unit','salesorderitem.price','salesorderitem.qty')
      // ->where('inventories.Categories','=','SPEEDFREAK')
      // ->where('salesorderitem.salesorderId','=',$details->Id)
      // ->get();

      $existitem = DB::table('salesorderitem')
      ->select('description','qty','price','unit','item_no')
      ->where('salesorderitem.salesorderId','=',$details->Id)
      ->get();

      $item = DB::table('salesorderitem')
      ->select('description','price','unit','item_no')
      ->where('clientId','=',$details->clientId)
      ->where('exclude',0)
      ->groupby('description')
      ->get();

      $log = DB::table('salesorderdetails')
      ->leftjoin('users','users.Id','=','salesorderdetails.userId')
      ->select('salesorderdetails.Id','salesorderdetails.details','salesorderdetails.created_at','users.Name')
      ->where('salesorderdetails.salesorderId','=',$details->Id)
      ->get();
      }

      $recur = DB::table('tracker')
      ->where('Id','=',$trackerid)
      ->select('recurring')
      ->first();

      $term = DB::table('options')
      ->select('Option')
      ->where('Table','=','salesorder')
      ->where('Field','=','Term')
      ->get();

      return view('salesorderdetails', ['me' => $me,'company'=>$company,'client'=>$client,'item'=>$item,'trackerid'=>$trackerid,'details'=>$details,'existitem'=>$existitem,'log'=>$log,'site'=>$site,'recur'=>$recur,'term'=>$term]);

  }

	public function salesorderdetails2(Request $request, $trackerid=null)
	 {
		 $me = (new CommonController)->get_current_user();


		 $details = DB::table('tracker')
		 // ->leftJoin( DB::raw('(select Max(Id) as maxid,trackerid from salesorder Group By trackerid) as max'), 'max.trackerid', '=', 'tracker.Id')
		 ->leftJoin('salesorder', 'salesorder.trackerid', '=', 'tracker.Id')
		 // ->leftjoin('tracker','tracker.Id','=','salesorder.trackerid')
		 ->leftjoin('companies','companies.Id','=','salesorder.companyId')
		 ->leftjoin('companies as client','client.Id','=','salesorder.clientId')
		 ->select('salesorder.*','companies.Company_Name','tracker.sales_order','client.Company_Name as client')
		 ->where('salesorder.TrackerId','=',$trackerid)
		 ->first();

		 $site = DB::table('tracker')
		 ->select(DB::raw('tracker.`Site Name` as site'))
		 ->where('Id','=',$trackerid)
		 ->first();

		 $item = array();

     $company = DB::table('companies')
     ->select('Id','Company_Name','Company_Code')
     ->whereIn('Id',[1,2,3,66,259,335])
     ->get();

     $client = DB::table('companies')
     ->select('Id','Company_Name','Company_Code')
     ->where('Client','=','Yes')
     ->get();

		 if($details == null || $details == "")
		 {

		 $existitem = array();
		 $log = "";
		 }
		 else
		 {
		 // $existitem = DB::table('inventories')
		 // ->leftjoin('salesorderitem','salesorderitem.inventoryId','=','inventories.Id')
		 // ->leftjoin('inventoryvendor','inventoryvendor.InventoryId','=','inventories.Id')
		 // ->select('inventories.Id','inventories.Item_Code','inventories.Description','inventories.Unit','salesorderitem.price','salesorderitem.qty')
		 // ->where('inventories.Categories','=','SPEEDFREAK')
		 // ->where('salesorderitem.salesorderId','=',$details->Id)
		 // ->get();

		 $existitem = DB::table('salesorderitem')
		 ->select('description','qty','price','unit','item_no')
		 ->where('salesorderitem.salesorderId','=',$details->Id)
		 ->get();

		 $item = DB::table('salesorderitem')
		 ->select('description','price','unit','item_no')
		 ->where('clientId','=',$details->clientId)
		 ->groupby('description')
		 ->get();

		 $log = DB::table('salesorderdetails')
		 ->leftjoin('users','users.Id','=','salesorderdetails.userId')
		 ->select('salesorderdetails.Id','salesorderdetails.details','salesorderdetails.created_at','users.Name')
		 ->where('salesorderdetails.salesorderId','=',$details->Id)
		 ->get();
		 }

		 $recur = DB::table('tracker')
		 ->where('Id','=',$trackerid)
		 ->select('recurring')
		 ->first();

		 $term = DB::table('options')
		 ->select('Option')
		 ->where('Table','=','salesorder')
		 ->where('Field','=','Term')
		 ->get();

		 return view('salesorderdetails', ['me' => $me,'company'=>$company,'client'=>$client,'item'=>$item,'trackerid'=>$trackerid,'details'=>$details,'existitem'=>$existitem,'log'=>$log,'site'=>$site,'recur'=>$recur,'term'=>$term]);

 }

  public function salessummary()
   {
     $me = (new CommonController)->get_current_user();
     $end = date('M-Y', strtotime('today'));
     $days = date('t',strtotime($end));
     $start = date('M-Y', strtotime('today - 1 year + '.$days.'days'));
     if(strpos($end,"Jan") !== false)
     {
      $start = "Feb".substr($start, 3);
     }
    
     $column = "";
     $boundary = $start;
     for($i=0 ; $boundary != $end; $i++)
     {  
        $boundary = date('M-Y', strtotime($start."+".$i."month"));
        $column = $column.'(SELECT ROUND( (SUM(total_amount) - SUM(IFNULL(creditnote.amount,0)) ),2) FROM salesorder LEFT JOIN tracker ON tracker.Id = salesorder.trackerid LEFT JOIN creditnote ON salesorder.Id = creditnote.salesorderId WHERE salesorder.clientId = companies.Id AND tracker.Id > 0 AND rental_start LIKE "%'.$boundary.'%" GROUP BY clientId) as `'.$boundary.'`,';
      }
      $column = rtrim($column,",");
      $summary = DB::table('salesorder')
      ->leftJoin('companies','companies.Id','=','salesorder.clientId')
      // ->leftJoin('salesorderitem','salesorderitem.salesorderId','=','salesorder.Id')
      ->leftjoin('companies as internal','internal.Id','=','salesorder.companyId')  
      ->select('companies.Id','companies.Company_Name','internal.Company_Code','companies.type',DB::raw($column))
      ->groupby('salesorder.clientId')
      ->get();

     return view('salessummary', ['me' => $me,'summary'=>$summary]);
   }

   public function jdnisummary()
   {
     $me = (new CommonController)->get_current_user();
     $end = date('M-Y', strtotime('today'));
     $start = date('M-Y', strtotime(' today - 1 year + 1 month '));
     if(strpos($end,"Jan") !== false)
     {
      $start = "Feb".substr($start, 3);
     }
     $column = "";
     $boundary = $start;
     for($i=0 ; $boundary != $end; $i++)
     {  
        $boundary = date('M-Y', strtotime($start."+".$i."month"));
        $column = $column.'(SELECT ROUND(SUM(total_amount),2) FROM salesorder LEFT JOIN tracker ON tracker.Id = salesorder.trackerid WHERE salesorder.clientId = companies.Id AND salesorder.invoice = 0 AND tracker.Id > 0 AND STR_TO_DATE(rental_start,"%d-%M-%Y") BETWEEN STR_TO_DATE("01-'.$boundary.'","%d-%M-%Y") AND STR_TO_DATE("31-'.$boundary.'","%d-%M-%Y") GROUP BY clientid) as `'.$boundary."`,";
      }
      $column = rtrim($column,",");
      $summary = DB::table('salesorder')
      ->leftJoin('companies','companies.Id','=','salesorder.clientId')
      ->leftjoin('companies as internal','internal.Id','=','salesorder.companyId')
      ->select('companies.Id','companies.Company_Name','internal.Company_Code','companies.type',DB::raw($column))
      ->groupby('salesorder.clientId')
      ->get();

     return view('jdnisummary', ['me' => $me,'summary'=>$summary]);
   }

  public function batchprint(Request $request)
  {
    $input = $request->all();
    $files = DB::Table('files')
    ->select('Id','Web_Path')
    ->whereIn('salesorderid',$input['Id'])
    ->where('Type','=','ARinvoice')
    ->get();
    return response()->json(['files' => $files]);
  }

  public function invoicelist($start = null, $end = null)
  {
    $me = (new CommonController)->get_current_user();
    if ($start==null)
        {
            $start=date('d-M-Y', strtotime('first day of last month'));
        }
        if ($end==null)
        {
            $end=date('d-M-Y', strtotime('last day of this month'));
        }

    $details = DB::table('salesorder')
    ->select('max.maxid','salesorder.Id','salesorder.invoice_number','salesorder.combined_invoice_num','salesorder.SO_Number','max.DO_No','max.temp_DO',DB::raw('tracker.`Site Name` as site'),'companies.Company_Name','client.Company_Name as client','client.type','salesorder.invoice_date','salesorder.combined_invoice_date','salesorder.total_amount','combine_remarks')
    ->leftjoin('companies','companies.Id','=','salesorder.companyId')
    ->leftjoin('companies as client','client.Id','=','salesorder.clientId')
    ->leftjoin(DB::raw('(SELECT max(Id) as maxid, salesorderid, DO_No , temp_DO from deliveryform group by salesorderid)as max'),'salesorder.Id','=',DB::raw('max.salesorderid'))
    ->leftjoin('tracker','tracker.Id','=','salesorder.trackerid')
    ->where('salesorder.invoice',1)
    ->whereRaw("STR_TO_DATE(salesorder.invoice_date,'%d-%M-%Y') BETWEEN STR_TO_DATE('".$start."','%d-%M-%Y') AND  STR_TO_DATE('".$end."','%d-%M-%Y')")
    ->get();

     return view('invoicelist', ['me' => $me,'details'=>$details,'start'=>$start,'end'=>$end]);
  }

  public function generateinvoice(Request $request)
  {
    $input = $request->all();
     $me = (new CommonController)->get_current_user();

     $company = DB::table('salesorder')
    ->leftjoin('companies','companies.Id','=','salesorder.companyId')
    ->select("Initial")
    ->where('salesorder.Id','=',$input['Id'])
    ->first();

    $indicator = $company->Initial.Carbon::now()->format('y').Carbon::now()->format('m');
     $max = DB::SELECT('SELECT MAX(invoice_number) as invoice_number FROM (select Max(invoice_number) as invoice_number from salesorder where `invoice_number` LIKE 
        "'.$indicator.'%"
        UNION 
        select Max(combined_invoice_num) as invoice_number from salesorder where combined_invoice_num LIKE "'.$indicator.'%") AS A');
      $maxinv = json_decode(json_encode($max),true);

      if($maxinv[0]['invoice_number'] == "" || $maxinv[0]['invoice_number'] == null)
      {
        $counter = 0;
      }
      else
      {
        $counter = substr($maxinv[0]['invoice_number'], 6);
        $counter = $counter + 1;
      }
      $invoicenumber = $indicator.str_pad($counter,3,'0',STR_PAD_LEFT);
  
    // $checking=DB::table('salesorder')
    //             ->where('invoice_number','LIKE',$company->Initial.Carbon::now()->format('y').Carbon::now()->format('m').'%')
    //             ->select(DB::raw("Max(invoice_number) as invoice_number"))
    //             // ->orderBy('Id','DESC')
    //             ->first();
    //             $temp="";
    //             if($checking)
    //             {
    //             $temp = substr($checking->invoice_number,6,8);
    //             }
    //             $generate = "";
    //             $generate.=$company->Initial.Carbon::now()->format('y').Carbon::now()->format('m');
    //             $checking == null ? $conv=sprintf("%03s",1):$conv=sprintf("%03s",$temp+1);
    //             $generate = $generate.$conv;
    DB::table('salesorder')
    ->where('salesorder.Id','=',$input['Id'])
    ->update([
        'invoice_number' => $invoicenumber,
        'invoice' => 1,
        'invoice_date' => date("d-M-Y", strtotime('today'))
    ]);

    $this->invoicepdf($input['Id']);

    DB::table('salesorderdetails')
    ->Insert([
        'salesorderId' => $input['Id'],
        'details' => "Invoice Has Been Generated",
        'userId' => $me->UserId,
        'created_at' => Carbon::now()
    ]);

    return 1;
  }

  public function activate(Request $request)
    {
      $me = (new CommonController)->get_current_user();
      $input = $request->all();
      DB::table('tracker')
      ->where('Id','=',$input['Id'])
      ->update([
        'recurring' => 1
      ]);

      $salesorder = DB::table('salesorder')
      ->select('Id','rental_start','rental_end')
      ->where('Id','=',$input['soid'])
      ->first();

      $item = DB::table('salesorderitem')
      ->select('salesorderitem.*')
      ->where('salesorderitem.salesorderId','=',$input['soid'])
      ->get();

      $rentalend = date("d-M-Y", strtotime("+1month -1 days",strtotime($salesorder->rental_start)));
        $days = date("t",strtotime($salesorder->rental_start));
        $diff = strtotime($salesorder->rental_end) - strtotime($salesorder->rental_start);
        $diff = ($diff / (60*60*24) ) +1;
            foreach($item as $i => $val)
            {
            $charges = round(($val->price/$diff)*$days);
            DB::table('salesorderitem')
            ->where('Id','=',$val->Id)
            ->update([
                'price'=> $charges
            ]);
            }
            DB::table('salesorder')
            ->where('Id','=',$input['soid'])
            ->update([
                'rental_end'=> $rentalend
            ]);
      DB::table('salesorderdetails')
      ->insertGetId([
          'salesorderId' => $input['soid'],
          'details' => "Sales Order Recurring Reactivated",
          'userId' => $me->UserId,
          'created_at' => Carbon::now()
      ]);
      return 1;
    }

   public function getitem(Request $request)
    {
      $me = (new CommonController)->get_current_user();
      $input = $request->all();

      $item = DB::table('salesorderitem')
      ->select('salesorderitem.description','salesorderitem.price','salesorderitem.unit')
      ->where('salesorderitem.clientId','=',$input['clientId'])
      ->groupby('description')
      ->get();

      return response()->json(['item' => $item]);
    }

   public function salesordertemplate($salesorderid,$history =null)
    {
      $me = (new CommonController)->get_current_user();

      $so = DB::table('salesorder')
      ->leftjoin('companies','companies.Id','=','salesorder.companyId')
      ->leftjoin('companies as client','client.Id','=','salesorder.clientId')
      ->leftjoin('tracker','tracker.Id','=','salesorder.trackerid')
      ->select('salesorder.Id','companies.Company_Name','companies.Address','companies.Contact_No','companies.Office_No','companies.Fax_No','client.Company_Name as client_company','client.Address as client_address','client.Contact_No as client_no','client.Office_No as client_office','client.Fax_No as client_fax','client.attention','salesorder.*',DB::raw('tracker.`Site Name` as site'),'client.type')
      ->where('salesorder.Id','=',$salesorderid)
      ->first();

      $item = DB::table('salesorderitem')
      ->select('salesorderitem.*',DB::raw(" CAST((salesorderitem.price * salesorderitem.qty) AS DECIMAL(12,2)) as total"))
      ->where('salesorderitem.salesorderId','=',$so->Id)
      ->get();

      $total = json_decode(json_encode($item),true);
      $grandtotal1 = 0;
      for($i=0;$i<count($total);$i++)
      {
          $grandtotal1 = $grandtotal1 + $total[$i]['total'];
      }
      $grandtotal = number_format((float)$grandtotal1,2,'.','');

      if($grandtotal != "" || $grandtotal != null)
      {
        $words = $this->convertNumber($grandtotal);
        $words = strtoupper($words);
      }
       return view('salesordertemplate',['me' => $me,'so'=>$so,'item'=>$item,'grandtotal'=>$grandtotal,'words'=>$words,'history'=>$history]);
      }

      public function invoicetemplate($sonum)
    {
    $me = (new CommonController)->get_current_user();

      $invoice = DB::table('salesorder')
      ->leftJoin('companies','companies.Id','=','salesorder.companyId')
      ->leftJoin('companies as client','client.Id','=','salesorder.clientId')
      ->leftjoin('deliveryform','deliveryform.salesorderid','=','salesorder.Id')
      ->leftjoin('tracker','tracker.Id','=','salesorder.trackerid')
      ->select('companies.Company_Name','companies.Address','companies.Contact_No','companies.Office_No','companies.Fax_No','client.Company_Name as client_company','client.Address as client_address','client.Contact_No as client_no','client.Office_No as client_office','client.Fax_No as client_fax','client.attention','salesorder.*',DB::raw('tracker.`Site Name` as site'),'companies.bank_acct','companies.bank','client.type','deliveryform.DO_No','salesorder.remarks')
      ->where('salesorder.SO_Number','=',$sonum)
      // ->where('deliveryform.Purpose','=',1814)
      ->orderBy('deliveryform.Id','DESC')
      ->first();

      // $item = DB::table('salesorderitem')
      // ->select('salesorderitem.*',DB::raw("round(salesorderitem.price * salesorderitem.qty,2) as total"))
      // ->where('salesorderitem.salesorderId','=',$invoice->Id)
      // ->get();
      $formid = DB::table('deliveryform')
      ->select('Id','DO_No')
      ->where('deliveryform.Purpose','=',1814)
      ->where('salesorderid','=',$invoice->Id)
      ->orderBy('Id','ASC')
      ->first();

      if($formid == null)
      {
        $soitem = DB::table('salesorderitem')
        ->leftjoin('salesorder','salesorder.Id','=','salesorderitem.salesorderId')
        ->select('salesorderitem.*',DB::raw("CAST((salesorderitem.price * salesorderitem.qty) AS DECIMAL(12,2)) as total"))
        ->where('salesorder.Id','=',$invoice->Id)
        ->get();
        $item = array();
        $tray = array();
        $machine = array();
      }
      else
      {
      $soitem = DB::table('salesorderitem')
      ->leftjoin('salesorder','salesorder.Id','=','salesorderitem.salesorderId')
      ->leftjoin('deliveryform','deliveryform.salesorderid','=','salesorder.Id')
      ->select('salesorderitem.*',DB::raw("CAST((salesorderitem.price * salesorderitem.qty) AS DECIMAL(12,2)) as total"))
      ->where('deliveryform.Id','=',$formid->Id)
      ->get();

      $item = DB::table('deliveryitem')
      ->leftjoin('inventories','inventories.Id','=','deliveryitem.inventoryId')
      ->leftjoin('speedfreakinventory','speedfreakinventory.machinery_no','=','inventories.Item_Code')
      ->select('inventories.Item_Code','speedfreakinventory.engine_no')
      ->where('deliveryitem.formId','=',$formid->Id)
      // ->where('Item_Code','LIKE','SC%')
      ->where('speedfreakinventory.type','=','SPEEDFREAK')
      ->first();

      $tray = DB::table('deliveryitem')
      ->leftjoin('inventories','inventories.Id','=','deliveryitem.inventoryId')
      ->select('inventories.Description','inventories.Item_Code')
      ->where('deliveryitem.formId','=',$formid->Id)
      ->where('Item_Code','LIKE','%Tray%')
      ->first();
      $machine = DB::table('speedfreakinventory')
      ->leftjoin('inventories','inventories.Item_Code','=','speedfreakinventory.machinery_no')
      ->select('inventories.Item_Code','speedfreakinventory.machinery_no','speedfreakinventory.engine_no')
      ->where('inventories.Item_Code','<>',"")
      ->get();
      }
      $words = $this->convertNumber($invoice->total_amount);
      $words = strtoupper($words);
      return view("invoicetemplate",['me'=>$me,'invoice'=>$invoice,'soitem'=>$soitem,'item'=>$item,'tray'=>$tray,'machine'=>$machine,'words'=>$words,'formid'=>$formid]);
    }

     public function invoicetemplate2($id)
    {
    $me = (new CommonController)->get_current_user();

      $invoice = DB::table('salesorder')
      ->leftJoin('companies','companies.Id','=','salesorder.companyId')
      ->leftJoin('companies as client','client.Id','=','salesorder.clientId')
      ->leftjoin('deliveryform','deliveryform.salesorderid','=','salesorder.Id')
      ->leftjoin('tracker','tracker.Id','=','salesorder.trackerid')
      ->select('companies.Company_Name','companies.Address','companies.Contact_No','companies.Office_No','companies.Fax_No','client.Company_Name as client_company','client.Address as client_address','client.Contact_No as client_no','client.Office_No as client_office','client.Fax_No as client_fax','client.attention','salesorder.*',DB::raw('tracker.`Site Name` as site'),'companies.bank_acct','companies.bank','client.type','deliveryform.temp_DO as DO_No')
      ->where('salesorder.id','=',$id)
      ->where('deliveryform.Purpose','=',1814)
      ->orderBy('deliveryform.Id','DESC')
      ->first();
      // $item = DB::table('salesorderitem')
      // ->select('salesorderitem.*',DB::raw("round(salesorderitem.price * salesorderitem.qty,2) as total"))
      // ->where('salesorderitem.salesorderId','=',$invoice->Id)
      // ->get();
      $formid = DB::table('deliveryform')
      ->select('Id','deliveryform.temp_DO as DO_No')
      ->where('deliveryform.Purpose','=',1814)
      ->where('salesorderid','=',$invoice->Id)
      ->orderBy('Id','DESC')
      ->first();


      if($formid == null)
      {
        $soitem = DB::table('salesorderitem')
        ->leftjoin('salesorder','salesorder.Id','=','salesorderitem.salesorderId')
        ->select('salesorderitem.*',DB::raw("CAST((salesorderitem.price * salesorderitem.qty) AS DECIMAL(12,2)) as total"))
        ->where('salesorder.Id','=',$invoice->Id)
        ->get();
        $item = array();
        $tray = array();
        $machine = array();
      }
      else
      {
      $soitem = DB::table('salesorderitem')
      ->leftjoin('salesorder','salesorder.Id','=','salesorderitem.salesorderId')
      ->leftjoin('deliveryform','deliveryform.salesorderid','=','salesorder.Id')
      ->select('salesorderitem.*',DB::raw("CAST((salesorderitem.price * salesorderitem.qty) AS DECIMAL(12,2)) as total"))
      ->where('deliveryform.Id','=',$formid->Id)
      ->get();

      $item = DB::table('deliveryitem')
      ->leftjoin('inventories','inventories.Id','=','deliveryitem.inventoryId')
      ->leftjoin('speedfreakinventory','speedfreakinventory.machinery_no','=','inventories.Item_Code')
      ->select('inventories.Item_Code','speedfreakinventory.engine_no')
      ->where('deliveryitem.formId','=',$formid->Id)
      // ->where('Item_Code','LIKE','SC%')
      ->where('speedfreakinventory.type','=','SPEEDFREAK')
      ->first();

      $tray = DB::table('deliveryitem')
      ->leftjoin('inventories','inventories.Id','=','deliveryitem.inventoryId')
      ->select('inventories.Description','inventories.Item_Code')
      ->where('deliveryitem.formId','=',$formid->Id)
      ->where('Item_Code','LIKE','%Tray%')
      ->first();

      $machine = DB::table('speedfreakinventory')
      ->leftjoin('inventories','inventories.Item_Code','=','speedfreakinventory.machinery_no')
      ->select('inventories.Item_Code','speedfreakinventory.machinery_no','speedfreakinventory.engine_no')
      ->where('inventories.Item_Code','<>',"")
      ->get();
      }
      $words = $this->convertNumber($invoice->total_amount);
      $words = strtoupper($words);
      return view("invoicetemplate",['me'=>$me,'invoice'=>$invoice,'soitem'=>$soitem,'item'=>$item,'tray'=>$tray,'machine'=>$machine,'words'=>$words,'formid'=>$formid]);
    }

    public function combineinvoicetemplate($id)
    {
      $me = (new CommonController)->get_current_user();
      $combinenum = DB::table('salesorder')
      ->where('Id','=',$id)
      ->select('combined_invoice_num')
      ->first();

      $list = DB::table('salesorder')
      ->leftJoin('companies','companies.Id','=','salesorder.companyId')
      ->leftJoin('companies as client','client.Id','=','salesorder.clientId')
      ->select('companies.Company_Name','companies.Address','companies.Contact_No','companies.Office_No','companies.Fax_No','client.Company_Name as client_company','client.Address as client_address','client.Contact_No as client_no','client.Office_No as client_office','client.Fax_No as client_fax','client.attention','companies.bank_acct','companies.bank','client.type','salesorder.combined_invoice_num','salesorder.combined_invoice_date','salesorder.po','salesorder.term','salesorder.combine_remarks')
      ->where('salesorder.Id','=',$id)
      ->first();

      $invoice = DB::table('salesorder')
      ->leftJoin('companies','companies.Id','=','salesorder.companyId')
      ->leftJoin('companies as client','client.Id','=','salesorder.clientId')
      // ->leftjoin('deliveryform','deliveryform.salesorderid','=','salesorder.Id')
      ->leftJoin(DB::raw('(SELECT Id,salesorderId,item_no FROM salesorderitem GROUP BY salesorderId) as item'),'salesorder.Id','=','item.salesorderId')
      ->leftjoin('tracker','tracker.Id','=','salesorder.trackerid')
      ->select('salesorder.*',DB::raw('tracker.`Site Name` as site'),'item.item_no')
      ->where('salesorder.combined_invoice_num','=',$combinenum->combined_invoice_num)
      // ->orderBy('item.item_no','ASC')
      ->orderBy('item.item_no','ASC')
      ->get();

      $itemnull = 0;
      foreach ($invoice as $a => $b) {
          if($b->item_no == NULL)
          {
            $itemnull = 1;
          }
      }

      if($itemnull)
      {
        $invoice = DB::table('salesorder')
      ->leftJoin('companies','companies.Id','=','salesorder.companyId')
      ->leftJoin('companies as client','client.Id','=','salesorder.clientId')
      // ->leftjoin('deliveryform','deliveryform.salesorderid','=','salesorder.Id')
      ->leftJoin(DB::raw('(SELECT Id,salesorderId,item_no FROM salesorderitem GROUP BY salesorderId) as item'),'salesorder.Id','=','item.salesorderId')
      ->leftjoin('tracker','tracker.Id','=','salesorder.trackerid')
      ->select('salesorder.*',DB::raw('tracker.`Site Name` as site'),'item.item_no')
      ->where('salesorder.combined_invoice_num','=',$combinenum->combined_invoice_num)
      // ->orderBy('item.item_no','ASC')
      ->orderBy(DB::raw('STR_TO_DATE(salesorder.rental_start,"%d-%M-%Y")'),'ASC')
      ->get();
      }

      $getid = json_decode(json_encode($invoice),true);
      $soid = array();
      $totalamount = 0.0;
      foreach ($getid as $k => $v) {
            array_push($soid,$v['Id']);
            $totalamount = $totalamount + $v['total_amount']; 
      }
      $formid = DB::table('deliveryform')
      ->select('Id')
      ->where('deliveryform.Purpose','=',1814)
      ->whereIn('salesorderid',$soid)
      ->orderBy('Id','DESC')
      ->get();
      $formids = json_decode(json_encode($formid),true);
      $doid = array();
      foreach ($formids as $key => $value) {
            array_push($doid, $value['Id']);
      }

        $soitem = DB::table('salesorderitem')
        ->leftjoin('salesorder','salesorder.Id','=','salesorderitem.salesorderId')
        ->select('salesorderitem.*',DB::raw("CAST((salesorderitem.price * salesorderitem.qty) AS DECIMAL(12,2)) as total"))
        ->WhereIN('salesorder.Id',$soid)
        ->get();
        
        $item = DB::table('deliveryitem')
        ->leftjoin('inventories','inventories.Id','=','deliveryitem.inventoryId')
        ->leftjoin('speedfreakinventory','speedfreakinventory.machinery_no','=','inventories.Item_Code')
        ->leftjoin('deliveryform','deliveryform.Id','=','deliveryitem.formId')
        ->select('deliveryform.salesorderid','inventories.Item_Code','speedfreakinventory.engine_no')
        ->whereIn('deliveryitem.formId',$doid)
        // ->where('Item_Code','LIKE','SC%')
        ->where('speedfreakinventory.type','=','SPEEDFREAK')
        ->orwhere('Item_Code','LIKE','%Tray%')
        ->get();

        $tray = DB::table('deliveryitem')
        ->leftjoin('inventories','inventories.Id','=','deliveryitem.inventoryId')
        ->select('inventories.Description','inventories.Item_Code')
        ->leftjoin('deliveryform','deliveryform.Id','=','deliveryitem.formId')
        ->whereIn('deliveryitem.formId',$doid)
        ->where('Item_Code','LIKE','%Tray%')
        ->get();

        $machine = DB::table('speedfreakinventory')
        ->leftjoin('inventories','inventories.Item_Code','=','speedfreakinventory.machinery_no')
        ->select('inventories.Item_Code','speedfreakinventory.machinery_no','speedfreakinventory.engine_no')
        ->where('inventories.Item_Code','<>',"")
        ->get();

      $words = $this->convertNumber($totalamount);
      $words = strtoupper($words);
      $totalamount = number_format($totalamount,2);
      return view("combineinvoicetemplate",['me'=>$me,'invoice'=>$invoice,'soitem'=>$soitem,'item'=>$item,'tray'=>$tray,'machine'=>$machine,'words'=>$words,'totalamount'=>$totalamount,'list'=>$list]);
    }

    public function autopdf()
    {
      $id = DB::table('salesorder')
      ->select('Id')
      ->where('invoice','=','1')
      ->where('Id','>',1517)
      ->get();

      $array = json_decode(json_encode($id),true);
      foreach ($array as $key => $value) {      
        $this->invoicepdf($value['Id']);
      }
    }


    public function invoicepdf($id)
    {
    $me = (new CommonController)->get_current_user();

      $invoice = DB::table('salesorder')
      ->leftJoin('companies','companies.Id','=','salesorder.companyId')
      ->leftJoin('companies as client','client.Id','=','salesorder.clientId')
      ->leftjoin('deliveryform','deliveryform.salesorderid','=','salesorder.Id')
      ->leftjoin('tracker','tracker.Id','=','salesorder.trackerid')
      ->select('companies.Company_Name','companies.Address','companies.Contact_No','companies.Office_No','companies.Fax_No','client.Company_Name as client_company','client.Address as client_address','client.Contact_No as client_no','client.Office_No as client_office','client.Fax_No as client_fax','client.attention','salesorder.*',DB::raw('tracker.`Site Name` as site'),'companies.bank_acct','companies.bank','client.type','deliveryform.DO_No')
      ->where('salesorder.Id','=',$id)
      ->orderBy('deliveryform.Id','DESC')
      ->first();
      // $item = DB::table('salesorderitem')
      // ->select('salesorderitem.*',DB::raw("round(salesorderitem.price * salesorderitem.qty,2) as total"))
      // ->where('salesorderitem.salesorderId','=',$invoice->Id)
      // ->get();
      $formid = DB::table('deliveryform')
      ->select('Id')
      ->where('deliveryform.Purpose','=',1814)
      ->where('salesorderid','=',$invoice->Id)
      ->orderBy('Id','DESC')
      ->first();

     if($formid == null)
      {
        $soitem = DB::table('salesorderitem')
        ->leftjoin('salesorder','salesorder.Id','=','salesorderitem.salesorderId')
        ->select('salesorderitem.*',DB::raw("CAST((salesorderitem.price * salesorderitem.qty) AS DECIMAL(12,2)) as total"))
        ->where('salesorder.Id','=',$invoice->Id)
        ->get();
        $item = array();
        $tray = array();
        $machine = array();
      }
      else
      {
      $soitem = DB::table('salesorderitem')
      ->leftjoin('salesorder','salesorder.Id','=','salesorderitem.salesorderId')
      ->leftjoin('deliveryform','deliveryform.salesorderid','=','salesorder.Id')
      ->select('salesorderitem.*',DB::raw("CAST((salesorderitem.price * salesorderitem.qty) AS DECIMAL(12,2)) as total"))
      ->where('deliveryform.Id','=',$formid->Id)
      ->get();

      $item = DB::table('deliveryitem')
      ->leftjoin('inventories','inventories.Id','=','deliveryitem.inventoryId')
      ->leftjoin('speedfreakinventory','speedfreakinventory.machinery_no','=','inventories.Item_Code')
      ->select('inventories.Item_Code','speedfreakinventory.engine_no')
      ->where('deliveryitem.formId','=',$formid->Id)
      // ->where('Item_Code','LIKE','SC%')
      ->where('speedfreakinventory.type','=','SPEEDFREAK')
      ->first();

      $tray = DB::table('deliveryitem')
      ->leftjoin('inventories','inventories.Id','=','deliveryitem.inventoryId')
      ->select('inventories.Description','inventories.Item_Code')
      ->where('deliveryitem.formId','=',$formid->Id)
      ->where('Item_Code','LIKE','%Tray%')
      ->first();

      $machine = DB::table('speedfreakinventory')
      ->leftjoin('inventories','inventories.Item_Code','=','speedfreakinventory.machinery_no')
      ->select('inventories.Item_Code','speedfreakinventory.machinery_no','speedfreakinventory.engine_no')
      ->where('inventories.Item_Code','<>',"")
      ->get();
      }
      $words = $this->convertNumber($invoice->total_amount);
      $words = strtoupper($words);
       $html =view("invoicepdf",['me'=>$me,'invoice'=>$invoice,'soitem'=>$soitem,'item'=>$item,'tray'=>$tray,'machine'=>$machine,'words'=>$words]);
      (new ExportPDFController)->ExportAndSave($html,$invoice->Id,$invoice->trackerid,$invoice->client_company,$invoice->SO_Number);
    }

    public function invoicepdf2($sonumber)
    {
    $me = (new CommonController)->get_current_user();

      $soid = DB::table('salesorder')
      ->select('Id')
      ->where('SO_Number','=',$sonumber)
      ->first();

      $invoice = DB::table('salesorder')
      ->leftJoin('companies','companies.Id','=','salesorder.companyId')
      ->leftJoin('companies as client','client.Id','=','salesorder.clientId')
      ->leftjoin('deliveryform','deliveryform.salesorderid','=','salesorder.Id')
      ->leftjoin('tracker','tracker.Id','=','salesorder.trackerid')
      ->select('companies.Company_Name','companies.Address','companies.Contact_No','companies.Office_No','companies.Fax_No','client.Company_Name as client_company','client.Address as client_address','client.Contact_No as client_no','client.Office_No as client_office','client.Fax_No as client_fax','client.attention','salesorder.*',DB::raw('tracker.`Site Name` as site'),'companies.bank_acct','companies.bank','client.type','deliveryform.DO_No')
      ->where('salesorder.Id','=',$soid->Id)
      ->orderBy('deliveryform.Id','DESC')
      ->first();
      // $item = DB::table('salesorderitem')
      // ->select('salesorderitem.*',DB::raw("round(salesorderitem.price * salesorderitem.qty,2) as total"))
      // ->where('salesorderitem.salesorderId','=',$invoice->Id)
      // ->get();
      $formid = DB::table('deliveryform')
      ->select('Id')
      ->where('deliveryform.Purpose','=',1814)
      ->where('salesorderid','=',$invoice->Id)
      ->orderBy('Id','DESC')
      ->first();

     if($formid == null)
      {
        $soitem = DB::table('salesorderitem')
        ->leftjoin('salesorder','salesorder.Id','=','salesorderitem.salesorderId')
        ->select('salesorderitem.*',DB::raw("CAST((salesorderitem.price * salesorderitem.qty) AS DECIMAL(12,2)) as total"))
        ->where('salesorder.Id','=',$invoice->Id)
        ->get();
        $item = array();
        $tray = array();
        $machine = array();
      }
      else
      {
      $soitem = DB::table('salesorderitem')
      ->leftjoin('salesorder','salesorder.Id','=','salesorderitem.salesorderId')
      ->leftjoin('deliveryform','deliveryform.salesorderid','=','salesorder.Id')
      ->select('salesorderitem.*',DB::raw("CAST((salesorderitem.price * salesorderitem.qty) AS DECIMAL(12,2)) as total"))
      ->where('deliveryform.Id','=',$formid->Id)
      ->get();

      $item = DB::table('deliveryitem')
      ->leftjoin('inventories','inventories.Id','=','deliveryitem.inventoryId')
      ->leftjoin('speedfreakinventory','speedfreakinventory.machinery_no','=','inventories.Item_Code')
      ->select('inventories.Item_Code','speedfreakinventory.engine_no')
      ->where('deliveryitem.formId','=',$formid->Id)
      // ->where('Item_Code','LIKE','SC%')
      ->where('speedfreakinventory.type','=','SPEEDFREAK')
      ->first();

      $tray = DB::table('deliveryitem')
      ->leftjoin('inventories','inventories.Id','=','deliveryitem.inventoryId')
      ->select('inventories.Description','inventories.Item_Code')
      ->where('deliveryitem.formId','=',$formid->Id)
      ->where('Item_Code','LIKE','%Tray%')
      ->first();

      $machine = DB::table('speedfreakinventory')
      ->leftjoin('inventories','inventories.Item_Code','=','speedfreakinventory.machinery_no')
      ->select('inventories.Item_Code','speedfreakinventory.machinery_no','speedfreakinventory.engine_no')
      ->where('inventories.Item_Code','<>',"")
      ->get();
      }
      $words = $this->convertNumber($invoice->total_amount);
      $words = strtoupper($words);
       $html =view("invoicepdf",['me'=>$me,'invoice'=>$invoice,'soitem'=>$soitem,'item'=>$item,'tray'=>$tray,'machine'=>$machine,'words'=>$words]);
      (new ExportPDFController)->Export($html);
    }

    public function cnpdf($id)
    {
        $me = (new CommonController)->get_current_user();
        $inv = DB::table('salesorder')
        ->select('companies.Company_Name','companies.Address','companies.Contact_No','companies.Office_No','companies.Fax_No','client.Company_Name as client_company','client.Address as client_address','client.Contact_No as client_no','client.Office_No as client_office','client.Fax_No as client_fax','creditnote.*','salesorder.invoice_date','salesorder.invoice_number','salesorder.creditnote')
        ->leftJoin('companies','companies.Id','=','salesorder.companyId')
        ->leftJoin('companies as client','client.Id','=','salesorder.clientId')
        ->leftJoin('creditnote','creditnote.salesorderId','=','salesorder.Id')
        ->leftjoin('creditnoteitem','creditnoteitem.creditnoteId','=','creditnote.Id')
        ->groupby('creditnote.Id')
        ->where('creditnote.Id',$id)
        ->first();

        $item = DB::table('creditnoteitem')
        ->leftjoin('creditnote','creditnote.Id','=','creditnoteitem.creditnoteId')
        ->where('creditnote.Id',$id)
        ->select('creditnoteitem.item_no','creditnoteitem.description','creditnoteitem.amount','creditnoteitem.knockoff')
        ->get();

        $totaltext = strtoupper($this->convertNumber($inv->amount));

        $html =  view('creditnotepdf',['me'=>$me,'inv'=>$inv,'item'=>$item, 'totaltext'=>$totaltext]);
        (new ExportPDFController)->ExportAndSaveCN($html);

        return 1;
    }

     public function salesorderlog($salesorderid)
    {
      $me = (new CommonController)->get_current_user();
      $log = DB::table('salesorderdetails')
      ->leftjoin('users','users.Id','=','salesorderdetails.userId')
      ->select('salesorderdetails.details','users.Name','salesorderdetails.created_at')
      ->where('salesorderdetails.salesorderId','=',$salesorderid)
      ->orderBy('salesorderdetails.Id','ASC')
      ->get();
      return view("salesorderlog",['me'=>$me,'log'=>$log]);
    }

      public function salesorderhistory($trackerid)
    {
      $me = (new CommonController)->get_current_user();

      $so = DB::table('salesorder')
      ->leftjoin('companies','companies.Id','=','salesorder.companyId')
      ->leftjoin('companies as client','client.Id','=','salesorder.clientId')
      ->leftjoin('tracker','tracker.Id','=','salesorder.trackerid')
      ->leftJoin('salesorderitem','salesorderitem.salesorderId','=','salesorder.Id')
      ->select('salesorder.Id','tracker.sales_order','salesorder.SO_Number','companies.Company_Name','client.Company_Name as client_company',DB::raw('tracker.`Site Name` as site'),'salesorder.date','salesorder.rental_start','salesorder.rental_end','salesorder.total_amount','client.type','salesorder.invoice')
      ->where('salesorder.trackerid','=',$trackerid)
      ->groupby('salesorder.Id')
      ->get();

       $files = DB::table('files')
      ->select('salesorderid','TargetId','Web_Path','Id','Type')
      // ->whereIN('Type',['BOQ_Approval','PoandSitePack'])
      ->get();

       return view('salesorderhistory',['me' => $me,'so'=>$so,'trackerid'=>$trackerid,'files'=>$files]);
      }

       public function deliveryorderhistory($trackerid)
      {
      $me = (new CommonController)->get_current_user();

      $so = DB::table('salesorder')
      ->select('Id')
      ->where('trackerid','=',$trackerid)
      ->get();

      $soid = json_decode(json_encode($so),true);
      $soids = array();
      for($i=0; $i<Count($soid); $i++)
      {
        array_push($soids, $soid[$i]['Id']);
      }

      $do = DB::table('deliveryform')
      ->leftJoin( DB::raw('(select Max(Id) as maxid,deliveryform_Id from deliverystatuses Group By deliveryform_Id) as max'), 'max.deliveryform_Id', '=', 'deliveryform.Id')
      ->leftJoin('deliverystatuses', 'deliverystatuses.Id', '=', DB::raw('max.`maxid`'))
      ->leftJoin('users','users.Id','=','deliveryform.RequestorId')
      ->leftJoin('companies','companies.Id','=','deliveryform.company_id')
      ->leftJoin('companies as client','client.Id','=','deliveryform.client')
      ->leftJoin('radius','radius.Id','=','deliveryform.Location')
      ->leftJoin('options','options.Id','=','deliveryform.Purpose')
      ->leftJoin('deliverylocation','deliverylocation.area','=','radius.Area')
      ->leftJoin('roadtax','roadtax.Id','=','deliveryform.roadtaxId')
      ->select('deliveryform.Id','deliveryform.DO_No','options.Option','companies.Company_Name','client.Company_Name as client','radius.Location_Name','users.Name','deliveryform.created_at')
      ->whereIn('deliveryform.salesorderid',$soids)
      ->where('deliverystatuses.delivery_status','<>','Incomplete')
      ->whereRaw('DO_NO NOT LIKE BINARY "%\_R%"')
      ->groupby('deliveryform.Id')
      ->get();

       return view('deliveryorderhistory',['me' => $me,'do'=>$do]);
      }

      function convertNumber($num = false)
{
    $tempNum = explode( '.' , $num );
    if(count($tempNum) == 1)
    {
      $tempNum[1] = "0";
    }
    //if the first is 0 get list1, else get list2
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
    } //end for loop
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
      // dd($cents,$cents2);
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


  public function save(Request $request)
  {

    $me=(new CommonController)->get_current_user();

      $input = $request->all();
      $rules = array(
        'date' => 'Required',
        'company' => 'Required',
        'client' => 'Required',
        'rentalstart' => 'Required',
        'rentalend' => 'Required',
        // 'po' => 'Required',
        'description' => 'Required|array'
        // 'quantity' => 'Required|array'
      );

                    if (isset($input['description'])) {
                        $item = $input['description'];
                        foreach($item as $k => $v) {
                            $rules['description.'.$k] = 'required';
                            $rules['unit.'.$k] = 'required';
                            $rules['quantity.'.$k] = 'required|numeric';
                            $rules['price.'.$k] = 'required|numeric';
                        }
                      }



      $messages = array(
            'date.required'  =>'The Date field is required',
            'client.required'  =>'The Client field is required',
            'company.required'  =>'The Company field is required',
            'rentalstart.required'  =>'The Rental Start field is required',
            'rentalend.required'  =>'The Rental End field is required',
            'po.required'  =>'The PO field is required',
            'description.required'  =>'The Item field is required',
            'quantity.required'  =>'The quantity field is required',
        );

        $validator = Validator::make($input, $rules,$messages);

        if ($validator->passes()) {

            return $this->insertRequestAndSendEmail($request, $me);
        }

        return json_encode($validator->errors()->toArray());
  }

  protected function insertRequestAndSendEmail($request, $me)
  {
       $input = $request->all();
       $tracker = DB::table('tracker')
       ->where('tracker.Id','=',$input['trackerid'])
       ->select('Id',DB::raw('tracker.`Site Name` as SiteName'),'NTP')
       ->first();

       $id = DB::table('salesorder')->insertGetId([
              'companyId'=> $input['company'],
              'date'=> $input['date'],
              'clientId' => $input['client'],
              'rental_start'=> $input['rentalstart'],
              'rental_end'=> $input['rentalend'],
              'po' => $input['po'],
              'term' => $input['term'],
              'trackerid' => $input['trackerid'],
              'remarks' => $input['remarks']
      ]);
       $item = $input['description'];
       foreach($item as $key => $val)
       {
            if($input['item_no'][$key] =="")
              {
                $input['item_no'][$key] = NULL;
              }
              DB::table('salesorderitem')->insert([
              'qty'=> $input['quantity'][$key],
              'price'=> $input['price'][$key],
              'item_no'=> $input['item_no'][$key],
              'salesorderId' => $id,
              'description' => $input['description'][$key],
              'unit' => $input['unit'][$key],
              'clientId' => $input['client']
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
          'total_amount' => $total->total,
          'parentId' => $id
      ]);

      DB::table('salesorderdetails')
      ->insert([
        'salesorderId' => $id,
        'details' => "New Sales Order Created",
        'userId' => $me->UserId,
        'created_at' => Carbon::Now()
      ]);

      return 1;
  }

    public function deletesalesorder(Request $request)
  {
      $input = $request->all();
      $me=(new CommonController)->get_current_user();

      $so = DB::table('salesorder')
      ->select('SO_Number','invoice_number')
      ->where('Id','=',$input['Id'])
      ->first();

      DB::table('salesorder')
      ->where('Id','=',$input['Id'])
      ->delete();

      DB::Table('salesorderitem')
      ->where('salesorderId','=',$input["Id"])
      ->delete();

      DB::table('salesorderdetails')
      ->where('salesorderId','=',$input["Id"])
      ->delete();

      DB::table('actionhistory')
      ->insert([
          'Type' => 'SO',
          'ActionId' => $input["Id"],
          'details' => "SO # =>".$so->SO_Number."Inv # =>".$so->invoice_number,
          'UserId' => $me->UserId,
          'action' => 'Delete',
          'created_at' => Carbon::now()
      ]);

      return 1;
  }

    public function save2(Request $request)
  {
      $me=(new CommonController)->get_current_user();

      $input = $request->all();

       $tracker = DB::table('tracker')
       ->where('tracker.Id','=',$input['trackerid'])
       ->select('Id',DB::raw('tracker.`Site Name` as SiteName'),'NTP')
       ->first();

       $invoiced = DB::table('salesorder')
       ->where('salesorder.Id','=',$input['salesorderid'])
       ->select('invoice')
       ->first();

       if($invoiced->invoice)
       {
          $this->invoicepdf($input['salesorderid']);
       }

      DB::table('salesorder')
      ->where('salesorder.Id','=',$input['salesorderid'])
      ->update([
              'companyId'=> $input['company'],
              'date'=> $input['date'],
              'clientId' => $input['client'],
              'rental_start'=> $input['rentalstart'],
              'rental_end'=> $input['rentalend'],
              'term' => $input['term'],
              'po' => $input['po'],
              'remarks' => $input['remarks']
            ]);
      DB::table('salesorderitem')
      ->where('salesorderId','=',$input['salesorderid'])
      ->delete();
      if(isset($input['description']))
      {
       $item = $input['description'];
       foreach($item as $key => $val)
       {
          if($input['item_no'][$key] =="")
          {
            $input['item_no'][$key] = NULL;
          }
         DB::table('salesorderitem')
         ->insert([
              'qty'=> $input['quantity'][$key],
              'price'=> $input['price'][$key],
              'salesorderId' => $input['salesorderid'],
              'description'=> $input['description'][$key],
              'clientId'=> $input['client'],
              'unit' => $input['unit'][$key],
              'item_no' => $input['item_no'][$key]
         ]);
       }
      }
      else
      {
        return "No item";
      }
      $total = DB::table('salesorderitem')
      ->select(DB::raw('SUM(qty * price) as total'))
      ->where('salesorderId','=',$input['salesorderid'])
      ->groupby('salesorderid')
      ->first();

      DB::table('salesorder')
      ->where('Id','=',$input['salesorderid'])
      ->update([
          'total_amount' => $total->total
      ]);
      DB::table('salesorderdetails')
      ->insert([
        'salesorderId' => $input['salesorderid'],
        'details' => "Sales Order Details Edited",
        'userId' => $me->UserId,
        'created_at' => Carbon::Now()
      ]);


      return 1;

  }

    public function combineinvoice(Request $request)
    {
      $input = $request->all();

      $company = DB::table('salesorder')
      ->leftjoin('companies','companies.Id','=','salesorder.companyId')
      ->select("Initial")
      ->where('salesorder.Id','=',$input['Id'][0])
      ->first();

      $indicator = $company->Initial.Carbon::now()->format('y').Carbon::now()->format('m');
     $max = DB::SELECT('SELECT MAX(invoice_number) as invoice_number FROM (select Max(invoice_number) as invoice_number from salesorder where `invoice_number` LIKE 
        "'.$indicator.'%"
        UNION 
        select Max(combined_invoice_num) as invoice_number from salesorder where combined_invoice_num LIKE "'.$indicator.'%") AS A');
      $maxinv = json_decode(json_encode($max),true);

      if($maxinv[0]['invoice_number'] == "" || $maxinv[0]['invoice_number'] == null)
      {
        $counter = 0;
      }
      else
      {
        $counter = substr($maxinv[0]['invoice_number'], 6);
        $counter = $counter + 1;
      }
      $invoicenumber = $indicator.str_pad($counter,3,'0',STR_PAD_LEFT);

      foreach ($input['Id'] as $key => $value) {
              $check = DB::table('salesorder')
              ->where('Id','=',$value)
              ->select('combined_invoice_num')
              ->first();

              if($check->combined_invoice_num == "" || $check->combined_invoice_num == null)
              {
                DB::table('salesorder')
                ->where('Id','=',$value)
                ->update([
                    'combined_invoice_num' => $invoicenumber,
                    'combined_invoice_date'=> Carbon::now()->format('d-M-Y')
                ]);
              }
              else
              {
                return 0;
              }
      }

      return 1;

    }

      public function manualRecurSalesOrder($id)
    {
      $today = date('d-M-Y', strtotime('today'));
      $allid = DB::table('tracker')
       ->leftJoin( DB::raw('(select Max(Id) as maxid,trackerid,parentId from salesorder Group By trackerid,parentId) as max'), 'max.trackerid', '=', 'tracker.Id')
      ->leftJoin('salesorder', 'salesorder.Id', '=', DB::raw('max.`maxid`'))
      ->select('salesorder.Id','salesorder.rental_end')
      // ->where('tracker.sales_order','<>',0)
      // ->where('tracker.recurring','=',1)
      // ->where('salesorder.rental_end','=',$today)
      ->where('salesorder.Id','=',$id)
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

                   return 1;
                 }
          }

        }

      }


    }

    public function creditnotedetail($id)
    {
      $me = (new CommonController)->get_current_user();

      $inv = DB::table('salesorder')
      ->select('creditnote.*','salesorder.invoice_date','salesorder.invoice_number','companies.Company_Name','client.Company_Name as client','salesorder.creditnote')
      ->leftJoin('companies','companies.Id','=','salesorder.companyId')
      ->leftJoin('companies as client','client.Id','=','salesorder.clientId')
      ->leftJoin('creditnote','creditnote.salesorderId','=','salesorder.Id')
      ->where('salesorder.Id',$id)
      ->first();

      $item = DB::table('creditnoteitem')
      ->leftjoin('creditnote','creditnote.Id','=','creditnoteitem.creditnoteId')
      ->where('creditnote.salesorderId',$id)
      ->select('creditnoteitem.Id','creditnoteitem.item_no','creditnoteitem.description','creditnoteitem.amount','creditnoteitem.knockoff')
      ->get();

      if(!$item)
      {
        $item = DB::table('salesorderitem')
        ->where('salesorderId',$id)
        ->select(DB::raw(' 0 as Id '),'item_no','description',DB::raw(' CAST( (qty * price) AS DECIMAL(10,2) ) as amount '),DB::raw(' "" as knockoff'))
        ->get();
      }

      return view('creditnotedetails',['me'=>$me,'item'=>$item, 'inv'=>$inv, 'id'=>$id]);
    }

    public function deletecreditnoteitem($id)
    {
      DB::table('creditnoteitem')
      ->where('Id',$id)
      ->delete();
    }

    public function savecreditnote(Request $request)
    {
      $me = (new CommonController)->get_current_user();
      $rules = array(
          'cn_date' => 'Required',
          'attn' => 'Required',
          'term' => 'Required',
          'tax' => 'Required',
          'reason' => 'Required'
      );

      $messages = array(
          'cn_date.required' => "The CN Date is required",
          'attn.required' => "The attention to is required",
          'term.required' => "The term is required",
          'tax.required' => "The Item is required",
          'reason.required' => "The reason is required"
      );

      $validator = Validator::make($request->all(), $rules,$messages);

      if( $validator->fails() )
      {
        return json_encode($validator->errors()->toArray());
      }

      $amount = 0;
      foreach($request->knockoff as $val)
      {
        $amount = $amount + (float)$val > 0 ? $val : 0;
      }

      if(!$request->cnid)
      {
        $cnno = $this->generateCNNumber();

        DB::table('salesorder')
        ->where('Id',$request->soid)
        ->update([
          'creditnote' => 1
        ]);

        $id = DB::Table('creditnote')
        ->insertGetId([
          'salesorderId' => $request->soid,
          'cn_no' => $cnno,
          'date' => $request->cn_date,
          'attn' => $request->attn,
          'amount' => $amount,
          'term' => $request->term,
          'reason' => $request->reason,
          'created_at' => DB::raw('NOW()'),
          'created_by' => $me->UserId
        ]);
      }
      else
      {
        DB::table('creditnote')
        ->where('Id',$request->cnid)
        ->update([
          'cn_no' => $request->cn_no,
          'date' => $request->cn_date,
          'attn' => $request->attn,
          'amount' => $amount,
          'reason' => $request->reason,
          'term' => $request->term
        ]);
      }

      foreach($request->cnitemid as $cnkey => $cnval)
      {
        if($cnval)
        {
          DB::table('creditnoteitem')
          ->where('Id',$cnval)
          ->update([
              'creditnoteId' => $request->cnid,
              'item_no' => $request->tax[$cnkey],
              'description' => $request->desc[$cnkey],
              'amount' => $request->amount[$cnkey],
              'knockoff' => $request->knockoff[$cnkey]
          ]);
        }
        else
        {
          DB::table('creditnoteitem')
          ->insert([
              'creditnoteId' => isset($id) ? $id : $request->cnid,
              'item_no' => $request->tax[$cnkey],
              'description' => $request->desc[$cnkey],
              'amount' => $request->amount[$cnkey],
              'knockoff' => $request->knockoff[$cnkey]
          ]);
        }
      }

      return $this->cnpdf(isset($id) ? $id : $request->cnid);

      // return 1;

    }

    public function generateCNNumber()
    {
      $indicator = "CN".date('ym',strtotime('today'));
      $number = 0;
      $check = DB::table('creditnote')
      ->select(DB::raw('Max(SUBSTRING(cn_no,7,3)) as cn_no'))
      ->where('cn_no','LIKE',"%".$indicator."%")
      ->first();

      if($check)
      {
        $number = $check->cn_no + 1;
      }

      $cnno = $indicator.sprintf("%03s",$number);

      return $cnno;
    }

    public function creditnotetemplate($id)
    {
      $inv = DB::table('salesorder')
      ->select('companies.Company_Name','companies.Address','companies.Contact_No','companies.Office_No','companies.Fax_No','client.Company_Name as client_company','client.Address as client_address','client.Contact_No as client_no','client.Office_No as client_office','client.Fax_No as client_fax','creditnote.*','salesorder.invoice_date','salesorder.invoice_number','salesorder.creditnote')
      ->leftJoin('companies','companies.Id','=','salesorder.companyId')
      ->leftJoin('companies as client','client.Id','=','salesorder.clientId')
      ->leftJoin('creditnote','creditnote.salesorderId','=','salesorder.Id')
      ->leftjoin('creditnoteitem','creditnoteitem.creditnoteId','=','creditnote.Id')
      ->groupby('creditnote.Id')
      ->where('creditnote.Id',$id)
      ->first();

      $item = DB::table('creditnoteitem')
      ->leftjoin('creditnote','creditnote.Id','=','creditnoteitem.creditnoteId')
      ->where('creditnote.Id',$id)
      ->select('creditnoteitem.item_no','creditnoteitem.description','creditnoteitem.amount','creditnoteitem.knockoff')
      ->get();

      $totaltext = strtoupper($this->convertNumber($inv->amount));

      return view('creditnote',['inv'=>$inv,'item'=>$item, 'totaltext'=>$totaltext]);
    }

    public function creditnotelist($start = null, $end = null)
    {
      $me = (new CommonController)->get_current_user();

      if(!$start)
      {
        $start = date('d-M-Y',strtotime('first day of last month'));
      }

      if(!$end)
      {
        $end = date('d-M-Y',strtotime('last day of this month'));
      }

      $details = DB::table('creditnote')
      ->leftjoin('salesorder','salesorder.Id','=','creditnote.salesorderId')
      ->leftjoin('companies','companies.Id','=','salesorder.companyId')
      ->leftJoin('companies as client','client.Id','=','salesorder.clientId')
      ->select('creditnote.Id','salesorder.Id as soId','creditnote.cn_no','creditnote.date','salesorder.invoice_number','salesorder.invoice_date','companies.Company_Name','client.Company_Name as client','salesorder.total_amount','creditnote.amount')
      ->whereRaw(' STR_TO_DATE(creditnote.date,"%d-%M-%Y") BETWEEN STR_TO_DATE("'.$start.'","%d-%M-%Y") AND STR_TO_DATE("'.$end.'","%d-%M-%Y")')
      ->get();

      return view('creditnotelist',['me'=>$me,'details'=>$details,'start'=>$start,'end'=>$end]);
    }


}
