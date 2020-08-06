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

      h3{
        color: red;
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
        var aging;

        $(document).ready(function() {
          // aging=$('#aging').dataTable( {
          //         responsive: false,
          //         colReorder: false,
          //         dom: "Brt",
          //         bAutoWidth: true,
          //         "bScrollCollapse": true,
          //         columnDefs: [{ "visible": false, "targets": [2,3] }],
          //         bScrollCollapse: true,
          //         columns: [
          //                 { title:"Action",
          //                 "render": function ( data, type, full, meta ) {
          //                    return '<a href="{{ url("tracker/agingpreview") }}/'+full.agings.Id+'" target="_blank">Preview</a>';
          //
          //                 }},
          //                 { data: null, render:"", title:"No"},
          //                 { data: "agings.Id" },
          //                 { data: "agings.ProjectId" },
          //                 { data: "agings.Active",
          //                 "render": function ( data, type, full, meta ) {
          //                     if (full.agings.Active==1)
          //                        return 'Yes';
          //                     else
          //                        return 'No';
          //                     endif
          //
          //                 }},
          //                 { data: "projects.Project_Name",Title:"Project_Name", editField: "agings.ProjectId"},
          //                 { data: "agings.Title",Title:"Title"},
          //                 { data: "agings.Type",Title:"Type"},
          //                 { data: "agings.Start_Date" ,Title:"Start_Date"},
          //                 { data: "agings.End_Date" ,Title:"End_Date"},
          //                 { data: "agings.Threshold" ,Title:"Threshold (days)"},
          //                 { data: "agings.Recurring_Frequency" ,Title:"Recurring_Frequency"},
          //                 { data: "agings.Frequency_Unit" ,Title:"Frequency_Unit"},
          //                 { data: "creator.Name",Title:"Creator" },
          //                 { data: "users", render: "[<br> ].Name",Title:"Subscriber",editField: "agingsubscribers.UserId"},
          //
          //         ],
          //         autoFill: {
          //                // columns: ':not(:first-child)',
          //         },
          //         select: {
          //                 style:    'os',
          //                 selector: 'td:first-child'
          //         },
          //         buttons: [
          //
          //         ],
          //   });
          //
          //   aging.api().on( 'order.dt search.dt', function () {
          //     aging.api().column(1, {search:'applied', order:'applied'}).nodes().each( function (cell, i) {
          //         cell.innerHTML = i+1;
          //     } );
          // } ).draw();

          $("thead input").keyup ( function () {

                  /* Filter on the column (the index) of this element */
                  if ($('#aging').length > 0)
                  {

                      var colnum=document.getElementById('aging').rows[0].cells.length;

                      if (this.value=="[empty]")
                      {

                         aging.fnFilter( '^$', $("thead input").index(this)-colnum,true,false );
                      }
                      else if (this.value=="[nonempty]")
                      {

                         aging.fnFilter( '^(?=\\s*\\S).*$', $("thead input").index(this)-colnum,true,false );
                      }
                      else if (this.value.startsWith("!")==true && this.value.length>1)
                      {

                         aging.fnFilter( '^(?'+ this.value +').*', $("thead input").index(this)-colnum,true,false );
                      }
                      else if (this.value.startsWith("!")==false)
                      {

                        aging.fnFilter( this.value, $("thead input").index(this)-colnum,true,false );
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
        {{$project->Project_Name}} Customized Report
        <small>Project Management</small>
      </h1>

      <ol class="breadcrumb">
      <li><a href="{{ url('/home') }}"><i class="fa fa-dashboard"></i> Home</a></li>
      <li><a href="#">Project Management</a></li>
      <li class="active">{{$project->Project_Name}} Customized Report</li>
      </ol>
    </section>

    <section class="content">

      <div class="row">
        <div class="box box-primary">
          <div class="box-body">

              <div class="row">

                <div class="col-md-4">

                  <div class="form-group">
                   <label>Region : </label>
                   <select class="form-control select2" id="Region" name="Region" style="width: 100%;">
                     <option value=null>All</option>

                     @foreach($regions as $item)

                         { label :"{{$item->Region}}", value: "{{$item->Region}}" },
                         <option <?php if($item->Region==$region) echo ' selected="selected" '; ?>>{{$item->Region}}</option>
                     @endforeach

                     </select>
                   </div>

                </div>

                <div class="col-md-1">

                  <br>

                  <div class="input-group">
                    <button type="button" class="btn btn-success btn-lg" data-toggle="modal" onclick="refresh();">Refresh</button>
                  </div>

                </div>

              </div>

              @if($region!="null" &&$region!=null)

                <h3>Filter : Region = {{$region}} </h3>

              @endif



                @for ($i=0; $i <count($chartarr) ; $i++)

                  @if($chartview[$i]->Chart_View_Type=="Weekly")

                    <div class="col-md-6">
                      <h4>{{$chartview[$i]->Chart_View_Name}}</h4>
                      <canvas id="Chart{{$i}}" style="height:300px;"></canvas>
                    </div>

                  @elseif($chartview[$i]->Chart_View_Type=="Total")

                    <div class="col-md-6">
                      <h4>{{$chartview[$i]->Chart_View_Name}}</h4>
                      <canvas id="Chart{{$i}}" style="height:300px;"></canvas>
                    </div>

                  @endif

                @endfor



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

  $(function () {

    //Initialize Select2 Elements
    $(".select2").select2();

  });


$(function () {

  @for ($i=0; $i <count($chartarr) ; $i++)

    var str = "{{$chartarr[$i][0]}}";
       str = str.split(",").map(function(str){
             return str;
         })
     var a=[str];
     var line = document.getElementById("Chart{{$i}}");

     var linechart = new Chart(line, {
         type: "bar",
         data: {
           labels:a[0],
           datasets: [
             @for ($j=0; $j <count($chartarr[$i][2]) ; $j++)
             {
               label: '{{$chartarr[$i][3][$j]}}',
               data: [{{$chartarr[$i][1][$j]}}],
               borderColor: '{{$chartarr[$i][4][$j]}}',
               pointBackgroundColor: '{{$chartarr[$i][4][$j]}}',
               backgroundColor: '{{$chartarr[$i][4][$j]}}',
               fill:false,
               type:"{{$chartarr[$i][2][$j]}}"

             },

             @endfor
           ],
         },

         options : {
           scales : {
               xAxes : [ {
                   gridLines : {
                       display : false
                   }
               } ],
               yAxes : [ {
                   ticks: {
                    beginAtZero: true,
                    userCallback: function(label, index, labels) {
                      if (Math.floor(label) === label) {
                        return label;
                      }

                    },
                  },
                   gridLines : {
                       display : true
                   }
               } ]
           }
       }
       });

  @endfor

});

function refresh()
{
  var region=$('#Region').val();

  window.location.href ="{{ url("/projectdashboard") }}/{{$projectid}}/"+region;

}

</script>

@endsection
