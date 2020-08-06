<?php namespace App;

use Illuminate\Database\Eloquent\Model;

class AccessControlTemplate extends Model {

	//
  protected $table = 'accesscontroltemplates';
  protected $primaryKey = 'Id';

  /**
  * Run the migrations.
  *
  * @return void
  */
  public function up()
  {
      Schema::create('accesscontroltemplates', function(Blueprint $table)
      {
          $table->increments('Id');
          $table->integer('Created_By');

          $table->string('Template_Name');

          $table->boolean('View_User_Profile')->default(false);
          $table->boolean('Edit_User')->default(false);

          $table->boolean('Staff_Monitoring')->default(false);

          $table->boolean('View_CV')->default(false);
          $table->boolean('Export_CV')->default(false);
          $table->boolean('Edit_CV')->default(false);

          $table->boolean('View_Contractor_Profile')->default(false);
          $table->boolean('Edit_Contractor')->default(false);

          $table->boolean('View_Org_Chart')->default(false);
          $table->boolean('Update_Org_Chart')->default(false);

          $table->boolean('Approve_Leave')->default(false);
          $table->boolean('View_All_Leave')->default(false);
          $table->boolean('View_Leave_Summary')->default(false);
          $table->boolean('Show_Leave_To_Public')->default(false);

          $table->boolean('Approve_Timesheet')->default(false);
          $table->boolean('View_Allowance')->default(false);
          $table->boolean('View_All_Timesheet')->default(false);
          $table->boolean('View_Timesheet_Summary')->default(false);
          $table->boolean('Timesheet_Required')->default(false);

          $table->boolean('Approve_Claim')->default(false);
          $table->boolean('View_All_Claim')->default(false);
          $table->boolean('View_Claim_Summary')->default(false);

          $table->boolean('Access_Control')->default(false);
          $table->boolean('Approval_Control')->default(false);
          $table->boolean('Allowance_Control')->default(false);
          $table->boolean('Asset_Tracking')->default(false);
          $table->boolean('Option_Control')->default(false);
          $table->boolean('Holiday_Management')->default(false);
          $table->boolean('Notice_Board_Management')->default(false);
          $table->boolean('Chart_Management')->default(false);
          $table->boolean('Project_Access')->default(false);
          $table->boolean('Notification_Maintenance')->default(false);
          $table->boolean('View_Login_Tracking')->default(false);
          $table->boolean('Import_Data')->default(false);

          $table->boolean('Create_Project')->default(false);
          $table->boolean('Delete_Project')->default(false);
          $table->boolean('Create_Project_Code')->default(false);
          $table->boolean('Delete_Project_Code')->default(false);
          $table->boolean('Project_Manager')->default(false);
          $table->boolean('View_Project_List')->default(false);
          $table->boolean('View_Resource_Calendar')->default(false);
          $table->boolean('View_Resource_Summary')->default(false);
          $table->boolean('View_Report_Store')->default(false);
          $table->boolean('Resource_Allocation')->default(false);
          $table->boolean('Staff_Skill')->default(false);
          $table->boolean('Project_Requirement')->default(false);
          $table->boolean('Import_Tracker')->default(false);

          $table->boolean('View_PO_Management')->default(false);
          $table->boolean('Create_PO')->default(false);
          $table->boolean('Delete_PO')->default(false);
          $table->boolean('View_PO_Summary')->default(false);
          $table->boolean('View_Invoice_Management')->default(false);
          $table->boolean('Create_Invoice')->default(false);
          $table->boolean('Delete_Invoice')->default(false);
          $table->boolean('View_Invoice_Summary')->default(false);
          $table->boolean('View_WIP')->default(false);
          $table->boolean('View_Forecast')->default(false);
          $table->boolean('View_PNL')->default(false);

          $table->timestamps();
      });
  }

  /**
  * Reverse the migrations.
  *
  * @return void
  */
  public function down()
  {
       Schema::drop('accesscontroltemplates');
  }

}
