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
                                    {{$title}}
                                </p>
                                <table cellpadding="0" cellspacing="0" border="0" width="100%">
                                    <tbody>

                                        <tr>
                                            <td colspan="3" rowspan="3" bgcolor="#FFFFFF" style="padding:30px 30px;border-radius: 10px;font-size:14px;line-height:22px;">
                                                <table cellpadding="0" cellspacing="0" border="0" width="100%">
                                                    <tbody>

                                                        
                                                    <tr valign="top">
                                                        <td style="width:45%;text-align:right;;">MR NO</td>
                                                        <td style="width:10%;text-align:center;">:</td>
                                                        <td style="width:45%;text-align:left;">{{$materialdetail->MR_No}}</td>
                                                    </tr>   
                                                      <tr valign="top">
                                                          <td style="width:45%;text-align:right;;">Name</td>
                                                          <td style="width:10%;text-align:center;">:</td>
                                                          <td style="width:45%;text-align:left;">{{$materialdetail->Name}}</td>
                                                      </tr>
                                                      <tr valign="top">
                                                        <td style="width:45%;text-align:right;;">Project Name</td>
                                                        <td style="width:10%;text-align:center;">:</td>
                                                        <td style="width:45%;text-align:left;">{{$materialdetail->Project_Name}}</td>
                                                        </tr>
                                                        <tr valign="top">
                                                            <td style="width:45%;text-align:right;;">Site Name</td>
                                                            <td style="width:10%;text-align:center;">:</td>
                                                            <td style="width:45%;text-align:left;">{{$materialdetail->site}}</td>
                                                        </tr>
                                                        <tr valign="top">
                                                            <td style="width:45%;text-align:right;;">Unique ID</td>
                                                            <td style="width:10%;text-align:center;">:</td>
                                                            <td style="width:45%;text-align:left;">{{$materialdetail->uniqueId}}</td>
                                                        </tr>
                                                    </tbody>
                                                </table>

                                                <table cellpadding="0" cellspacing="0" border="0" width="100%">
                                                    <tbody>
                                                      <tr>
                                                        <th style="text-align:center;white-space: nowrap;">Approver</th>
                                                        <th style="text-align:center;white-space: nowrap;">Status</th>
                                                        <th style="text-align:center;white-space: nowrap;">Reason</th>
                                                      </tr>
                                                      @foreach ($log as $l)

                                                        <tr valign="top">
                                                            <td style="text-align:center;white-space: nowrap;">{{$l->Name == null ? "-":$l->Name}}</td>
                                                            <td style="text-align:center;white-space: nowrap;">{{$l->Status}}</td>
                                                            <td style="text-align:center;white-space: nowrap;">{{$l->Status == "Rejected" ? $l->Reason:"-"}}</td>
                                                      @endforeach
                                                    </tbody>
                                                </table>

                                                <table cellpadding="0" cellspacing="0" border="0" width="100%">
                                                    <tbody>
                                                        <tr>
                                                        <th style="text-align:center;white-space: nowrap;">Item Code</th>
                                                        <th style="text-align:center;white-space: nowrap;">Description</th>
                                                        <th style="text-align:center;white-space: nowrap;">Unit</th>
                                                        <th style="text-align:center;white-space: nowrap;">Qty</th>
                                                        <th style="text-align:center;white-space: nowrap;">Price</th>
                                                        <th style="text-align:center;white-space: nowrap;">Total</th>
                                                        </tr>
                                                        <?php $sum=0;?>
                                                        @foreach ($item as $i)

                                                        <tr valign="top">
                                                            <td style="text-align:center;white-space: nowrap;">{{$i->Item_Code}}</td>
                                                            <td style="text-align:center;white-space: nowrap;">{{$i->Description}}</td>
                                                            <td style="text-align:center;white-space: nowrap;">{{$i->Unit}}</td>
                                                            <td style="text-align:center;white-space: nowrap;">{{$i->Qty}}</td>
                                                            <td style="text-align:center;white-space: nowrap;">{{$i->Price}}</td>
                                                            <?php $total=0;
                                                             $total=($i->Qty*$i->Price);
                                                                $sum+=$total;
                                                            ?>
                                                            <td style="text-align:center;white-space: nowrap;">{{number_format($total,2)}}</td>
                                                        </tr>
                                                        @endforeach
                                                        <tr valign="top">
                                                            <td style="text-align:center;white-space: nowrap;" colspan="5">Total</td>
                                                            <td style="text-align:center;white-space: nowrap;">{{number_format($sum,2)}}</td>
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
