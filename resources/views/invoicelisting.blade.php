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

.modal-dialog{
  width: 80%; /* respsonsive width */
}

.poheader{
  background-color: grey;

}

  </style>

@endsection

@section('content')

  <div class="content-wrapper">
  <!-- Content Header (Page header) -->
  <section class="content-header">
    <h1>
      Invoice Listing
      <small>Sales Management</small>
    </h1>
    <ol class="breadcrumb">
      <li><a href="{{ url('/home') }}"><i class="fa fa-dashboard"></i> Home</a></li>
      <li><a href="#">Sales Management</a></li>
      <li><a href="#">Invoice Listing</a></li>
    </ol>
  </section>

  <!-- Main content -->
  <section class="content">
    <div class="row">

        <div class="box box-primary">

          <div class="col-md-3">

             <div class="box-body">
               <div class="form-group">

                 <div class="form-group">
                   <label>Project Name : </label>
                  <select class="form-control select2" id="ProjectId" name="ProjectId" style="width: 30%;">
                    <option value="0">-</option>
                    <option value="All" <?php if($projectid=="All") echo "selected";?>>All</option>

                    @foreach ($projects as $project)
                       <option value="{{$project->Id}}" <?php if($project->Id==$projectid) echo "selected";?>>{{$project->Project_Name}}</option>
                    @endforeach
                  </select>
                </div>

               </div>

           </div>
       </div>

       <div class="col-md-2">

          <div class="box-body">
            <div class="form-group">

              <div class="form-group">
                <label>Year : </label>
               <select class="form-control select2" id="Year" name="Year" style="width: 30%;">
                 <option value="0">-</option>
                 <option value="All">All</option>
                 @foreach($years as $y)

                  <option value="{{$y->yearname}}" <?php if($y->yearname==$year) echo "selected";?>>{{$y->yearname}}</option>

                 @endforeach

               </select>
             </div>

            </div>

        </div>
    </div>

    <div class="col-md-2">
      <div class="box-body">
        <div class="form-group">
          <br/>
          <div class="input-group">
            <button type="button" class="btn btn-success btn" data-toggle="modal" onclick="refresh();">Search</button>
          </div>

        </div>

      </div>

    </div>

      </div>

      <div id="divLoading" style="display:none;">

      </div>

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

        <div class="modal fade" id="CreateNewSite" role="dialog" aria-labelledby="myModalLabel">
          <div class="modal-dialog" role="document">
            <div class="modal-content">
              <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title" id="myModalLabel">Create New record</h4>

               </div>

               <div class="modal-body">
                 <div class="form-group">
                   <form id="search_form" role="form" method="POST" action="" >

                     <div class="row">

                       <div class="col-md-4">
                           <label>Project Code : </label>

                         <div class="form-group">
                           <select class="form-control select2" id="Search_Project_Code" name="Search_Project_Code">
                             <option></option>

                             @foreach($projectcodes as $code)
                                 <option value="{{$code->Project_Code}}">{{$code->Project_Code}} - {{$code->{'Site ID'} }} - {{$code->{'Site LRD'} }}</option>
                             @endforeach

                         </select>
                         </div>

                       </div>

                       <div class="col-md-3">
                       <label>PO No : </label>

                       <div class="form-group">

                         <input type="text" class="form-control pull-right" id="Search_PO_No" name="Search_PO_No" value="">

                       </div>

                       </div>

                       <div class="col-md-3">
                       <label>Site ID / LRD / Site Name : </label>

                       <div class="form-group">
                         <input type="text" class="form-control pull-right" id="Search_Site" name="Search_Site" value="">
                       </div>

                       </div>

                         <br>
                           <button type="button" class="btn btn-success btn" data-toggle="modal" onclick="searchrecord();">Search</button>


                     </div>


                     <div class="row">

                         <div class="form-data table-responsive">
   		                       <div id="tablecontainer" class=container-fluid>
                              </div>
                         </div>

                     </div>



                   </form>
                 </div>
               </div>

               <div class="modal-footer">
                 <center><img src="{{ URL::to('/') ."/img/ajax-loader.gif" }}" width="50px" height="50px" alt="Loading" name='ajaxloader' id="ajaxloader"></center>
                 <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                 <button type="button" class="btn btn-primary" onclick="createmultiplerecord()">Create</button>
               </div>
             </div>
           </div>
         </div>

            <div class="col-md-12" id="zoomdiv">

              <div class="box-body">

                  <div class="box-body">

                     <button type="button" class="btn btn-success btn-xs" onclick="clickToSave()">Save Changes</button>

                      <button type="button" class="btn btn-success btn-xs" id="export-file">Export to CSV</button>
                      <button type="button" class="btn btn-warning btn-xs" onclick="clearfiltering()">Clear Filtering</button>
                      <button type="button" class="btn btn-warning btn-xs" onclick="unfreeze()">Unfreeze All Column</button>
                      <button type="button" class="btn btn-warning btn-xs" onclick="unhide()">Unhide All Column</button>

                      <br>
                      <br>

                      <b>Data &nbsp;&nbsp;&nbsp;&nbsp;: <input style="color: blue;font-Weight:bold;" type="text" name="contenttext" id="contenttext" size="70" disabled/>

                      Sum : <input style="color: blue;font-Weight:bold;" type="text" name="sumtext" id="sumtext" size="15" value="0" disabled/>
                      Count : <input style="color: blue;font-Weight:bold;" type="text" name="counttext" id="counttext" size="10" value="{{sizeof($invoicelisting)}}" disabled/></b>
                      <label class="pull-right"><font style="color: black;font-Weight:bold;"><span id="recordlabel">Showing {{sizeof($invoicelisting)}} of {{sizeof($invoicelisting)}} record(s)</span></font></label>
                      <br>
                      <b>Search : <input style="color: blue;font-Weight:bold;" type="text" name="searchtext" id="searchtext" size="30"/><button type="button" class="btn btn-success btn-xs" onclick="filtertext()">Search</button><button type="button" class="btn btn-danger btn-xs" onclick="resetsearch()">Reset</button>
                  </div>

                  <button type="button" class="btn btn-primary btn" data-toggle="modal" data-target="#CreateNewSite">New Record</button>
                  <br/><br/>
              <div id="example1"></div>

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
        $("#ajaxloader").hide();

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

     });

      //  $('#agingdiv').slimScroll({
      //     height: '250px'
      // });

       var availableTags = [];
       var sitename = [];
       var siteid = [];


       @foreach ($invoicelisting as $item)

         @if(array_key_exists("Project_Code",$item))

           siteid.push("{{$item["Project_Code"]}}");

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



  myVar = <?php echo json_encode($invoicelisting); ?>;

  var container = document.getElementById('example1');

  function firstRowRenderer(instance, td, row, col, prop, value, cellProperties) {
    Handsontable.renderers.TextRenderer.apply(this, arguments);
    td.style.fontWeight = 'bold';
    // td.style.color = 'green';
    td.style.background = 'yellow';
  }

  @if($invoicelisting)



  var header="<?php echo implode(",",array_keys($invoicelisting[0])); ?>";
  var colHeaders=header.split(",");

  var changes = null;
  var sourceFrom = null;
  // var changesArray = [];

  var Idarray = [];
  var Columnarray = [];
  var Originalarray = [];
  var Updatearray = [];

  function clickToSave() {
    var lastSave = false;
    // for (var i = 0, len = Idarray.length; i < len; i++) {
      // lastSave = (i == len - 1 ? true : false);
      saveChanges2(Idarray,Columnarray,Originalarray,Updatearray, lastSave);
    // }
     // changesArray = [];
     Idarray = [];
     Columnarray = [];
     Originalarray = [];
     Updatearray = [];

     // window.location.reload();
  }

  function saveChanges2(Idval,Columnval,Originalval,Updateval, lastSave) {

    if (Idarray) {

    } else {
      return;
    }

    $.ajaxSetup({
            headers: { 'X-CSRF-Token' : $('meta[name=_token]').attr('content') }
         });

         $.ajax({
             url: "{{ url('/invoicelisting/updateinvoicelisting') }}",
             method: "POST",
             data: {
               Id:Idval,
               Column:Columnval,
               Original:Originalval,
               Update:Updateval
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

                 window.location.reload();
               }
             }
         });

  }

  var hot = new Handsontable(container, {
    data: myVar,
    rowHeaders: true,
    colHeaders: true,

      contextMenu:  ['copy', 'cut','hidden_columns_hide','freeze_column', 'unfreeze_column', 'remove_row'],

    manualColumnFreeze: true,
    manualColumnMove: true,
    dropdownMenu: true,
    filters:true,
    renderAllRows:false,
    formulas: true,
    height: 440,
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
    stretchH: 'all',
    columns: [
      {
        data: '-',
        title:'Action',
        renderer: customActionRenderer,
        width: '60px',
      },
      {
        data: 'Id'
      },
      {
        data: 'TrackerId'
      },
      {
        data: 'Project_Code',
        readOnly: true,
      },
      {
        data: 'Project',
        readOnly: true,
      },
      {
        data: 'Customer',
        readOnly: true,
      },
      {
        data: 'Region',
        readOnly: true,
      },
      {
        data: 'Site_ID',
        readOnly: true,
      },
      {
        data: 'Year',
        type: 'dropdown',
        source: [
          '',
          @foreach($years as $y)
              '{{$y->yearname}}',
          @endforeach

        ]
      },
      {
        data: 'Month',
        type: 'dropdown',
        source: [
          '',
          @foreach($months as $m)
              '{{$m}}',
          @endforeach

        ]
      },
      {
        data: 'PO_Milestone',
        type: 'dropdown',
        source: [
          '',
          @foreach($milestones as $milestone)
              '{{$milestone->Option}}',
          @endforeach

        ]
      },
      {
        data: 'PO_No',
      },
      {
        data: 'PO_Date',
        type: 'date',
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
      },
      {
        data: 'PO_Amount',
        type: 'numeric',
        format: '0,0.00',
        language: 'en-US'
      },
      {
        data: 'Invoice_No',
      },
      {
        data: 'Invoice_Date',
        type: 'date',
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
      },
      {
        data: 'Invoice_Amount',
        type: 'numeric',
        format: '0,0.00',
        language: 'en-US'
      },
      {
        data: 'Name',
        readOnly: true,
      },

    ],

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
                  url: "{{ url('/invoicelisting/removerecord') }}",
                  method: "POST",
                  data: {
                    Ids:ids
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

    }

  },
  afterOnCellMouseDown: function(event,coords,TD)
  {

    var data = this.getDataAtRow(coords.row);
    if(coords.row>-1)
    {
      $("#contenttext").val(data[coords.col]);
    }
    else {

        var count=0;
        var sum=0;
        var all=hot.getData();
         for (var i = 0; i < all.length; i++) {

           if(all[i][coords.col].length>0)
           {
             count=count+1;
           }

           if(!isNaN(parseFloat(all[i][coords.col].replace( /[^\d\.]*/g, ''))))
           {
             sum=sum+parseFloat(all[i][coords.col].replace( /[^\d\.]*/g, ''));
           }

         }

         $("#counttext").val(count);
         $("#sumtext").val(parseFloat(sum.toFixed(2)).toLocaleString("en"));


    }

  },
  afterSelection: function(r,c){
    var data = this.getDataAtRow(r);
    $("#contenttext").val(data[c]);

  },
  afterFilter: function(){

    $("#recordlabel").html("Showing "+ hot.getData().length +" of {{sizeof($invoicelisting)}} record(s)");

  },
  afterChange: function (change, source) {
    if (source === 'loadData') {
      return; //don't save this change
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

          column=change[i][1];
          original=change[i][2];
          update=change[i][3];

          Idarray.push(id);
          Columnarray.push(column);
          Originalarray.push(original);
          Updatearray.push(update);

      }


    } else  {
          for (var i = 0; i < change.length; i++) {

            data=hot.getDataAtRow(change[i][0]);

            for (var j = 0; j < data.length; j++) {
              if(hot.getColHeader(j)=="Id")
              {
                break;
              }
            }

            id=data[j];

            column=change[i][1];
            original=change[i][2];
            update=change[i][3];

            Idarray.push(id);
            Columnarray.push(column);
            Originalarray.push(original);
            Updatearray.push(update);

        }
    }

  },
  });

  @endif

  var buttons = {
    file: document.getElementById('export-file')
  };

@if($invoicelisting)
   var exportPlugin = hot.getPlugin('exportFile');
@endif

   buttons.file.addEventListener('click', function() {
    exportPlugin.downloadFile('csv',
    {filename: '{{ date('dMY') }}',
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

  function resetsearch(){
    hot.loadData(myVar);
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
              array.push(data[row]);
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

  function customActionRenderer(instance, td, row, col, prop, value, cellProperties)
  {



    var data=instance.getDataAtRow(row);
    var header=instance.getColHeader();

    var Id=header.indexOf("TrackerId");

      @if($me->User_Type=="External")
        value="-";

      @else
      if(data[0]>0)
      {
        // document.write(Id);
        value="<a href='{{ URL::to('/tracker/filecategory2')}}/{{$projectid}}"+"/"+ data[Id] +"' target='_blank'>" + data[0] + " File(s)</a>"; //FINAL
        value+=" | <a href='#' onclick='openupdatemodal("+JSON.stringify(data)+","+JSON.stringify(header)+");'>";

      }
      else {
        value="<a href='{{ URL::to('/tracker/filecategory2')}}/{{$projectid}}"+"/"+ data[Id] +"' target='_blank'>0 File(s)</a>"; //FINAL
        value+=" | <a href='#' onclick='openupdatemodal("+JSON.stringify(data)+","+JSON.stringify(header)+");'>";
      }

      @endif

      value=value+"</center>";
      value = Handsontable.helper.stringify(value);

      td.innerHTML=value;

      return td;
  }

  searchFiled3 = document.getElementById('search_field');

  Handsontable.dom.addEvent(searchFiled3,'keyup', function(event) {
    var queryResult = hot.search.query(this.value);

    hot.render();
  });

  function refresh()
  {
    var year=$('#Year').val();
    var project=$('#ProjectId').val();

    window.location.href ="{{ url("/invoicelisting") }}/"+year+"/"+project;

  }

  function newrecord()
  {

    newprojectcode=$("#newprojectcode").val();

      $.ajaxSetup({
         headers: { 'X-CSRF-Token' : $('meta[name=_token]').attr('content') }
      });

      $.ajax({
                  url: "{{ url('/invoicelisting/newrecord') }}",
                  method: "POST",
                  data: {
                    TrackerId:newprojectcode
                  },
                  success: function(response){

                    if(response>0)
                    {

                      window.location.reload();

                    }
                    else {

                      var message ="Failed to add new record!";

                      $("#warning-alert ul").html(message);
                      $("#warning-alert").modal("show");

                    }

                  }
      });


  }

  function searchrecord() {

    var projectcode = document.getElementById("Search_Project_Code").value;
    var po = document.getElementById("Search_PO_No").value;
    var site = document.getElementById("Search_Site").value;
    // var updatetype=document.getElementById("Update_Type2").value;

    if("{{$projectid}}" && "{{$projectid}}"!="All")
    {

      $.ajaxSetup({
         headers: { 'X-CSRF-Token' : $('meta[name=_token]').attr('content') }
      });

      $("#ajaxloader").show();

      $.ajax({
            url: "{{ url('/tracker/searchrecord') }}",
            method: "POST",
            data: {PO:po,
            Project_Code:projectcode,
            Site:site,
            ProjectId:"{{$projectid}}",
            Type:"Invoice" },

            success: function(response){

              if (response=="[]")
              {
                var display='';
                var result='';

                display+='<table border="1" align="center" class="table" cellspacing="0" width="70%" margin="30px" style="font-size: 13px;">';

                display+='<th class="poheader">No Record Found</th>';

                display+='</table>';

                $("#tablecontainer").html(display);

                $("#ajaxloader").hide();


              }
              else {

                var myObject = JSON.parse(response);

                var display='';
                var result='';

                display+='<table border="1" align="center" class="table" cellspacing="0" width="70%" margin="30px" style="font-size: 13px;">';

                item=myObject[0];

                display+="<tr>";
                  display+='<th class="poheader"><input type="checkbox" name="selectall" id="selectall" value="all" onclick="checkall()"></th>';
                  for (var p in item) {

                      if(p!="Id")
                      {
                        if( item.hasOwnProperty(p) ) {

                          display+='<th class="poheader">'+p+'</th>';

                        }
                      }

                  }
                display+="</tr>";

                $.each(myObject, function(i,item){
                  display+="<tr>";
                    display+='<td><input type="checkbox" name="selectrow" id="selectrow" class="selectrow" value="'+item["Id"]+'|'+item["PO_Milestone"]+'" onclick="uncheck()"></td>';
                    for (var p in item) {

                      if(p!="Id")
                      {
                        if( item.hasOwnProperty(p) ) {

                          display+='<td>'+item[p]+'</td>';

                        }
                      }

                    }
                  display+="</tr>";
                });

                display+="</table>";


                  $("#tablecontainer").html(display);

                  $("#ajaxloader").hide();
              }

          }
      });

    }
    else {

      var errormessage="No Project selected!";
      $("#error-alert ul").html(errormessage);
      $("#error-alert").modal('show');

    }

  }

  function uncheck()
  {

    if (!$("#selectrow").is(':checked')) {
      $("#selectall").prop("checked", false)
    }

  }

  function checkall()
  {

        // alert(document.getElementById("selectall").checked);
    if ($("#selectall").is(':checked')) {
         $(".selectrow").prop("checked", true);
         $(".selectrow").trigger("change");
    } else {
        $(".selectrow").prop("checked", false);
        $(".selectrow").trigger("change");
    }
  }

  function createmultiplerecord() {

    var boxes = $('.selectrow:checkbox:checked')
    var ids="";
    if (boxes.length>0)
    {
      for (var i = 0; i < boxes.length; i++) {
        if(boxes[i].value)
        {
          ids+=boxes[i].value+",";
        }

      }

      ids=ids.substring(0, ids.length-1);

      $.ajaxSetup({
         headers: { 'X-CSRF-Token' : $('meta[name=_token]').attr('content') }
      });

      $("#ajaxloader").show();

      $.ajax({
            url: "{{ url('/invoicelisting/createmultiplerecord') }}",
            method: "POST",
            data: {Ids:ids
          },

            success: function(response){

              if (response==1)
              {
                var errormessage="Record created!";
                $("#update-alert ul").html(errormessage);
                $("#update-alert").modal('show');

                setTimeout(function() {
                  window.location.reload();
                }, 3000);

              }
              else {

                var errormessage="No item selected!";
                $("#error-alert ul").html(errormessage);
                $("#error-alert").modal('show');

              }

          }
      });
    }
    else {
      var errormessage="No item selected!";
      $("#error-alert ul").html(errormessage);
      $("#error-alert").modal('show');
    }

  }

</script>

@endsection
