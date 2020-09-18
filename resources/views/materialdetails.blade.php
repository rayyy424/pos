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

	    .dataTables_wrapper .dataTables_scroll div.dataTables_scrollBody th, .dataTables_wrapper .dataTables_scroll div.dataTables_scrollBody td {
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
      {{-- chart js --}}
      <script type="text/javascript" language="javascript" src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.4.0/Chart.bundle.min.js"></script>

    <script type="text/javascript" language="javascript" class="init">
    $(window).trigger('resize');
    $(function () {
     
        var oTable=$("#materialTable").dataTable({
            ajax: {
				"url": "{{ asset('/Include/material.php') }}",
				"data":{
					id:{{$data->Id}}
				}
			},
			columnDefs: [
				{ "visible": false, "targets": [1] },
                {"className": "dt-center", "targets": "_all"}
			],
			responsive: false,
			colReorder: false,
			sScrollX: "100%",
			bScrollCollapse: true,
			bAutoWidth: true,
			sScrollY: "100%",
			dom: "tp",
			iDisplayLength:10,
            rowId:"material.Id",
			columns: [
				{ data: null,"render":"", title:"No"},
				{ data: "materialrequest.Id"},
				{ data: "inventories.Item_Code", title:'Item Code'},
                { data: "inventories.Description", title: "Item Description"},
                { data: "materialrequest.Qty", title: "Qty"},
                { data: "materialrequest.Price", title: "Item Price",
                  render: function ( data, type, row, meta ) {
                    return "RM "+ data;
                }},
                {
                    data:null,
                    title:"Total",
                    render:function(data,type,row,meta){
                        return "RM " + parseFloat(data.materialrequest.Qty*data.materialrequest.Price).toFixed(2);
                    }
                }
			],
			select: {
                style:    'os',
                selector: 'td'
            },
        });

        oTable.api().on( 'order.dt search.dt', function () {
			oTable.api().column(0, {search:'applied', order:'applied'}).nodes().each( function (cell, i) {
				cell.innerHTML = i+1;
			} );
		} ).draw();
        $("#materialTable thead input").keyup ( function () {

            /* Filter on the column (the index) of this element */
            if ($('#materialTable').length > 0)
            {
                var colnum=document.getElementById('materialTable').rows[0].cells.length;
                if (this.value=="[empty]")
                {
                    oTable.fnFilter( '^$', this.name,true,false );
                }
                else if (this.value=="[nonempty]")
                {
                    oTable.fnFilter( '^(?=\\s*\\S).*$', this.name,true,false );
                }
                else if (this.value.startsWith("!")==true && this.value.length>1)
                {
                    oTable.fnFilter( '^(?'+ this.value +').*', this.name,true,false );
                }
                else if (this.value.startsWith("!")==false)
                {
                    oTable.fnFilter( this.value, this.name,true,false );
                }
            }
        });
        
        var mTable=$("#activityTable").dataTable({
            ajax: {
				"url": "{{ asset('/Include/materialactivitylog.php') }}",
				"data":{
					id:{{$data->Id}}
				}
			},
			columnDefs: [
				{ "visible": false, "targets": [1] },
                {"className": "dt-center", "targets": "_all"}
			],
			responsive: false,
			colReorder: false,
			sScrollX: "100%",
			bScrollCollapse: true,
			bAutoWidth: true,
			sScrollY: "100%",
			dom: "tp",
			iDisplayLength:10,
            rowId:"materialstatus.Id",
			columns: [
				{ data: null,"render":"", title:"No"},
				{ data: "materialstatus.Id"},
				{ data: null, title:'Name',
                  render:function(data,type,row,meta){
                    if(data.users.Name != null)
                        return data.users.Name;
                    else
                        return "{{$data->Name}}";
                }},
                { data: "materialstatus.Status", title:"Status"},
                { data:null,title:"Reason",
                render:function(data){
                    if(data.materialstatus.Status == "Rejected")
                        return data.materialstatus.Reason;
                    else
                        return "-";
                }},
                { data:"materialstatus.created_at" , title:"Created At"}
			],
			select: {
                style:    'os',
                selector: 'td'
            },
        });
        mTable.api().on( 'order.dt search.dt', function () {
			mTable.api().column(0, {search:'applied', order:'applied'}).nodes().each( function (cell, i) {
				cell.innerHTML = i+1;
			} );
		} ).draw();

        $("#activityTable thead input").keyup ( function () {
            /* Filter on the column (the index) of this element */
            if ($('#activityTable').length > 0)
            {
                var colnum=document.getElementById('activityTable').rows[0].cells.length;
                if (this.value=="[empty]")
                {
                    mTable.fnFilter( '^$', this.name,true,false );
                }
                else if (this.value=="[nonempty]")
                {
                    mTable.fnFilter( '^(?=\\s*\\S).*$', this.name,true,false );
                }
                else if (this.value.startsWith("!")==true && this.value.length>1)
                {
                    mTable.fnFilter( '^(?'+ this.value +').*', this.name,true,false );
                }
                else if (this.value.startsWith("!")==false)
                {
                    mTable.fnFilter( this.value, this.name,true,false );
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
		<h1>Material Request<small>Admin</small></h1>

		<ol class="breadcrumb">
			<li><a href="{{ url('/home') }}"><i class="fa fa-dashboard"></i> Home</a></li>
			<li><a href="#">Admin</a></li>
			<li class="active">Material Approval</li>
		</ol>
	</section>

	<section class="content">
        <br><br>
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
        <div class="row">
	        <div class="box">
	            <div class="box-body">

                    <div class="row">
                        <div class="col-sm-3">
                            <label>Requestor Name: </label>
                            {{$data->Name}}
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-sm-3">
                            <label>Site: </label>
                            {{$data->site}}
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-sm-3">
                            <label>Material: </label>
                        </div>
                    </div>
                
                    <div class="row">
                        <div class="col-md-12">
                            <table id="materialTable" cellspacing="0" width="100%" padding="30px" style="font-size: 13px;">
                            </table>
                        </div>
                    </div>
                    <br>

                    <div class="row">
                        <div class="col-md-4">
                            <table>
                                <tr>
                                    <th>Total</th>
                                    <td>:</td>
                                    <td>&nbsp; RM {{$data->Total}}</td>
                                </tr>
                            </table>
                        </div>
                    </div>
                    <br>
                    <div class="row">
                        <label class="col-sm-3">Activity Log: </label>
                    </div>
                    
                    <div class="row">
                        <div class="col-md-12">
                            <table id="activityTable" cellspacing="0" width="100%" padding="30px" style="font-size: 13px;">
                            </table>
                        </div>
                    </div>
                    <br>

                    <div class="row">
                        <div class="col-sm-12">
                            @if($data->Status != "Final Approved" && $me->UserId == $data->ApproverId && $data->Status != "Rejected")
                            <div class="col-sm-3">
                                <button id="approve_btn" class="btn btn-primary btn-sm" onclick="approve('approve')">Approve</button>
                            </div>
                            <div class="col-sm-3">
                                <button id="reject_btn" class="btn btn-warning btn-sm" onclick="approve('reject')">Reject</button>
                            </div>
                            @endif
                            <div class="col-sm-3">
                                <a href="{{url('material/materialApproval')}}" class="btn btn-info btn-sm">Back</a>
                            </div>
                        </div>
                    </div>
                </div>
		    </div>
        </div>

        
        <div class="modal fade" id="approvalModal"  role="dialog" aria-labelledby="myModalLabel">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                        <h4 class="modal-title" id="myModalLabel">Approval</h4>
                    </div>
                    <div class="row">
                        <div class="col-sm-4">
                        </div>
                    </div>
                    <div class="form-group" padding="10px">
                        <label id="approvalMess" class="col-sm-12"></label>
                    </div>
                    <br>
                    <div id="selection"></div>
                    <div class="modal-footer" id="addBtn">
                        <button type="button" class="btn btn-primary" onclick="update('approve')">Approve</button>
                        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                    </div>
                </div>
            </div>
        </div>

        
        <div class="modal  fade" id="rejectModal"  role="dialog" aria-labelledby="myModalLabel">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                        <h4 class="modal-title" id="myModalLabel">Reject</h4>
                    </div>
                    <div class="modal-body">
                        <div class="row">
                            <div class="col-sm-4">
                                <span style="color:red;" id="err_mess"></span>
                            </div>
                        </div>
                        <div class="form-group" padding="10px">
                            <label id="rejectMess" class="col-sm-12"></label>
                        </div>
                        <div class="form-group" padding="10px">
                            <label class="col-sm-3">Reason: </label>
                            <div class="col-sm-8">
                                <textarea  class="form-control" cols="5" rows="5" id="reason"></textarea>
                            </div>
                        </div>
                        <div class="row">

                        </div>
                    </div>
                    
                    <br>
                    <div id="selection"></div>
                    <div class="modal-footer" id="addBtn">
                        <button type="button" class="btn btn-primary" onclick="update('reject')">Reject</button>
                        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
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
	<strong>Copyright &copy; 2014-2016 <a href="http://www.softoya.com">TrackerOnTheGo</a>.</strong> All rights reserved.
</footer>
<script>
    function approve(status)
    {
        if(status == "approve")
        {
            $("#approvalModal").modal("show");
            $("#approvalMess").html("Are you sure you want to approve?");
        }
        else if(status == "reject"){
            $("#rejectModal").modal("show");
            $("#rejectMess").html("Are you sure you want to reject?");
        }
    }
    async function update(status)
    {
        await $.ajaxSetup({
            headers: { 'X-CSRF-Token' : $('meta[name=_token]').attr('content') }
        });
        if((status == "reject" && $("#reason").val()) || status == "approve")
        {
            $("#approve_btn").attr('disabled',true);
            $("#reject_btn").attr('disabled',true);
            await $.ajax({
                type: "post",
                url: "{{url('material/updateStatus')}}",
                data: {
                    id:{{$data->Id}},
                    status:status,
                    reason:$('#reason').val()
                },
                success: function (response) {
                    if(response == 1)
                    {
                        if(status == "approve")
                            $('#approvalModal').modal('hide');
                        else if(status == "reject")
                            $("#rejectModal").modal('hide');
                    $("#update-alert ul").html("Status updated");
                    $("#update-alert").modal('show');
                    setTimeout(() => {
                        window.location.reload();
                    }, 3000);
                    }
                }
            });
        }else{
            $("#err_mess").html("Reason is required!");
            setTimeout(() => {
                $("#err_mess").html("");
            }, 3000);
        }
       
    }
    
</script>

@endsection