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
				{ data:"gensetinventory.Id",title:'Id'},
				{ data:"gensetinventory.name",title:'Item Name'},
				{ data:"gensetinventory.qty_balance",title:'Branch Balance'},
				{ title:"Stock Out Quantity", 
					 "render": function ( data, type, full, meta ) {
					 	if(full.gensetinventory.qty_balance >0 || full.gensetinventory.qty_balance != "" || full.gensetinventory.qty_balance != null)
					 	{
					 		return "<input type='hidden' name='inventoryid[]' id='inventoryid' value='"+full.gensetinventory.Id+"'><input type='text' class='stockout form-control' inven_id_attr='"+full.gensetinventory.Id+"' balance='"+full.gensetinventory.qty_balance+"' name='stockout[]' id='stockout'>"
					 	}
					 }
				}
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
	} );
	</script>
@endsection

@section('content')
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
		<h1>Requisition Form</h1>
		<ol class="breadcrumb">
	        <li><a href="#"><i class="fa fa-dashboard"></i>Home</a></li>
	        <li>GENSET Management</li>
	        <li class="active">Requisition Form</li>
	    </ol>
    </section>
    <br>
    <!-- Main content -->
    <section class="content">
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
    	 <div class="modal fade" id="viewItemListModal" role="dialog" aria-labelledby="myItemListModalLabel" style="display: none;">
            <div class="modal-dialog" role="document">
              <div class="modal-content">
                <div class="modal-header">
                  <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true"></span></button>
                  <h4 class="modal-title" id="ItemListModalLabel">Item List</h4>
                </div>
                <div class="modal-body">
                	<form enctype='multipart/form-data' id='getitemform' role='form' method='POST' action=''>
                  <table id="itemlisttable" class="table table-condensed">
                <thead>
                    <tr>
                      <th style="display: none">Id</th>
                      <th>Name</th>
                      <th>Barcode</th>
                      <th>Stock Out Quantity</th>
                    </tr>
                  </thead>
                  <tbody>
                  </tbody>
                </table>
                <input type="hidden" name="tech" value="{{$tech}}">
                <input type="hidden" name="stockoutbranch" value="{{$branches}}">
                </form>
              </div>
                <div class="modal-footer">
                  <button type="button" class="btn btn-default" id="prepare">Confirm</button>
                  <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                </div>
              </div>
            </div>
      </div>
    	<div class="row">
    		<div class="box">
    			<div class="box-header">
    				<div class="col-md-4">
    					<label>Technician</label>
                        <select id="technician" name="technician" class="form-control select2">
                            <option value="" selected="">Select Technician Name</option>
                            @foreach($technician as $t)
                            <option value="{{$t->Id}}">{{$t->Name}}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-4">
    					<label>Branch</label>
                        <select id="branch" name="branch" class="form-control select2">
                            <option value="" selected="">Select Branch</option>
                            @foreach($branch as $b)
                            <option value="{{$b->Option}}">{{$b->Option}}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-2 pull-center">
                    	<br>
                    	<button class="btn btn-success" type="button" onclick="refresh()">Search</button><br><br>
                    </div>
    			</div>
		        <div class="box-body">
		            <div class="col-md-12">
		            	<table id="inventorytable" class="inventorytable" cellspacing="0" width="100%" padding="30px" style="font-size: 13px; white-space: pre-line;">
			              	<thead>
                                <tr class="search">
                                @foreach($list as $key=>$value)

                                    @if ($key==0)
                                        <?php $i = 0; ?>
                                            @foreach($value as $field=>$a)
                                                @if ($i==0 || $i==1)
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
                                <tr>
                                    @foreach($list as $key=>$value)

                                        @if ($key==0)
                                        <td></td>

                                        @foreach($value as $field=>$value)
                                        <td>
                                            {{ $field }}
                                        </td>
                                        @endforeach
                                        <td></td>

                                        @endif

                                    @endforeach
                                </tr>
                            </thead>
                            <tbody>
                                <?php $i = 0; ?>
                                @foreach($list as $delivery)

                                <tr>
                                    <td></td>

                                    @foreach($delivery as $key=>$value)
                                      <td>
                                        {{ $value }}
                                      </td>
                                    @endforeach
                                    <td></td>
                                </tr>
                                <?php $i++; ?>
                                @endforeach
                            </tbody>
                            <tfoot></tfoot>
                        </table>
                        <br><br>
                        <div class="col-md-2 pull-right">
                    	<br>
						{{-- <button class="btn bg-green pull-right" type="button" id="getitem" name="getitem">Proceed Stock Out</button> --}}
						<button class="btn bg-green pull-right" type="button" id="getitem" name="getitem">Prepare</button>
	                </div>
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

  <script>

    $(function () {

      $(".select2").select2();
    });

    function refresh()
    {
    	var tech = $('#technician').val();
    	var b = $('#branch').val();
       	window.location.href ="{{ url('/requisitionform') }}/"+tech+"/"+b;
    }

    $(document).ready(function(){
    	$(document).on('keyup','.stockout',function(){
    		$.ajaxSetup({
         	headers: { 'X-CSRF-Token' : $('meta[name=_token]').attr('content') }
      		});
    		var stockout = $(this).val();
    		var balance = $(this).attr('balance');
    		var invenid = $(this).attr('inven_id_attr');
    		var check = balance-stockout;
    		if(isNaN(check) == true)
    		{
    			check = 0;
    		}
    		if( check < 0 )
    		{
    			alert('Not enough stock');
    			$(this).val("");
    		}
    		else
    		{
    			$.ajax({
                url: '{{ url("/requisition/set_requisition_data") }}',
                data: {'stockout':stockout,'invenid':invenid},
                dataType: 'json',
                type: "post",
                 success: function(json) {
               
              	}

               });
    		}
    	});
	});

	$(document).ready(function(){

    	$(document).on('click','#getitem',function(){
    		$.ajaxSetup({
        		headers: { 'X-CSRF-Token' : $('meta[name=_token]').attr('content') }
      		});
      		var d = oTable.$('input').serialize();
			if(d == "") return alert("No data available.");
      		$.ajax({
	          url: "{{ url('/requisitionform/getitem') }}",
	          method: "POST",
	          data: d,
	          success: function(response){
	            $('#itemlisttable > tbody').empty();
	            response.Item.forEach(function(element) {
	                $('#itemlisttable > tbody').append(`<tr>
	                  <td style="display: none">${element.Id}<input type="hidden" name="confirmid[]" value="${element.Id}"></td>
	                  <td>${element.name}</td>
	                  <td>${element.barcode}</td>
	                  <td>${element.qty}<input type="hidden" name="confirmqty[]" value="${element.qty}"></td>
	                </tr>`);
	              });
	            $('#viewItemListModal').modal("show");
	          },
	          error: function(data){
	          }
      });
      });
    	});

		$(document).ready(function(){
    	$(document).on('click','#prepare',function(){
			$('#viewItemListModal').modal("hide");
    		$.ajaxSetup({
        		headers: { 'X-CSRF-Token' : $('meta[name=_token]').attr('content') }
      		});
      		var formData = $('#getitemform').serialize();
      		$.ajax({
	          url: "{{ url('/requisitionform/prepare') }}",
	          method: "POST",
	          data: formData,
	          success: function(response){
	            if(response == 1)
	            {
	            	var message="Successfully prepared!";
                     $("#update-alert ul").html(message);
                     $("#update-alert").modal('show');
					 
	            	window.location.reload();
	            }
	          },
	          error: function(data){

	          	var errormessage="Failed to prepare!";
                $("#error-alert ul").html(errormessage);
                $("#error-alert").modal('show');
	          }
      });
      });
	  
	});
	
</script>
@endsection