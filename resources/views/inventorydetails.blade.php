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
		editor = new $.fn.dataTable.Editor( {
			ajax: {
				"url": "{{ asset('/Include/vendor.php') }}"
			},
			idSrc: "inventoryvendor.Id",
			table: "#vendortable",
			fields: [
			{
				label: "Vendor:",
				name: "inventoryvendor.CompanyId",
				type:  'select2',
				options: [
					{ label :"", value: "" },
					@foreach($company as $c)
					{ label : "{{ $c->Company_Name }}", value:  "{{ $c->Id }}"},
					@endforeach
				],
			},{
                name:"inventoryvendor.InventoryId",
                type:"hidden",
                def: "{{ $item->Id }}"
            },{
                label:"Price:",
                name:"inventoryvendor.Item_Price",
                attr:{
                    type:"number"
                }
            }
          ]
        });

		//Activate an inline edit on click of a table cell
		$('#vendortable').on( 'click', 'tbody td', function (e) {
			editor.inline( this, {
				onBlur: 'submit'
			} );
		} );

		oTable=$('#vendortable').dataTable( {
            ajax: {
				"url": "{{ asset('/Include/vendor.php') }}",
				"data":{
					id:{{$item->Id}}
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
			dom: "Bfrtip",
			iDisplayLength:10,
            rowId:"inventoryvendor.Id",
			columns: [
				{ data: null,"render":"", title:"No"},
				{ data: "inventoryvendor.Id"},
				{ data: "companies.Company_Name", title:'Vendor',editField:"inventoryvendor.CompanyId"},
                { data:"inventoryvendor.Item_Price",title:"Price"},
				{ data:"inventoryvendor.created_at",title:"Created At"}
			],
			autoFill: {
				editor:  editor,
			},
			select: {
                style:    'os',
                selector: 'td'
            },
			// select: true,
			buttons: [{ 
				extend: "create", editor: editor 
			},
			{ extend: "remove", editor: editor },
			],
		});

		$('#vendortable').on( 'click', 'tr', function () {
			userid = oTable.api().row( this ).data().inventoryvendor.Id;
		});

		oTable.api().on( 'order.dt search.dt', function () {
			oTable.api().column(0, {search:'applied', order:'applied'}).nodes().each( function (cell, i) {
				cell.innerHTML = i+1;
			} );
		} ).draw();

		$("thead input").keyup ( function () {

			/* Filter on the column (the index) of this element */
			if ($('#vendortable').length > 0)
			{
				var colnum=document.getElementById('vendortable').rows[0].cells.length;
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
		<h1>Inventory Management<small>Admin</small></h1>

		<ol class="breadcrumb">
			<li><a href="{{ url('/home') }}"><i class="fa fa-dashboard"></i> Home</a></li>
			<li><a href="#">Admin</a></li>
            <li >Inventory Management</li>
            <li class="active">Inventory Details</li>
		</ol>
	</section>
	<br>
	<section class="content">
		<div class="row">
	        <div class="box">
                <div class="box-header">
                    <h3>Inventory Details</h3>
                </div>
	          <div class="box-body">
                <div class="row">
                    <div class="col-lg-4">
                        <table class="table table-bordered">
                            <tbody>
                                <tr>
                                    <th>Item Code: </th>
                                    <td>{{$item->Item_Code}}</td>
                                </tr>
                                <tr>
                                    <th>Item Description:</th>
                                    <td>{{$item->Description}}</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>

                <br>
                <div class="row">
                    <div class="col-lg-12">
                        <table id="vendortable" class="vendortable" cellspacing="0" width="100%" padding="30px" style="font-size: 13px; white-space: pre-line;">
                            
                        </table>
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