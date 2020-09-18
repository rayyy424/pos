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

      .box-header .box-title{
        font-size: 14px;
      }
      span#Grand_Total{
        color:red;
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

      <script type="text/javascript" language="javascript" class="init">

      var editor;

      $(document).ready(function() {


        editor1 = new $.fn.dataTable.Editor( {
              ajax: {
                 "url": "{{ asset('/Include/printer1.php') }}",
                 "data": {
                     "Id": {{ $id }}
                 }
               },
                table: "#reporttable",
                idSrc: "printer.Id",
                formOptions: {
                     bubble: {
                         submit: 'allIfChanged'
                     }
                 },
                fields: [
                        {
                                label: "Name:",
                                name: "printer.Name"
                        },{

                                label: "Name:",
                                name: "printer.ReportId"
                        },{
                                label: "Floor:",
                                name: "printer.Floor",
                                type: "select",
                                options: [
                                  { label :"", value: "" },
                                  { label :"Ground Floor", value: "Ground Floor" },
                                  { label :"First Floor", value: "First Floor" },
                                  { label :"Fabyard", value: "Fabyard" },
                                ],
                        },{
                                label: "Printer_Model:",
                                name: "printer.Printer_Model",
                                type:  "select",
                                options: [
                                  { label :"", value: "" },
                                  { label :"Canon IRC 3580 B/W", value: "Canon IRC 3580 B/W" },
                                  { label :"Canon IRC 3580 Color", value: "Canon IRC 3580 Color" },
                                  { label :"Canon IR 3570 B/W", value: "Canon IR 3570 B/W" },
                                  { label :"Monthly Services Maintenance Fees", value: "Monthly Services Maintenance Fees" }
                                ],
                        },{
                                label: "Type:",
                                name: "printer.Type"
                        },{
                                label: "Quantity:",
                                name: "printer.Quantity"


                        },{
                                label: "Start_Date:",
                                name: "printer.Start_Date",
                                type:   'datetime',
                                def:    function () { return ""; },
                                format: 'DD-MMM-YYYY'
                        },{
                                label: "End_Date:",
                                name: "printer.End_Date",
                                type:   'datetime',
                                def:    function () { return ""; },
                                format: 'DD-MMM-YYYY'
                        }

                ]
        } );

        //Activate an inline edit on click of a table cell

              $('#reporttable').on( 'click', 'tbody td', function (e) {
                    editor1.inline( this, {
                   onBlur: 'submit',
                    submit: 'allIfChanged'
                  } );
              } );

              editor1.on( 'postEdit', function ( e, json, data ) {

                $("#Grand_Total").html("RM " + reporttable.api().column( "Total:name" ).data().sum().toFixed(2));

               } );



        var reporttable= $('#reporttable').dataTable( {
          ajax: {
             "url": "{{ asset('/Include/printer1.php') }}",
             "data": {
                 "Id": {{ $id }}
             }
           },
               columnDefs: [{ "visible": false, "targets": [1] },{"className": "dt-center", "targets": "_all"}],
               fnInitComplete: function(oSettings, json) {
                 $("#Grand_Total").html("RM " + reporttable.api().column( "Total:name" ).data().sum().toFixed(2));
               },
               responsive: false,
               colReorder: false,
               bAutoWidth: true,
               dom: "Blfrtp",
               scrollY: "100%",
               scrollX: "100%",
               scrollCollapse: true,
               bAutoWidth: true,
               iDisplayLength:10,
               rowId: 'printer.Id',
               columns: [
                       { data: null, "render":"", title:"No"},
                       { data: "printer.Id"},
                       { data: "printer.Floor", title:"Floor" },
                       { data: "printer.Printer_Model" ,title:"Expense Type"},
                       { data: "printer.Start_Date" , title:"Start Date"},
                       { data: "printer.End_Date" , title:"End Date"},
                       { data: "printer.Quantity", title:"Quantity" },
                       { data: "printer.UP", title:"U/P" },
                       { data: "printer.Total_Without_GST", title:"Total Without GST" },
                       { data: "printer.Total", title:"Total", name:"Total" }
               ],
               select: {
                       style:    'os',
                       selector: 'td:first-child'
               },
               autoFill: {
                  editor:  editor1
              },
              select: true,
               buttons: [
                       {
                         text: 'New',
                         action: function ( e, dt, node, config ) {
                             // clearing all select/input options
                             editor1
                                .create( false )
                                .set( 'printer.Type','Report')
                                .set( 'printer.ReportId',{{$id}})
                                .submit();
                         },
                       },
                       { extend: "remove", editor: editor1 },
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


        reporttable.api().on( 'order.dt search.dt', function () {
          reporttable.api().column(0, {search:'applied', order:'applied'}).nodes().each( function (cell, i) {
              cell.innerHTML = i+1;
          } );
        } ).draw();



      $(".reporttable thead input").keyup ( function () {

              /* Filter on the column (the index) of this element */

              if ($('#reporttable').length > 0)
              {

                var colnum=document.getElementById('reporttable').rows[0].cells.length;

                if (this.value=="[empty]")
                {

                   reporttable.fnFilter( '^$', $("thead input").index(this)-colnum,true,false );
                }
                else if (this.value=="[nonempty]")
                {

                   reporttable.fnFilter( '^(?=\\s*\\S).*$', $("thead input").index(this)-colnum,true,false );
                }
                else if (this.value.startsWith("!")==true && this.value.length>1)
                {

                   reporttable.fnFilter( '^(?'+ this.value +').*', $("thead input").index(this)-colnum,true,false );
                }
                else if (this.value.startsWith("!")==false)
                {

                  reporttable.fnFilter( this.value, $("thead input").index(this)-colnum,true,false );
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
        Printer Report
        <small>IT Support</small>
      </h1>
      <ol class="breadcrumb">
       <li><a href="{{ url('/home') }}"><i class="fa fa-dashboard"></i> Home</a></li>
       <li><a href="#">IT Support</a></li>
       <li class="active">Printer Report</li>
      </ol>
    </section>

    <!-- Main content -->
    <section class="content">
      <div class="row">

        <div class="col-md-12">
          <div class="box">
            <div class="box-body">



                      <div class="row>">

                        <!-- <div class="col-md-6">
                          <select class="form-control" id="month" onchange="month()">
                            <option>Select Month</option>
                            <option>Jan 2017</option>
                            <option>Feb 2017</option>
                            <option>Mar 2017</option>
                            <option>Apr 2017</option>
                            <option>May 2017</option>
                            <option>Jun 2017</option>
                            <option>Jul 2017</option>
                            <option>Aug 2017</option>
                            <option>Sep 2017</option>
                            <option>Oct 2017</option>
                            <option>Nov 2017</option>
                            <option>Dec 2017</option>
                          </select>
                        </div>
                      </div> -->



                        <table id="reporttable" class="reporttable" cellspacing="0" width="100%" padding="30px" style="font-size: 13px;">
                            <thead>

                              <!-- <tr class="search">
                                <td align='center'><input type='hidden' class='search_init' /></td>
                                @foreach($report as $key=>$values)
                                  @if ($key==0)

                                  @foreach($values as $field=>$value)

                                      <td align='center'><input type='text' class='search_init' /></td>

                                  @endforeach

                                  @endif

                                @endforeach
                              </tr> -->

                                <tr>
                                    <td></td>
                                  @foreach($report as $key=>$value)

                                    @if ($key==0)
                                      @foreach($value as $field=>$value)
                                          <td/>{{ $field }}</td>
                                      @endforeach
                                    @endif

                                  @endforeach
                                </tr>
                            </thead>
                            <tbody>

                              <?php $i = 0; ?>
                              @foreach($report as $reportview)

                                <tr id="row_{{ $i }}">
                                  <td></td>
                                    @foreach($reportview as $key=>$value)
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

                        <div class="row">
                          <div class="col-md-12"><b>Grand Total</b> : <i><span id='Grand_Total'>RM 0.00</span></i>
                          <a class="btn btn-danger btn-small" onclick="save()">Save</a>
                          </div>
                        </div>


            </div>
          </div>

          <!-- /.nav-tabs-custom -->
        </div>
        <!-- /.col -->
      </div>
      <!-- /.row -->
    </section>
    <!-- /.content -->
  </div>
  <!-- /.content-wrapper -->
  <footer class="main-footer">
    <div class="pull-right hidden-xs">
      <b>Version</b> 1.0.0
    </div>
    <strong>Copyright &copy; 2014-2016 <a href="http://www.softoya.com">TrackerOnTheGo</a>.</strong> All rights
    reserved.
  </footer>

<script>

function save(){

}


</script>


@endsection
