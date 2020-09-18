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
      /*a.buttons-collection {
        margin-left: 1em;
      }
      .container1{
              width: 1200px;
              margin-left: 50px;
              padding: 10px;
      }*/

      #map{
    	height: 300px;
    	/*width:530px;*/
    	margin: 0 auto;
    }
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

      .yellow {
        color: #f39c12;
      }

      .red{
        color:red;
      }

      .claimtable{
        text-align: center;
      }

      .claimheader{
        background-color: gray;
      }
      .claimbox{
        width: 1000px;
      }

      .weekend {
        color: red;
      }

      .weekendrow.even {
        background-color: #FADBD8;
      }

      table.dataTable.display tbody tr.weekendrow.odd {
        background-color: #FADBD8;
      }

      #table-wrapper {
        position:relative;
      }
      #table-scroll {
        height:150px;
        overflow:auto;
        margin-top:20px;
      }
      #table-wrapper table {
        width:100%;

      }
      .buttonclaim img{
        width:40px;
      }

      .modal-dialog {
        width: 600px;
        margin: 20% auto;
    }


    </style>

@endsection

@section('datatable-js')

  <script async="" defer="" src="https://maps.googleapis.com/maps/api/js?key=AIzaSyDuDdIs9T09hGo4LV3dRSiuHVMbUnkS-JE&libraries=places"></script>

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
      <script type="text/javascript" language="javascript" src="https://cdn.datatables.net/plug-ins/1.10.13/api/sum().js"></script>

      <script type="text/javascript" language="javascript" src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.8.4/moment.min.js"></script>
      <script type="text/javascript" language="javascript" src="https://cdn.datatables.net/plug-ins/1.10.15/sorting/datetime-moment.js"></script>

      <script type="text/javascript" language="javascript" src="{{ asset('/plugin/js/dataTables.editor.min.js') }}"></script>

      {{-- <script type="text/javascript" language="javascript" src="{{ asset('/plugin/examples/resources/syntax/shCore.js') }}"></script>
      <script type="text/javascript" language="javascript" src="{{ asset('/plugin/examples/resources/demo.js') }}"></script>
      <script type="text/javascript" language="javascript" src="{{ asset('/plugin/examples/resources/editor-demo.js') }}"></script> --}}

      <script>

      var editor;
      var timesheettable;
      var map;
      var gmarkers = Array();
      function myfunction(latitude_in, longitude_in,latitude_out, longitude_out){

          for (var i = 0; i < gmarkers.length; i++) {

            if (parseFloat(gmarkers[i].getPosition().lat()).toFixed(8)==parseFloat(latitude_in).toFixed(8) && parseFloat(gmarkers[i].getPosition().lng()).toFixed(8)==parseFloat(longitude_in).toFixed(8))
            {
              gmarkers[i].setIcon('{{ asset('img/engineer_in.png') }}');
              map.setZoom(15);
              map.setCenter(gmarkers[i].getPosition());
            }
            else if (parseFloat(gmarkers[i].getPosition().lat()).toFixed(8)==parseFloat(latitude_out).toFixed(8) && parseFloat(gmarkers[i].getPosition().lng()).toFixed(8)==parseFloat(longitude_out).toFixed(8))
            {
              gmarkers[i].setIcon('{{ asset('img/engineer_out.png') }}');
              map.setZoom(15);
              map.setCenter(gmarkers[i].getPosition());
            }
            else {

              gmarkers[i].setIcon('{{ asset('img/map-marker-icon.png') }}');
            }

          }

            // var lat = lattitude;
            // var long = longitude;
            // var coords = new google.maps.LatLng(lat,long);
            // var marker = new google.maps.Marker({
            //     map: map,
            //     position: coords,
            //     icon:'http://icons.iconarchive.com/icons/iconshock/windows-7-general/64/administrator-icon.png'
            // });
            // var infowindow = new google.maps.InfoWindow();

            // google.maps.event.addListener(marker, 'click', function() {
            //   infowindow.setContent('<div><strong>' + '</strong><br>' + '<img src=http://icons.iconarchive.com/icons/iconshock/windows-7-general/64/administrator-icon.png />' + '<br>' +
            //   '<br> Coordinate: '+marker.getPosition().toUrlValue(6)+'</div>');
            //   infowindow.open(map, this);
            // });
        }

      $(document).ready(function() {

        $.fn.dataTable.moment( 'DD-MMM-YYYY' );

        function initialize() {
            var mapOptions = {
                  @if($timesheetdetail)
                    center: new google.maps.LatLng({{$timesheetdetail[0]->Latitude_In}},{{$timesheetdetail[0]->Longitude_In}}),
                    zoom: 12,
                  @else
                    center: new google.maps.LatLng(3.509247,101.524803),
                    zoom: 8,
                  @endif

                  mapTypeId: google.maps.MapTypeId.ROADMAP
              };
              map = new google.maps.Map(document.getElementById("map"), mapOptions);

              google.maps.event.addListener(map, 'click', function(e) {
                  latInput.value = e.latLng.lat() ;
                  longInput.value = e.latLng.lng();
              });
            var locs = [
                     @foreach($timesheetdetail as $timesheet)

                      [{{$timesheet->Latitude_In}}, {{$timesheet->Longitude_In}}, {{$timesheet->Id}}],
                      [{{$timesheet->Latitude_Out}}, {{$timesheet->Longitude_Out}}, {{$timesheet->Id}}],
                     @endforeach
                    ];
                var i;
                    for (i = 0; i < locs.length; i++) {
                    var id = locs[i][2];

                      var marker= new google.maps.Marker({
                      position: new google.maps.LatLng(locs[i][0], locs[i][1]),
                      map: map,
                      icon:"{{ asset('img/map-marker-icon.png') }}"
                      });

                      // marker.addListener('click', function() {
                      //   map.setZoom(13);
                      //   map.setCenter(marker.getPosition());
                      // });

                      gmarkers.push(marker);

                      // gmarkers[i].addListener('click', function() {
                      //   map.setZoom(13);
                      //   map.setCenter(gmarkers[i].getPosition());
                      // });

                    }

                  }

                     editor = new $.fn.dataTable.Editor( {
                       ajax: {
                          "url": "{{ asset('/Include/timesheetapproval.php') }}",
                          "data": {
                            @if($viewall==false)
                              "Approver": "{{ $me->UserId }}",
                            @endif
                            "UserId": "{{ $UserId }}",
                            "Start": "{{ $start }}",
                            "End": "{{ $end }}"
                          }
                        },
                             table: "#pendingtable",
                             formOptions: {
                                  inline: {
                                      submit: 'allIfChanged'
                                  }
                              },
                             idSrc: "timesheetstatuses.Id",
                             fields: [
                               {
                                       name: "timesheets.Id",

                               },{
                                      label: "Leader_Member:",
                                      name: "timesheets.Leader_Member",
                                      type:  'select',
                                      options: [
                                        { label :"", value: "" },
                                          @foreach($options as $option)
                                            @if ($option->Field=="Leader_Member")
                                              { label :"{{$option->Option}}", value: "{{$option->Option}}" },
                                            @endif
                                          @endforeach

                                      ],
                               },{
                                      label: "Next Person:",
                                      name: "timesheets.Next_Person"
                               },
                               @if($me->Edit_Allowance)
                               {
                                      label: "Allowance:",
                                      name: "timesheets.Allowance",
                                      attr: {
                                         type: "number"
                                       }

                               },
                               {
                                      label: "OT1:",
                                      name: "timesheets.OT1",
                                      attr: {
                                         type: "number"
                                       }

                               },
                               {
                                      label: "OT2:",
                                      name: "timesheets.OT2",
                                      attr: {
                                         type: "number"
                                       }

                               },
                               {
                                      label: "OT3:",
                                      name: "timesheets.OT3",
                                      attr: {
                                         type: "number"
                                       }

                               },
                               {
                                      label: "Monetary_Comp:",
                                      name: "timesheets.Monetary_Comp",
                                      attr: {
                                         type: "number"
                                       }

                               },
                               @endif
                               {
                                      label: "Remarks:",
                                      name: "timesheets.Remarks"
                               },
                               {
                                       label: "Comment:",
                                       name: "timesheetstatuses.Comment",
                                       type: "textarea"
                               }

                             ]
                     } );


                     // Activate an inline edit on click of a table cell
                           $('#pendingtable').on( 'click', 'tbody td:not(.child), tbody span.dtr-data', function (e) {
                             editor.inline( this, {
                            onBlur: 'submit',
                            submit: 'allIfChanged'
                               } );
                           } );

                           editor.on( 'postEdit', function ( e, json, data ) {

                             var approved=0;
                             var rejected=0;
                             var pending=0;
                             var allowance=0.0;
                             var monetary=0.0;
                             var ot1=0;
                             var ot2=0;
                             var ot3=0;

                             timesheettable.api().rows().every( function () {
                                var d = this.data();

                                var status=d.timesheetstatuses.Status;

                                @if($me->View_Allowance)

                                   allowance=allowance + parseFloat(d.timesheets.Allowance);
                                   monetary=monetary + parseFloat(d.timesheets.Monetary_Comp);

                                @endif

                                if(status===null)
                                {
                                  pending =pending+1;
                                }
                                else if(status.includes("Approved"))
                                {
                                  approved = approved+1;
                                }
                                else if(status.includes("Rejected"))
                                {
                                  rejected = rejected+1;
                                }
                                else
                                {
                                  pending =pending+1;
                                }

                                ot1=ot1+parseFloat(d.timesheets.OT1);
                                ot2=ot2+parseFloat(d.timesheets.OT2);
                                ot3=ot3+parseFloat(d.timesheets.OT3);

                            } );

                            $("#pendingapprovalcount").html(pending);
                            $("#approvedcount").html(approved);
                            $("#rejectedcount").html(rejected);

                             $("#total").html("RM" + allowance.toFixed(2));
                             $("#totalmonetary").html("RM" + monetary.toFixed(2));
                             $("#totalall").html("RM" + (allowance+monetary).toFixed(2));

                             $("#ot1").html( ot1.toFixed(2));
                             $("#ot2").html( ot2.toFixed(2));
                             $("#ot3").html( ot3.toFixed(2));

                            } );

                           timesheettable=$('#pendingtable').dataTable( {
                                 ajax: {
                                    "url": "{{ asset('/Include/timesheetapproval.php') }}",
                                    "data": {
                                      @if($viewall=="false")
                                        "Approver": "{{ $me->UserId }}",
                                      @endif
                                      "UserId": "{{ $UserId }}",
                                      "Start": "{{ $start }}",
                                      "End": "{{ $end }}"
                                    }
                                  },
                                  fnInitComplete: function(oSettings, json) {

                                    var approved=0;
                                    var rejected=0;
                                    var pending=0;
                                    var allowance=0.0;
                                    var monetary=0.0;
                                    var ot1=0;
                                    var ot2=0;
                                    var ot3=0;

                                    timesheettable.api().rows().every( function () {
                                       var d = this.data();

                                       var status=d.timesheetstatuses.Status;

                                       @if($me->View_Allowance)

                                          allowance=allowance + parseFloat(d.timesheets.Allowance);
                                          monetary=monetary + parseFloat(d.timesheets.Monetary_Comp);

                                       @endif

                                       if(status===null)
                                       {
                                         pending =pending+1;
                                       }
                                       else if(status.includes("Approved"))
                                       {
                                         approved = approved+1;
                                       }
                                       else if(status.includes("Rejected"))
                                       {
                                         rejected = rejected+1;
                                       }
                                       else
                                       {
                                         pending =pending+1;
                                       }

                                       ot1=ot1+parseFloat(d.timesheets.OT1);
                                       ot2=ot2+parseFloat(d.timesheets.OT2);
                                       ot3=ot3+parseFloat(d.timesheets.OT3);


                                   } );

                                   $("#pendingapprovalcount").html(pending);
                                   $("#approvedcount").html(approved);
                                   $("#rejectedcount").html(rejected);

                                   $("#total").html("RM" + allowance.toFixed(2));
                                   $("#totalmonetary").html("RM" + monetary.toFixed(2));
                                   $("#totalall").html("RM" + (allowance+monetary).toFixed(2));

                                   $("#ot1").html( ot1.toFixed(2));
                                   $("#ot2").html( ot2.toFixed(2));
                                   $("#ot3").html( ot3.toFixed(2));

                                   },
                                   createdRow: function ( row, data, index ) {
                                     var days = ['Sun','Mon','Tue','Wed','Thu','Fri','Sat'];
                                     var d = new Date(data.timesheets.Date)
                                     var n = days[d.getDay()];

                                     if (n=="Sun" || n=="Sat")
                                     {
                                       $(row).removeClass('odd');
                                       $(row).addClass('weekendrow');

                                     }
                                   },
                                   columnDefs: [{ "visible": false, "targets": [1,2,3,4,5,6,33] },{"className": "dt-center dt-padding", "targets": "_all"}],
                                   rowId: 'timesheetstatuses.Id',
                                   colReorder: false,
                                   sScrollX: "100%",
                                   bAutoWidth: true,
                                   sScrollY: "100%",
                                   bPaginate:true,
                                   responsive:false,
                                   dom: "lBfrtpi",
                                   aaSorting: [[8,"asc"]],
                                   columns: [
                                           {
                                             sortable: false,
                                             "render": function ( data, type, full, meta ) {
                                                 return '<input type="checkbox" name="selectrow" id="selectrow" class="selectrow" value="'+full.timesheetstatuses.Id+'" onclick="uncheck()">';

                                             }

                                           },
                                           { data: "timesheetstatuses.Id"},
                                           { data: "timesheets.Id"},
                                           { data: "timesheets.Latitude_In"},
                                           { data: "timesheets.Longitude_In"},
                                           { data: "timesheets.Latitude_Out"},
                                           { data: "timesheets.Longitude_Out"},
                                           { title:"Status",
                                           "render": function ( data, type, full, meta ) {

                                                if(full.timesheetstatuses.Status.includes("Approved"))
                                                {
                                                  return "<span class='green'>"+full.timesheetstatuses.Status+"</span>";
                                                }
                                                else if(full.timesheetstatuses.Status.includes("Rejected"))
                                                {
                                                  return "<span class='red'>"+full.timesheetstatuses.Status+"</span>";
                                                }
                                                else {
                                                  return "<span class='yellow'>"+full.timesheetstatuses.Status+"</span>";
                                                }

                                             }
                                           },
                                           { data: "timesheets.Date",title:"Date",
                                           "render": function ( data, type, full, meta ) {

                                                  return '<a class ="buttonclaim" onclick="viewclaim(\''+full.timesheets.Date+'\',\'{{$user->Id}}\')">'+full.timesheets.Date+'</a>';
                                              }
                                           },
                                           {
                                              title:"Day",
                                              sortable: false,
                                              "render": function ( data, type, full, meta ) {
                                                var days = ['Sun','Mon','Tue','Wed','Thu','Fri','Sat'];
                                                var dateSplit = full.timesheets.Date.split("-");
                                                objDate = new Date(dateSplit[1] + " " + dateSplit[0] + ", " + dateSplit[2]);
                                                var d = new Date(objDate)
                                                var n = days[d.getDay()];

                                                if (n=="Sun" || n=="Sat")
                                                {
                                                  return "<span class='red'>"+n+"</span>"
                                                }

                                                  return n;
                                              }
                                          },
                                          { data: "timesheets.Check_In_Type",title:"Type"},
                                          { title:"Time_In",
                                          "render": function ( data, type, full, meta ) {

                                              var timevalue=parseInt(full.timesheets.Time_In);
                                              if (full.timesheets.Time_In.includes("PM"))
                                              {
                                                timevalue+=12;
                                              }
                                              if (timevalue<=8 || timevalue>=18)
                                              {
                                                return "<span class='green'>"+full.timesheets.Time_In+"</span>";
                                              }
                                              else {
                                                return full.timesheets.Time_In;
                                              }

                                            }
                                          },
                                          { data: "timesheets.Time_Out",
                                          "render": function ( data, type, full, meta ) {

                                              var timevalue=parseInt(full.timesheets.Time_Out);
                                              if (full.timesheets.Time_Out.includes("PM"))
                                              {
                                                timevalue+=12;
                                              }
                                              if (timevalue<=8 || timevalue>=18)
                                              {
                                                return "<span class='green'>"+full.timesheets.Time_Out+"</span>";
                                              }
                                              else {
                                                return full.timesheets.Time_Out;
                                              }

                                            }
                                          },
                                          { data: "timesheets.State",title:"State"},
                                          { data: null, title:"Position",
                                            "render": function ( data, type, full, meta ) {

                                                return "{{$user->Position}}";
                                              }
                                          },
                                          { data: null, title:"Home_Base",
                                            "render": function ( data, type, full, meta ) {

                                                return "{{$user->Home_Base}}";
                                              }
                                          },
                                          { data: "timesheets.Allowance",
                                          name: "Allowance",title:"Allowance",
                                          @if($me->View_Allowance)

                                          "render": function ( data, type, full, meta ) {

                                              return full.timesheets.Allowance;

                                            }
                                          @else
                                            "render": function ( data, type, full, meta ) {

                                                return "-";

                                              }
                                          @endif
                                          },
                                          { data: "timesheets.Monetary_Comp",
                                          name: "Monetary_Comp",title:"Monetary_Comp",
                                          @if($me->View_Allowance)

                                          "render": function ( data, type, full, meta ) {

                                              return full.timesheets.Monetary_Comp;

                                            }
                                          @else
                                            "render": function ( data, type, full, meta ) {

                                                return "-";

                                              }
                                          @endif
                                          },
                                          { data: "timesheets.OT1",title:"OT_1.5"},
                                          { data: "timesheets.OT2",title:"OT_2.0"},
                                          { data: "timesheets.OT3",title:"OT_3.0"},
                                           { data: "timesheets.Leader_Member",title:"Leader/Member"},
                                           { data: "timesheets.Next_Person",title:"Next_Person"},
                                           { data: "timesheets.Site_Name",title:"Site_Name"},

                                           { data: "timesheets.Work_Description",title:"Work_Description"},
                                           { data: "timesheets.Remarks",title:"Remarks"},
                                           { data: "approver.Name",title:"Approver"},
                                           { data: "timesheetstatuses.Comment",title:"Comment"},
                                           { data: "timesheetstatuses.updated_at",title:"Review_Date"},

                                           { data: "checker.Name",title:"Checker"},
                                           { data: "timesheetchecked.Timesheet_Status",title:"Timesheet_Status"},
                                           { data: "timesheetchecked.Payment_Status",title:"Payment_Status"},
                                           { data: "timesheetchecked.Updated_At",title:"Updated_At"},

                                           { data: "files.Web_Path",
                                              render: function ( url, type, row ) {
                                                   if (url)
                                                   {
                                                     return '<a href="'+ url +'" target="_blank">Download</a>';

                                                   }
                                                   else {
                                                     return ' - ';
                                                   }

                                               },
                                               title: "File"
                                             }

                                   ],
                                   autoFill: {
                                      editor:  editor
                                  },
                                  keys: {
                                      columns: 'td',
                                      editor:  editor
                                  },
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
                                     }

                                   ],

                       });

                       editor.on( 'preSubmit', function ( e, o, action ) {
                         if ( action == 'edit' ) {

                           for (var key in o.data) {

                             for (var a in o.data[key])
                             {

                               for (var field in o.data[key].timesheets)
                               {

                                 if (field=="Time_In" )
                                 {
                                   var timein = this.field( 'timesheets.Time_In' );
                                   var regex = /^([0-9]|0[0-9]|1[0-9]|2[0-3]):[0-5][0-9]?\s?(?:AM|PM|am|pm|aM|pM|Am|Pm)$/i;

                                   // Only validate user input values - different values indicate that
                                   // the end user has not entered a value
                                   if (timein.val()!="")
                                   {

                                     if(!regex.test(timein.val()))
                                     {
                                       timein.error( 'Invalid time!' );
                                       timein.val("");
                                       return false;
                                     }

                                   }

                                 }
                                 else if ( field=="Time_Out")
                                 {
                                   var timeout = this.field( 'timesheets.Time_Out' );
                                   var regex = /^([0-9]|0[0-9]|1[0-9]|2[0-3]):[0-5][0-9]?\s?(?:AM|PM|am|pm|aM|pM|Am|Pm)$/i;

                                   // Only validate user input values - different values indicate that
                                   // the end user has not entered a value

                                   if (timeout.val()!="")
                                   {

                                     if(!regex.test(timeout.val()))
                                     {
                                       timeout.error( 'Invalid time!' );
                                       timeout.val("");
                                       return false;
                                     }

                                   }

                                 }
                                 else {
                                   return true;
                                 }

                               }

                             }

                           }

                             return true;

                         }
                     } );

                       $('#pendingtable').on( 'click', 'tbody td', function (e) {
                             editor.inline( this, {
                            onBlur: 'submit'
                           } );
                       } );

                       $('#pendingtable tbody').on('click', 'tr', function () {
                            var data=timesheettable.api().row(this).data();
                            var json=JSON.stringify(data);
                            myfunction(data.timesheets.Latitude_In,data.timesheets.Longitude_In,data.timesheets.Latitude_Out,data.timesheets.Longitude_Out);

                        } );

                       $("#ajaxloader").hide();
                       $("#ajaxloader2").hide();

                      //  google.maps.event.addDomListener(window, 'load', initialize);

            // // Activate an inline edit on click of a table cell
            // $('#pendingtable').on( 'click', 'tbody td:not(:first-child)', function (e) {
            //     editor.inline( this );
            // } );
            //
            // // Disable KeyTable while the main editing form is open
            // editor
            //     .on( 'open', function ( e, mode, action ) {
            //         if ( mode === 'main' ) {
            //             table.keys.disable();
            //         }
            //     } )
            //     .on( 'close', function () {
            //         table.keys.enable();
            //     } );

          } );

      </script>
@endsection

@section('content')

    <div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
      <h1>
        Timesheet Detail
        <small>Resource Management</small>
      </h1>
      <ol class="breadcrumb">
        <li><a href="{{ url('/home') }}"><i class="fa fa-dashboard"></i> Home</a></li>
        <li><a href="#">Resource Management</a></li>
        <li><a href="#">Timesheet</a></li>
        <li><a href="{{ url('/timesheetmanagement') }}">Timesheet Management</a></li>
        <li class="active">Timesheet Detail</li>
      </ol>
    </section>

    <!-- Main content -->
    <section class="content">
      <div class="row">

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


         <div class="box-body">
           <button type="button" class="btn btn-success btn-lg" data-toggle="modal" data-target="#ExportPDF">Export</button>
         </div>

         <div class="modal fade" id="ExportPDF" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
          <div class="modal-dialog">
           <div class="modal-content">
             <div class="modal-header">

               <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
               <h4 class="modal-title" id="myModalLabel">Export Timesheet</h4>

             </div>

             <div class="modal-body">
                 Are you sure you wish to export this timesheet?
             </div>
             <div class="modal-footer">
               <a class="btn btn-primary btn-lg" href="{{ url('/exporttimesheet') }}/{{$UserId}}/{{$start}}/{{$end}}" target="_blank">Export</a>
             </div>
           </div>
         </div>
       </div>

       <div class="modal fade" id="ViewClaim" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
         <div class="modal-dialog claimbox"  role="document">
           <div class="modal-content">
             <div class="modal-header">
               <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
               <h4 class="modal-title" id="myModalLabel">Claim Details</h4>
             </div>
             <div class="modal-body" name="claim" id="claim">

             </div>
             <div class="modal-footer">
               <center><img src="{{ URL::to('/') ."/img/ajax-loader.gif" }}" width="50px" height="50px" alt="Loading" name='ajaxloader3' id="ajaxloader3"></center>
               <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
             </div>
           </div>
         </div>
       </div>

       <div class="modal fade" id="Redirect" role="dialog" aria-labelledby="myModalLabel">
         <div class="modal-dialog" role="document">
           <div class="modal-content">
             <div class="modal-header">
               <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
               <h4 class="modal-title" id="myModalLabel">Redirect</h4>
             </div>
             <div class="modal-body">
               <div class="form-group" id="redirectleavestatus">

               </div>
               <div class="form-group">

                   <label>Approver : </label>

                   <select class="form-control select2" id="NewApprover" name="NewApprover" style="width: 100%;">

                     @if ($approver)
                       @foreach ($approver as $app)

                           <option  value="{{$app->Id}}">{{$app->Name}}</option>

                       @endforeach

                     @endif

                     </select>

               </div>
             </div>
             <div class="modal-footer">
               <center><img src="{{ URL::to('/') ."/img/ajax-loader.gif" }}" width="50px" height="50px" alt="Loading" name='ajaxloader2' id="ajaxloader2"></center>
               <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
               <button type="button" class="btn btn-primary" onclick="redirect()">Redirect</button>
             </div>
           </div>
         </div>
       </div>

         <div class="modal fade" id="Submit" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
           <div class="modal-dialog" role="document">
             <div class="modal-content">
               <div class="modal-header">
                 <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                 <h4 class="modal-title" id="myModalLabel">Submit for approval</h4>
               </div>
               <div class="modal-body">
                   Are you sure you wish to submit the selected timesheets for next action?
               </div>
               <div class="modal-footer">
                 <center><img src="{{ URL::to('/') ."/img/ajax-loader.gif" }}" width="50px" height="50px" alt="Loading" name='ajaxloader' id="ajaxloader"></center>
                 <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                 <button type="button" class="btn btn-primary" onclick="submit()">Yes</button>
               </div>
             </div>
           </div>
         </div>

         <div class="modal fade" id="NewTimesheet" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
           <div class="modal-dialog" role="document">
             <div class="modal-content">
               <div class="modal-header">
                 <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                 <h4 class="modal-title" id="myModalLabel">New Timesheet</h4>
               </div>

               <div class="modal-body">

                 <div class="form-group">
                   <label class="col-md-4 control-label">Date</label>
                   <div class="col-md-8">
                     <div class="input-group date">
                       <div class="input-group-addon">
                         <i class="fa fa-calendar"></i>
                       </div>
                       <input type="text" class="form-control pull-right" id="NewDate" name="NewDate" value="">
                     </div>
                     <br>
                   </div>
                 </div>

               </div>

               <div class="modal-footer">
                 <!-- <center><img src="{{ URL::to('/') ."/img/ajax-loader.gif" }}" width="50px" height="50px" alt="Loading" name='ajaxloader' id="ajaxloader"></center> -->
                 <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                 <button type="button" class="btn btn-primary" onclick="newtimesheet()">Create</button>
               </div>
             </div>
           </div>
         </div>

         <div class="col-md-4">

           <div class="box box-success">
             <br>
             <div class="row">
               <div class="form-group">
                 @if ($user->Web_Path)

                   <img class="profile-user-img img-responsive img-circle" name="profileimage" id="profileimage" src="{{ url($user->Web_Path) }}" alt="User profile picture">
                 @else
                     <img class="profile-user-img img-responsive img-circle" name="profileimage" id="profileimage" src="{{ URL::to('/') ."/img/default-user.png"  }}" alt="User profile picture">
                 @endif
               </div>
             </div>

             <div class="row">

               <div class="form-group">
                 <div class="col-lg-6">
                   <label>StaffId : <i>{{$user->StaffId}}</i></label>
                 </div>

                 <div class="col-lg-6">
                   <label>Name : <i>{{$user->Name}}</i></label>
                 </div>

               </div>
             </div>

             <div class="row">
               <div class="form-group">

                 <div class="col-lg-6">

                   <label>Position : <i>{{$user->Position}}</i></label>
                 </div>

               </div>
             </div>

           <div class="row">
             <div class="form-group">
               <div class="col-lg-6">
                 <label>Nationality : <i>{{$user->Nationality}}</i></label>

               </div>

               <div class="col-lg-6">
                 <label>Home Base : <i>{{$user->Home_Base}}</i></label>
               </div>

             </div>
           </div>

           <div class="row">
             <div class="form-group">
               <div class="col-lg-6">
                 <label>Joining Date : <i>{{$user->Joining_Date}}</i></label>

               </div>

               <div class="col-lg-6">
                 <label>Scheme Name : <i>{{$user->Scheme_Name}}</i></label>

                 @if($timesheetdetail)
                   <?php

                      $threemonth=date($user->Joining_Date, strtotime('+3 months'));
                      $twoweek=date($user->Joining_Date, strtotime('+2 weeks'));

                      $count=count($timesheetdetail);
                      $lasttimesheetday=date($timesheetdetail[$count-1]->Date);
                      $today=date("d-M-Y");

                      if($lasttimesheetday>=$threemonth && $user->Scheme_Name=="Engineer Scheme")
                      {
                        //half engineer half technician
                        echo "<br><span class='red'>Note : Half Engineer Rate, Hald Technician Rate</span>";
                      }
                      else if($threemonth>=$today && $user->Scheme_Name=="Engineer Scheme")
                      {
                        //engineer within 3 month technician rate
                        echo "<br><span class='red'>Note : Engineer - First 3 months follow Technician Scheme</span>";
                      }
                      else if($twoweek>=$today && $user->Scheme_Name=="Engineer Scheme")
                      {
                        //2 weeks no allowance
                        echo "<br><span class='red'>Note : First 2 weeks no allowance</span>";
                      }

                   ?>
                @endif
               </div>

             </div>
           </div>

           {{-- <a href="#" class="btn btn-primary btn-block"><b>Update</b></a> --}}
         </div>
         <!-- /.box-body -->
       </div>

       <div class="col-md-4">
         <div class="box box-success">
           <div class="box-body box-profile">

             <ul class="list-group list-group-unbordered">
               <li class="list-group-item">
                 <b>Timesheet Date</b> : <p class="pull-right"><i>{{$start}} - {{$end}}</i></p>
               </li>
               <li class="list-group-item">
                 <b>Pending Approval</b> : <p class="pull-right"><i><span id='pendingapprovalcount'>0</i></p>
               </li>

               <li class="list-group-item">
                 <b>Approved</b> : <p class="pull-right"><i><span id='approvedcount'>0</i></p>
               </li>

               <li class="list-group-item">
                 <b>Rejected</b> : <p class="pull-right"><i><span id='rejectedcount'>0</i></p>
               </li>

               @if($me->View_Allowance)

                 <li class="list-group-item">
                   <b>Total Allowance</b> : <p class="pull-right"><i><span id='total'>RM0.00</i></p>
                 </li>

                 <li class="list-group-item">
                   <b>Total Monetary Compensation</b> : <p class="pull-right"><i><span id='totalmonetary'>RM0.00</i></p>
                 </li>

                 <li class="list-group-item">
                   <b>Total Allowance + Monetary Compensation</b> : <p class="pull-right"><i><span id='totalall'>RM0.00</i></p>
                 </li>

                 <li class="list-group-item">
                   <b>Total OT 1.5</b> : <p class="pull-right"><i><span id='ot1'>0.00</i></p>
                 </li>

                 <li class="list-group-item">
                   <b>Total OT 2.0</b> : <p class="pull-right"><i><span id='ot2'>0.00</i></p>
                 </li>

                 <li class="list-group-item">
                   <b>Total OT 3.0</b> : <p class="pull-right"><i><span id='ot3'>0.00</i></p>
                 </li>

               @endif

             </ul>

             {{-- <a href="#" class="btn btn-primary btn-block"><b>Update</b></a> --}}
           </div>
         </div>
       </div>

       <div class="col-md-4">
         <div class="box box-success">
          <div id="map">
          </div>
         </div>
       </div>

    </div>

    <div class="row">

      <div class="box box-success">
        <br>

        <div class="col-md-6">
        <div class="input-group">
          <div class="input-group-addon">
            <i class="fa fa-clock-o"></i>
          </div>
          <input type="text" class="form-control" id="range" name="range">

        </div>
      </div>

      <div class="col-md-6">
          <div class="input-group">
            <button type="button" class="btn btn-success btn" data-toggle="modal" onclick="refresh();">Refresh</button>
          </div>
      </div>
      <label></label>
    </div>
  </div>


      <div class="row">
        <div class="col-md-12">

          <button type="button" class="btn btn-primary btn-lg" data-toggle="modal" data-target="#Submit">Submit and Notify</button>
          <button type="button" class="btn btn-success btn" data-toggle="modal" onclick="approve()">Approve</button>
          <button type="button" class="btn btn-success btn" data-toggle="modal" onclick="approve2()">Approve with Special Attention</button>
          <button type="button" class="btn btn-danger btn" data-toggle="modal" onclick="reject()">Reject</button>
          <button type="button" class="btn btn-warning btn" data-toggle="modal" data-target="#Redirect">Redirect</button>

          @if($me->Edit_Allowance)

            <button type="button" class="btn btn-primary btn" data-toggle="modal" onclick="updatechecked('Processed')">Processed</button>
            <button type="button" class="btn btn-primary btn" data-toggle="modal" onclick="updatechecked('Reset')">Reset</button>
            <button type="button" class="btn btn-primary btn" data-toggle="modal" data-toggle="modal" data-target="#NewTimesheet">New Timesheet</button>
            <button type="button" class="btn btn-primary btn" data-toggle="modal" onclick="deletetimesheet()">Delete Timesheet</button>

          @endif

          @if($me->Update_Payment_Month)

            <br>
            <br>

              <div class="input-group">

                <select class="changed form-control select" id="paymentstatus" name="paymentstatus" style="width: 100px;">

                <option></option>
                  @foreach ($months as $month)

                      <option value="{{$month}}">{{$month}}</option>

                  @endforeach
                </select>

                &nbsp;&nbsp;&nbsp;<button type="button" class="btn btn-primary btn" data-toggle="modal" onclick="updatechecked2()">Update Payment Month</button><button type="button" class="btn btn-danger btn" data-toggle="modal" onclick="updatechecked3()">Reset</button>

              </div>
            <br>
            <br>

          @endif


                  <table id="pendingtable" class="display" cellspacing="0" width="100%" padding="30px" style="font-size: 13px;">
                      <thead>
                          {{-- prepare header search textbox --}}
                          <tr>
                            @foreach($timesheetdetail as $key=>$value)

                              @if ($key==0)
                                <td><input type="checkbox" name="selectall" id="selectall" value="all" onclick="checkall()"></td>
                                @foreach($value as $field=>$value)
                                    <td/>{{ $field }}</td>
                                @endforeach

                              @endif

                            @endforeach
                          </tr>
                      </thead>
                      <tbody>

                        <?php $i = 0; ?>
                        @foreach($timesheetdetail as $timesheet)

                              <tr id="row_{{ $i }}" >
                                  <td></td>
                                  @foreach($timesheet as $key=>$value)
                                    <td>
                                      {{ $value }}
                                    </td>
                                  @endforeach
                              </tr>
                              <?php $i++; ?>

                        @endforeach

                    </tbody>
                      <tfoot>


                      </tfoot>
                  </table>


        </div>
      </div>

      <!-- /.row -->
    </section>
    <!-- /.content -->
  </div>
  <!-- /.content-wrapper -->
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

    //Flat red color scheme for iCheck
    $('input[type="checkbox"].flat-blue, input[type="radio"].flat-blue').iCheck({
      checkboxClass: 'icheckbox_flat-blue',
      radioClass: 'iradio_flat-blue'
    });

    //Date picker
    $('#Date').datepicker({
      autoclose: true,
      format: 'dd-M-yyyy',
    });

    $('#NewDate').datepicker({
      autoclose: true,
        format: 'dd-M-yyyy',
    });

    //Timepicker
    $(".timepicker").timepicker({
      showInputs: false
    });

    $('#range').daterangepicker({locale: {
      format: 'DD-MMM-YYYY'
    },startDate: '{{$start}}',
    endDate: '{{$end}}'});

  });

      function uncheck()
      {

        if (!$("#selectrow").is(':checked')) {
          $("#selectall").prop("checked", false)
        }

      }

      function viewclaim(date,userid)
      {
        // alert(date);
        // alert(nextperson);
        $('#ViewClaim').modal('show');
        $("#claim").html("");

        $.ajaxSetup({
           headers: { 'X-CSRF-Token' : $('meta[name=_token]').attr('content') }
        });

        $("#ajaxloader3").show();

        $.ajax({
                    url: "{{ url('/timesheetmanagement/viewclaim') }}",
                    method: "POST",
                    data: {
                      Date:date,
                      UserId:userid
                    },
                    success: function(response){
                      if (response==0)
                      {
                        var message ="Failed to retrieve asset history!";
                        $("#warning-alert ul").html(message);
                        $("#warning-alert").modal('show');
                        $('#ReturnedModal').modal('hide')

                        $("#ajaxloader3").hide();
                      }
                      else {

                        $("#exist-alert").hide();

                        var myObject = JSON.parse(response);


                        var display='<div id="table-wrapper"><div id="table-scroll"><table border="1" align="center" class="claimtable" cellspacing="0" width="100%" padding="30px" style="font-size: 13px;">';
                        display+='<tr class="claimheader"><td>Site_Name</td><td>State</td><td>Work_Description</td><td>Car_No</td><td>Mileage</td><td>Expenses_Type</td><td>Total_Expenses</td><td>Petrol_SmartPay</td><td>Advance</td><td>Total_Amount</td><td>GST_Amount</td><td>Total_Without_GST</td><td>Receipt_No</td><td>Company_Name</td><td>GST_No</td><td>Remarks</td><td>Approver</td><td>Status</td><td>Comment</td><td>Review_Date</td></tr>';

                        $.each(myObject, function(i,item){

                                display+="<tr>";
                                display+='<td>'+item.Site_Name+'</td><td>'+item.State+'</td><td>'+item.Work_Description+'</td><td>'+item.Car_No+'</td><td>'+item.Mileage+'</td><td>'+item.Expenses_Type+'</td><td>'+item.Total_Expenses+'</td><td>'+item.Petrol_SmartPay+'</td><td>'+item.Advance+'</td><td>'+item.Total_Amount+'</td><td>'+item.GST_Amount+'</td><td>'+item.Total_Without_GST+'</td><td>'+item.Receipt_No+'</td><td>'+item.Company_Name+'</td><td>'+item.GST_No+'</td><td>'+item.Remarks+'</td><td>'+item.Approver+'</td><td>'+item.Status+'</td><td>'+item.Comment+'</td><td>'+item.updated_at+'<td>';
                                display+="</tr>";
                        });

                        display+="</table></div></div>";

                        $("#claim").html(display);

                        $("#ajaxloader3").hide();
                      }
            }
        });

      }


        function checkall()
        {
          var allPages = timesheettable.fnGetNodes();
      // alert(document.getElementById("selectall").checked);
          if ($("#selectall").is(':checked')) {



             $('input[type="checkbox"]', allPages).prop('checked', true);
              // $(".selectrow").prop("checked", true);
               $(".selectrow").trigger("change");
               timesheettable.api().rows().select();
          } else {

              $('input[type="checkbox"]', allPages).prop('checked', false);
              $(".selectrow").trigger("change");
               timesheettable.api().rows().deselect();
          }
        }

      function refresh()
      {
        var d=$('#range').val();
        var arr = d.split(" - ");

        window.location.href ="{{ url("/timesheet") }}/{{$UserId}}/true/"+arr[0]+"/"+arr[1];

      }

      function submit() {

        var boxes = $('input[type="checkbox"]:checked', timesheettable.fnGetNodes() );
        var ids="";
        var norejectedcomment=false;

        timesheettable.api().rows().every( function ( rowIdx, tableLoop, rowLoop ) {
          var data = this.data();
          if(data.timesheetstatuses.Status.includes("Rejected") && data.timesheetstatuses.Comment=="")
          {
            norejectedcomment=true;
          }
          // ... do something with data(), or this.node(), etc
        } );

        if (norejectedcomment==false)
        {
              if (boxes.length>0)
              {
                for (var i = 0; i < boxes.length; i++) {
                  ids+=boxes[i].value+",";
                }

                ids=ids.substring(0, ids.length-1);

                $.ajaxSetup({
                   headers: { 'X-CSRF-Token' : $('meta[name=_token]').attr('content') }
                });

                  $("#ajaxloader").show();

                $.ajax({
                            url: "{{ url('/timesheet/submit') }}",
                            method: "POST",
                            data: {StatusIds:ids},

                            success: function(response){

                              if (response==1)
                              {

                                  timesheettable.api().ajax.url("{{ asset('/Include/timesheetapproval.php') }}").load();

                                  var message="Submitted for next action!";
                                  $("#update-alert ul").html(message);
                                  $("#update-alert").modal('show');

                                  // setTimeout(function() {
                                  //   $("#update-alert").fadeOut();
                                  // }, 6000);

                                  $('#Submit').modal('hide');

                                  $("#ajaxloader").hide();

                              }
                              else {

                                var errormessage="Failed to submit for next action!";
                                $("#error-alert ul").html(errormessage);
                                $("#error-alert").modal('show');
                                //
                                // setTimeout(function() {
                                //   $("#error-alert").fadeOut();
                                // }, 6000);

                                $('#Submit').modal('hide');

                                $("#ajaxloader").hide();

                              }

                    }
                });

            }
            else {
              var errormessage="No timesheet selected!";
              $("#error-alert ul").html(errormessage);
              $("#error-alert").modal('show');

              // setTimeout(function() {
              //   $("#error-alert").fadeOut();
              // }, 5000);

              $('#Submit').modal('hide');

              $("#ajaxloader").hide();
            }
        }
        else {

              var errormessage="Comment required for Rejected Timesheet!";
              $("#error-alert ul").html(errormessage);
              $("#error-alert").modal('show');

              // setTimeout(function() {
              //   $("#error-alert").fadeOut();
              // }, 5000);

              $('#Submit').modal('hide');

              $("#ajaxloader").hide();
      }
  }

      function approve() {

        var boxes = $('input[type="checkbox"]:checked', timesheettable.fnGetNodes() );
        var ids="";

        if (boxes.length>0)
        {
          for (var i = 0; i < boxes.length; i++) {
            ids+=boxes[i].value+",";
          }

          ids=ids.substring(0, ids.length-1);

          status="Approved";

          @if($mylevel)
            @if ($mylevel->Level=="1st Approval")
              status="1st Approved";
            @endif

            @if ($mylevel->Level=="2nd Approval")
              status="2nd Approved";
            @endif

            @if ($mylevel->Level=="3rd Approval")
              status="3rd Approved";
            @endif

            @if ($mylevel->Level=="4th Approval")
              status="4th Approved";
            @endif

            @if ($mylevel->Level=="5th Approval")
              status="5th Approved";
            @endif

            @if ($mylevel->Level=="Final Approval")
              status="Final Approved";
            @endif

          @endif

          $.ajaxSetup({
             headers: { 'X-CSRF-Token' : $('meta[name=_token]').attr('content') }
          });

            $("#ajaxloader").show();

          $.ajax({
                      url: "{{ url('/timesheet/approve') }}",
                      method: "POST",
                      data: {StatusIds:ids,
                      Status:status},

                      success: function(response){

                        if (response==1)
                        {

                            timesheettable.api().ajax.url("{{ asset('/Include/timesheetapproval.php') }}").load();

                            var message="Timesheet Approved!";
                            $("#update-alert ul").html(message);
                            $("#update-alert").modal('show');

                            // setTimeout(function() {
                            //   $("#update-alert").fadeOut();
                            //
                            // }, 5000);

                            $("#ajaxloader").hide();

                        }
                        else {

                          var errormessage="Failed to approve timesheet!";
                          $("#error-alert ul").html(errormessage);
                          $("#error-alert").modal('show');

                          // setTimeout(function() {
                          //   $("#error-alert").fadeOut();
                          // }, 5000);

                          $("#ajaxloader").hide();

                        }

              }
          });

      }
      else {
        var errormessage="No timesheet selected!";
        $("#error-alert ul").html(errormessage);
        $("#error-alert").modal('show');

        // setTimeout(function() {
        //   $("#error-alert").fadeOut();
        // }, 5000);

      }

    }

    function updatechecked(status) {

      var boxes = $('input[type="checkbox"]:checked', timesheettable.fnGetNodes() );
      var ids="";

      if (boxes.length>0)
      {
        for (var i = 0; i < boxes.length; i++) {
          ids+=boxes[i].value+",";
        }

        ids=ids.substring(0, ids.length-1);

        $.ajaxSetup({
           headers: { 'X-CSRF-Token' : $('meta[name=_token]').attr('content') }
        });

        $.ajax({
                    url: "{{ url('/timesheet/updatechecked') }}",
                    method: "POST",
                    data: {StatusIds:ids,
                    Status:status},

                    success: function(response){

                      if (response==1)
                      {

                          timesheettable.api().ajax.url("{{ asset('/Include/timesheetapproval.php') }}").load();

                          var message="Timesheet Status Updated!";
                          $("#update-alert ul").html(message);
                          $("#update-alert").modal('show');

                          // setTimeout(function() {
                          //   $("#update-alert").fadeOut();
                          //
                          // }, 5000);

                      }
                      else {

                        var errormessage="Failed to update timesheet status!";
                        $("#error-alert ul").html(errormessage);
                        $("#error-alert").modal('show');

                        // setTimeout(function() {
                        //   $("#error-alert").fadeOut();
                        // }, 5000);

                      }

            }
        });

      }
      else {
        var errormessage="No timesheet selected!";
        $("#error-alert ul").html(errormessage);
        $("#error-alert").modal('show');

        // setTimeout(function() {
        //   $("#error-alert").fadeOut();
        // }, 5000);

      }

    }

  function updatechecked2() {

    paymentstatus=$('[name="paymentstatus"]').val();

    var boxes = $('input[type="checkbox"]:checked', timesheettable.fnGetNodes() );
    var ids="";

    if (boxes.length>0)
    {
      for (var i = 0; i < boxes.length; i++) {
        ids+=boxes[i].value+",";
      }

      ids=ids.substring(0, ids.length-1);

      $.ajaxSetup({
         headers: { 'X-CSRF-Token' : $('meta[name=_token]').attr('content') }
      });

      $.ajax({
                  url: "{{ url('/timesheet/updatechecked2') }}",
                  method: "POST",
                  data: {StatusIds:ids,
                  Status:status,
                  PaymentStatus:paymentstatus},

                  success: function(response){

                    if (response==1)
                    {

                        timesheettable.api().ajax.url("{{ asset('/Include/timesheetapproval.php') }}").load();

                        var message="Timesheet Status Updated!";
                        $("#update-alert ul").html(message);
                      $("#update-alert").modal('show');

                        setTimeout(function() {
                          $("#update-alert").modal('hide');

                        }, 5000);

                    }
                    else {

                      var errormessage="Failed to update timesheet status!";
                      $("#error-alert ul").html(errormessage);
                      $("#error-alert").modal('show');

                      setTimeout(function() {
                        $("#error-alert").modal('hide');
                      }, 5000);

                    }

          }
      });

  }
  else {
    var errormessage="No timesheet selected!";
    $("#error-alert ul").html(errormessage);
    $("#error-alert").modal('show');

    setTimeout(function() {
      $("#error-alert").modal('hide');
    }, 5000);

  }

}

function updatechecked3() {

  paymentstatus="";

  var boxes = $('input[type="checkbox"]:checked', timesheettable.fnGetNodes() );
  var ids="";

  if (boxes.length>0)
  {
    for (var i = 0; i < boxes.length; i++) {
      ids+=boxes[i].value+",";
    }

    ids=ids.substring(0, ids.length-1);

    $.ajaxSetup({
       headers: { 'X-CSRF-Token' : $('meta[name=_token]').attr('content') }
    });

    $.ajax({
                url: "{{ url('/timesheet/updatechecked2') }}",
                method: "POST",
                data: {StatusIds:ids,
                Status:status,
                PaymentStatus:paymentstatus},

                success: function(response){

                  if (response==1)
                  {

                      timesheettable.api().ajax.url("{{ asset('/Include/timesheetapproval.php') }}").load();

                      var message="Timesheet Status Updated!";
                      $("#update-alert ul").html(message);
                      $("#update-alert").modal('show');

                      setTimeout(function() {
                        $("#update-alert").modal('hide');

                      }, 5000);

                  }
                  else {

                    var errormessage="Failed to update timesheet status!";
                    $("#error-alert ul").html(errormessage);
                    $("#error-alert").modal('show');

                    setTimeout(function() {
                      $("#error-alert").modal('hide');
                    }, 5000);

                  }

        }
    });

}
else {
  var errormessage="No timesheet selected!";
  $("#error-alert ul").html(errormessage);
  $("#error-alert").modal('show');

  setTimeout(function() {
    $("#error-alert").modal('hide');
  }, 5000);

}

}

    function approve2() {

      var boxes = $('input[type="checkbox"]:checked', timesheettable.fnGetNodes() );
      var ids="";

      if (boxes.length>0)
      {
        for (var i = 0; i < boxes.length; i++) {
          ids+=boxes[i].value+",";
        }

        ids=ids.substring(0, ids.length-1);

        status="Approved with Special Attention";

        @if($mylevel)
          @if ($mylevel->Level=="1st Approval")
            status="1st Approved with Special Attention";
          @endif

          @if ($mylevel->Level=="2nd Approval")
            status="2nd Approved with Special Attention";
          @endif

          @if ($mylevel->Level=="3rd Approval")
            status="3rd Approved with Special Attention";
          @endif

          @if ($mylevel->Level=="4th Approval")
            status="4th Approved with Special Attention";
          @endif

          @if ($mylevel->Level=="5th Approval")
            status="5th Approved with Special Attention";
          @endif

          @if ($mylevel->Level=="Final Approval")
            status="Final Approved with Special Attention";
          @endif

        @endif

        $.ajaxSetup({
           headers: { 'X-CSRF-Token' : $('meta[name=_token]').attr('content') }
        });

          $("#ajaxloader").show();

        $.ajax({
                    url: "{{ url('/timesheet/approve') }}",
                    method: "POST",
                    data: {StatusIds:ids,
                    Status:status},

                    success: function(response){

                      if (response==1)
                      {

                          timesheettable.api().ajax.url("{{ asset('/Include/timesheetapproval.php') }}").load();

                          var message="Timesheet Approved!";
                          $("#update-alert ul").html(message);
                          $("#update-alert").modal('show');

                          // setTimeout(function() {
                          //   $("#update-alert").fadeOut();
                          //
                          // }, 5000);

                         $("#pendingapprovalcount").html(pending);
                         $("#approvedcount").html(approved);
                         $("#rejectedcount").html(rejected);

                         $("#ajaxloader").hide();

                      }
                      else {

                        var errormessage="Failed to approve timesheet!";
                        $("#error-alert ul").html(errormessage);
                        $("#error-alert").modal('show');

                        // setTimeout(function() {
                        //   $("#error-alert").fadeOut();
                        // }, 5000);

                        $("#ajaxloader").hide();

                      }

            }
        });

    }
    else {
      var errormessage="No timesheet selected!";
      $("#error-alert ul").html(errormessage);
      $("#error-alert").modal('show');

      // setTimeout(function() {
      //   $("#error-alert").fadeOut();
      // }, 5000);

    }

  }

  function reject() {

    var boxes = $('input[type="checkbox"]:checked', timesheettable.fnGetNodes() );
    var ids="";

    if (boxes.length>0)
    {
      for (var i = 0; i < boxes.length; i++) {
        ids+=boxes[i].value+",";
      }

      ids=ids.substring(0, ids.length-1);

      status="Rejected";

      @if($mylevel)
        @if ($mylevel->Level=="1st Approval")
          status="1st Rejected";
        @endif

        @if ($mylevel->Level=="2nd Approval")
          status="2nd Rejected";
        @endif

        @if ($mylevel->Level=="3rd Approval")
          status="3rd Rejected";
        @endif

        @if ($mylevel->Level=="4th Approval")
          status="4th Rejected";
        @endif

        @if ($mylevel->Level=="5th Approval")
          status="5th Rejected";
        @endif

        @if ($mylevel->Level=="Final Approval")
          status="Final Rejected";
        @endif

      @endif

      $.ajaxSetup({
         headers: { 'X-CSRF-Token' : $('meta[name=_token]').attr('content') }
      });

        $("#ajaxloader").show();

      $.ajax({
                  url: "{{ url('/timesheet/approve') }}",
                  method: "POST",
                  data: {StatusIds:ids,
                  Status:status},

                  success: function(response){

                    if (response==1)
                    {

                        timesheettable.api().ajax.url("{{ asset('/Include/timesheetapproval.php') }}").load();

                        var message="Timesheet Rejected!";
                        $("#update-alert ul").html(message);
                        $("#update-alert").modal('show');

                        // setTimeout(function() {
                        //   $("#update-alert").fadeOut();
                        //
                        // }, 5000);

                       $("#pendingapprovalcount").html(pending);
                       $("#approvedcount").html(approved);
                       $("#rejectedcount").html(rejected);

                       $("#ajaxloader").hide();

                    }
                    else {

                      var errormessage="Failed to reject timesheet!";
                      $("#error-alert ul").html(errormessage);
                      $("#error-alert").modal('show');

                      // setTimeout(function() {
                      //   $("#error-alert").fadeOut();
                      // }, 5000);

                      $("#ajaxloader").hide();

                    }

          }
      });

  }
  else {
    var errormessage="No timesheet selected!";
    $("#error-alert ul").html(errormessage);
    $("#error-alert").modal('show');

    // setTimeout(function() {
    //   $("#error-alert").fadeOut();
    // }, 5000);

  }

}

function redirect() {

    $.ajaxSetup({
       headers: { 'X-CSRF-Token' : $('meta[name=_token]').attr('content') }
    });

    $("#ajaxloader").show();

    var boxes = $('input[type="checkbox"]:checked', timesheettable.fnGetNodes() );
    var ids="";

    if (boxes.length>0)
    {
      for (var i = 0; i < boxes.length; i++) {
        ids+=boxes[i].value+",";
      }

      ids=ids.substring(0, ids.length-1);

      newapprover=$('[name="NewApprover"]').val();

    $.ajax({
                url: "{{ url('/timesheet/redirect') }}",
                method: "POST",
                data: {StatusIds:ids,Approver:newapprover},

                success: function(response){

                  if (response==1)
                  {
                      timesheettable.api().ajax.url("{{ asset('/Include/timesheetapproval.php') }}").load();
                      var message="Timesheet redirected!";
                      $("#update-alert ul").html(message);
                      $("#update-alert").modal('show');

                      // setTimeout(function() {
                      //   $("#update-alert").fadeOut();
                      // }, 6000);

                      $('#Redirect').modal('hide');

                      $("#ajaxloader2").hide();

                  }
                  else {

                    var errormessage="Failed to redirect timesheet!";
                    $("#error-alert ul").html(errormessage);
                    $("#error-alert").modal('show');

                    // setTimeout(function() {
                    //   $("#error-alert").fadeOut();
                    // }, 6000);

                    $('#Redirect').modal('hide');

                    $("#ajaxloader2").hide();

                  }

        }
    });
  }
  else {
    var errormessage="No timesheet selected!";
    $("#error-alert ul").html(errormessage);
    $("#error-alert").modal('show');

    // setTimeout(function() {
    //   $("#error-alert").fadeOut();
    // }, 5000);

  }

}

function newtimesheet() {

  var date = "{{$end}}";
  var commentdate = $("input[name=NewDate]").val();
  var userid = {{$UserId}};

    $.ajaxSetup({
       headers: { 'X-CSRF-Token' : $('meta[name=_token]').attr('content') }
    });

    $.ajax({
                url: "{{ url('/timesheet/newtimesheet') }}",
                method: "POST",
                data: {
                  Date:date,
                  Comment:commentdate,
                  UserId:userid
                },

                success: function(response){

                  if (response==1)
                  {

                      $('#NewTimesheet').modal('hide');

                      var message="New Timesheet Added!";
                      $("#update-alert ul").html(message);
                      $("#update-alert").modal('show');

                      timesheettable.api().ajax.url("{{ asset('/Include/timesheetapproval.php') }}").load();

                  }
                  else {

                    $('#NewTimesheet').modal('hide');

                    var errormessage="Failed to add new timesheet!";
                    $("#error-alert ul").html(errormessage);
                    $("#error-alert").modal('show');


                  }

        }
    });


  }

  function deletetimesheet() {

    paymentstatus="";

    var boxes = $('input[type="checkbox"]:checked', timesheettable.fnGetNodes() );
    var ids="";

    if (boxes.length>0)
    {
      for (var i = 0; i < boxes.length; i++) {
        ids+=boxes[i].value+",";
      }

      ids=ids.substring(0, ids.length-1);

        $.ajaxSetup({
           headers: { 'X-CSRF-Token' : $('meta[name=_token]').attr('content') }
        });

      $.ajax({
                  url: "{{ url('/timesheet/deletetimesheet') }}",
                  method: "POST",
                  data: {
                    TimesheetIds : ids
                  },

                  success: function(response){

                    if (response==1)
                    {
                        $('#deletetimesheet').modal('hide');

                        var message="Timesheet deleted successfully!";
                        $("#update-alert ul").html(message);
                        $("#update-alert").modal('show');

                        timesheettable.api().ajax.url("{{ asset('/Include/timesheetapproval.php') }}").load();

                    }
                    else {

                        $('#deletetimesheet').modal('hide');

                      var errormessage="Failed to delete timesheet!";
                      $("#error-alert ul").html(errormessage);
                      $("#error-alert").modal('show');



                    }

          }
      });

    }
    else {
      var errormessage="No timesheet selected!";
      $("#error-alert ul").html(errormessage);
      $("#error-alert").modal('show');

    }

  }

</script>

@endsection
