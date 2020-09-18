<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;

use DB;
use Storage;
use Hash;
use Mail;

class PayrollController extends Controller
{

    public function authorizePayslipManagement(Request $request)
    {

        $me = (new CommonController)->get_current_user();

        $password = $request->Password;

        $hashed = DB::table('accesspasswords')->select('Password')->where('Access',  'Payslip_Management')->first();

        if ($hashed) {

            if (Hash::check($password, $hashed->Password)) {

                DB::table('logintrackings')->insert(array(
                    'Event'  => 'Payslip Management Authorized',
                    'UserId' => $me->UserId
                ));

                $request->session()->put('access.payslipmanagement', 'Authorized');

                return 1;
            }

        }

        return 0;
    }

    public function changeAuthorizationPassword(Request $request)
    {
        $password = $request->Password;
        $newPassword = $request->NewPassword;

        $hashed = DB::table('accesspasswords')->select('Password')->where('Access',  'Payslip_Management')->first();


        if ($hashed) {

            if (Hash::check($password, $hashed->Password)) {
                $hashedPassword = Hash::make($newPassword);

                DB::table('accesspasswords')->select('Password')->where('Access',  'Payslip_Management')->update([
                    'Password' => $hashedPassword
                ]);

                return 1;
            }

        }

        return 0;

    }

    public function payslipmanagement(Request $request, $includeResigned = 'false', $includeInactive = 'false')
    {

        $me = (new CommonController)->get_current_user();

        $authorized = $request->session()->get('access.payslipmanagement');

        if (! $authorized) {
            return view('payslipmanagementunauthorized', ['me' => $me]);
        }

        $cond = "1";
        if ($includeResigned == 'false') {

            $today = date('d-M-Y', strtotime('today'));
            $cond.=' AND (users.Resignation_Date = "" OR str_to_date(users.Resignation_Date,"%d-%M-%Y") >= str_to_date("'.$today.'","%d-%M-%Y"))';

        }

        if ($includeInactive == 'false') {

            $cond.=' AND users.Active = 1';

        }

        $users = DB::table('users')
        ->select('Id','Password_Last_Notified','Payslip_Password','StaffId','Name','NRIC','Joining_Date','Confirmation_Date','Position','Grade','Company')
        ->whereRaw($cond)
        ->get();

        $filePath = $request->input('path', 'payslips');

        if (strpos($filePath, '.') !== false) {
            $filePath = 'payslips';
        }



        $directories = Storage::directories($filePath);
        $files = Storage::files($filePath);

        usort($directories, function ($a, $b) {
            $monthA = date_parse(basename($a));
            $monthB = date_parse(basename($b));
            return $monthA["month"] - $monthB["month"];
        });



        return view('payslipmanagement', ['users' => $users, 'me' => $me, 'includeResigned' => $includeResigned, 'includeInactive' => $includeInactive, 'directories' => $directories, 'files' => $files]);
    }


    public function changepassword(Request $request)
    {
        $input = $request->all();

        $ids = explode(",", $input["Ids"]);
        $password = $input["Password"];

        DB::table('users')->whereIn('Id', $ids)->update([
            'Payslip_Password' => $password
        ]);

        return 1;
    }

    public function uploadpayslip(Request $request)
    {
        if ($request->file('file')->isValid()) {
            $file = $request->file('file');
            $month = $request->Payslip_Month;
            $year = $request->Payslip_Year;
            $fileName = $file->getClientOriginalName();
            $destinationPath = storage_path("app/payslips/$year/$month");
            $request->file('file')->move($destinationPath, $fileName);

            return $destinationPath;
        }

        return 0;
    }

    public function generatepassword(Request $request)
    {
        $input = $request->all();
        $ids = explode(",", $input["Ids"]);

        foreach ($ids as $id) {
            DB::table('users')->where('Id',$id)->update(['Payslip_Password' => mt_rand(100000, 999999)]);
        }

        return 1;
    }

    public function removepayslip(Request $request)
    {
        $file = $request->file;

        $directory = dirname($file);
        // prevent deleting other file
        if (strpos($directory, '.') !== false) {
            return 0;
        }

        $len = strlen('payslips');
        // prevent deleting other file
        if (! (substr($directory, 0, $len) === 'payslips')) {
            return 0;
        }

        Storage::delete($file);

        return 1;
    }

    public function viewpayslip(Request $request)
    {
        $me = (new CommonController)->get_current_user();

        if ($me->Payslip_Management) {

            $authorized = $request->session()->get('access.payslipmanagement');

            if (! $authorized) {
                return redirect('payslipmanagement');
            }

            $file = $request->file;

            $directory = dirname($file);
            // prevent viewing other file
            if (strpos($directory, '.') !== false) {
                abort(404);
            }

            $len = strlen('payslips');
            // prevent viewing other file
            if (! (substr($directory, 0, $len) === 'payslips')) {
                abort(404);
            }


            $exist = Storage::has($file);

            if ($exist) {

                $filename = ucwords(preg_replace('/[^a-zA-Z0-9_]/', '_', dirname($file))) . basename($file);

                $storageFile = storage_path('app/' . $file);



                $headers = [
                  'Content-Type' => 'application/pdf',
                ];


                return response()->download($storageFile, $filename , $headers);

            }

            //file not found
            abort(404);
        }

        return view('errors.denied', ['me' => $me]);
    }

    public function sendPasswordToSelectedUsers(Request $request)
    {
        ini_set('max_execution_time', 1800); //600 seconds = 10 minutes

        $input = $request->all();
        $ids = explode(",", $input["Ids"]);

        $users = DB::table('users')
                    ->select('users.Id','users.Name', 'users.Company_Email', 'users.Personal_Email' ,'users.Payslip_Password', 'users.Player_Id')
                    ->whereNotNull('Payslip_Password')
                    ->whereIn('users.Id', $ids)
                    ->get();

        foreach ($users as $user) {

            if ($user->Player_Id) {
                $playerids = array($user->Player_Id);

                $title  = 'Payslip Password';
                $body   = 'Your Payslip Password Is: ' . $user->Payslip_Password;
                $buttons = [["id" =>  "id1", "text" => "Confirm"]];

                $this->pushNotification($playerids, $title, $body, $buttons);

            }

            // if ($user->Company_Email != "") {
            //     Mail::send('emails.payslippassword', ['user' => $user], function($message) use ($user) {
            //         $message->to($user->Company_Email)->subject('Payslip Password ['.$user->Name.']');
            //     });


            // } else {
            //     Mail::send('emails.payslippassword', ['user' => $user], function($message) use ($user) {
            //         $message->to($user->Personal_Email)->subject('Payslip Password ['.$user->Name.']');
            //     });

            // }

            DB::table('users')->where('users.Id', $user->Id)->update(['Password_Last_Notified' => date('d-M-Y H:i:s')]);

        }

        return 1;

    }

    public function sendPasswordToActiveUsers()
    {
        ini_set('max_execution_time', 1800); //600 seconds = 10 minutes

        $users = DB::table('users')
                    ->select('users.Id', 'users.Name', 'users.Company_Email', 'users.Personal_Email' ,'users.Payslip_Password', 'users.Player_Id')
                    ->whereNotNull('Payslip_Password')
                    ->where('users.Active', 1)
                    ->get();

        foreach ($users as $user) {

            if ($user->Player_Id) {
                $playerids = array($user->Player_Id);

                $title  = 'Payslip Password';
                $body   = 'Your Payslip Password Is: ' . $user->Payslip_Password;
                $buttons = [["id" =>  "id1", "text" => "Confirm"]];

                $this->pushNotification($playerids, $title, $body, $buttons);

            }

            // if ($user->Company_Email != "") {
            //     Mail::send('emails.payslippassword', ['user' => $user], function($message) use ($user) {
            //         $message->to($user->Company_Email)->subject('Payslip Password ['.$user->Name.']');
            //     });

            // } elseif ($user->Company_Email != "") {
            //     Mail::send('emails.payslippassword', ['user' => $user], function($message) use ($user) {
            //         $message->to($user->Personal_Email)->subject('Payslip Password ['.$user->Name.']');
            //     });

            // }

            DB::table('users')->where('users.Id', $user->Id)->update(['Password_Last_Notified' => date('d-M-Y H:i:s')]);

        }

        return 1;

    }

    function pushNotification($playerids, $title, $body, $buttons = null){

        $content = array(
            "en" => $body
        );

        $heading = array(
            "en" => $title
        );

        $fields = array(
            'include_player_ids' => $playerids,
            'app_id' => "b22a7a60-2cfa-4641-a309-4720c564fddf",
            'contents' => $content,
            'headings' => $heading,
            'buttons' => $buttons,
            'data' => array("type" => "PayslipPassword")
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
