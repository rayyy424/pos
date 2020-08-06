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

      .modal-success{
        z-index: 9999999;
      }

      .modal-danger{
        z-index: 9999999;
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
      <script type="text/javascript" language="javascript" src="https://cdn.datatables.net/plug-ins/1.10.13/api/sum().js"></script>


      <script type="text/javascript" language="javascript" src="{{ asset('/plugin/js/dataTables.editor.min.js') }}"></script>

      {{-- chart js --}}
      <script type="text/javascript" language="javascript" src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.5.0/Chart.bundle.min.js"></script>

      <script type="text/javascript">
        var oTable;
        $(document).ready(function() {
           oTable=$('#oTable').dataTable({
                          columnDefs: [{ "visible": false, "targets":[1,2]},{"className": "dt-right", "targets": [-2,-3]},{"className": "dt-center", "targets": []}],
                            responsive: false,
                            dom: "Bltp",
                            sScrollX: "100%",
                            sScrollY: "100%",
                            scrollCollapse: true,
                            bAutoWidth: true,
                            iDisplayLength:10,
                            columns: [
                            { data : null, "render":"", title: "No"},
                            { data : 'creditnote.Id' , title: "Id"},
                            { data : 'soId' , title: "soId"},
                            { data : 'creditnote.cn_no' , title: "CN #"},
                            { data : 'creditnote.date' , title: "CN Date"},
                            { data : 'salesorder.invoice_number' , title: "Invoice #"},
                            { data : 'salesorder.invoice_date' , title: "Invoice Date"},
                            { data : 'companies.Company_Name' , title: "Company"},
                            { data : 'client' , title: "Client"},
                            { data : 'salesorder.total_amount' , title: "Invoice Amount(RM)"},
                            { data : 'creditnote.amount' , title: "CN Amount(RM)"},
                            { data : null, title: "Action",
                                "render": function ( data, type, full, meta ) {
                                    return '<a href="{{url("creditnotedetail")}}/'+full.soId+'" target="_blank"><button class="btn btn-default btn-xs" title="Detail" style="width:unset"><i class="fa fa-edit"></i></button></a>' + '<a href="{{url("creditnotetemplate")}}/'+full.creditnote.Id+'" target="_blank"><button class="btn btn-default btn-xs" title="Credit Note" style="width:unset"><i class="fa fa-file"></i></button></a>' ;
                                }
                            }
                             ],
                                 select: true,
                                 buttons: [
                                    {
                                        extend: 'excelHtml5',
                                        exportOptions: {
                                            columns: ':visible'
                                        }
                                    },
                                 ],
                     });

                          oTable.api().on( 'order.dt search.dt', function () {
                          oTable.api().column(0, {search:'applied', order:'applied'}).nodes().each( function (cell, i) {
                              cell.innerHTML = i+1;
                          } );
                          } ).draw();

                  $(".display thead input").keyup ( function () {

                    /* Filter on the column (the index) of this element */
                    if ($('#oTable').length > 0)
                    {

                        var colnum=document.getElementById('oTable').rows[0].cells.length;

                        if (this.value=="[empty]")
                        {

                           oTable.fnFilter( '^$', this.name,true,false );
                        }
                        else if (this.value=="[nonempty]")
                        {

                           oTable.fnFilter( '^(?=\\s*\\S).*$', this.name,true,false );
                        }
                        else if (this.value.startsWith("!")==true && this.value.length>1)
                        {

                           oTable.fnFilter( '^(?'+ this.value +').*', this.name,true,false );
                        }
                        else if (this.value.startsWith("!")==false)
                        {

                            oTable.fnFilter( this.value, this.name,true,false );
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
    <h1>Credit Note Lists<small>Credit Note Lists</small></h1>

    <ol class="breadcrumb">
      <li><a href="{{ url('/home') }}"><i class="fa fa-dashboard"></i>Home</a></li>
      <li><a href="#">Credit Note Lists</a></li>
      <li class="active">Credit Note Lists</li>
    </ol>
  </section>

  <br>

  <section class="content">
    <div class="row">
       <div class="modal modal-danger fade" id="error-alert">
            <div class="modal-dialog">
              <div class="modal-content">
                <div class="modal-header">
                  <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span></button>
                  <h4 class="modal-title">Alert!</h4>
                </div>
                <div class="modal-body">
                <ul></ul>
                </div>
                <div class="modal-footer">
                  <button type="button" class="btn btn-outline" data-dismiss="modal">Close</button>
                </div>
              </div>
            </div>
      </div>

      <div class="modal modal-success fade" id="update-alert">
            <div class="modal-dialog">
              <div class="modal-content">
                <div class="modal-header">
                  <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span></button>
                  <h4 class="modal-title">Alert!</h4>
                </div>
                <div class="modal-body">
                <ul></ul>
                </div>
                <div class="modal-footer">
                  <button type="button" class="btn btn-outline" data-dismiss="modal">Close</button>
                </div>
              </div>
            </div>
      </div>

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
        <br>
    <!-- <div class="row">
        <div class="col-md-2">
          <a class="form-group" href="{{route('ARInvoiceExcel.excel')}}" title="Export"><button><i class="fa fa-upload" aria-hidden="true"></i>  Export</button></a>
        </div>
          <form action="{{ url('ARInvoiceExcel/import') }}" method="POST" enctype="multipart/form-data">
            {{ csrf_field() }}
            <div class="row">
              <div class="col-md-4">
                <input type="file" name="file">
                <button id="import" title="Import"><i class="fa fa-download" aria-hidden="true"></i>  Import</button>
              </div>
          </form>
      </div>
    </div> -->

      <table id="oTable" class="display" cellspacing="0" width="100%" padding="30px" style="font-size: 13px;">
                       <thead>
                                <tr class="search">
                                @foreach($details as $key=>$value)

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
                                    @foreach($details as $key=>$value)

                                        @if ($key==0)
                                        <td></td>

                                        @foreach($value as $field=>$value)
                                        <td>
                                            {{ $field }}
                                        </td>
                                        @endforeach
                                        <td></td>
                                        @endif

                                    @endforeach
                                </tr>
                            </thead>
                            <tbody>
                                <?php $i = 0; ?>
                                @foreach($details as $delivery)

                                <tr>
                                    <td></td>      
                                    @foreach($delivery as $key=>$value)
                                      <td>
                                        {{ $value }}
                                      </td>
                                    @endforeach
                                    <td></td>
                                </tr>
                                <?php $i++; ?>
                                @endforeach
                            </tbody>
                            <tfoot></tfoot>
                        </table>
  </section>

  <footer class="main-footer">
    <div class="pull-right hidden-xs">
      <b>Version</b> 2.0.1
    </div>
    <strong>Copyright &copy; 2014-2016 <a href="http://www.softoya.com">TrackerOnTheGo</a>.</strong> All rights reserved.
  </footer>
</div>

<script type="text/javascript">
$(function(){
    $('#range').daterangepicker({locale: {
      format: 'DD-MMM-YYYY'
      }
      @if($start && $end)
      ,startDate: '{{$start}}',
      endDate: '{{$end}}'
      @endif
    });
});

function refresh()
{
    var d=$('#range').val();
    var arr = d.split(" - ");

    window.location.href ="{{ url('/creditnotelist') }}/"+arr[0]+"/"+arr[1];
}
</script>

@endsection