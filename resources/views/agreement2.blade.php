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

         var listtable;
         var listeditor;
         var rangetable;

         $(document).ready(function() {

           listeditor = new $.fn.dataTable.Editor( {
                   ajax: {
                      "url": "{{ asset('/Include/agreement.php') }}",
                      "data": {
                          "type": "{{ $type }}"
                      }
                    },
                   table: "#list",
                   idSrc: "agreement.Id",
                   fields: [

                          {
                                   label: "Type:",
                                   name: "agreement.Type",
                                   type:"readonly",
                                   def: '{{$type}}'
                           },{
                                   label: "Company:",
                                   name: "agreement.Company",
                                   type: "select2",
                                   options: [
                                     { label :"", value:""},
                                     { label :"SPEED FREAK", value: "SPEED FREAK" }
                                   ]
                           },{
                                   label: "Date of Agreement:",
                                   name: "agreement.Date_of_Agreement",
                                   type:   'datetime',
                                   def:    function () { return ""; },
                                   format: 'DD-MMM-YYYY',
                                   attr: {
                                    autocomplete: "off"
                                   }

                           },{
                                   label: "Remarks:",
                                   name: "agreement.Remarks"

                           },{
                                   label: "Description of Agreement:",
                                   name: "agreement.Description_of_Agreement",
                                   type:   'textarea'

                           },{
                                   label: "Expiry Date:",
                                   name: "agreement.Expiry_Date",
                                   type:   'datetime',
                                   // def:    function () { return ""; },
                                   format: 'DD-MMM-YYYY',
                                   attr: {
                                    autocomplete: "off"
                                   }

                           },{
                                  label: "File:",
                                  name: "files.Web_Path",
                                  type: "upload",
                                  ajaxData: function ( d ) {
                                    d.append( 'Id', agreementid ); // edit - use `d`
                                  },
                                  display: function ( file_id ) {
                                    return '<img src="'+ listtable.api().row( listeditor.modifier() ).data().files.Web_Path +'">';
                                  },
                                  clearText: "Clear",
                                  noImageText: 'No file'
                          }

                   ]
           } );


           listtable = $('#list').dataTable( {
                   ajax: {
                      "url": "{{ asset('/Include/agreement.php') }}",
                      "data": {
                          "type": "{{ $type }}"
                      }
                    },
                   dom: "Blrftip",
                   bAutoWidth: true,
                   aaSorting:false,
                   sScrollY: "100%",
                   sScrollX: "100%",
                   columnDefs: [{ "visible": false, "targets": [1,2] },{"className": "dt-center", "targets": "_all"}],
                   bScrollCollapse: true,
                   columns: [
                     {data: null, title:"No"},
                     {data:"agreement.Id", title:"Id"},
                     {data:'agreement.Type', title:"Type"},
                     {data:'agreement.Company', title:"Company"},
                     {data:'agreement.Date_of_Agreement', title:"Date of Agreement"},
                     {data:'agreement.Description_of_Agreement', title:"Description of Agreement"},
                     {data:'agreement.Expiry_Date', title:"Expiry Date"},
                     {data:'agreement.Remarks', title:"Remarks"},
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
                        //     text: 'New Bill',
                        //     action: function ( e, dt, node, config ) {
                        //       // clearing all select/input options
                        //       listeditor
                        //          .create( false )
                        //          .set( 'agreement.Type', '{{$type}}')
                        //          .submit();
                        //   },
                        // },
                        { extend: "create", text:'New Bill', editor: listeditor },
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
                 agreementid = listtable.api().row( this ).data().agreement.Id;
               });

               // $('#list').on( 'click', 'tbody td:not(.child), tbody span.dtr-data', function (e) {
               //       listeditor.inline( this, {
               //      onBlur: 'submit'
               //     } );
               // } );
               listeditor.on( 'initCreate', function ( e ) {
                  listeditor.field('files.Web_Path').hide();

                } );

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
        Agreement
      <small>Asset Management</small>
      </h1>

      <ol class="breadcrumb">
      <li><a href="{{ url('/home') }}"><i class="fa fa-dashboard"></i> Home</a></li>
      <li><a href="#">Asset Management</a></li>
      <li class="active">Agreement</li>
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
                           <a href="{{ url('/agreement') }}/{{$table->Option}}"><button type="button" class="btn btn-success btn-small">{{$table->Option}}</button></a>
                         @else
                           <a href="{{ url('/agreement') }}/{{$table->Option}}"><button type="button" class="btn btn-danger btn-small">{{$table->Option}}</button></a>
                         @endif

                       @endforeach

                       <br><br>

                        <table id="list" class="list" cellspacing="0" width="100%" padding="30px" style="font-size: 13px;">
                            <thead>
                              @if($agreement)
                                <tr class="search">

                                  @foreach($agreement as $key=>$value)

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

                                  @foreach($agreement as $key=>$value)

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
                              @foreach($agreement as $agreements)

                                    <tr id="row_{{ $i }}" >
                                         <!-- <td></td> -->
                                         <td></td>
                                        @foreach($agreements as $key=>$value)

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
