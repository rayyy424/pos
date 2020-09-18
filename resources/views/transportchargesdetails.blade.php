
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
      var summary2;
      var summary1;
      var editor;

      $(document).ready(function() {

          summary = $('#summary').dataTable( {
                           columnDefs: [{ "visible": false, "targets":[1]},{"className": "dt-right", "targets": [-1]}],
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
                            "drawCallback":  function( settings ) {
                          
                            var api = this.api();
                            var list = api.column(10).data().toArray();
                            for(var i=0, len=list.length; i<len; i++){
                                list[i] = parseInt(list[i], 10);
                            }
                            var maxnum = Math.max.apply(Math,list);
                            var rowcount = $('#summary tr').length-2;
                            var real = Math.round((maxnum/rowcount)*100)/100
                            api.rows().every( function () {
                               var d = this.data();
                                  if (d.deliverylocation.driverincentive < maxnum)
                                  {
                                    if(d.deliveryform.trip.match("1 Way Trip") != null)
                                    {
                                       // d.deliverylocation.driverincentive = 
                                       this.cell(this,10).data(maxnum);
                                       console.log("here")
                                    }
                                    else if (d.deliveryform.trip.match("2 Way Trip") != null)
                                    { 
                                       console.log("do ntg")
                                    }
                                    else
                                    {
                                      if(d.deliverylocation.area.match("Outstation") != null)
                                      {
                                         console.log(real)
                                         this.cell(this,10).data(maxnum);
                                      }
                                    }
                                  }
                            });                      
                            },
                            columns: [
                            { data : null, "render":"", title: "No"},
                            { data: "deliveryform.Id", title:"Form Id"},
                            { data: "deliveryform.delivery_date", title:"Delivery Date"},
                            { data: "users.Name", title:"Driver Name"},
                            { data: "deliveryform.DO_No", title:"DO Number"},
                            { data: "radius.Location_Name", title:"Site"},
                            { data: "companies.Company_Name", title:"Company"},
                            { data: "deliveryform.trip", title:"Trip Type"},
                            { data: "deliverylocation.area", title:"Area"},
                            { data: "roadtax.Lorry_Size", title:"Lorry Size"},
                            { data: "deliverylocation.driverincentive",title:"Total Charges (RM)",
                            "render": function ( data, type, full, meta ) {
                                        var charges = parseFloat(data).toFixed(2);
                                        var rowcount = $('#summary tr').length-2;
                                        var real = Math.round((charges/rowcount)*100)/100;
                                        if(full.deliveryform.trip !== "")
                                        {
                                          if(full.deliveryform.trip.indexOf("1 Way Trip") !== -1)
                                          {
                                           real = real;
                                          }
                                          else if(full.deliveryform.trip.indexOf("2 Way Trip") !== -1)
                                          {
                                            real = charges;
                                          }
                                        }
                                        else
                                        {
                                          if(full.deliverylocation.area.indexOf("Zone") !== -1)
                                          {
                                            real = charges;
                                          }
                                          else if (full.deliverylocation.area.indexOf("Outstation") !== -1)
                                          {
                                            real = real;
                                          }
                                        }

                                        return real;
                            }
                          }

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

            $.fn.dataTable.tables( {visible: true, api: true} ).columns.adjust();

        });

      </script>

      @endsection

@section('content')

  <div class="content-wrapper">
      <!-- Content Header (Page header) -->
    <section class="content-header">
      <h1>
      Transport Charges Summary
      <small>Human Resource</small>
      </h1>

      <ol class="breadcrumb">
      <li><a href="{{ url('/home') }}"><i class="fa fa-dashboard"></i> Home</a></li>
      <li><a href="#">Human Resource</a></li>
      <li class="active">Transport Charges Summary</li>
      </ol>
    </section>

    <section class="content">
      <br>
          <div class="box">
            <div class="box-body">

          <table id="summary" class="summary" cellspacing="0" width="100%" padding="30px" style="font-size: 13px;">
             <thead>
                                <tr class="search">
                                @foreach($details as $key=>$value)

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
                                    @foreach($details as $key=>$value)

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
                                <?php $i = 1; ?>
                                @foreach($details as $delivery)

                                <tr id="{{ $i }}">
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

</script>



@endsection
