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
      div.DTE_Body div.DTE_Body_Content div.DTE_Field {
            padding: 15px;
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
      {{-- chart js --}}
      <script type="text/javascript" language="javascript" src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.4.0/Chart.bundle.min.js"></script>

      <script type="text/javascript" language="javascript" class="init">

      var editor;
      $(document).ready(function() {
                editor = new $.fn.dataTable.Editor( {
                      ajax: {
                         "url": "{{ asset('/Include/dependencymaintenance.php') }}",
                         @if($projectid)
                         "data": {
                             "ProjectId": "{{ $projectid }}"
                         }
                         @endif
                       },
                       idSrc:"dependencyrules.Id",
                        table: "#dependencytable",
                        fields: [
                                {
                                        label: "Id",
                                        name: "dependencyrules.Id",

                                        type: "hidden"
                                },{
                                        name: "dependencyrules.UserId",

                                        type: "hidden"
                                },
                                {
                                        label: "Active :",
                                        name: "dependencyrules.Active",
                                        type: "select",
                                        options: [
                                          { label :"Yes", value: "1" },
                                          { label :"No", value: "0" }
                                        ]
                                },
                                {
                                        label: "Project Name:",
                                        name: "dependencyrules.ProjectId",
                                        type:  'select2',
                                        options: [
                                            { label :"", value: "" },
                                            @foreach($projects as $project)
                                                { label :"{{$project->Project_Name}}", value: "{{$project->Id}}" },
                                            @endforeach
                                        ]

                                },
                                {
                                        label: "Title :",
                                        name: "dependencyrules.Title"
                                },{
                                        label: "Sequence :",
                                        name: "dependencyrules.Sequence",
                                        attr: {
                                          type: "number"
                                        }

                                },{
                                        label: "Column1:",
                                        name: "dependencyrules.Column1",
                                        type:  'select2',
                                        options: [
                                            { label :"", value: "" },
                                            @foreach($columns as $column)
                                                { label :"{{$column->Column_Name}}", value: "{{$column->Column_Name}}" },
                                            @endforeach

                                        ]

                                },{
                                        label: "Column1 Status:",
                                        name: "dependencyrules.Column1_Status",
                                        type:  'select',
                                        options: [
                                            { label :"-", value: "" },
                                            { label :"[nonempty]", value: "[nonempty]" }

                                        ]

                                },{
                                        label: "Column2:",
                                        name: "dependencyrules.Column2",
                                        type:  'select2',
                                        options: [
                                            { label :"", value: "" },
                                            @foreach($columns as $column)
                                                { label :"{{$column->Column_Name}}", value: "{{$column->Column_Name}}" },
                                            @endforeach

                                        ]

                                },{
                                        label: "Column2 Status:",
                                        name: "dependencyrules.Column2_Status",
                                        type:  'select',
                                        options: [
                                            { label :"-", value: "" },
                                            { label :"[nonempty]", value: "[nonempty]" }

                                        ]
                                },{
                                        label: "Column3:",
                                        name: "dependencyrules.Column3",
                                        type:  'select2',
                                        options: [
                                            { label :"", value: "" },
                                            @foreach($columns as $column)
                                                { label :"{{$column->Column_Name}}", value: "{{$column->Column_Name}}" },
                                            @endforeach

                                        ]

                                },{
                                        label: "Column3 Status:",
                                        name: "dependencyrules.Column3_Status",
                                        type:  'select',
                                        options: [
                                            { label :"-", value: "" },
                                            { label :"[nonempty]", value: "[nonempty]" }

                                        ]
                                },{
                                        label: "Target Column:",
                                        name: "dependencyrules.Target_Column",
                                        type:  'select2',
                                        options: [
                                            { label :"", value: "" },
                                            @foreach($columns as $column)
                                                { label :"{{$column->Column_Name}}", value: "{{$column->Column_Name}}" },
                                            @endforeach

                                        ]

                                },{
                                        label: "Target Status:",
                                        name: "dependencyrules.Target_Status"

                                },
                                {
                                        label: "Users List:",
                                        name: "notify[].Id",
                                        type:  'checkbox'


                                }

                        ]
                 } );

              //    editor.on( 'preSubmit', function ( e, o, action ) {
              //      if ( action == 'edit' ) {
              //        var ProjectId = this.field( 'dependencyrules.ProjectId' );
              //        var Title = this.field( 'dependencyrules.Title' );
               //
              //          if ( ProjectId.val()==="") {
               //
              //                ProjectId.error( 'Project is required!' );
              //                return false;
              //          }
               //
              //          if ( Title.val()==="") {
               //
              //                Title.error( 'Title is required!' );
              //                return false;
              //          }
               //
              //          return true;
               //
              //      }
              //  } );

                 editor.on('open', function () {
                    $('div.DTE_Footer').css( 'text-indent', -1 );

                });

                $('#dependencytable').on( 'click', 'tbody td', function (e) {

                      editor.inline( this, {
                          onBlur: 'submit'
                    } );
                } );

                 var notify = $('#dependencytable').dataTable( {

                   ajax: {
                      "url": "{{ asset('/Include/dependencymaintenance.php') }}",
                      @if($projectid)
                      "data": {
                          "ProjectId": "{{ $projectid }}"
                      }
                      @endif
                    },
                   dom: "Blfrtp",
                   bAutoWidth: false,
                   responsive: false,
                   colReorder: false,
                   sScrollX: "100%",
                   rowId:"dependencyrules.Id",
                   sScrollY: "100%",
                   //aaSorting:false,
                   columnDefs: [{ "visible": false, "targets": [0,2,3] }],
                   bScrollCollapse: true,
                   "lengthMenu": [[10, 50, 100, 500, -1], [10, 50, 100,500, "All"]],
                   columns: [
                           { title:"Action",
                           "render": function ( data, type, full, meta ) {
                              return '';

                           }},
                           { data: null, render:"", title:"No"},
                           { data: "dependencyrules.Id",title:"Id" },
                           { data: "dependencyrules.ProjectId",title:"ProjectId" },
                           { data: "dependencyrules.Active",title:"Active",
                           "render": function ( data, type, full, meta ) {
                               if (full.dependencyrules.Active==1)
                                  return 'Yes';
                               else
                                  return 'No';
                               endif

                           }},
                           { data: "projects.Project_Name",Title:"Project_Name", editField: "dependencyrules.ProjectId"},
                           { data: "dependencyrules.Title",Title:"Title"},
                           { data: "dependencyrules.Sequence",Title:"Sequence"},
                           { data: "dependencyrules.Column1" ,Title:"Criteria_1",width:"300px"},
                           { data: "dependencyrules.Column1_Status" ,Title:"Status_1"},
                           { data: "dependencyrules.Column2" ,Title:"Criteria_2",width:"200px"},
                           { data: "dependencyrules.Column2_Status" ,Title:"Status_2"},
                           { data: "dependencyrules.Column3" ,Title:"Criteria_3",width:"200px"},
                           { data: "dependencyrules.Column3_Status" ,Title:"Status_3"},

                           { data: "dependencyrules.Target_Column" ,Title:"Target_Column",width:"200px"},
                           { data: "dependencyrules.Target_Status" ,Title:"Target_Status"},
                           { data: "creator.Name",Title:"Creator" },
                           { data: "notify", render: "[<br> ].Name", title:'Notify'}
                   ],

                   select: {
                           style:    'os',
                           selector: 'td'
                   },
                  buttons: [
                          { text: 'New Row',
                            action: function ( e, dt, node, config ) {
                                editor
                                   .create( false )
                                   .set( 'dependencyrules.UserId', {{$me->UserId}})
                                   // .set( 'dependencyrules.Target_Table', "po")
                                   @if($projectid!=0)
                                     .set( 'dependencyrules.ProjectId', {{$projectid}})
                                   @endif
                                   .submit();
                            },
                          },
                          { extend: "edit", editor: editor },
                          { extend: "remove", editor: editor },
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

                notify.api().on( 'order.dt search.dt', function () {
                  notify.api().column(1, {search:'applied', order:'applied'}).nodes().each( function (cell, i) {
                      cell.innerHTML = i+1;
                  } );
              } ).draw();

              $("thead input").keyup ( function () {

                      /* Filter on the column (the index) of this element */
                      if ($('#dependencytable').length > 0)
                      {

                          var colnum=document.getElementById('dependencytable').rows[0].cells.length;

                          if (this.value=="[empty]")
                          {

                             notify.fnFilter( '^$', $("thead input").index(this)-colnum,true,false );
                          }
                          else if (this.value=="[nonempty]")
                          {

                             notify.fnFilter( '^(?=\\s*\\S).*$', $("thead input").index(this)-colnum,true,false );
                          }
                          else if (this.value.startsWith("!")==true && this.value.length>1)
                          {

                             notify.fnFilter( '^(?'+ this.value +').*', $("thead input").index(this)-colnum,true,false );
                          }
                          else if (this.value.startsWith("!")==false)
                          {

                            notify.fnFilter( this.value, $("thead input").index(this)-colnum,true,false );
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
      Dependency Rules Maintenance
      <small>Project Management</small>
      </h1>

      <ol class="breadcrumb">
      <li><a href="{{ url('/home') }}"><i class="fa fa-dashboard"></i> Home</a></li>
      <li><a href="#">Project Management</a></li>
      <li class="active">Dependency Rules Maintenance</li>
      </ol>
    </section>

    <section class="content">
      <div class="row">
        <div class="box box-primary">
          <div class="box-body">
            <div class="col-md-12">

              <table id="dependencytable" class="display" cellspacing="0" width="100%" padding="30px" style="font-size: 13px;">
                  <thead>
                    <tr class="search">
                      <td align='center'><input type='hidden' class='search_init' /></td>
                      <td align='center'><input type='hidden' class='search_init' /></td>
                      @foreach($dependencyrules as $key=>$values)
                        @if ($key==0)

                        @foreach($values as $field=>$value)

                            <td align='center'><input type='text' class='search_init' /></td>

                        @endforeach

                        @endif

                      @endforeach
                    </tr>
                      <tr>
                        @foreach($dependencyrules as $key=>$value)

                          @if ($key==0)
                                <td>Action</td>
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
