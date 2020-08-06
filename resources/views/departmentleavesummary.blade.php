
@extends('app')

@section('datatable-css')

    <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.10.12/css/jquery.dataTables.min.css">
    <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/buttons/1.2.1/css/buttons.dataTables.min.css">
    <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/responsive/2.1.0/css/responsive.dataTables.min.css">
    <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/select/1.2.0/css/select.dataTables.min.css">
    <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/keytable/2.1.2/css/keyTable.dataTables.min.css">
    <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/autofill/2.1.2/css/autoFill.dataTables.min.css">
    <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/colreorder/1.3.2/css/colReorder.dataTables.min.css">

    <link rel="stylesheet" type="text/css" href="{{ asset('/plugin/css/editor.dataTables.min.css') }}">
    {{-- <link rel="stylesheet" type="text/css" href="{{ asset('/plugin/examples/resources/syntax/shCore.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('/Plugin/examples/resources/demo.css') }}"> --}}

    <style type="text/css" class="init">
      a.buttons-collection {
        margin-left: 1em;
      }
      .container1{
              width: 1200px;
              margin-left: 50px;
              padding: 10px;
      }
      .tableheader{
        background-color: gray;
      }

      .leavetable{
        text-align: center;
      }

      .legend div{

        color:red;

      }

      table.dataTable thead th{
            border: 1px solid #FFF;
      }

      /* table.dataTable thead th .dt-center, table.dataTable td .dt-center {

            width:50px;
      } */

      /* table.dataTable th td .sorting_disabled .dt-center{
  			max-width: 45px;
        width: 45px;
  	} */

    </style>

@endsection

@section('datatable-js')

      <script type="text/javascript" language="javascript" src="https://cdn.datatables.net/1.10.12/js/jquery.dataTables.min.js"></script>
      <script type="text/javascript" language="javascript" src="https://cdn.datatables.net/buttons/1.2.1/js/dataTables.buttons.min.js"></script>
      <script type="text/javascript" language="javascript" src="https://cdn.datatables.net/select/1.2.0/js/dataTables.select.min.js"></script>
      <script type="text/javascript" language="javascript" src="https://cdn.datatables.net/autofill/2.1.2/js/dataTables.autoFill.min.js"></script>
      <script type="text/javascript" language="javascript" src="https://cdn.datatables.net/colreorder/1.3.2/js/dataTables.colReorder.min.js"></script>
      <script type="text/javascript" language="javascript" src="https://cdn.datatables.net/responsive/2.1.0/js/dataTables.responsive.min.js"></script>
      <script type="text/javascript" language="javascript" src="https://cdnjs.cloudflare.com/ajax/libs/jszip/2.5.0/jszip.min.js"></script>
      <script type="text/javascript" language="javascript" src="https://cdn.datatables.net/keytable/2.1.2/js/dataTables.keyTable.min.js"></script>
      <script type="text/javascript" language="javascript" src="https://cdn.rawgit.com/bpampuch/pdfmake/0.1.18/build/pdfmake.min.js"></script>
      <script type="text/javascript" language="javascript" src="https://cdn.rawgit.com/bpampuch/pdfmake/0.1.18/build/vfs_fonts.js"></script>
      <script type="text/javascript" language="javascript" src="https://cdn.datatables.net/buttons/1.2.0/js/buttons.html5.min.js"></script>
      <script type="text/javascript" language="javascript" src="https://cdn.datatables.net/buttons/1.2.0/js/buttons.print.min.js"></script>
      <script type="text/javascript" language="javascript" src="https://cdn.datatables.net/fixedcolumns/3.2.6/js/dataTables.fixedColumns.min.js"></script>



      <script type="text/javascript" language="javascript" src="{{ asset('/plugin/js/dataTables.editor.min.js') }}"></script>
      {{-- chart js --}}
      <script type="text/javascript" language="javascript" src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.4.0/Chart.bundle.min.js"></script>

      <script type="text/javascript" language="javascript" class="init">

      var summary;

      $(document).ready(function() {

          summary = $('table.summary').dataTable( {

                  columnDefs: [{ "visible": false, "targets": [2] },{"className": "dt-left", "targets": [1]},{"className": "dt-center", "targets": "_all"}],
                  responsive: false,
                  colReorder: false,

                  dom: "Blfrtip",
                  sScrollX: "100%",
                  // bAutoWidth: true,
                  "autoWidth": false,
                  language : {
                     sLoadingRecords : 'Loading data...',
                     processing: 'Loading data...'
                   },
                  sScrollY: "100%",
                  scrollCollapse: true,
                  aaSorting:false,
                  "ordering": false,
                  bPaginate:true,
                  iDisplayLength:10,
                  "lengthMenu": [[10,15, 25, 50, -1], [10, 15, 25,50, "All"]],
                  fixedColumns: {
                      leftColumns: 4
                  },
                 buttons: [
                         {
                                 extend: 'collection',
                                 text: 'Export',
                                 buttons: [
                                         'csv'
                                 ]
                         }
                 ],

            });

        });

        $('a[data-toggle="tab"]').on( 'shown.bs.tab', function (e) {
          var target = $(e.target).attr("href") // activated tab


            $.fn.dataTable.tables( {visible: true, api: true} ).columns.adjust();


        } );

      </script>

      @endsection

@section('content')

  <div class="content-wrapper">
      <!-- Content Header (Page header) -->
    <section class="content-header">
      <h1>
      Department Attendance Summary
      <small>Human Resource</small>
      </h1>

      <ol class="breadcrumb">
      <li><a href="{{ url('/home') }}"><i class="fa fa-dashboard"></i> Home</a></li>
      <li><a href="#">Human Resource</a></li>
      <li class="active">Department Attendance Summary</li>
      </ol>
    </section>

    <section class="content">
      <br>
      <div class="row">

          <div class="col-md-2">
            <div class="form-group">
             <select class="form-control select2" id="Month" name="Month" >
               @foreach($monthnames as $monthname)
                 <option <?php if($month == $monthname) echo ' selected="selected" '; ?>>{{ $monthname }}</option>
               @endforeach
             </select>
           </div>
         </div>

         <div class="col-md-2">
           <div class="form-group">

             <select class="form-control select2" id="Year" name="Year" >
               @foreach ($years as $y)
                  <option <?php if($year == $y->yearname) echo ' selected="selected" '; ?>>{{$y->yearname}}</option>
               @endforeach

             </select>
           </div>
          </div>

          <div class="col-md-4">
            <div class="form-group">
             <select class="form-control select2" id="Company" name="Company" >
               <option></option>
               @foreach($companies as $com)
                  <option <?php if($company == $com->Option) echo ' selected="selected" '; ?>>{{$com->Option}}</option>
               @endforeach
             </select>
           </div>
         </div>

         <div class="col-md-4">
           <div class="form-group">
            <select class="form-control select2" id="Department" name="Department" >
              <option></option>
              @foreach($departments as $dept)
                 <option <?php if($department == $dept->Project_Name) echo ' selected="selected" '; ?>>{{$dept->Project_Name}}</option>
              @endforeach
            </select>
          </div>
        </div>




    </div>
    <div class="row">

      <div class="col-md-2">
         <div class="checkbox">
           <label><input type="checkbox" id="includeresigned" name="includeresigned" {{ $includeResigned == 'true' ? 'checked' : '' }}> Include Resigned</label>
         </div>
       </div>
       <div class="col-md-2">
         <div class="checkbox">
           <label><input type="checkbox" id="includeinactive" name="includeinactive" {{ $includeInactive == 'true' ? 'checked' : '' }}> Include Inactive</label>
         </div>
       </div>
       <div class="col-md-2">

           <div class="input-group">
             <button type="button" class="btn btn-success btn" data-toggle="modal" onclick="refresh();">Refresh</button>
           </div>
       </div>
    </div>

    <div class="row legend">

      <div class="col-md-12">
          <H4>Short Code</H4>
      </div>

      <div class="col-md-2">

        <b>Annual Leave : </b>AL

      </div>

      <div class="col-md-2">

        <b>Emergency Leave : </b>EL

      </div>

      <div class="col-md-2">

        <b>Medical Leave : </b>MC

      </div>

      <div class="col-md-2">

        <b>Unpaid Leave : </b>UL

      </div>

      <div class="col-md-2">

        <b>1 Hour Time Off : </b>1HR

      </div>

      <div class="col-md-2">

        <b>2 Hours Time Off : </b>2HR

      </div>

      <div class="col-md-2">

        <b>Replacement Leave : </b>RL

      </div>

      <div class="col-md-2">

        <b>Marriage Leave : </b>MRL

      </div>

      <div class="col-md-2">

        <b>Maternity Leave : </b>MTL

      </div>

      <div class="col-md-2">

        <b>Paternity Leave : </b>PTL

      </div>

      <div class="col-md-4">

        <b>Compassionate Leave : </b>CPL

      </div>

      <div class="col-md-4">

        <b>Hospitalization Leave : </b>HL

      </div>

    </div>

    <br>



                <div class="row">
                  <div class="col-md-12">

                          <?php $name=""; $index2=0; $count=0; $indexno=0;?>

                          <table class="summary" cellspacing="0" width="100%" padding="30px" style="font-size: 13px;">
                                <thead>
                                  <tr>
                                    <th>No.</th>
                                    <th>Name</th>
                                    <th>Company</th>
                                    <th>Department</th>
                                    @for ($i=0; $i <sizeof($arrdays) ; $i++)
                                      <th>{{substr($arrdays[$i]->Date,0,6)}} ({{date( "D", strtotime($arrdays[$i]->Date))}})</th>
                                    @endfor
                                  </tr>
                                </thead>
                                <tbody>

                            @for ($index=0; $index < sizeof($timesheetdetail); $index++)

                              <?php $color=""; ?>

                              @if($timesheetdetail[$index]->Holiday)
                                <?php $color='bgcolor=#bdc3c7'; ?>
                              @elseif($timesheetdetail[$index]->dayofweek==1 or $timesheetdetail[$index]->dayofweek==7)
                                <?php $color='bgcolor=#fab1a0'; ?>

                              @endif
                              @if($name=="ROFIQ AHMAD")
                                  {{$timesheetdetail[$index]->Name}}
                              @endif
                              @if($name!=$timesheetdetail[$index]->Name && $name=="")
                                <?php $name=$timesheetdetail[$index]->Name; $indexno++;?>
                                <?php $index2=0;$count=0;?>
                                <tr>
                                  <td>{{$indexno}}</td>
                                  <td>{{$name}}</td>
                                  <td>{{$timesheetdetail[$index]->Company}}</td>
                                  <td>{{$timesheetdetail[$index]->Department}}</td>
                              @elseif($name!=$timesheetdetail[$index]->Name)

                                @if($count>0)
                                  @for ($run=$count; $run < sizeof($arrdays); $run++)
                                    <td></td>
                                  @endfor
                                @endif
                                <?php $name=$timesheetdetail[$index]->Name; $indexno++;?>
                                <?php $index2=0;$count=0;?>
                                </tr>
                                <tr>
                                  <td>{{$indexno}}</td>
                                  <td>{{$name}}</td>
                                  <td>{{$timesheetdetail[$index]->Company}}</td>
                                  <td>{{$timesheetdetail[$index]->Department}}</td>
                              @endif

                              @if($timesheetdetail[$index]->Date==$arrdays[$index2]->Date)

                                @if($timesheetdetail[$index]->Time_In && $timesheetdetail[$index]->Time_Out && !$timesheetdetail[$index]->Leave_Type)
                                    <td {{$color}}>1</td>
                                    <?php $count++;?>
                                @elseif($timesheetdetail[$index]->Time_In && !$timesheetdetail[$index]->Time_Out && !$timesheetdetail[$index]->Leave_Type)
                                    <td {{$color}}>1 | 0</td>
                                    <?php $count++;?>

                                @elseif($timesheetdetail[$index]->Holiday)
                                      <td {{$color}}>PH</td>
                                      <?php $count++;?>

                                 @elseif(date("w", strtotime($timesheetdetail[$index]->Date))== 0 && $timesheetdetail[$index]->Working_Days <= 6)
                                          <td {{$color}}></td>
                                          <?php $count++;?>

                                @elseif(date("w", strtotime($timesheetdetail[$index]->Date))== 6 && $timesheetdetail[$index]->Working_Days == 5.5)
                                      @if($timesheetdetail[$index]->Leave_Type)
                                              @if(str_contains($timesheetdetail[$index]->Leave_Term,"Half Day") || str_contains($timesheetdetail[$index]->Leave_Period,"AM")  || str_contains($timesheetdetail[$index]->Leave_Period,"PM") )
                                                @if(! str_contains(strtoupper($timesheetdetail[$index]->Reason),strtoupper("[System Auto Apply] No Time In for the day")))
                                                <?php $term="0.5 ";?>
                                                @endif
                                              @elseif(str_contains($timesheetdetail[$index]->Leave_Period,"1 Hour"))
                                                <?php $term="0.125 ";?>
                                              @elseif(str_contains($timesheetdetail[$index]->Leave_Period,"2 Hours"))
                                                <?php $term="0.25 ";?>
                                              @else
                                                <?php $term="";?>
                                              @endif
                                              <?php $leaves = ""; ?>
                                              @if(str_contains(strtoupper($timesheetdetail[$index]->Leave_Type),"ANNUAL LEAVE"))
                                                  @if(str_contains(strtoupper($timesheetdetail[$index]->Reason),strtoupper("[System Auto Apply] No Time In for the day")))
                                                  <?php $leaves .= "ABSENT,"; ?>
                                                  @else
                                                  <?php $leaves .= "AL,"; ?>
                                                  @endif
                                                  <!-- <td {{$color}}>{{$term}}AL</td> -->
                                              @endif
                                              @if(str_contains(strtoupper($timesheetdetail[$index]->Leave_Type),"EMERGENCY LEAVE"))
                                                  <?php $leaves .= "EL,"; ?>
                                                <!-- <td {{$color}}>{{$term}}EL</td> -->
                                              @endif
                                              @if(str_contains(strtoupper($timesheetdetail[$index]->Leave_Type),"MEDICAL LEAVE"))
                                                  <?php $leaves .= "MC,"; ?>
                                                <!-- <td {{$color}}>{{$term}}MC</td> -->
                                              @endif
                                              @if(str_contains(strtoupper($timesheetdetail[$index]->Leave_Type),"UNPAID LEAVE"))
                                                  @if(str_contains(strtoupper($timesheetdetail[$index]->Reason),strtoupper("[System Auto Apply] No Time In for the day")))
                                                  <?php $leaves .= "ABSENT,"; ?>
                                                  @else
                                                  <?php $leaves .= "UL,"; ?>
                                                  @endif
                                                <!-- <td {{$color}}>{{$term}}UL</td> -->
                                              @endif
                                              @if(str_contains(strtoupper($timesheetdetail[$index]->Leave_Type),"1 HOUR TIME OFF"))
                                                  <?php $leaves .= "1HR,"; ?>
                                                <!-- <td {{$color}}>1HR</td> -->
                                              @endif
                                              @if(str_contains(strtoupper($timesheetdetail[$index]->Leave_Type),"2 HOURS TIME OFF"))
                                                <?php $leaves .= "2HR"; ?>
                                                <!-- <td {{$color}}>2HR</td> -->
                                              @endif

                                              @if(str_contains(strtoupper($timesheetdetail[$index]->Leave_Type),"REPLACEMENT LEAVE"))
                                                <?php $leaves .= "RL,"; ?>
                                                <!-- <td {{$color}}>RL</td> -->
                                              @endif

                                              @if(str_contains(strtoupper($timesheetdetail[$index]->Leave_Type),"MARRIAGE LEAVE"))
                                                <!-- <td {{$color}}>MRL</td> -->
                                                <?php $leaves .= "MRL,"; ?>
                                              @endif

                                              @if(str_contains(strtoupper($timesheetdetail[$index]->Leave_Type),"MATERNITY LEAVE"))
                                                <!-- <td {{$color}}>MTL</td> -->
                                                <?php $leaves .= "MTL,"; ?>
                                              @endif

                                              @if(str_contains(strtoupper($timesheetdetail[$index]->Leave_Type),"PATERNITY LEAVE"))
                                                <!-- <td {{$color}}>PTL</td> -->
                                                <?php $leaves .= "PTL,"; ?>
                                              @endif

                                              @if(str_contains(strtoupper($timesheetdetail[$index]->Leave_Type),"COMPASSIONATE LEAVE"))
                                                <!-- <td {{$color}}>CPL</td> -->
                                                <?php $leaves .= "CPL,"; ?>
                                              @endif

                                              @if(str_contains(strtoupper($timesheetdetail[$index]->Leave_Type),"HOSPITALIZATION LEAVE"))
                                                <!-- <td {{$color}}>CPL</td> -->
                                                <?php $leaves .= "HL,"; ?>
                                              @endif

                                              @if($leaves=="")
                                                <td {{$color}}>{{$term}}{{$timesheetdetail[$index]->Leave_Type}}</td>
                                              @else
                                                @if(rtrim($leaves,',') == "2HR" || rtrim($leaves,',') == "1HR")
                                                <td {{$color}}>{{rtrim($leaves,',')}}</td>
                                                @else
                                                <td {{$color}}>{{$term}}{{rtrim($leaves,',')}}</td>
                                                @endif
                                              @endif
                                              <?php $count++;?>
                                      @else
                                        <td></td>
                                        <?php $count++;?>
                                      @endif

                                @elseif(date("w", strtotime($timesheetdetail[$index]->Date))== 6 && $timesheetdetail[$index]->Working_Days == 5)
                                      <td></td>
                                      <?php $count++;?>

                                @elseif($timesheetdetail[$index]->Leave_Type)
                                      @if(str_contains($timesheetdetail[$index]->Leave_Term,"Half Day") || str_contains($timesheetdetail[$index]->Leave_Period,"AM")  || str_contains($timesheetdetail[$index]->Leave_Period,"PM") )
                                        @if(! str_contains(strtoupper($timesheetdetail[$index]->Reason),strtoupper("[System Auto Apply] No Time In for the day")))
                                        <?php $term="0.5 ";?>
                                        @endif
                                      @elseif(str_contains($timesheetdetail[$index]->Leave_Period,"1 Hour"))
                                        <?php $term="0.125 ";?>
                                      @elseif(str_contains($timesheetdetail[$index]->Leave_Period,"2 Hours"))
                                        <?php $term="0.25 ";?>
                                      @else
                                        <?php $term="";?>
                                      @endif

                                      <?php $leaves = ""; ?>

                                      @if(str_contains(strtoupper($timesheetdetail[$index]->Leave_Type),"ANNUAL LEAVE"))
                                          @if(str_contains(strtoupper($timesheetdetail[$index]->Reason),strtoupper("[System Auto Apply] No Time In for the day")))
                                          <?php $leaves .= "ABSENT,"; ?>
                                          @else
                                          <?php $leaves .= "AL,"; ?>
                                          @endif
                                          <!-- <td {{$color}}>{{$term}}AL</td> -->
                                      @endif
                                      @if(str_contains(strtoupper($timesheetdetail[$index]->Leave_Type),"EMERGENCY LEAVE"))
                                        <?php $leaves .= "EL,"; ?>
                                        <!-- <td {{$color}}>{{$term}}EL</td> -->
                                      @endif

                                      @if(str_contains(strtoupper($timesheetdetail[$index]->Leave_Type),"MEDICAL LEAVE"))
                                        <?php $leaves .= "MC,"; ?>
                                        <!-- <td {{$color}}>{{$term}}MC</td> -->
                                      @endif

                                      @if(str_contains(strtoupper($timesheetdetail[$index]->Leave_Type),"UNPAID LEAVE"))
                                        @if(str_contains(strtoupper($timesheetdetail[$index]->Reason),strtoupper("[System Auto Apply] No Time In for the day")))
                                        <?php $leaves .= "ABSENT,"; ?>
                                        @else
                                        <?php $leaves .= "UL,"; ?>
                                        @endif
                                        <!-- <td {{$color}}>{{$term}}UL</td> -->
                                      @endif

                                      @if(str_contains(strtoupper($timesheetdetail[$index]->Leave_Type),"1 HOUR TIME OFF"))
                                        <?php $leaves .= "1HR,"; ?>
                                        <!-- <td {{$color}}>1HR</td> -->
                                      @endif

                                      @if(str_contains(strtoupper($timesheetdetail[$index]->Leave_Type),"2 HOURS TIME OFF"))
                                        <?php $leaves .= "2HR,"; ?>
                                        <!-- <td {{$color}}>2HR</td> -->
                                      @endif

                                      @if(str_contains(strtoupper($timesheetdetail[$index]->Leave_Type),"REPLACEMENT LEAVE"))
                                        <?php $leaves .= "RL,"; ?>
                                        <!-- <td {{$color}}>RL</td> -->
                                      @endif

                                      @if(str_contains(strtoupper($timesheetdetail[$index]->Leave_Type),"MARRIAGE LEAVE"))
                                        <!-- <td {{$color}}>MRL</td> -->
                                        <?php $leaves .= "MRL,"; ?>
                                      @endif

                                      @if(str_contains(strtoupper($timesheetdetail[$index]->Leave_Type),"MATERNITY LEAVE"))
                                        <!-- <td {{$color}}>MTL</td> -->
                                        <?php $leaves .= "MTL,"; ?>
                                      @endif

                                      @if(str_contains(strtoupper($timesheetdetail[$index]->Leave_Type),"PATERNITY LEAVE"))
                                        <!-- <td {{$color}}>PTL</td> -->
                                        <?php $leaves .= "PTL,"; ?>
                                      @endif

                                      @if(str_contains(strtoupper($timesheetdetail[$index]->Leave_Type),"COMPASSIONATE LEAVE"))
                                        <!-- <td {{$color}}>CPL</td> -->
                                        <?php $leaves .= "CPL,"; ?>
                                      @endif

                                      @if(str_contains(strtoupper($timesheetdetail[$index]->Leave_Type),"HOSPITALIZATION LEAVE"))
                                        <!-- <td {{$color}}>CPL</td> -->
                                        <?php $leaves .= "HL,"; ?>
                                      @endif

                                      @if($leaves=="")
                                        <td {{$color}}>{{$term}}{{$timesheetdetail[$index]->Leave_Type}}</td>
                                      @else
                                        @if(rtrim($leaves,',') == "2HR" || rtrim($leaves,',') == "1HR")
                                        <td {{$color}}>{{rtrim($leaves,',')}}</td>
                                        @else
                                        <td {{$color}}>{{$term}}{{rtrim($leaves,',')}}</td>
                                        @endif
                                      @endif

                                        <?php $count++;?>

                                @else
                                <!-- here hau -->
                                  <td>ABSENT</td>
                                  <?php $count++;?>
                                @endif

                              @else

                                <td ></td>
                                <?php $count++;?>

                                @while($timesheetdetail[$index]->Date!=$arrdays[$index2]->Date)

                                  @if($timesheetdetail[$index]->Date==$arrdays[$index2+1]->Date)

                                    @if($timesheetdetail[$index]->Time_In && $timesheetdetail[$index]->Time_Out && !$timesheetdetail[$index]->Leave_Type)
                                        <td {{$color}}>1</td>
                                        <?php $count++;?>
                                    @elseif($timesheetdetail[$index]->Time_In && !$timesheetdetail[$index]->Time_Out && !$timesheetdetail[$index]->Leave_Type)
                                        <td {{$color}}>1 | 0</td>
                                        <?php $count++;?>

                                    @elseif($timesheetdetail[$index]->Holiday)
                                          <td {{$color}}>PH</td>
                                          <?php $count++;?>
                                    @elseif(date("w", strtotime($timesheetdetail[$index]->Date))== 0 && $timesheetdetail[$index]->Working_Days <= 6)
                                          <td {{$color}}></td>
                                          <?php $count++;?>
                                    @elseif($timesheetdetail[$index]->Leave_Type)
                                          @if(str_contains($timesheetdetail[$index]->Leave_Term,"Half Day") || str_contains($timesheetdetail[$index]->Leave_Period,"AM")  || str_contains($timesheetdetail[$index]->Leave_Period,"PM") )
                                            @if(! str_contains(strtoupper($timesheetdetail[$index]->Reason),strtoupper("[System Auto Apply] No Time In for the day")))
                                            <?php $term="0.5 ";?>
                                            @endif
                                          @elseif(str_contains($timesheetdetail[$index]->Leave_Period,"1 Hour"))
                                            <?php $term="0.125 ";?>
                                          @elseif(str_contains($timesheetdetail[$index]->Leave_Period,"2 Hours"))
                                            <?php $term="0.25 ";?>
                                          @else
                                            <?php $term="";?>
                                          @endif

                                          <?php $leaves = ""; ?>

                                          @if(str_contains(strtoupper($timesheetdetail[$index]->Leave_Type),"ANNUAL LEAVE"))
                                              @if(str_contains(strtoupper($timesheetdetail[$index]->Reason),strtoupper("[System Auto Apply] No Time In for the day")))
                                              <?php $leaves .= "ABSENT,"; ?>
                                              @else
                                              <?php $leaves .= "AL,"; ?>
                                              @endif
                                              <!-- <td {{$color}}>{{$term}}AL</td> -->
                                          @endif
                                          @if(str_contains(strtoupper($timesheetdetail[$index]->Leave_Type),"EMERGENCY LEAVE"))
                                            <?php $leaves .= "EL,"; ?>
                                            <!-- <td {{$color}}>{{$term}}EL</td> -->
                                          @endif

                                          @if(str_contains(strtoupper($timesheetdetail[$index]->Leave_Type),"MEDICAL LEAVE"))
                                            <?php $leaves .= "MC,"; ?>
                                            <!-- <td {{$color}}>{{$term}}MC</td> -->
                                          @endif

                                          @if(str_contains(strtoupper($timesheetdetail[$index]->Leave_Type),"UNPAID LEAVE"))
                                            @if(str_contains(strtoupper($timesheetdetail[$index]->Reason),strtoupper("[System Auto Apply] No Time In for the day")))
                                            <?php $leaves .= "ABSENT,"; ?>
                                            @else
                                            <?php $leaves .= "UL,"; ?>
                                            @endif
                                            <!-- <td {{$color}}>{{$term}}UL</td> -->
                                          @endif

                                          @if(str_contains(strtoupper($timesheetdetail[$index]->Leave_Type),"1 HOUR TIME OFF"))
                                            <?php $leaves .= "1HR,"; ?>
                                            <!-- <td {{$color}}>1HR</td> -->
                                          @endif

                                          @if(str_contains(strtoupper($timesheetdetail[$index]->Leave_Type),"2 HOURS TIME OFF"))
                                            <?php $leaves .= "2HR,"; ?>
                                            <!-- <td {{$color}}>2HR</td> -->
                                          @endif

                                          @if(str_contains(strtoupper($timesheetdetail[$index]->Leave_Type),"REPLACEMENT LEAVE"))
                                            <?php $leaves .= "RL,"; ?>
                                            <!-- <td {{$color}}>RL</td> -->
                                          @endif

                                          @if(str_contains(strtoupper($timesheetdetail[$index]->Leave_Type),"MARRIAGE LEAVE"))
                                            <!-- <td {{$color}}>MRL</td> -->
                                            <?php $leaves .= "MRL,"; ?>
                                          @endif

                                          @if(str_contains(strtoupper($timesheetdetail[$index]->Leave_Type),"MATERNITY LEAVE"))
                                            <!-- <td {{$color}}>MTL</td> -->
                                            <?php $leaves .= "MTL,"; ?>
                                          @endif

                                          @if(str_contains(strtoupper($timesheetdetail[$index]->Leave_Type),"PATERNITY LEAVE"))
                                            <!-- <td {{$color}}>PTL</td> -->
                                            <?php $leaves .= "PTL,"; ?>
                                          @endif

                                          @if(str_contains(strtoupper($timesheetdetail[$index]->Leave_Type),"COMPASSIONATE LEAVE"))
                                            <!-- <td {{$color}}>CPL</td> -->
                                            <?php $leaves .= "CPL,"; ?>
                                          @endif

                                          @if(str_contains(strtoupper($timesheetdetail[$index]->Leave_Type),"HOSPITALIZATION LEAVE"))
                                            <!-- <td {{$color}}>CPL</td> -->
                                            <?php $leaves .= "HL,"; ?>
                                          @endif

                                          @if($leaves=="")
                                            <td {{$color}}>{{$term}}{{$timesheetdetail[$index]->Leave_Type}}</td>
                                          @else
                                            @if(rtrim($leaves,',') == "2HR" || rtrim($leaves,',') == "1HR")
                                            <td {{$color}}>{{rtrim($leaves,',')}}</td>
                                            @else
                                            <td {{$color}}>{{$term}}{{rtrim($leaves,',')}}</td>
                                            @endif
                                          @endif

                                          <?php $count++;?>
                                    @elseif($timesheetdetail[$index])
                                      <td {{$color}}></td>
                                      <?php $count++;?>
                                    @else
                                      <td {{$color}}></td>
                                      <?php $count++;?>
                                    @endif
                                  @else
                                    <td></td>
                                    <?php $count++;?>
                                  @endif

                                  <?php $index2++;?>

                                  @if($index2>=sizeof($arrdays))
                                    <?php $index2=0;?>
                                    <?php $count=0;?>

                                  @endif

                                @endwhile

                              @endif

                              <?php $index2++;?>

                              @if($index2==sizeof($arrdays))
                                <?php $index2=0;?>
                                <?php $count=0;?>

                              @endif

                            @endfor

                          </tr>

                        </tbody>
                      <tfoot></tfoot>
                  </table>

                  </div>
                </div>

    </div>

    </section>

</div>
<footer class="main-footer">
  <div class="pull-right hidden-xs">
    <b>Version</b> 2.0.1
  </div>
  <strong>Copyright &copy; 2014-2016 <a href="http://www.softoya.com">TrackerOnTheGo</a>.</strong> All rights
  reserved.
</footer>

<script>

    $(function () {

      $(".select2").select2();

      $('#range').daterangepicker({locale: {
        format: 'DD-MMM-YYYY'
      },startDate: '{{$month}}',
      endDate: '{{$year}}'});

    });

    function refresh()
    {

      month=$('[name="Month"]').val();
      year=$('[name="Year"]').val();
      company=$('[name="Company"]').val();
      department=$('[name="Department"]').val();
      var includeresigned=$('#includeresigned').is(':checked');
      var includeinactive=$('#includeinactive').is(':checked');

      if(!company)
      {
        company=false;
      }

      if(!department)
      {
        department=false;
      }

      window.location.href ="{{ url("/departmentleavesummary") }}/"+month+"/"+year+"/"+company+"/"+department+"/"+includeresigned+"/"+includeinactive;

    }

</script>



@endsection
