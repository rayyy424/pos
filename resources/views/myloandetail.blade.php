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

      h5{
        border: 1px solid #aba2a2;
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
      });

      </script>

      @endsection

@section('content')

  <div class="content-wrapper">
      <!-- Content Header (Page header) -->
    <section class="content-header">
      <h1>
      My Loan Detail
      <small>My Request</small>
      </h1>

      <ol class="breadcrumb">
      <li><a href="{{ url('/home') }}"><i class="fa fa-dashboard"></i> Home</a></li>
      <li><a href="#">My Workplace</a></li>
      <li><a href="#">My Request</a></li>
      <li class="active">My Loan Detail</li>
      </ol>
    </section>

    <section class="content">

      <div class="row">

        <div class="modal fade" id="ExportPDF" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
         <div class="modal-dialog">
          <div class="modal-content">
            <div class="modal-header">

              <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
              <h4 class="modal-title" id="myModalLabel">Export</h4>

            </div>

            <div class="modal-body">
                Are you sure you wish to export this loan request?
            </div>
            <div class="modal-footer">
              <a class="btn btn-primary btn-lg" href="{{ url('/exportstaffloan') }}/{{$staffloanid}}" target="_blank">Export</a>
              {{-- <a class="btn btn-primary btn-lg" href="{{ url('/excelClaim') }}/{{$myclaim[0]->Id}}/{{$me->UserId}}/{{ $myclaim[0]->Claim_Sheet_Name }}/{{ $myclaim[0]->Claim_Sheet_Name }}" target="_blank">Excel</a> --}}

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
                      <div class="form-group">

                        <div class="col-lg-4">
                          <label>Name : </label>
                            <h5>{{$staffloans->Name}}</h5>
                        </div>


                       <div class="col-lg-4">
                         <label> Bank Account : </label>

                         @if($staffloans->Bank_Account_No=="")
                           <h5>-</h5>

                         @else

                           <h5>{{$staffloans->Bank_Account_No}}</h5>

                         @endif

                       </div>

                     </div>
                  </div>

                  <div class="row">
                    <div class="form-group">

                        <div class="col-lg-4">
                            <label>Project : </label>
                            <h5>{{$staffloans->Project_Name}}</h5>

                        </div>

                        <div class="col-lg-4">
                          <label> Position : </label>
                          @if($staffloans->Position=="")
                            <h5>-</h5>

                          @else

                            <h5>{{$staffloans->Position}}</h5>

                          @endif

                        </div>

                    </div>
                  </div>

                  <br>



                  <br>

                  <h4>Loan Required</h4>

                  <div class="row">
                    <div class="form-group">
                      <div class="col-lg-8">

                        <table class="table table-bordered">
                           <tr>

                             <th>Purpose</th>
                             <th style="width:100px;">Total (RM)</th>

                           </tr>


                          <?php $i = 0; ?>
                           @foreach($staffloandetails as $staffloandetail)


                               <tr id="row_{{ $i }}">
                                   <td>{{$staffloandetail->Type}}</td>
                                   <td>{{$staffloandetail->Total}}</td>

                               </tr>


                             <?php $i++; ?>


                           @endforeach

                           <tr>

                             <td style="text-align:right">Total Loan Requested : </td>
                             <td><input type="text" class="form-control" id="" name="" value="{{$staffloans->Total_Requested}}" disabled=""></td>
                           </tr>


                           <tr>

                             <td style="text-align:right">Total Loan Approved : </td>
                               <input type="hidden" class="form-control" id="StaffLoanId" name="StaffLoanId" value="{{$staffloanid}}">
                             <td><input type="text" class="form-control" id="Total_Approved" name="Total_Approved" value="{{$staffloans->Total_Approved}}" disabled=""></td>
                           </tr>

                         </table>
                      </div>

                    </div>
                  </div>


                </div>

                @endforeach
              </form>

              <div class="col-md-12">

                      <div id="receiptdiv">


                        @foreach ($myattachment as $attachment)

                          @if(strpos($attachment->Web_Path,'.png') !== false || strpos($attachment->Web_Path,'.jpg') !== false || strpos($attachment->Web_Path,'.jpeg') !== false ||strpos($attachment->Web_Path,'.PNG') !== false || strpos($attachment->Web_Path,'.JPG') !== false || strpos($attachment->Web_Path,'.JPEG') !== false)
                            <div class="col-md-4">
                              <div class="" id="receipt{{ $attachment->Id }}">
                                  <img class="" src="{{ url($attachment->Web_Path) }}" width="100%"  alt="Photo">
                                <!-- <button type="button" class="btn btn-block btn-danger btn-xs" onclick="deletereceipt({{$attachment->Id }})">Delete</button> -->
                              </div>
                            </div>

                          @else
                            <div class="col-md-4">
                              <div class="" id="receipt{{ $attachment->Id }}">
                                <!-- <span class="zoom"> -->
                                  {{ $attachment->File_Name}}
                                <!-- </span> -->
                                <!-- <button type="button" class="btn btn-block btn-danger btn-xs" onclick="deletereceipt({{$attachment->Id }})">Delete</button> -->
                              </div>
                            </div>

                          @endif

                        @endforeach

                      </div>

              </div>

              <div class="row">
                <div class="form-group">

                  <div class="col-lg-4">
                      <label>Approver : </label>
                      <h5>{{$staffloan[0]->Approver}}</h5>
                  </div>
                </div>
              </div>

              <div class="row">
                <div class="form-group">

                  <div class="col-lg-4">
                    <label>Status : </label>

                    <h5>{{$staffloan[0]->Status}}</h5>
                  </div>

                </div>
              </div>

              </div>

              <div class="box-footer">
                <button type="button" class="btn btn-success" data-toggle="modal" data-target="#ExportPDF">Export</button>
              </div>

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

});

</script>



@endsection
