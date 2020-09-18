<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\URL;
use App\Http\Controllers\CommonController;
use Illuminate\Support\Facades\Mail;

class NoticeController extends Controller {

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

    $notice = DB::table('noticeboards')
    ->select('noticeboards.Id','f.FileId','f.FileName','noticeboards.Title','noticeboards.Content','noticeboards.Start_Date','noticeboards.End_Date','users.Name as Created_By',DB::raw("if(`noticeboards`.Email_Notification, 'Yes', 'No') as Email_Notification"),'f.Attachment')
    ->leftJoin('users', 'users.Id', '=', 'noticeboards.UserId')
		->leftJoin(DB::raw('(SELECT TargetId, GROUP_CONCAT( Id SEPARATOR "|") as FileId,GROUP_CONCAT( Web_Path SEPARATOR "|") as Attachment,GROUP_CONCAT( File_Name SEPARATOR "|") as FileName FROM files WHERE Type="Notice" GROUP BY TargetId) as f'),'f.TargetId','=','noticeboards.Id')

    ->orderBy('noticeboards.Id','desc')
    ->get();

    return view('noticeboardmanagement', ['me' => $me,'notice' =>$notice]);

	}

	public function uploadfile(Request $request)
	{
		$input = $request->all();
		$insertid=$input["NoticeId"];
		$type="Notice";
		$uploadcount=1;
			//$file = Input::file('profilepicture');
			if ($request->hasFile('uploadfile')) {
				$file = $request->file('uploadfile');
				$destinationPath=public_path()."/private/upload";
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
					 'Web_Path' => '/private/upload/'.$fileName
					]
				);
				return url('/private/upload/'.$fileName);
				//return '/private/upload/'.$fileName;
			}
			else {
				return 0;
			}
	}

	public function deletefile(Request $request)
	{
		$input = $request->all();

		return DB::table('files')
		->where('Id', '=', $input["Id"])
		->delete();

	}

	public function notify(Request $request)
	{
		$input = $request->all();

		$emails = array();

		$notify = DB::table('users')
		->where('Active', '=','1')
		->get();

		foreach ($notify as $user) {
			if ($user->Company_Email!="")
			{
				if(!filter_var($user->Company_Email, FILTER_VALIDATE_EMAIL) === false)
				{
					array_push($emails,$user->Company_Email);
				}

			}
			else
			{
				if(!filter_var($user->Personal_Email, FILTER_VALIDATE_EMAIL) === false)
				{
					array_push($emails,$user->Personal_Email);
				}
			}

		}

		$notice = DB::table('noticeboards')
		->select('noticeboards.Id','noticeboards.Title','noticeboards.Content','noticeboards.Start_Date','noticeboards.End_Date','users.Name as Created_By',DB::raw("if(`noticeboards`.Email_Notification, 'Yes', 'No') as Email_Notification"),'f.Attachment','f.FileId','f.FileName')
		->leftJoin('users', 'users.Id', '=', 'noticeboards.UserId')
		->leftJoin(DB::raw('(SELECT TargetId, GROUP_CONCAT( Id SEPARATOR "|") as FileId,GROUP_CONCAT( Web_Path SEPARATOR "|") as Attachment,GROUP_CONCAT( File_Name SEPARATOR "|") as FileName FROM files WHERE Type="Notice" GROUP BY TargetId) as f'),'f.TargetId','=','noticeboards.Id')
		->where('noticeboards.Id', '=',$input["Id"])
		->orderBy('noticeboards.Id','desc')
		->first();


		// Mail::send('emails.notice', ['notice'=>$notice], function($message) use ($notice,$emails)
		// {
		// 		$emails = array_filter($emails);
		// 		array_push($emails,env('MAIL_DEFAULT_RECIPIENT'));
		// 		$message->to($emails)->subject($notice->Title);
		// });

	}

	public function viewnotice($Id)
	{
		$me = (new CommonController)->get_current_user();

		$currentnotice = DB::table('noticeboards')
		->select('noticeboards.Id','noticeboards.Title','noticeboards.Content','noticeboards.Start_Date','noticeboards.End_Date','users.Name as Created_By',DB::raw("if(`noticeboards`.Email_Notification, 'Yes', 'No') as Email_Notification"),'f.Attachment','f.FileId','f.FileName')
		->leftJoin('users', 'users.Id', '=', 'noticeboards.UserId')
		->leftJoin(DB::raw('(SELECT TargetId, GROUP_CONCAT( Id SEPARATOR "|") as FileId,GROUP_CONCAT( Web_Path SEPARATOR "|") as Attachment,GROUP_CONCAT( File_Name SEPARATOR "|") as FileName FROM files WHERE Type="Notice" GROUP BY TargetId) as f'),'f.TargetId','=','noticeboards.Id')
		->where('noticeboards.Id', '=',$Id)
		->orderBy('noticeboards.Id','desc')
		->first();

		return view('viewnotice', ['me' => $me, 'currentnotice' =>$currentnotice]);


	}

}
