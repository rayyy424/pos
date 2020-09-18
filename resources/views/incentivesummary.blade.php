
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

      .leavetable{
        text-align: center;
      }

      /* table.dataTable th {
  			min-width: 35px;
  	} */

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

      <script type="text/javascript" language="javascript" class="init">

      var summary;

      $(document).ready(function() {

          summary = $('#summary').dataTable( {

                  columnDefs: [{ "width": "35px", "targets": "_all" },{ "visible": false, "targets": [0,1,13,14,15,16,17] },{"className": "dt-left", "targets": [1]},{"className": "dt-center", "targets": "_all"}],
                  responsive: false,
                  colReorder: false,
                  dom: "lBfrti",
                  sScrollX: "100%",
                  // bAutoWidth: true,
                  sScrollY: "100%",
                  scrollCollapse: true,
                  bPaginate:false,
                  fnInitComplete: function(oSettings, json) {

                    var rows=this.api().rows( { search: 'applied' } ).data().toArray();
                    var totalamount=0;
                    var site=[];

                    for (var i = 0; i < rows.length; i++) {

                        totalamount=totalamount+parseFloat(rows[i][12]);

                        if(site.indexOf(rows[i][5])==-1)
                        {
                          site.push(rows[i][5]);
                        }

                    }

                    $("#total").html("<br>RM " + parseFloat(totalamount.toFixed(2)).toLocaleString("en"));
                    $("#sitecount").html("<br>" + site.length);
                   },
                 buttons: [
                         {
                                 extend: 'collection',
                                 text: 'Export',
                                 buttons: [
                                         'csv'
                                 ]
                         }
                 ],

            });

            $('#summary').on( 'search.dt', function () {

              var rows=summary.api().rows( { search: 'applied' } ).data().toArray();
              var totalamount=0;
              var site=[];

              for (var i = 0; i < rows.length; i++) {

                  totalamount=totalamount+parseFloat(rows[i][12]);

                  if(site.indexOf(rows[i][5])==-1)
                  {
                    site.push(rows[i][5]);
                  }
              }

              $("#total").html("<br>RM " + parseFloat(totalamount.toFixed(2)).toLocaleString("en"));
              $("#sitecount").html("<br>" + site.length);


            } );

            $(".summary thead input").keyup ( function () {

                    /* Filter on the column (the index) of this element */
                    if ($('#summary').length > 0)
                    {

                        var colnum=document.getElementById('summary').rows[0].cells.length;

                        if (this.value=="[empty]")
                        {

                           summary.fnFilter( '^$', this.name,true,false );
                        }
                        else if (this.value=="[nonempty]")
                        {

                           summary.fnFilter( '^(?=\\s*\\S).*$', this.name,true,false );
                        }
                        else if (this.value.startsWith("!")==true && this.value.length>1)
                        {

                           summary.fnFilter( '^(?'+ this.value +').*', this.name,true,false );
                        }
                        else if (this.value.startsWith("!")==false)
                        {

                            summary.fnFilter( this.value, this.name,true,false );
                        }
                    }



            } );

        });

      </script>

      @endsection

@section('content')

  <div class="content-wrapper">
      <!-- Content Header (Page header) -->
    <section class="content-header">
      <h1>
      Incentive Summary
      <small>Human Resource</small>
      </h1>

      <ol class="breadcrumb">
      <li><a href="{{ url('/home') }}"><i class="fa fa-dashboard"></i> Home</a></li>
      <li><a href="#">Human Resource</a></li>
      <li class="active">Incentive Summary</li>
      </ol>
    </section>

    <section class="content">
      <br>
      <div class="row">
        <div class="col-md-12">
          <div class="box">
            <div class="box-body">
               <div class="row">


                  <div class="col-md-4">
                   <div class="input-group">
                     <div class="input-group-addon">
                       <i class="fa fa-clock-o"></i>
                     </div>
                     <input type="text" class="form-control" id="range" name="range">

                   </div>
                 </div>
                 <div class="col-sm-3">
                   <div class="form-group">

                     <select class="form-control" id="Client" name="Client" style="width: 100%;">
                       <option></option>
                       @foreach($clients as $cl)
                         @if($cl->Client)
                           <option {{ trim($cl->Client) == trim($client) ? 'selected' : '' }}>{{$cl->Client}}</option>
                         @endif
                       @endforeach

                     </select>
                   </div>
                  </div>
                 <div class="col-md-2">
                   <div class="checkbox">
                     <label><input type="checkbox" id="includeresigned" name="includeresigned" {{ $includeResigned == "true" ? 'checked' : '' }}> Include Resigned</label>
                   </div>
                 </div>
                 <div class="col-md-2">
                     <div class="input-group">
                       <button type="button" class="btn btn-success btn" data-toggle="modal" onclick="refresh();">Refresh</button>
                     </div>
                 </div>



               </div>
               <div class="row">
                 <div class="col-md-3">
                   <h4 class="" >Total Incentive : <i><span id='total'>RM0.00</span></i></h4>
                 </div>

                 <div class="col-md-3">
                   <h4 class="" >Number of Site Completed : <i><span class="" id='sitecount'>0</span></i></h4>
                 </div>
               </div>
            </div>
          </div>
        </div>
      </div>


      <div class="box">
        <div class="box-body">

          <table id="summary" class="summary" cellspacing="0" width="100%" padding="30px" style="font-size: 13px;">
                <thead>

                  @if($summary)
                    <tr class="search">

                      @foreach($summary as $key=>$value)

                        @if ($key==0)
                          <?php $i = 0; ?>

                          @foreach($value as $field=>$a)
                              @if ($i==0|| $i==1)
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
                      @foreach($summary as $key=>$value)

                        @if ($key==0)

                              <td></td>

                          @foreach($value as $field=>$value)
                              <td/>{{ $field }}</td>
                          @endforeach

                        @endif

                      @endforeach
                    </tr>
                </thead>
                <tbody>

                  <?php $i = 0;?>
                  @foreach($summary as $row)

                    <?php $incentive=0;$entitle=0;?>

                      <?php

                          if($row->{'Total Visit'}>=$row->{'KPI'}+4)
                          {
                              $incentive+=$row->Incentive_5/$row->{'Visit Count'}*$row->{'Number of Own Visit'};
                              $entitle=$row->Incentive_5;
                          }
                          elseif($row->{'Total Visit'}>=$row->{'KPI'}+3)
                          {
                              $incentive+=$row->Incentive_4/$row->{'Visit Count'}*$row->{'Number of Own Visit'};
                              $entitle=$row->Incentive_4;
                          }
                          elseif($row->{'Total Visit'}>=$row->{'KPI'}+2)
                          {
                              $incentive+=$row->Incentive_3/$row->{'Visit Count'}*$row->{'Number of Own Visit'};
                              $entitle=$row->Incentive_3;
                          }
                          elseif($row->{'Total Visit'}>=$row->{'KPI'}+1)
                          {
                              $incentive+=$row->Incentive_2/$row->{'Visit Count'}*$row->{'Number of Own Visit'};
                              $entitle=$row->Incentive_2;
                          }

                          else {
                            // code...
                            if($row->{'Visit Count'}==0)
                            {
                              $incentive+=0;
                              $entitle=$row->Incentive_1;
                            }
                            else {
                              // code...
                              $incentive+=$row->Incentive_1/$row->{'Visit Count'}*$row->{'Number of Own Visit'};
                              $entitle=$row->Incentive_1;
                            }

                          }
                      ?>


                      <tr id="row_{{ $i }}">
                            <td></td>

                          @foreach($row as $key=>$value)
                            <td>

                              @if($key=="Incentive")
                                {{ number_format($incentive,2) }}
                              @elseif($key=="Incentive_Entitled")
                                  {{ $entitle }}
                              @else
                                {{ $value }}
                              @endif

                            </td>
                          @endforeach
                      </tr>

                      <?php $i++; ?>

                  @endforeach

                @endif
              </tbody>
            <tfoot></tfoot>
          </table>

        </div>
      </div>



    </section>

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

      $(".select2").select2();

      $('#range').daterangepicker({locale: {
        format: 'DD-MMM-YYYY'
      },startDate: '{{$start}}',
      endDate: '{{$end}}'});

    });

    function refresh()
    {

       var d=$('#range').val();
      var arr = d.split(" - ");
      var includeresigned=$('#includeresigned').is(':checked');
      month=arr[0];
      year=arr[1];
      cl=$('[name="Client"]').val();

      // window.location.href ="{{ url('/incentivesummary') }}/"+month+"/"+year+"/"+cl;
      // console.log("{{ url("/incentivesummary") }}/"+month+"/"+year+"/"+cl);

      if(cl)
      {
        window.location.href ="{{ url("/incentivesummary") }}/"+month+"/"+year+"/"+includeresigned+"/"+cl;
      }
      else {
        window.location.href ="{{ url("/incentivesummary") }}/"+month+"/"+year+"/"+includeresigned;
      }



    }

</script>



@endsection
