@extends('app')

@section('content')

    <div class="content-wrapper">
    <!-- Content Header (Page header) -->


    <section class="content-header">
      <h1>
        Modify Access Control
        <small>Admin</small>
      </h1>
      <ol class="breadcrumb">
        <li><a href="#"><i class="fa fa-dashboard"></i> Home</a></li>
        <li><a href="#">Admin</a></li>
        <li><a href="{{ url('/accesscontrol') }}">Access Control</a></li>
        <li class="active">{{ $access->Name }}</li>
      </ol>
    </section>

    <!-- Main content -->
    <section class="content">
      <div class="row">

        <div class="modal modal-danger fade" id="error-alert">
          <div class="modal-dialog">
            <div class="modal-content">
              <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                  <span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title">Alert!</h4>
              </div>
              <div class="modal-body">
              <ul></ul>
              </div>
              <div class="modal-footer">
                <button type="button" class="btn btn-outline" data-dismiss="modal">Close</button>
              </div>
            </div>
          </div>
        </div>

        <div class="modal modal-success fade" id="update-alert">
          <div class="modal-dialog">
            <div class="modal-content">
              <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                  <span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title">Alert!</h4>
              </div>
              <div class="modal-body">
              <ul></ul>
              </div>
              <div class="modal-footer">
                <button type="button" class="btn btn-outline" data-dismiss="modal">Close</button>
              </div>
            </div>
          </div>
        </div>

        <div class="modal modal-warning fade" id="warning-alert">
          <div class="modal-dialog">
            <div class="modal-content">
              <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                  <span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title">Alert!</h4>
              </div>
              <div class="modal-body">
              <ul></ul>
              </div>
              <div class="modal-footer">
                <button type="button" class="btn btn-outline" data-dismiss="modal">Close</button>
              </div>
            </div>
          </div>
        </div>

        <div class="box-header">
          <button type="button" class="btn btn-success btn-lg" onclick="update()">Update</button>
          <button type="button" class="pull-right btn btn-primary btn-lg" data-toggle="modal" data-target="#SaveTemplate">Save As Template</button>
        </div>

        <!-- Modal -->
<div class="modal fade" id="SaveTemplate" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <div id="exist-alert" class="alert alert-warning alert-dismissible" style="display:none;">
          <button type="button" class="close" onclick="$('#exist-alert').hide()" aria-hidden="true">&times;</button>
          <h4><i class="icon fa fa-check"></i> Alert!</h4>
              Template name already exist.
        </div>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title" id="myModalLabel">Save as Template</h4>
      </div>
      <div class="form-group" padding="10px">
        <input type="text" class="form-control" name="Template_Name" id="Template_Name" placeholder="Enter Template Name ...">
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
        <button type="button" class="btn btn-primary" onclick="savetemplate()">Save</button>
      </div>
    </div>
  </div>
</div>

        <div class="col-md-3">
          <!-- Profile Image -->
          <div class="box box-primary">
            <div class="box-body box-profile">
              <img class="profile-user-img img-responsive img-circle" src="https://superman-hoh.s3.amazonaws.com/images/celebrity_heroes/vector-unisex-avatar-468.png" alt="User profile picture">

              <h3 class="profile-username text-center">{{ $access->Name }}</h3>

              <p class="text-muted text-center">{{ $access->Position }}</p>

            </div>
            <!-- /.box-body -->
          </div>

          <div class="box box-success">
            <h5 class="box-header">General</h5>

              <div class="box-body">
                <div class="form-group">
                  <label>Active : </label><br>
                  <label>
                    <input type="radio" name="Active" class="flat-blue" value="1" <?php if($access->Active == "Yes") { echo 'checked="checked"'; } ?>>
                    Yes
                  </label>
                  <label>
                    <input type="radio" name="Active" class="flat-blue" value="0" <?php if($access->Active == "No") { echo 'checked="checked"'; } ?>>
                    No
                  </label>
                </div>

              <div class="form-group">
                <label>Admin : </label><br>
                <label>
                  <input type="radio" name="Admin" class="flat-blue" value="1" <?php if($access->Admin == "Yes") { echo 'checked="checked"'; } ?>>
                  Yes
                </label>
                <label>
                  <input type="radio" name="Admin" class="flat-blue" value="0" <?php if($access->Admin == "No") { echo 'checked="checked"'; } ?>>
                  No
                </label>

              </div>
            </div>
            <!-- /.box-body -->
          </div>
          <!-- /.box -->

          <div class="box box-success">
            <h5 class="box-header">Access Right Template</h5>

              <div class="box-body">
                <div class="form-group">

                  <div class="form-group">
                   <select class="form-control select2" id="Template" name="Template" style="width: 100%;">
                     <option></option>

                     @foreach ($templates as $template)
                        <option value="{{json_encode($template)}}">{{$template->Template_Name}}</option>
                     @endforeach
                   </select>
                 </div>

                </div>

                <button type="button" class="pull-right btn btn-primary" onclick="RemoveTemplate()">Remove Template</button>

            </div>
            <!-- /.box-body -->
          </div>
        </div>
        <!-- /.col -->

        <div class="col-md-9">

          <div class="nav-tabs-custom">
            <ul class="nav nav-tabs">
              <li class="active"><a href="#resourcemanagement" data-toggle="tab">Resource Management</a></li>
              <li><a href="#salesmanagement" data-toggle="tab">Sales Management</a></li>
            </ul>

            <div class="tab-content">
              <div class="active tab-pane" id="resourcemanagement" width="500px">
                <div class="box box-success">

                  <input type="hidden" name="Id" value="{{ $access->Id }}"/>

                  <div class="box box-solid box-success">
                    <div class="box-header">
                      User Control
                    </div>
                    <div class="box-body">

                      <div class="form-group">
                        <label>Create User : </label><br>
                        <label>
                          <input type="radio" name="Create_User" class="flat-blue" value="1" <?php if($access->Create_User == "Yes") { echo 'checked="checked"'; } ?>>
                          Yes
                        </label>
                        <label>
                          <input type="radio" name="Create_User" class="flat-blue" value="0" <?php if($access->Create_User == "No") { echo 'checked="checked"'; } ?>>
                          No
                        </label>
                      </div>

                      <div class="form-group">
                        <label>Read User : </label><br>
                        <label>
                          <input type="radio" name="Read_User" class="flat-blue" value="1" <?php if($access->Read_User == "Yes") { echo 'checked="checked"'; } ?>>
                          Yes
                        </label>
                        <label>
                          <input type="radio" name="Read_User" class="flat-blue" value="0" <?php if($access->Read_User == "No") { echo 'checked="checked"'; } ?>>
                          No
                        </label>
                      </div>

                      <div class="form-group">
                        <label>Update User : </label><br>
                        <label>
                          <input type="radio" name="Update_User" class="flat-blue" value="1" <?php if($access->Update_User == "Yes") { echo 'checked="checked"'; } ?>>
                          Yes
                        </label>
                        <label>
                          <input type="radio" name="Update_User" class="flat-blue" value="0" <?php if($access->Update_User == "No") { echo 'checked="checked"'; } ?>>
                          No
                        </label>
                      </div>

                      <div class="form-group">
                        <label>Delete User : </label><br>
                        <label>
                          <input type="radio" name="Delete_User" class="flat-blue" value="1" <?php if($access->Delete_User == "Yes") { echo 'checked="checked"'; } ?>>
                          Yes
                        </label>
                        <label>
                          <input type="radio" name="Delete_User" class="flat-blue" value="0" <?php if($access->Delete_User == "No") { echo 'checked="checked"'; } ?>>
                          No
                        </label>
                      </div>
                    </div>
                  </div>

                  <div class="box box-solid box-success">
                    <div class="box-header">
                      CV Control
                    </div>
                    <div class="box-body">
                      <div class="form-group">
                        <label>Create CV : </label><br>
                        <label>
                          <input type="radio" name="Create_CV" class="flat-blue" value="1" <?php if($access->Create_CV == "Yes") { echo 'checked="checked"'; } ?>>
                          Yes
                        </label>
                        <label>
                          <input type="radio" name="Create_CV" class="flat-blue" value="0" <?php if($access->Create_CV == "No") { echo 'checked="checked"'; } ?>>
                          No
                        </label>
                      </div>

                      <div class="form-group">
                        <label>Read CV : </label><br>
                        <label>
                          <input type="radio" name="Read_CV" class="flat-blue" value="1" <?php if($access->Read_CV == "Yes") { echo 'checked="checked"'; } ?>>
                          Yes
                        </label>
                        <label>
                          <input type="radio" name="Read_CV" class="flat-blue" value="0" <?php if($access->Read_CV == "No") { echo 'checked="checked"'; } ?>>
                          No
                        </label>
                      </div>

                      <div class="form-group">
                        <label>Update CV : </label><br>
                        <label>
                          <input type="radio" name="Update_CV" class="flat-blue" value="1" <?php if($access->Update_CV == "Yes") { echo 'checked="checked"'; } ?>>
                          Yes
                        </label>
                        <label>
                          <input type="radio" name="Update_CV" class="flat-blue" value="0" <?php if($access->Update_CV == "No") { echo 'checked="checked"'; } ?>>
                          No
                        </label>
                      </div>

                      <div class="form-group">
                        <label>Delete CV : </label><br>
                        <label>
                          <input type="radio" name="Delete_CV" class="flat-blue" value="1" <?php if($access->Delete_CV == "Yes") { echo 'checked="checked"'; } ?>>
                          Yes
                        </label>
                        <label>
                          <input type="radio" name="Delete_CV" class="flat-blue" value="0" <?php if($access->Delete_CV == "No") { echo 'checked="checked"'; } ?>>
                          No
                        </label>
                      </div>
                    </div>
                  </div>

                  <div class="box box-solid box-success">
                    <div class="box-header">
                      Contractor/Vendor Control
                    </div>
                    <div class="box-body">
                      <div class="form-group">
                        <label>Create Contractor/Vendor : </label><br>
                        <label>
                          <input type="radio" name="Create_Contractor_Vendor" class="flat-blue" value="1" <?php if($access->Create_Contractor_Vendor == "Yes") { echo 'checked="checked"'; } ?>>
                          Yes
                        </label>
                        <label>
                          <input type="radio" name="Create_Contractor_Vendor" class="flat-blue" value="0" <?php if($access->Create_Contractor_Vendor == "No") { echo 'checked="checked"'; } ?>>
                          No
                        </label>
                      </div>

                      <div class="form-group">
                        <label>Read Contractor/Vendor : </label><br>
                        <label>
                          <input type="radio" name="Read_Contractor_Vendor" class="flat-blue" value="1" <?php if($access->Read_Contractor_Vendor == "Yes") { echo 'checked="checked"'; } ?>>
                          Yes
                        </label>
                        <label>
                          <input type="radio" name="Read_Contractor_Vendor" class="flat-blue" value="0" <?php if($access->Read_Contractor_Vendor == "No") { echo 'checked="checked"'; } ?>>
                          No
                        </label>
                      </div>

                      <div class="form-group">
                        <label>Update Contractor/Vendor : </label><br>
                        <label>
                          <input type="radio" name="Update_Contractor_Vendor" class="flat-blue" value="1" <?php if($access->Update_Contractor_Vendor == "Yes") { echo 'checked="checked"'; } ?>>
                          Yes
                        </label>
                        <label>
                          <input type="radio" name="Update_Contractor_Vendor" class="flat-blue" value="0" <?php if($access->Update_Contractor_Vendor == "No") { echo 'checked="checked"'; } ?>>
                          No
                        </label>
                      </div>

                      <div class="form-group">
                        <label>Delete Contractor/Vendor : </label><br>
                        <label>
                          <input type="radio" name="Delete_Contractor_Vendor" class="flat-blue" value="1" <?php if($access->Delete_Contractor_Vendor == "Yes") { echo 'checked="checked"'; } ?>>
                          Yes
                        </label>
                        <label>
                          <input type="radio" name="Delete_Contractor_Vendor" class="flat-blue" value="0" <?php if($access->Delete_Contractor_Vendor == "No") { echo 'checked="checked"'; } ?>>
                          No
                        </label>
                      </div>
                    </div>
                  </div>

                  <div class="box box-solid box-success">
                    <div class="box-header">
                      Organization Chart Control
                    </div>
                    <div class="box-body">

                      <div class="form-group">
                        <label>Read Organization Chart : </label><br>
                        <label>
                          <input type="radio" name="Read_Org_Chart" class="flat-blue" value="1" <?php if($access->Read_Org_Chart == "Yes") { echo 'checked="checked"'; } ?>>
                          Yes
                        </label>
                        <label>
                          <input type="radio" name="Read_Org_Chart" class="flat-blue" value="0" <?php if($access->Read_Org_Chart == "No") { echo 'checked="checked"'; } ?>>
                          No
                        </label>
                      </div>

                      <div class="form-group">
                        <label>Update Organization Chart : </label><br>
                        <label>
                          <input type="radio" name="Update_Org_Chart" class="flat-blue" value="1" <?php if($access->Update_Org_Chart == "Yes") { echo 'checked="checked"'; } ?>>
                          Yes
                        </label>
                        <label>
                          <input type="radio" name="Update_Org_Chart" class="flat-blue" value="0" <?php if($access->Update_Org_Chart == "No") { echo 'checked="checked"'; } ?>>
                          No
                        </label>
                      </div>
                    </div>
                  </div>

                  <div class="box box-solid box-success">
                    <div class="box-header">
                      Claim Control
                    </div>
                    <div class="box-body">

                      <div class="form-group">
                        <label>Read Claim : </label><br>
                        <label>
                          <input type="radio" name="Read_Claim" class="flat-blue" value="1" <?php if($access->Read_Claim == "Yes") { echo 'checked="checked"'; } ?>>
                          Yes
                        </label>
                        <label>
                          <input type="radio" name="Read_Claim" class="flat-blue" value="0" <?php if($access->Read_Claim == "No") { echo 'checked="checked"'; } ?>>
                          No
                        </label>
                      </div>

                      {{-- <div class="form-group">
                        <label>1st Approval : </label><br>
                        <label>
                          <input type="radio" name="Claim_1st_Approval" class="flat-blue" value="1" <?php if($access->Claim_1st_Approval == "Yes") { echo 'checked="checked"'; } ?>>
                          Yes
                        </label>
                        <label>
                          <input type="radio" name="Claim_1st_Approval" class="flat-blue" value="0" <?php if($access->Claim_1st_Approval == "No") { echo 'checked="checked"'; } ?>>
                          No
                        </label>
                      </div>

                      <div class="form-group">
                        <label>2nd Approval : </label><br>
                        <label>
                          <input type="radio" name="Claim_2nd_Approval" class="flat-blue" value="1" <?php if($access->Claim_2nd_Approval == "Yes") { echo 'checked="checked"'; } ?>>
                          Yes
                        </label>
                        <label>
                          <input type="radio" name="Claim_2nd_Approval" class="flat-blue" value="0" <?php if($access->Claim_2nd_Approval == "No") { echo 'checked="checked"'; } ?>>
                          No
                        </label>
                      </div>

                      <div class="form-group">
                        <label>3rd Approval : </label><br>
                        <label>
                          <input type="radio" name="Claim_3rd_Approval" class="flat-blue" value="1" <?php if($access->Claim_3rd_Approval == "Yes") { echo 'checked="checked"'; } ?>>
                          Yes
                        </label>
                        <label>
                          <input type="radio" name="Claim_3rd_Approval" class="flat-blue" value="0" <?php if($access->Claim_3rd_Approval == "No") { echo 'checked="checked"'; } ?>>
                          No
                        </label>
                      </div>

                      <div class="form-group">
                        <label>4th Approval : </label><br>
                        <label>
                          <input type="radio" name="Claim_4th_Approval" class="flat-blue" value="1" <?php if($access->Claim_4th_Approval == "Yes") { echo 'checked="checked"'; } ?>>
                          Yes
                        </label>
                        <label>
                          <input type="radio" name="Claim_4th_Approval" class="flat-blue" value="0" <?php if($access->Claim_4th_Approval == "No") { echo 'checked="checked"'; } ?>>
                          No
                        </label>
                      </div>

                      <div class="form-group">
                        <label>5th Approval : </label><br>
                        <label>
                          <input type="radio" name="Claim_5th_Approval" class="flat-blue" value="1" <?php if($access->Claim_5th_Approval == "Yes") { echo 'checked="checked"'; } ?>>
                          Yes
                        </label>
                        <label>
                          <input type="radio" name="Claim_5th_Approval" class="flat-blue" value="0" <?php if($access->Claim_5th_Approval == "No") { echo 'checked="checked"'; } ?>>
                          No
                        </label>
                      </div>

                      <div class="form-group">
                        <label>Final Approval : </label><br>
                        <label>
                          <input type="radio" name="Claim_Final_Approval" class="flat-blue" value="1" <?php if($access->Claim_Final_Approval == "Yes") { echo 'checked="checked"'; } ?>>
                          Yes
                        </label>
                        <label>
                          <input type="radio" name="Claim_Final_Approval" class="flat-blue" value="0" <?php if($access->Claim_Final_Approval == "No") { echo 'checked="checked"'; } ?>>
                          No
                        </label>
                      </div> --}}

                    </div>
                  </div>

                  <div class="box box-solid box-success">
                    <div class="box-header">
                      Leave Control
                    </div>
                    <div class="box-body">
                      <div class="form-group">
                        <label>Read Leave : </label><br>
                        <label>
                          <input type="radio" name="Read_Leave" class="flat-blue" value="1" <?php if($access->Read_Leave == "Yes") { echo 'checked="checked"'; } ?>>
                          Yes
                        </label>
                        <label>
                          <input type="radio" name="Read_Leave" class="flat-blue" value="0" <?php if($access->Read_Leave == "No") { echo 'checked="checked"'; } ?>>
                          No
                        </label>
                      </div>

                      <div class="form-group">
                        <label>Show Leave To Public : </label><br>
                        <label>
                          <input type="radio" name="Show_Leave_To_Public" class="flat-blue" value="1" <?php if($access->Show_Leave_To_Public == "Yes") { echo 'checked="checked"'; } ?>>
                          Yes
                        </label>
                        <label>
                          <input type="radio" name="Show_Leave_To_Public" class="flat-blue" value="0" <?php if($access->Show_Leave_To_Public == "No") { echo 'checked="checked"'; } ?>>
                          No
                        </label>
                      </div>

                      {{-- <div class="form-group">
                        <label>1st Approval : </label><br>
                        <label>
                          <input type="radio" name="Leave_1st_Approval" class="flat-blue" value="1" <?php if($access->Leave_1st_Approval == "Yes") { echo 'checked="checked"'; } ?>>
                          Yes
                        </label>
                        <label>
                          <input type="radio" name="Leave_1st_Approval" class="flat-blue" value="0" <?php if($access->Leave_1st_Approval == "No") { echo 'checked="checked"'; } ?>>
                          No
                        </label>
                      </div>

                      <div class="form-group">
                        <label>2nd Approval : </label><br>
                        <label>
                          <input type="radio" name="Leave_2nd_Approval" class="flat-blue" value="1" <?php if($access->Leave_2nd_Approval == "Yes") { echo 'checked="checked"'; } ?>>
                          Yes
                        </label>
                        <label>
                          <input type="radio" name="Leave_2nd_Approval" class="flat-blue" value="0" <?php if($access->Leave_2nd_Approval == "No") { echo 'checked="checked"'; } ?>>
                          No
                        </label>
                      </div>

                      <div class="form-group">
                        <label>3rd Approval : </label><br>
                        <label>
                          <input type="radio" name="Leave_3rd_Approval" class="flat-blue" value="1" <?php if($access->Leave_3rd_Approval == "Yes") { echo 'checked="checked"'; } ?>>
                          Yes
                        </label>
                        <label>
                          <input type="radio" name="Leave_3rd_Approval" class="flat-blue" value="0" <?php if($access->Leave_3rd_Approval == "No") { echo 'checked="checked"'; } ?>>
                          No
                        </label>
                      </div>

                      <div class="form-group">
                        <label>4th Approval : </label><br>
                        <label>
                          <input type="radio" name="Leave_4th_Approval" class="flat-blue" value="1" <?php if($access->Leave_4th_Approval == "Yes") { echo 'checked="checked"'; } ?>>
                          Yes
                        </label>
                        <label>
                          <input type="radio" name="Leave_4th_Approval" class="flat-blue" value="0" <?php if($access->Leave_4th_Approval == "No") { echo 'checked="checked"'; } ?>>
                          No
                        </label>
                      </div>

                      <div class="form-group">
                        <label>5th Approval : </label><br>
                        <label>
                          <input type="radio" name="Leave_5th_Approval" class="flat-blue" value="1" <?php if($access->Leave_5th_Approval == "Yes") { echo 'checked="checked"'; } ?>>
                          Yes
                        </label>
                        <label>
                          <input type="radio" name="Leave_5th_Approval" class="flat-blue" value="0" <?php if($access->Leave_5th_Approval == "No") { echo 'checked="checked"'; } ?>>
                          No
                        </label>
                      </div>

                      <div class="form-group">
                        <label>Final Approval : </label><br>
                        <label>
                          <input type="radio" name="Leave_Final_Approval" class="flat-blue" value="1" <?php if($access->Leave_Final_Approval == "Yes") { echo 'checked="checked"'; } ?>>
                          Yes
                        </label>
                        <label>
                          <input type="radio" name="Leave_Final_Approval" class="flat-blue" value="0" <?php if($access->Leave_Final_Approval == "No") { echo 'checked="checked"'; } ?>>
                          No
                        </label>
                      </div> --}}
                    </div>
                  </div>

                  <div class="box box-solid box-success">
                    <div class="box-header">
                      Timesheet Control
                    </div>
                    <div class="box-body">
                      <div class="form-group">
                        <label>Read Timesheet : </label><br>
                        <label>
                          <input type="radio" name="Read_Timesheet" class="flat-blue" value="1" <?php if($access->Read_Timesheet == "Yes") { echo 'checked="checked"'; } ?>>
                          Yes
                        </label>
                        <label>
                          <input type="radio" name="Read_Timesheet" class="flat-blue" value="0" <?php if($access->Read_Timesheet == "No") { echo 'checked="checked"'; } ?>>
                          No
                        </label>
                      </div>

                      {{-- <div class="form-group">
                        <label>1st Approval : </label><br>
                        <label>
                          <input type="radio" name="Timesheet_1st_Approval" class="flat-blue" value="1" <?php if($access->Timesheet_1st_Approval == "Yes") { echo 'checked="checked"'; } ?>>
                          Yes
                        </label>
                        <label>
                          <input type="radio" name="Timesheet_1st_Approval" class="flat-blue" value="0" <?php if($access->Timesheet_1st_Approval == "No") { echo 'checked="checked"'; } ?>>
                          No
                        </label>
                      </div>

                      <div class="form-group">
                        <label>2nd Approval : </label><br>
                        <label>
                          <input type="radio" name="Timesheet_2nd_Approval" class="flat-blue" value="1" <?php if($access->Timesheet_2nd_Approval == "Yes") { echo 'checked="checked"'; } ?>>
                          Yes
                        </label>
                        <label>
                          <input type="radio" name="Timesheet_2nd_Approval" class="flat-blue" value="0" <?php if($access->Timesheet_2nd_Approval == "No") { echo 'checked="checked"'; } ?>>
                          No
                        </label>
                      </div>

                      <div class="form-group">
                        <label>3rd Approval : </label><br>
                        <label>
                          <input type="radio" name="Timesheet_3rd_Approval" class="flat-blue" value="1" <?php if($access->Timesheet_3rd_Approval == "Yes") { echo 'checked="checked"'; } ?>>
                          Yes
                        </label>
                        <label>
                          <input type="radio" name="Timesheet_3rd_Approval" class="flat-blue" value="0" <?php if($access->Timesheet_3rd_Approval == "No") { echo 'checked="checked"'; } ?>>
                          No
                        </label>
                      </div>

                      <div class="form-group">
                        <label>4th Approval : </label><br>
                        <label>
                          <input type="radio" name="Timesheet_4th_Approval" class="flat-blue" value="1" <?php if($access->Timesheet_4th_Approval == "Yes") { echo 'checked="checked"'; } ?>>
                          Yes
                        </label>
                        <label>
                          <input type="radio" name="Timesheet_4th_Approval" class="flat-blue" value="0" <?php if($access->Timesheet_4th_Approval == "No") { echo 'checked="checked"'; } ?>>
                          No
                        </label>
                      </div>

                      <div class="form-group">
                        <label>5th Approval : </label><br>
                        <label>
                          <input type="radio" name="Timesheet_5th_Approval" class="flat-blue" value="1" <?php if($access->Timesheet_5th_Approval == "Yes") { echo 'checked="checked"'; } ?>>
                          Yes
                        </label>
                        <label>
                          <input type="radio" name="Timesheet_5th_Approval" class="flat-blue" value="0" <?php if($access->Timesheet_5th_Approval == "No") { echo 'checked="checked"'; } ?>>
                          No
                        </label>
                      </div>

                      <div class="form-group">
                        <label>Final Approval : </label><br>
                        <label>
                          <input type="radio" name="Timesheet_Final_Approval" class="flat-blue" value="1" <?php if($access->Timesheet_Final_Approval == "Yes") { echo 'checked="checked"'; } ?>>
                          Yes
                        </label>
                        <label>
                          <input type="radio" name="Timesheet_Final_Approval" class="flat-blue" value="0" <?php if($access->Timesheet_Final_Approval == "No") { echo 'checked="checked"'; } ?>>
                          No
                        </label>
                      </div> --}}
                    </div>
                  </div>

                </div>

              </div>

              <div class="tab-pane" id="salesmanagement">
              </div>
            </div>

          </div>
          <!-- /.box -->
        </div>
        <!-- /.col -->
      <!-- /.row -->
    </section>
    <!-- /.content -->
  </div>
  <!-- /.content-wrapper -->
  <footer class="main-footer">
    <div class="pull-right hidden-xs">
      <b>Version</b> 1.0.0
    </div>
    <strong>Copyright &copy; 2014-2016 <a href="http://www.softoya.com">TrackerOnTheGo</a>.</strong> All rights
    reserved.
  </footer>

  <script>

    $(function () {

      //Initialize Select2 Elements
      $(".select2").select2();

      //Flat red color scheme for iCheck
      $('input[type="checkbox"].flat-blue, input[type="radio"].flat-blue').iCheck({
        checkboxClass: 'icheckbox_flat-blue',
        radioClass: 'iradio_flat-blue'
      });
  });

  function RemoveTemplate()
  {

    $.ajaxSetup({
       headers: { 'X-CSRF-Token' : $('meta[name=_token]').attr('content') }
    });

    if ($("#Template").val())
    {
      var result=JSON.parse($("#Template").val());
      var selectedtext=$("#Template option:selected").text();
      var id=result["Id"];

      $.ajax({
                  url: "{{ url('/accesscontrol/removetemplate') }}",
                  method: "POST",
                  data: {Id:id},

                  success: function(response){

                    if (response==1)
                    {
                        var message="Template removed!";

                        $("#update-alert ul").html(message);
                        $("#update-alert").modal('show');


                        jQuery("#Template option:contains('"+ selectedtext +"')").remove();
                    }
                    else {

                        var message="Failed to remove template!";

                        $("#warning-alert ul").html(message);
                        $("#warning-alert").modal('show');

                    }

                    // var message="Template saved!";
                    //
                    // var x = document.getElementById("Template");
                    // var option = document.createElement("option");
                    //
                    // document.getElementById("Template_Name").value = ''
                    // $("#exist-alert").hide();
                    // option.text = Template_Name;
                    // option.value = response;
                    // x.add(option);
                    //
                    // $('#SaveTemplate').modal('hide')
                    // $("#update-alert ul").html(message);
                    // $("#update-alert").show();

          }
      });
    }

  }

  $('#Template').on('change', function() {


    if (this.value)
    {
      var result=JSON.parse(this.value);
      for (var prop in result) {
      if (result.hasOwnProperty(prop)) {
          if (result[prop]==1)
          {
              //document.getElementById(prop).checked = true;
              //$('input[name=Admin]').prop('checked', true);
              //$($('input[name=Admin]')).prop('checked', 1);
              //$('input.type_checkbox[value="6"]').prop('checked', true);
              $("input[name="+prop+"][value=1]").iCheck('check');
          }
          else if (result[prop]==0)
          {
              //$('input[name=Admin]').prop('checked', false);
              //document.getElementById(prop).checked = false;
            $("input[name="+prop+"][value=0]").iCheck('check');
          }
      }
    }

}

  });

  function update() {

      $.ajaxSetup({
         headers: { 'X-CSRF-Token' : $('meta[name=_token]').attr('content') }
      });

      Id=$('[name="Id"]').val();

      Active=$('input[name=Active]:checked').val();
      Admin=$('input[name=Admin]:checked').val();

      Create_User=$('input[name=Create_User]:checked').val();
      Read_User=$('input[name=Read_User]:checked').val();
      Update_User=$('input[name=Update_User]:checked').val();
      Delete_User=$('input[name=Delete_User]:checked').val();

      Create_CV=$('input[name=Create_CV]:checked').val();
      Read_CV=$('input[name=Read_CV]:checked').val();
      Update_CV=$('input[name=Update_CV]:checked').val();
      Delete_CV=$('input[name=Delete_CV]:checked').val();

      Create_Contractor_Vendor=$('input[name=Create_Contractor_Vendor]:checked').val();
      Read_Contractor_Vendor=$('input[name=Read_Contractor_Vendor]:checked').val();
      Update_Contractor_Vendor=$('input[name=Update_Contractor_Vendor]:checked').val();
      Delete_Contractor_Vendor=$('input[name=Delete_Contractor_Vendor]:checked').val();

      Read_Org_Chart=$('input[name=Read_Org_Chart]:checked').val();
      Update_Org_Chart=$('input[name=Update_Org_Chart]:checked').val();

      Read_Claim=$('input[name=Read_Claim]:checked').val();
      Claim_1st_Approval=$('input[name=Claim_1st_Approval]:checked').val();
      Claim_2nd_Approval=$('input[name=Claim_2nd_Approval]:checked').val();
      Claim_3rd_Approval=$('input[name=Claim_3rd_Approval]:checked').val();
      Claim_4th_Approval=$('input[name=Claim_4th_Approval]:checked').val();
      Claim_5th_Approval=$('input[name=Claim_5th_Approval]:checked').val();
      Claim_Final_Approval=$('input[name=Claim_Final_Approval]:checked').val();

      Read_Leave=$('input[name=Read_Leave]:checked').val();
      Show_Leave_To_Public=$('input[name=Show_Leave_To_Public]:checked').val();
      Leave_1st_Approval=$('input[name=Leave_1st_Approval]:checked').val();
      Leave_2nd_Approval=$('input[name=Leave_2nd_Approval]:checked').val();
      Leave_3rd_Approval=$('input[name=Leave_3rd_Approval]:checked').val();
      Leave_4th_Approval=$('input[name=Leave_4th_Approval]:checked').val();
      Leave_5th_Approval=$('input[name=Leave_5th_Approval]:checked').val();
      Leave_Final_Approval=$('input[name=Leave_Final_Approval]:checked').val();

      Read_Timesheet=$('input[name=Read_Timesheet]:checked').val();
      Timesheet_1st_Approval=$('input[name=Timesheet_1st_Approval]:checked').val();
      Timesheet_2nd_Approval=$('input[name=Timesheet_2nd_Approval]:checked').val();
      Timesheet_3rd_Approval=$('input[name=Timesheet_3rd_Approval]:checked').val();
      Timesheet_4th_Approval=$('input[name=Timesheet_4th_Approval]:checked').val();
      Timesheet_5th_Approval=$('input[name=Timesheet_5th_Approval]:checked').val();
      Timesheet_Final_Approval=$('input[name=Timesheet_Final_Approval]:checked').val();

      $.ajax({
                  url: "{{ url('/accesscontrol/update') }}",
                  method: "POST",
                  data: {Id:Id,
                    Active:Active,
                    Admin:Admin,
                    Create_User:Create_User,
                    Read_User:Read_User,
                    Update_User:Update_User,
                    Delete_User:Delete_User,
                    Create_CV:Create_CV,
                    Read_CV:Read_CV,
                    Update_CV:Update_CV,
                    Delete_CV:Delete_CV,
                    Create_Contractor_Vendor:Create_Contractor_Vendor,
                    Read_Contractor_Vendor:Read_Contractor_Vendor,
                    Update_Contractor_Vendor:Update_Contractor_Vendor,
                    Delete_Contractor_Vendor:Delete_Contractor_Vendor,
                    Read_Org_Chart:Read_Org_Chart,
                    Update_Org_Chart:Update_Org_Chart,
                    Read_Claim:Read_Claim,
                    // Claim_1st_Approval:Claim_1st_Approval,
                    // Claim_2nd_Approval:Claim_2nd_Approval,
                    // Claim_3rd_Approval:Claim_3rd_Approval,
                    // Claim_4th_Approval:Claim_4th_Approval,
                    // Claim_5th_Approval:Claim_5th_Approval,
                    // Claim_Final_Approval:Claim_Final_Approval,
                    Read_Leave:Read_Leave,
                    Show_Leave_To_Public:Show_Leave_To_Public,
                    // Leave_1st_Approval:Leave_1st_Approval,
                    // Leave_2nd_Approval:Leave_2nd_Approval,
                    // Leave_3rd_Approval:Leave_3rd_Approval,
                    // Leave_4th_Approval:Leave_4th_Approval,
                    // Leave_5th_Approval:Leave_5th_Approval,
                    // Leave_Final_Approval:Leave_Final_Approval,
                    Read_Timesheet:Read_Timesheet,
                    // Timesheet_1st_Approval:Timesheet_1st_Approval,
                    // Timesheet_2nd_Approval:Timesheet_2nd_Approval,
                    // Timesheet_3rd_Approval:Timesheet_3rd_Approval,
                    // Timesheet_4th_Approval:Timesheet_4th_Approval,
                    // Timesheet_5th_Approval:Timesheet_5th_Approval,
                    // Timesheet_Final_Approval:Timesheet_Final_Approval,

                  success: function(response){
                    if (response==1)
                    {
                        var message ="Access Control Updated!";
                        $("#update-alert ul").html(message);
                        $("#update-alert").modal('show');


                        //$("#update-alert").show();
                        // setTimeout(function() {
                        //     $("#update-alert").hide('blind', {}, 500)
                        // }, 5000);

                    }
                    else {

                      var message ="No update on access control!";
                      $("#warning-alert ul").html(message);
                      $("#warning-alert").modal('show');


                      // $("#warning-alert").show();
                      // setTimeout(function() {
                      //     $("#warning-alert").hide('blind', {}, 500)
                      // }, 5000);

                    }

          }
      });

  }

  function savetemplate() {

      $.ajaxSetup({
         headers: { 'X-CSRF-Token' : $('meta[name=_token]').attr('content') }
      });

      Template_Name=$('[name="Template_Name"]').val();

      if ($('#Template option:contains('+ Template_Name +')').length){
          $("#exist-alert").show();
      }
      else {

        UserId={{$me->UserId}};

        Active=$('input[name=Active]:checked').val();
        Admin=$('input[name=Admin]:checked').val();

        Create_User=$('input[name=Create_User]:checked').val();
        Read_User=$('input[name=Read_User]:checked').val();
        Update_User=$('input[name=Update_User]:checked').val();
        Delete_User=$('input[name=Delete_User]:checked').val();

        Create_CV=$('input[name=Create_CV]:checked').val();
        Read_CV=$('input[name=Read_CV]:checked').val();
        Update_CV=$('input[name=Update_CV]:checked').val();
        Delete_CV=$('input[name=Delete_CV]:checked').val();

        Create_Contractor_Vendor=$('input[name=Create_Contractor_Vendor]:checked').val();
        Read_Contractor_Vendor=$('input[name=Read_Contractor_Vendor]:checked').val();
        Update_Contractor_Vendor=$('input[name=Update_Contractor_Vendor]:checked').val();
        Delete_Contractor_Vendor=$('input[name=Delete_Contractor_Vendor]:checked').val();

        Read_Org_Chart=$('input[name=Read_Org_Chart]:checked').val();
        Update_Org_Chart=$('input[name=Update_Org_Chart]:checked').val();

        Read_Claim=$('input[name=Read_Claim]:checked').val();
        // Claim_1st_Approval=$('input[name=Claim_1st_Approval]:checked').val();
        // Claim_2nd_Approval=$('input[name=Claim_2nd_Approval]:checked').val();
        // Claim_3rd_Approval=$('input[name=Claim_3rd_Approval]:checked').val();
        // Claim_4th_Approval=$('input[name=Claim_4th_Approval]:checked').val();
        // Claim_5th_Approval=$('input[name=Claim_5th_Approval]:checked').val();
        // Claim_Final_Approval=$('input[name=Claim_Final_Approval]:checked').val();

        Read_Leave=$('input[name=Read_Leave]:checked').val();
        Show_Leave_To_Public=$('input[name=Show_Leave_To_Public]:checked').val();
        // Leave_1st_Approval=$('input[name=Leave_1st_Approval]:checked').val();
        // Leave_2nd_Approval=$('input[name=Leave_2nd_Approval]:checked').val();
        // Leave_3rd_Approval=$('input[name=Leave_3rd_Approval]:checked').val();
        // Leave_4th_Approval=$('input[name=Leave_4th_Approval]:checked').val();
        // Leave_5th_Approval=$('input[name=Leave_5th_Approval]:checked').val();
        // Leave_Final_Approval=$('input[name=Leave_Final_Approval]:checked').val();

        Read_Timesheet=$('input[name=Read_Timesheet]:checked').val();
        // Timesheet_1st_Approval=$('input[name=Timesheet_1st_Approval]:checked').val();
        // Timesheet_2nd_Approval=$('input[name=Timesheet_2nd_Approval]:checked').val();
        // Timesheet_3rd_Approval=$('input[name=Timesheet_3rd_Approval]:checked').val();
        // Timesheet_4th_Approval=$('input[name=Timesheet_4th_Approval]:checked').val();
        // Timesheet_5th_Approval=$('input[name=Timesheet_5th_Approval]:checked').val();
        // Timesheet_Final_Approval=$('input[name=Timesheet_Final_Approval]:checked').val();

        $.ajax({
                    url: "{{ url('/accesscontrol/savetemplate') }}",
                    method: "POST",
                    data: {UserId:UserId,
                      Template_Name:Template_Name,
                      Active:Active,
                      Admin:Admin,
                      Create_User:Create_User,
                      Read_User:Read_User,
                      Update_User:Update_User,
                      Delete_User:Delete_User,
                      Create_CV:Create_CV,
                      Read_CV:Read_CV,
                      Update_CV:Update_CV,
                      Delete_CV:Delete_CV,
                      Create_Contractor_Vendor:Create_Contractor_Vendor,
                      Read_Contractor_Vendor:Read_Contractor_Vendor,
                      Update_Contractor_Vendor:Update_Contractor_Vendor,
                      Delete_Contractor_Vendor:Delete_Contractor_Vendor,
                      Read_Org_Chart:Read_Org_Chart,
                      Update_Org_Chart:Update_Org_Chart,
                      Read_Claim:Read_Claim,
                      // Claim_1st_Approval:Claim_1st_Approval,
                      // Claim_2nd_Approval:Claim_2nd_Approval,
                      // Claim_3rd_Approval:Claim_3rd_Approval,
                      // Claim_4th_Approval:Claim_4th_Approval,
                      // Claim_5th_Approval:Claim_5th_Approval,
                      // Claim_Final_Approval:Claim_Final_Approval,
                      Read_Leave:Read_Leave,
                      Show_Leave_To_Public:Show_Leave_To_Public,
                      // Leave_1st_Approval:Leave_1st_Approval,
                      // Leave_2nd_Approval:Leave_2nd_Approval,
                      // Leave_3rd_Approval:Leave_3rd_Approval,
                      // Leave_4th_Approval:Leave_4th_Approval,
                      // Leave_5th_Approval:Leave_5th_Approval,
                      // Leave_Final_Approval:Leave_Final_Approval,
                      Read_Timesheet:Read_Timesheet,
                      // Timesheet_1st_Approval:Timesheet_1st_Approval,
                      // Timesheet_2nd_Approval:Timesheet_2nd_Approval,
                      // Timesheet_3rd_Approval:Timesheet_3rd_Approval,
                      // Timesheet_4th_Approval:Timesheet_4th_Approval,
                      // Timesheet_5th_Approval:Timesheet_5th_Approval,
                      // Timesheet_Final_Approval:Timesheet_Final_Approval,

                    success: function(response){

                      var message="Template saved!";

                      var x = document.getElementById("Template");
                      var option = document.createElement("option");

                      document.getElementById("Template_Name").value = ''
                      $("#exist-alert").hide();
                      option.text = Template_Name;
                      option.value = response;
                      x.add(option);

                      $('#SaveTemplate').modal('hide')
                      $("#update-alert ul").html(message);
                      $("#update-alert").modal('show');


                      // if (response==1)
                      // {
                      //
                      //     $("#update-alert").show();
                      //     setTimeout(function() {
                      //         $("#update-alert").hide('blind', {}, 500)
                      //     }, 5000);
                      //
                      // }
                      // else {
                      //
                      //   $("#warning-alert").show();
                      //   setTimeout(function() {
                      //       $("#warning-alert").hide('blind', {}, 500)
                      //   }, 5000);
                      //
                      // }

            }
        });

      }

  }

  </script>

@endsection
