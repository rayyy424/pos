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
                    @foreach($endNotify as $notification)
                      <tr align="center">
                          <td>
                            <a href="" style="display:block; width:587px; height:122px; margin:0 auto 30px;">
                                <img src="{{ URL::to('/') ."/img/logo.png"  }}" width="587" height="122" alt="Midascom Network Sdn Bhd" style="display:block; border:0; margin:0;">
                            </a>
                              <br>
                                <p style="margin:0 0 36px; text-align:center; font-size:16px; line-height:22px; text-transform:uppercase; color:#626658;">

                                    Return Asset before {{$notification->Rental_End_Date}}!
                                </p>

                                <table cellpadding="0" cellspacing="0" border="0" width="100%">
                                    <tbody>

                                        <tr>
                                            <td colspan="3" rowspan="3" bgcolor="#FFFFFF" style="padding:30px 30px;border-radius: 10px;font-size:14px;line-height:22px;">
                                                <table cellpadding="3px" cellspacing="5px" border="0" width="100%">
                                                    <tbody>

                                                      <tr>
                                                        <th style="text-align:center;white-space: nowrap;">No</th>
                                                        <th style="text-align:center;white-space: nowrap;">Label</th>
                                                        <th style="text-align:center;white-space: nowrap;">Type</th>
                                                        <th style="text-align:center;white-space: nowrap;">Serial_No</th>
                                                        <th style="text-align:center;white-space: nowrap;">IMEI</th>
                                                        <th style="text-align:center;white-space: nowrap;">Model_No</th>
                                                        <th style="text-align:center;white-space: nowrap;">Car_No</th>
                                                        <th style="text-align:center;white-space: nowrap;">Color</th>
                                                        <th style="text-align:center;white-space: nowrap;">Rental_Start_Date</th>
                                                        <th style="text-align:center;white-space: nowrap;">Rental_End_Date</th>
                                                      </tr>

                                                      <?php $i = 1; ?>

                                                      @foreach ($endNotify as $notification1)


                                                          <tr valign="top">
                                                              <td style="text-align:center;white-space: nowrap;">{{ $i }}</td>
                                                              <td style="text-align:center;white-space: nowrap;">{{$notification1->Label}}</td>
                                                              <td style="text-align:center;white-space: nowrap;">{{$notification1->Type}}</td>
                                                              <td style="text-align:center;white-space: nowrap;">{{$notification1->Serial_No}}</td>                                                              <td style="text-align:center;white-space: nowrap;">{{$notification1->Serial_No}}</td>
                                                              <td style="text-align:center;white-space: nowrap;">{{$notification1->IMEI}}</td>
                                                              <td style="text-align:center;white-space: nowrap;">{{$notification1->Model_No}}</td>
                                                              <td style="text-align:center;white-space: nowrap;">{{$notification1->Car_No}}</td>
                                                              <td style="text-align:center;white-space: nowrap;">{{$notification1->Color}}</td>
                                                              <td style="text-align:center;white-space: nowrap;">{{$notification1->Rental_Start_Date}}</td>
                                                              <td style="text-align:center;white-space: nowrap;">{{$notification1->Rental_End_Date}}</td>
                                                          </tr>
                                                          <?php $i++; ?>


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
                      @endforeach
                    </tbody>
                </table>
            </td>
        </tr>
    </tbody>
</table>
</body>
</html>
