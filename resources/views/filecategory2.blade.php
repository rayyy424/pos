
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
<!--Drag n Drop-->
<!-- <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0-beta.3/css/bootstrap.min.css" integrity="sha384-Zug+QiDoJOrZ5t4lssLdxGhVrurbmBWopoEl+M6BdEfwnCJZtKxi1KgxUyJq13dy" crossorigin="anonymous"> -->

<!-- <link rel="stylesheet" type="text/css" href="{{ asset('/plugin/uploader/css/jquery.dm-uploader.min.css') }}">
<link rel="stylesheet" type="text/css" href="{{ asset('/plugin/uploader/css/style.css') }}"> -->

<!--Drag n Drop-->
{{-- <link rel="stylesheet" type="text/css" href="{{ asset('/plugin/examples/resources/syntax/shCore.css') }}">
<link rel="stylesheet" type="text/css" href="{{ asset('/Plugin/examples/resources/demo.css') }}"> --}}

<!--Drag n Drop Firdaus 20180622 -->
<script>
    $(document).ready(function(){
//$('#drag-and-drop-zone-' + $('#DocumentType').val()).dmUploader({
$('#drag-and-drop-zone').dmUploader({
    url: '{{Url("/tracker/submitdocument")}}',
    method:'post',
    maxFileSize: 3000000,
    allowedTypes: 'image/*',
    extFilter: ["jpg", "jpeg","png","gif"],
    extraData:{
        '_token':'{{csrf_token()}}',
        'UserId':$('#UserId').val(),
        'selectedtrackerid':$('#selectedtrackerid').val(),
        'DocumentType':$('#DocumentType').val(),
        'submitdate':$('#submitdate').val(),

    },
    onDragEnter: function(){
      this.addClass('active');
    },
    onDragLeave: function(){
      this.removeClass('active');
    },

    onNewFile: function(id, file){

      ui_multi_add_file(id, file);

      if (typeof FileReader !== "undefined"){
        var reader = new FileReader();
        var img = $('#uploaderFile' + id).find('img');

        reader.onload = function (e) {
          img.attr('src', e.target.result);
        }
        reader.readAsDataURL(file);
      }
    },
    onBeforeUpload: function(id){

      ui_multi_update_file_progress(id, 0, '', true);
      $('#uploaderFile' + id).find('#change').html('Uploading...').prop('class', 'status text-uploading');

    },
    onUploadProgress: function(id, percent){

      ui_multi_update_file_progress(id, percent);
    },
    onUploadSuccess: function(id, data){
      var bar = $('#uploaderFile' + id).find('div.progress-bar');

      bar.css("background-color", "green");


      $('#uploaderFile'+id).data('id', data.data);

      $('#uploaderFile'+id).find('.delete-modal').data('id',data.data);
      $('#uploaderFile'+id).find('.delete-modal').data('name',data.name);

      $('#uploaderFile'+id).addClass('delete'+data.data);

      $('.box-footer').append(
        $('<input>', {
        type: 'hidden',
        value: data.data,
        'name':'image[]'
    })
      );


      $('#uploaderFile' + id).find('#change').html('Upload Complete').prop('class', 'status text-success');
      ui_multi_update_file_progress(id, 100, 'success', false);

    },
    onUploadError: function(id, xhr, status, message){
      $('#uploaderFile' + id).find('#change').html(message).prop('class', 'status text-' + status);
      ui_multi_update_file_progress(id, 0, 'danger', false);
    },
    onFileSizeError: function(file){
      alert('File \'' + file.name + '\' cannot be added: size excess limit');
    },
    onFileTypeError: function(file){
      alert('File \'' + file.name + '\' cannot be added: must be an image');
    },
    onFileExtError: function(file){
      alert('File \'' + file.name + '\' cannot be added: must be an image');
    }
  });

  $('.modal-footer').on('click', '.delete', function() {
        $.ajax({
            type: 'delete',
            url: '{{Url('deleteImage')}}',
            data: {
                '_token': $('input[name=_token]').val(),
                'id': $('.did').text()
            },
            success: function(data) {
                $('.delete'+data.id).remove();
            }
        });
    });


  $(document).on('click', '.delete-modal', function() {
        $('#footer_action_button').text(" Delete");
        $('#footer_action_button').removeClass('glyphicon-check');
        $('#footer_action_button').addClass('glyphicon-trash');
        $('.actionBtn').removeClass('btn-success');
        $('.actionBtn').addClass('btn-danger');
        $('.actionBtn').addClass('delete');
        $('.modal-title').text('Delete');
        $('.did').text($(this).data('id'));
        $('.deleteContent').show();
        $('.form-horizontal').hide();
        $('.dname').html($(this).data('name'));
        $('#myModal').modal('show');
});
    });


</script>
<!--Drag n Drop Firdaus 20180622 -->

<style type="text/css" class="init">

.modal-dialog-wide{
  width: 80%; /* respsonsive width */
  position: absolute;
  float: left;
  left: 50%;
  top: 70%;
  transform: translate(-50%, -50%);
}
  #div1 {
    width: 600px;
    height: 570px;
    padding: 10px;
    border: 1px solid #aaaaaa;
  }

  .row{
    padding:10px;
  }
  .CompanyName{
    width:100%;
    margin:0;
  }
  .CompanyName h2{
    line-height: 20%;
    padding-top:10px;

  }
  .CompanyName h5{
    text-align:center;
    font-weight:100;
  }

  .CompanyName img{
    padding:10px;
  }

  .row h2,h4{
    padding: 0;
    margin: 0;
    padding-bottom:10px;
  }

  div.test {
    border: 2px solid;
    background-color: rgb(255, 192, 0);
    padding: 10px;
    width: 900px;
    resize: both;
    overflow: auto;
  }

  table {
    width:100%;
    font-size:14px;
  }

  table.gray{
    background-color: gray;
    border-spacing: 15px;
    padding:10px;
  }
  table.yellow{
  }

  table.towerdetails tr:nth-child(even){
    background-color: #f2f2f2;
  }

  table.locationtable th{
    background-color: blue;
    border: 1px solid black;
    border-collapse: collapse;
  }
  table.locationtable tr, table.locationtable td{
    border: 1px solid black;
    border-collapse: collapse;
  }

  table.tablebottomimage,table.tablebottomimage tr, table.tablebottomimage td {
    border: 1px solid black;
    border-collapse: collapse;

  }

  table.edot, table.edot tr, table.edot td{
    border: 1px solid black;
    border-collapse: collapse;
  }
  table.doubleimagetable{
    width:650px;
  }

  table.doubleimagetable, table.doubleimagetable td{
    border: 1px solid black;
    border-collapse: collapse;

    /*height:200px;*/
    /*margin:0 auto;*/
  }
  table.doubleimagetable th{
    border: 1px solid black;
  }

  table.smallsingleimagetable, table.smallsingleimagetable td{
    border: 1px solid black;
    border-collapse: collapse;
    width:325px;
    height:200px;
    text-align:center;


  }
  table.smallsingleimagetable td{
    text-align:center;

  }

  table.smallsingleimagetable th{
    border: 1px solid black;
  }

  table.three, table.three tr, table.three td{
    border: 1px solid black;
    border-collapse: collapse;
  }

  table.five, table.five td{
    border: 1px solid black;
    border-collapse: collapse;
    width:500px;
    height:200px;
    text-align:center;
    padding-left:150px;

  }
  table.five td{
    text-align:center;

  }

  table.five th{
    border: 1px solid black;
  }

  table.main{
    border-collapse: collapse;
  }

  table.main, table.main tr{
    background-color: rgb(255, 153, 204);
    width: 240px;
  }

  table.main td{
   padding-bottom:5px;
 }


 table.tableforsection2{
  border-collapse: collapse;
  padding-bottom:5px;
}

table.tableforsection2 tr,td{
  padding-bottom:5px;
}

table.section4TSSTable{
  border-collapse: collapse;
  padding-bottom:5px;
}

table.section4TSSTable tr,td{
  padding-bottom:5px;
}

.row1{
  padding:10px;
  width:100%;
}

h4.pagetitle{
  text-align:center;
  padding-top:5px;
  font-family: "Arial";
}



.profilepic{
  text-align:center;
}
.profilepic img{
  width:100px;
  border-radius:50%;
}

.col-second{
  width:100%;
  display:inline-block;
}

.col-second.snap {
  height: 500px;
  width:100%;
  display: inline-block;
  border: 1px solid #000;
  /background: #C5C5C5;/
}
.col-third{
  width:450px;
  display:inline-block;
}

.col-third.snap {
  height:300px;
  width:450px;
  display: inline-block;
  border: 1px solid #000;
  /background: #C5C5C5;/
}
.col-try{
  width:100%;
  display:inline-block;
}

.col-try.snap {
  height: 1000px;
  width:100%;
  display: inline-block;
  border: 1px solid #000;
  /background: #C5C5C5;/
}
.col-word{
  width:100%;
  display:inline-block;
}
.col-word.snap {
  height: 300px;
  width:400px;
  display: inline-block;
  border: 1px solid #000;
  /background: #C5C5C5;/
}

.dropped {
  border-color: #000 !important;
}
input.col-word{
}

.boximage{
  margin-top:10px;
}
.boximage input{
  height:200px;
}

.comparison img{
  width:250px;
  margin:5px;
  z-index: 20000;
}

.fixed{
  right:0;
  height:800px;

  display:inline-block;
}

.container{
  width: 100%;
  heght: 100%;
  margin: 0 0 0 0;
  padding: 0 0 0 0;
}

.test1{
  border: 1px solid green;
}

.header{
  height: 100px;
  border: 1px solid orange;
  margin-bottom: 1px;
}


.image{
  width: 215px;
  height: 200px;
  background-color: lightblue;
  margin: 10px 0 10px 10px;
}


.board{
  width: calc(100% - 300px);
  height: 600px;
  border: 1px solid red;
  margin: 0;
  display:inline-block;
  position: absolute;
}

img.object{
  width:250px;
}

.snap{
  /width:100%/
}

.imagediv img{
  width:380px;
  height:280px;
}

.imagediv{

  padding:0;
  height:320px;
  width:440px;

}

.imagediv1 img{
 width:100%;
}

.imagediv1{

  width: 96%;
  border: 1px solid #fff;
  height:1000px;

}

.imagediv2 img{
 width:500px;
}

.imagediv2{

  width: 96%;
  border: 1px solid #fff;
  height:500px;

}

.imagediv3 img{
 width:400px;
}

.imagediv3{

  width: 96%;
  border: 1px solid #fff;
  height:400px;

}

#Gallery {

  overflow:scroll;
  height:1000px;
}

#Gallery img{
  margin-top:10px;
}

#First, #Second
{
  position:absolute;
  min-width: 200px;
  border: 1px solid #aaa;
}

*[draggable=true]
{
  float:left;
  min-width: 50px;
  min-height: 50px;
  border: 2px solid black;
  margin: 10px;
  font-weight:bold;
  min-height:60px;
  background-image: -webkit-gradient(linear, left top, left bottom,color-stop(0.63, white),  color-stop(0.92, rgb(5,161,245)));
  background-image:-moz-linear-gradient(           center top,white 63%,rgb(5,161,245) 92%);
  -webkit-background-size:1px 8px;
  -moz-background-size:1px 8px;
  background-size:1px 8px;
  background-repeat:repeat;
}

div .printbutton {
  position: absolute;
  top: 60px;
  right: 0;
}
.nav-tabs-custom>.nav-tabs>li {
  width: 160px;
  text-align: center;

}

span.gallery_title{
  text-align:center;
}

.nav-tabs-custom>.smalltab>li {
  width: 120px;
  text-align: center;

}

.nav-tabs-custom>.smalltab>li{
  border: 3px solid transparent;

}

.nav-tabs-custom>.smalltab>li:hover{
  border: 3px solid transparent;
}
.nav-tabs-custom>.smalltab>li a{
  padding:5px;
}

.nav-tabs-custom>.smalltab>li.active {
  border-color: #3c8dbc;
}

.nav-tabs-custom>.smalltab>li.hover {
  border-color: red;
}

.table-responsive {
  overflow-x:visible;
}
@media only screen (min-width:500px)
{
.table-responsive {
  overflow-x:auto;
}
}
table.assettable td{
  text-align:center;
}

.fa-eye{

font-size: 20px;
}

span.image_name {
    position: absolute;
    bottom: 10px;
    left:0;
    right:0;
    width: 85%;
    font-size:12px;
    color:white;;
    object-fit: cover;
    background-color:rgba(0, 0, 0, 0.77);
    margin:10px;
    padding:3px;
}

.viewimages {
  overflow: auto;
  width: 190px;
  float: left;
  position:relative;
  padding:5px;
}

.viewimages img{
  width:180px;
  height:180px;
}
.viewimages img:hover{
  border: 2px solid red;
  opacity: 0.8;
}

span.fa-trash-o {
    position: absolute;
    top: 10px;
    left: 20px;
    width: 100%;
    font-size:20px;
    color:red;
    object-fit: cover;
}
.modal-dialog-wide1 {
  top: 60%;
  width: 80%; /* respsonsive width */
  position: absolute;
  float: left;
  left: 50%;
  transform: translate(-50%, -50%);
}

span.image_name {
    position: absolute;
    bottom: 10px;
    left:0;
    right:0;
    width: 85%;
    font-size:12px;
    color:white;;
    object-fit: cover;
    background-color:rgba(0, 0, 0, 0.77);
    margin:10px;
    padding:3px;
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
<script type="text/javascript" language="javascript" src="https://cdn.datatables.net/plug-ins/1.10.13/api/sum().js"></script>

<script type="text/javascript" language="javascript" src="{{ asset('/plugin/js/dataTables.editor.min.js') }}"></script>
<script async="" defer="" src="https://maps.googleapis.com/maps/api/js?key=AIzaSyDuDdIs9T09hGo4LV3dRSiuHVMbUnkS-JE&libraries=places"></script>


<script>

    $(document).ready(function() {

      $('#filecontent').hide().show(0);

    } );
</script>
<!--Drag n Drop-->
<!-- <link rel="stylesheet" type="text/css" href="{{ asset('/plugin/uploader/css/jquery.dm-uploader.min.css') }}"> -->

<!-- <script src="{{ asset('/plugin/uploader/js/jquery.dm-uploader.min.js') }}"></script>
<script src="{{ asset('/plugin/uploader/js/demo-ui.js') }}"></script>
<script src="{{ asset('/plugin/uploader/js/demo-config.js') }}"></script> -->

<!-- <script src="../dist/js/jquery.dm-uploader.min.js"></script> -->
<!-- <script src="demo-ui.js"></script>
<script src="demo-config.js"></script> -->
<!--Drag n Drop-->



<script>

var myMap;
var gmarkers = Array();

</script>
{{-- <script type="text/javascript" language="javascript" src="{{ asset('/plugin/examples/resources/syntax/shCore.js') }}"></script>
<script type="text/javascript" language="javascript" src="{{ asset('/plugin/examples/resources/demo.js') }}"></script>
<script type="text/javascript" language="javascript" src="{{ asset('/plugin/examples/resources/editor-demo.js') }}"></script> --}}

    <style>

    #map{
    height: 600px;
    /*width:530px;*/
    margin: 0 auto;
  }
    </style>


@endsection

@section('content')

<div class="content-wrapper" id="filecontent">

  <!-- Content Header (Page header) -->
        <section class="content-header">
          <h1>
            {{$site->{'Site ID'} }} {{$site->{'Site LRD'} }} <br>({{$site->{'Site Name'} }})
          </h1>
          <ol class="breadcrumb">
            <li><a href="#"><i class="fa fa-dashboard"></i>Home</a></li>
            <li><a href="#">{{ $site->{'Site Name'} }}</a></li>
          </ol>
        </section>

  <!-- Main content -->
        <section class="content">

          <div class="modal fade" id="CreateDocumentType" role="dialog" aria-labelledby="myModalLabel">
             <div class="modal-dialog">
               <div class="modal-content">
                 <div class="modal-header">
                   <div id="exist-alert" class="alert alert-warning alert-dismissible" style="display:none;">
                     <h4><i class="icon fa fa-check"></i> Alert!</h4>
                     <div id="changepasswordmessage"></div>
                   </div>
                   <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                   <h4 class="modal-title" id="myModalLabel">New Document Type</h4>

                 </div>

                 <div class="modal-body">

                     <div class="form-group">
                       <label class="col-md-4 control-label">Document Type</label>
                       <div class="col-md-8">

                          <input type="text" class="form-control" name="documenttype" id="documenttype" placeholder=""/>

                       </div>
                     </div>


                 </div>

                 <br>
                 <div class="modal-footer">

                   <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                   <button type="button" class="btn btn-primary" onclick="createdocumenttype()">Create</button>
                 </div>
               </div>
             </div>
           </div>

          @if($me->Add_Option)
            <div class="row">
              <a data-toggle="modal" data-target="#CreateDocumentType"><button type="button" class="btn btn-success">New Document Type</button></a>
            </div>
          @endif

          <div class="row">

            <?php $count=1;$section="-";?>


                @foreach ($category2 as $cat)

                    @if($section!=$cat->Section)

                        @if($section!="-" && $count!=1)
                          </div>
                          </div>
                        @endif
                        <div class="box box-solid box-primary">

                          @if($section!=$cat->Section)
                          <div class="box-header">
                            @if(!$cat->Section)
                              Empty Section
                            @else
                              {{$cat->Section}}
                            @endif

                          </div>
                          <div class="box-body">
                            <?php $count=1;$section=$cat->Section?>
                          @endif

                    @elseif($count==1)

                        <div class="box box-solid box-primary">

                    @endif

                      <div class="col-md-3">
                        <center>
                            <!-- <div id="drag-and-drop-zone-{{$cat->Option}}" class="dm-uploader p-5 text-center"> -->
                            <!-- <div id="drag-and-drop-zone" class="dm-uploader p-5 text-center"> -->
                              <form enctype="multipart/form-data" role="form" method="POST" action="" >
                                    <a href="{{ URL::to("/")}}/filerenderer2/{{$cat->TargetId}}/{{$cat->Option}}"><img src="{{ url('/img/folder.png') }}" alt="Photo" width="100px" height="100%"/>
                                      <!-- Hidden Files -->
                                      <input type="hidden" class="form-control" id="UserId" name="UserId" value="{{$me->UserId}}"> <!--Done-->
                                      <input type="hidden" class="form-control" id="selectedtrackerid" name="selectedtrackerid" value="{{$cat->TargetId}}">
                                      <input type="hidden" class="form-control" id="DocumentType" name="DocumentType" value="{{$cat->Option}}"> <!--Done-->
                                      <!-- <input type="hidden" class="form-control" id="SiteId" name="SiteId" value="'+SiteId+'"> -->
                                      <input type="hidden" class="form-control" id="submitdate" name="submitdate"> <!--Done-->
                                      <!-- Hidden Files -->
                                    </a><br>
                              </form>

                                      <center><a href="{{ URL::to("/")}}/filerenderer2/{{$cat->TargetId}}/{{$cat->Option}}" target="_blank">{{ $cat->Option}}<br>[{{$cat->Description}}]

                                        @foreach($files as $file)
                                          @if($file->Document_Type==$cat->Option && $file->count>0)
                                            <br><font color="blue">[{{$file->count}} file(s)]</font>
                                          @endif

                                        @endforeach

                                      </a></center>
                              <!-- </div> -->
                        </div>

                        <?php $count++; ?>

                  @endforeach
                  <!-- <div class="row">
                                <div class="col-md-12">
                                  <div class="card">
                                <div class="card-header">
                                   File
                                </div>
                             <div class="box">
                                <ul class="list-unstyled p-2 d-flex flex-column col" id="files">
                                    <div class="box-body">
                                    <li class="text-muted text-center empty">No files uploaded.</li></div>
                                </ul>
                              </div>
                          </div>

                        </div>
                    </div> -->


          </div>
        </section>
</div>

<script>

function createdocumenttype()
{

    documenttype=$('input[name="documenttype"]').val();

    $.ajaxSetup({
       headers: { 'X-CSRF-Token' : $('meta[name=_token]').attr('content') }
    });

    if(documenttype)
    {

      $.ajax({
                  url: "{{ url('/tracker/createdocumenttype') }}",
                  method: "POST",
                  data: {
                    DocumentType:documenttype


                  },
                  success: function(response){

                    if (response==0)
                    {

                      $('#CreateDocumentType').modal('hide')

                      var message ="Failed to create document type!";

                      $("#warning-alert ul").html(message);
                      $("#warning-alert").modal("show");


                    }
                    else {

                        $('#CreateDocumentType').modal('hide');


                      var message ="Document Type created!";
                      $("#update-alert ul").html(message);
                      $("#update-alert").modal("show");

                      window.location.reload();

                    }
          }
      });

    }
    else {
      var message ="Document Type cannot be empty!";

      $("#warning-alert ul").html(message);
      $("#warning-alert").modal("show");
    }

}

</script>





@endsection
