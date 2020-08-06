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

    <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/fixedcolumns/3.2.2/css/fixedColumns.dataTables.min.css">
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

      .profile-user-dt-img{
        width: 80px;
        height: 100px;
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

      <script type="text/javascript" language="javascript" src="https://cdn.datatables.net/fixedcolumns/3.2.2/js/dataTables.fixedColumns.min.js"></script>
      <script type="text/javascript" language="javascript" src="{{ asset('/plugin/js/dataTables.editor.min.js') }}"></script>
      <script type="text/javascript" language="javascript" src="{{ asset('/plugin/datatables/extensions/Autocomplete/editor.autoComplete.js') }}"></script>
      <script type="text/javascript" language="javascript" src="{{ asset('/plugin/datatables/extensions/Select2/editor.select2.js') }}"></script>


      {{-- <script type="text/javascript" language="javascript" src="{{ asset('/plugin/examples/resources/syntax/shCore.js') }}"></script>
      <script type="text/javascript" language="javascript" src="{{ asset('/plugin/examples/resources/demo.js') }}"></script>
      <script type="text/javascript" language="javascript" src="{{ asset('/plugin/examples/resources/editor-demo.js') }}"></script> --}}

      <script type="text/javascript" language="javascript" src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.4.0/Chart.bundle.min.js"></script>


      <script type="text/javascript" language="javascript" class="init">

          var asInitVals = new Array();
          var loantable;
          var editor
          var userid;

          $(document).ready(function() {

            editor = new $.fn.dataTable.Editor( {
                    ajax: {
                       "url": "{{ asset('/Include/staffloan.php') }}"
                     },
                    table: "#loantable",
                    idSrc: "staffloans.Id",
                    formOptions: {
                         bubble: {
                             submit: 'allIfChanged'
                         }
                     },
                    fields: [
                      {
                             label: "Staff Name:",
                             name: "staffloans.UserId",
                             type:  'select2',
                             ipOpts: [
                                 @foreach($users as $user)
                                     { label :"{{$user->Name}}", value: "{{$user->Id}}" },
                                 @endforeach
                               ]

                     },{
                                     label: "Purpose:",
                                     name: "staffloans.Purpose",
                                     type:"textarea"
                      },{
                                  label: "Date:",
                                  name: "staffloans.Date",
                                  type:   'datetime',
                                  format: 'DD-MMM-YYYY',
                                  attr: {
                                    autocomplete: "off"
                                  }
                      },{
                                  label: "Date_Approved:",
                                  name: "staffloanstatuses.update_at",
                                  type:   'datetime',
                                  format: 'DD-MMM-YYYY',
                                  attr: {
                                    autocomplete: "off"
                                  }
                      },{
                                  label: "Total Requested:",
                                  name: "staffloans.Total_Requested",
                                  attr: {
                                     type: "number"
                                   }

                       },{
                                  label: "Total Appproved:",
                                  name: "staffloans.Total_Approved",
                                  attr: {
                                     type: "number"
                                   }

                       }
                       ,{
                                 label: "Approver:",
                                 name: "staffloans.Approver",
                                 type:"textarea"

                     }
                     ,{
                                  label: "created_at:",
                                  name: "staffloans.created_at",
                                  // type: "hidden",
                                  def: "{{ date("Y-m-d H:i:s") }}"


                       }
                       ,{
                                  label: "updated_at:",
                                  name: "staffloans.updated_at",
                                  type: "hidden",
                                  def: "{{ $me->UserId }}"


                       }

                    ]
            } );

            // $('#loantable').on( 'click', 'tbody td:not(:first-child)', function (e) {
            //       editor.inline( this, {
            //         onBlur: 'submit',
            //         submit: 'allIfChanged'
            //     } );
            // } );


                               loantable=$('#loantable').dataTable( {
                                 ajax: {
                                    "url": "{{ asset('/Include/staffloan.php') }}",
                                    data:{
                                      end:"{{$end}}"
                                    }
                                  },
                                       columnDefs: [{ "visible": false, "targets": [2] },{"className": "dt-center", "targets": "_all"}],
                                       responsive: false,
                                       sScrollX: "100%",
                                       bAutoWidth: true,
                                       sScrollY: "100%",
                                       dom: "Bflrtip",
                                       lengthMenu: [[10, 25, 50, -1], [10, 25, 50, "All"]],
                                       bScrollCollapse: true,
                                       rowId:"staffloans.Id",
                                       fnInitComplete: function(oSettings, json) {

                                         var rows=this.api().rows( { search: 'applied' } ).data().toArray();
                                         var total_amount=0;
                                         var total_paid=0;

                                         for (var i = 0; i < rows.length; i++) {
                                             total_amount=total_amount+parseFloat(rows[i].staffloans.Amount);
                                             if (! isNaN(rows[i].paid_month.Total) && rows[i].paid_month.Total) {
                                                total_paid=total_paid+parseFloat(rows[i].paid_month.Total);
                                             }

                                         }

                                         $("#total_loan").html("<br>RM " + parseFloat(total_amount.toFixed(2)).toLocaleString("en"));
                                         $("#total_paid").html("<br>RM " + parseFloat(total_paid.toFixed(2)).toLocaleString("en"));
                                        },

                                       columns: [
                                                {  data: null, "render":"", title:"No"},
                                                {
                                                   sortable: false,
                                                   "render": function ( data, type, full, meta ) {
                                                       @if ($me->Edit_User)
                                                          return '<a href="staffloans/'+full.staffloans.Id+'" alt="View" title="View"><i class="fa fa-eye fa-2x"></i> </a>';
                                                       @else
                                                          return '-';
                                                       @endif

                                                   }
                                               },
                                              { data: "staffloans.Id", title:"ID"},
                                              { data: "users.StaffId" , title:"Staff ID"},
                                              {data:'users.Name', editField: "staffloans.UserId",title:"Name"},
                                              { data: "users.Department", title:"Department"},
                                              { data: "staffloans.Purpose", title:"Purpose"},
                                              { data: "staffloans.Date", title:"Date"},
                                              { data: "staffloanstatuses.update_at", title:"Date_Approved"},
                                              { data: "staffloans.Total_Requested", title:"Total Requested"},
                                              { data: "staffloans.Total_Approved", title:"Total Approved"},
                                              { data: "paid_month.Total", title:"Total_Paid"},
                                              { title: "Outstanding Balance", title:"Outstanding Balance",
                                              "render": function ( data, type, full, meta ) {

                                                     if (full.paid_month.Total) {
                                                       return (parseFloat(full.staffloans.Total_Approved)-parseFloat(full.paid_month.Total)).toFixed(2);

                                                     } else {
                                                       return (parseFloat(full.staffloans.Total_Approved)).toFixed(2);
                                                     }

                                              }},
                                              {data:'us.Name', editField: "staffloans.Approver",title:"Approver"},
                                              // { data: "a.Total", title: "Total Paid"},
                                              // { data: "paid_month.Total", title: "Total Paid Month"},
                                              // { title: "Outstanding Balance", title:"Outstanding Balance",
                                              // "render": function ( data, type, full, meta ) {

                                              //       //  if (full.paid_month.Total) {
                                              //       //    return (parseFloat(full.staffloans.Amount)-parseFloat(full.paid_month.Total)).toFixed(2);

                                              //       //  } else {
                                              //       //    return (parseFloat(full.staffloans.Amount)).toFixed(2);
                                              //       //  }

                                              // }},
                                              { data: "staffloans.created_at", title: "Created At"},
                                              { data: "staffloans.updated_at", title: "Updated At"}

                                       ],
                                       autoFill: {
                                          editor:  editor
                                      },
                                       select: true,
                                       buttons: [
                                         // {
                                         //   text: 'New Record',
                                         //   action: function ( e, dt, node, config ) {
                                         //       // clearing all select/input options
                                         //       editor
                                         //          .create( false )
                                         //          .set( 'staffloan.created_at', "{{ date("Y-m-d H:i:s") }}" )
                                         //          .submit();
                                         //   },
                                         // },
                                         { extend: "create", text: 'New Record', editor: editor },
                                         { extend: "edit", text: 'Edit', editor: editor },
                                         { extend: "remove", editor: editor },
                                         {
                                                 extend: 'collection',
                                                 text: 'Export',
                                                 buttons: [
                                                         'excel',
                                                         'csv'
                                                 ]
                                         }

                                       ],

                           });

                           loantable.api().on( 'order.dt search.dt', function () {
                               loantable.api().column(0, {search:'applied', order:'applied'}).nodes().each( function (cell, i) {
                                   cell.innerHTML = i+1;
                               } );
                           } ).draw();

                           $("thead input").keyup ( function () {

                                   /* Filter on the column (the index) of this element */

                                   if ($('#loantable').length > 0)
                                   {

                                       var colnum=document.getElementById('loantable').rows[0].cells.length;

                                       if (this.value=="[empty]")
                                       {

                                          loantable.fnFilter( '^$', this.name,true,false );
                                       }
                                       else if (this.value=="[nonempty]")
                                       {

                                          loantable.fnFilter( '^(?=\\s*\\S).*$', this.name,true,false );
                                       }
                                       else if (this.value.startsWith("!")==true && this.value.length>1)
                                       {

                                          loantable.fnFilter( '^(?'+ this.value +').*', this.name,true,false );
                                       }
                                       else if (this.value.startsWith("!")==false)
                                       {
                                           loantable.fnFilter( this.value, this.name,true,false );
                                       }
                                   }


                           } );


                            loantable.api().on( 'order.dt search.dt', function () {
                                loantable.api().column(0, {search:'applied', order:'applied'}).nodes().each( function (cell, i) {
                                    cell.innerHTML = i+1;
                                } );
                            } ).draw();

                       } );

               	</script>

@endsection

@section('content')

    <div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
      <h1>
        Staff Loan
        <small>Human Resource</small>
      </h1>
      <ol class="breadcrumb">
        <li><a href="{{ url('/home') }}"><i class="fa fa-dashboard"></i> Home</a></li>
        <li><a href="#">Human Resource</a></li>
        <li class="active">Staff Loan</li>
      </ol>
    </section>

    <!-- Main content -->
    <section class="content">
      <div class="row">
        <div class="col-xs-12">
          <div class="box">
          <div class="row">
                  <br>

                  <div class="col-md-3">
                  <div class="input-group">
                    <div class="input-group-addon">
                      <i class="fa fa-clock-o"></i>
                    </div>
                    <input type="text" class="form-control" id="range" name="range" placeholder="End Date">

                  </div>
                </div>


                <div class="col-md-6">
                    <div class="input-group">
                      <button type="button" class="btn btn-success btn" data-toggle="modal" onclick="refresh();">Refresh</button>
                    </div>
                </div>
                <label></label>
              </div>
            <!-- /.box-header -->
            <div class="box-body">
              <div class="row">
                <div class="col-md-3">
                  <h4 class="" >Total Loan : <i><span id='total_loan'>RM0.00</span></i></h4>
                </div>

                <div class="col-md-3">
                  <h4 class="" >Total Paid : <i><span class="" id='total_paid'>0</span></i></h4>
                </div>
              </div>
                <table id="loantable" class="loantable" cellspacing="0" width="100%" padding="30px" style="font-size: 13px;">
				            <thead>

                      <tr class="search">

                        @foreach($loans as $key=>$values)

                          @if ($key==0)

                            <?php $i = 0; ?>

                            @foreach($values as $field=>$a)
                                @if ($i==0)
                                  <th align='center'><input type='hidden' class='search_init' name='{{$i}}' placemark='{{$a}}'></th>
                                @else
                                  <th align='center'><input type='text' class='search_init' name='{{$i}}'  placemark='{{$a}}'></th>
                                @endif

                                <?php $i ++; ?>
                            @endforeach

                            <th align='center'><input type='text' class='search_init' name='{{$i}}'  placemark='{{$a}}'></th>
                            <th align='center'><input type='text' class='search_init' name='{{$i}}'  placemark='{{$a}}'></th>

                          @endif

                        @endforeach

                      </tr>


                        <tr>
                          @foreach($loans as $key=>$value)

                            @if ($key==0)
                              <td></td>
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
                      @foreach($loans as $identity)

                        <tr id="row_{{ $i }}">
                            <td></td>
                            <td></td>
                            @foreach($identity as $key=>$value)
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
            <!-- /.box-body -->
          </div>
          <!-- /.box -->
          <!-- /.box -->
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
      <b>Version</b> 2.0.1
    </div>
    <strong>Copyright &copy; 2014-2016 <a href="http://www.softoya.com">TrackerOnTheGo</a>.</strong> All rights
    reserved.
  </footer>


  <script>

$(function () {

  $('#range').daterangepicker({locale: {
    format: 'DD-MMM-YYYY',
  },    singleDatePicker:true,
  startDate: "{{$end}}",
});

});

function refresh()
{
  var d=$('#range').val();
  var arr = d.split(" - ");

  window.location.href ="{{ url("/staffloan")}}/"+arr[0];

}

</script>





@endsection
