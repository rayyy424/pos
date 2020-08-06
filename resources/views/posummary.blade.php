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
      $(document).ready(function() {

        var table = $('#table').dataTable( {
            columnDefs: [{ "visible": false, "targets": [] },{"className": "dt-center", "targets": "_all"}],
            responsive: false,
            colReorder: false,
            dom: "Brt",
            bAutoWidth: true,
            bPaginate:false,
            aaSorting:false,
            //"order": [[ 3, "asc" ]],
            // column:[
            //   {data: null,"render":"", title:"No"},
            //   {data: "PO_Type", title: "PO_Type"},
            //   {data: "#_Of_PO", title: "#_Of_PO"},
            //   {data: "Total_Amount", title: "Total_Amount"}
            // ],
            select: {
                    style:    'os',
                    selector: 'tr'
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

      var table2 = $('#table2').dataTable( {
          columnDefs: [{ "visible": false, "targets": [] },{"className": "dt-center", "targets": "_all"}],
          responsive: false,
          colReorder: false,
          dom: "Brt",
          bAutoWidth: true,
          bPaginate:false,
          aaSorting:false,
          iDisplayLength:10,
          //"order": [[ 3, "asc" ]],
          // column:[
          //   {data: null,"render":"", title:"No"},
          //   {data: "Company", title: "Company"},
          //   {data: "PO_Type", title: "PO_Type"},
          //   {data: "#_Of_PO", title: "#_Of_PO"},
          //   {data: "Total_Amount", title: "Total_Amount"}
          // ],
          select: {
                  style:    'os',
                  selector: 'tr'
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

    var table3 = $('#table3').dataTable( {
        columnDefs: [{ "visible": false, "targets": [] },{"className": "dt-center", "targets": "_all"}],
        responsive: false,
        colReorder: false,
        dom: "Brt",
        bAutoWidth: true,
        bPaginate:false,
        aaSorting:false,
        iDisplayLength:10,
        //"order": [[ 3, "asc" ]],
        // column:[
        //   {data: null,"render":"", title:"No"},
        //   {data: "Job_Type", title: "Job_Type"},
        //   {data: "#_Of_PO", title: "#_Of_PO"},
        //   {data: "Total_Amount", title: "Total_Amount"}
        // ],
        select: {
                style:    'os',
                selector: 'tr'
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

  var table4 = $('#table4').dataTable( {
      columnDefs: [{ "visible": false, "targets": [] },{"className": "dt-center", "targets": "_all"}],
      responsive: false,
      colReorder: false,
      dom: "Brt",
      bAutoWidth: true,
      bPaginate:false,
      aaSorting:false,
      iDisplayLength:10,
      //"order": [[ 3, "asc" ]],
      // column:[
      //   {data: null,"render":"", title:"No"},
      //   {data: "Project_Name", title: "Project_Name"},
      //   {data: "Pending", title: "Pending"}
      // ],
      select: {
              style:    'os',
              selector: 'tr'
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

    table.api().on( 'order.dt search.dt', function () {
        table.api().column(0, {search:'applied', order:'applied'}).nodes().each( function (cell, i) {
            cell.innerHTML = i+1;
        } );
    } ).draw();

    table2.api().on( 'order.dt search.dt', function () {
        table2.api().column(0, {search:'applied', order:'applied'}).nodes().each( function (cell, i) {
            cell.innerHTML = i+1;
        } );
    } ).draw();

    table3.api().on( 'order.dt search.dt', function () {
        table3.api().column(0, {search:'applied', order:'applied'}).nodes().each( function (cell, i) {
            cell.innerHTML = i+1;
        } );
    } ).draw();

    table4.api().on( 'order.dt search.dt', function () {
        table4.api().column(0, {search:'applied', order:'applied'}).nodes().each( function (cell, i) {
            cell.innerHTML = i+1;
        } );
    } ).draw();

    $("#table thead input").keyup ( function () {

            /* Filter on the column (the index) of this element */
            if ($('#table').length > 0)
            {

                var colnum=document.getElementById('table').rows[0].cells.length;

                if (this.value=="[empty]")
                {

                   table.fnFilter( '^$', $("#table thead input").index(this)-colnum,true,false );
                }
                else if (this.value=="[nonempty]")
                {

                   table.fnFilter( '^(?=\\s*\\S).*$', $("#table thead input").index(this)-colnum,true,false );
                }
                else if (this.value.startsWith("!")==true && this.value.length>1)
                {

                   table.fnFilter( '^(?'+ this.value +').*', $("#table thead input").index(this)-colnum,true,false );
                }
                else if (this.value.startsWith("!")==false)
                {

                   table.fnFilter( this.value, $("#table thead input").index(this)-colnum,true,false );
                }
            }
      } );

    $("#table2 thead input").keyup ( function () {

            if ($('#table2').length > 0)
            {

                var colnum=document.getElementById('table2').rows[0].cells.length;

                if (this.value=="[empty]")
                {

                   table2.fnFilter( '^$', $("#table2 thead input").index(this)-colnum,true,false );
                }
                else if (this.value=="[nonempty]")
                {

                   table2.fnFilter( '^(?=\\s*\\S).*$', $("#table2 thead input").index(this)-colnum,true,false );
                }
                else if (this.value.startsWith("!")==true && this.value.length>1)
                {

                   table2.fnFilter( '^(?'+ this.value +').*', $("#table2 thead input").index(this)-colnum,true,false );
                }
                else if (this.value.startsWith("!")==false)
                {

                   table2.fnFilter( this.value, $("#table2 thead input").index(this)-colnum,true,false );
                }
            }

    } );

    $("#table3 thead input").keyup ( function () {

            if ($('#table3').length > 0)
            {

                var colnum=document.getElementById('table3').rows[0].cells.length;

                if (this.value=="[empty]")
                {

                   table3.fnFilter( '^$', $("#table3 thead input").index(this)-colnum,true,false );
                }
                else if (this.value=="[nonempty]")
                {

                   table3.fnFilter( '^(?=\\s*\\S).*$', $("#table3 thead input").index(this)-colnum,true,false );
                }
                else if (this.value.startsWith("!")==true && this.value.length>1)
                {

                   table3.fnFilter( '^(?'+ this.value +').*', $("#table3 thead input").index(this)-colnum,true,false );
                }
                else if (this.value.startsWith("!")==false)
                {

                   table3.fnFilter( this.value, $("#table3 thead input").index(this)-colnum,true,false );
                }
            }

    } );

    $("#table4 thead input").keyup ( function () {

            if ($('#table4').length > 0)
            {

                var colnum=document.getElementById('table4').rows[0].cells.length;

                if (this.value=="[empty]")
                {

                   table4.fnFilter( '^$', $("#table4 thead input").index(this)-colnum,true,false );
                }
                else if (this.value=="[nonempty]")
                {

                   table4.fnFilter( '^(?=\\s*\\S).*$', $("#table4 thead input").index(this)-colnum,true,false );
                }
                else if (this.value.startsWith("!")==true && this.value.length>1)
                {

                   table4.fnFilter( '^(?'+ this.value +').*', $("#table4 thead input").index(this)-colnum,true,false );
                }
                else if (this.value.startsWith("!")==false)
                {

                   table4.fnFilter( this.value, $("#table4 thead input").index(this)-colnum,true,false );
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
      PO Summary
      <small>Sales Management</small>
      </h1>

      <ol class="breadcrumb">
      <li><a href="{{ url('/home') }}"><i class="fa fa-dashboard"></i> Home</a></li>
      <li><a href="#">Sales Management</a></li>
      <li class="active">PO Summary</li>
      </ol>
    </section>

    <section class="content">

      <div class="row">

        <div class="box box-success">
          <br>

          <div class="col-md-6">
          <div class="input-group">
            <div class="input-group-addon">
              <i class="fa fa-clock-o"></i>
            </div>
            <input type="text" class="form-control" id="range" name="range">

          </div>
        </div>

        <div class="col-md-6">
            <div class="input-group">
              <button type="button" class="btn btn-success btn" data-toggle="modal" onclick="refresh();">Refresh</button>
            </div>
        </div>
        <label></label>
      </div>
    </div>

      <div class="row">

        <div class="col-md-12">

          <h5 class="box-title"><b>PO Receive and Issue<b></h5>

        </div>

         <div class="col-md-12">
           <div class="box">
             <div class="box-body">
                <table id="table" class="display" cellspacing="0" width="100%" padding="30px" style="font-size: 13px;">
                    @if ($summary)

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
                                     <td></td>
                                  @foreach($value as $field=>$value)
                                      <td/>{{ $field }}</td>
                                  @endforeach

                                @endif

                              @endforeach
                            </tr>
                        </thead>
                        <tbody>

                          <?php $i = 0; ?>
                          @foreach($summary as $line)

                            <tr id="row_{{ $i }}">
                                 <td></td>
                                @foreach($line as $key=>$value)
                                  <td>
                                    {{ $value }}
                                  </td>
                                @endforeach
                            </tr>
                            <?php $i++; ?>
                          @endforeach



                      </tbody>
                        <tfoot></tfoot>

                    @endif

                 </table>
             </div>
         </div>
       </div>
     </div>

     <div class="row">

       <div class="col-md-12">

         <h5 class="box-title"><b>PO Receive and Issue Breakdown by Company<b></h5>

       </div>

        <div class="col-md-12">
          <div class="box">
            <div class="box-body">
               <table id="table2" class="display" cellspacing="0" width="100%" padding="30px" style="font-size: 13px;">
                 @if($summary2)

                   <thead>
                     <tr class="search">
                       <td align='center'><input type='hidden' class='search_init' /></td>
                       @foreach($summary2 as $key=>$values)

                         @if ($key==0)

                           @foreach($values as $field=>$value)

                               <td align='center'><input type='text' class='search_init' /></td>

                           @endforeach

                         @endif

                       @endforeach
                     </tr>
                       <tr>
                         @foreach($summary2 as $key=>$value)

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

                     <?php $i = 0; ?>
                     @foreach($summary2 as $line)

                       <tr id="row_{{ $i }}">
                             <td></td>
                           @foreach($line as $key=>$value)
                             <td>
                               {{ $value }}
                             </td>
                           @endforeach
                       </tr>
                       <?php $i++; ?>
                     @endforeach



                 </tbody>
                   <tfoot></tfoot>

                 @endif

                </table>
            </div>
        </div>
      </div>
    </div>

    <div class="row">

      <div class="col-md-12">

        <h5 class="box-title"><b>PO Receive and Issue Breakdown by Job Type<b></h5>

      </div>

       <div class="col-md-12">
         <div class="box">
           <div class="box-body">
              <table id="table3" class="display" cellspacing="0" width="100%" padding="30px" style="font-size: 13px;">

                  @if($summary3)

                    <thead>
                      <tr class="search">
                        <td align='center'><input type='hidden' class='search_init' /></td>
                        @foreach($summary3 as $key=>$values)

                          @if ($key==0)

                            @foreach($values as $field=>$value)

                                <td align='center'><input type='text' class='search_init' /></td>

                            @endforeach

                          @endif

                        @endforeach
                      </tr>

                        <tr>
                          @foreach($summary3 as $key=>$value)

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

                      <?php $i = 0; ?>
                      @foreach($summary3 as $line)

                        <tr id="row_{{ $i }}">
                             <td></td>
                            @foreach($line as $key=>$value)
                              <td>
                                {{ $value }}
                              </td>
                            @endforeach
                        </tr>
                        <?php $i++; ?>
                      @endforeach



                  </tbody>
                    <tfoot></tfoot>

                  @endif

               </table>
           </div>
       </div>
     </div>
   </div>

   <div class="row">

     <div class="col-md-12">

       <h5 class="box-title"><b>Job Pending PO<b></h5>

     </div>

      <div class="col-md-12">
        <div class="box">
          <div class="box-body">
             <table id="table4" class="display" cellspacing="0" width="100%" padding="30px" style="font-size: 13px;">

                @if($pendingpo)

                  <thead>
                    <tr class="search">
                      <td align='center'><input type='hidden' class='search_init' /></td>
                      @foreach($pendingpo as $key=>$values)

                        @if ($key==0)

                          @foreach($values as $field=>$value)

                              <td align='center'><input type='text' class='search_init' /></td>

                          @endforeach

                        @endif

                      @endforeach
                    </tr>

                      @if($pendingpo)
                        <tr>
                          @foreach($pendingpo as $key=>$value)

                            @if ($key==0)
                                  <td></td>
                              @foreach($value as $field=>$value)
                                  <td/>{{ $field }}</td>
                              @endforeach

                            @endif

                          @endforeach
                        </tr>
                      @endif
                  </thead>
                  <tbody>

                    @if($pendingpo)

                      <?php $i = 0; ?>
                      @foreach($pendingpo as $line)

                        <tr id="row_{{ $i }}">
                              <td></td>
                            @foreach($line as $key=>$value)
                              <td>
                                {{ $value }}
                              </td>
                            @endforeach
                        </tr>
                        <?php $i++; ?>
                      @endforeach

                    @endif

                </tbody>
                  <tfoot></tfoot>

                @endif

              </table>
          </div>
      </div>
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


  $('#range').daterangepicker({locale: {
    format: 'DD-MMM-YYYY'
  },startDate: '{{$start}}',
  endDate: '{{$end}}'});

});

function refresh()
{
  var d=$('#range').val();
  var arr = d.split(" - ");

  window.location.href ="{{ url("/POSummary") }}/"+arr[0]+"/"+arr[1];

}

</script>



@endsection
