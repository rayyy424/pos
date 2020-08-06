@extends('app', ['title' => 'TOTG'])

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

      table.dataTable tbody td {

       max-width: 350px;
      overflow:hidden;
      text-overflow: ellipsis;

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

        var login = $('#logintracktable').dataTable( {
            columnDefs: [{ "visible": false, "targets": [] },{"className": "dt-center", "targets": "_all"}],
            // responsive: false,
            // colReorder: false,
            // dom: "lfrtip",
            // bAutoWidth: true,
            // aaSorting:false,
            iDisplayLength:20,
            responsive: false,
            colReorder: false,
            sScrollX: "100%",
            bAutoWidth: true,
            sScrollY: "100%",
            dom: "Bfrtip",
            bScrollCollapse: true,
            //"order": [[ 3, "asc" ]],

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

      login.api().on( 'order.dt search.dt', function () {
        login.api().column(0, {search:'applied', order:'applied'}).nodes().each( function (cell, i) {
            cell.innerHTML = i+1;
        } );
    } ).draw();

    $("thead input").keyup(function() {

        /* Filter on the column (the index) of this element */
        if ($('#logintracktable').length > 0) {

            var colnum = document.getElementById('logintracktable').rows[0].cells.length;

            if (this.value == "[empty]") {

                login.fnFilter('^$', $("thead input").index(this) - colnum, true, false);
            } else if (this.value == "[nonempty]") {

                login.fnFilter('^(?=\\s*\\S).*$', $("thead input").index(this) - colnum, true, false);
            } else if (this.value.startsWith("!") == true && this.value.length > 1) {

                login.fnFilter('^(?' + this.value + ').*', $("thead input").index(this) - colnum, true, false);
            } else if (this.value.startsWith("!") == false) {

                login.fnFilter(this.value, $("thead input").index(this) - colnum, true, false);
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
      <h1>
      Tracker Update Tracker
      <small>Admin</small>
      </h1>

      <ol class="breadcrumb">
      <li><a href="{{ url('/home') }}"><i class="fa fa-dashboard"></i> Home</a></li>
      <li><a href="#">Admin</a></li>
      <li class="active">Tracker Update Tracker</li>
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
           <div class="box">
             <div class="box-body">
                <table id="logintracktable" class="display" cellspacing="0" width="100%" padding="30px" style="font-size: 13px;">
                     <thead>

                       <tr class="search">
                           @foreach($logs as $key=>$values)

                             @if ($key==0)

                             <td align='center'><input type='hidden' class='search_init' /></td>

                               @foreach($values as $field=>$value)

                                   <td align='center'><input type='text' class='search_init' /></td>


                               @endforeach

                             @endif

                           @endforeach
                       </tr>

                         <tr>
                           <td>No</td>
                           <td>Type</td>
                           <td>Project_Name</td>
                           <td>Project_Code</td>
                           <td>Unique ID</td>
                           <td>Site_ID</td>
                           <td>Site_Name</td>
                           <td>Details</td>
                           <td>Updated_By</td>
                           <td>Updated_At</td>
                         </tr>
                     </thead>
                     <tbody>

                       <?php $i = 0; ?>
                       @foreach($logs as $log)

                         <tr id="row_{{ $i }}">
                              <td></td>
                             @foreach($log as $key=>$value)
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

  window.location.href ="{{ url("/trackerupdatetracker") }}/"+arr[0]+"/"+arr[1];

}

</script>



@endsection
