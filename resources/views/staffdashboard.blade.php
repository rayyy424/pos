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
        Staff Dashboard
      </h1>
      <ol class="breadcrumb">
        <li><a href="#"><i class="fa fa-dashboard"></i> Home</a></li>
        <li>Human Resource</li>
        <li class="active">Staff Dashboard</li>
      </ol>
    </section>



    <!-- Main content -->
    <section class="content">

        <div class="col-md-12">
            <div class="row">
              <div class="col-md-4">
                <select id="user" class="form-control select2">
                  <option value="All">All</option>
                  @foreach($user as $u)
                  <option value="{{$u->Id}}" <?php if($userid == $u->Id) echo "selected"; ?> >{{$u->Name}}</option>
                  @endforeach
                </select> 
                <br><br>   
              </div>
              <div class="col-md-6">
                  <div class="input-group">
                    <div class="input-group-addon">
                      <i class="fa fa-clock-o"></i>
                    </div>
                    <input type="text" class="form-control" id="range" name="range">
                  </div>
              </div>
              <div class="col-md-2">
                <div class="input-group">
                  <button type="button" class="btn btn-success btn" data-toggle="modal" onclick="refresh();">Refresh</button>
                </div>
              </div>
            </div>
        </div>
            <div class="row">
              <div class="col-lg-3 col-xs-3">
                <div class="small-box bg-red">
                  <div class="inner">
                      <h3>@if($leave!=null)
                        {{$leave->count}}
                        @else
                        0
                        @endif</h3>
                      <p>Leave</p>
                  </div>
                  <a href="{{ url('/staffleave') }}/{{$start}}/{{$end}}/{{$userid}}" class="small-box-footer" target="_blank">More info <i class="fa fa-arrow-circle-right"></i></a>
                </div>
              </div>
              <div class="col-lg-3 col-xs-3">
                <div class="small-box bg-yellow">
                  <div class="inner">
                      <h3>@if($notimein!=null)
                        {{$notimein->count}}
                        @else
                        0
                        @endif</h3>
                      <p>No Time In</p>
                  </div>
                  <a href="{{ url('/staffnotimein') }}/{{$start}}/{{$end}}/{{$userid}}" class="small-box-footer" target="_blank">More info <i class="fa fa-arrow-circle-right"></i></a>
                </div>
              </div>
              <div class="col-lg-3 col-xs-3">
                <div class="small-box bg-blue">
                  <div class="inner">
                      <h4>@if($staffdeductions!=null)
                        {{$staffdeductions->sum}}
                        @else
                        $0
                        @endif</h4>
                      <p>Staff Deduction</p>
                  </div>
                  <a href="{{ url('/staffdeductionsdashboard') }}/{{$start}}/{{$end}}/{{$userid}}" class="small-box-footer" target="_blank">More info <i class="fa fa-arrow-circle-right"></i></a>
                </div>
              </div>
            </div>
            <div class="row">
              <div class="col-lg-3 col-xs-3">
                <div class="small-box bg-green">
                  <div class="inner">
                      <h3>
                        @if($kpiresult!=null)
                        {{$kpiresult}}%
                        @else
                        0%
                        @endif</h3>
                      <p>KPI Result</p>
                  </div>
                  <a href="{{ url('/kpiresult') }}/{{$start}}/{{$end}}/{{$userid}}/{{$year}}" class="small-box-footer" target="_blank">More info <i class="fa fa-arrow-circle-right"></i></a>
                </div>
              </div>
              <div class="col-lg-3 col-xs-3">
                <div class="small-box bg-green">
                  <div class="inner">
                      <h3>
                        @if($cmeresult!=null)
                        {{$cmeresult}}%
                        @else
                        0%
                        @endif</h3>
                      <p>CME Result</p>
                  </div>
                  <a href="{{ url('/cmeresult') }}/{{$start}}/{{$end}}/{{$userid}}/{{$year}}" class="small-box-footer" target="_blank">More info <i class="fa fa-arrow-circle-right"></i></a>
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
      <b>Version</b> 2.0.1
    </div>
    <strong>Copyright &copy; 2014-2016 <a href="http://www.softoya.com">TrackerOnTheGo</a>.</strong> All rights
    reserved.
  </footer>

  <script type="text/javascript">
    $(function(){
      $(".select2").select2();
      var hash = window.location.hash;
      hash && $('ul.nav a[href="' + hash + '"]').tab('show');

      $('.nav-tabs a').click(function (e) {
        $(this).tab('show');
        var scrollmem = $('body').scrollTop() || $('html').scrollTop();
        window.location.hash = this.hash;
        $('html,body').scrollTop(scrollmem);
      });

      $('#range').daterangepicker({locale: {
            format: 'DD-MMM-YYYY'
            },startDate: '{{$start}}',
            endDate: '{{$end}}'});
    });


function parseHtmlEntities(str) {
    return str.replace(/&#([0-9]{1,3});/gi, function(match, numStr) {
        var num = parseInt(numStr, 10); // read num as normal number
        return String.fromCharCode(num);
    });
}

   function refresh()
    {
      var d2=$(".select2").val();
      var d=$('#range').val();
      var arr = d.split(" - ");

      window.location.href ="{{ url("/staff/dashboard") }}/"+arr[0]+"/"+arr[1]+"/"+d2;

    }


  </script>

@endsection
