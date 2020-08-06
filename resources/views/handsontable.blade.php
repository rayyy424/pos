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

    <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/fixedcolumns/3.2.2/css/fixedColumns.dataTables.min.css">

  <link data-jsfiddle="common" rel="stylesheet" media="screen" href="{{ asset('/plugin/handsontable/dist/handsontable.full.min.css') }}">
  <link data-jsfiddle="common" rel="stylesheet" media="screen" href="{{ asset('/plugin/handsontable/dist/pikaday/pikaday.css') }}">

  <link rel="stylesheet" type="text/css" href="{{ asset('/plugin/css/editor.dataTables.min.css') }}">
  <link rel="stylesheet" type="text/css" href="{{ asset('/plugin/ReorderDiv/CSS/jquery-ui.css') }}">

  <script data-jsfiddle="common" src="{{ asset('/plugin/handsontable/dist/pikaday/pikaday.js') }}"></script>
  <script data-jsfiddle="common" src="{{ asset('/plugin/handsontable/dist/moment/moment.js') }}"></script>
  <script data-jsfiddle="common" src="{{ asset('/plugin/handsontable/dist/numbro/numbro.js') }}"></script>
  <script data-jsfiddle="common" src="{{ asset('/plugin/handsontable/dist/handsontable.full.min.js') }}"></script>
  <script data-jsfiddle="common" src="{{ asset('/plugin/handsontable/dist/select2-editor.js') }}"></script>
  <script data-jsfiddle="common" src="{{ asset('/plugin/handsontable/dist/customrenderer.js') }}"></script>
  <script data-jsfiddle="common" src="{{ asset('/plugin/jscolor/jscolor2.js') }}"></script>

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
  <script type="text/javascript" language="javascript" src="https://cdn.datatables.net/plug-ins/1.10.13/api/sum().js"></script>

  <script type="text/javascript" language="javascript" src="https://cdn.datatables.net/fixedcolumns/3.2.2/js/dataTables.fixedColumns.min.js"></script>
  <script type="text/javascript" language="javascript" src="{{ asset('/plugin/js/dataTables.editor.min.js') }}"></script>

  <script type="text/javascript" language="javascript" src="{{ asset('/plugin/datatables/extensions/Autocomplete/editor.autoComplete.js') }}"></script>


  <style type="text/css" class="init">

  .modal-dialog-wide{
    width: 80%; /* respsonsive width */
    position: absolute;
float: left;
left: 50%;
top: 30%;
transform: translate(-50%, -150px); */
}
  <?php  $arr=array(); ?>
@foreach($colorcodes as $codes)

  @if(!in_array($codes->Color_Code,$arr) && $codes->Color_Code!="FFFFFF")

    .handsontable th.c{{$codes->Color_Code}}{
      background-color: #{{$codes->Color_Code}};
      color: white;
    }
    <?php  array_push($arr,$codes->Color_Code); ?>
  @endif

@endforeach

  div.DTED_Lightbox_Wrapper{
    z-index: 9999;
  }

  #warning-alert{
    z-index: 3000;
  }

  #update-alert{
    z-index: 3000;
  }

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
      color:#000;
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
     font-size: 16px;
     font-weight: 400;
    }

    .changeType {
      z-index: 1;
      position: relative;
    }

    .buttonimg{
      padding-top:2px;
      padding-bottom:2px;
      height:20px;
      width:20px;
    }

    .historyheader{
      background-color: gray;
    }


/*/  svertical-align: middle;*/
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

    .handsontable th{
      padding:0px;
      margin:0px;
    }

    body.modal-open {
overflow: visible !important;
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

    tr:nth-of-type(even) > td {
    background-color: #D6EAF8;
    }

    .handsontable .red {
    /*background: red;*/
    color : red;
    }

    .handsontable .current .red {
    /*background: red;*/
    color : red;
    }

    #divLoading
{
display : none;
}
#divLoading
{
display : block;
position : fixed;
z-index: 100;
background-image : url('{{asset('/img/updating.gif')}}');
background-color:#666;
opacity : 0.9;
background-repeat : no-repeat;
background-position : center;
left : 0;
bottom : 0;
right : 0;
top : 0;
}
#loadinggif
{
left : 50%;
top : 50%;
position : absolute;
z-index : 101;
width : 32px;
height : 32px;
margin-left : -16px;
margin-top : -16px;
}


  .viewimages {
    overflow: auto;
    width: 190px;
    float: left;
    position:relative;
    padding:5px;
  }

  .viewimages img{
    width:180px;
    height:180px;
  }
  .viewimages img:hover{
    border: 2px solid red;
    opacity: 0.8;
  }

  span.fa-trash-o {
      position: absolute;
      top: 10px;
      left: 20px;
      width: 100%;
      font-size:20px;
      color:red;
      object-fit: cover;
  }

  .modal-dialog-wide1 {
    top: 60%;
    width: 80%; /* respsonsive width */
    position: absolute;
    float: left;
    left: 50%;
    transform: translate(-50%, -50%);
  }

  span.image_name {
      position: absolute;
      bottom: 10px;
      left:0;
      right:0;
      width: 85%;
      font-size:12px;
      color:white;;
      object-fit: cover;
      background-color:rgba(0, 0, 0, 0.77);
      margin:10px;
      padding:3px;
  }

  /*.nav>li>a {
    position: relative;
    display: block;
    padding: 1px 1px;
    font-size: 12px;
}*/

  .lastedit{
    font-size:10px;
    display: inline;
    text-align: right;
  }

  .showrecord{
    font-size:12px;
    display: inline;
    text-align: right;
  }

  .handsontable td, .handsontable th {
    font-size: 10px;
}

.handsontable td, .handsontable tr
{
      font-size: 10px;
      height:12px;
      max-width: 300px;
       overflow: hidden;
       text-overflow: ellipsis;
       white-space: nowrap !important;

}

.handsontable thead th .relative {
    padding: 0px 0px 0px 0px;
}

.labelclass{
  padding: 0px;
  margin: 0px;
  display: block;
  min-height: 100%;
  color:white;
}

.sumheader{

    font-weight: bold;
    font-family: Arial;
    font-size: 11px;
    height: 12px;
    text-align: left;
    color: blue;
}

.handsontable .htUIMultipleSelect .handsontable .htCore {
    width: 220px;
}

.modal-dialog{
  width: 80%; /* respsonsive width */
}

  </style>

  <script type="text/javascript" language="javascript" class="init">

      var editor; // use a global for the submit and return data rendering in the examples
      var asInitVals = new Array();
      var oTable;
      var userid;
      var myVar
      var arrsum=[];

      var clickedrow;
      var clickedcol;

      $(document).ready(function() {

        @foreach($arrsum as $a)
          arrsum.push('{{$a}}');
        @endforeach

        calculatesum();

        editor = new $.fn.dataTable.Editor( {
                ajax: {
                   "url": "{{ asset('/Include/issue.php') }}",
                   "data": function( d ) {
                         d.ProjectId=$("select.selection option:selected").val();
                         d.Site_Name=document.getElementById("SiteName3").value;
                         d.Site_ID=document.getElementById("SiteID3").value;
                     }
                 },
                 formOptions: {
                      bubble: {
                          submit: 'allIfChanged'
                      }
                  },
                table: "#issuetable",
                idSrc: "siteissue.Id",
                fields: [
                  {
                      label: "Project Name:",
                      name: "siteissue.ProjectId"

                 },{
                          label: "Status:",
                          name: "siteissue.Status",
                          type: "select",
                          options: [
                              { label :"Open", value: "Open" },
                              { label :"Close", value: "Close" }

                          ],
                  },{
                          label: "Person In Charge:",
                          name: "siteissue.Person_In_Charge"
                  },{
                          label: "Scope of Work:",
                          name: "siteissue.Scope_of_Work",
                          type: "select",
                          options: [
                              { label :"", value: "" },
                              @foreach($options as $option)
                                @if (strtoupper($option->Field)=="SCOPE_OF_WORK")
                                  { label :"{{$option->Option}}", value: "{{$option->Option}}" },
                                @endif
                              @endforeach


                          ],
                  },{
                          label: "Issue Description:",
                          name: "siteissue.Issue_Description",
                          type:"textarea"
                  },{
                          label: "Site_ID:",
                          name: "siteissue.Site_ID"
                  },{
                          label: "Site_Name:",
                          name: "siteissue.Site_Name"
                  },{
                         label: "Date:",
                         name: "siteissue.Date",
                         type:   'datetime',
                         def:    function () { return new Date(); },
                         format: 'DD-MMM-YYYY'
                  },{
                        label: "Time:",
                        name: "siteissue.Time",
                        type:   'datetime',
                        def:    function () { return new Date(); },
                        format: 'h:mm A'
                  },{
                          label: "Remarks:",
                          name: "siteissue.Remarks",
                          type:"textarea"
                  },{
                          label: "Solution:",
                          name: "siteissue.Solution",
                          type:"textarea"
                  },{
                          label: "created_by:",
                          name: "siteissue.created_by"
                  }


                ]
        } );

                     editor.on('open', function () {
                        $('div.DTE_Footer').css( 'text-indent', -1 );
                    });

                     //Activate an inline edit on click of a table cell
                          //  $('#potable').on( 'click', 'tbody td', function (e) {
                          //        editor.inline( this, {
                          //          onBlur: 'submit',
                          //          submit: 'allIfChanged'
                          //      } );
                          //  } );

                           $('#issuetable').on( 'click', 'tbody td:not(.child), tbody span.dtr-data', function (e) {
                             editor.inline( this, {
                            onBlur: 'submit',
                            submit: 'allIfChanged'
                               } );
                           } );

                           oTable=$('#issuetable').dataTable( {
                                   ajax: {
                                      "url": "{{ asset('/Include/issue.php') }}",
                                      "data": function( d ) {
                                            d.ProjectId=$("select.selection option:selected").val();
                                            d.Site_Name=document.getElementById("SiteName3").value;
                                            d.Site_ID=document.getElementById("SiteID3").value;
                                        }
                                    },
                                  columnDefs: [{ "visible": false, "targets": [13] },{"className": "dt-center", "targets": "_all"}],
                                   responsive: false,
                                   colReorder: false,
                                   sScrollX: "100%",
                                   bScrollCollapse: true,
                                   bAutoWidth: true,
                                   sScrollY: "100%",
                                   rowId: "siteissue.Id",
                                   //aaSorting: [[4,"asc"]],
                                   dom: "Brti",
                                   columns: [

                                     {data: null,"render":"", title:"No"},
                                     { data: "siteissue.Status",title:"Status"},
                                     { data: "siteissue.Site_ID",title:"Site_ID"},
                                     { data: "siteissue.Site_Name",title:"Site_Name"},
                                     { data: "siteissue.Person_In_Charge",title:"Person_In_Charge"},
                                     { data: "siteissue.Scope_of_Work",title:"Scope_of_Work"},
                                     { data: "siteissue.Issue_Description",title:"Issue_Description"},
                                     { data: "siteissue.Date",title:"Date"},
                                     { data: "siteissue.Time",title:"Time"},
                                     { data: "siteissue.Remarks",title:"Remarks"},
                                     { data: "siteissue.Solution",title:"Solution"},
                                     { data: "users.Name",title:"Created_By"},
                                     { data: "siteissue.created_at",title:"created_at"},
                                     { data: "siteissue.updated_at",title:"updated_at"}

                                   ],
                                   autoFill: {
                                      editor:  editor,
                                  },
                                   select: true,
                                   buttons: [
                                           {
                                            text: 'New Issue',
                                            action: function ( e, dt, node, config ) {
                                                // clearing all select/input options
                                                //document.getElementById("Site_ID").value
                                                editor
                                                   .create( false )
                                                   .set( 'siteissue.ProjectId', '{{$projectid}}')
                                                   .set( 'siteissue.created_by', '{{$me->UserId}}')
                                                   .set( 'siteissue.Site_Name', document.getElementById("SiteName3").value)
                                                   .set( 'siteissue.Site_ID', document.getElementById("SiteID3").value)
                                                   .submit();

                                                   oTable.api().ajax.reload();
                                            },
                                          },
                                           { extend: "remove", editor: editor }
                                   ],

                       });

                       oTable.api().on( 'order.dt search.dt', function () {
                           oTable.api().column(0, {search:'applied', order:'applied'}).nodes().each( function (cell, i) {
                               cell.innerHTML = i+1;
                           } );
                       } ).draw();

                      //  $('#potable').on( 'click', 'tr', function () {
                      //    // Get the rows id value
                      //   //  var row=$(this).closest("tr");
                      //   //  var oTable = row.closest('table').dataTable();
                      //    userid = oTable.api().row( this ).data().purchaseorders.Id;
                      //  });

                       $(".issuetable thead input").keyup ( function () {

                               /* Filter on the column (the index) of this element */
                               if ($('#issuetable').length > 0)
                               {

                                   var colnum=document.getElementById('issuetable').rows[0].cells.length;

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

                         $("#ajaxloader").hide();
                         $("#ajaxloader2").hide();
                         $("#ajaxloader3").hide();
                         $("#ajaxloaderaddcolumn").hide();
                         $("#ajaxloaderupdatecolumn").hide();

                   } );

                   window.onbeforeunload = function (e) {
                     console.log(Idarray[0]);
                        if (Idarray[0]) {
                            var message = "You have not saved your changes.", e = e || window.event;
                            if (e) {
                                e.returnValue = "abc";
                            }
                            // return message;
                        }
                    }

               </script>

@endsection

@section('content')

  <div class="content-wrapper">
  <!-- Content Header (Page header) -->
  <section class="content-header">
    <h1>
      <small>Project Management</small>
    </h1>

  <div class="row">

    <div class="col-md-3">
      <select class="form-control select2" id="ProjectId" name="ProjectId">
        <option value="0">Select a Project</option>

        @foreach ($projects as $project)
           <option value="{{$project->Id}}" <?php if($project->Id==$projectid) echo "selected";?>>{{$project->Project_Name}}</option>
        @endforeach
      </select>
    </div>

    <div class="col-md-3">

      <select class="form-control select2" id="TemplateId" name="TemplateId">
        <option value="0">Select a Tracker</option>

        @foreach ($trackerlist as $t)
           <option value="{{$t->Id}}" <?php if($t->Id==$trackerid) echo "selected";?>>{{$t->Tracker_Name}}</option>
        @endforeach
      </select>

    </div>

  </div>

    <ol class="breadcrumb">
      <li><a href="{{ url('/home') }}"><i class="fa fa-dashboard"></i> Home</a></li>
      <li><a href="#">Project Management</a></li>
      <li><a href="#">Project Tracker</a></li>
    </ol>
  </section>

  <!-- Main content -->
  <section class="content">
    <div class="row">

      <div id="divLoading" style="display:none;">

      </div>

      <!-- /.col -->

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

        <div class="modal modal-success fade" tabindex="9000" id="update-alert">
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

        <div class="modal fade" id="LogModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
          <div class="modal-dialog" role="document">
            <div class="modal-content">
              <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title" id="myModalLabel">Site Diary</h4>
              </div>
              <div class="modal-body" name="history" id="history">

              </div>
              <div class="modal-footer">

                <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
              </div>
            </div>
          </div>
        </div>

        <div class="modal modal-warning fade" tabindex="-1" id="warning-alert">
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

        <div class="modal fade" id="UpdateSite" role="dialog" aria-labelledby="myModalLabel">
          <div class="modal-dialog-wide" role="document">
            <div class="modal-content">
              <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title" id="myModalLabel">Update Site</h4>
              </div>
              <div class="modal-body">
                <div class="form-group">
                  <form id="update_form" role="form" method="POST" action="" >

                    <input type="hidden" class="form-control" name="ProjectId3" id="ProjectId3" value="{{$projectid}}"/>
                    <input type="hidden" class="form-control" name="TrackerId" id="TrackerId" value="{{$trackerid}}"/>
                    <input type="hidden" class="form-control" name=UpdateId id="UpdateId" value=""/>

                    @if ($trackercolumns!="")

                      <div class="row">

                      @foreach ($trackercolumns as $column)

                        @if(str_contains(strtoupper($column->Column_Name),"INVOICE AMOUNT") ||
                          str_contains(strtoupper($column->Column_Name),"INVOICE NO ADDITIONAL") ||
                          str_contains(strtoupper($column->Column_Name),"INVOICE DATE ADDITIONAL") ||
                          str_contains(strtoupper($column->Column_Name),"INVOICE NO") ||
                          str_contains(strtoupper($column->Column_Name),"INVOICE DATE") ||
                          str_contains(strtoupper($column->Column_Name),"INVOICE AMOUNT") ||
                          str_contains(strtoupper($column->Column_Name),"INVOICE STATUS") ||
                          str_contains(strtoupper($column->Column_Name),"INVOICE AMOUNT ADDITIONAL") ||
                          str_contains(strtoupper($column->Column_Name),"INVOICE VALUE") ||
                          str_contains(strtoupper($column->Column_Name),"INVOICE SCOPE") ||
                          str_contains(strtoupper($column->Column_Name),"INVOICE COMPANY")||

                          str_contains(strtoupper($column->Column_Name),"INV NO") ||
                          str_contains(strtoupper($column->Column_Name),"INV DATE") ||
                          str_contains(strtoupper($column->Column_Name),"INV AMOUNT") ||
                          str_contains(strtoupper($column->Column_Name),"SCOPE OF WORK") ||
                          str_contains(strtoupper($column->Column_Name),"SUPPLIER")

                        )

                          <div class="col-md-4">
                              <label>{{$column->Column_Name}} : </label>

                            <div class="form-group">

                              @if($column->Type=="Read Only")
                               <input type="text" class="form-control" name="{{$column->Column_Name}}2" id="{{$column->Column_Name}}2" disabled/>
                              @elseif($column->Type=="Textbox")
                                <input type="text" class="form-control" name="{{$column->Column_Name}}2" id="{{$column->Column_Name}}2" />
                              @elseif($column->Type=="Textarea")
                                <textarea class="form-control" rows="6" name="{{$column->Column_Name}}2" id="{{$column->Column_Name}}2" ></textarea>
                              @elseif($column->Type=="Date")

                                  <div class="input-group date">
                                    <div class="input-group-addon">
                                      <i class="fa fa-calendar"></i>
                                    </div>
                                    <input type="text" class="form-control pull-right dateclass" id="{{$column->Column_Name}}2" name="{{$column->Column_Name}}2">
                                  </div>

                              @elseif($column->Type=="Dropdown")

                                <select class="form-control select2" name="{{$column->Column_Name}}2" id="{{$column->Column_Name}}2" style="width:100%;">
                                  <option value=""></option>
                                  @foreach($options as $option)
                                    @if ($option->Field==$column->Column_Name)

                                      <option value="{{$option->Option}}">{{$option->Option}}</option>
                                    @endif
                                  @endforeach

                                </select>

                              @elseif($column->Type=="Staff List")

                                <select class="form-control select2" name="{{$column->Column_Name}}2" id="{{$column->Column_Name}}2" style="width:100%;">
                                  <option value=""></option>
                                   @foreach($staff as $user)

                                      <option value="{{$user->Name}}">{{$user->Name}}</option>
                                   @endforeach

                                </select>

                              @elseif($column->Type=="Contractor List")

                                <select class="form-control select2" name="{{$column->Column_Name}}2" id="{{$column->Column_Name}}2" style="width:100%;">
                                 <option value=""></option>
                                  @foreach($contractor as $user)

                                     <option value="{{$user->Name}}">{{$user->Name}}</option>
                                  @endforeach

                                </select>

                              @elseif($column->Type=="PO List")

                                <select class="form-control select2" name="{{$column->Column_Name}}2" id="{{$column->Column_Name}}2" style="width:100%;">

                                  <option value="">-</option>
                                  <option value="No PO">No PO</option>
                                  @foreach($poitems as $poitem)

                                   <?php

                                     $object = (object)[];
                                     $object->PO = $poitem->PO_No." | ".$poitem->PO_Line_No." | " . $poitem->Shipment_Num." | ".$poitem->Site_Name;

                                   ?>

                                    @if(!in_array($object,$usedpo))
                                      <option value="{{$poitem->PO_No}} | {{$poitem->PO_Line_No}} | {{$poitem->Shipment_Num}} | {{$poitem->Site_Name}}">{{$poitem->PO_No}} | {{$poitem->PO_Line_No}} | {{$poitem->Shipment_Num}} | {{$poitem->Site_Name}} | {{$poitem->Item_Description}}</option>
                                    @endif

                                  @endforeach

                                </select>

                              @elseif($column->Type=="Invoice List")
                                <select class="form-control select2" name="{{$column->Column_Name}}2" id="{{$column->Column_Name}}2" style="width:100%;">
                                  <option value=""></option>
                                @foreach($invoices as $invoice)
                                  <select class="form-control select2" name="{{$column->Column_Name}}2" id="{{$column->Column_Name}}2" style="width:100%;">
                                   <option value="{{$invoice->Invoice_No}}">{{$invoice->Invoice_No}}</option>
                                @endforeach
                              @elseif($column->Type=="Currency")

                                <input type="number" class="form-control" name="{{$column->Column_Name}}2" id="{{$column->Column_Name}}2" min="1" step="any"/>
                              @endif

                            </div>

                          </div>

                        @endif

                      @endforeach

                    </div>

                    @endif

                  </form>
                </div>
              </div>
              <div class="modal-footer">
                <center><img src="{{ URL::to('/') ."/img/ajax-loader.gif" }}" width="50px" height="50px" alt="Loading" name='ajaxloader5' id="ajaxloader5"></center>
                <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                <button type="button" class="btn btn-primary" id="updatesite" onclick="updatesite()">Update</button>
              </div>
            </div>
          </div>
        </div>

         <!-- <div class="modal fade" id="NewTracker" tabindex="-1" role="dialog" aria-labelledby="myModalLabel"> -->
           <div class="modal fade" id="NewTracker" role="dialog" aria-labelledby="myModalLabel">
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

                  <div class="row">
                      <div class="col-md-3">

                        <div class="form-group">
                          <label class="control-label">Tracker Name : </label>
                        </div>
                      </div>
                      <div class="col-md-9">
                          <input type="text" class="form-control" name="tracker" id="tracker" placeholder="" value"{{$project->Project_Name}} Tracker"/>
                          <br>
                        </div>
                  </div>

                  <div class="row">

                      <div class="col-md-2">

                        <div class="form-group">
                          <label class="control-label">Criteria 1 : </label>
                        </div>
                      </div>

                      <div class="col-md-3">

                        <div class="form-group">
                          <select class="form-control select2" id="Column1" name="Column1">
                            <option value="">Select a column</option>
                            @foreach ($trackercolumns as $column)

                              <option value="{{$column->Column_Name}}">{{$column->Column_Name}}</option>

                            @endforeach
                           </select>
                         </div>
                      </div>
                      <div class="col-md-2">
                        <div class="form-group">
                          <select class="form-control select2" id="Condition1" name="Condition1">
                            <option value="=">=</option>
                            <option value="!=">!=</option>
                            <option value="in">In</option>
                            <option value="not in">Not In</option>
                            <option value="like">Like</option>
                            <option value="not like">Not Like</option>

                           </select>
                         </div>

                       </div>
                       <div class="col-md-3">
                         <div class="form-group">
                         <input class="form-control" type="text" id="Criteria1" name="Criteria1" placeholder="Enter Filter..."></input>
                         </div>
                      </div>

                      <div class="col-md-2">
                        <div class="form-group">
                          <select class="form-control select2" id="Operator1" name="Operator1">
                            <option value="AND">AND</option>
                            <option value=" OR">OR</option>
                           </select>
                         </div>

                       </div>

                    </div>

                    <div class="row">

                      <div class="col-md-2">

                        <div class="form-group">
                          <label class="control-label">Criteria 2 : </label>
                        </div>
                      </div>

                      <div class="col-md-3">

                        <div class="form-group">
                          <select class="form-control select2" id="Column2" name="Column2">
                            <option value="">Select a column</option>
                            @foreach ($trackercolumns as $column)

                              <option value="{{$column->Column_Name}}">{{$column->Column_Name}}</option>

                            @endforeach
                           </select>
                         </div>
                      </div>
                      <div class="col-md-2">
                        <div class="form-group">
                          <select class="form-control select2" id="Condition2" name="Condition2">
                            <option value="=">=</option>
                            <option value="!=">!=</option>
                            <option value="in">In</option>
                            <option value="not in">Not In</option>
                            <option value="like">Like</option>
                            <option value="not like">Not Like</option>

                           </select>
                         </div>

                       </div>
                       <div class="col-md-3">
                         <div class="form-group">
                         <input class="form-control" type="text" id="Criteria2" name="Criteria2" placeholder="Enter Filter..."></input>
                         </div>
                      </div>

                      <div class="col-md-2">
                        <div class="form-group">
                          <select class="form-control select2" id="Operator2" name="Operator2">
                            <option value="AND">AND</option>
                            <option value=" OR">OR</option>
                           </select>
                         </div>

                       </div>

                    </div>

                    <div class="row">

                      <div class="col-md-2">

                        <div class="form-group">
                          <label class="control-label">Criteria 3 : </label>
                        </div>
                      </div>

                      <div class="col-md-3">

                        <div class="form-group">
                          <select class="form-control select2" id="Column3" name="Column3">
                            <option value="">Select a column</option>
                            @foreach ($trackercolumns as $column)

                              <option value="{{$column->Column_Name}}">{{$column->Column_Name}}</option>

                            @endforeach
                           </select>
                         </div>
                      </div>
                      <div class="col-md-2">
                        <div class="form-group">
                          <select class="form-control select2" id="Condition3" name="Condition3">
                            <option value="=">=</option>
                            <option value="!=">!=</option>
                            <option value="in">In</option>
                            <option value="not in">Not In</option>
                            <option value="like">Like</option>
                            <option value="not like">Not Like</option>

                           </select>
                         </div>

                       </div>
                       <div class="col-md-3">
                         <div class="form-group">
                         <input class="form-control" type="text" id="Criteria3" name="Criteria3" placeholder="Enter Filter..."></input>
                         </div>
                      </div>

                      <div class="col-md-2">
                        <div class="form-group">
                          <select class="form-control select2" id="Operator3" name="Operator3">
                            <option value="AND">AND</option>
                            <option value=" OR">OR</option>
                           </select>
                         </div>

                       </div>

                    </div>

                    <div class="row">

                        <div class="col-md-2">

                          <div class="form-group">
                            <label class="control-label">Criteria 4 : </label>
                          </div>
                        </div>

                        <div class="col-md-3">

                          <div class="form-group">
                            <select class="form-control select2" id="Column4" name="Column4">
                              <option value="">Select a column</option>
                              @foreach ($trackercolumns as $column)

                                <option value="{{$column->Column_Name}}">{{$column->Column_Name}}</option>

                              @endforeach
                             </select>
                           </div>
                        </div>
                        <div class="col-md-2">
                          <div class="form-group">
                            <select class="form-control select2" id="Condition4" name="Condition4">
                              <option value="=">=</option>
                              <option value="!=">!=</option>
                              <option value="in">In</option>
                              <option value="not in">Not In</option>
                              <option value="like">Like</option>
                              <option value="not like">Not Like</option>

                             </select>
                           </div>

                         </div>
                         <div class="col-md-3">
                           <div class="form-group">
                           <input class="form-control" type="text" id="Criteria4" name="Criteria4" placeholder="Enter Filter..."></input>
                           </div>
                        </div>

                        <div class="col-md-2">
                          <div class="form-group">
                            <select class="form-control select2" id="Operator4" name="Operator4">
                              <option value="AND">AND</option>
                              <option value=" OR">OR</option>
                             </select>
                           </div>

                         </div>

                      </div>

                      <div class="row">

                          <div class="col-md-2">

                            <div class="form-group">
                              <label class="control-label">Criteria 5 : </label>
                            </div>
                          </div>

                          <div class="col-md-3">

                            <div class="form-group">
                              <select class="form-control select2" id="Column5" name="Column5">
                                <option value="">Select a column</option>
                                @foreach ($trackercolumns as $column)

                                  <option value="{{$column->Column_Name}}">{{$column->Column_Name}}</option>

                                @endforeach
                               </select>
                             </div>
                          </div>
                          <div class="col-md-2">
                            <div class="form-group">
                              <select class="form-control select2" id="Condition5" name="Condition5">
                                <option value="=">=</option>
                                <option value="!=">!=</option>
                                <option value="in">In</option>
                                <option value="not in">Not In</option>
                                <option value="like">Like</option>
                                <option value="not like">Not Like</option>

                               </select>
                             </div>

                           </div>
                           <div class="col-md-3">
                             <div class="form-group">
                             <input class="form-control" type="text" id="Criteria5" name="Criteria5" placeholder="Enter Filter..."></input>
                             </div>
                          </div>

                        </div>

                    <div class="form-group">
                      <table border="1" style="width:100%;text-align:center;">
                        <thead style="color:#000;font-weight:bold;background-color:#aaa;s"><tr><td>Column Name</td><td>Column Type</td><td>Column Color</td></tr></thead>
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
                                    <option value="Staff List">Staff List</option>
                                    <option value="Contractor List">Contractor List</option>
                                    <option value="PO Received">PO Received</option>
                                    <option value="PO Issued">PO Issued</option>
                                    <option value="Invoice Received">Invoice Received</option>
                                    <option value="Invoice Issued">Invoice Issued</option>
                                    <option value="Read Only">Read Only</option>

                                  </select>
                              </td>
                              <td>
                                <input type="color" name="ColorCode[]" id="ColorCode" value="#000000">
                              </td>
                            </tr>
                          @endfor
                        </tbody>
                      </table>
                    </div>

                  </div>
                </div>

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
                   <h4 class="modal-title" id="myModalLabel">Update Tracker</h4>

                 </div>

                 <div class="modal-body">
                   <div class="form-group">

                     <div class="row">
                         <div class="col-md-3">

                           <div class="form-group">
                             <label class="control-label">Tracker Name : </label>
                           </div>
                         </div>
                         <div class="col-md-8">
                           @foreach($trackername as $name)
                            <input type="text" class="form-control" name="renametracker" id="renametracker" placeholder="" value="{{$name->Tracker_Name}}"/>
                           @endforeach
                           <!-- <input type="text" class="form-control" name="tracker" id="tracker" placeholder="" value"{{$project->Project_Name}} Tracker"/> -->
                           <br>
                         </div>
                     </div>

                     <div class="row">

                         <div class="col-md-2">

                           <div class="form-group">
                             <label class="control-label">Criteria 1 : </label>
                           </div>
                         </div>

                         <div class="col-md-3">

                           <div class="form-group">
                             <select class="form-control select2" id="Column1-2" name="Column1-2">
                               <option value="">Select a column</option>

                               @if($cri1)
                                  <option value="{{$cri1[0]}}" selected>{{$cri1[0]}}</option>
                               @endif

                               @foreach ($trackercolumns as $column)

                                 <option value="{{$column->Column_Name}}">{{$column->Column_Name}}</option>

                               @endforeach
                              </select>
                            </div>
                         </div>
                         <div class="col-md-2">
                           <div class="form-group">
                             <select class="form-control select2" id="Condition1-2" name="Condition1-2">

                               @if($cri1)
                                  <option value="{{$cri1[1]}}" selected>{{$cri1[1]}}</option>
                               @endif

                               <option value="=">=</option>
                               <option value="!=">!=</option>
                               <option value="in">In</option>
                               <option value="not in">Not In</option>
                               <option value="like">Like</option>
                               <option value="not like">Not Like</option>

                              </select>
                            </div>

                          </div>
                          <div class="col-md-3">
                            <div class="form-group">
                              @if($cri1)
                                <input class="form-control" type="text" id="Criteria1-2" name="Criteria1-2" placeholder="Enter Filter..." value="{{$cri1[2]}}"></input>
                              @else
                                <input class="form-control" type="text" id="Criteria1-2" name="Criteria1-2" placeholder="Enter Filter..."></input>

                              @endif

                            </div>
                         </div>

                         <div class="col-md-2">
                           <div class="form-group">
                             <select class="form-control select2" id="Operator1-2" name="Operator1-2">
                               @if($cri1)
                                  <option value="{{$cri1[3]}}" selected>{{$cri1[3]}}</option>
                               @endif

                               <option value="AND">AND</option>
                               <option value=" OR">OR</option>

                              </select>
                            </div>

                          </div>

                       </div>

                       <div class="row">

                           <div class="col-md-2">

                             <div class="form-group">
                               <label class="control-label">Criteria 2 : </label>
                             </div>
                           </div>

                           <div class="col-md-3">

                             <div class="form-group">
                               <select class="form-control select2" id="Column2-2" name="Column2-2">
                                 <option value="">Select a column</option>

                                 @if($cri2)
                                    <option value="{{$cri2[0]}}" selected>{{$cri2[0]}}</option>
                                 @endif

                                 @foreach ($trackercolumns as $column)

                                   <option value="{{$column->Column_Name}}">{{$column->Column_Name}}</option>

                                 @endforeach
                                </select>
                              </div>
                           </div>
                           <div class="col-md-2">
                             <div class="form-group">
                               <select class="form-control select2" id="Condition2-2" name="Condition2-2">

                                 @if($cri2)
                                    <option value="{{$cri2[1]}}" selected>{{$cri2[1]}}</option>
                                 @endif

                                 <option value="=">=</option>
                                 <option value="!=">!=</option>
                                 <option value="in">In</option>
                                 <option value="not in">Not In</option>
                                 <option value="like">Like</option>
                                 <option value="not like">Not Like</option>

                                </select>
                              </div>

                            </div>
                            <div class="col-md-3">
                              <div class="form-group">
                                @if($cri2)
                                  <input class="form-control" type="text" id="Criteria2-2" name="Criteria2-2" placeholder="Enter Filter..." value="{{$cri2[2]}}"></input>
                                @else
                                  <input class="form-control" type="text" id="Criteria2-2" name="Criteria2-2" placeholder="Enter Filter..."></input>

                                @endif

                              </div>
                           </div>

                           <div class="col-md-2">
                             <div class="form-group">
                               <select class="form-control select2" id="Operator2-2" name="Operator2-2">
                                 @if($cri2)
                                    <option value="{{$cri2[3]}}" selected>{{$cri2[3]}}</option>
                                 @endif

                                 <option value="AND">AND</option>
                                 <option value=" OR">OR</option>

                                </select>
                              </div>

                            </div>

                         </div>

                         <div class="row">

                             <div class="col-md-2">

                               <div class="form-group">
                                 <label class="control-label">Criteria 3 : </label>
                               </div>
                             </div>

                             <div class="col-md-3">

                               <div class="form-group">
                                 <select class="form-control select2" id="Column3-2" name="Column3-2">
                                   <option value="">Select a column</option>

                                   @if($cri3)
                                      <option value="{{$cri3[0]}}" selected>{{$cri3[0]}}</option>
                                   @endif

                                   @foreach ($trackercolumns as $column)

                                     <option value="{{$column->Column_Name}}">{{$column->Column_Name}}</option>

                                   @endforeach
                                  </select>
                                </div>
                             </div>
                             <div class="col-md-2">
                               <div class="form-group">
                                 <select class="form-control select2" id="Condition3-2" name="Condition3-2">

                                   @if($cri3)
                                      <option value="{{$cri3[1]}}" selected>{{$cri3[1]}}</option>
                                   @endif

                                   <option value="=">=</option>
                                   <option value="!=">!=</option>
                                   <option value="in">In</option>
                                   <option value="not in">Not In</option>
                                   <option value="like">Like</option>
                                   <option value="not like">Not Like</option>

                                  </select>
                                </div>

                              </div>
                              <div class="col-md-3">
                                <div class="form-group">
                                  @if($cri3)
                                    <input class="form-control" type="text" id="Criteria3-2" name="Criteria3-2" placeholder="Enter Filter..." value="{{$cri3[2]}}"></input>
                                  @else
                                    <input class="form-control" type="text" id="Criteria3-2" name="Criteria3-2" placeholder="Enter Filter..."></input>

                                  @endif

                                </div>
                             </div>

                             <div class="col-md-2">
                               <div class="form-group">
                                 <select class="form-control select2" id="Operator3-2" name="Operator3-2">
                                   @if($cri3)
                                      <option value="{{$cri3[3]}}" selected>{{$cri3[3]}}</option>
                                   @endif

                                   <option value="AND">AND</option>
                                   <option value=" OR">OR</option>

                                  </select>
                                </div>

                              </div>

                           </div>

                           <div class="row">

                               <div class="col-md-2">

                                 <div class="form-group">
                                   <label class="control-label">Criteria 4 : </label>
                                 </div>
                               </div>

                               <div class="col-md-3">

                                 <div class="form-group">
                                   <select class="form-control select2" id="Column4-2" name="Column4-2">
                                     <option value="">Select a column</option>

                                     @if($cri4)
                                        <option value="{{$cri4[0]}}" selected>{{$cri4[0]}}</option>
                                     @endif

                                     @foreach ($trackercolumns as $column)

                                       <option value="{{$column->Column_Name}}">{{$column->Column_Name}}</option>

                                     @endforeach
                                    </select>
                                  </div>
                               </div>
                               <div class="col-md-2">
                                 <div class="form-group">
                                   <select class="form-control select2" id="Condition4-2" name="Condition4-2">

                                     @if($cri4)
                                        <option value="{{$cri4[1]}}" selected>{{$cri4[1]}}</option>
                                     @endif

                                     <option value="=">=</option>
                                     <option value="!=">!=</option>
                                     <option value="in">In</option>
                                     <option value="not in">Not In</option>
                                     <option value="like">Like</option>
                                     <option value="not like">Not Like</option>

                                    </select>
                                  </div>

                                </div>
                                <div class="col-md-3">
                                  <div class="form-group">
                                    @if($cri4)
                                      <input class="form-control" type="text" id="Criteria4-2" name="Criteria4-2" placeholder="Enter Filter..." value="{{$cri4[2]}}"></input>
                                    @else
                                      <input class="form-control" type="text" id="Criteria4-2" name="Criteria4-2" placeholder="Enter Filter..."></input>

                                    @endif

                                  </div>
                               </div>

                               <div class="col-md-2">
                                 <div class="form-group">
                                   <select class="form-control select2" id="Operator4-2" name="Operator4-2">
                                     @if($cri4)
                                        <option value="{{$cri4[3]}}" selected>{{$cri4[3]}}</option>
                                     @endif

                                     <option value="AND">AND</option>
                                     <option value=" OR">OR</option>

                                    </select>
                                  </div>

                                </div>

                             </div>

                             <div class="row">

                                 <div class="col-md-2">

                                   <div class="form-group">
                                     <label class="control-label">Criteria 5 : </label>
                                   </div>
                                 </div>

                                 <div class="col-md-3">

                                   <div class="form-group">
                                     <select class="form-control select2" id="Column5-2" name="Column5-2">
                                       <option value="">Select a column</option>

                                       @if($cri5)
                                          <option value="{{$cri4[0]}}" selected>{{$cri5[0]}}</option>
                                       @endif

                                       @foreach ($trackercolumns as $column)

                                         <option value="{{$column->Column_Name}}">{{$column->Column_Name}}</option>

                                       @endforeach
                                      </select>
                                    </div>
                                 </div>
                                 <div class="col-md-2">
                                   <div class="form-group">
                                     <select class="form-control select2" id="Condition5-2" name="Condition5-2">

                                       @if($cri5)
                                          <option value="{{$cri5[1]}}" selected>{{$cri5[1]}}</option>
                                       @endif

                                       <option value="=">=</option>
                                       <option value="!=">!=</option>
                                       <option value="in">In</option>
                                       <option value="not in">Not In</option>
                                       <option value="like">Like</option>
                                       <option value="not like">Not Like</option>

                                      </select>
                                    </div>

                                  </div>
                                  <div class="col-md-3">
                                    <div class="form-group">
                                      @if($cri5)
                                        <input class="form-control" type="text" id="Criteria5-2" name="Criteria5-2" placeholder="Enter Filter..." value="{{$cri5[2]}}"></input>
                                      @else
                                        <input class="form-control" type="text" id="Criteria5-2" name="Criteria5-2" placeholder="Enter Filter..."></input>

                                      @endif

                                    </div>
                                 </div>

                               </div>

                   </div>

                 </div>

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

         <div class="modal fade" id="ImportHuaweiPO" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
           <div class="modal-dialog" role="document">
             <div class="modal-content">
               <div class="modal-header">
                 <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                 <h4 class="modal-title" id="myModalLabel">Import Huawei PO</h4>
               </div>
               <div class="modal-body">
                 <div class="form-group">
                   <form enctype="multipart/form-data" id="upload_form3" role="form" method="POST" action="" >
                     <input type="hidden" class="form-control" id="ProjectId4" name="ProjectId4" value="{{$projectid}}">
                     <input type="file" id="import3" name="import3">
                   </form>
                 </div>
               </div>
               <div class="modal-footer">
                 <center><img src="{{ URL::to('/') ."/img/ajax-loader.gif" }}" width="50px" height="50px" alt="Loading" name='ajaxloader3' id="ajaxloader3"></center>
                 <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                 <button type="button" class="btn btn-primary" onclick="importhuaweipo()">Import</button>
               </div>
             </div>
           </div>
         </div>

         <div class="modal fade" id="DeleteTracker" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
            <div class="modal-dialog">
              <div class="modal-content">
                <div class="modal-header">
                  <div id="exist-alert" class="alert alert-warning alert-dismissible" style="display:none;">
                    <h4><i class="icon fa fa-check"></i> Alert!</h4>
                    <div id="changepasswordmessage"></div>
                  </div>
                  <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                  <h4 class="modal-title" id="myModalLabel">Delete Tracker</h4>

                </div>

                <div class="modal-body">
                    Are you sure you wish to delete this tracker?
                </div>


                <div class="modal-footer">

                  <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                  <button type="button" class="btn btn-danger" onclick="deletetracker({{$trackerid}})">Apply</button>
                </div>
              </div>
            </div>
          </div>

         <div class="modal fade" id="DuplicateTracker" role="dialog" aria-labelledby="myModalLabel">
            <div class="modal-dialog">
              <div class="modal-content">
                <div class="modal-header">
                  <div id="exist-alert" class="alert alert-warning alert-dismissible" style="display:none;">
                    <h4><i class="icon fa fa-check"></i> Alert!</h4>
                    <div id="changepasswordmessage"></div>
                  </div>
                  <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                  <h4 class="modal-title" id="myModalLabel">Duplicate Tracker</h4>

                </div>

                <div class="modal-body">

                    <div class="form-group">
                      <label class="col-md-4 control-label">Copy From</label>
                      <div class="col-md-8">
                        <select class="form-control select2" id="ExistingTracker" width="100%">
                          <option></option>

                          @foreach($existingtracker as $tracker)

                              <option value="{{$tracker->Id}}">{{$tracker->Tracker_Name}}[{{$tracker->Project_Name}}]</option>

                          @endforeach

                      </select>

                      </div>

                    </div>

                      <br>
                      <br>

                    <div class="form-group">
                      <label class="col-md-4 control-label">New Tracker Name</label>
                      <div class="col-md-8">

                         <input type="text" class="form-control" name="duplicatetracker" id="duplicatetracker" placeholder=""/>

                      </div>
                    </div>


                </div>

                <br>
                <div class="modal-footer">

                  <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                  <button type="button" class="btn btn-primary" onclick="duplicatetracker()">Apply</button>
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
                        <thead style="color:#000;font-weight:bold;background-color:#aaa;s"><tr><td>Column Name</td><td>Column Type</td><td>Column Code</td></tr></thead>
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
                                    <option value="Staff List">Staff List</option>
                                    <option value="Contractor List">Contractor List</option>
                                    <option value="PO Received">PO Received</option>
                                    <option value="PO Issued">PO Issued</option>
                                    <option value="Invoice Received">Invoice Received</option>
                                    <option value="Invoice Issued">Invoice Issued</option>
                                    <option value="Read Only">Read Only</option>
                                  </select>
                              </td>
                              <td>
                                <input type="color" name="ColorCode[]" id="ColorCode" value="#000000">
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
                  <center><img src="{{ URL::to('/') ."/img/ajax-loader.gif" }}" width="50px" height="50px" alt="Loading" name='ajaxloaderaddcolumn' id="ajaxloaderaddcolumn"></center>
                  <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                  <button type="button" class="btn btn-primary" id="addcolumnbutton" onclick="addcolumn({{$trackerid}})">Add</button>
                </div>
              </div>
            </div>
          </div>

          <div class="modal fade" id="UpdatePurchaseInvoice" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
            <div class="modal-dialog">
              <div class="modal-content">
                <div class="modal-header">
                  <div id="exist-alert" class="alert alert-warning alert-dismissible" style="display:none;">
                    <h4><i class="icon fa fa-check"></i> Alert!</h4>
                    <div id="changepasswordmessage"></div>
                  </div>
                  <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                  <h4 class="modal-title" id="myModalLabel">Update Purchase Invoice</h4>

                </div>

                <div class="modal-body">
                  <div class="form-group">
                    <form id="update_form2" role="form" method="POST" action="" >

                      <input type='hidden' name='TargetId' id='TargetId' value=0/>

                    </form>


                  </div>

                </div>

                <br><br><br><br>
                <div class="modal-footer">

                  <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                  <button type="button" class="btn btn-primary" onclick="">Update</button>
                </div>
              </div>
            </div>
          </div>

          <div class="modal fade" id="ViewPhoto" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" style="overflow:scroll;">
            <div class="modal-dialog-wide1">
              <div class="modal-content">
                <div class="modal-header">
                  <div id="exist-alert" class="alert alert-warning alert-dismissible" style="display:none;">
                    <h4><i class="icon fa fa-check"></i> Alert!</h4>
                  </div>
                  <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                  <h4 class="modal-title" id="">Tracker Photo(s)</h4>

                </div>

                <div class="modal-body" name="photocontent" id="photocontent" style="height: auto;width: 100%;min-height: 500px;">

                </div>

                <br><br><br><br>
                <div class="modal-footer">

                  <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
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
                      <input type="hidden" class="form-control" id="ProjectId5" name="ProjectId5" value="{{$projectid}}">
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

          <div class="modal fade" id="SubmitDocument2" role="dialog" aria-labelledby="myModalLabel">
            <div class="modal-dialog-wide" role="document">
              <div class="modal-content">
                <div class="modal-header">
                  <div id="exist-alert" class="alert alert-warning alert-dismissible" style="display:none;">
                    <h4><i class="icon fa fa-check"></i> Alert!</h4>
                  </div>
                  <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                  <h4 class="modal-title" id="myModalLabel">Submit Document</h4>

                </div>

                <div class="modal-body" name="documentcontent" id="documentcontent">

                </div>

                <br><br><br><br>
                <div class="modal-footer">

                  <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                </div>
              </div>
            </div>
          </div>

          <div class="modal fade" id="FibreUpdate" role="dialog" aria-labelledby="myModalLabel">
            <div class="modal-dialog" role="document">
              <div class="modal-content">
                <div class="modal-header">
                  <div id="exist-alert" class="alert alert-warning alert-dismissible" style="display:none;">
                    <h4><i class="icon fa fa-check"></i> Alert!</h4>
                  </div>
                  <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                  <h4 class="modal-title" id="myModalLabel">Fibre Progress Update</h4>

                </div>

                <div class="modal-body" name="fibreupdatecontent" id="fibreupdatecontent">
                    <form id="fibreupdateform" role="form" method="POST" action="" >

                    <div class="col-md-12">
                      <label class="col-md-4 control-label">Team : </label>
                      <div class="col-md-8">
                        <select class="form-control select2" id="Team" name="Team">
                          <option></option>
                          <option>Team A</option>
                          <option>Team B</option>
                          <option>Team C</option>

                        </select>
                      </div>
                    </div>

                    <br>
                    <h4 class="modal-title" id="myModalLabel">Daily Progress</h4>
                    <br>
                      <div class="form-group">
                        <div class="row">
                          <div class="col-md-4">
                            <label class="col-md-6 control-label">HDD Completed : </label>
                            <div class="col-md-6">
                              <input type="number" class="form-control" name="HDD" id="HDD" required/>
                            </div>
                          </div>

                          <div class="col-md-4">
                            <label class="col-md-6 control-label">GV Completed : </label>
                            <div class="col-md-6">
                              <input type="number" class="form-control" name="GV" id="GV" required/>
                            </div>
                          </div>

                          <div class="col-md-4">
                            <label class="col-md-6 control-label">MH Completed : </label>
                            <div class="col-md-6">
                              <input type="number" class="form-control" name="MH" id="MH" required/>
                            </div>
                          </div>

                          <br><br><br>

                          <div class="col-md-4">
                            <label class="col-md-6 control-label">Poles Completed : </label>
                            <div class="col-md-6">
                              <input type="number" class="form-control" name="Poles" id="Poles" required/>
                            </div>
                          </div>

                          <div class="col-md-4">
                            <label class="col-md-6 control-label">Subduct Completed : </label>
                            <div class="col-md-6">
                              <input type="number" class="form-control" name="Subduct" id="Subduct" required/>
                            </div>
                          </div>

                          <div class="col-md-4">
                            <label class="col-md-6 control-label">Cables Completed : </label>
                            <div class="col-md-6">
                              <input type="number" class="form-control" name="Cables" id="Cables" required/>
                            </div>
                          </div>

                        </div>
                      </div>

                      <br>
                      <h4 class="modal-title" id="myModalLabel">Site Diary</h4>

                      <div class="row">
                        <div class="col-md-12">
                          <label class="col-md-4 control-label">Weather : </label>
                          <div class="col-md-8">
                            <select class="form-control select2" id="Weather" name="Weather">
                              <option></option>
                              <option>Good</option>
                              <option>Raining</option>
                              <option>Windy</option>
                              <option>Sunny</option>

                            </select>
                          </div>
                        </div>

                      </div>

                      <div class="row">
                        <div class="col-md-12">
                          <label class="col-md-4 control-label">Activity : </label>
                          <div class="col-md-8">
                            <input type="text" class="form-control" name="Activity" id="Activity" required/>
                          </div>
                        </div>
                      </div>

                      <div class="row">
                        <div class="col-md-12">
                          <label class="col-md-4 control-label">Issue : </label>
                          <div class="col-md-8">
                            <input type="text" class="form-control" name="Issue" id="Issue" required/>
                          </div>
                        </div>
                      </div>

                      <div class="row">
                        <div class="col-md-12">
                          <label class="col-md-4 control-label">Action : </label>
                          <div class="col-md-8">
                            <input type="text" class="form-control" name="Action" id="Action" required/>
                          </div>
                        </div>
                      </div>

                      <div class="row">
                        <div class="col-md-12">
                          <label class="col-md-4 control-label">Remarks : </label>
                          <div class="col-md-8">
                            <input type="text" class="form-control" name="Remarks" id="Remarks" required/>
                          </div>
                        </div>
                      </div>

                      <br>
                      <h4 class="modal-title" id="myModalLabel">Photo Attachment</h4>
                      <div class="form-group">
                            <input type="file" id="uploadphoto" name="uploadphoto" accept=".doc,.docx,.pdf">
                      </div>
                      <input type="hidden" class="form-control" name="FibreId" id="FibreId"/>
                    </form>
                </div>

                <div class="modal-footer">

                  <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                  <button type="button" class="btn btn-primary" onclick="updatefibre()">Update</button>
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
                        <thead style="color:#000;font-weight:bold;background-color:#aaa;s"><tr><td>Remove</td><td>Column Name</td><td>Column Type</td><td>Column Color</td></tr></thead>
                        <tbody>
                           <?php $a=5;?>
                          @for ($i = 0; $i < count($trackercolumns); $i++)
                            <tr>
                              <td><input type="checkbox" name="selectrow" id="selectrow" <?php if(!$me->Delete_Column) echo "disabled"; ?> value="{{$trackercolumns[$i]->Id}}"></td>
                              <td><input type="hidden" name="updatecolumnid[]" id="updatecolumnid" value="{{$trackercolumns[$i]->Id}}">{{$trackercolumns[$i]->Column_Name}}</td>
                              <td><select name="updateColumnType[]" id="updateColumnType" class="form-control" <?php if(!$me->Update_Column) echo "disabled"; ?>>



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

                                    @if($trackercolumns[$i]->Type=="Staff List")
                                      <option value="Staff List" selected>Staff List</option>
                                    @else
                                      <option value="Staff List">Staff List</option>
                                    @endif

                                    @if($trackercolumns[$i]->Type=="Contractor List")
                                      <option value="Contractor List" selected>Contractor List</option>
                                    @else
                                      <option value="Contractor List">Contractor List</option>
                                    @endif

                                    @if($trackercolumns[$i]->Type=="PO Received")
                                      <option value="PO Received" selected>PO Received</option>
                                    @else
                                      <option value="PO Received">PO Received</option>
                                    @endif

                                    @if($trackercolumns[$i]->Type=="PO Issued")
                                      <option value="PO Issued" selected>PO Issued</option>
                                    @else
                                      <option value="PO Issued">PO Issued</option>
                                    @endif

                                    @if($trackercolumns[$i]->Type=="Invoice Received")
                                      <option value="Invoice Received" selected>Invoice Received</option>
                                    @else
                                      <option value="Invoice Received">Invoice Received</option>
                                    @endif

                                    @if($trackercolumns[$i]->Type=="Invoice Issued")
                                      <option value="Invoice Issued" selected>Invoice Issued</option>
                                    @else
                                      <option value="Invoice Issued">Invoice Issued</option>
                                    @endif

                                    @if($trackercolumns[$i]->Type=="Read Only")
                                      <option value="Read Only" selected>Read Only</option>
                                    @else
                                      <option value="Read Only">Read Only</option>
                                    @endif

                                  </select>
                              </td>

                              <td>

                                @if($trackercolumns[$i]->Color_Code=="FFFFFF" || $trackercolumns[$i]->Color_Code=="ffffff")
                                  <input type="color" name="colorcode[]" id="colorcode" value="#000000" <?php if(!$me->Update_Column) echo "readonly"; ?>>

                                @else
                                  <input type="color" name="colorcode[]" id="colorcode" value="#{{$trackercolumns[$i]->Color_Code}}" <?php if(!$me->Update_Column) echo "readonly"; ?>>

                                @endif

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
                  <center><img src="{{ URL::to('/') ."/img/ajax-loader.gif" }}" width="50px" height="50px" alt="Loading" name='ajaxloaderupdatecolumn' id="ajaxloaderupdatecolumn"></center>
                  <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                  <button type="button" class="btn btn-danger" id="updatecolumnbutton"  onclick="updatecolumn()">Apply</button>
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

      @if($me->User_Type!="External")



        @endif

            <div class="col-md-12" id="zoomdiv">

              <div class="box-body">

                @if($me->User_Type!="External")

                <!-- @if($me->View_PO_Summary && $me->View_Invoice_Summary)
                  <a href='{{ url('/dashboard') }}/{{$projectid}}' target="_blank"><button type="button" class="btn btn-success">Dashboard</button></a>
                @endif -->

                <a href='{{ url('/customizereport') }}/{{$projectid}}' target="_blank"><button type="button" class="btn btn-success btn-xs">Customized Report</button></a>


                @endif

                @if(str_contains(strtoupper($project->Project_Name),"HUAWEI"))

                  <a data-toggle="modal" data-target="#ImportHuaweiPO"><button type="button" class="pull-right btn btn-warning btn-xs" >Import Huawei PO</button></a>

                @endif

                @if($me->Import_Tracker)

                  <a data-toggle="modal" data-target="#ImportData"><button type="button" class="pull-right btn btn-warning btn-xs" >Import Data</button></a>

                @endif

                @if($me->Add_Option)
                  <a data-toggle="modal" href="{{ url('/optioncontrol') }}/tracker/false" target="_blank"><button type="button btn-xs" class="pull-right btn btn-warning btn-xs">Add Option</button></a>
                @endif
                @if($me->Reorder_Column)
                  <a data-toggle="modal" data-target="#ReorderColumn"><button type="button" class="pull-right btn btn-primary btn-xs">Reorder Column</button></a>
                @endif

                @if($me->Update_Column ||$me->Delete_Column)
                  <a data-toggle="modal" data-target="#UpdateColumn"><button type="button" class="pull-right btn btn-primary btn-xs">Update Column</button></a>
                @endif

                @if($me->Add_Column)
                  <a data-toggle="modal" data-target="#AddColumn"><button type="button" class="pull-right btn btn-primary btn-xs">Add Column</button></a>
                @endif

                @if($me->Tracker_Management)

                  <a data-toggle="modal" data-target="#DeleteTracker"><button type="button" class="pull-right btn btn-danger btn-xs">Delete Tracker</button></a>
                  <a data-toggle="modal" data-target="#DuplicateTracker"><button type="button" class="pull-right btn btn-success btn-xs">Duplicate Tracker</button></a>
                  <a data-toggle="modal" data-target="#RenameTracker"><button type="button" class="pull-right btn btn-success btn-xs">Update Tracker</button></a>
                  <a data-toggle="modal" data-target="#NewTracker"><button type="button" class="pull-right btn btn-success btn-xs">Create Tracker</button></a>
                @endif
              </div>

              <div class="box-body">

                <div class="nav-tabs-custom">
                  <ul class="nav nav-tabs">

                      <!-- @foreach($trackerlist as $tracker)

                        @if($tracker->Id==$trackerid) -->

                          <li class="active"><a href="#po" data-toggle="tab">{{$trackername[0]->Tracker_Name}}</a></li>

                        <!-- @else

                          <li><a href="{{ url('/handsontable') }}/{{$projectid}}/{{$tracker->Id}}">{{$tracker->Tracker_Name}}</a></li>

                        @endif

                      @endforeach -->

                    {{-- <li class="active"><a href="#po" data-toggle="tab" id="potab">PO</a></li>
                    <li><a href="#poitem" data-toggle="tab" id="poitemtab">PO Item</a></li> --}}
                  </ul>

              <div class="box-body">

                <div class="col-md-12">
                @if($trackerwriteaccess)
                 <button type="button" class="btn btn-success btn-xs" onclick="clickToSave()">Save Changes</button>
                @endif


                @if($me->Edit_Project_Code)
                  <button type="button" class="btn btn-primary btn-xs" onclick="createnewsite()">Create New Site</button>
                @endif
                  <button type="button" class="btn btn-success btn-xs" id="export-file">Export to CSV</button>
                  <button type="button" class="btn btn-warning btn-xs" onclick="clearfiltering()">Clear Filtering</button>
                  <button type="button" class="btn btn-warning btn-xs" onclick="unfreeze()">Unfreeze All Column</button>
                  <button type="button" class="btn btn-warning btn-xs" onclick="unhide()">Unhide All Column</button>


                <!-- <div class="col-md-12"> -->

                  <br><b>Search : <input style="color: blue;font-Weight:bold;" type="text" name="searchtext" id="searchtext" size="15"/><button type="button" class="btn btn-success btn-xs" onclick="filtertext()">Search</button><button type="button" class="btn btn-danger btn-xs" onclick="resetsearch()">Reset</button>
                  <b>Data &nbsp;&nbsp;&nbsp;&nbsp;: <input style="color: blue;font-Weight:bold;" type="text" name="contenttext" id="contenttext" size="20" disabled/>
                </div>
                  <!-- Sum : <input style="color: blue;font-Weight:bold;" type="text" name="sumtext" id="sumtext" size="15" value="0" disabled/>
                  Count : <input style="color: blue;font-Weight:bold;" type="text" name="counttext" id="counttext" size="10" value="{{sizeof($trackerview)}}" disabled/></b> -->

                <!-- </div> -->
              </div>

              @if($current)

              <div class="row">

                <div class="col-md-3">
                      <b><i style="color:red">Unsaved data : <span id="unsaved">0</span> record(s)</i></b>
                </div>

                <div class="col-md-3">
                  @if($criteria!=1)

                        <b><i style="color:red">Criteria : {{ $criteria }}</i></b>

                  @endif
                </div>

                <div class="col-md-3">

                  <label class="pull-right showrecord"><span id="recordlabel"> Showing {{sizeof($trackerview)}} of {{sizeof($trackerview)}} record(s)</span></label>
                </div>
                <div class="col-md-3">
                  @if($lastedit)
                    <p class="lastedit pull-right"><b><i>Last edited : {{$lastedit->Name}} [{{$lastedit->Updated_At}}]</i></b></p>
                  @else
                    <p class="lastedit pull-right"><b><i>Last edited : </i></b></p>
                  @endif

                </div>
              </div>

              <div  class="row">

                <div class="col-md-12">

                <div class='row sumheader'>

                    @foreach($arrsum as $c)
                      <div class="col-md-2">
                        {{$c}} : <span class='sumgroups' id="{{$c}}" name="{{$c}}">-</span>
                      </div>
                    @endforeach

                  </div>

                </div>

              </div>

                <div id="example1"></div>

                </div>

              @else

                <div class="row">

                  <div class="col-md-12">
                        <b><i style="color:red">Tracker Access Needed!</i></b>
                  </div>

                </div>

              @endif

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

  <script type="text/javascript" language="javascript" class="init">


      $(function() {

        // $("#zoomdiv").css("zoom",0.7);

        $('.select2').css('width', '100%');

        $(".select2").select2();

        $('#Due_Date').datepicker({
          autoclose: true,
            format: 'dd-M-yyyy',
        });

        $('.dateclass').datepicker({
          autoclose: true,
            format: 'dd-M-yyyy',
        });

        $( "#Contractordiv" ).hide();
        $( "#Staffdiv" ).show();

         $( "#sorttracker" ).sortable();
         $( "#sorttracker" ).disableSelection();

         $("#ajaxloader").hide();
         $("#ajaxloader2").hide();
         $("#ajaxloader3").hide();
         $("#ajaxloader5").hide();
         $("#ajaxloaderaddcolumn").hide();
         $("#ajaxloaderupdatecolumn").hide();

         $( "#ProjectId" ).change(function() {

           var projectid=$('#ProjectId').val();

           window.location.href ="{{ url("/handsontable") }}/"+projectid;


        });

        $( "#TemplateId" ).change(function() {

          var projectid=$('#ProjectId').val();
          var trackerid=$('#TemplateId').val();

           window.location.href ="{{ url("/handsontable") }}/"+projectid+"/"+trackerid;


       });

     });

      //  $('#agingdiv').slimScroll({
      //     height: '250px'
      // });

       var availableTags = [];
       var sitename = [];
       var siteid = [];

       @foreach ($allavailablecolumns as $column)

         availableTags.push("{{$column->Col}}");

       @endforeach

       @foreach ($trackerview as $item)

         @if(array_key_exists("Site_Name",$item))

           sitename.push("{{$item["Site_Name"]}}");

         @endif

       @endforeach

       @foreach ($trackerview as $item)

         @if(array_key_exists("Site_ID",$item))

           siteid.push("{{$item["Site_ID"]}}");

         @endif

       @endforeach



       $( ".fieldname" ).autocomplete({
         source: availableTags,
         appendTo: "#NewTracker"
       });

       $( ".fieldname2" ).autocomplete({
         source: availableTags,
         appendTo: "#AddColumn"
       });

       $( ".sitename2" ).autocomplete({
         source: sitename,
         appendTo: "#Assign"
       });

       $( ".sitename3" ).autocomplete({
         source: sitename,
         appendTo: "#ReportIssue"
       });

       $( ".siteid3" ).autocomplete({
         source: siteid,
         appendTo: "#ReportIssue"
       });



  </script>

<script data-jsfiddle="example1">
$('body').on('mousedown', '.changeType', function() {
   // set time out to wait the dropdown to appear
  setTimeout(function() {

  $(".htUIClearAll a")[0].click();

  }, 300);
});

  myVar = <?php echo json_encode($trackerview); ?>;

  var container = document.getElementById('example1');

  function firstRowRenderer(instance, td, row, col, prop, value, cellProperties) {
    Handsontable.renderers.TextRenderer.apply(this, arguments);
    td.style.fontWeight = 'bold';
    // td.style.color = 'green';
    td.style.background = 'yellow';
  }

  @if($trackerview)



  var header="<?php echo implode(",",array_keys($trackerview[0])); ?>";
  var colHeaders=header.split(",");

  var changes = null;
  var sourceFrom = null;
  // var changesArray = [];

  var Idarray = [];
  var Columnarray = [];
  var Originalarray = [];
  var Updatearray = [];
  var ProjectIdarray = [];

  function clickToSave() {
    var lastSave = false;
    // for (var i = 0, len = Idarray.length; i < len; i++) {
      // lastSave = (i == len - 1 ? true : false);
      saveChanges2(Idarray,Columnarray,Originalarray,Updatearray,ProjectIdarray, lastSave);
    // }
     // changesArray = [];
     Idarray = [];
     Columnarray = [];
     Originalarray = [];
     Updatearray = [];
     ProjectIdarray = [];
     hot.updateSettings({
      cells: function(row, col) {
        var cellProp = {};
        cellProp.className = ''
        return cellProp
      }
    })
     // window.location.reload();
  }

  function saveChanges(Idval,Columnval,Originalval,Updateval,ProjectIdval, lastSave) {

    if (Idarray) {

    } else {
      return;
    }

    $.ajaxSetup({
            headers: { 'X-CSRF-Token' : $('meta[name=_token]').attr('content') }
         });

         $.ajax({
             url: "{{ url('/tracker/updatetracker') }}",
             method: "POST",
             data: {
               Id:Idval,
               Column:Columnval,
               Original:Originalval,
               Update:Updateval,
               ProjectId:ProjectIdval,
               TrackerId:{{$trackerid}}
             },
             success: function(response){
               console.log(response)
               if (lastSave) {
                 var message ="Changes submitted!";
                 $("#update-alert ul").html(message);
                 $("#update-alert").modal("show");
                 lastSave = false;
               }
             }
         });

  }

  function saveChanges2(Idval,Columnval,Originalval,Updateval,ProjectIdval, lastSave) {

    if (Idarray) {

    } else {
      return;
    }

    $.ajaxSetup({
            headers: { 'X-CSRF-Token' : $('meta[name=_token]').attr('content') }
         });

         $.ajax({
             url: "{{ url('/tracker/updatetrackerbatch') }}",
             method: "POST",
             data: {
               Id:Idval,
               Column:Columnval,
               Original:Originalval,
               Update:Updateval,
               ProjectId:ProjectIdval,
               TrackerId:{{$trackerid}}
             },
             success: function(response){

               if (response==1) {
                 var message ="Changes submitted!";
                 $("#update-alert ul").html(message);
                 $("#update-alert").modal("show");
                 lastSave = false;

                 // changesArray = [];
                 Idarray = [];
                 Columnarray = [];
                 Originalarray = [];
                 Updatearray = [];
                 ProjectIdarray = [];

                 // window.location.reload();
               }
             }
         });

  }

  var hot = new Handsontable(container, {
    data: myVar,
    rowHeaders: true,
    colHeaders: true,
    // Eddie / Eric / Ayu Requested by Ravi 17/3
    @if($me->UserId == 645 || $me->UserId == 655 || $me->UserId == 684)  
      contextMenu:  ['copy', 'cut','hidden_columns_hide','freeze_column', 'unfreeze_column','remove_row'],
    @else
      contextMenu:  ['copy', 'hidden_columns_hide','freeze_column', 'unfreeze_column'],
    @endif
    manualColumnFreeze: true,
    manualColumnMove: true,
    dropdownMenu: true,
    filters:true,
    renderAllRows:false,
    formulas: true,
    height: 400,
    dropdownMenu: ['filter_by_condition','filter_operators','filter_by_condition2','filter_by_value', 'filter_action_bar'],
    fillHandle: {
      direction: 'vertical'
    },
    autoColumnSize: true,
    columnSorting: true,
    sortIndicator: true,
    allowInsertRow: false,
    colHeaders:colHeaders,
    autoWrapRow: false,
    manualRowResize: true,
    manualColumnResize: true,
    autoRowSize: false,
    hiddenColumns: {

        columns: [1,2],
      indicators: true
    },

      fixedColumnsLeft: 6,
    stretchH: 'all',
    columns: [
      {
        data: '-',
        title:'Action',
        renderer: customActionRenderer,
        width: '150px',
        readOnly: true,
        // 1nd column is simple text, no special options here
      },
      {
        data: 'Id'
        // 1nd column is simple text, no special options here
      },
      {
        data: 'ProjectId'
        // 1nd column is simple text, no special options here
      },
      {
        data: 'Project_Code',

        @if($me->Edit_Project_Code)
          readOnly: false,
        @else
          readOnly: true,
        @endif
        // 1nd column is simple text, no special options here
      },
      @if ($trackercolumns!="")

        @foreach ($trackercolumns as $column)

          {
            data: decodeHTMLEntities('{{$column->Column_Name}}'),

            @if($column->Column_Name=="Site_Name")
              //  width: '150px',
              @elseif($column->Column_Name=="Pending_Invoice")
                type: 'numeric',
                format: '0',
                language: 'en-US',
                @if($projectid==146)
                  readOnly: true,
                @endif

              @elseif($column->Column_Name=="Latest_Invoice_Status")
                type: 'numeric',
                format: '0',
                language: 'en-US',
                @if($projectid==146)
                  readOnly: true,
                @endif

                @elseif($column->Column_Name=="INVOICE Amount")
                type: 'numeric',
                format: '0,0.00',
                language: 'en-US',

                @if($projectid==146)
                  readOnly: true,
                @endif

                @elseif($column->Column_Name=="PO Amount")
                type: 'numeric',
                format: '0,0.00',
                language: 'en-US',

                  @if($projectid==146)
                    readOnly: true,
                  @endif

                @elseif($column->Column_Name=="JDNI")
                type: 'numeric',
                format: '0,0.00',
                language: 'en-US',
                @if($projectid==146)
                  readOnly: true,
                @endif

              @elseif($column->Column_Name=="MR_Budget")
                type: 'numeric',
                format: '0,0.00',
                language: 'en-US',
                readOnly: true,

              @elseif($column->Column_Name=="First_MR_Budget")
                type: 'numeric',
                format: '0,0.00',
                language: 'en-US',
                readOnly: true,

              @elseif($column->Column_Name=="Manday")
                type: 'numeric',
                format: '0,0.00',
                language: 'en-US',
                readOnly: true,

              @elseif($column->Column_Name=="Incentive")
                type: 'numeric',
                format: '0,0.00',
                language: 'en-US',
                readOnly: true,

              @elseif($column->Column_Name=="E-wallet")
                type: 'numeric',
                format: '0,0.00',
                language: 'en-US',
                readOnly: true,

              @elseif($column->Column_Name=="Profit")
                type: 'numeric',
                format: '0,0.00',
                language: 'en-US',
                readOnly: true,
              @elseif($column->Column_Name=="%_Profit")
                readOnly: true,
                className:"htRight",
            @endif

            @if($column->Type=="Read Only")
              readOnly: true,
            @elseif($column->Type=="Textbox")
            @elseif($column->Type=="Textarea")
            @elseif($column->Type=="Date")
              type: 'date',
              renderer: customDateRenderer,
              dateFormat: 'DD-MMM-YYYY',
              correctFormat: true,
              datePickerConfig: {
                // First day of the week (0: Sunday, 1: Monday, etc)
                firstDay: 0,
                showWeekNumber: true,
                numberOfMonths: 3
                // disableDayFn: function(date) {
                //   // Disable Sunday and Saturday
                //   return date.getDay() === 0 || date.getDay() === 6;
                // }
              }
            @elseif($column->Type=="Dropdown")
            type: 'autocomplete',
            strict: true,
            /* type: 'dropdown',
            "allowInvalid": false, */
              source: [

                @if($column->Column_Name=="PO1_Type" || $column->Column_Name=="PO2_Type" || $column->Column_Name=="PO3_Type"|| $column->Column_Name=="PO4_Type"|| $column->Column_Name=="PO5_Type"|| $column->Column_Name=="PO6_Type"|| $column->Column_Name=="PO7_Type"|| $column->Column_Name=="PO8_Type")

                    '',
                  @foreach($options as $option)
                    @if ($option->Field=="PO1_Type")

                      "{{$option->Option}}".replace("&amp;", '&'),
                    @endif
                  @endforeach

                @elseif($column->Column_Name=="PO1_Status" || $column->Column_Name=="PO2_Status" || $column->Column_Name=="PO3_Status"|| $column->Column_Name=="PO4_Status"|| $column->Column_Name=="PO5_Status"|| $column->Column_Name=="PO6_Status"|| $column->Column_Name=="PO7_Status"|| $column->Column_Name=="PO8_Status")

                    '',
                  @foreach($options as $option)
                    @if ($option->Field=="PO1_Status")
                      "{{$option->Option}}".replace("&amp;", '&'),
                    @endif
                  @endforeach

                @else
                      '',
                    @foreach($options as $option)
                      @if (strtoupper($option->Field)==strtoupper($column->Column_Name))
                        "{{$option->Option}}".replace("&amp;", '&'),
                      @endif
                    @endforeach

                @endif

              ]

            @elseif($column->Type=="Staff List")
              type: 'autocomplete',
              strict: false,
              source: [

                @foreach($staff as $user)
                    '{{$user->Name}}',
                @endforeach

              ]

              @elseif($column->Type=="Contractor List")
                type: 'autocomplete',
                strict: false,
                source: [

                  @foreach($contractor as $user)
                      '{{$user->Name}}',
                  @endforeach

                ]

            @elseif($column->Type=="PO Received")

            @elseif($column->Type=="PO Issued")


            @elseif($column->Type=="Invoice Received")

            @elseif($column->Type=="Invoice Issued")


            @elseif($column->Type=="Currency")
              type: 'numeric',
              format: '0,0.00',
              language: 'en-US'

            @endif
            // 1nd column is simple text, no special options here
          },

        @endforeach

      @endif

      @if($additionalcolumns)
        {
            data: 'Total PO',
            readOnly: true,
        },
        {
          data: 'Total Claim',
          readOnly: true,
        },
        {
          data: 'Total Unclaim',
          readOnly: true,
        }
      @endif


    ],
    // modifyColWidth: function(width, col){
    //   if(width > 150){
    //     return 150
    //   }
    // },

    beforeAutofill: function (start,end, data) {

      s=start.row;
      e=end.row;
      col=start.col;
      ids="";

      for (var i = s; i < e+1; i++) {

        d=hot.getDataAtRow(i);
        ids=ids+d[1]+",";

      }
      column=hot.getColHeader(col);

      update=data[0][0];


    },
    beforeRemoveRow:function (index, amount,visualRows) {

      $("div#divLoading").show();

      var remove=String(visualRows).split(",");
      var ids="";

      for (var i = index; i < index+amount; i++) {

        data=hot.getDataAtRow(i);
        ids=ids+data[1]+",";

      }

      $.ajaxSetup({
         headers: { 'X-CSRF-Token' : $('meta[name=_token]').attr('content') }
      });

      $.ajax({
                  url: "{{ url('/tracker/removeitem') }}",
                  method: "POST",
                  data: {
                    Ids:ids,
                    ProjectId:{{$projectid}}
                  },
                  success: function(response){


                    setTimeout(function() {
                        $("div#divLoading").hide();

                    }, 100);

          }
      });

    },
    afterGetColHeader: function(col, TH) {
    var TR = TH.parentNode;

    if(TR)
    {

      var THEAD = TR.parentNode;
      var colname="";

      function applyClass(elem, className) {
        if (!Handsontable.dom.hasClass(elem, className)) {
          Handsontable.dom.addClass(elem, className);
        }
      }

      colname=this.getColHeader(col);

      @foreach($colorcodes as $code)
        @if($code->Color_Code!="FFFFFF")
          if(colname=="{{$code->Column_Name}}")
          {
            applyClass(TH, "c{{$code->Color_Code}}");

          }
        @endif
      @endforeach

      //
      // if(colname.startsWith("PO1"))
      // {
      //   applyClass(TH, 'po1');
      // }
      // else if(colname.startsWith("PO2"))
      // {
      //   applyClass(TH, 'po2');
      // }
      // else if(colname.startsWith("PO3"))
      // {
      //   applyClass(TH, 'po3');
      // }
      // else if(colname.startsWith("PO4"))
      // {
      //   applyClass(TH, 'po4');
      // }
      // else if(colname.startsWith("PO5"))
      // {
      //   applyClass(TH, 'po5');
      // }
      // else if(colname.startsWith("PO6"))
      // {
      //   applyClass(TH, 'po1');
      // }
      // else if(colname.startsWith("PO7"))
      // {
      //   applyClass(TH, 'po2');
      // }
      // else if(colname.startsWith("PO8"))
      // {
      //   applyClass(TH, 'po3');
      // }

    }

  },
  afterOnCellMouseDown: function(event,coords,TD)
{

  if(clickedrow==coords.row && clickedcol==coords.col && coords.row>-1 && coords.col>1)
  {
    var all=hot.getData();
    var header=hot.getColHeader();
    var id=header.indexOf("Id");
    var projectid=header.indexOf("ProjectId");
    var col=hot.getColHeader(coords.col);

    if(col=="MR_Budget")
    {
      var trackerid=hot.getDataAtCell(coords.row,id);
      var pid=hot.getDataAtCell(coords.row,projectid);
      window.open("{{ url('/material/MR') }}/"+pid+"/"+trackerid, '_blank');
    }
    if(col == "Manday"){
      var trackerid=hot.getDataAtCell(coords.row,id);
      window.open("{{ url('/tracker/mandayDetails') }}/"+trackerid, '_blank');
    }
    if(col=="Pending_Invoice")
    {
      var trackerid=hot.getDataAtCell(coords.row,id);
      window.open("{{ url('/salesorderhistory') }}/"+trackerid, '_blank');
    }
    else if(col=="E-wallet")
    {
      var trackerid=hot.getDataAtCell(coords.row,id);
      window.open("{{ url('/siteewalletrecord') }}/"+trackerid, '_blank');
    }
    else if(col=="Costing")
    {
      var pid=hot.getDataAtCell(coords.row,projectid);
      window.open("{{ url('/costing') }}/"+pid, '_blank');
    }
    else if(col=="Incentive")
    {
      alert("");
    }
    else if(new Date(hot.getDataAtCell(coords.row,coords.col)) != 'Invalid Date')
    {
      var trackerid=hot.getDataAtCell(coords.row,id);
      var pid=hot.getDataAtCell(coords.row,projectid);
      col=col.replace("/","|||");
      col=col.replace("/","|||")
      window.open("{{ url('/opendocument') }}/"+pid+"/"+trackerid+"/"+col, '_blank');
    }
  }
  else {
    clickedrow=coords.row;
    clickedcol=coords.col;
  }
  // var data = this.getDataAtRow(coords.row);
  // if(coords.row>-1)
  // {
  //   $("#contenttext").val(data[coords.col]);
  // }
  // else {
  //     var count=0;
  //     var sum=0;
  //     var all=hot.getData();
  //      for (var i = 0; i < all.length; i++) {
  //        if(all[i][coords.col].length>0)
  //        {
  //          count=count+1;
  //        }
  //        if(!isNaN(parseFloat(all[i][coords.col].replace( /[^\d\.]*/g, ''))))
  //        {
  //          sum=sum+parseFloat(all[i][coords.col].replace( /[^\d\.]*/g, ''));
  //        }
  //      }
  //      $("#counttext").val(count);
  //      $("#sumtext").val(parseFloat(sum.toFixed(2)).toLocaleString("en"));
  // }
},
  afterSelection: function(r,c){
    var data = this.getDataAtRow(r);
    $("#contenttext").val(data[c]);
    // console.log(this.getCell(r,c));
  },
  afterFilter: function(){

    $("#recordlabel").html("Showing "+ hot.getData().length +" of {{sizeof($trackerview)}} record(s)");
    calculatesum();
  },
  afterChange: function (change, source) {

      if (source === 'loadData') {
      return; //don't save this change
    }

    if (source === 'edit') {
      var col = hot.propToCol(change[0][1]);
      if(change[0][3] != change[0][2])
      {
        var cellProperties = this.getCellMeta(change[0][0], col);
        cellProperties.isModified = true;

        hot.setCellMeta(change[0][0], col, 'className', 'red');
        hot.render();
      }
    }

    if (source === 'CopyPaste.paste') {
      for (var i = 0; i < change.length; i++) {

          //Do something
          // data=hot.getDataAtRow(String(change[i]).split(",")[0]);
          //
          // id=data[1];

          var data=hot.getDataAtRow(change[i][0]);

          for (var j = 0; j < data.length; j++) {
            if(hot.getColHeader(j)=="Id")
            {
              break;
            }
          }

          var id=data[j];

          var projectid=data[2];

          column=change[i][1];
          original=change[i][2];
          update=change[i][3];

          Idarray.push(id);
          Columnarray.push(column);
          Originalarray.push(original);
          Updatearray.push(update);
          ProjectIdarray.push(projectid);
          $("span[id='unsaved']").html(Idarray.length);

          promptunsaved();

      }


    } else  {
          for (var i = 0; i < change.length; i++) {

            //Do something
            // data=hot.getDataAtRow(String(change[i]).split(",")[0]);
            //
            // id=data[1];

            data=hot.getDataAtRow(change[i][0]);

            for (var j = 0; j < data.length; j++) {
              if(hot.getColHeader(j)=="Id")
              {
                break;
              }
            }

            id=data[j];

            projectid=data[2];

            column=change[i][1];
            original=change[i][2];
            update=change[i][3];

            Idarray.push(id);
            Columnarray.push(column);
            Originalarray.push(original);
            Updatearray.push(update);
            ProjectIdarray.push(projectid);
            $("span[id='unsaved']").html(Idarray.length);

            promptunsaved();

        }
    }

    // changesArray.push({source: source, change: change});

  },
  });

  @endif

  var buttons = {
    file: document.getElementById('export-file')
  };

  Handsontable.hooks.add('modifyColWidth', function(width) {
  if (this === hot.getPlugin('dropdownMenu').menu.hotMenu) {
    return 200;
  }

  return width;
})

@if($trackerview)
   var exportPlugin = hot.getPlugin('exportFile');
@endif

   buttons.file.addEventListener('click', function() {
    exportPlugin.downloadFile('csv',
        {filename: $("#ProjectId option:selected").text() + ' {{ date('dMY') }}',
      exportHiddenRows: false, // default false
      exportHiddenColumns: true, // default false
      columnHeaders: true, // default false
      rowHeaders: false, // default false
      columnDelimiter: ','
    });
  });

  function clearfiltering()
  {
    hot.getPlugin('Filters').clearConditions();
    // console.log(hot.getPlugin('Filters'));
    hot.getPlugin('Filters').filter();

  }

  function unhide()
  {

    data=hot.getDataAtRow(0);

    for (var i = 3; i < data.length; i++) {

      if(hot.getPlugin('HiddenColumns').isHidden(i,false))
      {
        hot.getPlugin('HiddenColumns').showColumn(i);
      }

    }

    hot.render();

  }

  function zoomin()
  {

    if($("#zoomdiv").css("zoom")>0.6)
    {
      $("#zoomdiv").css("zoom", parseFloat($("#zoomdiv").css("zoom"))-0.1);
    }

    hot.render();

  }

  function zoomout()
  {

    if($("#zoomdiv").css("zoom")<1.2)
    {
      $("#zoomdiv").css("zoom", parseFloat($("#zoomdiv").css("zoom"))+0.1);
    }

    hot.render();

  }

  function resetsearch(){
    hot.loadData(myVar);
    calculatesum();
  }

  function filtertext() {

    var search=$("#searchtext").val();

    var
    row, r_len,
    data = myVar, // Keeping the integretity of the original data
    array = [];

    if(search!="")
    {

      for (row = 0, r_len = data.length; row < r_len; row++) {

        for (let k in data[row]) {
          if ((""+data[row][k]).toLowerCase().includes(search.toLowerCase())) {

            if(!array.includes(data[row]))
            {
              array.push(data[row]);
            }


          }
        }
      }

      if(array.length==0)
      {
        var message ="No record found!";
        $("#warning-alert ul").html(message);
        $("#warning-alert").modal("show");
      }
      else {
        hot.loadData(array);
      }

    }

    calculatesum();

}

  function unfreeze()
  {

    hot.getPlugin('ManualColumnFreeze').unfreezeColumn(0);
    hot.render();
    hot.getPlugin('ManualColumnFreeze').unfreezeColumn(0);
    hot.render();
    hot.getPlugin('ManualColumnFreeze').unfreezeColumn(0);
    hot.render();
    hot.getPlugin('ManualColumnFreeze').unfreezeColumn(0);
    hot.render();
    hot.getPlugin('ManualColumnFreeze').unfreezeColumn(0);
    hot.render();
    hot.getPlugin('ManualColumnFreeze').unfreezeColumn(0);
    hot.render();
    hot.getPlugin('ManualColumnFreeze').unfreezeColumn(0);
    hot.render();
    hot.getPlugin('ManualColumnFreeze').unfreezeColumn(0);
    hot.render();
    hot.getPlugin('ManualColumnFreeze').unfreezeColumn(0);
    hot.render();
    hot.getPlugin('ManualColumnFreeze').unfreezeColumn(0);
    hot.render();

  }

  function customPORenderer(instance, td, row, col, prop, value, cellProperties)
  {

      value="<a href='{{ url('/PO2') }}/"+value+"' target='_blank'>"+value+"</a>";
      value = strip_tags(value, '<em><b><strong><a><big>');
      value = Handsontable.helper.stringify(value);

      td.innerHTML=value;
      // Handsontable.TextCell.renderer.apply(this, arguments);
      // Handsontable.renderers.TextRenderer.apply(this, arguments);

      return td;
      // you can use the selectedId for posting to the DB or server
  }

  // function customActionRenderer(instance, td, row, col, prop, value, cellProperties)
  // {
  //
  //
  //
  //   var data=instance.getDataAtRow(row);
  //   var header=instance.getColHeader();
  //
  //   var Id=header.indexOf("Id");
  //   var Site_ID=header.indexOf("Site_ID");
  //
  //     // value="<a href='#' onclick='showsubmitdocumentmodal("+data[Id]+",\""+data[Site_ID]+"\");'>Submit</a>";
  //
  //     @if($me->User_Type=="External")
  //       value="-";
  //
  //     @else
  //     if(data[0])
  //     {
  //       value="<center><a href='#' id='submit"+data[Id]+"' onclick='showsubmitdocumentmodal("+data[Id]+",\""+data[Site_ID]+"\");'>Done</a>"
  //     }
  //     else {
  //       value="<center><a href='#' id='submit"+data[Id]+"' onclick='showsubmitdocumentmodal("+data[Id]+",\""+data[Site_ID]+"\");'>-</a>"
  //     }
  //
  //     @endif
  //
  //
  //     value=value+"</center>";
  //     // value = strip_tags(value, '<em><b><strong><a><big>');
  //     value = Handsontable.helper.stringify(value);
  //
  //     td.innerHTML=value;
  //     // Handsontable.TextCell.renderer.apply(this, arguments);
  //     // Handsontable.renderers.TextRenderer.apply(this, arguments);
  //
  //     return td;
  //     // you can use the selectedId for posting to the DB or server
  // }

  function customActionRenderer(instance, td, row, col, prop, value, cellProperties)
  {

    var data=instance.getDataAtRow(row);
    var header=instance.getColHeader();

    var Id=header.indexOf("Id");
    var Site_ID=header.indexOf("Site_ID");

      // value="<a href='#' onclick='showsubmitdocumentmodal("+data[Id]+",\""+data[Site_ID]+"\");'>Submit</a>";

      @if($me->User_Type=="External")
        value="-";

      @else
      if(data[0]>0)
      {
        // document.write(Id);
        value="<a href='{{ URL::to('/tracker/filecategory2')}}/{{$projectid}}"+"/"+ data[Id] +"' target='_blank'>" + data[0] + " File(s)</a> &nbsp;"
        +  " | <a href='{{url('material/MR')}}/{{$projectid}}/"+ data[Id] + "' target='_blank'>MR</a> | <a href='{{url('/salesorderhistory')}}/"+data[Id]+"' target='_blank'>SO</a> "; //FINAL

        @if($trackerwriteaccess)
          // value+=" | <a href='#' onclick='openupdatemodal("+JSON.stringify(data)+","+JSON.stringify(header)+");'><img class='buttonimg' src='{{ asset('/img/edit.png') }}' alt='Update Purchase Invoice'></a>";
        @endif

        //value="<center><a href='#' id='submit"+data[Id]+"' onclick='showsubmitdocumentmodal("+data[Id]+",\""+data[Site_ID]+"\");'>Done</a>" //original
        // value="<center><a href='{{ URL::to("/")}}/tracker/getdocumentlist/"+ data[Id] +"/"+ data[Site_ID] +"' 'target='_blank'>Done</a>"
      }
      else {
        // value="<input type='text' value="+ data[Id]+">";
        value="<a href='{{ URL::to('/tracker/filecategory2')}}/{{$projectid}}"+"/"+ data[Id] +"' target='_blank'>0 File(s)</a> &nbsp;" +
        " | <a href='{{url('material/MR')}}/{{$projectid}}/"+ data[Id] + "' target='_blank'>MR</a> | <a href='{{url('/salesorderhistory')}}/"+data[Id]+"' target='_blank'>SO</a>"; //FINAL
        @if($trackerwriteaccess)
          // value+=" | <a href='#' onclick='openupdatemodal("+JSON.stringify(data)+","+JSON.stringify(header)+");'><img class='buttonimg' src='{{ asset('/img/edit.png') }}' alt='Update Purchase Invoice'></a>";
        @endif

        //value="<center><a href='#' id='submit"+data[Id]+"' onclick='showsubmitdocumentmodal("+data[Id]+",\""+data[Site_ID]+"\");'>-</a>" //original
        // value="<center><a href='#' id='submit"+data[Id]+"' target='_blank' onclick='showsubmitdocumentmodal("+data[Id]+",\""+data[Site_ID]+"\");'>-</a>"
      }


      @endif

      value=value+" | <a href='#' id='submit"+data[Id]+"' onclick='showfibreupdate("+data[Id]+");'>Fibre</a>"
      value=value+" | <a href='#' id='submit"+data[Id]+"' onclick='showlog("+data[Id]+");'>Diary</a>"

      value=value+"</center>";
      // value = strip_tags(value, '<em><b><strong><a><big>');
      value = Handsontable.helper.stringify(value);

      td.innerHTML=value;
      // Handsontable.TextCell.renderer.apply(this, arguments);
      // Handsontable.renderers.TextRenderer.apply(this, arguments);

      return td;
      // you can use the selectedId for posting to the DB or server
  }

  function customDateRenderer(instance, td, row, col, prop, value, cellProperties)
  {
      Handsontable.renderers.TextRenderer.apply(this,arguments);

      if(String(prop).toUpperCase().includes("ACTUAL"))
      {

        var data=instance.getDataAtRow(row);
        var header=instance.getColHeader();
        var plancol=String(prop).replace("Actual","Plan");
        plancol=String(plancol).replace("ACTUAL","PLAN");

        for (var i = 0; i < header.length; i++) {
          if (String(header[i]).toUpperCase()==String(plancol).toUpperCase())
          {
            var plandate=data[i];

            var d1 = Date.parse(plandate);
            var d2 = Date.parse(value);
            var d3 = Date.parse("{{date("d-M-Y")}}");


            if(d2>d1)
            {
              td.style.background = '#F1654C';
            }
            else if(d2<=d1){
              td.style.background = '#97CE68';
            }
            else if(d3>d1)
            {
              td.style.background = '#F1654C';
            }

            break;

          }
        }

      }
      else if(String(prop).toUpperCase().includes("PLAN"))
      {

        var data=instance.getDataAtRow(row);
        var header=instance.getColHeader();
        var actualcol=String(prop).replace("Plan","Actual");
        actualcol=String(actualcol).replace("PLAN","ACTUAL");

        for (var i = 0; i < header.length; i++) {
          if (String(header[i]).toUpperCase()==String(actualcol).toUpperCase())
          {

            var actualdate=data[i];

            var d1 = Date.parse(value);
            var d2 = Date.parse(actualdate);
            var d3 = Date.parse("{{date("d-M-Y")}}");

            if(d2>d1)
            {

              td.style.background = '#F1654C';
            }
            else if(d2<=d1){

              td.style.background = '#97CE68';
            }
            else if(d3>d1)
            {
              td.style.background = '#F1654C';
            }

            break;

          }
        }

      }

      td.innerHTML=value;
      //data=hot.getDataAtRow(2);

      //console.log(instance.getColHeader())
      //console.log(instance.getDataAtRow(row));

      return td;

  }

  function customTotalRenderer(instance, td, row, col, prop, value, cellProperties)
  {

    var data=instance.getDataAtRow(row);
    var header=instance.getColHeader();
    var index=row;
    var total=0;
    var po1,po2,po3,po4,po5;

    if(data[header.indexOf("PO1_Amount")])
    {
      total=total+parseFloat(String(data[header.indexOf("PO1_Amount")]).replace(',', '').replace('(', '-').replace(')', ''));
    }

    if(data[header.indexOf("PO2_Amount")])
    {
      total=total+parseFloat(String(data[header.indexOf("PO2_Amount")]).replace(',', '').replace('(', '-').replace(')', ''));
    }

    if(data[header.indexOf("PO3_Amount")])
    {
      total=total+parseFloat(String(data[header.indexOf("PO3_Amount")]).replace(',', '').replace('(', '-').replace(')', ''));
    }

    if(data[header.indexOf("PO4_Amount")])
    {
      total=total+parseFloat(String(data[header.indexOf("PO4_Amount")]).replace(',', '').replace('(', '-').replace(')', ''));
    }

    if(data[header.indexOf("PO5_Amount")])
    {
      total=total+parseFloat(String(data[header.indexOf("PO5_Amount")]).replace(',', '').replace('(', '-').replace(')', ''));
    }

    if(data[header.indexOf("PO6_Amount")])
    {
      total=total+parseFloat(String(data[header.indexOf("PO6_Amount")]).replace(',', '').replace('(', '-').replace(')', ''));
    }

    if(data[header.indexOf("PO7_Amount")])
    {
      total=total+parseFloat(String(data[header.indexOf("PO7_Amount")]).replace(',', '').replace('(', '-').replace(')', ''));
    }

    if(data[header.indexOf("PO8_Amount")])
    {
      total=total+parseFloat(String(data[header.indexOf("PO8_Amount")]).replace(',', '').replace('(', '-').replace(')', ''));
    }

    index=index+1;

    var data1=instance.getDataAtRow(index);

    while (data[header.indexOf("Project_Code")]==data1[header.indexOf("Project_Code")]) {

      if(data1[header.indexOf("PO1_Amount")])
      {
        total=total+parseFloat(String(data1[header.indexOf("PO1_Amount")]).replace(',', '').replace('(', '-').replace(')', ''));
      }

      if(data1[header.indexOf("PO2_Amount")])
      {
        total=total+parseFloat(String(data1[header.indexOf("PO2_Amount")]).replace(',', '').replace('(', '-').replace(')', ''));
      }

      if(data1[header.indexOf("PO3_Amount")])
      {
        total=total+parseFloat(String(data1[header.indexOf("PO3_Amount")]).replace(',', '').replace('(', '-').replace(')', ''));
      }

      if(data1[header.indexOf("PO4_Amount")])
      {
        total=total+parseFloat(String(data1[header.indexOf("PO4_Amount")]).replace(',', '').replace('(', '-').replace(')', ''));
      }

      if(data1[header.indexOf("PO5_Amount")])
      {
        total=total+parseFloat(String(data1[header.indexOf("PO5_Amount")]).replace(',', '').replace('(', '-').replace(')', ''));
      }

      if(data1[header.indexOf("PO6_Amount")])
      {
        total=total+parseFloat(String(data1[header.indexOf("PO6_Amount")]).replace(',', '').replace('(', '-').replace(')', ''));
      }

      if(data1[header.indexOf("PO7_Amount")])
      {
        total=total+parseFloat(String(data1[header.indexOf("PO7_Amount")]).replace(',', '').replace('(', '-').replace(')', ''));
      }

      if(data1[header.indexOf("PO8_Amount")])
      {
        total=total+parseFloat(String(data1[header.indexOf("PO8_Amount")]).replace(',', '').replace('(', '-').replace(')', ''));
      }

      index=index+1;
      data1=instance.getDataAtRow(index);

    }

      td.innerHTML=total.toFixed(2).toLocaleString("en");

      return td;

  }

  function customProfitmarginRenderer(instance, td, row, col, prop, value, cellProperties)
  {

    var data=instance.getDataAtRow(row);
    var header=instance.getColHeader();
    var margin=0;

    margin=parseFloat(data[header.indexOf("%_Profit")]);

      td.innerHTML=margin.toFixed(2).toLocaleString("en")+"%";

      return td;

  }

  function customProfitRenderer(instance, td, row, col, prop, value, cellProperties)
  {

    var data=instance.getDataAtRow(row);
    var header=instance.getColHeader();
    var index=row;
    var total=0;
    var profit=0;
    var poamount=0;
    var totaltosubcon=0;
    var po1,po2,po3,po4,po5;

    if(data[header.indexOf("PO_Amount")])
    {
      poamount=parseFloat(String(data[header.indexOf("PO_Amount")]).replace(',', '').replace('(', '-').replace(')', ''));
    }

    if(data[header.indexOf("Total")])
    {
      totaltosubcon=parseFloat(String(data[header.indexOf("Total")]).replace(',', '').replace('(', '-').replace(')', ''));
    }

    index=index+1;

    var data1=instance.getDataAtRow(index);

    while (data[header.indexOf("Project_Code")]==data1[header.indexOf("Project_Code")]) {

      if(data1[header.indexOf("PO1_Amount")])
      {
        poamount=poamount+parseFloat(String(data1[header.indexOf("PO_Amount")]).replace(',', '').replace('(', '-').replace(')', ''));
      }

      if(data[header.indexOf("Total")])
      {
        totaltosubcon=totaltosubcon+parseFloat(String(data1[header.indexOf("Total")]).replace(',', '').replace('(', '-').replace(')', ''));
      }

      index=index+1;
      data1=instance.getDataAtRow(index);

    }

    profit=poamount-totaltosubcon;

      td.innerHTML=profit.toFixed(2).toLocaleString("en");

      return td;

  }

  function showsubmitdocumentmodal(TrackerId,SiteId)
  {
    // alert(SiteId);
    $.ajaxSetup({
       headers: { 'X-CSRF-Token' : $('meta[name=_token]').attr('content') }
    });
    $.ajax({
                url: "{{ url('/tracker/getdocumentlist') }}",
                method: "POST",
                data: {
                  TrackerId:TrackerId,
                  SiteId:SiteId
                },
                success: function(response){
                  if (response)
                  {
                      var myObject = JSON.parse(response);
                      var display='';
                      var index=1;
                      display+='<table border="1" align="center" class="assettable" cellspacing="0" width="100%" padding="30px" style="font-size: 13px;">';
                      display+='<tr class="tableheader"><td>Document Type</td><td>Submission Date</td><td>Submitted By</td><td>Download</td><td>Upload</td></tr>';
                      $.each(myObject, function(i,item){
                          display+="<tr>";
                          if(item.Option==="Site Photos" || item.Option==="Panaromic Photos" ){
                              display+='<td>'+item.Option+'</td>';
                              display+='<td></td><td></td>';
                              if(item.Download)
                              {
                                display+='<td><a onclick="viewphoto('+TrackerId+',\''+item.Option+'\')">View Photo</a></td>';
                              }
                              else
                              {
                                display+='<td></td>';
                              }
                          }
                          else{
                            display+='<td>'+item.Option+'</td><td>'+String(item.Submission_Date).replace('null','')+'</td><td>'+String(item.Submitted_By).replace('null','')+'</td>';
                            if(item.Download)
                            {
                              display+='<td><a href="{{ url('') }}'+item.Download+'" target="_blank">Download</a></td>';
                            }
                            else {
                              display+='<td></td>';
                            }
                          }
                          display+='<td>';
                            display+='<form enctype="multipart/form-data" id="upload_form_'+i+'" role="form" method="POST" action="" >';
                            display+='<input type="hidden" class="form-control" id="UserId" name="UserId" value="{{$me->UserId}}">';
                            display+='<input type="hidden" class="form-control" id="ProjectId" name="ProjectId" value="{{$projectid}}">';
                            display+='<input type="hidden" class="form-control" id="DocumentType" name="DocumentType" value="'+item.Option+'">';
                            display+='<input type="hidden" class="form-control" id="selectedtrackerid" name="selectedtrackerid" value="'+TrackerId+'">';
                            display+='<input type="hidden" class="form-control" id="SiteId" name="SiteId" value="'+SiteId+'">';
                            display+='<input type="hidden" class="form-control" id="submitdate" name="submitdate">';
                            if(item.Option==="Site Photos" || item.Option==="Panaromic Photos" ){
                              display+='<input type="file" id="document" name="document[]" accept=".png,.jpg,.jpeg" multiple><br><button type="button" class="btn btn-primary btn-sm" onclick="submitdocument2('+i+')" >Upload</button><button type="button" class="btn btn-danger btn-sm" onclick="Delete('+i+','+item.Id+')" >Delete</button>';
                            }
                            else{
                              display+='<input type="file" id="document" name="document[]" accept=".doc,.docx,.pdf"><br><button type="button" class="btn btn-primary btn-sm" onclick="submitdocument2('+i+')">Upload</button><button type="button" class="btn btn-danger btn-sm" onclick="Delete('+i+','+item.Id+')">Delete</button>';
                            }
                          display+='</form></td>';
                          display+="</tr>";
                      });
                      $("#documentcontent").html(display);
                  }
        }
    });
    $('#selectedtrackerid').val(TrackerId);
    $('#SiteId').val(SiteId);
    $('#SubmitDocument2').modal('show');
  }

  function showfibreupdate(TrackerId)
  {
    $('#FibreId').val(TrackerId);
    $('#FibreUpdate').modal('show');
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


                    }
                    else {
                      var message ="Document submitted!";
                      $("#update-alert ul").html(message);
                      $("#update-alert").modal("show");


                      // setTimeout(function() {
                      //   $("#update-alert").fadeOut();
                      window.location.reload();
                      // }, 3000);
                      //$("#Template").val(response).change();

                      $("#exist-alert").hide();

                      $('#SubmitDocument').modal('hide')

                    }
          }
      });
  }

  function submitdocument2(index) {
    // alert(index);
      $.ajaxSetup({
         headers: { 'X-CSRF-Token' : $('meta[name=_token]').attr('content') }
      });
      $.ajax({
                  url: "{{ url('/tracker/submitdocument') }}",
                  method: "POST",
                  contentType: false,
                  processData: false,
                  data:new FormData($("#upload_form_"+index)[0]),
                  success: function(response){
                    if (response==0)
                    {
                      var message ="Failed to submit document!";
                      $("#warning-alert ul").html(message);
                      $("#warning-alert").modal("show");
                    }
                    else {
                      TrackerId=document.getElementById("upload_form_"+index).elements.namedItem("selectedtrackerid").value;
                      SiteId=document.getElementById("upload_form_"+index).elements.namedItem("SiteId").value;
                      DocumentType=document.getElementById("upload_form_"+index).elements.namedItem("DocumentType").value;
                      $.ajaxSetup({
                         headers: { 'X-CSRF-Token' : $('meta[name=_token]').attr('content') }
                      });
                      $.ajax({
                                  url: "{{ url('/tracker/getdocumentlist') }}",
                                  method: "POST",
                                  data: {
                                    TrackerId:TrackerId,
                                    SiteId:SiteId
                                  },
                                  success: function(response){
                                    if (response)
                                    {
                                      var myObject = JSON.parse(response);
                                      var display='';
                                      var index=1;

                                      if(DocumentType=="PO")
                                      {
                                        $("button#submit"+TrackerId).toggleClass('btn-primary btn-success');
                                        $("button#submit"+TrackerId).text("PO Attached");

                                      }

                                      display+='<table border="1" align="center" class="assettable" cellspacing="0" width="100%" padding="30px" style="font-size: 13px;">';
                                      display+='<tr class="tableheader"><td>Document Type</td><td>Submission Date</td><td>Submitted By</td><td>Download</td><td>Upload</td></tr>';
                                      $.each(myObject, function(i,item){
                                          display+="<tr>";
                                          if(item.Option==="Site Photos" || item.Option==="Panaromic Photos" ){
                                              display+='<td>'+item.Option+'</td>';
                                              display+='<td></td><td></td>';
                                              if(item.Download)
                                              {
                                                display+='<td><a onclick="viewphoto('+TrackerId+',\''+item.Option+'\')">View Photo</a></td>';
                                              }
                                              else
                                              {
                                                display+='<td></td>';
                                              }
                                          }
                                          else{
                                            display+='<td>'+item.Option+'</td><td>'+String(item.Submission_Date).replace('null','')+'</td><td>'+String(item.Submitted_By).replace('null','')+'</td>';
                                            if(item.Download)
                                            {
                                              display+='<td><a href="{{ url('') }}'+item.Download+'" target="_blank">Download</a></td>';
                                            }
                                            else {
                                              display+='<td></td>';
                                            }

                                          }
                                          display+='<td>';
                                            display+='<form enctype="multipart/form-data" id="upload_form_'+i+'" role="form" method="POST" action="" >';
                                            display+='<input type="hidden" class="form-control" id="UserId" name="UserId" value="{{$me->UserId}}">';
                                            display+='<input type="hidden" class="form-control" id="ProjectId" name="ProjectId" value="{{$projectid}}">';
                                            display+='<input type="hidden" class="form-control" id="DocumentType" name="DocumentType" value="'+item.Option+'">';
                                            display+='<input type="hidden" class="form-control" id="selectedtrackerid" name="selectedtrackerid" value="'+TrackerId+'">';
                                            display+='<input type="hidden" class="form-control" id="SiteId" name="SiteId" value="'+SiteId+'">';
                                            display+='<input type="hidden" class="form-control" id="submitdate" name="submitdate">';
                                           if(item.Option==="Site Photos" || item.Option==="Panaromic Photos" ){
                                                display+='<input type="file" id="document" name="document"  accept=".png,.jpg,.jpeg" multiple><br><button type="button" class="btn btn-primary btn-sm" onclick="submitdocument2('+i+')" >Upload</button><button type="button" class="btn btn-danger btn-sm" onclick="Delete('+i+','+item.Id+')" >Delete</button>';
                                            }
                                            else{
                                                display+='<input type="file" id="document" name="document" accept=".doc,.docx,.pdf"><br><button type="button" class="btn btn-primary btn-sm" onclick="submitdocument2('+i+')">Upload</button><button type="button" class="btn btn-danger btn-sm" onclick="Delete('+i+','+item.Id+')">Delete</button>';
                                            }
                                          display+='</form></td>';
                                          display+="</tr>";
                                      });
                                      $("#documentcontent").html(display);
                                    }
                          }
                      });
                    }
          }
      });
  }

  function viewphoto(trackerid,document_type){
    // console.log(trackerid);
    // console.log(document_type);

    $('#SubmitDocument2').modal('hide');
    $('#ViewPhoto').modal('show');

    $.ajaxSetup({
       headers: { 'X-CSRF-Token' : $('meta[name=_token]').attr('content') }
    });

    $.ajax({
                url: "{{ url('/tracker/viewphoto') }}",
                method: "POST",
                data:{
                  TargetId:trackerid,
                  Document_Type:document_type
                },
                success: function(response){

                    if (response)
                    {

                      var myObject = JSON.parse(response);

                      var display='';
                      var index=1;

                      $.each(myObject, function(i,item){

                          display+="<div class='viewimages' id='viewimage"+ item.Id +"'>";

                          display+='<img src="{{ URL::to('/')}}'+ item.Web_Path +'" /><a onclick="Deletephoto(\'deleteimage\','+item.Id+')"><span class="fa fa-trash-o"></span></a><span class="image_name">'+item.File_Name+'</span>';

                          display+="</div>";

                      });

                      $("#photocontent").html(display);


                    }
          }

    });

  }

  function updatecolumn()
  {

      var boxes = $('input[type="checkbox"]:checked');
      var ids="";

      columnid=$('input[name="updatecolumnid[]"]').map(function(){return $(this).val();}).get();
      columnntype=$('[name="updateColumnType[]"] option:selected').map(function(){return $(this).val();}).get();
      colorcode=$('input[name="colorcode[]"]').map(function(){return $(this).val();}).get();


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

      $("#ajaxloaderupdatecolumn").show();
      $("#updatecolumnbutton").prop('disabled', true);

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
                      ColumnType:columnntype,
                      ColorCode:colorcode
                    },
                    success: function(response){
                      $("#ajaxloaderupdatecolumn").hide();
                      $("#updatecolumnbutton").prop('disabled', false);
                      if (response==0)
                      {
                        var message ="Failed to update column!";

                        $('#UpdateColumn').modal('hide')

                        $("#warning-alert ul").html(message);
                        $("#warning-alert").modal("show");

                      }
                      else {
                        $('#UpdateColumn').modal('hide')
                        var message ="Column Updated!";
                        $("#update-alert ul").html(message);
                        $("#update-alert").modal("show");


                        // setTimeout(function() {
                        //   $("#update-alert").fadeOut();
                        window.location.reload();



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

  function addcolumn(trackerid)
  {

      columnname=$('input[name="FieldName[]"]').map(function(){return $(this).val();}).get();
      datatype=$('[name="ColumnType[]"] option:selected').map(function(){return $(this).html();}).get();
      columnntype=$('[name="ColumnType[]"] option:selected').map(function(){return $(this).val();}).get();
      colorcode=$('input[name="ColorCode[]"]').map(function(){return $(this).val();}).get();

      $("#ajaxloaderaddcolumn").show();
      $("#addcolumnbutton").prop('disabled', true);

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
                    Type:columnntype,
                    ColorCode:colorcode
                  },
                  success: function(response){
                    $("#ajaxloaderaddcolumn").hide();
                    $("#addcolumnbutton").prop('disabled', false);
                    if (response==0)
                    {
                      var message ="Failed to add new column!";

                      $('#AddColumn').modal('hide')

                      $("#warning-alert ul").html(message);
                      $("#warning-alert").modal("show");

                    }
                    else {

                      $('#AddColumn').modal('hide');

                      var message ="New column added!";
                      $("#update-alert ul").html(message);
                      $("#update-alert").modal("show");

                        setTimeout(function() {
                          window.location.reload();
                        }, 4000);


                    }
          }
      });


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

                $('#ReorderColumn').modal('hide')

              var message ="Failed to save column sequence!";
              $("#warning-alert ul").html(message);
              $("#warning-alert").modal("show");
              //
              // setTimeout(function() {
              //   $("#warning-alert").fadeOut();
              // }, 3000);



            }
            else
            {

              $('#ReorderColumn').modal('hide');

              var message ="Column sequence saved!";
              $("#update-alert ul").html(message);
              $("#update-alert").modal("show");

              // setTimeout(function() {
              //   $("#update-alert").fadeOut();
              window.location.reload();
              // }, 3000);

              $('#ReorderColumn').modal('hide')

            }

        }
    });

  }

  function Deletephoto(index,Id)
  {
    var result = confirm("Delete the file?");
    if (result) {

      $.ajaxSetup({
         headers: { 'X-CSRF-Token' : $('meta[name=_token]').attr('content') }
      });

      $.ajax({
                  url: "{{ url('/tracker/deletedocument') }}",
                  method: "POST",
                  data:{Id:Id},
                  success: function(response){
                    if (response==0)
                    {
                      var message ="Failed to delete document!";
                      $("#warning-alert ul").html(message);
                      $("#warning-alert").modal("show");

                    }
                    else {
                      $("#viewimage"+Id).remove();
                  }
          }
      });

    }
  }

  function Delete(index,Id)
  {

    var result = confirm("Delete the file?");
    if (result) {
        //Logic to delete the item

        $.ajaxSetup({
           headers: { 'X-CSRF-Token' : $('meta[name=_token]').attr('content') }
        });

        $.ajax({
                    url: "{{ url('/tracker/deletedocument') }}",
                    method: "POST",
                    data:{Id:Id},
                    success: function(response){
                      if (response==0)
                      {
                        var message ="Failed to delete document!";
                        $("#warning-alert ul").html(message);
                        $("#warning-alert").modal("show");

                      }
                      else {

                        TrackerId=document.getElementById("upload_form_"+index).elements.namedItem("selectedtrackerid").value;
                        SiteId=document.getElementById("upload_form_"+index).elements.namedItem("SiteId").value;

                        $.ajaxSetup({
                           headers: { 'X-CSRF-Token' : $('meta[name=_token]').attr('content') }
                        });

                        $.ajax({
                                    url: "{{ url('/tracker/getdocumentlist') }}",
                                    method: "POST",
                                    data: {
                                      TrackerId:TrackerId,
                                      SiteId:SiteId
                                    },
                                    success: function(response){
                                      if (response)
                                      {

                                        var myObject = JSON.parse(response);

                                        var display='';
                                        var index=1;

                                        display+='<table border="1" align="center" class="assettable" cellspacing="0" width="100%" padding="30px" style="font-size: 13px;">';
                                        display+='<tr class="tableheader"><td>Document Type</td><td>Submission Date</td><td>Submitted By</td><td>Download</td><td>Upload</td></tr>';

                                        $.each(myObject, function(i,item){

                                            display+="<tr>";

                                            display+='<td>'+item.Option+'</td><td>'+String(item.Submission_Date).replace('null','')+'</td><td>'+String(item.Submitted_By).replace('null','')+'</td>';

                                            if(item.Download)
                                            {
                                              display+='<td><a href="{{ url('') }}'+item.Download+'" target="_blank">'+item.File_Name+'</a></td>';
                                            }
                                            else {
                                              display+='<td></td>';
                                            }

                                            display+='<td>';

                                              display+='<form enctype="multipart/form-data" id="upload_form_'+i+'" role="form" method="POST" action="" >';
                                              display+='<input type="hidden" class="form-control" id="UserId" name="UserId" value="{{$me->UserId}}">';
                                              display+='<input type="hidden" class="form-control" id="ProjectId" name="ProjectId" value="{{$projectid}}">';
                                              display+='<input type="hidden" class="form-control" id="DocumentType" name="DocumentType" value="'+item.Option+'">';
                                              display+='<input type="hidden" class="form-control" id="selectedtrackerid" name="selectedtrackerid" value="'+TrackerId+'">';
                                              display+='<input type="hidden" class="form-control" id="SiteId" name="SiteId" value="'+SiteId+'">';
                                              display+='<input type="hidden" class="form-control" id="submitdate" name="submitdate">';

                                              display+='<input type="file" id="document" name="document" accept=".doc,.docx,.pdf"><br><button type="button" class="btn btn-primary btn-sm" onclick="submitdocument2('+i+')">Upload</button><button type="button" class="btn btn-danger btn-sm" onclick="Delete('+i+','+item.Id+')">Delete</button>';

                                            display+='</form></td>';

                                            display+="</tr>";


                                        });

                                        $("#documentcontent").html(display);

                                        $("#viewimage"+Id).remove();

                                      }
                            }
                        });



                      }
            }
        });
    }

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

                      $('#ImportData').modal('hide');

                      var message ="Failed to import data!";
                      $("#warning-alert ul").html(message);
                      $("#warning-alert").modal("show");

                      $("#ajaxloader").hide();

                    }
                    else {

                      $('#ImportData').modal('hide');

                      var message ="Data imported!";
                      $("#update-alert ul").html(message);
                      $("#update-alert").modal("show");

                      // setTimeout(function() {
                      //   $("#update-alert").fadeOut();
                      window.location.reload();
                      // }, 3000);

                      $('#ImportData').modal('hide')
                      $("#ajaxloader").hide();

                    }

          }
      });

  }

  function importhuaweipo() {

      $.ajaxSetup({
         headers: { 'X-CSRF-Token' : $('meta[name=_token]').attr('content') }
      });

      $("#ajaxloader3").show();

      $.ajax({

                  url: "{{ url('/tracker/importhuaweipo') }}",
                  method: "POST",
                  contentType: false,
                  processData: false,
                  data:new FormData($("#upload_form3")[0]),

                  success: function(response){
                    if (response==0)
                    {

                      $('#ImportHuaweiPO').modal('hide');

                      var message ="Failed to import Huawei PO!";
                      $("#warning-alert ul").html(message);
                      $("#warning-alert").modal("show");

                      $("#ajaxloader").hide();

                    }
                    else {

                      $('#ImportHuaweiPO').modal('hide');

                      var message ="Huawei PO imported!";
                      $("#update-alert ul").html(message);
                      $("#update-alert").modal("show");

                      // setTimeout(function() {
                      //   $("#update-alert").fadeOut();
                      window.location.reload();
                      // }, 3000);

                      $("#ajaxloader").hide();

                    }

          }
      });

  }



  function newtracker(projectid)
  {

      TrackerName=$('input[name="tracker"]').val();
      columnname=$('input[name="FieldName[]"]').map(function(){return $(this).val();}).get();
      datatype=$('[name="ColumnType[]"] option:selected').map(function(){return $(this).html();}).get();
      columnntype=$('[name="ColumnType[]"] option:selected').map(function(){return $(this).val();}).get();
      colorcode=$('input[name="ColorCode[]"]').map(function(){return $(this).val();}).get();

      column1=$('#Column1').val();
      column2=$('#Column2').val();
      column3=$('#Column3').val();
      column4=$('#Column4').val();
      column5=$('#Column5').val();

      condition1=$('#Condition1').val();
      condition2=$('#Condition2').val();
      condition3=$('#Condition3').val();
      condition4=$('#Condition4').val();
      condition5=$('#Condition5').val();

      criteria1=$('#Criteria1').val();
      criteria2=$('#Criteria2').val();
      criteria3=$('#Criteria3').val();
      criteria4=$('#Criteria4').val();
      criteria5=$('#Criteria5').val();

      operator1=$('#Operator1').val();
      operator2=$('#Operator2').val();
      operator3=$('#Operator3').val();
      operator4=$('#Operator4').val();



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
                    Type:columnntype,
                    ColorCode:colorcode,

                    Column1:column1,
                    Condition1:condition1,
                    Criteria1:criteria1,
                    Operator1:operator1,

                    Column2:column2,
                    Condition2:condition2,
                    Criteria2:criteria2,
                    Operator2:operator2,

                    Column3:column3,
                    Condition3:condition3,
                    Criteria3:criteria3,
                    Operator3:operator3,

                    Column4:column4,
                    Condition4:condition4,
                    Criteria4:criteria4,
                    Operator4:operator4,

                    Column5:column5,
                    Condition5:condition5,
                    Criteria5:criteria5
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
                      // window.location.reload();

                      setTimeout(function() {
                        $("#update-alert").fadeOut();
                          window.location.reload();
                      }, 3000);

                      $('#NewTracker').modal('hide')

                    }
          }
      });


  }

  function assigntask()
  {


    sitename=$('[name="SiteName2"]').val();
    due_date=$('[name="Due_Date"]').val();

    ownertype=$("#OwnerType option:selected").text()

    if(ownertype=="Staff")
    {
      staff=$("#Staff option:selected").text()
      contractor="";
    }else {
      staff="";
      contractor=$("#Contractor option:selected").text()
    }

    task=$('[name="Task"]').val();
    remarks=$('[name="Remarks"]').val();

    if(staff=="" && contractor=="")
    {
      var message ="Person In Charge is required!";

      $("#warning-alert ul").html(message);
      $("#warning-alert").modal("show");
    }
    else {

      $.ajaxSetup({
         headers: { 'X-CSRF-Token' : $('meta[name=_token]').attr('content') }
      });

      $.ajax({
                  url: "{{ url('/tracker/assigntask') }}",
                  method: "POST",
                  data: {
                    ProjectId:{{$projectid}},
                    Site_Name:sitename,
                    Due_Date:due_date,
                    Staff:staff,
                    Contractor:contractor,
                    Task:task,
                    Remarks:remarks
                  },
                  success: function(response){

                    if(response>0)
                    {

                      $('#Assign').modal('hide');

                      var message ="New task assigned!";
                      $("#update-alert ul").html(message);
                      $("#update-alert").modal("show");

                      $("#SiteName2").val("").change();
                      $("#Staff").val("").change();
                      $("#Contractor").val("").change();
                      $("#Due_Date").val("");
                      $("#Task").val("");
                      $("#Remarks").val("");

                    }
                    else {

                      var message ="Failed to add new site!";

                      $("#warning-alert ul").html(message);
                      $("#warning-alert").modal("show");

                    }

                  }
      });

    }


  }

  function createnewsite()
  {

      $.ajaxSetup({
         headers: { 'X-CSRF-Token' : $('meta[name=_token]').attr('content') }
      });

      if({{$trackerid}}==0)
      {

        var message ="No Tracker created!";

        $("#warning-alert ul").html(message);
        $("#warning-alert").modal("show");

      }
      else {

        $.ajax({
                    url: "{{ url('/tracker/createnewsite') }}",
                    method: "POST",
                    data: {
                      ProjectId:{{$projectid}},
                      TrackerId:{{$trackerid}}
                    },
                    success: function(response){

                      if(response.split("|")[0]>0)
                      {

                        var message ="New site created!";
                        $("#update-alert ul").html(message);
                        $("#update-alert").modal("show");

                        window.location.reload();

                      }
                      else {

                        var message ="Failed to add new site!";

                        $("#warning-alert ul").html(message);
                        $("#warning-alert").modal("show");

                      }

                    }
        });

      }


  }

  function renametracker(trackerid)
  {

      TrackerName=$('input[name="renametracker"]').val();
      column1=$('#Column1-2').val();
      column2=$('#Column2-2').val();
      column3=$('#Column3-2').val();
      column4=$('#Column4-2').val();
      column5=$('#Column5-2').val();

      condition1=$('#Condition1-2').val();
      condition2=$('#Condition2-2').val();
      condition3=$('#Condition3-2').val();
      condition4=$('#Condition4-2').val();
      condition5=$('#Condition5-2').val();

      criteria1=$('#Criteria1-2').val();
      criteria2=$('#Criteria2-2').val();
      criteria3=$('#Criteria3-2').val();
      criteria4=$('#Criteria4-2').val();
      criteria5=$('#Criteria5-2').val();

      operator1=$('#Operator1-2').val();
      operator2=$('#Operator2-2').val();
      operator3=$('#Operator3-2').val();
      operator4=$('#Operator4-2').val();

      $.ajaxSetup({
         headers: { 'X-CSRF-Token' : $('meta[name=_token]').attr('content') }
      });

      $.ajax({
                  url: "{{ url('/tracker/rename') }}",
                  method: "POST",
                  data: {
                    TrackerId:trackerid,
                    TrackerName:TrackerName,

                    Column1:column1,
                    Condition1:condition1,
                    Criteria1:criteria1,
                    Operator1:operator1,

                    Column2:column2,
                    Condition2:condition2,
                    Criteria2:criteria2,
                    Operator2:operator2,

                    Column3:column3,
                    Condition3:condition3,
                    Criteria3:criteria3,
                    Operator3:operator3,

                    Column4:column4,
                    Condition4:condition4,
                    Criteria4:criteria4,
                    Operator4:operator4,

                    Column5:column5,
                    Condition5:condition5,
                    Criteria5:criteria5

                  },
                  success: function(response){

                    if (response==0)
                    {

                      $('#RenameTracker').modal('hide')

                      var message ="Failed to rename tracker!";

                      $("#warning-alert ul").html(message);
                      $("#warning-alert").modal("show");


                    }
                    else {

                        $('#RenameTracker').modal('hide');


                      var message ="New tracker added!";
                      $("#update-alert ul").html(message);
                      $("#update-alert").modal("show");

                      window.location.reload();



                    }
          }
      });


  }

  function search() {

    var siteid = document.getElementById("SiteID3").value;
    var sitename = document.getElementById("SiteName3").value;

    console.log('{{ asset('/Include/issue.php') }}?ProjectId={{$projectid}}&Site_Name='+sitename+'&Site_ID='+siteid);

    oTable.api().ajax.reload();

    //oTable.api().ajax.reload();
}

function getsiteid() {

  var siteid = document.getElementById("SiteID").value;
  return siteid;

}

function getsitename() {

  var sitename = document.getElementById("SiteName").value;
  return sitename;

}

function adjust(){
  $.fn.dataTable.tables( {visible: true, api: true} ).columns.adjust();
}

$( "#OwnerType" ).change(function() {

  if(document.getElementById("OwnerType").value=="Staff")
  {
    $( "#Contractordiv" ).hide();
    $( "#Staffdiv" ).show();
  }
  else {
    $( "#Staffdiv" ).hide();
    $( "#Contractordiv" ).show();
  }

});


function duplicatetracker()
{

    TrackerName=$('input[name="duplicatetracker"]').val();
    copyid = document.getElementById("ExistingTracker").value;

    $.ajaxSetup({
       headers: { 'X-CSRF-Token' : $('meta[name=_token]').attr('content') }
    });

    $.ajax({
                url: "{{ url('/tracker/duplicate') }}",
                method: "POST",
                data: {
                  ExistingTrackerId:copyid,
                  TrackerName:TrackerName,
                  ProjectId:{{$projectid}}

                },
                success: function(response){

                  if (response==0)
                  {

                    $('#DuplicateTracker').modal('hide')

                    var message ="Failed to duplicate tracker!";

                    $("#warning-alert ul").html(message);
                    $("#warning-alert").modal("show");


                  }
                  else if (response==-1)
                  {

                    $('#DuplicateTracker').modal('hide')

                    var message ="Tracker Name already in used!";

                    $("#warning-alert ul").html(message);
                    $("#warning-alert").modal("show");


                  }
                  else {

                      $('#DuplicateTracker').modal('hide');


                    var message ="Tracker Duplicated!";
                    $("#update-alert ul").html(message);
                    $("#update-alert").modal("show");

                    window.location.reload();

                  }
        }
    });


}

function deletetracker(trackerid)
{

    $.ajaxSetup({
       headers: { 'X-CSRF-Token' : $('meta[name=_token]').attr('content') }
    });

    $.ajax({
                url: "{{ url('/tracker/delete') }}",
                method: "POST",
                data: {
                  TrackerId:trackerid

                },
                success: function(response){

                  if (response==0)
                  {

                    $('#DeleteTracker').modal('hide')

                    var message ="Failed to delete tracker!";

                    $("#warning-alert ul").html(message);
                    $("#warning-alert").modal("show");


                  }
                  else {

                      $('#DeleteTracker').modal('hide');


                    var message ="Tracker Deleted!";
                    $("#update-alert ul").html(message);
                    $("#update-alert").modal("show");

                    window.location.reload();

                  }
        }
    });


}

function callupdatepurchaseinvoicemodal(id)
{
  $("#TargetId").val(id);

  $("#UpdatePurchaseInvoice").modal("show")

}

function openupdatemodal(data,header)
{

  var arr = $.map(data, function(el) { return el });
  var head = $.map(header, function(el) { return el });
  var newarr=[];
  head.splice(0, 1);

  $('#UpdateId').val(arr[0]);

  for(var i = 0; i < head.length; i++) {

    if(String(head[i]).toUpperCase().includes("INVOICE AMOUNT") ||
    String(head[i]).toUpperCase().includes("INVOICE NO ADDITIONAL") ||
    String(head[i]).toUpperCase().includes("INVOICE DATE ADDITIONAL") ||
    String(head[i]).toUpperCase().includes("INVOICE NO") ||
    String(head[i]).toUpperCase().includes("INVOICE DATE") ||
    String(head[i]).toUpperCase().includes("INVOICE AMOUNT") ||
    String(head[i]).toUpperCase().includes("INVOICE STATUS") ||
    String(head[i]).toUpperCase().includes("INVOICE AMOUNT ADDITIONAL") ||
    String(head[i]).toUpperCase().includes("INVOICE VALUE") ||
    String(head[i]).toUpperCase().includes("INVOICE SCOPE") ||
    String(head[i]).toUpperCase().includes("INVOICE COMPANY") ||

    String(head[i]).toUpperCase().includes("INV NO") ||
    String(head[i]).toUpperCase().includes("INV DATE") ||
    String(head[i]).toUpperCase().includes("INV AMOUNT") ||
    String(head[i]).toUpperCase().includes("SCOPE OF WORK") ||
    String(head[i]).toUpperCase().includes("SUPPLIER")



    )
    {
      newarr.push(arr[i]);
    }

  }

  var elements = document.getElementById("update_form").elements;

  for (var i = 0, element; element = elements[i++];) {
    var post=i-4;
    if(element.type=="text" || element.type=="number")
    {

      $("input[name='"+element.name+"']").val(newarr[post]);
    }
    else if(element.type=="textarea")
    {
      // $('#'+element.name).val(arr[i+2]);
      $("textarea#"+element.name).val(newarr[post]);
    }
    else if (element.type=="select-one")
    {
      //console.log($("input[name='"+element.name+"']"));

      // $('#' + element.name).append($('<option>', {
      //     value: arr[i+1],
      //     text: arr[i+1]
      // }));

      // value=arr[i+1];
      // console.log(value);
      //
      // $('#' + element.name).each(function(value) {
      //     if ($(this).val()==value)
      //     {
      //       $('#' + element.name).val(this.text).change();
      //     }
      //     console.log(value);
      //     console.log($(this).val());
      //
      // });

      var dropdown = document.getElementById(element.name);
      var bool=false;
   for(j = 0; j < dropdown.length; j++) {

     if (dropdown.options[j].value==newarr[post]) {
         $('#' + element.name).val(dropdown.options[j].value).change();
         bool=true;
     }
   }

   if(bool==false)
   {
     $('#' + element.name).append($('<option>', {
          value: newarr[post] ,
          text: newarr[post]
      }));

      $('#' + element.name).val(newarr[post]).change();
   }

      // $('#' + element.name).val(arr[i+1]).change();

    }

  }

  $("#UpdateSite").modal("show");

}

function updatesite()
{

  var myform = document.getElementById("update_form");
  var fd = new FormData(myform );

  $("#updatesite").prop('disabled', true);

    $.ajaxSetup({
       headers: { 'X-CSRF-Token' : $('meta[name=_token]').attr('content') }
    });

    $.ajax({
                url: "{{ url('/tracker/updatesite') }}",
                method: "POST",
                contentType: false,
                processData: false,
                data: fd,
                success: function(response){

                  if(response>0)
                  {

                    var message ="Site Updated!";
                    $("#update-alert ul").html(message);
                    $("#update-alert").modal("show");
                    window.location.reload();

                    $("#updatesite").prop('disabled', false);

                  }
                  else {

                    var message ="Failed to update site!";

                    $("#warning-alert ul").html(message);
                    $("#warning-alert").modal("show");

                    $("#updatesite").prop('disabled', false);

                  }

                }
    });


}

function calculatesum()
{
    var arrsumresult=[];

    if(typeof hot !== 'undefined')
    {

      var all=hot.getData();

      for(var j=0;j<arrsum.length;j++)
      {
        arrsumresult.push(0.0);
      }

      var header=hot.getColHeader();

       for (var i = 0; i < all.length; i++) {

         for(var j=0;j<arrsum.length;j++)
         {
           var Id=header.indexOf(arrsum[j]);

           if(all[i][Id])
           {
             if(!isNaN(parseFloat(all[i][Id].replace( /[^\d\.]*/g, ''))))
             {
               arrsumresult[j]=arrsumresult[j]+parseFloat(all[i][Id].replace( /[^\d\.]*/g, ''));
             }
           }


         }

       }

       for(var j=0;j<arrsum.length;j++)
       {

         $("span[id='"+arrsum[j]+"']").html(arrsumresult[j].toLocaleString('en-US', {minimumFractionDigits: 2}));
       }

    }

}

function promptunsaved()
{

  if(Idarray.length>49)
  {
    var message ="You have more than 50 unsave data!";
    $("#warning-alert ul").html(message);
    $("#warning-alert").modal("show");
  }

}

  function decodeHTMLEntities(encodedString) {
      var textArea = document.createElement('textarea');
      textArea.innerHTML = encodedString;
      return textArea.value;
  }

  function updatefibre() {
      $.ajaxSetup({
         headers: { 'X-CSRF-Token' : $('meta[name=_token]').attr('content') }
      });

      $.ajax({
                  url: "{{ url('/tracker/updatefibre') }}",
                  method: "POST",
                  contentType: false,
                  processData: false,
                  data:new FormData($("#fibreupdateform")[0]),
                  success: function(response){
                    if (response==0)
                    {
                      var message ="Failed to update diary!";
                      $("#warning-alert ul").html(message);
                      $("#warning-alert").modal("show");


                    }
                    else {
                      var message ="Diary Updated!";
                      $("#update-alert ul").html(message);
                      $("#update-alert").modal("show");

                      $('#FibreUpdate').modal('hide');

                    }
          }
      });
  }

  function showlog(TrackerId)
  {
    $('#LogModal').modal('show');
    $("#history").html("");

    $.ajaxSetup({
       headers: { 'X-CSRF-Token' : $('meta[name=_token]').attr('content') }
    });

    $.ajax({
                url: "{{ url('/tracker/diary') }}",
                method: "POST",
                data: {
                  TrackerId:TrackerId
                },
                success: function(response){
                  if (response==0)
                  {
                    var message ="Failed to retrieve sie diary!";
                    $("#warning-alert ul").html(message);
                    $("#warning-alert").show();
                    $('#LogModal').modal('hide')

                  }
                  else {

                    var myObject = JSON.parse(response);

                        var display='<table border="1" align="center" class="historytable" cellspacing="0" width="100%" padding="30px" style="font-size: 13px;">';
                        display+='<tr class="historyheader"><td>Date</td><td>Updated</td><td>Team</td><td>HDD</td><td>GV</td><td>MH</td><td>Poles</td><td>Subduct</td><td>Cables</td><td>Weather</td><td>Activity</td><td>Issue</td><td>Action</td><td>MH</td>Remarks</tr>';

                        $.each(myObject, function(i,item){

                                display+="<tr>";
                                display+='<td>'+item.Date+'</td><td>'+item.Updated_By+'</td><td>'+item.Team+'</td><td>'+item.HDD+'</td><td>'+item.GV+'</td><td>'+item.MH+'</td><td>'+item.Poles+'</td><td>'+item.Subduct+'</td><td>'+item.Cables+'</td><td>'+item.Weather+'</td><td>'+item.Activity+'</td><td>'+item.Issue+'</td><td>'+item.Action+'</td><td>'+item.Remarks+'</td>';
                                display+="</tr>";
                        });

                    display+="</table>";

                    $("#history").html(display);

                  }
        }
    });

  }

</script>

@endsection
