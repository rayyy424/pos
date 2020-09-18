<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\URL;
use App\Http\Controllers\CommonController;

use Carbon\Carbon;
use JWTAuth;
use Tymon\JWTAuth\Exceptions\JWTException;
use Storage;
use DateTime;

class SalaryController extends Controller {

    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard to the user.
     *
     * @return Response
     */
    public function index($start = null, $end = null, $type = null)
    {
        $me = (new CommonController)->get_current_user();

        $salary = DB::table('salary')
        ->select('users.Name','salary.Salary','salary.Remarks','us.Name','salary.created_at')
        ->leftJoin('users', 'users.Id', '=', 'salary.UserId')
        ->leftJoin('users as us', 'us.Id','=','salary.Created_By')
        ->orderBy('users.Name','asc')
        ->get();
        // dd($salary);
        

        // foreach($salary as $key=>$values){
        //     dd($key,$values);
        // }

    return view('salary', ['me' => $me, 'salary'=>$salary]);

        

    }
}