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
    var editor; // use a global for the submit and return data rendering in the examples
	var asInitVals = new Array();
	var oTable;
	var userid;
	var inventory;

	$(document).ready(function() {
		editor = new $.fn.dataTable.Editor( {
			ajax: {
				"url": "{{ asset('/Include/serviceticket.php') }}"
			},
			idSrc: "Id",
			table: "#servicetickettable",
			fields: [
			{
				label: "Service Type:",
				name: "service_type",
				type:  'select2',
				options: [
					{ label :"", value: "" },
					@foreach($type as $c)
					{ label : "{{ $c->Option }}", value:  "{{ $c->Option }}"},
					@endforeach
				],
			},{
				label:"Technician Name",
				name:"technician_name",
				type:'select2',
				options:[
					{label:"",value:""},
				]
			},
			{
				label: "Remark:",
				name: "service_summary",
				type:"textarea"
            }]
        });

		//Activate an inline edit on click of a table cell
		// $('#servicetickettable').on( 'click', 'tbody td', function (e) {
		// 	editor.inline( this, {
		// 		onBlur: 'submit'
		// 	} );
		// } );

		oTable=$('#servicetickettable').dataTable( {
			columnDefs: [
				{ "visible": false, "targets": [2] },
				// {"className": "dt-left", "targets": [4]},
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
			columns: [
				{ data: null,"render":"", title:"No"},
				{ data:null, title:"Action", sortable: false,
					render: function ( data, type, row, meta ) {
							return "<a class='btn btn-default btn-sm' href='{{ url('/serviceticket/details')}}/"+data.Id+"' targets='_blank'>View</a>";
						}
				},
				{ data: "Id"},
				{ data: "service_id", title:'Service Ticket ID'},
				{ data: "service_type", title:'Type'},
				{ data: "service_summary", title:'Service Summary'},
				{ data: "client", title:'Client Info'},			
				{ data:"branch",title:"Branch Info"},
				{ title:"Client Category"},
				{ data: "speedfreak_no", title:'SPEEDFREAK No'},
				{ data: "service_date", title:'Service Date'},
				{ data: "technician_name", title:'Technician'},
				{ data: "status", title:'Status'}
			],
			autoFill: {
				editor:  editor,
			},
			select: {
                style:    'os',
                selector: 'td'
            },
			// select: true,
			buttons: [
			{ extend: "create", editor: editor },
			{ extend: "edit", editor: editor },
			{ extend: "remove", editor: editor },
			{
				extend: 'collection',
				text: 'Export',
				buttons: [
				'excel',
				'csv',
				'pdf'
				]
			},
			],
		});

		$('#servicetickettable').on( 'click', 'tr', function () {
			// Get the rows id value
			//  var row=$(this).closest("tr");
			//  var oTable = row.closest('table').dataTable();
			userid = oTable.api().row( this ).data().serviceticket.Id;
		});

		oTable.api().on( 'order.dt search.dt', function () {
			oTable.api().column(0, {search:'applied', order:'applied'}).nodes().each( function (cell, i) {
				cell.innerHTML = i+1;
			} );
		} ).draw();

		$("thead input").keyup ( function () {

			/* Filter on the column (the index) of this element */
			if ($('#servicetickettable').length > 0)
			{
				var colnum=document.getElementById('servicetickettable').rows[0].cells.length;
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
		<h1>Service Ticket<small>SPEEEDFREAK</small></h1>
		<ol class="breadcrumb">
			<li><a href="{{ url('/home') }}"><i class="fa fa-dashboard"></i>Home</a></li>
			<li><a href="#">SPEED FREAK</a></li>
            <li class="active">Service Ticket</li>
		</ol>
	</section>

	<br>

	<section class="content">
		<div class="box">
			<div class="box-body">
				<div class="row">
					<div class="col-md-12">
						<div class="col-md-4">
							<label>Date Range</label>
							<div class="input-group">
		                        <div class="input-group-addon">
		                        	<i class="fa fa-clock-o"></i>
		                        </div>
		                        <input type="text" class="form-control" id="range" name="range">
		                    </div>
		                </div>
		                <div class="col-md-2 pull-center">
		                	<br>
		                	<button class="btn btn-success" type="button">Search</button><br><br>
		                </div>
		            </div>
	            </div>
	            <div class="row">
					 <div class="col-md-12">
		            	<table id="servicetickettable" class="servicetickettable" cellspacing="0" width="100%" padding="30px" style="font-size: 13px; white-space: pre-line;">
			              	<thead>
			              		<tr class="search">
		              			@foreach($service as $key=>$value)
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
	                                    <th align='center'><input type='text' class='search_init' name='{{$i}}'  placemark='{{$a}}'></th>
	                                    <th align='center'><input type='text' class='search_init' name='{{$i}}'  placemark='{{$a}}'></th>
									@endif
								@endforeach
			                    </tr>
			                    <tr>
			                    	@foreach($service as $key=>$value)
				                    	@if ($key==0)
					                    	<td></td>
					                    	<td></td>
				                            @foreach($value as $field=>$value)
					                            <td>{{ $field }}</td>
			                                @endforeach
					                    	<td></td>
		                                @endif
	                                @endforeach
		                        </tr>
			                </thead>
			                <tbody>

			                	<?php $i = 0; ?>
			                	@foreach($service as $inventory)

			                	<tr id="row_{{ $i }}">
			                		<td></td>      
			                		<td></td>
		                            @foreach($inventory as $key=>$value)
		                              <td>{{ $value }}</td>
		                            @endforeach
			                		<td></td>
		                        </tr>
		                        <?php $i++; ?>
			                    @endforeach

		                    </tbody>
			            </table>
		            </div>
				</div>
			</div>
		</div>
	</section>
</div>

<script>
  $(function () {
    $( "#upload_form" ).submit(function( event ) {
      event.preventDefault();
      return false;
    });
    /* initialize the calendar
     -----------------------------------------------------------------*/
    //Date for the calendar events (dummy data)
    var date = new Date();
    var d = date.getDate(),
        m = date.getMonth(),
        y = date.getFullYear();
    $('#calendar').fullCalendar({
      header: {
        left: 'prev,next today',
        center: 'title',
        right: 'month'
      },
      buttonText: {
        today: 'Today',
        month: 'Month'
      },
      //Random default events
      events: [


      ],
      editable: false,
      droppable: false, // this allows things to be dropped onto the calendar !!!
    });
  });
	$(function () {

      $('#range').daterangepicker({locale: {
        format: 'DD-MMM-YYYY'
      },
      startDate: '13-JUN-2019',
      endDate: '13-JUL-2019'});

    });
</script>
@endsection