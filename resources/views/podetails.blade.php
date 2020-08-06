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

      div.DTE_Field_Type_textarea textarea {
        padding: 3px;
        width: auto;
        height: auto;
        min-width: 600px;
        min-height: 100px;
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

      <script type="text/javascript" language="javascript" src="{{ asset('/plugin/datatables/extensions/Autocomplete/editor.autoComplete.js') }}"></script>

      {{-- <script type="text/javascript" language="javascript" src="{{ asset('/plugin/examples/resources/syntax/shCore.js') }}"></script>
      <script type="text/javascript" language="javascript" src="{{ asset('/plugin/examples/resources/demo.js') }}"></script>
      <script type="text/javascript" language="javascript" src="{{ asset('/plugin/examples/resources/editor-demo.js') }}"></script> --}}

    <script>

      var editor;
      var table;
      $(document).ready(function() {

                     editor = new $.fn.dataTable.Editor( {
                       ajax: {
                          "url": "{{ asset('/Include/po.php') }}",
                          "data": {
                                  "PO_No": "{{$po_no}}"

                          }
                        },
                        formOptions: {
                             bubble: {
                                 submit: 'allIfChanged'
                             }
                         },
                             table: "#pendingtable",
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
                                  label: "Project Code:",
                                  name: "po.Project_Code",
                                  type:  'autoComplete',
                                  "opts": {
                                    "source": [
                                      // array of genres...
                                      @if($projectcodes)
                                        @foreach($projectcodes as $projectcode)

                                        { label :"{{$projectcode->Project_Code}} - {{$projectcode->Site_ID}}", value: "{{$projectcode->Project_Code}}" },

                                        @endforeach
                                      @endif
                                    ]
                                  },
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
                                  label: "Project:",
                                  name: "po.Project"
                           },{
                                  label: "ProjectCode:",
                                  name: "po.ProjectCode"
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


                     // Activate an inline edit on click of a table cell
                           $('#pendingtable').on( 'click', 'tbody td:not(.child), tbody', function (e) {
                                 editor.inline( this, {
                                   onBlur: 'submit',
                                   submit: 'allIfChanged'
                               } );
                           } );

                           editor.on( 'postEdit', function ( e, json, data ) {

                             $("#total").html("RM " + table.api().column( "Total_Amount:name" ).data().sum().toFixed(2));

                            } );

                           table=$('#pendingtable').dataTable( {
                             ajax: {
                                "url": "{{ asset('/Include/po.php') }}",
                                "data": {
                                        "PO_No": "{{$po_no}}"

                                }
                              },
                                  rowId: 'po.Id',
                                   columnDefs: [{ "visible": false, "targets": [1] },{"className": "dt-center", "targets": "_all"}],

                                   bAutoWidth: true,
                                   dom: "Brti",
                                   scrollY: "100%",
                                   scrollX: "100%",
                                   scrollCollapse: true,
                                   iDisplayLength:10,
                                   bAutoWidth: true,
                                   fnInitComplete: function(oSettings, json) {
                                     $("#total").html("RM " + table.api().column( "Total_Amount:name" ).data().sum().toFixed(2));
                                    },
                                   columns: [

                                          {  data: null,"render":"", title:"No"},
                                           { data: "po.Id",title:"Id"},
                                           { data: "po.Huawei_ID",title:"Huawei_ID"},
                                           { data: "projects.Project_Name", editField: "po.ProjectId",title:"Project_Name" },
                                           { data: "po.Project",title:"Project" },
                                            { data: "po.Project_Code", title:"Project_Code" },
                                           { data: "po.PO_Status",title:"PO_Status" },
                                           { data: "po.Status",title:"Status" },
                                           { data: "po.ROR_Status",title:"ROR_Status" },
                                           { data: "po.ProjectCode",title:"ProjectCode" },

                                           { data: "po.PO_No",title:"PO_No" },
                                           { data: "po.PR_No",title:"PR_No" },
                                           { data: "po.Cut",title:"Cut" },
                                           { data: "po.PO_Line_No",title:"PO_Line_No" },
                                           { data: "po.Shipment_Num",title:"Shipment_Num" },
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
                                           { data: "po.Amount", title:"Amount" ,name:"Total_Amount"},
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
                                           { data: "po.First_Milestone_Amount",title:"First_Milestone_Amount" },
                                            { data: "po.First_Milestone_Amount_With_GST",title:"First_Milestone_Amount_With_GST" },
                                           { data: "po.First_Milestone_Completed_Date",title:"First_Milestone_Completed_Date" },
                                           { data: "po.First_Milestone_Invoice_No",title:"First_Milestone_Invoice_No" },
                                            { data: "po.First_Milestone_Invoice_Upload_Date",title:"First_Milestone_Invoice_Upload_Date" },
                                           { data: "po.First_Milestone_Forecast_Invoice_Date",title:"First_Milestone_Forecast_Invoice_Date" },

                                           { data: "po.Second_Milestone_Percentage",title:"Second_Milestone_Percentage" },
                                           { data: "po.Second_Milestone_Amount",title:"Second_Milestone_Amount" },
                                            { data: "po.Second_Milestone_Amount_With_GST",title:"Second_Milestone_Amount_With_GST" },
                                           { data: "po.Second_Milestone_Completed_Date",title:"Second_Milestone_Completed_Date" },
                                           { data: "po.Second_Milestone_Invoice_No",title:"Second_Milestone_Invoice_No" },
                                            { data: "po.Second_Milestone_Invoice_Upload_Date",title:"Second_Milestone_Invoice_Upload_Date" },
                                           { data: "po.Second_Milestone_Forecast_Invoice_Date",title:"Second_Milestone_Forecast_Invoice_Date" },

                                           { data: "po.Third_Milestone_Percentage",title:"Third_Milestone_Percentage" },
                                           { data: "po.Third_Milestone_Amount",title:"Third_Milestone_Amount" },
                                            { data: "po.Third_Milestone_Amount_With_GST",title:"Third_Milestone_Amount_With_GST" },
                                           { data: "po.Third_Milestone_Completed_Date",title:"Third_Milestone_Completed_Date" },
                                           { data: "po.Third_Milestone_Invoice_No",title:"Third_Milestone_Invoice_No" },
                                            { data: "po.Third_Milestone_Invoice_Upload_Date",title:"Third_Milestone_Invoice_Upload_Date" },
                                           { data: "po.Third_Milestone_Forecast_Invoice_Date",title:"Third_Milestone_Forecast_Invoice_Date" },

                                           { data: "po.Fourth_Milestone_Percentage",title:"Fourth_Milestone_Percentage" },
                                           { data: "po.Fourth_Milestone_Amount",title:"Fourth_Milestone_Amount" },
                                            { data: "po.Fourth_Milestone_Amount_With_GST",title:"Fourth_Milestone_Amount_With_GST" },
                                           { data: "po.Fourth_Milestone_Completed_Date",title:"Fourth_Milestone_Completed_Date" },
                                           { data: "po.Fourth_Milestone_Invoice_No",title:"Fourth_Milestone_Invoice_No" },
                                            { data: "po.Fourth_Milestone_Invoice_Upload_Date",title:"Fourth_Milestone_Invoice_Upload_Date" },
                                           { data: "po.Fourth_Milestone_Forecast_Invoice_Date",title:"Fourth_Milestone_Forecast_Invoice_Date" },

                                           { data: "po.Fifth_Milestone_Percentage",title:"Fifth_Milestone_Percentage" },
                                           { data: "po.Fifth_Milestone_Amount",title:"Fifth_Milestone_Amount" },
                                            { data: "po.Fifth_Milestone_Amount_With_GST",title:"Fifth_Milestone_Amount_With_GST" },
                                           { data: "po.Fifth_Milestone_Completed_Date",title:"Fifth_Milestone_Completed_Date" },
                                           { data: "po.Fifth_Milestone_Invoice_No",title:"Fifth_Milestone_Invoice_No" },
                                            { data: "po.Fifth_Milestone_Invoice_Upload_Date",title:"Fifth_Milestone_Invoice_Upload_Date" },
                                           { data: "po.Fifth_Milestone_Forecast_Invoice_Date",title:"Fifth_Milestone_Forecast_Invoice_Date" },


                                           { data: "users.Name", editField: "po.created_by",title:"Created_By" },
                                           { data: "po.Remarks",title:"Remarks"}

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
                                     {
                                       text: 'New Item',
                                       action: function ( e, dt, node, config ) {
                                           // clearing all select/input options
                                           editor
                                              .create( false )
                                              .set( 'po.PO_No', "{{ $po[0]->PO_No }}" )
                                              .set( 'po.PO_Type', "{{ $po[0]->PO_Type }}")
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

                       table.api().on( 'order.dt search.dt', function () {
                           table.api().column(0, {search:'applied', order:'applied'}).nodes().each( function (cell, i) {
                               cell.innerHTML = i+1;
                           } );
                       } ).draw();

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
        PO Line Items
        <small>Sales Management</small>
      </h1>
      <ol class="breadcrumb">
        <li><a href="{{ url('/home') }}"><i class="fa fa-dashboard"></i> Home</a></li>
        <li><a href="#">Sales Management</a></li>
        <li><a href="{{ url('/PO/') }}">PO Management</a></li>
        <li class="active">PO Details</li>
      </ol>
    </section>

    <!-- Main content -->
    <section class="content">
      <div class="row">
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

        <div class="col-md-4">

          <div class="box box-primary">

            <div class="box-body box-profile">

              <ul class="list-group list-group-unbordered">

                <li class="list-group-item">
                  <b>PO No</b> : <p class="pull-right"><i>{{ $po[0]->PO_No }}</i></p>
                </li>
                <li class="list-group-item">
                  <b>PO Date</b> : <p class="pull-right"><i>{{ $po[0]->PO_Date }}</i></p>
                </li>
                <li class="list-group-item">
                  <b>PO Type</b> : <p class="pull-right"><i><span id='status'>{{ $po[0]->PO_Type }}</span></i></p>
                </li>
                <li class="list-group-item">
                  <b>Company</b> : <p class="pull-right"><i><span id='status'>{{ $po[0]->Company }}</span></i></p>
                </li>
                <li class="list-group-item">
                  <b>Payment Term</b> : <br><i>{{ $po[0]->Payment_Term }}</i>
                </li>
                <li class="list-group-item">
                  <b>Description</b> : <br><i>{{ $po[0]->PO_Description }}</i>
                </li>
                <li class="list-group-item">
                  <b>PO Amount</b> : <p class="pull-right"><i><span id='total'>{{$po[0]->Amount}}</span></i></p>
                </li>
                <li class="list-group-item">
                  <b>Status</b> : <p class="pull-right"><i>{{ $po[0]->PO_Status }}</i></p>
                </li>

              </ul>

              {{-- <a href="#" class="btn btn-primary btn-block"><b>Update</b></a> --}}
            </div>

          </div>
        </div>

         <div class="col-md-8">
          <div class="box box-primary">
              <div class="box-header with-border">
                <div class="row">
                  <div class="col-md-4">
                    Attachment <p class="text-muted">[PNG, JPG and PDF file only]</p>
                  </div>

               <div class="col-md-4">
                    <br>

                      <div class="form-group">
                        <form enctype="multipart/form-data" id="upload_form" role="form" method="POST" action="" >
                          <input type="hidden" class="form-control" id="purchaseorderId" name="purchaseorderId" value="{{$po[0]->Id}}">
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

                  @if($receipts)

                      @foreach ($receipts as $receipt)

                        @if(strpos($receipt->Web_Path,'.png') !== false || strpos($receipt->Web_Path,'.jpg') !== false || strpos($receipt->Web_Path,'.jpeg') !== false)

                          <div class="col-sm-3" id="receipt{{ $receipt->Id }}">
                            <a download="{{ url($receipt->Web_Path) }}" href="{{ url($receipt->Web_Path) }}" title="Download">
                              <img class="img-responsive" src="{{ url($receipt->Web_Path) }}"  alt="Photo">
                            </a>
                            <button type="button" class="btn btn-block btn-danger btn-xs" onclick="deletereceipt({{$receipt->Id }})">Delete</button>
                          </div>

                        @else
                          <div class="col-sm-3" id="receipt{{ $receipt->Id }}">
                            <a download="{{ url($receipt->Web_Path) }}" href="{{ url($receipt->Web_Path) }}" title="Download">
                              {{ $receipt->File_Name}}
                            </a>
                            <button type="button" class="btn btn-block btn-danger btn-xs" onclick="deletereceipt({{$receipt->Id }})">Delete</button>
                          </div>

                        @endif

                      @endforeach
                    @endif

                </div>

              </div>
          </div>
        </div>


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

                              @foreach($po as $key=>$value)

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
                            @foreach($po as $PO)

                                  <tr id="row_{{ $i }}" >
                                    <td></td>
                                      @foreach($PO as $key=>$value)
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
 function deletereceipt(id) {
    $.ajaxSetup({
       headers: { 'X-CSRF-Token' : $('meta[name=_token]').attr('content') }
    });
    $.ajax({
                url: "{{ url('/PO/deletereceipt') }}",
                method: "POST",
                data: {Id:id},
                success: function(response){
                  if (response==0)
                  {
                    var message ="Failed to delete attachment!";
                    $("#warning-alert ul").html(message);
                    $("#warning-alert").show();
                  }
                  else {
                    var message ="Attachment deleted!";
                    $("#update-alert ul").html(message);
                    $("#update-alert").show();
                    //$("#Template").val(response).change();
                    $("#exist-alert").hide();

                    $("#receipt"+id).remove();
                  }
        }
    });
  }
 function uploadreceipt() {
      $.ajaxSetup({
         headers: { 'X-CSRF-Token' : $('meta[name=_token]').attr('content') }
      });
      $.ajax({
                  url: "{{ url('/PO/uploadreceipt') }}",
                  method: "POST",
                  contentType: false,
                  processData: false,
                  data:new FormData($("#upload_form")[0]),
                  success: function(response){
                    if (response==0)
                    {
                      var message ="Failed to upload attachment!";
                      $("#warning-alert ul").html(message);
                      $("#warning-alert").show();
                    }
                    else {
                      var message ="Attachment uploaded!";
                      $("#update-alert ul").html(message);
                      $("#update-alert").show();
                      //$("#Template").val(response).change();
                      $("#exist-alert").hide();
                      $("#receipt").val("");

                      var split=response.split(",");
                      for (var i = 0; i < split.length; i++) {

                        if (split[i].toUpperCase().includes(".PNG") ||split[i].toUpperCase().includes(".JPG")||split[i].toUpperCase().includes(".JPEG"))
                        {
                          var sub=split[i].split("|");

                          var html="<div class='col-sm-3' id='receipt"+sub[0]+"'>";
                          html+="<a download='"+sub[1]+"' href='"+sub[1]+"' title='Download'>";
                          html+="<img class='img-responsive' src='"+sub[1]+"'  alt='Photo'>";
                          html+="</a>";
                          html+="<button type='button' class='btn btn-block btn-danger btn-xs' onclick='deletereceipt("+sub[0]+")'>Delete</button>";
                          html+="</div>";

                          $("#receiptdiv").append(html);


                        }
                        else {

                          var sub=split[i].split("|");
                          var html="<div class='col-sm-3' id='receipt"+sub[0]+"'>";
                          html+="<a download='"+sub[1]+"' href='"+sub[1]+"' title='Download'>";
                          html+=sub[2];
                          html+="</a>";
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
