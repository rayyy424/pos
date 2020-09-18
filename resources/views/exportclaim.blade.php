<html>
<head>
  <title>{{ env('APP_NAME') }}</title>
<style>
body {
    margin-left: -30px;
    width:100%;
}
.container {
    width: 100%;
    margin:0;
    left: -10px;
}
.row{
    padding-left:-10px;
    text-align: center;

}
.row h2,h4,h3{
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
    font-size:7px;
    page-break-inside:auto ;
}
tr    { page-break-inside:avoid; page-break-after:auto }

table.t01 tr:nth-child(even) {
    background-color: #eee;
}
table.t01 tr:nth-child(odd) {
   background-color:#fff;
}
table.t01 th {
    background-color: #3b3d3e;
    color: white;
}
table.t01 th, td {
    text-align: center;
}
table.total{
    width:40%;
    font-size:12px;
}

.profilepic{
    text-align:center;
}
.profilepic img{
    width:100px;

    border-radius:50%;
}
.box{
     border: 2px solid #e0e0e0;
     padding: 10px;
     width: 350px;
     font-size: 14px;
     text-align:right;
     border-radius: 5px;margin:10px;
}
.box{
     border: 2px solid #e0e0e0;
     padding: 10px;
     width: 100%;
     font-size: 14px;
     text-align:center;
     border-radius: 5px;
     margin:10px;
}
@page { margin: 80px 50px; }
    #header { position: fixed; left: 0px; top: -70px; right: 0px; height: 60px;   }
    #footer { position: fixed; left: 0px; bottom: -80px; right: 0px; height: 40px;  text-align: right;padding:20px; }
    #header img { float:right; width:60px; padding:10px;}
    #footer .page:after { content: counter(page); }

</style>

</head>

<body>

<div id="header">
    <span>Exported at : {{date('d-M-Y h:i:s A')}}</span>
    <img src="http://totg.midascom.my/img/logo.png"></img>
  </div>
  <div id="footer">
    <p class="page"></p>
  </div>

<div class="container">



    <div class="row">
            <h3>Claims - [{{ $claim[0]->Claim_Sheet_Name }}]</h3>
    </div>

    <div class="row">

        <table border="0" style="width:100%; font-size:12px;padding:10px;padding-left:-20px;">
            <tr>
                <td><b>Staff ID</b>  : {{$user->StaffId}}</td>
                <td><b>Name</b> : {{$user->Name}}</td>
                @if($user->Position != "")
                  <td><b>Position</b> :  {{$user->Position}}</td>
                @else
                  <td><b>Position</b> : - </td>
                @endif
                @if($user->Nationality != "")
                  <td><b>Nationality</b> : {{$user->Nationality}}</td>
                @else
                  <td><b>Nationality</b> : - </td>
                @endif
                @if($user->Home_Base != "")
                  <td><b>Home Base</b> : {{$user->Home_Base}}</td>
                @else
                  <td><b>Home Base</b> : - </td>
                @endif
            </tr>
        </table>

    </div>

    @if($timesheetdetail)

      <h6>Timesheets</h6>

      <table class="t01">
      <thead>
      <tr>
          @foreach($timesheetdetail as $key=>$value)

              @if ($key==0)

              @foreach($value as $field=>$value)
              <th>{{ $field }}</th>
              @endforeach

              @endif

          @endforeach

          </tr>
          </thead>
              <?php $i = 0; ?>
              @foreach($timesheetdetail as $timesheetdetails)

                    <tr id="row_{{ $i }}" >
                        @foreach($timesheetdetails as $key=>$value)
                          <td>
                            @if($key=="Day")
                              {{ date('D', strtotime($timesheetdetails->Date)) }}
                            @else
                              {{ $value }}
                            @endif
                          </td>
                        @endforeach
                    </tr>
                    <?php $i++; ?>

              @endforeach

      </table>

    @endif

    @if($claimdetail)

        @if($timesheetdetail)

          <div style="page-break-before: always;">

        @else

          <div>

        @endif

      <h6>Claim</h6>

      <div class="row">
          <h4></h4>

          <table class="t01">
          <thead>
          <tr>

            @foreach($claimdetail as $key=>$value)

                @if ($key==0)

                @foreach($value as $field=>$value)
                <th>{{ $field }}</th>
                @endforeach

                @endif

            @endforeach

          </tr>
          </thead>
                  <?php $i = 0; ?>
                  @foreach($claimdetail as $claim)

                          <tr id="row_{{ $i }}" >
                              @foreach($claim as $key=>$value)

                                @if ($key=="Exc_SmartP")

                                  <?php $tot1=$claim->Tot_Exp-$claim->SmartP;
                                  $tot=number_format($tot1, 2, '.', ',') ?>
                                  <td>{{$tot}}</td>

                                @else
                                <td>
                                  @if($key=="Day")
                                    {{ date('D', strtotime($claim->Date)) }}
                                  @else
                                    {{ $value }}
                                  @endif
                                </td>
                                @endif
                              @endforeach
                          </tr>
                          <?php $i++; ?>

                  @endforeach

          </table>
      </div>

    </div>

    @endif

    <div class="row" style="padding-top:20px;">
        <table id = "total" border="0" style="width:40%;font-size:12px;">
            <tr >
                <td style="text-align:left;"><b>Total Expenses</b> : </td>
                @foreach($total as $sum)
                <td style="text-align:right;">RM {{$sum->TotalExpenses}}</td>
                @endforeach
            </tr>
            <tr>
                <td style="text-align:left;"><b>Total SmartPay</b> : </td>
                @foreach($total as $sum)
                <td style="text-align:right;">RM {{$sum->TotalSmartpay}}</td>
                @endforeach
            </tr>
            <tr>
                <td style="text-align:left;"><b>Total Advance</b> : </td>
                @foreach($total as $sum)
                <td style="text-align:right;">RM {{$sum->TotalAdvance}}</td>
                @endforeach
            </tr>
            <tr>
                <td style="text-align:left;"><b>Total Allowance</b> : </td>
                @foreach($total as $sum)
                <td style="text-align:right;">RM {{number_format($TotalAllowance,2)}}</td>
                @endforeach
            </tr>
            <tr>
                <td style="text-align:left;"><b>Total GST Amount</b> : </td>
                @foreach($total as $sum)
                <td style="text-align:right;">RM {{$sum->TotalGSTAmount}}</td>
                @endforeach
            </tr>
            <tr>
                <td style="text-align:left;"><b>Total Without GST</b> : </td>
                @foreach($total as $sum)
                <td style="text-align:right;">RM {{$sum->TotalnoGST}}</td>
                @endforeach
            </tr>
            <tr>
                <td style="text-align:left;"><b>Total Payable</b> : </td>
                @foreach($total as $sum)
                <td style="text-align:right;">RM {{$sum->TotalPayable}}</td>
                @endforeach
            </tr>
            <tr>
                <td style="text-align:left;"><b>Total Payable + Allowance + Monetary Comp</b> : </td>
                @foreach($total as $sum)
                <td style="text-align:right;">RM {{ number_format($sum->TotalPayable+$TotalAllowance,2)}}</td>
                @endforeach
            </tr>

        </table>
    </div>

</div>

</body>

</html>
