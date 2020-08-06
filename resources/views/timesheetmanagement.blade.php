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
      {{-- <script type="text/javascript" language="javascript" src="{{ asset('/plugin/examples/resources/syntax/shCore.js') }}"></script>
      <script type="text/javascript" language="javascript" src="{{ asset('/plugin/examples/resources/demo.js') }}"></script>
      <script type="text/javascript" language="javascript" src="{{ asset('/plugin/examples/resources/editor-demo.js') }}"></script> --}}

      <script type="text/javascript" language="javascript" class="init">

      var timesheettable;
      var timesheet2table;
      var timesheet3table;
      var timesheet4table;

      var alltable;

          $(document).ready(function() {

                    alltable=$('#alltable').dataTable( {
                      columnDefs: [{ "visible": false, "targets": [1] },{"className": "dt-center", "targets": "_all"}],
                      responsive: false,
                      colReorder: false,
                      dom: "Bfrtip",
                      iDisplayLength:10,
                      sScrollX: "100%",
                      bAutoWidth: true,
                      sScrollY: "100%",
                      aaSorting:false,
                      // columns: [
                      //   {
                      //        sortable: false,
                      //        "render": ""
                      //   },
                      //   { data: "submitter.Id"},
                      //   { data: "Submitter" },
                      //   { data: "Approver"},
                      //   { data: "timesheetstatuses.Status"},
                      //
                      // ],
                      select: {
                              style:    'os',
                              selector: 'tr'
                      },
                      buttons: [

                      ],

          });

                        timesheettable=$('#pendingtable').dataTable( {
                          columnDefs: [{ "visible": false, "targets": [1] },{"className": "dt-center", "targets": "_all"}],
                          responsive: false,
                          colReorder: false,
                          dom: "Bfrtip",
                          iDisplayLength:10,
                          sScrollX: "100%",
                          bAutoWidth: true,
                          sScrollY: "100%",
                          aaSorting:false,
                          // columns: [
                          //   {
                          //        sortable: false,
                          //        "render": ""
                          //   },
                          //   { data: "submitter.Id"},
                          //   { data: "submitter" },
                          //   { data: "timesheetstatuses.Status"},
                          //
                          // ],
                          select: {
                                  style:    'os',
                                  selector: 'tr'
                          },
                          buttons: [

                          ],

              });

                  timesheet2table=$('#approvedtable').dataTable( {
                    columnDefs: [{ "visible": false, "targets": [1] },{"className": "dt-center", "targets": "_all"}],
                    responsive: false,
                    colReorder: false,
                    dom: "Bfrtip",
                    sScrollX: "100%",
                    bAutoWidth: true,
                    iDisplayLength:10,
                    sScrollY: "100%",
                    aaSorting:false,
                    // columns: [
                    //   {
                    //        sortable: false,
                    //        "render": ""
                    //   },
                    //   { data: "submitter.Id"},
                    //   { data: "submitter" },
                    //   { data: "timesheetstatuses.Status"},
                    //
                    // ],
                    select: {
                            style:    'os',
                            selector: 'tr'
                    },
                    buttons: [

                    ],

          });

          timesheet3table=$('#rejectedtable').dataTable( {
            columnDefs: [{ "visible": false, "targets": [1] },{"className": "dt-center", "targets": "_all"}],
            responsive: false,
            colReorder: false,
            dom: "Bfrtip",
            sScrollX: "100%",
            bAutoWidth: true,
            iDisplayLength:10,
            sScrollY: "100%",
            aaSorting:false,
            // columns: [
            //   {
            //        sortable: false,
            //        "render": ""
            //   },
            //   { data: "submitter.Id"},
            //   { data: "submitter" },
            //   { data: "timesheetstatuses.Status"},
            //
            // ],
            select: {
                    style:    'os',
                    selector: 'tr'
            },
            buttons: [

            ],

  });

    timesheet4table=$('#finalapprovedtable').dataTable( {
      columnDefs: [{ "visible": false, "targets": [1] },{"className": "dt-right", "targets": [5]},{"className": "dt-left", "targets": "_all"}],
      responsive: false,
      colReorder: false,
      dom: "Bfrtip",
      iDisplayLength:10,
      //sScrollX: "100%",
      bAutoWidth: true,
      //sScrollY: "100%",
      // columns: [
      //   {
      //        sortable: false,
      //        "render": function ( data, type, full, meta ) {
      //          var str='<a href="claim/'+full.claims.Id+'" >Review</a>';
      //          return str;
      //
      //
      //        }
      //   },
      //   { data: "claims.Id"},
      //   { data: "claims.claim_Name" },
      //   { data: "submitter.Name"},
      //   { data: "claims.Date"},
      //   { data: "claims.Remarks" },
      //   { data: "approver.Name"},
      //   { data: "claimitemstatuses.Status" },
      //   { data: "claimitemstatuses.created_at" },
      //   { data: "claimitemstatuses.updated_at" },
      //
      // ],
      select: {
              style:    'os',
              selector: 'tr'
      },
      buttons: [

      ],

    });

          $(".alltable thead input").keyup ( function () {

                  /* Filter on the column (the index) of this element */
                  if ($('#alltable').length > 0)
                  {

                      var colnum=document.getElementById('alltable').rows[0].cells.length;

                      if (this.value=="[empty]")
                      {

                         alltable.fnFilter( '^$', this.name,true,false );
                      }
                      else if (this.value=="[nonempty]")
                      {

                         alltable.fnFilter( '^(?=\\s*\\S).*$', this.name,true,false );
                      }
                      else if (this.value.startsWith("!")==true && this.value.length>1)
                      {

                         alltable.fnFilter( '^(?'+ this.value +').*', this.name,true,false );
                      }
                      else if (this.value.startsWith("!")==false)
                      {

                          alltable.fnFilter( this.value, this.name,true,false );
                      }
                  }



          } );

          $(".pendingtable thead input").keyup ( function () {

                  /* Filter on the column (the index) of this element */
                  if ($('#pendingtable').length > 0)
                  {

                      var colnum=document.getElementById('pendingtable').rows[0].cells.length;

                      if (this.value=="[empty]")
                      {

                         timesheettable.fnFilter( '^$', this.name,true,false );
                      }
                      else if (this.value=="[nonempty]")
                      {

                         timesheettable.fnFilter( '^(?=\\s*\\S).*$', this.name,true,false );
                      }
                      else if (this.value.startsWith("!")==true && this.value.length>1)
                      {

                         timesheettable.fnFilter( '^(?'+ this.value +').*', this.name,true,false );
                      }
                      else if (this.value.startsWith("!")==false)
                      {

                          timesheettable.fnFilter( this.value, this.name,true,false );
                      }
                  }



          } );

          $(".approvedtable thead input").keyup ( function () {

                  /* Filter on the column (the index) of this element */
                  if ($('#approvedtable').length > 0)
                  {

                      var colnum=document.getElementById('approvedtable').rows[0].cells.length;

                      if (this.value=="[empty]")
                      {

                         timesheet2table.fnFilter( '^$', this.name,true,false );
                      }
                      else if (this.value=="[nonempty]")
                      {

                         timesheet2table.fnFilter( '^(?=\\s*\\S).*$', this.name,true,false );
                      }
                      else if (this.value.startsWith("!")==true && this.value.length>1)
                      {

                         timesheet2table.fnFilter( '^(?'+ this.value +').*', this.name,true,false );
                      }
                      else if (this.value.startsWith("!")==false)
                      {

                          timesheet2table.fnFilter( this.value, this.name,true,false );
                      }
                  }



          } );

          $(".rejectedtable thead input").keyup ( function () {

                  /* Filter on the column (the index) of this element */
                  if ($('#rejectedtable').length > 0)
                  {

                      var colnum=document.getElementById('rejectedtable').rows[0].cells.length;

                      if (this.value=="[empty]")
                      {

                         timesheet3table.fnFilter( '^$', this.name,true,false );
                      }
                      else if (this.value=="[nonempty]")
                      {

                         timesheet3table.fnFilter( '^(?=\\s*\\S).*$', this.name,true,false );
                      }
                      else if (this.value.startsWith("!")==true && this.value.length>1)
                      {

                         timesheet3table.fnFilter( '^(?'+ this.value +').*', this.name,true,false );
                      }
                      else if (this.value.startsWith("!")==false)
                      {

                          timesheet3table.fnFilter( this.value, this.name,true,false );
                      }
                  }



          } );


          $(".finalapprovedtable thead input").keyup ( function () {

                  /* Filter on the column (the index) of this element */
                  if ($('#finalapprovedtable').length > 0)
                  {

                      var colnum=document.getElementById('finalapprovedtable').rows[0].cells.length;

                      if (this.value=="[empty]")
                      {

                         timesheet4table.fnFilter( '^$', this.name,true,false );
                      }
                      else if (this.value=="[nonempty]")
                      {

                         timesheet4table.fnFilter( '^(?=\\s*\\S).*$', this.name,true,false );
                      }
                      else if (this.value.startsWith("!")==true && this.value.length>1)
                      {

                         timesheet4table.fnFilter( '^(?'+ this.value +').*', this.name,true,false );
                      }
                      else if (this.value.startsWith("!")==false)
                      {

                          timesheet4table.fnFilter( this.value, this.name,true,false );
                      }
                  }



          } );

          $('#alltimesheettab').html("Date Range Timesheet" + "[" + alltable.api().rows().count() +"]")
          $('#pendingreviewtab').html("Pending Review Timesheet" + "[" + timesheettable.api().rows().count() +"]")
          $('#approvedtimesheettab').html("Approved Timesheet" + "[" + timesheet2table.api().rows().count() +"]")
          $('#rejectedtimesheettab').html("Rejected Timesheet" + "[" + timesheet3table.api().rows().count() +"]")
          $('#finalapprovedtimesheettab').html("Final Approved Timesheet" + "[" + timesheet4table.api().rows().count() +"]")

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
        Timesheet Management
        <small>Resource Management</small>
      </h1>
      <ol class="breadcrumb">
        <li><a href="{{ url('/home') }}"><i class="fa fa-dashboard"></i> Home</a></li>
        <li><a href="#">Resource Management</a></li>
        <li><a href="#">Timesheet</a></li>
        <li class="active">Timesheet Management</li>
      </ol>
    </section>

    <!-- Main content -->
    <section class="content">
      <div class="row">

        {{-- <div class="col-md-4">
            <div class="box box-solid">
                <div class="box-header with-border">
                    <div id="calendar"></div>
                </div>
            </div>
        </div> --}}

        <div class="col-md-12">

          <div class="nav-tabs-custom">
            <ul class="nav nav-tabs">

              <li class="active"><a href="#pendingreview" data-toggle="tab" id="pendingreviewtab">Pending Review Timesheet</a></li>
              <li><a href="#approvedtimesheet" data-toggle="tab" id="approvedtimesheettab">Approved Timesheet</a></li>
              <li><a href="#rejectedtimesheet" data-toggle="tab" id="rejectedtimesheettab">Rejected Timesheet</a></li>
              @if($me->View_All_Timesheet)
                <li><a href="#alltimesheet" data-toggle="tab" id="alltimesheettab">Date Range Timesheet</a></li>
                <li><a href="#finalapprovedtimesheet" data-toggle="tab" id="finalapprovedtimesheettab">All Final Approved Timesheet</a></li>
              @endif

            </ul>

            <div class="tab-content">

              <div class="tab-pane" id="alltimesheet">

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
              </div>

                <table id="alltable" class="alltable" cellspacing="0" width="100%" padding="30px" style="font-size: 13px;">
                    <thead>
                        {{-- prepare header search textbox --}}
                        @if($alltimesheets)
                          <tr class="search">

                            @foreach($alltimesheets as $key=>$value)

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
                        <tr>
                          @foreach($alltimesheets as $key=>$value)

                            @if ($key==0)
                              <td>Action</td>
                              @foreach($value as $field=>$value)
                                  <td/>{{ $field }}</td>
                              @endforeach

                            @endif

                          @endforeach
                        </tr>
                    </thead>
                    <tbody>

                      <?php $i = 0; ?>
                      @foreach($alltimesheets as $timesheet)

                        <tr id="row_{{ $i }}">
                            <td><a href="{{ url('/timesheet') }}/{{$timesheet->Id}}/true/{{$start}}/{{$end}}">Review</a></td>
                            @foreach($timesheet as $key=>$value)
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

              <div class="active tab-pane" id="pendingreview">
                <table id="pendingtable" class="pendingtable" cellspacing="0" width="100%" padding="30px" style="font-size: 13px;">
                    <thead>
                        {{-- prepare header search textbox --}}
                        @if($timesheets)
                          <tr class="search">

                            @foreach($timesheets as $key=>$value)

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
                        <tr>
                          @foreach($timesheets as $key=>$value)

                            @if ($key==0)
                              <td>Action</td>
                              @foreach($value as $field=>$value)
                                  <td/>{{ $field }}</td>
                              @endforeach

                            @endif

                          @endforeach
                        </tr>
                    </thead>
                    <tbody>

                      <?php $i = 0; ?>
                      @foreach($timesheets as $timesheet)

                        @if(strpos($timesheet->Status,"Pending")!==false)

                        <tr id="row_{{ $i }}">
                            <td><a href="{{ url('/timesheet') }}/{{$timesheet->Id}}/false/{{$start}}/{{$end}}">Review</a></td>
                            @foreach($timesheet as $key=>$value)
                              <td>
                                {{ $value }}
                              </td>
                            @endforeach
                        </tr>
                        <?php $i++; ?>

                        @endif
                      @endforeach

                  </tbody>
                    <tfoot></tfoot>
                </table>
              </div>

              <div class="tab-pane" id="approvedtimesheet">
                <table id="approvedtable" class="approvedtable" cellspacing="0" width="100%" padding="30px" style="font-size: 13px;">
                    <thead>
                        {{-- prepare header search textbox --}}
                        @if($timesheets)
                          <tr class="search">

                            @foreach($timesheets as $key=>$value)

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
                        <tr>
                          @foreach($timesheets as $key=>$value)

                            @if ($key==0)
                              <td>Action</td>
                              @foreach($value as $field=>$value)
                                  <td/>{{ $field }}</td>
                              @endforeach

                            @endif

                          @endforeach
                        </tr>
                    </thead>
                    <tbody>

                      <?php $i = 0; ?>
                      @foreach($timesheets as $timesheet)

                        @if(strpos($timesheet->Status,"Approved")!==false)

                          <tr id="row_{{ $i }}">
                            <td><a href="{{ url('/timesheet') }}/{{$timesheet->Id}}/false/{{$start}}/{{$end}}">Review</a></td>
                              @foreach($timesheet as $key=>$value)
                                <td>
                                  {{ $value }}
                                </td>
                              @endforeach
                          </tr>
                          <?php $i++; ?>

                        @endif

                      @endforeach

                  </tbody>
                    <tfoot></tfoot>
                </table>

              </div>

              <div class="tab-pane" id="finalapprovedtimesheet">

                <div class="row">
                    <br>

                    <div class="col-md-6">
                    <div class="input-group">
                      <div class="input-group-addon">
                        <i class="fa fa-clock-o"></i>
                      </div>
                      <input type="text" class="form-control" id="range2" name="range2">

                    </div>
                  </div>

                  <div class="col-md-6">
                      <div class="input-group">
                        <button type="button" class="btn btn-success btn" data-toggle="modal" onclick="refresh2();">Refresh</button>
                      </div>
                  </div>
              </div>

                <table id="finalapprovedtable" class="finalapprovedtable" cellspacing="0" width="100%" padding="30px" style="font-size: 13px;">
                    <thead>
                        {{-- prepare header search textbox --}}
                        @if($allfinaltimesheets)
                          <tr class="search">

                            @foreach($allfinaltimesheets as $key=>$value)

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
                        <tr>
                          @foreach($allfinaltimesheets as $key=>$value)

                            @if ($key==0)
                              <td>Action</td>
                              @foreach($value as $field=>$value)
                                  <td/>{{ $field }}</td>
                              @endforeach

                            @endif

                          @endforeach
                        </tr>
                    </thead>
                    <tbody>

                      <?php $i = 0; ?>
                      @foreach($allfinaltimesheets as $timesheet)

                          <tr id="row_{{ $i }}">
                            <td><a href="{{ url('/timesheet') }}/{{$timesheet->Id}}/true/{{$start}}/{{$end}}">Review</a></td>
                              @foreach($timesheet as $key=>$value)
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

              <div class="tab-pane" id="rejectedtimesheet">
                <table id="rejectedtable" class="rejectedtable" cellspacing="0" width="100%" padding="30px" style="font-size: 13px;">
                    <thead>
                        {{-- prepare header search textbox --}}
                        @if($timesheets)
                          <tr class="search">

                            @foreach($timesheets as $key=>$value)

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
                        <tr>
                          @foreach($timesheets as $key=>$value)

                            @if ($key==0)
                              <td>Action</td>
                              @foreach($value as $field=>$value)
                                  <td/>{{ $field }}</td>
                              @endforeach

                            @endif

                          @endforeach
                        </tr>
                    </thead>
                    <tbody>

                      <?php $i = 0; ?>
                      @foreach($timesheets as $timesheet)

                        @if(strpos($timesheet->Status,"Rejected")!==false)

                          <tr id="row_{{ $i }}">
                              <td><a href="{{ url('/timesheet') }}/{{$timesheet->Id}}/false/{{$start}}/{{$end}}">Review</a></td>
                              @foreach($timesheet as $key=>$value)
                                <td>
                                  {{ $value }}
                                </td>
                              @endforeach
                          </tr>
                          <?php $i++; ?>

                        @endif

                      @endforeach

                  </tbody>
                    <tfoot></tfoot>
                </table>
              </div>

          </div>
          <!-- /.nav tab content -->
        </div>
        <!-- /.av-tabs-custom -->

      </div>
      <!-- /.row -->
    </section>
    <!-- /.content -->
  </div>
  <!-- /.content-wrapper -->
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


        $('#range2').daterangepicker({locale: {
          format: 'DD-MMM-YYYY'
        },startDate: '{{$start}}',
        endDate: '{{$end}}'});

    /* initialize the calendar
     -----------------------------------------------------------------*/
    //Date for the calendar events (dummy data)
    var date = new Date();
    var d = date.getDate(),
        m = date.getMonth(),
        y = date.getFullYear();
    $('#calendar').fullCalendar({
      header: {
        left: 'prev,next today',
        center: 'title',
        right: 'month'
      },
      buttonText: {
        today: 'Today',
        month: 'Month'
      },
      //Random default events
      events: [

        @foreach($showleave as $leave)

            @if(strpos($leave->Status,"Approved")!==false || $leave->Status="Pending Approval")
            {
              title: "{{ $leave->Name }} - {{ $leave->Leave_Term }}",
              start: new Date("{{$leave->Start_Date}}"),
              end: new Date("{{$leave->End_Date}}"),
              allDay: true,
              @if(strpos($leave->Status,"Approved")!==false)
                backgroundColor: "#00a65a", //green
                borderColor: "#00a65a" //green
              @else
                backgroundColor: "#f39c12", //yellow
                borderColor: "#f39c12" //yellow
              @endif

            },
            @endif

        @endforeach

        // {
        //   title: 'All Day Event',
        //   start: new Date(y, m, 1),
        //   backgroundColor: "#f56954", //red
        //   borderColor: "#f56954" //red
        // },
        // {
        //   title: 'Long Event',
        //   start: new Date(y, m, d - 5),
        //   end: new Date(y, m, d - 2),
        //   backgroundColor: "#f39c12", //yellow
        //   borderColor: "#f39c12" //yellow
        // },
        // {
        //   title: 'Meeting',
        //   start: new Date(y, m, d, 10, 30),
        //   allDay: false,
        //   backgroundColor: "#0073b7", //Blue
        //   borderColor: "#0073b7" //Blue
        // },
        // {
        //   title: 'Lunch',
        //   start: new Date(y, m, d, 12, 0),
        //   end: new Date(y, m, d, 14, 0),
        //   allDay: false,
        //   backgroundColor: "#00c0ef", //Info (aqua)
        //   borderColor: "#00c0ef" //Info (aqua)
        // },
        // {
        //   title: 'Birthday Party',
        //   start: new Date(y, m, d + 1, 19, 0),
        //   end: new Date(y, m, d + 1, 22, 30),
        //   allDay: false,
        //   backgroundColor: "#00a65a", //Success (green)
        //   borderColor: "#00a65a" //Success (green)
        // },
        // {
        //   title: 'Click for Google',
        //   start: new Date(y, m, 28),
        //   end: new Date(y, m, 29),
        //   url: 'http://google.com/',
        //   backgroundColor: "#3c8dbc", //Primary (light-blue)
        //   borderColor: "#3c8dbc" //Primary (light-blue)
        // },
      ],

      editable: false,
      droppable: false, // this allows things to be dropped onto the calendar !!!
    });
  });

  function refresh()
  {
    var d=$('#range').val();
    var arr = d.split(" - ");

    window.location.href ="{{ url("/timesheetmanagement") }}/"+arr[0]+"/"+arr[1];

  }

  function refresh2()
  {
    var d=$('#range2').val();
    var arr = d.split(" - ");

    window.location.href ="{{ url("/timesheetmanagement") }}/"+arr[0]+"/"+arr[1];

  }
</script>

@endsection
