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
      .tableheader{
        background-color: gray;
      }

      .interntable{
        text-align: center;
      }

      #newbilltable > tbody > tr > td,  #newbilltable > thead > tr > th{
        white-space: nowrap;
      }
      #updatebilltable > tbody > tr > td,  #updatebilltable > thead > tr > th{
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
      <script type="text/javascript" language="javascript" src="{{ asset('/plugin/datatables/extensions/Autocomplete/editor.autoComplete.js') }}"></script>
      <script type="text/javascript" language="javascript" src="{{ asset('/plugin/datatables/extensions/Select2/editor.select2.js') }}"></script>


      {{-- chart js --}}
      <script type="text/javascript" language="javascript" src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.4.0/Chart.bundle.min.js"></script>

      <script type="text/javascript" language="javascript" class="init">

         var listtable;
         var listeditor;
         var billtable;
         var billeditor;
         var rangetable;

         $(document).ready(function() {

           billeditor = new $.fn.dataTable.Editor( {
                   ajax: {
                      "url": "{{ asset('/Include/phonebill.php') }}",
                      "data": {
                          "type": "{{ $type }}"
                      }
                    },
                   table: "#bill",
                   idSrc: "phonebills.Id",
                   fields: [

                          {
                                   label: "Registered_Name:",
                                   name: "phonebills.Registered_Name",
                                   type:  'select2',
                                   options: [
                                      { label :"", value: "" },
                                      @foreach($company as $name)
                                          { label :"{{$name->Option}}", value: "{{$name->Option}}" },
                                      @endforeach

                                  ],

                           },{
                                   label: "Type:",
                                   name: "phonebills.Type",
                                   type: "hidden",
                                   def: "{{ $type }}"
                           },
                           @if($type == "RedOne")
                           {
                                   label: "Account_No:",
                                   name: "phonebills.Account_No"

                           },{
                                   label: "Bill_No:",
                                   name: "phonebills.Bill_No"

                           },{
                                   label: "Phone_No:",
                                   name: "phonebills.Phone_No",
                                   type: "select2",
                                  options: [
                                      { label :"", value: "" },
                                      @foreach($phone_no as $phone)
                                          { label :"{{$phone->Phone_No}}", value: "{{$phone->Phone_No}}" },
                                      @endforeach

                                  ],


                           },{
                                   label: "Current Holder:",
                                   name: "phonebills.Current_Holder"

                           },{
                                   label: "Position:",
                                   name: "phonebills.Position",
                                   type:  'select2',
                                   options: [
                                      { label :"", value: "" },
                                      @foreach($position as $positions)
                                          { label :"{{$positions->Position}}", value: "{{$positions->Position}}" },
                                      @endforeach

                                  ],

                           },{
                                   label: "Amount:",
                                   name: "phonebills.Amount"

                           },{
                                   label: "GST:",
                                   name: "phonebills.GST"

                           },{
                                   label: "Total:",
                                   name: "phonebills.Total"

                           },{
                                   label: "Bill_Date:",
                                   name: "phonebills.Bill_Date",
                                   type:   'datetime',
                                   format: 'DD-MMM-YYYY'

                           },{
                                   label: "Due_Date:",
                                   name: "phonebills.Due_Date",
                                   type:   'datetime',
                                   format: 'DD-MMM-YYYY'

                           },{
                                   label: "Credit_Card_No:",
                                   name: "phonebills.Credit_Card_No"

                           },{
                                   label: "Transaction_Date:",
                                   name: "phonebills.Transaction_Date",
                                   type:   'datetime',
                                   format: 'DD-MMM-YYYY'

                           },{
                                   label: "Transfer_Amount:",
                                   name: "phonebills.Transfer_Amount"

                           },{
                                   label: "Remarks:",
                                   name: "phonebills.Remarks"

                           },
                           @elseif($type == "Maxis" || $type == "Celcom")
                           {
                                   label: "Phone_No:",
                                  name: "phonebills.Phone_No",
                                  type: "select2",
                                  options: [
                                      { label :"", value: "" },
                                      @foreach($phone_no as $phone)
                                          { label :"{{$phone->Phone_No}}", value: "{{$phone->Phone_No}}" },
                                      @endforeach

                                  ],


                           },{
                                   label: "Account_No:",
                                   name: "phonebills.Account_No"

                           },{
                                   label: "Bill_No:",
                                   name: "phonebills.Bill_No"

                           },{

                                     label: "Current Holder:",
                                     name: "phonebills.UserId",
                                     type:  'select2',
                                     opts: {
                                        data : [
                                          { text :"", id: "" },
                                          @foreach($users as $position => $position_users)
                                          {
                                            text: "{{ $position }}",
                                            children: [
                                            @foreach($position_users as $user)
                                            {
                                                id: {{ $user->Id }},
                                                text: "{{ $user->Name }}"
                                            },
                                            @endforeach
                                            ]
                                          },
                                          @endforeach
                                        ]

                                    },

                           },{
                                   label: "Package:",
                                   name: "phonebills.Package"

                           },{
                                   label: "Amount:",
                                   name: "phonebills.Amount"

                           // },{
                           //         label: "GST:",
                           //         name: "phonebills.GST"

                           },{
                                   label: "Total:",
                                   name: "phonebills.Total"

                           },{
                                   label: "Bill_Date:",
                                   name: "phonebills.Bill_Date",
                                   type:   'datetime',
                                   format: 'DD-MMM-YYYY'

                           },{
                                   label: "Due_Date:",
                                   name: "phonebills.Due_Date",
                                   type:   'datetime',
                                   format: 'DD-MMM-YYYY'

                           },{
                                   label: "Credit_Card_No:",
                                   name: "phonebills.Credit_Card_No"

                           },{
                                   label: "Transaction_Date:",
                                   name: "phonebills.Transaction_Date",
                                   type:   'datetime',
                                   format: 'DD-MMM-YYYY'

                           },{
                                   label: "Transfer_Amount:",
                                   name: "phonebills.Transfer_Amount"

                           },{
                                   label: "Remarks:",
                                   name: "phonebills.Remarks",
                                   type:"textarea"

                           },
                           @else
                           {
                                   label: "Phone_No:",
                                  name: "phonebills.Phone_No",
                                  type: "select2",
                                  options: [
                                      { label :"", value: "" },
                                      @foreach($phone_no as $phone)
                                          { label :"{{$phone->Phone_No}}", value: "{{$phone->Phone_No}}" },
                                      @endforeach

                                  ],


                           },{
                                   label: "Account_No:",
                                   name: "phonebills.Account_No"

                           },{
                                   label: "Bill_No:",
                                   name: "phonebills.Bill_No"
                           },{
                                   label: "Remarks:",
                                   name: "phonebills.Remarks",
                                   type:"textarea"

                           },{
                                   label: "Current Holder:",
                                   name: "phonebills.Current_Holder"

                           },{
                                   label: "Position:",
                                   name: "phonebills.Position",
                                   type:  'select2',
                                   options: [
                                      { label :"", value: "" },
                                      @foreach($position as $positions)
                                          { label :"{{$positions->Position}}", value: "{{$positions->Position}}" },
                                      @endforeach

                                  ],

                           },{
                                   label: "Amount:",
                                   name: "phonebills.Amount"

                           },{
                                   label: "Package:",
                                   name: "phonebills.Package"

                           // },{
                           //         label: "GST:",
                           //         name: "phonebills.GST"

                           },{
                                   label: "Total:",
                                   name: "phonebills.Total"

                           },{
                                   label: "Bill_Date:",
                                   name: "phonebills.Bill_Date",
                                   type:   'datetime',
                                   format: 'DD-MMM-YYYY'

                           },{
                                   label: "Due_Date:",
                                   name: "phonebills.Due_Date",
                                   type:   'datetime',
                                   format: 'DD-MMM-YYYY'

                           },{
                                   label: "Credit_Card_No:",
                                   name: "phonebills.Credit_Card_No"

                           },{
                                   label: "Transaction_Date:",
                                   name: "phonebills.Transaction_Date",
                                   type:   'datetime',
                                   format: 'DD-MMM-YYYY'

                           },{

                                     label: "Current Holder:",
                                     name: "phonebills.UserId",
                                     type:  'select2',
                                     options: [
                                        { label :"", value: "0" },
                                        @foreach($users as $user)
                                            { label :"{{$user->Name}}", value: "{{$user->Id}}" },
                                        @endforeach

                                    ],

                             },{
                                   label: "Transfer_Amount:",
                                   name: "phonebills.Transfer_Amount"

                           }
                           @endif
                   ]
           } );


           billtable = $('#bill').dataTable( {
                   ajax: {
                      "url": "{{ asset('/Include/phonebill.php') }}",
                      "data": {
                          "type": "{{ $type }}"
                      }
                    },
                   dom: "Blrftip",
                   bAutoWidth: true,
                   order: [[ 2, "desc" ]],
                    sScrollY: "100%",
                   sScrollX: "100%",
                   @if ($type=="RedOne")
                     columnDefs: [{ "visible": false, "targets": [2,3,8,9,12,13,14] },{"className": "dt-center", "targets": "_all"}],
                   @elseif ($type=="Maxis" || $type=="Celcom")
                    columnDefs: [{ "visible": false, "targets": [2,3,10,11,14] },{"className": "dt-center", "targets": "_all"}],
                   @else
                    columnDefs: [{ "visible": false, "targets": [3,14] },{"className": "dt-center", "targets": "_all"}],
                   @endif
                   bScrollCollapse: true,
                   columns: [
                     {
                        data: null,
                        "render": function ( data, type, full, meta ) {
                          return '<input type="checkbox" name="selectrow1" id="selectrow1" class="selectrow1" value="'+full.phonebills.Id+'" onclick="uncheck(0)">';
                        },
                        "orderable": false
                     },
                     {data: null, title:"No"},
                     {data:"phonebills.Id", title:"Id"},
                     {data:'phonebills.Type', title:"Type"},
                     {data:'phonebills.Registered_Name', title:"Registered_Name"},
                     {data:'phonebills.Account_No', title:"Account_No"},
                     {data:'phonebills.Bill_No', title:"Bill_No"},

                     {data:'phonebills.Phone_No', title:"Phone_No"},
                     {data:'users.Name',editField: "phonebills.UserId", title:"Current_Holder"},
                     {data:'users.Position', title:"Position"},
                     {data:'phonebills.Current_Holder', title:"Current_Holder"},
                     {data:'phonebills.Position', title:"Position"},

                     {data:'phonebills.Package', title:"Package"},
                     {data:'phonebills.Amount', title:"Amount"},
                     {data:'phonebills.GST', title:"GST Amount"},
                     { title:"Total",
                        sortable: false,
                        "render": function ( data, type, full, meta ) {
                        return (parseFloat(full.phonebills.Amount)+parseFloat(full.phonebills.GST)).toFixed(2);
                      }
                     },
                     {data:'phonebills.Bill_Date', title:"Bill Date"},
                     {data:'phonebills.Due_Date', title:"Due Date"},
                     {data:'phonebills.Credit_Card_No', title:"Credit Card No"},
                     {data:'phonebills.Transaction_Date', title:"Transaction Date"},
                     {data:'phonebills.Transfer_Amount', title:"Transfer Amount"},
                     {data:'phonebills.Remarks', title:"Remarks"}
                   ],
                   autoFill: {
                      editor:  billeditor
                  },
                  select: true,
                  buttons: [
                        {
                            text: 'Update Bill',
                            action: function ( e, dt, node, config ) {
                              $('.select2').select2({tags:true});

                              updateBill();

                          },
                        },
                        { extend: "create", text: "New", editor: billeditor },
                        { extend: "edit", editor: billeditor },

                        { extend: "remove", editor: billeditor },
                        {
                                extend: 'collection',
                                text: 'Export',
                                buttons: [
                                        'excel',
                                        'csv'                                ]
                        }
                  ],

               });

               // $('#bill').on( 'click', 'tbody td:not(.child), tbody span.dtr-data', function (e) {
               //       billeditor.inline( this, {
               //      onBlur: 'submit'
               //     } );
               // } );

               rangetable = $('#rangetable').dataTable( {

                       dom: "Blrftip",
                       bAutoWidth: true,
                       aaSorting:false,
                       sScrollY: "100%",
                       sScrollX: "100%",
                       columnDefs: [{ "visible": false, "targets": [] },{"className": "dt-center", "targets": "_all"}],
                       bScrollCollapse: true,
                       columns: [
                         {data: "", title:"No"},
                         {data:'phonebills.Bill_Account', title:"Bill_Account"},
                         @foreach($daterange as $range)
                         { data : "{{$range}}", titlte : "{{$range}}", name : "{{$range}}"},
                         @endforeach

                       ],

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


               billtable.api().on( 'order.dt search.dt', function () {
                   billtable.api().column(1, {search:'applied', order:'applied'}).nodes().each( function (cell, i) {
                       cell.innerHTML = i+1;
                   } );
               } ).draw();

               rangetable.api().on( 'order.dt search.dt', function () {
                   rangetable.api().column(1, {search:'applied', order:'applied'}).nodes().each( function (cell, i) {
                       cell.innerHTML = i+1;
                   } );
               } ).draw();


               $(".bill thead input").keyup ( function () {

                       /* Filter on the column (the index) of this element */
                       if ($('#bill').length > 0)
                       {

                           var colnum=document.getElementById('bill').rows[0].cells.length;

                           if (this.value=="[empty]")
                           {

                              billtable.fnFilter( '^$', this.name,true,false );
                           }
                           else if (this.value=="[nonempty]")
                           {

                              billtable.fnFilter( '^(?=\\s*\\S).*$', this.name,true,false );
                           }
                           else if (this.value.startsWith("!")==true && this.value.length>1)
                           {

                              billtable.fnFilter( '^(?'+ this.value +').*', this.name,true,false );
                           }
                           else if (this.value.startsWith("!")==false)
                           {

                               billtable.fnFilter( this.value, this.name,true,false );
                           }
                       }



               } );

               $(".rangetable thead input").keyup ( function () {

                       /* Filter on the column (the index) of this element */
                       if ($('#rangetable').length > 0)
                       {

                           var colnum=document.getElementById('rangetable').rows[0].cells.length;

                           if (this.value=="[empty]")
                           {

                              rangetable.fnFilter( '^$', this.name,true,false );
                           }
                           else if (this.value=="[nonempty]")
                           {

                              rangetable.fnFilter( '^(?=\\s*\\S).*$', this.name,true,false );
                           }
                           else if (this.value.startsWith("!")==true && this.value.length>1)
                           {

                              rangetable.fnFilter( '^(?'+ this.value +').*', this.name,true,false );
                           }
                           else if (this.value.startsWith("!")==false)
                           {

                               rangetable.fnFilter( this.value, this.name,true,false );
                           }
                       }



               } );

               $('a[data-toggle="tab"]').on( 'shown.bs.tab', function (e) {
                 var target = $(e.target).attr("href") // activated tab

                   $.fn.dataTable.tables( {visible: true, api: true} ).columns.adjust();

               } );
           listeditor = new $.fn.dataTable.Editor( {
                   ajax: {
                      "url": "{{ asset('/Include/phone.php') }}",
                      "data": {
                          "type": "{{ $type }}"
                      }
                    },
                   table: "#list",
                   idSrc: "phones.Id",
                   fields: [

                          {
                                   label: "Registered_Name:",
                                   name: "phones.Registered_Name",
                                   type:  'select2',
                                   options: [
                                      { label :"", value: "" },
                                      @foreach($company as $name)
                                          { label :"{{$name->Option}}", value: "{{$name->Option}}" },
                                      @endforeach

                                  ],

                           },{
                                   label: "Type:",
                                   name: "phones.Type",
                                   type: "hidden",
                                   def: "{{ $type }}"
                           },
                           @if($type == "RedOne")
                           {
                                   label: "Account_No:",
                                   name: "phones.Account_No"

                           },{
                                   label: "Phone_No:",
                                   name: "phones.Phone_No",
                           },{
                                   label: "Current Holder:",
                                   name: "phones.Current_Holder"

                           },{
                                   label: "Position:",
                                   name: "phones.Position",
                                   type:  'select2',
                                   options: [
                                      { label :"", value: "" },
                                      @foreach($position as $positions)
                                          { label :"{{$positions->Position}}", value: "{{$positions->Position}}" },
                                      @endforeach

                                  ],

                           },{
                                   label: "Remarks:",
                                   name: "phones.Remarks"

                           },
                           @elseif($type == "Maxis" || $type == "Celcom")
                           {
                                   label: "Phone_No:",
                                  name: "phones.Phone_No",
                           },{
                                   label: "Account_No:",
                                   name: "phones.Account_No"

                           },{

                                     label: "Current Holder:",
                                     name: "phones.UserId",
                                     type:  'select2',
                                     opts: {
                                        data : [
                                          { text :"", id: "" },
                                          @foreach($users as $position => $position_users)
                                          {
                                            text: "{{ $position }}",
                                            children: [
                                            @foreach($position_users as $user)
                                            {
                                                id: {{ $user->Id }},
                                                text: "{{ $user->Name }}"
                                            },
                                            @endforeach
                                            ]
                                          },
                                          @endforeach
                                        ]

                                    },

                           },{
                                   label: "Package:",
                                   name: "phones.Package"

                           },{
                                   label: "Remarks:",
                                   name: "phones.Remarks",
                                   type:"textarea"

                           },
                           @else
                           {
                                   label: "Phone_No:",
                                  name: "phones.Phone_No",
                           },{
                                   label: "Account_No:",
                                   name: "phones.Account_No"

                           },{
                                   label: "Remarks:",
                                   name: "phones.Remarks",
                                   type:"textarea"

                           },{
                                   label: "Current Holder:",
                                   name: "phones.Current_Holder"

                           },{
                                   label: "Position:",
                                   name: "phones.Position",
                                   type:  'select2',
                                   options: [
                                      { label :"", value: "" },
                                      @foreach($position as $positions)
                                          { label :"{{$positions->Position}}", value: "{{$positions->Position}}" },
                                      @endforeach

                                  ],

                           },{
                                   label: "Package:",
                                   name: "phones.Package"

                           },{

                                     label: "Current Holder:",
                                     name: "phones.UserId",
                                     type:  'select2',
                                     options: [
                                        { label :"", value: "0" },
                                        @foreach($users as $user)
                                            { label :"{{$user->Name}}", value: "{{$user->Id}}" },
                                        @endforeach

                                    ],

                             }
                           @endif
                   ]
           } );


           listtable = $('#list').dataTable( {
                   ajax: {
                      "url": "{{ asset('/Include/phone.php') }}",
                      "data": {
                          "type": "{{ $type }}"
                      }
                    },
                   dom: "Blrftip",
                   bAutoWidth: true,
                   order: [[ 2, "desc" ]],
                    sScrollY: "100%",
                   sScrollX: "100%",
                   @if ($type=="RedOne")
                     columnDefs: [{ "visible": false, "targets": [2,3,7,8,11] },{"className": "dt-center", "targets": "_all"}],
                   @elseif ($type=="Maxis" || $type=="Celcom")
                    columnDefs: [{ "visible": false, "targets": [2,3,9,10] },{"className": "dt-center", "targets": "_all"}],
                   @else
                    columnDefs: [{ "visible": false, "targets": [2,3] },{"className": "dt-center", "targets": "_all"}],
                   @endif
                   bScrollCollapse: true,
                   columns: [
                     {
                        data: null,
                        "render": function ( data, type, full, meta ) {
                          return '<input type="checkbox" name="selectrow0" id="selectrow0" class="selectrow0" value="'+full.phones.Id+'" onclick="uncheck(0)">';
                        },
                        "orderable": false
                     },

                     {data: null, title:"No"},
                     {data:"phones.Id", title:"Id"},
                     {data:'phones.Type', title:"Type"},
                     {data:'phones.Registered_Name', title:"Registered_Name"},
                     {data:'phones.Account_No', title:"Account_No"},

                     {data:'phones.Phone_No', title:"Phone_No"},
                     {data:'users.Name',editField: "phones.UserId", title:"Current_Holder"},
                     {data:'users.Position', title:"Position"},
                     {data:'phones.Current_Holder', title:"Current_Holder"},
                     {data:'phones.Position', title:"Position"},

                     {data:'phones.Package', title:"Package"},
                     {data:'phones.Remarks', title:"Remarks"}
                   ],
                   autoFill: {
                      editor:  listeditor
                  },
                  select: true,
                  buttons: [
                        {
                            text: 'New Bill',
                            action: function ( e, dt, node, config ) {
                              $('.select2').select2({tags:true});

                              newbill();

                          },
                        },
                        { extend: "create", text: "New Number", editor: listeditor },
                        { extend: "edit", editor: listeditor },

                        { extend: "remove", editor: listeditor },
                        {
                                extend: 'collection',
                                text: 'Export',
                                buttons: [
                                        'excel',
                                        'csv'                                ]
                        }
                  ],

               });

               // $('#list').on( 'click', 'tbody td:not(.child), tbody span.dtr-data', function (e) {
               //       listeditor.inline( this, {
               //      onBlur: 'submit'
               //     } );
               // } );

               rangetable = $('#rangetable').dataTable( {

                       dom: "Blrftip",
                       bAutoWidth: true,
                       aaSorting:false,
                       sScrollY: "100%",
                       sScrollX: "100%",
                       columnDefs: [{ "visible": false, "targets": [] },{"className": "dt-center", "targets": "_all"}],
                       bScrollCollapse: true,
                       columns: [
                         {data: "", title:"No"},
                         {data:'phones.Bill_Account', title:"Bill_Account"},
                         @foreach($daterange as $range)
                         { data : "{{$range}}", titlte : "{{$range}}", name : "{{$range}}"},
                         @endforeach

                       ],

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


               listtable.api().on( 'order.dt search.dt', function () {
                   listtable.api().column(1, {search:'applied', order:'applied'}).nodes().each( function (cell, i) {
                       cell.innerHTML = i+1;
                   } );
               } ).draw();

               rangetable.api().on( 'order.dt search.dt', function () {
                   rangetable.api().column(1, {search:'applied', order:'applied'}).nodes().each( function (cell, i) {
                       cell.innerHTML = i+1;
                   } );
               } ).draw();


               $(".list thead input").keyup ( function () {

                       /* Filter on the column (the index) of this element */
                       if ($('#list').length > 0)
                       {

                           var colnum=document.getElementById('list').rows[0].cells.length;

                           if (this.value=="[empty]")
                           {

                              listtable.fnFilter( '^$', this.name,true,false );
                           }
                           else if (this.value=="[nonempty]")
                           {

                              listtable.fnFilter( '^(?=\\s*\\S).*$', this.name,true,false );
                           }
                           else if (this.value.startsWith("!")==true && this.value.length>1)
                           {

                              listtable.fnFilter( '^(?'+ this.value +').*', this.name,true,false );
                           }
                           else if (this.value.startsWith("!")==false)
                           {

                               listtable.fnFilter( this.value, this.name,true,false );
                           }
                       }



               } );

               $(".rangetable thead input").keyup ( function () {

                       /* Filter on the column (the index) of this element */
                       if ($('#rangetable').length > 0)
                       {

                           var colnum=document.getElementById('rangetable').rows[0].cells.length;

                           if (this.value=="[empty]")
                           {

                              rangetable.fnFilter( '^$', this.name,true,false );
                           }
                           else if (this.value=="[nonempty]")
                           {

                              rangetable.fnFilter( '^(?=\\s*\\S).*$', this.name,true,false );
                           }
                           else if (this.value.startsWith("!")==true && this.value.length>1)
                           {

                              rangetable.fnFilter( '^(?'+ this.value +').*', this.name,true,false );
                           }
                           else if (this.value.startsWith("!")==false)
                           {

                               rangetable.fnFilter( this.value, this.name,true,false );
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
      Phone Bills
      <small>Administration</small>
      </h1>

      <ol class="breadcrumb">
      <li><a href="{{ url('/home') }}"><i class="fa fa-dashboard"></i> Home</a></li>
      <li><a href="#">Administration</a></li>
      <li class="active">Phone Bills</li>
      </ol>
    </section>

    <section class="content">
      <div class="modal fade" id="newbillmodal">
        <div class="modal-dialog">
          <div class="modal-content">
            <div class="modal-header">
              <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span></button>
              <h4 class="modal-title">New Bill</h4>
            </div>
            <form id="newbillform">

            <div class="modal-body">
              <div class="row">
                <div class="col-md-4">
                  <div class="form-group">
                    <label class="form-label">Bill No</label><input class="form-control" type="text" name="Bill_No">
                  </div>
                </div>
                <div class="col-md-4">
                  <div class="form-group">
                    <label class="form-label">Bill Date</label><input type="text" class="datepicker form-control" name="Bill_Date">
                  </div>
                </div>
                <div class="col-md-4">
                  <div class="form-group">
                    <label class="form-label">Due Date</label><input type="text" class="datepicker form-control" name="Due_Date">
                  </div>
                </div>
              </div>

              <div class="table-responsive">

                <table class="table table-condensed table-hover" id="newbilltable">
                  <thead>
                    <tr>
                      <th>Phone No</th>
                      <th>Account No</th>
                      <th>Amount</th>
                      <th>Total</th>
                    </tr>
                  </thead>
                  <tbody>
                  </tbody>
                </table>
              </div>

            </div>
            <div class="modal-footer">
                <button type="submit" class="btn btn-success btn-small" id="btnnewbill">Add Bill</button>
            </div>
            </form>

          </div>
        </div>
      </div>

      <div class="modal fade" id="updatebillmodal">
        <div class="modal-dialog">
          <div class="modal-content">
            <div class="modal-header">
              <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span></button>
              <h4 class="modal-title">Update Bill</h4>
            </div>
            <form id="updatebillform">

            <div class="modal-body table-responsive">

                <table class="table table-hover" id="updatebilltable">
                  <thead>
                    <tr>
                      <th>Account No</th>
                      <th>Bill No</th>
                      <th>Phone No</th>
                      <th>Package</th>
                      <th>Amount</th>
                      <th>Bill Date</th>
                      <th>Due Date</th>
                      <th>Credit Card No</th>
                      <th>Transaction Date</th>
                      <th>Transfer Amount</th>
                      <th>Remarks</th>
                    </tr>
                  </thead>
                  <tbody>

                  </tbody>
                </table>

            </div>
            <div class="modal-footer">
                <button type="submit" class="btn btn-success btn-small" id="btnupdatebill">Update Entry</button>
            </div>
            </form>

          </div>
        </div>
      </div>
      <div class="row">
       <div class="col-md-12">

        <div class="box">
           <div class="box-body">
                @foreach($category as $table)
                  @if ($table->Option==$type)
                    <a href="{{ url('/phonebilltracker') }}/{{$table->Option}}"><button type="button" class="btn btn-danger btn-small">{{$table->Option}}</button></a>
                  @else
                    <a href="{{ url('/phonebilltracker') }}/{{$table->Option}}"><button type="button" class="btn btn-success btn-small">{{$table->Option}}</button></a>
                  @endif
                @endforeach
           </div>



         <div class="nav-tabs-custom">

           <ul class="nav nav-tabs">
             <li class="active"><a href="#listtab" data-toggle="tab">Master List</a></li>
             <li><a href="#billtab" data-toggle="tab" id="expenses1">Phone Bills</a></li>
           </ul>

           <br><br>

           <div class="tab-content">
             <div class="tab-pane" id="billtab">
                <table id="bill" class="bill" cellspacing="0" width="100%" padding="30px" style="font-size: 13px;">
                    <thead>
                      @if($bills)
                        <tr class="search">

                          @foreach($bills as $key=>$value)

                            @if ($key==0)
                              <?php $i = 0; ?>

                              @foreach($value as $field=>$a)
                                  @if ($i==0|| $i==1 || $i==2 || $i==3)
                                    <td align='center'><input type='hidden' class='search_init' name='{{$i}}' placemark='{{$a}}'></td>
                                  @else
                                    <td align='center'><input type='text' class='search_init' name='{{$i}}'  placemark='{{$a}}'></td>
                                  @endif

                                  <?php $i ++; ?>
                              @endforeach

                                <td align='center'><input type='text' class='search_init' name='{{$i}}'  placemark='{{$a}}'></td>
                                <td align='center'><input type='text' class='search_init' name='{{$i+1}}'  placemark='{{$a}}'></td>

                            @endif

                          @endforeach
                        </tr>

                      @endif
                        <tr>

                          @foreach($bills as $key=>$value)

                            @if ($key==0)
                                   <td><input type="checkbox" name="selectall" id="selectall1" value="all" onclick="checkall(1)"></td>
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
                      @foreach($bills as $bill)

                            <tr id="row_{{ $i }}" >
                                 <td></td>
                                 <td></td>
                                @foreach($bill as $key=>$value)

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
             <div class="active tab-pane" id="listtab">

                <table id="list" class="list" cellspacing="0" width="100%" padding="30px" style="font-size: 13px;">
                    <thead>
                      @if($list)
                      <tr class="search">
                        @foreach($list as $key=>$value)
                          @if ($key==0)
                            <?php $i = 0; ?>
                            @foreach($value as $field=>$a)
                                @if ($i==0|| $i==1 || $i==2 || $i==3)
                                  <td align='center'><input type='hidden' class='search_init' name='{{$i}}' placemark='{{$a}}'></td>
                                @else
                                  <td align='center'><input type='text' class='search_init' name='{{$i}}'  placemark='{{$a}}'></td>
                                @endif
                                <?php $i ++; ?>
                            @endforeach
                              <td align='center'><input type='text' class='search_init' name='{{$i}}'  placemark='{{$a}}'></td>
                              <td align='center'><input type='text' class='search_init' name='{{$i+1}}'></td>

                          @endif
                        @endforeach
                      </tr>
                      @endif
                      <tr>
                        @foreach($list as $key=>$value)
                          @if ($key==0)
                                 <td><input type="checkbox" name="selectall" id="selectall0" value="all" onclick="checkall(0)"></td>

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
                    @foreach($list as $lists)
                      <tr id="row_{{ $i }}" >
                           <td></td>
                           <td></td>
                          @foreach($lists as $key=>$value)

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

         </div>
        </div>
       </div>
      </div>

    </section>

</div>
<footer class="main-footer">
  <div class="pull-right hidden-xs">
    <b>Version</b> 1.0.0
  </div>
  <strong>Copyright &copy; 2014-2016 <a href="http://www.softoya.com">TrackerOnTheGo</a>.</strong> All rights
  reserved.
</footer>

<script>

$(function () {

 $('#range').daterangepicker({locale: {
    format: 'DD-MMM-YYYY'
  },startDate: '{{$start}}',
  endDate: '{{$end}}'});

  $('#Bill_Date').datepicker({
    autoclose: true,
      format: 'dd-M-yyyy',
  });

});

function refresh()
{
  var d=$('#range').val();
  var arr = d.split(" - ");

  window.location.href ="{{ url("/phonebilltracker") }}/"+arr[0]+"/"+arr[1];

}

 function updateBill() {
  var boxes = $(".selectrow1:checkbox:checked");
  var ids="";
  var payment_type = [
     { label :"CIMB - 0539", value: "CIMB - 0539" }
  ];
  var payment_options = "<option></option>";

  for (var i = 0; i < payment_type.length; i++) {
    payment_options += `<option>${payment_type[i].value}</option>`;
  }



  if (boxes.length>0)
  {
    var tableRows = '';
    for (var i = 0; i < boxes.length; i++) {
      ids+=boxes[i].value+",";
      var row = billtable.api().row('#row_' + boxes[i].value).data();

      tableRows += `<tr>
                      <td>${row.phonebills.Account_No}<input type="hidden" value="${row.phonebills.Id}" name="Id[${i}]"></td>
                      <td><input style="width:95px" class="form-control" name="Bill_No[${i}]" value="${row.phonebills.Bill_No}"></td>
                      <td>${row.phonebills.Phone_No}</td>
                      <td>${row.phonebills.Package}</td>
                      <td><input class="form-control" style="width:115px"  type="number" placeholder="0.00" required name="Amount[${i}]" min="0" value="${row.phonebills.Amount}" step="0.01" title="Amount" pattern="^\d+(?:\.\d{1,2})?$" onblur="this.value = parseFloat(this.value).toFixed(2);"></td>
                      <td><input style="width:115px" type="text" class="form-control datepicker" value="${row.phonebills.Bill_Date}" name="Bill_Date[${i}]"></td>
                      <td><input style="width:115px" type="text" class="form-control datepicker" value="${row.phonebills.Due_Date}" name="Due_Date[${i}]"></td>

                      <td><select style="width:115px" class="form-control select2" name="Credit_Card_No[${i}]"><option>${ row.phonebills.Credit_Card_No ? row.phonebills.Credit_Card_No : '' }</option>${payment_options}</select></td>
                      <td><input style="width:95px" class="form-control datepicker" name="Transaction_Date[${i}]"></td>
                      <td><input style="width:95px" class="form-control" type="number" placeholder="0.00" name="Transfer_Amount[${i}]" min="0" value="${row.phonebills.Transfer_Amount}" step="0.01" title="Currency" pattern="^\d+(?:\.\d{1,2})?$" onblur="this.value = parseFloat(this.value).toFixed(2);"></td>
                      <td><input style="width:95px" class="form-control" name="Remarks[${i}]"></td>
                    </tr>`;

    }

    ids=ids.substring(0, ids.length-1);

    $("#updatebilltable > tbody").html(tableRows);
    $('.datepicker').datepicker({
      autoclose: true,
        format: 'dd-M-yyyy',
    });

    $('.select2').select2({tags:true});
    $("#updatebillmodal").modal("show");

  }
  // console.log(listtable.api().rows().data())

}

$("#updatebillform").submit(function() {

   $("#btnupdatebill").prop("disabled", true);
   $.ajaxSetup({
      headers: { 'X-CSRF-Token' : $('meta[name=_token]').attr('content') }
   });

   $("#ajaxloader").show();

   $.ajax({
               url: "{{ url('/phonebills/' . $type .  '/updateentry') }}",
               method: "POST",
               data: $(this).serialize(),

               success: function(response){
                 $("#btnupdatebill").prop("disabled", false);
                 $("#updatebillmodal").modal("hide");
                 billtable.api().ajax.reload();
       }
   });
   return false;
});

$("#newbilltable").on("change",".amount", function(e) {
    var id = $(this).data('row-id');
    console.log($(this).data('row-id'));

    var amount = $("#amount_" + id).val();
    // var gst = $("#gst_" + id).val(amount * 0.06).val();
    // var gst = 0;
    // console.log(amount + gst);
    var total = $("#total_" + id).val(Number(amount).toFixed(2));
});
function newbill()
{
  // $('#Phone_Number').val(serialno);
  // $('.select2').select2({tags:true});

  var boxes = $(".selectrow0:checkbox:checked");

  var ids="";

  if (boxes.length>0)
  {
    var tableRows = '';
    for (var i = 0; i < boxes.length; i++) {
      ids+=boxes[i].value+",";
      var row = listtable.api().row('#row_' + boxes[i].value).data();
      tableRows += `<tr>
                      <td>${row.phones.Phone_No}<input type="hidden" value="${row.phones.Phone_No}" name="Phone_No[${i}]"><input type="hidden" value="${row.phones.Registered_Name}" name="Registered_Name[${i}]"><input type="hidden" value="${row.phones.Current_Holder}" name="Current_Holder[${i}]"><input type="hidden" value="${row.phones.Position}" name="Position[${i}]"><input type="hidden" value="${row.phones.UserId}" name="UserId[${i}]"><input type="hidden" value="${row.phones.Package}" name="Package[${i}]"></td>
                      <td>${row.phones.Account_No}<input type="hidden" value="${row.phones.Account_No}" name="Account_No[${i}]"></td>
                      <td><input style="width:95px" id="amount_${i}" data-row-id="${i}" class="form-control amount" type="number" placeholder="0.00" required name="Amount[${i}]" min="0" value="0.00" step="0.01" title="Amount" pattern="^\d+(?:\.\d{1,2})?$" onblur="this.value = parseFloat(this.value).toFixed(2);"></td>
                      <td><input style="width:95px" id="total_${i}" class="form-control" type="number" placeholder="0.00" required name="Total[${i}]" min="0" value="0.00" readonly></td>
                    </tr>`;


    }

    ids=ids.substring(0, ids.length-1);

    $("#newbilltable > tbody").html(tableRows);
    $('.datepicker').datepicker({
      autoclose: true,
        format: 'dd-M-yyyy',

    }).datepicker("setDate", new Date());;

    $('.select2').select2({tags:true});
    $("#newbillmodal").modal("show");

  }
  $('#NewBill').modal('show');




}
$("#newbillform").submit(function() {

   $("#btnnewbill").prop("disabled", true);
   $.ajaxSetup({
      headers: { 'X-CSRF-Token' : $('meta[name=_token]').attr('content') }
   });

   $("#ajaxloader").show();

   $.ajax({
               url: "{{ url('/phonebills/' . $type . '/newentry') }}",
               method: "POST",
               data: $(this).serialize(),

               success: function(response){
                 $("#btnnewbill").prop("disabled", false);
                 $("#newbillmodal").modal("hide");
                 $("#newbillform").trigger("reset");
                 // listtable.api().ajax.reload();
                 billtable.api().ajax.reload();
                 console.log(response);

       }
   });
   return false;
});
function sendbill()
{
  $.ajaxSetup({
     headers: { 'X-CSRF-Token' : $('meta[name=_token]').attr('content') }
  });

  phonenumber=$('[name="Phone_Number"]').val();
  userid=$('[name="UserId"]').val();
  billnumber=$('[name="Bill_Number"]').val();
  billdate=$('[name="Bill_Date"]').val();
  amount=$('[name="Amount"]').val();

  // $("#ajaxloader2").show();

  $.ajax({
              url: "{{ url('/phonebill/new') }}",
              method: "POST",
              data: {
                Phone_Number:phonenumber,
                UserId:userid,
                Bill_Number:billnumber,
                Bill_Date:billdate,
                Amount:amount
              },
              success: function(response){
                if (response==0)
                {
                  var message ="Failed to submit new bill!";
                  $("#warning-alert ul").html(message);
                  $("#warning-alert").show();

                  setTimeout(function() {
                    $("#warning-alert").fadeOut();
                  }, 6000);

                  $('#NewBill').modal('hide')

                  $("#ajaxloader2").hide();
                }
                else {
                  var message ="New bill sent!";
                  $("#update-alert ul").html(message);
                  $("#update-alert").show();

                  setTimeout(function() {
                    $("#update-alert").fadeOut();
                  }, 6000);
                  //$("#Template").val(response).change();
                  $("#exist-alert").hide();
                  $('#NewBill').modal('hide')

                  oTable.api().ajax.reload();

                  $("#ajaxloader2").hide();
                }
      }
  });
}

function uncheck(index)
{

  if (!$("#selectrow"+index).is(':checked')) {
    $("#selectall"+index).prop("checked", false)
  }

}

function checkall(index)
{
  if ($("#selectall"+index).is(':checked')) {

      $(".selectrow"+index).prop("checked", true);
       $(".selectrow"+index).trigger("change");
       if (index==0)
       {
            // listtable.api().rows().select();
       }

  } else {

      $(".selectrow"+index).prop("checked", false);
      $(".selectrow"+index).trigger("change");
       //leavetable.rows().deselect();
       if (index==0)
       {
            // listtable.api().rows().deselect();
       }

  }
}


</script>



@endsection
