@extends('app')

@section('datatable-css')

    <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.10.12/css/jquery.dataTables.min.css">
    <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/buttons/1.2.1/css/buttons.dataTables.min.css">
    <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/responsive/2.1.0/css/responsive.dataTables.min.css">
    <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/select/1.2.0/css/select.dataTables.min.css">
    <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/keytable/2.1.2/css/keyTable.dataTables.min.css">
    <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/autofill/2.1.2/css/autoFill.dataTables.min.css">
    <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/colreorder/1.3.2/css/colReorder.dataTables.min.css">
    <link rel="stylesheet" type="text/css" href="{{ asset('/plugin/colorpicker/spectrum.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('/plugin/css/editor.dataTables.min.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('/plugin/ReorderDiv/CSS/jquery-ui.css') }}">
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
      .colorSquare {
        width: 100px;
        height: 20px;
        margin:auto;
        border: 1px solid rgba(0, 0, 0, .2);
      }
      .sp-preview {
        width:80px;
      }
      table.dataTable tbody th.dt-body-center,
      table.dataTable tbody td.dt-body-center {
        text-align: center;
      }

      .selectedtrackerlink{
        margin-left: 10px;
        color:#dd4b39;
        font-size: 14px;
        font-weight: bold;
        text-decoration: underline;
      }

      .btn{
        margin-left: 2px;
      }

      ul {
        list-style-type: none;
        padding-left: 0px;
      }

      .ui-state-default{
       margin-top: 3px;
       padding: 5px;
       /*background-color: #3c8dbc;*/
       font-size: 16px;
       font-weight: 400;
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
      <script type="text/javascript" language="javascript" src="{{ asset('/plugin/colorpicker/spectrum.js') }}"></script>
      {{-- chart js --}}
      <script type="text/javascript" language="javascript" src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.4.0/Chart.bundle.min.js"></script>

      <script type="text/javascript" language="javascript" class="init">

      var editor;



      $(document).ready(function() {

        (function ($, DataTable) {

          if ( ! DataTable.ext.editorFields ) {
              DataTable.ext.editorFields = {};
          }

          var Editor = DataTable.Editor;
          var _fieldTypes = DataTable.ext.editorFields;

          _fieldTypes.colorpicker = {
              create: function ( conf ) {
                  var that = this;

                  conf._enabled = true;

                  // Create the elements to use for the input
                  conf._input = $(
                      '<div id="' + Editor.safeId(conf.id) + '">' +
                          '<input type="text" class="basic" id="spectrum" name="spectrum" value="#aaa" />' +
                          '<em id="basic-log"></em>' +
                          '</div>');



                  // Use the fact that we are called in the Editor instance's scope to call
                  // input.ClassName
                  $("input.basic", conf._input).spectrum({

                      // color: "#aaa",
                      change: function (color) {
                          $("#basic-log").text(color.toHexString());
                      }
                  });
                  return conf._input;
              },

              get: function (conf) {

                  var val = $("input.basic", conf._input).spectrum("get").toHexString();
                  return val;
              },

              set: function (conf, val) {
                  document.getElementsByClassName('basic').value="#aaa";
                  $("input.basic", conf._input).spectrum({
                      color: val,
                      change: function (color) {
                          // $("#basic-log").text("change called: " + color.toHexString());
                      }
                  });
              },

              enable: function ( conf ) {
                  conf._enabled = true;
                  $(conf._input).removeClass( 'disabled' );
              },

              disable: function ( conf ) {
                  conf._enabled = false;
                  $(conf._input).addClass( 'disabled' );
              }
          };



          })(jQuery, jQuery.fn.dataTable);
                editor = new $.fn.dataTable.Editor( {
                      ajax: {
                         "url": "{{ asset('/Include/chartcolumn.php') }}",
                         "data": {
                             "ChartViewId": "{{ $chartviewid }}",
                         }
                       },
                        table: "#chartcolumntable",
                        fields: [
                                {
                                        label: "Id",
                                        name: "chartcolumns.Id",
                                        type: "hidden"
                                },
                                {
                                        name: "chartcolumns.ChartViewId",
                                        type: "hidden"
                                },
                                {
                                        label: "Chart View Name :",
                                        name: "chartcolumns.Column_Name",
                                        type: "select",
                                        options: [
                                            { label :"", value: "" },
                                            @if($columns)
                                              @foreach($columns as $column)
                                                  { label :"{{$column->Column_Name}}", value: "{{$column->Column_Name}}" },
                                              @endforeach
                                            @endif


                                        ],
                                },
                                {
                                        label: "Chart View Type :",
                                        name: "chartcolumns.Display_Name"
                                },
                                {
                                        label: "Count Type :",
                                        name: "chartcolumns.Count_Type",
                                        type: "select",
                                        options: [
                                            { label :"", value: "" },
                                            { label :"Count Empty", value: "Count Empty" },
                                            { label :"Count Non Empty", value: "Count Non Empty" },
                                            { label :"Count With Condition", value: "Count With Condition" },

                                        ],
                                },{
                                        label: "Condition :",
                                        name: "chartcolumns.Condition"
                                },{
                                        label: "Series_Color :",
                                        name: "chartcolumns.Series_Color",
                                        type: "colorpicker",

                                },{
                                          label: "Series Type :",
                                          name: "chartcolumns.Series_Type",
                                          type: "select",
                                          options: [
                                              { label :"", value: "" },
                                              { label :"bar", value: "bar" },
                                              { label :"line", value: "line" },

                                          ],
                                  }


                        ]
                 } );

                 editor.on('open', function () {
                    $('div.DTE_Footer').css( 'text-indent', -1 );
                });


                 var chartcolumn = $('#chartcolumntable').dataTable( {

                   ajax: {
                      "url": "{{ asset('/Include/chartcolumn.php') }}",
                      "data": {
                          "ChartViewId": "{{ $chartviewid }}",
                      }
                    },
                   dom: "Bfrt",
                   bAutoWidth: false,
                   bPaginate:false,
                  //  rowId:"userability.Id",
                   //aaSorting:false,

                   @if ($chartview->Chart_View_Type == "Total" )
                     columnDefs: [{ "visible": false, "targets": [1,2,8,9] },{"className": "dt-center", "targets": "_all"}],

                   @else
                     columnDefs: [{ "visible": false, "targets": [1,2,9] },{"className": "dt-center", "targets": "_all"}],

                   @endif


                   bScrollCollapse: true,
                   columns: [
                           { data: null, render:"", title:"No"},
                           { data: "chartcolumns.Id", title:"Id"},
                           { data: "chartcolumns.ChartViewId", title:"ChartViewId" },
                           { data: "chartcolumns.Column_Name", title:"Column_Name"},
                           { data: "chartcolumns.Display_Name", title:"Display_Name"},
                           { data: "chartcolumns.Series_Type", title:"Series_Type"},
                           { data: "chartcolumns.Count_Type", title:"Count_Type"},
                           { data: "chartcolumns.Condition", title:"Condition"},
                           { data: "chartcolumns.Series_Color", title:"Series_Color",
                              render: function (data, type, row) {
                                  if (type === 'display') {
                                      return '<div class="colorSquare" style="background:' + data + ';"></div>';
                                  }
                                  return data;
                              },
                           },
                           { data: "chartcolumns.created_at", title:"created_at"}

                   ],

                   select: {
                           style:    'os',
                           selector: 'td'
                   },
                   autoFill: {
                      editor:  editor
                  },
                  buttons: [
                          { text: 'New Row',
                            action: function ( e, dt, node, config ) {
                                editor
                                   .create( false )
                                   .set( 'chartcolumns.ChartViewId', {{ $chartviewid }} )
                                   .submit();
                            },
                          },
                          { extend: "remove", editor: editor }
                  ],

                });

                $('#chartcolumntable').on( 'click', 'tbody td', function (e) {
                      editor.inline( this, {
                     onBlur: 'submit'
                    } );
                } );

                chartcolumn.api().on( 'order.dt search.dt', function () {
                  chartcolumn.api().column(0, {search:'applied', order:'applied'}).nodes().each( function (cell, i) {
                      cell.innerHTML = i+1;
                  } );
              } ).draw();

              $("thead input").keyup ( function () {

                      /* Filter on the column (the index) of this element */
                      if ($('#chartcolumntable').length > 0)
                      {

                          var colnum=document.getElementById('chartcolumntable').rows[0].cells.length;

                          if (this.value=="[empty]")
                          {

                             chartcolumn.fnFilter( '^$', $("thead input").index(this)-colnum,true,false );
                          }
                          else if (this.value=="[nonempty]")
                          {

                             chartcolumn.fnFilter( '^(?=\\s*\\S).*$', $("thead input").index(this)-colnum,true,false );
                          }
                          else if (this.value.startsWith("!")==true && this.value.length>1)
                          {

                             chartcolumn.fnFilter( '^(?'+ this.value +').*', $("thead input").index(this)-colnum,true,false );
                          }
                          else if (this.value.startsWith("!")==false)
                          {

                            chartcolumn.fnFilter( this.value, $("thead input").index(this)-colnum,true,false );
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
      Chart Columns Management
      <small>Admin</small>
      </h1>

      <ol class="breadcrumb">
      <li><a href="{{ url('/home') }}"><i class="fa fa-dashboard"></i> Home</a></li>
      <li><a href="#">Admin</a></li>
      <li class="active">Chart Columns Management</li>
      </ol>
    </section>

    <section class="content">

      <div class="row">
        <div class="box box-primary">
          <div class="box-body">

            <div class="col-md-12">

              <h4 class="box-title"><b>Chart View Name : {{$chartview->Chart_View_Name}}<b></h4>
              <h5 class="box-title"><b>Chart View Type : {{$chartview->Chart_View_Type}}<b></h5>

            </div>



             <div class="box-body">
               <a href='{{ url('/chartpreview') }}/{{$chartviewid}}' target="_blank"><button type="button" class="btn btn-primary btn-lg">Chart Preview</button></a>

             </div>

            <div class="col-md-12">

              <table id="chartcolumntable" class="display" cellspacing="0" width="100%" padding="30px" style="font-size: 13px;">
                  <thead>
                   @if($chartcolumn)
                    <tr class="search">
                      <td align='center'><input type='hidden' class='search_init' /></td>

                      @foreach($chartcolumn as $key=>$values)
                        @if ($key==0)

                        @foreach($values as $field=>$value)

                            <td align='center'><input type='text' class='search_init' /></td>

                        @endforeach

                        @endif

                      @endforeach
                    </tr>
                    @endif
                      <tr>
                        @foreach($chartcolumn as $key=>$value)

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
                    @foreach($chartcolumn as $column)

                      <tr id="row_{{ $i }}">
                          <td></td>
                          @foreach($column as $key=>$value)
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
