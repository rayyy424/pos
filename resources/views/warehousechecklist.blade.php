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

      <script type="text/javascript" language="javascript" class="init">
          var warehousetable, warehouse2table, warehouse3table,oTable1,viewtable1,logTable,special;
          $(function(){
              warehousetable = $('#warehousetable').dataTable({
                dom: "rtp",
                columnDefs: [{ "visible": false, "targets": 1}],
                iDisplayLength:25,
                sScrollX: "100%",
                sScrollY: "100%",
                scrollCollapse: true,
                iDisplayLength:100,
                bAutoWidth: true,
              });
              viewtable1=$("#viewTable").DataTable({
                dom:"tp",
                bSort:false,
                iDisplayLength:10,
                responsive: false,
                colReorder: false,
                sScrollX: "100%",
                bScrollCollapse: true,
                bAutoWidth: true,
                sScrollY: "100%",
            });
            logTable=$("#logTable").DataTable({
                dom:"tp",
                bSort:false,
                iDisplayLength:10,
                responsive: false,
                colReorder: false,
                sScrollX: "100%",
                bScrollCollapse: true,
                bAutoWidth: true,
                sScrollY: "100%",
            });
              warehouse2table = $('#warehouse2table').dataTable({
                dom: "rtp",
                iDisplayLength:25,
                sScrollX: "100%",
                sScrollY: "100%",
                scrollCollapse: true,
                iDisplayLength:100,
                bAutoWidth: true,
              });
              warehouse3table = $('#warehouse3table').dataTable({
                dom: "rtp",
                columnDefs: [{ "visible": false, "targets": [1,8]}],
                iDisplayLength:25,
                sScrollX: "100%",
                sScrollY: "100%",
                scrollCollapse: true,
                iDisplayLength:100,
                bAutoWidth: true,
              });
              
      $(".warehousetable thead input").keyup ( function () {

      /* Filter on the column (the index) of this element */
      if ($('#warehousetable').length > 0)
      {
        var colnum=document.getElementById('warehousetable').rows[0].cells.length;
        if (this.value=="[empty]")
        {
          warehousetable.fnFilter( '^$', this.name,true,false );
        }
        else if (this.value=="[nonempty]")
        {
          warehousetable.fnFilter( '^(?=\\s*\\S).*$', this.name,true,false );
        }
        else if (this.value.startsWith("!")==true && this.value.length>1)
        {
          warehousetable.fnFilter( '^(?'+ this.value +').*', this.name,true,false );
        }
        else if (this.value.startsWith("!")==false)
        {
          warehousetable.fnFilter( this.value, this.name,true,false );
        }
      }
    });

       $(".warehouse2table thead input").keyup ( function () {

      /* Filter on the column (the index) of this element */
      if ($('#warehouse2table').length > 0)
      {
        var colnum=document.getElementById('warehouse2table').rows[0].cells.length;
        if (this.value=="[empty]")
        {
          warehouse2table.fnFilter( '^$', this.name,true,false );
        }
        else if (this.value=="[nonempty]")
        {
          warehouse2table.fnFilter( '^(?=\\s*\\S).*$', this.name,true,false );
        }
        else if (this.value.startsWith("!")==true && this.value.length>1)
        {
          warehouse2table.fnFilter( '^(?'+ this.value +').*', this.name,true,false );
        }
        else if (this.value.startsWith("!")==false)
        {
          warehouse2table.fnFilter( this.value, this.name,true,false );
        }
      }
    });

        $(".warehouse3table thead input").keyup ( function () {

      /* Filter on the column (the index) of this element */
      if ($('#warehouse3table').length > 0)
      {
        var colnum=document.getElementById('warehouse3table').rows[0].cells.length;
        if (this.value=="[empty]")
        {
          warehouse3table.fnFilter( '^$', this.name,true,false );
        }
        else if (this.value=="[nonempty]")
        {
          warehouse3table.fnFilter( '^(?=\\s*\\S).*$', this.name,true,false );
        }
        else if (this.value.startsWith("!")==true && this.value.length>1)
        {
          warehouse3table.fnFilter( '^(?'+ this.value +').*', this.name,true,false );
        }
        else if (this.value.startsWith("!")==false)
        {
          warehouse3table.fnFilter( this.value, this.name,true,false );
        }
      }
    });

        $('a[data-toggle="tab"]').on( 'shown.bs.tab', function (e) {
               var target = $(e.target).attr("href") // activated tab
               if (target=="#mypendingwarehouse")
               {
                 $.fn.dataTable.tables( {visible: true, api: true} ).columns.adjust();
               }
               else if (target=="#myacceptedwarehouse")
               {
                 $.fn.dataTable.tables( {visible: true, api: true} ).columns.adjust();
               }
               else if(target=="#mystockinwarehouse")
               {
                 $.fn.dataTable.tables( {visible: true, api: true} ).columns.adjust();
               }
             } );
             special=$("#specialTable").dataTable({
              responsive: false,
                colReorder: false,
                sScrollX: "100%",
                bScrollCollapse: true,
                bAutoWidth: true,
                sScrollY: "100%",
                dom: "tip",
                iDisplayLength:10,
             })
             oTable1=$("#approvedTable").dataTable({
              columnDefs: [
                  // { "visible": false, "targets": [1] },
                    // {"className": "dt-center", "targets": []},
                    // {"className": "dt-right", "targets": []}
              ],
                responsive: false,
                colReorder: false,
                sScrollX: "100%",
                bScrollCollapse: true,
                bAutoWidth: true,
                sScrollY: "100%",
                dom: "tip",
                iDisplayLength:10,
             });


        oTable1.on( 'order.dt search.dt', function () {
          oTable1.api().column(0, {search:'applied', order:'applied'}).nodes().each( function (cell, i) {
            cell.innerHTML = i+1;
          } );
        } ).api().draw();
        special.on( 'order.dt search.dt', function () {
          special.api().column(0, {search:'applied', order:'applied'}).nodes().each( function (cell, i) {
            cell.innerHTML = i+1;
          } );
        } ).api().draw();
        $(".approvedTable thead input").keyup ( function () {
            if ($('#approvedTable').length > 0)
            {
                var colnum=document.getElementById('approvedTable').rows[0].cells.length;
                if (this.value=="[empty]")
                {
                    oTable1.fnFilter( '^$', this.name,true,false );
                }
                else if (this.value=="[nonempty]")
                {
                    oTable1.fnFilter( '^(?=\\s*\\S).*$', this.name,true,false );
                }
                else if (this.value.startsWith("!")==true && this.value.length>1)
                {
                    oTable1.fnFilter( '^(?'+ this.value +').*', this.name,true,false );
                }
                else if (this.value.startsWith("!")==false)
                {
                    oTable1.fnFilter( this.value, this.name,true,false );
                }
            }
        });
        $(".specialTable thead input").keyup ( function () {
            if ($('#specialTable').length > 0)
            {
                var colnum=document.getElementById('specialTable').rows[0].cells.length;
                if (this.value=="[empty]")
                {
                    special.fnFilter( '^$', this.name,true,false );
                }
                else if (this.value=="[nonempty]")
                {
                    special.fnFilter( '^(?=\\s*\\S).*$', this.name,true,false );
                }
                else if (this.value.startsWith("!")==true && this.value.length>1)
                {
                  special.fnFilter( '^(?'+ this.value +').*', this.name,true,false );
                }
                else if (this.value.startsWith("!")==false)
                {
                  special.fnFilter( this.value, this.name,true,false );
                }
            }
        });
        $('a[data-toggle="tab"]').on( 'shown.bs.tab', function (e) {
            var target = $(e.target).attr("href") 
            $.fn.dataTable.tables( {visible: true, api: true} ).columns.adjust();
        } );
        $('#viewModal,#uploadModal,#logModal').on('shown.bs.modal', function (e) {
            $.fn.dataTable.tables({ visible: true, api: true }).columns.adjust();
        });
          });
		</script>
@endsection

@section('content')

<div class="content-wrapper">
	<!-- Content Header (Page header) -->
	<section class="content-header">
		<h1>Warehouse Checklist<small>Delivery Management</small></h1>

		<ol class="breadcrumb">
			<li><a href="{{ url('/home') }}"><i class="fa fa-dashboard"></i> Home</a></li>
			<li><a href="#">Delivery Management</a></li>
			<li class="active">Warehouse Checklist</li>
		</ol>
	</section>

  <br>

	<!-- Main content -->
<section class="content">
  
    <div class="row">
        <div class="col-md-12">
          <div class="box">
              <div class="box-body">
                <div class="row">
                     <div class="col-md-4">
                       <div class="input-group">
                         <div class="input-group-addon">
                           <i class="fa fa-clock-o"></i>
                         </div>
                         <input type="text" class="form-control" id="range" name="range">
                       </div>
                    </div>
                    <!--  -->
                    <div class="col-md-2">
                      <div class="input-group">
                        <button type="button" class="btn btn-success btn" data-toggle="modal" onclick="refresh();">Refresh</button>
                      </div>
                    </div>
                </div>
              </div>
          </div>
        </div>
    </div>

  <div class="row">
        <div class="modal fade" id="NameList" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
            <div class="modal-dialog" role="document">
              <div class="modal-content">
                <div class="modal-header">
                  <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                  <h4 class="modal-title" id="myModalLabel">Leave List</h4>
                </div>
                <div class="modal-body" name="list" id="list">

                </div>
                <div class="modal-footer">
                  <center><img src="{{ URL::to('/') ."/img/ajax-loader.gif" }}" width="50px" height="50px" alt="Loading" name='ajaxloader3' id="ajaxloader5"></center>
                  <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                </div>
              </div>
            </div>
        </div>

        <div class="modal fade" id="viewItemListModal" role="dialog" aria-labelledby="myItemListModalLabel" style="display: none;">
          <div class="modal-dialog" role="document">
            <div class="modal-content">
              <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">×</span></button>
                <h4 class="modal-title" id="myItemListModalLabel">Items</h4>
              </div>
              <div class="modal-body">
                <table id="itemlisttable" class="table table-bordered table-hover">
                  <thead>
                    <tr>
                      <th style="display: none;"></th>
                      <th>Items</th>
                      <th>Description</th>
                      <th>Unit</th>
                      <th>Purpose</th>
                      <th>Quantity Requested</th>
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

        <div class="modal fade" id="Reject" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
          <div class="modal-dialog" role="document">
            <div class="modal-content">
              <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title" id="myModalLabel">Reject</h4>
              </div>
              <div class="modal-body">
                <div class="form-group" id="rejectwarehousechecklist">

                </div>
                <div class="form-group">

                    <div class="form-group">
                    <label>Reason : </label>
                    <textarea class="form-control" rows="3" name="Reason" id="Reason" placeholder="Enter your reason here ..."></textarea>
                    </div>

                </div>
                Are you sure you wish to reject this application?
              </div>
              <div class="modal-footer">
                <center><img src="{{ URL::to('/') . "/img/ajax-loader.gif" }}" width="50px" height="50px" alt="Loading" name='ajaxloader' id="ajaxloader"></center>
                <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                <button type="button" class="btn btn-primary" onclick="rejectwarehousechecklist()">Submit</button>
              </div>
            </div>
          </div>
        </div>

        <div class="modal fade" id="Accept" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
          <div class="modal-dialog" role="document">
            <div class="modal-content">
              <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title" id="myModalLabel">Accept Application</h4>
              </div>
              <div class="modal-body">
                <div class="form-group" id="acceptwarehousechecklist">

                </div>
                  Are you sure you wish to accept this application?
              </div>
              <div class="modal-footer">
                <center><img src="{{ URL::to('/') ."/img/ajax-loader.gif" }}" width="50px" height="50px" alt="Loading" name='ajaxloader2' id="ajaxloader2"></center>
                <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                <button type="button" class="btn btn-primary" onclick="accept()">Accept</button>
              </div>
            </div>
          </div>
        </div>
        
        <div class="col-md-12">
          <div class="nav-tabs-custom">
            <ul class="nav nav-tabs">
              <li class="active"><a href="#mypendingwarehouse" data-toggle="tab" id="mypendingwarehousetab">Warehouse Checklist</a></li>
              <li><a href="#myacceptedwarehouse" data-toggle="tab" id="myacceptedwarehousetab">Accepted Warehouse Checklist</a></li>
              <li><a href="#mystockinwarehouse" data-toggle="tab" id="mystockinwarehousetab">Stocks Update Checklist</a></li>
              <li><a href="#mr" data-toggle="tab" id="mrtab">MR</a></li>
              <li><a href="#special" data-toggle="tab" id="specialtab">Special Delivery</a></li>
            </ul>

          <div class="tab-content">
            <div class="active tab-pane" id="mypendingwarehouse">
              
              <table id="warehousetable" class="warehousetable" cellspacing="0" width="100%" padding="30px" style="font-size: 13px;">
                <thead>
                  <tr class="search">
                        @foreach($data as $key=>$value)
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
                              <!-- <th align='center'><input type='hidden' class='search_init' name='{{$i}}'  placemark='{{$a}}'></th> -->
                            @endif
                          @endforeach
                        </tr>
                  <tr>
                    <td>No</td>
                    <td>Form Id</td>
                    <td>Purpose</td>
                    <td>DO No.</td>
                    <td>Delivery Date</td>
                    <td>Delivery Time</td>
                    <td>Pickup Date</td>
                    <td>Pickup Time</td>
                    <td>Vehicle No</td>
                    <td>Site</td>
                    <td>Driver Name</td>
                    <td>Remarks</td>
                    <td>Requestor Name</td>
                    <td>Action</td>
                  </tr>
                </thead>
                <tbody>
                  <?php $i=1;?>
                  @foreach($data as $d)
                  <tr>
                    <td>{{$i}}</td>
                    <td>{{$d->formid}}</td>
                    <td>{{$d->Option}}</td>
                    <td>{{$d->DO_No}}</td>
                    <td>{{$d->delivery_date}}</td>
                    <td>{{$d->delivery_time}}</td>
                    <td>{{$d->pickup_date}}</td>
                    <td>{{$d->pick_up_time}}</td>
                    <td>{{$d->Vehicle_No}}</td>
                    <td>{{$d->Location_Name}}</td>
                    <td>{{$d->Name}}</td>
                    <td>{{$d->Remarks}}</td>
                    <td>{{$d->requestorName}}</td>
                    <td>
                      <a class="fa fa-eye fa-2x" target="_blank" href="{{url('warehousedetails')}}/{{$d->formid}}"> </a>
                    </td>
                  </tr>
                  <?php $i++?>
                   @endforeach
                </tbody>
                <tfoot></tfoot>
              </table>
            </div>

            <div class="tab-pane" id="myacceptedwarehouse">
              <table id="warehouse2table" class="warehouse2table" cellspacing="0" width="100%" padding="30px" style="font-size: 13px;">
                  <thead>
                    <tr class="search">
                        @foreach($accept as $key=>$value)
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
                              <!-- <th align='center'><input type='hidden' class='search_init' name='{{$i}}'  placemark='{{$a}}'></th> -->
                            @endif
                          @endforeach
                        </tr>
                    <tr>
                      <td>No</td>
                      <!-- <td>Form Id</td> -->
                      <td>Purpose</td>
                      <td>DO No.</td>
                      <td>Delivery Date</td>
                      <td>Delivery Time</td>
                      <td>Pickup Date</td>
                      <td>Pickup Time</td>
                      <td>Vehicle No</td>
                      <td>Site</td>
                      <td>Driver Name</td>
                      <td>Remarks</td>
                      <td>Requestor Name</td>
                      <td>Action</td>
                    </tr>
                  </thead>
                  <tbody>
                    <?php $i=1;?>
                    @foreach($accept as $accept)
                    <tr>
                      <td>{{$i}}</td>
                      <!-- <td>{{$accept->formid}}</td> -->
                      <td>{{$accept->Option}}</td>
                      <td>{{$accept->DO_No}}</td>
                      <td>{{$accept->delivery_date}}</td>
                      <td>{{$accept->delivery_time}}</td>
                      <td>{{$accept->pickup_date}}</td>
                      <td>{{$accept->pick_up_time}}</td>
                      <td>{{$accept->Vehicle_No}}</td>
                      <td>{{$accept->Location_Name}}</td>
                      <td>{{$accept->Name}}</td>
                      <td>{{$accept->Remarks}}</td>
                      <td>{{$accept->requestorName}}</td>
                      <td>
                          <a class="fa fa-eye fa-2x" target="_blank" href="{{url('warehousedetails')}}/{{$accept->formid}}"></a>
                      </td>
                      <?php $i++;?>

                    </tr>
                    @endforeach
                  </tbody>
                  <tfoot></tfoot>
              </table>
            </div>

            <div class="tab-pane" id="mystockinwarehouse">
              <table id="warehouse3table" class="warehouse3table" cellspacing="0" width="100%" padding="30px" style="font-size: 13px;">
                  <thead>
                    <tr class="search">
                        @foreach($stockin as $key=>$value)
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
                              <!-- <th align='center'><input type='hidden' class='search_init' name='{{$i}}'  placemark='{{$a}}'></th> -->
                            @endif
                          @endforeach
                        </tr>
                    <tr>
                      <td>No</td>
                      <td>Id</td>
                      <td>DO No.</td>
                      <td>Request Date</td>
                      <td>Delivery Date</td>
                      <td>Stock Insufficient Date</td>
                      <td>Site</td>
                      <td>Purpose</td>
                      <td>Action</td>
                    </tr>
                  </thead>
                  <tbody>
                    <?php $i=1;?>
                    @foreach($stockin as $accept)
                    <tr>
                      <td>{{$i}}</td>
                      <td>{{$accept->Id}}</td>
                      <td>{{$accept->DO_No}}</td>
                      <td>{{$accept->request}}</td>
                      <td>{{$accept->delivery_date}}</td>
                      <td>{{$accept->updated_at}}</td>
                      <td>{{$accept->Location_Name}}</td>
                      <td>{{$accept->Option}}</td>
                      <td>
                          <!-- <button class="btn btn-info" id="stockin" name="stockin">Stock-in</button> -->
                          <button class="btn btn-info" data-toggle="modal" data-target="#viewItemListModal" onclick="viewItemList({{$accept->Id}})">View Items</button>
                      </td>
                      <?php $i++;?>
                    </tr>
                    @endforeach
                  </tbody>
                  <tfoot></tfoot>
              </table>
            </div>

            <div class="tab-pane" id="mr">
              
              <table id="approvedTable" class="approvedTable" cellspacing="0" width="100%" padding="30px" style="font-size: 13px;">
                  <thead>
                      <tr class="search">
                          <th align='center'><input type='hidden' class='search_init' name='0'></th>
                          <th align='center'><input type='text' class='search_init' name='1'></th>
                          <th align='center'><input type='text' class='search_init' name='2'></th>
                          <th align='center'><input type='text' class='search_init' name='3'></th>
                          <th align='center'><input type='text' class='search_init' name='4'></th>
                          <th align='center'><input type='text' class='search_init' name='5'></th>
                          <th align='center'><input type='text' class='search_init' name='6'></th>
                          <th align='center'><input type='text' class='search_init' name='7'></th>
                          <th align='center'><input type='text' class='search_init' name='8'></th>
                          <th align='center'><input type='text' class='search_init' name='9'></th>
                      </tr>
                      
                      <tr>
                             <td>No</td>
                             <td>MR_NO</td>
                             <td>Name</td>
                             <td>Site Name</td>
                             <td>Created At</td>
                             <td>View By</td>
                             <td>View On</td>
                             <td>Action</td>
                      </tr>
                  </thead>
                  <tbody>
                      <?php $i=0;?>
                      @foreach($mr as $m)
                        @if($m->MR_No)
                          <tr id="row_{{$i}}">
                              <td></td>
                              <td><a style='color:blue;' target='_blank' href='{{url('material/print')}}/{{$m->Id}}'>{{$m->MR_No}}</a></td>
                              <td>{{$m->Name}}</td>
                              <td>{{$m->sitename}}</td>
                              <td>{{$m->created_at}}</td>
                              <td>{{$m->viewon}}</td>
                              <td>{{$m->viewName}}</td>
                              <td><a onclick='viewMaterial({{$m->Id}},"view")' style='width:unset;' class='btn btn-default btn-sm' ><i class="fa fa-eye" aria-hidden="true"></i></a></td>
                          </tr>
                        @endif
                          <?php $i++;?>
                      @endforeach
                  </tbody>
              </table>
            </div>


            <div class="tab-pane" id="special">
              
                <table id="specialTable" class="specialTable" cellspacing="0" width="100%" padding="30px" style="font-size: 13px;">
                    <thead>
                        <tr class="search">
                            <th align='center'><input type='hidden' class='search_init' name='0'></th>
                            <th align='center'><input type='text' class='search_init' name='1'></th>
                            <th align='center'><input type='text' class='search_init' name='2'></th>
                            <th align='center'><input type='text' class='search_init' name='3'></th>
                            <th align='center'><input type='text' class='search_init' name='4'></th>
                            <th align='center'><input type='text' class='search_init' name='5'></th>
                            <th align='center'><input type='text' class='search_init' name='6'></th>
                            <th align='center'><input type='text' class='search_init' name='7'></th>
                            <th align='center'><input type='text' class='search_init' name='8'></th>
                            <th align='center'><input type='text' class='search_init' name='9'></th>
                        </tr>
                        
                        <tr>
                               <td>No</td>
                               <td>DO NO</td>
                               <td>MR_NO</td>
                               <td>Name</td>
                               <td>Site Name</td>
                               <td>Created At</td>
                               <td>View By</td>
                               <td>View On</td>
                               <td>Action</td>
                        </tr>
                    </thead>
                    <tbody>
                        <?php $i=0;?>
                        @foreach($special as $m)
                          @if($m->MR_No)
                            <tr id="row_{{$i}}">
                                <td></td>
                                <td>{{$m->DO_No}}</td>
                                <td><a style='color:blue;' target='_blank' href='{{url('material/print')}}/{{$m->Id}}'>{{$m->MR_No}}</a></td>
                                <td>{{$m->Name}}</td>
                                <td>{{$m->sitename}}</td>
                                <td>{{$m->created_at}}</td>
                                <td>{{$m->viewon}}</td>
                                <td>{{$m->viewName}}</td>
                                <td>
                                  <a class="fa fa-eye fa-2x" target="_blank" href="{{url('warehousedetails')}}/{{$m->DeliveryId}}"></a>
                                </td>
                            </tr>
                          @endif
                            <?php $i++;?>
                        @endforeach
                    </tbody>
                </table>
              </div>

          </div>
          <!-- /.nav tab content -->
        </div>
        <!-- /.av-tabs-custom -->
      </div>
<!--Row-->
    </div>
    <div class="modal fade" id="viewModal">
      <div class="modal-dialog modal-lg">
          <div class="modal-content">
              <div class="modal-header">
                  <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                  <span aria-hidden="true">&times;</span></button>
                  <h4 class="modal-title">View</h4>
              </div>
              <div class="modal-body">
                  <div class="row">
                      <div class="col-sm-12">
                          <span id="save_mess" style="color:red;"></span>
                          <span id="view_error" style="color:red;"></span>
                      </div>
                  </div>
                  <div class="row">
                      <div class="col-sm-12" id='insertTypePrice'>
                          
                      </div>
                  </div>
                 
                  <div class="row">
                      <div class="col-sm-12">
                          <table id="viewTable" cellspacing="0" width="100%"  style="font-size: 13px;white-space:nowrap;">
                              <thead>
                                  <tr id="insertTh">
                                      <th>No</th>
                                      <th>Item Code</th>
                                      <th>Item Description</th>
                                      <th>Type</th>
                                      <th>Qty</th>
                                      <th>Price</th>
                                      <th>Total</th>
                                      <th>DO NO</th>
                                  </tr>
                              </thead>
                              <tbody id="materialData">

                              </tbody>
                          </table>
                      </div>
                  </div>
              </div>
              <div class="modal-footer">
                  <button type="button" class="btn btn-warning btn-sm" data-dismiss="modal">Close</button>
              </div>
          </div>
      </div>
  </div>

  <div class="modal fade" id="logModal">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title">View</h4>
            </div>
            <div class="modal-body">
               
                <div class="row">
                    <div class="col-sm-12">
                        <table id="logTable" cellspacing="0" width="100%"  style="font-size: 13px;white-space:nowrap;">
                            <thead>
                                <tr id="insertTh">
                                    <th>No</th>
                                    <th>View by</th>
                                    <th>Date</th>
                                </tr>
                            </thead>
                            <tbody id="logData">

                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-warning btn-sm" data-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>
  </section>
<!--Content Wrapper-->
</div>

<footer class="main-footer">
	<div class="pull-right hidden-xs">
		<b>Version</b> 1.0.0
	</div>
	<strong>Copyright &copy; 2014-2016 <a href="http://www.softoya.com">TrackerOnTheGo</a>.</strong> All rights reserved.
</footer>

<script>
  $(function () {
    $( "#upload_form" ).submit(function( event ) {
      event.preventDefault();
      return false;
    });
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


      ],
      editable: false,
      droppable: false, // this allows things to be dropped onto the calendar !!!
    });
  });

    function rejectwarehousechecklist() {

      $.ajaxSetup({
         headers: { 'X-CSRF-Token' : $('meta[name=_token]').attr('content') }
      });

      $("#ajaxloader2").show();

      warehouseid=$('[name="rejectwarehousechecklistid"]').val();

      $.ajax({
                  url: "{{ url('/mwarehousechecklist/reject') }}",
                  method: "POST",
                  data: {Id:leaveid},

                  success: function(response){

                    if (response==1)
                    {
                      warehousetable.ajax.reload();
                      warehouse2table.ajax.reload();
                      warehouse3table.ajax.reload();
                      var message="Application Rejected!";
                      $("#update-alert ul").html(message);
                      $("#update-alert").modal('show');

                      // setTimeout(function() {
                      //   $("#update-alert").fadeOut();
                      // }, 6000);

                      $('#Reject').modal('hide');

                      $("#ajaxloader2").hide();

                    }
                    else {

                      var errormessage="Failed to Reject Application!";
                      $("#error-alert ul").html(errormessage);
                      $("#error-alert").modal('show');


                      // setTimeout(function() {
                      //   $("#error-alert").fadeOut();
                      // }, 6000);

                      $('#Reject').modal('hide');

                      $("#ajaxloader2").hide();

                    }

          }
      });

  }
   function accept() {

    var boxes = $('input[type="checkbox"]:checked', warehousetable.fnGetNodes() );
    var ids="";

    if (boxes.length>0)
    {
      for (var i = 0; i < boxes.length; i++) {
        ids+=boxes[i].value+",";
      }

      ids=ids.substring(0, ids.length-1);

      $.ajaxSetup({
         headers: { 'X-CSRF-Token' : $('meta[name=_token]').attr('content') }
      });

        $("#ajaxloader").show();

      $.ajax({
                  url: "{{ url('/warehousechecklist/accept') }}",
                  method: "POST",
                  data: {Ids:ids,
                  Status:status},

                  success: function(response){

                    if (response==1)
                    {

                        warehousetable.api().ajax.url("{{ asset('/Include/leaveapproval.php') }}").load();

                        var message="Application Accepted!";
                        $("#update-alert ul").html(message);
                        $("#update-alert").modal('show');

                        $("#ajaxloader").hide();

                    }
                    else {

                      var errormessage="Failed to Accept Application!";
                      $("#error-alert ul").html(errormessage);
                      $("#error-alert").modal('show');

                      $("#ajaxloader").hide();

                    }

          }
      });

    }
    else {
      var errormessage="No Application Selected!";
      $("#error-alert ul").html(errormessage);
      $("#error-alert").modal('show');


    }

  }

  function rejectwarehousechecklist() {

      $.ajaxSetup({
         headers: { 'X-CSRF-Token' : $('meta[name=_token]').attr('content') }
      });

      $("#ajaxloader2").show();

      warehouseid=$('[name="rejectwarehousechecklistid"]').val();

      $.ajax({
                  url: "{{ url('/warehousechecklist/reject') }}",
                  method: "POST",
                  data: {Id:warehouseid},

                  success: function(response){

                    if (response==1)
                    {
                      warehousetable.ajax.reload();
                      warehouse2table.ajax.reload();
                      warehouse3table.ajax.reload();
                      warehouse4table.ajax.reload();
                      var message="Application Rejected!";
                      $("#update-alert ul").html(message);
                      $("#update-alert").modal('show');

                      // setTimeout(function() {
                      //   $("#update-alert").fadeOut();
                      // }, 6000);

                      $('#Reject').modal('hide');

                      $("#ajaxloader2").hide();

                    }
                    else {

                      var errormessage="Failed to Rec leave!";
                      $("#error-alert ul").html(errormessage);
                      $("#error-alert").modal('show');


                      // setTimeout(function() {
                      //   $("#error-alert").fadeOut();
                      // }, 6000);

                      $('#Reject').modal('hide');

                      $("#ajaxloader2").hide();

                    }

          }
      });

  }

    function RejectDialog(id)
  {

    var hiddeninput='<input type="hidden" class="form-control" id="rejectwarehousechecklistid" name="rejectwarehousechecklistid" value="'+id+'">';

      $( "#rejectwarehousechecklist" ).html(hiddeninput);
      $('#Reject').modal('show');

  }

      $(function () {

      $('#range').daterangepicker({locale: {
        format: 'DD-MMM-YYYY'
      },
      startDate: '{{date("d-M-Y",strtotime($start))}}',
      endDate: '{{date("d-M-Y",strtotime($end))}}'});

    });

    function refresh()
    {
      var d=$('#range').val();
      var arr = d.split(" - ");

      window.location.href ="{{ url("/warehousechecklist") }}/"+arr[0]+"/"+arr[1];

    }

      function decodeEntities(s){
      var str, temp= document.createElement('p');
      temp.innerHTML= s;
      str= temp.textContent || temp.innerText;
      temp=null;
      return str;
  }
  function viewItemList(formId) {
      $.ajaxSetup({
         headers: { 'X-CSRF-Token' : $('meta[name=_token]').attr('content') }
      });
      $.ajax({
          url: "{{ url("warehousechecklist/fetchItemList/") }}" + "/" + formId,
          method: "GET",
          success: function(response){
            $('#itemlisttable > tbody').empty();
            response.Item.forEach(function(element) {
                $('#itemlisttable > tbody').append(`<tr>
                  <td style="display: none;"><input type="hidden" id="formId" value="${element.formId}"></td>
                  <td>${element.Item_Code}</td>
                  <td>${element.Description}</td>
                  <td>${element.Unit}</td>
                  <td>${element.Option}</td>
                  <td>${element.Qty_request}</td>
                  <td><input type="hidden" id="stockin${element.Id}" name="stockin" value="${element.stockin}"><button class="stockin btn btn-info" onclick="stockin(${element.Id},${element.formId})">Stock-in</button></td>
                </tr>`);
              // }
            });
          },
          error: function(data){
          }
      });
    }
    function stockin(itemId,formId)
    {
      var id="stockin"+itemId;
      // var stockitem = document.getElementById(id).value;
      $("#ajaxloader").show();
      // console.log(stockitem)
       $.ajaxSetup({
         headers: { 'X-CSRF-Token' : $('meta[name=_token]').attr('content') }
      });
      $.ajax({
          url: "{{url('/stockin')}}" + "/"+itemId + "/" + formId,
          method: "GET",
          // data: {Id:itemId},
          success: function(response){
            if(response==1)
            {
                      alert('All items stock-in successfully. Email will be sent to requestor to make new application');
                      window.location.reload();
            }
            else
            {
              alert('You have Stock-in');
              // $(itemId).parents("tr:first").remove();
            }
            viewItemList(formId);          
          }

        });

        // var rowCount = 0;
        // var table = document.getElementById("itemlisttable");
        // var rows = table.getElementsByTagName("tr")
        // for (var i = 0; i < rows.length; i++) {
        //     if (rows[i].getElementsByTagName("td").length > 0) {
        //         rowCount++;
        //     }
        // }
    }

        
    // }

// $(document).ready(function(){
//   $(".stockin").on("click",function() {
//     var tblId = $(this).closest(':input').attr('id');
//           //get table id number
//           var numId = parseInt(tblId.match(/\d+/));
//           if(isNaN(numId)|| numId==""){
//             numId="";
//           }
//           console.log(numId)
//     // $("#stockin"+i).attr('disable','disable');
//     var formId = $(this).closest("tr").find("#formId").val();
//     console.log(formId)
//      $.ajaxSetup({
//          headers: { 'X-CSRF-Token' : $('meta[name=_token]').attr('content') }
//       });
//       $.ajax({
//           url: "{{ url('/warehousechecklist/stockin') }}",
//           method: "GET",
//           success: function(response){
//             alert( "Stock-in Successfully" );
//           },
//           error: function(data){
//           }
//       });
//     });
//   });

$(".stockin").click(function(){
          var tblId = $(this).closest(':input').attr('id');
          var numId = parseInt(tblId.match(/\d+/));
          if(isNaN(numId)|| numId==""){
            numId="";
          }
    console.log(numId)
});
async function viewMaterial(id,type){
    click=true;
    setTimeout(() => {
        click=false;
    }, 3000);
    newArr=[];
    editArr=[];
    removeArr=[];
    $("#insertTypePrice").empty();
    if(click)
    await $(".deleteBtn").remove();
    $("#materialId").val(id);
    var tablerows=document.getElementById('viewTable').getElementsByTagName("tr").length-1;
    await viewtable1.clear();
    await $(".removeAll").remove();
    await $.ajax({
        type: "get",
        url: "{{url('material/getMaterial')}}",
        data: {
            id:id,
            mpsb:"1"
        },
        success: function (response) {
            
            for(var y=0,i=response.price.length;y<i;y++){
                $("#insertTypePrice").append("<div class='col-sm-4'><label>"+response.price[y].Type+": RM "+parseFloat(response.price[y].total).toFixed(2)+"</label></div>")
            }
      
            for(var y=0,i=response.detail.length;y<i;y++)
            {

                viewtable1.row.add([
                    (y+1),response.detail[y].Item_Code,response.detail[y].Description ,response.detail[y].Type,parseFloat(response.detail[y].Qty).toFixed(2),
                    "RM " + response.detail[y].Price,"RM " + parseFloat(response.detail[y].Price*response.detail[y].Qty).toFixed(2),
                    response.detail[y].DO_No == null ? "-":response.detail[y].DO_No
                ]).node().id="material_"+ response.detail[y].Id;
                // + "<button class='btn btn-sm btn-primary' onclick='uploadModal("+response.detail[y].Id+",`item`)'>Upload Quotation</button>"
                viewtable1.draw(false);
            }
            $("#viewModal").modal('show');
        }
    });
    $.ajaxSetup({
        headers: { 'X-CSRF-Token' : $('meta[name=_token]').attr('content') }
    });
    $.ajax({
      type: "post",
      url: "{{url('updateViewLog')}}",
      data: {id:id},
      success: function (response) {
        
      }
    });
}
function viewLog(id){
  $.ajax({
    type: "get",
    url: "{{url('ViewLog')}}",
    data: {id:id},
    success: function (response) {
      
    }
  });
}
</script>
@endsection