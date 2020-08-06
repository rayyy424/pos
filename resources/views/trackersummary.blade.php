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

      div.DTE_Body div.DTE_Body_Content div.DTE_Field {
            padding: 15px;
      }

    .modal-dialog-wide{
      width: 80%; /* respsonsive width */
      position: absolute;
  float: left;
  left: 50%;
  top: 30%;
  transform: translate(-50%, -150px); */
  }

  table.dataTable thead .all{
    background-color: #27ae60;
  }

  table.dataTable thead .osu{
    background-color: #e74c3c;
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

      <script type="text/javascript" language="javascript" class="init">

      var editor;
      var editor1;
      var summary;

      $(document).ready(function() {

                 summary = $('#summary').dataTable( {
                     responsive: false,
                     colReorder: false,
                     sScrollX: "100%",
                     bAutoWidth: true,
                     rowId: 'users.Id',
                     sScrollY: "100%",
                     dom: "Bfrtp",
                     bScrollCollapse: true,

                    //  rowId:"userability.Id",
                     //aaSorting:false,
                     //8,9,10,11,12,13,14,15
                     @if($me->View_Costing_Summary && $me->View_OSU_Costing_Summary)
                      columnDefs: [{ "visible": false, "targets": [1] },{"className": "dt-left", "targets": [0,1,2,3]}],
                     @elseif($me->View_Costing_Summary && !$me->View_OSU_Costing_Summary)
                      columnDefs: [{ "visible": false, "targets": [1,8,9,10,11,12,13,14,15,16,17,18,19] },{"className": "dt-left", "targets": [0,1,2,3]}],
                     @elseif(!$me->View_Costing_Summary && $me->View_OSU_Costing_Summary)
                      columnDefs: [{ "visible": false, "targets": [1,4,5,6,7] },{"className": "dt-left", "targets": [0,1,2,3]}],
                     @else
                      columnDefs: [{ "visible": false, "targets": [1,4,5,6,7,8,9,10,11,12,13,14,15,16,17,18,19] },{"className": "dt-left", "targets": [0,1,2,3]}],
                     @endif

                     columns: [
                       {  data: null, "render":"", title:"No"},

                       { data: "Id",title:"Id"},
                        { data: "Unique ID",title:"Unique ID"},
                        { data: "Site Name",title:"Site Name"},

                        { data: "MR_Budget",title:"MR_Budget",className:"all"},
                        { data: "BOQ Approved Amount",title:"BOQ Approved Amount",className:"all"},

                        { data: "BOQ Approved VS Budget Costing",title:"BOQ Approved VS Budget Costing",className:"all"},

                        { data: "PO Value (RM)",title:"PO Value (RM)",className:"all"},


                        { data: "Invoice Amount",title:"Invoice Amount",className:"osu"},
                        { data: "Actual Costing",title:"Actual Costing",className:"osu"},

                        { data: "All Costing",title:"All Costing",className:"osu"},
                        { data: "E-Wallet",title:"E-Wallet",className:"osu"},
                        { data: "Incentive",title:"Incentive",className:"osu"},
                        { data: "Manday",title:"Manday",className:"osu"},

                        { data: "PO VS Budget Costing",title:"PO VS Budget Costing",className:"osu"},
                        { data: "PO VS Actual Costing",title:"PO VS Actual Costing",className:"osu"},

                        { data: "Invoice VS Budget Costing",title:"Invoice VS Budget Costing",className:"osu"},
                        { data: "Invoice VS Actual Costing",title:"Invoice VS Actual Costing",className:"osu"},

                        { data: "PO VS All Costing",title:"PO VS All Costing",className:"osu"},

                        { data: "Invoice VS All Costing",title:"Invoice VS All Costing",className:"osu"},

                     ],

                     select: {
                             style:    'os',
                             selector: 'td'
                     },
                     autoFill: {
                        editor:  editor
                    },
                    buttons: [

                            {
                                    extend: 'collection',
                                    text: 'Export',
                                    buttons: [
                                            'excel',
                                            'csv',
                                            'pdf'
                                    ]
                            }
                    ],



                });

                summary.api().on( 'order.dt search.dt', function () {
                  summary.api().column(0, {search:'applied', order:'applied'}).nodes().each( function (cell, i) {
                      cell.innerHTML = i+1;
                  } );
                } ).draw();


              $(".summary thead input").keyup ( function () {

                      /* Filter on the column (the index) of this element */
                  if ($('#summary').length > 0)
                  {

                      var colnum=document.getElementById('summary').rows[0].cells.length;

                      if (this.value=="[empty]")
                      {

                         summary.fnFilter( '^$', $("thead input").index(this)-colnum,true,false );
                      }
                      else if (this.value=="[nonempty]")
                      {

                         summary.fnFilter( '^(?=\\s*\\S).*$', $("thead input").index(this)-colnum,true,false );
                      }
                      else if (this.value.startsWith("!")==true && this.value.length>1)
                      {

                         summary.fnFilter( '^(?'+ this.value +').*', $("thead input").index(this)-colnum,true,false );
                      }
                      else if (this.value.startsWith("!")==false)
                      {

                        summary.fnFilter( this.value, $("thead input").index(this)-colnum,true,false );
                      }
                  }


              } );

          } );

      </script>

      @endsection

@section('content')

  <div class="content-wrapper">
      <!-- Content Header (Page header) -->
    <section class="content-header">
      <h1>
      Project Costing Summary
      <small>Project Management</small>
      </h1>

      <ol class="breadcrumb">
      <li><a href="{{ url('/home') }}"><i class="fa fa-dashboard"></i> Home</a></li>
      <li><a href="#">Project Management</a></li>
      <li class="active">Tracker Summary</li>
      </ol>
    </section>

    <section class="content">

      <div class="row">
        <div class="box box-primary">

          <div class="row">
            <div class="box-body">

             <div class="box-body">
               <div class="form-group">

                 <div class="form-group">
                   <label>Project Name : </label>
                  <select class="form-control select2" id="ProjectId" name="ProjectId" style="width: 30%;">
                    <option></option>

                    @foreach ($projects as $project)
                      @if(!str_contains($project->Project_Name,"DEPARTMENT") &&
                      !str_contains($project->Project_Name,"REIMBURSEMENT")
                      )
                       <option value="{{$project->Id}}" <?php if($project->Id==$projectid) echo "selected";?>>{{$project->Project_Name}}</option>
                      @endif
                    @endforeach
                  </select>
                </div>

               </div>

           </div>
         </div>
       </div>

          <div class="box-body">
            <div class="col-md-12">

            <table id="summary" class="summary" cellspacing="0" width="100%" padding="30px" style="font-size: 13px;">
                <thead>
                  <tr class="search">
                    <td align='center'><input type='hidden' class='search_init' /></td>
                    @foreach($summary as $key=>$values)
                      @if ($key==0)

                      @foreach($values as $field=>$value)

                          <td align='center'><input type='text' class='search_init' /></td>

                      @endforeach

                      @endif

                    @endforeach
                  </tr>
                    <tr>
                      @foreach($summary as $key=>$value)

                        @if ($key==0)
                              <th></th>
                          @foreach($value as $field=>$value)
                              <th/>{{ $field }}</th>
                          @endforeach

                        @endif

                      @endforeach
                    </tr>


                </thead>
                <tbody>

                    <?php $i = 0; ?>
                    @foreach($summary as $sum)

                          <tr id="row_{{ $i }}" >
                              <td></td>
                              @foreach($sum as $key=>$value)
                                <td>
                                  @if($key=="PO VS Budget Costing")
                                    @if($sum->{'PO Value (RM)'}>0 && $sum->{'MR_Budget'}>0)
                                      {{ number_format((str_replace(",","",$sum->{'PO Value (RM)'})-str_replace(",","",$sum->{'MR_Budget'}))/str_replace(",","",$sum->{'PO Value (RM)'}) *100,2) }}%
                                    @else
                                      -
                                    @endif
                                  @elseif($key=="All Costing")
                                    @if(($sum->{'Actual Costing'}>0 || $sum->{'Manday'}>0 || $sum->{'Incentive'}>0 || $sum->{'E-Wallet'}>0 ))
                                      {{ number_format(str_replace(",","",$sum->{'Actual Costing'}) + str_replace(",","",$sum->{'Manday'}) + str_replace(",","",$sum->{'Incentive'}) + str_replace(",","",$sum->{'E-Wallet'}),2) }}
                                    @else
                                      -
                                    @endif
                                  @elseif($key=="PO VS Actual Costing")
                                    @if($sum->{'PO Value (RM)'}>0 && $sum->{'Actual Costing'}>0)
                                      {{ number_format((str_replace(",","",$sum->{'PO Value (RM)'})-str_replace(",","",$sum->{'Actual Costing'}))/str_replace(",","",$sum->{'PO Value (RM)'}) *100,2) }}%
                                    @else
                                      -
                                    @endif
                                  @elseif($key=="PO VS All Costing")
                                    @if($sum->{'PO Value (RM)'}>0 && ($sum->{'Actual Costing'}>0 || $sum->{'Manday'}>0 || $sum->{'Incentive'}>0 || $sum->{'E-Wallet'}>0 ))
                                      {{ number_format((str_replace(",","",$sum->{'PO Value (RM)'})-(str_replace(",","",$sum->{'Actual Costing'}) + str_replace(",","",$sum->{'Manday'}) + str_replace(",","",$sum->{'Incentive'}) + str_replace(",","",$sum->{'E-Wallet'})))/str_replace(",","",$sum->{'PO Value (RM)'}) *100,2) }}%
                                    @else
                                      -
                                    @endif
                                  @elseif($key=="Invoice VS Actual Costing")
                                    @if($sum->{'Invoice Amount'}>0 && $sum->{'Actual Costing'}>0)
                                      {{ number_format((str_replace(",","",$sum->{'Invoice Amount'})-str_replace(",","",$sum->{'Actual Costing'}))/str_replace(",","",$sum->{'Invoice Amount'}) *100,2) }}%
                                    @else
                                      -
                                    @endif
                                    @elseif($key=="Invoice VS All Costing")
                                      @if($sum->{'Invoice Amount'}>0 && ($sum->{'Actual Costing'}>0 || $sum->{'Manday'}>0 || $sum->{'Incentive'}>0 || $sum->{'E-Wallet'}>0 ))
                                        {{ number_format((str_replace(",","",$sum->{'Invoice Amount'})-(str_replace(",","",$sum->{'Actual Costing'}) + str_replace(",","",$sum->{'Manday'}) + str_replace(",","",$sum->{'Incentive'}) + str_replace(",","",$sum->{'E-Wallet'})))/str_replace(",","",$sum->{'Invoice Amount'}) *100,2) }}%
                                      @else
                                        -
                                      @endif
                                  @elseif($key=="BOQ Approved VS Budget Costing")
                                    @if($sum->{'BOQ Approved Amount'}>0 && $sum->{'MR_Budget'}>0)
                                      {{ number_format((str_replace(",","",$sum->{'BOQ Approved Amount'})-str_replace(",","",$sum->{'MR_Budget'}))/str_replace(",","",$sum->{'BOQ Approved Amount'}) *100,2) }}%
                                    @else
                                      -
                                    @endif

                                  @elseif($key=="Invoice VS Budget Costing")
                                    @if($sum->{'Invoice Amount'}>0 && $sum->{'MR_Budget'}>0)
                                      {{ number_format((str_replace(",","",$sum->{'Invoice Amount'})-str_replace(",","",$sum->{'MR_Budget'}))/str_replace(",","",$sum->{'Invoice Amount'}) *100,2) }}%
                                    @else
                                      -
                                    @endif

                                  @else
                                    {{ $value }}
                                  @endif
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
    </section>

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

});

$('#ProjectId').on('change', function() {

  var d=$('#ProjectId').val();

  window.location.href ="{{ url("/trackersummary") }}/"+d;

});

</script>



@endsection
