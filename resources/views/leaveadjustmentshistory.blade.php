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

      .list-group-unbordered > .list-group-item {
        border: 0;
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

          var historytable;

          $(document).ready(function() {
             historytable=$('#historytable').dataTable({
              colReorder: false,
              order: [],
              columnDefs: [{ "visible": false, "targets": [0,5,8] },{"className": "dt-center", "targets": "_all"}],
              columns: [
                { data: "Id"},
                { data: "Adjustment_Leave_Type", title:"Leave"},
                { data: "Adjustment_Value", title: "Adjusted"},
                { data: "Adjustment_Year", title: "Year"},
                { data: "Remarks"},
                { data: "UserId"},
                { data: "ApproverName", title: "Approver" },
                { data: "Created_At", title: "Date", render: function (data) { return $.datepicker.formatDate("d MM yy", new Date(data)); }},
                { data: "Updated_At"}
              ]
             });

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
        <li class="active">Leave Adjustment History</li>
      </ol>
    </section>

    <!-- Main content -->
    <section class="content">
      <div class="row">



        <div class="col-md-12">
          <div class="nav-tabs-custom">
            <ul class="nav nav-tabs">
              <li class="active"><a href="#leaveadjustment" data-toggle="tab" id="stafflisttab">Leave Adjustment History</a></li>
            </ul>

          <div class="tab-content">

              <div class="active tab-pane" id="leaveadjustment">

                     <div class="row">
                      <div class="col-md-3">

                        <ul class="list-group list-group-unbordered">
                           <li class="list-group-item">
                             <b>Staff Id</b> : <p class="pull-right"><i><span id="status">{{ $userDetail->StaffId }}</span></i></p>
                           </li>
                           <li class="list-group-item">
                             <b>Name</b> : <p class="pull-right"><i><span id="status">{{ $userDetail->Name }}</span></i></p>
                           </li>
                           <li class="list-group-item">
                             <b>Grade</b> : <p class="pull-right"><i><span id="status">{{ $userDetail->Grade }}</span></i></p>
                           </li>
                           <li class="list-group-item">
                             <b>Position</b> : <p class="pull-right"><i><span id="status">{{ $userDetail->Position }}</span></i></p>
                           </li>
                        </ul>
                      </div>
                      <div class="col-md-3">
                        <ul class="list-group list-group-unbordered">
                           <li class="list-group-item">
                             <b>Leave Type</b> : <div class="pull-right">
                              <div class="form-group">
                              <input type="hidden" name="UserId" id="UserId" value="{{ $userId }}">
                              <select class="form-control select2" id="Leave_Type" name="Leave_Type"  onchange="changeLeaveType()">
                                <option value="">Show All Leave Type</option>
                                @foreach ($leaveTypes as $key => $type)
                                    <option <?php if($type == $leaveType) echo ' selected="selected" '; ?>>{{$type}}</option>
                                @endforeach
                              </select>
                              </div>
                             </div>
                             <br><br>
                           </li>
                        </ul>
                      </div>

                </div>
                <table id="historytable" class="display historytable" cellspacing="0" width="100%" padding="30px" style="font-size: 13px;">
                    <thead>
                        {{-- prepare header search textbox --}}
                        {{-- prepare header search textbox --}}
                        @if($leaveadjustmentshistory)
                          <tr class="search">

                            @foreach($leaveadjustmentshistory as $key=>$value)

                              @if ($key==0)
                                <?php $i = 0; ?>

                                @foreach($value as $field=>$a)
                                    @if ($i==0 )
                                      <th align='center'><input type='hidden' class='search_init' name='{{$i}}' placemark='{{$a}}'></th>
                                    @else
                                      <th align='center'><input type='text' class='search_init' name='{{$i}}'  placemark='{{$a}}'></th>
                                    @endif

                                    <?php $i ++; ?>
                                @endforeach



                              @endif

                            @endforeach
                          </tr>
                        @endif

                        <tr>
                          @foreach($leaveadjustmentshistory as $key=>$value)

                            @if ($key==0)
                              @foreach($value as $field=>$value)
                                  <td/>{{ $field }}</td>
                              @endforeach

                            @endif

                          @endforeach
                        </tr>
                    </thead>
                    <tbody>

                      <?php $i = 0; ?>
                      @foreach($leaveadjustmentshistory as $leave)
                          <tr id="row_{{ $i }}">
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

  <script type="text/javascript">
    function changeLeaveType() {
      var Leave_Type = $("#Leave_Type").val();
      var UserId = $("#UserId").val();

      window.location.href = '{{ url('leaveadjustmentshistory') }}/' + UserId + '/' + Leave_Type;
    }
  </script>

@endsection
