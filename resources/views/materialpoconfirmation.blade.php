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
        /* table.dataTable tbody th,table.dataTable tbody td 
        {
             white-space: nowrap;
        }  */
        .dataTables_wrapper .dataTables_scroll div.dataTables_scrollBody th, .dataTables_wrapper .dataTables_scroll div.dataTables_scrollBody td {
	      	white-space: pre-wrap;
	    }
        .description{
            white-space:pre-wrap;
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
        var table,newTable,poTable,viewPoTable,poItemTable;
        var editor;
        $(function () {
            $("#fileTable").dataTable({
                "dom":"tp"
            });
            newTable=$("#newTable").dataTable({
                "dom":"tp",
                responsive: false,
                colReorder: false,
                sScrollX: "100%",
                bScrollCollapse: true,
                bAutoWidth: true,
                sScrollY: "100%",
            });
            viewPoTable=$("#viewPoTable").dataTable({
                "dom":"tp",
                responsive: false,
                colReorder: false,
                sScrollX: "100%",
                bScrollCollapse: true,
                bAutoWidth: true,
                sScrollY: "100%",
            });
            poItemTable=$("#poItemTable").dataTable({
                responsive: false,
                colReorder: false,
                sScrollX: "100%",
                bScrollCollapse: true,
                bAutoWidth: true,
                sScrollY: "100%",
            });
            poItemTable.on( 'order.dt search.dt', function () {
                poItemTable.api().column(0, {search:'applied', order:'applied'}).nodes().each( function (cell, i) {
                    cell.innerHTML = i+1;
                } );
            } ).api().draw();
            viewPoTable.on( 'order.dt search.dt', function () {
                viewPoTable.api().column(0, {search:'applied', order:'applied'}).nodes().each( function (cell, i) {
                    cell.innerHTML = i+1;
                } );
            } ).api().draw();
            $(".viewPoTable thead input").keyup ( function () {
                if ($('#viewPoTable').length > 0)
                {
                    var colnum=document.getElementById('viewPoTable').rows[0].cells.length;
                    if (this.value=="[empty]")
                    {
                        viewPoTable.fnFilter( '^$', this.name,true,false );
                    }
                    else if (this.value=="[nonempty]")
                    {
                        viewPoTable.fnFilter( '^(?=\\s*\\S).*$', this.name,true,false );
                    }
                    else if (this.value.startsWith("!")==true && this.value.length>1)
                    {
                        viewPoTable.fnFilter( '^(?'+ this.value +').*', this.name,true,false );
                    }
                    else if (this.value.startsWith("!")==false)
                    {
                        viewPoTable.fnFilter( this.value, this.name,true,false );
                    }
                }
            });
            newTable.on( 'order.dt search.dt', function () {
                newTable.api().column(0, {search:'applied', order:'applied'}).nodes().each( function (cell, i) {
                    cell.innerHTML = i+1;
                } );
		    } ).api().draw();
            editor = new $.fn.dataTable.Editor({
                    ajax: "{{ asset('/Include/materialpo.php') }}",
                    table: "#poTable",
                    idSrc: "materialpo.Id",
                    fields: [
                                {
                                    name: "materialpo.Id",
                                    type: "hidden",
                                },
                                {
                                    label: "Company Name:",
                                    name: "materialpo.CompanyId",
                                    type:  'select2',
                                    options: [
                                        
                                    ],
                                },
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
            table=$("#itemTable").dataTable({
            ajax: {
				"url": "{{ asset('/Include/mritem.php') }}",
                "data":{
                    mid:{{$mid}},
                    type:""
                }
			},
			columnDefs: [
				{ "visible": false, "targets": [1] },
                {"className": "dt-center", "targets": "_all"},
                // {"className": "dt-right", "targets": [8]}
			],
			responsive: false,
			colReorder: false,
			sScrollX: "100%",
			bScrollCollapse: true,
			bAutoWidth: true,
			sScrollY: "100%",
			dom: "tp",
			iDisplayLength:10,
            rowId:"materialrequest.Id",
            fnInitComplete: function(oSettings, json) {
                $(".vendor").select2({
                    width:"100%"
                })
                $(".price").on('change',function(){
                    var qty=$(this).closest('tr').find('#qty').text();
                    var price=$(this).val();
                    var element=$(this).closest('tr').find('#total').html(parseFloat(qty*price).toFixed(2));
                    var total=$(".total");
                    var sum=0;
                    for(var x=0;x<total.length;x++){
                        sum+=parseFloat(total.eq(x).text());
                    }
                    $("#sum").html(parseFloat(sum).toFixed(2));
                    sum=0;
                });
                $(".vendor").on('change',function(){
                    var price =$(this).find('option:selected').eq(0).data('price');
                    $(this).closest('tr').find('#price').val(price);
                    var qty=$(this).closest('tr').find('#qty').text();
                    $(this).closest('tr').find('#total').html(parseFloat(qty*price).toFixed(2));
                    var total=$(".total");
                    var sum=0;
                    for(var x=0;x<total.length;x++){
                        sum+=parseFloat(total.eq(x).text());
                    }
                    $("#sum").html(parseFloat(sum).toFixed(2));
                    sum=0;
                });
            },
			columns: [
				{ data: null, title:"No",},
                { data: "Id"},
                { data:'Type',title:"Type"},
                { data:"Item_Code",title:"Item Code"},
                { data:'Description',title:"Item Description"},    
                { data:"Acc_No",title:"Acc No",
                  render:function(data)
                  {
                      return "<span>" + data + "</span>";
                  }},   
                { data:"Qty",title:"Qty",
                  render:function(data){
                    return "<span>" + data +"</span>";
                  }
                },
                { data:"Unit",title:"UOM"},
                // { data:"materialrequest.Price",title:"U/Price (RM)",
                //   render:function(data){
                //       return "<input value='"+data+"' type='number' min='0' class='form-control price' id='price'>";
                //   },width:"155px"
                // },
                // {data:null,title:"Total (RM)",
                // render:function(data){
                //     return "<span id='total' class='total'>"+parseFloat(data.materialrequest.Qty*data.materialrequest.Price).toFixed(2)+"</span>";
                // }},
                { data:null,title:"Add",
                render:function(data){
                    return "<a class='btn btn-primary btn-sm' onclick='addModal(`"+data.Type+"`,`"+data.Acc_No+"`)'>Add</a>";
                },width:"150px"}

            ],
            });
            table.api().on( 'order.dt search.dt', function () {
                table.api().column(0, {search:'applied', order:'applied'}).nodes().each( function (cell, i) {
                    cell.innerHTML = i+1;
                } );
            }).draw();
            poTable=$("#poTable").dataTable({
            ajax: {
				"url": "{{ asset('/Include/materialpo.php') }}",
                "data":{
                    mid:"{{$mid}}",
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
                    return "<a target='_blank' href='{{url('material/PODetails')}}/"+data.materialpo.Id+"'>View</a>";
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
        }).draw();

        $('#viewPOModal,#poItemModal').on('shown.bs.modal', function (e) {
            $.fn.dataTable.tables({ visible: true, api: true }).columns.adjust();
        });
    });
        
    </script>

@endsection

@section('content')

<div class="content-wrapper">
	<!-- Content Header (Page header) -->
	<section class="content-header">
		<h1>PO Confirmation<small>Admin</small></h1>

		<ol class="breadcrumb">
			<li><a href="{{ url('/home') }}"><i class="fa fa-dashboard"></i> Home</a></li>
			<li><a href="#">Admin</a></li>
			<li class="active">PO Confirmation</li>
		</ol>
	</section>
	<section class="content">
        <br><br>
        <br>
        <div class="row">
	        <div class="box">
                <div class="box-body">
                    <div class="row">
                        <div class="col-md-12">
                            
                            <div class="col-md-3">
                                <label>MR NO:</label>
                                {{$detail->MR_No}}
                            </div>
                            
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-12">
                            <div class="col-md-3">
                                <label>Details:</label>
                                <a onclick="detailModal()" class="btn btn-xs btn-default" style="width:unset;"><i class="fa fa-pencil-square-o"></i></a>
                            </div>  
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-12" id='insertDetail'>

                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-12">
                            <div class="col-md-3">
                                <label>Uploaded File:</label>
                            </div>  
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-12">
                            <div class='col-md-3'><b><u>Total For Quotation</u></b></div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-12">
                            @foreach($filePrice as $p)
                            <div class="col-md-3">
                                <label>{{$p->Type}}:</label>
                                RM <span>{{number_format($p->total,2)}}</span>
                            </div>
                            @endforeach
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-sm-6">
                            <table id="fileTable" cellspacing="0" width="100%" padding="30px" style="font-size: 13px;">
                                <thead>
                                    <tr>
                                        <th>No</th>
                                        <th>File</th>
                                        <th>Type</th>
                                        <th>Amount</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php $i=1;?>
                                    @foreach($files as $file)
                                        <tr>
                                            <td>{{$i}}</td>
                                            <td><a target='_blank' href='{{$file->Web_Path}}'>{{$file->File_Name}}</a></td>
                                            <td>{{$file->Type}}</td>
                                            <td style="text-align:right;">{{$file->Amount}}</td>
                                        </tr>
                                        <?php $i++;?>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                    <br>
                    <div class="row">
                        <div class="col-md-12">
                            @foreach($price as $p)
                                <div class="col-md-3">
                                    <label>{{$p->Type}}:</label>
                                    RM <span id='{{$p->Type}}'>{{number_format($p->total,2)}}</span>
                                </div>
                            @endforeach
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-12">
                            <table id="itemTable" cellspacing="0" width="100%" padding="30px" style="font-size: 13px;">
                            </table>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-12">
                            <button onclick='PO()'>Import</button>
                        </div>
                    </div>
                    <br>
                    <div class="row">
                        <div class="col-md-12">
                            <table id="newTable" cellspacing="0" width="100%" padding="30px" style="font-size: 13px;">
                                <thead>
                                    <tr>
                                        <th>No</th>
                                        <th>Vendor</th>
                                        <th>Type</th>
                                        <th>Description</th>
                                        <th>Addtional Description</th>
                                        <th>Qty</th>
                                        <th>Unit</th>
                                        <th>Price</th>
                                        <th>Total</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                               
                            </table>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-12">
                            <div class="col-md-3" style="float:right;">
                                <label>Total</label>
                                RM <span id='sum'>0.00</span>
                            </div>
                            
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-12">
                            <div class="col-md-6">
                                <button class="btn btn-primary btn-sm" onclick="confirmModal()">Confirm</button>
                                <button class="btn btn-primary btn-sm" onclick="preview()">Preview</button>
                            </div>
                        </div>
                    </div>
                    <br>
                    <div class="row">
                        <div class="col-md-12">
                            <table id="poTable" cellspacing="0" width="100%" padding="30px" style="font-size: 13px;">
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="modal fade" id="confirmModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
            <div class="modal-dialog modal-md" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                        <h4 class="modal-title" id="myModalLabel">Confirmation</h4>
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
                                        <div class="col-sm-12">Are you sure you want to confirm?</div>
                                    </div>
                                </div>
                                {{--<br> 
                                    <div class='row'>
                                    <div class="form-group">
                                        <label class="col-sm-2">Company: </label>
                                        <div class="col-sm-6">
                                            <select id='company' class='form-control'>
                                                <option value="0" selected disabled>None</option>
                                                @foreach($companies as $c)
                                                    <option value="{{$c->Id}}">{{$c->Company_Name}}</option> 
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                </div> --}}
                                <br>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button class="btn btn-default" onclick="confirmPO()">Confirm</button>
                        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                    </div>
                </div>
            </div>
        </div>
        <!--Add Modal-->
        <div class="modal fade" id="addModal">
            <div class="modal-dialog modal-lg">
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span></button>
                        <h4 class="modal-title">Add</h4>
                    </div>
                    <div class="modal-body">
                        <div class="row">
                            <div class="col-sm-12">
                                <span id="add_error" style="color:red;"></span>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-sm-12">
                                <label class='col-sm-3'>Vendor<span style="color:red;">*</span></label>
                                <div class="col-sm-6">
                                    <select id="vendor" class='form-control input-sm'>
                                        <option value=""></option>
                                    </select>
                                </div>
                            
                            </div>
                        </div>
                        <br>
                        <div class="row">
                            <div class="col-sm-12">
                                <label class="col-sm-3">Acc No:</label>
                                <div class="col-sm-6">
                                    <input type="text" readonly id='acc' class='form-control input-sm'>
                                </div>
                            </div>
                        </div>
                        <br>
                        <div class="row">
                            <div class="col-sm-12">
                                <label class="col-sm-3">Description<span style="color:red;">*</span></label>
                                <div class="col-sm-9">
                                    <textarea  style='white-space:pre-wrap' class="form-control" id="description" ></textarea>
                                </div>
                            </div>
                        </div>
                        <br>
                        <div class="row">
                            <div class="col-sm-12">
                                <label class="col-sm-3">Additional Description</label>
                                <div class="col-sm-9">
                                    <textarea  style='white-space:pre-wrap' class="form-control" id="add_description" ></textarea>
                                </div>
                            </div>
                        </div>
                        <br>
                        <div class="row">
                            <div class="col-sm-12">
                                <label class="col-sm-3">Unit<span style="color:red;">*</span></label>
                                <div class="col-sm-6">
                                    <input type="text" id="unit" class="form-control input-sm">
                                </div>
                            </div>
                        </div>
                        <br>
                        <div class="row">
                            <div class="col-sm-12">
                                <div class="form-group">
                                    <label class="col-sm-3">Qty<span style="color:red;">*</span></label>
                                    <div class="col-sm-6">
                                        <input type="number" min="0" id="qty" class="form-control input-sm">
                                    </div>
                                </div>
                               
                            </div>
                        </div>
                        <br>
                        <div class="row">
                            <div class="col-sm-12">
                                <label class="col-sm-3">Price<span style="color:red;">*</span></label>
                                <div class="col-sm-6">
                                    <input type="number" id="price" class="form-control input-sm">
                                </div>
                            </div>
                        </div>
                        <input type="hidden" id='itemType'>
                    </div>
                    <div class="modal-footer">
                        <button class="btn btn-primary btn-sm" id='approvalBtn' onclick='Add()'>Add</button>
                        <button type="button" class="btn btn-warning btn-sm" data-dismiss="modal">Close</button>
                    </div>
                </div>
            </div>
        </div>

        <div class="modal fade" id="editModal">
            <div class="modal-dialog modal-lg">
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span></button>
                        <h4 class="modal-title">Edit</h4>
                    </div>
                    <div class="modal-body">
                        <div class="row">
                            <div class="col-sm-12">
                                <span id="edit_error" style="color:red;"></span>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-sm-12">
                                <label class='col-sm-3'>Vendor<span style="color:red;">*</span></label>
                                <div class="col-sm-6">
                                    <select id="edit_vendor" class='form-control input-sm'>
                                        <option value=""></option>
                                        @foreach($vendor as $v)
                                        <option value="{{$v->Id}}">{{$v->Company_Name}}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                        </div>
                        <br>
                        <div class="row">
                            <div class="col-sm-12">
                                <label class="col-sm-3">Acc No</label>
                                <div class="col-sm-6">
                                    <input type="text" readonly id='edit_acc' class='form-control input-sm'>
                                </div>
                            </div>
                        </div>
                        <br>
                        <div class="row">
                            <div class="col-sm-12">
                                <label class="col-sm-3">Description<span style="color:red;">*</span></label>
                                <div class="col-sm-9">
                                    <textarea  style='white-space:pre-wrap' class="form-control" id="edit_description" cols="10" rows="3"></textarea>
                                </div>
                            </div>
                        </div>
                        <br>
                        <div class="row">
                            <div class="col-sm-12">
                                <label class="col-sm-3">Additional Description</label>
                                <div class="col-sm-9">
                                    <textarea  style='white-space:pre-wrap' class="form-control" id="edit_add_description" cols="10" rows="5"></textarea>
                                </div>
                            </div>
                        </div>
                        <br>
                        <div class="row">
                            <div class="col-sm-12">
                                <label class="col-sm-3">Unit<span style="color:red;">*</span></label>
                                <div class="col-sm-6">
                                    <input type="text" id="edit_unit" class="form-control input-sm">
                                </div>
                            </div>
                        </div>
                        <br>
                        <div class="row">
                            <div class="col-sm-12">
                                <div class="form-group">
                                    <label class="col-sm-3">Qty<span style="color:red;">*</span></label>
                                    <div class="col-sm-6">
                                        <input type="number" min="0" id="edit_qty" class="form-control input-sm">
                                    </div>
                                </div>
                                
                            </div>
                        </div>
                        <br>
                        <div class="row">
                            <div class="col-sm-12">
                                <label class="col-sm-3">Price<span style="color:red;">*</span></label>
                                <div class="col-sm-6">
                                    <input type="number" id="edit_price" class="form-control input-sm">
                                </div>
                            </div>
                        </div>
                        <input type="hidden" id='edit_itemType'>
                    </div>
                    <input type="hidden" id="edit_row">
                    <div class="modal-footer">
                        <button class="btn btn-primary btn-sm" id='editBtn' >Edit</button>
                        <button type="button" class="btn btn-warning btn-sm" data-dismiss="modal">Close</button>
                    </div>
                </div>
            </div>
        </div>

        <div class="modal fade" id="viewPOModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
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
                                          <table id="viewPoTable" class="display viewPoTable" cellspacing="0" width="100%" padding="30px" style="font-size: 13px;">
                                            <thead>
                                                <tr class="search">
                                                    <th align='center'><input type='hidden' class='search_init' name='0'></th>
                                                    <th align='center'><input type='text' class='search_init' name='1'></th>
                                                    <th align='center'><input type='text' class='search_init' name='2'></th>
                                                    <th align='center'><input type='text' class='search_init' name='3'></th>
                                                    <th align='center'><input type='hidden' class='search_init' name='4'></th>
                                                </tr>
                                              <tr>
                                                <th>No</th>
                                                <th>PO NO</th>
                                                <th>Type</th>
                                                <th>Vendor Name</th>
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

        <div class="modal fade" id="poItemModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
            <div class="modal-dialog modal-lg" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                        <h4 class="modal-title" id="myModalLabel">Item</h4>
                    </div>
                    <div class="modal-body">
                        <div class="row">
                            <div class="col-sm-12">
                                <span id="poitem_error" style="color:red;"></span>
                            </div>
                        </div>
                        <div class="row">
                            <input type="hidden" id='tableNum'>
                            <div class="col-sm-12">
                                <br>
                                <div class='row'>
                                    <div class="form-group">
                                        <div class="col-sm-12" id="InsertMR">
                                            <table id="poItemTable" class="display" cellspacing="0" width="100%" padding="30px" style="font-size: 13px;">
                                            <thead>
                                                <tr>
                                                <th>No</th>
                                                <th>Description</th>
                                                <th>Additional Description</th>
                                                <th>Quantity</th>
                                                <th>Price</th>
                                                <th>Total</th>
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
                        <button class="btn btn-primary" onclick='importPO()'>Import</button>
                        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                    </div>
                </div>
            </div>
        </div>
        <div class="modal fade" id="detailModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
            <div class="modal-dialog modal-md" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                        <h4 class="modal-title" id="myModalLabel">Details</h4>
                    </div>
                    <div class="modal-body">
                        <div class="row">
                            <div class="col-sm-12">
                                <span id="detail_error" style="color:red;"></span>
                            </div>
                        </div>
                        <div class="row">
                            <input type="hidden" id='tableNum'>
                            <div class="col-sm-12">
                                <br>
                                <div class='row'>
                                    <div class="form-group">
                                        <div class="col-sm-12">
                                            <label class="col-sm-3">Company</label>
                                            <div class="col-sm-6">
                                                <select id="detail_company" class="form-control">
                                                    <option value="0">None</option>
                                                    <option value="OMNI AVENUE SDN BHD">OMNI AVENUE SDN BHD</option>
                                                    <option value="MIDASCOM NETWORK SDN BHD">MIDASCOM NETWORK SDN BHD</option>
                                                    <option value="HN CITRA CAHAYA SDN BHD">HN CITRA CAHAYA SDN BHD</option>
                                                    <option value="MIDASCOM PERKASA SDN BHD">MIDASCOM PERKASA SDN BHD</option>
                                                </select>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <br>
                                <div class='row'>
                                    <div class="form-group">
                                        <div class="col-sm-12">
                                            <label class="col-sm-3">Terms</label>
                                            <div class="col-sm-6">
                                                <select id="detail_term" class="form-control">
                                                    <option value="0">None</option>
                                                    @foreach($terms as $term)
                                                    <option value="{{$term->Option}}">{{$term->Option}}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <br>
                                <div class='row'>
                                    <div class="form-group">
                                        <div class="col-sm-12">
                                            <label class="col-sm-3">Delivery Date</label>
                                            <div class="col-sm-6">
                                                <input type="text" id="date" style="background-color:white;cursor:pointer;" class="form-control" readonly>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button class="btn btn-primary" onclick='addDetail()'>Edit</button>
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
var ck_desc,ck_add,ck_editDes,ck_editAdd;
$(function () {
    $("#company").select2({
        width:"100%"
    });
    $("#detail_company").on('change',function(){
        $.ajax({
            type: "get",
            url: "{{url('material/filterClient')}}",
            data: {
                name:$("#detail_company option:selected").val()
            },
            success: function (response) {
                if(response.length > 0){
                    $("#vendor").empty();
                    for(let x=0,i=response.length;x<i;x++){
                         $('#vendor').append($("<option></option>")
                        .attr("value",response[x].Id)
                        .text(response[x].Company_Name+"("+response[x].CreditorCode+")")); 
                    }
                   
                }
            }
        });
    })
    // $.each(vendor, function(key, value) {   
    //     $('#vendor').append($("<option></option>")
    //     .attr("value",value.id)
    //     .text(value.name)); 
    // });
    $('#vendor').select2({width:"100%"});
    $('#edit_vendor').select2({width:"100%"});
    
    ck_desc=CKEDITOR.replace( 'description');
    ck_add=CKEDITOR.replace( 'add_description');
    ck_editDes=CKEDITOR.replace( 'edit_description');
    ck_editAdd=CKEDITOR.replace( 'edit_add_description');
    $("#date").datepicker({
        autoclose:true,
        format:"dd-mm-yyyy"
    });
    $("#detail_term").select2({width:"100%"});
    $("#detail_company").select2({width:"100%"});
});
function confirmPO()
{
    let temp=parseFloat($("#"+arr[0].type).text());
    let amount=parseFloat($("#sum").text());
    if((temp+amount) < amount){
        alert("Out of Balance for " + arr[0].type);
        return ;
    }
    if(arr.length != 0 && !((temp+amount) < amount) && confirm("Are you sure you want to generate?"))
    {
        $.ajaxSetup({
            headers: { 'X-CSRF-Token' : $('meta[name=_token]').attr('content') }
        });
        $.ajax({
            type: "post",
            url: "{{url('material/confirmPO')}}",
            data: {
                MaterialId:{{$mid}},
                vendor:vendor,
                arr:arr,
                detail:detail
            },
            success: function (response) {
                $("#confirmModal").modal('hide');
                for(var x=0;x<response.length;x++){
                    window.open('{{url('material/PODetails')}}/' +response[x]);
                }
                window.location.reload();
            }
        });
    }else if(arr.length == 0){
        $("#confirm_error").html("Please add item.");
        setTimeout(() => {
            $("#confirm_error").html('');
        }, 3000);
    }
}
function confirmModal()
{
    $("#confirmModal").modal('show');
}
var vendor=new Array();
@foreach($vendor as $v)
vendor.push({
    id:{{$v->Id}},
    name:"{{$v->Company_Name}}"
});
@endforeach
var arr=new Array();
var count=1;
var totalVal=0;
async function Add()
{
    let tablerow=await newTable.api().rows().count()+1;
    let type=await $("#itemType").val();
    let temp=parseFloat( $("#"+type).text().replace(/,/g,'') );

    let price=$("#price").val();
    // let desc=$("#description").val();
    let desc=ck_desc.getData();
    // let add_desc=$("#add_description").val();
    let add_desc=ck_add.getData();
    let unit=$("#unit").val();
    let vendor=$("#vendor option:selected").text();
    let qty=$("#qty").val();
    // let total=parseFloat(qty*price).toFixed(2);
   console.log(qty)
   console.log(price)
    let total=(Math.round(((qty*price) + 0.00001) * 100) / 100).toFixed(2);
    console.log(total)
    let acc=$('#acc').val();
    if(desc == "" || vendor == 0 || unit == "" || qty == "" || price == ""){
        $("#add_error").html("Please fill in all the details.");
        setTimeout(() => {
            $("#add_error").html("");
        }, 3000);
        return ;
    }
    if(parseFloat(temp) < parseFloat(total)){

        $("#add_error").html('Out of Balance for ' + type);
        setTimeout(() => {
            $("#add_error").html('');
        }, 3000);
        return ;
    }
    else{
        newTable.api().row.add([""
        ,vendor,type,"<span class='description'>"+desc+"</span>","<span class='description'>"+add_desc+"</span>",qty,unit,price,total,
        "<a style='width:unset;' class='btn btn-primary btn-xs' onclick='editModal("+count+",this)'><i class='fa fa-pencil-square-o' aria-hidden='true'></i></a> "+"<a style='width:unset;' class='btn btn-warning btn-xs' onclick='remove("+count+",this)'><i class='fa fa-trash' aria-hidden='true'></i></a>"]).node().id="new_row_"+count;
        newTable.api().draw(false);

        $("#"+type).html(parseFloat(temp-total).toFixed(2));
        arr.push({
            'no':count,
            "vendorId":$("#vendor option:selected").val(),
            "description":desc,
            "add_desc":add_desc,
            "unit":unit,
            "qty":qty,
            "price":price,
            "type":type,
            'acc':acc
        });
        count++;
        totalVal+=parseFloat(total);
        $("#sum").html(parseFloat(totalVal).toFixed(2));
        $("#addModal").modal('hide');
        $("#vendor").val(0).change();
        $("#description").val('');
        $("#add_description").val('');
        $('#unit').val('');
        $('#qty').val('');
        $('#price').val('');
    }
      
}
function addModal(type,acc){
    $("#addModal").modal('show');
    $("#itemType").val(type);
    $('#acc').val(acc);
}
function editModal(no,element){
    $("#editModal").modal('show');
    let filter=arr.filter(a=>a.no == no);
    $("#edit_vendor").val(filter[0].vendorId).change();
    // $("#edit_description").val();
    ck_editDes.setData(filter[0].description);
    // $("#edit_add_description").val(filter[0].add_desc);
    ck_editAdd.setData(filter[0].add_desc)
    $("#edit_acc").val(filter[0].acc);
    $("#edit_unit").val(filter[0].unit);
    $("#edit_qty").val(filter[0].qty);
    $("#edit_price").val(filter[0].price);
    $("#edit_row").val(no);
    $("#edit_itemType").val(filter[0].type);
    $("#editBtn").attr('onclick','edit()');
}
function remove(no,element){
    if(confirm('Are you sure you want to delete?')){
        let filter=arr.filter(a=>a.no == no);
        let total=filter[0].price*filter[0].qty;
        let type=filter[0].type;
        let val=parseFloat( $("#"+type).text().replace(/,/g,'') );
        totalVal-=total;
        $("#sum").html(parseFloat(totalVal).toFixed(2));
        $("#"+type).html(parseFloat(val+total).toFixed(2));
        newTable.api().row( element.closest('tr')).remove().draw();
        filter=arr.filter(a=>a.no != no);
        arr=filter;  
    }
}
function edit(){
    let no=parseInt($("#edit_row").val());
    let vendor=$("#edit_vendor option:selected").val();
    let desc=ck_editDes.getData();
    let add=ck_editAdd.getData();
    let acc=$("#edit_acc").val();
    let unit=$("#edit_unit").val();
    let qty=parseFloat($("#edit_qty").val());
    let price=parseFloat($("#edit_price").val());
    let type=$("#edit_itemType").val();
    let val=parseFloat( $("#"+type).text().replace(/,/g,'') );
    let filter=arr.filter(e=>e.no == no);
    let oriPrice=val+(filter[0].qty*filter[0].price);
    let total=(Math.round(((qty*price) + 0.00001) * 100) / 100);
    console.log(total)
    if(parseFloat(oriPrice) < parseFloat(price*qty))
    {
       $("#edit_error").html("Out of Balance for " + type + ".");
        setTimeout(() => {
            $("#edit_error").html("");
        }, 3000);
    }else{
        if(desc != "" && unit != "" && vendor != "" && qty != "" && price != ""){
            $("#"+type).html(parseFloat(oriPrice-(total)).toFixed(2));
            let temp={
                no:parseInt(no),
                vendorId:vendor,
                description:desc,
                add_desc:add,
                unit:unit,
                qty:qty,
                price:price.toFixed(2),
                type:type,
                acc:acc
            };
            checkArray(arr,temp);
            $("#editModal").modal('hide');
            totalVal+=(qty*price)-parseFloat(filter[0].qty*filter[0].price);
            $("#sum").html(parseFloat(totalVal).toFixed(2));
            let data=["",$("#edit_vendor option:selected").text(),type,desc,add,qty,unit,parseFloat(price).toFixed(2),parseFloat(total).toFixed(2),
            "<a style='width:unset' class='btn btn-primary btn-xs' onclick='editModal("+no+",this)'><i class='fa fa-pencil-square-o' aria-hidden='true'></i></a> "+"<a style='width:unset;' class='btn btn-warning btn-xs' onclick='remove("+no+",this)'><i class='fa fa-trash' aria-hidden='true'></a>"];
            newTable.api().row('#new_row_'+no).data(data).draw(false); 

        }else{
            $("#edit_error").html("Please fill in details");
            setTimeout(() => {
                $("#edit_error").html("");
            }, 3000);
        }
        
    }
    
}
function checkArray(itemarr, obj) {
    const index = itemarr.findIndex((e) => e.no === obj.no);
    if (index === -1) {
        itemarr.push(obj);
    } else {
        itemarr[index] = obj;
    }
}
function PO(){
    viewPoTable.api().clear().draw();
    $.ajax({
        type: "get",
        url: "{{url('material/getAllPO')}}",
        data: {
            id:{{$detail->Id}}
        },
        success: function (response) {
            for(let y=0,i=response.length;y<i;y++){
                viewPoTable.api().row.add([
                    "",response[y].PO_No,response[y].Type,response[y].vendorName,"<a><button onclick='poItem("+response[y].Id+")'>Import</button></a>"
                ]).draw(false);
            }
            $('#viewPOModal').modal('show');
        }
    });
}
function poItem(id){
    poItemTable.api().clear().draw();
    $.ajax({
        type: "get",
        url: "{{url('material/PoItem')}}",
        data: {
            id:id
        },
         success: function (response) {
            for(var y=0,i=response.length;y<i;y++){
                poItemTable.api().row.add([
                    "",response[y].Description,response[y].Add_Description,response[y].Qty,response[y].Price,parseFloat(response[y].total).toFixed(2)
                    ,"<input type='checkbox' class='itemCheck' name='itemCheck' value='"+response[y].Id+"' data-accno='"+response[y].AccNo+"' data-unit='"+response[y].Unit+"'\
                     data-type='"+response[y].Type+"' data-vendorid='"+response[y].vendorId+"' data-vendor='"+response[y].vendorName+"'>"
                ]).draw(false);
            }
            $("#poItemModal").modal('show');
        }
    });
}
function importPO(){
    $('#poItemTable tr').each(function(i,row){
      var row=$(row);
      checkbox=row.find('.itemCheck:checkbox:checked');
      checkbox.each(function(i,checkb){
        let acc=$(this).data('accno');
        let type=$(this).data('type');
        let unit=$(this).data('unit');
        let venId=$(this).data('vendorid');
        let vendor=$(this).data('vendor');
        var tblrow=$(this).closest('tr');
        poItemTable.api().row(tblrow)
        .every(function (rowIdx, tableLoop, rowLoop) {
            let temp=parseFloat( $("#"+type).text().replace(/,/g,'') );
            let desc=poItemTable.api().cell(rowIdx,1).data();
            let add=poItemTable.api().cell(rowIdx,2).data();
            let qty=parseFloat(poItemTable.api().cell(rowIdx,3).data());
            let p=parseFloat(poItemTable.api().cell(rowIdx,4).data());
            let t=parseFloat(poItemTable.api().cell(rowIdx,5).data()).toFixed(2);
            // if(t > temp){
            //     // $("#poitem_error").html("Out of balance for "+type);
            //     // setTimeout(() => {
            //     //     $("#poitem_error").html('');
            //     // }, 3000);
            // }else
            {
                temp=temp-parseFloat(p*qty);
                $("#"+type).html(parseFloat(temp).toFixed(2));
                    arr.push({
                    'no':count,
                    "vendorId":venId,
                    "description":desc,
                    "add_desc":add,
                    "unit":unit,
                    "qty":qty,
                    "price":p,
                    "type":type,
                    'acc':acc
                });
                totalVal+=(Math.round(((qty*p) + 0.00001) * 100) / 100);
                let total=(Math.round(((qty*p) + 0.00001) * 100) / 100).toFixed(2);
                newTable.api().row.add([""
                ,vendor,type,"<span class='description'>"+desc+"</span>","<span class='description'>"+add+"</span>",qty,unit,p,total,
                "<a style='width:unset;' class='btn btn-primary btn-xs' onclick='editModal("+count+",this)'><i class='fa fa-pencil-square-o' aria-hidden='true'></i></a> "+"<a style='width:unset;' class='btn btn-warning btn-xs' onclick='remove("+count+",this)'><i class='fa fa-trash' aria-hidden='true'></i></a>"]).node().id="new_row_"+count;
                newTable.api().draw(false);
                count++;
            }
            $("#sum").html(parseFloat(totalVal).toFixed(2));
        });
      })
      $("#poItemModal").modal('hide');
    });
}
function check(index){
    if ($("#itemCheckAll").is(':checked')){
        $(".itemCheck").prop("checked", true);
        $(".itemCheck").trigger("change");
        poItemTable.api().rows().select();
    }else{
        $(".itemCheck").prop("checked", false);
        $(".itemCheck").trigger("change");
        poItemTable.api().rows().deselect();
    }
}
function detailModal(){
    $("#detailModal").modal('show');
}
var detail;
function addDetail(){
    let c=$("#detail_company option:selected").val();
    let term=$("#detail_term").val();
    let date=$("#date").val();
    let cname=$("#detail_company option:selected").text();
    detail={
        'company':c,
        term:term,
        date:date
    }
    $("#insertDetail").html("<div class='col-sm-4'>\
    <label>Company:</label> "+(c == "0"? "-":cname)+"\
    </div><div class='col-sm-4'><label>Term:</label> "+(term == "0"? "-":term)+"</div><div class='col-sm-4'>\
    <label>Delivery Date:</label> "+(date == ""? "-":date)+"\
    </div>");
    $("#detailModal").modal('hide');
}
function preview(){
    if(arr.length != 0 && detail){
        $.ajax({
            type: "get",
            url: "{{url('material/previewPo')}}",
            data: {
                detail:detail,
                arr:arr,
                id:{{$detail->Id}}
            },
            success: function (response) {
                var newWindow = window.open();
                newWindow.document.write(response.html);
                newWindow.document.close();
            }
        });
    }
    else{
        alert("Please select company and item in order to use this preview.");
    }
    
}
</script>
@endsection