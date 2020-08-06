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


      {{-- <script type="text/javascript" language="javascript" src="{{ asset('/plugin/examples/resources/syntax/shCore.js') }}"></script>
      <script type="text/javascript" language="javascript" src="{{ asset('/plugin/examples/resources/demo.js') }}"></script>
      <script type="text/javascript" language="javascript" src="{{ asset('/plugin/examples/resources/editor-demo.js') }}"></script> --}}

      <script type="text/javascript" language="javascript" class="init">

          var asInitVals = new Array();
          var presavingtable;
          var editor
          var userid;

          $(document).ready(function() {

            editor = new $.fn.dataTable.Editor( {
                    ajax: {
                       "url": "{{ asset('/Include/presaving.php') }}",
                       "data": {
                         "Year": "{{ $year }}"
                       }
                     },
                    table: "#presavingtable",
                    idSrc: "presaving.Id",
                    formOptions: {
                         bubble: {
                             submit: 'allIfChanged'
                         }
                     },
                    fields: [
                      {
                             label: "Staff Name:",
                             name: "presaving.UserId",
                             type:  'select2',
                             opts: {
                              data : [
                                @foreach($users as $department => $dept_users)
                                {
                                  text: "{{ $department }}",
                                  children: [
                                  @foreach($dept_users as $user)
                                  {
                                      id: {{ $user->Id }},
                                      text: "{{ $user->Name }}"
                                  },
                                  @endforeach
                                  ]
                                },
                                @endforeach
                              ]
                             },
                     },{
                                 label: "Presaving_Start_On:",
                                 name: "presaving.Presaving_Start_On",
                                 type:   'datetime',
                                 format: 'DD-MMM-YYYY',
                                 attr: {
                                  autocomplete: "off"
                                 }
                     },{

                                 label: "Presaving_End_Date:",
                                 name: "presaving.Presaving_End_Date",
                                 type:   'datetime',
                                 format: 'DD-MMM-YYYY',
                                 attr: {
                                  autocomplete: "off"
                                 }
                     },{
                                 label: "From:",
                                 name: "presavingrecords.From",
                                 type:   'date',
                                 // format: 'DD-MMM-YYYY',
                                 opts: {
                                      format: 'MM yyyy',
                                      viewMode: "months", //this
                                      minViewMode: "months",//and this
                                      autoclose: true

                                 }
                     },{
                                 label: "Amount:",
                                 name: "presaving.Presaving_Monthly_Amount",
                                 type:   'text',
                                 opts: {
                                      min: '1',
                                      steps: '0.1'
                                 }
                     },{
                                 label: "Presaving_Scheme:",
                                 name: "presaving.Presaving_Scheme",
                                 type:'textarea'
                     },{
                                 label: "created_by:",
                                 name: "presaving.created_by",
                                 type:'hidden',
                                 def:"{{$me->UserId}}"
                     }

                    ]
            } );

            // $('#presavingtable').on( 'click', 'tbody td:not(:first-child)', function (e) {

            //       editor.inline( this, {
            //         onBlur: 'submit',
            //         submit: 'allIfChanged'
            //     } );

            // } );
                         presavingtable=$('#presavingtable').dataTable( {
                                 ajax: {
                                    "url": "{{ asset('/Include/presaving.php') }}",
                                    "data": {
                                      "Year": "{{ $year }}"
                                    }
                                  },
                                       columnDefs: [{ "visible": false, "targets": [2,10,11,12,13] },{"className": "dt-center", "targets": "_all"}],
                                       responsive: false,
                                       sScrollX: "100%",
                                       bAutoWidth: true,
                                       sScrollY: "100%",
                                       dom: "Blfrtip",
                                       bScrollCollapse: true,
                                       lengthMenu: [[10, 25, 50, -1], [10, 25, 50, "All"]],
                                       rowId:"presaving.Id",
                                       fnInitComplete: function(oSettings, json) {

                                         var rows=this.api().rows( { search: 'applied' } ).data().toArray();
                                         var total_saving=0;
                                         var total_withdraw=0;
                                         var total_balance=0;

                                         for (var i = 0; i < rows.length; i++) {
                                          console.log(rows[i]);
                                              if (rows[i].a.Total !== null) {
                                                  total_saving=total_saving+parseFloat(rows[i].a.Total);
                                              }

                                             if (rows[i].b.Total !== null) {
                                               total_withdraw=total_withdraw+parseFloat(rows[i].b.Total);
                                             }
                                         }

                                         $("#total_saving").html("<br>RM " + parseFloat(total_saving.toFixed(2)).toLocaleString("en"));
                                         $("#total_withdraw").html("<br>RM " + parseFloat(total_withdraw.toFixed(2)).toLocaleString("en"));
                                         $("#total_balance").html("<br>RM " + parseFloat(total_saving.toFixed(2) - total_withdraw.toFixed(2)).toLocaleString("en"));
                                       },
                                       columns: [
                                                {  data: null, "render":"", title:"No"},
                                                {
                                                   sortable: false,
                                                   "render": function ( data, type, full, meta ) {
                                                       @if ($me->Edit_User)
                                                          return '<a href="{{url('presavingrecord')}}/'+full.presaving.Id+'/{{$year}}" target="_blank" alt="View" title="View"><i class="fa fa-eye fa-2x"></i> </a>';
                                                       @else
                                                          return '-';
                                                       @endif

                                                   }
                                               },
                                              { data: "presaving.Id", title:"ID"},
                                              { data: "users.StaffId" , title:"Staff ID"},
                                              {data:'users.Name', editField: "presaving.UserId",title:"Name"},
                                              { data: "users.Department", title:"Department"},
                                              { data: "users.Position", title:"Position"},
                                              { data: "users.Joining_Date", title:"Joined_Date"},
                                              { data: "users.Nationality", title:"Nationality"},
                                              { data: "users.Passport_No", title:"Passport_No"},


                                              { data: "presaving.Presaving_Scheme", title:"Presaving Scheme"},
                                              { data: "presaving.Presaving_Start_On", title:"Presaving Start On"},
                                              { data: "presaving.Presaving_End_Date", title:"Presaving End Date"},
                                              { data: "presaving.Presaving_Monthly_Amount", title:"Monthly Amount"},

                                              { data: "a.Total", title: "Total Saving"},
                                              { data: "b.Total", title: "Total Withdraw"},
                                              { data: "Carry Forward", title: "Carry Forward",
                                                "render": function ( data, type, full, meta ) {

                                                       return full.c.Total-full.d.Total;

                                                     }
                                              },
                                              { title: "Total Balance",
                                                "render": function ( data, type, full, meta ) {

                                                       return (full.c.Total-full.d.Total)+(full.a.Total-full.b.Total);

                                                     }
                                              },

                                              { data: "presaving.created_at", title: "Created At"},
                                              { data: "presaving.updated_at", title: "Updated At"},
                                              { data: "creator.Name", editField: "presaving.created_by", title:"Created By" }

                                       ],
                                       autoFill: {
                                          editor:  editor
                                      },
                                       select: true,
                                       buttons: [
                                          { extend: "create", text: 'New Record', editor: editor },
                                          { extend: "edit", editor: editor },

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

                           presavingtable.api().on( 'order.dt search.dt', function () {
                               presavingtable.api().column(0, {search:'applied', order:'applied'}).nodes().each( function (cell, i) {
                                   cell.innerHTML = i+1;
                               } );
                           } ).draw();

                           $("thead input").keyup ( function () {

                                   /* Filter on the column (the index) of this element */

                                   if ($('#presavingtable').length > 0)
                                   {

                                       var colnum=document.getElementById('presavingtable').rows[0].cells.length;

                                       if (this.value=="[empty]")
                                       {

                                          presavingtable.fnFilter( '^$', this.name,true,false );
                                       }
                                       else if (this.value=="[nonempty]")
                                       {

                                          presavingtable.fnFilter( '^(?=\\s*\\S).*$', this.name,true,false );
                                       }
                                       else if (this.value.startsWith("!")==true && this.value.length>1)
                                       {

                                          presavingtable.fnFilter( '^(?'+ this.value +').*', this.name,true,false );
                                       }
                                       else if (this.value.startsWith("!")==false)
                                       {
                                           presavingtable.fnFilter( this.value, this.name,true,false );
                                       }
                                   }


                           } );


                            presavingtable.api().on( 'order.dt search.dt', function () {
                                presavingtable.api().column(0, {search:'applied', order:'applied'}).nodes().each( function (cell, i) {
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
        Presaving Scheme
        <small>Human Resource</small>
      </h1>
      <ol class="breadcrumb">
        <li><a href="{{ url('/home') }}"><i class="fa fa-dashboard"></i> Home</a></li>
        <li><a href="#">Human Resource</a></li>
        <li class="active">Presaving Scheme</li>
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

                <div class="col-sm-1">
                  <h4 class="" >Year : </h4>

                 </div>

                <div class="col-sm-1">

                  <div class="form-group">

                    <select class="form-control select2" id="Year" name="Year" style="width: 100%;">

                      <option <?php if($year == '2018') echo ' selected="selected" '; ?>>2018</option>
                      <option <?php if($year == '2019') echo ' selected="selected" '; ?>>2019</option>
                      <option <?php if($year == '2020') echo ' selected="selected" '; ?>>2020</option>
                    </select>
                  </div>
                 </div>

                 <div class="col-md-6">
                   <div class="input-group">
                     <button type="button" class="btn btn-success btn" data-toggle="modal" onclick="refresh();">Refresh</button>
                   </div>
                 </div>

              </div>

                <div class="row">
                  <div class="col-md-3">
                    <h4 class="" >Total Saving : <i><span id='total_saving'>0</span></i></h4>
                  </div>

                  <div class="col-md-3">
                    <h4 class="" >Total Withdraw : <i><span class="" id='total_withdraw'>0</span></i></h4>
                  </div>

                  <div class="col-md-3">
                    <h4 class="" >Total Balance : <i><span class="" id='total_balance'>0</span></i></h4>
                  </div>
                </div>

                <table id="presavingtable" class="presavingtable" cellspacing="0" width="100%" padding="30px" style="font-size: 13px;">
				            <thead>

                      <tr class="search">

                        @foreach($presaving as $key=>$values)

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
                          @foreach($presaving as $key=>$value)

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
                      @foreach($presaving as $identity)

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

    function refresh()
    {

      year=$('[name="Year"]').val();

      window.location.href ="{{ url("/presaving") }}/"+year;

    }

  </script>

@endsection
