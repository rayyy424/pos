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

      .info-box-text{
        font-size: 10px;
        font-weight: bold;
        text-overflow:inherit;
        display: inline-table;
      }

      .info-box-icon{
          font-size: 30px;
          height:65px;
          line-height: 65px;
      }

      .info-box{
        height:65px;
        /*line-height: 65px;*/
        min-height: 65px;*/
      }

      .content-header {
    position: relative;
    padding: 2px 2px 0 15px;
}

.info-box-number{
  font-size: 14px;
}

      th{
        text-align: center;
      }

      td{
        text-align: center;
      }

      .money{
        text-align: right;
      }

      h3{
        color: red;
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

@endsection

@section('content')

  <div class="content-wrapper">

    <section class="content">

      <div class="row">
        <div class="box box-primary">
          <div class="box-body">

              <div class="row">
                <div class="col-md-4">
                  <label ><input {{$date ? "checked":""}} type="checkbox" id="dateRange"/>Range:</label>
                  <div class="input-group">
                    <div class="input-group-addon">
                      <i class="fa fa-clock-o"></i>
                    </div>
                    <input type="text" class="form-control" id="range" name="range">
                  </div>
               </div>

                <div class="col-md-1">

                  <br>

                  <div class="input-group">
                    <button type="button" class="btn btn-success" data-toggle="modal" onclick="refresh();">Refresh</button>
                  </div>

                </div>

              </div>

                <div class="row">

                  <div class="col-md-12">

                    <section class="content-header">
                      <h4>
                        Projected Sales
                      </h4>
                    </section>
                @if(!$date)
                <a href="{{url('dashboard')}}/?region=true" target="_blank">
                @else 
                <a href="{{url('dashboard')}}/?region=true&start={{$start}}&end={{$end}}" target="_blank">
                @endif
                  <div class="col-md-2 col-sm-6 col-xs-12">
                    <div class="info-box">
                      <span class="info-box-icon bg-aqua"><i class="fa fa-usd"></i></span>

                      <div class="info-box-content">
                        <span class="info-box-text">Total <br>BOQ Submission<br>Amount</span>
                        <span class="info-box-number">{{$total->Total_PO_Amount ? $total->Total_PO_Amount:"0.00"}}</span>
                      </div>
                      <!-- /.info-box-content -->
                    </div>
                    <!-- /.info-box -->
                  </div>
                </a>
                @if(!$date)
                <a href="{{url('dashboard')}}/?region=true" target="_blank">
                @else 
                <a href="{{url('dashboard')}}/?region=true&start={{$start}}&end={{$end}}" target="_blank">
                @endif
                  <div class="col-md-2 col-sm-6 col-xs-12">
                    <div class="info-box">
                      <span class="info-box-icon bg-green"><i class="fa fa-usd"></i></span>

                      <div class="info-box-content">
                        <span class="info-box-text">Total <br>PO Received</span>
                        <span class="info-box-number">{{$total->PO_Received ? $total->PO_Received:"0.00"}}</span>
                      </div>
                      <!-- /.info-box-content -->
                    </div>
                    <!-- /.info-box -->
                  </div>
                </a>
                @if(!$date)
                <a href="{{url('dashboard')}}/?region=true" target="_blank">
                @else 
                <a href="{{url('dashboard')}}/?region=true&start={{$start}}&end={{$end}}" target="_blank">
                @endif
                  <div class="col-md-2 col-sm-6 col-xs-12">
                    <div class="info-box">
                      <span class="info-box-icon bg-red"><i class="fa fa-usd"></i></span>

                      <div class="info-box-content">
                        <span class="info-box-text">Total <br>BOQ Approved<br>Pending PO</span>
                        <span class="info-box-number">{{$total->Pending_PO? $total->Pending_PO:"0.00"}}</span>
                      </div>
                      <!-- /.info-box-content -->
                    </div>
                    <!-- /.info-box -->
                  </div>
                </a>
                </div>

         </div>

         <div class="row">

           <div class="col-md-12">

             <section class="content-header">
               <h4>
                 Invoice
               </h4>
             </section>
             @if(!$date)
             <a href="{{url('dashboard')}}/?region=true" target="_blank">
             @else 
             <a href="{{url('dashboard')}}/?region=true&start={{$start}}&end={{$end}}" target="_blank">
             @endif
             <div class="col-md-2 col-sm-6 col-xs-12">
               <div class="info-box">
                 <span class="info-box-icon bg-aqua"><i class="fa fa-usd"></i></span>

                 <div class="info-box-content">
                   <span class="info-box-text">Total <br>Invoiced Amount</span>
                   <span class="info-box-number">{{number_format($totalinvoiced->Total_Invoiced_Amount,2)}}</span>
                 </div>
                 <!-- /.info-box-content -->
               </div>
               <!-- /.info-box -->
             </div>
             </a>
             @if(!$date)
              <a href="{{url('dashboard')}}/?region=true" target="_blank">
              @else 
              <a href="{{url('dashboard')}}/?region=true&start={{$start}}&end={{$end}}" target="_blank">
              @endif
             <div class="col-md-2 col-sm-6 col-xs-12">
               <div class="info-box">
                 <span class="info-box-icon bg-yellow"><i class="fa fa-usd"></i></span>

                 <div class="info-box-content">
                   <span class="info-box-text">JOB DONE <br>NOT INVOICE</span>
                   <span class="info-box-number">{{$totalinvoiced->JDNI ? $totalinvoiced->JDNI:"0.00"}}</span>
                 </div>
                 <!-- /.info-box-content -->
               </div>
               <!-- /.info-box -->
             </div>
            </a>
            @if(!$date)
            <a href="{{url('dashboard')}}/?region=true" target="_blank">
            @else 
            <a href="{{url('dashboard')}}/?region=true&start={{$start}}&end={{$end}}" target="_blank">
            @endif
             <div class="col-md-2 col-sm-6 col-xs-12">
               <div class="info-box">
                 <span class="info-box-icon bg-yellow"><i class="fa fa-usd"></i></span>

                 <div class="info-box-content">
                   <span class="info-box-text">WIP<br><br></span>
                   <span class="info-box-number">{{$totalinvoiced->WIP ? $totalinvoiced->WIP:"0.00"}}</span>
                 </div>
                 <!-- /.info-box-content -->
               </div>
               <!-- /.info-box -->
             </div>
            </a>
            @if(!$date)
            <a href="{{url('dashboard')}}/?region=true" target="_blank">
            @else 
            <a href="{{url('dashboard')}}/?region=true&start={{$start}}&end={{$end}}" target="_blank">
            @endif
             <div class="col-md-2 col-sm-6 col-xs-12">
               <div class="info-box">
                 <span class="info-box-icon bg-yellow"><i class="fa fa-usd"></i></span>

                 <div class="info-box-content">
                   <span class="info-box-text">Not Yet Start<br><br></span>
                   <span class="info-box-number">{{$totalinvoiced->Not_Yet_Start ? $totalinvoiced->Not_Yet_Start:"0.00"}}</span>
                 </div>
                 <!-- /.info-box-content -->
               </div>
               <!-- /.info-box -->
             </div>
            </a>
            @if(!$date)
            <a href="{{url('dashboard')}}/?region=true" target="_blank">
            @else 
            <a href="{{url('dashboard')}}/?region=true&start={{$start}}&end={{$end}}" target="_blank">
            @endif
             <div class="col-md-2 col-sm-6 col-xs-12">
               <div class="info-box">
                 <span class="info-box-icon bg-yellow"><i class="fa fa-usd"></i></span>

                 <div class="info-box-content">
                   <span class="info-box-text">KIV<br><br></span>
                   <span class="info-box-number">{{$totalinvoiced->KIV ? $totalinvoiced->KIV:"0.00"}}</span>
                 </div>
                 <!-- /.info-box-content -->
               </div>
               <!-- /.info-box -->
             </div>
            </a>
            @if(!$date)
            <a href="{{url('dashboard')}}/?region=true" target="_blank">
            @else 
            <a href="{{url('dashboard')}}/?region=true&start={{$start}}&end={{$end}}" target="_blank">
            @endif
             <div class="col-md-2 col-sm-6 col-xs-12">
               <div class="info-box">
                 <span class="info-box-icon bg-red"><i class="fa fa-usd"></i></span>

                 <div class="info-box-content">
                   <span class="info-box-text">Cancelled<br><br></span>
                   <span class="info-box-number">{{$totalinvoiced->Cancelled ? $totalinvoiced->Cancelled:"0.00"}}</span>
                 </div>
                 <!-- /.info-box-content -->
               </div>
               <!-- /.info-box -->
             </div>
            </a>
           </div>

    </div>

    <div class="row">

      <div class="col-md-12">

        <section class="content-header">
          <h4>
            Project
          </h4>
        </section>
        @if(!$date)
        <a href="{{url('dashboard')}}/?region=true" target="_blank">
        @else 
        <a href="{{url('dashboard')}}/?region=true&start={{$start}}&end={{$end}}" target="_blank">
        @endif
        <div class="col-md-2 col-sm-6 col-xs-12">
          <div class="info-box">
            <span class="info-box-icon bg-aqua"><i class="fa fa-usd"></i></span>

            <div class="info-box-content">
              <span class="info-box-text">Total Sites</span>
              <span class="info-box-number">{{$totalsites->Total}}</span>
            </div>
            <!-- /.info-box-content -->
          </div>
          <!-- /.info-box -->
        </div>
      </a>
      @if(!$date)
      <a href="{{url('dashboard')}}/?region=true" target="_blank">
      @else 
      <a href="{{url('dashboard')}}/?region=true&start={{$start}}&end={{$end}}" target="_blank">
      @endif
        <div class="col-md-2 col-sm-6 col-xs-12">
          <div class="info-box">
            <span class="info-box-icon bg-green"><i class="fa fa-usd"></i></span>

            <div class="info-box-content">
              <span class="info-box-text">Completed</span>
              <span class="info-box-number">{{$totalsites->Completed? $totalsites->Completed:"0"}}</span>
            </div>
            <!-- /.info-box-content -->
          </div>
          <!-- /.info-box -->
        </div>
      </a>

      @if(!$date)
      <a href="{{url('dashboard')}}/?region=true" target="_blank">
      @else 
      <a href="{{url('dashboard')}}/?region=true&start={{$start}}&end={{$end}}" target="_blank">
      @endif
        <div class="col-md-2 col-sm-6 col-xs-12">
          <div class="info-box">
            <span class="info-box-icon bg-yellow"><i class="fa fa-usd"></i></span>

            <div class="info-box-content">
              <span class="info-box-text">WIP</span>
              <span class="info-box-number">{{$totalsites->WIP?$totalsites->WIP:"0"}}</span>
            </div>
            <!-- /.info-box-content -->
          </div>
          <!-- /.info-box -->
        </div>
      </a>
      @if(!$date)
      <a href="{{url('dashboard')}}/?region=true" target="_blank">
      @else 
      <a href="{{url('dashboard')}}/?region=true&start={{$start}}&end={{$end}}" target="_blank">
      @endif
        <div class="col-md-2 col-sm-6 col-xs-12">
          <div class="info-box">
            <span class="info-box-icon bg-yellow"><i class="fa fa-usd"></i></span>

            <div class="info-box-content">
              <span class="info-box-text">Not Yet Start</span>
              <span class="info-box-number">{{$totalsites->Not_Yet_Start?$totalsites->Not_Yet_Start:"0"}}</span>
            </div>
            <!-- /.info-box-content -->
          </div>
          <!-- /.info-box -->
        </div>
      </a>
      @if(!$date)
      <a href="{{url('dashboard')}}/?region=true" target="_blank">
      @else 
      <a href="{{url('dashboard')}}/?region=true&start={{$start}}&end={{$end}}" target="_blank">
      @endif
        <div class="col-md-2 col-sm-6 col-xs-12">
          <div class="info-box">
            <span class="info-box-icon bg-yellow"><i class="fa fa-usd"></i></span>

            <div class="info-box-content">
              <span class="info-box-text">KIV</span>
              <span class="info-box-number">{{$totalsites->KIV?$totalsites->KIV:"0"}}</span>
            </div>
            <!-- /.info-box-content -->
          </div>
          <!-- /.info-box -->
        </div>
      </a>
      @if(!$date)
      <a href="{{url('dashboard')}}/?region=true" target="_blank">
      @else 
      <a href="{{url('dashboard')}}/?region=true&start={{$start}}&end={{$end}}" target="_blank">
      @endif
        <div class="col-md-2 col-sm-6 col-xs-12">
          <div class="info-box">
            <span class="info-box-icon bg-red"><i class="fa fa-usd"></i></span>

            <div class="info-box-content">
              <span class="info-box-text">Cancelled</span>
              <span class="info-box-number">{{$totalsites->Cancelled?$totalsites->Cancelled:"0"}}</span>
            </div>
            <!-- /.info-box-content -->
          </div>
          <!-- /.info-box -->
        </div>
      </a>
      </div>

    </div>

    <div class="row">

      <div class="col-md-12">

        <section class="content-header">
          <h4>
            Gross Profit and Loss
          </h4>
        </section>
        @if(!$date)
        <a href="{{url('dashboard')}}/?region=true" target="_blank">
        @else 
        <a href="{{url('dashboard')}}/?region=true&start={{$start}}&end={{$end}}" target="_blank">
        @endif
        <div class="col-md-2 col-sm-6 col-xs-12">
          <div class="info-box">
            <span class="info-box-icon bg-yellow"><i class="fa fa-usd"></i></span>

            <div class="info-box-content">
              <span class="info-box-text">Total <br>Cost</span>
              <span class="info-box-number">{{number_format($totalcost->Total_Cost,2)}}</span>
            </div>
            <!-- /.info-box-content -->
          </div>
          <!-- /.info-box -->
        </div>
      </a>

      @if(!$date)
      <a href="{{url('dashboard')}}/?region=true" target="_blank">
      @else 
      <a href="{{url('dashboard')}}/?region=true&start={{$start}}&end={{$end}}" target="_blank">
      @endif
        <div class="col-md-2 col-sm-6 col-xs-12">
          <div class="info-box">
            <span class="info-box-icon bg-yellow"><i class="fa fa-usd"></i></span>

            <div class="info-box-content">
              <span class="info-box-text">Total <br>Manday</span>
              <span class="info-box-number">{{number_format($totalmanday[0]->Total,2)}}</span>
            </div>
            <!-- /.info-box-content -->
          </div>
          <!-- /.info-box -->
        </div>
      </a>
      @if(!$date)
      <a href="{{url('dashboard')}}/?region=true" target="_blank">
      @else 
      <a href="{{url('dashboard')}}/?region=true&start={{$start}}&end={{$end}}" target="_blank">
      @endif
        <div class="col-md-2 col-sm-6 col-xs-12">
          <div class="info-box">
            <span class="info-box-icon bg-yellow"><i class="fa fa-usd"></i></span>

            <div class="info-box-content">
              <span class="info-box-text">Total <br>Incentive</span>
              <span class="info-box-number">{{number_format($totalincentive[0]->Total,2)}}</span>
            </div>
            <!-- /.info-box-content -->
          </div>
          <!-- /.info-box -->
        </div>
      </a>
      @if(!$date)
      <a href="{{url('dashboard')}}/?region=true" target="_blank">
      @else 
      <a href="{{url('dashboard')}}/?region=true&start={{$start}}&end={{$end}}" target="_blank">
      @endif
        <div class="col-md-2 col-sm-6 col-xs-12">
          <div class="info-box">
            <span class="info-box-icon bg-yellow"><i class="fa fa-usd"></i></span>

            <div class="info-box-content">
              <span class="info-box-text">Total <br>eWallet</span>
              <span class="info-box-number">{{number_format($totalewallet[0]->Total,2)}}</span>
            </div>
            <!-- /.info-box-content -->
          </div>
          <!-- /.info-box -->
        </div>
      </a>
        <?php $profit=$totalinvoiced->Total_Invoiced_Amount-$totalcost->Total_Cost-$totalmanday[0]->Total-$totalincentive[0]->Total-$totalewallet[0]->Total; ?>

        @if(!$date)
        <a href="{{url('dashboard')}}/?region=true" target="_blank">
        @else 
        <a href="{{url('dashboard')}}/?region=true&start={{$start}}&end={{$end}}" target="_blank">
        @endif
        <div class="col-md-2 col-sm-6 col-xs-12">
          <div class="info-box">
            <span class="info-box-icon bg-green"><i class="fa fa-usd"></i></span>

            <div class="info-box-content">
              <span class="info-box-text">Total <br>Gross Profit</span>
              <span class="info-box-number">{{number_format($profit,2)}}</span>
            </div>
            <!-- /.info-box-content -->
          </div>
          <!-- /.info-box -->
        </div>
      </a>
        <?php 
        $totalinvoiced->Total_Invoiced_Amount;
        $totalinvoiced->Total_Invoiced_Amount == 0?  $margin=0:$margin=$profit/$totalinvoiced->Total_Invoiced_Amount*100;
        
        ?>
        @if(!$date)
        <a href="{{url('dashboard')}}/?region=true" target="_blank">
        @else 
        <a href="{{url('dashboard')}}/?region=true&start={{$start}}&end={{$end}}" target="_blank">
        @endif
        <div class="col-md-2 col-sm-6 col-xs-12">
          <div class="info-box">
            <span class="info-box-icon bg-green"><i class="fa fa-usd"></i></span>

            <div class="info-box-content">
              <span class="info-box-text">Margin <br>Percentage</span>
              <span class="info-box-number">{{number_format($margin,2)}}%</span>
            </div>
            <!-- /.info-box-content -->
          </div>
          <!-- /.info-box -->
        </div>
      </a>
        <div class="col-md-2 col-sm-6 col-xs-12">
            @if($date)
            <a target="_blank" href="{{url('tracker/piechart')}}start={{$start}}&end={{$end}}"   style="width:unset;" class="btn btn-sm btn-primary">Chart</a>
            @else 
            <a target="_blank" href="{{url('tracker/piechart')}}"   style="width:unset;" class="btn btn-sm btn-primary">Chart</a>
            @endif
            @if($date)
            <a target="_blank" style="width:unset;color:aqua;" class="btn btn-sm btn-danger"  href="{{url('tracker/piechart')}}?start={{$start}}&end={{$end}}&group=region">Region</a>
            @else
            <a target="_blank" style="width:unset;color:aqua;" class="btn btn-sm btn-danger"  href="{{url('tracker/piechart')}}group=region">Region</a>
            @endif
        </div>

      </div>

</div>

    </div>

  </div>

    </section>

  </div>

</div>
<footer class="main-footer">
  <div class="pull-right hidden-xs">
    <b>Version</b> 1.0.0
  </div>
  <strong>Copyright &copy; 2014-2016 <a href="http://www.softoya.com">TrackerOnTheGo</a>.</strong> All rights
  reserved.
</footer>

<script>

  $(function () {

    //Initialize Select2 Elements
    $(".select2").select2();

    $('#range').daterangepicker({locale: {
        format: 'DD-MMM-YYYY'
      },
      startDate: '{{date("d-M-Y",strtotime($start))}}',
      endDate: '{{date("d-M-Y",strtotime($end))}}'});

  });
  

function refresh()
{
  var d=$('#range').val();
  var arr = d.split(" - ");
  var check=$("#dateRange").is(':checked');
  if(check)
    arr="&start="+arr[0]+"&end="+arr[1];
  else arr="";

  window.location.href ="{{ url("/dashboard") }}"+"/"+"?check="+check+arr;
}

</script>

@endsection
