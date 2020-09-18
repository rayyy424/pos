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

      .dtr-data:before {
        display:inline-block;
        content: "\200D";
      }

      .dtr-data {
        display:inline-block;
        min-width: 100px;
        background-color:yellow;
        font-style:italic;
        font-size: 9pt;
      }

      .weekend {
        color: red;
      }

      .weekendrow.even {
        background-color: #FADBD8;
      }

      table.dataTable.display tbody tr.weekendrow.odd {
        background-color: #FADBD8;
      }

      .modal-dialog {
        width: 600px;
        margin: 20% auto;
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
      <script type="text/javascript" language="javascript" src="https://cdn.datatables.net/plug-ins/1.10.13/api/sum().js"></script>

      <script type="text/javascript" language="javascript" src="{{ asset('/plugin/js/dataTables.editor.min.js') }}"></script>
      <script type="text/javascript" language="javascript" src="{{ asset('/plugin/imagezoom/jquery.zoom.js') }}"></script>

      <script type="text/javascript" language="javascript" src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.8.4/moment.min.js"></script>
      <script type="text/javascript" language="javascript" src="https://cdn.datatables.net/plug-ins/1.10.15/sorting/datetime-moment.js"></script>


      {{-- <script type="text/javascript" language="javascript" src="{{ asset('/plugin/examples/resources/syntax/shCore.js') }}"></script>
      <script type="text/javascript" language="javascript" src="{{ asset('/plugin/examples/resources/demo.js') }}"></script>
      <script type="text/javascript" language="javascript" src="{{ asset('/plugin/examples/resources/editor-demo.js') }}"></script> --}}

      <script>

      var editor;
      var claimtable;

      $(document).ready(function() {

        $.fn.dataTable.moment( 'DD-MMM-YYYY' );

                     editor = new $.fn.dataTable.Editor( {
                         ajax: {
                            "url": "{{ asset('/Include/claim.php') }}",
                            "data": {
                                "ClaimSheetId": {{ $myclaim[0]->Id }}
                            }
                          },
                          formOptions: {
                               bubble: {
                                   submit: 'allIfChanged'
                               }
                           },
                             table: "#pendingtable",
                             idSrc: "claims.Id",
                             fields: [
                               {
                                       name: "claims.ClaimId",
                                       type: "hidden"
                               },{
                                       name: "claims.ClaimSheetId",
                                       type: "hidden"
                               },{
                                      label: "Date:",
                                      name: "claims.Date",
                                      type:   'datetime',
                                      def:    function () { return new Date(); },
                                      format: 'DD-MMM-YYYY'
                               },{
                                      label: "Depart_From:",
                                      name: "claims.Depart_From"
                               },{
                                      label: "Destination:",
                                      name: "claims.Destination"
                               },{
                                      label: "Site Name:",
                                      name: "claims.Site_Name"
                               },{
                                      label: "State:",
                                      name: "claims.State",
                                      type:  'select',
                                      options: [
                                          { label :"", value: "" },
                                          @foreach($options as $option)
                                            @if ($option->Field=="State")
                                              { label :"{{$option->Option}}", value: "{{$option->Option}}" },
                                            @endif
                                          @endforeach

                                      ],
                               },{
                                      label: "Work Description:",
                                      name: "claims.Work_Description"
                               },{
                                      label: "Next Person:",
                                      name: "claims.Next_Person"
                               },{
                                      label: "Transport_Type:",
                                      name: "claims.Transport_Type",
                                      type:  'select',
                                      options: [
                                          { label :"", value: "" },
                                          { label :"Car", value: "Car" },
                                          { label :"Motorbike", value: "Motorbike" },
                                      ],
                               },{
                                      label: "Car No:",
                                      name: "claims.Car_No"
                               },{
                                      label: "Mileage:",
                                      name: "claims.Mileage"
                               },{
                                      label: "Currency:",
                                      name: "claims.Currency",
                                      type:  'select',
                                      options: [
                                          { label :"", value: "" },
                                          @foreach($options as $option)
                                            @if ($option->Field=="Currency")
                                              { label :"{{$option->Option}}", value: "{{$option->Option}}" },
                                            @endif
                                          @endforeach
                                      ],
                               },{
                                      label: "Rate:",
                                      name: "claims.Rate",
                                      attr: {
                                        type: "number"
                                      }

                               },{
                                      label: "Expenses Type:",
                                      name: "claims.Expenses_Type",
                                      type:  'select',
                                      options: [
                                          { label :"", value: "" },
                                          @foreach($options as $option)
                                            @if ($option->Field=="Expenses_Type")
                                              { label :decodeEntities("{{$option->Option}}"), value: decodeEntities("{{$option->Option}}") },
                                            @endif
                                          @endforeach
                                      ],
                               },{
                                      label: "Total Expenses:",
                                      name: "claims.Total_Expenses"
                               },{
                                      label: "Advance:",
                                      name: "claims.Advance"
                               },{
                                      label: "Petrol SmartPay:",
                                      name: "claims.Petrol_SmartPay"
                               },{
                                      label: "GST Amount:",
                                      name: "claims.GST_Amount"
                               },{
                                      label: "Total Without GST:",
                                      name: "claims.Total_Without_GST"
                               },{
                                      label: "Receipt No:",
                                      name: "claims.Receipt_No"
                               },{
                                      label: "Company_Name:",
                                      name: "claims.Company_Name"
                               },{
                                      label: "GST No:",
                                      name: "claims.GST_No"
                               },{
                                      label: "Remarks:",
                                      name: "claims.Remarks"
                               }, {
                                       label: "File:",
                                       name: "files.Web_Path",
                                       type: "upload",
                                       ajaxData: function ( d ) {
                                         d.append( 'Id', claimtable.api().row( claimseditor.modifier() ).data().claims.Id ); // edit - use `d`
                                       },
                                       display: function ( file_id ) {
                                           return '<a href="'+ claimtable.api().row( claimseditor.modifier() ).data().files.Web_Path +'" target="_blank">Download</>';
                                       },
                                       noImageText: 'No file'
                               }

                             ]
                     } );


                     // Activate an inline edit on click of a table cell
                           $('#pendingtable').on( 'click', 'tbody td:not(.child), tbody span.dtr-data', function (e) {
                             editor.inline( this, {
                            onBlur: 'submit',
                            submit: 'allIfChanged'
                               } );
                           } );

                           editor.on( 'postEdit', function ( e, json, data ) {

                             $("#totalexpenses").html("RM" + claimtable.column( "Total_Expenses:name" ).data().sum().toFixed(2));
                             $("#totaladvance").html("RM" + claimtable.column( "Advance:name" ).data().sum().toFixed(2));
                             $("#totalsmartpay").html("RM" + claimtable.column( "Petrol_SmartPay:name" ).data().sum().toFixed(2));
                             $("#total").html("RM" + claimtable.column( "Total_Amount:name" ).data().sum().toFixed(2));
                             $("#totalgst").html("RM" + claimtable.column( "GST_Amount:name" ).data().sum().toFixed(2));
                             $("#totalnogst").html("RM" + claimtable.column( "Total_Without_GST:name" ).data().sum().toFixed(2));

                            } );

                           claimtable=$('#pendingtable').DataTable( {
                                 ajax: {
                                    "url": "{{ asset('/Include/claim.php') }}",
                                    "data": {
                                        "ClaimSheetId": {{ $myclaim[0]->Id }}
                                    }
                                  },
                                  fnInitComplete: function(oSettings, json) {
                                    $("#totalexpenses").html("RM" + claimtable.column( "Total_Expenses:name" ).data().sum().toFixed(2));
                                    $("#totaladvance").html("RM" + claimtable.column( "Advance:name" ).data().sum().toFixed(2));
                                    $("#totalsmartpay").html("RM" + claimtable.column( "Petrol_SmartPay:name" ).data().sum().toFixed(2));
                                     $("#total").html("RM" + claimtable.column( "Total_Amount:name" ).data().sum().toFixed(2));
                                     $("#totalgst").html("RM" + claimtable.column( "GST_Amount:name" ).data().sum().toFixed(2));
                                     $("#totalnogst").html("RM" + claimtable.column( "Total_Without_GST:name" ).data().sum().toFixed(2));

                                     @if ($myclaim[0]->Status=="Pending Submission" || $myclaim[0]->Status=="Recalled")
                                         $('#submitbtn').show();
                                         $('#recallbtn').hide();
                                         editor.enable();
                                         claimtable.autoFill().enable();

                                     @elseif ($myclaim[0]->Status=="Submitted for Approval")

                                         $('#submitbtn').hide();
                                         $('#recallbtn').show();
                                         editor.disable();
                                         claimtable.autoFill().disable();

                                     @endif
                                   },
                                   createdRow: function ( row, data, index ) {
                                     var days = ['Sun','Mon','Tue','Wed','Thu','Fri','Sat'];
                                     var d = new Date(data.claims.Date)
                                     var n = days[d.getDay()];

                                     if (n=="Sun" || n=="Sat")
                                     {
                                       $(row).removeClass('odd');
                                       $(row).addClass('weekendrow');

                                     }
                                   },
                                   rowId: 'claims.Id',
                                   columnDefs: [{ "visible": false, "targets": [1,2] },{"className": "dt-center", "targets": "_all"}],
                                   responsive: false,
                                   colReorder: true,
                                   bStateSave:false,
                                   sScrollX: "100%",
                                   bAutoWidth: false,
                                   iDisplayLength:10,
                                   //sScrollY: "100%",
                                   dom: "lBrtip",

                                   aaSorting: [[3,"asc"]],
                                   columns: [
                                     {
                                        sortable: false,
                                        "render": function ( data, type, full, meta ) {
                                            return '';
                                        }
                                    },
                                     { data: "claims.Id"},
                                     { data: "claims.ClaimSheetId"},
                                     { data: "claims.Date" ,title:"Date"},
                                     {
                                       title:"Day",
                                        sortable: false,
                                        "render": function ( data, type, full, meta ) {
                                          var days = ['Sun','Mon','Tue','Wed','Thu','Fri','Sat'];
                                          var dateSplit = full.claims.Date.split("-");
                                          objDate = new Date(dateSplit[1] + " " + dateSplit[0] + ", " + dateSplit[2]);
                                          var d = new Date(objDate)
                                          var n = days[d.getDay()];

                                          if (n=="Sun" || n=="Sat")
                                          {
                                            return "<span class='weekend'>"+n+"</span>"
                                          }

                                            return n;
                                        }
                                    },
                                     { data: "claims.Depart_From",title:"Depart_From" },
                                     { data: "claims.Destination",title:"Destination" },
                                     { data: "claims.Site_Name",title:"Site_Name" },
                                     { data: "claims.State" ,title:"State"},
                                     { data: "claims.Work_Description",title:"Work_Description" },
                                     { data: "claims.Next_Person" ,title:"Next_Person"},
                                     { data: "claims.Transport_Type" ,title:"Transport_Type"},
                                     { data: "claims.Car_No",title:"Vehicle_No" },
                                     { data: "claims.Mileage" ,title:"Mileage"},
                                     { data: "claims.Currency" ,title:"Currency"},
                                     { data: "claims.Rate" ,title:"Cur_Rate"},
                                     { data: "claims.Expenses_Type",title:"Expenses_Type" },
                                     { data: "claims.Total_Expenses",title:"Total_Expenses",name:"Total_Expenses" },
                                     { data: "claims.Petrol_SmartPay",title:"Petrol_SmartPay",name:"Petrol_SmartPay" },
                                     {
                                       title:"Claims_Amount_Exclude_SmartPay",
                                        sortable: false,
                                        "render": function ( data, type, full, meta ) {
                                        return (full.claims.Total_Expenses-full.claims.Petrol_SmartPay).toFixed(2);;
                                        }
                                    },
                                     { data: "claims.Advance",title:"Advance",name:"Advance" },
                                     { data: "claims.Total_Amount" , title:"Total_Payable",name: "Total_Amount"},
                                     { data: "claims.GST_Amount" ,title:"GST_Amount",name:"GST_Amount"},
                                     { data: "claims.Total_Without_GST",title:"Total_Without_GST" ,name:"Total_Without_GST"},
                                     { data: "claims.Receipt_No",title:"Receipt_No" },
                                     { data: "claims.Company_Name",title:"Company_Name" },
                                     { data: "claims.GST_No",title:"GST_No" },
                                     { data: "claims.Remarks",title:"Remarks" },
                                     { data: "approver.Name",title:"Approver" },
                                     { data: "claimstatuses.Status",title:"Status"},
                                     { data: "claimstatuses.Comment",title:"Comment"},
                                     { data: "claimstatuses.updated_at",title:"Updated_At"}

                                   ],
                                   autoFill: {
                                      editor:  editor,
                                      columns: [ 3,4,5,6,7,8,9,10,11,12,13,14,15,16,17,18,19,20,21,22,23,24 ]
                                  },
                                  // keys: {
                                  //     columns: ':not(:first-child)',
                                  //     editor:  editor
                                  // },
                                  select: true,
                                   buttons: [
                                     {
                                       text: 'New Claim',
                                       action: function ( e, dt, node, config ) {
                                           // clearing all select/input options
                                           editor
                                              .create( false )
                                              .set( 'claims.ClaimSheetId', {{ $myclaim[0]->Id }} )
                                              .submit();
                                       },
                                     },
                                           { extend: "remove", editor: editor },

                                   ],

                       });

                       editor.on( 'preSubmit', function ( e, o, action ) {
                         if ( action == 'edit' ) {
                             var mileage = this.field( 'claims.Mileage' );
                             var smartpay = this.field( 'claims.Petrol_SmartPay' );

                             // Only validate user input values - different values indicate that
                             // the end user has not entered a value
                             if (mileage.val()>0)
                             {

                               if ( smartpay.val()>0 ) {
                                   smartpay.error( 'Mileage and SmartPay cannot be co-exist!' );
                                   smartpay.val(0);
                                   return false;
                               }
                             }

                             if (smartpay.val()>0)
                             {

                               if ( mileage.val()>0 ) {
                                   mileage.error( 'Mileage and SmartPay cannot be co-exist!' );
                                   mileage.val(0);
                                   return false;
                               }
                             }

                             return true;

                         }
                     } );

            // // Activate an inline edit on click of a table cell
            // $('#pendingtable').on( 'click', 'tbody td:not(:first-child)', function (e) {
            //     editor.inline( this );
            // } );
            //
            // // Disable KeyTable while the main editing form is open
            // editor
            //     .on( 'open', function ( e, mode, action ) {
            //         if ( mode === 'main' ) {
            //             table.keys.disable();
            //         }
            //     } )
            //     .on( 'close', function () {
            //         table.keys.enable();
            //     } );

          } );

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
                   right: 'month'
                 },
                 buttonText: {
                   today: 'Today',
                   month: 'Month'
                 },
                 //Random default events
                 events: [
                 ],

                 editable: false,
                 droppable: false, // this allows things to be dropped onto the calendar !!!
               });

               $("#ajaxloader").hide();
               $("#ajaxloader2").hide();
             });

      </script>
@endsection

@section('content')

    <div class="content-wrapper">

    <!-- Content Header (Page header) -->
    <section class="content-header">
      <h1>
        My Claim Detail
        <small>My Workplace</small>
      </h1>
      <ol class="breadcrumb">
        <li><a href="{{ url('/home') }}"><i class="fa fa-dashboard"></i> Home</a></li>
        <li><a href="#">My Workplace</a></li>
        <li><a href="{{ url('/myclaim') }}">My Claim</a></li>
        <li class="active">My Claim Detail</li>
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
<!--
        <div id="warning-alert" class="alert alert-warning alert-dismissible" style="display:none;">
          <button type="button" class="close" onclick="$('#warning-alert').hide()">&times;</button>
          <h4><i class="icon fa fa-ban"></i> Alert!</h4>
          <ul>

          </ul>
        </div> -->

         {{-- <div class="box-body">
           <button type="button" class="btn btn-success btn-lg" data-toggle="modal" data-target="#ExportPDF">Export</button>
         </div> --}}

         <div class="modal fade" id="ExportPDF" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
          <div class="modal-dialog">
           <div class="modal-content">
             <div class="modal-header">

               <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
               <h4 class="modal-title" id="myModalLabel">Export Claim</h4>

             </div>

             <div class="modal-body">
                 Are you sure you wish to export this claim?
             </div>
             <div class="modal-footer">
               <a class="btn btn-primary btn-lg" href="{{ url('/exportclaim') }}/{{$myclaim[0]->Id}}/{{$me->UserId}}" target="_blank">PDF</a>
               <a class="btn btn-primary btn-lg" href="{{ url('/excelClaim') }}/{{$myclaim[0]->Id}}/{{$me->UserId}}/{{ $myclaim[0]->Claim_Sheet_Name }}/{{ $myclaim[0]->Claim_Sheet_Name }}" target="_blank">Excel</a>

             </div>
           </div>
         </div>
       </div>

        <div class="col-md-4">

          <div class="box box-primary">

            <div class="box-body box-profile">

              <ul class="list-group list-group-unbordered">
                <li class="list-group-item">
                  <b>Claim Name</b> : <br><i>{{ $myclaim[0]->Claim_Sheet_Name }}</i>
                </li>
                <li class="list-group-item">
                  <b>Remarks</b> : <br><i>{{ $myclaim[0]->Remarks }}</i>
                </li>
                <li class="list-group-item">
                  <b>Status</b> : <p class="pull-right"><i><span id='status'>{{ $myclaim[0]->Status }}</span></i></p>
                </li>
                <li class="list-group-item">
                  <b>Date</b> : <p class="pull-right"><i>{{ $myclaim[0]->Created_Date }}</i></p>
                </li>
                <li class="list-group-item">
                  <b>Total Expenses</b> : <p class="pull-right"><i><span id='totalexpenses'>RM0.00</span></i></p>
                </li>
                <li class="list-group-item">
                  <b>Total SmartPay</b> : <p class="pull-right"><i><span id='totalsmartpay'>RM0.00</span></i></p>
                </li>
                <li class="list-group-item">
                  <b>Total Advance</b> : <p class="pull-right"><i><span id='totaladvance'>RM0.00</span></i></p>
                </li>
                <li class="list-group-item">
                  <b>Total GST Amount</b> : <p class="pull-right"><i><span id='totalgst'>RM0.00</span></i></p>
                </li>
                <li class="list-group-item">
                  <b>Total Without GST</b> : <p class="pull-right"><i><span id='totalnogst'>RM0.00</span></i></p>
                </li>
                <li class="list-group-item">
                  <b>Total Payable</b> : <p class="pull-right"><i><span id='total'>RM0.00</span></i></p>
                </li>
                <li class="list-group-item">
                  <center>
                    <div class="box-body">
                      {{-- @if ($myclaim[0]->Status=="Pending Submission" || $myclaim[0]->Status=="Recalled") --}}
                        <button type="button" class="btn btn-success btn-lg" id="submitbtn" data-toggle="modal" data-target="#Submit">Submit for Approval</button>
                      {{-- @elseif ($myclaim[0]->Status=="Submitted for Approval") --}}
                        <button type="button" class="btn btn-danger btn-lg" id="recallbtn"  data-toggle="modal" data-target="#Recall">Recall</button>
                      {{-- @endif --}}
                    </div>
                  </center>
                </li>
              </ul>

              {{-- <a href="#" class="btn btn-primary btn-block"><b>Update</b></a> --}}
            </div>

          </div>
        </div>

        <div class="modal fade" id="Submit" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
          <div class="modal-dialog" role="document">
            <div class="modal-content">
              <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title" id="myModalLabel">Submit Claim</h4>
              </div>
              <div class="modal-body">
                  Are you sure you wish to submit this claim sheet for approval?
              </div>
              <div class="modal-footer">
                <center><img src="{{ URL::to('/') ."/img/ajax-loader.gif" }}" width="50px" height="50px" alt="Loading" name='ajaxloader' id="ajaxloader"></center>
                <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                <button type="button" class="btn btn-primary" onclick="Submitforapproval({{$myclaim[0]->Id}})">Yes</button>
              </div>
            </div>
          </div>
        </div>

        <div class="modal fade" id="Recall" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
          <div class="modal-dialog" role="document">
            <div class="modal-content">
              <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title" id="myModalLabel">Recall Claim</h4>
              </div>
              <div class="modal-body">
                  Are you sure you wish to recall this claim sheet?
              </div>
              <div class="modal-footer">
                <center><img src="{{ URL::to('/') ."/img/ajax-loader.gif" }}" width="50px" height="50px" alt="Loading" name='ajaxloader2' id="ajaxloader2"></center>
                <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                <button type="button" class="btn btn-primary" onclick="Recall({{$myclaim[0]->Id}})">Recall</button>
              </div>
            </div>
          </div>
        </div>

        <div class="col-md-8">
          <div class="box box-primary">
              <div class="box-header with-border">
                <div class="row">
                  <div class="col-md-4">
                    Receipt <p class="text-muted">[PNG, JPG and PDF file only]</p>
                  </div>
                  <div class="col-md-4">
                    <br>
                    <div class="form-group">
                      <form enctype="multipart/form-data" id="upload_form" role="form" method="POST" action="" >
                        <input type="hidden" class="form-control" id="ClaimSheetId" name="ClaimSheetId" value="{{$myclaim[0]->Id}}">
                        <input type="file" id="receipt[]" name="receipt[]" accept=".png,.jpg,.jpeg,.pdf" multiple>

                      </form>
                    </div>
                  </div>

                  <div class="col-md-4">
                    <br>
                    <button type="button" class="btn btn-primary" onclick="uploadreceipt()">Upload</button>
                  </div>
                </div>
                <br>



                <div id="receiptdiv">


                  @foreach ($myreceipt as $receipt)

                    @if(strpos($receipt->Web_Path,'.png') !== false || strpos($receipt->Web_Path,'.jpg') !== false || strpos($receipt->Web_Path,'.jpeg') !== false ||strpos($receipt->Web_Path,'.PNG') !== false || strpos($receipt->Web_Path,'.JPG') !== false || strpos($receipt->Web_Path,'.JPEG') !== false)
                      <div class="col-md-6">
                        <div class="" id="receipt{{ $receipt->Id }}">
                            <img class="" src="{{ url($receipt->Web_Path) }}" width="100%"  alt="Photo">
                          <button type="button" class="btn btn-block btn-danger btn-xs" onclick="deletereceipt({{$receipt->Id }})">Delete</button>
                        </div>
                      </div>

                    @else
                      <div class="" id="receipt{{ $receipt->Id }}">
                        <!-- <span class="zoom"> -->
                          {{ $receipt->File_Name}}
                        <!-- </span> -->
                        <button type="button" class="btn btn-block btn-danger btn-xs" onclick="deletereceipt({{$receipt->Id }})">Delete</button>
                      </div>

                    @endif

                  @endforeach

                </div>



              </div>
          </div>
        </div>

        {{-- <div class="col-md-4">
          <div class="box box-primary">
              <div class="box-header with-border">
                  <div id="calendar"></div>
              </div>
          </div>
        </div> --}}

      </div>

      <div class="row">
        <div class="col-md-12">
          <div class="box box-success">
            <br>
            <div class="nav-tabs-custom">

              <div class="tab-content">
                <div class="active tab-pane" id="pending">

                  <table id="pendingtable" class="display" cellspacing="0" width="100%" padding="30px" style="font-size: 13px;">
                      <thead>
                          {{-- prepare header search textbox --}}
                          <tr>
                            @foreach($myclaimdetail as $key=>$value)

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
                        @foreach($myclaimdetail as $claim)

                              <tr id="row_{{ $i }}" >
                                  @foreach($claim as $key=>$value)
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

                {{-- <div class="tab-pane" id="approved">

                </div>

                <div class="tab-pane" id="rejected">

                </div> --}}
              </div>
            </div>
          </div>
        </div>
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
    $('input[type="checkbox"].flat-blue, input[type="radio"].flat-blue').iCheck({
      checkboxClass: 'icheckbox_flat-blue',
      radioClass: 'iradio_flat-blue'
    });

    //Date picker
    $('#Date').datepicker({
      autoclose: true
    });

    //Timepicker
    $(".timepicker").timepicker({
      showInputs: false
    });

    // document.getElementById("Time_In").value = '';
    // document.getElementById("Time_Out").value = '';

  });

  function Recall(id)
  {

      $.ajaxSetup({
         headers: { 'X-CSRF-Token' : $('meta[name=_token]').attr('content') }
      });

      $("#ajaxloader2").show();

      Id=id;

      $.ajax({
                  url: "{{ url('/myclaim/recall') }}",
                  method: "POST",
                  data: {Id:id},

                  success: function(response){

                    if (response==1)
                    {

                        $('#Recall').modal('hide');
                        $('#submitbtn').show();
                        $('#recallbtn').hide();



                        $('#status').html("Recalled");

                        var message="Claim recalled!";
                        $("#update-alert ul").html(message);
                        $("#update-alert").modal('show');

                        $("#ajaxloader2").hide();

                        claimtable.ajax.url("{{ asset('/Include/claim.php') }}").load();

                        editor.enable();
                        claimtable.autoFill().enable();

                    }
                    else {

                        $('#Recall').modal('hide');

                      var errormessage="Failed to recall claim!";
                      $("#error-alert ul").html(errormessage);
                      $("#error-alert").modal('show');



                      $("#ajaxloader2").hide();

                    }

          }
      });

  }

  function Submitforapproval(id)
  {

      $.ajaxSetup({
         headers: { 'X-CSRF-Token' : $('meta[name=_token]').attr('content') }
      });

      $("#ajaxloader").show();

      Id=id;

      var emptyexpensestype=false;

      claimtable.rows().every( function ( rowIdx, tableLoop, rowLoop ) {
        var data = this.data();

        if(data.claims.Expenses_Type=="")
        {
          emptyexpensestype=true;
        }
        // ... do something with data(), or this.node(), etc
      } );

      if (emptyexpensestype==false)
      {
        $.ajax({
                    url: "{{ url('/myclaim/submitforapproval') }}",
                    method: "POST",
                    data: {Id:id},

                    success: function(response){

                      if (response==1)
                      {

                          $('#Submit').modal('hide');

                          $('#submitbtn').hide();
                          $('#recallbtn').hide();


                          $('#status').html("Submitted for Approval");

                          var message="Claim submitted for approval!";
                          $("#update-alert ul").html(message);
                          $("#update-alert").modal('show');

                          $("#ajaxloader").hide();

                          claimtable.ajax.url("{{ asset('/Include/claim.php') }}").load();
                          editor.disable();
                          claimtable.autoFill().disable();

                      }
                      else {

                        $('#Submit').modal('hide');

                        var errormessage="Failed to submit claim!";
                        $("#error-alert ul").html(errormessage);
                        $("#error-alert").modal('show');

                        $("#ajaxloader").hide();

                      }

            }
        });
      }
    else {

      $('#Submit').modal('hide');

      var errormessage="Expenses Type cannot be empty!";
      $("#error-alert ul").html(errormessage);
      $("#error-alert").modal('show');

      $("#ajaxloader").hide();

    }

  }

  function deletereceipt(id) {
    $.ajaxSetup({
       headers: { 'X-CSRF-Token' : $('meta[name=_token]').attr('content') }
    });
    $.ajax({
                url: "{{ url('/claim/deletereceipt') }}",
                method: "POST",
                data: {Id:id},
                success: function(response){
                  if (response==0)
                  {
                    var message ="Failed to delete receipt!";
                    $("#warning-alert ul").html(message);
                    $("#warning-alert").modal('show');

                  }
                  else {

                    //$("#Template").val(response).change();
                    $("#exist-alert").hide();

                    var message ="Receipt deleted!";
                    $("#update-alert ul").html(message);
                    $("#update-alert").modal('show');

                    $("#receipt"+id).remove();
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

  function uploadreceipt() {
      $.ajaxSetup({
         headers: { 'X-CSRF-Token' : $('meta[name=_token]').attr('content') }
      });
      $.ajax({
                  url: "{{ url('/claim/uploadreceipt') }}",
                  method: "POST",
                  contentType: false,
                  processData: false,
                  data:new FormData($("#upload_form")[0]),
                  success: function(response){
                    if (response==0)
                    {
                      var message ="Failed to upload receipt!";
                      $("#warning-alert ul").html(message);
                      $("#warning-alert").modal('show');


                    }
                    else {


                      //$("#Template").val(response).change();
                      $("#exist-alert").hide();

                      var message ="Receipt uploaded!";
                      $("#update-alert ul").html(message);
                      $("#update-alert").modal('show');

                      $("#receipt").val("");

                      var split=response.split(",");
                      for (var i = 0; i < split.length; i++) {

                        if (split[i].toUpperCase().includes(".PNG") ||split[i].toUpperCase().includes(".JPG")||split[i].toUpperCase().includes(".JPEG"))
                        {
                          var sub=split[i].split("|");

                          var html="<div class='col-sm-3' id='receipt"+sub[0]+"'>";
                          html+="<span class='zoom'>";
                          html+="<img class='img-responsive' src='"+sub[1]+"' width='200' height='200'  alt='Photo'>";
                          html+="</span>";
                          html+="<button type='button' class='btn btn-block btn-danger btn-xs' onclick='deletereceipt("+sub[0]+")'>Delete</button>";
                          html+="</div>";

                          $("#receiptdiv").append(html);


                        }
                        else {

                          var sub=split[i].split("|");
                          var html="<div class='col-sm-3' id='receipt"+sub[0]+"'>";
                        //  html+="<a download='"+sub[1]+"' href='"+sub[1]+"' title='Download'>";
                          html+=sub[2];
                        //  html+="</a>";
                          html+="<button type='button' class='btn btn-block btn-danger btn-xs' onclick='deletereceipt("+sub[0]+")'>Delete</button>";
                          html+="</div>";

                          $("#receiptdiv").append(html);

                        }

                      }
                    }
          }
      });
  }
</script>


@endsection
