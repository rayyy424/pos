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

.table-bordered>thead>tr>th, .table-bordered>tbody>tr>th, .table-bordered>tfoot>tr>th, .table-bordered>thead>tr>td, .table-bordered>tbody>tr>td, .table-bordered>tfoot>tr>td {
    border: 1px solid black;
}
      .tableheader{
        background-color: gray;
      }

      .interntable{
        text-align: center;
      }

      .border{
        border: 1px solid;
        padding: 5px;
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

      <script type="text/javascript" language="javascript" src="{{ asset('/plugin/js/dataTables.editor.min.js') }}"></script>
      {{-- chart js --}}
      <script type="text/javascript" language="javascript" src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.4.0/Chart.bundle.min.js"></script>

      <script type="text/javascript" language="javascript" class="init">

      $(document).ready(function() {
          var $total = $('#Sum_Meal'),
              $value = $('#value');
          $value.on('input', function (e) {
              var total = 1;
              $value.each(function (index, elem) {
                  if (!Number.isNaN(parseInt(this.value, 10))) total = total * parseInt(this.value, 10);
              });
              $total.val(total);
          });

          $("#ajaxloader").hide();
          $("#ajaxloader2").hide();
      });



      </script>

      <script type="text/javascript">
        var editor;
                 var loantable;

                 $(document).ready(function() {

                   editor = new $.fn.dataTable.Editor( {
                           ajax: {
                              "url": "{{ asset('/Include/staffloaninstallments.php') }}"
                            },
                           table: "#loan",
                           idSrc: "staffloaninstallments.Id",
                           formOptions: {
                                bubble: {
                                    submit: 'allIfChanged'
                                }
                            },
                           fields: [
                                   {
                                              label: "StaffLoanId:",
                                              name: "staffloaninstallments.StaffLoanId",
                                              type: "hidden",
                                              def: "{{ $staffloanid }}"

                                   },{
                                             label: "Amount:",
                                             name: "staffloaninstallments.Amount",
                                             attr: {
                                                type: "number"
                                              }
                                  },{
                                            label: "Status:",
                                            name: "staffloaninstallments.Paid",
                                            type:  "select",
                                            options: [
                                                { label: "Pending", value: "0" },
                                                { label: "Paid", value: "1" },
                                            ]
                                 },{
                                             label: "Payment_Date:",
                                             name: "staffloaninstallments.Payment_Date",
                                             type:   'datetime',
                                             format: 'DD-MMM-YYYY',
                                             attr: {
                                              autocomplete: "off"
                                             }

                                 },{
                                             label: "created_at:",
                                             name: "staffloaninstallments.created_at",
                                             type: "hidden",
                                             def: "{{ date("Y-m-d H:i:s") }}"

                                  }

                           ]
                   } );


                   $('#loan').on( 'click', 'tbody td:not(:first-child)', function (e) {
                         editor.inline( this, {
                           onBlur: 'submit',
                           submit: 'allIfChanged'
                       } );
                   } );

                       loantable = $('#loan').dataTable( {
                         ajax: {
                            "url": "{{ asset('/Include/staffloaninstallments.php') }}",
                            "data": {
                              "Id": "{{ $staffloanid }}"
                            }
                          },
                          rowId:"staffloaninstallments.Id",
                           dom: "Blrtip",
                           bAutoWidth: true,
                          //  sScrollY: "100%",
                          //  sScrollX: "100%",
                           columnDefs: [{ "visible": false, "targets": [1] },{"className": "dt-center", "targets": "_all"}],
                           bScrollCollapse: true,
                           fnInitComplete: function(oSettings, json) {

                            var totalloan=0.0;
                             var totalpayback=0.0;
                             var totalbalance=0.0;

                              loantable.api().rows().every( function () {
                                 var d = this.data();
                                 totalloan={{$staffloan[0]->Total_Approved ? $staffloan[0]->Total_Approved : 0}};
                                  if (d.staffloaninstallments.Paid == 1) {

                                    totalpayback=totalpayback+parseFloat(d.staffloaninstallments.Amount);
                                  }

                               } );

                              totalbalance=totalloan-totalpayback;

                             $("#totalpayback").html("RM" + totalpayback.toFixed(2));
                             $("#totalbalance").html("RM" + totalbalance.toFixed(2));


                            },
                           columns: [
                             {data: null, "render":"", title:"No"},
                             {data:'staffloaninstallments.Id', title:"Id"},
                             {data:'staffloaninstallments.Payment_Date', title:"Repayment_Date"},
                             {data:'staffloaninstallments.Amount', title:"Amount"},
                             {data:'staffloaninstallments.Paid', title:"Status", render: function (val, type, row) {
                                return val == 0 ? "Pending" : "Paid";
                             }},
                             {data:'staffloaninstallments.created_at', title:'Created_At'},
                             {data:'staffloaninstallments.updated_at', title:'Updated_At'}

                           ],
                           autoFill: {
                              editor:  editor
                          },
                           select: {
                                   style:    'os',
                                   selector: 'tr'
                           },
                           buttons: [
                                   // {
                                   //   text: 'New Record',
                                   //   action: function ( e, dt, node, config ) {
                                   //       // clearing all select/input options
                                   //       editor
                                   //          .create( false )
                                   //          .set( 'staffloaninstallments.StaffLoanId', {{ $staffloanid }} )
                                   //          .set( 'staffloaninstallments.created_at', "{{ date("Y-m-d H:i:s") }}" )
                                   //          .submit();
                                   //   },
                                   // },
                                   { extend: "create", text: "New Record" ,editor: editor },
                                   { extend: "remove", editor: editor },
                                   {
                                           extend: 'collection',
                                           text: 'Export',
                                           buttons: [
                                                   'excel',
                                                   'csv'
                                           ]
                                   }
                           ],

                       });

                       editor.on( 'postEdit', function ( e, json, data ) {

                         var totalloan=0.0;
                          var totalpayback=0.0;
                          var totalbalance=0.0;

                           loantable.api().rows().every( function () {
                              var d = this.data();
                              totalloan={{$staffloan[0]->Total_Approved ? $staffloan[0]->Total_Approved : 0}};
                               if (d.staffloaninstallments.Paid == 1) {


                                 totalpayback=totalpayback+parseFloat(d.staffloaninstallments.Amount);
                               }

                            } );

                           totalbalance=totalloan-totalpayback;

                          $("#totalpayback").html("RM" + totalpayback.toFixed(2));
                          $("#totalbalance").html("RM" + totalbalance.toFixed(2));

                       } );

                       editor.on( 'postCreate', function ( e, json, data ) {

                         var totalloan=0.0;
                          var totalpayback=0.0;
                          var totalbalance=0.0;

                           loantable.api().rows().every( function () {
                              var d = this.data();
                              totalloan={{$staffloan[0]->Total_Approved ? $staffloan[0]->Total_Approved : 0}};
                               if (d.staffloaninstallments.Paid == 1) {


                                 totalpayback=totalpayback+parseFloat(d.staffloaninstallments.Amount);
                               }

                            } );

                           totalbalance=totalloan-totalpayback;

                          $("#totalpayback").html("RM" + totalpayback.toFixed(2));
                          $("#totalbalance").html("RM" + totalbalance.toFixed(2));

                       } );



                       loantable.api().on( 'order.dt search.dt', function () {
                           loantable.api().column(0, {search:'applied', order:'applied'}).nodes().each( function (cell, i) {
                               cell.innerHTML = i+1;
                           } );
                       } ).draw();


                       $(".loan thead input").keyup ( function () {

                               /* Filter on the column (the index) of this element */
                               if ($('#loan').length > 0)
                               {

                                   var colnum=document.getElementById('loan').rows[0].cells.length;

                                   if (this.value=="[empty]")
                                   {

                                      loantable.fnFilter( '^$', this.name,true,false );
                                   }
                                   else if (this.value=="[nonempty]")
                                   {

                                      loantable.fnFilter( '^(?=\\s*\\S).*$', this.name,true,false );
                                   }
                                   else if (this.value.startsWith("!")==true && this.value.length>1)
                                   {

                                      loantable.fnFilter( '^(?'+ this.value +').*', this.name,true,false );
                                   }
                                   else if (this.value.startsWith("!")==false)
                                   {

                                       loantable.fnFilter( this.value, this.name,true,false );
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
      <h1>
      StaffLoan Detail
      <small>StaffLoan Management</small>
      </h1>

      <ol class="breadcrumb">
      <li><a href="{{ url('/home') }}"><i class="fa fa-dashboard"></i> Home</a></li>
      <li><a href="#">Resource Management</a></li>
      <li><a href="#">StaffLoan Management</a></li>
      <li class="active">StaffLoan Detail</li>
      </ol>
    </section>

    <section class="content">

      <div class="row">

        <div class="modal fade" id="Submit" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
          <div class="modal-dialog" role="document">
            <div class="modal-content">
              <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title" id="myModalLabel">Submit and Notify</h4>
              </div>
              <div class="modal-body">
                  Are you sure you wish to submit staffloan for next action?
              </div>
              <div class="modal-footer">
                <center><img src="{{ URL::to('/') ."/img/ajax-loader.gif" }}" width="50px" height="50px" alt="Loading" name='ajaxloader' id="ajaxloader"></center>
                <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                <button type="button" class="btn btn-primary" onclick="submit({{$staffloanid}})">Yes</button>
              </div>
            </div>
          </div>
        </div>

        <div class="modal fade" id="ExportPDF" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
         <div class="modal-dialog">
          <div class="modal-content">
            <div class="modal-header">

              <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
              <h4 class="modal-title" id="myModalLabel">Export</h4>

            </div>

            <div class="modal-body">
                Are you sure you wish to export this staffloan request?
            </div>
            <div class="modal-footer">
              <a class="btn btn-primary btn-lg" href="{{ url('/exportstaffloan') }}/{{$staffloanid}}" target="_blank">Export</a>
              {{-- <a class="btn btn-primary btn-lg" href="{{ url('/excelClaim') }}/{{$myclaim[0]->Id}}/{{$me->UserId}}/{{ $myclaim[0]->Claim_Sheet_Name }}/{{ $myclaim[0]->Claim_Sheet_Name }}" target="_blank">Excel</a> --}}

            </div>
          </div>
        </div>
      </div>

        {{-- <div class="modal fade" id="Submit" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
          <div class="modal-dialog" role="document">
            <div class="modal-content">
              <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title" id="myModalLabel">Submit and Notify</h4>
              </div>
              <div class="modal-body">
                  Are you sure you wish to submit the staffloan for next action?
              </div>
              <div class="modal-footer">
                <center><img src="{{ URL::to('/') ."/img/ajax-loader.gif" }}" width="50px" height="50px" alt="Loading" name='ajaxloader' id="ajaxloader"></center>
                <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                <button type="button" class="btn btn-primary" onclick="submit({{ $staffloanid }})">Yes</button>
              </div>
            </div>
          </div>
        </div> --}}

        <div class="modal fade" id="Redirect" role="dialog" aria-labelledby="myModalLabel">
          <div class="modal-dialog" role="document">
            <div class="modal-content">
              <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title" id="myModalLabel">Redirect</h4>
              </div>
              <div class="modal-body">
                <div class="form-group" id="redirectleavestatus">

                </div>
                <div class="form-group">

                    <label>Approver : </label>

                    <select class="form-control select2" id="NewApprover" name="NewApprover" style="width: 100%;">

                      @if ($approver)
                        @foreach ($approver as $app)

                            <option  value="{{$app->Id}}">{{$app->Name}}</option>

                        @endforeach

                      @endif

                      </select>

                </div>
              </div>
              <div class="modal-footer">
                <center><img src="{{ URL::to('/') ."/img/ajax-loader.gif" }}" width="50px" height="50px" alt="Loading" name='ajaxloader2' id="ajaxloader2"></center>
                <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                <button type="button" class="btn btn-primary" onclick="redirect()">Redirect</button>
              </div>
            </div>
          </div>
        </div>

        <div class="modal modal-danger fade" id="error-alert">
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

        <div class="modal modal-success fade" id="update-alert">
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
        <div class="col-md-12">
          <div class="box box-info">
            <div class="box-body">

              <form enctype="multipart/form-data" id="upload_form" role="form" method="POST" action="" >

                <h3>EMPLOYEE LOAN FORM</h3>
                @foreach ($staffloan as $staffloans)
                <div class="box-body">

                    <h4>Staff Details</h4>

                    <input type="hidden" name="UserId" value="{{ $me->UserId }}">

                    <div class="row">
                        <div class="col-md-6">
                          <div class="col-md-3">
                            <label>Name</label>
                          </div>
                          <div class="col-md-9">
                            : {{$staffloans->Name}}
                          </div>
                        </div> 

                        <div class="col-md-6">
                          <div class="col-md-3">
                            <label>Bank Name</label>
                          </div>
                          <div class="col-md-9">
                            : {{$staffloans->Bank_Name == "" ? "-" : $staffloans->Bank_Name}}
                          </div>
                        </div>

                        <div class="col-md-6">
                          <div class="col-md-3">
                            <label>Bank Account</label>
                          </div>
                          <div class="col-md-9">
                            : {{$staffloans->Bank_Account_No == "" ? "-" : $staffloans->Bank_Account_No}}
                          </div>
                        </div> 

                        <div class="col-md-6">
                          <div class="col-md-3">
                            <label> Position </label>
                          </div>
                          <div class="col-md-9">
                            : {{$staffloans->Position=="" ? "-" : $staffloans->Position}}
                          </div>
                        </div>

                        <div class="col-md-6">
                          <div class="col-md-3">
                            <label>Account Holder Name</label>
                          </div>
                          <div class="col-md-9">
                            : {{$staffloans->Acc_Holder_Name == "" ? "-" : $staffloans->Acc_Holder_Name}}
                          </div>
                        </div>

                         <div class="col-md-6">
                          <div class="col-md-3">
                            <label> Date </label>
                          </div>
                          <div class="col-md-9">
                            : {{$staffloans->Date}}
                          </div>
                         </div>

                         <div class="col-md-6">
                          <div class="col-md-3">
                            <label> Repayment times</label>
                          </div>
                          <div class="col-md-9">
                            : {{$staffloans->Repayment}}
                          </div>
                         </div>

                         <div class="col-md-6">
                          <div class="col-md-3">
                            <label> Purpose</label>
                          </div>
                          <div class="col-md-9">
                            : {{$staffloans->Purpose}}
                          </div>
                         </div>

                         <div class="col-md-6">
                          <div class="col-md-3">
                            <label> Status</label>
                          </div>
                          <div class="col-md-9">
                            : {{$staffloan[0]->Status}}
                          </div>
                         </div>

                         <div class="col-md-6">
                          <div class="col-md-3">
                            <label> Approver</label>
                          </div>
                          <div class="col-md-9">
                            : {{$staffloan[0]->Approver}}
                          </div>
                         </div>

                        <div class="col-md-6">
                          <div class="col-md-3">
                            <!-- <label> Bank In</label>
                              </div>
                              <div class="col-md-9">
                                : {{$staffloan[0]->PaidStatus}}
                            </div> -->


                            <label> Bank In</label>
                          </div>
                          <div class="col-md-5">
                            : 
                            <select class="form-control select2" id="BankIn" name="BankIn" style="width: 70%;">
                              <option></option>
                              <option value="Paid" <?php if($staffloan[0]->PaidStatus == "Paid") echo "selected" ?> >Paid</option>
                              <option value="Unpaid" <?php if($staffloan[0]->PaidStatus == "Unpaid") echo "selected" ?> >Unpaid</option>
                            </select>
                         </div>
                        </div>

                    </div>



                  <div class="row">
                    <div class="form-group">
                      <div class="col-md-4">

                      <h4>Loan Required</h4>
                        <table class="table table-bordered">
                           <tr>
                             <th>Purpose</th>
                             <th style="width:100px;">Total (RM)</th>
                           </tr>


                          <?php $i = 0; ?>
                           @foreach($staffloandetails as $staffloandetail)

                               <tr id="row_{{ $i }}">
                                   <td style="text-align:right">{{$staffloandetail->Type}}</td>
                                   <td>{{$staffloandetail->Total}}</td>
                               </tr>


                             <?php $i++; ?>

                           @endforeach

                           <tr>
                             <td style="text-align:right">Total StaffLoan Requested : </td>
                             <td><input type="text" class="form-control" id="" name="" value="{{$staffloans->Total_Requested}}" disabled=""></td>
                           </tr>


                           <tr>
                             <td style="text-align:right">Total StaffLoan Approved : </td>
                               <input type="hidden" class="form-control" id="StaffLoanId" name="StaffLoanId" value="{{$staffloanid}}">
                             <td><input type="number" class="form-control" id="Total_Approved" name="Total_Approved" value="{{$staffloans->Total_Approved}}"></td>
                           </tr>

                         </table>
                      </div>

                      <div class="col-md-4">
                        <h4>Repayment per month</h4>

                        <table class="table table-bordered">
                           <tr>
                             <th>Repayment</th>
                             <th style="width:100px;">Total (RM)</th>
                           </tr>

                          <!-- Month 1 -->
                           <tr>
                             <td style="text-align:right">Month 1 : </td>
                               <input type="hidden" class="form-control" id="StaffLoanId" name="StaffLoanId" value="{{$staffloanid}}">
                             <td><input type="number" class="form-control" id="Repayment_1" name="Repayment_1" value="{{$staffloans->Repayment_1}}"></td>
                           </tr>

                           <!-- Month 2 -->
                            <tr>
                              <td style="text-align:right">Month 2 : </td>

                              <td><input type="number" class="form-control" id="Repayment_2" name="Repayment_2" value="{{$staffloans->Repayment_2}}"></td>
                            </tr>

                            <!-- Month 3 -->
                             <tr>
                               <td style="text-align:right">Month 3 : </td>

                               <td><input type="number" class="form-control" id="Repayment_3" name="Repayment_3" value="{{$staffloans->Repayment_3}}"></td>
                             </tr>

                             <!-- Month 4 -->
                              <tr>
                                <td style="text-align:right">Month 4 : </td>

                                <td><input type="number" class="form-control" id="Repayment_4" name="Repayment_4" value="{{$staffloans->Repayment_4}}"></td>
                              </tr>

                         </table>
                      </div>

                     </form>

                    <div class="row">
                      <div class="col-md-4">
                        <h4>Attachment [Image/PDF] </h4>
                        <div class="form-group">
                          <form enctype="multipart/form-data" id="upload_form2" role="form" method="POST" action="" >
                            <input type="hidden" class="form-control" id="StaffLoanId" name="StaffLoanId" value="{{$staffloan[0]->Id}}">
                            <input type="file" id="receipt[]" name="receipt[]" accept=".png,.jpg,.jpeg,.pdf" multiple>

                          </form>
                        </div>
                          <button type="button" class="btn btn-primary btn-xs" onclick="uploadreceipt()">Upload</button>
                      </div>
                    </div>

                </div>
              </div>

                @endforeach

              <div class="col-md-12">


                      <div id="receiptdiv">


                        @foreach ($myattachment as $attachment)

                          @if(strpos($attachment->Web_Path,'.png') !== false || strpos($attachment->Web_Path,'.jpg') !== false || strpos($attachment->Web_Path,'.jpeg') !== false ||strpos($attachment->Web_Path,'.PNG') !== false || strpos($attachment->Web_Path,'.JPG') !== false || strpos($attachment->Web_Path,'.JPEG') !== false)
                            <div class="col-md-4">
                              <div class="" id="receipt{{ $attachment->Id }}">
                                  <img class="" src="{{ url($attachment->Web_Path) }}" width="100%"  alt="Photo">
                                <button type="button" class="btn btn-block btn-danger btn-xs" onclick="deletereceipt({{$attachment->Id }})">Delete</button>
                              </div>
                            </div>

                          @else
                            <div class="col-md-4">
                              <div class="" id="receipt{{ $attachment->Id }}">
                                <!-- <span class="zoom"> -->
                                  {{ $attachment->File_Name}}
                                <!-- </span> -->
                                <button type="button" class="btn btn-block btn-danger btn-xs" onclick="deletereceipt({{$attachment->Id }})">Delete</button>
                              </div>
                            </div>

                          @endif

                        @endforeach

                      </div>

              </div>

              </div>

              <div class="box-footer">

              <div class="row">
                <div class="col-md-12">
                    <div class="col-lg-4">
                        <label>Final Approver : </label>
                        <select class="form-control select2" id="NewApprover" name="NewApprover" style="width: 70%;" <?php if( strpos($staffloan[0]->Status,"Final Approved") !== false) echo "disabled";  ?>>
                        <option></option>
                        @foreach ($approver as $user)
                            <option value="{{$user->Id}}" <?php if($user->Id == $staffloan[0]->approverId && (strpos($staffloan[0]->Status,'Approved') !== false) ) echo "selected"; ?> >{{$user->Name}}</option>
                        @endforeach
                        </select>
                  </div>
                @if( $staffloan[0]->Status !== "Cancelled" && $staffloan[0]->Status !== "Rejected" && $staffloan[0]->Status !== "Final Approved" && $staffloan[0]->Status !== "Recall")
                <button type="submit" class="btn btn-success btn-xs" onclick="approve()">Approve</button>
                <button type="submit" class="btn btn-danger btn-xs" onclick="reject('{{$staffloanid}}')">Reject</button>
                <button type="submit" class="btn btn-warning btn-xs" data-toggle="modal" data-target="#Redirect">Redirect</button>
                @endif
                <button type="button" class="btn btn-success btn-xs" data-toggle="modal" data-target="#ExportPDF">Export</button>
                </div>
              </div>

              </div>

            </div>
          </div>

          <div class="col-md-12">
            <div class="box box-info">
              <div class="box-body">
                <div class="row">
                  <div class="col-md-6">
                    <h3>EMPLOYEE LOAN INSTALLMENT RECORD</h3>
                    <ul class="list-group list-group-unbordered">
                      <li class="list-group-item">
                        <b>Name</b> : <p class="pull-right"><i>{{ $staffloan[0]->Name }}</i>
                      </li>

                      <li class="list-group-item">
                        <b>Total Loan</b> : <p class="pull-right"><i>RM{{ $staffloan[0]->Total_Approved ? $staffloan[0]->Total_Approved : '0.00' }}</i>
                      </li>


                      <li class="list-group-item">
                        <b>Total Pay Back</b> : <p class="pull-right"><i><span id='totalpayback'>RM0.00</span></i></p>
                      </li>

                      <li class="list-group-item">
                        <b>Outstanding Balance</b> : <p class="pull-right"><i><span id='totalbalance'>RM0.00</span></i></p>
                      </li>

                    </ul>


                  </div>
                </div>

                <div class="row">
                  <div class="col-md-12">
                    <table id="loan" class="loan" cellspacing="0" width="100%" padding="30px" style="font-size: 13px;">
                      <thead>
                        <tr class="search">
                          @foreach($installments as $key=>$value)
                            @if ($key==0)
                              <?php $i = 0; ?>
                              @foreach($value as $field=>$a)
                                  @if ($i==0|| $i==1)
                                    <td align='center'><input type='hidden' class='search_init' name='{{$i}}' placemark='{{$a}}'></td>
                                  @else
                                    <td align='center'><input type='text' class='search_init' name='{{$i}}'  placemark='{{$a}}'></td>
                                  @endif
                                  <?php $i ++; ?>
                              @endforeach
                            @endif
                          @endforeach
                        </tr>
                        <tr>

                        @foreach($installments as $key=>$value)

                          @if ($key==0)

                            @foreach($value as $field=>$value)
                              <td/>{{ $field }}</td>
                            @endforeach

                          @endif

                        @endforeach
                        </tr>
                      </thead>
                      <tbody>

                      </tbody>
                      <tfoot></tfoot>
                    </table>
                  </div>
                </div>

              </div>
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
  <strong>Copyright &copy; 2014-2016 <a href="http://www.softoya.com">TrackerOnTheGo</a>.</strong> All rights
  reserved.
</footer>

<script>

$(function () {

  //Initialize Select2 Elements
  $(".select2").select2();

  $('#Total_Approved').change(function() {
     //do stuff
     $('#Total_Approved').val(parseFloat($('#Total_Approved').val()).toFixed(2));

  });

  $('#Repayment_1').change(function() {
     //do stuff
     $('#Repayment_1').val(parseFloat($('#Repayment_1').val()).toFixed(2));

  });

  $('#Repayment_2').change(function() {
     //do stuff
     $('#Repayment_2').val(parseFloat($('#Repayment_2').val()).toFixed(2));

  });

  $('#Repayment_3').change(function() {
     //do stuff
     $('#Repayment_3').val(parseFloat($('#Repayment_3').val()).toFixed(2));

  });

  $('#Repayment_4').change(function() {
     //do stuff
     $('#Repayment_4').val(parseFloat($('#Repayment_4').val()).toFixed(2));

  });



  //Flat red color scheme for iCheck
  $('input[type="checkbox"].flat-green, input[type="radio"].flat-green').iCheck({
    checkboxClass: 'icheckbox_flat-green',
    radioClass: 'iradio_flat-green'
  });

  //Date picker
  $('#Start_Date').datepicker({
    autoclose: true,
      format: 'dd-M-yyyy',
  });

  $('#End_Date').datepicker({
    autoclose: true,
      format: 'dd-M-yyyy',
  });

  $('#Total_Approved').keyup(function(){
  // alert('aaa');
  var total_approve =  $('#Total_Approved').val();
  // document.getElementsByName('Total_Approved');
  // var repay = document.getElementsByName('Repayment');
  var repay = {{$staffloans->Repayment}}

  var repay1 =  $('#Repayment_1').val();
  // document.getElementsByName('Repayment_1');
  var repay2 =  $('#Repayment_2').val();
  // document.getElementsByName('Repayment_2');
  var repay3 =  $('#Repayment_3').val();
  // document.getElementsByName('Repayment_3');
  var repay4 =  $('#Repayment_4').val();
  // document.getElementsByName('Repayment_4');
  // for (var i = 0; i < repay.length; i++) {

  repay1 = parseInt(total_approve) / parseInt(repay);

  for (var i = 1; i <= {{$staffloans->Repayment}}; i++) {
    $("#Repayment_"+i).val((repay1).toFixed(2));
  }


  // }
});


});




// $(document).keyup(function(event) {
//   alert('You have released a key');
//  });


function submit(staffloanid) {

    $.ajaxSetup({
       headers: { 'X-CSRF-Token' : $('meta[name=_token]').attr('content') }
    });

      // $("#ajaxloader").show();
    $.ajax({
                url: "{{ url('/staffloan/submit') }}",
                method: "POST",
                  data: {StaffLoanId:staffloanid},

                success: function(response){

                  if (response==1)
                  {

                      var message="Submitted for next action!";
                      $("#update-alert ul").html(message);
                      $("#update-alert").modal('show');

                      $('#Submit').modal('hide');

                      $('#submitbtn').show();
                      $('#recallbtn').show();

                      // editor.disable();
                      // claimtable.api().autoFill().disable();
                      $('#status').html("Submitted for Next Approval");

                      $("#ajaxloader").hide();

                  }
                  else {

                    var errormessage="Failed to submit for next action!";
                    $("#error-alert ul").html(errormessage);
                    $("#error-alert").modal('show');

                    $('#Submit').modal('hide');

                    $("#ajaxloader").hide();

                  }

        }
    });

  }


function approve() {

    $.ajaxSetup({
       headers: { 'X-CSRF-Token' : $('meta[name=_token]').attr('content') }
    });

    $("#ajaxloader").show();

    var totalapproved=$('#Total_Approved').val();
    var staffloanid={{$staffloanid}};
    var Repayment_1=$('#Repayment_1').val();
    var Repayment_2=$('#Repayment_2').val();
    var Repayment_3=$('#Repayment_3').val();
    var Repayment_4=$('#Repayment_4').val();
    var approver=$('#NewApprover').val();

    status="Approved";


    @if($mylevel)
      @if ($mylevel->Level=="1st Approval")
        status="1st Approved";
      @endif

      @if ($mylevel->Level=="2nd Approval")
        status="2nd Approved";
      @endif

      @if ($mylevel->Level=="3rd Approval")
        status="3rd Approved";
      @endif

      @if ($mylevel->Level=="4th Approval")
        status="4th Approved";
      @endif

      @if ($mylevel->Level=="5th Approval")
        status="5th Approved";
      @endif

      @if ($mylevel->Level=="Final Approval")
        status="Final Approved";
      @endif

  @endif

    $.ajax({
                url: "{{ url('/staffloan/approve') }}",
                method: "POST",
                data:{
                  Status: status,
                  Total_Approved: totalapproved,
                  StaffLoanId : staffloanid,
                  Repayment_1 : Repayment_1,
                  Repayment_2 : Repayment_2,
                  Repayment_3 : Repayment_3,
                  Repayment_4 : Repayment_4,
                  Approver : approver,
                },

                success: function(response){

                  if (response==1)
                  {

                      var message="StaffLoan application approved!";
                      $("#update-alert ul").html(message);
                      $("#update-alert").modal('show');
                      //
                      setTimeout(function() {
                        $("#error-alert").modal('hide');
                        $("#ajaxloader").hide();

                        window.location.reload();
                      }, 6000);




                  }
                  else {
                      var obj = jQuery.parseJSON(response);
                      var errormessage ="";

                      for (var item in obj) {
                        errormessage=errormessage + "<li> " + obj[item] + "</li>";
                      }

                      $("#error-alert ul").html(errormessage);
                      $("#error-alert").modal('show');

                      // setTimeout(function() {
                      //   $("#error-alert").fadeOut();
                      // }, 6000);

                      $("#ajaxloader").hide();

                  }

        }
    });

}

function reject(staffloanid) {

    $.ajaxSetup({
       headers: { 'X-CSRF-Token' : $('meta[name=_token]').attr('content') }
    });

    var Repayment_1=$('#Repayment_1').val();
    var Repayment_2=$('#Repayment_2').val();
    var Repayment_3=$('#Repayment_3').val();
    var Repayment_4=$('#Repayment_4').val();
    var approver=$('#NewApprover').val();
    var status = "Rejected";

    @if($mylevel)
      @if ($mylevel->Level=="1st Approval")
        status="1st Rejected";
      @endif

      @if ($mylevel->Level=="2nd Approval")
        status="2nd Rejected";
      @endif

      @if ($mylevel->Level=="3rd Approval")
        status="3rd Rejected";
      @endif

      @if ($mylevel->Level=="4th Approval")
        status="4th Rejected";
      @endif

      @if ($mylevel->Level=="5th Approval")
        status="5th Rejected";
      @endif

      @if ($mylevel->Level=="Final Approval")
        status="Final Rejected";
      @endif

    @endif

    // $("#ajaxloader").show();

    $.ajax({

                url: "{{ url('/staffloan/reject') }}",
                method: "POST",
                data:{
                  StaffLoanId : staffloanid,
                  Status : status,
                  Approver : approver
                },

                success: function(response){

                  if (response==1)
                  {

                      var message="StaffLoan application rejected!";
                      $("#update-alert ul").html(message);
                      $("#update-alert").modal('show');

                      $("#error-alert").modal('hide');
                      $("#ajaxloader").hide();

                      window.location.reload();


                  }
                  else {
                      var obj = jQuery.parseJSON(response);
                      var errormessage ="";

                      for (var item in obj) {
                        errormessage=errormessage + "<li> " + obj[item] + "</li>";
                      }

                      $("#error-alert ul").html(errormessage);
                      $("#error-alert").modal('show');

                      $("#ajaxloader").hide();

                  }

        }
    });

}

function redirect() {

    $.ajaxSetup({
       headers: { 'X-CSRF-Token' : $('meta[name=_token]').attr('content') }
    });

    // $("#ajaxloader").show();

    newapprover=$('[name="NewApprover"]').val();
    staffloanid = {{$staffloanid}};

    $.ajax({
                url: "{{ url('/staffloan/redirect') }}",
                method: "POST",
                data: {StaffLoanId:staffloanid,Approver:newapprover},

                success: function(response){

                  if (response==1)
                  {
                      var message="Claim redirected!";
                      $("#update-alert ul").html(message);
                      $("#update-alert").modal('show');


                      $('#Redirect').modal('hide');

                      $("#ajaxloader2").hide();

                    window.location.reload();

                  }
                  else {

                    var errormessage="Failed to redirect claim!";
                    $("#error-alert ul").html(errormessage);
                    $("#error-alert").modal('show');

                    $('#Redirect').modal('hide');

                    $("#ajaxloader2").hide();

                  }

        }
    });



}

function deletereceipt(id) {
  $.ajaxSetup({
     headers: { 'X-CSRF-Token' : $('meta[name=_token]').attr('content') }
  });
  $.ajax({
              url: "{{ url('/staffloan/deletereceipt') }}",
              method: "POST",
              data: {Id:id},
              success: function(response){
                if (response==0)
                {
                  var message ="Failed to delete receipt!";
                  $("#warning-alert ul").html(message);
                  $("#warning-alert").modal('show');

                  setTimeout(function() {
                    $("#warning-alert").modal('hide');
                  }, 6000);
                }
                else {
                  var message ="Receipt deleted!";
                  $("#update-alert ul").html(message);
                  $("#update-alert").modal('show');

                  setTimeout(function() {
                    $("#update-alert").modal('hide');
                  }, 6000);
                  //$("#Template").val(response).change();
                  $("#exist-alert").hide();

                  $("#receipt"+id).remove();
                }
      }
  });
}

function uploadreceipt() {
    $.ajaxSetup({
       headers: { 'X-CSRF-Token' : $('meta[name=_token]').attr('content') }
    });
    $.ajax({
                url: "{{ url('/staffloan/uploadreceipt') }}",
                method: "POST",
                contentType: false,
                processData: false,
                data:new FormData($("#upload_form2")[0]),
                success: function(response){
                  if (response==0)
                  {
                    var message ="Failed to upload receipt!";
                    $("#warning-alert ul").html(message);
                    $("#warning-alert").modal('show');

                    setTimeout(function() {
                      $("#warning-alert").modal('hide');
                    }, 6000);
                  }
                  else {
                    var message ="Receipt uploaded!";
                    $("#update-alert ul").html(message);
                    $("#update-alert").modal('show');

                    setTimeout(function() {
                      $("#update-alert").modal('hide');
                    }, 6000);
                    //$("#Template").val(response).change();
                    $("#exist-alert").hide();
                    $("#receipt").val("");

                    var split=response.split(",");
                    for (var i = 0; i < split.length; i++) {

                      if (split[i].toUpperCase().includes(".PNG") ||split[i].toUpperCase().includes(".JPG")||split[i].toUpperCase().includes(".JPEG"))
                      {
                        var sub=split[i].split("|");

                        var html="<div class='col-md-4'>";
                          html+="<div class='' id='receipt"+sub[0]+"'>";
                            html+="<img class='' src='"+sub[1]+"' width='100%'  alt='Photo'>";
                            html+="<button type='button' class='btn btn-block btn-danger btn-xs' onclick='deletereceipt("+sub[0]+")'>Delete</button>";
                          html+="</div>";
                        html+="</div>";

                        $("#receiptdiv").append(html);


                      }
                      else {

                        var sub=split[i].split("|");
                        var html="<div class='col-md-4'>";
                            html+="<div class='' id='receipt"+sub[0]+"'>";
                          //  html+="<a download='"+sub[1]+"' href='"+sub[1]+"' title='Download'>";
                                html+=sub[2];
                              //  html+="</a>";
                                html+="<button type='button' class='btn btn-block btn-danger btn-xs' onclick='deletereceipt("+sub[0]+")'>Delete</button>";
                            html+="</div>";
                        html+="</div>";

                        $("#receiptdiv").append(html);

                      }

                    }
                  }
        }
    });
}

$(document).ready(function() {
    $(document).on('change', '#BankIn', function(e) {
        var staffloanid = "{{$staffloanid}}";
        changepaymentstatus(staffloanid);
    });
});

function changepaymentstatus(staffloanid)
{
  console.log('2')
  $.ajaxSetup({
       headers: { 'X-CSRF-Token' : $('meta[name=_token]').attr('content') }
    });

    var BankIn=$('#BankIn').val();

    $.ajax({
                url: "{{ url('/staffloan/updateBankIn') }}",
                method: "POST",
                data:{
                  StaffLoanId : staffloanid,
                  Status : BankIn,
                  
                },
                success: function(response){
                  if (response==1)
                  {
                    var message="Bank In updated!";
                      $("#update-alert ul").html(message);
                      $("#update-alert").modal('show');
                      //
                      setTimeout(function() {
                        $("#error-alert").modal('hide');
                        $("#ajaxloader").hide();

                        window.location.reload();
                      }, 6000);
                  }
                  else {
                      
                  }
                }
    });
}

</script>



@endsection
