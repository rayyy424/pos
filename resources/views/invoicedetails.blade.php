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

      .dtr-data:before {
        display:inline-block;
        content: "\200D";
      }

      .dtr-data {
        display:inline-block;
        min-width: 100px;
        background-color:yellow;
        font-style:italic;
        font-size: 9pt;
      }

      .weekend {
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
      <script type="text/javascript" language="javascript" src="https://cdn.datatables.net/plug-ins/1.10.13/api/sum().js"></script>

      <script type="text/javascript" language="javascript" src="{{ asset('/plugin/js/dataTables.editor.min.js') }}"></script>
      {{-- <script type="text/javascript" language="javascript" src="{{ asset('/plugin/examples/resources/syntax/shCore.js') }}"></script>
      <script type="text/javascript" language="javascript" src="{{ asset('/plugin/examples/resources/demo.js') }}"></script>
      <script type="text/javascript" language="javascript" src="{{ asset('/plugin/examples/resources/editor-demo.js') }}"></script> --}}

    <script>

             $(function () {

               /* initialize the calendar
                -----------------------------------------------------------------*/
               //Date for the calendar events (dummy data)
               var date = new Date();
               var d = date.getDate(),
                   m = date.getMonth(),
                   y = date.getFullYear();
               $('#calendar').fullCalendar({
                 header: {
                   left: 'prev,next today',
                   center: 'title',
                   right: 'month'
                 },
                 buttonText: {
                   today: 'Today',
                   month: 'Month'
                 },
                 //Random default events
                 events: [
                 ],

                 editable: false,
                 droppable: false, // this allows things to be dropped onto the calendar !!!
               });

               $("#ajaxloader").hide();
               $("#ajaxloader2").hide();
             });

      </script>
@endsection

@section('content')

    <div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
      <h1>
        Invoice Details
        <small>Sales Management</small>
      </h1>
      <ol class="breadcrumb">
        <li><a href="{{ url('/home') }}"><i class="fa fa-dashboard"></i> Home</a></li>
        <li><a href="#">Sales Management</a></li>
        <li><a href="{{ url('/Invoice/') }}">Invoice Management</a></li>
        <li class="active">Invoice Details</li>
      </ol>
    </section>

    <!-- Main content -->
    <section class="content">
      <div class="row">
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

        <div class="col-md-4">

          <div class="box box-primary">

            <div class="box-body box-profile">

              <ul class="list-group list-group-unbordered">
                <li class="list-group-item">
                  <b>Invoice No</b> : <p class="pull-right"><i>{{ $invoice->Invoice_No }}</i></p>
                </li>
                <li class="list-group-item">
                  <b>Invoice Date</b> : <p class="pull-right"><i>{{ $invoice->Invoice_Date }}</i></p>
                </li>
                <li class="list-group-item">
                  <b>Invoice Type</b> : <p class="pull-right"><i><span id='status'>{{ $invoice->Invoice_Type }}</span></i></p>
                </li>
                <li class="list-group-item">
                  <b>Description</b> : <p class="pull-right"><i>{{ $invoice->Invoice_Description }}</i></p>
                </li>
                <li class="list-group-item">
                  <b>Total Amount</b> : <p class="pull-right"><i>RM {{ $invoice->Invoice_Amount }}</i></p>
                </li>
                <li class="list-group-item">
                  <b>Status</b> : <p class="pull-right"><i>{{ $invoice->Invoice_Status }}</i></p>
                </li>

              </ul>

              {{-- <a href="#" class="btn btn-primary btn-block"><b>Update</b></a> --}}
            </div>

          </div>
        </div>

         <div class="col-md-8">
          <div class="box box-primary">
              <div class="box-header with-border">
                <div class="row">
                  <div class="col-md-4">
                    Attachment <p class="text-muted">[PNG, JPG and PDF file only]</p>
                  </div>

               <div class="col-md-4">
                    <br>
                    <div class="form-group">
                      <form enctype="multipart/form-data" id="upload_form" role="form" method="POST" action="" >
                        <input type="hidden" class="form-control" id="InvoiceId" name="InvoiceId" value="{{$invoice->Id}}">
                        <input type="file" id="receipt[]" name="receipt[]" accept=".png,.jpg,.jpeg,.pdf" multiple>

                      </form>
                    </div>
                  </div>

                  <div class="col-md-4">
                    <br>
                    <button type="button" class="btn btn-primary" onclick="uploadreceipt()">Upload</button>
                  </div>
                </div>
                <br>

                  <div id="receiptdiv">

                  @foreach ($receipts as $receipt)

                    @if(strpos($receipt->Web_Path,'.png') !== false || strpos($receipt->Web_Path,'.jpg') !== false || strpos($receipt->Web_Path,'.jpeg') !== false)

                      <div class="col-sm-3" id="receipt{{ $receipt->Id }}">
                        <a download="{{ url($receipt->Web_Path) }}" href="{{ url($receipt->Web_Path) }}" title="Download">
                          <img class="img-responsive" src="{{ url($receipt->Web_Path) }}"  alt="Photo">
                        </a>
                        <button type="button" class="btn btn-block btn-danger btn-xs" onclick="deletereceipt({{$receipt->Id }})">Delete</button>
                      </div>

                    @else
                      <div class="col-sm-3" id="receipt{{ $receipt->Id }}">
                        <a download="{{ url($receipt->Web_Path) }}" href="{{ url($receipt->Web_Path) }}" title="Download">
                          {{ $receipt->File_Name}}
                        </a>
                        <button type="button" class="btn btn-block btn-danger btn-xs" onclick="deletereceipt({{$receipt->Id }})">Delete</button>
                      </div>

                    @endif

                  @endforeach

                </div>

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
 function deletereceipt(id) {
    $.ajaxSetup({
       headers: { 'X-CSRF-Token' : $('meta[name=_token]').attr('content') }
    });
    $.ajax({
                url: "{{ url('/Invoice/deletereceipt') }}",
                method: "POST",
                data: {Id:id},
                success: function(response){
                  if (response==0)
                  {
                    var message ="Failed to delete attachment!";
                    $("#warning-alert ul").html(message);
                    $("#warning-alert").show();
                  }
                  else {
                    var message ="Attachment deleted!";
                    $("#update-alert ul").html(message);
                    $("#update-alert").show();
                    //$("#Template").val(response).change();
                    $("#exist-alert").hide();

                    $("#receipt"+id).remove();
                  }
        }
    });
  }
 function uploadreceipt() {
      $.ajaxSetup({
         headers: { 'X-CSRF-Token' : $('meta[name=_token]').attr('content') }
      });
      $.ajax({
                  url: "{{ url('/Invoice/uploadreceipt') }}",
                  method: "POST",
                  contentType: false,
                  processData: false,
                  data:new FormData($("#upload_form")[0]),
                  success: function(response){
                    if (response==0)
                    {
                      var message ="Failed to upload attachment!";
                      $("#warning-alert ul").html(message);
                      $("#warning-alert").show();
                    }
                    else {
                      var message ="Attachment uploaded!";
                      $("#update-alert ul").html(message);
                      $("#update-alert").show();
                      //$("#Template").val(response).change();
                      $("#exist-alert").hide();
                      $("#receipt").val("");

                      var split=response.split(",");
                      for (var i = 0; i < split.length; i++) {

                        if (split[i].toUpperCase().includes(".PNG") ||split[i].toUpperCase().includes(".JPG")||split[i].toUpperCase().includes(".JPEG"))
                        {
                          var sub=split[i].split("|");

                          var html="<div class='col-sm-3' id='receipt"+sub[0]+"'>";
                          html+="<a download='"+sub[1]+"' href='"+sub[1]+"' title='Download'>";
                          html+="<img class='img-responsive' src='"+sub[1]+"'  alt='Photo'>";
                          html+="</a>";
                          html+="<button type='button' class='btn btn-block btn-danger btn-xs' onclick='deletereceipt("+sub[0]+")'>Delete</button>";
                          html+="</div>";

                          $("#receiptdiv").append(html);


                        }
                        else {

                          var sub=split[i].split("|");
                          var html="<div class='col-sm-3' id='receipt"+sub[0]+"'>";
                          html+="<a download='"+sub[1]+"' href='"+sub[1]+"' title='Download'>";
                          html+=sub[2];
                          html+="</a>";
                          html+="<button type='button' class='btn btn-block btn-danger btn-xs' onclick='deletereceipt("+sub[0]+")'>Delete</button>";
                          html+="</div>";

                          $("#receiptdiv").append(html);

                        }

                      }
                    }
          }
      });
  }
</script>


@endsection
