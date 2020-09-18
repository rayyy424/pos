
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

      var summary;
      var editor;

      $(document).ready(function() {

         editor = new $.fn.dataTable.Editor( {
                                ajax: {url:"{{ asset('/Include/driverincentivesummary.php') }}", "data": {
                                      "Start": "{{ $start }}",
                                      "End": "{{ $end }}"
                                    }
                                  },
                                
                                 table: "#summary",
                                 idSrc: "deliveryform.Id",
                                 fields: [
                                         // {
                                         //        label:"Id",
                                         //        name:"deliveryform.Id",
                                         //        type: "hidden"
                                         //  },
                                          {
                                                label:"Incentive",
                                                name:"deliveryform.incentive"
                                          }

                                 ]
                         } );

          summary = $('#summary').dataTable( {
                                ajax: {url:"{{ asset('/Include/driverincentivesummary.php') }}", "data": {
                                      "Start": "{{ $start }}",
                                      "End": "{{ $end }}"
                                    }
                                  },

                           columnDefs: [{ "visible": false, "targets":[1,-1,-2,-3,-4]},{"className": "dt-center", "targets": "_all"}],
                            responsive: false,
                            dom: "Bltp",
                            sScrollX: "100%",
                            sScrollY: "100%",
                            scrollCollapse: true,
                            iDisplayLength:100,
                            bAutoWidth: true,
                            iDisplayLength:10,
                            rowId:"deliveryform.Id",
                            order: [[ 2, "desc" ],[ 3, "desc" ]],
                            fnInitComplete: function(oSettings, json) {

                            var sum = 0.0;
                            var extra = 0.0;
                            var total = 0.0;
                            var pageTotal = 0.0;

                          //   var intVal = function ( i ) {
                          //     return typeof i === 'string' ?
                          //         i.replace(/[\$,]/g, '')*1 :
                          //         typeof i === 'number' ?
                          //             i : 0;
                          // };

                          //   pageTotal = summary.api().column( 7, { filter:'applied'} )
                          //   .data()
                          //   .reduce( function (a, b) {
                          //       return intVal(a) + intVal(b);
                          //   }, 0 );
                          //   console.log(pageTotal)

                         extra = summary.api().column(8).data().sum();
                         summary.api().rows().every( function () {
                         var d = this.data();
                         var type= d.roadtax.Lorry_Size;
                         // console.log(d)  
                         if(type.includes("10 Tan Crane")==true)
                         {  
                            if(d.deliverylocation.price_10ton_crane != null){
                           sum=sum+parseFloat(d.deliverylocation.price_10ton_crane);
                            }
                            else
                            {
                              sum = sum + 0;
                            }
                         }
                         else if(type.includes("10 Tan")==true)
                         {
                          if(d.deliverylocation.price_10ton != null){
                           sum=sum+parseFloat(d.deliverylocation.price_10ton);
                            }
                            else
                            {
                              sum = sum + 0;
                            }
                         }
                         else if(type.includes("5 Tan Crane")==true)
                         {
                          if(d.deliverylocation.price_5ton_crane != null){
                           sum=sum+parseFloat(d.deliverylocation.price_5ton);
                            }
                            else
                            {
                              sum = sum + 0;
                            }
                         }
                         else
                         {
                          if(d.deliverylocation.price_2ton_to_5ton != null){
                           sum=sum+parseFloat(d.deliverylocation.price_2ton_to_5ton);
                            }
                            else
                            {
                              sum = sum + 0;
                            }
                         }
                       } );
                         // console.log(sum,extra)
                         total = sum + extra;
                         $("#total").html("RM" + total.toFixed(2));

                    },
                        "drawCallback": function( settings ) {
                          
                          var api = this.api();
                          extra = 0.0;
                          sum = 0.0;
                          total = 0.0;
                          extra = api.column(8,{search:"applied"}).data().sum();
                          api.cells( function ( index, data, node ) { 
                         if(data.roadtax.Lorry_Size == "10 Tan Crane")
                         {  
                            if(data.deliverylocation.price_10ton_crane != null){
                           sum=sum+parseFloat(data.deliverylocation.price_10ton_crane);
                            }
                            else
                            {
                              sum = sum + 0;
                            }
                         }
                         else if(data.roadtax.Lorry_Size =="10 Tan")
                         {
                          if(data.deliverylocation.price_10ton != null){
                           sum=sum+parseFloat(data.deliverylocation.price_10ton);
                            }
                            else
                            {
                              sum = sum + 0;
                            }
                         }
                         else if(data.roadtax.Lorry_Size =="5 Tan Crane")
                         {
                          if(data.deliverylocation.price_5ton_crane != null){
                           sum=sum+parseFloat(data.deliverylocation.price_5ton);
                            }
                            else
                            {
                              sum = sum + 0;
                            }
                         }
                         else
                         {
                          if(data.deliverylocation.price_2ton_to_5ton != null){
                           sum=sum+parseFloat(data.deliverylocation.price_2ton_to_5ton);
                            }
                            else
                            {
                              sum = sum + 0;
                            }
                         }  
                          }, 0, { search: 'applied' } )
                          .data()
                          .sum();
                          total = sum + extra;
                         $("#total").html("RM" + total.toFixed(2));
                        },
                            columns: [
                            { data : null, "render":"", title: "No"},
                            { data: "deliveryform.Id", title:"Form Id"},
                            { data: "users.Name", title:"Driver Name"},
                            { data: "deliveryform.DO_No", title:"DO Number"},
                            { data: "radius.Location_Name", title:"Site"},
                            { data: "deliverylocation.area", title:"Area"},
                            { data: "roadtax.Lorry_Size", title:"Lorry Size"},
                            { data: "Temp", name: "driverincentive",
                                "render": function ( data, type, full, meta ) {
                                
                                if (full.Lorry_Size == "10 Tan Crane") {
                                  return full.deliverylocation.price_10ton_crane; 
                                } else if (full.Lorry_Size == "10 Tan") {
                                  return full.deliverylocation.price_10ton; 
                                } else if (full.Lorry_Size == "5 Tan Crane") {
                                  return full.deliverylocation.price_5ton_crane; 
                                } else {
                                  return full.deliverylocation.price_2ton_to_5ton; 
                                }                          
                            },title:"Incentive (RM) "},
                            { data: "deliveryform.incentive", title:"Extra Incentive (RM)"},
                            { data: "deliverylocation.price_10ton_crane"},
                            { data: "deliverylocation.price_10ton"},
                            { data: "deliverylocation.price_5ton_crane"},
                            { data: "deliverylocation.price_2ton_to_5ton"},
                            

                    ],
                                 select: true,
                                 buttons: [
                                 ],
                     });

             $('#summary').on( 'click', 'tbody td', function (e) {
                        editor.inline( this, {
                          onBlur: 'submit'
                        } );
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
      Driver Incentive Summary
      <small>Human Resource</small>
      </h1>

      <ol class="breadcrumb">
      <li><a href="{{ url('/home') }}"><i class="fa fa-dashboard"></i> Home</a></li>
      <li><a href="#">Human Resource</a></li>
      <li class="active">Driver Incentive Summary</li>
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

                                <tr id="row_{{ $i }}">
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
        window.location.href ="{{ url("/driverincentivesummary") }}/"+arr[0]+"/"+arr[1];
    }


</script>



@endsection
