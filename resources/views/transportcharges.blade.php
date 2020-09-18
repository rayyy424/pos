
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
      var detail;
      var editor;

      $(document).ready(function() {
        // editor = new $.fn.dataTable.Editor( {
        //                         ajax: {url:"{{ asset('/Include/driverincentivesummary.php') }}", "data": {
        //                               "Start": "{{ $start }}",
        //                               "End": "{{ $end }}"
        //                             }
        //                           },

        //                          table: "#summary",
        //                          idSrc: "deliveryform.Id",
        //                          fields: [
        //                                  // {
        //                                  //        label:"Id",
        //                                  //        name:"deliveryform.Id",
        //                                  //        type: "hidden"
        //                                  //  },
        //                                   {
        //                                         label:"Incentive",
        //                                         name:"deliveryform.incentive"
        //                                   }

        //                          ]
        //                  } );
        
        summary = $('#summary').dataTable( {
                         columnDefs: [{ "visible": false, "targets":[2,5,6,7]},{"className": "dt-right", "targets": [11,12,13,14]}],
                          responsive: false,
                          dom: "Bltp",
                          sScrollX: "100%",
                          sScrollY: "100%",
                          scrollCollapse: true,
                          iDisplayLength:100,
                          bAutoWidth: true,
                          iDisplayLength:10,
                          rowId:"deliveryform.Id",
                          // order: [[ 1, "asc" ]],
                          fnInitComplete: function(oSettings, json) {
                            var api = this.api();
                          var total = 0.0;
                          var extra = api.column(13).data().sum();
                          total = api.column(11).data().sum();
                          var incentive = total*0.15 + extra;
                           $("#total").html("RM" + total.toFixed(2));
                           $("#totalincentive").html("RM" + incentive.toFixed(2));

                  },
                      "drawCallback":  function( settings ) {

                        var api = this.api();
                        var total = 0.0;
                        total = api.column(11,{search:"applied"}).data().sum();
                        var extra = api.column(13,{search:"applied"}).data().sum();
                        var incentive = total*0.15 + extra;
                       $("#total").html("RM" + total.toFixed(2));
                       $("#totalincentive").html("RM" + incentive.toFixed(2));
                      },  
                          columns: [
                          { data : null, "render":"", title: "No"},
                          { data: "deliveryform.delivery_date", title:"Delivery Date"},
                          { data: "deliveryform.roadtaxId", title:"roadtax Id"},

                          { data: "users.Name", title:"Driver Name"},
                          { data: "deliveryform.DO_No", title:"DO Number"},
                          { data: "radius.Location_Name", title:"Site"},
                          { data: "companies.Company_Name", title:"Company"},
                          { data: "deliverylocation.area", title:"Area"},
                          { data: "roadtax.Lorry_Size", title:"Lorry Size"},
                          { data: "deliverylocation.charges",title:"Total Charges (RM)",
                          "render": function ( data, type, full, meta ) {
                                      var info = parseFloat(data).toFixed(2);
                                      return '<a href="/transportchargesdetails/'+full.deliveryform.roadtaxId+'/'+full.deliveryform.delivery_date+'">'+info+'</a>'
                                             }
                          },{ data: "deliverylocation.driverincentive", title:"Incentive (RM)",
                              "render": function ( data, type, full, meta ) {
                                      var charges = full.deliverylocation.charges;
                                      var info = charges*0.15;

                                      return info.toFixed(2);
                              }
                          },
                          { data: "deliveryform.incentive", title:"Extra Incentive (RM)",
                            "render": function ( data, type, full, meta ) {
                                if(data == "" || data == null)
                                {
                                    return 0;
                                }
                                else
                                {
                                   return parseFloat(data).toFixed(2);
                                }
                              }
                          },
                          { data: "deliveryform.totalincentive", title:"Total Incentive (RM)",
                            "render": function ( data, type, full, meta ) {
                                  var charges = full.deliverylocation.charges;
                                  var incentive = charges*0.15;
                                  if(full.deliveryform.incentive == "" || full.deliveryform.incentive == null)
                                  {
                                    var extra = 0;
                                  }
                                  else
                                  {
                                    var extra = full.deliveryform.incentive; 
                                  }
                                  var total = incentive + parseFloat(extra);

                                  return parseFloat(total).toFixed(2);
                            }
                          }

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
                               ],
                   });

           summary1 = $('#summary1').dataTable( {
                           columnDefs: [{"className": "dt-right", "targets": [2]}],
                            responsive: false,
                            dom: "Bltp",
                            sScrollX: "100%",
                            sScrollY: "100%",
                            scrollCollapse: true,
                            iDisplayLength:100,
                            bAutoWidth: true,
                            iDisplayLength:10,
                            // rowId:"deliveryform.Id",
                            order: [[ 1, "asc" ]],
                            fnInitComplete: function(oSettings, json) {
                            var api = this.api();
                            var total = 0.0;

                            total = api.column(2).data().sum();
                             $("#companytotal").html("RM" + total.toFixed(2));

                    },
                        "drawCallback": function( settings ) {

                          var api = this.api();
                          total = 0.0;
                          total = api.column(2,{search:"applied"}).data().sum();

                         $("#companytotal").html("RM" + total.toFixed(2));
                        },
                            columns: [
                            { data : null, "render":"", title: "No"},

                            { data: "companies.Company_Name", title:"Company_Name"},
                            { data: "deliverylocation.transportcharges",title:"Total Charges (RM)","render": function ( data, type, full, meta ) {
                                              return parseFloat(data).toFixed(2);
                                               }
                            }

                    ],
                                 select: true,
                                 buttons: [
                                 ],
                     });

                     summary2 = $('#summary2').dataTable( {
                                     columnDefs: [{"className": "dt-right", "targets": [1]}],
                                      responsive: false,
                                      dom: "Bltp",
                                      sScrollX: "100%",
                                      sScrollY: "100%",
                                      scrollCollapse: true,
                                      iDisplayLength:100,
                                      bAutoWidth: true,
                                      iDisplayLength:10,
                                      // rowId:"deliveryform.Id",
                                      order: [[ 1, "asc" ]],
                                      fnInitComplete: function(oSettings, json) {
                                        var api = this.api();
                                      var total = 0.0;

                                      total = api.column(1).data().sum();
                                       $("#typetotal").html("RM" + total.toFixed(2));

                              },
                                  "drawCallback": function( settings ) {

                                    var api = this.api();
                                    total = 0.0;
                                    total = api.column(1,{search:"applied"}).data().sum();

                                   $("#typetotal").html("RM" + total.toFixed(2));
                                  },
                                      columns: [
                                      { data : null, "render":"", title: "No"},

                            { data: "deliverylocation.transportcharges",title:"Total Charges (RM)","render": function ( data, type, full, meta ) {
                                              return parseFloat(data).toFixed(2);
                                               }
                            }

                    ],
                                 select: true,
                                 buttons: [
                                 ],
                     });

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

            detail = $('#detail').dataTable( {
                           columnDefs: [{ "visible": false, "targets":[1,12]},{"className": "dt-right", "targets": [11,13,15]},{ "width": "200%", "targets": [12] }],
                            responsive: false,
                            dom: "Bltp",
                            sScrollX: "100%",
                            sScrollY: "100%",
                            scrollCollapse: true,
                            iDisplayLength:100,
                            bAutoWidth: true,
                            iDisplayLength:10,
                            // rowId:"deliveryform.Id",
                            // order: [[ 2, "desc" ],[ 3, "desc" ]],
                            "drawCallback":  function( settings ) {
                            var api = this.api();
                            api.rows().every( function () {
                               var rowcount = 0;
                               var maxnum = 0;
                               var d = this.data();
                               var lorry = d.roadtax.Vehicle_No;
                               var date = d.deliveryform.delivery_date;
                               var trip = d.deliveryform.trip;
                               var extra = d.deliveryform.incentive;
                               if(d.deliveryform.trip.match("1 Way Trip")!= null ||d.deliverylocation.area.match("Outstation") != null)
                               {
                                  api.rows().every( function (){
                                     var c = this.data();
                                      if(c.roadtax.Vehicle_No == lorry && c.deliveryform.delivery_date == date && c.deliveryform.trip == trip)
                                      {
                                         if(parseFloat(c.deliverylocation.transportcharges) > maxnum)
                                         {  
                                            maxnum = c.deliverylocation.transportcharges;
                                         }
                                         
                                         rowcount = rowcount + 1;
                                      }
                                  });
                                  var charges = parseFloat(Math.round((maxnum/rowcount)*100)/100).toFixed(2);
                               }
                               if(d.deliveryform.trip.match("1 Way Trip") != null)
                                  {
                                    this.cell(this,11).data(charges);
                                  }
                                  else if(d.deliveryform.trip.match("2 Way Trip") != null)
                                  {
                                    charges = parseFloat(this.cell(this,13).data()).toFixed(2);
                                    this.cell(this,11).data(charges);
                                  }
                                  else
                                  {
                                    if(d.deliverylocation.area.match("Outstation") != null){
                                      this.cell(this,11).data(charges);
                                    }
                                    else if(d.deliverylocation.area.match("Zone") != null)
                                    {
                                      charges = parseFloat(this.cell(this,13).data()).toFixed(2);
                                      this.cell(this,11).data(charges);
                                    }
                                  }

                                  var incentive = charges*0.15;
                                  this.cell(this,13).data(parseFloat(incentive).toFixed(2));

                                  if(extra != "")
                                  {
                                    var total = incentive + parseFloat(extra);
                                  }
                                  else
                                  {
                                    var total = incentive;
                                  }
                                  this.cell(this,15).data(parseFloat(total).toFixed(2));
                            });                      
                            },
                            columns: [
                            { data : null, "render":"", title: "No"},
                            { data: "deliveryform.Id", title:"Form Id"},
                            { data: "roadtax.Vehicle_No", title:"Vehicle Number"},
                            { data: "deliveryform.delivery_date", title:"Delivery Date"},
                            { data: "users.Name", title:"Driver Name"},
                            { data: "deliveryform.DO_No", title:"DO Number"},
                            { data: "radius.Location_Name", title:"Site"},
                            { data: "client.Company_Name", title:"Client"},
                            { data: "companies.Company_Name", title:"Company"},
                            { data: "deliveryform.trip", title:"Trip Type"},
                            { data: "deliverylocation.area", title:"Area"},
                            { data: "roadtax.Lorry_Size", title:"Charges (RM)"},
                            { data: "deliverylocation.transportcharges",title:"Total Charges (RM)",
                            "render": function ( data, type, full, meta ) {

                                        var charges = parseFloat(data).toFixed(2);
                                        var rowcount = 1;
                                        var real = Math.round((charges/rowcount)*100)/100;
                                        if(full.deliveryform.trip !== "")
                                        {
                                          if(full.deliveryform.trip.indexOf("1 Way Trip") !== -1)
                                          {
                                           real = real;
                                          }
                                          else if(full.deliveryform.trip.indexOf("2 Way Trip") !== -1)
                                          {
                                            real = charges;
                                          }
                                        }
                                        else
                                        {
                                          if(full.deliverylocation.area.indexOf("Zone") !== -1)
                                          {
                                            real = charges;
                                          }
                                          else if (full.deliverylocation.area.indexOf("Outstation") !== -1)
                                          {
                                            real = real;
                                          }
                                        }

                                        return real;
                            }
                          },{ data: "deliverylocation.driverincentive", title:"Incentive (RM)",
                              "render": function ( data, type, full, meta ) {
                                   // var charges = full.deliverylocation.transportcharges;
                                   //    var info = charges*0.15;

                                      return data;
                              }
                          },
                          { data: "deliveryform.incentive", title:"Extra Incentive (RM)",
                            "render": function ( data, type, full, meta ) {
                                if(data == "" || data == null)
                                {
                                    return '<input type="number" step="0.02" class="form-control" value="'+data+'" id="'+full.deliveryform.Id+'">';
                                }
                                else
                                {
                                   data = parseFloat(data).toFixed(2);
                                   return '<input type="number" step="0.02" class="form-control" value="'+data+'" id="'+full.deliveryform.Id+'">';
                                }
                              }
                          },
                          { data: "deliveryform.totalincentive", title:"Total Incentive (RM)",
                            "render": function ( data, type, full, meta ) {
                                  return data;
                            }
                          }

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
                                 ],
                     });

                $('#detail').on( 'click', 'tbody td', function (e) {
                        var tr = $(this).parent().attr('id');
                        var table = $('#detail').dataTable();
                        var row_index = $(this).closest("tr").index();
                        var col = table.api().column(this)[0][0];
                        var data = table.api().rows().data();
                        $("input").blur(function(){
                            var id = $(this).attr('id');
                            var val = $(this).val();
                            updateincentive(id,val);
                            data.each(function (value, index) {
                                if(value.deliveryform.Id == id)
                                {
                                var totalincentive = table.api().cell(index,13).data();
                                var update = parseFloat(val) + parseFloat(totalincentive);
                                table.api().cell(index,15).data(parseFloat(update).toFixed(2));
                                }
                            });
                            // console.log(totalincentive,update);
                        });
                } );

             detail.api().on( 'order.dt search.dt', function () {
                detail.api().column(0, {search:'applied', order:'applied'}).nodes().each( function (cell, i) {
                    cell.innerHTML = i+1;
                } );
            } ).draw();
                 
            $(".detail thead input").keyup ( function () {

                    /* Filter on the column (the index) of this element */
                    if ($('#detail').length > 0)
                    {

                        var colnum=document.getElementById('detail').rows[0].cells.length;

                        if (this.value=="[empty]")
                        {

                           detail.fnFilter( '^$', this.name,true,false );
                        }
                        else if (this.value=="[nonempty]")
                        {

                           detail.fnFilter( '^(?=\\s*\\S).*$', this.name,true,false );
                        }
                        else if (this.value.startsWith("!")==true && this.value.length>1)
                        {

                           detail.fnFilter( '^(?'+ this.value +').*', this.name,true,false );
                        }
                        else if (this.value.startsWith("!")==false)
                        {

                            detail.fnFilter( this.value, this.name,true,false );
                        }
                    } });

          $('#viewcompany').click(function() {
          if ($(this).is(':checked')) {
            $('#viewtype').prop("checked", false);
            $('#all').prop("checked", false);
            $('#summary1').parents('div.dataTables_wrapper').first().show();
            $('#summary').parents('div.dataTables_wrapper').first().hide();
            $('#summary2').parents('div.dataTables_wrapper').first().hide();
            $('#detail').parents('div.dataTables_wrapper').first().hide();
            $('.total').hide();
            $('.typetotal').hide();
            $('.companytotal').show();
          }
          else
          {
            $('#summary1').parents('div.dataTables_wrapper').first().hide();
            $('#summary').parents('div.dataTables_wrapper').first().show();
            $('#summary2').parents('div.dataTables_wrapper').first().hide();
            $('#detail').parents('div.dataTables_wrapper').first().hide();
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
            $('#detail').parents('div.dataTables_wrapper').first().hide();
            $('.total').hide();
            $('.companytotal').hide();
            $('.typetotal').show();
          }
          else{
             $('#summary1').parents('div.dataTables_wrapper').first().hide();
            $('#summary').parents('div.dataTables_wrapper').first().show();
            $('#summary2').parents('div.dataTables_wrapper').first().hide();
            $('#detail').parents('div.dataTables_wrapper').first().hide();
            $('.companytotal').hide();
            $('.typetotal').hide();
            $('.total').show();
          }
          });
          $('#all').click(function() {
          if ($(this).is(':checked')) {
            $('#viewcompany').prop("checked", false);
            $('#viewtype').prop("checked", false);
            $('#summary').parents('div.dataTables_wrapper').first().hide();
            $('#summary1').parents('div.dataTables_wrapper').first().hide();
            $('#summary2').parents('div.dataTables_wrapper').first().hide();
            $('#detail').parents('div.dataTables_wrapper').first().show();
            $('.total').show();
            $('.companytotal').hide();
            $('.typetotal').hide();
          }
          else{
            $('#summary1').parents('div.dataTables_wrapper').first().hide();
            $('#summary').parents('div.dataTables_wrapper').first().show();
            $('#summary2').parents('div.dataTables_wrapper').first().hide();
            $('#detail').parents('div.dataTables_wrapper').first().hide();
            $('.companytotal').hide();
            $('.typetotal').hide();
            $('.total').show();
          }
          });
         $('.companytotal').hide();
         $('.typetotal').hide();
         $('#summary2').parents('div.dataTables_wrapper').first().hide();
         $('#summary1').parents('div.dataTables_wrapper').first().hide();
         $('#detail').parents('div.dataTables_wrapper').first().hide();

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

                 <div class="col-md-2">
                   <div class="input-group">
                    <label><input type="checkbox" name="all" id="all">All Details</label>

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
                 <div class="col-md-3 total">
                   <h4 class="" >Total Incentive : <i><span id='totalincentive'>RM0.00</span></i></h4>
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

                                <tr id="row_{{$i}}">
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

                        <table id="detail" class="detail" cellspacing="0" width="100%" padding="30px" style="font-size: 13px;">
             <thead>
                                <tr class="search">
                                @foreach($details as $key=>$value)

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
                                    @foreach($details as $key=>$value)

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
                                @foreach($details as $delivery)

                                <tr>
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
    <b>Version</b> 1.0.0
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
      var company = $('#company').val();
      console.log(company)

        window.location.href ="{{ url("/transportcharges") }}/"+arr[0]+"/"+arr[1]+"/"+company;
    }

    function updateincentive(id,value)
    {
      $.ajaxSetup({
           headers: { 'X-CSRF-Token' : $('meta[name=_token]').attr('content') }
          });
          $.ajax({
                  url: "{{ url('/transportcharges/updateincentive') }}" + "/" + id,
                  method: "POST",
                  data: {value:value},
                  success: function(response){
                    if (response==1)
                    {

                    }
                    else
                    {

                    }
                  }
                });
    }


</script>



@endsection
