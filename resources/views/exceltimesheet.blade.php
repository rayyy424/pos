<html>

<body>
   <h3>Timesheets - [{{ $start }} - {{ $end }}] </h3>

    <table border="0" style="width:100%; font-size:12px;padding:10px;padding-left:30px;">
        <tr><td><b>Staff ID</b></td><td>{{$user->StaffId}}</td></tr>
        <tr><td><b>Name</b></td><td>{{$user->Name}}</td></tr>
        <tr><td><b>Department</b></td><td>{{$user->Department}}</td></tr>
        <tr><td><b>Position</b></td><td>{{$user->Position}}</td></tr>
        <tr><td><b>Nationality</b></td><td>{{$user->Nationality}}</td></tr>
        <tr><td><b>Home Base</b></td><td>{{$user->Home_Base}}</td></tr>
        </tr>
    </table>

    <table id="t01">
        <thead>
            <tr>
                @foreach($timesheetdetail as $key=>$value)

                    @if ($key==0)

                    @foreach($value as $field=>$value)
                    <th>{{ $field }}</th>
                    @endforeach

                    @endif

                @endforeach

            </tr>
        </thead>
        <?php $i = 0; ?>
        @foreach($timesheetdetail as $timesheetdetails)

            <tr id="row_{{ $i }}" >
                @foreach($timesheetdetails as $key=>$value)
                <td>
                    {{ $value }}
                </td>
                @endforeach
            </tr>
            <?php $i++; ?>

        @endforeach

    </table>
    
    <table border="0" style="width:40%;font-size:12px;">
        <tr>
            <td><b>Total Allowance</b> : </td>
             @foreach($total as $sum)
                <td>RM {{$sum->TotalAllowance}}</td>
            @endforeach
        </tr>
        
    </table>

</body>
</html>
