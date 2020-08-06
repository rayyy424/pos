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

      .green {
        color: green;
      }

      .yellow {
        color: #f39c12;
      }

      .red{
        color:red;
      }

      .success {
          color: #00a65a;
      }

      .alert2 {
          color: #dd4b39;
      }

      .warning {
          color: #f39c12;
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

      <script type="text/javascript" language="javascript" src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.8.4/moment.min.js"></script>
      <script type="text/javascript" language="javascript" src="https://cdn.datatables.net/plug-ins/1.10.15/sorting/datetime-moment.js"></script>
      {{-- <script type="text/javascript" language="javascript" src="{{ asset('/plugin/examples/resources/syntax/shCore.js') }}"></script>
      <script type="text/javascript" language="javascript" src="{{ asset('/plugin/examples/resources/demo.js') }}"></script>
      <script type="text/javascript" language="javascript" src="{{ asset('/plugin/examples/resources/editor-demo.js') }}"></script> --}}

      <script type="text/javascript" language="javascript" class="init">

          var leaveseditor;
          var leaves2editor;
          var leaves3editor;
          var alleditor;
          var finaleditor;

          var leavetable;
          var leave2table;
          var leave3table;
          var alltable;
          var finaltable;
          var selectedtabindex;
          var asInitVals = new Array();

          $(document).ready(function() {

            $.fn.dataTable.moment( 'DD-MMM-YYYY' );

            alleditor = new $.fn.dataTable.Editor( {
                   ajax: "{{ asset('/Include/leaveapproval.php') }}",
                    table: "#alltable",
                    idSrc: "leavestatuses.Id",
                    fields: [
                      {

                               label: "Comment:",
                               name: "leavestatuses.Comment",
                               type: "textarea"
                       }

                    ]
            } );

            finaleditor = new $.fn.dataTable.Editor( {
                   ajax: "{{ asset('/Include/leaveapproval.php') }}",
                    table: "#finaltable",
                    idSrc: "leavestatuses.Id",
                    fields: [
                    {
                               label: "Comment:",
                               name: "leavestatuses.Comment",
                               type: "textarea"
                       }

                    ]
            } );

                         leaveseditor = new $.fn.dataTable.Editor( {
                                ajax: "{{ asset('/Include/leaveapproval.php') }}",
                                 table: "#leavetable",
                                 idSrc: "leavestatuses.Id",
                                 fields: [
                                   {
                                            label: "Comment:",
                                            name: "leavestatuses.Comment",
                                            type: "textarea"
                                    }

                                 ]
                         } );

                         leaves2editor = new $.fn.dataTable.Editor( {
                                ajax: "{{ asset('/Include/leaveapproval.php') }}",
                                 table: "#leave2table",
                                 idSrc: "leavestatuses.Id",
                                 fields: [
                                         {

                                                 label: "Comment:",
                                                 name: "leavestatuses.Comment"
                                         }

                                 ]
                         } );

                         leaves3editor = new $.fn.dataTable.Editor( {
                                ajax: "{{ asset('/Include/leaveapproval.php') }}",
                                 table: "#leave3table",
                                 idSrc: "leavestatuses.Id",
                                 fields: [
                                  {
                                            label: "Comment:",
                                            name: "leavestatuses.Comment"
                                    }
            //                         , {
            //     label: "Next Approver:",
            //     name:  "Next_Approver",
            //     type:  "select",
            //     options: [
            //         { label: "Shey Ley", value: "1" },
            //         { label: "Haw",           value: "2" },
            //         { label: "Test",           value: "3" }
            //     ]
            // }

                                 ]
                         } );

                         alltable=$('#alltable').dataTable( {
                               ajax: {
                                  "url": "{{ asset('/Include/leaveapproval.php') }}",
                                  "data": {
                                      "Start": "{{ $start }}",
                                      "End": "{{ $end }}"
                                  }
                                },
                                 columnDefs: [{ "visible": false, "targets": [1,2,15] },{"className": "dt-center", "targets": "_all"}],
                                 responsive: false,
                                 colReorder: false,
                                 dom: "Bfrtp",
                                 sScrollX: "100%",
                                 bAutoWidth: true,
                                 iDisplayLength:10,
                                 sScrollY: "100%",
                                 scrollCollapse: true,
                                 aaSorting: [[11,"desc"]],
                                 fnInitComplete: function(oSettings, json) {

                                   $('#allleavetab').html("All Leave " + "[" + alltable.api().rows().count() +"]")

                                  },
                                 rowId:"leavestatuses.Id",
                                 columns: [
                                         {
                                           sortable: false,
                                           "render": function ( data, type, full, meta ) {
                                             return '<input type="checkbox" name="selectrow0" id="selectrow0" class="selectrow0" value="'+full.leaves.Id+'" onclick="uncheck(0)">';

                                           }

                                         },

                                         { data: "leavestatuses.Id"},
                                         { data: "leaves.Id"},
                                         { data: "leavestatuses.Leave_Status",title:"Leave_Status",
                                           "render": function ( data, type, full, meta ) {

                                                if(full.leavestatuses.Leave_Status.includes("Approved"))
                                                {
                                                  return "<span class='green'>"+full.leavestatuses.Leave_Status+"</span>";
                                                }
                                                else if(full.leavestatuses.Leave_Status.includes("Rejected"))
                                                {
                                                  return "<span class='red'>"+full.leavestatuses.Leave_Status+"</span>";
                                                }
                                                else {
                                                  return "<span class='yellow'>"+full.leavestatuses.Leave_Status+"</span>";
                                                }

                                             }
                                         },
                                         { data: "applicant.StaffId",title:"Staff_ID"},
                                         { data: "applicant.Name",title:"Name"},
                                         { data: "leaves.Leave_Type",title:"Leave Type" },
                                         { data: "leaves.Leave_Term",title:"Leave Term" },
                                         { data: "leaves.Start_Date",title:"Start Date"},
                                         { data: "leaves.End_Date",title:"End Date"},
                                         { data: "leaves.No_of_Days",title:"No of Days"},

                                         { data: "leaves.Reason",title:"Reason"},
                                         { data: "leaves.Medical_Claim",title:"Medical_Claim"
                                         "render": function ( data, type, full, meta ) {

                                                return '<a class ="buttonclaim" onclick="viewmedicalclaim(\''+full.applicant.StaffId+'\')">'+full.leaves.Medical_Claim+'</a>';
                                            }
                                         },
                                         { data: "leaves.created_at",title:"Application Date"},
                                         { data: "projects.Project_Name",title:"Department"},
                                         { data: "approver.Name",title:"Approver"},
                                         { data: "leavestatuses.Comment",title:"Comment"},
                                         { data: "leavestatuses.updated_at",title:"Review Date"},
                                         { data: "files.Web_Path",
                                            render: function ( url, type, row ) {
                                                 if (url)
                                                 {
                                                   return '<a href="{{ url("/") }}'+url+'" target="_blank">Download</a>';
                                                 }
                                                 else {
                                                   return ' - ';
                                                 }
                                             },
                                             title: "File"
                                           }
                                 ],
                                 autoFill: {
                                    editor:  alleditor,
                                    columns: [ 9,10,11]
                                },
                                // keys: {
                                //     columns: ':not(:first-child)',
                                //     editor:  alleditor
                                // },
                                select: true,
                                 buttons: [


                                 ],

                     });

                     finaltable=$('#finaltable').dataTable( {
                           ajax: {
                              "url": "{{ asset('/Include/leaveapproval.php') }}",
                              "data": {
                                  "Start": "{{ $start }}",
                                  "End": "{{ $end }}",
                                  "Status": "%Final Approved%"
                              }
                            },
                            aaSorting: [[15,"desc"]],
                             columnDefs: [{ "visible": false, "targets": [1,2] },{"className": "dt-center", "targets": "_all"}],
                             responsive: false,
                             colReorder: false,
                             dom: "Blrtip",
                             sScrollX: "100%",
                             bAutoWidth: true,
                             iDisplayLength:10,
                             sScrollY: "100%",
                             scrollCollapse: true,
                             fnInitComplete: function(oSettings, json) {

                               $('#finalleavetab').html("Final Approved Leave " + "[" + finaltable.api().rows().count() +"]")

                              },
                             rowId:"leavestatuses.Id",
                             columns: [
                                     {
                                       sortable: false,
                                       "render": function ( data, type, full, meta ) {
                                         return '<input type="checkbox" name="selectrow0" id="selectrow0" class="selectrow0" value="'+full.leaves.Id+'" onclick="uncheck(0)">';

                                       }

                                     },

                                     { data: "leavestatuses.Id"},
                                     { data: "leaves.Id"},
                                     { data: "leavestatuses.Leave_Status",title:"Leave_Status",
                                       "render": function ( data, type, full, meta ) {

                                            if(full.leavestatuses.Leave_Status.includes("Approved"))
                                            {
                                              return "<span class='green'>"+full.leavestatuses.Leave_Status+"</span>";
                                            }
                                            else if(full.leavestatuses.Leave_Status.includes("Rejected"))
                                            {
                                              return "<span class='red'>"+full.leavestatuses.Leave_Status+"</span>";
                                            }
                                            else {
                                              return "<span class='yellow'>"+full.leavestatuses.Leave_Status+"</span>";
                                            }

                                         }
                                     },
                                     { data: "applicant.StaffId",title:"Staff_ID"},
                                     { data: "applicant.Name",title:"Name"},
                                     { data: "leaves.Leave_Type",title:"Leave_Type" },
                                     { data: "leaves.Leave_Term",title:"Leave_Term" },
                                     { data: "leaves.Start_Date",title:"Start_Date"},
                                     { data: "leaves.End_Date",title:"End_Date"},
                                     { data: "leaves.No_of_Days",title:"No_of_Days"},
                                     { data: "leaves.Reason",title:"Reason"},
                                     { data: "leaves.Medical_Claim",title:"Medical_Claim"
                                     "render": function ( data, type, full, meta ) {

                                            return '<a class ="buttonclaim" onclick="viewmedicalclaim(\''+full.applicant.StaffId+'\')">'+full.leaves.Medical_Claim+'</a>';
                                        }
                                     },
                                     { data: "leaves.created_at",title:"Application_Date"},
                                     { data: "projects.Project_Name",title:"Project_Name"},
                                     { data: "approver.Name",title:"Approver"},
                                     { data: "leavestatuses.Comment",title:"Comment"},
                                     { data: "leavestatuses.updated_at",title:"Review_Date"},
                                     { data: "files.Web_Path",
                                        render: function ( url, type, row ) {
                                             if (url)
                                             {
                                               return '<a href="{{ url("/") }}'+url+'" target="_blank">Download</a>';
                                             }
                                             else {
                                               return ' - ';
                                             }
                                         },
                                         title: "File"
                                       }
                             ],
                             autoFill: {
                                editor:  finaleditor,
                                columns: [ 9,10,11]
                            },
                            // keys: {
                            //     columns: ':not(:first-child)',
                            //     editor:  alleditor
                            // },
                            select: true,
                            buttons: [

                              {
                                      extend: 'collection',
                                      text: 'Export',
                                      buttons: [
                                              'excel',
                                              'csv',
                                              'pdf'
                                      ]
                              }
                            ],

                 });

                         leavetable=$('#leavetable').dataTable( {
                                ajax: {
                                   "url": "{{ asset('/Include/leaveapproval.php') }}",
                                   "data": {
                                       "UserId": {{ $me->UserId }},
                                       "Status": "%Pending%"
                                   }
                                 },
                                 columnDefs: [{ "visible": false, "targets": [1,2,15] },{"className": "dt-center", "targets": "_all"}],
                                 responsive: false,
                                 colReorder: false,
                                 dom: "Bfrtp",
                                 sScrollX: "100%",
                                 iDisplayLength:10,
                                 bAutoWidth: true,
                                 sScrollY: "100%",
                                 scrollCollapse: true,
                                 fnInitComplete: function(oSettings, json) {

                                   $('#pendingapprovaltab').html("Pending Approval Leave " + "[" + leavetable.api().rows().count() +"]")

                                  },
                                 rowId:"leavestatuses.Id",
                                 columns: [
                                         {
                                           sortable: false,
                                           "render": function ( data, type, full, meta ) {
                                             return '<input type="checkbox" name="selectrow0" id="selectrow0" class="selectrow0" value="'+full.leaves.Id+'" onclick="uncheck(0)">';

                                           }

                                         },
                                         { data: "leavestatuses.Id"},
                                         { data: "leaves.Id"},
                                         { data: "leavestatuses.Leave_Status",title:"Leave Status",
                                           "render": function ( data, type, full, meta ) {

                                                if(full.leavestatuses.Leave_Status.includes("Approved"))
                                                {
                                                  return "<span class='green'>"+full.leavestatuses.Leave_Status+"</span>";
                                                }
                                                else if(full.leavestatuses.Leave_Status.includes("Rejected"))
                                                {
                                                  return "<span class='red'>"+full.leavestatuses.Leave_Status+"</span>";
                                                }
                                                else {
                                                  return "<span class='yellow'>"+full.leavestatuses.Leave_Status+"</span>";
                                                }

                                             }
                                         },
                                         { data: "applicant.StaffId",title:"Staff ID"},
                                         { data: "applicant.Name",title:"Name"},
                                         { data: "leaves.Leave_Type",title:"Leave Type" },
                                         { data: "leaves.Leave_Term",title:"Leave Term" },
                                         { data: "leaves.Start_Date",title:"Start Date"},
                                         { data: "leaves.End_Date",title:"End Date"},
                                         { data: "leaves.No_of_Days",title:"No of Days"},
                                         { data: "leaves.Reason",title:"Reason"},
                                         { data: "leaves.Medical_Claim",title:"Medical_Claim"
                                         "render": function ( data, type, full, meta ) {

                                                return '<a class ="buttonclaim" onclick="viewmedicalclaim(\''+full.applicant.StaffId+'\')">'+full.leaves.Medical_Claim+'</a>';
                                            }
                                         },
                                         { data: "leaves.created_at",title:"Application Date"},
                                         { data: "projects.Project_Name",title:"Department"},
                                         { data: "approver.Name",title:"Approver"},
                                         { data: "leavestatuses.Comment",title:"Comment"},
                                         { data: "leavestatuses.updated_at",title:"Review Date"},
                                         { data: "files.Web_Path",
                                            render: function ( url, type, row ) {
                                                 if (url)
                                                 {
                                                   return '<a href="{{ url("/") }}'+url+'" target="_blank">Download</a>';
                                                 }
                                                 else {
                                                   return ' - ';
                                                 }
                                             },
                                             title: "File"
                                           }
                                 ],
                                 autoFill: {
                                    editor:  leaveseditor,
                                    columns: [ 9,10,11]
                                },
                                // keys: {
                                //     columns: ':not(:first-child)',
                                //     editor:  leaveseditor
                                // },
                                select: true,
                                 buttons: [

                                 ],

                     });

                     leave2table=$('#leave2table').dataTable( {
                           ajax: {
                              "url": "{{ asset('/Include/leaveapproval.php') }}",
                              "data": {
                                  "UserId": {{ $me->UserId }},
                                  "Status": "%Approved%"
                              }
                            },
                             columnDefs: [{ "visible": false, "targets": [1,2] },{"className": "dt-center", "targets": "_all"}],
                             responsive: false,
                             colReorder: false,
                             dom: "Bfrtp",
                             sScrollX: "100%",
                             bAutoWidth: true,
                             sScrollY: "100%",
                             iDisplayLength:10,
                             scrollCollapse: true,
                             fnInitComplete: function(oSettings, json) {

                               $('#approvedleavetab').html("Approved Leave " + "[" + leave2table.api().rows().count() +"]")

                              },
                             rowId:"leavestatuses.Id",
                             columns: [
                                     {
                                       sortable: false,
                                       "render": function ( data, type, full, meta ) {
                                         return '<input type="checkbox" name="selectrow1" id="selectrow1" class="selectrow1" value="'+full.leaves.Id+'" onclick="uncheck(1)">';

                                       }

                                     },
                                    { data: "leavestatuses.Id"},
                                    { data: "leaves.Id"},
                                    { data: "leavestatuses.Leave_Status",title:"Leave_Status",
                                      "render": function ( data, type, full, meta ) {

                                           if(full.leavestatuses.Leave_Status.includes("Approved"))
                                           {
                                             return "<span class='green'>"+full.leavestatuses.Leave_Status+"</span>";
                                           }
                                           else if(full.leavestatuses.Leave_Status.includes("Rejected"))
                                           {
                                             return "<span class='red'>"+full.leavestatuses.Leave_Status+"</span>";
                                           }
                                           else {
                                             return "<span class='yellow'>"+full.leavestatuses.Leave_Status+"</span>";
                                           }

                                        }
                                    },
                                    { data: "applicant.StaffId",title:"Staff_ID"},
                                    { data: "applicant.Name",title:"Name"},
                                    { data: "leaves.Leave_Type",title:"Leave_Type" },
                                    { data: "leaves.Leave_Term",title:"Leave_Term" },
                                    { data: "leaves.Start_Date",title:"Start_Date"},
                                    { data: "leaves.End_Date",title:"End_Date"},
                                    { data: "leaves.No_of_Days",title:"No_of_Days"},
                                    { data: "leaves.Reason",title:"Reason"},
                                    { data: "leaves.Medical_Claim",title:"Medical_Claim"
                                    "render": function ( data, type, full, meta ) {

                                           return '<a class ="buttonclaim" onclick="viewmedicalclaim(\''+full.applicant.StaffId+'\')">'+full.leaves.Medical_Claim+'</a>';
                                       }
                                    },
                                    { data: "leaves.created_at",title:"Application_Date"},
                                    { data: "projects.Project_Name",title:"Project_Name"},
                                    { data: "approver.Name",title:"Approver"},
                                    { data: "leavestatuses.Comment",title:"Comment"},
                                    { data: "leavestatuses.updated_at",title:"Review_Date"},
                                    { data: "files.Web_Path",
                                       render: function ( url, type, row ) {
                                            if (url)
                                            {
                                              return '<a href="{{ url("/") }}'+url+'" target="_blank">Download</a>';
                                            }
                                            else {
                                              return ' - ';
                                            }
                                        },
                                        title: "File"
                                      }
                             ],
                             autoFill: {
                                editor:  leaves2editor,
                                columns: [ 9,10,11]
                            },
                            // keys: {
                            //     columns: ':not(:first-child)',
                            //     editor:  leaves2editor
                            // },
                            select: true,
                             buttons: [


                             ],

                 });

                 leave3table=$('#leave3table').dataTable( {
                       ajax: {
                          "url": "{{ asset('/Include/leaveapproval.php') }}",
                          "data": {
                              "UserId": {{ $me->UserId }},
                              "Status": "%Rejected%"
                          }
                        },
                        columnDefs: [{ "visible": false, "targets": [1,2] },{"className": "dt-center", "targets": "_all"}],
                        responsive: false,
                        colReorder: false,
                        dom: "Bfrtp",
                        sScrollX: "100%",
                        bAutoWidth: true,
                        sScrollY: "100%",
                        iDisplayLength:10,
                        scrollCollapse: true,
                        fnInitComplete: function(oSettings, json) {

                          $('#rejectedleavetab').html("Rejected Leave " + "[" + leave3table.api().rows().count() +"]")

                         },
                        rowId:"leavestatuses.Id",
                         columns: [
                                   {
                                     sortable: false,
                                     "render": function ( data, type, full, meta ) {
                                       return '<input type="checkbox" name="selectrow2" id="selectrow2" class="selectrow2" value="'+full.leaves.Id+'" onclick="uncheck(2)">';

                                     }

                                   },
                                  { data: "leavestatuses.Id"},
                                  { data: "leaves.Id"},
                                  { data: "leavestatuses.Leave_Status",title:"Leave_Status",
                                    "render": function ( data, type, full, meta ) {

                                         if(full.leavestatuses.Leave_Status.includes("Approved"))
                                         {
                                           return "<span class='green'>"+full.leavestatuses.Leave_Status+"</span>";
                                         }
                                         else if(full.leavestatuses.Leave_Status.includes("Rejected"))
                                         {
                                           return "<span class='red'>"+full.leavestatuses.Leave_Status+"</span>";
                                         }
                                         else {
                                           return "<span class='yellow'>"+full.leavestatuses.Leave_Status+"</span>";
                                         }

                                      }
                                  },
                                  { data: "applicant.StaffId",title:"Staff_ID"},
                                  { data: "applicant.Name",title:"Name"},
                                  { data: "leaves.Leave_Type",title:"Leave_Type" },
                                  { data: "leaves.Leave_Term",title:"Leave_Term" },
                                  { data: "leaves.Start_Date",title:"Start_Date"},
                                  { data: "leaves.End_Date",title:"End_Date"},
                                  { data: "leaves.No_of_Days",title:"No_of_Days"},
                                  { data: "leaves.Reason",title:"Reason"},
                                  { data: "leaves.Medical_Claim",title:"Medical_Claim"
                                  "render": function ( data, type, full, meta ) {

                                         return '<a class ="buttonclaim" onclick="viewmedicalclaim(\''+full.applicant.StaffId+'\')">'+full.leaves.Medical_Claim+'</a>';
                                     }
                                  },
                                  { data: "leaves.created_at",title:"Application_Date"},
                                  { data: "projects.Project_Name",title:"Project_Name"},
                                  { data: "approver.Name",title:"Approver"},
                                  { data: "leavestatuses.Comment",title:"Comment"},
                                  { data: "leavestatuses.updated_at",title:"Review_Date"},
                                  { data: "files.Web_Path",
                                     render: function ( url, type, row ) {
                                          if (url)
                                          {
                                            return '<a href="{{ url("/") }}'+url+'" target="_blank">Download</a>';
                                          }
                                          else {
                                            return ' - ';
                                          }
                                      },
                                      title: "File"
                                    }
                         ],
                         autoFill: {
                            editor:  leaves3editor,
                            columns: [ 9,10,11]
                        },
                        // keys: {
                        //     columns: ':not(:first-child)',
                        //     editor:  leaves3editor
                        // },
                        select: true,
                         buttons: [



                         ],

             });

             //  Activate an inline edit on click of a table cell
                    $('#alltable').on( 'click', 'tbody td', function (e) {
                          alleditor.inline( this, {
                         onBlur: 'submit'
                        } );
                    } );

                    $('#finaltable').on( 'click', 'tbody td', function (e) {
                          finaleditor.inline( this, {
                         onBlur: 'submit'
                        } );
                    } );

            //  Activate an inline edit on click of a table cell
                   $('#leavetable').on( 'click', 'tbody td', function (e) {
                         leaveseditor.inline( this, {
                        onBlur: 'submit'
                       } );
                   } );


             // Activate an inline edit on click of a table cell
                   $('#leave2table').on( 'click', 'tbody td', function (e) {
                         leaves2editor.inline( this, {
                        onBlur: 'submit'
                       } );
                   } );

             // Activate an inline edit on click of a table cell
                   $('#leave3table').on( 'click', 'tbody td', function (e) {
                         leaves3editor.inline( this, {
                        onBlur: 'submit'
                       } );
                   } );

                  //  leaveseditor.on( 'postEdit', function ( e, json, data ) {
                  //       // $(this.modifier()).addClass('data-changed')
                  //       notifynextlevel(data.leavestatuses.Id);
                   //
                  //   } );

                  $("#ajaxloader").hide();
                  $("#ajaxloader2").hide();

                  $('a[data-toggle="tab"]').on( 'shown.bs.tab', function (e) {
                    var target = $(e.target).attr("href") // activated tab


                      $.fn.dataTable.tables( {visible: true, api: true} ).columns.adjust();


                  } );


                  $(".alltable thead input").keyup ( function () {

                          /* Filter on the column (the index) of this element */
                          if ($('#alltable').length > 0)
                          {

                              var colnum=document.getElementById('alltable').rows[0].cells.length;

                              if (this.value=="[empty]")
                              {

                                 alltable.fnFilter( '^$', this.name,true,false );
                              }
                              else if (this.value=="[nonempty]")
                              {

                                 alltable.fnFilter( '^(?=\\s*\\S).*$', this.name,true,false );
                              }
                              else if (this.value.startsWith("!")==true && this.value.length>1)
                              {

                                 alltable.fnFilter( '^(?'+ this.value +').*', this.name,true,false );
                              }
                              else if (this.value.startsWith("!")==false)
                              {

                                  alltable.fnFilter( this.value, this.name,true,false );
                              }
                          }



                  } );

                  $(".finaltable thead input").keyup ( function () {

                          /* Filter on the column (the index) of this element */
                          if ($('#finaltable').length > 0)
                          {

                              var colnum=document.getElementById('finaltable').rows[0].cells.length;

                              if (this.value=="[empty]")
                              {

                                 finaltable.fnFilter( '^$', this.name,true,false );
                              }
                              else if (this.value=="[nonempty]")
                              {

                                 finaltable.fnFilter( '^(?=\\s*\\S).*$', this.name,true,false );
                              }
                              else if (this.value.startsWith("!")==true && this.value.length>1)
                              {

                                 finaltable.fnFilter( '^(?'+ this.value +').*', this.name,true,false );
                              }
                              else if (this.value.startsWith("!")==false)
                              {

                                  finaltable.fnFilter( this.value, this.name,true,false );
                              }
                          }



                  } );

                  $(".leavetable thead input").keyup ( function () {

                          /* Filter on the column (the index) of this element */
                          if ($('#leavetable').length > 0)
                          {

                              var colnum=document.getElementById('leavetable').rows[0].cells.length;

                              if (this.value=="[empty]")
                              {

                                 leavetable.fnFilter( '^$', this.name,true,false );
                              }
                              else if (this.value=="[nonempty]")
                              {

                                 leavetable.fnFilter( '^(?=\\s*\\S).*$', this.name,true,false );
                              }
                              else if (this.value.startsWith("!")==true && this.value.length>1)
                              {

                                 leavetable.fnFilter( '^(?'+ this.value +').*', this.name,true,false );
                              }
                              else if (this.value.startsWith("!")==false)
                              {

                                  leavetable.fnFilter( this.value, this.name,true,false );
                              }
                          }



                  } );

                  $(".leave2table thead input").keyup ( function () {

                          /* Filter on the column (the index) of this element */
                          if ($('#leave2table').length > 0)
                          {

                              var colnum=document.getElementById('leave2table').rows[0].cells.length;

                              if (this.value=="[empty]")
                              {

                                 leave2table.fnFilter( '^$', this.name,true,false );
                              }
                              else if (this.value=="[nonempty]")
                              {

                                 leave2table.fnFilter( '^(?=\\s*\\S).*$', this.name,true,false );
                              }
                              else if (this.value.startsWith("!")==true && this.value.length>1)
                              {

                                 leave2table.fnFilter( '^(?'+ this.value +').*', this.name,true,false );
                              }
                              else if (this.value.startsWith("!")==false)
                              {

                                  leave2table.fnFilter( this.value, this.name,true,false );
                              }
                          }



                  } );

                  $(".leave3table thead input").keyup ( function () {

                          /* Filter on the column (the index) of this element */
                          if ($('#leave3table').length > 0)
                          {

                              var colnum=document.getElementById('leave3table').rows[0].cells.length;

                              if (this.value=="[empty]")
                              {

                                 leave3table.fnFilter( '^$', this.name,true,false );
                              }
                              else if (this.value=="[nonempty]")
                              {

                                 leave3table.fnFilter( '^(?=\\s*\\S).*$', this.name,true,false );
                              }
                              else if (this.value.startsWith("!")==true && this.value.length>1)
                              {

                                 leave3table.fnFilter( '^(?'+ this.value +').*', this.name,true,false );
                              }
                              else if (this.value.startsWith("!")==false)
                              {

                                  leave3table.fnFilter( this.value, this.name,true,false );
                              }
                          }



                  } );

              } );

      </script>

@endsection

@section('content')

    <div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
      <h1>
        Leave Management
        <small>Human Resource</small>
      </h1>
      <ol class="breadcrumb">
        <li><a href="{{ url('/home') }}"><i class="fa fa-dashboard"></i> Home</a></li>
        <li><a href="#">Human Resource</a></li>
        <li><a href="#">Leave</a></li>
        <li class="active">Leave Management</li>
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

        <div class="modal fade" id="Redirect" role="dialog" aria-labelledby="myModalLabel">
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
                          <option  value="{{$user->Id}}">{{$user->Name}} - {{$user->Country}} - {{$user->Level}}</option>
                        @else
                          <option  value="{{$user->Id}}">{{$user->Name}} - {{$user->Project_Name}} - {{$user->Level}}</option>
                        @endif

                      @endforeach

                      </select>

                </div>
              </div>
              <div class="modal-footer">
                <center><img src="{{ URL::to('/') ."/img/ajax-loader.gif" }}" width="50px" height="50px" alt="Loading" name='ajaxloader2' id="ajaxloader2"></center>
                <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                <button type="button" class="btn btn-primary" onclick="redirect()">Redirect</button>
              </div>
            </div>
          </div>
        </div>

        <div class="modal fade" id="Submit" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
          <div class="modal-dialog" role="document">
            <div class="modal-content">
              <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title" id="myModalLabel">Submit for approval</h4>
              </div>
              <div class="modal-body">
                  Are you sure you wish to submit the selected leave for next action?
              </div>
              <div class="modal-footer">
                <center><img src="{{ URL::to('/') ."/img/ajax-loader.gif" }}" width="50px" height="50px" alt="Loading" name='ajaxloader' id="ajaxloader"></center>
                <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                <button type="button" class="btn btn-primary" onclick="submit()">Yes</button>
              </div>
            </div>
          </div>
        </div>

        <div class="col-md-6">
            <div class="box box-solid">
              <br>
              <div align="center">

                <i class="fa fa-circle success"></i>  Final Approved&nbsp;&nbsp;&nbsp;

                <i class="fa fa-circle alert2"></i>  Rejected&nbsp;&nbsp;&nbsp;

                <i class="fa fa-circle warning"></i>  Pending Approval / Not yet Final Approved&nbsp;&nbsp;&nbsp;

              </div>
                <div class="box-header with-border">
                    <div id="calendar"></div>
                </div>


            </div>
        </div>

        <div class="col-md-4">




        </div>
      </div>

      <div class="row">
        <div class="col-md-12">
          <div class="nav-tabs-custom">
            <ul class="nav nav-tabs">

              <li class="active"><a href="#pendingapproval" data-toggle="tab" id="pendingapprovaltab">Pending Approval Leave</a></li>
              <li><a href="#approvedleave" data-toggle="tab" id="approvedleavetab">Approved Leave</a></li>
              <li><a href="#rejectedleave" data-toggle="tab" id="rejectedleavetab">Rejected Leave</a></li>
              @if($me->View_All_Leave)
                <li><a href="#allleave" data-toggle="tab" id="allleavetab">All Leave</a></li>
                <li><a href="#finalleave" data-toggle="tab" id="finalleavetab">Final Approved Leave</a></li>
              @endif
            </ul>

            <div class="tab-content">

              <div class="tab-pane" id="allleave">

                <div class="row">
                    <br>

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
                  <label></label>
              </div>

                <table id="alltable" class="alltable" cellspacing="0" width="100%" padding="30px" style="font-size: 13px;">
                    <thead>
                        {{-- prepare header search textbox --}}
                        @if($allleaves)
                          <tr class="search">

                            @foreach($allleaves as $key=>$value)

                              @if ($key==0)
                                <?php $i = 0; ?>

                                @foreach($value as $field=>$a)
                                    @if ($i==0|| $i==1)
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
                        @endif
                        <tr>
                          @foreach($allleaves as $key=>$value)

                            @if ($key==0)
                              <td><input type="checkbox" name="selectall" id="selectall" value="all" onclick="checkall(0)"></td>
                              @foreach($value as $field=>$value)
                                  <td/>{{ $field }}</td>
                              @endforeach

                            @endif

                          @endforeach
                        </tr>
                    </thead>
                    <tbody>

                      <?php $i = 0; ?>
                      @foreach($allleaves as $leave)

                          <tr id="row_{{ $i }}">
                              <td></td>
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
              </div>

              <div class="active tab-pane" id="pendingapproval">
                {{-- <button type="button" class="btn btn-danger btn-lg" id="submitbtn" data-toggle="modal" data-target="#Submit">Submit and Notify</button> --}}

                {{-- <button type="button" class="btn btn-danger btn-lg" id="recallbtn"  data-toggle="modal" data-target="#Recall">Recall</button> --}}

                <button type="button" class="btn btn-danger btn" data-toggle="modal" onclick="approve()">Approve</button>
                <!-- <button type="button" class="btn btn-success btn" data-toggle="modal" onclick="approve2()">Approve with Special Attention</button> -->
                <button type="button" class="btn btn-danger btn" data-toggle="modal" onclick="reject()">Reject</button>
                <button type="button" class="btn btn-danger btn" data-toggle="modal" data-target="#Redirect">Redirect</button>

                <br><br>

                <table id="leavetable" class="leavetable" cellspacing="0" width="100%" padding="30px" style="font-size: 13px;">
                    <thead>
                        {{-- prepare header search textbox --}}
                        @if($leaves)
                          <tr class="search">

                            @foreach($leaves as $key=>$value)

                              @if ($key==0)
                                <?php $i = 0; ?>

                                @foreach($value as $field=>$a)
                                    @if ($i==0|| $i==1)
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
                        @endif
                        <tr>
                          @foreach($leaves as $key=>$value)

                            @if ($key==0)
                              <td><input type="checkbox" name="selectall0" id="selectall0" value="all" onclick="checkall(0)"></td>

                              @foreach($value as $field=>$value)
                                  <td/>{{ $field }}</td>
                              @endforeach

                            @endif

                          @endforeach
                        </tr>
                    </thead>
                    <tbody>

                      <?php $i = 0; ?>
                      @foreach($leaves as $leave)

                        @if($leave->Status=="Pending Approval")

                          <tr id="row_{{ $i }}">
                              <td></td>

                              @foreach($leave as $key=>$value)
                                <td>
                                  {{ $value }}
                                </td>
                              @endforeach
                          </tr>
                          <?php $i++; ?>

                        @endif

                      @endforeach

                  </tbody>
                    <tfoot></tfoot>
                </table>
              </div>

              <div class="tab-pane" id="approvedleave">
                {{-- <button type="button" class="btn btn-primary btn-lg" id="submitbtn" data-toggle="modal" data-target="#Submit">Submit and Notify</button> --}}

                {{-- <button type="button" class="btn btn-danger btn-lg" id="recallbtn"  data-toggle="modal" data-target="#Recall">Recall</button> --}}

                <button type="button" class="btn btn-success btn" data-toggle="modal" onclick="approve3()">Approve</button>
                {{-- <button type="button" class="btn btn-success btn" data-toggle="modal" onclick="approve4()">Approve with Special Attention</button> --}}
                <button type="button" class="btn btn-danger btn" data-toggle="modal" onclick="reject2()">Reject</button>
                <button type="button" class="btn btn-warning btn" data-toggle="modal" data-target="#Redirect">Redirect</button>
                <br><br>

                <table id="leave2table" class="leave2table" cellspacing="0" width="100%" padding="30px" style="font-size: 13px;">
                    <thead>
                        {{-- prepare header search textbox --}}
                        @if($leaves)
                          <tr class="search">

                            @foreach($leaves as $key=>$value)

                              @if ($key==0)
                                <?php $i = 0; ?>

                                @foreach($value as $field=>$a)
                                    @if ($i==0|| $i==1)
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
                        @endif
                        <tr>
                          @foreach($leaves as $key=>$value)

                            @if ($key==0)
                              <td><input type="checkbox" name="selectall1" id="selectall1" value="all" onclick="checkall(1)"></td>
                              @foreach($value as $field=>$value)
                                  <td/>{{ $field }}</td>
                              @endforeach

                            @endif

                          @endforeach
                        </tr>
                    </thead>
                    <tbody>

                      <?php $i = 0; ?>
                      @foreach($leaves as $leave)

                        @if(strpos($leave->Status,"Approved")!==false)

                          <tr id="row_{{ $i }}">
                              <td></td>
                              @foreach($leave as $key=>$value)
                                <td>
                                  {{ $value }}
                                </td>
                              @endforeach
                          </tr>
                          <?php $i++; ?>

                        @endif

                      @endforeach

                  </tbody>
                    <tfoot></tfoot>
                </table>

              </div>

              <div class="tab-pane" id="finalleave">

                <table id="finaltable" class="finaltable" cellspacing="0" width="100%" padding="30px" style="font-size: 13px;">
                    <thead>
                        {{-- prepare header search textbox --}}
                        @if($finalapprovedleave)
                          <tr class="search">

                            @foreach($finalapprovedleave as $key=>$value)

                              @if ($key==0)
                                <?php $i = 0; ?>

                                @foreach($value as $field=>$a)
                                    @if ($i==0|| $i==1)
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
                        @endif
                        <tr>
                          @foreach($finalapprovedleave as $key=>$value)

                            @if ($key==0)
                              <td><input type="checkbox" name="selectall1" id="selectall1" value="all" onclick="checkall(1)"></td>
                              @foreach($value as $field=>$value)
                                  <td/>{{ $field }}</td>
                              @endforeach

                            @endif

                          @endforeach
                        </tr>
                    </thead>
                    <tbody>

                      <?php $i = 0; ?>
                      @foreach($finalapprovedleave as $leave)

                          <tr id="row_{{ $i }}">
                              <td></td>
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

              </div>

              <div class="tab-pane" id="rejectedleave">
                <table id="leave3table" class="leave3table" cellspacing="0" width="100%" padding="30px" style="font-size: 13px;">
                    <thead>
                        {{-- prepare header search textbox --}}
                        @if($leaves)
                          <tr class="search">

                            @foreach($leaves as $key=>$value)

                              @if ($key==0)
                                <?php $i = 0; ?>

                                @foreach($value as $field=>$a)
                                    @if ($i==0|| $i==1)
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
                        @endif
                        <tr>
                          @foreach($leaves as $key=>$value)

                            @if ($key==0)
                              <td><input type="checkbox" name="selectall2" id="selectall2" value="all" onclick="checkall(2)"></td>
                              @foreach($value as $field=>$value)
                                  <td/>{{ $field }}</td>
                              @endforeach

                            @endif

                          @endforeach
                        </tr>
                    </thead>
                    <tbody>

                      <?php $i = 0; ?>
                      @foreach($leaves as $leave)

                        @if(strpos($leave->Status,"Rejected")!==false)

                          <tr id="row_{{ $i }}">
                              <td></td>
                              @foreach($leave as $key=>$value)
                                <td>
                                  {{ $value }}
                                </td>
                              @endforeach
                          </tr>
                          <?php $i++; ?>

                        @endif

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
      <b>Version</b> 2.0.1
    </div>
    <strong>Copyright &copy; 2014-2016 <a href="http://www.softoya.com">TrackerOnTheGo</a>.</strong> All rights
    reserved.
  </footer>

  <script>
  $(function () {

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
        right: 'listWeek,month,agendaWeek,agendaDay'
      },
      defaultView: 'listWeek',
      buttonText: {
        list:  'List',
        today: 'Today',
        month: 'Month',
        week:  'Week',
        day:   'Day',
      },
      //Random default events
      events: [

        @foreach($showleave as $leave)

            @if(strpos($leave->Status,"Approved")!==false || strpos($leave->Status,"Rejected")!==false || $leave->Status="Pending Approval")
            {
              title: "{{ $leave->Name }} - {{ $leave->Leave_Term }}",
              start: new Date("{{date(DATE_ISO8601, strtotime($leave->Start_Date))}}"),
              end: new Date("{{ date(DATE_ISO8601, strtotime("+1 day", strtotime($leave->End_Date)))}}"),
              allDay: true,
              @if(strpos($leave->Status,"Final Approved")!==false)
                backgroundColor: "#00a65a", //green
                borderColor: "#00a65a" //green
              @elseif(strpos($leave->Status,"Rejected")!==false)
                  backgroundColor: "#dd4b39", //red
                  borderColor: "#dd4b39" //red
              @else
                backgroundColor: "#f39c12", //yellow
                borderColor: "#f39c12" //yellow
              @endif

            },
            @endif


        @endforeach

        // {
        //   title: 'All Day Event',
        //   start: new Date(y, m, 1),
        //   backgroundColor: "#f56954", //red
        //   borderColor: "#f56954" //red
        // },
        // {
        //   title: 'Long Event',
        //   start: new Date(y, m, d - 5),
        //   end: new Date(y, m, d - 2),
        //   backgroundColor: "#f39c12", //yellow
        //   borderColor: "#f39c12" //yellow
        // },
        // {
        //   title: 'Meeting',
        //   start: new Date(y, m, d, 10, 30),
        //   allDay: false,
        //   backgroundColor: "#0073b7", //Blue
        //   borderColor: "#0073b7" //Blue
        // },
        // {
        //   title: 'Lunch',
        //   start: new Date(y, m, d, 12, 0),
        //   end: new Date(y, m, d, 14, 0),
        //   allDay: false,
        //   backgroundColor: "#00c0ef", //Info (aqua)
        //   borderColor: "#00c0ef" //Info (aqua)
        // },
        // {
        //   title: 'Birthday Party',
        //   start: new Date(y, m, d + 1, 19, 0),
        //   end: new Date(y, m, d + 1, 22, 30),
        //   allDay: false,
        //   backgroundColor: "#00a65a", //Success (green)
        //   borderColor: "#00a65a" //Success (green)
        // },
        // {
        //   title: 'Click for Google',
        //   start: new Date(y, m, 28),
        //   end: new Date(y, m, 29),
        //   url: 'http://google.com/',
        //   backgroundColor: "#3c8dbc", //Primary (light-blue)
        //   borderColor: "#3c8dbc" //Primary (light-blue)
        // },
      ],

      editable: false,
      droppable: false, // this allows things to be dropped onto the calendar !!!
      eventRender: function(event, element) {
            element.find('.fc-title').append("<br/>");
        }
    });
  });
</script>

<script>
  $(function () {

    //Initialize Select2 Elements
    $(".select2").select2();

    //Flat red color scheme for iCheck
    $('input[type="checkbox"].flat-blue, input[type="radio"].flat-blue').iCheck({
      checkboxClass: 'icheckbox_flat-blue',
      radioClass: 'iradio_flat-blue'
    });

    //Date picker
    $('#Start_Date').datepicker({
      autoclose: true
    });

    $('#End_Date').datepicker({
      autoclose: true
    });

    $('#range').daterangepicker({locale: {
      format: 'DD-MMM-YYYY'
    },startDate: '{{$start}}',
    endDate: '{{$end}}'});

    $('#range2').daterangepicker({locale: {
      format: 'DD-MMM-YYYY'
    },startDate: '{{$start}}',
    endDate: '{{$end}}'});

  });


  // function uncheck()
  // {
  //
  //   if (!$("#selectrow"+index).is(':checked')) {
  //     $("#selectall"+index).prop("checked", false)
  //   }

  function uncheck(index)
  {

    if (!$("#selectrow"+index).is(':checked')) {
      $("#selectall"+index).prop("checked", false)
    }

  }

  function checkall(index)
  {
// alert(document.getElementById("selectall").checked);

    if ($("#selectall"+index).is(':checked')) {

        $(".selectrow"+index).prop("checked", true);
         $(".selectrow"+index).trigger("change");
         if (index==0)
         {
              leavetable.api().rows().select();
         }else if (index==1)
         {
              leave2table.api().rows().select();
         }else if (index==2)
          {
              leave3table.api().rows().select();
          }

    } else {

        $(".selectrow"+index).prop("checked", false);
        $(".selectrow"+index).trigger("change");
         //leavetable.rows().deselect();
         if (index==0)
         {
              leavetable.api().rows().deselect();
         }else if (index==1) {
              leave2table.api().rows().deselect();
         }else if (index==2)
          {
              leave3table.api().rows().deselect();
          }

    }
  }

  function OpenRedirectDialog(id,currentapprover)
  {
    var hiddeninput='<input type="hidden" class="form-control" id="selectedleavestatusid" name="selectedleavestatusid" value="'+id+'">';

      $("#NewApprover").val(currentapprover).change();

      $( "#redirectleavestatus" ).html(hiddeninput);
      $('#Redirect').modal('show');

  }

  function submit() {

    var boxes = $(".selectrow"+selectedtabindex+":checkbox:checked");
    var ids="";

    if (boxes.length>0)
    {
      for (var i = 0; i < boxes.length; i++) {
        ids+=boxes[i].value+",";
      }

      ids=ids.substring(0, ids.length-1);

      $.ajaxSetup({
         headers: { 'X-CSRF-Token' : $('meta[name=_token]').attr('content') }
      });

        $("#ajaxloader").show();

      $.ajax({
                  url: "{{ url('/leavemanagement/submit') }}",
                  method: "POST",
                  data: {LeaveIds:ids},

                  success: function(response){

                    if (response==1)
                    {
                      leavetable.ajax.reload();
                      leave2table.ajax.reload();
                      leave3table.ajax.reload();

                        $('#Submit').modal('hide');

                        var message="Submitted for next action!";
                        $("#update-alert ul").html(message);
                        $("#update-alert").modal("show");

                        $("#ajaxloader").hide();

                    }
                    else {

                      $('#Submit').modal('hide');

                      var errormessage="Failed to submit for next action!";
                      $("#error-alert ul").html(errormessage);
                      $("#error-alert").modal("show");


                      $("#ajaxloader").hide();

                    }

          }
      });

  }
  else {

    $('#Submit').modal('hide');

    var errormessage="No leave selected!";
    $("#error-alert ul").html(errormessage);
    $("#error-alert").modal("show");

    $("#ajaxloader").hide();
  }
}

  function redirect() {

      $.ajaxSetup({
         headers: { 'X-CSRF-Token' : $('meta[name=_token]').attr('content') }
      });

      $("#ajaxloader").show();
      var boxes = $('input[type="checkbox"]:checked');
      var ids="";

      if (boxes.length>0)
      {
        for (var i = 0; i < boxes.length; i++) {
          ids+=boxes[i].value+",";
        }

        ids=ids.substring(0, ids.length-1);

        newapprover=$('[name="NewApprover"]').val();

        $.ajax({
                  url: "{{ url('/leavemanagement/redirect') }}",
                  method: "POST",
                  data: {Ids:ids,Approver:newapprover},

                  success: function(response){

                    if (response==1)
                    {
                        leavetable.api().ajax.url("{{ asset('/Include/leaveapproval.php') }}").load();

                        $('#Redirect').modal('hide');

                        var message="Leave approval redirected!";
                        $("#update-alert ul").html(message);
                        $("#update-alert").modal("show");

                        $("#ajaxloader").hide();

                    }
                    else {

                      $('#Redirect').modal('hide');

                      var errormessage="Failed to redirect leave approval!";
                      $("#error-alert ul").html(errormessage);
                      $("#error-alert").modal("show");

                      $("#ajaxloader").hide();

                    }

          }
      });


      }
      else {

        $('#Redirect').modal('hide');

        var errormessage="No leave selected!";
        $("#error-alert ul").html(errormessage);
        $("#error-alert").modal("show");



        $("#ajaxloader").hide();
      }
    }

  function applyleave() {

      $.ajaxSetup({
         headers: { 'X-CSRF-Token' : $('meta[name=_token]').attr('content') }
      });

      leave_type=$('[name="Leave_Type"]').val();
      leave_term=$('[name="Leave_Term"]').val();
      start_date=$('[name="Start_Date"]').val();
      end_date=$('[name="End_Date"]').val();
      reason=$('[name="Reason"]').val();

      $.ajax({
                  url: "{{ url('/myleave/apply') }}",
                  method: "POST",
                  data: {Leave_Type:leave_type,Leave_Term:leave_term,Start_Date:start_date,End_Date:end_date,Reason:reason},

                  success: function(response){

                    if (response==1)
                    {

                        $("#update-alert").modal("show");

                        $("#error-alert").hide();

                        $("#Leave_Type").val("").change();
                        document.getElementById("Leave_Term_2").checked=false;
                        document.getElementById("Leave_Term_3").checked=false;
                        document.getElementById("Leave_Term_1").checked=true;
                        document.getElementById("Start_Date").value = ''
                        document.getElementById("End_Date").value = ''
                        document.getElementById("Reason").value = ''
                    }
                    else {
                        var obj = jQuery.parseJSON(response);
                        var errormessage ="";

                        for (var item in obj) {
                          errormessage=errormessage + "<li> " + obj[item] + "</li>";
                        }

                        $("#error-alert ul").html(errormessage);
                        $("#error-alert").modal("show");


                    }

          }
      });

    }

    function refresh()
    {
      var d=$('#range').val();
      var arr = d.split(" - ");

      window.location.href ="{{ url("/leavemanagement") }}/"+arr[0]+"/"+arr[1];

    }

  function reject() {

      var boxes = $('input[type="checkbox"]:checked', leavetable.fnGetNodes() );
      var ids="";

      if (boxes.length>0)
      {
        for (var i = 0; i < boxes.length; i++) {
          ids+=boxes[i].value+",";
        }

        ids=ids.substring(0, ids.length-1);


        status="Rejected";

        @if($mylevel)
          @if ($mylevel->Level=="1st Approval")
            status="1st Rejected";
          @endif

          @if ($mylevel->Level=="2nd Approval")
            status="2nd Rejected";
          @endif

          @if ($mylevel->Level=="3rd Approval")
            status="3rd Rejected";
          @endif

          @if ($mylevel->Level=="4th Approval")
            status="4th Rejected";
          @endif

          @if ($mylevel->Level=="5th Approval")
            status="5th Rejected";
          @endif

          @if ($mylevel->Level=="Final Approval")
            status="Final Rejected";
          @endif

        @endif

        $.ajaxSetup({
           headers: { 'X-CSRF-Token' : $('meta[name=_token]').attr('content') }
        });

          // $("#ajaxloader").show();

        $.ajax({
                    url: "{{ url('/leave/approve') }}",
                    method: "POST",
                    data: {Ids:ids,
                    Status:status},

                    success: function(response){

                      if (response==1)
                      {

                          leavetable.api().ajax.url("{{ asset('/Include/leaveapproval.php') }}").load();
                          leave2table.api().ajax.reload();
                          leave3table.api().ajax.reload();
                          alltable.api().ajax.reload();
                          finaltable.api().ajax.reload();

                          var message="Leave Rejected!";
                          $("#update-alert ul").html(message);
                          $("#update-alert").modal('show');

                         $("#ajaxloader").hide();

                      }
                      else {

                        var errormessage="Failed to reject leave!";
                        $("#error-alert ul").html(errormessage);
                        $("#error-alert").modal('show');

                        $("#ajaxloader").hide();

                      }

            }
        });

    }
    else {
      var errormessage="No leave selected!";
      $("#error-alert ul").html(errormessage);
      $("#error-alert").modal('show');

    }

  }

  function reject2() {

      var boxes = $('input[type="checkbox"]:checked', leavetable2.fnGetNodes() );
      var ids="";

      if (boxes.length>0)
      {
        for (var i = 0; i < boxes.length; i++) {
          ids+=boxes[i].value+",";
        }

        ids=ids.substring(0, ids.length-1);


        status="Rejected";

        @if($mylevel)
          @if ($mylevel->Level=="1st Approval")
            status="1st Rejected";
          @endif

          @if ($mylevel->Level=="2nd Approval")
            status="2nd Rejected";
          @endif

          @if ($mylevel->Level=="3rd Approval")
            status="3rd Rejected";
          @endif

          @if ($mylevel->Level=="4th Approval")
            status="4th Rejected";
          @endif

          @if ($mylevel->Level=="5th Approval")
            status="5th Rejected";
          @endif

          @if ($mylevel->Level=="Final Approval")
            status="Final Rejected";
          @endif

        @endif

        $.ajaxSetup({
           headers: { 'X-CSRF-Token' : $('meta[name=_token]').attr('content') }
        });

          // $("#ajaxloader").show();

        $.ajax({
                    url: "{{ url('/leave/approve') }}",
                    method: "POST",
                    data: {Ids:ids,
                    Status:status},

                    success: function(response){

                      if (response==1)
                      {

                          leavetable.api().ajax.url("{{ asset('/Include/leaveapproval.php') }}").load();
                          leave2table.api().ajax.reload();
                          leave3table.api().ajax.reload();
                          alltable.api().ajax.reload();
                          finaltable.api().ajax.reload();

                          var message="Leave Rejected!";
                          $("#update-alert ul").html(message);
                          $("#update-alert").modal('show');


                         $("#ajaxloader").hide();

                      }
                      else {

                        var errormessage="Failed to reject leave!";
                        $("#error-alert ul").html(errormessage);
                        $("#error-alert").modal('show');

                        $("#ajaxloader").hide();

                      }

            }
        });

    }
    else {
      var errormessage="No leave selected!";
      $("#error-alert ul").html(errormessage);
      $("#error-alert").modal('show');

    }

  }

  function approve2() {

    var boxes = $('input[type="checkbox"]:checked', leavetable.fnGetNodes() );
    var ids="";

    if (boxes.length>0)
    {
      for (var i = 0; i < boxes.length; i++) {
        ids+=boxes[i].value+",";
      }

      ids=ids.substring(0, ids.length-1);

      status="Approved with Special Attention";

      @if($mylevel)
        @if ($mylevel->Level=="1st Approval")
          status="1st Approved with Special Attention";
        @endif

        @if ($mylevel->Level=="2nd Approval")
          status="2nd Approved with Special Attention";
        @endif

        @if ($mylevel->Level=="3rd Approval")
          status="3rd Approved with Special Attention";
        @endif

        @if ($mylevel->Level=="4th Approval")
          status="4th Approved with Special Attention";
        @endif

        @if ($mylevel->Level=="5th Approval")
          status="5th Approved with Special Attention";
        @endif

        @if ($mylevel->Level=="Final Approval")
          status="Final Approved with Special Attention";
        @endif

      @endif

      $.ajaxSetup({
         headers: { 'X-CSRF-Token' : $('meta[name=_token]').attr('content') }
      });

        $("#ajaxloader").show();

      $.ajax({
                  url: "{{ url('/leave/approve') }}",
                  method: "POST",
                  data: {Ids:ids,
                  Status:status},

                  success: function(response){

                    if (response==1)
                    {

                        leavetable.api().ajax.url("{{ asset('/Include/leaveapproval.php') }}").load();
                        leave2table.api().ajax.reload();
                        leave3table.api().ajax.reload();
                        alltable.api().ajax.reload();
                        finaltable.api().ajax.reload();

                        var message="Leave Approved!";
                        $("#update-alert ul").html(message);
                        $("#update-alert").modal('show');

                       $("#ajaxloader").hide();

                    }
                    else {

                      var errormessage="Failed to approve leave!";
                      $("#error-alert ul").html(errormessage);
                      $("#error-alert").modal('show');

                      $("#ajaxloader").hide();

                    }

          }
      });

    }
    else {
      var errormessage="No leave selected!";
      $("#error-alert ul").html(errormessage);
      $("#error-alert").modal('show');

    }

  }

  function approve4() {

    var boxes = $('input[type="checkbox"]:checked', leavetable2.fnGetNodes() );
    var ids="";

    if (boxes.length>0)
    {
      for (var i = 0; i < boxes.length; i++) {
        ids+=boxes[i].value+",";
      }

      ids=ids.substring(0, ids.length-1);

      status="Approved with Special Attention";

      @if($mylevel)
        @if ($mylevel->Level=="1st Approval")
          status="1st Approved with Special Attention";
        @endif

        @if ($mylevel->Level=="2nd Approval")
          status="2nd Approved with Special Attention";
        @endif

        @if ($mylevel->Level=="3rd Approval")
          status="3rd Approved with Special Attention";
        @endif

        @if ($mylevel->Level=="4th Approval")
          status="4th Approved with Special Attention";
        @endif

        @if ($mylevel->Level=="5th Approval")
          status="5th Approved with Special Attention";
        @endif

        @if ($mylevel->Level=="Final Approval")
          status="Final Approved with Special Attention";
        @endif

      @endif

      $.ajaxSetup({
         headers: { 'X-CSRF-Token' : $('meta[name=_token]').attr('content') }
      });

        $("#ajaxloader").show();

      $.ajax({
                  url: "{{ url('/leave/approve') }}",
                  method: "POST",
                  data: {Ids:ids,
                  Status:status},

                  success: function(response){

                    if (response==1)
                    {

                        leave2table.api().ajax.url("{{ asset('/Include/leaveapproval.php') }}").load();
                        leave2table.api().ajax.reload();
                        leave3table.api().ajax.reload();
                        alltable.api().ajax.reload();
                        finaltable.api().ajax.reload();

                        var message="Leave Approved!";
                        $("#update-alert ul").html(message);
                        $("#update-alert").modal('show');


                       $("#ajaxloader").hide();

                    }
                    else {

                      var errormessage="Failed to approve leave!";
                      $("#error-alert ul").html(errormessage);
                      $("#error-alert").modal('show');

                      $("#ajaxloader").hide();

                    }

          }
      });

    }
    else {
      var errormessage="No leave selected!";
      $("#error-alert ul").html(errormessage);
      $("#error-alert").modal('show');

    }

  }

  function approve() {

    var boxes = $('input[type="checkbox"]:checked', leavetable.fnGetNodes() );
    var ids="";

    if (boxes.length>0)
    {
      for (var i = 0; i < boxes.length; i++) {
        ids+=boxes[i].value+",";
      }

      ids=ids.substring(0, ids.length-1);

      status="Approved";

      @if($mylevel)
        @if ($mylevel->Level=="1st Approval")
          status="1st Approved";
        @endif

        @if ($mylevel->Level=="2nd Approval")
          status="2nd Approved";
        @endif

        @if ($mylevel->Level=="3rd Approval")
          status="3rd Approved";
        @endif

        @if ($mylevel->Level=="4th Approval")
          status="4th Approved";
        @endif

        @if ($mylevel->Level=="5th Approval")
          status="5th Approved";
        @endif

        @if ($mylevel->Level=="Final Approval")
          status="Final Approved";
        @endif

      @endif

      $.ajaxSetup({
         headers: { 'X-CSRF-Token' : $('meta[name=_token]').attr('content') }
      });

        $("#ajaxloader").show();

      $.ajax({
                  url: "{{ url('/leave/approve') }}",
                  method: "POST",
                  data: {Ids:ids,
                  Status:status},

                  success: function(response){

                    if (response==1)
                    {

                        leavetable.api().ajax.url("{{ asset('/Include/leaveapproval.php') }}").load();

                        var message="Leave Approved!";
                        $("#update-alert ul").html(message);
                        $("#update-alert").modal('show');

                        $("#ajaxloader").hide();

                    }
                    else {

                      var errormessage="Failed to approve leave!";
                      $("#error-alert ul").html(errormessage);
                      $("#error-alert").modal('show');

                      $("#ajaxloader").hide();

                    }

          }
      });

    }
    else {
      var errormessage="No Leave selected!";
      $("#error-alert ul").html(errormessage);
      $("#error-alert").modal('show');


    }

  }

  function approve3() {

    var boxes = $('input[type="checkbox"]:checked', leave2table.fnGetNodes() );
    var ids="";

    if (boxes.length>0)
    {
      for (var i = 0; i < boxes.length; i++) {
        ids+=boxes[i].value+",";
      }

      ids=ids.substring(0, ids.length-1);

      status="Approved";

      @if($mylevel)
        @if ($mylevel->Level=="1st Approval")
          status="1st Approved";
        @endif

        @if ($mylevel->Level=="2nd Approval")
          status="2nd Approved";
        @endif

        @if ($mylevel->Level=="3rd Approval")
          status="3rd Approved";
        @endif

        @if ($mylevel->Level=="4th Approval")
          status="4th Approved";
        @endif

        @if ($mylevel->Level=="5th Approval")
          status="5th Approved";
        @endif

        @if ($mylevel->Level=="Final Approval")
          status="Final Approved";
        @endif

      @endif

      $.ajaxSetup({
         headers: { 'X-CSRF-Token' : $('meta[name=_token]').attr('content') }
      });

        $("#ajaxloader").show();

      $.ajax({
                  url: "{{ url('/leave/approve') }}",
                  method: "POST",
                  data: {Ids:ids,
                  Status:status},

                  success: function(response){

                    if (response==1)
                    {

                        leave2table.api().ajax.url("{{ asset('/Include/leaveapproval.php') }}").load();

                        var message="Leave Approved!";
                        $("#update-alert ul").html(message);
                        $("#update-alert").modal('show');


                        $("#ajaxloader").hide();

                    }
                    else {

                      var errormessage="Failed to approve leave!";
                      $("#error-alert ul").html(errormessage);
                      $("#error-alert").modal('show');


                      $("#ajaxloader").hide();

                    }

          }
      });

    }
    else {
      var errormessage="No Leave selected!";
      $("#error-alert ul").html(errormessage);
      $("#error-alert").modal('show');


    }

  }

</script>

@endsection
