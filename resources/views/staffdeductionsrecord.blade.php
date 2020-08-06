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
      <script type="text/javascript" language="javascript" src="{{ asset('/plugin/datatables/extensions/Select2/editor.select2.js') }}"></script>

      {{-- chart js --}}
      <script type="text/javascript" language="javascript" src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.4.0/Chart.bundle.min.js"></script>

      <script type="text/javascript" language="javascript" class="init">

         var editor;
         var loantable;

         $(document).ready(function() {

           editor = new $.fn.dataTable.Editor( {
                   ajax: {
                      "url": "{{ asset('/Include/staffdeductionsrecord.php') }}"
                    },
                   table: "#loan",
                   idSrc: "staffdeductions.Id",
                   formOptions: {
                        bubble: {
                            submit: 'allIfChanged'
                        }
                    },
                   fields: [
                    {
                           label: "Deduction Month:",
                           name: "staffdeductions.Month",
                           type:   'select2',
                           def: "{{ date('F') . ' ' . date('Y') }}",
                           options: [
                            @for($yearlist=date('Y')-2; $yearlist<=date('Y')+1; $yearlist++)


                              { label :"January {{ $yearlist }}", value: "January {{ $yearlist }}"},
                              { label :"February {{ $yearlist }}", value: "February {{ $yearlist }}"},
                              { label :"March {{ $yearlist }}", value: "March {{ $yearlist }}"},
                              { label :"April {{ $yearlist }}", value: "April {{ $yearlist }}"},
                              { label :"May {{ $yearlist }}", value: "May {{ $yearlist }}"},
                              { label :"June {{ $yearlist }}", value: "June {{ $yearlist }}"},
                              { label :"July {{ $yearlist }}", value: "July {{ $yearlist }}"},
                              { label :"August {{ $yearlist }}", value: "August {{ $yearlist }}"},
                              { label :"September {{ $yearlist }}", value: "September {{ $yearlist }}"},
                              { label :"October {{ $yearlist }}", value: "October {{ $yearlist }}"},
                              { label :"November {{ $yearlist }}", value: "November {{ $yearlist }}"},
                              { label :"December {{ $yearlist }}", value: "December {{ $yearlist }}"},

                            @endfor
                            ],
                            opts: {
                                // tags: true
                            }


                    },{
                           label: "Date:",
                           name: "staffdeductions.Date",
                           type:   'datetime',
                           def:    function () { return new Date(); },
                           format: 'DD-MMM-YYYY HH:mm'
                    },{
                            label: "Staff Name:",
                            name: "staffdeductions.UserId",
                            type:'hidden',
                            def: {{ $user->Id }}
                    },{
                           label: "Created By:",
                           name: "staffdeductions.created_by",
                           type:'hidden',
                           def:  {{ $me->UserId }}
                   },{
                            label: "Category:",
                            name: "staffdeductions.Type",
                            type:  'select2',
                            options: [
                            { label :"", value: "" },


                            // STAFF LOAN
                            // PRE SAVING DEDUCTION
                            // SUMMONS
                            // ACCIDENT
                            // SHELL CARD
                            // TOUCH N GO
                            // SAFETY SHOES
                            // PETTY CASH SABAH (FKA PETTY CASH CME)
                            // Late
                            // ADVANCE SALARY DEDUCTION
                            // MAX CARD
                            // NIOSH
                            // CIDB CARD
                            // LOSS OF EQUIPMENT
                            // PETTY CASH FION
                            // PAY BACK TO ERIC
                            // DRIVING LICENSE DEDUCTION


                            { label :"STAFF LOAN", value: "STAFF LOAN" },
                            { label :"PRE-SAVING SCHEME", value: "PRE-SAVING SCHEME" },
                            { label :"SUMMONS", value: "SUMMONS" },
                            { label :"ACCIDENT", value: "ACCIDENT" },
                            { label :"SHELL CARD", value: "SHELL CARD" },
                            { label :"TOUCH N GO", value: "TOUCH N GO" },
                            { label :"SAFETY SHOES", value: "SAFETY SHOES" },

                            { label :"PETTY CASH SABAH (FKA PETTY CASH CME)", value: "PETTY CASH SABAH (FKA PETTY CASH CME)" },
                            { label :"Late", value: "Late" },
                            { label :"ADVANCE SALARY DEDUCTION", value: "ADVANCE SALARY DEDUCTION" },
                            { label :"MAX CARD", value: "MAX CARD" },
                            { label :"NIOSH", value: "NIOSH" },
                            { label :"CIDB CARD", value: "CIDB CARD" },
                            { label :"LOSS OF EQUIPMENT", value: "LOSS OF EQUIPMENT" },

                            { label :"PETTY CASH FION", value: "PETTY CASH FION" },
                            { label :"PAY BACK TO ERIC", value: "PAY BACK TO ERIC" },
                            { label :"DRIVING LICENSE DEDUCTION", value: "DRIVING LICENSE DEDUCTION" },

                            ],
                            opts: {
                                tags: true
                            }
                    },{
                               label: "Particular:",
                               name: "staffdeductions.Description",
                               // attr: {
                               //    type: "number"
                               // }
                               type: "textarea"
                    },{
                               label: "Amount:",
                               name: "staffdeductions.Amount",
                               attr: {
                                  type: "number"
                                }
                    },{
                               label: "Final Amount:",
                               name: "staffdeductions.FinalAmount",
                               attr: {
                                  type: "number"
                                }
                    },{
                               label: "created_at:",
                               name: "staffdeductions.created_at",
                               type: "hidden",
                               def: "{{ date("Y-m-d H:i:s") }}"


                    }

                  ]
           } );


           // $('#loan').on( 'click', 'tbody td:not(:first-child)', function (e) {
           //       editor.inline( this, {
           //         onBlur: 'submit',
           //         submit: 'allIfChanged'
           //     } );
           // } );




               loantable = $('#loan').dataTable( {
                 ajax: {
                    "url": "{{ asset('/Include/staffdeductionsrecord.php') }}",
                    "data": {
                      "UserId": "{{ $user->Id }}",
                      "Start": "{{ $start }}",
                      "End": "{{ $end }}",
                    }
                  },
                  rowId:"staffdeductions.Id",
                   dom: "Blrtip",
                   bAutoWidth: true,
                   sScrollY: "100%",
                   sScrollX: "100%",
                   columnDefs: [{ "visible": false, "targets": [1,2] },{"className": "dt-center", "targets": "_all"}],
                   bScrollCollapse: true,
                   fnInitComplete: function(oSettings, json) {

                     var total=0.0;


                      loantable.api().rows().every( function () {
                         var d = this.data();
                         var type=d.staffdeductions.Type;


                            total=total+parseFloat(d.staffdeductions.FinalAmount);

                       } );

                     $("#total").html("RM" + total.toFixed(2));

                    },
                   columns: [
                     {data: null, "render":"", title:"No"},
                     {data:'staffdeductions.Id', title:"Id"},
                     {data:'staffdeductions.UserId', title:"UserId"},
                     {data:'staffdeductions.Month', title:"Month"},
                     {data:'staffdeductions.Date', title:"Date"},
                     {data:'staffdeductions.Type', title:"Category"},
                     {data:'staffdeductions.Description', title:"Particular"},
                     {data:'staffdeductions.Amount', title:"Amount"},
                     {data:'staffdeductions.FinalAmount', title:"Final Amount"},
                     {data:'creator.Name', title:'Created_By'},
                     {data:'staffdeductions.created_at', title:'Created_At'},
                     // {data:'staffdeductions.updated_at', title:'Updated_At'}

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
                           //          .set( 'staffdeductions.UserId', {{ $user->Id }} )
                           //          .set( 'staffdeductions.created_by', {{ $me->UserId }} )
                           //          .set( 'staffdeductions.Year', "{{ $year }}" )

                           //          .set( 'staffdeductions.created_at', "{{ date("Y-m-d H:i:s") }}" )
                           //          .submit();
                           //   },
                           // },
                           {
                                text: `Month <select class="deductionmonth">
                                  <option>All</option>
                                  @foreach($availableMonth as $month)
                                    <option>{{ $month }}</option>
                                  @endforeach
                                </select>`,
                                action: function ( e, dt, node, config ) {
                                  if ($(".deductionmonth").val() == 'All') {
                                    loantable.api().column(3).search('').draw();
                                  } else {
                                    loantable.api().column(3)
                                    .search($(".deductionmonth").val())
                                    .draw();
                                  }

                                }
                           },
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

               editor.on( 'postEdit', function ( e, json, data ) {

                 var total=0.0;

                  loantable.api().rows().every( function () {
                     var d = this.data();
                     var type=d.staffdeductions.Type;


                        total=total+parseFloat(d.staffdeductions.FinalAmount);

                   } );

                 $("#total").html("RM" + total.toFixed(2));

               } );

               loantable.api().on( 'order.dt search.dt', function () {
                   loantable.api().column(0, {search:'applied', order:'applied'}).nodes().each( function (cell, i) {
                       cell.innerHTML = i+1;
                   } );
               } ).draw();

               editor.field( 'staffdeductions.Amount' ).input().on( 'keyup', function () {
                if (editor.field('staffdeductions.Type').val() == 'TOUCH N GO') {
                  editor.field( 'staffdeductions.FinalAmount' ).val( this.value * 5 );
                } else {
                  editor.field( 'staffdeductions.FinalAmount' ).val( this.value );
                }
               });

               editor.field( 'staffdeductions.Type' ).input().on( 'change', function () {
                if (editor.field('staffdeductions.Type').val() == 'TOUCH N GO') {
                  editor.field( 'staffdeductions.FinalAmount' ).val( this.value * 5 );
                } else {
                  editor.field( 'staffdeductions.FinalAmount' ).val( editor.field('staffdeductions.Amount').val() );
                }
               });

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
      {{$user->Name}}
      <small>Staff Deductions</small>
      </h1>

      <ol class="breadcrumb">
      <li><a href="{{ url('/home') }}"><i class="fa fa-dashboard"></i> Home</a></li>
      <li><a href="#">Staff Deductions</a></li>
      <li class="active">{{$user->Name}}</li>
      </ol>
    </section>

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
                   <input type="text" class="form-control" id="range" name="range">

                 </div>
               </div>
               <div class="col-md-3">
                   <div class="input-group">
                     <button type="button" class="btn btn-success btn" data-toggle="modal" onclick="refresh();">Refresh</button>
                   </div>
               </div>
               <label></label>
             </div>
          </div>
        </div>
      </div>
    </div>
      <div class="row">
        <div class="col-md-12">
          <div class="box box-primary">
            <div class="box-body box-profile">
              <div class="col-md-4">
                <ul class="list-group list-group-unbordered">
                  <li class="list-group-item">
                    <b>Name</b> : <p class="pull-right"><i>{{ $user->Name }}</i>
                  </li>

                  <li class="list-group-item">
                    <b>Company</b> : <p class="pull-right"><i>{{ $user->Company }}</i>
                  </li>

                  <li class="list-group-item">
                    <b>Department</b> : <p class="pull-right"><i>{{ $user->Department }}</i>
                  </li>

                </ul>

                {{-- <a href="#" class="btn btn-primary btn-block"><b>Update</b></a> --}}
              </div>

              <div class="col-md-4">
                <ul class="list-group list-group-unbordered">

                  <li class="list-group-item"><b>STAFF LOAN </b> : <p class="pull-right"><i><span>RM{{$total ? $total->Staff_Loan_Total : 0}}</span></i></p></li>
                  <li class="list-group-item"><b>PRE-SAVING SCHEME </b> : <p class="pull-right"><i><span>RM{{$total ? $total->Presaving_Scheme_Total : 0}}</span></i></p></li>
                  <li class="list-group-item"><b>SUMMONS </b> : <p class="pull-right"><i><span>RM{{$total ? $total->Summons_Total : 0}}</span></i></p></li>
                  <li class="list-group-item"><b>ACCIDENT </b> : <p class="pull-right"><i><span>RM{{$total ? $total->Accident_Total : 0}}</span></i></p></li>
                  <li class="list-group-item"><b>SHELL CARD </b> : <p class="pull-right"><i><span>RM{{$total ? $total->Shell_Card_Total : 0}}</span></i></p></li>
                  <li class="list-group-item"><b>TOUCH N GO </b> : <p class="pull-right"><i><span>RM{{$total ? $total->TNG_Total : 0}}</span></i></p></li>
                  <li class="list-group-item"><b>SAFETY SHOES </b> : <p class="pull-right"><i><span>RM{{$total ? $total->Safety_Shoes_Total : 0}}</span></i></p></li>
                  <li class="list-group-item"><b>PETTY CASH SABAH (FKA PETTY CASH CME) </b> : <p class="pull-right"><i><span>RM{{$total ? $total->Petty_Cash_CME_Total : 0}}</span></i></p></li>
                  <li class="list-group-item"><b>LATE </b> : <p class="pull-right"><i><span>RM{{$total ? $total->Late_Total : 0}}</span></i></p></li>
                </ul>
              </div>
              <div class="col-md-4">
                <ul class="list-group list-group-unbordered">
                  <li class="list-group-item"><b>ADVANCE SALARY DEDUCTION </b> : <p class="pull-right"><i><span>RM{{$total ? $total->Advance_Salary_Total : 0}}</span></i></p></li>
                  <li class="list-group-item"><b>MAX CARD </b> : <p class="pull-right"><i><span>RM{{$total ? $total->MAX_CARD_Total : 0}}</span></i></p></li>
                  <li class="list-group-item"><b>NIOSH </b> : <p class="pull-right"><i><span>RM{{$total ? $total->NIOSH_Total : 0}}</span></i></p></li>
                  <li class="list-group-item"><b>CIDB CARD </b> : <p class="pull-right"><i><span>RM{{$total ? $total->CIDB_Total : 0}}</span></i></p></li>
                  <li class="list-group-item"><b>LOSS OF EQUIPMENT </b> : <p class="pull-right"><i><span>RM{{$total ? $total->LOSS_OF_EQ_Total : 0}}</span></i></p></li>
                  <li class="list-group-item"><b>PETTY CASH FION </b> : <p class="pull-right"><i><span>RM{{$total ? $total->Fion_Total : 0}}</span></i></p></li>
                  <li class="list-group-item"><b>PAY BACK TO ERIC </b> : <p class="pull-right"><i><span>RM{{$total ? $total->Eric_Total : 0}}</span></i></p></li>
                  <li class="list-group-item"><b>DRIVING LICENSE DEDUCTION </b> : <p class="pull-right"><i><span>RM{{$total ? $total->License_Total : 0}}</span></i></p></li>
                  <li class="list-group-item"><b>NOT IN RADIUS </b> : <p class="pull-right"><i><span>RM{{$total ? $total->Not_In_Radius : 0}}</span></i></p></li>

                  <li class="list-group-item"><b>TOTAL </b> : <p class="pull-right"><i><span>RM{{$total ? $total->Total : 0}}</span></i></p></li>
                </ul>
              </div>
            </div>
          </div>

        </div>

      </div>

      <div class="row">
              <div class="col-md-12">
                <div class="box">
                  <div class="box-body">
                    <table id="loan" class="loan" cellspacing="0" width="100%" padding="30px" style="font-size: 13px;">
                      <thead>

                        <tr class="search">

                          @foreach($record as $key=>$value)

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

                                <td align='center'><input type='text' class='search_init' name='{{$i}}'  placemark='{{$a}}'></td>

                            @endif

                          @endforeach
                        </tr>

                          <tr>

                            @foreach($record as $key=>$value)

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

                      </tbody>
                        <tfoot></tfoot>
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
  $('.select2').select2();
  $('#range').daterangepicker({locale: {
    format: 'DD-MMM-YYYY'
  },startDate: '{{$start}}',
  endDate: '{{$end}}'});

});

function refresh()
{
  var d=$('#range').val();
  var arr = d.split(" - ");

  window.location.href ="{{ url("/staffdeductionsrecord/$user->Id") }}/"+arr[0]+"/"+arr[1];

}
</script>
@endsection
