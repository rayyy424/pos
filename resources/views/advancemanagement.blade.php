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

      .green {
        color: green;
      }

      .yellow {
        color: #f39c12;
      }

      .red{
        color:red;
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

        var alltable;
        var pendingtable;
        var approvedtable;
        var allfinaltable;

        $(document).ready(function() {


          alltable=$('#alltable').dataTable( {
                  columnDefs: [{ "visible": false, "targets": [1] },{"className": "dt-center", "targets": "_all"}],
                  responsive: false,
                  colReorder: false,
                  dom: "Bfrtp",
                  sScrollX: "100%",
                  bAutoWidth: true,
                  sScrollY: "100%",
                  bScrollCollapse: true,
                  columns:[
                    {
                         sortable: false,
                         "render": function ( data, type, full, meta ) {

                             return '<a href="{{url('/advances/')}}/'+full.advances.Id+'" >View</a>';
                         }
                    },

                    {data:"advances.Id", title:"Id"},
                    {data:"advancestatuses.Status", title:"Status",
                      "render": function ( data, type, full, meta ) {

                           if(full.advancestatuses.Status.includes("Approved"))
                           {
                             return "<span class='green'>"+full.advancestatuses.Status+"</span>";
                           }
                           else if(full.advancestatuses.Status.includes("Rejected"))
                           {
                             return "<span class='red'>"+full.advancestatuses.Status+"</span>";
                           }
                           else {
                             return "<span class='yellow'>"+full.advancestatuses.Status+"</span>";
                           }

                        }
                    },
                    {data:"users.Name", title:"Name"},
                    {data:"advances.Purpose", title:"Purpose"},
                    {data:"advances.Destination", title:"Destination"},
                    {data:"advances.Start_Date", title:"Start_Date"},
                    {data:"advances.End_Date", title:"End_Date"},
                    {data:"advances.Mode_Of_Transport", title:"Mode_Of_Transport"},
                    {data:"advances.Car_No", title:"Car_No"},
                    {data:"Approver", title:"Approver"},

                  ],


                  select: {
                          style:    'os',
                          selector: 'tr'
                  },
                  buttons: [

                  ],

                  });

          pendingtable=$('#pendingtable').dataTable( {
                  columnDefs: [{ "visible": false, "targets": [1] },{"className": "dt-center", "targets": "_all"}],
                  responsive: false,
                  colReorder: false,
                  dom: "Bfrtp",
                  sScrollX: "100%",
                  bAutoWidth: true,
                  sScrollY: "100%",
                  bScrollCollapse: true,
                  columns:[
                    {
                         sortable: false,
                         "render": function ( data, type, full, meta ) {

                             return '<a href="{{url('/advances/')}}/'+full.advances.Id+'" >View</a>';
                         }
                    },
                    {data:"advances.Id", title:"Id"},
                    {data:"advancestatuses.Status", title:"Status",
                      "render": function ( data, type, full, meta ) {

                           if(full.advancestatuses.Status.includes("Approved"))
                           {
                             return "<span class='green'>"+full.advancestatuses.Status+"</span>";
                           }
                           else if(full.advancestatuses.Status.includes("Rejected"))
                           {
                             return "<span class='red'>"+full.advancestatuses.Status+"</span>";
                           }
                           else {
                             return "<span class='yellow'>"+full.advancestatuses.Status+"</span>";
                           }

                        }
                    },
                    {data:"users.Name", title:"Name"},
                    {data:"advances.Purpose", title:"Purpose"},
                    {data:"advances.Destination", title:"Destination"},
                    {data:"advances.Start_Date", title:"Start_Date"},
                    {data:"advances.End_Date", title:"End_Date"},
                    {data:"advances.Mode_Of_Transport", title:"Mode_Of_Transport"},
                    {data:"advances.Car_No", title:"Car_No"},
                    {data:"Approver", title:"Approver"},

                  ],


                  select: {
                          style:    'os',
                          selector: 'tr'
                  },
                  buttons: [

                  ],

                  });

          approvedtable=$('#approvedtable').dataTable( {
                  columnDefs: [{ "visible": false, "targets": [1] },{"className": "dt-center", "targets": "_all"}],
                  responsive: false,
                  colReorder: false,
                  dom: "Bfrtp",
                  sScrollX: "100%",
                  bAutoWidth: true,
                  sScrollY: "100%",
                  bScrollCollapse: true,
                  columns:[
                    {
                         sortable: false,
                         "render": function ( data, type, full, meta ) {

                             return '<a href="{{url('/advances/')}}/'+full.advances.Id+'" >View</a>';
                         }
                    },
                    {data:"advances.Id", title:"Id"},
                    {data:"advancestatuses.Status", title:"Status",
                      "render": function ( data, type, full, meta ) {

                           if(full.advancestatuses.Status.includes("Approved"))
                           {
                             return "<span class='green'>"+full.advancestatuses.Status+"</span>";
                           }
                           else if(full.advancestatuses.Status.includes("Rejected"))
                           {
                             return "<span class='red'>"+full.advancestatuses.Status+"</span>";
                           }
                           else {
                             return "<span class='yellow'>"+full.advancestatuses.Status+"</span>";
                           }

                        }
                    },
                    {data:"users.Name", title:"Name"},
                    {data:"advances.Purpose", title:"Purpose"},
                    {data:"advances.Destination", title:"Destination"},
                    {data:"advances.Start_Date", title:"Start_Date"},
                    {data:"advances.End_Date", title:"End_Date"},
                    {data:"advances.Mode_Of_Transport", title:"Mode_Of_Transport"},
                    {data:"advances.Car_No", title:"Car_No"},
                    {data:"Approver", title:"Approver"},

                  ],


                  select: {
                          style:    'os',
                          selector: 'tr'
                  },
                  buttons: [

                  ],

                  });

          allfinaltable=$('#allfinaltable').dataTable( {
                  columnDefs: [{ "visible": false, "targets": [0] },{"className": "dt-center", "targets": "_all"}],
                  responsive: false,
                  colReorder: false,
                  dom: "Bfrtp",
                  sScrollX: "100%",
                  bAutoWidth: true,
                  sScrollY: "100%",
                  bScrollCollapse: true,
                  columns:[
                    {data:"advances.Id", title:"Id"},
                    {data:"advancestatuses.Status", title:"Status",
                      "render": function ( data, type, full, meta ) {

                           if(full.advancestatuses.Status.includes("Approved"))
                           {
                             return "<span class='green'>"+full.advancestatuses.Status+"</span>";
                           }
                           else if(full.advancestatuses.Status.includes("Rejected"))
                           {
                             return "<span class='red'>"+full.advancestatuses.Status+"</span>";
                           }
                           else {
                             return "<span class='yellow'>"+full.advancestatuses.Status+"</span>";
                           }

                        }
                    },
                    {data:"users.Name", title:"Name"},
                    {data:"advances.Purpose", title:"Purpose"},
                    {data:"advances.Destination", title:"Destination"},
                    {data:"advances.Start_Date", title:"Start_Date"},
                    {data:"advances.End_Date", title:"End_Date"},
                    {data:"advances.Mode_Of_Transport", title:"Mode_Of_Transport"},
                    {data:"advances.Car_No", title:"Car_No"},
                    {data:"Approver", title:"Approver"},

                  ],


                  select: {
                          style:    'os',
                          selector: 'tr'
                  },
                  buttons: [

                  ],

                  });

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

                       pendingtable.fnFilter( '^$', this.name,true,false );
                    }
                    else if (this.value=="[nonempty]")
                    {

                       pendingtable.fnFilter( '^(?=\\s*\\S).*$', this.name,true,false );
                    }
                    else if (this.value.startsWith("!")==true && this.value.length>1)
                    {

                       pendingtable.fnFilter( '^(?'+ this.value +').*', this.name,true,false );
                    }
                    else if (this.value.startsWith("!")==false)
                    {

                        pendingtable.fnFilter( this.value, this.name,true,false );
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

                       approvedtable.fnFilter( '^$', this.name,true,false );
                    }
                    else if (this.value=="[nonempty]")
                    {

                       approvedtable.fnFilter( '^(?=\\s*\\S).*$', this.name,true,false );
                    }
                    else if (this.value.startsWith("!")==true && this.value.length>1)
                    {

                       approvedtable.fnFilter( '^(?'+ this.value +').*', this.name,true,false );
                    }
                    else if (this.value.startsWith("!")==false)
                    {

                        approvedtable.fnFilter( this.value, this.name,true,false );
                    }
                }

        } );

        $(".allfinaltable thead input").keyup ( function () {

                /* Filter on the column (the index) of this element */
                if ($('#allfinaltable').length > 0)
                {

                    var colnum=document.getElementById('allfinaltable').rows[0].cells.length;

                    if (this.value=="[empty]")
                    {

                       allfinaltable.fnFilter( '^$', this.name,true,false );
                    }
                    else if (this.value=="[nonempty]")
                    {

                       allfinaltable.fnFilter( '^(?=\\s*\\S).*$', this.name,true,false );
                    }
                    else if (this.value.startsWith("!")==true && this.value.length>1)
                    {

                       allfinaltable.fnFilter( '^(?'+ this.value +').*', this.name,true,false );
                    }
                    else if (this.value.startsWith("!")==false)
                    {

                        allfinaltable.fnFilter( this.value, this.name,true,false );
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
      Advance Management
      <small>Admin</small>
      </h1>

      <ol class="breadcrumb">
      <li><a href="{{ url('/home') }}"><i class="fa fa-dashboard"></i>Home</a></li>
      <li><a href="#">Resource Management</a></li>
      <li class="active">Advance Management</li>
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
          <div class="nav-tabs-custom">
            <ul class="nav nav-tabs">
              <li class="active"><a href="#pending" data-toggle="tab" id="pendingtab">Pending Advance</a></li>
              <li><a href="#approved" data-toggle="tab" id="approvedtab">Approved Advance</a></li>
              <!-- <li><a href="#rejected" data-toggle="tab" id="rejectedtab">Rejected Advance</a></li> -->
              <li><a href="#all" data-toggle="tab" id="alltab">All Advance</a></li>
              <li><a href="#final" data-toggle="tab" id="finaltab">All Final Approved Advance</a></li>
            </ul>


            <div class="tab-content">

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

              <div class="active tab-pane" id="pending">

                <table id="pendingtable" class="pendingtable" cellspacing="0" width="100%" padding="30px" style="font-size: 13px;">
                    <thead>
                        {{-- prepare header search textbox --}}
                        @if($all)
                            <tr class="search">

                              @foreach($all as $key=>$value)

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
                          @foreach($all as $key=>$value)

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
                      @foreach($all as $alltable)
                        @if(strpos($alltable->Status,"Pending Approval")!==false)
                        <tr id="row_{{ $i }}">
                          <td></td>
                            @foreach($alltable as $key=>$value)
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

              <div class="tab-pane" id="all">

                  <table id="alltable" class="alltable" cellspacing="0" width="100%" padding="30px" style="font-size: 13px;">
                      <thead>
                          {{-- prepare header search textbox --}}
                          @if($all)
                              <tr class="search">

                                @foreach($all as $key=>$value)

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
                            @foreach($all as $key=>$value)

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
                        @foreach($all as $advance)

                          <tr id="row_{{ $i }}">
                            <td></td>
                              @foreach($advance as $key=>$value)
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

              <div class="tab-pane" id="approved">

                <table id="approvedtable" class="approvedtable" cellspacing="0" width="100%" padding="30px" style="font-size: 13px;">
                    <thead>
                        {{-- prepare header search textbox --}}
                        @if($all)
                            <tr class="search">

                              @foreach($all as $key=>$value)

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
                          @foreach($all as $key=>$value)

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
                      @foreach($all as $alltable)
                        @if(strpos($alltable->Status,"Advance Approved")!==false)
                        <tr id="row_{{ $i }}">
                          <td></td>
                            @foreach($alltable as $key=>$value)
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

              <div class="tab-pane" id="final">

                <table id="allfinaltable" class="allfinaltable" cellspacing="0" width="100%" padding="30px" style="font-size: 13px;">
                    <thead>
                        {{-- prepare header search textbox --}}


                        <tr>
                          @foreach($allfinal as $key=>$value)

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
                      @foreach($allfinal as $alltable)

                        <tr id="row_{{ $i }}">
                            @foreach($alltable as $key=>$value)
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
          <!-- /.nav tab content -->
        </div>
        <!-- /.av-tabs-custom -->

      </div>
      <!-- /.row -->
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

});

function refresh()
{
  var d=$('#range').val();
  var arr = d.split(" - ");

  window.location.href ="{{ url("/advancemanagement") }}/"+arr[0]+"/"+arr[1];

}

</script>



@endsection
