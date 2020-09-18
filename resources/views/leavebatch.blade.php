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

    <style type="text/css" class="init">
      a.buttons-collection {
        margin-left: 1em;
      }
      .container1{
              width: 1200px;
              margin-left: 50px;
              padding: 10px;
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

      <script type="text/javascript" language="javascript" class="init">

          var stafflisttable;
          var leaveid;

          $(document).ready(function() {
                     stafflisttable=$('#leaveadjustmenttable').dataTable();
                     stafflisttable=$('#stafflisttable').dataTable( {
                             columnDefs: [{ "visible": false, "targets": [1] },{"className": "dt-center", "targets": "_all"}],
                             // responsive: false,
                             colReorder: false,
                             // dom: "pBrt",
                             sScrollX: "100%",
                             bAutoWidth: true,
                             sScrollY: "100%",
                             scrollCollapse: true,
                             // aaSorting:false,
                             // "ajax": '../ajax/data/arrays.txt'
                             ajax: {
                               url: '{{ url('getAdjustedLeave') }}',
                               dataSrc: 'adjustedleave'
                             },
                             columns: [
                               {
                                 sortable: false,
                                 orderable: false,
                                 "render": function ( data, type, full, meta ) {
                                   return '<input type="checkbox" name="selectrow0" id="selectrow0" class="selectrow0" value="'+full.Id+'" onclick="uncheck(0)">';
                                 }
                               },
                               { data: "Id"},
                               { data: "StaffId",title:"Staff ID"},
                               { data: "Name",title:"Name"},
                               { data: "Grade",title:"Grade"},
                               { data: "Position",title:"Position"},
                               @foreach($entitlementLeaveTypes as $type)
                                {
                                  data: '{{ str_replace(" ", "_", $type) }}',
                                  title:"{{ $type }}"
                                }, {
                                  data: '{{ str_replace(" ", "_", $type) }}_Adjusted',
                                  title:"{{ $type }} Adjusted",
                                  render: function (data, type, full, meta) {
                                    if (data > 0) {
                                      return '<span class="text-success"><a href="{{ url('leaveadjustmentshistory') }}/' + full.Id + '/'+ '{{ $type }}' + '">'+ data +'</a></span>';
                                    } else if (data < 0) {
                                      return '<span class="text-danger"><a href="{{ url('leaveadjustmentshistory') }}/' + full.Id + '/'+ '{{ $type }}' + '">'+ data +'</a></span>';
                                    } else {
                                      return '<a href="{{ url('leaveadjustmentshistory') }}/' + full.Id + '/'+ '{{ $type }}' + '">'+ data +'</a></span>';
                                    }
                                  }
                                },
                               @endforeach
                             ],
                             select: {
                                     style:    'os',
                                     selector: 'tr'
                                   },
                             buttons: [

                             ],

                 });

              $(".stafflisttable thead input").keyup ( function () {

                      /* Filter on the column (the index) of this element */
                      if ($('#stafflisttable').length > 0)
                      {

                          var colnum=document.getElementById('stafflisttable').rows[0].cells.length;

                          if (this.value=="[empty]")
                          {

                             stafflisttable.fnFilter( '^$', this.name,true,false );
                          }
                          else if (this.value=="[nonempty]")
                          {

                             stafflisttable.fnFilter( '^(?=\\s*\\S).*$', this.name,true,false );
                          }
                          else if (this.value.startsWith("!")==true && this.value.length>1)
                          {

                             stafflisttable.fnFilter( '^(?'+ this.value +').*', this.name,true,false );
                          }
                          else if (this.value.startsWith("!")==false)
                          {

                              stafflisttable.fnFilter( this.value, this.name,true,false );
                          }
                      }



              } );

             $("#ajaxloader").hide();
             $("#ajaxloader2").hide();
             $("#ajaxloader3").hide();

             $('a[data-toggle="tab"]').on( 'shown.bs.tab', function (e) {
               var target = $(e.target).attr("href") // activated tab

               if (target=="#stafflist")
               {
                 $.fn.dataTable.tables( {visible: true, api: true} ).columns.adjust();
               }

             } );


        } );

      </script>
@endsection


@section('content')



    <div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
      <h1>
        Leave Batch Adjustment
        <small>Resource Management</small>
      </h1>
      <ol class="breadcrumb">
        <li><a href="{{ url('/home') }}"><i class="fa fa-dashboard"></i> Home</a></li>
        <li><a href="#">Management Tool</a></li>
        <li><a href="#">HR Management</a></li>
        <li class="active">Leave Batch Adjustment</li>
      </ol>
    </section>

    <!-- Main content -->
    <section class="content">
      <div class="row">

        <div class="modal fade" id="batchLeaveApplyModal" role="dialog" aria-labelledby="batchLeaveApplyLabel" style="display: none;">
          <div class="modal-dialog" role="document">
            <div class="modal-content">
              <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">×</span></button>
                <h4 class="modal-title" id="batchLeaveApplyLabel">Batch Leave Application</h4>
              </div>
              <div class="modal-body">
                <form enctype="multipart/form-data" id="upload_form" role="form" method="POST" action="" >
                  <div class="box-body">
                    <div class="row">
                      <div class="col-md-6">
                         <input type="hidden" name="UserId" value="{{ $me->UserId }}">
                         <div class="form-group">
                          <label>Leave Type : </label>

                          <select class="form-control select2" id="Leave_Type" name="Leave_Type" style="width: 100%;">
                            <option></option>

                            @foreach($options as $option)
                                <option <?php if(Input::old('Leave_Type') == '{{$option->Option}}') echo ' selected="selected" '; ?>>{{$option->Option}}</option>
                            @endforeach

                            </select>
                        </div>



                         <!-- Date -->
                           <div class="form-group">
                             <label>Start Date : </label>

                             <div class="input-group date">
                               <div class="input-group-addon">
                                 <i class="fa fa-calendar"></i>
                               </div>
                               <input type="text" class="form-control pull-right" id="Start_Date" name="Start_Date" value="{!! old('Start_Date') !!}" autocomplete="off">
                             </div>
                             <!-- /.input group -->
                           </div>

                           <!-- /.form group -->

                           <!-- Date -->
                           <div class="form-group">
                             <label>End Date : </label>

                             <div class="input-group date">
                               <div class="input-group-addon">
                                 <i class="fa fa-calendar"></i>
                               </div>
                               <input type="text" class="form-control pull-right" id="End_Date" name="End_Date" value="{!! old('End_Date') !!}" autocomplete="off">
                             </div>
                             <!-- /.input group -->
                           </div>
                           <!-- /.form group -->
                           <!-- textarea -->
                           <div class="form-group">
                             <label>Reason : </label>
                             <textarea class="form-control" rows="3" name="Reason" id="Reason" placeholder="Enter your reason here ...">{!! old('Reason') !!}</textarea>
                           </div>

                           <div class="form-group">
                             <label>Attachment : </label>

                             <input type="file" id="attachment[]" name="attachment[]" accept=".png,.jpg,.jpeg,.pdf" multiple>
                           </div>
                    </div>
                    <div class="col-md-6">

                        <div class="box">
                          <div class="box-body no-padding">
                            <table class="table table-condensed" id="LeaveListTable">
                              <thead>
                                <tr>
                                  <th>Date</th>
                                  <th>Description</th>
                                  <th>Period</th>
                                </tr>
                                <tr>
                                  <td colspan="3" class="text-center"><h5>Please select start and end dates<h5></td>
                                </tr>
                              </thead>
                              <tbody>
                              </tbody>
                            </table>
                          </div>
                          <!-- /.box-body -->
                        </div>


                        <div class="form-group">
                          <label>No of Days : </label>

                          <div class="input-group date">
                            <input type="text" class="form-control pull-right" id="No_Of_Days" name="No_Of_Days" value="{!! old('No_Of_Days') !!}" readonly>
                            <br><br><img src="{{ URL::to('/') ."/img/ajax-loader.gif" }}" width="50px" height="50px" alt="Loading" name='ajaxloader3' id="ajaxloader3">
                          </div>
                          <!-- /.input group -->
                        </div>




                  </div>
                      </div>
                    </div>
                </form>
                  <!-- /.box-body -->

              </div>
              <div class="modal-footer">
                <button type="submit" class="btn btn-primary" onclick="applyleave()" id="btnleavesubmit">Submit</button>
                <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
              </div>
            </div>
          </div>
        </div>

        <div class="modal fade" id="batchAdjustmentApplyModal" role="dialog" aria-labelledby="batchAdjustmentApplyLabel" style="display: none;">
          <div class="modal-dialog modal-sm" role="document">
            <div class="modal-content">
              <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">×</span></button>
                <h4 class="modal-title" id="batchAdjustmentApplyLabel">Batch Leave Adjustment</h4>
              </div>
              <div class="modal-body">
                <form class="form" id="leaveAdjustmentForm">
                   <div class="form-group">
                    <label>Leave Type : </label>

                    <select class="form-control select2" name="Leave_Type" style="width: 100%;">
                      <option></option>

                      @foreach($entitlementLeaveTypes as $type)
                          <option>{{ $type }}</option>
                      @endforeach

                      </select>
                  </div>
                  <div class="form-group">
                    <label>Year : </label>

                    <select class="form-control select2" name="Year" style="width: 100%;">
                      <option>{{ date('Y') }}</option>
                      <option>{{ date('Y') + 1 }}</option>
                      <option>{{ date('Y') - 1 }}</option>
                      </select>
                  </div>
                  <div class="form-group">
                    <label>Remarks : </label>
                    <textarea class="form-control" name="Remarks" rows="3"></textarea>
                  </div>
                  <div class="form-group">
                    <label>No of days : </label>

                    <div class="input-group date">
                      <div class="input-group-addon">
                        <i class="fa fa-edit"></i>
                      </div>
                      <input type="number" name="Adjustment_No_Of_Days" id="Adjustment_No_Of_Days" class="form-control pull-right">
                    </div>
                     <span id="helpBlock" class="help-block"><small>To deduct place a negative sign for no of days.</small></span>
                  </div>
                </form>
              </div>
              <div class="modal-footer">
                <button type="submit" class="btn btn-primary" onclick="adjustment()" id="btnadjustmentsubmit">Submit</button>
                <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
              </div>
            </div>
          </div>
        </div>
        <div class="modal modal-danger fade" id="error-alert">
          <div class="modal-dialog">
            <div class="modal-content">
              <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                  <span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title">Alert!</h4>
              </div>
              <div class="modal-body">
              <ul></ul>
              </div>
              <div class="modal-footer">
                <button type="button" class="btn btn-outline" data-dismiss="modal">Close</button>
              </div>
            </div>
          </div>
        </div>

        <div class="modal modal-success fade" id="update-alert">
          <div class="modal-dialog">
            <div class="modal-content">
              <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                  <span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title">Alert!</h4>
              </div>
              <div class="modal-body">
              <ul></ul>
              </div>
              <div class="modal-footer">
                <button type="button" class="btn btn-outline" data-dismiss="modal">Close</button>
              </div>
            </div>
          </div>
        </div>
       <!--  <div id="update-alert" class="alert alert-success alert-dismissible" style="display:none;">
          <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
          <h4><i class="icon fa fa-check"></i> Alert!</h4>
          <ul>

          </ul>
        </div>

         <div id="error-alert" class="alert alert-danger alert-dismissible" style="display:none;">
           <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
           <h4><i class="icon fa fa-ban"></i> Alert!</h4>
           <ul>

           </ul>
         </div> -->


        <div class="col-md-12">
          <div class="nav-tabs-custom">
            <ul class="nav nav-tabs">
              <li class="active"><a href="#stafflist" data-toggle="tab" id="stafflisttab">Staff List</a></li>
            </ul>

          <div class="tab-content">

              <div class="active tab-pane" id="stafflist">
                <button data-toggle="modal" data-target="#batchLeaveApplyModal" class="btn btn-success" >Apply Leave</button>
                <button data-toggle="modal" data-target="#batchAdjustmentApplyModal" class="btn btn-success" >Leave Adjustment</button>
                <br><br>
                <table id="stafflisttable" class="display stafflisttable" cellspacing="0" width="100%" padding="30px" style="font-size: 13px;">
                    <thead>
                        {{-- prepare header search textbox --}}
                        {{-- prepare header search textbox --}}
                        @if($stafflist)
                          <tr class="search">

                            @foreach($stafflist as $key=>$value)

                              @if ($key==0)
                                <?php $i = 0; ?>

                                @foreach($value as $field=>$a)
                                    @if ($i==0|| $i==1 )
                                      <th align='center'><input type='hidden' class='search_init' name='{{$i}}' placemark='{{$a}}'></th>
                                    @else
                                      <th align='center'><input type='text' class='search_init' name='{{$i}}'  placemark='{{$a}}'></th>
                                    @endif

                                    <?php $i ++; ?>
                                @endforeach


                                <th align='center'><input type='text' class='search_init' name='{{$i}}'  placemark='{{$a}}'></th>

                              @endif

                            @endforeach
                          </tr>
                        @endif

                        <tr>
                          @foreach($stafflist as $key=>$value)

                            @if ($key==0)
                              <td><input type="checkbox" name="selectall0" id="selectall0" value="all" onclick="checkall(0)"></td>
                              @foreach($value as $field=>$value)
                                  <td/>{{ $field }}</td>
                              @endforeach

                            @endif

                          @endforeach
                        </tr>
                    </thead>
                    <tbody>

                      <?php $i = 0; ?>
                      @foreach($stafflist as $leave)
                          <tr id="row_{{ $i }}">
                            <td></td>
                              @foreach($leave as $key=>$value)
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
          <!-- /.nav tab content -->
        </div>
        <!-- /.av-tabs-custom -->

      </div>
      <!-- /.row -->
    </section>
    <!-- /.content -->
  </div>
  <!-- /.content-wrapper -->
  <footer class="main-footer">
    <div class="pull-right hidden-xs">
      <b>Version</b> 1.0.0
    </div>
    <strong>Copyright &copy; 2014-2016 <a href="http://www.softoya.com">TrackerOnTheGo</a>.</strong> All rights
    reserved.
  </footer>

<script>
   function calculatePeriod() {
    var Leave_Period = $('[name^="Leave_Period"]');
    var Days = 0;
    Leave_Period.each(function() {
      var element = $(this);
      if (element.val() == "AM" || element.val() == "PM") {
        Days += 0.5;
      } else if (element.val() == "Full") {
        Days += 1;
      } else if (element.val() == '1 Hour') {
        Days += 0.125;
      } else if (element.val() == '2 Hours') {
        Days += 0.25;
      }
    });
    $("#No_Of_Days").val(Days);
  }
  $(function () {

    $('#Leave_Type').on('select2:select', function (e) {
      calculateDates();
    });
    /**
     * Calculate and retrieve dates from backend
     */
    function calculateDates() {

      var Start_Date = $("#Start_Date").val();
      var End_Date = $("#End_Date").val();
      var Leave_Type = $("#Leave_Type").val();
      if (Start_Date == "" || End_Date == "" || Leave_Type == "") {
        return;
      }

      var date_received = $("#Start_Date").datepicker('getDate');
      var date_completed = $("#End_Date").datepicker('getDate');

      var diff = date_completed - date_received;
      var days = diff / 1000 / 60 / 60 / 24;

      // check if user wrongly selected between start and end dates
      if (days < 0) {
        // diff = date_received - date_completed;
        // days = diff / 1000 / 60 / 60 / 24;

        //  Exchange the value of Start_Date and End_Date
        $("#Start_Date").datepicker('update', date_completed);
        $("#End_Date").datepicker('update', date_received);

        // read the updated value
        Start_Date = $("#Start_Date").val();
        End_Date = $("#End_Date").val();

      }
      // alert(Start_Date);
      $("#LeaveListTable > thead > tr:eq(1) > td").html(`<i class="fa fa-gear fa-spin"></i> Loading`);
      setTimeout(function () {
        getCalculatedLeaveDays(Start_Date, End_Date);
      }, 300);

    }

    function getCalculatedLeaveDays(Start_Date, End_Date) {

      var boxes = $('input[type="checkbox"]:checked', stafflisttable.fnGetNodes() );
      var id=0;

      if (boxes.length>0) {
        id=boxes[0].value;
      } else {
        return;
      }


      $.ajaxSetup({
        headers: { 'X-CSRF-Token' : $('meta[name=_token]').attr('content') }
      });


      $.ajax('/fetchCalculatedLeaveDaysForUser?Start_Date=' + Start_Date + '&End_Date=' + End_Date, {
        type: 'GET',  // http method
        data: {
          'Leave_Type': $("#Leave_Type").val(),
          'UserId': id
        },  // data to submit
        success:
          function (data, status, xhr) {
            // setTimeout(function () {
              // console.log(data);
              $("#No_Of_Days").val(data.calculated_days)
              $("#LeaveListTable > thead > tr:eq(1)").remove();
              $('#LeaveListTable > tbody').empty();
              var counter = 0;
              if ($("#Leave_Type").val() == 'Maternity Leave' || $("#Leave_Type").val() == 'Hospitalization Leave') {
                $('#LeaveListTable > tbody').append(`<tr class='active'>
                  <td>From: ${data.list[0].Date}</td>
                  <td></td>
                  <td>Full</td>
                </tr>
                <tr class='active'>
                  <td>To: ${data.list[data.list.length-1].Date}</td>
                  <td></td>
                  <td>Full</td>
                </tr>`);
              } else {
                data.list.forEach(function(element) {
                  if ($("#Leave_Type").val() != '1 Hour Time Off' && $("#Leave_Type").val() != '2 Hours Time Off') {
                    if (element.Day_Type == 0 || element.Day_Type == 2) {
                      $('#LeaveListTable > tbody').append(`<tr class='active'>
                        <td>${element.Date}</td>
                        <td>${element.Day_Type_Description}</td>
                        <td><input type="hidden" name="Leave_Period[${counter}]" value="Non-Workday"></td>
                      </tr>`);
                    } else if (element.Day_Type == -1) {
                      $('#LeaveListTable > tbody').append(`<tr>
                        <td>${element.Date}</td>
                        <td>${element.Day_Type_Description}</td>
                        <td>
                          <select class="form-control input-sm" name="Leave_Period[${counter}]" onChange="calculatePeriod()">
                            <option value="AM">AM</option>
                          </select>
                        </td>
                      </tr>`);
                    } else {
                      $('#LeaveListTable > tbody').append(`<tr>
                        <td>${element.Date}</td>
                        <td>${element.Day_Type_Description}</td>
                        <td>
                          <select class="form-control input-sm" name="Leave_Period[${counter}]" onChange="calculatePeriod()">
                            <option value="Full">Full</option>
                            <option value="AM">AM</option>
                            <option value="PM">PM</option>
                          </select>
                        </td>
                      </tr>`);
                    }
                  } else {
                    if (element.Day_Type == 0 || element.Day_Type == 2) {
                      $('#LeaveListTable > tbody').append(`<tr class='active'>
                        <td>${element.Date}</td>
                        <td>${element.Day_Type_Description}</td>
                        <td><input type="hidden" name="Leave_Period[${counter}]" value="Non-Workday"></td>
                      </tr>`);
                    } else {
                      $('#LeaveListTable > tbody').append(`<tr>
                        <td>${element.Date}</td>
                        <td>${element.Day_Type_Description}</td>
                        <td>
                          <input type="hidden" name="Leave_Period[${counter}]" value="${ $("#Leave_Type").val() == '1 Hour Time Off' ? '1 Hour' : '2 Hours' }">
                          <span>${ $("#Leave_Type").val() == '1 Hour Time Off' ? '1 Hour' : '2 Hours' }</span>
                        </td>
                      </tr>`);
                    }
                  }
                  counter += 1;
                });
              }
              // location.reload(true);
            // }, 200)
          },
        error:
          function (jqXhr, textStatus, errorMessage) {

          }
      });
    }

    //Initialize Select2 Elements
    $(".select2").select2();

    //Flat red color scheme for iCheck
    $('input[type="checkbox"].flat-green, input[type="radio"].flat-green').iCheck({
      checkboxClass: 'icheckbox_flat-green',
      radioClass: 'iradio_flat-green'
    });

    //Date picker
    $('#Start_Date').datepicker({
        autoclose: true,
        format: 'dd-M-yyyy',
    }).on('changeDate', function(ev){
        calculateDates();
        //my work here
        $.ajaxSetup({
           headers: { 'X-CSRF-Token' : $('meta[name=_token]').attr('content') }
        });

        start_date=$("#Start_Date").val();
        end_date=$("#End_Date").val();
        leave_type=$("#Leave_Type").val();
        leave_term=$("#Leave_Term").val();

        if(leave_type && leave_term && start_date && end_date)
        {
          $("#ajaxloader3").show();
          $.ajax({
                      url: "{{ url('/myleave/calculatedays') }}",
                      method: "POST",
                      data: {
                      Start_Date:start_date,
                      End_Date:end_date,
                      Leave_Type:leave_type,
                      Leave_Term:leave_term},

                      success: function(response){

                        if (response>0)
                        {
                          $("#No_Of_Days").val(response)

                        }
                        else {

                        }

                        $("#ajaxloader3").hide();

              }
          });
        }

    });

    $('#End_Date').datepicker({
      autoclose: true,
        format: 'dd-M-yyyy',
    }).on('changeDate', function(ev){
        calculateDates();
        //my work here
        $.ajaxSetup({
           headers: { 'X-CSRF-Token' : $('meta[name=_token]').attr('content') }
        });

        start_date=$("#Start_Date").val();
        end_date=$("#End_Date").val();
        leave_type=$("#Leave_Type").val();
        leave_term=$('input[name=Leave_Term]:checked').val();

        if(leave_type && leave_term && start_date && end_date)
        {

          $("#ajaxloader3").show();
          $.ajax({
                      url: "{{ url('/myleave/calculatedays') }}",
                      method: "POST",
                      data: {
                      Start_Date:start_date,
                      End_Date:end_date,
                      Leave_Type:leave_type,
                      Leave_Term:leave_term},

                      success: function(response){

                        if (response>0)
                        {
                          $("#No_Of_Days").val(response)

                        }
                        else {

                        }

                        $("#ajaxloader3").hide();

              }
          });
        }
    });

  });


function checkall(index) {
  if ($("#selectall"+index).is(':checked')) {
      $(".selectrow"+index).prop("checked", true);
      $(".selectrow"+index).trigger("change");
      stafflisttable.api().rows().select();
  } else {
      $(".selectrow"+index).prop("checked", false);
      $(".selectrow"+index).trigger("change");
      stafflisttable.api().rows().deselect();
  }
}

function applyleave() {
  var formData = new FormData($("#upload_form")[0]);

  var boxes = $('input[type="checkbox"]:checked', stafflisttable.fnGetNodes() );
  var ids="";

  if (boxes.length>0) {
    $('#btnleavesubmit').prop('disabled', true);

    for (var i = 0; i < boxes.length; i++) {
      ids+=boxes[i].value+",";
    }
    ids=ids.substring(0, ids.length-1);

    formData.append("Ids", ids);
    // console.log(...formData);
    $.ajaxSetup({
       headers: { 'X-CSRF-Token' : $('meta[name=_token]').attr('content') }
    });

    $("#ajaxloader").show();

    $.ajax({
      url: "{{ url('/leavebatch/apply') }}",
      method: "POST",
      contentType: false,
      processData: false,
      data:formData,
      success: function(response) {
        $('#btnleavesubmit').prop('disabled', false);

        if (response==1) {
            $('html, body').animate({scrollTop: '0px'}, 500);

            $("#Leave_Type").val("").change();
            $("#Approver").val("").change();
            // document.getElementById("Leave_Term_2").checked=false;
            // document.getElementById("Leave_Term_3").checked=false;
            // document.getElementById("Leave_Term_1").checked=true;
            document.getElementById("Start_Date").value = ''
            document.getElementById("End_Date").value = ''
            document.getElementById("Reason").value = ''
            $('#LeaveListTable > tbody').empty();
            $("#attachment").val("");

            $("#ajaxloader").hide();

            var message="Leave application submitted!";
            $("#update-alert ul").html(message);
            $("#update-alert").modal('show');



        } else if (response==-1) {
           $('html, body').animate({scrollTop: '0px'}, 500);
            var obj = jQuery.parseJSON(response);
            var errormessage ="";

            errormessage="<li>Leave application not submmitted!</li>";

            $("#error-alert ul").html(errormessage);
            $("#error-alert").modal('show');

            // setTimeout(function() {
            //   $("#error-alert").fadeOut();
            // }, 10000);

            $("#ajaxloader").hide();

        } else {
            $('html, body').animate({scrollTop: '0px'}, 500);
            var obj = jQuery.parseJSON(response);

              if (obj.hasOwnProperty('overlappedUsers')) {
                // console.log(obj);
              var errormessage ="";
              errormessage=errormessage + "<li>Leaves are not applied for users with overlapped dates.</li>";
              for (var id in obj.overlappedUsers) {

                errormessage=errormessage + "<li> " + obj.overlappedUsers[id].StaffId +  " - " + obj.overlappedUsers[id].Name + "</li>";
              }

            } else {
              // console.log(obj);
              var errormessage ="";

              for (var item in obj) {
                errormessage=errormessage + "<li> " + obj[item] + "</li>";
              }

            }

            $("#error-alert ul").html(errormessage);
            $("#error-alert").modal('show');


            $("#ajaxloader").hide();

        }
        $('#batchLeaveApplyModal').modal('hide');
      }
    });
  }

}

function uncheck(index) {
  if (!$("#selectrow"+index).is(':checked')) {
    $("#selectall"+index).prop("checked", false)
  }
}

function adjustment() {
  var boxes = $('input[type="checkbox"]:checked', stafflisttable.fnGetNodes() );
  var ids="";

  if (boxes.length>0) {
    $('#btnadjustmentsubmit').prop('disabled', true);
    for (var i = 0; i < boxes.length; i++) {
      ids+=boxes[i].value+",";
    }
    ids=ids.substring(0, ids.length-1);

    formData = new FormData($("#leaveAdjustmentForm")[0]);
    formData.append("Ids", ids);
    if($("#Adjustment_No_Of_Days").val() != '') {
        $.ajaxSetup({
           headers: { 'X-CSRF-Token' : $('meta[name=_token]').attr('content') }
        });
        $.ajax({
          url: "{{ url('/leavebatch/adjustment') }}",
          method: "POST",
          contentType: false,
          processData: false,
          data:formData,
          success: function(response) {
            $('#btnadjustmentsubmit').prop('disabled', false);

            $('#batchAdjustmentApplyModal').modal('hide');
            if (response==1) {
                $('html, body').animate({scrollTop: '0px'}, 500);
                var message="Leave adjustment submitted!";
                $("#update-alert ul").html(message);
                $("#update-alert").modal('show');

                stafflisttable.api().ajax.reload();
                // $("#error-alert").hide();
            } else if (response==-1) {
                $('html, body').animate({scrollTop: '0px'}, 500);
                var obj = jQuery.parseJSON(response);
                var errormessage ="";

                errormessage="<li>Leave overlapped!</li>";

                $("#error-alert ul").html(errormessage);
                $("#error-alert").modal('show');


                // setTimeout(function() {
                //   $("#error-alert").fadeOut();
                // }, 10000);

                $("#ajaxloader").hide();

            } else {
                $('html, body').animate({scrollTop: '0px'}, 500);
                var obj = jQuery.parseJSON(response);
                // console.log(obj);
                var errormessage ="";

                for (var item in obj) {
                  errormessage=errormessage + "<li> " + obj[item] + "</li>";
                }

                $("#error-alert ul").html(errormessage);
                $("#error-alert").modal('show');



                $("#ajaxloader").hide();

            }
          }
        });
    }
  }
}
</script>
@endsection
