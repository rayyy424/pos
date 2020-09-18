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
      {{-- chart js --}}
      <script type="text/javascript" language="javascript" src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.4.0/Chart.bundle.min.js"></script>

      <script type="text/javascript" language="javascript" class="init">
            var entitlement;
            var editor;
            var grade;

            $(document).ready(function() {

              editor = new $.fn.dataTable.Editor( {
                      ajax: "{{ asset('/Include/leaveentitlement.php') }}",
                      table: "#leaveentitlement",
                      idSrc: "leaveentitlements.Id",
                      fields: [
                              {
                                     name: "leaveentitlements.Grade",
                                     type: "hidden"
                              },{

                                     label: "Year:",
                                     name: "leaveentitlements.Year",
                                     attr: {
                                       type: "number"
                                     }

                              },{

                                     label: "Days:",
                                     name: "leaveentitlements.Days",
                                     attr: {
                                       type: "number"
                                     }

                              },{
                                     label: "Leave Type:",
                                     name: "leaveentitlements.Leave_Type",
                                     type: "select",
                                     options: [
                                         { label :"", value: "" },
                                         @foreach($options as $option)
                                           @if ($option->Field=="Leave_Type")
                                             { label :"{{$option->Option}}", value: "{{$option->Option}}" },
                                           @endif
                                         @endforeach
                                     ],

                              }


                      ]
              } );

              // Activate an inline edit on click of a table cell

              $('#leaveentitlement').on( 'click', 'tbody td', function (e) {
                    editor.inline( this, {
                   onBlur: 'submit'
                  } );
              } );

              entitlement=$('#leaveentitlement').DataTable( {
                      ajax: "{{ asset('/Include/leaveentitlement.php') }}",
                      columnDefs: [{ "visible": false, "targets": [0,1] },{"className": "dt-center", "targets": "_all"}],
                      responsive: false,
                      colReorder: false,
                      //dom: "Brt",
                      rowId:"leaveentitlements.Id",
                      sScrollX: "100%",
                      // order: [[ 1, "asc" ],[ 2, "asc" ],[ 3, "asc" ]],
                      //sScrollY: "100%",
                      dom: "Brftip",
                      iDisplayLength:100,
                      //bAutoWidth: true,
                      columns: [
                              { data: "leaveentitlements.Id"},
                              { data: "leaveentitlements.Grade"},
                              { data: "leaveentitlements.Year"},
                              { data: "leaveentitlements.Days"},
                              { data: "leaveentitlements.Leave_Type" }

                      ],
                      autoFill: {
                         editor:  editor
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
                              editor
                                 .create( false )
                                 .set( 'leaveentitlements.Grade', grade )
                                 .submit();
                          },
                        },
                        { extend: "remove", editor: editor },


                      ],

          });



            });
      </script>

      @endsection

@section('content')

  <div class="content-wrapper">
      <!-- Content Header (Page header) -->
    <section class="content-header">
      <h1>
      Leave Entitlement
      <small>Human Resource</small>
      </h1>

      <ol class="breadcrumb">
        <li><a href="{{ url('/home') }}"><i class="fa fa-dashboard"></i> Home</a></li>
        <li><a href="#">Human Resource</a></li>
        <li><a href="#">Leave</a></li>
        <li class="active">Leave Entitlement</li>
      </ol>
    </section>

    <section class="content">
      <div class="row">

        <div class="col-md-12">
          <div class="box box-body">

              <div class="form-group">

                Grade :
               <select class="form-control select2" id="Grade" name="Grade" style="width: 30%;">
                 <option></option>

                 @foreach ($grade as $gradeoption)
                    <option value="{{$gradeoption->Option}}">{{$gradeoption->Option}}</option>
                 @endforeach


               </select>
             </div>


            <!-- <button type="button" class="pull-right btn btn-primary btn-lg" data-toggle="modal" data-target="#CreateNewScheme">Create New Scheme</button>
            <button type="button" class="btn btn-danger btn-lg" data-toggle="modal" onclick="OpenRemoveDialog()">Remove Scheme</button> -->

            <div class="row">

              <div class="col-md-12">

                  <table id="leaveentitlement" class="leaveentitlement" cellspacing="0" width="100%" padding="30px" style="font-size: 13px;">
                      <thead>
                          {{-- prepare header search textbox --}}

                          <tr>
                            @foreach($entitlement as $key=>$value)

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
                        @foreach($entitlement as $leave)

                          <tr id="row_{{ $i }}">
                              @foreach($leave as $key=>$value)
                                <td>
                                  {{ $value }}
                                </td>
                              @endforeach
                          </tr>
                          <?php $i++; ?>
                        @endforeach

                    </tbody>

                  </table>

            </div>

          </div>

        </div>

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

<script>

$(function () {

  //Initialize Select2 Elements
  $(".select2").select2();

  $('#Grade option')[1].selected = true;
  $( "#Grade" ).change();

});

$('#Grade').on('change', function() {


  if (this.value)
  {
      grade=this.value;

      entitlement.ajax.url("{{ asset('/Include/leaveentitlement.php?grade=') }}"+grade).load();

}

});

</script>



@endsection
