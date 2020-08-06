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
      <script type="text/javascript" language="javascript" src="{{ asset('/plugin/datatables/extensions/Autocomplete/editor.autoComplete.js') }}"></script>
      <script type="text/javascript" language="javascript" src="{{ asset('/plugin/datatables/extensions/Select2/editor.select2.js') }}"></script>


      {{-- chart js --}}
      <script type="text/javascript" language="javascript" src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.4.0/Chart.bundle.min.js"></script>

      <script type="text/javascript" language="javascript" class="init">

         var listtable;
         var listeditor;
         var rangetable;

         $(document).ready(function() {

           listtable = $('#list').dataTable( {
                   dom: "Blrftip",
                   bAutoWidth: true,
                   // aaSorting:false,
                   sScrollY: "100%",
                   sScrollX: "100%",
                   columnDefs: [{ "visible": false, "targets": [1] },{"className": "dt-center", "targets": "_all"}],
                   bScrollCollapse: true,
                   columns: [
                     {data: null, title:"No"},
                     {data:"billing.Id", title:"Id"},
                     {data:"billing.bill_no", title:"Bill #"},
                     {data:"billing.bill_date", title:"Date"},
                     {data:"billing.amount", title:"Amount(RM)"},
                     {data:"users.Name", title:"Update_By"},
                     {data:"billing.type", title:"Type"},
                      {data:"button", title:"Action",
                        "render": function ( data, type, full, meta ) {
                           var files = getRowFiles(full.billing.Id);
                          if (files && files.length > 0) {
                            var display = "";
                            for(var i =0; i < files.length; i++) {
                              display += '<a href="'+ files[i].Web_Path +'" target="_blank">View</a> | <a href="#" onclick="removefile('+files[i].Id+','+files[i].TargetId+')">Remove</a><br>';
                            }
                            return display;
                          }
                          return '<button class="upload btn btn-default btn-xs" title="Upload" style="width:unset" id="'+full.billing.Id+'"><i class="fa fa-upload"></i></button>';
                        }
                      }

                   ],
                   autoFill: {
                  },
                  select: true,
                  buttons: [

                  ],

               });

               listtable.api().on( 'order.dt search.dt', function () {
                   listtable.api().column(0, {search:'applied', order:'applied'}).nodes().each( function (cell, i) {
                       cell.innerHTML = i+1;
                   } );
               } ).draw();



               $(".list thead input").keyup ( function () {

                       /* Filter on the column (the index) of this element */
                       if ($('#list').length > 0)
                       {

                           var colnum=document.getElementById('list').rows[0].cells.length;

                           if (this.value=="[empty]")
                           {

                              listtable.fnFilter( '^$', this.name,true,false );
                           }
                           else if (this.value=="[nonempty]")
                           {

                              listtable.fnFilter( '^(?=\\s*\\S).*$', this.name,true,false );
                           }
                           else if (this.value.startsWith("!")==true && this.value.length>1)
                           {

                              listtable.fnFilter( '^(?'+ this.value +').*', this.name,true,false );
                           }
                           else if (this.value.startsWith("!")==false)
                           {

                               listtable.fnFilter( this.value, this.name,true,false );
                           }
                       }



               } );


               $('a[data-toggle="tab"]').on( 'shown.bs.tab', function (e) {
                 var target = $(e.target).attr("href") // activated tab

                   $.fn.dataTable.tables( {visible: true, api: true} ).columns.adjust();

               } );

             } );

     </script>

      @endsection

@section('content')

  <div class="content-wrapper">
      <!-- Content Header (Page header) -->
    <section class="content-header">
      <h1>
        Billing
      <small>Asset Management</small>
      </h1>

      <ol class="breadcrumb">
      <li><a href="{{ url('/home') }}"><i class="fa fa-dashboard"></i> Home</a></li>
      <li><a href="#">Asset Management</a></li>
      <li class="active">Billing</li>
      </ol>
    </section>

    <section class="content">

      <div class="row">
        <div class="modal fade" id="createmodal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
        <div class="modal-dialog" role="document">
          <div class="modal-content">
            <div class="modal-header">
              <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
              <h4 class="modal-title" id="myModalLabel">Create</h4>
            </div>
            <div class="modal-body">
              <form id="create_form">
                    <div class="form-group">
                      <input type="hidden" name="propertyid" value="{{$id}}">
                      <label>Bill #</label>
                      <input type="text" name="bill_no" class="form-control">
                      <label>Date</label>
                      <input type="text" name="date" class="form-control datepicker">
                      <label>Amount</label>
                      <input type="number" step="0.01" name="amount" class="form-control">
                      <label>Type</label>
                      <select name="type" class="form-control">
                        @foreach($category as $categories)
                        <option value="{{$categories->Option}}">{{$categories->Option}}</option>
                        @endforeach
                      </select>
                    </div>
              </form>
            </div>
            <div class="modal-footer">
              <button type="button" class="btn btn-default" id="confirmcreate">Create</button>
              <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
            </div>
          </div>
        </div>
      </div>
        <div class="modal fade" id="deleteModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
        <div class="modal-dialog" role="document">
          <div class="modal-content">
            <div class="modal-header">
              <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
              <h4 class="modal-title" id="myModalLabel">Delete File</h4>
            </div>
            <div class="modal-body">
              Are you sure you want to remove / delete this file?
            </div>
            <div class="modal-footer">
              <button type="button" class="btn btn-default" id="btn-delete">Remove</button>
              <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
            </div>
          </div>
        </div>
      </div>
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
                        <input type="hidden" name="uploadid" id="uploadid">
                        <input type="file" id="uploadfile[]" name="uploadfile[]" multiple>
                      </form>
                    </div>
                  </div>
                  <div class="modal-footer">
                    <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                    <button type="button" class="btn btn-primary" id="confirm">Upload</button>
                  </div>
                </div>
              </div>
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

      <div class="modal modal-success fade" id="update-alert">
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
        <div class="col-md-12">
          <div class="box box-primary">
            <div class="box-header with-border">

              <div class="col-md-12">
                <div class="nav-tabs-custom">

                   <div class="tab-content">
                     <div class="active tab-pane" id="listtab">

                       @foreach($category as $table)

                         @if ($table->Option==$type)
                           <a href="{{url("billing")}}/{{$id}}/{{$table->Option}}"><button type="button" class="btn btn-success btn-small">{{$table->Option}}</button></a>
                         @else
                           <a href="{{url("billing")}}/{{$id}}/{{$table->Option}}"><button type="button" class="btn btn-danger btn-small">{{$table->Option}}</button></a>
                         @endif

                       @endforeach

                       <br><br>
                       <button id="create" class="btn btn-default"><span class="fa fa-plus-circle"> Create</span></button>
                       <br><br>
                        <table id="list" class="list" cellspacing="0" width="100%" padding="30px" style="font-size: 13px;">
                            <thead>
                              @if($billing)
                                <tr class="search">

                                  @foreach($billing as $key=>$value)

                                    @if ($key==0)
                                      <?php $i = 0; ?>

                                      @foreach($value as $field=>$a)
                                          @if ($i==0|| $i==1 || $i==2)
                                            <td align='center'><input type='hidden' class='search_init' name='{{$i}}' placemark='{{$a}}'></td>
                                          @else
                                            <td align='center'><input type='text' class='search_init' name='{{$i}}'  placemark='{{$a}}'></td>
                                          @endif

                                          <?php $i ++; ?>
                                      @endforeach

                                        <td align='center'><input type='text' class='search_init' name='{{$i}}'  placemark='{{$a}}'></td>

                                    @endif

                                  @endforeach
                                </tr>

                              @endif
                                <tr>

                                  @foreach($billing as $key=>$value)

                                    @if ($key==0)
                                           <!-- <td></td> -->
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
                              @foreach($billing as $agreements)

                                    <tr id="row_{{ $i }}" >
                                         <!-- <td></td> -->
                                         <td></td>
                                        @foreach($agreements as $key=>$value)

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
var files = {!! json_encode($filesByGroup) !!};
function getRowFiles(id) {
  return files[id];
}
$(function () {
    $( "#upload_form" ).submit(function( event ) {
      event.preventDefault();
      return false;
    });

    $('.datepicker').datepicker({
      autoclose: true,
        format: 'dd-M-yyyy',
    });
  });

  $(document).ready(function() {
        $(document).on('click', '#create', function(e) {
            $('#createmodal').modal('show');
        });
  });

  $(document).ready(function() {
        $(document).on('click', '#confirmcreate', function(e) {
              $.ajaxSetup({
                headers: { 'X-CSRF-Token' : $('meta[name=_token]').attr('content') }
                });
              $.ajax({
                  url: "{{ url('/billing/create') }}",
                  method: "POST",
                  data: $('#create_form').serialize(),
                  success: function(response){
                    $('#createmodal').modal('hide');
                    if (response==1)
                    {
                      var message="Successfully Created!";
                      $("#update-alert ul").html(message);
                      $("#update-alert").modal('show');
                       window.location.reload();
                    }
                    else
                    {
                      var errormessage="Failed to Create New!";
                      $("#error-alert ul").html(errormessage);
                      $("#error-alert").modal('show');
                    }
                  }
                });

        });
  });

  $(document).ready(function() {
        $(document).on('click', '.upload', function(e) {
          var id = $(this).attr('id');
          $('#uploadid').val(id);
          $('#UploadModal').modal('show');
        });
      });

$(document).ready(function() {
        $(document).on('click', '#confirm', function(e) {
              $.ajaxSetup({
                headers: { 'X-CSRF-Token' : $('meta[name=_token]').attr('content') }
                });
              $.ajax({
                  url: "{{ url('/billing/upload') }}",
                  method: "POST",
                  contentType: false,
                  processData: false,
                  data:new FormData($("#upload_form")[0]),
                  success: function(response){
                    $('#UploadModal').modal('hide');
                    if (response==1)
                    {
                      var message="File Uploaded!";
                      $("#update-alert ul").html(message);
                      $("#update-alert").modal('show');
                       window.location.reload();
                    }
                    else
                    {
                      var errormessage="Failed to Upload File!";
                      $("#error-alert ul").html(errormessage);
                      $("#error-alert").modal('show');
                    }
                  }

                });
        });
      });
    function removefile(id, parentid) {
        modalConfirm(function () {
            doremovefile(id, parentid);
        });
    }
      var modalConfirm = function(confirm){
      $("#deleteModal").modal('show');
      $("#btn-delete").on("click", function(){
        confirm();
        $("#deleteModal").modal('hide');
      });
    };
  function doremovefile(id, parentid) {
    $.ajaxSetup({
       headers: { 'X-CSRF-Token' : $('meta[name=_token]').attr('content') }
    });
    $.ajax({
                url: "{{ url('/asset/deletefile') }}",
                method: "POST",
                data: {Id:id},
                success: function (response) {
                $('#UploadModal').modal('hide');
                    if (response==1)
                    {
                      var message="File Deleted!";
                      $("#update-alert ul").html(message);
                      $("#update-alert").modal('show');
                      window.location.reload();
                    }
                    else
                    {
                      var errormessage="Failed to Delete File!";
                      $("#error-alert ul").html(errormessage);
                      $("#error-alert").modal('show');
                    }
                }
        });
    }
</script>



@endsection
