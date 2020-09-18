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

      .weekend {
        color: red;
      }

      table.dataTable  {
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
      <script type="text/javascript" language="javascript" src="https://cdn.datatables.net/plug-ins/1.10.13/api/sum().js"></script>

      <script type="text/javascript" language="javascript" src="{{ asset('/plugin/js/dataTables.editor.min.js') }}"></script>
      {{-- <script type="text/javascript" language="javascript" src="{{ asset('/plugin/examples/resources/syntax/shCore.js') }}"></script>
      <script type="text/javascript" language="javascript" src="{{ asset('/plugin/examples/resources/demo.js') }}"></script>
      <script type="text/javascript" language="javascript" src="{{ asset('/plugin/examples/resources/editor-demo.js') }}"></script> --}}
      <script type="text/javascript" language="javascript" src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.4.0/Chart.bundle.min.js"></script>

       <script type="text/javascript" language="javascript" class="init">

          var all;
          var bytype;
          var byperson;
          var byperson2;

          $(document).ready(function() {

            all = $('#all').dataTable( {

                    dom: "Brt",
                    bAutoWidth: true,
                    aaSorting: false,
                    bPaginate:false,
                    columnDefs: [{ "visible": false, "targets": [] },{"className": "dt-left", "targets": 0},{"className": "dt-right", "targets": [1,2]}],
                    bScrollCollapse: true,
                    columns: [
                      {data:'Expenses_Type'},
                      {data:'Total_Expenses'},
                      {data:'Total_GST'}
                    ],
                    select: {
                            style:    'os',
                            selector: 'td:first-child'
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

                bytype =  $('#bytype').dataTable( {

                             dom: "fBrtip",
                             bAutoWidth: true,
                             rowId:"claims.Id",
                             columnDefs: [{ "visible": false, "targets": [1] },{"className": "dt-left", "targets": 2},{"className": "dt-right", "targets": "_all"}],
                             bScrollCollapse: true,
                             columns: [
                               { data: "","render":"", title:"No"},
                               { data: "claims.Id"},
                               { data: "claims.Expenses_Type"},
                                { data: "Total_Petrol_SmartPay"},
                                { data: "Total_Claim_With_SmartPay"},
                                { data: "Total_Claim_Without_SmartPay"},
                                { data: "Staff_Allowance"},
                                { data: "Staff_Monetary_Comp"},
                                { data: "Total_Claim_With_Allowance_Monetary",

                                   "render": function ( data, type, full, meta ) {

                                     if(full.Staff_Allowance=="")
                                     {
                                       full.Staff_Allowance="0.00";
                                     }

                                     if(full.Staff_Monetary_Comp=="")
                                     {
                                       full.Staff_Monetary_Comp="0.00";
                                     }

                                      return (parseFloat(full.Total_Claim_Without_SmartPay.replace(",",""))+parseFloat(full.Staff_Allowance.replace(",",""))+parseFloat(full.Staff_Monetary_Comp.replace(",",""))).toLocaleString("en");

                                   }
                                },
                                { data: "Total_Advance"},
                                { data: "Total_Summon"},
                                { data: "Total_Payable",

                                   "render": function ( data, type, full, meta ) {

                                       return (parseFloat(full.Total_Claim_Without_SmartPay.replace(",",""))+parseFloat(full.Staff_Allowance.replace(",",""))+parseFloat(full.Staff_Monetary_Comp.replace(",",""))-parseFloat(full.Total_Advance.replace(",",""))-parseFloat(full.Total_Summon.replace(",",""))).toLocaleString("en");

                                   }
                                }
                             ],
                             select: {
                                     style:    'os',
                                     selector: 'td:first-child'
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

                    byperson = $('#byperson').dataTable( {

                                 dom: "fBrtip",
                                 bAutoWidth: true,
                                 rowId:"Userid",
                                 //aaSorting:false,

                                 columnDefs: [{ "visible": false, "targets": [1] },{"className": "dt-left", "targets": [2,3]},{"className": "dt-right", "targets": "_all"}],
                                 bScrollCollapse: true,
                                 sScrollX: "100%",
                                 sScrollY: "100%",
                                 columns: [
                                   { data: "","render":"", title:"No"},
                                   { data: "users.Id"},
                                   { data: "users.StaffId"},
                                   { data: "users.Name",
                                     "render": function ( data, type, full, meta ) {

                                      return '<a href="{{ url('/') }}/userclaimbreakdown/'+full.users.Id+'/{{$start}}/{{$end}}" target="_blank">'+data+'</a>';
                                      },
                                      title: "Name"},
                                    @foreach($options as $option)
                                      { data: "{{$option->Option}}", name:"{{$option->Option}}"},
                                    @endforeach
                                    { data: "Total_Petrol_SmartPay"},
                                    { data: "Total_Claim_With_SmartPay"},
                                    { data: "Total_Claim_Without_SmartPay"},
                                    { data: "Staff_Allowance"},
                                    { data: "Staff_Monetary_Comp"},
                                    { data: "Total_Claim_With_Allowance_Monetary",

                                       "render": function ( data, type, full, meta ) {
                                          if(full.Staff_Allowance=="")
                                          {
                                            full.Staff_Allowance="0.00";
                                          }

                                          if(full.Staff_Monetary_Comp=="")
                                          {
                                            full.Staff_Monetary_Comp="0.00";
                                          }
                                           return (parseFloat(full.Total_Claim_Without_SmartPay.replace(",",""))+parseFloat(full.Staff_Allowance.replace(",",""))+parseFloat(full.Staff_Monetary_Comp.replace(",",""))).toLocaleString("en");

                                       }
                                    },
                                    { data: "Total_Advance"},
                                    { data: "Total_Summon"},
                                    { data: "Total_Payable",

                                       "render": function ( data, type, full, meta ) {
                                         if(full.Staff_Allowance=="")
                                         {
                                           full.Staff_Allowance="0.00";
                                         }

                                         if(full.Staff_Monetary_Comp=="")
                                         {
                                           full.Staff_Monetary_Comp="0.00";
                                         }
                                           return (parseFloat(full.Total_Claim_Without_SmartPay.replace(",",""))+parseFloat(full.Staff_Allowance.replace(",",""))+parseFloat(full.Staff_Monetary_Comp.replace(",",""))-parseFloat(full.Total_Advance.replace(",",""))-parseFloat(full.Total_Summon.replace(",",""))).toLocaleString("en");

                                       }
                                    }
                                 ],
                                 select: {
                                         style:    'os',
                                         selector: 'td:first-child'
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

                    byperson2 = $('#byperson2').dataTable( {

                             dom: "fBrtip",
                             bAutoWidth: true,
                             rowId:"Userid",
                             //aaSorting:false,
                             columnDefs: [{ "visible": false, "targets": [1] },{"className": "dt-left", "targets": [2,3]},{"className": "dt-right", "targets": "_all"}],
                             bScrollCollapse: true,
                             sScrollX: "100%",
                             sScrollY: "100%",
                             columns: [
                               { data: "","render":"", title:"No"},
                               { data: "users.Id"},
                               { data: "users.StaffId"},
                               { data: "users.Name",
                                 "render": function ( data, type, full, meta ) {

                                  return '<a href="{{ url('/') }}/userclaimbreakdown/'+full.users.Id+'/{{$start}}/{{$end}}" target="_blank">'+data+'</a>';
                                  },
                                  title: "Name"},
                                  { data: "Total_Claim_With_SmartPay"},
                                  { data: "Total_Claim_Without_SmartPay"},
                                  { data: "Staff_Allowance"},
                                  { data: "Staff_Monetary_Comp"},
                                  { data: "Total_Claim_With_Allowance_Monetary",

                                     "render": function ( data, type, full, meta ) {
                                       if(full.Staff_Allowance=="")
                                       {
                                         full.Staff_Allowance="0.00";
                                       }

                                       if(full.Staff_Monetary_Comp=="")
                                       {
                                         full.Staff_Monetary_Comp="0.00";
                                       }
                                         return (parseFloat(full.Total_Claim_Without_SmartPay.replace(",",""))+parseFloat(full.Staff_Allowance.replace(",",""))+parseFloat(full.Staff_Monetary_Comp.replace(",",""))).toLocaleString("en");

                                     }
                                  },
                                  { data: "Total_Advance"},
                                  { data: "Total_Summon"},
                                  { data: "Total_Payable",

                                     "render": function ( data, type, full, meta ) {
                                       if(full.Staff_Allowance=="")
                                       {
                                         full.Staff_Allowance="0.00";
                                       }

                                       if(full.Staff_Monetary_Comp=="")
                                       {
                                         full.Staff_Monetary_Comp="0.00";
                                       }
                                         return (parseFloat(full.Total_Claim_Without_SmartPay.replace(",",""))+parseFloat(full.Staff_Allowance.replace(",",""))+parseFloat(full.Staff_Monetary_Comp.replace(",",""))-parseFloat(full.Total_Advance.replace(",",""))-parseFloat(full.Total_Summon.replace(",",""))).toLocaleString("en");

                                     }
                                  }
                             ],
                             select: {
                                     style:    'os',
                                     selector: 'td:first-child'
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

                     bytype.api().on( 'order.dt search.dt', function () {
                         bytype.api().column(0, {search:'applied', order:'applied'}).nodes().each( function (cell, i) {
                             cell.innerHTML = i+1;
                         } );
                     } ).draw();

                     byperson.api().on( 'order.dt search.dt', function () {
                         byperson.api().column(0, {search:'applied', order:'applied'}).nodes().each( function (cell, i) {
                             cell.innerHTML = i+1;
                         } );
                     } ).draw();

                     byperson2.api().on( 'order.dt search.dt', function () {
                         byperson2.api().column(0, {search:'applied', order:'applied'}).nodes().each( function (cell, i) {
                             cell.innerHTML = i+1;
                         } );
                     } ).draw();



                     $(".bytype thead input").keyup ( function () {

                             /* Filter on the column (the index) of this element */
                             if ($('#bytype').length > 0)
                             {

                                 var colnum=document.getElementById('bytype').rows[0].cells.length;

                                 if (this.value=="[empty]")
                                 {

                                    bytype.fnFilter( '^$', this.name,true,false );
                                 }
                                 else if (this.value=="[nonempty]")
                                 {

                                    bytype.fnFilter( '^(?=\\s*\\S).*$', this.name,true,false );
                                 }
                                 else if (this.value.startsWith("!")==true && this.value.length>1)
                                 {

                                    bytype.fnFilter( '^(?'+ this.value +').*', this.name,true,false );
                                 }
                                 else if (this.value.startsWith("!")==false)
                                 {

                                     bytype.fnFilter( this.value, this.name,true,false );
                                 }
                             }



                     } );

                     $(".byperson thead input").keyup ( function () {

                             /* Filter on the column (the index) of this element */
                             if ($('#byperson').length > 0)
                             {

                                 var colnum=document.getElementById('byperson').rows[0].cells.length;

                                 if (this.value=="[empty]")
                                 {

                                    byperson.fnFilter( '^$', this.name,true,false );
                                 }
                                 else if (this.value=="[nonempty]")
                                 {

                                    byperson.fnFilter( '^(?=\\s*\\S).*$', this.name,true,false );
                                 }
                                 else if (this.value.startsWith("!")==true && this.value.length>1)
                                 {

                                    byperson.fnFilter( '^(?'+ this.value +').*', this.name,true,false );
                                 }
                                 else if (this.value.startsWith("!")==false)
                                 {

                                     byperson.fnFilter( this.value, this.name,true,false );
                                 }
                             }



                     } );

                     $(".byperson2 thead input").keyup ( function () {

                             /* Filter on the column (the index) of this element */
                             if ($('#byperson2').length > 0)
                             {

                                 var colnum=document.getElementById('byperson2').rows[0].cells.length;

                                 if (this.value=="[empty]")
                                 {

                                    byperson2.fnFilter( '^$', this.name,true,false );
                                 }
                                 else if (this.value=="[nonempty]")
                                 {

                                    byperson2.fnFilter( '^(?=\\s*\\S).*$', this.name,true,false );
                                 }
                                 else if (this.value.startsWith("!")==true && this.value.length>1)
                                 {

                                    byperson2.fnFilter( '^(?'+ this.value +').*', this.name,true,false );
                                 }
                                 else if (this.value.startsWith("!")==false)
                                 {

                                     byperson2.fnFilter( this.value, this.name,true,false );
                                 }
                             }



                     } );




              } );

      </script>
@endsection

@section('content')

    <div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
      <h1>
        Claim Summary
        <small>Finance Management</small>
      </h1>
      <ol class="breadcrumb">
        <li><a href="{{ url('/home') }}"><i class="fa fa-dashboard"></i> Home</a></li>
        <li><a href="#">Finance Management</a></li>
        <li class="active">Claim Summary</li>
      </ol>
    </section>

    <!-- Main content -->
    <section class="content">

      <div class="modal fade" id="chartpopup" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title" id="myModalLabel">Expenses Type</h4>
                </div>
                <div class="modal-body" name="chart" id="chart">

                            <div class="box box-primary">
                                <div class="box-header with-border">

                                </div>
                                <div class="box-body">
                                    <canvas id="pieChartpopup"  ></canvas>
                                </div>
                            </div>

                </div>
                <div class="modal-footer">
                <center><img src="{{ URL::to('/') ."/img/ajax-loader.gif" }}" width="50px" height="50px" alt="Loading" name='ajaxloader3' id="ajaxloader3"></center>
                <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
      </div>

      <div class="row">

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

      <div class="row">
        <div class="col-md-6">
          <div class="box box-primary">
            <div class="box-header with-border">
              <h3 class="box-title">Expenses Type</h3>
            </div>
            <div class="box-body">
              <canvas id="pieChart"  ></canvas>
            </div>
          </div>
        </div>

        <div class="col-md-6">
          <div class="box box-primary">
            <div class="box-header with-border">
              <h3 class="box-title">Claim Summary</h3>
            </div>
            <div class="box-body">

              <table id="all" class="display" cellspacing="0" width="100%" padding="30px" style="font-size: 13px;">
                  <thead>
                      {{-- prepare header search textbox --}}
                      <tr>

                        @foreach($summary as $key=>$value)

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
                    @foreach($summary as $type)

                          <tr id="row_{{ $i }}" >

                              @foreach($type as $key=>$value)
                                <td>
                                  @if(is_numeric($value))
                                    RM {{ number_format($value,2,".",",") }}
                                  @else
                                    {{ $value }}
                                  @endif
                                </td>
                              @endforeach
                          </tr>
                          <?php $i++; ?>

                    @endforeach

                    @foreach($total as $type)

                          <tr id="row_{{ $i }}" style="background-color:yellow">

                              @foreach($type as $key=>$value)
                                <td>
                                  @if(is_numeric($value))
                                    RM {{ number_format($value,2,".",",") }}
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

      </div>

       <div class="col-md-12">
         <div class="nav-tabs-custom">
            <ul class="nav nav-tabs">
              <li class="active"><a href="#person" data-toggle="tab" id="persontab">Staff Claim Breakdown</a></li>
              <li><a href="#person2" data-toggle="tab" id="person2tab">Staff Claim Total</a></li>
            </ul>

            <div class="tab-content">

              <div class="active tab-pane" id="person">

                  <table id="byperson" class="byperson" cellspacing="0" width="100%" padding="30px" style="font-size: 13px;">
                      <thead>
                        @if($byperson)
                        <tr class="search">

                          @foreach($byperson as $key=>$value)

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

                          @endif
                          <tr>

                            @foreach($byperson as $key=>$value)

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
                        @foreach($byperson as $person)

                              <tr id="row_{{ $i }}" >
                                  <td></td>

                                  @foreach($person as $key=>$value)
                                    <td>
                                      @if($key=="Id")
                                        {{ $value }}
                                      @elseif(is_numeric($value))
                                        {{ number_format($value,2,".",",") }}
                                      @elseif($value=="" || $value=="NaN")
                                        0.00
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
              <div class="tab-pane" id="person2">

                  <table id="byperson2" class="byperson2" cellspacing="0" width="100%" padding="30px" style="font-size: 13px;">
                      <thead>
                        @if($byperson2)
                        <tr class="search">

                          @foreach($byperson2 as $key=>$value)

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

                          @endif
                          <tr>

                            @foreach($byperson2 as $key=>$value)

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
                        @foreach($byperson2 as $person)

                              <tr id="row_{{ $i }}" >
                                  <td></td>
                                  @foreach($person as $key=>$value)
                                    <td>
                                      @if($key=="Id")
                                        {{ $value }}
                                      @elseif(is_numeric($value))
                                        {{ number_format($value,2,".",",") }}
                                      @elseif($value=="" || $value=="NaN")
                                        0.00
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

    });

    function refresh()
    {
      var d=$('#range').val();
      var arr = d.split(" - ");

      window.location.href ="{{ url("/claimsummary") }}/"+arr[0]+"/"+arr[1];

    }



  $(function () {
     //pie chart js

    var pie = document.getElementById("pieChart");
    var pieOptions = {
      events: false,
      animation: {
        duration: 500,
        easing: "easeOutQuart",
        onComplete: function () {
          var ctx = this.chart.ctx;
          ctx.font = Chart.helpers.fontString(Chart.defaults.global.defaultFontFamily, 'normal', Chart.defaults.global.defaultFontFamily);
          ctx.textAlign = 'center';
          ctx.textBaseline = 'bottom';

          this.data.datasets.forEach(function (dataset) {

            for (var i = 0; i < dataset.data.length; i++) {
              var model = dataset._meta[Object.keys(dataset._meta)[0]].data[i]._model,
                  total = dataset._meta[Object.keys(dataset._meta)[0]].total,
                  mid_radius = model.innerRadius + (model.outerRadius - model.innerRadius)/2,
                  start_angle = model.startAngle,
                  end_angle = model.endAngle,
                  mid_angle = start_angle + (end_angle - start_angle)/2;

              var x = mid_radius * Math.cos(mid_angle);
              var y = mid_radius * Math.sin(mid_angle);

              ctx.fillStyle = '#fff';
              if (i == 3){ // Darker text color for lighter background
                ctx.fillStyle = '#fff';
              }
              var percent = String(Math.round(dataset.data[i]/total*100)) + "%";
              ctx.fillText("RM " + dataset.data[i].toLocaleString("en"), model.x + x, model.y + y);
              // Display percent in another line, line break doesn't work for fillText
              ctx.fillText(percent, model.x + x, model.y + y + 15);
            }
          });
        }
      }
    };
  var str = "{{$title}}";
    str = str.split(",").map(function(str){
          return str; // add quotes
      })
      //alert(str);

    //console.log({{$data}});
  var a = [str];
    var piechart = new Chart(pie, {
      type: 'pie',
      data: {
        labels: a[0],
        datasets: [{
          backgroundColor: [
            "#2ecc71",
            "#3498db",
            "#95a5a6",
            "#9b59b6",
            "#f1c40f",
            "#e74c3c",
            "#34495e"
          ],
          data: [{{$data}}]
        }],
      },
      options: pieOptions
     });

  });

  function userclaimbreakdown(userid)
  {

      $('#chartpopup').modal('show');
      $("#pieChartpopup").html("");

      $.ajaxSetup({
         headers: { 'X-CSRF-Token' : $('meta[name=_token]').attr('content') }
      });

      $("#ajaxloader3").show();

       $.ajax({
                  url: "{{ url('userclaimbreakdown') }}"+userid+"/"+$start+"/"+$end,
                  method: "POST",
                  data: {
                    Id:userid
                  },
                  success: function(response){
                    if (response==0)
                    {
                      alert("NO");
                    }
                    else
                    {
                      $("#exist-alert").hide();

                      var myObject = JSON.parse(response);

                        //chart
                        var pie = document.getElementById("pieChartpopup");
                        var pieOptions = {
                            events: false,
                            animation: {
                                duration: 500,
                                easing: "easeOutQuart",
                                onComplete: function () {
                                var ctx = this.chart.ctx;
                                ctx.font = Chart.helpers.fontString(Chart.defaults.global.defaultFontFamily, 'normal', Chart.defaults.global.defaultFontFamily);
                                ctx.textAlign = 'center';
                                ctx.textBaseline = 'bottom';

                                this.data.datasets.forEach(function (dataset) {

                                    for (var i = 0; i < dataset.data.length; i++) {
                                    var model = dataset._meta[Object.keys(dataset._meta)[0]].data[i]._model,
                                        total = dataset._meta[Object.keys(dataset._meta)[0]].total,
                                        mid_radius = model.innerRadius + (model.outerRadius - model.innerRadius)/2,
                                        start_angle = model.startAngle,
                                        end_angle = model.endAngle,
                                        mid_angle = start_angle + (end_angle - start_angle)/2;

                                    var x = mid_radius * Math.cos(mid_angle);
                                    var y = mid_radius * Math.sin(mid_angle);

                                    ctx.fillStyle = '#fff';
                                    if (i == 3){ // Darker text color for lighter background
                                        ctx.fillStyle = '#fff';
                                    }
                                    var percent = String(Math.round(dataset.data[i]/total*100)) + "%";
                                    ctx.fillText(dataset.data[i], model.x + x, model.y + y);
                                    // Display percent in another line, line break doesn't work for fillText
                                    ctx.fillText(percent, model.x + x, model.y + y + 15);
                                    }
                                });
                                }
                            }
                        };

                        var piedata = [item.Expenses_Type];

                        var piechart = new Chart(pie, {
                            type: 'pie',
                            data: {
                                labels: ["a","b"],
                                datasets: [{
                                backgroundColor: [
                                    "#2ecc71",
                                    "#3498db",
                                    "#95a5a6",
                                    "#9b59b6",
                                    "#f1c40f",
                                    "#e74c3c",
                                    "#34495e"
                                ],
                                data: [1,2]
                                }],
                            },
                            options: pieOptions
                        });
                         // console.log(piechart);
                            $("#ajaxloader3").hide();
                        //end chart

                     $("#ajaxloader3").hide();

                    }
                   }
       });


  }

  </script>

@endsection
