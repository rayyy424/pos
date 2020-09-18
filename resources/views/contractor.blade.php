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

      .profile-user-dt-img{
        width: 80px;
        height: 100px;
      }

    </style>

    <script type="text/javascript" language="javascript" class="init">

        var editor; // use a global for the submit and return data rendering in the examples
        var asInitVals = new Array();
        var oTable;

        $(document).ready(function() {
          editor = new $.fn.dataTable.Editor( {
                   ajax: "{{ asset('/Include/contractor.php') }}",
                  table: "#contractortable",
                  idSrc: "users.Id",
                  fields: [
                          {
                                 label: "StaffId:",
                                 name: "users.StaffId"
                         },{
                                 label: "Password:",
                                 name: "users.Password",
                                 type: "password"
                         },{
                                  label: "Name:",
                                  name: "users.Name"
                          }, {
                                  label: "Company Email:",
                                  name: "users.Company_Email"
                          }, {
                                  label: "Contact No 1:",
                                  name: "users.Contact_No_1"
                          }, {
                                  label: "Contact No 2:",
                                  name: "users.Contact_No_2"
                          }, {
                                  label: "Permanent Address:",
                                  name: "users.Permanent_Address",
                                  type: "textarea"
                          }, {
                                  label: "Current Address:",
                                  name: "users.Current Address",
                                  type: "textarea"
                          }, {
                                  label: "User Type:",
                                  name: "users.User_Type",
                                  type:  "select",
                                  options: [
                                      { label :"Contractor", value: "Contractor" },
                                  ],
                          }, {
                                  label: "Nationality:",
                                  name: "users.Nationality",
                                  type:  "select",
                                  options: [
                                      { label :"", value: "" },
                                      @foreach($options as $option)
                                        @if ($option->Field=="Nationality")
                                          { label :"{{$option->Option}}", value: "{{$option->Option}}" },
                                        @endif
                                      @endforeach
                                  ],
                          }, {
                                  label: "DOB:",
                                  name: "users.DOB",
                                  type:   'datetime',
                                  def:    function () { return new Date(); },
                                  format: 'DD-MMM-YYYY'
                          }, {
                                  label: "NRIC:",
                                  name: "users.NRIC"
                          }, {
                                  label: "Passport No:",
                                  name: "users.Passport_No"
                          }, {
                                  label: "Gender:",
                                  name: "users.Gender",
                                  type:  "select",
                                  options: [
                                    { label :"", value: "" },
                                    @foreach($options as $option)
                                      @if ($option->Field=="Gender")
                                        { label :"{{$option->Option}}", value: "{{$option->Option}}" },
                                      @endif
                                    @endforeach
                                  ],
                          }

                  ]
          } );

          // $('#contractortable').on( 'click', 'tbody td:not(:first-child)', function (e) {
          //       editor.inline( this, {
          // onBlur: 'submit'
          //     } );
          // } );

          editor.on('open', function () {
               $('div.DTE_Footer').css( 'text-indent', -1 );
           });


          oTable=$('#contractortable').dataTable( {
                  ajax: "{{ asset('/Include/contractor.php') }}",
                  columnDefs: [{ "visible": false, "targets": [1] },{"className": "dt-center", "targets": "_all"}],
                  responsive: false,
                  colReorder: false,
                  sScrollX: "100%",
                  iDisplayLength:10,
                  bAutoWidth: false,
                  rowId: 'users.Id',
                  sScrollY: "100%",
                  dom: "Bfrtip",
                  columns: [
                           {
                              sortable: false,
                              "render": function ( data, type, full, meta ) {
                                  @if ($me->View_CV)
                                     return '<a href="contractor/'+full.users.Id+'" >Edit</a>';
                                  @else
                                     return '-';
                                  @endif

                              }
                          },
                          { data: "users.Id"},
                         //  { data: "files.Web_Path",
                         //  render: function (  data, type, full, meta ) {

                         //       if (data)
                         //       { 
                         //         return '<img class="profile-user-dt-img img-responsive img-circle" src="'+ data +'" alt="User profile picture">';
                         //       }
                         //       else {
                         //         return '<img class="profile-user-dt-img img-responsive img-circle" src="{{ URL::to('/') ."/img/default-user.png"  }}" alt="User profile picture">';
                         //       }

                         //   },
                         //   title: "Image"
                         // },
                         { data: "users.StaffId" },
                         // { data: "users.Password" },
                         { data: "users.Name" },
                         { data: "users.Company" },
                          { data: "users.Company_Email" }
                          // { data: "users.Contact_No_1"},
                          // { data: "users.Contact_No_2"},
                          // { data: "users.Permanent_Address"},
                          // { data: "users.Current_Address"},
                          // { data: "users.Nationality"},
                          // { data: "users.NRIC"},
                          // { data: "users.Passport_No"},
                          // { data: "users.Gender"}

                  ],
                  autoFill: {
                     editor:  editor,
                     columns: [ 2, 3, 4, 5,6,7,8,9,10,11,12,13 ]
                 },
                //  keys: {
                //      columns: ':not(:first-child)',
                //      editor:  editor
                //  },
                  select: true,
                  buttons: [
                          // { extend: "create", editor: editor},
                          // { extend: "remove", editor: editor },
                          {
                            text: 'New',
                            action: function ( e, dt, node, config ) {
                              OpenModal();
                            }
                          },
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

          $('#contractortable').on( 'click', 'tr', function () {
          // Get the rows id value
          //  var row=$(this).closest("tr");
          //  var oTable = row.closest('table').dataTable();
          userid = oTable.api().row( this ).data().users.Id;
          });

          $("thead input").keyup ( function () {

                /* Filter on the column (the index) of this element */
                if ($('#contractortable').length > 0)
                {

                    var colnum=document.getElementById('contractortable').rows[0].cells.length;

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

  <!-- Content Wrapper. Contains page content -->
    {{-- <div class="content-wrapper">
        <!-- Content Header (Page header) -->
        <section class="content-header">
            @foreach ($users as $user)
                <p>
                    {{ $user->Name }}
                </p>
            @endforeach
        </section>
    </div> --}}

    <div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
      <h1>
        Contractor / Vendor Profile
        <small>Human Resource</small>
      </h1>
      <ol class="breadcrumb">
        <li><a href="{{ url('/home') }}"><i class="fa fa-dashboard"></i> Home</a></li>
        <li><a href="#">Human Resource</a></li>
        <li class="active">Contractor / Vendor Profile</li>
      </ol>
    </section>

    <!-- Main content -->
    <section class="content">
      <div class="row">
        <div class="col-xs-12">
          <div class="box">
            <!-- /.box-header -->
            <div class="box-body">
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

      <div class="modal fade" id="CreateModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
            <div class="modal-dialog" role="document">
              <div class="modal-content">
                <div class="modal-header">
                  <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                  <h4 class="modal-title" id="myModalLabel">Create</h4>
                </div>
                <div class="modal-body">
                  <div class="row">
                    <div class="col-md-10">
                      <form id="upload_form">
                        {{ csrf_field() }}
                        <label>StaffId</label>
                        <input type="text" name="staffid" class="form-control" required="required">
                        <label>Name</label>
                        <input type="text" name="name" class="form-control" required="">
                        <label>Password</label>
                        <input type="password" name="password" class="form-control" required="">
                        <label>Company</label>
                        <select name="company" class="form-control" required="">
                          <option value="">NONE</option>
                          @foreach($company as $c)
                          <option value="{{$c->Company_Name}}">{{$c->Company_Name}}</option>
                          @endforeach
                        </select>
                        <label>Company Email</label>
                        <input type="email" name="email" class="form-control" required="">
                      </form>
                    </div>
                  </div>
                </div>
                <div class="modal-footer">
                  <!-- <center><img src="{{ URL::to('/') . "/img/ajax-loader.gif" }}" width="50px" height="50px" alt="Loading" name='ajaxloader3' id="ajaxloader3"></center> -->
                  <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                  <button type="button" class="btn btn-primary" id="confirmbtn" onclick="submit()">Confirm</button>
                </div>
              </div>
            </div>
      </div>

                <table id="contractortable" class="display" cellspacing="0" width="100%" padding="30px" style="font-size: 13px;">
                  <thead>
                      {{-- prepare header search textbox --}}

                      <tr class="search">
                        <td align='center'><input type='hidden' class='search_init' /></td>
                        @foreach($contractors as $key=>$values)

                          @if ($key==0)

                            @foreach($values as $field=>$value)
                              @if ($field=="Web_Path")
                                <td align='center'><input type='hidden' class='search_init' /></td>
                              @else
                                <td align='center'><input type='text' class='search_init' /></td>
                              @endif

                            @endforeach

                          @endif

                        @endforeach
                      </tr>

                      <tr>
                        <td align='center'></td>
                        @foreach($contractors as $key=>$values)

                          @if ($key==0)

                            @foreach($values as $field=>$value)
                                <td  align='center'>{{ $field }}</td>
                            @endforeach

                          @endif

                        @endforeach
                      </tr>

                  </thead>
                  <tbody>

                    <?php $i = 0; ?>
                    @foreach($contractors as $contractor)

                      <tr id="row_{{ $i }}">
                          <td></td>
                          @foreach($contractor as $key=>$value)
                            <td>
                              {{ $value }}
                            </td>
                          @endforeach
                      </tr>
                      <?php $i++; ?>
                    @endforeach

                    {{-- <tr id="row_1">
                        <td>123321123</td>
                        <td>Email</td>
                        <td>position</td>

                    </tr>

                    <tr id="row_2" >
                        <td>123321123</td>
                        <td>Email</td>
                        <td>position</td>

                    </tr> --}}
                </tbody>
                  <tfoot></tfoot>
			          </table>
            </div>
            <!-- /.box-body -->
          </div>
          <!-- /.box -->
          <!-- /.box -->
        </div>
        <!-- /.col -->
      </div>
      <!-- /.row -->
    </section>
    <!-- /.content -->
  </div>
  <!-- /.content-wrapper -->
  <footer class="main-footer">
    <div class="pull-right hidden-xs">
      <b>Version</b> 1.0.0
    </div>
    <strong>Copyright &copy; 2014-2016 <a href="http://www.softoya.com">TrackerOnTheGo</a>.</strong> All rights
    reserved.
  </footer>

<script type="text/javascript">

  $(function () {
    $( "#upload_form" ).submit(function( event ) {
      event.preventDefault();
      return false;
    });
  });
  function OpenModal()
  {
    $('#CreateModal').modal('show');
  }
  function submit()
  {
    $.ajaxSetup({
           headers: { 'X-CSRF-Token' : $('meta[name=_token]').attr('content') }
          });
          $.ajax({
                  url: "{{ url('/contractor/create') }}",
                  method: "POST",
                  contentType: false,
                  processData: false,
                  data:new FormData($("#upload_form")[0]),
                  success: function(response){
                    $('#CreateModal').modal('hide');
                    if (response==1)
                    {
                         $(".form-control").val("")
                         var message="New contractor account created";
                         $("#update-alert ul").html(message);
                         $("#update-alert").modal('show');
                         window.location.reload();
                    }
                    else 
                    {
                        $('html, body').animate({scrollTop: '0px'}, 500);
                        var errormessage = "Failed to create new account";
                        $("#error-alert ul").html(errormessage);
                        $("#error-alert").modal('show');
                    }
                  }
                });
  }
</script>
@endsection
