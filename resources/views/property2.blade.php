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

      .btn{
        width: 200px;
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

         var listtable;
         var listeditor;
         var rangetable;

         $(document).ready(function() {

           listeditor = new $.fn.dataTable.Editor( {
                   ajax: {
                      "url": "{{ asset('/Include/property.php') }}",
                      "data": {
                          "type": "{{ $type }}"
                      }
                    },
                   table: "#list",
                   idSrc: "property.Id",
                   fields: [

                          {
                                   label: "Type:",
                                   name: "property.Type",
                                   type:"hidden",
                                   def: "{{ $type }}"
                           },
                           @if ($type=="OWN PROPERTY")
                           {
                                   label: "Start:",
                                   name: "property.Start",
                                   type:   'datetime',
                                   def:    function () { return ""; },
                                   format: 'DD-MMM-YYYY'

                           },{
                                   label: "End:",
                                   name: "property.End",
                                   type:   'datetime',
                                   def:    function () { return ""; },
                                   format: 'DD-MMM-YYYY'

                           },{
                                   label: "Address:",
                                   name: "property.Address",
                                   type:"textarea"

                           },{
                                   label: "Landlord:",
                                   name: "property.Landlord"

                           },{
                                   label: "Tenant:",
                                   name: "property.Tenant"

                           },{
                                   label: "Business:",
                                   name: "property.Business"

                           },{
                                   label: "Area:",
                                   name: "property.Area"
                           },{
                                   label: "Property_Type:",
                                   name: "property.Property_Type"

                           },{
                                   label: "Status:",
                                   name: "property.Status"

                           },{
                                   label: "Rental:",
                                   name: "property.Rental"

                           },{
                                   label: "TNB:",
                                   name: "property.TNB"

                           },{
                                   label: "Water:",
                                   name: "property.Water"

                           },{
                                   label: "IWK:",
                                   name: "property.IWK"

                           },{
                                   label: "Security_Deposit:",
                                   name: "property.Security_Deposit"

                           },{
                                   label: "Utility_Deposit:",
                                   name: "property.Utility_Deposit"

                           },{
                                   label: "Termination_Notice:",
                                   name: "property.Termination_Notice"

                           },{
                                   label: "Agreement:",
                                   name: "property.Agreement"

                           },{
                                   label: "Keys:",
                                   name: "property.Keys"

                           },{
                                   label: "Contact_Person:",
                                   name: "property.Contact_Person"

                           },{
                                   label: "Remarks:",
                                   name: "property.Remarks"

                           },{
                                   label: "File:",
                                   name: "files.Web_Path",
                                   type: "upload",
                                   ajaxData: function ( d ) {
                                     d.append( 'Id', fileid ); // edit - use `d`
                                   },
                                   display: function ( file_id ) {
                                     return '<img src="'+ listtable.api().row( listeditor.modifier() ).data().files.Web_Path +'">';
                                   },
                                   clearText: "Clear",
                                   noImageText: 'No file'
                          }

                          @elseif($type=="RENTAL")
                          {
                                   label: "Start:",
                                   name: "property.Start",
                                   type:   'datetime',
                                   def:    function () { return ""; },
                                   format: 'DD-MMM-YYYY'

                           },{
                                   label: "End:",
                                   name: "property.End",
                                   type:   'datetime',
                                   def:    function () { return ""; },
                                   format: 'DD-MMM-YYYY'

                           },{
                                   label: "Address:",
                                   name: "property.Address",
                                   type:"textarea"

                           },{
                                   label: "Company:",
                                   name: "property.Company",
                                   type:  "select2",
                                   options: [
                                     { label :"", value: "" },
                                     @foreach($company as $option)
                                         { label :"{{$option->Option}}", value: "{{$option->Option}}" },
                                     @endforeach
                                   ],

                           },{
                                   label: "Business:",
                                   name: "property.Business"

                           },{
                                   label: "Area:",
                                   name: "property.Area"
                           },{
                                   label: "Property_Type:",
                                   name: "property.Property_Type"

                           },{
                                   label: "Status:",
                                   name: "property.Status"

                           },{
                                   label: "Rental:",
                                   name: "property.Rental"

                           },{
                                   label: "TNB:",
                                   name: "property.TNB"

                           },{
                                   label: "Water:",
                                   name: "property.Water"

                           },{
                                   label: "IWK:",
                                   name: "property.IWK"

                           },{
                                   label: "Security_Deposit:",
                                   name: "property.Security_Deposit"

                           },{
                                   label: "Utility_Deposit:",
                                   name: "property.Utility_Deposit"

                           },{
                                   label: "Termination_Notice:",
                                   name: "property.Termination_Notice"

                           },{
                                   label: "Agreement:",
                                   name: "property.Agreement"

                           },{
                                   label: "Owner:",
                                   name: "property.Owner"

                           },{
                                   label: "Contact_Person:",
                                   name: "property.Contact_Person"

                           },{
                                   label: "Remarks:",
                                   name: "property.Remarks"

                           },{
                                   label: "File:",
                                   name: "files.Web_Path",
                                   type: "upload",
                                   ajaxData: function ( d ) {
                                     d.append( 'Id', fileid ); // edit - use `d`
                                   },
                                   display: function ( file_id ) {
                                     return '<img src="'+ listtable.api().row( listeditor.modifier() ).data().files.Web_Path +'">';
                                   },
                                   clearText: "Clear",
                                   noImageText: 'No file'
                          }
                          @else
                          {
                                   label: "Address:",
                                   name: "property.Address",
                                   type:"textarea"

                           },{
                                   label: "Area:",
                                   name: "property.Area",
                                   type:  "select2",
                                   opts: {
                                    tags: true,
                                    data: [
                                      { text: "", id: ""},
                                      @foreach($area as $option)
                                      {
                                        text: "{{$option->Option}}", id: "{{$option->Option}}"
                                      },
                                      @endforeach
                                    ]
                                   }

                           },{
                                   label: "File:",
                                   name: "files.Web_Path",
                                   type: "upload",
                                   ajaxData: function ( d ) {
                                     d.append( 'Id', fileid ); // edit - use `d`
                                   },
                                   display: function ( file_id ) {
                                     return '<img src="'+ listtable.api().row( listeditor.modifier() ).data().files.Web_Path +'">';
                                   },
                                   clearText: "Clear",
                                   noImageText: 'No file'
                          }
                          @endif

                   ]
           } );


           listtable = $('#list').dataTable( {
                   ajax: {
                      "url": "{{ asset('/Include/property.php') }}",
                      "data": {
                          "type": "{{ $type }}"
                      }
                    },
                   dom: "Blrftip",
                   bAutoWidth: true,
                   aaSorting:[[3,"asc"]],
                   sScrollY: "100%",
                   sScrollX: "100%",
                   @if ($type=="OWN PROPERTY")
                     columnDefs: [{ "visible": false, "targets": [1,2,5,22] },{"className": "dt-left", "targets": [3,22,23,24]},{"className": "dt-center", "targets": "_all"}],
                   @elseif ($type=="RENTAL")
                     columnDefs: [{ "visible": false, "targets": [2,3,5,22] },{"className": "dt-left", "targets": [3,22,23,24]},{"className": "dt-center", "targets": "_all"}],
                    @else
                     columnDefs: [{ "visible": false, "targets": [1,2,4,5,6,7,9,10,11,12,13,14,15,16,17,18,19,20,21,22,23,24] },{"className": "dt-left", "targets": [3,22,23,24]},{"className": "dt-center", "targets": "_all"}],
                   @endif
                   bScrollCollapse: true,
                   columns: [
                     {data: null, title:"No"},
                     {data: "button", title:"Action",
                        "render": function ( data, type, full, meta ) {
                            return '<a href="{{url("maintenance/")}}/'+full.property.Id+'" target="_blank"><button class="btn btn-default btn-xs" title="Maintenance" style="width:unset"><i class="fa fa-cogs" style="color:orange"></i></button></a>' +
                             '<a href="{{url("billing/")}}/'+full.property.Id+'" target="_blank"><button class="btn btn-default btn-xs" title="Billing" style="width:unset;"><i class="fa fa-money" style="color:green"></i></button></a>' +
                             '<a href="{{url("agreement2/EDOTCO/")}}" target="_blank"><button class="btn btn-default btn-xs" title="Agreement" style="width:unset"><i class="fa fa-file-text-o"></i></button></a>' +
                             '<a href="{{url("insurance/")}}/'+full.property.Id+'" target="_blank"><button class="btn btn-default btn-xs" title="Agreement" style="width:unset"><i class="fa fa-heartbeat" style="color:red"></i></button></a>'
                        }
                      },
                     {data:"property.Id", title:"Id"},
                     {data:'property.Type', title:"Type"},
                     {data:'property.Address', title:"Address"},
                     {data:'property.Landlord', title:"Landlord"},
                     {data:'property.Company', title:"Company"},
                     {data:'property.Tenant', title:"Tenant"},
                     {data:'property.Business', title:"Business"},
                     {data:'property.Area', title:"Area"},
                     {data:'property.Property_Type', title:"Type"},

                     {data:'property.Status', title:"Status"},
                     {data:'property.Rental', title:"Installment/Rental"},
                     {data:'property.TNB', title:"TNB"},
                     {data:'property.Water', title:"Water"},
                     {data:'property.IWK', title:"IWK"},
                     {data:'property.Start', title:"Start"},
                     {data:'property.End', title:"End"},
                     {data:'property.Security_Deposit', title:"Security Deposit"},
                     {data:'property.Utility_Deposit', title:"Utility Deposit"},
                     {data:'property.Termination_Notice', title:"Termination Notice"},

                     {data:'property.Agreement', title:"Agreement"},
                     {data:'property.Keys', title:"Keys"},
                     {data:'property.Owner', title:"Owner"},
                     {data:'property.Contact_Person', title:"Contact Person"},
                     {data:'property.Remarks', title:"Remarks"},
                     { data: "files.Web_Path",
                        render: function ( url, type, row ) {
                             if (url)
                             {
                               return '<a href="'+ url +'" target="_blank">Download</a>';
                             }
                             else {
                               return ' - ';
                             }
                         },
                         title: "File"
                       }
                   ],
                   autoFill: {
                      editor:  listeditor
                  },
                  select: true,
                  buttons: [
                        { extend: "create", text: 'New Bill', editor: listeditor },
                        { extend: "edit", editor: listeditor },
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

               $('#list').on( 'click', 'tr', function () {
                 // Get the rows id value
                //  var row=$(this).closest("tr");
                //  var oTable = row.closest('table').dataTable();
                fileid= listtable.api().row( this ).data().property.Id;
               });

               // $('#list').on( 'click', 'tbody td:not(.child), tbody span.dtr-data', function (e) {
               //       listeditor.inline( this, {
               //      onBlur: 'submit'
               //     } );
               // } );

                listeditor.on( 'initCreate', function ( e ) {
                  listeditor.field('files.Web_Path').hide();

                } );

                listeditor.on('initEdit', function ( e, json, data ) {
                   // the list of areas
                   var areas = [
                      @foreach($area as $option)
                        "{{$option->Option}}",
                      @endforeach
                   ];

                   // the selected users from edit
                   var selectedArea = data['property']['Area'];

                   // combine list and selected
                   var combinedAreas = areas.concat(selectedArea);

                   // remove the duplicates
                   var uniqueAreas = combinedAreas.filter(function(item, pos) {
                       return combinedAreas.indexOf(item) == pos;
                   });

                   listeditor.field('property.Area')
                     .update(uniqueAreas)
                     .val(selectedArea);
                });

               listeditor.on( 'preClose', function ( e ) {
                  listeditor.field('files.Web_Path').show();

                } );

               listtable.api().on( 'order.dt search.dt', function () {
                   listtable.api().column(0, {search:'applied', order:'applied'}).nodes().each( function (cell, i) {
                       cell.innerHTML = i+1;
                   } );
               } ).draw();



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
        Property
      <small>Administration</small>
      </h1>

      <ol class="breadcrumb">
      <li><a href="{{ url('/home') }}"><i class="fa fa-dashboard"></i> Home</a></li>
      <li><a href="#">Administration</a></li>
      <li class="active">Property</li>
      </ol>
    </section>

    <section class="content">

      <div class="row">
        <div class="col-md-12">
          <div class="box box-primary">
            <div class="box-header with-border">

              <div class="col-md-12">
                <div class="nav-tabs-custom">

                   <div class="tab-content">
                     <div class="active tab-pane" id="listtab">

                       @foreach($category as $table)

                         @if ($table->Option==$type)
                           <a href="{{ url('/property') }}/{{$table->Option}}"><button type="button" class="btn btn-success btn-small">{{$table->Option}}</button></a>
                         @else
                           <a href="{{ url('/property') }}/{{$table->Option}}"><button type="button" class="btn btn-danger btn-small">{{$table->Option}}</button></a>
                         @endif

                       @endforeach

                       <br><br>

                        <table id="list" class="list" cellspacing="0" width="100%" padding="30px" style="font-size: 13px;">
                            <thead>
                              @if($property)
                                <tr class="search">

                                  @foreach($property as $key=>$value)

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

                                  @foreach($property as $key=>$value)

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
                              @foreach($property as $properties)

                                    <tr id="row_{{ $i }}" >
                                         <!-- <td></td> -->
                                         <td></td>
                                        @foreach($properties as $key=>$value)

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


                </div>
              </div>

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


</script>



@endsection
