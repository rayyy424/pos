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
    <link rel="stylesheet" type="text/css" href="{{ asset('/plugin/dmUploader/css/jquery.dm-uploader.min.css') }}">


    <style type="text/css" class="init">
      a.buttons-collection {
        margin-left: 1em;
      }
      .container1{
              width: 1200px;
              margin-left: 50px;
              padding: 10px;
      }

      .green {
        color: green;
      }

      .yellow {
        color: #f39c12;
      }

      .red{
        color:red;
      }

      #files {
          overflow-y: scroll !important;
          min-height: 320px;
      }
      @media (min-width: 768px) {
        #files {
          min-height: 0;
        }
      }

      #debug {
        overflow-y: scroll !important;
        height: 180px;
      }


      .preview-img {
        width: 64px;
        height: 64px;
      }

      form {
        border: solid #f7f7f9 !important;
        padding: 1.5rem
      }

      form.active {
        border-color: red !important;
      }

      form .progress {
        height: 38px;
      }

      .dm-uploader {
        border: 0.25rem dashed #A5A5C7;
      }
      .dm-uploader.active {
        border-color: red;

        border-style: solid;
      }

      /*-- spacing utilities --*/
      .m-0 {
        margin: 0 0 !important;
      }
      .mt-0 {
        margin-top: 0 !important;
      }
      .mr-0 {
        margin-right: 0 !important;
      }
      .mb-0 {
        margin-bottom: 0 !important;
      }
      .ml-0 {
        margin-left: 0 !important;
      }
      .mx-0 {
        margin-right: 0 !important;
        margin-left: 0 !important;
      }
      .my-0 {
        margin-top: 0 !important;
        margin-bottom: 0 !important;
      }
      .m-1 {
        margin: 0.25rem 0.25rem !important;
      }
      .mt-1 {
        margin-top: 0.25rem !important;
      }
      .mr-1 {
        margin-right: 0.25rem !important;
      }
      .mb-1 {
        margin-bottom: 0.25rem !important;
      }
      .ml-1 {
        margin-left: 0.25rem !important;
      }
      .mx-1 {
        margin-right: 0.25rem !important;
        margin-left: 0.25rem !important;
      }
      .my-1 {
        margin-top: 0.25rem !important;
        margin-bottom: 0.25rem !important;
      }
      .m-2 {
        margin: 0.5rem 0.5rem !important;
      }
      .mt-2 {
        margin-top: 0.5rem !important;
      }
      .mr-2 {
        margin-right: 0.5rem !important;
      }
      .mb-2 {
        margin-bottom: 0.5rem !important;
      }
      .ml-2 {
        margin-left: 0.5rem !important;
      }
      .mx-2 {
        margin-right: 0.5rem !important;
        margin-left: 0.5rem !important;
      }
      .my-2 {
        margin-top: 0.5rem !important;
        margin-bottom: 0.5rem !important;
      }
      .m-3 {
        margin: 1rem 1rem !important;
      }
      .mt-3 {
        margin-top: 1rem !important;
      }
      .mr-3 {
        margin-right: 1rem !important;
      }
      .mb-3 {
        margin-bottom: 1rem !important;
      }
      .ml-3 {
        margin-left: 1rem !important;
      }
      .mx-3 {
        margin-right: 1rem !important;
        margin-left: 1rem !important;
      }
      .my-3 {
        margin-top: 1rem !important;
        margin-bottom: 1rem !important;
      }
      .m-4 {
        margin: 1.5rem 1.5rem !important;
      }
      .mt-4 {
        margin-top: 1.5rem !important;
      }
      .mr-4 {
        margin-right: 1.5rem !important;
      }
      .mb-4 {
        margin-bottom: 1.5rem !important;
      }
      .ml-4 {
        margin-left: 1.5rem !important;
      }
      .mx-4 {
        margin-right: 1.5rem !important;
        margin-left: 1.5rem !important;
      }
      .my-4 {
        margin-top: 1.5rem !important;
        margin-bottom: 1.5rem !important;
      }
      .m-5 {
        margin: 3rem 3rem !important;
      }
      .mt-5 {
        margin-top: 3rem !important;
      }
      .mr-5 {
        margin-right: 3rem !important;
      }
      .mb-5 {
        margin-bottom: 3rem !important;
      }
      .ml-5 {
        margin-left: 3rem !important;
      }
      .mx-5 {
        margin-right: 3rem !important;
        margin-left: 3rem !important;
      }
      .my-5 {
        margin-top: 3rem !important;
        margin-bottom: 3rem !important;
      }
      .p-0 {
        padding: 0 0 !important;
      }
      .pt-0 {
        padding-top: 0 !important;
      }
      .pr-0 {
        padding-right: 0 !important;
      }
      .pb-0 {
        padding-bottom: 0 !important;
      }
      .pl-0 {
        padding-left: 0 !important;
      }
      .px-0 {
        padding-right: 0 !important;
        padding-left: 0 !important;
      }
      .py-0 {
        padding-top: 0 !important;
        padding-bottom: 0 !important;
      }
      .p-1 {
        padding: 0.25rem 0.25rem !important;
      }
      .pt-1 {
        padding-top: 0.25rem !important;
      }
      .pr-1 {
        padding-right: 0.25rem !important;
      }
      .pb-1 {
        padding-bottom: 0.25rem !important;
      }
      .pl-1 {
        padding-left: 0.25rem !important;
      }
      .px-1 {
        padding-right: 0.25rem !important;
        padding-left: 0.25rem !important;
      }
      .py-1 {
        padding-top: 0.25rem !important;
        padding-bottom: 0.25rem !important;
      }
      .p-2 {
        padding: 0.5rem 0.5rem !important;
      }
      .pt-2 {
        padding-top: 0.5rem !important;
      }
      .pr-2 {
        padding-right: 0.5rem !important;
      }
      .pb-2 {
        padding-bottom: 0.5rem !important;
      }
      .pl-2 {
        padding-left: 0.5rem !important;
      }
      .px-2 {
        padding-right: 0.5rem !important;
        padding-left: 0.5rem !important;
      }
      .py-2 {
        padding-top: 0.5rem !important;
        padding-bottom: 0.5rem !important;
      }
      .p-3 {
        padding: 1rem 1rem !important;
      }
      .pt-3 {
        padding-top: 1rem !important;
      }
      .pr-3 {
        padding-right: 1rem !important;
      }
      .pb-3 {
        padding-bottom: 1rem !important;
      }
      .pl-3 {
        padding-left: 1rem !important;
      }
      .px-3 {
        padding-right: 1rem !important;
        padding-left: 1rem !important;
      }
      .py-3 {
        padding-top: 1rem !important;
        padding-bottom: 1rem !important;
      }
      .p-4 {
        padding: 1.5rem 1.5rem !important;
      }
      .pt-4 {
        padding-top: 1.5rem !important;
      }
      .pr-4 {
        padding-right: 1.5rem !important;
      }
      .pb-4 {
        padding-bottom: 1.5rem !important;
      }
      .pl-4 {
        padding-left: 1.5rem !important;
      }
      .px-4 {
        padding-right: 1.5rem !important;
        padding-left: 1.5rem !important;
      }
      .py-4 {
        padding-top: 1.5rem !important;
        padding-bottom: 1.5rem !important;
      }
      .p-5 {
        padding: 3rem 3rem !important;
      }
      .pt-5 {
        padding-top: 3rem !important;
      }
      .pr-5 {
        padding-right: 3rem !important;
      }
      .pb-5 {
        padding-bottom: 3rem !important;
      }
      .pl-5 {
        padding-left: 3rem !important;
      }
      .px-5 {
        padding-right: 3rem !important;
        padding-left: 3rem !important;
      }
      .py-5 {
        padding-top: 3rem !important;
        padding-bottom: 3rem !important;
      }


      element.style {
      }
      @media (min-width: 768px) {
        #files {
            min-height: 0;
        }

      }

      #files {
          overflow-y: scroll !important;
          min-height: 320px;
      }
      .p-2 {
          padding: .5rem!important;
      }
      .flex-column {
          -webkit-box-orient: vertical!important;
          -webkit-box-direction: normal!important;
          -ms-flex-direction: column!important;
          flex-direction: column!important;
      }
      .d-flex {
          display: -webkit-box!important;
          display: -ms-flexbox!important;
          display: flex!important;
      }
      .col {
          -ms-flex-preferred-size: 0;
          flex-basis: 0;
          -webkit-box-flex: 1;
          -ms-flex-positive: 1;
          flex-grow: 1;
          max-width: 100%;
      }

      .btn-primary, .btn-primary:focus, .btn-primary:hover {
            background-color: #011844;
            border-color: #011844;
      }

      .filelist {
          /* BOTH of the following are required for text-overflow */
          white-space: nowrap;
          overflow: hidden;
          text-overflow: ellipsis;
          margin-bottom: 10px;
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

      <script type="text/javascript" language="javascript" src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.8.4/moment.min.js"></script>
      <script type="text/javascript" language="javascript" src="https://cdn.datatables.net/plug-ins/1.10.15/sorting/datetime-moment.js"></script>
      {{-- <script type="text/javascript" language="javascript" src="{{ asset('/plugin/examples/resources/syntax/shCore.js') }}"></script>
      <script type="text/javascript" language="javascript" src="{{ asset('/plugin/examples/resources/demo.js') }}"></script>
      <script type="text/javascript" language="javascript" src="{{ asset('/plugin/examples/resources/editor-demo.js') }}"></script> --}}
      <script src="{{ asset('/plugin/dmUploader/js/jquery.dm-uploader.min.js') }}"></script>
      <script src="{{ asset('/plugin/dmUploader/js/ui-main.js') }}"></script>
      <script src="{{ asset('/plugin/dmUploader/js/ui-multiple.js') }}"></script>
      <script src="{{ asset('/plugin/dmUploader/js/ui-single.js') }}"></script>

      <script type="text/javascript" language="javascript" class="init">
          var staffpaysliptable;
          var selectedtabindex;
          var asInitVals = new Array();

          $(document).ready(function() {

              $.fn.dataTable.moment( 'DD-MMM-YYYY' );
              staffpaysliptable=$('#staffpaysliptable').dataTable( {
                  ajax: {
                      "url": "{{ asset('/Include/payslipmanagement.php') }}",
                      "data": {
                          "IncludeResigned": "{{ $includeResigned }}",
                          "IncludeInactive": "{{ $includeInactive }}"
                      }
                  },
                  columnDefs: [{ "visible": false, "targets": [1] },{"className": "dt-left", "targets": [4]},{"className": "dt-center", "targets": "_all"}],
                  responsive: false,
                  colReorder: false,
                  dom: "Blfrtip",
                  sScrollX: "100%",
                  bAutoWidth: true,
                  iDisplayLength:10,
                  sScrollY: "100%",
                  scrollCollapse: true,
                  rowId:"users.Id",
                  columns: [
                      {
                          sortable: false,
                          "render": function ( data, type, full, meta ) {
                              return '<input type="checkbox" name="selectrow0" id="selectrow0" class="selectrow0" value="'+full.users.Id+'" >';
                          }

                      },
                      { data: "users.Id",title:"Id"},
                      { data: "users.Password_Last_Notified",title:"Last Notify"},
                      { data: "users.Payslip_Password",title:"Password"},
                      { data: "users.StaffId",title:"StaffId"},
                      { data: "users.Name",title:"Name"},
                      { data: "users.NRIC",title:"NRIC"},
                      { data: "users.Joining_Date",title:"Joining_Date"},
                      { data: "users.Confirmation_Date",title:"Confirmation_Date"},
                      { data: "users.Position",title:"Position"},
                      { data: "users.Grade",title:"Grade"},
                      { data: "users.Company",title:"Company"},
                      { data: "users.Department",title:"Department"},
                      { data: "users.Category",title:"Category"},
                  ],

                  select: true,
                  buttons: [
                    {
                        extend: 'collection',
                        text: 'Export',
                        buttons: [
                            'excel',
                            'csv',
                            'pdf'
                        ]
                    },
                    {
                        text: 'Change Password',
                        action: function ( e, dt, node, config ) {
                            $('#Submit').modal('show');
                        }
                    },
                    {
                        text: 'Generate Password',
                        action: function ( e, dt, node, config ) {
                            $('#Submit2').modal('show');
                        }
                    },
                    {
                        text: 'Send Password',
                        action: function ( e, dt, node, config ) {
                            $('#Submit3').modal('show');
                        }
                    },
                    {
                        text: 'Send Password To All',
                        action: function ( e, dt, node, config ) {
                            $('#Submit4').modal('show');
                        }
                    }
                  ],
              });

              $("#ajaxloader").hide();
              $("#ajaxloader2").hide();
              $("#ajaxloader3").hide();
              $("#ajaxloader4").hide();

              $('a[data-toggle="tab"]').on( 'shown.bs.tab', function (e) {
                  var target = $(e.target).attr("href") // activated tab
                  $.fn.dataTable.tables( {visible: true, api: true} ).columns.adjust();
              } );


              $(".staffpaysliptable thead input").keyup ( function () {

                  /* Filter on the column (the index) of this element */
                  if ($('#staffpaysliptable').length > 0) {
                    var colnum=document.getElementById('staffpaysliptable').rows[0].cells.length;

                    if (this.value=="[empty]") {
                      staffpaysliptable.fnFilter( '^$', this.name,true,false );
                    } else if (this.value=="[nonempty]") {
                      staffpaysliptable.fnFilter( '^(?=\\s*\\S).*$', this.name,true,false );
                    } else if (this.value.startsWith("!")==true && this.value.length>1){
                      staffpaysliptable.fnFilter( '^(?'+ this.value +').*', this.name,true,false );
                    } else if (this.value.startsWith("!")==false) {
                      staffpaysliptable.fnFilter( this.value, this.name,true,false );
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
        Payslip Management
        <small>Resource Management</small>
      </h1>
      <ol class="breadcrumb">
        <li><a href="{{ url('/home') }}"><i class="fa fa-dashboard"></i> Home</a></li>
        <li><a href="#">Management Tool</a></li>
        <li><a href="#">HR Management</a></li>
        <li class="active">Payslip Management</li>
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

        <div class="modal fade" id="Submit" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
          <div class="modal-dialog" role="document">
            <div class="modal-content">
              <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title" id="myModalLabel">Change / Assign Password</h4>
              </div>
              <div class="modal-body">
                  <input class="form-control" type="text" id="Password" name="Password">
                  <span id="Password_Error" class="text-danger"></span>
              </div>
              <div class="modal-footer">
                <center><img src="{{ URL::to('/') ."/img/ajax-loader.gif" }}" width="50px" height="50px" alt="Loading" name='ajaxloader' id="ajaxloader"></center>
                <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                <button type="button" class="btn btn-primary" onclick="changepassword()">Change</button>
              </div>
            </div>
          </div>
        </div>

        <div class="modal fade" id="RemovePayslipModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
          <div class="modal-dialog" role="document">
            <div class="modal-content">
              <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title" id="myModalLabel">Remove File</h4>
              </div>
              <div class="modal-body">
                  <input class="form-control" type="hidden" id="filepath">
                  <p>Are you sure you want to remove this file?</p>
              </div>
              <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                <button type="button" class="btn btn-primary" onclick="removePayslip()">Remove</button>
              </div>
            </div>
          </div>
        </div>

        <div class="modal fade" id="authorizationModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
          <div class="modal-dialog" role="document">
            <div class="modal-content">
              <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title" id="myModalLabel">Change Authorization Password</h4>
              </div>
              <div class="modal-body">
                  <div class="form-group">
                    <label class="control-label">Old Password</label>
                    <input class="form-control" type="password" id="OldPassword" name="OldPassword">
                  </div>
                  <div class="form-group">
                    <label class="control-label">New Password</label>
                    <input class="form-control" type="password" id="NewPassword" name="NewPassword">
                  </div>
                  <div class="form-group">
                    <label class="control-label">Confirm New Password</label>
                    <input class="form-control" type="password" id="ConfirmPassword" name="ConfirmPassword">
                  </div>
                  <span id="Password_Success2" class="text-success"></span>
                  <span id="Password_Error2" class="text-danger"></span>
              </div>
              <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                <button type="button" class="btn btn-primary" onclick="changeAuthorizationPassword()">Change</button>
              </div>
            </div>
          </div>
        </div>

        <div class="modal fade" id="Submit2" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
          <div class="modal-dialog" role="document">
            <div class="modal-content">
              <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title" id="myModalLabel">Generate Random Password</h4>
              </div>
              <div class="modal-body">
                  <p>Are you sure you want to generate password for the selected users?</p>
              </div>
              <div class="modal-footer">
                <center><img src="{{ URL::to('/') ."/img/ajax-loader.gif" }}" width="50px" height="50px" alt="Loading" name='ajaxloader2' id="ajaxloader2"></center>
                <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                <button type="button" class="btn btn-primary" onclick="generatepassword()">Generate Password</button>
              </div>
            </div>
          </div>
        </div>

        <div class="modal fade" id="Submit3" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
          <div class="modal-dialog" role="document">
            <div class="modal-content">
              <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title" id="myModalLabel">Send Payslip Password</h4>
              </div>
              <div class="modal-body">
                  <p>Send notification to selected users?</p>
              </div>
              <div class="modal-footer">
                <center><img src="{{ URL::to('/') ."/img/ajax-loader.gif" }}" width="50px" height="50px" alt="Loading" name='ajaxloader2' id="ajaxloader3"></center>
                <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                <button type="button" class="btn btn-primary" onclick="sendpasswordtoselected()">Send</button>
              </div>
            </div>
          </div>
        </div>

        <div class="modal fade" id="Submit4" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
          <div class="modal-dialog" role="document">
            <div class="modal-content">
              <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title" id="myModalLabel">Send Payslip Password To All Users</h4>
              </div>
              <div class="modal-body">
                  <p>Send notification to all active users?</p>
                  <!-- <p class="text-sm text-warning">Note: This may take a longer time, it is recommended to send to selected users only.</p> -->
              </div>
              <div class="modal-footer">
                <center><img src="{{ URL::to('/') ."/img/ajax-loader.gif" }}" width="50px" height="50px" alt="Loading" name='ajaxloader2' id="ajaxloader4"></center>
                <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                <button type="button" class="btn btn-primary" onclick="sendpasswordtoall()">Send</button>
              </div>
            </div>
          </div>
        </div>

        {{-- <div class="col-md-6">
            <div class="box box-solid">
                <div class="box-header with-border">
                    <div id="calendar"></div>
                </div>
            </div>
        </div> --}}
      </div>

      <div class="row">
        <div class="col-md-12">
          <div class="nav-tabs-custom">
            <ul class="nav nav-tabs">
              <li class="active"><a href="#filelist" data-toggle="tab" id="filelisttab">File List</a></li>
              <li><a href="#uploadfile" data-toggle="tab" id="uploadfiletab">Upload Payslip</a></li>
              <li><a href="#staffpayslip" data-toggle="tab" id="staffpaysliptab">Staff List</a></li>
            </ul>

            <div class="tab-content">

                <div class="active tab-pane" id="filelist">
                  @if(empty($directories) && empty($files))
                  <div class="row">
                    <div class="col-md-12">
                        <h3>No files was found.</h3>
                    </div>
                  </div>
                  @endif
                  <div class="row">
                      @if (! (request()->input('path', 'payslips') == 'payslips'))
                      <div class="col-md-2 text-center filelist">
                        <a href="{{ request()->url() . '?path=' . dirname(request()->input('path', 'payslips')) }}"><i class="fa fa-5x fa-folder" aria-hidden="true"></i><br>
                        Back</a>
                      </div>
                      @endif

                      @foreach($directories as $directory)
                      <div class="col-md-2 text-center filelist">
                        <a href="{{ request()->url() . '?path=' . $directory }}"><i class="fa fa-5x fa-folder-o" aria-hidden="true"></i><br>
                        {{ basename($directory) }}
                        </a>
                      </div>
                      @endforeach
                  </div>
                  @if(count($files))
                  <hr>
                  @endif
                  <div class="row">
                    @foreach($files as $file)
                    <div class="col-md-2 text-center filelist" id="file_{{preg_replace('/[^a-zA-Z0-9_]/', '_', $file)}}">
                      <a href="{{ url('payslipmanagement/viewpayslip?file=' . $file) }}" target="_blank"><i class="fa fa-5x fa-file-o" aria-hidden="true"></i><br>
                      <span>{{ basename($file) }}</span></a><br>
                      <button class="btn btn-xs btn-danger" onclick="removeFile('{{$file}}')">Remove</button>
                    </div>
                    @endforeach
                  </div>
                </div>


              <div class="tab-pane" id="staffpayslip">
                <div class="row">

                    <div class="col-md-2">
                       <div class="checkbox">
                         <label><input type="checkbox" id="includeresigned" name="includeresigned" {{ $includeResigned == 'true' ? 'checked' : '' }}> Include Resigned</label>
                       </div>
                     </div>
                     <div class="col-md-2">
                       <div class="checkbox">
                         <label><input type="checkbox" id="includeinactive" name="includeinactive" {{ $includeInactive == 'true' ? 'checked' : '' }}> Include Inactive</label>
                       </div>
                     </div>
                     <div class="col-md-6">


                           <button type="button" class="btn btn-success btn" data-toggle="modal" onclick="refresh();">Refresh</button>
                           <button type="button" class="btn btn-success" data-toggle="modal" data-target="#authorizationModal">Authorization</button>

                     </div>

                </div>
                <table id="staffpaysliptable" class="staffpaysliptable" cellspacing="0" width="100%" padding="30px" style="font-size: 13px;">
                    <thead>
                        {{-- prepare header search textbox --}}
                        @if($users)
                          <tr class="search">

                            @foreach($users as $key=>$value)

                              @if ($key==0)
                                <?php $i = 0; ?>

                                @foreach($value as $field=>$a)
                                    @if ($i==0|| $i==1)
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
                        @endif
                        <tr>
                          @foreach($users as $key=>$value)

                            @if ($key==0)
                              <td><input type="checkbox" name="selectall0" id="selectall0" value="all" onclick="checkall(0)"></td>

                              @foreach($value as $field=>$value)
                                  <td/>{{ $field }}</td>
                              @endforeach

                            @endif

                          @endforeach
                        </tr>
                    </thead>
                    <tbody>

                      <?php $i = 0; ?>
                      @foreach($users as $user)

                          <tr id="row_{{ $i }}">
                            <td></td>
                            @foreach($user as $key=>$value)
                              <td>{{ $value }}</td>
                            @endforeach
                          </tr>
                          <?php $i++; ?>

                      @endforeach

                  </tbody>
                  <tfoot></tfoot>
                </table>
              </div>

              <div class="tab-pane" id="uploadfile">
                <div class="row">
                  <div class="col-md-3">
                    <div class="form-group">
                      <select class="form-control" name="Payslip_Month" id="Payslip_Month">
                        <option></option>
                        <option selected>{{ date('F',strtotime("-1 month")) }}</option>
                        <option>January</option>
                        <option>February</option>
                        <option>March</option>
                        <option>April</option>
                        <option>May</option>
                        <option>June</option>
                        <option>July</option>
                        <option>August</option>
                        <option>September</option>
                        <option>October</option>
                        <option>November</option>
                        <option>December</option>
                      </select>
                    </div>
                  </div>
                  <div class="col-md-3">
                    <div class="form-group">
                      <select class="form-control" name="Payslip_Year" id="Payslip_Year">
                        <option></option>
                        <option selected>{{ date('Y') }}</option>
                        @for($year=date('Y')-2;$year <= date('Y')+1; $year++)
                          <option>{{ $year }}</option>
                        @endfor
                      </select>
                    </div>
                  </div>

                </div>
                  <div class="row">
                     <div class="col-md-6 col-sm-12">
                        <div id="drag-and-drop-zone" class="dm-uploader p-5 text-center">
                          <h3 class="mb-5 mt-5 text-muted">Drag &amp; drop Files here</h3>

                          <div class="btn btn-primary btn-block mb-5">
                              <span>Open the file Browser</span>
                              <input type="file" title='Click to add Files' />
                          </div>
                        </div><!-- /uploader -->

                      </div>
                      <div class="col-md-6 col-sm-12">
                        <div class="panel panel-default h-100">
                          <div class="panel-heading">
                            File List
                          </div>

                          <ul class="list-unstyled p-2 d-flex flex-column col" id="files">
                            <li class="text-muted text-center empty">No files uploaded.</li>
                          </ul>
                        </div>
                      </div>
                  </div><!-- /file list -->

                  <!-- File item template -->
                  <script type="text/html" id="files-template">
                    <li class="media">

                      <div class="media-body mb-1">
                        <p class="mb-2">
                          <strong>%%filename%%</strong> - Status: <span class="text-muted">Waiting</span>
                        </p>
                        <div class="progress">
                          <div class="progress-bar progress-bar-primary progress-bar-striped"
                            role="progressbar"
                            style="width: 0%"
                            aria-valuenow="0" aria-valuemin="0" aria-valuemax="100">
                          </div>
                        </div>

                        <hr class="mt-1 mb-1" />
                      </div>
                    </li>
                  </script>
                </div>


          </div>
          <!-- /.nav tab content -->
        </div>
        <!-- /.av-tabs-custom -->

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

      $('#drag-and-drop-zone').dmUploader({ //
          url: "{{ url('payslipmanagement/uploadpayslip') }}",
          headers: {
             'X-CSRF-Token': $('meta[name="_token"]').attr('content')
          },
          allowedTypes: "application/pdf",

          extraData: function() {
             return {
               "Payslip_Month": $('#Payslip_Month').val(),
               "Payslip_Year": $('#Payslip_Year').val(),
             };
          },
          // maxFileSize: 3000000, // 3 Megs
          onDragEnter: function(){
            // Happens when dragging something over the DnD area
            this.addClass('active');
          },
          onDragLeave: function(){
            // Happens when dragging something OUT of the DnD area
            this.removeClass('active');
          },
          onInit: function(){
            // Plugin is ready to use
            // ui_add_log('Penguin initialized :)', 'info');
          },
          onComplete: function(){
            // All files in the queue are processed (success or error)
            // ui_add_log('All pending tranfers finished');
            $('#Payslip_Month').val('');
            $('#Payslip_Year').val('');
          },
          onNewFile: function(id, file){
            // When a new file is added using the file selector or the DnD area
            if ($("#Payslip_Month").val() == '' || $("#Payslip_Year").val() == '') {
              var errormessage="Month and year need to be selected!";
              $("#error-alert ul").html(errormessage);
              $("#error-alert").modal('show');
              return false;
            }

            // ui_add_log('New file added #' + id);
            ui_multi_add_file(id, file);
          },
          onBeforeUpload: function(id){
            // about tho start uploading a file
            // ui_add_log('Starting the upload of #' + id);
            ui_multi_update_file_progress(id, 0, '', true);
            ui_multi_update_file_status(id, 'uploading', 'Uploading...');
          },
          onUploadProgress: function(id, percent){
            // Updating file progress
            ui_multi_update_file_progress(id, percent);
          },
          onUploadSuccess: function(id, data){
            // A file was successfully uploaded
            // ui_add_log('Server Response for file #' + id + ': ' + JSON.stringify(data));
            // ui_add_log('Upload of file #' + id + ' COMPLETED', 'success');
            ui_multi_update_file_status(id, 'success', 'Upload Complete');
            ui_multi_update_file_progress(id, 100, 'success', false);
          },
          onUploadError: function(id, xhr, status, message){
            // Happens when an upload error happens
            ui_multi_update_file_status(id, 'danger', message);
            ui_multi_update_file_progress(id, 0, 'danger', false);
          },
          onFallbackMode: function(){
            // When the browser doesn't support this plugin :(
            // ui_add_log('Plugin cant be used here, running Fallback callback', 'danger');
          },
          onFileSizeError: function(file){
            // ui_add_log('File \'' + file.name + '\' cannot be added: size excess limit', 'danger');
          }
      });

      //Initialize Select2 Elements
      $(".select2").select2();

      //Flat red color scheme for iCheck
      $('input[type="checkbox"].flat-blue, input[type="radio"].flat-blue').iCheck({
        checkboxClass: 'icheckbox_flat-blue',
        radioClass: 'iradio_flat-blue'
      });

      //Date picker
      $('#Start_Date').datepicker({
        autoclose: true
      });

      $('#End_Date').datepicker({
        autoclose: true
      });

    });

    function checkall(index) {

      if ($("#selectall"+index).is(':checked')) {

          $(".selectrow"+index).prop("checked", true);

          $(".selectrow"+index).trigger("change");

          staffpaysliptable.rows().select();

      } else {

          $(".selectrow"+index).prop("checked", false);
          $(".selectrow"+index).trigger("change");

          staffpaysliptable.rows().deselect();
      }
    }

    function changepassword() {

      var password = $("#Password").val();
      var boxes = $('input[type="checkbox"]:checked', staffpaysliptable.fnGetNodes() );
      var ids="";


      if (password.length < 6) {
        $("#Password_Error").html("Password length must be at least 6 characters.");
        return;
      }

      $("#Password_Error").html("");

      $("#Submit").modal("hide");

      if (boxes.length > 0 && password != '') {
        for (var i = 0; i < boxes.length; i++) {
          ids+=boxes[i].value+",";
        }

        ids=ids.substring(0, ids.length-1);


        $.ajaxSetup({
          headers: { 'X-CSRF-Token' : $('meta[name=_token]').attr('content') }
        });

        $.ajax({
          url: "{{ url('/payslipmanagement/changepassword') }}",
          method: "POST",
          data: { Ids:ids, Password:password },
          success: function(response){
            if (response == 1) {
              staffpaysliptable.api().ajax.url("{{ asset('/Include/payslipmanagement.php') }}").load();
              var message="Password sucessfully assigned!";
              $("#update-alert ul").html(message);
              $("#update-alert").modal('show');
              $("#ajaxloader").hide();
            } else {
              var errormessage="Failed to assign password!";
              $("#error-alert ul").html(errormessage);
              $("#error-alert").modal('show');
              $("#ajaxloader").hide();
            }

          }
        });

      } else {
        var errormessage="No user selected!";
        $("#error-alert ul").html(errormessage);
        $("#error-alert").modal('show');
      }

    }

    function changeAuthorizationPassword() {

      var oldpassword = $("#OldPassword").val();
      var newpassword = $("#NewPassword").val();
      var confirmpassword = $("#ConfirmPassword").val();

      $("#Password_Error2").html("");
      $("#Password_Success2").html("");

      if (confirmpassword !== newpassword) {
        var errormessage="Confirmation password not matched!";
        $("#Password_Error2").html(errormessage);
        return;
      }

      $.ajaxSetup({
        headers: { 'X-CSRF-Token' : $('meta[name=_token]').attr('content') }
      });

      $.ajax({
        url: "{{ url('/payslipmanagement/changeauthorizationpassword') }}",
        method: "POST",
        data: { Password:oldpassword, NewPassword: newpassword },
        success: function(response){
          if (response == 1) {
            var message="Password sucessfully changed!";
            $("#Password_Success2").html(message);
          } else {
            var errormessage="Failed to change password!";
            $("#Password_Error2").html(errormessage);
          }

        }
      });
    }

    function generatepassword() {

      var boxes = $('input[type="checkbox"]:checked', staffpaysliptable.fnGetNodes() );
      var ids="";


      $("#Submit2").modal("hide");

      if (boxes.length > 0) {
        for (var i = 0; i < boxes.length; i++) {
          ids+=boxes[i].value+",";
        }

        ids=ids.substring(0, ids.length-1);


        $.ajaxSetup({
          headers: { 'X-CSRF-Token' : $('meta[name=_token]').attr('content') }
        });

        $.ajax({
          url: "{{ url('/payslipmanagement/generatepassword') }}",
          method: "POST",
          data: { Ids:ids },
          success: function(response){
            if (response == 1) {
              staffpaysliptable.api().ajax.url("{{ asset('/Include/payslipmanagement.php') }}").load();
              var message="Password sucessfully generated!";
              $("#update-alert ul").html(message);
              $("#update-alert").modal('show');
              $("#ajaxloader").hide();
            } else {
              var errormessage="Failed to generate password!";
              $("#error-alert ul").html(errormessage);
              $("#error-alert").modal('show');
              $("#ajaxloader").hide();
            }

          }
        });

      } else {
        var errormessage="No user selected!";
        $("#error-alert ul").html(errormessage);
        $("#error-alert").modal('show');
      }

    }

    function sendpasswordtoselected() {

      var boxes = $('input[type="checkbox"]:checked', staffpaysliptable.fnGetNodes() );
      var ids="";

      if (boxes.length > 0) {

        $("#ajaxloader3").show();

        for (var i = 0; i < boxes.length; i++) {
          ids+=boxes[i].value+",";
        }

        ids=ids.substring(0, ids.length-1);


        $.ajaxSetup({
          headers: { 'X-CSRF-Token' : $('meta[name=_token]').attr('content') }
        });

        $.ajax({
          url: "{{ url('/payslipmanagement/sendpasswordtoselected') }}",
          method: "POST",
          data: { Ids:ids },
          success: function(response){
            if (response == 1) {
              $("#Submit3").modal("hide");
              staffpaysliptable.api().ajax.reload(null, false);
              var message="Password sent sucessfully to selected users!";
              $("#update-alert ul").html(message);
              $("#update-alert").modal('show');
              $("#ajaxloader3").hide();
            } else {
              $("#Submit3").modal("hide");

              var errormessage="Error occured!";
              $("#error-alert ul").html(errormessage);
              $("#error-alert").modal('show');
              $("#ajaxloader3").hide();
            }

          },
          error: function (error) {
            $("#Submit4").modal("hide");
            var errormessage="Error occured!";
            $("#error-alert ul").html(errormessage);
            $("#error-alert").modal('show');
            $("#ajaxloader4").hide();
          }
        });

      } else {
        $("#Submit3").modal("hide");
        var errormessage="No user selected!";
        $("#error-alert ul").html(errormessage);
        $("#error-alert").modal('show');
      }

    }

    function sendpasswordtoall() {
      $("#ajaxloader4").show();

      $.ajaxSetup({
        headers: { 'X-CSRF-Token' : $('meta[name=_token]').attr('content') }
      });

      $.ajax({
        url: "{{ url('/payslipmanagement/sendpasswordtoall') }}",
        method: "POST",
        success: function(response){
          if (response == 1) {
            $("#Submit4").modal("hide");
            staffpaysliptable.api().ajax.reload(null, false);
            var message="Password sent sucessfully to all active users!";
            $("#update-alert ul").html(message);
            $("#update-alert").modal('show');
            $("#ajaxloader4").hide();
          } else {
            $("#Submit4").modal("hide");
            var errormessage="Error occured!";
            $("#error-alert ul").html(errormessage);
            $("#error-alert").modal('show');
            $("#ajaxloader4").hide();
          }

        },
        error: function (error) {
          $("#Submit4").modal("hide");
          var errormessage="Error occured!";
          $("#error-alert ul").html(errormessage);
          $("#error-alert").modal('show');
          $("#ajaxloader4").hide();
        }
      });

    }

    function refresh()
    {

      var includeresigned=$('#includeresigned').is(':checked');
      var includeinactive=$('#includeinactive').is(':checked');

      window.location.href ="{{ url("/payslipmanagement") }}/"+includeresigned+"/"+includeinactive;

    }

    function removeFile(file) {

      $('#filepath').val(file);

      $('#RemovePayslipModal').modal('show');
    }

    function removePayslip() {
      $('#RemovePayslipModal').modal('hide');
      var file = $('#filepath').val();
      console.log(file);
      $.ajaxSetup({
        headers: { 'X-CSRF-Token' : $('meta[name=_token]').attr('content') }
      });

      $.ajax({
        url: "{{ url('/payslipmanagement/removepayslip') }}",
        method: "POST",
        data: { file:file },
        success: function(response){
          if (response == 1) {
            $('#file_' + file.replace(/[^A-Z0-9_]/ig, "_")).remove();
            var message="File was deleted!";
            $("#update-alert ul").html(message);
            $("#update-alert").modal('show');
            $("#ajaxloader").hide();
          } else {
            var errormessage="Failed to delete file!";
            $("#error-alert ul").html(errormessage);
            $("#error-alert").modal('show');
            $("#ajaxloader").hide();
          }

        }
      });

    }
  </script>

@endsection
