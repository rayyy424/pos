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
      .DTED_Lightbox_Wrapper{
        margin-top: 20px;
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
                         "url": "{{ asset('/Include/notificationmaintenance.php') }}"
                       },
                        table: "#notificationtable",
                        fields: [
                                {
                                        label: "Id",
                                        name: "notificationtype.Id",

                                        type: "hidden"
                                },
                                {
                                        label: "Notification Name :",
                                        name: "notificationtype.Notification_Name"
                                },
                                {
                                        label: "Notification Description :",
                                        name: "notificationtype.Description"
                                },
                                {
                                        label: "Notification Subject :",
                                        name: "notificationtype.Notification_Subject"
                                },
                                {
                                        label: "Notification Content :",
                                        name: "notificationtype.Notification_Content"
                                },
                                {
                                        label: "Users List:",
                                        name: "users[].Id",
                                        type:  'checkbox'


                                }

                        ]
                 } );

                 editor.on('open', function () {
                    $('div.DTE_Footer').css( 'text-indent', -1 );
                });


                 var notify = $('#notificationtable').dataTable( {

                   ajax: {
                      "url": "{{ asset('/Include/notificationmaintenance.php') }}"
                    },
                   dom: "Bfrtp",
                   bAutoWidth: false,
                  //  rowId:"userability.Id",
                   //aaSorting:false,
                   columnDefs: [{ "visible": false, "targets": [1] },{"className": "dt-center", "targets": [0,1,2,3,4,5]}],
                   bScrollCollapse: true,
                   columns: [
                           { data: null, render:"", title:"No"},
                           { data: "notificationtype.Id" },
                           { data: "notificationtype.Notification_Name" },
                           { data: "notificationtype.Description"},
                           { data: "notificationtype.Notification_Subject" },
                           { data: "notificationtype.Notification_Content" },
                           { data: "users", render: "[<br> ].Name", title:'Subscriber_Name'}

                   ],

                   select: {
                           style:    'os',
                           selector: 'td'
                   },
                   autoFill: {
                      editor:  editor
                  },
                  buttons: [
                          // { text: 'New Row',extend: "create",editor: editor},
                          { extend: "edit", editor: editor },
                          // { extend: "remove", editor: editor },
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
                  notify.api().column(0, {search:'applied', order:'applied'}).nodes().each( function (cell, i) {
                      cell.innerHTML = i+1;
                  } );
              } ).draw();

              $("thead input").keyup ( function () {

                      /* Filter on the column (the index) of this element */
                      if ($('#notificationtable').length > 0)
                      {

                          var colnum=document.getElementById('notificationtable').rows[0].cells.length;

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
      Notification Maintenance
      <small>IT Support</small>
      </h1>

      <ol class="breadcrumb">
      <li><a href="{{ url('/home') }}"><i class="fa fa-dashboard"></i> Home</a></li>
      <li><a href="#">IT Support</a></li>
      <li class="active">Notification Maintenance</li>
      </ol>
    </section>

    <section class="content">
      <div class="row">
        <div class="box box-primary">
          <div class="box-body">
            <div class="col-md-12">


              <table id="notificationtable" class="display" cellspacing="0" width="100%" padding="30px" style="font-size: 13px;">

                  <thead>
                    @if($notification)
                      <tr class="search">
                        <td align='center'><input type='hidden' class='search_init' /></td>
                        @foreach($notification as $key=>$values)
                          @if ($key==0)

                          @foreach($values as $field=>$value)

                              <td align='center'><input type='text' class='search_init' /></td>

                          @endforeach

                          @endif

                        @endforeach

                    </tr>
                    @endif
                      <tr>
                        @if($notification)
                          @foreach($notification as $key=>$value)

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

</script>



@endsection
