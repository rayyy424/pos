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
                                <p style="margin:0 0 36px; text-align:center; font-size:16px; line-height:22px; text-transform:uppercase; color:#626658;">
                                    Unprocessed Claim and Timesheet [{{$entry->StaffId}} {{$entry->Name}}] [{{$month}} {{$year}} {{$start}} To {{$end}}]
                                </p>

                                <p style="margin:0 0 15px; text-align:left; font-size:14px; line-height:22px; text-transform:uppercase; color:red;">
                                    Note :
                                      Claim without Timesheet - Not process<br>
                                      Incomplete Timesheet - Not process<br>
                                      Rejected Claim - Not process<br>
                                      Rejected Timesheet - Not process<br>
                                      Claim Pending Submission - Not process<br>
                                      Timesheet Pending Submission - Not process<br>
                                      Claim not Final Approved - Not process<br>
                                      Timesheet not Final Approved - Not process<br>
                                      No hardcopy receipt - Not process<br>
                                      Receipt not uploaded - Not process<br>
                                      Late Submission - Not process<br>
                                      Late Approval - Not process

                                </p>

                                <table cellpadding="0" cellspacing="0" border="0" width="100%">
                                    <tbody>

                                        <tr>
                                            <td colspan="3" rowspan="3" bgcolor="#FFFFFF" style="padding:30px 30px;border-radius: 10px;font-size:14px;line-height:22px;">
                                                <table cellpadding="3px" cellspacing="5px" border="0" width="100%">
                                                    <tbody>

                                                      <tr>

                                                            @foreach($entry as $field=>$value)

                                                              @if($field!="Id")
                                                                <th/>{{ $field }}</th>
                                                              @endif
                                                            @endforeach

                                                      </tr>

                                                        <tr>
                                                            @foreach($entry as $key=>$value)

                                                                @if($key!="Id")
                                                                  <td>
                                                                  {{ $value }}
                                                                  </td>
                                                                @endif

                                                            @endforeach
                                                        </tr>

                                                    </tbody>
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
