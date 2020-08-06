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
      .icon{
        padding-top:10px;
      }

      .pre {
     white-space: pre-line;
  }

      .tableheader{
        background-color: gray;
      }

      .assettable{
        text-align: center;
      }
    .widget-user .widget-user-username {
        font-size:16px;
      }

    .contentbox{
      width: 800px;
    }
    .success {
      color: #00a65a;
    }

    .alert2 {
    	color: #dd4b39;
    }

    .warning {
    	color: #f39c12;
    }

    .default {
      color: #0000FF;
    }

    </style>

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



    </script>

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

@endsection

@section('content')

    <div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
      <h1>
        To-Do List Dashboard
      </h1>
      <ol class="breadcrumb">
        <li><a href="#"><i class="fa fa-dashboard"></i> Home</a></li>
        <li>To-Do List</li>
        <li class="active">To-Do List Dashboard</li>
      </ol>
    </section>



    <!-- Main content -->
    <section class="content">

      <div class="row">
      <div id="calendarModal" class="modal fade">
      <div class="modal-dialog">
          <div class="modal-content">
              <div class="modal-header">
                  <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">×</span> <span class="sr-only">close</span></button>
                  <h4 id="calendarmodalTitle" class="modal-title"></h4>
              </div>
              <div id="calendarmodalBody" class="modal-body"> </div>
              <div class="modal-footer">
                  <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
              </div>
          </div>
      </div>
      </div>

        <div class="col-md-3">

          <div class="row">

            <div class="col-lg-12 col-xs-12">
            <!-- Horizontal Form -->
              <div class="box">
                <div class="box-header">
                  <h3 class="box-title">To-Do List</h3>
                  <br>
                  <div align="center">
                    <div class="row">
                    <i class="fa fa-circle default"></i> Assigned&nbsp;&nbsp;&nbsp;
                    <i class="fa fa-circle warning"></i> In-Progress&nbsp;&nbsp;&nbsp;
                    </div>
                    <div class="row">
                    <i class="fa fa-circle alert2"></i> Rejected&nbsp;&nbsp;&nbsp;
                    <i class="fa fa-circle success"></i> Completed&nbsp;&nbsp;&nbsp;
                    </div>
                  </div>
                  <div id="calendar"></div>
                </div>
              </div>
            </div>

          </div>

        </div>

        <div class="col-md-9">

          <div class="col-lg-3 col-xs-3">

            <div class="small-box bg-blue">
              <div class="inner">
                <h3>{{$count->assigned}}</h3>
                <p>Assigned Task</p>
              </div>

                <a target="_blank" href="{{ url('/todolist/Assigned') }}" class="small-box-footer">More info <i class="fa fa-arrow-circle-right"></i></a>
              </div>
          </div>

              <div class="col-lg-3 col-xs-3">

                  <div class="small-box bg-fuchsia">
                    <div class="inner">
                      <h3>{{$count->inprogress}}</h3>
                      <p>In Progress Task</p>
                    </div>

                      <a target="_blank" href="{{ url('/todolist/In Progress') }}" class="small-box-footer">More info <i class="fa fa-arrow-circle-right"></i></a>
                    </div>
                </div>

                <div class="col-lg-3 col-xs-3">

                  <div class="small-box bg-yellow">
                    <div class="inner">
                      <h3>{{$count->rejected}}</h3>
                      <p>Rejected Task</p>
                    </div>

                      <a target="_blank" href="{{ url('/todolist/Rejected') }}" class="small-box-footer">More info <i class="fa fa-arrow-circle-right"></i></a>
                    </div>
                </div>

                <div class="col-lg-3 col-xs-3">

                  <div class="small-box bg-green">
                    <div class="inner">
                      <h3>{{$count->completed}}</h3>
                      <p>Completed Task</p>
                    </div>

                      <a target="_blank" href="{{ url('/todolist/Completed') }}" class="small-box-footer">More info <i class="fa fa-arrow-circle-right"></i></a>
                    </div>
                </div>

                <div class="col-lg-3 col-xs-3">

                  <div class="small-box bg-red">
                    <div class="inner">
                      <h3>{{$overdue->overdue}}</h3>
                      <p>Overdue Task</p>
                    </div>

                      <a target="_blank" href="{{ url('/todolist/Overdue') }}" class="small-box-footer">More info <i class="fa fa-arrow-circle-right"></i></a>
                    </div>
                </div>

                <div class="col-lg-3 col-xs-3">

                  <div class="small-box bg-red">
                    <div class="inner">
                      <h3>{{$overduecompleted->overduecompleted}}</h3>
                      <p>Overdue-Completed Task</p>
                    </div>

                      <a target="_blank" href="{{ url('/todolist/Overdue-Completed') }}" class="small-box-footer">More info <i class="fa fa-arrow-circle-right"></i></a>
                    </div>
                </div>
      </div>





      <!-- /.row -->
    </section>
    <!-- /.content -->
  </div>
  <!-- /.content-wrapper -->
  <footer class="main-footer">
    <div class="pull-right hidden-xs">
      <b>Version</b> 2.0.1
    </div>
    <strong>Copyright &copy; 2014-2016 <a href="http://www.softoya.com">TrackerOnTheGo</a>.</strong> All rights
    reserved.
  </footer>

  <script>
    $(function(){
      var hash = window.location.hash;
      hash && $('ul.nav a[href="' + hash + '"]').tab('show');

      $('.nav-tabs a').click(function (e) {
        $(this).tab('show');
        var scrollmem = $('body').scrollTop() || $('html').scrollTop();
        window.location.hash = this.hash;
        $('html,body').scrollTop(scrollmem);
      });
    });

  $(function () {

    /* initialize the calendar
     -----------------------------------------------------------------*/
    //Date for the calendar events (dummy data)
    var date = new Date();
    var d = date.getDate(),
        m = date.getMonth(),
        y = date.getFullYear();
    $('#calendar').fullCalendar({
      header: {
        left: 'prev,next today',
        center: 'title',
        right: 'listWeek,month,agendaWeek,agendaDay'
      },
      defaultView: 'month',
      buttonText: {
        list:  'List',
        today: 'Today',
        month: 'Month',
        week:  'Week',
        day:   'Day',

      },
      //Random default events
      events: [
         @foreach($showtasks as $schedule)
            {
              title: "{{ $schedule->Name }} - {{ $schedule->Status }}",
              start: new Date("{{date(DATE_ISO8601, strtotime($schedule->assign_date))}}"),
              description:
              "<p>Task : {{$schedule->Current_Task}}</p><p>Assigned Date : {{$schedule->assign_date}}</p><p>Assigned By : {{$schedule->assignby}}</p><p>Target Completion Date : {{$schedule->target_date}}</p>",
              allDay: true,
              @if(strpos($schedule->Status,"Assigned")!==false)
                backgroundColor: "#0000FF", //blue
                borderColor: "#0000FF" //blue
              @elseif(strpos($schedule->Status,"Acknowledged")!==false)
                backgroundColor: "#f39c12", //yellow
                borderColor: "#f39c12" //yellow
              @elseif(strpos($schedule->Status,"Rejected")!==false)
                backgroundColor: "#dd4b39", //red
                borderColor: "#dd4b39" //red
              @else
                backgroundColor: "#00a65a", //green
                borderColor: "#00a65a" //green
              @endif
            },
        @endforeach
      ],
      eventRender: function(event, eventElement) {
          if (event.imageurl) {
              eventElement.find("div.fc-content").prepend("<img src='" + event.imageurl +"' width='20' height='20'>");
          }
      },

      eventClick: function(event, jsEvent, view) {
            $('#calendarmodalTitle').html(event.title);
            $('#calendarmodalBody').html(event.description);
            $('#calendarModal').modal();
    },
      editable: false,
      displayEventTime:false,
      droppable: false, // this allows things to be dropped onto the calendar !!!
    });
  });


function parseHtmlEntities(str) {
    return str.replace(/&#([0-9]{1,3});/gi, function(match, numStr) {
        var num = parseInt(numStr, 10); // read num as normal number
        return String.fromCharCode(num);
    });
}

  </script>

@endsection
