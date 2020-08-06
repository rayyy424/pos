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
      .btn-default-click {
        background-color: #949a97;
        border-color: #747776;
        color:white;
      }
      .btn-default-click:hover {
        background-color: #a0a5a2;
        border-color: #747776;
        color:white;
      }
      .btn{
        width: 150px;
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
                      "url": "{{ asset('/Include/insurance.php') }}",
                      "data": {
                          "type": "{{ $type }}"
                      }
                    },
                   table: "#list",
                   idSrc: "insurances.Id",
                   fields: [
                           @if ($type=="LEE KUI HUENG")
                            {
                                   label: "Insured_Name:",
                                   name: "insurances.Insured_Name"

                           },{
                                   label: "Type:",
                                   name: "insurances.Type",
                                   type: "hidden",
                                   def: "{{ $type }}"
                           },{
                                    label: "Insured_Person:",
                                    name: "insurances.Insured_Person"
                           },{
                                    label: "Insurance_Expiry:",
                                    name: "insurances.Insurance_Expiry",
                                    type:   'datetime',
                                    def:    function () { return ""; },
                                    format: 'DD-MMM-YYYY'
                           },{
                                    label: "Insurance_Company:",
                                    name: "insurances.Insurance_Company"
                           },{
                                  label: "Company:",
                                  name: "insurances.Company",
                                  type:  "select2",
                                  options: [
                                    { label :"", value: "" },
                                    @foreach($company as $option)
                                        { label :"{{$option->Option}}", value: "{{$option->Option}}" },
                                    @endforeach
                                  ],
                          },{
                                    label: "Account_No:",
                                    name: "insurances.Account_No"
                           },{
                                    label: "Policy_No:",
                                    name: "insurances.Policy_No"
                           },{
                                    label: "Type_of_Policy:",
                                    name: "insurances.Type_of_Policy"
                           },{
                                    label: "Plan_Covered:",
                                    name: "insurances.Plan_Covered"
                           },{
                                    label: "Class:",
                                    name: "insurances.Class"
                           },{
                                    label: "Benefits:",
                                    name: "insurances.Benefits"
                           },{
                                    label: "Sum_Insured:",
                                    name: "insurances.Sum_Insured"
                           },{
                                    label: "Total_Premium:",
                                    name: "insurances.Total_Premium"
                           },{
                                  label: "File:",
                                  name: "files.Web_Path",
                                  type: "upload",
                                  ajaxData: function ( d ) {
                                    d.append( 'Id', insuranceid ); // edit - use `d`
                                  },
                                  display: function ( file_id ) {
                                    return '<img src="'+ listtable.api().row( listeditor.modifier() ).data().files.Web_Path +'">';
                                  },
                                  clearText: "Clear",
                                  noImageText: 'No file'
                          }

                          @elseif ($type=="GENSET")
                          {
                                   label: "Type:",
                                   name: "insurances.Type",
                                   type: "hidden",
                                   def: "{{ $type }}"
                           },{
                                    label: "Insurance_Expiry:",
                                    name: "insurances.Insurance_Expiry",
                                    type:   'datetime',
                                    def:    function () { return ""; },
                                    format: 'DD-MMM-YYYY',
                                    attr: {
                                      autocomplete: 'off'
                                    }
                           },{
                                  label: "Company:",
                                  name: "insurances.Company",
                                  type:  "select2",
                                  options: [
                                    { label :"", value: "" },
                                    @foreach($company as $option)
                                        { label :"{{$option->Option}}", value: "{{$option->Option}}" },
                                    @endforeach
                                  ],
                          },{
                                    label: "Policy_No:",
                                    name: "insurances.Policy_No"
                           },{
                                    label: "Sum_Insured:",
                                    name: "insurances.Sum_Insured"
                           },{
                                    label: "Total_Premium:",
                                    name: "insurances.Total_Premium"
                           },{
                                    label: "Insurance_Type:",
                                    name: "insurances.Insurance_Type"
                           },{
                                    label: "Purchase_Date:",
                                    name: "insurances.Purchase_Date",
                                    type:   'datetime',
                                    def:    function () { return ""; },
                                    format: 'DD-MMM-YYYY'
                           },{
                                    label: "Brand:",
                                    name: "insurances.Brand"
                           },{
                                    label: "Serial_No:",
                                    name: "insurances.Serial_No"
                           },{
                                    label: "Engine_Model:",
                                    name: "insurances.Engine_Model"
                           },{
                                    label: "Engine_No:",
                                    name: "insurances.Engine_No"
                           },{
                                    label: "Financier:",
                                    name: "insurances.Financier"
                           },{
                                    label: "Capacity:",
                                    name: "insurances.Capacity"
                           },{
                                   label: "Remarks:",
                                   name: "insurances.Remarks",
                                   type:"textarea"

                           },{
                                  label: "File:",
                                  name: "files.Web_Path",
                                  type: "upload",
                                  ajaxData: function ( d ) {
                                    d.append( 'Id', insuranceid ); // edit - use `d`
                                  },
                                  display: function ( file_id ) {
                                    return '<img src="'+ listtable.api().row( listeditor.modifier() ).data().files.Web_Path +'">';
                                  },
                                  clearText: "Clear",
                                  noImageText: 'No file'
                          }
                          @elseif ($type=="PROJECT")
                          {
                                   label: "Type:",
                                   name: "insurances.Type",
                                   type: "hidden",
                                   def: "{{ $type }}"
                           },{
                                   label: "Address:",
                                   name: "insurances.Address",
                                   type: "textarea"
                           },{
                                    label: "Insurance_Expiry:",
                                    name: "insurances.Insurance_Expiry",
                                    type:   'datetime',
                                    def:    function () { return ""; },
                                    format: 'DD-MMM-YYYY'
                           },{
                                    label: "Insurance_Company:",
                                    name: "insurances.Insurance_Company"
                           },{
                                  label: "Company:",
                                  name: "insurances.Company",
                                  type:  "select2",
                                  options: [
                                    { label :"", value: "" },
                                    @foreach($company as $option)
                                        { label :"{{$option->Option}}", value: "{{$option->Option}}" },
                                    @endforeach
                                  ],
                          },{
                                    label: "Account_No:",
                                    name: "insurances.Account_No"
                           },{
                                    label: "Policy_No:",
                                    name: "insurances.Policy_No"
                           },{
                                    label: "Type_of_Policy:",
                                    name: "insurances.Type_of_Policy"
                           },{
                                    label: "Ratings:",
                                    name: "insurances.Ratings"
                           },{
                                    label: "Sum_Insured:",
                                    name: "insurances.Sum_Insured"
                           },{
                                    label: "Total_Premium:",
                                    name: "insurances.Total_Premium"
                           },{
                                    label: "Contact_No:",
                                    name: "insurances.Contact_No"
                           },{
                                    label: "Section:",
                                    name: "insurances.Section"
                           },{
                                    label: "Client:",
                                    name: "insurances.Client"
                           },{
                                  label: "File:",
                                  name: "files.Web_Path",
                                  type: "upload",
                                  ajaxData: function ( d ) {
                                    d.append( 'Id', insuranceid ); // edit - use `d`
                                  },
                                  display: function ( file_id ) {
                                    return '<img src="'+ listtable.api().row( listeditor.modifier() ).data().files.Web_Path +'">';
                                  },
                                  clearText: "Clear",
                                  noImageText: 'No file'
                          }
                          @else
                          {
                                   label: "Type:",
                                   name: "insurances.Type",
                                   type: "hidden",
                                   def: "{{ $type }}"
                           },{
                                   label: "Address:",
                                   name: "insurances.Address",
                                   type: "textarea"
                           },{
                                    label: "Insurance_Expiry:",
                                    name: "insurances.Insurance_Expiry",
                                    type:   'datetime',
                                    def:    function () { return ""; },
                                    format: 'DD-MMM-YYYY'
                           },{
                                    label: "Insurance_Company:",
                                    name: "insurances.Insurance_Company"
                           },{
                                  label: "Company:",
                                  name: "insurances.Company",
                                  type:  "select2",
                                  options: [
                                    { label :"", value: "" },
                                    @foreach($company as $option)
                                        { label :"{{$option->Option}}", value: "{{$option->Option}}" },
                                    @endforeach
                                  ],
                          },{
                                    label: "Policy_No:",
                                    name: "insurances.Policy_No"
                           },{
                                    label: "Type_of_Policy:",
                                    name: "insurances.Type_of_Policy"
                           },{
                                    label: "Ratings:",
                                    name: "insurances.Ratings"
                           },{
                                    label: "Sum_Insured:",
                                    name: "insurances.Sum_Insured"
                           },{
                                    label: "Total_Premium:",
                                    name: "insurances.Total_Premium"
                           },{
                                    label: "Business:",
                                    name: "insurances.Business"
                           },{
                                    label: "Area:",
                                    name: "insurances.Area"
                           },{
                                    label: "Insurance_Type:",
                                    name: "insurances.Insurance_Type"
                           },{
                                    label: "Status:",
                                    name: "insurances.Status"
                           },{
                                    label: "Installment_Rental:",
                                    name: "insurances.Installment_Rental"
                           },{
                                    label: "Start_Date:",
                                    name: "insurances.Start_Date",
                                    type:   'datetime',
                                    def:    function () { return ""; },
                                    format: 'DD-MMM-YYYY'
                           },{
                                    label: "Contact_Person:",
                                    name: "insurances.Contact_Person"
                           },{
                                    label: "Contact_No:",
                                    name: "insurances.Contact_No"
                           },{
                                   label: "Remarks:",
                                   name: "insurances.Remarks",
                                   type:"textarea"

                           },{
                                  label: "File:",
                                  name: "files.Web_Path",
                                  type: "upload",
                                  ajaxData: function ( d ) {
                                    d.append( 'Id', insuranceid ); // edit - use `d`
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
           listeditor.on( 'initCreate', function ( e ) {
              listeditor.field('files.Web_Path').hide();

            } );

           listeditor.on( 'preClose', function ( e ) {
              listeditor.field('files.Web_Path').show();

            } );

           listtable = $('#list').dataTable( {
                   ajax: {
                      "url": "{{ asset('/Include/insurance.php') }}",
                      "data": {
                          "type": "{{ $type }}"
                      }
                    },
                   dom: "Blrftip",
                   bAutoWidth: true,
                   // aaSorting:false,
                   sScrollY: "100%",
                   sScrollX: "100%",
                   @if ($type=="LEE KUI HUENG")
                       columnDefs: [{ "visible": false, "targets": [1,2,4,5,16,19,20,21,22,23,24,25,26,27,28,29,30,32,31,33,34,35,36,37] },{"className": "dt-center", "targets": "_all"}],
                   @elseif ($type=="HOUSEOWNER")
                       columnDefs: [{ "visible": false, "targets": [1,2,3,5,14,19,20,21,22,23,24,25,26,27,28,29,30,31,32,33,34,35,36,37,38] },{"className": "dt-center", "targets": "_all"}],
                   @elseif ($type=="PROPERTY")
                       columnDefs: [{ "visible": false, "targets": [1,2,3,4,6,10,13,14,15,25,28,29,30,31,32,33,34,35,36] },{"className": "dt-left", "targets": [5,21]},{"className": "dt-center", "targets": "_all"}],
                   @elseif ($type=="PROJECT")
                       columnDefs: [{ "visible": false, "targets": [1,2,3,4,6,13,14,15,19,20,21,22,23,24,25,26,30,31,32,33,34,35,36,37] },{"className": "dt-left", "targets": [5]},{"className": "dt-center", "targets": "_all"}],
                   @elseif ($type=="GENSET")
                       columnDefs: [{ "visible": false, "targets": [1,2,3,4,5,6,8,10,12,13,14,15,16,19,20,22,23,24,25,26,27,28,29] },{"className": "dt-center", "targets": "_all"}],
                   @else
                     columnDefs: [{ "visible": false, "targets": [1,2] },{"className": "dt-center", "targets": "_all"}],
                   @endif
                   bScrollCollapse: true,
                   columns: [
                     {data: null, title:"No"},
                     {data:"insurances.Id", title:"Id"},
                     {data:'insurances.Type', title:"Type"},
                     {data:'insurances.Insured_Name', title:"Insured_Name"},
                     {data:'insurances.Situation', title:"Situation"},
                     {data:'insurances.Address', title:"Address"},
                     {data:'insurances.Insured_Person', title:"Insured_Person"},
                     {data:'insurances.Insurance_Expiry', title:"Insurance_Expiry"},
                     {data:'insurances.Insurance_Company', title:"Insurance_Company"},
                     {data:'insurances.Company', title:"Company"},
                     {data:'insurances.Account_No', title:"Account_No"},
                     {data:'insurances.Policy_No', title:"Policy_No"},
                     {data:'insurances.Type_of_Policy', title:"Type_of_Policy"},
                     {data:'insurances.Plan_Covered', title:"Plan_Covered"},
                     {data:'insurances.Class', title:"Class"},
                     {data:'insurances.Benefits', title:"Benefits"},
                     {data:'insurances.Ratings', title:"Ratings"},
                     {data:'insurances.Sum_Insured', title:"Sum_Insured"},
                     {data:'insurances.Total_Premium', title:"Total_Premium"},
                     {data:'insurances.Business', title:"Business"},
                     {data:'insurances.Area', title:"Area"},
                     {data:'insurances.Insurance_Type', title:"Type"},
                     {data:'insurances.Status', title:"Status"},
                     {data:'insurances.Installment_Rental', title:"Installment/Rental"},
                     {data:'insurances.Start_Date', title:"Start_Date"},
                     {data:'insurances.End_Date', title:"End_Date"},
                     {data:'insurances.Contact_Person', title:"Contact_Person"},
                     {data:'insurances.Contact_No', title:"Contact_No"},
                     {data:'insurances.Section', title:"Section"},
                     {data:'insurances.Client', title:"Client"},
                     {data:'insurances.Purchase_Date', title:"Purchase_Date"},

                     {data:'insurances.Brand', title:"Brand"},
                     {data:'insurances.Serial_No', title:"Serial_No"},
                     {data:'insurances.Engine_Model', title:"Engine_Model"},
                     {data:'insurances.Engine_No', title:"Engine_No"},
                     {data:'insurances.Financier', title:"Financier"},
                     {data:'insurances.Capacity', title:"Capacity"},
                     {data:'insurances.Remarks', title:"Remarks"},
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
                        // {
                        //     text: 'New',
                        //     action: function ( e, dt, node, config ) {
                        //       // clearing all select/input options
                        //       listeditor
                        //          .create( false )
                        //          .set( 'insurances.Type', '{{$type}}')
                        //          .submit();
                        //   },
                        // },
                        { extend: "create", text:"New" ,editor: listeditor },
                        { extend: "edit", text:"Edit" ,editor: listeditor },

                        { extend: "remove", editor: listeditor },
                        {
                                extend: 'collection',
                                text: 'Export',
                                buttons: [
                                        'excel',
                                        'csv',
                                ]
                        }
                        // 'print'

                  ],

               });

               $('#list').on( 'click', 'tr', function () {
                 // Get the rows id value
                //  var row=$(this).closest("tr");
                //  var oTable = row.closest('table').dataTable();
                 insuranceid = listtable.api().row( this ).data().insurances.Id;
               });

               // $('#list').on( 'click', 'tbody td:not(.child), tbody span.dtr-data', function (e) {
               //       listeditor.inline( this, {
               //      onBlur: 'submit'
               //     } );
               // } );



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
      Insurance
      <small>Administration</small>
      </h1>

      <ol class="breadcrumb">
      <li><a href="{{ url('/home') }}"><i class="fa fa-dashboard"></i> Home</a></li>
      <li><a href="#">Administration</a></li>
      <li class="active">Insurance</li>
      </ol>
    </section>

    <section class="content">

      <div class="row">
        <div class="col-md-12">
          <div class="box">
            <div class="box-body">

              @foreach($category as $table)

                @if ($table->Option==$type)
                  <a href="{{ url('/insurancetracker') }}/{{$table->Option}}"><button type="button" class="btn btn-danger btn-small">{{$table->Option}}</button></a>
                @else
                  <a href="{{ url('/insurancetracker') }}/{{$table->Option}}"><button type="button" class="btn btn-success btn-small">{{$table->Option}}</button></a>
                @endif

              @endforeach

              <br><br>

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


  $('#Bill_Date').datepicker({
    autoclose: true,
      format: 'dd-M-yyyy',
  });

});



</script>



@endsection
