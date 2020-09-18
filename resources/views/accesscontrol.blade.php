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

      .btn {
          width: 180px;
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

      <script type="text/javascript" language="javascript" class="init">

      var editor; // use a global for the submit and return data rendering in the examples
      var editor2;
      var editor3;
      var oTable;
      var oTable2;
      var oTable3;

      $(document).ready(function() {

            $('#Template option')[1].selected = true;
            $( "#Template" ).change();

            editor = new $.fn.dataTable.Editor( {
                  ajax: {
                     "url": "{{ asset('/Include/accountaccesscontroltemplate.php') }}",
                     "data": {
                         "type": "Staff"
                     }
                   },
                    table: "#alltable",
                    idSrc: "users.Id",
                    fields: [
                            {
                                    label: "Status:",
                                    name: "users.Status",
                                    type: "select",
                                    options: [
                                        { label :"", value: "" },
                                        @foreach($options as $option)
                                          @if ($option->Field=="Status")
                                            { label :"{{$option->Option}}", value: "{{$option->Option}}" },
                                          @endif
                                        @endforeach
                                    ],
                            },{
                                   label: "Staff_ID:",
                                   name: "users.StaffId"
                            },{
                                   label: "Superior:",
                                   name: "users.SuperiorId",
                                   type:  "select",
                                   options: [
                                       { label :"", value: "" },
                                       @foreach($users as $user)
                                           { label :"{{$user->Name}}", value: "{{$user->Id}}" },
                                       @endforeach
                                   ],
                           },{
                                    label: "Country Base:",
                                    name: "users.Country_Base",
                                    type: "select",
                                    options: [
                                        { label :"", value: "" },
                                        @foreach($options as $option)
                                          @if ($option->Field=="Country")
                                            { label :"{{$option->Option}}", value: "{{$option->Option}}" },
                                          @endif
                                        @endforeach
                                    ],
                            }, {
                                    label: "Home Base:",
                                    name: "users.Home_Base",
                                    type: "select",
                                    options: [
                                        { label :"", value: "" },
                                        @foreach($options as $option)
                                          @if ($option->Field=="Home_Base")
                                            { label :"{{$option->Option}}", value: "{{$option->Option}}" },
                                          @endif
                                        @endforeach
                                    ],
                            },{
                                   label: "Template Name:",
                                   name: "users.AccessControlTemplateId",
                                   type:  'select',
                                   options: [
                                       { label :"", value: "0" },
                                       @foreach($templates as $template)
                                           { label :"{{$template->Template_Name}}", value: "{{$template->Id}}" },
                                       @endforeach

                                   ],
                            },{
                                   label: "Active:",
                                   name: "users.Active",
                                   type:  'select',
                                   options: [
                                     { label :"Yes", value: "1" },
                                     { label :"No", value: "0" }
                                   ],
                            },{
                                   label: "Admin:",
                                   name: "users.Admin",
                                   type:  'select',
                                   options: [
                                     { label :"Yes", value: "1" },
                                     { label :"No", value: "0" }
                                   ],
                            },{
                                   label: "Approved:",
                                   name: "users.Approved",
                                   type:  'select',
                                   options: [
                                       { label :"Yes", value: "1" },
                                       { label :"No", value: "0" }
                                   ],
                            }


                    ]
            } );

            editor.on( 'preSubmit', function ( e, o, action ) {
              if ( action == 'edit' ) {
                  var Approved = this.field( 'users.Approved' );
                  var StaffId = this.field( 'users.StaffId' );
                  var Superior = this.field( 'users.SuperiorId' );
                  var HomeBase = this.field( 'users.Home_Base' );
                  var CountryBase = this.field( 'users.Country_Base' );
                  var Template = this.field( 'users.AccessControlTemplateId' );

                  // Only validate user input values - different values indicate that
                  // the end user has not entered a value
                  if (Approved.val()=="1")
                  {
                    if ( ! StaffId.val()) {
                        if (StaffId.val() !=undefined)
                        {
                          Approved.error( 'Staff ID must be given in order to approve!' );
                          Approved.val(0);
                          return false;
                        }

                    }

                  }

                  return true;

              }
          } );

            editor2 = new $.fn.dataTable.Editor( {
                  ajax: {
                     "url": "{{ asset('/Include/accountaccesscontroltemplate.php') }}",
                     "data": {
                         "type": "Assistant Engineer"
                     }
                   },
                    table: "#assistantengineertable",
                    idSrc: "users.Id",
                    fields: [
                            {
                                    label: "Status:",
                                    name: "users.Status",
                                    type: "select",
                                    options: [
                                        { label :"", value: "" },
                                        @foreach($options as $option)
                                          @if ($option->Field=="Status")
                                            { label :"{{$option->Option}}", value: "{{$option->Option}}" },
                                          @endif
                                        @endforeach
                                    ],
                            },{
                                   label: "Staff_ID:",
                                   name: "users.StaffId"
                            },{
                                  label: "Internship Status:",
                                  name: "users.Internship_Status",
                                   type:  "select",
                                   options: [
                                      { label :"", value: "" },
                                       { label :"Pending", value: "Pending" },
                                       { label :"Accepted", value: "Accepted" },
                                       { label :"Rejected", value: "Rejected" }
                                   ],
                           },{
                                  label: "Superior:",
                                  name: "users.SuperiorId",
                                   type:  "select",
                                   options: [
                                       { label :"", value: "" },
                                       @foreach($users as $user)
                                           { label :"{{$user->Name}}", value: "{{$user->Id}}" },
                                       @endforeach
                                   ],
                           }, {
                                    label: "Country Base:",
                                    name: "users.Country_Base",
                                    type: "select",
                                    options: [
                                        { label :"", value: "" },
                                        @foreach($options as $option)
                                          @if ($option->Field=="Country")
                                            { label :"{{$option->Option}}", value: "{{$option->Option}}" },
                                          @endif
                                        @endforeach
                                    ],
                            }, {
                                    label: "Home Base:",
                                    name: "users.Home_Base",
                                    type: "select",
                                    options: [
                                        { label :"", value: "" },
                                        @foreach($options as $option)
                                          @if ($option->Field=="Home_Base")
                                            { label :"{{$option->Option}}", value: "{{$option->Option}}" },
                                          @endif
                                        @endforeach
                                    ],
                            },{
                                   label: "Template Name:",
                                   name: "users.AccessControlTemplateId",
                                   type:  'select',
                                   options: [
                                       { label :"", value: "0" },
                                       @foreach($templates as $template)
                                           { label :"{{$template->Template_Name}}", value: "{{$template->Id}}" },
                                       @endforeach

                                   ],
                            },{
                                   label: "Active:",
                                   name: "users.Active",
                                   type:  'select',
                                   options: [
                                     { label :"Yes", value: "1" },
                                     { label :"No", value: "0" }
                                   ],
                            },{
                                   label: "Admin:",
                                   name: "users.Admin",
                                   type:  'select',
                                   options: [
                                     { label :"Yes", value: "1" },
                                     { label :"No", value: "0" }
                                   ],
                            },{
                                   label: "Approved:",
                                   name: "users.Approved",
                                   type:  'select',
                                   options: [
                                       { label :"Yes", value: "1" },
                                       { label :"No", value: "0" }
                                   ],
                            }


                    ]
            } );

            editor2.on( 'preSubmit', function ( e, o, action ) {
              if ( action == 'edit' ) {
                  var Approved = editor2.field( 'users.Approved' );
                  var StaffId = editor2.field( 'users.StaffId' );
                  var HomeBase = editor2.field( 'users.Home_Base' );
                  var Internship_Status = editor2.field( 'users.Internship_Status' );
                  var CountryBase = editor2.field( 'users.Country_Base' );
                  var Template = editor2.field( 'users.AccessControlTemplateId' );

                  // Only validate user input values - different values indicate that
                  // the end user has not entered a value
                  if (Approved.val()=="1")
                  {
                    if ( ! StaffId.val()) {
                        if (StaffId.val() !=undefined)
                        {
                          Approved.error( 'Staff ID must be given in order to approve!' );
                          Approved.val(0);
                          return false;
                        }

                    }

                    if ( Internship_Status.val()==="Pending" )
                    {
                        Approved.error( 'Pending Internship Status!' );
                        Approved.val(0);
                        return false;
                    }

                    // if ( ! HomeBase.val() ) {
                    //     Approved.error( 'Home Base must be set in order to approve' );
                    //     return false;
                    // }

                    // if ( ! Template.val() ) {
                    //   alert("Template");
                    //     Approved.error( 'Template Name must be set in order to approve' );
                    //     Approved.val(0);
                    //     return false;
                    // }
                  }

              }
          } );

            editor3 = new $.fn.dataTable.Editor( {
                  ajax: {
                     "url": "{{ asset('/Include/accountaccesscontroltemplate.php') }}",
                     "data": {
                         "type": "Contractor"
                     }
                   },
                    table: "#contractortable",
                    idSrc: "users.Id",
                    fields: [
                      {
                              label: "Status:",
                              name: "users.Status",
                              type: "select",
                              options: [
                                  { label :"", value: "" },
                                  @foreach($options as $option)
                                    @if ($option->Field=="Status")
                                      { label :"{{$option->Option}}", value: "{{$option->Option}}" },
                                    @endif
                                  @endforeach
                              ],
                      },{
                                   label: "Staff_ID:",
                                   name: "users.StaffId"
                            },{
                                  label: "Superior:",
                                  name: "users.SuperiorId",
                                   type:  "select",
                                   options: [
                                       { label :"", value: "" },
                                       @foreach($users as $user)
                                           { label :"{{$user->Name}}", value: "{{$user->Id}}" },
                                       @endforeach
                                   ],
                           }, {
                                    label: "Country Base:",
                                    name: "users.Country_Base",
                                    type: "select",
                                    options: [
                                        { label :"", value: "" },
                                        @foreach($options as $option)
                                          @if ($option->Field=="Country")
                                            { label :"{{$option->Option}}", value: "{{$option->Option}}" },
                                          @endif
                                        @endforeach
                                    ],
                            }, {
                                    label: "Home Base:",
                                    name: "users.Home_Base",
                                    type: "select",
                                    options: [
                                        { label :"", value: "" },
                                        @foreach($options as $option)
                                          @if ($option->Field=="Home_Base")
                                            { label :"{{$option->Option}}", value: "{{$option->Option}}" },
                                          @endif
                                        @endforeach
                                    ],
                            },{
                                   label: "Template Name:",
                                   name: "users.AccessControlTemplateId",
                                   type:  'select',
                                   options: [
                                       { label :"", value: "0" },
                                       @foreach($templates as $template)
                                           { label :"{{$template->Template_Name}}", value: "{{$template->Id}}" },
                                       @endforeach

                                   ],
                            },{
                                   label: "Active:",
                                   name: "users.Active",
                                   type:  'select',
                                   options: [
                                     { label :"Yes", value: "1" },
                                     { label :"No", value: "0" }
                                   ],
                            },{
                                   label: "Admin:",
                                   name: "users.Admin",
                                   type:  'select',
                                   options: [
                                     { label :"Yes", value: "1" },
                                     { label :"No", value: "0" }
                                   ],
                            },{
                                   label: "Approved:",
                                   name: "users.Approved",
                                   type:  'select',
                                   options: [
                                       { label :"Yes", value: "1" },
                                       { label :"No", value: "0" }
                                   ],
                            }


                    ]
            } );

            editor3.on( 'preSubmit', function ( e, o, action ) {
              if ( action == 'edit' ) {
                  var Approved = editor3.field( 'users.Approved' );
                  var StaffId = editor3.field( 'users.StaffId' );
                  var HomeBase = editor3.field( 'users.Home_Base' );
                  var CountryBase = editor3.field( 'users.Country_Base' );
                  var Template = editor3.field( 'users.AccessControlTemplateId' );

                  // Only validate user input values - different values indicate that
                  // the end user has not entered a value
                  if (Approved.val()=="1")
                  {
                    if ( ! StaffId.val()) {
                        if (StaffId.val() !=undefined)
                        {
                          Approved.error( 'Staff ID must be given in order to approve!' );
                          Approved.val(0);
                          return false;
                        }

                    }

                    // if ( ! Template.val() ) {
                    //     Approved.error( 'Template Name must be set in order to approve' );
                    //     return false;
                    // }
                  }

              }
          } );

            // Activate an inline edit on click of a table cell
            $('#alltable').on( 'click', 'tbody td', function (e) {
                  editor.inline( this, {
                 onBlur: 'submit'
                } );
            } );

            editor.on( 'postSubmit', function ( e, json, data ) {

              for (var userid in data.data)
              {

                for (var prop in data.data[userid].users) {

                    if (prop=="Approved" && data.data[userid].users[prop]==1)
                    {
                      $.ajaxSetup({
                         headers: { 'X-CSRF-Token' : $('meta[name=_token]').attr('content') }
                      });

                      $.ajax({
                                  url: "{{ url('/user/approved') }}",
                                  method: "POST",
                                  data: {UserId:userid
                                  },

                                  success: function(response){


                          }
                      });

                    }
                  }

              }


          } );

          $('#assistantengineertable').on( 'click', 'tbody td', function (e) {
                editor2.inline( this, {
               onBlur: 'submit'
              } );
          } );

          editor2.on( 'postSubmit', function ( e, json, data ) {



            for (var userid in data.data)
            {

              for (var prop in data.data[userid].users) {

                  if (prop=="Approved" && data.data[userid].users[prop]==1)
                  {
                    $.ajaxSetup({
                       headers: { 'X-CSRF-Token' : $('meta[name=_token]').attr('content') }
                    });

                    $.ajax({
                                url: "{{ url('/user/approved') }}",
                                method: "POST",
                                data: {UserId:userid
                                },

                                success: function(response){


                        }
                    });

                  }
                }

            }


        } );

        $('#contractortable').on( 'click', 'tbody td', function (e) {
              editor3.inline( this, {
             onBlur: 'submit'
            } );
        } );

        editor3.on( 'postSubmit', function ( e, json, data ) {



          for (var userid in data.data)
          {

            for (var prop in data.data[userid].users) {

                if (prop=="Approved" && data.data[userid].users[prop]==1)
                {
                  $.ajaxSetup({
                     headers: { 'X-CSRF-Token' : $('meta[name=_token]').attr('content') }
                  });

                  $.ajax({
                              url: "{{ url('/user/approved') }}",
                              method: "POST",
                              data: {UserId:userid
                              },

                              success: function(response){


                      }
                  });

                }
              }

          }


      } );

            oTable=$('#alltable').dataTable( {
                    ajax: {
                       "url": "{{ asset('/Include/accountaccesscontroltemplate.php') }}",
                       "data": {
                           "type": "Staff"
                       }
                     },
                     fnRowCallback: function (nRow, aData, iDisplayIndex, iDisplayIndexFull) {

                       if(aData.users.Active=="0")
                       {
                          $('td', nRow).closest('tr').css('color', 'red');
                       }
                       else if(aData.users.Status=="Resigned" || aData.users.Status=="Contract Ended" || aData.users.Status=="Internship Ended")
                       {
                          $('td', nRow).closest('tr').css('color', 'red');
                       }
                       else if(aData.users.Active=="1" && aData.users.Approved=="1")
                       {
                          $('td', nRow).closest('tr').css('color', 'green');
                       }
                       else if(aData.users.Active=="1" && aData.users.Approved=="0")
                       {
                          $('td', nRow).closest('tr').css('color', '#f39c12');
                       }
                       else if(aData.users.Active=="1" && aData.users.Approved=="1")
                       {
                          $('td', nRow).closest('tr').css('color', 'black');
                       }

                      return nRow;
                    },
                    columnDefs: [{ "visible": false, "targets": [1,3,4,7] },{"className": "dt-center", "targets": "_all"}],
                    responsive: false,
                    colReorder: false,
                    //dom: "Brt",
                    rowId:"users.Id",
                    //sScrollX: "100%",
                    //sScrollY: "100%",
                    dom: "Blftp",
                    scrollY: "100%",
                    scrollX: "100%",
                    scrollCollapse: true,
                    iDisplayLength:10,
                    bAutoWidth: true,
                    columns: [
                            {
                               sortable: false,
                               "render": function ( data, type, full, meta ) {
                                   @if ($me->Edit_User)
                                      return '<a href="user/'+full.users.Id+'" >Edit</a>';
                                   @else
                                      return '-';
                                   @endif

                               }
                           },
                            { data: "users.Id"},
                            { data: "users.Status" },
                            { data: "users.Internship_Status" },
                            { data: "users.Internship_End_Date" },
                            { data: "users.Name" },
                            { data: "users.StaffId" },
                            { data: "users.User_Type" },
                            { data: "superior.Name", editField: "users.SuperiorId" },
                            { data: "users.Country_Base" },
                            { data: "users.Home_Base" },
                            { data: "accesscontroltemplates.Template_Name" , editField: "users.AccessControlTemplateId" },
                            { data: "users.Active",
                            "render": function ( data, type, full, meta ) {
                                if (full.users.Active==1)
                                   return 'Yes';
                                else
                                   return 'No';
                                endif

                            }},
                            { data: "users.Admin",
                            "render": function ( data, type, full, meta ) {
                                if (full.users.Admin==1)
                                   return 'Yes';
                                else
                                   return 'No';
                                endif

                            }},
                            { data: "users.Approved",
                            "render": function ( data, type, full, meta ) {
                                if (full.users.Approved==1)
                                   return 'Yes';
                                else
                                   return 'No';
                                endif

                            }}
                    ],
                    autoFill: {
                       editor:  editor,
                       alwaysAsk: true
                   },
                  //  keys: {
                  //      columns: ':not(:first-child)',
                  //      editor:  editor
                  //  },
                   select: true,
                    buttons: [
                      { extend: "remove", editor: editor },


                    ],

        });

        oTable2=$('#assistantengineertable').dataTable( {
              ajax: {
                 "url": "{{ asset('/Include/accountaccesscontroltemplate.php') }}",
                 "data": {
                     "type": "Assistant Engineer"
                 }
               },
               fnRowCallback: function (nRow, aData, iDisplayIndex, iDisplayIndexFull) {

                 if(aData.users.Active=="0")
                 {
                    $('td', nRow).closest('tr').css('color', 'red');
                 }
                 else if(aData.users.Status=="Resigned" || aData.users.Status=="Contract Ended" || aData.users.Status=="Internship Ended")
                 {
                    $('td', nRow).closest('tr').css('color', 'red');
                 }
                 else if(aData.users.Active=="1" && aData.users.Approved=="1")
                 {
                    $('td', nRow).closest('tr').css('color', 'green');
                 }
                 else if(aData.users.Active=="1" && aData.users.Approved=="0")
                 {
                    $('td', nRow).closest('tr').css('color', '#f39c12');
                 }
                 else if(aData.users.Active=="1" && aData.users.Approved=="1")
                 {
                    $('td', nRow).closest('tr').css('color', 'black');
                 }

                return nRow;
              },
                columnDefs: [{ "visible": false, "targets": [1,7,13] },{"className": "dt-center", "targets": "_all"}],
                //columnDefs: [{ "visible": false, "targets": [0,3] },{"className": "dt-center", "targets": "_all"}],
                responsive: false,
                colReorder: false,
                //dom: "Brt",
                rowId:"users.Id",
                //sScrollX: "100%",
                //sScrollY: "100%",
                bAutoWidth: true,
                dom: "Brtip",
                scrollY: "100%",
                scrollX: "100%",
                scrollCollapse: true,
                iDisplayLength:15,
                columns: [
                          {
                             sortable: false,
                             "render": function ( data, type, full, meta ) {
                                 @if ($me->Edit_User)
                                    return '<a href="user/'+full.users.Id+'" >Edit</a>';
                                 @else
                                    return '-';
                                 @endif

                             }
                         },
                        { data: "users.Id"},
                        { data: "users.Status" },
                        { data: "users.Internship_Status" },
                        { data: "users.Internship_End_Date" },
                        { data: "users.Name" },
                        { data: "users.StaffId" },
                        { data: "users.User_Type" },
                        { data: "superior.Name", editField: "users.SuperiorId" },
                        { data: "users.Country_Base" },
                        { data: "users.Home_Base" },
                        { data: "accesscontroltemplates.Template_Name" , editField: "users.AccessControlTemplateId" },
                        { data: "users.Active",
                        "render": function ( data, type, full, meta ) {
                            if (full.users.Active==1)
                               return 'Yes';
                            else
                               return 'No';
                            endif

                        }},
                        { data: "users.Admin",
                        "render": function ( data, type, full, meta ) {
                            if (full.users.Admin==1)
                               return 'Yes';
                            else
                               return 'No';
                            endif

                        }},
                        { data: "users.Approved",
                        "render": function ( data, type, full, meta ) {
                            if (full.users.Approved==1)
                               return 'Yes';
                            else
                               return 'No';
                            endif

                        }}
                ],
                autoFill: {
                   editor:  editor2,
                   alwaysAsk: true
               },
              //  keys: {
              //      columns: ':not(:first-child)',
              //      editor:  editor2
              //  },
               select: true,
                buttons: [
                  { extend: "remove", editor: editor2 },


                ],

    });

    oTable3=$('#contractortable').dataTable( {
            ajax: {
               "url": "{{ asset('/Include/accountaccesscontroltemplate.php') }}",
               "data": {
                   "type": "Contractor"
               }
             },
            columnDefs: [{ "visible": false, "targets": [1,3,4,7,8,9,13] },{"className": "dt-center", "targets": "_all"}],
            //columnDefs: [{ "visible": false, "targets": [0,3] },{"className": "dt-center", "targets": "_all"}],
            responsive: false,
            colReorder: false,
            //dom: "Brt",
            rowId:"users.Id",
            //sScrollX: "100%",
            //sScrollY: "100%",
            bAutoWidth: true,
            dom: "Brtip",
            scrollY: "100%",
            scrollX: "100%",
            scrollCollapse: true,
            iDisplayLength:15,
            columns: [
                      {
                         sortable: false,
                         "render": function ( data, type, full, meta ) {
                             @if ($me->Edit_User)
                                return '<a href="contractor/'+full.users.Id+'" >Edit</a>';
                             @else
                                return '-';
                             @endif

                         }
                     },
                    { data: "users.Id"},
                    { data: "users.Status" },
                    { data: "users.Internship_Status" },
                    { data: "users.Internship_End_Date" },
                    { data: "users.Name" },
                    { data: "users.StaffId" },
                    { data: "users.User_Type" },
                    { data: "superior.Name", editField: "users.SuperiorId" },
                    { data: "users.Country_Base" },
                    { data: "users.Home_Base" },
                    { data: "accesscontroltemplates.Template_Name" , editField: "users.AccessControlTemplateId" },
                    { data: "users.Active",
                    "render": function ( data, type, full, meta ) {
                        if (full.users.Active==1)
                           return 'Yes';
                        else
                           return 'No';
                        endif

                    }},
                    { data: "users.Admin",
                    "render": function ( data, type, full, meta ) {
                        if (full.users.Admin==1)
                           return 'Yes';
                        else
                           return 'No';
                        endif

                    }},
                    { data: "users.Approved",
                    "render": function ( data, type, full, meta ) {
                        if (full.users.Approved==1)
                           return 'Yes';
                        else
                           return 'No';
                        endif

                    }}
            ],
            autoFill: {
               editor:  editor3,
               alwaysAsk: true
           },
          //  keys: {
          //      columns: ':not(:first-child)',
          //      editor:  editor3
          //  },
           select: true,
            buttons: [
              { extend: "remove", editor: editor3 },

            ],

});

        $(".alltable thead input").keyup ( function () {

                /* Filter on the column (the index) of this element */
                if ($('#alltable').length > 0)
                {

                    var colnum=document.getElementById('alltable').rows[0].cells.length;

                    if (this.value=="[empty]")
                    {

                       oTable.fnFilter( '^$', this.name,true,false );
                    }
                    else if (this.value=="[nonempty]")
                    {

                       oTable.fnFilter( '^(?=\\s*\\S).*$', this.name,true,false );
                    }
                    else if (this.value.startsWith("!")==true && this.value.length>1)
                    {

                       oTable.fnFilter( '^(?'+ this.value +').*', this.name,true,false );
                    }
                    else if (this.value.startsWith("!")==false)
                    {
                        oTable.fnFilter( this.value, this.name,true,false );
                    }
                }


        } );

        $(".assistantengineertable thead input").keyup ( function () {

                /* Filter on the column (the index) of this element */
                if ($('#assistantengineertable').length > 0)
                {

                    var colnum=document.getElementById('assistantengineertable').rows[0].cells.length;

                    if (this.value=="[empty]")
                    {

                       oTable2.fnFilter( '^$', this.name,true,false );
                    }
                    else if (this.value=="[nonempty]")
                    {

                       oTable2.fnFilter( '^(?=\\s*\\S).*$', this.name,true,false );
                    }
                    else if (this.value.startsWith("!")==true && this.value.length>1)
                    {

                       oTable2.fnFilter( '^(?'+ this.value +').*', this.name,true,false );
                    }
                    else if (this.value.startsWith("!")==false)
                    {

                        oTable2.fnFilter( this.value, this.name,true,false );
                    }
                }



        } );

        $(".contractortable thead input").keyup ( function () {

                /* Filter on the column (the index) of this element */
                if ($('#contractortable').length > 0)
                {

                    var colnum=document.getElementById('contractortable').rows[0].cells.length;

                    if (this.value=="[empty]")
                    {

                       oTable3.fnFilter( '^$', this.name,true,false );
                    }
                    else if (this.value=="[nonempty]")
                    {

                       oTable3.fnFilter( '^(?=\\s*\\S).*$', this.name,true,false );
                    }
                    else if (this.value.startsWith("!")==true && this.value.length>1)
                    {

                       oTable3.fnFilter( '^(?'+ this.value +').*', this.name,true,false );
                    }
                    else if (this.value.startsWith("!")==false)
                    {
                        oTable3.fnFilter( this.value, this.name,true,false );
                    }
                }


        } );

        $('a[data-toggle="tab"]').on( 'shown.bs.tab', function (e) {
          var target = $(e.target).attr("href") // activated tab


            $.fn.dataTable.tables( {visible: true, api: true} ).columns.adjust();


        } );


    } );

      </script>

@endsection

@section('content')

    <div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
      <h1>
        Access Control
        <small>IT Support</small>
      </h1>
      <ol class="breadcrumb">
        <li><a href="{{ url('/home') }}"><i class="fa fa-dashboard"></i> Home</a></li>
        <li><a href="#">IT Support</a></li>
        <li class="active">Access Control</li>
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

        <div class="col-md-12">

          <div id="update-alert" class="alert alert-success alert-dismissible" style="display:none;">
            <button type="button" class="close" onclick="$('#update-alert').hide()" aria-hidden="true">&times;</button>
            <h4><i class="icon fa fa-check"></i> Alert!</h4>
            <ul>

            </ul>
          </div>

           <div id="warning-alert" class="alert alert-warning alert-dismissible" style="display:none;">
             <button type="button" class="close" onclick="$('#warning-alert').hide()">&times;</button>
             <h4><i class="icon fa fa-ban"></i> Alert!</h4>
             <ul>

             </ul>
           </div>

          <div class="nav-tabs-custom">
            <ul class="nav nav-tabs">
              {{-- <li><a href="#company" data-toggle="tab">Company</a></li>
              <li><a href="#document" data-toggle="tab">Document</a></li>
              <li><a href="#evaluation" data-toggle="tab">Evaluation</a></li> --}}
              <li class="active"><a href="#staff" data-toggle="tab">Staff</a></li>
              <li><a href="#assistantengineer" data-toggle="tab">Assistant Engineer</a></li>
              <li><a href="#contractor" data-toggle="tab">Contractor</a></li>
              <li><a href="#accesscontroltemplate" data-toggle="tab">Access Control Template</a></li>
            </ul>
            <div class="tab-content">
              <div class="active tab-pane" id="staff">

                <div class="box-body">
                    <table id="alltable" class="alltable" cellspacing="0" width="100%" padding="30px" style="font-size: 13px;">
                    <thead>
                        {{-- prepare header search textbox --}}

                        <tr class="search">

                          @foreach($accounts as $key=>$value)

                            @if ($key==0)
                              <?php $i = 0; ?>

                              @foreach($value as $field=>$a)
                                  @if ($i==0|| $i==1 ||$i==3 ||$i==4 ||$i==7)
                                    <th align='center'><input type='hidden' class='search_init' name='{{$i}}' placemark='{{$a}}'></th>
                                  @else
                                    <th align='center'><input type='text' class='search_init' name='{{$i}}'  placemark='{{$a}}'></th>
                                  @endif

                                  <?php $i ++; ?>
                              @endforeach

                              <th align='center'><input type='text' class='search_init' name='{{$i}}'  placemark='{{$a}}'></th>

                            @endif

                          @endforeach
                        </tr>

                        <tr>
                          @foreach($accounts as $key=>$value)

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
                      @foreach($accounts as $account)

                          <tr id="row_{{ $i }}">
                              <td></td>
                              @foreach($account as $key=>$value)
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
              <!-- /.tab-pane -->

              <div class="tab-pane" id="assistantengineer">

                <div class="box-body">
                    <table id="assistantengineertable" class="assistantengineertable" cellspacing="0" width="100%" padding="30px" style="font-size: 13px;">
                    <thead>
                        {{-- prepare header search textbox --}}

                        <tr class="search">

                          @foreach($accounts as $key=>$value)

                            @if ($key==0)
                              <?php $i = 0; ?>

                              @foreach($value as $field=>$a)
                                  @if ($i==0 ||$i==1 || $i==7 || $i==13)
                                    <th align='center'><input type='hidden' class='search_init' name='{{$i}}'/></th>
                                  @else
                                    <th align='center'><input type='text' class='search_init' name='{{$i}}' /></th>
                                  @endif

                                  <?php $i ++; ?>
                              @endforeach

                              <th align='center'><input type='text' class='search_init' name='{{$i}}'  placemark='{{$a}}'></th>

                            @endif

                          @endforeach
                        </tr>

                        <tr>
                          @foreach($accounts as $key=>$value)

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
                      @foreach($accounts as $account)

                          <tr id="row_{{ $i }}">
                              <td></td>
                              @foreach($account as $key=>$value)
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

              <div class="tab-pane" id="contractor">

                <div class="box-body">
                    <table id="contractortable" class="contractortable" cellspacing="0" width="100%" padding="30px" style="font-size: 13px;">
                    <thead>
                        {{-- prepare header search textbox --}}

                        <tr class="search">

                          @foreach($accounts as $key=>$value)

                            @if ($key==0)
                              <?php $i = 0; ?>

                              @foreach($value as $field=>$a)
                                  @if ($i==0 || $i==1 || $i==3 || $i==4|| $i==7 || $i==8 || $i==9 || $i==13)
                                    <th align='center'><input type='hidden' class='search_init' name='{{$i}}'/></th>
                                  @else
                                    <th align='center'><input type='text' class='search_init' name='{{$i}}' /></th>
                                  @endif

                                  <?php $i ++; ?>
                              @endforeach

                              <th align='center'><input type='text' class='search_init' name='{{$i}}'  placemark='{{$a}}'></th>

                            @endif

                          @endforeach
                        </tr>

                        <tr>
                          @foreach($accounts as $key=>$value)

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
                      @foreach($accounts as $account)

                          <tr id="row_{{ $i }}">
                              <td></td>
                              @foreach($account as $key=>$value)
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

              <div class="tab-pane" id="accesscontroltemplate">
                <div class="box-body">


                 <div class="box-body">
                   <div class="form-group">

                     <div class="form-group">
                      <select class="form-control select2" id="Template" name="Template" style="width: 30%;">
                        <option></option>

                        @foreach ($templates as $template)
                           <option value="{{json_encode($template)}}">{{$template->Template_Name}}</option>
                        @endforeach
                      </select>
                    </div>

                   </div>

                   <button type="button" class="pull-right btn btn-primary btn-lg" data-toggle="modal" data-target="#SaveTemplate">Save As Template</button>
                   <button type="button" class="btn btn-success btn-lg" data-toggle="modal" onclick="OpenUpdateDialog()">Update Template</button>
                   <button type="button" class="btn btn-danger btn-lg" data-toggle="modal" onclick="OpenRemoveDialog()">Remove Template</button>

               </div>

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

               <div class="modal fade" id="UpdateTemplate" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
                 <div class="modal-dialog" role="document">
                   <div class="modal-content">
                     <div class="modal-header">
                       <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                       <h4 class="modal-title" id="myModalLabel">Update Template</h4>
                     </div>
                     <div class="form-group" padding="10px">
                         <label id="updatetemplatemessage"></label>
                     </div>
                     <div class="modal-footer">
                       <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                       <button type="button" class="btn btn-primary" onclick="updatetemplate()">Update</button>
                     </div>
                   </div>
                 </div>
               </div>

               <div class="modal fade" id="RemoveTemplate" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
                 <div class="modal-dialog" role="document">
                   <div class="modal-content">
                     <div class="modal-header">
                       <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                       <h4 class="modal-title" id="myModalLabel">Remove Template</h4>
                     </div>
                     <div class="form-group" padding="10px">
                         <label id="removetemplatemessage">Are you sure you wish to remove "" Template?</label>
                     </div>
                     <div class="modal-footer">
                       <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                       <button type="button" class="btn btn-primary" onclick="removetemplate()">Remove</button>
                     </div>
                   </div>
                 </div>
               </div>

               <div class="col-md-12">

                 <div class="nav-tabs-custom">
                   <ul class="nav nav-tabs">
                     <li class="active"><a href="#administration" data-toggle="tab">Administration</a></li>
                     <li><a href="#logistic" data-toggle="tab">Logistic</a></li>
                     <li><a href="#humanresource" data-toggle="tab">Human Resource</a></li>
                     <li><a href="#itsupport" data-toggle="tab">IT Support</a></li>
                     <li><a href="#projectmanagement" data-toggle="tab">Project Management</a></li>
                     <li><a href="#salesmanagement" data-toggle="tab">Sales Management</a></li>
                     <li><a href="#deliverymanagement" data-toggle="tab">Delivery Management</a></li>
                   </ul>

                   <div class="tab-content">

                     <div class="active tab-pane" id="administration" width="500px">
                       <div class="box box-success">

                         <input type="hidden" name="Id" value="{{ $access->Id }}"/>

                         <div class="box box-solid box-success">
                           <div class="box-header">
                             Admin
                           </div>
                           <div class="box-body">

                             <div class="form-group">
                               <label>Agreement : </label><span class="text-muted"></span><br>
                               <label>
                                 <input type="radio" name="Agreement" class="flat-green" value="1" <?php if($access->Agreement == "Yes") { echo 'checked="checked"'; } ?>>
                                 Yes
                               </label>
                               <label>
                                 <input type="radio" name="Agreement" class="flat-green" value="0" <?php if($access->Agreement == "No") { echo 'checked="checked"'; } ?>>
                                 No
                               </label>
                             </div>

                             <div class="form-group">
                               <label>Credit Card : </label><span class="text-muted"></span><br>
                               <label>
                                 <input type="radio" name="Credit_Card" class="flat-green" value="1" <?php if($access->Credit_Card == "Yes") { echo 'checked="checked"'; } ?>>
                                 Yes
                               </label>
                               <label>
                                 <input type="radio" name="Credit_Card" class="flat-green" value="0" <?php if($access->Credit_Card == "No") { echo 'checked="checked"'; } ?>>
                                 No
                               </label>
                             </div>

                             <div class="form-group">
                               <label>Filing System : </label><span class="text-muted"></span><br>
                               <label>
                                 <input type="radio" name="Filing_System" class="flat-green" value="1" <?php if($access->Filing_System == "Yes") { echo 'checked="checked"'; } ?>>
                                 Yes
                               </label>
                               <label>
                                 <input type="radio" name="Filing_System" class="flat-green" value="0" <?php if($access->Filing_System == "No") { echo 'checked="checked"'; } ?>>
                                 No
                               </label>
                             </div>

                             <div class="form-group">
                               <label>Fixed Asset : </label><span class="text-muted"></span><br>
                               <label>
                                 <input type="radio" name="Asset_Tracking" class="flat-green" value="1" <?php if($access->Asset_Tracking == "Yes") { echo 'checked="checked"'; } ?>>
                                 Yes
                               </label>
                               <label>
                                 <input type="radio" name="Asset_Tracking" class="flat-green" value="0" <?php if($access->Asset_Tracking == "No") { echo 'checked="checked"'; } ?>>
                                 No
                               </label>
                             </div>

                             <div class="form-group">
                               <label>Insurance : </label><span class="text-muted"></span><br>
                               <label>
                                 <input type="radio" name="Insurance" class="flat-green" value="1" <?php if($access->Insurance == "Yes") { echo 'checked="checked"'; } ?>>
                                 Yes
                               </label>
                               <label>
                                 <input type="radio" name="Insurance" class="flat-green" value="0" <?php if($access->Insurance == "No") { echo 'checked="checked"'; } ?>>
                                 No
                               </label>
                             </div>

                             <div class="form-group">
                               <label>License and Card : </label><span class="text-muted"></span><br>
                               <label>
                                 <input type="radio" name="License" class="flat-green" value="1" <?php if($access->License == "Yes") { echo 'checked="checked"'; } ?>>
                                 Yes
                               </label>
                               <label>
                                 <input type="radio" name="License" class="flat-green" value="0" <?php if($access->License == "No") { echo 'checked="checked"'; } ?>>
                                 No
                               </label>
                             </div>

                             <div class="form-group">
                               <label>Notice Board Management : </label><span class="text-muted"> </span><br>
                               <label>
                                 <input type="radio" name="Notice_Board_Management" class="flat-green" value="1" <?php if($access->Notice_Board_Management == "Yes") { echo 'checked="checked"'; } ?>>
                                 Yes
                               </label>
                               <label>
                                 <input type="radio" name="Notice_Board_Management" class="flat-green" value="0" <?php if($access->Notice_Board_Management == "No") { echo 'checked="checked"'; } ?>>
                                 No
                               </label>
                             </div>

                             <div class="form-group">
                               <label>Phone Bills : </label><span class="text-muted"></span><br>
                               <label>
                                 <input type="radio" name="Phone_Bills" class="flat-green" value="1" <?php if($access->Phone_Bills == "Yes") { echo 'checked="checked"'; } ?>>
                                 Yes
                               </label>
                               <label>
                                 <input type="radio" name="Phone_Bills" class="flat-green" value="0" <?php if($access->Phone_Bills == "No") { echo 'checked="checked"'; } ?>>
                                 No
                               </label>
                             </div>

                             <div class="form-group">
                               <label>Property : </label><span class="text-muted"></span><br>
                               <label>
                                 <input type="radio" name="Property" class="flat-green" value="1" <?php if($access->Property == "Yes") { echo 'checked="checked"'; } ?>>
                                 Yes
                               </label>
                               <label>
                                 <input type="radio" name="Property" class="flat-green" value="0" <?php if($access->Property == "No") { echo 'checked="checked"'; } ?>>
                                 No
                               </label>
                             </div>

                             <div class="form-group">
                               <label>Utility Bills : </label><span class="text-muted"></span><br>
                               <label>
                                 <input type="radio" name="Utility_Bills" class="flat-green" value="1" <?php if($access->Utility_Bills == "Yes") { echo 'checked="checked"'; } ?>>
                                 Yes
                               </label>
                               <label>
                                 <input type="radio" name="Utility_Bills" class="flat-green" value="0" <?php if($access->Utility_Bills == "No") { echo 'checked="checked"'; } ?>>
                                 No
                               </label>
                             </div>

                             <div class="form-group" style="display: none;">
                               <label>Allowance Control : </label><span class="text-muted"> Permission to view, update and delete Allowance Control</span><br>
                               <label>
                                 <input type="radio" name="Allowance_Control" class="flat-green" value="1" <?php if($access->Allowance_Control == "Yes") { echo 'checked="checked"'; } ?>>
                                 Yes
                               </label>
                               <label>
                                 <input type="radio" name="Allowance_Control" class="flat-green" value="0" <?php if($access->Allowance_Control == "No") { echo 'checked="checked"'; } ?>>
                                 No
                               </label>
                             </div>

                             <div class="form-group" style="display: none;">
                               <label>Cut-off Management : </label><span class="text-muted"> Permission to set cut-off date</span><br>
                               <label>
                                 <input type="radio" name="Cutoff_Management" class="flat-green" value="1" <?php if($access->Cutoff_Management == "Yes") { echo 'checked="checked"'; } ?>>
                                 Yes
                               </label>
                               <label>
                                 <input type="radio" name="Cutoff_Management" class="flat-green" value="0" <?php if($access->Cutoff_Management == "No") { echo 'checked="checked"'; } ?>>
                                 No
                               </label>
                             </div>

                           </div>
                         </div>

                         <div class="box box-solid box-success" style="display: none;">
                           <div class="box-header">
                             Others
                           </div>
                           <div class="box-body">

                             <div class="form-group">
                               <label>Import Data : </label><span class="text-muted"> Permission to import data</span><br>
                               <label>
                                 <input type="radio" name="Import_Data" class="flat-green" value="1" <?php if($access->Import_Data == "Yes") { echo 'checked="checked"'; } ?>>
                                 Yes
                               </label>
                               <label>
                                 <input type="radio" name="Import_Data" class="flat-green" value="0" <?php if($access->Import_Data == "No") { echo 'checked="checked"'; } ?>>
                                 No
                               </label>
                             </div>

                           </div>
                         </div>

                       </div>
                     </div>

                     <div class="tab-pane" id="humanresource" width="500px">
                       <div class="box box-success">

                         <div class="box box-solid box-success">
                           <div class="box-header">
                             User Control
                           </div>
                           <div class="box-body">

                             <div class="form-group">
                               <label>View User Profile : </label><span class="text-muted"> Permission to view all user profile</span><br>
                               <label>
                                 <input type="radio" name="View_User_Profile" class="flat-green" value="1" <?php if($access->View_User_Profile == "Yes") { echo 'checked="checked"'; } ?>>
                                 Yes
                               </label>
                               <label>
                                 <input type="radio" name="View_User_Profile" class="flat-green" value="0" <?php if($access->View_User_Profile == "No") { echo 'checked="checked"'; } ?>>
                                 No
                               </label>
                             </div>

                             <div class="form-group">
                               <label>Edit User : </label><span class="text-muted"> Permission to update other account</span><br>
                               <label>
                                 <input type="radio" name="Edit_User" class="flat-green" value="1" <?php if($access->Edit_User == "Yes") { echo 'checked="checked"'; } ?>>
                                 Yes
                               </label>
                               <label>
                                 <input type="radio" name="Edit_User" class="flat-green" value="0" <?php if($access->Edit_User == "No") { echo 'checked="checked"'; } ?>>
                                 No
                               </label>
                             </div>

                             <div class="form-group">
                               <label>Staff Monitoring : </label><span class="text-muted"> Permission to view Staff Monitoring and Staff Location Tracking</span><br>
                               <label>
                                 <input type="radio" name="Staff_Monitoring" class="flat-green" value="1" <?php if($access->Staff_Monitoring == "Yes") { echo 'checked="checked"'; } ?>>
                                 Yes
                               </label>
                               <label>
                                 <input type="radio" name="Staff_Monitoring" class="flat-green" value="0" <?php if($access->Staff_Monitoring == "No") { echo 'checked="checked"'; } ?>>
                                 No
                               </label>
                             </div>

                             <div class="form-group">
                               <label>Edit Time In / Time Out : </label><span class="text-muted"> Permission to edit Staff Time In/ Time Out record</span><br>
                               <label>
                                 <input type="radio" name="Edit_Time" class="flat-green" value="1" <?php if($access->Edit_Time == "Yes") { echo 'checked="checked"'; } ?>>
                                 Yes
                               </label>
                               <label>
                                 <input type="radio" name="Edit_Time" class="flat-green" value="0" <?php if($access->Edit_Time == "No") { echo 'checked="checked"'; } ?>>
                                 No
                               </label>
                             </div>

                           </div>
                         </div>

                         <div class="box box-solid box-success" style="display:none;">
                           <div class="box-header">
                             CV Control
                           </div>
                           <div class="box-body">

                             <div class="form-group">
                               <label>View CV : </label><span class="text-muted"> Permission to View all CV</span><br>
                               <label>
                                 <input type="radio" name="View_CV" class="flat-green" value="1" <?php if($access->View_CV == "Yes") { echo 'checked="checked"'; } ?>>
                                 Yes
                               </label>
                               <label>
                                 <input type="radio" name="View_CV" class="flat-green" value="0" <?php if($access->View_CV == "No") { echo 'checked="checked"'; } ?>>
                                 No
                               </label>
                             </div>

                             <div class="form-group">
                               <label>Edit CV : </label><span class="text-muted"> Permission to update CV for any account</span><br>
                               <label>
                                 <input type="radio" name="Edit_CV" class="flat-green" value="1" <?php if($access->Edit_CV == "Yes") { echo 'checked="checked"'; } ?>>
                                 Yes
                               </label>
                               <label>
                                 <input type="radio" name="Edit_CV" class="flat-green" value="0" <?php if($access->Edit_CV == "No") { echo 'checked="checked"'; } ?>>
                                 No
                               </label>
                             </div>

                             <div class="form-group">
                               <label>Export CV : </label><span class="text-muted"> Permission to export CV for any account</span><br>
                               <label>
                                 <input type="radio" name="Export_CV" class="flat-green" value="1" <?php if($access->Export_CV == "Yes") { echo 'checked="checked"'; } ?>>
                                 Yes
                               </label>
                               <label>
                                 <input type="radio" name="Export_CV" class="flat-green" value="0" <?php if($access->Export_CV == "No") { echo 'checked="checked"'; } ?>>
                                 No
                               </label>
                             </div>
                           </div>
                         </div>

                         <div class="box box-solid box-success"  style="display: none;">
                           <div class="box-header">
                             Contractor Control
                           </div>
                           <div class="box-body">

                             <div class="form-group">
                               <label>View Contractor Profile : </label><span class="text-muted"> Permission to view any contractor</span><br>
                               <label>
                                 <input type="radio" name="View_Contractor_Profile" class="flat-green" value="1" <?php if($access->View_Contractor_Profile == "Yes") { echo 'checked="checked"'; } ?>>
                                 Yes
                               </label>
                               <label>
                                 <input type="radio" name="View_Contractor_Profile" class="flat-green" value="0" <?php if($access->View_Contractor_Profile == "No") { echo 'checked="checked"'; } ?>>
                                 No
                               </label>
                             </div>

                             <div class="form-group">
                               <label>Edit Contractor : </label><span class="text-muted"> Permission to update any contractor</span><br>
                               <label>
                                 <input type="radio" name="Edit_Contractor" class="flat-green" value="1" <?php if($access->Edit_Contractor == "Yes") { echo 'checked="checked"'; } ?>>
                                 Yes
                               </label>
                               <label>
                                 <input type="radio" name="Edit_Contractor" class="flat-green" value="0" <?php if($access->Edit_Contractor == "No") { echo 'checked="checked"'; } ?>>
                                 No
                               </label>
                             </div>

                           </div>
                         </div>

                         <div class="box box-solid box-success" style="display: none;">
                           <div class="box-header">
                             Organization Chart Control
                           </div>
                           <div class="box-body">

                             <div class="form-group">
                               <label>View Organization Chart : </label><span class="text-muted"> Permission to view Orgnization Chart</span><br>
                               <label>
                                 <input type="radio" name="View_Org_Chart" class="flat-green" value="1" <?php if($access->View_Org_Chart == "Yes") { echo 'checked="checked"'; } ?>>
                                 Yes
                               </label>
                               <label>
                                 <input type="radio" name="View_Org_Chart" class="flat-green" value="0" <?php if($access->View_Org_Chart == "No") { echo 'checked="checked"'; } ?>>
                                 No
                               </label>
                             </div>

                             <div class="form-group">
                               <label>Update Organization Chart : </label><span class="text-muted"> Permission to update Organization Chart</span><br>
                               <label>
                                 <input type="radio" name="Update_Org_Chart" class="flat-green" value="1" <?php if($access->Update_Org_Chart == "Yes") { echo 'checked="checked"'; } ?>>
                                 Yes
                               </label>
                               <label>
                                 <input type="radio" name="Update_Org_Chart" class="flat-green" value="0" <?php if($access->Update_Org_Chart == "No") { echo 'checked="checked"'; } ?>>
                                 No
                               </label>
                             </div>
                           </div>
                         </div>

                         <div class="box box-solid box-success" style="display: none;">
                           <div class="box-header">
                             Claim Control
                           </div>
                           <div class="box-body">

                             <div class="form-group">
                               <label>Approve Claim : </label><span class="text-muted"> Permission to approve claim</span><br>
                               <label>
                                 <input type="radio" name="Approve_Claim" class="flat-green" value="1" <?php if($access->Approve_Claim == "Yes") { echo 'checked="checked"'; } ?>>
                                 Yes
                               </label>
                               <label>
                                 <input type="radio" name="Approve_Claim" class="flat-green" value="0" <?php if($access->Approve_Claim == "No") { echo 'checked="checked"'; } ?>>
                                 No
                               </label>
                             </div>

                             <div class="form-group">
                               <label>View All Claim : </label><span class="text-muted"> Permission to view any claim</span><br>
                               <label>
                                 <input type="radio" name="View_All_Claim" class="flat-green" value="1" <?php if($access->View_All_Claim == "Yes") { echo 'checked="checked"'; } ?>>
                                 Yes
                               </label>
                               <label>
                                 <input type="radio" name="View_All_Claim" class="flat-green" value="0" <?php if($access->View_All_Claim == "No") { echo 'checked="checked"'; } ?>>
                                 No
                               </label>
                             </div>

                             <div class="form-group">
                               <label>View Claim Summary : </label><span class="text-muted"> Permission to view claim summary</span><br>
                               <label>
                                 <input type="radio" name="View_Claim_Summary" class="flat-green" value="1" <?php if($access->View_Claim_Summary == "Yes") { echo 'checked="checked"'; } ?>>
                                 Yes
                               </label>
                               <label>
                                 <input type="radio" name="View_Claim_Summary" class="flat-green" value="0" <?php if($access->View_Claim_Summary == "No") { echo 'checked="checked"'; } ?>>
                                 No
                               </label>
                             </div>

                             <div class="form-group">
                               <label>Update Payment Month : </label><span class="text-muted"> Permission to update payment month</span><br>
                               <label>
                                 <input type="radio" name="Update_Payment_Month" class="flat-green" value="1" <?php if($access->Update_Payment_Month == "Yes") { echo 'checked="checked"'; } ?>>
                                 Yes
                               </label>
                               <label>
                                 <input type="radio" name="Update_Payment_Month" class="flat-green" value="0" <?php if($access->Update_Payment_Month == "No") { echo 'checked="checked"'; } ?>>
                                 No
                               </label>
                             </div>

                           </div>
                         </div>

                         <div class="box box-solid box-success">
                           <div class="box-header">
                             Payroll
                           </div>
                           <div class="box-body">

                             <div class="form-group">
                               <label>Payroll : </label><span class="text-muted"></span><br>
                               <label>
                                 <input type="radio" name="Payroll" class="flat-green" value="1" <?php if($access->Payroll == "Yes") { echo 'checked="checked"'; } ?>>
                                 Yes
                               </label>
                               <label>
                                 <input type="radio" name="Payroll" class="flat-green" value="0" <?php if($access->Payroll == "No") { echo 'checked="checked"'; } ?>>
                                 No
                               </label>
                             </div>

                             <div class="form-group">
                               <label>Payslip Management : </label><span class="text-muted"></span><br>
                               <label>
                                 <input type="radio" name="Payslip_Management" class="flat-green" value="1" <?php if($access->Payslip_Management == "Yes") { echo 'checked="checked"'; } ?>>
                                 Yes
                               </label>
                               <label>
                                 <input type="radio" name="Payslip_Management" class="flat-green" value="0" <?php if($access->Payslip_Management == "No") { echo 'checked="checked"'; } ?>>
                                 No
                               </label>
                             </div>


                           </div>
                         </div>

                         <div class="box box-solid box-success">
                           <div class="box-header">
                             OT Control
                           </div>
                           <div class="box-body">

                             <div class="form-group">
                               <label>OT Management HR : </label><span class="text-muted"></span><br>
                               <label>
                                 <input type="radio" name="OT_Management_HR" class="flat-green" value="1" <?php if($access->OT_Management_HR == "Yes") { echo 'checked="checked"'; } ?>>
                                 Yes
                               </label>
                               <label>
                                 <input type="radio" name="OT_Management_HR" class="flat-green" value="0" <?php if($access->OT_Management_HR == "No") { echo 'checked="checked"'; } ?>>
                                 No
                               </label>
                             </div>

                             <div class="form-group">
                               <label>OT Management HOD : </label><span class="text-muted"></span><br>
                               <label>
                                 <input type="radio" name="OT_Management_HOD" class="flat-green" value="1" <?php if($access->OT_Management_HOD == "Yes") { echo 'checked="checked"'; } ?>>
                                 Yes
                               </label>
                               <label>
                                 <input type="radio" name="OT_Management_HOD" class="flat-green" value="0" <?php if($access->OT_Management_HOD == "No") { echo 'checked="checked"'; } ?>>
                                 No
                               </label>
                             </div>
                           </div>
                         </div>

                         <div class="box box-solid box-success">
                           <div class="box-header">
                             Leave Control
                           </div>
                           <div class="box-body">

                             <div class="form-group">
                               <label>Approve Leave : </label><span class="text-muted"> Permission to view approve leave</span><br>
                               <label>
                                 <input type="radio" name="Approve_Leave" class="flat-green" value="1" <?php if($access->Approve_Leave == "Yes") { echo 'checked="checked"'; } ?>>
                                 Yes
                               </label>
                               <label>
                                 <input type="radio" name="Approve_Leave" class="flat-green" value="0" <?php if($access->Approve_Leave == "No") { echo 'checked="checked"'; } ?>>
                                 No
                               </label>
                             </div>

                             <div class="form-group">
                               <label>View All Leave : </label><span class="text-muted"> Permission to view leave application</span><br>
                               <label>
                                 <input type="radio" name="View_All_Leave" class="flat-green" value="1" <?php if($access->View_All_Leave == "Yes") { echo 'checked="checked"'; } ?>>
                                 Yes
                               </label>
                               <label>
                                 <input type="radio" name="View_All_Leave" class="flat-green" value="0" <?php if($access->View_All_Leave == "No") { echo 'checked="checked"'; } ?>>
                                 No
                               </label>
                             </div>

                             <div class="form-group">
                               <label>Delete Leave : </label><span class="text-muted"> Permission to delete leave application</span><br>
                               <label>
                                 <input type="radio" name="Delete_Leave" class="flat-green" value="1" <?php if($access->Delete_Leave == "Yes") { echo 'checked="checked"'; } ?>>
                                 Yes
                               </label>
                               <label>
                                 <input type="radio" name="Delete_Leave" class="flat-green" value="0" <?php if($access->Delete_Leave == "No") { echo 'checked="checked"'; } ?>>
                                 No
                               </label>
                             </div>

                             <div class="form-group">
                               <label>View Leave Summary : </label><span class="text-muted"> Permission to view leave summary</span><br>
                               <label>
                                 <input type="radio" name="View_Leave_Summary" class="flat-green" value="1" <?php if($access->View_Leave_Summary == "Yes") { echo 'checked="checked"'; } ?>>
                                 Yes
                               </label>
                               <label>
                                 <input type="radio" name="View_Leave_Summary" class="flat-green" value="0" <?php if($access->View_Leave_Summary == "No") { echo 'checked="checked"'; } ?>>
                                 No
                               </label>
                             </div>

                             <div class="form-group" style="display: none;">
                               <label>Show Leave To Public : </label><span class="text-muted"> Show your leave status to public</span><br>
                               <label>
                                 <input type="radio" name="Show_Leave_To_Public" class="flat-green" value="1" <?php if($access->Show_Leave_To_Public == "Yes") { echo 'checked="checked"'; } ?>>
                                 Yes
                               </label>
                               <label>
                                 <input type="radio" name="Show_Leave_To_Public" class="flat-green" value="0" <?php if($access->Show_Leave_To_Public == "No") { echo 'checked="checked"'; } ?>>
                                 No
                               </label>
                             </div>

                             <div class="form-group">
                               <label>Leave Entitlement : </label><span class="text-muted"> Permission to set leave entitlement</span><br>
                               <label>
                                 <input type="radio" name="Leave_Entitlement" class="flat-green" value="1" <?php if($access->Leave_Entitlement == "Yes") { echo 'checked="checked"'; } ?>>
                                 Yes
                               </label>
                               <label>
                                 <input type="radio" name="Leave_Entitlement" class="flat-green" value="0" <?php if($access->Leave_Entitlement == "No") { echo 'checked="checked"'; } ?>>
                                 No
                               </label>
                             </div>

                             <div class="form-group">
                               <label>Leave Adjustment : </label><span class="text-muted"> Permission to adjust leave or apply leave for staff</span><br>
                               <label>
                                 <input type="radio" name="Leave_Adjustment" class="flat-green" value="1" <?php if($access->Leave_Adjustment == "Yes") { echo 'checked="checked"'; } ?>>
                                 Yes
                               </label>
                               <label>
                                 <input type="radio" name="Leave_Adjustment" class="flat-green" value="0" <?php if($access->Leave_Adjustment == "No") { echo 'checked="checked"'; } ?>>
                                 No
                               </label>
                             </div>

                             <div class="form-group">
                               <label>Holiday Management : </label><span class="text-muted"> Permission to view, update and delete Holiday</span><br>
                               <label>
                                 <input type="radio" name="Holiday_Management" class="flat-green" value="1" <?php if($access->Holiday_Management == "Yes") { echo 'checked="checked"'; } ?>>
                                 Yes
                               </label>
                               <label>
                                 <input type="radio" name="Holiday_Management" class="flat-green" value="0" <?php if($access->Holiday_Management == "No") { echo 'checked="checked"'; } ?>>
                                 No
                               </label>
                             </div>

                             <div class="form-group">
                               <label>Update Medical Claim : </label><span class="text-muted"> Permission to update medical claim on leave</span><br>
                               <label>
                                 <input type="radio" name="Update_Medical_Claim" class="flat-green" value="1" <?php if($access->Update_Medical_Claim == "Yes") { echo 'checked="checked"'; } ?>>
                                 Yes
                               </label>
                               <label>
                                 <input type="radio" name="Update_Medical_Claim" class="flat-green" value="0" <?php if($access->Update_Medical_Claim == "No") { echo 'checked="checked"'; } ?>>
                                 No
                               </label>
                             </div>

                             <div class="form-group">
                               <label>View MIA List : </label><span class="text-muted"> Permission to update view MIA list</span><br>
                               <label>
                                 <input type="radio" name="View_MIA" class="flat-green" value="1" <?php if($access->View_MIA == "Yes") { echo 'checked="checked"'; } ?>>
                                 Yes
                               </label>
                               <label>
                                 <input type="radio" name="View_MIA" class="flat-green" value="0" <?php if($access->View_MIA == "No") { echo 'checked="checked"'; } ?>>
                                 No
                               </label>
                             </div>

                           </div>
                         </div>

                         <div class="box box-solid box-success" style="display: none;">
                           <div class="box-header">
                             Timesheet Control
                           </div>
                           <div class="box-body">

                             <div class="form-group">
                               <label>Approve Timesheet : </label><span class="text-muted"> Permission to approve timesheet</span><br>
                               <label>
                                 <input type="radio" name="Approve_Timesheet" class="flat-green" value="1" <?php if($access->Approve_Timesheet == "Yes") { echo 'checked="checked"'; } ?>>
                                 Yes
                               </label>
                               <label>
                                 <input type="radio" name="Approve_Timesheet" class="flat-green" value="0" <?php if($access->Approve_Timesheet == "No") { echo 'checked="checked"'; } ?>>
                                 No
                               </label>
                             </div>

                             <div class="form-group">
                               <label>View All Timesheet : </label><span class="text-muted"> Permission to view any timesheet</span><br>
                               <label>
                                 <input type="radio" name="View_All_Timesheet" class="flat-green" value="1" <?php if($access->View_All_Timesheet == "Yes") { echo 'checked="checked"'; } ?>>
                                 Yes
                               </label>
                               <label>
                                 <input type="radio" name="View_All_Timesheet" class="flat-green" value="0" <?php if($access->View_All_Timesheet == "No") { echo 'checked="checked"'; } ?>>
                                 No
                               </label>
                             </div>

                             <div class="form-group">
                               <label>View Timesheet Summary : </label><span class="text-muted"> Permission to view any timesheet summary</span><br>
                               <label>
                                 <input type="radio" name="View_Timesheet_Summary" class="flat-green" value="1" <?php if($access->View_Timesheet_Summary == "Yes") { echo 'checked="checked"'; } ?>>
                                 Yes
                               </label>
                               <label>
                                 <input type="radio" name="View_Timesheet_Summary" class="flat-green" value="0" <?php if($access->View_Timesheet_Summary == "No") { echo 'checked="checked"'; } ?>>
                                 No
                               </label>
                             </div>

                             <div class="form-group">
                               <label>View Allowance : </label><span class="text-muted"> Permission to view allowance</span><br>
                               <label>
                                 <input type="radio" name="View_Allowance" class="flat-green" value="1" <?php if($access->View_Allowance == "Yes") { echo 'checked="checked"'; } ?>>
                                 Yes
                               </label>
                               <label>
                                 <input type="radio" name="View_Allowance" class="flat-green" value="0" <?php if($access->View_Allowance == "No") { echo 'checked="checked"'; } ?>>
                                 No
                               </label>
                             </div>

                             <div class="form-group">
                               <label>Edit Allowance : </label><span class="text-muted"> Permission to view allowance</span><br>
                               <label>
                                 <input type="radio" name="Edit_Allowance" class="flat-green" value="1" <?php if($access->Edit_Allowance == "Yes") { echo 'checked="checked"'; } ?>>
                                 Yes
                               </label>
                               <label>
                                 <input type="radio" name="Edit_Allowance" class="flat-green" value="0" <?php if($access->Edit_Allowance == "No") { echo 'checked="checked"'; } ?>>
                                 No
                               </label>
                             </div>

                             <div class="form-group">
                               <label>Timesheet Required : </label><span class="text-muted"> Required to submit timesheet</span><br>
                               <label>
                                 <input type="radio" name="Timesheet_Required" class="flat-green" value="1" <?php if($access->Timesheet_Required == "Yes") { echo 'checked="checked"'; } ?>>
                                 Yes
                               </label>
                               <label>
                                 <input type="radio" name="Timesheet_Required" class="flat-green" value="0" <?php if($access->Timesheet_Required == "No") { echo 'checked="checked"'; } ?>>
                                 No
                               </label>
                             </div>

                           </div>
                         </div>

                         <div class="box box-solid box-success">
                           <div class="box-header">
                             Loan,Presaving,Deduction,Expenses and Request
                           </div>
                           <div class="box-body">
                             <div class="form-group">
                               <label>View All Staff Loan : </label><span class="text-muted"> Permission to view all staffloan</span><br>
                               <label>
                                 <input type="radio" name="View_All_Staff_Loan" class="flat-green" value="1" <?php if($access->View_All_Staff_Loan == "Yes") { echo 'checked="checked"'; } ?>>
                                 Yes
                               </label>
                               <label>
                                 <input type="radio" name="View_All_Staff_Loan" class="flat-green" value="0" <?php if($access->View_All_Staff_Loan == "No") { echo 'checked="checked"'; } ?>>
                                 No
                               </label>
                             </div>
                             <div class="form-group">
                               <label>Approve Staff Loan : </label><span class="text-muted"> Permission to approve staffloan</span><br>
                               <label>
                                 <input type="radio" name="Approve_Staff_Loan" class="flat-green" value="1" <?php if($access->Approve_Staff_Loan == "Yes") { echo 'checked="checked"'; } ?>>
                                 Yes
                               </label>
                               <label>
                                 <input type="radio" name="Approve_Staff_Loan" class="flat-green" value="0" <?php if($access->Approve_Staff_Loan == "No") { echo 'checked="checked"'; } ?>>
                                 No
                               </label>
                             </div>
                             <div class="form-group">
                               <label>Staff Loan : </label><span class="text-muted"> Permission to manage staffloan</span><br>
                               <label>
                                 <input type="radio" name="Staff_Loan" class="flat-green" value="1" <?php if($access->Staff_Loan == "Yes") { echo 'checked="checked"'; } ?>>
                                 Yes
                               </label>
                               <label>
                                 <input type="radio" name="Staff_Loan" class="flat-green" value="0" <?php if($access->Staff_Loan == "No") { echo 'checked="checked"'; } ?>>
                                 No
                               </label>
                             </div>

                             <div class="form-group">
                               <label>Presaving : </label><span class="text-muted"> Permission to manage Presaving</span><br>
                               <label>
                                 <input type="radio" name="Presaving" class="flat-green" value="1" <?php if($access->Presaving == "Yes") { echo 'checked="checked"'; } ?>>
                                 Yes
                               </label>
                               <label>
                                 <input type="radio" name="Presaving" class="flat-green" value="0" <?php if($access->Presaving == "No") { echo 'checked="checked"'; } ?>>
                                 No
                               </label>
                             </div>

                             <div class="form-group">
                               <label>Deduction : </label><span class="text-muted"> Permission to manage Deduction</span><br>
                               <label>
                                 <input type="radio" name="Deduction" class="flat-green" value="1" <?php if($access->Deduction == "Yes") { echo 'checked="checked"'; } ?>>
                                 Yes
                               </label>
                               <label>
                                 <input type="radio" name="Deduction" class="flat-green" value="0" <?php if($access->Deduction == "No") { echo 'checked="checked"'; } ?>>
                                 No
                               </label>
                             </div>

                             <div class="form-group">
                               <label>Staff Expenses : </label><span class="text-muted"> Permission to manage staff expenses</span><br>
                               <label>
                                 <input type="radio" name="Staff_Expenses" class="flat-green" value="1" <?php if($access->Staff_Expenses == "Yes") { echo 'checked="checked"'; } ?>>
                                 Yes
                               </label>
                               <label>
                                 <input type="radio" name="Staff_Expenses" class="flat-green" value="0" <?php if($access->Staff_Expenses == "No") { echo 'checked="checked"'; } ?>>
                                 No
                               </label>
                             </div>

                             <div class="form-group">
                               <label>Request Management : </label><span class="text-muted"> Permission to manage Request</span><br>
                               <label>
                                 <input type="radio" name="Request_Management" class="flat-green" value="1" <?php if($access->Request_Management == "Yes") { echo 'checked="checked"'; } ?>>
                                 Yes
                               </label>
                               <label>
                                 <input type="radio" name="Request_Management" class="flat-green" value="0" <?php if($access->Request_Management == "No") { echo 'checked="checked"'; } ?>>
                                 No
                               </label>
                             </div>

                           </div>
                         </div>


                         <div class="box box-solid box-success">
                           <div class="box-header">
                             Additionals
                           </div>
                           <div class="box-body">

                             <div class="form-group">
                               <label>View Incentive Summary : </label><span class="text-muted"> Permission to view incentive summary</span><br>
                               <label>
                                 <input type="radio" name="View_Incentive_Summary" class="flat-green" value="1" <?php if($access->View_Incentive_Summary == "Yes") { echo 'checked="checked"'; } ?>>
                                 Yes
                               </label>
                               <label>
                                 <input type="radio" name="View_Incentive_Summary" class="flat-green" value="0" <?php if($access->View_Incentive_Summary == "No") { echo 'checked="checked"'; } ?>>
                                 No
                               </label>
                             </div>

                             <div class="form-group">
                               <label>Driver Incentive Summary: </label><span class="text-muted"> </span><br>
                               <label>
                                 <input type="radio" name="Driver_Incentive_Summary" class="flat-green" value="1" <?php if($access->Driver_Incentive_Summary == "Yes") { echo 'checked="checked"'; } ?>>
                                 Yes
                               </label>
                               <label>
                                 <input type="radio" name="Driver_Incentive_Summary" class="flat-green" value="0" <?php if($access->Driver_Incentive_Summary == "No") { echo 'checked="checked"'; } ?>>
                                 No
                               </label>
                             </div>

                             <div class="form-group">
                               <label>View Medical Claim Summary : </label><span class="text-muted"> Permission to view medical claim summary</span><br>
                               <label>
                                 <input type="radio" name="View_Medical_Claim_Summary" class="flat-green" value="1" <?php if($access->View_Medical_Claim_Summary == "Yes") { echo 'checked="checked"'; } ?>>
                                 Yes
                               </label>
                               <label>
                                 <input type="radio" name="View_Medical_Claim_Summary" class="flat-green" value="0" <?php if($access->View_Medical_Claim_Summary == "No") { echo 'checked="checked"'; } ?>>
                                 No
                               </label>
                             </div>

                             <div class="form-group">
                               <label>View Staff Deduction Detail : </label><span class="text-muted"> Permission to view staff deduction detail</span><br>
                               <label>
                                 <input type="radio" name="View_Staff_Deduction_Detail" class="flat-green" value="1" <?php if($access->View_Staff_Deduction_Detail == "Yes") { echo 'checked="checked"'; } ?>>
                                 Yes
                               </label>
                               <label>
                                 <input type="radio" name="View_Staff_Deduction_Detail" class="flat-green" value="0" <?php if($access->View_Staff_Deduction_Detail == "No") { echo 'checked="checked"'; } ?>>
                                 No
                               </label>
                             </div>

                           </div>
                         </div>

                       </div>

                     </div>

                     <div class="tab-pane" id="logistic" width="500px">
                       <div class="box box-success">

                         <div class="box box-solid box-success">
                           <div class="box-header">
                             Logistic
                           </div>
                           <div class="box-body">

                             <div class="form-group">
                               <label>Motor Vehicle : </label><span class="text-muted"></span><br>
                               <label>
                                 <input type="radio" name="Motor_Vehicle" class="flat-green" value="1" <?php if($access->Motor_Vehicle == "Yes") { echo 'checked="checked"'; } ?>>
                                 Yes
                               </label>
                               <label>
                                 <input type="radio" name="Motor_Vehicle" class="flat-green" value="0" <?php if($access->Motor_Vehicle == "No") { echo 'checked="checked"'; } ?>>
                                 No
                               </label>
                             </div>

                             <div class="form-group">
                               <label>Shell Cards : </label><span class="text-muted"></span><br>
                               <label>
                                 <input type="radio" name="Shell_Cards" class="flat-green" value="1" <?php if($access->Shell_Cards == "Yes") { echo 'checked="checked"'; } ?>>
                                 Yes
                               </label>
                               <label>
                                 <input type="radio" name="Shell_Cards" class="flat-green" value="0" <?php if($access->Shell_Cards == "No") { echo 'checked="checked"'; } ?>>
                                 No
                               </label>
                             </div>

                             <div class="form-group">
                               <label>Summon : </label><span class="text-muted"></span><br>
                               <label>
                                 <input type="radio" name="Summon" class="flat-green" value="1" <?php if($access->Summon == "Yes") { echo 'checked="checked"'; } ?>>
                                 Yes
                               </label>
                               <label>
                                 <input type="radio" name="Summon" class="flat-green" value="0" <?php if($access->Summon == "No") { echo 'checked="checked"'; } ?>>
                                 No
                               </label>
                             </div>

                             <div class="form-group">
                               <label>Touch N Go : </label><span class="text-muted"></span><br>
                               <label>
                                 <input type="radio" name="TouchNGo" class="flat-green" value="1" <?php if($access->TouchNGo == "Yes") { echo 'checked="checked"'; } ?>>
                                 Yes
                               </label>
                               <label>
                                 <input type="radio" name="TouchNGo" class="flat-green" value="0" <?php if($access->TouchNGo == "No") { echo 'checked="checked"'; } ?>>
                                 No
                               </label>
                             </div>

                       </div>

                     </div>


                       </div>
                     </div>

                     <div class="tab-pane" id="itsupport" width="500px">
                       <div class="box box-success">

                         <div class="box box-solid box-success">
                           <div class="box-header">
                             IT Support
                           </div>
                           <div class="box-body">

                         <div class="form-group">
                           <label>Access Control : </label><span class="text-muted"> Permission to view, update and delete Access Control</span><br>
                           <label>
                             <input type="radio" name="Access_Control" class="flat-green" value="1" <?php if($access->Access_Control == "Yes") { echo 'checked="checked"'; } ?>>
                             Yes
                           </label>
                           <label>
                             <input type="radio" name="Access_Control" class="flat-green" value="0" <?php if($access->Access_Control == "No") { echo 'checked="checked"'; } ?>>
                             No
                           </label>
                         </div>

                         <div class="form-group">
                           <label>Approval Control : </label><span class="text-muted"> Permission to view, update and delete Approval Control</span><br>
                           <label>
                             <input type="radio" name="Approval_Control" class="flat-green" value="1" <?php if($access->Approval_Control == "Yes") { echo 'checked="checked"'; } ?>>
                             Yes
                           </label>
                           <label>
                             <input type="radio" name="Approval_Control" class="flat-green" value="0" <?php if($access->Approval_Control == "No") { echo 'checked="checked"'; } ?>>
                             No
                           </label>
                         </div>

                         <div class="form-group">
                           <label>Option Control : </label><span class="text-muted"> Permission to view, update and delete Option Control</span><br>
                           <label>
                             <input type="radio" name="Option_Control" class="flat-green" value="1" <?php if($access->Option_Control == "Yes") { echo 'checked="checked"'; } ?>>
                             Yes
                           </label>
                           <label>
                             <input type="radio" name="Option_Control" class="flat-green" value="0" <?php if($access->Option_Control == "No") { echo 'checked="checked"'; } ?>>
                             No
                           </label>
                         </div>

                         <div class="form-group">
                           <label>View Login Tracking : </label><span class="text-muted"> Permission to view login tracking</span><br>
                           <label>
                             <input type="radio" name="View_Login_Tracking" class="flat-green" value="1" <?php if($access->View_Login_Tracking == "Yes") { echo 'checked="checked"'; } ?>>
                             Yes
                           </label>
                           <label>
                             <input type="radio" name="View_Login_Tracking" class="flat-green" value="0" <?php if($access->View_Login_Tracking == "No") { echo 'checked="checked"'; } ?>>
                             No
                           </label>
                         </div>

                         <div class="form-group">
                           <label>Notification Maintenance : </label><span class="text-muted"> Permission to set notification subscriber</span><br>
                           <label>
                             <input type="radio" name="Notification_Maintenance" class="flat-green" value="1" <?php if($access->Notification_Maintenance == "Yes") { echo 'checked="checked"'; } ?>>
                             Yes
                           </label>
                           <label>
                             <input type="radio" name="Notification_Maintenance" class="flat-green" value="0" <?php if($access->Notification_Maintenance == "No") { echo 'checked="checked"'; } ?>>
                             No
                           </label>
                         </div>

                         <div class="form-group">
                           <label>Service Contact : </label><span class="text-muted"></span><br>
                           <label>
                             <input type="radio" name="Service_Contact" class="flat-green" value="1" <?php if($access->Service_Contact == "Yes") { echo 'checked="checked"'; } ?>>
                             Yes
                           </label>
                           <label>
                             <input type="radio" name="Service_Contact" class="flat-green" value="0" <?php if($access->Service_Contact == "No") { echo 'checked="checked"'; } ?>>
                             No
                           </label>
                         </div>

                         <div class="form-group">
                           <label>IT Services : </label><span class="text-muted"></span><br>
                           <label>
                             <input type="radio" name="IT_Services" class="flat-green" value="1" <?php if($access->IT_Services == "Yes") { echo 'checked="checked"'; } ?>>
                             Yes
                           </label>
                           <label>
                             <input type="radio" name="IT_Services" class="flat-green" value="0" <?php if($access->IT_Services == "No") { echo 'checked="checked"'; } ?>>
                             No
                           </label>
                         </div>

                         <div class="form-group">
                           <label>Printer : </label><span class="text-muted"></span><br>
                           <label>
                             <input type="radio" name="Printer" class="flat-green" value="1" <?php if($access->Printer == "Yes") { echo 'checked="checked"'; } ?>>
                             Yes
                           </label>
                           <label>
                             <input type="radio" name="Printer" class="flat-green" value="0" <?php if($access->Printer == "No") { echo 'checked="checked"'; } ?>>
                             No
                           </label>
                         </div>

                         <div class="form-group">
                           <label>Radius Management : </label><span class="text-muted"></span><br>
                           <label>
                             <input type="radio" name="radius" class="flat-green" value="1" <?php if($access->radius == "Yes") { echo 'checked="checked"'; } ?>>
                             Yes
                           </label>
                           <label>
                             <input type="radio" name="radius" class="flat-green" value="0" <?php if($access->radius == "No") { echo 'checked="checked"'; } ?>>
                             No
                           </label>
                         </div>

                       </div>

                     </div>


                       </div>
                     </div>

                     <div class="tab-pane" id="projectmanagement">

                       <div class="box box-solid box-success">
                         <div class="box-header">
                           Project Management Control
                         </div>
                         <div class="box-body">

                           <div class="form-group">
                             <label>Tracker Management : </label><span class="text-muted"> Permission to manage tracker</span><br>
                             <label>
                               <input type="radio" name="Tracker_Management" class="flat-green" value="1" <?php if($access->Tracker_Management == "Yes") { echo 'checked="checked"'; } ?>>
                               Yes
                             </label>
                             <label>
                               <input type="radio" name="Tracker_Management" class="flat-green" value="0" <?php if($access->Tracker_Management == "No") { echo 'checked="checked"'; } ?>>
                               No
                             </label>
                           </div>

                           <div class="form-group">
                             <label>Add Column : </label><span class="text-muted"> Permission to add column</span><br>
                             <label>
                               <input type="radio" name="Add_Column" class="flat-green" value="1" <?php if($access->Add_Column == "Yes") { echo 'checked="checked"'; } ?>>
                               Yes
                             </label>
                             <label>
                               <input type="radio" name="Add_Column" class="flat-green" value="0" <?php if($access->Add_Column == "No") { echo 'checked="checked"'; } ?>>
                               No
                             </label>
                           </div>

                           <div class="form-group">
                             <label>Delete Column : </label><span class="text-muted"> Permission to delete column</span><br>
                             <label>
                               <input type="radio" name="Delete_Column" class="flat-green" value="1" <?php if($access->Delete_Column == "Yes") { echo 'checked="checked"'; } ?>>
                               Yes
                             </label>
                             <label>
                               <input type="radio" name="Delete_Column" class="flat-green" value="0" <?php if($access->Delete_Column == "No") { echo 'checked="checked"'; } ?>>
                               No
                             </label>
                           </div>

                           <div class="form-group">
                             <label>Update Column : </label><span class="text-muted"> Permission to update column</span><br>
                             <label>
                               <input type="radio" name="Update_Column" class="flat-green" value="1" <?php if($access->Update_Column == "Yes") { echo 'checked="checked"'; } ?>>
                               Yes
                             </label>
                             <label>
                               <input type="radio" name="Update_Column" class="flat-green" value="0" <?php if($access->Update_Column == "No") { echo 'checked="checked"'; } ?>>
                               No
                             </label>
                           </div>

                           <div class="form-group">
                             <label>Reorder Column : </label><span class="text-muted"> Permission to reorder column</span><br>
                             <label>
                               <input type="radio" name="Reorder_Column" class="flat-green" value="1" <?php if($access->Reorder_Column == "Yes") { echo 'checked="checked"'; } ?>>
                               Yes
                             </label>
                             <label>
                               <input type="radio" name="Reorder_Column" class="flat-green" value="0" <?php if($access->Reorder_Column == "No") { echo 'checked="checked"'; } ?>>
                               No
                             </label>
                           </div>

                           <div class="form-group">
                             <label>Add Option : </label><span class="text-muted"> Permission to add option</span><br>
                             <label>
                               <input type="radio" name="Add_Option" class="flat-green" value="1" <?php if($access->Add_Option == "Yes") { echo 'checked="checked"'; } ?>>
                               Yes
                             </label>
                             <label>
                               <input type="radio" name="Add_Option" class="flat-green" value="0" <?php if($access->Add_Option == "No") { echo 'checked="checked"'; } ?>>
                               No
                             </label>
                           </div>

                           <div class="form-group">
                             <label>Import Tracker : </label><span class="text-muted"> Permission to import tracker data</span><br>
                             <label>
                               <input type="radio" name="Import_Tracker" class="flat-green" value="1" <?php if($access->Import_Tracker == "Yes") { echo 'checked="checked"'; } ?>>
                               Yes
                             </label>
                             <label>
                               <input type="radio" name="Import_Tracker" class="flat-green" value="0" <?php if($access->Import_Tracker == "No") { echo 'checked="checked"'; } ?>>
                               No
                             </label>
                           </div>

                           <div class="form-group">
                             <label>Aging Rules Management : </label><span class="text-muted"> Permission to set aging rules</span><br>
                             <label>
                               <input type="radio" name="Aging_Rules_Management" class="flat-green" value="1" <?php if($access->Aging_Rules_Management == "Yes") { echo 'checked="checked"'; } ?>>
                               Yes
                             </label>
                             <label>
                               <input type="radio" name="Aging_Rules_Management" class="flat-green" value="0" <?php if($access->Aging_Rules_Management == "No") { echo 'checked="checked"'; } ?>>
                               No
                             </label>
                           </div>

                           <div class="form-group">
                             <label>Target Rules Management : </label><span class="text-muted"> Permission to set target rules</span><br>
                             <label>
                               <input type="radio" name="Target_Rules_Management" class="flat-green" value="1" <?php if($access->Target_Rules_Management == "Yes") { echo 'checked="checked"'; } ?>>
                               Yes
                             </label>
                             <label>
                               <input type="radio" name="Target_Rules_Management" class="flat-green" value="0" <?php if($access->Target_Rules_Management == "No") { echo 'checked="checked"'; } ?>>
                               No
                             </label>
                           </div>

                           <div class="form-group">
                             <label>Dependency Rules Management : </label><span class="text-muted"> Permission to set dependency rules</span><br>
                             <label>
                               <input type="radio" name="Dependency_Rules_Management" class="flat-green" value="1" <?php if($access->Dependency_Rules_Management == "Yes") { echo 'checked="checked"'; } ?>>
                               Yes
                             </label>
                             <label>
                               <input type="radio" name="Dependency_Rules_Management" class="flat-green" value="0" <?php if($access->Dependency_Rules_Management == "No") { echo 'checked="checked"'; } ?>>
                               No
                             </label>
                           </div>

                           <div class="form-group">
                             <label>Auto Date Management : </label><span class="text-muted"> Permission to set auto date rules</span><br>
                             <label>
                               <input type="radio" name="Auto_Date_Management" class="flat-green" value="1" <?php if($access->Auto_Date_Management == "Yes") { echo 'checked="checked"'; } ?>>
                               Yes
                             </label>
                             <label>
                               <input type="radio" name="Auto_Date_Management" class="flat-green" value="0" <?php if($access->Auto_Date_Management == "No") { echo 'checked="checked"'; } ?>>
                               No
                             </label>
                           </div>

                           <div class="form-group">
                             <label>Site Issue : </label><span class="text-muted"> Permission to manage site issue</span><br>
                             <label>
                               <input type="radio" name="Site_Issue" class="flat-green" value="1" <?php if($access->Site_Issue == "Yes") { echo 'checked="checked"'; } ?>>
                               Yes
                             </label>
                             <label>
                               <input type="radio" name="Site_Issue" class="flat-green" value="0" <?php if($access->Site_Issue == "No") { echo 'checked="checked"'; } ?>>
                               No
                             </label>
                           </div>

                           <div class="form-group">
                             <label>Task Management : </label><span class="text-muted"> Permission to manage task</span><br>
                             <label>
                               <input type="radio" name="Task_Management" class="flat-green" value="1" <?php if($access->Task_Management == "Yes") { echo 'checked="checked"'; } ?>>
                               Yes
                             </label>
                             <label>
                               <input type="radio" name="Task_Management" class="flat-green" value="0" <?php if($access->Task_Management == "No") { echo 'checked="checked"'; } ?>>
                               No
                             </label>
                           </div>

                           <div class="form-group" style="display:none;">
                             <label>License Checklist : </label><span class="text-muted"> Permission to view license checklist</span><br>
                             <label>
                               <input type="radio" name="License_Checklist" class="flat-green" value="1" <?php if($access->License_Checklist == "Yes") { echo 'checked="checked"'; } ?>>
                               Yes
                             </label>
                             <label>
                               <input type="radio" name="License_Checklist" class="flat-green" value="0" <?php if($access->License_Checklist == "No") { echo 'checked="checked"'; } ?>>
                               No
                             </label>
                           </div>

                           <div class="form-group">
                             <label>Chart Management : </label><span class="text-muted"> Permission to set chart</span><br>
                             <label>
                               <input type="radio" name="Chart_Management" class="flat-green" value="1" <?php if($access->Chart_Management == "Yes") { echo 'checked="checked"'; } ?>>
                               Yes
                             </label>
                             <label>
                               <input type="radio" name="Chart_Management" class="flat-green" value="0" <?php if($access->Chart_Management == "No") { echo 'checked="checked"'; } ?>>
                               No
                             </label>
                           </div>

                           <div class="form-group">
                             <label>Template Access : </label><span class="text-muted"> Permission to set account template access</span><br>
                             <label>
                               <input type="radio" name="Template_Access" class="flat-green" value="1" <?php if($access->Template_Access == "Yes") { echo 'checked="checked"'; } ?>>
                               Yes
                             </label>
                             <label>
                               <input type="radio" name="Template_Access" class="flat-green" value="0" <?php if($access->Template_Access == "No") { echo 'checked="checked"'; } ?>>
                               No
                             </label>
                           </div>

                           <div class="form-group">
                             <label>Schedules : </label><span class="text-muted"> Permission to set schedules</span><br>
                             <label>
                               <input type="radio" name="Schedule" class="flat-green" value="1" <?php if($access->Schedule == "Yes") { echo 'checked="checked"'; } ?>>
                               Yes
                             </label>
                             <label>
                               <input type="radio" name="Schedule" class="flat-green" value="0" <?php if($access->Schedule == "No") { echo 'checked="checked"'; } ?>>
                               No
                             </label>
                           </div>

                            <div class="form-group">
                                <label>Transport Charges: </label><span class="text-muted"> </span><br>
                                <label>
                                  <input type="radio" name="Transport_Charges" class="flat-green" value="1" <?php if($access->Transport_Charges == "Yes") { echo 'checked="checked"'; } ?>>
                                  Yes
                                </label>
                                <label>
                                  <input type="radio" name="Transport_Charges" class="flat-green" value="0" <?php if($access->Transport_Charges == "No") { echo 'checked="checked"'; } ?>>
                                  No
                                </label>
                              </div>
                              <div class="form-group">
                              <label>SpeedFreak System : </label><span class="text-muted"> </span><br>
                              <label>
                                <input type="radio" name="Speedfreak" class="flat-green" value="1" <?php if($access->Speedfreak == "Yes") { echo 'checked="checked"'; } ?>>
                                Yes
                              </label>
                              <label>
                                <input type="radio" name="Speedfreak" class="flat-green" value="0" <?php if($access->Speedfreak== "No") { echo 'checked="checked"'; } ?>>
                                No
                              </label>
                            </div>
                             <div class="form-group">
                              <label>SpeedFreak Summary Report : </label><span class="text-muted"> </span><br>
                              <label>
                                <input type="radio" name="Speedfreak_Summary" class="flat-green" value="1" <?php if($access->Speedfreak_Summary == "Yes") { echo 'checked="checked"'; } ?>>
                                Yes
                              </label>
                              <label>
                                <input type="radio" name="Speedfreak_Summary" class="flat-green" value="0" <?php if($access->Speedfreak_Summary== "No") { echo 'checked="checked"'; } ?>>
                                No
                              </label>
                            </div>
                            <div class="form-group">
                              <label>View All Branch : </label><span class="text-muted"> </span><br>
                              <label>
                                <input type="radio" name="View_All_Branch" class="flat-green" value="1" <?php if($access->View_All_Branch == "Yes") { echo 'checked="checked"'; } ?>>
                                Yes
                              </label>
                              <label>
                                <input type="radio" name="View_All_Branch" class="flat-green" value="0" <?php if($access->View_All_Branch== "No") { echo 'checked="checked"'; } ?>>
                                No
                              </label>
                            </div>
                            <div class="form-group">
                              <label>Update Speedfreak Inventory : </label><span class="text-muted"> </span><br>
                              <label>
                                <input type="radio" name="Update_Inventory" class="flat-green" value="1" <?php if($access->Update_Inventory == "Yes") { echo 'checked="checked"'; } ?>>
                                Yes
                              </label>
                              <label>
                                <input type="radio" name="Update_Inventory" class="flat-green" value="0" <?php if($access->Update_Inventory== "No") { echo 'checked="checked"'; } ?>>
                                No
                              </label>
                            </div>
                            <div class="form-group">
                              <label>Storekeeper Management : </label><span class="text-muted"> </span><br>
                              <label>
                                <input type="radio" name="Storekeeper" class="flat-green" value="1" <?php if($access->Storekeeper == "Yes") { echo 'checked="checked"'; } ?>>
                                Yes
                              </label>
                              <label>
                                <input type="radio" name="Storekeeper" class="flat-green" value="0" <?php if($access->Storekeeper== "No") { echo 'checked="checked"'; } ?>>
                                No
                              </label>
                            </div>
                            <div class="form-group">
                              <label>Sales Order : </label><span class="text-muted"> </span><br>
                              <label>
                                <input type="radio" name="Sales_Order" class="flat-green" value="1" <?php if($access->Sales_Order == "Yes") { echo 'checked="checked"'; } ?>>
                                Yes
                              </label>
                              <label>
                                <input type="radio" name="Sales_Order" class="flat-green" value="0" <?php if($access->Sales_Order== "No") { echo 'checked="checked"'; } ?>>
                                No
                              </label>
                            </div>
                            <div class="form-group">
                              <label>Sales Summary : </label><span class="text-muted"> </span><br>
                              <label>
                                <input type="radio" name="Sales_Summary" class="flat-green" value="1" <?php if($access->Sales_Summary == "Yes") { echo 'checked="checked"'; } ?>>
                                Yes
                              </label>
                              <label>
                                <input type="radio" name="Sales_Summary" class="flat-green" value="0" <?php if($access->Sales_Summary== "No") { echo 'checked="checked"'; } ?>>
                                No
                              </label>
                            </div>
                            <div class="form-group">
                              <label>Delete Sales Order : </label><span class="text-muted"> </span><br>
                              <label>
                                <input type="radio" name="Delete_SO" class="flat-green" value="1" <?php if($access->Delete_SO == "Yes") { echo 'checked="checked"'; } ?>>
                                Yes
                              </label>
                              <label>
                                <input type="radio" name="Delete_SO" class="flat-green" value="0" <?php if($access->Delete_SO== "No") { echo 'checked="checked"'; } ?>>
                                No
                              </label>
                            </div>
                            <div class="form-group">
                              <label>Recur Sales Order : </label><span class="text-muted"> </span><br>
                              <label>
                                <input type="radio" name="Recur_SO" class="flat-green" value="1" <?php if($access->Recur_SO == "Yes") { echo 'checked="checked"'; } ?>>
                                Yes
                              </label>
                              <label>
                                <input type="radio" name="Recur_SO" class="flat-green" value="0" <?php if($access->Recur_SO== "No") { echo 'checked="checked"'; } ?>>
                                No
                              </label>
                            </div>
                            <div class="form-group">
                              <label>Sales Order Details : </label><span class="text-muted"> </span><br>
                              <label>
                                <input type="radio" name="SO_Details" class="flat-green" value="1" <?php if($access->SO_Details == "Yes") { echo 'checked="checked"'; } ?>>
                                Yes
                              </label>
                              <label>
                                <input type="radio" name="SO_Details" class="flat-green" value="0" <?php if($access->SO_Details== "No") { echo 'checked="checked"'; } ?>>
                                No
                              </label>
                            </div>
                            <div class="form-group">
                              <label>Dummy Do: </label><span class="text-muted"> </span><br>
                              <label>
                                <input type="radio" name="dummy_do" class="flat-green" value="1" <?php if($access->dummy_do == "Yes") { echo 'checked="checked"'; } ?>>
                                Yes
                              </label>
                              <label>
                                <input type="radio" name="dummy_do" class="flat-green" value="0" <?php if($access->dummy_do == "No") { echo 'checked="checked"'; } ?>>
                                No
                              </label>
                            </div>
                            <div class="form-group">
                              <label>Costing: </label><span class="text-muted"> </span><br>
                              <label>
                                <input type="radio" name="Costing" class="flat-green" value="1" <?php if($access->Costing == "Yes") { echo 'checked="checked"'; } ?>>
                                Yes
                              </label>
                              <label>
                                <input type="radio" name="Costing" class="flat-green" value="0" <?php if($access->Costing == "No") { echo 'checked="checked"'; } ?>>
                                No
                              </label>
                            </div>
                            <div class="form-group">
                              <label>Autocount: </label><span class="text-muted"> </span><br>
                              <label>
                                <input type="radio" name="autocount" class="flat-green" value="1" <?php if($access->autocount == "Yes") { echo 'checked="checked"'; } ?>>
                                Yes
                              </label>
                              <label>
                                <input type="radio" name="autocount" class="flat-green" value="0" <?php if($access->autocount == "No") { echo 'checked="checked"'; } ?>>
                                No
                              </label>
                            </div>
                            <div class="form-group">
                              <label>Delete ROQ : </label><span class="text-muted"> </span><br>
                              <label>
                                <input type="radio" name="Delete_RQO" class="flat-green" value="1" <?php if($access->Delete_RQO == "Yes") { echo 'checked="checked"'; } ?>>
                                Yes
                              </label>
                              <label>
                                <input type="radio" name="Delete_RQO" class="flat-green" value="0" <?php if($access->Delete_RQO== "No") { echo 'checked="checked"'; } ?>>
                                No
                              </label>
                            </div>
                      </div>

                    </div>



                     <div class="box box-solid box-success" style="display: none;">
                       <div class="box-header">
                         Resource Control
                       </div>
                       <div class="box-body">

                           <div class="form-group">
                             <label>View Resource Calendar : </label><span class="text-muted"> Permission to view resource calendar</span><br>
                             <label>
                               <input type="radio" name="View_Resource_Calendar" class="flat-green" value="1" <?php if($access->View_Resource_Calendar == "Yes") { echo 'checked="checked"'; } ?>>
                               Yes
                             </label>
                             <label>
                               <input type="radio" name="View_Resource_Calendar" class="flat-green" value="0" <?php if($access->View_Resource_Calendar == "No") { echo 'checked="checked"'; } ?>>
                               No
                             </label>
                           </div>

                           <div class="form-group">
                             <label>View Resource Summary : </label><span class="text-muted"> Permission to view resource summary</span><br>
                             <label>
                               <input type="radio" name="View_Resource_Summary" class="flat-green" value="1" <?php if($access->View_Resource_Summary == "Yes") { echo 'checked="checked"'; } ?>>
                               Yes
                             </label>
                             <label>
                               <input type="radio" name="View_Resource_Summary" class="flat-green" value="0" <?php if($access->View_Resource_Summary == "No") { echo 'checked="checked"'; } ?>>
                               No
                             </label>
                           </div>

                           <div class="form-group">
                             <label>Resource Allocation : </label><span class="text-muted"> Permission to allocate resource to project</span><br>
                             <label>
                               <input type="radio" name="Resource_Allocation" class="flat-green" value="1" <?php if($access->Resource_Allocation == "Yes") { echo 'checked="checked"'; } ?>>
                               Yes
                             </label>
                             <label>
                               <input type="radio" name="Resource_Allocation" class="flat-green" value="0" <?php if($access->Resource_Allocation == "No") { echo 'checked="checked"'; } ?>>
                               No
                             </label>
                           </div>

                           <div class="form-group">
                             <label>Staff Skill : </label><span class="text-muted"> Permission to update staff skill set</span><br>
                             <label>
                               <input type="radio" name="Staff_Skill" class="flat-green" value="1" <?php if($access->Staff_Skill == "Yes") { echo 'checked="checked"'; } ?>>
                               Yes
                             </label>
                             <label>
                               <input type="radio" name="Staff_Skill" class="flat-green" value="0" <?php if($access->Staff_Skill == "No") { echo 'checked="checked"'; } ?>>
                               No
                             </label>
                           </div>

                         </div>
                       </div>

                       <div class="box box-solid box-success">
                         <div class="box-header">
                           Project Costing
                         </div>
                         <div class="box-body">

                           <div class="form-group">
                              <label>Budget Costing: </label><span class="text-muted"> </span><br>
                              <label>
                                <input type="radio" name="Material_Request" class="flat-green" value="1" <?php if($access->Material_Request == "Yes") { echo 'checked="checked"'; } ?>>
                                Yes
                              </label>
                              <label>
                                <input type="radio" name="Material_Request" class="flat-green" value="0" <?php if($access->Material_Request == "No") { echo 'checked="checked"'; } ?>>
                                No
                              </label>
                            </div>
                            <div class="form-group">
                              <label>Recall MR : </label><span class="text-muted"> </span><br>
                              <label>
                                <input type="radio" name="Recall_MR" class="flat-green" value="1" <?php if($access->Recall_MR == "Yes") { echo 'checked="checked"'; } ?>>
                                Yes
                              </label>
                              <label>
                                <input type="radio" name="Recall_MR" class="flat-green" value="0" <?php if($access->Recall_MR== "No") { echo 'checked="checked"'; } ?>>
                                No
                              </label>
                            </div>
                            <div class="form-group">
                              <label>Material Approval : </label><span class="text-muted"> </span><br>
                              <label>
                                <input type="radio" name="Material_Approval" class="flat-green" value="1" <?php if($access->Material_Approval == "Yes") { echo 'checked="checked"'; } ?>>
                                Yes
                              </label>
                              <label>
                                <input type="radio" name="Material_Approval" class="flat-green" value="0" <?php if($access->Material_Approval== "No") { echo 'checked="checked"'; } ?>>
                                No
                              </label>
                            </div>
                            <div class="form-group">
                                <label>View Material Request: </label><span class="text-muted">Permission to view all material request</span><br>
                                <label>
                                  <input type="radio" name="View_Material_Request" class="flat-green" value="1" <?php if($access->View_Material_Request == "Yes") { echo 'checked="checked"'; } ?>>
                                  Yes
                                </label>
                                <label>
                                  <input type="radio" name="View_Material_Request" class="flat-green" value="0" <?php if($access->View_Material_Request == "No") { echo 'checked="checked"'; } ?>>
                                  No
                                </label>
                              </div>
                              <div class="form-group">
                                  <label>Quotation Approval: </label><span class="text-muted"></span><br>
                                  <label>
                                    <input type="radio" name="Quotation_Approval" class="flat-green" value="1" <?php if($access->Quotation_Approval == "Yes") { echo 'checked="checked"'; } ?>>
                                    Yes
                                  </label>
                                  <label>
                                    <input type="radio" name="Quotation_Approval" class="flat-green" value="0" <?php if($access->Quotation_Approval == "No") { echo 'checked="checked"'; } ?>>
                                    No
                                  </label>
                                </div>
                                <div class="form-group">
                                  <label>Upload Quotation: </label><span class="text-muted"></span><br>
                                  <label>
                                    <input type="radio" name="Upload_Quotation" class="flat-green" value="1" <?php if($access->Quotation_Approval == "Yes") { echo 'checked="checked"'; } ?>>
                                    Yes
                                  </label>
                                  <label>
                                    <input type="radio" name="Upload_Quotation" class="flat-green" value="0" <?php if($access->Quotation_Approval == "No") { echo 'checked="checked"'; } ?>>
                                    No
                                  </label>
                                </div>


                           <div class="form-group">
                             <label>E-Wallet : </label><span class="text-muted"> </span><br>
                             <label>
                               <input type="radio" name="ewallet" class="flat-green" value="1" <?php if($access->ewallet == "Yes") { echo 'checked="checked"'; } ?>>
                               Yes
                             </label>
                             <label>
                               <input type="radio" name="ewallet" class="flat-green" value="0" <?php if($access->ewallet== "No") { echo 'checked="checked"'; } ?>>
                               No
                             </label>
                           </div>

                           <div class="form-group">
                              <label>View PM, QS and OSU Costing: </label><span class="text-muted"> </span><br>
                              <label>
                                <input type="radio" name="View_Costing_Summary" class="flat-green" value="1" <?php if($access->View_Costing_Summary == "Yes") { echo 'checked="checked"'; } ?>>
                                Yes
                              </label>
                              <label>
                                <input type="radio" name="View_Costing_Summary" class="flat-green" value="0" <?php if($access->View_Costing_Summary == "No") { echo 'checked="checked"'; } ?>>
                                No
                              </label>
                            </div>

                            <div class="form-group">
                               <label>View OSU Costing: </label><span class="text-muted"> </span><br>
                               <label>
                                 <input type="radio" name="View_OSU_Costing_Summary" class="flat-green" value="1" <?php if($access->View_OSU_Costing_Summary == "Yes") { echo 'checked="checked"'; } ?>>
                                 Yes
                               </label>
                               <label>
                                 <input type="radio" name="View_OSU_Costing_Summary" class="flat-green" value="0" <?php if($access->View_OSU_Costing_Summary == "No") { echo 'checked="checked"'; } ?>>
                                 No
                               </label>
                             </div>

                         </div>
                       </div>

                       <div class="box box-solid box-success">
                         <div class="box-header">
                           Report Store Control
                         </div>
                         <div class="box-body">

                           <div class="form-group">
                             <label>View Report Store : </label><span class="text-muted"> Permission to view report store</span><br>
                             <label>
                               <input type="radio" name="View_Report_Store" class="flat-green" value="1" <?php if($access->View_Report_Store == "Yes") { echo 'checked="checked"'; } ?>>
                               Yes
                             </label>
                             <label>
                               <input type="radio" name="View_Report_Store" class="flat-green" value="0" <?php if($access->View_Report_Store == "No") { echo 'checked="checked"'; } ?>>
                               No
                             </label>
                           </div>

                           <div class="form-group">
                             <label>Delete File : </label><span class="text-muted"> Permission to Delete File</span><br>
                             <label>
                               <input type="radio" name="Delete_File" class="flat-green" value="1" <?php if($access->Delete_File == "Yes") { echo 'checked="checked"'; } ?>>
                               Yes
                             </label>
                             <label>
                               <input type="radio" name="Delete_File" class="flat-green" value="0" <?php if($access->Delete_File == "No") { echo 'checked="checked"'; } ?>>
                               No
                             </label>
                           </div>

                        </div>
                      </div>

                      <div class="box box-solid box-success">
                         <div class="box-header">
                           Task Management
                         </div>
                         <div class="box-body">
                           <div class="form-group">
                             <label>View Dashboard : </label><span class="text-muted"> Permission to view to-do list dashboard</span><br>
                             <label>
                               <input type="radio" name="Todolist_Dashboard" class="flat-green" value="1" <?php if($access->Todolist_Dashboard == "Yes") { echo 'checked="checked"'; } ?>>
                               Yes
                             </label>
                             <label>
                               <input type="radio" name="Todolist_Dashboard" class="flat-green" value="0" <?php if($access->Todolist_Dashboard == "No") { echo 'checked="checked"'; } ?>>
                               No
                             </label>
                           </div>

                           <div class="form-group">
                             <label>View To-do List : </label><span class="text-muted"> Permission to view all to-do list</span><br>
                             <label>
                               <input type="radio" name="View_Todolist" class="flat-green" value="1" <?php if($access->View_Todolist == "Yes") { echo 'checked="checked"'; } ?>>
                               Yes
                             </label>
                             <label>
                               <input type="radio" name="View_Todolist" class="flat-green" value="0" <?php if($access->View_Todolist == "No") { echo 'checked="checked"'; } ?>>
                               No
                             </label>
                           </div>

                           <div class="form-group">
                             <label>Delete To-do List : </label><span class="text-muted"> Permission to delete to-do list</span><br>
                             <label>
                               <input type="radio" name="Delete_Todo" class="flat-green" value="1" <?php if($access->Delete_Todo == "Yes") { echo 'checked="checked"'; } ?>>
                               Yes
                             </label>
                             <label>
                               <input type="radio" name="Delete_Todo" class="flat-green" value="0" <?php if($access->Delete_Todo == "No") { echo 'checked="checked"'; } ?>>
                               No
                             </label>
                           </div>

                           <div class="form-group">
                             <label>CME Dashboard : </label><span class="text-muted"> Permission to view CME Dashboard</span><br>
                             <label>
                               <input type="radio" name="CME_Dashboard" class="flat-green" value="1" <?php if($access->CME_Dashboard == "Yes") { echo 'checked="checked"'; } ?>>
                               Yes
                             </label>
                             <label>
                               <input type="radio" name="CME_Dashboard" class="flat-green" value="0" <?php if($access->CME_Dashboard == "No") { echo 'checked="checked"'; } ?>>
                               No
                             </label>
                           </div>
                           
                           <div class="form-group">
                             <label>Task List : </label><span class="text-muted"> Permission to view tasks list</span><br>
                             <label>
                               <input type="radio" name="Task_List" class="flat-green" value="1" <?php if($access->Task_List == "Yes") { echo 'checked="checked"'; } ?>>
                               Yes
                             </label>
                             <label>
                               <input type="radio" name="Task_List" class="flat-green" value="0" <?php if($access->Task_List == "No") { echo 'checked="checked"'; } ?>>
                               No
                             </label>
                           </div>
                         </div>
                       </div>

                  </div>

                     <div class="tab-pane" id="salesmanagement">

                       <div class="box box-solid box-success">
                         <div class="box-header">
                           PO Control
                         </div>
                         <div class="box-body">

                           <div class="form-group">
                             <label>Create PO : </label><span class="text-muted"> Permission to create new PO</span><br>
                             <label>
                               <input type="radio" name="Create_PO" class="flat-green" value="1" <?php if($access->Create_PO == "Yes") { echo 'checked="checked"'; } ?>>
                               Yes
                             </label>
                             <label>
                               <input type="radio" name="Create_PO" class="flat-green" value="0" <?php if($access->Create_PO == "No") { echo 'checked="checked"'; } ?>>
                               No
                             </label>
                           </div>

                           <div class="form-group">
                             <label>Delete PO : </label><span class="text-muted"> Permission to delete PO</span><br>
                             <label>
                               <input type="radio" name="Delete_PO" class="flat-green" value="1" <?php if($access->Delete_PO == "Yes") { echo 'checked="checked"'; } ?>>
                               Yes
                             </label>
                             <label>
                               <input type="radio" name="Delete_PO" class="flat-green" value="0" <?php if($access->Delete_PO == "No") { echo 'checked="checked"'; } ?>>
                               No
                             </label>
                           </div>

                           <div class="form-group">
                             <label>View PO Management : </label><span class="text-muted"> Permission to view PO Management</span><br>
                             <label>
                               <input type="radio" name="View_PO_Management" class="flat-green" value="1" <?php if($access->View_PO_Management == "Yes") { echo 'checked="checked"'; } ?>>
                               Yes
                             </label>
                             <label>
                               <input type="radio" name="View_PO_Management" class="flat-green" value="0" <?php if($access->View_PO_Management == "No") { echo 'checked="checked"'; } ?>>
                               No
                             </label>
                           </div>

                           <div class="form-group">
                             <label>View PO Summary : </label><span class="text-muted"> Permission to view PO Summary</span><br>
                             <label>
                               <input type="radio" name="View_PO_Summary" class="flat-green" value="1" <?php if($access->View_PO_Summary == "Yes") { echo 'checked="checked"'; } ?>>
                               Yes
                             </label>
                             <label>
                               <input type="radio" name="View_PO_Summary" class="flat-green" value="0" <?php if($access->View_PO_Summary == "No") { echo 'checked="checked"'; } ?>>
                               No
                             </label>
                           </div>

                           <div class="form-group">
                              <label>View PO : </label><span class="text-muted"> Permission to view all PO</span><br>
                              <label>
                                <input type="radio" name="View_PO_Listing" class="flat-green" value="1" <?php if($access->View_PO_Listing == "Yes") { echo 'checked="checked"'; } ?>>
                                Yes
                              </label>
                              <label>
                                <input type="radio" name="View_PO_Listing" class="flat-green" value="0" <?php if($access->View_PO_Listing == "No") { echo 'checked="checked"'; } ?>>
                                No
                              </label>
                            </div>
                            <div class="form-group">
                              <label>Generate PO : </label><span class="text-muted"> Permission to generate PO</span><br>
                              <label>
                                <input type="radio" name="Generate_PO" class="flat-green" value="1" <?php if($access->Generate_PO == "Yes") { echo 'checked="checked"'; } ?>>
                                Yes
                              </label>
                              <label>
                                <input type="radio" name="Generate_PO" class="flat-green" value="0" <?php if($access->Generate_PO == "No") { echo 'checked="checked"'; } ?>>
                                No
                              </label>
                            </div>
                        </div>
                      </div>

                      <div class="box box-solid box-success">
                        <div class="box-header">
                          Invoice Control
                        </div>
                        <div class="box-body">

                           <div class="form-group">
                             <label>Create Invoice : </label><span class="text-muted"> Permission to create new Invoice</span><br>
                             <label>
                               <input type="radio" name="Create_Invoice" class="flat-green" value="1" <?php if($access->Create_Invoice == "Yes") { echo 'checked="checked"'; } ?>>
                               Yes
                             </label>
                             <label>
                               <input type="radio" name="Create_Invoice" class="flat-green" value="0" <?php if($access->Create_Invoice == "No") { echo 'checked="checked"'; } ?>>
                               No
                             </label>
                           </div>

                           <div class="form-group">
                             <label>Delete Invoice : </label><span class="text-muted"> Permission to delete Invoice</span><br>
                             <label>
                               <input type="radio" name="Delete_Invoice" class="flat-green" value="1" <?php if($access->Delete_Invoice == "Yes") { echo 'checked="checked"'; } ?>>
                               Yes
                             </label>
                             <label>
                               <input type="radio" name="Delete_Invoice" class="flat-green" value="0" <?php if($access->Delete_Invoice == "No") { echo 'checked="checked"'; } ?>>
                               No
                             </label>
                           </div>
                           <div class="form-group">
                              <label>View Invoice : </label><span class="text-muted"> </span><br>
                              <label>
                                <input type="radio" name="View_Invoice" class="flat-green" value="1" <?php if($access->View_Invoice == "Yes") { echo 'checked="checked"'; } ?>>
                                Yes
                              </label>
                              <label>
                                <input type="radio" name="View_Invoice" class="flat-green" value="0" <?php if($access->View_Invoice == "No") { echo 'checked="checked"'; } ?>>
                                No
                              </label>
                            </div>
                            <div class="form-group">
                              <label>Update Invoice Number: </label><span class="text-muted"> </span><br>
                              <label>
                                <input type="radio" name="Update_Inv_Num" class="flat-green" value="1" <?php if($access->Update_Inv_Num == "Yes") { echo 'checked="checked"'; } ?>>
                                Yes
                              </label>
                              <label>
                                <input type="radio" name="Update_Inv_Num" class="flat-green" value="0" <?php if($access->Update_Inv_Num == "No") { echo 'checked="checked"'; } ?>>
                                No
                              </label>
                            </div>
                            <div class="form-group">
                              <label>Update Invoice: </label><span class="text-muted"> </span><br>
                              <label>
                                <input type="radio" name="Update_Inv" class="flat-green" value="1" <?php if($access->Update_Inv == "Yes") { echo 'checked="checked"'; } ?>>
                                Yes
                              </label>
                              <label>
                                <input type="radio" name="Update_Inv" class="flat-green" value="0" <?php if($access->Update_Inv == "No") { echo 'checked="checked"'; } ?>>
                                No
                              </label>
                            </div>
                            <div class="form-group">
                              <label>Generate Invoice : </label><span class="text-muted"> </span><br>
                              <label>
                                <input type="radio" name="Generate_Invoice" class="flat-green" value="1" <?php if($access->Generate_Invoice == "Yes") { echo 'checked="checked"'; } ?>>
                                Yes
                              </label>
                              <label>
                                <input type="radio" name="Generate_Invoice" class="flat-green" value="0" <?php if($access->Generate_Invoice == "No") { echo 'checked="checked"'; } ?>>
                                No
                              </label>
                            </div>
                            <div class="form-group">
                              <label>Invoice Listing : </label><span class="text-muted"> </span><br>
                              <label>
                                <input type="radio" name="invoice_listing" class="flat-green" value="1" <?php if($access->invoice_listing == "Yes") { echo 'checked="checked"'; } ?>>
                                Yes
                              </label>
                              <label>
                                <input type="radio" name="invoice_listing" class="flat-green" value="0" <?php if($access->invoice_listing == "No") { echo 'checked="checked"'; } ?>>
                                No
                              </label>
                            </div>
                           <div class="form-group">
                             <label>View Invoice Management : </label><span class="text-muted"> Permission to view Invoice Management</span><br>
                             <label>
                               <input type="radio" name="View_Invoice_Management" class="flat-green" value="1" <?php if($access->View_Invoice_Management == "Yes") { echo 'checked="checked"'; } ?>>
                               Yes
                             </label>
                             <label>
                               <input type="radio" name="View_Invoice_Management" class="flat-green" value="0" <?php if($access->View_Invoice_Management == "No") { echo 'checked="checked"'; } ?>>
                               No
                             </label>
                           </div>

                           <div class="form-group">
                             <label>View Invoice Summary : </label><span class="text-muted"> Permission to view Invoice Summary</span><br>
                             <label>
                               <input type="radio" name="View_Invoice_Summary" class="flat-green" value="1" <?php if($access->View_Invoice_Summary == "Yes") { echo 'checked="checked"'; } ?>>
                               Yes
                             </label>
                             <label>
                               <input type="radio" name="View_Invoice_Summary" class="flat-green" value="0" <?php if($access->View_Invoice_Summary == "No") { echo 'checked="checked"'; } ?>>
                               No
                             </label>
                           </div>

                           <div class="form-group">
                             <label>Generate Credit Note : </label><span class="text-muted"> Permission to generate Credit Note</span><br>
                             <label>
                               <input type="radio" name="Credit_Note" class="flat-green" value="1" <?php if($access->Credit_Note == "Yes") { echo 'checked="checked"'; } ?>>
                               Yes
                             </label>
                             <label>
                               <input type="radio" name="Credit_Note" class="flat-green" value="0" <?php if($access->Credit_Note == "No") { echo 'checked="checked"'; } ?>>
                               No
                             </label>
                           </div>

                           <div class="form-group">
                             <label>Credit Note List : </label><span class="text-muted"> Permission to view Credit Note List</span><br>
                             <label>
                               <input type="radio" name="Credit_Note_List" class="flat-green" value="1" <?php if($access->Credit_Note_List == "Yes") { echo 'checked="checked"'; } ?>>
                               Yes
                             </label>
                             <label>
                               <input type="radio" name="Credit_Note_List" class="flat-green" value="0" <?php if($access->Credit_Note_List == "No") { echo 'checked="checked"'; } ?>>
                               No
                             </label>
                           </div>

                        </div>
                      </div>

                      <div class="box box-solid box-success" style="display: none;">
                        <div class="box-header">
                          Others
                        </div>
                        <div class="box-body">

                           <div class="form-group">
                             <label>View Work in Progress : </label><span class="text-muted"> Permission to view WIP</span><br>
                             <label>
                               <input type="radio" name="View_WIP" class="flat-green" value="1" <?php if($access->View_WIP == "Yes") { echo 'checked="checked"'; } ?>>
                               Yes
                             </label>
                             <label>
                               <input type="radio" name="View_WIP" class="flat-green" value="0" <?php if($access->View_WIP == "No") { echo 'checked="checked"'; } ?>>
                               No
                             </label>
                           </div>

                           <div class="form-group">
                             <label>View Forecast : </label><span class="text-muted"> Permission to view forecast</span><br>
                             <label>
                               <input type="radio" name="View_Forecast" class="flat-green" value="1" <?php if($access->View_Forecast == "Yes") { echo 'checked="checked"'; } ?>>
                               Yes
                             </label>
                             <label>
                               <input type="radio" name="View_Forecast" class="flat-green" value="0" <?php if($access->View_Forecast == "No") { echo 'checked="checked"'; } ?>>
                               No
                             </label>
                           </div>

                           <div class="form-group">
                             <label>View Profit & Loss : </label><span class="text-muted"> Permission to view profit and loss</span><br>
                             <label>
                               <input type="radio" name="View_PNL" class="flat-green" value="1" <?php if($access->View_PNL == "Yes") { echo 'checked="checked"'; } ?>>
                               Yes
                             </label>
                             <label>
                               <input type="radio" name="View_PNL" class="flat-green" value="0" <?php if($access->View_PNL == "No") { echo 'checked="checked"'; } ?>>
                               No
                             </label>
                           </div>

                        </div>
                      </div>

                     </div>

                     <div class="tab-pane" id="deliverymanagement">

                      <div class="box box-solid box-success">
                        <div class="box-header">
                          Delivery  Management Control
                        </div>
                        <div class="box-body">

                            <div class="form-group">
                              <label>Delivery Dashboard : </label><span class="text-muted"> </span><br>
                              <label>
                                <input type="radio" name="Delivery_Dashboard" class="flat-green" value="1" <?php if($access->Delivery_Dashboard == "Yes") { echo 'checked="checked"'; } ?>>
                                Yes
                              </label>
                              <label>
                                <input type="radio" name="Delivery_Dashboard" class="flat-green" value="0" <?php if($access->Delivery_Dashboard == "No") { echo 'checked="checked"'; } ?>>
                                No
                              </label>
                            </div>

                          <div class="form-group">
                            <label>Delivery Approval : </label><span class="text-muted"> </span><br>
                            <label>
                              <input type="radio" name="Delivery_Approval" class="flat-green" value="1" <?php if($access->Delivery_Approval == "Yes") { echo 'checked="checked"'; } ?>>
                              Yes
                            </label>
                            <label>
                              <input type="radio" name="Delivery_Approval" class="flat-green" value="0" <?php if($access->Delivery_Approval == "No") { echo 'checked="checked"'; } ?>>
                              No
                            </label>
                          </div>

                          <div class="form-group">
                            <label>Delete Delivery : </label><span class="text-muted"> </span><br>
                            <label>
                              <input type="radio" name="Delete_Delivery" class="flat-green" value="1" <?php if($access->Delete_Delivery == "Yes") { echo 'checked="checked"'; } ?>>
                              Yes
                            </label>
                            <label>
                              <input type="radio" name="Delete_Delivery" class="flat-green" value="0" <?php if($access->Delete_Delivery == "No") { echo 'checked="checked"'; } ?>>
                              No
                            </label>
                          </div>


                          <div class="form-group">
                            <label>Delivery Tracking : </label><span class="text-muted"> </span><br>
                            <label>
                              <input type="radio" name="Delivery_Tracking" class="flat-green" value="1" <?php if($access->Delivery_Tracking == "Yes") { echo 'checked="checked"'; } ?>>
                              Yes
                            </label>
                            <label>
                              <input type="radio" name="Delivery_Tracking" class="flat-green" value="0" <?php if($access->Delivery_Tracking == "No") { echo 'checked="checked"'; } ?>>
                              No
                            </label>
                          </div>

                          <div class="form-group">
                            <label>Site Delivery Summary : </label><span class="text-muted"> </span><br>
                            <label>
                              <input type="radio" name="Site_Delivery_Summary" class="flat-green" value="1" <?php if($access->Site_Delivery_Summary == "Yes") { echo 'checked="checked"'; } ?>>
                              Yes
                            </label>
                            <label>
                              <input type="radio" name="Site_Delivery_Summary" class="flat-green" value="0" <?php if($access->Site_Delivery_Summary == "No") { echo 'checked="checked"'; } ?>>
                              No
                            </label>
                          </div>

                          <div class="form-group">
                            <label>Warehouse Checklist : </label><span class="text-muted"> </span><br>
                            <label>
                              <input type="radio" name="Warehouse_Checklist" class="flat-green" value="1" <?php if($access->Warehouse_Checklist == "Yes") { echo 'checked="checked"'; } ?>>
                              Yes
                            </label>
                            <label>
                              <input type="radio" name="Warehouse_Checklist" class="flat-green" value="0" <?php if($access->Warehouse_Checklist == "No") { echo 'checked="checked"'; } ?>>
                              No
                            </label>
                          </div>

                          <div class="form-group">
                            <label>Requestor KPI : </label><span class="text-muted"> </span><br>
                            <label>
                              <input type="radio" name="Requestor_KPI" class="flat-green" value="1" <?php if($access->Requestor_KPI == "Yes") { echo 'checked="checked"'; } ?>>
                              Yes
                            </label>
                            <label>
                              <input type="radio" name="Requestor_KPI" class="flat-green" value="0" <?php if($access->Requestor_KPI == "No") { echo 'checked="checked"'; } ?>>
                              No
                            </label>
                          </div>

                          <div class="form-group">
                            <label>Driver KPI : </label><span class="text-muted"> </span><br>
                            <label>
                              <input type="radio" name="Driver_KPI" class="flat-green" value="1" <?php if($access->Driver_KPI == "Yes") { echo 'checked="checked"'; } ?>>
                              Yes
                            </label>
                            <label>
                              <input type="radio" name="Driver_KPI" class="flat-green" value="0" <?php if($access->Driver_KPI == "No") { echo 'checked="checked"'; } ?>>
                              No
                            </label>
                          </div>

                          <div class="form-group">
                            <label>Warehouse KPI : </label><span class="text-muted"> </span><br>
                            <label>
                              <input type="radio" name="Warehouse_KPI" class="flat-green" value="1" <?php if($access->Warehouse_KPI == "Yes") { echo 'checked="checked"'; } ?>>
                              Yes
                            </label>
                            <label>
                              <input type="radio" name="Warehouse_KPI" class="flat-green" value="0" <?php if($access->Warehouse_KPI == "No") { echo 'checked="checked"'; } ?>>
                              No
                            </label>
                          </div>

                       </div>
                     </div>


                    </div><!-- Delivery management-->
                   </div>

                 </div>
                 <!-- /.box -->
               </div>
              </div>
              </div>

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

      //Initialize Select2 Elements
      $(".select2").select2();

      $('input[type="checkbox"].flat-green, input[type="radio"].flat-green').iCheck({
        checkboxClass: 'icheckbox_flat-green',
        radioClass: 'iradio_flat-green'
      });
  });

  function removetemplate()
  {

    $.ajaxSetup({
       headers: { 'X-CSRF-Token' : $('meta[name=_token]').attr('content') }
    });

    if ($("#Template").text()!="Default Template")
    {
      if ($("#Template").val())
      {
        var result=JSON.parse($("#Template").val());
        var selectedtext=$("#Template option:selected").text();
        var selectedvalue=$("#Template option:selected").val();
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
                          $("#update-alert").modal("show");

                          //jQuery("#Template option:contains('"+ selectedtext +"')").remove();
                          $("#Template option[value='"+selectedvalue+"']").remove();

                          var message="Template removed!";

                          $('#RemoveTemplate').modal('hide')
                          $("#update-alert ul").html(message);
                          $("#update-alert").modal("show");

                          setTimeout(function() {
                            $("#update-alert").fadeOut();
                          }, 6000);
                      }
                      else {

                          var message="Failed to remove template!";

                          $('#RemoveTemplate').modal('hide')
                          $("#warning-alert ul").html(message);
                          $("#warning-alert").modal("show");

                          setTimeout(function() {
                            $("#warning-alert").fadeOut();
                          }, 6000);

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

  }

  $('#Template').on('change', function() {


    if (this.value)
    {
      var result=JSON.parse(this.value);

      for (var prop in result) {
      if (result.hasOwnProperty(prop)) {
        console.log(prop, result[prop])
          if (result[prop]==1)
          {
              //document.getElementById(prop).checked = true;
              //$('input[name=Admin]').prop('checked', true);
              //$($('input[name=Admin]')).prop('checked', 1);
              //$('input.type_checkbox[value="6"]').prop('checked', true);
              $("input[name=\""+prop+"\"][value=1]").iCheck('check');
          }
          else if (result[prop]==0)
          {
              //$('input[name=Admin]').prop('checked', false);
              //document.getElementById(prop).checked = false;
            $("input[name=\""+prop+"\"][value=0]").iCheck('check');
          }
      }
    }

  }

  });

  function updatetemplate() {

      $.ajaxSetup({
         headers: { 'X-CSRF-Token' : $('meta[name=_token]').attr('content') }
      });

      value=$("#Template option:selected").val();

      View_User_Profile=$('input[name=View_User_Profile]:checked').val();
      Edit_User=$('input[name=Edit_User]:checked').val();
      Staff_Monitoring=$('input[name=Staff_Monitoring]:checked').val();
      Edit_Time=$('input[name=Edit_Time]:checked').val();
      View_CV=$('input[name=View_CV]:checked').val();
      Export_CV=$('input[name=Export_CV]:checked').val();
      Edit_CV=$('input[name=Edit_CV]:checked').val();
      View_Contractor_Profile=$('input[name=View_Contractor_Profile]:checked').val();
      Edit_Contractor=$('input[name=Edit_Contractor]:checked').val();
      View_Org_Chart=$('input[name=View_Org_Chart]:checked').val();
      Update_Org_Chart=$('input[name=Update_Org_Chart]:checked').val();
      Approve_Leave=$('input[name=Approve_Leave]:checked').val();
      View_All_Leave=$('input[name=View_All_Leave]:checked').val();
      Delete_Leave=$('input[name=Delete_Leave]:checked').val();
      View_Leave_Summary=$('input[name=View_Leave_Summary]:checked').val();
      Show_Leave_To_Public=$('input[name=Show_Leave_To_Public]:checked').val();
      Approve_Timesheet=$('input[name=Approve_Timesheet]:checked').val();
      View_All_Timesheet=$('input[name=View_All_Timesheet]:checked').val();
      View_Timesheet_Summary=$('input[name=View_Timesheet_Summary]:checked').val();
      View_Allowance=$('input[name=View_Allowance]:checked').val();
      Edit_Allowance=$('input[name=Edit_Allowance]:checked').val();
      Timesheet_Required=$('input[name=Timesheet_Required]:checked').val();
      Approve_Claim=$('input[name=Approve_Claim]:checked').val();
      View_All_Claim=$('input[name=View_All_Claim]:checked').val();
      View_Claim_Summary=$('input[name=View_Claim_Summary]:checked').val();
      Update_Payment_Month=$('input[name=Update_Payment_Month]:checked').val();
      Access_Control=$('input[name=Access_Control]:checked').val();
      Approval_Control=$('input[name=Approval_Control]:checked').val();
      Allowance_Control=$('input[name=Allowance_Control]:checked').val();
      Asset_Tracking=$('input[name=Asset_Tracking]:checked').val();
      Option_Control=$('input[name=Option_Control]:checked').val();
      Holiday_Management=$('input[name=Holiday_Management]:checked').val();
      Notice_Board_Management=$('input[name=Notice_Board_Management]:checked').val();
      Cutoff_Management=$('input[name=Cutoff_Management]:checked').val();
      Chart_Management=$('input[name=Chart_Management]:checked').val();
      Project_Access=$('input[name=Project_Access]:checked').val();
      Template_Access=$('input[name=Template_Access]:checked').val();
      Notification_Maintenance=$('input[name=Notification_Maintenance]:checked').val();
      Leave_Entitlement=$('input[name=Leave_Entitlement]:checked').val();
      Schedule=$('input[name=Schedule]:checked').val();
      View_Login_Tracking=$('input[name=View_Login_Tracking]:checked').val();
      Import_Data=$('input[name=Import_Data]:checked').val();
      Create_Project=$('input[name=Create_Project]:checked').val();
      Create_Project_Code=$('input[name=Create_Project_Code]:checked').val();
      Project_Manager=$('input[name=Project_Manager]:checked').val();
      View_Project_List=$('input[name=View_Project_List]:checked').val();
      View_Resource_Calendar=$('input[name=View_Resource_Calendar]:checked').val();
      View_Resource_Summary=$('input[name=View_Resource_Summary]:checked').val();
      View_Report_Store=$('input[name=View_Report_Store]:checked').val();
      Todolist_Dashboard=$('input[name=Todolist_Dashboard]:checked').val();
      View_Todolist=$('input[name=View_Todolist]:checked').val();
      Delete_Todo=$('input[name=Delete_Todo]:checked').val();
      Delete_File=$('input[name=Delete_File]:checked').val();

      Resource_Allocation=$('input[name=Resource_Allocation]:checked').val();
      Staff_Skill=$('input[name=Staff_Skill]:checked').val();
      Project_Requirement=$('input[name=Project_Requirement]:checked').val();
      Aging_Rules_Management=$('input[name=Aging_Rules_Management]:checked').val();
      Target_Rules_Management=$('input[name=Target_Rules_Management]:checked').val();
      Dependency_Rules_Management=$('input[name=Dependency_Rules_Management]:checked').val();
      Auto_Date_Management=$('input[name=Auto_Date_Management]:checked').val();
      Task_Management=$('input[name=Task_Management]:checked').val();
      Site_Issue=$('input[name=Site_Issue]:checked').val();

      License_Checklist=$('input[name=License_Checklist]:checked').val();
      Tracker_Management=$('input[name=Tracker_Management]:checked').val();

      Add_Column=$('input[name=Add_Column]:checked').val();
      Delete_Column=$('input[name=Delete_Column]:checked').val();
      Update_Column=$('input[name=Update_Column]:checked').val();
      Reorder_Column=$('input[name=Reorder_Column]:checked').val();
      Add_Option=$('input[name=Add_Option]:checked').val();
      Edit_Project_Code=$('input[name=Edit_Project_Code]:checked').val();

      Import_Tracker=$('input[name=Import_Tracker]:checked').val();
      Create_PO=$('input[name=Create_PO]:checked').val();
      Delete_PO=$('input[name=Delete_PO]:checked').val();
      View_PO_Management=$('input[name=View_PO_Management]:checked').val();
      View_PO_Summary=$('input[name=View_PO_Summary]:checked').val();
      Create_Invoice=$('input[name=Create_Invoice]:checked').val();
      Delete_Invoice=$('input[name=Delete_Invoice]:checked').val();
      View_Invoice_Management=$('input[name=View_Invoice_Management]:checked').val();
      View_Invoice_Summary=$('input[name=View_Invoice_Summary]:checked').val();
      Credit_Note=$('input[name=Credit_Note]:checked').val();
      Credit_Note_List=$('input[name=Credit_Note_List]:checked').val();
      View_WIP=$('input[name=View_WIP]:checked').val();
      View_Forecast=$('input[name=View_Forecast]:checked').val();
      View_PNL=$('input[name=View_PNL]:checked').val();

      Staff_Loan=$('input[name=Staff_Loan]:checked').val();
      Presaving=$('input[name=Presaving]:checked').val();
      Staff_Expenses=$('input[name=Staff_Expenses]:checked').val();
      Deduction=$('input[name=Deduction]:checked').val();
      Request_Management=$('input[name=Request_Management]:checked').val();

      Payroll=$('input[name=Payroll]:checked').val();
      Motor_Vehicle=$('input[name=Motor_Vehicle]:checked').val();
      Phone_Bills=$('input[name=Phone_Bills]:checked').val();
      Insurance=$('input[name=Insurance]:checked').val();
      Shell_Cards=$('input[name=Shell_Cards]:checked').val();
      Summon=$('input[name=Summon]:checked').val();
      TouchNGo=$('input[name=TouchNGo]:checked').val();
      Credit_Card=$('input[name=Credit_Card]:checked').val();
      Utility_Bills=$('input[name=Utility_Bills]:checked').val();
      License=$('input[name=License]:checked').val();
      Agreement=$('input[name=Agreement]:checked').val();
      Property=$('input[name=Property]:checked').val();
      Filing_System=$('input[name=Filing_System]:checked').val();
      Printer=$('input[name=Printer]:checked').val();
      radius=$('input[name=radius]:checked').val();
      IT_Services=$('input[name=IT_Services]:checked').val();
      Service_Contact=$('input[name=Service_Contact]:checked').val();
      Approve_Staff_Loan=$('input[name=Approve_Staff_Loan]:checked').val();
      View_All_Staff_Loan=$('input[name=View_All_Staff_Loan]:checked').val();
      Update_Medical_Claim=$('input[name=Update_Medical_Claim]:checked').val();
      View_MIA=$('input[name=View_MIA]:checked').val();
      OT_Management_HR=$('input[name=OT_Management_HR]:checked').val();
      OT_Management_HOD=$('input[name=OT_Management_HOD]:checked').val();
      Payslip_Management=$('input[name=Payslip_Management]:checked').val();
      Leave_Adjustment=$('input[name=Leave_Adjustment]:checked').val();

      View_Department_Attendance_Summary=$('input[name=View_Department_Attendance_Summary]:checked').val();
      View_Incentive_Summary=$('input[name=View_Incentive_Summary]:checked').val();
      View_Staff_Deduction_Detail=$('input[name=View_Staff_Deduction_Detail]:checked').val();

      View_Medical_Claim_Summary=$('input[name=View_Medical_Claim_Summary]:checked').val();

      Delivery_Dashboard=$('input[name=Delivery_Dashboard]:checked').val();
      Delivery_Approval=$('input[name=Delivery_Approval]:checked').val();
      Delete_Delivery=$('input[name=Delivery_Approval]:checked').val();
      Delivery_Tracking=$('input[name=Delivery_Tracking]:checked').val();
      Site_Delivery_Summary=$('input[name=Site_Delivery_Summary]:checked').val();
      Warehouse_Checklist=$('input[name=Warehouse_Checklist]:checked').val();
      Requestor_KPI=$('input[name=Requestor_KPI]:checked').val();
      Driver_KPI=$('input[name=Driver_KPI]:checked').val();
      Warehouse_KPI=$('input[name=Warehouse_KPI]:checked').val();

      Material_Request=$('input[name=Material_Request]:checked').val();
      View_Costing_Summary=$('input[name=View_Costing_Summary]:checked').val();
      View_OSU_Costing_Summary=$('input[name=View_OSU_Costing_Summary]:checked').val();
      Material_Approval=$('input[name=Material_Approval]:checked').val();
      Driver_Incentive_Summary=$('input[name=Driver_Incentive_Summary]:checked').val();
      Transport_Charges=$('input[name=Transport_Charges]:checked').val();

      View_Material_Request=$('input[name=View_Material_Request]:checked').val();
      View_PO_Listing=$('input[name=View_PO_Listing]:checked').val();
      Generate_PO=$('input[name=Generate_PO]:checked').val();
      Quotation_Approval=$('input[name=Quotation_Approval]:checked').val();
      Upload_Quotation=$('input[name=Upload_Quotation]:checked').val();
      Recall_MR=$('input[name=Recall_MR]:checked').val();
      Speedfreak=$('input[name=Speedfreak]:checked').val();
      Speedfreak_Summary=$('input[name=Speedfreak_Summary]:checked').val();
      Delete_RQO=$('input[name=Delete_RQO]:checked').val();
      View_All_Branch=$('input[name=View_All_Branch]:checked').val();
      Update_Inventory=$('input[name=Update_Inventory]:checked').val();
      Sales_Order=$('input[name=Sales_Order]:checked').val();
      Storekeeper=$('input[name=Storekeeper]:checked').val();
      Sales_Summary=$('input[name=Sales_Summary]:checked').val();
      Delete_SO=$('input[name=Delete_SO]:checked').val();
      Recur_SO=$('input[name=Recur_SO]:checked').val();
      View_Invoice=$('input[name=View_Invoice]:checked').val();
      Update_Inv_Num=$('input[name=Update_Inv_Num]:checked').val();
      Update_Inv=$('input[name=Update_Inv]:checked').val();
      Generate_Invoice=$('input[name=Generate_Invoice]:checked').val();
      invoice_listing=$('input[name=invoice_listing]:checked').val();
      dummy_do=$('input[name=dummy_do]:checked').val();
      Costing=$('input[name=Costing]:checked').val();
      autocount=$('input[name=autocount]:checked').val();
      ewallet=$('input[name=ewallet]:checked').val();
      Task_List=$('input[name=Task_List]:checked').val();
      CME_Dashboard=$('input[name=CME_Dashboard]:checked').val();
      SO_Details=$('input[name=SO_Details]:checked').val();
      $.ajax({
                  url: "{{ url('/accesscontrol/update') }}",
                  method: "POST",
                  data: {value:value,
                    View_User_Profile:View_User_Profile,
                    Edit_User:Edit_User,
                    Staff_Monitoring:Staff_Monitoring,
                    Edit_Time:Edit_Time,
                    View_CV:View_CV,
                    Export_CV:Export_CV,
                    Edit_CV:Edit_CV,
                    View_Contractor_Profile:View_Contractor_Profile,
                    Edit_Contractor:Edit_Contractor,
                    View_Org_Chart:View_Org_Chart,
                    Update_Org_Chart:Update_Org_Chart,
                    Approve_Leave:Approve_Leave,
                    View_All_Leave:View_All_Leave,
                    Delete_Leave:Delete_Leave,
                    View_Leave_Summary:View_Leave_Summary,
                    Show_Leave_To_Public:Show_Leave_To_Public,
                    Approve_Timesheet:Approve_Timesheet,
                    View_All_Timesheet:View_All_Timesheet,
                    View_Timesheet_Summary:View_Timesheet_Summary,
                    View_Allowance:View_Allowance,
                    Update_Payment_Month:Update_Payment_Month,
                    Edit_Allowance:Edit_Allowance,
                    Timesheet_Required:Timesheet_Required,
                    Approve_Claim:Approve_Claim,
                    View_All_Claim:View_All_Claim,
                    View_Claim_Summary:View_Claim_Summary,
                    Update_Payment_Month:Update_Payment_Month,
                    Access_Control:Access_Control,
                    Approval_Control:Approval_Control,
                    Allowance_Control:Allowance_Control,
                    Asset_Tracking:Asset_Tracking,
                    Option_Control:Option_Control,
                    Holiday_Management:Holiday_Management,
                    Notice_Board_Management:Notice_Board_Management,
                    Cutoff_Management:Cutoff_Management,
                    Chart_Management:Chart_Management,
                    Project_Access:Project_Access,
                    Template_Access:Template_Access,
                    Notification_Maintenance:Notification_Maintenance,
                    Leave_Entitlement:Leave_Entitlement,
                    Schedule:Schedule,
                    View_Login_Tracking:View_Login_Tracking,
                    Import_Data:Import_Data,
                    Create_Project:Create_Project,
                    Create_Project_Code:Create_Project_Code,
                    Project_Manager:Project_Manager,
                    View_Project_List:View_Project_List,
                    View_Resource_Calendar:View_Resource_Calendar,
                    View_Resource_Summary:View_Resource_Summary,
                    View_Report_Store:View_Report_Store,
                    View_Todolist:View_Todolist,
                    Delete_Todo:Delete_Todo,
                    Todolist_Dashboard:Todolist_Dashboard,
                    Delete_File:Delete_File,
                    Resource_Allocation:Resource_Allocation,
                    Staff_Skill:Staff_Skill,
                    Project_Requirement:Project_Requirement,
                    Aging_Rules_Management:Aging_Rules_Management,
                    Target_Rules_Management:Target_Rules_Management,
                    Dependency_Rules_Management:Dependency_Rules_Management,
                    Task_Management:Task_Management,
                    Site_Issue:Site_Issue,
                    Auto_Date_Management:Auto_Date_Management,

                    License_Checklist:License_Checklist,
                    Tracker_Management:Tracker_Management,

                    Add_Column:Add_Column,
                    Delete_Column:Delete_Column,
                    Update_Column:Update_Column,
                    Reorder_Column:Reorder_Column,
                    Add_Option:Add_Option,
                    Edit_Project_Code:Edit_Project_Code,

                    Import_Tracker:Import_Tracker,
                    Create_PO:Create_PO,
                    Delete_PO:Delete_PO,
                    View_PO_Management:View_PO_Management,
                    View_PO_Summary:View_PO_Summary,
                    Create_Invoice:Create_Invoice,
                    Delete_Invoice:Delete_Invoice,
                    View_Invoice_Management:View_Invoice_Management,
                    View_Invoice_Summary:View_Invoice_Summary,
                    Credit_Note:Credit_Note,
                    Credit_Note_List:Credit_Note_List,
                    View_WIP:View_WIP,
                    View_Forecast:View_Forecast,
                    View_PNL:View_PNL,

                    Staff_Loan:Staff_Loan,
                    Presaving:Presaving,
                    Staff_Expenses:Staff_Expenses,
                    Deduction:Deduction,
                    Request_Management:Request_Management,

                    Payroll:Payroll,
                    Motor_Vehicle:Motor_Vehicle,
                    Phone_Bills:Phone_Bills,
                    Insurance:Insurance,
                    Shell_Cards:Shell_Cards,
                    Summon:Summon,
                    TouchNGo:TouchNGo,
                    Credit_Card:Credit_Card,
                    Utility_Bills:Utility_Bills,
                    License:License,
                    Agreement:Agreement,
                    Property:Property,
                    Filing_System:Filing_System,
                    Printer:Printer,
                    radius:radius,
                    IT_Services:IT_Services,
                    Service_Contact:Service_Contact,
                    Approve_Staff_Loan:Approve_Staff_Loan,
                    View_All_Staff_Loan:View_All_Staff_Loan,
                    Update_Medical_Claim:Update_Medical_Claim,
                    OT_Management_HR:OT_Management_HR,
                    OT_Management_HOD:OT_Management_HOD,
                    Payslip_Management:Payslip_Management,
                    Leave_Adjustment:Leave_Adjustment,
                    View_Department_Attendance_Summary:View_Department_Attendance_Summary,
                    View_Incentive_Summary:View_Incentive_Summary,
                    View_Staff_Deduction_Detail:View_Staff_Deduction_Detail,

                    View_Medical_Claim_Summary:View_Medical_Claim_Summary,
                    View_MIA:View_MIA,
                    Delivery_Dashboard:Delivery_Dashboard,
                    Delivery_Approval:Delivery_Approval,
                    Delete_Delivery:Delete_Delivery,
                    Delivery_Tracking:Delivery_Tracking,
                    Site_Delivery_Summary:Site_Delivery_Summary,
                    Warehouse_Checklist:Warehouse_Checklist,
                    Requestor_KPI:Requestor_KPI,
                    Driver_KPI:Driver_KPI,
                    Warehouse_KPI:Warehouse_KPI,

                    Material_Request:Material_Request,
                    View_Costing_Summary:View_Costing_Summary,
                    View_OSU_Costing_Summary:View_OSU_Costing_Summary,
                    Material_Approval:Material_Approval,
                    Driver_Incentive_Summary:Driver_Incentive_Summary,
                    Transport_Charges:Transport_Charges,
                    View_Material_Request:View_Material_Request,
                    View_PO_Listing:View_PO_Listing,
                    Generate_PO:Generate_PO,
                    Quotation_Approval:Quotation_Approval,
                    Upload_Quotation:Upload_Quotation,
                    Recall_MR:Recall_MR,
                    Speedfreak:Speedfreak,
                    Speedfreak_Summary:Speedfreak_Summary,
                    Delete_RQO:Delete_RQO,
                    View_All_Branch:View_All_Branch,
                    Update_Inventory:Update_Inventory,
                    Sales_Order:Sales_Order,
                    Storekeeper:Storekeeper,
                    Sales_Summary:Sales_Summary,
                    Delete_SO:Delete_SO,
                    Recur_SO:Recur_SO,
                    View_Invoice:View_Invoice,
                    Update_Inv_Num:Update_Inv_Num,
                    Update_Inv:Update_Inv,
                    Generate_Invoice:Generate_Invoice,
                    invoice_listing:invoice_listing,
                    dummy_do:dummy_do,
                    Costing:Costing,
                    autocount:autocount,
                    ewallet:ewallet,
                    SO_Details:SO_Details,
                    Task_List:Task_List,
                    CME_Dashboard:CME_Dashboard
                  },

                  success: function(response){
                    if (response==0)
                    {
                      var message ="No update on template!";
                      $("#warning-alert ul").html(message);
                      $("#warning-alert").modal("show");

                      $('#UpdateTemplate').modal('hide')

                    }
                    else {

                      var message ="Template updated!";
                      $("#update-alert ul").html(message);
                      $("#update-alert").modal("show");

                      $("#Template option:selected").val(response).change();
                      //$("#Template").val(response).change();
                      $("#exist-alert").modal("hide");
                      $('#UpdateTemplate').modal('hide')

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
          $("#exist-alert").modal("show");
      }
      else {

        Created_By={{$me->UserId}};

        View_User_Profile=$('input[name=View_User_Profile]:checked').val();
        Edit_User=$('input[name=Edit_User]:checked').val();
        Staff_Monitoring=$('input[name=Staff_Monitoring]:checked').val();
        Edit_Time=$('input[name=Edit_Time]:checked').val();
        View_CV=$('input[name=View_CV]:checked').val();
        Export_CV=$('input[name=Export_CV]:checked').val();
        Edit_CV=$('input[name=Edit_CV]:checked').val();
        View_Contractor_Profile=$('input[name=View_Contractor_Profile]:checked').val();
        Edit_Contractor=$('input[name=Edit_Contractor]:checked').val();
        View_Org_Chart=$('input[name=View_Org_Chart]:checked').val();
        Update_Org_Chart=$('input[name=Update_Org_Chart]:checked').val();
        Approve_Leave=$('input[name=Approve_Leave]:checked').val();
        View_All_Leave=$('input[name=View_All_Leave]:checked').val();
        Delete_Leave=$('input[name=Delete_Leave]:checked').val();
        View_Leave_Summary=$('input[name=View_Leave_Summary]:checked').val();
        Show_Leave_To_Public=$('input[name=Show_Leave_To_Public]:checked').val();
        Approve_Timesheet=$('input[name=Approve_Timesheet]:checked').val();
        View_All_Timesheet=$('input[name=View_All_Timesheet]:checked').val();
        View_Timesheet_Summary=$('input[name=View_Timesheet_Summary]:checked').val();
        View_Allowance=$('input[name=View_Allowance]:checked').val();
        Edit_Allowance=$('input[name=Edit_Allowance]:checked').val();
        Timesheet_Required=$('input[name=Timesheet_Required]:checked').val();
        Approve_Claim=$('input[name=Approve_Claim]:checked').val();
        View_All_Claim=$('input[name=View_All_Claim]:checked').val();
        View_Claim_Summary=$('input[name=View_Claim_Summary]:checked').val();
        Update_Payment_Month=$('input[name=Update_Payment_Month]:checked').val();
        Access_Control=$('input[name=Access_Control]:checked').val();
        Approval_Control=$('input[name=Approval_Control]:checked').val();
        Allowance_Control=$('input[name=Allowance_Control]:checked').val();
        Asset_Tracking=$('input[name=Asset_Tracking]:checked').val();
        Option_Control=$('input[name=Option_Control]:checked').val();
        Holiday_Management=$('input[name=Holiday_Management]:checked').val();
        Notice_Board_Management=$('input[name=Notice_Board_Management]:checked').val();
        Cutoff_Management=$('input[name=Cutoff_Management]:checked').val();
        Chart_Management=$('input[name=Chart_Management]:checked').val();
        Project_Access=$('input[name=Project_Access]:checked').val();
        Template_Access=$('input[name=Template_Access]:checked').val();
        Notification_Maintenance=$('input[name=Notification_Maintenance]:checked').val();
        Leave_Entitlement=$('input[name=Leave_Entitlement]:checked').val();
        Schedule=$('input[name=Schedule]:checked').val();
        View_Login_Tracking=$('input[name=View_Login_Tracking]:checked').val();
        Import_Data=$('input[name=Import_Data]:checked').val();
        Create_Project=$('input[name=Create_Project]:checked').val();
        Create_Project_Code=$('input[name=Create_Project_Code]:checked').val();
        Project_Manager=$('input[name=Project_Manager]:checked').val();
        View_Project_List=$('input[name=View_Project_List]:checked').val();
        View_Resource_Calendar=$('input[name=View_Resource_Calendar]:checked').val();
        View_Resource_Summary=$('input[name=View_Resource_Summary]:checked').val();
        View_Report_Store=$('input[name=View_Report_Store]:checked').val();
        Todolist_Dashboard=$('input[name=Todolist_Dashboard]:checked').val();
        View_Todolist=$('input[name=View_Todolist]:checked').val();
        Delete_Todo=$('input[name=Delete_Todo]:checked').val();
        Delete_File=$('input[name=Delete_File]:checked').val();
        Resource_Allocation=$('input[name=Resource_Allocation]:checked').val();
        Staff_Skill=$('input[name=Staff_Skill]:checked').val();
        Project_Requirement=$('input[name=Project_Requirement]:checked').val();
        Aging_Rules_Management=$('input[name=Aging_Rules_Management]:checked').val();
        Target_Rules_Management=$('input[name=Target_Rules_Management]:checked').val();
        Dependency_Rules_Management=$('input[name=Dependency_Rules_Management]:checked').val();
        Auto_Date_Management=$('input[name=Auto_Date_Management]:checked').val();
        Site_Issue=$('input[name=Site_Issue]:checked').val();
        Task_Management=$('input[name=Task_Management]:checked').val();

        License_Checklist=$('input[name=License_Checklist]:checked').val();
        Tracker_Management=$('input[name=Tracker_Management]:checked').val();

        Add_Column=$('input[name=Add_Column]:checked').val();
        Delete_Column=$('input[name=Delete_Column]:checked').val();
        Update_Column=$('input[name=Update_Column]:checked').val();
        Reorder_Column=$('input[name=Reorder_Column]:checked').val();
        Add_Option=$('input[name=Add_Option]:checked').val();
        Edit_Project_Code=$('input[name=Edit_Project_Code]:checked').val();

        Import_Tracker=$('input[name=Import_Tracker]:checked').val();
        Create_PO=$('input[name=Create_PO]:checked').val();
        Delete_PO=$('input[name=Delete_PO]:checked').val();
        View_PO_Management=$('input[name=View_PO_Management]:checked').val();
        View_PO_Summary=$('input[name=View_PO_Summary]:checked').val();
        Create_Invoice=$('input[name=Create_Invoice]:checked').val();
        Delete_Invoice=$('input[name=Delete_Invoice]:checked').val();
        View_Invoice_Management=$('input[name=View_Invoice_Management]:checked').val();
        View_Invoice_Summary=$('input[name=View_Invoice_Summary]:checked').val();
        Credit_Note=$('input[name=Credit_Note]:checked').val();
        Credit_Note_List=$('input[name=Credit_Note_List]:checked').val();
        View_WIP=$('input[name=View_WIP]:checked').val();
        View_Forecast=$('input[name=View_Forecast]:checked').val();
        View_PNL=$('input[name=View_PNL]:checked').val();

        Staff_Loan=$('input[name=Staff_Loan]:checked').val();
        Presaving=$('input[name=Presaving]:checked').val();
        Staff_Expenses=$('input[name=Staff_Expenses]:checked').val();
        Deduction=$('input[name=Deduction]:checked').val();
        Request_Management=$('input[name=Request_Management]:checked').val();


        Payroll=$('input[name=Payroll]:checked').val();
        Motor_Vehicle=$('input[name=Motor_Vehicle]:checked').val();
        Phone_Bills=$('input[name=Phone_Bills]:checked').val();
        Insurance=$('input[name=Insurance]:checked').val();
        Shell_Cards=$('input[name=Shell_Cards]:checked').val();
        Summon=$('input[name=Summon]:checked').val();
        TouchNGo=$('input[name=TouchNGo]:checked').val();
        Credit_Card=$('input[name=Credit_Card]:checked').val();
        Utility_Bills=$('input[name=Utility_Bills]:checked').val();
        License=$('input[name=License]:checked').val();
        Agreement=$('input[name=Agreement]:checked').val();
        Property=$('input[name=Property]:checked').val();
        Filing_System=$('input[name=Filing_System]:checked').val();
        Printer=$('input[name=Printer]:checked').val();
        radius=$('input[name=radius]:checked').val();
        IT_Services=$('input[name=IT_Services]:checked').val();
        Service_Contact=$('input[name=Service_Contact]:checked').val();
        Approve_Staff_Loan=$('input[name=Approve_Staff_Loan]:checked').val();
        View_All_Staff_Loan=$('input[name=View_All_Staff_Loan]:checked').val();
        Update_Medical_Claim=$('input[name=Update_Medical_Claim]:checked').val();
        OT_Management_HR=$('input[name=OT_Management_HR]:checked').val();
        OT_Management_HOD=$('input[name=OT_Management_HOD]:checked').val();
        Payslip_Management=$('input[name=Payslip_Management]:checked').val();
        Leave_Adjustment=$('input[name=Leave_Adjustment]:checked').val();
        View_Department_Attendance_Summary=$('input[name=View_Department_Attendance_Summary]:checked').val();
        View_Incentive_Summary=$('input[name=View_Incentive_Summary]:checked').val();
        View_Staff_Deduction_Detail=$('input[name=View_Staff_Deduction_Detail]:checked').val();

        View_Medical_Claim_Summary=$('input[name=View_Medical_Claim_Summary]:checked').val();
        View_MIA=$('input[name=View_MIA]:checked').val();
        Delivery_Dashboard=$('input[name=Delivery_Dashboard]:checked').val();
        Delivery_Approval=$('input[name=Delivery_Approval]:checked').val();
        Delete_Delivery=$('input[name=Delete_Delivery]:checked').val();
        Delivery_Tracking=$('input[name=Delivery_Tracking]:checked').val();
        Site_Delivery_Summary=$('input[name=Site_Delivery_Summary]:checked').val();
        Warehouse_Checklist=$('input[name=Warehouse_Checklist]:checked').val();
        Requestor_KPI=$('input[name=Requestor_KPI]:checked').val();
        Driver_KPI=$('input[name=Driver_KPI]:checked').val();
        Warehouse_KPI=$('input[name=Warehouse_KPI]:checked').val();

        Material_Request=$('input[name=Material_Request]:checked').val();
        View_Costing_Summary=$('input[name=View_Costing_Summary]:checked').val();
        View_OSU_Costing_Summary=$('input[name=View_OSU_Costing_Summary]:checked').val();
        Material_Approval=$('input[name=Material_Approval]:checked').val();
        Driver_Incentive_Summary=$('input[name=Driver_Incentive_Summary]:checked').val();
        Transport_Charges=$('input[name=Transport_Charges]:checked').val();
        View_Material_Request=$('input[name=View_Material_Request]:checked').val();
        View_PO_Listing=$('input[name=View_PO_Listing]:checked').val();
        Generate_PO=$('input[name=Generate_PO]:checked').val();
        Quotation_Approval=$('input[name=Quotation_Approval]:checked').val();
        Upload_Quotation=$('input[name=Upload_Quotation]:checked').val();
        Recall_MR=$('input[name=Recall_MR]:checked').val();
        Speedfreak=$('input[name=Speedfreak]:checked').val();
        Speedfreak_Summary=$('input[name=Speedfreak_Summary]:checked').val();
        Delete_RQO=$('input[name=Delete_RQO]:checked').val();
        View_All_Branch=$('input[name=View_All_Branch]:checked').val();
        Update_Inventory=$('input[name=Update_Inventory]:checked').val();
        Sales_Order=$('input[name=Sales_Order]:checked').val();
        Storekeeper=$('input[name=Storekeeper]:checked').val();
        Sales_Summary=$('input[name=Sales_Summary]:checked').val();
        Delete_SO=$('input[name=Delete_SO]:checked').val();
        Recur_SO=$('input[name=Recur_SO]:checked').val();
        View_Invoice=$('input[name=View_Invoice]:checked').val();
        Update_Inv_Num=$('input[name=Update_Inv_Num]:checked').val();
        Update_Inv=$('input[name=Update_Inv]:checked').val();
        Generate_Invoice=$('input[name=Generate_Invoice]:checked').val();
        invoice_listing=$('input[name=invoice_listing]:checked').val();
        dummy_do=$('input[name=dummy_do]:checked').val();
        Costing=$('input[name=Costing]:checked').val();
        autocount=$('input[name=autocount]:checked').val();
        ewallet=$('input[name=ewallet]:checked').val();
        Task_List=$('input[name=Task_List]:checked').val();
        CME_Dashboard=$('input[name=CME_Dashboard]:checked').val();
        SO_Details=$('input[name=SO_Details]:checked').val();
        $.ajax({
                    url: "{{ url('/accesscontrol/savetemplate') }}",
                    method: "POST",
                    data: {Created_By:Created_By,
                      Template_Name:Template_Name,
                      View_User_Profile:View_User_Profile,
                      Edit_User:Edit_User,
                      Staff_Monitoring:Staff_Monitoring,
                      Edit_Time:Edit_Time,
                      View_CV:View_CV,
                      Export_CV:Export_CV,
                      Edit_CV:Edit_CV,
                      View_Contractor_Profile:View_Contractor_Profile,
                      Edit_Contractor:Edit_Contractor,
                      View_Org_Chart:View_Org_Chart,
                      Update_Org_Chart:Update_Org_Chart,
                      Approve_Leave:Approve_Leave,
                      View_All_Leave:View_All_Leave,
                      Delete_Leave:Delete_Leave,
                      View_Leave_Summary:View_Leave_Summary,
                      Show_Leave_To_Public:Show_Leave_To_Public,
                      Approve_Timesheet:Approve_Timesheet,
                      View_All_Timesheet:View_All_Timesheet,
                      View_Timesheet_Summary:View_Timesheet_Summary,

                      View_Allowance:View_Allowance,
                      Edit_Allowance:Edit_Allowance,
                      Timesheet_Required:Timesheet_Required,
                      Approve_Claim:Approve_Claim,
                      View_All_Claim:View_All_Claim,
                      View_Claim_Summary:View_Claim_Summary,
                      Update_Payment_Month:Update_Payment_Month,
                      Access_Control:Access_Control,
                      Approval_Control:Approval_Control,
                      Allowance_Control:Allowance_Control,
                      Asset_Tracking:Asset_Tracking,
                      Option_Control:Option_Control,
                      Holiday_Management:Holiday_Management,
                      Notice_Board_Management:Notice_Board_Management,
                      Cutoff_Management:Cutoff_Management,
                      Chart_Management:Chart_Management,
                      Project_Access:Project_Access,
                      Template_Access:Template_Access,
                      Notification_Maintenance:Notification_Maintenance,
                      Leave_Entitlement:Leave_Entitlement,
                      Schedule:Schedule,
                      View_Login_Tracking:View_Login_Tracking,
                      Import_Data:Import_Data,
                      Create_Project:Create_Project,
                      Create_Project_Code:Create_Project_Code,
                      Project_Manager:Project_Manager,
                      View_Project_List:View_Project_List,
                      View_Resource_Calendar:View_Resource_Calendar,
                      View_Resource_Summary:View_Resource_Summary,
                      View_Report_Store:View_Report_Store,
                      Todolist_Dashboard:Todolist_Dashboard,
                      View_Todolist:View_Todolist,
                      Delete_Todo:Delete_Todo,
                      Delete_File:Delete_File,
                      Resource_Allocation:Resource_Allocation,
                      Staff_Skill:Staff_Skill,
                      Project_Requirement:Project_Requirement,
                      Aging_Rules_Management:Aging_Rules_Management,
                      Target_Rules_Management:Target_Rules_Management,
                      Dependency_Rules_Management:Dependency_Rules_Management,
                      Task_Management:Task_Management,
                      Site_Issue:Site_Issue,
                      Auto_Date_Management:Auto_Date_Management,

                      License_Checklist:License_Checklist,
                      Tracker_Management:Tracker_Management,

                      Add_Column:Add_Column,
                      Delete_Column:Delete_Column,
                      Update_Column:Update_Column,
                      Reorder_Column:Reorder_Column,
                      Add_Option:Add_Option,
                      Edit_Project_Code:Edit_Project_Code,

                      Import_Tracker:Import_Tracker,
                      Create_PO:Create_PO,
                      Delete_PO:Delete_PO,
                      View_PO_Management:View_PO_Management,
                      View_PO_Summary:View_PO_Summary,
                      Create_Invoice:Create_Invoice,
                      Delete_Invoice:Delete_Invoice,
                      View_Invoice_Management:View_Invoice_Management,
                      View_Invoice_Summary:View_Invoice_Summary,
                      Credit_Note:Credit_Note,
                      Credit_Note_List:Credit_Note_List,
                      View_WIP:View_WIP,
                      View_Forecast:View_Forecast,
                      View_PNL:View_PNL,
                      Staff_Loan:Staff_Loan,
                      Presaving:Presaving,
                      Staff_Expenses:Staff_Expenses,
                      Deduction:Deduction,
                      Request_Management:Request_Management,

                      Payroll:Payroll,
                      Motor_Vehicle:Motor_Vehicle,
                      Phone_Bills:Phone_Bills,
                      Insurance:Insurance,
                      Shell_Cards:Shell_Cards,
                      Summon:Summon,
                      TouchNGo:TouchNGo,
                      Credit_Card:Credit_Card,
                      Utility_Bills:Utility_Bills,
                      License:License,
                      Agreement:Agreement,
                      Property:Property,
                      Filing_System:Filing_System,
                      Printer:Printer,
                      radius:radius,
                      IT_Services:IT_Services,
                      Service_Contact:Service_Contact,
                      Approve_Staff_Loan:Approve_Staff_Loan,
                      View_All_Staff_Loan:View_All_Staff_Loan,
                      Update_Medical_Claim:Update_Medical_Claim,
                      OT_Management_HR:OT_Management_HR,
                      OT_Management_HOD:OT_Management_HOD,
                      Payslip_Management:Payslip_Management,
                      Leave_Adjustment:Leave_Adjustment,
                      View_Department_Attendance_Summary:View_Department_Attendance_Summary,
                      View_Incentive_Summary:View_Incentive_Summary,
                      View_Staff_Deduction_Detail:View_Staff_Deduction_Detail,

                      View_Medical_Claim_Summary:View_Medical_Claim_Summary,
                      View_MIA:View_MIA,
                      Delivery_Dashboard:Delivery_Dashboard,
                      Delivery_Approval:Delivery_Approval,
                      Delete_Delivery:Delete_Delivery,
                      Delivery_Tracking:Delivery_Tracking,
                      Site_Delivery_Summary:Site_Delivery_Summary,
                      Warehouse_Checklist:Warehouse_Checklist,
                      Requestor_KPI:Requestor_KPI,
                      Driver_KPI:Driver_KPI,
                      Warehouse_KPI:Warehouse_KPI,

                      Material_Request:Material_Request,
                      View_Costing_Summary:View_Costing_Summary,
                      View_OSU_Costing_Summary:View_OSU_Costing_Summary,
                      Material_Approval:Material_Approval,
                      Driver_Incentive_Summary:Driver_Incentive_Summary,
                      Transport_Charges:Transport_Charges,
                      View_Material_Request:View_Material_Request,
                      View_PO_Listing:View_PO_Listing,
                      Generate_PO:Generate_PO,
                      Quotation_Approval:Quotation_Approval,
                      Upload_Quotation:Upload_Quotation,
                      Recall_MR:Recall_MR,
                      Speedfreak:Speedfreak,
                      Speedfreak_Summary:Speedfreak_Summary,
                      Delete_RQO:Delete_RQO,
                      View_All_Branch:View_All_Branch,
                      Update_Inventory:Update_Inventory,
                      Sales_Order:Sales_Order,
                      Storekeeper:Storekeeper,
                      Sales_Summary:Sales_Summary,
                      Delete_SO:Delete_SO,
                      Recur_SO:Recur_SO,
                      View_Invoice:View_Invoice,
                      Update_Inv_Num:Update_Inv_Num,
                      Update_Inv:Update_Inv,
                      Generate_Invoice:Generate_Invoice,
                      invoice_listing:invoice_listing,
                      dummy_do:dummy_do,
                      Costing:Costing,
                      autocount:autocount,
                      ewallet:ewallet,
                      Task_List:Task_List,
                      CME_Dashboard:CME_Dashboard,
                      SO_Details:SO_Details
                    },



                    success: function(response){

                      var message="Template saved!";

                      var x = document.getElementById("Template");
                      var option = document.createElement("option");

                      document.getElementById("Template_Name").value = ''
                      $("#exist-alert").modal("hide");
                      option.text = Template_Name;
                      option.value = response;
                      x.add(option);

                      $('#SaveTemplate').modal('hide')
                      $("#update-alert ul").html(message);
                      $("#update-alert").modal("show");

                      setTimeout(function() {
                        $("#update-alert").fadeOut();
                      }, 6000);

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

  function OpenRemoveDialog()
  {
    if ($("#Template option:selected").text()!="Default Template")
    {
        if ($("#Template option:selected").html()!="")
        {
          $templatename=$("#Template option:selected").html();
          $( "#removetemplatemessage" ).html("&nbsp;&nbsp;&nbsp; Are you sure you wish to remove <i>'"+ $templatename +"'</i> template?");
          $('#RemoveTemplate').modal('show');

        }
      }
      else {
        var message='Cannot remove "<i>Default Template</i>"!';

        $('#RemoveTemplate').modal('hide')
        $("#warning-alert ul").html(message);
        $("#warning-alert").modal("show");
      }

  }

  function OpenUpdateDialog()
  {
      if ($("#Template option:selected").html()!="")
      {
        $templatename=$("#Template option:selected").html();
        $( "#updatetemplatemessage" ).html("&nbsp;&nbsp;&nbsp; Are you sure you wish to update <i>'"+ $templatename +"'</i> template?");
        $('#UpdateTemplate').modal('show');

      }

  }

  </script>

@endsection
