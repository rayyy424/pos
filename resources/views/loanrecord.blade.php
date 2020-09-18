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
      <script type="text/javascript" language="javascript" src="{{ asset('/plugin/datatables/extensions/Autocomplete/editor.autoComplete.js') }}"></script>
      {{-- chart js --}}
      <script type="text/javascript" language="javascript" src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.4.0/Chart.bundle.min.js"></script>

      <script type="text/javascript" language="javascript" class="init">

         var editor;
         var loantable;

         $(document).ready(function() {

           editor = new $.fn.dataTable.Editor( {
                   ajax: {
                      "url": "{{ asset('/Include/loanrecord.php') }}"
                    },
                   table: "#loan",
                   idSrc: "repaymentrecords.Id",
                   formOptions: {
                        bubble: {
                            submit: 'allIfChanged'
                        }
                    },
                   fields: [
                           {
                                      label: "StaffLoanId:",
                                      name: "repaymentrecords.StaffLoanId",
                                      type: "hidden",
                                      def: "{{ $id }}"

                           },{
                                     label: "Amount:",
                                     name: "repaymentrecords.Amount",
                                     attr: {
                                        type: "number"
                                      }

                          },{
                                     label: "Payment_Date:",
                                     name: "repaymentrecords.Payment_Date",
                                     type:   'datetime',
                                     format: 'DD-MMM-YYYY',
                                     attr: {
                                      autocomplete: "off"
                                     }

                         },{
                                     label: "created_at:",
                                     name: "repaymentrecords.created_at",
                                     type: "hidden",
                                     def: "{{ date("Y-m-d H:i:s") }}"

                          }

                   ]
           } );


           $('#loan').on( 'click', 'tbody td:not(:first-child)', function (e) {
                 editor.inline( this, {
                   onBlur: 'submit',
                   submit: 'allIfChanged'
               } );
           } );

               loantable = $('#loan').dataTable( {
                 ajax: {
                    "url": "{{ asset('/Include/loanrecord.php') }}",
                    "data": {
                      "Id": "{{ $id }}"
                    }
                  },
                  rowId:"repaymentrecords.Id",
                   dom: "Blrtip",
                   bAutoWidth: true,
                  //  sScrollY: "100%",
                  //  sScrollX: "100%",
                   columnDefs: [{ "visible": false, "targets": [1] },{"className": "dt-center", "targets": "_all"}],
                   bScrollCollapse: true,
                   fnInitComplete: function(oSettings, json) {

                     var totalloan=0.0;
                     var totalpayback=0.0;
                     var totalbalance=0.0;

                     var rows = loantable.api().rows().data();
                     if  (rows.length) {
                        if (rows[0].paid_month.Total) {
                          totalpayback = parseFloat(rows[0].paid_month.Total);

                        }

                     }
                     totalloan={{$staffloan->Amount}};
                      // totalpayback = loantable.api().rows()[0].data().total_paid.Total;

                      totalbalance=totalloan-totalpayback;

                     $("#totalpayback").html("RM" + totalpayback.toFixed(2));
                     $("#totalbalance").html("RM" + totalbalance.toFixed(2));


                    },
                   columns: [
                     {data: null, "render":"", title:"No"},
                     {data:'repaymentrecords.Id', title:"Id"},
                     {data:'repaymentrecords.Payment_Date', title:"Repayment_Date"},
                     {data:'repaymentrecords.Amount', title:"Amount"},
                     {data:'repaymentrecords.created_at', title:'Created_At'},
                     {data:'repaymentrecords.updated_at', title:'Updated_At'}

                   ],
                   autoFill: {
                      editor:  editor
                  },
                   select: {
                           style:    'os',
                           selector: 'tr'
                   },
                   buttons: [
                           // {
                           //   text: 'New Record',
                           //   action: function ( e, dt, node, config ) {
                           //       // clearing all select/input options
                           //       editor
                           //          .create( false )
                           //          .set( 'repaymentrecords.StaffLoanId', {{ $id }} )
                           //          .set( 'repaymentrecords.created_at', "{{ date("Y-m-d H:i:s") }}" )
                           //          .submit();
                           //   },
                           // },
                           { extend: "create", text: "New Record" ,editor: editor },
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

               editor.on( 'postEdit', function ( e, json, data ) {

                 var totalloan=0.0;
                  var totalpayback=0.0;
                  var totalbalance=0.0;

                   loantable.api().rows().every( function () {
                      var d = this.data();
                      totalloan={{$staffloan->Amount}};

                         totalpayback=totalpayback+parseFloat(d.repaymentrecords.Amount);

                    } );

                   totalbalance=totalloan-totalpayback;

                  $("#totalpayback").html("RM" + totalpayback.toFixed(2));
                  $("#totalbalance").html("RM" + totalbalance.toFixed(2));

               } );

               loantable.api().on( 'order.dt search.dt', function () {
                   loantable.api().column(0, {search:'applied', order:'applied'}).nodes().each( function (cell, i) {
                       cell.innerHTML = i+1;
                   } );
               } ).draw();


               $(".loan thead input").keyup ( function () {

                       /* Filter on the column (the index) of this element */
                       if ($('#loan').length > 0)
                       {

                           var colnum=document.getElementById('loan').rows[0].cells.length;

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


             } );

     </script>

      @endsection

@section('content')

  <div class="content-wrapper">
      <!-- Content Header (Page header) -->
    <section class="content-header">
      <h1>
      {{$staffloan->Name}}
      <small>Staff Loan</small>
      </h1>

      <ol class="breadcrumb">
      <li><a href="{{ url('/home') }}"><i class="fa fa-dashboard"></i> Home</a></li>
      <li><a href="{{ url('/staffloan') }}">Staff Loan</a></li>
      <li class="active">{{$staffloan->Name}}</li>
      </ol>
    </section>

    <section class="content">

      <div class="row">

          <div class="col-md-4">

            <div class="box box-primary">

              <div class="box-body box-profile">

                <ul class="list-group list-group-unbordered">
                  <li class="list-group-item">
                    <b>Name</b> : <p class="pull-right"><i>{{ $staffloan->Name }}</i>
                  </li>

                  <li class="list-group-item">
                    <b>Date Approved</b> : <p class="pull-right"><i>{{ $staffloan->Date_Approved }}</i>
                  </li>

                  <li class="list-group-item">
                    <b>Repayment Start On</b> : <p class="pull-right"><i>{{ $staffloan->Repayment_Start_On }}</i>
                  </li>

                  <li class="list-group-item">
                    <b>Loan Amount</b> : <p class="pull-right"><i><span id='totalloan'>RM{{ $staffloan->Amount }}</span></i></p>
                  </li>

                  <li class="list-group-item">
                    <b>Total Pay Back</b> : <p class="pull-right"><i><span id='totalpayback'>RM0.00</span></i></p>
                  </li>

                  <li class="list-group-item">
                    <b>Outstanding Balance</b> : <p class="pull-right"><i><span id='totalbalance'>RM0.00</span></i></p>
                  </li>

                </ul>

                {{-- <a href="#" class="btn btn-primary btn-block"><b>Update</b></a> --}}
              </div>

            </div>
          </div>

      </div>

      <div class="row">

        <div class="col-md-12">


                  <table id="loan" class="loan" cellspacing="0" width="100%" padding="30px" style="font-size: 13px;">
                    <thead>

                      <tr class="search">

                        @foreach($loans as $key=>$value)

                          @if ($key==0)
                            <?php $i = 0; ?>

                            @foreach($value as $field=>$a)
                                @if ($i==0|| $i==1)
                                  <td align='center'><input type='hidden' class='search_init' name='{{$i}}' placemark='{{$a}}'></td>
                                @else
                                  <td align='center'><input type='text' class='search_init' name='{{$i}}'  placemark='{{$a}}'></td>
                                @endif

                                <?php $i ++; ?>
                            @endforeach


                          @endif

                        @endforeach
                      </tr>

                        <tr>

                          @foreach($loans as $key=>$value)

                            @if ($key==0)

                              @foreach($value as $field=>$value)
                                  <td/>{{ $field }}</td>
                              @endforeach

                            @endif

                          @endforeach
                        </tr>
                    </thead>
                      <tbody>

                    </tbody>
                      <tfoot></tfoot>
                  </table>


     </div>

   </div>


   </section>

 </div>

<footer class="main-footer">
  <div class="pull-right hidden-xs">
    <b>Version</b> 1.0.0
  </div>
  <strong>Copyright &copy; 2014-2016 <a href="http://www.softoya.com">TrackerOnTheGo</a>.</strong> All rights
  reserved.
</footer>

@endsection
