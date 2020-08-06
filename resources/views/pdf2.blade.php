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
    <img src="http://totg.midascom.my/img/logo.png"></img>
  </div>
  <div id="footer">
    <p class="page"></p>
  </div>

<div class="container">
<div class="row">
        <h2>Personal Resume</h2>
</div>



<div class="row">
    <h4>Personal Details</h4>
    <table width="350">
        <tr>
            <td>Name</td>
            <td>:</td>
            <td>{{$user->Name}}</td>
        </tr>
        <tr>
            <td>Gender</td>
            <td>:</td>
            <td>{{$user->Gender}}</td>
        </tr>
        <tr>
            <td>Marital Status</td>
            <td>:</td>
            <td>{{$user->Marital_Status}}</td>
        </tr>
        <tr>
            <td>Nationality</td>
            <td>:</td>
            <td>{{$user->Nationality}}</td>
        </tr>
        <tr>
            <td>Date of Birth</td>
            <td>:</td>
            <td>{{$user->DOB}}</td>
        </tr>
    </table>
</div>

@if(!$experiences)

@else
<div class="row">
    <h4>Experience</h4>

    <table width="350">
         <?php $i = 0; ?>
        @foreach($experiences as $experience)

             @foreach($experience as $key=>$value)
              <tr>
                <td>{{ $key }}</td>
                <td>:</td>
                <td>{{ $value }}</td>
              </tr>
            @endforeach

            <tr>
                <td>&nbsp;</td>
                <td></td>
                <td></td>
            </tr>


         @endforeach
    </table>

</div>
@endif

@if(!$qualifications)

@else
<div class="row">
    <h4>Education</h4>
    <table id="t01">

        <tr>
         @foreach($qualifications as $key=>$value)

            @if ($key==0)

            @foreach($value as $field=>$value)
            <th>{{ $field }}</th>
            @endforeach

            @endif

        @endforeach


        </tr>
         <?php $i = 0; ?>
        @foreach($qualifications as $qualification)
        <tr>
             @foreach($qualification as $key=>$value)
               <td>{{ $value }}</td>
            @endforeach
        </tr>
         @endforeach
    </table>
</div>
@endif

@if(!$skills)

@else
<div class="row">
    <h4>Skill</h4>
    <table id="t01">

        <tr>
         @foreach($skills as $key=>$value)

            @if ($key==0)

            @foreach($value as $field=>$value)
            <th>{{ $field }}</th>
            @endforeach

            @endif

        @endforeach


        </tr>
         <?php $i = 0; ?>
        @foreach($skills as $skill)
        <tr>
             @foreach($skill as $key=>$value)
               <td>{{ $value }}</td>
            @endforeach
        </tr>
         @endforeach
    </table>
</div>
@endif

@if(!$licenses)

@else
<div class="row">
    <h4>License</h4>
    <table id="t01">

        <tr>
         @foreach($licenses as $key=>$value)

            @if ($key==0)

            @foreach($value as $field=>$value)
            <th>{{ $field }}</th>
            @endforeach

            @endif

        @endforeach


        </tr>
         <?php $i = 0; ?>
        @foreach($licenses as $license)
        <tr>
             @foreach($license as $key=>$value)
               <td>{{ $value }}</td>
            @endforeach
        </tr>
         @endforeach
    </table>
</div>
@endif

@if(!$trainings)

@else
<div class="row">
    <h4>Training</h4>
    <table id="t01">

        <tr>
         @foreach($trainings as $key=>$value)

            @if ($key==0)

            @foreach($value as $field=>$value)
            <th>{{ $field }}</th>
            @endforeach

            @endif

        @endforeach


        </tr>
         <?php $i = 0; ?>
        @foreach($trainings as $training)
        <tr>
             @foreach($training as $key=>$value)
               <td>{{ $value }}</td>
            @endforeach
        </tr>
         @endforeach
    </table>
</div>
@endif

@if(!$employmenthistories)

@else
<div class="row">
    <h4>Employment History</h4>
    <table id="t01">

        <tr>
         @foreach($employmenthistories as $key=>$value)

            @if ($key==0)

            @foreach($value as $field=>$value)
            <th>{{ $field }}</th>
            @endforeach

            @endif

        @endforeach


        </tr>
         <?php $i = 0; ?>
        @foreach($employmenthistories as $employmenthistory)
        <tr>
             @foreach($employmenthistory as $key=>$value)
               <td>{{ $value }}</td>
            @endforeach
        </tr>
         @endforeach
    </table>
</div>
@endif


</div>



</body>
</html>
