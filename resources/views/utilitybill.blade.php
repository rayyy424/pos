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
      <script type="text/javascript" language="javascript" src="{{ asset('/plugin/datatables/extensions/Select2/editor.select2.js') }}"></script>

      {{-- chart js --}}
      <script type="text/javascript" language="javascript" src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.4.0/Chart.bundle.min.js"></script>

      <script type="text/javascript" language="javascript" src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.8.4/moment.min.js"></script>
      <script type="text/javascript" language="javascript" src="https://cdn.datatables.net/plug-ins/1.10.15/sorting/datetime-moment.js"></script>

      <script type="text/javascript" language="javascript" class="init">

         var listtable;
         var listeditor;

         $(document).ready(function() {

           $.fn.dataTable.moment( 'DD-MMM-YYYY' );

           listeditor = new $.fn.dataTable.Editor( {
                   ajax: {
                      "url": "{{ asset('/Include/utility.php') }}",
                      "data": {
                          "type": "{{ $type }}"
                      }
                    },
                   table: "#list",
                   idSrc: "utility.Id",
                   formOptions: {
                        bubble: {
                            submit: 'allIfChanged'
                        }
                    },
                   fields: [

                          {
                                   label: "Bill Account No:",
                                   name: "utility.Bill_Account_No",
                                   type:  "select2",
                                   opts: {
                                    createTag: function (tag) {
                                        return {
                                            id: tag.term,
                                            text: tag.term,
                                            tag: true
                                        };
                                    },
                                    tags: true,
                                    data: [
                                      { text: "", id:""},
                                      @foreach($bill_account_no as $option)
                                      {
                                        text: "{{$option->Option}}", id: "{{$option->Option}}"
                                      },
                                      @endforeach
                                    ]
                                  }
                           },{
                                   label: "Bill Date:",
                                   name: "utility.Bill_Date",
                                   type:   'datetime',
                                   def:    function () { return ""; },
                                   format: 'DD-MMM-YYYY',
                                   attr: {
                                      autocomplete: "off"
                                   }
                           },{
                                   label: "Type:",
                                   name: "utility.Type",
                                   type: "hidden",
                                   def: "{{ $type }}"
                           },{
                                   label: "Branch:",
                                   name: "utility.Branch",
                                   type:  "select2",
                                   opts: {
                                    data: [
                                      { text: "", id: ""},
                                      @foreach($branch as $option)
                                      {
                                        text: "{{$option->Option}}", id: "{{$option->Option}}"
                                      },
                                      @endforeach
                                    ]
                                   }
                           },{
                                   label: "Bill Due Date:",
                                   name: "utility.Bill_Due_Date",
                                   type:   'datetime',
                                   def:    function () { return ""; },
                                   format: 'DD-MMM-YYYY'
                           },{
                                   label: "Monthly Charges:",
                                   name: "utility.Monthly_Charges",
                                   attr: {
                                      type: "number"
                                    }
                           },{
                                   label: "GST Charges:",
                                   name: "utility.GST_Charges",
                                   attr: {
                                      type: "number"
                                    }
                           },{
                                   label: "Payment_Type:",
                                   name: "utility.Payment_Type",
                                   type:  "select2",
                                   opts: {
                                    createTag: function (tag) {
                                        return {
                                            id: tag.term,
                                            text: tag.term,
                                            tag: true
                                        };
                                    },
                                    tags: true,
                                    data: [
                                      { text: "", id:""},
                                      @foreach($payment_type as $option)
                                      {
                                        text: "{{$option->Option}}", id: "{{$option->Option}}"
                                      },
                                      @endforeach
                                    ]
                                   }
                           },{
                                   label: "Date Paid:",
                                   name: "utility.Date_Paid",
                                   type:   'datetime',
                                   def:    function () { return ""; },
                                   format: 'DD-MMM-YYYY',
                                   attr: {
                                      autocomplete: "off"
                                   }
                           },{
                                   label: "Paid Amount:",
                                   name: "utility.Paid_Amount",
                                   attr: {
                                      type: "number"
                                    }
                           }, {
                                   label: "Remarks:",
                                   name: "utility.Remarks",
                                   type:"textarea"

                           }
                   ]
           } );

           listeditor.on( 'postSubmit', function ( e, json, data, action) {

            //  if(json["fieldErrors"])
            //  {
             //
            //    var errormessage="Duplicate claimsheet for "+$('[name="Payment_Month"]').val();
            //    $("#error-alert ul").html(errormessage);
            //    $("#error-alert").modal('show');
             //
             //
            //  }

         } );


         		var detail = [{Type:"Water", Branch:"Saujana Villa", Account_No:"4000322219016"},
                        {Type:"Electricity", Branch:"Saujana Villa", Account_No:"220296501103"},
                        {Type:"Unifi", Branch:"Saujana Villa", Account_No:"1031227299"},
                        {Type:"MPKJ", Branch:"Saujana Villa", Account_No:"40T0292A021-0274279"}
                      ];

            console.log(detail);


           listtable = $('#list').dataTable( {
             ajax: {
                "url": "{{ asset('/Include/utility.php') }}",
                "data": {
                    "type": "{{ $type }}"
                }
              },

                   dom: "Blrftip",
                   bAutoWidth: true,
                   sScrollY: "100%",
                   aaSorting: [[4,"asc"],[ 5, "asc" ]],
                   sScrollX: "100%",
                   columnDefs: [{ "visible": false, "targets": [1,2] },{"className": "dt-center", "targets": "_all"}],
                   bScrollCollapse: true,
                   columns: [
                     {data: null, title:"No"},
                     {data:"utility.Id", title:"Id"},
                     {data:'utility.Type', title:"Type"},
                     {data:'utility.Branch', title:"Branch"},
                     {data:'utility.Bill_Account_No', title:"Bill_Account_No"},
                     {data:'utility.Bill_Date', title:"Bill_Date"},
                     {data:'utility.Bill_Due_Date', title:"Bill_Due_Date"},
                     {data:'utility.Monthly_Charges', title:"Monthly_Charges",
                     render: function ( data, type, full, meta ) {

                        return parseFloat(full.utility.Monthly_Charges).toLocaleString("en",{ minimumFractionDigits: 2 });

                      }
                    },
                     {data:'utility.GST_Charges', title:"GST_Charges",
                     render: function ( data, type, full, meta ) {

                        return parseFloat(full.utility.GST_Charges).toLocaleString("en",{ minimumFractionDigits: 2 });

                      }
                    },
                     {data:'utility.Total_Amount', title:"Total_Amount",
                     render: function ( data, type, full, meta ) {

                        return parseFloat(full.utility.Total_Amount).toLocaleString("en",{ minimumFractionDigits: 2 });

                      }
                    },
                     {data:'utility.Payment_Type', title:"Payment_Type"},
                     {data:'utility.Date_Paid', title:"Date_Paid"},
                     {data:'utility.Paid_Amount', title:"Paid_Amount",
                     render: function ( data, type, full, meta ) {

                        return parseFloat(full.utility.Paid_Amount).toLocaleString("en",{ minimumFractionDigits: 2 });

                      }
                    },
                     {data:'utility.Remarks', title:"Remarks"}
                   ],
                   autoFill: {
                      editor:  listeditor
                  },
                  select: true,
                   buttons: [

                         // {
                         //   text: 'New',
                         //   action: function ( e, dt, node, config ) {
                         //       // clearing all select/input options
                         //       listeditor
                         //          .create( false )
                         //          .set( 'utility.Type', '{{$type}}')
                         //          .submit();
                         //   },
                         // },
                         { extend: "create", text: "New", editor: listeditor},
                         { extend: "edit", editor: listeditor},
                         { extend: "remove", editor: listeditor },
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



               // $('#list').on( 'click', 'tbody td:not(.child), tbody span.dtr-data', function (e) {
               //       listeditor.inline( this, {
               //      onBlur: 'submit',
               //      submit: 'allIfChanged'

               //     } );
               // } );


               listtable.api().on( 'order.dt search.dt', function () {
                   listtable.api().column(0, {search:'applied', order:'applied'}).nodes().each( function (cell, i) {
                       cell.innerHTML = i+1;
                   } );
               } ).draw();

               listeditor.on('initEdit', function ( e, json, data ) {
                console.log(data['utility']['Bill_Account_No']);
                  listeditor.field('utility.Bill_Account_No')
                    .update([
                      data['utility']['Bill_Account_No'],
                      @foreach($bill_account_no as $option)
                        "{{ $option->Option }}",
                      @endforeach
                    ])
                    .val(data['utility']['Bill_Account_No']);

                  // update select 2 with the initial field value and plus option values
                  listeditor
                    .field('utility.Payment_Type')
                    .update([
                      data['utility']['Payment_Type'],
                      @foreach($payment_type as $option)
                        "{{$option->Option}}",
                      @endforeach
                    ])
                    .val(
                      data['utility']['Payment_Type']
                    );

               });


               $(".list thead input").keyup ( function () {

                       /* Filter on the column (the index) of this element */
                       if ($('#list').length > 0)
                       {

                           var colnum=document.getElementById('list').rows[0].cells.length;

                           if (this.value=="[empty]")
                           {

                              listtable.fnFilter( '^$', this.name,true,false );
                           }
                           else if (this.value=="[nonempty]")
                           {

                              listtable.fnFilter( '^(?=\\s*\\S).*$', this.name,true,false );
                           }
                           else if (this.value.startsWith("!")==true && this.value.length>1)
                           {

                              listtable.fnFilter( '^(?'+ this.value +').*', this.name,true,false );
                           }
                           else if (this.value.startsWith("!")==false)
                           {

                               listtable.fnFilter( this.value, this.name,true,false );
                           }
                       }



               } );

               $('a[data-toggle="tab"]').on( 'shown.bs.tab', function (e) {
                 var target = $(e.target).attr("href") // activated tab

                   $.fn.dataTable.tables( {visible: true, api: true} ).columns.adjust();

               } );

             } );

     </script>

      @endsection

@section('content')

  <div class="content-wrapper">
      <!-- Content Header (Page header) -->
    <section class="content-header">
      <h1>
      Utility Bills Tracker
      <small>Administration</small>
      </h1>

      <ol class="breadcrumb">
      <li><a href="{{ url('/home') }}"><i class="fa fa-dashboard"></i> Home</a></li>
      <li><a href="#">Administration</a></li>
      <li class="active">Utility Bills Tracker</li>
      </ol>
    </section>

    <section class="content">

      <div class="row">

        <div class="col-md-12">

          <div class="box">
              <div class="box-body">

                 @foreach($category as $category1)

                  @if ($category1->Option==$type)
                     <a href="{{ url('/utilitytracker') }}/{{$category1->Option}}"><button type="button" class="btn btn-danger btn-small">{{$category1->Option}}</button></a>
                  @else
                    <a href="{{ url('/utilitytracker') }}/{{$category1->Option}}"><button type="button" class="btn btn-success btn-small">{{$category1->Option}}</button></a>
                  @endif

                 @endforeach

                 {{-- <a href="{{ url('/utilitysummary') }}"><button type="button" class="btn btn-success btn-small">Summary</button></a> --}}

                 <br><br>


                  <div class="col-md-12">

                     <table id="list" class="list" cellspacing="0" width="100%" padding="30px" style="font-size: 13px;">
                         <thead>
                           @if($list)
                             <tr class="search">

                               @foreach($list as $key=>$value)

                                 @if ($key==0)
                                   <?php $i = 0; ?>

                                   @foreach($value as $field=>$a)
                                       @if ($i==0|| $i==1 || $i==2)
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

                           @endif
                             <tr>

                               @foreach($list as $key=>$value)

                                 @if ($key==0)
                                        <!-- <td></td> -->
                                        <td></td>
                                   @foreach($value as $field=>$value)
                                       <td/>{{ $field }}</td>
                                   @endforeach

                                 @endif

                               @endforeach
                             </tr>
                         </thead>
                         <tbody>

                           <?php $i = 0; ?>
                           @foreach($list as $lists)

                                 <tr id="row_{{ $i }}" >
                                      <!-- <td></td> -->
                                      <td></td>
                                     @foreach($lists as $key=>$value)

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

                 <br><br>


              </div>


          </div>
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

<script>



function getval(sel)
{

  window.location.href ="{{ url("/utilitytracker") }}/"+"{{$type}}"+"/"+sel.value;
}

</script>



@endsection
