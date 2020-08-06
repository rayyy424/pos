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

      .green {
        color: green;
      }

      .timeheader{
        background-color: gray;
      }

      .timeheader th{
        text-align: center;
      }

      .yellow {
        color: #f39c12;
      }

      .red{
        color:red;
      }

      .success {
          color: #00a65a;
      }

      .alert2 {
          color: #dd4b39;
      }

      .warning {
          color: #f39c12;
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

       <script type="text/javascript" language="javascript" class="init">

          var byperson;

          var country;

          $(document).ready(function() {

                    byperson = $('#byperson').dataTable( {

                             dom: "fBrtip",
                             bAutoWidth: true,
                             rowId:"Userid",
                             pageLength: 10,
                             //aaSorting:false,
                             columnDefs: [{ "visible": false, "targets": [1] },{"className": "dt-left", "targets": [2,3]},{"className": "dt-center", "targets": [-1]},{"className": "dt-right", "targets": "_all"}],
                             bScrollCollapse: true,
                             sScrollX: "100%",
                             sScrollY: "100%",
                             columns: [
                               { data: "","render":"", title:"No"},
                               { data: "users.Id"},
                               { data: "users.StaffId",  title: "Staff Id"},
                               { data: "users.Name",  title: "Name"},
                               // { data: "users.Bank_Name",  title: "Bank_Name"},
                               // { data: "users.Bank_Account_No",  title: "Bank_Account_No"},
                               { data: "users.Grade",  title: "Entitlement",
                                  "render": function ( data, type, full, meta ) {
                                    if(data)
                                    {
                                        if(data.indexOf("A") != -1)
                                        {
                                          return 1200;
                                        }
                                        else if(data.indexOf("B") != -1)
                                        {
                                          return 800;
                                        }
                                        else if(data.indexOf("C") != -1)
                                        {
                                          return 500;
                                        }
                                        else 
                                        {
                                          return 300;
                                        }
                                    }
                                    else
                                    {
                                      return 0;
                                    }

                                  }
                               },
                               { data: "users.Claim",  title: "Claim"},
                               { data: "leaves.Panel_Claim",  title: "Panel_Claim"},
                               // {  title: "Panel Balance", 
                               //  "render": function ( data, type, full, meta ) {
                               //          var balance = 300 - full.leaves.Panel_Claim;
                               //          if(full.leaves.Panel_Claim)
                               //          {
                               //            return parseFloat(balance).toFixed(2);
                               //          }
                               //          else
                               //          {
                               //            return 0.00;
                               //          }
                               //    }
                               // },
                               {  title: "Actual Balance",
                                "render": function ( data, type, full, meta ) {
                                      var grade = full.users.Grade;
                                    if(grade)
                                    {
                                      if(grade.indexOf("A") != -1)
                                        {
                                          total =  1200;
                                        }
                                        else if(grade.indexOf("B") != -1)
                                        {
                                          total = 800;
                                        }
                                        else if(grade.indexOf("C") != -1)
                                        {
                                          total = 500;
                                        }
                                        else
                                        {
                                          total = 300;
                                        }
                                    }
                                    else
                                    {
                                      total = 0;
                                    }
                                      var balance = total - full.users.Claim;
                                      return parseFloat(balance).toFixed(2);
                                  }
                               },
                               {  title: "Action",
                                "render": function ( data, type, full, meta ) {
                                      return '<button class="view btn btn-default btn-xs" title="View Medical Claim" id="'+full.users.Id+'" style="width:unset;"><i class="fa fa-eye"></i></button>' + ' <button class="create btn btn-default btn-xs" title="Create Medical Claim" id="'+full.users.Id+'" style="width:unset;"><i class="fa fa-user-plus"></i></button>'
                                  }
                               }
                               // { data: "leaves.Medical_Paid_Month",  title: "Paid On"},

                             ],
                             select: {
                                     style:    'os',
                                     selector: 'td:first-child'
                             },
                             buttons: [
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





                     $('a[data-toggle="tab"]').on( 'shown.bs.tab', function (e) {
                       var target = $(e.target).attr("href") // activated tab

                         $.fn.dataTable.tables( {visible: true, api: true} ).columns.adjust();

                     } );

                     byperson.api().on( 'order.dt search.dt', function () {
                         byperson.api().column(0, {search:'applied', order:'applied'}).nodes().each( function (cell, i) {
                             cell.innerHTML = i+1;
                         } );
                     } ).draw();


                     $(".byperson thead input").keyup ( function () {

                             /* Filter on the column (the index) of this element */
                             if ($('#byperson').length > 0)
                             {

                                 var colnum=document.getElementById('byperson').rows[0].cells.length;

                                 if (this.value=="[empty]")
                                 {

                                    byperson.fnFilter( '^$', this.name,true,false );
                                 }
                                 else if (this.value=="[nonempty]")
                                 {

                                    byperson.fnFilter( '^(?=\\s*\\S).*$', this.name,true,false );
                                 }
                                 else if (this.value.startsWith("!")==true && this.value.length>1)
                                 {

                                    byperson.fnFilter( '^(?'+ this.value +').*', this.name,true,false );
                                 }
                                 else if (this.value.startsWith("!")==false)
                                 {

                                     byperson.fnFilter( this.value, this.name,true,false );
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
        Staff Summary
        <small>Claim Summary</small>
      </h1>
      <ol class="breadcrumb">
        <li><a href="{{ url('/home') }}"><i class="fa fa-dashboard"></i> Home</a></li>
        <li><a href="#">Resource Management</a></li>
        <li><a href="#">Claim Summary</a></li>
        <li class="active">Staff Summary</li>
      </ol>
    </section>

    <!-- Main content -->
    <section class="content">
    <br>
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
      <div class="modal fade" id="MedicalClaimModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
            <div class="modal-dialog" role="document">
              <div class="modal-content">
                <div class="modal-header">
                  <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                  <h4 class="modal-title" id="myModalLabel">List of Medical Claim</h4>
                </div>
                <div class="modal-body">
                  <table class="table table-hover" id="listofmedicalclaim">
                    <thead>
                      <th>Start Date</th>
                      <th>End Date</th>
                      <th>Medical Claim</th>
                      <th>Panel Claim</th>
                      <!-- <th>File<th> -->
                    </thead>
                    <tbody>
                    </tbody>
                  </table>
                </div>
                <div class="modal-footer">
                  <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                </div>
              </div>
            </div>
      </div>

      <div class="modal fade" id="ActionModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
            <div class="modal-dialog" role="document">
              <div class="modal-content">
                <div class="modal-header">
                  <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                  <h4 class="modal-title" id="myModalLabel">Confirm</h4>
                </div>
                <form enctype="multipart/form-data" id="upload_form" role="form" method="POST" action="" >
                <div class="modal-body">
                    {!!csrf_field()!!}
                  <input type="hidden" id="confirmid" name="confirmid">
                  This is to create a <strong>MEDICAL CLAIM</strong> for <strong>NON MEDICAL LEAVE</strong>.<br><br>

                  <label>Claim Amount (RM)</label>
                  <input type="number" name="claim" id="claim" class="form-control">
                  <label>Panel Amount (RM)</label>
                  <input type="number" name="panel" id="panel" class="form-control">
                  <label>Support Documents</label>
                  <input type="file" name="file" id="file" class="form-control">
                </div>
                <div class="modal-footer">
                  <!-- <center><img src="{{ URL::to('/') . "/img/ajax-loader.gif" }}" width="50px" height="50px" alt="Loading" name='ajaxloader3' id="ajaxloader3"></center> -->
                  <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                  <button type="submit" class="btn btn-primary" id="confirm">Confirm</button>
                </div>
                </form>
              </div>
            </div>
      </div>
    <div class="row">
      <div class="col-md-12">
      <div class="box box-success">

      <div class="box-body">

          <div class="row">
          <div class="col-sm-4">
            <div class="form-group">
             <input type="text" class="form-control" id="range" name="range">
           </div>
         </div>

         <div class="col-sm-2">
           <div class="form-group">
           </div>
          </div>

          <div class="col-md-6">
            <div class="input-group">
              <button type="button" class="btn btn-success btn" data-toggle="modal" onclick="refresh();">Refresh</button>
            </div>
          </div>
          </div>
          <div class="row">
            <div class="col-md-3">

              <h4><b>Paid </b> : <p class="pull-right"><i><span id='total'>RM{{ $total ? $total->Total_Paid : 0}}</span></i></p></h4>
              <h4><b>Unpaid </b> : <p class="pull-right"><i><span id='total'>RM{{ $total ? number_format($total->Total_Claim - $total->Total_Paid,2) : 0}}</span></i></p></h4>
              <h4><b>Total Claim</b> : <p class="pull-right"><i><span id='total'>RM{{ $total ? $total->Total_Claim : 0}}</span></i></p></h4>
              <h4><b>Total Panel Claim</b> : <p class="pull-right"><i><span id='total'>RM{{ $total ? $total->Total_Panel_Claim : 0}}</span></i></p></h4>

            </div>
          </div>
      </div>
    </div>
    </div>
    </div>

      <div class="row">
       <div class="col-md-12">
         <div class="nav-tabs-custom">
            <ul class="nav nav-tabs">
              <li class="active"><a href="#person2" data-toggle="tab" id="person2tab">Staff Medical Claim</a></li>
            </ul>

            <div class="tab-content">

                <div class="active tab-pane" id="person2">

                  <table id="byperson" class="byperson" cellspacing="0" width="100%" padding="30px" style="font-size: 13px;">
                      <thead>
                        @if($byperson)
                        <tr class="search">

                          @foreach($byperson as $key=>$value)

                            @if ($key==0)
                              <?php $i = 0; ?>

                              @foreach($value as $field=>$a)
                                  @if ($i==0|| $i==1 )
                                    <th align='center'><input type='hidden' class='search_init' name='{{$i}}' placemark='{{$a}}'></th>
                                  @else
                                    <th align='center'><input type='text' class='search_init' name='{{$i}}'  placemark='{{$a}}'></th>
                                  @endif

                                  <?php $i ++; ?>
                              @endforeach

                              <th align='center'><input type='text' class='search_init' name='{{$i}}'  placemark='{{$a}}'></th>

                            @endif

                          @endforeach
                          <th align='center'><input type='text' class='search_init' name='{{$i}}'  placemark='{{$a}}'></th>
                        </tr>

                          @endif
                          <tr>

                            @foreach($byperson as $key=>$value)

                              @if ($key==0)
                                    <td></td>
                                @foreach($value as $field=>$value)

                                    <td/>{{ $field }}</td>
                                @endforeach

                              @endif

                            @endforeach
                            <td></td>
                            <td></td>
                            <!-- <td></td> -->
                          </tr>
                      </thead>
                      <tbody>

                        <?php $i = 0; ?>
                          @foreach($byperson as $person)

                              <tr id="row_{{ $i }}" >
                                  <td></td>
                                  @foreach($person as $key=>$value)
                                    <td>
                                      @if($key=="Id")
                                        {{ $value }}
                                      @elseif(is_numeric($value) && $key=="Claim")
                                        {{ number_format($value,2,".",",") }}
                                      @elseif($key == "Claim" && $value=="" || $value=="NaN")
                                        0.00
                                      @else
                                        @if ($key=="Medical_Paid_Month" && ($value == '' || $value == null))
                                        {{ 'Unpaid' }}
                                        @else
                                        {{ $value }}
                                        @endif
                                      @endif
                                    </td>
                            
                                  @endforeach
                                  <td></td>
                                  <td></td>
                                  <!-- <td></td> -->
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
    $(function(){
      $('#range').daterangepicker({locale: {
        format: 'DD-MMM-YYYY'
        },startDate: '{{$start}}',
        endDate: '{{$end}}'});

      $( "#upload_form" ).submit(function( event ) {
      event.preventDefault();
      return false;
      });
    });
    function refresh()
    {
      var d=$('#range').val();
      var arr = d.split(" - ");
      window.location.href ="{{ url("/medicalclaimsummary") }}/"+arr[0]+"/"+arr[1];

    }

    $(document).ready(function() {
        $(document).on('click', '.create', function(e) {
      var id = $(this).attr('id');
      $('#confirmid').val(id);
      $('#ActionModal').modal('show');

      });
    });

    $(document).ready(function() {
        $(document).on('click', '.view', function(e) {
         var id = $(this).attr('id');
         var start = "{{$start}}";
         var end = "{{$end}}";
              $.ajaxSetup({
                headers: { 'X-CSRF-Token' : $('meta[name=_token]').attr('content') }
                });
              $.ajax({
                  url: "{{ url('/getmedicalclaim') }}" + "/" + id,
                  method: "GET",
                  data: {start:start,end:end},
                  success: function(response){
                    $('#listofmedicalclaim > TBODY').empty();
                    response.list.forEach(function(element) {
                        $('#listofmedicalclaim > TBODY').append(`<tr id="${element.Id}"><td>${element.Start_Date}</td><td>${element.End_Date}</td><td>${element.Medical_Claim}</td><td>${element.Panel_Claim}</td><td></td></a></tr>`)
                      });
                    $('#MedicalClaimModal').modal('show');
                  }
                });

      });
    });

    $(document).ready(function() {
        $(document).on('click', '#confirm', function(e) {
              $.ajaxSetup({
                headers: { 'X-CSRF-Token' : $('meta[name=_token]').attr('content') }
                });
              $.ajax({
                  url: "{{ url('/createmedicalclaim') }}",
                  method: "POST",
                  contentType: false,
                  processData: false,
                  data:new FormData($("#upload_form")[0]),
                  success: function(response){
                    $('#ActionModal').modal('hide');
                    if (response==1)
                    {
                      var message="Claim Successfully Submitted";
                      $("#update-alert ul").html(message);
                      $("#update-alert").modal('show');
                      window.location.reload();
                    }
                    else
                    {
                      var errormessage="Failed to Submit Claim";
                      $("#error-alert ul").html(errormessage + "<br>" + response);
                      $("#error-alert").modal('show');
                    }
                  }
                });
            });
      });


  </script>

@endsection
