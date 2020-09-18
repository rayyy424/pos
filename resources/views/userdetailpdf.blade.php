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

.row1{
  float:left;
  padding:10px;
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
    background-color: #d00f01;
    clear: left;
}
footer {
    padding: 1em;
}
header img{
    float:right;
    width:40px;
    padding:10px;
}
header{
    height:60px;
}
table {
    width:100%;
    font-size:13px;
    border-collapse:collapse;
}

.table-bordered th,td{
  text-align:left;
  border: 1px solid #bdbcbc;
  padding:3px;
}

.table-bordered th{
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
}
table#t01 td {
}
.profilepic img{
    height:180px;
}
@page { margin: 80px 50px; }
    #header { position: fixed; left: 0px; top: -50px; right: 0px; height: 50px;   }
    #footer { position: fixed; left: 0px; bottom: -80px; right: 0px; height: 40px;  text-align: right;padding:20px; }
    #header img { float:right; width:100px; padding:10px;}
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

    <h3>EMPLOYEE PERSONAL DETAILS</h3>

      <div class="row1">
          <div class="profilepic">
           <img src="http://totg.midascom.my/{{$input['Profile_Image']}}"></img>
          </div>
      </div>

      <div class="row1" style="padding-left:200px;">
          <table class="table table-bordered">
            <tbody>
              <tr>
                <th>Staff ID</th><td colspan="3">{{$input["StaffId1"]}}</td>
              </tr>
              <tr>
                <th>Name</th><td colspan="3">{{$input["Name1"]}}</td>
              </tr>
              <tr>
                <th>NRIC/PASSPORT NO/UNION NO</th><td colspan="3">{{$input["NRIC1"]}}</td>
              </tr>
              <tr>
                <th>Position</th><td colspan="3">{{$input["Position1"]}}</td>
              </tr>
              <tr>
                <th>Grade</th><td colspan="3">{{$input["Grade1"]}}</td>
              </tr>
              <tr>
                <th>Company</th><td colspan="3">{{$input["Company1"]}}</td>
              </tr>
              <tr>
                <th>Holiday Territory</th><td colspan="3">{{$input["HolidayTerritoryId1"]}}</td>
              </tr>
              <tr>
                <th>Working Days</th><td colspan="3">{{$input["Working_Days1"]}}</td>
              </tr>
              <tr>
                <th>Joining Date</th><td colspan="3">{{$input["Joining_Date1"]}}</td>
              </tr>
              <tr>
                <th>Confirmation Date</th><td colspan="3">{{$input["Confirmation_Date1"]}}</td>
              </tr>
              <tr>
                <th>Resignation Date</th><td colspan="3">{{$input["Resignation_Date1"]}}</td>
              </tr>


            </tbody>
          </table>
      </div>

      <div class="row">
          <table class="table table-bordered" style="padding-top:320px;">
            <tbody>
              <tr>
                <th>DOB</th><td>{{$input["DOB1"]}}</td>
                <th>Place of Birth</th><td>{{$input["Place_Of_Birth1"]}}</td>
              </tr>
              <tr>
                <th>Company Email</th><td>{{$input["Company_Email1"]}}</td>
                <th>Personal Email</th><td>{{$input["Personal_Email1"]}}</td>
              </tr>
              <tr>
                <th>Contact No 1</th><td>{{$input["Contact_No_1Z"]}}</td>
                <th>Contact No 2</th><td>{{$input["Contact_No_2Z"]}}</td>
              </tr>
              <tr>
                <th>House Phone No</th><td>{{$input["House_Phone_No1"]}}</td>
                <th>Marital Status</th><td>{{$input["Marital_Status1"]}}</td>
              </tr>
              <tr>
                <th>Gender</th><td>{{$input["Gender1"]}}</td>
                <th>Nationality</th><td>{{$input["Nationality1"]}}</td>
              </tr>
              <tr>
                <th>Race</th><td>{{$input["Race1"]}}</td>
                <th>Religion</th><td>{{$input["Religion1"]}}</td>
              </tr>
              <tr>
                <th>Permanent Address</th><td colspan="3">{{$input["Permanent_Address1"]}}</td>
              </tr>
              <tr>
                <th>Emergency Contact Person 1</th><td>{{$input["Emergency_Contact_Person1"]}}</td>
                <th>Emergency Contact Person 2</th><td>{{$input["Emergency_Contact_Person_2Z"]}}</td>
              </tr>
              <tr>
                <th>Emergency Contact No</th><td>{{$input["Emergency_Contact_No1"]}}</td>
                <th>Emergency Contact No</th><td>{{$input["Emergency_Contact_No_2Z"]}}</td>
              </tr>
              <tr>
                <th>Emergency Contact Relationship </th><td>{{$input["Emergency_Contact_Relationship1"]}}</td>
                <th>Emergency Contact Relationship </th><td>{{$input["Emergency_Contact_Relationship_2Z"]}}</td>
              </tr>


            </tbody>
          </table>
      </div>

      <div class="row">
          <table class="table table-bordered" style="padding-top:10px;">
            <tbody>
              <tr>
                <th>Bank Name</th><td>{{$input["Bank_Name1"]}}</td>
                <th>Bank Account No</th><td>{{$input["Bank_Account_No1"]}}</td>
              </tr>
              <tr>
                <th>EPF No</th><td>{{$input["EPF_No1"]}}</td>
                <th>SOCSO No</th><td>{{$input["SOCSO_No1"]}}</td>
              </tr>
              <tr>
                <th>Income Tax No No</th><td colspan="3">{{$input["Income_Tax_No1"]}}</td>
              </tr>

            </tbody>
          </table>
      </div>

      <div class="row">

        <h4 style="text-align:center">Family details</h4>

        <table class="table table-bordered" style="padding-top:20px;">

            <tbody>

              <tr>
                <th>No</th>
                <th>Name</th>
                <th>Age</th>
                <th>Relationship</th>
                <th>Occupation</th>
                <th>Company/School Name</th>
              </tr>

              <?php $i = 1; ?>
              @foreach($family as $family1)

                <tr id="row_{{ $i }}">
                      <td>{{$i}}</td>
                    @foreach($family1 as $key=>$value)
                      <td>
                        {{ $value }}
                      </td>
                    @endforeach
                </tr>
                <?php $i++; ?>
              @endforeach

          </tbody>
            <tfoot></tfoot>
        </table>

      </div>




</div>


<!-- style="page-break-before: always;" -->
</body>
</html>
