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

      <script type="text/javascript" language="javascript" src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.8.4/moment.min.js"></script>

      <script type="text/javascript" language="javascript" src="{{ asset('/plugin/js/dataTables.editor.min.js') }}"></script>
      {{-- <script type="text/javascript" language="javascript" src="{{ asset('/plugin/examples/resources/syntax/shCore.js') }}"></script>
      <script type="text/javascript" language="javascript" src="{{ asset('/plugin/examples/resources/demo.js') }}"></script>
      <script type="text/javascript" language="javascript" src="{{ asset('/plugin/examples/resources/editor-demo.js') }}"></script> --}}

      <script type="text/javascript" language="javascript" class="init">

          var claimseditor;
          var claims2editor;
          var claims3editor;
          var claimtable;
          var claim2table;
          var claim3table;

          var timesheetmonth;

          var asInitVals = new Array();

          $(document).ready(function() {
                         claimseditor = new $.fn.dataTable.Editor( {
                                 ajax: {
                                    "url": "{{ asset('/Include/claimsheet.php') }}",
                                    "data": {
                                        "UserId": {{ $me->UserId }}
                                    }
                                  },
                                 table: "#claimtable",
                                 idSrc: "claimsheets.Id",
                                 fields: [
                                   {
                                           name: "claimsheets.UserId",
                                           type: "hidden"
                                   },{
                                          label: "Claim Sheet Name",
                                          name: "claimsheets.Claim_Sheet_Name",
                                          type:"readonly"
                                   },{
                                          label: "Remarks:",
                                          name: "claimsheets.Remarks"
                                   },{
                                          label: "Status:",
                                          name: "claimsheets.Status",
                                          type:"readonly"
                                   }

                                 ]
                         } );

                         claimseditor.on( 'postSubmit', function ( e, json, data, action) {

                           if(json["fieldErrors"])
                           {

                             var errormessage="Duplicate claimsheet for "+$('[name="Payment_Month"]').val();
                             $("#error-alert ul").html(errormessage);
                             $("#error-alert").modal('show');


                           }

                       } );

                         claims2editor = new $.fn.dataTable.Editor( {
                           ajax: {
                              "url": "{{ asset('/Include/claimsheet.php') }}",
                              "data": {
                                  "UserId": {{ $me->UserId }}
                              }
                            },
                                 table: "#claim2table",
                                 idSrc: "claimsheets.Id",
                                 fields: [
                                   {
                                           name: "claimsheets.UserId",
                                           type: "hidden"
                                   },{
                                          label: "Claim Sheet Name",
                                          name: "claimsheets.Claim_Sheet_Name"
                                   },{
                                          label: "Remarks:",
                                          name: "claimsheets.Remarks"
                                   },{
                                          label: "Status:",
                                          name: "claimsheets.Status",
                                          type:"readonly"
                                   }

                                 ]
                         } );

                         claims3editor = new $.fn.dataTable.Editor( {
                           ajax: {
                              "url": "{{ asset('/Include/claimsheet.php') }}",
                              "data": {
                                  "UserId": {{ $me->UserId }}
                              }
                            },
                                 table: "#claim3table",
                                 idSrc: "claimsheets.Id",
                                 fields: [
                                   {
                                           name: "claimsheets.UserId",
                                           type: "hidden"
                                   },{
                                          label: "Claim Sheet Name",
                                          name: "claimsheets.Claim_Sheet_Name"
                                   },{
                                          label: "Remarks:",
                                          name: "claimsheets.Remarks"
                                   },{
                                          label: "Status:",
                                          name: "claimsheets.Status",
                                          type:"readonly"
                                   }

                                 ]
                         } );

                         claimtable=$('#claimtable').dataTable( {
                                 ajax: {
                                    "url": "{{ asset('/Include/claimsheet.php') }}",
                                    "data": {
                                        "UserId": {{ $me->UserId }}
                                    }
                                  },
                                 columnDefs: [{ "visible": false, "targets": [1,2] },{"className": "dt-center", "targets": "_all"}],
                                 responsive: false,
                                 colReorder: false,
                                 dom: "Brt",
                                 bPaginate: false,
                                 bAutoWidth: false,
                                 aaSorting:false,
                                 rowId:"claimsheets.Id",
                                 fnInitComplete: function(oSettings, json) {

                                   $('#mypendingclaimtab').html("My Pending Claim" + "[" + claimtable.api().rows().count() +"]")

                                  },
                                 columns: [
                                     {
                                        sortable: false,
                                        "render": function ( data, type, full, meta ) {
                                            return '<a href="myclaim/'+full.claimsheets.Id+'" >View</a>';
                                        }
                                    },
                                   { data: "claimsheets.Id"},
                                   { data: "claimsheets.UserId"},
                                   { data: "claimsheets.Claim_Sheet_Name"},
                                   { data: "claimsheets.Remarks"},
                                   { data: "claimsheets.Status"},
                                   { data: "claimsheets.created_at"}

                                 ],
                                 autoFill: {
                                    editor:  claimseditor,
                                    columns: [ 3, 4 ]
                                },
                                // keys: {
                                //     columns: ':not(:first-child)',
                                //     editor:  claimseditor
                                // },
                                select: {
                                    style:    'multi',
                                    selector: 'tr'
                                },
                                 buttons: [
                                         {
                                           text: 'Claimsheet For : <select id="Payment_Month" name="Payment_Month" > @foreach ($paymentmonth as $month) <option value="{{$month->Payment_Month}}" <?php if($month->Payment_Month == $current) echo ' selected="selected" '; ?>>{{$month->Payment_Month}}</option>@endforeach</select>'

                                         },
                                         {
                                           text: 'New Claim Sheet',
                                           action: function ( e, dt, node, config ) {
                                               // clearing all select/input options

                                               var current = moment("{{$current}}")
                                               var select = moment($('[name="Payment_Month"]').val());
                                               var naming="";

                                               if(select<current)
                                               {
                                                 naming=" (Back-Date)";
                                               }

                                               claimseditor
                                                  .create( false )
                                                  .set( 'claimsheets.Claim_Sheet_Name', "{{$me->Staff_ID}}-"+ $('[name="Payment_Month"]').val() +" Claim"+ naming)
                                                  .set( 'claimsheets.UserId', {{ $me->UserId }} )
                                                  .set( 'claimsheets.Status', "Pending Submission" )
                                                  .submit();
                                           },
                                         },
                                         { extend: "remove", editor: claimseditor }
                                 ],

                     });

                     claim2table=$('#claim2table').dataTable( {
                           ajax: {
                              "url": "{{ asset('/Include/claimsheet.php') }}",
                              "data": {
                                  "UserId": {{ $me->UserId }},
                                  "Status": "%Approved%"
                              }
                            },
                            columnDefs: [{ "visible": false, "targets": [1,2] },{"className": "dt-center", "targets": "_all"}],
                            responsive: false,
                            colReorder: false,
                            dom: "Brt",
                            bPaginate: false,
                            bAutoWidth: false,
                            aaSorting:false,
                            rowId:"claimsheets.Id",
                            fnInitComplete: function(oSettings, json) {

                              $('#myapprovedclaimtab').html("My Approved Claim" + "[" + claim2table.api().rows().count() +"]")

                             },
                             columns: [
                               {
                                  sortable: false,
                                  "render": function ( data, type, full, meta ) {
                                      return '<a href="myclaim/'+full.claimsheets.Id+'" >View</a>';
                                  }
                              },
                               { data: "claimsheets.Id"},
                               { data: "claimsheets.UserId"},
                               { data: "claimsheets.Claim_Sheet_Name"},
                               { data: "claimsheets.Remarks"},
                               { data: "claimsheets.Status"},
                               { data: "claimsheets.created_at"}
                             ],

                            // keys: {
                            //     columns: ':not(:first-child)',
                            //     editor:  claims2editor
                            // },
                            select: true,
                             buttons: [

                             ],

                 });

                 claim3table=$('#claim3table').dataTable( {
                         ajax: {
                            "url": "{{ asset('/Include/claimsheet.php') }}",
                            "data": {
                                "UserId": {{ $me->UserId }},
                                "Status": "%Rejected%"
                            }
                          },
                          columnDefs: [{ "visible": false, "targets": [1,2] },{"className": "dt-center", "targets": "_all"}],
                          responsive: false,
                          colReorder: false,
                          dom: "Brt",
                          bPaginate: false,
                          bAutoWidth: false,
                          aaSorting:false,
                          rowId:"claimsheets.Id",
                          fnInitComplete: function(oSettings, json) {

                            $('#myrejectedclaimtab').html("My Rejected Claim" + "[" + claim3table.api().rows().count() +"]")

                           },
                         columns: [
                           {
                              sortable: false,
                              "render": function ( data, type, full, meta ) {
                                  return '<a href="myclaim/'+full.claimsheets.Id+'" >View</a>';
                              }
                          },
                           { data: "claimsheets.Id"},
                           { data: "claimsheets.UserId"},
                           { data: "claimsheets.Claim_Sheet_Name"},
                           { data: "claimsheets.Remarks"},
                           { data: "claimsheets.Status"},
                           { data: "claimsheets.created_at"}
                         ],

                        // keys: {
                        //     columns: ':not(:first-child)',
                        //     editor:  claims3editor
                        // },
                        select: true,
                         buttons: [

                         ],

             });

             // Activate an inline edit on click of a table cell
                   $('#claimtable').on( 'click', 'tbody td', function (e) {

                         claimseditor.inline( this, {
                        onBlur: 'submit'
                       } );
                   } );

             // Activate an inline edit on click of a table cell
                   $('#claim2table').on( 'click', 'tbody td', function (e) {
                         claims2editor.inline( this, {
                        onBlur: 'submit'
                       } );
                   } );

           // Activate an inline edit on click of a table cell
                 $('#claim3table').on( 'click', 'tbody td', function (e) {
                       claims3editor.inline( this, {
                      onBlur: 'submit'
                     } );
                 } );

        } );

      </script>

@endsection

@section('content')

    <div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
      <h1>
        My Claim
        <small>My Workplace</small>
      </h1>
      <ol class="breadcrumb">
        <li><a href="{{ url('/home') }}"><i class="fa fa-dashboard"></i> Home</a></li>
        <li><a href="#">My Workplace</a></li>
        <li class="active">My Claim</li>
      </ol>
    </section>

    <!-- Main content -->
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

        <div class="modal modal-warning fade" id="warning-alert">
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

        <div class="col-md-12">
          <div class="nav-tabs-custom">
            <ul class="nav nav-tabs">
              <li class="active"><a href="#mypendingclaim" data-toggle="tab" id="mypendingclaimtab">My Pending Claim</a></li>
              <li><a href="#myapprovedclaim" data-toggle="tab" id="myapprovedclaimtab">My Approved Claim</a></li>
              <li><a href="#myrejectedclaim" data-toggle="tab" id="myrejectedclaimtab">My Rejected Claim</a></li>
              {{-- <li><a href="#timesheetform" data-toggle="tab">Timesheet Form</a></li> --}}
            </ul>

            <div class="tab-content">
              <div class="active tab-pane" id="mypendingclaim">
                <table id="claimtable" class="display" cellspacing="0" width="100%" padding="30px" style="font-size: 13px;">
                    <thead>
                        {{-- prepare header search textbox --}}

                        <tr>
                          @foreach($myclaim as $key=>$value)

                            @if ($key==0)
                              <td>Action</td>
                              @foreach($value as $field=>$value)
                                  <td>{{ $field }}</td>
                              @endforeach

                            @endif

                          @endforeach
                        </tr>
                    </thead>
                    <tbody>

                      <?php $i = 0; ?>
                      @foreach($myclaim as $claim)

                        <tr id="row_{{ $i }}">
                            <td></td>
                            @foreach($claim as $key=>$value)
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

              <div class="tab-pane" id="myapprovedclaim">
                <table id="claim2table" class="display" cellspacing="0" width="100%" padding="30px" style="font-size: 13px;">
                    <thead>
                        {{-- prepare header search textbox --}}

                        <tr>
                          @foreach($myclaim as $key=>$value)

                            @if ($key==0)
                              <td>Action</td>
                              @foreach($value as $field=>$value)
                                  <td/>{{ $field }}</td>
                              @endforeach

                            @endif

                          @endforeach
                        </tr>
                    </thead>
                    <tbody>

                      <?php $i = 0; ?>
                      @foreach($myclaim as $claim)

                        <tr id="row_{{ $i }}">
                            <td></td>
                            @foreach($claim as $key=>$value)
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

              <div class="tab-pane" id="myrejectedclaim">
                <table id="claim3table" class="display" cellspacing="0" width="100%" padding="30px" style="font-size: 13px;">
                    <thead>
                        {{-- prepare header search textbox --}}

                        <tr>
                          @foreach($myclaim as $key=>$value)

                            @if ($key==0)
                              <td>Action</td>
                              @foreach($value as $field=>$value)
                                  <td/>{{ $field }}</td>
                              @endforeach

                            @endif

                          @endforeach
                        </tr>
                    </thead>
                    <tbody>

                      <?php $i = 0; ?>
                      @foreach($myclaim as $claim)

                            <tr id="row_{{ $i }}">
                                <td></td>
                                @foreach($claim as $key=>$value)
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


            {{-- </div> --}}

              </div>
          </div>
          <!-- /.nav tab content -->
        </div>
        <!-- /.av-tabs-custom -->

      </div>
      <!-- /.row -->
    </section>
    <!-- /.content -->
  </div>
  <!-- /.content-wrapper -->
  <footer class="main-footer">
    <div class="pull-right hidden-xs">
      <b>Version</b> 2.0.1
    </div>
    <strong>Copyright &copy; 2014-2016 <a href="http://www.softoya.com">TrackerOnTheGo</a>.</strong> All rights
    reserved.
  </footer>

</script>

<script>
  $(function () {

    //Initialize Select2 Elements
    $(".select2").select2();

});

</script>

@endsection
