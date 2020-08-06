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
                        <button type="button" class="btn btn-default" data-dismiss="modal" onclick="finalapprove()">Approve</button>
                        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
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
                                <h3>Delivery Details</h3>
                            </div>
                            <!-- @if($detail->delivery_status == 'Completed' && $me->Admin)
                            <div class="col-lg-3">
                                <a href="{{url('deliveryorder')}}" class="btn btn-sm btn-info" style="float:right;">
                                    Print DO
                                </a>
                            </div>
                            @endif -->
                        </div>
                    </div>
                    <div class="box-body">
                        <div class="row">
                            <div class="col-sm-4">
                                <h4>Trip Details</h4>
                                <div class="form-group">
                                    <label class="control-label">DO No: </label> {{$detail->DO_No}}<br>
                                    <label class="control-label">Project: </label> {{$detail->Project_Name}}<br>
                                    <label class="control-label">Location: </label> {{$detail->Location_Name}} ({{$detail->Latitude}} , {{$detail->Longitude}})<br>
                                    <label class="control-label">Delivery Date: </label> {{$detail->delivery_date}} , {{$detail->delivery_time}}<br>
                                    <label class="control-label">Requestor Name: </label> {{$detail->requestorName}}<br>
                                    <!-- <label class="control-label">Delivery Time: </label> <br> -->
                                    <label class="control-label">Remarks: </label> {{$detail->Remarks}}<br>
                                </div>
                            </div>
                            <div class="col-sm-4">
                                <h4>Driver Details</h4>
                                <div class="form-group">
                                    <label class="control-label">Driver Name: </label> {{$detail->Driver_Name}}<br>
                                    <label class="control-label">Vehicle No: </label> {{$detail->Vehicle_No}} ({{$detail->Lorry_Size}})<br>
                                </div>
                            </div>
                            <div class="col-sm-4">
                                <h4>Person In Charge Details</h4>
                                <div class="form-group">
                                    <label class="control-label">PIC Name: </label> {{$detail->PIC_Name}}<br>
                                    <label class="control-label">PIC Contact: </label> {{$detail->PIC_Contact}}<br>
                                </div>
                            </div>
                        </div>

                        <div class="box-header with-border"></div>
                        <br/>
                        <div class="row">
                            <label class="control-label col-sm-2">Delivery Items: </label>
                        </div><!--row-->

                        <div class="row">
                            <div class="col-sm-12 col-lg-12">
                                <table class="display" cellspacing="0" width="100%" padding="30px" style="font-size: 13px;" id="display">
                                <!-- <table id="display" class="display" cellspacing="0" width="100%" padding="30px" style="font-size: 13px;"> -->
                                    <thead>
                                        <tr>
                                            <td>No</td>
                                            <td>Categories</td>
                                            <td>Item Code</td>
                                            <td>Item Description</td>
                                            <td>Unit</td>
                                            <td>Item Remarks</td>
                                            <td>Quantity Request</td>
                                            <td>Quantity Send</td>
                                            <td>Quantity Received</td>
                                            <td>Availability</td>
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
                                            <td>{{$item->Unit}}</td>
                                            <td>{{$item->remarks}}</td>
                                            <td>{{$item->Qty_request}}</td>
                                            @if($item->available == 1)
                                                <td>{{$item->Qty_request}}</td>
                                            @else
                                                <td>-</td>
                                            @endif
                                            @if($item->Qty_received == "")
                                                <td>-</td>
                                            @else
                                                <td>{{$item->Qty_received}}</td>
                                            @endif
                                            @if($item->available == 1)
                                                <td><span style="color:green;">Available</span></td>
                                            @else
                                                <td><span style="color:red;">Insufficient</span></td>
                                            @endif    
                                         </tr>
                                         <?php $i++;?>
                                         @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                        <br>
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
                                        </tr>
                                    </thead>
                                    <tbody>
                                    <?php $i=1;?>
                                    @foreach($log as $l)
                                        <tr>
                                            <td>{{$i}}</td>
                                            <td>{{$l->Name}}</td>
                                            <td>{{$l->delivery_status}}</td>
                                            <td>{{$l->delivery_status_details}}</td>
                                            <td>{{$l->created_at}}</td>
                                        </tr>
                                        <?php $i++;?>
                                    @endforeach
                                    <tbody>
                                </table>
                            </div>
                        </div>

                        <br/>   
                        <div class="row">
                            <div class="col-sm-2">
                                <a class="btn btn-info btn-sm" href="{{url('/mydeliveryrequest')}}">Back</a>
                            </div>
                            @if($detail->RequestorId == $me->UserId)
                            <div class="col-sm-2">
                                <button class="btn btn-success btn-sm" onclick="OpenModal({{$detail->Id}},'{{$detail->delivery_status}}')">Final Approve</button>
                            </div>
                            @endif
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
	<strong>Copyright &copy; 2014-2016 <a href="http://www.softoya.com">TrackerOnTheGo</a>.</strong> All rights reserved.
</footer>

<script type="text/javascript">
function OpenModal(id)
{
        var hiddeninput='<input type="hidden" class="form-control" id="finalapproveid" name="finalapproveid" value="'+id+'">';
        $( "#myModalLabel" ).html(hiddeninput);
        $( "#approvalMess" ).html("&nbsp;&nbsp;&nbsp; Are you sure you want to final approve?");
        // $("#addBtn > .btn").after('<button class="btn btn-primary" id="removeBtn" onclick="'+set+'")">Approve</button>');
        $('#approvalModal').modal('show');
}

function finalapprove()
{
    $.ajaxSetup({
            headers: { 'X-CSRF-Token' : $('meta[name=_token]').attr('content') }
        });
        id=$('[name="finalapproveid"]').val();
        $.ajax({
            url:"{{url('finalapprove')}}"+"/"+id,
            method:"post",
            data:{id},
       success: function(response){
                    if (response==1)
                    {
                        var message="Success!";
                        $('#approvalModal').modal('hide');
                        $("#update-alert ul").html(message);
                        $("#update-alert").modal('show');
                    }
                    else {
                      var errormessage="Failed!";
                      $('#approvalModal').modal('hide');
                      $("#error-alert ul").html(errormessage);
                      $("#error-alert").modal('show');
                      // $("#ajaxloader").hide();
                    }
            }
        });
    }
</script>
@endsection
