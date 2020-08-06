<html>
<head>
<style>

body {
    font-family: "Calibri";
    margin: 0;
    font-size: 14px;
    /*font-weight:600;*/
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
.CompanyName h4{

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
    /*font-size:14px;*/
}

table.main{
  width:100%;

}

table.main, table.main tr{
  border:1px solid black;
}

table.main td{
  /*padding:5px;*/
}

table.main1{
  /*border-collapse: collapse;*/
  /*padding:5px;*/
  border:1px solid black;
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

table.main3{
  border-collapse: collapse;
  border:1px solid black;
}

table.main3 th{
  text-align:center;
  background-color: #eee;
  padding:5px;
  border-collapse: collapse;
  border:1px solid black

}

table.main3 td{

  padding:5px;
  border-collapse: collapse;
  border:1px solid black

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

h4.pagetitle{
  text-align:center;
  padding-top:5px;
  color:red;
  font-weight:bold;
  font-size:18px
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
      <!-- <img src="{{ asset('/img/Picture1.png') }}" /> -->
        <h4>HUAWEI TECHNOLOGIES (MALAYSIA) SDN BHD</h4>

    </div>

    <div class="row1">

      <h4 class="pagetitle">ENGINEERING SERVICE ACCEPTANCE REPORT</h4>

      <table class="main">
          <tr>
              <td>Project Name :</td>
              <td>{{$input["ProjectName"]}}</td>
              <td>Engineering Code :</td>
              <td>{{$input["EngineeringCode"]}}</td>
          </tr>

      </table>

    </div>

    <div class="row1" style="width:100%;">

      <table class="main1" style="width:50%;float:left">
          <tr>
              <td class="bottom">Supplier:</td>
              <td class="bottom">{{$input["Supplier"]}}</td>
          </tr>
          <tr>
              <td>Address:</td>
              <td>{{$input["Address"]}}</td>
          </tr>

      </table>
      <table class="main1" style=" width:50%; float:left">
          <tr>
              <td class="bottom">Acceptance Date:</td>
              <td class="bottom">{{$input["AcceptanceDate"]}}</td>
          </tr>
          <tr>
              <td class="bottom">PO No:</td>
              <td class="bottom">{{$input["PoNo"]}}</td>
          </tr>
          <tr>
              <td>Subcontract No: </td>
              <td>{{$input["SubConNo"]}}</td>
          </tr>

      </table>

    </div>

    <div class="row1" style="padding-top:140px;">
      <table class="main">
          <tr>
              <td>Payment Milestone:</td>
              <td>{{$input["PaymentMilestone"]}}</td>
              <td>{{$input["Milestone"]}}</td>
          </tr>

      </table>
    </div>

    <div class="row1" style="">
      <table class="main">
          <tr>
            <td style="width:15%;" class="bottom">Scope of Work:</td>
            <td style="width:30%;" class="bottom">ENGINEERING_COOPERATION．</td>
            <td style="width:25%;" class="bottom">EQUIPMENT_INSTALLATION．OTHERS</td>
            <td style="width:25%;" class="bottom"></td>
          </tr>

          <tr>
            <td style="width:15%;">Starting Date:</td>
            <td style="width:30%;">11-Nov-16</td>
            <td style="width:25%;">Finishing Date: 18-Nov-16</td>
          </tr>

      </table>
    </div>

    <div style="padding-top:20px">

      <table class="main3" style=" width:100%; align:center" >
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

    <div class="row1" style="padding-top:20px">
      <table class="main">
        <tr>
          <td>Acceptance of Document:</td>
          <td>{{$input["AcceptanceDocument"]}}</td>
        </tr>
      </table>
    </div>

    <div class="row1">
      <table style="width:100%">
        <tr>
          <td style="width:25%;">Drafter:</td>
          <td style="width:25%;">{{$input["Drafter"]}}</td>
          <td style="width:25%;">Date:</td>
          <td style="width:25%;">{{$input["DrafterDate"]}}</td>
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

    <div class="row1">
      <table  class="main2" style="padding-top:130px">
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
