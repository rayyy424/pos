<?php

include( "plugin/php/DataTables.php" );

// Alias Editor classes so they are easy to use
use
	plugin\php\Database\Driver\Mysql\Query,
  DataTables\Editor,
  DataTables\Editor\Field,
  DataTables\Editor\Format,
  DataTables\Editor\Mjoin,
  DataTables\Editor\Upload,
	DataTables\Database,
  DataTables\Editor\Validate;

  // if (!isset( $_POST['columns'])) {
  //     $_POST['columns']="`Id`,`ProjectId`,`Site_Name`,`Work_Order_ID`,`Region`,`Work_Scope`,`WO_Received`";
  // }
	//
  // if (!isset( $_POST['projectid'])) {
  //     $_POST['projectid']=17;
  // }





		if (( isset( $_POST['action'] )) && ( $_POST['action'] == 'edit'))
		{

			$keys=array();

			foreach ($_POST['data'] as $key => $value) {

				foreach ($value as $key2 => $value2) {
					# code...

					$details="";

					$set= array($key2 => $value2);
					$where= array('Id' => $key);

					$sql="update tracker set `".$key2."`='".$value2."' WHERE Id=".$key;

					$stmt = $db->sql($sql);

					// Prepare statement
					// $stmt = $db->query( 'update' )
					// 	->table( "tracker" )
					// 	->set( $set)
					// 	->where( $where )
					// 	->exec();

					$details="".$key2."=>".$value2."";

					$set2= array('Details' => $details,'TrackerId' =>$key,'Type' =>'Update','ProjectId' =>$_POST['projectid'],'UserId' =>$_POST['userid']);

					$stmt = $db->query( 'insert' )
						->table( "trackerupdate" )
						->set( $set2)
						->exec();

						array_push($keys,$key);
				}

			}

			$_POST['columns']= htmlspecialchars_decode($_POST['columns']);

			$_POST['condition']= htmlspecialchars_decode($_POST['condition']);

			$RAW_SQL_QUERY="select ".$_POST['columns']." from tracker where ProjectId = ".$_POST['projectid']." AND Id in (".implode(",",$keys).")";

			$r=$db ->sql($RAW_SQL_QUERY)->fetchAll();
			$arr=array("data"=>$r,"options"=>'',"files"=>'');//DATATABLE CLIENT SIDE PARSES
			echo json_encode($arr);
			exit();

		}

		if (( isset( $_POST['action'] )) && ( $_POST['action'] == 'remove'))
		{


			foreach ($_POST['data'] as $key => $value) {

				foreach ($value as $key2 => $value2) {

					$details="";

					if ($key2=="Id")
					{
						$where= array('Id' => $key);

						// // Prepare statement
						if($value2>0)
						{
							$stmt = $db->query( 'delete' )
								->table( "tracker" )
								->where( $where )
								->exec();

						}

						$details="Id = ".$key."";

						$set2= array('Details' => $details,'TrackerId' =>$key,'Type' =>'Delete','ProjectId' =>$_POST['projectid'],'UserId' =>$_POST['userid']);

						$stmt = $db->query( 'insert' )
							->table( "trackerupdate" )
							->set( $set2)
							->exec();

					}

				}

			}

			$_POST['columns']= htmlspecialchars_decode($_POST['columns']);

			$_POST['condition']= htmlspecialchars_decode($_POST['condition']);


			$RAW_SQL_QUERY="select ".$_POST['columns']." from tracker where ProjectId = ".$_POST['projectid'];
			// $RAW_SQL_QUERY="select ".$_POST['columns']." from tracker where ProjectId = ".$_POST['projectid']." AND ".$_POST['condition'];

			// echo $RAW_SQL_QUERY;

			$r=$db ->sql($RAW_SQL_QUERY)->fetchAll();
			$arr=array("data"=>$r,"options"=>'',"files"=>'');//DATATABLE CLIENT SIDE PARSES
			echo json_encode($arr);
			exit();

		}

		if (( isset( $_POST['action'] )) && ( $_POST['action'] == 'create'))
		{

			$insertid=0;

			foreach ($_POST['data'] as $key => $value) {

				foreach ($value as $key2 => $value2) {
					# code...
					$set= array($key2 => $value2);
					$where= array('Id' => $key);

					// Prepare statement
					if($value2>0)
					{
						$stmt = $db->query( 'insert' )
							->table( "tracker" )
							->set( $set)
							->exec();

							$insertid=$stmt->insertId();

							$details="Id = ".$stmt->insertId()."";

							$set2= array('Details' => $details,'TrackerId' =>$stmt->insertId(),'Type' =>'Create','ProjectId' =>$_POST['projectid'],'UserId' =>$_POST['userid']);

							$stmt = $db->query( 'insert' )
								->table( "trackerupdate" )
								->set( $set2)
								->exec();

					}
				}

			}

			$_POST['columns']= htmlspecialchars_decode($_POST['columns']);

			$_POST['condition']= htmlspecialchars_decode($_POST['condition']);


			$RAW_SQL_QUERY="select ".$_POST['columns']." from tracker where Id =".$insertid;

			$r=$db ->sql($RAW_SQL_QUERY)->fetchAll();
			$arr=array("data"=>$r,"options"=>'',"files"=>'');//DATATABLE CLIENT SIDE PARSES
			echo json_encode($arr);
			exit();
		}
		else {
			# code...
			$_POST['columns']= htmlspecialchars_decode($_POST['columns']);

			$_POST['condition']= htmlspecialchars_decode($_POST['condition']);

			$RAW_SQL_QUERY="select ".$_POST['columns']." from tracker where ProjectId = ".$_POST['projectid']." AND ".$_POST['condition'];

			$r=$db ->sql($RAW_SQL_QUERY)->fetchAll();

			$arr=array("data"=>$r,"options"=>'',"files"=>'');//DATATABLE CLIENT SIDE PARSES

			echo json_encode($arr);
			exit();
		}


/*
 * Example PHP implementation used for the index.html example
 */

// DataTables PHP library

  // Build our Editor instance and process the data coming from _POST

//   $editor=Editor::inst( $db, 'tracker' )
//   	->fields(
//       Field::inst( 'tracker.Id' ),
//       Field::inst( 'tracker.ProjectID' )
// )
//       ->process( $_POST )
//     	->json();
//   $editor=Editor::inst( $db, 'tracker' )
//   	->fields(
//       Field::inst( 'tracker.Id' ),
//       Field::inst( 'tracker.ProjectID' )
// )
//       ->process( $_POST )
//     	->json();
