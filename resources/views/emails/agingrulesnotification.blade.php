<html>
<head>
<meta charset="utf-8">
<title></title>
</head>
<body style="font-family:Gotham, 'Helvetica Neue', Helvetica, Arial, sans-serif; background-color:#f0f2ea; margin:0; padding:0; color:#6d6969;">

<table width="100%" cellpadding="0" cellspacing="0" border="0">
  <tbody>
      <tr>
          <td style="padding:40px 0;">
              <table cellpadding="0" cellspacing="0" width="608" border="0" align="center">
                  <tbody>
                      <tr align="center">
                          <td>
                            <a href="" style="display:block; width:587px; height:122px; margin:0 auto 30px;">
                                <img src="{{ URL::to('/') ."/img/logo.png"  }}" width="587" height="122" alt="Midascom Network Sdn Bhd" style="display:block; border:0; margin:0;">
                            </a>
                              <br>
                                <p style="margin:0 0 36px; text-align:left; font-size:16px; line-height:22px; text-transform:uppercase; color:#626658;">
                                    <h1>Aging Notification : {{$rule->Title}} [{{ date("d-M-Y")}}]</h1>

                                    <h3>Start Date : {{$rule->Start_Date}}</h5>
                                    <h3>End Date : {{$rule->End_Date}}</h5>
                                    <h3>Threshold : {{$rule->Threshold}} days</h5>
                                </p>

                                <table cellpadding="0" cellspacing="0" border="0" width="100%">
                                    <tbody>

                                        <tr>
                                            <td colspan="3" rowspan="3" bgcolor="#FFFFFF" style="padding:30px 30px;border-radius: 10px;font-size:14px;line-height:22px;">
                                                <table cellpadding="2px" cellspacing="1px" border="1" width="100%">

                                                      @if (sizeof($agings)>0)

                                                        <tr>
                                                          @foreach($agings as $key=>$value)

                                                            @if ($key==0)
                                                              <th bgcolor="#A4A3A3">No</th>
                                                              @foreach($value as $field=>$value)
                                                                  <th bgcolor="#A4A3A3">{{ $field }}</th>
                                                              @endforeach

                                                            @endif

                                                          @endforeach
                                                        </tr>

                                                        <tbody>

                                                          <?php $i = 1; ?>

                                                          @foreach($agings as $key=>$value)

                                                            <tr>
                                                              <td>{{$i}}</td>
                                                              @foreach($value as $field=>$value)
                                                                @if($field=="Aging (days)")
                                                                    @if($value>=$rule->Threshold)
                                                                      <td style="text-align:center;white-space: nowrap;" ><font color="red">{{ $value }}</font></td>
                                                                    @else
                                                                      <td style="text-align:center;white-space: nowrap;">{{ $value }}</td>
                                                                    @endif

                                                                @else
                                                                    <td>{{ $value }}</td>
                                                                @endif

                                                              @endforeach
                                                            </tr>

                                                            <?php $i++; ?>

                                                          @endforeach

                                                      </tbody>

                                                      @else

                                                        <tr>
                                                          <td style="text-align:center;white-space: nowrap;">No overdue item.</td>
                                                        </tr>

                                                      @endif

                                                </table>
                                            </td>
                                        </tr>

                                    </tbody>
                                </table>
                                <p style="margin:0; padding:34px 0 0; text-align:center; font-size:12px; line-height:13px; color:#333333;">
                                    Copyright © 2017 Softoya International Sdn Bhd
                                </p>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </td>
        </tr>
    </tbody>
</table>
</body>
</html>
