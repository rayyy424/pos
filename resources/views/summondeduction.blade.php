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

      .btn-danger{
        margin-left:10px;
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
                      "url": "{{ asset('/Include/summon.php') }}",
                      "data": {
                          "DeductionId": {{$deductionid}}
                      }
                    },
                   table: "#deductiontable",
                   idSrc: "summons.Id",
                   fields: [
                           {
                                  label: "Vehicle_No:",
                                  name: "summons.Vehicle_No",
                                  type: 'select2',
                                  options: [
                                    { label :"", value: "" },
                                    @foreach($cars as $car)
                                    { label :"{{$car->Vehicle_No}}", value: "{{$car->Vehicle_No}}" },
                                    @endforeach
                                  ],
                         },{
                                label: "DeductionId:",
                                name: "summons.DeductionId",
                                type: "hidden",
                                def: "{{ $deductionid }}"
                       },{
                                label: "Driver:",
                                name: "summons.UserId",
                                type:  'select2',
                                // "opts": {
                                //   "source": [
                                //        @foreach($users as $user)
                                //            { label :"{{$user->Name}}", value: "{{$user->Id}}" },
                                //        @endforeach
                                //   ]
                                // },
                                options: [
                                  { label :"", value: "" },
                                  @foreach($users as $user)
                                      { label :"{{$user->Name}}", value: "{{$user->Id}}" },
                                  @endforeach
                                ],

                         },{

                                label: "Company:",
                                name: "summons.Company",
                                type:  "select2",
                                options: [
                                  { label :"", value: "" },
                                  @foreach($company as $option)
                                      { label :"{{$option->Option}}", value: "{{$option->Option}}" },
                                  @endforeach
                                ],
                        },{

                                 label: "Summon_No:",
                                 name: "summons.Summon_No"
                         },{
                                    label: "Date:",
                                    name: "summons.Date",
                                    type:   'datetime',
                                    def:    function () { return new Date(); },
                                    format: 'DD-MMM-YYYY'
                          },{
                                     label: "Settlement_Date:",
                                     name: "summons.Settlement_Date",
                                     type:   'datetime',
                                     def:    function () { return new Date(); },
                                     format: 'DD-MMM-YYYY'
                           },{
                                     label: "Time:",
                                     name: "summons.Time"
                          },{
                                    label: "Total_Deduction:",
                                    name: "summons.Total_Deduction"
                         },{
                                     label: "Offense:",
                                     name: "summons.Offense",
                                     type: "select2",
                                     opts: {
                                      data: [
                                        { text: "SPEEDING" }
                                      ],
                                      tags: true
                                     }
                          },{
                                     label: "Amount:",
                                     name: "summons.Amount"

                          },{
                                     label: "Place:",
                                     name: "summons.Place"
                          },{
                                     label: "Employer_Bare:",
                                     name: "summons.Employer_Bare"
                          },{
                                     label: "Company_Deduction:",
                                     name: "summons.Company_Deduction"
                          },{
                                      label: "Remarks:",
                                      name: "summons.Remarks",
                                      type:"textarea"
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
                        "url": "{{ asset('/Include/summon.php') }}",
                        "data": {
                            "DeductionId": {{$deductionid}}
                        }
                      },
                      dom: "Blrtip",
                      bAutoWidth: true,
                      sScrollY: "100%",
                      sScrollX: "100%",
                      aaSorting:[[1,'desc']],
                      columnDefs: [{ "visible": false, "targets": [1,2,13] },{"className": "dt-left", "targets": []},{"className": "dt-center", "targets": "_all"}],
                      bScrollCollapse: true,
                      columns: [
                        {data:null, "render":"", title:"No"},
                        {data:'summons.Id', title:"Id"},
                        {data:'summons.DeductionId', title:"DeductionId"},
                        {data:'summons.Vehicle_No', title:"Vehicle No"},
                        {data:'summons.Company', title:"Company"},
                        {data:'summons.Place', title:"Place"},
                        {data:'summons.Summon_No', title:"Summon No"},
                        {data:'summons.Date', title:"Offense Date"},
                        {data:'summons.Time', title:"Offense Time"},
                        {data:'summons.Offense', title:"Offense"},
                        {data:'summons.Amount' , title:"Final Amount (RM)"},
                        {data:'users.Name', editField:"summons.UserId", title:"Driver"},
                        {data:'users.Department', title:"Department"},
                        {data:'summons.Company_Deduction', tititlele:"Company Staff Deduct by Employee"},
                        {data:'summons.Total_Deduction', title:"Total to be deduct by Employee"},
                        {data:'summons.Employer_Bare', title:"Bare by Employer"},
                        {data:'summons.Settlement_Date', title:"Settlement Date"},
                        {data:'summons.Remarks', title:"Remarks"},

                      ],
                      autoFill: {
                         editor:  editor
                     },
                      select: true,
                      buttons: [
                              // {
                              //   text: 'New',
                              //   action: function ( e, dt, node, config ) {
                              //       // clearing all select/input options
                              //       editor
                              //          .create( false )
                              //          .set( 'summons.DeductionId', {{ $deductionid }})
                              //          .submit();
                              //   },
                              // },
                              { extend: "create", editor: editor },
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

               editor.on('initEdit', function ( e, json, data ) {

                  // the list of years
                  var offenses = ['SPEEDING'];

                  // the selected year from edit
                  var selectedOffense = data['summons']['Offense'];

                  // combine list and selected
                  var combinedOffenses = offenses.concat(selectedOffense);

                  // remove the duplicates
                  var uniqueOffenses = combinedOffenses.filter(function(item, pos) {
                      return combinedOffenses.indexOf(item) == pos;
                  });

                  editor.field('summons.Offense')
                    .update(uniqueOffenses)
                    .val(selectedOffense);
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
      Summon Deduction
      <small>Administration</small>
      </h1>

      <ol class="breadcrumb">
      <li><a href="{{ url('/home') }}"><i class="fa fa-dashboard"></i> Home</a></li>
      <li><a href="#">Administration</a></li>
      <li class="active">Summon Deduction</li>
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
                    @if($deductions->UserId==$me->UserId)
                    <a class="btn btn-danger btn-small" onclick="submitapproval('UserId')" style="float:right">Submit for Approval</a>
                    @endif
                    @if($deductions->LOG_HOD==$me->UserId)
                      <a class="btn btn-danger btn-small" onclick="submitapproval('HRA')" style="float:right">LOG Approval</a>
                    @endif
                    @if($deductions->CME_HOD==$me->UserId)
                      <a class="btn btn-danger btn-small" onclick="submitapproval('CME')" style="float:right">CME Approval</a>
                    @endif
                    @if($deductions->GENSET_HOD==$me->UserId)
                      <a class="btn btn-danger btn-small" onclick="submitapproval('GENSET')" style="float:right">GENSET Approval</a>
                    @endif

                    @if($deductions->MD==$me->UserId)
                      <a class="btn btn-danger btn-small" onclick="submitapproval('GENSET')" style="float:right">GENSET Approval</a>
                    @endif
                </div>
                <div class="col-md-12">
                  <br>

                  <table id="deductiontable" class="display" cellspacing="0" width="100%" padding="30px" style="font-size: 13px;">
                      <thead>
                          {{-- prepare header search textbox --}}

                          <tr>
                            @foreach($summondeductions as $key=>$value)

                              @if ($key==0)
                                <td>Action</td>
                                @foreach($value as $field=>$value)
                                    <td>{{ $field }}</td>
                                @endforeach

                              @endif

                            @endforeach
                          </tr>
                      </thead>
                      <tbody>

                        <?php $i = 0; ?>
                        @foreach($summondeductions as $summondeduction)

                          <tr id="row_{{ $i }}">
                              <td></td>
                              @foreach($summondeduction as $key=>$value)
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


function submitapproval(role){


  $.ajaxSetup({
     headers: { 'X-CSRF-Token' : $('meta[name=_token]').attr('content') }
  });
  $.ajax({
              url: "{{ url('/deductionapproval') }}",
              method: "POST",
              data: {
                DeductionId:{{$deductionid}},
                Role:role
              },
              success: function(response){
                if (response==1)
                {
                  var message="Status Updated!";
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
