@extends('app')

@section('datatable-css')

    <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.10.12/css/jquery.dataTables.min.css">
    <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/buttons/1.2.1/css/buttons.dataTables.min.css">
    <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/responsive/2.1.0/css/responsive.dataTables.min.css">
    <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/select/1.2.0/css/select.dataTables.min.css">
    <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/keytable/2.1.2/css/keyTable.dataTables.min.css">
    <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/autofill/2.1.2/css/autoFill.dataTables.min.css">
    <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/colreorder/1.3.2/css/colReorder.dataTables.min.css">
    <link rel="stylesheet" type="text/css" href="{{ asset('/plugin/css/editor.dataTables.min.css') }}">
    {{-- <link rel="stylesheet" type="text/css" href="{{ asset('/plugin/examples/resources/syntax/shCore.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('/Plugin/examples/resources/demo.css') }}"> --}}

    <style type="text/css" class="init">
      a.buttons-collection {
        margin-left: 1em;
      }
      .container1{
              width: 1200px;
              margin-left: 50px;
              padding: 10px;
      }

    </style>

@endsection

@section('datatable-js')

      <script type="text/javascript" language="javascript" src="https://cdn.datatables.net/1.10.12/js/jquery.dataTables.min.js"></script>
      <script type="text/javascript" language="javascript" src="https://cdn.datatables.net/buttons/1.2.1/js/dataTables.buttons.min.js"></script>
      <script type="text/javascript" language="javascript" src="https://cdn.datatables.net/select/1.2.0/js/dataTables.select.min.js"></script>
      <script type="text/javascript" language="javascript" src="https://cdn.datatables.net/autofill/2.1.2/js/dataTables.autoFill.min.js"></script>
      <script type="text/javascript" language="javascript" src="https://cdn.datatables.net/colreorder/1.3.2/js/dataTables.colReorder.min.js"></script>
      <script type="text/javascript" language="javascript" src="https://cdn.datatables.net/responsive/2.1.0/js/dataTables.responsive.min.js"></script>
      <script type="text/javascript" language="javascript" src="https://cdnjs.cloudflare.com/ajax/libs/jszip/2.5.0/jszip.min.js"></script>
      <script type="text/javascript" language="javascript" src="https://cdn.datatables.net/keytable/2.1.2/js/dataTables.keyTable.min.js"></script>
      <script type="text/javascript" language="javascript" src="https://cdn.rawgit.com/bpampuch/pdfmake/0.1.18/build/pdfmake.min.js"></script>
      <script type="text/javascript" language="javascript" src="https://cdn.rawgit.com/bpampuch/pdfmake/0.1.18/build/vfs_fonts.js"></script>
      <script type="text/javascript" language="javascript" src="https://cdn.datatables.net/buttons/1.2.0/js/buttons.html5.min.js"></script>
      <script type="text/javascript" language="javascript" src="https://cdn.datatables.net/buttons/1.2.0/js/buttons.print.min.js"></script>

      <script type="text/javascript" language="javascript" src="{{ asset('/plugin/js/dataTables.editor.min.js') }}"></script>
      {{-- <script type="text/javascript" language="javascript" src="{{ asset('/plugin/examples/resources/syntax/shCore.js') }}"></script>
      <script type="text/javascript" language="javascript" src="{{ asset('/plugin/examples/resources/demo.js') }}"></script>
      <script type="text/javascript" language="javascript" src="{{ asset('/plugin/examples/resources/editor-demo.js') }}"></script> --}}

      <script type="text/javascript" language="javascript" class="init">

          var experienceseditor;
          var licenseseditor;
          var referenceseditor;
          var skillseditor;

          var experienceid; // use a global for the submit and return data rendering in the examples
          var licenseid;
          var referenceid;
          var skillid;

          var experiencetable; // use a global for the submit and return data rendering in the examples
          var licensetable;
          var referencetable;
          var skilltable;

          $(document).ready(function() {
                         experienceseditor = new $.fn.dataTable.Editor( {
                                ajax: "{{ asset('/Include/experience.php') }}",
                                 table: "#experiencetable",
                                 idSrc: "experiences.Id",
                                 fields: [
                                         {
                                                 label: "UserId:",
                                                 name: "experiences.UserId",
                                                 type: "hidden",
                                                 default: {{ $UserId }}
                                         }, {
                                                 label: "Project:",
                                                 name: "experiences.Project"
                                         },{
                                                 label: "Role:",
                                                 name: "experiences.Role"
                                         }, {
                                                 label: "Responsibility:",
                                                 name: "experiences.Responsibility"
                                         }, {
                                                 label: "Achievement:",
                                                 name: "experiences.Achievement"
                                         }, {
                                                 label: "Start_Date:",
                                                 name: "experiences.Start_Date"
                                         }, {
                                                 label: "End_Date:",
                                                 name: "experiences.End_Date"
                                         }, {
                                                 label: "File:",
                                                 name: "files.Web_Path",
                                                 type: "upload",
                                                 ajaxData: function ( d ) {
                                                   d.append( 'Id', experienceid ); // edit - use `d`
                                                 },
                                                 display: function ( file_id ) {
                                                     return '<a href="'+ experiencetable.api().row( experienceseditor.modifier() ).data().files.Web_Path +'">Download</>';
                                                 },
                                                 noImageText: 'No file'
                                         }

                                 ]
                         } );

                         licenseseditor = new $.fn.dataTable.Editor( {
                                ajax: "{{ asset('/Include/license.php') }}",
                                 table: "#licensetable",
                                 idSrc: "licenses.Id",
                                 fields: [
                                         {
                                                 label: "UserId:",
                                                 name: "licenses.UserId",
                                                 type: "hidden",
                                                 default: {{ $UserId }}
                                         }, {
                                                 label: "License Type:",
                                                 name: "licenses.License_Type"
                                         },{
                                                 label: "Identity No:",
                                                 name: "licenses.Identity_No"
                                         }, {
                                                 label: "Issue Date:",
                                                 name: "licenses.Issue_Date"
                                         }, {
                                                 label: "Expiry Date:",
                                                 name: "licenses.Expiry_Date"
                                         }, {
                                                 label: "License Status:",
                                                 name: "licenses.License_Status"
                                         }, {
                                                 label: "Start_Date:",
                                                 name: "licenses.Start_Date"
                                         }, {
                                                 label: "End_Date:",
                                                 name: "licenses.End_Date"
                                         }, {
                                                 label: "File:",
                                                 name: "files.Web_Path",
                                                 type: "upload",
                                                 ajaxData: function ( d ) {
                                                   d.append( 'Id', licenseid ); // edit - use `d`
                                                 },
                                                 display: function ( file_id ) {
                                                     return '<a href="'+ licensetable.api().row( licenseseditor.modifier() ).data().files.Web_Path +'">Download</>';
                                                 },
                                                 noImageText: 'No file'
                                         }

                                 ]
                         } );

                         referenceseditor = new $.fn.dataTable.Editor( {
                                ajax: "{{ asset('/Include/reference.php') }}",
                                 table: "#referencetable",
                                 idSrc: "references.Id",
                                 fields: [
                                         {
                                                 label: "UserId:",
                                                 name: "references.UserId",
                                                 type: "hidden",
                                                 default: {{ $UserId }}
                                         }, {
                                                 label: "Reference:",
                                                 name: "references.Reference"
                                         },{
                                                 label: "Contact No:",
                                                 name: "references.Contact_No"
                                         }, {
                                                 label: "Company:",
                                                 name: "references.Company"
                                         }, {
                                                 label: "Position:",
                                                 name: "references.Position"
                                         }, {
                                                 label: "File:",
                                                 name: "files.Web_Path",
                                                 type: "upload",
                                                 ajaxData: function ( d ) {
                                                   d.append( 'Id', referenceid ); // edit - use `d`
                                                 },
                                                 display: function ( file_id ) {
                                                     return '<a href="'+ referencetable.api().row( referenceseditor.modifier() ).data().files.Web_Path +'">Download</>';
                                                 },
                                                 noImageText: 'No file'
                                         }

                                 ]
                         } );

                         skillseditor = new $.fn.dataTable.Editor( {
                                ajax: "{{ asset('/Include/skill.php') }}",
                                 table: "#skilltable",
                                 idSrc: "skills.Id",
                                 fields: [
                                         {
                                                 label: "UserId:",
                                                 name: "skills.UserId",
                                                 type: "hidden",
                                                 default: {{ $UserId }}
                                         }, {
                                                 label: "Skill:",
                                                 name: "skills.Skill"
                                         },{
                                                 label: "Level:",
                                                 name: "skills.Level"
                                         }, {
                                                 label: "Description:",
                                                 name: "skills.Description"
                                         }, {
                                                 label: "File:",
                                                 name: "files.Web_Path",
                                                 type: "upload",
                                                 ajaxData: function ( d ) {
                                                   d.append( 'Id', skillid ); // edit - use `d`
                                                 },
                                                 display: function ( file_id ) {
                                                     return '<a href="'+ skilltable.api().row( skillseditor.modifier() ).data().files.Web_Path +'">Download</>';
                                                 },
                                                 noImageText: 'No file'
                                         }

                                 ]
                         } );

                     experiencetable=$('#experiencetable').dataTable( {
                            // keys: {
                            //      columns: ':not(:first-child)',
                            //      editor:  experienceseditor   //THIS LINE FIXED THE PROBLEM
                            //  },
                             columnDefs: [{ "visible": false, "targets": [0] }],
                             responsive: false,
                             colReorder: false,
                             dom: "Brt",
                             bAutoWidth: true,
                             "bScrollCollapse": true,
                             columns: [
                                     { data: "experiences.Id"},
                                     { data: "experiences.Project" },
                                     { data: "experiences.Role" },
                                     { data: "experiences.Responsibility" },
                                     { data: "experiences.Achievement" },
                                     { data: "experiences.Start_Date"},
                                     { data: "experiences.End_Date"},
                                     { data: "files.Web_Path",
                                        render: function ( url, type, row ) {
                                             if (url)
                                             {
                                               return '<a href="'+ url +'">Download</a>';

                                             }
                                             else {
                                               return ' - ';
                                             }

                                         },
                                         title: "File"
                                       }
                             ],
                             autoFill: {
                                     columns: ':not(:first-child)',
                                      editor:  experienceseditor
                             },
                             select: {
                                     style:    'os',
                                     selector: 'td:first-child'
                             },
                             buttons: [
                                     { extend: "create", editor: experienceseditor },
                                     { extend: "edit",   editor: experienceseditor },
                                     { extend: "remove", editor: experienceseditor }
                             ],

                 });

                 $('#experiencetable').on( 'click', 'tr', function () {
                   // Get the rows id value
                  //  var row=$(this).closest("tr");
                  //  var oTable = row.closest('table').dataTable();
                   experienceid = experiencetable.api().row( this ).data().experiences.Id;
                 });

                 licensetable=$('#licensetable').dataTable( {
                        // keys: {
                        //      columns: ':not(:first-child)',
                        //      editor:  licenseseditor   //THIS LINE FIXED THE PROBLEM
                        //  },
                         columnDefs: [{ "visible": false, "targets": [0] }],
                         responsive: false,
                         colReorder: false,
                         dom: "Brt",
                         bAutoWidth: true,
                         "bScrollCollapse": true,
                         columns: [
                                 { data: "licenses.Id"},
                                 { data: "licenses.License_Type" },
                                 { data: "licenses.Identity_No" },
                                 { data: "licenses.Issue_Date" },
                                 { data: "licenses.Expiry_Date" },
                                 { data: "licenses.License_Status" },
                                 { data: "licenses.Start_Date"},
                                 { data: "licenses.End_Date"},
                                 { data: "files.Web_Path",
                                    render: function ( url, type, row ) {
                                         if (url)
                                         {
                                           return '<a href="'+ url +'">Download</a>';

                                         }
                                         else {
                                           return ' - ';
                                         }

                                     },
                                     title: "File"
                                   }
                         ],
                         autoFill: {
                                 columns: ':not(:first-child)',
                                  editor:  licenseseditor
                         },
                         select: {
                                 style:    'os',
                                 selector: 'td:first-child'
                         },
                         buttons: [
                                 { extend: "create", editor: licenseseditor },
                                 { extend: "edit",   editor: licenseseditor },
                                 { extend: "remove", editor: licenseseditor }
                         ],

                  });

                  $('#licensetable').on( 'click', 'tr', function () {
                    // Get the rows id value
                   //  var row=$(this).closest("tr");
                   //  var oTable = row.closest('table').dataTable();
                    licenseid = licensetable.api().row( this ).data().licenses.Id;
                  });

                   referencetable=$('#referencetable').dataTable( {
                          // keys: {
                          //      columns: ':not(:first-child)',
                          //      editor:  referenceseditor   //THIS LINE FIXED THE PROBLEM
                          //  },
                           columnDefs: [{ "visible": false, "targets": [0] }],
                           responsive: false,
                           colReorder: false,
                           dom: "Brt",
                           bAutoWidth: true,
                           "bScrollCollapse": true,
                           columns: [
                                   { data: "references.Id"},
                                   { data: "references.Reference" },
                                   { data: "references.Contact_No" },
                                   { data: "references.Company" },
                                   { data: "references.Position" },
                                   { data: "files.Web_Path",
                                      render: function ( url, type, row ) {
                                           if (url)
                                           {
                                             return '<a href="'+ url +'">Download</a>';

                                           }
                                           else {
                                             return ' - ';
                                           }

                                       },
                                       title: "File"
                                     }
                           ],
                           autoFill: {
                                   columns: ':not(:first-child)',
                                    editor:  referenceseditor
                           },
                           select: {
                                   style:    'os',
                                   selector: 'td:first-child'
                           },
                           buttons: [
                                   { extend: "create", editor: referenceseditor },
                                   { extend: "edit",   editor: referenceseditor },
                                   { extend: "remove", editor: referenceseditor }
                           ],

                    });

                    $('#referencetable').on( 'click', 'tr', function () {
                      // Get the rows id value
                     //  var row=$(this).closest("tr");
                     //  var oTable = row.closest('table').dataTable();
                      referenceid = referencetable.api().row( this ).data().references.Id;
                    });

                    skilltable=$('#skilltable').dataTable( {
                          //  keys: {
                          //       columns: ':not(:first-child)',
                          //       editor:  skillseditor   //THIS LINE FIXED THE PROBLEM
                          //   },
                            columnDefs: [{ "visible": false, "targets": [0] }],
                            responsive: false,
                            colReorder: false,
                            dom: "Brt",
                            bAutoWidth: true,
                            "bScrollCollapse": true,
                            columns: [
                                    { data: "skills.Id"},
                                    { data: "skills.Skill" },
                                    { data: "skills.Level" },
                                    { data: "skills.Description" },
                                    { data: "files.Web_Path",
                                       render: function ( url, type, row ) {
                                            if (url)
                                            {
                                              return '<a href="'+ url +'">Download</a>';

                                            }
                                            else {
                                              return ' - ';
                                            }

                                        },
                                        title: "File"
                                      }
                            ],
                            autoFill: {
                                    columns: ':not(:first-child)',
                                     editor:  skillseditor
                            },
                            select: {
                                    style:    'os',
                                    selector: 'td:first-child'
                            },
                            buttons: [
                                    { extend: "create", editor: skillseditor },
                                    { extend: "edit",   editor: skillseditor },
                                    { extend: "remove", editor: skillseditor }
                            ],

                     });

                     $('#skilltable').on( 'click', 'tr', function () {
                       // Get the rows id value
                      //  var row=$(this).closest("tr");
                      //  var oTable = row.closest('table').dataTable();
                       skillid = skilltable.api().row( this ).data().skills.Id;
                     });

          } );

      </script>

@endsection

@section('content')

  <!-- Content Wrapper. Contains page content -->
    {{-- <div class="content-wrapper">
        <!-- Content Header (Page header) -->
        <section class="content-header">
            @foreach ($users as $user)
                <p>
                    {{ $user->Name }}
                </p>
            @endforeach
        </section>
    </div> --}}

    <div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
      <h1>
        Contractor Detail
        <small>Resource Mangement</small>
      </h1>
      <ol class="breadcrumb">
        <li><a href="#"><i class="fa fa-dashboard"></i> Home</a></li>
        <li><a href="#">Resource Management</a></li>
        <li><a href="{{ url('/user') }}">Contractor Profile</a></li>
        <li class="active">{{ $user->Name }}</li>
      </ol>
    </section>

    <!-- Main content -->
    <section class="content">
      <div class="row">
        <!-- /.col -->
        <div class="col-md-12">

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

          <div class="nav-tabs-custom">
            <ul class="nav nav-tabs">
              {{-- <li><a href="#company" data-toggle="tab">Company</a></li>
              <li><a href="#document" data-toggle="tab">Document</a></li>
              <li><a href="#evaluation" data-toggle="tab">Evaluation</a></li> --}}

              <li class="active"><a href="#contractordetail" data-toggle="tab">Contractor Detail</a></li>
              <li><a href="#experience" data-toggle="tab">Experience</a></li>
              <li><a href="#skill" data-toggle="tab">Skill</a></li>
              <li><a href="#license" data-toggle="tab">License</a></li>
              <li><a href="#reference" data-toggle="tab">Reference</a></li>
              <li><a href="#document" data-toggle="tab">Document</a></li>
            </ul>

            <div class="tab-content">
              <div class="active tab-pane" id="contractordetail">
                  <div class="box-body">

                      <div class="box-body">
                        <button type="button" class="btn btn-success btn-lg" data-toggle="modal" data-target="#UpdateProfile">Update Profile</button>
                        <button type="button" class="btn btn-success btn-lg" data-toggle="modal" data-target="#UpdateProfilePicture">Update Profile Picture</button>
                        <button type="button" class="btn btn-success btn-lg" data-toggle="modal" data-target="#ChangePassword">Change Password</button>
                      </div>

                      <div class="modal fade" id="ChangePassword" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
                        <div class="modal-dialog">
                          <div class="modal-content">
                            <div class="modal-header">
                              <div id="exist-alert" class="alert alert-warning alert-dismissible" style="display:none;">
                                <h4><i class="icon fa fa-check"></i> Alert!</h4>
                                <div id="changepasswordmessage"></div>
                              </div>
                              <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                              <h4 class="modal-title" id="myModalLabel">Change Your Password</h4>

                            </div>

                            <div class="modal-body">
                              <h6 class="modal-title" id="myModalLabel">Please enter a new password in the fields below.</h6>
                              <div class="form-group">
                                <div class="form-group">
                    							<label class="col-md-4 control-label">Current Password</label>
                    							<div class="col-md-6">
                    								<input type="password" class="form-control" name="CurrentPassword"/>
                    							</div>
                    						</div>
                                <div class="form-group">
                    							<label class="col-md-4 control-label">Password</label>
                    							<div class="col-md-6">
                    								<input type="password" class="form-control" name="Password"/>
                    							</div>
                    						</div>
                                <div class="form-group">
                    							<label class="col-md-4 control-label">Confirm Password</label>
                    							<div class="col-md-6">
                    								<input type="password" class="form-control" name="ConfirmPassword"/>
                    							</div>
                    						</div>
                              </div>

                            </div>

                            <br><br><br><br>
                            <div class="modal-footer">

                              <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                              <button type="button" class="btn btn-primary" onclick="changepassword()">Update</button>
                            </div>
                          </div>
                        </div>
                      </div>

                      <div class="modal fade" id="UpdateProfile" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
                        <div class="modal-dialog" role="document">
                          <div class="modal-content">
                            <div class="modal-header">
                              <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                              <h4 class="modal-title" id="myModalLabel">Update Profile</h4>
                            </div>
                            <div class="modal-body">
                                Are you sure you wish to update this profile?
                            </div>
                            <div class="modal-footer">
                              <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                              <button type="button" class="btn btn-primary" onclick="updateprofile()">Update</button>
                            </div>
                          </div>
                        </div>
                      </div>

                      <div class="modal fade" id="UpdateProfilePicture" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
                        <div class="modal-dialog" role="document">
                          <div class="modal-content">
                            <div class="modal-header">
                              <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                              <h4 class="modal-title" id="myModalLabel">Update Profile Picture</h4>
                            </div>
                            <div class="modal-body">
                              <div class="form-group">
                                <form enctype="multipart/form-data" id="upload_form" role="form" method="POST" action="" >
                                  <input type="hidden" class="form-control" id="UserId" name="UserId" value="{{$user->Id}}">
                                  <label for="exampleInputFile">Profile Picture</label>
                                  <input type="file" id="profilepicture" name="profilepicture">
                                </form>
                              </div>
                            </div>
                            <div class="modal-footer">
                              <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                              <button type="button" class="btn btn-primary" onclick="updateprofilepicture()">Update</button>
                            </div>
                          </div>
                        </div>
                      </div>

                      <div class="box box-success">
                        <br>
                        <div class="row">
                          <div class="form-group">
                            @if ($user->Web_Path)

                              <img class="profile-user-img img-responsive img-circle" name="profileimage" id="profileimage" src="{{ url($user->Web_Path) }}" alt="User profile picture">
                            @else
                                <img class="profile-user-img img-responsive img-circle" name="profileimage" id="profileimage" src="{{ URL::to('/') ."/img/default-user.png"  }}" alt="User profile picture">
                            @endif
                          </div>
                        </div>

                        <div class="row">

                          <div class="form-group">
                            <div class="col-lg-4">
                              <label>StaffId : </label>
                              <input type="text" class="form-control" id="StaffId" name="StaffId" value="{{$user->Staff_ID}}" disabled>
                              <input type="hidden" class="form-control" id="UserId" name="UserId" value="{{$user->Id}}" disabled>
                            </div>

                            <div class="col-lg-4">
                              <label>Name : </label>
                              <input type="text" class="form-control" id="Name" name="Name" value="{{$user->Name}}">
                            </div>

                            <div class="col-lg-4">
                              <label>User Type : </label>
                              <input type="text" class="form-control" id="User_Type" name="User_Type" value="{{$user->User_Type}}" disabled>
                            </div>

                          </div>
                        </div>

                      <div class="row">
                        <div class="form-group">

                          <div class="col-lg-4">
                            <label>Company : </label>
                            <input type="text" class="form-control" id="Company" name="Company" value="{{$user->Company}}">
                          </div>

                          <div class="col-lg-4">
                            <label>Company Email : </label>
                            <input type="text" class="form-control" id="Company_Email" name="Company_Email" value="{{$user->Company_Email}}">
                          </div>

                          <div class="col-lg-4">
                            <label>Personal Email : </label>
                            <input type="text" class="form-control" id="Personal_Email" name="Personal_Email" value="{{$user->Personal_Email}}">
                          </div>

                        </div>
                      </div>

                      <div class="row">
                        <div class="form-group">
                          <div class="col-lg-4">
                            <label>Contact No 1 : </label>
                            <input type="text" class="form-control" id="Contact_No_1" name="Contact_No_1" placeholder="+0123456789" value="{{$user->Contact_No_1}}">
                          </div>

                          <div class="col-lg-4">
                            <label>Contact No 2 : </label>
                            <input type="text" class="form-control" id="Contact_No_2" name="Contact_No_2" placeholder="+0123456789" value="{{$user->Contact_No_2}}">
                          </div>

                        </div>
                      </div>

                      <div class="row">
                        <div class="form-group">

                          <div class="col-lg-4">
                            <label>Gender : </label>
                            {{-- <input type="text" class="form-control" id="Marital_Status" name="Marital_Status" value="{{$user->Marital_Status}}"> --}}
                            <select class="form-control select2" id="Gender" name="Gender" style="width: 100%;">

                                <option></option>
                                @foreach ($options as $key => $option)
                                  @if ($option->Field=="Gender")

                                    <option <?php if($user->Gender == $option->Option) echo ' selected="selected" '; ?>>{{$option->Option}}</option>

                                  @endif
                                @endforeach
                              </select>
                          </div>

                        </div>
                      </div>

                      <div class="row">
                        <div class="form-group">
                          <div class="col-lg-4">
                            <label>Nationality : </label>
                            {{-- <input type="text" class="form-control" id="Country" name="Country" value="{{$user->Country}}"> --}}
                            <select class="form-control select2" id="Nationality" name="Nationality" style="width: 100%;">

                                <option></option>
                                @foreach ($options as $key => $option)
                                  @if ($option->Field=="Nationality")

                                    <option <?php if($user->Nationality == $option->Option) echo ' selected="selected" '; ?>>{{$option->Option}}</option>

                                  @endif
                                @endforeach
                              </select>
                          </div>

                          <div class="form-group">
                            <div class="col-lg-4">
                              <label>Permanent Address : </label>
                              <textarea class="form-control" id="Permanent_Address" name="Permanent_Address">{{$user->Permanent_Address}}</textarea>
                            </div>

                            <div class="col-lg-4">
                              <label>Current Address : </label>
                              <textarea class="form-control" id="Current_Address" name="Current_Address" >{{$user->Current_Address}}</textarea>
                            </div>

                          </div>

                        </div>
                      </div>

                      <div class="row">
                        <div class="form-group">

                          {{-- <div class="col-lg-4">
                            <label>DOB : </label>
                            <input type="text" class="form-control" id="DOB" name="DOB" value="{{$user->DOB}}">
                          </div> --}}

                          <div class="col-lg-4">
                            <label>NRIC : </label>
                            <input type="text" class="form-control" id="NRIC" name="NRIC" value="{{$user->NRIC}}">
                          </div>

                          <div class="col-lg-4">
                            <label>Passport No : </label>
                            <input type="text" class="form-control" id="Passport_No" name="Passport_No" value="{{$user->Passport_No}}">
                          </div>

                        </div>
                      </div>
                    <!-- /.box-body -->
                  </div>
                </div>
              </div>


              <!-- /.tab-pane -->
              <div class="tab-pane" id="experience">
                <table id="experiencetable" class="display" cellspacing="0" width="100%" padding="30px" style="font-size: 13px;">
                    <thead>
                        {{-- prepare header search textbox --}}
                        <tr>
                          @foreach($experiences as $key=>$value)

                            @if ($key==0)

                              @foreach($value as $field=>$value)
                                  <td/>{{ $field }}</td>
                              @endforeach

                            @endif

                          @endforeach
                        </tr>
                    </thead>
                    <tbody>

                      <?php $i = 0; ?>
                      @foreach($experiences as $experience)

                        <tr id="row_{{ $i }}">
                            @foreach($experience as $key=>$value)
                              <td>
                                {{ $value }}
                              </td>
                            @endforeach
                        </tr>
                        <?php $i++; ?>
                      @endforeach

                  </tbody>
                    <tfoot></tfoot>
                </table>

              </div>
              <!-- /.tab-pane -->

              <div class="tab-pane" id="license">
                <table id="licensetable" class="display" cellspacing="0" width="100%" padding="30px" style="font-size: 13px;">
                    <thead>
                        {{-- prepare header search textbox --}}
                        <tr>
                          @foreach($licenses as $key=>$value)

                            @if ($key==0)

                              @foreach($value as $field=>$value)
                                  <td/>{{ $field }}</td>
                              @endforeach

                            @endif

                          @endforeach
                        </tr>
                    </thead>
                    <tbody>

                      <?php $i = 0; ?>
                      @foreach($licenses as $license)

                        <tr id="row_{{ $i }}">
                            @foreach($license as $key=>$value)
                              <td>
                                {{ $value }}
                              </td>
                            @endforeach
                        </tr>
                        <?php $i++; ?>
                      @endforeach

                  </tbody>
                    <tfoot></tfoot>
                </table>

              </div>

              <div class="tab-pane" id="document">
                <div class="row">
                  <div class="col-md-12">
                    <p class="text-muted">[Word Document and PDF file only]</p>
                    <br>
                    <div class="form-group">
                      <form enctype="multipart/form-data" id="upload_form2" role="form" method="POST" action="" >
                        <input type="hidden" class="form-control" id="UserId" name="UserId" value="{{$user->Id}}">
                        <input type="file" id="document" name="document" accept=".doc,.docx,.pdf">

                      </form>
                    </div>

                    <br>
                    <button type="button" class="btn btn-primary" onclick="uploaddocument()">Upload</button>

                    <br>
                    <br>

                      <div id="documentdiv">

                          @foreach ($documents as $document)

                              <div id="document{{ $document->Id }}">
                                  {{ $document->File_Name}} - [{{$document->created_at}}]
                                </a>
                                <a download="{{$document->File_Name}}" href="{{ url($document->Web_Path) }}" title="Download">
                                  <button type="button" class="btn btn-primary btn-xs">Download</button>
                                </a>
                              </div>

                          @endforeach

                    </div>

                  </div>
                </div>

              </div>



              <div class="tab-pane" id="reference">

                <table id="referencetable" class="display" cellspacing="0" width="100%" padding="30px" style="font-size: 13px;">
                    <thead>
                        {{-- prepare header search textbox --}}
                        <tr>
                          @foreach($references as $key=>$value)

                            @if ($key==0)

                              @foreach($value as $field=>$value)
                                  <td/>{{ $field }}</td>
                              @endforeach

                            @endif

                          @endforeach
                        </tr>
                    </thead>
                    <tbody>

                      <?php $i = 0; ?>
                      @foreach($references as $reference)

                        <tr id="row_{{ $i }}">
                            @foreach($reference as $key=>$value)
                              <td>
                                {{ $value }}
                              </td>
                            @endforeach
                        </tr>
                        <?php $i++; ?>
                      @endforeach

                  </tbody>
                    <tfoot></tfoot>
                </table>

              </div>

              <div class="tab-pane" id="skill">

                <table id="skilltable" class="display" cellspacing="0" width="100%" padding="30px" style="font-size: 13px;">
                    <thead>
                        {{-- prepare header search textbox --}}
                        <tr>
                          @foreach($skills as $key=>$value)

                            @if ($key==0)

                              @foreach($value as $field=>$value)
                                  <td/>{{ $field }}</td>
                              @endforeach

                            @endif

                          @endforeach
                        </tr>
                    </thead>
                    <tbody>

                      <?php $i = 0; ?>
                      @foreach($skills as $skill)

                        <tr id="row_{{ $i }}">
                            @foreach($skill as $key=>$value)
                              <td>
                                {{ $value }}
                              </td>
                            @endforeach
                        </tr>
                        <?php $i++; ?>
                      @endforeach

                  </tbody>
                    <tfoot></tfoot>
                </table>

              </div>


              <!-- /.tab-pane -->
            </div>
            <!-- /.tab-content -->
          </div>
          <!-- /.nav-tabs-custom -->
        </div>
        <!-- /.col -->
      </div>
      <!-- /.row -->
    </section>
    <!-- /.content -->
  </div>
  <!-- /.content-wrapper -->
  <footer class="main-footer">
    <div class="pull-right hidden-xs">
      <b>Version</b> 2.0.1
    </div>
    <strong>Copyright &copy; 2014-2016 <a href="http://www.softoya.com">TrackerOnTheGo</a>.</strong> All rights
    reserved.
  </footer>

  <script>
    function changepassword()
    {

      currentpassword=$('[name="CurrentPassword"]').val();
      password=$('[name="Password"]').val();
      confirmpassword=$('[name="ConfirmPassword"]').val();

      if (password!=confirmpassword)
      {
        $("#exist-alert").show();
        $("#changepasswordmessage").html("Password and Confirm Password mismatch!");
      }
      else if (password=="")
      {
        $("#exist-alert").show();
        $("#changepasswordmessage").html("Password cannot be empty!");
      }
      else if (checkPasswordComplexity(password)!=true)
      {
        $("#exist-alert").show();
        $("#changepasswordmessage").html(checkPasswordComplexity(password));
      }
      else {
        $("#exist-alert").hide();

        $.ajaxSetup({
           headers: { 'X-CSRF-Token' : $('meta[name=_token]').attr('content') }
        });

        $.ajax({
                    url: "{{ url('/user/changepassword2') }}",
                    method: "POST",
                    data: {UserId:{{$me->UserId}},
                    Password:password,
                    CurrentPassword:currentpassword},
                    success: function(response){
                      if (response==1)
                      {
                        var message="Password Changed!";

                        $('#ChangePassword').modal('hide')
                        $("#update-alert ul").html(message);
                        $("#update-alert").modal('show');

                      }
                      else {
                        $("#exist-alert").show();
                        $("#changepasswordmessage").html("Your current password is incorrect!");
                      }



            }
        });
      }

    }

    function checkPasswordComplexity(password) {

      errors="";
      var pattern = /[!@#$%^&*()_+\-=\[\]{};':"\\|,.<>\/?]/;
      if (password.length < 8) {
          errors=errors +"Your password must be at least 8 characters.<br>";
      }
      if (password.search(/[a-zA-Z]/i) < 0) {
          errors=errors +"Your password must contain at least one letter.<br>";
      }
      if (password.search(/[0-9]/) < 0) {
          errors=errors +"Your password must contain at least one digit.<br>";
      }
      if (!pattern.test(password)){
          errors=errors +"Your password must contain at least one symbol.<br>";
      }
      if (errors.length == 0) {
          return true;
      }

      return errors;
  }

  function updateprofile() {

      $.ajaxSetup({
         headers: { 'X-CSRF-Token' : $('meta[name=_token]').attr('content') }
      });

      userid=$('[name="UserId"]').val();
      name=$('[name="Name"]').val();
      company=$('[name="Company"]').val();
      companyemail=$('[name="Company_Email"]').val();
      personalemail=$('[name="Personal_Email"]').val();
      contactno1=$('[name="Contact_No_1"]').val();
      contactno2=$('[name="Contact_No_2"]').val();
      nationality=$('[name="Nationality"]').val();
      gender=$('[name="Gender"]').val();
      maritalstatus=$('[name="Marital_Status"]').val();
      permanentaddress=$('[name="Permanent_Address"]').val();
      currentaddress=$('[name="Current_Address"]').val();

      dob=$('[name="DOB"]').val();
      nric=$('[name="NRIC"]').val();
      passportno=$('[name="Passport_No"]').val();
      department=$('[name="Department"]').val();
      position=$('[name="Position"]').val();
      emergencycontactperson=$('[name="Emergency_Contact_Person"]').val();
      emergencycontactrelationship=$('[name="Emergency_Contact_Relationship"]').val();
      emergencycontactno=$('[name="Emergency_Contact_No"]').val();
      emergencycontactaddress=$('[name="Emergency_Contact_Address"]').val();

      $.ajax({
                  url: "{{ url('/contractor/updateprofile') }}",
                  method: "POST",
                  data: {
                    UserId:userid,
                    Name:name,
                    Company:company,
                    Company_Email:companyemail,
                    Personal_Email:personalemail,
                    Contact_No_1:contactno1,
                    Contact_No_2:contactno2,
                    Nationality:nationality,
                    Gender:gender,
                    Permanent_Address:permanentaddress,
                    Current_Address:currentaddress,
                    NRIC:nric,
                    Passport_No:passportno
                  },

                  success: function(response){
                    if (response==0)
                    {
                      $('#UpdateProfile').modal('hide')

                      var message ="No update on profile!";
                      $("#warning-alert ul").html(message);
                      $("#warning-alert").modal('show');




                    }
                    else {

                      $("#Template option:selected").val(response).change();
                      //$("#Template").val(response).change();
                      $("#exist-alert").hide();
                      $('#UpdateProfile').modal('hide')

                      var message ="Profile updated!";
                      $("#update-alert ul").html(message);
                      $("#update-alert").modal('show');


                    }

          }
      });

  }

  function updateprofilepicture() {

      $.ajaxSetup({
         headers: { 'X-CSRF-Token' : $('meta[name=_token]').attr('content') }
      });

      $.ajax({

                  url: "{{ url('/user/updateprofilepicture') }}",
                  method: "POST",
                  contentType: false,
                  processData: false,
                  data:new FormData($("#upload_form")[0]),

                  success: function(response){
                    if (response==0)
                    {

                      $('#UpdateProfilePicture').modal('hide')

                      var message ="No update on profile picture!";
                      $("#warning-alert ul").html(message);
                      $("#warning-alert").modal('show');


                    }
                    else {


                      $("#Template option:selected").val(response).change();
                      //$("#Template").val(response).change();
                      $("#exist-alert").hide();
                      $('#UpdateProfilePicture').modal('hide')

                      $('#profileimage').attr('src',response);
                      $("#profilepicture").val("");

                      var message ="Profile picture updated!";
                      $("#update-alert ul").html(message);
                      $("#update-alert").modal('show');

                    }

          }
      });

  }

  $(function () {

    //Initialize Select2 Elements
    $(".select2").select2();

    //Date picker
    $('#DOB').datepicker({
      format: 'dd-M-yyyy',
      autoclose: true
    });

  });

  function uploaddocument() {
      $.ajaxSetup({
         headers: { 'X-CSRF-Token' : $('meta[name=_token]').attr('content') }
      });

      $.ajax({
                  url: "{{ url('/contractor/uploaddocument') }}",
                  method: "POST",
                  contentType: false,
                  processData: false,
                  data:new FormData($("#upload_form2")[0]),
                  success: function(response){
                    if (response==0)
                    {
                      var message ="Failed to upload document!";
                      $("#warning-alert ul").html(message);
                      $("#warning-alert").modal('show');

                    }
                    else {


                      //$("#Template").val(response).change();
                      $("#exist-alert").hide();
                      $("#document").val("");

                      var sub=response.split("|");

                      var html="<div id='document"+sub[0]+"'>";
                      html+=sub[2];
                      html+="</a>";
                      html+="<a download='"+sub[2]+"' href="+sub[1]+"' title='Download'>";
                      html+="<button type='button' class='btn btn-primary btn-xs'>Download</button>";
                      html+="</a>";
                      html+="</div>";


                      $("#documentdiv").append(html);

                      var message ="Document uploaded!";
                      $("#update-alert ul").html(message);
                      $("#update-alert").modal('show');

                    }
          }
      });
  }

  </script>

@endsection
