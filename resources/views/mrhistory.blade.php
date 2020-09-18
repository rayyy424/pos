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
        table.dataTable tbody th,table.dataTable tbody td 
        {
             white-space: nowrap;
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
        var table,itemtable;
        $(function () {
            table=$("#historyTable").dataTable({
            ajax: {
				"url": "{{ asset('/Include/mrhistory.php') }}",
                "data":{
                    materialId:{{$id}},
                }
			},columnDefs: [
				{ "visible": false, "targets": [1] },
                {"className": "dt-center", "targets": "_all"},
			],
			responsive: false,
			sScrollX: "100%",
			bScrollCollapse: true,
			bAutoWidth: true,
			sScrollY: "100%",
			dom: "tp",
			iDisplayLength:10,
            rowId:"mrhistory.Id",
			columns: [
				{ data: null,"render":"", title:"No"},
                { data: "mrhistory.Id"},
                { data:"mrhistory.MR_No",title:"MR NO"},
                { data:'users.Name',title:"Updated By"},
                { data:'mrhistory.created_at',title:'Created_At'},
                { data:null,title:"Action",
                render:function(data){
                    return '<a style="width:unset;" class="btn btn-default btn-xs" onclick="itemModal('+data.mrhistory.Id+')"><i class="fa fa-eye" aria-hidden="true"></i></a>';
                }}
            ],
            });
            table.api().on( 'order.dt search.dt', function () {
                table.api().column(0, {search:'applied', order:'applied'}).nodes().each( function (cell, i) {
                    cell.innerHTML = i+1;
                } );
            }).draw();

            itemtable=$("#itemTable").dataTable({
            ajax: {
				"url": "{{ asset('/Include/mrhistoryitem.php') }}",
                "data":function(d){
                    d.HistoryId=$("#historyId").val()
                }
			},columnDefs: [
				{ "visible": false, "targets": [1] },
                {"className": "dt-center", "targets": "_all"},
			],
			responsive: false,
			sScrollX: "100%",
			bScrollCollapse: true,
			bAutoWidth: true,
			sScrollY: "100%",
			dom: "tp",
			iDisplayLength:10,
            rowId:"mrhistoryitem.Id",
			columns: [
				{ data: null,"render":"", title:"No"},
                { data: "mrhistoryitem.Id"},
                { data:"inventories.Item_Code",title:"Item Code"},
                { data:'inventories.Description',title:"Description"},
                { data:'inventories.Unit',title:'Unit'},
                { data:null,title:"Qty",
                render:function(data){
                    if(data.mrhistoryitem.Reason)
                        return data.mrhistoryitem.Qty;
                    else {
                        if(data.mrhistoryitem.OldQty)
                            return data.mrhistoryitem.OldQty + " to " + parseFloat(data.mrhistoryitem.Qty).toFixed(2);
                        else return data.mrhistoryitem.Qty;
                    };
                }},
                { data:null,title:"Price",
                render:function(data){
                    if(data.mrhistoryitem.Reason)
                        return data.mrhistoryitem.Price;
                    else {
                        if(data.mrhistoryitem.OldPrice)
                            return data.mrhistoryitem.OldPrice + " to " + parseFloat(data.mrhistoryitem.Price).toFixed(2);
                        else return data.mrhistoryitem.Price;
                    }
                        
                }},
                { data:null,title:"Total",
                render:function(data){
                    return parseFloat(data.mrhistoryitem.Price*data.mrhistoryitem.Qty).toFixed(2);
                }},
                { data:'mrhistoryitem.Type',title:"Type"},
                { data:"mrhistoryitem.Reason",title:"Reason",
                render:function(data){
                    if(data)
                        return data;
                    else return "-";
                }}
            ],
            });
            itemtable.api().on( 'order.dt search.dt', function () {
                itemtable.api().column(0, {search:'applied', order:'applied'}).nodes().each( function (cell, i) {
                    cell.innerHTML = i+1;
                } );
            }).draw();
            $('#itemModal').on('shown.bs.modal', function (e) {
                $.fn.dataTable.tables({ visible: true, api: true }).columns.adjust();
            });
        });
        
    </script>

@endsection

@section('content')

<div class="content-wrapper">
    <!-- Content Header (Page header) -->
	<section class="content-header">
        <h1>MR History<small>Admin</small></h1>
        
		<ol class="breadcrumb">
            <li><a href="{{ url('/home') }}"><i class="fa fa-dashboard"></i> Home</a></li>
			<li><a href="#">Admin</a></li>
			<li class="active">MR History</li>
		</ol>
	</section>
    
	<section class="content">
        
        <br><br>
        <div class="row">
            <div class="box">
                <div class="box-body">
                    <div class="row">
                        <div class="col-md-12">
                            <table id="historyTable" cellspacing="0" width="100%" style="font-size: 13px;">
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="modal fade" id="itemModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
            <div class="modal-dialog modal-lg" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                        <h4 class="modal-title" id="myModalLabel">MR Item</h4>
                    </div>
                    <div class="modal-body">
                        <div class="row">
                            <div class="col-sm-12">
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-sm-12">
                                <br>
                                <div class='row'>
                                    <div class="form-group">
                                        <div class="col-sm-12">
                                            <table id="itemTable" class="display itemTable" cellspacing="0" width="100%" padding="30px" style="font-size: 13px;">                                                
                                            </table>
                                        </div>
                                    </div>
                                </div>
                                <input type="hidden" id="historyId">
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
	</section>
</div>
<footer class="main-footer">
	<div class="pull-right hidden-xs">
		<b>Version</b> 1.0.0
	</div>
	<strong>Copyright &copy; 2014-2016 <a href="http://www.softoya.com">TrackerOnTheGo</a>.</strong> All rights reserved.
</footer>
<script>
function itemModal(id){
    $('#itemModal').modal('show');
    $("#historyId").val(id);
    itemtable.api().ajax.reload();
}
</script>
@endsection