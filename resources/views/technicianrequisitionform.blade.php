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
				{ "visible": false, "targets": [1,4] },
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
				{ data: null,"render":"", title:"No"
        },
				{ data:"speedfreakinventory.Id",title:'Id'
        },
				{ data:"speedfreakinventory.name",title:'Item Name'},
				{ data:"history.qty",title:'Prepared Qty'},
				{ data:"history.speedfreakInventoryId",title:"history.speedfreakInventoryId"}
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
		
			// userid = oTable.api().row( this ).data().speedfreakinventory.Id;
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
	        <li>SPEED FREAK Management</li>
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
                <input type="hidden" name="tech" value="{{$me->UserId}}">
                <input type="hidden" name="stockoutbranch" value="{{$branches}}">
                </form>
              </div>
                <div class="modal-footer">
                  <button type="button" class="btn btn-default" id="confirmstockout">Confirm</button>
                  <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                </div>
              </div>
            </div>
      </div>
    	<div class="row">
    		<div class="box">
    			<div class="box-header">
    			
            <div class="col-md-4">
    					<label>Branch</label>
                        <select id="branch" name="branch" class="form-control select2">
                            <option value="0" {{$branches == 0 ? 'selected':''}}>Select Branch</option>
                            @foreach($branch as $b)
                            <option value="{{$b->Option}}" {{$branches == $b->Option ? 'selected':''}}>{{$b->Option}}</option>
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
                                @foreach($items as $key=>$value)

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
                                    @endif
                                @endforeach
                                </tr>
                                <tr>
                                    @foreach($items as $key=>$value)

                                        @if ($key==0)
                                        <td></td>

                                        @foreach($value as $field=>$value)
                                        <td>
                                            {{ $field }}
                                        </td>
                                        @endforeach

                                        @endif

                                    @endforeach
                                </tr>
                            </thead>
                            <tbody>
                                <?php $i = 0; ?>
                                @foreach($items as $delivery)

                                <tr>
                                    <td></td>

                                    @foreach($delivery as $key=>$value)
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
                        <br><br>
                        <div class="col-md-2 pull-right">
                      <br>
                
						        <button class="btn bg-green pull-right" type="button" id="confirm" name="confirm">Confirm Stock Out</button>
	                </div>
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
function refresh()
{
  var b = $('#branch').val();
  window.location.href ="{{ url('/requisitionform') }}/"+0+"/"+b;
}
$(document).ready(function(){
  

  $(document).on('click','#confirm',function(){
    var d=oTable.api().rows().data().toArray();
    if(d.length){
      $.ajaxSetup({
        headers: { 'X-CSRF-Token' : $('meta[name=_token]').attr('content') }
      });
      
      $.ajax({
        url: "{{ url('/requisitionform/confirmStockOut') }}",
        method: "POST",
        data: {
          data:d,
          branch:"{{$branches}}"
        },
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
    }else{
      $("#error-alert ul").html("No items for confirmation");
      $("#error-alert").modal('show');
    }
    
  });

});
</script>
@endsection