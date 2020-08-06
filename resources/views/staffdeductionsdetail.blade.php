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
      {{-- <script type="text/javascript" language="javascript" src="{{ asset('/plugin/examples/resources/syntax/shCore.js') }}"></script>
      <script type="text/javascript" language="javascript" src="{{ asset('/plugin/examples/resources/demo.js') }}"></script>
      <script type="text/javascript" language="javascript" src="{{ asset('/plugin/examples/resources/editor-demo.js') }}"></script> --}}
      @if($me->View_Staff_Deduction_Detail)
      <script type="text/javascript" language="javascript" class="init">

          var asInitVals = new Array();
          var oTable;
          var userid;

          $(document).ready(function() {

                               oTable=$('#usertable').dataTable( {
                                       columnDefs: [{ "visible": false, "targets": [2] },{"className": "dt-center", "targets": "_all"}],
                                       responsive: false,
                                       sScrollX: "100%",
                                       bAutoWidth: true,
                                       sScrollY: "100%",
                                       dom: "Brtip",
                                       bScrollCollapse: true,
                                       columns: [
                                                {  data: null, "render":"", title:"No"},
                                                {
                                                   sortable: false,
                                                   "render": function ( data, type, full, meta ) {
                                                       @if ($me->Edit_User)
                                                          return '<a href="{{url('staffdeductionsrecord')}}/'+full.users.Id+'{{ "/$start/$end" }}'+'" alt="View" title="View"><i class="fa fa-eye fa-2x"></i> </a>';
                                                       @else
                                                          return '-';
                                                       @endif

                                                   }
                                               },
                                              { data: "users.Id"},
                                              { data: "users.StaffId" , title:"Staff ID"},
                                              { data: "users.Name" },
                                              { data: "users.Department" , title:"Department"},
                                              { data: "users.Position" , title:"Position"},
                                              { data: "users.Joining_Date" , title:"Joined Date"},
                                              { data: "users.Nationality" , title:"Nationality"},
                                              { data: "Total_Deductions", title: "Total Deductions"}

                                       ],
                                       select: true,
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

                           $("thead input").keyup ( function () {

                                   /* Filter on the column (the index) of this element */

                                   if ($('#usertable').length > 0)
                                   {

                                       var colnum=document.getElementById('usertable').rows[0].cells.length;

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


                            oTable.api().on( 'order.dt search.dt', function () {
                                oTable.api().column(0, {search:'applied', order:'applied'}).nodes().each( function (cell, i) {
                                    cell.innerHTML = i+1;
                                } );
                            } ).draw();

                       } );

               	</script>
      @endif
@endsection

@section('content')

    <div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
      <h1>
        Staff Deductions
        <small>Staff</small>
      </h1>
      <ol class="breadcrumb">
        <li><a href="{{ url('/home') }}"><i class="fa fa-dashboard"></i> Home</a></li>
        <li><a href="#">Human Resource</a></li>
        <li><a href="#">Staff Dashboard</a></li>
        <li><a href="#">Staff Deductions Dashboard</a></li>
        <li class="active">Staff Deductions</li>
      </ol>
    </section>

    <!-- Main content -->
    <section class="content">
      <br>
    <div class="row">
      <div class="col-md-12">
        <div class="box">
          <div class="box-body">
             <div class="row">


                 <div class="col-md-6">
                 <div class="input-group">
                   <div class="input-group-addon">
                     <i class="fa fa-clock-o"></i>
                   </div>
                   <input type="text" class="form-control" id="range" name="range" disabled="true">

                 </div>
               </div>
               <label></label>
             </div>
          </div>
        </div>
      </div>
    </div>
      @if($me->View_Staff_Deduction_Detail)
      <div class="row">
        <div class="col-xs-12">
          <div class="box">
            <!-- /.box-header -->
            <div class="box-body">
                <table id="usertable" class="usertable" cellspacing="0" width="100%" padding="30px" style="font-size: 13px;">
				            <thead>

                      <tr class="search">

                        @foreach($users as $key=>$values)

                          @if ($key==0)

                            <?php $i = 0; ?>

                            @foreach($values as $field=>$a)
                                @if ($i==0 || $i==1)
                                  <th align='center'><input type='hidden' class='search_init' name='{{$i}}' placemark='{{$a}}'></th>
                                @else
                                  <th align='center'><input type='text' class='search_init' name='{{$i}}'  placemark='{{$a}}'></th>
                                @endif

                                <?php $i ++; ?>
                            @endforeach

                            <th align='center'><input type='text' class='search_init' name='{{$i}}'  placemark='{{$a}}'></th>
                            <th align='center'><input type='text' class='search_init' name='{{$i+1}}'  placemark='{{$a}}'></th>

                          @endif

                        @endforeach

                      </tr>


                        <tr>
                          @foreach($users as $key=>$value)

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
                      @foreach($users as $identity)

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
      @endif
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
    format: 'DD-MMM-YYYY'
  },startDate: '{{$start}}',
  endDate: '{{$end}}'});

});

function refresh()
{
  var d=$('#range').val();
  var arr = d.split(" - ");

  window.location.href ="{{ url("/staffdeductions") }}/"+arr[0]+"/"+arr[1];

}
</script>
@endsection
