
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

      .leavetable{
        text-align: center;
      }

      /* table.dataTable th {
  			min-width: 35px;
  	} */

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
      <script type="text/javascript" language="javascript" src="{{ asset('/plugin/datatables/extensions/Select2/editor.select2.js') }}"></script>
       <script type="text/javascript" language="javascript" src="https://cdn.datatables.net/plug-ins/1.10.19/api/sum().js"></script>

      <script type="text/javascript" language="javascript" src="{{ asset('/plugin/js/dataTables.editor.min.js') }}"></script>
      {{-- chart js --}}
      <script type="text/javascript" language="javascript" src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.4.0/Chart.bundle.min.js"></script>

      <script type="text/javascript" language="javascript" class="init">

      var summary;
      var summary2;
      var summary1;
      var editor;

      $(document).ready(function() {

         // editor = new $.fn.dataTable.Editor( {
         //                        ajax: {url:"{{ asset('/Include/transportcharges.php') }}", "data": {
         //                              "Start": "{{ $start }}",
         //                              "End": "{{ $end }}"
         //                            }
         //                          },
                                
         //                         table: "#summary",
         //                         idSrc: "deliveryform.Id",
         //                         fields: [
         //                                 // {
         //                                 //        label:"Id",
         //                                 //        name:"deliveryform.Id",
         //                                 //        type: "hidden"
         //                                 //  },
         //                                  {
         //                                        label:"Incentive",
         //                                        name:"deliveryform.incentive"
         //                                  }

         //                         ]
         //                 } );

           summary1 = $('#summary1').dataTable( {
                           columnDefs: [{ "visible": false, "targets":[1]},{"className": "dt-center", "targets": "_all"}],
                            responsive: false,
                            dom: "Bltp",
                            sScrollX: "100%",
                            sScrollY: "100%",
                            scrollCollapse: true,
                            iDisplayLength:100,
                            bAutoWidth: true,
                            iDisplayLength:10,
                            rowId:"deliveryform.Id",
                            order: [[ 2, "desc" ],[ 3, "desc" ]],
                            fnInitComplete: function(oSettings, json) {
                              var api = this.api();
                            var total = 0.0;

                            total = api.column(3).data().sum();
                             $("#companytotal").html("RM" + total.toFixed(2));

                    },
                        "drawCallback": function( settings ) {
                          
                          var api = this.api();
                          total = 0.0;
                          total = api.column(3,{search:"applied"}).data().sum();
                        
                         $("#companytotal").html("RM" + total.toFixed(2));
                        },
                            columns: [
                            { data : null, "render":"", title: "No"},
                            { data : 'deliveryform.Id', title:"Id"},
                            { data: "companies.Company_Name", title:"Company_Name"},
                            { data: "deliverylocation.driverincentive",title:"Total Charges (RM)"}

                    ],
                                 select: true,
                                 buttons: [
                                 ],
                     }); 

                            summary2 = $('#summary2').dataTable( {
                           columnDefs: [{ "visible": false, "targets":[1]},{"className": "dt-center", "targets": "_all"}],
                            responsive: false,
                            dom: "Bltp",
                            sScrollX: "100%",
                            sScrollY: "100%",
                            scrollCollapse: true,
                            iDisplayLength:100,
                            bAutoWidth: true,
                            iDisplayLength:10,
                            rowId:"deliveryform.Id",
                            order: [[ 2, "desc" ],[ 3, "desc" ]],
                            fnInitComplete: function(oSettings, json) {
                              var api = this.api();
                            var total = 0.0;

                            total = api.column(3).data().sum();
                             $("#typetotal").html("RM" + total.toFixed(2));

                    },
                        "drawCallback": function( settings ) {
                          
                          var api = this.api();
                          total = 0.0;
                          total = api.column(3,{search:"applied"}).data().sum();
                        
                         $("#typetotal").html("RM" + total.toFixed(2));
                        },
                            columns: [
                            { data : null, "render":"", title: "No"},
                            { data : 'deliveryform.Id', title:"Id"},
                            { data: "deliveryform.project_type", title:"Project Type"},
                            { data: "deliverylocation.driverincentive",title:"Total Charges (RM)"}

                    ],
                                 select: true,
                                 buttons: [
                                 ],
                     });

          summary = $('#summary').dataTable( {
                           columnDefs: [{ "visible": false, "targets":[1]},{"className": "dt-center", "targets": "_all"}],
                            responsive: false,
                            dom: "Bltp",
                            sScrollX: "100%",
                            sScrollY: "100%",
                            scrollCollapse: true,
                            iDisplayLength:100,
                            bAutoWidth: true,
                            iDisplayLength:10,
                            rowId:"deliveryform.Id",
                            order: [[ 2, "desc" ],[ 3, "desc" ]],
                            fnInitComplete: function(oSettings, json) {
                              var api = this.api();
                            var total = 0.0;

                            total = api.column(10).data().sum();
                             $("#total").html("RM" + total.toFixed(2));

                    },
                        "drawCallback": function( settings ) {
                          
                          var api = this.api();
                          total = 0.0;
                          total = api.column(10,{search:"applied"}).data().sum();
                        
                         $("#total").html("RM" + total.toFixed(2));
                        },
                            columns: [
                            { data : null, "render":"", title: "No"},
                            { data: "deliveryform.Id", title:"Form Id"},
                            { data: "users.Name", title:"Driver Name"},
                            { data: "deliveryform.DO_No", title:"DO Number"},
                            { data: "radius.Location_Name", title:"Site"},
                            { data: "projects.Project_Name", title:"Project"},
                            { data: "deliveryform.project_type", title:"Project Type"},
                            { data: "companies.Company_Name", title:"Company"},
                            { data: "deliverylocation.area", title:"Area"},
                            { data: "roadtax.Lorry_Size", title:"Lorry Size"},
                            { data: "deliverylocation.driverincentive",title:"Total Charges (RM)"}

                    ],
                                 select: true,
                                 buttons: [
                                 ],
                     });

             $('#summary').on( 'click', 'tbody td', function (e) {
                        editor.inline( this, {
                          onBlur: 'submit'
                        } );
                      } );

             summary.api().on( 'order.dt search.dt', function () {
                summary.api().column(0, {search:'applied', order:'applied'}).nodes().each( function (cell, i) {
                    cell.innerHTML = i+1;
                } );
                // console.log(summary.api().column( 7, {page:'current'} ).data().sum() );
                //  console.log(summary.api().column( 8, {page:'current'} ).data().sum() );
            } ).draw();
                 
            $(".summary thead input").keyup ( function () {

                    /* Filter on the column (the index) of this element */
                    if ($('#summary').length > 0)
                    {

                        var colnum=document.getElementById('summary').rows[0].cells.length;

                        if (this.value=="[empty]")
                        {

                           summary.fnFilter( '^$', this.name,true,false );
                        }
                        else if (this.value=="[nonempty]")
                        {

                           summary.fnFilter( '^(?=\\s*\\S).*$', this.name,true,false );
                        }
                        else if (this.value.startsWith("!")==true && this.value.length>1)
                        {

                           summary.fnFilter( '^(?'+ this.value +').*', this.name,true,false );
                        }
                        else if (this.value.startsWith("!")==false)
                        {

                            summary.fnFilter( this.value, this.name,true,false );
                        }
                    } });

                summary1.api().on( 'order.dt search.dt', function () {
                summary1.api().column(0, {search:'applied', order:'applied'}).nodes().each( function (cell, i) {
                    cell.innerHTML = i+1;
                } );
                // console.log(summary.api().column( 7, {page:'current'} ).data().sum() );
                //  console.log(summary.api().column( 8, {page:'current'} ).data().sum() );
            } ).draw();
                 
            $(".summary1 thead input").keyup ( function () {

                    /* Filter on the column (the index) of this element */
                    if ($('#summary1').length > 0)
                    {

                        var colnum=document.getElementById('summary1').rows[0].cells.length;

                        if (this.value=="[empty]")
                        {

                           summary1.fnFilter( '^$', this.name,true,false );
                        }
                        else if (this.value=="[nonempty]")
                        {

                           summary1.fnFilter( '^(?=\\s*\\S).*$', this.name,true,false );
                        }
                        else if (this.value.startsWith("!")==true && this.value.length>1)
                        {

                           summary1.fnFilter( '^(?'+ this.value +').*', this.name,true,false );
                        }
                        else if (this.value.startsWith("!")==false)
                        {

                            summary1.fnFilter( this.value, this.name,true,false );
                        }
                    }} );

                summary2.api().on( 'order.dt search.dt', function () {
                summary2.api().column(0, {search:'applied', order:'applied'}).nodes().each( function (cell, i) {
                    cell.innerHTML = i+1;
                } );
                // console.log(summary.api().column( 7, {page:'current'} ).data().sum() );
                //  console.log(summary.api().column( 8, {page:'current'} ).data().sum() );
            } ).draw();
                 
            $(".summary2 thead input").keyup ( function () {

                    /* Filter on the column (the index) of this element */
                    if ($('#summary2').length > 0)
                    {

                        var colnum=document.getElementById('summary2').rows[0].cells.length;

                        if (this.value=="[empty]")
                        {

                           summary2.fnFilter( '^$', this.name,true,false );
                        }
                        else if (this.value=="[nonempty]")
                        {

                           summary2.fnFilter( '^(?=\\s*\\S).*$', this.name,true,false );
                        }
                        else if (this.value.startsWith("!")==true && this.value.length>1)
                        {

                           summary2.fnFilter( '^(?'+ this.value +').*', this.name,true,false );
                        }
                        else if (this.value.startsWith("!")==false)
                        {

                            summary2.fnFilter( this.value, this.name,true,false );
                        }
                    }} );

          $('#viewcompany').click(function() {
          if ($(this).is(':checked')) {
            $('#viewtype').prop("checked", false);
            $('#summary1').parents('div.dataTables_wrapper').first().show();
            $('#summary').parents('div.dataTables_wrapper').first().hide();
            $('#summary2').parents('div.dataTables_wrapper').first().hide();
            $('.total').hide();
            $('.typetotal').hide();
            $('.companytotal').show();
          }
          else
          {
            $('#summary1').parents('div.dataTables_wrapper').first().hide();
            $('#summary').parents('div.dataTables_wrapper').first().show();
            $('#summary2').parents('div.dataTables_wrapper').first().hide();
            $('.companytotal').hide();
            $('.typetotal').hide();
            $('.total').show();
          }
          });
          $('#viewtype').click(function() {
          if ($(this).is(':checked')) {
            $('#viewcompany').prop("checked", false);
            $('#summary').parents('div.dataTables_wrapper').first().hide();
            $('#summary1').parents('div.dataTables_wrapper').first().hide();
            $('#summary2').parents('div.dataTables_wrapper').first().show();
            $('.total').hide();
            $('.companytotal').hide();
            $('.typetotal').show();
          }
          else{
             $('#summary1').parents('div.dataTables_wrapper').first().hide();
            $('#summary').parents('div.dataTables_wrapper').first().show();
            $('#summary2').parents('div.dataTables_wrapper').first().hide();
            $('.companytotal').hide();
            $('.typetotal').hide();
            $('.total').show();
          }
          });

         $('.companytotal').hide();
         $('.typetotal').hide();
         $('#summary2').parents('div.dataTables_wrapper').first().hide();
         $('#summary1').parents('div.dataTables_wrapper').first().hide();

        });

      </script>

      @endsection

@section('content')

  <div class="content-wrapper">
      <!-- Content Header (Page header) -->
    <section class="content-header">
      <h1>
      Transport Charges Summary
      <small>Human Resource</small>
      </h1>

      <ol class="breadcrumb">
      <li><a href="{{ url('/home') }}"><i class="fa fa-dashboard"></i> Home</a></li>
      <li><a href="#">Human Resource</a></li>
      <li class="active">Transport Charges Summary</li>
      </ol>
    </section>

    <section class="content">
      <br>
      <div class="row">
        <div class="col-md-12">
          <div class="box">
            <div class="box-body">
               <div class="row">


                  <div class="col-md-3">
                   <div class="input-group">
                     <label>Date:</label>
                     <input type="text" class="form-control" id="range" name="range">

                   </div>
                 </div>

                 <div class="col-md-4">
                   <div class="input-group">
                    <label>Company: <input type="checkbox" name="viewcompany" id="viewcompany">Group By Company</label>

                     <select class="form-control" id="company" name="company">
                      <option value="" selected=""></option>
                      @foreach ($company as $c)
                      <option value="{{$c->Id}}">{{$c->Company_Name}}</option>
                      @endforeach
                     </select>

                   </div>
                 </div>

                  <div class="col-md-3">
                   <div class="input-group">
                    <label>Project Type: <input type="checkbox" name="viewtype" id="viewtype">Group By Project Type</label>
                     <select class="form-control" id="projecttype" name="projecttype">
                      <option value="" selected=""></option>
                      <option value="CME">CME</option>
                      <option value="GSC">GSC</option>
                      <option value="GST">GST</option>
                      <option value="FAB">FAB</option>
                      <option value="Others">Others</option>
                     </select>

                   </div>
                 </div>

                 <div class="col-md-2">
                     <div class="input-group">
                      <label>Refresh</label>
                       <button type="button" class="btn btn-success btn" data-toggle="modal" onclick="refresh();">Refresh</button>
                     </div>
                 </div>



               </div>
               <div class="row">
                <div class="col-md-3 total">
                   <h4 class="" >Total Charges : <i><span id='total'>RM0.00</span></i></h4>
                 </div>
                 <div class="col-md-3 companytotal">
                   <h4 class="" >Total Charges : <i><span id='companytotal'>RM0.00</span></i></h4>
                 </div>
                 <div class="col-md-3 typetotal">
                   <h4 class="" >Total Charges : <i><span id='typetotal'>RM0.00</span></i></h4>
                 </div>
               </div>
            </div>
          </div>
        </div>
      </div>


      <div class="box">
        <div class="box-body">

          <table id="summary" class="summary" cellspacing="0" width="100%" padding="30px" style="font-size: 13px;">
             <thead>
                                <tr class="search">
                                @foreach($summary as $key=>$value)

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
                                    @endif
                                @endforeach
                                </tr>
                                <tr>
                                    @foreach($summary as $key=>$value)

                                        @if ($key==0)
                                        <td></td>

                                        @foreach($value as $field=>$value)
                                        <td>
                                            {{ $field }}
                                        </td>
                                        @endforeach

                                        @endif

                                    @endforeach
                                </tr>
                            </thead>
                            <tbody>
                                <?php $i = 0; ?>
                                @foreach($summary as $delivery)

                                <tr id="row_{{ $i }}">
                                    <td></td>      

                                    @foreach($delivery as $key=>$value)
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

                         <table id="summary1" class="summary1" cellspacing="0" width="100%" padding="30px" style="font-size: 13px;">
             <thead>
                                <tr class="search">
                                @foreach($bycompany as $key=>$value)

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
                                    @endif
                                @endforeach
                                </tr>
                                <tr>
                                    @foreach($bycompany as $key=>$value)

                                        @if ($key==0)
                                        <td></td>

                                        @foreach($value as $field=>$value)
                                        <td>
                                            {{ $field }}
                                        </td>
                                        @endforeach

                                        @endif

                                    @endforeach
                                </tr>
                            </thead>
                            <tbody>
                                <?php $i = 0; ?>
                                @foreach($bycompany as $delivery)

                                <tr id="row_{{ $i }}">
                                    <td></td>      

                                    @foreach($delivery as $key=>$value)
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

                         <table id="summary2" class="summary2" cellspacing="0" width="100%" padding="30px" style="font-size: 13px;">
             <thead>
                                <tr class="search">
                                @foreach($bytype as $key=>$value)

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
                                    @endif
                                @endforeach
                                </tr>
                                <tr>
                                    @foreach($bytype as $key=>$value)

                                        @if ($key==0)
                                        <td></td>

                                        @foreach($value as $field=>$value)
                                        <td>
                                            {{ $field }}
                                        </td>
                                        @endforeach

                                        @endif

                                    @endforeach
                                </tr>
                            </thead>
                            <tbody>
                                <?php $i = 0; ?>
                                @foreach($bytype as $delivery)

                                <tr id="row_{{ $i }}">
                                    <td></td>      

                                    @foreach($delivery as $key=>$value)
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

      $(".select2").select2();

      $('#range').daterangepicker({locale: {
        format: 'DD-MMM-YYYY'
      },startDate: '{{$start}}',
      endDate: '{{$end}}'});

    });

    function refresh()
    {

      var d=$('#range').val();
      var arr = d.split(" - ");
      var projecttype = $('#projecttype').val();
      var company = $('#company').val();
      console.log(projecttype,company)

      if((projecttype == null || projecttype == "") && (company == null || company == ""))
      {
        window.location.href ="{{ url("/transportcharges") }}/"+arr[0]+"/"+arr[1];
      }
      else if((company == null || company == ""))
      {
        window.location.href ="{{ url("/transportcharges") }}/"+arr[0]+"/"+arr[1]+"/"+projecttype;
      }
      else if((projecttype == null || projecttype == ""))
      {
        window.location.href ="{{ url("/transportcharges") }}/"+arr[0]+"/"+arr[1]+"/"+ null +"/"+company;
      }
    }


</script>



@endsection
