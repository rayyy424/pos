
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

      <script type="text/javascript" language="javascript" src="{{ asset('/plugin/js/dataTables.editor.min.js') }}"></script>
      {{-- chart js --}}
      <script type="text/javascript" language="javascript" src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.4.0/Chart.bundle.min.js"></script>

      <script type="text/javascript" language="javascript" class="init">

      var sgsimport;

      $(document).ready(function() {

          sgsimport = $('#sgsimport').dataTable( {

                  columnDefs: [{ "visible": false, "targets": [0] },{"className": "dt-center", "targets": "_all"}],
                  responsive: false,
                  colReorder: false,
                  dom: "Bfrtpi",
                  sScrollX: "100%",
                  bAutoWidth: true,
                  sScrollY: "100%",
                  iDisplayLength:20,
                  scrollCollapse: true,
                 buttons: [
                                   {
                          extend: 'csv',
                          exportOptions: {
                              columns: ':visible'
                          }
                      },
                 ],

            });

            sgsimport.api().on( 'order.dt search.dt', function () {
                sgsimport.api().column(0, {search:'applied', order:'applied'}).nodes().each( function (cell, i) {
                    cell.innerHTML = i+1;
                } );
            } ).draw();

            $(".sgsimport thead input").keyup ( function () {

                    /* Filter on the column (the index) of this element */
                    if ($('#sgsimport').length > 0)
                    {

                        var colnum=document.getElementById('sgsimport').rows[0].cells.length;

                        if (this.value=="[empty]")
                        {

                           sgsimport.fnFilter( '^$', this.name,true,false );
                        }
                        else if (this.value=="[nonempty]")
                        {

                           sgsimport.fnFilter( '^(?=\\s*\\S).*$', this.name,true,false );
                        }
                        else if (this.value.startsWith("!")==true && this.value.length>1)
                        {

                           sgsimport.fnFilter( '^(?'+ this.value +').*', this.name,true,false );
                        }
                        else if (this.value.startsWith("!")==false)
                        {

                            sgsimport.fnFilter( this.value, this.name,true,false );
                        }
                    }



            } );

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
      SGS Import
      <small>Human Resource</small>
      </h1>

      <ol class="breadcrumb">
      <li><a href="{{ url('/home') }}"><i class="fa fa-dashboard"></i> Home</a></li>
      <li><a href="#">Human Resource</a></li>
      <li><a href="#">Leave</a></li>
      <li class="active">SGS Import</li>
      </ol>
    </section>

    <section class="content">
      <br>
      <div class="row">


          <div class="col-sm-2">
            <div class="form-group">
             <select class="form-control select2" id="Month" name="Month" style="width: 100%;">
               <option <?php if($month == 'January') echo ' selected="selected" '; ?>>January</option>
               <option <?php if($month == 'February') echo ' selected="selected" '; ?>>February</option>
               <option <?php if($month == 'March') echo ' selected="selected" '; ?>>March</option>
               <option <?php if($month == 'April') echo ' selected="selected" '; ?>>April</option>
               <option <?php if($month == 'May') echo ' selected="selected" '; ?>>May</option>
               <option <?php if($month == 'June') echo ' selected="selected" '; ?>>June</option>
               <option <?php if($month == 'July') echo ' selected="selected" '; ?>>July</option>
               <option <?php if($month == 'August') echo ' selected="selected" '; ?>>August</option>
               <option <?php if($month == 'September') echo ' selected="selected" '; ?>>September</option>
               <option <?php if($month == 'October') echo ' selected="selected" '; ?>>October</option>
               <option <?php if($month == 'November') echo ' selected="selected" '; ?>>November</option>
               <option <?php if($month == 'December') echo ' selected="selected" '; ?>>December</option>
             </select>
           </div>
         </div>

         <div class="col-sm-2">
           <div class="form-group">

             <select class="form-control select2" id="Year" name="Year" style="width: 100%;">
               @foreach($years as $y)
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
                <div class="col-md-6">

                    <div class="input-group">
                      <button type="button" class="btn btn-success btn" data-toggle="modal" onclick="refresh();">Refresh</button>
                    </div>
                </div>
                <label></label>
      </div>
                <div class="row">
                  <div class="col-md-12">

                    <table id="sgsimport" class="sgsimport" cellspacing="0" width="100%" padding="30px" style="font-size: 13px;">
                          <thead>

                            @if($sgsimport)
                              <tr class="search">

                                @foreach($sgsimport as $key=>$value)

                                  @if ($key==0)
                                    <?php $i = 0; ?>

                                    @foreach($value as $field=>$a)
                                        @if ($i==0)
                                          <th align='center'><input type='hidden' class='search_init' name='{{$i}}' placemark='{{$a}}'></th>
                                        @else
                                          @if(!starts_with($field,"Allowance Code #1") && !starts_with($field,"Allowance Amount #1"))
                                            <th align='center'><input type='text' class='search_init' name='{{$i}}'  placemark='{{$a}}'></th>
                                          @endif

                                        @endif

                                        <?php $i ++; ?>
                                    @endforeach

                                    <th align='center'><input type='text' class='search_init' name='{{$i}}'  placemark='{{$a}}'></th>

                                  @endif

                                @endforeach
                              </tr>
                            @endif
                              {{-- prepare header search textbox --}}
                              <tr>
                                @foreach($sgsimport as $key=>$value)

                                  @if ($key==0)

                                        <td></td>

                                    @foreach($value as $field=>$value)
                                      @if(!starts_with($field,"Allowance Code #1") && !starts_with($field,"Allowance Amount #1"))
                                        <td/>{{ $field }}</td>
                                      @endif
                                    @endforeach

                                  @endif

                                @endforeach
                              </tr>
                          </thead>
                          <tbody>

                            <?php $i = 0; ?>
                            @foreach($sgsimport as $leave)

                                <tr id="row_{{ $i }}">
                                      <td></td>

                                      <?php $count=0; ?>

                                          <td>{{$leave->{'1. Employee Number'} }}</td>
                                          <td>{{$leave->{'2. Employee Name'} }}</td>
                                          <td>{{$leave->{'3. Overtime Hours #1'} && $leave->{'3. Overtime Hours #1'} > 0 ? $leave->{'3. Overtime Hours #1'} : ''}}</td>
                                          <td>{{$leave->{'4. Overtime Hours #2'} && $leave->{'4. Overtime Hours #2'} > 0 ? $leave->{'4. Overtime Hours #2'} : ''}}</td>
                                          <td>{{$leave->{'5. Overtime Hours #3'} && $leave->{'5. Overtime Hours #3'} > 0 ? $leave->{'5. Overtime Hours #3'} : ''}}</td>
                                          <td></td>
                                          <td></td>
                                          <td></td>
                                          <td></td>
                                          <td></td>
                                          <td></td>
                                          <td></td>
                                          <td></td>
                                          <td></td>
                                          <td></td>
                                          <td></td>
                                          <td></td>


                                            @if($leave->{'19. Allowance Amount #1'} !=null)
                                              <td>{{$leave->{'18. Allowance Code #1'} }}</td>
                                              <td>{{$leave->{'19. Allowance Amount #1'} }}</td>
                                              <?php $count+=1; ?>

                                            @endif

                                            @if($leave->{'21. Allowance Amount #2'} !=null)
                                              <td>{{$leave->{'20. Allowance Code #2'} }}</td>
                                              <td>{{$leave->{'21. Allowance Amount #2'} }}</td>
                                              <?php $count+=1; ?>

                                            @endif

                                            @if($leave->{'23. Allowance Amount #3'} !=null)
                                              <td>{{$leave->{'22. Allowance Code #3'} }}</td>
                                              <td>{{$leave->{'23. Allowance Amount #3'} }}</td>
                                              <?php $count+=1; ?>

                                            @endif

                                            @if($leave->{'25. Allowance Amount #4'} !=null)
                                              <td>{{$leave->{'24. Allowance Code #4'} }}</td>
                                              <td>{{$leave->{'25. Allowance Amount #4'} }}</td>
                                              <?php $count+=1; ?>

                                            @endif

                                            @if($leave->{'27. Allowance Amount #5'} !=null)
                                              <td>{{$leave->{'26. Allowance Code #5'} }}</td>
                                              <td>{{$leave->{'27. Allowance Amount #5'} }}</td>
                                              <?php $count+=1; ?>

                                            @endif

                                            @if($leave->{'29. Allowance Amount #6'} !=null)
                                              <td>{{$leave->{'28. Allowance Code #6'} }}</td>
                                              <td>{{$leave->{'29. Allowance Amount #6'} }}</td>
                                              <?php $count+=1; ?>

                                            @endif

                                            @if($leave->{'31. Allowance Amount #7'} !=null)
                                              <td>{{$leave->{'30. Allowance Code #7'} }}</td>
                                              <td>{{$leave->{'31. Allowance Amount #7'} }}</td>
                                              <?php $count+=1; ?>

                                            @endif

                                            @if($leave->{'33. Allowance Amount #8'} !=null)
                                              <td>{{$leave->{'32. Allowance Code #8'} }}</td>
                                              <td>{{$leave->{'33. Allowance Amount #8'} }}</td>
                                              <?php $count+=1; ?>

                                            @endif

                                            @if($leave->{'35. Allowance Amount #9'} !=null)
                                              <td>{{$leave->{'34. Allowance Code #9'} }}</td>
                                              <td>{{$leave->{'35. Allowance Amount #9'} }}</td>
                                              <?php $count+=1; ?>

                                            @endif

                                            @if($leave->{'37. Allowance Amount #10'} !=null)
                                              <td>{{$leave->{'36. Allowance Code #10'} }}</td>
                                              <td>{{$leave->{'37. Allowance Amount #10'} }}</td>
                                              <?php $count+=1; ?>

                                            @endif

                                            @if($leave->{'Allowance Amount #11'}!=null)
                                              <td>{{$leave->{'Allowance Code #11'} }}</td>
                                              <td>{{$leave->{'Allowance Amount #11'} }}</td>
                                              <?php $count+=1; ?>
                                            @endif

                                            @if($leave->{'Allowance Amount #12'}!=null)
                                              <td>{{$leave->{'Allowance Code #12'} }}</td>
                                              <td>{{$leave->{'Allowance Amount #12'} }}</td>
                                              <?php $count+=1; ?>
                                            @endif

                                            @if($leave->{'Allowance Amount #13'}!=null)
                                              <td>{{$leave->{'Allowance Code #13'} }}</td>
                                              <td>{{$leave->{'Allowance Amount #13'} }}</td>
                                              <?php $count+=1; ?>

                                            @endif

                                            @if($leave->{'Allowance Amount #14'}!=null)
                                              <td>{{$leave->{'Allowance Code #14'} }}</td>
                                              <td>{{$leave->{'Allowance Amount #14'} }}</td>
                                              <?php $count+=1; ?>

                                            @endif

                                            @if($leave->{'Allowance Amount #15'})
                                              <td>{{$leave->{'Allowance Code #15'} }}</td>
                                              <td>{{$leave->{'Allowance Amount #15'} }}</td>
                                              <?php $count+=1; ?>

                                            @endif

                                            @if($leave->{'Allowance Amount #16'})
                                              <td>{{$leave->{'Allowance Code #16'} }}</td>
                                              <td>{{$leave->{'Allowance Amount #16'} }}</td>
                                              <?php $count+=1; ?>

                                            @endif

                                            @if($leave->{'Allowance Amount #17'})
                                              <td>{{$leave->{'Allowance Code #17'} }}</td>
                                              <td>{{$leave->{'Allowance Amount #17'} }}</td>
                                              <?php $count+=1; ?>

                                            @endif

                                            @if($leave->{'Allowance Amount #18'})
                                              <td>{{$leave->{'Allowance Code #18'} }}</td>
                                              <td>{{$leave->{'Allowance Amount #18'} }}</td>
                                              <?php $count+=1; ?>

                                            @endif

                                        @for ($j = $count; $j < 10; $j++)
                                          <td></td>
                                          <td></td>
                                        @endfor

                                        <td>{{$leave->{'38. NPL Days'} }}</td>
                                        <td></td>
                                        <td></td>
                                        <td></td>

                                        <td>{{$leave->{'42. Leave Day #1'} }}</td>
                                        <td>{{$leave->{'43. Leave Day #2'} }}</td>
                                        <td>{{$leave->{'44. Leave Day #3'} }}</td>
                                        <td>{{$leave->{'45. Leave Day #4'} }}</td>
                                        <td>{{$leave->{'46. Leave Day #5'} }}</td>
                                        <td>{{$leave->{'47. Leave Day #6'} }}</td>
                                        <td>{{$leave->{'48. Leave Day #7'} }}</td>

                                        <td></td>
                                        <td></td>
                                        <td></td>
                                        <td></td>
                                        <td></td>
                                        <td></td>
                                        <td></td>
                                        <td></td>
                                        <td></td>
                                        <td></td>
                                        <td></td>
                                        <td></td>
                                        <td></td>
                                        <td></td>
                                        <td></td>
                                        <td></td>
                                        <td></td>
                                        <td></td>
                                        <td></td>
                                        <td></td>
                                        <td></td>
                                        <td></td>
                                        <td></td>
                                        <td></td>
                                        <td></td>
                                        <td></td>
                                        <td></td>
                                        <td></td>


                                </tr>
                                <?php $i++; ?>

                            @endforeach

                        </tbody>
                      <tfoot></tfoot>
                    </table>

                  </div>
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

      window.location.href ="{{ url("/sgsimport") }}/"+month+"/"+year+"/"+company+"/"+department+"/"+includeresigned+"/"+includeinactive;

    }

</script>



@endsection
