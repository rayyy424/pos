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


      $(document).ready(function() {

      var summary = $('#summary').dataTable( {
              //ajax: "{{ asset('/Include/project.php') }}",
              columnDefs: [{ "visible": false, "targets": [1] },{"className": "dt-center", "targets": "_all"}],
              responsive: false,
              colReorder: false,
              bAutoWidth: true,
              dom: "frtp",
              scrollY: "100%",
              scrollX: "100%",
              scrollCollapse: true,
              rowId: 'Projectid',
              bAutoWidth: true,
              columns:[
                { data: null, "render":"", title: "No"},
                { data : "userprojects.ProjectId" },
                { data : "Projects.Project_Name" },
                { data : "Staff",
                  "render": function ( data, type, full, meta ) {
                    return "<a href='#' onclick='viewdatatype("+full.userprojects.ProjectId+",\"Staff\");'>"+data+"</a>";
                  }
                },
                { data : "Interns",
                  "render": function ( data, type, full, meta ) {
                    return "<a href='#' onclick='viewdatatype("+full.userprojects.ProjectId+",\"Assistant Engineer\");'>"+data+"</a>";
                  }},
                { data : "DTA",
                  "render": function ( data, type, full, meta ) {
                    return "<a href='#' onclick='viewdataposition("+full.userprojects.ProjectId+",\"DTA\");'>"+data+"</a>";
                  } },
                { data : "DTE",
                  "render": function ( data, type, full, meta ) {
                    return "<a href='#' onclick='viewdataposition("+full.userprojects.ProjectId+",\"DTE\");'>"+data+"</a>";
                  }}

              ],
              select: {
                      style:    'os',
                      selector: 'td:first-child'
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
              ]

            //  keys: {
            //      columns: ':not(:first-child)',
            //      editor:  editor
            //  },


    });

        summary.api().on( 'order.dt search.dt', function () {
            summary.api().column(0, {search:'applied', order:'applied'}).nodes().each( function (cell, i) {
                cell.innerHTML = i+1;
            } );
        } ).draw();

      $("thead input").keyup ( function () {

              /* Filter on the column (the index) of this element */
          if ($('#summary').length > 0)
          {

              var colnum=document.getElementById('summary').rows[0].cells.length;

              if (this.value=="[empty]")
              {

                 summary.fnFilter( '^$', $("thead input").index(this)-colnum,true,false );
              }
              else if (this.value=="[nonempty]")
              {

                 summary.fnFilter( '^(?=\\s*\\S).*$', $("thead input").index(this)-colnum,true,false );
              }
              else if (this.value.startsWith("!")==true && this.value.length>1)
              {

                 summary.fnFilter( '^(?'+ this.value +').*', $("thead input").index(this)-colnum,true,false );
              }
              else if (this.value.startsWith("!")==false)
              {

                summary.fnFilter( this.value, $("thead input").index(this)-colnum,true,false );
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
      Resource Allocation
      <small>Admin</small>
    </h1>
    <ol class="breadcrumb">
      <li><a href="{{ url('/home') }}"><i class="fa fa-dashboard"></i> Home</a></li>
    <li><a href="#">Project Management</a></li>
      <li class="active">Resource Allocation</li>
    </ol>
  </section>

  <section class="content">

    <div class="modal fade" id="NameList" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
        <div class="modal-dialog" role="document">
          <div class="modal-content">
            <div class="modal-header">
              <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
              <h4 class="modal-title" id="myModalLabel">Name List</h4>
            </div>
            <div class="modal-body" name="list" id="list">

            </div>
            <div class="modal-footer">
              <center><img src="{{ URL::to('/') ."/img/ajax-loader.gif" }}" width="50px" height="50px" alt="Loading" name='ajaxloader3' id="ajaxloader3"></center>
              <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
            </div>
          </div>
        </div>
    </div>




    <div class="row">
       <div class="col-md-12">

          <div id="update-alert" class="alert alert-success alert-dismissible" style="display:none;">
            <button type="button" class="close" onclick="$('#update-alert').hide()" aria-hidden="true">&times;</button>
            <h4><i class="icon fa fa-check"></i> Alert!</h4>
            <ul>

            </ul>
          </div>

           <div id="warning-alert" class="alert alert-warning alert-dismissible" style="display:none;">
             <button type="button" class="close" onclick="$('#warning-alert').hide()">&times;</button>
             <h4><i class="icon fa fa-ban"></i> Alert!</h4>
             <ul>

             </ul>
           </div>


      </div>

           <!-- BAR CHART -->
             <!-- <div class="col-md-12">
               <div class="box box-primary">
                 <div class="box-header with-border">
                   <h3 class="box-title"></h3>

                 </div>
                 <div class="box-body">
                   <div class="chart">
                     <canvas id="lineChart" style="height:300px;"></canvas>
                   </div>
                 </div>
               </div>
             </div> -->
               <!-- /.box -->
      <div class="col-md-12">
           <div class="box">
             <div class="box-body">
                <table id="summary" class="display" cellspacing="0" width="100%" padding="30px" style="font-size: 13px;">
                     <thead>
                       <tr class="search">
                         <td align='center'><input type='hidden' class='search_init' /></td>
                         @foreach($summary as $key=>$values)
                           @if ($key==0)

                           @foreach($values as $field=>$value)

                               <td align='center'><input type='text' class='search_init' /></td>

                           @endforeach

                           @endif

                         @endforeach
                       </tr>
                         <tr>
                           @foreach($summary as $key=>$value)

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
                       @foreach($summary as $count)

                         <tr id="row_{{ $i }}">
                              <td></td>
                             @foreach($count as $key=>$value)
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




</div>

<footer class="main-footer">
  <div class="pull-right hidden-xs">
    <b>Version</b> 2.0.1
  </div>
  <strong>Copyright &copy; 2014-2016 <a href="http://www.softoya.com">TrackerOnTheGo</a>.</strong> All rights
  reserved.
</footer>

<script>
function viewdatatype(projectid,type)
{
   $('#NameList').modal('show');
   $("#list").html("");

   $.ajaxSetup({
      headers: { 'X-CSRF-Token' : $('meta[name=_token]').attr('content') }
   });

   $("#ajaxloader3").show();

   $.ajax({
               url: "{{ url('allocation/namelisttype') }}",
               method: "POST",
               data: {
                 ProjectId:projectid,
                 User_Type:type
               },
               success: function(response){
                 if (response==0)
                 {

                   var message ="Failed to retrieve intern list!";
                   $("#warning-alert ul").html(message);
                   $("#warning-alert").show();
                   $('#ReturnedModal').modal('hide')

                   $("#ajaxloader3").hide();
                 }
                 else {
                   $("#exist-alert").hide();

                   var myObject = JSON.parse(response);

                   var display='<table border="1" align="center" class="interntable" cellspacing="0" width="100%" padding="30px" style="font-size: 13px;">';
                   display+='<tr class="tableheader"><td>Name</td><td>User Type</td><td>Position</td></tr>';

                   $.each(myObject, function(i,item){

                     display+="<tr>";
                     display+='<td>'+item.Name+'</td><td>'+item.User_Type+'</td><td>'+item.Position+'</td>';
                     display+="</tr>";

                   });

                   $("#list").html(display);

                   $("#ajaxloader3").hide();
                 }
       }
   });

 }

function viewdataposition(projectid,position)
 {
    $('#NameList').modal('show');
    $("#list").html("");

    $.ajaxSetup({
       headers: { 'X-CSRF-Token' : $('meta[name=_token]').attr('content') }
    });

    $("#ajaxloader3").show();

    $.ajax({
                url: "{{ url('allocation/namelistposition') }}",
                method: "POST",
                data: {
                  ProjectId:projectid,
                  Position:position
                },
                success: function(response){
                  if (response==0)
                  {

                    var message ="Failed to retrieve intern list!";
                    $("#warning-alert ul").html(message);
                    $("#warning-alert").show();
                    $('#ReturnedModal').modal('hide')

                    $("#ajaxloader3").hide();
                  }
                  else {
                    $("#exist-alert").hide();

                    var myObject = JSON.parse(response);

                    var display='<table border="1" align="center" class="interntable" cellspacing="0" width="100%" padding="30px" style="font-size: 13px;">';
                    display+='<tr class="tableheader"><td>Name</td><td>User Type</td><td>Position</td></tr>';

                    $.each(myObject, function(i,item){

                      display+="<tr>";
                      display+='<td>'+item.Name+'</td><td>'+item.User_Type+'</td><td>'+item.Position+'</td>';
                      display+="</tr>";

                    });

                    $("#list").html(display);

                    $("#ajaxloader3").hide();
                  }
        }
    });

  }

  // $(function () {
  //   var str = "{{$title}}";
  //      str = str.split(",").map(function(str){
  //            return str;
  //        })
  //    var a=[str];
  //    var line = document.getElementById("lineChart");
  //      var linechart = new Chart(line, {
  //        type: 'line',
  //        data: {
  //          labels:a[0],
  //          datasets: [{
  //            type: 'line',
  //            label: 'staff',
  //            data: [{{$data1}}],
  //            borderColor: "#3c8dbc",
  //            pointBackgroundColor: "#3c8dbc",
  //            fill:false,
  //          },
  //          {
  //            type: 'line',
  //            label: 'intern',
  //            data: [{{$data2}}],
  //            borderColor: "green",
  //            pointBackgroundColor: "#3c8dbc",
  //            fill:false,
  //          },
  //          {
  //            type: 'line',
  //            label: 'DTA',
  //            data: [{{$data3}}],
  //            borderColor: "red",
  //            pointBackgroundColor: "#3c8dbc",
  //            fill:false,
  //          },
  //          {
  //            type: 'line',
  //            label: 'DTE',
  //            data: [{{$data4}}],
  //            borderColor: "grey",
  //            pointBackgroundColor: "#3c8dbc",
  //            fill:false,
  //          }]
  //        },
  //        options : {
  //          scales : {
  //              xAxes : [ {
  //                  gridLines : {
  //                      display : false
  //                  }
  //              } ],
  //              yAxes : [ {
  //                  gridLines : {
  //                      display : false
  //                  }
  //              } ]
  //          }
  //      }
  //      });
  //
  //      $("#lineChart").click(
  //
  //      function(evt){
  //         //
  //         var activePoints = linechart.getElementsAtEvent(evt);
  //         var firstPoint = activePoints[0];
  //         var index = firstPoint._index;
  //         var dataset = linechart.data.labels[index];
  //         // console.log(dataset);
  //
  //         var a=linechart.getDatasetAtEvent(evt);
  //         var b = a[0];
  //         var c = b._datasetIndex;
  //         var label = linechart.data.datasets[c].label;
  //
  //         // console.log(label);
  //       alert(dataset)
  //
  //       });
  //
  //
  // });




</script>



@endsection
