<?php
namespace App\Http\Controllers;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\URL;
use App\Http\Controllers\CommonController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Dompdf\Dompdf;
use Dompdf\Options;
use File;
class ExportPDFController extends Controller {
    
  public function __construct()
	{
	  $this->middleware('auth');
	}

  public function Export($html)
	{
    $options = new Options();
    $options->set('isRemoteEnabled', TRUE);
    $options->set('logOutputFile',__DIR__ . '/dompdf.log.html');
    $dompdf = new Dompdf($options);
    $dompdf ->loadHtml($html);
    $dompdf->setPaper('A4', 'portrait');
    $dompdf->render();
    $dompdf->stream("attachment", array("Attachment" => false));
  }

  public function ExportLandscape($html)
	{
    $options = new Options();
    $options->set('isRemoteEnabled', TRUE);
    $options->set('logOutputFile',__DIR__ . '/dompdf.log.html');
    $dompdf = new Dompdf($options);
    $dompdf ->loadHtml($html);
    $dompdf->setPaper('A4', 'landscape');
    $dompdf->render();
    $dompdf->stream("attachment", array("Attachment" => false));
  }

  public function GeneralExport($html)
  {
    set_time_limit(3000);
    $options = new Options();
    $options->set('isRemoteEnabled', TRUE);
    $options->set('logOutputFile',__DIR__ . '/dompdf.log.html');
    $dompdf = new Dompdf($options);
    $dompdf ->loadHtml($html);
    $dompdf->setPaper('A4', 'portrait');
    $dompdf->render();
    $content = $dompdf->output();
    return $content;
  }

  public function DOPdf($html,$id,$donum)
  { 
    $content = $this->GeneralExport($html);
    $path = "/private/upload/E-DO";

    File::makeDirectory(public_path().$path, 0777, true, true);
    $fileName = $donum.".pdf";
    file_put_contents(public_path().$path."/".$fileName, $content);

     $insert=DB::table('files')->insertGetId(
          ['Type' => "Delivery",
           'TargetId' => $id,
           'File_Name' => $fileName,
           'Document_Type' => "E-DO",
           'Web_Path' => $path."/".$fileName
          ]
      );

     return $path."/".$fileName;
  }

  public function ExportAndSave($html,$salesorderid,$trackerid,$client,$sonum)
  {     
    set_time_limit(3000);
    $options = new Options();
    $options->set('isRemoteEnabled', TRUE);
    $options->set('logOutputFile',__DIR__ . '/dompdf.log.html');
    $dompdf = new Dompdf($options);
    $dompdf ->loadHtml($html);
    $dompdf->setPaper('A4', 'portrait');
    $dompdf->render();
    // $dompdf->stream("attachment", array("Attachment" => false));
    $content = $dompdf->output();
    $path = "/private/upload/ARInvoice/".$client."/Invoice";

    File::makeDirectory(public_path() ."/private/upload/ARInvoice/".$client."/Invoice/", 0777, true, true);
    $fileName= $sonum.".pdf";
    file_put_contents(public_path() ."/private/upload/ARInvoice/".$client."/Invoice/".$fileName, $content);


    $insert=DB::table('files')->insertGetId(
            ['Type' => "ARinvoice",
             'TargetId' => $trackerid,
             'salesorderid' => $salesorderid,
             'File_Name' => $fileName,
             'Document_Type' => "ARinvoice",
             'Web_Path' => $path."/".$fileName
            ]
        );
  }

  public function ExportAndSaveCN($html)
  {
    set_time_limit(3000);
    $options = new Options();
    $options->set('isRemoteEnabled', TRUE);
    $options->set('logOutputFile',__DIR__ . '/dompdf.log.html');
    $dompdf = new Dompdf($options);
    $dompdf ->loadHtml($html);
    $dompdf->setPaper('A4', 'portrait');
    $dompdf->render();
    // $dompdf->stream("attachment", array("Attachment" => false));
    $content = $dompdf->output();
    $path = "/private/upload/CreditNote";

    File::makeDirectory(public_path() ."/private/upload/CreditNote/", 0777, true, true);
    $files = File::files(public_path() ."/private/upload/CreditNote/");
    $filecount = 0;
        
    if ($files !== false) {
        $filecount = count($files);
    }
    $filecount = $filecount + 1;
    $fileName= "test".$filecount.".pdf";
    file_put_contents(public_path() ."/private/upload/CreditNote/".$fileName, $content);


    $insert=DB::table('files')->insertGetId(
            ['Type' => "CreditNote",
             'TargetId' => "",
             'salesorderid' => "",
             'File_Name' => $fileName,
             'Document_Type' => "CreditNote",
             'Web_Path' => $path."/".$fileName
            ]
        );
  }
    
  //   public function ExportLandscape2($html)
	// {
  //       $options = new Options();
  //       $options->set('isRemoteEnabled', TRUE);
  //       $options->set('logOutputFile',__DIR__ . '/dompdf.log.html');
  //       $dompdf = new Dompdf($options);
  //       $dompdf ->loadHtml($html);
  //       $dompdf->setPaper('A4', 'landscape');
  //       $dompdf->render();
  //       $dompdf->stream("attachment", array("Attachment" => false));
  //
  //   }

}
