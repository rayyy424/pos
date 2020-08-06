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
      .container {
          width: 100%;
          margin:0;
      }
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
        width:70%;
        padding-left:50px;
      }

      .row1{
        padding:10px;
        width:100%;
      }

      h4.pagetitle{
        text-align:center;
        /*background-color:#323277;*/
        border:2px solid black;
        /*color:white; */
        padding-top:5px;
      }

      table#t01 thead tr{
        background-color: grey;
        color:black;
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
      @page { margin: 80px 50px; }
          #header { position: fixed; left: 0px; top: -60px; right: 0px; height: 60px;   }
          #footer { position: fixed; left: 0px; bottom: -80px; right: 0px; height: 40px;  text-align: right;padding:20px; }
          #header img { float:right; width:60px; padding:10px;}
          #footer .page:after { content: counter(page); }

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
        PR
        <small></small>
      </h1>
      <ol class="breadcrumb">
        <li><a href="#"><i class="fa fa-dashboard"></i>Home</a></li>
        <li><a href="#">PR</a></li>
      </ol>
    </section>

    <!-- Main content -->
    <section class="content">


        <div id="footer">
          <p class="page"></p>
        </div>

          <div class="CompanyName">
            <img src="http://trackeronthego-v2.softoya.com/img/jalur_full.png" width="200"></img>
              <h2 style="color:#323277;">Jalur Milenium Sdn Bhd</h2>
              <h5> No. 1-2 Jalan Damai Niaga,Alam Damai,Cheras,56000 Kuala Lumpur<br>
              Tel : 03-9101 8999   Fax : 03-9101 7999<br>
              GST ID No : 000565903360</h5>

          </div>

          <div class="row1" >
            <h4 class="pagetitle">Purchase Request</h4>

            <table class="main">
                <tr>
                    <td>Requestor</td>
                    <td>:</td>
                    <td>David</td>
                </tr>
                <tr>
                    <td>Project Name</td>
                    <td>:</td>
                    <td>Celcom Hammer Microwave KV</td>
                </tr>
                 <tr>
                    <td>Project Code</td>
                    <td>:</td>
                    <td>HW-CL-MWKV</td>
                </tr>
                <tr>
                    <td>Sub-con TI  Antenna Swap</td>
                    <td>:</td>
                    <td>LITECORE SDN BHD<td>
                </tr>
                <tr>
                    <td>Contact Person</td>
                    <td>:</td>
                    <td>SK Peong / Allen Ng</td>
                </tr>
                <tr>
                    <td>Contact No.</td>
                    <td>:</td>
                    <td>016-3102836 / 012-7701643</td>
                </tr>


                <tr>
                    <td>PR No</td>
                    <td>:</td>
                    <td>JM-CELCOM-MW-160801</td>
                </tr>
                <tr>
                    <td>Date</td>
                    <td>:</td>
                    <td>02/08/2016</td>
                </tr>
                 <tr>
                    <td>Payment Terms</td>
                    <td>:</td>
                    <td>50% Upon Job Completion With Photo<br>50% 90 Days from the 1st Payment</td>
                </tr>


            </table>

          </div>

          <div class="">
            <table id="t01">
              <thead>
                <tr>
                    <td>No</td>
                    <td>Site Name</td>
                    <td>Site ID</td>
                    <td>BOM</td>
                    <td>Item Description</td>
                    <td>Qty</td>
                    <td>Subcon Price (RM)</td>
                </tr>
              </thead>

                <tr>
                    <td>1</td>
                    <td>KM6.7_CYBERPARK_U9</td>
                    <td>B01825</td>
                    <td>JM-MW-A12</td>
                    <td>JM-MW-A12	</td>
                    <td>1</td>
                    <td>1,900.00</td>
                </tr>

                <tr>
                    <td>2</td>
                    <td>KM6.7_CYBERPARK_U9</td>
                    <td>B01825</td>
                    <td>JM-MW-Migration</td>
                    <td>MW Link Service Migration & Integration	</td>
                    <td>1</td>
                    <td>300.00</td>
                </tr>

                <tr>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td>Subtotal ( Exluding GST )</td>
                    <td></td>
                    <td>2,200.00</td>
                </tr>

                <tr>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td>( Priced quoted with GST for GST register company )	</td>
                    <td>Total ( Inclusive of GST</td>
                    <td> 2,200.00 </td>
                    <td> 132.00 </td>
                </tr>


            </table>
          </div>

          <div>
            <p style="font-weight:bold">This is a computer generated Purchase Order. No Signature required.</p>
          </div>

          <a href="{{ url('/prpdf/') }}" class="btn btn-primary" style="float:right">Print PDF</a>


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
