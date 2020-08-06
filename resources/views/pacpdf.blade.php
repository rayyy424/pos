<html>
<head>
<style>

body {
    font-family: "Calibri";
    margin-left: -20px;
    font-size: 14px;
    /font-weight:600;/
    width:100%;
}
.container {
    width: 100%;
    margin:0;
}
.row{
padding:10px;
}
.CompanyName{
  width:100%;
  margin:0;
}
.CompanyName h2{
  line-height: 20%;
  padding-top:10px;

}
.CompanyName h4{
  text-align:center;
  font-weight:bold;
  font-size:18px;

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
header, footer {
    color: white;
    background-color: #d00f01;
    clear: left;
    text-align: center;
}
footer {
    padding: 1em;
}
header img{
    float:right;
    width:40px;
    padding:10px;
    border-radius:50%;
}
header{
    height:60px;
}
table {
    width:100%;

}

table.main{
  width:100%;

}

table.main, table.main tr{

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

td.bottom{
  border-bottom: 1px solid black;
}

td.right{
  border-right: 1px solid black;
}

table.main2, table.main2 tr{
  border-collapse: collapse;
  border:1px solid black;
}

table.main2 td{
  padding:10px;
}

table#datatable {
  border: 1px solid black;
}

table#datatable thead{
  font-weight:bold;
}


.row1{
  padding:10px;
  width:100%;
}

h1.pagetitle{
  text-align:center;
  padding-top:5px;
  color:black;
  font-weight:bold;
  font-size:30px
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

</head>

<body>

  <div id="footer">
    <p class="page"></p>
  </div>

  <div class="CompanyName">

      <h4 style="">ACCEPTANCE CERTIFICATE</h4>

  </div>

  <div class="row1" >
    <!-- <img width="60" src="{{ asset('/img/Picture1.png') }}" />&nbsp;&nbsp;&nbsp;Huawei Technologies (Malaysia) Sdn. Bhd. -->
    <br>
    <table class="main">
        <tr>
            <td style="width:20%">Supplier Code:</td>
            <td tyle="width:25%">{{$input["SupplierCode"]}}</td>
            <td tyle="width:23%;margin-left:10px">Supplier Name:</td>
            <td tyle="width:25%">{{$input["SupplierName"]}}</td>
        </tr>
        <tr>
            <td>Project Code:</td>
            <td>{{$input["ProjectCode"]}}</td>
            <td>Project Name :</td>
            <td>{{$input["ProjectName"]}}</td>
        </tr>
        <tr>
            <td>Payment Terms:</td>
            <td>{{$input["PaymentTerms"]}}</td>
            <td>SubcontractorNo</td>
            <td>{{$input["SubContractNo"]}}</td>
        </tr>
        <tr>
            <td>EngineeringNo:</td>
            <td>{{$input["EngineeringNo"]}}</td>
            <td>Central Site :</td>
            <td>{{$input["CentralSite"]}}</td>
        </tr>
        <tr>
            <td>PO No. :</td>
            <td>{{$input["PONo"]}}</td>
        </tr>
    </table>

  </div>


  <div class="row1" style="width:100%;">

    <table class="main1" style=" width:100%; align:center" >

            <thead>

                <tr>
                  <td>No</td>
                  <td>Site_Name</td>
                  <td>Site_ID</td>
                  <td>Item_Description</td>
                  <td>Due_Quantity</td>
                  <td>Unit_Price</td>
                </tr>
            </thead>

            <tbody>

              <?php $i = 1; ?>
              <?php $table = array_chunk($input["po"], 5); ?>
                 @foreach($table as $tablevalue)
                   <tr id="row_{{ $i }}">

                     <td>{{ $i }}</td>

                       @foreach($tablevalue as $key=>$value)
                         <td>{{$value}}</td>
                       @endforeach
                   </tr>
                  @endforeach
                <?php $i++; ?>

          </tbody>

    </table>


  </div>

  <div class="row1" style="font-weight:600" >

    <p>Remark:</p>
    <ol>

      <li>This shows the final value of all work done in accordance with the PO. The whole of the works have now been substantially completed,
      and have satisfactorily passed all the tests on completion specified in the contract.</li>
      <li>Signatures are required according to the clauses that is clearly stated in the contract.</li>
    </ol>
    <p>Note:</p>


  </div>

  <div class="row1" style="">

    <table  style="width:50%;float:left;">

        <tr>
            <td>Name*:JALUR MILENIUM SDN BHD</td>
        </tr>
        <tr>
            <td>Authorized Signature：</td>
        </tr>
        <tr>
            <td>Date*:</td>
        </tr>

    </table>

  </div>

  <div class="row1" >

    <table  style="width:50%;float:left">
      <tr>
          <td>Name*:Huawei Technologies (Malaysia) Sdn. Bhd.</td>
      </tr>
      <tr>
          <td>Authorized Signature：</td>
      </tr>
      <tr>
          <td>Date*:</td>
      </tr>


    </table>

  </div>






</body>
</html>
