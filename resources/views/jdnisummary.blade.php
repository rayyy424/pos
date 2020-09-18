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
    <!-- <script type="text/javascript" language="javascript" src="https://cdn.datatables.net/plug-ins/1.10.19/api/sum().js"></script> -->


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

      .details{
        border: none;
        outline:none;
        background-color:#03fcdf;
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
      <script type="text/javascript" language="javascript" src="https://cdn.datatables.net/plug-ins/1.10.19/api/sum().js"></script>


      <script type="text/javascript" language="javascript" src="{{ asset('/plugin/js/dataTables.editor.min.js') }}"></script>

      {{-- chart js --}}
      <script type="text/javascript" language="javascript" src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.5.0/Chart.bundle.min.js"></script>

      <script type="text/javascript">
        var oTable1;
        $(document).ready(function() {

                   oTable1=$('#summarytable').dataTable({
                          columnDefs: [{ "visible": false, "targets":[1]},{"className": "dt-center", "targets": "_all"}],
                            responsive: false,
                            dom: "Bltp",
                            sScrollX: "100%",
                            sScrollY: "100%",
                            scrollCollapse: true,
                            iDisplayLength:100,
                            bAutoWidth: true,
                            iDisplayLength:10,
                            // rowId:"tracker.Id",
                             "footerCallback": function ( row, data, start, end, display ) {
                                    var api = this.api(), data;

                                    var intVal = function ( i ) {
                                      return typeof i === 'string' ?
                                          i.replace(/[\$,]/g, '')*1 :
                                          typeof i === 'number' ?
                                              i : 0;
                                    };

                                    var a = api.column(5).data().reduce( function (a, b) {
                                                return intVal(a) + intVal(b);
                                                }, 0 );
                                    var b = api.column(6).data().reduce( function (a, b) {
                                                return intVal(a) + intVal(b);
                                                }, 0 );
                                    var c = api.column(7).data().reduce( function (a, b) {
                                                return intVal(a) + intVal(b);
                                                }, 0 );
                                    var d = api.column(8).data().reduce( function (a, b) {
                                                return intVal(a) + intVal(b);
                                                }, 0 );
                                    var e = api.column(9).data().reduce( function (a, b) {
                                                return intVal(a) + intVal(b);
                                                }, 0 );
                                    var f = api.column(10).data().reduce( function (a, b) {
                                                return intVal(a) + intVal(b);
                                                }, 0 );
                                    var g = api.column(11).data().reduce( function (a, b) {
                                                return intVal(a) + intVal(b);
                                                }, 0 );
                                    var h = api.column(12).data().reduce( function (a, b) {
                                                return intVal(a) + intVal(b);
                                                }, 0 );
                                    var i = api.column(13).data().reduce( function (a, b) {
                                                return intVal(a) + intVal(b);
                                                }, 0 );
                                    var j = api.column(14).data().reduce( function (a, b) {
                                                return intVal(a) + intVal(b);
                                                }, 0 );
                                    var k = api.column(15).data().reduce( function (a, b) {
                                                return intVal(a) + intVal(b);
                                                }, 0 );
                                    var l = api.column(16).data().reduce( function (a, b) {
                                                return intVal(a) + intVal(b);
                                                }, 0 );
                                    // var m = api.column(14).data().reduce( function (a, b) {
                                    //             return intVal(a) + intVal(b);
                                    //             }, 0 );
                                    $( api.column( 4 ).footer() ).html('<b>Total Per Month(RM)</b>');
                                    $( api.column( 5 ).footer() ).html('<b>'+formatNumber(parseFloat(a).toFixed(2))+'</b>');
                                    $( api.column( 6 ).footer() ).html('<b>'+formatNumber(parseFloat(b).toFixed(2))+'</b>');
                                    $( api.column( 7 ).footer() ).html('<b>'+formatNumber(parseFloat(c).toFixed(2))+'</b>');
                                    $( api.column( 8 ).footer() ).html('<b>'+formatNumber(parseFloat(d).toFixed(2))+'</b>');
                                    $( api.column( 9 ).footer() ).html('<b>'+formatNumber(parseFloat(e).toFixed(2))+'</b>');
                                    $( api.column( 10 ).footer() ).html('<b>'+formatNumber(parseFloat(f).toFixed(2))+'</b>');
                                    $( api.column( 11 ).footer() ).html('<b>'+formatNumber(parseFloat(g).toFixed(2))+'</b>');
                                    $( api.column( 12 ).footer() ).html('<b>'+formatNumber(parseFloat(h).toFixed(2))+'</b>');
                                    $( api.column( 13 ).footer() ).html('<b>'+formatNumber(parseFloat(i).toFixed(2))+'</b>');
                                    $( api.column( 14 ).footer() ).html('<b>'+formatNumber(parseFloat(j).toFixed(2))+'</b>');
                                    $( api.column( 15 ).footer() ).html('<b>'+formatNumber(parseFloat(k).toFixed(2))+'</b>');
                                    $( api.column( 16 ).footer() ).html('<b>'+formatNumber(parseFloat(l).toFixed(2))+'</b>');
                                    var m = a + b + c + d + e + f + g + h + i + j + k + l;
                                    $( api.column( 17 ).footer() ).html('<b>'+formatNumber(parseFloat(m).toFixed(2))+'</b>');
                            },
                            drawCallback: function( settings ) {
                                  var api = this.api(), data;

                                    var intVal = function ( i ) {
                                      return typeof i === 'string' ?
                                          i.replace(/[\$,]/g, '')*1 :
                                          typeof i === 'number' ?
                                              i : 0;
                                    };

                                    var a = api.column(5,{search:"applied"}).data().reduce( function (a, b) {
                                                return intVal(a) + intVal(b);
                                                }, 0 );
                                    var b = api.column(6,{search:"applied"}).data().reduce( function (a, b) {
                                                return intVal(a) + intVal(b);
                                                }, 0 );
                                    var c = api.column(7,{search:"applied"}).data().reduce( function (a, b) {
                                                return intVal(a) + intVal(b);
                                                }, 0 );
                                    var d = api.column(8,{search:"applied"}).data().reduce( function (a, b) {
                                                return intVal(a) + intVal(b);
                                                }, 0 );
                                    var e = api.column(9,{search:"applied"}).data().reduce( function (a, b) {
                                                return intVal(a) + intVal(b);
                                                }, 0 );
                                    var f = api.column(10,{search:"applied"}).data().reduce( function (a, b) {
                                                return intVal(a) + intVal(b);
                                                }, 0 );
                                    var g = api.column(11,{search:"applied"}).data().reduce( function (a, b) {
                                                return intVal(a) + intVal(b);
                                                }, 0 );
                                    var h = api.column(12,{search:"applied"}).data().reduce( function (a, b) {
                                                return intVal(a) + intVal(b);
                                                }, 0 );
                                    var i = api.column(13,{search:"applied"}).data().reduce( function (a, b) {
                                                return intVal(a) + intVal(b);
                                                }, 0 );
                                    var j = api.column(14,{search:"applied"}).data().reduce( function (a, b) {
                                                return intVal(a) + intVal(b);
                                                }, 0 );
                                    var k = api.column(15,{search:"applied"}).data().reduce( function (a, b) {
                                                return intVal(a) + intVal(b);
                                                }, 0 );
                                    var l = api.column(16,{search:"applied"}).data().reduce( function (a, b) {
                                                return intVal(a) + intVal(b);
                                                }, 0 );
                                    // var m = api.column(14).data().reduce( function (a, b) {
                                    //             return intVal(a) + intVal(b);
                                    //             }, 0 );
                                    $( api.column( 4 ).footer() ).html('<b>Total Per Month(RM)</b>');
                                    $( api.column( 5 ).footer() ).html('<b>'+formatNumber(parseFloat(a).toFixed(2))+'</b>');
                                    $( api.column( 6 ).footer() ).html('<b>'+formatNumber(parseFloat(b).toFixed(2))+'</b>');
                                    $( api.column( 7 ).footer() ).html('<b>'+formatNumber(parseFloat(c).toFixed(2))+'</b>');
                                    $( api.column( 8 ).footer() ).html('<b>'+formatNumber(parseFloat(d).toFixed(2))+'</b>');
                                    $( api.column( 9 ).footer() ).html('<b>'+formatNumber(parseFloat(e).toFixed(2))+'</b>');
                                    $( api.column( 10 ).footer() ).html('<b>'+formatNumber(parseFloat(f).toFixed(2))+'</b>');
                                    $( api.column( 11 ).footer() ).html('<b>'+formatNumber(parseFloat(g).toFixed(2))+'</b>');
                                    $( api.column( 12 ).footer() ).html('<b>'+formatNumber(parseFloat(h).toFixed(2))+'</b>');
                                    $( api.column( 13 ).footer() ).html('<b>'+formatNumber(parseFloat(i).toFixed(2))+'</b>');
                                    $( api.column( 14 ).footer() ).html('<b>'+formatNumber(parseFloat(j).toFixed(2))+'</b>');
                                    $( api.column( 15 ).footer() ).html('<b>'+formatNumber(parseFloat(k).toFixed(2))+'</b>');
                                    $( api.column( 16 ).footer() ).html('<b>'+formatNumber(parseFloat(l).toFixed(2))+'</b>');
                                    var m = a + b + c + d + e + f + g + h + i + j + k + l;
                                    $( api.column( 17 ).footer() ).html('<b>'+formatNumber(parseFloat(m).toFixed(2))+'</b>');
                            },
                            columns: [
                            { data : null, "render":"", title: "No"},
                            { data : 'companies.Id', title : 'Id'},
                            { data : 'companies.Company_Name', title : 'Client'},
                            { data : 'companies.Company_Code', title : "Code"},
                            { data : 'companies.type', title : "Type"},
                            { data : 'a',"render": function ( data, type, full, meta ) {
                                      var col = meta.col;
                                      if(full.a == "" || full.a == null)
                                      {
                                        return 0;
                                      }
                                      else
                                      {
                                        return '<button class="details" id="'+full.companies.Id+'" col="'+col+'">'+formatNumber(parseFloat(data).toFixed(2))+'</button>';
                                      }
                              }
                            },
                            { data : 'b',"render": function ( data, type, full, meta ) {
                                      var col = meta.col;
                                      if(full.b == "" || full.b == null)
                                      {
                                        return 0;
                                      }
                                      else
                                      {
                                        return '<button class="details" id="'+full.companies.Id+'" col="'+col+'">'+formatNumber(parseFloat(data).toFixed(2))+'</button>';
                                      }
                              }},
                            { data : 'c',"render": function ( data, type, full, meta ) {
                                      var col = meta.col;
                                      if(full.c == "" || full.c == null)
                                      {
                                        return 0;
                                      }
                                      else
                                      {
                                        return '<button class="details" id="'+full.companies.Id+'" col="'+col+'">'+formatNumber(parseFloat(data).toFixed(2))+'</button>';
                                      }
                              }},
                            { data : 'd',"render": function ( data, type, full, meta ) {
                                      var col = meta.col;
                                      if(full.d == "" || full.d == null)
                                      {
                                        return 0;
                                      }
                                      else
                                      {
                                        return '<button class="details" id="'+full.companies.Id+'" col="'+col+'">'+formatNumber(parseFloat(data).toFixed(2))+'</button>';
                                      }
                              }},
                            { data : 'e',"render": function ( data, type, full, meta ) {
                                      var col = meta.col;
                                      if(full.e == "" || full.e == null)
                                      {
                                        return 0;
                                      }
                                      else
                                      {
                                        return '<button class="details" id="'+full.companies.Id+'" col="'+col+'">'+formatNumber(parseFloat(data).toFixed(2))+'</button>';
                                      }
                              }},
                            { data : 'f',"render": function ( data, type, full, meta ) {
                                      var col = meta.col;
                                      if(full.f == "" || full.f == null)
                                      {
                                        return 0;
                                      }
                                      else
                                      {
                                        return '<button class="details" id="'+full.companies.Id+'" col="'+col+'">'+formatNumber(parseFloat(data).toFixed(2))+'</button>';
                                      }
                              }},
                            { data : 'g',"render": function ( data, type, full, meta ) {
                                      var col = meta.col;
                                      if(full.g == "" || full.g == null)
                                      {
                                        return 0;
                                      }
                                      else
                                      {
                                        return '<button class="details" id="'+full.companies.Id+'" col="'+col+'">'+formatNumber(parseFloat(data).toFixed(2))+'</button>';
                                      }
                              }},
                            { data : 'h',"render": function ( data, type, full, meta ) {
                                      var col = meta.col;
                                      if(full.h == "" || full.h == null)
                                      {
                                        return 0;
                                      }
                                      else
                                      {
                                        return '<button class="details" id="'+full.companies.Id+'" col="'+col+'">'+formatNumber(parseFloat(data).toFixed(2))+'</button>';
                                      }
                              }},
                            { data : 'i',"render": function ( data, type, full, meta ) {
                                      var col = meta.col;
                                      if(full.i == "" || full.i == null)
                                      {
                                        return 0;
                                      }
                                      else
                                      {
                                        return '<button class="details" id="'+full.companies.Id+'" col="'+col+'">'+formatNumber(parseFloat(data).toFixed(2))+'</button>';
                                      }
                              }},
                            { data : 'j',"render": function ( data, type, full, meta ) {
                                      var col = meta.col;
                                      if(full.j == "" || full.j == null)
                                      {
                                        return 0;
                                      }
                                      else
                                      {
                                        return '<button class="details" id="'+full.companies.Id+'" col="'+col+'">'+formatNumber(parseFloat(data).toFixed(2))+'</button>';
                                      }
                              }},
                            { data : 'k',"render": function ( data, type, full, meta ) {
                                      var col = meta.col;
                                      if(full.k == "" || full.k == null)
                                      {
                                        return 0;
                                      }
                                      else
                                      {
                                        return '<button class="details" id="'+full.companies.Id+'" col="'+col+'">'+formatNumber(parseFloat(data).toFixed(2))+'</button>';
                                      }
                              }},
                            { data : 'l',"render": function ( data, type, full, meta ) {
                                      var col = meta.col;
                                      if(full.l == "" || full.l == null)
                                      {
                                        return 0;
                                      }
                                      else
                                      {
                                        return '<button class="details" id="'+full.companies.Id+'" col="'+col+'">'+formatNumber(parseFloat(data).toFixed(2))+'</button>';
                                      }
                              }},
                            { data : 'm', title : "Total Per Client(RM)",
                              "render": function ( data, type, full, meta ) {
                                  var a = full.a;
                                  var b = full.b;
                                  var c = full.c;
                                  var d = full.d;
                                  var e = full.e;
                                  var f = full.f;
                                  var g = full.g;
                                  var h = full.h;
                                  var i = full.i;
                                  var j = full.j;
                                  var k = full.k;
                                  var l = full.l;
                                  var total = Number(a) + Number(b) + Number(c) + Number(d) + Number(e) + Number(f) + Number(g) + Number(h) + Number(i) + Number(j) + Number(k) + Number(l)
                                  return formatNumber(parseFloat(total).toFixed(2));
                              }

                            }
                            ],
                                 select: true,
                                 buttons: [
                                 ],
                     });

          oTable1.on( 'order.dt search.dt', function () {
          oTable1.api().column(0, {search:'applied', order:'applied'}).nodes().each( function (cell, i) {
            cell.innerHTML = i+1;
          } );
          } ).api().draw();

          $(".summarytable thead input").keyup ( function () {
            if ($('#summarytable').length > 0)
            {
                var colnum=document.getElementById('summarytable').rows[0].cells.length;
                if (this.value=="[empty]")
                {
                    oTable1.fnFilter( '^$', this.name,true,false );
                }
                else if (this.value=="[nonempty]")
                {
                    oTable1.fnFilter( '^(?=\\s*\\S).*$', this.name,true,false );
                }
                else if (this.value.startsWith("!")==true && this.value.length>1)
                {
                    oTable1.fnFilter( '^(?'+ this.value +').*', this.name,true,false );
                }
                else if (this.value.startsWith("!")==false)
                {
                    oTable1.fnFilter( this.value, this.name,true,false );
                }
            }
        });
        });
      </script>

@endsection

@section('content')

<div class="content-wrapper">
  <!-- Content Header (Page header) -->
  <section class="content-header">
    <h1>Stock Cost Summary<small>Sales Order</small></h1>

    <ol class="breadcrumb">
      <li><a href="{{ url('/home') }}"><i class="fa fa-dashboard"></i>Home</a></li>
      <li><a href="#">Sales Order</a></li>
      <li class="active">Stock Cost Summary</li>
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

      <div class="box">
      <table id="summarytable" class="summarytable" cellspacing="0" width="100%" padding="30px" style="font-size: 13px;">
                            <thead>
                               <tr class="search">
                                @foreach($summary as $key=>$value)

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
                                    @foreach($summary as $key=>$value)

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
                                @foreach($summary as $delivery)

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
                            <tfoot>
                                <tr>
                                  <td></td>
                                  <td></td>
                                  <td></td>
                                  <td></td>
                                  <td></td>
                                  <td></td>
                                  <td></td>
                                  <td></td>
                                  <td></td>
                                  <td></td>
                                  <td></td>
                                  <td></td>
                                  <td></td>
                                  <td></td>
                                  <td></td>
                                  <td></td>
                                  <td></td>
                                  <td></td>
                                </tr>
                            </tfoot>
                        </table>
                      </div>

  </section>

  <footer class="main-footer">
    <div class="pull-right hidden-xs">
      <b>Version</b> 1.0.0
    </div>
    <strong>Copyright &copy; 2014-2016 <a href="http://www.softoya.com">TrackerOnTheGo</a>.</strong> All rights reserved.
  </footer>
</div>

<script type="text/javascript">

  $(function () {
    });

  function formatNumber(num) {
    return num.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ",");
   }

   $(document).ready(function() {
    $(document).on('click', '.details', function(e) {
    var table = $('#summarytable').dataTable();
    var companyid = $(this).attr('id');
    var col = $(this).attr('col');
    var title = table.api().column( col ).header();
    title = $.trim($(title).html());
    window.open('/salessummarydetails/'+companyid+'/'+title+'/JDNI','_blank');
    });
    });
</script>

@endsection