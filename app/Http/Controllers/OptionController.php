<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\URL;
use App\Http\Controllers\CommonController;

class OptionController extends Controller {

	public function __construct()
	{
		$this->middleware('auth');
	}

	/**
	 * Show the application dashboard to the user.
	 *
	 * @return Response
	 */
	public function getoptions($table,$type)
	{
		$me = DB::table('options')
		->leftJoin( DB::raw('(select Max(Id) as maxid,TargetId from files where Type="User" Group By TargetId) as max'), 'max.TargetId', '=', 'users.Id')
		->leftJoin('files', 'files.Id', '=', DB::raw('max.`maxid` and files.`Type`="User"'))
		// ->leftJoin('accesscontrols', 'accesscontrols.UserId', '=', 'users.Id')
		->where('users.Id', '=',$auth -> Id)
		->first();

	}

	public function Index($type,$all=null)
	{

		$me = (new CommonController)->get_current_user();

		$category=DB::table('fieldproperties')
		->distinct('fieldproperties.Table')
		->select('fieldproperties.Table','fieldproperties.Category')
		->where(function ($query) {
			$query->where('fieldproperties.Field_Type', '=', 'Dropdown')
			->orWhere('fieldproperties.Field_Type', '=', 'Checkbox')
        	->orWhere('fieldproperties.Field_Type', '=', 'Radio Button');
		})
		->where('fieldproperties.Category', '<>','')
		->orderBy('fieldproperties.Category')
		->get();

		if($type=="Report Store")
		{
			$options = DB::table('options')
			->select('options.Id','options.Table','options.Field','options.Option','projects.Project_Name','options.Update_Column','options.Section','options.Description')
			->leftJoin('optionprojects','options.Id','=','optionprojects.OptionId')
			->leftJoin('projects','projects.Id','=','optionprojects.ProjectId')
			->where('options.Field', '=','Document_Type')
			->orderBy('options.Field', 'asc')
			->get();

		}
		else {
			// code...
			$options = DB::table('options')
			->select('options.Id','options.Table','options.Field','options.Option','projects.Project_Name','options.Update_Column','options.Section','options.Description')
			->leftJoin('optionprojects','options.Id','=','optionprojects.OptionId')
			->leftJoin('projects','projects.Id','=','optionprojects.ProjectId')
			->where('options.Table', '=',$type)
			->orderBy('options.Field', 'asc')
			->get();
		}

		$field=DB::table('fieldproperties')
		->distinct('fieldproperties.Field_Name')
		->select('fieldproperties.Field_Name')
		->where(function ($query) {
    		$query->where('fieldproperties.Field_Type', '=', 'Dropdown')
			->orWhere('fieldproperties.Field_Type', '=', 'Checkbox')
          	->orWhere('fieldproperties.Field_Type', '=', 'Radio Button');
		})
		->where('fieldproperties.Category', '<>','')
		->where('fieldproperties.Table', '=',$type)
		->orderBy('fieldproperties.Field_Name')
		->get();

		if($all)
		{
			$category=DB::table('fieldproperties')
			->distinct('fieldproperties.Table')
			->select('fieldproperties.Table','fieldproperties.Category')
			->where(function ($query) {
				$query->where('fieldproperties.Field_Type', '=', 'Dropdown')
				->orWhere('fieldproperties.Field_Type', '=', 'Checkbox')
				->orWhere('fieldproperties.Field_Type', '=', 'Radio Button');
			})
			->where('fieldproperties.Category', '<>','')
			->where('fieldproperties.Table', '=',$type)
			->orderBy('fieldproperties.Category')
			->get();
		}

		$columns= DB::table('trackercolumn')
		->select(DB::raw('DISTINCT (Column_Name) As Col'))
		->orderBy('Column_Name')
		->get();

		return view('option', ['me' => $me, 'type'=>$type,'options' => $options,'category'=>$category,'field'=>$field,'all'=>$all,'columns'=>$columns]);

	}

}
