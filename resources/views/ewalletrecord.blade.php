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

           editor = new $.fn.dataTable.Editor( {
                   ajax: {
                      "url": "{{ asset('/Include/ewalletrecord.php') }}",
                      "data": {
                        "UserId": "{{ $userid }}",
                        "Start": "{{ $start }}",
                        "End": "{{ $end }}"
                      }
                    },
                   table: "#ewallet",
                   idSrc: "ewallet.Id",
                   formOptions: {
                        bubble: {
                            submit: 'allIfChanged'
                        }
                    },
                   fields: [
                     {
                                label: "UserId:",
                                name: "ewallet.UserId",
                                type: "hidden",
                                def: "{{ $userid }}"
                    },{
                                label: "Date:",
                                name: "ewallet.Date",
                                type:   'datetime',
                                format: 'DD-MMM-YYYY'
                    },{
                            label: "Project:",
                            name: "ewallet.ProjectId",
                            type:  'select2',
                            options: [
                                { label :"", value: "" },
                                @foreach($projects as $project)
                                    { label :"{{$project->Project_Name}}", value: "{{$project->Id}}" },
                                @endforeach
                            ],
                    },{
                              label: "Site:",
                              name: "ewallet.TrackerId",
                              type:  'select2',
                              options: [
                                  { label :"", value: "" },
                                  @foreach($sitelist as $site)
                                  @if($site->site_name != "")
                                      { label :<?php echo json_encode($site->Project_Code." - ".$site->site_name) ?>, value: "{{$site->Id}}" },
                                  @endif
                                  @endforeach
                              ],
                      },{
                               label: "Type:",
                               name: "ewallet.Type",
                               type:  'select2',
                               options: [
                                   { label :"", value: "" },
                                   @foreach($options as $opt)
                                    @if($opt->Field=="Type")
                                       { label :"{{$opt->Option}}", value: "{{$opt->Option}}" },
                                    @endif
                                   @endforeach

                               ],
                   },{
                              label: "Expenses Type:",
                              name: "ewallet.Expenses_Type",
                              type:  'select2',
                                options: [
                                    { label :"", value: "" },
                                    @foreach($options as $opt)
                                     @if($opt->Field=="Expenses_Type")
                                        { label :"{{$opt->Option}}", value: "{{$opt->Option}}" },
                                     @endif
                                    @endforeach

                                ],
                  },{
                                label: "Amount:",
                                name: "ewallet.Amount",
                                type:   'text',
                                opts: {
                                     min: '1',
                                     steps: '0.1'
                                }
                    },{
                                label: "Remarks:",
                                name: "ewallet.Remarks",
                                type:   'textarea'
                    },{
                                   label: "created_by:",
                                   name: "ewallet.created_by",
                                   type:'hidden',
                                   def:"{{ $me->UserId }}"
                   },{
                                  label: "created_at:",
                                  name: "ewallet.created_at",
                                  type:'hidden',
                                  def:"{{ date('Y-m-d H:i:s') }}"
                      }

                   ]
           } );



           // $('#ewallet').on( 'click', 'tbody td:not(:first-child)', function (e) {
           //       editor.inline( this, {
           //         onBlur: 'submit',
           //         submit: 'allIfChanged'
           //     } );
           // } );

               ewallettable = $('#ewallet').dataTable( {
                 ajax: {
                    "url": "{{ asset('/Include/ewalletrecord.php') }}",
                    "data": {
                      "UserId": "{{ $userid }}",
                      "Start": "{{ $start }}",
                      "End": "{{ $end }}"
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
                     {data:'ewallet.Type', title:"Type"},
                     {data:'ewallet.Expenses_Type', title:"Expenses_Type"},
                     {data:'ewallet.Amount', title:"Amount"},
                     {data:'ewallet.Remarks', title:"Remarks"},
                     {data:'ewallet.created_at', title:'Created_At'},
                     {data:'ewallet.updated_at', title:'Updated_At'},
                     {data:'creator.Name', editField: 'ewallet.created_by', title:'Created_By' },
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

                           { extend: "create", text: 'New Record',  editor: editor },
                           @if($me->ewallet_update)
                           { extend: "edit",  editor: editor },
                           { extend: "remove", editor: editor },
                           @endif
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

               editor.on( 'postCreate', function ( e, json, data ) {

                 var totalsaving=0.0;
                  var totalwithdraw=0.0;
                  var totalbalance=0.0;

                  ewallettable.api().rows().every( function () {
                     var d = this.data();
                     var type= d.ewallet.Type;

                     if(type.includes("Top-up")==true)
                     {
                       totalsaving=totalsaving+parseFloat(d.ewallet.Amount);
                     }
                     else if(type.includes("Expenses")==true)
                     {
                       totalwithdraw=totalwithdraw+parseFloat(d.ewallet.Amount);
                     }

                   } );

                   totalbalance=totalsaving-totalwithdraw;

                  $("#totalsaving").html("RM" + totalsaving.toFixed(2));
                  $("#totalwithdraw").html("RM" + totalwithdraw.toFixed(2));
                  $("#totalbalance").html("RM" + totalbalance.toFixed(2));
                  $("#totalbalance").val(totalbalance) ;

               } );

               editor.on( 'postRemove', function ( e, json, data ) {

                 var totalsaving=0.0;
                  var totalwithdraw=0.0;
                  var totalbalance=0.0;

                  ewallettable.api().rows().every( function () {
                     var d = this.data();
                     var type= d.ewallet.Type;

                     if(type.includes("Top-up")==true)
                     {
                       totalsaving=totalsaving+parseFloat(d.ewallet.Amount);
                     }
                     else if(type.includes("Expenses")==true)
                     {
                       totalwithdraw=totalwithdraw+parseFloat(d.ewallet.Amount);
                     }

                   } );

                   totalbalance=totalsaving-totalwithdraw;

                  $("#totalsaving").html("RM" + totalsaving.toFixed(2));
                  $("#totalwithdraw").html("RM" + totalwithdraw.toFixed(2));
                  $("#totalbalance").html("RM" + totalbalance.toFixed(2));
                  $("#totalbalance").val(totalbalance);

               } );

               editor.on( 'postEdit', function ( e, json, data ) {

                 var totalsaving=0.0;
                  var totalwithdraw=0.0;
                  var totalbalance=0.0;

                  ewallettable.api().rows().every( function () {
                     var d = this.data();
                     var type= d.ewallet.Type;

                     if(type.includes("Top-up")==true)
                     {
                       totalsaving=totalsaving+parseFloat(d.ewallet.Amount);
                     }
                     else if(type.includes("Expenses")==true)
                     {
                       totalwithdraw=totalwithdraw+parseFloat(d.ewallet.Amount);
                     }

                   } );

                   totalbalance=totalsaving-totalwithdraw;

                  $("#totalsaving").html("RM" + totalsaving.toFixed(2));
                  $("#totalwithdraw").html("RM" + totalwithdraw.toFixed(2));
                  $("#totalbalance").html("RM" + totalbalance.toFixed(2));
                  $("#totalbalance").val(totalbalance);

               } );

               ewallettable.api().on( 'order.dt search.dt', function () {
                   ewallettable.api().column(1, {search:'applied', order:'applied'}).nodes().each( function (cell, i) {
                       cell.innerHTML = i+1;
                   } );
               } ).draw();

               editor.on( 'preSubmit', function ( e, o, action ) {
            //         if ( action !== 'remove' ) {
            //               var balance = $('#totalbalance').val();
            //               var amount = this.field('ewallet.Amount');
            //               var type = this.field('ewallet.Type');
            //               console.log(balance,amount.val(),type.val());
            //               if( type.val() == "Expenses")
            //               {
            //                 if( amount.val() > balance)
            //                 {
            //                   amount.error('Not enough balance');
            //                 }
            //               }
            //
            //               if ( this.inError() ) {
            //               return false;
            // }
            //         }
                });


               $(".ewallet thead input").keyup ( function () {

                       /* Filter on the column (the index) of this element */
                       if ($('#ewallet').length > 0)
                       {

                           var colnum=document.getElementById('ewallet').rows[0].cells.length;

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

             } );

     </script>

      @endsection

@section('content')

  <div class="content-wrapper">
      <!-- Content Header (Page header) -->
    <section class="content-header">
      <h1>
      {{$ewallet->Name}}
      <small>eWallet</small>
      </h1>

      <ol class="breadcrumb">
      <li><a href="{{ url('/home') }}"><i class="fa fa-dashboard"></i> Home</a></li>
      <li><a href="#">eWallet</a></li>
      <li class="active">{{$ewallet->Name}}</li>
      </ol>
    </section>

    <section class="content">

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
        <div class="col-md-4">
         <div class="input-group">
           <div class="input-group-addon">
             <i class="fa fa-clock-o"></i>
           </div>
           <input type="text" class="form-control" id="range" name="range">

         </div>
       </div>

       <div class="col-md-2">
           <div class="input-group">
             <button type="button" class="btn btn-success btn" data-toggle="modal" onclick="refresh();">Refresh</button>
           </div>
       </div>
      </div>

      <br>


      <div class="row">

          <div class="col-md-4">

            <div class="box box-primary">

              <div class="box-body box-profile">

                <ul class="list-group list-group-unbordered">
                  <li class="list-group-item">
                    <b>Name</b> : <p class="pull-right"><i>{{ $ewallet->Name }}</i>
                  </li>

                  <li class="list-group-item">
                    <b>Department</b> : <p class="pull-right"><i>{{ $ewallet->Department }}</i>
                  </li>

                  <li class="list-group-item">
                    <b>Position</b> : <p class="pull-right"><i>{{ $ewallet->Position }}</i>
                  </li>

                  <li class="list-group-item">
                    <b>Total Top-up</b> : <p class="pull-right"><i><span id='totalsaving'>RM{{$ewallet->{'Total_Top-Up'} }}</span></i></p>
                  </li>

                  <li class="list-group-item">
                    <b>Total Expenses</b> : <p class="pull-right"><i><span id='totalwithdraw'>RM{{$ewallet->Total_Expenses}}</span></i></p>
                  </li>

                  <li class="list-group-item">
                    <b>Total Balance</b> : <p class="pull-right"><i><span id='totalbalance' value="">RM{{$ewallet->Balance}}</span></i></p>
                  </li>

                </ul>

              </div>

            </div>
          </div>

          <div class="col-md-4">
             <div class="box box-primary">

              <div class="box-body box-profile">
             <ul class="list-group list-group-unbordered">
                  @foreach ($expenses as $s)
                  <li class="list-group-item">
                    <b>{{$s->Expenses_Type}}</b> : <p class="pull-right"><a href="{{ url('/ewalletdetails/')}}/{{$s->Expenses_Type}}/{{$start}}/{{$end}}/{{$userid}}" target="_blank"><i>RM {{ $s->total }}</i></a>
                  </li>
                  @endforeach
                </ul>
          </div>
        </div>
      </div>
      </div>
      <div class="row">

        <div class="col-md-12">
          <!-- <div class="box">
            <div class="box-body"> -->


                  <table id="ewallet" class="ewallet" cellspacing="0" width="100%" padding="30px" style="font-size: 13px;">
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

$(function () {

  $('#range').daterangepicker({locale: {
    format: 'DD-MMM-YYYY'
  },startDate: '{{$start}}',
  endDate: '{{$end}}'});

});

  var files = {!! json_encode($filesByGroup) !!};
function getRowFiles(id) {
  return files[id];
}
  function OpenUploadWindow(id)
  {
    $('input[name="ewalletId"]').val(id);
    // $('input[name="projectname"]').val(projectname);
    // $('input[name="projectcode"]').val(projectcode);
    $('#UploadModal').modal('show');
  }

  function uploadfile() {
      $.ajaxSetup({
         headers: { 'X-CSRF-Token' : $('meta[name=_token]').attr('content') }
      });
      $.ajax({
                  url: "{{ url('/ewalletrecord/uploadfile') }}",
                  method: "POST",
                  contentType: false,
                  processData: false,
                  data:new FormData($("#upload_form")[0]),
                  success: function(response){
                      var newFiles = response.split(',');
                      newFiles.forEach(function(file) {
                        var arr = file.split('|');
                        var fileId = arr[0];
                        var filePath = arr[1];
                        var fileName = arr[2];
                        if (files[$('input[name="ewalletId"]').val()] === undefined) {
                          files[$('input[name="ewalletId"]').val()] = [];
                        }
                        files[$('input[name="ewalletId"]').val()].push({
                          Id: fileId,
                          Web_Path: filePath,
                          TargetId: $('input[name="ewalletId"]').val()
                        });
                      });
                      var message ="File uploaded!";
                      $("#update-alert ul").html(message);
                      $("#update-alert").show();
                      setTimeout(function() {
                        $("#update-alert").fadeOut();
                      }, 6000);
                      $('input[name="ewalletId"]').val(0);
                      // $('input[name="projectcode"]').val(0);
                      // $('input[name="projectname"]').val(0);
                      $('#uploadfile').empty();
                      $("#exist-alert").hide();
                      $('#UploadModal').modal('hide')
                      $("#uploadfile").val("");
                      ewallettable.api().ajax.reload();
                      window.location.reload();
                    },
                    error: function (response) {
                  var message ="Failed to upload file!";
                      $("#warning-alert ul").html(message);
                      $("#warning-alert").show();
                      setTimeout(function() {
                        $("#warning-alert").fadeOut();
                      }, 6000);

                      $('#UploadModal').modal('hide')
                    }

      });
  }
    function removefile(id, parentid) {
        modalConfirm(function () {
            doremovefile(id, parentid);
        });
    }
      var modalConfirm = function(confirm){
      $("#deleteModal").modal('show');
      $("#btn-delete").on("click", function(){
        confirm();
        $("#deleteModal").modal('hide');
      });
    };
  function doremovefile(id, parentid) {
    $.ajaxSetup({
       headers: { 'X-CSRF-Token' : $('meta[name=_token]').attr('content') }
    });
    $.ajax({
                url: "{{ url('/ewalletrecord/deletefile') }}",
                method: "POST",
                data: {Id:id},
                success: function (response) {
                for (var i = files[parentid].length - 1; i >= 0; --i) {
                    if (files[parentid][i].Id == id) {
                        files[parentid].splice(i,1);
                    }
                }
                ewallettable.api().row('#'+parentid).invalidate().draw();
            },
            error: function (response) {
            }
        });
    }

    function refresh()
    {

       var d=$('#range').val();
      var arr = d.split(" - ");
        window.location.href ="{{ url("/ewalletrecord") }}/{{$userid}}/"+arr[0]+"/"+arr[1];
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
