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
         var presavingtable;

         $(document).ready(function() {

           editor = new $.fn.dataTable.Editor( {
                   ajax: {
                      "url": "{{ asset('/Include/presavingrecord.php') }}",
                      "data": {
                        "Year": "{{ $year }}",
                          "created_by": "{{ $me->UserId }}"
                      }
                    },
                   table: "#presaving",
                   idSrc: "presavingrecords.Id",
                   formOptions: {
                        bubble: {
                            submit: 'allIfChanged'
                        }
                    },
                   fields: [
                           {
                                      label: "PresavingId:",
                                      name: "presavingrecords.PresavingId",
                                      type: "hidden",
                                      def: "{{ $id }}"

                           },{
                                     label: "Amount:",
                                     name: "presavingrecords.Amount",
                                     attr: {
                                        type: "number"
                                      }

                          },{
                                     label: "Payment_Date:",
                                     name: "presavingrecords.Payment_Date",
                                     type:   'datetime',
                                     format: 'DD-MMM-YYYY',
                                     attr: {
                                      autocomplete: "off"
                                     }
                         },{
                                    label: "Type:",
                                    name: "presavingrecords.Type",
                                    type:  'select2',
                                    options: [
                                        { label :"", value: "" },
                                        { label :"Saving", value: "Saving" },
                                        { label :"Withdraw", value: "Withdraw" },

                                    ],
                        },{
                                   label: "Reason:",
                                   name: "presavingrecords.Reason",
                                   type:   'textarea'
                       },{
                                   label: "created_by:",
                                   name: "presavingrecords.created_by",
                                   type:'hidden',
                                   def:"{{$me->UserId}}"
                       }

                   ]
           } );


           // $('#presaving').on( 'click', 'tbody td:not(:first-child)', function (e) {
           //       editor.inline( this, {
           //         onBlur: 'submit',
           //         submit: 'allIfChanged'
           //     } );
           // } );

               presavingtable = $('#presaving').dataTable( {
                 ajax: {
                    "url": "{{ asset('/Include/presavingrecord.php') }}",
                    "data": {
                      "Id": "{{ $id }}",
                      "Year": "{{ $year }}",
                      "created_by": "{{ $me->UserId }}"
                    }
                  },
                  rowId:"presavingrecord.Id",
                   dom: "Blrtip",
                   bAutoWidth: true,
                   lengthMenu: [[10, 25, 50, -1], [10, 25, 50, "All"]],
                  //  sScrollY: "100%",
                  //  sScrollX: "100%",
                   columnDefs: [{ "visible": false, "targets": [1] },{"className": "dt-center", "targets": "_all"}],
                   bScrollCollapse: true,
                   fnInitComplete: function(oSettings, json) {

                     var totalsaving=0.0;
                      var totalwithdraw=0.0;
                      var totalbalance=0.0;
                      var carryforward={{$carryforward}};

                      presavingtable.api().rows().every( function () {
                         var d = this.data();
                         var type= d.presavingrecords.Type;

                         if(type.includes("Saving")==true)
                         {
                           totalsaving=totalsaving+parseFloat(d.presavingrecords.Amount);
                         }
                         else if(type.includes("Withdraw")==true)
                         {
                           totalwithdraw=totalwithdraw+parseFloat(d.presavingrecords.Amount);
                         }

                       } );

                       totalbalance=totalsaving-totalwithdraw+carryforward;

                      $("#totalsaving").html("RM" + totalsaving.toFixed(2));
                      $("#totalwithdraw").html("RM" + totalwithdraw.toFixed(2));
                      $("#totalbalance").html("RM" + totalbalance.toFixed(2));
                      $("#carryforward").html("RM" + carryforward.toFixed(2));

                    },
                   columns: [
                     {data: null, "render":"", title:"No"},
                     {data:'presavingrecords.Id', title:"Id"},
                     {data:'presavingrecords.Type', title:"Type"},
                     {data:'presavingrecords.Payment_Date', title:"Payment_Date"},
                     {data:'presavingrecords.Amount', title:"Amount"},
                     {data:'presavingrecords.Reason', title:"Reason"},
                     {data:'presavingrecords.created_at', title:'Created_At'},
                     {data:'presavingrecords.updated_at', title:'Updated_At'},
                     { data: "creator.Name", editField: "presavingrecords.created_by", title:"Created By" }


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
                           //          .set( 'presavingrecords.PresavingId', {{ $id }} )
                           //          .set( 'presavingrecords.created_at', "{{ date("Y-m-d H:i:s") }}" )
                           //          .submit();
                           //   },
                           // },
                           { extend: "create", text: 'New Record',  editor: editor },
                           { extend: "edit",  editor: editor },

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

                 var totalsaving=0.0;
                  var totalwithdraw=0.0;
                  var totalbalance=0.0;
                  var carryforward={{$carryforward}};

                  presavingtable.api().rows().every( function () {
                     var d = this.data();
                     var type= d.presavingrecords.Type;

                     if(type.includes("Saving")==true)
                     {
                       totalsaving=totalsaving+parseFloat(d.presavingrecords.Amount);
                     }
                     else if(type.includes("Withdraw")==true)
                     {
                       totalwithdraw=totalwithdraw+parseFloat(d.presavingrecords.Amount);
                     }

                   } );

                   totalbalance=totalsaving-totalwithdraw+carryforward;


                  $("#totalsaving").html("RM" + totalsaving.toFixed(2));
                  $("#totalwithdraw").html("RM" + totalwithdraw.toFixed(2));
                  $("#totalbalance").html("RM" + totalbalance.toFixed(2));
                  $("#carryforward").html("RM" + carryforward.toFixed(2));

               } );

               presavingtable.api().on( 'order.dt search.dt', function () {
                   presavingtable.api().column(0, {search:'applied', order:'applied'}).nodes().each( function (cell, i) {
                       cell.innerHTML = i+1;
                   } );
               } ).draw();


               $(".presaving thead input").keyup ( function () {

                       /* Filter on the column (the index) of this element */
                       if ($('#presaving').length > 0)
                       {

                           var colnum=document.getElementById('presaving').rows[0].cells.length;

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


             } );

     </script>

      @endsection

@section('content')

  <div class="content-wrapper">
      <!-- Content Header (Page header) -->
    <section class="content-header">
      <h1>
      {{$presaving->Name}}
      <small>Presaving</small>
      </h1>

      <ol class="breadcrumb">
      <li><a href="{{ url('/home') }}"><i class="fa fa-dashboard"></i> Home</a></li>
      <li><a href="#">presaving</a></li>
      <li class="active">{{$presaving->Name}}</li>
      </ol>
    </section>

    <section class="content">

      <div class="row">

          <div class="col-md-4">

            <div class="box box-primary">

              <div class="box-body box-profile">

                <ul class="list-group list-group-unbordered">
                  <li class="list-group-item">
                    <b>Name</b> : <p class="pull-right"><i>{{ $presaving->Name }}</i>
                  </li>

                  <li class="list-group-item">
                    <b>Department</b> : <p class="pull-right"><i>{{ $presaving->Department }}</i>
                  </li>

                  <li class="list-group-item">
                    <b>Position</b> : <p class="pull-right"><i>{{ $presaving->Position }}</i>
                  </li>

                  <li class="list-group-item">
                    <b>Joining Date</b> : <p class="pull-right"><i>{{ $presaving->Joining_Date }}</i>
                  </li>

                  <li class="list-group-item">
                    <b>Nationality</b> : <p class="pull-right"><i>{{ $presaving->Nationality }}</i>
                  </li>

                  <li class="list-group-item">
                    <b>Passport No</b> : <p class="pull-right"><i>{{ $presaving->Passport_No }}</i>
                  </li>

                  <li class="list-group-item">
                    <b>Presaving Start On</b> : <p class="pull-right"><i>{{ $presaving->Presaving_Start_On }}</i>
                  </li>

                  <li class="list-group-item">
                    <b>Total Saving</b> : <p class="pull-right"><i><span id='totalsaving'>RM0.00</span></i></p>
                  </li>

                  <li class="list-group-item">
                    <b>Total Withdraw</b> : <p class="pull-right"><i><span id='totalwithdraw'>RM0.00</span></i></p>
                  </li>

                  <li class="list-group-item">
                    <b>Carry Forward</b> : <p class="pull-right"><i><span id='carryforward'>RM0.00</span></i></p>
                  </li>

                  <li class="list-group-item">
                    <b>Total Balance</b> : <p class="pull-right"><i><span id='totalbalance'>RM0.00</span></i></p>
                  </li>

                </ul>

                {{-- <a href="#" class="btn btn-primary btn-block"><b>Update</b></a> --}}
              </div>

            </div>
          </div>

      </div>

      <div class="row">

        <div class="col-md-12">
          <!-- <div class="box">
            <div class="box-body"> -->


                  <table id="presaving" class="presaving" cellspacing="0" width="100%" padding="30px" style="font-size: 13px;">
                    <thead>

                      <tr class="search">

                        @foreach($savings as $key=>$value)

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

                          @foreach($savings as $key=>$value)

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
    <b>Version</b> 2.0.1
  </div>
  <strong>Copyright &copy; 2014-2016 <a href="http://www.softoya.com">TrackerOnTheGo</a>.</strong> All rights
  reserved.
</footer>

@endsection
