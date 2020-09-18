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
    var oTable;
    var oTable1;
    $(function () {
        $('#materialTable').dataTable({
            dom:""
        });
        $("#viewTable").dataTable({
            dom:""
        });
        oTable=$("#pendingTable").dataTable({
            ajax: {
				"url": "{{ asset('/Include/materialapproval.php') }}",
				"data":{
					id:{{$me->UserId}},
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
			columns: [
				{ data: null,"render":"", title:"No"},
                { data: "material.Id"},
                { data: "material.MR_No", title:"MR NO",
                  render:function(data){
                    if(data != "")
                        return data;
                    else return "-";
                }},
                { data: "tracker.`Site Name`", title: "Site Name"},
                { data: "materialstatus.Status", title: "Status"},
                { data: "material.Total", title: "Total",
                  render: function ( data, type, row, meta ) {
                    return "RM "+ data;
                }},
                {
                    data:null,
                    title:"Action",
                    render:function(data,type,row,meta){
                        return "<a class='btn btn-info btn-sm' href='{{url('material/materialDetails')}}/"+data.material.Id
                        +"'>View</a>"
                    }
                },{
                    data:null,
                    title:"Print",
                    render:function(data){
                        return "<a href='{{url('material/print')}}/"+data.material.Id+"'>Print</a>";
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
        $("thead input").keyup ( function () {

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
				"url": "{{ asset('/Include/materialapproval.php') }}",
				"data":{
					//id:{{$me->UserId}},
                    status:"%Approved%"
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
			columns: [
				{ data: null,"render":"", title:"No"},
                { data: "material.Id"},
                { data: "material.MR_No", title:"MR NO",
                  render:function(data){
                    if(data != "")
                        return data;
                    else return "-";
                }},{
                    data:"requestor.Name", title:"Requestor Name"
                },
                { data: "tracker.`Site Name`", title: "Site Name"},
                { data: "materialstatus.Status", title: "Status"},
                { data: "material.Total", title: "Total",
                  render: function ( data, type, row, meta ) {
                    return "RM "+ data;
                }},
                {
                    data:null,
                    title:"Action",
                    render:function(data,type,row,meta){
                        return "<a class='btn btn-info btn-sm' href='{{url('material/materialDetails')}}/"+data.material.Id
                        +"'>View</a>"
                    }
                },{
                    data:null,
                    title:"Print",
                    render:function(data){
                        return "<a href='{{url('material/print')}}/"+data.material.Id+"'>Print</a>";
                    }
                }
			],
			select: {
                style:    'os',
                selector: 'td'
            },
        });

        oTable1.api().on( 'order.dt search.dt', function () {
			oTable1.api().column(0, {search:'applied', order:'applied'}).nodes().each( function (cell, i) {
				cell.innerHTML = i+1;
			} );
		} ).draw();
        $("#approvedTable thead input").keyup ( function () {

            /* Filter on the column (the index) of this element */
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
		<h1>Material Request<small>Admin</small></h1>

		<ol class="breadcrumb">
			<li><a href="{{ url('/home') }}"><i class="fa fa-dashboard"></i> Home</a></li>
			<li><a href="#">Admin</a></li>
			<li class="active">Material Approval</li>
		</ol>
	</section>

	<section class="content">
        <br><br>
        <div class="row">
	        <div class="box">
	          <div class="box-body">
                    <div class="nav-tabs-custom">
                        <ul class="nav nav-tabs">
                            <li class="active"><a href="#pending" data-toggle="tab" id="pendingtab">Pending Approval</a></li>
                            <li><a href="#approved" data-toggle="tab" id="approvedtab">Approved</a></li>
                        </ul>
                        <div class="tab-content">
                            <div class="active tab-pane" id="pending">
                                <div class="row">
                                    <div class="col-md-12">
                                        <table id="pendingTable" cellspacing="0" width="100%" padding="30px" style="font-size: 13px;">
                                        </table>
                                    </div>
                                </div>
                            </div>

                            <div class="tab-pane" id="approved">
                                <div class="row">
                                    <div class="col-md-12">
                                        <table id="approvedTable" cellspacing="0" width="100%" padding="30px" style="font-size: 13px;">
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>
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


@endsection