<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Session;
use App\Http\Controllers\CommonController;

class ActivityController extends Controller
{
    var $pusher;
    var $user;

    public function __construct()
    {
        $this->pusher = App::make('pusher');
        $this->middleware('auth');
    }

    /**
     * Serve the example activities view
     */
    public function getIndex()
    {
      $user = (new CommonController)->get_current_user();

        // TODO: provide some useful text
        $activity = [
            'text' => $user->Name . ' has visited the page',
            'username' => $user->Name,
            'avatar' => $user->Web_Path,
            'id' => $user->UserId
        ];

        // TODO: trigger event
        $this->pusher->trigger('activities', 'user-visit', $activity);

        return view('activities');
    }

    /**
       * A new status update has been posted
       * @param Request $request
       */
      public function postStatusUpdate(Request $request)
      {
        $user = (new CommonController)->get_current_user();
          $activity = [
              'text' => e($request->input('status_text')),
              'username' => $user->Name,
              'avatar' => $user->Web_Path,
              'id' => $user->UserId
          ];
          $this->pusher->trigger('activities', 'new-status-update', $activity);
      }
      /**
       * Like an activity
       * @param $id The ID of the activity that has been liked
       */
      public function postLike($id)
      {
        $user = (new CommonController)->get_current_user();
          $activity = [
              'text' => $user->Name . ' liked a status update',
              'username' => $user->Name,
              'avatar' => $user->Web_Path,
              'id' => $user->UserId
          ];
          $this->pusher->trigger('activities', 'status-update-liked', $activity);
      }
}
