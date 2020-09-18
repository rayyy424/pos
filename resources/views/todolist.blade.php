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
    <!-- <link href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datetimepicker/4.17.37/css/bootstrap-datetimepicker.min.css" rel="stylesheet"> -->
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

      .interntable{
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
      <script type="text/javascript" language="javascript" src="https://cdn.datatables.net/plug-ins/1.10.13/api/sum().js"></script>
      <script type="text/javascript" language="javascript" src="{{ asset('/plugin/js/dataTables.editor.min.js') }}"></script>
      <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.9.0/moment.min.js"></script>
      <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datetimepicker/4.17.37/js/bootstrap-datetimepicker.min.js"></script>

      {{-- chart js --}}
      <script type="text/javascript" language="javascript" src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.5.0/Chart.bundle.min.js"></script>

      <script type="text/javascript" language="javascript" class="init">
        var oTable1;

        $(document).ready(function() {
             oTable1=$('#listable').dataTable({
                          columnDefs: [{ "visible": false, "targets":[1]},{"className": "dt-center", "targets": "_all"}],
                            responsive: false,
                            dom: "Bltip",
                            sScrollX: "100%",
                            sScrollY: "100%",
                            scrollCollapse: true,
                            iDisplayLength:100,
                            bAutoWidth: true,
                            iDisplayLength:10,
                            rowId:"tracker.Id",
                            columns: [
                            { data : null, "render":"", title: "No"},
                            { data : 'tasks.Id' , title: "Id"},
                            { data : 'users.Name' , title: "Person-in-Charge"},
                            { data : 'assignby' , title: "Assigned By"},
                            { data : 'tasks.Current_Task' , title: "Task"},
                            { data : 'tasks.assign_date' , title: "Assign Date"},
                            { data : 'tasks.target_date' , title: "Target Date"},
                            { data : 'tasks.complete_date' , title: "Complete Date"},
                            { data : 'tasks.reminder' , title: "Reminder"},
                            { data : 'taskstatuses.Status' , title: "Status"},
                            { data : 'tasks.taskrepeat' , title: "Repeat",
                              "render": function ( data, type, full, meta ) {
                                  if(data == 1)
                                  {
                                    return '<span class="label label-success">YES</span>';
                                  }
                                  else
                                  {
                                    return '<span class="label label-danger">NO</span>';
                                  }
                              }
                            },
                            { data : 'tasks.repeattype' , title: "Repeat Type"},
                            { data : null, title:"Action",
                              "render": function ( data, type, full, meta ) {
                                var button = '<button class="details btn btn-default btn-xs" title="Edit" style="width:unset;color:blue" id="'+full.tasks.Id+'"><i class="fa fa-edit"></i></button>';
                                if({{$me->Delete_Todo}})
                                {
                                  button = button + ' <button class="delete btn btn-default btn-xs" title="Delete" style="width:unset; color:red;" id="'+full.tasks.Id+'"><i class="fa fa-times-circle"></i></button>';
                                }
                                if(full.taskstatuses.Status == "Assigned")
                                {
                                  button = button + ' <button class="accept btn btn-default btn-xs" title="Accept" style="width:unset; color:green;" id="'+full.tasks.Id+'"><i class="fa fa-check-square-o"></i></button>';
                                }
                                if(full.taskstatuses.Status == "In Progress")
                                {
                                  button = button + ' <button class="complete btn btn-default btn-xs" title="Complete" style="width:unset; color:green;" id="'+full.tasks.Id+'"><i class="fa fa-check-circle"></i></button>';
                                }
                                  return button;
                              }
                            }
                    ],
                                 select: true,
                                 buttons: [
                                 ],
                     });

          oTable1.on( 'order.dt search.dt', function () {
          oTable1.api().column(0, {search:'applied', order:'applied'}).nodes().each( function (cell, i) {
            cell.innerHTML = i+1;
          } );
          } ).api().draw();

            $(".listable thead input").keyup ( function () {
            if ($('#listable').length > 0)
            {
                var colnum=document.getElementById('listable').rows[0].cells.length;
                if (this.value=="[empty]")
                {
                    oTable1.fnFilter( '^$', this.name,true,false );
                }
                else if (this.value=="[nonempty]")
                {
                    oTable1.fnFilter( '^(?=\\s*\\S).*$', this.name,true,false );
                }
                else if (this.value.startsWith("!")==true && this.value.length>1)
                {
                    oTable1.fnFilter( '^(?'+ this.value +').*', this.name,true,false );
                }
                else if (this.value.startsWith("!")==false)
                {
                    oTable1.fnFilter( this.value, this.name,true,false );
                }
            }
        });
        });
      </script>

@endsection

@section('content')

<div class="content-wrapper">
  <!-- Content Header (Page header) -->
  <section class="content-header">
    <h1>To-Do List<small>To-Do List</small></h1>

    <ol class="breadcrumb">
      <li><a href="{{ url('/home') }}"><i class="fa fa-dashboard"></i> Home</a></li>
      <li><a href="#">To-Do List</a></li>
      <li class="active">To-Do List</li>
    </ol>
  </section>

  <br>

  <section class="content">
    <div class="row">
      <div class="modal fade" id="Delete" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
            <div class="modal-dialog" role="document">
              <div class="modal-content">
                <div class="modal-header">
                  <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                  <h4 class="modal-title" id="myModalLabel">Delete</h4>
                </div>
                <div class="modal-body">
                  <input type="hidden" id="deleteid">
                  Are you sure you want to delete this?
                </div>
                <div class="modal-footer">
                  <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                  <button type="button" class="btn btn-primary" id="deletethis">Delete</button>
                </div>
              </div>
            </div>
      </div>

      <div class="modal fade" id="ActionModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
            <div class="modal-dialog" role="document">
              <div class="modal-content">
                <div class="modal-header">
                  <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                  <h4 class="modal-title" id="myModalLabel">Confirm</h4>
                </div>
                <div class="modal-body">
                  <div id="confirmtext">
                  </div>
                  <input type="hidden" name="confirmid" id="confirmid">
                  <div id="td">
                  <label>Target Date</label>
                  <input type="text" name="target_date" id="target_date" class="datepicker form-control"> 
                  </div>
                  <div id="tt">
                  <label>Target Time</label>
                  <input type="text" name="target_time" id="target_time" class="timepicker form-control">
                  </div>
                </div>
                <div class="modal-footer">
                  <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                  <button type="button" class="btn btn-primary" id="confirmcomplete">Complete</button>
                  <button type="button" class="btn btn-primary" id="confirmaccept">Accept</button>
                </div>
              </div>
            </div>
      </div>

      <div class="modal fade" id="Create" role="dialog" aria-labelledby="myModalLabel">
            <div class="modal-dialog" role="document">
              <div class="modal-content">
                <div class="modal-header">
                  <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                  <h4 class="modal-title" id="myModalLabel">To Do List</h4>
                </div>
                <div class="modal-body">
                  <form id="upload_form">
                    <input type="hidden" name="taskid" id="taskid">
                    <label>Person-in-Charge</label>
                    <select class="select form-control" name="pic" id="pic">
                      @foreach($user as $u)
                      <option value="{{$u->Id}}">{{$u->Name}}</option>
                      @endforeach
                    </select>
                    <label>Assign Date</label>
                    <input type="text" name="assign_date" id="assign_date" class="datepicker form-control">
                    <label>Task Description</label>
                    <input type="text" name="task" id="task" class="form-control">
                    <label>Reminder</label>
                    <select class="select form-control" name="reminder" id="reminder">
                      <option value="Daily">Daily</option>
                      <option value="Weekly">Weekly</option>
                      <option value="Monthly">Monthly</option>
                    </select>
                    <label>Repeat</label>
                    <select class="select form-control" name="repeattype" id="repeattype">
                      <option value="">Does Not Repeat</option>
                      <option value="Day">Daily</option>
                      <option value="Week">Weekly</option>
                      <option value="Month">Monthly</option>
                      <option value="Year">Yearly</option>
                    </select>
                  </form>
                </div>
                <div class="modal-footer">
                  <!-- <center><img src="{{ URL::to('/') . "/img/ajax-loader.gif" }}" width="50px" height="50px" alt="Loading" name='ajaxloader3' id="ajaxloader3"></center> -->
                  <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                  <button type="button" class="btn btn-primary" id="confirm">Confirm</button>
                  <button type="button" class="btn btn-primary" id="update">Update</button>
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

    <div class="row">
        <div class="col-md-2">
          <div class="input-group">
            <label>Date</label>
            <input type="text" class="form-control" id="range" name="range">
          </div>
        </div>
        <div class="col-md-3">
          <label>Status</label>
          <div class="input-group">
            <select class="form-group select2" id="status_filter" style="width: 260%">
              <option value="" default>None</option>
              <option value="Assigned" <?php if ($type=="Assigned") echo "selected";?>>Assigned</option>
              <option value="In Progress" <?php if ($type=="In Progress") echo "selected";?>>In Progress</option>
              <option value="Rejected" <?php if ($type=="Rejected") echo "selected";?>>Rejected</option>
              <option value="Completed" <?php if ($type=="Completed") echo "selected";?>>Completed</option>
              <option value="Overdue" <?php if ($type=="Overdue") echo "selected";?>>Overdue</option>
              <option value="Overdue-Completed" <?php if ($type=="Overdue-Completed") echo "selected";?>>Overdue-Completed</option>
            </select>
          </div>
        </div>
        <div class="col-md-4">
          <label>User</label>
          <div class="input-group">
            <select class="form-group select2" id="user_filter">
              <option value="" default>None</option>
              @foreach($user as $u)
              <option value="{{$u->Id}}">{{$u->Name}}</option>
              @endforeach
            </select>
          </div>
        </div>
        <div class="col-md-2">
          <label>Refresh</label>
          <div class="input-group">
          <button type="button" id="refresh" class="btn btn-success btn" data-toggle="modal">Refresh</button>
          </div>
        </div>
    </div>
    <br>
    @if($me->View_Todolist)
    <div class="row">
      <div class="col-md-2">
      <button class="btn btn-default" id="create"><i><span class="fa fa-plus"></span></i>Create</button>
      </div>
    </div>
    @endif
    <br>
    <div class="box">
    <table id="listable" class="listable" cellspacing="0" width="100%" padding="30px" style="font-size: 13px;">
                       <thead>
                                <tr class="search">
                                @foreach($list as $key=>$value)

                                    @if ($key==0)
                                        <?php $i = 0; ?>
                                            @foreach($value as $field=>$a)
                                                @if ($i==0)
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
                                <tr>
                                    @foreach($list as $key=>$value)

                                        @if ($key==0)
                                        <td></td>

                                        @foreach($value as $field=>$value)
                                        <td>
                                            {{ $field }}
                                        </td>
                                        @endforeach
                                        <td></td>
                                        @endif

                                    @endforeach
                                </tr>
                            </thead>
                            <tbody>
                                <?php $i = 0; ?>
                                @foreach($list as $delivery)

                                <tr>
                                    <td></td>

                                    @foreach($delivery as $key=>$value)
                                      <td>
                                        {{ $value }}
                                      </td>
                                    @endforeach
                                    <td></td>
                                </tr>
                                <?php $i++; ?>
                                @endforeach
                            </tbody>
                            <tfoot></tfoot>
                        </table>
                  </div>

  </section>

  <footer class="main-footer">
    <div class="pull-right hidden-xs">
      <b>Version</b> 1.0.0
    </div>
    <strong>Copyright &copy; 2014-2016 <a href="http://www.softoya.com">TrackerOnTheGo</a>.</strong> All rights reserved.
  </footer>
</div>

<script type="text/javascript">
     $(function () {
        $('.select2').select2();
        $('#range').daterangepicker({locale: {
        format: 'DD-MMM-YYYY'
        },
        startDate: '{{date("d-M-Y",strtotime($start))}}',
        endDate: '{{date("d-M-Y",strtotime($end))}}'});
        $('.datepicker').datepicker({
          autoclose: true,
          format: 'dd-M-yyyy'
        });
        $(".timepicker").datetimepicker({
          format: 'HH:mm:ss'
        });
        $('#update').hide();
     });

     $(document).ready(function() {
        $(document).on('click', '#create', function(e) {
              $('#pic').val(562);
              $('#task').val("");
              $('#assign_date').val("");
              $('#reminder').val("Daily");
              $('#repeattype').val("");

              $('#pic').attr('disabled',false);
              $('#task').attr('readonly',false);
              $('#assign_date').attr('readonly',false);
              $('#confirm').show();
              $('#update').hide();
            $('#Create').modal('show');
        });
      });

     $(document).ready(function() {
    $(document).on('click', '#update', function(e) {
      var data = $('#upload_form').serialize();
        $.ajaxSetup({
         headers: { 'X-CSRF-Token' : $('meta[name=_token]').attr('content') }
      });

        $.ajax({
            url: "{{ url('/todolistupdate') }}",
            method: "POST",
            data: data,
            success: function(response){
                $('#Create').modal('hide');
                if(response==1)
                {
                  var message = "Task Updated";
                  $("#update-alert ul").html(message);
                  $("#update-alert").modal('show');
                  window.location.reload();
                }
                else
                {
                  var message = "Failed to Update Task";
                  $("#error-alert ul").html(message);
                  $("#error-alert").modal('show');
                }
            }
          });
      });
  });

    $(document).ready(function() {
    $(document).on('click', '#confirm', function(e) {
      var data = $('#upload_form').serialize();
        $.ajaxSetup({
         headers: { 'X-CSRF-Token' : $('meta[name=_token]').attr('content') }
      });

        $.ajax({
            url: "{{ url('/todolist/createnew') }}",
            method: "POST",
            data: data,
            success: function(response){
                $('#Create').modal('hide');
                if(response==1)
                {
                  var message = "New Task Assigned";
                  $("#update-alert ul").html(message);
                  $("#update-alert").modal('show');
                  window.location.reload();
                }
                else
                {
                  var message = "Failed to Assign Task";
                  $("#error-alert ul").html(message);
                  $("#error-alert").modal('show');
                }
            }
          });
      });
  });

    $(document).ready(function() {
      $(document).on('click', '.details', function(e) {
          var id = $(this).attr('id');
          $("#taskid").val(id);
          $.ajaxSetup({
         headers: { 'X-CSRF-Token' : $('meta[name=_token]').attr('content') }
      });

        $.ajax({
            url: "{{ url('/todolistgetdetails') }}",
            method: "GET",
            data: {Id:id},
            success: function(response){
              $('#taskid').val(response.details.parentId);
              $('#pic').val(response.details.UserId);
              $('#task').val(response.details.Current_Task);
              $('#assign_date').val(response.details.assign_date);
              $('#reminder').val(response.details.reminder);
              $('#repeattype').val(response.details.repeattype);

              $('#pic').attr('disabled',true);
              $('#task').attr('readonly',true);
              $('#assign_date').attr('readonly',true);
              $('#confirm').hide();
              $('#update').show();
            }
          });
          $('#Create').modal('show');
      });
    });

    $(document).ready(function() {
      $(document).on('click', '.complete', function(e) {
          var id = $(this).attr('id');
          var text = "Are you sure to complete the task?";
          $("#confirmid").val(id);
          $('#td').hide();
          $('#tt').hide();
          $('#confirmtext').html(text);
          $('#confirmcomplete').show();
          $('#confirmaccept').hide();
          $('#ActionModal').modal('show');
      });
    });
    $(document).ready(function() {
      $(document).on('click', '.accept', function(e) {
          var id = $(this).attr('id');
          $("#confirmid").val(id);
          $('#td').show();
          $('#tt').show();
          $('#confirmtext').html("");
          $('#confirmcomplete').hide();
          $('#confirmaccept').show();
          $('#ActionModal').modal('show');
      });
    });
    $(document).ready(function() {
      $(document).on('click', '#confirmcomplete', function(e) {
        var id = $('#confirmid').val();

        $.ajaxSetup({
         headers: { 'X-CSRF-Token' : $('meta[name=_token]').attr('content') }
        });

        $.ajax({
            url: "{{ url('/completetodo') }}",
            method: "POST",
            data: {id:id},
            success: function(response){
                $('#ActionModal').modal('hide');
                if(response==1)
                {
                  var message = "Task Completed!";
                  $("#update-alert ul").html(message);
                  $("#update-alert").modal('show');
                  window.location.reload();
                }
                else
                {
                  var message = "Failed to Complete Task";
                  $("#error-alert ul").html(message + " " + response);
                  $("#error-alert").modal('show');
                }
            }
          });
      });
      $(document).ready(function() {
      $(document).on('click', '#confirmaccept', function(e) {
        var id = $('#confirmid').val();
        var date = $('#target_date').val();
        var time = $('#target_time').val();

        $.ajaxSetup({
         headers: { 'X-CSRF-Token' : $('meta[name=_token]').attr('content') }
        });

        $.ajax({
            url: "{{ url('/accepttodo') }}",
            method: "POST",
            data: {id:id,date:date,time:time},
            success: function(response){
                $('#ActionModal').modal('hide');
                if(response==1)
                {
                  var message = "Task Accepted!";
                  $("#update-alert ul").html(message);
                  $("#update-alert").modal('show');
                  window.location.reload();
                }
                else
                {
                  var message = "Failed to Accept Task";
                  $("#error-alert ul").html(message + " " + response);
                  $("#error-alert").modal('show');
                }
            }
          });
      });
    });
    });

    $(document).ready(function() {
      $(document).on('click', '.delete', function(e) {
          var id = $(this).attr('id');
          $("#deleteid").val(id);
          $('#Delete').modal('show');
      });
    });

    $(document).ready(function() {
      $(document).on('click', '#deletethis', function(e) {
        var id = $("#deleteid").val();
           $.ajaxSetup({
         headers: { 'X-CSRF-Token' : $('meta[name=_token]').attr('content') }
      });

        $.ajax({
            url: "{{ url('/todolist/delete') }}",
            method: "POST",
            data: {Id:id},
            success: function(response){
                $('#Delete').modal('hide');
                if(response==1)
                {
                  var message = "Task Deleted";
                  $("#update-alert ul").html(message);
                  $("#update-alert").modal('show');
                  window.location.reload();
                }
                else
                {
                  var message = "Failed to Delete Task";
                  $("#error-alert ul").html(message);
                  $("#error-alert").modal('show');
                }
            }
          });
      });
    });

    $(document).ready(function() {
      $(document).on('click', '#refresh', function(e) {

          var d=$('#range').val();
          var arr = d.split(" - ");
          var user = $('#user_filter').val();
          var status = $('#status_filter').val();

          if(status == "" && user == "")
          {
            window.location.href ="{{ url("/todolist") }}/" + "All" +"/" +arr[0]+"/"+arr[1];
          }
          else if(user == "")
          {
            window.location.href ="{{ url("/todolist") }}/" + status +"/" +arr[0]+"/"+arr[1];
          }
          else if(status == "")
          {
            window.location.href ="{{ url("/todolist") }}/" + "All" +"/" +arr[0]+"/"+arr[1] + "/" + user;
          }
          else
          {
            window.location.href ="{{ url("/todolist") }}/" + status +"/" +arr[0]+"/"+arr[1] + "/" + user;
          }
      });
    });
  </script>

@endsection
