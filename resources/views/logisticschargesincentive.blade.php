
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
      <script type="text/javascript" language="javascript" src="{{ asset('/plugin/datatables/extensions/Select2/editor.select2.js') }}"></script>
       <script type="text/javascript" language="javascript" src="https://cdn.datatables.net/plug-ins/1.10.19/api/sum().js"></script>

      <script type="text/javascript" language="javascript" src="{{ asset('/plugin/js/dataTables.editor.min.js') }}"></script>
      {{-- chart js --}}
      <script type="text/javascript" language="javascript" src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.4.0/Chart.bundle.min.js"></script>

      <script type="text/javascript" language="javascript" class="init">

      var editor;
      var summary;
      $(document).ready(function() {
        // editor = new $.fn.dataTable.Editor( {
        //                         ajax: {url:"{{ asset('/Include/logistics2.php') }}",
        //                              "data": {
        //                               "start": "{{ $start }}",
        //                               "end": "{{ $end }}"
        //                             }
        //                           },

        //                          table: "#summary",
        //                          idSrc: "deliveryform.Id",
        //                          fields: [
        //                                   {
        //                                         label:"Id",
        //                                         name:"deliveryform.Id",
        //                                         type: "hidden"
        //                                   },
        //                                   {
        //                                         label:"Incentive",
        //                                         name:"deliveryform.incentive"
        //                                   }

        //                          ]
        //                  } );
        
        summary = $('#summary').dataTable( {
                         columnDefs: [{ "visible": false, "targets":[1,2]},{"className": "dt-right", "targets": []}],
                          responsive: false,
                          dom: "Bltp",
                          sScrollX: "100%",
                          sScrollY: "100%",
                          scrollCollapse: true,
                          iDisplayLength:100,
                          bAutoWidth: true,
                          iDisplayLength:10,
                          rowId:"deliveryform.Id",
                          fnInitComplete: function(oSettings, json) {
                            var api = this.api();
                            var charges = api.column(12,{search:"applied"}).data().sum();
                            var incentive = api.column(17,{search:"applied"}).data().sum();
                           $("#total").html("RM" + charges.toFixed(2));
                           $("#totalincentive").html("RM" + incentive.toFixed(2));
                          },
                          "drawCallback":  function( settings ) {

                            var api = this.api();
                            var charges = api.column(12,{search:"applied"}).data().sum();
                            var incentive = api.column(17,{search:"applied"}).data().sum();
                           $("#total").html("RM" + charges.toFixed(2));
                           $("#totalincentive").html("RM" + incentive.toFixed(2));
                          }, 
                          // order: [[ 1, "asc" ]],
                          columns: [
                          { data : null, "render":"", title: "No"},
                          { data: 'deliveryform.Id' , title:"deliveryform.Id"},
                          { data: "deliveryform.roadtaxId", title:"roadtax Id"},
                          { data: "deliveryform.delivery_date", title:"Delivery Date"},
                          { data: "users.Name", title:"Driver Name"},
                          { data: "deliveryform.DO_No", title:"DO Number"},
                          { data: "radius.Location_Name", title:"Site"},
                          { data: "projects.Project_Name", title:"Project"},
                          { data: "deliveryform.project_type", title:"Project Type"},
                          { data: "companies.Company_Name", title:"Company"},
                          { data: "deliveryform.distance_km", title:"Distance(KM)"},
                          { data: "deliveryform.charges_rate", title:"Charges Rate"},
                          { data: "deliveryform.charges", title:"Tranpsort Charges"},
                          { data: "deliveryform.incentive_rate", title:"Incentive Rate"},
                          { data: "deliveryform.basicincentive", title:"Basic Incentive"},
                          { data: "deliveryform.ontime", title:"Ontime Incentive"},
                          { data: "deliveryform.incentive", title:"Extra Incentive",
                            "render": function ( data, type, full, meta ) {
                              return '<input type="number" step="0.02" class="form-control" value="'+data+'" id="'+full.deliveryform.Id+'">';
                            }
                          },
                          { data: "deliveryform.totalincentive", title:"Total Incentive (RM)"}
                  ],
                               select: true,
                               buttons: [
                               {
                                  extend: 'collection',
                                  text: 'Export',
                                  buttons: [
                                  'excel',
                                  'csv',
                                  'pdf'
                                  ]
                                },
                               ],
                   });

            $('#summary').on( 'click', 'tbody td', function (e) {
                          var tr = $(this).parent().attr('id');
                          var table = $('#summary').dataTable();
                          var row_index = $(this).closest("tr").index();
                          var col = table.api().column(this)[0][0];
                          var data = table.api().rows().data();
                          $("input").blur(function(){
                              var id = $(this).attr('id');
                              var val = $(this).val();
                              updateincentive(id,val);
                              data.each(function (value, index) {
                                  if(value.deliveryform.Id == id)
                                  {
                                    var incentive = table.api().cell(index,13).data();
                                    var incentive2 = table.api().cell(index,14).data();
                                    // var incentive3 = table.api().cell(index,15).data();
                                    var update = parseFloat(val) + parseFloat(incentive) + parseFloat(incentive2);
                                    table.api().cell(index,16).data(parseFloat(update).toFixed(2));
                                    console.log(totalincentive,update,val);
                                  }
                              });
                          });
                  } );

             summary.api().on( 'order.dt search.dt', function () {
                summary.api().column(0, {search:'applied', order:'applied'}).nodes().each( function (cell, i) {
                    cell.innerHTML = i+1;
                } );
                // console.log(summary.api().column( 7, {page:'current'} ).data().sum() );
                //  console.log(summary.api().column( 8, {page:'current'} ).data().sum() );
            } ).draw();

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
                    } });

        });

      </script>

      @endsection

@section('content')

  <div class="content-wrapper">
      <!-- Content Header (Page header) -->
    <section class="content-header">
      <h1>
      Logistics Charges & Incentive
      <small>Human Resource</small>
      </h1>

      <ol class="breadcrumb">
      <li><a href="{{ url('/home') }}"><i class="fa fa-dashboard"></i> Home</a></li>
      <li><a href="#">Human Resource</a></li>
      <li class="active">Logistics Charges & Incentive</li>
      </ol>
    </section>

    <section class="content">
      <br>
      <div class="row">
        <div class="col-md-12">
          <div class="box">
            <div class="box-body">
               <div class="row">


                  <div class="col-md-2">
                   <div class="input-group">
                     <label>Date:</label>
                     <input type="text" class="form-control" id="range" name="range">

                   </div>
                 </div>

                 <!--<div class="col-md-2">
                   <div class="input-group">
                    <label>Company:</label>

                     <select class="select2 form-control" id="company" name="company">
                      <option value=""></option>
                      @foreach ($company as $c)
                      <option value="{{$c->Id}}"   >{{$c->Company_Name}}</option>
                      @endforeach
                     </select>

                   </div>
                 </div>

                  <div class="col-md-2">
                   <div class="input-group">
                    <label>Project Type:</label>
                     <select class="select2 form-control" id="projecttype" name="projecttype">
                      <option value=""></option>
                      @foreach($projecttype as $pt)
                      <option value="{{$pt->type}}"  >{{$pt->type}}</option>
                      @endforeach
                     </select>
                   </div>
                 </div>-->

                 <div class="col-md-2">
                  <br>
                     <div class="input-group">
                       <button type="button" class="btn btn-success btn" data-toggle="modal" onclick="refresh();">Refresh</button>
                     </div>
                 </div>

               </div>
               <div class="row">
                <div class="col-md-3 total">
                   <h4 class="" >Total Charges : <i><span id='total'>RM0.00</span></i></h4>
                 </div>
                 <div class="col-md-3 total">
                   <h4 class="" >Total Incentive : <i><span id='totalincentive'>RM0.00</span></i></h4>
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
                <tr class="search">
                @foreach($summary as $key=>$value)

                    @if ($key==0)
                        <?php $i = 0; ?>
                            @foreach($value as $field=>$a)
                                @if ($i==0 || $i==1)
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
                        <td>
                            {{ $field }}
                        </td>
                        @endforeach

                        @endif

                    @endforeach
                </tr>
            </thead>
            <tbody>
                <?php $i = 0; ?>
                @foreach($summary as $delivery)

                <tr>
                    <td></td>

                    @foreach($delivery as $key=>$value)
                      <td>
                        {{ $value }}
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
      var projecttype = $('#projecttype').val();
      var company = $('#company').val();
      console.log(projecttype,company)

      if((projecttype == null || projecttype == "") && (company == null || company == ""))
      {
        window.location.href ="{{ url("/logisticschargesincentive") }}/"+arr[0]+"/"+arr[1];
      }
      else if((company == null || company == ""))
      {
        window.location.href ="{{ url("/logisticschargesincentive") }}/"+arr[0]+"/"+arr[1]+"/"+projecttype;
      }
      else if((projecttype == null || projecttype == ""))
      {
        window.location.href ="{{ url("/logisticschargesincentive") }}/"+arr[0]+"/"+arr[1]+"/"+ null +"/"+company;
      }
    }

    function updateincentive(id,value)
    {
      $.ajaxSetup({
           headers: { 'X-CSRF-Token' : $('meta[name=_token]').attr('content') }
          });
          $.ajax({
                  url: "{{ url('/transportcharges/updateincentive') }}" + "/" + id,
                  method: "POST",
                  data: {value:value},
                  success: function(response){
                    if (response==1)
                    {

                    }
                    else
                    {

                    }
                  }
                });
    }


</script>



@endsection
