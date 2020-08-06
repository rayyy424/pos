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

          var requestseditor;
          var requests2editor;
          var requests3editor;
          var alleditor;
          var finaleditor;

          var requesttable;
          var requesttable2;
          var requesttable3;
          var alltable;
          var finaltable;
          var selectedtabindex;
          var asInitVals = new Array();

          $(document).ready(function() {

            $.fn.dataTable.moment( 'DD-MMM-YYYY' );

            alleditor = new $.fn.dataTable.Editor( {
                   ajax: "{{ asset('/Include/requestapproval.php') }}",
                    table: "#alltable",
                    idSrc: "requeststatuses.Id",
                    fields: [
                      {

                               label: "Comment:",
                               name: "requeststatuses.Comment",
                               type: "textarea"
                       }

                    ]
            } );

            finaleditor = new $.fn.dataTable.Editor( {
                   ajax: "{{ asset('/Include/requestapproval.php') }}",
                    table: "#finaltable",
                    idSrc: "requeststatuses.Id",
                    fields: [
                    {
                               label: "Comment:",
                               name: "requeststatuses.Comment",
                               type: "textarea"
                       }

                    ]
            } );

                         requestseditor = new $.fn.dataTable.Editor( {
                                ajax: "{{ asset('/Include/requestapproval.php') }}",
                                 table: "#requesttable",
                                 idSrc: "requeststatuses.Id",
                                 fields: [
                                   {
                                            label: "Comment:",
                                            name: "requeststatuses.Comment",
                                            type: "textarea"
                                    }

                                 ]
                         } );

                         requests2editor = new $.fn.dataTable.Editor( {
                                ajax: "{{ asset('/Include/requestapproval.php') }}",
                                 table: "#requesttable2",
                                 idSrc: "requeststatuses.Id",
                                 fields: [
                                         {

                                                 label: "Comment:",
                                                 name: "requeststatuses.Comment"
                                         }

                                 ]
                         } );

                         requests3editor = new $.fn.dataTable.Editor( {
                                ajax: "{{ asset('/Include/requestapproval.php') }}",
                                 table: "#requesttable3",
                                 idSrc: "requeststatuses.Id",
                                 fields: [
                                  {
                                            label: "Comment:",
                                            name: "requeststatuses.Comment",
                                            type: "textarea"
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
                                  "url": "{{ asset('/Include/requestapproval.php') }}",
                                  "data": {

                                      // "Start": "{{ $start }}",
                                      // "End": "{{ $end }}"
                                  }
                                },
                                 columnDefs: [{ "visible": false, "targets": [2,3,6] },{"className": "dt-left", "targets": "_all"}],
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

                                   $('#allrequesttab').html("All Request" + "[" + alltable.api().rows().count() +"]")

                                  },
                                 rowId:"requeststatuses.Id",
                                 columns: [
                                         {
                                           sortable: false,
                                           "render": function ( data, type, full, meta ) {
                                             return '<input type="checkbox" name="selectrow0" id="selectrow0" class="selectrow0" value="'+full.request.Id+'" onclick="uncheck(0)">';

                                           }

                                         },
                                         {
                                            sortable: false,
                                            "render": function ( data, type, full, meta ) {

                                              if (full.requeststatuses.Request_status!=="Cancelled" && full.requeststatuses.Request_status.includes("Approved")==false)
                                              {
                                                return '<button type="button" class="btn btn-danger btn-sm" data-toggle="modal" onclick="CancelDialog('+full.request.Id+')">Cancel Request</button>';

                                              }
                                              else {
                                                return '';

                                              }

                                            }
                                        },
                                        { data: "request.Id",title:"Id"},
                                        { data: "requeststatuses.Id",title:"Id"},
                                        { data: "applicant.Name",title:"Applicant"},
                                        { data: "applicant.Department",title:"Department"},
                                        { data: "request.Approver",title:"Approver"},
                                        { data: "request.Request_type",title:"Request_type",editfield:"request.Request_type"},
                                        { data: "request.Others",title:"Others"},
                                        { data: "request.Start_Date",title:"Start_Date"},
                                        { data: "request.End_Date",title:"End_Date"},
                                        { data: "request.Remarks",title:"Remarks"},
                                        { data: "request.created_at",title:"created_at"},
                                        { data: "projects.Project_Name",title:"Project_Name"},
                                        { data: "approver.Name",title:"Approver", editfield:"request.Approver"},
                                        { data: "requeststatuses.Request_status",title:"Request Status",
                                           "render": function( data, type, full, meta ) {
                                                   if(full.requeststatuses.Request_status.includes("Approved"))
                                                    {
                                                      return "<span class='green'>"+full.requeststatuses.Request_status+"</span>";
                                                    }
                                                    else if(full.requeststatuses.Request_status.includes("Rejected"))
                                                    {
                                                      return "<span class='red'>"+full.requeststatuses.Request_status+"</span>";
                                                    }
                                                    else if(full.requeststatuses.Request_status.includes("Cancelled"))
                                                    {
                                                      return "<span class='red'>"+full.requeststatuses.Request_status+"</span>";
                                                    }
                                                    else {
                                                      return "<span class='yellow'>"+full.requeststatuses.Request_status+"</span>";
                                                    }


                                           }

                                        },
                                        { data: "requeststatuses.updated_at",title:"updated_at"},
                                        { data: "requeststatuses.Comment",title:"Comment"}
                                         // { data: "requeststatuses.Id"},
                                         // { data: "requests.Id"},
                                         // { data: "requeststatuses.Request_Status",title:"Request_Status",
                                         //   "render": function ( data, type, full, meta ) {
                                         //
                                         //        if(full.requeststatuses.Request_Status.includes("Approved"))
                                         //        {
                                         //          return "<span class='green'>"+full.requeststatuses.Request_Status+"</span>";
                                         //        }
                                         //        else if(full.requeststatuses.Request_Status.includes("Rejected"))
                                         //        {
                                         //          return "<span class='red'>"+full.requeststatuses.Request_Status+"</span>";
                                         //        }
                                         //        else if(full.requeststatuses.Request_Status.includes("Cancelled"))
                                         //        {
                                         //          return "<span class='red'>"+full.requeststatuses.Request_Status+"</span>";
                                         //        }
                                         //        else {
                                         //          return "<span class='yellow'>"+full.requeststatuses.Request_Status+"</span>";
                                         //        }
                                         //
                                         //     }
                                         // },
                                         // { data: "applicant.StaffId",title:"Staff_ID"},
                                         // { data: "applicant.Name",title:"Name"},
                                         // { data: "requests.Request_Type",title:"Request_Type" },
                                         // { data: "requests.Request_Term",title:"Request_Term" },
                                         // { data: "requests.Start_Date",title:"Start_Date"},
                                         // { data: "requests.End_Date",title:"End_Date"},
                                         // { data: "requests.No_of_Days",title:"No_of_Days"},
                                         // { data: "requests.Reason",title:"Reason"},
                                         // { data: "requests.created_at",title:"Application_Date"},
                                         // { data: "projects.Project_Name",title:"Project_Name"},
                                         // { data: "approver.Name",title:"Approver"},
                                         // { data: "requeststatuses.Comment",title:"Comment"},
                                         // { data: "requeststatuses.updated_at",title:"Review_Date"},
                                         // { data: "files.Web_Path",
                                         //    render: function ( url, type, row ) {
                                         //         if (url)
                                         //         {
                                         //           return '<a href="{{ url("/") }}'+url+'" target="_blank">Download</a>';
                                         //         }
                                         //         else {
                                         //           return ' - ';
                                         //         }
                                         //     },
                                         //     title: "File"
                                         //   }
                                 ],
                                 autoFill: {
                                    editor:  alleditor
                                    // columns: [ 9,10,11]
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

                     finaltable=$('#finaltable').dataTable( {
                           ajax: {
                              "url": "{{ asset('/Include/requestapproval.php') }}",
                              "data": {
                                  "Request_status": "%Final Approved%"
                              }
                            },
                            // aaSorting: [[15,"desc"]],
                             columnDefs: [{ "visible": false, "targets": [2,3,6] },{"className": "dt-left", "targets": "_all"}],
                             responsive: false,
                             colReorder: false,
                             dom: "Bfrtp",
                             sScrollX: "100%",
                             bAutoWidth: true,
                             iDisplayLength:10,
                             sScrollY: "100%",
                             scrollCollapse: true,
                             fnInitComplete: function(oSettings, json) {

                               $('#finalrequesttab').html("Final Approved Request" + "[" + finaltable.api().rows().count() +"]")

                              },
                             rowId:"requeststatuses.Id",
                             columns: [
                               {
                                 sortable: false,
                                 "render": function ( data, type, full, meta ) {
                                   return '<input type="checkbox" name="selectrow0" id="selectrow0" class="selectrow0" value="'+full.request.Id+'" onclick="uncheck(0)">';

                                 }

                               },
                               {
                                  sortable: false,
                                  title:"Action",
                                  "render": function ( data, type, full, meta ) {

                                    if (full.requeststatuses.Request_status!=="Cancelled" && full.requeststatuses.Request_status.includes("Approved")==false)
                                    {
                                      return '<button type="button" class="btn btn-danger btn-sm" data-toggle="modal" onclick="CancelDialog('+full.request.Id+')">Cancel Request</button>';

                                    }
                                    else {
                                      return '';

                                    }

                                  }
                              },
                              { data: "request.Id",title:"Id"},
                              { data: "requeststatuses.Id",title:"Id"},
                              { data: "applicant.Name",title:"Applicant"},
                              { data: "applicant.Department",title:"Department"},
                              { data: "request.Approver",title:"Approver"},
                              { data: "request.Request_type",title:"Request_type",editfield:"request.Request_type"},
                              { data: "request.Others",title:"Others"},
                              { data: "request.Start_Date",title:"Start_Date"},
                              { data: "request.End_Date",title:"End_Date"},
                              { data: "request.Remarks",title:"Remarks"},
                              { data: "request.created_at",title:"created_at"},
                              { data: "projects.Project_Name",title:"Project_Name"},
                              { data: "approver.Name",title:"Approver", editfield:"request.Approver"},
                              { data: "requeststatuses.Request_status",title:"Request Status",
                                 "render": function( data, type, full, meta ) {
                                         if(full.requeststatuses.Request_status.includes("Approved"))
                                          {
                                            return "<span class='green'>"+full.requeststatuses.Request_status+"</span>";
                                          }
                                          else if(full.requeststatuses.Request_status.includes("Rejected"))
                                          {
                                            return "<span class='red'>"+full.requeststatuses.Request_status+"</span>";
                                          }
                                          else if(full.requeststatuses.Request_status.includes("Cancelled"))
                                          {
                                            return "<span class='red'>"+full.requeststatuses.Request_status+"</span>";
                                          }
                                          else {
                                            return "<span class='yellow'>"+full.requeststatuses.Request_status+"</span>";
                                          }


                                 }

                              },
                              { data: "requeststatuses.updated_at",title:"updated_at"},
                              { data: "requeststatuses.Comment",title:"Comment"}
                             ],
                             autoFill: {
                                editor:  finaleditor
                                // columns: [ 9,10,11]
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

                         requesttable=$('#requesttable').dataTable( {
                                ajax: {
                                   "url": "{{ asset('/Include/requestapproval.php') }}",
                                   "data": {
                                       "UserId": {{ $me->UserId }},
                                       "Request_status": "%Pending%"
                                   }
                                 },
                                 columnDefs: [{ "visible": false, "targets": [1,2,5] },{"className": "dt-left", "targets": "_all"}],
                                 responsive: false,
                                 colReorder: false,
                                 dom: "Bfrtp",
                                 sScrollX: "100%",
                                 iDisplayLength:10,
                                 bAutoWidth: true,
                                 sScrollY: "100%",
                                 scrollCollapse: true,
                                 fnInitComplete: function(oSettings, json) {

                                   $('#pendingapprovaltab').html("Pending Approval Request" + "[" + requesttable.api().rows().count() +"]")

                                  },
                                 rowId:"requeststatuses.Id",
                                 columns: [
                                         {
                                           sortable: false,
                                           "render": function ( data, type, full, meta ) {
                                             return '<input type="checkbox" name="selectrow0" id="selectrow0" class="selectrow0" value="'+full.request.Id+'" onclick="uncheck(0)">';

                                           }

                                         },
                                             { data: "request.Id",title:"Id"},
                                             { data: "requeststatuses.Id",title:"Id"},
                                             { data: "applicant.Name",title:"Applicant"},
                                             { data: "applicant.Department",title:"Department"},
                                             { data: "request.Approver",title:"Approver"},
                                             { data: "request.Request_type",title:"Request_type",editfield:"request.Request_type"},
                                             { data: "request.Others",title:"Others"},
                                             { data: "request.Start_Date",title:"Start_Date"},
                                             { data: "request.End_Date",title:"End_Date"},
                                             { data: "request.Remarks",title:"Remarks"},
                                             { data: "request.created_at",title:"created_at"},
                                             { data: "projects.Project_Name",title:"Project_Name"},
                                             { data: "approver.Name",title:"Approver", editfield:"request.Approver"},
                                             { data: "requeststatuses.Request_status",title:"Request Status",
                                                "render": function( data, type, full, meta ) {
                                                        if(full.requeststatuses.Request_status.includes("Approved"))
                                                         {
                                                           return "<span class='green'>"+full.requeststatuses.Request_status+"</span>";
                                                         }
                                                         else if(full.requeststatuses.Request_status.includes("Rejected"))
                                                         {
                                                           return "<span class='red'>"+full.requeststatuses.Request_status+"</span>";
                                                         }
                                                         else if(full.requeststatuses.Request_status.includes("Cancelled"))
                                                         {
                                                           return "<span class='red'>"+full.requeststatuses.Request_status+"</span>";
                                                         }
                                                         else {
                                                           return "<span class='yellow'>"+full.requeststatuses.Request_status+"</span>";
                                                         }


                                                }

                                             },
                                             { data: "requeststatuses.updated_at",title:"updated_at"},
                                             { data: "requeststatuses.Comment",title:"Comment"}
                                         // { data: "requeststatuses.Id"},
                                         // { data: "request.Id"},
                                         // { data: "requeststatuses.Request_Status",title:"Request_Status",
                                         //   "render": function ( data, type, full, meta ) {
                                         //
                                         //        if(full.requeststatuses.Request_Status.includes("Approved"))
                                         //        {
                                         //          return "<span class='green'>"+full.requeststatuses.Request_Status+"</span>";
                                         //        }
                                         //        else if(full.requeststatuses.Request_Status.includes("Rejected"))
                                         //        {
                                         //          return "<span class='red'>"+full.requeststatuses.Request_Status+"</span>";
                                         //        }
                                         //        else if(full.requeststatuses.Request_Status.includes("Cancelled"))
                                         //        {
                                         //          return "<span class='red'>"+full.requeststatuses.Request_Status+"</span>";
                                         //        }
                                         //        else {
                                         //          return "<span class='yellow'>"+full.requeststatuses.Request_Status+"</span>";
                                         //        }
                                         //
                                         //     }
                                         // },
                                         // { data: "applicant.StaffId",title:"Staff_ID"},
                                         // { data: "applicant.Name",title:"Name"},
                                         // { data: "requests.Request_Type",title:"Request_Type" },
                                         // { data: "requests.Request_Term",title:"Request_Term" },
                                         // { data: "requests.Start_Date",title:"Start_Date"},
                                         // { data: "requests.End_Date",title:"End_Date"},
                                         // { data: "requests.No_of_Days",title:"No_of_Days"},
                                         // { data: "requests.Reason",title:"Reason"},
                                         // { data: "requests.created_at",title:"Application_Date"},
                                         // { data: "projects.Project_Name",title:"Project_Name"},
                                         // { data: "approver.Name",title:"Approver"},
                                         // { data: "requeststatuses.Comment",title:"Comment"},
                                         // { data: "requeststatuses.updated_at",title:"Review_Date"},
                                         // { data: "files.Web_Path",
                                         //    render: function ( url, type, row ) {
                                         //         if (url)
                                         //         {
                                         //           return '<a href="{{ url("/") }}'+url+'" target="_blank">Download</a>';
                                         //         }
                                         //         else {
                                         //           return ' - ';
                                         //         }
                                         //     },
                                         //     title: "File"
                                         //   }
                                 ],
                                 autoFill: {
                                    editor:  requestseditor
                                    // columns: [ 9,10,11]
                                },
                                // keys: {
                                //     columns: ':not(:first-child)',
                                //     editor:  requestseditor
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

                     requesttable2=$('#requesttable2').dataTable( {
                           ajax: {
                              "url": "{{ asset('/Include/requestapproval.php') }}",
                              "data": {
                                  "UserId": {{ $me->UserId }},
                                  "Request_status": "%Approved%"
                              }
                            },
                             columnDefs: [{ "visible": false, "targets": [1,2,5] },{"className": "dt-left", "targets": "_all"}],
                             responsive: false,
                             colReorder: false,
                             dom: "Bfrtp",
                             sScrollX: "100%",
                             bAutoWidth: true,
                             sScrollY: "100%",
                             iDisplayLength:10,
                             scrollCollapse: true,
                             fnInitComplete: function(oSettings, json) {

                               $('#approvedrequesttab').html("Approved Request" + "[" + requesttable2.api().rows().count() +"]")

                              },
                             rowId:"requeststatuses.Id",
                             columns: [
                                     {
                                       sortable: false,
                                       "render": function ( data, type, full, meta ) {
                                         return '<input type="checkbox" name="selectrow1" id="selectrow1" class="selectrow1" value="'+full.request.Id+'" onclick="uncheck(1)">';

                                       }

                                     },
                                     { data: "request.Id",title:"Id"},
                                     { data: "requeststatuses.Id",title:"Id"},
                                     { data: "applicant.Name",title:"Applicant"},
                                     { data: "applicant.Department",title:"Department"},
                                     { data: "request.Approver",title:"Approver"},
                                     { data: "request.Request_type",title:"Request_type",editfield:"request.Request_type"},
                                     { data: "request.Others",title:"Others"},
                                     { data: "request.Start_Date",title:"Start_Date"},
                                     { data: "request.End_Date",title:"End_Date"},
                                     { data: "request.Remarks",title:"Remarks"},
                                     { data: "request.created_at",title:"created_at"},
                                     { data: "projects.Project_Name",title:"Project_Name"},
                                     { data: "approver.Name",title:"Approver", editfield:"request.Approver"},
                                     { data: "requeststatuses.Request_status",title:"Request Status",
                                        "render": function( data, type, full, meta ) {
                                                if(full.requeststatuses.Request_status.includes("Approved"))
                                                 {
                                                   return "<span class='green'>"+full.requeststatuses.Request_status+"</span>";
                                                 }
                                                 else if(full.requeststatuses.Request_status.includes("Rejected"))
                                                 {
                                                   return "<span class='red'>"+full.requeststatuses.Request_status+"</span>";
                                                 }
                                                 else if(full.requeststatuses.Request_status.includes("Cancelled"))
                                                 {
                                                   return "<span class='red'>"+full.requeststatuses.Request_status+"</span>";
                                                 }
                                                 else {
                                                   return "<span class='yellow'>"+full.requeststatuses.Request_status+"</span>";
                                                 }


                                        }

                                     },
                                     { data: "requeststatuses.updated_at",title:"updated_at"},
                                     { data: "requeststatuses.Comment",title:"Comment"}
                                    // { data: "requeststatuses.Id"},
                                    // { data: "requests.Id"},
                                    // { data: "requeststatuses.Request_Status",title:"Request_Status",
                                    //   "render": function ( data, type, full, meta ) {
                                    //
                                    //        if(full.requeststatuses.Request_Status.includes("Approved"))
                                    //        {
                                    //          return "<span class='green'>"+full.requeststatuses.Request_Status+"</span>";
                                    //        }
                                    //        else if(full.requeststatuses.Request_Status.includes("Rejected"))
                                    //        {
                                    //          return "<span class='red'>"+full.requeststatuses.Request_Status+"</span>";
                                    //        }
                                    //        else if(full.requeststatuses.Request_Status.includes("Cancelled"))
                                    //        {
                                    //          return "<span class='red'>"+full.requeststatuses.Request_Status+"</span>";
                                    //        }
                                    //        else {
                                    //          return "<span class='yellow'>"+full.requeststatuses.Request_Status+"</span>";
                                    //        }
                                    //
                                    //     }
                                    // },
                                    // { data: "applicant.StaffId",title:"Staff_ID"},
                                    // { data: "applicant.Name",title:"Name"},
                                    // { data: "requests.Request_Type",title:"Request_Type" },
                                    // { data: "requests.Request_Term",title:"Request_Term" },
                                    // { data: "requests.Start_Date",title:"Start_Date"},
                                    // { data: "requests.End_Date",title:"End_Date"},
                                    // { data: "requests.No_of_Days",title:"No_of_Days"},
                                    // { data: "requests.Reason",title:"Reason"},
                                    // { data: "requests.created_at",title:"Application_Date"},
                                    // { data: "projects.Project_Name",title:"Project_Name"},
                                    // { data: "approver.Name",title:"Approver"},
                                    // { data: "requeststatuses.Comment",title:"Comment"},
                                    // { data: "requeststatuses.updated_at",title:"Review_Date"},
                                    // { data: "files.Web_Path",
                                    //    render: function ( url, type, row ) {
                                    //         if (url)
                                    //         {
                                    //           return '<a href="{{ url("/") }}'+url+'" target="_blank">Download</a>';
                                    //         }
                                    //         else {
                                    //           return ' - ';
                                    //         }
                                    //     },
                                    //     title: "File"
                                    //   }
                             ],
                             autoFill: {
                                editor:  requests2editor
                                // columns: [ 9,10,11]
                            },
                            // keys: {
                            //     columns: ':not(:first-child)',
                            //     editor:  requests2editor
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

                 requesttable3=$('#requesttable3').dataTable( {
                       ajax: {
                          "url": "{{ asset('/Include/requestapproval.php') }}",
                          "data": {
                              "UserId": {{ $me->UserId }},
                              "Request_status": "%Rejected%"
                          }
                        },
                        columnDefs: [{ "visible": false, "targets": [1,2,5] },{"className": "dt-left", "targets": "_all"}],
                        responsive: false,
                        colReorder: false,
                        dom: "Bfrtp",
                        sScrollX: "100%",
                        bAutoWidth: true,
                        sScrollY: "100%",
                        iDisplayLength:10,
                        scrollCollapse: true,
                        fnInitComplete: function(oSettings, json) {

                          $('#rejectedrequesttab').html("Rejected Request" + "[" + requesttable3.api().rows().count() +"]")

                         },
                        rowId:"requeststatuses.Id",
                         columns: [
                                   {
                                     sortable: false,
                                     "render": function ( data, type, full, meta ) {
                                       return '<input type="checkbox" name="selectrow2" id="selectrow2" class="selectrow2" value="'+full.request.Id+'" onclick="uncheck(2)">';

                                     }

                                   },
                                   { data: "request.Id",title:"Id"},
                                   { data: "requeststatuses.Id",title:"Id"},
                                   { data: "applicant.Name",title:"Applicant"},
                                   { data: "applicant.Department",title:"Department"},
                                   { data: "request.Approver",title:"Approver"},
                                   { data: "request.Request_type",title:"Request_type",editfield:"request.Request_type"},
                                   { data: "request.Others",title:"Others"},
                                   { data: "request.Start_Date",title:"Start_Date"},
                                   { data: "request.End_Date",title:"End_Date"},
                                   { data: "request.Remarks",title:"Remarks"},
                                   { data: "request.created_at",title:"created_at"},
                                   { data: "projects.Project_Name",title:"Project_Name"},
                                   { data: "approver.Name",title:"Approver", editfield:"request.Approver"},
                                   { data: "requeststatuses.Request_status",title:"Request Status",
                                      "render": function( data, type, full, meta ) {
                                              if(full.requeststatuses.Request_status.includes("Approved"))
                                               {
                                                 return "<span class='green'>"+full.requeststatuses.Request_status+"</span>";
                                               }
                                               else if(full.requeststatuses.Request_status.includes("Rejected"))
                                               {
                                                 return "<span class='red'>"+full.requeststatuses.Request_status+"</span>";
                                               }
                                               else if(full.requeststatuses.Request_status.includes("Cancelled"))
                                               {
                                                 return "<span class='red'>"+full.requeststatuses.Request_status+"</span>";
                                               }
                                               else {
                                                 return "<span class='yellow'>"+full.requeststatuses.Request_status+"</span>";
                                               }


                                      }

                                   },
                                   { data: "requeststatuses.updated_at",title:"updated_at"},
                                   { data: "requeststatuses.Comment",title:"Comment"}
                                  // { data: "requeststatuses.Id"},
                                  // { data: "requests.Id"},
                                  // { data: "requeststatuses.Request_Status",title:"Request_Status",
                                  //   "render": function ( data, type, full, meta ) {
                                  //
                                  //        if(full.requeststatuses.Request_Status.includes("Approved"))
                                  //        {
                                  //          return "<span class='green'>"+full.requeststatuses.Request_Status+"</span>";
                                  //        }
                                  //        else if(full.requeststatuses.Request_Status.includes("Rejected"))
                                  //        {
                                  //          return "<span class='red'>"+full.requeststatuses.Request_Status+"</span>";
                                  //        }
                                  //        else if(full.requeststatuses.Request_Status.includes("Cancelled"))
                                  //        {
                                  //          return "<span class='red'>"+full.requeststatuses.Request_Status+"</span>";
                                  //        }
                                  //        else {
                                  //          return "<span class='yellow'>"+full.requeststatuses.Request_Status+"</span>";
                                  //        }
                                  //
                                  //     }
                                  // },
                                  // { data: "applicant.StaffId",title:"Staff_ID"},
                                  // { data: "applicant.Name",title:"Name"},
                                  // { data: "requests.Request_Type",title:"Request_Type" },
                                  // { data: "requests.Request_Term",title:"Request_Term" },
                                  // { data: "requests.Start_Date",title:"Start_Date"},
                                  // { data: "requests.End_Date",title:"End_Date"},
                                  // { data: "requests.No_of_Days",title:"No_of_Days"},
                                  // { data: "requests.Reason",title:"Reason"},
                                  // { data: "requests.created_at",title:"Application_Date"},
                                  // { data: "projects.Project_Name",title:"Project_Name"},
                                  // { data: "approver.Name",title:"Approver"},
                                  // { data: "requeststatuses.Comment",title:"Comment"},
                                  // { data: "requeststatuses.updated_at",title:"Review_Date"},
                                  // { data: "files.Web_Path",
                                  //    render: function ( url, type, row ) {
                                  //         if (url)
                                  //         {
                                  //           return '<a href="{{ url("/") }}'+url+'" target="_blank">Download</a>';
                                  //         }
                                  //         else {
                                  //           return ' - ';
                                  //         }
                                  //     },
                                  //     title: "File"
                                  //   }
                         ],
                         autoFill: {
                            editor:  requests3editor
                            // columns: [ 9,10,11]
                        },
                        // keys: {
                        //     columns: ':not(:first-child)',
                        //     editor:  requests3editor
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
                   $('#requesttable').on( 'click', 'tbody td', function (e) {
                         requestseditor.inline( this, {
                        onBlur: 'submit'
                       } );
                   } );


             // Activate an inline edit on click of a table cell
                   $('#requesttable2').on( 'click', 'tbody td', function (e) {
                         requests2editor.inline( this, {
                        onBlur: 'submit'
                       } );
                   } );

             // Activate an inline edit on click of a table cell
                   $('#requesttable3').on( 'click', 'tbody td', function (e) {
                         requests3editor.inline( this, {
                        onBlur: 'submit'
                       } );
                   } );

                  $("#ajaxloader").hide();
                  $("#ajaxloader2").hide();
                  $("#ajaxloader3").hide();


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

                  $(".requesttable thead input").keyup ( function () {

                          /* Filter on the column (the index) of this element */
                          if ($('#requesttable').length > 0)
                          {

                              var colnum=document.getElementById('requesttable').rows[0].cells.length;

                              if (this.value=="[empty]")
                              {

                                 requesttable.fnFilter( '^$', this.name,true,false );
                              }
                              else if (this.value=="[nonempty]")
                              {

                                 requesttable.fnFilter( '^(?=\\s*\\S).*$', this.name,true,false );
                              }
                              else if (this.value.startsWith("!")==true && this.value.length>1)
                              {

                                 requesttable.fnFilter( '^(?'+ this.value +').*', this.name,true,false );
                              }
                              else if (this.value.startsWith("!")==false)
                              {

                                  requesttable.fnFilter( this.value, this.name,true,false );
                              }
                          }



                  } );

                  $(".requesttable2 thead input").keyup ( function () {

                          /* Filter on the column (the index) of this element */
                          if ($('#requesttable2').length > 0)
                          {

                              var colnum=document.getElementById('requesttable2').rows[0].cells.length;

                              if (this.value=="[empty]")
                              {

                                 requesttable2.fnFilter( '^$', this.name,true,false );
                              }
                              else if (this.value=="[nonempty]")
                              {

                                 requesttable2.fnFilter( '^(?=\\s*\\S).*$', this.name,true,false );
                              }
                              else if (this.value.startsWith("!")==true && this.value.length>1)
                              {

                                 requesttable2.fnFilter( '^(?'+ this.value +').*', this.name,true,false );
                              }
                              else if (this.value.startsWith("!")==false)
                              {

                                  requesttable2.fnFilter( this.value, this.name,true,false );
                              }
                          }



                  } );

                  $(".requesttable3 thead input").keyup ( function () {

                          /* Filter on the column (the index) of this element */
                          if ($('#requesttable3').length > 0)
                          {

                              var colnum=document.getElementById('requesttable3').rows[0].cells.length;

                              if (this.value=="[empty]")
                              {

                                 requesttable3.fnFilter( '^$', this.name,true,false );
                              }
                              else if (this.value=="[nonempty]")
                              {

                                 requesttable3.fnFilter( '^(?=\\s*\\S).*$', this.name,true,false );
                              }
                              else if (this.value.startsWith("!")==true && this.value.length>1)
                              {

                                 requesttable3.fnFilter( '^(?'+ this.value +').*', this.name,true,false );
                              }
                              else if (this.value.startsWith("!")==false)
                              {

                                  requesttable3.fnFilter( this.value, this.name,true,false );
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
        Request Management
        <small>Resource Management</small>
      </h1>
      <ol class="breadcrumb">
        <li><a href="{{ url('/home') }}"><i class="fa fa-dashboard"></i> Home</a></li>
        <li><a href="#">Management Tool</a></li>
        <li><a href="#">HR Management</a></li>
        <li class="active">Request Management</li>
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

        <div class="modal fade" id="Cancel" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
          <div class="modal-dialog" role="document">
            <div class="modal-content">
              <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title" id="myModalLabel">Cancel Request</h4>
              </div>
              <div class="modal-body">
                <div class="form-group" id="cancelrequest">

                </div>
                  Are you sure you wish to cancel this request?
              </div>
              <div class="modal-footer">
                <center><img src="{{ URL::to('/') ."/img/ajax-loader.gif" }}" width="50px" height="50px" alt="Loading" name='ajaxloader3' id="ajaxloader3"></center>
                <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                <button type="button" class="btn btn-primary" onclick="cancelrequest()">Cancel Request</button>
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
                <div class="form-group" id="redirectrequeststatus">

                </div>
                <div class="form-group">

                    <label>Approver : </label>

                    <select class="form-control select2" id="NewApprover" name="NewApprover" style="width: 100%;">
                      <option></option>

                      @foreach ($approver as $user)

                          <option  value="{{$user->Id}}">{{$user->Name}}</option>


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
                  Are you sure you wish to submit the selected request for next action?
              </div>
              <div class="modal-footer">
                <center><img src="{{ URL::to('/') ."/img/ajax-loader.gif" }}" width="50px" height="50px" alt="Loading" name='ajaxloader' id="ajaxloader"></center>
                <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                <button type="button" class="btn btn-primary" onclick="submit()">Yes</button>
              </div>
            </div>
          </div>
        </div>

        {{-- <div class="col-md-6">
            <div class="box box-solid">
                <div class="box-header with-border">
                    <div id="calendar"></div>
                </div>
            </div>
        </div> --}}
      </div>

      <div class="row">
        <div class="col-md-12">
          <div class="nav-tabs-custom">
            <ul class="nav nav-tabs">

              <li class="active"><a href="#pendingapproval" data-toggle="tab" id="pendingapprovaltab">Pending Approval Request</a></li>
              <li><a href="#approvedrequest" data-toggle="tab" id="approvedrequesttab">Approved Request</a></li>
              <li><a href="#rejectedrequest" data-toggle="tab" id="rejectedrequesttab">Rejected Request</a></li>
              {{--@if($me->View_All_Request)--}}
                <li><a href="#allrequest" data-toggle="tab" id="allrequesttab">All Request</a></li>
                <li><a href="#finalrequest" data-toggle="tab" id="finalrequesttab">Final Approved Request</a></li>
              {{--@endif--}}
            </ul>

            <div class="tab-content">

              <div class="tab-pane" id="allrequest">

                <!-- <div class="row">
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
              </div> -->

                <table id="alltable" class="alltable" cellspacing="0" width="100%" padding="30px" style="font-size: 13px;">
                    <thead>
                        {{-- prepare header search textbox --}}
                        @if($allrequest)
                          <tr class="search">

                            @foreach($allrequest as $key=>$value)

                              @if ($key==0)
                                <?php $i = 0; ?>

                                @foreach($value as $field=>$a)
                                    @if ($i==0|| $i==1 ||$i==2)
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
                          @foreach($allrequest as $key=>$value)

                            @if ($key==0)
                              <td><input type="checkbox" name="selectall" id="selectall" value="all" onclick="checkall(0)"></td>
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
                      @foreach($allrequest as $allrequests)

                          <tr id="row_{{ $i }}">
                              <td></td>
                              <td></td>
                              @foreach($allrequests as $key=>$value)
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
                {{-- <button type="button" class="btn btn-primary btn-lg" id="submitbtn" data-toggle="modal" data-target="#Submit">Submit and Notify</button> --}}

                {{-- <button type="button" class="btn btn-danger btn-lg" id="recallbtn"  data-toggle="modal" data-target="#Recall">Recall</button> --}}

                <button type="button" class="btn btn-success btn" data-toggle="modal" onclick="approve()">Approve</button>

                <button type="button" class="btn btn-danger btn" data-toggle="modal" onclick="reject()">Reject</button>
                <button type="button" class="btn btn-warning btn" data-toggle="modal" data-target="#Redirect">Redirect</button>

                <br><br>

                <table id="requesttable" class="requesttable" cellspacing="0" width="100%" padding="30px" style="font-size: 13px;">
                    <thead>
                        {{-- prepare header search textbox --}}
                        @if($request)
                          <tr class="search">

                            @foreach($request as $key=>$value)

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
                          @foreach($request as $key=>$value)

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
                      @foreach($request as $requests)

                        @if($requests->Status=="Pending Approval")

                          <tr id="row_{{ $i }}">
                              <td></td>

                              @foreach($requests as $key=>$value)
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

              <div class="tab-pane" id="approvedrequest">
                {{-- <button type="button" class="btn btn-primary btn-lg" id="submitbtn" data-toggle="modal" data-target="#Submit">Submit and Notify</button> --}}

                {{-- <button type="button" class="btn btn-danger btn-lg" id="recallbtn"  data-toggle="modal" data-target="#Recall">Recall</button> --}}

                <button type="button" class="btn btn-success btn" data-toggle="modal" onclick="approve3()">Approve</button>

                <button type="button" class="btn btn-danger btn" data-toggle="modal" onclick="reject2()">Reject</button>
                <button type="button" class="btn btn-warning btn" data-toggle="modal" data-target="#Redirect">Redirect</button>
                <br><br>

                <table id="requesttable2" class="requesttable2" cellspacing="0" width="100%" padding="30px" style="font-size: 13px;">
                    <thead>
                        {{-- prepare header search textbox --}}
                        @if($request)
                          <tr class="search">

                            @foreach($request as $key=>$value)

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
                          @foreach($request as $key=>$value)

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
                      @foreach($request as $requests)

                        @if(strpos($requests->Status,"Approved")!==false)

                          <tr id="row_{{ $i }}">
                              <td></td>
                              @foreach($requests as $key=>$value)
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

              <div class="tab-pane" id="finalrequest">

                <table id="finaltable" class="finaltable" cellspacing="0" width="100%" padding="30px" style="font-size: 13px;">
                    <thead>
                        {{-- prepare header search textbox --}}
                        @if($finalapprovedrequest)
                          <tr class="search">

                            @foreach($finalapprovedrequest as $key=>$value)

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
                          @foreach($finalapprovedrequest as $key=>$value)

                            @if ($key==0)
                              <td><input type="checkbox" name="selectall1" id="selectall1" value="all" onclick="checkall(1)"></td>
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
                      @foreach($finalapprovedrequest as $finalapprovedrequests)

                          <tr id="row_{{ $i }}">
                              <td></td>
                              @foreach($finalapprovedrequests as $key=>$value)
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

              <div class="tab-pane" id="rejectedrequest">
                <table id="requesttable3" class="requesttable3" cellspacing="0" width="100%" padding="30px" style="font-size: 13px;">
                    <thead>
                        {{-- prepare header search textbox --}}
                        @if($request)
                          <tr class="search">

                            @foreach($request as $key=>$value)

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
                          @foreach($request as $key=>$value)

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
                      @foreach($request as $requests)

                        @if(strpos($requests->Status,"Rejected")!==false)

                          <tr id="row_{{ $i }}">
                              <td></td>
                              @foreach($requests as $key=>$value)
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
              requesttable.api().rows().select();
         }else if (index==1)
         {
              requesttable2.api().rows().select();
         }else if (index==2)
          {
              requesttable3.api().rows().select();
          }

    } else {

        $(".selectrow"+index).prop("checked", false);
        $(".selectrow"+index).trigger("change");
         //requesttable.rows().deselect();
         if (index==0)
         {
              requesttable.api().rows().deselect();
         }else if (index==1) {
              requesttable2.api().rows().deselect();
         }else if (index==2)
          {
              requesttable3.api().rows().deselect();
          }

    }
  }

  function OpenRedirectDialog(id,currentapprover)
  {
    var hiddeninput='<input type="hidden" class="form-control" id="selectedrequeststatusid" name="selectedrequeststatusid" value="'+id+'">';

      $("#NewApprover").val(currentapprover).change();

      $( "#redirectrequeststatus" ).html(hiddeninput);
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
                  url: "{{ url('/requestmanagement/submit') }}",
                  method: "POST",
                  data: {RequestIds:ids},

                  success: function(response){

                    if (response==1)
                    {
                      requesttable.ajax.reload();
                      requesttable2.ajax.reload();
                      requesttable3.ajax.reload();

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

    var errormessage="No request selected!";
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
                  url: "{{ url('/requestmanagement/redirect') }}",
                  method: "POST",
                  data: {Ids:ids,Approver:newapprover},

                  success: function(response){

                    if (response==1)
                    {
                        requesttable.api().ajax.url("{{ asset('/Include/requestapproval.php') }}").load();

                        $('#Redirect').modal('hide');

                        var message="Request approval redirected!";
                        $("#update-alert ul").html(message);
                        $("#update-alert").modal("show");

                        $("#ajaxloader").hide();

                    }
                    else {

                      $('#Redirect').modal('hide');

                      var errormessage="Failed to redirect request approval!";
                      $("#error-alert ul").html(errormessage);
                      $("#error-alert").modal("show");

                      $("#ajaxloader").hide();

                    }

          }
      });


      }
      else {

        $('#Redirect').modal('hide');

        var errormessage="No request selected!";
        $("#error-alert ul").html(errormessage);
        $("#error-alert").modal("show");



        $("#ajaxloader").hide();
      }
    }

    function refresh()
    {
      var d=$('#range').val();
      var arr = d.split(" - ");

      window.location.href ="{{ url("/requestmanagement") }}/"+arr[0]+"/"+arr[1];

    }

  function reject() {

      var boxes = $('input[type="checkbox"]:checked', requesttable.fnGetNodes() );
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
                    url: "{{ url('/request/approve') }}",
                    method: "POST",
                    data: {Ids:ids,
                    Status:status},

                    success: function(response){

                      if (response==1)
                      {

                          requesttable.api().ajax.url("{{ asset('/Include/requestapproval.php') }}").load();
                          requesttable2.api().ajax.reload();
                          requesttable3.api().ajax.reload();
                          alltable.api().ajax.reload();
                          finaltable.api().ajax.reload();

                          var message="Request Rejected!";
                          $("#update-alert ul").html(message);
                          $("#update-alert").modal('show');

                         $("#ajaxloader").hide();

                      }
                      else {

                        var errormessage="Failed to reject request!";
                        $("#error-alert ul").html(errormessage);
                        $("#error-alert").modal('show');

                        $("#ajaxloader").hide();

                      }

            }
        });

    }
    else {
      var errormessage="No request selected!";
      $("#error-alert ul").html(errormessage);
      $("#error-alert").modal('show');

    }

  }

  function reject2() {

      var boxes = $('input[type="checkbox"]:checked', requesttable2.fnGetNodes() );
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
                    url: "{{ url('/request/approve') }}",
                    method: "POST",
                    data: {Ids:ids,
                    Status:status},

                    success: function(response){

                      if (response==1)
                      {

                          requesttable.api().ajax.url("{{ asset('/Include/requestapproval.php') }}").load();
                          requesttable2.api().ajax.reload();
                          requesttable3.api().ajax.reload();
                          alltable.api().ajax.reload();
                          finaltable.api().ajax.reload();

                          var message="Request Rejected!";
                          $("#update-alert ul").html(message);
                          $("#update-alert").modal('show');


                         $("#ajaxloader").hide();

                      }
                      else {

                        var errormessage="Failed to reject request!";
                        $("#error-alert ul").html(errormessage);
                        $("#error-alert").modal('show');

                        $("#ajaxloader").hide();

                      }

            }
        });

    }
    else {
      var errormessage="No request selected!";
      $("#error-alert ul").html(errormessage);
      $("#error-alert").modal('show');

    }

  }

  function approve2() {

    var boxes = $('input[type="checkbox"]:checked', requesttable.fnGetNodes() );
    var ids="";

    if (boxes.length>0)
    {
      for (var i = 0; i < boxes.length; i++) {
        ids+=boxes[i].value+",";
      }

      ids=ids.substring(0, ids.length-1);

      status="Not Approve";

      $.ajaxSetup({
         headers: { 'X-CSRF-Token' : $('meta[name=_token]').attr('content') }
      });

        $("#ajaxloader").show();

      $.ajax({
                  url: "{{ url('/request/approve') }}",
                  method: "POST",
                  data: {Ids:ids,
                  Status:status},

                  success: function(response){

                    if (response==1)
                    {

                        requesttable.api().ajax.url("{{ asset('/Include/requestapproval.php') }}").load();
                        requesttable2.api().ajax.reload();
                        requesttable3.api().ajax.reload();
                        alltable.api().ajax.reload();
                        finaltable.api().ajax.reload();

                        var message="Request Approved!";
                        $("#update-alert ul").html(message);
                        $("#update-alert").modal('show');

                       $("#ajaxloader").hide();

                    }
                    else {

                      var errormessage="Failed to approve request!";
                      $("#error-alert ul").html(errormessage);
                      $("#error-alert").modal('show');

                      $("#ajaxloader").hide();

                    }

          }
      });

    }
    else {
      var errormessage="No request selected!";
      $("#error-alert ul").html(errormessage);
      $("#error-alert").modal('show');

    }

  }

  function approve4() {

    var boxes = $('input[type="checkbox"]:checked', requesttable2.fnGetNodes() );
    var ids="";

    if (boxes.length>0)
    {
      for (var i = 0; i < boxes.length; i++) {
        ids+=boxes[i].value+",";
      }

      ids=ids.substring(0, ids.length-1);

      status="Not Approve";

      $.ajaxSetup({
         headers: { 'X-CSRF-Token' : $('meta[name=_token]').attr('content') }
      });

        $("#ajaxloader").show();

      $.ajax({
                  url: "{{ url('/request/approve') }}",
                  method: "POST",
                  data: {Ids:ids,
                  Status:status},

                  success: function(response){

                    if (response==1)
                    {

                        requesttable2.api().ajax.url("{{ asset('/Include/requestapproval.php') }}").load();
                        requesttable2.api().ajax.reload();
                        requesttable3.api().ajax.reload();
                        alltable.api().ajax.reload();
                        finaltable.api().ajax.reload();

                        var message="Request Approved!";
                        $("#update-alert ul").html(message);
                        $("#update-alert").modal('show');


                       $("#ajaxloader").hide();

                    }
                    else {

                      var errormessage="Failed to approve request!";
                      $("#error-alert ul").html(errormessage);
                      $("#error-alert").modal('show');

                      $("#ajaxloader").hide();

                    }

          }
      });

    }
    else {
      var errormessage="No request selected!";
      $("#error-alert ul").html(errormessage);
      $("#error-alert").modal('show');

    }

  }

  //Firdaus 19/07/2018
  function approve() {

    var boxes = $('input[type="checkbox"]:checked', requesttable.fnGetNodes() );
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
                  url: "{{ url('/request/approve') }}",
                  method: "POST",
                  data: {Ids:ids,
                  Status:status},

                  success: function(response){

                    if (response==1)
                    {

                        requesttable.api().ajax.url("{{ asset('/Include/requestapproval.php') }}").load();

                        var message="Request Approved!";
                        $("#update-alert ul").html(message);
                        $("#update-alert").modal('show');

                        $("#ajaxloader").hide();

                    }
                    else {

                      var errormessage="Failed to approve request!";
                      $("#error-alert ul").html(errormessage);
                      $("#error-alert").modal('show');

                      $("#ajaxloader").hide();

                    }

          }
      });

    }
    else {
      var errormessage="No Request selected!";
      $("#error-alert ul").html(errormessage);
      $("#error-alert").modal('show');


    }

  }

  function approve3() {

    var boxes = $('input[type="checkbox"]:checked', requesttable2.fnGetNodes() );
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
                  url: "{{ url('/request/approve') }}",
                  method: "POST",
                  data: {Ids:ids,
                  Status:status},

                  success: function(response){

                    if (response==1)
                    {

                        requesttable2.api().ajax.url("{{ asset('/Include/requestapproval.php') }}").load();

                        var message="Request Approved!";
                        $("#update-alert ul").html(message);
                        $("#update-alert").modal('show');


                        $("#ajaxloader").hide();

                    }
                    else {

                      var errormessage="Failed to approve request!";
                      $("#error-alert ul").html(errormessage);
                      $("#error-alert").modal('show');


                      $("#ajaxloader").hide();

                    }

          }
      });

    }
    else {
      var errormessage="No Request selected!";
      $("#error-alert ul").html(errormessage);
      $("#error-alert").modal('show');


    }

  }

  function CancelDialog(id)
  {

    var hiddeninput='<input type="hidden" class="form-control" id="cancelrequestid" name="cancelrequestid" value="'+id+'">';

      $( "#cancelrequest" ).html(hiddeninput);
      $('#Cancel').modal('show');


  }

  function cancelrequest() {

      $.ajaxSetup({
         headers: { 'X-CSRF-Token' : $('meta[name=_token]').attr('content') }
      });

      $("#ajaxloader3").show();

      requestid=$('[name="cancelrequestid"]').val();

      $.ajax({
                  url: "{{ url('/myrequest/cancel') }}",
                  method: "POST",
                  data: {Id:requestid},

                  success: function(response){

                    if (response==1)
                    {
                        alltable.api().ajax.reload();

                        var message="Request cancelled!";
                        $("#update-alert ul").html(message);
                        $("#update-alert").modal('show');

                        $('#Cancel').modal('hide');

                        $("#ajaxloader3").hide();

                    }
                    else {

                      var errormessage="Failed to cancel request!";
                      $("#error-alert ul").html(errormessage);
                      $("#error-alert").modal('show');


                      $('#Cancel').modal('hide');

                      $("#ajaxloader3").hide();

                    }

          }
      });

  }

</script>

@endsection
