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

      var claimtable;
      var claim2table;
      var claim3table;
      var claim4table;

      var alltable;

          $(document).ready(function() {


            var end = new Date();

            var start = new Date();
            var opt;
            start.setMonth(start.getMonth()-5,1);

            var months = [ "Jan", "Feb", "Mar", "Apr", "May", "Jun","Jul", "Aug", "Sep", "Oct", "Nov", "Dec" ];

            opt=opt+"<option value=''></option>";
            opt=opt+"<option value='Reset'>Reset</option>";

            while(start <= end){

              opt=opt+"<option value='"+months[start.getMonth()] +" "+start.getFullYear()+"'>"+months[start.getMonth()] +" "+start.getFullYear()+"</option>";
              if(start.getMonth()==11)
              {

                start.setMonth(0);
                start.setYear(start.getFullYear()+1);
              }
              else {
                start.setMonth(start.getMonth() + 1);
              }

            }

            alltable=$('#alltable').dataTable( {
                    columnDefs: [{ "visible": false, "targets": [1,2,3] },{"className": "dt-center", "targets": "_all"}],

                    responsive: false,
                    colReorder: false,
                    dom: "Bfrtip",
                    sScrollX: "100%",
                    bAutoWidth: true,
                    sScrollY: "100%",
                    bScrollCollapse: true,

                    select: {
                            style:    'os',
                            selector: 'tr'
                    },
                    buttons: [

                    ],

        });

                claimtable=$('#pendingtable').dataTable( {
                        columnDefs: [{ "visible": false, "targets": [1,2] },{"className": "dt-center", "targets": "_all"}],
                        responsive: false,
                        colReorder: false,
                        dom: "Bfrtip",
                        sScrollX: "100%",
                        bAutoWidth: true,
                        sScrollY: "100%",
                        bScrollCollapse: true,
                        columns:[
                          {
                               sortable: false,
                               "render": ""
                          },
                          {data:"claimsheets.Id", title:"Id"},
                          {data:"claimsheets.UserId", title:"UserId"},
                          {data:"submitter.Staff_ID", title:"Staff_ID"},
                          {data:"submitter.Name", title:"Name"},
                          {data:"claimsheets.Claim_Sheet_Name", title:"Claim_Sheet_Name"},
                          {data:"claimsheets.Remarks", title:"Remarks"},
                          {data:"claimstatuses.Status", title:"Status"},
                          {data:"Created_Date", title:"Created_Date"},
                        ],

                        select: {
                                style:    'os',
                                selector: 'tr'
                        },
                        buttons: [

                        ],

            });

                claim2table=$('#approvedtable').dataTable( {
                        columnDefs: [{ "visible": false, "targets": [1,2] },{"className": "dt-center", "targets": "_all"}],
                        responsive: false,
                        colReorder: false,
                        dom: "Bfrtip",
                        sScrollX: "100%",
                        bAutoWidth: true,
                        sScrollY: "100%",
                        bScrollCollapse: true,
                        columns: [
                          {
                               sortable: false,
                               "render": ""
                          },
                          {data:"claimsheets.Id", title:"Id"},
                          {data:"claimsheets.UserId", title:"UserId"},
                          {data:"submitter.Staff_ID", title:"Staff_ID"},
                          {data:"submitter.Name", title:"Name"},
                          {data:"claimsheets.Claim_Sheet_Name", title:"Claim_Sheet_Name"},
                          {data:"claimsheets.Remarks", title:"Remarks"},
                          {data:"claimstatuses.Status", title:"Status"},
                          {data:"Created_Date", title:"Created_Date"},

                        ],
                        select: {
                                style:    'os',
                                selector: 'tr'
                        },
                        buttons: [

                        ],

            });

                claim3table=$('#rejectedtable').dataTable( {
                        columnDefs: [{ "visible": false, "targets": [1,2] },{"className": "dt-center", "targets": "_all"}],
                        responsive: false,
                        colReorder: false,
                        dom: "Bfrtip",
                        iDisplayLength:10,
                        bAutoWidth: true,
                        sScrollX: "100%",
                        sScrollY: "100%",
                        bScrollCollapse: true,
                        columns: [
                          {
                               sortable: false,
                               "render": ""
                          },
                          {data:"claimsheets.Id", title:"Id"},
                          {data:"claimsheets.UserId", title:"UserId"},
                          {data:"submitter.Staff_ID", title:"Staff_ID"},
                          {data:"submitter.Name", title:"Name"},
                          {data:"claimsheets.Claim_Sheet_Name", title:"Claim_Sheet_Name"},
                          {data:"claimsheets.Remarks", title:"Remarks"},
                          {data:"claimstatuses.Status", title:"Status"},
                          {data:"Created_Date", title:"Created_Date"},

                        ],
                        select: {
                                style:    'os',
                                selector: 'tr'
                        },
                        buttons: [

                        ],

            });

                    claim4table=$('#finalapprovedtable').dataTable( {
                            ajax: {
                               "url": "{{ asset('/Include/claimfinal.php') }}"
                             },
                            columnDefs: [{ "visible": false, "targets": [1,2] },{"className": "dt-center", "targets": "_all"}],
                            responsive: false,
                            colReorder: false,
                            dom: "Bfrtip",
                            iDisplayLength:10,
                            bAutoWidth: true,
                            sScrollX: "100%",
                            sScrollY: "100%",
                            rowId:"claimsheets.Id",
                            bScrollCollapse: true,
                            fnInitComplete: function(oSettings, json) {

                              $('#allfinalclaimtab').html("Final Approved Claim" + "[" + claim4table.api().rows().count() +"]")

                             },
                            columns: [
                              {
                                   sortable: false,
                                   "render": function ( data, type, full, meta ) {

                                     var str='<a href="{{url('/claim/')}}/'+full.Id+'/'+full.UserId+'" target="_blank">Review</a> | ';

                                       @if($me->Update_Payment_Month)

                                          str=str+"<select id='checkerstatus"+full.Id+"' onchange='updateclaimsheet("+full.Id+")'>";
                                          str=str+opt;
                                          str=str+"</select>";

                                      @endif

                                     return str;
                                  }
                              },
                                    { data: "Id"},
                                    { data: "UserId"},
                                    {data:"Staff_ID", title:"Staff_ID"},
                                    { data: "Name"},
                                    { data: "Claim_Sheet_Name" },
                                    { data: "Remarks" },
                                    { data: "Approver"},
                                    { data: "Status"},
                                    { data: "Claim_Status" },
                                    { data: "Updated_By" },
                                    { data: "Updated_At",title: "Update_Date" },
                                    { data: "Created_Date" }

                            ],
                            select: {
                                    style:    'os',
                                    selector: 'tr'
                            },
                            buttons: [

                            ],

                    });

                      $('#allclaimtab').html("Date Range Claim" + "[" + alltable.api().rows().count() +"]")
                      $('#pendingreviewtab').html("Pending Review Claim" + "[" + claimtable.api().rows().count() +"]")
                      $('#approvedclaimtab').html("Approved Claim" + "[" + claim2table.api().rows().count() +"]")
                      $('#rejectedclaimtab').html("Rejected Claim" + "[" + claim3table.api().rows().count() +"]")
                      $('#allfinalclaimtab').html("Final Approved Claim" + "[" + claim4table.api().rows().count() +"]")

                      $('a[data-toggle="tab"]').on( 'shown.bs.tab', function (e) {
                        var target = $(e.target).attr("href") // activated tab

                          $.fn.dataTable.tables( {visible: true, api: true} ).columns.adjust();

                      } );

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

                                     claimtable.fnFilter( '^$', this.name,true,false );
                                  }
                                  else if (this.value=="[nonempty]")
                                  {

                                     claimtable.fnFilter( '^(?=\\s*\\S).*$', this.name,true,false );
                                  }
                                  else if (this.value.startsWith("!")==true && this.value.length>1)
                                  {

                                     claimtable.fnFilter( '^(?'+ this.value +').*', this.name,true,false );
                                  }
                                  else if (this.value.startsWith("!")==false)
                                  {

                                      claimtable.fnFilter( this.value, this.name,true,false );
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

                                     claim2table.fnFilter( '^$', this.name,true,false );
                                  }
                                  else if (this.value=="[nonempty]")
                                  {

                                     claim2table.fnFilter( '^(?=\\s*\\S).*$', this.name,true,false );
                                  }
                                  else if (this.value.startsWith("!")==true && this.value.length>1)
                                  {

                                     claim2table.fnFilter( '^(?'+ this.value +').*', this.name,true,false );
                                  }
                                  else if (this.value.startsWith("!")==false)
                                  {

                                      claim2table.fnFilter( this.value, this.name,true,false );
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

                                     claim3table.fnFilter( '^$', this.name,true,false );
                                  }
                                  else if (this.value=="[nonempty]")
                                  {

                                     claim3table.fnFilter( '^(?=\\s*\\S).*$', this.name,true,false );
                                  }
                                  else if (this.value.startsWith("!")==true && this.value.length>1)
                                  {

                                     claim3table.fnFilter( '^(?'+ this.value +').*', this.name,true,false );
                                  }
                                  else if (this.value.startsWith("!")==false)
                                  {

                                      claim3table.fnFilter( this.value, this.name,true,false );
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

                                     claim4table.fnFilter( '^$', this.name,true,false );
                                  }
                                  else if (this.value=="[nonempty]")
                                  {

                                     claim4table.fnFilter( '^(?=\\s*\\S).*$', this.name,true,false );
                                  }
                                  else if (this.value.startsWith("!")==true && this.value.length>1)
                                  {

                                     claim4table.fnFilter( '^(?'+ this.value +').*', this.name,true,false );
                                  }
                                  else if (this.value.startsWith("!")==false)
                                  {

                                      claim4table.fnFilter( this.value, this.name,true,false );
                                  }
                              }


            });

          });

      </script>

@endsection

@section('content')

    <div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
      <h1>
        Claim Management
        <small>Finance Management</small>
      </h1>
      <ol class="breadcrumb">
        <li><a href="{{ url('/home') }}"><i class="fa fa-dashboard"></i> Home</a></li>
        <li><a href="#">Finance Management</a></li>
        <li class="active">Claim Management</li>
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

              <li class="active"><a href="#pendingreview" data-toggle="tab" id="pendingreviewtab">Pending Review Claim</a></li>
              <li><a href="#approvedclaim" data-toggle="tab" id="approvedclaimtab">Approved Claim</a></li>
              <li><a href="#rejectedclaim" data-toggle="tab" id="rejectedclaimtab">Rejected Claim</a></li>
              @if($me->View_All_Claim)
                <li><a href="#allclaim" data-toggle="tab" id="allclaimtab">Date Range Claim</a></li>
                <li><a href="#allfinalclaim" data-toggle="tab" id="allfinalclaimtab">Final Approved Claim</a></li>
              @endif
            </ul>

            <div class="tab-content">

              <div class="tab-pane" id="allclaim">

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
                  <label></label>
              </div>

                <table id="alltable" class="alltable" cellspacing="0" width="100%" padding="30px" style="font-size: 13px;">
                    <thead>
                        {{-- prepare header search textbox --}}
                        @if($allclaims)
                          <tr class="search">

                            @foreach($allclaims as $key=>$value)

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
                          @foreach($allclaims as $key=>$value)

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
                      @foreach($allclaims as $claim)

                        <tr id="row_{{ $i }}">
                            <td><a href="{{ url('/claim') }}/{{$claim->Id}}/{{$claim->UserId}}/true" >Review</a></td>
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

              <div class="active tab-pane" id="pendingreview">
                <table id="pendingtable" class="pendingtable" cellspacing="0" width="100%" padding="30px" style="font-size: 13px;">
                    <thead>
                        {{-- prepare header search textbox --}}
                        @if($claims)
                          <tr class="search">

                            @foreach($claims as $key=>$value)

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
                          @foreach($claims as $key=>$value)

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
                      @foreach($claims as $claim)

                        @if(strpos($claim->Status,"Pending")!==false)

                        <tr id="row_{{ $i }}">
                            <td><a href="{{ url('/claim') }}/{{$claim->Id}}/{{$claim->UserId}}">Review</a></td>
                            @foreach($claim as $key=>$value)
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

              <div class="tab-pane" id="approvedclaim">
                <table id="approvedtable" class="approvedtable" cellspacing="0" width="100%" padding="30px" style="font-size: 13px;">
                    <thead>
                        {{-- prepare header search textbox --}}
                        @if($claims)
                          <tr class="search">

                            @foreach($claims as $key=>$value)

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
                          @foreach($claims as $key=>$value)

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
                      @foreach($claims as $claim)

                        @if(strpos($claim->Status,"Approved")!==false)

                          <tr id="row_{{ $i }}">
                            <td><a href="{{ url('/claim') }}/{{$claim->Id}}/{{$claim->UserId}}" >Review</a></td>
                              @foreach($claim as $key=>$value)
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

              <div class="tab-pane" id="allfinalclaim">
                <table id="finalapprovedtable" class="finalapprovedtable" cellspacing="0" width="100%" padding="30px" style="font-size: 13px;">
                    <thead>
                        {{-- prepare header search textbox --}}
                        @if($allfinalclaims)
                          <tr class="search">

                            @foreach($allfinalclaims as $key=>$value)

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
                          @foreach($allfinalclaims as $key=>$value)

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



                  </tbody>
                    <tfoot></tfoot>
                </table>

              </div>

              <div class="tab-pane" id="rejectedclaim">
                <table id="rejectedtable" class="rejectedtable" cellspacing="0" width="100%" padding="30px" style="font-size: 13px;">
                    <thead>
                        {{-- prepare header search textbox --}}
                        @if($claims)
                          <tr class="search">

                            @foreach($claims as $key=>$value)

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
                          @foreach($claims as $key=>$value)

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
                      @foreach($claims as $claim)

                        @if(strpos($claim->Status,"Rejected")!==false)

                          <tr id="row_{{ $i }}">
                              <td><a href="{{ url('/claim') }}/{{$claim->Id}}/{{$claim->UserId}}">Review</a></td>
                              @foreach($claim as $key=>$value)
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

    window.location.href ="{{ url("/claimmanagement") }}/"+arr[0]+"/"+arr[1];

  }

  function updateclaimsheet(id) {

    status=$('#checkerstatus'+id).val();

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

                        claim4table.api().ajax.url("{{ asset('/Include/claimfinal.php') }}").load();

                        var message="Status Updated!";
                        $("#update-alert ul").html(message);
                        $("#update-alert").modal("show");


                    }
                    else {

                      var errormessage="Failed to update status!";
                      $("#error-alert ul").html(errormessage);
                      $("#error-alert").modal("show");



                    }

          }
      });

    }

</script>

@endsection
