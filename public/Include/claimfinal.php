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
  DataTables\Editor\Validate;

/*
 * Example PHP implementation used for the index.html example
 */


 $RAW_SQL_QUERY="select `claimsheets`.`Id`, `claimsheets`.`UserId`, `submitter`.`StaffId` as Staff_ID, `submitter`.`Name`, `claimsheets`.`Claim_Sheet_Name`, `claimsheets`.`Remarks`, `approver`.`Name` as `Approver`, `claimstatuses`.`Status`, `claimsheetstatuses`.`Claim_Status`, `update`.`Name` as `Updated_By`, `claimsheetstatuses`.`Updated_At`, `claimsheets`.`created_at` as `Created_Date`
 from `claimsheets` left join `claims` on `claimsheets`.`Id` = `claims`.`ClaimSheetId` left join (select Max(Id) as maxid2,ClaimSheetId from claimsheetstatuses Group By ClaimSheetId) as max2 on `max2`.`ClaimSheetId` = `claimsheets`.`Id` left join `claimsheetstatuses` on `claimsheetstatuses`.`Id` = max2.`maxid2` left join `users` as `submitter` on `claimsheets`.`UserId` = `submitter`.`Id` left join (select Max(Id) as maxid,ClaimId from claimstatuses Group By ClaimId) as max on `max`.`ClaimId` = `claims`.`Id` left join `claimstatuses` on `claimstatuses`.`Id` = max.`maxid` left join `users` as `approver` on `claimstatuses`.`UserId` = `approver`.`Id` left join `users` as `update` on `claimsheetstatuses`.`UserId` = `update`.`Id` where `claimstatuses`.`Status` like '%Final Approved%' group by `claimsheets`.`Id` order by `claimsheets`.`Id` desc";

 $r=$db ->sql($RAW_SQL_QUERY)->fetchAll();
 $arr=array("data"=>$r,"options"=>'',"files"=>'');//DATATABLE CLIENT SIDE PARSES
 echo json_encode($arr);
 exit();
