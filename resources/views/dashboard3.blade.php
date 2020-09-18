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

      <h3>
        Commercial Dashboard
      </h3>
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
        <div class="box box-primary">
          <div class="box-body">
             
              
                <div class="row">

                  <div class="col-md-12">

                <a href="{{url('handsontable')}}/?{{$date ? "start=".$start."&end=".$end:''}}&dashboard=3" target="_blank">
                  <div class="col-md-2 col-sm-6 col-xs-12">
                    <div class="info-box">
                      <span class="info-box-icon bg-green"><i class="fa fa-usd"></i></span>

                      <div class="info-box-content">
                        <span class="info-box-text">TOTAL<br>SITES (QTY)</span>
                        <span class="info-box-number">{{$totalsites[0]->Total}}</span>
                      </div>
                      <!-- /.info-box-content -->
                    </div>
                    <!-- /.info-box -->
                  </div>
                </a>
                <a href="{{url('handsontable')}}/{{? $template->reject(function($item){return mb_strpos($item->Tracker_Name,'QS') === false;})->first()->Id:0}}?{{$date ? "start=".$start."&end=".$end:''}}&dashboard=3" target="_blank">
                  <div class="col-md-2 col-sm-6 col-xs-12">
                    <div class="info-box">
                      <span class="info-box-icon bg-green"><i class="fa fa-usd"></i></span>

                      <div class="info-box-content">
                        <span class="info-box-text">TOTAL<br>SITES (AMOUNT)</span>
                        <span class="info-box-number">{{number_format($totalsites[0]->boq,2)}}</span>
                      </div>
                      <!-- /.info-box-content -->
                    </div>
                    <!-- /.info-box -->
                  </div>
                </a>

                <a href="{{url('handsontable')}}/?{{$date ? "start=".$start."&end=".$end:''}}&dashboard=3" target="_blank">
                  <div class="col-md-2 col-sm-6 col-xs-12">
                    <div class="info-box">
                      <span class="info-box-icon bg-green"><i class="fa fa-usd"></i></span>

                      <div class="info-box-content">
                        <span class="info-box-text">TOTAL<br>INVOICE</span>
                        <span class="info-box-number">{{number_format($totalsites[0]->invoice,2)}}</span>
                      </div>
                      <!-- /.info-box-content -->
                    </div>
                    <!-- /.info-box -->
                  </div>
                </a>

                <a href="{{url('handsontable')}}/?{{$date ? "start=".$start."&end=".$end:''}}&dashboard=3" target="_blank">
                  <div class="col-md-2 col-sm-6 col-xs-12">
                    <div class="info-box">
                      <span class="info-box-icon bg-red"><i class="fa fa-usd"></i></span>

                      <div class="info-box-content">
                        <span class="info-box-text">TOTAL<br>SITE BALANCE<br>TO INVOICE</span>
                        <span class="info-box-number">{{number_format($totalsites[0]->boq - $totalsites[0]->invoice,2)}}</span>
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
                      PROJECTED SALES
                    </h4>
                  
                  </section>

              <a href="{{url('handsontable')}}/?{{$date ? "start=".$start."&end=".$end:''}}&dashboard=3" target="_blank">
                <div class="col-md-2 col-sm-6 col-xs-12">
                  <div class="info-box">
                    <span class="info-box-icon bg-green"><i class="fa fa-usd"></i></span>

                    <div class="info-box-content">
                      <span class="info-box-text">TOTAL<br>BOQ SUBMISSION</span>
                      <span class="info-box-number">({{$projected[0]->boq}}) {{number_format($projected[0]->total_boq,2)}}</span>
                    </div>
                    <!-- /.info-box-content -->
                  </div>
                  <!-- /.info-box -->
                </div>
              </a>

              <a href="{{url('handsontable')}}/?{{$date ? "start=".$start."&end=".$end:''}}&dashboard=3" target="_blank">
                <div class="col-md-2 col-sm-6 col-xs-12">
                  <div class="info-box">
                    <span class="info-box-icon bg-green"><i class="fa fa-usd"></i></span>

                    <div class="info-box-content">
                      <span class="info-box-text">TOTAL<br>BOQ APPROVED</span>
                      <span class="info-box-number">({{$projected[0]->approved}}) {{number_format($projected[0]->total_approved,2)}}</span>
                    </div>
                    <!-- /.info-box-content -->
                  </div>
                  <!-- /.info-box -->
                </div>
              </a>

              <a href="{{url('handsontable')}}" target="_blank">
                <div class="col-md-2 col-sm-6 col-xs-12">
                  <div class="info-box">
                    <span class="info-box-icon bg-green"><i class="fa fa-usd"></i></span>

                    <div class="info-box-content">
                      <span class="info-box-text">TOTAL<br>PO RECEIVED</span>
                      <span class="info-box-number">({{$projected[0]->po}}) {{number_format($projected[0]->total_po,2)}}</span>
                    </div>
                    <!-- /.info-box-content -->
                  </div>
                  <!-- /.info-box -->
                </div>
              </a>

              <a href="{{url('handsontable')}}" target="_blank">
                <div class="col-md-2 col-sm-6 col-xs-12">
                  <div class="info-box">
                    <span class="info-box-icon bg-red"><i class="fa fa-usd"></i></span>

                    <div class="info-box-content">
                      <span class="info-box-text">TOTAL<br>BOQ APPROVED<br>PENDING PO</span>
                      <span class="info-box-number">{{number_format($projected[0]->total_approved - $projected[0]->total_po,2)}}</span>
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

                {{-- <h6>
                  (RENEWAL PERMIT TRACKER)
                </h6> --}}
              </section>

          <a href="{{url('handsontable')}}?{{$date ? "start=".$start."&end=".$end:''}}&dashboard=3" target="_blank">
            <div class="col-md-2 col-sm-6 col-xs-12">
              <div class="info-box">
                <span class="info-box-icon bg-green"><i class="fa fa-usd"></i></span>

                <div class="info-box-content">
                  <span class="info-box-text">TOTAL<br>BOQ SUBMISSION</span>
                  <span class="info-box-number">({{$renewal[0]->boq}}) {{number_format($renewal[0]->total_boq,2)}}</span>
                </div>
                <!-- /.info-box-content -->
              </div>
              <!-- /.info-box -->
            </div>
          </a>

          <a href="{{url('handsontable')}}/?{{$date ? "start=".$start."&end=".$end:''}}&dashboard=3" target="_blank">
            <div class="col-md-2 col-sm-6 col-xs-12">
              <div class="info-box">
                <span class="info-box-icon bg-green"><i class="fa fa-usd"></i></span>

                <div class="info-box-content">
                  <span class="info-box-text">TOTAL<br>BOQ APPROVED</span>
                  <span class="info-box-number">({{$renewal[0]->approved}}) {{number_format($renewal[0]->total_approved,2)}}</span>
                </div>
                <!-- /.info-box-content -->
              </div>
              <!-- /.info-box -->
            </div>
          </a>

          <a href="{{url('handsontable')}}/?{{$date ? "start=".$start."&end=".$end:''}}&dashboard=3" target="_blank">
            <div class="col-md-2 col-sm-6 col-xs-12">
              <div class="info-box">
                <span class="info-box-icon bg-green"><i class="fa fa-usd"></i></span>

                <div class="info-box-content">
                  <span class="info-box-text">TOTAL<br>PO RECEIVED</span>
                  <span class="info-box-number">({{$renewal[0]->po}}) {{number_format($renewal[0]->total_po,2)}}</span>
                </div>
                <!-- /.info-box-content -->
              </div>
              <!-- /.info-box -->
            </div>
          </a>

          <a href="{{url('handsontable')}}/?{{$date ? "start=".$start."&end=".$end:''}}&dashboard=3" target="_blank">
            <div class="col-md-2 col-sm-6 col-xs-12">
              <div class="info-box">
                <span class="info-box-icon bg-red"><i class="fa fa-usd"></i></span>

                <div class="info-box-content">
                  <span class="info-box-text">TOTAL<br>BOQ APPROVED<br>PENDING PO</span>
                  <span class="info-box-number">{{number_format($renewal[0]->total_approved - $renewal[0]->total_po,2)}}</span>
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

            {{-- <h6>
              (LEGALIZATION TRACKER)
            </h6> --}}
          </section>

      <a href="{{url('handsontable')}}/?{{$date ? "start=".$start."&end=".$end:''}}&dashboard=3" target="_blank">
        <div class="col-md-2 col-sm-6 col-xs-12">
          <div class="info-box">
            <span class="info-box-icon bg-green"><i class="fa fa-usd"></i></span>

            <div class="info-box-content">
              <span class="info-box-text">TOTAL<br>BOQ SUBMISSION</span>
              <span class="info-box-number">({{$legalization[0]->boq}}) {{number_format($legalization[0]->total_boq,2)}}</span>
            </div>
            <!-- /.info-box-content -->
          </div>
          <!-- /.info-box -->
        </div>
      </a>

      <a href="{{url('handsontable')}}/?{{$date ? "start=".$start."&end=".$end:''}}&dashboard=3" target="_blank">
        <div class="col-md-2 col-sm-6 col-xs-12">
          <div class="info-box">
            <span class="info-box-icon bg-green"><i class="fa fa-usd"></i></span>

            <div class="info-box-content">
              <span class="info-box-text">TOTAL<br>BOQ APPROVED</span>
              <span class="info-box-number">({{$legalization[0]->approved}}) {{number_format($legalization[0]->total_approved,2)}}</span>
            </div>
            <!-- /.info-box-content -->
          </div>
          <!-- /.info-box -->
        </div>
      </a>

      <a href="{{url('handsontable')}}/?{{$date ? "start=".$start."&end=".$end:''}}&dashboard=3" target="_blank">
        <div class="col-md-2 col-sm-6 col-xs-12">
          <div class="info-box">
            <span class="info-box-icon bg-green"><i class="fa fa-usd"></i></span>

            <div class="info-box-content">
              <span class="info-box-text">TOTAL<br>PO RECEIVED</span>
              <span class="info-box-number">({{$legalization[0]->po}}) {{number_format($legalization[0]->total_po,2)}}</span>
            </div>
            <!-- /.info-box-content -->
          </div>
          <!-- /.info-box -->
        </div>
      </a>

      <a href="{{url('handsontable')}}/?{{$date ? "start=".$start."&end=".$end:''}}&dashboard=3" target="_blank">
        <div class="col-md-2 col-sm-6 col-xs-12">
          <div class="info-box">
            <span class="info-box-icon bg-red"><i class="fa fa-usd"></i></span>

            <div class="info-box-content">
              <span class="info-box-text">TOTAL<br>BOQ APPROVED<br>PENDING PO</span>
              <span class="info-box-number">{{number_format($legalization[0]->total_approved - $legalization[0]->total_po,2)}}</span>
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
                PROJECT
              </h4>

              <h6>
                (UM/DG/SBC TRACKER)
              </h6>
            </section>

        <a href="{{url('handsontable')}}/{{$template->reject(function($item){return mb_strpos($item->Tracker_Name,'ROLLOUT') === false;})->first()->Id}}?{{$date ? "start=".$start."&end=".$end:''}}&dashboard=3&status=Pending NTP" target="_blank">
          <div class="col-md-2 col-sm-6 col-xs-12">
            <div class="info-box">
              <span class="info-box-icon bg-green"><i class="fa fa-usd"></i></span>

              <div class="info-box-content">
                <span class="info-box-text">TOTAL<br>PENDING NTP</span>
                <span class="info-box-number">({{$project[0]->pending_ntp}}) {{number_format($project[0]->ntp_boq,2)}}</span>
              </div>
              <!-- /.info-box-content -->
            </div>
            <!-- /.info-box -->
          </div>
        </a>

        <a  href="{{url('handsontable')}}/{{$template->reject(function($item){return mb_strpos($item->Tracker_Name,'ROLLOUT') === false;})->first()->Id}}?{{$date ? "start=".$start."&end=".$end:''}}&dashboard=3&status=Not Yet Start" target="_blank">
          <div class="col-md-2 col-sm-6 col-xs-12">
            <div class="info-box">
              <span class="info-box-icon bg-green"><i class="fa fa-usd"></i></span>

              <div class="info-box-content">
                <span class="info-box-text">TOTAL<br>NOT YET START</span>
                <span class="info-box-number">({{$project[0]->not_yet}}) {{number_format($project[0]->not_boq,2)}}</span>
              </div>
              <!-- /.info-box-content -->
            </div>
            <!-- /.info-box -->
          </div>
        </a>

        <a href="{{url('handsontable')}}/{{$template->reject(function($item){return mb_strpos($item->Tracker_Name,'ROLLOUT') === false;})->first()->Id}}?{{$date ? "start=".$start."&end=".$end:''}}&dashboard=3&status=WIP" target="_blank">
          <div class="col-md-2 col-sm-6 col-xs-12">
            <div class="info-box">
              <span class="info-box-icon bg-green"><i class="fa fa-usd"></i></span>

              <div class="info-box-content">
                <span class="info-box-text">TOTAL<br>WIP</span>
                <span class="info-box-number">({{$project[0]->wip}}) {{number_format($project[0]->boq_wip,2)}}</span>
              </div>
              <!-- /.info-box-content -->
            </div>
            <!-- /.info-box -->
          </div>
        </a>

        <a href="{{url('handsontable')}}/{{$template->reject(function($item){return mb_strpos($item->Tracker_Name,'ROLLOUT') === false;})->first()->Id}}?{{$date ? "start=".$start."&end=".$end:''}}&dashboard=3&status=Completed Pending ATP" target="_blank">
          <div class="col-md-2 col-sm-6 col-xs-12">
            <div class="info-box">
              <span class="info-box-icon bg-red"><i class="fa fa-usd"></i></span>

              <div class="info-box-content">
                <span class="info-box-text">TOTAL<br>COMPLETED<br>PENDING ATP</span>
                <span class="info-box-number">({{$project[0]->completed_pending_atp}}) {{number_format($project[0]->boq_atp,2)}}</span>
              </div>
              <!-- /.info-box-content -->
            </div>
            <!-- /.info-box -->
          </div>
        </a>

        <a href="{{url('handsontable')}}/{{$template->reject(function($item){return mb_strpos($item->Tracker_Name,'ROLLOUT') === false;})->first()->Id}}?{{$date ? "start=".$start."&end=".$end:''}}&dashboard=3&status=PLC" target="_blank">
          <div class="col-md-2 col-sm-6 col-xs-12">
            <div class="info-box">
              <span class="info-box-icon bg-red"><i class="fa fa-usd"></i></span>

              <div class="info-box-content">
                <span class="info-box-text">TOTAL<br>PLC</span>
                <span class="info-box-number">({{$project[0]->pending_plc}}) {{number_format($project[0]->boq_plc,2)}}</span>
              </div>
              <!-- /.info-box-content -->
            </div>
            <!-- /.info-box -->
          </div>
        </a>

        <a href="{{url('handsontable')}}/{{$template->reject(function($item){return mb_strpos($item->Tracker_Name,'ROLLOUT') === false;})->first()->Id}}?{{$date ? "start=".$start."&end=".$end:''}}&dashboard=3&status=Site done" target="_blank">
          <div class="col-md-2 col-sm-6 col-xs-12">
            <div class="info-box">
              <span class="info-box-icon bg-red"><i class="fa fa-usd"></i></span>

              <div class="info-box-content">
                <span class="info-box-text">TOTAL<br>SITE DONE</span>
                <span class="info-box-number">({{$project[0]->site_done}}) {{number_format($project[0]->boq_site,2)}}</span>
              </div>
              <!-- /.info-box-content -->
            </div>
            <!-- /.info-box -->
          </div>
        </a>

        <a href="{{url('handsontable')}}/{{$template->reject(function($item){return mb_strpos($item->Tracker_Name,'ROLLOUT') === false;})->first()->Id}}?{{$date ? "start=".$start."&end=".$end:''}}&dashboard=3&status=KIV" target="_blank">
          <div class="col-md-2 col-sm-6 col-xs-12">
            <div class="info-box">
              <span class="info-box-icon bg-red"><i class="fa fa-usd"></i></span>

              <div class="info-box-content">
                <span class="info-box-text">TOTAL<br>KIV</span>
                <span class="info-box-number">({{$project[0]->kiv}}) {{number_format($project[0]->boq_kiv,2)}}</span>
              </div>
              <!-- /.info-box-content -->
            </div>
            <!-- /.info-box -->
          </div>
        </a>

        <a href="{{url('handsontable')}}/{{$template->reject(function($item){return mb_strpos($item->Tracker_Name,'ROLLOUT') === false;})->first()->Id}}?{{$date ? "start=".$start."&end=".$end:''}}&dashboard=3&status=Cancelled/DROP" target="_blank">
          <div class="col-md-2 col-sm-6 col-xs-12">
            <div class="info-box">
              <span class="info-box-icon bg-red"><i class="fa fa-usd"></i></span>

              <div class="info-box-content">
                <span class="info-box-text">TOTAL<br>CANCELLED/DROP</span>
                <span class="info-box-number">({{$project[0]->cancelled}}) {{number_format($project[0]->boq_cancelled,2)}}</span>
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
                MILESTONE
              </h4>

              {{-- <h6>
                (UM/DG/SBC/RENEWAL/LEGALIZATION TRACKER)
              </h6> --}}
            </section>

        <a href="{{url('handsontable')}}/{{ ? $template->reject(function($item){return mb_strpos($item->Tracker_Name,'OSU') === false;})->first()->Id:0}}?{{$date ? "start=".$start."&end=".$end:''}}&dashboard=3&milestone=PCC" target="_blank">
          <div class="col-md-2 col-sm-6 col-xs-12">
            <div class="info-box">
              <span class="info-box-icon bg-green"><i class="fa fa-usd"></i></span>

              <div class="info-box-content">
                <span class="info-box-text">TOTAL<br>PCC</span>
                <span class="info-box-number">({{$milestone[0]->total_pcc}}) {{number_format($milestone[0]->pcc,2)}}</span>
              </div>
              <!-- /.info-box-content -->
            </div>
            <!-- /.info-box -->
          </div>
        </a>

        <a href="{{url('handsontable')}}/{{ ? $template->reject(function($item){return mb_strpos($item->Tracker_Name,'OSU') === false;})->first()->Id:0}}?{{$date ? "start=".$start."&end=".$end:''}}&dashboard=3&milestone=NIC" target="_blank">
          <div class="col-md-2 col-sm-6 col-xs-12">
            <div class="info-box">
              <span class="info-box-icon bg-green"><i class="fa fa-usd"></i></span>

              <div class="info-box-content">
                <span class="info-box-text">TOTAL<br>NIC</span>
                <span class="info-box-number">({{$milestone[0]->total_nic}}) {{number_format($milestone[0]->nic,2)}}</span>
              </div>
              <!-- /.info-box-content -->
            </div>
            <!-- /.info-box -->
          </div>
        </a>

        <a href="{{url('handsontable')}}/{{? $template->reject(function($item){return mb_strpos($item->Tracker_Name,'OSU') === false;})->first()->Id:0}}?{{$date ? "start=".$start."&end=".$end:''}}&dashboard=3&milestone=PM" target="_blank">
          <div class="col-md-2 col-sm-6 col-xs-12">
            <div class="info-box">
              <span class="info-box-icon bg-green"><i class="fa fa-usd"></i></span>

              <div class="info-box-content">
                <span class="info-box-text">TOTAL<br>PM</span>
                <span class="info-box-number">({{$milestone[0]->total_pm}}) {{number_format($milestone[0]->pm,2)}}</span>
              </div>
              <!-- /.info-box-content -->
            </div>
            <!-- /.info-box -->
          </div>
        </a>

        <a href="{{url('handsontable')}}/{{ ?$template->reject(function($item){return mb_strpos($item->Tracker_Name,'OSU') === false;})->first()->Id:0}}?{{$date ? "start=".$start."&end=".$end:''}}&dashboard=3&milestone=QS" target="_blank">
          <div class="col-md-2 col-sm-6 col-xs-12">
            <div class="info-box">
              <span class="info-box-icon bg-red"><i class="fa fa-usd"></i></span>

              <div class="info-box-content">
                <span class="info-box-text">TOTAL<br>QS</span>
                <span class="info-box-number">({{$milestone[0]->total_qs}}) {{number_format($milestone[0]->qs,2)}}</span>
              </div>
              <!-- /.info-box-content -->
            </div>
            <!-- /.info-box -->
          </div>
        </a>

        <a href="{{url('handsontable')}}/{{ ?$template->reject(function($item){return mb_strpos($item->Tracker_Name,'OSU') === false;})->first()->Id:0}}?{{$date ? "start=".$start."&end=".$end:''}}&dashboard=3&milestone=TSS" target="_blank">
          <div class="col-md-2 col-sm-6 col-xs-12">
            <div class="info-box">
              <span class="info-box-icon bg-red"><i class="fa fa-usd"></i></span>

              <div class="info-box-content">
                <span class="info-box-text">TOTAL<br>TSS</span>
                <span class="info-box-number">({{$milestone[0]->total_tss}}) {{number_format($milestone[0]->tss,2)}}</span>
              </div>
              <!-- /.info-box-content -->
            </div>
            <!-- /.info-box -->
          </div>
        </a>

        <a href="{{url('handsontable')}}/{{ ? $template->reject(function($item){return mb_strpos($item->Tracker_Name,'OSU') === false;})->first()->Id:0}}?{{$date ? "start=".$start."&end=".$end:''}}&dashboard=3&milestone=ROLLOUT" target="_blank">
          <div class="col-md-2 col-sm-6 col-xs-12">
            <div class="info-box">
              <span class="info-box-icon bg-red"><i class="fa fa-usd"></i></span>

              <div class="info-box-content">
                <span class="info-box-text">TOTAL<br>ROLLOUT</span>
                <span class="info-box-number">({{$milestone[0]->total_rollout}}) {{number_format($milestone[0]->rollout,2)}}</span>
              </div>
              <!-- /.info-box-content -->
            </div>
            <!-- /.info-box -->
          </div>
        </a>

        <a ref="{{url('handsontable')}}/{{ ? $template->reject(function($item){return mb_strpos($item->Tracker_Name,'OSU') === false;})->first()->Id:0}}?{{$date ? "start=".$start."&end=".$end:''}}&dashboard=3&milestone=OSU" target="_blank">
          <div class="col-md-2 col-sm-6 col-xs-12">
            <div class="info-box">
              <span class="info-box-icon bg-red"><i class="fa fa-usd"></i></span>

              <div class="info-box-content">
                <span class="info-box-text">TOTAL<br>OSU</span>
                <span class="info-box-number">({{$milestone[0]->total_osu}}) {{number_format($milestone[0]->osu,2)}}</span>
              </div>
              <!-- /.info-box-content -->
            </div>
            <!-- /.info-box -->
          </div>
        </a>

        <a ref="{{url('handsontable')}}/{{  ?$template->reject(function($item){return mb_strpos($item->Tracker_Name,'OSU') === false;})->first()->Id:0}}?{{$date ? "start=".$start."&end=".$end:''}}&dashboard=3&milestone=CLOSED" target="_blank">
          <div class="col-md-2 col-sm-6 col-xs-12">
            <div class="info-box">
              <span class="info-box-icon bg-red"><i class="fa fa-usd"></i></span>

              <div class="info-box-content">
                <span class="info-box-text">TOTAL<br>CLOSED</span>
                <span class="info-box-number">({{$milestone[0]->total_closed}}) {{number_format($milestone[0]->closed,2)}}</span>
              </div>
              <!-- /.info-box-content -->
            </div>
            <!-- /.info-box -->
          </div>
        </a>

        <a ref="{{url('handsontable')}}/{{ ?$template->reject(function($item){return mb_strpos($item->Tracker_Name,'OSU') === false;})->first()->Id:0}}?{{$date ? "start=".$start."&end=".$end:''}}&dashboard=3&milestone=CANCELLED/DROP" target="_blank">
          <div class="col-md-2 col-sm-6 col-xs-12">
            <div class="info-box">
              <span class="info-box-icon bg-red"><i class="fa fa-usd"></i></span>

              <div class="info-box-content">
                <span class="info-box-text">TOTAL<br>CANCELLED/DROP</span>
                <span class="info-box-number">({{$milestone[0]->total_cancelled}}) {{number_format($milestone[0]->cancelled,2)}}</span>
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
                GROSS PROFIT & LOSS
              </h4>

              {{-- <h6>
                (UM/DG/SBC/RENEWAL/LEGALIZATION TRACKER)
              </h6> --}}
            </section>

        <a  href="{{url('handsontable')}}/?{{$date ? "start=".$start."&end=".$end:''}}&dashboard=3" target="_blank">
          <div class="col-md-2 col-sm-6 col-xs-12">
            <div class="info-box">
              <span class="info-box-icon bg-green"><i class="fa fa-usd"></i></span>

              <div class="info-box-content">
                <span class="info-box-text">TOTAL<br>INVOICE</span>
                <span class="info-box-number">({{$gross[0]->invoice_site}}) {{number_format($gross[0]->Total_Invoiced_Amount,2)}}</span>
              </div>
              <!-- /.info-box-content -->
            </div>
            <!-- /.info-box -->
          </div>
        </a>

        <a  href="{{url('handsontable')}}/?{{$date ? "start=".$start."&end=".$end:''}}&dashboard=3" target="_blank">
          <div class="col-md-2 col-sm-6 col-xs-12">
            <div class="info-box">
              <span class="info-box-icon bg-green"><i class="fa fa-usd"></i></span>

              <div class="info-box-content">
                <span class="info-box-text">TOTAL<br>ALL COSTING</span>
                <span class="info-box-number">({{$gross[0]->total_tracker}}) {{number_format($gross[0]->total_budget,2)}}</span>
              </div>
              <!-- /.info-box-content -->
            </div>
            <!-- /.info-box -->
          </div>
        </a>

        <a  href="{{url('handsontable')}}/?{{$date ? "start=".$start."&end=".$end:''}}&dashboard=3" target="_blank">
          <div class="col-md-2 col-sm-6 col-xs-12">
            <div class="info-box">
              <span class="info-box-icon bg-green"><i class="fa fa-usd"></i></span>

              <div class="info-box-content">
                <span class="info-box-text">TOTAL<br>MANDAY</span>
                <span class="info-box-number">({{$gross[0]->manday_site}}) {{number_format($gross[0]->total_manday,2)}}</span>
              </div>
              <!-- /.info-box-content -->
            </div>
            <!-- /.info-box -->
          </div>
        </a>

        <a  href="{{url('handsontable')}}/?{{$date ? "start=".$start."&end=".$end:''}}&dashboard=3" target="_blank">
          <div class="col-md-2 col-sm-6 col-xs-12">
            <div class="info-box">
              <span class="info-box-icon bg-red"><i class="fa fa-usd"></i></span>

              <div class="info-box-content">
                <span class="info-box-text">TOTAL<br>INCENTIVE</span>
                <span class="info-box-number">{{number_format($gross[0]->total_incentive,2)}}</span>
              </div>
              <!-- /.info-box-content -->
            </div>
            <!-- /.info-box -->
          </div>
        </a>

        <a  href="{{url('handsontable')}}/?{{$date ? "start=".$start."&end=".$end:''}}&dashboard=3" target="_blank">
          <div class="col-md-2 col-sm-6 col-xs-12">
            <div class="info-box">
              <span class="info-box-icon bg-red"><i class="fa fa-usd"></i></span>

              <div class="info-box-content">
                <span class="info-box-text">TOTAL<br>E-WALLET</span>
                <span class="info-box-number">({{$gross[0]->ewallet}}) {{number_format($gross[0]->total_ewallet,2)}}</span>
              </div>
              <!-- /.info-box-content -->
            </div>
            <!-- /.info-box -->
          </div>
        </a>

        <a  href="{{url('handsontable')}}/?{{$date ? "start=".$start."&end=".$end:''}}&dashboard=3" target="_blank">
          <div class="col-md-2 col-sm-6 col-xs-12">
            <div class="info-box">
              <span class="info-box-icon bg-red"><i class="fa fa-usd"></i></span>

              <div class="info-box-content">
                <span class="info-box-text">TOTAL<br>GROSS PROFIT</span>
                <span class="info-box-number">{{number_format($gross[0]->Total_Invoiced_Amount - $gross[0]->total_budget,2)}}</span>
              </div>
              <!-- /.info-box-content -->
            </div>
            <!-- /.info-box -->
          </div>
        </a>

        <a  href="{{url('handsontable')}}/?{{$date ? "start=".$start."&end=".$end:''}}&dashboard=3" target="_blank">
          <div class="col-md-2 col-sm-6 col-xs-12">
            <div class="info-box">
              <span class="info-box-icon bg-red"><i class="fa fa-usd"></i></span>

              <div class="info-box-content">
                <span class="info-box-text">MARGIN PERCENTAGE</span>
                <span class="info-box-number">{{ $gross[0]->Total_Invoiced_Amount != 0 ? round( ( ( $gross[0]->Total_Invoiced_Amount - $gross[0]->total_budget)/$gross[0]->Total_Invoiced_Amount ) * 100 ,2 ):0 }}%</span>
              </div>
              <!-- /.info-box-content -->
            </div>
            <!-- /.info-box -->
          </div>
        </a>

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
  window.location.href ="{{ url("/dashboard3") }}?check="+check+arr;
}

</script>

@endsection
