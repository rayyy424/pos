<?php namespace App\Http\Controllers\Api;

use JWTAuth;
use Excel;
use Tymon\JWTAuth\Exceptions\JWTException;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

use Input;

class UploadController extends Controller {

	public function upload(Request $request)
	{

		$me = JWTAuth::parseToken()->authenticate();
		$input = $request->all();

		$insertid=$input["Id"];
		$type=$input["Type"];
		$count=$input['filecount'];

		$uploadcount = 1;

		for ($i=1; $i <= $count; $i++) {

	        $file = Input::file('File'.$i);

			$destinationPath=public_path()."/private/upload/".$type;
			$extension = $file->getClientOriginalExtension();
			$originalName=$file->getClientOriginalName();
			$fileSize=$file->getSize();
			$fileName=time()."_".$uploadcount.".".$extension;
			$upload_success = $file->move($destinationPath, $fileName);

			DB::table('files')->insert(
				['Type' => $type,
				 'TargetId' => $insertid,
				 'File_Name' => $originalName,
				 'File_Size' => $fileSize,
				 'Web_Path' => '/public/private/upload/'.$type.'/'.$fileName
				]
			);
		}

		return 1;
	}

	public function importpctracking(Request $request)
	{

		// $me = JWTAuth::parseToken()->authenticate();
		$input = $request->all();

		$file = Input::file('file');

		$destinationPath=public_path()."/private/upload/EngineerLog";
		$extension = $file->getClientOriginalExtension();
		$originalName=$file->getClientOriginalName();
		$fileSize=$file->getSize();
		$fileName=time().".".$extension;
		$upload_success = $file->move($destinationPath, $fileName);

		$handle = fopen($destinationPath."/".$fileName, "r");
		if ($handle) {
			$line = fgets($handle);
		    while (($line = fgets($handle)) !== false) {
		        // process the line read.
						$value=base64_decode($line);
						$csvitem=str_getcsv($value);
						$insert[] = ['PC_Label' => $csvitem[0],
				    'Process_Name' => $csvitem[1],
				    'Title' => $csvitem[2],
				    'Active_Inactive' => $csvitem[3],
				    'Date_Time' => $csvitem[4]
				  ];
		    }

		    fclose($handle);
		} 	

		else {
	    	// error opening the file.
		}

		// $data = Excel::load($destinationPath."/".$fileName, function($reader) {
		// })->get();
		// if(!empty($data) && $data->count()){

		// echo $data;
		// foreach ($data as $key => $value) {

		// 	$value=utf8_encode(base64_decode($value));
		// 	$value=json_decode($value);
		// 	echo $value;
		// 	$insert[] = [
		// 		'UserId' => $value->userid,
		// 		'Process_Name' => $value->process_name,
		// 		'Title' => $value->title,
		// 		'Active_Inactive' => $value->active_inactive,
		// 		'Date_Time' => $value->date_time
		// 	];
		// }

		// echo json_encode($insert);

		if(!empty($insert)){
		 	DB::table('pctrackings')->insert($insert);
		}

		$affected = DB::update('update pctrackings a
		JOIN assets b on a.PC_Label=b.Label
		JOIN assettrackings c on b.Id=c.AssetId AND c.Status="Taken"
		SET a.UserId=c.UserId
		Where b.Type="Laptop" and a.UserId=0 AND str_to_date(a.Date_Time,"%d-%m-%Y")>=str_to_date(c.Date,"%d-%M-%Y")');

		return 1;
	}
	
}
