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

        div.DTE_Field_Type_textarea textarea {
		    width: 150%;
		}
		div.DTE_Field_Type_text textarea {
		    width: 150%;
		}
		div.DTE_Field_Type_select textarea {
		    width: 150%;
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
		var companyid;
		var reader=new FileReader();
		$(document).ready(function() {

			editor = new $.fn.dataTable.Editor( {
				ajax: "{{ asset('/Include/companies.php') }}",
				table: "#companiestable",
				idSrc: "companies.Id",
				fields: [
				{
					label: "Status:",
					name: "companies.Status",
					type: "select2",
					options: [
          						@foreach($options as $option)
          							@if ($option->Field=="Status")
          							       { label :"{{$option->Option}}", value: "{{$option->Option}}" },
	                       @endif
                        @endforeach
                    ],
                },
                {
                	label: "Company_Account:",
                	name: "companies.Company_Account",
                	type: "select",
                	options: [
                      { label :"", value: "" },
                      @foreach($companies as $com)
                               { label :"{{$com->Option}}", value: "{{$com->Option}}" },
                        @endforeach
                	],
                },
                {
                	label: "Company Name:",
                	name: "companies.Company_Name"
                },{
                	label: "Register Number:",
                	name: "companies.Register_Num"
                },{
                	label: "Company_Code:",
                	name: "companies.Company_Code"
                },{
                	label: "CreditorCode:",
                	name: "companies.CreditorCode"
                },{
                	label: "Initial:",
                	name: "companies.Initial"
                },{
                	label: "Type:",
                	name: "companies.type",
                	type: "select",
                	options: [
	                	{ label :"GST", value: "GST" },
	                	{ label :"GSC", value: "GSC" },
	                	{ label :"FAB", value: "FAB" },
	                	{ label :"CME", value: "CME" },
	                	{ label :"Other", value: "Other" }
                	],
                },{
                	label: "Person_In_Charge:",
                	name: "companies.Person_In_Charge"
                },
                {
                	label: "Attention:",
                	name: "companies.attention"
                },
                {
                	label: "Bank Account:",
                	name: "companies.bank_acct"
                },{
                	label: "Bank:",
                	name: "companies.bank"
                },{
                	label: "Contact_No:",
                	name: "companies.Contact_No"
                },{
                	label: "Office_No:",
                	name: "companies.Office_No"
                },{
                	label: "Fax_No:",
                	name: "companies.Fax_No"
                },{
                	label: "Email:",
                	name: "companies.Email"
                },{
                	label: "Address:",
                	name: "companies.Address",
                	type: "textarea"
                },{
                	label: "Subcon:",
                	name: "companies.subcon",
                	type: "select",
                	options: [
	                	{ label :"No", value: "No" },
	                	{ label :"Yes", value: "Yes" },
                	],
                },{
                	label: "Client:",
                	name: "companies.Client",
                	type: "select",
                	options: [
	                	{ label :"No", value: "No" },
	                	{ label :"Yes", value: "Yes" },
                	],
                },{
                	label: "Subsidiary:",
                	name: "companies.Subsidiary",
                	type: "select",
                	options: [
	                	{ label :"No", value: "No" },
	                	{ label :"Yes", value: "Yes" },
                	],
                },{
                	label: "Supplier:",
                	name: "companies.Supplier",
                	type: "select",
                	options: [
	                	{ label :"No", value: "No" },
	                	{ label :"Yes", value: "Yes" },
                	],
                },{
                	label: "Remarks:",
                	name: "companies.Remarks",
                	type:"textarea"
                },{
					label: "Logo:",
					name: "files.Web_Path",
					type: "upload",
					ajaxData: function ( d ) {
                    	d.append( 'Id', companyid); // edit - use `d`
					},
					display: function ( file_id) {
						//return '<img src="/private/upload/Company logo/'+file_id+'"/>';
						//return '<img src="'+ oTable.api().row( editor.modifier() ).data().files.Web_Path +'">';
				    },
					clearText: "Clear",
					noImageText: 'No image'
				},
                ]
            } );

			 editor.on('preUpload',function(e,o,action){
				console.log(o);
			 });
            //Activate an inline edit on click of a table cell
			// $('#companiestable').on( 'click', 'tbody td', function (e) {
			//     editor.inline( this, {
			//     	onBlur: 'submit'
			//     } );
			// } );

			oTable=$('#companiestable').dataTable( {
				columnDefs: [{ "visible": false, "targets": [1,2,-2,-3] },{"className": "dt-center", "targets": "_all"}],
				responsive: false,
				colReorder: false,
				sScrollX: "100%",
				bScrollCollapse: true,
				bAutoWidth: true,
				sScrollY: "100%",
				dom: "Bfrtip",
				iDisplayLength:25,
				columns: [
					{ data: null,"render":"", title:"No"},
					{
						sortable: false,
						searchable: false,
						"render": function ( data, type, full, meta ) {
							return "";
						},title: "Action"
					},
					{ data: "companies.Id",title:"Id"},
					{ data: "companies.Status",title:"Status"},
          			{ data: "companies.Company_Account",title:"Company_Account"},
					{ data: "companies.Company_Name",title:"Company_Name"},
					{ data: "companies.Register_Num",title:"Register_Number"},
					{ data: "companies.Company_Code",title:"Company_Code"},
          			{ data: "companies.CreditorCode",title:"CreditorCode"},
					{ data: "companies.Initial",title:"Initial"},
					{ data: "companies.type",title:"Type"},
					{ data: "companies.Person_In_Charge",title:"Person_In_Charge"},
					{ data: "companies.attention",title:"Attention"},
					{ data: "companies.bank",title:"Bank"},
					{ data: "companies.bank_acct",title:"Bank Account"},
					{ data: "companies.Contact_No",title:"Contact_No"},
					{ data: "companies.Office_No",title:"Office_No"},
					{ data: "companies.Fax_No",title:"Fax_No"},
					{ data: "companies.Email",title:"Email"},
					{ data: "companies.Address",title:"Address"},
					{ data: "companies.subcon",title:"Subcon"},
					{ data: "companies.Client",title:"Client"},
					{ data: "companies.Subsidiary",title:"Subsidiary"},
					{ data: "companies.Supplier",title:"Supplier"},
					{ data: "companies.Remarks",title:"Remarks"},
					{ data: "companies.created_at",title:"created_at"},
					{ data: "companies.updated_at",title:"updated_at"},
					{ data: "files.Web_Path",
						render: function (  data, type, full, meta ) {

							if (data)
							{
								return '<img class="profile-user-dt-img" src="{{ URL::to('/')}}'+ data +'" alt="User profile picture">';
							}
						},
						defaultContent:"No image",
						title: "Logo"
					},

				],
				autoFill: {
					editor:  editor,
				},
				select: true,
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
				}
				],
			});

			$('#companiestable').on( 'click', 'tr', function () {
				companyid = oTable.api().row( this ).data().companies.Id;
			});

			oTable.api().on( 'order.dt search.dt', function () {
				oTable.api().column(0, {search:'applied', order:'applied'}).nodes().each( function (cell, i) {
					cell.innerHTML = i+1;
				} );
			} ).draw();

			$("thead input").keyup ( function () {
				/* Filter on the column (the index) of this element */
				if ($('#companiestable').length > 0)
				{
					var colnum=document.getElementById('companiestable').rows[0].cells.length;
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
    	<h1>Company Management
    		<small>Logistic</small>
    	</h1>

    	<ol class="breadcrumb">
    		<li><a href="{{ url('/home') }}"><i class="fa fa-dashboard"></i> Home</a></li>
    		<li><a href="#">Logistic</a></li>
    		<li class="active">Company Management</li>
    	</ol>
    </section>

    <br>

    <section class="content">


    	<div class="row">
    		<div class="col-md-12">
    			<div class="box box-primary">
    				<div class="box-body">

              <div class="row">

                <div class="col-md-4">
                  <div class="form-group">
                   <select class="form-control select2" id="Company" name="Company" >
                     <option value="">All</option>
                     @foreach($companies as $com)
                        <option <?php if($company == $com->Option) echo ' selected="selected" '; ?>>{{$com->Option}}</option>
                     @endforeach
                   </select>
                 </div>
               </div>


                <div class="col-md-2">
                    <div class="input-group">
                      <button type="button" class="btn btn-success btn" data-toggle="modal" onclick="refresh();">Refresh</button>
                    </div>
                </div>



              </div>

				        <table id="companiestable" class="display" cellspacing="0" width="100%" padding="30px" style="font-size: 13px;">
				        	@if($customers)
				        	<thead>
				        		<tr class="search">
				        			{{-- <td align='center'><input type='hidden' class='search_init' /></td>
				        			<td align='center'><input type='hidden' class='search_init' /></td> --}}
				        			@foreach($customers as $key=>$values)
					        			<?php $i = 0; ?>

					        			@if ($key==0)

						        			@foreach($values as $field=>$a)

							        			@if ($i==0|| $i==1|| $i==2 ||$i==19|| $i==20 )
							        			<th align='center'><input type='hidden' class='search_init' name='{{$i}}' placemark='{{$a}}'></th>
							        			@else
							        			<th align='center'><input type='text' class='search_init' name='{{$i}}'  placemark='{{$a}}'></th>
							        			@endif

							        			<?php $i ++; ?>
						        			@endforeach

					        			@endif
					        			<?php $i ++; ?>
				        			@endforeach
				        		</tr>

				        		<tr>
				        			@foreach($customers as $key=>$value)
					        			@if ($key==0)
					        			<td></td>
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
				        		<?php $i = 0;?>
				        		@foreach($customers as $customer)
				        		<tr id="row_{{ $i }}">
				        			<td></td>
				        			<td></td>

				        			@foreach($customer as $key=>$value)
				        			<td>
										{{ $value }}
									</td>
									@endforeach

				        		</tr>
				        		<?php $i++; ?>
								@endforeach
				        	</tbody>
				        	@endif
				        </table>
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

   company=$('#Company').val();

   if(company)
   {
    window.location.href ="{{ url("/customers") }}/All/"+company;
   }
   else {
     window.location.href ="{{ url("/customers") }}/All";
   }

}

</script>

 @endsection
