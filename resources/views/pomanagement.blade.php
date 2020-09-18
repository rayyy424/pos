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

      .first{
        background-color: #446CB3;
      }

      .second{
        background-color: #1E824C;
      }

      .third{
        background-color: #8E44AD;
      }

      .fourth{
        background-color: #F89406;
      }

      .fifth{
        background-color: #6C7A89;
      }

      .PM{
        background-color: #1E824C;
      }

      .Finance{
        background-color: #DB0A5B;
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

      <script type="text/javascript" language="javascript" src="https://cdn.datatables.net/fixedcolumns/3.2.2/js/dataTables.fixedColumns.min.js"></script>
      <script type="text/javascript" language="javascript" src="{{ asset('/plugin/js/dataTables.editor.min.js') }}"></script>

      <script type="text/javascript" language="javascript" src="{{ asset('/plugin/datatables/extensions/Autocomplete/editor.autoComplete.js') }}"></script>

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
                    ajax: {
                       "url": "{{ asset('/Include/po.php') }}",
                       "data": {
                       }
                     },
                     formOptions: {
                          bubble: {
                              submit: 'allIfChanged'
                          }
                      },
                    table: "#potable",
                    idSrc: "po.Id",
                    fields: [
                            {
                                   label: "Huawei_ID:",
                                   name: "po.Huawei_ID",
                                   attr: {
                                     type: "number"
                                   }
                           },{
                                   label: "PO No:",
                                   name: "po.PO_No"
                           },{
                                  label: "PR No:",
                                  name: "po.PR_No"
                          },{
                                      label: "PO Date:",
                                      name: "po.PO_Date",
                                      type:   'datetime',
                                      format: 'DD-MMM-YYYY'
                           },{
                                    label: "PO Type:",
                                    name: "po.PO_Type",
                                    type: "select",
                                    options: [
                                        { label :"", value: "" },
                                        @foreach($options as $option)
                                          @if ($option->Field=="PO_Type")
                                            { label :"{{$option->Option}}", value: "{{$option->Option}}" },
                                          @endif
                                        @endforeach
                                    ],
                           },{
                                   label: "Company:",
                                   name: "po.Company"
                           },{
                                 label: "PO Description:",
                                 name: "po.PO_Description",
                                 type: "textarea"
                         },{
                                 label: "PO Status:",
                                 name: "po.PO_Status",
                                 type: "select",
                                 options: [
                                     { label :"", value: "" },
                                     @foreach($options as $option)
                                       @if ($option->Field=="PO_Status")
                                         { label :"{{$option->Option}}", value: "{{$option->Option}}" },
                                       @endif
                                     @endforeach
                                 ],
                         },{
                                 label: "Status:",
                                 name: "po.Status"
                         },{
                                 label: "ROR_Status:",
                                 name: "po.ROR_Status",
                                 type: "select",
                                 options: [
                                     { label :"No", value: "No" },
                                     { label :"Yes", value: "Yes" },
                                 ],
                         },{
                                 label: "Payment Term:",
                                 name: "po.Payment_Term",
                                 type: "select",
                                 options: [
                                     { label :"", value: "" },
                                     { label :"30", value: "30" },
                                     { label :"60", value: "60" },
                                     { label :"90", value: "90" },
                                     { label :"120", value: "120" },
                                     @foreach($options as $option)
                                       @if ($option->Field=="Payment_Term")
                                         { label :"{{$option->Option}}", value: "{{$option->Option}}" },
                                       @endif
                                     @endforeach
                                 ],
                         },{
                                 label: "Cut:",
                                 name: "po.Cut",
                                 type: "select",
                                 options: [
                                     { label :"", value: "" },
                                     { label :"1", value: "1" },
                                     { label :"2", value: "2" },
                                     { label :"3", value: "3" },
                                     { label :"4", value: "4" },
                                     { label :"5", value: "5" },
                                 ],
                         },{
                               label: "Remarks:",
                               name: "po.Remarks",
                               type: "textarea"
                       },{
                              label: "PM Remarks:",
                              name: "po.PM_Remarks",
                              type: "textarea"
                      },{
                             label: "Finance Remarks:",
                             name: "po.Finance_Remarks",
                             type: "textarea"
                     },{
                              label: "Item Description:",
                              name: "po.Item_Description",
                              type: "textarea"
                        },{
                               label: "Scope of Work:",
                               name: "po.Scope_of_Work",
                               type: "textarea"
                        },{
                               label: "Work Order ID:",
                               name: "po.Work_Order_ID"
                        },{
                               label: "Site_ID:",
                               name: "po.Site_ID"
                        },{
                               label: "Amount:",
                               name: "po.Amount",
                               attr: {
                                 type: "number"
                               }
                        },{
                               label: "Amount with GST:",
                               name: "po.Amount_With_GST",
                               attr: {
                                 type: "number"
                               }
                        },{
                               label: "First Milestone Percentage:",
                               name: "po.First_Milestone_Percentage",
                               type: "select",
                               options: [
                                   { label :"0", value: "0" },
                                   { label :"20", value: "20" },
                                   { label :"30", value: "30" },
                                   { label :"70", value: "70" },
                                   { label :"80", value: "80" },
                                   { label :"100", value: "100" }
                               ],
                        },{
                               label: "First Milestone Amount:",
                               name: "po.First_Milestone_Amount",
                               attr: {
                                 type: "number"
                               }
                        },{
                               label: "First Milestone Amount With GST:",
                               name: "po.First_Milestone_Amount_With_GST",
                               attr: {
                                 type: "number"
                               }
                        },{
                               label: "First Milestone Completed Date:",
                               name: "po.First_Milestone_Completed_Date",
                               type:   'datetime',
                               format: 'DD-MMM-YYYY'
                        },{
                               label: "First Milestone Invoice No:",
                               name: "po.First_Milestone_Invoice_No",
                               type: "select",
                               options: [
                                   { label :"", value: "" },
                                   @if($invoices)
                                     @foreach($invoices as $invoice)
                                         { label :"{{$invoice->Invoice_No}}", value: "{{$invoice->Invoice_No}}" },
                                     @endforeach
                                   @endif

                               ],
                        },{
                               label: "First Milestone Invoice Update Date:",
                               name: "po.First_Milestone_Invoice_Upload_Date",
                               type:   'datetime',
                               format: 'DD-MMM-YYYY'
                        },{
                               label: "First Milestone Forecast Invoice Date:",
                               name: "po.First_Milestone_Forecast_Invoice_Date",
                               type:   'datetime',
                               format: 'DD-MMM-YYYY'
                        }


                        ,{
                               label: "Second Milestone Percentage:",
                               name: "po.Second_Milestone_Percentage",
                               type: "select",
                               options: [
                                   { label :"0", value: "0" },
                                   { label :"20", value: "20" },
                                   { label :"30", value: "30" },
                                   { label :"70", value: "70" },
                                   { label :"80", value: "80" },
                                   { label :"100", value: "100" }
                               ],
                        },{
                               label: "Second Milestone Amount:",
                               name: "po.Second_Milestone_Amount",
                               attr: {
                                 type: "number"
                               }
                        },{
                               label: "Second Milestone Amount With GST:",
                               name: "po.Second_Milestone_Amount_With_GST",
                               attr: {
                                 type: "number"
                               }
                        },{
                               label: "Second Milestone Completed Date:",
                               name: "po.Second_Milestone_Completed_Date",
                               type:   'datetime',
                               format: 'DD-MMM-YYYY'
                        },{
                               label: "Second Milestone Invoice No:",
                               name: "po.Second_Milestone_Invoice_No",
                               type: "select",
                               options: [
                                   { label :"", value: "" },
                                   @if($invoices)
                                     @foreach($invoices as $invoice)
                                         { label :"{{$invoice->Invoice_No}}", value: "{{$invoice->Invoice_No}}" },
                                     @endforeach
                                   @endif

                               ],
                        },{
                               label: "Second Milestone Invoice Update Date:",
                               name: "po.Second_Milestone_Invoice_Upload_Date",
                               type:   'datetime',
                               format: 'DD-MMM-YYYY'
                        },{
                               label: "Second Milestone Forecast Invoice Date:",
                               name: "po.Second_Milestone_Forecast_Invoice_Date",
                               type:   'datetime',
                               format: 'DD-MMM-YYYY'
                        }

                        ,{
                               label: "Third Milestone Percentage:",
                               name: "po.Third_Milestone_Percentage",
                               type: "select",
                               options: [
                                   { label :"0", value: "0" },
                                   { label :"20", value: "20" },
                                   { label :"30", value: "30" },
                                   { label :"70", value: "70" },
                                   { label :"80", value: "80" },
                                   { label :"100", value: "100" }
                               ],
                        },{
                               label: "Third Milestone Amount:",
                               name: "po.Third_Milestone_Amount",
                               attr: {
                                 type: "number"
                               }
                        },{
                               label: "Third Milestone Amount With GST:",
                               name: "po.Third_Milestone_Amount_With_GST",
                               attr: {
                                 type: "number"
                               }
                        },{
                               label: "Third Milestone Completed Date:",
                               name: "po.Third_Milestone_Completed_Date",
                               type:   'datetime',
                               format: 'DD-MMM-YYYY'
                        },{
                               label: "Third Milestone Invoice No:",
                               name: "po.Third_Milestone_Invoice_No",
                               type: "select",
                               options: [
                                   { label :"", value: "" },
                                   @if($invoices)
                                     @foreach($invoices as $invoice)
                                         { label :"{{$invoice->Invoice_No}}", value: "{{$invoice->Invoice_No}}" },
                                     @endforeach
                                   @endif

                               ],
                        },{
                               label: "Third Milestone Invoice Update Date:",
                               name: "po.Third_Milestone_Invoice_Upload_Date",
                               type:   'datetime',
                               format: 'DD-MMM-YYYY'
                        },{
                               label: "Third Milestone Forecast Invoice Date:",
                               name: "po.Third_Milestone_Forecast_Invoice_Date",
                               type:   'datetime',
                               format: 'DD-MMM-YYYY'
                        }

                        ,{
                               label: "Fourth Milestone Percentage:",
                               name: "po.Fourth_Milestone_Percentage",
                               type: "select",
                               options: [
                                   { label :"0", value: "0" },
                                   { label :"20", value: "20" },
                                   { label :"30", value: "30" },
                                   { label :"70", value: "70" },
                                   { label :"80", value: "80" },
                                   { label :"100", value: "100" }
                               ],
                        },{
                               label: "Fourth Milestone Amount:",
                               name: "po.Fourth_Milestone_Amount",
                               attr: {
                                 type: "number"
                               }
                        },{
                               label: "Fourth Milestone Amount With GST:",
                               name: "po.Fourth_Milestone_Amount_With_GST",
                               attr: {
                                 type: "number"
                               }
                        },{
                               label: "Fourth Milestone Completed Date:",
                               name: "po.Fourth_Milestone_Completed_Date",
                               type:   'datetime',
                               format: 'DD-MMM-YYYY'
                        },{
                               label: "Fourth Milestone Invoice No:",
                               name: "po.Fourth_Milestone_Invoice_No",
                               type: "select",
                               options: [
                                   { label :"", value: "" },
                                   @if($invoices)
                                     @foreach($invoices as $invoice)
                                         { label :"{{$invoice->Invoice_No}}", value: "{{$invoice->Invoice_No}}" },
                                     @endforeach
                                   @endif

                               ],
                        },{
                               label: "Fourth Milestone Invoice Update Date:",
                               name: "po.Fourth_Milestone_Invoice_Upload_Date",
                               type:   'datetime',
                               format: 'DD-MMM-YYYY'
                        },{
                               label: "Fourth Milestone Forecast Invoice Date:",
                               name: "po.Fourth_Milestone_Forecast_Invoice_Date",
                               type:   'datetime',
                               format: 'DD-MMM-YYYY'
                        }

                        ,{
                               label: "Fifth Milestone Percentage:",
                               name: "po.Fifth_Milestone_Percentage",
                               type: "select",
                               options: [
                                   { label :"0", value: "0" },
                                   { label :"20", value: "20" },
                                   { label :"30", value: "30" },
                                   { label :"70", value: "70" },
                                   { label :"80", value: "80" },
                                   { label :"100", value: "100" }
                               ],
                        },{
                               label: "Fifth Milestone Amount:",
                               name: "po.Fifth_Milestone_Amount",
                               attr: {
                                 type: "number"
                               }
                        },{
                               label: "Fifth Milestone Amount With GST:",
                               name: "po.Fifth_Milestone_Amount_With_GST",
                               attr: {
                                 type: "number"
                               }
                        },{
                               label: "Fifth Milestone Completed Date:",
                               name: "po.Fifth_Milestone_Completed_Date",
                               type:   'datetime',
                               format: 'DD-MMM-YYYY'
                        },{
                               label: "Fifth Milestone Invoice No:",
                               name: "po.Fifth_Milestone_Invoice_No",
                               type: "select",
                               options: [
                                   { label :"", value: "" },
                                   @if($invoices)
                                     @foreach($invoices as $invoice)
                                         { label :"{{$invoice->Invoice_No}}", value: "{{$invoice->Invoice_No}}" },
                                     @endforeach
                                   @endif

                               ],
                        },{
                               label: "Fifth Milestone Invoice Update Date:",
                               name: "po.Fifth_Milestone_Invoice_Upload_Date",
                               type:   'datetime',
                               format: 'DD-MMM-YYYY'
                        },{
                               label: "Fifth Milestone Forecast Invoice Date:",
                               name: "po.Fifth_Milestone_Forecast_Invoice_Date",
                               type:   'datetime',
                               format: 'DD-MMM-YYYY'
                        }


                        ,{
                               label: "PO_Line_No:",
                               name: "po.PO_Line_No"
                        },{
                               label: "Site_Code:",
                               name: "po.Site_Code"
                        },{
                               label: "Site_Name:",
                               name: "po.Site_Name"
                        },{
                               label: "Engineering_No:",
                               name: "po.Engineering_No"
                        },{
                               label: "Quantity_Request:",
                               name: "po.Quantity_Request",
                               attr: {
                                 type: "number"
                               }
                        },{
                               label: "Due_Quantity:",
                               name: "po.Due_Quantity",
                               attr: {
                                 type: "number"
                               }
                        },{
                               label: "Unit:",
                               name: "po.Unit"
                        },{
                               label: "Unit_Price:",
                               name: "po.Unit_Price",
                               attr: {
                                 type: "number"
                               }
                        },{
                               label: "Line_Account:",
                               name: "po.Line_Account",
                               attr: {
                                 type: "number"
                               }
                        },{
                               label: "Start_Date:",
                               name: "po.Start_Date",
                               type:   'datetime',
                               format: 'DD-MMM-YYYY'
                        },{
                               label: "End_Date:",
                               name: "po.End_Date",
                               type:   'datetime',
                               format: 'DD-MMM-YYYY'
                        },{
                               label: "Shipment_Num:",
                               name: "po.Shipment_Num",
                               attr: {
                                 type: "number"
                               }
                        },{
                               label: "Vendor_Code:",
                               name: "po.Vendor_Code"
                        },{
                               label: "Vendor_Name:",
                               name: "po.Vendor_Name"
                        },{
                               label: "Sub_Contractor_No:",
                               name: "po.Sub_Contractor_No"
                        },{
                               label: "Payment_Method:",
                               name: "po.Payment_Method"
                        },{
                               label: "Center_Area:",
                               name: "po.Center_Area"
                        },{
                               label: "ESAR_Document_Submitted_Date:",
                               name: "po.ESAR_Document_Submitted_Date",
                               type:   'datetime',
                               format: 'DD-MMM-YYYY'
                        },{
                               label: "ESAR_Date:",
                               name: "po.ESAR_Date",
                               type:   'datetime',
                               format: 'DD-MMM-YYYY'
                        },{
                               label: "ESAR_Status:",
                               name: "po.ESAR_Status"
                        },{
                               label: "PAC_Document_Submitted_Date:",
                               name: "po.PAC_Document_Submitted_Date",
                               type:   'datetime',
                               format: 'DD-MMM-YYYY'
                        },{
                               label: "PAC_Date:",
                               name: "po.PAC_Date",
                               type:   'datetime',
                               format: 'DD-MMM-YYYY'
                        },{
                               label: "PAC_Status:",
                               name: "po.PAC_Status"
                        }

                    ]
            } );

                         editor.on('open', function () {
                            $('div.DTE_Footer').css( 'text-indent', -1 );
                        });

                         //Activate an inline edit on click of a table cell
                              //  $('#potable').on( 'click', 'tbody td', function (e) {
                              //        editor.inline( this, {
                              //          onBlur: 'submit',
                              //          submit: 'allIfChanged'
                              //      } );
                              //  } );

                               $('#potable').on( 'click', 'tbody td:not(.child), tbody span.dtr-data', function (e) {
                                 editor.inline( this, {
                                onBlur: 'submit',
                                submit: 'allIfChanged'
                                   } );
                               } );

                               oTable=$('#potable').dataTable( {
                                       ajax: {
                                          "url": "{{ asset('/Include/po.php') }}",
                                          "data": {

                                                @if($Ids)
                                                  "Ids":"{{implode(",",$Ids)}}",
                                                  "Type":"All"
                                                @else

                                                @endif


                                          }
                                        },
                                      //   fnRowCallback: function (nRow, aData, iDisplayIndex, iDisplayIndexFull) {
                                       //
                                      //    $('td', nRow).closest('tr').css('color', aData.purchaseorders.PO_Type === "Issue" ? 'red' : 'green');
                                      //    return nRow;
                                      //  },
                                      @if($template=="Digi")

                                        columnDefs: [{ "visible": false, "targets": [3,4,7,12,13,28,29,30,31,32,36,37,38,39,40,41,42,43,44,45,46,47,48,49,50,51,52,53,54,55,56,78,79,80,81,82,83,84,85,86,87,88,89,90,91] },{"className": "dt-center", "targets": "_all"}],

                                      @elseif($template=="Alcatel")

                                        columnDefs: [{ "visible": false, "targets": [3,4,7,12,13,21,28,29,30,31,32,36,37,38,39,40,41,42,43,44,45,46,47,48,49,50,51,52,53,54,55,56,78,79,80,81,82,83,84,85,86,87,88,89,90,91] },{"className": "dt-center", "targets": "_all"}],

                                      @elseif($template=="Huawei")

                                        columnDefs: [{ "visible": false, "targets": [3,4,7,21,71,72,73,74,75,76,77,78,79,80,81,82,83,84,85,86,87,88,89,90] },{"className": "dt-center", "targets": "_all"}],

                                      @else
                                        columnDefs: [{ "visible": false, "targets": [3,4,7,12,13,21,28,29,30,31,32,36,37,38,39,40,41,42,43,44,45,46,47,48,49,50,51,52,53,54,55,56,78,79,80,81,82,83,84,85,86,87,88,89,90,91] },{"className": "dt-center", "targets": "_all"}],

                                      @endif
                                      fnInitComplete: function(oSettings, json) {
                                        $("#totalamount").html("RM " + oTable.api().column( "Amount:name" ).data().sum().toLocaleString("en"));
                                        var outstanding=oTable.api().column( "Amount:name" ).data().sum()-
                                        oTable.api().column( "First_Milestone_Amount:name" ).data().sum()-
                                        oTable.api().column( "Second_Milestone_Amount:name" ).data().sum()-
                                        oTable.api().column( "Third_Milestone_Amount:name" ).data().sum()-
                                        oTable.api().column( "Fourth_Milestone_Amount:name" ).data().sum()-
                                        oTable.api().column( "Fifth_Milestone_Amount:name" ).data().sum()
                                        $("#totaloutstanding").html("RM " + outstanding.toLocaleString("en"));

                                       },

                                       responsive: false,
                                       colReorder: false,
                                       sScrollX: "100%",
                                       bScrollCollapse: true,
                                       bAutoWidth: true,
                                       sScrollY: "100%",
                                       rowId: "po.Id",
                                       aaSorting: [[4,"asc"]],
                                       dom: "Blrtip",
                                       columns: [
                                             {
                                               sortable: false,
                                               "render": function ( data, type, full, meta ) {
                                                 if (full.po.PM_Status=="Accepted" && full.finance.Finance_Status=="Accepted")
                                                 {
                                                   return '-';

                                                 }
                                                 else {
                                                   return '<input type="checkbox" name="selectrow" id="selectrow" class="selectrow" value="'+full.po.Id+'" onclick="uncheck()">';

                                                 }

                                               }

                                             },
                                             {
                                               title:"Action",
                                               sortable: false,
                                               "render": function ( data, type, full, meta ) {

                                                    return '<a href="{{ url('/PO2') }}/'+full.po.PO_No+'" >View</a>';

                                               }
                                             },
                                              {  data: null,"render":"", title:"No"},
                                               { data: "po.Id",title:"Id"},
                                               { data: "po.Huawei_ID",title:"Huawei_ID"},
                                               { data: "po.PO_Status",title:"PO_Status" },
                                               { data: "po.Status",title:"Status" },
                                               { data: "po.ROR_Status",title:"ROR_Status" },

                                               { data: "po.PO_No",title:"PO_No" },
                                               { data: "po.PR_No",title:"PR_No" },
                                               { data: "po.Cut",title:"Cut" },
                                               { data: "po.PO_Line_No",title:"PO_Line_No" },
                                               { data: "po.Shipment_Num",title:"Shipment_Num" },//16
                                               { data: "po.Item_Code",title:"Item_Code" },
                                               { data: "po.Credit_Note",title:"Credit_Note" },

                                               { data: "po.PO_Date",title:"PO_Date" },
                                               { data: "po.PO_Type",title:"PO_Type" },

                                               { data: "po.PO_Description",title:"PO_Description" },
                                               { data: "po.Scope_of_Work", title:"Scope_of_Work"},
                                               { data: "po.Item_Description", title:"Item_Description" },
                                               { data: "po.Company",title:"Company" },
                                               { data: "po.Work_Order_ID",title:"Work_Order_ID" },
                                               { data: "po.Site_ID", title:"Site_ID" },
                                               { data: "po.Site_Code",title:"Site_Code" },
                                               { data: "po.Site_Name",title:"Site_Name" },
                                               { data: "po.Region",title:"Region" },
                                               { data: "po.Payment_Term",title:"Payment_Term" },
                                               { data: "po.Payment_Method",title:"Payment_Method" },
                                               { data: "po.Engineering_No",title:"Engineering_No" },
                                               { data: "po.Center_Area",title:"Center_Area" },
                                               { data: "po.Due_Quantity",title:"Due_Quantity" },
                                               { data: "po.Quantity_Request",title:"Quantity_Request" },
                                               { data: "po.Unit",title:"Unit" },
                                               { data: "po.Unit_Price",title:"Unit_Price" },
                                               { data: "po.Amount", title:"Amount", name:"Amount" },
                                               { data: "po.Amount_With_GST", title:"Amount_With_GST" },
                                               { data: "po.Line_Account",title:"Line_Account" },
                                               { data: "po.Start_Date",title:"Start_Date" },
                                               { data: "po.End_Date",title:"End_Date" },
                                               { data: "po.Acceptance_Date",title:"Acceptance_Date" },
                                               { data: "po.Vendor_Code",title:"Vendor_Code" },
                                               { data: "po.Vendor_Name",title:"Vendor_Name" },
                                                { data: "po.Sub_Contract_No",title:"Sub_Contract_No" },

                                                { data: "po.ESAR_Document_Submitted_Date",title:"ESAR_Document_Submitted_Date" },
                                                { data: "po.ESAR_Date",title:"ESAR_Date" },
                                                { data: "po.ESAR_Status",title:"ESAR_Status" },

                                                { data: "po.PAC_Document_Submitted_Date",title:"PAC_Document_Submitted_Date" },
                                                { data: "po.PAC_Date",title:"PAC_Date" },
                                                { data: "po.PAC_Status",title:"PAC_Status" },

                                               { data: "pm.Name", title:"PM" },
                                               { data: "po.PM_Accepted_At",title:"PM_Accepted_At" },
                                               { data: "po.PM_Status",title:"PM_Status" },
                                               { data: "po.PM_Remarks",title:"PM_Remarks" },

                                               { data: "finance.Name", title:"Finance" },
                                               { data: "po.Finance_Accepted_At",title:"Finance_Accepted_At" },
                                               { data: "po.Finance_Status",title:"Finance_Status" },
                                               { data: "po.Finance_Remarks",title:"Finance_Remarks" },

                                               { data: "po.First_Milestone_Percentage",title:"First_Milestone_Percentage" },
                                               { data: "po.First_Milestone_Amount",title:"First_Milestone_Amount",name:"First_Milestone_Amount" },
                                                { data: "po.First_Milestone_Amount_With_GST",title:"First_Milestone_Amount_With_GST" },
                                               { data: "po.First_Milestone_Completed_Date",title:"First_Milestone_Completed_Date" },
                                               { data: "po.First_Milestone_Invoice_No",title:"First_Milestone_Invoice_No" },
                                                { data: "po.First_Milestone_Invoice_Upload_Date",title:"First_Milestone_Invoice_Upload_Date" },
                                               { data: "po.First_Milestone_Forecast_Invoice_Date",title:"First_Milestone_Forecast_Invoice_Date" },

                                               { data: "po.Second_Milestone_Percentage",title:"Second_Milestone_Percentage" },
                                               { data: "po.Second_Milestone_Amount",title:"Second_Milestone_Amount",name:"Second_Milestone_Amount" },
                                                { data: "po.Second_Milestone_Amount_With_GST",title:"Second_Milestone_Amount_With_GST" },
                                               { data: "po.Second_Milestone_Completed_Date",title:"Second_Milestone_Completed_Date" },
                                               { data: "po.Second_Milestone_Invoice_No",title:"Second_Milestone_Invoice_No" },
                                                { data: "po.Second_Milestone_Invoice_Upload_Date",title:"Second_Milestone_Invoice_Upload_Date" },
                                               { data: "po.Second_Milestone_Forecast_Invoice_Date",title:"Second_Milestone_Forecast_Invoice_Date" },

                                               { data: "po.Third_Milestone_Percentage",title:"Third_Milestone_Percentage" },
                                               { data: "po.Third_Milestone_Amount",title:"Third_Milestone_Amount",name:"Third_Milestone_Amount" },
                                                { data: "po.Third_Milestone_Amount_With_GST",title:"Third_Milestone_Amount_With_GST" },
                                               { data: "po.Third_Milestone_Completed_Date",title:"Third_Milestone_Completed_Date" },
                                               { data: "po.Third_Milestone_Invoice_No",title:"Third_Milestone_Invoice_No" },
                                                { data: "po.Third_Milestone_Invoice_Upload_Date",title:"Third_Milestone_Invoice_Upload_Date" },
                                               { data: "po.Third_Milestone_Forecast_Invoice_Date",title:"Third_Milestone_Forecast_Invoice_Date" },

                                               { data: "po.Fourth_Milestone_Percentage",title:"Fourth_Milestone_Percentage" },
                                               { data: "po.Fourth_Milestone_Amount",title:"Fourth_Milestone_Amount",name:"Fourth_Milestone_Amount" },
                                                { data: "po.Fourth_Milestone_Amount_With_GST",title:"Fourth_Milestone_Amount_With_GST" },
                                               { data: "po.Fourth_Milestone_Completed_Date",title:"Fourth_Milestone_Completed_Date" },
                                               { data: "po.Fourth_Milestone_Invoice_No",title:"Fourth_Milestone_Invoice_No" },
                                                { data: "po.Fourth_Milestone_Invoice_Upload_Date",title:"Fourth_Milestone_Invoice_Upload_Date" },
                                               { data: "po.Fourth_Milestone_Forecast_Invoice_Date",title:"Fourth_Milestone_Forecast_Invoice_Date" },

                                               { data: "po.Fifth_Milestone_Percentage",title:"Fifth_Milestone_Percentage" },
                                               { data: "po.Fifth_Milestone_Amount",title:"Fifth_Milestone_Amount",name:"Fifth_Milestone_Amount" },
                                                { data: "po.Fifth_Milestone_Amount_With_GST",title:"Fifth_Milestone_Amount_With_GST" },
                                               { data: "po.Fifth_Milestone_Completed_Date",title:"Fifth_Milestone_Completed_Date" },
                                               { data: "po.Fifth_Milestone_Invoice_No",title:"Fifth_Milestone_Invoice_No" },
                                                { data: "po.Fifth_Milestone_Invoice_Upload_Date",title:"Fifth_Milestone_Invoice_Upload_Date" },
                                               { data: "po.Fifth_Milestone_Forecast_Invoice_Date",title:"Fifth_Milestone_Forecast_Invoice_Date" },


                                               { data: "users.Name", editField: "po.created_by",title:"Created_By" },
                                               { data: "po.Remarks",title:"Remarks"}

                                       ],
                                       autoFill: {
                                          editor:  editor,
                                      },
                                      // keys: {
                                      //     columns: ':not(:first-child)',
                                      //     editor:  editor
                                      // },
                                       select: true,
                                       buttons: [
                                                 {
                                                  text: 'New PO',
                                                  action: function ( e, dt, node, config ) {
                                                      // clearing all select/input options
                                                      editor
                                                         .create( false )
                                                         .submit();
                                                  },
                                                },
                                               { extend: "remove", editor: editor },
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

                           editor.on( 'postEdit', function ( e, json, data ) {

                             $("#totalamount").html("RM " + oTable.api().column( "Amount:name" ).data().sum().toLocaleString("en"));
                             var outstanding=oTable.api().column( "Amount:name" ).data().sum()-
                             oTable.api().column( "First_Milestone_Amount:name" ).data().sum()-
                             oTable.api().column( "Second_Milestone_Amount:name" ).data().sum()-
                             oTable.api().column( "Third_Milestone_Amount:name" ).data().sum()-
                             oTable.api().column( "Fourth_Milestone_Amount:name" ).data().sum()-
                             oTable.api().column( "Fifth_Milestone_Amount:name" ).data().sum()
                              $("#totaloutstanding").html("RM " + outstanding.toLocaleString("en"));

                            } );

                            $('#potable').on( 'search.dt', function () {

                              var rows=oTable.api().rows( { search: 'applied' } ).data().toArray();
                              var totalamount=0;
                              var totaloutstanding=0;

                              for (var i = 0; i < rows.length; i++) {

                                totalamount=totalamount+parseFloat(rows[i].po.Amount);
                                totaloutstanding=totaloutstanding+(parseFloat(rows[i].po.Amount)-parseFloat(rows[i].po.First_Milestone_Amount)-parseFloat(rows[i].po.Second_Milestone_Amount)-parseFloat(rows[i].po.Third_Milestone_Amount)-parseFloat(rows[i].po.Fourth_Milestone_Amount)-parseFloat(rows[i].po.Fifth_Milestone_Amount))

                              }

                              $("#totalamount").html("RM " + totalamount.toLocaleString("en"));
                              $("#totaloutstanding").html("RM " + totaloutstanding.toLocaleString("en"));

                            } );

                           oTable.api().on( 'order.dt search.dt', function () {
                               oTable.api().column(2, {search:'applied', order:'applied'}).nodes().each( function (cell, i) {
                                   cell.innerHTML = i+1;
                               } );
                           } ).draw();

                          //  $('#potable').on( 'click', 'tr', function () {
                          //    // Get the rows id value
                          //   //  var row=$(this).closest("tr");
                          //   //  var oTable = row.closest('table').dataTable();
                          //    userid = oTable.api().row( this ).data().purchaseorders.Id;
                          //  });

                           $(".potable thead input").keyup ( function () {

                                   /* Filter on the column (the index) of this element */
                                   if ($('#potable').length > 0)
                                   {

                                       var colnum=document.getElementById('potable').rows[0].cells.length;

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

                             $('a[data-toggle="tab"]').on( 'shown.bs.tab', function (e) {
                               var target = $(e.target).attr("href") // activated tab

                                 $.fn.dataTable.tables( {visible: true, api: true} ).columns.adjust();

                             } );

                             $("#ajaxloader").hide();

                       } );


                   </script>

@endsection

@section('content')

    <div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
      <h1>
        PO Management
        <small>Sales Management</small>
      </h1>
      <ol class="breadcrumb">
        <li><a href="{{ url('/home') }}"><i class="fa fa-dashboard"></i> Home</a></li>
        <li><a href="#">Sales Management</a></li>
        <li><a href="#">PO Management</a></li>
      </ol>
    </section>

    <!-- Main content -->
    <section class="content">
      <div class="row">

        <div class="modal fade" id="ImportData" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
          <div class="modal-dialog" role="document">
            <div class="modal-content">
              <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title" id="myModalLabel">Import PO</h4>
              </div>
              <div class="modal-body">
                <div class="form-group">
                  <form enctype="multipart/form-data" id="upload_form" role="form" method="POST" action="" >
                    <input type="file" id="import" name="import">
                  </form>
                </div>
              </div>
              <div class="modal-footer">
                <center><img src="{{ URL::to('/') ."/img/ajax-loader.gif" }}" width="50px" height="50px" alt="Loading" name='ajaxloader' id="ajaxloader"></center>
                <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                <button type="button" class="btn btn-primary" onclick="importdata()">Import</button>
              </div>
            </div>
          </div>
        </div>

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

          <div class="box-body">

      <div class="col-md-1">
        <label>Filter : </label>

      </div>

      <div class="col-md-2">

        <div class="form-group">
          <select class="form-control select2" id="filter">
            <option <?php if($type=="All PO") echo ' selected="selected" '; ?>>All PO</option>
            <option <?php if($type=="Accepted PO") echo ' selected="selected" '; ?>>Accepted PO</option>
            <option <?php if($type=="Pending PM Acceptance") echo ' selected="selected" '; ?>>Pending PM Acceptance</option>
            <option <?php if($type=="Pending Finance Acceptance") echo ' selected="selected" '; ?>>Pending Finance Acceptance</option>
            <option <?php if($type=="Rejected PO") echo ' selected="selected" '; ?>>Rejected PO</option>

            <option <?php if($type=="Ready for ESAR") echo ' selected="selected" '; ?>>Ready for ESAR</option>
            <option <?php if($type=="Ready for PAC") echo ' selected="selected" '; ?>>Ready for PAC</option>

        </select>
      </div>

    </div>

    <div class="col-md-1">
      <label>Template : </label>

    </div>

    <div class="col-md-2">

      <div class="form-group">
        <select class="form-control select2" id="potemplate">
          <option <?php if($template == "General") echo ' selected="selected" '; ?>>General</option>
          <option <?php if($template == "Alcatel") echo ' selected="selected" '; ?>>Alcatel</option>
          <option <?php if($template == "Digi") echo ' selected="selected" '; ?>>Digi</option>
          <option <?php if($template == "Huawei") echo ' selected="selected" '; ?>>Huawei</option>

      </select>
    </div>

  </div>

        <div class="col-md-1">
            <div class="input-group">
              <button type="button" class="btn btn-success btn" data-toggle="modal" onclick="refresh();">Refresh</button>
            </div>
        </div>

        <a data-toggle="modal" data-target="#ImportData"><button type="button" class="pull-right btn btn-warning" >Import PO</button></a>

          </div>

              @if($type=="Accepted PO")

                <button type="button" class="btn btn-danger btn-lg" data-toggle="modal" onclick="updateaccept('Finance Reject')">Finance Reject</button>

              @elseif($type=="Pending PM Acceptance")

                <button type="button" class="btn btn-success btn-lg" data-toggle="modal" onclick="updateaccept('PM Accept')">PM Accept</button>
                <button type="button" class="btn btn-danger btn-lg" data-toggle="modal" onclick="updateaccept('PM Reject')">PM Reject</button>

              @elseif($type=="Pending Finance Acceptance")

                <button type="button" class="btn btn-primary btn-lg" data-toggle="modal" onclick="updateaccept('Finance Accept')">Finance Accept</button>
                <button type="button" class="btn btn-danger btn-lg" data-toggle="modal" onclick="updateaccept('Finance Reject')">Finance Reject</button>

              @elseif($type=="Rejected PO")

                <button type="button" class="btn btn-success btn-lg" data-toggle="modal" onclick="updateaccept('PM Accept')">PM Accept</button>
                <button type="button" class="btn btn-primary btn-lg" data-toggle="modal" onclick="updateaccept('Finance Accept')">Finance Accept</button>

              @elseif($type=="All PO")

              @elseif($type=="Ready for ESAR")

                <button type="button" class="btn btn-primary btn-lg" data-toggle="modal" onclick="esar()">Proceed ESAR (Old)</button>
                <button type="button" class="btn btn-primary btn-lg" data-toggle="modal" onclick="esar1()">Proceed ESAR (New)</button>

              @elseif($type=="Ready for PAC")

                <button type="button" class="btn btn-primary btn-lg" data-toggle="modal" onclick="pac()">Proceed PAC</button>

              @endif

              <br>
              <br>

              @if($poitem)

                <div class="col-lg-2 col-xs-6">
                  <!-- small box -->
                  <div class="small-box bg-red">
                    <div class="inner">
                      <h3>{{sizeof($poitem)}}</h3>
                      <p>Pending</p>
                    </div>
                    <div class="icon">
                      <i class="ion ion-clock"></i>
                    </div>
                      <a href="{{ url('/POs/') }}/{{implode(",",$poitem)}}" class="small-box-footer">More info <i class="fa fa-arrow-circle-right"></i></a>
                    </div>
                </div>

              @endif

              <br>

              <div class="col-md-10">


                    <b>Total Amount</b> : <i><span id='totalamount'>RM0.00</span></i><br>

                    <b>Total Outstanding Balance</b> : <i><span id='totaloutstanding'>RM0.00</span></i><br>
                <br>

              </div>

                <br><br>

                <table id="potable" class="potable" cellspacing="0" width="100%" padding="30px" style="font-size: 13px;">

                            <thead>
                        <tr class="search">

                        @foreach($po as $key=>$value2)

                          @if ($key==0)

                            <?php $i = 0; ?>

                            @foreach($value2 as $field=>$a)

                                @if ($i==0)
                                  <th align='center'><input type='hidden' class='search_init' name='{{$i}}' placemark='{{$a}}'></th>
                                @elseif ($i==1)
                                  <th align='center'><input type='hidden' class='search_init' name='{{$i}}' placemark='{{$a}}'></th>
                                @else
                                  <th align='center'><input type='text' class='search_init' name='{{$i}}'  placemark='{{$a}}'></th>
                                @endif

                                <?php $i ++; ?>
                            @endforeach

                            <th align='center'><input type='text' class='search_init' name='{{$i}}'  placemark='{{$a}}'></th>
                            <th align='center'><input type='text' class='search_init' name='{{$i}}'  placemark='{{$a}}'></th>
                            <th align='center'><input type='text' class='search_init' name='{{$i}}'  placemark='{{$a}}'></th>

                          @endif

                        @endforeach

                      </tr>

                        <tr>
                          @foreach($po as $key=>$value2)

                            @if ($key==0)
                                  <td><input type="checkbox" name="selectall" id="selectall" value="all" onclick="checkall()"></td>
                                  <td></td>
                                  <td></td>
                              @foreach($value2 as $field=>$value)
                                @if(str_contains($field,"First_Milestone"))
                                  <td class="first">
                                      {{ $field }}
                                  </td>
                                @elseif(str_contains($field,"Second_Milestone"))
                                  <td class="second">
                                      {{ $field }}
                                  </td>
                                @elseif(str_contains($field,"Third_Milestone"))
                                  <td class="third">
                                      {{ $field }}
                                  </td>

                                @elseif(str_contains($field,"Fourth_Milestone"))
                                  <td class="fourth">
                                      {{ $field }}
                                  </td>

                                @elseif(str_contains($field,"Fifth_Milestone"))
                                  <td class="fifth">
                                      {{ $field }}
                                  </td>

                                @elseif(starts_with($field,"PM"))
                                  <td class="PM">
                                      {{ $field }}
                                  </td>

                                @elseif(starts_with($field,"Finance"))
                                  <td class="Finance">
                                      {{ $field }}
                                  </td>

                                @else
                                  <td>
                                      {{ $field }}
                                  </td>

                                @endif
                              @endforeach

                            @endif

                          @endforeach
                        </tr>

                    </thead>
                    <tbody>


                  </tbody>
                    <tfoot></tfoot>
                      </table>

        <!-- /.av-tabs-custom -->

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

  <script>

    function importdata() {

        $.ajaxSetup({
           headers: { 'X-CSRF-Token' : $('meta[name=_token]').attr('content') }
        });

        $("#ajaxloader").show();

        $.ajax({

                    url: "{{ url('/PO/importpo') }}",
                    method: "POST",
                    contentType: false,
                    processData: false,
                    data:new FormData($("#upload_form")[0]),

                    success: function(response){
                      if (response==0)
                      {
                        var message ="Failed to import data!";
                        $("#warning-alert ul").html(message);
                        $("#warning-alert").modal('show')

                        $('#ImportData').modal('hide')
                        $("#ajaxloader").hide();

                      }
                      else {

                        var message ="Data imported!";
                        $("#update-alert ul").html(message);
                        $("#update-alert").modal('show')

                        setTimeout(function() {
                          window.location.reload();
                        }, 3000);

                        $('#ImportData').modal('hide')
                        $("#ajaxloader").hide();

                      }

            }
        });

    }

    function refresh()
    {

      var id = document.getElementById("mySelect").value;
      var filter=document.getElementById("filter").value;
      var template=document.getElementById("potemplate").value;

      if(id=="")
      {
        id=0;
      }

      window.location.href ="{{ url("/PO") }}/null/null/"+id+"/"+filter+"/"+template;

    }

    function uncheck()
    {

      if (!$("#selectrow").is(':checked')) {
        $("#selectall").prop("checked", false)
      }

    }

    function checkall()
    {
      var allPages = oTable.fnGetNodes();
  // alert(document.getElementById("selectall").checked);
      if ($("#selectall").is(':checked')) {

         $('input[type="checkbox"]', allPages).prop('checked', true);
          // $(".selectrow").prop("checked", true);
           $(".selectrow").trigger("change");
           oTable.api().rows().select();
      } else {

          $('input[type="checkbox"]', allPages).prop('checked', false);
          $(".selectrow").trigger("change");
           oTable.api().rows().deselect();
      }
    }

    function updateaccept(status) {

      var boxes = $('input[type="checkbox"]:checked', oTable.fnGetNodes() );

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

        $.ajax({
                    url: "{{ url('/PO/updateaccept') }}",
                    method: "POST",
                    data: {POIds:ids,
                    Status:status},

                    success: function(response){

                      if (response==1)
                      {

                          oTable.api().ajax.url("{{ asset('/Include/po.php') }}").load();

                          var message="Status updated!";
                          $("#update-alert ul").html(message);
                          $("#update-alert").modal('show');

                      }
                      else {

                        var errormessage="Failed to update status!";
                        $("#error-alert ul").html(errormessage);
                        $("#error-alert").modal('show');

                      }

            }
        });

    }
    else {
      var errormessage="No PO selected!";
      $("#error-alert ul").html(errormessage);
      $("#error-alert").modal('show');

    }

  }

  function esar() {

    var boxes = $('input[type="checkbox"]:checked', oTable.fnGetNodes() );

    var ids="";

    if (boxes.length>0)
    {
      for (var i = 0; i < boxes.length; i++) {
        ids+=boxes[i].value+"|";
      }

      ids=ids.substring(0, ids.length-1);

      var win = window.open("{{ url('/esar') }}/"+ids, '_blank');
      win.focus();
    }

}

function esar1() {

  var boxes = $('input[type="checkbox"]:checked', oTable.fnGetNodes() );

  var ids="";

  if (boxes.length>0)
  {
    for (var i = 0; i < boxes.length; i++) {
      ids+=boxes[i].value+"|";
    }

    ids=ids.substring(0, ids.length-1);

    var win = window.open("{{ url('/esar1') }}/"+ids, '_blank');
    win.focus();
  }

}

function pac() {

  var boxes = $('input[type="checkbox"]:checked', oTable.fnGetNodes() );

  var ids="";

  if (boxes.length>0)
  {
    for (var i = 0; i < boxes.length; i++) {
      ids+=boxes[i].value+"|";
    }

    ids=ids.substring(0, ids.length-1);

    var win = window.open("{{ url('/pac') }}/"+ids, '_blank');
    win.focus();
  }

}


  </script>

@endsection
