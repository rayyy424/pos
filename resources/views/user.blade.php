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

    <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/fixedcolumns/3.2.2/css/fixedColumns.dataTables.min.css">
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

      .profile-user-dt-img{
        width: 80px;
        height: 100px;
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

      <script type="text/javascript" language="javascript" src="https://cdn.datatables.net/fixedcolumns/3.2.2/js/dataTables.fixedColumns.min.js"></script>
      <script type="text/javascript" language="javascript" src="{{ asset('/plugin/js/dataTables.editor.min.js') }}"></script>
      {{-- <script type="text/javascript" language="javascript" src="{{ asset('/plugin/examples/resources/syntax/shCore.js') }}"></script>
      <script type="text/javascript" language="javascript" src="{{ asset('/plugin/examples/resources/demo.js') }}"></script>
      <script type="text/javascript" language="javascript" src="{{ asset('/plugin/examples/resources/editor-demo.js') }}"></script> --}}

      <script type="text/javascript" language="javascript" class="init">

          var editor; // use a global for the submit and return data rendering in the examples
          var asInitVals = new Array();
          var oTable;
          var userid;

          $(document).ready(function() {

                         editor = new $.fn.dataTable.Editor( {
                                  ajax: "{{ asset('/Include/user.php') }}",
                                 table: "#usertable",
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
                                                 label: "Name:",
                                                 name: "users.Name"
                                         },{
                                                  label: "Nick Name:",
                                                  name: "users.Nick_Name"
                                          }, {
                                                 label: "Company Email:",
                                                 name: "users.Company_Email"
                                         }, {
                                                 label: "Personal Email:",
                                                 name: "users.Personal_Email"
                                         }, {
                                                 label: "Contact No 1:",
                                                 name: "users.Contact_No_1"
                                         }, {
                                                 label: "Contact No 2:",
                                                 name: "users.Contact_No_2"
                                         }, {
                                                 label: "Permanent Address:",
                                                 name: "users.Permanent_Address",
                                                 type: "textarea"
                                         }, {
                                                 label: "Current Address:",
                                                 name: "users.Current Address",
                                                 type: "textarea"
                                         }, {
                                                 label: "Country Base:",
                                                 name: "users.Country_Base",
                                                 type: "select",
                                                 options: [
                                                     { label :"", value: "" },
                                                     @foreach($options as $option)
                                                       @if ($option->Field=="Nationality")
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
                                         }, {
                                                 label: "User Type:",
                                                 name: "users.User_Type",
                                                 type:  "select",
                                                 options: [
                                                   @foreach($options as $option)
                                                     @if ($option->Field=="User_Type")
                                                       { label :"{{$option->Option}}", value: "{{$option->Option}}" },
                                                     @endif
                                                   @endforeach
                                                 ],
                                         }, {
                                         //         label: "Nationality:",
                                         //         name: "users.Nationality",
                                         //         type:  "select",
                                         //         options: [
                                         //             { label :"", value: "" },
                                         //             @foreach($options as $option)
                                         //               @if ($option->Field=="Nationality")
                                         //                 { label :"{{$option->Option}}", value: "{{$option->Option}}" },
                                         //               @endif
                                         //             @endforeach
                                         //         ],
                                         // }, {
                                                 label: "DOB:",
                                                 name: "users.DOB",
                                                 type:   'datetime',
                                                 def:    function () { return new Date(); },
                                                 format: 'DD-MMM-YYYY'
                                         }, {
                                                 label: "NRIC:",
                                                 name: "users.NRIC"
                                         }, {
                                                 label: "Passport No:",
                                                 name: "users.Passport_No"
                                         }, {
                                                 label: "Gender:",
                                                 name: "users.Gender",
                                                 type:  "select",
                                                 options: [
                                                   { label :"", value: "" },
                                                   @foreach($options as $option)
                                                     @if ($option->Field=="Gender")
                                                       { label :"{{$option->Option}}", value: "{{$option->Option}}" },
                                                     @endif
                                                   @endforeach
                                                 ],
                                         }, {
                                                 label: "Marital Status:",
                                                 name: "users.Marital_Status",
                                                 type:  "select",
                                                 options: [
                                                   { label :"", value: "" },
                                                   @foreach($options as $option)
                                                     @if ($option->Field=="Marital_Status")
                                                       { label :"{{$option->Option}}", value: "{{$option->Option}}" },
                                                     @endif
                                                   @endforeach
                                                 ],
                                         }, {
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
                                                 label: "Company:",
                                                 name: "users.Company",
                                                 type:  "select",
                                                 options: [
                                                   { label :"", value: "" },
                                                   @foreach($options as $option)
                                                     @if ($option->Field=="Company")
                                                       { label :"{{$option->Option}}", value: "{{$option->Option}}" },
                                                     @endif
                                                   @endforeach
                                                 ],
                                         },{
                                                  label: "Working_Days:",
                                                  name: "users.Working_Days",
                                                  type:  "select",
                                                  options: [
                                                    { label :"", value: "" },
                                                    { label :5.0, value: 5.0 },

                                                    { label :6.0, value: 6.0 },
                                                  ],
                                          }, {
                                                  label: "Holiday Territory:",
                                                  name: "users.HolidayTerritoryId",
                                                  type:  "select",
                                                  options: [
                                                    { label :"", value: "" },
                                                    @foreach($holidayterritories as $holidayterritory)
                                                      { label :"{{ $holidayterritory->Name }}", value: "{{$holidayterritory->Id}}" },
                                                    @endforeach
                                                  ],
                                          }, {
                                                 label: "Position:",
                                                 name: "users.Position",
                                                 type:  "select",
                                                 options: [
                                                   { label :"", value: "" },
                                                   @foreach($options as $option)
                                                     @if ($option->Field=="Position")
                                                       { label :"{{$option->Option}}", value: "{{$option->Option}}" },
                                                     @endif
                                                   @endforeach
                                                 ],
                                         }, {
                                                 label: "Nationality:",
                                                 name: "users.Nationality",
                                                 type:  "select",
                                                 options: [
                                                   { label :"", value: "" },
                                                   @foreach($options as $option)
                                                     @if ($option->Field=="Nationality")
                                                       { label :"{{$option->Option}}", value: "{{$option->Option}}" },
                                                     @endif
                                                   @endforeach
                                                 ],
                                         }, {
                                                 label: "Grade:",
                                                 name: "users.Grade",
                                                 type:  "select",
                                                 options: [
                                                   { label :"", value: "" },
                                                   @foreach($options as $option)
                                                     @if ($option->Field=="Grade")
                                                       { label :"{{$option->Option}}", value: "{{$option->Option}}" },
                                                     @endif
                                                   @endforeach
                                                 ],
                                         }, {
                                                 label: "Joining Date:",
                                                 name: "users.Joining_Date",
                                                 type:   'datetime',
                                                 format: 'DD-MMM-YYYY'
                                         }, {
                                                 label: "Confirmation Date:",
                                                 name: "users.Confirmation_Date",
                                                 type:   'datetime',
                                                 format: 'DD-MMM-YYYY'
                                         }, {
                                                 label: "Resignation Date:",
                                                 name: "users.Resignation_Date",
                                                 type:   'datetime',
                                                 format: 'DD-MMM-YYYY'
                                         }, {
                                                 label: "Internship Start Date:",
                                                 name: "users.Internship_Start_Date",
                                                 type:   'datetime',
                                                 format: 'DD-MMM-YYYY'
                                         }, {
                                                 label: "Internship End Date:",
                                                 name: "users.Internship_End_Date",
                                                 type:   'datetime',
                                                 format: 'DD-MMM-YYYY'
                                         }, {
                                                 label: "Race:",
                                                 name: "users.Race",type:  "select",
                                                 options: [
                                                   { label :"", value: "" },
                                                   @foreach($options as $option)
                                                     @if ($option->Field=="Race")
                                                       { label :"{{$option->Option}}", value: "{{$option->Option}}" },
                                                     @endif
                                                   @endforeach
                                                 ],
                                         }, {
                                                 label: "Religion:",
                                                 name: "users.Religion",
                                                 type:  "select",
                                                 options: [
                                                   { label :"", value: "" },
                                                   @foreach($options as $option)
                                                     @if ($option->Field=="Religion")
                                                       { label :"{{$option->Option}}", value: "{{$option->Option}}" },
                                                     @endif
                                                   @endforeach
                                                 ],
                                         }, {
                                                 label: "Place of Birth:",
                                                 name: "users.Place_Of_Birth"
                                         }, {
                                                 label: "Bank Name:",
                                                 name: "users.Bank_Name",
                                                 type:  "select",
                                                 options: [
                                                   { label :"", value: "" },
                                                   @foreach($options as $option)
                                                     @if ($option->Field=="Bank_Name")
                                                       { label :"{{$option->Option}}", value: "{{$option->Option}}" },
                                                     @endif
                                                   @endforeach
                                                 ],
                                         }, {
                                                 label: "Bank Account No:",
                                                 name: "users.Bank_Account_No"
                                         }, {
                                                 label: "EPF No:",
                                                 name: "users.EPF_No"
                                         }, {
                                                 label: "Ext No:",
                                                 name: "users.Ext_No"
                                         }, {
                                                 label: "SOCSO No:",
                                                 name: "users.SOCSO_No"
                                         }, {
                                                 label: "Income Tax No:",
                                                 name: "users.Income_Tax_No"
                                         }, {
                                                 label: "Emergency Contact Person:",
                                                 name: "users.Emergency_Contact_Person"
                                         }, {
                                                 label: "Emergency Contact No:",
                                                 name: "users.Emergency_Contact_No"
                                         }, {
                                                 label: "Emergency Contact Relationship:",
                                                 name: "users.Emergency_Contact_Relationship"
                                         }, {
                                                 label: "Emergency Contact Address:",
                                                 name: "users.Emergency_Contact_Address"
                                         }, {
                                                 label: "Driving License:",
                                                 name: "users.Driving_License",
                                                 type:  "select",
                                                 options: [
                                                   { label :"", value: "" },
                                                   @foreach($options as $option)
                                                     @if ($option->Field=="Driving_License")
                                                       { label :"{{$option->Option}}", value: "{{$option->Option}}" },
                                                     @endif
                                                   @endforeach
                                                 ],
                                         }, {
                                                 label: "Car Owner:",
                                                 name: "users.Car_Owner",type:  "select",
                                                 options: [
                                                   { label :"", value: "" },
                                                   @foreach($options as $option)
                                                     @if ($option->Field=="Car_Owner")
                                                       { label :"{{$option->Option}}", value: "{{$option->Option}}" },
                                                     @endif
                                                   @endforeach
                                                 ],
                                         }, {
                                                 label: "Criminal Activities",
                                                 name: "users.Criminal_Activity",
                                                 type:  "select",
                                                 options: [
                                                   { label :"", value: "" },
                                                   @foreach($options as $option)
                                                     @if ($option->Field=="Car_Owner")
                                                       { label :"{{$option->Option}}", value: "{{$option->Option}}" },
                                                     @endif
                                                   @endforeach
                                                 ],
                                         }

                                 ]
                         } );


                         // Activate an inline edit on click of a table cell
                               $('#usertable').on( 'click', 'tbody td:not(:first-child)', function (e) {
                                     editor.inline( this, {
               			                      onBlur: 'submit'
                                   } );
                               } );


                               oTable=$('#usertable').dataTable( {
                                       ajax: {
                                          "url": "{{ asset('/Include/user.php') }}",
                                          "data": {
                                              "Resigned": "{{ $resigned }}",
                                              'Start' : "{{$start}}",
                                              'End' : "{{$end}}",
                                              'Type' : "{{$type}}"                                          }
                                        },
                                       fnRowCallback: function (nRow, aData, iDisplayIndex, iDisplayIndexFull) {

                                         if(aData.users.Status=="Resigned" || aData.users.Status=="Contract Ended" || aData.users.Status=="Internship Ended")
                                         {
                                            $('td', nRow).closest('tr').css('color', 'red');
                                         }
                                         else
                                         {
                                            $('td', nRow).closest('tr').css('color', 'black');
                                         }

                                        return nRow;
                                      },
                                       columnDefs: [{ "visible": false, "targets": [2,4,16,17,20,21,22,23,24,25,26,27,28,29,30,31,32,33,34,35,36,37,38,39,40,41,42,43,44,45,46,47,48,49,50] },{"className": "dt-center", "targets": "_all"}],
                                       responsive: false,
                                       sScrollX: "100%",
                                       language : {
                                          sLoadingRecords : 'Loading data...',
                                          processing: 'Loading data...'
                                        },
                                       bAutoWidth: true,
                                       rowId: 'users.Id',
                                       sScrollY: "100%",
                                       dom: "Bfrtip",
                                       bScrollCollapse: true,
                                       aaSorting: [[8,"asc"]],
                                       columns: [
                                                {  data: null, "render":"", title:"No"},
                                                {
                                                   sortable: false,
                                                   "render": function ( data, type, full, meta ) {
                                                       @if ($me->Edit_User)
                                                          return '<a href="{{ URL::to('/')}}/userdetail/'+full.users.Id+'" alt="Edit" title="Edit"><i class="fa fa-pencil-square-o fa-2x"></i> </a> <a href="{{ URL::to('/')}}/user/'+full.users.Id+'/Export/" alt="View" title="View"><i class="fa fa-eye fa-2x" ></i></a>';
                                                       @else
                                                          return '-';
                                                       @endif

                                                   }
                                               },
                                               { data: "users.Id"},
                                               { data: "files.Web_Path",
                                               render: function (  data, type, full, meta ) {

                                                    if (data)
                                                    {
                                                      // return '<img height="64" width="64" src="'+ data +'"/>';
                                                      //return "<div class='user' style='background: url(\"https://www.google.com/search?q=profile+image&espv=2&biw=1280&bih=950&tbm=isch&imgil=TYfJzUB_6JYtoM%253A%253Bp5kd_UYfCDL1SM%253Bhttp%25253A%25252F%25252Fshushi168.com%25252Fprofile-pics.html&source=iu&pf=m&fir=TYfJzUB_6JYtoM%253A%252Cp5kd_UYfCDL1SM%252C_&usg=__5y_SbQEXwYMRGOd3pJFSgFFWkqE%3D&ved=0ahUKEwjHkfnL0JjQAhUFOY8KHVcBCYEQyjcIOQ&ei=x30hWIeSIoXyvATXgqSICA\") no-repeat center;background-size: 55px 70px;background-color:white;'></div>";
                                                      return '<img class="profile-user-dt-img" src="{{ URL::to('/')}}'+ data +'" alt="User profile picture">';
                                                    }
                                                    else {
                                                      return '<img class="profile-user-dt-img" src="{{ URL::to('/') ."/img/default-user.png"  }}" alt="User profile picture">';
                                                    }

                                                },
                                                title: "Image"
                                              },
                                              { data: "users.Status" },
                                              { data: "users.StaffId" , title:"Staff ID"},
                                              { data: "users.Name" },
                                              { data: "users.NRIC"},
                                              { data: "users.Joining_Date", title: "Joined Date"},
                                              { data: "users.Confirmation_Date", title: "Confirm Date"},
                                              { data: "users.Resignation_Date"},
                                              { data: "users.Position"},
                                              { data: "users.Contact_No_1", title:"Contact No"},
                                              { data: "users.Nationality"},
                                              { data: "users.Grade"},
                                              { data: "users.Company"},//15
                                              { data: "users.Entitled_for_OT"},
                                              { data: "users.Working_Days"},
                                              { data: "holidayterritories.Name", editField: "users.HolidayTerritoryId" },
                                              { data: "users.Ext_No", title:"Ext No"},
                                              { data: "users.Company_Email" },
                                              { data: "users.Nick_Name" },
                                              { data: "users.User_Type" },
                                               { data: "users.Personal_Email" },
                                               { data: "users.Contact_No_2"},
                                               { data: "users.Permanent_Address"},
                                               { data: "users.Current_Address"},
                                               { data: "users.Country_Base"},
                                               { data: "users.Home_Base"},
                                               // { data: "users.Nationality"},
                                               { data: "users.DOB"},
                                               { data: "users.Place_Of_Birth"},
                                               { data: "users.Race"},
                                               { data: "users.Religion"},
                                               { data: "users.Passport_No"},
                                               { data: "users.Gender"},
                                               { data: "users.Marital_Status"},//35
                                               { data: "superior.Name", editField: "users.SuperiorId" },

                                               // { data: "users.Resignation_Date"},
                                               { data: "users.Internship_Start_Date"},
                                               { data: "users.Internship_End_Date"},
                                               { data: "users.Bank_Name"},
                                               { data: "users.Bank_Account_No"},
                                               { data: "users.EPF_No"},
                                               { data: "users.SOCSO_No"},
                                               { data: "users.Income_Tax_No"},
                                               { data: "users.Emergency_Contact_Person"},
                                               { data: "users.Emergency_Contact_No"},
                                               { data: "users.Emergency_Contact_Relationship"},
                                               { data: "users.Emergency_Contact_Address"},
                                               { data: "users.Car_Owner"},
                                               { data: "users.Driving_License"},
                                               { data: "users.Criminal_Activity"}


                                       ],
                                       autoFill: {
                                          editor:  editor
                                      },
                                      // keys: {
                                      //     columns: ':not(:first-child)',
                                      //     editor:  editor
                                      // },
                                       select: true,
                                       buttons: [
                                         { extend: "remove", editor: editor },
                                         {
                                           text: 'Resigned',
                                           action: function ( e, dt, node, config ) {
                                               // clearing all select/input options
                                               window.location.href = "{{url('/userresigned')}}";
                                           },
                                         },
                                         {
                                             extend: 'excelHtml5',
                                             exportOptions: {
                                                 columns: ':visible'
                                             }
                                         },
                                               //{ extend: "create", editor: editor },
                                              //  {
                                              //          extend: 'collection',
                                              //          text: 'Export',
                                              //          buttons: [
                                              //                  'excel',
                                              //                  'csv',
                                              //                  'pdf'
                                              //          ]
                                              //  }
                                       ],

                           });

                          //  $('#usertable').on( 'click', 'tr', function () {
                          //    // Get the rows id value
                          //   //  var row=$(this).closest("tr");
                          //   //  var oTable = row.closest('table').dataTable();
                          //    userid = oTable.row( this ).data().users.Id;
                          //  });
                           //


                           $("thead input").keyup ( function () {

                                   /* Filter on the column (the index) of this element */

                                   if ($('#usertable').length > 0)
                                   {

                                       var colnum=document.getElementById('usertable').rows[0].cells.length;

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


                            oTable.api().on( 'order.dt search.dt', function () {
                                oTable.api().column(0, {search:'applied', order:'applied'}).nodes().each( function (cell, i) {
                                    cell.innerHTML = i+1;
                                } );
                            } ).draw();

                            $.fn.dataTable.tables( {visible: true, api: true} ).columns.adjust();

                             $('a[data-toggle="tab"]').on( 'shown.bs.tab', function (e) {
                               var target = $(e.target).attr("href") // activated tab



                             } );

                             $("#maincontent").css("zoom",0.9);
                             $.fn.dataTable.tables( {visible: true, api: true} ).columns.adjust();

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
        User Profile
        <small>Human Resource</small>
      </h1>
      <ol class="breadcrumb">
        <li><a href="{{ url('/home') }}"><i class="fa fa-dashboard"></i> Home</a></li>
        <li><a href="#">Human Resource</a></li>
        <li class="active">User Profile</li>
      </ol>
    </section>

    <!-- Main content -->
    <section class="content">
      <div class="row" id="maincontent">
        <div class="col-xs-12">
          <div class="box">
            <!-- /.box-header -->
            <div class="box-body">
                <table id="usertable" class="usertable" cellspacing="0" width="100%" padding="30px" style="font-size: 13px;">
				            <thead>

                      <tr class="search">

                        @foreach($users as $key=>$values)

                          @if ($key==0)

                            <?php $i = 0; ?>


                            @foreach($values as $field=>$a)
                                @if ($i==0|| $i==1 || $i==2 || $i==3)
                                  <th align='center'><input type='hidden' class='search_init' name='{{$i}}' placemark='{{$a}}'></th>
                                @else
                                  <th align='center'><input type='text' class='search_init' name='{{$i}}'  placemark='{{$a}}'></th>
                                @endif

                                <?php $i ++; ?>
                            @endforeach


                          @endif

                        @endforeach

                      </tr>


                        <tr>
                          @foreach($users as $key=>$value)

                            @if ($key==0)
                                  <td></td>
                                    <td></td>
                              @foreach($value as $field=>$value)
                                <td>
                                    {{ $field }}
                                </td>
                              @endforeach

                            @endif

                          @endforeach
                        </tr>

                    </thead>
                    <tbody>



                  </tbody>
                    <tfoot></tfoot>
			          </table>
            </div>
            <!-- /.box-body -->
          </div>
          <!-- /.box -->
          <!-- /.box -->
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
