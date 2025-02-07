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

         var deductiontable;

         $(document).ready(function() {

           editor = new $.fn.dataTable.Editor( {
                   ajax: {
                      "url": "{{ asset('/Include/touchdeductionitems.php') }}",
                      "data": {
                          "DeductionId": {{$deductionid}}
                      }
                    },
                   table: "#deductiontable",
                   idSrc: "deductionitems.Id",
                   fields: [
                           {
                                label: "Custodian:",
                                name: "deductionitems.UserId",
                                type:  'select2',
                                options: [
                                  { label :"", value: "0" },
                                  @foreach($users as $user)
                                      { label :"{{$user->Name}}", value: "{{$user->Id}}" },
                                  @endforeach
                                ],

                         },{
                                  label: "Date:",
                                  name: "deductionitems.Date",
                                  type:   'datetime',
                                  def:    function () { return ""; },
                                  format: 'DD-MMM-YYYY',
                                  attr: {
                                    autocomplete: "off"
                                  }
                          },{

                                label: "Time:",
                                name: "deductionitems.Time",
                        },{

                               label: "DeductionId:",
                               name: "deductionitems.DeductionId",
                               type: "hidden"
                        },{

                                 label: "Card_Serial:",
                                 name: "deductionitems.Card_Serial",
                                 type:  'select2',
                                 options: [
                                   { label :"", value: "" },
                                   @foreach($touchngocards as $tngcard)
                                       { label :"{{$tngcard->Card_No}}", value: "{{$tngcard->Card_No}}" },
                                   @endforeach
                                 ],
                         },{
                                     label: "Entry_location:",
                                     name: "deductionitems.Entry_location"

                          },{
                                     label: "Amount:",
                                     name: "deductionitems.Amount"
                          },{
                                     label: "Total_Deduction:",
                                     name: "deductionitems.Total_Deduction"

                          }

                   ]
           } );




             // $('#deductiontable').on( 'click', 'tbody td:not(:first-child)', function (e) {
             //       editor.inline( this, {
             //            onBlur: 'submit'
             //     } );
             // } );

               deductiontable = $('#deductiontable').dataTable( {

                   ajax: {
                      "url": "{{ asset('/Include/touchdeductionitems.php') }}",
                      "data": {
                          "DeductionId": {{$deductionid}}
                      }
                    },
                      dom: "Blrtip",
                      bAutoWidth: true,
                      sScrollY: "100%",
                      sScrollX: "100%",
                      aaSorting:[[1,'desc']],
                      columnDefs: [{ "visible": false, "targets": [1,2,8] },{"className": "dt-left", "targets": []},{"className": "dt-center", "targets": "_all"}],
                      bScrollCollapse: true,
                      columns: [
                        {data:null, "render":"", title:"No"},
                        {data:'deductionitems.Id', title:"Id"},
                        {data:'deductionitems.DeductionId', title:"DeductionId"},
                        {data:'deductionitems.Date', title:"Date"},
                        {data:'deductionitems.Time', title:"Time"},
                        {data:'deductionitems.Card_Serial', title:"Card Serial"},
                        {data:'users.Name', editField:"deductionitems.UserId", title:"Custodian"},
                        {data:'deductionitems.Entry_location', title:"Entry Location"},
                        {data:'deductionitems.Amount' , title:"Amount (RM)"},
                        {data:'deductionitems.Total_Deduction' , title:"Deduction (RM)"},
                        {data:'deductionitems.Penalty', title:"Penalty X5"},

                      ],
                      autoFill: {
                         editor:  editor
                     },
                      select: true,
                      buttons: [
                              {
                                text: 'New',
                                action: function ( e, dt, node, config ) {
                                    // clearing all select/input options
                                    editor
                                       .create('New Deduction', {
                                                   label: "Submit",
                                                   fn: function () { this.submit(); }
                                                }, false )
                                       .set( 'deductionitems.DeductionId', {{ $deductionid }})
                                       .open();
                                },
                              },
                              { extend: "edit", editor: editor },

                              { extend: "remove", editor: editor },
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




               deductiontable.api().on( 'order.dt search.dt', function () {
                   deductiontable.api().column(0, {search:'applied', order:'applied'}).nodes().each( function (cell, i) {
                       cell.innerHTML = i+1;
                   } );
               } ).draw();

               $(".deductiontable thead input").keyup ( function () {

                       /* Filter on the column (the index) of this element */
                       if ($('#deductiontable').length > 0)
                       {

                           var colnum=document.getElementById('deductiontable').rows[0].cells.length;

                           if (this.value=="[empty]")
                           {

                              deductiontable.fnFilter( '^$', this.name,true,false );
                           }
                           else if (this.value=="[nonempty]")
                           {

                              deductiontable.fnFilter( '^(?=\\s*\\S).*$', this.name,true,false );
                           }
                           else if (this.value.startsWith("!")==true && this.value.length>1)
                           {

                              deductiontable.fnFilter( '^(?'+ this.value +').*', this.name,true,false );
                           }
                           else if (this.value.startsWith("!")==false)
                           {

                               deductiontable.fnFilter( this.value, this.name,true,false );
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
      Touch N Go Deduction
      <small>Administration</small>
      </h1>

      <ol class="breadcrumb">
      <li><a href="{{ url('/home') }}"><i class="fa fa-dashboard"></i> Home</a></li>
      <li><a href="#">Administration</a></li>
      <li class="active">Touch N Go Deduction</li>
      </ol>
    </section>

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

          <div class="box">
            <div class="box-body">

                <div class="col-md-6">
                  <h4>{{ $deductions->Company }}</h4>
                </div>
                <div class="col-md-6">
                  <a class="btn btn-danger" onclick="submitapproval()" style="float:right">Submit for Approval</a>
                </div>



                <div class="col-md-12">
                  <br>

                  <table id="deductiontable" class="deductiontable" cellspacing="0" width="100%" padding="30px" style="font-size: 13px;">
                      <thead>

                        <tr class="search">

                          @foreach($touchdeductions as $key=>$value)

                            @if ($key==0)
                              <?php $i = 0; ?>

                              @foreach($value as $field=>$a)
                                  @if ($i==0|| $i==1)
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

                          <tr>

                            @foreach($touchdeductions as $key=>$value)

                              @if ($key==0)
                                     <td></td>
                                @foreach($value as $field=>$value)
                                    <td/>{{ $field }}</td>
                                @endforeach

                              @endif

                            @endforeach
                          </tr>
                      </thead>
                      <tbody>



                    </tbody>
                    <tfoot></tfoot>
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

  $('#Due_Date').datepicker({
    format: 'dd-M-yyyy',
    autoclose: true
  });

  $('#Invoice_Date').datepicker({
    format: 'dd-M-yyyy',
    autoclose: true
  });
});


function submitapproval(){

  $.ajaxSetup({
     headers: { 'X-CSRF-Token' : $('meta[name=_token]').attr('content') }
  });
  $.ajax({
              url: "{{ url('/deductionapproval') }}",
              method: "POST",
              data: {
                DeductionId:{{$deductionid}}
              },
              success: function(response){
                if (response==1)
                {
                  var message="Submited for approval!";
                  $('#Approval').modal('hide')
                  $("#update-alert ul").html(message);
                  $("#update-alert").show();

                  setTimeout(function() {
                    $("#update-alert").fadeOut();
                  }, 6000);
                }
                else {
                  $("#exist-alert").show();
                  $("#changepasswordmessage").html("Your current password is incorrect!");
                }
      }
  });
}

</script>



@endsection
