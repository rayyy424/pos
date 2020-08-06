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
         var listeditor1;
         var rangetable;
         var selected = [];
         $(document).ready(function() {

           listeditor = new $.fn.dataTable.Editor( {
                   ajax: {
                      "url": "{{ asset('/Include/creditcard.php') }}",
                      "data": {
                          "owner": "{{ $owner }}"
                      }
                    },
                   table: "#listtable",
                   idSrc: "creditcards.Id",
                   fields: [

                          {
                                   label: "Owner:",
                                   name: "creditcards.Owner",
                                   type: "readonly"

                           },{
                                   label: "Type:",
                                   name: "creditcards.Type",
                                   type: "select2",
                                   options: [
                                    @if($owner=="ADELINE")
                                     { label :"", value: "" },
                                     { label :"SCCB - 2162", value: "SCCB - 2162" },
                                     { label :"SCCB - 4464", value: "SCCB - 4464" },
                                     { label :"HSBC - 2411", value: "HSBC - 2411" },
                                     { label :"HLBB - 8200", value: "HLBB - 8200" },
                                     { label :"PBB - 9111", value: "PBB - 9111" },
                                     { label :"UOB - 1922", value: "UOB - 1922" },
                                     { label :"CIMB - 3289", value: "CIMB - 3289" },
                                     { label :"RHB - 9312", value: "RHB - 9312" }

                                    @elseif($owner=="ALVIN")
                                     { label :"", value: "" },
                                     { label :"SCCB - 8079", value: "SCCB - 8079" },
                                     { label :"SCCB - 9600", value: "SCCB - 9600" },
                                     { label :"RHB - 1010N", value: "RHB - 1010N" },
                                     { label :"CITI - 9228", value: "CITI - 9228" },
                                     { label :"HLBB - 8200", value: "HLBB - 8200" },
                                     { label :"PBB - 5108", value: "PBB - 5108" },
                                     { label :"UOB - 4539", value: "UOB - 4539" },
                                     { label :"CIMB - 7900", value: "CIMB - 7900" },
                                     { label :"MBB - 2687 & 2679", value: "MBB - 2687 & 2679" },
                                     { label :"MBB - 3803 & 3779", value: "MBB - 3803 & 3779" },

                                    @endif
                                   ],

                           },{
                                   label: "Statement_Due:",
                                   name: "creditcards.Statement_Due",
                                   type:   'datetime',
                                   def:    function () { return ""; },
                                   format: 'DD-MMM-YYYY'
                           },{
                                   label: "Statement_Date:",
                                   name: "creditcards.Statement_Date",
                                   type:   'datetime',
                                   def:    function () { return ""; },
                                   format: 'DD-MMM-YYYY'

                           },{
                                   label: "Current_Balance:",
                                   name: "creditcards.Current_Balance",
                                   attr: {
                                      type: "number"
                                    }
                           },{
                                   label: "Payment_Date:",
                                   name: "creditcards.Payment_Date",
                                   type:   'datetime',
                                   def:    function () { return ""; },
                                   format: 'DD-MMM-YYYY'
                           },{
                                   label: "Payment_Type:",
                                   name: "creditcards.Payment_Type",
                                   type: "select2",
                                   options: [
                                    @foreach($payment_type as $payment)
                                      "{{ $payment->Option }}",
                                    @endforeach
                                   ]
                           },{
                                   label: "Amount:",
                                   name: "creditcards.Amount"


                           }

                   ]
           } );



           listtable = $('#listtable').dataTable( {
                   ajax: {
                      "url": "{{ asset('/Include/creditcard.php') }}",
                      "data": {
                          "owner": "{{ $owner }}"
                      }
                    },
                   dom: "Blrftip",
                   bAutoWidth: true,
                   aaSorting:[[5,"desc"]],
                   sScrollY: "100%",
                   sScrollX: "100%",
                   columnDefs: [{ "visible": false, "targets": [2,4] },{"className": "dt-right", "targets": [7,10]},{"className": "dt-center", "targets": "_all"}],

                   bScrollCollapse: true,
                   columns: [
                     {
                        data: null,
                        "render": function ( data, type, full, meta ) {
                          return '<input type="checkbox" name="selectrow0" id="selectrow0" class="selectrow0" value="'+full.creditcards.Id+'" onclick="uncheck(0)">';
                        },
                        "orderable": false
                     },
                     {data: null, title:"No"},
                     {data:"creditcards.Id", title:"Id"},
                     {data:'creditcards.Type', title:"Bank"},
                     {data:'creditcards.Owner', title:"Owner"},
                     {data:'creditcards.Statement_Date', title:"Statement Date"},
                     {data:'creditcards.Statement_Due', title:"Statement Due"},
                     {data:'creditcards.Current_Balance', title:"Current Balance",
                     render: function ( data, type, full, meta ) {

                        return parseFloat(full.creditcards.Current_Balance).toLocaleString("en",{ minimumFractionDigits: 2 });

                      }
                    },
                     {data:'creditcards.Payment_Date', title:"Payment Date"},
                     {data:'creditcards.Payment_Type', title:"Payment Type"},
                     {data:'creditcards.Amount', title:"Amount",
                     render: function ( data, type, full, meta ) {

                        return parseFloat(full.creditcards.Amount).toLocaleString("en",{ minimumFractionDigits: 2 });

                      }
                    }
                   ],
                   autoFill: {
                      editor:  listeditor
                  },
                  select: true,
                  buttons: [
                        {
                            text: 'New Bill',
                            action: function ( e, dt, node, config ) {
                              $("#newbillmodal").modal("show");

                          },
                        },
                        {
                            text: 'Update',
                            action: function ( e, dt, node, config ) {
                              updateBill();

                          },
                        },
                        { extend: "edit", editor: listeditor },

                        { extend: "remove", editor: listeditor },
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



               // $('#listtable').on( 'click', 'tbody td:not(.child), tbody span.dtr-data', function (e) {
               //       listeditor.inline( this, {
               //      onBlur: 'submit'
               //     } );
               // } );


               listtable.api().on( 'order.dt search.dt', function () {
                   listtable.api().column(1, {search:'applied', order:'applied'}).nodes().each( function (cell, i) {
                       cell.innerHTML = i+1;
                   } );
               } ).draw();

               listeditor.on('initEdit', function ( e, json, data ) {
                  // the list of users
                  var type = [
                      @if($owner=="ADELINE")
                       "",
                       "SCCB - 2162",
                       "SCCB - 4464",
                       "HSBC - 2411",
                       "HLBB - 8200",
                       "PBB - 9111",
                       "UOB - 1922",
                       "CIMB - 3289",
                       "RHB - 9312"

                      @elseif($owner=="ALVIN")
                       "",
                       "SCCB - 8079",
                       "SCCB - 9600",
                       "RHB - 1010N",
                       "CITI - 9228",
                       "HLBB - 8200",
                       "PBB - 5108",
                       "UOB - 4539",
                       "CIMB - 7900",
                       "MBB - 2687 & 2679",
                       "MBB - 3803 & 3779",

                      @endif
                  ];

                  // the selected users from edit
                  var selectedType = data['creditcards']['Type'];
                  console.log(selectedType);
                  // combine list and selected
                  var combinedType = type.concat(selectedType);

                  // remove the duplicates
                  var uniqueType = combinedType.filter(function(item, pos) {
                      return combinedType.indexOf(item) == pos;
                  });

                  listeditor.field('creditcards.Type')
                    .update(uniqueType)
                    .val(selectedType);


                  // the list
                  var payment_type = [
                      @foreach($payment_type as $payment)
                        "{{$payment->Option}}",
                      @endforeach
                  ];

                  // the selected
                  var selectedPayment = data['creditcards']['Payment_Type'];
                  console.log(selectedPayment);
                  // combine list and selected
                  var combinedPayment = payment_type.concat(selectedPayment);

                  // remove the duplicates
                  var uniquePayment = combinedPayment.filter(function(item, pos) {
                      return combinedPayment.indexOf(item) == pos;
                  });

                  listeditor.field('creditcards.Payment_Type')
                    .update(uniquePayment)
                    .val(selectedPayment);
               });


               $(".list thead input").keyup ( function () {

                       /* Filter on the column (the index) of this element */
                       if ($('#listtable').length > 0)
                       {

                           var colnum=document.getElementById('listtable').rows[0].cells.length;

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
      Credit Card Tracker
      <small>Administration</small>
      </h1>

      <ol class="breadcrumb">
      <li><a href="{{ url('/home') }}"><i class="fa fa-dashboard"></i> Home</a></li>
      <li><a href="#">Administration</a></li>
      <li class="active">Credit Card Tracker</li>
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

                <table class="table table-hover">
                  <tbody>
                    <tr>
                      <th>Cards</th>
                      <th width="25%">Statement Date</th>
                      <th width="25%">Statement Due</th>
                      <th width="25%">Current Balance</th>
                    </tr>
                    <tr>
                      <td>SCCB - 2162<input type="hidden" value="SCCB - 2162" name="Card_Type[0]"></td>
                      <td><input type="text" class="datepicker form-control input-sm" name="Statement_Date[0]"></td>
                      <td><input type="text" class="datepicker form-control input-sm" name="Statement_Due[0]"></td>
                      <td><input class="form-control input-sm" type="number" placeholder="0.00" required name="Current_Balance[0]" min="0" value="0.00" step="0.01" title="Currency" pattern="^\d+(?:\.\d{1,2})?$" onblur="this.value = parseFloat(this.value).toFixed(2);"></td>
                    </tr>
                    <tr>
                      <td>SCCB - 4464<input type="hidden" value="SCCB - 4464" name="Card_Type[1]"></td>
                      <td><input type="text" class="datepicker form-control input-sm" name="Statement_Date[1]"></td>
                      <td><input type="text" class="datepicker form-control input-sm" name="Statement_Due[1]"></td>
                      <td><input class="form-control input-sm" type="number" placeholder="0.00" required name="Current_Balance[1]" min="0" value="0.00" step="0.01" title="Currency" pattern="^\d+(?:\.\d{1,2})?$" onblur="this.value = parseFloat(this.value).toFixed(2);"></td>
                    </tr>
                    <tr>
                      <td>HSBC - 2411<input type="hidden" value="HSBC - 2411" name="Card_Type[2]"></td>
                      <td><input type="text" class="datepicker form-control input-sm" name="Statement_Date[2]"></td>
                      <td><input type="text" class="datepicker form-control input-sm" name="Statement_Due[2]"></td>
                      <td><input class="form-control input-sm" type="number" placeholder="0.00" required name="Current_Balance[2]" min="0" value="0.00" step="0.01" title="Currency" pattern="^\d+(?:\.\d{1,2})?$" onblur="this.value = parseFloat(this.value).toFixed(2);"></td>
                    </tr>
                    <tr>
                      <td>HLBB - 8200<input type="hidden" value="HLBB - 8200" name="Card_Type[3]"></td>
                      <td><input type="text" class="datepicker form-control input-sm" name="Statement_Date[3]"></td>
                      <td><input type="text" class="datepicker form-control input-sm" name="Statement_Due[3]"></td>
                      <td><input class="form-control input-sm" type="number" placeholder="0.00" required name="Current_Balance[3]" min="0" value="0.00" step="0.01" title="Currency" pattern="^\d+(?:\.\d{1,2})?$" onblur="this.value = parseFloat(this.value).toFixed(2);"></td>
                    </tr>
                    <tr>
                      <td>PBB - 9111<input type="hidden" value="PBB - 9111" name="Card_Type[4]"></td>
                      <td><input type="text" class="datepicker form-control input-sm" name="Statement_Date[4]"></td>
                      <td><input type="text" class="datepicker form-control input-sm" name="Statement_Due[4]"></td>
                      <td><input class="form-control input-sm" type="number" placeholder="0.00" required name="Current_Balance[4]" min="0" value="0.00" step="0.01" title="Currency" pattern="^\d+(?:\.\d{1,2})?$" onblur="this.value = parseFloat(this.value).toFixed(2);"></td>
                    </tr>
                    <tr>
                      <td>UOB - 1922<input type="hidden" value="UOB - 1922" name="Card_Type[5]"></td>
                      <td><input type="text" class="datepicker form-control input-sm" name="Statement_Date[5]"></td>
                      <td><input type="text" class="datepicker form-control input-sm" name="Statement_Due[5]"></td>
                      <td><input class="form-control input-sm" type="number" placeholder="0.00" required name="Current_Balance[5]" min="0" value="0.00" step="0.01" title="Currency" pattern="^\d+(?:\.\d{1,2})?$" onblur="this.value = parseFloat(this.value).toFixed(2);"></td>
                    </tr>
                    <tr>
                      <td>CIMB - 3289<input type="hidden" value="CIMB - 3289" name="Card_Type[6]"></td>
                      <td><input type="text" class="datepicker form-control input-sm" name="Statement_Date[6]"></td>
                      <td><input type="text" class="datepicker form-control input-sm" name="Statement_Due[6]"></td>
                      <td><input class="form-control input-sm" type="number" placeholder="0.00" required name="Current_Balance[6]" min="0" value="0.00" step="0.01" title="Currency" pattern="^\d+(?:\.\d{1,2})?$" onblur="this.value = parseFloat(this.value).toFixed(2);"></td>
                    </tr>
                    <tr>
                      <td>RHB - 9312<input type="hidden" value="RHB - 9312" name="Card_Type[7]"></td>
                      <td><input type="text" class="datepicker form-control input-sm" name="Statement_Date[7]"></td>
                      <td><input type="text" class="datepicker form-control input-sm" name="Statement_Due[7]"></td>
                      <td><input class="form-control input-sm" type="number" placeholder="0.00" required name="Current_Balance[7]" min="0" value="0.00" step="0.01" title="Currency" pattern="^\d+(?:\.\d{1,2})?$" onblur="this.value = parseFloat(this.value).toFixed(2);"></td>
                    </tr>
                  </tbody>
                </table>

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

                @foreach($category as $category1)

                 @if ($category1->Option==$owner)
                    <a href="{{ url('/creditcardtracker') }}/{{$category1->Option}}"><button type="button" class="btn btn-danger btn-small">{{$category1->Option}}</button></a>
                 @else
                   <a href="{{ url('/creditcardtracker') }}/{{$category1->Option}}"><button type="button" class="btn btn-success btn-small">{{$category1->Option}}</button></a>
                 @endif

                @endforeach

                <div class="nav-tabs-custom">

                   <div class="tab-content">
                     <div class="active tab-pane" id="listtab">


                        <br><br>
                        <table id="listtable" class="list" cellspacing="0" width="100%" padding="30px" style="font-size: 13px;">
                            <thead>
                              @if($lists)
                                <tr class="search">

                                  @foreach($lists as $key=>$value)

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
                                      <td align='center'><input type='text' class='search_init' name='{{$i+1}}'></td>

                                    @endif

                                  @endforeach
                                </tr>

                              @endif
                                <tr>

                                  @foreach($lists as $key=>$value)

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
                              @foreach($lists as $list)

                                    <tr id="row_{{ $i }}" >
                                         <td></td>
                                         <td></td>
                                        @foreach($list as $key=>$value)

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

                      <div class="tab-pane" id="rangetab">
                        <br>
                      </div>


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
 $(document).ready(function() {
    $('.datepicker').datepicker({
      autoclose: true,
        format: 'dd-M-yyyy',
    });
 });

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
               listtable.api().rows().select();
          }

     } else {

         $(".selectrow"+index).prop("checked", false);
         $(".selectrow"+index).trigger("change");
          //leavetable.rows().deselect();
          if (index==0)
          {
               listtable.api().rows().deselect();
          }

     }
   }

 $("#newbillform").submit(function() {

    $("#btnnewbill").prop("disabled", true);
    $.ajaxSetup({
       headers: { 'X-CSRF-Token' : $('meta[name=_token]').attr('content') }
    });

    $("#ajaxloader").show();

    $.ajax({
                url: "{{ url('/creditcard/' . $owner . '/newentry') }}",
                method: "POST",
                data: $(this).serialize(),

                success: function(response){
                  $("#btnnewbill").prop("disabled", false);
                  $("#newbillmodal").modal("hide");
                  $("#newbillform").trigger("reset");
                  listtable.api().ajax.reload();
                  console.log(response);

        }
    });
    return false;
 });

 $("#updatebillform").submit(function() {

    $("#btnupdatebill").prop("disabled", true);
    $.ajaxSetup({
       headers: { 'X-CSRF-Token' : $('meta[name=_token]').attr('content') }
    });

    $("#ajaxloader").show();

    $.ajax({
                url: "{{ url('/creditcard/' . $owner . '/updateentry') }}",
                method: "POST",
                data: $(this).serialize(),

                success: function(response){
                  $("#btnupdatebill").prop("disabled", false);
                  $("#updatebillmodal").modal("hide");
                  listtable.api().ajax.reload();
        }
    });
    return false;
 });

 function updateBill() {
  var boxes = $(".selectrow0:checkbox:checked");
  var ids="";
  var payment_type = [
    @foreach($payment_type as $payment)
      "{{ $payment->Option }}",
    @endforeach
  ];
  var payment_options = "<option></option>";

  for (var i = 0; i < payment_type.length; i++) {
    payment_options += `<option>${payment_type[i]}</option>`;
  }



  if (boxes.length>0)
  {
    var tableRows = '';
    for (var i = 0; i < boxes.length; i++) {
      ids+=boxes[i].value+",";
      var row = listtable.api().row('#row_' + boxes[i].value).data();

      tableRows += `<tr>
                      <td>${row.creditcards.Type}<input type="hidden" value="${row.creditcards.Id}" name="Id[${i}]"></td>
                      <td><input type="text" class="datepicker form-control" value="${row.creditcards.Statement_Date}" name="Statement_Date[${i}]"></td>
                      <td><input type="text" class="datepicker form-control" value="${row.creditcards.Statement_Due}" name="Statement_Due[${i}]"></td>
                      <td><input class="form-control" type="number" placeholder="0.00" required name="Current_Balance[${i}]" min="0" value="${row.creditcards.Current_Balance}" step="0.01" title="Currency" pattern="^\d+(?:\.\d{1,2})?$" onblur="this.value = parseFloat(this.value).toFixed(2);"></td>
                      <td><input type="text" class="datepicker form-control" value="${row.creditcards.Payment_Date}" name="Payment_Date[${i}]"></td>
                      <td><select style="width:115px" class="form-control select2" name="Payment_Type[${i}]"><option>${ row.creditcards.Payment_Type }</option>${payment_options}</select></td>
                      <td><input style="width:95px" class="form-control" type="number" placeholder="0.00" required name="Amount[${i}]" min="0" value="${row.creditcards.Amount}" step="0.01" title="Currency" pattern="^\d+(?:\.\d{1,2})?$" onblur="this.value = parseFloat(this.value).toFixed(2);"></td>
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
function getval(sel)
{
  window.location.href ="{{ url("/creditcardtracker") }}/"+"{{$owner}}"+"/"+sel.value;
}
</script>



@endsection
