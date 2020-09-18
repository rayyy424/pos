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
      {{-- chart js --}}
      <script type="text/javascript" language="javascript" src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.4.0/Chart.bundle.min.js"></script>

    <script type="text/javascript" language="javascript" class="init">
    $(window).trigger('resize');
    var oTable,oTable1,oTable2,oTable3,materialeditor,viewtable1,filetable,savedTablemmrItemTable,viewMrTable;
    $(function () {
        viewMrTable=$("#viewMrTable").dataTable({
            dom:"tp"
        });
        viewMrTable.on( 'order.dt search.dt', function () {
			viewMrTable.api().column(0, {search:'applied', order:'applied'}).nodes().each( function (cell, i) {
				cell.innerHTML = i+1;
			} );
		} ).api().draw();
        mrItemTable=$("#mrItemTable").dataTable({
            "pagingType": "full_numbers",
            "dom": 'T<"clear">lrtip',
            "lengthMenu": [[10, 25, 50, -1],[10, 25, 50, "All"]]

        });
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
        savedTable=$("#savedTable").dataTable();
        $('#savedtab').html("Saved " + "[" + savedTable.api().rows().count() +"]")

        filetable.on( 'order.dt search.dt', function () {
			filetable.column(0, {search:'applied', order:'applied'}).nodes().each( function (cell, i) {
				cell.innerHTML = i+1;
			} );
		} ).draw();
        $('#materialTable').dataTable({
            dom:"",
            bSort:false
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
            createdRow: function (row, data, dataIndex) {
                $(row).attr('id', "material_"+(dataIndex+1));
            }
        });
        viewtable1.on( 'order.dt search.dt', function () {
			viewtable1.column(0, {search:'applied', order:'applied'}).nodes().each( function (cell, i) {
				cell.innerHTML = i+1;
			} );
		} ).draw();
       
        oTable=$("#pendingTable").DataTable({
            ajax: {
				"url": "{{ asset('/Include/materialrequest.php') }}",
				"data":{
                    status:"%Pending%"
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
			dom: "frtip",
			iDisplayLength:10,
            rowId:"material.Id",
            fnInitComplete: function(oSettings, json) {
                $('#pendingtab').html("Pending Approval" + "[" + oTable.rows().count() +"]")
            },
			columns: [
				{ data: null,"render":"", title:"No"},
                { data: "material.Id"},
                { data: "material.MR_No", title: "MR NO",
                  render:function(data){
                    if( data == "")
                        return "-"
                    else return data;
                }},
                { data:"users.Name" , title:"Requestor Name"},
                { data: "tracker.`Site Name`", title: "Site Name"},
                { data: "materialstatus.Status", title: "Status"},
                { data:"material.created_at",title:"Created At"},
                { data: "material.Total", title: "Total",
                  render: function ( data, type, row, meta ) {
                    return "RM "+ data;
                }}, 
                {
                    data:null,
                    title:"Action",
                    render:function(data,type,row,meta){
                        var f='viewMaterial('+data.material.Id+',"view")';
                        var r="";
                        if(data.material.UserId == {{$me->UserId}})
                            r=" <a class='btn btn-primary btn-sm' onclick='recall("+data.material.Id+")'>Recall</a>";
                        return "<a class='btn btn-info btn-sm' onclick='"+f+"'>View Material</a>" + r;
                        
                    }
                },{
                    data:null,
                    title:"Print",
                    render:function(data){
                        return "<a target='_blank' href='{{url('material/print')}}/"+data.material.Id+"'>Print</a>";
                    }
                }
			],
			select: {
                style:    'os',
                selector: 'td'
            },
        });

        oTable.on( 'order.dt search.dt', function () {
			oTable.column(0, {search:'applied', order:'applied'}).nodes().each( function (cell, i) {
				cell.innerHTML = i+1;
			} );
		} ).draw();
        $(".pendingTable thead input").keyup ( function () {

            /* Filter on the column (the index) of this element */
            if ($('#pendingTable').length > 0)
            {
                var colnum=document.getElementById('pendingTable').rows[0].cells.length;
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

        oTable1=$("#approvedTable").dataTable({
            ajax: {
				"url": "{{ asset('/Include/materialrequest.php') }}",
				"data":{
					
                    @if(!$me->View_Material_Request)
                    id:{{$me->UserId}},
                    @endif
                    status:"%Approved%"
				}
			},
			columnDefs: [
				{ "visible": false, "targets": [1] },
                {"className": "dt-center", "targets": [0,2,3,4,5,6,8]},
                {"className": "dt-right", "targets": [7]}
			],
			responsive: false,
			colReorder: false,
			sScrollX: "100%",
			bScrollCollapse: true,
			bAutoWidth: true,
			sScrollY: "100%",
			dom: "tip",
			iDisplayLength:10,
            rowId:"material.Id",
            order:[["9","desc"]],
            fnInitComplete: function(oSettings, json) {
                $('#approvedtab').html("Approved" + "[" + oTable1.api().rows().count() +"]")
            },
			columns: [
				{ data: null,"render":"", title:"No"},
                { data: "material.Id"},
                { data: null, title: "MR NO",
                  render:function(data){
                    if( data.material.MR_No == "")
                        return "-"
                    else 
                        return "<a style='color:blue;' target='_blank' href='{{url('material/print')}}/"+data.material.Id+"'>"+ data.material.MR_No+"</a>";
                }},
                { data:'tracker.`Unique Id`',title:"Unique Id"},
                { data:"users.Name" , title:"Requestor Name"},
                { data: "tracker.`Site Name`", title: "Site Name"},
                { data: "materialstatus.Status", title: "Status"},
                { data:"material.created_at",title:"Created At"},
                { data: "material.Total", title: "Total",
                  render: function ( data, type, row, meta ) {
                    return "RM "+ data.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ",");
                }},
                {
                    data:null,
                    title:"Action",
                    render:function(data,type,row,meta){
                        var f='viewMaterial('+data.material.Id+',"view")';
                        var r="";
                        var c="";
                        var w="";
                        var q="";
                       
                        // if(data.material.generatePO == "No PO")
                        if(!data.request.total)
                            c="";
                        // else if(data.request.generatePO == "PO" && {{$me->Generate_PO}} == 1
                        // (parseInt(data.material.generatePO.substr(0,(data.material.generatePO.length-data.material.generatePO.indexOf('PO')-1))) <= data.request.total &&
                        // parseInt(data.material.generatePO.substr(0,(data.material.generatePO.length-data.material.generatePO.indexOf('PO')-1))) != 0)
                        // )
                        else if ({{$me->Generate_PO}} && data.request.total && !(parseFloat(data.po.sum) >= parseFloat(data.request.total)))
                        {
                            c= " <a style='width:unset;' title='Generate PO' class='btn btn-default btn-xs' onclick='generatePO("+data.material.Id+")'><i class='fa fa-folder-open' aria-hidden='true'></i></a>";
                        }
                            
                        // if (data.material.generatePO == "0 PO" ||
                        //  data.request.total > parseInt(data.material.generatePO.substr(0,(data.material.generatePO.length-data.material.generatePO.indexOf('PO')-1)))
                        //  && data.material.UserId == {{$me->UserId}}
                        if(data.request.total && data.po.sum && {{$me->Generate_PO}})
                            w=" <a style='width:unset;' title='View PO' class='btn btn-default btn-xs' target='_blank' href='{{url('material/PO')}}/"+data.material.Id+"'><i class='fa fa-eye-slash' aria-hidden='true'></i></a>";                        
                        if((({{$me->Quotation_Approval}} || data.material.UserId == {{$me->UserId}} || {{$me->Upload_Quotation}}) && data.request.total))
                        {
                            q=' <a style="width:unset;" title="Quotation" onclick="uploadModal('+data.material.Id+',`material`)" class="btn btn-xs btn-default"><i class="fa fa-file" aria-hidden="true"></i></a>';
                        }
                        if(data.material.UserId == {{$me->UserId}} ||{{$me->Recall_MR}})
                            r=" <a style='width:unset;' title='Recall' class='btn btn-default btn-xs' onclick='recall("+data.material.Id+")'><i class='fa fa-undo' aria-hidden='true'></i></a>";

                        return "<a  style='width:unset;' title='View Material' class='btn btn-default btn-xs' onclick='"+f+"'><i class='fa fa-eye' aria-hidden='true'></i></a>" +r +c +w + q+
                        "<a target='_blank' style='width:unset;' title='View History' class='btn btn-default btn-xs' href='{{url('material/history')}}/"+data.material.Id+"'><i class='fa fa-history' aria-hidden='true'></i></a>";
                    }
                }
			],
			// select: {
            //     style:    'os',
            //     selector: 'td'
            // },
        });

        oTable1.on( 'order.dt search.dt', function () {
			oTable1.api().column(0, {search:'applied', order:'applied'}).nodes().each( function (cell, i) {
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
         oTable2=$("#revisionTable").DataTable({
            ajax: {
				"url": "{{ asset('/Include/materialrequest.php') }}",
				"data":{
                    mr_no:"%rev%"
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
			dom: "frtip",
			iDisplayLength:10,
            rowId:"material.Id",
            fnInitComplete: function(oSettings, json) {
                $('#revisiontab').html("Revision" + "[" + oTable2.rows().count() +"]")
            },
			columns: [
				{ data: null,"render":"", title:"No"},
                { data: "material.Id"},
                { data: "material.MR_No", title: "MR NO",
                  render:function(data){
                    if( data == "")
                        return "-"
                    else return data;
                }},
                { data:"users.Name" , title:"Requestor Name"},
                { data: "tracker.`Site Name`", title: "Site Name"},
                { data: "materialstatus.Status", title: "Status"},
                { data:"material.created_at",title:"Created At"},
                { data: "material.Total", title: "Total",
                  render: function ( data, type, row, meta ) {
                    return "RM "+ data;
                }},
                {
                    data:null,
                    title:"Action",
                    render:function(data,type,row,meta){
                        var f='viewMaterial('+data.material.Id+',"view")';
                        return "<a class='btn btn-info btn-sm' onclick='"+f+"'>View Material</a>"
                    }
                },{
                    data:null,
                    title:"Print",
                    render:function(data){
                        return "<a target='_blank' href='{{url('material/print')}}/"+data.material.Id+"'>Print</a>";
                    }
                }
			],
			select: {
                style:    'os',
                selector: 'td'
            },
        });

        oTable2.on( 'order.dt search.dt', function () {
			oTable2.column(0, {search:'applied', order:'applied'}).nodes().each( function (cell, i) {
				cell.innerHTML = i+1;
			} );
		} ).draw();

        $("#revisionTable thead input").keyup ( function () {
            if ($('#revisionTable').length > 0)
            {
                var colnum=document.getElementById('revisionTable').rows[0].cells.length;
                if (this.value=="[empty]")
                {
                    oTable2.fnFilter( '^$', this.name,true,false );
                }
                else if (this.value=="[nonempty]")
                {
                    oTable2.fnFilter( '^(?=\\s*\\S).*$', this.name,true,false );
                }
                else if (this.value.startsWith("!")==true && this.value.length>1)
                {
                    oTable2.fnFilter( '^(?'+ this.value +').*', this.name,true,false );
                }
                else if (this.value.startsWith("!")==false)
                {
                    oTable2.fnFilter( this.value, this.name,true,false );
                }
            }
        });


        oTable3=$("#recalledTable").dataTable({
            ajax: {
				"url": "{{ asset('/Include/materialrequest.php') }}",
				"data":{
                    @if(!$me->Recall_MR)
					id:{{$me->UserId}},
                    @endif
                    status:"%Recalled%"
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
			dom: "frtip",
			iDisplayLength:10,
            rowId:"material.Id",
            fnInitComplete: function(oSettings, json) {
                $('#recalledtab').html("Recalled" + "[" + oTable3.api().rows().count() +"]")
            },
			columns: [
				{ data: null,"render":"", title:"No"},
                { data: "material.Id"},
                { data: null, title: "MR NO",
                  render:function(data){
                    if( data.material.MR_No == "")
                        return "-"
                    else return "<a style='color:blue;' target='_blank' href='{{url('material/print')}}/"+data.material.Id+"'>"+data.material.MR_No+"</a>";
                }},
                { data:'tracker.`Unique Id`',title:"Unique Id"},
                { data:"users.Name" , title:"Requestor Name"},
                
                { data: "tracker.`Site Name`", title: "Site Name"},
                { data: "materialstatus.Status", title: "Status"},
                { data: "material.Total", title: "Total",
                  render: function ( data, type, row, meta ) {
                    return "RM "+ data.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ",");
                }},
                { data:"material.created_at",title:"Created At"},
                {
                    data:null,
                    title:"Action",
                    render:function(data,type,row,meta){
                        var f='viewMaterial('+data.material.Id+',"edit")';
                        return "<a style='width:unset;' title='Edit Material' class='btn btn-default btn-xs' onclick='"+f+"'><i class='fa fa-pencil' aria-hidden='true'></i></a> <a style='width:unset;' title='Resubmit' class='btn btn-default btn-xs' onclick='resubmit("+data.material.Id+")'><i class='fa fa-undo' aria-hidden='true'></i></a>";
                        
                    }
                },
			],
			select: {
                style:    'os',
                selector: 'td'
            },
        });

        oTable3.on( 'order.dt search.dt', function () {
			oTable3.api().column(0, {search:'applied', order:'applied'}).nodes().each( function (cell, i) {
				cell.innerHTML = i+1;
			} );
		} ).api().draw();

        $(".recalledTable thead input").keyup ( function () {
            if ($('#recalledTable').length > 0)
            {
                var colnum=document.getElementById('recalledTable').rows[0].cells.length;
                if (this.value=="[empty]")
                {
                    oTable3.fnFilter( '^$', this.name,true,false );
                }
                else if (this.value=="[nonempty]")
                {
                    oTable3.fnFilter( '^(?=\\s*\\S).*$', this.name,true,false );
                }
                else if (this.value.startsWith("!")==true && this.value.length>1)
                {
                    oTable3.fnFilter( '^(?'+ this.value +').*', this.name,true,false );
                }
                else if (this.value.startsWith("!")==false)
                {
                    oTable3.fnFilter( this.value, this.name,true,false );
                }
            }
        });
        $(".viewMrTable thead input").keyup ( function () {
            if ($('#viewMrTable').length > 0)
            {
                var colnum=document.getElementById('viewMrTable').rows[0].cells.length;
                if (this.value=="[empty]")
                {
                    viewMrTable.fnFilter( '^$', this.name,true,false );
                }
                else if (this.value=="[nonempty]")
                {
                    viewMrTable.fnFilter( '^(?=\\s*\\S).*$', this.name,true,false );
                }
                else if (this.value.startsWith("!")==true && this.value.length>1)
                {
                    viewMrTable.fnFilter( '^(?'+ this.value +').*', this.name,true,false );
                }
                else if (this.value.startsWith("!")==false)
                {
                    viewMrTable.fnFilter( this.value, this.name,true,false );
                }
            }
        });

        $('a[data-toggle="tab"]').on( 'shown.bs.tab', function (e) {
            var target = $(e.target).attr("href") 
            $.fn.dataTable.tables( {visible: true, api: true} ).columns.adjust();
        } );
        $('#viewModal,#uploadModal').on('shown.bs.modal', function (e) {
            $.fn.dataTable.tables({ visible: true, api: true }).columns.adjust();
        });
        
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

        <br>
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
                            {{-- <li><a href="#pending" data-toggle="tab" id="pendingtab">Pending Approval</a></li> --}}
                            <li><a href="#approved" data-toggle="tab" id="approvedtab">Approved</a></li>
                            <li><a href="#recalled" data-toggle="tab" id="recalledtab">Recalled</a></li>
                            <li><a href="#saved" data-toggle="tab" id="savedtab">Saved</a></li>
                            <li><a href="#revision" data-toggle="tab" id="revisiontab">Revision</a></li>
                        </ul>
                        <div class="tab-content">
                            <div class="active tab-pane" id="newrequest">
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
                                {{-- <div class="row">
                                    <div class="col-md-12">
                                        <div class="col-md-3">
                                            <label>MATERIAL: <span id='material'>-</span>   </label>
                                        </div>
                                        <div class="col-md-3">
                                            <label>TRANSPORT: <span id='transport'>-</span>   </label>
                                        </div>
                                    </div>
                                </div> --}}
                                <div class="row">
                                    <div class="col-md-8">
                                        @foreach($type as $t)
                                        <div class="col-md-4">
                                            <label>{{strtoupper($t->Option)}}: RM <span class="changeAllPrice" id='{{strtolower($t->Option)}}'>-</span>   </label>
                                        </div>
                                    
                                        @endforeach
                                    </div>
                                </div>
                                {{-- <div class="row">
                                    <div class="col-md-12">
                                        <div class="col-md-3">
                                            <label>MPSB: <span id='mpsb'>-</span>   </label>
                                        </div>
                                        <div class="col-md-3">
                                            <label>E-WALLET: <span id='e-wallet'>-</span>   </label>
                                        </div>
                                    </div>
                                </div> --}}
                                {{-- <div class="row">
                                    <div class="col-md-12">
                                        <div class="col-md-3">
                                            <label>HARDWARE: <span id='hardware'>-</span>   </label>
                                        </div>
                                    </div>
                                </div> --}}
                                <br>
                                <div class="row">
                                    <div class="col-md-12">
                                        <button class='btn btn-primary btn-sm' style="width:unset;" onclick="importMrModal()">Import <i class="fa fa-download" aria-hidden="true"></i></button>
                                        <button class='btn btn-sm btn-default' style='width:unset;' onclick='saveMR()'>Save <i class="fa fa-floppy-o" aria-hidden="true"></i></button>
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
                                                        <button onclick="add()" style="width:unset;" class="btn btn-primary btn-sm" id='add_item_btn'><i class="fa fa-plus" aria-hidden="true"></i></button>
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
                                <input type="hidden" id="saveMr">
            
                                <br>
                                <div class="row">
                                    <div class="col-md-3">
                                        <button style="width:unset;" id="submit_btn" class="btn btn-primary btn-sm" onclick="insertData()">Submit <i class="fa fa-file-text-o" aria-hidden="true"></i></button>
                                    </div>
                                </div>
                            </div>

                            <div class="tab-pane" id="pending">
                                <div class="row">
                                    <div class="col-md-12">
                                        <table id="pendingTable" cellspacing="0" width="100%" padding="30px" style="font-size: 13px;">
                                        </table>
                                    </div>
                                </div>
                            </div><!--Pending tab-->

                            <div class="tab-pane" id="approved">
                                <div class="row">
                                    <div class="col-md-12">
                                        <table id="approvedTable" class="approvedTable" cellspacing="0" width="100%" padding="30px" style="font-size: 13px;">
                                            <thead>
                                                <tr class="search">
                                                    @foreach($approve as $key=>$values)
                                                        <?php $i=0;?>
                                                        @if($key == 0)
                                                            @foreach($values as $field=>$a)
                                                            @if ($i == 0 )
                                                                <th align='center'><input type='hidden' class='search_init' name='{{$i}}' placemark='{{$a}}'></th>
                                                            @else
                                                                <th align='center'><input type='text' class='search_init' name='{{$i}}'  placemark='{{$a}}'></th>
                                                            @endif
                                                                <?php $i++;?>
                                                            @endforeach
                                                            <th align='center'><input type='text' class='search_init' name='{{$i}}'  placemark='{{$a}}'></th>
                                                            <th align='center'><input type='hidden' class='search_init' name='{{$i}}' placemark='{{$a}}'></th>
                                                        @endif
                                                    @endforeach
                                                </tr>
                                                
                                                <tr>
                                                    @foreach($approve as $key=>$value)
                                                        @if($key == 0)
                                                            <td></td>
                                                            @foreach($value as $field=>$value)
                                                                <td>{{$field}}</td>
                                                            @endforeach
                                                            <td></td>
                                                        @endif
                                                    @endforeach
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <?php $i=0;?>
                                                @foreach($approve as $apr)
                                                    <tr id="row_{{$i}}">
                                                        <td></td>
                                                        @foreach($apr as $key=>$value)
                                                            <td>{{$value}}</td>
                                                        @endforeach
                                                        <td></td>
                                                    </tr>
                                                    <?php $i++;?>
                                                @endforeach
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                            <!--Approved tab-->

                            <div class="tab-pane" id="recalled">
                                <div class="row">
                                    <div class="col-md-12">
                                            <table id="recalledTable" class="recalledTable" cellspacing="0" width="100%" padding="30px" style="font-size: 13px;">
                                                <thead>
                                                    <tr class="search">
                                                        @foreach($approve as $key=>$values)
                                                            <?php $i=0;?>
                                                            @if($key == 0)
                                                                @foreach($values as $field=>$a)
                                                                @if ($i == 0 )
                                                                    <th align='center'><input type='hidden' class='search_init' name='{{$i}}' placemark='{{$a}}'></th>
                                                                @else
                                                                    <th align='center'><input type='text' class='search_init' name='{{$i}}'  placemark='{{$a}}'></th>
                                                                @endif
                                                                    <?php $i++;?>
                                                                @endforeach
                                                                <th align='center'><input type='text' class='search_init' name='{{$i}}'  placemark='{{$a}}'></th>
                                                                <th align='center'><input type='hidden' class='search_init' name='{{$i}}' placemark='{{$a}}'></th>
                                                            @endif
                                                        @endforeach
                                                    </tr>
                                                    
                                                    <tr>
                                                        @foreach($approve as $key=>$value)
                                                            @if($key == 0)
                                                                <td></td>
                                                                @foreach($value as $field=>$value)
                                                                    <td>{{$field}}</td>
                                                                @endforeach
                                                                <td></td>
                                                            @endif
                                                        @endforeach
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    <?php $i=0;?>
                                                    @foreach($approve as $apr)
                                                        <tr id="row_{{$i}}">
                                                            <td></td>
                                                            @foreach($apr as $key=>$value)
                                                                <td>{{$value}}</td>
                                                            @endforeach
                                                            <td></td>
                                                        </tr>
                                                        <?php $i++;?>
                                                    @endforeach
                                                </tbody>
                                            </table>
                                    </div>
                                </div>
                            </div>
                            <!--Recalled tab-->

                            <div class="tab-pane" id="revision">
                                <div class="row">
                                    <div class="col-md-12">
                                        <table id="revisionTable" cellspacing="0" width="100%" padding="30px" style="font-size: 13px;">
                                        </table>
                                    </div>
                                </div>
                            </div>

                            <div class="tab-pane" id="saved">
                                <div class="row">
                                    <div class="col-md-12">
                                            <table id="savedTable" cellspacing="0" width="100%" padding="30px" style="font-size: 13px;">
                                                <thead>
                                                    <tr>
                                                        <th>No</th>
                                                        <th>Save by</th>
                                                        <th>Save on</th>
                                                        <th>Action</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    <?php $i=1;?>
                                                    @foreach($save as $s)
                                                        <tr>
                                                            <td>{{$i}}</td>
                                                            <td>{{$s->Name}}</td>
                                                            <td>{{$s->created_at}}</td>
                                                            <td><a target="_blank" class='btn btn-sm btn-default' style="width:unset;" title="Edit" href="{{url('material/saveMr')}}/{{$s->Id}}"><i class="fa fa-pencil-square-o" aria-hidden="true"></i></a></td>
                                                        </tr>
                                                    <?php $i++;?>
                                                    @endforeach
                                                </tbody>
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
                                        <th>Deliver By</th>
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
                                    <div class="row">
                                        <div class="form-group">
                                            <label class="col-sm-2">Reason:</label>
                                            <div class="col-sm-6">
                                                <textarea id="add_new_reason" class="form-control" cols="30" rows="5"></textarea>                       
                                            </div>
                                        </div>
                                    </div>
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
                        {{-- <button class="btn btn-primary btn-sm" onclick="uploadQuotation()">Upload</button> --}}
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
                        <button class="btn btn-primary btn-sm" id="upload_quotation_btn" onclick="uploadQuotation()">Upload</button>
                        <button type="button" class="btn btn-warning btn-sm" data-dismiss="modal">Close</button>
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
                                <span id="approval_mess" style="color:green;"></span>
                                <span id="approval_error" style="color:red;"></span>
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

        <!--Import mr modal-->
        <div class="modal fade" id="importMrModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
            <div class="modal-dialog modal-md" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                        <h4 class="modal-title" id="myModalLabel">MR</h4>
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
                                            <table id="viewMrTable" class="display viewMrTable" cellspacing="0" width="100%" padding="30px" style="font-size: 13px;">
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
                                <span id="edit_new_mess" style="color:green;"></span>
                                <span id="edit_new_error" style="color:red;"></span>
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
        </div><!--Edit new modal-->

        <div class="modal fade" id="removeModal">
            <div class="modal-dialog modal-md">
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span></button>
                        <h4 class="modal-title">Remove</h4>
                    </div>
                    <div class="modal-body">
                        <div class="row">
                            <div class="col-sm-12">
                                <span id="remove_item_mess" style="color:green;"></span>
                                <span id="remove_item_error" style="color:red;"></span>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-sm-12">
                                <label class="col-sm-3">Reason</label>
                                <div class="col-sm-6">
                                    <textarea class="form-control" id="remove_item_reason" cols="30" rows="5"></textarea>
                                </div>
                            </div>
                        </div>
                    </div>
                    <input type="hidden" id="materialrequestId">
                    <div class="modal-footer">
                        <button class="btn btn-primary btn-sm" onclick="removeData()">Remove</button>
                        <button type="button" class="btn btn-warning btn-sm" data-dismiss="modal">Close</button>
                    </div>
                </div>
            </div>
        </div>
</div>
<footer class="main-footer">
	<div class="pull-right hidden-xs">
		<b>Version</b> 1.0.0
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
    $('#uploadFileModal').on('show.bs.modal',function(){
        $('#quotation_type').val('').change();
    });
    ajaxManager.run();
    $('#site').select2();
    $("#item_code").select2();
    $("#item_desc").select2();
    $("#type").select2();
    $("#edit_item_code").select2({width:'100%',dropdownParent: $("#editModal")});
    $("#edit_item_des").select2({width:'100%',dropdownParent: $("#editModal")});
    $("#new_item_code").select2({width:'100%',dropdownParent: $("#addModal")});
    $("#new_item_des").select2({width:'100%',dropdownParent: $("#addModal")});
    $("#new_type").select2({width:'100%',dropdownParent: $("#addModal")})
    $(".site").select2({width:'100%'});
    $("#edit_new_type").select2({width:'100%'});
    $("#edit_new_code").select2({width:'100%'});
    $("#edit_new_desc").select2({width:'100%'}); 
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
   $("#edit_new_code").on('change', function () {
        var ele=$(this).val();
        if(ele != $('#edit_new_desc').val())
        {
            $("#edit_new_price").val($("#edit_new_code option:selected").data("price"));
            $('#edit_new_unit').val($('#edit_new_code option:selected').data('unit'));
            $("#edit_new_desc").val(ele).change();
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

                // <option value="{{$item->Id}},{{$item->vendorId}},{{$item->companyid}}" data-price="{{$item->Item_Price}}"
                //             data-unit="{{$item->Unit}}" >{{$item->Item_Code}}  (RM {{$item->Item_Price}})</option>
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
                        $("#upload_quotation_btn").attr('disabled',true);
                        check=1;
                    }
                    $("#upload_quotation_btn").attr('disabled',false);
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
            $("#edit_item_des").val(ele).change();
            $("#edit_price").val($("#edit_item_code option:selected").data("price"));
            $('#edit_type').val($('#edit_item_code option:selected').data('type'));
        }             
    });
    $("#new_item_code").on('change', function () {
        var ele=$(this).val();
        if(ele != $('#new_item_des').val())
        {
            $("#new_price").val($("#new_item_code option:selected").data("price"));
            // $('#new_type').val($('#new_item_code option:selected').data('type'));
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
            // $('#type').val($('#item_code option:selected').data('type'));
            $("#price").val($("#item_code option:selected").data("price"));
        } 
    });

    $("#new_item_des").on('change', function () {
        var ele=$(this).val();
        if(ele != $('#new_item_code').val())
        {
            // $('#unit').val($('#item_desc option:selected').data('unit'));
            $("#new_price").val($("#new_item_des option:selected").data("price"));    
            $("#new_item_code").val(ele).change();

        } 
    });
    $("#edit_item_des").on('change', function () {
        var ele=$(this).val();
        if(ele != $('#edit_item_code').val())
        {
            // $('#unit').val($('#item_desc option:selected').data('unit'));
            $("#edit_item_code").val(ele).change();
            $("#edit_price").val($("#edit_item_code option:selected").data("price"));
            $('#edit_type').val($('#edit_item_code option:selected').data('type'));
        }
             
    });
    
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
    if(itemArr.length != 0 && $("#site option:selected").val() != "")
    {   
        $.ajaxSetup({
            headers: { 'X-CSRF-Token' : $('meta[name=_token]').attr('content') }
        });
        $.ajax({
            type: "post",
            url: "{{url('material/newRequest')}}",
            data: {
                item:itemArr,
                site:$("#site option:selected").val(),
                savemr:$("#saveMr").val()
                // updateVendor:updateVendor
            },
            success: function (response) {
                if(response == 1)
                {
                    $("#submit_btn").attr('disabled',false);
                    $('.deleteAll').remove();
                    $("#item_code").val("").change();
                    $('#qty').val("");
                    $("#price").empty();
                    $("#total").empty();
                    $("#site").val('').change();
                    itemArr=[];
                    updateVendor=[];
                    $(".changeAllPrice").html("-");
                    var mess="New Material Request submitted";
                    $("#update-alert ul").html(mess);
                    $("#update-alert").modal('show');
                    $("#totalAmount").html("RM 0.00");
                    calculate=0.00;
                    oTable.ajax.reload(function(){
                        $('#pendingtab').html("Pending Approval" + "[" + oTable.rows().count() +"]");
                    });
                    oTable1.api().ajax.url("{{ asset('/Include/materialrequest.php') }}").load(function(){
                        $('#approvedtab').html("Approved" + "[" + oTable1.api().rows().count() +"]");
                    });
                    oTable2.ajax.reload(function(){
                        $('#revisiontab').html("Revision" + "[" + oTable2.rows().count() +"]");
                    });
                    oTable3.api().ajax.reload(function(){
                        $('#recalledtab').html("Recalled" + "[" + oTable3.api().rows().count() +"]");
                    });
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
    $("#viewModal").modal('show');
    await $(".removeAll").remove();
    ajaxManager.addReq({
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
                    // if(response.detail[y].sum){
                    // }
                    // else
                    {
                        let doNo=response.detail[y].DO_No == null ? "-":response.detail[y].DO_No;
                        temp="<td><a style='width:unset;' title='Edit' class='btn btn-danger btn-sm'\
                            onclick='editModal("+response.detail[y].Id+","+ (tablerows) +",`"+doNo+"`)'><i class='fa fa-pencil-square-o' aria-hidden='true'></i></a>\
                            <a class='btn btn-warning btn-sm' title='Remove' style='width:unset;' onclick='removeModal(" + response.detail[y].Id + ")'><i class='fa fa-trash' aria-hidden='true'></i></a>\
                        </td>";
                    }
                }
                let test="material_"+tablerows;
                var rowNode=viewtable1.row.add([
                    (y+1),response.detail[y].Item_Code,response.detail[y].Description ,response.detail[y].Type,parseFloat(response.detail[y].Qty).toFixed(2),
                    "RM " + response.detail[y].Price,"RM " + parseFloat(response.detail[y].Price*response.detail[y].Qty).toFixed(2),
                    response.detail[y].DO_No == null ? "-":"<a target='_blank' href='{{url('deliverytrackingdetails')}}/"+response.detail[y].deliveryId+"'>"+response.detail[y].DO_No+"</a>",response.detail[y].vehicType ? response.detail[y].vehicType+"("+response.detail[y].Vehicle_No+")":"-",temp  
                    ]).node();
                // $(rowNode).attr('id',test);
                $(rowNode).attr('class',"rows_"+response.detail[y].Id);
                viewtable1.draw();
                tablerows += 1;
            }
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
                    // oTable1.ajax.reload(function(){
                    //     $('#approvedtab').html("Approved" + "[" + oTable1.rows().count() +"]");
                    // });
                    oTable1.api().ajax.url("{{ asset('/Include/materialrequest.php') }}").load(function(){
                        $('#approvedtab').html("Approved" + "[" + oTable1.api().rows().count() +"]");
                    });
                    oTable2.ajax.reload(function(){
                        $('#revisiontab').html("Revision" + "[" + oTable2.rows().count() +"]");
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
    viewtable1.row("#material_"+tablerow)
    .every(function (rowIdx, tableLoop, rowLoop) {
        viewtable1.cell(rowIdx,1).data(code[0]);
        viewtable1.cell(rowIdx,2).data(des[0]);
        viewtable1.cell(rowIdx,4).data($("#edit_qty").val());
        viewtable1.cell(rowIdx,3).data($("#edit_type").val());
        viewtable1.cell(rowIdx,5).data("RM " + parseFloat($("#edit_price").val()).toFixed(2));
        viewtable1.cell(rowIdx,6).data("RM " + parseFloat($("#edit_price").val() * $("#edit_qty").val()).toFixed(2));
    })
    .draw();
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
    let reason=$("#add_new_reason").val();
    if($("#new_item_code option:selected") != "" && $("#new_qty").val() != "" && reason != ""){
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
                company:conv_item[2],
                reason:reason
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
                company:0,
                reason:reason
            };
        }
        checkArray(newArr,temp);
        var code=$("#new_item_code option:selected").text().split("(RM");
        var des=$("#new_item_des option:selected").text().split("(RM");
        viewtable1.row.add([
            countrow,code[0],des[0],$("#new_type").val(),$("#new_qty").val(),"RM "+ $("#new_price").val(),"RM "+ parseFloat($("#new_price").val() * $("#new_qty").val()).toFixed(2),
        "-","-","<a style='width:unset;' title='Remove' class='btn btn-warning btn-sm' onclick='remove("+countrow+")'><i class='fa fa-trash' aria-hidden='true'></i></a>"
        ]).node().id="row_"+countrow;
        viewtable1.draw(false);
        $("#addModal").modal('hide');
        $("#new_qty").val("");
        $("#new_type").val("").change();
        $("#add_new_reason").val('');
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
function removeData()
{
    let reason=$("#remove_item_reason").val();
    let id=$("#materialrequestId").val();
    var temp={
        id:id,
        reason:reason
    };
    
    if(reason != ""){
        checkArray(removeArr,temp);
        viewtable1.row(".rows_"+id).remove().draw(false);
        $("#removeModal").modal('hide');
        $("#remove_item_reason").val('');
        $("#save_mess").html("*Please click save button to save details.");
    }else{
        $("#remove_item_error").html("Please enter reason");
        setTimeout(() => {
            $("#remove_item_error").html("");
        }, 2000);
    }
    
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
                    // oTable.ajax.reload(function(){
                    //     $('#pendingtab').html("Pending Approval" + "[" + oTable.rows().count() +"]");
                    // });
                    // oTable1.ajax.reload(function(){
                    //     $('#approvedtab').html("Approved" + "[" + oTable1.rows().count() +"]");
                    // });
                    // oTable2.ajax.reload(function(){
                    //     $('#rejectedtab').html("Rejected" + "[" + oTable2.rows().count() +"]");
                    // });
                    // oTable3.ajax.reload(function(){
                    //     $('#recalledtab').html("Recalled" + "[" + oTable3.rows().count() +"]");
                    // });
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
                // oTable.ajax.reload(function(){
                //     $('#pendingtab').html("Pending Approval" + "[" + oTable.rows().count() +"]");
                // });
                // oTable1.ajax.reload(function(){
                //     $('#approvedtab').html("Approved" + "[" + oTable1.rows().count() +"]");
                // });
                // oTable2.ajax.reload(function(){
                //     $('#rejectedtab').html("Rejected" + "[" + oTable2.rows().count() +"]");
                // });
                // oTable3.ajax.reload(function(){
                //     $('#recalledtab').html("Recalled" + "[" + oTable3.rows().count() +"]");
                // });
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
        //window.open('{{url('material/POConfirmation')}}/' +id);
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
                // if(response == 1)
                // {
                //     oTable.ajax.reload(function(){
                //         $('#pendingtab').html("Pending Approval" + "[" + oTable.rows().count() +"]");
                //     });
                //     oTable1.ajax.reload(function(){
                //         $('#approvedtab').html("Approved" + "[" + oTable1.rows().count() +"]");
                //     });
                //     oTable2.ajax.reload(function(){
                //         $('#rejectedtab').html("Rejected" + "[" + oTable2.rows().count() +"]");
                //     });
                //     oTable3.ajax.reload(function(){
                //         $('#recalledtab').html("Recalled" + "[" + oTable3.rows().count() +"]");
                //     });
                // }
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
    
}
function uploadModal(id,type)
{
    // $("#uploadedFile").empty();
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
            // if(response.length == 0){
            //     $("#uploadedFile").html('No File.');
            // }else{
            //     if(response[0].Web_Path.includes('.pdf')){
            //         $("#uploadedFile").html("<a target='_blank' href='"+response[0].Web_Path+"'>"+response[0].File_Name+"</a>");
            //     }else
            //         $("#uploadedFile").html("<img width='200px' height='200px' src='"+response[0].Web_Path+"'/>");
            // }
            if(response.mr.UserId == {{$me->UserId}} || {{$me->Upload_Quotation}})
                $("#uploadFileBtn").html("<button class='col-sm-3 btn btn-sm btn-primary' onclick='uploadFileModal()'>Upload</button>");
            else    
                $("#uploadFileBtn").html('');
            
            for(var y=0,i=response.data.length;y<i;y++){

            @if($me->Quotation_Approval)
                 filetable.row.add([
                    "",response.data[y].Type,response.data[y].Amount,"<a target='_blank' href='"+response.data[y].Web_Path+"'>"+response.data[y].File_Name+"</a>",response.data[y].Name,
                    response.data[y].Status != "" ? response.data[y].Status:"-",response.data[y].Requestor_Reason != "" ? response.data[y].Requestor_Reason:"-",response.data[y].Reason != "" ? response.data[y].Reason:"-",
                    (response.data[y].created_by == {{$me->UserId}} ? "<a onclick='removeFile("+response.data[y].Id+",this)' style='width:unset;' class='btn btn-danger btn-sm'>Remove</a>":"") +
                   ( response.data[y].Status == "Pending Approval" ? "<a style='width:unset;' class='btn btn-sm btn-primary' onclick='approvalModal("+response.data[y].Id+",`approve`)'>Approve</a> <a style='width:unset;' class='btn btn-sm btn-warning' onclick='approvalModal("+response.data[y].Id+",`reject`)'>Reject</a>":"")
                ]).draw(false);
            @else
            console.log(response.data[y].created_by == {{$me->UserId}});
            filetable.row.add([
                    "",
                    response.data[y].Type,
                    response.data[y].Amount,
                    "<a target='_blank' href='"+response.data[y].Web_Path+"'>"+response.data[y].File_Name+"</a>",
                    response.data[y].Name,
                    response.data[y].Status != "" ? response.data[y].Status:"-",
                    response.data[y].Requestor_Reason != "" ? response.data[y].Requestor_Reason:"-",
                    response.data[y].Reason != "" ? response.data[y].Reason:"-",
                    (response.data[y].created_by == {{$me->UserId}} ? "<a onclick='removeFile("+response.data[y].Id+",this)' style='width:unset;' class='btn btn-danger btn-sm'>Remove</a>":"")                ]).draw(false);
            @endif
            }                
        }
    });
}
async function uploadQuotation()
{
    await checkQuotationAmount();
    if(check == 1){
        if($("#reqReason").val() == ""){
            $("#upload_error").html("Please enter reason.");
            setTimeout(() => {
                $("#upload_error").html("");
            }, 3000);
            return;
        }
        check=0;
    }    
    if(check == -1){
        $("#upload_error").html("Unable to find the type for this MR.");
        setTimeout(() => {
            $("#upload_error").html("");
        }, 3000);
        return ;
    }
    if(document.getElementById("quotation").files.length > 0 && check!= 1 && check != -1)
    {
        $("#upload_quotation_btn").attr('disabled',true);
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
                $("#upload_quotation_btn").attr('disabled',false);
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
                site:$("#site option:selected").val(),
                savemr:$("#saveMr").val()
            },
            success: function (response) {
                if(response > 0){
                    $("#saveMr").val(response);
                    $("#update-alert ul").html("Saved");
                    $("#update-alert").modal('show');
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
    let filter=itemArr.filter(a=>a.row == tblrow);
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
    temp=parseFloat($("#"+type.toLowerCase()).text());
    temp=temp-filter[0].total+qty*price;
    $("#"+type.toLowerCase()).html(temp.toFixed(2));
    calculate=calculate-filter[0].total+qty*price;
    $("#totalAmount").html(calculate.toFixed(2));
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
            let Dataarr=[];
            for(var y=0,i=response.length;y<i;y++){
                Dataarr.push([
                    "",response[y]['MR_No'],response[y]['Unique Id'],
                    "<a style='width:unset;' class='btn btn-default btn-sm' onclick='importMrItemModal("+response[y].Id+")'><i class='fa fa-download' aria-hidden='true'></i></a>"
                ]);
            }
            viewMrTable.api().rows.add(Dataarr).draw(false);
            // viewMrTable.api().draw(false);
        }
    });
}
function importMrItemModal(id){
    $("#mrItemModal").modal('show');
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
            }
        }
    });
}
function check(index){
    var allPages = mrItemTable.fnGetNodes();
    if ($("#itemCheckAll").is(':checked')){
        // $(".itemCheck").prop("checked", true);
        $('.itemCheck', allPages).prop('checked', true);
        $(".itemCheck").trigger("change");
        mrItemTable.api().rows().select();
    }else{
        // $(".itemCheck").prop("checked", false);
        $('.itemCheck', allPages).prop('checked', false);
        $(".itemCheck").trigger("change");
        mrItemTable.api().rows().deselect();
    }
}
function importMR(){
    var allPages = mrItemTable.fnGetNodes();      
    // console.log($('.itemCheck:checkbox:checked', allPages));  
    $(allPages).each(function(i,row){
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
function removeFile(id,ele){
    let element=ele.closest('tr');
    console.log(element);
    if(confirm("Are you sure you want to delete this file?")){
        $.ajaxSetup({
            headers: { 'X-CSRF-Token' : $('meta[name=_token]').attr('content') }
        });
        ajaxManager.addReq({
            type: 'post',
            url: '{{url("material/removeFile")}}',
            data:{id:id},
            success: function(response){
                if(response  == 1){
                    filetable.row(element).remove().draw();
                    var mess="Deleted.";
                    $("#uploadModal_error").html(mess);
                    setTimeout(() => {
                        $("#uploadModal_error").html('');
                    }, 3000);;
                }
            }
        });
    }
}
function removeModal(id){
    $("#removeModal").modal('show');
    $('#materialrequestId').val(id);
}
</script>

@endsection