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
    $(window).trigger('resize');
    var poTable;
    var cancelTable;
    var editor;
    $(function () {
        editor = new $.fn.dataTable.Editor({
                    ajax: "{{ asset('/Include/materialpo.php') }}",
                    table: "#poTable",
                    idSrc: "materialpo.Id",
                    fields: [
                                {
                                    name: "materialpo.Id",
                                    type: "hidden",
                                },
                                // {
                                //     label: "Company Name:",
                                //     name: "materialpo.CompanyId",
                                //     type:  'select2',
                                //     options: [
                                //         @foreach($companies as $company)
                                //             {label:"{{$company->Company_Name}}",value:"{{$company->Id}}"},
                                //         @endforeach
                                //     ],
                                // },
                                {
                                    label:"Terms",
                                    name:"materialpo.Terms",
                                    type:"select2",
                                    options:[
                                        @foreach($terms as $term)
                                            {label:"{{$term->Option}}",value:"{{$term->Option}}"},
                                        @endforeach
                                    ]
                                },{
                                    label:"Delivery Date:",
                                    name:"materialpo.Delivery_Date",
                                    type:   'datetime',
                                    def:    function () { return new Date(); },
                                    format: 'DD-MM-YYYY',
                                    keyInput: false
                                }
                             
                            ]
                        });
        $('#poTable').on( 'click', 'tbody td:nth-child(5),tbody td:nth-child(7),tbody td:nth-child(8)', function (e) {
            editor.inline( this,{
                onBlur: 'submit'
            });
        });
        poTable=$("#poTable").dataTable({
            ajax: {
				"url": "{{ asset('/Include/materialpo.php') }}",
                "data":{
                    mid:"{{$mid}}",
                    @if(!$me->View_PO_Listing)
                    userid:{{$me->UserId}},
                    @endif
                    @if($start && $end)
                    start:'{{$start}}',
                    end:"{{$end}}",
                    @endif
                    status:""
                }
			},
			columnDefs: [
				{ "visible": false, "targets": [1] },
                {"className": "dt-center", "targets": [0,2,3,4,5,6,7,8,9,10,12]},
                {"className": "dt-right", "targets": [11]}
			],
			responsive: false,
			colReorder: false,
			sScrollX: "100%",
			bScrollCollapse: true,
			bAutoWidth: true,
			sScrollY: "100%",
			dom: "ltip",
			iDisplayLength:10,
            rowId:"material.Id",
            order: [[ 10, "desc" ]],
            fnInitComplete: function(oSettings, json) {
                $('#potab').html("PO " + "[" + poTable.api().rows().count() +"]")
            },
			columns: [
				{ data: null,"render":"", title:"No"},
                { data: "materialpo.Id"},
                { data:"materialpo.PO_No",title:"PO NO"},
                { data:"material.MR_No",title:"MR NO"},
                { data:"item.Type",title:"Type"},
                { data:"vendor.Company_Account",title:"Company Name"},
                { data: "vendor.Company_Name",title:"Vendor Name"},
                { data:"materialpo.Terms",title:"Terms"},
                { data:"materialpo.Delivery_Date",title:"Delivery Date"},
                { data:"created.Name",title:"Created By"},
                { data:"materialpo.created_at",title:"Created At"},
                { data:'item.total',title:"Total",
                render:function(data){
                    return "RM " + parseFloat(data).toFixed(2);
                }},
                { data: null,title:"Action",
                render:function(data){
                    // return "<a target='_blank' href='{{url('material/print')}}/"+data.materialpo.Id+"'>Print</a>";
                    // return "<a target='_blank' href='{{url('material/PODetails')}}/"+data.materialpo.Id+"'><button class='btn btn-default btn-xs' title='View' style='width:unset'><i class='fa fa-wrench'></i></button></a>\
                    // <a><button class='btn btn-default btn-xs' title='Terminate' style='width:unset'><i class='fa fa-warning'></i></button></a>";
                    return '<a href="{{url("material/PODetails")}}/'+data.materialpo.Id+'" target="_blank"><button class="btn btn-default btn-xs" title="View" style="width:unset"><i class="fa fa-wrench"></i></button></a>'+
                        '<a onclick="cancelModal('+data.materialpo.Id+')"><button class="btn btn-default btn-xs" title="Cancel" style="width:unset"><i class="fa fa-warning"></i></button></a>';
                }}

			],
            autoFill: {
				editor:  editor,
			},
			select: {
                style:    'os',
                selector: 'td'
            },
        });

        poTable.api().on( 'order.dt search.dt', function () {
			poTable.api().column(0, {search:'applied', order:'applied'}).nodes().each( function (cell, i) {
				cell.innerHTML = i+1;
			} );
		} ).draw();
        $("thead input").keyup ( function () {

            /* Filter on the column (the index) of this element */
            if ($('#poTable').length > 0)
            {
                var colnum=document.getElementById('poTable').rows[0].cells.length;
                if (this.value=="[empty]")
                {
                    poTable.fnFilter( '^$', this.name,true,false );
                }
                else if (this.value=="[nonempty]")
                {
                    poTable.fnFilter( '^(?=\\s*\\S).*$', this.name,true,false );
                }
                else if (this.value.startsWith("!")==true && this.value.length>1)
                {
                    poTable.fnFilter( '^(?'+ this.value +').*', this.name,true,false );
                }
                else if (this.value.startsWith("!")==false)
                {
                    poTable.fnFilter( this.value, this.name,true,false );
                }
            }
        });
        cancelTable=$("#cancelTable").dataTable({
            ajax: {
				"url": "{{ asset('/Include/materialpo.php') }}",
                "data":{
                    mid:"{{$mid}}",
                    @if(!$me->View_PO_Listing)
                    userid:{{$me->UserId}},
                    @endif
                    @if($start && $end)
                    start:'{{$start}}',
                    end:"{{$end}}",
                    @endif
                    status:"Cancelled"
                }
			},
			columnDefs: [
				{ "visible": false, "targets": [1] },
                {"className": "dt-center", "targets": [0,2,3,4,5,6,7,8,9,10,12,13]},
                {"className": "dt-right", "targets": [11]}
			],
			responsive: false,
			colReorder: false,
			sScrollX: "100%",
			bScrollCollapse: true,
			bAutoWidth: true,
			sScrollY: "100%",
			dom: "ltip",
			iDisplayLength:10,
            rowId:"material.Id",
            order: [[ 10, "desc" ]],
            fnInitComplete: function(oSettings, json) {
                $('#canceltab').html("Cancelled " + "[" + cancelTable.api().rows().count() +"]")
            },
			columns: [
				{ data: null,"render":"", title:"No"},
                { data: "materialpo.Id"},
                { data:"materialpo.PO_No",title:"PO NO",width:"10%"},
                { data:"material.MR_No",title:"MR NO",width:"10%"},
                { data:"item.Type",title:"Type"},
                { data:"vendor.Company_Account",title:"Company Name"},
                { data: "vendor.Company_Name",title:"Vendor Name"},
                { data:"materialpo.Terms",title:"Terms"},
                { data:"materialpo.Delivery_Date",title:"Delivery Date"},
                { data:"created.Name",title:"Created By"},
                { data:"materialpo.created_at",title:"Created At"},
                { data:'item.total',title:"Total",width:"7%",
                render:function(data){
                    return "RM " + parseFloat(data).toFixed(2);
                }},
                { data:"materialpo.Reason",title:"Reason"},
                { data:"cancel.Name",title:"Cancel by"},
                { data:"materialpo.updated_at",title:"Cancel on"},
                { data: null,title:"Action",
                render:function(data){
            
                    return '<a href="{{url("material/PODetails")}}/'+data.materialpo.Id+'" target="_blank"><button class="btn btn-default btn-xs" title="View" style="width:unset"><i class="fa fa-wrench"></i></button></a>';
                }}

			],
            autoFill: {
				editor:  editor,
			},
			select: {
                style:    'os',
                selector: 'td'
            },
        });
        cancelTable.api().on( 'order.dt search.dt', function () {
			cancelTable.api().column(0, {search:'applied', order:'applied'}).nodes().each( function (cell, i) {
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
		<h1>PO Listing<small>Admin</small></h1>

		<ol class="breadcrumb">
			<li><a href="{{ url('/home') }}"><i class="fa fa-dashboard"></i> Home</a></li>
			<li><a href="#">Admin</a></li>
			<li class="active">PO Listing</li>
		</ol>
	</section>

	<section class="content">
        
        <br><br>

        <div class="row">
            <div class="col-md-4">
                <div class="input-group">
                    <div class="input-group-addon">
                        <i class="fa fa-clock-o"></i>
                    </div>
                    <input type="text" class="form-control" id="range" name="range">
                </div>
            </div>
            <div class="col-md-2">
                <div class="input-group">
                    <button type="button" class="btn btn-success btn" data-toggle="modal" onclick="refresh();">Refresh</button>
                </div>
            </div>
        </div>
        <br>
        <div class="row">
	        <div class="box">
                <div class="box-body">
                    <div class="nav-tabs-custom">
                        <ul class="nav nav-tabs">
                            <li class="active"><a href="#po" data-toggle="tab" id="potab">PO</a></li>
                            <li><a href="#cancel" data-toggle="tab" id="canceltab">Cancelled</a></li>
                        </ul>
                        <div class="tab-content">
                            <div class="active tab-pane" id="po">
                                <div class="row">
                                    <div class="col-md-12">
                                        <table id="poTable" cellspacing="0" width="100%" padding="30px" style="font-size: 13px;">
                                            <thead>
                                                
                                                <tr class="search">
                                                    @foreach($details as $key=>$values)
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
                                                            <th align='center'><input type='text' class='search_init' name='{{$i}}'  placemark='{{$a}}'></th>
                                                            
                                                            <th align='center'><input type='hidden' class='search_init' name='{{$i}}' placemark='{{$a}}'></th>
                                                        @endif
                                                    @endforeach
                                                </tr>
                                                
                                                <tr>
                                                    @foreach($details as $key=>$value)
                                                        @if($key == 0)
                                                            <td></td>
                                                            @foreach($value as $field=>$value)
                                                                <td>{{$field}}</td>
                                                            @endforeach
                                                            <td></td>
                                                            <td></td>
                                                        @endif
                                                    @endforeach
                                                </tr>
                                            </thead>

                                            <tbody>
                                                <?php $i=0;?>
                                                @foreach($details as $detail)
                                                    <tr id="row_{{$i}}">
                                                        <td></td>
                                                        @foreach($detail as $key=>$value)
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
                               
                            <div class="tab-pane" id="cancel">
                                <div class="row">
                                    <div class="col-md-12">
                                        <table id="cancelTable" cellspacing="0" width="100%" padding="30px" style="font-size: 13px;">
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="modal fade" id="cancelModal">
            <div class="modal-dialog modal-md">
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span></button>
                        <h4 class="modal-title">Cancel</h4>
                    </div>
                    <div class="modal-body">
                        <div class="row">
                            <div class="col-sm-12">
                                <span id="cancel_error" style="color:red;"></span>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-sm-12">
                                <label class='col-sm-3'>Reason<span style="color:red;">*</span></label>
                                <div class="col-sm-6">
                                    <textarea id="reason"  class="form-control" cols="10" rows="5"></textarea>
                                </div>
                            </div>
                        </div>
            
                        <input type="hidden" id='poid'>
                    </div>
                    <div class="modal-footer">
                        <button class="btn btn-primary btn-sm" id='approvalBtn' onclick='cancel()'>Cancel</button>
                        <button type="button" class="btn btn-warning btn-sm" data-dismiss="modal">Close</button>
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
    $(function () {
        $('#range').daterangepicker({locale: {
			format: 'DD-MMM-YYYY'
		}
        @if($start && $end)
		,startDate: '{{$start}}',
		endDate: '{{$end}}'
        @endif
        });
    });
function refresh()
{
    var d=$('#range').val();
    var arr = d.split(" - ");

    window.location.href ="{{ url("/material/PO") }}/{{$mid}}/"+arr[0]+"/"+arr[1];
}
function cancelModal(id){
    $("#cancelModal").modal('show');
    $("#poid").val(id);
}
function cancel(){
    $("#cancelModal").modal('hide');
    $.ajaxSetup({
        headers: { 'X-CSRF-Token' : $('meta[name=_token]').attr('content') }
    });
    let id=$("#poid").val();
    $.ajax({
        type: "post",
        url: "{{url('material/cancel')}}",
        data: {
            reason:$("#reason").val(),
            id:id
        },
        success: function (response) {
            if(response == 1){
                $("#reason").val('');
                poTable.api().ajax.reload(function(){
                    $('#potab').html("PO " + "[" + poTable.api().rows().count() +"]");
                });
                cancelTable.api().ajax.reload(function(){
                    $('#canceltab').html("Cancelled " + "[" + cancelTable.api().rows().count() +"]");
                });
            }
        }
    });
}
</script>

@endsection