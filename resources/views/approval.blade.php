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

      .projectheader{
        background-color: gray;
      }

      .projecttable{
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

      <script type="text/javascript" language="javascript" class="init">

      var editor; // use a global for the submit and return data rendering in the examples
      var oTable;

      $(document).ready(function() {

            editor = new $.fn.dataTable.Editor( {
                  ajax: {
                     "url": "{{ asset('/Include/approvalcontrol.php') }}",
                     "data": {
                         "type": "{{ $type }}"
                     }
                   },
                    table: "#approvaltable",
                    idSrc: "approvalsettings.Id",
                    fields: [
                            {
                                   name: "approvalsettings.Created_By",
                                   type: "hidden"
                            },{
                                    label: "Approver:",
                                    name: "approvalsettings.UserId",
                                    type:  'select',
                                    options: [
                                      { label :"", value: "" },
                                      @foreach($approver as $user)
                                          { label :"{{$user->Name}}", value: "{{$user->Id}}" },
                                      @endforeach
                                    ],
                            },{
                                    label: "Project:",
                                    name: "approvalsettings.ProjectId",
                                    type:  'select',
                                    options: [
                                        { label :"", value: "" },
                                        @foreach($projects as $project)
                                            { label :"{{$project->Project_Name}}", value: "{{$project->Id}}" },
                                        @endforeach
                                    ],
                            },{
                                    label: "Type:",
                                    name: "approvalsettings.Type",
                                    type:  'select',
                                    options: [
                                        { label :"Claim", value: "Claim" },
                                        { label :"Leave", value: "Leave" },
                                        { label :"Deduction", value: "Deduction" },
                                        { label :"Loan", value: "Loan" },
                                        { label :"Request", value: "Request" },
                                        // { label: "MR", value:"MR"}
                                    ],
                            },{
                                    label: "Level:",
                                    name: "approvalsettings.Level",
                                    type:  'select',
                                    options: [
                                        { label :"1st Approval", value: "1st Approval" },
                                        { label :"2nd Approval", value: "2nd Approval" },
                                        { label :"3rd Approval", value: "3rd Approval" },
                                        { label :"4th Approval", value: "4th Approval" },
                                        { label :"5th Approval", value: "5th Approval" },
                                        { label :"Final Approval", value: "Final Approval" }
                                    ],
                            }

                    ]
            } );

            // Activate an inline edit on click of a table cell
            $('#approvaltable').on( 'click', 'tbody td', function (e) {
                  editor.inline( this, {
                 onBlur: 'submit'
                } );
            } );

            oTable=$('#approvaltable').dataTable( {
                    ajax: {
                       "url": "{{ asset('/Include/approvalcontrol.php') }}",
                       "data": {
                           "type": "{{ $type }}"
                       }
                     },
                    columnDefs: [{ "visible": false, "targets": [1] },{"className": "dt-center", "targets": "_all"}],
                    responsive: false,
                    //colReorder: true,
                    //stateSave:true,
                    dom: "Blftp",
                    iDisplayLength:100,
                    bAutoWidth: true,
                    iDisplayLength:10,
                    rowId:"approvalsettings.Id",
                    order: [[ 5, "asc" ]],
                    columns: [
                            { data: null, "render":"", title: "No"},
                            { data: "approvalsettings.Id", title: "Id"},
                            { data: "approvalsettings.Type", title: "Type" },
                            { data: "users.Name", editField: "approvalsettings.UserId", title: "Approver" },
                            { data: "approvalsettings.Level", title: "Level" },
                            { data: "projects.Project_Name", editField: "approvalsettings.ProjectId", title:"Department" }

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
                            {
                              text: 'New Row',
                              action: function ( e, dt, node, config ) {
                                  // clearing all select/input options
                                  editor
                                     .create( false )
                                     .set( 'approvalsettings.Created_By', {{ $me->UserId }} )
                                     .set( 'approvalsettings.Type', '{{$type}}')
                                     .submit();
                              },
                            },
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
          if ($('#approvaltable').length > 0)
          {

              var colnum=document.getElementById('approvaltable').rows[0].cells.length;

              if (this.value=="[empty]")
              {

                 oTable.fnFilter( '^$', $("thead input").index(this)-colnum,true,false );
              }
              else if (this.value=="[nonempty]")
              {

                 oTable.fnFilter( '^(?=\\s*\\S).*$', $("thead input").index(this)-colnum,true,false );
              }
              else if (this.value.startsWith("!")==true && this.value.length>1)
              {

                 oTable.fnFilter( '^(?'+ this.value +').*', $("thead input").index(this)-colnum,true,false );
              }
              else if (this.value.startsWith("!")==false)
              {

                oTable.fnFilter( this.value, $("thead input").index(this)-colnum,true,false );
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
          Approval Control
          <small>IT Support</small>
        </h1>
        <ol class="breadcrumb">
          <li><a href="{{ url('/home') }}"><i class="fa fa-dashboard"></i> Home</a></li>
        <li><a href="#">IT Support</a></li>
          <li class="active">Approval Control</li>
        </ol>
      </section>

    <!-- Main content -->
    <section class="content">

      <!-- <div class="row">
        <div class="col-lg-3 col-xs-6">

          <div class="small-box bg-red">
            <div class="inner">
              {{$missedproject->COUNT}}

              <p>Project Without <br>Approval Set</p>
            </div>
            <div class="icon">
              <i class="ion ion-android-folder"></i>
            </div>
            <a href="#" onclick="projectview('{{$type}}');" class="small-box-footer">More info <i class="fa fa-arrow-circle-right"></i></a>
          </div>
        </div>
      </div> -->

      <!--on leave list -->
      <div class="modal fade" id="projectview" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
          <div class="modal-dialog" role="document">
            <div class="modal-content">
              <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title" id="myModalLabel">Project without approval setting</h4>
              </div>
              <div class="modal-body" name="projectlabel" id="projectlabel">

              </div>
              <div class="modal-footer">
                <center><img src="{{ URL::to('/') ."/img/ajax-loader.gif" }}" width="50px" height="50px" alt="Loading" name='ajaxloader' id="ajaxloader"></center>
                <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
              </div>
            </div>
          </div>
      </div>

      <div class="row">
          <div class="col-md-12">
            <div class="box box-primary">
                <div class="box-body">

                  @foreach($category as $item)

                    @if ($item==$type)
                      <a href="{{ url('/approvalcontrol') }}/{{$item}}"><button type="button" class="btn btn-danger btn-small">{{$item}}</button></a>
                    @else
                      <a href="{{ url('/approvalcontrol') }}/{{$item}}"><button type="button" class="btn btn-success btn-small">{{$item}}</button></a>
                    @endif

                  @endforeach

                  <br><br>

                    <table id="approvaltable" class="display" cellspacing="0" width="100%" padding="30px" style="font-size: 13px;">
                      <thead>
                        @if(count($approvalsettings))
                        <tr class="search">
                          <td align='center'><input type='hidden' class='search_init' /></td>
                          @foreach($approvalsettings as $key=>$values)
                            @if ($key==0)

                            @foreach($values as $field=>$value)

                                <td align='center'><input type='text' class='search_init' /></td>

                            @endforeach

                            @endif

                          @endforeach
                        </tr>
                          <tr>
                            @foreach($approvalsettings as $key=>$value)

                              @if ($key==0)
                                  <td></td>

                                @foreach($value as $field=>$value)
                                    <td/>{{ $field }}</td>
                                @endforeach

                              @endif

                            @endforeach
                          </tr>

                          @endif
                      </thead>
                      <tbody>
                        @if(count($approvalsettings))

                        <?php $i = 0; ?>
                        @foreach($approvalsettings as $setting)

                          <tr id="row_{{ $i }}">
                              <td></td>
                              @foreach($setting as $key=>$value)
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

function projectview($type)
 {
    $('#projectview').modal('show');
    $("#projectlabel").html("");

    $.ajaxSetup({
       headers: { 'X-CSRF-Token' : $('meta[name=_token]').attr('content') }
    });

    $("#ajaxloader").show();

    $.ajax({
                url: "{{ url('/approval/project/') }}/{{$type}}",
                success: function(response){

                  if (response==0)
                  {
                    var message ="Failed to retrieve on project list!";
                    $("#warning-alert ul").html(message);
                    $("#warning-alert").show();

                    $("#ajaxloader").hide();
                  }
                  else {

                    var myObject = JSON.parse(response);

                    var display='<table border="1" align="center" class="projecttable" cellspacing="0" width="100%" padding="30px" style="font-size: 13px;">';
                    display+='<tr class="projectheader"><td>Project Name</td></tr>';

                    $.each(myObject, function(i,item){

                      display+="<tr>";
                      display+='<td>'+item.Project_Name+'</td>';
                      display+="</tr>";

                    });


                    $("#exist-alert").hide();

                    var myObject = JSON.parse(response);

                      $("#projectlabel").html(display);

                      $("#ajaxloader").hide();
                    }
                  }
    });

  }

</script>

@endsection
