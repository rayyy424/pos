<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\URL;
use App\Http\Controllers\CommonController;

class HolidayController extends Controller {

	public function __construct()
	{
		$this->middleware('auth');
	}

	/**
	 * Show the application dashboard to the user.
	 *
	 * @return Response
	 */
	public function index($year)
	{
		$me = (new CommonController)->get_current_user();

    // $years = DB::table('holidays')
    // ->select(DB::raw('Distinct right(Start_Date,4) as yearname'))
    // ->orderBy('holidays.Start_Date','desc')
    // ->get();

		$years= DB::select("
			SELECT Year(Now())-1 as yearname UNION ALL
			SELECT Year(Now()) UNION ALL
			SELECT Year(Now())+1
			");

    $holidays = DB::table('holidays')
    ->select('holidays.Id','holidays.Holiday','holidays.Start_Date','holidays.End_Date','holidays.State','holidays.Country')
    ->orderBy(DB::raw('str_to_date(holidays.Start_Date,"%d-%M-%Y")'),'asc')
    ->where(DB::raw('right(Start_Date,4)'), $year)
    ->get();

    $options= DB::table('options')
    ->whereIn('Table', ["users","claims"])
    ->orderBy('Table','asc')
    ->orderBy('Option','asc')
    ->get();

    return view('holidaymanagement', ['me' => $me,'holidays' => $holidays,'years' =>$years,'year' =>$year,'options' =>$options]);

	}

    public function territories()
    {
        $me = (new CommonController)->get_current_user();

        $territories = DB::table('holidayterritories')->get();

        return view('holidayterritories', ['me' => $me,'territories' => $territories]);
    }

    public function territorydays($id, $year)
    {
        $me = (new CommonController)->get_current_user();

        $years= DB::select("
            SELECT Year(Now())-1 as yearname UNION ALL
            SELECT Year(Now()) UNION ALL
            SELECT Year(Now())+1
        ");

        $holidays = DB::table('holidayterritorydays')
            ->select('holidayterritorydays.Id','holidayterritorydays.Holiday','holidayterritorydays.Start_Date','holidayterritorydays.End_Date','holidayterritorydays.State','holidayterritorydays.Country', 'holidayterritorydays.HolidayTerritoryId')
            ->orderBy(DB::raw('str_to_date(holidayterritorydays.Start_Date,"%d-%M-%Y")'),'asc')
            ->where(DB::raw('right(Start_Date,4)'), $year)
            ->where('holidayterritorydays.HolidayTerritoryId', $id)
            ->get();

        $options= DB::table('options')
        ->whereIn('Table', ["users","claims"])
        ->orderBy('Table','asc')
        ->orderBy('Option','asc')
        ->get();

        $territories = DB::table('holidayterritories')->get();
        return view('holidayterritorydays', ['me' => $me,'holidays' => $holidays,'years' =>$years,'year' =>$year,'options' =>$options, 'id' => $id, 'territories' => $territories]);
    }

    public function duplicate(Request $request, $id, $year)
    {
        $input = $request->all();

        $name =         isset($input['Name']) && $input['Name']  ? $input['Name'] : 'New Duplicated Holiday';
        $newYear =         isset($input['Year']) && $input['Year'] ? $input['Year'] : date('Y');
        $description =  isset($input['Description']) && $input['Description'] ? $input['Description'] : '';
        $remark =       isset($input['Remark']) && $input['Remark'] ? $input['Remark'] : '';

        $holidays = DB::table('holidayterritorydays')
            ->select('holidayterritorydays.Id','holidayterritorydays.Holiday','holidayterritorydays.Start_Date','holidayterritorydays.End_Date','holidayterritorydays.State','holidayterritorydays.Country', 'holidayterritorydays.HolidayTerritoryId')
            ->orderBy(DB::raw('str_to_date(holidayterritorydays.Start_Date,"%d-%M-%Y")'),'asc')
            ->where(DB::raw('right(Start_Date,4)'), $year)
            ->where('holidayterritorydays.HolidayTerritoryId', $id)
            ->get();

        $territoryId = DB::table('holidayterritories')->insertGetId([
            'Name' => $name,
            'Description' => $description,
            'Remark' => $remark
        ]);

        foreach($holidays as $holiday) {
            DB::table('holidayterritorydays')->insert([
                'Holiday' => $holiday->Holiday,
                'Start_Date' => str_replace($year, $newYear, $holiday->Start_Date),
                'End_Date' => str_replace($year, $newYear, $holiday->End_Date),
                'State' => $holiday->State,
                'Country' => $holiday->Country,
                'HolidayTerritoryId' => $territoryId
            ]);
        }

        return redirect("holidaymanagement/territory/$territoryId/$newYear")->with('success', 'Successfully duplicated!');
    }

    public function duplicatenextyear(Request $request, $id, $year)
    {
        $input = $request->all();


        $holidays = DB::table('holidayterritorydays')
            ->select('holidayterritorydays.Id','holidayterritorydays.Holiday','holidayterritorydays.Start_Date','holidayterritorydays.End_Date','holidayterritorydays.State','holidayterritorydays.Country', 'holidayterritorydays.HolidayTerritoryId')
            ->orderBy(DB::raw('str_to_date(holidayterritorydays.Start_Date,"%d-%M-%Y")'),'asc')
            ->where(DB::raw('right(Start_Date,4)'), $year)
            ->where('holidayterritorydays.HolidayTerritoryId', $id)
            ->get();


        $newYear = intval($year) + 1;
        foreach($holidays as $holiday) {
            DB::table('holidayterritorydays')->insert([
                'Holiday' => $holiday->Holiday,
                'Start_Date' => str_replace($year, $newYear, $holiday->Start_Date),
                'End_Date' => str_replace($year, $newYear, $holiday->End_Date),
                'State' => $holiday->State,
                'Country' => $holiday->Country,
                'HolidayTerritoryId' => $id
            ]);
        }

        return redirect("holidaymanagement/territory/$id/$newYear")->with('success', 'Successfully duplicated!');
    }

}
