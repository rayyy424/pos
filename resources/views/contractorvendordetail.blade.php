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
          var asInitVals = new Array();

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
                                                 label: "Relationship:",
                                                 name: "references.Relationship"
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
                                   { data: "references.Relationship"},
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
        Contractor/Vendor Detail
        <small>Resource Mangement</small>
      </h1>
      <ol class="breadcrumb">
        <li><a href="#"><i class="fa fa-dashboard"></i> Home</a></li>
        <li><a href="#">Resource Management</a></li>
        <li><a href="{{ url('/contractorvendor') }}">Contractor/Vendor Profile</a></li>
        <li class="active">{{ $contractorvendor->Name }}</li>
      </ol>
    </section>

    <!-- Main content -->
    <section class="content">
      <div class="row">
        <div class="col-md-3">

          <!-- Profile Image -->
          <div class="box box-primary">
            <div class="box-body box-profile">
              @if ($contractorvendor->Web_Path)
                <img class="profile-user-img img-responsive img-circle" src="{{ $contractorvendor->Web_Path }}" alt="User profile picture">
              @else
                  <img class="profile-user-img img-responsive img-circle" src="{{ URL::to('/') ."/img/default-user.png"  }}" alt="User profile picture">
              @endif
              <h3 class="profile-username text-center">{{ $contractorvendor->Name }}</h3>

              <ul class="list-group list-group-unbordered">

                @foreach ($contractorvendor as $key => $value)

                    @if ($key!="Id" && $key!="Name" && $key!="Position" && $key!="Web_Path")
                      <li class="list-group-item">
                        <b>{{$key}}</b><br><div class="italicfont">{{ $value }}</div>
                      </li>
                    @endif


                @endforeach
              </ul>

              {{-- <a href="#" class="btn btn-primary btn-block"><b>Update</b></a> --}}
            </div>
            <!-- /.box-body -->
          </div>
          <!-- /.box -->

        </div>
        <!-- /.col -->
        <div class="col-md-9">
          <div class="nav-tabs-custom">
            <ul class="nav nav-tabs">
              {{-- <li><a href="#company" data-toggle="tab">Company</a></li>
              <li><a href="#document" data-toggle="tab">Document</a></li>
              <li><a href="#evaluation" data-toggle="tab">Evaluation</a></li> --}}
              <li class="active"><a href="#experience" data-toggle="tab">Experience</a></li>
              <li><a href="#license" data-toggle="tab">License</a></li>
              <li><a href="#reference" data-toggle="tab">Reference</a></li>
              <li><a href="#skill" data-toggle="tab">Skill</a></li>
            </ul>
            <div class="tab-content">
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
      <b>Version</b> 1.0.0
    </div>
    <strong>Copyright &copy; 2014-2016 <a href="http://www.softoya.com">TrackerOnTheGo</a>.</strong> All rights
    reserved.
  </footer>

@endsection
