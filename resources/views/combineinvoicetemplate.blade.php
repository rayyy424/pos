<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>{{ env('APP_NAME') }}</title>
  <link href="{{ asset('/css/app.css') }}" rel="stylesheet">

  <!-- Fonts -->
  <link href='//fonts.googleapis.com/css?family=Roboto:400,300' rel='stylesheet' type='text/css'>

  <link rel="stylesheet" href="//code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">

  <!-- jQuery 2.2.3 -->
  <script src="{{ asset('/plugin//jQuery/jquery-2.2.3.min.js') }}"></script>
  <!-- jQuery UI 1.11.4 -->
  <script src="https://code.jquery.com/ui/1.11.4/jquery-ui.js"></script>

  <link href="{{ asset('/plugin/bootstrap/css/bootstrap.min.css') }}" rel="stylesheet">

  <style type="text/css">
    @page { 
      margin: 50px 50px; 
    }

    body {
      /*padding-left: 10px;
      padding-right: 10px;*/
      font-size: 12px;
    }

    img {
      max-height: 50px;
      max-width: 80px;
    }

    h3 {
      margin-top: 5px;
      margin-bottom: 5px;
      font-size: 17px;
    }

    header {
      /*padding: 1px;*/
      padding-bottom: 0px;
      text-align: center;
      width: 100%;
      height: 5%; 
      display: block;
    }

    h4 {
      font-size: 15px;
      margin: 0px !important;
      padding-top: 5px;
      padding-bottom: 5px;
    }

    p {
      white-space: pre-line;
      margin-bottom: 0;
    }

    hr {
      margin: 0;
      border-top: 1px solid black;
    }

    .head-note {
      text-align: center;
      font-size: 30px;
    }

    .content {
      min-height: 250px;
      padding: 10px;
      margin-right: auto;
      margin-left: auto;
      padding-left: 10px;
      padding-right: 10px;
      padding-top: 0px;
    }

    table {
        width:100%;
        font-size:12px;
        page-break-inside:auto;
        border:1;
        border-collapse: collapse;
    }

    tr { 
        page-break-inside:avoid; 
        page-break-after:auto 
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
    }

    .sign {
      border-bottom: 1px solid black;
      width: 100%;
      height: 40px;
    }

    .sign-name {
      padding-left: 15px;
    }

    .note {
      font-size: 11px;
    }

    .total {
      padding-left: 16px;
    }

    .list {
      /*min-height: 650px;*/
    }

    .signature {
      margin-bottom: 30px;
      margin-top: 30px;
    }

    .right
    {
      position: absolute;
        right: 10px;
        /*top: 5px;*/
    }

    /*@media print{
      .page-header{
        display: block;
        position: fixed;
        margin-top: 0;
        margin-right: 50px;
        margin-left: 50px;
        width: 90%;
      }
      .page-content{
        display: block;
        position: absolute;
        margin-top: 320px;
        margin-right: 50px;
        margin-left: 50px;
        width: 90%;
      }
      .custom-page-start {
      margin-top: 320px;
      }

    }

    tbody tr {
    page-break-before: always;
    page-break-inside: avoid;
    }*/

  </style>
</head>

<body onload="window.print();">
  <!-- <body> -->
  <header>
    <h3><b>{{$list->Company_Name}}</b></h3>
    <p class="no-margin">{{$list->Address}}</p>
    <p>Tel: {{$list->Office_No}}  Fax: {{$list->Fax_No}}</p>
    <br>
    <hr>
  </header>

  <section class="content">
    <h4 class="text-center" style=""><b>E-INVOICE</b></h4>
    <br>
    <div class="row">

      <!-- TO -->
      <div class="col-xs-7">
        <div class="row">
          <div class="col-xs-1">
            <p><b></b></p>
          </div> 
          <div class="col-xs-10">
            <p><b>{{$list->client_company}}</b></p>
          </div>
        </div>
        <div class="row">
          <div class="col-xs-1">
            <p><b></b></p>
          </div>
          <div class="col-xs-10">
            <p>{{$list->client_address}}</p>
          </div>
        </div>
        <div class="row">
          <div class="col-xs-1">
            <p><b></b></p>
          </div>
          <div class="col-xs-10">
            <p>TEL: {{$list->client_no}}         &nbsp&nbsp FAX:  {{$list->client_fax}}</p>
          </div>
        </div>
        <div class="row">
          <div class="col-xs-1">
            <p><b></b></p>
          </div> 
          <div class="col-xs-10">
            <p>ATTN: {{$list->attention}}</p>
          </div>
        </div>
      </div>

      <div class="col-xs-5">
        <div class="row">
          <div class="col-xs-5">
            <p><b>NO</b></p>
          </div>
          <div class="col-xs-7">
            <p><b>:&nbsp&nbsp{{$list->combined_invoice_num}}</b></p>
          </div>
        </div>
        <div class="row">
          <div class="col-xs-5">
            <p>DATE</p>
          </div>
          <div class="col-xs-7">
            <p>:&nbsp&nbsp{{date('d-m-Y',strtotime($list->combined_invoice_date))}}</p>
          </div>
        </div>
        <div class="row">
          <div class="col-xs-5">
            <p>P.O NO.</p>
          </div>
          <div class="col-xs-7">
            <p>:&nbsp&nbsp{{$list->po}}</p>
          </div>
        </div>
        <div class="row">
          <div class="col-xs-5">
            <p>D.O NO.</p>
          </div>
          <div class="col-xs-7">
            <p>:</p>
          </div>
        </div>
         <div class="row">
          <div class="col-xs-5">
            <p>DEPT</p>
          </div>
          <div class="col-xs-7">
            <p>:&nbsp&nbspSPEEDFREAK</p>
          </div>
        </div>
        <div class="row">
          <div class="col-xs-5">
            <p>PROJ CODE</p>
          </div>
          <div class="col-xs-7">
            <p>:&nbsp&nbsp{{$list->type}}</p>
          </div>
        </div>
        <div class="row">
          <div class="col-xs-5">
            <p>TERM</p>
          </div>
          <div class="col-xs-7">
            <p>:&nbsp&nbsp{{$list->term}}</p>
          </div>
        </div>
        
          </div>
    </div>
    <br><hr><br>
    @foreach ($invoice as $invoice)
    <div class="row">
      <div class="col-xs-12 list">
        <table border="1" >
          <col width="8%">
          <col width="60%">
          <col width="5%">
          <col width="7%">
          <col width="10%">
          <col width="10%">
          <thead>
            <tr>
                        <th style="text-align:center;">No.</th>
                        <th style="text-align:center;">Item Description</th>
                        <th style="text-align:center;">Qty</th>
                        <th style="text-align:center;">Unit</th>
                        <th style="text-align:center;">Unit Price (RM)</th>
                        <th style="text-align:center;">Total (RM)</th>
            </tr>
          </thead>
          <tbody>
            <?php $i=1;?>
              @foreach($soitem as $si)
                    @if($si->salesorderId == $invoice->Id)
                  <tr>
                    <td style="text-align:center">{{$si->item_no}}</td>
                    <td style="text-align:left;"><p>{{$si->description}}</p>
                    </td>
                    <td style="text-align:center;">{{$si->qty}}</td>
                    <td style="text-align:center;">{{$si->unit}}</td>
                    <td style="text-align:center;">{{$si->price}}</td>
                    <td style="text-align:center;">{{$si->total}}</td>
                  </tr>
              <?php $i+=1;?>
                    @endif   
              @endforeach
          </tbody>
        </table>
        <br>
        <div class="row">
        <div class="col-xs-6">
          <p style="text-align: center;"><b>Site Name</b><br>{{$invoice->site}}</p>
        </div>
        <div class="col-xs-6">
          <p style="text-align: center;"><b>Rental Period</b><br>{{$invoice->rental_start}} to {{$invoice->rental_end}}</p>
        </div>
        </div>
        @endforeach
        @if($list->combine_remarks != "" || $list->combine_remarks != NULL)
        <br>
        <div class="row">
          <div class="col-xs-12">
            <p> Remarks : {{$invoice->combine_remarks}} </p>
          </div>
        </div>
        @endif
        <br><hr><br>
        <div class="row">
        <div class="col-xs-6">
          <p>RINGGIT MALAYSIA {{$words}} ONLY</p>
        </div>
        <div class="col-xs-6">
        <p align="right">Total: <input type="textarea" value="{{$totalamount}}" readonly="" style="text-align: right"></p>
        </div>
        </div>
        <br><hr><br>
        <div class="row">
          <div class="col-xs-7">
          Notes:
          <br>
          1. Please officially notify us within 7 days if you have dispute to this invoice, otherwise it will be treated as correct & effective
          <br>
          2. We reserve the right to charge 1.5% interest per month on any outstanding amount due after the stipulated terms of payment.
          <br>
          3. All cheques should be crossed and made payable to 
          <br><br><b>{{$list->Company_Name}} &nbsp&nbsp&nbsp&nbsp{{$list->bank}} A/C NO: {{$list->bank_acct}}</b>
          <br>
          <br>
          <b>This is computer generated. No signature is required.</b>
        </div>
        </div>
      </div>
    </div>
    </div>

  </section>
</body>
</html>
