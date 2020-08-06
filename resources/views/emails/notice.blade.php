<html>
<head>
<meta charset="utf-8">
<title>{{$notice->Title}}</title>
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
                                    {{$notice->Title}}
                                </p>

                                  {{$notice->Content}}<br>

                                  <center>

                                  @if($notice->FileName)
                                   <?php
                                     $split=explode("|",$notice->FileName);
                                     $split2=explode("|",$notice->Attachment);
                                     $j=0;

                                     foreach ($split as $value) {

                                       if (strpos(strtoupper($split[$j]), '.PNG') !== false || strpos(strtoupper($split[$j]), '.JPG') !== false || strpos(strtoupper($split[$j]), '.JPEG') !== false)
                                       {

                                         ?>
                                          <a download="."{{ URL::to('/') .$split[$j]  }}"." href="{{ URL::to('/') .$split2[$j]  }}" title='Download'><img class='img-responsive' src="{{ URL::to('/') .$split2[$j]  }}" alt='Photo'></a>
                                         <?php
                                       }
                                       else {
                                         ?>
                                          <a download="{{ URL::to('/') .$split[$j]  }}" href="{{ URL::to('/') .$split2[$j]  }}" title='Download'>Download&nbsp;&nbsp;"{{ URL::to('/') .$split[$j]  }}"</a>
                                          <?php
                                       }

                                       $j++;
                                     }


                                   ?>
                                  @endif

                                  </center>

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
