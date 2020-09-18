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
        Dashboard
      </h1>
      <ol class="breadcrumb">
        <li><a href="#"><i class="fa fa-dashboard"></i> Home</a></li>
        <li>Delivery Management</li>
        <li class="active">Delivery Dashboard</li>
      </ol>
    </section>



    <!-- Main content -->
    <section class="content">

      <div class="row">

        <div class="col-md-3">

          <div class="row">

            <div class="col-lg-12 col-xs-12">
            <!-- Horizontal Form -->
              <div class="box">
                <div class="box-header">
                  <h3 class="box-title">Delivery Schedule</h3>
                  <br>
                  <div align="center">
                    <i class="fa fa-circle success"></i> Accepted&nbsp;&nbsp;&nbsp;
                    <i class="fa fa-circle alert2"></i> Processing&nbsp;&nbsp;&nbsp;
                    <i class="fa fa-circle warning"></i> Pending&nbsp;&nbsp;&nbsp;
                  </div>
                  <div id="calendar"></div>
                </div>
              </div>
            </div>

          </div>

        </div>

        <div class="col-md-9">

         

          <div class="col-lg-3 col-xs-3">

            <div class="small-box bg-red">
              <div class="inner">
                <h3>{{$pendingNum[0]->pending}}</h3>
                <p>Pending Approval</p>
              </div>

                <a href="{{ url('/deliveryapproval') }}" class="small-box-footer">More info <i class="fa fa-arrow-circle-right"></i></a>
              </div>
          </div>

              <div class="col-lg-3 col-xs-3">

                  <div class="small-box bg-red">
                    <div class="inner">
                      <h3>{{$rejected[0]->rejected}}</h3>
                      <p>Rejected Delivery</p>
                    </div>

                      <a href="{{ url('/deliveryapproval#processingdelivery') }}" class="small-box-footer">More info <i class="fa fa-arrow-circle-right"></i></a>
                    </div>
                </div>

                <div class="col-lg-3 col-xs-3">

                  <div class="small-box bg-red">
                    <div class="inner">
                      <h3>{{$insufficientdelivery[0]->insufficient}}</h3>
                      <p>Insufficient Stock Delivery</p>
                    </div>

                      <a href="{{ url('/deliveryapproval#insufficientdelivery') }}" class="small-box-footer">More info <i class="fa fa-arrow-circle-right"></i></a>
                    </div>
                </div>

                <div class="col-lg-3 col-xs-3">

                  <div class="small-box bg-red">
                    <div class="inner">
                      <h3>{{$overdue[0]->overdue}}</h3>
                      <p>Incompleted/Overdue Delivery</p>
                    </div>

                      <a href="{{ url('/deliveryapproval') }}" class="small-box-footer">More info <i class="fa fa-arrow-circle-right"></i></a>
                    </div>
                </div>

                <div class="col-lg-3 col-xs-3">

                  @if($completedNum[0]->completed == 0)
                  <div class="small-box bg-red">
                  @else
                  <div class="small-box bg-green">
                  @endif
                    <div class="inner">
                      <h3>{{$completedNum[0]->completed}}</h3>
                      <p>Completed Delivery</p>
                    </div>

                      <a href="{{ url('/deliveryapproval#completeddelivery') }}" class="small-box-footer">More info <i class="fa fa-arrow-circle-right"></i></a>
                    </div>
                </div>

                <div class="col-lg-3 col-xs-3">
                  @if($finaldelivery[0]->finalappr == 0)
                  <div class="small-box bg-red">
                  @else
                  <div class="small-box bg-green">
                  @endif
                    <div class="inner">
                      <h3>{{$finaldelivery[0]->finalappr}}</h3>
                      <p>Final Approval Delivery</p>
                    </div>

                      <a href="{{ url('/deliveryapproval') }}" class="small-box-footer">More info <i class="fa fa-arrow-circle-right"></i></a>
                    </div>
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
      <b>Version</b> 1.0.0
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
        @foreach($pending as $p)
        {
            title: parseHtmlEntities("{{$p->Name}}"),
            start:new Date("{{date(DATE_ISO8601, strtotime($p->delivery_date))}}"),
            end:new Date("{{date(DATE_ISO8601, strtotime($p->delivery_date))}}"),
            allday:true,
            backgroundColor: "#f39c12", //yellow
            borderColor: "#f39c12" //yellow
        },
        @endforeach

        @foreach($processing as $proc)
        {
            title: parseHtmlEntities("{{$proc->DO_No}} - {{$proc->Name}}"),
            start:new Date("{{date(DATE_ISO8601, strtotime($proc->delivery_date))}}"),
            end:new Date("{{date(DATE_ISO8601, strtotime($proc->delivery_date))}}"),
            allday:true,
            backgroundColor: "#dd4b39", //red
            borderColor: "#dd4b39"
        },
        @endforeach
        @foreach($accepted as $accep)
        {
            title: parseHtmlEntities("{{$accep->DO_No}} - {{$accep->Name}}"),
            start:new Date("{{date(DATE_ISO8601, strtotime($accep->delivery_date))}}"),
            end:new Date("{{date(DATE_ISO8601, strtotime($accep->delivery_date))}}"),
            allday:true,
            backgroundColor: "#00a65a", //green
            borderColor: "#00a65a" //green
        },
        @endforeach
      ],
      eventRender: function(event, eventElement) {
          if (event.imageurl) {
              eventElement.find("div.fc-content").prepend("<img src='" + event.imageurl +"' width='20' height='20'>");
          }
      },

      eventClick: function(event) {
        if (event.url) {
            window.open(event.url);
            return false;
        }
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
