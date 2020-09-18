<?php

include( "plugin/php/DataTables.php" );

// Alias Editor classes so they are easy to use
use
  DataTables\Editor,
  DataTables\Editor\Field,
  DataTables\Editor\Format,
  DataTables\Editor\Mjoin,
  DataTables\Editor\Upload,
  DataTables\Editor\Validate;


// DataTables PHP library

  // Build our Editor instance and process the data coming from _POST
  $editor=Editor::inst( $db, 'noticeboards' )
  	->fields(
      Field::inst( 'noticeboards.Id' ),
      Field::inst( 'noticeboards.UserId' ),
      Field::inst( 'users.Name' ),
  		Field::inst( 'noticeboards.Title' ),
      Field::inst( 'noticeboards.Content' ),
      Field::inst( 'noticeboards.Start_Date' ),
      Field::inst( 'noticeboards.End_Date' ),
      Field::inst( 'noticeboards.Created_At' ),
      Field::inst( 'noticeboards.Email_Notification' ),
      Field::inst( 'f.FileId' ),
      Field::inst( 'f.Attachment' ),
      Field::inst( 'f.FileName' ))
      ->on('postCreate', function ($editor, $id, $values, $row) use ( $db ) {

          $playerids = array();

          $users = $db->raw()
            ->exec('SELECT Id, Player_Id from users WHERE Active = 1')
            ->fetchAll();

          $insertQuery = "INSERT INTO notificationstatus (userid,type,seen,TargetId) VALUES ";
          foreach($users as $user) {
              array_push($playerids, $user['Player_Id']);
              $insertQuery = $insertQuery . "(" . $user['Id'] . ", 'New Notice', 0, " . $id . "),";
          }

          $insertQuery = rtrim($insertQuery, ',') . ';';

          $db->raw()->exec($insertQuery);

          // $db->insert('notificationstatus', array(
          //     'userid'      => $userid,
          //     'type' => 'New Notice',
          //     'seen' => 0,
          //     'TargetId' => $id
          // ));
          //
          sendNotification($playerids);

      })
      ->on('postRemove', function ($editor, $id, $values) use ( $db ) {
          $db->raw()
            ->bind(':Id', $id)
            ->exec("DELETE from notificationstatus WHERE Type = 'New Notice' AND TargetId = :Id");
      })
      ->leftJoin('users', 'users.Id', '=', 'noticeboards.UserId')
      ->leftJoin('(SELECT TargetId, GROUP_CONCAT( Id SEPARATOR "|") as FileId,GROUP_CONCAT( Web_Path SEPARATOR "|") as Attachment,GROUP_CONCAT( File_Name SEPARATOR "|") as FileName FROM files WHERE Type="Notice" GROUP BY TargetId) as f','f.TargetId','=','noticeboards.Id')
      ->process( $_POST )
      ->json();


      function sendNotification($playerids){


        $content = array(
            "en" => 'New Notice'
        );

        $fields = array(
            'app_id' => "b22a7a60-2cfa-4641-a309-4720c564fddf",
            // 'included_segments' => array("All"),
            'include_player_ids' => $playerids,
            'data' => array("foo" => "bar"),
            'contents' => $content,
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
