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

         var licensetable;
         var listeditor;
         var listeditor1;
         var rangetable;
         var licenseid;

         $(document).ready(function() {

           listeditor = new $.fn.dataTable.Editor( {
                   ajax: {
                      "url": "{{ asset('/Include/licensecard.php') }}"
                    },
                   table: "#licensetable",
                   idSrc: "licenses.Id",
                   fields: [
                           {
                                   label: "Type:",
                                   name: "licenses.Type",
                                   type: "hidden",
                                   def: "{{ $type }}"
                            },
                           @if($type == "Company")
                            {
                                   label: "License Type:",
                                   name: "licenses.License_Type",
                                   type: "select2",
                                   options: [
                                       @foreach($options as $option)
                                         @if ($option->Field=="License_Type")
                                           { label :"{{$option->Option}}", value: "{{$option->Option}}" },
                                         @endif
                                       @endforeach
                                   ],
                                   attr:  {
                                      placeholder: 'CIDB'
                                  }

                           },{
                                   label: "Description:",
                                   name: "licenses.Description"
                            }, {
                                   label: "Expiry Date:",
                                   name: "licenses.Expiry_Date",
                                   type:   'datetime',
                                   def:    function () { return ""; },
                                   format: 'DD-MMM-YYYY'


                            },{
                                   label: "Remarks:",
                                   name: "licenses.Remarks"
                            },{
                                   label: "File:",
                                   name: "files.Web_Path",
                                   type: "upload",
                                   ajaxData: function ( d ) {
                                     d.append( 'Id', licenseid ); // edit - use `d`
                                   },
                                   display: function ( file_id ) {
                                     return '<img src="'+ licensetable.api().row( listeditor.modifier() ).data().files.Web_Path +'">';
                                   },
                                   clearText: "Clear",
                                   noImageText: 'No file'
                           }
                           @elseif ($type=="Employee")
                           {
                                   label: "Name:",
                                   name: "licenses.UserId",
                                   type:  'select2',
                                   options: [
                                      { label :"", value: "0" },
                                      @foreach($users as $user)
                                          { label :"{{$user->Name}}", value: "{{$user->Id}}" },
                                      @endforeach

                                  ],


                           }, {
                                   label: "License Type:",
                                   name: "licenses.License_Type",
                                   type: "select2",
                                   options: [
                                       @foreach($options as $option)
                                         @if ($option->Field=="License_Type")
                                           { label :"{{$option->Option}}", value: "{{$option->Option}}" },
                                         @endif
                                       @endforeach
                                   ],
                                   attr:  {
                                      placeholder: 'CIDB'
                                  }

                           },{
                                   label: "Identity No:",
                                   name: "licenses.Identity_No",
                                   attr:  {
                                      placeholder: 'A1234567'
                                  }

                           }, {
                                   label: "Expiry Date:",
                                   name: "licenses.Expiry_Date",
                                   type:   'datetime',
                                   def:    function () { return ""; },
                                   format: 'DD-MMM-YYYY'


                            },{
                                   label: "Remarks:",
                                   name: "licenses.Remarks"
                            },{
                                   label: "File:",
                                   name: "files.Web_Path",
                                   type: "upload",
                                   ajaxData: function ( d ) {
                                     d.append( 'Id', licenseid ); // edit - use `d`
                                   },
                                   display: function ( file_id ) {
                                     return '<img src="'+ licensetable.api().row( listeditor.modifier() ).data().files.Web_Path +'">';
                                   },
                                   clearText: "Clear",
                                   noImageText: 'No file'
                           }
                           @else
                           {
                                   label: "Name:",
                                   name: "licenses.UserId",
                                   type:  'select2',
                                   options: [
                                      { label :"", value: "0" },
                                      @foreach($users as $user)
                                          { label :"{{$user->Name}}", value: "{{$user->Id}}" },
                                      @endforeach

                                  ],


                           }, {
                                   label: "License Type:",
                                   name: "licenses.License_Type",
                                   type: "select2",
                                   options: [
                                       @foreach($options as $option)
                                         @if ($option->Field=="License_Type")
                                           { label :"{{$option->Option}}", value: "{{$option->Option}}" },
                                         @endif
                                       @endforeach
                                   ],
                                   attr:  {
                                      placeholder: 'CIDB'
                                  }

                           },{
                                   label: "Identity No:",
                                   name: "licenses.Identity_No",
                                   attr:  {
                                      placeholder: 'A1234567'
                                  }

                           }, {
                                   label: "Expiry Date:",
                                   name: "licenses.Expiry_Date",
                                   type:   'datetime',
                                   def:    function () { return ""; },
                                   format: 'DD-MMM-YYYY'


                            },{
                                   label: "Description:",
                                   name: "licenses.Description"
                            },{
                                   label: "Remarks:",
                                   name: "licenses.Remarks"
                            },{
                                   label: "File:",
                                   name: "files.Web_Path",
                                   type: "upload",
                                   ajaxData: function ( d ) {
                                     d.append( 'Id', licenseid ); // edit - use `d`
                                   },
                                   display: function ( file_id ) {
                                     return '<img src="'+ licensetable.api().row( listeditor.modifier() ).data().files.Web_Path +'">';
                                   },
                                   clearText: "Clear",
                                   noImageText: 'No file'
                           }
                           @endif



                   ]
           } );

           licensetable=$('#licensetable').dataTable( {
                 // keys: {
                 //      columns: ':not(:first-child)',
                 //      editor:  licenseseditor   //THIS LINE FIXED THE PROBLEM
                 //  },
                 dom: "Blrftip",
                 bAutoWidth: true,
                 order: [[ 1, "desc" ]],
                  sScrollY: "100%",
                 sScrollX: "100%",
                  bScrollCollapse: true,
                  @if ($type=="Company")
                      columnDefs: [{ "visible": false, "targets": [1,2,3,4,7] },{"className": "dt-center", "targets": "_all"}],
                  @elseif ($type=="Employee")
                      columnDefs: [{ "visible": false, "targets": [1,2,6] },{"className": "dt-center", "targets": "_all"}],
                  @else
                    columnDefs: [{ "visible": false, "targets": [1,2] },{"className": "dt-center", "targets": "_all"}, {"editable": false, ""}],
                  @endif
                  columns: [
                          { data: null,"render":"", title:"No", editField: null},
                          { data: "licenses.Id",title: "Id"},
                          { data: "licenses.Type",title: "Type"},
                          { data: 'users.Name',editField:'licenses.UserId',title: "Name"},
                          { data: 'users.Position',title: "Position"},
                          { data: "licenses.License_Type",title: "License_Type" },
                          { data: "licenses.Description",title: "Description" },
                          { data: "licenses.Identity_No",title: "Identity_No" },
                          { data: "licenses.Expiry_Date" ,title: "Expiry_Date"},
                          { data: "licenses.Remarks" ,title: "Remarks"},
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
                          //columns: ':not(:first-child)',
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
                    //          .set( 'licenses.Type', '{{$type}}')
                    //          .submit();
                    //   },
                    // },
                    { extend: "create", editor: listeditor },
                    { extend: "edit", editor: listeditor },

                    { extend: "remove", editor: listeditor }
                  ],
           });
            listeditor.on( 'initCreate', function ( e ) {
              listeditor.field('files.Web_Path').hide();

            } );

           listeditor.on( 'preClose', function ( e ) {
              listeditor.field('files.Web_Path').show();

            } );
           $('#licensetable').on( 'click', 'tr', function () {
             // Get the rows id value
            //  var row=$(this).closest("tr");
            //  var oTable = row.closest('table').dataTable();
             licenseid = licensetable.api().row( this ).data().licenses.Id;
           });



               // $('#licensetable').on( 'click', 'tbody td:not(.child), tbody span.dtr-data', function (e) {
               //       listeditor.inline( this, {
               //      onBlur: 'submit'
               //     } );
               // } );


               licensetable.api().on( 'order.dt search.dt', function () {
                   licensetable.api().column(0, {search:'applied', order:'applied'}).nodes().each( function (cell, i) {
                       cell.innerHTML = i+1;
                   } );
               } ).draw();




               $(".license thead input").keyup ( function () {

                       /* Filter on the column (the index) of this element */
                       if ($('#licensetable').length > 0)
                       {

                           var colnum=document.getElementById('licensetable').rows[0].cells.length;

                           if (this.value=="[empty]")
                           {

                              licensetable.fnFilter( '^$', this.name,true,false );
                           }
                           else if (this.value=="[nonempty]")
                           {

                              licensetable.fnFilter( '^(?=\\s*\\S).*$', this.name,true,false );
                           }
                           else if (this.value.startsWith("!")==true && this.value.length>1)
                           {

                              licensetable.fnFilter( '^(?'+ this.value +').*', this.name,true,false );
                           }
                           else if (this.value.startsWith("!")==false)
                           {

                               licensetable.fnFilter( this.value, this.name,true,false );
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
      License & Card
      <small>Administration</small>
      </h1>

      <ol class="breadcrumb">
      <li><a href="{{ url('/home') }}"><i class="fa fa-dashboard"></i> Home</a></li>
      <li><a href="#">Admin</a></li>
      <li class="active">License & Card</li>
      </ol>
    </section>

    <section class="content">

      <div class="row">
        <div class="col-md-12">
          <div class="box box-primary">
            <div class="box-header with-border">

              <div class="col-md-12">

                @foreach($category as $table)

                  @if ($table->Option==$type)
                    <a href="{{ url('/licensetracker') }}/{{$table->Option}}"><button type="button" class="btn btn-success btn-small">{{$table->Option}}</button></a>
                  @else
                    <a href="{{ url('/licensetracker') }}/{{$table->Option}}"><button type="button" class="btn btn-danger btn-small">{{$table->Option}}</button></a>
                  @endif

                @endforeach

                <br><br>


                <table id="licensetable" class="license" cellspacing="0" width="100%" padding="30px" style="font-size: 13px;">
                    <thead>
                    @if($licenses)
                      <tr class="search">
                        {{-- <td align='center'><input type='hidden' class='search_init' /></td> --}}
                        @foreach($licenses as $key=>$values)

                          @if ($key==0)
                          <?php $i = 0; ?>

                          @foreach($values as $field=>$a)

                            @if ($type=="Company")

                              @if ($i==0|| $i==1|| $i==2|| $i==3|| $i==4|| $i==7)
                                <td align='center'><input type='hidden' class='search_init' name='{{$i}}' placemark='{{$a}}'></td>
                              @else
                                <td align='center'><input type='text' class='search_init' name='{{$i}}'  placemark='{{$a}}'></td>
                              @endif

                            @elseif ($type=="Employee")

                              @if ($i==0|| $i==1|| $i==2|| $i==6)
                                <td align='center'><input type='hidden' class='search_init' name='{{$i}}' placemark='{{$a}}'></td>
                              @else
                                <td align='center'><input type='text' class='search_init' name='{{$i}}'  placemark='{{$a}}'></td>
                              @endif


                            @else
                              @if ($i==0|| $i==1|| $i==2)
                                <td align='center'><input type='hidden' class='search_init' name='{{$i}}' placemark='{{$a}}'></td>
                              @else
                                <td align='center'><input type='text' class='search_init' name='{{$i}}'  placemark='{{$a}}'></td>
                              @endif

                            @endif

                              <?php $i ++; ?>
                          @endforeach

                          @endif

                        @endforeach
                      </tr>
                      @endif
                        <tr>
                          @foreach($licenses as $key=>$value)

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

                      <?php $i = 0; ?>
                      @foreach($licenses as $license)

                        <tr id="row_{{ $i }}">
                              <td></td>
                            @foreach($license as $key=>$value)
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
