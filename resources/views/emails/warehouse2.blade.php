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
                                  <img src="{{ URL::to('/') ."/img/logo.png"  }}" width="407" height="100" alt="Midascom Network Sdn Bhd" style="display:block; border:0; margin:0;">
                              </a>
                              <br>
                                <p style="margin:0 0 36px; text-align:center; font-size:16px; line-height:22px; text-transform:uppercase; color:#626658;">
                                    Delivery order
                                </p>
                                <table cellpadding="0" cellspacing="0" border="0" width="100%">
                                    <tbody>

                                        <tr>
                                            <td colspan="3" rowspan="3" bgcolor="#FFFFFF" style="padding:30px 30px;border-radius: 10px;font-size:14px;line-height:22px;">
                                                <table cellpadding="0" cellspacing="0" border="0" width="100%">
                                                    <tbody>

                                                        <tr valign="top">
                                                            <td style="width:45%;text-align:right;;">DO NO</td>
                                                            <td style="width:10%;text-align:center;">:</td>
                                                            <td style="width:45%;text-align:left;">{{$deliverydetail->DO_No}}</td>
                                                        </tr>

                                                        <tr valign="top">
                                                            <td style="width:45%;text-align:right;;">Requestor Name</td>
                                                            <td style="width:10%;text-align:center;">:</td>
                                                            <td style="width:45%;text-align:left;">{{$deliverydetail->requestorName}}</td>
                                                        </tr>
                                                        <tr valign="top">
                                                            <td style="width:45%;text-align:right;;">Purpose</td>
                                                            <td style="width:10%;text-align:center;">:</td>
                                                            <td style="width:45%;text-align:left;">{{$deliverydetail->Option}}</td>
                                                        </tr>

                                                        <tr valign="top">
                                                            <td style="width:45%;text-align:right;;">Delivery Date</td>
                                                            <td style="width:10%;text-align:center;">:</td>
                                                            <td style="width:45%;text-align:left;">{{$deliverydetail->delivery_date}}</td>
                                                        </tr>

                                                        <tr valign="top">
                                                            <td style="width:45%;text-align:right;;">Remarks</td>
                                                            <td style="width:10%;text-align:center;">:</td>
                                                            <td style="width:45%;text-align:left;">{{$deliverydetail->Remarks}}</td>
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
