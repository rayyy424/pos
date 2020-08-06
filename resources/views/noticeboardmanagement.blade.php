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
                    ajax: "{{ asset('/Include/noticeboard.php') }}",
                    table: "#noticetable",
                    idSrc: "noticeboards.Id",
                    fields: [
                            {
                                    name: "noticeboards.UserId",
                                    default: {{$me->UserId}},
                                    type:"hidden"
                            },{
                                    label: "Title:",
                                    name: "noticeboards.Title"
                            },{
                                    label: "Content:",
                                    name: "noticeboards.Content",
                                    type: "textarea"
                            },{
                                   label: "Start Date:",
                                   name: "noticeboards.Start_Date",
                                   type:   'datetime',
                                   def:    function () { return new Date(); },
                                   format: 'DD-MMM-YYYY'
                            },{
                                   label: "End Date:",
                                   name: "noticeboards.End_Date",
                                   type:   'datetime',
                                   def:    function () { return new Date(); },
                                   format: 'DD-MMM-YYYY'
                            },{
                                   label: "Email Notification:",
                                   name: "noticeboards.Email_Notification",
                                   type:   'select',
                                   options: [
                                        { label :"No", value: "0" },
                                        { label :"Yes", value: "1" },
                                   ],
                            }

                    ]
            } );

            // Activate an inline edit on click of a table cell
            // $('#noticetable').on( 'click', 'tbody td', function (e) {
            //       editor.inline( this, {
            //      onBlur: 'submit'
            //     } );
            // } );

            editor.on( 'postEdit', function ( e, json, data ) {
                 $(this.modifier()).addClass('data-changed')


                 id=data.noticeboards.Id;
                 notification=data.noticeboards.Email_Notification;

                 if (notification==1)
                 {

                    $.ajaxSetup({
                       headers: { 'X-CSRF-Token' : $('meta[name=_token]').attr('content') }
                    });

                    $.ajax({
                                url: "{{ url('/notice/notify') }}",
                                method: "POST",
                                data: {Id:id
                                },

                                success: function(response){


                        }
                    });

                 }

             } );

            oTable=$('#noticetable').dataTable( {
                    ajax: "{{ asset('/Include/noticeboard.php') }}",
                    columnDefs: [{ "visible": false, "targets": [1,2,3] },{"className": "dt-center", "targets": "_all"}],
                    responsive: false,
                    colReorder: false,
                    sScrollX: "100%",
                    bAutoWidth: true,
                    sScrollY: "100%",
                    dom: "Bfrtip",
                    bScrollCollapse: true,
                    rowId:"noticeboards.Id",
                    order: [[ 1, "desc" ]],
                    columns: [
                            { data: null,"render":"", title:"No"},
                            { data: "noticeboards.Id"},
                            { data: "f.FileId" },
                            { data: "f.FileName" },
                            { data: "noticeboards.Title" },
                            { data: "noticeboards.Content" },
                            { data: "noticeboards.Start_Date" },
                            { data: "noticeboards.End_Date" },
                            { data: "users.Name" },
                            { data: 'noticeboards.Email_Notification',
                             "render": function ( data, type,full, meta ) {

                                if (data==0)
                                {
                                  return "No";
                                }
                                else {
                                  return "Yes";
                                }
                             }
                         },
                            {
                              data: 'Attachment',
                               "render": function ( data, type,full, meta ) {

                                  display='<button type="button" class="btn btn-danger" data-toggle="modal" onclick="OpenUploadWindow(' + full.noticeboards.Id + ')">Upload</i></button><br><br>';
                                  if (data)
                                  {
                                    split=data.split("|");
                                    split2=full.f.FileId.split("|");
                                    split3=full.f.FileName.split("|");

                                    split.forEach(function(entry,i) {
                                        if (entry.length>0)
                                        {
                                          display +="<div class='col-5' id='file"+split2[i]+"'>";
                                          display +="<a download='{{ URL::to('/') }}"+entry+"' href='{{ URL::to('/') }}"+entry+"' title='Download'>"+ split3[i]+"</a>";
                                          display +='<button type="button" class="btn btn-block btn-danger btn-xs" onclick="deletefile('+split2[i]+')">Delete</button><br>';
                                          display +="</div>";
                                        }

                                    });
                                  }
                                   return display;
                               }
                           }

                    ],

                  //  keys: {
                  //      columns: ':not(:first-child)',
                  //      editor:  editor
                  //  },
                   select: true,
                    buttons: [
                            // {
                            //   text: 'New Notice',
                            //   action: function ( e, dt, node, config ) {
                            //       // clearing all select/input options
                            //       editor
                            //          .create( false )
                            //          .set( 'noticeboards.UserId', {{ $me->UserId }} )
                            //          .submit();
                            //   },
                            // },
                            { extend: "create", text:"New Notice",editor: editor },
                            { extend: "edit",editor: editor },

                            { extend: "remove", editor: editor },
                    ],
                    autoFill: {
                       editor:  editor
                   },

                 });

              oTable.api().on( 'order.dt search.dt', function () {
                  oTable.api().column(0, {search:'applied', order:'applied'}).nodes().each( function (cell, i) {
                      cell.innerHTML = i+1;
                  } );
              } ).draw();

            // column search

              $("thead input").keyup ( function () {

                    /* Filter on the column (the index) of this element */
                    if ($('#noticetable').length > 0)
                    {

                        var colnum=document.getElementById('noticetable').rows[0].cells.length;

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
          Notice Board Management
          <small>Administration</small>
        </h1>
        <ol class="breadcrumb">
         <li><a href="{{ url('/home') }}"><i class="fa fa-dashboard"></i> Home</a></li>
        <li><a href="#">Administration</a></li>
          <li class="active">Notice Board Management</li>
        </ol>
      </section>

    <!-- Main content -->
    <section class="content">
      <div class="row">
          <div class="col-md-12">

            <div class="modal fade" id="UploadModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
              <div class="modal-dialog" role="document">
                <div class="modal-content">
                  <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                    <h4 class="modal-title" id="myModalLabel">Upload File</h4>
                  </div>
                  <div class="modal-body">
                    <div class="form-group">
                      <form enctype="multipart/form-data" id="upload_form" role="form" method="POST" action="" >
                        <input type="hidden" value="0" name="NoticeId" id="NoticeId">
                        <input type="file" id="uploadfile" name="uploadfile">
                      </form>
                    </div>
                  </div>
                  <div class="modal-footer">
                    <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                    <button type="button" class="btn btn-primary" onclick="uploadfile()">Upload</button>
                  </div>
                </div>
              </div>
            </div>

            <div class="box">
                <div class="box-body">

                    <table id="noticetable" class="display" cellspacing="0" width="100%" padding="30px" style="font-size: 13px;">
                      <thead>
                        <tr class="search">
                          <td align='center'><input type='hidden' class='search_init' /></td>
                          @foreach($notice as $key=>$values)

                            @if ($key==0)
                            <?php $i = 0; ?>

                            @foreach($values as $field=>$value)
                              @if ($field=="Id")
                                <td align='center'><input type='hidden' class='search_init' /></td>
                              @else
                                <td align='center'><input type='text' class='search_init' /></td>
                              @endif

                            @endforeach

                            @endif

                          @endforeach
                        </tr>

                          <tr>

                            @foreach($notice as $key=>$value)

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
                        @foreach($notice as $item)

                          <tr id="row_{{ $i }}">
                            <td></td>
                              @foreach($item as $key=>$value)
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

  function OpenUploadWindow(id)
  {

    $('input[name="NoticeId"]').val(id);
    $('#UploadModal').modal('show');
  }

  function uploadfile() {

      $.ajaxSetup({
         headers: { 'X-CSRF-Token' : $('meta[name=_token]').attr('content') }
      });

      $.ajax({

                  url: "{{ url('/notice/uploadfile') }}",
                  method: "POST",
                  contentType: false,
                  processData: false,
                  data:new FormData($("#upload_form")[0]),

                  success: function(response){
                    if (response==0)
                    {
                      var message ="Failed to upload file!";
                      $("#warning-alert ul").html(message);
                      $("#warning-alert").show();

                      setTimeout(function() {
                        $("#warning-alert").fadeOut();
                      }, 6000);

                      $('#UploadModal').modal('hide')

                    }
                    else {

                      var message ="File uploaded!";
                      $("#update-alert ul").html(message);
                      $("#update-alert").show();

                      setTimeout(function() {
                        $("#update-alert").fadeOut();
                      }, 6000);

                      $('input[name="NoticeId"]').val(0);

                      $("#exist-alert").hide();
                      $('#UploadModal').modal('hide')

                      $("#uploadfile").val("");

                      oTable.api().ajax.reload();

                    }

          }
      });

  }

  function deletefile(id) {
    $.ajaxSetup({
       headers: { 'X-CSRF-Token' : $('meta[name=_token]').attr('content') }
    });
    $.ajax({
                url: "{{ url('/notice/deletefile') }}",
                method: "POST",
                data: {Id:id},
                success: function(response){
                  if (response==0)
                  {
                    var message ="Failed to delete file!";
                    $("#warning-alert ul").html(message);
                    $("#warning-alert").show();

                    setTimeout(function() {
                      $("#warning-alert").fadeOut();
                    }, 6000);
                  }
                  else {
                    var message ="File deleted!";
                    $("#update-alert ul").html(message);
                    $("#update-alert").show();

                    setTimeout(function() {
                      $("#update-alert").fadeOut();
                    }, 6000);
                    //$("#Template").val(response).change();
                    $("#exist-alert").hide();

                    $("#file"+id).remove();
                  }
        }
    });
  }

  </script>

@endsection
