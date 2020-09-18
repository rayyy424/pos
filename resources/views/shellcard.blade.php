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
         var expenseseditor;
         var listtable;
         var deductiontable;
         var expensestable;

         $(document).ready(function() {

           editor = new $.fn.dataTable.Editor( {
                   ajax: {
                      "url": "{{ asset('/Include/shellcard.php') }}"
                    },
                   table: "#list",
                   idSrc: "shellcards.Id",
                   formOptions: {
                        bubble: {
                            submit: 'allIfChanged'
                        }
                    },
                   fields: [
                           {

                                  label: "Company:",
                                  name: "shellcards.Company",
                                  type:  'select2',
                                  options: [
                                      { label :"", value: "" },
                                      @foreach($company as $option)
                                        { label :"{{ $option->Option }}", value: "{{ $option->Option }}" },
                                      @endforeach

                                  ],
                          },{
                                  label: "Account_No:",
                                  name: "shellcards.Account_No",
                                  type: "select2",
                                  options: [
                                    { label :"", value: "" },
                                    { label :"00100983", value: "00100983" },
                                    { label :"00610607", value: "00610607" },
                                    { label :"00100132", value: "00100132" },

                                  ],
                                  opts: {
                                    tags: true
                                  },
                          },{
                                  label: "Card_No:",
                                  name: "shellcards.Card_No"
                          },{
                                    label: "Type:",
                                    name: "shellcards.Type",
                                    type:  'select2',
                                    options: [
                                      { label :"", value: "" },
                                      { label :"Diesel", value: "Diesel" },
                                      { label :"Petrol", value: "Petrol" },
                                      { label :"Diesel/Petrol", value: "Diesel/Petrol" },

                                    ],

                          },{
                                    label: "Limit_Month:",
                                    name: "shellcards.Limit_Month"
                          },{
                                     label: "Expiry_Date:",
                                     name: "shellcards.Expiry_Date",
                                     type:   'datetime',
                                     def:    function () { return new Date(); },
                                     format: 'DD-MMM-YYYY',
                                     attr: {
                                      autocomplete: "off"
                                     }
                         },{
                                      label: "Custodian:",
                                      name: "shellcards.UserId",
                                      type:  'select2',
                                      opts: {

                                          data: [
                                            @foreach($users as $position => $pos_users)
                                            {
                                              text: "{{ $position }}",
                                              children: [
                                                @foreach($pos_users as $user)
                                                { text :"{{$user->Name}}", id: "{{$user->Id}}" },
                                                @endforeach
                                              ]
                                            },
                                            @endforeach
                                          ]
                                      },

                         // },{
                         //             label: "Usage_RM_ltr:",
                         //             name: "shellcards.Usage_RM_ltr",
                         //             attr: {
                         //                type: "number"
                         //              }

                          },{
                                      label: "Pin_Code:",
                                      name: "shellcards.Pin_Code"

                           },{
                                       label: "Vehicle_No:",
                                       name: "shellcards.Vehicle_No",
                                       type: "select2",
                                       options: [
                                        @foreach($vehiclelist as $vehicle)
                                          { label: "{{$vehicle->Vehicle_No}}", value: "{{$vehicle->Vehicle_No}}" },
                                        @endforeach
                                       ]

                            },{
                                      label: "Remarks:",
                                      name: "shellcards.Remarks",
                                      type:"textarea"
                           }

                   ]
           } );

           expenseseditor = new $.fn.dataTable.Editor( {
                   ajax: {
                      "url": "{{ asset('/Include/shellcardexpenses.php') }}"
                    },
                   table: "#expensestable",
                   idSrc: "shellcardexpenses.Id",
                   fields: [
                          {

                                  label: "Payment_Month:",
                                  name: "shellcardexpenses.Payment_Month",
                                  type:  'select2',
                                  options: [
                                    { label :"", value: "" },
                                    @foreach($paymentmonth as $month)
                                    { label :"{{$month->Payment_Month}}", value: "{{$month->Payment_Month}}" },
                                    @endforeach

                                  ],
                          },{

                                  label: "Amount:",
                                  name: "shellcardexpenses.Amount"
                          },{
                                    label: "Account No:",
                                    name: "shellcardexpenses.ShellCardId",
                                    type:  'select2',
                                    options: [
                                      { label :"", value: "" },
                                      @foreach($account_no as $account)
                                      { label :"{{$account->Card_No}}", value: "{{$account->Id}}" },
                                      @endforeach

                                    ],

                          }
                   ]
           } );

           deductioneditor = new $.fn.dataTable.Editor( {
                   ajax: {
                      "url": "{{ asset('/Include/shelldeduction.php') }}"
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
                            type: "select2",
                            options: [
                              @foreach ($paymentmonth as $month)
                                "{{ $month->Payment_Month }} Shellcard",
                              @endforeach
                            ]
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
                            name: "deductions.Admin_Status",
                            type: "select2",
                            options: [
                              { label: "Pending", value: "Pending"},
                              { label: "Approved", value: "Approved"},
                            ]
                     },{
                            label: "CME_HOD",
                            name: "deductions.CME_HOD",
                            type: "hidden"
                     },{
                            label: "CME_Status",
                            name: "deductions.CME_Status",
                            type: "select2",
                            options: [
                              { label: "Pending", value: "Pending"},
                              { label: "Approved", value: "Approved"},
                            ]
                     },{
                            label: "GENSET_HOD",
                            name: "deductions.GENSET_HOD",
                            type: "hidden",
                     },{

                            label: "MD",
                            name: "deductions.MD",
                            type: "hidden"
                     },{
                            label: "GENSET_Status",
                            name: "deductions.GENSET_Status",
                            type: "select2",
                            options: [
                              { label: "Pending", value: "Pending"},
                              { label: "Approved", value: "Approved"},
                            ]
                     },{

                    //         label: "Company:",
                    //         name: "deductions.Company",
                    //         type:  "select2",
                    //         options: [
                    //           { label :"", value: "" },
                    //           @foreach($company as $option)
                    //               { label :"{{$option->Option}}", value: "{{$option->Option}}" },
                    //           @endforeach
                    //         ],
                    // },{
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

         editor.on('initEdit', function ( e, json, data ) {

            // the list
            var accounts = [
               "00100983","00610607","00100132"
            ];

            // the selected from edit
            var selectedAccount = data['shellcards']['Account_No'];

            // combine list and selected
            var combinedAccounts = accounts.concat(selectedAccount);

            // remove the duplicates
            var uniqueAccounts = combinedAccounts.filter(function(item, pos) {
                return combinedAccounts.indexOf(item) == pos;
            });

            editor.field('shellcards.Account_No')
              .update(uniqueAccounts)
              .val(selectedAccount);


            var vehiclelist = [
              @foreach($vehiclelist as $vehicle)
                "{{$vehicle->Vehicle_No}}",
              @endforeach
            ];

            // the selected from edit
            var selectedVehicle = data['shellcards']['Vehicle_No'];

            // combine list and selected
            var combinedVehicles = vehiclelist.concat(selectedVehicle);

            // remove the duplicates
            var uniqueVehicles = combinedVehicles.filter(function(item, pos) {
                return combinedVehicles.indexOf(item) == pos;
            });

            editor.field('shellcards.Vehicle_No')
              .update(uniqueVehicles)
              .val(selectedVehicle);
         });

           // $('#list').on( 'click', 'tbody td:not(:first-child)', function (e) {
           //       editor.inline( this, {
           //         onBlur: 'submit',
           //         submit: 'allIfChanged'
           //     } );
           // } );

               listtable = $('#list').dataTable( {
                 ajax: {
                    "url": "{{ asset('/Include/shellcard.php') }}"
                  },
                  rowId:"shellcards.Id",
                   dom: "Blrftip",
                   bAutoWidth: true,
                   sScrollY: "100%",
                   sScrollX: "100%",
                   columnDefs: [{ "visible": false, "targets": [1] },{"className": "dt-center", "targets": "_all"}],
                   bScrollCollapse: true,
                   columns: [
                     {data: null, "render":"", title:"No"},
                     {data:'shellcards.Id'},
                     {data:'shellcards.Company', title:"Company"},
                     {data:'shellcards.Vehicle_No', title:"Vehicle_No"},
                     {data:'shellcards.Account_No', title:"Account No"},
                     {data:'shellcards.Card_No', title:"Card No"},
                     {data:'shellcards.Pin_Code', title:"Pin Code"},
                     {data:'shellcards.Type', title:"Type"},
                     {data:'shellcards.Limit_Month', title:"Card Limit/Month"},
                     {data:'shellcards.Expiry_Date', title:"Expiry Date"},
                     {data:'users.Name', editField: "shellcards.UserId",title:"Custodian"},
                     {data:'users.Position', title:"Position"},
                     {data:'shellcards.Remarks', title:"Remarks"},


                   ],
                   autoFill: {
                      editor:  editor
                  },
                   select: {
                           style:    'os',
                           selector: 'td'
                   },
                   buttons: [
                           // {
                           //   text: 'New Expenses',
                           //   action: function ( e, dt, node, config ) {
                           //       // clearing all select/input options
                           //       editor
                           //          .create( false )
                           //          .submit();
                           //   },
                           // },
                           { extend: "create", editor: editor },
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


                expensestable = $('#expensestable').dataTable( {
                       ajax: {
                          "url": "{{ asset('/Include/shellcardexpenses.php') }}"
                        },
                       rowId:"shellcardexpenses.Id",
                       dom: "Blrftip",
                       bAutoWidth: true,
                       sScrollY: "100%",
                       sScrollX: "100%",
                       columnDefs: [{ "visible": false, "targets": [1] },{"className": "dt-center", "targets": "_all"}],
                       bScrollCollapse: true,
                       columns: [
                         {data: null, "render":"", title:"No"},
                         {data:'shellcardexpenses.Id'},
                         {data:'shellcards.Card_No', editField: "shellcardexpenses.ShellCardId",title:"Card No"},
                         {data:'shellcards.Vehicle_No', title:"Vehicle No"},
                         {data:'shellcardexpenses.Payment_Month', title:"Payment Month"},
                         {data:'shellcardexpenses.Amount', title:"Amount"},
                       ],
                       autoFill: {
                          editor:  expenseseditor
                      },
                       select: {
                               style:    'os',
                               selector: 'td'
                       },
                       buttons: [
                               // {
                               //   text: 'New Expenses',
                               //   action: function ( e, dt, node, config ) {
                               //       // clearing all select/input options
                               //       expenseseditor
                               //          .create( false )
                               //          .submit();
                               //   },
                               // },
                               { extend: "create", editor: expenseseditor },
                               { extend: "edit", editor: expenseseditor },

                               { extend: "remove", editor: expenseseditor },
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



                   // $('#expensestable').on( 'click', 'tbody td:not(.child), tbody span.dtr-data', function (e) {
                   //       expenseseditor.inline( this, {
                   //      onBlur: 'submit'
                   //     } );
                   // } );


               deductiontable=$('#deductiontable').dataTable( {
                         ajax: {
                            "url": "{{ asset('/Include/shelldeduction.php') }}"
                          },
                           columnDefs: [{ "visible": false, "targets": [1,3,5,7] },{"className": "dt-center", "targets": "_all"}],
                           responsive: false,
                           colReorder: false,
                           dom: "Blrftip",
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
                                      return '<a href="shelldeductions/'+full.deductions.Id+'" target="_blank">View</a>';
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
                              // style:    'multi',
                              style:    'os',
                              selector: 'td'
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
                                            .create('Shellcard Deduction', {
                                               label: "Submit",
                                               fn: function () { this.submit(); }
                                            }, false )
                                            .set( 'deductions.Name', $('[name="Payment_Month"]').val()+" Shellcard")
                                            .set( 'deductions.UserId', {{ $me->UserId }} )
                                            .set( 'deductions.Status', "Pending Submission" )
                                            .set( 'deductions.Admin_HOD', {{$HOD_LOG}})
                                            .set( 'deductions.GENSET_HOD', {{$HOD_GST}})
                                            .set( 'deductions.CME_HOD', {{$HOD_CME}})
                                            .set( 'deductions.MD', {{$MD}})
                                            .set( 'deductions.Admin_Status', "Pending")
                                            .set( 'deductions.GENSET_Status', "Pending")
                                            .set( 'deductions.CME_Status', "Pending")
                                            .set( 'deductions.Type', "shellcards" )
                                            .open();
                                     },
                                   },
                                   { extend: "edit", editor: deductioneditor },

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

               expensestable.api().on( 'order.dt search.dt', function () {
                   expensestable.api().column(0, {search:'applied', order:'applied'}).nodes().each( function (cell, i) {
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
      Shell Cards
      <small>Administration</small>
      </h1>

      <ol class="breadcrumb">
      <li><a href="{{ url('/home') }}"><i class="fa fa-dashboard"></i> Home</a></li>
      <li><a href="#">Administration</a></li>
      <li class="active">Shell Cards</li>
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
                     <li class="active"><a href="#listtab" data-toggle="tab" id="listtab1">Master List Shellcards</a></li>
                     <li><a href="#expensestab" data-toggle="tab" id="expenses1">Shellcard Expenses</a></li>
                     <li><a href="#deductiontab" data-toggle="tab" id="deductiontab1">Shellcard Deduction</a></li>
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

                     <div class="tab-pane" id="expensestab">

                       <table id="expensestable" class="display" cellspacing="0" width="100%" padding="30px" style="font-size: 13px;">
                           <thead>
                               {{-- prepare header search textbox --}}

                               <tr>
                                  @foreach($expenses as $key=>$value)

                                   @if ($key==0)
                                         <td></td>
                                     @foreach($value as $field=>$value)
                                         <td>{{ $field }}</td>
                                     @endforeach

                                   @endif

                                 @endforeach
                               </tr>
                           </thead>
                           <tbody>

                             <?php $i = 0; ?>
                             @foreach($expenses as $expense)

                               <tr id="row_{{ $i }}">
                                   <td></td>
                                   @foreach($expense as $key=>$value)
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

  $('#range').daterangepicker({locale: {
     format: 'DD-MMM-YYYY'
   },startDate: '{{$start}}',
   endDate: '{{$end}}'});

});

function refresh()
{
  var d=$('#range').val();
  var arr = d.split(" - ");

  window.location.href ="{{ url("/shellcardtracker") }}/"+arr[0]+"/"+arr[1];

}

</script>



@endsection
