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
    <link rel="stylesheet" type="text/css" href="{{ asset('/plugin/ReorderDiv/CSS/jquery-ui.css') }}">

    <link rel="stylesheet" type="text/css" href="http://code.jquery.com/ui/1.10.0/themes/base/jquery-ui.css">

    {{-- <link rel="stylesheet" type="text/css" href="{{ asset('/plugin/examples/resources/syntax/shCore.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('/Plugin/examples/resources/demo.css') }}"> --}}

    <style type="text/css" class="init">
      /*#category2 option{
          display:none;
      }

      #category2 option.label{
          display:block;
      }*/
      a.buttons-collection {
        margin-left: 1em;
      }
      .container1{
              width: 1200px;
              margin-left: 50px;
              padding: 10px;
      }

      .trackerlink{
        margin-left: 10px;
      }

      .action {
        width:100%;
        text-align: left;
      }

      div.DTE_Inline div.DTE_Inline_Field div.DTE_Field input, div.DTE_Inline div.DTE_Inline_Buttons div.DTE_Field input {
          width: auto;
      }

      div.DTE_Field_Type_textarea textarea {
        padding: 3px;
        width: auto;
        height: auto;
        min-width: 600px;
        min-height: 100px;
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
       background-color: #3c8dbc;
       font-size: 16px;
       font-weight: 400;
      }


  /*/  vertical-align: middle;*/
  .nav-tabs-custom>.nav-tabs>li:first-of-type {
         margin-left: 2px;
    }

  .nav-tabs-custom>.nav-tabs>li
     {
      width: 19.5%;
      /*background-color:#ccc;*/
      margin:2px;
      /*border-top: 2px solid #337ab7;*/
    }

    .nav-tabs-custom>.nav-tabs>li:hover
       {

        color:black;

        border-top: 3px solid red;
      }

      .tableheader{
        background-color: gray;
        border:2px solid black;
        color:white;

      }

      .tablebody{
        text-align: center;
        padding:10px;
        font-size:12px;
      }

      table tr td{
        padding:2px
      }

      ul{
            list-style-type: circle;
      }

      #option2 option, #option5 option, #option8 option{
          display:none;
      }

      #option2  option.label, #option5 option.label,  #option8 option.label{
          display:block;
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


      <script type="text/javascript" language="javascript" class="init">
      var trackertable
      var editor;

      $(function() {
         $( "#sorttracker" ).sortable();
         $( "#sorttracker" ).disableSelection();
       });

      $(document).ready(function() {

        @if($trackerid>0)

        editor = new $.fn.dataTable.Editor( {
                ajax: {
                   "url": "{{ asset('/Include/projecttracker.php') }}",
                   "data": {
                     "columns": "{{ $columns }}",
                     "projectid": "{{ $projectid }}",
                     "condition": "{{ $condition }}",
                     "userid": "{{ $me->UserId }}"
                   }
                 },
                table: "#trackertable",
                idSrc: "Id",
                fields: [
                  {
                         label: "ProjectId",
                         name: "ProjectId",
                  },
                  {
                         label: "Project_Code",
                         name: "Project_Code",
                         type:  'autoComplete',
                         "opts": {
                           "source": [
                             // array of genres...
                             @if($projectcodes)
                               @foreach($projectcodes as $projectcode)

                               { label :"{{$projectcode->Project_Code}} - {{$projectcode->Site_ID}}", value: "{{$projectcode->Project_Code}}" },

                               @endforeach
                             @endif
                           ]
                         },
                  },

                  @if ($trackercolumns!="")

                    @foreach ($trackercolumns as $column)

                      {
                             label: "{{$column->Column_Name}}",
                             name: "{{$column->Column_Name}}",
                             @if($column->Type=="Textbox")
                             @elseif($column->Type=="Textarea")
                              type: "textarea"
                             @elseif($column->Type=="Date")
                               type:   'datetime',
                               format: 'DD-MMM-YYYY'
                             @elseif($column->Type=="Dropdown")
                               type:  'select',
                               options: [
                                   { label :"", value: "" },
                                   @foreach($options as $option)
                                     @if ($option->Field==$column->Column_Name)
                                       { label :"{{$option->Option}}", value: "{{$option->Option}}" },
                                     @endif
                                   @endforeach


                               ],
                             @elseif($column->Type=="Name List")
                               type:  'select',
                               options: [
                                   { label :"", value: "" },
                                   @foreach($users as $user)
                                       { label :"{{$user->Name}}", value: "{{$user->Name}}" },
                                   @endforeach
                               ],
                              @elseif($column->Type=="PO List")
                                 type:  'autoComplete',
                                 "opts": {
                                   "source": [
                                     // array of genres...
                                     "No PO",
                                          @if($poitems)
                                            @foreach($poitems as $poitem)
                                               @if($poitem->ProjectId==$projectid)
                                                { label :"{{$poitem->PO_No}} | {{$poitem->Shipment_Num}} | {{$poitem->Site_Name}} | {{$poitem->Item_Description}}", value: "{{$poitem->PO_No}} | {{$poitem->Shipment_Num}} | {{$poitem->Site_Name}}" },
                                               @endif
                                            @endforeach
                                          @endif
                                   ]
                                 },

                              @elseif($column->Type=="Invoice List")
                                    type:  'autoComplete',
                                    "opts": {
                                      "source": [
                                        // array of genres...
                                             @if($invoices)
                                               @foreach($invoices as $invoice)
                                                  @if($invoice->ProjectId==$projectid)
                                                   "{{$invoice->Invoice_No}}",
                                                  @endif
                                               @endforeach
                                             @endif
                                      ]
                                    },
                                    // options: [
                                    //   { label :"", value: "" },
                                    //   { label :"-", value: null },
                                    //     @if($invoices)
                                    //       @foreach($invoices as $invoice)
                                    //         @if($invoice->ProjectId==$projectid)
                                    //           { label :"{{$invoice->Invoice_No}}", value: "{{$invoice->Invoice_No}}" },
                                    //         @endif
                                    //       @endforeach
                                    //     @endif
                                    // ],
                             @elseif($column->Type=="Currency")
                                attr: {
                                  type: "number"
                                },
                             @endif

                      },

                    @endforeach

                  @endif
                ]
        } );

        // Activate an inline edit on click of a table cell
              $('#trackertable').on( 'click', 'tbody td', function (e) {

                clearTimeout(timer);

                  @if($trackerwriteaccess)
                    editor.inline( this, {
                        onBlur: 'submit'
                  } );
                  @endif
              } );

              $('#trackertable').on( 'mouseleave', 'tbody td .viewtext', function (e) {

                clearTimeout(timer);

              } );

        trackertable=$('#trackertable').dataTable( {
            ajax: {
               "url": "{{ asset('/Include/projecttracker.php') }}",
               "type": "POST",
               "data": {
                 "columns": "{{ $columns }}",
                 "projectid": "{{ $projectid }}",
                 "condition": "{{ $condition }}"
               }
             },
             fnInitComplete: function(oSettings, json) {

               @if($trackerwriteaccess)
                //have write access
               @else
                //no write access
                editor.disable();
                trackertable.autoFill().disable();
               @endif
             },
              columnDefs: [{ "visible": false, "targets": [2,3,4] }],
              responsive: false,
              colReorder: false,
              sScrollX: "100%",
              bAutoWidth: true,
              rowId: 'Id',
              sScrollY: "100%",
              dom: "Blfrtip",
              "bScrollCollapse": true,
              columns: [
                  { data: null,"render":"", title:"No"},
                  {
                    title:"Action",
                     sortable: false,
                     "render": function ( data, type, full, meta ) {

                       @if($trackerwriteaccess)
                          return "<div class='action'><a href='#' onclick='showsubmitdocumentmodal("+full.Id+",\""+full.Site_ID+"\");'> Submit Document </a></div>";

                       @else
                          return "";

                       @endif

                     }
                  },
                  {
                    title:"Gantt",
                     sortable: false,
                     "render": function ( data, type, full, meta ) {

                        return "<a href='{{ url('/gantt') }}/"+{{$projectid}}+"/"+{{$trackerid}}+"/"+full.Id+"' target='_blank'> View </a>";

                     }
                  },
                  { data: "Id",title:"Id" },
                  { data: "ProjectId",title:"ProjectId" },
                  { data: "Project_Code",title:"Project_Code",
                  "render": function ( data, type, full, meta ) {

                     return "<div class='action'><a href='{{ url('/PObyprojectcode') }}/"+data+"' target='_blank'>"+data+"</a></div>";

                   }
                  },

                  @if ($trackercolumns!="")
                    @foreach ($trackercolumns as $column)

                      @if($column->Type=="Currency")
                        {
                          title: "{{$column->Column_Name}}",
                          data: "{{$column->Column_Name}}",
                          render: $.fn.dataTable.render.number( ',', '.', 2 )
                        },
                      @elseif($column->Type=="PO List")
                      {
                        title: "{{$column->Column_Name}}",
                        data: "{{$column->Column_Name}}",
                        "render": function ( data, type, full, meta ) {

                          if(data.includes("_"))
                          {
                              var po=data.substring(0,data.indexOf("_"));
                          }
                          if(data.includes("WO Cancel"))
                          {
                              return data;
                          }
                          else if(data.includes("No PO"))
                          {
                              return data;
                          }
                          else {

                            var po=data;
                          }

                           return "<div class='action'><a href='{{ url('/PO2') }}/"+po+"' target='_blank'>"+data+"</a></div>";

                        }
                      },
                      @elseif($column->Type=="Invoice List")
                      {
                        title: "{{$column->Column_Name}}",
                        data: "{{$column->Column_Name}}",

                          "render": function ( data, type, full, meta ) {
                             return "<div class='action'><a href='{{ url('/Invoice2') }}/"+data+"' target='_blank'>"+data+"</a></div>";

                          }

                      },
                      @elseif($column->Type=="Textarea")
                      {
                        title: "{{$column->Column_Name}}",
                        data: "{{$column->Column_Name}}",
                        "render": function ( data, type, full, meta ) {

                           var encode=data.replace(/(?:\r\n|\r|\n)/g, '<br/>');

                           return '<div class="viewtext" onmouseenter="viewtextarea(\''+encode+'\')">'+data+'</div>';

                        }
                      },
                      @else
                        { title: "{{$column->Column_Name}}",
                          data: "{{$column->Column_Name}}" },

                      @endif

                    @endforeach
                  @endif

            ],
              autoFill: {
                editor:  editor
              },
              select: true,
              buttons: [

                @if($trackerwriteaccess)
                 //have write access
                 {
                   text: 'New Record',
                   action: function ( e, dt, node, config ) {
                       // clearing all select/input options
                       editor
                          .create( false )
                          .set( 'ProjectId', '{{$projectid}}')
                          .submit();
                   },
                 },
                 { extend: "remove", editor: editor },
                 { extend: "edit", editor: editor },
                 {
                         extend: 'collection',
                         text: 'Export',
                         buttons: [
                                 'excel',
                                 'csv',
                                 'pdf'
                         ]
                 }
                @else
                 //no write access
                 {
                         extend: 'collection',
                         text: 'Export',
                         buttons: [
                                 'excel',
                                 'csv',
                                 'pdf'
                         ]
                 }
                @endif

              ],
          });

          // $('#trackertable').on( 'click', 'tbody td:not(.child), tbody span.dtr-data', function (e) {
          //       editor.inline( this, {
          //      onBlur: 'submit'
          //     } );
          // } );

          trackertable.api().on( 'order.dt search.dt', function () {
              trackertable.api().column(0, {search:'applied', order:'applied'}).nodes().each( function (cell, i) {
                  cell.innerHTML = i+1;
              } );
          } ).draw();

          $("thead input").keyup ( function () {

                  /* Filter on the column (the index) of this element */
              if ($('#trackertable').length > 0)
              {

                  var colnum=document.getElementById('trackertable').rows[0].cells.length;

                  if (this.value=="[empty]")
                  {

                     trackertable.fnFilter( '^$', $("thead input").index(this)-colnum,true,false );
                  }
                  else if (this.value=="[nonempty]")
                  {

                     trackertable.fnFilter( '^(?=\\s*\\S).*$', $("thead input").index(this)-colnum,true,false );
                  }
                  else if (this.value.startsWith("!")==true && this.value.length>1)
                  {

                     trackertable.fnFilter( '^(?'+ this.value +').*', $("thead input").index(this)-colnum,true,false );
                  }
                  else if (this.value.startsWith("!")==false)
                  {

                    trackertable.fnFilter( this.value, $("thead input").index(this)-colnum,true,false );
                  }
              }


          } );

      @endif

          $("#ajaxloader").hide();

      });

      </script>

@endsection

@section('content')

  <!-- Content Wrapper. Contains page content -->
    {{-- <div class="content-wrapper">
        <!-- Content Header (Page header) -->
        <section class="content-header">

        </section>
    </div> --}}

    <div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
      <h1>
        {{$project->Project_Name}}
        <small>Project Management</small>
      </h1>
      <ol class="breadcrumb">
        <li><a href="{{ url('/home') }}"><i class="fa fa-dashboard"></i> Home</a></li>
        <li><a href="#">Project Management</a></li>
        <li><a href="#">Project Tracker</a></li>
      </ol>
    </section>

    <!-- Main content -->
    <section class="content">
      <div class="row">


        <!-- /.col -->
        <div class="col-md-12">

          <div class="modal modal-danger fade" id="error-alert">
            <div class="modal-dialog">
              <div class="modal-content">
                <div class="modal-header">
                  <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span></button>
                  <h4 class="modal-title">Alert!</h4>
                </div>
                <div class="modal-body">
                <ul></ul>
                </div>
                <div class="modal-footer">
                  <button type="button" class="btn btn-outline" data-dismiss="modal">Close</button>
                </div>
              </div>
            </div>
          </div>

          <div class="modal modal-success fade" id="update-alert">
            <div class="modal-dialog">
              <div class="modal-content">
                <div class="modal-header">
                  <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span></button>
                  <h4 class="modal-title">Alert!</h4>
                </div>
                <div class="modal-body">
                <ul></ul>
                </div>
                <div class="modal-footer">
                  <button type="button" class="btn btn-outline" data-dismiss="modal">Close</button>
                </div>
              </div>
            </div>
          </div>

          <div class="modal modal-warning fade" id="warning-alert">
            <div class="modal-dialog">
              <div class="modal-content">
                <div class="modal-header">
                  <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span></button>
                  <h4 class="modal-title">Alert!</h4>
                </div>
                <div class="modal-body">
                <ul></ul>
                </div>
                <div class="modal-footer">
                  <button type="button" class="btn btn-outline" data-dismiss="modal">Close</button>
                </div>
              </div>
            </div>
          </div>

           <div class="modal fade" id="NewTracker" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
        			<div class="modal-dialog">
        				<div class="modal-content">
        					<div class="modal-header">
        						<div id="exist-alert" class="alert alert-warning alert-dismissible" style="display:none;">
        							<h4><i class="icon fa fa-check"></i> Alert!</h4>
        							<div id="changepasswordmessage"></div>
        						</div>
        						<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        						<h4 class="modal-title" id="myModalLabel">Add New Tracker</h4>

        					</div>

        					<div class="modal-body">
        						<div class="form-group">

        							<div class="form-group">
        								<label class="col-md-4 control-label">Tracker Name</label>
        								<div class="col-md-8">
        									<input type="text" class="form-control" name="tracker" id="tracker" placeholder="" value"{{$project->Project_Name}} Tracker"/>
        									<br>
        								</div>
        							</div>

                      <!-- <div class="form-group">
        								<label class="col-md-4 control-label">Number of rows</label>
        								<div class="col-md-3">
        									<input type="text" class="form-control" name="tracker"  placeholder=""/>

        									<br>
        								</div>
                        <div class="col-md-3">
                          <button onclick="go()" class="btn btn-success">Go</button>
                        </div>
        							</div> -->

        							<div class="form-group">
        								<table border="1" style="width:100%;text-align:center;">
        									<thead style="color:#000;font-weight:bold;background-color:#aaa;s"><tr><td>Column Name</td><td>Column Type</td></tr></thead>
        									<tbody>
        										@for ($i = 0; $i < 30; $i++)
        											<tr>
        												<td><input type="text" class="form-control fieldname" name="FieldName[]" placeholder=""/></td>
        												<td><select name="ColumnType[]" id="ColumnType" class="form-control">
        													    <option value="Textbox">Textbox</option>
        															<option value="Textarea">Textarea</option>
        															<option value="Date">Date</option>
        															<option value="Dropdown">Dropdown</option>
        															<option value="Currency">Currency</option>
                                      <option value="Name List">Name List</option>
                                      <option value="PO List">PO List</option>
                                      <option value="Invoice List">Invoice List</option>

        													  </select>
        												</td>
        											</tr>
        										@endfor
        									</tbody>
        								</table>
        							</div>
        						</div>

        					</div>

        					<br><br><br><br>
        					<div class="modal-footer">

        						<button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
        						<button type="button" class="btn btn-primary" onclick="newtracker({{$projectid}})">Create</button>
        					</div>
        				</div>
        			</div>
        		</div>

            <div class="modal fade" id="RenameTracker" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
               <div class="modal-dialog">
                 <div class="modal-content">
                   <div class="modal-header">
                     <div id="exist-alert" class="alert alert-warning alert-dismissible" style="display:none;">
                       <h4><i class="icon fa fa-check"></i> Alert!</h4>
                       <div id="changepasswordmessage"></div>
                     </div>
                     <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                     <h4 class="modal-title" id="myModalLabel">Rename Tracker</h4>

                   </div>

                   <div class="modal-body">
                     <div class="form-group">

                       <div class="form-group">
                         <label class="col-md-4 control-label">Tracker Name</label>
                         <div class="col-md-8">
                           @foreach($trackername as $name)
                            <input type="text" class="form-control" name="renametracker" id="renametracker" placeholder="" value="{{$name->Tracker_Name}}"/>
                           @endforeach
                           <!-- <input type="text" class="form-control" name="tracker" id="tracker" placeholder="" value"{{$project->Project_Name}} Tracker"/> -->
                           <br>
                         </div>
                       </div>

                     </div>

                   </div>

                   <br><br><br><br>
                   <div class="modal-footer">

                     <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                     <button type="button" class="btn btn-primary" onclick="renametracker({{$trackerid}})">Apply</button>
                   </div>
                 </div>
               </div>
             </div>


           <div class="modal fade" id="ViewTextarea" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
             <div class="modal-dialog claimbox"  role="document">
               <div class="modal-content">
                 <div class="modal-header">
                   <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                   <h4 class="modal-title" id="myModalLabel">Content</h4>
                 </div>
                 <div class="modal-body" name="content" id="content">

                 </div>
                 <div class="modal-footer">
                   <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                 </div>
               </div>
             </div>
           </div>

           <div class="modal fade" id="ImportData" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
             <div class="modal-dialog" role="document">
               <div class="modal-content">
                 <div class="modal-header">
                   <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                   <h4 class="modal-title" id="myModalLabel">Import Data</h4>
                 </div>
                 <div class="modal-body">
                   <div class="form-group">
                     <form enctype="multipart/form-data" id="upload_form" role="form" method="POST" action="" >
                       <input type="hidden" class="form-control" id="ProjectId" name="ProjectId" value="{{$projectid}}">
                       <input type="file" id="import" name="import">
                     </form>
                   </div>
                 </div>
                 <div class="modal-footer">
                   <center><img src="{{ URL::to('/') ."/img/ajax-loader.gif" }}" width="50px" height="50px" alt="Loading" name='ajaxloader' id="ajaxloader"></center>
                   <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                   <button type="button" class="btn btn-primary" onclick="importdata()">Import</button>
                 </div>
               </div>
             </div>
           </div>

            <div class="modal fade" id="AddColumn" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
         			<div class="modal-dialog">
         				<div class="modal-content">
         					<div class="modal-header">
         						<div id="exist-alert" class="alert alert-warning alert-dismissible" style="display:none;">
         							<h4><i class="icon fa fa-check"></i> Alert!</h4>
         							<div id="changepasswordmessage"></div>
         						</div>
         						<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
         						<h4 class="modal-title" id="myModalLabel">Add New Column</h4>

         					</div>

         					<div class="modal-body">
         						<div class="form-group">

         							<div class="form-group">
         								<table border="1" style="width:100%;text-align:center;">
         									<thead style="color:#000;font-weight:bold;background-color:#aaa;s"><tr><td>Column Name</td><td>Column Type</td></tr></thead>
         									<tbody>
         										@for ($i = 0; $i < 10  ; $i++)
         											<tr>
         												<td><input type="text" class="form-control fieldname2" name="FieldName[]" placeholder=""/></td>
         												<td><select name="ColumnType[]" id="ColumnType" class="form-control">
         													    <option value="Textbox">Textbox</option>
         															<option value="Textarea">Textarea</option>
         															<option value="Date">Date</option>
         															<option value="Dropdown">Dropdown</option>
         															<option value="Currency">Currency</option>
                                      <option value="Name List">Name List</option>
                                      <option value="PO List">PO List</option>
                                      <option value="Invoice List">Invoice List</option>
         													  </select>
         												</td>
         											</tr>
         										@endfor
         									</tbody>
         								</table>
         							</div>
         						</div>

         					</div>

         					<br><br><br><br>
         					<div class="modal-footer">

         						<button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
         						<button type="button" class="btn btn-primary" onclick="addcolumn({{$trackerid}})">Add</button>
         					</div>
         				</div>
         			</div>
         		</div>

            <div class="modal fade" id="SubmitDocument" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
         			<div class="modal-dialog">
         				<div class="modal-content">
         					<div class="modal-header">
         						<div id="exist-alert" class="alert alert-warning alert-dismissible" style="display:none;">
         							<h4><i class="icon fa fa-check"></i> Alert!</h4>
         						</div>
         						<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
         						<h4 class="modal-title" id="myModalLabel">Submit Document</h4>

         					</div>

         					<div class="modal-body">
         						<div class="form-group">

                      <label>Document Type</label>

                      <form enctype="multipart/form-data" id="upload_form2" role="form" method="POST" action="" >
                        <input type="hidden" id="selectedtrackerid" name="selectedtrackerid" value=0>
                        <input type="hidden" class="form-control" id="UserId" name="UserId" value="{{$me->UserId}}">
                        <input type="hidden" class="form-control" id="ProjectId" name="ProjectId" value="{{$projectid}}">
                        <input type="hidden" class="form-control" id="SiteId" name="SiteId" value="">

                        <div class="form-group">

                          <select class="form-control" name="DocumentType" id="DocumentType">
                            <option value></option>

                            @foreach($options as $option)
                             @if ($option->Field=="Document_Type")
                               <option value="{{$option->Option}}">{{$option->Option}}</option>
                             @endif
                           @endforeach

                          </select>

                        </div>

                        <label>Submitted Date</label>

                        <div class="form-group">
                          <select class="form-control" id="submitdate" name="submitdate">
                            <option value></option>

                            <!-- @foreach($trackercolumns as $column)
                              <option rel="{{$column->Column_Name}}" value="{{$column->Column_Name}}">{{$column->Column_Name}}</option>
                            @endforeach -->

                          </select>
                        </div>

                        <div class="form-group">
                              <input type="file" id="document" name="document" accept=".doc,.docx,.pdf">
                        </div>

                      </form>

         						</div>

         					</div>

         					<br><br><br><br>
         					<div class="modal-footer">

         						<button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
         						<button type="button" class="btn btn-primary" onclick="submitdocument()">Submit</button>
         					</div>
         				</div>
         			</div>
         		</div>

            <div class="modal fade" id="UpdateColumn" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
         			<div class="modal-dialog">
         				<div class="modal-content">
         					<div class="modal-header">
         						<div id="exist-alert" class="alert alert-warning alert-dismissible" style="display:none;">
         							<h4><i class="icon fa fa-check"></i> Alert!</h4>
         							<div id="changepasswordmessage"></div>
         						</div>
         						<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
         						<h4 class="modal-title" id="myModalLabel">Update Column</h4>

         					</div>

         					<div class="modal-body">
         						<div class="form-group">

         							<div class="form-group">
         								<table border="1" style="width:100%;text-align:center;">
         									<thead style="color:#000;font-weight:bold;background-color:#aaa;s"><tr><td>Remove</td><td>Column Name</td><td>Column Type</td></tr></thead>
         									<tbody>
                             <?php $a=5;?>
         										@for ($i = 0; $i < count($trackercolumns); $i++)
         											<tr>
                                <td><input type="checkbox" name="selectrow" id="selectrow" value="{{$trackercolumns[$i]->Id}}"></td>
         												<td><input type="hidden" name="updatecolumnid[]" id="updatecolumnid" value="{{$trackercolumns[$i]->Id}}">{{$trackercolumns[$i]->Column_Name}}</td>
        												<td><select name="updateColumnType[]" id="updateColumnType" class="form-control">

                                      @if($trackercolumns[$i]->Type=="Textbox")
          													    <option value="Textbox" selected>Textbox</option>
                                      @else
                                        <option value="Textbox">Textbox</option>
                                      @endif

                                      @if($trackercolumns[$i]->Type=="Textarea")
          															<option value="Textarea" selected>Textarea</option>
                                      @else
                                        <option value="Textarea">Textarea</option>
                                      @endif

                                      @if($trackercolumns[$i]->Type=="Date")
                                        <option value="Date" selected>Date</option>
                                      @else
                                        <option value="Date">Date</option>
                                      @endif

                                      @if($trackercolumns[$i]->Type=="Dropdown")
                                        <option value="Dropdown" selected>Dropdown</option>
                                      @else
                                        <option value="Dropdown">Dropdown</option>
                                      @endif

                                      @if($trackercolumns[$i]->Type=="Currency")
                                        <option value="Currency" selected>Currency</option>
                                      @else
                                        <option value="Currency">Currency</option>
                                      @endif

                                      @if($trackercolumns[$i]->Type=="Name List")
                                        <option value="Name List" selected>Name List</option>
                                      @else
                                        <option value="Name List">Name List</option>
                                      @endif

                                      @if($trackercolumns[$i]->Type=="PO List")
                                        <option value="PO List" selected>PO List</option>
                                      @else
                                        <option value="PO List">PO List</option>
                                      @endif

                                      @if($trackercolumns[$i]->Type=="Invoice List")
                                        <option value="Invoice List" selected>Invoice List</option>
                                      @else
                                        <option value="Invoice List">Invoice List</option>
                                      @endif
        													  </select>
        												</td>

         											</tr>
         										@endfor
         									</tbody>
         								</table>
         							</div>
         						</div>

         					</div>

         					<br><br><br><br>
         					<div class="modal-footer">

         						<button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
         						<button type="button" class="btn btn-danger" onclick="updatecolumn()">Apply</button>
         					</div>
         				</div>
         			</div>
         		</div>

            <div class="modal fade" id="ReorderColumn" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
         			<div class="modal-dialog">
         				<div class="modal-content">
         					<div class="modal-header">
         						<div id="exist-alert" class="alert alert-warning alert-dismissible" style="display:none;">
         							<h4><i class="icon fa fa-check"></i> Alert!</h4>
         							<div id="changepasswordmessage"></div>
         						</div>
         						<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
         						<h4 class="modal-title" id="myModalLabel">Reorder Column</h4>

         					</div>

         					<div class="modal-body">
                      <center>
           							<ul id='sorttracker'>

                          @foreach ($trackercolumns as $column)

                            <li id='ID_{{$column->Id}}' align='left' class='ui-state-default'>{{$column->Column_Name}}</li>

                          @endforeach

                        </ul>

                      </center>
         					</div>

         					<br><br><br><br>
         					<div class="modal-footer">

         						<button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
         						<button type="button" class="btn btn-danger" onclick="reordercolumn()">Save</button>
         					</div>
         				</div>
         			</div>
         		</div>

            <div class="box box-success">
              <div class="box-body">
              <div class="col-md-4">
                <h4>Staff on leave</h4>

                <table class="tablebody" border="1">
                    <thead>
                        <tr class="tableheader" >
                          @foreach($leaves as $key=>$values)
                            @if ($key==0)

                            @foreach($values as $field=>$value)

                              <td> {{ $field }}</td>
                            @endforeach

                            @endif

                          @endforeach
                        </tr>
                    </thead>
                    <tbody>

                      @foreach($leaves as $leave)
                        <tr>

                        @foreach($leave as $field=>$value)

                          <td>{{ $value }}</td>
                        @endforeach

                        </tr>
                      @endforeach
                    </tbody>
                </table>
              </div>


              <div class="col-md-4">
                <h4>Staff Timesheet</h4>
                <table class="tablebody" border="1">
                    <thead>
                        <tr class="tableheader" >
                          @foreach($timesheetdetail as $key=>$values)
                            @if ($key==0)

                            @foreach($values as $field=>$value)

                              <td> {{ $field }}</td>
                            @endforeach

                            @endif

                          @endforeach
                        </tr>
                    </thead>
                    <tbody>

                      @foreach($timesheetdetail as $timesheets)
                        <tr>

                        @foreach($timesheets as $field=>$value)

                          <td>{{ $value }}</td>
                        @endforeach

                        </tr>
                      @endforeach
                    </tbody>
                </table>
              </div>

              <div class="col-md-4">
                <h4>Aging</h4>

                <div id="agingdiv">

                  <ul class="list-group list-group-unbordered">

                    @foreach ($agingrules as $rules)

                      <li class="list-group-item">
                        <b><a href="{{ url('tracker/agingpreview') }}/{{$rules->Id}}" target="_blank">{{$rules->Title}}</a></b>
                      </li>

                    @endforeach

                  </ul>

                </div>

              </div>

              <div class="col-md-12">
                <br><br>
                <div class="box-body">

                  <a href='{{ url('/projectdashboard') }}/{{$projectid}}'><button type="button" class="btn btn-primary btn-lg">Project Dashboard</button></a>

                  @if($me->Import_Tracker && $trackerwriteaccess)
                    <a data-toggle="modal" data-target="#ImportData"><button type="button" class="pull-right btn btn-warning" >Import Data</button></a>
                  @endif
                  @if($me->Tracker_Management)
                    <a data-toggle="modal" href="{{ url('/optioncontrol') }}/tracker"><button type="button" class="pull-right btn btn-warning">Add Option</button></a>
                    <a data-toggle="modal" data-target="#UpdateColumn"><button type="button" class="pull-right btn btn-primary">Update Column</button></a>
                    <a data-toggle="modal" data-target="#ReorderColumn"><button type="button" class="pull-right btn btn-primary">Reorder Column</button></a>
                    <a data-toggle="modal" data-target="#AddColumn"><button type="button" class="pull-right btn btn-primary">Add Column</button></a>
                    <a data-toggle="modal" data-target="#RenameTracker"><button type="button" class="pull-right btn btn-success">Rename Tracker</button></a>
                    <a data-toggle="modal" data-target="#NewTracker"><button type="button" class="pull-right btn btn-success">Create New Tracker</button></a>
                  @endif
                </div>

                <div class="box-body">

                  <div class="nav-tabs-custom">
                    <ul class="nav nav-tabs">

                        @foreach($trackerlist as $tracker)

                          @if($tracker->Id==$trackerid)

                            <li class="active"><a href="#po" data-toggle="tab">{{$tracker->Tracker_Name}}</a></li>

                          @else

                            <li><a href="{{ url('/projecttracker') }}/{{$projectid}}/{{$tracker->Id}}">{{$tracker->Tracker_Name}}</a></li>

                          @endif

                        @endforeach

                      {{-- <li class="active"><a href="#po" data-toggle="tab" id="potab">PO</a></li>
                      <li><a href="#poitem" data-toggle="tab" id="poitemtab">PO Item</a></li> --}}
                    </ul>

                    <div class="tab-content">

                     <div class="row">

                      <div class="col-md-2">

                        <select class="form-control select2" id="option1" name="option1" style="width: 100%;">

                          <option value="">Select Column</option>

                          @foreach($optionkey as $option)
                              <option value="{{$option->Column_Name}}">{{$option->Column_Name}}</option>
                          @endforeach

                        </select>

                      </div>

                      <div class="col-md-2">

                        <select class="form-control select2" id="option2" name="option2" style="width: 100%;" disabled>

                          <option value="">Select Option</option>

                          @foreach($optionvalue as $value)
                              <option rel="{{$value->Field}}" value="{{$value->Option}}">{{$value->Option}}</option>
                          @endforeach

                        </select>

                      </div>

                      <div class="col-md-2">

                        <select class="form-control select2" id="option3" name="option3" style="width: 100%;">

                          <option value="">AND/OR</option>
                          <option value="AND">AND</option>
                          <option value="OR">OR</option>

                        </select>

                      </div>

                    </div>

                    <br>

                    <div class="row">

                      <div class="col-md-2">

                        <select class="form-control select2" id="option4" name="option4" style="width: 100%;">

                          <option value="">Select Column</option>

                          @foreach($optionkey as $option)
                              <option value="{{$option->Column_Name}}">{{$option->Column_Name}}</option>
                          @endforeach

                        </select>

                      </div>

                      <div class="col-md-2">

                        <select class="form-control select2" id="option5" name="option5" style="width: 100%;" disabled>

                          <option value="">Select Option</option>

                          @foreach($optionvalue as $value)
                              <option rel="{{$value->Field}}" value="{{$value->Option}}">{{$value->Option}}</option>
                          @endforeach

                        </select>

                      </div>

                      <div class="col-md-2">

                        <select class="form-control select2" id="option6" name="option6" style="width: 100%;">

                          <option value="">AND/OR</option>
                          <option value="AND">AND</option>
                          <option value="OR">OR</option>

                        </select>

                      </div>

                    </div>

                    <br>

                    <div class="row">

                      <div class="col-md-2">

                        <select class="form-control select2" id="option7" name="option7" style="width: 100%;">

                          <option value="">Select Column</option>

                          @foreach($optionkey as $option)
                              <option value="{{$option->Column_Name}}">{{$option->Column_Name}}</option>
                          @endforeach

                        </select>

                      </div>

                      <div class="col-md-2">

                        <select class="form-control select2" id="option8" name="option8" style="width: 100%;" disabled>

                          <option value="">Select Option</option>

                          @foreach($optionvalue as $value)
                              <option rel="{{$value->Field}}" value="{{$value->Option}}">{{$value->Option}}</option>
                          @endforeach

                        </select>

                      </div>

                      <div class="col-md-2">

                        <div class="input-group">
                          <button type="button" class="btn btn-success" data-toggle="modal" onclick="refresh();">Refresh</button>
                          <button type="button" class="btn btn-danger" data-toggle="modal" onclick="reset();">Reset</button>
                        </div>

                      </div>

                    </div>

                    @if($condition=="`tracker`.`ProjectId` <> \"null\"")

                    @else

                      <h5 class="box-title" style="color:red"><b><i>Filter : {{ $condition }}</i></b></h5>

                    @endif


                  </br>

                      <div class="col-md-12">

                        @if($lastedit)
                          <h5 class="box-title"><b><i>Last edited : {{$lastedit->Name}} [{{$lastedit->Updated_At}}]</i></b></h5>
                        @else
                          <h5 class="box-title"><b><i>Last edited : </i></b></h5>
                        @endif

                      </div>

                      <br>
                      <br>

                      <div class="active tab-pane" id="trackerview">
                        <table id="trackertable" class="display" cellspacing="0" width="100%" padding="30px" style="font-size: 13px;">
                            <thead>
                              <tr class="search">
                                <td align='center'><input type='hidden' class='search_init' /></td>
                                <td align='center'><input type='hidden' class='search_init' /></td>
                                <td align='center'><input type='hidden' class='search_init' /></td>
                                @foreach($trackerview as $key=>$values)
                                  @if ($key==0)

                                  @foreach($values as $field=>$value)

                                      <td align='center'><input type='text' class='search_init' /></td>

                                  @endforeach

                                  @endif

                                @endforeach

                              </tr>
                                <tr>
                                  @if($trackerview!="")
                                    @foreach($trackerview as $key=>$value)

                                      @if ($key==0)
                                        <td></td>
                                        <td></td>
                                        <td></td>

                                        @foreach($value as $field=>$value)
                                            <td/>{{ $field }}</td>
                                        @endforeach

                                      @endif

                                    @endforeach
                                  @endif
                                </tr>
                            </thead>
                            <tbody>

                              @if($trackerview!="")

                                <?php $i = 0; ?>
                                @foreach($trackerview as $view)

                                  <tr id="row_{{ $i }}">
                                      <td></td>
                                      <td></td>
                                      <td></td>
                                      @foreach($view as $key=>$value)
                                        <td>

                                            {{ $value }}

                                        </td>
                                      @endforeach
                                  </tr>
                                  <?php $i++; ?>
                                @endforeach

                              @endif

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
  $(function(){

    var $cat = $("#DocumentType"),
        $subcat = $("#submitdate");

    $cat.on("change",function(){
      $('#submitdate').children('option:not(:first)').remove();
        var rel = $(this).val();

        @foreach ($trackercolumns as $column)
            var a = "{{$column->Column_Name}}";
            if(a.indexOf(rel) > -1) {
              $("#submitdate").append(" <option value='{{$column->Column_Name}}'>{{$column->Column_Name}}</option>");
            }
            else {

            }
        @endforeach

    });


});

$(function(){
      var $option = $("#option1"),
         $value = $("#option2");

       var $option1 = $("#option4"),
          $value1 = $("#option5");

      var $option2 = $("#option7"),
         $value2 = $("#option8");


      $option.on("change",function(){
         var _rel = $(this).val();
         $value.find("option").attr("style","");
         $value.val("");
         if(!_rel) return $value.prop("disabled",true);
         $value.find("[rel="+_rel+"]").show();
         $value.prop("disabled",false);
      });

      $option1.on("change",function(){
         var _rel = $(this).val();
         $value1.find("option").attr("style","");
         $value1.val("");
         if(!_rel) return $value1.prop("disabled",true);
         $value1.find("[rel="+_rel+"]").show();
         $value1.prop("disabled",false);
      });

      $option2.on("change",function(){
         var _rel = $(this).val();
         $value2.find("option").attr("style","");
         $value2.val("");
         if(!_rel) return $value2.prop("disabled",true);
         $value2.find("[rel="+_rel+"]").show();
         $value2.prop("disabled",false);
      });

});

function reset()
{
  window.location.href ="{{ url('/projecttracker') }}/{{$projectid}}/{{$trackerid}}";
}


function refresh()
  {
    var option1=$('#option1').val();
    var option2=$('#option2').val();
    var option3=$('#option3').val();
    var option4=$('#option4').val();
    var option5=$('#option5').val();
    var option6=$('#option6').val();
    var option7=$('#option7').val();
    var option8=$('#option8').val();


    if(option2.length>0 && option3.length==0 && option6.length==0 )
    {

      window.location.href ="{{ url('/projecttracker') }}/{{$projectid}}/{{$trackerid}}/`"+option1+"`=\""+option2+"\"";
      document.getElementById("filter").innerHTML = option1 + option2;
    }

    else if(option2.length>0 && option3.length>0 && option6.length==0)
    {

      window.location.href ="{{ url('/projecttracker') }}/{{$projectid}}/{{$trackerid}}/`"+option1+"`=\""+option2+"\" "+option3+" `"+option4+"`=\""+option5+"\"";

    }

    else if(option2.length>0 && option3.length>0 && option6.length>0)
    {

      window.location.href ="{{ url('/projecttracker') }}/{{$projectid}}/{{$trackerid}}/`"+option1+"`=\""+option2+"\" "+option3+" `"+option4+"`=\""+option5+"\" "+option6+" `"+option7+"`=\""+option8+"\"";

    }

    else
    {

      var message ="Select at least one option!";
      $("#warning-alert ul").html(message);
      $("#warning-alert").modal("show");

    }


  }


  $( function() {

    $('#agingdiv').slimScroll({
       height: '250px'
   });

    var availableTags = [];

    @foreach ($allavailablecolumns as $column)

      availableTags.push("{{$column->Col}}");

    @endforeach

    $( ".fieldname" ).autocomplete({
      source: availableTags,
      appendTo: "#NewTracker"
    });

    $( ".fieldname2" ).autocomplete({
      source: availableTags,
      appendTo: "#AddColumn"
    });

  } );


  function newtracker(projectid)
  {

      TrackerName=$('input[name="tracker"]').val();
      columnname=$('input[name="FieldName[]"]').map(function(){return $(this).val();}).get();
      datatype=$('[name="ColumnType[]"] option:selected').map(function(){return $(this).html();}).get();
      columnntype=$('[name="ColumnType[]"] option:selected').map(function(){return $(this).val();}).get();

      $.ajaxSetup({
         headers: { 'X-CSRF-Token' : $('meta[name=_token]').attr('content') }
      });

      $.ajax({
                  url: "{{ url('/tracker/new') }}",
                  method: "POST",
                  data: {
                    ProjectID:projectid,
                    Tracker_Name:TrackerName,
                    Column_Name:columnname,
                    Data_Type:datatype,
                    Type:columnntype
                  },
                  success: function(response){

                    if (response==0)
                    {
                      var message ="Failed to create new tracker!";

                      $("#warning-alert ul").html(message);
                      $("#warning-alert").modal("show");

                      $('#NewTracker').modal('hide')

                      // setTimeout(function() {
                      //   $("#warning-alert").fadeOut();
                      // }, 3000);

                    }
                    else {
                      var message ="New tracker added!";
                      $("#update-alert ul").html(message);
                      $("#update-alert").modal("show");

                      // setTimeout(function() {
                      //   $("#update-alert").fadeOut();
                      //     window.location.reload();
                      // }, 3000);

                      $('#NewTracker').modal('hide')

                    }
          }
      });


  }

  function addcolumn(trackerid)
  {

      columnname=$('input[name="FieldName[]"]').map(function(){return $(this).val();}).get();
      datatype=$('[name="ColumnType[]"] option:selected').map(function(){return $(this).html();}).get();
      columnntype=$('[name="ColumnType[]"] option:selected').map(function(){return $(this).val();}).get();

      $.ajaxSetup({
         headers: { 'X-CSRF-Token' : $('meta[name=_token]').attr('content') }
      });

      $.ajax({
                  url: "{{ url('/tracker/addcolumn') }}",
                  method: "POST",
                  data: {
                    TrackerId:trackerid,
                    Column_Name:columnname,
                    Data_Type:datatype,
                    Type:columnntype
                  },
                  success: function(response){
                    console.log(response)
                    if (response==0)
                    {
                      var message ="Failed to add new column!";

                      $("#warning-alert ul").html(message);
                      $("#warning-alert").modal("show");

                      $('#AddColumn').modal('hide')

                      // setTimeout(function() {
                      //   $("#warning-alert").fadeOut();
                      // }, 3000);

                    }
                    else if (response==-1)
                    {
                      var message ="Column already exist!";

                      $("#warning-alert ul").html(message);
                      $("#warning-alert").modal("show");

                      $('#AddColumn').modal('hide')

                      // setTimeout(function() {
                      //   $("#warning-alert").fadeOut();
                      // }, 3000);

                    }
                    else {
                      var message ="New column added!";
                      $("#update-alert ul").html(message);
                      $("#update-alert").modal("show");

                      // setTimeout(function() {
                      //   $("#update-alert").fadeOut();
                      window.location.reload();
                      // }, 3000);

                      $('#AddColumn').modal('hide')

                    }
          }
      });


  }



  function showsubmitdocumentmodal(TrackerId,SiteId)
  {
    //alert(SiteId);

    $('#selectedtrackerid').val(TrackerId);
    $('#SiteId').val(SiteId);
    $('#SubmitDocument').modal('show');

  }

  function updatecolumn()
  {

      var boxes = $('input[type="checkbox"]:checked');
      var ids="";

      columnid=$('input[name="updatecolumnid[]"]').map(function(){return $(this).val();}).get();
      columnntype=$('[name="updateColumnType[]"] option:selected').map(function(){return $(this).val();}).get();

      if (boxes.length>0)
      {
        for (var i = 0; i < boxes.length; i++) {
          ids+=boxes[i].value+",";
        }

        ids=ids.substring(0, ids.length-1);
      }
      else {
        ids=0;
      }

        $.ajaxSetup({
           headers: { 'X-CSRF-Token' : $('meta[name=_token]').attr('content') }
        });

        $.ajax({
                    url: "{{ url('/tracker/updatecolumn') }}",
                    method: "POST",
                    data: {
                      Ids:ids,
                      TrackerId:{{$trackerid}},
                      ColumnId:columnid,
                      ColumnType:columnntype
                    },
                    success: function(response){
                      console.log(response)
                      if (response==0)
                      {
                        var message ="Failed to update column!";

                        $("#warning-alert ul").html(message);
                        $("#warning-alert").modal("show");

                        $('#UpdateColumn').modal('hide')

                        // setTimeout(function() {
                        //   $("#warning-alert").fadeOut();
                        // }, 3000);

                      }
                      else {
                        var message ="Column Updated!";
                        $("#update-alert ul").html(message);
                        $("#update-alert").modal("show");

                        // setTimeout(function() {
                        //   $("#update-alert").fadeOut();
                        //   window.location.reload();
                        // }, 3000);

                        $('#UpdateColumn').modal('hide')

                      }
            }
        });
      // else {
      //   var errormessage="No column selected!";
      //   $("#error-alert ul").html(errormessage);
      //   $("#error-alert").show();
      //
      //   setTimeout(function() {
      //     $("#error-alert").fadeOut();
      //   }, 3000);
      //
      //   $('#RemoveColumn').modal('hide');
      //
      //
      // }


  }

  function submitdocument() {
      $.ajaxSetup({
         headers: { 'X-CSRF-Token' : $('meta[name=_token]').attr('content') }
      });

      $.ajax({
                  url: "{{ url('/tracker/submitdocument') }}",
                  method: "POST",
                  contentType: false,
                  processData: false,
                  data:new FormData($("#upload_form2")[0]),
                  success: function(response){
                    if (response==0)
                    {
                      var message ="Failed to submit document!";
                      $("#warning-alert ul").html(message);
                      $("#warning-alert").modal("show");

                      // setTimeout(function() {
                      //   $("#warning-alert").fadeOut();
                      // }, 3000);

                    }
                    else {
                      var message ="Document submitted!";
                      $("#update-alert ul").html(message);
                      $("#update-alert").modal("show");

                      // setTimeout(function() {
                      //   $("#update-alert").fadeOut();
                      //     window.location.reload();
                      // }, 3000);
                      //$("#Template").val(response).change();
                      $("#exist-alert").hide();

                      $('#SubmitDocument').modal('hide')

                    }
          }
      });
  }

  function importdata() {

      $.ajaxSetup({
         headers: { 'X-CSRF-Token' : $('meta[name=_token]').attr('content') }
      });

      $("#ajaxloader").show();

      $.ajax({

                  url: "{{ url('/tracker/importdata') }}",
                  method: "POST",
                  contentType: false,
                  processData: false,
                  data:new FormData($("#upload_form")[0]),

                  success: function(response){
                    if (response==0)
                    {
                      var message ="Failed to import data!";
                      $("#warning-alert ul").html(message);
                      $("#warning-alert").modal("show");

                      // setTimeout(function() {
                      //   $("#warning-alert").fadeOut();
                      // }, 3000);

                      $('#ImportData').modal('hide')
                      $("#ajaxloader").hide();

                    }
                    else {

                      var message ="Data imported!";
                      $("#update-alert ul").html(message);
                      $("#update-alert").modal("show");

                      // setTimeout(function() {
                      //   $("#update-alert").fadeOut();
                      //   window.location.reload();
                      // }, 3000);

                      $('#ImportData').modal('hide')
                      $("#ajaxloader").hide();

                    }

          }
      });

  }

  var timer;

  function viewtextarea(content)
  {

    clearTimeout(timer);

    timer=setTimeout(function() {

      $('#ViewTextarea').modal('show');
      $("#content").html(content);

    }, 1000);

  }

  function reordercolumn()
  {

    $.ajaxSetup({
       headers: { 'X-CSRF-Token' : $('meta[name=_token]').attr('content') }
    });

        var order = $("#sorttracker").sortable("serialize");

        $.ajax({
        type: "POST",
        url: "{{ url('/tracker/reordercolumn') }}",
        data: { sequence:order},
        success: function(response)
        {
            if (response==0)
            {

              var message ="Failed to save column sequence!";
              $("#warning-alert ul").html(message);
              $("#warning-alert").modal("show");
              //
              // setTimeout(function() {
              //   $("#warning-alert").fadeOut();
              // }, 3000);

              $('#ReorderColumn').modal('hide')

            }
            else
            {

              var message ="Column sequence saved!";
              $("#update-alert ul").html(message);
              $("#update-alert").modal("show");

              // setTimeout(function() {
              //   $("#update-alert").fadeOut();
              //   window.location.reload();
              // }, 3000);

              $('#ReorderColumn').modal('hide')

            }

        }
    });

  }

  function renametracker(trackerid)
  {

      TrackerName=$('input[name="renametracker"]').val();

      $.ajaxSetup({
         headers: { 'X-CSRF-Token' : $('meta[name=_token]').attr('content') }
      });

      $.ajax({
                  url: "{{ url('/tracker/rename') }}",
                  method: "POST",
                  data: {
                    TrackerId:trackerid,
                    TrackerName:TrackerName
                  },
                  success: function(response){

                    if (response==0)
                    {
                      var message ="Failed to rename tracker!";

                      $("#warning-alert ul").html(message);
                      $("#warning-alert").modal("show");

                      $('#RenameTracker').modal('hide')

                      // setTimeout(function() {
                      //   $("#warning-alert").fadeOut();
                      // }, 3000);

                    }
                    else {
                      var message ="New tracker added!";
                      $("#update-alert ul").html(message);
                      $("#update-alert").modal("show");

                        window.location.reload();

                      // setTimeout(function() {
                      //   $("#update-alert").fadeOut();
                      //
                      // }, 3000);

                      $('#RenameTracker').modal('hide')

                    }
          }
      });


  }



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




  </script>



@endsection
