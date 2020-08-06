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
         var rangetable;

         $(document).ready(function() {

           listeditor = new $.fn.dataTable.Editor( {
                   ajax: {
                      "url": "{{ asset('/Include/phonebill.php') }}",
                      "data": {
                          "type": "{{ $type }}"
                      }
                    },
                   table: "#list",
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
                                   label: "Phone_No:",
                                   name: "phonebills.Phone_No",
                                   type:  'select2',
                                   options: [
                                      { label :"", value: "" },
                                      @foreach($phone_no as $phone)
                                          { label :"{{$phone->Option}}", value: "{{$phone->Option}}" },
                                      @endforeach

                                  ],


                           },{
                                   label: "Current Holder:",
                                   name: "phonebills.Current_Holder"

                           },{
                                   label: "Department:",
                                   name: "phonebills.Department",
                                   type:  'select2',
                                   options: [
                                      { label :"", value: "" },
                                      @foreach($departments as $department)
                                          { label :"{{$department->Project_Name}}", value: "{{$department->Project_Name}}" },
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
                                          { label :"{{$phone->Option}}", value: "{{$phone->Option}}" },
                                      @endforeach

                                  ],


                           },{
                                   label: "Account_No:",
                                   name: "phonebills.Account_No"

                           },{

                                     label: "Current Holder:",
                                     name: "phonebills.UserId",
                                     type:  'select2',
                                     opts: {
                                        data : [
                                          { text :"", id: "" },
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
                                   label: "Package:",
                                   name: "phonebills.Package"

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
                                          { label :"{{$phone->Option}}", value: "{{$phone->Option}}" },
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
                                   label: "Department:",
                                   name: "phonebills.Department",
                                   type:  'select2',
                                   options: [
                                      { label :"", value: "" },
                                      @foreach($departments as $department)
                                          { label :"{{$department->Project_Name}}", value: "{{$department->Project_Name}}" },
                                      @endforeach

                                  ],

                           },{
                                   label: "Amount:",
                                   name: "phonebills.Amount"

                           },{
                                   label: "Package:",
                                   name: "phonebills.Package"

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


           listtable = $('#list').dataTable( {
                   ajax: {
                      "url": "{{ asset('/Include/phonebill.php') }}",
                      "data": {
                          "type": "{{ $type }}"
                      }
                    },
                   dom: "Blrftip",
                   bAutoWidth: true,
                   order: [[ 1, "desc" ]],
                    sScrollY: "100%",
                   sScrollX: "100%",
                   @if ($type=="RedOne")
                     columnDefs: [{ "visible": false, "targets": [1,2,7,8,11] },{"className": "dt-center", "targets": "_all"}],
                   @elseif ($type=="Maxis" || $type=="Celcom")
                    columnDefs: [{ "visible": false, "targets": [1,2,9,10] },{"className": "dt-center", "targets": "_all"}],
                   @else
                    columnDefs: [{ "visible": false, "targets": [1,2,] },{"className": "dt-center", "targets": "_all"}],
                   @endif
                   bScrollCollapse: true,
                   columns: [
                     {data: null, title:"No"},
                     {data:"phonebills.Id", title:"Id"},
                     {data:'phonebills.Type', title:"Type"},
                     {data:'phonebills.Registered_Name', title:"Registered_Name"},
                     {data:'phonebills.Account_No', title:"Account_No"},
                     {data:'phonebills.Bill_No', title:"Bill_No"},
                     {data:'phonebills.Phone_No', title:"Phone_No"},
                     {data:'users.Name',editField: "phonebills.UserId", title:"Current_Holder"},
                     {data:'users.Department', title:"Department"},
                     {data:'phonebills.Current_Holder', title:"Current_Holder"},
                     {data:'phonebills.Department', title:"Department"},
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
                      editor:  listeditor
                  },
                  select: true,
                  buttons: [
                        {
                            text: 'New Bill',
                            action: function ( e, dt, node, config ) {
                              $('.select2').select2({tags:true});

                              $("#newbillmodal").modal("show");

                          },
                        },
                        {
                            text: 'Update',
                            action: function ( e, dt, node, config ) {
                              updateBill();

                          },
                        },
                        { extend: "create", text: "New Bill", editor: listeditor },
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


               listtable.api().on( 'order.dt search.dt', function () {
                   listtable.api().column(0, {search:'applied', order:'applied'}).nodes().each( function (cell, i) {
                       cell.innerHTML = i+1;
                   } );
               } ).draw();

               rangetable.api().on( 'order.dt search.dt', function () {
                   rangetable.api().column(0, {search:'applied', order:'applied'}).nodes().each( function (cell, i) {
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

              <?php

                $company_options = "";
                foreach($company as $comp) {
                  $company_options .= "<option>$comp->Option</option>";
                }
              ?>
              <div class="table-responsive">

                <table class="table table-hover" id="newbilltable">
                  <thead>
                    <tr>
                      <th><label><input type="checkbox" name="selectall" id="selectall0" value="all" onclick="checkall(0)"></th>
                      <th>Registered Name</th>
                      <th>Phone No</th>
                      <th>Account No</th>
                      <th>Current Holder</th>
                      <th>Package</th>

                      <th>Amount</th>
                      <th>GST</th>
                      <th>Total</th>
                      <th>Bill Date</th>
                      <th>Due Date</th>

                      <th>Credit Card No</th>
                      <th>Transaction Date</th>
                      <th>Transfer Amount</th>
                      <th>Remarks</th>
                    </tr>
                  </thead>
                  <tbody>
                    @foreach($phone_no as $index => $phone)
                    <tr>
                      <td><input type="checkbox" name="selectrow0" id="selectrow0" class="selectrow0" value="" onclick="uncheck(0)"></td>
                      <td><select class="form-control select2">{!! $company_options !!}</select></td>
                      <td><input class="form-control" type="readonly" name="Phone_No[{{ $index }}]" value="{{ $phone->Option }}"></td>
                      <td><input class="form-control" type="readonly" name="Account_No[{{ $index }}]" value="{{ $phone->Extra }}"></td>
                      <td></td>
                      <td></td>
                      <td></td>
                      <td></td>
                      <td></td>
                      <td></td>
                      <td></td>
                      <td></td>
                      <td></td>
                      <td></td>
                      <td><input class="form-control input-sm" type="number" placeholder="0.00" required name="Current_Balance[0]" min="0" value="0.00" step="0.01" title="Currency" pattern="^\d+(?:\.\d{1,2})?$" onblur="this.value = parseFloat(this.value).toFixed(2);"></td>
                    </tr>
                    @endforeach
                  </tbody>
                </table>
              </div>

            </div>
            <div class="modal-footer">
                <button type="submit" class="btn btn-success btn-small" id="btnnewbill">Add Entry</button>
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
                      <th>Cards</th>
                      <th>Statement Date</th>
                      <th>Statement Due</th>
                      <th>Current Balance</th>
                      <th>Payment Date</th>
                      <th>Payment Type</th>
                      <th>Amount</th>

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
          <div class="box box-primary">
            <div class="box-header with-border">

              <div class="col-md-12">
                <div class="nav-tabs-custom">

                   <div class="tab-content">
                     <div class="active tab-pane" id="listtab">

                       @foreach($category as $table)

                         @if ($table->Option==$type)
                           <a href="{{ url('/phonebilltracker') }}/{{$table->Option}}"><button type="button" class="btn btn-danger btn-small">{{$table->Option}}</button></a>
                         @else
                           <a href="{{ url('/phonebilltracker') }}/{{$table->Option}}"><button type="button" class="btn btn-success btn-small">{{$table->Option}}</button></a>
                         @endif

                       @endforeach

                       <br><br>

                        <table id="list" class="list" cellspacing="0" width="100%" padding="30px" style="font-size: 13px;">
                            <thead>
                              @if($list)
                                <tr class="search">

                                  @foreach($list as $key=>$value)

                                    @if ($key==0)
                                      <?php $i = 0; ?>

                                      @foreach($value as $field=>$a)
                                          @if ($i==0|| $i==1 || $i==2)
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

                                  @foreach($list as $key=>$value)

                                    @if ($key==0)
                                           <!-- <td></td> -->
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
                                         <!-- <td></td> -->
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

function newbill(serialno)
{
  $('#Phone_Number').val(serialno);
    $('.select2').select2({tags:true});

  $('#NewBill').modal('show');

}

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
