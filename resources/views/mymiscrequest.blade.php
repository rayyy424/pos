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

          var requestseditor;
          var requests2editor;
          var requests3editor;
          var requesttable;
          var request2table;
          var request3table;
          var request4table;
          var asInitVals = new Array();
          var requestid;

          var selectedrequestid;

          $(document).ready(function() {
                         requestseditor = new $.fn.dataTable.Editor( {
                                ajax: "{{ asset('/Include/request.php') }}",
                                 table: "#requesttable",
                                 idSrc: "request.Id",
                                 fields: [
                                           // {
                                           //          label: "Approver:",
                                           //          name: "request.Approver",
                                           //  },
                                            {
                                                 label: "Request Type:",
                                                 name: "requests.request_Type",
                                                 type:  'select',
                                                 options: [
                                                     { label :"", value: "" },
                                                     @foreach($options as $option)
                                                       @if ($option->Field=="Request_Type")
                                                         { label :"{{$option->Option}}", value: "{{$option->Option}}" },
                                                       @endif
                                                     @endforeach
                                                 ],
                                            },
                                            {
                                                     label: "Others:",
                                                     name: "request.Others"
                                             },
                                            {
                                                       label: "Remarks:",
                                                       name: "request.Remarks",
                                             },
                                             {
                                                        label: "created_at:",
                                                        name: "request.created_at",
                                              }, {
                                                      label: "Start Date:",
                                                      name: "request.Start_Date",
                                                      type:   'datetime',
                                                      def:    function () { return new Date(); },
                                                      format: 'DD-MMM-YYYY'
                                              }, {
                                                      label: "End Date:",
                                                      name: "request.End_Date",
                                                      type:   'datetime',
                                                      def:    function () { return new Date(); },
                                                      format: 'DD-MMM-YYYY'
                                              },
                                            {
                                                 label: "Approver:",
                                                 name: "request.Approver_Id",
                                                 type:  'select',
                                                 options: [
                                                     { label :"", value: "" },
                                                     @foreach($approver as $approvers)

                                                         { label :"{{$approvers->Name}}", value: "{{$approvers->Id}}" },

                                                     @endforeach
                                                 ],
                                               },
                                               // {
                                               //      label: "Request_status:",
                                               //      name: "request.Request_status",
                                               //      type:  'select',
                                               //      options: [
                                               //          { label :"Approve", value: "" },
                                               //          { label :"", value: "" },
                                               //          { label :"", value: "" },
                                               //          { label :"", value: "" },
                                               //          { label :"", value: "" },
                                               //
                                               //      ],
                                               //  },
                                                {
                                                           label: "updated_at:",
                                                           name: "request.updated_at",
                                                 },
                                                 {
                                                            label: "Comment:",
                                                            name: "request.Comment",
                                                  },
                                         //{
                                        //          label: "request Term:",
                                        //          name: "requests.request_Term",
                                        //          type:  "radio",
                                        //          options: [
                                        //                { label :"Full Day", value: "Full Day" },
                                        //                { label :"Half Day Morning", value: "Half Day Morning" },
                                        //                { label :"Half Day Afternoon", value: "Half Day Afternoon" },
                                        //          ],
                                        //  }, {
                                        //          label: "Start Date:",
                                        //          name: "requests.Start_Date",
                                        //          type:   'datetime',
                                        //          def:    function () { return new Date(); },
                                        //          format: 'DD-MMM-YYYY'
                                        //  }, {
                                        //          label: "End Date:",
                                        //          name: "requests.End_Date",
                                        //          type:   'datetime',
                                        //          def:    function () { return new Date(); },
                                        //          format: 'DD-MMM-YYYY'
                                        //  }, {
                                        //          label: "No of Days:",
                                        //          name: "requests.No_of_Days"
                                        //  }, {
                                        //          label: "Reason:",
                                        //          name: "requests.Reason",
                                        //          type:  "textarea"
                                        //  }, {
                                        //          label: "File:",
                                        //          name: "files.Web_Path",
                                        //          type: "upload",
                                        //          ajaxData: function ( d ) {
                                        //            d.append( 'Id', requestid ); // edit - use `d`
                                        //          },
                                        //          display: function ( file_id ) {
                                        //            return '<img src="'+ requesttable.row( requestseditor.modifier() ).data().files.Web_Path +'">';
                                        //          },
                                        //          clearText: "Clear",
                                        //          noImageText: 'No file'
                                        //  }

                                 ]
                         } );

                         $('#requesttable').on( 'click', 'tr', function () {
                           // Get the rows id value
                          //  var row=$(this).closest("tr");
                          //  var oTable = row.closest('table').dataTable();
                           requestid = requesttable.row( this ).data().request.Id;
                         });

                         requests2editor = new $.fn.dataTable.Editor( {
                                ajax: "{{ asset('/Include/request.php') }}",
                                 table: "#request2table",
                                 idSrc: "request.Id",
                                 fields: [

                                    {
                                             label: "Others:",
                                             name: "request.Others",
                                     },

                                      {
                                               label: "Remarks:",
                                               name: "request.Remarks",
                                       }
                                        // {
                                        //          label: "request Type:",
                                        //          name: "requests.request_Type",
                                        //          type:  'select',
                                        //          options: [
                                        //              { label :"", value: "" },
                                        //              @foreach($options as $option)
                                        //                @if ($option->Field="request_Type")
                                        //                  { label :"{{$option->Option}}", value: "{{$option->Option}}" },
                                        //                @endif
                                        //              @endforeach
                                        //          ],
                                        //  },{
                                        //          label: "request Term:",
                                        //          name: "requests.request_Term"
                                        //  }, {
                                        //          label: "Start Date:",
                                        //          name: "requests.Start_Date",
                                        //          type:   'datetime',
                                        //          def:    function () { return new Date(); },
                                        //          format: 'DD-MMM-YYYY'
                                        //  }, {
                                        //          label: "End Date:",
                                        //          name: "requests.End_Date",
                                        //          type:   'datetime',
                                        //          def:    function () { return new Date(); },
                                        //          format: 'DD-MMM-YYYY'
                                        //  }, {
                                        //          label: "No of Days:",
                                        //          name: "requests.No_of_Days"
                                        //  }, {
                                        //          label: "Reason:",
                                        //          name: "requests.Reason",
                                        //          type:  "textarea"
                                        //  }

                                 ]
                         } );

                         requests3editor = new $.fn.dataTable.Editor( {
                                ajax: "{{ asset('/Include/request.php') }}",
                                 table: "#request3table",
                                 idSrc: "request.Id",
                                 fields: [
                                        {
                                                 label: "Request Type:",
                                                 name: "request.Request_type",
                                                 type:  'select',
                                                 options: [
                                                     { label :"", value: "" },
                                                     @foreach($options as $option)
                                                       @if ($option->Field="Request_Type")
                                                         { label :"{{$option->Option}}", value: "{{$option->Option}}" },
                                                       @endif
                                                     @endforeach
                                                 ],
                                         },{
                                                 label: "request Term:",
                                                 name: "requests.request_Term",
                                                 type:  "radio",
                                                 options: [
                                                   { label :"Full Day", value: "Full Day" },
                                                   { label :"Half Day Morning", value: "Half Day Morning" },
                                                   { label :"Half Day Afternoon", value: "Half Day Afternoon" },
                                                 ],
                                         }, {
                                                 label: "Start Date:",
                                                 name: "requests.Start_Date",
                                                 type:   'datetime',
                                                 def:    function () { return new Date(); },
                                                 format: 'DD-MMM-YYYY'
                                         }, {
                                                 label: "End Date:",
                                                 name: "requests.End_Date",
                                                 type:   'datetime',
                                                 def:    function () { return new Date(); },
                                                 format: 'DD-MMM-YYYY'
                                         }, {
                                                 label: "No of Days:",
                                                 name: "requests.No_of_Days"
                                         }, {
                                                 label: "Reason:",
                                                 name: "requests.Reason",
                                                 type:  "textarea"
                                         }

                                 ]
                         } );

                         $('#requesttable').on( 'click', 'tbody td', function (e) {
                               requestseditor.inline( this, {
                                  onBlur: 'submit'
                             } );
                         } );

                         requesttable=$('#requesttable').DataTable( {
                           ajax: {
                             "url": "{{ asset('/Include/request.php') }}",
                             "data": {
                                 "UserId": {{ $me->UserId }},
                                 "Request_status": "%Pending Approval%"
                             }
                           },
                                 columnDefs: [{ "visible": false, "targets": [1,2] },{"className": "dt-center", "targets": "_all"}],
                                 responsive: false,
                                 colReorder: false,
                                 dom: "pBrt",
                                 sScrollX: "100%",
                                 bAutoWidth: true,
                                 sScrollY: "100%",
                                 scrollCollapse: true,
                                 aaSorting:false,
                                 fnInitComplete: function(oSettings, json) {

                                   $('#mypendingrequesttab').html("My Pending Request" + "[" + requesttable.rows().count() +"]")

                                  },
                                 columns: [
                                         {
                                            sortable: false,
                                            "render": function ( data, type, full, meta ) {

                                              if (full.requeststatuses.Status!=="Cancelled" && full.requeststatuses.Status!=="Final Approved")
                                              {
                                                return '<button type="button" class="btn btn-danger btn-sm" data-toggle="modal" onclick="CancelDialog('+full.request.Id+')">Cancel Request</button>';

                                              }
                                              else {
                                                // return '<button type="button" class="btn btn-success btn-sm" data-toggle="modal" onclick="OpenRedirectDialog('+full.request.Id+','+full.requeststatuses.UserId+')">Redirect</button>';
                                                return '';
                                              }

                                            }
                                        },

                                         { data: "request.Id",title:"Id"},
                                         { data: "requeststatuses.Id",title:"Id"},
                                         { data: "request.Request_type",title:"Request_Type",editfield:"request.Request_type"},
                                         { data: "request.Others",title:"Others"},
                                         { data: "request.Start_Date",title:"Start_Date"},
                                         { data: "request.End_Date",title:"End_Date"},
                                         { data: "request.Remarks",title:"Remarks"},
                                         { data: "request.created_at",title:"created_at"},
                                         { data: "approver.Name",title:"Approver", editfield:"request.Approver"},
                                         { data: "requeststatuses.Request_status",title:"Request_status"},
                                         { data: "requeststatuses.updated_at",title:"updated_at"},
                                         { data: "requeststatuses.Comment",title:"Comment"}
                                         // { data: "files.Web_Path",
                                         //    render: function ( url, type, row ) {
                                         //         if (url)
                                         //         {
                                         //           return '<a href="'+ url +'" target="_blank">Download</a>';
                                         //         }
                                         //         else {
                                         //           return ' - ';
                                         //         }
                                         //     },
                                         //     title: "File"
                                         //   }
                                 ],
                                 select: {
                                         style:    'os',
                                         selector: 'tr'
                                       },
                                 buttons: [
                                         { extend: "edit",   editor: requestseditor },

                                 ],

                     });

                     request2table=$('#request2table').DataTable( {
                       ajax: {
                         "url": "{{ asset('/Include/request.php') }}",
                         "data": {
                             "UserId": {{ $me->UserId }},
                             "Request_status": "%Approved%"
                         }
                       },
                             columnDefs: [{ "visible": false, "targets": [1,2] },{"className": "dt-center", "targets": "_all"}],
                             responsive: false,
                             colReorder: false,
                             dom: "pBrt",
                             sScrollX: "100%",
                             bAutoWidth: true,
                             sScrollY: "100%",
                             scrollCollapse: true,
                             aaSorting:false,
                             fnInitComplete: function(oSettings, json) {

                               $('#myapprovedrequesttab').html("My Approved Request" + "[" + request2table.rows().count() +"]")

                              },
                             columns: [
                               {
                                  sortable: false,
                                  "render": function ( data, type, full, meta ) {

                                      if (full.requeststatuses.Request_status=="Final Approved")
                                    {
                                      return '<button type="button" class="btn btn-success btn-sm" data-toggle="modal" onclick="OpenExportDialog('+full.request.Id+')">Export</button>';

                                    }
                                    else {
                                      return '';

                                    }

                                  }
                              },

                               { data: "request.Id",title:"Id"},
                               { data: "requeststatuses.Id",title:"Id"},
                               { data: "request.Request_type",title:"Request_Type"},
                               { data: "request.Others",title:"Others"},
                               { data: "request.Start_Date",title:"Start_Date"},
                               { data: "request.End_Date",title:"End_Date"},
                               { data: "request.Remarks",title:"Remarks"},
                               { data: "request.created_at",title:"created_at"},
                               { data: "approver.Name",title:"Approver"},
                               { data: "requeststatuses.Request_status",title:"Request_status"},
                               { data: "requeststatuses.updated_at",title:"updated_at"},
                               { data: "requeststatuses.Comment",title:"Comment"}

                               // { data: "files.Web_Path",
                               //    render: function ( url, type, row ) {
                               //         if (url)
                               //         {
                               //           return '<a href="'+ url +'" target="_blank">Download</a>';
                               //         }
                               //         else {
                               //           return ' - ';
                               //         }
                               //     },
                               //     title: "File"
                               //   }
                             ],
                             select: {
                                     style:    'os',
                                     selector: 'tr'
                                   },
                             buttons: [

                             ],

                 });

                 request3table=$('#request3table').DataTable( {
                   ajax: {
                     "url": "{{ asset('/Include/request.php') }}",
                     "data": {
                         "UserId": {{ $me->UserId }},
                         "Request_status": "%Rejected%"
                     }
                   },
                         columnDefs: [{ "visible": false, "targets": [0,1] },{"className": "dt-center", "targets": "_all"}],
                         responsive: false,
                         colReorder: false,
                         dom: "pBrt",
                         sScrollX: "100%",
                         bAutoWidth: true,
                         sScrollY: "100%",
                         scrollCollapse: true,
                         aaSorting:false,
                         fnInitComplete: function(oSettings, json) {

                           $('#myrejectedrequesttab').html("My Rejected Request" + "[" + request3table.rows().count() +"]")

                          },
                         columns: [
                           { data: "request.Id",title:"Id"},
                           { data: "requeststatuses.Id",title:"Id"},
                           { data: "request.Request_type",title:"Request_Type"},
                           { data: "request.Others",title:"Others"},
                           { data: "request.Start_Date",title:"Start_Date"},
                           { data: "request.End_Date",title:"End_Date"},
                           { data: "request.Remarks",title:"Remarks"},
                           { data: "request.created_at",title:"created_at"},
                           { data: "approver.Name",title:"Approver"},
                           { data: "requeststatuses.Request_status",title:"Request_status"},
                           { data: "requeststatuses.updated_at",title:"updated_at"},
                           { data: "requeststatuses.Comment",title:"Comment"}
                           // { data: "files.Web_Path",
                           //    render: function ( url, type, row ) {
                           //         if (url)
                           //         {
                           //           return '<a href="'+ url +'" target="_blank">Download</a>';
                           //         }
                           //         else {
                           //           return ' - ';
                           //         }
                           //     },
                           //     title: "File"
                           //   }
                         ],
                         select: {
                                 style:    'os',
                                 selector: 'tr'
                               },
                         buttons: [

                         ],

             });

             request4table=$('#request4table').DataTable( {
               ajax: {
                 "url": "{{ asset('/Include/request.php') }}",
                 "data": {
                     "UserId": {{ $me->UserId }},
                     "Request_status": "%Cancelled%"
                 }
               },
                     columnDefs: [{ "visible": false, "targets": [0,1] },{"className": "dt-center", "targets": "_all"}],
                     responsive: false,
                     colReorder: false,
                     dom: "pBrt",
                     sScrollX: "100%",
                     bAutoWidth: true,
                     sScrollY: "100%",
                     scrollCollapse: true,
                     aaSorting:false,
                     fnInitComplete: function(oSettings, json) {

                       $('#mycancelledrequesttab').html("My Cancelled Request" + "[" + request4table.rows().count() +"]")

                      },
                     columns: [
                       { data: "request.Id",title:"Id"},
                       { data: "requeststatuses.Id",title:"Id"},
                       { data: "request.Request_type",title:"Request_Type"},
                       { data: "request.Others",title:"Others"},
                       { data: "request.Start_Date",title:"Start_Date"},
                       { data: "request.End_Date",title:"End_Date"},
                       { data: "request.Remarks",title:"Remarks"},
                       { data: "request.created_at",title:"created_at"},
                       { data: "approver.Name",title:"Approver"},
                       { data: "requeststatuses.Request_status",title:"Request_status"},
                       { data: "requeststatuses.updated_at",title:"updated_at"},
                       { data: "requeststatuses.Comment",title:"Comment"}
                       // { data: "files.Web_Path",
                       //    render: function ( url, type, row ) {
                       //         if (url)
                       //         {
                       //           return '<a href="'+ url +'" target="_blank">Download</a>';
                       //         }
                       //         else {
                       //           return ' - ';
                       //         }
                       //     },
                       //     title: "File"
                       //   }
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
        My Request
        <small>My Workplace</small>
      </h1>
      <ol class="breadcrumb">
        <li><a href="{{ url('/home') }}"><i class="fa fa-dashboard"></i> Home</a></li>
        <li><a href="#">My Workplace</a></li>
        <li class="active">My Request</li>
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

        <div class="modal fade" id="Export" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
          <div class="modal-dialog" role="document">
            <div class="modal-content">
              <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title" id="myModalLabel">Export</h4>

              </div>
              <div class="modal-body">
                <div class="form-group" id="exportrequest">

                </div>
                  Are you sure you wish to export this request?
              </div>
              <div class="modal-footer">
                <center><img src="{{ URL::to('/') ."/img/ajax-loader.gif" }}" width="50px" height="50px" alt="Loading" name='ajaxloader' id="ajaxloader"></center>
                <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                <button type="button" class="btn btn-success" onclick="window.open('{{ url('/myrequest/') }}/export/' + selectedrequestid, '_blank');">Export</button>


              </div>
            </div>
          </div>
        </div>

        <div class="modal fade" id="Cancel" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
          <div class="modal-dialog" role="document">
            <div class="modal-content">
              <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title" id="myModalLabel">Cancel Loan Request</h4>
              </div>
              <div class="modal-body">
                <div class="form-group" id="cancelrequest">

                </div>
                  Are you sure you wish to cancel this request?
              </div>
              <div class="modal-footer">
                <center><img src="{{ URL::to('/') ."/img/ajax-loader.gif" }}" width="50px" height="50px" alt="Loading" name='ajaxloader2' id="ajaxloader2"></center>
                <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                <button type="button" class="btn btn-primary" onclick="cancelrequest()">Cancel Request</button>
              </div>
            </div>
          </div>
        </div>

        <div class="col-md-12">
          <div class="nav-tabs-custom">
            <ul class="nav nav-tabs">
              <li class="active"><a href="#requestapplicationform" data-toggle="tab">My Request Application Form</a></li>
              <li><a href="#mypendingrequest" data-toggle="tab" id="mypendingrequesttab">My Pending Request</a></li>
              <li><a href="#myapprovedrequest" data-toggle="tab" id="myapprovedrequesttab">My Approved Request</a></li>
              <li><a href="#myrejectedrequest" data-toggle="tab" id="myrejectedrequesttab">My Rejected Request</a></li>
              <li><a href="#mycancelledrequest" data-toggle="tab" id="mycancelledrequesttab">My Cancelled Request</a></li>
            </ul>

          <div class="tab-content">

            <div class="active tab-pane" id="requestapplicationform">

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

              <div class="col-md-6">
                  <div class="box box-solid">
                      <div class="box-header with-border">

                      <form enctype="multipart/form-data" id="upload_form" role="form" method="POST">
                        <input type="hidden" name="_token" value="{{ csrf_token() }}">
                        <input type="hidden" name="UserId" value="{{ $me->UserId }}">
                        <div class="form-group">
                        <label>Request Type : </label>
                        <!--<input type="text" class="form-control" id="Request_Type" name="Request_Type" value="">-->
                        <select class="form-control select2" id="Request_Type" name="Request_Type" style="width: 100%;" onChange="locktextbox();">
                         <option></option>

                         @foreach($options as $option)
                               @if ($option->Field=="Request_Type")
                                 <option <?php if(Input::old('Request_Type') == '{{$option->Option}}') echo ' selected="selected" '; ?>>{{$option->Option}}</option>
                               @endif
                             @endforeach


                        </select>
                      </div>

                      <div class="form-group">
                         <label>Others : </label>

                         <input type="text" class="form-control" id="Others" name="Others" value="" disabled>


                      </div>

                      <div class="form-group">
                        <label>Start Date : </label>

                        <div class="input-group date">
                          <div class="input-group-addon">
                            <i class="fa fa-calendar"></i>
                          </div>
                          <input type="text" class="form-control pull-right" id="Start_Date" name="Start_Date" value="{!! old('Start_Date') !!}" autocomplete="off" onchange="swapDate()">
                        </div>
                        <!-- /.input group -->
                      </div>


                      <div class="form-group">
                        <label>End Date : </label>

                        <div class="input-group date">
                          <div class="input-group-addon">
                            <i class="fa fa-calendar"></i>
                          </div>
                          <input type="text" class="form-control pull-right" id="End_Date" name="End_Date" value="{!! old('End_Date') !!}" autocomplete="off" onchange="swapDate()">
                        </div>
                        <!-- /.input group -->
                      </div>

                        <div class="form-group">
                         <label>Approver : </label>

                         <select class="form-control select2" id="Approver" name="Approver" style="width: 100%;">
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

                        <br>
                        <div class="form-group">
                           <label>Remarks : </label>

                           <textarea rows="4" cols="50" class="form-control" id="Remarks" name="Remarks" value=""></textarea>

                        </div>
                      </form>

                            <!-- /.box-body -->

                            <div class="box-footer">
                              <!-- <a class="btn btn-primary" onclick="applyrequest()">Submit<a/> -->
                              <button type="submit" class="btn btn-primary" onclick="applyrequest()">Submit</button>
                            </div>
                          {{-- </form> --}}
                      </div>
                  </div>
                </div>
              </div>
              {{-- </div> --}}

            </div>

              <div class="tab-pane" id="mypendingrequest">
                <table id="requesttable" class="display" cellspacing="0" width="100%" padding="30px" style="font-size: 13px;">
                    <thead>
                        {{-- prepare header search textbox --}}
                        <tr>
                          @foreach($myrequest as $key=>$value)

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
                      @foreach($myrequest as $myrequests)

                        {{-- @if(strpos($myrequests->Request_status,"Pending Approval")!==false) --}}

                        @if(strpos($myrequests->Status,"Pending Approval")!==false)
                          <tr id="row_{{ $i }}">
                            <td></td>
                              @foreach($myrequests as $key=>$value)
                                <td>
                                  {{ $value }}
                                </td>
                              @endforeach
                          </tr>
                          <?php $i++; ?>
                          @endif
                        {{-- @endif --}}

                      @endforeach

                  </tbody>
                    <tfoot></tfoot>
                </table>
              </div>

              <div class="tab-pane" id="myapprovedrequest">
                <table id="request2table" class="display" cellspacing="0" width="100%" padding="30px" style="font-size: 13px;">
                    <thead>
                        {{-- prepare header search textbox --}}
                        <tr>
                          @foreach($myrequest as $key=>$value)

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
                      @foreach($myrequest as $myrequests)

                          {{-- @if(strpos($myrequests->Request_status,"Approved")!==false) --}}
                          @if(strpos($myrequests->Status,"Approved")!==false)
                          <tr id="row_{{ $i }}">
                            <td></td>
                              @foreach($myrequests as $key=>$value)
                                <td>
                                  {{ $value }}
                                </td>
                              @endforeach
                          </tr>
                          <?php $i++; ?>
                          @endif
                        {{-- @endif --}}

                      @endforeach

                  </tbody>
                    <tfoot></tfoot>
                </table>
              </div>

              <div class="tab-pane" id="myrejectedrequest">
                <table id="request3table" class="display" cellspacing="0" width="100%" padding="30px" style="font-size: 13px;">
                    <thead>
                        {{-- prepare header search textbox --}}
                        <tr>
                          @foreach($myrequest as $key=>$value)

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
                      @foreach($myrequest as $myrequests)

                          {{-- @if(strpos($myrequests->Status,"Rejected")!==false) --}}

                          @if(strpos($myrequests->Status,"Rejected")!==false)
                          <tr id="row_{{ $i }}">
                              @foreach($myrequests as $key=>$value)
                                <td>
                                  {{ $value }}
                                </td>
                              @endforeach
                          </tr>
                          <?php $i++; ?>
                          @endif
                        {{-- @endif --}}

                      @endforeach

                  </tbody>
                    <tfoot></tfoot>
                </table>
              </div>

              <div class="tab-pane" id="mycancelledrequest">
                <table id="request4table" class="display" cellspacing="0" width="100%" padding="30px" style="font-size: 13px;">
                    <thead>
                        {{-- prepare header search textbox --}}
                        <tr>
                          @foreach($myrequest as $key=>$value)

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
                      @foreach($myrequest as $myrequests)

                          @if(strpos($myrequests->Status,"Cancelled")!==false)
                          <tr id="row_{{ $i }}">
                              @foreach($myrequests as $key=>$value)
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
    $('input[type="checkbox"].flat-green, input[type="radio"].flat-green').iCheck({
      checkboxClass: 'icheckbox_flat-green',
      radioClass: 'iradio_flat-green'
    });

    //Date picker
    $('#Start_Date').datepicker({
      autoclose: true,
        format: 'dd-M-yyyy',
    });

    $('#End_Date').datepicker({
      autoclose: true,
        format: 'dd-M-yyyy',
    });

  });

  function OpenExportDialog(id)
  {
    var hiddeninput='<input type="hidden" class="form-control" id="selectedrequestid" name="selectedrequestid" value="'+id+'">';


    $( "#exportrequest" ).html(hiddeninput);

    selectedrequestid=id;

      $('#Export').modal('show');

  }

  function CancelDialog(id)
  {

    var hiddeninput='<input type="hidden" class="form-control" id="cancelrequestid" name="cancelrequestid" value="'+id+'">';

      $( "#cancelrequest" ).html(hiddeninput);
      $('#Cancel').modal('show');

  }

function applyrequest() {

  $("#Others").prop("disabled", false);
  $("#Start_Date").prop("disabled", false);
  $("#End_Date").prop("disabled", false);

   $.ajaxSetup({
       headers: {
           'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
     }
   });

      $.ajax({
                  url: "{{ url('applyrequest') }}",
                  method: "POST",
                  contentType: false,
                  processData: false,
                  data:new FormData($("#upload_form")[0]),

                  success: function(response){

                    if (response==1)
                    {

                        var message="Request application submitted!";
                        $("#update-alert ul").html(message);
                        $("#update-alert").modal('show');


                        $("#error-alert").hide();

                        $("#Request_Type").val("").change();
                        $("#Others").val("").change();
                        $("#Approver").val("").change();
                        document.getElementById("Others").checked= ''

                        $("#ajaxloader").hide();

                        requesttable.ajax.reload();
                    }
                    else {
                        var obj = jQuery.parseJSON(response);
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

      $("#Others").prop("disabled", true);
      $("#Start_Date").prop("disabled", true);
      $("#End_Date").prop("disabled", true);

  }

      function locktextbox()
      {
          // console.log('test');
          // if (document.getElementById("Request_Type").value === "Others") {
          if($("#Request_Type").val() == 'Others'){
              // document.getElementById("Others").disable='true';
              $("#Others").prop("disabled", false);
              // $("#Start_Date").prop("disabled", false);
              // $("#End_Date").prop("disabled", false);
          }
          else if($("#Request_Type").val().includes('Advance')){
              // document.getElementById("Others").disable='true';
              $("#Others").prop("disabled", true);
              // $("#Start_Date").prop("disabled", false);
              // $("#End_Date").prop("disabled", false);
          }
          else if($("#Request_Type").val().includes('Travel')){
              // document.getElementById("Others").disable='true';
              $("#Others").prop("disabled", true);
              // $("#Start_Date").prop("disabled", false);
              // $("#End_Date").prop("disabled", false);
          }
          else if($("#Request_Type").val().includes('Holiday')){
              // document.getElementById("Others").disable='true';
              $("#Others").prop("disabled", true);
              // $("#Start_Date").prop("disabled", false);
              // $("#End_Date").prop("disabled", false);
          }
          else {
              // document.getElementById("Others").disable='false';
              $("#Others").prop("disabled", true);
              // $("#Start_Date").prop("disabled", true);
              // $("#End_Date").prop("disabled", true);
          }

      }

      function cancelrequest() {

          $.ajaxSetup({
             headers: { 'X-CSRF-Token' : $('meta[name=_token]').attr('content') }
          });

          $("#ajaxloader2").show();

          requestid=$('[name="cancelrequestid"]').val();

          $.ajax({
                      url: "{{ url('/myrequest/cancel') }}",
                      method: "POST",
                      data: {Id:requestid},

                      success: function(response){

                        if (response==1)
                        {
                            requesttable.ajax.reload();
                            request2table.ajax.reload();
                            request3table.ajax.reload();
                            request4table.ajax.reload();
                            var message="Request cancelled!";
                            $("#update-alert ul").html(message);
                            $("#update-alert").modal('show');

                            $('#Cancel').modal('hide');

                            $("#ajaxloader2").hide();

                        }
                        else {

                          var errormessage="Failed to cancel request!";
                          $("#error-alert ul").html(errormessage);
                          $("#error-alert").modal('show');


                          $('#Cancel').modal('hide');

                          $("#ajaxloader2").hide();

                        }

              }
          });

      }

      // $('#Project').on('change', function() {

      //   $('#Approver')
      //   .empty();

      //   $('#Approver')
      //   .append('<option value=""></option>');

      //   if($("#Project option:selected").text()=="")
      //   {

      //     @foreach ($approver as $user)

      //           $('#Approver')
      //           .append('<option value="{{$user->Id}}">{{$user->Name}} - {{$user->Project_Name}} [{{$user->Level}}]</option>');

      //     @endforeach

      //   }
      //   else {

      //     @foreach ($approver as $user)

      //       if ($("#Project option:selected").text()==decodeEntities("{{$user->Project_Name}}"))
      //       {

      //           $('#Approver')
      //           .append('<option value="{{$user->Id}}">{{$user->Name}} - {{$user->Project_Name}} [{{$user->Level}}]</option>');

      //       }

      //     @endforeach

      //   }

      // });

      function decodeEntities(s){
          var str, temp= document.createElement('p');
          temp.innerHTML= s;
          str= temp.textContent || temp.innerText;
          temp=null;
          return str;
        }

      function swapDate(argument) {
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
      }

  </script>

@endsection
