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

         var deductiontable;
         var editor2;
         var editor3;

         var alltable
         var paidtable;
         var deductioneditor;
         var accidenteditor;

         $(document).ready(function() {


           editor = new $.fn.dataTable.Editor( {
                   ajax: {
                      "url": "{{ asset('/Include/summon1.php') }}"
                    },
                   table: "#deductiontable",
                   idSrc: "summons.Id",
                   fields: [
                           {
                                  label: "Vehicle_No:",
                                  name: "summons.Vehicle_No"
                         },{
                                label: "DeductionId:",
                                name: "summons.DeductionId"
                       },{
                                label: "Driver:",
                                name: "summons.UserId",
                                type:  'select',
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
                                type:  "select",
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
                                     name: "summons.Offense"
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



           deductioneditor = new $.fn.dataTable.Editor( {
                   ajax: {
                      "url": "{{ asset('/Include/summondeduction.php') }}"
                    },
                   table: "#deductiontable",
                   idSrc: "deductions.Id",
                   fields: [
                     {
                             label: "Type",
                             name: "deductions.Type",
                             type: "hidden"
                     },{
                            label: "Name",
                            name: "deductions.Name",
                            type:"readonly"
                     },{
                            label: "UserId",
                            name: "deductions.UserId",
                            type: "hidden"
                     },{
                            label: "Admin_HOD",
                            name: "deductions.Admin_HOD",
                            type: "hidden",
                     },{
                            label: "Admin_Status",
                            name: "deductions.Admin_Status",
                            type: "select2",
                            options: [
                              {value: "Pending", label: "Pending"},
                              {value: "Approved", label: "Approved"}
                            ]
                     },{
                            label: "CME_HOD",
                            name: "deductions.CME_HOD",
                            type: "hidden",
                     },{
                            label: "CME_Status",
                            name: "deductions.CME_Status"
                     },{
                            label: "GENSET_HOD",
                            name: "deductions.GENSET_HOD",
                            type: "hidden",
                     },{
                            label: "LOG_Status",
                            name: "deductions.LOG_Status"
                     },{
                            label: "HOD",
                            name: "deductions.LOG_HOD",
                            type: "hidden",
                     },{

                            label: "MD",
                            name: "deductions.MD",
                            type: "hidden",
                     },{
                            label: "GENSET_Status",
                            name: "deductions.GENSET_Status"
                     },{

                            label: "Company:",
                            name: "deductions.Company",
                            type:  "select2",
                            options: [
                              { label :"", value: "" },
                              @foreach($company as $option)
                                  { label :"{{$option->Option}}", value: "{{$option->Option}}" },
                              @endforeach
                            ],
                    },{
                            label: "Remarks:",
                            name: "deductions.Remarks"
                     },{
                            label: "Status:",
                            name: "deductions.Status",
                            type:"readonly"
                     }

                   ]
           } );

           accidenteditor = new $.fn.dataTable.Editor( {
                   ajax: {
                      "url": "{{ asset('/Include/accidentdeduction.php') }}"
                    },
                   table: "#accidenttable",
                   idSrc: "deductions.Id",
                   fields: [
                     {
                             label: "Type",
                             name: "deductions.Type",
                             type: "hidden"
                     },{
                            label: "Name",
                            name: "deductions.Name",
                            type:"readonly"
                     },{
                            label: "UserId",
                            name: "deductions.UserId",
                            type: "hidden"
                     },{
                            label: "Admin_HOD",
                            name: "deductions.Admin_HOD",
                            type: "hidden"
                     },{
                            label: "Admin_Status",
                            name: "deductions.Admin_Status"
                     },{
                            label: "CME_HOD",
                            name: "deductions.CME_HOD",
                            type: "hidden"
                     },{
                            label: "CME_Status",
                            name: "deductions.CME_Status"
                     },{

                            label: "LOG_HOD",
                            name: "deductions.LOG_HOD",
                            type: "hidden"
                     },{
                            label: "LOG_Status",
                            name: "deductions.LOG_Status"
                     },{
                            label: "MD",
                            name: "deductions.MD",
                            type: "hidden"
                     },{
                            label: "GENSET_HOD",
                            name: "deductions.GENSET_HOD",
                            type: "hidden"
                     },{
                            label: "GENSET_Status",
                            name: "deductions.GENSET_Status"
                     },{
                            label: "Remarks:",
                            name: "deductions.Remarks"
                     },{
                            label: "Status:",
                            name: "deductions.Status",
                            type:"readonly"
                     }

                   ]
           } );

           deductioneditor.on( 'postSubmit', function ( e, json, data, action) {

             if(json["fieldErrors"])
             {

               var errormessage="Duplicate deduction for "+$('[name="Payment_Month"]').val();
               $("#error-alert ul").html(errormessage);
               $("#error-alert").modal('show');


             }

         } );

           accidenteditor.on( 'postSubmit', function ( e, json, data, action) {

             if(json["fieldErrors"])
             {

               var errormessage="Duplicate deduction for "+$('[name="Payment_Month1"]').val();
               $("#error-alert ul").html(errormessage);
               $("#error-alert").modal('show');


             }

          } );



             $('#deductiontable').on( 'click', 'tbody td:not(:first-child)', function (e) {
                   deductioneditor.inline( this, {
                        onBlur: 'submit'
                 } );
             } );



             alltable = $('#all').dataTable( {


                dom: "Blrtip",
                bAutoWidth: true,
                sScrollY: "100%",
                sScrollX: "100%",
                aaSorting:[[1,'desc']],
                columnDefs: [{ "visible": false, "targets": [1] },{"className": "dt-left", "targets": []},{"className": "dt-center", "targets": "_all"}],
                bScrollCollapse: true,
                columns: [
                  {data:null, "render":"", title:"No"},
                  {data:'summons.Id', title:"Id"},
                  {data:'summons.Vehicle_No', title:"Vehicle No"},
                  {data:'summons.Company', title:"Company"},
                  {data:'summons.Place', title:"Place"},
                  {data:'summons.Summon_No', title:"Summon No"},
                  {data:'summons.Date', title:"Offense Date"},
                  {data:'summons.Time', title:"Offense Time"},
                  {data:'summons.Offense', title:"Offense"},
                  {data:'summons.Amount' , title:"Final Amount (RM)"},
                  {data:'users.Name', editField:"summons.UserId", title:"Driver"},
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

                        // { extend: "remove", editor: editor },
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

                alltable.api().on( 'order.dt search.dt', function () {
                    alltable.api().column(0, {search:'applied', order:'applied'}).nodes().each( function (cell, i) {
                        cell.innerHTML = i+1;
                    } );
                } ).draw();

                  deductiontable=$('#deductiontable').dataTable( {
                            ajax: {
                               "url": "{{ asset('/Include/summondeduction.php') }}"
                             },
                              columnDefs: [{ "visible": false, "targets": [1,3,5,7] },{"className": "dt-center", "targets": "_all"}],
                              responsive: false,
                              colReorder: false,
                              dom: "Brt",
                              sScrollY: "100%",
                              order: [[ 1, "desc" ]],
                              sScrollX: "100%",
                              bPaginate: false,
                              bAutoWidth: false,
                              aaSorting:false,
                              rowId:"deductions.Id",
                              columns: [
                                 {
                                     sortable: false,
                                     "render": function ( data, type, full, meta ) {
                                         return '<a href="summondeductions/'+full.deductions.Id+'" target="_blank">View</a>';
                                     }
                                 },
                                { data: "deductions.Id"},
                                { data: "deductions.Name", title:"Date"},
                                { data: "deductions.Remarks",title:"Remarks"},
                                { data: "submitter.Name", title: "Prepared By"},
                                { data: "deductions.Company", title: "Company"},
                                { data: "deductions.Status", title:"Status"},
                                { data: "deductions.created_at" ,title:"created_at"},
                                { data: "approver_LOG.Name" ,title:"Approve by HOD of LOG"},
                                { data: "deductions.LOG_Status" ,title:"Status"},
                                { data: "approver_CME.Name" ,title:"Approve by HOD of CME"},
                                { data: "deductions.CME_Status" ,title:"Status"},
                                { data: "approver_GENSET.Name" ,title:"Approve by HOD of GENSET"},
                                { data: "deductions.GENSET_Status" ,title:"Status"},
                                { data: "MD.Name" ,title:"Approve by MD"},
                                { data: "deductions.MD_Status" ,title:"Status"},

                              ],
                              autoFill: {
                                 editor:  deductioneditor
                             },
                             // keys: {
                             //     columns: ':not(:first-child)',
                             //     editor:  claimseditor
                             // },
                             select: {
                                 style:    'multi',
                                 selector: 'tr'
                             },
                              buttons: [
                                      {
                                        text: 'Deduction For : <select id="Payment_Month" name="Payment_Month" > @foreach ($paymentmonth as $month) <option value="{{$month->Payment_Month}}" <?php if($month->Payment_Month == $current) echo ' selected="selected" '; ?>>{{$month->Payment_Month}}</option>@endforeach</select>'

                                      },
                                      {
                                        text: 'New Deduction',
                                        action: function ( e, dt, node, config ) {
                                            // clearing all select/input options

                                            var current = moment("{{$current}}")
                                            var select = moment($('[name="Payment_Month"]').val());
                                            var naming="";


                                            if(select<current)
                                            {
                                              naming=" (Back-Date)";
                                            }

                                            deductioneditor
                                               .create( 'New Deduction', {
                                                   label: "Submit",
                                                   fn: function () { this.submit(); }
                                                },false )
                                               .set( 'deductions.Name', $('[name="Payment_Month"]').val()+" Summon")
                                               .set( 'deductions.UserId', {{ $me->UserId }} )
                                               .set( 'deductions.Status', "Pending Submission" )
                                               .set( 'deductions.LOG_HOD', {{$HOD_LOG}})
                                               .set( 'deductions.GENSET_HOD', {{$HOD_GST}})
                                               .set( 'deductions.CME_HOD', {{$HOD_CME}})
                                               .set( 'deductions.MD', {{$MD}})
                                               .set( 'deductions.Admin_Status', "Pending")
                                               .set( 'deductions.GENSET_Status', "Pending")
                                               .set( 'deductions.CME_Status', "Pending")
                                               .set( 'deductions.LOG_Status', "Pending")
                                               .set( 'deductions.Type', "summon" )
                                               .open();
                                        },
                                      },
                                      { extend: "edit", editor: deductioneditor },

                                      { extend: "remove", editor: deductioneditor }
                              ],

                  });


                  accidenttable=$('#accidenttable').dataTable( {
                            ajax: {
                               "url": "{{ asset('/Include/accidentdeduction.php') }}"
                             },
                              columnDefs: [{ "visible": false, "targets": [1,3,6] },{"className": "dt-center", "targets": "_all"}],
                              responsive: false,
                              colReorder: false,
                              dom: "Brt",
                              sScrollY: "100%",
                              sScrollX: "100%",
                              bPaginate: false,
                              bAutoWidth: false,
                              aaSorting:false,
                              rowId:"deductions.Id",
                              columns: [
                                 {
                                     sortable: false,
                                     "render": function ( data, type, full, meta ) {
                                         return '<a href="accidentdeduction/'+full.deductions.Id+'" target="_blank">View</a>';
                                     }
                                 },
                                { data: "deductions.Id"},
                                { data: "deductions.Name", title:"Date"},
                                { data: "deductions.Remarks",title:"Remarks"},
                                { data: "submitter.Name", title: "Prepared By"},
                                { data: "deductions.Status", title:"Status"},
                                { data: "deductions.created_at" ,title:"created_at"},
                                { data: "approver_HRA.Name" ,title:"Approve by HOD of LOG"},
                                { data: "deductions.Admin_Status" ,title:"Status"},
                                { data: "approver_CME.Name" ,title:"Approve by HOD of CME"},
                                { data: "deductions.CME_Status" ,title:"Status"},
                                { data: "approver_GENSET.Name" ,title:"Approve by HOD of GENSET"},
                                { data: "deductions.GENSET_Status" ,title:"Status"},
                                { data: "MD.Name" ,title:"Approve by MD"},
                                { data: "deductions.MD_Status" ,title:"Status"},

                              ],
                              autoFill: {
                                 editor:  accidenteditor
                             },
                             // keys: {
                             //     columns: ':not(:first-child)',
                             //     editor:  claimseditor
                             // },
                             select: {
                                 style:    'multi',
                                 selector: 'tr'
                             },
                              buttons: [
                                      {
                                        text: 'Deduction For : <select id="Payment_Month1" name="Payment_Month1" > @foreach ($paymentmonth as $month) <option value="{{$month->Payment_Month}}" <?php if($month->Payment_Month == $current) echo ' selected="selected" '; ?>>{{$month->Payment_Month}}</option>@endforeach</select>'

                                      },
                                      {
                                        text: 'New Deduction',
                                        action: function ( e, dt, node, config ) {
                                            // clearing all select/input options

                                            var current = moment("{{$current}}")
                                            var select = moment($('[name="Payment_Month1"]').val());
                                            var naming="";

                                            if(select<current)
                                            {
                                              naming=" (Back-Date)";
                                            }

                                            accidenteditor
                                               .create( false )
                                               .set( 'deductions.Name', $('[name="Payment_Month1"]').val()+"_Accident")
                                               .set( 'deductions.UserId', {{ $me->UserId }} )
                                               .set( 'deductions.Status', "Pending Submission" )
                                               .set( 'deductions.Admin_HOD', '{{ $HOD_LOG }}')
                                               .set( 'deductions.GENSET_HOD', {{$HOD_GST}})
                                               .set( 'deductions.CME_HOD', {{$HOD_CME}})
                                               .set( 'deductions.MD', {{$MD}})
                                               .set( 'deductions.Admin_Status', "Pending")
                                               .set( 'deductions.GENSET_Status', "Pending")
                                               .set( 'deductions.CME_Status', "Pending")
                                               .set( 'deductions.Type', "accident" )
                                               .submit();
                                        },
                                      },
                                      { extend: "edit", editor: accidenteditor },

                                      { extend: "remove", editor: accidenteditor }
                              ],

                  });


               $(".all thead input").keyup ( function () {

                       /* Filter on the column (the index) of this element */
                       if ($('#all').length > 0)
                       {

                           var colnum=document.getElementById('all').rows[0].cells.length;

                           if (this.value=="[empty]")
                           {

                              alltable.fnFilter( '^$', this.name,true,false );
                           }
                           else if (this.value=="[nonempty]")
                           {

                              alltable.fnFilter( '^(?=\\s*\\S).*$', this.name,true,false );
                           }
                           else if (this.value.startsWith("!")==true && this.value.length>1)
                           {

                              alltable.fnFilter( '^(?'+ this.value +').*', this.name,true,false );
                           }
                           else if (this.value.startsWith("!")==false)
                           {

                               alltable.fnFilter( this.value, this.name,true,false );
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
      Summons
      <small>Administration</small>
      </h1>

      <ol class="breadcrumb">
      <li><a href="{{ url('/home') }}"><i class="fa fa-dashboard"></i> Home</a></li>
      <li><a href="#">Administration</a></li>
      <li class="active">Summons</li>
      </ol>
    </section>

    <section class="content">

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


      <div class="modal fade" id="NewSummon" role="dialog" aria-labelledby="myModalLabel">
        <div class="modal-dialog">
          <div class="modal-content">
            <div class="modal-header">
              <div id="assign-alert" class="alert alert-warning alert-dismissible" style="display:none;">
                <h4><i class="icon fa fa-check"></i> Alert!</h4>
                <div id="assignmessage"></div>
              </div>
              <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
              <h4 class="modal-title" id="myModalLabel">New Bill</h4>

            </div>

            <div class="modal-body">

              <input type="hidden" id="Vehicle_No" name="Vehicle_No" value=0>

              <div class="form-group">
                <label>Holder Name </label>

                  <select class="form-control select2" id="UserId" name="UserId" style="width: 100%;">
                     <option value="0"></option>
                    @foreach ($users as $user)
                       <option value="{{$user->Id}}">{{$user->Name}}</option>
                    @endforeach
                  </select>

              </div>

              <div class="form-group">
                <label>Summon No : </label>
                  <input type="text" class="form-control pull-right" id="Summon_No" name="Summon_No">

              </div>

              <div class="form-group">
                <label>Bill Date : </label>

                <div class="input-group date">
                  <div class="input-group-addon">
                    <i class="fa fa-calendar"></i>
                  </div>
                  <input type="text" class="form-control pull-right" id="Date" name="Date" value="{{ date("d-M-Y") }}">
                </div>
                <!-- /.input group -->
              </div>

              <div class="form-group">
                <label>Time : </label>
                  <input type="text" class="form-control pull-right" id="Time" name="Time">

              </div>

              <div class="form-group">
                <label>Offense : </label>
                  <input type="text" class="form-control pull-right" id="Offense" name="Offense">

              </div>

              <div class="form-group">
                <label>Amount : </label>
                  <input type="text" class="form-control pull-right" id="Amount" name="Amount">

              </div>

              <div class="form-group">
                <label>Payment Date : </label>

                <div class="input-group date">
                  <div class="input-group-addon">
                    <i class="fa fa-calendar"></i>
                  </div>
                  <input type="text" class="form-control pull-right" id="Payment_Date" name="Payment_Date" value="{{ date("d-M-Y") }}">
                </div>
                <!-- /.input group -->
              </div>

              <div class="form-group">
                <label>Remarks : </label>
                  <input type="text" class="form-control pull-right" id="Remarks" name="Remarks">

              </div>



            </div>
            <div class="modal-footer">
              <!-- <center><img src="{{ URL::to('/') ."/img/ajax-loader.gif" }}" width="50px" height="50px" alt="Loading" name='ajaxloader' id="ajaxloader"></center> -->
              <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
              <button type="button" class="btn btn-primary" onclick="sendsummon()">Submit</button>
            </div>
          </div>
        </div>
      </div>

      <div class="row">
        <div class="col-md-12">
          <!-- <div class="box">
            <div class="box-body"> -->

              <div class="col-md-12">
                <div class="nav-tabs-custom">
                   <ul class="nav nav-tabs">
                     <li class="active"><a href="#masterlisttab" data-toggle="tab" id="">Masterlist</a></li>
                     <li><a href="#deductiontab" data-toggle="tab" id="paidtab1">Summon Deduction</a></li>
                     <li><a href="#accidenttab" data-toggle="tab" id="unpaidtab1">Accident Deduction</a></li>
                   </ul>

                   <br><br>

                   <div class="tab-content">

                      <div class="active tab-pane" id="masterlisttab">

                        <table id="all" class="display" cellspacing="0" width="100%" padding="30px" style="font-size: 13px;">
                            <thead>
                                {{-- prepare header search textbox --}}

                                <tr>
                                  @foreach($all as $key=>$value)

                                    @if ($key==0)
                                    <td></td>
                                      @foreach($value as $field=>$value)
                                          <td>{{ $field }}</td>
                                      @endforeach

                                    @endif

                                  @endforeach
                                </tr>
                            </thead>
                            <tbody>

                              <?php $i = 0; ?>
                              @foreach($all as $allsummon)

                                <tr id="row_{{ $i }}">
                                  <td></td>
                                    @foreach($allsummon as $key=>$value)
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

                      <div class="tab-pane" id="deductiontab">

                        <table id="deductiontable" class="display" cellspacing="0" width="100%" padding="30px" style="font-size: 13px;">
                            <thead>
                                {{-- prepare header search textbox --}}

                                <tr>
                                  @foreach($deductions as $key=>$value)

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
                              @foreach($deductions as $deduction)

                                <tr id="row_{{ $i }}">
                                    <td></td>
                                    @foreach($deduction as $key=>$value)
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

                      <div class="tab-pane" id="accidenttab">

                        <table id="accidenttable" class="display" cellspacing="0" width="100%" padding="30px" style="font-size: 13px;">
                            <thead>
                                {{-- prepare header search textbox --}}

                                <tr>
                                  @foreach($accidents as $key=>$value)

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
                              @foreach($accidents as $accident)

                                <tr id="row_{{ $i }}">
                                    <td></td>
                                    @foreach($accident as $key=>$value)
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

            <!-- </div>
          </div> -->
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



function newsummon(vehicleno)
{
  $('#Vehicle_No').val(vehicleno);
  $('#NewSummon').modal('show');
  // alert("3r")

}

function sendsummon()
{
  $.ajaxSetup({
     headers: { 'X-CSRF-Token' : $('meta[name=_token]').attr('content') }
  });



  vehicleno=$('[name="Vehicle_No"]').val();
  summonno=$('[name="Summon_No"]').val();
  date=$('[name="Date"]').val();
  time=$('[name="Time"]').val();
  userid=$('[name="UserId"]').val();
  paymentdate=$('[name="Payment_Date"]').val();
  amount=$('[name="Offense"]').val();
  amount=$('[name="Amount"]').val();
  remarks=$('[name="Remarks"]').val();
  offense=$('[name="Offense"]').val();



  // $("#ajaxloader2").show();

  $.ajax({
              url: "{{ url('/summon/new') }}",
              method: "POST",
              data: {
                Vehicle_No:vehicleno,
                UserId:userid,
                Summon_No:summonno,
                Date:date,
                Offense:offense,
                Time:time,
                Payment_Date:paymentdate,
                Amount:amount,
                Remarks:remarks
              },
              success: function(response){
                if (response==0)
                {
                  var message ="Failed to submit new bill!";
                  $("#warning-alert ul").html(message);
                  $("#warning-alert").show();

                  setTimeout(function() {
                    $("#warning-alert").fadeOut();
                  }, 6000);

                  $('#NewSummon').modal('hide')

                  $("#ajaxloader2").hide();
                }
                else {
                  var message ="New bill sent!";
                  $("#update-alert ul").html(message);
                  $("#update-alert").show();

                  setTimeout(function() {
                    $("#update-alert").fadeOut();
                  }, 6000);
                  //$("#Template").val(response).change();
                  $("#exist-alert").hide();
                  $('#NewSummon').modal('hide')

                  oTable.api().ajax.reload();

                  $("#ajaxloader2").hide();
                }
      }
  });
}

</script>



@endsection
