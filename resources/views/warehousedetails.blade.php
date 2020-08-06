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
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/gh/fancyapps/fancybox@3.5.7/dist/jquery.fancybox.min.css" />

    <style type="text/css" class="init">
    
    a.buttons-collection {
        margin-left: 1em;
    }

    .tableheader {
        background-color: gray;
    }

    .container1 {
        width: 1200px;
        margin-left: 50px;
        padding: 10px;
    }

    .green {
        color: green;
    }

    .timeheader{
        background-color: gray;
    }

    .timeheader th{
        text-align: center;
    }

    .yellow {
        color: #f39c12;
    }

    .red{
        color:red;
    }

    .success {
        color: #00a65a;
    }

    .alert2 {
        color: #dd4b39;
    }

    .warning {
        color: #f39c12;
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
<script src="https://cdn.jsdelivr.net/gh/fancyapps/fancybox@3.5.7/dist/jquery.fancybox.min.js"></script>

    <script type="text/javascript" language="javascript" class="init">
        $(function(){
            
            // var t=$(".display").DataTable({
            //     "bInfo": false,
            //     "dom": '<"top"i>rt<"bottom"p><"clear">'
            // });

            // var editor deliveryTable

            var editor = new $.fn.dataTable.Editor( {
                                ajax: "{{ asset('/Include/warehouseitem.php?formId='.$formId) }}",
                                 table: "#deliveryTable",
                                 idSrc: "deliveryitem.Id",
                                 fields: [
                                        {
                                        
                                                 label: "formId",
                                                 name : "deliveryitem.formId",
                                                 type : "hidden"
                                        },
                                        {

                                                 label: "Approve Quantity",
                                                 name : "deliveryitem.approve_qty"
                                        }
                                 ]
                         } );

            @if(strpos($details->delivery_status,"Processing")!==false && strpos($details->delivery_status_details,"Accepted by Admin")!==false)
            $('#deliveryTable').on( 'click', 'tbody td', function (e) {
                        editor.inline( this, {
                          onBlur: 'submit'
                        } );
                      } );
            @endif

// if( ({{$details->delivery_status}}=="Processing") && ({{$details->delivery_status_details}} =="Accepted by Admin"))
//             {
//             }

            deliveryTable=$('#deliveryTable').DataTable( {
                           ajax: {
                             "url": "{{ asset('/Include/warehouseitem.php') }}",
                             "data": {
                                 "UserId": {{ $me->UserId }},
                                 "formId": {{ $formId }}
                             }
                           },
                                 columnDefs: [{ "visible": false, "targets" :[1]},{"className": "dt-center", "targets": "_all"}],
                                 responsive: false,
                                 colReorder: false,
                                 dom: "Blftp",
                                 sScrollX: "100%",
                                 bAutoWidth: true,
                                 sScrollY: "100%",
                                 rowId:"deliveryitem.Id",
                                 scrollCollapse: true,
                                 // aaSorting:false,
                                 columns: [
                                         { data: null, "render":"", title:"No"},
                                         { data: "deliveryitem.Id"},
                                         // { data: "deliveryitem.formId"},
                                         { data: "inventories.Categories", title:"Categories"},
                                         { data: "inventories.Item_Code", title:"Item Code"},
                                         { data: "inventories.Description", title:"Item Description"},
                                         { data: "deliveryitem.add_desc", title:"Additional Description"},
                                         { data: "inventories.Unit", title:"Unit"},
                                         { data: "deliveryitem.remarks", title:"Remarks"},
                                         { data: "deliveryitem.Qty_request", title:"Request Quantity"},
                                         { data: "deliveryitem.approve_qty", title:"Available Quantity"
                                         // "render": function ( data, type, full, meta ) {
                                         //        var request = full.deliveryitem.Qty_request;
                                         //        var approve = full.deliveryitem.approve_qty;
                                         //        console.log(request)
                                                // if(approve> request)
                                                // {
                                                    // return 'approve = 0';
                                                // }
                                            // }
                                         },
                                         {   data: "deliveryitem.available", 
                                             title:"Enough Quantity",
                                            "render": function ( data, type, full, meta ) {
                                                if(full.deliverystatuses.delivery_status =="Processing" && full.deliverystatuses.delivery_status_details == "Accepted by Admin")
                                                {
                                                return '<input  type="checkbox" id="check'+full.deliveryitem.Id+'" name="check[]" value="'+full.deliveryitem.Id+'">'
                                                }
                                                else
                                                {
                                                    if(full.deliveryitem.available==1)
                                                    {
                                                    return '<input  type="checkbox" id="check'+full.deliveryitem.Id+'" name="check[]" disabled value="'+full.deliveryitem.Id+'" checked="checked"/>'
                                                    }
                                                    else{
                                                    return '<input  type="checkbox" id="check'+full.deliveryitem.Id+'" name="check[]" disabled value="'+full.deliveryitem.Id+'"/>'
                                                    }
                                                 }
                                             }
                                        },
                                          ],
                                 select: {
                                         style:    'os',
                                         selector: 'tr'
                                       },
                                       buttons:[],
                     });

                function GetNumberOfRows() {
                    return row = $('#deliveryTable').DataTable().rows().indexes().length;

                }

            deliveryTable.on( 'order.dt search.dt', function () {
                          deliveryTable.column(0, {search:'applied', order:'applied'}).nodes().each( function (cell, i) {
                              cell.innerHTML = i+1;
                          } );
                          } ).draw();

        });
    </script>

@endsection

@section('content')

<div class="content-wrapper">

    <!-- Content Header (Page header) -->
    <section class="content-header">
        <h1>Delivery Details<small>Delivery Management</small></h1>

        <ol class="breadcrumb">
            <li><a href="{{ url('/home') }}"><i class="fa fa-dashboard"></i> Home</a></li>
            <li><a href="#">Delivery Management</a></li>
            <li class="active">Warehouse Checklist</li>
        </ol>
    </section>

    <br>

    <section class="content">

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

          <div class="modal modal-danger fade" id="warning-alert">
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
            <div class="col-md-12">
                <div class="box">
                    <div class="box-header">
                        <div class="row">
                            <div class="col-lg-9">                        
                                <h3>Delivery Details</h3>
                            </div>
                            @if($details->delivery_status != 'Pending')
                                @if(strpos($details->DO_No,'DO') !== false)
                            <div class="col-lg-3">
                                <a href="{{url('deliveryorder')}}/{{$details->Id}}" class="btn btn-sm btn-info" style="float:right;" target="_blank">
                                    Print DO 
                                </a>
                            </div>
                                @else
                            <div class="col-lg-3">
                                <a href="{{url('returnnote')}}/{{$details->Id}}" class="btn btn-sm btn-info" style="float:right;" target="_blank">
                                    Print RN 
                                </a>
                            </div> 
                                @endif
                            @endif
                        </div>
                    </div>
                    <div class="box-body">
                        <!-- <form enctype="multipart/form-data" id="assigntime" role="form" method="POST" action="" > -->
                            <div class="row">
                                <div class="col-sm-4">
                                    <h4>Trip Details</h4>
                                    <div class="form-group">
                                        <label>DO No: </label> {{$details->DO_No}}<br>
                                        <label class="control-label">Requestor Name: </label> {{$details->requestorName}}<br>
                                        <label class="control-label">Location: </label> {{$details->Location_Name}}<br>
                                        <label>Lat/Long: </label> {{$details->Latitude}} , {{$details->Longitude}}<br>
                                        <label class="control-label">Remarks: </label> {{$details->Remarks}}<br>
                                    </div>
                                </div>
                                <div class="col-sm-4">
                                    <h4>Driver Details</h4>
                                    <div class="form-group">
                                        <label class="control-label">Driver Name: </label> {{$details->driverName}}<br>
                                        <label class="control-label">Vehicle No: </label> {{$details->Vehicle_No}}<br>
                                        <label class="control-label">Lorry Size: </label> {{$details->Lorry_Size}}<br>
                                    </div>
                                </div>
                                <div class="col-sm-4">
                                    <h4>Pick Up Details</h4>
                                    <div class="form-group">
                                        <label class="control-label">Delivery Date: </label> {{$details->delivery_date}}<br>
                                        <label class="control-label">Delivery Time: </label> {{$details->delivery_time}}<br>
                                        <label class="control-label">Pick Up: </label> <span id="assignPickUp">{{$details->pick_up_time == null? "" :$details->pick_up_time }}</span>
                                        <!-- @if($details->delivery_status == "Processing" && $details->delivery_status_details == "Accepted by Admin")
                                        <a class="btn btn-success btn-xs" id='assignTime' onclick="assignTime()">Assign</a>
                                         <span id="assignPickUp">
                                            <input type="time" class="form-control" id="time" name="time" required="" >
                                        </span>
                                        @endif  -->
                                        <input type='hidden' value="{{$details->Id}}" name="formid"/>
                                    </div>
                                </div>
                            </div>
                            <!-- @if($details->delivery_status == "Processing" && $details->delivery_status_details == "Accepted by Admin")
                            <div class="row">
                                <div class="col-sm-8"></div>
                                <div class="col-sm-4">
                                    <span id="pending_update"></span>
                                    <button style="float:right;" class="btn btn-sm btn-primary" id='saveBtn'>Save</button>
                                </div>
                            </div>
                            @endif -->
                        <!-- </form> -->

                        <div class="box-header with-border"></div>
                        <div class="row">
                            <label class="control-label col-sm-2">Delivery Items: </label>
                        </div>

                        <div class="row">
                            <div class="col-sm-12 col-lg-12">
                                <form method="POST" id="check_form" enctype="multipart/form-data">
                                    <table id="deliveryTable" class="deliveryTable" cellspacing="0" width="100%" padding="30px" style="font-size: 13px;">
                                       <thead>
                                    {{-- prepare header search textbox --}}
                                      <tr>
                                        @foreach($items as $key=>$value)

                                          @if ($key==0)
                                              <td></td>

                                            @foreach($value as $field=>$value)
                                                <td/>{{ $field }}</td>
                                            @endforeach
                                            <!-- <td></td> -->

                                          @endif

                                        @endforeach
                                      </tr>
                                  </thead>
                                  <tbody>
                                    @if(count($items))

                                    <?php $i = 0; ?>
                                    @foreach($items as $mydeliveries)

                                      <tr id="row_{{ $i }}">
                                          <td></td>
                                          @foreach($mydeliveries as $key=>$value)
                                            <td>
                                              {{ $value }}
                                            </td>
                                          @endforeach
                                          <!-- <td></td> -->
                                      </tr>
                                      <?php $i++; ?>
                                    @endforeach

                                    @endif

                                </tbody>
                                <tfoot></tfoot>
                                    </table>
                                    @if($details->delivery_status == "Processing" && $details->delivery_status_details == "Accepted by Admin")
                                    <input type='hidden' value="{{$details->Id}}" name="formid"/>
                                    <input type="hidden" name="total" value="{{$i}}">
                                    @endif
                                    <input type="hidden" name="userid" value="{{$me->UserId}}">
                                </form>
                            </div>
                        </div><!--row-->
                        
                        <br>
                        
                       

                        <br/>   
                        <div class="row">
                            @if($details->delivery_status == "Processing" && $details->delivery_status_details == "Accepted by Admin")
                                <div class="col-sm-2">
                                    <a class="btn btn-sm bg-green" id="accept" onclick="acceptModal()">Accept</a>
                                </div>
                            @endif
                            <div class="col-sm-2">
                                <a class="btn btn-info btn-sm" href="{{url('/warehousechecklist')}}">Back</a>
                            </div>
                        
                        </div>
                    </div>
                </div>
            </div>
        </div><!--row-->

        <div class="modal fade" id="approvalModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                    <h4 class="modal-title" id="myModalLabel">Accept</h4>
                </div>
                <div class="form-group" padding="10px">
                    <label id="approvalMess"></label>
                </div>
                <div class="modal-footer" id="addBtn">
                    <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                </div>
                </div>
            </div>
        </div>

       

        <div class="modal modal-danger fade" id="insufficientModal">
            <div class="modal-dialog">
                <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span></button>
                    <h4 class="modal-title">Not Enough Balance</h4>
                </div>
                <div class="modal-body">
                <ul>Not Enough Balance, Are you sure you want to proceed?</ul>
                </div>
                <div class="modal-footer">
                    <button class="btn btn-warning" onclick="accept()")">Accept</button>
                    <button type="button" class="btn btn-outline" data-dismiss="modal">Close</button>
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

<script type="text/javascript">

    @if($details->delivery_status == "Processing" && $details->delivery_status_details == "Accepted by Admin")
    var arr=[];
    var checkAvailable=true;
    var total=$('input[name="check[]"]').length;
        $('input[name="check[]"]').on('change',function(){
            var count = $('input[name="check[]"]:checked').map(function() {
                return $(this).val();
            }).get(); 
           
            if(count.length != total)
                checkAvailable=true;
            else if(count.length == total)
                checkAvailable=false;
            console.log(checkAvailable)
        });
        

    function assignTime()
    {
        $('#assignPickUp').append('<span><input type="time" class="form-control" id="time" name="time" required="" ></span>');

        
        $('#assignTime').remove();
    }
   
    $("#saveBtn").click(function() {
        var time = $("#time").val();
        var formid = {{$details->Id}}

        if (time == null || time == undefined || time == "") {
            return;
        }

        $.ajaxSetup({
            headers: { 'X-CSRF-Token' : $('meta[name=_token]').attr('content') }
        });

        $.ajax({
            type:'POST',
            url:"{{ url('/savePickUp')}}",
            data:{ time:time, formid:formid },
            success:function(result) {
                if(result == 1)
                {
                    var message="Pick up time have been updated.";
                    $("#update-alert ul").html(message);
                    $("#update-alert").modal('show');
                    setTimeout(function(){
                        $("update-alert").modal('hide');
                        window.location.reload();
                    }, 6000);
                }
                else
                {
                    var message="Failed to update pick up time";
                    $("#warning-alert ul").html(message);
                    $("#warning-alert").modal('show');
                    setTimeout(function(){
                        $("warning-alert").modal('hide');
                        window.location.reload();
                    }, 6000);
                }
            }
        });

    });
       

    function acceptModal()
    {
        if(checkAvailable==true)
        {  
            $('#approvalMess').empty();
            $('#removeBtn').remove();
            $("#addBtn > .btn").after('<button class="btn btn-warning" id="removeBtn" onclick="accept()")">Accept</button>');
            $( "#approvalMess" ).html("&nbsp;&nbsp;&nbsp; Are you sure you want to accept?");
            $('#approvalModal').modal('show');
        }
        else
        {

            $('#insufficientModal').modal('show');
        }
    }

    function accept()
    {
        $('#accept').attr('disabled',true);
        if(checkAvailable==true)
            $('#approvalModal').modal('hide');
        else
            $('#insufficientModal').modal('hide');  
        $.ajaxSetup({
            headers: { 'X-CSRF-Token' : $('meta[name=_token]').attr('content') }
        });
        var fd = new FormData($("#check_form")[0]);
        fd.delete('check[]');
        $.each($(deliveryTable.rows().nodes()).find(':input:checked'), function(){            
                fd.append('check[]', $(this).val());
        });
        $.ajax({
            url:"{{ url('/warehouseaccept')}}",
            method:"post",
            contentType: false,
            processData: false,
            data:fd,
            success:function(result){
                if(result == 1)
                {
                    $('#accept').attr('disabled',false);
                    var message="Details has been updated.";
                    $("#update-alert ul").html(message);
                    $("#update-alert").modal('show');
                    setTimeout(function(){
                        $("update-alert").modal('hide');
                        window.location.reload();
                    }, 6000);
                }
            
            }
        });
    }
    
//     ("input[type='checkbox']").on("change",(function() {
//     if(this.checked) {
//         console.log("functionning")
//     }
// });

    $(document).ready(function() {
    $(document).on('change',':input[type="checkbox"]', function() {
        if(this.checked) {
            var rowid = $(this).val();
            console.log(rowid)
            $.ajaxSetup({
            headers: { 'X-CSRF-Token' : $('meta[name=_token]').attr('content') }
        });
        $.ajax({
            url:"{{ url('/approvestock')}}" + "/" + rowid,
            method:"get",
            contentType: false,
            processData: false,
            // data: {id:rowid},
            success:function(result){
                if(result == 1)
                {
                    
                     var Row = document.getElementById(rowid);
                     var Cells = Row.getElementsByTagName("td");
                     Cells[8].innerText= Cells[7].innerText;
                }
                else
                {
                    alert('failed');
                }
            
            }
        });
            // var approve = $(this).find("td").html();
    }
    else
    {
        checkAvailable=false;
    }
            var count = $('input[type="checkbox"]');
            if(count.length == count.filter(":checked").length)
            {
                checkAvailable=true;
                // alert('all checked');
            }
            else
            {
                checkAvailable=false;
                // alert('not yet');
            }
            console.log(checkAvailable)
            // console.log(request,approve)
    
    });
});
    
@endif
</script>
@endsection
