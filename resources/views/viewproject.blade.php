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
      {{-- chart js --}}
      <script type="text/javascript" language="javascript" src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.4.0/Chart.bundle.min.js"></script>

      <script type="text/javascript" language="javascript" class="init">
      var cal;
      var totalrequired;
      var totalassigned;
      var totalavailable;
      $(document).ready(function() {


                 cal = $('#viewproject').DataTable( {

                     dom: "Brtp",
                     bAutoWidth: true,
                     //rowId:"userability.Id",
                     aaSorting:false,
                     bPaginate:false,
                     columnDefs: [{ "visible": false, "targets": [] },{"className": "dt-center", "targets": "_all"}],
                     bScrollCollapse: true,
                     scrollY: "100%",
                     scrollX: "100%",
                     scrollCollapse: true,

                     columns: [
                            { data: "Project_Name", name:"Project_Name", title: "Total"},
                            { data : "{{$start}}", titlte : "{{$start}}", name : "{{$start}}"},
                            @foreach($daterange as $range)
                            { data : "{{$range}}", titlte : "{{$range}}", name : "{{$range}}"},
                            @endforeach

                     ],
                     select: {
                             style:    'os',
                             selector: 'td:first-child'
                     },
                     autoFill: {

                    },
                    buttons: [
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

                totalrequired = $('#totalrequired').DataTable( {

                    dom: "Brtp",
                    bAutoWidth: true,
                    //rowId:"userability.Id",
                    aaSorting:false,
                    bPaginate:false,
                    columnDefs: [{ "visible": false, "targets": [0] },{"className": "dt-center", "targets": "_all"}],
                    bScrollCollapse: true,
                    scrollY: "100%",
                    scrollX: "100%",
                    scrollCollapse: true,
                    columns: [
                           { data: "ProjectId", name:"ProjectId"},
                           { data: "User_Type", name:"User_Type"},
                           { data : "{{$start}}", titlte : "{{$start}}", name : "{{$start}}"},
                           @foreach($daterange as $range)
                           { data : "{{$range}}", titlte : "{{$range}}", name : "{{$range}}"},
                           @endforeach

                    ],
                    select: {
                            style:    'os',
                            selector: 'td:first-child'
                    },
                    autoFill: {

                   },
                   buttons: [
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

               totalassigned = $('#totalassigned').DataTable( {

                   dom: "Brtp",
                   bAutoWidth: true,
                   //rowId:"userability.Id",
                   aaSorting:false,
                   bPaginate:false,
                   columnDefs: [{ "visible": false, "targets": [] },{"className": "dt-center", "targets": "_all"}],
                   bScrollCollapse: true,
                   scrollY: "100%",
                   scrollX: "100%",
                   scrollCollapse: true,
                   columns: [

                          { data: "Assigned_As", name:"Assigned_As"},
                          { data : "{{$start}}", titlte : "{{$start}}", name : "{{$start}}"},
                          @foreach($daterange as $range)
                          { data : "{{$range}}", titlte : "{{$range}}", name : "{{$range}}"},
                          @endforeach

                   ],
                   select: {
                           style:    'os',
                           selector: 'td:first-child'
                   },
                   autoFill: {

                  },
                  buttons: [
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

              totalavailable = $('#totalavailable').DataTable( {

                  dom: "Brtp",
                  bAutoWidth: true,
                  //rowId:"userability.Id",
                  aaSorting:false,
                  bPaginate:false,
                  columnDefs: [{ "visible": false, "targets": [] },{"className": "dt-center", "targets": "_all"}],
                  bScrollCollapse: true,
                  scrollY: "100%",
                  scrollX: "100%",
                  scrollCollapse: true,
                  columns: [
                         { data: "User_Type", name:"User_Type"},
                         { data : "{{$start}}", titlte : "{{$start}}", name : "{{$start}}"},
                         @foreach($daterange as $range)
                         { data : "{{$range}}", titlte : "{{$range}}", name : "{{$range}}"},
                         @endforeach

                  ],
                  select: {
                          style:    'os',
                          selector: 'td:first-child'
                  },
                  autoFill: {

                 },
                 buttons: [
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



          } );
      </script>

      @endsection

@section('content')

  <div class="content-wrapper">
      <!-- Content Header (Page header) -->
    <section class="content-header">
      <h1>
      {{$project->Project_Name}}
      <small>Resource Calendar</small>
      </h1>

      <ol class="breadcrumb">
      <li><a href="{{ url('/home') }}"><i class="fa fa-dashboard"></i> Home</a></li>
      <li><a href="#">Project Management</a></li>
      <li>Resource Calendar</li>
      <li class="active">{{$project->Project_Name}}</li>
      </ol>
    </section>

    <section class="content">
      <div class="row">

        <div class="box box-success">
          <br>

          <div class="col-md-6">
            <div class="input-group">
              <div class="input-group-addon">
                <i class="fa fa-clock-o"></i>
              </div>
              <input type="text" class="form-control" id="range" name="range">

            </div>
          </div>

          <div class="col-md-6">
            <div class="input-group">
              <button type="button" class="btn btn-success btn" data-toggle="modal" onclick="refresh();">Refresh</button>
            </div>
          </div>

          <div class="box-body">

            <br><br>
            @if($role=="All")
              <a href="{{ url('/resourcecalendar') }}/{{$start}}/{{$end}}/All/{{$projectid}}"><button type="button" class="btn btn-danger .btn-xs">All</button></a>

            @else
              <a href="{{ url('/resourcecalendar') }}/{{$start}}/{{$end}}/All/{{$projectid}}"><button type="button" class="btn btn-primary .btn-xs">All</button></a>

            @endif

            @foreach($options as $option)

              @if ($option->Field=="Ability")

                @if($option->Option==$role)
                  <a href="{{ url('/resourcecalendar') }}/{{$start}}/{{$end}}/{{$option->Option}}/{{$projectid}}"><button type="button" class="btn btn-danger .btn-xs">{{$option->Option}}</button></a>

                @else
                  <a href="{{ url('/resourcecalendar') }}/{{$start}}/{{$end}}/{{$option->Option}}/{{$projectid}}"><button type="button" class="btn btn-primary .btn-xs">{{$option->Option}}</button></a>

                @endif

              @endif

            @endforeach

          </div>
          <!-- <span id='abc'></span> -->
          <div class="row">
           <div class="col-md-12">
             <table id="viewproject" class="display" cellspacing="0" width="100%" padding="30px" style="font-size: 13px;">
               <thead>
                   {{-- prepare header search textbox --}}
                   <tr>
                     @foreach($viewproject as $key=>$value)

                       @if ($key==0)

                         @foreach($value as $field=>$value)
                             <td/>{{ $field }}</td>
                         @endforeach

                       @endif


                     @endforeach

                   </tr>



               </thead>
               <tbody>

                 <?php $i = 0; ?>
                 @foreach($viewproject as $viewprojects)

                  @if($viewprojects->Project_Name=="Total Available")
                     <tr id="row_{{ $i }}" style="background-color:yellow">
                  @elseif($viewprojects->Project_Name=="Total Required")
                    <tr id="row_{{ $i }}" style="background-color:#839ce4">
                  @else
                    <tr id="row_{{ $i }}">
                  @endif


                   @foreach($viewprojects as $key=>$value)
                     <td>
                       {{ $value }}
                     </td>
                   @endforeach


                   </tr>

                   <?php $i++; ?>
                 @endforeach

                     @foreach($assign as $assign1)

                       <tr id="row_{{ $i }}">

                         @foreach($assign1 as $key=>$value)
                           <td>
                             {{ $value }}
                           </td>
                         @endforeach

                     </tr>

                     @endforeach

                     <tr>
                       <td>(+/-)</td>
                       @foreach($viewprojects as $key=>$value)
                         @if ($key!="Project_Name")
                           <td><?php echo $viewproject[0]->$key-$assign1->$key; ?></td>
                         @endif


                       @endforeach
                     </tr>
             </tbody>
               <tfoot></tfoot>
           </table>

           </div>



         </div>



        </div>
      </div>

      <br>
      @if($totalavailable)
      <div class="row">
        <div class="box box-body">
          <div class="box-header with-border">
            <h3 class="box-title">Total Available</h3>
          </div>
          <div class="row">
           <div class="col-md-12">
             <table id="totalavailable" class="display" cellspacing="0" width="100%" padding="30px" style="font-size: 13px;">
                 <thead>
                     {{-- prepare header search textbox --}}
                     <tr>

                       @foreach($totalavailable as $key=>$value)

                         @if ($key==0)

                           @foreach($value as $field=>$value)
                               <td/>{{ $field }}</td>
                           @endforeach

                         @endif

                       @endforeach
                     </tr>
                 </thead>
                 <tbody>

                   <?php $i = 0; ?>
                   @foreach($totalavailable as $available)

                         <tr id="row_{{ $i }}" >

                             @foreach($available as $key=>$value)
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
      @endif
      <br>

      @if($totalrequired)
      <div class="row">
        <div class="box box-body">
          <div class="box-header with-border">
            <h3 class="box-title">Total Required</h3>
          </div>
          <div class="row">
           <div class="col-md-12">
             <table id="totalrequired" class="display" cellspacing="0" width="100%" padding="30px" style="font-size: 13px;">
                 <thead>
                     {{-- prepare header search textbox --}}
                     <tr>

                       @foreach($totalrequired as $key=>$value)

                         @if ($key==0)

                           @foreach($value as $field=>$value)
                               <td/>{{ $field }}</td>
                           @endforeach

                         @endif

                       @endforeach
                     </tr>
                 </thead>
                 <tbody>

                   <?php $i = 0; ?>
                   @foreach($totalrequired as $require)

                         <tr id="row_{{ $i }}" >

                             @foreach($require as $key=>$value)
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
      @endif
      <br>

      @if($totalassigned)
      <div class="row">
        <div class="box box-body">
          <div class="box-header with-border">
            <h3 class="box-title">Total Assigned</h3>
          </div>
          <div class="row">
           <div class="col-md-12">
             <table id="totalassigned" class="display" cellspacing="0" width="100%" padding="30px" style="font-size: 13px;">
                 <thead>
                     {{-- prepare header search textbox --}}
                     <tr>

                       @foreach($totalassigned as $key=>$value)

                         @if ($key==0)

                           @foreach($value as $field=>$value)
                               <td/>{{ $field }}</td>
                           @endforeach

                         @endif

                       @endforeach
                     </tr>
                 </thead>
                 <tbody>

                   <?php $i = 0; ?>
                   @foreach($totalassigned as $assign)

                         <tr id="row_{{ $i }}" >

                             @foreach($assign as $key=>$value)
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
      @endif

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

$(function () {

 $('#range').daterangepicker({locale: {
    format: 'DD-MMM-YYYY'
  },startDate: '{{$start}}',
  endDate: '{{$end}}'});

});

function refresh()
{
  var d=$('#range').val();
  var arr = d.split(" - ");

  window.location.href ="{{ url("/resourcecalendar") }}/"+arr[0]+"/"+arr[1]+"/"+"{{$role}}"+"/"+"{{$projectid}}";

}

</script>



@endsection
