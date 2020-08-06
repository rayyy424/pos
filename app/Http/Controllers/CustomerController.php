<?php namespace App\Http\Controllers;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use DB;
use Illuminate\Http\Request;

class CustomerController extends Controller {

	public function __construct()
	{
		$this->middleware('auth');
	}

	/**
	 * Display a listing of the vendors.
	 *
	 * @return Response
	 */
	public function index($type,$company=null)
	{
		$me = (new CommonController)->get_current_user();

		$cond="1";

		if($company && $company!="false")
		{
			$cond.=" AND companies.Company_Account='".$company."'";
		}

		$customers = DB::table('companies')
		->leftJoin( DB::raw('(select Max(Id) as maxid,TargetId from files where Type="Company Logo" Group By Type,TargetId) as max'), 'max.TargetId', '=', 'companies.Id')
		->leftJoin('files', 'files.Id', '=', DB::raw('max.`maxid`'))
		->select('companies.Id','companies.Status','companies.Company_Account','companies.Company_Name','companies.Register_Num','companies.Company_Code','companies.CreditorCode','companies.Initial','companies.type','companies.Person_In_Charge','companies.attention','companies.bank','companies.bank_acct','companies.Contact_No','companies.Office_No','companies.Fax_No','companies.Email','companies.Address','companies.subcon','companies.Client','companies.Subsidiary','companies.Supplier','companies.Remarks','companies.created_at','companies.updated_at','files.Web_Path')
		->whereRaw($cond);

		switch ($type) {
			case "All":
				$customers = $customers->get();
			break;
		    case "Client":
			    $customers = $customers->where('companies.Client', '=', 'Yes')->get();
		    break;
		    case "Subsidiary":
			    $customers = $customers->where('companies.Subsidiary', '=', 'Yes')->get();
		    break;
		    case "Supplier":
			    $customers = $customers->where('companies.Supplier', '=', 'Yes')->get();
		    break;
		}

		$options= DB::table('options')
		->whereIn('Table', ["company"])
		->orderBy('Table','asc')
		->orderBy('Option','asc')
		->get();

		$category= DB::table('options')
		->where('Table', '=','company')
		->where('Field', '=','Type')
		->orderBy('Table','asc')
		->orderBy('Option','asc')
		->get();

		$companies= DB::table('options')
		->whereIn('Table', ["users"])
		->where('Field','=','Company')
		->orderBy('Table','asc')
		->orderBy('Option','asc')
		->get();

		// dd($customers);

		return view('customermanagement', ['me' => $me, 'customers' => $customers,'options' => $options,'category'=>$category,'type'=>$type,'companies'=>$companies,'company'=>$company]);
	}
}
