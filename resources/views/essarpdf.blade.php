<html>
<head>
<style>
body {
  font-family: 'Source Sans Pro';
    margin: 0;
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
    font-size:14px;
}

table.main{
  border-collapse: collapse;
}

table.main, table.main tr{
  border:1px solid black;
}

table.main td{
  padding:5px;
}

table.main1{
  border-collapse: collapse;
  border:1px solid black;
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
  background-color:#94ce58;
  color:red;
  padding-top:5px;
}

table#t01 thead tr{
  background-color: grey;
  color:white;
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

</head>

<body>


  <div id="footer">
    <p class="page"></p>
  </div>

    <div class="CompanyName">
      <img src="{{ asset('/img/Picture1.png') }}" />
        <h4 style="color:#323277;">HUAWEI TECHNOLOGIES (MALAYSIA) SDN BHD</h4>

    </div>

    <div class="row1" >
      <h4 class="pagetitle">ENGINEERING SERVICE ACCEPTANCE REPORT</h4>

      <table class="main">
          <tr>
              <td>Engineering Code :</td>
              <td style="background-color:#3c8dbc; color:white;">0004581600810A</td>
          </tr>

      </table>

    </div>

    <div class="row1" >

    <table class="main1" style="background-color: #f3e627; width:50%;">
        <tr>
            <td>Supplier:</td>
            <td>JALUR MILENIUM SDN BHD</td>
        </tr>
        <tr>
            <td>Address:</td>
            <td>No 1-1, Jalan Damai Niaga, Alam Damai, Cheras 56000 Kuala Lumpur</td>
        </tr>

    </table>

    </div>

    <div class="row1" >

    <table class="main1" style=" width:70%;background-color:#ce5f52;color:white">
        <tr>
            <td>Acceptance Date:</td>
            <td>08/03/2017</td>
        </tr>
        <tr>
            <td>PO No:</td>
            <td>1031070062-40</td>
        </tr>
        <tr>
            <td>Subcontract No: </td>
            <td>FPA1031MYS1608180010097690203 891</td>
        </tr>

    </table>

    </div>

    <div class="row1">
      <table class="main1" style=" width:100%;background-color:#3c8dbc;color:white">
          <tr>
              <td>Payment Milestone:</td>
              <td>100% Payment</td>
              <td>Hardware Installation Completion</td>
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

    <div class="row1">
      <table>
        <tr>
          <td>Acceptance of Document:</td>
          <td style="background-color:#f3e627">HIR (Hardware Installation Report) approved by Customer</td>
        </tr>
      </table>
    </div>

    <div class="row1">
      <table style="width:100%">
        <tr>
          <td>Drafter:</td>
          <td style="text-decoration: underline;">Hana</td>
          <td>Date:</td>
          <td style="background-color:#ce5f52;color:white">8-Mar-17</td>
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
            <td>Name: MUHAMMAD ZAHIN BIN KAMAL</td>
        </tr>
        <tr>
            <td>Title: Director</td>
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
              <td>Customer</td>
          </tr>
          <tr>
              <td>Name: </td>
          </tr>
          <tr>
              <td>Title: </td>
          </tr>

      </table>

    </div>

    <div class="row1">
      <table  class="main2" style="padding-top:120px">
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





<div class="row">
    <div class="profilepic">
    </div>
</div>





</body>
</html>
