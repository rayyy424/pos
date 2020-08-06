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

      var allocationeditor;
      var allocation;


      $(document).ready(function() {

        allocationeditor = new $.fn.dataTable.Editor( {
              ajax: {
                 "url": "{{ asset('/Include/allocation.php') }}"
               },
                table: "#allocationtable",
                idSrc: "userprojects.Id",
                fields: [
                        {
                                label: "Project:",
                                name: "userprojects.ProjectId",
                                type:  'select',
                                options: [
                                    { label :"", value: "" },
                                    @foreach($projects as $project)
                                        { label :"{{$project->Project_Name}}", value: "{{$project->Id}}" },
                                    @endforeach
                                ]

                        },
                        {
                                label: "Assigned As:",
                                name: "userprojects.Assigned_As",
                                type:  'select',
                                options: [
                                  { label :"", value: "" },

                                  ]
                        },
                        {
                                label: "Start Date:",
                                name: "userprojects.Start_Date",
                                type:   'datetime',
                                def:    function () { return new Date(); },
                                format: 'DD-MMM-YYYY'

                        },
                        {
                                label: "End Date:",
                                name: "userprojects.End_Date",
                                type:   'datetime',
                                def:    function () { return new Date(); },
                                format: 'DD-MMM-YYYY'

                        }

                ]
        } );

        $('#allocationtable').on( 'click', 'tbody td', function (e) {
              allocationeditor.inline( this, {
             onBlur: 'submit'
            } );
        } );


        allocationeditor.on( 'initEdit', function ( e, node, data ) {

          var ability=data.userability.Ability;

          if (ability!=null)
          {
            var split=ability.split(",");
            var arr=[];

            arr.push({
                value: "",
                label: ""
            });

            $.each(split, function (index) {
           arr.push({
               value: split[index],
               label: split[index]
           });
       });


              allocationeditor.field( 'userprojects.Assigned_As' ).update( arr );

          }

        } );



      allocation=  $('#allocationtable').dataTable( {
                ajax: {
                   "url": "{{ asset('/Include/allocation.php') }}"
                 },
                columnDefs: [{ "visible": false, "targets": [1,2] },{"className": "dt-center", "targets": "_all"}],
                responsive: false,
                colReorder: false,
                bAutoWidth: true,
                iDisplayLength:10,
                dom: "lfrtp",
                scrollY: "100%",
                scrollX: "100%",
                rowId: 'userprojects.Id',
                scrollCollapse: true,
                bAutoWidth: true,
                columns: [
                        { data: null, "render":"", title:"No"},
                        { data: "userprojects.Id"},
                        { data: "userability.Ability" },
                        { data: "users.StaffId" },
                        { data: "users.Name"},
                        { data: "users.Position" },
                        { data: "users.Grade" },
                        { data: "users.User_Type" },
                        { data: "users.Home_Base" },
                        { data: "projects.Project_Name", editField: "userprojects.ProjectId"},
                        { data: "userprojects.Assigned_As" },
                        { data: "userprojects.Start_Date" },
                        { data: "userprojects.End_Date" }


                ],


                select: {
                        style:    'os',
                        selector: 'td:first-child'
                },
                autoFill: {
                   editor:  allocationeditor
               }

              //  keys: {
              //      columns: ':not(:first-child)',
              //      editor:  editor
              //  },


      });

      allocation.api().on( 'order.dt search.dt', function () {
        allocation.api().column(0, {search:'applied', order:'applied'}).nodes().each( function (cell, i) {
            cell.innerHTML = i+1;
        } );
    } ).draw();

    $("thead input").keyup ( function () {

            /* Filter on the column (the index) of this element */
        if ($('#allocationtable').length > 0)
        {

            var colnum=document.getElementById('allocationtable').rows[0].cells.length;

            if (this.value=="[empty]")
            {

               allocation.fnFilter( '^$', $("thead input").index(this)-colnum,true,false );
            }
            else if (this.value=="[nonempty]")
            {

               allocation.fnFilter( '^(?=\\s*\\S).*$', $("thead input").index(this)-colnum,true,false );
            }
            else if (this.value.startsWith("!")==true && this.value.length>1)
            {

               allocation.fnFilter( '^(?'+ this.value +').*', $("thead input").index(this)-colnum,true,false );
            }
            else if (this.value.startsWith("!")==false)
            {

              allocation.fnFilter( this.value, $("thead input").index(this)-colnum,true,false );
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

           <div class="box">
             <div class="box-body">
                <table id="allocationtable" class="display" cellspacing="0" width="100%" padding="30px" style="font-size: 13px;">
                     <thead>
                       <tr class="search">

                         @foreach($allocations as $key=>$values)
                           @if ($key==0)

                             <?php $i = 0; ?>

                           @foreach($values as $field=>$a)

                               @if ( $i==0|| $i==1 ||$i==2 )
                                 <th align='center'><input type='hidden' class='search_init' name='{{$i}}' placemark='{{$a}}'></th>
                               @else
                                 <th align='center'><input type='text' class='search_init' name='{{$i}}'  placemark='{{$a}}'></th>
                               @endif

                               <?php $i ++; ?>
                           @endforeach

                            <th align='center'><input type='text' class='search_init' name='{{$i}}'  placemark='{{$a}}'></th>
                           @endif

                         @endforeach
                       </tr>

                         <tr>
                           @foreach($allocations as $key=>$value)

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



                   </tbody>
                     <tfoot></tfoot>
                 </table>
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

</script>



@endsection
