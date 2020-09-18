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

         var editor;
         var deductioneditor;
         var listtable;
         var deductiontable;

         $(document).ready(function() {

           editor = new $.fn.dataTable.Editor( {
                   ajax: {
                      "url": "{{ asset('/Include/touchngo.php') }}"
                    },
                   table: "#list",
                   idSrc: "touchngo.Id",
                   formOptions: {
                        bubble: {
                            submit: 'allIfChanged'
                        }
                    },
                   fields: [
                           {
                                  label: "Card No:",
                                  name: "touchngo.Card_No"
                          },{
                                  label: "Username:",
                                  name: "touchngo.Username"
                          },{
                                    label: "Card Type:",
                                    name: "touchngo.Card_Type",
                                    type: 'select2',
                                    options: [

                                      { label :"", value: "" },
                                    @foreach($cardtypes as $card)
                                      { label :"{{$card->Option}}", value: "{{$card->Option}}" },
                                    @endforeach


                                    ],
                                    opts: {
                                      escapeMarkup: function (text) { return text; },
                                      tags: true
                                    }

                          },{
                                    label: "Vehicle No:",
                                    name: "touchngo.Vehicle_No",
                                    type:  'select2',
                                    options: [
                                      { label :"", value: "" },
                                    @foreach($vehicle_no as $vehicle)
                                      { label :"{{$vehicle->Vehicle_No}}", value: "{{$vehicle->Vehicle_No}}" },
                                    @endforeach
                                      ],

                                    opts: {
                                      escapeMarkup: function (text) { return text; },
                                      tags: true
                                    }

                          },{
                                    label: "User_ID:",
                                    name: "touchngo.User_ID"
                          },{
                                     label: "Date_Provide:",
                                     name: "touchngo.Date_Provide",
                                     type:   'datetime',
                                     def:    function () { return ""; },
                                     format: 'DD-MMM-YYYY',
                                     attr: {
                                      autocomplete: "off"
                                     }
                         },{
                                    label: "Date_Return:",
                                    name: "touchngo.Date_Return",
                                    type:   'datetime',
                                    def:    function () { return ""; },
                                    format: 'DD-MMM-YYYY',
                                    attr: {
                                      autocomplete: "off"
                                    }
                        },{
                                   label: "Date_Terminate:",
                                   name: "touchngo.Date_Terminate",
                                   type:   'datetime',
                                   def:    function () { return ""; },
                                   format: 'DD-MMM-YYYY',
                                   attr: {
                                    autocomplete: "off"
                                   }
                       },{
                                      label: "Custodian:",
                                      name: "touchngo.UserId",
                                      type:  'select2',
                                      options: [
                                        { label :"", value: "" },
                                        @foreach($users as $user)
                                            { label :"{{$user->Name}}", value: "{{$user->Id}}" },
                                        @endforeach
                                      ],

                         },{
                                     label: "Registered_Name:",
                                     name: "touchngo.Registered_Name"

                          },{
                                      label: "Plusmiles_Register:",
                                      name: "touchngo.Plusmiles_Register",
                                      type: "select2",
                                      options: [
                                        "Yes", "No", "NIL"
                                      ]

                           },{
                                      label: "Remarks:",
                                      name: "touchngo.Remarks",
                                      type:"textarea"
                           }

                   ]
           } );

           reloadeditor = new $.fn.dataTable.Editor( {
                   ajax: {
                      "url": "{{ asset('/Include/reload.php') }}"
                    },
                   table: "#reloadtable",
                   idSrc: "reload.Id",
                   formOptions: {
                        bubble: {
                            submit: 'allIfChanged'
                        }
                    },
                   fields: [
                           {
                                  label: "Card_No:",
                                  name: "reload.Card_No",
                                  type: "select2",
                                  options: [
                                    {label: "",value: ""},
                                    @foreach($touchngocards as $tngcard)
                                      { label: "{{$tngcard->Card_No}}", value: "{{ $tngcard->Card_No }}" },
                                    @endforeach
                                  ],
                                  opts: {
                                    escapeMarkup: function (text) { return text; },
                                    tags: true
                                  }
                          },{
                                    label: "Balance_Before:",
                                    name: "reload.Balance_Before",
                                    attr: {
                                      type: "number"
                                    }

                          },{
                                    label: "Total_Reload:",
                                    name: "reload.Total_Reload",
                                    attr: {
                                      type: "number"
                                    }

                          },{
                                    label: "Topup:",
                                    name: "reload.Topup",
                                    attr: {
                                      type: "number"
                                    }
                          },{
                                    label: "Balance:",
                                    name: "reload.Balance",
                                    type:"hidden"

                          },{
                                   label: "Date_Reload:",
                                   name: "reload.Date_Reload",
                                   type:   'datetime',
                                   def:    function () { return ""; },
                                   format: 'DD-MMM-YYYY'
                       },{
                                      label: "Request_By:",
                                      name: "reload.Request_By",
                                      type:  'select2',
                                      options: [
                                        { label :"", value: "" },
                                        @foreach($users as $user)
                                            { label :"{{$user->Name}}", value: "{{$user->Id}}" },
                                        @endforeach
                                      ],
                                      opts: {
                                        escapeMarkup: function (text) { return text; },
                                        tags: true
                                      }

                         },{
                                        label: "Reload_By:",
                                        name: "reload.Reload_By",
                                        type:  'select2',
                                        options: [
                                          { label :"", value: "" },
                                          @foreach($users as $user)
                                              { label :"{{$user->Name}}", value: "{{$user->Id}}" },
                                          @endforeach
                                        ],
                                        opts: {
                                          escapeMarkup: function (text) { return text; },
                                          tags: true
                                        }

                           },{
                                      label: "Remarks:",
                                      name: "reload.Remarks",
                                      type:"textarea"
                           }

                   ]
           } );

           deductioneditor = new $.fn.dataTable.Editor( {
                   ajax: {
                      "url": "{{ asset('/Include/touchdeduction.php') }}"
                    },
                   table: "#deductiontable",
                   idSrc: "deductions.Id",
                   fields: [
                     {
                             label: "Type",
                             name: "deductions.Type",
                             type: "hidden"
                     },{
                            label: "Name",
                            name: "deductions.Name",
                            type:"readonly"
                     },{
                            label: "UserId",
                            name: "deductions.UserId",
                            type: "hidden"
                     },{
                            label: "Admin_HOD",
                            name: "deductions.Admin_HOD",
                            type: "hidden"
                     },{
                            label: "Admin_Status",
                            name: "deductions.Admin_Status"
                     },{
                            label: "CME_HOD",
                            name: "deductions.CME_HOD",
                            type: "hidden"
                     },{
                            label: "CME_Status",
                            name: "deductions.CME_Status"
                     },{
                            label: "GENSET_HOD",
                            name: "deductions.GENSET_HOD",
                            type: "hidden"
                     },{

                              label: "MD",
                              name: "deductions.MD",
                              type: "hidden"
                     },{
                            label: "GENSET_Status",
                            name: "deductions.GENSET_Status"
                     },{

                            label: "Company:",
                            name: "deductions.Company",
                            type:  "select2",
                            options: [
                              { label :"", value: "" },
                              @foreach($company as $option)
                                  { label :"{{$option->Option}}", value: "{{$option->Option}}" },
                              @endforeach
                            ],
                            opts: {
                              escapeMarkup: function (text) { return text; },
                              tags: true
                            }
                    },{
                            label: "Remarks:",
                            name: "deductions.Remarks"
                     },{
                            label: "Status:",
                            name: "deductions.Status",
                            type:"readonly"
                     }

                   ]
           } );

           deductioneditor.on( 'postSubmit', function ( e, json, data, action) {

             if(json["fieldErrors"])
             {

               var errormessage="Duplicate deduction for "+$('[name="Payment_Month"]').val();
               $("#error-alert ul").html(errormessage);
               $("#error-alert").modal('show');


             }

         } );


           // $('#list').on( 'click', 'tbody td:not(:first-child)', function (e) {
           //       editor.inline( this, {
           //         onBlur: 'submit',
           //         submit: 'allIfChanged'
           //     } );
           // } );

          listtable = $('#list').dataTable( {
                 ajax: {
                    "url": "{{ asset('/Include/touchngo.php') }}"
                  },
                  rowId:"touchngo.Id",
                   dom: "Blrtip",
                   bAutoWidth: true,
                   sScrollY: "100%",
                   sScrollX: "100%",
                   columnDefs: [{ "visible": false, "targets": [1] },{"className": "dt-center", "targets": "_all"}],
                   bScrollCollapse: true,
                   columns: [
                     {data: null, "render":"", title:"No"},
                     {data:'touchngo.Id'},
                     {data:'touchngo.Username', title:"Username"},
                     {data:'touchngo.User_ID', title:"User ID"},
                     {data:'touchngo.Card_No', title:"Card No"},
                     {data:'touchngo.Vehicle_No', title:"Vehicle No"},
                     {data:'touchngo.Card_Type', title:"Card Type"},
                     {data:'touchngo.Registered_Name', title:"Registered Name"},
                     {data:'touchngo.Plusmiles_Register', title:"Plusmiles Register"},
                     {data:'users.Name', editField: "touchngo.UserId",title:"Custodian"},
                     {data:'users.Position', title:"Position"},
                     {data:'touchngo.Date_Provide', title:"Date Provide"},
                     {data:'touchngo.Date_Return', title:"Date Return"},
                     {data:'touchngo.Date_Terminate', title:"Date Terminate"},
                     {data:'touchngo.Remarks', title:"Remarks"},


                   ],
                   autoFill: {
                      editor:  editor
                  },
                   select: {
                           style:    'os',
                           selector: 'td'
                   },
                   buttons: [
                           {
                             text: 'New Card',
                             action: function ( e, dt, node, config ) {
                                 // clearing all select/input options
                                 editor
                                    .create('New Tounch N Go', {
                                                   label: "Submit",
                                                   fn: function () { this.submit(); }
                                                }, false )
                                    .open();
                             },
                           },
                           { extend: "edit", editor: editor },

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

               reloadtable = $('#reloadtable').dataTable( {

                 ajax: {
                    "url": "{{ asset('/Include/reload.php') }}"
                  },
                      rowId:"reload.Id",
                        dom: "Blrtip",
                        bPaginate:true,
                        bAutoWidth: true,
                        sScrollY: "100%",
                        sScrollX: "100%",

                        columnDefs: [{ "visible": false, "targets": [1] },{"className": "dt-center", "targets": "_all"}],
                        bScrollCollapse: true,
                        aaSorting:[[1,"desc"]],
                        columns: [
                          {data: null, "render":"", title:"No"},
                          {data:'reload.Id'},
                          {data:'reload.Card_No', title:"Card No"},
                          {data:'users.Name', editField: "reload.Request_By",title:"Request By"},
                          {data:'users.Position', title:"Position"},
                          {data:'reloadBy.Name', editField: "reload.Reload_By",title:"Reload By"},
                          {data:'reloadBy.Position', title:"Position"},
                          {data:'reload.Date_Reload', title:"Date Reload"},
                          {data:'reload.Balance_Before', title:"Balance Before"},
                          {data:'reload.Total_Reload', title:"Total Reload"},
                          {data:'reload.Topup', title:"Top Up"},
                          {data:'reload.Balance', title:"Balance"},
                          {data:'reload.Remarks', title:"Remarks"}

                        ],
                        autoFill: {
                           editor:  reloadeditor
                       },
                        select: {
                                style:    'os',
                                selector: 'tr'
                        },
                        buttons: [
                                {
                                  text: 'New',
                                  action: function ( e, dt, node, config ) {
                                      // clearing all select/input options
                                      reloadeditor
                                         .create('Tounch N Go Reload', {
                                                   label: "Submit",
                                                   fn: function () { this.submit(); }
                                                }, false )
                                         .open();
                                  },
                                },
                                { extend: "edit", editor: reloadeditor },
                                { extend: "remove", editor: reloadeditor },
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


                    // $('#reloadtable').on( 'click', 'tbody td:not(:first-child)', function (e) {
                    //       reloadeditor.inline( this, {
                    //         onBlur: 'submit',
                    //         submit: 'allIfChanged'
                    //     } );
                    // } );

               deductiontable=$('#deductiontable').dataTable( {
                         ajax: {
                            "url": "{{ asset('/Include/touchdeduction.php') }}"
                          },
                           columnDefs: [{ "visible": false, "targets": [1,3,5,7] },{"className": "dt-center", "targets": "_all"}],
                           responsive: false,
                           colReorder: false,
                           dom: "Brt",
                           sScrollY: "100%",
                           sScrollX: "100%",
                           bPaginate: false,
                           bAutoWidth: false,
                           aaSorting:false,
                           rowId:"deductions.Id",
                           columns: [
                              {
                                  sortable: false,
                                  "render": function ( data, type, full, meta ) {
                                      return '<a href="touchngodeduction/'+full.deductions.Id+'" target="_blank">View</a>';
                                  }
                              },
                             { data: "deductions.Id"},
                             { data: "deductions.Name", title:"Date"},
                             { data: "deductions.Remarks",title:"Remarks"},
                             { data: "submitter.Name", title: "Prepared By"},
                             { data: "deductions.Company", title: "Company"},
                             { data: "deductions.Status", title:"Status"},
                             { data: "deductions.created_at" ,title:"created_at"},
                             { data: "approver_HRA.Name" ,title:"Approve by HOD of LOG"},
                             { data: "deductions.Admin_Status" ,title:"Status"},
                             { data: "approver_CME.Name" ,title:"Approve by HOD of CME"},
                             { data: "deductions.CME_Status" ,title:"Status"},
                             { data: "approver_GENSET.Name" ,title:"Approve by HOD of GENSET"},
                             { data: "deductions.GENSET_Status" ,title:"Status"},
                             { data: "MD.Name" ,title:"Approve by MD"},
                             { data: "deductions.MD_Status" ,title:"Status"},

                           ],
                           autoFill: {
                              editor:  deductioneditor
                          },
                          // keys: {
                          //     columns: ':not(:first-child)',
                          //     editor:  claimseditor
                          // },
                          select: {
                              style:    'multi',
                              selector: 'tr'
                          },
                           buttons: [
                                   {
                                     text: 'Deduction For : <select id="Payment_Month" name="Payment_Month" > @foreach ($paymentmonth as $month) <option value="{{$month->Payment_Month}}" <?php if($month->Payment_Month == $current) echo ' selected="selected" '; ?>>{{$month->Payment_Month}}</option>@endforeach</select>'

                                   },
                                   {
                                     text: 'New Deduction',
                                     action: function ( e, dt, node, config ) {
                                         // clearing all select/input options

                                         var current = moment("{{$current}}")
                                         var select = moment($('[name="Payment_Month"]').val());
                                         var naming="";


                                         if(select<current)
                                         {
                                           naming=" (Back-Date)";
                                         }

                                         deductioneditor
                                            .create( 'New Deduction', {
                                               label: "Submit",
                                               fn: function () { this.submit(); }
                                            }, false )
                                            .set( 'deductions.Name', $('[name="Payment_Month"]').val()+" TouchNGo")
                                            .set( 'deductions.UserId', {{ $me->UserId }} )
                                            .set( 'deductions.Status', "Pending Submission" )
                                            .set( 'deductions.Admin_HOD', {{$HOD_LOG}})
                                            .set( 'deductions.GENSET_HOD', {{$HOD_GST}})
                                            .set( 'deductions.CME_HOD', {{$HOD_CME}})
                                            .set( 'deductions.MD', {{$MD}})
                                            .set( 'deductions.Admin_Status', "Pending")
                                            .set( 'deductions.GENSET_Status', "Pending")
                                            .set( 'deductions.CME_Status', "Pending")
                                            .set( 'deductions.Type', "touchngo" )
                                            .open();
                                     },
                                   },
                                   { extend: "remove", editor: deductioneditor }
                           ],

               });

               // Activate an inline edit on click of a table cell
                     // $('#deductiontable').on( 'click', 'tbody td', function (e) {

                     //       deductioneditor.inline( this, {
                     //      onBlur: 'submit'
                     //     } );
                     // } );

               listtable.api().on( 'order.dt search.dt', function () {
                   listtable.api().column(0, {search:'applied', order:'applied'}).nodes().each( function (cell, i) {
                       cell.innerHTML = i+1;
                   } );
               } ).draw();

               reloadtable.api().on( 'order.dt search.dt', function () {
                   reloadtable.api().column(0, {search:'applied', order:'applied'}).nodes().each( function (cell, i) {
                       cell.innerHTML = i+1;
                   } );
               } ).draw();

                reloadeditor.on('initEdit', function ( e, json, data ) {

                  // the list
                  var cards = [
                      "",
                      @foreach($touchngocards as $card)
                        "{{ $card->Card_No }}",
                      @endforeach
                  ];

                  // the selected users from edit
                  var selectedCard = data['reload']['Card_No'];

                  // combine list and selected
                  var combinedCards = cards.concat(selectedCard);

                  // remove the duplicates
                  var uniqueCards = combinedCards.filter(function(item, pos) {
                      return combinedCards.indexOf(item) == pos;
                  });

                  reloadeditor.field('reload.Card_No')
                    .update(uniqueCards)
                    .val(selectedCard);
                });

               editor.on('initEdit', function ( e, json, data ) {

                  // the list
                  var cardtypes = [
                      "",
                      @foreach($cardtypes as $card)
                        "{{ $card->Option }}",
                      @endforeach
                  ];

                  // the selected users from edit
                  var selectedCard = data['touchngo']['Card_Type'];

                  // combine list and selected
                  var combinedCards = cardtypes.concat(selectedCard);

                  // remove the duplicates
                  var uniqueCards = combinedCards.filter(function(item, pos) {
                      return combinedCards.indexOf(item) == pos;
                  });

                  editor.field('touchngo.Card_Type')
                    .update(uniqueCards)
                    .val(selectedCard);

                  // the list
                  var vehicles = [
                      "",
                      @foreach($vehicle_no as $vehicle)
                        "{{$vehicle->Vehicle_No}}",
                      @endforeach
                  ];

                  // the selected users from edit
                  var selectedVehicle = data['touchngo']['Vehicle_No'];

                  // combine list and selected
                  var combinedVehicles= vehicles.concat(selectedVehicle);

                  // remove the duplicates
                  var uniqueVehicles = combinedVehicles.filter(function(item, pos) {
                      return combinedVehicles.indexOf(item) == pos;
                  });

                  editor.field('touchngo.Vehicle_No')
                    .update(uniqueVehicles)
                    .val(selectedVehicle);
               });


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

               $(".reloadtable thead input").keyup ( function () {

                       /* Filter on the column (the index) of this element */
                       if ($('#reloadtable').length > 0)
                       {

                           var colnum=document.getElementById('reloadtable').rows[0].cells.length;

                           if (this.value=="[empty]")
                           {

                              reloadtable.fnFilter( '^$', this.name,true,false );
                           }
                           else if (this.value=="[nonempty]")
                           {

                              reloadtable.fnFilter( '^(?=\\s*\\S).*$', this.name,true,false );
                           }
                           else if (this.value.startsWith("!")==true && this.value.length>1)
                           {

                              reloadtable.fnFilter( '^(?'+ this.value +').*', this.name,true,false );
                           }
                           else if (this.value.startsWith("!")==false)
                           {

                               reloadtable.fnFilter( this.value, this.name,true,false );
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
      Touch N Go
      <small>Administration</small>
      </h1>

      <ol class="breadcrumb">
      <li><a href="{{ url('/home') }}"><i class="fa fa-dashboard"></i> Home</a></li>
      <li><a href="#">Administration</a></li>
      <li class="active">Touch N Go</li>
      </ol>
    </section>

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

        <div class="col-md-12">
          <!-- <div class="box">
            <div class="box-body"> -->

              <div class="col-md-12">
                <div class="nav-tabs-custom">
                   <ul class="nav nav-tabs">
                     <li class="active"><a href="#listtab" data-toggle="tab" id="listtab1">Master List Touch N Go</a></li>
                     <li><a href="#reloadtab" data-toggle="tab" id="reload">Touch N GO Reload</a></li>
                     <li><a href="#deductiontab" data-toggle="tab" id="deductiontab1">Touch N GO Deduction</a></li>
                   </ul>

                   <br><br>

                   <div class="tab-content">
                     <div class="active tab-pane" id="listtab">
                        <table id="list" class="list" cellspacing="0" width="100%" padding="30px" style="font-size: 13px;">
                          <thead>

                            <tr class="search">

                              @foreach($list as $key=>$value)

                                @if ($key==0)
                                  <?php $i = 0; ?>

                                  @foreach($value as $field=>$a)
                                      @if ($i==0|| $i==1)
                                        <td align='center'><input type='hidden' class='search_init' name='{{$i}}' placemark='{{$a}}'></td>
                                      @else
                                        <td align='center'><input type='text' class='search_init' name='{{$i}}'  placemark='{{$a}}'></td>
                                      @endif

                                      <?php $i ++; ?>
                                  @endforeach

                                    <td align='center'><input type='text' class='search_init' name='{{$i}}'  placemark='{{$a}}'></td>

                                @endif

                              @endforeach
                            </tr>

                              <tr>

                                @foreach($list as $key=>$value)

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



                          </tbody>
                            <tfoot></tfoot>
                        </table>
                     </div>

                     <div class="tab-pane" id="reloadtab">

                       <table id="reloadtable" class="reloadtable" cellspacing="0" width="100%" padding="30px" style="font-size: 13px;">
                           <thead>
                               {{-- prepare header search textbox --}}

                               <tr>
                                 @foreach($reload as $key=>$value)

                                   @if ($key==0)
                                     <td>Action</td>
                                     @foreach($value as $field=>$value)
                                         <td>{{ $field }}</td>
                                     @endforeach

                                   @endif

                                 @endforeach
                               </tr>
                           </thead>
                           <tbody>

                             <?php $i = 0; ?>
                             @foreach($reload as $reloadtouch)

                               <tr id="row_{{ $i }}">
                                   <td></td>
                                   @foreach($reloadtouch as $key=>$value)
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

                     <div class="tab-pane" id="deductiontab">

                       <table id="deductiontable" class="display" cellspacing="0" width="100%" padding="30px" style="font-size: 13px;">
                           <thead>
                               {{-- prepare header search textbox --}}

                               <tr>
                                 @foreach($deductions as $key=>$value)

                                   @if ($key==0)
                                     <td>Action</td>
                                     @foreach($value as $field=>$value)
                                         <td>{{ $field }}</td>
                                     @endforeach

                                   @endif

                                 @endforeach
                               </tr>
                           </thead>
                           <tbody>

                             <?php $i = 0; ?>
                             @foreach($deductions as $deduction)

                               <tr id="row_{{ $i }}">
                                   <td></td>
                                   @foreach($deduction as $key=>$value)
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
         <!-- </div>
       </div> -->
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

  //Initialize Select2 Elements
  $(".select2").select2();

  $('#Date').datepicker({
    autoclose: true,
      format: 'dd-M-yyyy',
  });

  $(".timepicker").timepicker({
      showInputs: false
    });

});

function refresh()
{
  var d=$('#range').val();
  var arr = d.split(" - ");

  window.location.href ="{{ url("/shellcardtracker") }}/"+arr[0]+"/"+arr[1];

}

</script>



@endsection
