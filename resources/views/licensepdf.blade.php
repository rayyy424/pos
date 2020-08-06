<html>

<head>
    <style>
        body {
            margin: 0;
            width:100%;
        }
        .container {
            width: 100%;
            margin:0;
        }
        .row{
        padding:10px;
        }
        .row h2,h4{
            padding: 0;
            margin: 0;
            padding-bottom:10px;
        }
        header, footer {
            color: white;
            bacackground-color: #d00f01;
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
            font-size:14px;
        }

        td.imagebox img{
           width:50px;
        }

        table#t01 tr:nth-child(even) {
            background-color: #eee;
        }
        table#t01 tr:nth-child(odd) {
           background-color:#fff;
        }
        table#t01 th {
            background-color: #3b3d3e;
            color: white;
            text-align: center;
        }
        table#t01 td {
            text-align: center;
        }
        .profilepic{
            text-align:center;
        }
        .profilepic img{
            width:100px;
            border-radius:50%;
        }
        @page { margin: 80px 50px; }
            #header { position: fixed; left: 0px; top: -60px; right: 0px; height: 60px;   }
            #footer { position: fixed; left: 0px; bottom: -80px; right: 0px; height: 40px;  text-align: right;padding:20px; }
            #header img { float:right; width:60px; padding:10px;}
            #footer .page:after { content: counter(page); }

    </style>

</head>

<body>

  <div id="header">
      <span>Exported at : {{date('d-M-Y h:i:s A')}}</span>
      <img src="http://totg.midascom.my/img/logo.png"></img>
    </div>

      <div id="footer">
        <p class="page"></p>
      </div>

    <div class="container">

      <div class="row">
              <h2>License Details</h2>
      </div>

      @if($license)
      <div class="row">

        <table id="t01">
            <thead>
            <tr>
             @foreach($license as $key=>$value)

                @if ($key==0)

                @foreach($value as $field=>$value)
                <th>{{ $field }}</th>
                @endforeach

                @endif

            @endforeach

            </tr>
            </thead>

             <?php $i = 0; ?>
            @foreach($license as $license1)
            <tr>
                 @foreach($license1 as $key=>$value)
                    @if($key=='Image')
                      @if($value)
                        <td class="imagebox"><img src="{{ url($value) }}" width="100px"></img></td>
                      @else
                        <td class="imagebox"><img src="{{ URL::to('/') ."/img/default-user.png"  }}" width="100px"></img></td>
                      @endif

                    @elseif($key=='CIDB' || $key=='NIOSH' || $key=='HUAWEI' || $key=='WAH' || $key == 'Web_Path')
                        @if($value)
                          <td class="imagebox"><img src="{{ url($value) }}" width="200px"></img></td>
                        @else
                          <td></td>
                        @endif

                    @else
                      <td>{{ $value }}</td>
                    @endif

                @endforeach
            </tr>
             @endforeach
        </table>

      </div>
      @endif


    </div>

</body>

</html>
