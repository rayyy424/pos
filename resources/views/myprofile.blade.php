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

      .changed{
        background-color: rgb(123, 197, 237);
      }
      .table-bordered>tbody>tr>th{
        background-color: #dedcdc;
      }



      .has-error{
        font-style: italic;
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
          var employmentseditor; // use a global for the submit and return data rendering in the examples
          var licenseseditor;
          var qualificationseditor;
          var referenceseditor;
          var skillseditor;
          var trainingseditor;
          var certificateseditor;
          var salaryeditor;
          var revieweditor;
          var familyeditor;
          var familyeditor1;
          var languageeditor;

          var asInitVals = new Array();
          var employmentid;
          var experienceid; // use a global for the submit and return data rendering in the examples
          var licenseid;
          var qualificationid;
          var referenceid;
          var skillid;
          var trainingid;
          var certificateid;
          var employmenttable;
          var experiencetable; // use a global for the submit and return data rendering in the examples
          var licensetable;
          var qualificationtable;
          var referencetable;
          var skilltable;
          var trainingtable;
          var certificatetable;
          var salarytable;
          var reviewtable;
          var familytable;
          var familytable1;
          var languagetable;
          $(document).ready(function() {
            $("#nricfield").hide();
            $("#passportfield").hide();
            $("#unionnofield").hide();

            $("#nricfield1").hide();
            $("#passportfield1").hide();
            $("#unionnofield1").hide();

                         employmentseditor = new $.fn.dataTable.Editor( {
                                ajax: "{{ asset('/Include/employmenthistory.php') }}",
                                 table: "#employmenttable",
                                 idSrc: "employmenthistories.Id",
                                 fields: [
                                         {
                                                 label: "UserId:",
                                                 name: "employmenthistories.UserId",
                                                 type: "hidden",
                                                 default: {{ $UserId }}
                                         }, {
                                                 label: "Company:",
                                                 name: "employmenthistories.Company",
                                                 attr:  {
                                                    placeholder: 'ABC SDN BHD'
                                                }
                                         },{
                                                 label: "Company Address:",
                                                 name: "employmenthistories.Company_Address",
                                                 type:"textarea",
                                                 attr:  {
                                                    placeholder: '123, Jalan 123'
                                                }
                                         },{
                                                 label: "Company Contact No:",
                                                 name: "employmenthistories.Company_Contact_No",
                                                 attr:  {
                                                    placeholder: '+60123456789'
                                                }
                                         }, {
                                                 label: "Start_Date:",
                                                 name: "employmenthistories.Start_Date",
                                                 type:   'datetime',
                                                 def:    function () { return new Date(); },
                                                 format: 'DD-MMM-YYYY',
                                                 opts:  {
                                                    firstDay: 0

                                                },
                                                 attr:  {
                                                    placeholder: '2017'
                                                }
                                         }, {
                                                 label: "End_Date:",
                                                 name: "employmenthistories.End_Date",
                                                 type:   'datetime',
                                                 def:    function () { return new Date(); },
                                                 format: 'DD-MMM-YYYY',
                                                 opts:  {
                                                    firstDay: 0
                                                },
                                                 attr:  {
                                                    placeholder: '2017'
                                                }
                                         }, {
                                                 label: "Position:",
                                                 name: "employmenthistories.Position",
                                                 attr:  {
                                                    placeholder: 'Engineer'
                                                }
                                         }, {
                                                 label: "Supervisor:",
                                                 name: "employmenthistories.Supervisor",
                                                 attr:  {
                                                    placeholder: 'John Doe'
                                                }
                                         }, {
                                                 label: "Remarks:",
                                                 name: "employmenthistories.Remarks",
                                                 type:"textarea",
                                                 attr:  {
                                                    placeholder: 'Write your remarks here.'
                                                }

                                         }, {
                                                 label: "File:",
                                                 name: "files.Web_Path",
                                                 type: "upload",
                                                 ajaxData: function ( d ) {
                                                   d.append( 'Id', employmentid ); // edit - use `d`
                                                 },
                                                 display: function ( file_id ) {
                                                   return '<img src="'+ employmenttable.api().row( employmentseditor.modifier() ).data().files.Web_Path +'">';
                                                 },
                                                 clearText: "Clear",
                                                 noImageText: 'No file'
                                         }
                                 ]
                         } );
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
                                                 name: "experiences.Project",
                                                 attr:  {
                                                    placeholder: 'Huawei KV LTE1800'
                                                }
                                         },{
                                                 label: "Role:",
                                                 name: "experiences.Role",
                                                 attr:  {
                                                    placeholder: 'Tuning Engineer'
                                                }
                                         }, {
                                                 label: "Responsibility:",
                                                 name: "experiences.Responsibility",
                                                 type:"textarea",
                                                 attr:  {
                                                    placeholder: '-Tuning report'
                                                }
                                         }, {
                                                 label: "Achievement:",
                                                 name: "experiences.Achievement",
                                                 type:"textarea",
                                                 attr:  {
                                                    placeholder: '-clear acceptance in 1 month'
                                                }
                                         }, {
                                                 label: "Start_Date:",
                                                 name: "experiences.Start_Date",
                                                 type:   'datetime',
                                                 def:    function () { return new Date(); },
                                                 format: 'MMM-YYYY',
                                                 opts:  {
                                                    firstDay: 0
                                                },
                                                 attr:  {
                                                    placeholder: '01-Jan-2017'
                                                }
                                         }, {
                                                 label: "End_Date:",
                                                 name: "experiences.End_Date",
                                                 type:   'datetime',
                                                 def:    function () { return new Date(); },
                                                 format: 'MMM-YYYY',
                                                 opts:  {
                                                    firstDay: 0
                                                },
                                                 attr:  {
                                                    placeholder: '01-Jan-2017'
                                                }
                                         }, {
                                                 label: "File:",
                                                 name: "files.Web_Path",
                                                 type: "upload",
                                                 ajaxData: function ( d ) {
                                                   d.append( 'Id', experienceid ); // edit - use `d`
                                                 },
                                                 display: function ( file_id ) {
                                                   return '<img src="'+ experiencetable.api().row( experienceseditor.modifier() ).data().files.Web_Path +'">';
                                                 },
                                                 clearText: "Clear",
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
                                                 name: "licenses.License_Type",
                                                 type: "select",
                                                 options: [
                                                     @foreach($options as $option)
                                                       @if ($option->Field=="License_Type")
                                                         { label :"{{$option->Option}}", value: "{{$option->Option}}" },
                                                       @endif
                                                     @endforeach
                                                 ],
                                                 attr:  {
                                                    placeholder: 'CIDB'
                                                }

                                         },{
                                                 label: "Identity No:",
                                                 name: "licenses.Identity_No",
                                                 attr:  {
                                                    placeholder: 'A1234567'
                                                }
                                         }, {

                                                   label: "Type:",
                                                   name: "licenses.Type",
                                                    type: "hidden",
                                           }, {
                                                 label: "Issue Date:",
                                                 name: "licenses.Issue_Date",
                                                 type:   'datetime',
                                                 def:    function () { return new Date(); },
                                                 format: 'DD-MMM-YYYY',
                                                 opts:  {
                                                    firstDay: 0
                                                },
                                                 attr:  {
                                                    placeholder: '01-Jan-2017'
                                                }
                                         }, {
                                                 label: "Expiry Date:",
                                                 name: "licenses.Expiry_Date",
                                                 type:   'datetime',
                                                 def:    function () { return new Date(); },
                                                 format: 'DD-MMM-YYYY',
                                                 opts:  {
                                                    firstDay: 0
                                                },
                                                 attr:  {
                                                    placeholder: '01-Jan-2017'
                                                }
                                         }, {
                                                 label: "License Status:",
                                                 name: "licenses.License_Status",
                                                 type: "select",
                                                 options: [
                                                     @foreach($options as $option)
                                                       @if ($option->Field=="License_Status")
                                                         { label :"{{$option->Option}}", value: "{{$option->Option}}" },
                                                       @endif
                                                     @endforeach
                                                 ],
                                                 attr:  {
                                                    placeholder: 'Active'
                                                }
                                         }, {
                                                 label: "File:",
                                                 name: "files.Web_Path",
                                                 type: "upload",
                                                 ajaxData: function ( d ) {
                                                   d.append( 'Id', licenseid ); // edit - use `d`
                                                 },
                                                 display: function ( file_id ) {
                                                   return '<img src="'+ licensetable.api().row( licenseseditor.modifier() ).data().files.Web_Path +'">';
                                                 },
                                                 clearText: "Clear",
                                                 noImageText: 'No file'
                                         }
                                 ]
                         } );
                         qualificationseditor = new $.fn.dataTable.Editor( {
                                ajax: "{{ asset('/Include/qualification.php') }}",
                                 table: "#qualificationtable",
                                 idSrc: "qualifications.Id",
                                 fields: [
                                         {
                                                 label: "UserId:",
                                                 name: "qualifications.UserId",
                                                 type: "hidden",
                                                 default: {{ $UserId }}
                                         }, {
                                                 label: "Institution:",
                                                 name: "qualifications.Institution",
                                                 attr:  {
                                                    placeholder: 'ABC College'
                                                }
                                         },{
                                                 label: "Major:",
                                                 name: "qualifications.Major",
                                                 attr:  {
                                                    placeholder: 'Software Engineering'
                                                }
                                         }, {
                                                 label: "Qualification Level:",
                                                 name: "qualifications.Qualification_Level",
                                                 type: "select",
                                                 options: [
                                                     @foreach($options as $option)
                                                       @if ($option->Field=="Qualification_Level")
                                                         { label :"{{$option->Option}}", value: "{{$option->Option}}" },
                                                       @endif
                                                     @endforeach
                                                 ],
                                                 attr:  {
                                                    placeholder: 'Degree'
                                                }
                                         }, {
                                                 label: "Start_Date:",
                                                 name: "qualifications.Start_Date",
                                                 type:   'datetime',
                                                 def:    function () { return new Date(); },
                                                 format: 'YYYY',
                                                 opts:  {
                                                    firstDay: 0,
                                                    minDate: new Date('2000'),
                                                },
                                                 attr:  {
                                                    placeholder: '2017'
                                                }
                                         }, {
                                                 label: "End_Date:",
                                                 name: "qualifications.End_Date",
                                                 type:   'datetime',
                                                 def:    function () { return new Date(); },
                                                 format: 'YYYY',
                                                 opts:  {
                                                    firstDay: 0
                                                },
                                                 attr:  {
                                                    placeholder: '2017'
                                                }
                                         }, {
                                                 label: "File:",
                                                 name: "files.Web_Path",
                                                 type: "upload",
                                                 ajaxData: function ( d ) {
                                                   d.append( 'Id', qualificationid ); // edit - use `d`
                                                 },
                                                 display: function ( file_id ) {
                                                   return '<img src="'+ qualificationtable.api().row( qualificationseditor.modifier() ).data().files.Web_Path +'">';
                                                 },
                                                 clearText: "Clear",
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
                                                 name: "references.Reference",
                                                 attr:  {
                                                    placeholder: 'MR ABC'
                                                }
                                         },{
                                                 label: "Contact No:",
                                                 name: "references.Contact_No",
                                                 attr:  {
                                                    placeholder: '+60123456789'
                                                }
                                         }, {
                                                 label: "Company:",
                                                 name: "references.Company",
                                                 attr:  {
                                                    placeholder: 'ABC SDN BHD'
                                                }
                                         }, {
                                                 label: "Position:",
                                                 name: "references.Position",
                                                 attr:  {
                                                    placeholder: 'Manager'
                                                }
                                         }, {
                                                 label: "File:",
                                                 name: "files.Web_Path",
                                                 type: "upload",
                                                 ajaxData: function ( d ) {
                                                   d.append( 'Id', referenceid ); // edit - use `d`
                                                 },
                                                 display: function ( file_id ) {
                                                   return '<img src="'+ referencetable.api().row( referenceseditor.modifier() ).data().files.Web_Path +'">';
                                                 },
                                                 clearText: "Clear",
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
                                                 name: "skills.Skill",
                                                 attr:  {
                                                    placeholder: 'Programming, Nemo Outdoor, WCDMA'
                                                }
                                         },{
                                                 label: "Level:",
                                                 name: "skills.Level",
                                                 type: "select",
                                                 options: [
                                                     @foreach($options as $option)
                                                       @if ($option->Field=="Level")
                                                         { label :"{{$option->Option}}", value: "{{$option->Option}}" },
                                                       @endif
                                                     @endforeach
                                                 ],
                                                 attr:  {
                                                    placeholder: 'Intermediate'
                                                }
                                         }, {
                                                 label: "Description:",
                                                 name: "skills.Description",
                                                 type:"textarea",
                                                 attr:  {
                                                    placeholder: 'Write your description here.'
                                                }
                                         }, {
                                                 label: "File:",
                                                 name: "files.Web_Path",
                                                 type: "upload",
                                                 ajaxData: function ( d ) {
                                                   d.append( 'Id', skillid ); // edit - use `d`
                                                 },
                                                 display: function ( file_id ) {
                                                   return '<img src="'+ skilltable.api().row( skillseditor.modifier() ).data().files.Web_Path +'">';
                                                 },
                                                 clearText: "Clear",
                                                 noImageText: 'No file'
                                         }
                                 ]
                         } );
                         trainingseditor = new $.fn.dataTable.Editor( {
                                ajax: "{{ asset('/Include/training.php') }}",
                                 table: "#trainingtable",
                                 idSrc: "trainings.Id",
                                 fields: [
                                         {
                                                 label: "UserId:",
                                                 name: "trainings.UserId",
                                                 type: "hidden",
                                                 default: {{ $UserId }}
                                         }, {
                                                 label: "Training:",
                                                 name: "trainings.Training",
                                                 attr:  {
                                                    placeholder: 'Huawei NodeB Configuration Training'
                                                }
                                         },{
                                                 label: "Description:",
                                                 name: "trainings.Description",
                                                 type:"textarea",
                                                 attr:  {
                                                    placeholder: 'Write your description here.'
                                                }
                                         }, {
                                                 label: "Organizer:",
                                                 name: "trainings.Organizer",
                                                 attr:  {
                                                    placeholder: 'Huawei Training Centre'
                                                }
                                         }, {
                                                 label: "Training Date:",
                                                 name: "trainings.Training_Date",
                                                 type:   'datetime',
                                                 def:    function () { return new Date(); },
                                                 format: 'DD-MMM-YYYY',
                                                 opts:  {
                                                    firstDay: 0
                                                },
                                                 attr:  {
                                                    placeholder: '01-Jan-2017'
                                                }
                                         }, {
                                                 label: "File:",
                                                 name: "files.Web_Path",
                                                 type: "upload",
                                                 ajaxData: function ( d ) {
                                                   d.append( 'Id', trainingid ); // edit - use `d`
                                                 },
                                                 display: function ( file_id ) {
                                                   return '<img src="'+ trainingtable.api().row( trainingseditor.modifier() ).data().files.Web_Path +'">';
                                                 },
                                                 clearText: "Clear",
                                                 noImageText: 'No file'
                                         }
                                 ]
                         } );
                         certificateseditor = new $.fn.dataTable.Editor( {
                                ajax: "{{ asset('/Include/certificate.php') }}",
                                 table: "#certificatetable",
                                 idSrc: "certificates.Id",
                                 fields: [
                                         {
                                                 label: "UserId:",
                                                 name: "certificates.UserId",
                                                 type: "hidden",
                                                 default: {{ $UserId }}
                                         }, {
                                                 label: "Certificate:",
                                                 name: "certificates.Certificate",
                                                 attr:  {
                                                    placeholder: 'CCNA'
                                                }
                                         },{
                                                 label: "Certificate Date:",
                                                 name: "certificates.Certificate_Date",
                                                 type:   'datetime',
                                                 def:    function () { return new Date(); },
                                                 format: 'DD-MMM-YYYY',
                                                 opts:  {
                                                    firstDay: 0
                                                },
                                                 attr:  {
                                                    placeholder: '01-Jan-2017'
                                                }
                                         }, {
                                                 label: "Valid Until:",
                                                 name: "certificates.Valid_Until",
                                                 type:   'datetime',
                                                 def:    function () { return new Date(); },
                                                 format: 'DD-MMM-YYYY',
                                                 opts:  {
                                                    firstDay: 0
                                                },
                                                 attr:  {
                                                    placeholder: '01-Jan-2017'
                                                }
                                         }, {
                                                 label: "Description:",
                                                 name: "certificates.Description",
                                                 type: "textarea"
                                         }, {
                                                 label: "File:",
                                                 name: "files.Web_Path",
                                                 type: "upload",
                                                 ajaxData: function ( d ) {
                                                   d.append( 'Id', certificateid ); // edit - use `d`
                                                 },
                                                 display: function ( file_id ) {
                                                   return '<img src="'+ certificatetable.api().row( certificateseditor.modifier() ).data().files.Web_Path +'">';
                                                 },
                                                 clearText: "Clear",
                                                 noImageText: 'No file'
                                         }
                                 ]
                         } );
                         salaryeditor = new $.fn.dataTable.Editor( {
                                ajax: "{{ asset('/Include/salary.php') }}",
                                 table: "#salarytable",
                                 idSrc: "salary.Id",
                                 fields: [
                                         {
                                                 label: "UserId:",
                                                 name: "salary.UserId",
                                                 type: "hidden",
                                                 default: {{ $UserId }}
                                         }, {
                                                 label: "Salary:",
                                                 name: "salary.Salary",
                                                 attr:  {
                                                    placeholder: ''
                                                }
                                         },{
                                                 label: "Remarks:",
                                                 name: "salary.Remarks",
                                                 type:"textarea",
                                                 attr:  {
                                                    placeholder: ''
                                                }
                                         },{
                                                 label: "Created By:",
                                                 name: "salary.Created_By",
                                                 attr:  {
                                                    placeholder: ''
                                                }
                                         }
                                 ]
                         } );
                         revieweditor = new $.fn.dataTable.Editor( {
                                ajax: "{{ asset('/Include/reviews.php') }}",
                                 table: "#reviewtable",
                                 idSrc: "reviews.Id",
                                 fields: [
                                         {
                                                 label: "UserId:",
                                                 name: "reviews.UserId",
                                                 type: "hidden",
                                                 default: {{ $UserId }}
                                         }, {
                                                 label: "Status:",
                                                 name: "reviews.Status",
                                                 attr:  {
                                                    placeholder: ''
                                                }
                                         },{
                                                 label: "Remarks:",
                                                 name: "reviews.Remarks",
                                                 type:"textarea",
                                                 attr:  {
                                                    placeholder: ''
                                                }
                                         },{
                                                 label: "File:",
                                                 name: "files.Web_Path",
                                                 type: "upload",
                                                 ajaxData: function ( d ) {
                                                   d.append( 'Id', certificateid ); // edit - use `d`
                                                 },
                                                 display: function ( file_id ) {
                                                     return '<a href="'+ reviews.api().row( revieweditor.modifier() ).data().files.Web_Path +'">Download</>';
                                                 },
                                                 noImageText: 'No file'
                                         }
                                 ]
                         } );
                         familyeditor = new $.fn.dataTable.Editor( {
                                ajax: "{{ asset('/Include/family.php') }}",
                                 table: "#familytable",
                                 idSrc: "family.Id",
                                 fields: [
                                         {
                                                 label: "UserId:",
                                                 name: "family.UserId",
                                                 type: "hidden",
                                                 default: {{ $UserId }}
                                         }, {
                                                 label: "Name:",
                                                 name: "family.Name",
                                                 attr:  {
                                                    placeholder: 'John Doe'
                                                }
                                         },{
                                                 label: "NRIC:",
                                                 name: "family.NRIC",
                                                 attr:  {
                                                    placeholder: '123456-12-1234'
                                                }
                                        },{
                                                label: "Gender:",
                                                name: "family.Gender",
                                                type: "select",
                                                options: [
                                                  { label :"Female", value: "Female" },
                                                  { label :"Male", value: "Male" },

                                                ],
                                                attr:  {
                                                   placeholder: ''
                                               }
                                       },{
                                               label: "Age:",
                                               name: "family.Age",
                                               attr:  {
                                                  placeholder: '45'
                                              }
                                       },{

                                                 label: "Relationship:",
                                                 name: "family.Relationship",
                                                 type: "select",
                                                 options: [
                                                   { label :"Father", value: "Father" },
                                                   { label :"Mother", value: "Mother" },
                                                   { label :"Husband", value: "Husband" },
                                                   { label :"Wife", value: "Wife" },
                                                   { label :"Son", value: "Son" },
                                                   { label :"Daughter", value: "Daughter" },
                                                   { label :"Sibling 1", value: "Sibling 1" },
                                                   { label :"Sibling 2", value: "Sibling 2" },
                                                   { label :"Sibling 3", value: "Sibling 3" },

                                                 ],
                                                 attr:  {
                                                    placeholder: ''
                                                }
                                         }, {
                                                 label: "Occupation:",
                                                 name: "family.Occupation",
                                                 attr:  {
                                                    placeholder: 'Doctor'
                                                }
                                         }, {
                                                 label: "Company/School Name:",
                                                 name: "family.Company_School_Name",
                                                 attr:  {
                                                    placeholder: 'ABC SDN BHD'
                                                }
                                        }, {
                                                label: "Contact_No:",
                                                name: "family.Contact_No",
                                                attr:  {
                                                   placeholder: '+6016983363'
                                               }
                                        }
                                 ]
                         } );
                         familyeditor1 = new $.fn.dataTable.Editor( {
                                ajax: "{{ asset('/Include/family.php') }}",
                                 table: "#familytable",
                                 idSrc: "family.Id",
                                 fields: [
                                         {
                                                 label: "UserId:",
                                                 name: "family.UserId",
                                                 type: "hidden",
                                                 default: {{ $UserId }}
                                         }, {
                                                 label: "Name:",
                                                 name: "family.Name",
                                                 attr:  {
                                                    placeholder: 'John Doe'
                                                }
                                         },{
                                                 label: "NRIC:",
                                                 name: "family.NRIC",
                                                 attr:  {
                                                    placeholder: '123456-12-1234'
                                                }
                                        },{
                                                label: "Gender:",
                                                name: "family.Gender",
                                                type: "select",
                                                options: [
                                                  { label :"Female", value: "Female" },
                                                  { label :"Male", value: "Male" },

                                                ],
                                                attr:  {
                                                   placeholder: ''
                                               }
                                       },{
                                               label: "Age:",
                                               name: "family.Age",
                                               attr:  {
                                                  placeholder: '45'
                                              }
                                       },{

                                                 label: "Relationship:",
                                                 name: "family.Relationship",
                                                 type: "select",
                                                 options: [
                                                   { label :"Father", value: "Father" },
                                                   { label :"Mother", value: "Mother" },
                                                   { label :"Wife", value: "Wife" },
                                                   { label :"Son", value: "Son" },
                                                   { label :"Daughter", value: "Daughter" },
                                                   { label :"Sibling 1", value: "Sibling 1" },
                                                   { label :"Sibling 2", value: "Sibling 2" },
                                                   { label :"Sibling 3", value: "Sibling 3" },

                                                 ],
                                                 attr:  {
                                                    placeholder: ''
                                                }
                                         }, {
                                                 label: "Occupation:",
                                                 name: "family.Occupation",
                                                 attr:  {
                                                    placeholder: 'Doctor'
                                                }
                                         }, {
                                                 label: "Company/School Name:",
                                                 name: "family.Company_School_Name",
                                                 attr:  {
                                                    placeholder: 'Selayang Hospital'
                                                }
                                        }, {
                                                label: "Contact_No:",
                                                name: "family.Contact_No",
                                                attr:  {
                                                   placeholder: '+6016983363'
                                               }
                                        }
                                 ]
                         } );
                         languageeditor = new $.fn.dataTable.Editor( {
                                ajax: "{{ asset('/Include/language.php') }}",
                                 table: "#languagetable",
                                 idSrc: "languages.Id",
                                 fields: [
                                         {
                                                 label: "UserId:",
                                                 name: "languages.UserId",
                                                 type: "hidden",
                                                 default: {{ $UserId }}
                                         }, {
                                                 label: "Language:",
                                                 name: "languages.Language",
                                                 type: "select",
                                                 options: [
                                                     @foreach($options as $option)
                                                       @if ($option->Field=="Language")
                                                         { label :"{{$option->Option}}", value: "{{$option->Option}}" },
                                                       @endif
                                                     @endforeach
                                                 ],
                                                 attr:  {
                                                    placeholder: 'English'
                                                }

                                         },{
                                                 label: "Speak:",
                                                 name: "languages.Speak",
                                                 attr:  {
                                                    placeholder: '9',
                                                  type: 'number'
                                                }
                                         },{
                                                 label: "Written:",
                                                 name: "languages.Written",
                                                 attr:  {
                                                    placeholder: '9',
                                                  type: 'number'
                                                }
                                         }
                                 ]
                         } );


                         employmenttable=$('#employmenttable').dataTable( {
                                // keys: {
                                //      columns: ':not(:first-child)',
                                //      editor:  employmentseditor   //THIS LINE FIXED THE PROBLEM
                                //  },
                                 columnDefs: [{ "visible": false, "targets": [1] },{"className": "dt-center", "targets": "_all"}],
                                 responsive: false,
                                 colReorder: false,
                                 dom: "Brt",
                                 bAutoWidth: true,
                                 sScrollX: "100%",
                                 sScrollY: "100%",
                                 bScrollCollapse: true,

                                 columns: [
                                         { data: null,"render":"", title:"No"},
                                         { data: "employmenthistories.Id",title: "Id"},
                                         { data: "employmenthistories.Company",title: "Company" },
                                         { data: "employmenthistories.Company_Address",title: "Company_Address" },
                                         { data: "employmenthistories.Company_Contact_No",title: "Company_Contact_No" },
                                         { data: "employmenthistories.Start_Date",title: "Start_Date"},
                                         { data: "employmenthistories.End_Date",title: "End_Date"},
                                         { data: "employmenthistories.Position",title: "Position"},
                                         { data: "employmenthistories.Supervisor",title: "Supervisor"},
                                         { data: "employmenthistories.Remarks",title: "Remarks"},
                                         { data: "files.Web_Path",
                                            render: function ( url, type, row ) {
                                                 if (url)
                                                 {
                                                   return '<a href="'+ url +'" target="_blank">Download</a>';
                                                 }
                                                 else {
                                                   return ' - ';
                                                 }
                                             },
                                             title: "File"
                                           }
                                 ],
                                 autoFill: {
                                         //columns: ':not(:first-child)',
                                          editor:  employmentseditor
                                 },
                                 select: {
                                         style:    'os',
                                         selector: 'tr'
                                 },
                                 buttons: [
                                         { extend: "create", editor: employmentseditor },
                                         { extend: "edit",   editor: employmentseditor },
                                         { extend: "remove", editor: employmentseditor }
                                 ],
                     });

                     $('#employmenttable').on( 'click', 'tr', function () {
                       // Get the rows id value
                      //  var row=$(this).closest("tr");
                      //  var oTable = row.closest('table').dataTable();
                       employmentid = employmenttable.api().row( this ).data().employmenthistories.Id;
                     });
                     experiencetable=$('#experiencetable').dataTable( {
                            // keys: {
                            //      columns: ':not(:first-child)',
                            //      editor:  experienceseditor   //THIS LINE FIXED THE PROBLEM
                            //  },
                             columnDefs: [{ "visible": false, "targets": [1] },{"className": "dt-center", "targets": "_all"}],
                             responsive: false,
                             colReorder: false,
                             dom: "Brt",
                             bAutoWidth: true,
                             "bScrollCollapse": true,
                             columns: [
                                     { data: null,"render":"", title:"No"},
                                     { data: "experiences.Id",title: "Id"},
                                     { data: "experiences.Project",title: "Project" },
                                     { data: "experiences.Role",title: "Role" },
                                     { data: "experiences.Responsibility",title: "Responsibility" },
                                     { data: "experiences.Achievement",title: "Achievement" },
                                     { data: "experiences.Start_Date",title: "Start_Date"},
                                     { data: "experiences.End_Date",title: "End_Date"},
                                     { data: "files.Web_Path",
                                        render: function ( url, type, row ) {
                                             if (url)
                                             {
                                               return '<a href="'+ url +'" target="_blank">Download</a>';
                                             }
                                             else {
                                               return ' - ';
                                             }
                                         },
                                         title: "File"
                                       }
                             ],
                             autoFill: {
                                    // columns: ':not(:first-child)',
                                      editor:  experienceseditor
                             },
                             select: {
                                     style:    'os',
                                     selector: 'tr'
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
                         columnDefs: [{ "visible": false, "targets": [1] },{"className": "dt-center", "targets": "_all"}],
                         responsive: false,
                         colReorder: false,
                         dom: "Brt",
                         bAutoWidth: true,
                         "bScrollCollapse": true,
                         columns: [
                                 { data: null,"render":"", title:"No"},
                                 { data: "licenses.Id",title: "Id"},
                                 { data: "licenses.License_Type",title: "License_Type" },
                                 { data: "licenses.Identity_No",title: "Identity_No" },
                                 { data: "licenses.Issue_Date",title: "Issue_Date" },
                                 { data: "licenses.Expiry_Date" ,title: "Expiry_Date"},
                                 { data: "licenses.License_Status" ,title: "License_Status"},
                                 { data: "files.Web_Path",
                                    render: function ( url, type, row ) {
                                         if (url)
                                         {
                                           return '<a href="'+ url +'" target="_blank">Download</a>';
                                         }
                                         else {
                                           return ' - ';
                                         }
                                     },
                                     title: "File"
                                   }
                         ],
                         autoFill: {
                                 //columns: ':not(:first-child)',
                                  editor:  licenseseditor
                         },
                         select: {
                                 style:    'os',
                                 selector: 'tr'
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
                    qualificationtable=$('#qualificationtable').dataTable( {
                        //  keys: {
                        //       columns: ':not(:first-child)',
                        //       editor:  qualificationseditor   //THIS LINE FIXED THE PROBLEM
                        //   },
                          columnDefs: [{ "visible": false, "targets": [1] },{"className": "dt-center", "targets": "_all"}],
                          responsive: false,
                          colReorder: false,
                          dom: "Brt",
                          bAutoWidth: true,
                          "bScrollCollapse": true,
                          columns: [
                                  { data: null,"render":"", title:"No"},
                                  { data: "qualifications.Id",title: "Id"},
                                  { data: "qualifications.Institution" ,title: "Institution"},
                                  { data: "qualifications.Major",title: "Major" },
                                  { data: "qualifications.Qualification_Level",title: "Qualification_Level" },
                                  { data: "qualifications.Start_Date",title: "Start_Date"},
                                  { data: "qualifications.End_Date",title: "End_Date"},
                                  { data: "files.Web_Path",
                                     render: function ( url, type, row ) {
                                          if (url)
                                          {
                                            return '<a href="'+ url +'" target="_blank">Download</a>';
                                          }
                                          else {
                                            return ' - ';
                                          }
                                      },
                                      title: "File"
                                    }
                          ],
                          autoFill: {
                                  //columns: ':not(:first-child)',
                                   editor:  qualificationseditor
                          },
                          select: {
                                  style:    'os',
                                  selector: 'tr'
                          },
                          buttons: [
                                  { extend: "create", editor: qualificationseditor },
                                  { extend: "edit",   editor: qualificationseditor },
                                  { extend: "remove", editor: qualificationseditor }
                          ],
                   });
                   $('#qualificationtable').on( 'click', 'tr', function () {
                     // Get the rows id value
                    //  var row=$(this).closest("tr");
                    //  var oTable = row.closest('table').dataTable();
                     qualificationid = qualificationtable.api().row( this ).data().qualifications.Id;
                   });
                    referencetable=$('#referencetable').dataTable( {
                          // keys: {
                          //      columns: ':not(:first-child)',
                          //      editor:  referenceseditor   //THIS LINE FIXED THE PROBLEM
                          //  },
                           columnDefs: [{ "visible": false, "targets": [1] },{"className": "dt-center", "targets": "_all"}],
                           responsive: false,
                           colReorder: false,
                           dom: "Brt",
                           bAutoWidth: true,
                           "bScrollCollapse": true,
                           columns: [
                                   { data: null,"render":"", title:"No"},
                                   { data: "references.Id",title: "Id"},
                                   { data: "references.Reference",title: "Reference" },
                                   { data: "references.Contact_No",title: "Contact_No" },
                                   { data: "references.Company",title: "Company" },
                                   { data: "references.Position",title: "Position" },
                                   { data: "files.Web_Path",
                                      render: function ( url, type, row ) {
                                           if (url)
                                           {
                                             return '<a href="'+ url +'" target="_blank">Download</a>';
                                           }
                                           else {
                                             return ' - ';
                                           }
                                       },
                                       title: "File"
                                     }
                           ],
                           autoFill: {
                                   //columns: ':not(:first-child)',
                                    editor:  referenceseditor
                           },
                           select: {
                                   style:    'os',
                                   selector: 'tr'
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
                            columnDefs: [{ "visible": false, "targets": [1] },{"className": "dt-center", "targets": "_all"}],
                            responsive: false,
                            colReorder: false,
                            dom: "Brt",
                            bAutoWidth: true,
                            "bScrollCollapse": true,
                            columns: [
                                    { data: null,"render":"", title:"No"},
                                    { data: "skills.Id",title: "Id" },
                                    { data: "skills.Skill" ,title: "Skill" },
                                    { data: "skills.Level",title: "Level"  },
                                    { data: "skills.Description" ,title: "Description" },
                                    { data: "files.Web_Path",
                                       render: function ( url, type, row ) {
                                            if (url)
                                            {
                                              return '<a href="'+ url +'" target="_blank">Download</a>';
                                            }
                                            else {
                                              return ' - ';
                                            }
                                        },
                                        title: "File"
                                      }
                            ],
                            autoFill: {
                                    //columns: ':not(:first-child)',
                                     editor:  skillseditor
                            },
                            select: {
                                    style:    'os',
                                    selector: 'tr'
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

                      trainingtable=$('#trainingtable').dataTable( {
                            // keys: {
                            //      columns: ':not(:first-child)',
                            //      editor:  trainingseditor   //THIS LINE FIXED THE PROBLEM
                            //  },
                             columnDefs: [{ "visible": false, "targets": [1] },{"className": "dt-center", "targets": "_all"}],
                             responsive: false,
                             colReorder: false,
                             dom: "Brt",
                             bAutoWidth: true,
                             bScrollCollapse: true,
                             columns: [
                                     { data: null,"render":"", title:"No"},
                                     { data: "trainings.Id",title: "Id"},
                                     { data: "trainings.Training",title: "Training" },
                                     { data: "trainings.Description" ,title: "Description"},
                                     { data: "trainings.Organizer",title: "Organizer" },
                                     { data: "trainings.Training_Date" ,title: "Training_Date"},
                                     { data: "files.Web_Path",
                                        render: function ( url, type, row ) {
                                             if (url)
                                             {
                                               return '<a href="'+ url +'" target="_blank">Download</a>';
                                             }
                                             else {
                                               return ' - ';
                                             }
                                         },
                                         title: "File"
                                       }
                             ],
                             autoFill: {
                                     //columns: ':not(:first-child)',
                                      editor:  trainingseditor
                             },
                             select: {
                                     style:    'os',
                                     selector: 'tr'
                             },
                             buttons: [
                                     { extend: "create", editor: trainingseditor },
                                     { extend: "edit",   editor: trainingseditor },
                                     { extend: "remove", editor: trainingseditor }
                             ],
                      });
                      $('#trainingtable').on( 'click', 'tr', function () {
                        // Get the rows id value
                       //  var row=$(this).closest("tr");
                       //  var oTable = row.closest('table').dataTable();
                        trainingid = trainingtable.api().row( this ).data().trainings.Id;
                      });

                        certificatetable=$('#certificatetable').dataTable( {
                             // keys: {
                             //      columns: ':not(:first-child)',
                             //      editor:  trainingseditor   //THIS LINE FIXED THE PROBLEM
                             //  },
                              columnDefs: [{ "visible": false, "targets": [1] },{"className": "dt-center", "targets": "_all"}],
                              responsive: false,
                              colReorder: false,
                              dom: "Brt",
                              bAutoWidth: true,
                              bScrollCollapse: true,
                              columns: [
                                      { data: null,"render":"", title:"No"},
                                      { data: "certificates.Id",title: "Id"},
                                      { data: "certificates.Certificate",title: "Certificate" },
                                      { data: "certificates.Description" ,title: "Description"},
                                      { data: "certificates.Certificate_Date" ,title: "Certificate_Date"},
                                      { data: "certificates.Valid_Until",title: "Valid_Until" },
                                      { data: "files.Web_Path",
                                         render: function ( url, type, row ) {
                                              if (url)
                                              {
                                                return '<a href="'+ url +'" target="_blank">Download</a>';
                                              }
                                              else {
                                                return ' - ';
                                              }
                                          },
                                          title: "File"
                                        }
                              ],
                              autoFill: {
                                      //columns: ':not(:first-child)',
                                       editor:  certificateseditor
                              },
                              select: {
                                      style:    'os',
                                      selector: 'tr'
                              },
                              buttons: [
                                      { extend: "create", editor: certificateseditor },
                                      { extend: "edit",   editor: certificateseditor },
                                      { extend: "remove", editor: certificateseditor }
                              ],
                       });
                       $('#certificatetable').on( 'click', 'tr', function () {
                         // Get the rows id value
                        //  var row=$(this).closest("tr");
                        //  var oTable = row.closest('table').dataTable();
                         certificateid = certificatetable.api().row( this ).data().certificates.Id;
                       });

                       salarytable=$('#salarytable').dataTable( {
                              // keys: {
                              //      columns: ':not(:first-child)',
                              //      editor:  employmentseditor   //THIS LINE FIXED THE PROBLEM
                              //  },
                               columnDefs: [{ "visible": false, "targets": [1] },{"className": "dt-center", "targets": "_all"}],
                               responsive: false,
                               colReorder: false,
                               dom: "Brt",
                               bAutoWidth: true,
                               columns: [
                                       { data: null,"render":"", title:"No"},
                                       { data: "salary.Id",title: "Id"},
                                       { data: "salary.Salary",title: "Salary" },
                                       { data: "salary.Remarks",title: "Remarks" },
                                       { data: "salary.Created_By",title: "Created By" },

                                       { data: "salary.Adjustment_Date",title: "Adjustment_Date" }
                               ],
                               autoFill: {
                                       //columns: ':not(:first-child)',
                                        editor:  salaryeditor
                               },
                               select: {
                                       style:    'os',
                                       selector: 'td:first-child'
                               },
                               buttons: [
                                      //  { extend: "create", editor: salaryeditor },
                                      //  { extend: "edit",   editor: salaryeditor },
                                      //  { extend: "remove", editor: salaryeditor }
                               ],
                   });
                   reviewtable=$('#reviewtable').dataTable( {
                          // keys: {
                          //      columns: ':not(:first-child)',
                          //      editor:  employmentseditor   //THIS LINE FIXED THE PROBLEM
                          //  },
                           columnDefs: [{ "visible": false, "targets": [1] },{"className": "dt-center", "targets": "_all"}],
                           responsive: false,
                           colReorder: false,
                           dom: "Brt",
                           bAutoWidth: true,
                           columns: [
                                   { data: null,"render":"", title:"No"},
                                   { data: "reviews.Id",title: "Id"},
                                   { data: "reviews.Status",title: "Status" },
                                   { data: "reviews.Remarks",title: "Remarks" },
                                   { data: "reviews.Reviewed_Date",title: "Reviewed Date" },
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
                                   //columns: ':not(:first-child)',
                                    editor:  revieweditor
                           },
                           select: {
                                   style:    'os',
                                   selector: 'td:first-child'
                           },
                           buttons: [
                                  //  { extend: "create", editor: revieweditor },
                                  //  { extend: "edit",   editor: revieweditor },
                                  //  { extend: "remove", editor: revieweditor }
                           ],
               });

               familytable=$('#familytable').dataTable( {
                     // keys: {
                     //      columns: ':not(:first-child)',
                     //      editor:  trainingseditor   //THIS LINE FIXED THE PROBLEM
                     //  },
                      columnDefs: [{ "visible": false, "targets": [1] },{"className": "dt-center", "targets": "_all"}],
                      responsive: false,
                      colReorder: false,
                      dom: "Brt",
                      bAutoWidth: true,
                      bScrollCollapse: true,
                      columns: [
                              { data: null,"render":"", title:"No"},
                              { data: "family.Id",title: "Id"},
                              { data: "family.Name",title: "Name" },
                              { data: "family.NRIC",title: "NRIC" },
                              { data: "family.Gender",title: "Gender" },
                              { data: "family.Age" ,title: "Age"},
                              { data: "family.Relationship",title: "Relationship" },
                              { data: "family.Occupation" ,title: "Occupation"},
                              { data: "family.Company_School_Name" ,title: "Company/School Name"},
                              { data: "family.Contact_No" ,title: "Contact No"},


                      ],
                      autoFill: {
                              //columns: ':not(:first-child)',
                               editor:  familyeditor
                      },
                      select: {
                              style:    'os',
                              selector: 'tr'
                      },
                      buttons: [
                              { extend: "create", editor: familyeditor },
                              { extend: "edit",   editor: familyeditor },
                              { extend: "remove", editor: familyeditor }
                      ],
               });

               familytable1=$('#familytable1').dataTable( {
                     // keys: {
                     //      columns: ':not(:first-child)',
                     //      editor:  trainingseditor   //THIS LINE FIXED THE PROBLEM
                     //  },
                      columnDefs: [{ "visible": false, "targets": [1] },{"className": "dt-center", "targets": "_all"}],
                      responsive: false,
                      colReorder: false,
                      dom: "Brt",
                      bAutoWidth: true,
                      bScrollCollapse: true,
                      columns: [
                              { data: null,"render":"", title:"No"},
                              { data: "family.Id",title: "Id"},
                              { data: "family.Name",title: "Name" },
                              { data: "family.NRIC",title: "NRIC" },
                              { data: "family.Gender",title: "Gender" },
                              { data: "family.Age" ,title: "Age"},
                              { data: "family.Relationship",title: "Relationship" },
                              { data: "family.Occupation" ,title: "Occupation"},
                              { data: "family.Company_School_Name" ,title: "Company/School Name"},
                              { data: "family.Contact_No" ,title: "Contact No"},


                      ],
                      autoFill: {
                              //columns: ':not(:first-child)',
                               editor:  familyeditor
                      },
                      select: {
                              style:    'os',
                              selector: 'tr'
                      },
                      buttons: [
                              { extend: "create", editor: familyeditor },
                              { extend: "edit",   editor: familyeditor },
                              { extend: "remove", editor: familyeditor }
                      ],
               });

               languagetable=$('#languagetable').dataTable( {
                     // keys: {
                     //      columns: ':not(:first-child)',
                     //      editor:  trainingseditor   //THIS LINE FIXED THE PROBLEM
                     //  },
                      columnDefs: [{ "visible": false, "targets": [1] },{"className": "dt-center", "targets": "_all"}],
                      responsive: false,
                      colReorder: false,
                      dom: "Brt",
                      bAutoWidth: true,
                      bScrollCollapse: true,
                      columns: [
                              { data: null,"render":"", title:"No"},
                              { data: "languages.Id",title: "Id"},
                              { data: "languages.Language",title: "Language" },
                              { data: "languages.Speak" ,title: "Speak"},
                              { data: "languages.Written",title: "Written" },


                      ],
                      autoFill: {
                              //columns: ':not(:first-child)',
                               editor:  languageeditor
                      },
                      select: {
                              style:    'os',
                              selector: 'tr'
                      },
                      buttons: [
                              { extend: "create", editor: languageeditor },
                              { extend: "edit",   editor: languageeditor },
                              { extend: "remove", editor: languageeditor }
                      ],
               });

                       //number ordering in first column
                     employmenttable.api().on( 'order.dt search.dt', function () {
                         employmenttable.api().column(0, {search:'applied', order:'applied'}).nodes().each( function (cell, i) {
                             cell.innerHTML = i+1;
                         } );
                     } ).draw();

                     licensetable.api().on( 'order.dt search.dt', function () {
                         licensetable.api().column(0, {search:'applied', order:'applied'}).nodes().each( function (cell, i) {
                             cell.innerHTML = i+1;
                         } );
                     } ).draw();

                     skilltable.api().on( 'order.dt search.dt', function () {
                         skilltable.api().column(0, {search:'applied', order:'applied'}).nodes().each( function (cell, i) {
                             cell.innerHTML = i+1;
                         } );
                     } ).draw();

                     certificatetable.api().on( 'order.dt search.dt', function () {
                         certificatetable.api().column(0, {search:'applied', order:'applied'}).nodes().each( function (cell, i) {
                             cell.innerHTML = i+1;
                         } );
                     } ).draw();

                     trainingtable.api().on( 'order.dt search.dt', function () {
                         trainingtable.api().column(0, {search:'applied', order:'applied'}).nodes().each( function (cell, i) {
                             cell.innerHTML = i+1;
                         } );
                     } ).draw();

                     referencetable.api().on( 'order.dt search.dt', function () {
                         referencetable.api().column(0, {search:'applied', order:'applied'}).nodes().each( function (cell, i) {
                             cell.innerHTML = i+1;
                         } );
                     } ).draw();

                     qualificationtable.api().on( 'order.dt search.dt', function () {
                         qualificationtable.api().column(0, {search:'applied', order:'applied'}).nodes().each( function (cell, i) {
                             cell.innerHTML = i+1;
                         } );
                     } ).draw();

                     salarytable.api().on( 'order.dt search.dt', function () {
                         salarytable.api().column(0, {search:'applied', order:'applied'}).nodes().each( function (cell, i) {
                             cell.innerHTML = i+1;
                         } );
                     } ).draw();
                     experiencetable.api().on( 'order.dt search.dt', function () {
                         experiencetable.api().column(0, {search:'applied', order:'applied'}).nodes().each( function (cell, i) {
                             cell.innerHTML = i+1;
                         } );
                     } ).draw();
                     reviewtable.api().on( 'order.dt search.dt', function () {
                         reviewtable.api().column(0, {search:'applied', order:'applied'}).nodes().each( function (cell, i) {
                             cell.innerHTML = i+1;
                         } );
                     } ).draw();
                     familytable.api().on( 'order.dt search.dt', function () {
                         familytable.api().column(0, {search:'applied', order:'applied'}).nodes().each( function (cell, i) {
                             cell.innerHTML = i+1;
                         } );
                     } ).draw();
                     familytable1.api().on( 'order.dt search.dt', function () {
                         familytable1.api().column(0, {search:'applied', order:'applied'}).nodes().each( function (cell, i) {
                             cell.innerHTML = i+1;
                         } );
                     } ).draw();
                     languagetable.api().on( 'order.dt search.dt', function () {
                         languagetable.api().column(0, {search:'applied', order:'applied'}).nodes().each( function (cell, i) {
                             cell.innerHTML = i+1;
                         } );
                     } ).draw();

                     //column search

                     $("#experiencetable thead input").keyup ( function () {

                             /* Filter on the column (the index) of this element */
                         if ($('#experiencetable').length > 0)
                         {

                             var colnum=document.getElementById('experiencetable').rows[0].cells.length;

                             if (this.value=="[empty]")
                             {

                                experiencetable.fnFilter( '^$', $("#experiencetable thead input").index(this)-colnum,true,false );
                             }
                             else if (this.value=="[nonempty]")
                             {

                                experiencetable.fnFilter( '^(?=\\s*\\S).*$', $("#experiencetable thead input").index(this)-colnum,true,false );
                             }
                             else if (this.value.startsWith("!")==true && this.value.length>1)
                             {

                                experiencetable.fnFilter( '^(?'+ this.value +').*', $("#experiencetable thead input").index(this)-colnum,true,false );
                             }
                             else if (this.value.startsWith("!")==false)
                             {

                               experiencetable.fnFilter( this.value, $("#experiencetable thead input").index(this)-colnum,true,false );
                             }
                         }


                     } );

                     $("#licensetable thead input").keyup ( function () {

                             /* Filter on the column (the index) of this element */
                         if ($('#licensetable').length > 0)
                         {

                             var colnum=document.getElementById('licensetable').rows[0].cells.length;

                             if (this.value=="[empty]")
                             {

                                licensetable.fnFilter( '^$', $("#licensetable thead input").index(this)-colnum,true,false );
                             }
                             else if (this.value=="[nonempty]")
                             {

                                licensetable.fnFilter( '^(?=\\s*\\S).*$', $("#licensetable thead input").index(this)-colnum,true,false );
                             }
                             else if (this.value.startsWith("!")==true && this.value.length>1)
                             {

                                licensetable.fnFilter( '^(?'+ this.value +').*', $("#licensetable thead input").index(this)-colnum,true,false );
                             }
                             else if (this.value.startsWith("!")==false)
                             {

                               licensetable.fnFilter( this.value, $("#licensetable thead input").index(this)-colnum,true,false );
                             }
                         }


                     } );

                     $("#employmenttable thead input").keyup ( function () {

                             /* Filter on the column (the index) of this element */
                         if ($('#employmenttable').length > 0)
                         {

                             var colnum=document.getElementById('employmenttable').rows[0].cells.length;

                             if (this.value=="[empty]")
                             {

                                employmenttable.fnFilter( '^$', $("#employmenttable thead input").index(this)-colnum,true,false );
                             }
                             else if (this.value=="[nonempty]")
                             {

                                employmenttable.fnFilter( '^(?=\\s*\\S).*$', $("#employmenttable thead input").index(this)-colnum,true,false );
                             }
                             else if (this.value.startsWith("!")==true && this.value.length>1)
                             {

                                employmenttable.fnFilter( '^(?'+ this.value +').*', $("#employmenttable thead input").index(this)-colnum,true,false );
                             }
                             else if (this.value.startsWith("!")==false)
                             {

                               employmenttable.fnFilter( this.value, $("#employmenttable thead input").index(this)-colnum,true,false );
                             }
                         }


                     } );

                     $("#skilltable thead input").keyup ( function () {

                             /* Filter on the column (the index) of this element */
                         if ($('#skilltable').length > 0)
                         {

                             var colnum=document.getElementById('skilltable').rows[0].cells.length;

                             if (this.value=="[empty]")
                             {

                                skilltable.fnFilter( '^$', $("#skilltable thead input").index(this)-colnum,true,false );
                             }
                             else if (this.value=="[nonempty]")
                             {

                                skilltable.fnFilter( '^(?=\\s*\\S).*$', $("#skilltable thead input").index(this)-colnum,true,false );
                             }
                             else if (this.value.startsWith("!")==true && this.value.length>1)
                             {

                                skilltable.fnFilter( '^(?'+ this.value +').*', $("#skilltable thead input").index(this)-colnum,true,false );
                             }
                             else if (this.value.startsWith("!")==false)
                             {

                               skilltable.fnFilter( this.value, $("#skilltable thead input").index(this)-colnum,true,false );
                             }
                         }


                     } );

                     $("#certificatetable thead input").keyup ( function () {

                             /* Filter on the column (the index) of this element */
                         if ($('#certificatetable').length > 0)
                         {

                             var colnum=document.getElementById('certificatetable').rows[0].cells.length;

                             if (this.value=="[empty]")
                             {

                                certificatetable.fnFilter( '^$', $("#certificatetable thead input").index(this)-colnum,true,false );
                             }
                             else if (this.value=="[nonempty]")
                             {

                                certificatetable.fnFilter( '^(?=\\s*\\S).*$', $("#certificatetable thead input").index(this)-colnum,true,false );
                             }
                             else if (this.value.startsWith("!")==true && this.value.length>1)
                             {

                                certificatetable.fnFilter( '^(?'+ this.value +').*', $("#certificatetable thead input").index(this)-colnum,true,false );
                             }
                             else if (this.value.startsWith("!")==false)
                             {

                               certificatetable.fnFilter( this.value, $("#certificatetable thead input").index(this)-colnum,true,false );
                             }
                         }


                     } );

                     $("#trainingtable thead input").keyup ( function () {

                             /* Filter on the column (the index) of this element */
                         if ($('#trainingtable').length > 0)
                         {

                             var colnum=document.getElementById('trainingtable').rows[0].cells.length;

                             if (this.value=="[empty]")
                             {

                                trainingtable.fnFilter( '^$', $("#trainingtable thead input").index(this)-colnum,true,false );
                             }
                             else if (this.value=="[nonempty]")
                             {

                                trainingtable.fnFilter( '^(?=\\s*\\S).*$', $("#trainingtable thead input").index(this)-colnum,true,false );
                             }
                             else if (this.value.startsWith("!")==true && this.value.length>1)
                             {

                                trainingtable.fnFilter( '^(?'+ this.value +').*', $("#trainingtable thead input").index(this)-colnum,true,false );
                             }
                             else if (this.value.startsWith("!")==false)
                             {

                               trainingtable.fnFilter( this.value, $("#trainingtable thead input").index(this)-colnum,true,false );
                             }
                         }


                     } );

                     $("#referencetable thead input").keyup ( function () {

                             /* Filter on the column (the index) of this element */
                         if ($('#referencetable').length > 0)
                         {

                             var colnum=document.getElementById('referencetable').rows[0].cells.length;

                             if (this.value=="[empty]")
                             {

                                referencetable.fnFilter( '^$', $("#referencetable thead input").index(this)-colnum,true,false );
                             }
                             else if (this.value=="[nonempty]")
                             {

                                referencetable.fnFilter( '^(?=\\s*\\S).*$', $("#referencetable thead input").index(this)-colnum,true,false );
                             }
                             else if (this.value.startsWith("!")==true && this.value.length>1)
                             {

                                referencetable.fnFilter( '^(?'+ this.value +').*', $("#referencetable thead input").index(this)-colnum,true,false );
                             }
                             else if (this.value.startsWith("!")==false)
                             {

                               referencetable.fnFilter( this.value, $("#referencetable thead input").index(this)-colnum,true,false );
                             }
                         }


                     } );

                     $("#qualificationtable thead input").keyup ( function () {

                             /* Filter on the column (the index) of this element */
                         if ($('#qualificationtable').length > 0)
                         {

                             var colnum=document.getElementById('qualificationtable').rows[0].cells.length;

                             if (this.value=="[empty]")
                             {

                                qualificationtable.fnFilter( '^$', $("#qualificationtable thead input").index(this)-colnum,true,false );
                             }
                             else if (this.value=="[nonempty]")
                             {

                                qualificationtable.fnFilter( '^(?=\\s*\\S).*$', $("#qualificationtable thead input").index(this)-colnum,true,false );
                             }
                             else if (this.value.startsWith("!")==true && this.value.length>1)
                             {

                                qualificationtable.fnFilter( '^(?'+ this.value +').*', $("#qualificationtable thead input").index(this)-colnum,true,false );
                             }
                             else if (this.value.startsWith("!")==false)
                             {

                               qualificationtable.fnFilter( this.value, $("#qualificationtable thead input").index(this)-colnum,true,false );
                             }
                         }


                     } );

                     $("#salarytable thead input").keyup ( function () {

                             /* Filter on the column (the index) of this element */
                         if ($('#salarytable').length > 0)
                         {

                             var colnum=document.getElementById('salarytable').rows[0].cells.length;

                             if (this.value=="[empty]")
                             {

                                salarytable.fnFilter( '^$', $("#salarytable thead input").index(this)-colnum,true,false );
                             }
                             else if (this.value=="[nonempty]")
                             {

                                salarytable.fnFilter( '^(?=\\s*\\S).*$', $("#salarytable thead input").index(this)-colnum,true,false );
                             }
                             else if (this.value.startsWith("!")==true && this.value.length>1)
                             {

                                salarytable.fnFilter( '^(?'+ this.value +').*', $("#salarytable thead input").index(this)-colnum,true,false );
                             }
                             else if (this.value.startsWith("!")==false)
                             {

                               salarytable.fnFilter( this.value, $("#salarytable thead input").index(this)-colnum,true,false );
                             }
                         }


                     } );

                     $("#reviewtable thead input").keyup ( function () {

                             /* Filter on the column (the index) of this element */
                         if ($('#reviewtable').length > 0)
                         {

                             var colnum=document.getElementById('reviewtable').rows[0].cells.length;

                             if (this.value=="[empty]")
                             {

                                reviewtable.fnFilter( '^$', $("#reviewtable thead input").index(this)-colnum,true,false );
                             }
                             else if (this.value=="[nonempty]")
                             {

                                reviewtable.fnFilter( '^(?=\\s*\\S).*$', $("#reviewtable thead input").index(this)-colnum,true,false );
                             }
                             else if (this.value.startsWith("!")==true && this.value.length>1)
                             {

                                reviewtable.fnFilter( '^(?'+ this.value +').*', $("#reviewtable thead input").index(this)-colnum,true,false );
                             }
                             else if (this.value.startsWith("!")==false)
                             {

                               reviewtable.fnFilter( this.value, $("#reviewtable thead input").index(this)-colnum,true,false );
                             }
                         }


                     } );

                     $("#familytable thead input").keyup ( function () {

                             /* Filter on the column (the index) of this element */
                         if ($('#familytable').length > 0)
                         {

                             var colnum=document.getElementById('familytable').rows[0].cells.length;

                             if (this.value=="[empty]")
                             {

                                familytable.fnFilter( '^$', $("#familytable thead input").index(this)-colnum,true,false );
                             }
                             else if (this.value=="[nonempty]")
                             {

                                familytable.fnFilter( '^(?=\\s*\\S).*$', $("#familytable thead input").index(this)-colnum,true,false );
                             }
                             else if (this.value.startsWith("!")==true && this.value.length>1)
                             {

                                familytable.fnFilter( '^(?'+ this.value +').*', $("#familytable thead input").index(this)-colnum,true,false );
                             }
                             else if (this.value.startsWith("!")==false)
                             {

                               familytable.fnFilter( this.value, $("#familytable thead input").index(this)-colnum,true,false );
                             }
                         }


                     } );

                     $("#languagetable thead input").keyup ( function () {

                             /* Filter on the column (the index) of this element */
                         if ($('#languagetable').length > 0)
                         {

                             var colnum=document.getElementById('languagetable').rows[0].cells.length;

                             if (this.value=="[empty]")
                             {

                                languagetable.fnFilter( '^$', $("#languagetable thead input").index(this)-colnum,true,false );
                             }
                             else if (this.value=="[nonempty]")
                             {

                                languagetable.fnFilter( '^(?=\\s*\\S).*$', $("#languagetable thead input").index(this)-colnum,true,false );
                             }
                             else if (this.value.startsWith("!")==true && this.value.length>1)
                             {

                                languagetable.fnFilter( '^(?'+ this.value +').*', $("#languagetable thead input").index(this)-colnum,true,false );
                             }
                             else if (this.value.startsWith("!")==false)
                             {

                               languagetable.fnFilter( this.value, $("#languagetable thead input").index(this)-colnum,true,false );
                             }
                         }


                     } );

                     $('a[data-toggle="tab"]').on( 'shown.bs.tab', function (e) {
                       var target = $(e.target).attr("href") // activated tab


                         $.fn.dataTable.tables( {visible: true, api: true} ).columns.adjust();


                     } );

                      var formHasChanged = false;

                      $('input').keydown(function() {
                        formHasChanged=true;
                     });

                     $('select').change(function() {
                       formHasChanged=true;
                    });

                    $('textarea').keydown(function() {
                      formHasChanged=true;
                   });

                     window.onbeforeunload = function (e) {
                          if (formHasChanged) {
                              var message = "You have not saved your changes.", e = e || window.event;
                              if (e) {
                                  e.returnValue = "abc";
                              }
                              // return message;
                          }
                      }
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
        My Profile
        <small>My Workplace</small>
      </h1>
      <ol class="breadcrumb">
        <li><a href="#"><i class="fa fa-dashboard"></i> Home</a></li>
        <li><a href="#">My Workplace</a></li>
        <li><a href="{{ url('/user') }}">My Profile</a></li>
        <li class="active">{{ $user->Name }}</li>
      </ol>
    </section>

    <!-- Main content -->
    <section class="content">
      <div class="row">
        <!-- /.col -->
        <div class="col-md-12">

          {{-- <div id="update-alert" class="alert alert-success alert-dismissible" style="display:none;">
            <button type="button" class="close" onclick="$('#update-alert').hide()" aria-hidden="true">&times;</button>
            <h4><i class="icon fa fa-check"></i> Alert!</h4>
            <ul>

            </ul>
          </div> --}}

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

              <li class="active"><a href="#personaldetail" data-toggle="tab">Personal Detail</a></li>
              <li><a href="#family" data-toggle="tab">Family</a></li>
              <li><a href="#license" data-toggle="tab">License</a></li>
              <li><a href="#certificate" data-toggle="tab">Certificate</a></li>
              <li><a href="#resume" data-toggle="tab">Resume</a></li>
              <li><a href="#Export" data-toggle="tab">Export</a></li>

              <!-- <li><a href="#qualification" data-toggle="tab">Education</a></li>
              <li><a href="#experience" data-toggle="tab">Experience</a></li>
              <li><a href="#skill" data-toggle="tab">Skill</a></li>
              <li><a href="#license" data-toggle="tab">License</a></li>
              <li><a href="#training" data-toggle="tab">Training</a></li>
              <li><a href="#reference" data-toggle="tab">Reference</a></li>
              <li><a href="#employment" data-toggle="tab">Employment History</a></li>
              <li><a href="#salary" data-toggle="tab">Salary</a></li>
              <li><a href="#review" data-toggle="tab">Review</a></li>
              <li><a href="#language" data-toggle="tab">Language</a></li> -->


            </ul>

            <div class="tab-content">
              <div class="active tab-pane" id="personaldetail">
                  <div class="">

                      <div class="">
                        <!-- <button type="button" class="btn btn-default btn-small" data-toggle="modal" data-target="#UpdateProfile">Update Profile</button>
                        <button type="button" class="btn btn-default btn-small" data-toggle="modal" data-target="#UpdateProfilePicture">Update Profile Picture</button> -->
                        {{-- <button type="button" class="btn btn-success btn-small" data-toggle="modal" data-target="#ChangePassword">Change Password</button> --}}
                        <!-- <a href="{{ url('/export1') }}/{{$UserId}}" target="_blank"><button type="button" class="btn btn-success btn-lg" >Export CV</button></a> -->

                        <!-- <button type="button" class="btn btn-success btn-lg" data-toggle="modal" data-target="#ExportPDF">Export CV</button> -->

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

                      <div class="modal fade" id="ExportPDF" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
                         <div class="modal-dialog">
                          <div class="modal-content">
                            <div class="modal-header">
                              <div id="exist-alert" class="alert alert-warning alert-dismissible" style="display:none;">
                                <h4><i class="icon fa fa-check"></i> Alert!</h4>
                                <div id="changepasswordmessage"></div>
                              </div>
                              <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                              <h4 class="modal-title" id="myModalLabel">Export CV</h4>

                            </div>
                            <div class="modal-body">
                              <center>

                                <a class="btn btn-primary btn-small" href="{{ url('/export1') }}/{{$UserId}}">CV 1</a>
                                <a class="btn btn-primary btn-small" href="{{ url('/export2') }}/{{$UserId}}">CV 2</a>
                                <a class="btn btn-primary btn-small" href="{{ url('/export3') }}/{{$UserId}}">CV 3</a>

                             </center>
                            </div>
                          </div>
                        </div>
                      </div>

                      <div class="">
                        <br>

                          <div class="row">

                            <div class="col-lg-9">

                              @if( $user->Status=="Account Detail Rejected")
                                <div class="form-group has-error">
                                    <label class="pull-left control-label" for="inputError"><p>Account Detail Rejected<br>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Comment : {{$user->Comment}}</p></label>
                                </div>
                              @elseif( $user->Status=="Initial Update Required" || $interval==-1)
                                <div class="form-group has-error">
                                    <label class="pull-left control-label" for="inputError"><p>Initial account update required. Please fill in your personal detail and submit for approval!</p></label>
                                </div>
                                <br>
                              @elseif( $user->Status=="Pending Account Detail Approval")
                                <div class="form-group has-error">
                                    <label class="pull-left control-label" for="inputError"><p>Pending Account Detail Approval</p></label>
                                </div>
                                <br>
                              @elseif( $interval>=6)
                                <div class="form-group has-error">
                                    <label class="pull-left control-label" for="inputError"><p>Approved On : {{date_format(date_create($user->Detail_Approved_On), 'd-M-Y')}}&nbsp;&nbsp;&nbsp;[Staff detail more than 6 months old.]</p></label>
                                </div>
                                <br>
                              @else
                                <div class="form-group has-success">
                                    <label class="pull-left control-label" for="inputSuccess"><p>Approved On : {{date_format(date_create($user->Detail_Approved_On), 'd-M-Y')}}</p></label>
                                </div>
                                <br>
                              @endif

                            </div>

                            <div class="col-lg-3">

                              <button type="button" class="btn btn-danger btn-small" style="background: #d9534f; border-color: #d9534f;" data-toggle="modal" data-target="#UpdateProfile" style="float:right"><i class="fa fa-pencil-square-o" aria-hidden="true"></i> Update Profile</button>
                            </div>

                              <br><br>

                                <div class="col-lg-6">
                                   @if ($user->Web_Path)

                                    <img class="profile-user-img img-responsive" name="profileimage" id="profileimage" src="{{ url($user->Web_Path) }}" alt="User profile picture">
                                    <div class="caption" data-toggle="modal" data-target="#UpdateProfilePicture"><i class="fa fa-camera"></i> Change Picture</div>
                                  @else
                                    <img class="profile-user-img img-responsive" name="profileimage" id="profileimage" src="{{ URL::to('/') ."/img/default-user.png"  }}" alt="User profile picture">
                                    <div class="caption" data-toggle="modal" data-target="#UpdateProfilePicture"><i class="fa fa-camera"></i> Change Picture</div>
                                  @endif

                                </div>

                                <div class="col-lg-6" style="padding:0;">

                                  <div class="col-lg-6">
                                    <label>Staff ID : </label>
                                  </div>

                                  <div class="col-lg-6">
                                    <input type="text" class="form-control" id="StaffId" name="StaffId" value="{{$user->Staff_ID}}" disabled>
                                    <input type="hidden" class="form-control" id="UserId" name="UserId" value="{{$user->Id}}" disabled>

                                  </div>

                                  <div class="col-lg-6">
                                    <label>Name : </label>
                                  </div>

                                  <div class="col-lg-6">
                                      @if(str_contains($changes, 'Name'))
                                        <input type="text" class="changed form-control" id="Name" name="Name" value="{{$user->Name}}">
                                      @else
                                        <input type="text" class="form-control" id="Name" name="Name" value="{{$user->Name}}">
                                      @endif
                                  </div>

                                  <div class="col-lg-6">

                                      <label>NRIC/PASSPORT NO/UNION NO</label>

                                  </div>

                                  <div class="col-lg-6">

                                    <select class="form-control select" id="SelectValue" name="SelectValue" style="width: 100%;">
                                      <option></option>
                                      <option value="NRIC">NRIC</option>
                   									  <option value="Passport No">Passport No</option>
                                      <option value="Union No">Union No</option>
                                    </select>


                                    <div id="nricfield">

                                       @if(str_contains($changes, 'NRIC'))
                                         <input type="text" class="changed form-control" id="NRIC" name="NRIC" value="{{$user->NRIC}}">
                                       @else
                                         <input type="text" class="form-control" id="NRIC" name="NRIC" value="{{$user->NRIC}}">
                                       @endif
                                    </div>

                                    <div id="passportfield">
                                       @if(str_contains($changes, 'Passport_No'))
                                         <input type="text" class="changed form-control" id="Passport_No" name="Passport_No" value="{{$user->Passport_No}}">
                                       @else
                                         <input type="text" class="form-control" id="Passport_No" name="Passport_No" value="{{$user->Passport_No}}">
                                       @endif
                                    </div>

                                    <div id="unionnofield">
                                      @if(str_contains($changes, 'Union_No'))
                                        <input type="text" class="changed form-control" id="Union_No" name="Union_No" value="{{$user->Union_No}}">
                                      @else
                                        <input type="text" class="form-control" id="Union_No" name="Union_No" value="{{$user->Union_No}}">
                                      @endif
                                    </div>

                                  </div>

                                  <div class="col-lg-6">
                                    <label>Position : </label>
                                  </div>

                                  <div class="col-lg-6">
                                    @if(str_contains($changes, 'Position'))
                                      <select class="changed form-control select" id="Position" name="Position" style="width: 100%;" disabled>
                                    @else
                                      <select class="form-control select2" id="Position" name="Position" style="width: 100%;" disabled>
                                    @endif

                                      <option></option>
                                      @foreach ($options as $key => $option)
                                        @if ($option->Field=="Position")

                                          <option <?php if($user->Position == $option->Option) echo ' selected="selected" '; ?>>{{$option->Option}}</option>

                                        @endif
                                      @endforeach
                                    </select>
                                  </div>

                                  <div class="col-lg-6">
                                    <label>Company : </label>
                                  </div>

                                  <div class="col-lg-6">
                                    @if(str_contains($changes, 'Company'))
                                      <select class="changed form-control select" id="Company" name="Company" style="width: 100%;" disabled>
                                    @else
                                      <select class="form-control select2" id="Company" name="Company" style="width: 100%;" disabled>
                                    @endif

                                      <option></option>
                                      @foreach ($options as $key => $option)
                                        @if ($option->Field=="Company")

                                          <option <?php if($user->Company == $option->Option) echo ' selected="selected" '; ?>>{{$option->Option}}</option>

                                        @endif
                                      @endforeach
                                    </select>

                                  </div>

                                  <div class="col-lg-6">
                                    <label>Department : </label>
                                  </div>

                                  <div class="col-lg-6">
                                    @if(str_contains($changes, 'Department'))
                                      <select class="changed form-control select" id="Department" name="Department" style="width: 100%;" disabled>
                                    @else
                                      <select class="form-control select2" id="Department" name="Department" style="width: 100%;" disabled>
                                    @endif

                                      <option></option>
                                      @foreach ($projects as $project)

                                          <option <?php if($user->Department == $project->Project_Name) echo ' selected="selected" '; ?>>{{$project->Project_Name}}</option>

                                      @endforeach
                                    </select>

                                  </div>

                                  <div class="col-lg-6">
                                    <label>Category : </label>
                                  </div>

                                  <div class="col-lg-6">
                                    @if(str_contains($changes, 'Category'))
                                      <select class="changed form-control select" id="Category" name="Category" style="width: 100%;" disabled>
                                    @else
                                      <select class="form-control select2" id="Category" name="Category" style="width: 100%;" disabled>
                                    @endif

                                      <option></option>
                                      @foreach ($options as $opt)
                                          @if ($opt->Field=="Category")
                                            <option <?php if($user->Category == $opt->Option) echo ' selected="selected" '; ?>>{{$opt->Option}}</option>
                                          @endif
                                      @endforeach
                                    </select>

                                  </div>

                                  <div class="col-lg-6">
                                    <label>Joined Date : </label>
                                  </div>

                                  <div class="col-lg-6">
                                    @if(str_contains($changes, 'Joining_Date'))
                                      <input type="text" class="changed form-control" id="Joining_Date" name="Joining_Date" value="{{$user->Joining_Date}}" disabled>
                                    @else
                                      <input type="text" class="form-control" id="Joining_Date" name="Joining_Date" value="{{$user->Joining_Date}}" disabled>
                                    @endif

                                  </div>

                                  <div class="col-lg-6">
                                    <label>Confirmation Date : </label>
                                  </div>

                                  <div class="col-lg-6">
                                    @if(str_contains($changes, 'Confirmation_Date'))
                                      <input type="text" class="changed form-control" id="Confirmation_Date" name="Confirmation_Date" value="{{$user->Confirmation_Date}}" disabled>
                                    @else
                                      <input type="text" class="form-control" id="Confirmation_Date" name="Confirmation_Date" value="{{$user->Confirmation_Date}}" disabled>
                                    @endif

                                  </div>

                                </div>

                            </div>

                            <br>

                          <div class="row">

                            <div class="form-group">

                              <div class="col-lg-3">
                                <label>DOB : </label>
                              </div>

                              <div class="col-lg-3">

                                @if(str_contains($changes, 'DOB'))
                                  <input type="text" class="changed form-control" id="DOB" name="DOB" value="{{$user->DOB}}">
                                @else
                                  <input type="text" class="form-control" id="DOB" name="DOB" value="{{$user->DOB}}">
                                @endif

                              </div>

                              <div class="col-lg-3">
                                <label>Place of Birth : </label>
                              </div>

                              <div class="col-lg-3">
                                @if(str_contains($changes, 'Place_Of_Birth'))
                                  <input type="text" class="changed form-control" id="Place_Of_Birth" name="Place_Of_Birth" value="{{$user->Place_Of_Birth}}">
                                @else
                                  <input type="text" class="form-control" id="Place_Of_Birth" name="Place_Of_Birth" value="{{$user->Place_Of_Birth}}">
                                @endif
                              </div>

                            </div>

                          </div>

                          <div class="row">
                              <div class="form-group">

                                <div class="col-lg-3">
                                  <label>Company Email : </label>
                                </div>

                                <div class="col-lg-3">

                                  @if(str_contains($changes, 'Company_Email'))
                                    <input type="text" class="changed form-control" id="Company_Email" name="Company_Email" value="{{$user->Company_Email}}">
                                  @else
                                    <input type="text" class="form-control" id="Company_Email" name="Company_Email" value="{{$user->Company_Email}}">
                                  @endif
                                </div>

                                <div class="col-lg-3">
                                  <label>Personal Email : </label>
                                </div>

                                <div class="col-lg-3">

                                  @if(str_contains($changes, 'Personal_Email'))
                                    <input type="text" class="changed form-control" id="Personal_Email" name="Personal_Email" value="{{$user->Personal_Email}}">
                                  @else
                                    <input type="text" class="form-control" id="Personal_Email" name="Personal_Email" value="{{$user->Personal_Email}}">
                                  @endif
                                </div>

                            </div>
                          </div>

                          <div class="row">
                            <div class="form-group">

                              <div class="col-lg-3">
                                <label>Contact No 1 : </label>
                              </div>


                              <div class="col-lg-3">
                                @if(str_contains($changes, 'Contact_No_1'))
                                  <input type="text" class="changed form-control" id="Contact_No_1" name="Contact_No_1" placeholder="+60123456789" value="{{$user->Contact_No_1}}">
                                @else
                                  <input type="text" class="form-control" id="Contact_No_1" name="Contact_No_1" placeholder="+60123456789" value="{{$user->Contact_No_1}}">
                                @endif
                              </div>

                              <div class="col-lg-3">
                                <label>Contact No 2 : </label>
                              </div>

                              <div class="col-lg-3">

                                @if(str_contains($changes, 'Contact_No_2'))
                                  <input type="text" class="changed form-control" id="Contact_No_2" name="Contact_No_2" placeholder="+60123456789" value="{{$user->Contact_No_2}}">
                                @else
                                  <input type="text" class="form-control" id="Contact_No_2" name="Contact_No_2" placeholder="+60123456789" value="{{$user->Contact_No_2}}">
                                @endif
                              </div>

                            </div>
                          </div>

                          <div class="row">
                            <div class="form-group">

                              <div class="col-lg-3">
                                <label>House Phone No: </label>
                              </div>

                              <div class="col-lg-3">
                                @if(str_contains($changes, 'House_Phone_No'))
                                  <input type="text" class="changed form-control" id="House_Phone_No" name="House_Phone_No" placeholder="+6033456789" value="{{$user->House_Phone_No}}">
                                @else
                                  <input type="text" class="form-control" id="House_Phone_No" name="House_Phone_No" placeholder="+6033456789" value="{{$user->House_Phone_No}}">
                                @endif
                              </div>

                              <div class="col-lg-3">
                                <label>Marital Status : </label>
                              </div>

                              <div class="col-lg-3">

                                  @if(str_contains($changes, 'Marital_Status'))
                                    <select class="changed form-control select" id="Marital_Status" name="Marital_Status" style="width: 100%;">
                                  @else
                                    <select class="form-control select2" id="Marital_Status" name="Marital_Status" style="width: 100%;">
                                  @endif

                                    <option></option>
                                    @foreach ($options as $key => $option)
                                      @if ($option->Field=="Marital_Status")

                                        <option <?php if($user->Marital_Status == $option->Option) echo ' selected="selected" '; ?>>{{$option->Option}}</option>

                                      @endif
                                    @endforeach
                                  </select>
                              </div>
                            </div>
                          </div>

                          <div class="row">
                            <div class="form-group">
                              <div class="col-lg-3">
                                <label>Gender : </label>
                              </div>

                              <div class="col-lg-3">

                                  @if(str_contains($changes, 'Contact_No_2'))
                                    <select class="changed form-control select" id="Gender" name="Gender" style="width: 100%;">
                                  @else
                                    <select class="form-control select2" id="Gender" name="Gender" style="width: 100%;">
                                  @endif

                                    <option></option>
                                    @foreach ($options as $key => $option)
                                      @if ($option->Field=="Gender")

                                        <option <?php if($user->Gender == $option->Option) echo ' selected="selected" '; ?>>{{$option->Option}}</option>

                                      @endif
                                    @endforeach
                                  </select>
                              </div>

                              <div class="col-lg-3">
                                <label>Nationality : </label>
                              </div>

                              <div class="col-lg-3">
                                @if(str_contains($changes, 'Nationality'))
                                  <select class="changed form-control select" id="Nationality" name="Nationality" style="width: 100%;">
                                @else
                                  <select class="form-control select2" id="Nationality" name="Nationality" style="width: 100%;">
                                @endif

                                  <option></option>
                                  @foreach ($options as $key => $option)
                                    @if ($option->Field=="Nationality")

                                      <option <?php if($user->Nationality == $option->Option) echo ' selected="selected" '; ?>>{{$option->Option}}</option>

                                    @endif
                                  @endforeach
                                </select>
                              </div>

                            </div>
                          </div>

                          <div class="row">
                            <div class="form-group">
                              <div class="col-lg-3">
                                <label>Race : </label>
                              </div>

                              <div class="col-lg-3">

                                @if(str_contains($changes, 'Race'))
                                  <select class="changed form-control select" id="Race" name="Race" style="width: 100%;">
                                @else
                                  <select class="form-control select2" id="Race" name="Race" style="width: 100%;">
                                @endif

                                  <option></option>
                                  @foreach ($options as $key => $option)
                                    @if ($option->Field=="Race")

                                      <option <?php if($user->Race == $option->Option) echo ' selected="selected" '; ?>>{{$option->Option}}</option>

                                    @endif
                                  @endforeach
                                </select>
                              </div>

                              <div class="col-lg-3">

                                <label>Religion : </label>
                              </div>

                              <div class="col-lg-3">
                                @if(str_contains($changes, 'Religion'))
                                  <select class="changed form-control select" id="Religion" name="Religion" style="width: 100%;">
                                @else
                                  <select class="form-control select2" id="Religion" name="Religion" style="width: 100%;">
                                @endif

                                  <option></option>
                                  @foreach ($options as $key => $option)
                                    @if ($option->Field=="Religion")

                                      <option <?php if($user->Religion == $option->Option) echo ' selected="selected" '; ?>>{{$option->Option}}</option>

                                    @endif
                                  @endforeach
                                </select>
                              </div>
                            </div>
                          </div>

                          <br>

                          <div class="row">
                            <div class="form-group">
                              <div class="col-lg-3">
                                <label>Permanent Address : </label>
                              </div>

                              <div class="col-lg-9">

                                @if(str_contains($changes, 'Permanent_Address'))
                                  <textarea class="changed form-control" id="Permanent_Address" name="Permanent_Address" placeholder="">{{$user->Permanent_Address}}</textarea>
                                @else
                                  <textarea class="form-control" id="Permanent_Address" name="Permanent_Address" placeholder="">{{$user->Permanent_Address}}</textarea>
                                @endif
                              </div>
                            </div>
                          </div>

                          <div class="row">
                            <div class="form-group">

                              <div class="col-lg-3">
                                <label>Emergency Contact Person 1 : </label>
                              </div>

                              <div class="col-lg-3">

                                @if(str_contains($changes, 'Emergency_Contact_Person'))
                                  <input type="text" class="changed form-control" id="Emergency_Contact_Person" name="Emergency_Contact_Person" value="{{$user->Emergency_Contact_Person}}">
                                @else
                                  <input type="text" class="form-control" id="Emergency_Contact_Person" name="Emergency_Contact_Person" value="{{$user->Emergency_Contact_Person}}">
                                @endif
                              </div>

                              <div class="col-lg-3">
                                <label>Emergency Contact Person 2 : </label>
                              </div>

                              <div class="col-lg-3">

                                @if(str_contains($changes, 'Emergency_Contact_Person_2'))
                                  <input type="text" class="changed form-control" id="Emergency_Contact_Person_2" name="Emergency_Contact_Person_2" value="{{$user->Emergency_Contact_Person_2}}">
                                @else
                                  <input type="text" class="form-control" id="Emergency_Contact_Person_2" name="Emergency_Contact_Person_2" value="{{$user->Emergency_Contact_Person_2}}">
                                @endif
                              </div>

                            </div>
                          </div>

                          <div class="row">
                            <div class="form-group">
                              <div class="col-lg-3">
                                <label>Emergency Contact No : </label>
                              </div>

                              <div class="col-lg-3">

                                @if(str_contains($changes, 'Emergency_Contact_No'))
                                  <input type="text" class="changed form-control" id="Emergency_Contact_No" name="Emergency_Contact_No" placeholder="+60123456789" value="{{$user->Emergency_Contact_No}}">
                                @else
                                  <input type="text" class="form-control" id="Emergency_Contact_No" name="Emergency_Contact_No" placeholder="+60123456789" value="{{$user->Emergency_Contact_No}}">
                                @endif

                              </div>

                              <div class="col-lg-3">
                                <label>Emergency Contact No : </label>
                              </div>

                              <div class="col-lg-3">
                                @if(str_contains($changes, 'Emergency_Contact_No'))
                                  <input type="text" class="changed form-control" id="Emergency_Contact_No_2" name="Emergency_Contact_No_2" placeholder="+60123456789" value="{{$user->Emergency_Contact_No_2}}">
                                @else
                                  <input type="text" class="form-control" id="Emergency_Contact_No_2" name="Emergency_Contact_No_2" placeholder="+60123456789" value="{{$user->Emergency_Contact_No_2}}">
                                @endif
                              </div>

                            </div>
                          </div>

                          <div class="row">
                            <div class="form-group">

                              <div class="col-lg-3">
                                <label>Emergency Contact Relationship : </label>
                              </div>

                              <div class="col-lg-3">

                                @if(str_contains($changes, 'Emergency_Contact_Relationship'))
                                  <input type="text" class="changed form-control" id="Emergency_Contact_Relationship" name="Emergency_Contact_Relationship" value="{{$user->Emergency_Contact_Relationship}}">
                                @else
                                  <input type="text" class="form-control" id="Emergency_Contact_Relationship" name="Emergency_Contact_Relationship" value="{{$user->Emergency_Contact_Relationship}}">
                                @endif
                              </div>

                              <div class="col-lg-3">
                                <label>Emergency Contact Relationship : </label>
                              </div>

                              <div class="col-lg-3">

                                @if(str_contains($changes, 'Emergency_Contact_Relationship'))
                                  <input type="text" class="changed form-control" id="Emergency_Contact_Relationship_2" name="Emergency_Contact_Relationship_2" value="{{$user->Emergency_Contact_Relationship_2}}">
                                @else
                                  <input type="text" class="form-control" id="Emergency_Contact_Relationship_2" name="Emergency_Contact_Relationship_2" value="{{$user->Emergency_Contact_Relationship_2}}">
                                @endif

                              </div>

                            </div>
                          </div>

                          <br>

                          <div class="row">
                            <div class="form-group">
                              <div class="col-lg-3">
                                <label>Bank Name : </label>
                              </div>

                              <div class="col-lg-3">

                                @if(str_contains($changes, 'Bank_Name'))
                                  <select class="changed form-control select" id="Bank_Name" name="Bank_Name" style="width: 100%;">
                                @else
                                  <select class="form-control select2" id="Bank_Name" name="Bank_Name" style="width: 100%;">
                                @endif

                                  <option></option>
                                  @foreach ($options as $key => $option)
                                    @if ($option->Field=="Bank_Name")

                                      <option <?php if($user->Bank_Name == $option->Option) echo ' selected="selected" '; ?>>{{$option->Option}}</option>

                                    @endif
                                  @endforeach
                                </select>
                              </div>

                              <div class="col-lg-3">
                                <label>Bank Account No :</label>
                              </div>

                              <div class="col-lg-3">

                                @if(str_contains($changes, 'Bank_Account_No'))
                                  <input type="text" class="changed form-control" id="Bank_Account_No" name="Bank_Account_No" value="{{$user->Bank_Account_No}}">
                                @else
                                  <input type="text" class="form-control" id="Bank_Account_No" name="Bank_Account_No" value="{{$user->Bank_Account_No}}">
                                @endif
                              </div>

                            </div>
                          </div>

                          <div class="row">
                            <div class="form-group">

                              <div class="col-lg-3">
                                <label>Account Holder Name :</label>
                              </div>

                              <div class="col-lg-3">

                                @if(str_contains($changes, 'Acc_Holder_Name'))
                                  <input type="text" class="changed form-control" id="Acc_Holder_Name" name="Acc_Holder_Name" value="{{$user->Acc_Holder_Name}}">
                                @else
                                  <input type="text" class="form-control" id="Acc_Holder_Name" name="Acc_Holder_Name" value="{{$user->Acc_Holder_Name}}">
                                @endif
                              </div>

                            </div>
                          </div>

                          <div class="row">
                            <div class="form-group">

                              <div class="col-lg-3">
                                <label>EPF No : </label>
                              </div>

                              <div class="col-lg-3">

                                @if(str_contains($changes, 'EPF_No'))
                                  <input type="text" class="changed form-control" id="EPF_No" name="EPF_No" value="{{$user->EPF_No}}">
                                @else
                                  <input type="text" class="form-control" id="EPF_No" name="EPF_No" value="{{$user->EPF_No}}">
                                @endif
                              </div>

                              <div class="col-lg-3">
                                <label>SOCSO No : </label>
                              </div>

                              <div class="col-lg-3">

                                @if(str_contains($changes, 'SOCSO_No'))
                                  <input type="text" class="changed form-control" id="SOCSO_No" name="SOCSO_No" value="{{$user->SOCSO_No}}">
                                @else
                                  <input type="text" class="form-control" id="SOCSO_No" name="SOCSO_No" value="{{$user->SOCSO_No}}">
                                @endif
                              </div>


                            </div>
                          </div>

                          <div class="row">

                            <div class="form-group">


                              <div class="col-lg-3">
                                <label>Income Tax No No : </label>
                              </div>

                              <div class="col-lg-3">

                                @if(str_contains($changes, 'Income_Tax_No'))
                                  <input type="text" class="changed form-control" id="Income_Tax_No" name="Income_Tax_No" value="{{$user->Income_Tax_No}}">
                                @else
                                  <input type="text" class="form-control" id="Income_Tax_No" name="Income_Tax_No" value="{{$user->Income_Tax_No}}">
                                @endif
                              </div>


                            </div>
                          </div>


                    </div>
                </div>
              </div>

              <div class="tab-pane" id="employment">

                <div class="box-body">
                  <table id="employmenttable" class="display" cellspacing="0" width="100%" padding="30px" style="font-size: 13px;">
                      <thead>
                        @if($employmenthistories)
                        <tr class="search">
                          <td align='center'><input type='hidden' class='search_init' /></td>
                          @foreach($employmenthistories as $key=>$values)

                            @if ($key==0)
                            <?php $i = 0; ?>

                            @foreach($values as $field=>$value)
                              @if ($field=="Id")
                                <td align='center'><input type='hidden' class='search_init' /></td>
                              @else
                                <td align='center'><input type='text' class='search_init' /></td>
                              @endif

                            @endforeach

                            @endif

                          @endforeach
                        </tr>
                        @endif

                          <tr>
                            @foreach($employmenthistories as $key=>$value)

                              @if ($key==0)
                                    <td></td>
                                @foreach($value as $field=>$value)
                                    <td/>{{ $field }}</td>
                                @endforeach

                              @endif

                            @endforeach
                          </tr>
                      </thead>
                      <tbody>

                        <?php $i = 0; ?>
                        @foreach($employmenthistories as $employmenthistory)

                          <tr id="row_{{ $i }}">

                                <td></td>
                              @foreach($employmenthistory as $key=>$value)
                                <td>
                                  {{ $value }}
                                </td>
                              @endforeach
                          </tr>
                        @endforeach

                    </tbody>
                      <tfoot></tfoot>
                  </table>
                </div>

              </div>
              <!-- /.tab-pane -->
              <div class="tab-pane" id="experience">
                <table id="experiencetable" class="display" cellspacing="0" width="100%" padding="30px" style="font-size: 13px;">
                    <thead>
                    @if($experiences)
                      <tr class="search">
                        <td align='center'><input type='hidden' class='search_init' /></td>
                        @foreach($experiences as $key=>$values)

                          @if ($key==0)
                          <?php $i = 0; ?>

                          @foreach($values as $field=>$value)
                            @if ($field=="Id")
                              <td align='center'><input type='hidden' class='search_init' /></td>
                            @else
                              <td align='center'><input type='text' class='search_init' /></td>
                            @endif

                          @endforeach

                          @endif

                        @endforeach
                      </tr>

                      @endif

                        <tr>
                          @foreach($experiences as $key=>$value)

                            @if ($key==0)
                                  <th></th>

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

                              <td></td>
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
                    @if($licenses)
                      <tr class="search">
                        <td align='center'><input type='hidden' class='search_init' /></td>
                        @foreach($licenses as $key=>$values)

                          @if ($key==0)
                          <?php $i = 0; ?>

                          @foreach($values as $field=>$value)
                            @if ($field=="Id")
                              <td align='center'><input type='hidden' class='search_init' /></td>
                            @else
                              <td align='center'><input type='text' class='search_init' /></td>
                            @endif

                          @endforeach

                          @endif

                        @endforeach
                      </tr>
                      @endif
                        <tr>
                          @foreach($licenses as $key=>$value)

                            @if ($key==0)
                                  <td></td>
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
                              <td></td>
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


              <div class="tab-pane" id="resume">
                <div class="row">
                  <div class="col-md-12">
                    <p class="text-muted">[Word Document and PDF file only]</p>
                    <br>
                    <div class="form-group">
                      <form enctype="multipart/form-data" id="upload_form2" role="form" method="POST" action="" >
                        <input type="hidden" class="form-control" id="UserId" name="UserId" value="{{$user->Id}}">
                        <input type="file" id="resume" name="resume" accept=".doc,.docx,.pdf">

                      </form>
                    </div>

                    <br>
                    <button type="button" class="btn btn-primary" onclick="uploadresume()">Upload</button>

                    <br>
                    <br>

                      <div id="resumediv">

                          @foreach ($resumes as $resume)

                              <div id="resume{{ $resume->Id }}">
                                  {{ $resume->File_Name}} - [{{$resume->created_at}}]
                                </a>
                                <a download="{{ url($resume->Web_Path) }}" href="{{ url($resume->Web_Path) }}" title="Download">
                                  <button type="button" class="btn btn-primary btn-xs">Download</button>

                                </a>
                                <button type="button" class="btn btn-danger btn-xs" style="background: #d9534f; border-color: #d9534f;" onclick="deleteresume({{$resume->Id }})">Delete</button>
                              </div>

                          @endforeach

                    </div>

                  </div>
                </div>

              </div>

              <div class="tab-pane" id="Export">
                <div class="row">
                  <div class="col-md-12">


                    <form enctype="multipart/form-data" id="upload_form_detail" role="form" method="POST" action="{{ url('/userdetailpdf/') }}/{{ $me->UserId}}" target="_blank">
                      <input type="hidden" name="_token" value="{{ csrf_token() }}">


                      <br>

                        <div class="row">

                          @if ($user->Web_Path)
                          <input type="hidden" class="form-control" id="Profile_Image" name="Profile_Image" value="{{$user->Web_Path}}">
                          @else
                          <input type="hidden" class="form-control" id="Profile_Image" name="Profile_Image" value="{{ URL::to('/') ."/img/default-user.png"  }}}">
                          @endif

                            <br><br>

                              <div class="col-lg-6">
                                 @if ($user->Web_Path)

                                  <img class="profile-user-img img-responsive" name="Profile_Image" id="Profile_Image" src="{{ url($user->Web_Path) }}" alt="User profile picture">
                                @else
                                  <img class="profile-user-img img-responsive" name="Profile_Image" id="Profile_Image" src="{{ URL::to('/') ."/img/default-user.png"  }}" alt="User profile picture">
                                @endif

                              </div>

                              <div class="col-lg-6" style="padding:0;">

                                <div class="col-lg-6">
                                  <label>Staff ID : </label>
                                </div>

                                <div class="col-lg-6">
                                  <input type="text" class="form-control" id="StaffId1" name="StaffId1" value="{{$user->Staff_ID}}">
                                  <input type="hidden" class="form-control" id="UserId" name="UserId" value="{{$user->Id}}" disabled>
                                </div>

                                <div class="col-lg-6">
                                  <label>Name : </label>
                                </div>

                                <div class="col-lg-6">
                                    <input type="text" class="form-control" id="Name1" name="Name1" value="{{$user->Name}}">
                                </div>

                                <div class="col-lg-6">

                                    <label>NRIC/PASSPORT NO/UNION NO</label>

                                </div>

                                <div class="col-lg-6">

                                  <select class="form-control select" id="SelectValue1" name="SelectValue1" style="width: 100%;">
                                    <option></option>
                                    <option value="NRIC">NRIC</option>
                                    <option value="Passport No">Passport No</option>
                                    <option value="Union No">Union No</option>
                                  </select>


                                  <div id="nricfield1">
                                     <input type="text" class="form-control" id="NRIC1" name="NRIC1" value="{{$user->NRIC}}">
                                  </div>

                                  <div id="passportfield1">
                                    <input type="text" class="form-control" id="Passport_No1" name="Passport_No1" value="{{$user->Passport_No}}">
                                  </div>

                                  <div id="unionnofield1">
                                    <input type="text" class="form-control" id="Union_No1" name="Union_No1" value="{{$user->Union_No}}">
                                  </div>

                                </div>

                                <div class="col-lg-6">
                                  <label>Position : </label>
                                </div>

                                <div class="col-lg-6">
                                  <select class="form-control select2" id="Position1" name="Position1" style="width: 100%;">

                                    <option></option>
                                    @foreach ($options as $key => $option)
                                      @if ($option->Field=="Position")

                                        <option <?php if($user->Position == $option->Option) echo ' selected="selected" '; ?>>{{$option->Option}}</option>

                                      @endif
                                    @endforeach
                                  </select>
                                </div>

                                <div class="col-lg-6">
                                  <label>Department : </label>
                                </div>

                                <div class="col-lg-6">
                                  <select class="form-control select2" id="Department1" name="Department1" style="width: 100%;">

                                    <option></option>
                                    @foreach ($projects as $project)

                                        <option <?php if($user->Department == $project->Project_Name) echo ' selected="selected" '; ?>>{{$project->Project_Name}}</option>

                                    @endforeach
                                  </select>

                                </div>

                                <div class="col-lg-6">
                                  <label>Joined Date : </label>
                                </div>

                                <div class="col-lg-6">
                                  <input type="text" class="form-control" id="Joining_Date1" name="Joining_Date1" value="{{$user->Joining_Date}}">
                                </div>

                              </div>

                          </div>

                          <br>

                        <div class="row">

                          <div class="form-group">

                            <div class="col-lg-3">
                              <label>DOB : </label>
                            </div>

                            <div class="col-lg-3">
                              <input type="text" class="form-control" id="DOB1" name="DOB1" value="{{$user->DOB}}">

                            </div>

                            <div class="col-lg-3">
                              <label>Place of Birth : </label>
                            </div>

                            <div class="col-lg-3">
                              <input type="text" class="form-control" id="Place_Of_Birth1" name="Place_Of_Birth1" value="{{$user->Place_Of_Birth}}">
                            </div>

                          </div>

                        </div>

                        <div class="row">
                            <div class="form-group">

                              <div class="col-lg-3">
                                <label>Company Email : </label>
                              </div>

                              <div class="col-lg-3">
                                <input type="text" class="form-control" id="Company_Email1" name="Company_Email1" value="{{$user->Company_Email}}">
                              </div>

                              <div class="col-lg-3">
                                <label>Personal Email : </label>
                              </div>

                              <div class="col-lg-3">
                                <input type="text" class="form-control" id="Personal_Email1" name="Personal_Email1" value="{{$user->Personal_Email}}">
                              </div>

                          </div>
                        </div>

                        <div class="row">
                          <div class="form-group">

                            <div class="col-lg-3">
                              <label>Contact No 1 : </label>
                            </div>

                            <div class="col-lg-3">
                              <input type="text" class="form-control" id="Contact_No_1Z" name="Contact_No_1Z" placeholder="+60123456789" value="{{$user->Contact_No_1}}">
                            </div>

                            <div class="col-lg-3">
                              <label>Contact No 2 : </label>
                            </div>

                            <div class="col-lg-3">
                              <input type="text" class="form-control" id="Contact_No_2Z" name="Contact_No_2Z" placeholder="+60123456789" value="{{$user->Contact_No_2}}">
                            </div>

                          </div>
                        </div>

                        <div class="row">
                          <div class="form-group">

                            <div class="col-lg-3">
                              <label>House Phone No: </label>
                            </div>

                            <div class="col-lg-3">
                              <input type="text" class="form-control" id="House_Phone_No1" name="House_Phone_No1" placeholder="+6033456789" value="{{$user->House_Phone_No}}">
                            </div>

                            <div class="col-lg-3">
                              <label>Marital Status : </label>
                            </div>

                            <div class="col-lg-3">

                              <select class="form-control select2" id="Marital_Status1" name="Marital_Status1" style="width: 100%;">

                                  <option></option>
                                  @foreach ($options as $key => $option)
                                    @if ($option->Field=="Marital_Status")

                                      <option <?php if($user->Marital_Status == $option->Option) echo ' selected="selected" '; ?>>{{$option->Option}}</option>

                                    @endif
                                  @endforeach
                                </select>
                            </div>
                          </div>
                        </div>

                        <div class="row">
                          <div class="form-group">
                            <div class="col-lg-3">
                              <label>Gender : </label>
                            </div>

                            <div class="col-lg-3">

                              <select class="form-control select2" id="Gender1" name="Gender1" style="width: 100%;">

                                  <option></option>
                                  @foreach ($options as $key => $option)
                                    @if ($option->Field=="Gender")

                                      <option <?php if($user->Gender == $option->Option) echo ' selected="selected" '; ?>>{{$option->Option}}</option>

                                    @endif
                                  @endforeach
                                </select>
                            </div>

                            <div class="col-lg-3">
                              <label>Nationality : </label>
                            </div>

                            <div class="col-lg-3">
                              <select class="form-control select2" id="Nationality1" name="Nationality1" style="width: 100%;">

                                <option></option>
                                @foreach ($options as $key => $option)
                                  @if ($option->Field=="Nationality")

                                    <option <?php if($user->Nationality == $option->Option) echo ' selected="selected" '; ?>>{{$option->Option}}</option>

                                  @endif
                                @endforeach
                              </select>
                            </div>

                          </div>
                        </div>

                        <div class="row">
                          <div class="form-group">
                            <div class="col-lg-3">
                              <label>Race : </label>
                            </div>

                            <div class="col-lg-3">

                              <select class="form-control select2" id="Race1" name="Race1" style="width: 100%;">

                                <option></option>
                                @foreach ($options as $key => $option)
                                  @if ($option->Field=="Race")

                                    <option <?php if($user->Race == $option->Option) echo ' selected="selected" '; ?>>{{$option->Option}}</option>

                                  @endif
                                @endforeach
                              </select>
                            </div>

                            <div class="col-lg-3">

                              <label>Religion : </label>
                            </div>

                            <div class="col-lg-3">
                              <select class="form-control select2" id="Religion1" name="Religion1" style="width: 100%;">

                                <option></option>
                                @foreach ($options as $key => $option)
                                  @if ($option->Field=="Religion")

                                    <option <?php if($user->Religion == $option->Option) echo ' selected="selected" '; ?>>{{$option->Option}}</option>

                                  @endif
                                @endforeach
                              </select>
                            </div>
                          </div>
                        </div>

                        <br>

                        <div class="row">
                          <div class="form-group">
                            <div class="col-lg-3">
                              <label>Permanent Address : </label>
                            </div>

                            <div class="col-lg-9">

                              <textarea class="form-control" id="Permanent_Address1" name="Permanent_Address1" placeholder="">{{$user->Permanent_Address}}</textarea>

                            </div>
                          </div>
                        </div>

                        <div class="row">
                          <div class="form-group">

                            <div class="col-lg-3">
                              <label>Emergency Contact Person 1 : </label>
                            </div>

                            <div class="col-lg-3">

                              <input type="text" class="form-control" id="Emergency_Contact_Person1" name="Emergency_Contact_Person1" value="{{$user->Emergency_Contact_Person}}">

                            </div>

                            <div class="col-lg-3">
                              <label>Emergency Contact Person 2 : </label>
                            </div>

                            <div class="col-lg-3">

                              <input type="text" class="form-control" id="Emergency_Contact_Person_2Z" name="Emergency_Contact_Person_2Z" value="{{$user->Emergency_Contact_Person_2}}">

                            </div>

                          </div>
                        </div>

                        <div class="row">
                          <div class="form-group">
                            <div class="col-lg-3">
                              <label>Emergency Contact No 1: </label>
                            </div>

                            <div class="col-lg-3">

                              <input type="text" class="form-control" id="Emergency_Contact_No1" name="Emergency_Contact_No1" placeholder="+60123456789" value="{{$user->Emergency_Contact_No}}">

                            </div>

                            <div class="col-lg-3">
                              <label>Emergency Contact No 2: </label>
                            </div>

                            <div class="col-lg-3">
                              <input type="text" class="form-control" id="Emergency_Contact_No_2Z" name="Emergency_Contact_No_2Z" placeholder="+60123456789" value="{{$user->Emergency_Contact_No_2}}">
                            </div>

                          </div>
                        </div>

                        <div class="row">
                          <div class="form-group">

                            <div class="col-lg-3">
                              <label>Emergency Contact Relationship : </label>
                            </div>

                            <div class="col-lg-3">

                              <input type="text" class="form-control" id="Emergency_Contact_Relationship1" name="Emergency_Contact_Relationship1" value="{{$user->Emergency_Contact_Relationship}}">

                            </div>

                            <div class="col-lg-3">
                              <label>Emergency Contact Relationship : </label>
                            </div>

                            <div class="col-lg-3">

                              <input type="text" class="form-control" id="Emergency_Contact_Relationship_2Z" name="Emergency_Contact_Relationship_2Z" value="{{$user->Emergency_Contact_Relationship_2}}">

                            </div>

                          </div>
                        </div>

                        <br>

                        <div class="row">
                          <div class="form-group">
                            <div class="col-lg-3">
                              <label>Bank Name : </label>
                            </div>

                            <div class="col-lg-3">

                              <select class="form-control select2" id="Bank_Name1" name="Bank_Name1" style="width: 100%;">

                                <option></option>
                                @foreach ($options as $key => $option)
                                  @if ($option->Field=="Bank_Name")

                                    <option <?php if($user->Bank_Name == $option->Option) echo ' selected="selected" '; ?>>{{$option->Option}}</option>

                                  @endif
                                @endforeach
                              </select>
                            </div>

                            <div class="col-lg-3">
                              <label>Bank Account No :</label>
                            </div>

                            <div class="col-lg-3">

                              <input type="text" class="form-control" id="Bank_Account_No1" name="Bank_Account_No1" value="{{$user->Bank_Account_No}}">

                            </div>

                          </div>
                        </div>

                        <div class="row">
                          <div class="form-group">

                            <div class="col-lg-3">
                              <label>Account Holder Name :</label>
                            </div>

                            <div class="col-lg-3">

                              <input type="text" class="form-control" id="Acc_Holder_Name1" name="Acc_Holder_Name1" value="{{$user->Acc_Holder_Name}}">

                            </div>

                          </div>
                        </div>

                        <div class="row">
                          <div class="form-group">

                            <div class="col-lg-3">
                              <label>EPF No : </label>
                            </div>

                            <div class="col-lg-3">

                              <input type="text" class="form-control" id="EPF_No1" name="EPF_No1" value="{{$user->EPF_No}}">

                            </div>

                            <div class="col-lg-3">
                              <label>SOCSO No : </label>
                            </div>

                            <div class="col-lg-3">

                              <input type="text" class="form-control" id="SOCSO_No1" name="SOCSO_No1" value="{{$user->SOCSO_No}}">

                            </div>


                          </div>
                        </div>

                        <div class="row">

                          <div class="form-group">


                            <div class="col-lg-3">
                              <label>Income Tax No No : </label>
                            </div>

                            <div class="col-lg-3">

                              <input type="text" class="form-control" id="Income_Tax_No1" name="Income_Tax_No1" value="{{$user->Income_Tax_No}}">

                            </div>


                          </div>
                        </div>

                        <br>

                        <div class="row">
                          <div class="col-lg-12">

                            <div class="form-group">
                              <table id="familytable1" class="display" cellspacing="0" width="100%" padding="30px" style="font-size: 13px;">
                                  <thead>
                                      <tr>
                                        @foreach($family as $key=>$value)

                                          @if ($key==0)
                                                <td></td>
                                            @foreach($value as $field=>$value)
                                                <td/>{{ $field }}</td>
                                            @endforeach

                                          @endif

                                        @endforeach
                                      </tr>
                                  </thead>
                                  <tbody>

                                    <?php $i = 0; ?>
                                    @foreach($family as $family1)

                                      <tr id="row_{{ $i }}">
                                            <td></td>
                                          @foreach($family1 as $key=>$value)
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

                          </div>
                        </div>



                        <div class="row1" style="height: 40px;">
                          <input type="submit" class="btn btn-danger" style="background: #d9534f; border-color: #d9534f;" style="float:right;" value="Print"></input>
                        </div>

                      </form>


                  </div>
                </div>

              </div>

              <div class="tab-pane" id="qualification">
                <table id="qualificationtable" class="display" cellspacing="0" width="100%" padding="30px" style="font-size: 13px;">
                    <thead>
                      @if($qualifications)
                      <tr class="search">
                        <td align='center'><input type='hidden' class='search_init' /></td>
                        @foreach($qualifications as $key=>$values)
                          @if ($key==0)

                          @foreach($values as $field=>$value)

                            @if ($field=="Id")
                              <td align='center'><input type='hidden' class='search_init' /></td>
                            @else
                              <td align='center'><input type='text' class='search_init' /></td>
                            @endif

                          @endforeach

                          @endif

                        @endforeach
                      </tr>
                      @endif
                        <tr>
                          @foreach($qualifications as $key=>$value)

                            @if ($key==0)
                                <td></td>
                              @foreach($value as $field=>$value)
                                  <td/>{{ $field }}</td>
                              @endforeach

                            @endif

                          @endforeach
                        </tr>
                    </thead>
                    <tbody>

                      <?php $i = 0; ?>
                      @foreach($qualifications as $qualification)

                        <tr id="row_{{ $i }}">
                              <td></td>
                            @foreach($qualification as $key=>$value)
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
                    @if($references)
                      <tr class="search">
                        <td align='center'><input type='hidden' class='search_init' /></td>
                        @foreach($references as $key=>$values)
                          @if ($key==0)

                          @foreach($values as $field=>$value)

                            @if ($field=="Id")
                              <td align='center'><input type='hidden' class='search_init' /></td>
                            @else
                              <td align='center'><input type='text' class='search_init' /></td>
                            @endif

                          @endforeach

                          @endif

                        @endforeach
                      </tr>
                      @endif
                        <tr>
                          @foreach($references as $key=>$value)

                            @if ($key==0)
                                  <td></td>
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
                              <td></td>
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
                    @if($skills)
                      <tr class="search">
                        <td align='center'><input type='hidden' class='search_init' /></td>
                        @foreach($skills as $key=>$values)

                          @if ($key==0)
                          <?php $i = 0; ?>

                          @foreach($values as $field=>$value)
                            @if ($field=="Id")
                              <td align='center'><input type='hidden' class='search_init' /></td>
                            @else
                              <td align='center'><input type='text' class='search_init' /></td>
                            @endif

                          @endforeach

                          @endif

                        @endforeach
                      </tr>
                     @endif
                        <tr>
                          @foreach($skills as $key=>$value)

                            @if ($key==0)
                                  <td></td>
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
                          <td></td>
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

              <div class="tab-pane" id="training">
                <table id="trainingtable" class="display" cellspacing="0" width="100%" padding="30px" style="font-size: 13px;">
                    <thead>
                    @if($trainings)
                      <tr class="search">

                        <td align='center'><input type='hidden' class='search_init' /></td>
                        @foreach($trainings as $key=>$values)

                          @if ($key==0)

                          @foreach($values as $field=>$value)
                            @if ($field=="Id")
                              <td align='center'><input type='hidden' class='search_init' /></td>
                            @else
                              <td align='center'><input type='text' class='search_init' /></td>
                            @endif

                          @endforeach

                          @endif

                        @endforeach
                      </tr>
                      @endif
                        <tr>
                          @foreach($trainings as $key=>$value)

                            @if ($key==0)
                                  <td></td>
                              @foreach($value as $field=>$value)
                                  <td/>{{ $field }}</td>
                              @endforeach

                            @endif

                          @endforeach
                        </tr>
                    </thead>
                    <tbody>

                      <?php $i = 0; ?>
                      @foreach($trainings as $training)

                        <tr id="row_{{ $i }}">

                              <td></td>
                            @foreach($training as $key=>$value)
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

              <div class="tab-pane" id="certificate">
                <table id="certificatetable" class="display" cellspacing="0" width="100%" padding="30px" style="font-size: 13px;">
                    <thead>
                      @if($certificates)
                      <tr class="search">

                        <td align='center'><input type='hidden' class='search_init' /></td>
                        @foreach($certificates as $key=>$values)

                          @if ($key==0)

                          @foreach($values as $field=>$value)
                            @if ($field=="Id")
                              <td align='center'><input type='hidden' class='search_init' /></td>
                            @else
                              <td align='center'><input type='text' class='search_init' /></td>
                            @endif

                          @endforeach

                          @endif

                        @endforeach
                      </tr>
                      @endif
                        <tr>
                          @foreach($certificates as $key=>$value)

                            @if ($key==0)
                                  <td></td>
                              @foreach($value as $field=>$value)
                                  <td/>{{ $field }}</td>
                              @endforeach

                            @endif

                          @endforeach
                        </tr>
                    </thead>
                    <tbody>

                      <?php $i = 0; ?>
                      @foreach($certificates as $certificate)

                        <tr id="row_{{ $i }}">
                              <td></td>
                            @foreach($certificate as $key=>$value)
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

              <div class="tab-pane" id="salary">
                <table id="salarytable" class="display" cellspacing="0" width="100%" padding="30px" style="font-size: 13px;">
                    <thead>
                    @if($salary)
                      <tr class="search">
                        <td align='center'><input type='hidden' class='search_init' /></td>
                        @foreach($salary as $key=>$values)

                          @if ($key==0)
                          <?php $i = 0; ?>

                          @foreach($values as $field=>$value)
                            @if ($field=="Id")
                              <td align='center'><input type='hidden' class='search_init' /></td>
                            @else
                              <td align='center'><input type='text' class='search_init' /></td>
                            @endif

                          @endforeach

                          @endif

                        @endforeach
                      </tr>
                      @endif
                        <tr>
                          @foreach($salary as $key=>$value)

                            @if ($key==0)
                                  <td></td>
                              @foreach($value as $field=>$value)
                                  <td/>{{ $field }}</td>
                              @endforeach

                            @endif

                          @endforeach
                        </tr>
                    </thead>
                    <tbody>

                      <?php $i = 0; ?>
                      @foreach($salary as $salary1)

                        <tr id="row_{{ $i }}">
                              <td></td>
                            @foreach($salary1 as $key=>$value)
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

              <div class="tab-pane" id="review">
                <table id="reviewtable" class="display" cellspacing="0" width="100%" padding="30px" style="font-size: 13px;">
                    <thead>
                    @if($review)
                      <tr class="search">
                        <td align='center'><input type='hidden' class='search_init' /></td>
                        @foreach($review as $key=>$values)

                          @if ($key==0)
                          <?php $i = 0; ?>

                          @foreach($values as $field=>$value)
                            @if ($field=="Id")
                              <td align='center'><input type='hidden' class='search_init' /></td>
                            @else
                              <td align='center'><input type='text' class='search_init' /></td>
                            @endif

                          @endforeach

                          @endif

                        @endforeach
                      </tr>
                      @endif
                        <tr>
                          @foreach($review as $key=>$value)

                            @if ($key==0)
                                  <td></td>
                              @foreach($value as $field=>$value)
                                  <td/>{{ $field }}</td>
                              @endforeach

                            @endif

                          @endforeach
                        </tr>
                    </thead>
                    <tbody>

                      <?php $i = 0; ?>
                      @foreach($review as $reviews)

                        <tr id="row_{{ $i }}">
                              <td></td>
                            @foreach($reviews as $key=>$value)
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

              <div class="tab-pane" id="family">
                <table id="familytable" class="display" cellspacing="0" width="100%" padding="30px" style="font-size: 13px;">
                    <thead>
                    @if($family)
                      <tr class="search">
                        <td align='center'><input type='hidden' class='search_init' /></td>
                        @foreach($family as $key=>$values)

                          @if ($key==0)
                          <?php $i = 0; ?>

                          @foreach($values as $field=>$value)
                            @if ($field=="Id")
                              <td align='center'><input type='hidden' class='search_init' /></td>
                            @else
                              <td align='center'><input type='text' class='search_init' /></td>
                            @endif

                          @endforeach

                          @endif

                        @endforeach
                      </tr>
                      @endif
                        <tr>
                          @foreach($family as $key=>$value)

                            @if ($key==0)
                                  <td></td>
                              @foreach($value as $field=>$value)
                                  <td/>{{ $field }}</td>
                              @endforeach

                            @endif

                          @endforeach
                        </tr>
                    </thead>
                    <tbody>

                      <?php $i = 0; ?>
                      @foreach($family as $family1)

                        <tr id="row_{{ $i }}">
                              <td></td>
                            @foreach($family1 as $key=>$value)
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

              <div class="tab-pane" id="language">
                <table id="languagetable" class="display" cellspacing="0" width="100%" padding="30px" style="font-size: 13px;">
                    <thead>
                    @if($language)
                      <tr class="search">
                        <td align='center'><input type='hidden' class='search_init' /></td>
                        @foreach($language as $key=>$values)

                          @if ($key==0)
                          <?php $i = 0; ?>

                          @foreach($values as $field=>$value)
                            @if ($field=="Id")
                              <td align='center'><input type='hidden' class='search_init' /></td>
                            @else
                              <td align='center'><input type='text' class='search_init' /></td>
                            @endif

                          @endforeach

                          @endif

                        @endforeach
                      </tr>
                      @endif
                        <tr>
                          @foreach($language as $key=>$value)

                            @if ($key==0)
                                  <td></td>
                              @foreach($value as $field=>$value)
                                  <td/>{{ $field }}</td>
                              @endforeach

                            @endif

                          @endforeach
                        </tr>
                    </thead>
                    <tbody>

                      <?php $i = 0; ?>
                      @foreach($language as $language1)

                        <tr id="row_{{ $i }}">
                              <td></td>
                            @foreach($language1 as $key=>$value)
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
    $(function () {

      $('#SelectValue1').on('change', function() {
        if (this.value=="NRIC")
        {
          $("#nricfield1").show();
          $("#passportfield1").hide();
          $("#unionnofield1").hide();

        }
        else if (this.value=="Passport No") {
          $("#passportfield1").show();
          $("#unionnofield1").hide();
          $("#nricfield1").hide();

        }
        else if (this.value=="Union No"){
          $("#unionnofield1").show();
          $("#passportfield1").hide();
          $("#nricfield1").hide();
        }
        else{

        }

      });

      $('#SelectValue').on('change', function() {
        if (this.value=="NRIC")
        {
          $("#nricfield").show();
          $("#passportfield").hide();
          $("#unionnofield").hide();

        }
        else if (this.value=="Passport No") {
          $("#passportfield").show();
          $("#unionnofield").hide();
          $("#nricfield").hide();

        }
        else if (this.value=="Union No"){
          $("#unionnofield").show();
          $("#passportfield").hide();
          $("#nricfield").hide();
        }
        else{

        }

      });



  });
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
      // else if (checkPasswordComplexity(password)!=true)
      // {
      //   $("#exist-alert").show();
      //   $("#changepasswordmessage").html(checkPasswordComplexity(password));
      // }
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
                        $("#update-alert").show();

                        setTimeout(function() {
                          $("#update-alert").fadeOut();
                        }, 6000);
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
      staffid=$('[name="StaffId"]').val();
      // usertype=$('[name="User_Type"]').val();
      name=$('[name="Name"]').val();
      nickname=$('[name="Nick_Name"]').val();
      contactno1=$('[name="Contact_No_1"]').val();
      contactno2=$('[name="Contact_No_2"]').val();
      housephoneno=$('[name="House_Phone_No"]').val();
      companyemail=$('[name="Company_Email"]').val();
      personalemail=$('[name="Personal_Email"]').val();
      nationality=$('[name="Nationality"]').val();
      gender=$('[name="Gender"]').val();
      maritalstatus=$('[name="Marital_Status"]').val();
      permanentaddress=$('[name="Permanent_Address"]').val();
      // currentaddress=$('[name="Current_Address"]').val();
      dob=$('[name="DOB"]').val();
      placebirth=$('[name="Place_Of_Birth"]').val();
      race=$('[name="Race"]').val();
      religion=$('[name="Religion"]').val();
      bankname=$('[name="Bank_Name"]').val();
      bankaccountno=$('[name="Bank_Account_No"]').val();
      accholdername=$('[name="Acc_Holder_Name"]').val();
      epfno=$('[name="EPF_No"]').val();
      socsono=$('[name="SOCSO_No"]').val();
      incometaxno=$('[name="Income_Tax_No"]').val();
      nric=$('[name="NRIC"]').val();
      passportno=$('[name="Passport_No"]').val();
      unionno=$('[name="Union_No"]').val();
      department=$('[name="Department"]').val();
      category=$('[name="Category"]').val();
      company=$('[name="Company"]').val();
      position=$('[name="Position"]').val();
      // grade=$('[name="Grade"]').val();
      // superior=$('[name="Superior"]').val();
      emergencycontactperson=$('[name="Emergency_Contact_Person"]').val();
      emergencycontactrelationship=$('[name="Emergency_Contact_Relationship"]').val();
      emergencycontactno=$('[name="Emergency_Contact_No"]').val();
      emergencycontactperson2=$('[name="Emergency_Contact_Person_2"]').val();
      emergencycontactrelationship2=$('[name="Emergency_Contact_Relationship_2"]').val();
      emergencycontactno2=$('[name="Emergency_Contact_No_2"]').val();
      joiningdate=$('[name="Joining_Date"]').val();
      $.ajax({
                  url: "{{ url('/user/updateprofile') }}",
                  method: "POST",
                  data: {
                    UserId:userid,
                    Name:name,
                    StaffId:staffid,
                    Contact_No_1:contactno1,
                    Contact_No_2:contactno2,
                    House_Phone_No:housephoneno,
                    Company_Email:companyemail,
                    Personal_Email:personalemail,
                    Nationality:nationality,
                    Gender:gender,
                    Marital_Status:maritalstatus,
                    Permanent_Address:permanentaddress,
                    DOB:dob,
                    Place_Of_Birth:placebirth,
                    Race:race,
                    Religion:religion,
                    Bank_Name:bankname,
                    Bank_Account_No:bankaccountno,
                    Acc_Holder_Name:accholdername,
                    EPF_No:epfno,
                    SOCSO_No:socsono,
                    Income_Tax_No:incometaxno,
                    NRIC:nric,
                    Passport_No:passportno,
                    Union_No:unionno,
                    Department:department,
                    Category:category,
                    Company:company,
                    Position:position,
                    Emergency_Contact_Person:emergencycontactperson,
                    Emergency_Contact_Relationship:emergencycontactrelationship,
                    Emergency_Contact_No:emergencycontactno,
                    Emergency_Contact_Person_2:emergencycontactperson2,
                    Emergency_Contact_Relationship_2:emergencycontactrelationship2,
                    Emergency_Contact_No_2:emergencycontactno2,
                    Joining_Date:joiningdate,
                  },
                  success: function(response){
                    if (response==0)
                    {
                      var message ="No update on profile!";
                      $("#warning-alert ul").html(message);
                      $("#warning-alert").show();
                      $('#UpdateProfile').modal('hide')

                      setTimeout(function() {
                        $("#warning-alert").fadeOut();
                      }, 6000);

                      formHasChanged=false;
                    }
                    else {
                      var message ="Profile updated!";
                      $("#update-alert ul").html(message);
                      $("#update-alert").show();

                      setTimeout(function() {
                        $("#update-alert").fadeOut();
                        window.location.reload();
                      }, 6000);

                      $("#Template option:selected").val(response).change();
                      //$("#Template").val(response).change();
                      $("#exist-alert").hide();
                      $('#UpdateProfile').modal('hide')

                      formHasChanged=false;
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
                      var message ="No update on profile picture!";
                      $("#warning-alert ul").html(message);
                      $("#warning-alert").show();
                      $('#UpdateProfilePicture').modal('hide')

                      setTimeout(function() {
                        $("#warning-alert").fadeOut();
                      }, 6000);
                    }
                    else {
                      var message ="Profile picture updated!";
                      $("#update-alert ul").html(message);
                      $("#update-alert").show();
                      $("#Template option:selected").val(response).change();
                      //$("#Template").val(response).change();
                      $("#exist-alert").hide();
                      $('#UpdateProfilePicture').modal('hide')
                      $('#profileimage').attr('src',response);
                      $("#profilepicture").val("");

                      setTimeout(function() {
                        $("#update-alert").fadeOut();
                      }, 6000);
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
    $('#DOB1').datepicker({
      format: 'dd-M-yyyy',
      autoclose: true
    });

    $('#Joining_Date').datepicker({
      format: 'dd-M-yyyy',
      autoclose: true
    });
  });

  function uploadresume() {
      $.ajaxSetup({
         headers: { 'X-CSRF-Token' : $('meta[name=_token]').attr('content') }
      });

      $.ajax({
                  url: "{{ url('/user/uploadresume') }}",
                  method: "POST",
                  contentType: false,
                  processData: false,
                  data:new FormData($("#upload_form2")[0]),
                  success: function(response){
                    if (response==0)
                    {
                      var message ="Failed to upload resume!";
                      $("#warning-alert ul").html(message);
                      $("#warning-alert").show();

                      setTimeout(function() {
                        $("#warning-alert").fadeOut();
                      }, 6000);
                    }
                    else {
                      var message ="Resume uploaded!";
                      $("#update-alert ul").html(message);
                      $("#update-alert").show();

                      setTimeout(function() {
                        $("#update-alert").fadeOut();
                      }, 6000);
                      //$("#Template").val(response).change();
                      $("#exist-alert").hide();
                      $("#resume").val("");

                      var sub=response.split("|");

                      var html="<div id='resume"+sub[0]+"'>";
                      html+=sub[2];
                      html+="</a>";
                      html+="<a download='"+sub[1]+"' href="+sub[1]+"' title='Download'>";
                      html+="<button type='button' class='btn btn-primary btn-xs'>Download</button>";
                      html+="</a>";
                      html+="</div>";

                      $("#resumediv").append(html);

                    }
          }
      });
  }

  function deleteresume(id) {
    $.ajaxSetup({
       headers: { 'X-CSRF-Token' : $('meta[name=_token]').attr('content') }
    });
    $.ajax({
                url: "{{ url('/user/deleteresume') }}",
                method: "POST",
                data: {Id:id},
                success: function(response){
                  if (response==0)
                  {
                    var message ="Failed to delete resume!";
                    $("#warning-alert ul").html(message);
                    $("#warning-alert").modal('show');

                  }
                  else {

                    //$("#Template").val(response).change();
                    // $("#exist-alert").modal('hide');

                    var message ="Resume deleted!";
                    $("#update-alert ul").html(message);
                    $("#update-alert").modal('show');

                    $("#resume"+id).remove();
                  }
        }
    });
  }

  </script>

@endsection
