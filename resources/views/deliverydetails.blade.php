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
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/gh/fancyapps/fancybox@3.5.7/dist/jquery.fancybox.min.css" />

    <style type="text/css" class="init">

    a.buttons-collection {
        margin-left: 1em;
    }

    .tableheader {
        background-color: gray;
    }

    .container1 {
        width: 1200px;
        margin-left: 50px;
        padding: 10px;
    }

    .green {
        color: green;
    }

    .timeheader{
        background-color: gray;
    }

    .timeheader th{
        text-align: center;
    }

    .yellow {
        color: #f39c12;
    }

    .red{
        color:red;
    }

    .success {
        color: #00a65a;
    }

    .alert2 {
        color: #dd4b39;
    }

    .warning {
        color: #f39c12;
    }
    .img-wrap {
    position: relative;
    display: inline-block;
    border: 1px red solid;
    font-size: 0;
    }
    .img-wrap .close {
        position: absolute;
        top: 2px;
        right: 2px;
        z-index: 100;
        background-color: #FFF;
        padding: 5px 2px 2px;
        color: #000;
        font-weight: bold;
        cursor: pointer;
        opacity: .2;
        text-align: center;
        font-size: 22px;
        line-height: 10px;
        border-radius: 50%;
    }
    .img-wrap:hover .close {
        opacity: 1;
    }
    #map{
        height: 300px;
        margin: 0 auto;
    }
    a:hover {
        cursor:pointer;
    }
    #textarea{
        white-space: pre-wrap;
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
<script type="text/javascript" language="javascript" src="{{ asset('/plugin/datatables/extensions/Select2/editor.select2.js') }}"></script>
<script type="text/javascript" language="javascript" src="{{ asset('/plugin/js/dataTables.editor.min.js') }}"></script>
<script src="https://cdn.jsdelivr.net/gh/fancyapps/fancybox@3.5.7/dist/jquery.fancybox.min.js"></script>

    <script type="text/javascript" language="javascript" class="init">
        $(function(){

            var t=$(".display").DataTable({
                "bInfo": false,
                "dom": '<"top"i>rt<"bottom"p><"clear">'
            });

        });


    </script>

@endsection

@section('content')

<div class="content-wrapper">

    <!-- Content Header (Page header) -->
    <section class="content-header">
        <h1>Delivery Details<small>Delivery Management</small></h1>

        <ol class="breadcrumb">
            <li><a href="{{ url('/home') }}"><i class="fa fa-dashboard"></i> Home</a></li>
            <li><a href="#">Delivery Management</a></li>
            <li class="active">Delivery Approval</li>
        </ol>
    </section>

    <br>

    <section class="content">

         <div class="modal fade" id="Confirm" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
            <div class="modal-dialog" role="document">
              <div class="modal-content">
                <div class="modal-header">
                  <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                  <h4 class="modal-title" id="myModalLabel">Confirm</h4>
                </div>
                <div class="modal-body">
                  <input type="hidden" name="confirmid" id="confirmid">
                  <div>
                    <label>Additional Description</label>
                    <input type="text" name="change" id="change" class="form-control">
                  </div>
                </div>
                <div class="modal-footer">
                  <!-- <center><img src="{{ URL::to('/') . "/img/ajax-loader.gif" }}" width="50px" height="50px" alt="Loading" name='ajaxloader3' id="ajaxloader3"></center> -->
                  <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                  <button type="button" class="btn btn-primary" id="updatebutton">Update</button>
                </div>
              </div>
            </div>
      </div>

    <div class="modal modal-success  fade" id="update-alert">
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

        <div class="row">
            <div class="col-md-12">
                <div class="box">
                    <div class="box-header">
                        <div class="row">
                            <div class="col-lg-9">
                                <h3>Delivery Details {{$detail->delivery_status_details == "-" ? "(Special Delivery)":""}}</h3>
                            </div>
                            @if($detail->delivery_status != 'Pending' && strpos($detail->DO_No,'DO') !== false)
                            <div class="col-lg-3">
                                <a href="{{url('deliveryorder')}}/{{$detail->Id}}" class="btn btn-sm btn-info" style="float:right;" target="_blank">
                                    Print DO
                                </a>
                            </div>
                            @endif
                            @if($detail->delivery_status != 'Pending'  && strpos($detail->DO_No,'RN') !== false)
                            <div class="col-lg-3">
                                <a href="{{url('returnnote')}}/{{$detail->Id}}" class="btn btn-sm btn-info" style="float:right;" target="_blank">
                                    Print RN
                                </a>
                            </div>
                            @endif
                        </div>
                    </div>
                    <div class="box-body">

                        <div class="row">
                            <div class="col-sm-3">
                                <h4>Form details</h4>
                            </div>
                            <div class="col-sm-3">
                                <h4>Person In Charge</h4>
                            </div>
                            <div class="col-sm-3">
                                <h4>Company</h4>
                            </div>
                            <div class="col-sm-3">
                                <h4>Driver Details</h4>
                            </div>
                        </div>

                        <div class="row">
                            <div class="form-group">

                                <div class="col-sm-3">
                                    <label class="control-label">Delivery Date: </label>
                                    {{$detail->delivery_date}}
                                </div>
                                <div class="col-sm-3">
                                    <label class="control-label">PIC Name: </label>
                                    {{$detail->PIC_Name}}
                                </div>
                                <div class="col-sm-3"></div>
                                <div class="col-sm-3">
                                    <label class="control-label">Driver Name: </label>
                                    <span id='driverDetail'>{{$detail->Name == null? "-":$detail->Name}}</span>
                                    <!-- @if($detail->delivery_status != "Rejected")
                                    <a   class="btn btn-success btn-xs editDriver" id='assignDriver' onclick="assignDriver({{$detail->DriverId}})">Assign</a>
                                    @endif -->
                                </div>
                            </div>
                        </div><!--row-->
                        <div class="row">
                            <div class="col-sm-3">
                                <label class="control-label">Delivery Time: </label>
                                <span id='deliverytime'>{{date('h:i a',strtotime($detail->delivery_time))}}</span>
                                <a class="btn btn-xs btn-primary" onclick="deliverytime('Delivery Time','{{$detail->delivery_time}}')">Edit</a>
                            </div>

                            <div class="col-sm-3">
                                <label class="control-label">PIC Contact: </label>
                                {{$detail->PIC_Contact}}
                            </div>
                            <div class="col-sm-3">
                                <label>Company : </label>
                                <span id="selection">{{$detail->Company_Name == null ? "-":$detail->Company_Name}} - {{$detail->Company_Code}}</span>
                                @if($detail->delivery_status == "Pending" && $detail->Company_Name == null)
                                <a id='add_company' class="btn btn-xs btn-warning">Edit</a>
                                @endif
                            @if($detail->Company_Name != null || $detail->delivery_status_details == "-" || $detail->delivery_status_details == "" )
                                <a id='edit_company' class="btn btn-xs btn-warning">Edit</a>
                                @endif
                            </div>

                            <div class="col-sm-3">
                                <label class="control-label">Vehicle No: </label>
                                <span id='chgV'>{{$detail->Vehicle_No}}</span>
                                @if($detail->delivery_status != "Rejected")
                                <a  class="btn btn-xs btn-success" id="changeVehicle" onclick="chgVehicle({{$detail->roadtax}})">Change</a>
                                @endif
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-sm-3">
                                <label class="control-label">Requestor Name: </label>
                                {{$detail->requestorName}}
                            </div>
                            <div class="col-sm-3"></div>
                            <div class="col-sm-3"></div>
                            <div class="col-sm-3">
                                <label class="control-label">Pick up Time: </label>
                                <span id='pickuptime'>{{date('h:i a',strtotime($detail->pick_up_time))}}</span>
                                <a class="btn btn-xs btn-primary" onclick="deliverytime('Pick up','{{$detail->pick_up_time}}')">Edit</a>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-sm-3">

                                </div>
                                <div class="col-sm-3">

                                </div>
                            <div class="col-sm-3">
                                    <label class="control-label">Pickup Date: </label>
                                    {{$detail->pickup_date}}
                                </div>
                        </div>
                        <div class="row">
                            <div class="col-sm-3">
                                <label>DO No:</label>
                                {{$detail->DO_No == null ? "-":$detail->DO_No}}
                            </div>
                            <div class="col-sm-3">

                                </div>
                                <div class="col-sm-3">

                                </div>
                                <div class="col-sm-3">
                                <label>Trip Type:</label>
                                <select class="form" name="triptype" id="triptype">
                                    @if($detail->trip == "" || $detail->trip == null)
                                    <option value="" selected="">Not Applicable</option>
                                    <option value="1 Way Trip">1 Way Trip</option>
                                    <option value="2 Way Trip">2 Way Trip</option>
                                    @else
                                    <option value="{{$detail->trip}}" selected="" disabled="">{{$detail->trip}}</option>
                                    <option value="">Not Applicable</option>
                                    <option value="1 Way Trip">1 Way Trip</option>
                                    <option value="2 Way Trip">2 Way Trip</option>
                                    @endif
                                </select>
                                </div>
                        </div>

                        <div class='row'>
                            <div class="col-sm-3">
                                <label>Location Name: </label>
                                <a onclick="myfunction({{$detail->Latitude}},{{$detail->Longitude}})">{{$detail->Location_Name}}</a>
                            </div>

                        </div>

                        <div class="row">
                            <div class="col-sm-3">
                                <label class="control-label">Client: </label>
                                {{$detail->client}}
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-sm-3">
                                <label class="control-label">Remarks: </label>
                                {{$detail->Remarks}}
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-sm-12">
                                <label for="condition">Condition:</label>
                                <span><a class="btn btn-warning btn-xs" onclick="editCondition()">Edit</a></span>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-sm-12" id='chgCond'>
                                <?php $i=1;?>
                                @foreach($deliverycond as $dc)
                                    <div>
                                        <span>{{$i}}) {{$dc->Option}}</span>
                                    </div>
                                    <?php $i++;?>
                                @endforeach
                            </div>
                        </div>
                        <br>
                        <div class="row">
                            <div class="col-sm-3">
                                <label for="">Note:</label>
                                <span><a class="btn btn-xs btn-warning" onclick="addNote()">Edit</a></span>
                            </div>
                            <div class="col-sm-3">

                                </div>
                                <div class="col-sm-3">

                                </div>
                                @if($detail->delivery_status == "Completed" && $detail->delivery_status_details != "Final Approved by Admin")
                                <div class="col-sm-3">
                                <label>Extra Incentive:</label>
                                <input type="textarea" class="form" name="extraincentive" id="extraincentive">
                                </div>
                                @endif
                                @if($detail->delivery_status == "Completed" && $detail->delivery_status_details == "Final Approved by Admin")
                                <div class="col-sm-3">
                                <label>Extra Incentive:</label>
                                <span>RM {{$detail->incentive}}</span>
                                </div>
                                @endif
                        </div>

                        <div class="row">
                            <div class="col-sm-12" id="changeNoteData">
                                @foreach($note as $n)
                                    @if($n->options_Id == 0)
                                        <div>
                                            <span style="white-space:pre-wrap;" class="noteValue">{{$n->note}}</span>
                                        </div>
                                    @elseif($n->options_Id != 0)
                                        <div>
                                            <span  id='noteValue'>{{$n->Option}}</span>
                                        </div>
                                    @endif
                                @endforeach

                            </div>
                        </div>
                        @if($detail->delivery_status != "Rejected")
                        <div class="row">
                            <div class="col-sm-8"></div>
                            <div class="col-sm-4">
                                <span id="pending_update"></span>
                                <button style="float:right;" class="btn btn-sm btn-primary" id='saveBtn'>Save</button>
                            </div>
                        </div>
                        @endif
                        <div class="box-header with-border"></div>
                        <br/>
                        <div class="row">
                            <label class="control-label col-sm-2">Delivery Items: </label>
                        </div><!--row-->

                        <div class="row">
                            <div class="col-sm-12 col-lg-12">
                                <table id="deliveryTable" class="display" cellspacing="0" width="100%" padding="30px" style="font-size: 13px;">
                                    <thead>
                                        <tr>
                                            <th>No</th>
                                            <th>Categories</th>
                                            <th>Item Code</th>
                                            <th>Item Description</th>
                                            <th>Additional Description</th>
                                            <th>Item Remarks</th>
                                            <th>Unit</th>
                                            <th>Quantity Request</th>
                                            <th>Quantity Send</th>
                                            <th>Quantity Received</th>
                                            <th>Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php $i=1;?>
                                        @foreach($items as $item)
                                         <tr id='row_{{$i}}'>
                                            <td>{{$i}}</td>
                                            <td>{{$item->Categories}}</td>
                                            <td>{{$item->Item_Code}}</td>
                                            <td>{{$item->Description}}</td>
                                            <td>{{$item->add_desc}}</td>
                                            <td>{{$item->Remark}}</td>
                                            <td>{{$item->Unit}}</td>
                                            <td>{{$item->Qty_request}}</td>
                                            <td>{{$item->Qty_send}}</td>
                                            <td>{{$item->Qty_received}}</td>

                                            <td>
                                                <a class="update" id="{{$item->Id}}" desc="{{$item->add_desc}}">Update</a>
                                            @if($detail->delivery_status == "Completed" && $detail->delivery_status_details != "Final Approved by Admin")
                                                 | <a onClick="editModal({{$item->Id}},{{$i}})">Edit</a>
                                            @endif
                                            </td>
                                         </tr>
                                         <?php $i++;?>
                                         @endforeach
                                         @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div><!--row-->
                        @if($detail->delivery_status == "Completed" && $detail->delivery_status_details != "Final Approved by Admin")
                        <div class="row">
                            <div class="col-lg-6">
                                <form enctype="multipart/form-data" id='upload_form' method="post">
                                    <label>Upload Image: </label>
                                    <input type="file" id='file' multiple name="file[]" accept="image/*"/>
                                    <input type="hidden" name="form_id" value="{{$detail->Id}}">
                                    <input type="hidden" name="status_id" id="status_id">
                                    <input type="hidden" name='userid' value="{{$me->UserId}}">
                                </form>
                            </div>
                        </div>
                        @endif
                        <br>
                        <div class="row">
                            <div class="col-lg-9">
                                    <button class="btn btn-sm" style="float:left;" onclick="newItemModal()">Add</button>
                            </div>

                             <div class="col-lg-3">
                                 <span style="float:right;"><i id='updateMess' style="color:red;"></i></span>
                                 <button class="btn-sm btn btn-success" id='updateBtn' style='float:right;' onclick="update()">Update</button>
                            </div>
                        </div>
                        <div class="row">
                            <label class="control-label col-sm-2">Activity Log: </label>
                        </div>
                        <div class="row">
                            <div class="col-sm-12">
                                <table class="display" cellspacing="0" width="100%" padding="30px" style="font-size: 13px;" id="logTable">
                                    <thead>
                                        <tr>
                                            <td>No</td>
                                            <td>Name</td>
                                            <td>Status</td>
                                            <td>Activity</td>
                                            <td>Date</td>
                                            <td>Action</td>
                                        </tr>
                                    </thead>
                                    <tbody>
                                    <?php $i=1;?>
                                    @foreach($log as $l)
                                    <?php $check=false;
                                    ?>
                                        <tr>
                                            <td>{{$i}}</td>
                                            <td>{{$l->Name}}</td>
                                            <td>{{$l->delivery_status}}</td>
                                            <td>{{$l->delivery_status_details}}</td>
                                            <td>{{$l->created_at}}</td>
                                                <td>
                                                @if($view != null)
                                                    @foreach($view as $v)
                                                        @if($v->Id == $l->Id)
                                                        <a onclick ='viewModal({{$detail->Id}},{{$l->Id}},"image");'  class="btn btn-info btn-sm">View Image</a>
                                                        @endif
                                                    @endforeach

                                                @endif
                                                @if($l->latitude1 != null )
                                                    <a onclick ='viewModal({{$detail->Id}},{{$l->Id}},"time");'  class="btn btn-info btn-sm">View</a>
                                                @endif
                                            </td>
                                        </tr>
                                        <?php $i++;?>
                                    @endforeach
                                    <tbody>
                                </table>
                            </div>
                        </div>

                        <br/>
                        <div class="row">
                        @if($detail->delivery_status == "Pending" && $me->Admin )
                            <div class="col-sm-2">
                                <button class="btn btn-primary btn-sm" id="admin_accept" onclick="OpenModal({{$detail->Id}},'{{$detail->delivery_status}}','admin')">Accept</button>
                            </div>
                        @endif

                        @if($detail->delivery_status == "Pending" && $me->Admin)
                            <div class="col-sm-2">
                                <button class="btn btn-warning btn-sm" onclick="OpenModal({{$detail->Id}},'{{$detail->delivery_status}}','rejected')">Reject</button>
                            </div>
                        @endif
                        @if($detail->delivery_status == "Completed"  && $detail->delivery_status_details != "Final Approved by Admin" && $detail->delivery_status_details != "-")
                            <div class="col-sm-2">
                                <button class="btn btn-success btn-sm" onclick="OpenModal({{$detail->Id}},'{{$detail->delivery_status}}','admin')">Final Approve</button>
                            </div>
                        @endif
                        @if($detail->delivery_status == "Processing" && $detail->delivery_status_details == "Driver Transfer Trip")
                        <div class="col-sm-2">
                            <a class="btn btn-primary btn-sm" onclick="OpenModal({{$detail->Id}},'{{$detail->delivery_status}}','transfer')">Transfer Driver</a>
                        </div>
                        @endif
                        @if($detail->delivery_status == "Processing" && $detail->delivery_status_details == "Insufficient stock")
                        <div class="col-sm-2">
                            <a class="btn btn-primary btn-sm" onclick="OpenModal({{$detail->Id}},'{{$detail->delivery_status}}','proceed')">Accept</a>
                        </div>
                        <div class="col-sm-2">
                            <a class="btn btn-warning btn-sm" onclick="OpenModal({{$detail->Id}},'{{$detail->delivery_status}}','bounce back')">Reject</a>
                        </div>
                        @endif
                            <div class="col-sm-2">
                                <a class="btn btn-info btn-sm" href="{{url('/deliveryapproval')}}">Back</a>
                            </div>

                        </div>
                    </div>
                </div>
            </div>
        </div><!--row-->

        <div class="modal fade" id="approvalModal"  role="dialog" aria-labelledby="myModalLabel">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                        <h4 class="modal-title" id="myModalLabel">Approval</h4>
                    </div>
                    <div class="row">
                        <div class="col-sm-4">
                            <span style="color:red;" id="err_mess"></span>
                        </div>
                    </div>
                    <div class="form-group" padding="10px">
                        <label id="approvalMess"></label>
                    </div>
                    <div id="selection"></div>
                    <div class="modal-footer" id="addBtn">
                        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                    </div>
                </div>
            </div>
        </div>
        @if($detail->delivery_status == "Completed")
        <div class="modal fade" id="editModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
            <div class="modal-dialog modal-md" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                        <h4 class="modal-title" id="myModalLabel">Edit</h4>
                    </div>
                    <div class="col-sm-12">
                        <br>
                        <div class='row'>
                            <div class="form-group">
                                <label class="col-sm-2">Item Code: </label>
                                <div class="col-sm-4">
                                    <select id='item_code' class='form-control'>
                                        @foreach($options as $option)
                                        <option value="{{$option->Id}}" data-category='{{$option->Categories}}'
                                            data-description='{{$option->Description}}' data-unit='{{$option->Unit}}'
                                            data-remark="{{$option->Remark}}"
                                            >{{$option->Item_Code}}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                        </div>
                        <br>
                        <div class="row">
                            <div class="form-group">
                                <label class="col-sm-2">Category:</label>
                                <div class="col-sm-4">
                                    <input type="text" class="form-control" disabled  id='category'>
                                </div>
                            </div>
                        </div>
                        <br>
                        <div class="row">
                            <label class="col-sm-2">Item Unit:</label>
                            <div class="col-sm-4">
                                <input type="text" class="form-control" disabled id='unit'>
                            </div>
                        </div>
                        <br>
                        <div class="row">
                            <label class="col-sm-2">Item Remarks:</label>
                            <div class="col-sm-4">
                                <input type="text" class="form-control" disabled id='remark'>
                            </div>
                        </div>
                        <br>
                        <div class="row">
                            <label class="col-sm-2">Quantity Request:</label>
                            <div class="col-sm-4">
                                <input type="number" class="form-control" id="qty_request">
                            </div>
                        </div>
                        <br>
                        <div class="row">
                            <label class="col-sm-2">Quantity Send:</label>
                            <div class="col-sm-4">
                                <input type="number" class="form-control" id="qty_send">
                            </div>
                        </div>
                        <br>
                        <div class="row">
                            <div class="form-group">
                                <label class="col-sm-2">Quantity Received:</label>
                                <div class="col-sm-4">
                                    <input type="number" class="form-control" id="qty_received">
                                </div>
                            </div>
                        </div>
                        <input type="hidden" id='deliveryId'/>
                        <input type='hidden' id='rowNum'/>
                        <input type="hidden" id='description'>
                    </div>
                    <div class="modal-footer">
                        <button class="btn btn-default" onclick="edit()">Edit</button>
                        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                    </div>
                </div>
            </div>
        </div>

        <!--add item modal-->
        <div class="modal fade" id="newModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
            <div class="modal-dialog modal-md" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                        <h4 class="modal-title" id="myModalLabel">Add</h4>
                    </div>
                    <div class="col-sm-12">
                        <div class="row"  style="color:red;">
                            <div class="col-sm-6" id="err_mess">

                            </div>
                        </div>
                        <br>
                        <div class='row'>
                            <div class="form-group">
                                <label class="col-sm-2">Item Code: </label>
                                <div class="col-sm-4">
                                    <select id='newitem_code' class='form-control'>
                                        <option   selected>Please Select</option>
                                        @foreach($options as $option)
                                        <option value="{{$option->Id}}" data-category='{{$option->Categories}}'
                                            data-description='{{$option->Description}}' data-unit='{{$option->Unit}}'
                                            data-remark="{{$option->Remark}}"
                                            >{{$option->Item_Code}}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                        </div>
                        <br>
                        <div class="row">
                            <div class="form-group">
                                <label class="col-sm-2">Category:</label>
                                <div class="col-sm-4">
                                    <input type="text" class="form-control" disabled  id='newcategory'>
                                </div>
                            </div>
                        </div>
                        <br>
                        <div class="row">
                            <label class="col-sm-2">Item Unit:</label>
                            <div class="col-sm-4">
                                <input type="text" class="form-control" disabled id='newunit'>
                            </div>
                        </div>
                        <br>
                        <div class="row">
                            <label class="col-sm-2">Item Remarks:</label>
                            <div class="col-sm-4">
                                <input type="text" class="form-control" disabled id='newremark'>
                            </div>
                        </div>
                        <br>
                        <div class="row">
                            <label class="col-sm-2">Quantity Request:</label>
                            <div class="col-sm-4">
                                <input type="number" class="form-control" id="newqty_request">
                            </div>
                        </div>
                        <br>
                        <div class="row">
                            <label class="col-sm-2">Quantity Send:</label>
                            <div class="col-sm-4">
                                <input type="number" class="form-control" id="newqty_send">
                            </div>
                        </div>
                        <br>
                        <div class="row">
                            <div class="form-group">
                                <label class="col-sm-2">Quantity Received:</label>
                                <div class="col-sm-4">
                                    <input type="number" class="form-control" id="newqty_received">
                                </div>
                            </div>
                        </div>
                        <input type="hidden" id='newdescription'>
                    </div>
                    <div class="modal-footer">
                        <button class="btn btn-default" onclick="addItem()">Add</button>
                        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                    </div>
                </div>
            </div>
        </div>
        @endif

        <!--edit condition modal -->
        <div class="modal fade" id="conditionModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
            <div class="modal-dialog modal-lg" role="document">
                <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                    <h4 class="modal-title" id="myModalLabel">Edit condition</h4>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-lg-12">
                            <span style="color:red;" id="conErr"></span>
                            <span style="color:green;" id="conSucc"></span>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-lg-12">
                            <label class="col-lg-2">Condition: </label>
                            <div class="col-lg-10" id="insertCondition">
                                @foreach($deliverycond as $dc)
                                    <div class="row" id="addCondition{{$dc->Id}}">
                                        <span id="addCond{{$dc->Id}}" class="col-lg-6">{{$dc->Option}}</span>
                                        <a class="btn btn-xs btn-primary col-lg-2" onclick="editCondition1({{$dc->Id}},{{$dc->optionId}})">Edit</a>
                                        <a class="btn btn-xs btn-warning col-lg-2" onclick="removeCondition({{$dc->Id}})">Remove</a>
                                    </div>
                                    <br>
                                @endforeach
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-lg-3">

                        </div>
                    </div>
                </div>

                <div class="modal-footer">
                    <button class="btn btn-primary btn-sm col-lg-2" onclick="addCondition()">Add Condition</button>
                    <span id="condMess" style="color:red;"></span>
                    <button class="btn btn-primary" onclick="saveCondition()">Save</button>
                    <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                </div>
                </div>
            </div>
        </div>
        <div class="modal fade" id="noteModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
            <div class="modal-dialog modal-lg" role="document">
                <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                    <h4 class="modal-title" id="myModalLabel">Edit Note</h4>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-lg-12">
                            <span style="color:red;" id="noteErr"></span>
                            <span style="color:green;" id="noteSucc"></span>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-lg-12">
                            <label class="col-lg-2">Note: </label>
                            <div class="col-lg-10" id="insertNote">
                            @foreach($note as $not)
                                <div class="row" id="addNote{{$not->Id}}">
                                    @if($not->options_Id == 0)
                                    <span style="white-space:pre-wrap;" id="addNot{{$not->Id}}" class="col-lg-6">{{$not->note}}</span>
                                    <a class="btn btn-xs btn-primary col-lg-2" onclick="editNote({{$not->Id}},{{$not->options_Id}})">Edit</a>
                                    <a class="btn btn-xs btn-warning col-lg-2" onclick="removeNote1({{$not->Id}})">Remove</a>
                                    @elseif($not->options_Id != 0)
                                    <span id="addNot{{$not->Id}}" class="col-lg-6">{{$not->Option}}</span>
                                    <a class="btn btn-xs btn-primary col-lg-2" onclick="editNote({{$not->Id}},{{$not->options_Id}})">Edit</a>
                                    <a class="btn btn-xs btn-warning col-lg-2" onclick="removeNote1({{$not->Id}})">Remove</a>
                                    @endif
                                </div>
                                <br>
                            @endforeach
                            </div>
                        </div>
                    </div>

                    <div class="row">

                        <div class="col-lg-3">

                        </div>
                    </div>
                </div>

                <div class="modal-footer">
                    <div class="col-lg-6">
                        <div class="col-lg-4">
                            <select class="form-control input-sm" id='noteOption'>
                                <option value="">Select Type</option>
                                <option value="text">Text</option>
                                <option value="option">Option</option>
                            <select>
                        </div>

                        <button class="btn btn-primary btn-xs col-lg-2" onclick="addNoteRow()">Add row</button>
                    </div>
                    <span id="noteMess" style="color:red;"></span>
                    <button class="btn btn-primary" onclick="saveNote()">Save</button>
                    <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                </div>
                </div>
            </div>
        </div><!--Edit Note Modal-->

        <div class="modal fade" id="timeModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
            <div class="modal-dialog modal-lg" role="document">
                <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                    <h4 class="modal-title" id="timeTitle" class="delete"></h4>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-lg-12">
                            <span style="color:red;" id="timeErr"></span>
                            <span style="color:green;" id="timeSucc"></span>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-lg-12">
                            <label class="col-lg-2 delete" id="timeLabel"></label>
                            <div class="col-lg-4 delete" id="time">
                            </div>
                        </div>
                    </div>
                    <br>
                    <div class="row">
                        <div class="col-lg-12 delete" id="timeremarks">

                        </div>
                    </div>
                    <div class="row">
                        <div class="col-lg-12 delete" id="timelog">

                        </div>
                    </div>
                </div>

                <div class="modal-footer">
                    <span id="condMess" style="color:red;"></span>
                    <button class="btn btn-primary"   id="saveType">Save</button>
                    <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                </div>
                </div>
            </div>
        </div><!--time modal-->
        <!--View Item Modal-->
        <div class="modal fade" id="viewModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
            <div class="modal-dialog modal-lg" role="document">
                <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                    <h4 class="modal-title" id="myModalLabel">View</h4>
                </div>
                <div class="col-lg-12">
                    <div class="row">
                        <div class="col-sm-12" id='addTable'>

                        </div>
                    </div>
                    <div class="row">
                        <div class="col-sm-4">
                            <span style="color:green;" id='successMess'></span>
                        </div>
                    </div>
                    <div class="row" id='imgL'>
                        <div class="col-sm-4">
                            <label>Images: </label>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-sm-12" id="imgdata"></div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                </div>
                </div>
            </div>
        </div>
        @if($detail->delivery_status == "Processing" && $detail->delivery_status_details == "Driver Transfer Trip")
        <div class="modal fade" id="transferModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
            <div class="modal-dialog modal-md" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                        <h4 class="modal-title" id="myModalLabel">Transfer Trip</h4>
                    </div>
                    <div class="col-sm-12">
                        <div class="row"  style="color:red;">
                            <div class="col-sm-6" id="err_mess">

                            </div>
                        </div>

                        <br>

                        <div class="row">
                            <div class="form-group">
                                <label class="col-sm-3">Vehicle No: </label>
                                <div class="col-sm-4">
                                    <select id="driverid" class="form-control">
                                        <option value="">Please Select</option>
                                        @foreach ($lorry as $l)
                                            @if($detail->DriverId != $l->UserId)
                                                <option value="{{$l->UserId}}">{{$l->Vehicle_No}}</option>
                                            @endif
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                        </div>

                        <br>

                        <div class="row">
                            <div class="form-group">
                                <label class="col-sm-3">Driver Name: </label>
                                <div class="col-sm-4">
                                    <input type="text" disabled class="form-control">
                                    <p><a href="" class="col-sm-2">Edit</a></p>
                                </div>
                            </div>
                        </div>

                        <br>

                    </div>
                    <div class="modal-footer">
                        <button class="btn btn-default" onclick="transfer({{$detail->Id}},'transfer')">Transfer</button>
                        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                    </div>
                </div>
            </div>
        </div>
        @endif
        <div class="modal fade" id="mapModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
            <div class="modal-dialog" role="document">
                    <div class="modal-content">
                    <div class="modal-header">
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                            <h4 class="modal-title" id="myModalLabel">Map</h4>
                    </div>
                    <div id="map"></div>
                    <div class="modal-footer" id="addBtn">
                            <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                    </div>
                    </div>
            </div>
        </div>

        <script>
            function initMap() {

                var mapOptions = {
                    center: new google.maps.LatLng(0,0),
                    zoom: 12,
                    mapTypeId: google.maps.MapTypeId.ROADMAP
                };
                map = new google.maps.Map(document.getElementById("map"), mapOptions);

            }

            function myfunction(latitude, longitude){
                $('#mapModal').modal('show');
                map.setZoom(12);
                map.setCenter(new google.maps.LatLng(latitude, longitude));
                var marker= new google.maps.Marker({
                    position: new google.maps.LatLng(latitude, longitude),
                    map: map,
                    icon:"{{ asset('img/map-marker-icon.png') }}"
                });
                marker.setMap(map);
            }
        </script>
        <script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyDuDdIs9T09hGo4LV3dRSiuHVMbUnkS-JE&libraries=places&callback=initMap" async defer></script>

    </section>

</div>

<footer class="main-footer">
    <div class="pull-right hidden-xs">
        <b>Version</b> 1.0.0
    </div>
    <strong>Copyright &copy; 2014-2016 <a href="http://www.softoya.com">TrackerOnTheGo</a>.</strong> All rights reserved.
</footer>

<script type="text/javascript">
const save=[];
var newItem=[];
var companyId;

$(function(){
    @if($detail->Name == null && $detail->Company_Name == null)
    $('#admin_accept').attr('disabled',true);
    @endif

    /*Company*/
    $('#edit_company').click(function(){
        $('#selection').empty();
        $('#selection').append('<select id="company" class="form-control select2"><option value="" selected disabled >None</option>'+
        '</select>');
        $.each(company, function(key, value) {
            $('#company').append($("<option></option>")
            .attr("value",value.id)
            .text(value.name+" - "+value.code));
        });
        $(".select2").select2({
            tags: true,
            width:'100%'
        });
    });
    $('#add_company').click(function(){
        $('#selection').append('<select id="company" class="form-control select2"><option value="" selected disabled >None</option>'+
        '</select>');
        $.each(company, function(key, value) {
            $('#company').append($("<option></option>")
            .attr("value",value.id)
            .text(value.name+" - "+value.code));
        });
        $(".select2").select2({
            tags: true,
            width:'100%'
        });
        $('#add_company').remove();
    });
    var checkVehicle=false,checkCompany=false,checkDriver=false;
    $('#saveBtn').click(function(){
        var v=document.getElementById("vehicle");
        var c=document.getElementById("company");
        var d=document.getElementById("driverName");
        checkCompany={{$detail->Company_Name == null ? "false":"true"}};
        if($('#vehicle').val() != null && $('#vehicle').val() != "" && v != null && !checkVehicle)
        {
            checkVehicle=true;
        }
        if($('#company').val() != null && $('#company').val() != "" && c != null && !checkCompany)
        {
            checkCompany=true;
            companyId=$('#company').val();
        }
        if($('#driverName').val() != null && $('#driverName').val() != "" && d != null && !checkDriver)
        {
            checkDriver=true;
        }

            $('#saveBtn').attr('disabled',true);
            $.ajaxSetup({
                headers: { 'X-CSRF-Token' : $('meta[name=_token]').attr('content') }
            });
            $.ajax({
            url:"{{ url('/savePendingDelivery')}}",
            method:"post",
            data:{
                "id":{{$detail->Id}},
                'incentive':$('#extraincentive').val(),
                'triptype':$('#triptype').val(),
                'vehicle':$('#vehicle').val(),
                'company':$('#company').val(),
                'driver':$('#driverName').val(),
                'deliverystatus':'{{$detail->delivery_status}}',
                'details':'{{$detail->delivery_status_details}}'
            },
            success:function(result){
                if(result == 1)
                {
                    var message="Details has been updated.";
                    $("#update-alert ul").html(message);
                    $("#update-alert").modal('show');
                    $("#updateBtn").prop('disabled',false);
                    $('#saveBtn').attr('disabled',false);
                    setTimeout(function(){
                        $("update-alert").modal('hide');
                    }, 2000);
                    var vid=$('#vehicle').val();
                    var did=$('#driverName').val();
                    $('#admin_accept').attr('disabled',false);
                    if(!checkCompany)
                        $('#selection').html($('#company option:selected').text());
                    // $('#driverDetail').html($('#driverName option:selected').text());
                    // $('#driverDetail').after("&nbsp;<a class='editDriver btn btn-xs btn-success'  id='assignDriver' onclick='assignDriver("
                    // + did
                    // +")'>Assign</a>");
                    if(checkVehicle)
                    {
                        $('#chgV').html($('#vehicle option:selected').text());
                        $('#chgV').after('&nbsp;<a id="changeVehicle" class="btn btn-xs btn-success" onclick="chgVehicle(' + vid
                        + ')">Change</a>');
                    }

                }
                 else{
                var message="The company and vehicle is required.";
                $("#warning-alert ul").html(message);
                $("#warning-alert").modal('show');
                $('#saveBtn').attr('disabled',false);
                }
            }
        });
    });
    $('#item_code').on('change',function(){
        var element=$(this).find('option:selected');
        $('#remark').val(element.data('remark'));
        $('#category').val(element.data('category'));
        $('#unit').val(element.data('unit'));
        $('#description').val(element.data('description'));
    });
    $('#newitem_code').on('change',function(){
        var element=$(this).find('option:selected');
        $('#newremark').val(element.data('remark'));
        $('#newcategory').val(element.data('category'));
        $('#newunit').val(element.data('unit'));
        $('#newdescription').val(element.data('description'));
    });
    $('#file').on('change',function(){
        if($('#file').length == 0)
        {

        }
        else{
            $('#updateMess').html("Please click update button to save the information.");
        }
    });
});
function remove(rows)
{
    $('#row_' + rows).remove();
    var filter=newItem.filter(x=>{return x.id != rows});
    newItem=filter;
    if((newItem == undefined && save != undefined) || (newItem.length == 0 && save.length == 0)){
        $('#updateMess').html("");
    }
}
function updateTable(rows,cols,content)
{
    var update=document.getElementById('deliveryTable').rows[parseInt(rows,10)].cells;
    update[parseInt(cols,10)].innerHTML=content;
}
function editModal(id,row)
{
    let chg = save.find(e => e.id == id);
    if(chg  == undefined || chg.length == 0){
        $.ajax({
            url:"{{ url('/deliveryitemdetails')}}",
            method:"get",
            data:{
                "id":id,
            },
            success:function(result){
                $('#item_code').val(result.item.Id);
                $('#item_code').change();
                $('#remark').val(result.item.Remark);
                $('#category').val(result.item.Categories);
                $("#unit").val(result.item.Unit);
                $('#qty_request').val(result.item.Qty_request);
                $('#qty_received').val(result.item.Qty_received);
                $('#qty_send').val(result.item.Qty_send);
                $("#deliveryId").val(id);
                $('#rowNum').val(row);
                $('#editModal').modal('show');
            }
        });
    }
    else{
        $('#item_code').val(chg.inventoryId);
        $('#item_code').change();
        $('#qty_request').val(chg.Qty_request);
        $('#qty_received').val(chg.Qty_received);
        $('#qty_send').val(chg.Qty_send);
        $("#deliveryId").val(id);
        $('#rowNum').val(row);
        $('#editModal').modal('show');
    }

}

function newItemModal()
{
    $('#newitem_code').val("");
    $('#newitem_code').change();
    $("#newqty_send").val("");
    $("#newqty_request").val("");
    $("#newqty_received").val("");
    $('#newModal').modal('show');
}
var tablerows = document.getElementById('deliveryTable').getElementsByTagName("tr").length;
function addItem()
{
    var err=false;
    $('#err_mess').empty();
    if($("#newitem_code").val() == null)
    {
        $('#err_mess').append("Item code is empty.<br>");
        err=true;
    }
    if($('#newqty_request').val() == ""){
        $('#err_mess').append("Quantity request is empty.<br>");
        err=true;
    }
    if($('#newqty_send').val() == ""){
        $('#err_mess').append("Quantity send is empty.<br>");
        err=true;
    }
    if($('#newqty_received').val() == ""){
        $('#err_mess').append("Quantity received is empty.<br>");
        err=true;
    }
    if(!err){
        $('#deliveryTable tr:last').after("<tr id='row_"+ tablerows +"'><td>" + tablerows + "</td><td>" + $('#newcategory').val() + "</td><td>" + $('#newitem_code option:selected').text() +
            "</td><td>" + $('#newdescription').val() + "</td><td>" + $('#newremark').val() + "</td><td>" +
            $('#newunit').val() + "</td><td>" + $('#newqty_request').val() + "</td><td>" + $('#newqty_send').val() + "</td><td>" +
                $('#newqty_received').val() + "</td><td><a onclick='remove(" + tablerows + ")'>Remove</a></td></tr>");

        var temp={
            'id':tablerows,
            'inventoryId':$('#newitem_code').val(),
            'qty_request':$('#newqty_request').val(),
            'qty_send':$('#newqty_send').val(),
            'qty_received':$('#newqty_received').val(),
            'formId':{{$detail->Id}},
        };
        tablerows+=1;
        checkArray(newItem,temp);
        if(newItem != undefined || newItem.length != 0)
        {
            $('#updateMess').html("Please click update button to save the information.");
        }
        $('#newModal').modal('hide');
    }
}

function checkArray(arr, obj) {
    const index = arr.findIndex((e) => e.id === obj.id);
    if (index === -1) {
        arr.push(obj);
    } else {
        arr[index] = obj;
    }
}
function removeImg(id)
{
    if(confirm('Are you sure you want to delete this image?'))
    {
        $.ajaxSetup({
            headers: { 'X-CSRF-Token' : $('meta[name=_token]').attr('content') }
        });
        $.ajax({
            url:"{{ url('/deleteDeliveryImage')}}",
            method:"post",
            data:{
                "id":id,
            },
            success:function(result){
                if(result == 1)
                {
                    $("#successMess").html('Image has been deleted.');
                    $('#image'+id).remove();
                    setTimeout(function(){
                        $("#successMess").html("");
                    }, 2000);
                }
            }
        });
    }
    else{}

}
function edit()
{
    var temp={
        'id':$("#deliveryId").val(),
        'inventoryId':$('#item_code').val(),
        'Qty_request': parseInt($('#qty_request').val()),
        'Qty_send':parseInt($("#qty_send").val()),
        'Qty_received':parseInt($('#qty_received').val()),
    };
    var row=$('#rowNum').val();
    updateTable(row,1,$('#category').val());
    updateTable(row,2,$('#item_code option:selected').text());
    updateTable(row,3,$('#description').val());
    updateTable(row,4,$('#remark').val());
    updateTable(row,5,$('#unit').val());
    updateTable(row,6,parseInt($('#qty_request').val()));
    updateTable(row,7,parseInt($('#qty_send').val()));
    updateTable(row,8,parseInt($('#qty_received').val()));
    $('#editModal').modal('hide');
    checkArray(save,temp);
    if(save != undefined || save.length != 0)
        $('#updateMess').append("Please click update button to save the information.");
}
function update()
{
    var img=document.getElementById('file');

    var store=[];
    if( newItem.length != 0 || save.length != 0)
    {
        store.push({
            'new':newItem,
            'edit':save
        });
        $("#updateBtn").prop('disabled',true);
    }


    if(store.length != 0){
        $("#updateBtn").prop('disabled',true);
        $.ajaxSetup({
            headers: { 'X-CSRF-Token' : $('meta[name=_token]').attr('content') }
        });
        $.ajax({
            url:"{{ url('/updateDeliveryItem')}}",
            method:"post",
            data:{
                "data":store,
                'formid':{{$detail->Id}},
                'userid':{{$me->UserId}}
            },
            success:function(result){
                $('#status_id').val(result.status_id);
                if(img.length != 0)
                    uploadImg();
                if(result.response == 1)
                {
                    var message="Details has been updated.";
                    $("#update-alert ul").html(message);
                    $("#update-alert").modal('show');
                    $("#updateBtn").prop('disabled',false);
                    setTimeout(function(){
                        $("update-alert").modal('hide');
                        window.location.reload();
                    }, 3000);

                }
            }
        });
    }
    else if($("#status_id").val() == "" && img.value.length > 1){
        if(img.length != 0)
            uploadImg();
    }
    else{
        var message="Nothing to update.";
        $("#warning-alert ul").html(message);
        $("#warning-alert").modal('show');
    }

}
function uploadImg()
{
    $.ajaxSetup({
        headers: { 'X-CSRF-Token' : $('meta[name=_token]').attr('content') }
    });
    $.ajax({
        url:"{{ url('/uploadDeliveryImage')}}",
        method:"post",
        contentType: false,
        processData: false,
        data:new FormData($("#upload_form")[0]),
        success:function(result){
            if(result == 1){
                var message="Image has been uploaded.";
                $("#update-alert ul").html(message);
                $("#update-alert").modal('show');
                $("#updateBtn").prop('disabled',false);
                setTimeout(function(){
                    $("update-alert").modal('hide');
                    window.location.reload();
                }, 3000);
            }
        }
    });
}
function viewModal(id,statusId,type)
{
    $("#imgdata").empty();
    $('#imgL').hide();
    $('#addTable').empty();
    $.ajax({
        url:"{{ url('/getDeliveryDetails')}}",
        method:"get",
        data:{
            "id":id,
            "statusid":statusId
        },
        success:function(result){
            if(result.details.length != 0 && type == "time")
            {
                $('#addTable').append("<table class='display table-bordered' cellspacing='0' width='100%' padding='30px' style='font-size:13px;' id='detailsTable'>"+
                "<thead><tr><th>No</th><th>Location</th><th>Time In</th></tr></thead>" +
                "<tbody id='driverDetails'></tbody>" +
                "</table>");

                for(var x=0;x<result.details.length;x++)
                {
                    $('#driverDetails').append('<tr><td>'+(x+1)+'</td><td><a onclick="myfunction('+result.details[x].latitude1+','+
                        result.details[x].longitude1+')">'+result.details[x].latitude1+' , '+
                    result.details[x].longitude1 +
                    '</td><td>'+
                    result.details[x].created_at+'</td></tr>');
                }
            }
            $('#detailsTable').DataTable();

            if(result.img.length != 0 && type == "image")
            {
                $('#imgL').show();
                for(var x=0;x<result.img.length;x++)
                {
                    $('#imgdata').append("<div class='img-wrap' id='image" + result.img[x].Id + "'><span class='close'><a onclick='removeImg("+ result.img[x].Id +")'>&times;</a></span><a data-fancybox='gallery' href='" + result.img[x].Web_Path +"'><img width='100' height='100' src='"+
                    result.img[x].Web_Path +"'></img></a></div>");
                }
            }

        }
    });
    $('#viewModal').modal('show');
}
var company=[];
@foreach($company as $c)
company.push({
    "name":"{{$c->Company_Name}}",
    "code":"{{$c->Company_Code}}",
    "id":{{$c->Id}}
});
@endforeach
var vehicleData=[];
@foreach($lorry as $l)
vehicleData.push({
    'name':'{{$l->Vehicle_No}}',
    'id':'{{$l->Id}}'
});
@endforeach
var driverData=[];
@foreach($driver as $d)
driverData.push({
    'name':'{{$d->Name}}',
    'id':'{{$d->Id}}'
});
@endforeach
function OpenModal(id,status,type)
{

    var set="updateStatus("+id+",'"+status+"','"+type+"')";
    $("#err_mess").html("");

    if(type == 'rejected')
    {
        $('#approvalMess').empty();
        $('#removeBtn').remove();
        $('#deleteR').remove();
        $("#addBtn > .btn").after('<button class="btn btn-warning" id="removeBtn" onclick="'+set+'")">Reject</button>');
        $('#approvalMess').after('<div class="row" id="deleteR"><label class="col-sm-3">Reason: </label><div class="col-sm-8"><textarea id="remarks" cols="3" rows="3" class="form-control"></textarea></div></div>')
        $('#approvalModal').modal('show');
    }
    else if(type == "admin" && status == "Completed"){
        $("#removeBtn").remove();
        $("#approvalMess").empty();
        $('#deleteR').remove();
        $( "#approvalMess" ).html("&nbsp;&nbsp;&nbsp; Are you sure you want to final approve?");
        $("#addBtn > .btn").after('<button class="btn btn-primary" id="removeBtn" onclick="'+set+'")">Approve</button>');
        $('#approvalModal').modal('show');
    }
    else if(type == "transfer"){

        $('#transferModal').modal('show');
    }
    else if(type == 'proceed'){
        $("#removeBtn").remove();
        $("#approvalMess").empty();
        $('#deleteR').remove();
        $( "#approvalMess" ).html("&nbsp;&nbsp;&nbsp; Are you sure you want to proceed?");
        $("#addBtn > .btn").after('<button class="btn btn-primary" id="removeBtn" onclick="'+set+'")">Proceed</button>');
        $('#approvalModal').modal('show');
    }
    else if(type == "bounce back"){
        $("#removeBtn").remove();
        $("#approvalMess").empty();
        $('#deleteR').remove();
        $( "#approvalMess" ).html("&nbsp;&nbsp;&nbsp; Are you sure you want to reject?");
        $("#addBtn > .btn").after('<button class="btn btn-warning" id="removeBtn" onclick="'+set+'")">Reject</button>');
        $('#approvalModal').modal('show');
    }
    else
    {
        $('#admin_accept').attr('disabled',true);
        $("#removeBtn").remove();
        $("#approvalMess").empty();
        $('#deleteR').remove();
        $( "#approvalMess" ).html("&nbsp;&nbsp;&nbsp; Are you sure you wish to approve?");
        $("#addBtn > .btn").after('<button class="btn btn-primary" id="removeBtn" onclick="'+set+'")">Approve</button>');
        $('#approvalModal').modal('show');
    }
}
@if($detail->delivery_status == "Processing" && $detail->delivery_status_details == "Driver Transfer Trip")
function transfer(id,type)
{
    $('#transferModal').modal('hide');
    $.ajaxSetup({
        headers: { 'X-CSRF-Token' : $('meta[name=_token]').attr('content') }
    });
    $.ajax({
        url:"{{ url('/changeDeliveryStatus')}}",
        method:"post",
        data:{
            "id":id,
            "type":type,
            "driverid":$('#driverid').val(),
            "olddriverid":{{$detail->DriverId}}
        },
        success:function(result){
            if(result == 1){
                var message="Status has been updated.";
                $("#update-alert ul").html(message);
                $("#update-alert").modal('show');
                setTimeout(function(){
                    $("update-alert").modal('hide');
                    window.location.reload();
                }, 6000);
            }
            else{

            }
        }
    });
}
@endif

function updateStatus(id,status,type)
{
    var err=false;
    if(type == "rejected")
    {
        if($('#remarks').val() == ""){
            $("#err_mess").html("&nbsp;&nbsp;&nbsp;Reason is required");
            $('#approvalModal').modal('show');
            err=true;
        }
    }
    else{
        $('#approvalModal').modal('hide');
    }
    if(!err){

        $.ajaxSetup({
             headers: { 'X-CSRF-Token' : $('meta[name=_token]').attr('content') }
        });
        console.log(type)
        $.ajax({
            url:"{{ url('/changeDeliveryStatus')}}",
            method:"post",
            data:{
                "id":id,
                'vehicle':{{$detail->roadtax ? : 0}},
                'incentive':$('#extraincentive').val(),
                'triptype':$('#triptype').val(),
                "status":status,
                "type":type,
                'do':<?php if(isset($detail->DO_Id))echo $detail->DO_Id?><?php else echo 0;?>,
                'remarks':$('#remarks').val(),
                'company': @if($detail->companyId != null) {{$detail->companyId}} @else companyId @endif
            },
            success:function(result){
                if(result == 1){
                    var message="Status has been updated.";
                    $("#update-alert ul").html(message);
                    $("#update-alert").modal('show');
                    setTimeout(function(){
                        $("update-alert").modal('hide');
                        window.location.reload();
                    }, 6000);
                }
                else{}
            }
        });
    }
}
    function assignDriver(id)
    {
        $('#driverDetail').empty();
        $('#driverDetail').append('<select id="driverName" class="form-control select2"><option value="" selected disabled >None</option>'+
        '</select>');

        $.each(driverData, function(key, value) {
            $('#driverName').append($("<option></option>")
            .attr("value",value.id)
            .text(value.name));
        });
        $('#driverName').val(id).change();
        $(".select2").select2({
            tags: true,
            width:'100%'
        });
        $('#assignDriver').remove();
    }
    function chgVehicle(id)
    {
        $('#chgV').empty();

        $('#chgV').append('<select id="vehicle" class="form-control select2"><option value="" selected disabled >None</option>'+
        '</select>');
        $.each(vehicleData, function(key, value) {
            $('#vehicle').append($("<option></option>")
            .attr("value",value.id)
            .text(value.name));
        });
        $('#vehicle').val(id).change();
        $(".select2").select2({
            width:'100%'
        });

        $('#changeVehicle').remove();
    }
    /*Condition*/
    function editCondition()
    {
        $('#conditionModal').modal('show');
    }
    var x=1,countRow=1;
    var condOp=[];
    var storeCond=[];
    @foreach($condOption as $op)
        condOp.push({
            'name':'{{$op->Option}}',
            'id':'{{$op->Id}}'
        });
    @endforeach
    function addCondition()
    {
        $('#insertCondition').append('<div class="row removeCond" id="cond' + x + '"><div class="col-sm-8"><select id="condition'+ x +
        '" class="form-control select2"><option value="" selected disabled >None</option>'+
        '</select></div><a class="col-sm-2 btn btn-xs btn-info" onclick="addCond(' + x + ')">Add</a>\
        <a class="col-sm-2 btn btn-xs btn-danger" onclick=" removeCond('+x+')">Remove</a>\
        </div>');

        $.each(condOp, function(key, value) {
            $('#condition' + x).append($("<option></option>")
            .attr("value",value.id)
            .text(value.name));
        });
        $(".select2").select2({
            tags: true,
            width:'100%'
        });
        x++;
    }
    function addCond(id)
    {
        var condValue=$("#condition" + id + " option:selected").text();
        storeCond.push({
            "no":countRow,
            "id":$("#condition" + id + " option:selected").val(),
            'option':$("#condition" + id + " option:selected").text()
        });
        $('#cond' + id).empty();
        $('#cond' + id).append("<span class='col-lg-6'>" + condValue + "</span> <a class='btn btn-xs btn-warning col-lg-2' onclick='removeCond(" + id + ")'> &times; Remove</a>");
        countRow++;
        $('#condMess').html("Please click save button to save conditions");
    }

    function removeCond(row)
    {
        var filter=storeCond.filter(x=>{return x.no != row});
        storeCond=filter;
        $('#cond' + row).remove();
        if(storeCond.length == 0)
            $('#condMess').html('');
    }
    var editC=[];
    var removeC=[];
    function saveCondition()
    {
        if(storeCond.length != 0 || editC.length !=0 || removeC.length != 0)
        {
            $.ajaxSetup({
                headers: { 'X-CSRF-Token' : $('meta[name=_token]').attr('content') }
            });

            $.ajax({
                url:"{{ url('deliverymangement/saveCondition')}}",
                method:"post",
                data:{
                    'formid':{{$detail->Id}},
                    'condId':storeCond,
                    'editcond':editC,
                    'remove':removeC
                },
                success:function(result){
                    storeCond=[];
                    editC=[];
                    removeC=[];
                    $('#condMess').html('');
                    $('#chgCond').empty();
                    $('.removeCond').remove();
                    if(result.length != 0)
                    {
                        for(var x=0;x<result[0].obj.length;x++)
                        {
                            $('#insertCondition').append('<div class="row" id="addCondition'+result[0].obj[x].id+'"><span class="col-lg-6" id="addCond' +
                            result[0].obj[x].id+ '">' +
                            result[0].obj[x].option + '</span><a class="btn btn-xs btn-primary col-lg-2" onclick="editCondition1('+result[0].obj[x].id+',' + result[0].obj[x].cid +
                            ')"> Edit</a> <a class="btn btn-xs btn-warning col-lg-2" onclick="removeCondition('+result[0].obj[x].id +')"> Remove</a></div>');
                        }
                        //readd in delivery details page
                        if(result[result.length-1].data.length != null)
                        {
                            for(var x=0;x<result[result.length-1].data.length;x++)
                            {
                                $('#chgCond').append('<div><span>' + (x+1) + ') ' + result[result.length-1].data[x].Option +'</span></div>')
                            }
                        }
                    }
                    if(result.length > 0){
                        var message="Condition updated.";
                        $('#conSucc').html(message);
                        setTimeout(function(){
                            $('#conSucc').html('');
                        }, 3000);
                    }
                    else{

                    }
                }
            });
        }
        else
        {
            $('#conErr').html('Please add condition.');
            setTimeout(function(){
                $('#conErr').html('');
            }, 3000);
        }

    }//savecondition function

    function editCondition1(id,condId)
    {
        $('#addCondition' + id).empty();
        $('#addCondition' + id).append('<div class="col-sm-8"><select id="condition'+ id +
        '" class="form-control select2"><option value="" selected disabled >None</option>'+
        '</select></div><a class="col-sm-2 btn btn-xs btn-info" onclick="editCond(' + id + ')">Edit</a>');

        $.each(condOp, function(key, value) {
            $('#condition' + id).append($("<option></option>")
            .attr("value",value.id)
            .text(value.name));
        });
        $('#condition' + id).val(condId).change();

        $(".select2").select2({
            tags: true,
            width:'100%'
        });
    }
    function editCond(id)
    {
        var condValue=$("#condition" + id + " option:selected").text();
        var conditionId=$("#condition" + id + " option:selected").val();
        editC.push({
            "cid":id,
            "id":conditionId,
            'option':$("#condition" + id + " option:selected").text()
        });

        $('#addCondition' + id).empty();
        $('#addCondition' + id).append("<span class='col-lg-6'>"+ condValue + "</span><a class='btn btn-xs btn-primary col-lg-2' onclick='editCondition1(" + id + ","+ conditionId +")'>"+
        " Edit</a><a class='btn btn-xs btn-warning col-lg-2' onclick='removeCondition(" + id + ")'> Remove</a>");
        $('#condMess').html("Please click save button to save conditions");
    }
    function removeCondition(id)
    {
        removeC.push({
            'id':id
        });
        $('#addCondition' + id).remove();
        $('#condMess').html("Please click save button to save conditions");
    }
    function addNote()
    {
        $('#noteModal').modal('show');
    }
    function saveNote()
    {
        if(noteArr.length != 0 || removeNoteArr != 0 ||editNoteValue != 0)
        {
            $.ajaxSetup({
                headers: { 'X-CSRF-Token' : $('meta[name=_token]').attr('content') }
            });

            $.ajax({
                url:"{{ url('deliverymanagement/insertNote')}}",
                method:"post",
                data:{
                    'formid':{{$detail->Id}},
                    'note':noteArr,
                    'remove':removeNoteArr,
                    'edit':editNoteValue
                },
                success:function(result){
                    noteArr=[];
                    removeNoteArr=[];
                    editNoteValue=[];
                    $('.removeNote').remove();
                    $('#noteMess').html('');
                    $('#changeNoteData').empty();
                    for(var x=0,len=result.length;x<len;x++)
                    {
                        if(result[x].type == "new")
                        {
                            for(var i=0,l=result[x].obj.length;i<l;i++)
                            {
                                $('#insertNote').append('<div class="row" id="addNote'+result[x].obj[i].id+'"><span class="col-lg-6" id="addNot' +
                                result[x].obj[i].id+ '">' + result[x].obj[i].option + '</span>\
                                <a class="btn btn-xs btn-primary col-lg-2" onclick="editNote(' +result[x].obj[i].id+',' + result[x].obj[i].opId +
                                ')"> Edit</a> <a class="btn btn-xs btn-warning col-lg-2" onclick="removeNote1(' + result[x].obj[i].id +')"> \
                                Remove</a></div>');
                            }
                        }
                        else if(result[x].type == "data")
                        {
                            for(var i=0,l=result[x].obj.length;i<l;i++)
                            {
                                console.log(result[x].obj[i]);
                                if(result[x].obj[i].Option != null)
                                    $('#changeNoteData').append('<div><span>'+ result[x].obj[i].Option+'</span>\
                                    </br></div>');
                                else if(result[x].obj[i].Option == null)
                                {
                                    $('#changeNoteData').append('<div><span style="white-space:pre-wrap;">'+result[x].obj[i].note+'</span></div>');
                                }
                            }
                        }

                    }
                    if(result.length > 0)
                    {
                        $('#noteErr').html('');
                        $('#noteSucc').html('Note added.');
                        $('#noteValue').empty();
                        // $('#noteModal').modal('hide');
                        // var message="Note has been updated.";
                        // $("#update-alert ul").html(message);
                        // $("#update-alert").modal('show');
                        setTimeout(function(){
                            $("#noteSucc").html('');
                        }, 3000);
                    }
                }
            });
        }else{
            $('#noteErr').html('Nothing to save.');
            setTimeout(function(){
                $("#noteErr").html('');
            }, 3000);
        }
    }
    function deliverytime(type,time)
    {
        $('.delete').empty();
        var func="timelog('"+type+"');";
        if(type == "Delivery Time")
        {
            $('#timeModal').modal('show');
            $('#timeTitle').html('Edit Delivery Time');
            $('#timeLabel').html('Delivery Time: ');
            $('#time').append('<input type="time" id="newtime" class="form-control" value="'+time+'"/>');
            $('#timeremarks').append('<label class="col-lg-2">Remarks: </label>\
            <div class="col-lg-10"><textarea class="form-control" rows="4" cols="10" id="timeremark"></textarea></div>');
            $('#timelog').append('<button class="btn btn-xs btn-info" onclick="'+func+'">View logs</button>');
            $('#saveType').attr('onclick','saveTime("'+ type +'","'+time+'")');
        }
        else if(type == "Pick up")
        {
            $('#timeModal').modal('show');
            $('#timeTitle').html('Edit Delivery Time');
            $('#timeLabel').html('Delivery Time: ');
            $('#time').append('<input type="time" id="newtime" class="form-control" value="'+time+'"/>');
            $('#timeremarks').append('<label class="col-lg-2">Remarks: </label>\
            <div class="col-lg-10"><textarea class="form-control" rows="4" cols="10" id="timeremark"></textarea></div>');
            $('#timelog').append('<button class="btn btn-xs btn-info" onclick="'+func+'">View logs</button>')
            $('#saveType').attr('onclick','saveTime("'+ type +'","'+time+'")');
        }
    }
    function saveTime(type,time)
    {

        if( $('#timeremark').val() != null && $('#timeremark').val().length != 0)
        {
            $.ajaxSetup({
                headers: { 'X-CSRF-Token' : $('meta[name=_token]').attr('content') }
            });

            $.ajax({
                url:"{{ url('deliverymanagement/saveTime')}}",
                method:"post",
                data:{
                    'formid':{{$detail->Id}},
                    'type':type,
                    'oldtime':time,
                    'newtime':$('#newtime').val(),
                    'remarks':$('#timeremark').val(),
                },
                success:function(result){
                    if(result == 1)
                    {
                        // $('#timeModal').modal('hide');
                        type == "Deliver Time" ? $('#deliverytime').html(timeC($('#newtime').val())):$('#pickuptime').html(timeC($('#newtime').val()));
                        var message=type + " has been updated.";
                        $('#timeremark').val('');
                        $('#timeSucc').html(message);
                        // $("#update-alert ul").html(message);
                        // $("#update-alert").modal('show');
                        setTimeout(function(){
                            $('#timeSucc').html('');
                        }, 3000);
                    }
                }
            });
        }else{
            $('#timeErr').html('Please fill in the remarks.');
            setTimeout(function(){
                $('#timeErr').html('');
            }, 3000);
        }
    }
    function timeC (time) {
        // Check correct time format and split into components
        time = time.toString ().match (/^([01]\d|2[0-3])(:)([0-5]\d)(:[0-5]\d)?$/) || [time];

        if (time.length > 1) { // If time format correct
            time = time.slice (1);  // Remove full string match value
            time[5] = +time[0] < 12 ? ' AM' : ' PM'; // Set AM/PM
            time[0] = +time[0] % 12 || 12; // Adjust hours
        }
        return time.join (''); // return adjusted time or original string
    }
    function timelog(type)
    {
        $('#imgL').empty();
        $('#addTable').empty();
        $.ajaxSetup({
            headers: { 'X-CSRF-Token' : $('meta[name=_token]').attr('content') }
        });

        $.ajax({
            url:"{{ url('deliverymanagement/timeLog')}}",
            method:"get",
            data:{
                'formid':{{$detail->Id}},
                'type':type,
            },
            success:function(result){
                $('#viewModal').modal('show');
                console.log(result);
                $('#addTable').append("<table class='display table-bordered' cellspacing='0' width='100%' padding='30px' style='font-size:13px;' id='timeLogTable'>"+
                "<thead><tr><th>No</th><th>Delivery Time</th><th>Remarks</th></tr></thead>" +
                "<tbody id='timelogDetails'></tbody>" +
                "</table>");
                for(var x=0;x<result.length;x++)
                {
                    $('#timelogDetails').append('<tr><td>'+(x+1)+'</td><td>'+ result[x].delivery_time +
                    '</td><td>'+
                    result[x].remarks +'</td></tr>');
                }
                $('#timeLogTable').DataTable();

                // if(result == 1)
                // {
                //     $('#timeModal').modal('hide');
                //     $('#deliverytime').html(timeC($('#newtime').val()));
                //     var message=type + " has been updated.";
                //     $("#update-alert ul").html(message);
                //     $("#update-alert").modal('show');
                //     setTimeout(function(){
                //         $("#update-alert").modal('hide');
                //     }, 6000);
                // }
            }
        });
    }//time log
    var noteOp=[];
    var noteNum=1;
    var checkInsert=false;
    @foreach($noteOption as $op)
    noteOp.push({
        'name':'{{$op->Option}}',
        'id':'{{$op->Id}}'
    });
    @endforeach
    function addNoteRow()
    {
        var op=$('#noteOption').val();
        $('#noteOption').val("");
        if(!checkInsert)
        {
            if(op == "text")
            {
                var f="newNote(" + noteNum + ",'text')";
                $('#insertNote').append('<div class="row removeNote" id="note'+noteNum+'"><div class="col-lg-6">\
                <textarea id="noteV' + noteNum + '"  class="form-control input-sm"></textarea></div>\
                <a class="btn btn-xs btn-primary col-lg-2" onclick="' + f + '">Add</a>\
                <a class="btn btn-xs btn-danger col-lg-2" onclick="removeRow('+noteNum+')">Remove</a>\
                </div>');
                checkInsert=true;
            }
            else if(op == "option")
            {
                var f="newNote(" + noteNum + ",'option')";
                $('#insertNote').append('<div class="row removeNote" id="note' + noteNum + '"><div class="col-lg-8"><select id="noteV'+ noteNum +
                '" class="form-control select2"><option value="" selected disabled >None</option>'+
                '</select></div><a class="col-sm-2 btn btn-xs btn-info" onclick="' + f + '">Add</a>\
                <a class="btn btn-xs btn-danger col-lg-2" onclick="removeRow('+noteNum+')">Remove</a>\
                </div>');

                $.each(noteOp, function(key, value) {
                    $('#noteV' + noteNum).append($("<option></option>")
                    .attr("value",value.id)
                    .text(value.name));
                });
                $(".select2").select2({
                    tags: true,
                    width:'100%'
                 });
                 checkInsert=true;

            }
            else{
                $('#noteErr').html("Please select option.");
                setTimeout(function(){
                    $('#noteErr').html('');
                }, 3000);
            }
            noteNum++;

        }
        else if(checkInsert)
        {
            $('#noteErr').html("Please fill in the details.");
            setTimeout(function(){
                $('#noteErr').html('');
            }, 3000);
        }

    }//add note row
    var noteArr=[];
    function newNote(id,type)
    {
        var val= type == "option" ? $("#noteV" + id + " option:selected").val():0;
        var noteText= type == "option" ? $("#noteV" + id + " option:selected").text():$("#noteV" + id).val();

        $('#note' + id).empty();
        if(type == 'option')
            $("#note" + id).append("<span class='col-lg-6'>"+ noteText + "</span>\
            <a class='btn btn-xs btn-warning col-lg-2' onclick='removeNote(" + id + ")'> Remove</a>");
        else
            $("#note" + id).append("<span class='col-lg-6' style='white-space:pre-wrap;'>"+ noteText + "</span>\
            <a class='btn btn-xs btn-warning col-lg-2' onclick='removeNote(" + id + ")'> Remove</a>");

        noteArr.push({
            'no':id,
            "id":val,
            'option':noteText
        });
        checkInsert=false;
        $('#noteErr').html("Please click save button to save note.");
    }//create new note
    function removeNote(row)//for temporary usage
    {
        var filter=noteArr.filter(x=>{return x.no != row});
        noteArr=filter;
        $('#note' + row).remove();
        if(noteArr.length == 0)
            $('#noteMess').html('');
    }//remove note
    function editNote(id,type)
    {
        var store= type == 0 ? $('#addNot' + id).html():type;
        $('#addNote' + id).empty();
        if(type == 0)
        {
            $('#addNote' + id).append('<div class="col-sm-8"><textarea class="form-control input-sm" id="noteV' + id + '"></textarea>'+
            '</div><a class="col-sm-2 btn btn-xs btn-info" onclick="editNote1(' + id + ','+type+')">Edit</a>');
            $('#noteV' + id).val(store);
        }
        else if(type != 0)
        {
            $('#addNote' + id).append('<div class="col-sm-8"><select id="noteV'+ id +
            '" class="form-control select2"><option value="" selected disabled >None</option>'+
            '</select></div><a class="col-sm-2 btn btn-xs btn-info" onclick="editNote1(' + id + ','+type+')">Edit</a>');

            $.each(noteOp, function(key, value) {
                $('#noteV' + id).append($("<option></option>")
                .attr("value",value.id)
                .text(value.name));
            });
            $('#noteV' + id).val(type).change();

            $(".select2").select2({
                tags: true,
                width:'100%'
            });
        }
    }
    var removeNoteArr=[];
    function removeNote1(id) //to be delete from db
    {
        removeNoteArr.push({
            'id':id
        });
        $('#addNote' + id).remove();
        $('#noteErr').html("Please click save button to save conditions");
    }
    var editNoteValue=[];
    function editNote1(id,type) //store array
    {
        var noteva= type == 0 ? $('#noteV' + id).val():$("#noteV" + id + " option:selected").text();
        var noteid= type == 0 ? 0:$("#noteV" + id + " option:selected").val();
        editNoteValue.push({
            "id":id,
            "noteid":noteid,
            'option':noteva
        });
        $('#addNote' + id).empty();
        if(type == 0)
        {
            $('#addNote' + id).append("<span style='white-space:pre-wrap;' class='col-lg-6' id='addNot"+id+"'>"+ noteva + "</span><a class='btn btn-xs btn-primary col-lg-2' onclick='editNote(" + id + ","+ type +")'>"+
            " Edit</a><a class='btn btn-xs btn-warning col-lg-2' onclick='removeNote1(" + id + ")'> Remove</a>");
        }
        else{
            $('#addNote' + id).append("<span  class='col-lg-6' id='addNot"+id+"'>"+ noteva + "</span><a class='btn btn-xs btn-primary col-lg-2' onclick='editNote(" + id + ","+ type +")'>"+
            " Edit</a><a class='btn btn-xs btn-warning col-lg-2' onclick='removeNote1(" + id + ")'> Remove</a>");
        }
        $('#noteErr').html("Please click save button to save note");
    }
    function removeRow(row)
    {
        $('#note' + row).remove();
        checkInsert=false;
    }

    $(document).ready(function() {
    $(document).on('click', '.update', function(e) {
        var Id = $(this).attr('id');
        var desc = $(this).attr('desc');
        $('#change').val(desc);
        $('#confirmid').val(Id);
        $('#Confirm').modal('show');
    });
    });

    $(document).ready(function() {
    $(document).on('click', '#updatebutton', function(e) {
            var id = $('#confirmid').val();
            var desc = $('#change').val();
        $.ajaxSetup({
             headers: { 'X-CSRF-Token' : $('meta[name=_token]').attr('content') }
            });
         $.ajax({
                    url: "{{ url('/updateadddesc') }}",
                    method: "POST",
                    data: {id:id,desc:desc},
                    success: function(response){
                        $('#Confirm').modal('hide');
                        if(response == 1)
                        {
                            var message ="Description updated!";
                            $("#update-alert ul").html(message);
                            $("#update-alert").modal('show');
                            window.location.reload();

                        }
                        else
                        {
                            var message = "Failed to update description";
                            $("#error-alert ul").html(message);
                            $("#error-alert").modal('show');
                        }
                    }
              });
    });
    });
</script>
@endsection
