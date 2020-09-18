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

         var editor;
         var fiontable;

         $(document).ready(function() {

           editor = new $.fn.dataTable.Editor( {
                   ajax: {
                      "url": "{{ asset('/Include/fionrecord.php') }}",
                      "data": {
                        "Start": "{{ $start }}",
                        "End": "{{ $end }}"
                      }
                    },
                   table: "#fionrecord",
                   idSrc: "fionrecord.Id",
                   formOptions: {
                        bubble: {
                            submit: 'allIfChanged'
                        }
                    },
                   fields: [
                    {
                                label: "Date:",
                                name: "fionrecord.Date",
                                type:   'datetime',
                                format: 'DD-MMM-YYYY'
                    },{
                               label: "Type:",
                               name: "fionrecord.Type",
                               type:  'select2',
                               options: [
                                   { label :"", value: "" },
                                   { label :"Starting Balance", value: "Starting Balance" },
                                   { label :"Reimburse", value: "Reimburse" },
                                  


                               ],
                   },{
                                label: "Amount:",
                                name: "fionrecord.Amount",
                                type:   'text',
                                opts: {
                                     min: '1',
                                     steps: '0.1'
                                }
                    },{
                                label: "Remarks:",
                                name: "fionrecord.Remarks",
                                type:   'textarea'
                    },{
                                   label: "created_by:",
                                   name: "fionrecord.created_by",
                                   type:'hidden',
                                   def:"{{ $me->UserId }}"
                   },{
                                  label: "created_at:",
                                  name: "fionrecord.created_at",
                                  type:'hidden',
                                  def:"{{ date('Y-m-d H:i:s') }}"
                      }

                   ]
           } );



           // $('#fionrecord').on( 'click', 'tbody td:not(:first-child)', function (e) {
           //       editor.inline( this, {
           //         onBlur: 'submit',
           //         submit: 'allIfChanged'
           //     } );
           // } );

               fiontable = $('#fionrecord').dataTable( {
                 ajax: {
                    "url": "{{ asset('/Include/fionrecord.php') }}",
                    "data": {
                      "Start": "{{ $start }}",
                      "End": "{{ $end }}"
                    }
                  },
                  columnDefs: [{"className": "dt-center", "targets": "_all"}],
                  responsive: false,
                  sScrollX: "100%",
                  language : {
                     sLoadingRecords : 'Loading data...',
                     processing: 'Loading data...'
                   },
                  bAutoWidth: true,
                  rowId: 'fionrecord.Id',
                  sScrollY: "100%",
                  dom: "Bfrtip",
                  bScrollCollapse: true,

                   columns: [

                     {data: null, "render":"", title:"No"},
                     {data:'fionrecord.Id', title:"Id",visible:false},
                     {data:'fionrecord.Date', title:"Date"},

                     {data:'fionrecord.Type', title:"Type"},

                     {data:'fionrecord.Amount', title:"Amount"},
                     {data:'fionrecord.Remarks', title:"Remarks"},
                     {data:'fionrecord.created_at', title:'Created_At'},
                     {data:'fionrecord.updated_at', title:'Updated_At'},
                     {data:'creator.Name', editField: 'fionrecord.created_by', title:'Created_By' },
                     {
                        data:null,
                        title:"Upload",
                        "render": function ( data, type,full, meta ) {
                                  return '<button type="button" class="btn btn-primary" data-toggle="modal" onclick="OpenUploadWindow('+full.fionrecord.Id+')">Upload</button><br><br>';
                        }
                      },
                      { data: "files.Web_Path", title: 'Files',
                        "render": function ( data, type, full, meta ) {
                        var files = getRowFiles(full.fionrecord.Id);
                        if (files && files.length > 0) {
                        var display = "";
                        for(var i =0; i < files.length; i++) {
                        display += '<a href="{{url('/')}}/'+ files[i].Web_Path +'" target="_blank">View</a> | <a href="#" onclick="removefile('+files[i].Id+','+files[i].TargetId+')">Remove</a><br>';
                        }
                         return display;;
                      }
                        return '-';
                      }
                     }


                   ],
                   autoFill: {
                      editor:  editor
                  },
                   select: {
                           style:    'os',
                           selector: 'tr'
                   },
                   buttons: [

                           { extend: "create", text: 'New Record',  editor: editor },
                           @if($me->ewallet_update)
                           { extend: "edit",  editor: editor },
                           { extend: "remove", editor: editor },
                           @endif
                           {
                                   extend: 'collection',
                                   text: 'Export',
                                   buttons: [
                                           'excel',
                                           'csv'
                                   ]
                           }
                   ],

               });

               fiontable.api().on( 'order.dt search.dt', function () {
                   fiontable.api().column(0, {search:'applied', order:'applied'}).nodes().each( function (cell, i) {
                       cell.innerHTML = i+1;
                   } );
               } ).draw();


               $(".fionrecord thead input").keyup ( function () {

                       /* Filter on the column (the index) of this element */
                       if ($('#fionrecord').length > 0)
                       {

                           var colnum=document.getElementById('fionrecord').rows[0].cells.length;

                           if (this.value=="[empty]")
                           {

                              fiontable.fnFilter( '^$', this.name,true,false );
                           }
                           else if (this.value=="[nonempty]")
                           {

                              fiontable.fnFilter( '^(?=\\s*\\S).*$', this.name,true,false );
                           }
                           else if (this.value.startsWith("!")==true && this.value.length>1)
                           {

                              fiontable.fnFilter( '^(?'+ this.value +').*', this.name,true,false );
                           }
                           else if (this.value.startsWith("!")==false)
                           {

                               fiontable.fnFilter( this.value, this.name,true,false );
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
        Fion Record
      <small>eWallet</small>
      </h1>

      <ol class="breadcrumb">
      <li><a href="{{ url('/home') }}"><i class="fa fa-dashboard"></i> Home</a></li>
      <li><a href="#">eWallet</a></li>
      <li class="active">Fion Record</li>
      </ol>
    </section>

    <section class="content">

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

      <div class="modal modal-warning fade" id="warning-alert">
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
                        <input type="hidden" value="0" name="fionrecordId" id="fionrecordId">
                        <input type="file" id="uploadfile[]" name="uploadfile[]" multiple>
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

      <div class="row">
        <div class="col-md-4">
         <div class="input-group">
           <div class="input-group-addon">
             <i class="fa fa-clock-o"></i>
           </div>
           <input type="text" class="form-control" id="range" name="range">

         </div>
       </div>

       <div class="col-md-2">
           <div class="input-group">
             <button type="button" class="btn btn-success btn" data-toggle="modal" onclick="refresh();">Refresh</button>
           </div>
       </div>
      </div>

      <br>



      <div class="row">

        <div class="col-md-12">
          <!-- <div class="box">
            <div class="box-body"> -->


                  <table id="fionrecord" class="fionrecord" cellspacing="0" width="100%" padding="30px" style="font-size: 13px;">
                   <thead>
                                <tr class="search">
                                @foreach($fionrecord as $key=>$value)

                                    @if ($key==0)
                                        <?php $i = 0; ?>
                                            @foreach($value as $field=>$a)
                                                @if ($i==0 )
                                                  <th align='center'><input type='hidden' class='search_init' name='{{$i}}' placemark='{{$a}}'></th>
                                                @else
                                                  <th align='center'><input type='text' class='search_init' name='{{$i}}'  placemark='{{$a}}'></th>
                                                @endif
                                                <?php $i ++; ?>
                                            @endforeach
                                        <th align='center'><input type='text' class='search_init' name='{{$i}}'  placemark='{{$a}}'></th>
                                        <th align='center'><input type='text' class='search_init' name='{{$i}}'  placemark='{{$a}}'></th>

                                    @endif
                                @endforeach
                                </tr>
                                <tr>
                                    @foreach($fionrecord as $key=>$value)

                                        @if ($key==0)
                                        <td></td>

                                        @foreach($value as $field=>$value)
                                        <td>
                                            {{ $field }}
                                        </td>
                                        @endforeach
                                        <td></td>
                                        <td></td>

                                        @endif

                                    @endforeach
                                </tr>
                            </thead>
                            <tbody>
                                <?php $i = 0; ?>
                                @foreach($fionrecord as $delivery)

                                <tr>


                                    @foreach($delivery as $key=>$value)
                                      <td>
                                        {{ $value }}
                                      </td>
                                    @endforeach
                                    <td></td>
                                    <td></td>

                                </tr>
                                <?php $i++; ?>
                                @endforeach
                            </tbody>
                            <tfoot></tfoot>
                        </table>

            </div>



   </div>



   </section>

 </div>

<footer class="main-footer">
  <div class="pull-right hidden-xs">
    <b>Version</b> 1.0.0
  </div>
  <strong>Copyright &copy; 2014-2016 <a href="http://www.softoya.com">TrackerOnTheGo</a>.</strong> All rights
  reserved.
</footer>

<script type="text/javascript">

$(function () {

  $('#range').daterangepicker({locale: {
    format: 'DD-MMM-YYYY'
  },startDate: '{{$start}}',
  endDate: '{{$end}}'});

});

var files = {!! json_encode($filesByGroup) !!};
function getRowFiles(id) {
return files[id];
}

  function OpenUploadWindow(id)
  {
    $('input[name="fionrecordId"]').val(id);
    $('#UploadModal').modal('show');
  }

  function uploadfile() {
      $.ajaxSetup({
         headers: { 'X-CSRF-Token' : $('meta[name=_token]').attr('content') }
      });
      $.ajax({
                  url: "{{ url('/fionrecord/uploadfile') }}",
                  method: "POST",
                  contentType: false,
                  processData: false,
                  data:new FormData($("#upload_form")[0]),
                  success: function(response){
                      var newFiles = response.split(',');
                      newFiles.forEach(function(file) {
                        var arr = file.split('|');
                        var fileId = arr[0];
                        var filePath = arr[1];
                        var fileName = arr[2];
                        if (files[$('input[name="fionrecordId"]').val()] === undefined) {
                          files[$('input[name="fionrecordId"]').val()] = [];
                        }
                        files[$('input[name="fionrecordId"]').val()].push({
                          Id: fileId,
                          Web_Path: filePath,
                          TargetId: $('input[name="fionrecordId"]').val()
                        });
                      });
                      var message ="File uploaded!";
                      $("#update-alert ul").html(message);
                      $("#update-alert").show();
                      setTimeout(function() {
                        $("#update-alert").fadeOut();
                      }, 6000);
                      $('input[name="fionrecordId"]').val(0);
                      $('#uploadfile').empty();
                      $("#exist-alert").hide();
                      $('#UploadModal').modal('hide')
                      $("#uploadfile").val("");
                      fiontable.api().ajax.reload();
                      // window.location.reload();
                    },
                    error: function (response) {
                  var message ="Failed to upload file!";
                      $("#warning-alert ul").html(message);
                      $("#warning-alert").show();
                      setTimeout(function() {
                        $("#warning-alert").fadeOut();
                      }, 6000);

                      $('#UploadModal').modal('hide')
                    }

      });
  }
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
                url: "{{ url('/fionrecord/deletefile') }}",
                method: "POST",
                data: {Id:id},
                success: function (response) {
                for (var i = files[parentid].length - 1; i >= 0; --i) {
                    if (files[parentid][i].Id == id) {
                        files[parentid].splice(i,1);
                    }
                }
                fiontable.api().row('#'+parentid).invalidate().draw();
            },
            error: function (response) {
            }
        });
    }

    function refresh()
    {

       var d=$('#range').val();
      var arr = d.split(" - ");
        window.location.href ="{{ url("/fionrecord") }}/"+arr[0]+"/"+arr[1];
    }


</script>

@endsection
