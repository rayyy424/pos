<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\URL;
use App\Http\Controllers\CommonController;

class ScopeofWorkController extends Controller {

	public function __construct()
	{
		$this->middleware('auth');
	}

	public function index()
	{

		$me = (new CommonController)->get_current_user();

    $type = DB::table('options')
		->select('options.Id','options.Table','options.Field','options.Option')
		->where('options.Table', '=','scopeofwork')
		->where('options.Field', '=','Type')
		->orderBy('options.Field', 'asc')
		->get();

		$scopeofwork = DB::table('scopeofwork')
		->select('scopeofwork.Id','scopeofwork.Type','scopeofwork.Code','scopeofwork.Scope_Of_Work','scopeofwork.KPI','scopeofwork.Incentive_1','scopeofwork.Incentive_2','scopeofwork.Incentive_3','scopeofwork.Incentive_4','scopeofwork.Incentive_5','users.Name')
		->leftJoin('users', 'users.Id', '=', 'scopeofwork.UserId')
		->orderBy('scopeofwork.Type', 'ASC')
		->orderBy('scopeofwork.Code', 'ASC')
		->get();

		return view('scopeofwork', ['me' => $me, 'type'=>$type,'scopeofwork' => $scopeofwork]);

	}

}
