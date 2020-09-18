
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
        
        summary = $('#summary').dataTable( {
                         columnDefs: [{ "visible": false, "targets":[1]},{"className": "dt-right", "targets": []}],
                          responsive: false,
                          dom: "Bltp",
                          sScrollX: "100%",
                          sScrollY: "100%",
                          scrollCollapse: true,
                          bAutoWidth: true,
                          iDisplayLength:25,
                        //   rowId:"serviceticket.Id",
                          columns: [
                          { data : null, "render":"", title: "No"},
                          { data: 'inventorypricehistory.Id' , title:"Id"},
                          { data: 'speedfreakinventory.name' , title:"Inventory"},
                          { data: 'speedfreakinventory.barcode' , title:"Barcode"},
                          { data: 'companies.Company_Name' , title:"Supplier"},
                          { data: 'inventorypricehistory.price' , title:"Price (RM)"},
                          { data: 'inventorypricehistory.stockin' , title:"Stock In"},
                          { data: 'inventorypricehistory.stockout' , title:"Stock Out",
                               "render": function ( data, type, full, meta ) {
                                  return '<a href="{{url("replacementhistory")}}/null/null/'+full.inventorypricehistory.Id+'" target="_blank">'+data+'</a>'
                               }
                          },
                          { data: 'user' , title:"Created By"},
                          { data: 'inventorypricehistory.created_at' , title:"Created At"},
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
      Price History
      <small>Speedfreak Inventory Management</small>
      </h1>

      <ol class="breadcrumb">
      <li><a href="{{ url('/home') }}"><i class="fa fa-dashboard"></i> Home</a></li>
      <li><a href="#">Speedfreak Inventory Management</a></li>
      <li class="active">Price History</li>
      </ol>
    </section>

    <section class="content">

      <div class="box">
        <div class="box-body">
            <div class="row">
                <div class="col-md-2">
                    <div class="input-group">
                        <label>Inventory:</label>
                        <select class="select2 form-control" id="inventoryfilter" onchange="Refresh()">
                            <option></option>
                            @foreach($inventory as $inven)
                            <option value="{{$inven->Id}}" <?php if($id == $inven->Id) echo "selected" ?> >{{$inven->barcode}}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
            </div>
            <br>
          <table id="summary" class="summary" cellspacing="0" width="100%" padding="30px" style="font-size: 13px;">
             <thead>
                <tr class="search">
                @foreach($list as $key=>$value)

                    @if ($key==0)
                        <?php $i = 0; ?>
                            @foreach($value as $field=>$a)
                                @if ($i==0)
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
                    @foreach($list as $key=>$value)

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
                @foreach($list as $delivery)

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
    <b>Version</b> 1.0.0
  </div>
  <strong>Copyright &copy; 2014-2016 <a href="http://www.softoya.com">TrackerOnTheGo</a>.</strong> All rights
  reserved.
</footer>

<script>
    $(function () {

      $(".select2").select2();  

    });

    function Refresh()
    {
      window.location.href = "{{url('inventorypricehistory')}}/" + $('#inventoryfilter').val();
    }

</script>



@endsection
