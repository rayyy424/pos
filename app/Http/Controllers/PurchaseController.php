<?php namespace App\Http\Controllers;

use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\URL;
use App\Http\Controllers\CommonController;
use Illuminate\Support\Facades\Mail;

class PurchaseController extends Controller {

	/*
	|--------------------------------------------------------------------------
	| Welcome Controller
	|--------------------------------------------------------------------------
	|
	| This controller renders the "marketing page" for the application and
	| is configured to only allow guests. Like most of the other sample
	| controllers, you are free to modify or remove it as you desire.
	|
	*/

	/**
	 * Create a new controller instance.
	 *
	 * @return void
	 */


	public function pr()
	{
		$me = (new CommonController)->get_current_user();
		return view("pr",['me'=>$me]);
		//
		// $html=view('pr', []);
		// (new ExportPDFController)->Export($html);

	}
	public function prpdf()
	{
		// $me = (new CommonController)->get_current_user();
		// return view("pr",['me'=>$me]);
		//
		$html=view('prpdf', []);
		(new ExportPDFController)->Export($html);

	}

	public function esar()
	{
		$me = (new CommonController)->get_current_user();
		return view("esar",['me'=>$me]);


	}
	public function esarpdf()
	{
		$html=view('esarpdf', []);
		(new ExportPDFController)->Export($html);

	}

}
