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
        Project Dashboard
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

          <div class="col-md-2">

            <div class="form-group">
              <label>Project : </label>
              <select class="form-control select2" id="Project" name="Project" style="width: 100%;">
                <option value=all>All</option>

                @foreach($projects as $p)

                @if(str_contains($p->Project_Name,"DIGI") || str_contains($p->Project_Name,"SBC") ||str_contains($p->Project_Name,"UM"))
                    <option value="{{$p->Id}}" <?php if($p->Id==$projectid) echo ' selected="selected" '; ?>>{{$p->Project_Name}}</option>
                @endif
                @endforeach

                </select>
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

                    <section class="content-header">
                      <h4>
                        Surveyor
                      </h4>

                      <h6>
                        (UM/DG/SBC --- SURVEYOR TRACKER)
                      </h6>

                    </section>

                <a href="#" target="_blank">
                  <div class="col-md-2 col-sm-6 col-xs-12">
                    <div class="info-box">
                      <span class="info-box-icon bg-green"><i class="fa fa-usd"></i></span>

                      <div class="info-box-content">
                        <span class="info-box-text">TOTAL <br>SITE</span>
                        <span class="info-box-number">{{$surveytotal[0]->Survey}}</span>
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

                <a href="#" target="_blank">
                  <div class="col-md-2 col-sm-6 col-xs-12">
                    <div class="info-box">
                      <span class="info-box-icon bg-aqua"><i class="fa fa-usd"></i></span>

                      <div class="info-box-content">
                        <span class="info-box-text">TOTAL <br>SITE SURVEY<br>(INTERNAL)</span>
                        <span class="info-box-number">{{$surveytotal[0]->Survey_Internal}}</span>
                      </div>
                      <!-- /.info-box-content -->
                    </div>
                    <!-- /.info-box -->
                  </div>
                </a>

                <a href="#" target="_blank">
                  <div class="col-md-2 col-sm-6 col-xs-12">
                    <div class="info-box">
                      <span class="info-box-icon bg-aqua"><i class="fa fa-usd"></i></span>

                      <div class="info-box-content">
                        <span class="info-box-text">TOTAL<br>DONE<br>(INTERNAL)</span>
                        <span class="info-box-number">{{$surveytotal[0]->Survey_Done}}</span>
                      </div>
                      <!-- /.info-box-content -->
                    </div>
                    <!-- /.info-box -->
                  </div>
                </a>

                <a href="#" target="_blank">
                  <div class="col-md-2 col-sm-6 col-xs-12">
                    <div class="info-box">
                      <span class="info-box-icon bg-red"><i class="fa fa-usd"></i></span>

                      <div class="info-box-content">
                        <span class="info-box-text">TOTAL<br>PENDING NTP<br>(INTERNAL)</span>
                        <span class="info-box-number">{{$surveytotal[0]->Survey_Pending_NTP}}</span>
                      </div>
                      <!-- /.info-box-content -->
                    </div>
                    <!-- /.info-box -->
                  </div>
                </a>

                <a href="#" target="_blank">
                  <div class="col-md-2 col-sm-6 col-xs-12">
                    <div class="info-box">
                      <span class="info-box-icon bg-red"><i class="fa fa-usd"></i></span>

                      <div class="info-box-content">
                        <span class="info-box-text">TOTAL<br>PENDING WP<br>(INTERNAL)</span>
                        <span class="info-box-number">{{$surveytotal[0]->Survey_Pending_WP}}</span>
                      </div>
                      <!-- /.info-box-content -->
                    </div>
                    <!-- /.info-box -->
                  </div>
                </a>

                <a href="#" target="_blank">
                  <div class="col-md-2 col-sm-6 col-xs-12">
                    <div class="info-box">
                      <span class="info-box-icon bg-red"><i class="fa fa-usd"></i></span>

                      <div class="info-box-content">
                        <span class="info-box-text">TOTAL<br>PENDING SURVEY<br>(INTERNAL)</span>
                        <span class="info-box-number">{{$surveytotal[0]->Survey_Pending_Survey}}</span>
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

                  <a href="#" target="_blank">
                    <div class="col-md-2 col-sm-6 col-xs-12">
                      <div class="info-box">
                        <span class="info-box-icon bg-yellow"><i class="fa fa-usd"></i></span>

                        <div class="info-box-content">
                          <span class="info-box-text">TOTAL <br>SITE SURVEY<br>(SUBCON)</span>
                          <span class="info-box-number">{{$surveytotal[0]->Survey_Subcon}}</span>
                        </div>
                        <!-- /.info-box-content -->
                      </div>
                      <!-- /.info-box -->
                    </div>
                  </a>

                  <a href="#" target="_blank">
                    <div class="col-md-2 col-sm-6 col-xs-12">
                      <div class="info-box">
                        <span class="info-box-icon bg-yellow"><i class="fa fa-usd"></i></span>

                        <div class="info-box-content">
                          <span class="info-box-text">TOTAL<br>DONE<br>(SUBCON)</span>
                          <span class="info-box-number">{{$surveytotal[0]->Survey_Subcon_Done}}</span>
                        </div>
                        <!-- /.info-box-content -->
                      </div>
                      <!-- /.info-box -->
                    </div>
                  </a>

                  <a href="#" target="_blank">
                    <div class="col-md-2 col-sm-6 col-xs-12">
                      <div class="info-box">
                        <span class="info-box-icon bg-red"><i class="fa fa-usd"></i></span>

                        <div class="info-box-content">
                          <span class="info-box-text">TOTAL<br>PENDING NTP<br>(SUBCON)</span>
                          <span class="info-box-number">{{$surveytotal[0]->Survey_Subcon_Pending_NTP}}</span>
                        </div>
                        <!-- /.info-box-content -->
                      </div>
                      <!-- /.info-box -->
                    </div>
                  </a>

                  <a href="#" target="_blank">
                    <div class="col-md-2 col-sm-6 col-xs-12">
                      <div class="info-box">
                        <span class="info-box-icon bg-red"><i class="fa fa-usd"></i></span>

                        <div class="info-box-content">
                          <span class="info-box-text">TOTAL<br>PENDING WP<br>(SUBCON)</span>
                          <span class="info-box-number">{{$surveytotal[0]->Survey_Subcon_Pending_WP}}</span>
                        </div>
                        <!-- /.info-box-content -->
                      </div>
                      <!-- /.info-box -->
                    </div>
                  </a>

                  <a href="#" target="_blank">
                    <div class="col-md-2 col-sm-6 col-xs-12">
                      <div class="info-box">
                        <span class="info-box-icon bg-red"><i class="fa fa-usd"></i></span>

                        <div class="info-box-content">
                          <span class="info-box-text">TOTAL<br>PENDING SURVEY<br>(SUBCON)</span>
                          <span class="info-box-number">{{$surveytotal[0]->Survey_Subcon_Pending_Survey}}</span>
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
                      DRAFTER (PO1)
                    </h4>

                    <h6>
                      (UM/DG/SBC --- SURVEYOR TRACKER)
                    </h6>
                  </section>

              <a href="#" target="_blank">
                <div class="col-md-2 col-sm-6 col-xs-12">
                  <div class="info-box">
                    <span class="info-box-icon bg-green"><i class="fa fa-usd"></i></span>

                    <div class="info-box-content">
                      <span class="info-box-text">TOTAL <br>SITE</span>
                      <span class="info-box-number">{{$draftertotal[0]->Drafter}}</span>
                    </div>
                    <!-- /.info-box-content -->
                  </div>
                  <!-- /.info-box -->
                </div>
              </a>

              <a href="#" target="_blank">
                <div class="col-md-2 col-sm-6 col-xs-12">
                  <div class="info-box">
                    <span class="info-box-icon bg-green"><i class="fa fa-usd"></i></span>

                    <div class="info-box-content">
                      <span class="info-box-text">TOTAL <br>SITE SURVEY</span>
                      <span class="info-box-number">?</span>
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

          <a href="#" target="_blank">
            <div class="col-md-2 col-sm-6 col-xs-12">
              <div class="info-box">
                <span class="info-box-icon bg-aqua"><i class="fa fa-usd"></i></span>

                <div class="info-box-content">
                  <span class="info-box-text">TOTAL <br>SITE<br>(INTERNAL)</span>
                  <span class="info-box-number">{{$draftertotal[0]->Drafter - $draftertotal[0]->Drafter_Subcon}}</span>
                </div>
                <!-- /.info-box-content -->
              </div>
              <!-- /.info-box -->
            </div>
          </a>

          <a href="#" target="_blank">
            <div class="col-md-2 col-sm-6 col-xs-12">
              <div class="info-box">
                <span class="info-box-icon bg-aqua"><i class="fa fa-usd"></i></span>

                <div class="info-box-content">
                  <span class="info-box-text">TOTAL <br>APPROVED<br>(INTERNAL)</span>
                  <span class="info-box-number">{{$draftertotal[0]->Drafter_Approved}}</span>
                </div>
                <!-- /.info-box-content -->
              </div>
              <!-- /.info-box -->
            </div>
          </a>

          <a href="#" target="_blank">
            <div class="col-md-2 col-sm-6 col-xs-12">
              <div class="info-box">
                <span class="info-box-icon bg-aqua"><i class="fa fa-usd"></i></span>

                <div class="info-box-content">
                  <span class="info-box-text">TOTAL <br>APPROVED WITH <br>COMMENT<br>(INTERNAL)</span>
                  <span class="info-box-number">{{$draftertotal[0]->Drafter_Approved_Comment}}</span>
                </div>
                <!-- /.info-box-content -->
              </div>
              <!-- /.info-box -->
            </div>
          </a>

          <a href="#" target="_blank">
            <div class="col-md-2 col-sm-6 col-xs-12">
              <div class="info-box">
                <span class="info-box-icon bg-red"><i class="fa fa-usd"></i></span>

                <div class="info-box-content">
                  <span class="info-box-text">TOTAL <br>PENDING APPROVAL<br>(INTERNAL)</span>
                  <span class="info-box-number">{{$draftertotal[0]->Drafter_Pending_Approval}}</span>
                </div>
                <!-- /.info-box-content -->
              </div>
              <!-- /.info-box -->
            </div>
          </a>

          <a href="#" target="_blank">
            <div class="col-md-2 col-sm-6 col-xs-12">
              <div class="info-box">
                <span class="info-box-icon bg-red"><i class="fa fa-usd"></i></span>

                <div class="info-box-content">
                  <span class="info-box-text">TOTAL <br>PENDING REVISED<br>(INTERNAL)</span>
                  <span class="info-box-number">{{$draftertotal[0]->Drafter_Pending_Revised}}</span>
                </div>
                <!-- /.info-box-content -->
              </div>
              <!-- /.info-box -->
            </div>
          </a>

          <a href="#" target="_blank">
            <div class="col-md-2 col-sm-6 col-xs-12">
              <div class="info-box">
                <span class="info-box-icon bg-red"><i class="fa fa-usd"></i></span>

                <div class="info-box-content">
                  <span class="info-box-text">TOTAL <br>PENDING REPORT<br>SUBMISSION<br>(INTERNAL)</span>
                  <span class="info-box-number">{{$draftertotal[0]->Drafter_Pending_Report}}</span>
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

      <a href="#" target="_blank">
        <div class="col-md-2 col-sm-6 col-xs-12">
          <div class="info-box">
            <span class="info-box-icon bg-yellow"><i class="fa fa-usd"></i></span>

            <div class="info-box-content">
              <span class="info-box-text">TOTAL <br>SITE<br>(SUBCON)</span>
              <span class="info-box-number">{{$draftertotal[0]->Drafter_Subcon}}</span>
            </div>
            <!-- /.info-box-content -->
          </div>
          <!-- /.info-box -->
        </div>
      </a>

      <a href="#" target="_blank">
        <div class="col-md-2 col-sm-6 col-xs-12">
          <div class="info-box">
            <span class="info-box-icon bg-yellow"><i class="fa fa-usd"></i></span>

            <div class="info-box-content">
              <span class="info-box-text">TOTAL <br>APPROVED<br>(SUBCON)</span>
              <span class="info-box-number">{{$draftertotal[0]->Drafter_Subcon_Approved}}</span>
            </div>
            <!-- /.info-box-content -->
          </div>
          <!-- /.info-box -->
        </div>
      </a>

      <a href="#" target="_blank">
        <div class="col-md-2 col-sm-6 col-xs-12">
          <div class="info-box">
            <span class="info-box-icon bg-yellow"><i class="fa fa-usd"></i></span>

            <div class="info-box-content">
              <span class="info-box-text">TOTAL <br>APPROVED WITH <br>COMMENT<br>(SUBCON)</span>
              <span class="info-box-number">{{$draftertotal[0]->Drafter_Subcon_Approved_Comment}}</span>
            </div>
            <!-- /.info-box-content -->
          </div>
          <!-- /.info-box -->
        </div>
      </a>

      <a href="#" target="_blank">
        <div class="col-md-2 col-sm-6 col-xs-12">
          <div class="info-box">
            <span class="info-box-icon bg-red"><i class="fa fa-usd"></i></span>

            <div class="info-box-content">
              <span class="info-box-text">TOTAL <br>PENDING APPROVAL<br>(SUBCON)</span>
              <span class="info-box-number">{{$draftertotal[0]->Drafter_Subcon_Pending_Approval}}</span>
            </div>
            <!-- /.info-box-content -->
          </div>
          <!-- /.info-box -->
        </div>
      </a>

      <a href="#" target="_blank">
        <div class="col-md-2 col-sm-6 col-xs-12">
          <div class="info-box">
            <span class="info-box-icon bg-red"><i class="fa fa-usd"></i></span>

            <div class="info-box-content">
              <span class="info-box-text">TOTAL <br>PENDING REVISED<br>(SUBCON)</span>
              <span class="info-box-number">{{$draftertotal[0]->Drafter_Subcon_Pending_Revised}}</span>
            </div>
            <!-- /.info-box-content -->
          </div>
          <!-- /.info-box -->
        </div>
      </a>

      <a href="#" target="_blank">
        <div class="col-md-2 col-sm-6 col-xs-12">
          <div class="info-box">
            <span class="info-box-icon bg-red"><i class="fa fa-usd"></i></span>

            <div class="info-box-content">
              <span class="info-box-text">TOTAL <br>PENDING REPORT<br>SUBMISSION<br>(SUBCON)</span>
              <span class="info-box-number">{{$draftertotal[0]->Drafter_Subcon_Pending_Report}}</span>
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
              DRAFTER (PO2)
            </h4>

            <h6>
              (UM/DG/SBC TRACKER)
            </h6>
          </section>

      <a href="#" target="_blank">
        <div class="col-md-2 col-sm-6 col-xs-12">
          <div class="info-box">
            <span class="info-box-icon bg-green"><i class="fa fa-usd"></i></span>

            <div class="info-box-content">
              <span class="info-box-text">TOTAL <br>SITE</span>
              <span class="info-box-number">{{$drafterpo2[0]->Total}}</span>
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

        <a href="#" target="_blank">
          <div class="col-md-2 col-sm-6 col-xs-12">
            <div class="info-box">
              <span class="info-box-icon bg-aqua"><i class="fa fa-usd"></i></span>

              <div class="info-box-content">
                <span class="info-box-text">TOTAL<br>CONSTRUCTION<br>DRAWING<br>(INTERNAL)</span>
                <span class="info-box-number">{{$drafterpo2[0]->Drafter_Internal}}</span>
              </div>
              <!-- /.info-box-content -->
            </div>
            <!-- /.info-box -->
          </div>
        </a>

        <a href="#" target="_blank">
          <div class="col-md-2 col-sm-6 col-xs-12">
            <div class="info-box">
              <span class="info-box-icon bg-aqua"><i class="fa fa-usd"></i></span>

              <div class="info-box-content">
                <span class="info-box-text">TOTAL<br>CONSTRUCTION<br>DRAWING SUBMISSION<br>(INTERNAL)</span>
                <span class="info-box-number">{{$drafterpo2[0]->Drafter_Drawing_Internal}}</span>
              </div>
              <!-- /.info-box-content -->
            </div>
            <!-- /.info-box -->
          </div>
        </a>

        <a href="#" target="_blank">
          <div class="col-md-2 col-sm-6 col-xs-12">
            <div class="info-box">
              <span class="info-box-icon bg-aqua"><i class="fa fa-usd"></i></span>

              <div class="info-box-content">
                <span class="info-box-text">TOTAL<br>CONSTRUCTION<br>DRAWING APPROVED<br>(INTERNAL)</span>
                <span class="info-box-number">{{$drafterpo2[0]->Drafter_Drawing_Approved_Internal}}</span>
              </div>
              <!-- /.info-box-content -->
            </div>
            <!-- /.info-box -->
          </div>
        </a>

        <a href="#" target="_blank">
          <div class="col-md-2 col-sm-6 col-xs-12">
            <div class="info-box">
              <span class="info-box-icon bg-aqua"><i class="fa fa-usd"></i></span>

              <div class="info-box-content">
                <span class="info-box-text">TOTAL<br>AS BUILT DRAWING<br>(INTERNAL)</span>
                <span class="info-box-number">?</span>
              </div>
              <!-- /.info-box-content -->
            </div>
            <!-- /.info-box -->
          </div>
        </a>

        <a href="#" target="_blank">
          <div class="col-md-2 col-sm-6 col-xs-12">
            <div class="info-box">
              <span class="info-box-icon bg-aqua"><i class="fa fa-usd"></i></span>

              <div class="info-box-content">
                <span class="info-box-text">TOTAL<br>AS BUILT DRAWING<br>DONE<br>(INTERNAL)</span>
                <span class="info-box-number">?</span>
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

          <a href="#" target="_blank">
            <div class="col-md-2 col-sm-6 col-xs-12">
              <div class="info-box">
                <span class="info-box-icon bg-yellow"><i class="fa fa-usd"></i></span>

                <div class="info-box-content">
                  <span class="info-box-text">TOTAL<br>CONSTRUCTION<br>DRAWING<br>(SUBCON)</span>
                  <span class="info-box-number">?</span>
                </div>
                <!-- /.info-box-content -->
              </div>
              <!-- /.info-box -->
            </div>
          </a>

          <a href="#" target="_blank">
            <div class="col-md-2 col-sm-6 col-xs-12">
              <div class="info-box">
                <span class="info-box-icon bg-yellow"><i class="fa fa-usd"></i></span>

                <div class="info-box-content">
                  <span class="info-box-text">TOTAL<br>CONSTRUCTION<br>DRAWING SUBMISSION<br>(SUBCON)</span>
                  <span class="info-box-number">{{$drafterpo2[0]->Drafter_Drawing_Submission_Subcon}}</span>
                </div>
                <!-- /.info-box-content -->
              </div>
              <!-- /.info-box -->
            </div>
          </a>

          <a href="#" target="_blank">
            <div class="col-md-2 col-sm-6 col-xs-12">
              <div class="info-box">
                <span class="info-box-icon bg-yellow"><i class="fa fa-usd"></i></span>

                <div class="info-box-content">
                  <span class="info-box-text">TOTAL<br>CONSTRUCTION<br>DRAWING APPROVED<br>(SUBCON)</span>
                  <span class="info-box-number">{{$drafterpo2[0]->Drafter_Drawing_Approved_Subcon}}</span>
                </div>
                <!-- /.info-box-content -->
              </div>
              <!-- /.info-box -->
            </div>
          </a>

          <a href="#" target="_blank">
            <div class="col-md-2 col-sm-6 col-xs-12">
              <div class="info-box">
                <span class="info-box-icon bg-yellow"><i class="fa fa-usd"></i></span>

                <div class="info-box-content">
                  <span class="info-box-text">TOTAL<br>AS BUILT DRAWING<br>(SUBCON)</span>
                  <span class="info-box-number">?</span>
                </div>
                <!-- /.info-box-content -->
              </div>
              <!-- /.info-box -->
            </div>
          </a>

          <a href="#" target="_blank">
            <div class="col-md-2 col-sm-6 col-xs-12">
              <div class="info-box">
                <span class="info-box-icon bg-yellow"><i class="fa fa-usd"></i></span>

                <div class="info-box-content">
                  <span class="info-box-text">TOTAL<br>AS BUILT DRAWING<br>DONE<br>(SUBCON)</span>
                  <span class="info-box-number">?</span>
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
                QS
              </h4>

              <h6>
                (UM/DG/SBC/RENEWAL/LEGALIZATION TRACKER)
              </h6>
            </section>

        <a href="#" target="_blank">
          <div class="col-md-2 col-sm-6 col-xs-12">
            <div class="info-box">
              <span class="info-box-icon bg-green"><i class="fa fa-usd"></i></span>

              <div class="info-box-content">
                <span class="info-box-text">TOTAL <br>SITE</span>
                <span class="info-box-number">{{$qs[0]->total_site}}</span>
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

          <a href="#" target="_blank">
            <div class="col-md-2 col-sm-6 col-xs-12">
              <div class="info-box">
                <span class="info-box-icon bg-aqua"><i class="fa fa-usd"></i></span>

                <div class="info-box-content">
                  <span class="info-box-text">TOTAL<br>BUDGET COSTING</span>
                  <span class="info-box-number">{{$qs[0]->total_budget}}</span>
                </div>
                <!-- /.info-box-content -->
              </div>
              <!-- /.info-box -->
            </div>
          </a>

          <a href="#" target="_blank">
            <div class="col-md-2 col-sm-6 col-xs-12">
              <div class="info-box">
                <span class="info-box-icon bg-aqua"><i class="fa fa-usd"></i></span>

                <div class="info-box-content">
                  <span class="info-box-text">TOTAL<br>BOQ SUBMISSION</span>
                  <span class="info-box-number">{{$qs[0]->total_submit}}</span>
                </div>
                <!-- /.info-box-content -->
              </div>
              <!-- /.info-box -->
            </div>
          </a>

          <a href="#" target="_blank">
            <div class="col-md-2 col-sm-6 col-xs-12">
              <div class="info-box">
                <span class="info-box-icon bg-aqua"><i class="fa fa-usd"></i></span>

                <div class="info-box-content">
                  <span class="info-box-text">TOTAL<br>BOQ APPROVED</span>
                  <span class="info-box-number">{{$qs[0]->total_approved}}</span>
                </div>
                <!-- /.info-box-content -->
              </div>
              <!-- /.info-box -->
            </div>
          </a>

          <a href="#" target="_blank">
            <div class="col-md-2 col-sm-6 col-xs-12">
              <div class="info-box">
                <span class="info-box-icon bg-aqua"><i class="fa fa-usd"></i></span>

                <div class="info-box-content">
                  <span class="info-box-text">TOTAL<br>PO RECEIVED</span>
                  <span class="info-box-number">{{$qs[0]->total_po}}</span>
                </div>
                <!-- /.info-box-content -->
              </div>
              <!-- /.info-box -->
            </div>
          </a>

          <a href="#" target="_blank">
            <div class="col-md-2 col-sm-6 col-xs-12">
              <div class="info-box">
                <span class="info-box-icon bg-red"><i class="fa fa-usd"></i></span>

                <div class="info-box-content">
                  <span class="info-box-text">TOTAL<br>BOQ APPROVED<br>PENDING PO</span>
                  <span class="info-box-number">{{$qs[0]->total_approved - $qs[0]->total_po}}</span>
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
                ROLLOUT
              </h4>

              <h6>
                (UM/DG/SBC TRACKER)
              </h6>
            </section>

        <a href="#" target="_blank">
          <div class="col-md-2 col-sm-6 col-xs-12">
            <div class="info-box">
              <span class="info-box-icon bg-green"><i class="fa fa-usd"></i></span>

              <div class="info-box-content">
                <span class="info-box-text">TOTAL <br>SITE</span>
                <span class="info-box-number">{{$rollout[0]->total_site}}</span>
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

          <a href="#" target="_blank">
            <div class="col-md-2 col-sm-6 col-xs-12">
              <div class="info-box">
                <span class="info-box-icon bg-red"><i class="fa fa-usd"></i></span>

                <div class="info-box-content">
                  <span class="info-box-text">TOTAL<br>PENDING NTP</span>
                  <span class="info-box-number">{{$rollout[0]->pending_ntp}}</span>
                </div>
                <!-- /.info-box-content -->
              </div>
              <!-- /.info-box -->
            </div>
          </a>

          <a href="#" target="_blank">
            <div class="col-md-2 col-sm-6 col-xs-12">
              <div class="info-box">
                <span class="info-box-icon bg-aqua"><i class="fa fa-usd"></i></span>

                <div class="info-box-content">
                  <span class="info-box-text">TOTAL<br>NOT YET START</span>
                  <span class="info-box-number">{{$rollout[0]->not_yet_start}}</span>
                </div>
                <!-- /.info-box-content -->
              </div>
              <!-- /.info-box -->
            </div>
          </a>

          <a href="#" target="_blank">
            <div class="col-md-2 col-sm-6 col-xs-12">
              <div class="info-box">
                <span class="info-box-icon bg-aqua"><i class="fa fa-usd"></i></span>

                <div class="info-box-content">
                  <span class="info-box-text">TOTAL<br>WIP</span>
                  <span class="info-box-number">{{$rollout[0]->wip}}</span>
                </div>
                <!-- /.info-box-content -->
              </div>
              <!-- /.info-box -->
            </div>
          </a>

          <a href="#" target="_blank">
            <div class="col-md-2 col-sm-6 col-xs-12">
              <div class="info-box">
                <span class="info-box-icon bg-red"><i class="fa fa-usd"></i></span>

                <div class="info-box-content">
                  <span class="info-box-text">TOTAL<br>COMPLETED PENDING ATP</span>
                  <span class="info-box-number">{{$rollout[0]->pending_atp}}</span>
                </div>
                <!-- /.info-box-content -->
              </div>
              <!-- /.info-box -->
            </div>
          </a>

          <a href="#" target="_blank">
            <div class="col-md-2 col-sm-6 col-xs-12">
              <div class="info-box">
                <span class="info-box-icon bg-red"><i class="fa fa-usd"></i></span>

                <div class="info-box-content">
                  <span class="info-box-text">TOTAL<br>PENDING PLC</span>
                  <span class="info-box-number">{{$rollout[0]->pending_plc}}</span>
                </div>
                <!-- /.info-box-content -->
              </div>
              <!-- /.info-box -->
            </div>
          </a>

          <a href="#" target="_blank">
            <div class="col-md-2 col-sm-6 col-xs-12">
              <div class="info-box">
                <span class="info-box-icon bg-aqua"><i class="fa fa-usd"></i></span>

                <div class="info-box-content">
                  <span class="info-box-text">TOTAL<br>SITE DONE</span>
                  <span class="info-box-number">{{$rollout[0]->site_done}}</span>
                </div>
                <!-- /.info-box-content -->
              </div>
              <!-- /.info-box -->
            </div>
          </a>

          <a href="#" target="_blank">
            <div class="col-md-2 col-sm-6 col-xs-12">
              <div class="info-box">
                <span class="info-box-icon bg-aqua"><i class="fa fa-usd"></i></span>

                <div class="info-box-content">
                  <span class="info-box-text">TOTAL<br>KIV</span>
                  <span class="info-box-number">{{$rollout[0]->kiv}}</span>
                </div>
                <!-- /.info-box-content -->
              </div>
              <!-- /.info-box -->
            </div>
          </a>

          <a href="#" target="_blank">
            <div class="col-md-2 col-sm-6 col-xs-12">
              <div class="info-box">
                <span class="info-box-icon bg-aqua"><i class="fa fa-usd"></i></span>

                <div class="info-box-content">
                  <span class="info-box-text">TOTAL<br>CANCELLED/DROP</span>
                  <span class="info-box-number">{{$rollout[0]->cancelled}}</span>
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
                TNB/SESB
              </h4>

              <h6>
                (UM/DG/SBC TRACKER)
              </h6>
            </section>

        <a href="#" target="_blank">
          <div class="col-md-2 col-sm-6 col-xs-12">
            <div class="info-box">
              <span class="info-box-icon bg-green"><i class="fa fa-usd"></i></span>

              <div class="info-box-content">
                <span class="info-box-text">TOTAL <br>SITE</span>
                <span class="info-box-number">{{$tnb[0]->total_site}}</span>
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

          <a href="#" target="_blank">
            <div class="col-md-2 col-sm-6 col-xs-12">
              <div class="info-box">
                <span class="info-box-icon bg-aqua"><i class="fa fa-usd"></i></span>

                <div class="info-box-content">
                  <span class="info-box-text">TOTAL<br>APPLICATION</span>
                  <span class="info-box-number">{{$tnb[0]->total_application}}</span>
                </div>
                <!-- /.info-box-content -->
              </div>
              <!-- /.info-box -->
            </div>
          </a>

          <a href="#" target="_blank">
            <div class="col-md-2 col-sm-6 col-xs-12">
              <div class="info-box">
                <span class="info-box-icon bg-aqua"><i class="fa fa-usd"></i></span>

                <div class="info-box-content">
                  <span class="info-box-text">TOTAL<br>APPROVAL</span>
                  <span class="info-box-number">{{$tnb[0]->total_approval}}</span>
                </div>
                <!-- /.info-box-content -->
              </div>
              <!-- /.info-box -->
            </div>
          </a>

          <a href="#" target="_blank">
            <div class="col-md-2 col-sm-6 col-xs-12">
              <div class="info-box">
                <span class="info-box-icon bg-red"><i class="fa fa-usd"></i></span>

                <div class="info-box-content">
                  <span class="info-box-text">TOTAL<br>CSP</span>
                  <span class="info-box-number">{{$tnb[0]->total_csp}}</span>
                </div>
                <!-- /.info-box-content -->
              </div>
              <!-- /.info-box -->
            </div>
          </a>

          <a href="#" target="_blank">
            <div class="col-md-2 col-sm-6 col-xs-12">
              <div class="info-box">
                <span class="info-box-icon bg-red"><i class="fa fa-usd"></i></span>

                <div class="info-box-content">
                  <span class="info-box-text">TOTAL<br>DEMAND LETTER</span>
                  <span class="info-box-number">{{$tnb[0]->total_demand}}</span>
                </div>
                <!-- /.info-box-content -->
              </div>
              <!-- /.info-box -->
            </div>
          </a>

          <a href="#" target="_blank">
            <div class="col-md-2 col-sm-6 col-xs-12">
              <div class="info-box">
                <span class="info-box-icon bg-aqua"><i class="fa fa-usd"></i></span>

                <div class="info-box-content">
                  <span class="info-box-text">TOTAL<br>CSP PAYMENT</span>
                  <span class="info-box-number">{{$tnb[0]->total_csp_payment}}</span>
                </div>
                <!-- /.info-box-content -->
              </div>
              <!-- /.info-box -->
            </div>
          </a>

          <a href="#" target="_blank">
            <div class="col-md-2 col-sm-6 col-xs-12">
              <div class="info-box">
                <span class="info-box-icon bg-aqua"><i class="fa fa-usd"></i></span>

                <div class="info-box-content">
                  <span class="info-box-text">TOTAL<br>DEPOSIT PAYMENT</span>
                  <span class="info-box-number">{{$tnb[0]->total_deposit_payment}}</span>
                </div>
                <!-- /.info-box-content -->
              </div>
              <!-- /.info-box -->
            </div>
          </a>

          <a href="#" target="_blank">
            <div class="col-md-2 col-sm-6 col-xs-12">
              <div class="info-box">
                <span class="info-box-icon bg-aqua"><i class="fa fa-usd"></i></span>

                <div class="info-box-content">
                  <span class="info-box-text">TOTAL<br>METER INSTALLED</span>
                  <span class="info-box-number">{{$tnb[0]->total_tnb_installation}}</span>
                </div>
                <!-- /.info-box-content -->
              </div>
              <!-- /.info-box -->
            </div>
          </a>

          <a href="#" target="_blank">
            <div class="col-md-2 col-sm-6 col-xs-12">
              <div class="info-box">
                <span class="info-box-icon bg-aqua"><i class="fa fa-usd"></i></span>

                <div class="info-box-content">
                  <span class="info-box-text">TOTAL<br>INVOICED</span>
                  <span class="info-box-number">{{$tnb[0]->total_tnb_invoice}}</span>
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
                ASSR/SA
              </h4>

              <h6>
                (DG TRACKER)
              </h6>
            </section>

        <a href="#" target="_blank">
          <div class="col-md-2 col-sm-6 col-xs-12">
            <div class="info-box">
              <span class="info-box-icon bg-green"><i class="fa fa-usd"></i></span>

              <div class="info-box-content">
                <span class="info-box-text">TOTAL <br>SITE</span>
                <span class="info-box-number">{{$assr[0]->total_site}}</span>
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

          <a href="#" target="_blank">
            <div class="col-md-2 col-sm-6 col-xs-12">
              <div class="info-box">
                <span class="info-box-icon bg-aqua"><i class="fa fa-usd"></i></span>

                <div class="info-box-content">
                  <span class="info-box-text">TOTAL<br>ASSR/GE SUBMISSION</span>
                  <span class="info-box-number">{{$assr[0]->total_submit}}</span>
                </div>
                <!-- /.info-box-content -->
              </div>
              <!-- /.info-box -->
            </div>
          </a>

          <a href="#" target="_blank">
            <div class="col-md-2 col-sm-6 col-xs-12">
              <div class="info-box">
                <span class="info-box-icon bg-aqua"><i class="fa fa-usd"></i></span>

                <div class="info-box-content">
                  <span class="info-box-text">TOTAL<br>ASSR/GE APPROVAL</span>
                  <span class="info-box-number">{{$assr[0]->total_approval}}</span>
                </div>
                <!-- /.info-box-content -->
              </div>
              <!-- /.info-box -->
            </div>
          </a>

          <a href="#" target="_blank">
            <div class="col-md-2 col-sm-6 col-xs-12">
              <div class="info-box">
                <span class="info-box-icon bg-aqua"><i class="fa fa-usd"></i></span>

                <div class="info-box-content">
                  <span class="info-box-text">TOTAL<br>SIGNED LOI</span>
                  <span class="info-box-number">{{$assr[0]->total_loi}}</span>
                </div>
                <!-- /.info-box-content -->
              </div>
              <!-- /.info-box -->
            </div>
          </a>

          <a href="#" target="_blank">
            <div class="col-md-2 col-sm-6 col-xs-12">
              <div class="info-box">
                <span class="info-box-icon bg-aqua"><i class="fa fa-usd"></i></span>

                <div class="info-box-content">
                  <span class="info-box-text">TOTAL<br>SIGNED TA</span>
                  <span class="info-box-number">{{$assr[0]->total_ta}}</span>
                </div>
                <!-- /.info-box-content -->
              </div>
              <!-- /.info-box -->
            </div>
          </a>

          <a href="#" target="_blank">
            <div class="col-md-2 col-sm-6 col-xs-12">
              <div class="info-box">
                <span class="info-box-icon bg-aqua"><i class="fa fa-usd"></i></span>

                <div class="info-box-content">
                  <span class="info-box-text">TOTAL<br>STAMPED TA</span>
                  <span class="info-box-number">{{$assr[0]->total_stamp}}</span>
                </div>
                <!-- /.info-box-content -->
              </div>
              <!-- /.info-box -->
            </div>
          </a>

          <a href="#" target="_blank">
            <div class="col-md-2 col-sm-6 col-xs-12">
              <div class="info-box">
                <span class="info-box-icon bg-aqua"><i class="fa fa-usd"></i></span>

                <div class="info-box-content">
                  <span class="info-box-text">TOTAL<br>SA ATP</span>
                  <span class="info-box-number">{{$assr[0]->total_actual}}</span>
                </div>
                <!-- /.info-box-content -->
              </div>
              <!-- /.info-box -->
            </div>
          </a>

          <a href="#" target="_blank">
            <div class="col-md-2 col-sm-6 col-xs-12">
              <div class="info-box">
                <span class="info-box-icon bg-aqua"><i class="fa fa-usd"></i></span>

                <div class="info-box-content">
                  <span class="info-box-text">TOTAL<br>CSI</span>
                  <span class="info-box-number">{{$assr[0]->total_csi}}</span>
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
                LC SUBMISSION
              </h4>

              <h6>
                (DG TRACKER)
              </h6>
            </section>

        <a href="#" target="_blank">
          <div class="col-md-2 col-sm-6 col-xs-12">
            <div class="info-box">
              <span class="info-box-icon bg-green"><i class="fa fa-usd"></i></span>

              <div class="info-box-content">
                <span class="info-box-text">TOTAL <br>SITE</span>
                <span class="info-box-number">{{$lc[0]->total_site}}</span>
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

          <a href="#" target="_blank">
            <div class="col-md-2 col-sm-6 col-xs-12">
              <div class="info-box">
                <span class="info-box-icon bg-aqua"><i class="fa fa-usd"></i></span>

                <div class="info-box-content">
                  <span class="info-box-text">TOTAL<br>SIGNED LOI</span>
                  <span class="info-box-number">{{$lc[0]->total_loi}}</span>
                </div>
                <!-- /.info-box-content -->
              </div>
              <!-- /.info-box -->
            </div>
          </a>

          <a href="#" target="_blank">
            <div class="col-md-2 col-sm-6 col-xs-12">
              <div class="info-box">
                <span class="info-box-icon bg-aqua"><i class="fa fa-usd"></i></span>

                <div class="info-box-content">
                  <span class="info-box-text">TOTAL<br>SIGNED LOI</span>
                  <span class="info-box-number">{{$lc[0]->total_ta}}</span>
                </div>
                <!-- /.info-box-content -->
              </div>
              <!-- /.info-box -->
            </div>
          </a>

          <a href="#" target="_blank">
            <div class="col-md-2 col-sm-6 col-xs-12">
              <div class="info-box">
                <span class="info-box-icon bg-aqua"><i class="fa fa-usd"></i></span>

                <div class="info-box-content">
                  <span class="info-box-text">TOTAL<br>STAMPED TA</span>
                  <span class="info-box-number">{{$lc[0]->total_stamp}}</span>
                </div>
                <!-- /.info-box-content -->
              </div>
              <!-- /.info-box -->
            </div>
          </a>

          <a href="#" target="_blank">
            <div class="col-md-2 col-sm-6 col-xs-12">
              <div class="info-box">
                <span class="info-box-icon bg-aqua"><i class="fa fa-usd"></i></span>

                <div class="info-box-content">
                  <span class="info-box-text">TOTAL<br>PE DRAWING<br>RECEIVED</span>
                  <span class="info-box-number">{{$lc[0]->total_pe}}</span>
                </div>
                <!-- /.info-box-content -->
              </div>
              <!-- /.info-box -->
            </div>
          </a>

          <a href="#" target="_blank">
            <div class="col-md-2 col-sm-6 col-xs-12">
              <div class="info-box">
                <span class="info-box-icon bg-aqua"><i class="fa fa-usd"></i></span>

                <div class="info-box-content">
                  <span class="info-box-text">TOTAL<br>QUIT RENT</span>
                  <span class="info-box-number">{{$lc[0]->total_rent}}</span>
                </div>
                <!-- /.info-box-content -->
              </div>
              <!-- /.info-box -->
            </div>
          </a>

          <a href="#" target="_blank">
            <div class="col-md-2 col-sm-6 col-xs-12">
              <div class="info-box">
                <span class="info-box-icon bg-aqua"><i class="fa fa-usd"></i></span>

                <div class="info-box-content">
                  <span class="info-box-text">TOTAL<br>FEE PERMIT</span>
                  <span class="info-box-number">{{$lc[0]->total_fee}}</span>
                </div>
                <!-- /.info-box-content -->
              </div>
              <!-- /.info-box -->
            </div>
          </a>
          <div class="col-md-2 col-sm-6 col-xs-12">
            <div class="info-box">
              <span class="info-box-icon bg-aqua"><i class="fa fa-usd"></i></span>

              <div class="info-box-content">
                <span class="info-box-text">TOTAL<br>FEE Process</span>
                <span class="info-box-number">{{$lc[0]->total_fee_process}}</span>
              </div>
              <!-- /.info-box-content -->
            </div>
            <!-- /.info-box -->
          </div>

          <a href="#" target="_blank">
            <div class="col-md-2 col-sm-6 col-xs-12">
              <div class="info-box">
                <span class="info-box-icon bg-aqua"><i class="fa fa-usd"></i></span>

                <div class="info-box-content">
                  <span class="info-box-text">TOTAL<br>ONLINE SUBMISSION</span>
                  <span class="info-box-number">{{$lc[0]->total_online_submission}}</span>
                </div>
                <!-- /.info-box-content -->
              </div>
              <!-- /.info-box -->
            </div>
          </a>

          <a href="#" target="_blank">
            <div class="col-md-2 col-sm-6 col-xs-12">
              <div class="info-box">
                <span class="info-box-icon bg-aqua"><i class="fa fa-usd"></i></span>

                <div class="info-box-content">
                  <span class="info-box-text">TOTAL<br>HARDCOPY SUBMISSION</span>
                  <span class="info-box-number">{{$lc[0]->total_hardcopy}}</span>
                </div>
                <!-- /.info-box-content -->
              </div>
              <!-- /.info-box -->
            </div>
          </a>

          <a href="#" target="_blank">
            <div class="col-md-2 col-sm-6 col-xs-12">
              <div class="info-box">
                <span class="info-box-icon bg-aqua"><i class="fa fa-usd"></i></span>

                <div class="info-box-content">
                  <span class="info-box-text">TOTAL<br>APPROVED  MCMC</span>
                  <span class="info-box-number">{{$lc[0]->total_mc}}</span>
                </div>
                <!-- /.info-box-content -->
              </div>
              <!-- /.info-box -->
            </div>
          </a>

          <a href="#" target="_blank">
            <div class="col-md-2 col-sm-6 col-xs-12">
              <div class="info-box">
                <span class="info-box-icon bg-aqua"><i class="fa fa-usd"></i></span>

                <div class="info-box-content">
                  <span class="info-box-text">TOTAL<br>APPROVED DCA</span>
                  <span class="info-box-number">{{$lc[0]->total_dca}}</span>
                </div>
                <!-- /.info-box-content -->
              </div>
              <!-- /.info-box -->
            </div>
          </a>

          <a href="#" target="_blank">
            <div class="col-md-2 col-sm-6 col-xs-12">
              <div class="info-box">
                <span class="info-box-icon bg-aqua"><i class="fa fa-usd"></i></span>

                <div class="info-box-content">
                  <span class="info-box-text">TOTAL<br>OSHA</span>
                  <span class="info-box-number">{{$lc[0]->total_osha}}</span>
                </div>
                <!-- /.info-box-content -->
              </div>
              <!-- /.info-box -->
            </div>
          </a>

          <a href="#" target="_blank">
            <div class="col-md-2 col-sm-6 col-xs-12">
              <div class="info-box">
                <span class="info-box-icon bg-aqua"><i class="fa fa-usd"></i></span>

                <div class="info-box-content">
                  <span class="info-box-text">TOTAL<br>BOMBA SUBMISSION</span>
                  <span class="info-box-number">{{$lc[0]->total_bomba}}</span>
                </div>
                <!-- /.info-box-content -->
              </div>
              <!-- /.info-box -->
            </div>
          </a>

          <a href="#" target="_blank">
            <div class="col-md-2 col-sm-6 col-xs-12">
              <div class="info-box">
                <span class="info-box-icon bg-aqua"><i class="fa fa-usd"></i></span>

                <div class="info-box-content">
                  <span class="info-box-text">TOTAL<br>APPROVED LC/LA</span>
                  <span class="info-box-number">{{$lc[0]->total_lc}}</span>
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
                RENEWAL AUDIT
              </h4>

              <h6>
                (RENEWAL AUDIT TRACKER)
              </h6>
            </section>

        <a href="#" target="_blank">
          <div class="col-md-2 col-sm-6 col-xs-12">
            <div class="info-box">
              <span class="info-box-icon bg-green"><i class="fa fa-usd"></i></span>

              <div class="info-box-content">
                <span class="info-box-text">TOTAL <br>SITE</span>
                <span class="info-box-number">{{$renewal[0]->total_site}}</span>
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

          <a href="#" target="_blank">
            <div class="col-md-2 col-sm-6 col-xs-12">
              <div class="info-box">
                <span class="info-box-icon bg-aqua"><i class="fa fa-usd"></i></span>

                <div class="info-box-content">
                  <span class="info-box-text">TOTAL<br>ACKNOWLEDGEMENT</span>
                  <span class="info-box-number">{{$renewal[0]->total_doc}}</span>
                </div>
                <!-- /.info-box-content -->
              </div>
              <!-- /.info-box -->
            </div>
          </a>

          <a href="#" target="_blank">
            <div class="col-md-2 col-sm-6 col-xs-12">
              <div class="info-box">
                <span class="info-box-icon bg-red"><i class="fa fa-usd"></i></span>

                <div class="info-box-content">
                  <span class="info-box-text">TOTAL<br>PENDING DOCUMENT</span>
                  <span class="info-box-number">{{(float) $renewal[0]->total_site -$renewal[0]->total_doc}}</span>
                </div>
                <!-- /.info-box-content -->
              </div>
              <!-- /.info-box -->
            </div>
          </a>


          <div class="row">

            <div class="col-md-12">

              <section class="content-header">
                <h4>
                  LEGALIZATION
                </h4>

                <h6>
                  (LEGALIZATION TRACKER)
                </h6>
              </section>

          <a href="#" target="_blank">
            <div class="col-md-2 col-sm-6 col-xs-12">
              <div class="info-box">
                <span class="info-box-icon bg-green"><i class="fa fa-usd"></i></span>

                <div class="info-box-content">
                  <span class="info-box-text">TOTAL <br>SITE</span>
                  <span class="info-box-number">{{$legalization[0]->total_site}}</span>
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

            <a href="#" target="_blank">
              <div class="col-md-2 col-sm-6 col-xs-12">
                <div class="info-box">
                  <span class="info-box-icon bg-aqua"><i class="fa fa-usd"></i></span>

                  <div class="info-box-content">
                    <span class="info-box-text">TOTAL<br>TNB</span>
                    <span class="info-box-number">{{$legalization[0]->total_tnb}}</span>
                  </div>
                  <!-- /.info-box-content -->
                </div>
                <!-- /.info-box -->
              </div>
            </a>

            <a href="#" target="_blank">
              <div class="col-md-2 col-sm-6 col-xs-12">
                <div class="info-box">
                  <span class="info-box-icon bg-aqua"><i class="fa fa-usd"></i></span>

                  <div class="info-box-content">
                    <span class="info-box-text">TOTAL<br>APPROVED  MCMC</span>
                    <span class="info-box-number">{{$legalization[0]->total_mcmc}}</span>
                  </div>
                  <!-- /.info-box-content -->
                </div>
                <!-- /.info-box -->
              </div>
            </a>

            <a href="#" target="_blank">
              <div class="col-md-2 col-sm-6 col-xs-12">
                <div class="info-box">
                  <span class="info-box-icon bg-aqua"><i class="fa fa-usd"></i></span>

                  <div class="info-box-content">
                    <span class="info-box-text">TOTAL<br>APPROVED DCA</span>
                    <span class="info-box-number">{{$legalization[0]->total_dca}}</span>
                  </div>
                  <!-- /.info-box-content -->
                </div>
                <!-- /.info-box -->
              </div>
            </a>

            <a href="#" target="_blank">
              <div class="col-md-2 col-sm-6 col-xs-12">
                <div class="info-box">
                  <span class="info-box-icon bg-aqua"><i class="fa fa-usd"></i></span>

                  <div class="info-box-content">
                    <span class="info-box-text">TOTAL<br>OSHA</span>
                    <span class="info-box-number">{{$legalization[0]->total_osha}}</span>
                  </div>
                  <!-- /.info-box-content -->
                </div>
                <!-- /.info-box -->
              </div>
            </a>

            <a href="#" target="_blank">
              <div class="col-md-2 col-sm-6 col-xs-12">
                <div class="info-box">
                  <span class="info-box-icon bg-aqua"><i class="fa fa-usd"></i></span>

                  <div class="info-box-content">
                    <span class="info-box-text">TOTAL<br>BOMBA SUBMISSION</span>
                    <span class="info-box-number">{{$legalization[0]->total_bomba}}</span>
                  </div>
                  <!-- /.info-box-content -->
                </div>
                <!-- /.info-box -->
              </div>
            </a>

            <a href="#" target="_blank">
              <div class="col-md-2 col-sm-6 col-xs-12">
                <div class="info-box">
                  <span class="info-box-icon bg-aqua"><i class="fa fa-usd"></i></span>

                  <div class="info-box-content">
                    <span class="info-box-text">TOTAL<br>LC/LA SUBMISSION</span>
                    <span class="info-box-number">{{$legalization[0]->total_lc}}</span>
                  </div>
                  <!-- /.info-box-content -->
                </div>
                <!-- /.info-box -->
              </div>
            </a>

            <a href="#" target="_blank">
              <div class="col-md-2 col-sm-6 col-xs-12">
                <div class="info-box">
                  <span class="info-box-icon bg-aqua"><i class="fa fa-usd"></i></span>

                  <div class="info-box-content">
                    <span class="info-box-text">TOTAL<br>APPROVED LC/LA</span>
                    <span class="info-box-number">{{$legalization[0]->total_approval}}</span>
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
                OSU (PO1/PO3/PO4)
              </h4>

              <h6>
                (UM/DG/LEGALIZATION/RENEWAL AUDIT/SA/LC SUBMISSION TRACKER)
              </h6>
            </section>

        <a href="#" target="_blank">
          <div class="col-md-2 col-sm-6 col-xs-12">
            <div class="info-box">
              <span class="info-box-icon bg-green"><i class="fa fa-usd"></i></span>

              <div class="info-box-content">
                <span class="info-box-text">TOTAL <br>SITE</span>
                <span class="info-box-number">{{$osu[0]->total_site}}</span>
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

          <a href="#" target="_blank">
            <div class="col-md-2 col-sm-6 col-xs-12">
              <div class="info-box">
                <span class="info-box-icon bg-aqua"><i class="fa fa-usd"></i></span>

                <div class="info-box-content">
                  <span class="info-box-text">TOTAL<br>SITE DONE</span>
                  <span class="info-box-number">{{$osu[0]->site_done}}</span>
                </div>
                <!-- /.info-box-content -->
              </div>
              <!-- /.info-box -->
            </div>
          </a>

          <a href="#" target="_blank">
            <div class="col-md-2 col-sm-6 col-xs-12">
              <div class="info-box">
                <span class="info-box-icon bg-aqua"><i class="fa fa-usd"></i></span>

                <div class="info-box-content">
                  <span class="info-box-text">TOTAL<br>SITE PACK READY</span>
                  <span class="info-box-number">{{$osu[0]->total_site_pack}}</span>
                </div>
                <!-- /.info-box-content -->
              </div>
              <!-- /.info-box -->
            </div>
          </a>

          <a href="#" target="_blank">
            <div class="col-md-2 col-sm-6 col-xs-12">
              <div class="info-box">
                <span class="info-box-icon bg-aqua"><i class="fa fa-usd"></i></span>

                <div class="info-box-content">
                  <span class="info-box-text">TOTAL<br>TO INVOICE</span>
                  <span class="info-box-number">{{$osu[0]->total_to_invoice}}</span>
                </div>
                <!-- /.info-box-content -->
              </div>
              <!-- /.info-box -->
            </div>
          </a>

          <a href="#" target="_blank">
            <div class="col-md-2 col-sm-6 col-xs-12">
              <div class="info-box">
                <span class="info-box-icon bg-aqua"><i class="fa fa-usd"></i></span>

                <div class="info-box-content">
                  <span class="info-box-text">TOTAL<br>SO</span>
                  <span class="info-box-number">{{$osu[0]->total_so}}</span>
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
              OSU (PO2)
            </h4>

            <h6>
              (UM/DG TRACKER)
            </h6>
          </section>

      <a href="#" target="_blank">
        <div class="col-md-2 col-sm-6 col-xs-12">
          <div class="info-box">
            <span class="info-box-icon bg-green"><i class="fa fa-usd"></i></span>

            <div class="info-box-content">
              <span class="info-box-text">TOTAL <br>SITE</span>
              <span class="info-box-number">{{$osu2[0]->total_site}}</span>
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

        <a href="#" target="_blank">
          <div class="col-md-2 col-sm-6 col-xs-12">
            <div class="info-box">
              <span class="info-box-icon bg-aqua"><i class="fa fa-usd"></i></span>

              <div class="info-box-content">
                <span class="info-box-text">TOTAL<br>SITE DONE</span>
                <span class="info-box-number">{{$osu2[0]->site_done}}</span>
              </div>
              <!-- /.info-box-content -->
            </div>
            <!-- /.info-box -->
          </div>
        </a>

        <a href="#" target="_blank">
          <div class="col-md-2 col-sm-6 col-xs-12">
            <div class="info-box">
              <span class="info-box-icon bg-aqua"><i class="fa fa-usd"></i></span>

              <div class="info-box-content">
                <span class="info-box-text">TOTAL<br>SITE PACK READY</span>
                <span class="info-box-number">{{$osu2[0]->total_site_pack}}</span>
              </div>
              <!-- /.info-box-content -->
            </div>
            <!-- /.info-box -->
          </div>
        </a>

        <a href="#" target="_blank">
          <div class="col-md-2 col-sm-6 col-xs-12">
            <div class="info-box">
              <span class="info-box-icon bg-aqua"><i class="fa fa-usd"></i></span>

              <div class="info-box-content">
                <span class="info-box-text">TOTAL<br>TO INVOICE</span>
                <span class="info-box-number">{{$osu2[0]->total_to_invoice}}</span>
              </div>
              <!-- /.info-box-content -->
            </div>
            <!-- /.info-box -->
          </div>
        </a>

        <a href="#" target="_blank">
          <div class="col-md-2 col-sm-6 col-xs-12">
            <div class="info-box">
              <span class="info-box-icon bg-aqua"><i class="fa fa-usd"></i></span>

              <div class="info-box-content">
                <span class="info-box-text">TOTAL<br>SO</span>
                <span class="info-box-number">{{$osu2[0]->total_so}}</span>
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

    </div>

  </div>

    </section>

  </div>

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
  var project=$('#Project').val();
  var d=$('#range').val();
  var arr = d.split(" - ");
  var check=$("#dateRange").is(':checked');
  if(check)
    arr="&start="+arr[0]+"&end="+arr[1];
  else arr="";
  if(project) project="&project="+project;
  else project="";
  window.location.href ="{{ url("/dashboard2") }}?check="+check+arr+project;
}
</script>

@endsection
