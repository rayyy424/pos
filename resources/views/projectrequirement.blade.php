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

      <script type="text/javascript" language="javascript" class="init">

          var requirementeditor;
          $(document).ready(function() {

            requirementeditor = new $.fn.dataTable.Editor( {
                  ajax: {
                     "url": "{{ asset('/Include/projectrequirement.php') }}",
                     "data": {
                         "ProjectId": "{{ $projectid }}"
                     }
                   },
                    table: "#requirementtable",
                    idSrc: "projectrequirements.Id",
                    fields: [
                            {
                                   name: "projectrequirements.ProjectId",
                                   type: "hidden"
                            },
                            {
                                    label: "Type:",
                                    name: "projectrequirements.Type",
                                    type:  'select',
                                    options: [
                                      { label :"", value: "" },
                                        @foreach($options as $option)
                                          @if ($option->Field=="Ability")
                                            { label :"{{$option->Option}}", value: "{{$option->Option}}" },
                                          @endif
                                        @endforeach

                                    ],

                            },
                            {
                                    label: "Requirement:",
                                    name: "projectrequirements.Requirement",
                                    attr: {
                                       type: "number"
                                     }
                            },
                            {
                                    label: "Start Date:",
                                    name: "projectrequirements.Start_Date",
                                    type:   'datetime',
                                    def:    function () { return new Date(); },
                                    format: 'DD-MMM-YYYY'

                            },
                            {
                                    label: "End Date:",
                                    name: "projectrequirements.End_Date",
                                    type:   'datetime',
                                    def:    function () { return new Date(); },
                                    format: 'DD-MMM-YYYY'

                            }


                    ]
            } );

            $('#requirementtable').on( 'click', 'tbody td', function (e) {
                  requirementeditor.inline( this, {
                 onBlur: 'submit'
                } );
            } );



            var requirementtable = $('#requirementtable').dataTable( {
              ajax: {
                 "url": "{{ asset('/Include/projectrequirement.php') }}",
                 "data": {
                     "ProjectId": "{{ $projectid }}"
                 }
               },
               columnDefs: [{ "visible": false, "targets": [1,2] },{"className": "dt-center", "targets": "_all"}],
               responsive: false,
               colReorder: true,
               stateSave:false,
               dom: "Bftp",
               iDisplayLength:10,
               bAutoWidth: true,
               rowId:"projectrequirements.Id",
               order: [[ 3, "asc" ]],
                 columns:[
                  {data: null,"render":"", title:"No"},
                  {data: "projectrequirements.Id"},
                  {data: "projectrequirements.ProjectId"},
                  {data: "projectrequirements.Type"},
                  {data: "projectrequirements.Requirement"},
                  {data: "projectrequirements.Start_Date"},
                  {data: "projectrequirements.End_Date"}
                ],
                select: true,
                autoFill: {
                   editor:  requirementeditor
               },
               buttons: [
                  { text: 'New Row',
                    action: function ( e, dt, node, config ) {
                        requirementeditor
                           .create( false )
                           .set( 'projectrequirements.ProjectId', {{$projectid}})
                           .submit();
                    },
                  },
                  { extend: "remove", editor: requirementeditor },
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

            requirementtable.api().on( 'order.dt search.dt', function () {
              requirementtable.api().column(0, {search:'applied', order:'applied'}).nodes().each( function (cell, i) {
                  cell.innerHTML = i+1;
              } );
          } ).draw();

          $("thead input").keyup ( function () {

                  /* Filter on the column (the index) of this element */
              if ($('#requirementtable').length > 0)
              {

                  var colnum=document.getElementById('requirementtable').rows[0].cells.length;

                  if (this.value=="[empty]")
                  {

                     requirementtable.fnFilter( '^$', $("thead input").index(this)-colnum,true,false );
                  }
                  else if (this.value=="[nonempty]")
                  {

                     requirementtable.fnFilter( '^(?=\\s*\\S).*$', $("thead input").index(this)-colnum,true,false );
                  }
                  else if (this.value.startsWith("!")==true && this.value.length>1)
                  {

                     requirementtable.fnFilter( '^(?'+ this.value +').*', $("thead input").index(this)-colnum,true,false );
                  }
                  else if (this.value.startsWith("!")==false)
                  {

                    requirementtable.fnFilter( this.value, $("thead input").index(this)-colnum,true,false );
                  }
              }


          } );


          });
      </script>

      @endsection

@section('content')

  <div class="content-wrapper">
      <!-- Content Header (Page header) -->
    <section class="content-header">
      <h1>
      Project Requirements
      <small>Poject Management</small>
      </h1>

      <ol class="breadcrumb">
      <li><a href="{{ url('/home') }}"><i class="fa fa-dashboard"></i> Home</a></li>
      <li><a href="#">Project Management</a></li>
      <li class="active">Project Requirements</li>
      </ol>
    </section>

    <section class="content">

      <div class="box box-success">
          <div class="box-body">

            <br><br>
              <div class="col-md-2">
                <label>Select Project : </label>
              </div>
              <div class="col-md-4">
                <div class="form-group">
                  <select class="form-control select2" id="mySelect" onchange="searchbyproject()">

                  @foreach($projects as $project)

                    @if($projectid==$project->Id)

                       <option value="{{$project->Id}}" selected>{{$project->Project_Name}}</option>

                    @else

                       <option value="{{$project->Id}}">{{$project->Project_Name}}</option>

                    @endif

                  @endforeach

                </select>
              </div>
            </div>

            <!-- @foreach($projects as $project)

              @if($projectid==$project->Id)

                <a href="{{ url('/projectrequirement') }}/{{$project->Id}}"><button type="button" class="btn btn-danger .btn-xs">{{$project->Project_Name}}</button></a>

              @else

                <a href="{{ url('/projectrequirement') }}/{{$project->Id}}"><button type="button" class="btn btn-primary .btn-xs">{{$project->Project_Name}}</button></a>

              @endif

            @endforeach -->


          </div>

          <br><br>
          <div class="tab-pane" id="requirement">
            <table id="requirementtable" class="display" cellspacing="0" width="100%" padding="30px" style="font-size: 13px;">
                <thead>
                  <tr class="search">
                    <td align='center'><input type='hidden' class='search_init' /></td>
                    @foreach($requirement as $key=>$values)
                      @if ($key==0)

                      @foreach($values as $field=>$value)

                          <td align='center'><input type='text' class='search_init' /></td>

                      @endforeach

                      @endif

                    @endforeach
                  </tr>
                    <tr>
                      @if($requirement!="")
                        @foreach($requirement as $key=>$value)

                          @if ($key==0)
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

                  @if($requirement!="")

                    <?php $i = 0; ?>
                    @foreach($requirement as $view)

                      <tr id="row_{{ $i }}">
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

function searchbyproject(){

  var projectid = document.getElementById("mySelect").value;
  window.location.href ="{{ url("/projectrequirement") }}/"+projectid;

}

$(function () {

  //Initialize Select2 Elements
  $(".select2").select2();

});

</script>



@endsection
