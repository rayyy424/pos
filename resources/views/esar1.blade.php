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
      margin-top:15px;
      font-weight:600;
    }

    table.main, table.main tr{
      /*border:1px solid black;*/
    }

    table.main td{
      padding:5px;
    }

    table.main1{
      border-collapse: collapse;
      border:1px solid black;
    }

    table.main1 th{
      text-align:center;
      background-color: #eee;
      padding:5px;
      border-collapse: collapse;
      border:1px solid black

    }

    table.main1 td{

      padding:5px;
      border-collapse: collapse;
      border:1px solid black

    }
    table.main2, table.main2 tr{
      border-collapse: collapse;
      border:1px solid black;
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

<form enctype="multipart/form-data" id="upload_form" role="form" method="POST" action="{{ url('/esar1pdf/') }}" >
<input type="hidden" name="_token" value="{{ csrf_token() }}">

    @foreach($po as $podetail)

    @endforeach

        <div class="row1">


    	  <div class="CompanyName">
          <!-- <img src="{{ asset('/img/Picture1.png') }}" /><h4>HUAWEI TECHNOLOGIES (MALAYSIA) SDN BHD</h4> -->
            <h3>ACCEPTANCE CERTIFICATE</h3>
        </div>

          <table class="main">

    		  <tr>
    			  <td>Supplier Code:</td>
    			  <td><input type="text" name="SupplierCode" class="form-control" value=""></td>
    			  <td>Supplier Name:</td>
    			  <td><input type="text" name="SupplierName" class="form-control" value=""></td>
    		  </tr>

    		  <tr>
    			  <td>Payment Terms:</td>
    			  <td><input type="text" name="PaymentTerms" class="form-control" value=""></td>
    			  <td>SubcontractorNo:</td>
    			  <td><input type="text" name="SubConNo" class="form-control" value="{{$podetail->Sub_Contractor_No}}"></td>
    		  </tr>

            <tr>
                <td>EngineeringNo:</td>
                <td><input type="text" name="EngineeringNo" class="form-control" value="{{$podetail->Engineering_No}}"></td>
                <td>Central Site:</td>
                <td><input type="text" name="CentralSite" class="form-control" value=""></td>
            </tr>

    		  <tr>
    			<td>PO No:</td>
    			<td><input type="text" name="PoNo" class="form-control" value="{{$podetail->PO_No}}"></td>

          </table>

        </div>

        <div class="row1" style="width:100%;">


          <table class="main1" style=" width:100%; align:center;" >

              <thead>
                  {{-- prepare header search textbox --}}
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

        <div class="row1" >
          <p><b>Remark:</b></p>
    	  <ol>
    		  <li>This shows the final value of all work done in accordance with the PO. The whole of the works have now been substantially
    		  completed, and have satisfactorily passed all the tests on completion specified in the contract.</li>
    		  <li>Signatures are required according to the clauses that is clearly stated in the contract.</li>
    	</ol>

    	  <p><b>Note:</b></p>


    	  <table>

    	  <tr>
    		<td>Name*:JALUR MILENIUM SDN BHD</td>
    		<td>Name*:Huawei Technologies (Malaysia) Sdn. Bhd.</td>
    	  <tr>

    	  <tr>
    		<td>Authorized Signature:</td>
    		<td>Authorized Signature:</td>

    	  </tr>

    	  <tr>
    		<td>Date:</td>
    		<td>Date:</td>

    	  </tr>

    	  </table>

        </div>


      <div class="row1" style="height: 40px;">

           <input type="submit" class="btn btn-primary" style="float:right;"></input>

          </div>
          </form>

      </div>

    </section>
    <!-- /.content -->
  </div>
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
                  url: "{{ url('/esar1pdf/') }}",
                  method: "POST",
                  contentType: false,
                  processData: false,
                  data:new FormData($("#upload_form")[0]),
                });
}


</script>
