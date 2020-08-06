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
                            { data : 'tasks.Current_Task' , title: "Task"},
                            { data : 'tasks.Previous_Task' , title: "Previous Task"},
                            { data : 'tasks.Current_Task_Date' , title: "Previous Task Date"},
                            { data : 'tasks.Project_Code' , title: "Project Code"},
                            { data : 'tasks.Site_Name' , title: "Site Name"},
                            { data : 'tasks.Threshold' , title: "KPI (Days)"},
                            { data : 'pic' , title: "Person-in-Charge"},
                            { data : 'tasks.assign_date' , title: "Assign Date"},
                            { data : 'tasks.target_date' , title: "Target Date"},
                            { data : 'tasks.complete_date' , title: "Complete Date"},
                            { data : 'users.Name' , title: "Assigned By"},
                            { data : 'taskstatuses.Status' , title: "Status"},
                            { data : null, title:"Action",
                              "render": function ( data, type, full, meta ) {
                                  return '<button class="delete btn btn-default btn-xs" title="Delete" style="width:unset; color:red;" id="'+full.tasks.Id+'"><i class="fa fa-times-circle"></i></button>';
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
    <h1>Task<small>Tasks List</small></h1>

    <ol class="breadcrumb">
      <li><a href="{{ url('/home') }}"><i class="fa fa-dashboard"></i> Home</a></li>
      <li><a href="#">Task</a></li>
      <li class="active">Tasks List</li>
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

      <div class="modal fade" id="Create" role="dialog" aria-labelledby="myModalLabel">
            <div class="modal-dialog" role="document">
              <div class="modal-content">
                <div class="modal-header">
                  <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                  <h4 class="modal-title" id="myModalLabel">Create</h4>
                </div>
                <div class="modal-body">
                  <form id="upload_form">
                    <label>Person-in-Charge</label>
                    <select class="select form-control" name="pic" id="pic">
                      @foreach($user as $u)
                      <option value="{{$u->Id}}">{{$u->Name}}</option>
                      @endforeach
                    </select>
                    <label>Task Description</label>
                    <input type="text" name="task" id="task" class="form-control">
                    <label>Threshold</label>
                    <input type="number" name="threshold" id="threshold" class="form-control">
                    <label>Reminder</label>
                    <select class="select form-control" name="reminder" id="reminder">
                      <option value="Daily">Daily</option>
                      <option value="Weekly">Weekly</option>
                      <option value="Monthly">Monthly</option>
                    </select>
                  </form>
                </div>
                <div class="modal-footer">
                  <!-- <center><img src="{{ URL::to('/') . "/img/ajax-loader.gif" }}" width="50px" height="50px" alt="Loading" name='ajaxloader3' id="ajaxloader3"></center> -->
                  <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                  <button type="button" class="btn btn-primary" id="confirm">Confirm</button>
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
        <div class="col-md-3">
          <div class="input-group">
            <label>Date</label>
            <input type="text" class="form-control" id="range" name="range">
          </div>
        </div>
        <div class="col-md-3">
          <label>Status</label>
          <div class="input-group">
            <select class="form-group select2" id="status_filter" style="width: 280%">
              <option value="" default>None</option>
              <option value="Assigned" <?php if ($status=="Assigned") echo "selected";?>>Assigned</option>
              <option value="In Progress" <?php if ($status=="In Progress") echo "selected";?>>In Progress</option>
              <option value="Rejected" <?php if ($status=="Rejected") echo "selected";?>>Rejected</option>
              <option value="Completed" <?php if ($status=="Completed") echo "selected";?>>Completed</option>
              <option value="Overdue" <?php if ($status=="Overdue") echo "selected";?>>Overdue</option>
              <option value="Overdue-Completed" <?php if ($status=="Overdue-Completed") echo "selected";?>>Overdue-Completed</option>
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
      <b>Version</b> 2.0.1
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
     });

     $(document).ready(function() {
        $(document).on('click', '#create', function(e) {
            $('#Create').modal('show');
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
            window.location.href ="{{ url("/taskslist") }}/" + null + "/" +arr[0]+"/"+arr[1];
          }
          else if(user == "")
          {
            window.location.href ="{{ url("/taskslist") }}/" +status +"/" + arr[0]+"/"+arr[1] + "/" + "All" ;
          }
          else if(status == "")
          {
            window.location.href ="{{ url("/taskslist") }}/" + null +"/" + arr[0]+"/"+arr[1] + "/" + user ;
          }
          else
          {
            window.location.href ="{{ url("/taskslist") }}/" +status +"/" + arr[0]+"/"+arr[1] + "/" + user;
          }
      });
    });
  </script>

@endsection
