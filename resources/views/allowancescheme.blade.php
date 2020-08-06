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
      var schemeitemeditor;
      var oTable;
      var schemetable;
      var currentschemeid;

      $(document).ready(function() {

            // $('#Scheme option')[1].selected = true;
            // $( "#Scheme" ).change()

            editor = new $.fn.dataTable.Editor( {
                    ajax: "{{ asset('/Include/accountallowancecontrol.php') }}",
                    table: "#alltable",
                    idSrc: "users.Id",
                    fields: [
                            {
                                   label: "Scheme Name:",
                                   name: "users.AllowanceSchemeId",
                                   type:  'select',
                                   options: [
                                       @foreach($schemes as $scheme)
                                           { label :"{{$scheme->Scheme_Name}}", value: "{{$scheme->Id}}" },
                                       @endforeach

                                   ],
                            }


                    ]
            } );

            schemeitemeditor = new $.fn.dataTable.Editor( {
                    ajax: "{{ asset('/Include/allowanceitem.php') }}",
                    table: "#schemetable",
                    idSrc: "allowanceschemeitems.Id",
                    fields: [
                            {
                                   name: "allowanceschemeitems.AllowanceSchemeId",
                                   type: "hidden"
                            },{
                                   label: "Day Type:",
                                   name: "allowanceschemeitems.Day_Type",
                                   type: "select",
                                   options: [
                                           { label :"", value: "" },
                                           { label :"Weekday", value: "Weekday" },
                                           { label :"Saturday", value: "Saturday" },
                                           { label :"Sunday", value: "Sunday" },
                                           { label :"Public Holiday", value: "Public Holiday" }

                                   ]
                            },{
                                   label: "Start:",
                                   name: "allowanceschemeitems.Start",
                                   type:   'datetime',
                                   def:    function () { return new Date(); },
                                   format: 'h:mm A'
                            },{
                                   label: "End:",
                                   name: "allowanceschemeitems.End",
                                   type:   'datetime',
                                   def:    function () { return new Date(); },
                                   format: 'h:mm A'
                            },{
                                   label: "Minimum Hour:",
                                   name: "allowanceschemeitems.Minimum_Hour"
                            },{
                                   label: "Currency:",
                                   name: "allowanceschemeitems.Currency",
                                   type: "select",
                                   options: [
                                           { label :"RM", value: "RM" },
                                           { label :"USD", value: "USD" }

                                   ]
                            },{
                                   label: "Home Base:",
                                   name: "allowanceschemeitems.Home_Base"
                            },{
                                   label: "Outstation:",
                                   name: "allowanceschemeitems.Outstation"
                            },{
                                   label: "Subsequent_Home_Base:",
                                   name: "allowanceschemeitems.Subsequent_Home_Base"
                            },{
                                   label: "Subsequent_Outstation:",
                                   name: "allowanceschemeitems.Subsequent_Outstation"
                            },{
                                   label: "Remarks:",
                                   name: "allowanceschemeitems.Remarks",
                                   type: "textarea"
                            }


                    ]
            } );

            // Activate an inline edit on click of a table cell
            $('#alltable').on( 'click', 'tbody td', function (e) {
                  editor.inline( this, {
                 onBlur: 'submit'
                } );
            } );

            $('#schemetable').on( 'click', 'tbody td', function (e) {
                  schemeitemeditor.inline( this, {
                 onBlur: 'submit'
                } );
            } );

            oTable=$('#alltable').dataTable( {
                    ajax: "{{ asset('/Include/accountallowancecontrol.php') }}",
                    columnDefs: [{ "visible": false, "targets": [0] },{"className": "dt-center", "targets": "_all"}],
                    responsive: false,
                    colReorder: false,
                    //dom: "Brt",
                    rowId:"users.Id",
                    //sScrollX: "100%",
                    //sScrollY: "100%",
                    dom: "Brtip",
                    iDisplayLength:15,
                    bAutoWidth: true,
                    aaSorting: [],
                    columns: [
                            { data: "users.Id"},
                            { data: "users.Name" },
                            { data: "users.Department" },
                            { data: "users.Position" },
                            { data: "allowanceschemes.Scheme_Name" , editField: "users.AllowanceSchemeId" }

                    ],
                    autoFill: {
                       editor:  editor,
                       alwaysAsk: true
                   },
                  //  keys: {
                  //      columns: ':not(:first-child)',
                  //      editor:  editor
                  //  },
                   select: true,
                    buttons: [

                    ],

        });

        schemetable=$('#schemetable').DataTable( {
                ajax: "{{ asset('/Include/allowanceitem.php') }}",
                columnDefs: [{ "visible": false, "targets": [0,1] },{"className": "dt-center", "targets": "_all"}],
                responsive: false,
                colReorder: false,
                //dom: "Brt",
                rowId:"allowanceschemeitems.Id",
                sScrollX: "100%",
                // order: [[ 1, "asc" ],[ 2, "asc" ],[ 3, "asc" ]],
                //sScrollY: "100%",
                dom: "Brftip",
                iDisplayLength:100,
                //bAutoWidth: true,
                columns: [
                        { data: "allowanceschemeitems.Id"},
                        { data: "allowanceschemeitems.AllowanceSchemeId"},
                        { data: "allowanceschemeitems.Day_Type" },
                        { data: "allowanceschemeitems.Start" },
                        { data: "allowanceschemeitems.End"},
                        { data: "allowanceschemeitems.Minimum_Hour"},
                        { data: "allowanceschemeitems.Currency"},
                        { data: "allowanceschemeitems.Home_Base"},
                        { data: "allowanceschemeitems.Outstation"},
                        { data: "allowanceschemeitems.Subsequent_Home_Base"},
                        { data: "allowanceschemeitems.Subsequent_Outstation"},
                        { data: "allowanceschemeitems.Remarks"}

                ],
                autoFill: {
                   editor:  schemeitemeditor
               },
              //  keys: {
              //      columns: ':not(:first-child)',
              //      editor:  schemeitemeditor
              //  },
               select: true,
                buttons: [
                  {
                    text: 'New',
                    action: function ( e, dt, node, config ) {
                        // clearing all select/input options
                        schemeitemeditor
                           .create( false )
                           .set( 'allowanceschemeitems.AllowanceSchemeId', currentschemeid )
                           .submit();
                    },
                  },
                  { extend: "remove", editor: schemeitemeditor },

                ],

    });

 //    $('#schemetable').on('click', 'a.editor_create', function (e) {
 //     e.preventDefault();
 //     var cellContents = schemetable.cell(($(this).closest('tr')), 1).data();
 //
 //     alert("a");
 // });

        $("thead input").keyup ( function () {

                /* Filter on the column (the index) of this element */
                if ($('#alltable').length > 0)
                {

                    var colnum=document.getElementById('alltable').rows[0].cells.length;

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
        Allowance Control
        <small>Admin</small>
      </h1>
      <ol class="breadcrumb">
        <li><a href="{{ url('/home') }}"><i class="fa fa-dashboard"></i> Home</a></li>
        <li><a href="#">Admin</a></li>
        <li class="active">Allowance Control</li>
      </ol>
    </section>

    <!-- Main content -->
    <section class="content">
      <div class="row">

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

          <div class="nav-tabs-custom">
            <ul class="nav nav-tabs">
              {{-- <li><a href="#company" data-toggle="tab">Company</a></li>
              <li><a href="#document" data-toggle="tab">Document</a></li>
              <li><a href="#evaluation" data-toggle="tab">Evaluation</a></li> --}}
              <li class="active"><a href="#account" data-toggle="tab">Account</a></li>
              <li><a href="#allowancescheme" data-toggle="tab">Allowance Scheme</a></li>
            </ul>
            <div class="tab-content">
              <div class="active tab-pane" id="account">

                <div class="box-body">
                    <table id="alltable" class="display" cellspacing="0" width="100%" padding="30px" style="font-size: 13px;">
                    <thead>
                        {{-- prepare header search textbox --}}

                        <tr class="search">
                          @foreach($accounts as $key=>$value)

                            @if ($key==0)
                              @foreach($value as $field=>$value)
                                  <th align='center'><input type='text' class='search_init' /></th>
                              @endforeach

                            @endif

                          @endforeach
                        </tr>

                        <tr>
                          @foreach($accounts as $key=>$value)

                            @if ($key==0)

                              @foreach($value as $field=>$value)
                                  <td/>{{ $field }}</td>
                              @endforeach

                            @endif

                          @endforeach
                        </tr>
                    </thead>
                    <tbody>

                      <?php $i = 0; ?>
                      @foreach($accounts as $account)

                        <tr id="row_{{ $i }}">
                            @foreach($account as $key=>$value)
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
              <!-- /.tab-pane -->

              <div class="tab-pane" id="allowancescheme">
                <div class="box-body">


                 <div class="box-body">
                   <div class="form-group">

                     <div class="form-group">
                      <select class="form-control select2" id="Scheme" name="Scheme" style="width: 30%;">
                        <option></option>

                        @foreach ($schemes as $scheme)
                           <option value="{{$scheme->Id}}">{{$scheme->Scheme_Name}}</option>
                        @endforeach
                      </select>
                    </div>

                   </div>

                   <button type="button" class="pull-right btn btn-primary btn-lg" data-toggle="modal" data-target="#CreateNewScheme">Create New Scheme</button>
                   <button type="button" class="btn btn-danger btn-lg" data-toggle="modal" onclick="OpenRemoveDialog()">Remove Scheme</button>

               </div>

               <div class="modal fade" id="CreateNewScheme" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
                 <div class="modal-dialog" role="document">
                   <div class="modal-content">
                     <div class="modal-header">
                       <div id="exist-alert" class="alert alert-warning alert-dismissible" style="display:none;">
                         <button type="button" class="close" onclick="$('#exist-alert').hide()" aria-hidden="true">&times;</button>
                         <h4><i class="icon fa fa-check"></i> Alert!</h4>
                             Scheme name already exist.
                       </div>
                       <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                       <h4 class="modal-title" id="myModalLabel">Create New Scheme</h4>
                     </div>
                     <div class="form-group" padding="10px">
                       <input type="text" class="form-control" name="Scheme_Name" id="Scheme_Name" placeholder="Enter Scheme Name ...">
                     </div>
                     <div class="modal-footer">
                       <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                       <button type="button" class="btn btn-primary" onclick="createnewscheme()">Save</button>
                     </div>
                   </div>
                 </div>
               </div>

               <div class="modal fade" id="RemoveScheme" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
                 <div class="modal-dialog" role="document">
                   <div class="modal-content">
                     <div class="modal-header">
                       <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                       <h4 class="modal-title" id="myModalLabel">Remove Scheme</h4>
                     </div>
                     <div class="form-group" padding="10px">
                         <label id="removeschememessage">Are you sure you wish to remove "" Scheme?</label>
                     </div>
                     <div class="modal-footer">
                       <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                       <button type="button" class="btn btn-primary" onclick="removescheme()">Remove</button>
                     </div>
                   </div>
                 </div>
               </div>

               <div class="col-md-12">

                 <div class="box box-success">

                   <div class="box-body">

                       <table id="schemetable" class="display" cellspacing="0" width="100%" padding="30px" style="font-size: 13px;">
                         <thead>
                             {{-- prepare header search textbox --}}

                             <tr>
                               @foreach($schemeitems as $key=>$value)

                                 @if ($key==0)

                                   @foreach($value as $field=>$value)
                                       <td/>{{ $field }}</td>
                                   @endforeach

                                 @endif

                               @endforeach
                             </tr>
                         </thead>
                           <tbody>

                             <?php $i = 0; ?>
                             @foreach($schemeitems as $item)

                               <tr id="row_{{ $i }}">
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
                 <!-- /.box -->
               </div>
              </div>
              </div>

            </div>
            <!-- /.tab-content -->
          </div>
          <!-- /.nav-tabs-custom -->
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

      $('#Scheme option')[1].selected = true;
      $( "#Scheme" ).change();

  });

  function removescheme()
  {

    $.ajaxSetup({
       headers: { 'X-CSRF-Token' : $('meta[name=_token]').attr('content') }
    });

    if ($("#Scheme").val())
    {
      var result=JSON.parse($("#Scheme").val());
      var selectedtext=$("#Scheme option:selected").text();
      var id=$("#Scheme option:selected").val();

      $.ajax({
                  url: "{{ url('/allowancecontrol/removescheme') }}",
                  method: "POST",
                  data: {Id:id},

                  success: function(response){

                    if (response==1)
                    {
                        var message="Scheme removed!";

                        $("#update-alert ul").html(message);
                        $("#update-alert").show();

                        jQuery("#Scheme option:contains('"+ selectedtext +"')").remove();

                        var message="Scheme removed!";

                        $('#RemoveScheme').modal('hide')
                        $("#update-alert ul").html(message);
                        $("#update-alert").show();

                        setTimeout(function() {
                          $("#update-alert").fadeOut();
                        }, 6000);
                    }
                    else {

                        var message="Failed to remove scheme!";

                        $('#RemoveScheme').modal('hide')
                        $("#warning-alert ul").html(message);
                        $("#warning-alert").show();

                        setTimeout(function() {
                          $("#warning-alert").fadeOut();
                        }, 6000);

                    }

                    // var message="Template saved!";
                    //
                    // var x = document.getElementById("Template");
                    // var option = document.createElement("option");
                    //
                    // document.getElementById("Template_Name").value = ''
                    // $("#exist-alert").hide();
                    // option.text = Template_Name;
                    // option.value = response;
                    // x.add(option);
                    //
                    // $('#SaveTemplate').modal('hide')
                    // $("#update-alert ul").html(message);
                    // $("#update-alert").show();

          }
      });
    }

  }

  function createnewscheme() {

      $.ajaxSetup({
         headers: { 'X-CSRF-Token' : $('meta[name=_token]').attr('content') }
      });

      Scheme_Name=$('[name="Scheme_Name"]').val();

      if ($('#Scheme option:contains('+ Scheme_Name +')').length){
          $("#exist-alert").show();
      }
      else {

        Created_By={{$me->UserId}};

        $.ajax({
                    url: "{{ url('/allowancecontrol/createnewscheme') }}",
                    method: "POST",
                    data: {Created_By:Created_By,
                      Scheme_Name:Scheme_Name},

                    success: function(response){

                      var message="Scheme saved!";

                      var x = document.getElementById("Scheme");
                      var option = document.createElement("option");

                      document.getElementById("Scheme_Name").value = ''
                      $("#exist-alert").hide();
                      option.text = Scheme_Name;
                      option.value = response;
                      x.add(option);

                      $('#CreateNewScheme').modal('hide')
                      $("#update-alert ul").html(message);
                      $("#update-alert").show();

                      setTimeout(function() {
                        $("#update-alert").fadeOut();
                      }, 6000);

                      // if (response==1)
                      // {
                      //
                      //     $("#update-alert").show();
                      //     setTimeout(function() {
                      //         $("#update-alert").hide('blind', {}, 500)
                      //     }, 5000);
                      //
                      // }
                      // else {
                      //
                      //   $("#warning-alert").show();
                      //   setTimeout(function() {
                      //       $("#warning-alert").hide('blind', {}, 500)
                      //   }, 5000);
                      //
                      // }

            }
        });

      }

  }

  function OpenRemoveDialog()
  {
      if ($("#Scheme option:selected").html()!="")
      {
        $schemename=$("#Scheme option:selected").html();
        $( "#removeschememessage" ).html("&nbsp;&nbsp;&nbsp; Are you sure you wish to remove <i>'"+ $schemename +"'</i> scheme?");
        $('#RemoveScheme').modal('show');

      }

  }

  $('#Scheme').on('change', function() {


    if (this.value)
    {
        currentschemeid=this.value;

        schemetable.ajax.url("{{ asset('/Include/allowanceitem.php?Id=') }}"+currentschemeid).load();

  }

  });

  </script>

@endsection
