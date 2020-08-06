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
      }

      .action {
        width:100%;
        text-align: left;
      }

      .historytable{
        text-align: center;
      }

      .historyheader{
        background-color: gray;
      }
      .btn{
        width:200px;
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
      <script type="text/javascript" language="javascript" src="{{ asset('/plugin/datatables/extensions/Select2/editor.select2.js') }}"></script>


      <script type="text/javascript" language="javascript" class="init">

      var editor; // use a global for the submit and return data rendering in the examples
      var oTable;
      var users;
      var assetid;

      $(document).ready(function() {

        editor = new $.fn.dataTable.Editor( {
                ajax: {
                   "url": "{{ asset('/Include/assettracking.php') }}",
                   "data": {
                       "type": "{{ $type }}"
                   }
                 },
                table: "#assettrackingtable",
                idSrc: "assets.Id",
                fields: [
                  {
                         label: "Type:",
                         name: "assets.Type",
                         type: "hidden",
                         def: "{{ $type }}"
                  },
                  @if ($type=="IT APPLIANCES")
                  {
                         label: "Item :",
                         name: "assets.Rental_End_Date",
                         type:  'select2',
                         options: [
                           { label :"", value: "" },

                           @foreach($items as $item)

                             @if ($item->Field=="IT_Item")
                               { label :"{{$item->Option}}", value: "{{$item->Option}}" },
                             @endif
                           @endforeach
                         ],
                  },{
                         label: "Availability:",
                         name: "assets.Availability",
                         type:  'select',
                         options: [
                           { label :"", value: "" },
                           { label :"Yes", value: "Yes" },
                           { label :"No", value: "No" }

                         ],
                  },{
                         label: "Date of Purchase:",
                         name: "assets.Date_of_Purchase",
                         type:   'datetime',
                         def:    function () { return ""; },
                         format: 'DD-MMM-YYYY'
                  },{
                         label: "Supplier Name:",
                         name: "assets.Supplier_Name",
                         type:  'select2',
                         options: [
                           { label :"", value: "" },

                           @foreach($items as $item)

                             @if ($item->Field=="IT_Supplier_Name")
                               { label :"{{$item->Option}}", value: "{{$item->Option}}" },
                             @endif
                           @endforeach
                         ],
                         opts: {
                          tags: true
                         }
                  },{
                         label: "Price:",
                         name: "assets.Price"
                  },{
                         label: "Description:",
                         name: "assets.Description"

                  },{
                         label: "Model No:",
                         name: "assets.Model_No"
                  },{
                         label: "User:",
                         name: "assets.Label"
                  },{
                         label: "Serial No:",
                         name: "assets.Serial_No"
                  },{
                         label: "Status:",
                         name: "assets.Extra_Detail_1",
                         type:  'select2',
                         options: [
                           { label :"", value: "" },
                           { label :"Workable", value: "Workable" },
                           { label :"Not Workable", value: "Not Workable" },
                           { label :"Lost", value: "Lost" },
                           { label :"Disposal", value: "Disposal" },
                         ],
                  },{
                          label: "Remarks:",
                          name: "assets.Remarks",
                          type: "textarea"
                   },{
                           label: "File:",
                           name: "files.Web_Path",
                           type: "upload",
                           ajaxData: function ( d ) {
                             d.append( 'Id', assetid ); // edit - use `d`
                           },
                           display: function ( file_id ) {

                               return '<img src="'+ oTable.api().row( editor.modifier() ).data().files.Web_Path +'">';
                           },
                           clearText: "Clear",
                           noImageText: 'No file'
                   },
                  @elseif($type == "OFFICE FURNITURES")
                  {
                         label: "Item:",
                         name: "assets.Rental_End_Date",
                         type:  'select2',
                         options: [
                          { label :"", value: "" },

                           @foreach($items as $item)

                             @if ($item->Field=="Office_Item")
                               { label :"{{$item->Option}}", value: "{{$item->Option}}" },
                             @endif
                           @endforeach
                         ],
                  },{
                         label: "Supplier Name:",
                         name: "assets.Supplier_Name",
                         type: "select2",
                         options: [
                          { label :"", value: "" },
                          @foreach($items as $item)

                            @if ($item->Field=="Office_Supplier_Name")
                              { label :"{{$item->Option}}", value: "{{$item->Option}}" },
                            @endif
                          @endforeach
                         ]
                  },{
                         label: "Price:",
                         name: "assets.Price"
                  },{
                         label: "Model No:",
                         name: "assets.Model_No"
                  },{
                         label: "Serial No:",
                         name: "assets.Serial_No"
                  },{
                         label: "Company:",
                         name: "assets.Company",
                         type:  'select2',
                         options: [
                           { label :"", value: "" },
                           @foreach($company as $option)
                               { label :"{{$option->Option}}", value: "{{$option->Option}}" },
                           @endforeach
                         ],

                 },{
                        label: "Location:",
                        name: "assets.Location"

                 },{
                         label: "Status:",
                         name: "assets.Extra_Detail_1",
                         type:  'select2',
                         options: [
                           { label :"", value: "" },
                           { label :"Workable", value: "Workable" },
                           { label :"Not Workable", value: "Not Workable" },
                           { label :"Lost", value: "Lost" },
                           { label :"Disposal", value: "Disposal" },
                         ],
                  },{
                         label: "Warranty Start Date:",
                         name: "assets.Extra_Detail_2",
                         type:   'datetime',
                         def:    function () { return ""; },
                         format: 'DD-MMM-YYYY',
                         attr: {
                          autocomplete: "off"
                         }
                  },{
                         label: "Warranty End Date:",
                         name: "assets.Extra_Detail_3",
                         type:   'datetime',
                         def:    function () { return ""; },
                         format: 'DD-MMM-YYYY',
                         attr: {
                          autocomplete: "off"
                         }
                  },{
                         label: "Tel:",
                         name: "assets.Extra_Detail_4"
                  },{
                         label: "Fax:",
                         name: "assets.Extra_Detail_5"
                  },{
                        label: "Rental Fees:",
                        name: "assets.Rental_Fees"

                 },{
                        label: "Fees:",
                        name: "assets.Registered_Fees"

                 },{
                        label: "Agreenment Start_Date:",
                        name: "assets.Agreenment_Start_Date",
                        type:   'datetime',
                        def:    function () { return ""; },
                        format: 'DD-MMM-YYYY',
                        attr: {
                          autocomplete: "off"
                        }

                 },{
                        label: "Agreenment End_Date:",
                        name: "assets.Agreenment_End_Date",
                        type:   'datetime',
                        def:    function () { return ""; },
                        format: 'DD-MMM-YYYY',
                        attr: {
                          autocomplete: "off"
                        }

                 },{
                        label: "Termination of Agreenment:",
                        name: "assets.Termination_of_Agreenment"

                 },{
                         label: "Remarks:",
                         name: "assets.Remarks",
                         type: "textarea"
                  },{
                          label: "File:",
                          name: "files.Web_Path",
                          type: "upload",
                          ajaxData: function ( d ) {
                            d.append( 'Id', assetid ); // edit - use `d`
                          },
                          display: function ( file_id ) {
                              return '<img src="'+ oTable.api().row( editor.modifier() ).data().files.Web_Path +'">';
                          },
                          clearText: "Clear",
                          noImageText: 'No file'
                  }
                  @elseif($type=="CERTIFICATES")
                  {
                         label: "Certificate Type:",
                         name: "assets.IMEI",
                         type:  'select2',
                         options: [
                           { label :"", value: "" },
                             @foreach($options as $option)
                               @if ($option->Field=="Certificate_Type")
                                 { label :"{{$option->Option}}", value: "{{$option->Option}}" },
                               @endif
                             @endforeach

                         ],
                  },{
                         label: "Certificate Name:",
                         name: "assets.Brand"
                  },{
                         label: "Car No:",
                         name: "assets.Car_No"
                  },{
                         label: "Replacement Car No:",
                         name: "assets.Replacement_Car_No"
                  },{
                         label: "Color:",
                         name: "assets.Color"
                  },{
                         label: "Availability:",
                         name: "assets.Availability",
                         type:   'datetime',
                         def:    function () { return ""; },
                         format: 'DD-MMM-YYYY'
                  },{
                         label: "Ownership:",
                         name: "assets.Ownership",
                         type:   'datetime',
                         def:    function () { return ""; },
                         format: 'DD-MMM-YYYY'
                  },{
                         label: "Company:",
                         name: "assets.Company",
                         type:  'select',
                         options: [
                           { label :"", value: "" },
                           @foreach($company as $option)
                               { label :"{{$option->Option}}", value: "{{$option->Option}}" },
                           @endforeach
                         ],

                 },{
                        label: "Location:",
                        name: "assets.Location"

                 },{
                         label: "Status:",
                         name: "assets.Extra_Detail_1",
                         type:  'select',
                         options: [
                           { label :"", value: "" },
                           { label :"Workable", value: "Workable" },
                           { label :"Not Workable", value: "Not Workable" },
                           { label :"Lost", value: "Lost" },
                           { label :"Disposal", value: "Disposal" },
                         ],
                  },{
                         label: "Warranty Start Date:",
                         name: "assets.Extra_Detail_2",
                         type:   'datetime',
                         def:    function () { return ""; },
                         format: 'DD-MMM-YYYY'
                  },{
                         label: "Warranty End Date:",
                         name: "assets.Extra_Detail_3",
                         type:   'datetime',
                         def:    function () { return ""; },
                         format: 'DD-MMM-YYYY'
                  },{
                         label: "Tel:",
                         name: "assets.Extra_Detail_4"
                  },{
                         label: "Fax:",
                         name: "assets.Extra_Detail_5"
                  },{
                        label: "Rental Fees:",
                        name: "assets.Rental_Fees"

                  },{
                        label: "Fees:",
                        name: "assets.Registered_Fees"

                  },{
                         label: "Remarks:",
                         name: "assets.Remarks",
                         type: "textarea"
                  },{
                          label: "File:",
                          name: "files.Web_Path",
                          type: "upload",
                          ajaxData: function ( d ) {
                            d.append( 'Id', assetid ); // edit - use `d`
                          },
                          display: function ( file_id ) {
                              return '<img src="'+ oTable.api().row( editor.modifier() ).data().files.Web_Path +'">';
                          },
                          clearText: "Clear",
                          noImageText: 'No file'
                  }
                 @elseif($type=="TOOLS AND EQUIPMENTS")
                  {
                         label: "Item:",
                         name: "assets.Rental_End_Date",
                         type:  'select2',
                         options: [
                             { label :"", value: "" },

                             @foreach($items as $item)

                               @if ($item->Field=="Tool_Item")
                                 { label :"{{$item->Option}}", value: "{{$item->Option}}" },
                               @endif
                             @endforeach
                         ],
                  },{
                         label: "Date of Purchase:",
                         name: "assets.Date_of_Purchase",
                         type:   'datetime',
                         def:    function () { return ""; },
                         format: 'DD-MMM-YYYY'
                  },{
                         label: "Supplier Name:",
                         name: "assets.Supplier_Name",
                         type: "select2",
                         options: [
                          { label :"", value: "" },
                          @foreach($items as $item)

                            @if ($item->Field=="Tool_Supplier_Name")
                              { label :"{{$item->Option}}", value: "{{$item->Option}}" },
                            @endif
                          @endforeach
                         ]
                  },{
                         label: "Price:",
                         name: "assets.Price"
                  },{
                         label: "Description:",
                         name: "assets.Description"

                  },{
                         label: "Serial No:",
                         name: "assets.Serial_No"
                  },{
                         label: "Status:",
                         name: "assets.Extra_Detail_1",
                         type:  'select2',
                         options: [
                           { label :"", value: "" },
                           { label :"Workable", value: "Workable" },
                           { label :"Not Workable", value: "Not Workable" },
                           { label :"Lost", value: "Lost" },
                           { label :"Disposal", value: "Disposal" },
                         ],
                  },{
                         label: "Remarks:",
                         name: "assets.Remarks",
                         type: "textarea"
                  },{
                          label: "File:",
                          name: "files.Web_Path",
                          type: "upload",
                          ajaxData: function ( d ) {
                            d.append( 'Id', assetid ); // edit - use `d`
                          },
                          display: function ( file_id ) {
                              return '<img src="'+ oTable.api().row( editor.modifier() ).data().files.Web_Path +'">';
                          },
                          clearText: "Clear",
                          noImageText: 'No file'
                  }
                  @elseif($type=="GENSET ASSETS")
                   {
                          label: "Item:",
                          name: "assets.Rental_End_Date",
                          type:  'select2',
                          options: [
                              { label :"", value: "" },

                              @foreach($items as $item)

                                @if ($item->Field=="GENSET_Item")
                                  { label :"{{$item->Option}}", value: "{{$item->Option}}" },
                                @endif
                              @endforeach
                          ],
                   },{
                          label: "Asset Type :",
                          name: "assets.Asset_Type",
                          type:  'select2',
                          options: [
                            { label :"", value: "" },

                            @foreach($items as $item)

                              @if ($item->Field=="Asset_Type")
                                { label :"{{$item->Option}}", value: "{{$item->Option}}" },
                              @endif
                            @endforeach
                          ],
                   },{
                          label: "Date of Purchase:",
                          name: "assets.Date_of_Purchase",
                          type:   'datetime',
                          def:    function () { return ""; },
                          format: 'DD-MMM-YYYY'
                   },{
                          label: "Supplier Name:",
                          name: "assets.Supplier_Name",
                          type: "select2",
                          options: [
                           { label :"", value: "" },
                           @foreach($items as $item)

                             @if ($item->Field=="GENSET_Supplier_Name")
                               { label :"{{$item->Option}}", value: "{{$item->Option}}" },
                             @endif
                           @endforeach
                          ]
                   },{
                          label: "Price:",
                          name: "assets.Price"
                   },{
                          label: "Description:",
                          name: "assets.Description"

                   },{
                          label: "Serial No:",
                          name: "assets.Serial_No"
                   },{
                          label: "Status:",
                          name: "assets.Extra_Detail_1",
                          type:  'select2',
                          options: [
                            { label :"", value: "" },
                            { label :"Workable", value: "Workable" },
                            { label :"Not Workable", value: "Not Workable" },
                            { label :"Lost", value: "Lost" },
                            { label :"Disposal", value: "Disposal" },
                          ],
                   },{
                          label: "Remarks:",
                          name: "assets.Remarks",
                          type: "textarea"
                   },{
                           label: "File:",
                           name: "files.Web_Path",
                           type: "upload",
                           ajaxData: function ( d ) {
                             d.append( 'Id', assetid ); // edit - use `d`
                           },
                           display: function ( file_id ) {
                               return '<img src="'+ oTable.api().row( editor.modifier() ).data().files.Web_Path +'">';
                           },
                           clearText: "Clear",
                           noImageText: 'No file'
                   }
                  @elseif($type=="CME ASSETS")
                   {
                          label: "Item:",
                          name: "assets.Rental_End_Date",
                          type:  'select2',
                          options: [
                              { label :"", value: "" },

                              @foreach($items as $item)

                                @if ($item->Field=="CME_Item")
                                  { label :"{{$item->Option}}", value: "{{$item->Option}}" },
                                @endif
                              @endforeach
                          ],
                   },{
                          label: "Asset Type :",
                          name: "assets.Asset_Type",
                          type:  'select2',
                          options: [
                            { label :"", value: "" },

                            @foreach($items as $item)

                              @if ($item->Field=="Asset_Type")
                                { label :"{{$item->Option}}", value: "{{$item->Option}}" },
                              @endif
                            @endforeach
                          ],
                   },{
                          label: "Date of Purchase:",
                          name: "assets.Date_of_Purchase",
                          type:   'datetime',
                          def:    function () { return ""; },
                          format: 'DD-MMM-YYYY'
                   },{
                          label: "Supplier Name:",
                          name: "assets.Supplier_Name",
                          type: "select2",
                          options: [
                           { label :"", value: "" },
                           @foreach($items as $item)

                             @if ($item->Field=="CME_Supplier_Name")
                               { label :"{{$item->Option}}", value: "{{$item->Option}}" },
                             @endif
                           @endforeach
                          ]
                   },{
                          label: "Price:",
                          name: "assets.Price"
                   },{
                          label: "Description:",
                          name: "assets.Description"

                   },{
                          label: "Serial No:",
                          name: "assets.Serial_No"
                   },{
                          label: "Status:",
                          name: "assets.Extra_Detail_1",
                          type:  'select2',
                          options: [
                            { label :"", value: "" },
                            { label :"Workable", value: "Workable" },
                            { label :"Not Workable", value: "Not Workable" },
                            { label :"Lost", value: "Lost" },
                            { label :"Disposal", value: "Disposal" },
                          ],
                   },{
                          label: "Remarks:",
                          name: "assets.Remarks",
                          type: "textarea"
                   },{
                           label: "File:",
                           name: "files.Web_Path",
                           type: "upload",
                           ajaxData: function ( d ) {
                             d.append( 'Id', assetid ); // edit - use `d`
                           },
                           display: function ( file_id ) {
                               return '<img src="'+ oTable.api().row( editor.modifier() ).data().files.Web_Path +'">';
                           },
                           clearText: "Clear",
                           noImageText: 'No file'
                   }
                   @elseif($type=="LOGISTIC ASSETS")
                    {
                           label: "Item:",
                           name: "assets.Rental_End_Date",
                           type:  'select2',
                           options: [
                               { label :"", value: "" },

                               @foreach($items as $item)

                                 @if ($item->Field=="LOGISTIC_Item")
                                   { label :"{{$item->Option}}", value: "{{$item->Option}}" },
                                 @endif
                               @endforeach
                           ],
                    },{
                           label: "Asset Type :",
                           name: "assets.Asset_Type",
                           type:  'select2',
                           options: [
                             { label :"", value: "" },

                             @foreach($items as $item)

                               @if ($item->Field=="Asset_Type")
                                 { label :"{{$item->Option}}", value: "{{$item->Option}}" },
                               @endif
                             @endforeach
                           ],
                    },{
                           label: "Date of Purchase:",
                           name: "assets.Date_of_Purchase",
                           type:   'datetime',
                           def:    function () { return ""; },
                           format: 'DD-MMM-YYYY'
                    },{
                           label: "Supplier Name:",
                           name: "assets.Supplier_Name",
                           type: "select2",
                           options: [
                            { label :"", value: "" },
                            @foreach($items as $item)

                              @if ($item->Field=="LOGISTIC_Supplier_Name")
                                { label :"{{$item->Option}}", value: "{{$item->Option}}" },
                              @endif
                            @endforeach
                           ]
                    },{
                           label: "Price:",
                           name: "assets.Price"
                    },{
                           label: "Description:",
                           name: "assets.Description"

                    },{
                           label: "Serial No:",
                           name: "assets.Serial_No"
                    },{
                           label: "Status:",
                           name: "assets.Extra_Detail_1",
                           type:  'select2',
                           options: [
                             { label :"", value: "" },
                             { label :"Workable", value: "Workable" },
                             { label :"Not Workable", value: "Not Workable" },
                             { label :"Lost", value: "Lost" },
                             { label :"Disposal", value: "Disposal" },
                           ],
                    },{
                           label: "Remarks:",
                           name: "assets.Remarks",
                           type: "textarea"
                    },{
                            label: "File:",
                            name: "files.Web_Path",
                            type: "upload",
                            ajaxData: function ( d ) {
                              d.append( 'Id', assetid ); // edit - use `d`
                            },
                            display: function ( file_id ) {
                                return '<img src="'+ oTable.api().row( editor.modifier() ).data().files.Web_Path +'">';
                            },
                            clearText: "Clear",
                            noImageText: 'No file'
                    }
                 @else
                  {
                         label: "Item Name:",
                         name: "assets.Label"
                  },{
                         label: "Type:",
                         name: "assets.Type"
                  },{
                         label: "Serial No:",
                         name: "assets.Serial_No"
                  },{
                         label: "Certificate Name:",
                         name: "assets.Brand"
                  },{
                         label: "Model No:",
                         name: "assets.Model_No"
                  },{
                         label: "Replacement Car No:",
                         name: "assets.Replacement_Car_No"
                  },{
                         label: "Color:",
                         name: "assets.Color"
                  },{
                         label: "Extra Detail 1:",
                         name: "assets.Extra_Detail_1",
                         type:  'select',
                         options: [
                           { label :"", value: "" },
                           { label :"Workable", value: "Workable" },
                           { label :"Not Workable", value: "Not Workable" },
                           { label :"Lost", value: "Lost" },
                           { label :"Disposal", value: "Disposal" },
                         ],
                  },{
                         label: "Extra Detail 2:",
                         name: "assets.Extra_Detail_2",
                         type:   'datetime',
                         def:    function () { return ""; },
                         format: 'DD-MMM-YYYY'
                  },{
                         label: "Extra Detail 3:",
                         name: "assets.Extra_Detail_3",
                         type:   'datetime',
                         def:    function () { return ""; },
                         format: 'DD-MMM-YYYY'
                  },{
                         label: "Extra Detail 4:",
                         name: "assets.Extra_Detail_4"
                  },{
                         label: "Extra Detail 5:",
                         name: "assets.Extra_Detail_5"
                  },{
                         label: "Remarks:",
                         name: "assets.Remarks",
                         type: "textarea"
                  },{
                         label: "Ownership:",
                         name: "assets.Ownership",
                         type:   'datetime',
                         def:    function () { return ""; },
                         format: 'DD-MMM-YYYY'
                  },{
                         label: "Rental Company:",
                         name: "assets.Rental_Company"
                  },{
                         label: "Rental Start Date:",
                         name: "assets.Rental_Start_Date",
                         type:   'datetime',
                         def:    function () { return new Date(); },
                         format: 'DD-MMM-YYYY'
                  },{
                         label: "Rental_End_Date:",
                         name: "assets.Rental_End_Date",
                         type:  'select',
                         options: [

                           @if($type=="IT APPLIANCES")
                             { label :"", value: "" },

                             @foreach($items as $item)

                               @if ($item->Field=="IT_Item")
                                 { label :"{{$item->Option}}", value: "{{$item->Option}}" },
                               @endif
                             @endforeach


                           @elseif($type=="TOOLS AND EQUIPMENTS")
                             { label :"", value: "" },

                             @foreach($items as $item)

                               @if ($item->Field=="Tool_Item")
                                 { label :"{{$item->Option}}", value: "{{$item->Option}}" },
                               @endif
                             @endforeach

                           @elseif($type=="OFFICE FURNITURES")
                            { label :"", value: "" },

                             @foreach($items as $item)

                               @if ($item->Field=="Office_Item")
                                 { label :"{{$item->Option}}", value: "{{$item->Option}}" },
                               @endif
                             @endforeach

                           @ENDIF
                         ],
                  }, {
                          label: "File:",
                          name: "files.Web_Path",
                          type: "upload",
                          ajaxData: function ( d ) {
                            d.append( 'Id', assetid ); // edit - use `d`
                          },
                          display: function ( file_id ) {
                              return '<img src="'+ oTable.api().row( editor.modifier() ).data().files.Web_Path +'">';
                          },
                          clearText: "Clear",
                          noImageText: 'No file'
                  },{
                         label: "Supplier Name:",
                         name: "assets.Supplier_Name"
                  },{
                         label: "Price:",
                         name: "assets.Price"
                  },{
                         label: "Date_of_Purchase:",
                         name: "assets.Date_of_Purchase",
                         type:   'datetime',
                         def:    function () { return ""; },
                         format: 'DD-MMM-YYYY'
                  },{
                         label: "Description:",
                         name: "assets.Description"

                  },{
                         label: "Company:",
                         name: "assets.Company",
                         type:  'select',
                         options: [
                           { label :"", value: "" },
                           @foreach($company as $option)
                               { label :"{{$option->Option}}", value: "{{$option->Option}}" },
                           @endforeach
                         ],

                 },{
                        label: "Location:",
                        name: "assets.Location"

                 },{
                         label: "Kitchen Appliances:",
                         name: "assets.Kitchen_Appliances",
                         type:   'datetime',
                         def:    function () { return ""; },
                         format: 'DD-MMM-YYYY'

                 },{
                        label: "Rental Fees:",
                        name: "assets.Rental_Fees"

                 },{
                        label: "Registered Fees:",
                        name: "assets.Registered_Fees"

                 },{
                        label: "Agreenment Start_Date:",
                        name: "assets.Agreenment_Start_Date",
                        type:   'datetime',
                        def:    function () { return ""; },
                        format: 'DD-MMM-YYYY'

                 },{
                        label: "Agreenment End_Date:",
                        name: "assets.Agreenment_End_Date",
                        type:   'datetime',
                        def:    function () { return ""; },
                        format: 'DD-MMM-YYYY'

                 },{
                        label: "Termination of Agreenment:",
                        name: "assets.Termination_of_Agreenment"

                 },{
                        label: "Asset Listed:",
                        name: "assets.Asset_Listed"

                 },{
                        label: "Rental Date:",
                        name: "assets.Rental_Date"

                 },{
                        label: "Rental Deposit:",
                        name: "assets.Rental_Deposit"

                 },{
                        label: "ServiceProvided:",
                        name: "assets.Service_Provided"

                 },{
                        label: "APA/Registration_No:",
                        name: "assets.APA_Registration_No"

                 },{
                        label: "Expired Date:",
                        name: "assets.Expired_Date",
                        type:   'datetime',
                        def:    function () { return ""; },
                        format: 'DD-MMM-YYYY'
                 },{
                        label: "Quantity:",
                        name: "assets.Quantity",
                        type:  'select',
                        options: [
                            { label :"", value: "" },
                            { label :"1", value: "1" },
                            { label :"2", value: "2" },
                            { label :"3", value: "3" },
                            { label :"4", value: "4" },
                            { label :"5", value: "5" },
                            { label :"6", value: "6" },
                            { label :"7", value: "7" },

                        ],

                 },{
                        label: "Contact_No:",
                        name: "assets.Contact_No"

                 }
                 @endif


                ]
        } );

        // Activate an inline edit on click of a table cell
              // $('#assettrackingtable').on( 'click', 'tbody td:not(.child), tbody span.dtr-data', function (e) {
              //       editor.inline( this, {
              //      onBlur: 'submit'
              //     } );
              // } );

              $('#assettrackingtable').on( 'click', 'tr', function () {
                // Get the rows id value
               //  var row=$(this).closest("tr");
               //  var oTable = row.closest('table').dataTable();
                assetid = oTable.api().row( this ).data().assets.Id;
              });

              editor.on( 'postSubmit', function ( e, json, data ) {

                for (var id in data.data)
                {

                  for (var prop in data.data[id].assets) {

                      if (prop=="Car_No")
                      {
                        $.ajaxSetup({
                           headers: { 'X-CSRF-Token' : $('meta[name=_token]').attr('content') }
                        });

                        $.ajax({
                                    url: "{{ url('/asset/updatecarno') }}",
                                    method: "POST",
                                    data: {AssetId:id,Car_No:data.data[id].assets[prop]
                                    },

                                    success: function(response){

                            }
                        });

                      }
                    }

                }


            } );

            oTable=$('#assettrackingtable').dataTable( {
                    ajax: {
                       "url": "{{ asset('/Include/assettracking.php') }}",
                       "data": {
                           "type": "{{ $type }}"
                       }
                     },
                    @if ($type=="CME ASSETS")
                       columnDefs: [{ "visible": false, "targets": [2,3,4,5,6,7,8,9,10,11,12,13,14,15,22,23,27,28,29,31,32,33,34,35,36,37,38,39,40,41,42,43,44,45,46,47] },{"className": "dt-center", "targets": "_all"}],
                    @elseif ($type=="GENSET ASSETS")
                      columnDefs: [{ "visible": false, "targets": [2,3,4,5,6,7,8,9,10,11,12,13,14,15,22,23,27,28,29,31,32,33,34,35,36,37,38,39,40,41,42,43,44,45,46,47] },{"className": "dt-center", "targets": "_all"}],
                    @elseif ($type=="LOGISTIC ASSETS")
                      columnDefs: [{ "visible": false, "targets": [2,3,4,5,6,7,8,9,10,11,12,13,14,15,22,23,27,28,29,31,32,33,34,35,36,37,38,39,40,41,42,43,44,45,46,47] },{"className": "dt-center", "targets": "_all"}],
                    @elseif ($type=="IT APPLIANCES")
                      columnDefs: [{ "visible": false, "targets": [2,3,4,5,6,7,8,10,11,12,13,14,15,16,27,28,29,31,32,33,34,35,36,37,38,39,40,41,42,43,44,45,46,47] },{"className": "dt-center", "targets": "_all"}],
                    @elseif ($type=="OFFICE FURNITURES")
                      columnDefs: [{ "visible": false, "targets": [2,4,5,6,7,8,9,10,11,12,13,14,15,16,18,21,23,27,40,41,42,43,44,45,46,47] },{"className": "dt-center", "targets": "_all"}],
                    @elseif ($type=="TOOLS AND EQUIPMENTS")
                        columnDefs: [{ "visible": false, "targets": [2,3,4,5,6,7,8,9,10,11,12,13,14,15,16,22,23,27,28,29,31,32,33,34,35,36,37,38,39,40,41,42,43,44,45,46,47] },{"className": "dt-center", "targets": "_all"}],
                    @else
                      columnDefs: [{ "visible": false, "targets": [2,3,4,5,6,7,8,9,10,11,12,13,14,15,22,23,27,28,29,31,32,33,34,35,36,37,38,39,40,41,42,43,44,45,46,47] },{"className": "dt-center", "targets": "_all"}],
                    @endif

                    responsive: false,
                    colReorder: false,
                    //dom: "Brt",
                    rowId:"assets.Id",
                    bAutoWidth: true,
                    dom: "Blrftip",
                    scrollY: "100%",
                    scrollX: "100%",
                    scrollCollapse: true,
                    iDisplayLength:10,
                    columns: [
                            { data: null, "render":"", title: "No"},
                            {
                               sortable: false,
                               "render": function ( data, type, full, meta ) {
                                  if (full.assets.Availability=="Yes")
                                  {
                                    return "<div class='action'><a href='#' onclick='assign("+full.assets.Id+");'>Assign</a> | <a href='#' onclick='history("+full.assets.Id+");'>History</a></div>";
                                  }else if (full.assets.Availability=="Returned") {
                                    return "<div class='action'><a href='#' onclick='history("+full.assets.Id+");'>History</a></div>";
                                  }
                                  else if (full.assets.Availability=="Terminated") {
                                    return "<div class='action'><a href='#' onclick='history("+full.assets.Id+");'>History</a></div>";
                                  }
                                  else if (full.assets.Availability=="Stolen") {
                                    return "<div class='action'><a href='#' onclick='history("+full.assets.Id+");'>History</a></div>";
                                  }
                                  else if (full.assets.Availability=="Missing") {
                                    return "<div class='action'><a href='#' onclick='history("+full.assets.Id+");'>History</a></div>";
                                  }
                                  else if (full.assets.Availability=="Faulty Unit") {
                                    return "<div class='action'><a href='#' onclick='history("+full.assets.Id+");'>History</a></div>";
                                  }
                                  else {
                                    return "<div class='action'><a href='#' onclick='assign("+full.assets.Id+");'>Assign</a> | <a href='#' onclick='returned("+full.assets.Id+","+full.assettrackings.Id+","+full.assettrackings.UserId+","+full.assettrackings.ProjectId+");'>Return</a> | <a href='#' onclick='history("+full.assets.Id+");'>History</a></div>";
                                  }

                               },title: "Action"
                            },
                            { data: "assets.Id",title: "Id"},
                            { data: "assets.Type" ,title: "Type"},
                            { data: "assets.IMEI",title: "Premises" },
                            { data: "assets.Brand",title: "Company" },
                            { data: "assets.Car_No",title: "License Scope" },
                            { data: "assets.Replacement_Car_No",title: "Registration No" },
                            { data: "assets.Color",title: "Gred" },
                            @if ($type=="IT APPLIANCES")
                              { data: "assets.Availability",title: "Availability" },
                            @else
                              { data: "assets.Availability",title: "Effective Date" },
                            @endif
                            { data: "transfer.Name",title: "Transfer_To"},//10

                            { data: "assettrackings.Transfer_Date_Time",title: "Due Date"},
                            { data: "assettrackings.Acknowledge_Date_Time",title: "Acknowledge_Date_Time"},
                            { data: "assets.Ownership" ,title: "Due Date"},
                            { data: "assets.Rental_Company",title: "Rental_Company" },
                            { data: "assets.Rental_Start_Date",title: "Rental_Start_Date" },
                            { data: "assets.Asset_Type",title: "Asset_Type" },
                            { data: "assets.Rental_End_Date",title: "Item" },

                            { data: "assets.Date_of_Purchase",title: "Date of Purchase" },
                            { data: "assets.Supplier_Name",title: "Supplier Name" },
                            { data: "assets.Price",title: "Price" },//20
                            { data: "assets.Description",title: "Description" },

                            { data: "assets.Model_No",title: "Model" },
                            { data: "assets.Label",title: "User" },
                            { data: "assets.Serial_No",title: "Serial No" },
                            { data: "holder.Name",title: "Custodian"},
                            { data: "projects.Project_Name",editField: "assets.ProjectId",title: "Department" },
                            //25

                            { data: "assets.Kitchen_Appliances",title: "Taken" },
                            { data: "assets.Company",title: "Company" },
                            { data: "assets.Location",title: "Location" },
                            { data: "assets.Extra_Detail_1",title: "Status" },//30
                            { data: "assets.Extra_Detail_2" ,title: "Warranty Start Date"},
                            { data: "assets.Extra_Detail_3" ,title: "Warranty End Date"},
                            { data: "assets.Extra_Detail_4" ,title: "Tel"},
                            { data: "assets.Extra_Detail_5" ,title: "Fax"},
                            { data: "assets.Rental_Fees" ,title: "Rental Fees"},
                            { data: "assets.Registered_Fees" ,title: "Fees"},

                            { data: "assets.Agreenment_Start_Date" ,title: "Agreenment Start Date"},
                            { data: "assets.Agreenment_End_Date" ,title: "Agreenment End Date"},
                            { data: "assets.Termination_of_Agreenment" ,title: "Termination of Agreenment"},
                            { data: "assets.Asset_Listed" ,title: "Asset_Listed"},//40
                            { data: "assets.Rental_Date" ,title: "Rental_Date"},
                            { data: "assets.Rental_Deposit" ,title: "Rental_Deposit"},
                            { data: "assets.Service_Provided" ,title: "Service_Provided"},
                            { data: "assets.APA_Registration_No" ,title: "APA_Registration_No"},
                            { data: "assets.Expired_Date" ,title: "Expired_Date"},
                            { data: "assets.Quantity" ,title: "Quantity"},

                            { data: "assets.Contact_No" ,title: "Contact_No"},
                            { data: "assets.Remarks",title: "Remarks" },

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
                       editor:  editor,
                       columns: [ 2, 3,4, 5,6,7,8,9,10,11,12,13,14,15,16,17,18,19,20,21,22,23,24,25,26,27]
                   },
                  //  keys: {
                  //      columns: ':not(:first-child)',
                  //      editor:  editor
                  //  },
                   select: true,
                    buttons: [
                            // {
                            //   text: 'New Asset',
                            //   action: function ( e, dt, node, config ) {
                            //       // clearing all select/input options
                            //       editor
                            //          .create( false )
                            //          .set( 'assets.Type', '{{$type}}')
                            //          .submit();
                            //   },
                            // },
                            { extend: "create", text: "New Asset", editor: editor },

                            { extend: "edit", editor: editor },
                            { extend: "remove", editor: editor },
                            {
                                    extend: 'collection',
                                    text: 'Export',
                                    buttons: [
                                            'excel',
                                            'csv',
                                    ]
                            },

                              'print',
                              {
                                text: 'History',
                                action: function ( e, dt, node, config ) {
                                    // clearing all select/input options
                                    var win = window.open("{{ url('/assethistory') }}/{{$type}}", '_blank');
                                    win.focus();
                                },
                              },
                    ],

        });



        $(".assettrackingtable thead input").keyup ( function () {

                /* Filter on the column (the index) of this element */

                if ($('#assettrackingtable').length > 0)
                {

                    var colnum=document.getElementById('assettrackingtable').rows[0].cells.length;

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

        $("#ajaxloader").hide();
        $("#ajaxloader2").hide();
        $("#ajaxloader3").hide();

        oTable.api().on( 'order.dt search.dt', function () {
            oTable.api().column(0, {search:'applied', order:'applied'}).nodes().each( function (cell, i) {
                cell.innerHTML = i+1;
            } );
        } ).draw();

    } );

      </script>

@endsection

@section('content')

    <div class="content-wrapper">
      <!-- Content Header (Page header) -->
      <section class="content-header">
        <h1>
          Fixed Asset
          <small>Administration</small>
        </h1>
        <ol class="breadcrumb">
          <li><a href="{{ url('/home') }}"><i class="fa fa-dashboard"></i> Home</a></li>
        <li><a href="#">Administration</a></li>
          <li class="active">Fixed Asset</li>
        </ol>
      </section>

    <!-- Main content -->
    <section class="content">
      <div class="row">
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

             <div class="modal fade" id="AssignModal" role="dialog" aria-labelledby="myModalLabel">
               <div class="modal-dialog">
                 <div class="modal-content">
                   <div class="modal-header">
                     <div id="assign-alert" class="alert alert-warning alert-dismissible" style="display:none;">
                       <h4><i class="icon fa fa-check"></i> Alert!</h4>
                       <div id="assignmessage"></div>
                     </div>
                     <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                     <h4 class="modal-title" id="myModalLabel">Assign Asset</h4>

                   </div>

                   <div class="modal-body">

                     <div class="form-group">
                       <label>Date : </label>

                       <div class="input-group date">
                         <div class="input-group-addon">
                           <i class="fa fa-calendar"></i>
                         </div>
                         <input type="text" class="form-control pull-right" id="Date" name="Date" value="{{ date("d-M-Y") }}">
                       </div>
                       <!-- /.input group -->
                     </div>

                     <div class="form-group">
                         <label>Assign to [Department]</label>

                           <input type="hidden" id="assignassetid" name="assignassetid" value=0>
                           <select class="form-control select2" id="ProjectId" name="ProjectId" style="width: 100%;">
                             <option value="0">No Department</option>

                             @foreach ($projects as $project)
                                <option value="{{$project->Id}}">{{$project->Project_Name}}</option>
                             @endforeach
                           </select>


                       </div>
                       <div class="form-group">
                         <label>Assign to [Staff]</label>


                           <select class="form-control select2" id="UserId" name="UserId" style="width: 100%;">
                              <option value="0"></option>
                             @foreach ($users as $user)
                                <option value="{{$user->Id}}">{{$user->Name}}</option>
                             @endforeach
                           </select>

                       </div>

                   </div>
                   <div class="modal-footer">
                     <center><img src="{{ URL::to('/') ."/img/ajax-loader.gif" }}" width="50px" height="50px" alt="Loading" name='ajaxloader' id="ajaxloader"></center>
                     <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                     <button type="button" class="btn btn-primary" onclick="callassign()">Assign</button>
                   </div>
                 </div>
               </div>
             </div>

             <div class="modal fade" id="ReturnedModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
               <div class="modal-dialog" role="document">
                 <div class="modal-content">
                   <div class="modal-header">
                     <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                     <h4 class="modal-title" id="myModalLabel">Return Asset</h4>
                   </div>
                   <div class="modal-body">
                        <input type="hidden" id="returnassetid" name="returnassetid" value=0>
                        <input type="hidden" id="returntrackingid" name="returntrackingid" value=0>
                        <input type="hidden" id="returnuserid" name="returnuserid" value=0>
                        <input type="hidden" id="returnprojectid" name="returnprojectid" value=0>
                       Are you sure the asset already returned?
                   </div>
                   <div class="modal-footer">
                     <center><img src="{{ URL::to('/') ."/img/ajax-loader.gif" }}" width="50px" height="50px" alt="Loading" name='ajaxloader2' id="ajaxloader2"></center>
                     <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                     <button type="button" class="btn btn-primary" onclick="callreturned()">Yes</button>
                   </div>
                 </div>
               </div>
             </div>

             <div class="modal fade" id="HistoryModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
               <div class="modal-dialog" role="document">
                 <div class="modal-content">
                   <div class="modal-header">
                     <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                     <h4 class="modal-title" id="myModalLabel">Asset History</h4>
                   </div>
                   <div class="modal-body" name="history" id="history">

                   </div>
                   <div class="modal-footer">
                     <center><img src="{{ URL::to('/') ."/img/ajax-loader.gif" }}" width="50px" height="50px" alt="Loading" name='ajaxloader3' id="ajaxloader3"></center>
                     <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                   </div>
                 </div>
               </div>
             </div>

             <div class="modal fade" id="AssetList" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
               <div class="modal-dialog" role="document">
                 <div class="modal-content">
                   <div class="modal-header">
                     <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                     <h4 class="modal-title" id="myModalLabel">Asset List</h4>
                   </div>
                   <div class="modal-body" name="list" id="list">

                   </div>
                   <div class="modal-footer">
                     <center><img src="{{ URL::to('/') ."/img/ajax-loader.gif" }}" width="50px" height="50px" alt="Loading" name='ajaxloader3' id="ajaxloader4"></center>
                     <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                   </div>
                 </div>
               </div>
             </div>

            <div class="box">
                <div class="box-body">

                  @foreach($category as $table)

                    @if ($table->Option==$type)
                      <a href="{{ url('/assettracking') }}/{{$table->Option}}"><button type="button" class="btn btn-danger btn">{{$table->Option}}</button></a>
                    @else
                      <a href="{{ url('/assettracking') }}/{{$table->Option}}"><button type="button" class="btn btn-success btn">{{$table->Option}}</button></a>
                    @endif

                  @endforeach

                  <!-- @if ($type=="Users")
                    <a href="{{ url('/assettracking') }}/Users"><button type="button" class="btn btn-danger btn">Users</button></a>
                  @else
                    <a href="{{ url('/assettracking') }}/Users"><button type="button" class="btn btn-success btn">Users</button></a>
                  @endif -->

                  <br><br>
                  @if ($type=="Users")
                  <table id="users" class="users" cellspacing="0" width="100%" padding="30px" style="font-size: 13px;">
                      <thead>
                        <tr class="search">
                          @foreach($usersasset as $key=>$value)

                            @if ($key==0)
                              <?php $i = 0; ?>

                              @foreach($value as $field=>$a)
                                  @if ($i==0|| $i==1 )
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

                            @foreach($usersasset as $key=>$value)

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
                        @foreach($usersasset as $user)

                              <tr id="row_{{ $i }}" >
                                  <td></td>

                                  @foreach($user as $key=>$value)
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


                  @else
                    <table id="assettrackingtable" class="assettrackingtable" cellspacing="0" width="100%" padding="30px" style="font-size: 13px;">
                      <thead>

                        <tr class="search">

                          @foreach($assettrackings as $key=>$value)

                            @if ($key==0)
                              <?php $i = 0; ?>

                              @foreach($value as $field=>$a)

                                @if ($type=="Car")
                                  @if ($i==0 || $i==1 || $i==2 || $i==5 || $i==6 )
                                    <th align='center'><input type='hidden' class='search_init' name='{{$i}}'></th>
                                  @else
                                    <th align='center'><input type='text' class='search_init' name='{{$i}}'></th>
                                  @endif
                                @elseif ($type=="Inverter")
                                  @if ($i==0 || $i==1 || $i==2 || $i==4 || $i==6 || $i==10)
                                    <th align='center'><input type='hidden' class='search_init' name='{{$i}}'></th>
                                  @else
                                    <th align='center'><input type='text' class='search_init' name='{{$i}}'></th>
                                  @endif
                                @elseif ($type=="Handphone")
                                  @if ($i==0 || $i==1 || $i==2 || $i==4 || $i==9 || $i==10)
                                    <th align='center'><input type='hidden' class='search_init' name='{{$i}}'></th>
                                  @else
                                    <th align='center'><input type='text' class='search_init' name='{{$i}}'></th>
                                  @endif
                                @elseif ($type=="Sim Card")
                                  @if ($i==0 || $i==1 || $i==2 || $i==4 || $i==6 || $i==7 || $i==8 || $i==9 || $i==10)
                                    <th align='center'><input type='hidden' class='search_init' name='{{$i}}'></th>
                                  @else
                                    <th align='center'><input type='text' class='search_init' name='{{$i}}'></th>
                                  @endif
                                @else
                                  @if ($i==0 || $i==1 || $i==2 || $i==4 || $i==6)
                                    <th align='center'><input type='hidden' class='search_init' name='{{$i}}'></th>
                                  @else
                                    <th align='center'><input type='text' class='search_init' name='{{$i}}'></th>
                                  @endif
                                @endif

                                  <?php $i ++; ?>
                              @endforeach

                              <th align='center'><input type='text' class='search_init' name='{{$i}}'></th>
                              <th align='center'><input type='text' class='search_init' name='{{$i}}'></th>

                            @endif

                          @endforeach
                        </tr>

                          {{-- prepare header search textbox --}}
                          <tr>
                            @foreach($assettrackings as $key=>$value)

                              @if ($key==0)
                                <td/></td>
                                <td/>Action</td>
                                @foreach($value as $field=>$value)
                                    <td/>{{ $field }}</td>
                                @endforeach

                              @endif

                            @endforeach
                          </tr>
                      </thead>
                      <tbody>



                      </tbody>
                        <tfoot></tfoot>
                    </table>
                   @endif


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

  $(function () {

    //Initialize Select2 Elements
    $(".select2").select2();

    $('#Date').datepicker({
      autoclose: true,
        format: 'dd-M-yyyy',
    });

});

    function assign(AssetId)
    {
      $('#assignassetid').val(AssetId);
      $('#AssignModal').modal('show');

    }

    function returned(AssetId,TrackingId,UserId,ProjectId)
    {
      $('#returnassetid').val(AssetId);
      $('#returntrackingid').val(TrackingId);
      $('#returnuserid').val(UserId);
      $('#returnprojectid').val(ProjectId);
      $('#ReturnedModal').modal('show');

    }

    function history(AssetId)
    {
      $('#HistoryModal').modal('show');
      $("#history").html("");

      $.ajaxSetup({
         headers: { 'X-CSRF-Token' : $('meta[name=_token]').attr('content') }
      });

      $("#ajaxloader3").show();

      $.ajax({
                  url: "{{ url('/asset/history') }}",
                  method: "POST",
                  data: {
                    AssetId:AssetId,Type:"{{$type}}"
                  },
                  success: function(response){
                    if (response==0)
                    {
                      var message ="Failed to retrieve asset history!";
                      $("#warning-alert ul").html(message);
                      $("#warning-alert").show();
                      $('#ReturnedModal').modal('hide')

                      $("#ajaxloader3").hide();
                    }
                    else {

                      $("#exist-alert").hide();


                      var myObject = JSON.parse(response);

                          @if ($type=="Car")

                          var display='<table border="1" align="center" class="historytable" cellspacing="0" width="100%" padding="30px" style="font-size: 13px;">';
                          display+='<tr class="historyheader"><td>Date</td><td>Project Name</td><td>Holder</td><td>Car_No</td><td>Status</td></tr>';

                          $.each(myObject, function(i,item){

                                  if (item.Project_Name===null)
                                  {
                                    item.Project_Name=" - ";
                                  }

                                  display+="<tr>";
                                  display+='<td>'+item.Date+'</td><td>'+item.Project_Name+'</td><td>'+item.Name+'</td><td>'+item.Car_No+'</td><td>'+item.Status+'</td>';
                                  display+="</tr>";
                          });

                      @else

                          var display='<table border="1" align="center" class="historytable" cellspacing="0" width="100%" padding="30px" style="font-size: 13px;">';
                          display+='<tr class="historyheader"><td>Date</td><td>Project Name</td><td>Holder</td><td>Status</td></tr>';

                          $.each(myObject, function(i,item){

                                  if (item.Project_Name===null)
                                  {
                                    item.Project_Name=" - ";
                                  }

                                  display+="<tr>";
                                  display+='<td>'+item.Date+'</td><td>'+item.Project_Name+'</td><td>'+item.Name+'</td><td>'+item.Status+'</td>';
                                  display+="</tr>";
                          });

                      @endif

                      // $.each(myObject, function(i, item) {
                      //       // display+="<tr>";
                      //       // display+='<td>'+item.Date+'</td><td>'+item.Project_Name+'</td><td>'+item.Name+'</td><td>'+item.Status+'</td>';
                      //       // display+="</tr>";
                      // });​

                      // for (i in myObject)
                      // {
                      //   alert(myObject[i]["Date"]);
                      //   display+="<tr>";
                      //     display+="<td>";
                      //       display='<tr><th>'+myObject[i]["Date"]+'</th><th>'+myObject[i]["Project_Name"]+'</th><th>'+myObject[i]["Name"]+'</th><th>'+myObject[i]["Status"]+'</th></tr>';
                      //     display+="</td>";
                      //   display+="</tr>";
                      //
                      // }

                      display+="</table>";

                      $("#history").html(display);

                      $("#ajaxloader3").hide();
                    }
          }
      });

    }

    function callassign()
    {
      $.ajaxSetup({
         headers: { 'X-CSRF-Token' : $('meta[name=_token]').attr('content') }
      });

      assignassetid=$('[name="assignassetid"]').val();
      projectid=$('[name="ProjectId"]').val();
      userid=$('[name="UserId"]').val();
      date=$('[name="Date"]').val();

      if (userid==0)
      {
        $("#assignmessage").html('"Assign to" field is required.');
        $("#assign-alert").show();
      }
      else {
        $("#assignmessage").html("");
        $("#assign-alert").hide();
        $("#ajaxloader").show();

        $.ajax({
                    url: "{{ url('/asset/assign') }}",
                    method: "POST",
                    data: {
                      UserId:userid,
                      Date:date,
                      ProjectId:projectid,
                      AssetId:assignassetid
                    },
                    success: function(response){
                      if (response==0)
                      {
                        var message ="Failed to assign asset!";
                        $("#warning-alert ul").html(message);
                        $("#warning-alert").show();

                        setTimeout(function() {
                          $("#warning-alert").fadeOut();
                        }, 6000);

                        $('#AssignModal').modal('hide')

                        $("#ajaxloader").hide();
                      }
                      else {
                        var message ="Asset Assigned!";
                        $("#update-alert ul").html(message);
                        $("#update-alert").show();

                        setTimeout(function() {
                          $("#update-alert").fadeOut();
                        }, 6000);

                        $("#UserId").val("").change();
                        $("#ProjectId").val("").change();
                        //$("#Template").val(response).change();
                        $("#exist-alert").hide();
                        $('#AssignModal').modal('hide')

                        oTable.ajax.reload();

                        $("#ajaxloader").hide();
                      }
            }
        });
      }

    }

    function callreturned()
    {
      $.ajaxSetup({
         headers: { 'X-CSRF-Token' : $('meta[name=_token]').attr('content') }
      });

      returnassetid=$('[name="returnassetid"]').val();
      returntrackingid=$('[name="returntrackingid"]').val();
      returnuserid=$('[name="returnuserid"]').val();
      returnprojectid=$('[name="returnprojectid"]').val();

      $("#ajaxloader2").show();

      $.ajax({
                  url: "{{ url('/asset/returned') }}",
                  method: "POST",
                  data: {
                    AssetId:returnassetid,
                    TrackingId:returntrackingid,
                    UserId:returnuserid,
                    ProjectId:returnprojectid
                  },
                  success: function(response){
                    if (response==0)
                    {
                      var message ="Failed to return asset!";
                      $("#warning-alert ul").html(message);
                      $("#warning-alert").show();

                      setTimeout(function() {
                        $("#warning-alert").fadeOut();
                      }, 6000);

                      $('#ReturnedModal').modal('hide')

                      $("#ajaxloader2").hide();
                    }
                    else {
                      var message ="Asset Returned!";
                      $("#update-alert ul").html(message);
                      $("#update-alert").show();

                      setTimeout(function() {
                        $("#update-alert").fadeOut();
                      }, 6000);
                      //$("#Template").val(response).change();
                      $("#exist-alert").hide();
                      $('#ReturnedModal').modal('hide')

                      oTable.ajax.reload();

                      $("#ajaxloader2").hide();
                    }
          }
      });
    }

    function assetlist(userid)
    {
        $('#AssetList').modal('show');
        $("#list").html("");

        $.ajaxSetup({
          headers: { 'X-CSRF-Token' : $('meta[name=_token]').attr('content') }
        });

        $("#ajaxloader4").show();

        $.ajax({
                    url: "{{ url('/asset/assetlist') }}",
                    method: "POST",
                    data: {
                      Id:userid
                    },
                    success: function(response){
                      if (response==0)
                      {
                        var message ="Failed to retrieve asset list!";
                        $("#warning-alert ul").html(message);
                        $("#warning-alert").show();
                        $('#ReturnedModal').modal('hide')

                        $("#ajaxloader4").hide();
                      }
                      else {

                        $("#exist-alert").hide();


                        var myObject = JSON.parse(response);

                            var display='<table border="1" align="center" class="historytable" cellspacing="0" width="100%" padding="30px" style="font-size: 13px;">';
                            display+='<tr class="historyheader"><td>Asset Type</td><td>Car_No</td><td>Asset Label</td><td>Brand</td><td>Model_No</td><td>Serial_No</td><td>IMEI</td></tr>';

                            $.each(myObject, function(i,item){

                                    if (item.Project_Name===null)
                                    {
                                      item.Project_Name=" - ";
                                    }

                                    display+="<tr>";
                                    display+='<td>'+item.Type+'</td><td>'+item.Car_No+'</td><td>'+item.Label+'</td><td>'+item.Brand+'</td><td>'+item.Model_No+'</td><td>'+item.Serial_No+'</td><td>'+item.IMEI+'</td>';
                                    display+="</tr>";
                            });

                        display+="</table>";

                        $("#list").html(display);

                        $("#ajaxloader4").hide();
                      }
            }
        });

    }

  </script>

@endsection
