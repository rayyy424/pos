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

	    /* .dataTables_wrapper .dataTables_scroll div.dataTables_scrollBody th, .dataTables_wrapper .dataTables_scroll div.dataTables_scrollBody td {
	      	white-space: pre-wrap;
	    } */
        table.dataTable tbody th,table.dataTable tbody td 
        {
        white-space: nowrap;
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

    <script type="text/javascript" language="javascript" class="init">
    var oTable,oTable1,oTable2,oTable3,materialeditor,viewtable1,filetable,mrtable,viewMrTable,mrItemTable;
    $(function () {
        viewMrTable=$("#viewMrTable").dataTable({
            dom:"tp"
        });
        viewMrTable.on( 'order.dt search.dt', function () {
			viewMrTable.api().column(0, {search:'applied', order:'applied'}).nodes().each( function (cell, i) {
				cell.innerHTML = i+1;
			} );
		} ).api().draw();
        mrItemTable=$("#mrItemTable").dataTable();
        mrItemTable.on( 'order.dt search.dt', function () {
			mrItemTable.api().column(0, {search:'applied', order:'applied'}).nodes().each( function (cell, i) {
				cell.innerHTML = i+1;
			} );
		} ).api().draw();
        filetable=$("#fileTable").DataTable({
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
        filetable.on( 'order.dt search.dt', function () {
			filetable.column(0, {search:'applied', order:'applied'}).nodes().each( function (cell, i) {
				cell.innerHTML = i+1;
			} );
		} ).draw();
        mrtable=$('#materialTable').dataTable({
            dom:"",
            bSort:false,
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
        viewtable1.on( 'order.dt search.dt', function () {
			viewtable1.column(0, {search:'applied', order:'applied'}).nodes().each( function (cell, i) {
				cell.innerHTML = i+1;
			} );
		} ).draw();


        $('a[data-toggle="tab"]').on( 'shown.bs.tab', function (e) {
            var target = $(e.target).attr("href") 
            $.fn.dataTable.tables( {visible: true, api: true} ).columns.adjust();
        } );
        
    });
</script>

@endsection

@section('content')

<div class="content-wrapper">
	<!-- Content Header (Page header) -->
	<section class="content-header">
		<h1>Budget Costing<small>Admin</small></h1>

		<ol class="breadcrumb">
			<li><a href="{{ url('/home') }}"><i class="fa fa-dashboard"></i> Home</a></li>
			<li><a href="#">Admin</a></li>
			<li class="active">Budget Costing</li>
		</ol>
	</section>

	<section class="content">
        
        <br><br>
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
                    <div class="nav-tabs-custom">
                        <ul class="nav nav-tabs">
                            <li class="active"><a href="#newrequest" data-toggle="tab" id="newrequesttab">New Request</a></li>
                        </ul>
                        <div class="tab-content">
                            <div class="active tab-pane" id="newrequest">
                                <div class="row">
                                    <div class="col-md-12">
                                        <div class="col-md-3">
                                            <label>Project Name</label>
                                        </div>
                                        <div class="col-md-6">
                                            <select id="project" class="form-control">
                                                <option value="">None</option>
                                                @foreach($projects as $project)
                                                <option value="{{$project->Id}}" {{$mr->ProjectId== $project->Id ? "selected":""}}>{{$project->Project_Name}}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-12">
                                        <div class="col-md-3">
                                            <label>Site Name    </label>
                                        </div>
                                        <div class="col-md-6">
                                            <select id="site" class="site">
                                                <option value="" disabled>None</option>
                                            </select>
                                        </div>
                                    </div>
                                </div>
                                <br>
                                <div class="row">
                                    <div class="col-md-12">
                                            <div class="col-md-8">
                                                @foreach($type as $t)
                                                <div class="col-md-4">
                                                    <label>{{strtoupper($t->Option)}}: RM <span id='{{strtolower($t->Option)}}'>-</span>   </label>
                                                </div>
                                            
                                                @endforeach
                                            </div>
                                    </div>
                                </div>                               
                                <div class="row">
                                    <div class="col-sm-12">
                                        <span class="col-sm-12" id="add_error" style="color:red;"></span>
                                    </div>
                                </div>
                                <br>
                                <div class="row">
                                    <div class="col-md-12">
                                        <button class='btn btn-primary btn-sm' style="width:unset;" onclick="importMrModal()">Import <i class="fa fa-download" aria-hidden="true"></i></button>
                                        <button class='btn btn-sm btn-default' style='width:unset;' onclick='saveMR()'>Save <i class="fa fa-floppy-o" aria-hidden="true"></i></button>
                                    </div>
                                </div>
              
                                <br>
                                <div class="row">
                                    <div class="col-md-12">
                                        <table id="materialTable" cellspacing="0" width="100%" padding="30px" style="font-size: 13px;">
                                            <thead>
                                                <tr>
                                                    <th rowspan="2" style="text-align: center;">Type</th>
                                                    <th rowspan="2" style="vertical-align: inherit;text-align: center;">Item Code</th>
                                                    <th rowspan="2" style="vertical-align: inherit;text-align: center;">Item Description</th>
                                                    <th colspan="2" style="text-align: center;">Pre-Con</th>
                                                    <th colspan="2" style="text-align: center;">Budget Costing</th>
                                                    <th rowspan="2" style="text-align: center;">Action</th>
                                                </tr>
                                                <tr>
                                                    <th style="text-align: center;width:8%;">Qty</th>
                                                    <th style="text-align: center;width:10%">Unit</th>
                                                    <th style="text-align: center;width:15%;">Unit Price</th>
                                                    <th style="text-align: center;">Amount</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <tr id="insert">
                                                    <td style="text-align:center;">
                                                        <select id='type' class='form-control'>
                                                            <option value="0">Please Select Type</option>
                                                            @foreach($type as $t)
                                                                <option value="{{$t->Option}}">{{$t->Option}}</option>
                                                            @endforeach
                                                        </select>
                                                    </td>
                                                    <td style="width:200px;">
                                                        <select id="item_code" class="form-control">
                                                            <option value="" disabled selected>None</option>
                    
                                                        </select>
                                                    </td>
                                                    <td>
                                                        <select class="form-control" id="item_desc">
                                                            <option value="" disabled selected>None</option>
                
                                                        </select>
                                                    </td>
                                                    
                                                    <td style="text-align:center;">
                                                        <input type="number" min="1" id="qty" class="form-control"/>
                                                    </td>
                                                    <td>
                                                        <input type="text" readonly id="unit" class="form-control"/>
                                                    </td>
                                                    <td id="selectPrice">
                                                        {{-- <span id="price"></span> --}}
                                                        <input type="number" min="0" id='price' class="form-control"/>
                                                    </td>
                                                    <td>
                                                        <span id="total"></span>
                                                    </td>
                                                    <td>
                                                        <button style="width:unset;" onclick="add()" class="btn btn-primary btn-sm" id='add_item_btn'><i class="fa fa-plus" aria-hidden="true"></i></button>
                                                    </td>
                                                </tr>
                                                
                                            </tbody>
                                            <tfoot>
                                                <tr>
                                                    <th colspan="6">Total</th>
                                                    <th colspan="2"><span id="totalAmount">RM 0.00</span></th>
                                                </tr>
                                            </tfoot>
                                        </table>
                                    </div>
                                </div>
                                <input type="hidden" id="saveMr" value="{{$mr->Id}}">
                                
                                <br>
                                <div class="row">
                                    <div class="col-md-3">
                                        <button id="submit_btn" style="width:unset;" class="btn btn-primary btn-sm" onclick="insertData()">Submit <i class="fa fa-file-text-o" aria-hidden="true"></i></button>
                                    </div>
                                </div>
                            </div>

                            <div class="tab-pane" id="saved">
                                <div class="row">
                                    <div class="col-md-12">
                                            <table id="savedTable" cellspacing="0" width="100%" padding="30px" style="font-size: 13px;">
                                            </table>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    
	            
             

		    </div>
		</div>
    </div>


       <!-- View material modal-->
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
                                        <th>Action</th>
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

    <!--Edit Modal -->
    <div class="modal fade" id="editModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
            <div class="modal-dialog modal-md" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                        <h4 class="modal-title" id="myModalLabel">Edit</h4>
                    </div>
                    <div class="modal-body">
                        <div class="row">
                            <div class="col-sm-12">
                                <br>
                                <div class='row'>
                                    <div class="form-group">
                                        <label class="col-sm-2">Item Code: </label>
                                        <div class="col-sm-6">
                                            <select id='edit_item_code' class='form-control'>
                                                @foreach($items as $item)
                                                    <option value="{{$item->Id}},{{$item->vendorId}},{{$item->companyid}}"  data-type='{{$item->Type}}'
                                                    data-price="{{$item->Item_Price}}"'>{{$item->Item_Code}} (RM {{$item->Item_Price}})
                                                    </option> 
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                </div>
                                <br>
                                <div class="row">
                                    <div class="form-group">
                                        <label class="col-sm-2">Item Description:</label>
                                        <div class="col-sm-6">
                                            <select id='edit_item_des' class='form-control'>
                                                @foreach($items as $item)
                                                    <option value="{{$item->Id}},{{$item->vendorId}},{{$item->companyid}}"  
                                                        >{{$item->Description}} (RM {{$item->Item_Price}})
                                                    </option> 
                                                @endforeach
                                            </select>                              
                                        </div>
                                    </div>
                                </div>
                                <br>
                                <div class="row">
                                    <div class="form-group">
                                        <label class="col-sm-2">Type:</label>
                                        <div class="col-sm-4">
                                            <input type="text"  class="form-control" id="edit_type" readonly>                        
                                        </div>
                                    </div>
                                </div>
                                <br> 
                                <div class="row">
                                    <div class="form-group">
                                        <label class="col-sm-2">Qty:</label>
                                        <div class="col-sm-4">
                                            <input type="number" min="0" class="form-control" id="edit_qty">                        
                                        </div>
                                    </div>
                                </div>
                                <br>
                                <div class="row">
                                    <div class="form-group">
                                        <label class="col-sm-2">Price:</label>
                                        <div class="col-sm-4">
                                            <div class="input-group">
                                                <span class="input-group-addon" style="background-color:lawngreen;">RM</span>
                                                <input type="number"  class="form-control" id="edit_price">                        
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <br>
                                <br>
                                <div class="row">
                                    <div class="form-group">
                                        <label class="col-sm-2">Total:</label>
                                        <div class="col-sm-4">
                                            <input type="text" disabled class="form-control" id="edit_total">                        
                                        </div>
                                    </div>
                                </div>
                                <input type="hidden" id="edit_id" />
                                <input type="hidden" id="table_rows">
                                <input type="hidden" id="do_no">
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button class="btn btn-default" onclick="edit()">Edit</button>
                        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                    </div>
                </div>
            </div>
        </div>

        <div class="modal fade" id="addModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
                <div class="modal-dialog modal-md" role="document">
                    <div class="modal-content">
                        <div class="modal-header">
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                            <h4 class="modal-title" id="myModalLabel">Add</h4>
                        </div>
                        <div class="modal-body">
                            <div class="row">
                                <div class="col-sm-12">
                                    <span id="new_error" style="color:red;"></span>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-sm-12">
                                    <div class="row">
                                        <div class="form-group">
                                            <label class="col-sm-2">Type:</label>
                                            <div class="col-sm-4">
                                                {{-- <input type="text"  class="form-control" id="new_type" readonly>   --}}
                                                <select id='new_type' class='form-control'>
                                                    <option value="0">Please Select Type</option>
                                                    @foreach($type as $t)
                                                        <option value="{{$t->Option}}">{{$t->Option}}</option>
                                                    @endforeach
                                                </select>  
                                            </div>
                                        </div>
                                    </div>
                                    <br>
                                    <div class='row'>
                                        <div class="form-group">
                                            <label class="col-sm-2">Item Code: </label>
                                            <div class="col-sm-6">
                                                <select id='new_item_code' class='form-control'>
                                                    <option value="" selected>None</option>
                                                    @foreach($items as $item)
                                                        <option value="{{$item->Id}},{{$item->vendorId}},{{$item->companyid}}"   data-type='{{$item->Type}}'
                                                        data-price="{{$item->Item_Price}}"'>{{$item->Item_Code}} (RM {{$item->Item_Price}})
                                                        </option> 
                                                    @endforeach
                                                </select>
                                            </div>
                                        </div>
                                    </div>
                                    <br>
                                    <div class="row">
                                        <div class="form-group">
                                            <label class="col-sm-2">Item Description:</label>
                                            <div class="col-sm-6">
                                                <select id='new_item_des' class='form-control'>
                                                    <option value="" selected>None</option>
                                                    @foreach($items as $item)
                                                        <option value="{{$item->Id}},{{$item->vendorId}},{{$item->companyid}}"  data-price="{{$item->Item_Price}}" 
                                                            >{{$item->Description}} (RM {{$item->Item_Price}})
                                                        </option> 
                                                    @endforeach
                                                </select>                              
                                            </div>
                                        </div>
                                    </div>
                                    <br>
                                    
                                    <div class="row">
                                        <div class="form-group">
                                            <label class="col-sm-2">Qty:</label>
                                            <div class="col-sm-4">
                                                <input type="number" min="0" class="form-control" id="new_qty">                        
                                            </div>
                                        </div>
                                    </div>
                                    <br>
                                    <div class="row">
                                        <div class="form-group">
                                            <label class="col-sm-2">Price:</label>
                                            <div class="col-sm-4">
                                                <input type="number" class="form-control" id="new_price">                        
                                            </div>
                                        </div>
                                    </div>
                                    <br>
                                    <br>
                                    <div class="row">
                                        <div class="form-group">
                                            <label class="col-sm-2">Total:</label>
                                            <div class="col-sm-4">
                                                <input type="text" disabled class="form-control" id="new_total">                        
                                            </div>
                                        </div>
                                    </div>
                                    <input type="hidden" id='materialId'/>
                                </div>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button class="btn btn-default" onclick="newMaterial()">Add</button>
                            <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                        </div>
                    </div>
                </div>
            </div>
        <!--Upload Modal-->
        <div class="modal fade" id="uploadModal">
            <div class="modal-dialog modal-lg">
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span></button>
                        <h4 class="modal-title">Quotation</h4>
                    </div>
                    <div class="modal-body">
                        <div class="row">
                            <div class="col-sm-12">
                                <span id="uploadModal_mess" style="color:green;"></span>
                                <span id="uploadModal_error" style="color:red;"></span>
                            </div>
                        </div>
                        {{-- <div class="row">
                            <div class="col-sm-12">
                                <label class='col-sm-3'>Upload Quotation: </label>
                                <div class="col-sm-8">
                                    <form enctype="multipart/form-data" id='quotationForm'>
                                        <input type="file" id='quotation' name='quotation'>
                                        <input type="hidden" id='id' name='id'>
                                        <input type="hidden" id='type1' name='type'>
                                    </form>
                                </div>
                            </div>
                        </div> --}}
                        <div class="row">
                            <div class="col-sm-12">
                                {{-- <label class='col-sm-3'>Uploaded File:</label>
                                <div class="col-sm-9" id='uploadedFile'>
                                </div> --}}
                                <table id="fileTable" cellspacing="0" width="100%"  style="font-size: 13px;white-space:nowrap;">
                                    <thead>
                                        <tr>
                                            <th>No</th>
                                            <th>Type</th>
                                            <th>Amount</th>
                                            <th>File</th>
                                            <th>Upload by</th>
                                            <th>Status</th>
                                            <th>Requestor Reason</th>
                                            <th>Approver Reason</th>
                                            <th>Action</th>
                                        </tr>
                                    </thead>
                                </table>
                            </div>
                        </div>
                        <br>
                        <input type="hidden" id="mrId">
                        <div class="row">
                            
                            <div class="col-sm-12" id='uploadFileBtn'>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-warning btn-sm" data-dismiss="modal">Close</button>
                    </div>
                </div>
            </div>
        </div>
            <!--Upload file modal-->
        <div class="modal fade" id="uploadFileModal">
            <div class="modal-dialog modal-md">
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span></button>
                        <h4 class="modal-title">Upload Quotation</h4>
                    </div>
                    <div class="modal-body">
                        <div class="row">
                            <div class="col-sm-12">
                                <span id="upload_mess" style="color:green;"></span>
                                <span id="upload_error" style="color:red;"></span>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-sm-12">
                                <label class='col-sm-3'>Upload: </label>
                                <div class="col-sm-8">
                                    <form enctype="multipart/form-data" id='quotationForm'>
                                        <input type="file" id='quotation' name='quotation'>
                                        <input type="hidden" id='id' name='id'>
                                        <input type="hidden" id='type1' name='type'>
                                
                                </div>
                            </div>
                        </div>
                        <br>
                        <div class="row">
                            <div class="col-sm-12">
                                <label class='col-sm-3'>Type: </label>
                                <div class="col-sm-8">
                                    <select id="quotation_type" name='quotation_type'>
                                        <option value="0">Please Select Type</option>
                                        @foreach($type1 as $t)
                                            <option value="{{$t->Option}}">{{$t->Option}}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                        </div>
                        <br>
                        <div class="row">
                            <div class="col-sm-12">
                                <label class="col-sm-3">Amount:</label>
                                <div class="col-sm-6">
                                    <input type="number" id='amount' name='amount'>
                                </div>
                            </div>
                        </div>
                        <br>
                        <div class="row">
                            <div class="col-sm-12" id='requestorReason'>
                                <label class="col-sm-3">Reason:</label>
                                <div class="col-sm-6">
                                   <textarea name="reqReason" id="reqReason" class="form-control"></textarea>
                                </div>
                            </div>
                        </div>
                        </form>
                
                    </div>
                    <div class="modal-footer">
                        <button class="btn btn-primary btn-sm" onclick="uploadQuotation()">Upload</button>
                        <button type="button" class="btn btn-warning btn-sm" data-dismiss="modal">Close</button>
                    </div>
                </div>
            </div>
        </div>
        <!--Import mr modal-->
        <div class="modal fade" id="importMrModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
            <div class="modal-dialog modal-md" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                        <h4 class="modal-title" id="myModalLabel">PO</h4>
                    </div>
                    <div class="modal-body">
                        <div class="row">
                            <div class="col-sm-12">
                                <span id="confirm_error" style="color:red;"></span>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-sm-12">
                                <br>
                                <div class='row'>
                                    <div class="form-group">
                                        <div class="col-sm-12" id="InsertMR">
                                            <table id="viewMrTable" class="display viewPoTable" cellspacing="0" width="100%" padding="30px" style="font-size: 13px;">
                                            <thead>
                                                <tr class="search">
                                                    <th align='center'><input type='hidden' class='search_init' name='0'></th>
                                                    <th align='center'><input type='text' class='search_init' name='1'></th>
                                                    <th align='center'><input type='text' class='search_init' name='2'></th>
                                                    <th align='center' style='width:"39px"'><input type='hidden' class='search_init' name='3'></th>
                                                </tr>
                                                <tr>
                                                <th>No</th>
                                                <th>MR NO</th>
                                                <th>Unique ID</th>
                                                <th>Action</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                
                                            </tbody>
                                            </table>
                                        </div>
                                    </div>
                                </div>
    
                                <br>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                    </div>
                </div>
            </div>
        </div>
        <!--Import Mr item modal-->
        <div class="modal fade" id="mrItemModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
            <div class="modal-dialog modal-lg" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                        <h4 class="modal-title" id="myModalLabel">Item</h4>
                    </div>
                    <div class="modal-body">
                        <div class="row">
                            <div class="col-sm-12">
                                <span id="mritem_error" style="color:red;"></span>
                            </div>
                        </div>
                        <div class="row">
                            <input type="hidden" id='tableNum'>
                            <div class="col-sm-12">
                                <br>
                                <div class='row'>
                                    <div class="form-group">
                                        <div class="col-sm-12">
                                            <table id="mrItemTable" class="display" cellspacing="0" width="100%" padding="30px" style="font-size: 13px;">
                                            <thead>
                                                <tr>
                                                <th>No</th>
                                                <th>Type</th>
                                                <th>Item Code</th>
                                                <th>Description</th>
                                                <th>Unit</th>
                                                <th>Quantity</th>
                                                <th>Price(RM)</th>
                                                <th>Total(RM)</th>
                                                <th><input type='checkbox' id='itemCheckAll' class='itemCheckAll' name='itemCheckAll' onclick='check()'/></th>
                                                </tr>
                                            </thead>
                                            <tbody> 
                                                
                                            </tbody>
                                            </table>
                                        </div>
                                    </div>
                                </div>
    
                                <br>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button class="btn btn-primary" onclick='importMR()'>Import</button>
                        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                    </div>
                </div>
            </div>
        </div>
        <!--Approval Modal-->
        <div class="modal fade" id="approvalModal">
            <div class="modal-dialog modal-md">
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span></button>
                        <h4 class="modal-title">Approval</h4>
                    </div>
                    <div class="modal-body">
                        <div class="row">
                            <div class="col-sm-12">
                                <span id="upload_mess" style="color:green;"></span>
                                <span id="upload_error" style="color:red;"></span>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-sm-12">
                                <div class="col-sm-8" id='approvalMess'>
                                    
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-sm-12" id='reason'>
                                
                            </div>
                        </div>
                
                    </div>
                    <div class="modal-footer">
                        <button class="btn btn-primary btn-sm" id='approvalBtn'></button>
                        <button type="button" class="btn btn-warning btn-sm" data-dismiss="modal">Close</button>
                    </div>
                </div>
            </div>
        </div>
        <!--Edit new modal -->
        <div class="modal fade" id="edit_newModal">
            <div class="modal-dialog modal-md">
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span></button>
                        <h4 class="modal-title">Edit</h4>
                    </div>
                    <div class="modal-body">
                        <div class="row">
                            <div class="col-sm-12">
                                <span id="upload_mess" style="color:green;"></span>
                                <span id="upload_error" style="color:red;"></span>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-sm-12">
                                <label class="col-sm-3">Type</label>
                                <div class="col-sm-6">
                                    <select id="edit_new_type">
                                        @foreach($type as $t)
                                        <option value="{{$t->Option}}">{{$t->Option}}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                        </div>
                        <br>
                        <div class="row">
                            <div class="col-sm-12">
                                <label class="col-sm-3">Item Code</label>
                                <div class="col-sm-6">
                                    <select id="edit_new_code">
                                    </select>
                                </div>
                            </div>
                        </div>
                        <br>
                        <div class="row">
                            <div class="col-sm-12">
                                <label class="col-sm-3">Description</label>
                                <div class="col-sm-6">
                                    <select id="edit_new_desc">
                                    </select>
                                </div>
                            </div>
                        </div>
                        <br>
                        <div class="row">
                            <div class="col-sm-12">
                                <label class="col-sm-3">Unit</label>
                                <div class="col-sm-6">
                                    <input type="text" readonly id="edit_new_unit" class="form-control">
                                </div>
                            </div>
                        </div>
                        <br>
                        <div class="row">
                            <div class="col-sm-12">
                                <label class="col-sm-3">Qty</label>
                                <div class="col-sm-6">
                                    <input type="number" id="edit_new_qty" class="form-control">
                                </div>
                            </div>
                        </div>
                        <br>
                        <div class="row">
                            <div class="col-sm-12">
                                <label class="col-sm-3">Price</label>
                                <div class="col-sm-6">
                                    <input type="number" id="edit_new_price" class="form-control">
                                </div>
                            </div>
                        </div>
                    </div>
                    <input type="hidden" id="rowNumber">
                    <div class="modal-footer">
                        <button class="btn btn-primary btn-sm" onclick="editNewItem()">Edit</button>
                        <button type="button" class="btn btn-warning btn-sm" data-dismiss="modal">Close</button>
                    </div>
                </div>
            </div>
        </div>
</div>
<footer class="main-footer">
	<div class="pull-right hidden-xs">
		<b>Version</b> 2.0.1
	</div>
	<strong>Copyright &copy; 2014-2016 <a href="http://www.softoya.com">TrackerOnTheGo</a>.</strong> All rights reserved.
</footer>

<script type="text/javascript">
var tablerows = document.getElementById('viewTable').getElementsByTagName("tr").length;
var ajaxManager = (function() {
     var requests = [];

     return {
        addReq:  function(opt) {
            requests.push(opt);
        },
        removeReq:  function(opt) {
            if( $.inArray(opt, requests) > -1 )
                requests.splice($.inArray(opt, requests), 1);
        },
        run: function() {
            var self = this,
                oriSuc;

            if( requests.length ) {
                oriSuc = requests[0].complete;

                requests[0].complete = function() {
                     if( typeof(oriSuc) === 'function' ) oriSuc();
                     requests.shift();
                     self.run.apply(self, []);
                };   

                $.ajax(requests[0]);
            } else {
              self.tid = setTimeout(function() {
                 self.run.apply(self, []);
              }, 1000);
            }
        },
        stop:  function() {
            requests = [];
            clearTimeout(this.tid);
        }
     };
}());
 $(function () {
    ajaxManager.run(); 
    $('#project').select2();
    $('#site').select2();
    $("#item_code").select2();
    $("#item_desc").select2();
    $("#type").select2();
    $("#edit_item_code").select2({width:'100%'});
    $("#edit_item_des").select2({width:'100%'});
    $("#new_item_code").select2({width:'100%'});
    $("#new_item_des").select2({width:'100%'});
    $("#new_type").select2({width:'100%'})
    $(".site").select2({
        width:'100%'
    }); 
    $("#edit_new_type").select2({width:'100%'});
    $("#edit_new_code").select2({width:'100%'});
    $("#edit_new_desc").select2({width:'100%'});
    $.ajax({
        type: "get",
        url: "{{url('material/getSaveMrItem')}}",
        data: {
            id:{{$mr->Id}}
        },
        success: function (response) {
            for(var y=0,i=response.length;y<i;y++){
                $('#insert').before("<tr role='row' class='deleteAll' id='row_"+x+"'>\
                <td>"+ response[y].Type +"</td>\
                <td>"+ response[y].Item_Code+"</td>\
                <td>"+response[y].Description +"</td>\
                <td>"+response[y].Qty+"</td>\
                <td>"+ response[y].Unit +"</td>\
                <td>RM "+ response[y].Price +"</td>\
                <td>RM "+ response[y].total +"</td>\
                <td>\
                    <button style='width:unset;' title='Edit' class='btn btn-primary btn-xs' onclick='editNewModal("+x+")'><i class='fa fa-pencil-square-o' aria-hidden='true'></i></button>\
                    <button style='width:unset;' title='Remove' class='btn btn-primary btn-xs'   onclick='removeRow("+x+")'><i class='fa fa-trash' aria-hidden='true'></i></button></td>\
                </tr>");
                let price=$("#"+response[y].Type.toLowerCase()).text();
                let temp=parseFloat(price == "-"? 0 :parseFloat(price));
                temp+=parseFloat(response[y].total);
                $("#"+response[y].Type.toLowerCase()).html(parseFloat(temp).toFixed(2));
                itemArr.push({
                    row:x,
                    total:response[y].total,
                    qty:response[y].Qty,
                    item:response[y].InventoryId,
                    price:response[y].Price,
                    vendor:response[y].vendorId,
                    type:response[y].Type
                });
                calculate+=parseFloat(response[y].total);
                x++;
            }
            $("#totalAmount").html("RM " + parseFloat(calculate).toFixed(2));
        }
    });
   $("#type").on('change',function(){
       $("#item_code").empty();
       $("#item_desc").empty();
        $.ajax({
            type: "get",
            url: "{{url('material/getItemBasedOnType')}}",
            data: {
                type:$("#type").val()
            },
            success: function (response) {
                $('#item_code').append($("<option></option>")
                .attr('value','0')
                .text("None"));
                $.each(response, function(key, value) {   
                    $('#item_code').append($("<option></option>")
                    .attr("value",value.Id+","+value.vendorId+","+value.companyid)
                    .attr('data-unit',value.Unit)
                    .attr('data-price',value.Item_Price)
                    .text(value.Item_Code+" (RM "+value.Item_Price+")")
                    ); 
                });
                $('#item_desc').append($("<option></option>")
                .attr('value','0')
                .text("None"));
                $.each(response, function(key, value) {   
                    $('#item_desc').append($("<option></option>")
                    .attr("value",value.Id+","+value.vendorId+","+value.companyid)
                    .attr('data-unit',value.Unit)
                    .attr('data-price',value.Item_Price)
                    .text(value.Description+" (RM "+value.Item_Price+")")); 
                });
            }
        });
   });
   $("#edit_new_type").on('change',function(){
       $("#edit_new_code").empty();
       $("#edit_new_desc").empty();
        $.ajax({
            type: "get",
            url: "{{url('material/getItemBasedOnType')}}",
            data: {
                type:$("#edit_new_type").val()
            },
            success: function (response) {
                $('#edit_new_code').append($("<option></option>")
                .attr('value','0')
                .text("None"));
                $.each(response, function(key, value) {   
                    $('#edit_new_code').append($("<option></option>")
                    .attr("value",value.Id+","+value.vendorId)
                    .attr('data-unit',value.Unit)
                    .attr('data-price',value.Item_Price)
                    .text(value.Item_Code+" (RM "+value.Item_Price+")")
                    ); 
                });
                $('#edit_new_desc').append($("<option></option>")
                .attr('value','0')
                .text("None"));
                $.each(response, function(key, value) {   
                    $('#edit_new_desc').append($("<option></option>")
                    .attr("value",value.Id+","+value.vendorId)
                    .attr('data-unit',value.Unit)
                    .attr('data-price',value.Item_Price)
                    .text(value.Description+" (RM "+value.Item_Price+")")); 
                });
            }
        });
   });
   $("#amount, #quotation_type").on('change',function(){
        $("#upload_error").html("");
        $.ajax({
            type: "get",
            url: "{{url('material/checkQuotationExceed')}}",
            data: {
                id:$("#mrId").val(),
                type:$("#quotation_type option:selected").val(),
                amount:$("#amount").val()
            },
            success: function (response) {
                if(response == 1){
                    $("#requestorReason").show();
                    if($('#reqReason').length !=0 && $("#reqReason").val() == ""){
                        $("#upload_error").html("Out of Balance, Please enter reason.");
                        check=1;
                    }
                }else if(response == -1 ){
                    $("#upload_error").html("Unable to find the type for this MR.");
                    check=-1;
                }
                else {
                    check=0;
                    $("#requestorReason").hide();
                    $("#reqReason").val('');
                };
            }
        });
   });
   $("#new_type").on('change',function(){
       $("#new_item_code").empty();
       $("#new_item_des").empty();
        $.ajax({
            type: "get",
            url: "{{url('material/getItemBasedOnType')}}",
            data: {
                type:$("#new_type").val()
            },
            success: function (response) {
                $('#new_item_code').append($("<option></option>")
                .attr('value','0')
                .text("None"));
                $.each(response, function(key, value) {   
                    $('#new_item_code').append($("<option></option>")
                    .attr("value",value.Id+","+value.vendorId+","+value.companyid)
                    .attr('data-unit',value.Unit)
                    .attr('data-price',value.Item_Price)
                    .text(value.Item_Code+" (RM "+value.Item_Price+")")
                    ); 
                });
                $('#new_item_des').append($("<option></option>")
                .attr('value','0')
                .text("None"));
                $.each(response, function(key, value) {   
                    $('#new_item_des').append($("<option></option>")
                    .attr("value",value.Id+","+value.vendorId+","+value.companyid)
                    .attr('data-unit',value.Unit)
                    .attr('data-price',value.Item_Price)
                    .text(value.Description+" (RM "+value.Item_Price+")")); 
                });
            }
        });
   });
    $("#item_code").on('change', function () {
        var ele=$(this).val();
       
        if(ele != $('#item_desc').val())
        {
            $('#unit').val($('#item_code option:selected').data('unit'));
            // $('#type').val($('#item_code option:selected').data('type'));
            $("#price").val($("#item_code option:selected").data("price"));
            $("#item_desc").val(ele).change();
        }
             
    });
    $("#edit_item_code").on('change', function () {
        var ele=$(this).val();
        if(ele != $('#edit_item_des').val())
        {
            $("#edit_price").val($("#edit_item_code option:selected").data("price"));
            $('#edit_type').val($('#edit_item_code option:selected').data('type'));
            $("#edit_item_des").val(ele).change();
        }             
    });
    $("#edit_new_code").on('change', function () {
        var ele=$(this).val();
        if(ele != $('#edit_new_desc').val())
        {
            $("#edit_new_price").val($("#edit_new_code option:selected").data("price"));
            $('#edit_new_unit').val($('#edit_new_code option:selected').data('unit'));
            $("#edit_new_desc").val(ele).change();
        }             
    });
    $("#new_item_code").on('change', function () {
        var ele=$(this).val();
        if(ele != $('#new_item_des').val())
        {
            $("#new_price").val($("#new_item_code option:selected").data("price"));
            $("#new_item_des").val(ele).change();
        }             
    });
    $('#qty').on('change',function(){
        $("#total").html(parseFloat($('#qty').val() * $("#price").val()).toFixed(2));
    });
    $('#edit_qty').on('change',function(){
        $("#edit_total").val(parseFloat($('#edit_qty').val() * $("#edit_price").val()).toFixed(2));
    });
    $('#new_qty').on('change',function(){
        $("#new_total").val(parseFloat($('#new_qty').val() * $("#new_price").val()).toFixed(2));
    });
    $("#item_desc").on('change', function () {
        var ele=$(this).val();
        if(ele != $('#item_code').val())
        {
            $('#unit').val($('#item_desc option:selected').data('unit'));
            $("#item_code").val(ele).change();
            $("#price").val($("#item_code option:selected").data("price"));
        } 
    });
    $("#edit_new_desc").on('change', function () {
        var ele=$(this).val();
        if(ele != $('#edit_new_code').val())
        {
            $('#edit_new_unit').val($('#edit_new_code option:selected').data('unit'));
            $("#edit_new_price").val($("#edit_new_code option:selected").data("price"));
            $("#edit_new_code").val(ele).change();
        } 
    });
    $("#new_item_des").on('change', function () {
        var ele=$(this).val();
        if(ele != $('#new_item_code').val())
        {
            $("#new_price").val($("#new_item_des option:selected").data("price"));    
            $("#new_item_code").val(ele).change();

        } 
    });
    $("#edit_item_des").on('change', function () {
        var ele=$(this).val();
        if(ele != $('#edit_item_code').val())
        {
            $("#edit_item_code").val(ele).change();
            $("#edit_price").val($("#edit_item_code option:selected").data("price"));
        }
             
    });
    $('#project').on('change',function(){
        $("#site").empty();
        var check=false;
        $.ajax({
            type: "get",
            url: "{{url('material/getSite')}}",
            data: {
              id:$('#project option:selected').val()  
            },
            success: function (response) {
                for(var x=0,l=response.length;x<l;x++){
                    $('#site').append($("<option></option>")
                    .attr("value",response[x].Id)
                    .text(response[x].site)
                    );
                    check=true;
                }    
                @if($mr->TrackerId)
                    $("#site").val({{$mr->TrackerId}}).change();
                @endif 
            }
        });
    }).change();
    
});
var x=1;
var itemArr=[];
var calculate=0.00;
var updateVendor=[];
var machine=0,mpsb=0,material=0,labour=0,ewallet=0,hardware=0,transport=0;
function add(){
    $("#add_item_btn").attr('disabled',true);
    var type=$("#type option:selected").val();
    var unit=$("#item_code option:selected").data('unit');
    var price=$("#item_code option:selected").data("price");
    var conv_item=$("#item_code option:selected").val().split(",");
    var total=parseFloat($("#qty").val() * price).toFixed(2);
    if(price != $("#price").val()){
        total=parseFloat($("#qty").val() * $("#price").val()).toFixed(2);
        updateVendor.push({
            row:x,
            id:conv_item[1],
            price:$("#price").val(),
            item:conv_item[0],
            company:conv_item[2]
        });
        price=$("#price").val()
    }
    if($("#item_code option:selected").val() != "" && $("#item_desc option:selected").val() != "" && $("#qty").val() != "" && $("#price").val() != ""){
        let tempType=$('#'+type.toLowerCase()).text();
        tempType= tempType == "-" ? 0:parseFloat(tempType);
        tempType+=parseFloat(price*$("#qty").val());
        $("#"+type.toLowerCase()).html(parseFloat(tempType).toFixed(2));
        $('#insert').before("<tr class='deleteAll' id='row_"+x+"'>\
        <td>"+ type +"</td>\
        <td>"+$("#item_code option:selected").text()+"</td>\
        <td>"+$("#item_desc option:selected").text() +"</td>\
        <td>"+$("#qty").val()+"</td>\
        <td>"+ unit +"</td>\
        <td>RM "+ price +"</td>\
        <td>RM "+ total +"</td>\
        <td>\
            <button style='width:unset;' title='Edit' class='btn btn-primary btn-xs' onclick='editNewModal("+x+")'><i class='fa fa-pencil-square-o' aria-hidden='true'></i></button>\
            <button style='width:unset;' title='Remove' class='btn btn-primary btn-xs'   onclick='removeRow("+x+")'><i class='fa fa-trash' aria-hidden='true'></i></button></td>\
        </tr>");
        itemArr.push({
            row:x,
            total:total,
            qty:$("#qty").val(),
            item:conv_item[0],
            price:price,
            vendor:conv_item[1],
            type:type
        });
        calculate+=parseFloat(total);
        $("#totalAmount").html("RM " + parseFloat(calculate).toFixed(2));
        $("#type").val(0).change();
        $("#item_code").val("").change();
        $('#qty').val("");
        $("#price").val("");
        $("#total").empty();
         x++;
    }
    else{
        $("#add_error").html("Please fill in all the details");
        setTimeout(() => {
            $("#add_error").html("");
        }, 3000);
    }
    $("#add_item_btn").attr('disabled',false);
}
async function removeRow(id){
    $("#row_"+ id).remove();
    var filter=itemArr.filter(i=>i.row == id);
    let type=filter[0].type; 
    let total=parseFloat(filter[0].total);
    let temp=$("#"+type.toLowerCase()).text();
    temp=temp == "-" ? 0:parseFloat(temp);
    temp-=parseFloat(filter[0].total);
    $("#"+type.toLowerCase()).html(parseFloat(temp).toFixed(2));
    calculate-=parseFloat(filter[0].total);
    filter=itemArr.filter(i=>i.row != id);
    itemArr=filter;
    $("#totalAmount").html("RM " + parseFloat(calculate).toFixed(2));
}
async function insertData()
{
    $("#submit_btn").attr('disabled', true);
    if(itemArr.length != 0 && $("#site option:selected").val() != "" && $("#project option:selected").val() != "")
    {   
        $.ajaxSetup({
            headers: { 'X-CSRF-Token' : $('meta[name=_token]').attr('content') }
        });
        $.ajax({
            type: "post",
            url: "{{url('material/newRequest')}}",
            data: {
                item:itemArr,
                project:$('#project option:selected').val() ,
                site:$("#site option:selected").val(),
                savemr:$("#saveMr").val()
            },
            success: function (response) {
                if(response == 1)
                {
                    $('.deleteAll').remove();
                    $("#item_code").val("").change();
                    $('#qty').val("");
                    $("#price").empty();
                    $("#total").empty();
                    $("#project").val('').change();
                    $("#site").val('').change();
                    itemArr=[];
                    updateVendor=[];
                    var mess="New Material Request submitted";
                    $("#update-alert ul").html(mess);
                    $("#update-alert").modal('show');
                    setTimeout(() => {
                        window.location.href="{{url('material/MR')}}";
                    }, 2000);
                }
            }
        });
    }
    else{
        $("#submit_btn").attr('disabled', false);
        var mess="Please fill in all the details.";
        $("#warning-alert ul").html(mess);
        $("#warning-alert").modal('show');
    }
}
var tablerows = document.getElementById('viewTable').getElementsByTagName("tr").length;
var click=false;
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
    viewtable1.clear().draw(false);
    viewtable1.page('first').draw('page');
    var tablerows=document.getElementById('viewTable').getElementsByTagName("tr").length-1;
    await $(".removeAll").remove();
    await $.ajax({
        type: "get",
        url: "{{url('material/getMaterial')}}",
        data: {
            id:id
        },
        success: function (response) {
            
            for(var y=0,i=response.price.length;y<i;y++){
                $("#insertTypePrice").append("<div class='col-sm-4'><label>"+response.price[y].Type+": RM "+parseFloat(response.price[y].total).toFixed(2)+"</label></div>")
            }
            if(type == "edit")
            {
                $("#viewModal .modal-footer .btn-warning").before('<button style="float:left;" class="btn btn-info btn-sm deleteBtn" onclick="addModal()">Add Material</button>\
                <button class="btn btn-primary btn-sm deleteBtn" onclick="insertNewMaterial()">Save</button>');
            }
            for(var y=0,i=response.detail.length;y<i;y++)
            {
                var temp ="";
                if(type == "edit")
                {
                    if(response.detail[y].sum){
                    }
                    else{
                        let doNo=response.detail[y].DO_No == null ? "-":response.detail[y].DO_No;
                        temp="<td><a class='btn btn-danger btn-sm'\
                            onclick='editModal("+response.detail[y].Id+","+ (tablerows) +",`"+doNo+"`)'>Edit</a>\
                            <a class='btn btn-warning btn-sm' onclick='removeData(" + response.detail[y].Id + ")'>Remove</a>\
                        </td>";
                    }
                }
                let test="material_"+tablerows;
                var rowNode=viewtable1.row.add([
                    (y+1),response.detail[y].Item_Code,response.detail[y].Description ,response.detail[y].Type,parseFloat(response.detail[y].Qty).toFixed(2),
                    "RM " + response.detail[y].Price,"RM " + parseFloat(response.detail[y].Price*response.detail[y].Qty).toFixed(2),
                    response.detail[y].DO_No == null ? "-":response.detail[y].DO_No,temp  
                    ]).node();
                $(rowNode).attr('id',test);
                $(rowNode).attr('class',"rows_"+response.detail[y].Id);
                viewtable1.draw(false);
                tablerows += 1;
            }
            $("#viewModal").modal('show');
        }
    });
}

function recall(id){
    if(confirm("Are you sure you want to recall?")){
        $.ajaxSetup({
            headers: { 'X-CSRF-Token' : $('meta[name=_token]').attr('content') }
        });
        $.ajax({
            type: "post",
            url: "{{url('material/recall')}}",
            data: {
                id:id
            },
            success: function (response) {
                if(response == 1){
                    oTable.ajax.reload(function(){
                        $('#pendingtab').html("Pending Approval" + "[" + oTable.rows().count() +"]");
                    });
                    oTable1.api().ajax.url("{{ asset('/Include/materialrequest.php') }}").load(function(){
                        $('#approvedtab').html("Approved" + "[" + oTable1.api().rows().count() +"]");
                    });
                    oTable2.ajax.reload(function(){
                        $('#rejectedtab').html("Rejected" + "[" + oTable2.rows().count() +"]");
                    });
                    // oTable3.ajax.reload(function(){
                    //     $('#recalledtab').html("Recalled" + "[" + oTable3.rows().count() +"]");
                    // });
                    oTable3.api().ajax.url("{{ asset('/Include/materialrequest.php') }}").load(function(){
                        $('#recalledtab').html("Recalled" + "[" + oTable3.api().rows().count() +"]");
                    });
                }
            }
        });
    }
}
var ori_price=0;
async function editModal(id,row,doNo){
   $("#editModal").modal('show');
   $("#do_no").val(doNo);
   $.ajax({
       type: "get",
       url: "{{url('material/getItemDetail')}}",
       data: {
           id:id
       },
       success: function (response) {
            $("#edit_id").val(id);   
            var exist=($("#edit_item_code option[value='"+response[0].InventoryId+","+response[0].vendorId+","+response[0].CompanyId+"']").length > 0)
            if(exist)
            {
                $("#edit_item_code").val(response[0].InventoryId+","+response[0].vendorId+","+response[0].CompanyId).change();
                $("#edit_price").val(response[0].Price);
                $("#edit_total").val(parseFloat(response[0].Qty * response[0].Price).toFixed(2));
                ori_price=response[0].Price;
            }
            else{
                $('#edit_item_code').append($("<option></option>")
                    .attr("value",response[0].InventoryId+","+response[0].vendorId+","+response[0].CompanyId)
                    .attr('data-price',response[0].Item_Price)
                    .attr('data-type',response[0].Type)
                    .text(response[0].Item_Code + " (RM "+ response[0].Item_Price + ")")
                );
                $('#edit_item_des').append($("<option></option>")
                    .attr("value",response[0].InventoryId+","+response[0].vendorId+","+response[0].CompanyId)
                    .text(response[0].Description + " (RM "+ response[0].Item_Price + ")")
                
                );
                $("#edit_type").val(response[0].Type);
                $("#edit_item_code").val(response[0].InventoryId+","+response[0].vendorId+","+response[0].CompanyId).change();
                $("#edit_price").val(response[0].Item_Price);
                $("#edit_total").val(parseFloat(response[0].Qty * response[0].Item_Price).toFixed(2));
                ori_price=response[0].Item_Price;
            }
            $("#edit_qty").val(response[0].Qty);
            $("#edit_total").val(parseFloat(response[0].Qty * response[0].Price).toFixed(2));
            $("#table_rows").val(row);
       }
   });
}//edit modal

function updateTable(rows,cols,content){
    var update=document.getElementById('viewTable').rows[parseInt(rows,9)].cells;
    update[parseInt(cols,9)].innerHTML=content;
}//update table 

var editArr=[];
function checkArray1(arr, obj) {
    const index = arr.findIndex((e) => e.row === obj.row);
    if (index === -1) {
        arr.push(obj);
    } else {
        arr[index] = obj;
    }
}//checkarray
async function edit()
{
    var conv_item=$("#edit_item_code option:selected").val().split(",");
    var tablerow=$("#table_rows").val();
    if(ori_price != $("#edit_price").val())
    {
        var temp={
            row:tablerow,
            id:$("#edit_id").val(),
            qty:$("#edit_qty").val(),
            item:conv_item[0],
            price:$("#edit_price").val(),
            vendor:conv_item[1],
            total:$("#edit_total").val(),
            company:conv_item[2],
            update:true,
        };
    }
    else{
        var temp={
            row:tablerow,
            id:$("#edit_id").val(),
            qty:$("#edit_qty").val(),
            item:conv_item[0],
            price:$("#edit_price").val(),
            vendor:conv_item[1],
            total:$("#edit_total").val(),
            update:false,
        };
    }
    
    await checkArray1(editArr,temp);
    let doNo=$("#do_no").val();
    var code=$("#edit_item_code option:selected").text().split("(RM");
    var des=$("#edit_item_des option:selected").text().split("(RM");
    let trow=document.getElementById('viewTable').getElementsByTagName("tr").length;
    viewtable1.row("#material_"+tablerow).data([
        tablerow,code[0],des[0],$("#edit_type").val(),$("#edit_qty").val(),"RM " + parseFloat($("#edit_price").val()).toFixed(2),"RM " + parseFloat($("#edit_price").val() * $("#edit_qty").val()).toFixed(2),
        doNo,"<td><a class='btn btn-danger btn-sm'\
            onclick='editModal("+$("#edit_id").val()+","+ (tablerow) +")'>Edit</a>\
            <a class='btn btn-warning btn-sm' onclick='removeData(" + $("#edit_id").val() + ")'>Remove</a>\
        </td>"
    ])
    $("#editModal").modal('hide');
    $("#save_mess").html("*Please click save button to save details.");
}
function checkArray(arr, obj) {
    const index = arr.findIndex((e) => e.id === obj.id);
    if (index === -1) {
        arr.push(obj);
    } else {
        arr[index] = obj;
    }
}//checkarray
var newArr=[];
var removeArr=[];
async function addModal(){
    $("#addModal").modal('show');
}
async function newMaterial(){
    if($("#new_item_code option:selected") != "" && $("#new_qty").val() != ""){
        var countrow=document.getElementById('viewTable').getElementsByTagName("tr").length;
        var conv_item=$("#new_item_code option:selected").val().split(",");
        var ori=$("#new_item_code option:selected").data('price');
        if($("#new_price").val() != ori){
            var temp={
                id:countrow,
                qty:$("#new_qty").val(),
                total:$("#new_total").val(),
                price:$("#new_price").val(),   
                vendor:conv_item[1],
                item:conv_item[0],
                update:"true",
                company:conv_item[2]
            };
        }else{
            var temp={
                id:countrow,
                qty:$("#new_qty").val(),
                total:$("#new_total").val(),
                price:$("#new_price").val(),   
                vendor:conv_item[1],
                item:conv_item[0],
                update:"false",
                company:0
            };
        }
        checkArray(newArr,temp);
        var code=$("#new_item_code option:selected").text().split("(RM");
        var des=$("#new_item_des option:selected").text().split("(RM");

        viewtable1.row.add([
            countrow,code[0],des[0],$("#new_type").val(),$("#new_qty").val(),"RM "+ $("#new_price").val(),"RM "+ parseFloat($("#new_price").val() * $("#new_qty").val()).toFixed(2),
        "-","<a class='btn btn-primary btn-sm' onclick='remove("+countrow+")'>Remove</a>"
        ]).node().id="row_"+countrow;
        viewtable1.draw(false);
        $("#addModal").modal('hide');
        $("#new_qty").val("");
        $("#new_item_code").val("").change();
        $("#new_total").val(0.00);
        $("#save_mess").html("*Please Click save button to save details.");
    }
    else{
        $("#new_error").html("Please fill in all the details");
        setTimeout(() => {
            $("#new_error").html("");
        }, 4000);
    }
}
function remove(row)//remove new material 
{
    viewtable1.row("#row_"+ row).remove().draw(false);
    var filter=newArr.filter(n=>n.id != row);
    newArr=filter;
    if(newArr.length == 0)
        $("#save_mess").html("");
}//remove temporary new material
function removeData(id)
{
    var temp={
        id:id,
    };
    checkArray(removeArr,temp);
    viewtable1.row(".rows_"+id).remove().draw(false);
    $("#save_mess").html("*Please click save button to save details.");
}//remove existing material
async function insertNewMaterial(id)
{
    if(newArr.length != 0 || editArr.length != 0 || removeArr.length != 0)
    {
        $.ajaxSetup({
        headers: { 'X-CSRF-Token' : $('meta[name=_token]').attr('content') }
        });
        $.ajax({
            type: "post",
            url: "{{url('material/saveDetails')}}",
            data: {
                new:newArr,
                edit:editArr,
                remove:removeArr,
                id:$('#materialId').val()
            },
            success: function (response) {
                if(response == 1){
                    $('#viewModal').modal('hide');
                    $("#save_mess").html("");
                    var mess="Saved";
                    $("#update-alert ul").html(mess);
                    $("#update-alert").modal('show');
                }
            }
        });
    }
    else{
        $("#view_error").html('Nothing to save!');
        setTimeout(() => {
            $("#view_error").html(""); 
        }, 5000);
    }
    
}//insert material to db

function resubmit(id){
    if(confirm("Are you sure you want to resubmit?"))
    {
        
        $.ajaxSetup({
            headers: { 'X-CSRF-Token' : $('meta[name=_token]').attr('content') }
        });
        $.ajax({
            type: "post",
            url: "{{url('material/resubmit')}}",
            data: {
                id:id
            },
            success: function (response) {
                oTable1.api().ajax.url("{{ asset('/Include/materialrequest.php') }}").load(function(){
                    $('#approvedtab').html("Approved" + "[" + oTable1.api().rows().count() +"]");
                });
                oTable3.api().ajax.url("{{ asset('/Include/materialrequest.php') }}").load(function(){
                    $('#recalledtab').html("Recalled" + "[" + oTable3.api().rows().count() +"]");
                });
            }
        });
    }
}
function generatePO(id)
{
    if(confirm("Are you sure you want to generate PO?"))
    {
        $.ajaxSetup({
            headers: { 'X-CSRF-Token' : $('meta[name=_token]').attr('content') }
        });
        $.ajax({
            type: "post",
            url: "{{url('material/generatePO')}}",
            data: {id:id},
            success: function (response) {

                if(response == 1){
                    window.open('{{url('material/POConfirmation')}}/' +id);
                }else{
                    window.open('{{url('material/PO')}}/' +id);
                }
            }
        });
    }
}
async function checkQuotationAmount(){
    var check=0;
    await $.ajax({
        type: "get",
        url: "{{url('material/checkQuotationExceed')}}",
        data: {
            id:$("#mrId").val(),
            type:$("#quotation_type option:selected").val(),
            amount:$("#amount").val()
        },
        success: function (response) {
            if(response == 1){
                check=1;
            }else check=0;
        }
    });
    return await check;
}
function uploadModal(id,type)
{
    $("#requestorReason").hide()
    filetable.clear().draw();
    $("#type1").val(type);
    $("#id").val(id);
    $("#mrId").val(id);
    $("#uploadModal").modal('show');
    if(type == 'material'){
        type='PO Quotation';
    }else if(type == 'item'){
        type='Item Quotation';
    }
    $.ajax({
        type: "get",
        url: "{{url('material/getFile')}}",
        data: {
            id:id,
            type:type
        },
        success: function (response) {
            if(response.mr.UserId == {{$me->UserId}} || {{$me->Upload_Quotation}})
                $("#uploadFileBtn").html("<button class='col-sm-3 btn btn-sm btn-primary' onclick='uploadFileModal()'>Upload</button>");
            else    
                $("#uploadFileBtn").html('');
            for(var y=0,i=response.data.length;y<i;y++){
            @if($me->Quotation_Approval)
                 filetable.row.add([
                    "",response.data[y].Type,response.data[y].Amount,"<a target='_blank' href='"+response.data[y].Web_Path+"'>"+response.data[y].File_Name+"</a>",response.data[y].Name,
                    response.data[y].Status != "" ? response.data[y].Status:"-",response.data[y].Requestor_Reason != "" ? response.data[y].Requestor_Reason:"-",response.data[y].Reason != "" ? response.data[y].Reason:"-",
                    response.data[y].Status == "Pending Approval" ? "<a class='btn btn-sm btn-primary' onclick='approvalModal("+response.data[y].Id+",`approve`)'>Approve</a> \
                    <a class='btn btn-sm btn-warning' onclick='approvalModal("+response.data[y].Id+",`reject`)'>Reject</a>":"-",response.data[y].Reason != "" ? response.data[y].Reason:"-"
                ]).draw(false);
            @else
            filetable.row.add([
                    "",response.data[y].Type,response.data[y].Amount,"<a target='_blank' href='"+response.data[y].Web_Path+"'>"+response.data[y].File_Name+"</a>",response.data[y].Name,
                    response.data[y].Status != "" ? response.data[y].Status:"-",response.data[y].Requestor_Reason != "" ? response.data[y].Requestor_Reason:"-",response.data[y].Reason != "" ? response.data[y].Reason:"-",
                    "-",response.data[y].Reason != "" ? response.data[y].Reason:"-"
                ]).draw(false);
            @endif
            }                
        }
    });
}
async function uploadQuotation()
{
    if(check == 1){
        if($("#reqReason").val() == ""){
            $("#upload_error").html("Please enter reason.");
            setTimeout(() => {
                $("#upload_error").html("");
            }, 3000);
            return;
        }
    }    
    if(check == -1){
        $("#upload_error").html("Unable to find the type for this MR.");
        setTimeout(() => {
            $("#upload_error").html("");
        }, 3000);
        return ;
    }
    if(document.getElementById("quotation").files.length > 0 )
    {
        $.ajaxSetup({
            headers: { 'X-CSRF-Token' : $('meta[name=_token]').attr('content') }
        });
        $.ajax({
            type: "post",
            url: "{{url('material/uploadQuotation')}}",
            data: new FormData($("#quotationForm")[0]),
            contentType: false,
            processData: false,
            success: function (response) {
                $("#uploadModal_mess").html("File uploaded.");
                $("#quotation_type").val(0).change();
                $("#amount").val(0);
                $("#uploadFileModal").modal('hide');
                $("#quotation").val('');
                $("#reqReason").val('');
                
                setTimeout(() => {
                    $("#uploadModal_mess").html('');
                }, 3000);
                @if($me->Quotation_Approval)
                filetable.row.add([
                    "",response.detail.Type,response.detail.Amount,"<a target='_blank' href='"+response.url+"'>"+response.file+"</a>",response.detail.Name,
                    response.detail.Status != "" ? response.detail.Status:"-",response.detail.Requestor_Reason != "" ? response.detail.Requestor_Reason:"-",response.detail.Reason != "" ? response.detail.Reason:"-",
                    response.detail.Status == "Pending Approval" ? "<a class='btn btn-sm btn-primary' onclick='approvalModal("+response.detail.Id+",`approve`)'>Approve</a> \
                    <a class='btn btn-sm btn-warning' onclick='approvalModal("+response.detail.Id+",`reject`)'>Reject</a>":"-",
                    
                ]).draw(false);
                @else
                filetable.row.add([
                    "",response.detail.Type,response.detail.Amount,"<a target='_blank' href='"+response.url+"'>"+response.file+"</a>",response.detail.Name,
                    response.detail.Status != "" ? response.detail.Status:"-",response.detail.Requestor_Reason != "" ? response.detail.Requestor_Reason:"-",response.detail.Reason != "" ? response.detail.Reason:"-",
                    "-",
                    
                ]).draw(false);
                @endif
                // if(response.url.includes('.pdf')){
                //     $("#uploadedFile").html("<a target='_blank' href='"+response.url+"'>"+response.file+"</a>");
                // }else
                //     $("#uploadedFile").html("<img width='200px' height='200px' src='"+response.url+"'/>");
            }
        });
    }else{
        $("#upload_error").html("Please upload file");
        setTimeout(() => {
            $("#upload_error").html("Please upload file");
        }, 3000);
    }
    
}
function uploadFileModal(){
    $("#uploadFileModal").modal('show');
}
function approvalModal(id,type){
    $("#approvalModal").modal('show');
    $("#approvalBtn").attr('onclick','approval('+id+',"'+type+'")');
    $("#approvalBtn").html(type.substr(0,1).toUpperCase()+type.substr(1));
    $("#approvalMess").html("Are you sure you want to " + type + "?");
    if(type == "reject"){
        $("#reason").html('<label class="col-sm-3">Reason: </label>\
        <div class="col-sm-8"><textarea id="reject_reason" class="form-control" cols="8" rows="3"></textarea></div>');
    }else{
        $("#reason").html('');
    }
}
function approval(id,type){
    $.ajaxSetup({
        headers: { 'X-CSRF-Token' : $('meta[name=_token]').attr('content') }
    });
    $.ajax({
        type: "post",
        url: "{{url('material/quotationApproval')}}",
        data: {
            status:type,
            id:id,
            reason:$("#reject_reason").val(),
            mrId:$("#mrId").val()
        },
        success: function (response) {
            $("#approvalModal").modal('hide');
            uploadModal($("#mrId").val(),"PO Quotation");
        }
    });
}

function saveMR(){
    if(confirm("Are you sure you want to save this MR?")){
        $.ajaxSetup({
            headers: { 'X-CSRF-Token' : $('meta[name=_token]').attr('content') }
        });
        $.ajax({
            type: "post",
            url: "{{url('material/saveMR')}}",
            data: {
                arr:itemArr,
                project:$('#project option:selected').val() ,
                site:$("#site option:selected").val(),
                savemr:$("#saveMr").val()
            },
            success: function (response) {
                if(response > 0){
                    $("#saveMr").val(response);
                    $("#update-alert ul").html("Saved");
                    $("#update-alert").modal('show');
                    window.history.pushState({}, null, '{{url("material/saveMr")}}/'+response);
                }
            }
        });
    }
}
function editNewModal(id){
    let filter=itemArr.filter(a=>a.row == id);
    $("#edit_new_type").val(filter[0].type).change();
    $("#edit_new_qty").val(filter[0].qty);
    setTimeout(() => {
        $("#edit_new_code").val(filter[0].item+','+filter[0].vendor).change();  
    }, 1000);
    $("#rowNumber").val(id);
    $("#edit_newModal").modal('show');
}
function editNewItem(){
    let tblrow=$("#rowNumber").val();
    let type=$("#edit_new_type").val();
    let qty=parseFloat($("#edit_new_qty").val());
    let code=$("#edit_new_code option:selected").text();
    let des=$("#edit_new_desc option:selected").text();
    let price=parseFloat($("#edit_new_price").val());
    let unit=$("#edit_new_unit").val();
    let total=parseFloat(qty*price).toFixed(2);
    let conv_item=$("#edit_new_code option:selected").val().split(",");
    let temp={
        row:parseInt(tblrow),
        total:total,
        qty:qty,
        item:conv_item[0],
        price:price,
        vendor:parseInt(conv_item[1]),
        type:type
    };
   
    checkArray1(itemArr,temp);
    let updatetable=$("#row_"+tblrow).find('td');
    updatetable.eq(0).html(type);
    updatetable.eq(1).html(code);
    updatetable.eq(2).html(des);
    updatetable.eq(3).html(parseFloat(qty).toFixed(2));
    updatetable.eq(4).html(unit);
    updatetable.eq(5).html("RM "+parseFloat(price).toFixed(2));
    updatetable.eq(6).html("RM "+parseFloat(total).toFixed(2));
    updatetable.eq(7).html("<button style='width:unset;' title='Edit' class='btn btn-primary btn-xs' onclick='editNewModal("+tblrow+")'><i class='fa fa-pencil-square-o' aria-hidden='true'></i></button>\
    <button style='width:unset;' title='Remove' class='btn btn-primary btn-xs'   onclick='removeRow("+tblrow+")'><i class='fa fa-trash' aria-hidden='true'></i></button></td>");
    $("#edit_newModal").modal('hide');
}
function importMrModal(){
    $("#importMrModal").modal('show');
    viewMrTable.api().clear().draw();
    ajaxManager.addReq({
        type: 'get',
        url: '{{url("material/getMr")}}',
        success: function(response){
            for(var y=0,i=response.length;y<i;y++){
                viewMrTable.api().row.add([
                    "",response[y]['MR_No'],response[y]['Unique Id'],
                    "<a style='width:unset;' class='btn btn-default btn-sm' onclick='importMrItemModal("+response[y].Id+")'><i class='fa fa-download' aria-hidden='true'></i></a>"
                ]).draw(false);
            }
        }
    });
}
function importMrItemModal(id){
    mrItemTable.api().clear().draw();
    ajaxManager.addReq({
        type: 'get',
        url: '{{url("material/getImportMRItem")}}',
        data:{id:id},
        success: function(response){
            let res=JSON.parse(response);
            for(var y=0,i=res.length;y<i;y++){
                mrItemTable.api().row.add([
                    "",res[y]['Type'],res[y]['Item_Code'],res[y]['Description'],res[y]['Unit'],res[y]['Qty'],res[y]['Price'],
                    res[y]['total'],"<input type='checkbox' class='itemCheck' value='"+res[y]['InventoryId']+","+res[y]['vendorId']+"'/>"
                ]).draw(false);
                $("#mrItemModal").modal('show');
            }
        }
    });
}
function check(index){
    if ($("#itemCheckAll").is(':checked')){
        $(".itemCheck").prop("checked", true);
        $(".itemCheck").trigger("change");
        mrItemTable.api().rows().select();
    }else{
        $(".itemCheck").prop("checked", false);
        $(".itemCheck").trigger("change");
        mrItemTable.api().rows().deselect();
    }
}
function importMR(){
    $('#mrItemTable tr').each(function(i,row){
        var row=$(row);
        checkbox=row.find('.itemCheck:checkbox:checked');
        checkbox.each(function(i,checkb){
        var tblrow=$(this).closest('tr');
        let val=$(this).val().split(",");
        mrItemTable.api().row(tblrow)
        .every(function (rowIdx, tableLoop, rowLoop) {
            let type=mrItemTable.api().cell(rowIdx,1).data();
            let code=mrItemTable.api().cell(rowIdx,2).data();
            let qty=parseFloat(mrItemTable.api().cell(rowIdx,5).data());
            let desc=mrItemTable.api().cell(rowIdx,3).data();
            let unit=mrItemTable.api().cell(rowIdx,4).data();
            let price=mrItemTable.api().cell(rowIdx,6).data();
            let total=parseFloat(qty*price).toFixed(2);
            itemArr.push({
                row:x,
                total:total,
                qty:qty,
                item:val[0],
                price:price,
                vendor:val[1],
                type:type
            });
            $('#insert').before("<tr role='row' class='deleteAll' id='row_"+x+"'>\
                <td>"+ type +"</td>\
                <td>"+ code+"</td>\
                <td>"+desc +"</td>\
                <td>"+qty+"</td>\
                <td>"+ unit +"</td>\
                <td>RM "+ price +"</td>\
                <td>RM "+ parseFloat(price*qty).toFixed(2) +"</td>\
                <td>\
                    <button style='width:unset;' title='Edit' class='btn btn-primary btn-xs' onclick='editNewModal("+x+")'><i class='fa fa-pencil-square-o' aria-hidden='true'></i></button>\
                    <button style='width:unset;' title='Remove' class='btn btn-primary btn-xs'   onclick='removeRow("+x+")'><i class='fa fa-trash' aria-hidden='true'></i></button></td>\
                </tr>");
            x++;
            calculate+=parseFloat(total);
            $("#totalAmount").html("RM " + parseFloat(calculate).toFixed(2));
            let temp=$("#"+type.toLowerCase()).text();
            temp=temp == "-"? 0:parseFloat(temp);
            temp+=parseFloat(qty*price);
            $("#"+type.toLowerCase()).html(temp.toFixed(2));
        });
      })
      $("#mrItemModal").modal('hide');
    });
}
</script>

@endsection