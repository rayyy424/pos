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

      <script type="text/javascript" language="javascript" src="https://cdn.datatables.net/fixedcolumns/3.2.2/js/dataTables.fixedColumns.min.js"></script>
      <script type="text/javascript" language="javascript" src="{{ asset('/plugin/js/dataTables.editor.min.js') }}"></script>
      <script type="text/javascript" language="javascript" src="{{ asset('/plugin/datatables/extensions/Autocomplete/editor.autoComplete.js') }}"></script>
      <script type="text/javascript" language="javascript" src="{{ asset('/plugin/datatables/extensions/Select2/editor.select2.js') }}"></script>
      <script type="text/javascript" language="javascript" src="https://cdn.datatables.net/plug-ins/1.10.19/api/sum().js"></script>


      {{-- <script type="text/javascript" language="javascript" src="{{ asset('/plugin/examples/resources/syntax/shCore.js') }}"></script>
      <script type="text/javascript" language="javascript" src="{{ asset('/plugin/examples/resources/demo.js') }}"></script>
      <script type="text/javascript" language="javascript" src="{{ asset('/plugin/examples/resources/editor-demo.js') }}"></script> --}}

      <script type="text/javascript" language="javascript" class="init">

          var asInitVals = new Array();
          var ewallettable;
          var editor
          var userid;

          $(document).ready(function() {


                         ewallettable =$('#ewallettable').dataTable( {

                                       columnDefs: [{ "visible": false, "targets": [1] },{"className": "dt-right", "targets": [-2,-3,-4,-5]}],
                                       responsive: false,
                                       sScrollX: "100%",
                                       bAutoWidth: true,
                                       sScrollY: "100%",
                                       dom: "Blfrtip",
                                       bScrollCollapse: true,
                                       lengthMenu: [[10, 25, 50, -1], [10, 25, 50, "All"]],
                                       rowId:"users.Id",
                                       select: true,
                                       fnInitComplete: function(oSettings, json) {
                                         var topup = 0.0;
                                         var currentopup = 0.0;
                                         var expenses = 0.0;
                                         var balance = 0.0;
                                         var currentexpen=0.0;
                                         topup = this.api().column(7,{search:"applied"}).data().sum();
                                         expenses = this.api().column(8,{search:"applied"}).data().sum();
                                         currentopup = this.api().column(9,{search:"applied"}).data().sum();
                                         currentexpen=this.api().column(10,{search:"applied"}).data().sum();
                                         balance = topup-expenses;

                                         $("#total_topup").html("RM" + topup.toFixed(2));
                                         $("#current_topup").html("RM" + currentopup.toFixed(2));
                                         $("#total_expenses").html("RM" + expenses.toFixed(2));
                                         $("#current_expenses").html("RM " + currentexpen.toFixed(2));
                                         $("#total_balance").html("RM" + balance.toFixed(2));

                                       },
                                       "drawCallback": function( settings ) {
                                            var topup = 0.0;
                                            var currentopup = 0.0;
                                            var expenses = 0.0;
                                            var balance = 0.0;
                                            topup = this.api().column(7,{search:"applied"}).data().sum();
                                            expenses = this.api().column(8,{search:"applied"}).data().sum();
                                            currentopup = this.api().column(9,{search:"applied"}).data().sum();

                                            balance = topup-expenses;

                                            $("#total_topup").html("RM" + topup.toFixed(2));
                                            $("#current_topup").html("RM" + currentopup.toFixed(2));
                                            $("#total_expenses").html("RM" + expenses.toFixed(2));
                                            $("#total_balance").html("RM" + balance.toFixed(2));
                                       },
                                        columns: [
                                      { data :null, "render":"", title: "No"},
                                      { data: "users.Id", title:"user Id"},
                                      { data: "users.StaffId", title:"Staff Id"},
                                      { data: "users.Company", title:"Company"},
                                      { data: "users.Name", title:"Name"},
                                      { data: "users.Department", title:"Department"},
                                      { data: "users.Position", title:"Position"},
                                      { data: "Total_TopUp", title:"Top Up",
                                        "render": function ( data, type, full, meta ) {

                                            if(!full.Total_TopUp)
                                            {
                                              return 0;
                                            }
                                            else
                                            {
                                              return parseFloat(full.Total_TopUp).toFixed(2);
                                            }
                                        }
                                      },
                                      { data: "Total_Expenses", title:"Total Expenses",
                                        "render": function ( data, type, full, meta ) {
                                            if(full.Total_Expenses == "")
                                            {
                                              return 0;
                                            }
                                            else
                                            {
                                              return parseFloat(full.Total_Expenses).toFixed(2);
                                            }
                                        }},
                                      { data: "Current_TopUp", title:"Current Top Up",
                                        "render": function ( data, type, full, meta ) {

                                            if(!full.Current_TopUp)
                                            {
                                              return 0;
                                            }
                                            else
                                            {
                                              return parseFloat(full.Current_TopUp).toFixed(2);
                                            }
                                        }
                                      },

                                      { data: "Current_Expenses", title:"Expenses",
                                        "render": function ( data, type, full, meta ) {
                                            if(full.Current_Expenses == "")
                                            {
                                              return 0;
                                            }
                                            else
                                            {
                                              return parseFloat(full.Current_Expenses).toFixed(2);
                                            }
                                        }},
                                      { data: "Balance", title:"Balance",
                                        "render": function ( data, type, full, meta ) {
                                            if(full.Total_TopUp == "")
                                            {
                                              return 0;
                                            }
                                            else
                                            {
                                              if(full.Total_Expenses == "")
                                              {
                                                return parseFloat(full.Total_TopUp).toFixed(2);
                                              }
                                              else {
                                                return parseFloat(full.Total_TopUp-full.Total_Expenses).toFixed(2);
                                              }

                                            }
                                        }},
                                      { data: null, title:"View",
                                      "render": function ( data, type, full, meta ) {

                                        var d=$('#range').val();
                                       var arr = d.split(" - ");

                                            return '<a href="{{url('ewalletrecord')}}/'+full.users.Id+'/{{$start}}/{{$end}}" alt="View"<i class="fa fa-eye fa-2x"></i> </a>'
                                      }
                                      }


                    ],
                                       buttons: [
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

                           ewallettable.api().on( 'order.dt search.dt', function () {
                               ewallettable.api().column(0, {search:'applied', order:'applied'}).nodes().each( function (cell, i) {
                                   cell.innerHTML = i+1;
                               } );
                           } ).draw();

                           $("thead input").keyup ( function () {

                                   /* Filter on the column (the index) of this element */

                                   if ($('#ewallettable').length > 0)
                                   {

                                       var colnum=document.getElementById('ewallettable').rows[0].cells.length;

                                       if (this.value=="[empty]")
                                       {

                                          ewallettable.fnFilter( '^$', this.name,true,false );
                                       }
                                       else if (this.value=="[nonempty]")
                                       {

                                          ewallettable.fnFilter( '^(?=\\s*\\S).*$', this.name,true,false );
                                       }
                                       else if (this.value.startsWith("!")==true && this.value.length>1)
                                       {

                                          ewallettable.fnFilter( '^(?'+ this.value +').*', this.name,true,false );
                                       }
                                       else if (this.value.startsWith("!")==false)
                                       {
                                           ewallettable.fnFilter( this.value, this.name,true,false );
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
        e-Wallet
        <small>Sales Management</small>
      </h1>
      <ol class="breadcrumb">
        <li><a href="{{ url('/home') }}"><i class="fa fa-dashboard"></i> Home</a></li>
        <li><a href="#">Sales Management</a></li>
        <li class="active">e-Wallet</li>
      </ol>
    </section>

    <!-- Main content -->
    <section class="content">
      <div class="row">
        <div class="col-xs-12">
          <div class="box">
            <!-- /.box-header -->
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
                   <div class="checkbox">
                     <label><input type="checkbox" id="includeresigned" name="includeresigned" {{ $includeResigned == "true" ? 'checked' : '' }}> Include Resigned</label>
                   </div>
                 </div>

                 <div class="col-md-2">
                     <div class="input-group">
                       <button type="button" class="btn btn-success btn" data-toggle="modal" onclick="refresh();">Refresh</button>
                     </div>
                 </div>
                </div>
                <br>
                <div class="row">
                <div class="col-md-4">
                <div class="box">
                  <div class="box-body">
                    <ul class="list-group list-group-unbordered">
                      @foreach ($expenses as $s)
                      <li class="list-group-item">
                        <b>{{$s->Expenses_Type}}</b> : <p class="pull-right"><a href="{{ url('/ewalletdetails/')}}/{{$s->Expenses_Type}}/{{$start}}/{{$end}} " target="_blank"><i>RM {{ $s->total }}</i></a>
                      </li>
                      @endforeach
                    </ul>
                  </div>
                </div>
              </div>
              <div class="col-md-8">
                <table class="table">
                  <tr align='center'>
                    <th>Total</th>
                    <th>Fion Top Up to Staff</th>
                    <th>Staff Resits</th>
                    <th>Fion Verified</th>
                    <th>Submission Acct</th>
                    <th>Account Payback</th>
                    <th>Reimbursed to Petty Cash</th>
                    <th>Cash Available on Fion Hand</th>
                  </tr>
                  <tr align="center">
                    <td><a href="{{url('ewalletsummarybreakdown')}}/{{$start}}/{{$end}}/Total" target="_blank">{{number_format((float) $fion[0]->cash_on_hand,2)}}</a></td>
                    <td><a href="{{url('ewalletsummarybreakdown')}}/{{$start}}/{{$end}}/Fion Top Up to Staff" target="_blank">{{number_format((float) $fiontopup[0]->Total_TopUp,2)}}</a></td>
                    <td><a href="{{url('ewalletsummarybreakdown')}}/{{$start}}/{{$end}}/Staff Resits" target="_blank">{{number_format((float) $collection->sum('Current_Expenses'),2)}}</a></td>
                    <td><a href="{{url('ewalletsummarybreakdown')}}/{{$start}}/{{$end}}/Verified" target="_blank">{{ $verified->verified }}</a></td>
                    <td><a href="{{url('ewalletsummarybreakdown')}}/{{$start}}/{{$end}}/Submission Acct" target="_blank">{{number_format((float) $fion[0]->submission_acct+$verified->verified ,2)}}</a></td>
                    <td>{{number_format( $fion[0]->reimburse - ($fion[0]->submission_acct+$verified->verified),2)}}</td>
                    <td><a href="{{url('ewalletsummarybreakdown')}}/{{$start}}/{{$end}}/Reimbursed to Fion" target="_blank">{{number_format((float) $fion[0]->reimburse,2)}}</a></td>
                    <td>
                      {{number_format((float)$fion[0]->cash_on_hand - (float) $fiontopup[0]->Total_TopUp
                        + (float) $fion[0]->reimburse,2)
                      }}
                    </td>
                  </tr>
                </table>
              </div>
            </div>
                <br>
                <div class="row">
                  <div class="col-md-3">
                    <h4 class="" >Total Top-up : <i><span id='total_topup'>0</span></i></h4>
                  </div>

                  <div class="col-md-3">
                    <h4 class="" >Current Top-up : <i><span id='current_topup'>0</span></i></h4>
                  </div>

                  <div class="col-md-3">
                    <h4 class="" >Total Expenses : <i><span class="" id='total_expenses'>0</span></i></h4>
                  </div>
                  <div class="col-md-3">
                    <h4 class="" >Current Expenses : <i><span class="" id='current_expenses'>0</span></i></h4>
                  </div>
                  <div class="col-md-3">
                    <h4 class="" >Total Balance : <i><span class="" id='total_balance'>0</span></i></h4>
                  </div>
                </div>

                <table id="ewallettable" class="ewallettable" cellspacing="0" width="100%" padding="30px" style="font-size: 13px;">
				            <thead>
                                <tr class="search">
                                @foreach($ewallet as $key=>$value)

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
                                    @foreach($ewallet as $key=>$value)

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
                                @foreach($ewallet as $delivery)

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
      var includeresigned=$('#includeresigned').is(':checked');

        window.location.href ="{{ url("/ewallet") }}/"+arr[0]+"/"+arr[1]+"/"+includeresigned;
    }

</script>


@endsection
