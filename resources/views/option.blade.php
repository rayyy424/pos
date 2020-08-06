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

      <script type="text/javascript" language="javascript" class="init">

      var editor; // use a global for the submit and return data rendering in the examples
      var oTable;

      $(document).ready(function() {

            editor = new $.fn.dataTable.Editor( {
                  ajax: {
                     "url": "{{ asset('/Include/optioncontrol.php') }}",
                     "data": {
                         "type": "{{ $type }}"
                     }
                   },
                    table: "#optiontable",
                    idSrc: "options.Id",
                    fields: [
                            {
                                   name: "options.UserId",
                                  def:{{$me->UserId}},
                                   type: "hidden"
                            },{
                                    label: "Table:",
                                    name: "options.Table",
                                    type:  'select',
                                    options: [

                                        @if($type=="Report Store")
                                          { label :"{{ $type }}", value: "tracker" },
                                        @else
                                          { label :"{{ $type }}", value: "{{ $type }}" },
                                        @endif


                                    ],
                            },{
                                    label: "Field:",
                                    name: "options.Field",
                                    type:  'select',
                                    options: [
                                        { label :"", value: "" },
                                        @if($type=="Report Store")
                                            { label :"Document_Type", value: "Document_Type" },
                                        @else
                                          @foreach($field as $item)
                                              { label :"{{$item->Field_Name}}", value: "{{$item->Field_Name}}" },
                                          @endforeach
                                        @endif

                                    ],
                            },{
                                   label: "Option:",
                                   name: "options.Option"
                            },

                            @if($type=="costing" || $type=="polisting" || $type=="invoicelisting")

                            @elseif($type=="tracker")
                            {
                                    label: "Update_Column:",
                                    name: "options.Update_Column",
                                    type:  'select2',
                                    options: [
                                        { label :"", value: "" },
                                        @foreach($columns as $col)
                                            { label :"{{$col->Col}}", value: "{{$col->Col}}" },
                                        @endforeach
                                    ],
                            },{
                                    label: "Projects:",
                                    name: "projects[].Id",
                                    type:  'checkbox'

                            },
                            @elseif($type=="Report Store")
                            {
                                    label: "Update_Column:",
                                    name: "options.Update_Column",
                                    type:  'select2',
                                    options: [
                                        { label :"", value: "" },
                                        @foreach($columns as $col)
                                            { label :"{{$col->Col}}", value: "{{$col->Col}}" },
                                        @endforeach
                                    ],
                            },{
                                    label: "Projects:",
                                    name: "projects[].Id",
                                    type:  'checkbox'

                            },
                            {
                                   label: "Section:",
                                   name: "options.Section"
                            },{
                                   label: "Description:",
                                   name: "options.Description"
                            },
                            @endif


                    ]
            } );

            // Activate an inline edit on click of a table cell
            $('#optiontable').on( 'click', 'tbody td', function (e) {
                  editor.inline( this, {
                 onBlur: 'submit'
                } );
            } );

            oTable=$('#optiontable').dataTable( {
                    ajax: {
                       "url": "{{ asset('/Include/optioncontrol.php') }}",
                       "data": {
                           "type": "{{ $type }}"
                       }
                     },
                     @if($type=="costing" || $type=="polisting" || $type=="invoicelisting")
                      columnDefs: [{ "visible": false, "targets": [1,2,5,7,8] },{"className": "dt-center", "targets": "_all"}],
                      @elseif($type=="Report Store")
                       columnDefs: [{ "visible": false, "targets": [1,2] },{"className": "dt-center", "targets": "_all"}],
                     @elseif($type=="tracker" || $type=="costing" || $type=="polisting" || $type=="invoicelisting")
                       columnDefs: [{ "visible": false, "targets": [1,2,7,8] },{"className": "dt-center", "targets": "_all"}],
                     @else
                      columnDefs: [{ "visible": false, "targets": [1,2,5,6,7,8] },{"className": "dt-center", "targets": "_all"}],
                     @endif

                    responsive: false,
                    colReorder: true,
                    stateSave:false,
                    dom: "Bftp",
                    iDisplayLength:10,
                    bAutoWidth: true,
                    rowId:"options.Id",
                    order: [[ 1, "desc" ]],
                    columns: [
                            { data: null, "render":"", title:"No"},
                            { data: "options.Id"},
                            { data: "options.Table" },
                            { data: "options.Field",title:"Field" },
                            { data: "options.Option",title:"Option" },
                            { data: "projects", render: "[<br> ].Project_Name" ,title:"Projects"},
                            { data: "options.Update_Column", title:"Update_Column"},
                            { data: "options.Section", title:"Section"},
                            { data: "options.Description", title:"Description"}
                    ],
                    autoFill: {
                       editor:  editor
                   },
                  //  keys: {
                  //      columns: ':not(:first-child)',
                  //      editor:  editor
                  //  },
                   select: true,
                    buttons: [
                            // {
                            //   text: 'New Row',
                            //   action: function ( e, dt, node, config ) {
                            //       // clearing all select/input options
                            //       editor
                            //          .create( false )
                            //          .set( 'options.UserId', {{ $me->UserId }} )
                            //          .set( 'options.Table', '{{$type}}')
                            //          .set( 'options.Field', 'ABC')
                            //          .submit();
                            //   },
                            // },
                            { extend: "create", editor: editor },
                            { extend: "edit", editor: editor },
                            { extend: "remove", editor: editor },
                    ],

        });

        oTable.api().on( 'order.dt search.dt', function () {
          oTable.api().column(0, {search:'applied', order:'applied'}).nodes().each( function (cell, i) {
              cell.innerHTML = i+1;
          } );
      } ).draw();

      $("thead input").keyup ( function () {

              /* Filter on the column (the index) of this element */
              if ($('#optiontable').length > 0)
              {

                  var colnum=document.getElementById('optiontable').rows[0].cells.length;

                  if (this.value=="[empty]")
                  {

                     oTable.fnFilter( '^$', this.name,true,false );
                  }
                  else if (this.value=="[nonempty]")
                  {

                     oTable.fnFilter( '^(?=\\s*\\S).*$', this.name,true,false );
                  }
                  else if (this.value.startsWith("!")==true && this.value.length>1)
                  {

                     oTable.fnFilter( '^(?'+ this.value +').*', this.name,true,false );
                  }
                  else if (this.value.startsWith("!")==false)
                  {
                      oTable.fnFilter( this.value, this.name,true,false );
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
          Option Control
          <small>Admin</small>
        </h1>
        <ol class="breadcrumb">
          <li><a href="{{ url('/home') }}"><i class="fa fa-dashboard"></i> Home</a></li>
        <li><a href="#">Admin</a></li>
          <li class="active">Option Control</li>
        </ol>
      </section>

    <!-- Main content -->
    <section class="content">
      <div class="row">
          <div class="col-md-12">
            <div class="box box-success">
                <div class="box-body">

                  @foreach($category as $table)

                    @if ($table->Table==$type)
                      <a href="{{ url('/optioncontrol') }}/{{$table->Table}}"><button type="button" class="btn btn-danger btn-lg">{{$table->Category}}</button></a>
                    @else
                      <a href="{{ url('/optioncontrol') }}/{{$table->Table}}"><button type="button" class="btn btn-success btn-lg">{{$table->Category}}</button></a>
                    @endif

                  @endforeach

                  @if($type=="Report Store")
                    <a href="{{ url('/optioncontrol') }}/Report Store"><button type="button" class="btn btn-danger btn-lg">Report Store</button></a>
                  @else
                    <a href="{{ url('/optioncontrol') }}/Report Store"><button type="button" class="btn btn-success btn-lg">Report Store</button></a>
                  @endif

                  <br><br>

                    <table id="optiontable" class="display" cellspacing="0" width="100%" padding="30px" style="font-size: 13px;">
                      <thead>
                        <tr class="search">

                          @foreach($options as $key=>$value)
                            @if ($key==0)
                            <?php $i = 0; ?>

                              @foreach($value as $field=>$a)

                                @if($type=="Report Store")
                                    @if ($i==0|| $i==1|| $i==2)
                                      <th align='center'><input type='hidden' class='search_init' name='{{$i}}' placemark='{{$a}}'></th>
                                    @else
                                      <th align='center'><input type='text' class='search_init' name='{{$i}}' placemark='{{$a}}'></th>
                                    @endif
                                @elseif($type=="costing" || $type=="polisting" || $type=="invoicelisting")
                                    @if ($i==0|| $i==1|| $i==2||$i==6)
                                      <th align='center'><input type='hidden' class='search_init' name='{{$i}}' placemark='{{$a}}'></th>
                                    @else
                                      <th align='center'><input type='text' class='search_init' name='{{$i}}' placemark='{{$a}}'></th>
                                    @endif

                                  @elseif($type=="tracker")
                                    @if ($i==0|| $i==1|| $i==2)
                                      <th align='center'><input type='hidden' class='search_init' name='{{$i}}' placemark='{{$a}}'></th>
                                    @else
                                      <th align='center'><input type='text' class='search_init' name='{{$i}}' placemark='{{$a}}'></th>
                                    @endif

                                  @else
                                    @if ($i==0|| $i==1|| $i==2|| $i==5)
                                      <th align='center'><input type='hidden' class='search_init' name='{{$i}}' placemark='{{$a}}'></th>
                                    @else
                                      <th align='center'><input type='text' class='search_init' name='{{$i}}' placemark='{{$a}}'></th>
                                    @endif

                                  @endif

                                    <?php $i ++; ?>
                              @endforeach

                              <th align='center'><input type='text' class='search_init' name='{{$i}}'  placemark='{{$a}}'></th>

                            @endif

                          @endforeach
                        </tr>
                          <tr>
                            @foreach($options as $key=>$value)

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
                        @foreach($options as $option)

                          <tr id="row_{{ $i }}">
                                <td></td>
                              @foreach($option as $key=>$value)
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


        (function ($, DataTable) {

        if ( ! DataTable.ext.editorFields ) {
            DataTable.ext.editorFields = {};
        }

        var _fieldTypes = DataTable.Editor ?
            DataTable.Editor.fieldTypes :
            DataTable.ext.editorFields;


        _fieldTypes.autoComplete = {
            create: function ( conf ) {
                conf._input = $('<input type="text" id="'+conf.id+'">')
                    .autocomplete( conf.opts || {} );

                return conf._input[0];
            },

            get: function ( conf ) {
                return conf._input.val();
            },

            set: function ( conf, val ) {
                conf._input.val( val );
            },

            enable: function ( conf ) {
                conf._input.autocomplete( 'enable' );
            },

            disable: function ( conf ) {
                conf._input.autocomplete( 'disable' );
            },

            // Non-standard Editor method - custom to this plug-in
            node: function ( conf ) {
                return conf._input;
            },

            update: function ( conf, options ) {
                conf._input.autocomplete( 'option', 'source', options );
            }
        };


        })(jQuery, jQuery.fn.dataTable);

        (function( factory ){
      if ( typeof define === 'function' && define.amd ) {
          // AMD
          define( ['jquery', 'datatables', 'datatables-editor'], factory );
      }
      else if ( typeof exports === 'object' ) {
          // Node / CommonJS
          module.exports = function ($, dt) {
              if ( ! $ ) { $ = require('jquery'); }
              factory( $, dt || $.fn.dataTable || require('datatables') );
          };
      }
      else if ( jQuery ) {
          // Browser standard
          factory( jQuery, jQuery.fn.dataTable );
      }
  }(function( $, DataTable ) {
  'use strict';


  if ( ! DataTable.ext.editorFields ) {
      DataTable.ext.editorFields = {};
  }

  var _fieldTypes = DataTable.Editor ?
      DataTable.Editor.fieldTypes :
      DataTable.ext.editorFields;

  _fieldTypes.select2 = {

      _addOptions: function ( conf, opts ) {
          var elOpts = conf._input[0].options;

          elOpts.length = 0;

          if ( opts ) {
              DataTable.Editor.pairs( opts, conf.optionsPair, function ( val, label, i ) {
                  elOpts[i] = new Option( label, val );
              } );
          }
      },

      create: function ( conf ) {
          conf._input = $('<select/>')
              .attr( $.extend( {
                  id: DataTable.Editor.safeId( conf.id )
              }, conf.attr || {} ) );

          var options = $.extend( {
                  width: '100%'
              }, conf.opts );

          _fieldTypes.select2._addOptions( conf, conf.options || conf.ipOpts );
          conf._input.select2( options );

          var open;
          conf._input
              .on( 'select2:open', function () {
                  open = true;
              } )
              .on( 'select2:close', function () {
                  open = false;
              } );

          // On open, need to have the instance update now that it is in the DOM
          this.one( 'open.select2-'+DataTable.Editor.safeId( conf.id ), function () {
              conf._input.select2( options );

              if ( open ) {
                  conf._input.select2( 'open' );
              }
          } );

          return conf._input[0];
      },

      get: function ( conf ) {
          var val = conf._input.val();
          val =  conf._input.prop('multiple') && val === null ?
              [] :
              val;

          return conf.separator ?
              val.join( conf.separator ) :
              val;
      },

      set: function ( conf, val ) {
          if ( conf.separator && ! $.isArray( val ) ) {
              val = val.split( conf.separator );
          }

          // Clear out any existing tags
          if ( conf.opts && conf.opts.tags ) {
              conf._input.val([]);
          }

          // The value isn't present in the current options list, so we need to add it
          // in order to be able to select it. Note that this makes the set action async!
          // It doesn't appear to be possible to add an option to select2, then change
          // its label and update the display
          var needAjax = false;

          if ( conf.opts && conf.opts.ajax ) {
              if ( $.isArray( val ) ) {
                  for ( var i=0, ien=val.length ; i<ien ; i++ ) {
                      if ( conf._input.find('option[value="'+val[i]+'"]').length === 0 ) {
                          needAjax = true;
                          break;
                      }
                  }
              }
              else {
                  if ( conf._input.find('option[value="'+val+'"]').length === 0 ) {
                      needAjax = true;
                  }
              }
          }

          if ( needAjax ) {
              $.ajax( $.extend( {
                  beforeSend: function ( jqXhr, settings ) {
                      // Add an initial data request to the server, but don't
                      // override `data` since the dev might be using that
                      var initData = 'initialValue=true&value='+
                          JSON.stringify(val);

                      if ( settings.type === 'GET' ) {
                          settings.url += settings.url.indexOf('?') === -1 ?
                              '?'+initData :
                              '&'+initData;
                      }
                      else {
                          settings.data = settings.data ?
                              settings.data +'&'+ initData :
                              initData;
                      }
                  },
                  success: function ( json ) {
                      var addOption = function ( option ) {
                          if ( conf._input.find('option[value="'+option.id+'"]').length === 0 ) {
                              $('<option/>')
                                  .attr('value', option.id)
                                  .text( option.text )
                                  .appendTo( conf._input );
                          }
                      }

                      if ( $.isArray( json ) ) {
                          for ( var i=0, ien=json.length ; i<ien ; i++ ) {
                              addOption( json[i] );
                          }
                      }
                      else if ( json.results && $.isArray( json.results ) ) {
                          for ( var i=0, ien=json.results.length ; i<ien ; i++ ) {
                              addOption( json.results[i] );
                          }
                      }
                      else {
                          addOption( json );
                      }

                      conf._input
                          .val( val )
                          .trigger( 'change', {editor: true} );
                  }
              }, conf.opts.ajax ) );
          }
          else {
              conf._input
                  .val( val )
                  .trigger( 'change', {editor: true} );
          }
      },

      enable: function ( conf ) {
          $(conf._input).removeAttr( 'disabled' );
      },

      disable: function ( conf ) {
          $(conf._input).attr( 'disabled', 'disabled' );
      },

      // Non-standard Editor methods - custom to this plug-in
      inst: function ( conf ) {
          var args = Array.prototype.slice.call( arguments );
          args.shift();

          return conf._input.select2.apply( conf._input, args );
      },

      update: function ( conf, data ) {

          var val = _fieldTypes.select2.get( conf );

          _fieldTypes.select2._addOptions( conf, data );

          // Restore null value if it was, to let the placeholder show
          if ( val === null ) {
              _fieldTypes.select2.set( conf, null );
          }

          $(conf._input).trigger('change', {editor: true} );
      },

      focus: function ( conf ) {
          if ( conf._input.is(':visible') && conf.onFocus === 'focus' ) {
              conf._input.select2('open');
          }
      },

      owns: function ( conf, node ) {
          if ( $(node).closest('.select2-container').length || $(node).closest('.select2').length || $(node).hasClass('select2-selection__choice__remove') ) {
              return true;
          }
          return false;
      }
  };


  }));

    </script>

@endsection
