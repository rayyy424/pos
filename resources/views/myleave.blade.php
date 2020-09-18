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

    <style type="text/css" class="init">
      a.buttons-collection {
        margin-left: 1em;
      }
      .container1{
              width: 1200px;
              margin-left: 50px;
              padding: 10px;
      }
      #list td:nth-child(1),#list td:nth-child(2),#list td:nth-child(3),#list td:nth-child(5) {
        white-space: nowrap;
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

          var leaveseditor;
          var leaves2editor;
          var leaves3editor;
          var leavetable;
          var leave2table;
          var leave3table;
          var leave4table;
          var asInitVals = new Array();
          var leaveid;

          $(document).ready(function() {
                         leaveseditor = new $.fn.dataTable.Editor( {
                                ajax: "{{ asset('/Include/leave.php') }}",
                                 table: "#leavetable",
                                 idSrc: "leaves.Id",
                                 fields: [
                                        {
                                                 label: "Leave Type:",
                                                 name: "leaves.Leave_Type",
                                                 type:  'readonly'
                                         },{
                                                 label: "Start Date:",
                                                 name: "leaves.Start_Date",
                                                 type:   'datetime',
                                                 def:    function () { return new Date(); },
                                                 format: 'DD-MMM-YYYY'
                                         }, {
                                                 label: "End Date:",
                                                 name: "leaves.End_Date",
                                                 type:   'datetime',
                                                 def:    function () { return new Date(); },
                                                 format: 'DD-MMM-YYYY'
                                         }, {
                                                 label: "Reason:",
                                                 name: "leaves.Reason",
                                                 type:  'readonly'
                                         }, {
                                                 label: "File:",
                                                 name: "files.Web_Path",
                                                 type: "upload",
                                                 ajaxData: function ( d ) {
                                                   d.append( 'Id', leaveid ); // edit - use `d`
                                                 },
                                                 display: function ( file_id ) {
                                                   return '<img src="'+ leavetable.row( leaveseditor.modifier() ).data().files.Web_Path +'">';
                                                 },
                                                 clearText: "Clear",
                                                 noImageText: 'No file'
                                         }

                                 ]
                         } );

                         $('#leavetable').on( 'click', 'tr', function () {
                           // Get the rows id value
                          //  var row=$(this).closest("tr");
                          //  var oTable = row.closest('table').dataTable();
                           leaveid = leavetable.row( this ).data().leaves.Id;
                         });

                         leaves2editor = new $.fn.dataTable.Editor( {
                                ajax: "{{ asset('/Include/leave.php') }}",
                                 table: "#leave2table",
                                 idSrc: "leaves.Id",
                                 fields: [
                                        {
                                                 label: "Leave Type:",
                                                 name: "leaves.Leave_Type",
                                                 type:  'readonly'
                                         },{
                                                 label: "Leave Term:",
                                                 name: "leaves.Leave_Term",
                                                 type:  'readonly'
                                         }, {
                                                 label: "Start Date:",
                                                 name: "leaves.Start_Date",
                                                 type:  'readonly'
                                         }, {
                                                 label: "End Date:",
                                                 name: "leaves.End_Date",
                                                type:  'readonly'
                                         }, {
                                                 label: "No of Days:",
                                                 name: "leaves.No_of_Days",
                                                 type:  'readonly'
                                         }, {
                                                 label: "Reason:",
                                                 name: "leaves.Reason",

                                                 type:  'readonly'
                                         }

                                 ]
                         } );

                         leaves3editor = new $.fn.dataTable.Editor( {
                                ajax: "{{ asset('/Include/leave.php') }}",
                                 table: "#leave3table",
                                 idSrc: "leaves.Id",
                                 fields: [
                                        {
                                                 label: "Leave Type:",
                                                 name: "leaves.Leave_Type",
                                                 type:  'readonly'
                                         }, {
                                                 label: "Start Date:",
                                                 name: "leaves.Start_Date",
                                                 type:  'readonly'
                                         }, {
                                                 label: "End Date:",
                                                 name: "leaves.End_Date",
                                                 type:  'readonly'
                                         }, {
                                                 label: "No of Days:",
                                                 name: "leaves.No_of_Days",
                                                 type:  'readonly'
                                         }, {
                                                 label: "Reason:",
                                                 name: "leaves.Reason",
                                                 type:  'readonly'
                                         }

                                 ]
                         } );

                         leavetable=$('#leavetable').DataTable( {
                           ajax: {
                             "url": "{{ asset('/Include/leave.php') }}",
                             "data": {
                                 "UserId": {{ $me->UserId }},
                                 "Status": "%Pending%"
                             }
                           },
                                 columnDefs: [{ "visible": false, "targets": [1,2,3] },{"className": "dt-center", "targets": "_all"}],
                                 responsive: false,
                                 colReorder: false,
                                 dom: "fBrtp",
                                 sScrollX: "100%",
                                 bAutoWidth: true,
                                 sScrollY: "100%",
                                 scrollCollapse: true,
                                 // aaSorting:false,
                                 fnInitComplete: function(oSettings, json) {

                                   $('#mypendingleavetab').html("My Pending Leave" + "[" + leavetable.rows().count() +"]")

                                  },
                                 columns: [
                                         {
                                            sortable: false,
                                            "render": function ( data, type, full, meta ) {

                                              if (full.leaves.Leave_Type=="Compassionate Leave" ||
                                              full.leaves.Leave_Type=="Maternity Leave" ||
                                              full.leaves.Leave_Type=="Marriage Leave" ||
                                              full.leaves.Leave_Type=="Paternity Leave")
                                              {
                                                console.log(full.leavestatuses.Id);
                                                if (full.files.Web_Path && (full.leavestatuses.Id != null))
                                                {
                                                return '<button type="button" class="btn btn-success btn-sm" data-toggle="modal" onclick="OpenRedirectDialog('+full.leaves.Id+','+full.leavestatuses.UserId+')">Redirect</button> <button type="button" class="btn btn-danger btn-sm" data-toggle="modal" onclick="CancelDialog('+full.leaves.Id+')">Cancel Leave</button>';

                                                }
                                                else if(full.files.Web_Path)
                                                {
                                                  return '<button type="button" class="btn btn-primary btn-sm" data-toggle="modal" onclick="OpenRedirectDialog('+full.leaves.Id+','+full.leavestatuses.UserId+')">Submit</button>  <button type="button" class="btn btn-danger btn-sm" data-toggle="modal" onclick="CancelDialog('+full.leaves.Id+')">Cancel Leave</button>';
                                                }
                                                else {
                                                  return '<button type="button" class="btn btn-danger btn-sm" data-toggle="modal" onclick="CancelDialog('+full.leaves.Id+')">Cancel Leave</button>';

                                                }

                                              }
                                              else if (full.leavestatuses.Leave_Status!=="Cancelled" && full.leavestatuses.Leave_Status!=="Final Approved")
                                              {
                                                return '<button type="button" class="btn btn-success btn-sm" data-toggle="modal" onclick="OpenRedirectDialog('+full.leaves.Id+','+full.leavestatuses.UserId+')">Redirect</button>  <button type="button" class="btn btn-danger btn-sm" data-toggle="modal" onclick="CancelDialog('+full.leaves.Id+')">Cancel Leave</button>';

                                              }
                                              else {
                                                return '<button type="button" class="btn btn-success btn-sm" data-toggle="modal" onclick="OpenRedirectDialog('+full.leaves.Id+','+full.leavestatuses.UserId+')">Redirect</button> <button type="button" class="btn btn-danger btn-sm" data-toggle="modal" onclick="CancelDialog('+full.leaves.Id+')">Cancel Leave</button>';

                                              }

                                            }
                                        },
                                         { data: "leaves.Id"},
                                         { data: "leavestatuses.Id"},
                                         { data: "applicant.Name",title:"Name"},
                                         { data: "leaves.Leave_Type",title:"Leave_Type" },
                                         {
                                            sortable: false,
                                            title:"Leave_Term",
                                            render: function ( data, type, full, meta ) {
                                              if (full.leaves.Leave_Type == 'Maternity Leave' || full.leaves.Leave_Type == 'Hospitalization Leave' || full.leaves.Leave_Type == '1 Hour Time Off' || full.leaves.Leave_Type == '2 Hours Time Off') {
                                                return '<button type="button" class="btn btn-xs btn-default" data-toggle="modal" data-target="#viewLeaveTermsModal" onclick="viewLeaveTerms(' + full.leaves.Id + ')">View</button>';
                                              } else {
                                                return '<button type="button" class="btn btn-xs btn-default" data-toggle="modal" data-target="#viewLeaveTermsModal" onclick="viewLeaveTerms(' + full.leaves.Id + ')">View</button>';
                                                // return '<div class="btn-group" role="group"><button type="button" class="btn btn-xs btn-default" data-toggle="modal" data-target="#editLeaveTermsModal" onclick="editLeaveTerms(' + full.leaves.Id + ')">Edit</button><button type="button" class="btn btn-xs btn-default" data-toggle="modal" data-target="#viewLeaveTermsModal" onclick="viewLeaveTerms(' + full.leaves.Id + ')">View</button></div>';
                                              }

                                            }
                                          },
                                         { data: "leaves.Start_Date",title:"Start_Date"},
                                         { data: "leaves.End_Date",title:"End_Date"},
                                         { data: "leaves.No_of_Days",title:"No_of_Days"},
                                         { data: "leaves.Reason",title:"Reason"},
                                         { data: "leaves.created_at",title:"Application_Date"},
                                         { data: "approver.Name", editField: "leavestatuses.UserId",title:"Approver" },
                                         { data: "leavestatuses.Leave_Status",title:"Leave_Status"},
                                         { data: "leavestatuses.updated_at",title:"Review_Date"},
                                         { data: "leavestatuses.Comment",title:"Comment"},
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
                                 select: {
                                         style:    'os',
                                         selector: 'tr'
                                       },
                                 buttons: [
                                          //temporary fix..later need to implement through modal
                                         // { extend: "edit",   editor: leaveseditor },

                                 ],

                     });



                     leave2table=$('#leave2table').DataTable( {
                       ajax: {
                         "url": "{{ asset('/Include/leave.php') }}",
                         "data": {
                             "UserId": {{ $me->UserId }},
                             "Status": "%Approved%"
                         }
                       },
                             columnDefs: [{ "visible": false, "targets": [1,2,3] },{"className": "dt-center", "targets": "_all"}],
                             responsive: false,
                             colReorder: false,
                             dom: "pBrt",
                             sScrollX: "100%",
                             bAutoWidth: true,
                             sScrollY: "100%",
                             scrollCollapse: true,
                             // aaSorting:false,
                             fnInitComplete: function(oSettings, json) {

                               $('#myapprovedleavetab').html("My Approved Leave" + "[" + leave2table.rows().count() +"]")

                              },
                             columns: [
                               {
                                  sortable: false,
                                  "render": function ( data, type, full, meta ) {

                                      if (full.leavestatuses.Leave_Status!=="Cancelled" && full.leavestatuses.Leave_Status!=="Final Approved")
                                    {
                                      return '<button type="button" class="btn btn-success btn-sm" data-toggle="modal" onclick="OpenRedirectDialog('+full.leaves.Id+','+full.leavestatuses.UserId+')">Redirect</button> <button type="button" class="btn btn-danger btn-sm" data-toggle="modal" onclick="CancelDialog('+full.leaves.Id+')">Cancel Leave</button>';

                                    }
                                    else {
                                      return '';

                                    }

                                  }
                              },
                               { data: "leaves.Id"},
                               { data: "leavestatuses.Id"},
                               { data: "applicant.Name",title:"Name"},
                               { data: "leaves.Leave_Type",title:"Leave_Type" },
                               {
                                  sortable: false,
                                  title:"Leave_Term",
                                  render: function ( data, type, full, meta ) {
                                    return '<button type="button" class="btn btn-xs btn-default" data-toggle="modal" data-target="#viewLeaveTermsModal" onclick="viewLeaveTerms(' + full.leaves.Id + ')">View</button>';
                                  }
                               },
                               { data: "leaves.Start_Date",title:"Start_Date"},
                               { data: "leaves.End_Date",title:"End_Date"},
                               { data: "leaves.No_of_Days",title:"No_of_Days"},
                               { data: "leaves.Reason",title:"Reason"},
                               { data: "leaves.created_at",title:"Application_Date"},
                               { data: "approver.Name", editField: "leavestatuses.UserId",title:"Approver" },
                               { data: "leavestatuses.Leave_Status",title:"Leave_Status"},
                               { data: "leavestatuses.updated_at",title:"Review_Date"},
                               { data: "leavestatuses.Comment",title:"Comment"},
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
                             select: {
                                     style:    'os',
                                     selector: 'tr'
                                   },
                             buttons: [

                             ],

                 });

                 leave3table=$('#leave3table').DataTable( {
                   ajax: {
                     "url": "{{ asset('/Include/leave.php') }}",
                     "data": {
                         "UserId": {{ $me->UserId }},
                         "Status": "%Rejected%"
                     }
                   },
                         columnDefs: [{ "visible": false, "targets": [0,1,2] },{"className": "dt-center", "targets": "_all"}],
                         responsive: false,
                         colReorder: false,
                         dom: "pBrt",
                         sScrollX: "100%",
                         bAutoWidth: true,
                         sScrollY: "100%",
                         scrollCollapse: true,
                         // aaSorting:false,
                         fnInitComplete: function(oSettings, json) {

                           $('#myrejectedleavetab').html("My Rejected Leave" + "[" + leave3table.rows().count() +"]")

                          },
                         columns: [
                           { data: "leaves.Id"},
                           { data: "leavestatuses.Id"},
                           { data: "applicant.Name",title:"Name"},
                           { data: "leaves.Leave_Type",title:"Leave_Type" },
                           { sortable: false,
                                  title:"Leave_Term",
                              render: function ( data, type, full, meta ) {
                                return '<button type="button" class="btn btn-xs btn-default" data-toggle="modal" data-target="#viewLeaveTermsModal" onclick="viewLeaveTerms(' + full.leaves.Id + ')">View</button>';
                              }
                           },
                           { data: "leaves.Start_Date",title:"Start_Date"},
                           { data: "leaves.End_Date",title:"End_Date"},
                           { data: "leaves.No_of_Days",title:"No_of_Days"},
                           { data: "leaves.Reason",title:"Reason"},
                           { data: "leaves.created_at",title:"Application_Date"},
                           { data: "approver.Name", editField: "leavestatuses.UserId",title:"Approver" },
                           { data: "leavestatuses.Leave_Status",title:"Leave_Status"},
                           { data: "leavestatuses.updated_at",title:"Review_Date"},
                           { data: "leavestatuses.Comment",title:"Comment"},
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
                         select: {
                                 style:    'os',
                                 selector: 'tr'
                               },
                         buttons: [

                         ],

             });

             leave4table=$('#leave4table').DataTable( {
               ajax: {
                 "url": "{{ asset('/Include/leave.php') }}",
                 "data": {
                     "UserId": {{ $me->UserId }},
                     "Status": "%Cancelled%"
                 }
               },
                     columnDefs: [{ "visible": false, "targets": [0,1,2] },{"className": "dt-center", "targets": "_all"}],
                     responsive: false,
                     colReorder: false,
                     dom: "pBrt",
                     sScrollX: "100%",
                     bAutoWidth: true,
                     sScrollY: "100%",
                     scrollCollapse: true,
                     // aaSorting:false,
                     fnInitComplete: function(oSettings, json) {

                       $('#mycancelledleavetab').html("My Cancelled Leave" + "[" + leave4table.rows().count() +"]")

                      },
                     columns: [
                       { data: "leaves.Id"},
                       { data: "leavestatuses.Id"},
                       { data: "applicant.Name",title:"Name"},
                       { data: "leaves.Leave_Type",title:"Leave_Type" },
                       { sortable: false,
                                  title:"Leave_Term",
                          render: function ( data, type, full, meta ) {
                            return '<button type="button" class="btn btn-xs btn-default" data-toggle="modal" data-target="#viewLeaveTermsModal" onclick="viewLeaveTerms(' + full.leaves.Id + ')">View</button>';
                          }
                       },
                       { data: "leaves.Start_Date",title:"Start_Date"},
                       { data: "leaves.End_Date",title:"End_Date"},
                       { data: "leaves.No_of_Days",title:"No_of_Days"},
                       { data: "leaves.Reason",title:"Reason"},
                       { data: "leaves.created_at",title:"Application_Date"},
                       { data: "approver.Name", editField: "leavestatuses.UserId",title:"Approver" },
                       { data: "leavestatuses.Leave_Status",title:"Leave_Status"},
                       { data: "leavestatuses.updated_at",title:"Review_Date"},
                       { data: "leavestatuses.Comment",title:"Comment"},
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
                     select: {
                             style:    'os',
                             selector: 'tr'
                           },
                     buttons: [

                     ],

         });

             $("#ajaxloader").hide();
             $("#ajaxloader2").hide();
             $("#ajaxloader3").hide();

             $('a[data-toggle="tab"]').on( 'shown.bs.tab', function (e) {
               var target = $(e.target).attr("href") // activated tab

               if (target=="#mypendingleave")
               {
                 $.fn.dataTable.tables( {visible: true, api: true} ).columns.adjust();
               }
               else if (target=="#myapprovedleave")
               {
                 $.fn.dataTable.tables( {visible: true, api: true} ).columns.adjust();
               }
               else if(target=="#myrejectedleave")
               {
                 $.fn.dataTable.tables( {visible: true, api: true} ).columns.adjust();
               }
               else if(target=="#mycancelledleave")
               {
                 $.fn.dataTable.tables( {visible: true, api: true} ).columns.adjust();
               }
               else if(target=="#myleavebalance")
               {
                 $.fn.dataTable.tables( {visible: true, api: true} ).columns.adjust();
               }

             } );

        } );

      </script>
      <script type="text/javascript">
        $(document).ready(function() {
            $('#leavebalancetable').DataTable({
                    columnDefs: [{ "visible": false, "targets": [1,4,5,6] },{"className": "dt-center", "targets": "_all"}],
                     searching: false,
                     paging:   false,
                     ordering: false,
                     info:     false,
                     columns:[
                        { title:'Leave Type'},
                        { title:'Yearly'},
                        { title:'Entitlement'},
                        { title:'Adjustment'},
                        { title:'Replacement Adjustment'},
                        { title:'Leave Taken Before July' },
                        { title:'Leave Taken With Timeoff'},
                        { title:'Leave Taken'},
                        { title:'Burnt'},
                        { title:'Carried Forward Count'},
                        { title:'Carry Forward Balance'},
                        { title:'Timeoff Taken'},
                        { title:'EL Taken'},
                        { title:'Pending', data: 'Pending'},
                        { title:'Leave Balance', data: 'Leave_Balance',
                          render: function ( data, type, full, meta ) {
                            return parseFloat(data) + parseFloat(full.Pending);
                          }
                        },
                        { title:'Replacement Taken',data: 'Replacement'},
                        { title:'Replacement Pending',data:'Replacement_Pending',},
                        { title:'Replacement Balance',data:'Replacement_Balance',
                          render: function ( data, type, full, meta ) {
                            return parseFloat(data) + parseFloat(full.Replacement_Pending);
                          }
                        },
                        { title:'Total Balance', data: 'Total_Leave_Balance',
                          render: function ( data, type, full, meta ) {
                            return parseFloat(data) + parseFloat(full.Pending) + parseFloat(full.Replacement_Pending);
                          }
                        }

                     ]
            });

            leavesummary = $('#leavesummary').dataTable( {

                    columnDefs: [{ "visible": false, "targets": [0,1] },{"className": "dt-center", "targets": "_all"}],

                    searching: false,
                    paging:   false,
                    ordering: false,
                    info:     false,
                    columns:[
                      { data:'users.Id', title:'users.Id'},
                      { data:'users.StaffId', title:'Staff_ID'},
                      // { data:'users.Name', title:'Name'},
                      @foreach($leavetype as $leave)
                      <?php
                        if (($leave->Option == 'Maternity Leave' && $me->Gender == 'MALE') || ($leave->Option == 'Paternity Leave' && $me->Gender == 'FEMALE')) {
                          continue;
                        } elseif (($leave->Option == 'Paternity Leave' || $leave->Option == 'Maternity Leave') && $me->Gender == '') {
                          continue;

                        } elseif ($leave->Option == 'Marriage Leave' && $me->Marital_Status == 'MARRIED') {
                            continue;
                        }
                      ?>
                      { data : "{{$leave->Option}}", titlte : "{{$leave->Option}}",
                        "render": function ( data, type, full, meta ) {
                          return "<a onclick='viewdata(\"{{$leave->Option}}\",\""+full.users.Id+"\",\"{{$start}}\",\"{{$end}}\")'>"+data+"</a>";
                        }
                      },
                      @endforeach
                    ],

                    autoFill: {


                   },
                   buttons: [
                   ],

              });
        });
      </script>
@endsection


@section('content')



    <div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
      <h1>
        My Leave
        <small>My Workplace</small>
      </h1>
      <ol class="breadcrumb">
        <li><a href="{{ url('/home') }}"><i class="fa fa-dashboard"></i> Home</a></li>
        <li><a href="#">My Workplace</a></li>
        <li class="active">My Leave</li>
      </ol>
    </section>

    <!-- Main content -->
    <section class="content">
      <div class="row">
        <div class="modal fade" id="NameList" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
            <div class="modal-dialog" role="document">
              <div class="modal-content">
                <div class="modal-header">
                  <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                  <h4 class="modal-title" id="myModalLabel">Leave List</h4>
                </div>
                <div class="modal-body" name="list" id="list">

                </div>
                <div class="modal-footer">
                  <center><img src="{{ URL::to('/') ."/img/ajax-loader.gif" }}" width="50px" height="50px" alt="Loading" name='ajaxloader3' id="ajaxloader5"></center>
                  <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                </div>
              </div>
            </div>
        </div>
        <div class="modal fade" id="editLeaveTermsModal" role="dialog" aria-labelledby="editLeaveTermsModalLabel" style="display: none;">
          <div class="modal-dialog modal-sm" role="document">
            <div class="modal-content">
              <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">×</span></button>
                <h4 class="modal-title" id="editLeaveTermsModalLabel">Leave Terms</h4>
              </div>
              <form id="formUpdateLeaveTerms">
                <input type="hidden" name="Leave_Id" id="Update_Leave_Id">
              <div class="modal-body">

                <input type="hidden" name="_method" value="PUT">
                <table id="editLeaveTermsTable" class="table table-condensed">
                  <thead>
                    <tr>
                      <th>Date</th>
                      <th>Period</th>
                    </tr>
                  </thead>
                  <tbody>
                  </tbody>
                </table>
                 <span id="helpBlock" class="help-block"><small>Change the leave period and click Save.</small></span>

              </div>
              <div id="updateLeaveTermsStatus">
                <center><img src="{{ URL::to('/') . "/img/ajax-loader.gif" }}" width="50px" height="50px" alt="Loading" name='ajaxloader4' id="ajaxloader4"></center>
              </div>
              <div class="modal-footer">
                <button type="button" class="btn btn-success" onclick="updateLeaveTerms()">Save</button>
                <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
              </div>
              </form>
            </div>
          </div>
        </div>
        <div class="modal fade" id="viewLeaveTermsModal" role="dialog" aria-labelledby="myLeaveTermsModalLabel" style="display: none;">
          <div class="modal-dialog modal-sm" role="document">
            <div class="modal-content">
              <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">×</span></button>
                <h4 class="modal-title" id="myLeaveTermsModalLabel">Leave Terms</h4>
              </div>
              <div class="modal-body">
                <table id="viewLeaveTermsTable" class="table table-condensed">
                  <thead>
                    <tr>
                      <th>Date</th>
                      <th>Period</th>
                    </tr>
                  </thead>
                  <tbody>
                  </tbody>
                </table>
              </div>
              <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
              </div>
            </div>
          </div>
        </div>
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

       <!--  <div id="update-alert" class="alert alert-success alert-dismissible" style="display:none;">
          <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
          <h4><i class="icon fa fa-check"></i> Alert!</h4>
          <ul>

          </ul>
        </div>

         <div id="error-alert" class="alert alert-danger alert-dismissible" style="display:none;">
           <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
           <h4><i class="icon fa fa-ban"></i> Alert!</h4>
           <ul>

           </ul>
         </div> -->

        <div class="modal fade" id="Redirect" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
          <div class="modal-dialog" role="document">
            <div class="modal-content">
              <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title" id="myModalLabel">Redirect</h4>
              </div>
              <div class="modal-body">
                <div class="form-group" id="redirectleavestatus">

                </div>
                <div class="form-group">

                    <label>Approver : </label>

                    <select class="form-control select2" id="NewApprover" name="NewApprover" style="width: 100%;">
                      <option></option>

                      @foreach ($approver as $user)
                        @if ($user->Country!="")
                          <option  value="{{$user->Id}}">{{$user->Name}} - {{$user->Country}}</option>
                        @else
                          <option  value="{{$user->Id}}">{{$user->Name}}</option>
                        @endif

                      @endforeach

                      </select>

                </div>
              </div>
              <div class="modal-footer">
                <center><img src="{{ URL::to('/') . "/img/ajax-loader.gif" }}" width="50px" height="50px" alt="Loading" name='ajaxloader' id="ajaxloader"></center>
                <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                <button type="button" class="btn btn-primary" onclick="redirect()">Redirect</button>
              </div>
            </div>
          </div>
        </div>

        <div class="modal fade" id="Cancel" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
          <div class="modal-dialog" role="document">
            <div class="modal-content">
              <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title" id="myModalLabel">Update Profile</h4>
              </div>
              <div class="modal-body">
                <div class="form-group" id="cancelleave">

                </div>
                  Are you sure you wish to cancel this leave?
              </div>
              <div class="modal-footer">
                <center><img src="{{ URL::to('/') ."/img/ajax-loader.gif" }}" width="50px" height="50px" alt="Loading" name='ajaxloader2' id="ajaxloader2"></center>
                <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                <button type="button" class="btn btn-primary" onclick="cancelleave()">Cancel Leave</button>
              </div>
            </div>
          </div>
        </div>

        <div class="col-md-12">
          <div class="nav-tabs-custom">
            <ul class="nav nav-tabs">
            @if($datediff > 90)
              <li class="active"><a href="#myleavebalance" data-toggle="tab" id="myleavebalancetab">My Leave Summary</a></li>
              <li><a href="#leaveapplicationform" data-toggle="tab">My Leave Application Form</a></li>
            @else
              <li class="active"><a href="#leaveapplicationform" data-toggle="tab">My Leave Application Form</a></li>
            @endif
              <li><a href="#mypendingleave" data-toggle="tab" id="mypendingleavetab">My Pending Leave</a></li>
              <li><a href="#myapprovedleave" data-toggle="tab" id="myapprovedleavetab">My Approved Leave</a></li>
              <li><a href="#myrejectedleave" data-toggle="tab" id="myrejectedleavetab">My Rejected Leave</a></li>
              <li><a href="#mycancelledleave" data-toggle="tab" id="mycancelledleavetab">My Cancelled Leave</a></li>
            </ul>

          <div class="tab-content">
            @if($datediff > 90)
            <div class="active tab-pane" id="myleavebalance">
              <div class="row">
                <div class="col-md-6">
                  <div class="input-group">
                    <div class="input-group-addon">
                      <i class="fa fa-clock-o"></i>
                    </div>
                    <input type="text" class="form-control" id="range" name="range">

                  </div>
                </div>
                <div class="col-md-6">
                    <div class="input-group">
                      <button type="button" class="btn btn-success btn" data-toggle="modal" onclick="refresh();">Refresh</button>
                    </div>
                </div>
                <br><br>
              </div>
              <table id="leavesummary" class="leavesummary" cellspacing="0" width="100%" padding="30px" style="font-size: 13px;">
                    <thead>
                        <tr>
                          @foreach($leavesummary as $key=>$value)

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
                      @foreach($leavesummary as $leave)

                          <tr id="row_{{ $i }}">

                              @foreach($leave as $key=>$value)
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
              <hr>
              <table id="leavebalancetable" class="display" cellspacing="0" width="100%" padding="30px" style="font-size: 13px;">
                  <thead>
                      {{-- prepare header search textbox --}}
                      <tr>
                        @foreach($leavebalance as $key=>$value)

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
                    @foreach($leavebalance as $leave)

                      {{-- @if(strpos($leave->Status,"Pending")!==false) --}}
                        <tr id="row_{{ $i }}">

                            @foreach($leave as $key=>$value)

                              <td>
                                {{ is_numeric($value) ? (float)$value : $value }}
                              </td>
                            @endforeach
                        </tr>
                        <?php $i++; ?>
                      {{-- @endif --}}

                    @endforeach

                </tbody>
                  <tfoot></tfoot>
              </table>
            </div>
            @endif
            <div class="{{ $datediff > 90 ?: 'active' }} tab-pane" id="leaveapplicationform">
            <form enctype="multipart/form-data" id="upload_form" role="form" method="POST" action="" >
              <div class="box box-solid">

                <div class="box-header with-border">
                  <div class="box-body">
                    <div class="row">
                      <div class="col-md-6">
                        <input type="hidden" name="UserId" value="{{ $me->UserId }}">
                        <div class="form-group">
                          <label>Leave Type : </label>
                          <select class="form-control select2" id="Leave_Type" name="Leave_Type" style="width: 100%;">
                             <option></option>

                             @foreach($options as $option)
                               @if ($option->Field=="Leave_Type")
                               <?php
                                  if ($option->Option != 'Unpaid Leave' && $option->Option != 'Emergency Leave' && $option->Option != 'Medical Leave' && $datediff <= 90) {
                                    continue;
                                  }
                                 if (($option->Option == 'Maternity Leave' && $me->Gender == 'Male') || ($option->Option == 'Paternity Leave' && $me->Gender == 'Female')) {
                                   continue;
                                 } elseif (($option->Option == 'Paternity Leave' || $option->Option == 'Maternity Leave') && $me->Gender == '') {
                                   continue;

                                 }
                               ?>
                                 <option <?php if(Input::old('Leave_Type') == '{{$option->Option}}') echo ' selected="selected" '; ?>>{{$option->Option}}</option>
                               @endif
                             @endforeach

                          </select>
                        </div>
                        <!-- Date -->
                        <div class="form-group">
                          <label>Start Date : </label>

                          <div class="input-group date">
                            <div class="input-group-addon">
                              <i class="fa fa-calendar"></i>
                            </div>
                            <input type="text" autocomplete="off" class="form-control pull-right" id="Start_Date" name="Start_Date" value="{!! old('Start_Date') !!}">
                          </div>
                          <!-- /.input group -->
                        </div>
                        <!-- /.form group -->

                        <!-- Date -->
                        <div class="form-group">
                          <label>End Date : </label>

                          <div class="input-group date">
                            <div class="input-group-addon">
                              <i class="fa fa-calendar"></i>
                            </div>
                            <input type="text" autocomplete="off" class="form-control pull-right" id="End_Date" name="End_Date" value="{!! old('End_Date') !!}">
                          </div>
                          <!-- /.input group -->
                        </div>
                        <!-- /.form group -->
                        <div class="box">
                          <div class="box-body no-padding">
                            <table class="table table-condensed" id="LeaveListTable">
                              <thead>
                                <tr>
                                  <th>Date</th>
                                  <th>Description</th>
                                  <th>Period</th>
                                </tr>
                                <tr>
                                  <td colspan="3" class="text-center"><h5>Please select start and end dates<h5></td>
                                </tr>
                              </thead>
                              <tbody>
                              </tbody>
                            </table>
                          </div>
                          <!-- /.box-body -->
                        </div>


                        <div class="form-group">
                          <label>No of Days : </label>

                          <div class="input-group date">
                            <input type="text" class="form-control pull-right" id="No_Of_Days" name="No_Of_Days" value="{!! old('No_Of_Days') !!}" readonly>
                            <br><br><img src="{{ URL::to('/') ."/img/ajax-loader.gif" }}" width="50px" height="50px" alt="Loading" name='ajaxloader3' id="ajaxloader3">
                          </div>
                          <!-- /.input group -->
                        </div>



                      </div>
                      <div class="col-md-6">
                        <!-- textarea -->
                        <div class="form-group">
                          <label>Reason : </label>
                          <textarea class="form-control" rows="3" name="Reason" id="Reason" placeholder="Enter your reason here ...">{!! old('Reason') !!}</textarea>
                        </div>

                        <div class="form-group">
                          <label>Approver : </label>

                          <select class="form-control select2" id="Approver" name="Approver" style="width: 100%;">
                            <option></option>

                            @foreach ($approver as $user)
                              @if ($user->Country!="")
                                <option  value="{{$user->Id}}">{{$user->Name}} - {{$user->Country}} [{{$user->Level}}]</option>
                              @else
                                <option  value="{{$user->Id}}">{{$user->Name}} [{{$user->Level}}]</option>
                              @endif

                            @endforeach

                            </select>
                        </div>

                        <div class="form-group">
                          <label>Attachment : </label>

                          <input type="file" id="attachment[]" name="attachment[]" accept=".png,.jpg,.jpeg,.pdf" multiple>
                        </div>
                        <div class="form-group">
                          <button type="submit" class="btn btn-primary" onclick="applyleave()" id="btnapplyleave">Submit Application</button>

                        </div>
                      </div>
                      <!-- /.box-body -->
                    </div>
                  </div>

                </div>
              </div>
            </form>

            </div>

              <div class="tab-pane" id="mypendingleave">
                <table id="leavetable" class="display" cellspacing="0" width="100%" padding="30px" style="font-size: 13px;">
                    <thead>
                        {{-- prepare header search textbox --}}
                        <tr>
                          @foreach($myleave as $key=>$value)

                            @if ($key==0)
                              <td>Action</td>
                              @foreach($value as $field=>$value)
                                  <td/>{{ $field }}</td>
                              @endforeach

                            @endif

                          @endforeach
                        </tr>
                    </thead>
                    <tbody>

                      <?php $i = 0; ?>
                      @foreach($myleave as $leave)

                        {{-- @if(strpos($leave->Status,"Pending")!==false) --}}
                          <tr id="row_{{ $i }}">
                            <td></td>
                              @foreach($leave as $key=>$value)
                                <td>
                                  {{ $value }}
                                </td>
                              @endforeach
                          </tr>
                          <?php $i++; ?>
                        {{-- @endif --}}

                      @endforeach

                  </tbody>
                    <tfoot></tfoot>
                </table>
              </div>

              <div class="tab-pane" id="myapprovedleave">
                <table id="leave2table" class="display" cellspacing="0" width="100%" padding="30px" style="font-size: 13px;">
                    <thead>
                        {{-- prepare header search textbox --}}
                        <tr>
                          @foreach($myleave as $key=>$value)

                            @if ($key==0)
                              <td>Action</td>
                              @foreach($value as $field=>$value)
                                  <td/>{{ $field }}</td>
                              @endforeach

                            @endif

                          @endforeach
                        </tr>
                    </thead>
                    <tbody>

                      <?php $i = 0; ?>
                      @foreach($myleave as $leave)

                          {{-- @if(strpos($leave->Status,"Approved")!==false) --}}
                          <tr id="row_{{ $i }}">
                            <td></td>
                              @foreach($leave as $key=>$value)
                                <td>
                                  {{ $value }}
                                </td>
                              @endforeach
                          </tr>
                          <?php $i++; ?>
                        {{-- @endif --}}

                      @endforeach

                  </tbody>
                    <tfoot></tfoot>
                </table>
              </div>

              <div class="tab-pane" id="myrejectedleave">
                <table id="leave3table" class="display" cellspacing="0" width="100%" padding="30px" style="font-size: 13px;">
                    <thead>
                        {{-- prepare header search textbox --}}
                        <tr>
                          @foreach($myleave as $key=>$value)

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
                      @foreach($myleave as $leave)

                          {{-- @if(strpos($leave->Status,"Rejected")!==false) --}}
                          <tr id="row_{{ $i }}">
                              @foreach($leave as $key=>$value)
                                <td>
                                  {{ $value }}
                                </td>
                              @endforeach
                          </tr>
                          <?php $i++; ?>
                        {{-- @endif --}}

                      @endforeach

                  </tbody>
                    <tfoot></tfoot>
                </table>
              </div>

              <div class="tab-pane" id="mycancelledleave">
                <table id="leave4table" class="display" cellspacing="0" width="100%" padding="30px" style="font-size: 13px;">
                    <thead>
                        {{-- prepare header search textbox --}}
                        <tr>
                          @foreach($myleave as $key=>$value)

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
                      @foreach($myleave as $leave)

                          {{-- @if(strpos($leave->Status,"Rejected")!==false) --}}
                          <tr id="row_{{ $i }}">
                              @foreach($leave as $key=>$value)
                                <td>
                                  {{ $value }}
                                </td>
                              @endforeach
                          </tr>
                          <?php $i++; ?>
                        {{-- @endif --}}

                      @endforeach

                  </tbody>
                    <tfoot></tfoot>
                </table>
              </div>

          </div>
          <!-- /.nav tab content -->
        </div>
        <!-- /.av-tabs-custom -->

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

  <script>
  $(function () {
    $( "#upload_form" ).submit(function( event ) {
      event.preventDefault();
      return false;
    });
    /* initialize the calendar
     -----------------------------------------------------------------*/
    //Date for the calendar events (dummy data)
    var date = new Date();
    var d = date.getDate(),
        m = date.getMonth(),
        y = date.getFullYear();
    $('#calendar').fullCalendar({
      header: {
        left: 'prev,next today',
        center: 'title',
        right: 'month'
      },
      buttonText: {
        today: 'Today',
        month: 'Month'
      },
      //Random default events
      events: [

        @foreach($showleave as $leave)

            @if(strpos($leave->Status,"Approved")!==false || $leave->Status="Pending Approval")
            {
              title: "{{ $leave->Name }} - {{ $leave->Leave_Term }}",
              start: new Date("{{$leave->Start_Date}}"),
              end: new Date("{{$leave->End_Date}}"),
              allDay: true,
              @if(strpos($leave->Status,"Approved")!==false)
                backgroundColor: "#00a65a", //green
                borderColor: "#00a65a" //green
              @else
                backgroundColor: "#f39c12", //yellow
                borderColor: "#f39c12" //yellow
              @endif

            },
            @endif

        @endforeach

      ],
      editable: false,
      droppable: false, // this allows things to be dropped onto the calendar !!!
    });
  });
</script>

<script>
     function calculatePeriod() {
      var Leave_Period = $('[name^="Leave_Period"]');
      var Days = 0;
      Leave_Period.each(function() {
        var element = $(this);
        if (element.val() == "AM" || element.val() == "PM") {
          Days += 0.5;
        } else if (element.val() == "Full") {
          Days += 1;
        } else if (element.val() == '1 Hour') {
          Days += 0.125;
        } else if (element.val() == '2 Hours') {
          Days += 0.25;
        }
      });
      $("#No_Of_Days").val(Days);
    }
  $(function () {

    $('#Leave_Type').on('select2:select', function (e) {
      calculateDates();
    });
    /**
     * Calculate and retrieve dates from backend
     */
    function calculateDates() {

      var Start_Date = $("#Start_Date").val();
      var End_Date = $("#End_Date").val();
      var Leave_Type = $("#Leave_Type").val();
      if (Start_Date == "" || End_Date == "" || Leave_Type == "") {
        return;
      }

      var date_received = $("#Start_Date").datepicker('getDate');
      var date_completed = $("#End_Date").datepicker('getDate');

      var diff = date_completed - date_received;
      var days = diff / 1000 / 60 / 60 / 24;

      // check if user wrongly selected between start and end dates
      if (days < 0) {
        // diff = date_received - date_completed;
        // days = diff / 1000 / 60 / 60 / 24;

        //  Exchange the value of Start_Date and End_Date
        $("#Start_Date").datepicker('update', date_completed);
        $("#End_Date").datepicker('update', date_received);

        // read the updated value
        Start_Date = $("#Start_Date").val();
        End_Date = $("#End_Date").val();

      }
      // alert(Start_Date);
      $("#LeaveListTable > thead > tr:eq(1) > td").html(`<i class="fa fa-gear fa-spin"></i> Loading`);
      setTimeout(function () {
        getCalculatedLeaveDays(Start_Date, End_Date);
      }, 300);

    }

    function getCalculatedLeaveDays(Start_Date, End_Date) {


      $.ajaxSetup({
        headers: { 'X-CSRF-Token' : $('meta[name=_token]').attr('content') }
      });


      $.ajax('{{ url("/fetchCalculatedLeaveDays/") }}?Start_Date=' + Start_Date + '&End_Date=' + End_Date, {
        type: 'GET',  // http method
        data: {
          'Leave_Type': $("#Leave_Type").val()
        },  // data to submit
        success:
          function (data, status, xhr) {
            // setTimeout(function () {
              console.log(data);
              $("#No_Of_Days").val(data.calculated_days)
              $("#LeaveListTable > thead > tr:eq(1)").remove();
              $('#LeaveListTable > tbody').empty();
              var counter = 0;
              if ($("#Leave_Type").val() == 'Maternity Leave' || $("#Leave_Type").val() == 'Hospitalization Leave') {
                $('#LeaveListTable > tbody').append(`<tr class='active'>
                  <td>From: ${data.list[0].Date}</td>
                  <td></td>
                  <td>Full</td>
                </tr>
                <tr class='active'>
                  <td>To: ${data.list[data.list.length-1].Date}</td>
                  <td></td>
                  <td>Full</td>
                </tr>`);
              } else {
                data.list.forEach(function(element) {
                  if ($("#Leave_Type").val() != '1 Hour Time Off' && $("#Leave_Type").val() != '2 Hours Time Off') {
                    if (element.Day_Type == 0 || element.Day_Type == 2 || element.Day_Type == -1) {
                      $('#LeaveListTable > tbody').append(`<tr class='active'>
                        <td>${element.Date}</td>
                        <td>${element.Day_Type_Description}</td>
                        <td>${element.Period}<input type="hidden" name="Leave_Period[${counter}]" value="${element.Period}"></td>
                      </tr>`);
                    } else {
                      $('#LeaveListTable > tbody').append(`<tr>
                        <td>${element.Date}</td>
                        <td>${element.Day_Type_Description}</td>
                        <td>
                          <select class="form-control input-sm" name="Leave_Period[${counter}]" onChange="calculatePeriod()">
                            <option value="Full">Full</option>
                            <option value="AM">AM 9 - 1</option>
                            <option value="PM">PM 2 - 6</option>
                          </select>
                        </td>
                      </tr>`);
                    }
                  } else {
                    if (element.Day_Type == 0 || element.Day_Type == 2 || element.Day_Type == -1) {
                      $('#LeaveListTable > tbody').append(`<tr class='active'>
                        <td>${element.Date}</td>
                        <td>${element.Day_Type_Description}</td>
                        <td>${element.Period}<input type="hidden" name="Leave_Period[${counter}]" value="${element.Period}"></td>
                      </tr>`);
                    } else {
                      $('#LeaveListTable > tbody').append(`<tr>
                        <td>${element.Date}</td>
                        <td>${element.Day_Type_Description}</td>
                        <td>
                          <input type="hidden" name="Leave_Period[${counter}]" value="${ $("#Leave_Type").val() == '1 Hour Time Off' ? '1 Hour' : '2 Hours' }">
                          <span>${ $("#Leave_Type").val() == '1 Hour Time Off' ? '1 Hour' : '2 Hours' }</span>
                        </td>
                      </tr>`);
                    }
                  }
                  counter += 1;
                });
              }
              // location.reload(true);
            // }, 200)
          },
        error:
          function (jqXhr, textStatus, errorMessage) {

          }
      });
    }



    //Initialize Select2 Elements
    $(".select2").select2();

    //Flat red color scheme for iCheck
    $('input[type="checkbox"].flat-green, input[type="radio"].flat-green').iCheck({
      checkboxClass: 'icheckbox_flat-green',
      radioClass: 'iradio_flat-green'
    });

    //Date picker
    $('#Start_Date').datepicker({
        autoclose: true,
        format: 'dd-M-yyyy',
    }).on('changeDate', function(ev){
        calculateDates();
        //my work here
        $.ajaxSetup({
           headers: { 'X-CSRF-Token' : $('meta[name=_token]').attr('content') }
        });

        start_date=$("#Start_Date").val();
        end_date=$("#End_Date").val();
        leave_type=$("#Leave_Type").val();
        leave_term=$("#Leave_Term").val();

        if(leave_type && leave_term && start_date && end_date)
        {
          $("#ajaxloader3").show();
          $.ajax({
                      url: "{{ url('/myleave/calculatedays') }}",
                      method: "POST",
                      data: {
                      Start_Date:start_date,
                      End_Date:end_date,
                      Leave_Type:leave_type,
                      Leave_Term:leave_term},

                      success: function(response){

                        if (response>0)
                        {
                          $("#No_Of_Days").val(response)

                        }
                        else {

                        }

                        $("#ajaxloader3").hide();

              }
          });
        }

    });

    $('#End_Date').datepicker({
      autoclose: true,
        format: 'dd-M-yyyy',
    }).on('changeDate', function(ev){
        calculateDates();
        //my work here
        $.ajaxSetup({
           headers: { 'X-CSRF-Token' : $('meta[name=_token]').attr('content') }
        });

        start_date=$("#Start_Date").val();
        end_date=$("#End_Date").val();
        leave_type=$("#Leave_Type").val();
        leave_term=$('input[name=Leave_Term]:checked').val();

        if(leave_type && leave_term && start_date && end_date)
        {

          $("#ajaxloader3").show();
          $.ajax({
                      url: "{{ url('/myleave/calculatedays') }}",
                      method: "POST",
                      data: {
                      Start_Date:start_date,
                      End_Date:end_date,
                      Leave_Type:leave_type,
                      Leave_Term:leave_term},

                      success: function(response){

                        if (response>0)
                        {
                          $("#No_Of_Days").val(response)

                        }
                        else {

                        }

                        $("#ajaxloader3").hide();

              }
          });
        }
    });

  });

  function OpenRedirectDialog(id,currentapprover)
  {
    var hiddeninput='<input type="hidden" class="form-control" id="selectedleaveid" name="selectedleaveid" value="'+id+'">';

      $("#NewApprover").val(currentapprover).change();

      $( "#redirectleavestatus" ).html(hiddeninput);
      $('#Redirect').modal('show');

  }

  function CancelDialog(id)
  {

    var hiddeninput='<input type="hidden" class="form-control" id="cancelleaveid" name="cancelleaveid" value="'+id+'">';

      $( "#cancelleave" ).html(hiddeninput);
      $('#Cancel').modal('show');

  }

  function redirect() {

      $.ajaxSetup({
         headers: { 'X-CSRF-Token' : $('meta[name=_token]').attr('content') }
      });

      $("#ajaxloader").show();

      newapprover=$('[name="NewApprover"]').val();
      leaveid=$('[name="selectedleaveid"]').val();

      $.ajax({
                  url: "{{ url('/myleave/redirect') }}",
                  method: "POST",
                  data: {Id:leaveid,Approver:newapprover},

                  success: function(response){

                    if (response==1)
                    {
                        leavetable.ajax.url("{{ asset('/Include/leave.php') }}").load();
                        var message="Leave approval redirected!";
                        $('#Redirect').modal('hide');
                        $("#update-alert ul").html(message);
                        $("#update-alert").modal('show');


                        $("#ajaxloader").hide();

                    }
                    else {

                      var errormessage="Failed to redirect leave approval!";
                      $('#Redirect').modal('hide');
                      $("#error-alert ul").html(errormessage);
                      $("#error-alert").modal('show');



                      $("#ajaxloader").hide();

                    }

          }
      });

  }

  function cancelleave() {

      $.ajaxSetup({
         headers: { 'X-CSRF-Token' : $('meta[name=_token]').attr('content') }
      });

      $("#ajaxloader2").show();

      leaveid=$('[name="cancelleaveid"]').val();

      $.ajax({
                  url: "{{ url('/myleave/cancel') }}",
                  method: "POST",
                  data: {Id:leaveid},

                  success: function(response){

                    if (response==1)
                    {
                      leavetable.ajax.reload();
                      leave2table.ajax.reload();
                      leave3table.ajax.reload();
                      leave4table.ajax.reload();
                      var message="Leave cancelled!";
                      $("#update-alert ul").html(message);
                      $("#update-alert").modal('show');

                      // setTimeout(function() {
                      //   $("#update-alert").fadeOut();
                      // }, 6000);

                      $('#Cancel').modal('hide');

                      $("#ajaxloader2").hide();

                    }
                    else {

                      var errormessage="Failed to cancel leave!";
                      $("#error-alert ul").html(errormessage);
                      $("#error-alert").modal('show');


                      // setTimeout(function() {
                      //   $("#error-alert").fadeOut();
                      // }, 6000);

                      $('#Cancel').modal('hide');

                      $("#ajaxloader2").hide();

                    }

          }
      });

  }

  function applyleave() {
      $("#btnapplyleave").prop('disabled',true);
      $.ajaxSetup({
         headers: { 'X-CSRF-Token' : $('meta[name=_token]').attr('content') }
      });

      $("#ajaxloader").show();

      $.ajax({
                  url: "{{ url('/myleave/apply') }}",
                  method: "POST",
                  contentType: false,
                  processData: false,
                  data:new FormData($("#upload_form")[0]),

                  success: function(response){
                    $("#btnapplyleave").prop('disabled',false);

                    if (response==1)
                    {
                        $('html, body').animate({scrollTop: '0px'}, 500);
                        leavetable.ajax.reload();

                        $("#Leave_Type").val("").change();
                        $("#Approver").val("").change();
                        // document.getElementById("Leave_Term_2").checked=false;
                        // document.getElementById("Leave_Term_3").checked=false;
                        // document.getElementById("Leave_Term_1").checked=true;
                        document.getElementById("Start_Date").value = ''
                        document.getElementById("End_Date").value = ''
                        document.getElementById("Reason").value = ''
                        $('#LeaveListTable > tbody').empty();
                        $("#attachment").val("");

                        $("#ajaxloader").hide();

                        var message="Leave application submitted!";
                        $("#update-alert ul").html(message);
                        $("#update-alert").modal('show');

                        // $("#error-alert").hide();
                    }
                    else if (response==-1){
                       $('html, body').animate({scrollTop: '0px'}, 500);
                        var obj = jQuery.parseJSON(response);
                        var errormessage ="";

                        errormessage="<li>Leave overlapped!</li>";

                        $("#error-alert ul").html(errormessage);
                        $("#error-alert").modal('show');

                        // setTimeout(function() {
                        //   $("#error-alert").fadeOut();
                        // }, 10000);

                        $("#ajaxloader").hide();

                    }
                    else {
                        $('html, body').animate({scrollTop: '0px'}, 500);
                        var obj = jQuery.parseJSON(response);
                        console.log(obj);
                        var errormessage ="";

                        for (var item in obj) {
                          errormessage=errormessage + "<li> " + obj[item] + "</li>";
                        }

                        $("#error-alert ul").html(errormessage);
                        $("#error-alert").modal('show');


                        $("#ajaxloader").hide();

                    }

          }
      });

  }

  function decodeEntities(s){
      var str, temp= document.createElement('p');
      temp.innerHTML= s;
      str= temp.textContent || temp.innerText;
      temp=null;
      return str;
  }

</script>

<script type="text/javascript">
  /**
   * Function to fetch leave terms and display it in a modal
   */
  function viewLeaveTerms(Leave_Id) {

    $.ajaxSetup({
       headers: { 'X-CSRF-Token' : $('meta[name=_token]').attr('content') }
    });
    $.ajax({
        url: "{{ url("/fetchLeaveTerms/") }}" + "/" + Leave_Id,
        method: "GET",
        success: function(response){
          $('#viewLeaveTermsTable > tbody').empty();
          response.Leave_Terms.forEach(function(element) {
            if (element.Leave_Period == 'Non-Workday') {
              $('#viewLeaveTermsTable > tbody').append(`<tr class='active'>
                <td>${element.Leave_Date}</td>
                <td>${element.Leave_Period}</td>
              </tr>`);
            } else {
              $('#viewLeaveTermsTable > tbody').append(`<tr>
                <td>${element.Leave_Date}</td>
                <td>${element.Leave_Period}</td>
              </tr>`);
            }
          });
        },
        error: function(data){

        }
    });

  }

  /**
   * Function to fetch leave terms and display it in a modal
   * for edit
   */
  function editLeaveTerms(Leave_Id) {
    $("#ajaxloader4").show();
        $('#editLeaveTermsTable > tbody').empty();

    $.ajaxSetup({
       headers: { 'X-CSRF-Token' : $('meta[name=_token]').attr('content') }
    });
    $.ajax({
        url: "{{ url("/fetchCalculatedLeaveDays/") }}",
        method: "GET",
        data: {
          Leave_Id: Leave_Id
        },
        success: function(response){
          $('#Update_Leave_Id').val(Leave_Id);
          var counter = 0;

                response.list.forEach(function(element) {
                  if (element.Leave_Type == 'Maternity Leave' || element.Leave_Type == 'Hospitalization Leave') {
                    $('#editLeaveTermsTable > tbody').append(`<tr class='active'>
                      <td>From: ${data.list[0].Date}</td>
                      <td></td>
                      <td>Full</td>
                    </tr>
                    <tr class='active'>
                      <td>To: ${data.list[data.list.length-1].Date}</td>
                      <td></td>
                      <td>Full</td>
                    </tr>`);
                    return false;
                  } else {
                    if (element.Leave_Type != '1 Hour Time Off' && element.Leave_Type != '2 Hours Time Off') {
                      if (element.Day_Type == 0 || element.Day_Type == 2 || element.Day_Type == -1) {
                        $('#editLeaveTermsTable > tbody').append(`<tr class='active'>
                          <td>${element.Date}</td>
                          <td>${element.Day_Type_Description}</td>
                          <td>${element.Period}<input type="hidden" name="Leave_Period[${counter}]" value="${element.Period}"></td>
                        </tr>`);
                      } else {
                        $('#editLeaveTermsTable > tbody').append(`<tr>
                          <td>${element.Date}</td>
                          <td>${element.Day_Type_Description}</td>
                          <td>
                            <select class="form-control input-sm" name="Leave_Period[${counter}]" onChange="calculatePeriod()">
                              <option value="Full">Full</option>
                              <option value="AM">AM 8 - 1</option>
                              <option value="PM">PM 2 - 5</option>
                            </select>
                          </td>
                        </tr>`);
                      }
                    } else {
                      if (element.Day_Type == 0 || element.Day_Type == 2 || element.Day_Type == -1) {
                        $('#editLeaveTermsTable > tbody').append(`<tr class='active'>
                          <td>${element.Date}</td>
                          <td>${element.Day_Type_Description}</td>
                          <td>${element.Period}<input type="hidden" name="Leave_Period[${counter}]" value="${element.Period}"></td>
                        </tr>`);
                      } else {
                        $('#editLeaveTermsTable > tbody').append(`<tr>
                          <td>${element.Date}</td>
                          <td>${element.Day_Type_Description}</td>
                          <td>
                            <input type="hidden" name="Leave_Period[${counter}]" value="${ $("#Leave_Type").val() == '1 Hour Time Off' ? '1 Hour' : '2 Hours' }">
                            <span>${ $("#Leave_Type").val() == '1 Hour Time Off' ? '1 Hour' : '2 Hours' }</span>
                          </td>
                        </tr>`);
                      }
                    }
                    counter += 1;
                  }
                });


          $("#ajaxloader4").hide();
        },
        error: function(data){
          $("#ajaxloader4").hide();
        }
    });

  }

  function updateLeaveTerms() {
    $("#ajaxloader4").show();
    $.ajaxSetup({
       headers: { 'X-CSRF-Token' : $('meta[name=_token]').attr('content') }
    });

    $.ajax({
      url: "{{ url('/updateLeaveTerms') }}",
      method: "POST",
      processData: false,
      contentType: false,
      data:new FormData($("#formUpdateLeaveTerms")[0]),
      success: function(response){
        leavetable.ajax.reload();
        $("#ajaxloader4").hide();
        var message="Leave period updated!";
        $('#editLeaveTermsModal').modal('hide');
        $("#update-alert ul").html(message);
        $('#update-alert').modal('show');
      }}).always(function() {
        // leavetable.ajax.reload();
        $("#ajaxloader4").hide();
      });
  }
</script>

<script type="text/javascript">
  function viewdata(leavetype,userid,start,end)
  {
    console.log(end)

     $('#NameList').modal('show');
     $("#list").html("");

     $.ajaxSetup({
        headers: { 'X-CSRF-Token' : $('meta[name=_token]').attr('content') }
     });

     $("#ajaxloader5").show();

     $.ajax({
         url: "{{ url('leavesummary/viewdata') }}",
         method: "POST",
         data: {
           Leave_Type:leavetype,
           UserId:userid,
           Start:start,
           End:end
         },
         success: function(response){
           if (response==0)
           {

             var message ="Failed to retrieve user details!";
             $("#warning-alert ul").html(message);
             $("#warning-alert").show();
             $('#ReturnedModal').modal('hide')

             $("#ajaxloader5").hide();
           }
           else {
             $("#exist-alert").hide();

             var myObject = JSON.parse(response);

             var display='<table border="1" align="center" class="leavetable table table-condensed table-bordered" cellspacing="0" width="100%" padding="30px" style="font-size: 13px;">';
             display+='<tr class="tableheader"><th>Start</th><th>End</th><th>Days</th><th>Reason</th><th>Status</th></tr>';

             $.each(myObject, function(i,item){

               if (item.Leave_Status.includes('Approved')) {
                 display+="<tr class='success'>";
                 display+='<td>'+item.Start_Date+'</td><td>'+item.End_Date+'</td><td>'+item.No_of_Days+'</td><td>'+item.Reason+'</td><td>'+item.Leave_Status+'</td>';
                 display+="</tr>";
               } else {
                 display+="<tr>";
                 display+='<td>'+item.Start_Date+'</td><td>'+item.End_Date+'</td><td>'+item.No_of_Days+'</td><td>'+item.Reason+'</td><td>'+item.Leave_Status+'</td>';
                 display+="</tr>";
               }

             });

                     $("#list").html(display);

                     $("#ajaxloader5").hide();
                   }
           }
       });

     }

    $(function () {

      $('#range').daterangepicker({locale: {
        format: 'DD-MMM-YYYY'
      },startDate: '{{$start}}',
      endDate: '{{$end}}'});

    });

    function refresh()
    {
      var d=$('#range').val();
      var arr = d.split(" - ");

      window.location.href ="{{ url("/myleave") }}/"+arr[0]+"/"+arr[1];

    }
</script>
@endsection
