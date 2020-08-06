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

@endsection

@section('content')

    <div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
      <h1>
        Document Type Access Control
        <small>Admin</small>
      </h1>
      <ol class="breadcrumb">
        <li><a href="{{ url('/home') }}"><i class="fa fa-dashboard"></i> Home</a></li>
        <li><a href="#">Admin</a></li>
        <li class="active">Document Type Access Control</li>
      </ol>
    </section>

    <!-- Main content -->
    <section class="content">
      <div class="row">

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

            <form enctype="multipart/form-data" id="update_form" role="form" method="POST" action="" >
              <br>
              <div class="row">

                  <div class="col-md-3">
                    <label>Project Name : </label>
                   <select class="form-control select2" id="ProjectId" name="ProjectId" style="width: 100%;">
                     <option></option>

                     @foreach ($projects as $project)
                        <option value="{{$project->Id}}" <?php if($project->Id==$projectid) echo "selected";?>>{{$project->Project_Name}}</option>
                     @endforeach
                   </select>
                 </div>

                 <div class="col-md-3">
                       <label>Template Name : </label>
                      <select class="form-control select2" id="TemplateId" name="TemplateId" style="width: 100%;">
                        <option></option>

                        @foreach ($templates as $template)
                           <option value="{{$template->Id}}" <?php if($template->Id==$id) echo "selected";?>>{{$template->Template_Name}}</option>
                        @endforeach
                      </select>
                  </div>

                  <div class="col-md-1">
                    <button type="button" class="btn btn-primary btn-lg" data-toggle="modal" onclick="OpenUpdateDialog()">Save</button>
                  </div>
               </div>

               <br>

              <div class="row">

               <div class="modal fade" id="UpdateTemplate" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
                 <div class="modal-dialog" role="document">
                   <div class="modal-content">
                     <div class="modal-header">
                       <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                       <h4 class="modal-title" id="myModalLabel">Update Template</h4>
                     </div>
                     <div class="form-group" padding="10px">
                         <label id="updatetemplatemessage"></label>
                     </div>
                     <div class="modal-footer">
                       <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                       <button type="button" class="btn btn-primary" onclick="updatetemplate()">Update</button>
                     </div>
                   </div>
                 </div>
               </div>

            </div>

            <div>

              @foreach ($documentaccess as $type)

                <div class="box box-solid box-success">
                   <div class="box-header">
                     {{$type->Option}}
                   </div>
                   <div class="box-body">

                       <div class="form-group">
                         <label>Read : </label><span class="text-muted"></span><br>
                         <label>
                           <input type="radio" name="Readzzz{{$type->Option}}" class="flat-green" value="1" <?php if($type->Read == 1) { echo 'checked="checked"'; } ?>>
                           Yes
                         </label>
                         <label>
                           <input type="radio" name="Readzzz{{$type->Option}}" class="flat-green" value="0" <?php if($type->Read == 0) { echo 'checked="checked"'; } ?>>
                           No
                         </label>
                       </div>

                       <div class="form-group">
                         <label>Write : </label><span class="text-muted"></span><br>
                         <label>
                           <input type="radio" name="Writezzz{{$type->Option}}" class="flat-green" value="1" <?php if($type->Write == 1) { echo 'checked="checked"'; } ?>>
                           Yes
                         </label>
                         <label>
                           <input type="radio" name="Writezzz{{$type->Option}}" class="flat-green" value="0" <?php if($type->Write == 0) { echo 'checked="checked"'; } ?>>
                           No
                         </label>
                       </div>

                  </div>
                </div>

              @endforeach

            </div>

          </form>

        <!-- /.col -->
      </div>
      <!-- /.row -->
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

    $(function () {

      //Initialize Select2 Elements
      $(".select2").select2();

      $('input[type="checkbox"].flat-green, input[type="radio"].flat-green').iCheck({
        checkboxClass: 'icheckbox_flat-green',
        radioClass: 'iradio_flat-green'
      });


  });

  $('#ProjectId').on('change', function() {

    var d=$('#ProjectId').val();
    var t=$('#TemplateId').val();

    window.location.href ="{{ url("/documenttypeaccesscontrol") }}/"+t+"/"+d;

  });

  $('#TemplateId').on('change', function() {

    var d=$('#ProjectId').val();
    var t=$('#TemplateId').val();

    window.location.href ="{{ url("/documenttypeaccesscontrol") }}/"+t+"/"+d;

  });

  function updatetemplate() {

      $.ajaxSetup({
         headers: { 'X-CSRF-Token' : $('meta[name=_token]').attr('content') }
      });

      $.ajax({
                  url: "{{ url('/accesscontrol/updatedocumenttypeaccess') }}",
                  method: "POST",
                  contentType: false,
                  processData: false,
                  data:new FormData($("#update_form")[0]),
                  success: function(response){
                    if (response==0)
                    {
                      var message ="No update on template!";
                      $("#warning-alert ul").html(message);
                      $("#warning-alert").modal('show');

                      $('#UpdateTemplate').modal('hide')

                    }
                    else {

                      var message ="Template updated!";
                      $("#update-alert ul").html(message);
                      $("#update-alert").modal('show');

                      $('#UpdateTemplate').modal('hide');

                    }

          }
      });

  }

  function OpenUpdateDialog()
  {
      if ($("#TemplateId option:selected").html()!="")
      {
        $templatename=$("#TemplateId option:selected").html();
        $( "#updatetemplatemessage" ).html("&nbsp;&nbsp;&nbsp; Are you sure you wish to update <i>'"+ $templatename +"'</i> template?");
        $('#UpdateTemplate').modal('show');

      }

  }

  </script>

@endsection
