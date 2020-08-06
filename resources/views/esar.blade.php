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
    .row{
    padding:10px;
    }
    .CompanyName{
      text-align:center;
      width:100%;
      margin:0;
    }
    .CompanyName h2{
      line-height: 20%;
      padding-top:10px;

    }
    .CompanyName h5{
      text-align:center;
      font-weight:100;
    }

    .CompanyName img{
      padding:10px;
    }

    .row h2,h4{
        padding: 0;
        margin: 0;
        padding-bottom:10px;
    }

    table {
        width:100%;
        font-size:14px;
    }

    table.main{
      border-collapse: collapse;
    }

    table.main, table.main tr{
      border:1px solid black;
      /*padding:5px;*/
    }

    table.main td{
      padding:5px;
    }

    table.main1{
      border-collapse: collapse;
      border:1px solid black;
      padding:5px;
    }

    table.main1 td{
      padding:5px;
    }

    table.main2, table.main2 tr{
      border-collapse: collapse;
      border:1px solid black;
      padding:5px;
    }

    table.main2 td{
      padding:10px;
    }

    .row1{
      padding:10px;
      width:100%;
    }

    h4.pagetitle{
      text-align:center;
      padding-top:5px;
    }

    table#t01 thead tr{
      background-color: grey;
    }

    table#t01 tr:nth-child(even) {
        background-color: #eee;
    }
    table#t01 tr:nth-child(odd) {
       background-color:#fff;
    }
    table#t01 th {
        background-color: #3b3d3e;
        color: white;
        text-align: center;
    }
    table#t01 td {
        text-align: center;
    }
    .profilepic{
        text-align:center;
    }
    .profilepic img{
        width:100px;
        border-radius:50%;
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
      <script type="text/javascript" language="javascript" src="https://cdn.datatables.net/plug-ins/1.10.13/api/sum().js"></script>

      <script type="text/javascript" language="javascript" src="{{ asset('/plugin/js/dataTables.editor.min.js') }}"></script>
      {{-- <script type="text/javascript" language="javascript" src="{{ asset('/plugin/examples/resources/syntax/shCore.js') }}"></script>
      <script type="text/javascript" language="javascript" src="{{ asset('/plugin/examples/resources/demo.js') }}"></script>
      <script type="text/javascript" language="javascript" src="{{ asset('/plugin/examples/resources/editor-demo.js') }}"></script> --}}

      <script type="text/javascript" language="javascript" class="init">

      $(function () {
        //Initialize Select2 Elements
        $(".select2").select2();

        //Date picker
        $('#AcceptanceDate').datepicker({
          autoclose: true,
            format: 'dd-M-yyyy',
        });

        $('#DrafterDate').datepicker({
          autoclose: true,
            format: 'dd-M-yyyy',
        });

      });

      </script>


@endsection

@section('content')

  <div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
      <h1>
        ESAR
        <small></small>
      </h1>
      <ol class="breadcrumb">
        <li><a href="#"><i class="fa fa-dashboard"></i>Home</a></li>
        <li><a href="#">ESAR</a></li>
      </ol>
    </section>

    <!-- Main content -->
  <section class="content">

    <div class="row">

    @foreach($po as $podetail)

    @endforeach

  <form enctype="multipart/form-data" id="upload_form" role="form" method="POST" action="{{ url('/esarpdf/') }}" >
        <input type="hidden" name="_token" value="{{ csrf_token() }}">
      <div class="CompanyName">
        <img src="{{ asset('/img/Picture1.png') }}" />
          <h4 style="color:#323277;">HUAWEI TECHNOLOGIES (MALAYSIA) SDN BHD</h4>

      </div>

      <div class="row1" >
        <h4 class="pagetitle">
          <select>
            <option>ENGINEERING SERVICE ACCEPTANCE REPORT</option>
            <option>PROVISIONAL ACCEPTANCE CERTIFICATE (PAC)</option>
            <option>FINAL ACCEPTANCE CERTIFICATE (FAC)</option>
          </select>
        </h4>

        <table class="main">
            <tr>
                <td>Project Name :</td>
                <td><input type="text" name="ProjectName" class="form-control" value="{{$podetail->Project}}" id=""></td>
                <td>Engineering Code :</td>
                <td><input type="text" name="EngineeringCode" class="form-control" value="{{$podetail->Engineering_No}}" id=""></td>
            </tr>

        </table>

      </div>

      <div class="row1" >

        <table class="main1" style="width:50%;">
            <tr>
                <td>Supplier:</td>
                <td><input type="text" name="Supplier" class="form-control" value="JALUR MILENIUM SDN BHD" id=""></td>
            </tr>
            <tr>
                <td>Address:</td>
                <td><input type="text" name="Address" class="form-control" value="No 1-1, Jalan Damai Niaga, Alam Damai, Cheras 56000 Kuala Lumpur"></td>
            </tr>

        </table>

      </div>

      <div class="row1" >

        <table class="main1" style="width:70%;">
            <tr>
                <td>Acceptance Date:</td>
                <td>
                  <div class="input-group date">
                    <div class="input-group-addon">
                      <i class="fa fa-calendar"></i>
                    </div>
                    <input type="text" class="form-control pull-right" id="AcceptanceDate" name="AcceptanceDate" value="">
                  </div>
                </td>
            </tr>
            <tr>
                <td>PO No:</td>
                <td><input type="text" name="PoNo" class="form-control" value="{{$podetail->PO_No}}" id=""></td>
            </tr>
            <tr>
                <td>Subcontract No: </td>
                <td><input type="text" name="SubConNo" class="form-control" value="{{$podetail->Sub_Contractor_No}}"></td>
            </tr>

        </table>

      </div>


      <div class="row1">
        <table class="main1" style=" width:100%;">
            <tr>
                <td>Payment Milestone:</td>
                <td>
                  <select style="color:black" class="form-control select2" name="PaymentMilestone" id="PaymentMilestone">
                    <option value="1st Milestone">1st Milestone</option>
                    <option value="2nd Milestone">2nd Milestone</option>
                    <option value="3rd Milestone">3rd Milestone</option>
                    <option value="4th Milestone">4th Milestone</option>
                    <option value="100% Payment">100% Payment</option>
                  </select>
                </td>
                <td>
                  <select style="color:black" class="form-control select2" name="Milestone" id="Milestone">
                    <option value="Hardware Installation Completion">Hardware Installation Completion</option>
                    <option value="Hardware Installation & Software Commissioning Completion">Hardware Installation & Software Commissioning Completion</option>
                    <option value="Software Commissioning Completion">Software Commissioning Completion</option>
                    <option value="Echnical Site Survey">echnical Site Survey</option>
                    <option value="RF Survey">RF Survey</option>
                  </select>
                </td>
            </tr>

        </table>
      </div>

      <br>

      <div class="row1" style="width:100%;">

        <table class="main2" style=" width:100%; align:center;" >
          <thead>
              <tr>
                @foreach($potable as $key=>$value)
                  @if ($key==0)
                    <td>No</td>

                    @foreach($value as $field=>$value)
                        <td/>{{ $field }}</td>
                    @endforeach

                  @endif

                @endforeach
              </tr>
          </thead>

          <tbody>

            <?php $i = 1; ?>

              @foreach($potable as $po1)
                <tr id="row_{{ $i }}">

                  <td>{{ $i }}</td>

                    @foreach($po1 as $key=>$value)
                      <td>
                        <input class="form-control" name="po[]" value="{{ $value }}" />
                      </td>
                    @endforeach
                </tr>
                <?php $i++; ?>
              @endforeach

        </tbody>

        </table>
      </div>

      <br>

      <div class="row1">
        <table>
          <tr>
            <td>Acceptance of Document:</td>
            <td>
              <select class="form-control select2" name="AcceptanceDocument" id="AcceptanceDocument">
                <option value="HIR (Hardware Installation Report) approved by Customer">HIR (Hardware Installation Report) approved by Customer</option>
                <option value="EIR (Equipment Installation Report) approved by Customer">EIR (Equipment Installation Report) approved by Customer</option>
                <option value="S-SIR (Software Self-Inspection Report) approved by Huawei">S-SIR (Software Self-Inspection Report) approved by Huawei</option>
              </select>
            </td>
          </tr>
        </table>
      </div>

      <div class="row1">
        <table style="width:100%">
          <tr>
            <td style="width:25%;padding:5px;">Drafter:</td>
            <td style="width:25%;padding:5px;"><input type="text" name="Drafter" class="form-control" id=""></td>
            <td style="width:25%;padding:5px;">Date:</td>
            <td style="width:25%;padding:5px;">
              <div class="input-group date">
                <div class="input-group-addon">
                  <i class="fa fa-calendar"></i>
                </div>
                <input type="text" class="form-control pull-right" name="DrafterDate" id="DrafterDate" value="">
              </div>
            </td>
          </tr>
        </table>
      </div>

      <div class="row1" style="">

        <table  style="width:50%;float:left;">
            <tr>
                <td>Authorised Signature</td>
            </tr>
            <tr>
                <td>________________________</td>
            </tr>
            <tr>
                <td>Supplier</td>
            </tr>
            <tr>
                <td>Name: ABD ZAKI MAT ISA</td>
            </tr>
            <tr>
                <td>Title: Project Manager</td>
            </tr>

        </table>

      </div>

      <div class="row1" >

        <table  style="width:50%;float:left">
            <tr>
                <td>Authorised Signature</td>
            </tr>
            <tr>
                <td>________________________</td>
            </tr>
            <tr>
                <td>HUAWEI TECHNOLOGIES (MALAYSIA) SDN BHD</td>
            </tr>
            <tr>
                <td>Name: </td>
            </tr>
            <tr>
                <td>Title: </td>
            </tr>

        </table>

      </div>

      <div class="row1" >
        <table  class="main2" >
            <tr>
                <td>Amount of Attachment:</td>
                <td>Inspector I:</td>
                <td>Inspector II:</td>
            </tr>
            <tr>
                <td>______________________</td>
                <td>______________________</td>
                <td>______________________</td>

            </tr>
            <tr>
                <td>Date:</td>
                <td>Date:</td>
                <td>Date:</td>
            </tr>


        </table>

      </div>

      <div class="row1" style="height: 40px;">
        <input type="submit" class="btn btn-primary" style="float:right;"></input>
      </div>

    </form>

</section>

</div>
    <!-- /.content -->
  <!-- /.content-wrapper -->
  <footer class="main-footer">
    <div class="pull-right hidden-xs">
      <b>Version</b> 2.0.1
    </div>
    <strong>Copyright &copy; 2014-2016 <a href="http://www.softoya.com">TrackerOnTheGo</a>.</strong> All rights
    reserved.
  </footer>

@endsection

<script>


  function pacpdfform() {

        $.ajaxSetup({
           headers: { 'X-CSRF-Token' : $('meta[name=_token]').attr('content') }
        });

        $.ajax({
                    url: "{{ url('/esarpdf/') }}",
                    method: "POST",
                    contentType: false,
                    processData: false,
                    data:new FormData($("#upload_form")[0]),
                  });
  }


</script>
