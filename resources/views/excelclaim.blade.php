<html>
<head>


</head>

<body>
        <h3>Claims - [{{ $claim[0]->Claim_Sheet_Name }}]</h3>

        <table border="0" style="width:100%; font-size:12px;padding:10px;padding-left:-20px;">
            <tr><td><b>Staff ID</b></td><td>{{$user->StaffId}}</td></tr>
            <tr><td><b>Name</b></td><td>{{$user->Name}}</td></tr>
            <tr><td><b>Department</b></td><td>{{$user->Department}}</td></tr>
            <tr><td><b>Position</b></td><td>{{$user->Position}}</td></tr>
            <tr><td><b>Nationality</b></td><td>{{$user->Nationality}}</td></tr>
            <tr><td><b>Home Base</b></td><td>{{$user->Home_Base}}</td></tr>
        </table>

        <table id="t01">
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

                            <?php $tot1=$claim->Total_Expenses-$claim->Petrol_SmartP;
                            $tot=number_format($tot1, 2, '.', ',') ?>
                            <td>{{$tot}}</td>
                            @else
                            <td>
                                {{ $value }}
                            </td>
                            @endif
                            @endforeach
                        </tr>
                        <?php $i++; ?>

                @endforeach

        </table>
    </div>

      <div class="row" style="padding-top:20px;">
        <table id = "total" border="0" style="width:20%;font-size:12px;">
            <tr >
                <td style="text-align:left;"><b>Total Expenses</b></td>
                @foreach($total as $sum)
                <td style="text-align:right;">RM {{$sum->TotalExpenses}}</td>
                @endforeach
            </tr>
            <tr>
                <td style="text-align:left;"><b>Total SmartPay</b></td>
                @foreach($total as $sum)
                <td style="text-align:right;">RM {{$sum->TotalSmartpay}}</td>
                @endforeach
            </tr>
            <tr>
                <td style="text-align:left;"><b>Total Advance</b></td>
                @foreach($total as $sum)
                <td style="text-align:right;">RM {{$sum->TotalAdvance}}</td>
                @endforeach
            </tr>
            <tr>
                <td style="text-align:left;"><b>Total GST Amount</b></td>
                @foreach($total as $sum)
                <td style="text-align:right;">RM {{$sum->TotalGSTAmount}}</td>
                @endforeach
            </tr>
            <tr>
                <td style="text-align:left;"><b>Total Without GST</b></td>
                @foreach($total as $sum)
                <td style="text-align:right;">RM {{$sum->TotalnoGST}}</td>
                @endforeach
            </tr>
            <tr>
                <td style="text-align:left;"><b>Total Payable Amount</b></td>
                @foreach($total as $sum)
                <td style="text-align:right;">RM {{$sum->TotalPayable}}</td>
                @endforeach
            </tr>

        </table>


</body>

</html>
