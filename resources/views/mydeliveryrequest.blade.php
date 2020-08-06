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


    <style type="text/css" class="init">
      a.buttons-collection {
        margin-left: 1em;
      }
      .container1{
              width: 1200px;
              margin-left: 50px;
              padding: 10px;
      }
      #map{
        height: 300px;
        /*width:530px;*/
        margin: 0 auto;
      }
      .calendar{
              float:right;
              padding: 10px;
      }
      #list td:nth-child(1),#list td:nth-child(2),#list td:nth-child(3),#list td:nth-child(5) {
        white-space: nowrap;
      }
      .yellow {
        color: #f39c12;
      }
      .red{
          color:red;
      }
      .pending {
          color: #f39c12;
      }
      .processing {
          color: #00FF00;
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
      div.DTED_Lightbox_Wrapper {
        z-index: 9999 !important;
      }
      .red-star{
        color:red;
      }
      .mapsmodal{
        z-index: 9999999;
      }
    </style>

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
      <script type="text/javascript" language="javascript" src="{{ asset('/plugin/js/dataTables.editor.min.js') }}"></script>

      <script type="text/javascript" language="javascript" class="init">
          var lorryeditor;
          var lorrytable;
          var itemeditor;
          var itemtable;
          var updatelisttable;
          var editlisttable;
          var delivery5editor;
          var deliverytable;
          var delivery2table;
          var delivery3table;
          var delivery4table;
          var delivery5table;
          var delivery6table;
          var delivery7table;
          var delivery8table;
          var delivery9table;
          var delivery10table;
          var mrItemTable,mrTable;
          var asInitVals = new Array();
          var deliveryid;
              var map;
    var gmarkers = Array();
    var locs = [];
    var marker;
    function initMap() {
      @foreach($showdelivery as $show)
      @if($show->latitude=="" || $show->latitude==null)
        var mapOptions = {
          center: new google.maps.LatLng(2.959660,101.785942),
          zoom: 12,
          mapTypeId: google.maps.MapTypeId.ROADMAP
        };
      @else
      var mapOptions = {
          center: new google.maps.LatLng({{$show->latitude}},{{$show->longitude}}),
          zoom: 12,
          mapTypeId: google.maps.MapTypeId.ROADMAP
        };
      @endif
      @endforeach
      map = new google.maps.Map(document.getElementById("map"), mapOptions);
      google.maps.event.addListener(map, 'click', function(e) {
        latInput.value = e.latLng.lat() ;
        longInput.value = e.latLng.lng();
      });
      locs = [
        @foreach($showdelivery as $timesheet)
          [{{$timesheet->latitude}}, {{$timesheet->longitude}}, {{$timesheet->Id}}],
        @endforeach
      ];
        for (var i = 0; i < locs.length; i++) {
        // var id = locs[i][2];

        var marker = new google.maps.Marker({
          position: new google.maps.LatLng(locs[i][0], locs[i][1]),
          map: map,
          icon:"{{ asset('img/truck.png') }}"
        });
        gmarkers.push(marker);
      }
    }
    // google.maps.event.addDomListener(window, 'load', initMap);
    function myfunction(latitude, longitude){
      console.log(map)
      map.setZoom(12);
      map.setCenter(new google.maps.LatLng(latitude, longitude));
      console.log(map)
      marker.setMap(map);
    }
  $(document).ready(function() {

    mrTable=$("#mrTable").dataTable();
    mrItemTable=$("#mrItemTable").dataTable({
      dom:"tp"
    });
    mrTable.on( 'order.dt search.dt', function () {
      mrTable.api().column(0, {search:'applied', order:'applied'}).nodes().each( function (cell, i) {
          cell.innerHTML = i+1;
      } );
    } ).api().draw();
    mrItemTable.on( 'order.dt search.dt', function () {
      mrItemTable.api().column(0, {search:'applied', order:'applied'}).nodes().each( function (cell, i) {
          cell.innerHTML = i+1;
      } );
    } ).api().draw();
                     //  lorryeditor = new $.fn.dataTable.Editor( {
                     //            ajax: "{{ asset('/Include/delivery.php') }}",
                     //             table: "#lorrytable",
                     //             idSrc: "lorry.lorryId",
                     //             fields: [
                     //                     {
                     //                             label: "Lorry Id:",
                     //                             name : "Lorry Id",
                     //                             type : 'readonly'
                     //                     },{
                     //                             label: "Lorry Size:",
                     //                             name: "Lorry_Size",
                     //                             type:  'readonly'
                     //                     },{
                     //                             label: "Plate Number:",
                     //                             name: "Plate_Number",
                     //                             type:  'readonly'
                     //                     }, {
                     //                             label: "Destination:",
                     //                             name: "Destination",
                     //                             type: 'readonly'
                     //                     }, {
                     //                             label: "Status:",
                     //                             name: "Status",
                     //                             type:  'readonly'
                     //                     }
                     //             ]
                     //     } );
                     //     $('#lorrytable').on( 'click', 'button', function () {
                     //       var data = lorrytable.row($(this).parents('tr')).data();
                     //       // lorryid = lorrytable.row( this ).data().lorry.lorryId;
                     //     });
                     //      lorrytable=$('#lorrytable').DataTable( {
                     //       // ajax: {
                     //       //   "url": "{{ asset('/Include/delivery.php') }}",
                     //       //   "data": {
                     //       //       "UserId": {{ $me->UserId }}
                     //       //   }
                     //       // },
                     //           columnDefs: [{ "visible": false,"targets":[1]},{"className": "dt-center", "targets": "_all"}],
                     //        responsive: false,
                     //        //colReorder: true,
                     //        //stateSave:true,
                     //        dom: "Blftp",
                     //        iDisplayLength:100,
                     //        bAutoWidth: true,
                     //        iDisplayLength:10,
                     //        rowId:"lorry.lorryId",
                     //        order: [[ 5, "asc" ]],
                     //        columns: [
                     //        { data: null, "render":"", title: "No"},
                     //        { data: "lorry.lorryId", title: "Id"},
                     //        { data: "roadtax.Lorry_Size", title: "Lorry Size"},
                     //        { data: "roadtax.Vehicle_No", title: "Plate Number" },
                     //        { data: "lorry.Destination", title: "Destination" },
                     //        { data: "lorry.Status", title: "Status" },
                     //        // {
                     //        //   sortable: false,
                     //        //                 "render": function ( data, type, row, meta ) {
                     //        //                     return '<button type="button" class="btn btn-success" btn-sm data-toggle="modal" onclick="SelectDialog()"">Select</button>'
                     //        //                   }
                     //        //                 }
                     //      ],
                     //             select: {
                     //                     style:    'os',
                     //                     selector: 'tr'
                     //                   },
                     //             buttons: [
                     //                      {text:'Refresh',
                     //                      action: function ( e, dt, button, config ) {
                     //                      dt.ajax.reload();
                     //                      },
                     //                    }
                     //             ],
                     // });
                     //      lorrytable.on( 'order.dt search.dt', function () {
                     //      lorrytable.column(0, {search:'applied', order:'applied'}).nodes().each( function (cell, i) {
                     //          cell.innerHTML = i+1;
                     //      } );
                     //      } ).draw();
                            //   lorrytable.button().add( 0, {
                            //     action: function ( e, dt, button, config ) {
                            //         dt.ajax.reload();
                            //     },
                            //     text: 'Reload table'
                            // } );
                            itemeditor = new $.fn.dataTable.Editor( {
                                ajax: "{{ asset('/Include/deliveryitem.php') }}",
                                 table: "#editlisttable",
                                 idSrc: "deliveryitem.Id",
                                 fields: [
                                         {
                                                 label: "formId",
                                                 name: "deliveryitem.formId",
                                                 type: "hidden",
                                         },
                                         // {
                                         //         label: "Id",
                                         //         name: "deliveryitem.Id",
                                         //         type: "hidden"
                                         // },
                                         {
                                                 label: "Item Code:",
                                                 name: "deliveryitem.inventoryId",
                                                 type:  'select2',
                                                 options: [
                                                @foreach($deliveryitems as $di)
                                                    { label :"{{$di->Item_Code}}", value: "{{$di->Id}}"},
                                                @endforeach
                                                ],
                                         },
                                         // {
                                         //         label: "Description:",
                                         //         name: "inventories.Description",
                                         //         type:  'readonly'
                                         // }, {
                                         //         label: "Unit:",
                                         //         name: "Destination",
                                         //         type: 'readonly'
                                         // },
                                         {
                                                 label: "Additional_Description:",
                                                 name: "deliveryitem.add_desc"
                                         },
                                         {
                                                 label: "Request Quantity:",
                                                 name: "deliveryitem.Qty_request",
                                                 type:  'text'
                                         }
                                 ]
                         } );
                         //  $('#editlisttable').on( 'click', 'tr', function () {
                         //   itemid = editlisttable.row( this ).data().deliveryitem.Id;
                         // });
                          editlisttable=$('#editlisttable').DataTable( {
                           ajax: {
                             "url": "{{ asset('/Include/deliveryitem.php') }}",
                             "data": {
                                 "UserId": {{ $me->UserId }}
                             }
                           },
                               columnDefs: [{ "visible": false, "targets":[1]},{"className": "dt-center", "targets": "_all"}],
                            responsive: false,
                            dom: "Bltp",
                            sScrollX: "100%",
                            sScrollY: "100%",
                            scrollCollapse: true,
                            iDisplayLength:100,
                            bAutoWidth: true,
                            iDisplayLength:10,
                            rowId:"deliveryitem.Id",
                            // order: [[ 5, "asc" ]],
                            columns: [
                            { data : null, "render":"", title: "No"},
                            { data: "deliveryitem.Id"},
                            { data: "inventories.Item_Code", editField: "deliveryitem.inventoryId",title: "Name"},
                            { data: "inventories.Description", title: "Description"},
                            { data: "deliveryitem.add_desc", title: "Additional Description"},
                            { data: "inventories.Unit", title:"Unit"},
                            { data: "deliveryitem.Qty_request", title: "Quantity Requested"},
                            { data: "deliveryitem.available", title: "Available",
                              "render": function ( data, type, full, meta ) {
                                                if(full.deliveryitem.available==1)
                                                {
                                                  return '<p style="color:green;">Available</p>';
                                                }
                                                else if (full.deliveryitem.available==0)
                                                {
                                                  return '<p style="color:red;">Insufficient</p>';
                                                }
                                                else
                                                {
                                                  return '';
                                                }
                                               }
                            },
                            {
                                data: null,
                                title:"Action",
                                className: "center",
                                defaultContent: '<button type="button" class="itemeditor_remove">Delete</button>'
                            }
                    ],
                                 select: true,
                                 buttons: [
                                 {
                                 text: 'New Row',
                                 action: function ( e, dt, node, config ) {
                                  // clearing all select/input options
                                  itemeditor
                                     .create( false )
                                     .set('deliveryitem.Qty_request',1)
                                     .set('deliveryitem.inventoryId',1)
                                     .submit();
                                  },
                                  },
                                  // { extend: "remove", editor: itemeditor },
                                 ],
                     });
                          // Delete a record
                          $('#editlisttable').on('click', 'button.itemeditor_remove', function (e) {
                              e.preventDefault();
                              itemeditor.remove( $(this).closest('tr'), {
                                  title: 'Delete record',
                                  message: 'Are you sure you wish to remove this record?',
                                  buttons: 'Delete'
                              } );
                          } );
                          editlisttable.on( 'order.dt search.dt', function () {
                          editlisttable.column(0, {search:'applied', order:'applied'}).nodes().each( function (cell, i) {
                              cell.innerHTML = i+1;
                          } );
                          } ).draw();
                // }
                         delivery4editor = new $.fn.dataTable.Editor( {
                                ajax: "{{ asset('/Include/delivery.php') }}",
                                 table: "#delivery4table",
                                 idSrc: "deliveryform.Id",
                                 fields: [
                                        // {
                                        //          label: "Delivery Form Id",
                                        //          name : "deliveryform.Id",
                                        //          type : "readonly"
                                        // },
                                        {
                                                label: "Date:",
                                                name: "deliveryform.delivery_date",
                                                type:  'datetime',
                                                def:    function () { return new Date(); },
                                                format: 'DD-MMM-YYYY'
                                        },{
                                                label: "Time:",
                                                name: "deliveryform.delivery_time",
                                                type:  'datetime',
                                                format: 'HH:mm:ss'
                                        },
                                        {
                                                label: "Pickup Date:",
                                                name: "deliveryform.pickup_date",
                                                type:  'datetime',
                                                def:    function () { return new Date(); },
                                                format: 'DD-MMM-YYYY'
                                        },{
                                                label: "Pickup Time:",
                                                name: "deliveryform.pick_up_time",
                                                type:  'datetime',
                                                format: 'HH:mm:ss'
                                        },
                                        {
                                                 label: "Vehicle No:",
                                                 name: "deliveryform.roadtaxId",
                                                 type:  'select2',
                                                 options: [
                                                { label :"Self-Collect", value: "0" },
                                                @foreach($lorry as $lorries)
                                                    { label :"{{$lorries->Vehicle_No}}", value: "{{$lorries->Id}}" },
                                                @endforeach
                                                @foreach($truck as $t)
                                                    { label :"TRUCK - {{$t->Vehicle_No}}", value: "{{$t->Id}}" },
                                                @endforeach
                                                ],
                                        },
                                        // {
                                        //          label: "Driver:",
                                        //          name: "deliveryform.DriverId",
                                        //          type:  'textarea'
                                        // },
                                        {
                                                 label: "Project Name",
                                                 name : "deliveryform.ProjectId",
                                                 type : "select2",
                                                 options: [
                                                { label :"", value: "" },
                                                @foreach($project as $projects)
                                                    { label :"{{$projects->Project_Name}}", value: "{{$projects->Id}}" },
                                                @endforeach
                                                ],
                                         },{
                                                 label: "Site:",
                                                 name: "deliveryform.Location",
                                                 type:  'select2',
                                                 options: [
                                                { label :"", value: "" },
                                                @foreach($destination as $key)
                                                    { label :"{{$key->Location_Name}}", value: "{{$key->Id}}" },
                                                @endforeach
                                                ],
                                         },
                                         {
                                                 label: "PIC Name:",
                                                 name: "deliveryform.PIC_Name"
                                                 // type:  'select2',
                                                 // options: [
                                                 //  { label :"", value: "" },
                                                 //  @foreach($pic as $key)
                                                 //    @if($key->Person_In_Charge != "")
                                                 //      { label :"{{$key->Person_In_Charge}}", value: "{{$key->Person_In_Charge}}" },
                                                 //    @endif
                                                 //  @endforeach
                                                 //  ],
                                         },{
                                                 label: "PIC Contact:",
                                                 name: "deliveryform.PIC_Contact"
                                                //  type:  'select2',
                                                //  options: [
                                                // { label :"", value: "" },
                                                // @foreach($pic as $key)
                                                //     @if($key->Contact_No != "")
                                                //     { label :"{{$key->Contact_No}}", value: "{{$key->Contact_No}}" },
                                                //     @endif
                                                // @endforeach
                                                // ],
                                         },{
                                                 label: "Remarks:",
                                                 name: "deliveryform.Remarks"
                                         }
                                 ]
                         } );
                     //     $('#deliverytable').on( 'click', 'tr', function () {
                     //      // var data = deliverytable.row($(this).parents('tr')).data();
                     //       var deliveryid = deliverytable.row( this ).data().deliveryform.Id;
                     //     // console.log(deliveryid)
                     //     });
                     //        deliverytable=$('#deliverytable').DataTable( {
                     //       ajax: {
                     //         "url": "{{ asset('/Include/delivery.php') }}",
                     //         "data": {
                     //             "UserId": {{ $me->UserId }},
                     //             "Status": "%Pending%"
                     //         }
                     //       },
                     //             columnDefs: [{ "visible": false,"targets":[1,2,3]},{"className": "dt-center", "targets": "_all"}],
                     //             responsive: false,
                     //             colReorder: false,
                     //             dom: "Blftp",
                     //             sScrollX: "100%",
                     //             bAutoWidth: true,
                     //             sScrollY: "100%",
                     //             rowId:"deliveryform.Id",
                     //             scrollCollapse: true,
                     //             // aaSorting:false,
                     //             fnInitComplete: function(oSettings, json) {
                     //               $('#mypendingdeliverytab').html("Pending Delivery" + "[" + deliverytable.rows().count() +"]")
                     //              },
                     //             columns: [
                     //                     { data: null, "render":"", title:"No"},
                     //                     { data: "deliveryform.Id"},
                     //                     { data: "deliverystatuses.Id"},
                     //                     { data: "requestor.Name", title:"Requestor"},
                     //                     { data: "roadtax.Vehicle_No",title:"Vehicle No"},
                     //                     { data: "roadtax.Lorry_Size",title:"Size"},
                     //                     // { data: "driver.Name",title:"Driver"},
                     //                     { data: "deliveryform.delivery_date",title:"Delivery Date"},
                     //                     { data: "deliveryform.delivery_time",title:"Delivery Time"},
                     //                     { data: "deliveryform.pickup_date",title:"Pickup Date"},
                     //                     { data: "deliveryform.pick_up_time",title:"Pickup Time"},
                     //                     { data: "radius.Location_Name",title:"Site",editField:"deliveryform.Location"},
                     //                     { data: "projects.Project_Name", title:"Project Name"},
                     //                     // { data: "company.Company_Name", title:"Company"},
                     //                     // { data: "client.Company_Name", title:"Client"},
                     //                     // { data: "deliveryform.VisitStatus", title:"Visit Status"},
                     //                     { data: "options.Option", title:"Purpose"},
                     //                     { data: "deliveryform.PIC_Name", title:"PIC Name"},
                     //                     { data: "deliveryform.PIC_Contact", title:"PIC Contact"},
                     //                     { data: "deliveryform.Remarks", title:"Remarks"},
                     //                     { data: "deliveryform.created_at", title:"Request Date"},
                     //                     {
                     //                        sortable: false,
                     //                        title:"Items",
                     //                        // title:"View Items",
                     //                        "render": function ( data, type, full, meta ) {
                     //                            return '<button data-toggle="modal" data-target="#viewItemListModal" onclick="viewItemList('+full.deliveryform.Id+')">View Items</button>';
                     //                           }
                     //                    },
                     //                    {
                     //                        sortable: false,
                     //                        title:"Action",
                     //                        // title:"View Items",
                     //                        "render": function ( data, type, full, meta ) {
                     //                            return '<button onclick="OpenRecallDialog('+full.deliveryform.Id+')">Recall</button><br><br><button onclick="CancelDialog('+full.deliveryform.Id+')">Cancel</button><br><br><a href="/deliverytrackingdetails/'+full.deliveryform.Id+'"><button>Track</button></a><br><br>'+
                     //                            "<a target='_blank' href='{{url('materiallist')}}/"+full.deliveryform.Id+"'><button>Print</button></a>";
                     //                           }
                     //                    },
                     //                      ],
                     //             select: {
                     //                     style:    'os',
                     //                     selector: 'tr'
                     //                   },
                     //                   buttons:[],
                     // });
                     //    deliverytable.on( 'order.dt search.dt', function () {
                     //      deliverytable.column(0, {search:'applied', order:'applied'}).nodes().each( function (cell, i) {
                     //          cell.innerHTML = i+1;
                     //      } );
                     //      } ).draw();
                        // Table 2
                        $('#delivery2table').on( 'click', 'tr', function () {
                          // var data = delivery2table.row($(this).parents('tr')).data();
                           deliveryid = delivery2table.row( this ).data().deliveryform.Id;
                         });
                            delivery2table=$('#delivery2table').DataTable( {
                           ajax: {
                             "url": "{{ asset('/Include/delivery.php') }}",
                             "data": {
                                 "UserId": {{ $me->UserId }},
                                 "Status": "%Processing%"
                             }
                           },
                                 columnDefs: [{ "visible": false, "targets" :[1,2,3]},{"className": "dt-center", "targets": "_all"}],
                                 responsive: false,
                                 colReorder: false,
                                 dom: "Blftp",
                                 sScrollX: "100%",
                                 bAutoWidth: true,
                                 sScrollY: "100%",
                                 rowId:"deliveryform.Id",
                                 scrollCollapse: true,
                                 // aaSorting:false,
                                 fnInitComplete: function(oSettings, json) {
                                   $('#myprocessingdeliverytab').html("Processing Delivery" + "[" + delivery2table.rows().count() +"]")
                                  },
                                 columns: [
                                         { data: null, "render":"", title:"No"},
                                         { data: "deliveryform.Id"},
                                         { data: "deliverystatuses.Id"},
                                         { data: "requestor.Name", title:"Requestor"},
                                         { data: "deliveryform.DO_No", title:"DO Number"},
                                         { data: "roadtax.Vehicle_No",title:"Vehicle No"},
                                         { data: "roadtax.Lorry_Size",title:"Size"},
                                         { data: "driver.Name",title:"Driver"},
                                         { data: "deliveryform.delivery_date",title:"Delivery Date"},
                                         { data: "deliveryform.delivery_time",title:"Delivery Time"},
                                         { data: "deliveryform.pickup_date",title:"Pickup Date"},
                                         { data: "deliveryform.pick_up_time",title:"Pickup Time"},
                                         { data: "radius.Location_Name",title:"Site",editField:"deliveryform.Location"},
                                         { data: "projects.Project_Name", title:"Project Name"},
                                         // { data: "company.Company_Name", title:"Company"},
                                         // { data: "client.Company_Name", title:"Client"},
                                         // { data: "deliveryform.VisitStatus", title:"Visit Status"},
                                         { data: "options.Option", title:"Purpose" },
                                         { data: "deliveryform.PIC_Name", title:"PIC Name"},
                                         { data: "deliveryform.PIC_Contact", title:"PIC Contact"},
                                         { data: "deliveryform.Remarks", title:"Remarks"},
                                         { data: "deliveryform.created_at", title:"Request Date"},
                                         {
                                            sortable: false,
                                            title:"Items",
                                            // title:"View Items",
                                            "render": function ( data, type, full, meta ) {
                                                return '<button data-toggle="modal" data-target="#viewItemListModal" onclick="viewItemList('+full.deliveryform.Id+')">View Items</button>';
                                               }
                                        },
                                        {
                                            sortable: false,
                                            title:"Action",
                                            // title:"View Items",
                                            "render": function ( data, type, full, meta ) {
                                                // return '<button onclick="CancelDialog('+full.deliveryform.Id+')">Cancel</button>'
                                                if(full.options.Option == "Delivery")
                                                {
                                                return '<button onclick="OpenRecallDialog('+full.deliveryform.Id+')">Recall</button><br><br><button onclick="CancelDialog('+full.deliveryform.Id+')">Cancel</button><br><br><a href="/deliverytrackingdetails/'+full.deliveryform.Id+'"><button>Track</button></a><br><br><a target="_blank" href="{{url("deliveryorder")}}/'+full.deliveryform.Id+'"><button>Print DO</button><a>';
                                                }
                                                else
                                                {
                                                  return '<button onclick="OpenRecallDialog('+full.deliveryform.Id+')">Recall</button><br><br><button onclick="CancelDialog('+full.deliveryform.Id+')">Cancel</button><br><br><a href="/deliverytrackingdetails/'+full.deliveryform.Id+'"><button>Track</button></a><br><br><a target="_blank" href="{{url("returnnote")}}/'+full.deliveryform.Id+'"><button>Print DO</button><a>';
                                                }
                                               }
                                        },
                                          ],
                                 select: {
                                         style:    'os',
                                         selector: 'tr'
                                       },
                                       buttons:[],
                     });
                        delivery2table.on( 'order.dt search.dt', function () {
                          delivery2table.column(0, {search:'applied', order:'applied'}).nodes().each( function (cell, i) {
                              cell.innerHTML = i+1;
                          } );
                          } ).draw();
                        // Table 3
                        $('#delivery3table').on( 'click', 'tr', function () {
                          // var data = delivery3table.row($(this).parents('tr')).data();
                           deliveryid = delivery3table.row( this ).data().deliveryform.Id;
                         });
                            delivery3table=$('#delivery3table').DataTable( {
                           ajax: {
                             "url": "{{ asset('/Include/delivery.php') }}",
                             "data": {
                                 "UserId": {{ $me->UserId }},
                                 "Status": "%Accepted%"
                             }
                           },
                                 columnDefs: [{ "visible": false, "targets" :[1,2,3]},{"className": "dt-center", "targets": "_all"}],
                                 responsive: false,
                                 colReorder: false,
                                 dom: "Blftp",
                                 sScrollX: "100%",
                                 bAutoWidth: true,
                                 sScrollY: "100%",
                                 rowId:"deliveryform.Id",
                                 scrollCollapse: true,
                                 // aaSorting:false,
                                 fnInitComplete: function(oSettings, json) {
                                   $('#myaccepteddeliverytab').html("Accepted Delivery" + "[" + delivery3table.rows().count() +"]")
                                  },
                                 columns: [
                                         { data: null, "render":"", title:"No"},
                                         { data: "deliveryform.Id"},
                                         { data: "deliverystatuses.Id"},
                                         { data: "requestor.Name", title:"Requestor"},
                                         { data: "deliveryform.DO_No", title:"DO Number"},
                                         { data: "roadtax.Vehicle_No",title:"Vehicle No"},
                                         { data: "roadtax.Lorry_Size",title:"Size"},
                                         { data: "driver.Name",title:"Driver"},
                                         { data: "deliveryform.delivery_date",title:"Delivery Date"},
                                         { data: "deliveryform.delivery_time",title:"Delivery Time"},
                                         { data: "deliveryform.pickup_date",title:"Pickup Date"},
                                         { data: "deliveryform.pick_up_time",title:"Pickup Time"},
                                         { data: "radius.Location_Name",title:"Site",editField:"deliveryform.Location"},
                                         { data: "projects.Project_Name", title:"Project Name"},
                                         // { data: "company.Company_Name", title:"Company"},
                                         // { data: "client.Company_Name", title:"Client"},
                                         // { data: "deliveryform.VisitStatus", title:"Visit Status"},
                                         { data: "options.Option", title:"Purpose" },
                                         { data: "deliveryform.PIC_Name", title:"PIC Name"},
                                         { data: "deliveryform.PIC_Contact", title:"PIC Contact"},
                                         { data: "deliveryform.Remarks", title:"Remarks"},
                                         { data: "deliveryform.created_at", title:"Request Date"},
                                         {
                                            sortable: false,
                                            title:"Items",
                                            // title:"View Items",
                                            "render": function ( data, type, full, meta ) {
                                                return '<button data-toggle="modal" data-target="#viewItemListModal" onclick="viewItemList('+full.deliveryform.Id+')">View Items</button>';
                                               }
                                        },
                                        {
                                            sortable: false,
                                            title:"Action",
                                            // title:"View Items",
                                            "render": function ( data, type, full, meta ) {
                                                if(full.deliverystatuses.delivery_status == "Accepted" && full.options.Option == "Delivery")
                                                {
                                                  return '<button onclick="OpenRecallDialog('+full.deliveryform.Id+')">Recall</button><br><br><button onclick="CancelDialog('+full.deliveryform.Id+')">Cancel</button><br><br><a href="/deliverytrackingdetails/'+full.deliveryform.Id+'"><button>Track</button></a><br><br><a target="_blank" href="/deliveryorder/'+full.deliveryform.Id+'"><button>Print</button></a>';
                                                }
                                                else if(full.deliverystatuses.delivery_status == "Accepted" && full.options.Option == "Collection")
                                                {
                                                  return '<button onclick="OpenRecallDialog('+full.deliveryform.Id+')">Recall</button><br><br><button onclick="CancelDialog('+full.deliveryform.Id+')">Cancel</button><br><br><a href="/deliverytrackingdetails/'+full.deliveryform.Id+'"><button>Track</button></a><br><br><a target="_blank" href="/returnnote/'+full.deliveryform.Id+'"><button>Print</button></a>';
                                                }
                                                else
                                                {
                                                  return '<button onclick="OpenRecallDialog('+full.deliveryform.Id+')">Recall</button><br><br><button onclick="CancelDialog('+full.deliveryform.Id+')">Cancel</button><br><br><a href="/deliverytrackingdetails/'+full.deliveryform.Id+'"><button>Track</button></a>';
                                                }
                                               }
                                        },
                                          ],
                                 select: {
                                         style:    'os',
                                         selector: 'tr'
                                       },
                                       buttons:[],
                     });
                        delivery3table.on( 'order.dt search.dt', function () {
                          delivery3table.column(0, {search:'applied', order:'applied'}).nodes().each( function (cell, i) {
                              cell.innerHTML = i+1;
                          } );
                          } ).draw();
                        // Table 4
                           $('#delivery4table').on( 'click', 'tbody td', function (e) {
                        delivery4editor.inline( this, {
                          onBlur: 'submit'
                        } );
                      } );
                      // delivery4editor.inline( $('#delivery4table tbody tr:first-child td:first-child') );
                        $('#delivery4table').on( 'click', 'tr', function () {
                          // var data = delivery4table.row($(this).parents('tr')).data();
                           deliveryid = delivery4table.row( this ).data().deliveryform.Id;
                         });
                            delivery4table=$('#delivery4table').DataTable( {
                           ajax: {
                             "url": "{{ asset('/Include/delivery.php') }}",
                             "data": {
                                 "UserId": {{ $me->UserId }},
                                 "Status": "%Recalled%"
                             }
                           },
                                 columnDefs: [{ "visible": false, "targets" :[1,2,3]},{"className": "dt-center", "targets": "_all"}],
                                 responsive: false,
                                 colReorder: false,
                                 dom: "Blftp",
                                 sScrollX: "100%",
                                 bAutoWidth: true,
                                 sScrollY: "100%",
                                 rowId:"deliveryform.Id",
                                 scrollCollapse: true,
                                 // aaSorting:false,
                                 fnInitComplete: function(oSettings, json) {
                                   $('#myrecalleddeliverytab').html("Recalled Delivery" + "[" + delivery4table.rows().count() +"]")
                                  },
                                 columns: [
                                        { data: null, "render":"", title:"No"},
                                         { data: "deliveryform.Id"},
                                         { data: "deliverystatuses.Id"},
                                         { data: "requestor.Name", title:"Requestor"},
                                         { data: "deliveryform.DO_No",title:"Do Number"},
                                         { data: "roadtax.Vehicle_No",title:"Vehicle No",editField:"deliveryform.roadtaxId"},
                                         { data: "roadtax.Lorry_Size",title:"Size"},
                                         { data: "driver.Name",title:"Driver"},
                                         { data: "deliveryform.delivery_date",title:"Delivery Date"},
                                         { data: "deliveryform.delivery_time",title:"Delivery Time"},
                                         { data: "deliveryform.pickup_date",title:"Pickup Date"},
                                         { data: "deliveryform.pick_up_time",title:"Pickup Time"},
                                         { data: "radius.Location_Name",title:"Site",editField:"deliveryform.Location"},
                                         { data: "projects.Project_Name", title:"Project Name",editField:"deliveryform.ProjectId"},
                                         // { data: "company.Company_Name", title:"Company",editField:"deliveryform.company_id"},
                                         // { data: "deliveryform.VisitStatus", title:"Visit Status"},
                                         { data:"options.Option", title:"Purpose"},
                                         { data: "deliveryform.PIC_Name", title:"PIC Name"},
                                         { data: "deliveryform.PIC_Contact", title:"PIC Contact"},
                                         { data: "deliveryform.Remarks", title:"Remarks"},
                                         { data:"deliverystatuses.updated_at", title:"Recall Date"},
                                         {
                                            sortable: false,
                                            title:"Items",
                                            // title:"View Items",
                                            "render": function ( data, type, full, meta ) {
                                                return '<button data-toggle="modal" data-target="#editItemListModal" onclick="editItemList('+full.deliveryform.Id+')">Edit Items</button>';
                                               }
                                        },
                                        {
                                            sortable: false,
                                            title:"Action",
                                            // title:"View Items",
                                            "render": function ( data, type, full, meta ) {
                                                return '<button onclick="ResubmitDialog('+full.deliveryform.Id+')">Resubmit</button><br><br><a href="/deliverytrackingdetails/'+full.deliveryform.Id+'"><button>Track</button></a>';
                                               }
                                        },
                                          ],
                                 select: {
                                         style:    'os',
                                         selector: 'tr'
                                       },
                                       buttons:[],
                     });
                        delivery4table.on( 'order.dt search.dt', function () {
                          delivery4table.column(0, {search:'applied', order:'applied'}).nodes().each( function (cell, i) {
                              cell.innerHTML = i+1;
                          } );
                          } ).draw();
                              delivery5editor = new $.fn.dataTable.Editor( {
                                ajax: "{{ asset('/Include/deliveryitem.php') }}",
                                 table: "#updatelisttable",
                                 idSrc: "deliveryitem.Id",
                                 fields: [
                                         {
                                                 label: "status",
                                                 name: "deliveryitem.status",
                                                 type: "hidden",
                                         },
                                         {
                                                 label: "formId",
                                                 name: "deliveryitem.formId",
                                                 type: "hidden",
                                         },{
                                                 label: "Item Code:",
                                                 name: "deliveryitem.inventoryId",
                                                 type:  'select2',
                                                 options: [
                                                @foreach($deliveryitems as $dis)
                                                    { label :"{{$dis->Item_Code}}", value:"{{$dis->Id}}"},
                                                @endforeach
                                                ],
                                         },{
                                                 label: "Request Quantity:",
                                                 name: "deliveryitem.Qty_request",
                                                 type:  'text'
                                         },{
                                                 label: "Sent Quantity:",
                                                 name: "deliveryitem.Qty_send",
                                                 type:  'text'
                                         },{
                                                 label: "Actual Quantity:",
                                                 name: "deliveryitem.Qty_received",
                                                 type:  'text'
                                         },{
                                                 label: "Remarks:",
                                                 name: "deliveryitem.remarks",
                                                 type:  'text'
                                         },{
                                                 label: "File:",
                                                 name: "files.Web_Path",
                                                 type: "upload",
                                                 ajaxData: function ( d ) {
                                                   d.append( 'Id', deliveryid ); // edit - use `d`
                                                 },
                                                 display: function ( file_id ) {
                                                   return '<img src="'+ delivery5table.row( delivery5editor.modifier() ).data().files.Web_Path +'">';
                                                 },
                                                 clearText: "Clear",
                                                 noImageText: 'No file'
                                         }
                                 ]
                         } );
                          $(document).ready(function(){
                          $("#updateitem").click(function(){
                              if(! $.fn.DataTable.isDataTable( '#updatelisttable' )){
                                  showTable();
                              }
                          });
                          });
                         //  $('#updatelisttable').on( 'click', 'tr', function () {
                         //   itemid = updatelisttable.row( this ).data().deliveryitem.Id;
                         // });
                          $('#updatelisttable').on( 'click', 'tbody td', function (e) {
                            var currentRow = updatelisttable.row(this).data();
                            var colIndex = updatelisttable.cell(this).index().column;
                            if  (currentRow.deliveryitem.status==0){
                              if(dtCols[colIndex].data == 'deliveryitem.Qty_send'){
                                if (currentRow.deliveryitem.Qty_send){
                                  delivery5editor.inline( this, {
                                    onBlur: 'submit'
                                  } );
                                }
                              }
                              else if(dtCols[colIndex].data == 'deliveryitem.remarks'){
                                if(currentRow.deliveryitem.remarks){
                                  delivery5editor.inline( this, {
                                    onBlur: 'submit'
                                  } );
                                }
                              }
                              else if(dtCols[colIndex].data == 'deliveryitem.Qty_received'){
                                if(currentRow.deliveryitem.Qty_received){
                                  delivery5editor.inline( this, {
                                    onBlur: 'submit'
                                  } );
                                }
                              }
                            }
                            else{
                                delivery5editor.inline( this, {
                                  onBlur: 'submit'
                                } );
                             }
                            // delivery5editor.inline( thi s, {
                            //   // submit: 'allIfChanged'
                            //   onBlur: 'submit'
                            } );
                          var dtCols = [
                            { data : null, "render":"", title: "No"},
                            { data: "deliveryitem.status", title:"Status"},
                            { data: "deliveryitem.Id"},
                            { data: "inventories.Item_Code", editField:"deliveryitem.inventoryId",title: "Name"},
                            { data: "inventories.Description", title: "Description"},
                            { data: "deliveryitem.add_desc", title: "Additional Description"},
                            { data: "inventories.Unit", title:"Unit"},
                            { data: "deliveryitem.Qty_request", title: "Quantity Requested"},
                            { data: "deliveryitem.Qty_send", title: "Quantity Sent"},
                            { data: "deliveryitem.Qty_received", title: "Actual Quantity"},
                            { data: "deliveryitem.remarks", title: "Remarks"},
                            {
                                data: null,
                                title: "Action",
                                className: "center",
                                defaultContent: '<a href="" class="delivery5editor_remove">Delete</a>'
                            },
                          ]
                          updatelisttable=$('#updatelisttable').DataTable( {
                           ajax: {
                             "url": "{{ asset('/Include/deliveryitem.php') }}",
                             "data": {
                                 "UserId": {{ $me->UserId }}
                             }
                           },
                               columnDefs: [{ "visible": false, "targets":[1,2]},{"className": "dt-center", "targets": "_all"}],
                            responsive: false,
                            dom: "Bltp",
                            iDisplayLength:100,
                            bAutoWidth: true,
                            iDisplayLength:10,
                            rowId:"deliveryitem.Id",
                            // order: [[ 5, "asc" ]],
                            columns: dtCols,
                                 select: true,
                                 buttons: [
                                 {
                                 text: 'New Row',
                                 action: function ( e, dt, node, config ) {
                                  // clearing all select/input options
                                  delivery5editor
                                     .create( false )
                                     .set('deliveryitem.status',1)
                                     .submit();
                                  },
                                  },
                                  // { extend: "create", editor: delivery5editor },
                                 ],
                     });
                          // Delete a record
                          $('#updatelisttable').on('click', 'a.delivery5editor_remove', function (e) {
                              e.preventDefault();
                              delivery5editor.remove( $(this).closest('tr'), {
                                  title: 'Delete record',
                                  message: 'Are you sure you wish to remove this record?',
                                  buttons: 'Delete'
                              } );
                          } );
                          updatelisttable.on( 'order.dt search.dt', function () {
                          updatelisttable.column(0, {search:'applied', order:'applied'}).nodes().each( function (cell, i) {
                              cell.innerHTML = i+1;
                          } );
                          } ).draw();
                        // Table 5
                        $('#delivery5table').on( 'click', 'tr', function () {
                          // var data = delivery5table.row($(this).parents('tr')).data();
                           deliveryid = delivery5table.row( this ).data().deliveryform.Id;
                         });
                            delivery5table=$('#delivery5table').DataTable( {
                           ajax: {
                             "url": "{{ asset('/Include/delivery.php') }}",
                             "data": {
                                 "UserId": {{ $me->UserId }},
                                 "Status": "%Completed%"
                             }
                           },
                                 columnDefs: [{ "visible": false, "targets" :[1,2,3]},{"className": "dt-center", "targets": "_all"}],
                                 responsive: false,
                                 colReorder: false,
                                 dom: "Blftp",
                                 sScrollX: "100%",
                                 bAutoWidth: true,
                                 sScrollY: "100%",
                                 rowId:"deliveryform.Id",
                                 scrollCollapse: true,
                                 // aaSorting:false,
                                 fnInitComplete: function(oSettings, json) {
                                   $('#mycompleteddeliverytab').html("Completed Delivery" + "[" + delivery5table.rows().count() +"]")
                                  },
                                 columns: [
                                        { data: null, "render":"", title:"No"},
                                         { data: "deliveryform.Id"},
                                         { data: "deliverystatuses.Id"},
                                         { data: "requestor.Name", title:"Requestor"},
                                         { data: null, title:"DO Number",render:function(data){
                                           if(data.deliverystatuses.delivery_status_details == "-"){
                                              return data.deliveryform.DO_No + " (Special Delivery)"
                                           }else return data.deliveryform.DO_No;
                                         }},
                                         { data: "roadtax.Vehicle_No",title:"Vehicle No"},
                                         { data: "roadtax.Lorry_Size",title:"Size"},
                                         { data: "driver.Name",title:"Driver"},
                                         { data: "deliveryform.delivery_date",title:"Delivery Date"},
                                         { data: "deliveryform.delivery_time",title:"Delivery Time"},
                                         { data: "radius.Location_Name",title:"Site",editField:"deliveryform.Location"},
                                         { data: "projects.Project_Name", title:"Project Name"},
                                         // { data: "company.Company_Name", title:"Company"},
                                         // { data: "client.Company_Name", title:"Client"},
                                         // { data: "deliveryform.VisitStatus", title:"Visit Status"},
                                         { data: "deliveryform.PIC_Name", title:"PIC Name"},
                                         { data: "deliveryform.PIC_Contact", title:"PIC Contact"},
                                         { data: "deliveryform.Remarks", title:"Remarks"},
                                         // { data: "approver.Name",title:"Approver"},
                                         { data: "deliveryform.created_at", title:"Request Date"},
                                         {
                                            sortable: false,
                                            title:"Items",
                                            // title:"View Items",
                                            "render": function ( data, type, full, meta ) {
                                                return '<button data-toggle="modal" data-target="#viewItemListModal" onclick="viewItemList('+full.deliveryform.Id+')">View Items</button>';
                                               }
                                        },
                                        {
                                            sortable: false,
                                            title:"Action",
                                            // title:"View Items",
                                            "render": function ( data, type, full, meta ) {
                                                return '<button data-toggle="modal" data-target="#updateItemListModal" onclick="updateItemList('+full.deliveryform.Id+')">Update Items</button><br><br><a href="/deliverytrackingdetails/'+full.deliveryform.Id+'"><button>Track</button></a>\
                                                <br><br><a target="_blank" href="{{url("deliveryorder")}}/'+full.deliveryform.Id+'"><button>Print DO</button><a>';
                                               }
                                        },
                                        {
                                        data: null,
                                        title: "File",
                                        className: "center",
                                        "render": function ( data, type, full, meta ) {
                                            return '<form enctype="multipart/form-data" id="uploadphoto'+full.deliverystatuses.Id+'" role="form" method="POST" action="{{ url("mydeliveryrequest/upload") }}">{{ csrf_field() }}<input name="Id" type="hidden" value="'+ full.deliverystatuses.Id +'"><input type="file" id="attachment[]" name="attachment[]" accept=".png,.jpg,.jpeg,.pdf" multiple><br><button type="submit" onclick="upload(\'#uploadphoto'+full.deliverystatuses.Id+'\', '+full.deliverystatuses.Id+')">Upload</button></form>'
                                        }
                                        },
                                        { data: "files.Web_Path", title: 'Files',
                                            "render": function ( data, type, full, meta ) {
                                              var files = getRowFiles(full.deliveryform.Id);
                                              if (files && files.length > 0) {
                                                var display = "";
                                                for(var i =0; i < files.length; i++) {
                                                  display += '<a href="'+ files[i].Web_Path +'" target="_blank">View</a> | <a href="#" onclick="removefile('+files[i].Id+','+files[i].TargetId+')">Remove</a><br>';
                                                }
                                                return display + '<br>' +'<a href="/deliverydetails2/'+full.deliveryform.Id+'" class="btn btn-info btn-sm">Final Approve</a>';
                                              }
                                              return '-';
                                            }
                                            // render: function ( url, type, row ) {
                                            //      if (url)
                                            //      {
                                            //        return '<a href="'+ url +'" target="_blank">Download</a>';
                                            //      }
                                            //      else {
                                            //        return ' - ';
                                            //      }
                                            //  },
                                            //  title: "File"
                                           },
                                          ],
                                 select: {
                                         style:    'os',
                                         selector: 'tr'
                                       },
                                       buttons:[],
                     });
                        delivery5table.on( 'order.dt search.dt', function () {
                          delivery5table.column(0, {search:'applied', order:'applied'}).nodes().each( function (cell, i) {
                              cell.innerHTML = i+1;
                          } );
                          } ).draw();
                        // Table 10
                        $('#delivery10table').on( 'click', 'tr', function () {
                          // var data = delivery5table.row($(this).parents('tr')).data();
                           deliveryid = delivery10table.row( this ).data().deliveryform.Id;
                         });
                            delivery10table=$('#delivery10table').DataTable( {
                           ajax: {
                             "url": "{{ asset('/Include/delivery.php') }}",
                             "data": {
                                 "UserId": {{ $me->UserId }},
                                 "Status": "%Incomplete%"
                             }
                           },
                                 columnDefs: [{ "visible": false, "targets" :[1,2,3]},{"className": "dt-center", "targets": "_all"}],
                                 responsive: false,
                                 colReorder: false,
                                 dom: "Blftp",
                                 sScrollX: "100%",
                                 bAutoWidth: true,
                                 sScrollY: "100%",
                                 rowId:"deliveryform.Id",
                                 scrollCollapse: true,
                                 // aaSorting:false,
                                 fnInitComplete: function(oSettings, json) {
                                   $('#myincompleteddeliverytab').html("Incomplete Delivery" + "[" + delivery10table.rows().count() +"]")
                                  },
                                 columns: [
                                        { data: null, "render":"", title:"No"},
                                         { data: "deliveryform.Id"},
                                         { data: "deliverystatuses.Id"},
                                         { data: "requestor.Name", title:"Requestor"},
                                         { data: "deliveryform.DO_No", title:"DO Number"},
                                         { data: "roadtax.Vehicle_No",title:"Vehicle No"},
                                         { data: "roadtax.Lorry_Size",title:"Size"},
                                         { data: "driver.Name",title:"Driver"},
                                         { data: "deliveryform.delivery_date",title:"Delivery Date"},
                                         { data: "deliveryform.delivery_time",title:"Delivery Time"},
                                         { data: "radius.Location_Name",title:"Site",editField:"deliveryform.Location"},
                                         { data: "projects.Project_Name", title:"Project Name"},
                                         // { data: "company.Company_Name", title:"Company"},
                                         // { data: "client.Company_Name", title:"Client"},
                                         // { data: "deliveryform.VisitStatus", title:"Visit Status"},
                                         { data: "deliveryform.PIC_Name", title:"PIC Name"},
                                         { data: "deliveryform.PIC_Contact", title:"PIC Contact"},
                                         { data: "deliveryform.Remarks", title:"Remarks"},
                                         // { data: "approver.Name",title:"Approver"},
                                         { data: "deliveryform.created_at", title:"Request Date"},
                                         { data: "deliverystatuses.remarks", title:"Reason"},
                                         {
                                            sortable: false,
                                            title:"Items",
                                            // title:"View Items",
                                            "render": function ( data, type, full, meta ) {
                                                return '<button data-toggle="modal" data-target="#viewItemListModal" onclick="viewItemList('+full.deliveryform.Id+')">View Items</button>';
                                               }
                                        },
                                          ],
                                 select: {
                                         style:    'os',
                                         selector: 'tr'
                                       },
                                       buttons:[],
                     });
                        delivery10table.on( 'order.dt search.dt', function () {
                          delivery10table.column(0, {search:'applied', order:'applied'}).nodes().each( function (cell, i) {
                              cell.innerHTML = i+1;
                          } );
                          } ).draw();
                        //Table 6
                         $('#delivery6table').on( 'click', 'tr', function () {
                          // var data = delivery2table.row($(this).parents('tr')).data();
                           deliveryid = delivery6table.row( this ).data().deliveryform.Id;
                         });
                            delivery6table=$('#delivery6table').DataTable( {
                           ajax: {
                             "url": "{{ asset('/Include/delivery.php') }}",
                             "data": {
                                 "UserId": {{ $me->UserId }},
                                 "Status": "%Rejected%"
                             }
                           },
                                 columnDefs: [{ "visible": false, "targets" :[1,2,3]},{"className": "dt-center", "targets": "_all"}],
                                 responsive: false,
                                 colReorder: false,
                                 dom: "Blftp",
                                 // sScrollX: "100%",
                                 bAutoWidth: true,
                                 // sScrollY: "100%",
                                 rowId:"deliveryform.Id",
                                 scrollCollapse: true,
                                 // aaSorting:false,
                                 fnInitComplete: function(oSettings, json) {
                                   $('#myrejecteddeliverytab').html("Rejected Delivery" + "[" + delivery6table.rows().count() +"]")
                                  },
                                 columns: [
                                         { data: null, "render":"", title:"No"},
                                         { data: "deliveryform.Id"},
                                         { data: "deliverystatuses.Id"},
                                         { data: "requestor.Name", title:"Requestor"},
                                         // { data: "roadtax.Vehicle_No",title:"Vehicle No"},
                                         // { data: "roadtax.Lorry_Size",title:"Size"},
                                         // { data: "driver.Name",title:"Driver"},
                                         { data: "deliveryform.delivery_date",title:"Delivery Date"},
                                         { data: "deliveryform.delivery_time",title:"Delivery Time"},
                                         { data: "radius.Location_Name",title:"Site",editField:"deliveryform.Location"},
                                         { data: "projects.Project_Name", title:"Project Name"},
                                         { data:"options.Option" , title:"Purpose"},
                                         // { data: "company.Company_Name", title:"Company"},
                                         // { data: "client.Company_Name", title:"Client"},
                                         // { data: "deliveryform.VisitStatus", title:"Visit Status"},
                                         // { data: "deliveryform.PIC_Name", title:"PIC Name"},
                                         // { data: "deliveryform.PIC_Contact", title:"PIC Contact"},
                                         { data: "deliveryform.Remarks", title:"Remarks"},
                                         { data: "deliverystatuses.remarks", title:"Reason"},
                                         // { data: "approver.Name", title:"Approver"},
                                         { data: "deliveryform.created_at", title:"Request Date"},
                                         { data: "deliverystatuses.updated_at", title:"Reject Date"},
                                         {
                                            sortable: false,
                                            title:"Items",
                                            // title:"View Items",
                                            "render": function ( data, type, full, meta ) {
                                                return '<button data-toggle="modal" data-target="#viewItemListModal" onclick="viewItemList('+full.deliveryform.Id+')">View Items</button>';
                                               }
                                        },
                                        {
                                            sortable: false,
                                            title:"Action",
                                            // title:"View Items",
                                            "render": function ( data, type, full, meta ) {
                                                // return '<button onclick="CancelDialog('+full.deliveryform.Id+')">Cancel</button><button onclick="OpenRecallDialog('+full.deliveryform.Id+')">Recall</button>';
                                                return '<a href="/deliverytrackingdetails/'+full.deliveryform.Id+'"><button>Track</button></a>';
                                               }
                                        },
                                          ],
                                 select: {
                                         style:    'os',
                                         selector: 'tr'
                                       },
                                       buttons:[],
                     });
                        delivery6table.on( 'order.dt search.dt', function () {
                          delivery6table.column(0, {search:'applied', order:'applied'}).nodes().each( function (cell, i) {
                              cell.innerHTML = i+1;
                          } );
                          } ).draw();
                        //Table 7
                        $('#delivery7table').on( 'click', 'tr', function () {
                          // var data = delivery4table.row($(this).parents('tr')).data();
                           deliveryid = delivery7table.row( this ).data().deliveryform.Id;
                         });
                            delivery7table=$('#delivery7table').DataTable( {
                           ajax: {
                             "url": "{{ asset('/Include/delivery.php') }}",
                             "data": {
                                 "UserId": {{ $me->UserId }},
                                 "Status": "%Insufficient%"
                             }
                           },
                                 columnDefs: [{ "visible": false, "targets" :[1,2,3]},{"className": "dt-center", "targets": "_all"}],
                                 responsive: false,
                                 colReorder: false,
                                 dom: "Blftp",
                                 sScrollX: "100%",
                                 bAutoWidth: true,
                                 sScrollY: "100%",
                                 rowId:"deliveryform.Id",
                                 scrollCollapse: true,
                                 // aaSorting:false,
                                 fnInitComplete: function(oSettings, json) {
                                   $('#insufficientstockstab').html("Insufficient Stocks" + "[" + delivery7table.rows().count() +"]")
                                  },
                                 columns: [
                                        { data: null, "render":"", title:"No"},
                                         { data: "deliveryform.Id"},
                                         { data: "deliverystatuses.Id"},
                                         { data: "requestor.Name", title:"Requestor"},
                                         { data: "deliveryform.DO_No",title:"Do Number"},
                                         { data: "roadtax.Vehicle_No",title:"Vehicle No",editField:"deliveryform.roadtaxId"},
                                         { data: "roadtax.Lorry_Size",title:"Size"},
                                         { data: "driver.Name",title:"Driver"},
                                         { data: "deliveryform.delivery_date",title:" Delivery Date"},
                                         { data: "deliveryform.delivery_time",title:" Delivery Time"},
                                         { data: "deliveryform.pickup_date",title:"Pickup Date"},
                                         { data: "deliveryform.pick_up_time",title:"Pickup Time"},
                                         { data: "radius.Location_Name",title:"Site",editField:"deliveryform.Location"},
                                         { data: "projects.Project_Name", title:"Project Name"},
                                         // { data: "company.Company_Name", title:"Company",editField:"deliveryform.company_id"},
                                         // { data: "deliveryform.VisitStatus", title:"Visit Status"},
                                         { data:"options.Option", title:"Purpose"},
                                         { data: "deliveryform.PIC_Name", title:"PIC Name"},
                                         { data: "deliveryform.PIC_Contact", title:"PIC Contact"},
                                         { data: "deliveryform.Remarks", title:"Remarks"},
                                         { data:"deliverystatuses.updated_at", title:"Recall Date"},
                                         {
                                            sortable: false,
                                            title:"Items",
                                            // title:"View Items",
                                            "render": function ( data, type, full, meta ) {
                                                return '<button data-toggle="modal" data-target="#editItemListModal" onclick="editItemList2('+full.deliveryform.Id+')">Edit Items</button>';
                                               }
                                        },
                                        {
                                            sortable: false,
                                            title:"Action",
                                            // title:"View Items",
                                            "render": function ( data, type, full, meta ) {
                                                return '<button onclick="ResubmitDialog('+full.deliveryform.Id+')">Resubmit</button><br><br><a href="/deliverytrackingdetails/'+full.deliveryform.Id+'"><button>Track</button></a>';
                                               }
                                        },
                                          ],
                                 select: {
                                         style:    'os',
                                         selector: 'tr'
                                       },
                                       buttons:[],
                     });
                        delivery7table.on( 'order.dt search.dt', function () {
                          delivery7table.column(0, {search:'applied', order:'applied'}).nodes().each( function (cell, i) {
                              cell.innerHTML = i+1;
                          } );
                          } ).draw();
                        //Table 8
                        $('#delivery8table').on( 'click', 'tr', function () {
                          // var data = delivery4table.row($(this).parents('tr')).data();
                           deliveryid = delivery7table.row( this ).data().deliveryform.Id;
                         });
                            delivery8table=$('#delivery8table').DataTable( {
                           ajax: {
                             "url": "{{ asset('/Include/delivery.php') }}",
                             "data": {
                                 "UserId": {{ $me->UserId }},
                                 "Status": "%Update%"
                             }
                           },
                                 columnDefs: [{ "visible": false, "targets" :[1,2]},{"className": "dt-center", "targets": "_all"}],
                                 responsive: false,
                                 colReorder: false,
                                 dom: "Blftp",
                                 sScrollX: "100%",
                                 bAutoWidth: true,
                                 sScrollY: "100%",
                                 rowId:"deliveryform.Id",
                                 scrollCollapse: true,
                                 // aaSorting:false,
                                 fnInitComplete: function(oSettings, json) {
                                   $('#stockupdatetab').html("Update Stocks" + "[" + delivery8table.rows().count() +"]")
                                  },
                                 columns: [
                                        { data: null, "render":"", title:"No"},
                                         { data: "deliveryform.Id"},
                                         { data: "deliverystatuses.Id"},
                                         // { data: "requestor.Name", title:"Requestor"},
                                         { data: "deliveryform.DO_No",title:"Do Number"},
                                         // { data: "roadtax.Vehicle_No",title:"Vehicle No",editField:"deliveryform.roadtaxId"},
                                         // { data: "roadtax.Lorry_Size",title:"Size"},
                                         // { data: "driver.Name",title:"Driver"},
                                         { data: "deliveryform.delivery_date",title:" Delivery Date"},
                                         { data: "deliveryform.delivery_time",title:" Delivery Time"},
                                         { data: "radius.Location_Name",title:"Site",editField:"deliveryform.Location"},
                                         { data: "projects.Project_Name", title:"Project Name"},
                                         // { data: "company.Company_Name", title:"Company",editField:"deliveryform.company_id"},
                                         // { data: "deliveryform.VisitStatus", title:"Visit Status"},
                                         // { data:"inventories.Item_Code",title:"Item"},
                                         { data:"options.Option", title:"Purpose"},
                                         // { data: "deliveryform.PIC_Name", title:"PIC Name"},
                                         // { data: "deliveryform.PIC_Contact", title:"PIC Contact"},
                                         // { data: "deliveryform.Remarks", title:"Remarks"},
                                         { data:"deliverystatuses.updated_at", title:"Stock-In Date"},
                                         {
                                            sortable: false,
                                            title:"Items",
                                            // title:"View Items",
                                            "render": function ( data, type, full, meta ) {
                                                // console.log(full.deliveryform.Id);
                                                return '<button data-toggle="modal" data-target="#viewItemListModal" onclick="viewItemList('+full.deliveryform.Id+')">View Items</button>';
                                               }
                                        }
                                          ],
                                 select: {
                                         style:    'os',
                                         selector: 'tr'
                                       },
                                       buttons:[],
                     });
                        delivery8table.on( 'order.dt search.dt', function () {
                          delivery8table.column(0, {search:'applied', order:'applied'}).nodes().each( function (cell, i) {
                              cell.innerHTML = i+1;
                          } );
                          } ).draw();
                         //Table 9
                        $('#delivery9table').on( 'click', 'tr', function () {
                          // var data = delivery4table.row($(this).parents('tr')).data();
                           deliveryid = delivery9table.row( this ).data().deliveryform.Id;
                         });
                            delivery9table=$('#delivery9table').DataTable( {
                           ajax: {
                             "url": "{{ asset('/Include/delivery.php') }}",
                             "data": {
                                 "UserId": {{ $me->UserId }},
                                 "Status": "%Cancelled%"
                             }
                           },
                                 columnDefs: [{ "visible": false, "targets" :[1,2]},{"className": "dt-center", "targets": "_all"}],
                                 responsive: false,
                                 colReorder: false,
                                 dom: "Blftp",
                                 sScrollX: "100%",
                                 bAutoWidth: true,
                                 sScrollY: "100%",
                                 rowId:"deliveryform.Id",
                                 scrollCollapse: true,
                                 // aaSorting:false,
                                 fnInitComplete: function(oSettings, json) {
                                   $('#mycanceldeliverytab').html("Cancelled Delivery" + "[" + delivery9table.rows().count() +"]")
                                  },
                                 columns: [
                                       { data: null, "render":"", title:"No"},
                                         { data: "deliveryform.Id"},
                                         { data: "deliverystatuses.Id"},
                                         // { data: "requestor.Name", title:"Requestor"},
                                         { data: "deliveryform.DO_No", title:"DO Number"},
                                         // { data: "roadtax.Vehicle_No",title:"Vehicle No"},
                                         // { data: "roadtax.Lorry_Size",title:"Size"},
                                         // { data: "driver.Name",title:"Driver"},
                                         { data: "deliveryform.delivery_date",title:"Delivery Date"},
                                         { data: "deliveryform.delivery_time",title:"Delivery Time"},
                                         // { data: "deliveryform.pickup_date",title:"Pickup Date"},
                                         // { data: "deliveryform.pick_up_time",title:"Pickup Time"},
                                         { data: "radius.Location_Name",title:"Site",editField:"deliveryform.Location"},
                                         { data: "projects.Project_Name", title:"Project Name"},
                                         // { data: "company.Company_Name", title:"Company"},
                                         // { data: "client.Company_Name", title:"Client"},
                                         // { data: "deliveryform.VisitStatus", title:"Visit Status"},
                                         { data: "options.Option", title:"Purpose" },
                                         // { data: "deliveryform.PIC_Name", title:"PIC Name"},
                                         // { data: "deliveryform.PIC_Contact", title:"PIC Contact"},
                                         { data: "deliveryform.Remarks", title:"Remarks"},
                                         { data: "deliverystatuses.remarks", title:"Reason"},
                                         { data: "deliverystatuses.created_at", title:"Request Date"},
                                         { data: "deliverystatuses.updated_at", title:"Approve Date"},
                                         { data: "deliveryform.approve", title:"Status",
                                           "render": function ( data, type, full, meta ) {
                                                if(full.deliveryform.approve == 1)
                                                {
                                                  return '<span style="color:green">Approved</span>'
                                                }
                                                else
                                                {
                                                  return '<span style="color:red">Pending</span>'
                                                }
                                               }
                                         },
                                         {
                                            sortable: false,
                                            title:"Items",
                                            // title:"View Items",
                                            "render": function ( data, type, full, meta ) {
                                                return '<button data-toggle="modal" data-target="#viewItemListModal" onclick="viewItemList('+full.deliveryform.Id+')">View Items</button>';
                                               }
                                        },
                                        {
                                            sortable: false,
                                            title:"Action",
                                            // title:"View Items",
                                            "render": function ( data, type, full, meta ) {
                                                return '<a href="/deliverytrackingdetails/'+full.deliveryform.Id+'"><button>Track</button></a>\
                                                <a target="_blank" href="{{url("deliveryorder")}}/'+full.deliveryform.Id+'">Print DO<a>/';
                                               }
                                        },
                                          ],
                                 select: {
                                         style:    'os',
                                         selector: 'tr'
                                       },
                                       buttons:[],
                     });
                        delivery9table.on( 'order.dt search.dt', function () {
                          delivery9table.column(0, {search:'applied', order:'applied'}).nodes().each( function (cell, i) {
                              cell.innerHTML = i+1;
                          } );
                          } ).draw();
             $("#ajaxloader").hide();
             $("#ajaxloader2").hide();
             $("#ajaxloader3").hide();
             $("#ajaxloader4").hide();
             $("#ajaxloader5").hide();
             $("#ajaxloader6").hide();
             $('a[data-toggle="tab"]').on( 'shown.bs.tab', function (e) {
               var target = $(e.target).attr("href") // activated tab
               if (target=="#mypendingdelivery")
               {
                 $.fn.dataTable.tables( {visible: true, api: true} ).columns.adjust();
               }
               else if (target=="#myprocessingdelivery")
               {
                 $.fn.dataTable.tables( {visible: true, api: true} ).columns.adjust();
               }
               else if(target=="#myaccepteddelivery")
               {
                 $.fn.dataTable.tables( {visible: true, api: true} ).columns.adjust();
               }
               else if(target=="#myrecalleddelivery")
               {
                 $.fn.dataTable.tables( {visible: true, api: true} ).columns.adjust();
               }
               else if(target=="#mycompleteddelivery")
               {
                 $.fn.dataTable.tables( {visible: true, api: true} ).columns.adjust();
               }
               else if(target=="#myrejecteddelivery")
               {
                 $.fn.dataTable.tables( {visible: true, api: true} ).columns.adjust();
               }
               else if(target=="#mycanceldelivery")
               {
                 $.fn.dataTable.tables( {visible: true, api: true} ).columns.adjust();
               }
               else if(target=="#insufficientstocks")
               {
                 $.fn.dataTable.tables( {visible: true, api: true} ).columns.adjust();
               }
               else if(target=="#stockupdate")
               {
                 $.fn.dataTable.tables( {visible: true, api: true} ).columns.adjust();
               }
             } );
    $(function(){
    $('a[data-toggle="tab"]').on( 'shown.bs.tab', function (e) {
      var target = $(e.target).attr("href") // activated tab
      $.fn.dataTable.tables( {visible: true, api: true} ).columns.adjust();
    } );
  })
    $('body').on('expanded.pushMenu collapsed.pushMenu', function() {
    setTimeout(function(){
        $.fn.dataTable.tables( {visible: true, api: true} ).columns.adjust();
    }, 350);
});
});
</script>
<script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyDuDdIs9T09hGo4LV3dRSiuHVMbUnkS-JE&libraries=places&callback=initMap" async defer></script>

@endsection

@section('content')

<div class="content-wrapper">
  <!-- Content Header (Page header) -->
  <section class="content-header">
    <h1>My Delivery Request
      <small>My Workplace</small>
    </h1>

    <ol class="breadcrumb">
      <li><a href="{{ url('/home') }}"><i class="fa fa-dashboard"></i> Home</a></li>
      <li><a href="#">My Workplace</a></li>
      <li class="active">My Delivery Request</li>
    </ol>
  </section>

  <!--Main Content-->
  <section class="content">

    <div class="row">

      <div class="modal fade" id="viewItemListModal" role="dialog" aria-labelledby="myItemListModalLabel" style="display: none;">
            <div class="modal-dialog" role="document">
              <div class="modal-content">
                <div class="modal-header">
                  <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true"></span></button>
                  <h4 class="modal-title" id="ItemListModalLabel">Item List</h4>
                </div>
                <div class="modal-body">
                  <table id="itemlisttable" class="table table-condensed">
                <thead>
                    <tr>
                      <th>Item Code</th>
                      <th>Description</th>
                      <th>Additional Description</th>
                      <th>Unit</th>
                      <th>Purpose</th>
                      <th>Requested Quantity</th>
                     <!--  <th>Sent Quantity</th>
                      <th>Actual Quantity</th> -->
                    </tr>
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


      <div class="modal fade" id="updateItemListModal" role="dialog" aria-labelledby="updateItemListModalLabel" style="display: none;">
          <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
              <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">×</span></button>
                <h4 class="modal-title" id="updateItemListModalLabel">Item List</h4>
              </div>
              <div class="modal-body">
                <table id="updatelisttable" class="display">
                  <thead>
                        {{-- prepare header search textbox --}}
                        <tr>
                          @foreach($updateitem as $key=>$value)

                            @if ($key==0)
                              <td></td>
                              @foreach($value as $field=>$value)
                                  <td/>{{ $field }}</td>
                              @endforeach
                              <td></td>
                              <!-- <td></td> -->

                            @endif

                          @endforeach
                        </tr>
                    </thead>
                    <tbody>

                      <?php $i = 0; ?>
                      @foreach($updateitem as $itemlists)
                          <tr id="row_{{ $i }}">
                            <td></td>
                              @foreach($itemlists as $key=>$value)
                                <td>
                                  {{ $value }}
                                </td>
                              @endforeach
                              <td></td>
                              <!-- <td></td> -->
                          </tr>
                          <?php $i++; ?>

                      @endforeach

                  </tbody>
                  </table>
              </div>
            </div>
          </div>
        </div>

      <div class="modal fade" id="editItemListModal" role="dialog" aria-labelledby="editItemListModalLabel" style="display: none;">
          <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
              <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">×</span></button>
                <h4 class="modal-title" id="editItemListModalLabel">Item List</h4>
              </div>
              <div class="modal-body">
                <table id="editlisttable" class="display" style="width: 100%">
                  <thead>
                        {{-- prepare header search textbox --}}
                        <tr>
                          @foreach($itemlist as $key=>$value)

                            @if ($key==0)
                              <td></td>
                              @foreach($value as $field=>$value)
                                  <td/>{{ $field }}</td>
                              @endforeach
                              <td>Action</td>

                            @endif

                          @endforeach
                        </tr>
                    </thead>
                    <tbody>

                      <?php $i = 0; ?>
                      @foreach($itemlist as $itemlists)
                          <tr id="row_{{ $i }}">
                            <td></td>
                              @foreach($itemlists as $key=>$value)
                                <td>
                                  {{ $value }}
                                </td>
                              @endforeach
                              <td></td>
                          </tr>
                          <?php $i++; ?>

                      @endforeach

                  </tbody>
                  </table>
              </div>
            </div>
          </div>
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

      <div class="modal fade" id="Delete" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
            <div class="modal-dialog" role="document">
              <div class="modal-content">
                <div class="modal-header">
                  <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                  <h4 class="modal-title" id="myModalLabel">Delete</h4>
                </div>
                <div class="modal-body">
                  <div class="form-group" id="deletedelivery">

                  </div>
                  Are you sure you want to delete this delivery?
                </div>
                <div class="modal-footer">
                  <center><img src="{{ URL::to('/') . "/img/ajax-loader.gif" }}" width="50px" height="50px" alt="Loading" name='ajaxloader3' id="ajaxloader3"></center>
                  <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                  <button type="button" class="btn btn-primary" onclick="deletedelivery()">Delete</button>
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
      <div class="modal fade" id="Recall" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
            <div class="modal-dialog" role="document">
              <div class="modal-content">
                <div class="modal-header">
                  <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                  <h4 class="modal-title" id="myModalLabel">Recall Delivery</h4>
                </div>
                <div class="modal-body">
                  <div class="form-group" id="recalldelivery">

                  </div>
                  Are you sure you want to recall this delivery?
                </div>
                <div class="modal-footer">
                  <center><img src="{{ URL::to('/') . "/img/ajax-loader.gif" }}" width="50px" height="50px" alt="Loading" name='ajaxloader' id="ajaxloader";></center>
                  <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                  <button type="button" class="btn btn-primary" onclick="recalldelivery()">Recall</button>
                </div>
              </div>
            </div>
      </div>
      <div class="modal fade" id="Cancel" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
            <div class="modal-dialog" role="document">
              <div class="modal-content">
                <div class="modal-header">
                  <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                  <h4 class="modal-title" id="myModalLabel">Cancel Delivery</h4>
                </div>
                <div class="modal-body">
                  <div class="form-group" id="canceldelivery">

                  </div>
                  <label>Reason<span class="red-star">*</span></label>
                  <div class="form-group">
                      <input placeholder="Cancel Reason" type="textarea" id="cancelreason" name="cancelreason" value="">
                  </div>
                </div>
                <div class="modal-footer">
                  <center><img src="{{ URL::to('/') ."/img/ajax-loader.gif" }}" width="50px" height="50px" alt="Loading" name='ajaxloader5' id="ajaxloader5"></center>
                  <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                  <button type="button" class="btn btn-primary" onclick="canceldelivery()">Cancel Delivery</button>
                </div>
              </div>
            </div>
      </div>
       <div class="modal fade" id="terminateSO" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
            <div class="modal-dialog" role="document">
              <div class="modal-content">
                <div class="modal-header">
                  <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                  <h4 class="modal-title" id="myModalLabel">Terminate Genset</h4>
                </div>
                 <form enctype="multipart/form-data" id="terminate_form" role="form" method="POST" action="" >
                <div class="modal-body">
                  <label>Off-Hire Date<span class="red-star">*</span></label>
                  <div class="row">
                    <div class="col-md-6">
                       <div class="input-group">
                         <div class="input-group-addon">
                           <i class="fa fa-clock-o"></i>
                         </div>
                         <input type="text" class="form-control" id="offhire" name="offhire">
                       </div>
                     </div>
                  </div>
                  <br>
                  <div class="row">
                    <div class="col-md-6">
                    <label>Documents<span class="red-star">*</span></label>
                      <input type="file" name="terminatedoc[]" id="terminatedoc" multiple="">
                       <input type="hidden" name="deliveryitemid[]" id="deliveryitemid">
                    </div>
                  </div>
                </div>
                <div class="modal-footer">
                  <center><img src="{{ URL::to('/') ."/img/ajax-loader.gif" }}" width="50px" height="50px" alt="Loading" name='ajaxloader6' id="ajaxloader6"></center>
                  <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                  <button type="submit" class="btn btn-primary" id="terminate" value="">Terminate</button>
                </div>
                </form>
              </div>
            </div>
      </div>
       <div class="modal fade" id="Resubmit" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
            <div class="modal-dialog" role="document">
              <div class="modal-content">
                <div class="modal-header">
                  <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                  <h4 class="modal-title" id="myModalLabel">Resubmit Delivery</h4>
                </div>
                <div class="modal-body">
                  <div class="form-group" id="resubmitdelivery">

                  </div>
                    Are you sure you wish to resubmit this delivery?
                </div>
                <div class="modal-footer">
                  <center><img src="{{ URL::to('/') ."/img/ajax-loader.gif" }}" width="50px" height="50px" alt="Loading" name='ajaxloader2' id="ajaxloader2"></center>
                  <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                  <button type="button" class="btn btn-primary" onclick="Resubmit()">Resubmit Delivery</button>
                </div>
              </div>
            </div>
      </div>
      <div class="mapsmodal modal fade" id="mapsViewModal" role="dialog" aria-labelledby="mymapsViewModalLabel" style="display: none;">
          <div class="modal-dialog" role="document">
            <div class="modal-content">
              <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true"></span></button>
                <h4 class="modal-title" id="mapsModalLabel">Location</h4>
              </div>
              <div class="modal-body">
                  <div id="map">
                  </div>
              </div>
              <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
              </div>
            </div>
          </div>
        </div>
      <div id="calendarModal" class="modal fade">
      <div class="modal-dialog">
          <div class="modal-content">
              <div class="modal-header">
                  <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">×</span> <span class="sr-only">close</span></button>
                  <h4 id="calendarmodalTitle" class="modal-title"></h4>
              </div>
              <div id="calendarmodalBody" class="modal-body"> </div>
              <div class="modal-footer">
                  <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
              </div>
          </div>
      </div>
      </div>



      <!-- Tab Content Main -->
      <div class="col-md-12">
        <div class="nav-tabs-custom">

          <ul class="nav nav-tabs">
            <li class="active" style="width:10%"><a href="#deliveryapplicationform" data-toggle="tab">Application Form</a></li>
            <!-- <li style="width:10%"><a href="#mypendingdelivery" data-toggle="tab" id="mypendingdeliverytab">My Pending Delivery</a></li> -->
            <li style="width:10%"><a href="#myprocessingdelivery" data-toggle="tab" id="myprocessingdeliverytab">My Processing Delivery</a></li>
            <li style="width:10%"><a href="#myaccepteddelivery" data-toggle="tab" id="myaccepteddeliverytab">My Accepted Delivery</a></li>
            <li style="width:10%"><a href="#mycompleteddelivery" data-toggle="tab" id="mycompleteddeliverytab">My Completed Delivery</a></li>
            <li style="width:10%"><a href="#myincompleteddelivery" data-toggle="tab" id="myincompleteddeliverytab">My Incompleted Delivery</a></li>
            <li style="width:10%"><a href="#myrecalleddelivery" data-toggle="tab" id="myrecalleddeliverytab">My Recalled Delivery</a></li>
            <li style="width:10%"><a href="#myrejecteddelivery" data-toggle="tab" id="myrejecteddeliverytab">My Rejected Delivery</a></li>
            <li style="width:10%"><a href="#mycanceldelivery" data-toggle="tab" id="mycanceldeliverytab">My Cancelled Delivery</a></li>
            <li style="width:10%"><a href="#insufficientstocks" data-toggle="tab" id="insufficientstockstab">Insufficient Stocks</a></li>
            <li style="width:10%"><a href="#stockupdate" data-toggle="tab" id="stockupdatetab">Stocks Update</a></li>
          </ul>

          <!-- Tab -->
          <div class="tab-content">
            <div class="active tab-pane" id="deliveryapplicationform">
            <!-- Form -->
            <form enctype="multipart/form-data" id="upload_form" role="form" method="POST" action="" >
                <!-- Delivery Application Form-->
                <div class="box box-solid">
                 <div class="box-header with-border">
                  <div class="box-body">
                      <div class="col-md-6">
                      <div class="row">
                          <div class="row">
                            <div class="col-md-8">
                          <input type="hidden" name="UserId" value="{{ $me->UserId }}">
                          <p style="color: red"><b><i>WARNING: ANY CANCELLATION (RECALL) REQUIRES THREE BUSINESS DAYS BEFORE THE DELIVERY TO AVOID PENALTIES</i></b></p>
                          <div class="form-group">
                            <label>Date :<span class="red-star">*</span> </label>
                            <div class="input-group date">
                              <div class="input-group-addon">
                                <i class="fa fa-calendar"></i>
                              </div>
                              @if($details == null)
                              <input type="text" autocomplete="off" class="form-control pull-right" id="Date" name="Date">
                              @else
                              <input type="text" autocomplete="off" class="form-control pull-right" id="Date" name="Date" value="{{$timenow}}">
                              @endif
                            </div>
                            <!-- /.input group -->
                          </div>
                        </div>
                      </div>

                      <div class="row">
                          <div class="col-md-8">
                          <div class="form-group">
                            <label>Delivery Time:<span class="red-star">*</span> </label>
                             <input type="time" class="form-control" id="time" name="time">
                          </div>
                        </div>
                      </div>
                            <div class="row">
                              <div class="col-md-8">
                                <div class="form-group">
                            <label>Pick-up Date :<span class="red-star">*</span> </label>
                            <div class="input-group date">
                              <div class="input-group-addon">
                                <i class="fa fa-calendar"></i>
                              </div>
                              <input type="text" autocomplete="off" class="form-control pull-right" id="pickupdate" name="pickupdate">
                            </div>
                          </div>
                          </div>
                        </div>

                      <div class="row">
                          <div class="col-md-8">
                          <div class="form-group">
                            <label>Pick-up Time:<span class="red-star">*</span> </label>
                             <input type="time" class="form-control" id="pickup" name="pickup">
                          </div>
                        </div>
                      </div>

                      <div class="row">
                        <div class="col-md-8">
                        <div class="form-group">

                            <input type="checkbox" name="truck_check" id="truck_check" value="1" onclick='checkTruck()'/>
                            <label>Truck</label>
                        </div>
                      </div>
                    </div>
                          <div class="row" id="lorry_row">
                          <div class="col-md-8">
                          <div class="form-group">
                            <label>Lorry:<span class="red-star">*</span> </label>
                             <select class="lorry form-control select2" id="lorry" name="lorry">
                                  <option value="" selected>None</option>
                                  @if($terminate == "terminate")
                                  <option value="0">Self-Collect</option>
                                  @endif
                                  @foreach ($lorry as $lorries)
                                      <option  value="{{$lorries->Id}}" data-Size="{{$lorries->Lorry_Size}}" data-Dimension="{{$lorries->Lorry_Dimension}}">{{$lorries->Vehicle_No}} ({{$lorries->Lorry_Size}})</option></option>
                                  @endforeach
                                </select>
                          </div>
                        </div>
                      </div>

                      <div class="row" id="truck_row">
                        <div class="col-md-8">
                        <div class="form-group" >
                          <label>Truck:<span class="red-star">*</span> </label>
                           <select class="lorry form-control select2" id="truck" name="truck">
                                <option value="" selected>None</option>
                                <option value="0">Self-Collect</option>
                                @foreach ($truck as $t)
                                    <option  value="{{$t->Id}}">{{$t->Vehicle_No}}</option></option>
                                @endforeach
                              </select>
                        </div>
                      </div>
                    </div>

                      <div class="row" id="status_row">
                          <div class="col-md-8">
                          <div class="form-group">

                            <label>Lorry Status:</span> </label>
                             <input type="text" class="form-control" name="lorrystatus" id='lorrystatus' disabled>
                          </div>
                        </div>
                      </div>
                      <div class="row" id="dimension_row">
                          <div class="col-md-8">
                          <div class="form-group">
                            <label>Lorry Dimension:</span> </label>
                             <input type="text" class="form-control" name="lorrydimension" id='lorrydimension' disabled>
                          </div>
                        </div>
                      </div>
                      <div class="row">
                        <div class="col-md-8">
                          <label><input type="checkbox" id="checkbox"> Applying for others</label>
                          <div class="form-group" id="behalf" style="visibility: hidden;">
                            <p><i>Fill this in only when applying on behalf of others</i></p>
                             <select class="form-control select2" id="representative" name="representative">
                               <option value="" selected="">Origin Requestor's Name</option>
                               @foreach ($requestor as $req)
                              <option value="{{$req->Id}}">{{$req->Name}}</option>
                               @endforeach
                             </select>
                          </div>
                        </div>
                      </div>

                      </div>
                      </div>



                          <!--Calendar-->
                          <div class="row">
                          <div class="col-md-6">
                              <div class="col-lg-12 col-xs-12">
                                <!-- Horizontal Form -->
                                <div class="box">
                                  <div class="box-header">
                                    <h3 class="box-title">Delivery Schedule</h3>
                                    <div align="center">
                                      <i class="fa fa-circle pending"></i> Pending&nbsp;&nbsp;&nbsp;
                                      <i class="fa fa-circle processing"></i> Processing&nbsp;&nbsp;&nbsp;
                                      <i class="fa fa-circle success"></i> Approved&nbsp;&nbsp;&nbsp;
                                      <i class="fa fa-circle alert2"></i> Insufficient Stocks&nbsp;&nbsp;&nbsp;
                                    </div>
                                    <div id="calendar"></div>
                                  </div>
                                </div>
                              </div>
                            </div>
                          </div>

                      </div>
                     </div>
                   </div>



                     <!--  <div class="box-body no-padding">
                    <table id="lorrytable" class="display" cellspacing="0" width="100%" padding="30px" style="font-size: 13px;">
                      <label>Lorry : </label>
                      <thead>
                       <tr>
                       </tr>
                      </thead>
                      <tbody>
                      </tbody>
                      <tfoot></tfoot>
                    </table>
                  </div>  -->
                    <div class="box box-solid">
                     <div class="box-header with-border">
                      <div class="box-body">
                      <div class="row" id="sections">
                       <div class="section">
                        <fieldset>
                        <h3>Delivery Order</h3>
                        <div class="row">
                        <div class="col-md-5">
                          <div class="form-group">
                            <label>Project:<span class="red-star">*</span></label>
                            <select class="project form-control select2" id="project" name="section[0][project]">
                                @if($details == null)
                                  <option value="" disabled selected>None</option>
                                  @foreach ($project as $projects)
                                      <option  value="{{$projects->Id}}" data-Name="{{$projects->Project_Name}}">{{$projects->Project_Name}}</option>
                                  @endforeach
                                  @else
                                    <option  value="{{$details->projectId}}" selected="">{{$details->Project_Name}}</option>
                                  @endif
                                </select>
                          </div>
                        </div>


                          <div class="col-md-5">
                          <div class="form-group">
                            <label>Site:<span class="red-star">*</span> </label>
                            <select class="destination form-control select2" id="destination" name="section[0][destination]">
                            @if($details == null)
                                  <option value="" disabled selected>None</option>
                            @else
                              @if($site != null)
                                  <option value="{{$site->Id}}">{{$site->Location_Name ?: "-"}}</option>
                              @endif
                            @endif
                                </select>
                          </div>
                        </div>
                         <!-- <div class="col-md-2">
                          <div class="form-group">
                            <label>Insert Site:</label>
                          <button id="insertsite" target="_blank" name="insertsite" class="btn btn-success" onclick="window.open('radiusmanagement','_blank');">Insert</button>
                          </div>
                        </div> -->
                      </div>

                      <!-- <div class="row"> -->
                        <!-- <div class="col-md-5"> -->
                          <!-- <div class="form-group">
                            <label>Company: </label>
                            <select class="form-control select2"  class="select2" id="company" name="section[0][company]">
                                  <option value="" disabled selected>None</option>
                                  </option>
                          </select>
                           <input type="text" class="company form-control" id="company" name="section[0][company]" disabled>
                          </div>  -->
                          <!-- </div> -->
                      <div class="row">
                        <div class="col-md-5">
                          <label>Client:<span class="red-star">*</span> </label>
                      <div class="form-group">
                            <select class="client form-control select2" id="client" name="section[0][client]">
                              @if($details == null)
                                  <option value="" disabled selected>None</option>
                                  @foreach ($client as $destinations)
                                      <option  value="{{$destinations->Id}}">{{$destinations->Company_Name}} - {{$destinations->Company_Code}}</option>
                                  @endforeach
                              @else
                                <option value="{{$details->client_id}}" selected>{{$details->client_company}}</option>
                              @endif
                          </select>
                          </div>
                        </div>

                        <div class="col-md-5">
                          <label>Purpose:<span class="red-star">*</span> </label>
                          <div class="form-group">
                            <select class="form-control select2" id="purpose" name="section[0][purpose]">
                              @if($details == null)
                                  <option value="" disabled selected>None</option>
                                  @foreach ($purpose as $pur)
                                   <option  value="{{$pur->Id}}">{{$pur->Option}}</option>
                                  @endforeach
                              @else
                                @if ($terminate == null || $terminate == "exchangedelivery" || $terminate == "dummy")
                                  <option value="1814">Delivery</option>
                                @elseif ($terminate == "terminate" || $terminate == "exchange")
                                  <option value="1815">Collection</option>
                                @endif
                              @endif

                          </select>
                          </div>
                        </div>
                      </div>


                      <div class="row">
                        <div class="col-md-5">
                          <div class="form-group">
                            <label>Project Type:<span class="red-star">*</span> </label>
                                <!-- <select class="form-control select2" id="projtype" name="section[0][projtype]">-->
                                @if($details == null)
                                <input class="form-control textarea" id="projtype" name="section[0][projtype]" disabled="" value="">
                                @else
                                <input class="form-control textarea" id="projtype" name="section[0][projtype]" disabled="" value="{{$pt->type}}">
                                @endif
                          </div>
                        </div>

                        <div class="col-md-5">
                          <div class="form-group">
                            <label>PIC:<span class="red-star">*</span> </label>
                                <select class="form-control select2tag" id="PICname" name="section[0][PICname]">
                                  <option value="" disabled selected>None</option>
                                  @foreach ($pic as $type)
                                      @if($type->Person_In_Charge != "" || $type->Person_In_Charge != null)
                                      <option  value="{{$type->Person_In_Charge}}">{{$type->Person_In_Charge}}</option>
                                      @endif
                                  @endforeach
                                </select>
                          </div>
                        </div>
                      </div>

                      <div class="row">
                        <div class="col-md-5">
                        <div class="form-group">
                            <label>PIC Contact:<span class="red-star">*</span> </label>
                            <select class="form-control select2tag" id="PICcontact" name="section[0][PICcontact]">
                                  <option value="" disabled selected>None</option>
                                  @foreach ($pic as $type)
                                  @if($type->Contact_No != "" || $type->Contact_No != null)
                                  <option  value="{{$type->Contact_No}}">{{$type->Contact_No}}</option>
                                  @endif
                                  @endforeach
                                </select>
                          </div>
                        </div>
                        <div class="col-md-5">
                          <div class="form-group">
                            <label>Remarks: </label>
                                <input class="form-control textarea" placeholder="Anything Special?" id="Remarks" name="section[0][Remarks]" value="">
                          </div>
                        </div>
                      </div>


                      <div class="row">
                        <div class="col-md-5">
                        <div class="form-group">
                            <label>Term:</label>
                            @if($details == null)
                            <input class="form-control textarea" placeholder="Term" id="term" name="section[0][term]">
                            @else
                            <input class="form-control textarea" placeholder="Term" id="term" name="section[0][term]" value='{{$details->term}}' readonly>
                            @endif
                          </div>
                        </div>
                        <div class="col-md-5">
                          <div class="form-group">
                            <label>PO Number:</label>
                            @if($details == null)
                                <input class="form-control textarea" placeholder="PO Number" id="po" name="section[0][po]" value="">
                            @else
                                <input class="form-control textarea" placeholder="PO Number" id="po" name="section[0][po]" value="{{$details->po}}" readonly="">
                            @endif
                          </div>
                        </div>
                      </div>
                      @if($terminate == "dummy")
                      <div class="row">
                        <div class="col-md-5">
                          <div class="form-group">
                            <label>Company:</label>
                            <select class="select2 form-control" name="company">
                              @foreach($company as $companies)
                              <option value="{{$companies->Id}}">{{$companies->Company_Name}}</option>
                              @endforeach
                            </select>
                          </div>
                        </div>
                      </div>
                      @endif
                      <div class="row">
                        <div class="col-md-8">
                        <div class="form-group initialconditionsection" id="initialcondition">
                          <label>Condition: </label>
                          <select class="condition form-control select2tag" id="condition" name="section[0][condition][]">
                          <option value="" default>None</option>
                          @foreach($condition as $note)
                          <option value="{{$note->Id}}">{{$note->Option}}</option>
                          @endforeach
                          </select>
                        </div>
                      </div>

                      <div class="col-md-2">
                        <div class="form-group">
                          <label>Add Condition</label>
                          <button class="addcondition btn btn-success" id="addcondition" name="addcondition"><i class="fa fa-plus-circle"> Add</i></button>
                        </div>
                      </div>
                    </div>

                    <div class="row">
                      <div class="conditionsection col-md-8" id="conditionsection" name="conditionsection">
                      </div>
                      <!-- <div class="col-md-2">
                        <div class="form-group" id="removeconditionsection">
                        </div>
                      </div> -->
                    </div>
                    <br>

                    <div class="row">
                        <div class="col-md-8">
                        <div class="form-group initialnotesection" id="initialnote">
                          <label>Note: </label>
                          <select class="note form-control select2tag" id="note" name="section[0][note][]">
                          <option value="" default>None</option>
                          @foreach($deliverynote as $note)
                          <option value="{{$note->Id}}">{{$note->Option}}</option>
                          @endforeach
                          </select>
                        </div>
                      </div>

                      <div class="col-md-2">
                        <div class="form-group">
                          <label>Add Note</label>
                          <button class="addnote btn btn-success" id="addnote" name="addnote"><i class="fa fa-plus-circle"> Add</i></button>
                        </div>
                      </div>
                    </div>

                    <div class="row">
                      <div class="notesection col-md-8" id="notesection" name="notesection">
                      </div>
                      <!-- <div class="col-md-2">
                        <div class="form-group" id="removeconditionsection">
                        </div>
                      </div> -->
                    </div>
                    <br>

                       <!-- <div class="row">
                        <div class="col-md-10">
                        <div class="form-group">
                          <label>Note: </label>
                          <select class="form-control select2tag" id="note" name="note">
                          <option value="" default>None</option>
                          @foreach($deliverynote as $note)
                          <option value="{{$note->Id}}">{{$note->Option}}</option>
                          @endforeach
                          </select>
                        </div>
                      </div>
                    </div> -->

                      <div class="form-group">
                        <label>Delivery Items:<span class="red-star">*</span><span><a class='btn btn-primary btn-sm' onclick="MRModal(this)">Import</a></span></label>
                        <table id="itemtable" class="table table-bordered table-hover">
                            <col width="10%">
                            <col width="30%">
                            <col width="30%">
                            <col width="7%">
                            <col width="3%">
                            <col width="3%">
                            <!-- <col width="10%"> -->
                          <thead align="center">
                            <tr bgcolor="#0d2244" style="color:white;" >
                              <th><label>Item Code<span class="red-star">*</span></label></th>
                              <th><label>Item Description</label></th>
                              <th><label>Additional Description</label></th>
                              <th><label>Item Unit</label></th>
                              <th><label>Quantity<span class="red-star">*</span></label></th>
                              <!-- <th><label>Purpose</label></th> -->
                              <th><label>Action</label></th>
                            </tr>
                          </thead>
                          <tbody>
                          </tbody>
                          <tfoot>
                            <tr>
                              <td>
                                  <select class="Item form-control select2" id="item" name="item">
                                  <option value="" disabled selected>None</option>
                                  @if($soitem !=null && $terminate != null && $terminate!= "dummy")
                                    @foreach($soitem as $items)
                                     <option  value="{{$items->Id}}" data-description=" {{$items->Description}}" data-unit="{{$items->Unit}}">{{$items->Item_Code}}</option>
                                     @endforeach
                                     <option value="1080" data-description="Oil Tray" data-unit="unit">Tray</option>
                                  @else
                                  @foreach ($deliveryitems as $items)
                                      <option  value="{{$items->Id}}" data-description=" {{$items->Description}}" data-unit="{{$items->Unit}}">{{$items->Item_Code}}</option>
                                  @endforeach
                                  @endif
                                </select>
                              </td>
                              <td>
                                   <select class="Description form-control select2" id="Description" name="Description">
                                  <option value="" disabled selected>None</option>
                                  @if($soitem !=null && $terminate != null && $terminate != "dummy")
                                    @foreach($soitem as $items)
                                     <option value="{{$items->Id}}" data-code=" {{$items->Item_Code}}" data-unit="{{$items->Unit}}">{{$items->Description}}</option>
                                     @endforeach
                                     <option value="1080" data-code="Tray" data-unit="unit">Oil Tray</option>
                                  @else
                                  @foreach ($deliveryitems as $items)
                                      <option value="{{$items->Id}}" data-code=" {{$items->Item_Code}}" data-unit="{{$items->Unit}}">{{$items->Description}}</option>
                                  @endforeach
                                  @endif
                                </select>
                              </td>
                              <td>
                                   <input type="text" class="form-control" placeholder="Additional Description" name="Additional_Description" id="add_desc">
                              </td>
                              <td>
                                  <input type="text" class="Unit form-control" name="Unit" id="Unit" readonly>
                              </td>
                              <td>
                                <input type="text" class="form-control" placeholder="Qty" id="xquantity" name="quantity";>
                              </td>
                              <!-- <td>
                                <select class="form-control select2" id="purpose" name="purpose">
                                  <option value="" disabled selected>None</option>
                                  @foreach ($purpose as $purposes)
                                      <option  value="{{$purposes->Id}}">{{$purposes->Option}}</option>
                                  @endforeach
                                </select>
                              </td> -->
                              <td>
                                <div>
                            <input type="button" id="addItem" class="btn btn-success" value="Add" onclick="Add(this)">
                            <!-- <button id="deleteItem" class="btn btn-danger">Delete</button> -->
                                </div>
                              </td>
                            </tr>
                          </tfoot>
                        </table>
                      </div>
                        <a href="#" class="remove btn btn-danger" style="background: #d9534f; border-color: #d9534f;">Remove</a>
                        <br><br>
                      </fieldset>
                      </div>
                    </div>
                  </div>
                 </div>
                </div>
                      <div>
                         <!-- <button class="addsection btn btn-success">Add Site</button> -->
                         @if($details == null)
                        <a  class="addsection btn btn-success">Add Site</a>
                        @endif
                      </div>
                      <br>
                      <div>
                        @if($details != null)
                        <input type="hidden" name="salesorderid" id="salesorderid" value="{{$details->Id}}">
                        <input type="hidden" name="flow" id="flow" value="{{$terminate}}">
                          @if($terminate == "terminate")
                            @if($details->recurring == 1)
                              <button class="btn btn btn-primary" id="terminatereq">Terminate</button>
                            @else

                            @endif
                          @elseif($terminate == "dummy")
                            <button class="btn btn btn-primary" id="dummy">Dummy DO</button>
                          @else
                            <button type="submit" class="btn btn-primary" onclick="applydelivery()" id="btnapplydelivery">Submit Application</button>
                          @endif
                        @else
                        <input type="hidden" name="salesorderid" id="salesorderid" value="">
                        <input type="hidden" name="flow" id="flow" value="">
                        <button type="submit" class="btn btn-primary" onclick="applydelivery()" id="btnapplydelivery">Submit Application</button>
                        @endif

                      </div>
           </form>
            <center><img src="{{ URL::to('/') . "/img/ajax-loader.gif" }}" width="50px" height="50px" alt="Loading" name='ajaxloader4' id="ajaxloader4" style="display:none"></center>
            </div>
        <!-- .Tab -->


              <div class="tab-pane" id="myprocessingdelivery">
                    <table id="delivery2table" class="display" cellspacing="0" width="100%" padding="30px" style="font-size: 13px;">
                      <thead>
                        {{-- prepare header search textbox --}}
                          <tr>
                            @foreach($processingdelivery as $key=>$value)

                              @if ($key==0)
                                  <td></td>

                                @foreach($value as $field=>$value)
                                    <td/>{{ $field }}</td>
                                @endforeach
                                  <td></td>
                                  <td></td>
                                  <td></td>

                              @endif

                            @endforeach
                          </tr>
                      </thead>
                      <tbody>
                        @if(count($processingdelivery))

                        <?php $i = 0; ?>
                        @foreach($processingdelivery as $mydeliveries)

                          <tr id="row_{{ $i }}">
                              <td></td>
                              @foreach($mydeliveries as $key=>$value)
                                <td>
                                  {{ $value }}
                                </td>
                              @endforeach
                                  <td></td>
                                  <td></td>
                                  <!-- <td></td> -->

                          </tr>
                          <?php $i++; ?>
                        @endforeach

                        @endif

                    </tbody>
                      <tfoot></tfoot>
                  </table>
              </div>

              <div class="tab-pane" id="myaccepteddelivery">
                  <table id="delivery3table" class="display" cellspacing="0" width="100%" padding="30px" style="font-size: 13px;">
                    <thead>
                        {{-- prepare header search textbox --}}
                          <tr>
                            @foreach($processingdelivery as $key=>$value)

                              @if ($key==0)
                                  <td></td>

                                @foreach($value as $field=>$value)
                                    <td/>{{ $field }}</td>
                                @endforeach
                                  <td></td>
                                  <td></td>
                                  <td></td>

                              @endif

                            @endforeach
                          </tr>
                      </thead>
                      <tbody>
                        @if(count($processingdelivery))

                        <?php $i = 0; ?>
                        @foreach($processingdelivery as $mydeliveries)

                          <tr id="row_{{ $i }}">
                              <td></td>
                              @foreach($mydeliveries as $key=>$value)
                                <td>
                                  {{ $value }}
                                </td>
                              @endforeach
                                  <td></td>
                                  <td></td>
                                  <!-- <td></td> -->

                          </tr>
                          <?php $i++; ?>
                        @endforeach

                        @endif

                    </tbody>
                      <tfoot></tfoot>
                  </table>
              </div>

              <div class="tab-pane" id="myrecalleddelivery">
                  <table id="delivery4table" class="display" cellspacing="0" width="100%" padding="30px" style="font-size: 13px;">
                    <thead>
                        {{-- prepare header search textbox --}}
                          <tr>
                            @foreach($recalldelivery as $key=>$value)

                              @if ($key==0)
                                  <td></td>

                                @foreach($value as $field=>$value)
                                    <td/>{{ $field }}</td>
                                @endforeach
                                  <td></td>
                                  <td></td>
                                  <td></td>

                              @endif

                            @endforeach
                          </tr>
                      </thead>
                      <tbody>
                        @if(count($recalldelivery))

                        <?php $i = 0; ?>
                        @foreach($recalldelivery as $mydeliveries)

                          <tr id="row_{{ $i }}">
                              <td></td>
                              @foreach($mydeliveries as $key=>$value)
                                <td>
                                  {{ $value }}
                                </td>
                              @endforeach
                                  <td></td>
                                  <td></td>
                                  <!-- <td></td> -->
                          </tr>
                          <?php $i++; ?>
                        @endforeach

                        @endif

                    </tbody>
                      <tfoot></tfoot>
                  </table>
              </div>

              <div class="tab-pane" id="mycompleteddelivery">
                  <table id="delivery5table" class="display" cellspacing="0" width="100%" padding="30px" style="font-size: 13px;">
                    <thead>
                        {{-- prepare header search textbox --}}
                          <tr>
                            @foreach($completedelivery as $key=>$value)

                              @if ($key==0)
                                  <td></td>

                                @foreach($value as $field=>$value)
                                    <td/>{{ $field }}</td>
                                @endforeach
                                  <td></td>
                                  <td></td>
                                  <td></td>
                                  <td></td>
                                {{-- 15 --}}

                              @endif

                            @endforeach
                          </tr>
                      </thead>

                      <tbody>
                        @if(count($completedelivery))

                        <?php $i = 0; ?>
                        @foreach($completedelivery as $mydeliveries)

                          <tr id="row_{{ $i }}">
                              <td></td>
                              @foreach($mydeliveries as $key=>$value)
                                <td>
                                  {{ $value }}
                                </td>
                              @endforeach
                                  <td></td>
                                  <td></td>
                                  <td></td>
                                  <td></td>
                          </tr>
                          <?php $i++; ?>
                        @endforeach

                        @endif

                    </tbody>
                      <tfoot></tfoot>
                  </table>
              </div>
              <div class="tab-pane" id="myincompleteddelivery">
                  <table id="delivery10table" class="display" cellspacing="0" width="100%" padding="30px" style="font-size: 13px;">
                    <thead>
                        {{-- prepare header search textbox --}}
                          <tr>
                            @foreach($incompletedelivery as $key=>$value)

                              @if ($key==0)
                                  <td></td>

                                @foreach($value as $field=>$value)
                                    <td/>{{ $field }}</td>
                                @endforeach
                                  <td></td>

                              @endif

                            @endforeach
                          </tr>
                      </thead>
                      <tbody>
                        @if(count($incompletedelivery))

                        <?php $i = 0; ?>
                        @foreach($incompletedelivery as $mydeliveries)

                          <tr id="row_{{ $i }}">
                              <td></td>
                              @foreach($mydeliveries as $key=>$value)
                                <td>
                                  {{ $value }}
                                </td>
                              @endforeach
                                  <td></td>

                          </tr>
                          <?php $i++; ?>
                        @endforeach

                        @endif

                    </tbody>
                      <tfoot></tfoot>
                  </table>
              </div>
              <div class="tab-pane" id="myrejecteddelivery">
                  <table id="delivery6table" class="display" cellspacing="0" width="100%" padding="30px" style="font-size: 13px;">
                    <thead>
                        {{-- prepare header search textbox --}}
                          <tr>
                            @foreach($rejectdelivery as $key=>$value)

                              @if ($key==0)
                                  <td></td>

                                @foreach($value as $field=>$value)
                                    <td/>{{ $field }}</td>
                                @endforeach
                                  <td></td>
                                  <td></td>
                                  <!-- <td></td> -->

                              @endif

                            @endforeach
                          </tr>
                      </thead>
                      <tbody>
                        @if(count($rejectdelivery))

                        <?php $i = 0; ?>
                        @foreach($rejectdelivery as $mydeliveries)

                          <tr id="row_{{ $i }}">
                              <td></td>
                              @foreach($mydeliveries as $key=>$value)
                                <td>
                                  {{ $value }}
                                </td>
                              @endforeach
                                  <td></td>
                                  <td></td>
                                  <!-- <td></td> -->

                          </tr>
                          <?php $i++; ?>
                        @endforeach

                        @endif

                    </tbody>
                      <tfoot></tfoot>
                  </table>
              </div>

               <div class="tab-pane" id="insufficientstocks">
                  <table id="delivery7table" class="display" cellspacing="0" width="100%" padding="30px" style="font-size: 13px;">
                    <thead>
                        {{-- prepare header search textbox --}}
                          <tr>
                            @foreach($recalldelivery as $key=>$value)

                              @if ($key==0)
                                  <td></td>

                                @foreach($value as $field=>$value)
                                    <td/>{{ $field }}</td>
                                @endforeach
                                  <td></td>
                                  <td></td>
                                  <td></td>

                              @endif

                            @endforeach
                          </tr>
                      </thead>
                      <tbody>
                        @if(count($recalldelivery))

                        <?php $i = 0; ?>
                        @foreach($recalldelivery as $mydeliveries)

                          <tr id="row_{{ $i }}">
                              <td></td>
                              @foreach($mydeliveries as $key=>$value)
                                <td>
                                  {{ $value }}
                                </td>
                              @endforeach
                                  <td></td>
                                  <td></td>
                                  <!-- <td></td> -->

                          </tr>
                          <?php $i++; ?>
                        @endforeach

                        @endif

                    </tbody>
                      <tfoot></tfoot>
                  </table>
              </div>

               <div class="tab-pane" id="stockupdate">
                  <table id="delivery8table" class="display" cellspacing="0" width="100%" padding="30px" style="font-size: 13px;">
                    <thead>
                        {{-- prepare header search textbox --}}
                          <tr>
                            @foreach($stockupdate as $key=>$value)

                              @if ($key==0)
                                  <td></td>

                                @foreach($value as $field=>$value)
                                    <td/>{{ $field }}</td>
                                @endforeach
                                <td></td>


                              @endif

                            @endforeach
                          </tr>
                      </thead>
                      <tbody>
                        @if(count($stockupdate))

                        <?php $i = 0; ?>
                        @foreach($stockupdate as $mydeliveries)

                          <tr id="row_{{ $i }}">
                              <td></td>
                              @foreach($mydeliveries as $key=>$value)
                                <td>
                                  {{ $value }}
                                </td>
                              @endforeach

                                  <td></td>

                          </tr>
                          <?php $i++; ?>
                        @endforeach

                        @endif

                    </tbody>
                      <tfoot></tfoot>
                  </table>
              </div>

              <div class="tab-pane" id="mycanceldelivery">
                  <table id="delivery9table" class="display" cellspacing="0" width="100%" padding="30px" style="font-size: 13px;">
                    <thead>
                        {{-- prepare header search textbox --}}
                          <tr>
                            @foreach($canceldelivery as $key=>$value)

                              @if ($key==0)
                                  <td></td>

                                @foreach($value as $field=>$value)
                                    <td/>{{ $field }}</td>
                                @endforeach
                                  <td></td>
                                  <td></td>
                                  <!-- <td></td> -->

                              @endif

                            @endforeach
                          </tr>
                      </thead>
                      <tbody>
                        @if(count($canceldelivery))

                        <?php $i = 0; ?>
                        @foreach($canceldelivery as $mydeliveries)

                          <tr id="row_{{ $i }}">
                              <td></td>
                              @foreach($mydeliveries as $key=>$value)
                                <td>
                                  {{ $value }}
                                </td>
                              @endforeach
                                  <td></td>
                                  <td></td>
                                  <!-- <td></td> -->

                          </tr>
                          <?php $i++; ?>
                        @endforeach

                        @endif

                    </tbody>
                      <tfoot></tfoot>
                  </table>
              </div>
            </div>
             <!-- Close Tab-Content -->
            </div>
            </div>
      <!--Row-->
      </div>
      <div class="modal fade" id="viewMRModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
          <div class="modal-dialog modal-md" role="document">
              <div class="modal-content">
                  <div class="modal-header">
                      <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                      <h4 class="modal-title" id="myModalLabel">MR</h4>
                  </div>
                  <div class="modal-body">
                      <div class="row">
                          <div class="col-sm-12">
                              <span id="confirm_error" style="color:red;"></span>
                          </div>
                      </div>
                      <div class="row">
                          <div class="col-sm-12">
                              <br>
                              <div class='row'>
                                  <div class="form-group">
                                      <div class="col-sm-12" id="InsertMR">
                                        <table id="mrTable" class="display" cellspacing="0" width="100%" padding="30px" style="font-size: 13px;">
                                          <thead>
                                            <tr>
                                              <th>No</th>
                                              <th>MR NO</th>
                                              <th>Project Name</th>
                                              <th>Status</th>
                                              <th>Action</th>
                                            </tr>
                                          </thead>
                                          <tbody>

                                          </tbody>
                                        </table>
                                      </div>
                                  </div>
                              </div>

                              <br>
                          </div>
                      </div>
                  </div>
                  <div class="modal-footer">
                      <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                  </div>
              </div>
          </div>
      </div>
      <div class="modal fade" id="mrItemModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                    <h4 class="modal-title" id="myModalLabel">Item</h4>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-sm-12">
                            <span id="confirm_error" style="color:red;"></span>
                        </div>
                    </div>
                    <div class="row">
                      <input type="hidden" id='tableNum'>
                        <div class="col-sm-12">
                            <br>
                            <div class='row'>
                                <div class="form-group">
                                    <div class="col-sm-12" id="InsertMR">
                                      <table id="mrItemTable" class="display" cellspacing="0" width="100%" padding="30px" style="font-size: 13px;">
                                        <thead>
                                          <tr>
                                            <th>No</th>
                                            <th>Item Code</th>
                                            <th>Item Description</th>
                                            <th>Additional Description</th>
                                            <th>Quantity</th>
                                            <th><input type='checkbox' id='itemCheckAll' class='itemCheckAll' name='itemCheckAll' onclick='check()'/></th>
                                          </tr>
                                        </thead>
                                        <tbody>

                                        </tbody>
                                      </table>
                                    </div>
                                </div>
                            </div>

                            <br>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button class="btn btn-primary" onclick='importMPSB()'>Import</button>
                    <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </div>
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

<script type="text/javascript">
var files = {!! json_encode($filesByGroup) !!};
function getRowFiles(id) {
  return files[id];
}
 $(function () {
    $( "#upload_form" ).submit(function( event ) {
      event.preventDefault();
      return false;
    });
    $( "#terminate_form" ).submit(function( event ) {
      event.preventDefault();
      return false;
    });
    $( "#delivery5table" ).on('submit', "form", function() {
      //do something
      event.preventDefault();
      return false;
    });
    //Date picker
    $('#Date').datepicker({
      autoclose: true,
        format: 'dd-M-yyyy',
    });
    $('#offhire').datepicker({
      autoclose: true,
        format: 'dd-M-yyyy',
    });
    //Date picker
    $('#pickupdate').datepicker({
      autoclose: true,
        format: 'dd-M-yyyy',
    });
    $("#truck_row").hide();
    $(".select2").select2();
    $(".select2tag").select2({
          tags: true
        });
    /* initialize the calendar
     -----------------------------------------------------------------*/
    //Date for the calendar events (dummy data)
    var date = new Date();
    var d = date.getDate(),
        m = date.getMonth(),
        y = date.getFullYear();
    $('#calendar').fullCalendar({
      header: {
        left: 'prev,next today',
        center: 'title',
        right: 'listWeek,month,agendaWeek,agendaDay'
      },
      defaultView: 'month',
      buttonText: {
        list:  'List',
        today: 'Today',
        month: 'Month',
        week:  'Week',
        day:   'Day',
      },
      //Random default events
      events: [
       @foreach($holidays as $holiday)
            {
              title: "{{ $holiday->Holiday}}",
              start: new Date("{{date(DATE_ISO8601, strtotime($holiday->Start_Date))}}"),
              description: "It's {{$holiday->Holiday}} ! Have a pleasant holiday",
              end: new Date("{{ date(DATE_ISO8601, strtotime("+1 day", strtotime($holiday->End_Date)))}}"),
              allDay: true,
                backgroundColor: "#848484", //gray
                borderColor: "#848484" //gray
            },
        @endforeach
        @foreach($vehicleevent as $vehicle)
            {
              title: "{{ $vehicle->Vehicle_No }}-{{ $vehicle->Event }}",
              start: new Date("{{date(DATE_ISO8601, strtotime($vehicle->Start_Date))}}"),
              end: new Date("{{ date(DATE_ISO8601, strtotime("+1 day",strtotime($vehicle->End_Date)))}}"),
              description: "{{$vehicle->Vehicle_No}} is not available due to {{$vehicle->Event}}",
              allDay: true,
                backgroundColor: "#FF33D7", //purple
                borderColor: "#FF33D7" //purple
            },
        @endforeach
      @foreach($showdelivery as $schedule)
            @if(strpos($schedule->Status,"Pending")!==false || strpos($schedule->Status,"Processing")!==false || strpos($schedule->Status,"Accepted")!==false || strpos($schedule->Status,"Insufficient Stocks")!==false)
            {
              title: "{{ $schedule->Requestor }} - {{ $schedule->Lorry }}",
              start: new Date("{{date(DATE_ISO8601, strtotime($schedule->Delivery_Date))}}"),
              description:
              "<p><b>Requestor</b>: {{$schedule->Requestor}}</p><p><b>Lorry</b>: {{$schedule->Lorry}}</p><p><b>Driver</b>: {{$schedule->Driver}}</p><p><b>Delivery Date</b>: {{$schedule->Delivery_Date}}</p><p><b>Status</b>: {{$schedule->Status}}</p><p><b>Project</b>: {{$schedule->Project_Name}}</p><p><b>Site</b>: {{$schedule->Location_Name}}</p><p><b>Lat/Long: </b><a data-toggle='modal' data-target='#mapsViewModal' onclick='myfunction({{$schedule->latitude}},{{$schedule->longitude}})' href='#'  target='_blank'>{{$schedule->longitude}},{{$schedule->latitude}}</a></p><p><b>Purpose</b>: {{$schedule->Option}}</p><p><b>Application Date</b>: {{$schedule->created_at}}</p>",
              allDay: true,
              @if(strpos($schedule->Status,"Accepted")!==false)
                backgroundColor: "#00a65a", //green
                borderColor: "#00a65a" //green
              @elseif(strpos($schedule->Status,"Insufficient Stocks")!==false)
                backgroundColor: "#dd4b39", //red
                borderColor: "#dd4b39" //red
              @elseif(strpos($schedule->Status,"Pending")!==false)
                backgroundColor: "#f39c12", //yellow
                borderColor: "#f39c12" //yellow
              @else
                backgroundColor: "#00FF00", //lime
                borderColor: "#00FF00" //lime
              @endif
            },
            @endif
        @endforeach
      ],
      eventRender: function(event, eventElement) {
          if (event.imageurl) {
              eventElement.find("div.fc-content").prepend("<img src='" + event.imageurl +"' width='20' height='20'>");
          }
      },
      eventClick:  function(event, jsEvent, view) {
        // console.log(event)
            $('#calendarmodalTitle').html(event.title);
            $('#calendarmodalBody').html(event.description);
            $('#calendarModal').modal();
    },
      editable: false,
      droppable: false, // this allows things to be dropped onto the calendar !!!
    });
  });

  function OpenRecallDialog(id)
  {
    var hiddeninput='<input type="hidden" class="form-control" id="recalldeliveryid" name="recalldeliveryid" value="'+id+'">';
     $( "#recalldelivery" ).html(hiddeninput);
     $('#Recall').modal('show');
  }
  function CancelDialog(id)
  {
    var hiddeninput='<input type="hidden" class="form-control" id="canceldeliveryid" name="canceldeliveryid" value="'+id+'">';
      $("#canceldelivery" ).html(hiddeninput);
      $('#Cancel').modal('show');
  }
    function ResubmitDialog(id, status)
  {
    var hiddeninput='<input type="hidden" class="form-control" id="resubmitdeliveryid" name="resubmitdeliveryid" value="'+id+'">';
      $("#resubmitdelivery" ).html(hiddeninput);
      $('#Resubmit').modal('show');
  }
  function OpenDeleteDialog(id)
  {
    var hiddeninput='<input type="hidden" class="form-control" id="deletedeliveryid" name="deletedeliveryid" value="'+id+'">';
     $( "#deletedelivery" ).html(hiddeninput);
     $('#Delete').modal('show');
  }
  function deletedelivery() {
      $.ajaxSetup({
         headers: { 'X-CSRF-Token' : $('meta[name=_token]').attr('content') }
      });
      $("#ajaxloader").show();
      deliveryid=$('[name="deletedeliveryid"]').val();
      $.ajax({
                  url: "{{ url('/mydeliveryrequest/delete') }}",
                  method: "POST",
                  data: {Id:deliveryid},
                  success: function(response){
                    if (response==1)
                    {
                        // deliverytable.ajax.url("{{ asset('/Include/delivery.php') }}").load();
                        deliverytable.ajax.reload();
                        delivery2table.ajax.reload();
                        delivery3table.ajax.reload();
                        delivery4table.ajax.reload();
                        delivery5table.ajax.reload();
                        delivery6table.ajax.reload();
                        var message="Delivery Deleted!";
                        $('#Delete').modal('hide');
                        $("#update-alert ul").html(message);
                        $("#update-alert").modal('show');
                        $("#ajaxloader").hide();
                    }
                    else {
                      var errormessage="Failed to Recall Delivery!";
                      $('#Delete').modal('hide');
                      $("#error-alert ul").html(errormessage);
                      $("#error-alert").modal('show');
                      $("#ajaxloader").hide();
                    }
          }
      });
  }
  function recalldelivery() {
      $.ajaxSetup({
         headers: { 'X-CSRF-Token' : $('meta[name=_token]').attr('content') }
      });
      $("#ajaxloader").show();
      deliveryid=$('[name="recalldeliveryid"]').val();
      $.ajax({
                  url: "{{ url('/mydeliveryrequest/recall') }}",
                  method: "POST",
                  data: {Id:deliveryid},
                  success: function(response){
                    if (response==1)
                    {
                        // deliverytable.ajax.url("{{ asset('/Include/delivery.php') }}").load();
                        deliverytable.ajax.reload();
                        delivery2table.ajax.reload();
                        delivery3table.ajax.reload();
                        delivery4table.ajax.reload();
                        delivery5table.ajax.reload();
                        delivery6table.ajax.reload();
                        var message="Delivery Recalled!";
                        $('#Recall').modal('hide');
                        $("#update-alert ul").html(message);
                        $("#update-alert").modal('show');
                        $("#ajaxloader").hide();
                    }
                    else {
                      var errormessage="Failed to Recall Delivery!";
                      $('#Recall').modal('hide');
                      $("#error-alert ul").html(errormessage);
                      $("#error-alert").modal('show');
                      $("#ajaxloader").hide();
                    }
          }
      });
  }
  function canceldelivery() {
      $.ajaxSetup({
         headers: { 'X-CSRF-Token' : $('meta[name=_token]').attr('content') }
      });
      deliveryid=$('[name="canceldeliveryid"]').val();
      var x = document.getElementById("cancelreason").value;
      if(x=="" || x==null)
      {
        alert('Reason should not be empty');
      }
      else{
        $("#ajaxloader5").show();
      $.ajax({
                  url: "{{ url('/mydeliveryrequest/cancel') }}",
                  method: "POST",
                  data: {Id:deliveryid, Reason:x},
                  success: function(response){
                    if (response==1)
                    {
                      deliverytable.ajax.reload();
                      delivery2table.ajax.reload();
                      delivery3table.ajax.reload();
                      // delivery4table.ajax.reload();
                      // delivery5table.ajax.reload();
                      // delivery6table.ajax.reload();
                      // delivery7table.ajax.reload();
                      // delivery8table.ajax.reload();
                      delivery9table.ajax.reload();
                      var message="Delivery Cancel Request Sent!";
                      $("#update-alert ul").html(message);
                      $("#update-alert").modal('show');
                      // setTimeout(function() {
                      //   $("#update-alert").fadeOut();
                      // }, 6000);
                      $('#Cancel').modal('hide');
                      $("#ajaxloader5").hide();
                    }
                    else {
                      var errormessage="Failed to Cancel Delivery!";
                      $("#error-alert ul").html(errormessage);
                      $("#error-alert").modal('show');
                      // setTimeout(function() {
                      //   $("#error-alert").fadeOut();
                      // }, 6000);
                      $('#Cancel').modal('hide');
                      $("#ajaxloader5").hide();
                    }
          }
      });
    }
  }
  $('#btnapplydelivery').click(function(){
          $("#ajaxloader4").show();
  });
  $('#terminatereq').click(function(){
    //set here
      $('#terminateSO').modal('show');
  });
  $(document).ready(function() {
    $(document).on('click', '#dummy', function(e) {
      $("#dummy").prop('disabled',true);
        $.ajaxSetup({
         headers: { 'X-CSRF-Token' : $('meta[name=_token]').attr('content') }
      });

        $.ajax({
            url: "{{ url('/mydeliveryrequest/dummyDO') }}",
            method: "POST",
            contentType: false,
            processData: false,
            data:new FormData($("#upload_form")[0]),
            success: function(response){
              $("#dummy").prop('disabled',false);
                if(response == 1)
                {
                  var message="Dummy DO Has Been Created!";
                  $("#update-alert ul").html(message);
                  $("#update-alert").modal('show');
                  window.location.href="{{url('/mydeliveryrequest')}}";
                }
                else
                {
                  var errormessage="Failed to Create Dummy DO!";
                  $("#error-alert ul").html(errormessage);
                  $("#error-alert").modal('show');
                }
              }
          });

    });
  });
$(document).ready(function() {
    $(document).on('click', '#terminate', function(e) {
      $("#ajaxloader5").show();
      $("#terminate").prop('disabled',true);
      var file = $('#terminatedoc').val();
      var offhire = $('#offhire').val();
      var id = $('#salesorderid').val();
      var that =  $("#terminate_form");
    $.ajaxSetup({
         headers: { 'X-CSRF-Token' : $('meta[name=_token]').attr('content') }
      });
    if(file != "" && offhire != "" && id != "")
    {
      $.ajax({
            url: "{{ url('/mydeliveryrequest/apply') }}",
            method: "POST",
            contentType: false,
            processData: false,
            data:new FormData($("#upload_form")[0]),
            success: function(response){
              $("#terminate").prop('disabled',false);
                if(response == 1)
                {
                  $.ajax({
                  url: "{{ url('/salesorderterminate/') }}" +"/"+ id,
                  method: "POST",
                  data: new FormData($("#terminate_form")[0]),
                  contentType: false,
                  processData: false,
                  success: function(response){
                    $('#terminateSO').modal('hide');
                      if(response == 1)
                      {
                        var message="Sales Order has been successfully terminated";
                        $("#update-alert ul").html(message);
                        $("#update-alert").modal('show');
                        $("#ajaxloader5").hide();
                        window.location.reload();
                      }
                       else
                      {
                          var message="Error when terminating the Sales Order";
                          $("#error-alert ul").html(message);
                          $("#error-alert").modal('show');
                          $('#terminateSO').modal('hide');
                          $("#ajaxloader5").hide();
                      }
                    }
                  });
                }
                else
                {
                        $('html, body').animate({scrollTop: '0px'}, 500);
                        // var stringified = JSON.stringified(response)
                        // console.log(response)
                        var obj = jQuery.parseJSON(response);
                        // console.log(obj);
                        var errormessage ="";
                        for (var item in obj) {
                          errormessage=errormessage + "<li> " + obj[item] + "</li>";
                        }
                        $("#error-alert ul").html(errormessage);
                        $("#error-alert").modal('show');
                        $('#terminateSO').modal('hide');
                        $("#ajaxloader5").hide();
                }
            }
      });
    }
    else
    {
      alert('Offhire date and supporting documents needed');
      $("#terminate").prop('disabled',false);
    }
  });
  });
  function applydelivery() {
      $("#ajaxloader4").show();
      $("#btnapplydelivery").prop('disabled',true);
      $.ajaxSetup({
         headers: { 'X-CSRF-Token' : $('meta[name=_token]').attr('content') }
      });
      $.ajax({
                  url: "{{ url('/mydeliveryrequest/apply') }}",
                  method: "POST",
                  contentType: false,
                  processData: false,
                  data:new FormData($("#upload_form")[0]),
                  success: function(response){
                    $("#btnapplydelivery").prop('disabled',false);
                    if (response==1)
                    {
                        $('html, body').animate({scrollTop: '0px'}, 500);
                        deliverytable.ajax.reload();
                        document.getElementById("Date").value = ''
                        document.getElementById("time").value = ''
                        document.getElementById("pickupdate").value = ''
                        document.getElementById("pickup").value = ''
                        $("#lorry").val("").change();
                        $("#project").val("").change();
                        $("#destination").val("").change();
                        $("#client").val("").change();
                        // document.getElementById("client").value = ''
                        // $("#projtype").val("").change();
                        // document.getElementById("#projtype").value = ""
                        $("#condition").val("").change();
                        $("#note").val("").change();
                        $("#PICname").val("").change();
                        $("#PICcontact").val("").change();
                        // document.getElementById("PICname").value = ''
                        // document.getElementById("PICcontact").value = ''
                        document.getElementById("Remarks").value = ''
                        document.getElementById("po").value = ''
                        document.getElementById("term").value = ''
                        $("#item").val("").change();
                        document.getElementById("xquantity").value = ''
                        $("#purpose").val("").change();
                        $('#itemtable > tbody').empty();
                        $("#ajaxloader4").hide();
                        var message="Delivery Application Submitted!";
                        $("#update-alert ul").html(message);
                        $("#update-alert").modal('show');
                        $(".section").not(':first-child').remove();
                        $(".condition").not(':first-child').remove();
                        $(".note").not(':first-child').remove();
                        // $("#error-alert").hide();
                          if($("#flow").val() == "exchange")
                          {
                            window.location.href="/mydeliveryrequest/{{$trackerid}}/exchangedelivery" ;
                          }
                          else{
                          window.location.href="{{url('/mydeliveryrequest')}}";
                          }
                    }
                    else {
                        $('html, body').animate({scrollTop: '0px'}, 500);
                        $("#error-alert ul").html(response);
                        $("#error-alert").modal('show');
                        $("#ajaxloader4").hide();
                    }
          }
      });
  }
    function viewItemList(formId) {
      $.ajaxSetup({
         headers: { 'X-CSRF-Token' : $('meta[name=_token]').attr('content') }
      });
      $.ajax({
          url: "{{ url("/fetchItemList/") }}" + "/" + formId,
          method: "GET",
          success: function(response){
            $('#itemlisttable > tbody').empty();
            response.Item.forEach(function(element) {
                $('#itemlisttable > tbody').append(`<tr>
                  <td>${element.Item_Code}</td>
                  <td>${element.Description}</td>
                  <td>${element.add_desc}</td>
                  <td>${element.Unit}</td>
                  <td>${element.Option}</td>
                  <td>${element.Qty_request}</td>
                </tr>`);
              // }
            });
          },
          error: function(response){
          }
      });
    }
     function editItemList(formId) {
      // console.log(formId);
      editlisttable.button('0').enable();
      editlisttable.ajax.url("{{ url('/Include/deliveryitem.php') }}" + "?formId=" + formId);
      editlisttable.ajax.reload();
      itemeditor.field('deliveryitem.formId').def(formId);
      $('#editlisttable').on( 'click', 'tbody td', function (e) {
        itemeditor.inline( this, {
          // submit: 'allIfChanged'
          onBlur: 'submit'
        } );
        } );
    }
    function editItemList2(formId) {
      editlisttable.ajax.url("{{ url('/Include/deliveryitem.php') }}" + "?formId=" + formId);
      editlisttable.ajax.reload();
      $('#editlisttable').off('click', 'tbody td');
      editlisttable.button('0').disable();
    }
    function updateItemList(formId) {
      updatelisttable.ajax.url("{{ url('/Include/deliveryitem.php') }}" + "?formId=" + formId);
      updatelisttable.ajax.reload();
      delivery5editor.field('deliveryitem.formId').def(formId);
    }
    function Resubmit(formId) {
      $.ajaxSetup({
         headers: { 'X-CSRF-Token' : $('meta[name=_token]').attr('content') }
      });
      $("#ajaxloader2").show();
      deliveryid=$('[name="resubmitdeliveryid"]').val();
      $.ajax({
                  url: "{{ url('/mydeliveryrequest/resubmit') }}",
                  method: "POST",
                  data: {Id:deliveryid},
                  success: function(response){
                    if (response==1)
                    {
                      deliverytable.ajax.reload();
                      delivery2table.ajax.reload();
                      delivery3table.ajax.reload();
                      delivery4table.ajax.reload();
                      delivery5table.ajax.reload();
                      delivery6table.ajax.reload();
                      delivery7table.ajax.reload();
                      var message="Delivery Resubmitted!";
                      $("#update-alert ul").html(message);
                      $("#update-alert").modal('show');
                      // setTimeout(function() {
                      //   $("#update-alert").fadeOut();
                      // }, 6000);
                      $('#Resubmit').modal('hide');
                      $("#ajaxloader2").hide();
                    }
                    else {
                      var errormessage="Failed to Resubmit Delivery!";
                      $("#error-alert ul").html(errormessage);
                      $("#error-alert").modal('show');
                      // setTimeout(function() {
                      //   $("#error-alert").fadeOut();
                      // }, 6000);
                      $('#Resubmit').modal('hide');
                      $("#ajaxloader2").hide();
                    }
          }
      });
  }
  // $(document).ready(function (e) {
  //   $('#uploadphoto').on('submit',(function(e) {
  //       e.preventDefault();
  //       $.ajaxSetup({
  //        headers: { 'X-CSRF-Token' : $('meta[name=_token]').attr('content') }
  //     });
  //       var formData = new FormData(this);
  //       $.ajax({
  //           type:'POST',
  //           url: "{{ url('/mydeliveryrequest/upload') }}",
  //           data:formData,
  //           cache:false,
  //           contentType: false,
  //           processData: false,
  //           success:function(response){
  //               if (response==1)
  //                   {
  //                     deliverytable.ajax.reload();
  //                     delivery2table.ajax.reload();
  //                     delivery3table.ajax.reload();
  //                     delivery4table.ajax.reload();
  //                     delivery5table.ajax.reload();
  //                     delivery6table.ajax.reload();
  //                     var message="Photo Submitted!";
  //                     $("#update-alert ul").html(message);
  //                     $("#update-alert").modal('show');
  //                   }
  //                   else {
  //                     var errormessage="Failed to Photo!";
  //                     $("#error-alert ul").html(errormessage);
  //                     $("#error-alert").modal('show');
  //                   }
  //                 }
  //       });
  //   }));
  // });
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
            url: "{{ url('mydeliveryrequest/removeupload') }}", // point to server-side PHP script
            cache: false,
            data: {Id: id},
            type: 'POST',
            success: function (response) {
                for (var i = files[parentid].length - 1; i >= 0; --i) {
                    if (files[parentid][i].Id == id) {
                        files[parentid].splice(i,1);
                    }
                }
                delivery5table.row('#'+parentid).invalidate().draw();
            },
            error: function (response) {
            }
        });
    }
  function upload(uploadformid, filesParentId) {
      $.ajaxSetup({
         headers: { 'X-CSRF-Token' : $('meta[name=_token]').attr('content') }
      });
      // var formData = new FormData(this);
      $.ajax({
                  url: "{{ url('/mydeliveryrequest/upload') }}",
                  method: "POST",
                  data: new FormData($(uploadformid)[0]),
                  cache:false,
                  contentType: false,
                  processData: false,
                  success: function(response){
                      // console.log(filesParentId)
                      var newFiles = response.split(',');
                      newFiles.forEach(function(file) {
                        var arr = file.split('|');
                        var fileId = arr[0];
                        var filePath = arr[1];
                        var fileName = arr[2];
                        if (files[filesParentId] === undefined) {
                          files[filesParentId] = [];
                        }
                        files[filesParentId].push({
                          Id: fileId,
                          Web_Path: filePath,
                          TargetId: filesParentId
                        });
                      });
                      deliverytable.ajax.reload();
                      delivery2table.ajax.reload();
                      delivery3table.ajax.reload();
                      delivery4table.ajax.reload();
                      delivery5table.ajax.reload();
                      delivery6table.ajax.reload();
                      var message="Photo Submitted!";
                      $("#update-alert ul").html(message);
                      $("#update-alert").modal('show');
                },
                error: function (response) {
                  var errormessage="Failed to Upload Photo!";
                  $("#error-alert ul").html(errormessage);
                  $("#error-alert").modal('show');
          }
      });
  }
// Clone Sections
// define template
var template = $('#sections .section:first').clone(true);
// define counter
var sectionsCount = 0;
// add new section
$('body').on('click', '.addsection', function() {
    //increment
    sectionsCount++;
    //loop through each input
    var section = template.clone(true).find(':input').each(function(){
        this.name = "section["+sectionsCount+"][" + this.id + "]";
        //set id to store the updated section number
        if ((this.id + sectionsCount == 'condition'+sectionsCount) || (this.id + sectionsCount == 'note'+sectionsCount)) {
          this.name = "section["+sectionsCount+"][" + this.id + "][]";
        }
        var newId = this.id + sectionsCount;
        //update id
        this.id = newId;
        $("#project"+sectionsCount).select2("destroy");
        $("#destination"+sectionsCount).select2("destroy");
        $("#client"+sectionsCount).select2("destroy");
        $("#purpose"+sectionsCount).select2("destroy");
        // $("#projtype"+sectionsCount).select2("destroy");
        $("#item"+sectionsCount).select2("destroy");
        $("#Description"+sectionsCount).select2("destroy");
        $("#note"+sectionsCount).select2("destroy");
        $("#condition"+sectionsCount).select2("destroy");
        $("#PICname"+sectionsCount).select2("destroy");
        $("#PICcontact"+sectionsCount).select2("destroy");
    }).end()
    .find('table').each(function(){
        //set id to store the updated section number
        var newId = this.id + sectionsCount;
        //update id
        this.id = newId;
    }).end()
    .find('.conditionsection').each(function () {
      // console.log(this);
      var newId = this.id + sectionsCount;
        //update id
        this.id = newId;
    }).end()
    .find('.initialconditionsection').each(function () {
      // console.log(this);
      var newId = this.id + sectionsCount;
        //update id
        this.id = newId;
    }).end()
    .find('.notesection').each(function () {
      // console.log(this);
      var newId = this.id + sectionsCount;
        //update id
        this.id = newId;
    }).end()
    .find('.initialnotesection').each(function () {
      // console.log(this);
      var newId = this.id + sectionsCount;
        //update id
        this.id = newId;
    }).end()
    //inject new section
    .appendTo('#sections');
    $("#project"+sectionsCount).select2();
    $("#destination"+sectionsCount).select2();
    $("#client"+sectionsCount).select2();
    $("#purpose"+sectionsCount).select2();
    // $("#projtype"+sectionsCount).select2();
    $("#item"+sectionsCount).select2();
    $("#Description"+sectionsCount).select2();
    $("#note"+sectionsCount).select2({
          tags: true
        });
    $("#condition"+sectionsCount).select2({
          tags: true
        });
    // return false;
    $("#PICname"+sectionsCount).select2({
          tags: true
        });
    // return false;
    $("#PICcontact"+sectionsCount).select2({
          tags: true
        });
    // return false;
});
//remove section
$('#sections').on('click', '.remove', function() {
    //fade out section
    $(this).closest('.section').fadeOut(300, function(){
        //remove parent element (main section)
        $(this).closest('.section').empty();
        return false;
    });
    return false;
});
  function Add(element) {
          var tblId = $(element).closest('table').attr('id');
          //get table id number
          var numId = parseInt(tblId.match(/\d+/));
          //To set the numId empty
          if (isNaN(numId)) {
            numId = '';
          }
          // console.log("#item"+numId);
            AddRow(numId, tblId, $("#item"+numId+' :selected').text(), $("#item"+numId).val(), $("#Description"+numId+' :selected').text(),$("#add_desc"+numId).val(),$("#Unit"+numId).val(),$("#xquantity"+numId).val());
              // ,$("#purpose"+numId).val(),$("#purpose"+numId+ ' :selected').text());
            $("#item"+numId).val("").change();
            $("#Description"+numId).val("");
            $("#add_desc"+numId).val("");
            $("#Unit"+numId).val("");
            $("#xquantity"+numId).val("");
            // $("#purpose"+numId).val("");
        };
        function AddRow(numId, tblId, label, item, Description, Additional_Description,Unit,quantity,purpose,mr) {
            if (isNaN(numId) || numId == '') {
              numId = 0;
              // console.log(123,numId);
              // console.log(numId);
            }
            var m="";
            if(mr != undefined){
              m="<input type='hidden' name='section["+numId+"][mr][]' value='"+mr+"'>";
            }
            //Get the reference of the Table's TBODY element.
            var tBody = $("#" + tblId + " > TBODY")[0];
            //Add Row.
            row = tBody.insertRow(-1);
            //Add Item cell.
            var cell = $(row.insertCell(-1));
            cell.html(label + "<input type='hidden' name='section["+numId+"][item][]' value='"+item+"'>" + m);
            //Add Description cell.
            cell = $(row.insertCell(-1));
            console.log(Description)
            cell.html(Description);
            //Add Additional Description cell.
            cell = $(row.insertCell(-1));
            cell.html(Additional_Description + "<input type='hidden' name='section["+numId+"][Additional_Description][]' value='"+Additional_Description+"'>");
            //Add Unit cell.
            cell = $(row.insertCell(-1));
            cell.html(Unit);
             //Add Quantity cell.
            cell = $(row.insertCell(-1));
            cell.html(quantity + "<input type='hidden' name='section["+numId+"][quantity][]' value='"+quantity+"'>");
            // cell = $(row.insertCell(-1));
            // cell.html(label2 + "<input type='hidden' name='section["+numId+"][purpose][]' value='"+purpose+"'>");
            //Add Button cell.
            cell = $(row.insertCell(-1));
            var btnRemove = $("<input />");
            btnRemove.attr("type", "button");
            btnRemove.attr("class","btn btn-danger");
            btnRemove.attr("onclick", "Remove(this);");
            btnRemove.val("Remove");
            cell.append(btnRemove);
        };
        function Remove(button) {
            //Determine the reference of the Row using the Button.
            var row = $(button).closest("TR");
            var name = $("TD", row).eq(0).html();
            if (confirm("Do you want to delete: " + name)) {
                $(row).remove();
                //Get the reference of the Table.
                // var table = $("#itemtable")[0];
                //Delete the Table row using it's Index.
                // table.deleteRow(row[0].rowIndex);
            }
        };
         var i=1;
        $(document).ready(function() {
         $(document).on('click','.addcondition',function(e){
        var tblId = $(this).closest(':input').attr('id');
        // console.log(tblId)
        var numId = parseInt(tblId.match(/\d+/));
          //To set the numId empty
          if (isNaN(numId)) {
            numId = '';
          }
          // console.log($("#initialcondition").clone())
        if (numId != "") {
        $("select[name='section["+numId+"][condition][]']").select2('destroy')
          var dom = $("#initialcondition"+numId).children().clone(true);
          dom.find('select').attr('name','section['+numId+'][condition][]');
        } else {
          $("select[name='section[0][condition][]']").select2('destroy')
          var dom = $("#initialcondition").children().clone(true);
          dom.find('select').attr('name','section[0][condition][]');

        }
        var btnRemove = $("<input />");
            btnRemove.attr("type", "button");
            btnRemove.attr("class","removecondition btn btn-sm");
            btnRemove.val("Remove");
        dom.append(btnRemove);
        dom.appendTo("#conditionsection"+numId);
        if (numId != "") {
          $("select[name='section["+numId+"][condition][]']").select2({tags:true})
        } else {
          $("select[name='section[0][condition][]']").select2({tags:true})
        }
        i++;
        // var btnRemove = $("<input />");
        //     btnRemove.attr("type", "button");
        //     btnRemove.attr("class","removecondition btn btn-danger");
        //     btnRemove.val("Remove");
        // $("#removeconditionsection"+numId).append(btnRemove);
    });
});
        $(document).on('click','.removecondition',function(e) {
          // alert(123);
          // console.log($(this).parent().next('.condition'))
            var el = $(this).parent().next('.condition');
            $(el).select2('destroy')
            $(this).parent().next().remove();
            $(this).parent().remove();

        });
        $(document).ready(function() {
         $(document).on('click','.addnote',function(e){
        var tblId = $(this).closest(':input').attr('id');
        // console.log(tblId)
        var numId = parseInt(tblId.match(/\d+/));
          //To set the numId empty
          if (isNaN(numId)) {
            numId = '';
          }
          // console.log($("#initialcondition").clone())
        if (numId != "") {
        $("select[name='section["+numId+"][note][]']").select2('destroy')
          var dom = $("#initialnote"+numId).children().clone(true);
          dom.find('select').attr('name','section['+numId+'][note][]');
        } else {
          $("select[name='section[0][note][]']").select2('destroy')
          var dom = $("#initialnote").children().clone(true);
          dom.find('select').attr('name','section[0][note][]');

        }
        var btnRemove = $("<input />");
            btnRemove.attr("type", "button");
            btnRemove.attr("class","removenote btn btn-sm");
            btnRemove.val("Remove");
        dom.append(btnRemove);
        dom.appendTo("#notesection"+numId);
        if (numId != "") {
          $("select[name='section["+numId+"][note][]']").select2({tags:true})
        } else {
          $("select[name='section[0][note][]']").select2({tags:true})
        }
        i++;
        // var btnRemove = $("<input />");
        //     btnRemove.attr("type", "button");
        //     btnRemove.attr("class","removecondition btn btn-danger");
        //     btnRemove.val("Remove");
        // $("#removeconditionsection"+numId).append(btnRemove);
    });
});
        $(document).on('click','.removenote',function(e) {
          // alert(123);
          // console.log($(this).parent().next('.note'))
            var el = $(this).parent().next('.note');
            $(el).select2('destroy')
            $(this).parent().next().remove();
            $(this).parent().remove();

        });
        $(document).on('click','.removenote',function(e) {
          // alert(123);
          // console.log($(this).parent().next('.note'))
            var el = $(this).parent().next('.note');
            $(el).select2('destroy')
            $(this).parent().next().remove();
            $(this).parent().remove();

        });
        $(document).ready(function() {
        $(document).on('change', '.Item', function(e) {
        var element = $(this).find('option:selected');
        var tblId = $(this).closest('table').attr('id');
        var numId = parseInt(tblId.match(/\d+/));
          //To set the numId empty
          if (isNaN(numId)) {
            numId = '';
          }
        // console.log(numId)
        var Id = $(this).val();
        console.log(Id)
        var unit = element.data("unit");
         $('#Unit'+numId).val(unit);
         var desc = element.data("description");
         if($('#Description'+numId).val()!= Id){
         $('#Description'+numId).val(Id).change();
          }
         e.stopPropagation();
    });
});
$(document).ready(function() {
    $(document).on('change', '.Description', function(e) {
        var element = $(this).find('option:selected');
        var tblId = $(this).closest('table').attr('id');
        var numId = parseInt(tblId.match(/\d+/));
          //To set the numId empty
          if (isNaN(numId)) {
            numId = '';
          }
        // console.log(numId)
        var Id = $(this).val();
         var code = element.data("code");
         var unit = element.data("unit");
         $('#Unit'+numId).val(unit);
         if($('#item'+numId).val()!=Id){
         $('#item'+numId).val(Id).change();
        }
         e.stopPropagation();
    });
});
$(document).ready(function() {
    $(document).on('change', '.lorry', function() {
        // var itemId = $(this).closest().attr('id');
        var element = $(this).find('option:selected');
         var size = element.data("dimension");
         $('#lorrydimension').val(size);
    });
    // $(document).on('change', '#truck', function() {
    //     var element = $(this).is(":checked");
    //     if(element){
    //       $
    //     }
    //      var size = element.data("dimension");
    //      $('#lorrydimension').val(size);
    // });
});
// $(document).ready(function() {
//     $(document).on('change', '.department', function() {
//         var id = $(this).children(":selected").attr("Id");
//         console.log(id);
//         var cli = element.data("client");
//          $('.client').val(cli);
//     });
// });
// $( ".client" ).hide();
// $('.department').change(function(){
//   var id = $(this).children(":selected").val();
//   if(id == 40){
//     $("#"+$(this).val()).show();
//   }
//   else{
//     $("#other").show();
//   }
// });
$(document).ready(function() {
    $(document).on('change', '.destination', function() {
        // var itemId = $(this).closest().attr('id');
        var element = $(this).find('option:selected');
         // var com = element.data("company");
         // $('.company').val(com);
         // var cli = element.data("client");
         // $('.client').val(cli);
    });
});
  $(document).ready(function(){
    $(document).on('change','.project',function(){
        $.ajaxSetup({
         headers: { 'X-CSRF-Token' : $('meta[name=_token]').attr('content') }
      });
          var projectid=$(this).val();
          var tblId = $(this).closest(':input').attr('id');
          //get table id number
          var numId = parseInt(tblId.match(/\d+/));
          if(isNaN(numId)|| numId==""){
            numId="";
          }
          // console.log(numId);
        var element = $(this).find('option:selected');
        console.log(element);
         var name = element.data("name");
         if(name == "MY_GSC")
         {
            $('#projtype'+numId).val("GSC");
         }
         else if(name == "MY_FAB")
         {
            $('#projtype'+numId).val("FAB");
         }
         else if(name== "MY_GST")
         {
           $('#projtype'+numId).val("GST");
         }
         else if(name== "MY_UM")
         {
           $('#projtype'+numId).val("CME");
           var select = $('#client'+numId);
          var newOptions = {
                          '2' : 'OMNI AVENUE SDN BHD - OASB',
                      };
          $('option', select).remove();
          $.each(newOptions, function(text, key) {
              var option = new Option(key, text);
              select.append($(option));
          });
         }
         else if(name== "MY_SBC")
         {
          $('#projtype'+numId).val("CME");
           var select = $('#client'+numId);
          var newOptions = {
                          '1' : 'MIDASCOM PERKASA SDN BHD - MPSB',
                          '2' : 'OMNI AVENUE SDN BHD - OASB',
                          '3' : 'MIDASCOM NETWORK SDN BHD - MNSB'
                      };
          $('option', select).remove();
          $.each(newOptions, function(text, key) {
              var option = new Option(key, text);
              select.append($(option));
          });
         }
         else if(name== "MY_DIGI")
         {
          $('#projtype'+numId).val("CME");
         var select = $('#client'+numId);
          var newOptions = {
                          '3' : 'MIDASCOM NETWORK SDN BHD - MNSB'
                      };
          $('option', select).remove();
          $.each(newOptions, function(text, key) {
              var option = new Option(key, text);
              select.append($(option));
          });
         }
         else
         {
           $('#projtype'+numId).val("");
         }
          $.ajax
          ({
          type: "GET",
          url: "{{ url('/deliveryrequest/getsite') }}",
          data: {Id:projectid},
          cache: false,
          success: function(response){
            $("#destination"+numId).empty();
            response.site.forEach(function(key){
                if( ((projectid != null && projectid != 142 ) && (projectid != null && projectid != 143 ))|| ((projectid != "" && projectid != 142 ) && (projectid != "" && projectid != 143 )))
                {
                $("#destination"+numId).append("<option value='"+key.Id+"''>"+key.Location_Name+"</option>");
                }
                else{
                  $("#destination"+numId).empty();
                }
            });
            // $("#client"+numId).empty();
            if( projectid == 142 || projectid == 143 || projectid =="" || projectid == null)
              {
                $("#client"+numId).empty();
              }
            response.allclient.forEach(function(val){
              if(projectid == 142 || projectid == 143)
              {
                $("#client"+numId).append("<option value='"+val.Id+"''>"+val.Company_Name +"-"+ val.Company_Code+ "</option>");
              }
            });
            if(projectid == 142 || projectid == 143)
                {
                    alert("Please Select Client First");
                }
        }
      });
  });
    $(document).ready(function(){
    $(document).on('change','.client',function(){
        $.ajaxSetup({
         headers: { 'X-CSRF-Token' : $('meta[name=_token]').attr('content') }
      });
        var tblId = $(this).closest(':input').attr('id');
          //get table id number
          var numId = parseInt(tblId.match(/\d+/));
          if(isNaN(numId)|| numId==""){
            numId="";
          }
        var projectid = $('#project'+numId).val();
        var clientid = $(this).val();
        var length = $('#destination'+numId).children('option').length;
        // console.log(length, projectid, clientid)
          $.ajax
          ({
          type: "GET",
          url: "{{ url('/deliveryrequest/getsite2') }}",
          data: {projectid,clientid},
          cache: false,
          success: function(response){
              if( projectid == 142 || projectid == 143 || projectid =="" || projectid == null)
              {
                $("#destination"+numId).empty();
              }
            response.site.forEach(function(key){
                  if(projectid != "" || projectid != null)
                  {
                 $("#destination"+numId).append("<option value='"+key.Id+"''>"+key.Location_Name+"</option>");
                  }
               });
          }
            });

      });
  });
      $(document).ready(function(){
    $(document).on('change','#lorry',function(){
        $.ajaxSetup({
         headers: { 'X-CSRF-Token' : $('meta[name=_token]').attr('content') }
      });
          var date=$('#Date').val();
          var lorry=$(this).val();
          if($('#truck_check').is(':checked')){

          }else{
            $.ajax
            ({
            type: "GET",
            url: "{{ url('/lorrystatus') }}",
            data: {date:date, lorry:lorry},
            cache: false,
            success: function(response){
                  $('#lorrystatus').empty();
                  $('#lorrystatus').val(response.lorry);
                  alert(response.event);
                  if(response.event == "No Event")
                  {
                  }
                  else{
                  $('#lorry option:selected').remove();
                  $('#lorry').val("");
                  $('#lorrystatus').val("");
                  }
                }
              });
          }
      });
  });
    });
  $(function () {
        $("#checkbox").click(function () {
            if ($(this).is(":checked")) {
                $("#behalf").css('visibility','visible');
                $("#representative").val("").change();
            } else {
                $("#behalf").css('visibility','hidden');
                $("#representative").val("").change();
            }
        });
    });
  function MRModal(element){
    $("#viewMRModal").modal('show');
    let id=$(element).closest('div.form-group').find('table').attr('id');
    $("#tableNum").val(id);
    getMR();
  }
  var mrArray=new Array();
  function MRItemModal(id){
    $("#mrItemModal").modal('show');
    mrItemTable.api().clear().draw();
    $.ajaxSetup({
         headers: { 'X-CSRF-Token' : $('meta[name=_token]').attr('content') }
      });

    $.ajax({
      type: "get",
      url: "{{url('MPSBItem')}}",
      data: {
        id:id,
        arr:mrArray
      },
      success: function (response) {
        for(var x=0,i=response.length;x<i;x++){
          mrItemTable.api().row.add([
            "",response[x].Item_Code+"<input name='item_code'type='hidden' value='"+response[x].Item_Code+"'/><input type='hidden' value='"+response[x].InventoryId+"'name='invId'/>",
            response[x].Description+"<input name='description' type='hidden' value='"+response[x].Description+"'/>"
            ,"<input type='text' class='form-control' name='add_desc'/>",
            response[x].Qty+"<input type='hidden' name='qty' value='"+response[x].Qty+"'/>","<input type='checkbox' class='itemCheck' name='itemCheck' value='"+response[x].Id+"'>"
          ]).draw(false);
        }

      }
    });
  }
  function check(index){
    var allPages = mrItemTable.fnGetNodes();
    if ($("#itemCheckAll").is(':checked')){
        $('.itemCheck', allPages).prop('checked', true);
        $(".itemCheck").trigger("change");
        mrItemTable.api().rows().select();
    }else{
        $('.itemCheck', allPages).prop('checked', false);
        $(".itemCheck").trigger("change");
        mrItemTable.api().rows().deselect();
    }
  }

  function importMPSB(){
    var tblId=$("#tableNum").val();
    var numId = parseInt(tblId.match(/\d+/));
    var allPages = mrItemTable.fnGetNodes();
    $(allPages).each(function(i,row){
      var row=$(row);
      var code=row.find('input[name="item_code"]').val();
      var description=row.find('input[name="description"]').val();
      var qty=row.find('input[name="qty"]').val();
      var add=row.find('input[name="add_desc"]').val();
      var invId=row.find('input[name="invId"]').val();
      checkbox=row.find('.itemCheck:checkbox:checked');
      checkbox.each(function(i,checkb){
        mrArray.push($(this).val());
        AddRow(numId, tblId, code, invId,
        description,add,"unit",qty,"",$(this).val());
      })
    });
    $("#mrItemModal").modal('hide');
    getMR();
  }
  function getMR(){
    mrTable.api().clear().draw();
    var tblId=$("#tableNum").val();
    var numId = parseInt(tblId.match(/\d+/));
    if(isNaN(numId))
      numId=0;
    var site=$("select[name='section["+numId+"][destination]']").find(':selected').text();
    $("#tableNum").val()
    $.ajax({
      type: "get",
      url: "{{url('getMR')}}",
      data: {
        arr:mrArray,
        site:site
      },
      success: function (response) {
          for(let x=0,i=response.length;x<i;x++){
            mrTable.api().row.add([
              "",response[x].MR_No,response[x].Project_Name,response[x].Status,response[x].total>0 ? "<a style='width:unset;' class='btn btn-xs btn-primary' onclick='MRItemModal("+response[x].Id+")'><i class='fa fa-plus' aria-hidden='true'></i></a>":"-"
            ]).draw(false);
          }
      }
    });
  }
  function checkTruck(){
    let check=$("#truck_check").is(':checked');
    if(check){
      // $("#truck_row").show();
      // $("#truck_row").css({
      //   'position': 'static',
      //   'opacity': 1,
      // });
      $("#truck_row").show();
      $("#truck_row").css('visibility','visible');
      $("#lorry_row").hide();
      $("#dimension_row").hide();
      $("#status_row").hide();
      $("#lorry").val(0).change();

    }else{
      $("#truck_row").hide();
      $("#lorry_row").show();
      $("#dimension_row").show();
      $("#status_row").show();
      $("#truck").val("").change();
    }
  }
</script>
@endsection
