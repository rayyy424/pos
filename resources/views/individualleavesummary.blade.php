<html>
<head>
  <title>{{ env('APP_NAME') }}</title>
<style>
body {
    margin-left: 0px;
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

.info{
  text-align:left;
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

.attendance table {
    border-collapse: collapse;
    border-style: hidden;
}

.attendance table td, table th {
    border: 1px solid black;
    cellpadding:0;
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
    {{-- <span>Exported at : {{date('d-M-Y h:i:s A')}}</span> --}}
    {{-- <img src="http://pronetwork.com.my/portal/public/img/propel.jpg"></img> --}}
  </div>
  <div id="footer">
    <p class="page"></p>
  </div>

<div class="container">

    <div class="row">
            <h3 style="text-align:left;">SUMMARY OF ANNUAL LEAVE AND MEDICAL LEAVE RECORD as of : {{date('d-M-Y')}}</h3>
    </div>

    <div class="row">

      <table border="0" style="width:100%; font-size:12px;">
          <tr>
              <th style="width:25%;text-align:left;Color:white;background-color:grey;">EMPLOYEE DATA</th>
              <th style="width:25%;text-align:left;Color:white;background-color:grey;">ANNUAL LEAVE RECORDS</th>
              <th style="width:25%;text-align:left;Color:white;background-color:grey;">MEDICAL LEAVE RECORDS</th>
              <th style="width:25%;text-align:left;Color:white;background-color:grey;">LEAVE TAKEN</th>

          </tr>

          <tr>
            <td style="vertical-align:top;">
              <table border="0" style="width:100%; font-size:10px;">
                  <tr>
                      <td class="info"><b>Employee Name</b> : </td><td class="info">{{$user->Name}}</td>
                  </tr>
                  <tr>
                      <td class="info"><b>Department</b> : </td><td class="info">{{$user->Department}}</td>
                  </tr>
                  <tr>
                      <td class="info"><b>Job Title</b> : </td><td class="info">{{$user->Position}}</td>
                  </tr>
                  <tr>
                      <td class="info"><b>Employee Number</b> : </td><td class="info">{{$user->StaffId}}</td>
                  </tr>
                  <tr>
                      <td class="info"><b>Joined Date</b> : </td><td class="info">{{$user->Joining_Date}}</td>
                  </tr>
              </table>
            </td>

            <td style="vertical-align:top;">
              <table border="0" style="width:100%; font-size:10px;">
                  {{-- <tr>
                      <td class="info"><b>Annual Leave Carried Forward from Year 2017</b> : </td><td class="info">{{$leavebalance[5]}}</td>
                  </tr> --}}

                  <tr>
                      <td class="info"><b>Annual Leave Entitlement</b> : </td><td class="info">{{$leavebalance[0]}}</td>
                  </tr>

                  <tr>
                      <td class="info"><b>Annual Leave Taken todate</b> : </td><td class="info">{{$leavebalance[4]}}</td>
                  </tr>

                  <tr>
                      <td class="info"><b>Annual Leave Pro-rate Entitled as todate</b> : </td><td class="info">{{$leavebalance[1]}}</td>
                  </tr>

                  <tr>
                      <td class="info"><b>Annual Leave balance as at todate</b> : </td><td class="info">{{$leavebalance[3]}}</td>
                  </tr>

                  <tr>
                      <td class="info"><b>Annual Leave balance until end of the year</b> : </td><td class="info">{{$leavebalance[6]}}</td>
                  </tr>

              </table>
            </td>

            <td style="vertical-align:top;">
              <table border="0" style="width:100%; font-size:10px;">
                  <tr>
                      <td class="info"><b>Medical Leave Entitled for Year 2018</b> : </td><td class="info">{{$sickbalance[0]}}</td>
                  </tr>

                  <tr>
                      <td class="info"><b>Medical Leave taken todate</b> : </td><td class="info">{{$sickbalance[1]}}</td>
                  </tr>

                  <tr>
                      <td class="info"><b>Medical Leave Pro-rate Entitled as todate</b> : </td><td class="info">{{$sickbalance[3]}}</td>
                  </tr>

                  <tr>
                      <td class="info"><b>Medical Leave Leave balance (days)</b> : </td><td class="info">{{$sickbalance[2]}}</td>
                  </tr>

              </table>
            </td>

            <td style="vertical-align:top;">
              <table border="0" style="width:100%; font-size:10px;">

                @foreach ($leavetaken as $leave)

                  <tr>
                      <td class="info"><b>{{$leave->Option}}</b> : </td><td class="info">{{$leave->count}}</td>
                  </tr>

                @endforeach

              </table>
            </td>
          </tr>

      </table>

      <table class="attendance" border="0" style="width:100%; font-size:10px;">

        <tr>

            <th style="background-color:grey">1H</th>
            <th style="background-color:grey">2H</th>
            <th style="background-color:grey">H</th>
            <th style="background-color:grey">AL</th>
            <th style="background-color:grey">EL</th>
            <th style="background-color:grey">MARL</th>
            <th style="background-color:grey">MATL</th>
            <th style="background-color:grey">ML</th>
            <th style="background-color:grey">RL</th>
            <th style="background-color:grey">UL</th>

            @for ($i=0; $i <6 ; $i++)


              <th>Mon</th>
              <th>Tue</th>
              @if ($i<5)

                <th>Wed</th>
                <th>Thu</th>
                <th>Fri</th>
                <th style="background-color:grey">Sat</th>
                <th style="background-color:grey">Sun</th>

              @endif


            @endfor

        </tr>

        <?php
          $h1t=0;
          $h2t=0;
          $ht=0;
          $alt=0;
          $elt=0;
          $marlt=0;
          $matlt=0;
          $mlt=0;
          $rlt=0;
          $ult=0;
        ?>

        @foreach ($months as $key => $value)

          <tr>
              <th style="background-color:grey" colspan="10">{{$value}} 2018</th>

              <?php $day=1; $start=date('d-M-Y', strtotime(date('Y-'.$key.'-01'))); $startTime = strtotime($start); ?>
              @for ($i=0; $i <6 ; $i++)

                @for ($j=1; $j <8 ; $j++)

                  @if(date( "N", $startTime)==$j)

                    @if (date( "w", $startTime)==6 || date( "w", $startTime)==0)
                      <th style="background-color:grey">{{$day}}</th>
                    @else
                      <th>{{$day}}</th>
                    @endif


                    <?php

                        $startTime = strtotime('+1 day',$startTime);

                        if(date('d',$startTime)=="01")
                        {
                          $startTime=0;
                          break;
                        }
                        else {
                          $day++;
                        }

                    ?>
                  @else
                    <th style="background-color:black"></th>

                  @endif

                @endfor

                <?php

                    if($startTime==0)
                    {
                      break;
                    }

                ?>

              @endfor

          </tr>

          <tr>

            <?php
              $h1=0;
              $h2=0;
              $h=0;
              $al=0;
              $el=0;
              $marl=0;
              $matl=0;
              $ml=0;
              $rl=0;
              $ul=0;
            ?>
            @foreach ($leaves as $item)

              @if (date('M', strtotime(date('Y-'.$key.'-01')))==date('M', strtotime($item->Start)))
                @if ($item->Leave_Type=="1 Hour Time Off")
                  <?php $h1+=$item->No_Of_Days; $h1t+=$item->No_Of_Days;?>
                @elseif ($item->Leave_Type=="2 Hours Time Off")
                  <?php $h2+=$item->No_Of_Days; $h2t+=$item->No_Of_Days;?>
                @elseif ($item->Leave_Term=="Annual Leave" && str_contains($item->Leave_Term,"Half Day"))
                  <?php $h+=$item->No_Of_Days; $ht+=$item->No_Of_Days;?>
                @elseif ($item->Leave_Type=="Annual Leave")
                  <?php $al+=$item->No_Of_Days; $alt+=$item->No_Of_Days;?>
                @elseif ($item->Leave_Type=="Emergency Leave")
                  <?php $el+=$item->No_Of_Days; $elt+=$item->No_Of_Days;?>
                @elseif ($item->Leave_Type=="Marriage Leave")
                  <?php $marl+=$item->No_Of_Days; $marlt+=$item->No_Of_Days;?>
                @elseif ($item->Leave_Type=="Maternity Leave")
                  <?php $matl+=$item->No_Of_Days; $matlt+=$item->No_Of_Days;?>
                @elseif ($item->Leave_Type=="Medical Leave")
                  <?php $ml+=$item->No_Of_Days; $mlt+=$item->No_Of_Days;?>
                @elseif ($item->Leave_Type=="Replacement Leave")
                  <?php $rl+=$item->No_Of_Days; $rlt+=$item->No_Of_Days;?>
                @elseif ($item->Leave_Type=="Unpaid Leave")
                  <?php $ul+=$item->No_Of_Days; $ult+=$item->No_Of_Days; ?>
                @endif
              @endif

            @endforeach


              <th <?php if ($h1>0) echo "style='background-color:yellow'";?>>{{$h1}}</th>
              <th <?php if ($h2>0) echo "style='background-color:yellow'";?>>{{$h2}}</th>
              <th <?php if ($h>0) echo "style='background-color:yellow'";?>>{{$h}}</th>
              <th <?php if ($al>0) echo "style='background-color:yellow'";?>>{{$al}}</th>
              <th <?php if ($el>0) echo "style='background-color:yellow'";?>>{{$el}}</th>
              <th <?php if ($marl>0) echo "style='background-color:yellow'";?>>{{$marl}}</th>
              <th <?php if ($matl>0) echo "style='background-color:yellow'";?>>{{$matl}}</th>
              <th <?php if ($ml>0) echo "style='background-color:yellow'";?>>{{$ml}}</th>
              <th <?php if ($rl>0) echo "style='background-color:yellow'";?>>{{$rl}}</th>
              <th <?php if ($ul>0) echo "style='background-color:yellow'";?>>{{$ul}}</th>

              <?php $day=1; $start=date('d-M-Y', strtotime(date('Y-'.$key.'-01'))); $startTime = strtotime($start); ?>
              @for ($i=0; $i <6 ; $i++)

                @for ($j=1; $j <8 ; $j++)

                  @if(date( "N", $startTime)==$j)

                    {{-- @if (date('d-M-Y', strtotime(date('Y-'.$key.'-'.$day)))

                    @endif --}}
                    <?php $display="-"; ?>
                    @foreach ($leaves as $item)

                      @if (strtotime(date('Y-'.$key.'-'.$day))>=strtotime($item->Start) && strtotime(date('Y-'.$key.'-'.$day))<=strtotime($item->End_Date))
                        @if ($item->Type=="Leave")
                          @if (str_contains($item->Leave_Term,"Half Day") && $item->Leave_Term)
                              <?php $display="H"; ?>
                          @else
                              <?php $display=$item->Leave_Type; ?>
                          @endif

                        @else
                          <?php $display="PH"; ?>
                        @endif
                      @endif

                    @endforeach

                      @if ($display=="PH")
                        <th style="background-color:yellow">{{$display}}</th>
                      @elseif ($display=="1 Hour Time Off")
                        <th style="background-color:orange">1H</th>
                      @elseif ($display=="2 Hours Time Off")
                        <th style="background-color:orange">2H</th>
                      @elseif ($display=="H")
                        <th style="background-color:orange">H</th>
                      @elseif ($display=="Annual Leave")
                        <th style="background-color:orange">AL</th>
                      @elseif ($display=="Emergency Leave")
                        <th style="background-color:orange">EL</th>
                      @elseif ($display=="Marriage Leave")
                        <th style="background-color:orange">MARL</th>
                      @elseif ($display=="Maternity Leave")
                        <th style="background-color:orange">MATL</th>
                      @elseif ($display=="Medical Leave")
                        <th style="background-color:orange">ML</th>
                      @elseif ($display=="Replacement Leave")
                        <th style="background-color:orange">RL</th>
                      @elseif ($display=="Unpaid Leave")
                        <th style="background-color:orange">UL</th>
                      @elseif (date( "w", $startTime)==6 && $user->Working_Days==5)
                        <th style="background-color:grey"></th>
                      @elseif (date( "w", $startTime)==0)
                        <th style="background-color:grey"></th>
                      @else
                        <th></th>
                      @endif

                    <?php

                        $startTime = strtotime('+1 day',$startTime);

                        if(date('d',$startTime)=="01")
                        {
                          $startTime=0;
                          break;
                        }
                        else {
                          $day++;
                        }

                    ?>
                  @else
                    <th style="background-color:black"></th>

                  @endif

                @endfor

                <?php

                    if($startTime==0)
                    {
                      break;
                    }

                ?>

              @endfor

          </tr>

        @endforeach

        <tr>
            <th style="background-color:grey" colspan="10">Total</th>
        </tr>

        <tr>
          <th <?php if ($h1t>0) echo "style='background-color:yellow'";?>>{{$h1t}}</th>
          <th <?php if ($h2t>0) echo "style='background-color:yellow'";?>>{{$h2t}}</th>
          <th <?php if ($ht>0) echo "style='background-color:yellow'";?>>{{$ht}}</th>
          <th <?php if ($alt>0) echo "style='background-color:yellow'";?>>{{$alt}}</th>
          <th <?php if ($elt>0) echo "style='background-color:yellow'";?>>{{$elt}}</th>
          <th <?php if ($marlt>0) echo "style='background-color:yellow'";?>>{{$marlt}}</th>
          <th <?php if ($matlt>0) echo "style='background-color:yellow'";?>>{{$matlt}}</th>
          <th <?php if ($mlt>0) echo "style='background-color:yellow'";?>>{{$mlt}}</th>
          <th <?php if ($rlt>0) echo "style='background-color:yellow'";?>>{{$rlt}}</th>
          <th <?php if ($ult>0) echo "style='background-color:yellow'";?>>{{$ult}}</th>
        </tr>

      </table>

      <table border="0" style="width:100%; font-size:12px;">
          <tr>
              <th style="width:33%;Color:white;background-color:grey;" colspan="3">PUBLIC HOLIDAYS FOR YEAR 2018</th>

          </tr>

          <tr>
            <td style="vertical-align:top;">
              <table border="0" style="width:100%; font-size:10px;">
                  <tr>

                    <td style="width:16.6%;vertical-align:top;">

                      <table border="0" style="width:100%; font-size:10px;">

                          <?php $bl=false; ?>
                          @foreach ($holidays as $day)

                            @if (str_contains($day->Start_Date, 'Jan'))

                              <tr>
                                  <td class="info" style="width:40%;">{{date("F", strtotime($day->Start_Date))}}</td>
                                  <td class="info" style="width:10%;">{{date("j", strtotime($day->Start_Date))}}</td>
                                  <td class="info" style="width:50%;">{{$day->Holiday}}</td>
                                  <?php $bl=true; ?>
                              </tr>

                            @endif

                          @endforeach

                          @if ($bl==false)

                            <tr>
                                <td class="info" style="width:40%;">January</td>
                                <td class="info" style="width:10%;">-</td>
                                <td class="info" style="width:50%;"></td>
                            </tr>

                          @endif

                      </table>

                    </td>

                    <td style="width:16.6%;vertical-align:top;">

                      <table border="0" style="width:100%; font-size:10px;">

                          <?php $bl=false; ?>
                          @foreach ($holidays as $day)

                            @if (str_contains($day->Start_Date, 'Feb'))

                              <tr>
                                  <td class="info" style="width:40%;">{{date("F", strtotime($day->Start_Date))}}</td>
                                  <td class="info" style="width:10%;">{{date("j", strtotime($day->Start_Date))}}</td>
                                  <td class="info" style="width:50%;">{{$day->Holiday}}</td>
                                  <?php $bl=true; ?>
                              </tr>

                            @endif

                          @endforeach

                          @if ($bl==false)

                            <tr>
                                <td class="info" style="width:40%;">Febraury</td>
                                <td class="info" style="width:10%;">-</td>
                                <td class="info" style="width:50%;"></td>
                            </tr>

                          @endif

                      </table>

                    </td>

                    <td style="width:16.6%;vertical-align:top;">

                      <table border="0" style="width:100%; font-size:10px;">

                          <?php $bl=false; ?>
                          @foreach ($holidays as $day)

                            @if (str_contains($day->Start_Date, 'Mar'))

                              <tr>
                                  <td class="info" style="width:40%;">{{date("F", strtotime($day->Start_Date))}}</td>
                                  <td class="info" style="width:10%;">{{date("j", strtotime($day->Start_Date))}}</td>
                                  <td class="info" style="width:50%;">{{$day->Holiday}}</td>
                                  <?php $bl=true; ?>
                              </tr>

                            @endif

                          @endforeach

                          @if ($bl==false)

                            <tr>
                                <td class="info" style="width:40%;">March</td>
                                <td class="info" style="width:10%;">-</td>
                                <td class="info" style="width:50%;"></td>
                            </tr>

                          @endif

                      </table>

                    </td>


                    <td style="width:16.6%;vertical-align:top;">

                      <table border="0" style="width:100%; font-size:10px;">

                          <?php $bl=false; ?>
                          @foreach ($holidays as $day)

                            @if (str_contains($day->Start_Date, 'Apr'))

                              <tr>
                                  <td class="info" style="width:40%;">{{date("F", strtotime($day->Start_Date))}}</td>
                                  <td class="info" style="width:10%;">{{date("j", strtotime($day->Start_Date))}}</td>
                                  <td class="info" style="width:50%;">{{$day->Holiday}}</td>
                                  <?php $bl=true; ?>
                              </tr>

                            @endif

                          @endforeach

                          @if ($bl==false)

                            <tr>
                                <td class="info" style="width:40%;">April</td>
                                <td class="info" style="width:10%;">-</td>
                                <td class="info" style="width:50%;"></td>
                            </tr>

                          @endif

                      </table>

                    </td>

                    <td style="width:16.6%;vertical-align:top;">

                      <table border="0" style="width:100%; font-size:10px;">

                          <?php $bl=false; ?>
                          @foreach ($holidays as $day)

                            @if (str_contains($day->Start_Date, 'May'))

                              <tr>
                                  <td class="info" style="width:40%;">{{date("F", strtotime($day->Start_Date))}}</td>
                                  <td class="info" style="width:10%;">{{date("j", strtotime($day->Start_Date))}}</td>
                                  <td class="info" style="width:50%;">{{$day->Holiday}}</td>
                                  <?php $bl=true; ?>
                              </tr>

                            @endif

                          @endforeach

                          @if ($bl==false)

                            <tr>
                                <td class="info" style="width:40%;">May</td>
                                <td class="info" style="width:10%;">-</td>
                                <td class="info" style="width:50%;"></td>
                            </tr>

                          @endif

                      </table>

                    </td>

                    <td style="width:16.6%;vertical-align:top;">

                      <table border="0" style="width:100%; font-size:10px;">

                          <?php $bl=false; ?>
                          @foreach ($holidays as $day)

                            @if (str_contains($day->Start_Date, 'Jun'))

                              <tr>
                                  <td class="info" style="width:40%;">{{date("F", strtotime($day->Start_Date))}}</td>
                                  <td class="info" style="width:10%;">{{date("j", strtotime($day->Start_Date))}}</td>
                                  <td class="info" style="width:50%;">{{$day->Holiday}}</td>
                                  <?php $bl=true; ?>
                              </tr>

                            @endif

                          @endforeach

                          @if ($bl==false)

                            <tr>
                                <td class="info" style="width:40%;">June</td>
                                <td class="info" style="width:10%;">-</td>
                                <td class="info" style="width:50%;"></td>
                            </tr>

                          @endif

                      </table>

                    </td>

                  </tr>

                  <tr>

                      <td style="width:16.6%;vertical-align:top;">

                        <table border="0" style="width:100%; font-size:10px;">

                            <?php $bl=false; ?>
                            @foreach ($holidays as $day)

                              @if (str_contains($day->Start_Date, 'Jul'))

                                <tr>
                                    <td class="info" style="width:40%;">{{date("F", strtotime($day->Start_Date))}}</td>
                                    <td class="info" style="width:10%;">{{date("j", strtotime($day->Start_Date))}}</td>
                                    <td class="info" style="width:50%;">{{$day->Holiday}}</td>
                                    <?php $bl=true; ?>
                                </tr>

                              @endif

                            @endforeach

                            @if ($bl==false)

                              <tr>
                                  <td class="info" style="width:40%;">July</td>
                                  <td class="info" style="width:10%;">-</td>
                                  <td class="info" style="width:50%;"></td>
                              </tr>

                            @endif

                        </table>

                      </td>

                      <td style="width:16.6%;vertical-align:top;">

                        <table border="0" style="width:100%; font-size:10px;">

                            <?php $bl=false; ?>
                            @foreach ($holidays as $day)

                              @if (str_contains($day->Start_Date, 'Aug'))

                                <tr>
                                    <td class="info" style="width:40%;">{{date("F", strtotime($day->Start_Date))}}</td>
                                    <td class="info" style="width:10%;">{{date("j", strtotime($day->Start_Date))}}</td>
                                    <td class="info" style="width:50%;">{{$day->Holiday}}</td>
                                    <?php $bl=true; ?>
                                </tr>

                              @endif

                            @endforeach

                            @if ($bl==false)

                              <tr>
                                  <td class="info" style="width:40%;">August</td>
                                  <td class="info" style="width:10%;">-</td>
                                  <td class="info" style="width:50%;"></td>
                              </tr>

                            @endif

                        </table>

                      </td>

                      <td style="width:16.6%;vertical-align:top;">

                        <table border="0" style="width:100%; font-size:10px;">

                            <?php $bl=false; ?>
                            @foreach ($holidays as $day)

                              @if (str_contains($day->Start_Date, 'Sep'))

                                <tr>
                                    <td class="info" style="width:40%;">{{date("F", strtotime($day->Start_Date))}}</td>
                                    <td class="info" style="width:10%;">{{date("j", strtotime($day->Start_Date))}}</td>
                                    <td class="info" style="width:50%;">{{$day->Holiday}}</td>
                                    <?php $bl=true; ?>
                                </tr>

                              @endif

                            @endforeach

                            @if ($bl==false)

                              <tr>
                                  <td class="info" style="width:40%;">September</td>
                                  <td class="info" style="width:10%;">-</td>
                                  <td class="info" style="width:50%;"></td>
                              </tr>

                            @endif

                        </table>

                      </td>

                      <td style="width:16.6%;vertical-align:top;">

                        <table border="0" style="width:100%; font-size:10px;">

                            <?php $bl=false; ?>
                            @foreach ($holidays as $day)

                              @if (str_contains($day->Start_Date, 'Oct'))

                                <tr>
                                    <td class="info" style="width:40%;">{{date("F", strtotime($day->Start_Date))}}</td>
                                    <td class="info" style="width:10%;">{{date("j", strtotime($day->Start_Date))}}</td>
                                    <td class="info" style="width:50%;">{{$day->Holiday}}</td>
                                    <?php $bl=true; ?>
                                </tr>

                              @endif

                            @endforeach

                            @if ($bl==false)

                              <tr>
                                  <td class="info" style="width:40%;">October</td>
                                  <td class="info" style="width:10%;">-</td>
                                  <td class="info" style="width:50%;"></td>
                              </tr>

                            @endif

                        </table>

                      </td>

                      <td style="width:16.6%;vertical-align:top;">

                        <table border="0" style="width:100%; font-size:10px;">

                            <?php $bl=false; ?>
                            @foreach ($holidays as $day)

                              @if (str_contains($day->Start_Date, 'Nov'))

                                <tr>
                                    <td class="info" style="width:40%;">{{date("F", strtotime($day->Start_Date))}}</td>
                                    <td class="info" style="width:10%;">{{date("j", strtotime($day->Start_Date))}}</td>
                                    <td class="info" style="width:50%;">{{$day->Holiday}}</td>
                                    <?php $bl=true; ?>
                                </tr>

                              @endif

                            @endforeach

                            @if ($bl==false)

                              <tr>
                                  <td class="info" style="width:40%;">November</td>
                                  <td class="info" style="width:10%;">-</td>
                                  <td class="info" style="width:50%;"></td>
                              </tr>

                            @endif

                        </table>

                      </td>

                    <td style="width:16.6%;vertical-align:top;">

                      <table border="0" style="width:100%; font-size:10px;">

                          <?php $bl=false; ?>
                          @foreach ($holidays as $day)

                            @if (str_contains($day->Start_Date, 'Dec'))

                              <tr>
                                  <td class="info" style="width:40%;">{{date("F", strtotime($day->Start_Date))}}</td>
                                  <td class="info" style="width:10%;">{{date("j", strtotime($day->Start_Date))}}</td>
                                  <td class="info" style="width:50%;">{{$day->Holiday}}</td>
                                  <?php $bl=true; ?>
                              </tr>

                            @endif

                          @endforeach

                          @if ($bl==false)

                            <tr>
                                <td class="info" style="width:40%;">December</td>
                                <td class="info" style="width:10%;">-</td>
                                <td class="info" style="width:50%;"></td>
                            </tr>

                          @endif

                      </table>

                    </td>

                </tr>

              </table>
            </td>

      </table>

    </div>

</div>

</body>

</html>
