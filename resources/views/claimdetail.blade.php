@extends('app')

@section('datatable-css')

    <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.10.12/css/jquery.dataTables.min.css">
    <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/buttons/1.2.1/css/buttons.dataTables.min.css">
    <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/responsive/2.1.0/css/responsive.dataTables.min.css">
    <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/select/1.2.0/css/select.dataTables.min.css">
    <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/keytable/2.1.2/css/keyTable.dataTables.min.css">
    <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/autofill/2.1.2/css/autoFill.dataTables.min.css">
    <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/colreorder/1.3.2/css/colReorder.dataTables.min.css">
    <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/fixedcolumns/3.2.2/css/fixedColumns.dataTables.min.css">

    <link rel="stylesheet" type="text/css" href="{{ asset('/plugin/css/editor.dataTables.min.css') }}">
    {{-- <link rel="stylesheet" type="text/css" href="{{ asset('/plugin/examples/resources/syntax/shCore.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('/Plugin/examples/resources/demo.css') }}"> --}}

    <link rel="stylesheet" type="text/css" href="{{ asset('/plugin/imagezoom1/jquery.iviewer.css') }}">


    <style type="text/css" class="init">
      a.buttons-collection {
        margin-left: 1em;
      }
      .container1{
              width: 1200px;
              margin-left: 50px;
              padding: 10px;
      }

      .weekend {
        color: red;
      }

      .weekendrow.even {
        background-color: #FADBD8;
      }

      .green {
        color: green;
      }

      .yellow {
        color: #f39c12;
      }

      .red{
        color:red;
      }

      table.dataTable.display tbody tr.weekendrow.odd {
        background-color: #FADBD8;
      }

      .timetable{
        text-align: center;
      }

      .timeheader{
        background-color: gray;
      }
      .timesheetbox{
        width: 1000px;
      }

      #table-wrapper {
        position:relative;
      }
      #table-scroll {
        height:150px;
        overflow:auto;
        margin-top:20px;
      }
      #table-wrapper table {
        width:100%;

      }
      .buttontimesheet img{
        width:40px;
      }
      .zoom {
  			display:inline-block;
  			position: relative;
  		}

  		/* magnifying glass icon */
  		.zoom:after {
  			content:'';
  			display:block;
  			width:13px;
  			height:33px;
  			position:absolute;
  			top:100;
  			right:0;
  			background:url(icon.png);
  		}

  		.zoom img {
  			display: block;
        width:570px;
  		}
      canvas{
        max-width:570px;

      }
      canvas img{
        max-width:570px;

      }

        a:visited{

             opacity: 0.3;
      }
      .viewer2
      {
          width: 50%;
          height: 500px;
          border: 1px solid black;
          position: relative;
      }

      .wrapper
      {
          overflow: hidden;
      }
      .viewer2 img{
        position: absolute;
top: 0;
left: 0;
max-width: none;
width: "";
height: "";
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


      <script type="text/javascript" language="javascript" src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.8.4/moment.min.js"></script>
      <script type="text/javascript" language="javascript" src="https://cdn.datatables.net/plug-ins/1.10.15/sorting/datetime-moment.js"></script>

      <script type="text/javascript" language="javascript" src="{{ asset('/plugin/imagezoom/jquery.zoom.js') }}"></script>

      <script type="text/javascript" language="javascript" src="{{ asset('/plugin/js/dataTables.editor.min.js') }}"></script>
      {{-- <script type="text/javascript" language="javascript" src="{{ asset('/plugin/examples/resources/syntax/shCore.js') }}"></script>
      <script type="text/javascript" language="javascript" src="{{ asset('/plugin/examples/resources/demo.js') }}"></script>
      <script type="text/javascript" language="javascript" src="{{ asset('/plugin/examples/resources/editor-demo.js') }}"></script> --}}

      <!-- Image rotation -->
      {{-- <script type="text/javascript" language="javascript" src="{{ asset('/plugin/imagerotation/jquery.rotate.js') }}"></script> --}}
      <script type="text/javascript" language="javascript" src="{{ asset('/plugin/imagezoom1/jquery.iviewer.js') }}"></script>
      <!-- <script type="text/javascript" language="javascript" src="{{ asset('/plugin/imagezoom1/jqueryui.js') }}" ></script> -->
      <script type="text/javascript" language="javascript" src="{{ asset('/plugin/imagezoom1/jquery.mousewheel.min.js') }}" ></script>

      <script>

      var editor;
      var claimtable;
      var timesheet;

      $(document).ready(function() {

        $.fn.dataTable.moment( 'DD-MMM-YYYY' );

                     editor = new $.fn.dataTable.Editor( {
                         ajax: {
                            "url": "{{ asset('/Include/claimapproval.php') }}",
                            "data": {
                              @if($viewall==false)
                                "Approver": "{{ $me->UserId }}",
                              @endif
                              "Id": "{{ $Id }}",
                              "UserId": "{{ $user->Id }}"
                            }
                          },
                             table: "#pendingtable",
                             formOptions: {
                                  inline: {
                                      submit: 'allIfChanged'
                                  }
                              },
                             idSrc: "claimstatuses.Id",
                             fields: [
                               {
                                       name: "claims.Id",

                               },{
                                      label: "Depart_From:",
                                      name: "claims.Depart_From"
                               },{
                                      label: "Destination:",
                                      name: "claims.Destination"
                               },{
                                      label: "Site Name:",
                                      name: "claims.Site_Name"
                               },{
                                      label: "State:",
                                      name: "claims.State",
                                      type:  'select',
                                      options: [
                                          { label :"", value: "" },
                                          @foreach($options as $option)
                                            @if ($option->Field=="State")
                                              { label :"{{$option->Option}}", value: "{{$option->Option}}" },
                                            @endif
                                          @endforeach

                                      ],
                               },{
                                      label: "Work Description:",
                                      name: "claims.Work_Description"
                               },{
                                      label: "Next Person:",
                                      name: "claims.Next_Person"
                               },{
                                      label: "Transport_Type:",
                                      name: "claims.Transport_Type",
                                      type:  'select',
                                      options: [
                                          { label :"", value: "" },
                                          { label :"Car", value: "Car" },
                                          { label :"Motorbike", value: "Motorbike" },
                                      ],
                               },{
                                      label: "Car No:",
                                      name: "claims.Car_No"
                               },{
                                      label: "Mileage:",
                                      name: "claims.Mileage"
                               },{
                                      label: "Currency:",
                                      name: "claims.Currency",
                                      type:  'select',
                                      options: [
                                          { label :"", value: "" },
                                          @foreach($options as $option)
                                            @if ($option->Field=="Currency")
                                              { label :"{{$option->Option}}", value: "{{$option->Option}}" },
                                            @endif
                                          @endforeach
                                      ],
                               },{
                                      label: "Rate:",
                                      name: "claims.Rate",
                                      attr: {
                                        type: "number"
                                      }

                               },{
                                      label: "Expenses Type:",
                                      name: "claims.Expenses_Type",
                                      type:  'select',
                                      options: [
                                          { label :"", value: "" },
                                          @foreach($options as $option)
                                            @if ($option->Field=="Expenses_Type")
                                              { label :"{{$option->Option}}".replace("&amp;", '&'), value: "{{$option->Option}}".replace("&amp;", '&') },
                                            @endif
                                          @endforeach
                                      ],
                               },{
                                      label: "Total Expenses:",
                                      name: "claims.Total_Expenses"
                               },{
                                      label: "Advance:",
                                      name: "claims.Advance"
                               },{
                                      label: "Summon:",
                                      name: "claims.Summon"
                               },{
                                      label: "Allowance:",
                                      name: "allowance.Allowance",
                                      type: "readonly"
                               },{
                                      label: "Petrol SmartPay:",
                                      name: "claims.Petrol_SmartPay"
                               },{
                                      label: "GST Amount:",
                                      name: "claims.GST_Amount"
                               },{
                                      label: "Total Without GST:",
                                      name: "claims.Total_Without_GST"
                               },{
                                      label: "Receipt No:",
                                      name: "claims.Receipt_No"
                               },{
                                      label: "Company_Name:",
                                      name: "claims.Company_Name"
                               },{
                                      label: "GST No:",
                                      name: "claims.GST_No"
                               },{
                                      label: "Remarks:",
                                      name: "claims.Remarks",
                                      type: "textarea"
                               },
                               {
                                       label: "Comment:",
                                       name: "claimstatuses.Comment",
                                       type: "textarea"
                               }

                             ]
                     } );

                     timesheet=$('#timesheettable').dataTable( {
                       columnDefs: [{ "visible": false, "targets": [0,1,2,3,4,5,20] },{"className": "dt-center dt-padding", "targets": "_all"}],
                       colReorder: false,
                       sScrollX: "100%",
                       bAutoWidth: true,
                       sScrollY: "100%",
                       bPaginate:true,
                       responsive:false,
                       aaSorting: [[7,"asc"]],
                       dom: "lBfrtpi",
                             buttons: [

                             ],

                 });


                     // Activate an inline edit on click of a table cell
                     // Activate an inline edit on click of a table cell
                           $('#pendingtable').on( 'click', 'tbody td:not(.child), tbody span.dtr-data', function (e) {
                             editor.inline( this, {
                            onBlur: 'submit',
                            submit: 'allIfChanged'
                               } );
                           } );

                           editor.on( 'postEdit', function ( e, json, data ) {

                             var totalexpenses=0.0;
                             var totaladvance=0.0;
                             var totalallowance=0.0;
                             var totalsummon=0.0;
                             var totalsmartpay=0.0;
                             var total=0.0;
                             var totalgst=0.0;
                             var totalnogst=0.0;
                             var totalwithallowance=0.0;

                             claimtable.api().rows().every( function () {
                                var d = this.data();

                                var status=d.claimstatuses.Status;

                                if(status.includes("Rejected")==false)
                                {
                                  totalexpenses=totalexpenses+parseFloat(d.claims.Total_Expenses);
                                  totaladvance=totaladvance+parseFloat(d.claims.Advance);
                                  // if(d.allowance.Allowance)
                                  // {
                                  //   totalallowance=totalallowance+parseFloat(d.allowance.Allowance);
                                  // }
                                  //
                                  // if(d.allowance.Monetary_Comp)
                                  // {
                                  //   totalallowance=totalallowance+parseFloat(d.allowance.Monetary_Comp);
                                  // }

                                  totalsmartpay=totalsmartpay+parseFloat(d.claims.Petrol_SmartPay);
                                  total=total+parseFloat(d.claims.Total_Amount);
                                  totalgst=totalgst+parseFloat(d.claims.GST_Amount);
                                  totalnogst=totalnogst+parseFloat(d.claims.Total_Without_GST);

                                }

                                totalsummon=totalsummon+parseFloat(d.claims.Summon);

                              } );

                              timesheet.api().rows().every( function () {
                                 var d = this.data();

                                 totalallowance=totalallowance+parseFloat(d[13])+parseFloat(d[14]);

                                  //  totalallowance=totalallowance+parseFloat(d.timesheets.Allowance)+parseFloat(d.timesheets.Monetary_Comp);

                               } );

                              totalwithallowance=total+totalallowance;

                             $("#totalexpenses").html("RM" + totalexpenses.toFixed(2));
                             $("#totaladvance").html("RM" + totaladvance.toFixed(2));
                             $("#totalallowance").html("RM" + totalallowance.toFixed(2));
                             $("#totalsummon").html("RM" + totalsummon.toFixed(2));
                             $("#totalsmartpay").html("RM" + totalsmartpay.toFixed(2));
                             $("#total").html("RM" + total.toFixed(2));
                             $("#totalgst").html("RM" + totalgst.toFixed(2));
                             $("#totalnogst").html("RM" + totalnogst.toFixed(2));
                             $("#totalwithallowance").html("RM" + totalwithallowance.toFixed(2));

                            } );

                          //  editor.on( 'postEdit', function ( e, json, data ) {
                          //       $(this.modifier()).addClass('data-changed')
                           //
                          //       insertclaimstatus(data);
                          //   } );

                           claimtable=$('#pendingtable').dataTable( {
                             ajax: {
                                "url": "{{ asset('/Include/claimapproval.php') }}",
                                "data": {
                                  @if($viewall=="false")
                                    "Approver": "{{ $me->UserId }}",
                                  @endif
                                  "Id": "{{ $Id }}",
                                  "UserId": "{{ $user->Id }}"
                                }
                              },
                                   columnDefs: [{ "visible": false, "targets": [1,2] },{"className": "dt-center", "targets": "_all"}],
                                   rowId: 'claimstatuses.Id',
                                   colReorder: true,
                                   bAutoWidth: true,
                                   iDisplayLength:10,
                                   scrollY:        "300px",
                                   scrollX:        true,
                                   scrollCollapse: true,
                                   paging:         false,

                                   //sScrollY: "100%",
                                   dom: "lBfrtpi",
                                   aaSorting: [[4,"asc"]],
                                   fnInitComplete: function(oSettings, json) {

                                     var totalexpenses=0.0;
                                     var totaladvance=0.0;
                                     var totalallowance=0.0;
                                     var totalsummon=0.0;
                                     var totalsmartpay=0.0;
                                     var total=0.0;
                                     var totalgst=0.0;
                                     var totalnogst=0.0;
                                     var totalwithallowance=0.0;

                                     claimtable.api().rows().every( function () {
                                        var d = this.data();

                                        var status=d.claimstatuses.Status;

                                        if(status.includes("Rejected")==false)
                                        {
                                          totalexpenses=totalexpenses+parseFloat(d.claims.Total_Expenses);
                                          totaladvance=totaladvance+parseFloat(d.claims.Advance);

                                          // if(d.allowance.Allowance)
                                          // {
                                          //   totalallowance=totalallowance+parseFloat(d.allowance.Allowance);
                                          // }
                                          //
                                          // if(d.allowance.Monetary_Comp)
                                          // {
                                          //   totalallowance=totalallowance+parseFloat(d.allowance.Monetary_Comp);
                                          // }

                                          totalsmartpay=totalsmartpay+parseFloat(d.claims.Petrol_SmartPay);
                                          total=total+parseFloat(d.claims.Total_Amount);
                                          totalgst=totalgst+parseFloat(d.claims.GST_Amount);
                                          totalnogst=totalnogst+parseFloat(d.claims.Total_Without_GST);

                                        }

                                        totalsummon=totalsummon+parseFloat(d.claims.Summon);

                                      } );

                                      timesheet.api().rows().every( function () {
                                         var d = this.data();

                                          totalallowance=totalallowance+parseFloat(d[13])+parseFloat(d[14]);

                                       } );

                                      totalwithallowance=total+totalallowance;

                                     $("#totalexpenses").html("RM" + totalexpenses.toFixed(2));
                                     $("#totaladvance").html("RM" + totaladvance.toFixed(2));
                                     $("#totalallowance").html("RM" + totalallowance.toFixed(2));
                                     $("#totalsmartpay").html("RM" + totalsmartpay.toFixed(2));
                                     $("#totalsummon").html("RM" + totalsummon.toFixed(2));
                                     $("#total").html("RM" + total.toFixed(2));
                                     $("#totalgst").html("RM" + totalgst.toFixed(2));
                                     $("#totalnogst").html("RM" + totalnogst.toFixed(2));
                                     $("#totalwithallowance").html("RM" + totalwithallowance.toFixed(2));

                                     @if ($claim[0]->Status=="Recalled" || $claim[0]->Status=="Submitted for Approval")
                                         $('#submitbtn').show();
                                         $('#recallbtn').hide();
                                        //  editor.enable();
                                        //  claimtable.api().autoFill().enable();

                                     @elseif ($claim[0]->Status=="Submitted for Next Approval" || $claim[0]->Status=="Final Approved" || $claim[0]->Status=="Final Rejected")

                                         $('#submitbtn').show();
                                         $('#recallbtn').show();
                                        //  editor.disable();
                                        //  claimtable.api().autoFill().disable();

                                     @endif

                                    },
                                    createdRow: function ( row, data, index ) {
                                      var days = ['Sun','Mon','Tue','Wed','Thu','Fri','Sat'];
                                      var d = new Date(data.claims.Date)
                                      var n = days[d.getDay()];

                                      if (n=="Sun" || n=="Sat")
                                      {
                                        $(row).removeClass('odd');
                                        $(row).addClass('weekendrow');

                                      }
                                    },
                                   columns: [
                                     {
                                       sortable: false,
                                       "render": function ( data, type, full, meta ) {
                                           return '<input type="checkbox" name="selectrow" id="selectrow" class="selectrow" value="'+full.claimstatuses.Id+'" onclick="uncheck()">';

                                       }

                                     },
                                     { data: "claimstatuses.Id"},
                                     { data: "claims.Id"},
                                     { title:"Status",
                                     "render": function ( data, type, full, meta ) {

                                          if(full.claimstatuses.Status.includes("Approved"))
                                          {
                                            return "<span class='green'>"+full.claimstatuses.Status+"</span>";
                                          }
                                          else if(full.claimstatuses.Status.includes("Rejected"))
                                          {
                                            return "<span class='red'>"+full.claimstatuses.Status+"</span>";
                                          }
                                          else {
                                            return "<span class='yellow'>"+full.claimstatuses.Status+"</span>";
                                          }

                                       }
                                     },
                                     { data: "claims.Date",title:"Date", Type: "date-moment",
                                     "render": function ( data, type, full, meta ) {

                                            return '<a class ="buttonclaim" onclick="viewtimesheet(\''+full.claims.Date+'\',\'{{$user->Id}}\')">'+full.claims.Date+'</a>';
                                        }
                                     },
                                     {
                                       title:"Day",
                                       sortable: false,
                                       "render": function ( data, type, full, meta ) {
                                         var days = ['Sun','Mon','Tue','Wed','Thu','Fri','Sat'];
                                         var dateSplit = full.claims.Date.split("-");
                                         objDate = new Date(dateSplit[1] + " " + dateSplit[0] + ", " + dateSplit[2]);
                                         var d = new Date(objDate)
                                         var n = days[d.getDay()];

                                         if (n=="Sun" || n=="Sat")
                                         {
                                           return "<span class='weekend'>"+n+"</span>"
                                         }

                                           return n;
                                       }
                                    },
                                     { data: "claims.Depart_From"},
                                     { data: "claims.Destination"},
                                     { data: "claims.Site_Name"},
                                     { data: "claims.State"},
                                     { data: "claims.Work_Description"},
                                     { data: "claims.Next_Person"},
                                     { data: "claims.Transport_Type"},
                                     { data: "claims.Car_No",title:"Vehicle_No" },
                                     { data: "claims.Mileage" ,title:"Mileage"},
                                     { data: "claims.Currency",title:"Currency" },
                                     { data: "claims.Rate",title:"Cur_Rate" },
                                     { data: "claims.Expenses_Type",title:"Expenses_Type" },
                                     { data: "claims.Total_Expenses",title:"Total_Expenses",name:"Total_Expenses" },
                                     { data: "claims.Petrol_SmartPay",title:"Petrol_SmartPay",name:"Petrol_SmartPay" },
                                     {
                                       title:"Claims_Amount_Exclude_SmartPay",
                                        sortable: false,
                                        "render": function ( data, type, full, meta ) {
                                        return (full.claims.Total_Expenses-full.claims.Petrol_SmartPay).toFixed(2);
                                        }
                                     },
                                     { data: "claims.Advance",title:"Advance",name:"Advance" },
                                     { data: "claims.Summon",title:"Summon",name:"Summon" },
                                     { data: "claims.Total_Amount" , title:"Total_Payable",name: "Total_Amount"},
                                     { data: "allowance.Allowance",title:"Allowance",name:"Allowance" },
                                     { data: "allowance.Monetary_Comp",title:"Monetary_Comp",name:"Monetary_Comp" },
                                     { data: "claims.GST_Amount",name: "GST_Amount"},
                                     { data: "claims.Total_Without_GST",name: "Total_Without_GST"},
                                     { data: "claims.Receipt_No"},
                                     { data: "claims.Company_Name"},
                                     { data: "claims.GST_No"},

                                     { data: "claims.Remarks"},
                                     { data: "approver.Name"},
                                     { data: "claimstatuses.Comment"},
                                     { data: "claimstatuses.updated_at"}

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

            // Activate an inline edit on click of a table cell
                  $('#pendingtable').on( 'click', 'tbody td', function (e) {
                        editor.inline( this, {
                       onBlur: 'submit'
                      } );
                  } );

                  $("#ajaxloader").hide();
                  $("#ajaxloader2").hide();


          } );


      </script>
@endsection

@section('content')

    <div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
      <h1>
        Claim Detail
        <small>My Workplace</small>
      </h1>
      <ol class="breadcrumb">
        <li><a href="#"><i class="fa fa-dashboard"></i> Home</a></li>
        <li><a href="#">My Workplace</a></li>
        <li><a href="{{ url('/claim/') }}"/{{$user->Id}}>My Claim</a></li>
        <li class="active">Claim Detail</li>
      </ol>
    </section>

    <!-- Main content -->
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


         <div class="box-body">
           <button type="button" class="btn btn-success btn-lg" data-toggle="modal" data-target="#ExportPDF">Export Claim and Timesheet</button>
           <button type="button" class="btn btn-success btn-lg" data-toggle="modal" data-target="#ExportPDF2">Export Claim Only</button>
         </div>

         <div class="modal fade" id="ExportPDF" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
          <div class="modal-dialog">
           <div class="modal-content">
             <div class="modal-header">

               <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
               <h4 class="modal-title" id="myModalLabel">Export Claim</h4>

             </div>

             <div class="modal-body">
                 Are you sure you wish to export this claim?
             </div>
             <div class="modal-footer">
               <a class="btn btn-primary btn-lg" href="{{ url('/exportclaim') }}/{{$claim[0]->Id}}/{{$user->Id}}/{{$start}}/{{$end}}" target="_blank">Export</a>
             </div>
           </div>
         </div>
       </div>

       <div class="modal fade" id="ExportPDF2" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
        <div class="modal-dialog">
         <div class="modal-content">
           <div class="modal-header">

             <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
             <h4 class="modal-title" id="myModalLabel">Export Claim</h4>

           </div>

           <div class="modal-body">
               Are you sure you wish to export this claim?
           </div>
           <div class="modal-footer">
             <a class="btn btn-primary btn-lg" href="{{ url('/exportclaim2') }}/{{$claim[0]->Id}}/{{$user->Id}}/{{$start}}/{{$end}}" target="_blank">Export</a>
           </div>
         </div>
       </div>
     </div>

       <div class="modal fade" id="Redirect" role="dialog" aria-labelledby="myModalLabel">
         <div class="modal-dialog" role="document">
           <div class="modal-content">
             <div class="modal-header">
               <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
               <h4 class="modal-title" id="myModalLabel">Redirect</h4>
             </div>
             <div class="modal-body">
               <div class="form-group" id="redirectleavestatus">

               </div>
               <div class="form-group">

                   <label>Approver : </label>

                   <select class="form-control select2" id="NewApprover" name="NewApprover" style="width: 100%;">

                     @if ($approver)
                       @foreach ($approver as $app)

                           <option  value="{{$app->Id}}">{{$app->Name}}</option>

                       @endforeach

                     @endif

                     </select>

               </div>
             </div>
             <div class="modal-footer">
               <center><img src="{{ URL::to('/') ."/img/ajax-loader.gif" }}" width="50px" height="50px" alt="Loading" name='ajaxloader2' id="ajaxloader2"></center>
               <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
               <button type="button" class="btn btn-primary" onclick="redirect()">Redirect</button>
             </div>
           </div>
         </div>
       </div>

       <div class="modal fade" id="Recall" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
         <div class="modal-dialog" role="document">
           <div class="modal-content">
             <div class="modal-header">
               <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
               <h4 class="modal-title" id="myModalLabel">Recall Claim</h4>
             </div>
             <div class="modal-body">
                 Are you sure you wish to recall this claim sheet?
             </div>
             <div class="modal-footer">
               <center><img src="{{ URL::to('/') ."/img/ajax-loader.gif" }}" width="50px" height="50px" alt="Loading" name='ajaxloader2' id="ajaxloader2"></center>
               <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
               <button type="button" class="btn btn-primary" onclick="Recall({{$claim[0]->Id}})">Recall</button>
             </div>
           </div>
         </div>
       </div>

       <div class="modal fade" id="popup" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
         <div class="modal-dialog" role="document">
           <div class="modal-content">
             <div class="modal-header">
               <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
               <h4 class="modal-title" id="myModalLabel">Image</h4>
             </div>
             <div class="modal-body">
               <div class="wrapper">


                   <div id="viewer2" class="viewer2" style="width: 100%;">
                     <!-- <img class="img responsive" id="imageurl" src=""/> -->
                   </div>
                   <br />

               </div>
               <!-- <div id="viewer" class="viewer">

                 <img class="img responsive" id="imageurl" src=""/>

               </div> -->

                <!-- <button class="btn btn-primary" onclick="left()">Rotate Left</button>
               <button class="btn btn-primary" onclick="right()">Rotate Right</button> -->
             </div>
             <div class="modal-footer">
               <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
             </div>
           </div>
         </div>
       </div>

         <div class="modal fade" id="Submit" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
           <div class="modal-dialog" role="document">
             <div class="modal-content">
               <div class="modal-header">
                 <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                 <h4 class="modal-title" id="myModalLabel">Submit and Notify</h4>
               </div>
               <div class="modal-body">
                   Are you sure you wish to submit the selected claim for next action?
               </div>
               <div class="modal-footer">
                 <center><img src="{{ URL::to('/') ."/img/ajax-loader.gif" }}" width="50px" height="50px" alt="Loading" name='ajaxloader' id="ajaxloader"></center>
                 <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                 <button type="button" class="btn btn-primary" onclick="submit({{ $claim[0]->Id }})">Yes</button>
               </div>
             </div>
           </div>
         </div>

         <div class="modal fade" id="ViewTimesheet" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
           <div class="modal-dialog timesheetbox"  role="document">
             <div class="modal-content">
               <div class="modal-header">
                 <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                 <h4 class="modal-title" id="myModalLabel">Timesheet Details</h4>
               </div>
               <div class="modal-body" name="timesheet" id="timesheet">

               </div>
               <div class="modal-footer">
                 <center><img src="{{ URL::to('/') ."/img/ajax-loader.gif" }}" width="50px" height="50px" alt="Loading" name='ajaxloader3' id="ajaxloader3"></center>
                 <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
               </div>
             </div>
           </div>
         </div>

         <div class="col-md-4">

           <div class="box box-success">

             <div class="box-body box-profile">

             <br>
             <div class="row">
               <div class="form-group">
                 @if ($user->Web_Path)

                   <img class="profile-user-img img-responsive img-circle" name="profileimage" id="profileimage" src="{{ url($user->Web_Path) }}" alt="User profile picture">
                 @else
                     <img class="profile-user-img img-responsive img-circle" name="profileimage" id="profileimage" src="{{ URL::to('/') ."/img/default-user.png"  }}" alt="User profile picture">
                 @endif
               </div>
             </div>

             <div class="row">

               <div class="form-group">
                 <div class="col-lg-6">
                   <label>StaffId : <i>{{$user->StaffId}}</i></label>
                 </div>

                 <div class="col-lg-6">
                   <label>Name : <i>{{$user->Name}}</i></label>
                 </div>

               </div>
             </div>

             <div class="row">
               <div class="form-group">

                 <div class="col-lg-6">

                   <label>Position : <i>{{$user->Position}}</i></label>
                 </div>

               </div>
             </div>

           <div class="row">
             <div class="form-group">
               <div class="col-lg-6">
                 <label>Nationality : <i>{{$user->Nationality}}</i></label>

               </div>

               <div class="col-lg-6">
                 <label>Home Base : <i>{{$user->Home_Base}}</i></label>
               </div>

             </div>
           </div>

         </div>

           {{-- <a href="#" class="btn btn-primary btn-block"><b>Update</b></a> --}}
         </div>
         <!-- /.box-body -->
       </div>

       <div class="col-md-4">
         <div class="box box-success">
           <div class="box-body box-profile">

             <ul class="list-group list-group-unbordered">
               <li class="list-group-item">
                 <b>Claim Name</b> : <br><i>{{ $claim[0]->Claim_Sheet_Name }}</i>
               </li>

               <li class="list-group-item">
                 <b>Remarks</b> : <br><i>{{ $claim[0]->Remarks }}</i>
               </li>
               <li class="list-group-item">
                 <b>Status</b> : <p class="pull-right"><i><span id='status'>{{ $claim[0]->Status }}</span></i></p>
               </li>
               <li class="list-group-item">
                 <b>Date</b> : <p class="pull-right"><i>{{ $claim[0]->Created_Date }}</i></p>
               </li>
               <li class="list-group-item">
                 <b>Total Expenses</b> : <p class="pull-right"><i><span id='totalexpenses'>RM0.00</span></i></p>
               </li>
               <li class="list-group-item">
                 <b>Total SmartPay</b> : <p class="pull-right"><i><span id='totalsmartpay'>RM0.00</span></i></p>
               </li>
               <li class="list-group-item">
                 <b>Total Advance</b> : <p class="pull-right"><i><span id='totaladvance'>RM0.00</span></i></p>
               </li>
               <li class="list-group-item">
                 <b>Total Allowance</b> : <p class="pull-right"><i><span id='totalallowance'>RM0.00</span></i></p>
               </li>

               <li class="list-group-item">
                 <b>Total Summon</b> : <p class="pull-right"><i><span id='totalsummon'>RM0.00</span></i></p>
               </li>

               <li class="list-group-item">
                 <b>Total GST Amount</b> : <p class="pull-right"><i><span id='totalgst'>RM0.00</span></i></p>
               </li>
               <li class="list-group-item">
                 <b>Total Without GST</b> : <p class="pull-right"><i><span id='totalnogst'>RM0.00</span></i></p>
               </li>
               <li class="list-group-item">
                 <b>Total Payable</b> : <p class="pull-right"><i><span id='total'>RM0.00</span></i></p>
               </li>

               <li class="list-group-item">
                 <b>Total Payable + Allowance + Monetary Comp</b> : <p class="pull-right"><i><span id='totalwithallowance'>RM0.00</span></i></p>
               </li>

               <li class="list-group-item">
                 <b>Claim Sheet Status</b> : <br><i>{{ $claim[0]->Claim_Status }}</i>
               </li>
               <li class="list-group-item">
                 <b>Checked By</b> : <br><i>{{ $claim[0]->Updated_By }}</i>
               </li>
               <li class="list-group-item">
                 <b>Date</b> : <br><i>{{ $claim[0]->Updated_At }}</i>
               </li>


             </ul>

             @if($me->Update_Payment_Month)

               <select class="changed form-control select" id="checkerstatus" style="width: 100%;">

               <option></option>
               @foreach ($months as $month)

                   <option value="{{$month}}">{{$month}}</option>

               @endforeach
             </select><br>

               <button type="button" class="btn btn-primary btn" data-toggle="modal" onclick="updateclaimsheet('{{$claim[0]->Id}}')">Processed</button>
               <button type="button" class="btn btn-danger btn" data-toggle="modal" onclick="updateclaimsheet('{{$claim[0]->Id}}')">Reset</button>

             @endif
             {{-- <a href="#" class="btn btn-primary btn-block"><b>Update</b></a> --}}
           </div>
         </div>
       </div>

       <div class="col-md-4">
         <div class="box box-success">

           <div class="box-header with-border">
                 Receipt
             <br>
             <br>

               <div id="receiptdiv">

               @foreach ($receipts as $receipt)

                 @if(strpos($receipt->Web_Path,'.png') !== false || strpos($receipt->Web_Path,'.jpg') !== false || strpos($receipt->Web_Path,'.jpeg') !== false ||strpos($receipt->Web_Path,'.PNG') !== false || strpos($receipt->Web_Path,'.JPG') !== false || strpos($receipt->Web_Path,'.JPEG') !== false)

                   <div class="col-sm-6" id="receipt{{ $receipt->Id }}">

                     <a onclick="imagepopup('{{ url($receipt->Web_Path) }}')" class="coolLinks">
                       <span class="zoom">
                         <img class="img-responsive coolLinks" src="{{ url($receipt->Web_Path) }}" alt="Photo" />
                       </span>
                     </a>
                     <a download="{{ url($receipt->Web_Path) }}" href="{{ url($receipt->Web_Path) }}" title="Download"><button type="button" class="btn btn-block btn-primary btn-xs">Download</button></a>
                   </div>

                 @else
                   <div class="col-sm-6" id="receipt{{ $receipt->Id }}">
                     <a download="{{ $receipt->File_Name }}" href="{{ url($receipt->Web_Path) }}" title="Download">
                       {{ $receipt->File_Name}}
                     </a>
                   </div>

                 @endif

               @endforeach

             </div>

           </div>

         </div>
       </div>

    </div>



   @if($timesheetdetail)

     <div class="row">

       <div class="col-md-12">

         <div class="box box-success">
           <br>

           <div class="col-md-6">
           <div class="input-group">
             <div class="input-group-addon">
               <i class="fa fa-clock-o"></i>
             </div>
             <input type="text" class="form-control" id="range" name="range">

           </div>
         </div>

         <div class="col-md-6">
             <div class="input-group">
               <button type="button" class="btn btn-success btn" data-toggle="modal" onclick="refresh();">Refresh</button>
             </div>
         </div>
         <label></label>
       </div>

         <div class="box-header with-border">
           <h3 class="box-title">Timesheets without claim</h3>
         </div>

           <table id="timesheettable" class="display" cellspacing="0" width="100%" padding="30px" style="font-size: 13px;">
             <thead>
                 {{-- prepare header search textbox --}}
                 <tr>
                   @foreach($timesheetdetail as $key=>$value)

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
               @foreach($timesheetdetail as $timesheet)

                 <tr>
                     @foreach($timesheet as $key=>$value)
                         <td>
                           @if($key=="Day")
                             {{ date('D', strtotime($timesheet->Date)) }}
                           @else
                             {{ $value }}
                           @endif
                         </td>

                     @endforeach
                 </tr>

                 <?php $i++; ?>
               @endforeach

           </tbody>
         </table>

       </div>
     </div>

   @endif

      {{-- <div class="row">

        <div class="box box-success">
          <br>

          <div class="col-md-4">
          <div class="input-group">
            <div class="input-group-addon">
              <i class="fa fa-clock-o"></i>
            </div>
            <input type="text" class="form-control" id="range" name="range">

          </div>
        </div>

        <div class="col-md-6">
            <div class="input-group">
              <button type="button" class="btn btn-success btn" data-toggle="modal" onclick="refresh();">Refresh</button>
            </div>
        </div>
        <label></label>
      </div>
    </div> --}}
    <div class="row">


    </div>

    <div class="box box-success">
      <br>




        <div class="row">

          <div class="col-md-12">

            <button type="button" class="btn btn-primary btn-lg" id="submitbtn" data-toggle="modal" data-target="#Submit">Submit and Notify</button>

            {{-- <button type="button" class="btn btn-danger btn-lg" id="recallbtn"  data-toggle="modal" data-target="#Recall">Recall</button> --}}

            <button type="button" class="btn btn-success btn" data-toggle="modal" onclick="approve()">Approve</button>
            <button type="button" class="btn btn-success btn" data-toggle="modal" onclick="approve2()">Approve with Special Attention</button>
            <button type="button" class="btn btn-danger btn" data-toggle="modal" onclick="reject()">Reject</button>
            <button type="button" class="btn btn-warning btn" data-toggle="modal" data-target="#Redirect">Redirect</button>

            <br>
            <br>

                  <table id="pendingtable" class="display" cellspacing="0" width="100%" padding="30px" style="font-size: 13px;">
                      <thead>
                          {{-- prepare header search textbox --}}
                          <tr>
                            @foreach($claimdetail as $key=>$value)

                              @if ($key==0)
                                <td><input type="checkbox" name="selectall" id="selectall" value="all" onclick="checkall()"></td>

                                @foreach($value as $field=>$value)
                                    <td/>{{ $field }}</td>
                                @endforeach

                              @endif

                            @endforeach
                          </tr>
                      </thead>
                      <tbody>

                        <?php $i = 0; ?>
                        @foreach($claimdetail as $claim)

                              <tr id="row_{{ $i }}" >
                                <td></td>
                                  @foreach($claim as $key=>$value)
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
  $(function () {

    //Initialize Select2 Elements
    $(".select2").select2();

    //Flat red color scheme for iCheck
    $('input[type="checkbox"].flat-blue, input[type="radio"].flat-blue').iCheck({
      checkboxClass: 'icheckbox_flat-blue',
      radioClass: 'iradio_flat-blue'
    });

    //Date picker
    $('#Date').datepicker({
      autoclose: true
    });

    //Timepicker
    $(".timepicker").timepicker({
      showInputs: false
    });

    $('#range').daterangepicker({locale: {
      format: 'DD-MMM-YYYY'
    },startDate: '{{$start}}',
    endDate: '{{$end}}'});

    // document.getElementById("Time_In").value = '';
    // document.getElementById("Time_Out").value = '';

  });

  function viewtimesheet(date,userid)
  {

    $('#ViewTimesheet').modal('show');
    $("#timesheet").html("");

    $.ajaxSetup({
       headers: { 'X-CSRF-Token' : $('meta[name=_token]').attr('content') }
    });

    $("#ajaxloader3").show();

    $.ajax({
                url: "{{ url('/claimmanagement/viewtimesheet') }}",
                method: "POST",
                data: {
                  Date:date,
                  UserId:userid
                },
                success: function(response){
                  if (response==0)
                  {
                    var message ="Failed to retrieve asset history!";
                    $("#warning-alert ul").html(message);
                    $("#warning-alert").modal('show');
                    $('#ReturnedModal').modal('hide');

                    $("#ajaxloader3").hide();
                  }
                  else {

                    $("#exist-alert").hide();

                    var myObject = JSON.parse(response);


                    var display='<div id="table-wrapper"><div id="table-scroll"><table border="1" align="center" class="timetable" cellspacing="0" width="100%" padding="30px" style="font-size: 13px;">';
                    display+='<tr class="timeheader"><td>Check_In_Type</td><td>Time_In</td><td>Time_Out</td><td>Allowance</td><td>Leader_Member</td><td>Site_Name</td><td>State</td><td>Work_Description</td><td>Reason</td><td>Remarks</td><td>Approver</td><td>Status</td><td>Comment</td><td>Review_Date</td></tr>';

                    $.each(myObject, function(i,item){

                            display+="<tr>";
                            display+='<td>'+item.Check_In_Type+'</td><td>'+item.Time_In+'</td><td>'+item.Time_Out+'</td><td>'+item.Allowance+'</td><td>'+item.Leader_Member+'</td><td>'+item.Site_Name+'</td><td>'+item.State+'</td><td>'+item.Work_Description+'</td><td>'+item.Reason+'</td><td>'+item.Remarks+'</td><td>'+item.Approver+'</td><td>'+item.Status+'</td><td>'+item.Comment+'</td><td>'+item.updated_at+'</td>';
                            display+="</tr>";
                    });

                    display+="</table></div></div>";

                    $("#timesheet").html(display);

                    $("#ajaxloader3").hide();
                  }
        }
    });

  }


  function uncheck()
  {

    if (!$("#selectrow").is(':checked')) {
      $("#selectall").prop("checked", false)
    }

  }

  function checkall()
  {
    var allPages = claimtable.fnGetNodes();
        // alert(document.getElementById("selectall").checked);
    if ($("#selectall").is(':checked')) {
       $('input[type="checkbox"]', allPages).prop('checked', true);
        // $(".selectrow").prop("checked", true);
         $(".selectrow").trigger("change");
         claimtable.api().rows().select();
    } else {

        $('input[type="checkbox"]', allPages).prop('checked', false);
        $(".selectrow").trigger("change");
         claimtable.api().rows().deselect();
    }
  }

  function refresh()
  {
    var d=$('#range').val();
    var arr = d.split(" - ");

    @if($viewall=="false")

      window.location.href ="{{ url("/claim") }}/{{$ClaimsheetId}}/{{$user->Id}}/false/"+arr[0]+"/"+arr[1];

    @elseif($viewall=="true")

      window.location.href ="{{ url("/claim") }}/{{$ClaimsheetId}}/{{$user->Id}}/true/"+arr[0]+"/"+arr[1];

    @else

      window.location.href ="{{ url("/claim") }}/{{$ClaimsheetId}}/{{$user->Id}}/null/"+arr[0]+"/"+arr[1];
    @endif



  }

  function imagepopup(url){
      // $('#imageurl').attr('src', url);
      $("#popup").modal('show');
      // $('.zoom').zoom();

      // alert(url);
      var iv1 = $("#viewer2").iviewer({

           src: url,

      });


  }

  // function left(){
  //   $('#imageurl').rotateLeft();
  //   $('.zoom').zoom();
  // }
  // function right(){
  //   $('#imageurl').rotateRight();
  //   $('.zoom').zoom();
  // }


  function Recall(id)
  {

      $.ajaxSetup({
         headers: { 'X-CSRF-Token' : $('meta[name=_token]').attr('content') }
      });

      $("#ajaxloader2").show();

      Id=id;

      $.ajax({
                  url: "{{ url('/myclaim/recall') }}",
                  method: "POST",
                  data: {Id:id},

                  success: function(response){

                    if (response==1)
                    {
                        claimtable.api().ajax.url("{{ asset('/Include/claimapproval.php') }}").load();
                        var message="Claim recalled!";
                        $("#update-alert ul").html(message);
                        $("#update-alert").modal('show');

                        $('#Recall').modal('hide');
                        $('#submitbtn').show();
                        $('#recallbtn').hide();

                        // editor.enable();
                        // claimtable.api().autoFill().enable();

                        $('#status').html("Recalled");

                        $("#ajaxloader2").hide();

                    }
                    else {

                      var errormessage="Failed to recall claim!";
                      $("#error-alert ul").html(errormessage);
                      $("#error-alert").modal('show');

                      $('#Recall').modal('hide');

                      $("#ajaxloader2").hide();

                    }

          }
      });

  }

  function approve() {

    var boxes = $('input[type="checkbox"]:checked', claimtable.fnGetNodes() );
    var ids="";

    if (boxes.length>0)
    {
      for (var i = 0; i < boxes.length; i++) {
        ids+=boxes[i].value+",";
      }

      ids=ids.substring(0, ids.length-1);

      status="Approved";

      @if($mylevel)
        @if ($mylevel->Level=="1st Approval")
          status="1st Approved";
        @endif

        @if ($mylevel->Level=="2nd Approval")
          status="2nd Approved";
        @endif

        @if ($mylevel->Level=="3rd Approval")
          status="3rd Approved";
        @endif

        @if ($mylevel->Level=="4th Approval")
          status="4th Approved";
        @endif

        @if ($mylevel->Level=="5th Approval")
          status="5th Approved";
        @endif

        @if ($mylevel->Level=="Final Approval")
          status="Final Approved";
        @endif

      @endif

      $.ajaxSetup({
         headers: { 'X-CSRF-Token' : $('meta[name=_token]').attr('content') }
      });

        $("#ajaxloader").show();

      $.ajax({
                  url: "{{ url('/claim/approve') }}",
                  method: "POST",
                  data: {StatusIds:ids,
                  Status:status},

                  success: function(response){

                    if (response==1)
                    {

                        claimtable.api().ajax.url("{{ asset('/Include/claimapproval.php') }}").load();

                        var message="Claim Approved!";
                        $("#update-alert ul").html(message);
                        $("#update-alert").modal('show');


                        $("#ajaxloader").hide();

                    }
                    else {

                      var errormessage="Failed to approve claim!";
                      $("#error-alert ul").html(errormessage);
                      $("#error-alert").modal('show');


                      $("#ajaxloader").hide();

                    }

          }
      });

  }
  else {
    var errormessage="No claim selected!";
    $("#error-alert ul").html(errormessage);
    $("#error-alert").modal('show');


  }

}

  function submit(id) {

      $.ajaxSetup({
         headers: { 'X-CSRF-Token' : $('meta[name=_token]').attr('content') }
      });

        $("#ajaxloader").show();

        Id=id;

      $.ajax({
                  url: "{{ url('/claim/submit') }}",
                  method: "POST",
                    data: {Id:id},

                  success: function(response){

                    if (response==1)
                    {

                        claimtable.api().ajax.url("{{ asset('/Include/claimapproval.php') }}").load();

                        var message="Submitted for next action!";
                        $("#update-alert ul").html(message);
                        $("#update-alert").modal('show');


                        $('#Submit').modal('hide');

                        $('#submitbtn').show();
                        $('#recallbtn').show();

                        // editor.disable();
                        // claimtable.api().autoFill().disable();
                        $('#status').html("Submitted for Next Approval");

                        $("#ajaxloader").hide();

                    }
                    else {

                      var errormessage="Failed to submit for next action!";
                      $("#error-alert ul").html(errormessage);
                      $("#error-alert").modal('show');


                      $('#Submit').modal('hide');

                      $("#ajaxloader").hide();

                    }

          }
      });

    }

    function approve2() {

      var boxes = $('input[type="checkbox"]:checked', claimtable.fnGetNodes() );
      var ids="";

      if (boxes.length>0)
      {
        for (var i = 0; i < boxes.length; i++) {
          ids+=boxes[i].value+",";
        }

        ids=ids.substring(0, ids.length-1);

        status="Approved with Special Attention";

        @if($mylevel)
          @if ($mylevel->Level=="1st Approval")
            status="1st Approved with Special Attention";
          @endif

          @if ($mylevel->Level=="2nd Approval")
            status="2nd Approved with Special Attention";
          @endif

          @if ($mylevel->Level=="3rd Approval")
            status="3rd Approved with Special Attention";
          @endif

          @if ($mylevel->Level=="4th Approval")
            status="4th Approved with Special Attention";
          @endif

          @if ($mylevel->Level=="5th Approval")
            status="5th Approved with Special Attention";
          @endif

          @if ($mylevel->Level=="Final Approval")
            status="Final Approved with Special Attention";
          @endif

        @endif

        $.ajaxSetup({
           headers: { 'X-CSRF-Token' : $('meta[name=_token]').attr('content') }
        });

          $("#ajaxloader").show();

        $.ajax({
                    url: "{{ url('/claim/approve') }}",
                    method: "POST",
                    data: {StatusIds:ids,
                    Status:status},

                    success: function(response){

                      if (response==1)
                      {

                          claimtable.api().ajax.url("{{ asset('/Include/claimapproval.php') }}").load();

                          var message="Claim Approved!";
                          $("#update-alert ul").html(message);
                          $("#update-alert").modal('show');


                         $("#ajaxloader").hide();

                      }
                      else {

                        var errormessage="Failed to approve claim!";
                        $("#error-alert ul").html(errormessage);
                        $("#error-alert").modal('show');


                        $("#ajaxloader").hide();

                      }

            }
        });

    }
    else {
      var errormessage="No claim selected!";
      $("#error-alert ul").html(errormessage);
      $("#error-alert").modal('show');

    }

  }

  function reject() {

    var boxes = $('input[type="checkbox"]:checked', claimtable.fnGetNodes() );
    var ids="";

    if (boxes.length>0)
    {
      for (var i = 0; i < boxes.length; i++) {
        ids+=boxes[i].value+",";
      }

      ids=ids.substring(0, ids.length-1);

      status="Rejected";

      @if($mylevel)
        @if ($mylevel->Level=="1st Approval")
          status="1st Rejected";
        @endif

        @if ($mylevel->Level=="2nd Approval")
          status="2nd Rejected";
        @endif

        @if ($mylevel->Level=="3rd Approval")
          status="3rd Rejected";
        @endif

        @if ($mylevel->Level=="4th Approval")
          status="4th Rejected";
        @endif

        @if ($mylevel->Level=="5th Approval")
          status="5th Rejected";
        @endif

        @if ($mylevel->Level=="Final Approval")
          status="Final Rejected";
        @endif

      @endif

      $.ajaxSetup({
         headers: { 'X-CSRF-Token' : $('meta[name=_token]').attr('content') }
      });

        $("#ajaxloader").show();

      $.ajax({
                  url: "{{ url('/claim/approve') }}",
                  method: "POST",
                  data: {StatusIds:ids,
                  Status:status},

                  success: function(response){

                    if (response==1)
                    {

                        claimtable.api().ajax.url("{{ asset('/Include/claimapproval.php') }}").load();

                        var message="Claim Rejected!";
                        $("#update-alert ul").html(message);
                        $("#update-alert").modal('show');


                       $("#ajaxloader").hide();

                    }
                    else {

                      var errormessage="Failed to reject claim!";
                      $("#error-alert ul").html(errormessage);
                      $("#error-alert").modal('show');


                      $("#ajaxloader").hide();

                    }

          }
      });

  }
  else {
    var errormessage="No claim selected!";
    $("#error-alert ul").html(errormessage);
    $("#error-alert").modal('show');


  }

}

function redirect() {

    $.ajaxSetup({
       headers: { 'X-CSRF-Token' : $('meta[name=_token]').attr('content') }
    });

    $("#ajaxloader").show();

    var boxes = $('input[type="checkbox"]:checked', claimtable.fnGetNodes() );
    var ids="";

    if (boxes.length>0)
    {
      for (var i = 0; i < boxes.length; i++) {
        ids+=boxes[i].value+",";
      }

      ids=ids.substring(0, ids.length-1);

      newapprover=$('[name="NewApprover"]').val();

    $.ajax({
                url: "{{ url('/claim/redirect') }}",
                method: "POST",
                data: {StatusIds:ids,Approver:newapprover},

                success: function(response){

                  if (response==1)
                  {
                      claimtable.api().ajax.url("{{ asset('/Include/claimapproval.php') }}").load();
                      var message="Claim redirected!";
                      $("#update-alert ul").html(message);
                      $("#update-alert").modal('show');


                      $('#Redirect').modal('hide');

                      $("#ajaxloader2").hide();

                  }
                  else {

                    var errormessage="Failed to redirect claim!";
                    $("#error-alert ul").html(errormessage);
                    $("#error-alert").modal('show');


                    $('#Redirect').modal('hide');

                    $("#ajaxloader2").hide();

                  }

        }
    });
  }
  else {
    var errormessage="No claim selected!";
    $("#error-alert ul").html(errormessage);
    $("#error-alert").modal('show');

  }

}

function updateclaimsheet(id) {

  var status=$('#checkerstatus').val();

    $.ajaxSetup({
       headers: { 'X-CSRF-Token' : $('meta[name=_token]').attr('content') }
    });

    $.ajax({
                url: "{{ url('/claimsheet/updatestatus') }}",
                method: "POST",
                  data: {Id:id,Status:status},

                success: function(response){

                  if (response>0)
                  {

                      var message="Status Updated!";
                      $("#update-alert ul").html(message);
                      $("#update-alert").modal('show');

                        window.location.reload();

                  }
                  else {

                    var errormessage="Failed to update status!";
                    $("#error-alert ul").html(errormessage);
                    $("#error-alert").modal('show');



                  }

        }
    });

  }

</script>

@endsection
