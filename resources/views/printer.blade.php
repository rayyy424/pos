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

      .box-header .box-title{
        font-size: 14px;
      }
      span#Grand_Total{
        color:red;
      }

      tr.header{
        color: #0e42b3;
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
      <script type="text/javascript" language="javascript" src="{{ asset('/plugin/datatables/extensions/Select2/editor.select2.js') }}"></script>


      {{-- chart js --}}
      <script type="text/javascript" language="javascript" src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.5.0/Chart.bundle.min.js"></script>

      <script type="text/javascript" language="javascript" class="init">

      var editor;

      $(document).ready(function() {

        editor = new $.fn.dataTable.Editor( {
               ajax: "{{ asset('/Include/printer.php') }}",
                table: "#printertable",
                idSrc: "printer.Id",
                fields: [
                        {
                                label: "Name:",
                                name: "printer.Name",
                                type: "select2",
                                opts: {
                                    tags: true,
                                    data: [
                                      @foreach($users as $user)
                                      { text: "{{ $user->Nick_Name == "" ? $user->Name : $user->Nick_Name }}", id: "{{ $user->Nick_Name == "" ? $user->Name : $user->Nick_Name }}"},
                                      @endforeach
                                    ]
                                }
                        },{
                                label: "Floor:",
                                name: "printer.Floor",
                                type: "select2",
                                options: [
                                  { label :"", value: "" },
                                  { label :"Ground Floor", value: "Ground Floor" },
                                  { label :"First Floor", value: "First Floor" },
                                ],
                                opts: {
                                    tags: true
                                }
                        },{
                                label: "ID:",
                                name: "printer.Printer_ID"
                        },{
                                label: "Type:",
                                name: "printer.Type",
                                type: "hidden",
                                def: "Pin"
                        },{
                                label: "Password:",
                                name: "printer.Password"
                        }

                ]
        } );
        editor.on('initEdit', function ( e, json, data ) {

           // the list of users
           var users = [
               @foreach($users as $user2)
                 "{{ $user2->Nick_Name == '' ? $user2->Name : $user2->Nick_Name }}",
               @endforeach
           ];

           // the selected users from edit
           var selectedUser = data['printer']['Name'];

           // combine list and selected
           var combinedUsers = users.concat(selectedUser);

           // remove the duplicates
           var uniqueUsers = combinedUsers.filter(function(item, pos) {
               return combinedUsers.indexOf(item) == pos;
           });

           editor.field('printer.Name')
             .update(uniqueUsers)
             .val(selectedUser);
        });
        editor1 = new $.fn.dataTable.Editor( {
                ajax: {
                   "url": "{{ asset('/Include/printer1.php') }}",
                   "data": {
                       "Bill_Month": "{{ $bill_month }}"
                   }
                 },
                table: "#reporttable",
                idSrc: "printer.Id",
                formOptions: {
                     bubble: {
                         submit: 'allIfChanged'
                     }
                 },
                fields: [
                        // {
                        //         label: "Name:",
                        //         name: "printer.Name"
                        // },{
                        {
                                label: "Floor:",
                                name: "printer.Floor",
                                type: "select2",
                                options: [
                                  { label :"", value: "" },
                                  { label :"Ground Floor", value: "Ground Floor" },
                                  { label :"First Floor", value: "First Floor" },
                                  { label :"Fabyard", value: "Fabyard" },
                                ],
                                opts: {
                                    tags: true
                                }
                        },{
                                label: "Printer_Model:",
                                name: "printer.Printer_Model",
                                type:  "select2",
                                options: [
                                  { label :"", value: "" },
                                  { label :"Canon IRC 3580 B/W", value: "Canon IRC 3580 B/W" },
                                  { label :"Canon IRC 3580 Color", value: "Canon IRC 3580 Color" },
                                  { label :"Canon IR 3570 B/W", value: "Canon IR 3570 B/W" },
                                  { label :"Monthly Services Maintenance Fees", value: "Monthly Services Maintenance Fees" }
                                ],
                                opts: {
                                    tags: true
                                }
                        },{
                                label: "Type:",
                                name: "printer.Type",
                                type: "hidden",
                                def: "Report"
                        },{
                           @if($bill_month == "All")
                                label: "Bill_Month:",
                                name: "printer.Bill_Month",
                                type:  "select2",
                                options: [
                                  { label :"", value: "" },
                                  @foreach($months as $month)
                                  { label :"{{$month->Payment_Month}}", value: "{{$month->Payment_Month}}" },
                                  @endforeach
                                ],
                            @else
                                label: "Bill_Month:",
                                name: "printer.Bill_Month",
                                type:  "select2",
                                options: [
                                  { label :"{{$bill_month}}", value: "{{$bill_month}}" },
                                  @foreach($months as $month)
                                  { label :"{{$month->Payment_Month}}", value: "{{$month->Payment_Month}}" },
                                  @endforeach
                                ],
                            @endif
                        },{
                                label: "Quantity:",
                                name: "printer.Quantity"
                        },{
                                label: "Start_Date:",
                                name: "printer.Start_Date",
                                type:   'datetime',
                                def:    function () { return ""; },
                                format: 'DD-MMM-YYYY'
                        },{
                                label: "End_Date:",
                                name: "printer.End_Date",
                                type:   'datetime',
                                def:    function () { return ""; },
                                format: 'DD-MMM-YYYY'
                        }

                ]
        } );

        //Activate an inline edit on click of a table cell
              // $('#printertable').on( 'click', 'tbody td', function (e) {
              //       editor.inline( this, {
              //      onBlur: 'submit'
              //     } );
              // } );

              // $('#reporttable').on( 'click', 'tbody td', function (e) {
              //       editor1.inline( this, {
              //      onBlur: 'submit',
              //       submit: 'allIfChanged'
              //     } );
              // } );

              editor1.on( 'postEdit', function ( e, json, data ) {

                $("#Grand_Total").html("RM " + reporttable.api().column( "Total:name" ).data().sum().toFixed(2));

               } );

             var printertable= $('#printertable').dataTable( {
                    ajax: "{{ asset('/Include/printer.php') }}",
                    columnDefs: [{ "visible": false, "targets": [1] },{"className": "dt-center", "targets": "_all"}],

                    responsive: false,
                    colReorder: false,
                    bAutoWidth: true,
                    dom: "Blfrtp",
                    scrollY: "100%",
                    scrollX: "100%",
                    scrollCollapse: true,
                    bAutoWidth: true,
                    iDisplayLength:10,
                    rowId: 'printertable.Id',
                    columns: [
                            { data: null, "render":"", title:"No"},
                            { data: "printer.Id"},
                            { data: "printer.Name", title:"Name" },
                            { data: "printer.Floor" ,title:"Floor"},
                            { data: "printer.Printer_ID" , title:"ID"},
                            { data: "printer.Password", title:"Password" }
                    ],
                    select: {
                            style:    'os',
                            selector: 'td:first-child'
                    },
                    autoFill: {
                       editor:  editor
                   },
                   select: true,
                    buttons: [
                            // {
                            //   text: 'New',
                            //   action: function ( e, dt, node, config ) {
                            //       // clearing all select/input options
                            //       editor
                            //          .create( false )
                            //          .set( 'printer.Type','Pin')
                            //          .submit();
                            //   },
                            // },
                            { extend: "create", text: "New", editor: editor },
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

        var reporttable= $('#reporttable').dataTable( {
              ajax: {
                 "url": "{{ asset('/Include/printer1.php') }}",
                 "data": {
                     "Bill_Month": "{{ $bill_month }}"
                 }
               },
               columnDefs: [{ "visible": false, "targets": [1] },{"className": "dt-center", "targets": "_all"}],
               fnInitComplete: function(oSettings, json) {
                 $("#Grand_Total").html("RM " + reporttable.api().column( "Total:name" ).data().sum().toFixed(2));
               },
               responsive: false,
               colReorder: false,
               bAutoWidth: true,
               dom: "Blfrtp",
               scrollY: "100%",
               scrollX: "100%",
               scrollCollapse: true,
               bAutoWidth: true,
               "order": [[ 2, "desc" ]],
               iDisplayLength:10,
               rowId: 'printer.Id',
               columns: [
                       { data: null, "render":"", title:"No"},
                       { data: "printer.Id"},
                       { data: "printer.Floor", title:"Floor" },
                       { data: "printer.Printer_Model" ,title:"Expense Type"},
                       { data: "printer.Bill_Month" , title:"Bill Month"},
                       { data: "printer.Start_Date" , title:"Start Date"},
                       { data: "printer.End_Date" , title:"End Date"},
                       { data: "printer.Quantity", title:"Quantity" },
                       { data: "printer.UP", title:"U/P" },
                       { data: "printer.Total_Without_GST", title:"Total Without GST" },
                       { data: "printer.Total", title:"Total", name:"Total" }
               ],
               select: {
                       style:    'os',
                       selector: 'td:first-child'
               },
               autoFill: {
                  editor:  editor1
              },
              select: true,
               buttons: [
                       // {
                       //   text: 'New',
                       //   action: function ( e, dt, node, config ) {
                       //       // clearing all select/input options
                       //      if("{{$bill_month}}"=="All"){
                       //       editor1
                       //          .create( false )
                       //          .set( 'printer.Type','Report')
                       //          .set( 'printer.Bill_Month',"")
                       //          .submit();
                       //      }
                       //      else{
                       //        editor1
                       //           .create( false )
                       //           .set( 'printer.Type','Report')
                       //           .set( 'printer.Bill_Month',"{{$bill_month}}")
                       //           .submit();
                       //       }
                       //   },
                       // },
                       { extend: "create", text: "New", editor: editor1 },
                       { extend: "edit", editor: editor1 },

                       { extend: "remove", editor: editor1 },
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

        printertable.api().on( 'order.dt search.dt', function () {
          printertable.api().column(0, {search:'applied', order:'applied'}).nodes().each( function (cell, i) {
              cell.innerHTML = i+1;
          } );
        } ).draw();

        reporttable.api().on( 'order.dt search.dt', function () {
          reporttable.api().column(0, {search:'applied', order:'applied'}).nodes().each( function (cell, i) {
              cell.innerHTML = i+1;
          } );
        } ).draw();

      $(".printertable thead input").keyup ( function () {

              /* Filter on the column (the index) of this element */

              if ($('#printertable').length > 0)
              {

                var colnum=document.getElementById('printertable').rows[0].cells.length;

                if (this.value=="[empty]")
                {

                   printertable.fnFilter( '^$', $("thead input").index(this)-colnum,true,false );
                }
                else if (this.value=="[nonempty]")
                {

                   printertable.fnFilter( '^(?=\\s*\\S).*$', $("thead input").index(this)-colnum,true,false );
                }
                else if (this.value.startsWith("!")==true && this.value.length>1)
                {

                   printertable.fnFilter( '^(?'+ this.value +').*', $("thead input").index(this)-colnum,true,false );
                }
                else if (this.value.startsWith("!")==false)
                {

                  printertable.fnFilter( this.value, $("thead input").index(this)-colnum,true,false );
                }

              }

      } );

      $(".reporttable thead input").keyup ( function () {

              /* Filter on the column (the index) of this element */

              if ($('#reporttable').length > 0)
              {

                var colnum=document.getElementById('reporttable').rows[0].cells.length;

                if (this.value=="[empty]")
                {

                   reporttable.fnFilter( '^$', $("thead input").index(this)-colnum,true,false );
                }
                else if (this.value=="[nonempty]")
                {

                   reporttable.fnFilter( '^(?=\\s*\\S).*$', $("thead input").index(this)-colnum,true,false );
                }
                else if (this.value.startsWith("!")==true && this.value.length>1)
                {

                   reporttable.fnFilter( '^(?'+ this.value +').*', $("thead input").index(this)-colnum,true,false );
                }
                else if (this.value.startsWith("!")==false)
                {

                  reporttable.fnFilter( this.value, $("thead input").index(this)-colnum,true,false );
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
        Printer
        <small>IT Support</small>
      </h1>
      <ol class="breadcrumb">
       <li><a href="{{ url('/home') }}"><i class="fa fa-dashboard"></i> Home</a></li>
       <li><a href="#">IT Support</a></li>
       <li class="active">Printer</li>
      </ol>
    </section>

    <!-- Main content -->
    <section class="content">
      <div class="row">

        <div class="col-md-12">
          <!-- <div class="box">
            <div class="box-body"> -->


              <div class="nav-tabs-custom">
                 <ul class="nav nav-tabs">
                   <li class="active"><a href="#report" data-toggle="tab" id="">REPORT</a></li>
                   <li><a href="#summary" data-toggle="tab" id="">SUMMARY</a></li>
                   <li><a href="#idpin" data-toggle="tab" id="">ID AND PIN</a></li>
                 </ul>
                 <br>

                  <div class="tab-content">

                     <div class="tab-pane" id="idpin">

                        <table id="printertable" class="printertable" cellspacing="0" width="100%" padding="30px" style="font-size: 13px;">
                            <thead>
                              <tr class="search">
                                <td align='center'><input type='hidden' class='search_init' /></td>
                                @foreach($printer as $key=>$values)
                                  @if ($key==0)

                                  @foreach($values as $field=>$value)

                                      <td align='center'><input type='text' class='search_init' /></td>

                                  @endforeach

                                  @endif

                                @endforeach
                              </tr>

                                <tr>
                                  @foreach($printer as $key=>$value)

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
                              @foreach($printer as $printers)

                                <tr id="row_{{ $i }}">
                                  <td></td>
                                    @foreach($printers as $key=>$value)
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

                      <div class="active tab-pane" id="report">


                        <div class="row>">

                          <div class="col-md-6">
                            <select class="form-control select2" id="month" onchange="month()">
                              <option value="All">All</option>

                              @foreach($months as $month)
                                <option value="{{$month->Payment_Month}}" <?php if($month->Payment_Month == $bill_month) { echo "selected"; }?>>{{$month->Payment_Month}}</option>
                              @endforeach
                            </select>
                          </div>
                        </div>

                        <br><br><br>

                        <table id="reporttable" class="reporttable" cellspacing="0" width="100%" padding="30px" style="font-size: 13px;">
                            <thead>

                              <!-- <tr class="search">
                                <td align='center'><input type='hidden' class='search_init' /></td>
                                @foreach($report as $key=>$values)
                                  @if ($key==0)

                                  @foreach($values as $field=>$value)

                                      <td align='center'><input type='text' class='search_init' /></td>

                                  @endforeach

                                  @endif

                                @endforeach
                              </tr> -->

                                <tr>
                                    <td></td>
                                  @foreach($report as $key=>$value)

                                    @if ($key==0)
                                      @foreach($value as $field=>$value)
                                          <td/>{{ $field }}</td>
                                      @endforeach
                                    @endif

                                  @endforeach
                                </tr>
                            </thead>
                            <tbody>

                              <?php $i = 0; ?>
                              @foreach($report as $reportview)

                                <tr id="row_{{ $i }}">
                                  <td></td>
                                    @foreach($reportview as $key=>$value)
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

                        <div class="row">
                          <div class="col-md-12 pull-right"><b>Grand Total</b> : <i><span id='Grand_Total'>RM 0.00</span></i>
                          </div>
                        </div>

                      </div>

                      <div class="tab-pane" id="summary">


                          <div class="box-body">

                            <div class="col-md-8">
                              <div class="box box-primary">
                                <div class="box-header with-border">
                                  <h4 class="box-title">PRINTER USAGE - 2017</h4>

                                </div>
                                <div class="box-body">
                                  <div class="chart">
                                    <canvas id="lineChart_overall" style="height:300px;"></canvas>
                                  </div>
                                </div>
                              </div>
                            </div>

                            <div class="col-md-4">
                              <div class="box box-primary">
                                <div class="box-header with-border">
                                  <table class="table table-bordered">
                                    <tr class="header">
                                      <td>Bill Month</td>
                                      <td>Total (RM)</td>
                                    </tr>
                                    @foreach($printer_usage as $first)

                                      <tr>
                                          @foreach($first as $key=>$value)
                                            <td>
                                              {{ $value }}
                                            </td>
                                          @endforeach
                                      </tr>
                                      <?php $i++; ?>
                                    @endforeach

                                  </table>
                                </div>
                              </div>
                            </div>

                            <div class="col-md-8">
                              <div class="box box-primary">
                                <div class="box-header with-border">
                                  <h4 class="box-title">SUMMARY PRINTING COLOUR 1ST FLOOR</h4>

                                </div>
                                <div class="box-body">
                                  <div class="chart">
                                    <canvas id="lineChart" style="height:300px;"></canvas>
                                  </div>
                                </div>
                              </div>
                            </div>

                            <div class="col-md-4">
                              <div class="box box-primary">
                                <div class="box-header with-border">
                                  <table class="table table-bordered">
                                    <tr class="header">
                                      <td>Bill Month</td>
                                      <td>Total (RM)</td>
                                    </tr>
                                    @foreach($first_color as $first)

                                      <tr>
                                          @foreach($first as $key=>$value)
                                            <td>
                                              {{ $value }}
                                            </td>
                                          @endforeach
                                      </tr>
                                      <?php $i++; ?>
                                    @endforeach

                                  </table>
                                </div>
                              </div>
                            </div>


                            <div class="col-md-8">
                              <div class="box box-primary">
                                <div class="box-header with-border">
                                  <h4 class="box-title">SUMMARY PRINTING  B/W 1ST FLOOR</h4>

                                </div>
                                <div class="box-body">
                                  <div class="chart">
                                    <canvas id="lineChart1" style="height:300px;"></canvas>
                                  </div>
                                </div>
                              </div>
                            </div>

                            <div class="col-md-4">
                              <div class="box box-primary">
                                <div class="box-header with-border">
                                  <table class="table table-bordered">
                                    <tr class="header">
                                      <td>Bill Month</td>
                                      <td>Total (RM)</td>
                                    </tr>
                                    @foreach($first_BW as $first)

                                      <tr>
                                          @foreach($first as $key=>$value)
                                            <td>
                                              {{ $value }}
                                            </td>
                                          @endforeach
                                      </tr>
                                      <?php $i++; ?>
                                    @endforeach

                                  </table>
                                </div>
                              </div>
                            </div>

                            <div class="col-md-8">
                              <div class="box box-primary">
                                <div class="box-header with-border">
                                  <h4 class="box-title">SUMMARY PRINTING COLOR /W GROUND FLOOR</h4>

                                </div>
                                <div class="box-body">
                                  <div class="chart">
                                    <canvas id="lineChart3" style="height:300px;"></canvas>
                                  </div>
                                </div>
                              </div>
                            </div>

                            <div class="col-md-4">
                              <div class="box box-primary">
                                <div class="box-header with-border">
                                  <table class="table table-bordered">
                                    <tr class="header">
                                      <td>Bill Month</td>
                                      <td>Total (RM)</td>
                                    </tr>
                                    @foreach($ground_color as $first)

                                      <tr>
                                          @foreach($first as $key=>$value)
                                            <td>
                                              {{ $value }}
                                            </td>
                                          @endforeach
                                      </tr>
                                      <?php $i++; ?>
                                    @endforeach

                                  </table>
                                </div>
                              </div>
                            </div>

                            <div class="col-md-8">
                              <div class="box box-primary">
                                <div class="box-header with-border">
                                  <h4 class="box-title">SUMMARY PRINTING  B/W GROUND FLOOR</h4>

                                </div>
                                <div class="box-body">
                                  <div class="chart">
                                    <canvas id="lineChart2" style="height:300px;"></canvas>
                                  </div>
                                </div>
                              </div>
                            </div>

                            <div class="col-md-4">
                              <div class="box box-primary">
                                <div class="box-header with-border">
                                  <table class="table table-bordered">
                                    <tbody>
                                      <tr class="header">
                                        <td>Bill Month</td>
                                        <td>Total (RM)</td>
                                      </tr>
                                      @foreach($ground_BW as $first)

                                        <tr>
                                            @foreach($first as $key=>$value)
                                              <td>
                                                {{ $value }}
                                              </td>
                                            @endforeach
                                        </tr>
                                        <?php $i++; ?>
                                      @endforeach
                                    </tbody>

                                  </table>
                                </div>
                              </div>
                            </div>

                          </div>


                      </div>

                  </div>
              </div>

            <!-- </div>
          </div> -->

          <!-- /.nav-tabs-custom -->
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

function month() {
    var x = document.getElementById("month").value;
    window.location.href ="{{ url("/printer") }}/"+x;
}

//line chart js
    $(function () {
      $(".select2").select2();
      var str_all = "{{ $printer1 }}";
         str_all = str_all.split(",").map(function(str_all){
               return str_all;
           })
       var a_all=[str_all];
       var line_all = document.getElementById("lineChart_overall");
       var lineChart_overall = new Chart(line_all, {
           type: 'line',
           data: {
             labels:a_all[0],
             datasets: [{
             label: 'Printing Colour Amount',
             data: [{{ $printer2 }}],
             borderColor: "#3c8dbc",
             pointBackgroundColor: "#3c8dbc",
             fill:false,
           }]
         },
         options : {
           scales : {
               xAxes : [ {
                   gridLines : {
                       display : false
                   }
               } ],
               yAxes : [ {
                   ticks: {
                    beginAtZero: true,
                    userCallback: function(label, index, labels) {
                      if (Math.floor(label) === label) {
                        return label;
                      }

                    },
                  },
                   gridLines : {
                       display : false
                   }
               } ]
           }
       }
       });



        var str = "{{$first_color1}}";
           str = str.split(",").map(function(str){
                 return str;
             })
         var a=[str];
         var line = document.getElementById("lineChart");
         var linechart = new Chart(line, {
             type: 'line',
             data: {
               labels:a[0],
               datasets: [{
               label: 'Printing Colour Amount',
               data: [{{$first_color2}}],
               borderColor: "#3c8dbc",
               pointBackgroundColor: "#3c8dbc",
               fill:false,
             }]
           },
           options : {
             scales : {
                 xAxes : [ {
                     gridLines : {
                         display : false
                     }
                 } ],
                 yAxes : [ {
                     ticks: {
                      beginAtZero: true,
                      userCallback: function(label, index, labels) {
                        if (Math.floor(label) === label) {
                          return label;
                        }

                      },
                    },
                     gridLines : {
                         display : false
                     }
                 } ]
             }
         }
         });


         var str1 = "{{$first_bw1}}";
            str1 = str1.split(",").map(function(str1){
                  return str1;
              })
          var a1=[str1];
          var line1 = document.getElementById("lineChart1");
          var linechart1 = new Chart(line1, {
              type: 'line',
              data: {
                labels:a1[0],
                datasets: [{
                label: 'Printing B/W Amount',
                data: [{{$first_bw2}}],
                borderColor: "#3c8dbc",
                pointBackgroundColor: "#3c8dbc",
                fill:false,
              }]
            },
            options : {
              scales : {
                  xAxes : [ {
                      gridLines : {
                          display : false
                      }
                  } ],
                  yAxes : [ {
                      ticks: {
                       beginAtZero: true,
                       userCallback: function(label, index, labels) {
                         if (Math.floor(label) === label) {
                           return label;
                         }

                       },
                     },
                      gridLines : {
                          display : false
                      }
                  } ]
              }
          }
          });

          var str2 = "{{$ground_bw1}}";
             str2 = str2.split(",").map(function(str2){
                   return str2;
               })
           var a2=[str2];
           var line2 = document.getElementById("lineChart2");
           var linechart2 = new Chart(line2, {
               type: 'line',
               data: {
                 labels:a2[0],
                 datasets: [{
                 label: 'Printing B/W Amount',
                 data: [{{$ground_bw2}}],
                 borderColor: "#3c8dbc",
                 pointBackgroundColor: "#3c8dbc",
                 fill:false,
               }]
             },
             options : {
               scales : {
                   xAxes : [ {
                       gridLines : {
                           display : false
                       }
                   } ],
                   yAxes : [ {
                       ticks: {
                        beginAtZero: true,
                        userCallback: function(label, index, labels) {
                          if (Math.floor(label) === label) {
                            return label;
                          }

                        },
                      },
                       gridLines : {
                           display : false
                       }
                   } ]
               }
           }
           });

           var str3 = "{{$ground_color1}}";
              str3 = str3.split(",").map(function(str3){
                    return str3;
                })
            var a3=[str3];
            var line3 = document.getElementById("lineChart3");
            var linechart3 = new Chart(line3, {
                type: 'line',
                data: {
                  labels:a2[0],
                  datasets: [{
                  label: 'Printing Color Amount',
                  data: [{{$ground_color2}}],
                  borderColor: "#3c8dbc",
                  pointBackgroundColor: "#3c8dbc",
                  fill:false,
                }]
              },
              options : {
                scales : {
                    xAxes : [ {
                        gridLines : {
                            display : false
                        }
                    } ],
                    yAxes : [ {
                        ticks: {
                         beginAtZero: true,
                         userCallback: function(label, index, labels) {
                           if (Math.floor(label) === label) {
                             return label;
                           }

                         },
                       },
                        gridLines : {
                            display : false
                        }
                    } ]
                }
            }
            });



    });

</script>


@endsection
