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
      <script  src="{{ asset('/plugin/ckeditor4/ckeditor.js') }}"></script>
    <script type="text/javascript" language="javascript" class="init">
        var table,removeTable;
        var editor;
        $(function () {
            // editor=editor = new $.fn.dataTable.Editor({
            //     ajax: "{{ asset('/Include/poitem.php') }}",
            //     table: "#itemTable",
            //     idSrc: "materialrequest.Id",
            //     fields: [
            //             {
            //                 name: "materialrequest.Id",
            //                 type: "hidden",
            //             },
            //             {
            //                 label: "Additional Description:",
            //                 name: "materialrequest.Add_Description",
            //                 type:  'textarea',
            //             },
            //         ]
            //     });
            // $('#itemTable').on( 'click', 'tbody td:nth-child(5)', function (e) {
            //     editor.inline( this,{
            //         onBlur: 'submit'
            //     });
            // });
            table=$("#itemTable").dataTable({
            ajax: {
				"url": "{{ asset('/Include/poitem.php') }}",
                "data":{
                    poid:{{$data->Id}},
                    type:""
                }
			},columnDefs: [
				{ "visible": false, "targets": [2] },
                @if($data->Status == "")
                {"className": "dt-center", "targets": [0,1,2,3,6,7,11]},
                @else
                {"className": "dt-center", "targets": [0,1,2,3,6,7,10,11]},
                @endif
                {"className": "dt-left", "targets": [4,5,9]},
			],
			responsive: false,
			sScrollX: "100%",
			bScrollCollapse: true,
			bAutoWidth: true,
			sScrollY: "100%",
			dom: "tp",
			iDisplayLength:10,
            rowId:"materialpoitem.Id",
			columns: [
                {data:null,title:"<input type='checkbox' onclick='checkRemove()' id='itemCheckAll'/>",
                render:function(data){
                    return "<input type='checkbox' class='itemCheck' value='"+data.materialpoitem.Id+"'/>";
                }},
				{ data: null,"render":"", title:"No"},
                { data: "materialpoitem.Id"},
                { data:"materialpoitem.Type",title:"Type"},
                { data:'materialpoitem.Description',title:"Description"},
                { data:'materialpoitem.Add_Description',title:"Additional Description",
                  render:function(data){
                    if(data != "")
                        return data;
                    else return "-";
                }},
                { data:"materialpoitem.Qty",title:"Qty"},
                { data:"materialpoitem.Unit",title:"UOM"},
                {data:"materialpoitem.Price",title:"U/Price (RM)"},
                {data:null,title:"Total (RM)",
                render:function(data){
                    return (Math.round(((data.materialpoitem.Qty*data.materialpoitem.Price) + 0.00001) * 100) / 100).toFixed(2)
                }},
                {data:'editpo.Reason',title:"Reason",
                render:function(data){
                   if(data)
                        return data;
                    else return "-";
                }},
                {data:"users.Name",title:"Updated by",
                render:function(data){
                    if(data)
                        return data;
                    else return "-";
                }},
                @if($data->Status == "")
                {data:null,title:"Action",
                render:function(data){
                    return "<button class='btn btn-default btn-xs' title='Edit' onclick='editModal(this)' style='width:unset;'><i class='fa fa-pencil-square-o' aria-hidden='true'></i></button>";
                }},
                @endif
            ],
            });
            table.api().on( 'order.dt search.dt', function () {
                table.api().column(1, {search:'applied', order:'applied'}).nodes().each( function (cell, i) {
                    cell.innerHTML = i+1;
                } );
            }).draw();

            removeTable=$("#removeTable").dataTable({
            ajax: {
				"url": "{{ asset('/Include/removepo.php') }}",
                "data":{
                    poid:{{$data->Id}},
                }
			},columnDefs: [
				{ "visible": false, "targets": [1] },
                {"className": "dt-center", "targets": [0,2]},
			],
			responsive: false,
			sScrollX: "100%",
			bScrollCollapse: true,
			bAutoWidth: true,
			sScrollY: "100%",
			dom: "tp",
			iDisplayLength:10,
            rowId:"removepo.Id",
			columns: [
				{ data: null,"render":"", title:"No"},
                { data: "removepo.Id"},
                { data:'removepo.Description',title:"Description"},
                { data:'removepo.Add_Description',title:"Additional Description",
                  render:function(data){
                    if(data != "")
                        return data;
                    else return "-";
                }},
                { data:"removepo.Qty",title:"Qty"},
                { data:"removepo.Unit",title:"UOM"},
                {data:"removepo.Price",title:"U/Price (RM)"},
                {data:null,title:"Total (RM)",
                render:function(data){
                    return (Math.round(((data.removepo.Qty*data.removepo.Price) + 0.00001) * 100) / 100).toFixed(2)

                }},
                {data:'removepo.Reason',title:"Reason",
                render:function(data){
                   if(data)
                        return data;
                    else return "-";
                }},
                {data:"users.Name",title:"Delete by",
                render:function(data){
                    if(data)
                        return data;
                    else return "-";
                }},
               
            ],
            });
            removeTable.api().on( 'order.dt search.dt', function () {
                removeTable.api().column(0, {search:'applied', order:'applied'}).nodes().each( function (cell, i) {
                    cell.innerHTML = i+1;
                } );
            }).draw();
        });
        
    </script>

@endsection

@section('content')

<div class="content-wrapper">
    <!-- Content Header (Page header) -->
	<section class="content-header">
        <h1>PO Details<small>Admin</small></h1>
        
		<ol class="breadcrumb">
            <li><a href="{{ url('/home') }}"><i class="fa fa-dashboard"></i> Home</a></li>
			<li><a href="#">Admin</a></li>
			<li class="active">PO Details</li>
		</ol>
	</section>
    
	<section class="content">
        
        <br><br>
        <br>
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
                    <div class="row">
                        <div class="col-md-12">
                            <div class="col-md-3">
                                <label>PO NO: </label>
                                <span id="update_po">{{$data->PO_No}}</span>
                                <a style="width:unset;" class="btn btn-default btn-xs" onclick="editPoModal()"><i class="fa fa-pencil-square-o" aria-hidden="true"></i></a>
                            </div>
                            <div class="col-md-3">
                                <label>MR NO:</label>
                                {{$data->MR_No}}
                            </div>
                            <div class="col-md-3" style="float:right;">
                                <a target="_blank" style='width:unset;' href="{{url('material/printpo')}}/{{$data->Id}}" class="btn btn-primary btn-sm"><i class="fa fa-print" aria-hidden="true"></i> Print PO</a>
                            </div>
                        </div>
                    </div>
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="col-md-3">
                                    <label>Created By:</label>
                                    {{$data->Name}}
                                </div>
                                <div class="col-md-3">
                                    <label>Created At:</label>
                                    {{$data->created_at}}
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-12">
                                <div class="col-md-3">
                                    <label>Company: </label>
                                    <span id='companyAcc'>{{$data->Company_Account != "" ? $data->Company_Account:"-"}}</span>
                            </div>
                            <div class="col-md-3">
                                <label>Vendor: </label>
                                <span id='clientCompany'>{{$data->clientCompany}}</span>
                                <a style="width:unset;" class="btn btn-default btn-xs" onclick="editCompany()"><i class="fa fa-pencil-square-o" aria-hidden="true"></i></a>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-12">
                            <div class="col-md-3">
                                <label>Payment Terms:</label>
                                @if($data->Status == "")
                                <a style='width:unset;' title='Edit' class="btn btn-primary btn-sm" style="width:54px;" onclick="paymentModal()"><i class="fa fa-pencil-square-o" aria-hidden="true"></i></a>
                                @endif
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-12" id="changePaymentData">
                            <div class="col-md-12">
                                @foreach($payment as $pay)
                                @if($pay->OptionsId == 0)
                                        <div>
                                            <span style="white-space:pre-wrap;" >{{$pay->Value}}</span>
                                        </div>
                                        @elseif($pay->OptionsId != 0)
                                    <div>
                                        <span>{{$pay->Option}}</span>
                                    </div>
                                    @endif
                                    @endforeach
                                </div>
                            </div>
                        </div>
                        <br>
                    <div class="row">
                        <div class="col-md-12">
                            <div class="col-md-3">
                                <label>Extra:</label>
                                @if($data->Status == "")
                                <a class="btn btn-primary btn-sm" style="width:unset;" title='Edit' onclick="extraModal()"><i class="fa fa-pencil-square-o" aria-hidden="true"></i> </a>
                                @endif
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-12" id="changeExtraData">
                            <div class="col-md-12">
                                @foreach($extra as $ext)
                                @if($ext->OptionsId == 0)
                                <div>
                                    <span style="white-space:pre-wrap;" >{{$ext->Value}}</span>
                                </div>
                                @elseif($ext->OptionsId != 0)
                                <div>
                                    <span>{{$ext->Option}}</span>
                                </div>
                                @endif
                                @endforeach
                            </div>
                        </div>
                    </div>
                    <br>
                    <div class="row">
                        <div class="col-md-12">
                            <div class="col-md-3">
                                <label><b><u>Balance</u></b></label>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-12">
                            @foreach($balance as $bal)
                            <div class="col-md-3">
                                <label>{{$bal->Type}}: RM <span id='{{$bal->Type}}'>{{$bal->total}}</span></label>
                            </div>
                            @endforeach
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-12">
                            <div class="col-md-3">
                                <span style="color:red;" id="saveErr"></span>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-12">
                            <button style="width:unset;" class="btn btn-sm btn-default" onclick='removeModal()'><i class='fa fa-trash' aria-hidden='true'></i></button>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-12">
                            <table id="itemTable" cellspacing="0" width="100%" style="font-size: 13px;">
                            </table>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-12">
                            <div class="col-md-3">
                                <button style='width:unset;' onclick='save()' class="btn btn-sm btn-primary"><i class="fa fa-floppy-o" aria-hidden="true"></i> Save</button>
                            </div>
                            <div class="col-md-3" style="float:right;">
                                <label>Total</label>
                                RM <span id='sum'>{{number_format($data->total,2)}}</span>
                            </div>
                        </div>
                    </div>
                    <br>
                    <div class="row">
                        <div class="col-md-12">
                            <table id="removeTable" cellspacing="0" width="100%" style="font-size: 13px;">
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!--Payment Terms-->
        <div class="modal fade" id="paymentModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
            <div class="modal-dialog modal-lg" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                        <h4 class="modal-title" id="myModalLabel">Edit Payment Terms</h4>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-lg-12">
                            <span style="color:red;" id="paymentErr"></span>
                            <span style="color:green;" id="paymentSucc"></span>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-lg-12">
                            <label class="col-lg-2">Payment Terms: </label>
                            <div class="col-lg-10" id="insertPayment">
                                @foreach($payment as $pay)
                                    <div class="row" id="addPayment{{$pay->Id}}">
                                        @if($pay->OptionsId == 0)
                                        <span style="white-space:pre-wrap;" id="addPay{{$pay->Id}}" class="col-lg-6">{{$pay->Value}}</span>
                                        <a class="btn btn-xs btn-primary col-lg-2" onclick="editPayment({{$pay->Id}},{{$pay->OptionsId}})">Edit</a>
                                        <a class="btn btn-xs btn-warning col-lg-2" onclick="removePayment1({{$pay->Id}})">Remove</a>
                                        @elseif($pay->OptionsId != 0)
                                        <span id="addPay{{$pay->Id}}" class="col-lg-6">{{$pay->Option}}</span>
                                        <a class="btn btn-xs btn-primary col-lg-2" onclick="editPayment({{$pay->Id}},{{$pay->OptionsId}})">Edit</a>
                                        <a class="btn btn-xs btn-warning col-lg-2" onclick="removePayment1({{$pay->Id}})">Remove</a>
                                        @endif
                                    </div>
                                    <br>
                                @endforeach
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        
                        <div class="col-lg-3">
                            
                        </div>
                    </div>
                </div>
                
                <div class="modal-footer">
                    <div class="col-lg-6">
                        <div class="col-lg-4">
                            <select class="form-control input-sm" id='paymentOption'>
                                <option value="">Select Type</option>
                                <option value="text">Text</option>
                                <option value="option">Option</option>
                            <select>
                        </div>
                        
                        <button class="btn btn-primary btn-xs col-lg-2" onclick="addPaymentRow()">Add row</button>
                    </div>                   
                    <span id="noteMess" style="color:red;"></span>
                    <button class="btn btn-primary" onclick="savePaymentTerms()">Save</button>
                    <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                </div>
                </div>
            </div>
        </div>
        <!--Extra-->
        <div class="modal fade" id="extraModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
            <div class="modal-dialog modal-lg" role="document">
                <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                    <h4 class="modal-title" id="myModalLabel">Edit Extra</h4>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-lg-12">
                            <span style="color:red;" id="extraErr"></span>
                            <span style="color:green;" id="extraSucc"></span>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-lg-12">
                            <label class="col-lg-2">Extra: </label>
                            <div class="col-lg-10" id="insertExtra">
                                @foreach($extra as $ext)
                                    <div class="row" id="addExtra{{$ext->Id}}">
                                        @if($ext->OptionsId == 0)
                                        <span style="white-space:pre-wrap;" id="addPay{{$ext->Id}}" class="col-lg-6">{{$ext->Value}}</span>
                                        <a class="btn btn-xs btn-primary col-lg-2" onclick="editExtra({{$ext->Id}},{{$ext->OptionsId}})">Edit</a>
                                        <a class="btn btn-xs btn-warning col-lg-2" onclick="removeExtra1({{$ext->Id}})">Remove</a>
                                        @elseif($ext->OptionsId != 0)
                                        <span id="addExt{{$ext->Id}}" class="col-lg-6">{{$ext->Option}}</span>
                                        <a class="btn btn-xs btn-primary col-lg-2" onclick="editExtra({{$ext->Id}},{{$ext->OptionsId}})">Edit</a>
                                        <a class="btn btn-xs btn-warning col-lg-2" onclick="removeExtra1({{$ext->Id}})">Remove</a>
                                        @endif
                                    </div>
                                    <br>
                                @endforeach
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        
                        <div class="col-lg-3">
                            
                        </div>
                    </div>
                </div>
                
                <div class="modal-footer">
                    <div class="col-lg-6">
                        <div class="col-lg-4">
                            <select class="form-control input-sm" id='extraOption'>
                                <option value="">Select Type</option>
                                <option value="text">Text</option>
                                <option value="option">Option</option>
                            <select>
                        </div>
                        
                        <button class="btn btn-primary btn-xs col-lg-2" onclick="addExtraRow()">Add row</button>
                    </div>                   
                    <span id="noteMess" style="color:red;"></span>
                    <button class="btn btn-primary" onclick="saveExtra()">Save</button>
                    <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                </div>
                </div>
            </div>
        </div>

        <div class="modal fade" id="editModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
            <div class="modal-dialog modal-lg" role="document">
                <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                    <h4 class="modal-title" id="myModalLabel">Edit</h4>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-lg-12">
                            <span style="color:red;" id="editErr"></span>
                            <span style="color:green;" id="editSucc"></span>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-lg-12">
                            <label class="col-lg-2">Description</label>
                            <div class="col-lg-8">
                                <textarea  class="form-control" id="edit_description" cols="30" rows="5"></textarea>
                            </div>
                        </div>
                    </div>
                    <br>
                    <div class="row">
                        <div class="col-lg-12">
                            <label class="col-lg-2">Additional Description</label>
                            <div class="col-lg-8">
                                <textarea  class="form-control" id="edit_add_description" cols="30" rows="5"></textarea>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-lg-12">
                            <label class="col-lg-2">Qty</label>
                            <div class="col-lg-6">
                                <input type="number" id="edit_qty" class="form-control">
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-lg-12">
                            <label class="col-lg-2">Price</label>
                            <div class="col-lg-6">
                                <input type="number" id="edit_price" class="form-control">
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-lg-12">
                            <label class="col-lg-2">Reason</label>
                            <div class="col-lg-6">
                                <textarea style="white-space:pre-wrap;" id="edit_reason" cols="30" rows="5" class="form-control"></textarea>
                            </div>
                        </div>
                    </div>
                </div>
                <input type="hidden" id="edit_type">
                <input type="hidden" id="edit_id">
                <input type="hidden" id="edit_oriPrice">
                <input type="hidden" id="edit_oriQty">
                <div class="modal-footer">         
                    <button class="btn btn-primary" onclick="edit()">Edit</button>
                    <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                </div>
                </div>
            </div>
        </div>

        <div class="modal fade" id="removeModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
            <div class="modal-dialog modal-md" role="document">
                <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                    <h4 class="modal-title" id="myModalLabel">Remove</h4>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-lg-12">
                            <label class="col-lg-2">Reason</label>
                            <div class="col-lg-6">
                                <textarea style="white-space:pre-wrap;" id="remove_reason" cols="30" rows="5" class="form-control"></textarea>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">         
                    <button class="btn btn-primary" onclick="remove()">Remove</button>
                    <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                </div>
                </div>
            </div>
        </div>

        <div class="modal fade" id="editPoModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
            <div class="modal-dialog modal-md" role="document">
                <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                    <h4 class="modal-title" id="myModalLabel">Edit Po</h4>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-lg-12">
                            <label class="col-lg-2">PO</label>
                            <div class="col-lg-6">
                                <select id="edit_po" class="form-control">

                                </select>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">         
                    <button class="btn btn-primary" onclick="savePoNo()">Edit</button>
                    <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                </div>
                </div>
            </div>
        </div>

        <div class="modal fade" id="editVendorModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
            <div class="modal-dialog modal-md" role="document">
                <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                    <h4 class="modal-title" id="myModalLabel">Edit Vendor</h4>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-lg-12">
                            <label class="col-lg-2">Vendor</label>
                            <div class="col-lg-9">
                                <select id="edit_vendor" class="form-control">
                                    @foreach($companies as $company)
                                        <option value="{{$company->Id}}">{{$company->Company_Name}} ({{$company->Company_Account}})</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">         
                    <button class="btn btn-primary" onclick="saveVendor()">Edit</button>
                    <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
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
var edit_description;
var additional_desc;
$(function () {
    $("#edit_po").select2({
        width:"100%",
        dropdownParent: $("#editPoModal")
    });
    $("#edit_vendor").select2({
        width:"100%",
        dropdownParent: $("#editVendorModal")
    });
    edit_description=CKEDITOR.replace( 'edit_description');
    additional_desc=CKEDITOR.replace('edit_add_description');
});
function paymentModal()
{
    $('#paymentModal').modal('show');
}
var paymentNum=1;
var checkInsert=false;
var paymentOp=[];
@foreach($paymentOption as $op)
    paymentOp.push({
        name:"{{$op->Option}}",
        id:"{{$op->Id}}"
    })
@endforeach
function addPaymentRow()
{
    var op=$('#paymentOption').val();
    $('#paymentOption').val("");
    if(!checkInsert)
    {  
        if(op == "text")
        {
            var f="newPayment(" + paymentNum + ",'text')";
            $('#insertPayment').append('<div class="row removePayment" id="payment'+paymentNum+'"><div class="col-lg-6">\
            <textarea id="paymentV' + paymentNum + '"  class="form-control input-sm"></textarea></div>\
            <a class="btn btn-xs btn-primary col-lg-2" onclick="' + f + '">Add</a>\
            <a class="btn btn-xs btn-danger col-lg-2" onclick="removeRow('+paymentNum+')">Remove</a>\
            </div>');
            checkInsert=true;
        }
        else if(op == "option")
        {
            var f="newPayment(" + paymentNum + ",'option')";
            $('#insertPayment').append('<div class="row removePayment" id="payment' + paymentNum + '"><div class="col-lg-8"><select id="paymentV'+ paymentNum +
            '" class="form-control select2"><option value="" selected disabled >None</option>'+
            '</select></div><a class="col-sm-2 btn btn-xs btn-info" onclick="' + f + '">Add</a>\
            <a class="btn btn-xs btn-danger col-lg-2" onclick="removeRow('+paymentNum+')">Remove</a>\
            </div>');

            $.each(paymentOp, function(key, value) {   
                $('#paymentV' + paymentNum).append($("<option></option>")
                .attr("value",value.id)
                .text(value.name)); 
            });
            $(".select2").select2({
                width:'100%'
            }); 
            checkInsert=true;
        }
        else{
            $('#paymentErr').html("Please select option.");
            setTimeout(function(){
                $('#paymentErr').html('');
            }, 3000);
        }
        paymentNum++;
        
    }
    else if(checkInsert)
    {
        $('#paymentErr').html("Please fill in the details.");
        setTimeout(function(){
            $('#paymentErr').html('');
        }, 3000);
    }
    
}

var paymentArr=[];
function newPayment(id,type)
{
    var val= type == "option" ? $("#paymentV" + id + " option:selected").val():0;
    var paymentText= type == "option" ? $("#paymentV" + id + " option:selected").text():$("#paymentV" + id).val();

    $('#payment' + id).empty();
    if(type == 'option')
        $("#payment" + id).append("<span class='col-lg-6'>"+ paymentText + "</span>\
        <a class='btn btn-xs btn-warning col-lg-2' onclick='removePayment(" + id + ")'> Remove</a>");
    else
        $("#payment" + id).append("<span class='col-lg-6' style='white-space:pre-wrap;'>"+ paymentText + "</span>\
        <a class='btn btn-xs btn-warning col-lg-2' onclick='removePayment(" + id + ")'> Remove</a>");

    paymentArr.push({
        'no':id,
        "id":val,
        'option':paymentText
    });
    checkInsert=false;
    $('#paymentErr').html("Please click save button to save payment terms.");
}
function removePayment(row)//for temporary usage
{
    var filter=paymentArr.filter(x=>{return x.no != row});
    paymentArr=filter;
    $('#payment' + row).remove();
    if(paymentArr.length == 0)
        $('#paymentErr').html('');
} 
function editPayment(id,type)
{
    var store= type == 0 ? $('#addPay' + id).html():type;
    $('#addPayment' + id).empty();
    if(type == 0)
    {
        $('#addPayment' + id).append('<div class="col-sm-8"><textarea class="form-control input-sm" id="paymentV' + id + '"></textarea>'+ 
        '</div><a class="col-sm-2 btn btn-xs btn-info" onclick="editPayment1(' + id + ','+type+')">Edit</a>');
        $('#paymentV' + id).val(store);
    }
    else if(type != 0)
    {
        $('#addPayment' + id).append('<div class="col-sm-8"><select id="paymentV'+ id +
        '" class="form-control select2"><option value="" selected disabled >None</option>'+
        '</select></div><a class="col-sm-2 btn btn-xs btn-info" onclick="editPayment1(' + id + ','+type+')">Edit</a>');

        $.each(paymentOp, function(key, value) {   
            $('#paymentV' + id).append($("<option></option>")
            .attr("value",value.id)
            .text(value.name)); 
        });
        $('#paymentV' + id).val(type).change();
    
        $(".select2").select2({
            width:'100%'
        });
    }
}
var removePaymentArr=[];
function removePayment1(id) //to be delete from db
{
    removePaymentArr.push({
        'id':id
    });
    $('#addPayment' + id).remove();
    $('#paymentErr').html("Please click save button to save payment terms.");
}
var editPaymentValue=[];
function editPayment1(id,type) //store array
{
    var paymentva= type == 0 ? $('#paymentV' + id).val():$("#paymentV" + id + " option:selected").text();
    var paymentid= type == 0 ? 0:$("#paymentV" + id + " option:selected").val();
    editPaymentValue.push({
        "id":id,
        "paymentid":paymentid,
        'option':paymentva
    });
    $('#addPayment' + id).empty();
    if(type == 0)
    {
        $('#addPayment' + id).append("<span style='white-space:pre-wrap;' class='col-lg-6' id='addPay"+id+"'>"+ paymentva 
        + "</span><a class='btn btn-xs btn-primary col-lg-2' onclick='editPayment(" + id + ","+ type +")'>"+
        " Edit</a><a class='btn btn-xs btn-warning col-lg-2' onclick='removePayment1(" + id + ")'> Remove</a>");
    }
    else{
        $('#addPayment' + id).append("<span  class='col-lg-6' id='addPay"+id+"'>"+ paymentva 
        + "</span><a class='btn btn-xs btn-primary col-lg-2' onclick='editPayment(" + id + ","+ type +")'>"+
        " Edit</a><a class='btn btn-xs btn-warning col-lg-2' onclick='removePayment1(" + id + ")'> Remove</a>");
    }
    $('#paymentErr').html("Please click save button to save payment terms.");
}
function removeRow(row)
{
    $('#payment' + row).remove();
    checkInsert=false;
}
function savePaymentTerms()
{
    if(paymentArr.length != 0 || editPaymentValue.length != 0 || removePaymentArr.length != 0)
    {
        $.ajaxSetup({
            headers: { 'X-CSRF-Token' : $('meta[name=_token]').attr('content') }
        });
        $.ajax({
            type: "post",
            url: "{{url('material/savePaymentTerms')}}",
            data: {
                id:{{$data->Id}},
                new:paymentArr,
                edit:editPaymentValue,
                remove:removePaymentArr
            },
            success: function (result) {
                paymentArr=[];
                removePaymentArr=[];
                editPaymentValue=[];
                $('.removePayment').remove();
                $('#paymentMess').html('');
                $('#changePaymentData').empty();
                $("#paymentErr").html('');
                $("#paymentSucc").html("Payment terms updated.");
                setTimeout(() => {
                    $("#paymentSucc").html("");
                }, 3000);
                for(var x=0,len=result.length;x<len;x++)
                {
                    if(result[x].type == "new")
                    {
                        for(var i=0,l=result[x].obj.length;i<l;i++)
                        {
                            $('#insertPayment').append('<div class="row" id="addPayment'+result[x].obj[i].id+'"><span class="col-lg-6" id="addPay' + 
                            result[x].obj[i].id+ '">' + result[x].obj[i].option + '</span>\
                            <a class="btn btn-xs btn-primary col-lg-2" onclick="editPayment(' +result[x].obj[i].id+',' + result[x].obj[i].opId + 
                            ')"> Edit</a> <a class="btn btn-xs btn-warning col-lg-2" onclick="removePayment1(' + result[x].obj[i].id +')"> \
                            Remove</a></div>');
                        }
                    }
                    else if(result[x].type == "data")
                    {
                        for(var i=0,l=result[x].obj.length;i<l;i++)
                        {
                            if(result[x].obj[i].Option != null)
                                $('#changePaymentData').append('<div class="col-md-12"><span>'+ result[x].obj[i].Option+'</span>\
                                </br></div>');
                            else if(result[x].obj[i].Option == null)
                            {
                                $('#changePaymentData').append('<div class="col-md-12"><span style="white-space:pre-wrap;">'+result[x].obj[i].Value+'</span></div>');
                            }
                        }
                    }
                
                }
            }
        });
    }
    else{
        $("#paymentErr").html("Nothing to save.");
        setTimeout(() => {
            $("#paymentErr").html("");
        }, 3000);
    }
}
/*Extra */
function extraModal()
{
    $('#extraModal').modal('show');
}
var extraNum=1;
var checkInsertExtra=false;
var extraOp=[];
@foreach($extraOp as $op)
    extraOp.push({
        name:"{{$op->Option}}",
        id:"{{$op->Id}}"
    })
@endforeach

function addExtraRow()
{
    var op=$('#extraOption').val();
    $('#extraOption').val("");
    if(!checkInsertExtra)
    {  
        if(op == "text")
        {
            var f="newExtra(" + extraNum + ",'text')";
            $('#insertExtra').append('<div class="row removeExtra" id="extra'+extraNum+'"><div class="col-lg-6">\
            <textarea id="extraV' + extraNum + '"  class="form-control input-sm"></textarea></div>\
            <a class="btn btn-xs btn-primary col-lg-2" onclick="' + f + '">Add</a>\
            <a class="btn btn-xs btn-danger col-lg-2" onclick="removeExtraRow('+extraNum+')">Remove</a>\
            </div>');
            checkInsertExtra=true;
        }
        else if(op == "option")
        {
            var f="newExtra(" + extraNum + ",'option')";
            $('#insertExtra').append('<div class="row removeExtra" id="extra' + extraNum + '"><div class="col-lg-8"><select id="extraV'+ extraNum +
            '" class="form-control select2"><option value="" selected disabled >None</option>'+
            '</select></div><a class="col-sm-2 btn btn-xs btn-info" onclick="' + f + '">Add</a>\
            <a class="btn btn-xs btn-danger col-lg-2" onclick="removeExtraRow('+extraNum+')">Remove</a>\
            </div>');

            $.each(extraOp, function(key, value) {   
                $('#extraV' + extraNum).append($("<option></option>")
                .attr("value",value.id)
                .text(value.name)); 
            });
            $(".select2").select2({
                width:'100%'
            }); 
            checkInsertExtra=true;
        }
        else{
            $('#extraErr').html("Please select option.");
            setTimeout(function(){
                $('#extraErr').html('');
            }, 3000);
        }
        extraNum++;
        
    }
    else if(checkInsertExtra)
    {
        $('#extraErr').html("Please fill in the details.");
        setTimeout(function(){
            $('#extraErr').html('');
        }, 3000);
    }
    
}

var extraArr=[];
function newExtra(id,type)
{
    var val= type == "option" ? $("#extraV" + id + " option:selected").val():0;
    var extraText= type == "option" ? $("#extraV" + id + " option:selected").text():$("#extraV" + id).val();

    $('#extra' + id).empty();
    if(type == 'option')
        $("#extra" + id).append("<span class='col-lg-6'>"+ extraText + "</span>\
        <a class='btn btn-xs btn-warning col-lg-2' onclick='removeExtra(" + id + ")'> Remove</a>");
    else
        $("#extra" + id).append("<span class='col-lg-6' style='white-space:pre-wrap;'>"+ extraText + "</span>\
        <a class='btn btn-xs btn-warning col-lg-2' onclick='removeExtra(" + id + ")'> Remove</a>");

    extraArr.push({
        'no':id,
        "id":val,
        'option':extraText
    });
    checkInsertExtra=false;
    $('#extraErr').html("Please click save button to save extra.");
}
function removeExtra(row)//for temporary usage
{
    var filter=extraArr.filter(x=>{return x.no != row});
    extraArr=filter;
    $('#extra' + row).remove();
    if(extraArr.length == 0)
        $('#extraErr').html('');
} 

function editExtra(id,type)
{
    var store= type == 0 ? $('#addExt' + id).html():type;
    $('#addExtra' + id).empty();
    if(type == 0)
    {
        $('#addExtra' + id).append('<div class="col-sm-8"><textarea class="form-control input-sm" id="extraV' + id + '"></textarea>'+ 
        '</div><a class="col-sm-2 btn btn-xs btn-info" onclick="editExtra1(' + id + ','+type+')">Edit</a>');
        $('#extraV' + id).val(store);
    }
    else if(type != 0)
    {
        $('#addExtra' + id).append('<div class="col-sm-8"><select id="extraV'+ id +
        '" class="form-control select2"><option value="" selected disabled >None</option>'+
        '</select></div><a class="col-sm-2 btn btn-xs btn-info" onclick="editExtra1(' + id + ','+type+')">Edit</a>');

        $.each(extraOp, function(key, value) {   
            $('#extraV' + id).append($("<option></option>")
            .attr("value",value.id)
            .text(value.name)); 
        });
        $('#extraV' + id).val(type).change();
    
        $(".select2").select2({
            width:'100%'
        });
    }
}
var removeExtraArr=[];
function removeExtra1(id) //to be delete from db
{
    removeExtraArr.push({
        'id':id
    });
    $('#addExtra' + id).remove();
    $('#extraErr').html("Please click save button to save extra.");
}
var editExtraValue=[];
function editExtra1(id,type) //store array
{
    var extrava= type == 0 ? $('#extraV' + id).val():$("#extraV" + id + " option:selected").text();
    var extraid= type == 0 ? 0:$("#extraV" + id + " option:selected").val();
    editExtraValue.push({
        "id":id,
        "extraid":extraid,
        'option':extrava
    });
    $('#addExtra' + id).empty();
    if(type == 0)
    {
        $('#addExtra' + id).append("<span style='white-space:pre-wrap;' class='col-lg-6' id='addExt"+id+"'>"+ extrava 
        + "</span><a class='btn btn-xs btn-primary col-lg-2' onclick='editExtra(" + id + ","+ type +")'>"+
        " Edit</a><a class='btn btn-xs btn-warning col-lg-2' onclick='removeExtra1(" + id + ")'> Remove</a>");
    }
    else{
        $('#addExtra' + id).append("<span  class='col-lg-6' id='addExt"+id+"'>"+ extrava 
        + "</span><a class='btn btn-xs btn-primary col-lg-2' onclick='editExtra(" + id + ","+ type +")'>"+
        " Edit</a><a class='btn btn-xs btn-warning col-lg-2' onclick='removeExtra1(" + id + ")'> Remove</a>");
    }
    $('#extraErr').html("Please click save button to save extra.");
}
function removeExtraRow(row)
{
    $('#extra' + row).remove();
    checkInsertExtra=false;
}
function saveExtra()
{
    if(extraArr.length != 0 || editExtraValue.length != 0 || removeExtraArr.length != 0)
    {
        $.ajaxSetup({
            headers: { 'X-CSRF-Token' : $('meta[name=_token]').attr('content') }
        });
        $.ajax({
            type: "post",
            url: "{{url('material/saveExtra')}}",
            data: {
                id:{{$data->Id}},
                new:extraArr,
                edit:editExtraValue,
                remove:removeExtraArr
            },
            success: function (result) {
                extraArr=[];
                removeExtraArr=[];
                editExtraValue=[];
                $('.removeExtra').remove();
                $('#extraMess').html('');
                $('#changeExtraData').empty();
                $("#extraErr").html('');
                $("#extraSucc").html("Extra updated.");
                setTimeout(() => {
                    $("#extraSucc").html("");
                }, 3000);
                for(var x=0,len=result.length;x<len;x++)
                {
                    if(result[x].type == "new")
                    {
                        for(var i=0,l=result[x].obj.length;i<l;i++)
                        {
                            $('#insertExtra').append('<div class="row" id="addExtra'+result[x].obj[i].id+'"><span class="col-lg-6" id="addExt' + 
                            result[x].obj[i].id+ '">' + result[x].obj[i].option + '</span>\
                            <a class="btn btn-xs btn-primary col-lg-2" onclick="editExtra(' +result[x].obj[i].id+',' + result[x].obj[i].opId + 
                            ')"> Edit</a> <a class="btn btn-xs btn-warning col-lg-2" onclick="removeExtra1(' + result[x].obj[i].id +')"> \
                            Remove</a></div>');
                        }
                    }
                    else if(result[x].type == "data")
                    {
                        for(var i=0,l=result[x].obj.length;i<l;i++)
                        {
                            if(result[x].obj[i].Option != null)
                                $('#changeExtraData').append('<div class="col-md-12"><span>'+ result[x].obj[i].Option+'</span>\
                                </br></div>');
                            else if(result[x].obj[i].Option == null)
                            {
                                $('#changeExtraData').append('<div class="col-md-12"><span style="white-space:pre-wrap;">'+result[x].obj[i].Value+'</span></div>');
                            }
                        }
                    }
                
                }
            }
        });
    }
    else{
        $("#extraErr").html("Nothing to save.");
        setTimeout(() => {
            $("#extraErr").html("");
        }, 3000);
    }
}
function editModal(element){
    let el=element.closest('tr');
    let data=table.api().row(el).data();
    $("#edit_qty").val(data.materialpoitem.Qty);
    // $("#edit_description").val(data.materialpoitem.Description);
    edit_description.setData(data.materialpoitem.Description);
    additional_desc.setData(data.materialpoitem.Add_Description);
    $("#edit_price").val(data.materialpoitem.Price);
    $("#edit_id").val(el.id);
    $("#edit_type").val(data.materialpoitem.Type);
    $("#editModal").modal('show');
    $("#edit_oriPrice").val(data.materialpoitem.Price);
    $("#edit_oriQty").val(data.materialpoitem.Qty);
    
}
var editArr=new Array();
function edit(){
    let qty=parseFloat($("#edit_qty").val());
    let price=parseFloat($("#edit_price").val());
    let total=(Math.round(((qty*price) + 0.00001) * 100) / 100)
    let type=$("#edit_type").val();
    let oriQty=$("#edit_oriQty").val();
    let ori=parseFloat($("#edit_oriPrice").val())*oriQty;
    let bal=parseFloat($("#"+type).text())+ori;
    let reason=$("#edit_reason").val();
    let desc=edit_description.getData();
    let add_desc=additional_desc.getData();
    if(total > bal){
        $("#editErr").html("Out of Balance..");
        setTimeout(() => {
            $("#editErr").html("");
        }, 3000);
    }else{
        let row=$("#edit_id").val();
        if(reason != ""){
            table.api().row($("#"+row))
            .every(function (rowIdx, tableLoop, rowLoop) {
                table.api().cell(rowIdx,4).data(desc);
                table.api().cell(rowIdx,5).data(add_desc);
                table.api().cell(rowIdx,6).data(qty);
                table.api().cell(rowIdx, 8).data(parseFloat(price).toFixed(2));
                table.api().cell(rowIdx, 9).data(parseFloat(total).toFixed(2));
                table.api().cell(rowIdx, 10).data(reason);
            })
            .draw();
            let sum=parseFloat($("#sum").text().replace(/,/g,''));
            sum=sum-ori+total;
            $("#sum").html(parseFloat(sum).toFixed(2));
            let temp={
                id:row,
                qty:qty,
                price:price,
                desc:desc,
                add_desc:add_desc,
                reason:reason
            };
            $("#"+type).html(parseFloat(bal-total).toFixed(2));
            checkArray(editArr,temp);
            $("#edit_reason").val("");
            $("#editModal").modal('hide');
        }else{
            $("#editErr").html("Reason is required.");
            setTimeout(() => {
                $("#editErr").html("");
            }, 3000);
        }
        
    }
    
}
function checkArray(itemarr, obj) {
    const index = itemarr.findIndex((e) => e.id === obj.id);
    if (index === -1) {
        itemarr.push(obj);
    } else {
        itemarr[index] = obj;
    }
}
function save()
{
    if(editArr.length != 0){
       $.ajaxSetup({
            headers: { 'X-CSRF-Token' : $('meta[name=_token]').attr('content') }
        });
        $.ajax({
            type: "post",
            url: "{{url('material/savePoItem')}}",
            data: {
                data:editArr
            },
            success: function (response) {
                if(response == 1){
                    table.api().ajax.reload();
                    editArr=[];
                    $("#update-alert").modal('show');
                    $("#update-alert ul").html("Updated!");
                }
            }
        }); 
    }else{
        $("#saveErr").html("Nothing to save.");
        setTimeout(() => {
            $("#saveErr").html("");
        }, 3000);
    }
    
}
function checkRemove(){
    if ($("#itemCheckAll").is(':checked')){
        $(".itemCheck").prop("checked", true);
        $(".itemCheck").trigger("change");
        table.api().rows().select();
    }else{
        $(".itemCheck").prop("checked", false);
        $(".itemCheck").trigger("change");
        table.api().rows().deselect();
    }
}
function remove(){

    var checkbox= $('.itemCheck:checkbox:checked');
    var checkArr=[];
    for(var x=0,y=checkbox.length;x<y;x++){
        checkArr.push(checkbox[x].value);
    }
    if(checkArr.length != 0){
         $.ajaxSetup({
            headers: { 'X-CSRF-Token' : $('meta[name=_token]').attr('content') }
        });
        $.ajax({
            type: "post",
            url: "{{url('material/removePoItem')}}",
            data: {
                id:checkArr,
                reason:$("#remove_reason").val()
            },
            success: function (response) {
                if(response == 1){
                    $("#removeModal").modal('hide');
                    $("#remove_reason").val('');
                }
            }    
        });
    }else{
        alert("Please select item to remove.");
    }
   
}
function removeModal(){
    $("#removeModal").modal('show');
}
function editPoModal(){
    $("#editPoModal").modal('show');
    $("#edit_po").empty();
    $.ajax({
        type: "get",
        url: "{{url('material/getCancelledPo')}}",
        success: function (response) {
            for(var y=0,i=response.length;y<i;y++){
                console.log(response[y].Po_No);
                 $("#edit_po").append($('<option></option>')
                .attr('value',response[y].PO_No)
                .text(response[y].PO_No));
            }
        }
    });
}
function savePoNo(){
    if($("#edit_po").val()){
        $.ajaxSetup({
            headers: { 'X-CSRF-Token' : $('meta[name=_token]').attr('content') }
        });
        $.ajax({
            type: "post",
            url: "{{url('material/savePoNo')}}",
            data: {id:{{$data->Id}},
            po:$('#edit_po').val()},
            success: function (response) {
                if(response == 1){
                    $("#update_po").html($("#edit_po").val());
                    $("#editPoModal").modal('hide');
                }
            }
        });  
    }else{
        alert("Po missing");
    }
    
}
function editCompany(){
    $("#editVendorModal").modal('show');
}
function saveVendor(){
    if(confirm("Are you sure you want to save?")){
        $.ajaxSetup({
            headers: { 'X-CSRF-Token' : $('meta[name=_token]').attr('content') }
        });
        $.ajax({
            type: "post",
            url: "{{url('material/saveVendor')}}",
            data: {
                vendor:$("#edit_vendor option:selected").val(),
                id:{{$data->Id}}
            },
            success: function (response) {
                $("#editVendorModal").modal('hide');
                let temp=$("#edit_vendor option:selected").text().split(' (');
                $("#companyAcc").html(temp[1].split(')')[0]);
                $("#clientCompany").html(temp[0]);
            }
        });
    }
}
</script>
@endsection