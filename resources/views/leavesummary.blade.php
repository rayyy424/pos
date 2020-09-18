
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

      .leavetable{
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
      {{-- chart js --}}
      <script type="text/javascript" language="javascript" src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.4.0/Chart.bundle.min.js"></script>

      <script type="text/javascript" language="javascript" class="init">

      var leavesummary;
      var leavetaken;
      var leavebalance;
      var sickbalance;


      $(document).ready(function() {

          leavesummary = $('#leavesummary').dataTable( {

                  columnDefs: [{ "visible": false, "targets": [1] },{"className": "dt-center", "targets": "_all"}],
                  responsive: false,
                  colReorder: false,
                  dom: "lBfrtp",
                  "lengthMenu": [[10, 15, 50, 100, 500, -1], [10, 15, 50, 100,500, "All"]],
                  "iDisplayLength": 15,
                  sScrollX: "100%",
                  language : {
                     sLoadingRecords : 'Loading data...',
                     processing: 'Loading data...'
                   },
                  bAutoWidth: true,
                  sScrollY: "100%",
                  scrollCollapse: true,

                  columns:[
                    { data:null, "render":"", title:"No"},
                    { data:'users.Id', title:'users.Id'},
                    { data:'users.StaffId', title:'Staff_ID'},
                    { data:'users.Name', title:'Name',
                      "render": function ( data, type, full, meta ) {

                        return '<a href="{{ URL::to('/')}}/individualleavesummary/'+full.users.Id+'" alt="Edit" title="Edit" target="_blank">'+full.users.Name+'</a>';


                      }
                    },
                    { data:'users.Company', title:"Company"},
                    { data:'Confirmation_Date', title:"Confirmation_Date"},
                    { data:'Resignation_Date', title:"Resignation_Date"},
                    { data:'Working_Days', title:"Working_Days"},
                    // { data:'Current_Entitlement', title:"Current Entitlement (AL)"},
                    { title:"Total Working Days"},
                    { title:"Staff Working Days"},
                    { title:"Time In"},
                    { title:"No Time In"},
                    { title:"Work On Sunday"},
                    { title:"Work On Public"},
                    { title:"Forgot Time Out"},
                    { title:"On Leave Time In"},
                    { data:'Leave_Days',  title:"Leave_Taken"},
                    @foreach($leavetype as $leave)

                        { data : "{{$leave->Option}}", titlte : "{{$leave->Option}}",
                          "render": function ( data, type, full, meta ) {
                            return "<a onclick='viewdata(\"{{$leave->Option}}\",\""+full.users.Id+"\",\"{{$start}}\",\"{{$end}}\")'>"+data+"</a>";
                          }
                        },

                        @if($leave->Option=="Medical Leave")
                          { title:'Medical_claim'},
                        @endif

                    @endforeach
                  ],

                  autoFill: {


                 },
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

            leavetaken = $('#leavetakentable').dataTable( {

                    columnDefs: [{ "visible": false, "targets": [] },{"className": "dt-center", "targets": "_all"}],
                    responsive: false,
                    colReorder: false,
                    dom: "lBfrtp",
                    "lengthMenu": [[10,15, 50, 100, 500, -1], [10,15, 50, 100,500, "All"]],
                    "iDisplayLength": 15,
                    sScrollX: "100%",
                    bAutoWidth: true,
                    sScrollY: "100%",
                    scrollCollapse: true,

                    columns:[
                        { data:null, "render":"", title:"No"},
                        { data:'StaffID', title:"StaffID"},
                        { data:'Name', title:"Name"},
                        { data:'Leave_Type', title:"Leave Code"},
                        { data:'Start_Date', title:"Start Leave Date"},
                        { data:'End_Date', title:"End_Date"},
                        { data:'No_Of_Days', title:"No of Days"},
                        { data:'Reason', title:"Reason"},

                    ],

                    autoFill: {


                   },
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

            leavebalance = $('#leavebalancetable').dataTable( {

                    columnDefs: [{ "visible": false, "targets": [1,11,12,19,20] },{"className": "dt-center", "targets": "_all"}],
                    responsive: false,
                    colReorder: false,
                    dom: "lBfrtp",
                    "lengthMenu": [[10,15, 50, 100, 500, -1], [10,15, 50, 100,500, "All"]],
                    "iDisplayLength": 15,
                    sScrollX: "100%",
                    bAutoWidth: true,
                    sScrollY: "100%",
                    scrollCollapse: true,



                    autoFill: {


                   },
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

              sickbalance = $('#sickbalancetable').dataTable( {

                      columnDefs: [{ "visible": false, "targets": [1] },{"className": "dt-center", "targets": "_all"}],
                      responsive: false,
                      colReorder: false,
                      dom: "lBfrtp",
                      "lengthMenu": [[10,15, 50, 100, 500, -1], [10,15, 50, 100,500, "All"]],
                      "iDisplayLength": 15,
                      sScrollX: "100%",
                      bAutoWidth: true,
                      sScrollY: "100%",
                      scrollCollapse: true,

                      autoFill: {


                     },
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


              $('a[data-toggle="tab"]').on( 'shown.bs.tab', function (e) {
                var target = $(e.target).attr("href") // activated tab


                  $.fn.dataTable.tables( {visible: true, api: true} ).columns.adjust();


              } );


            leavesummary.api().on( 'order.dt search.dt', function () {
                leavesummary.api().column(0, {search:'applied', order:'applied'}).nodes().each( function (cell, i) {
                    cell.innerHTML = i+1;
                } );
            } ).draw();

            leavetaken.api().on( 'order.dt search.dt', function () {
                leavetaken.api().column(0, {search:'applied', order:'applied'}).nodes().each( function (cell, i) {
                    cell.innerHTML = i+1;
                } );
            } ).draw();



            leavebalance.api().on( 'order.dt search.dt', function () {
                leavebalance.api().column(0, {search:'applied', order:'applied'}).nodes().each( function (cell, i) {
                    cell.innerHTML = i+1;
                } );
            } ).draw();

            sickbalance.api().on( 'order.dt search.dt', function () {
                sickbalance.api().column(0, {search:'applied', order:'applied'}).nodes().each( function (cell, i) {
                    cell.innerHTML = i+1;
                } );
            } ).draw();

            $(".leavesummary thead input").keyup ( function () {

                    /* Filter on the column (the index) of this element */
                    if ($('#leavesummary').length > 0)
                    {

                        var colnum=document.getElementById('leavesummary').rows[0].cells.length;

                        if (this.value=="[empty]")
                        {

                           leavesummary.fnFilter( '^$', this.name,true,false );
                        }
                        else if (this.value=="[nonempty]")
                        {

                           leavesummary.fnFilter( '^(?=\\s*\\S).*$', this.name,true,false );
                        }
                        else if (this.value.startsWith("!")==true && this.value.length>1)
                        {

                           leavesummary.fnFilter( '^(?'+ this.value +').*', this.name,true,false );
                        }
                        else if (this.value.startsWith("!")==false)
                        {

                            leavesummary.fnFilter( this.value, this.name,true,false );
                        }
                    }



            } );



            $(".leavebalance thead input").keyup ( function () {

                    /* Filter on the column (the index) of this element */
                    if ($('#leavebalancetable').length > 0)
                    {

                        var colnum=document.getElementById('leavebalancetable').rows[0].cells.length;

                        if (this.value=="[empty]")
                        {

                           leavebalance.fnFilter( '^$', this.name,true,false );
                        }
                        else if (this.value=="[nonempty]")
                        {

                           leavebalance.fnFilter( '^(?=\\s*\\S).*$', this.name,true,false );
                        }
                        else if (this.value.startsWith("!")==true && this.value.length>1)
                        {

                           leavebalance.fnFilter( '^(?'+ this.value +').*', this.name,true,false );
                        }
                        else if (this.value.startsWith("!")==false)
                        {

                            leavebalance.fnFilter( this.value, this.name,true,false );
                        }
                    }

            } );

            $(".sickbalance thead input").keyup ( function () {

                    /* Filter on the column (the index) of this element */
                    if ($('#sickbalancetable').length > 0)
                    {

                        var colnum=document.getElementById('sickbalancetable').rows[0].cells.length;

                        if (this.value=="[empty]")
                        {

                           sickbalance.fnFilter( '^$', this.name,true,false );
                        }
                        else if (this.value=="[nonempty]")
                        {

                           sickbalance.fnFilter( '^(?=\\s*\\S).*$', this.name,true,false );
                        }
                        else if (this.value.startsWith("!")==true && this.value.length>1)
                        {

                           sickbalance.fnFilter( '^(?'+ this.value +').*', this.name,true,false );
                        }
                        else if (this.value.startsWith("!")==false)
                        {

                            sickbalance.fnFilter( this.value, this.name,true,false );
                        }
                    }

            } );

            $(".leavetaken thead input").keyup ( function () {

                    /* Filter on the column (the index) of this element */
                    if ($('#leavetakentable').length > 0)
                    {

                        var colnum=document.getElementById('leavetakentable').rows[0].cells.length;

                        if (this.value=="[empty]")
                        {

                           leavetaken.fnFilter( '^$', this.name,true,false );
                        }
                        else if (this.value=="[nonempty]")
                        {

                           leavetaken.fnFilter( '^(?=\\s*\\S).*$', this.name,true,false );
                        }
                        else if (this.value.startsWith("!")==true && this.value.length>1)
                        {

                           leavetaken.fnFilter( '^(?'+ this.value +').*', this.name,true,false );
                        }
                        else if (this.value.startsWith("!")==false)
                        {

                            leavetaken.fnFilter( this.value, this.name,true,false );
                        }
                    }

            } );


        });

        $('a[data-toggle="tab"]').on( 'shown.bs.tab', function (e) {
          var target = $(e.target).attr("href") // activated tab


            $.fn.dataTable.tables( {visible: true, api: true} ).columns.adjust();


        } );

      </script>

      @endsection

@section('content')

  <div class="content-wrapper">
      <!-- Content Header (Page header) -->
    <section class="content-header">
      <h1>
      Leave Summary
      <small>Human Resource</small>
      </h1>

      <ol class="breadcrumb">
      <li><a href="{{ url('/home') }}"><i class="fa fa-dashboard"></i> Home</a></li>
      <li><a href="#">Human Resource</a></li>
      <li><a href="#">Leave</a></li>
      <li class="active">Leave Summary</li>
      </ol>
    </section>

    <section class="content">

      <div class="modal fade" id="NameList" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
          <div class="modal-dialog" role="document">
            <div class="modal-content">
              <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title" id="myModalLabel">Name List</h4>
              </div>
              <div class="modal-body" name="list" id="list">

              </div>
              <div class="modal-footer">
                <center><img src="{{ URL::to('/') ."/img/ajax-loader.gif" }}" width="50px" height="50px" alt="Loading" name='ajaxloader3' id="ajaxloader3"></center>
                <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
              </div>
            </div>
          </div>
      </div>

      <div class="row">

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

          <br>
          <br>

        <div class="col-md-12">
          <div class="nav-tabs-custom">
            <ul class="nav nav-tabs">

              <li class="active"><a href="#leavebreakdown" data-toggle="tab" id="leavebreakdowntab">Leave Taken Breakdown</a></li>
              <li><a href="#leavetaken" data-toggle="tab" id="leavetakentab">Leave Taken List</a></li>

              <li><a href="#leavebalance" data-toggle="tab" id="leavebalancetab">Annual Leave Balance</a></li>
              <li><a href="#sickbalance" data-toggle="tab" id="sickbalancetab">Medical Leave Balance</a></li>

            </ul>

            <br>
            <div class="tab-content">

              <div class="tab-pane active" id="leavebreakdown">

                <div class="row">
                  <div class="col-md-12">

                    <table id="leavesummary" class="leavesummary" cellspacing="0" width="100%" padding="30px" style="font-size: 13px;">
                          <thead>

                            @if($leavesummary)
                              <tr class="search">

                                @foreach($leavesummary as $key=>$value)

                                  @if ($key==0)
                                    <?php $i = 0; ?>

                                    @foreach($value as $field=>$a)
                                        @if ($i==0|| $i==1)
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
                            @endif
                              {{-- prepare header search textbox --}}
                              <tr>
                                @foreach($leavesummary as $key=>$value)

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
                            @foreach($leavesummary as $leave)

                                <tr id="row_{{ $i }}">
                                      <td></td>

                                    @foreach($leave as $key=>$value)
                                      <td>
                                        @if ($key=="Total_Working_Days")
                                          @if ($leave->Working_Days==0)
                                            {{ $d5 }}
                                          @elseif ($leave->Working_Days==5)
                                            {{ $d5 }}
                                          @elseif ($leave->Working_Days==5.5)
                                            {{ $d55 }}
                                          @elseif ($leave->Working_Days==6)
                                            {{ $d6 }}
                                          @endif
                                        @elseif ($key=="Staff_Working_Days")
                                          @if ($leave->Working_Days==0)
                                            {{ $d5-$leave->Leave_Taken }}
                                          @elseif ($leave->Working_Days==5)
                                            {{ $d5-$leave->Leave_Taken }}
                                          @elseif ($leave->Working_Days==5.5)
                                            {{ $d55-$leave->Leave_Taken }}
                                          @elseif ($leave->Working_Days==6)
                                            {{ $d6-$leave->Leave_Taken }}
                                          @endif
                                        @elseif ($key=="Non_Check_In_Day")
                                          @if ($leave->Working_Days==0)
                                            @if ($d5-$leave->Non_Check_In_Day-$leave->Leave_Taken<1)
                                              0
                                            @else
                                              <a onclick='viewdata2("{{$leave->Id}}","{{$leave->Working_Days}}","{{$start}}","{{$end}}","No Time In")'>{{ $d5-$leave->Non_Check_In_Day-$leave->Leave_Taken }}</a>
                                            @endif

                                          @elseif ($leave->Working_Days==5)
                                            @if ($d5-$leave->Non_Check_In_Day-$leave->Leave_Taken<1)
                                              0
                                            @else

                                               <a onclick='viewdata2("{{$leave->Id}}","{{$leave->Working_Days}}","{{$start}}","{{$end}}","No Time In")'>{{ $d5-$leave->Non_Check_In_Day-$leave->Leave_Taken }}</a>
                                            @endif
                                          @elseif ($leave->Working_Days==5.5)
                                            @if ($d55-$leave->Non_Check_In_Day-$leave->Leave_Taken<1)
                                              0
                                            @else
                                              <a onclick='viewdata2("{{$leave->Id}}","{{$leave->Working_Days}}","{{$start}}","{{$end}}","No Time In")'>{{ $d55-$leave->Non_Check_In_Day-$leave->Leave_Taken }}</a>
                                            @endif
                                          @elseif ($leave->Working_Days==6)
                                            @if ($d6-$leave->Non_Check_In_Day-$leave->Leave_Taken<1)
                                              0
                                            @else
                                              <a onclick='viewdata2("{{$leave->Id}}","{{$leave->Working_Days}}","{{$start}}","{{$end}}","No Time In")'>{{ $d6-$leave->Non_Check_In_Day-$leave->Leave_Taken }}</a>
                                            @endif
                                          @endif
                                        @elseif ($key=="Check_In_Day")
                                          <a onclick='viewdata2("{{$leave->Id}}","{{$leave->Working_Days}}","{{$start}}","{{$end}}","Time In")'>{{ $value }}</a>

                                        @elseif ($key=="Work_On_Sunday")
                                          <a onclick='viewdata2("{{$leave->Id}}","{{$leave->Working_Days}}","{{$start}}","{{$end}}","Work On Sunday")'>{{ $value }}</a>

                                        @elseif ($key=="Work_On_Public")
                                          <a onclick='viewdata2("{{$leave->Id}}","{{$leave->Working_Days}}","{{$start}}","{{$end}}","Work On Public")'>{{ $value }}</a>

                                        @elseif ($key=="Forgot_Time_Out")
                                          <a onclick='viewdata2("{{$leave->Id}}","{{$leave->Working_Days}}","{{$start}}","{{$end}}","Forgot Time Out")'>{{ $value }}</a>

                                        @elseif ($key=="On_Leave_Time_In")
                                          <a onclick='viewdata2("{{$leave->Id}}","{{$leave->Working_Days}}","{{$start}}","{{$end}}","On Leave Time In")'>{{ $value }}</a>


                                        @else
                                          {{ $value }}
                                        @endif


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

              <div class="tab-pane" id="leavetaken">

                <div class="row">
                  <div class="col-md-12">

                    <table id="leavetakentable" class="leavetaken" cellspacing="0" width="100%" padding="30px" style="font-size: 13px;">
                          <thead>

                            @if($leavetaken)
                              <tr class="search">

                                @foreach($leavetaken as $key=>$value)

                                  @if ($key==0)
                                    <?php $i = 0; ?>

                                    @foreach($value as $field=>$a)
                                        @if ($i==0)
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
                            @endif

                              <tr>
                                @foreach($leavetaken as $key=>$value)

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
                            @foreach($leavetaken as $leave)

                                <tr id="row_{{ $i }}">
                                      <td></td>

                                    @foreach($leave as $key=>$value)
                                      <td>
                                        @if($key=="Leave_Type")
                                          @if($value=="Annual Leave")
                                            ANNU
                                          @elseif($value=="Replacement Leave")
                                            RL
                                          @elseif($value=="Compassionate Leave")
                                            COMP
                                          @elseif($value=="Emergency Leave")
                                            EMERG
                                          @elseif($value=="Hospitalisation Leave")
                                            HOSP
                                          @elseif($value=="Maternity Leave")
                                            MATE
                                          @elseif($value=="Unpaid Leave")
                                            NPL
                                          @elseif($value=="Paternity Leave")
                                            PATE
                                          @elseif($value=="Medical Leave")
                                            SICK
                                          @elseif($value=="Marriage Leave")
                                            MARR
                                          @elseif($value=="1 Hour Time Off")
                                            1H
                                          @elseif($value=="2 Hours Time Off")
                                            2H
                                          @endif
                                        @else
                                          {{$value}}
                                        @endif
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



              <div class="tab-pane" id="leavebalance">

                <div class="row">
                  <div class="col-md-12">

                    <table id="leavebalancetable" class="leavebalance" cellspacing="0" width="100%" padding="30px" style="font-size: 13px;">
                          <thead>

                            @if($leavebalance)
                              <tr class="search">

                                @foreach($leavebalance as $key=>$value)

                                  @if ($key==0)
                                    <?php $i = 0; ?>

                                    @foreach($value as $field=>$a)
                                        @if ($i==0|| $i==1)
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
                            @endif
                              {{-- prepare header search textbox --}}
                              <tr>
                                @foreach($leavebalance as $key=>$value)

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
                            @foreach($leavebalance as $balance)

                              <?php $currententitlement=0;$burnt=0; ?>

                                <tr id="row_{{ $i }}">
                                    <td></td>

                                    @foreach($balance as $key=>$value)
                                      <td>

                                      @if($key=="Name")
                                        <a href="{{ URL::to('/')}}/individualleavesummary/{{$balance->Id}}" alt="Edit" title="Edit" target="_blank">{{$value}}</a>
                                      @elseif($key == 'Burnt')
                                        @if($balance->Carried_Forward > ($balance->Leave_Taken_Before_April +  $balance->Leave_Taken_In_Between_March) && date('n') > 3)
                                          <?php $burnt = 0;
                                          ?>
                                        @else
                                          <?php $burnt = 0; ?>
                                        @endif
                                        {{ (float) $burnt }}
                                      @elseif($key=="Replacement_Earned")
                                        {{ (float) $value }}
                                      @elseif($key=="Adjusted")
                                        {{ (float) $value }}
                                      @elseif($key=="Carried_Forward")
                                        {{ (float) ($value - $burnt) }}
                                      @elseif($key=="Total_Leave_Days")
                                        {{ (float) $balance->Replacement_Earned + $balance->Current_Entitlement+$balance->Carried_Forward+$balance->Adjusted-$burnt}}
                                      @elseif($key=="Total_Leave_Taken")
                                        {{ (float) $value }}
                                      @elseif($key=="Replacement_Balance")
                                        @if (($balance->Current_Entitlement+$balance->Adjusted+($balance->Carried_Forward-$burnt)-($balance->Total_Leave_Taken-$balance->Replacement_Taken)) < 0)
                                        {{ (float) $balance->Replacement_Earned-$balance->Replacement_Taken+$balance->Current_Entitlement+$balance->Adjusted+($balance->Carried_Forward-$burnt)-($balance->Total_Leave_Taken-$balance->Replacement_Taken) }}
                                        @else
                                        {{ (float) $balance->Replacement_Earned-$balance->Replacement_Taken}}
                                        @endif
                                      @elseif($key=="AL_Balance")
                                        @if (($balance->Current_Entitlement+$balance->Adjusted+($balance->Carried_Forward-$burnt)-($balance->Total_Leave_Taken-$balance->Replacement_Taken)) < 0)
                                        {{ ((float) $balance->Current_Entitlement+$balance->Adjusted+($balance->Carried_Forward-$burnt)-($balance->Total_Leave_Taken-$balance->Replacement_Taken)) + ((float) $balance->Replacement_Earned-$balance->Replacement_Taken) - ((float) $balance->Replacement_Earned-$balance->Replacement_Taken+$balance->Current_Entitlement+$balance->Adjusted+($balance->Carried_Forward-$burnt)-($balance->Total_Leave_Taken-$balance->Replacement_Taken))}}
                                        @else
                                        {{ (float) $balance->Current_Entitlement+$balance->Adjusted+($balance->Carried_Forward-$burnt)-($balance->Total_Leave_Taken-$balance->Replacement_Taken)}}
                                        @endif
                                      @elseif($key=="Total_Leave_Balance")
                                        {{ (float) $balance->Replacement_Earned+$balance->Current_Entitlement+$balance->Adjusted+($balance->Carried_Forward-$burnt)-$balance->Total_Leave_Taken}}
                                      @elseif($key=="Non_Paid_Leave")
                                        @if(($balance->Current_Entitlement+$balance->Adjusted+($balance->Carried_Forward-$burnt)-$balance->Total_Leave_Taken) < 0)
                                          {{ (float) (($balance->Current_Entitlement+$balance->Adjusted+($balance->Carried_Forward-$burnt)-$balance->Total_Leave_Taken) * -1) + $balance->{"Unpaid Leave"} }}
                                        @endif
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


              </div>

              <div class="tab-pane" id="sickbalance">

                <div class="row">
                  <div class="col-md-12">

                    <table id="sickbalancetable" class="sickbalance" cellspacing="0" width="100%" padding="30px" style="font-size: 13px;">
                          <thead>

                            @if($sickbalance)
                              <tr class="search">

                                @foreach($sickbalance as $key=>$value)

                                  @if ($key==0)
                                    <?php $i = 0; ?>

                                    @foreach($value as $field=>$a)
                                        @if ($i==0|| $i==1)
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
                            @endif
                              {{-- prepare header search textbox --}}
                              <tr>
                                @foreach($sickbalance as $key=>$value)

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
                            @foreach($sickbalance as $balance)

                              <?php $currentsickentitlement=0; ?>

                                <tr id="row_{{ $i }}">
                                    <td></td>

                                    @foreach($balance as $key=>$value)
                                      <td>
                                        @if($key=="Name")
                                          <a href="{{ URL::to('/')}}/individualleavesummary/{{$balance->Id}}" alt="Edit" title="Edit" target="_blank">{{$value}}</a>

                                        @elseif($key=="Yearly_Entitlement")
                                          @foreach ($sickentitlement as $entitlement)

                                            @if($balance->Grade==$entitlement->Grade)
                                              @if($balance->Years_of_Service>=$entitlement->Year)
                                                {{$entitlement->Days}}
                                                <?php $currentsickentitlement=$entitlement->Days; ?>
                                                <?php break; ?>
                                              @endif
                                            @endif

                                          @endforeach
                                        @elseif($key=="Adjusted")
                                          {{ (float) $value }}
                                        @elseif($key=="Total_Leave_Taken")
                                          {{ $value }}
                                        @elseif($key=="Total_Leave_Balance")
                                          {{ $currentsickentitlement+$balance->Adjusted-$balance->Total_Leave_Taken }}
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

    function viewdata(leavetype,userid,start,end)
    {
      // console.log(end)

       $('#NameList').modal('show');
       $("#list").html("");

       $.ajaxSetup({
          headers: { 'X-CSRF-Token' : $('meta[name=_token]').attr('content') }
       });

       $("#ajaxloader3").show();

       $.ajax({
                   url: "{{ url('leavesummary/viewdata') }}",
                   method: "POST",
                   data: {
                     Leave_Type:leavetype,
                     UserId:userid,
                     Start:start,
                     End:end
                   },
                   success: function(response){
                     if (response==0)
                     {

                       var message ="Failed to retrieve user details!";
                       $("#warning-alert ul").html(message);
                       $("#warning-alert").show();
                       $('#ReturnedModal').modal('hide')

                       $("#ajaxloader3").hide();
                     }
                     else {
                       $("#exist-alert").hide();

                       var myObject = JSON.parse(response);

                       var display='<table border="1" align="center" class="leavetable" cellspacing="0" width="100%" padding="30px" style="font-size: 13px;">';
                       display+='<tr class="tableheader"><td>Leave_Term</td><td>Start_Date</td><td>End_Date Date</td><td>No_of_Days</td><td>Reason</td><td>Leave_Status</td></tr>';

                       $.each(myObject, function(i,item){

                         display+="<tr>";
                         display+='<td>'+item.Leave_Term+'</td><td>'+item.Start_Date+'</td><td>'+item.End_Date+'</td><td>'+item.No_of_Days+'</td><td>'+item.Reason+'</td><td>'+item.Leave_Status+'</td>';
                         display+="</tr>";

                       });

                       $("#list").html(display);

                       $("#ajaxloader3").hide();
                     }
           }
       });

     }

     function viewdata2(userid,type,start,end,type)
     {
       // console.log(end)

        $('#NameList').modal('show');
        $("#list").html("");

        $.ajaxSetup({
           headers: { 'X-CSRF-Token' : $('meta[name=_token]').attr('content') }
        });

        $("#ajaxloader3").show();

        $.ajax({
                    url: "{{ url('leavesummary/viewdata2') }}",
                    method: "POST",
                    data: {
                      UserId:userid,
                      Start:start,
                      End:end,
                      Type:type
                    },
                    success: function(response){
                      if (response==0)
                      {

                        var message ="Failed to retrieve user details!";
                        $("#warning-alert ul").html(message);
                        $("#warning-alert").show();
                        $('#ReturnedModal').modal('hide')

                        $("#ajaxloader3").hide();
                      }
                      else {
                        $("#exist-alert").hide();

                        var myObject = JSON.parse(response);

                        if(type=="On Leave Time In")
                        {

                          var display='<table border="1" align="center" class="leavetable" cellspacing="0" width="100%" padding="30px" style="font-size: 13px;">';
                          display+='<tr class="tableheader"><td>Date</td><td>Day</td><td>Check In Type</td><td>Leave_Type</td><td>Start_Date</td><td>End_Date</td><td>Time In</td><td>Time Out</td><td>Remarks</td></tr>';

                          $.each(myObject, function(i,item){

                            display+="<tr>";
                            display+='<td>'+item.Date+'</td><td>'+item.Day+'</td><td>'+item.Check_In_Type+'</td><td>'+item.Leave_Type+'</td><td>'+item.Start_Date+'</td><td>'+item.End_Date+'</td><td>'+item.Time_In+'</td><td>'+item.Time_Out+'</td><td>'+item.Remarks+'</td>';
                            display+="</tr>";

                          });

                        }
                        else {

                          var display='<table border="1" align="center" class="leavetable" cellspacing="0" width="100%" padding="30px" style="font-size: 13px;">';
                          display+='<tr class="tableheader"><td>Date</td><td>Day</td><td>Check In Type</td><td>Time In</td><td>Time Out</td><td>Remarks</td></tr>';

                          $.each(myObject, function(i,item){

                            display+="<tr>";
                            display+='<td>'+item.Date+'</td><td>'+item.Day+'</td><td>'+item.Check_In_Type+'</td><td>'+item.Time_In+'</td><td>'+item.Time_Out+'</td><td>'+item.Remarks+'</td>';
                            display+="</tr>";

                          });

                        }

                        $("#list").html(display);

                        $("#ajaxloader3").hide();
                      }
            }
        });

      }

    $(function () {

      $('#range').daterangepicker({locale: {
        format: 'DD-MMM-YYYY'
      },startDate: '{{$start}}',
      endDate: '{{$end}}'});

      $('#range2').daterangepicker({locale: {
        format: 'DD-MMM-YYYY'
      },startDate: '{{$start}}',
      endDate: '{{$end}}'});

    });

    function refresh()
    {
      var d=$('#range').val();
      var arr = d.split(" - ");

      window.location.href ="{{ url("/leavesummary") }}/"+arr[0]+"/"+arr[1];

    }

    function refresh2()
    {
      var d=$('#range2').val();
      var arr = d.split(" - ");

      window.location.href ="{{ url("/leavesummary") }}/"+arr[0]+"/"+arr[1];

    }

</script>



@endsection
