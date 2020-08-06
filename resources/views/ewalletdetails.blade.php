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
         var ewallettable;

         $(document).ready(function() {

           ewallettable = $('#ewallettable').dataTable( {
             ajax: {
                "url": "{{ asset('/Include/ewalletrecord.php') }}",
                "data": {
                  "Expenses_Type": "{{ $type }}",
                  "Start": "{{ $start }}",
                  "End": "{{ $end }}",
                  @if($userid)
                    "UserId": "{{ $userid }}"
                  @endif
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
              rowId: 'ewallet.Id',
              sScrollY: "100%",
              dom: "Bfrtip",
              bScrollCollapse: true,

               columns: [
                 {
                   data:null,
                    "render": function ( data, type, full, meta ) {
                     if(!data.verify.Name)
                       //return '<a style="width:unset;" class="btn btn-default btn-sm" onclick="verify('+data.ewallet.Id+')">Verify</a>';
                       return '<input type="checkbox" name="selectrow" id="selectrow" class="selectrow" value="'+full.ewallet.Id+'" onclick="uncheck()">';
                     else return "-";
                   }
                 },
                 {data: null, "render":"", title:"No"},
                 {data:'ewallet.Id', title:"Id",visible:false},
                 {data:'ewallet.Date', title:"Date"},
                 {data:'projects.Project_Name', title:"Project_Name"},
                 {data:'tracker.Project_Code', title:"Project_Code"},
                 {data:'ewallet.Amount', title:"Amount"},
                 {data:'ewallet.Remarks', title:"Remarks"},
                 {data:'ewallet.created_at', title:'Created_At'},
                 {data:'ewallet.updated_at', title:'Updated_At'},
                 {data:'creator.Name', editField: 'ewallet.created_by', title:'Created_By' },
                 {data:'creator.Company', title:'Company'},
                 {
                    data:null,
                    title:"Upload",
                    "render": function ( data, type,full, meta ) {
                              return '<button type="button" class="btn btn-primary" data-toggle="modal" onclick="OpenUploadWindow('+full.ewallet.Id+')">Upload</button><br><br>';
                    }
                  },
                  { data: "files.Web_Path", title: 'Files',
                    "render": function ( data, type, full, meta ) {
                    var files = getRowFiles(full.ewallet.Id);
                    if (files && files.length > 0) {
                    var display = "";
                    for(var i =0; i < files.length; i++) {
                    display += '<a href="{{url('/')}}/'+ files[i].Web_Path +'" target="_blank">View</a> | <a href="#" onclick="removefile('+files[i].Id+','+files[i].TargetId+')">Remove</a><br>';
                    }
                     return display;;
                  }
                    return '-';
                  }
                 },
                  {
                    data:"verify.Name",
                    title:"Verified by",
                    render:function(data){
                      if(data) return data; else return "-";
                    }
                  },
                  {
                    data:null,
                    title:"Verified At",
                    render:function(data){
                      if(data.verify.Name) return data.ewallet.verified_at; else return "-";
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
                       {
                               extend: 'collection',
                               text: 'Export',
                               buttons: [
                                       'excel',
                                       'csv'
                               ]
                       },
                       {
                          text: 'Verified',
                          action: function ( e, dt, node, config ) {
                            verifytick();
                          }
                      }
               ],

           });

                           ewallettable.api().on( 'order.dt search.dt', function () {
                               ewallettable.api().column(1, {search:'applied', order:'applied'}).nodes().each( function (cell, i) {
                                   cell.innerHTML = i+1;
                               } );
                           } ).draw();

                           $("thead input").keyup ( function () {

                                   /* Filter on the column (the index) of this element */

                                   if ($('#ewallettable').length > 0)
                                   {

                                       var colnum=document.getElementById('ewallettable').rows[0].cells.length;

                                       if (this.value=="[empty]")
                                       {

                                          ewallettable.fnFilter( '^$', this.name,true,false );
                                       }
                                       else if (this.value=="[nonempty]")
                                       {

                                          ewallettable.fnFilter( '^(?=\\s*\\S).*$', this.name,true,false );
                                       }
                                       else if (this.value.startsWith("!")==true && this.value.length>1)
                                       {

                                          ewallettable.fnFilter( '^(?'+ this.value +').*', this.name,true,false );
                                       }
                                       else if (this.value.startsWith("!")==false)
                                       {
                                           ewallettable.fnFilter( this.value, this.name,true,false );
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
      eWallet Details ({{$type}})
      <small>eWallet</small>
      </h1>

      <ol class="breadcrumb">
      <li><a href="{{ url('/home') }}"><i class="fa fa-dashboard"></i> Home</a></li>
      <li><a href="#">eWallet</a></li>
      <li class="active">eWallet Details ({{$type}})</li>
      </ol>
    </section>

    <section class="content">

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
                        <input type="hidden" value="0" name="ewalletId" id="ewalletId">
                       <!--  <input type="hidden" value="" name="projectname" id="projectname">
                        <input type="hidden" value="" name="projectcode" id="projectcode"> -->
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

          <div class="col-md-12">

            <div class="box box-primary">

              <div class="box-body box-profile">


                  <table id="ewallettable" class="ewallettable" cellspacing="0" width="100%" padding="30px" style="font-size: 13px;">
                    <thead>
                                 <tr class="search">
                                 @foreach($ewalletrecord as $key=>$value)

                                     @if ($key==0)
                                         <?php $i = 0; ?>
                                             @foreach($value as $field=>$a)
                                                 @if ($i==0 || $i==1)
                                                   <th align='center'><input type='hidden' class='search_init' name='{{$i}}' placemark='{{$a}}'></th>
                                                 @else
                                                   <th align='center'><input type='text' class='search_init' name='{{$i}}'  placemark='{{$a}}'></th>
                                                 @endif
                                                 <?php $i ++; ?>
                                             @endforeach
                                         <th align='center'><input type='text' class='search_init' name='{{$i}}'  placemark='{{$a}}'></th>
                                         <th align='center'><input type='text' class='search_init' name='{{$i+1}}' placemark='{{$a}}'></th>
                                         <th align='center'><input type='text' class='search_init' name='{{$i+2}}'  placemark='{{$a}}'></th>
                                         <th align='center'><input type='text' class='search_init' name='{{$i+3}}'  placemark='{{$a}}'></th>
                                         <th align='center'><input type='text' class='search_init' name='{{$i+4}}'  placemark='{{$a}}'></th>
                                         <th align='center'><input type='text' class='search_init' name='{{$i+5}}'  placemark='{{$a}}'></th>
                                     @endif
                                 @endforeach
                                 </tr>
                                 <tr>
                                     @foreach($ewalletrecord as $key=>$value)

                                         @if ($key==0)
                                         <td><input type="checkbox" name="selectall" id="selectall" value="all" onclick="checkall()"></td>

                                         @foreach($value as $field=>$value)
                                         <td>
                                             {{ $field }}
                                         </td>
                                         @endforeach
                                         <td></td>
                                         <td></td>
                                         <td></td>
                                         <td></td>
                                         <td></td>
                                         @endif

                                     @endforeach
                                 </tr>
                             </thead>
                             <tbody>
                                 <?php $i = 0; ?>
                                 @foreach($ewalletrecord as $delivery)

                                 <tr>
                                     <td></td>

                                     @foreach($delivery as $key=>$value)
                                       <td>
                                         {{ $value }}
                                       </td>
                                     @endforeach
                                     <td></td>
                                     <td></td>
                                     <td></td>
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
<script type="text/javascript">

var files = {!! json_encode($filesByGroup) !!};
function getRowFiles(id) {
return files[id];
}

function verify(id){
  if(confirm("Are you sure you want to verify this?")){

    $.ajaxSetup({
       headers: { 'X-CSRF-Token' : $('meta[name=_token]').attr('content') }
    });
    $.ajax({
      type: "post",
      url: "{{url('ewalletrecord/verify')}}",
      data: {
        id:id
      },
      success: function (response) {
        if(response == 1){
          let message="Verified";
          $("#update-alert ul").html(message);
          $("#update-alert").modal('show');
          ewallettable.api().ajax.reload();
        }else{
          $("#warning-alert ul").html("Error");
          $("#warning-alert").modal('show');
        }
      }
    });
  }
}

function verifytick(){
  if(confirm("Are you sure you want to verify these records?")){

    var boxes = $('input[type="checkbox"]:checked', ewallettable.fnGetNodes() );
    var ids="";

    for (var i = 0; i < boxes.length; i++) {
      ids+=boxes[i].value+",";
    }

    ids=ids.substring(0, ids.length-1);

    if (boxes.length>0)
    {

      $.ajaxSetup({
         headers: { 'X-CSRF-Token' : $('meta[name=_token]').attr('content') }
      });

      $.ajax({
        type: "post",
        url: "{{url('ewalletrecord/verifytick')}}",
        data: {
          Ids:ids
        },
        success: function (response) {
          if(response == 1){
            let message="Verified";
            $("#update-alert ul").html(message);
            $("#update-alert").modal('show');
            ewallettable.api().ajax.reload();
          }else{
            $("#warning-alert ul").html("Error");
            $("#warning-alert").modal('show');
          }
        }
      });
    }
    else {

      var errormessage="No record selected!";
      $("#error-alert ul").html(errormessage);
      $("#error-alert").modal('show');

    }

  }
}

function checkall()
{
  var allPages = ewallettable.fnGetNodes();
// alert(document.getElementById("selectall").checked);
  if ($("#selectall").is(':checked')) {

     $('input[type="checkbox"]', allPages).prop('checked', true);
      // $(".selectrow").prop("checked", true);
       $(".selectrow").trigger("change");
       ewallettable.api().rows().select();
  } else {

      $('input[type="checkbox"]', allPages).prop('checked', false);
      $(".selectrow").trigger("change");
       ewallettable.api().rows().deselect();
  }
}

function uncheck()
{

  if (!$("#selectrow").is(':checked')) {
    $("#selectall").prop("checked", false)
  }

}

</script>

@endsection
