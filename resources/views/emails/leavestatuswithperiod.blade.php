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
                              <a href="" style="display:block; width:407px; height:100px; margin:0 auto 30px;">
                                  <img src="{{ URL::to('/') ."/img/logo.png"  }}" width="407" height="100" alt="Propel Network" style="display:block; border:0; margin:0;">
                              </a>
                              <br>
                                <p style="margin:0 0 36px; text-align:center; font-size:16px; line-height:22px; text-transform:uppercase; color:#626658;">
                                    Leave Status Updated!
                                </p>
                                <table cellpadding="0" cellspacing="0" border="0" width="100%">
                                    <tbody>

                                        <tr>
                                            <td colspan="3" rowspan="3" bgcolor="#FFFFFF" style="padding:30px 30px;border-radius: 10px;font-size:14px;line-height:22px;">
                                                <table cellpadding="0" cellspacing="0" border="0" width="100%">
                                                    <tbody>

                                                      <tr valign="top">
                                                          <td style="width:45%;text-align:right;;">Name</td>
                                                          <td style="width:10%;text-align:center;">:</td>
                                                          <td style="width:45%;text-align:left;">{{$leavedetail[0]->Name}}</td>
                                                      </tr>
                                                      <tr valign="top">
                                                          <td style="width:45%;text-align:right;;">Leave Type</td>
                                                          <td style="width:10%;text-align:center;">:</td>
                                                          <td style="width:45%;text-align:left;">{{$leavedetail[0]->Leave_Type}}</td>
                                                      </tr>
                                                      <!-- <tr valign="top">
                                                          <td style="width:45%;text-align:right;;">Leave Term</td>
                                                          <td style="width:10%;text-align:center;">:</td>
                                                          <td style="width:45%;text-align:left;">{{$leavedetail[0]->Leave_Term}}</td>
                                                      </tr> -->
                                                      <tr valign="top">
                                                          <td style="width:45%;text-align:right;;">Start Date</td>
                                                          <td style="width:10%;text-align:center;">:</td>
                                                          <td style="width:45%;text-align:left;">{{$leavedetail[0]->Start_Date}}</td>
                                                      </tr>
                                                      <tr valign="top">
                                                          <td style="width:45%;text-align:right;;">End Date</td>
                                                          <td style="width:10%;text-align:center;">:</td>
                                                          <td style="width:45%;text-align:left;">{{$leavedetail[0]->End_Date}}</td>
                                                      </tr>
                                                      <tr valign="top">
                                                          <td style="width:45%;text-align:right;;">Reason</td>
                                                          <td style="width:10%;text-align:center;">:</td>
                                                          <td style="width:45%;text-align:left;">{{$leavedetail[0]->Reason}}</td>
                                                      </tr>
                                                      <tr valign="top">
                                                          <td style="width:45%;text-align:right;;">Application Date</td>
                                                          <td style="width:10%;text-align:center;">:</td>
                                                          <td style="width:45%;text-align:left;">{{$leavedetail[0]->Application_Date}}</td>
                                                      </tr>
                                                      @if($attachmentUrl)
                                                      <tr valign="top">
                                                          <td style="width:45%;text-align:right;;">Attachment</td>
                                                          <td style="width:10%;text-align:center;">:</td>
                                                          <td style="width:45%;text-align:left;"><a target="_blank" href="{{$attachmentUrl}}">Click to view</a></td>
                                                      </tr>
                                                      @endif
                                                    </tbody>
                                                </table>
                                                <hr>
                                                <table cellpadding="0" cellspacing="0" border="0" width="100%">
                                                  <thead>
                                                    <tr>
                                                      <th style="width:15%;text-align:left;">Day</th>
                                                      <th style="width:35%;text-align:left;">Date</th>
                                                      <th style="width:50%;text-align:left;">Period</th>
                                                    </tr>
                                                    @foreach($periods as $no => $period)
                                                    <tr>
                                                      <td>{{ $no+1 }}</td>
                                                      <td>{{ $period->Leave_Date }}</td>
                                                      <td>{{ $period->Leave_Period }}</td>
                                                    </tr>
                                                    @endforeach
                                                  </thead>
                                                  <tbody>
                                                  </tbody>
                                                </table>
                                                <hr>
                                                <table cellpadding="0" cellspacing="0" border="0" width="100%">
                                                    <tbody>
                                                      <tr>
                                                        <th style="text-align:center;white-space: nowrap;">Approver</th>
                                                        <th style="text-align:center;white-space: nowrap;">Status</th>
                                                        <th style="text-align:center;white-space: nowrap;">Comment</th>
                                                        <th style="text-align:center;white-space: nowrap;">Review Date</th>
                                                      </tr>
                                                      @foreach ($leavedetail as $leave)

                                                        <tr valign="top">
                                                            <td style="text-align:center;white-space: nowrap;">{{$leave->Approver}}</td>

                                                            <td style="text-align:center;white-space: nowrap;">{{$leave->Status}}</td>

                                                            <td style="text-align:center;white-space: nowrap;">{{$leave->Comment}}</td>

                                                            <td style="text-align:center;white-space: nowrap;">{{$leave->Review_Date}}</td>
                                                        </tr>

                                                      @endforeach
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
