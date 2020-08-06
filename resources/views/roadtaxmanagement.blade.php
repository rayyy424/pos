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

      .btn{
        width:210px;
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

      {{-- chart js --}}
      <script type="text/javascript" language="javascript" src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.4.0/Chart.bundle.min.js"></script>

      <script type="text/javascript" language="javascript" class="init">
       var eventeditor;
       var editor;
       var roadtax;
       var roadtaxeditor;
       var shellcardeditor;
       var shellcarddeductioneditor;
       var summondeductioneditor;
       var expenseseditor;
       var touchngoeditor;
       var touchngodeductioneditor;
       $(document).ready(function() {
        eventeditor = new $.fn.dataTable.Editor( {
                  ajax: {
                     "url": "{{ asset('/Include/vehicleevent.php') }}"
                   },
                  fields: [
                         {
                                   label: "VehicleId",
                                   name: "vehicleevent.VehicleId",
                                   type: "hidden"
                         },
                         {
                                   label: "Availability",
                                   name: "roadtax.available",
                                   type: "hidden"
                         },{
                                   label: "Event",
                                   name: "vehicleevent.Event"
                         },
                         {
                                   label: "Start Date:",
                                   name: "vehicleevent.Start_Date",
                                   type:  'datetime',
                                   def:    function () { return new Date(); },
                                                format: 'DD-MMM-YYYY'
                         },
                         {
                                  label: "End Date:",
                                   name: "vehicleevent.End_Date",
                                   type:  'datetime',
                                   def:    function () { return new Date(); },
                                                format: 'DD-MMM-YYYY'
                         }
                  ]
          } );

          touchngodeductioneditor = new $.fn.dataTable.Editor( {
                  ajax: {
                     "url": "{{ asset('/Include/touchdeductionitems.php') }}"
                   },
                  fields: [
                        {
                          label: "Custodian:",
                          name: "deductionitems.UserId",
                          type:  'select2',
                          opts: {
                              data: [
                                { text :"", id: "0" },
                                @foreach($users as $department => $dept_users)
                                {
                                  text: "{{ $department }}",
                                  children: [
                                    @foreach($dept_users as $user)
                                    { text :"{{$user->Name}}", id: "{{$user->Id}}" },
                                    @endforeach
                                  ]
                                },
                                @endforeach
                              ]
                          }

                        },
                        {
                           label: "Date:",
                           name: "deductionitems.Date",
                           type:   'datetime',
                           def:    function () { return ""; },
                           format: 'DD-MMM-YYYY',
                           attr: {
                             autocomplete: "off"
                           }
                        },{

                            label: "Time:",
                            name: "deductionitems.Time",
                        },{

                            label: "Deduction For:",
                            name: "deductionitems.DeductionId",
                            type: "select2",
                            options: [
                              { label :"", value: "" },
                              @foreach($touchngodeductions as $touchngodeduction)
                              { label: "{{ $touchngodeduction->Name }}", value: "{{ $touchngodeduction->Id }}"},
                              @endforeach
                            ]
                        },{

                            label: "Card_Serial:",
                            name: "deductionitems.Card_Serial",
                            type:  'select2',
                            options: [
                              { label :"", value: "" },
                            ],
                         },{
                            label: "Entry Location:",
                            name: "deductionitems.Entry_location"

                         },{
                            label: "Amount:",
                            name: "deductionitems.Amount"
                         },{
                            label: "Total Deduction:",
                            name: "deductionitems.Total_Deduction"

                         }

                  ]
          } );
          touchngoeditor = new $.fn.dataTable.Editor( {
                  ajax: {
                     "url": "{{ asset('/Include/touchngo.php') }}"
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
                                   @foreach($tngcardtypes as $tngcard)
                                     { label :"{{$tngcard->Option}}", value: "{{$tngcard->Option}}" },
                                   @endforeach


                                   ],
                                   opts: {
                                     escapeMarkup: function (text) { return text; },
                                     tags: true
                                   }

                         },{
                                   label: "Vehicle No:",
                                   name: "touchngo.Vehicle_No",
                                   type:  'readonly',
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
                                     opts: {

                                         data: [
                                           { text :"", id: "0" },
                                           @foreach($users as $department => $dept_users)
                                           {
                                             text: "{{ $department }}",
                                             children: [
                                               @foreach($dept_users as $user)
                                               { text :"{{$user->Name}}", id: "{{$user->Id}}" },
                                               @endforeach
                                             ]
                                           },
                                           @endforeach
                                         ]
                                     }

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

          accidentdeductioneditor = new $.fn.dataTable.Editor( {
                  ajax: {
                     "url": "{{ asset('/Include/accident.php') }}"
                   },
                  fields: [
                          {
                                 label: "Car No:",
                                 name: "deductionitems.Car_No",
                                 type: "readonly",
                        },{
                                label: "Deduction For:",
                                name: "deductionitems.DeductionId",
                                type: "select2",
                                options: [
                                  { label :"", value: "" },
                                  @foreach($accidentdeductions as $accidentdeduction)
                                  { label: "{{ $accidentdeduction->Name }}", value: "{{ $accidentdeduction->Id }}"},
                                  @endforeach
                                ]
                       },{
                               label: "Custodian:",
                               name: "deductionitems.UserId",
                               type:  'select2',
                                opts: {

                                    data: [
                                      { text :"", id: "0" },
                                      @foreach($users as $department => $dept_users)
                                      {
                                        text: "{{ $department }}",
                                        children: [
                                          @foreach($dept_users as $user)
                                          { text :"{{$user->Name}}", id: "{{$user->Id}}" },
                                          @endforeach
                                        ]
                                      },
                                      @endforeach
                                    ]
                                }

                       },{
                                   label: "Date:",
                                   name: "deductionitems.Date",
                                   type:   'datetime',
                                   def:    function () { return ""; },
                                   format: 'DD-MMM-YYYY',
                                   attr: {
                                     autocomplete: "off"
                                   }
                       },{
                                    label: "Time:",
                                    name: "deductionitems.Time"
                       },{
                                   label: "Victim:",
                                   name: "deductionitems.Victim"
                       },{
                                    label: "Offense:",
                                    name: "deductionitems.Offense"
                       },{
                                    label: "Total_Deduction:",
                                    name: "deductionitems.Total_Deduction"
                       },{
                                    label: "Amount:",
                                    name: "deductionitems.Amount"

                       }

                  ]
          } );

          expenseseditor = new $.fn.dataTable.Editor( {
                  ajax: {
                     "url": "{{ asset('/Include/shellcardexpenses.php') }}"
                   },
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
                                   type:  'hidden'
                         },
                         {
                                   label: "Account No:",
                                   name: "Card_No",
                                   type:  'readonly'
                         },
                         {
                                   label: "Vehicle No:",
                                   name: "Vehicle_No",
                                   type:  'readonly'
                         }
                  ]
          } );

          shellcarddeductioneditor = new $.fn.dataTable.Editor( {
                  ajax: {
                     "url": "{{ asset('/Include/shelldeductionitems.php') }}"
                   },
                  fields: [
                          {
                                 label: "Date:",
                                 name: "deductionitems.Date",
                                 type:   'datetime',
                                 def:    function () { return ""; },
                                 format: 'DD-MMM-YYYY'
                         },{
                               label: "Custodian:",
                               name: "deductionitems.UserId",
                               type:  'select2',
                                opts: {

                                    data: [
                                      { text :"", id: "0" },
                                      @foreach($users as $department => $dept_users)
                                      {
                                        text: "{{ $department }}",
                                        children: [
                                          @foreach($dept_users as $user)
                                          { text :"{{$user->Name}}", id: "{{$user->Id}}" },
                                          @endforeach
                                        ]
                                      },
                                      @endforeach
                                    ]
                                }

                        },{

                               label: "Time:",
                               name: "deductionitems.Time"
                       },{
                              label: "Deduction For:",
                              name: "deductionitems.DeductionId",
                              type: "select2",
                              options: [
                                { label :"", value: "" },
                                @foreach($shellcarddeductions as $deduction)
                                { label: "{{ $deduction->Name }}", value: "{{ $deduction->Id }}"},
                                @endforeach
                              ]

                       },{

                                label: "Account_No:",
                                name: "deductionitems.Account_No",
                                type: "select2",
                                options: [
                                  { label :"", value: "" },
                                  { label :"00100983", value: "00100983" },
                                  { label :"00610607", value: "00610607" },
                                  { label :"00100132", value: "00100132" },

                                ],
                        },{
                                    label: "Project_Code:",
                                    name: "deductionitems.Project_Code",
                          },{
                                    label: "Petrol_Station:",
                                    name: "deductionitems.Petrol_Station"
                         },{
                                    label: "Amount:",
                                    name: "deductionitems.Amount"
                         },{
                                    label: "Invoice_No:",
                                    name: "deductionitems.Invoice_No"

                         },{
                                    label: "Invoice_Date:",
                                    name: "deductionitems.Invoice_Date",
                                    type:   'datetime',
                                    def:    function () { return ""; },
                                    format: 'DD-MMM-YYYY'

                         },{
                                    label: "Due_Date:",
                                    name: "deductionitems.Due_Date",
                                    type:   'datetime',
                                    def:    function () { return ""; },
                                    format: 'DD-MMM-YYYY'

                         },{
                                    label: "Company:",
                                    name: "deductionitems.Company",
                                    type:  "select2",
                                    options: [
                                      { label :"", value: "" },
                                      @foreach($company as $option4)
                                          { label :"{{$option4->Option}}", value: "{{$option4->Option}}" },
                                      @endforeach
                                    ],
                         },{
                                    label: "Remarks:",
                                    name: "deductionitems.Remarks"
                         }

                  ]
          } );

         summondeductioneditor = new $.fn.dataTable.Editor( {
                 ajax: {
                    "url": "{{ asset('/Include/summon.php') }}",
                  },
                 fields: [
                         {
                                label: "Vehicle_No:",
                                name: "summons.Vehicle_No",
                                type: 'readonly'
                       },{
                              label: "Deduction For:",
                              name: "summons.DeductionId",
                              type: "select2",
                              options: [
                                { label :"", value: "" },
                                @foreach($summondeductions as $deduction)
                                { label: "{{ $deduction->Name }}", value: "{{ $deduction->Id }}"},
                                @endforeach
                              ]

                     },{
                              label: "Driver:",
                              name: "summons.UserId",
                              type:  'select2',
                              opts: {

                                  data: [
                                    { text :"", id: "" },
                                    @foreach($users as $department => $dept_users)
                                    {
                                      text: "{{ $department }}",
                                      children: [
                                        @foreach($dept_users as $user)
                                        { text :"{{$user->Name}}", id: "{{$user->Id}}" },
                                        @endforeach
                                      ]
                                    },
                                    @endforeach
                                  ]
                              }

                       },{

                              label: "Company:",
                              name: "summons.Company",
                              type:  "select2",
                              options: [
                                { label :"", value: "" },
                                @foreach($company as $option3)
                                    { label :"{{$option3->Option}}", value: "{{$option3->Option}}" },
                                @endforeach
                              ],
                      },{

                               label: "Summon_No:",
                               name: "summons.Summon_No"
                       },{
                                  label: "Date:",
                                  name: "summons.Date",
                                  type:   'datetime',
                                  def:    function () { return new Date(); },
                                  format: 'DD-MMM-YYYY'
                        },{
                                   label: "Settlement_Date:",
                                   name: "summons.Settlement_Date",
                                   type:   'datetime',
                                   def:    function () { return new Date(); },
                                   format: 'DD-MMM-YYYY'
                         },{
                                   label: "Time:",
                                   name: "summons.Time"
                        },{
                                  label: "Total_Deduction:",
                                  name: "summons.Total_Deduction"
                       },{
                                   label: "Offense:",
                                   name: "summons.Offense",
                                   type: "select2",
                                   opts: {
                                    data: [
                                      { text: "SPEEDING" }
                                    ],
                                    tags: true
                                   }
                        },{
                                   label: "Amount:",
                                   name: "summons.Amount"

                        },{
                                   label: "Place:",
                                   name: "summons.Place"
                        },{
                                   label: "Employer_Bare:",
                                   name: "summons.Employer_Bare"
                        },{
                                   label: "Company_Deduction:",
                                   name: "summons.Company_Deduction"
                        },{
                                    label: "Remarks:",
                                    name: "summons.Remarks",
                                    type:"textarea"
                         }

                 ]
         } );

         shellcardeditor = new $.fn.dataTable.Editor( {
                 ajax: {
                    "url": "{{ asset('/Include/shellcard.php') }}"
                  },
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
                                    @foreach($company as $option2)
                                      { label :"{{ $option2->Option }}", value: "{{ $option2->Option }}" },
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
                                          @foreach($users as $department => $dept_users)
                                          {
                                            text: "{{ $department }}",
                                            children: [
                                              @foreach($dept_users as $user)
                                              { text :"{{$user->Name}}", id: "{{$user->Id}}" },
                                              @endforeach
                                            ]
                                          },
                                          @endforeach
                                        ]
                                    }
                        },{
                                    label: "Pin_Code:",
                                    name: "shellcards.Pin_Code"

                         },{
                                     label: "Vehicle_No:",
                                     name: "shellcards.Vehicle_No",
                                     type: "readonly"

                          },{
                                    label: "Remarks:",
                                    name: "shellcards.Remarks",
                                    type:"textarea"
                         }

                 ]
         } );

         roadtaxeditor = new $.fn.dataTable.Editor( {
                 ajax: {
                    "url": "{{ asset('/Include/roadtax.php') }}",
                    "data": {
                        "option": "{{ $option }}"
                    }
                  },
                 table: "#roadtaxtable",
                 idSrc: "roadtax.Id",
                 formOptions: {
                      bubble: {
                          submit: 'allIfChanged'
                      }
                  },
                  fields: [
                        {
                          name: "roadtax.UserId",
                          type: "hidden",
                          def: 0
                        },{
                                  label: "Option:",
                                  name: "roadtax.Option",
                                  type: "hidden",
                                  def: "ROADTAX AND INSURANCE"
                        },{

                                 label: "Vehicle No:",
                                 name: "roadtax.Vehicle_No",
                                 type: "readonly"
                        },{
                                  label: "Insurance Company:",
                                  name: "roadtax.Remarks",
                                  type:  'select2',
                                  options: [
                                      { label :"", value: "" },
                                      @foreach($insurance_company as $c)
                                      { label : "{{ $c->Option }}", value:  "{{ $c->Option }}"},
                                      @endforeach

                                  ],
                        },{
                                  label: "Insurance Expiry:",
                                  name: "roadtax.Financier",
                                  type:   'datetime',
                                  def:    function () { return ""; },
                                  format: 'DD-MMM-YYYY'
                        },{
                                  label: "Roadtax Expiry:",
                                  name: "roadtax.Account_No",
                                  type:   'datetime',
                                  def:    function () { return ""; },
                                  format: 'DD-MMM-YYYY'
                        },{
                                  label: "Personal_Accident:",
                                  name: "roadtax.Personal_Accident",
                                  type:   'datetime',
                                  def:    function () { return ""; },
                                  format: 'DD-MMM-YYYY'

                        },{
                                  label: "Puspakom_Expiry:",
                                  name: "roadtax.Puspakom_Expiry",
                                  type:   'datetime',
                                  def:    function () { return ""; },
                                  format: 'DD-MMM-YYYY'
                        },{
                                  label: "PMA_Expiry:",
                                  name: "roadtax.PMA_Expiry",
                                  type:   'datetime',
                                  def:    function () { return ""; },
                                  format: 'DD-MMM-YYYY'
                        },{
                                  label: "SPAD_Expiry:",
                                  name: "roadtax.SPAD_Expiry",
                                  type:   'datetime',
                                  def:    function () { return ""; },
                                  format: 'DD-MMM-YYYY'
                        },{
                                  label: "NCD:",
                                  name: "roadtax.NCD"
                        },{
                                  label: "Loading:",
                                  name: "roadtax.Loading"
                        },{
                                  label: "Sum_Insured:",
                                  name: "roadtax.Sum_Insured"
                        },{
                                  label: "Windscreen:",
                                  name: "roadtax.Windscreen"

                        }
                      ]
         } );

         editor = new $.fn.dataTable.Editor( {
          ajax: {
            "url": "{{ asset('/Include/roadtax.php') }}",
            "data": {
              "option": "{{ $option }}"
            }
          },
          table: "#roadtaxtable",
          idSrc: "roadtax.Id",
          formOptions: {
            bubble: {
              submit: 'allIfChanged'
            }
          },
          fields: [
          {
            label: "Option:",
            name: "roadtax.Option",
            type: "hidden",
            def: "{{ $option }}"
          },
          @if ($option=="VEHICLE LIST")
          {
            label: "Vehicle No:",
            name: "roadtax.Vehicle_No"
          },{
            label: "Custodian:",
            name: "roadtax.UserId",
            type:  'select2',
            opts: {
              data : [
              @foreach($users as $department => $dept_users)
              {
                text: "{{ $department }}",
                children: [
                @foreach($dept_users as $user)
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
            label: "Custodian2:",
            name: "roadtax.UserId2",
            type:  'select2',
            opts: {
              data : [
              @foreach($users as $department => $dept_users)
              {
                text: "{{ $department }}",
                children: [
                @foreach($dept_users as $user)
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
          },
          {
            label: "Custodian3:",
            name: "roadtax.UserId3",
            type:  'select2',
            opts: {
              data : [
              @foreach($users as $department => $dept_users)
              {
                text: "{{ $department }}",
                children: [
                @foreach($dept_users as $user)
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
            label: "Maker:",
            name: "roadtax.Maker"
          },{
            label: "Model:",
            name: "roadtax.Model"
          },{
            label: "Year:",
            name: "roadtax.Year",
            type: "select2",
            opts: {
              data: [
              { id: "", text: "" },
              @for($i = date("Y") + 1; $i > 1970; $i--)
              {id: "{{ $i }}", text: "{{ $i }}" },
              @endfor
              ]
            }
          },{
            label: "Type:",
            name: "roadtax.Type"
          },{
            label: "Lorry Size:",
            name: "roadtax.Lorry_Size",
            type:  'select2',
            options: [
            { label :"", value: "" },
            @foreach($lorry_size as $c)
            { label : "{{ $c->Option }}", value:  "{{ $c->Option }}"},
            @endforeach
            ],
          },
          {
            label: "Lorry Dimension:",
            name: "roadtax.Lorry_Dimension",
            type:  'select2',
            options: [
            { label :"", value: "" },
            @foreach($dimension as $c)
            { label : "{{ $c->Option }}", value:  "{{ $c->Option }}"},
            @endforeach
            ],
          },
          {
            label: "Dimension:",
            name: "roadtax.dimension",
            attr: {
              type:"number"
            }
          },
          {
            label: "Owner:",
            name: "roadtax.Owner",
            type:  'select2',
            options: [
            { label :"", value: "" },
            @foreach($company as $c)
            { label : "{{ $c->Option }}", value:  "{{ $c->Option }}"},
            @endforeach
            ],
          },{
            label: "Spare Keys:",
            name: "roadtax.Original_Reg_Card",
            type:  'select',
            options: [
              { label :"", value: "" },
              { label :"YES", value: "YES" },
              { label :"NO", value: "NO" },
            ],
          },{
            label: "Availability:",
            name: "roadtax.Availability",
            type:  'select2',
            options: [
            { label :"", value: "" },
            @foreach($Availability as $c)
            { label : "{{ $c->Option }}", value:  "{{ $c->Option }}"},
            @endforeach
            ],
          },{
            label: "Remarks:",
            name: "roadtax.Remarks",
            type:"textarea"
          }
          @elseif ($option=="ROADTAX AND INSURANCE")
          {
            label: "Vehicle No:",
            name: "roadtax.Vehicle_No"
          },{
            label: "Insurance Expiry:",
            name: "roadtax.Financier",
            type:   'datetime',
            def:    function () { return ""; },
            format: 'DD-MMM-YYYY'
          },{
            label: "Roadtax Expiry:",
            name: "roadtax.Account_No",
            type:   'datetime',
            def:    function () { return ""; },
            format: 'DD-MMM-YYYY'
          },{
            label: "Personal_Accident:",
            name: "roadtax.Personal_Accident",
            type:   'datetime',
            def:    function () { return ""; },
            format: 'DD-MMM-YYYY'
          },{
            label: "Puspakom_Expiry:",
            name: "roadtax.Puspakom_Expiry",
            type:   'datetime',
            def:    function () { return ""; },
            format: 'DD-MMM-YYYY'
          },{
            label: "PMA_Expiry:",
            name: "roadtax.PMA_Expiry",
            type:   'datetime',
            def:    function () { return ""; },
            format: 'DD-MMM-YYYY'
          },{
            label: "SPAD_Expiry:",
            name: "roadtax.SPAD_Expiry",
            type:   'datetime',
            def:    function () { return ""; },
            format: 'DD-MMM-YYYY'
          },{
            label: "NCD:",
            name: "roadtax.NCD"
          },{
            label: "Loading:",
            name: "roadtax.Loading"
          },{
            label: "Sum_Insured:",
            name: "roadtax.Sum_Insured"
          },{
            label: "Windscreen:",
            name: "roadtax.Windscreen"
          }
          @else
          {
            label: "Vehicle No:",
            name: "roadtax.Vehicle_No"
          },{
            label: "RoadTax Expire Date:",
            name: "roadtax.RoadTax_Expire_Date",
            type:   'datetime',
            format: 'DD-MMM-YYYY'
          },{
            label: "Insurance_Company:",
            name: "roadtax.Insurance_Company",
          },{
            label: "Insurance Expire Date:",
            name: "roadtax.Insurance_Expiry_Date",
            type:   'datetime',
            format: 'DD-MMM-YYYY'
          },{
            label: "Asset_Listed:",
            name: "roadtax.Asset_Listed"
          },{
            label: "With_ShellCard:",
            name: "roadtax.With_ShellCard"
          },{
            label: "Custodian:",
            name: "roadtax.Custodian"
          },{
            label: "Maker:",
            name: "roadtax.Maker"
          },{
            label: "Year:",
            name: "roadtax.Year"
          },{
            label: "Model:",
            name: "roadtax.Model"
          },{
            label: "Owner:",
            name: "roadtax.Owner",
            type:  'select',
            options: [
            { label :"", value: "" },
            { label :"MULTITUDE NETWORKS (M) SDN BHD", value: "MULTITUDE NETWORKS (M) SDN BHD" },
            { label :"HN CITRA CAHAYA SDN BHD", value: "HN CITRA CAHAYA SDN BHD" },
            { label :"MIDASCOM PERKASA SDN BHD", value: "MIDASCOM PERKASA SDN BHD" },
            { label :"OMNI AVENUE SDN BHD", value: "OMNI AVENUE SDN BHD" },
            { label :"MIDASCOM NETWORK SDN BHD", value: "MIDASCOM NETWORK SDN BHD" },
            ],
          },{
            label: "Type:",
            name: "roadtax.Type"
          },{
            label: "Original_Reg_Card:",
            name: "roadtax.Original_Reg_Card",
            type:  'select',
            options: [
            { label :"", value: "" },
            { label :"YES", value: "YES" },
            { label :"NO", value: "NO" },
            ],
          },{
            label: "Hire_Purchase:",
            name: "roadtax.Hire_Purchase"
          },{
            label: "Purchase_Date:",
            name: "roadtax.Purchase_Date",
            type:   'datetime',
            def:    function () { return ""; },
            format: 'DD-MMM-YYYY'
          },{
            label: "Insurance Expire:",
            name: "roadtax.Financier",
            type:   'datetime',
            def:    function () { return ""; },
            format: 'DD-MMM-YYYY'
          },{
            label: "Roadtax Expiry:",
            name: "roadtax.Account_No",
            type:   'datetime',
            def:    function () { return ""; },
            format: 'DD-MMM-YYYY'
          },{
            label: "First_Installment:",
            name: "roadtax.First_Installment"
          },{
            label: "Monthly_Installment:",
            name: "roadtax.Monthly_Installment"
          },{
            label: "Personal_Accident:",
            name: "roadtax.Personal_Accident",
            type:   'datetime',
            def:    function () { return ""; },
            format: 'DD-MMM-YYYY'
          },{
            label: "SPAD_Expiry:",
            name: "roadtax.SPAD_Expiry",
            type:   'datetime',
            def:    function () { return ""; },
            format: 'DD-MMM-YYYY'
          },{
            label: "Puspakom_Expiry:",
            name: "roadtax.Puspakom_Expiry",
            type:   'datetime',
            def:    function () { return ""; },
            format: 'DD-MMM-YYYY'
          },{
            label: "PMA_Expiry:",
            name: "roadtax.PMA_Expiry",
            type:   'datetime',
            def:    function () { return ""; },
            format: 'DD-MMM-YYYY'
          },{
            label: "Loading:",
            name: "roadtax.Loading"
          },{
            label: "Sum_Insured:",
            name: "roadtax.Sum_Insured"
          },{
            label: "NCD:",
            name: "roadtax.NCD"
          },{
            label: "Windscreen:",
            name: "roadtax.Windscreen"
          },{
            label: "Custodian:",
            name: "roadtax.UserId",
            type:  'select',
            options: [
            { label :"", value: "0" },
            @foreach($users as $user)
            { label :"{{$user->Name}}", value: "{{$user->Id}}" },
            @endforeach
            ],
          },{
            label: "Remarks:",
            name: "roadtax.Remarks",
            type:"textarea"
          }
          @endif
          ]});                 
         

         roadtax = $('#roadtaxtable').dataTable( {
               ajax: {
                  "url": "{{ asset('/Include/roadtax.php') }}","data": {
                      "option": "{{ $option }}"
                  }

                },
                rowId:"roadtax.Id",
                fnRowCallback: function (nRow, aData, iDisplayIndex, iDisplayIndexFull) {

                  if(aData.roadtax.RoadTax_Expire_Date < "{{$noticedate}}" && aData.roadtax.RoadTax_Expire_Date >= "{{$today}}")
                  {
                     $('td', nRow).closest('tr').css('color', '#f39c12');
                  }
                  else
                  {
                     $('td', nRow).closest('tr').css('color', 'black');
                  }

                 return nRow;
               },
                 dom: "Blrtip",
                 bAutoWidth: true,
                 sScrollY: "100%",
                 sScrollX: "100%",
                 @if ($option=="VEHICLE LIST")
                     columnDefs: [{ "visible": false, "targets": [2,3,9,10,11,12,13,21,22,23,24,25,26,27,28,29,30,31,32,33,34,35,36,37,-1] },{"className": "dt-center", "targets": "_all"}],
                 @elseif ($option=="ROADTAX AND INSURANCE")
                 columnDefs: [{ "visible": false, "targets": [2,3,5,6,7,8,9,10,11,12,13,14,15,16,17,18,19,21,22,23,33,34,35,36] },{"className": "dt-center", "targets": "_all"}],
                 @else
                     columnDefs: [{ "visible": false, "targets": [1,2] },{"className": "dt-center", "targets": "_all"}],
                 @endif

                 bScrollCollapse: true,
                 columns: [
                   {data: null, title:"No"},
                   {
                      data: null,
                      title:"Action",  sortable: false,
                      "render": function ( data, type, full, meta ) {
                        if (data.shellcards.Card_No) {
                          return '<button class="btn btn-default btn-xs" style="width:unset" onclick="openRoadTaxForm(\'' + full.roadtax.Vehicle_No + '\')" title="Roadtax"><i class="fa fa-road"></i></button>' +
                          ' <button class="btn btn-default btn-xs" style="width:unset" onclick="openShellCardForm(\'' + full.roadtax.Vehicle_No + '\')"  title="Shellcard"><i class="fa fa-credit-card-alt"></i></button>' +
                          ' <button class="btn btn-default btn-xs" style="width:unset" onclick="openShellCardDeductionForm(\'' + full.roadtax.Vehicle_No + '\')"  title="Shellcard Deduction"><i class="fa fa-arrow-down"></i></button>' +
                          ' <button class="btn btn-default btn-xs" style="width:unset" onclick="openShellCardExpenseForm(\'' + full.shellcards.Card_No + '\', \'' + full.shellcards.Id + '\', \'' + full.roadtax.Vehicle_No + '\')"  title="Shellcard Expense"><i class="fa fa-usd"></i></button>' +
                          ' <button class="btn btn-default btn-xs" style="width:unset" onclick="openSummonForm(\'' + full.roadtax.Vehicle_No + '\')" title="Summon Deduction"><i class="fa fa-sticky-note-o "></i></button>' +
                          ' <button class="btn btn-default btn-xs" style="width:unset" onclick="openAccidentDeductionForm(\'' + full.roadtax.Vehicle_No + '\')" title="Accident Deduction"><i class="fa fa-exclamation-triangle "></i></button>' +
                          ' <button class="btn btn-default btn-xs" style="width:unset" onclick="openTouchngoForm(\'' + full.roadtax.Vehicle_No + '\')" title="Touch N Go"><i class="fa fa-ticket"></i></button>' +
                          ' <button class="btn btn-default btn-xs" style="width:unset" onclick="openTouchngoDeductionForm(\'' + full.roadtax.Vehicle_No + '\')" title="Touch N Go Deduction"><i class="fa fa-arrow-down"></i></button>' + ' <button class="btn btn-default btn-xs" style="width:unset" onclick="openVehicleEvent(\'' + full.roadtax.Id + '\')" title="Vehicle Management"><i class="fa fa-cogs"></i></button>' +  ' <button class="btn btn-default btn-xs" style="width:unset" data-toggle="modal" data-target="#viewEventListModal" onclick="viewEventList(\'' + full.roadtax.Id + '\')" title="Vehicle List"><i class="fa fa-eye"></i></button>'

                        } else {
                          return '<button class="btn btn-default btn-xs" style="width:unset" onclick="openRoadTaxForm(\'' + full.roadtax.Vehicle_No + '\')" title="Roadtax"><i class="fa fa-road"></i></button>' +
                          ' <button class="btn btn-default btn-xs" style="width:unset" onclick="openShellCardForm(\'' + full.roadtax.Vehicle_No + '\')" title="Shellcard"><i class="fa fa-credit-card-alt"></i></button>' +
                          ' <button class="btn btn-default btn-xs" style="width:unset" onclick="openShellCardDeductionForm(\'' + full.roadtax.Vehicle_No + '\')" title="Shellcard Deduction"><i class="fa fa-arrow-down" ></i></button>' +
                          ' <button class="btn btn-default btn-xs" style="width:unset" onclick="openSummonForm(\'' + full.roadtax.Vehicle_No + '\')" title="Summon Deduction"><i class="fa fa-sticky-note-o "></i></button>' +
                          ' <button class="btn btn-default btn-xs" style="width:unset" onclick="openTouchngoForm(\'' + full.roadtax.Vehicle_No + '\')" title="Touch N Go"><i class="fa fa-ticket"></i></button>' +
                          ' <button class="btn btn-default btn-xs" style="width:unset" onclick="openTouchngoDeductionForm(\'' + full.roadtax.Vehicle_No + '\')" title="Touch N Go Deduction"><i class="fa fa-arrow-down"></i></button>' +  ' <button class="btn btn-default btn-xs" style="width:unset" onclick="openVehicleEvent(\'' + full.roadtax.Id + '\')" title="Vehicle Management"><i class="fa fa-cogs"></i></button>'
                        }
                      }
                   },

                   {data:'roadtax.Id',title:"Id"},
                   {data:'roadtax.Option',title:"Option"},
                   {data:'roadtax.Vehicle_No', title:"Vehicle_No"},
                   {data:"users.Name", editField: "roadtax.UserId",title:'Custodian' },
                   {data:"driver.Name", editField: "roadtax.UserId2",title:'Custodian2' },
                   {data:"driver2.Name", editField: "roadtax.UserId3",title:'Custodian3' },
                   {data:'users.Department', title:"Department"},
                   {data:'roadtax.RoadTax_Expire_Date', title:"RoadTax_Expiry_Date"},
                   {data:'roadtax.Insurance_Expiry_Date', title:"Insurance_Expiry_Date"},
                   {data:'roadtax.Insurance_Company', title:"Insurance_Company"},
                   {data:'roadtax.Asset_Listed', title:"Asset Listed"},
                   {data:'roadtax.With_ShellCard', title:"With Shell Card"},

                   {data:'roadtax.Maker', title:"Maker"},
                   {data:'roadtax.Model', title:"Model"},
                   {data:'roadtax.Year', title:"Year"},
                   {data:'roadtax.Type', title:"Type"},
                   {data:'roadtax.Lorry_Size', title:"Lorry Size"},
                   {data:'roadtax.Lorry_Dimension', title:"Lorry Dimension"},
                   {data:'roadtax.dimension', title:"Dimension (&#13217;)"},
                   {data:'roadtax.Owner', title:"Company"},
                   {data:'roadtax.Original_Reg_Card', title:"Spare Keys"},
                   {data:'roadtax.Availability', title:"Availability"}, //19
                   {data:'roadtax.Purchase_Date', title:"Roadtax Expiry"},
                   {data:'roadtax.Financier', title:"Insurance Expiry"},
                   {data:'roadtax.Account_No', title:"Roadtax Expiry"},

                   {data:'roadtax.Hire_Purchase', title:"Hire Purchase"},
                   {data:'roadtax.First_Installment', title:"First Installment"},
                   {data:'roadtax.Monthly_Installment', title:"Insurance Company"},
                   {data:'roadtax.Personal_Accident', title:"Personal Accident"},
                   {data:'roadtax.Puspakom_Expiry', title:"Puspakom Expiry"},
                   {data:'roadtax.PMA_Expiry', title:"PMA Expiry"},
                   {data:'roadtax.SPAD_Expiry', title:"SPAD Expiry"},
                   {data:'roadtax.NCD', title:"NCD"},
                   {data:'roadtax.Loading', title:"Loading"},
                   {data:'roadtax.Sum_Insured', title:"Sum Insured"},
                   {data:'roadtax.Windscreen', title:"Windscreen"},
                   {data:'roadtax.Remarks', title:"Insurance Company"},
                   {data:'shellcards.Card_No', title:"Shell Card"},
                   {data:'shellcards.Id', title:"Shell Card Id"},

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
                         //   text: 'New',
                         //   action: function ( e, dt, node, config ) {
                         //       // clearing all select/input options
                         //       editor
                         //          .create( false )
                         //          .set( 'roadtax.Option', '{{$option}}')
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


         // $('#roadtaxtable').on( 'click', 'tbody td:not(:first-child)', function (e) {
         //       editor.inline( this, {
         //         onBlur: 'submit',
         //         submit: 'allIfChanged'
         //     } );
         // } );

             roadtax.api().on( 'order.dt search.dt', function () {
                 roadtax.api().column(0, {search:'applied', order:'applied'}).nodes().each( function (cell, i) {
                     cell.innerHTML = i+1;
                 } );
             } ).draw();



             $(".roadtaxtable thead input").keyup ( function () {

                     /* Filter on the column (the index) of this element */
                     if ($('#roadtaxtable').length > 0)
                     {

                         var colnum=document.getElementById('roadtaxtable').rows[0].cells.length;

                         if (this.value=="[empty]")
                         {

                            roadtax.fnFilter( '^$', this.name,true,false );
                         }
                         else if (this.value=="[nonempty]")
                         {

                            roadtax.fnFilter( '^(?=\\s*\\S).*$', this.name,true,false );
                         }
                         else if (this.value.startsWith("!")==true && this.value.length>1)
                         {

                            roadtax.fnFilter( '^(?'+ this.value +').*', this.name,true,false );
                         }
                         else if (this.value.startsWith("!")==false)
                         {

                             roadtax.fnFilter( this.value, this.name,true,false );
                         }
                     }



             } );

           });
      </script>

      @endsection

@section('content')

  <div class="content-wrapper">
      <!-- Content Header (Page header) -->
    <section class="content-header">
      <h1>
      Motor Vehicle
      <small>Administration</small>
      </h1>

      <ol class="breadcrumb">
      <li><a href="{{ url('/home') }}"><i class="fa fa-dashboard"></i> Home</a></li>
      <li><a href="#">Administration</a></li>
      <li class="active">Motor Vehicle</li>
      </ol>
    </section>

    <section class="content">

      <div class="modal fade" id="viewEventListModal" role="dialog" aria-labelledby="myItemListModalLabel" style="display: none;">
            <div class="modal-dialog" role="document">
              <div class="modal-content">
                <div class="modal-header">
                  <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true"></span></button>
                  <h4 class="modal-title" id="ItemListModalLabel">Item List</h4>
                </div>
                <div class="modal-body">
                  <table id="itemlisttable" class="table table-condensed">
                <thead>
                    <tr>
                      <th style="display:none">Id</th>
                      <th>Vehicle</th>
                      <th>Event</th>
                      <th>Start Date</th>
                      <th>End Date</th>
                      <th>Action</th>
                    </tr>
                  </thead>
                  <tbody>
                  </tbody>
                </table>
              </div>
                <div class="modal-footer">
                  <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                </div>
              </div>
            </div>
      </div>

      <div class="row">
        <div class="col-md-12">
          <div class="box">
            <div class="box-body">

              @foreach($category as $table)

                @if ($table->Option==$option)
                  <a href="{{ url('/roadtaxmanagement') }}/{{$table->Option}}"><button type="button" class="btn btn-success btn-small">{{$table->Option}}</button></a>
                @else
                  <a href="{{ url('/roadtaxmanagement') }}/{{$table->Option}}"><button type="button" class="btn btn-danger btn-small">{{$table->Option}}</button></a>
                @endif

              @endforeach

              <br><br>



              <table id="roadtaxtable" class="roadtaxtable" cellspacing="0" width="100%" padding="30px" style="font-size: 13px;">
                <thead>
                  @if($roadtax)
                    <tr class="search">

                      @foreach($roadtax as $key=>$value)

                        @if ($key==0)
                          <?php $i = 0; ?>

                          @foreach($value as $field=>$a)
                              @if ($i==0|| $i==1 || $i==2 || $i==3 )
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

                  @endif

                    <tr>
                      @foreach($roadtax as $key=>$value)

                        @if ($key==0)
                          <td></td>
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
                  @foreach($roadtax as $holiday)

                    <tr id="row_{{ $i }}">
                        <td></td>
                        <td></td>

                        @foreach($holiday as $key=>$value)
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

    </section>

</div>
<footer class="main-footer">
  <div class="pull-right hidden-xs">
    <b>Version</b> 2.0.1
  </div>
  <strong>Copyright &copy; 2014-2016 <a href="http://www.softoya.com">TrackerOnTheGo</a>.</strong> All rights
  reserved.
</footer>

<script>

  function openRoadTaxForm(Vehicle_No) {
    roadtaxeditor.create( 'Add Roadtax And Insurance', {
       label: "Submit",
       fn: function () { this.submit(); }
    }, false );
    roadtaxeditor.set( 'roadtax.Vehicle_No', Vehicle_No);
    roadtaxeditor.set( 'roadtax.UserId', 0);

    roadtaxeditor.open();
  }
  function openTouchngoForm(Vehicle_No) {
    touchngoeditor.create( 'Touch N Go', {
       label: "Submit",
       fn: function () { this.submit(); }
    }, false );
    touchngoeditor.set( 'touchngo.Vehicle_No', Vehicle_No);

    touchngoeditor.open();
  }

  function openTouchngoDeductionForm(Vehicle_No) {
    $.ajaxSetup({
       headers: { 'X-CSRF-Token' : $('meta[name=_token]').attr('content') }
    });
    $.ajax({
      url: "{{ url('/touchngoforvehicle') }}" + '/' + Vehicle_No ,
      method: "GET",
      success: function(response){
        touchngodeductioneditor.create( 'Touch N Go Deduction', {
           label: "Submit",
           fn: function () { this.submit(); }
        }, false );
        // touchngodeductioneditor.set( 'touchngo.Vehicle_No', Vehicle_No);
        //
        touchngodeductioneditor.field('deductionitems.Card_Serial')
                  .update(response)
                  .val(response[0]);

        touchngodeductioneditor.open();
      }
    });
  }

  function openShellCardForm(Vehicle_No) {
    shellcardeditor.create( 'Shell Card', {
       label: "Submit",
       fn: function () { this.submit(); }
    }, false );
    shellcardeditor.set( 'shellcards.Vehicle_No', Vehicle_No);
    // roadtaxeditor.set( 'roadtax.UserId', 0);

    shellcardeditor.open();
  }
  function openShellCardDeductionForm(Vehicle_No) {
    shellcarddeductioneditor.create( 'Shell Card Deduction', {
       label: "Submit",
       fn: function () { this.submit(); }
    }, false );
    shellcarddeductioneditor.set( 'deductionitems.Project_Code', Vehicle_No);
    // roadtaxeditor.set( 'roadtax.UserId', 0);

    shellcarddeductioneditor.open();
  }
  function openSummonForm(Vehicle_No) {
    summondeductioneditor.create( 'Summon Deduction', {
       label: "Submit",
       fn: function () { this.submit(); }
    }, false );
    summondeductioneditor.set( 'summons.Vehicle_No', Vehicle_No);
    // roadtaxeditor.set( 'roadtax.UserId', 0);

    summondeductioneditor.open();
  }

  function openAccidentDeductionForm(Vehicle_No) {
    accidentdeductioneditor.create( 'Accident Deduction', {
       label: "Submit",
       fn: function () { this.submit(); }
    }, false );
    accidentdeductioneditor.set( 'deductionitems.Car_No', Vehicle_No);

    accidentdeductioneditor.open();
  }

  function openShellCardExpenseForm(Card_No, Card_Id, Vehicle_No ) {
    expenseseditor.create( 'Shell Card Expense', {
       label: "Submit",
       fn: function () { this.submit(); }
    }, false );
    expenseseditor.set( 'Card_No', Card_No);
    expenseseditor.set( 'shellcardexpenses.ShellCardId', Card_Id);
    expenseseditor.set( 'Vehicle_No', Vehicle_No);


    expenseseditor.open();
  }
    function openVehicleEvent(Id) {
    eventeditor.create( 'Vehicle Event Management', {
       label: "Submit",
       fn: function () { this.submit(); }
    }, false );
    eventeditor.set( 'vehicleevent.VehicleId', Id);
    eventeditor.open();
  }
  function viewEventList(id) {
   $.ajaxSetup({
         headers: { 'X-CSRF-Token' : $('meta[name=_token]').attr('content') }
      });
      $.ajax({
          url: "{{ url('/roadtaxmanagement/eventlist') }}" + "/" + id,
          method: "GET",
          success: function(response){
            $('#itemlisttable > tbody').empty();
            response.event.forEach(function(element) {
                $('#itemlisttable > tbody').append(`<tr>
                  <td style="display:none">${element.Id}</td>
                  <td>${element.Vehicle_No}</td>
                  <td>${element.Event}</td>
                  <td>${element.Start_Date}</td>
                  <td>${element.End_Date}</td>
                  <td><button id="deleteevent" onclick="deleteevent(${element.Id})">Delete</button></td>
                </tr>`);
              // }
            });
          },
          error: function(data){
          }
      });
    }
  function deleteevent(id) {
   $.ajaxSetup({
         headers: { 'X-CSRF-Token' : $('meta[name=_token]').attr('content') }
      });
      $.ajax({
        url: "{{ url('/roadtaxmanagement/eventlist/delete') }}",
          method: "POST",
          data: {Id: id},
          success: function(response){
            if(response == 1)
            {
              alert("Event Deleted");
              window.location.reload();
            }
            else
            {
              alert("Failed to Delete Event");
            }
          }
        });

    }

</script>



@endsection
