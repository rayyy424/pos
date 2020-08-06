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
    var editor;
    var asInitVals = new Array();
	var oTable;
	var userid;
	var inventory;
   $(document).ready(function() {
		oTable=$('#inventorytable').dataTable( {
			columnDefs: [
				{ "visible": false, "targets": [1] },
				// {"className": "dt-left", "targets": [4]},
				{"className": "dt-center", "targets": "_all"}
			],
			responsive: false,
			colReorder: false,
			sScrollX: "100%",
			bScrollCollapse: true,
			bAutoWidth: true,
			sScrollY: "100%",
			dom: "frtip",
			iDisplayLength:25,
			columns: [
				{ data: null,"render":"", title:"No"},
				{ title:'Id'},
				{ title:'Item Name'},
				{ title:'Barcode'},
				{ title:'Stock Out Quantity'}
			],
			autoFill: {
				editor:  editor,
			},
			select: {
                style:    'os',
                selector: 'td'
            },
		});

		$('#inventorytable').on( 'click', 'tr', function () {
			// Get the rows id value
			//  var row=$(this).closest("tr");
			//  var oTable = row.closest('table').dataTable();
			userid = oTable.api().row( this ).data().gensetinventory.Id;
		});

		oTable.api().on( 'order.dt search.dt', function () {
			oTable.api().column(0, {search:'applied', order:'applied'}).nodes().each( function (cell, i) {
				cell.innerHTML = i+1;
			} );
		} ).draw();

		$("thead input").keyup ( function () {

			/* Filter on the column (the index) of this element */
			if ($('#inventorytable').length > 0)
			{
				var colnum=document.getElementById('inventorytable').rows[0].cells.length;
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
		} );
		$("#ajaxloader4").hide();
	} );
	</script>

@endsection

@section('content')
<div class="content-wrapper">
	<!-- Content Header (Page header) -->
	<section class="content-header">
		<h1>Requisition Management<small>GENSET</small></h1>
		<ol class="breadcrumb">
			<li><a href="{{ url('/home') }}"><i class="fa fa-dashboard"></i>Home</a></li>
			<li><a href="#">GENSET</a></li>
            <li>Requisition Management</li>
            <li class="active">Requisition Details</li>
		</ol>
	</section>

	<br>

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

      <div class="modal fade" id="Confirm" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
            <div class="modal-dialog" role="document">
              <div class="modal-content">
                <div class="modal-header">
                  <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                  <h4 class="modal-title" id="myModalLabel">Approve</h4>
                </div>
                <div class="modal-body">
                  <div class="form-group" id="generateinvoicesoid">

                  </div>
                  Are you sure you want to approve this requisition?
                </div>
                <div class="modal-footer">
                  <center><img src="{{ URL::to('/') . "/img/ajax-loader.gif" }}" width="50px" height="50px" alt="Loading" name='ajaxloader4' id="ajaxloader4";></center>
                  <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                  <button type="button" class="btn btn-primary" id="confirmapprove">Approve</button>
                </div>
              </div>
            </div>
      </div>
		<div class="box">
			<div class="box-header">
				<h3>Requisition Details</h3>
			</div>
			<div class="box-body">
				<div class="row">
					<div class="col-md-6">
						<div class="box-header">
							<h4>Details</h4>
						</div>
                        <table class="table table-bordered">
                            <tbody>
                                <tr>
                                    <th>Requisition No.</th>
                                    <td>{{$list->Req_No}}</td>
                                </tr>
                                <tr>
                                    <th>Service Ticket No.</th>
                                    <td>{{$list->service_id}}</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
                <div class="row">
                	<div class="col-md-12">
                		<div class="box-header">
                			<h4>History</h4>
                		</div>
                		<table id="inventorytable" class="inventorytable" cellspacing="0" width="100%" padding="30px" style="font-size: 13px; white-space: pre-line;">
			              	<thead>
			              		<th>Name</th>
                        <th>QR Code</th>
                        <th>Model</th>
                        <!-- <th>Requested Qty</th> -->
                        <th>Status</th>
                        <th>Details</th>
                        <th>Action Taken By</th>
                        <th>Action Taken On</th>
                        <th>Action Qty</th>
			                </thead>
			                <tbody>
                        @foreach($item as $i)
			                	<tr>
			                		<td>{{$i->name}}</td>
			                		<td>{{$i->barcode}}</td>
			                		<td>{{$i->model}}</td>
			                		<!-- <td>{{$i->requested}}</td> -->
			                		<td>{{$i->status}}</td>
                          <td>{{$i->status_details}}</td>
                          <td>{{$i->user}}</td>
                          <td>{{$i->created_at}}</td>
                          <td>{{$i->Qty}}</td>
			                	</tr>
		                    </tbody>
                        @endforeach
			            </table>
			            @if($list->status =="Completed")
			            <button class="btn btn-primary" id="approve">Approve</button>
			            @endif
			            <input type="hidden" name="requisitionid" id="requisitionid" value="{{$id}}">
                	</div>
                </div>
            </div>
		</div>
	</section>
	
 <footer class="main-footer">
    <div class="pull-right hidden-xs">
      <b>Version</b> 2.0.1
    </div>
    <strong>Copyright &copy; 2014-2016 <a href="http://www.softoya.com">TrackerOnTheGo</a>.</strong> All rights reserved.
  </footer>
</div>

<script type="text/javascript">

   $(function () {
    });

    $(document).ready(function() {
        $(document).on('click', '#approve', function(e) {
        	$('#Confirm').modal('show');
        });
    });
   $(document).ready(function() {
        $(document).on('click', '#confirmapprove', function(e) {
        	var id = $('#requisitionid').val();
        	$("#ajaxloader4").show();
        	$("#confirmapprove").prop('disabled',true);
        	$.ajaxSetup({
           headers: { 'X-CSRF-Token' : $('meta[name=_token]').attr('content') }
          });
          $.ajax({
                  url: "{{ url('/requisition/approve') }}" + "/" + id,
                  method: "POST",
                  // data: {Id:id},
                   success: function(response){
                   	$("#confirmapprove").prop('disabled',false);
                    $('#Confirm').modal('hide');
                   if (response==1)
                    {
                        $("#ajaxloader4").hide();
                        var message="Requisition Approved";
                        $("#update-alert ul").html(message);
                        $("#update-alert").modal('show');
                        window.location.reload();
                    }
                    else {
                        var errormessage="Failed to Approve";
                        $("#error-alert ul").html(errormessage);
                        $("#error-alert").modal('show');
                        $("#ajaxloader4").hide();
                    }
                }
            });
        });
    });

</script>
@endsection
