
<?php

use Illuminate\Support\Facades\App;
// use Illuminate\Contracts\Broadcasting\ShouldBroadcast;

/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| Here is where you can register all of the routes for an application.
| It's a breeze. Simply tell Laravel the URIs it should respond to
| and give it the controller to call when that URI is requested.
|
*/

Route::get('tester','LeaveController@getLeaveCalculation');
Route::get('/', 'HomeController@index');
Route::get('deliveryorderpdf/{id}','HomeController@deliveryorderpdf');

Route::get('home', 'HomeController@index');

// Route::get('/', function() {
// 		return view('errors/migrate');
// });
//
// Route::get('home', function() {
// 		return view('errors/migrate');
// });


Route::controllers([
	'password' => 'Auth\PasswordController',
		'auth' => 'Auth\AuthController',
]);

Route::get('/login', 'Auth\AuthController@getLogin');
Route::get('/register', 'Auth\AuthController@showRegistrationForm');

Route::get('auth/register', 'Auth\AuthController@showRegistrationForm');
Route::get('auth/logout', 'Auth\AuthController@logout');

Route::group(['prefix' => 'api'], function() {

	Route::get('dumpdb', function () {

	    $dbusername = env('DB_USERNAME');
	    $dbpassword = env('DB_PASSWORD');
	    $dbname = env('DB_DATABASE');

	    $currentdate = date('Y_m_d_His');
	    $path = storage_path('app');

	    $outputname = $path . '/' . $dbname . '_' . $currentdate . '.sql';

	    if ($dbpassword) {
	        exec("mysqldump -u {$dbusername} -p{$dbpassword} {$dbname} --single-transaction --quick --lock-tables=false | gzip > {$outputname}.gz");
	    } else {
	        exec("mysqldump -u {$dbusername} {$dbname} --single-transaction --quick --lock-tables=false | gzip > {$outputname}.gz");
	    }

	    return 1;
	});

	Route::get('testgoogleapi/{lat1}/{long1}/{lat2}/{long2}','Api\DeliveryController@test');
	Route::get('checkdriverresponse','Api\DeliveryController@checkdriverresponse');
	Route::get('updatemonthlypresavings','Api\UserController@updatemonthlypresavings');
	Route::get('lastweekmedicalleavecheck','Api\LeaveController@lastweekmedicalleavecheck');
	Route::get('confirmationnotification','Api\UserController@confirmationnotification');
	Route::get('yesterdaytimesheet','Api\TimesheetController@yesterdaytimesheet');
	Route::get('calculateDistance/{timesheetid}','Api\TimesheetController@calculateDistance');
	Route::get('getMIA','Api\TimesheetController@getMIA');
	Route::get('todonotification','Api\TrackerController@todonotification');
	Route::get('taskchecking','Api\TrackerController@taskchecking');
	Route::get('repeattask','Api\TimesheetController@repeattask');
	Route::get('timediffoftimesheet','Api\TimesheetController@timediffoftimesheet');

    Route::post('login', 'Api\AuthController@login');
	Route::post('postplayerid', 'Api\TestController@postplayerid');

	Route::post('getadmin', 'Api\AuthController@getadmin');

	Route::get('getuser', 'Api\AuthController@getuser');

	Route::post('clearplayerid', 'Api\TestController@clearplayerid');

    Route::group(['middleware' => ['jwt.auth', 'jwt.refresh']], function() {
	Route::post('logout', 'Api\AuthController@logout');

	Route::get('test', function(){
	    return response()->json(['foo'=>'bar']);

        });
    });

    	Route::post('authenticatepayslip', 'Api\UserController@authenticatepayslip');
    	Route::get('downloadpayslip', 'Api\UserController@downloadpayslip');
		Route::get('scopeofwork', 'Api\ScopeofWorkController@index');

		Route::get('mobilemyrequest', 'Api\ClaimController@myrequest');
		Route::post('myadvance/apply', 'Api\ClaimController@applyadvance');

		Route::get('todaydelivery', 'Api\DeliveryController@todaydelivery');
		Route::get('myalldelivery', 'Api\DeliveryController@myalldelivery');
		Route::get('myprocessingdelivery', 'Api\DeliveryController@myprocessingdelivery');
		Route::get('getdeliveryapproved', 'Api\DeliveryController@getdeliveryapproved');
		Route::get('myacceptdelivery', 'Api\DeliveryController@myacceptdelivery');
		Route::get('mycompletedelivery', 'Api\DeliveryController@mycompletedelivery');
		Route::get('opentrip', 'Api\DeliveryController@opentrip');
		Route::get('checkpickup', 'Api\DeliveryController@checkpickup');
		Route::get('checkstartdelivery', 'Api\DeliveryController@checkstartdelivery');
		Route::get('checkcompletedelivery', 'Api\DeliveryController@checkcompletedelivery');
		Route::get('getitem', 'Api\DeliveryController@getitem');
		Route::post('transferdelivery', 'Api\DeliveryController@transferdelivery');
		Route::post('accepttrip', 'Api\DeliveryController@accepttrip');
		Route::post('opentripdetails', 'Api\DeliveryController@opentripdetails');
		Route::post('pickup', 'Api\DeliveryController@pickup');
		Route::post('startdelivery', 'Api\DeliveryController@startdelivery');
		Route::post('completedelivery', ['middleware'=>'cors','uses'=>'Api\DeliveryController@completedelivery']);
		Route::post('completedelivery2', ['middleware'=>'cors','uses'=>'Api\DeliveryController@completedelivery2']);
		Route::post('incompletedelivery2', 'Api\DeliveryController@incompletedelivery2');
		// 14 Feb 2020 Kye Peng (Midascom Logistics 2)
		Route::get('getdriverhistory', 'Api\DeliveryController@getdriverhistory');
		Route::get('getincentiverecord', 'Api\DeliveryController@getincentiverecord');
		Route::get('getSOdetails', 'Api\DeliveryController@getSOdetails');
		Route::get('combinetrip', 'Api\DeliveryController@combinetrip');
		// Route::post('delivery/submitimages', 'Api\DeliveryController@submitimages');

		Route::post('checkitem', 'Api\DeliveryController@checkitem');

		Route::post('newtimesheet', 'Api\TimesheetController@newtimesheet');
		// Route::post('newtimesheetitem', 'Api\TimesheetController@newtimesheetitem');
		Route::post('mytimesheet', 'Api\TimesheetController@mytimesheet');
		Route::get('getSite', 'Api\TimesheetController@getSite');

		Route::post('timeout', 'Api\TimesheetController@timeout');
		Route::get('gettimesheets', 'Api\TimesheetController@gettimesheets');
		Route::get('gettimesheetoption', 'Api\TimesheetController@getoptions');
		Route::get('getsitelist', 'Api\TimesheetController@getsitelist');
		Route::post('calculateallowance', 'Api\TimesheetController@calculateallowance');
		// Route::get('gettimesheetitems', 'Api\TimesheetController@gettimesheetitems');


		Route::post('newclaim', 'Api\ClaimController@newclaim');
		Route::post('newclaimsheet', 'Api\ClaimController@newclaimsheet');
		// Route::post('newclaimitem', 'Api\ClaimController@newclaimitem');
		Route::get('getclaims', 'Api\ClaimController@getclaims');
		Route::get('getclaimsheet', 'Api\ClaimController@getclaimsheet');
		Route::get('getclaimoption', 'Api\ClaimController@getoptions');
		// Route::get('getclaimitems', 'Api\ClaimController@getclaimitems');

		Route::get('fetchCalculatedLeaveDays', 'Api\LeaveController@fetchCalculatedLeaveDays');
    	Route::get('fetchLeaveTerms/{id}', 'Api\LeaveController@fetchLeaveTerms');
		Route::post('newleavewithperiod', 'Api\LeaveController@newLeaveWithPeriod');
		Route::get('myleavebalance', 'Api\LeaveController@getMyLeaveBalance');

		Route::post('newleave', 'Api\LeaveController@newleave');
		Route::get('getallleaves', 'Api\LeaveController@getallleaves');
		Route::get('getleaves', 'Api\LeaveController@getleaves');
		Route::get('getapprover', 'Api\LeaveController@getapprover');
		Route::get('getleaveoption', 'Api\LeaveController@getoptions');
		Route::post('adminapproval', 'Api\LeaveController@adminapproval');
		Route::post('adminrejected', 'Api\LeaveController@adminrejected');
		Route::get('myApprover', 'Api\LeaveController@myApprover');
		Route::get('myAllApprover', 'Api\LeaveController@myAllApprover');
		Route::post('redirect', 'Api\LeaveController@redirect');
		Route::post('redirect2', 'Api\LeaveController@redirect2');
		Route::post('cancelleave', 'Api\LeaveController@cancelleave');
		Route::get('getleavesapproved', 'Api\LeaveController@getleavesapproved');
		Route::get('getleavesrejected', 'Api\LeaveController@getleavesrejected');
		Route::get('getleavescancelled', 'Api\LeaveController@getleavescancelled');

		Route::get('getprojects', 'Api\ProjectController@getprojects');
		Route::get('getdepartments', 'Api\ProjectController@getdepartments');
		Route::get('getprojectcodes', 'Api\ProjectController@getprojectcodes');

		Route::post('upload', 'Api\UploadController@upload');
		Route::post('importpctracking', 'Api\UploadController@importpctracking');

		Route::post('setavailability', 'Api\AvailabilityController@setavailability');
		Route::get('getavailability', 'Api\AvailabilityController@getavailability');

		Route::get('getnotice', 'Api\NoticeController@getnotice');

		Route::get('latenotification', 'Api\TimesheetController@latenotification');
		Route::get('probationnotification', 'Api\UserController@probationnotification');

		Route::get('populatetimesheet', 'Api\TimesheetController@populatetimesheet');

		Route::get('holiday', 'Api\HolidayController@index');

		// Leave notification
		Route::get('notifications/allleave','Api\NotificationController@getallleave');
		Route::get('notifications/leaveall','Api\NotificationController@getleaveall');
		Route::get('notifications/leavepending','Api\NotificationController@getleavepending');
    	Route::post('notifications/updateleavepending','Api\NotificationController@updateleavepending');
    	Route::get('notifications/leaveapproved','Api\NotificationController@getleaveapproved');
    	Route::post('notifications/updateleaveapproved','Api\NotificationController@updateleaveapproved');
    	Route::get('notifications/leaverejected','Api\NotificationController@getleaverejected');
    	Route::post('notifications/updateleaverejected','Api\NotificationController@updateleaverejected');
		Route::get('notifications/leavecancelled','Api\NotificationController@getleavecancelled');
    	Route::post('notifications/updateleavecancelled','Api\NotificationController@updateleavecancelled');
		Route::get('notifications/getnoticebadge','Api\NotificationController@getnoticebadge');
    	Route::post('notifications/updatenoticebadge','Api\NotificationController@updatenoticebadge');
    	Route::get('notifications/leaveredirect','Api\NotificationController@getleaveredirect');
    	Route::post('notifications/updateleaveredirect','Api\NotificationController@updateleaveredirect');

    	Route::get('notifications/gettaskbadge','Api\NotificationController@gettaskbadge');
    	Route::get('notifications/getopentripbadge','Api\NotificationController@getopentripbadge');

		// 	// Incentive
		// Route::get('incentivesummary/{Month?}/{Year?}', 'Api\TimesheetController@incentivesummary');
		// Route::post('incentivesummary/{Month?}/{Year?}', 'Api\TimesheetController@incentivesummary');

		Route::get('getincentive/{Month?}/{Year?}', 'Api\TimesheetController@getincentive');
		Route::get('getdeduction/{Month?}/{Year?}', 'Api\TimesheetController@getdeduction');

		// E-Wallet
		// SiteController
		Route::get('getsitedepartment', 'Api\SiteController@getsitedepartment');
		Route::get('getsiteprojectcodes/{Id?}', 'Api\SiteController@getsiteprojectcodes');
		Route::get('getewalletoptions', 'Api\SiteController@getewalletoptions');


		Route::get('getbalance', 'Api\UserController@getbalance');
		Route::post('getrecord', 'Api\UserController@getrecord');
		Route::post('insertexpenses', 'Api\UserController@insertexpenses');
		Route::post('deletehistory', 'Api\UserController@deletehistory');

		Route::post('accepttask', 'Api\TrackerController@accepttask');
		Route::post('rejecttask', 'Api\TrackerController@rejecttask');

		// TrackerController
		Route::get('getmypendingtask', 'Api\TrackerController@getmypendingtask');
		Route::get('getmypendingtaskcount', 'Api\TrackerController@getmypendingtaskcount');
		Route::get('getmyacknowledgedtask', 'Api\TrackerController@getmyacknowledgedtask');
		Route::get('getmycompletedtask', 'Api\TrackerController@getmycompletedtask');

		Route::get('notifypendingtasks', 'Api\TrackerController@notifypendingtasks');
		Route::get('getnextrejectedtask', 'Api\TrackerController@getnextrejectedtask');
		Route::get('getrejectedtaskbadge', 'Api\NotificationController@getrejectedtaskbadge');
		Route::post('updaterejectedtaskbadge', 'Api\NotificationController@updaterejectedtaskbadge');
		Route::post('changetaskstatus', 'Api\TrackerController@changetaskstatus');

		Route::get('getalltask', 'Api\NotificationController@getalltask');

		Route::get('recurringSalesOrder', 'Api\SalesController@recurringSalesOrder');
		Route::get('removeduplicate', 'Api\SalesController@removeduplicate');
		Route::get('tresholdnotification', 'Api\InventoryController@tresholdnotification');

		// Service ticket
		Route::get('serviceticket/getService','Api\ServiceController@getServiceTicket');
		Route::get('serviceticket/getCompleted','Api\ServiceController@getServiceTicketCompleted');
		Route::get('serviceticket/getLeaderCompleted','Api\ServiceController@getServiceTicketLeaderCompleted');

		Route::get('serviceticket/getApproved','Api\ServiceController@getServiceTicketApproved');
		Route::post('serviceticket/updateService','Api\ServiceController@updateService');
		Route::post('serviceticket/approveService','Api\ServiceController@approveService');
		Route::get('serviceticket/getItem','Api\ServiceController@getItem');
		Route::post('serviceticket/replacement','Api\ServiceController@replacement');
		Route::post('serviceticket/startTask','Api\ServiceController@startTask');
		Route::post('serviceticket/startTask2','Api\ServiceController@startTask2');

		Route::post('serviceticket/endTask','Api\ServiceController@endTask');

		Route::post('serviceticket/requestItem','Api\ServiceController@requestItem');
		Route::post('serviceticket/requestItemtest','Api\ServiceController@requestItemtest');

		Route::get('serviceticket/getItemOption','Api\ServiceController@getItemOption');
		Route::get('serviceticket/getRequisitionItems','Api\ServiceController@getRequisitionItems');
		Route::post('serviceticket/createTicket','Api\ServiceController@createTicket');
		Route::get('serviceticket/getServiceDate','Api\ServiceController@getServiceDate');
		Route::get('serviceticket/getServiceDatetest','Api\ServiceController@getServiceDatetest');

		Route::get('serviceticket/getItemInv','Api\ServiceController@getItemInv');

		Route::get('getgenset','Api\NoticeController@getgenset');
		Route::get('serviceticket/getAssign','Api\ServiceController@getServiceTicketAssign');
		Route::get('systemstockin','Api\ServiceController@systemstockin');



		//Oncall
		Route::get('getoncallprogress','Api\ServiceController@getoncallprogress');
		Route::get('getoncallpending','Api\ServiceController@getoncallpending');
		Route::get('getoncallcompleted','Api\ServiceController@getoncallcompleted');
		Route::post('markcompleted','Api\ServiceController@markcompleted');
		Route::post('createServiceTicket','Api\ServiceController@createServiceTicket');
		Route::get('getServiceDateOncall','Api\ServiceController@getServiceDateOncall');
		Route::post('oncallstartTask','Api\ServiceController@oncallstartTask');
		Route::get('getcompany', 'Api\ServiceController@getcompany');
		Route::get('getsitename', 'Api\ServiceController@getsitename');
		Route::get('getcall', 'Api\ServiceController@getcall');



		Route::post('serviceticket/updateRequisition','Api\ServiceController@updateRequisition');
		Route::get('serviceticket/getRequisition','Api\ServiceController@getRequisition');
		Route::get('serviceticket/getRequisitionDate','Api\ServiceController@getRequisitionDate');

		Route::get('getRequisitionNoti','Api\ServiceController@getRequisitionNoti');

		Route::get('getradius/{Latitude_In?}/{Longitude_In?}', 'Api\TimesheetController@getradius');

		Route::post('otw','Api\TimesheetController@otw');

		Route::get('getlistassigned','Api\NoticeController@getlistassigned');
		Route::get('getlistacknowledge','Api\NoticeController@getlistacknowledge');
		Route::get('getlistrejected','Api\NoticeController@getlistrejected');
		Route::get('getlistcompleted','Api\NoticeController@getlistcompleted');
		Route::get('getlistoverdue','Api\NoticeController@getlistoverdue');

		Route::post('listchangetaskstatus', 'Api\NoticeController@listchangetaskstatus');
		Route::post('listchangeack', 'Api\NoticeController@listchangeack');
		Route::post('listchangecomplete', 'Api\NoticeController@listchangecomplete');
		Route::get('getalllist', 'Api\NoticeController@getalllist');


		Route::post('otw/test','Api\TimesheetController@test');

		Route::post('returnInv','Api\ServiceController@returnInv');

		Route::post('taskchangecomplete', 'Api\TrackerController@taskchangecomplete');
		Route::post('taskchangeack', 'Api\TrackerController@taskchangeack');
		Route::get('getmyoverduetask', 'Api\TrackerController@getmyoverduetask');
		Route::get('getmyoverduetask2', 'Api\TrackerController@getmyoverduetask2');

		Route::get('getoverduetodo','Api\NoticeController@getoverduetodo');

		Route::get('getrequisitionhistory','Api\ServiceController@getrequisitionhistory');
		Route::get('getrequisitionhistorytech','Api\ServiceController@getrequisitionhistorytech');
		Route::post('serviceticket/pendingSparepart','Api\ServiceController@pendingSparepart');


		Route::get('getteammember','Api\ServiceController@getteammember');
		Route::post('assignServiceTicket','Api\ServiceController@assignServiceTicket');
		Route::post('getservicelog','Api\ServiceController@getservicelog');

		Route::get('delivery/getVehicle','Api\DeliveryController@getVehicle');
		// Route::get('delivery/getLorrySize','Api\DeliveryController@getLorrySize');
		Route::get('delivery/getSite','Api\DeliveryController@getSite');
		// Route::get('delivery/getProjects','Api\DeliveryController@getProjects');
		Route::get('delivery/getOptions','Api\DeliveryController@getOptions');
		Route::get('delivery/getPIC','Api\DeliveryController@getPIC');
		// Route::get('delivery/getSalesOrder','Api\DeliveryCOntroller@getSalesOrder');
		Route::get('delivery/getAllOptions','Api\DeliveryController@getAllOptions');
		Route::get('delivery/getItems','Api\DeliveryController@getItems');
		Route::get('delivery/getMR','Api\DeliveryController@getMR');
		Route::get('delivery/getMrItems','Api\DeliveryController@getMrItems');
		Route::post('delivery/submit','Api\DeliveryController@submitDelivery');
		Route::get('requestor/deliveryDetails','Api\DeliveryController@deliveryDetails');
		Route::get('requestor/deliveryDetail','Api\DeliveryController@deliveryDetail');
		Route::get('requestor/getDeliveryItems','Api\DeliveryController@getDeliveryItems');
		Route::get('delivery/calculateDimension','Api\DeliveryController@calculateDimension');
		Route::post('requestor/saveItems','Api\DeliveryController@saveItems');
		Route::post('requestor/saveDeliveryDetails','Api\DeliveryController@saveDeliveryDetails');
		Route::post('requestor/canceldelivery','Api\DeliveryController@canceldelivery');
		Route::post('requestor/resubmit','Api\DeliveryController@resubmit');
		Route::post('requestor/recall','Api\DeliveryController@recall');


		//LoanController
		Route::get('getbank','Api\LoanController@getbank');
		Route::post('submitloan','Api\LoanController@submitloan');
		Route::get('getallloan','Api\LoanController@getallloan');

});

Route::get('accesscontrol', 'AccessControlController@index');
Route::get('accesscontrol/{Id}', 'AccessControlController@accesscontrol');
Route::post('accesscontrol/update', 'AccessControlController@update');
Route::post('accesscontrol/approval', 'AccessControlController@approval');
Route::post('accesscontrol/activate', 'AccessControlController@activate');
Route::post('accesscontrol/savetemplate', 'AccessControlController@savetemplate');
Route::post('accesscontrol/removetemplate', 'AccessControlController@removetemplate');

Route::get('allowancecontrol', 'AllowanceController@index');
Route::post('allowancecontrol/createnewscheme', 'AllowanceController@createnewscheme');
Route::post('allowancecontrol/removescheme', 'AllowanceController@removescheme');

Route::post('accesscontrol/updatedocumenttypeaccess', 'AccessControlController@updatedocumenttypeaccess');

Route::get('documenttypeaccesscontrol/{Id?}/{ProjectId?}', 'AccessControlController@documenttypeaccesscontrol');


// resource management route
Route::get('user/{start?}/{end?}/{type?}', 'UserController@index');
Route::get('userresigned/{start?}/{end?}/{type?}', 'UserController@resigned');
Route::post('user/logout', 'UserController@logout');
Route::get('userdetail/{Id}/{Export?}', 'UserController@userdetail');
Route::get('myprofile', 'UserController@myprofile');
Route::post('user/uploadresume', 'UserController@uploadresume');
Route::post('user/changepassword', 'UserController@changepassword');
Route::post('user/changepassword2', 'UserController@changepassword2');
Route::post('user/changepassword3', 'UserController@changepassword3');
Route::post('user/updateprofile', 'UserController@updateprofile');
Route::post('user/updateprofilepicture', 'UserController@updateprofilepicture');
Route::post('user/approved', 'UserController@approved');
Route::post('user/approveprofile', 'UserController@approveprofile');
Route::post('user/rejectprofile', 'UserController@rejectprofile');

Route::get('contractor', 'UserController@contractor');
Route::post('contractor/create', 'UserController@contractorcreate');
Route::get('contractor/{Id}', 'UserController@contractordetail');
Route::post('contractor/updateprofile', 'UserController@updatecontractorprofile');
Route::post('contractor/uploaddocument', 'UserController@uploaddocument');

Route::get('customers/{type}/{company?}', 'CustomerController@index');

Route::get('orgchart', 'OrgChartController@index');
Route::post('orgchart/update', 'OrgChartController@update');

//
Route::get('checkannualleave/{userId}','LeaveController@checkAnnualLeave');
Route::post('convertemergencyleave','LeaveController@convertEmergencyLeave');
Route::get('checkLeaveBalance/{userId}/{leaveType?}', 'LeaveController@checkLeaveBalance');
Route::get('fetchLeaveTerms/{leaveId}', 'LeaveController@fetchLeaveTerms');
Route::put('updateLeaveTerms', 'LeaveController@updateLeaveTerms');
Route::get('fetchCalculatedLeaveDays', 'LeaveController@fetchCalculatedLeaveDays');
Route::get('fetchCalculatedLeaveDaysForUser', 'LeaveController@fetchCalculatedLeaveDaysForUser');
Route::get('leavebatch', 'LeaveController@leavebatch');
Route::post('leavebatch/apply', 'LeaveController@leavebatchapply');
Route::get('getAdjustedLeave', 'LeaveController@getAdjustedLeave');
Route::post('leavebatch/adjustment', 'LeaveController@leavebatchadjustment');
Route::get('leaveadjustmentshistory/{userId}/{leaveType?}', 'LeaveController@leaveadjustmentshistory');

Route::get('myleave/{start?}/{end?}', 'LeaveController@myleave');
Route::post('myleave/apply', 'LeaveController@applyleave');
Route::get('leavemanagement2/{Start?}/{End?}', 'LeaveController@leavemanagement2');
Route::post('leavemanagement/viewmedicalclaim', 'LeaveController@viewmedicalclaim');

Route::get('leavesummary/{Start?}/{End?}', 'LeaveController@leavesummary');
Route::get('sgsimport/{Month?}/{Year?}/{Company?}/{Department?}/{IncludeResigned?}/{IncludeInactive?}', 'LeaveController@sgsimport');
Route::get('departmentleavesummary/{Month?}/{Year?}/{Company?}/{Department?}/{IncludeResigned?}/{IncludeInactive?}', 'LeaveController@departmentleavesummary');

Route::post('leavesummary/viewdata', 'LeaveController@viewdata');
Route::post('leavesummary/viewdata2', 'LeaveController@viewdata2');
Route::post('leavesummary/viewdata3', 'LeaveController@viewdata3');
Route::get('individualleavesummary/{userid}', 'LeaveController@individualreport');

Route::post('myleave/redirect', 'LeaveController@redirect');
Route::post('myleave/cancel', 'LeaveController@cancelleave');
Route::post('myleave/delete', 'LeaveController@deleteleave');
Route::post('leavemanagement/redirect', 'LeaveController@redirect2');
Route::post('leavemanagement/submit', 'LeaveController@submit');
Route::get('onleavetoday', 'LeaveController@onleavetoday');
Route::post('leave/approve', 'LeaveController@approve');
Route::get('leavecarryforward/{Year?}', 'LeaveController@leavecarryforward');

Route::get('myclaim', 'ClaimController@myclaim');
Route::post('myclaim/new', 'ClaimController@newclaim');
Route::get('claimmanagement/{Start?}/{End?}', 'ClaimController@claimmanagement');
Route::post('claimmanagement/viewtimesheet', 'ClaimController@viewtimesheet');
Route::get('myclaim/{Id}', 'ClaimController@myclaimdetail');
Route::post('claim/newclaimitemstatus', 'ClaimController@newclaimitemstatus');
Route::post('claim/uploadreceipt', 'ClaimController@uploadreceipt');
Route::post('claim/deletereceipt', 'ClaimController@deletereceipt');
Route::post('claimsheet/updatestatus', 'ClaimController@updateclaimsheet');
// Route::get('claim/{Id}', 'ClaimController@claimdetail');
Route::get('claim/{Id}/{UserId}/{ViewAll?}/{Start?}/{End?}', 'ClaimController@claimdetail');
Route::post('myclaim/submitforapproval', 'ClaimController@submitforapproval');
Route::post('myclaim/recall', 'ClaimController@recall');
Route::post('claim/submit', 'ClaimController@submit');

Route::post('claim/approve', 'ClaimController@approve');
Route::post('claim/redirect', 'ClaimController@redirect');

Route::get('mytimesheet', 'TimesheetController@mytimesheet');
Route::get('mytimesheet2', 'TimesheetController@mytimesheet2');
Route::get('mytimesheet/{Start?}/{End?}', 'TimesheetController@mytimesheet');
Route::post('mytimesheet/new', 'TimesheetController@newtimesheet');
Route::post('timesheet/newtimesheetitemstatus', 'TimesheetController@newtimesheetitemstatus');
Route::get('timesheetmanagement/{Start?}/{End?}', 'TimesheetController@timesheetmanagement');
Route::post('timesheetmanagement/viewclaim', 'TimesheetController@viewclaim');
Route::get('mytimesheet/{Id}', 'TimesheetController@mytimesheetdetail');
Route::post('timesheet/calculateallowance', 'TimesheetController@calculateallowance');
// Route::get('timesheet/{Id}', 'TimesheetController@timesheetdetail');
Route::get('timesheet/{Id}/{ViewAll?}/{Start?}/{End?}/', 'TimesheetController@timesheetdetail');
Route::post('mytimesheet/submitforapproval', 'TimesheetController@submitforapproval');
Route::post('timesheet/submit', 'TimesheetController@submit');

Route::post('timesheet/approve', 'TimesheetController@approve');
Route::post('timesheet/redirect', 'TimesheetController@redirect');
Route::post('timesheet/updatechecked', 'TimesheetController@updatechecked');
Route::get('mialist', 'TimesheetController@MIAlist');
Route::post('mialist/exclude', 'TimesheetController@excludeMIA');
Route::post('mialist/markresign', 'TimesheetController@markResign');

Route::get('tododashboard', 'TimesheetController@tododashboard');
Route::get('cmedashboard/{user?}', 'TimesheetController@cmedashboard');
Route::get('todolist/{type?}/{start?}/{end?}/{userid?}', 'TimesheetController@todolist');
Route::post('todolist/createnew', 'TimesheetController@todolistCreate');
Route::post('todolistupdate', 'TimesheetController@todolistupdate');
Route::post('todolist/delete', 'TimesheetController@todolistDelete');
Route::post('completetodo', 'TimesheetController@completetodo');
Route::post('accepttodo', 'TimesheetController@accepttodo');
Route::get('todolistgetdetails', 'TimesheetController@todolistgetdetails');
Route::get('rejectedtask/{start?}/{end?}/{user?}', 'TimesheetController@rejectedtask');
Route::get('tasklog/{id}', 'TimesheetController@tasklog');
Route::post('rejectedtaskrevoke/{id}', 'TimesheetController@rejectedtaskrevoke');
Route::get('taskslist/{status?}/{start?}/{end?}/{userid?}', 'TimesheetController@taskslist');

Route::get('otwgetpoints/{id}', 'TimesheetController@otwgetpoints');

Route::get('project', 'ProjectController@project');
Route::get('project/projectcode', 'ProjectController@projectcode');

Route::get('engineermonitoring', 'MonitoringController@index');
Route::get('engineerlocationtracking/{Start?}/{End?}/{includeResigned?}', 'TimesheetController@engineerlocationtracking');
Route::get('sitevisitsummary', 'TimesheetController@sitevisitsummary');
Route::get('sitevisitdetail/{sitename}/{code}/{Start?}/{End?}', 'TimesheetController@sitevisitdetail');
Route::get('sitevisitsummary/{Start?}/{End?}/{Depart?}/{Client?}', 'TimesheetController@sitevisitsummary');
Route::get('incentivesummary/{Month?}/{Year?}/{includeResigned?}/{Client?}', 'TimesheetController@incentivesummary');
Route::get('driverincentivesummary/{Start?}/{End?}', 'TimesheetController@driverincentivesummary');
Route::get('sitedriverincentivesummary/{Start?}/{End?}/{Site?}', 'TimesheetController@sitedriverincentivesummary');
Route::get('useractivity/{Id}/{Start?}/{End?}', 'MonitoringController@useractivity');

Route::get('optioncontrol/{Type}/{All?}', 'OptionController@index');
Route::get('options/{Table}/{Type}', 'OptionController@getoptions');

Route::get('approvalcontrol/{Type}', 'ApprovalController@index');
Route::get('approval/project/{Type}', 'ApprovalController@missedproject');

// Route::get('holidaymanagement/{Year}', 'HolidayController@index');

Route::get('assettracking/{Type}', 'AssetController@index');
Route::get('assethistory/{Type}', 'AssetController@assethistory');
Route::post('asset/assign', 'AssetController@assign');
Route::post('asset/returned', 'AssetController@returned');
Route::post('asset/transfer', 'AssetController@transfer');
Route::post('asset/report', 'AssetController@report');
Route::post('asset/acknowledge', 'AssetController@acknowledge');
Route::post('asset/updatecarno', 'AssetController@updatecarno');
Route::post('asset/history', 'AssetController@history');
Route::post('asset/assetlist', 'AssetController@assetlist');
Route::get('roadtaxmanagement/{option}', 'AssetController@roadtaxmanagement');
Route::get('logisticsrate', 'AssetController@logisticsrate');
Route::post('createlogisticsrate', 'AssetController@createlogisticsrate');
Route::post('logisticsrate/delete/{id?}', 'AssetController@logisticsratedelete');
Route::get('roadtaxmanagement/eventlist/{vehicleid}', 'AssetController@vehicleeventlist');
Route::post('roadtaxmanagement/eventlist/delete', 'AssetController@vehicleeventdelete');

Route::get('noticemanagement', 'NoticeController@index');
Route::post('notice/uploadfile', 'NoticeController@uploadfile');
Route::post('notice/deletefile', 'NoticeController@deletefile');
Route::post('notice/notify', 'NoticeController@notify');
Route::get('notice/{Id}', 'NoticeController@viewnotice');


//malar

Route::get('export1/{userid}', 'UserController@resumeview1');
Route::get('export2/{userid}', 'UserController@resumeview2');
Route::get('export3/{userid}', 'UserController@resumeview3');

//PO management


Route::get('PO', 'POController@index');
Route::get('POs/{Ids}', 'POController@index2');
Route::get('PO/{Id}', 'POController@purchaseorderitem');
Route::get('PO2/{PO}', 'POController@purchaseorderitem2');
Route::post('PO/uploadreceipt', 'POController@uploadreceipt');
Route::post('PO/deletereceipt', 'POController@deletereceipt');
Route::get('PObyprojectcode/{ProjectCode?}', 'POController@index');
Route::get('PObyworkorderid/{ProjectCode?}/{WorkItemId?}', 'POController@index');
Route::get('PO/{ProjectCode?}/{WorkItemId?}/{ProjectId}', 'POController@index');
Route::get('PO/{ProjectCode?}/{WorkItemId?}/{ProjectId}/{Type}', 'POController@index');
Route::get('PO/{ProjectCode?}/{WorkItemId?}/{ProjectId}/{Type}/{Template}', 'POController@index');
Route::get('PObyprojectcode/{ProjectCode?}', 'POController@index');
Route::post('PO/importpo', 'POController@importpo');

Route::get('POSummary/{Start?}/{End?}', 'POController@posummary');
Route::get('POAgingSummary/{ProjectId?}', 'POController@poagingsummary');

Route::get('Invoice', 'InvoiceController@index');
Route::get('Invoice/{Id}', 'InvoiceController@invoicedetail');
Route::get('Invoice2/{Invoice}', 'InvoiceController@invoicedetail2');
Route::post('Invoice/uploadreceipt', 'InvoiceController@uploadreceipt');
Route::post('Invoice/deletereceipt', 'InvoiceController@deletereceipt');
Route::get('InvoiceSummary/{Start?}/{End?}', 'InvoiceController@invoicesummary');

//interns tracker

Route::get('resourcetracker', 'InternController@intern');
Route::get('resourcetracker/{Start?}/{End?}', 'InternController@intern');
Route::post('list', 'InternController@internlist');

//export claim and timesheet

Route::get('exportclaim/{Id}/{UserId}/{Start?}/{End?}', 'ClaimController@export');
Route::get('exporttimesheet/{Id}', 'TimesheetController@export');

//testing
Route::post('type', 'HomeController@labelview');
Route::get('exporttimesheet/{Id}/{Start?}/{End?}', 'TimesheetController@export');

//summary
Route::get('claimsummary', 'ClaimController@summary');
Route::get('claimsummary2', 'ClaimController@summary2');
Route::get('claimsummary/{Start?}/{End?}', 'ClaimController@summary');
Route::get('userclaimbreakdown/{UserId}/{Start?}/{End?}','ClaimController@userclaimbreakdown');
Route::get('projectclaimbreakdown/{ProjectId}/{Start?}/{End?}','ClaimController@projectclaimbreakdown');

Route::get('timesheetsummary', 'TimesheetController@summary');
Route::get('timesheetsummary/{Start?}/{End?}', 'TimesheetController@summary');
Route::post('timesheetsummary/viewlist', 'TimesheetController@viewlist');
Route::post('timesheetsummary/submit', 'TimesheetController@submitpending');
Route::post('timesheetsummary/submit2', 'TimesheetController@submitincomplete');

Route::get('claimtimesheetsummary', 'TimesheetController@claimtimesheetsummary');
Route::get('claimtimesheetsummary/{Start?}/{End?}', 'TimesheetController@claimtimesheetsummary');

Route::get('pendingsubmitalert', 'TimesheetController@pendingsubmitalert');
Route::get('incompletealert', 'TimesheetController@incompletealert');

Route::get('rentalnotification','AssetController@rentalNotify');


Route::get('chart', 'HomeController@chart');

Route::get('test', 'InternController@test');
Route::get('excelClaim/{Id}/{UserId}/{filename?}/{sheetname?}', 'ExcelController@ExcelClaim');
Route::get('excelTimesheet/{Id}/{Start?}/{End?}/{filename?}/{sheetname?}', 'ExcelController@ExcelTimesheet');
Route::get('export/{Id}/{UserId}', 'ExcelController@index');
Route::get('ARInvoiceExcel', 'ExcelController@ARInvoiceExcel')->name('ARInvoiceExcel.excel');
Route::POST('ARInvoiceExcel/import', 'ExcelController@ARInvoiceImport');
Route::get('test', 'WelcomeController@test');
Route::get('downloadExcel/{type}', 'ExcelController@downloadExcel');
Route::get('export', 'ExcelController@importExport');

Route::get('reportrepository/{Type}', 'ReportController@index');
Route::get('reportrepository', 'ReportController@index');


// get('/bridge', function() {
// 	$pusher = App::make('pusher');
//
//  $pusher->trigger( 'test-channel',
// 									 'test-event',
// 									 array('text' => 'Preparing the Pusher Laracon.eu workshop!'));
//
//  return view('welcome');
// });
//
// class TestEvent implements ShouldBroadcast
// {
//     public $text;
//     public function __construct($text)
//     {
//         $this->text = $text;
//     }
//     public function broadcastOn()
//     {
//         return ['test-channel'];
//     }
// }
// get('/broadcast', function() {
//     event(new TestEvent('Broadcasting in Laravel using Pusher!'));
//     return view('welcome');
// });
//
// Route::controller('notifications', 'NotificationController');
// Route::controller('activities', 'ActivityController');
// Route::controller('chat', 'ChatController');
Route::post('tracker/new', 'TrackerController@addtracker');
Route::post('tracker/rename', 'TrackerController@renametracker');
Route::post('tracker/delete', 'TrackerController@deletetracker');
Route::post('tracker/duplicate', 'TrackerController@duplicatetracker');
Route::post('tracker/createdocumenttype', 'TrackerController@createdocumenttype');
Route::post('tracker/addcolumn', 'TrackerController@addcolumn');
Route::post('tracker/updatecolumn', 'TrackerController@updatecolumn');
Route::post('tracker/viewdocument/{trackerid?}', 'TrackerController@viewdocument');
Route::post('tracker/submitdocument', 'TrackerController@submitdocument'); //original
Route::post('tracker/submitdocumentmanual', 'TrackerController@submitdocumentmanual'); //for manual
Route::get('tracker/filecategory2/{projectid}/{trackerid}', 'TrackerController@filecategory2'); //Firdaus - Copy from filecategory to do new route for getdocumentlist 20180621 Original = Route::get('filecategory/{trackerid}', 'TrackerController@filecategory');
Route::post('tracker/deleteallfiles', 'TrackerController@deleteallfiles');

Route::post('tracker/gettemplateaccess', 'TrackerController@gettemplateaccess');
Route::post('tracker/updatetemplateaccess', 'TrackerController@updatetemplateaccess');

Route::get('customizereport/{projectid}/{region?}', 'ChartController@customizereport');

Route::get('projectfolder', 'TrackerController@projectfolder'); //Firdaus - Copy from filecategory to do new route for getdocumentlist 20180621 Original = Route::get('filecategory/{trackerid}', 'TrackerController@filecategory');
Route::get('projectfolder/sitefolder/{ProjectId}', 'TrackerController@sitefolder');

Route::post('tracker/submitdocument', 'TrackerController@submitdocument');
Route::post('tracker/getdocumentlist', 'TrackerController@getdocumentlist');
Route::post('tracker/getdocumentlist1', 'TrackerController@getdocumentlist1');
Route::post('tracker/fetchimage', 'TrackerController@fetchimage');
Route::post('tracker/deletedocument', 'TrackerController@deletedocument');
Route::post('tracker/importdata', 'TrackerController@importdata');
Route::post('tracker/importhuaweipo', 'TrackerController@importhuaweipo');
Route::post('tracker/reordercolumn', 'TrackerController@reordercolumn');
Route::get('tracker/agingrules/{ProjectId?}', 'TrackerController@agingrules');
Route::get('tracker/dependencyrules/{ProjectId?}', 'TrackerController@dependencyrules');
Route::get('tracker/agingpreview/{AgingId}', 'TrackerController@agingpreview');
Route::get('tracker/targetrules/{ProjectId?}', 'TrackerController@targetrules');
Route::get('tracker/targetpreview/{TargetId}', 'TrackerController@targetpreview');
Route::post('tracker/updatepocolumn', 'TrackerController@updatepocolumn');
Route::post('tracker/assigntask', 'TrackerController@assigntask');
Route::post('tracker/viewphoto', 'TrackerController@viewphoto');

Route::get('trackerupdatetracker/{start?}/{end?}', 'TrackerController@trackerupdatetracker');

Route::get('tracker/autodate/{ProjectId?}', 'TrackerController@autodate');

Route::post('tracker/updatetracker', 'TrackerController@updatetracker');
Route::post('tracker/updatetracker2', 'TrackerController@updatetracker2');
Route::post('tracker/updatetrackerbatch', 'TrackerController@updatetrackerbatch');
Route::post('tracker/createnewsite', 'TrackerController@createnewsite');
Route::post('tracker/removeitem', 'TrackerController@removeitem');
Route::post('tracker/updatesite', 'TrackerController@updatesite');


// Route::get('projecttracker/{ProjectId?}/{TrackerId?}', 'TrackerController@trackerview');
Route::get('projecttracker/{ProjectId?}/{TrackerId?}/{Condition?}', 'TrackerController@trackerview');
Route::get('handsontable/{ProjectId?}/{TrackerId?}/{Condition?}', 'TrackerController@handsontable');

Route::get('siteissue', 'TrackerController@siteissue');
Route::get('assignments', 'TrackerController@assignments');
Route::get('assignments/{Name?}', 'TrackerController@assignments');

Route::get('allocation', 'UserController@allocation');
Route::post('allocation/namelisttype', 'UserController@viewlisttype');
Route::post('allocation/namelistposition', 'UserController@viewlistposition');
Route::get('resourcesummary', 'UserController@resourcesummary');
Route::get('logintracker/{start?}/{end?}', 'TrackerController@logintracker');
Route::post('loginlog', 'TrackerController@updatelogin');

Route::get('userability', 'UserController@userability');
Route::get('resourcecalendar/{start?}/{end?}/{role?}', 'TrackerController@resourcecalendar');
Route::get('resourcecalendar/{start?}/{end?}/{role?}/{projectid?}', 'TrackerController@viewproject');
Route::post('resourcecalendar/typelist', 'TrackerController@typelist');
Route::get('unassignedusers/{role?}', 'TrackerController@unassignedusers');
Route::get('projectrequirement/{ProjectId?}', 'TrackerController@projectrequirement');
Route::get('projectaccess', 'TrackerController@projectaccess');
Route::get('notificationmaintenance', 'NotificationController@notificationmaintenance');
Route::get('templateaccess/{projectid?}', 'TrackerController@templateaccess');
Route::get('chartmanagement', 'ChartController@index');
Route::get('chartcolumn/{chartviewid}/{projectid}', 'ChartController@chartcolumn');
Route::get('chartpreview/{chartviewid}/{projectid}', 'ChartController@chartpreview');
Route::post('chartcolumn/reordercolumn', 'ChartController@reordercolumn');

Route::get('gantt/{projectid?}/{trackerid?}/{siteid?}', 'GanttController@gantt');
Route::get('projectdashboard/{projectid}/{region?}', 'ChartController@projectdashboard');

Route::get('dashboard/{projectid?}', 'TrackerController@dashboard');
Route::get('dashboard2/{projectid?}', 'TrackerController@dashboard2');
Route::get('dashboard3/{projectid?}', 'TrackerController@dashboard3');

Route::get('PNL', 'SalesController@index');
Route::get('PNL/{projectid}', 'SalesController@pnl');
Route::get('salesorder/{projectid?}/{clientid?}/{companytype?}/{detail?}', 'SalesController@salesorder');
Route::post('salesorder/generate/{trackerid}', 'SalesController@generate');
Route::post('salesorderdetails/save', 'SalesController@save');
Route::post('salesorderdetails/save2', 'SalesController@save2');
Route::post('salesorderdetails/deleteoption', 'SalesController@deleteoption');
Route::post('salesorderdetails/activate', 'SalesController@activate');
Route::get('getsalesorderitem', 'SalesController@getitem');
Route::get('salesorderdetails/{sonumber}', 'SalesController@salesorderdetails');
Route::get('salesorderdetails2/{trackerid}', 'SalesController@salesorderdetails2');
Route::get('salesordertemplate/{salesorderid}/{history?}', 'SalesController@salesordertemplate');
Route::get('salesorderlog/{salesorderid}', 'SalesController@salesorderlog');
Route::get('salesorderhistory/fetchfiles/{salesorderid}/{trackerid}', 'SalesController@fetchfiles');
Route::post('salesorderhistory/uploadacceptance', 'SalesController@uploadacceptance');
Route::post('salesorderhistory/generateinvoice', 'SalesController@generateinvoice');
Route::get('deliveryorderhistory/{trackerid}', 'SalesController@deliveryorderhistory');
Route::get('salesorderhistory/{trackerid}', 'SalesController@salesorderhistory');
Route::post('salesorderhistory/deleteso', 'SalesController@deletesalesorder');
Route::post('salesorderterminate/{salesorderid}', 'DeliveryController@salesorderterminate');
Route::get('invoicetemplate/{sonumber}', 'SalesController@invoicetemplate');
Route::get('invoicetemplate2/{soid}', 'SalesController@invoicetemplate2');
Route::get('combineinvoicetemplate/{id}', 'SalesController@combineinvoicetemplate');
Route::get('invoicepdf/{id}', 'SalesController@invoicepdf');
Route::get('autopdf', 'SalesController@autopdf');
Route::get('invoicepdf2/{sonumber}', 'SalesController@invoicepdf2');
Route::get('invoicelist/{start?}/{end?}', 'SalesController@invoicelist');
Route::post('invoicelist/getInvoiceNumber/{id}', 'SalesController@InvoiceNumber');
Route::post('invoicelist/updateInvoiceNumber', 'SalesController@updateInvoiceNumber');
Route::post('invoicelist/batchprint', 'SalesController@batchprint');
Route::post('invoicelist/combineinvoice', 'SalesController@combineinvoice');
Route::post('invoicelist/updateTempDONumber', 'SalesController@updateTempDONumber');
Route::get('pendinginvoice/{client?}/{type?}', 'SalesController@pendinginvoice');
Route::post('salesorder/generateinvoice', 'SalesController@generateinvoice');
Route::get('salessummary', 'SalesController@salessummary');
Route::get('salessummarydetails/{companyname}/{range}/{jdni?}', 'SalesController@salessummarydetails');
Route::post('salesorder/manualRecur/{id}', 'SalesController@manualRecurSalesOrder');
Route::get('jdnisummary', 'SalesController@jdnisummary');
Route::get('pr', 'POController@pr');
// Route::get('esar', 'POController@esar');
Route::get('prpdf', 'POController@prpdf');
Route::get('esar/{ids}', 'POController@esar');
Route::post('esarpdf', 'POController@esarpdf');

//Credit Note
Route::get('creditnotedetail/{id?}','SalesController@creditnotedetail');
Route::get('creditnotetemplate/{id?}','SalesController@creditnotetemplate');
Route::get('cnpdf', 'SalesController@cnpdf');
Route::get('creditnotelist/{start?}/{end?}','SalesController@creditnotelist');
Route::post('deletecreditnoteitem/{id?}','SalesController@deletecreditnoteitem');
Route::post('savecreditnote','SalesController@savecreditnote');
// Route::get('pac', 'POController@pac');
// Route::get('pacpdf', 'POController@pacpdf');
// Route::get('esar1', 'POController@esar1');
// Route::get('esar1pdf', 'POController@esar1pdf');

Route::get('pac/{ids}', 'POController@pac');
Route::post('pacpdf', 'POController@pacpdf');
Route::get('esar1/{ids}', 'POController@esar1');
Route::post('esar1pdf', 'POController@esar1pdf');

Route::get('birthdayalert', 'HomeController@birthdayalert');
Route::get('licensechecklist', 'UserController@licensechecklist');
Route::get('licensepdf', 'UserController@licensepdf');
Route::post('user/deleteresume', 'UserController@deleteresume');

Route::get('phonebilltracker/{type}/{start?}/{end?}', 'AssetController@phonebilltracker');
Route::get('phonebillsummary', 'AssetController@phonebillsummary');
Route::get('phonebillsummary/{year}', 'AssetController@phonebillsummary');
Route::get('phonebillsummary/{year}/{operator}', 'AssetController@phonebillsummary');
Route::get('phonebillsummary/{year}/{operator}/{number}', 'AssetController@phonebillsummary');
Route::get('insurancetracker/{type}', 'AssetController@insurancetracker');
Route::post('phonebill/new', 'AssetController@newphonebill');

Route::get('vehicleexpensessummary', 'AssetController@vehicleexpensessummary');
Route::get('vehicleexpensessummary/{year}', 'AssetController@vehicleexpensessummary');
Route::get('vehicleexpensessummary/{year}/{carnumber}', 'AssetController@vehicleexpensessummary');

Route::get('shellcardtracker', 'AssetController@shellcardtracker');
Route::get('shelldeductions/{Id}', 'AssetController@shelldeduction');
Route::post('deductionapproval', 'AssetController@deductionapproval');

Route::post('summon/new', 'AssetController@newsummon');

Route::get('summontracker', 'AssetController@summontracker');
Route::get('summondeductions/{Id}', 'AssetController@summondeduction');
Route::get('accidentdeduction/{Id}', 'AssetController@accidentdeduction');

Route::get('touchngo', 'AssetController@touchngo');
Route::get('touchngodeduction/{Id}', 'AssetController@touchngodeduction');

Route::get('licensetracker/{type}', 'AssetController@licensetracker');
Route::post('shellcard/new', 'AssetController@newshellbill');

Route::get('utilitytracker/{type}', 'AssetController@utilitybill');
Route::get('utilitysummary', 'AssetController@utilitysummary');
Route::get('utilitysummary/{year}/{type}/{branch}', 'AssetController@utilitysummary');

Route::get('creditcardtracker/{owner}', 'AssetController@creditcardtracker');
Route::get('myadvancerequest', 'UserController@myadvancerequest');

Route::post('myadvance/apply', 'UserController@applyadvance');
Route::get('advancemanagement/{start?}/{end?}', 'UserController@advancemanagement');
Route::get('advances/{advanceid}', 'UserController@advancedetail');
Route::post('advance/approve', 'UserController@approveadvance');
Route::post('advance/reject', 'UserController@rejectadvance');
Route::post('advance/redirect', 'UserController@redirectadvance');
Route::post('advance/submit', 'UserController@submitadvance');

Route::get('cutoffmanagement', 'ClaimController@cutoffmanagement');

Route::get('tracker/search', 'TrackerController@search');

Route::get('leaveentitlement', 'LeaveController@entitlement');
Route::get('timeoffreport/{Start?}/{End?}', 'LeaveController@timeoffreport');

Route::get('schedule', 'UserController@schedule');
Route::get('schedulereminder', 'UserController@schedulelist');
Route::get('myschedulereminder', 'UserController@myschedulelist');

Route::get('staffloan-old', 'UserController@staffloanold');
Route::get('staffloan-old/{Id}', 'UserController@loanrecord');
Route::get('staffloan2/{end?}', 'UserController@staffloan2');

Route::get('staffloan/{end?}', 'UserController@staffloan');

Route::get('presaving/{year?}', 'UserController@presaving');
Route::get('presavingrecord/{Id}/{year}', 'UserController@presavingrecord');

Route::get('staffexpenses/{start?}/{end?}', 'UserController@staffexpenses');
Route::get('staffexpensesrecord/{Id}/{start?}/{end?}', 'UserController@staffexpensesrecord');

Route::get('staffdeductions/{start?}/{end?}', 'UserController@staffdeductions');
Route::get('staffdeductionslist/{type}/{start?}/{end?}', 'UserController@staffdeductionslist');
Route::get('staffdeductionsrecord/{Id}/{start?}/{end?}', 'UserController@staffdeductionsrecord');

Route::post('PO/updateaccept', 'POController@updateaccept');
Route::post('userdetailpdf/{Id}', 'UserController@userdetailpdf');
Route::get('ITservice/{domain?}', 'AssetController@ITservice');
Route::get('servicecontact', 'AssetController@servicecontact');
Route::get('printer/{month?}', 'AssetController@printer');
Route::get('printerdetails/{Id}', 'AssetController@printerdetails');
Route::get('agreement/{type}', 'AssetController@agreement');
Route::get('property/{type}', 'AssetController@property');
Route::get('filingsystem/{type}', 'AssetController@filingsystem');

//Demo
Route::get('property2/{type}', 'AssetController@property2');
Route::get('agreement2/{type}', 'AssetController@agreement2');
Route::get('billing/{id}/{type?}', 'AssetController@billing');
Route::get('maintenance/{id}', 'AssetController@maintenance');
Route::get('insurance/{id}', 'AssetController@insurance');
Route::post('maintenance/upload', 'AssetController@maintenanceupload');
Route::post('maintenance/create', 'AssetController@maintenancecreate');
Route::post('insurance/upload', 'AssetController@insuranceupload');
Route::post('insurance/create', 'AssetController@insurancecreate');
Route::post('billing/upload', 'AssetController@billingupload');
Route::post('billing/create', 'AssetController@billingcreate');
Route::post('asset/deletefile', 'AssetController@deletefile');

// Route::get('deliverytracking/{type?}', 'DeliveryTracking@deliverytracking');
// Route::get('deliverytrackingdetails', 'DeliveryTracking@deliverytrackingdetails');

Route::get('radiusmanagement', 'TimesheetController@radiusmanagement');
Route::get('deliverylocation', 'TimesheetController@deliverylocation');
Route::get('touchngoforvehicle/{vehicleno}', 'AssetController@touchngoforvehicle');
Route::post('creditcard/{owner}/newentry', 'AssetController@creditcardnewentry');
Route::post('creditcard/{owner}/updateentry', 'AssetController@creditcardupdateentry');
Route::post('phonebills/{type}/newentry', 'AssetController@phonebillsnewentry');
Route::post('phonebills/{type}/updateentry', 'AssetController@phonebillsupdateentry');
Route::get('creditcardsummary/{year?}/{owner?}/{type?}', 'AssetController@creditcardsummary');

Route::get('scopeofwork', 'ScopeofWorkController@index');

Route::get('myloan', 'UserController@myloan');
Route::post('myloan/apply', 'UserController@applyloan');
Route::post('myloan/cancel', 'UserController@cancelloan');

Route::get('staffloanmanagement/{start?}/{end?}', 'UserController@staffloanmanagement');
Route::get('staffloans/{advanceid}', 'UserController@staffloandetail');
Route::get('myloan/{advanceid}', 'UserController@myloandetail');
Route::post('staffloan/approve', 'UserController@approvestaffloan');
Route::post('staffloan/updateBankIn', 'UserController@updateBankIn');

Route::post('staffloan/reject', 'UserController@rejectstaffloan');
Route::post('staffloan/redirect', 'UserController@redirectstaffloan');
Route::post('staffloan/submit', 'UserController@submitstaffloan');

Route::post('staffloan/uploadreceipt', 'UserController@staffloanuploadreceipt');
Route::post('staffloan/deletereceipt', 'UserController@staffloandeletereceipt');
Route::get('exportstaffloan/{Id}', 'UserController@exportstaffloandetail');


//holiday scheme
Route::post('holidaymanagement/territory/{id}/{year}/duplicate', 'HolidayController@duplicate');
Route::post('holidaymanagement/territory/{id}/{year}/duplicatenextyear', 'HolidayController@duplicatenextyear');
Route::get('holidaymanagement/territories', 'HolidayController@territories');
Route::get('holidaymanagement/territory/{id}/{year}', 'HolidayController@territorydays');
Route::get('holidaymanagement/{year}', 'HolidayController@index');

Route::get('medicalclaimsummary/{Start?}/{End?}', 'ClaimController@medicalclaimsummary');
Route::get('getmedicalclaim/{id}', 'ClaimController@getmedicalclaim');
Route::post('createmedicalclaim', 'ClaimController@createmedicalclaim');
Route::post('importleavecarryforward','LeaveController@importleavecarryforward');

Route::get('myrequest', 'UserController@myrequest');
Route::post('request/approve', 'UserController@approverequest');
Route::post('requestmanagement/redirect', 'UserController@redirectrequest');
Route::post('myrequest/cancel', 'UserController@cancelrequest');
Route::post('applyrequest', 'UserController@applyrequest');
Route::get('requestmanagement', 'UserController@requestmanagement');
// Route::get('myrequest/export/{RequestId}', 'UserController@exportrequest');

Route::post('payslipmanagement/sendpasswordtoselected', 'PayrollController@sendPasswordToSelectedUsers');
Route::post('payslipmanagement/sendpasswordtoall', 'PayrollController@sendPasswordToActiveUsers');
Route::post('payslipmanagement/authorize', 'PayrollController@authorizePayslipManagement');
Route::post('payslipmanagement/changeauthorizationpassword', 'PayrollController@changeAuthorizationPassword');
Route::post('payslipmanagement/changepassword', 'PayrollController@changepassword');
Route::post('payslipmanagement/generatepassword', 'PayrollController@generatepassword');
Route::post('payslipmanagement/uploadpayslip', 'PayrollController@uploadpayslip');
Route::post('payslipmanagement/removepayslip', 'PayrollController@removepayslip');
Route::get('payslipmanagement/viewpayslip', 'PayrollController@viewpayslip');
Route::get('payslipmanagement/{includeResigned?}/{includeInactive?}', 'PayrollController@payslipmanagement');
Route::get('mypayslip', 'UserController@mypayslip');
Route::post('mypayslip/downloadpayslip', 'UserController@downloadpayslip');

Route::get('otmanagementhr/{Start?}/{End?}/{includeResigned?}', 'TimesheetController@otmanagementhr');
Route::get('otmanagementhod/{Start?}/{End?}/{includeResigned?}', 'TimesheetController@otmanagementhod');

//Delivery Management
Route::get('mydeliveryrequest/{trackerid?}/{terminate?}','DeliveryController@mydeliveryrequest');
Route::post('mydeliveryrequest/apply','DeliveryController@applydelivery');
Route::post('mydeliveryrequest/cancel', 'DeliveryController@canceldelivery');
Route::post('mydeliveryrequest/recall', 'DeliveryController@recalldelivery');
Route::post('mydeliveryrequest/delete', 'DeliveryController@deletedelivery');
Route::post('mydeliveryrequest/dummyDO', 'DeliveryController@createDummyDO');
Route::get('deliveryapproval/{start?}/{end?}','DeliveryController@deliveryapproval');
Route::post('deliveryapproval/approvecancel','DeliveryController@approvecancel');
Route::post('deliveryapproval/delete','DeliveryController@deletedelivery');
Route::post("changeDeliveryStatus","DeliveryController@changeDeliveryStatus");
Route::get("deliverydetails/{id}","DeliveryController@deliveryDetails");
Route::get("deliverydetails2/{id}","DeliveryController@deliveryDetails2");
Route::post("updateadddesc","DeliveryController@updateadddesc");
Route::get('sitedeliverysummary/{Start?}/{End?}','DeliveryController@sitedeliverysummary');
Route::get('sitedeliverydetails/{sitename}/{Start?}/{End?}', 'DeliveryController@sitedeliverydetails');
Route::get('deliverytracking/{do_no?}','DeliveryController@deliverytracking');
Route::get("deliverytrackingdetails/{formid}","DeliveryController@deliverytrackingdetails");
Route::get("trackingphoto/{statusid}","DeliveryController@trackingphoto");
Route::get('warehousechecklist/fetchItemList/{id}', 'DeliveryController@fetchItem');
Route::get('warehousechecklist/{start?}/{end?}','DeliveryController@warehousechecklist');
Route::get('approvestock/{itemid}','DeliveryController@approvestock');
Route::get('stockin/{id}/{formId}','DeliveryController@warehousestockin');
Route::get('requestorkpi','DeliveryController@requestorkpi');
Route::get('requestorkpi/{start?}/{end?}/{id?}','DeliveryController@requestorkpi');
Route::get('driverkpi', 'DeliveryController@driverkpi');
Route::get('driverkpi/{date}', 'DeliveryController@driverkpi');
Route::get('driverkpi/{date}/{requestor}', 'DeliveryController@driverkpi');
Route::get('warehousekpi','DeliveryController@warehousekpi');
Route::get('deliveryorder/{id}','DeliveryController@deliveryorder');
Route::get('deliveryorderpdf/{id}','DeliveryController@deliveryorderpdf');
Route::get('materiallist/{id}','DeliveryController@materialList');
Route::get('fetchItemList/{id}', 'DeliveryController@fetchItemList');
Route::get('editItemList/{id}', 'DeliveryController@editItemList');
Route::get('updateItemList/{id}', 'DeliveryController@editItemList');
Route::post('mydeliveryrequest/resubmit','DeliveryController@resubmit');
Route::get('returnnote','DeliveryController@returnnote');
Route::get('deliveryrequest/getsite','DeliveryController@getSite');
Route::get('deliveryrequest/getsite2','DeliveryController@getSite2');
Route::post('mydeliveryrequest/upload/','DeliveryController@upload');
Route::post('mydeliveryrequest/removeupload','DeliveryController@removeupload');
Route::post('deleteDeliveryImage','DeliveryController@deleteDeliveryImage');
Route::post('editItemList/', 'DeliveryController@editItemList');
Route::get('returnnote/{id}','DeliveryController@returnnote');
Route::get('deliveryitemdetails','DeliveryController@getDeliveryItem');
Route::post('updateDeliveryItem','DeliveryController@updateDeliveryItem');
Route::post('uploadDeliveryImage','DeliveryController@uploadDeliveryImage');
Route::get('getDeliveryDetails','DeliveryController@getDeliveryDetails');
Route::get('warehousedetails/{id}','DeliveryController@warehousedetails');
Route::post('warehouseaccept','DeliveryController@warehouseaccept');
Route::get('deliverydashboard','DeliveryController@DeliveryDashboard');
Route::post('savePendingDelivery','DeliveryController@savePendingDelivery');
Route::post('finalapprove/{id}','DeliveryController@finalapprove');
Route::get('lorrystatus','DeliveryController@lorrystatus');
Route::post('deliverymangement/saveCondition','DeliveryController@saveCondition');
Route::post('deliverymanagement/insertNote','DeliveryController@insertNote');

Route::post('savePickUp','DeliveryController@savePickUp');
Route::post('deliverymanagement/saveTime','DeliveryController@saveTime');
Route::get('deliverymanagement/timeLog','DeliveryController@timeLog');

Route::get('mydeliveryrequest/MR','DeliveryController@MR');

Route::get('getMR','DeliveryController@MR');
Route::get('MPSBItem','DeliveryController@MPSBItem');
Route::post('updateViewLog','DeliveryController@updateViewLog');
Route::get('ViewLog','DeliveryController@ViewLog');

//Inventory Management
Route::get('inventorymanagement','InventoryController@inventorymanagement');
Route::get('inventorymanagement/inventorydetails/{id}','InventoryController@vendor');
Route::post('importinventory','InventoryController@importinventory');

// Asset Inventory
Route::get('asset/inventory/{type?}','InventoryController@assetinventory');
Route::get('asset/inventorydetails/{Id}','InventoryController@assetinventorydetails');
Route::get('assetdetails/{Id}','InventoryController@assetdetails');
Route::post('assetupdate','InventoryController@assetsupdate');

// GENSET Inventory
Route::get('genset/inventory/{branch?}','InventoryController@gensetinventory');
Route::get('lowtresholdlist','InventoryController@lowtresholdlist');
Route::get('lowtresholdlist/getpricehistory/{id}','InventoryController@getpricehistory');
Route::post('inventory/updatestock','InventoryController@gensetupdate');
Route::post('gensetinventorycreate','InventoryController@gensetinventorycreate');
Route::post('gensetinventory/edit','InventoryController@gensetinventoryedit');
Route::post('toolsupdate','InventoryController@assetupdate');
Route::post('inventory/delete','InventoryController@gensetdelete');
Route::get('genset/inventorydetails/{Id?}','InventoryController@gensetinventorydetails');
Route::get('genset/inventoryhistory/{branch}/{Id}','InventoryController@gensetinventoryhistory');
Route::get('branchtransfergetquantity/{type?}','InventoryController@branchtransfergetquantity');
Route::get('inventorypricehistory/{id?}','InventoryController@inventorypricehistory');

Route::get('filerenderer2/{projectid}/{trackerid}/{option}', 'TrackerController@filerenderer2'); //Firdaus - New Route for renderer 2 (For Document List)
Route::post('filerenderer2downloadall', 'TrackerController@downloadall');
Route::get('filerenderer2delete/{Id}', 'TrackerController@filerenderer2delete');
Route::get('filecategory/{trackerid}', 'TrackerController@filecategory'); //Firdaus - Capture this route for getdocumentlist

Route::get('opendocument/{ProjectId}/{Id}/{Column}', 'TrackerController@opendocument'); //Firdaus - Capture this route for getdocumentlist

Route::get('invoicelisting/{Year?}/{ProjectId?}', 'TrackerController@invoicelisting');
Route::post('invoicelisting/newrecord', 'TrackerController@createnewrecord');
Route::post('invoicelisting/updateinvoicelisting', 'TrackerController@updateinvoicelisting');
Route::post('invoicelisting/removerecord', 'TrackerController@removerecord');
Route::post('tracker/searchrecord', 'TrackerController@searchrecord');
Route::post('invoicelisting/createmultiplerecord', 'TrackerController@createmultiplerecord');
Route::post('invoicelisting/generateinvoice', 'TrackerController@generateinvoice');

Route::get('transportcharges/{Start?}/{End?}', 'TrackerController@transportcharges');
Route::post('transportcharges/updateincentive/{id}', 'TrackerController@updateincentive');
Route::get('transportchargesdetails/{roadtaxid}/{date}', 'TrackerController@transportchargesdetails');
Route::get('sitetransportcharges/{Start?}/{End?}/{Site?}', 'TrackerController@sitetransportcharges');
Route::get('costing/{ProjectId?}', 'TrackerController@costing');
Route::get('logisticschargesincentive/{Start?}/{End?}/{ProjectType?}/{Company?}', 'TrackerController@logisticschargesincentive');

Route::post('costing/newcostingrecord', 'TrackerController@createnewcostingrecord');
Route::post('costing/updatecosting', 'TrackerController@updatecosting');
Route::post('costing/removecostingrecord', 'TrackerController@removecostingrecord');
/*Material Request*/
Route::get('material/MR','MaterialController@materialRequest');
Route::get('material/MR/{projectid?}/{trackerid?}','MaterialController@materialRequest2');
Route::get('material/getSite','MaterialController@getSite');
Route::post('material/newRequest','MaterialController@newMaterialRequest');
Route::get('material/getMaterial','MaterialController@getMaterial');
Route::get('material/materialApproval','MaterialController@materialApproval');
Route::get('material/materialDetails/{id}','MaterialController@materialDetails');
Route::post('material/updateStatus','MaterialController@updateStatus');
Route::post('material/recall','MaterialController@recall');
Route::get('material/getItemDetail','MaterialController@getItemDetail');
Route::post('material/saveDetails','MaterialController@saveDetails');
Route::post('material/resubmit','MaterialController@resubmit');
Route::get('material/print/{id}', 'MaterialController@materialPrint');
Route::get('material/history/{id}','MaterialController@MrHistory');

Route::get('ewallet/{start?}/{end?}/{includeResigned?}', 'UserController@ewallet');
Route::get('ewalletfinanceupdate/{start?}/{end?}/{company?}', 'UserController@ewalletfinanceupdate');
Route::get('ewalletrecord/{UserId}/{start?}/{end?}', 'UserController@ewalletrecord');
Route::get('ewalletdetails/{type}/{start}/{end}/{userid?}', 'UserController@ewalletdetails');
Route::get('ewalletsummarybreakdown/{start?}/{end?}/{type?}', 'UserController@ewalletsummarybreakdown');

Route::get('fionrecord/{start?}/{end?}', 'UserController@fionrecord');
Route::post('fionrecord/uploadfile', 'UserController@fionupload');
Route::post('fionrecord/deletefile', 'UserController@fionremoveupload');

Route::get('siteewalletrecord/{TrackerId?}', 'UserController@siteewalletrecord');
Route::post('ewalletrecord/uploadfile', 'UserController@upload');
Route::post('ewalletrecord/deletefile', 'UserController@removeupload');
Route::post('ewalletrecord/verify','UserController@verify');
Route::post('ewalletrecord/verifytick','UserController@verifytick');
Route::get('ewalletsummary/{start?}/{end?}/{projectid?}/{trackerid?}','UserController@ewalletsummary');
/*Material PO */
Route::post('material/generatePO','MaterialController@generatePO');
Route::get('material/PO/{mid?}/{start?}/{end?}','MaterialController@materialPO');
Route::get('material/printpo/{id}','MaterialController@materialPOPrint');
Route::get('material/print/{id}','MaterialController@materialPrint');
Route::get('material/PODetails/{id}','MaterialController@materialPODetails');
Route::post('material/savePaymentTerms','MaterialController@savePaymentTerms');
Route::post('material/saveExtra','MaterialController@saveExtra');
Route::get('material/POConfirmation/{mid}','MaterialController@PoConfirmation');
Route::post('material/confirmPO','MaterialController@confirmPO');
Route::get('material/getItemBasedOnType','MaterialController@getItemBasedOnType');
Route::post('material/uploadQuotation','MaterialController@uploadQuotation');
Route::get('material/getFile','MaterialController@getFile');
Route::post('material/quotationApproval','MaterialController@quotationApproval');
Route::get('material/getProjectCode','MaterialController@getProjectCode');
Route::get('material/checkQuotationExceed','MaterialController@checkQuotationExceed');
Route::post('material/savePoItem','MaterialController@savePoItem');
Route::post('material/cancel','MaterialController@cancel');
Route::get('material/getAllPO','MaterialController@getAllPO');
Route::get('material/PoItem','MaterialController@PoItem');
Route::get('material/previewPo','MaterialController@previewPo');
Route::post('material/removePoItem','MaterialController@removePoItem');
Route::post('material/saveMR','MaterialController@saveMR');
Route::get('material/saveMr/{id}','MaterialController@saveMrPage');
Route::get('material/getSaveMrItem','MaterialController@getSaveMrItem');
Route::get('material/getMr','MaterialController@getMr');
Route::get('material/getImportMRItem','MaterialController@getImportMRItem');
Route::post('material/removeFile','MaterialController@removeFile');
Route::get('material/getCancelledPo','MaterialController@getCancelledPo');
Route::post('material/savePoNo','MaterialController@savePoNo');
Route::get('material/filterClient','MaterialController@filterClient');
// GENSET
Route::get('genset/dashboard', 'GensetController@index');
Route::get('gensetsummarydashboard/{year?}/{cat?}/{clientId?}/{scope?}/{region?}','GensetController@gensetsummarydashboard');
// Human Resources Dashboard
Route::get('humanresource/dashboard/{start?}/{end?}', 'HumanResourceController@index');
Route::get('onleavetodaydashboard/{start?}/{end?}', 'HumanResourceController@onleavetodaydashboard');
Route::get('onleavetoday/{start?}/{end?}/{k?}', 'HumanResourceController@leavetoday');
Route::get('staffconfirmed', 'HumanResourceController@staffconfirmed');
Route::get('newstaffjoin', 'HumanResourceController@newstaff');
Route::get('staffresigned', 'HumanResourceController@resignedstaff');
Route::get('staffloanpending', 'HumanResourceController@staffloanpending');
Route::get('approvedloan', 'HumanResourceController@approvedloan');
Route::get('totalstaffloan', 'HumanResourceController@index');
Route::get('totalrepayment', 'HumanResourceController@index');
// Staff Dashboard
Route::get('staff/dashboard/{start?}/{end?}/{user?}', 'UserController@staffdashboard');
Route::get('staffleave/{start?}/{end?}/{user?}', 'LeaveController@staffleave');
Route::get('staffnotimein/{Start?}/{End?}/{user?}/{includeResigned?}', 'TimesheetController@staffnotimein');
Route::get('staffdeductionsdashboard/{start?}/{end?}/{user?}', 'UserController@staffdeductionsdashboard');
Route::get('staffdeductionsdetail/{start?}/{end?}/{user?}/{type?}', 'UserController@staffdeductionsdetail');
Route::get('kpiresult/{start?}/{end?}/{user?}/{year?}', 'UserController@kpiresult');
Route::get('cmeresult/{start?}/{end?}/{user?}/{year?}', 'UserController@cmeresult');


Route::get('technicianbag/{id?}','GensetController@technicianbag');
Route::get('technicianbag/details/{id}','GensetController@technicianbagdetails');
Route::post('importgensetinventory','GensetController@importgensetinventory');
Route::get('requisitionmanagement/{start?}/{end?}','GensetController@requisitionmanagement');
Route::get('deleterequisition/{id}','GensetController@deleterequisition');
Route::get('requisitionmanagementdetails/{id}','GensetController@requisitionmanagementdetails');
Route::post('requisition/approve/{id}','GensetController@approveRequisition');
Route::get('requisitionform/{technician?}/{branch?}','GensetController@requisitionform');
Route::post('/requisition/set_requisition_data','GensetController@fetchItemList');
Route::post('/requisitionform/getitem','GensetController@getitemlist');
Route::post('/requisitionform/confirmStockOut','GensetController@confirmStockOut');
Route::post('requisitionform/prepare','GensetController@prepare');
Route::get('exportGensetInventory','GensetController@exportGensetInventory');

// service ticket
Route::get('serviceticket','ServiceTicketController@serviceticket');
Route::get('serviceticket/details/{id}','ServiceTicketController@serviceticketdetails');
Route::get('servicemanagement/{start?}/{end?}/{status?}/{type?}/{asset?}','ServiceTicketController@servicemanagement');
Route::get('servicegetsite/{genset}','ServiceTicketController@SVTGetSite');
Route::get('servicegetpic/{service}','ServiceTicketController@SVTGetPic');
Route::post('servicemanagement/create','ServiceTicketController@create');
Route::post('servicemanagement/update','ServiceTicketController@update');
Route::post('servicemanagement/delete','ServiceTicketController@delete');
Route::get('getTicketDetails/{id}','ServiceTicketController@getDetails');
Route::get('storekeeper/{start?}/{end?}','ServiceTicketController@storekeeper');
Route::get('storeGetItemList/{id}','ServiceTicketController@storeGetItem');
Route::get('ticketassettype/{type}','ServiceTicketController@ticketassettype');
Route::get('ticketflow','ServiceTicketController@ticketflow');
Route::post('createticketflow','ServiceTicketController@createticketflow');
Route::get('getflowdetails/{id}','ServiceTicketController@getflowdetails');
Route::get('deleteticketflow/{id}','ServiceTicketController@deleteticketflow');
Route::post('updateticketflow','ServiceTicketController@updateticketflow');
Route::post('deleteticket/{id}','ServiceTicketController@deleteticket');

Route::get('svtreport/{start?}/{end?}','ServiceTicketController@svtreport');
Route::get('replacementhistory/{start?}/{end?}/{priceId?}','ServiceTicketController@replacementhistory');

Route::get('trackersummary/{projectid?}', 'TrackerController@trackersummary');
Route::get('cashbook/{start?}/{end?}/{company?}', 'TrackerController@cashbook');
Route::get('apinvoice/{start?}/{end?}/{company?}', 'TrackerController@apinvoice');
Route::get('apcreditnote/{start?}/{end?}/{company?}', 'TrackerController@apcreditnote');
Route::get('arinvoice/{start?}/{end?}/{company?}', 'TrackerController@arinvoice');
Route::get('apfinanceupdate/{start?}/{end?}/{company?}', 'TrackerController@apfinanceupdate');
Route::get('tracker/mandayDetails/{id}','TrackerController@mandayDetails');
Route::post('material/saveVendor','MaterialController@saveVendor');

Route::get('tracker/piechart','TrackerController@pieChart');
Route::post('tracker/updatefibre', 'TrackerController@updatefibre');
Route::post('tracker/diary', 'TrackerController@diary');

Route::get('svt/dashboard/{start?}/{end?}/{status?}','ServiceTicketController@dashboard');
