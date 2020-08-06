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
                                    {{$count->count}} Genset Spare Part(s) Are Low In Quantity
                                </p>

                                <table border="1" width="100%" class="table table-bordered">
                                    <thead>
                                        <tr>
                                            <th>Item Name</th>
                                            <th>Item Code</th>
                                            <th>Treshold Quantity</th>
                                            <th>Balance Quantity</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                         @foreach ($item as $i)
                                        <tr>
                                            <td>{{$i->name}}</td>
                                            <td>{{$i->machinery_no}}</td>
                                            <td>{{$i->balance_treshold}}</td>
                                            <td>{{$i->qty_balance}}</td>
                                        </tr>
                                        @endforeach
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
