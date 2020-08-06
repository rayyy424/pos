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
      .tableheader{
        background-color: gray;
      }

      .interntable{
        text-align: center;
      }
    </style>

    <style type="text/css">
      /*
      * Scut, a collection of Sass utilities
      * to ease and improve our implementations of common style-code patterns.
      * v1.3.0
      * Docs at https://davidtheclark.github.io/scut
      */
      .ProgressBar {
        margin: 0 auto;
        padding: 2em 0 3em;
        list-style: none;
        position: relative;
        display: flex;
        justify-content: space-between;
      }

      .ProgressBar-step {
        text-align: center;
        position: relative;
        width: 100%;
      }
      .ProgressBar-step:before, .ProgressBar-step:after {
        content: "";
        height: 0.5em;
        background-color: #9F9FA3;
        position: absolute;
        z-index: 1;
        width: 100%;
        left: -50%;
        top: 50%;
        -webkit-transform: translateY(-50%);
                transform: translateY(-50%);
        transition: all .25s ease-out;
      }
      .ProgressBar-step:first-child:before, .ProgressBar-step:first-child:after {
        display: none;
      }
      .ProgressBar-step:after {
        background-color: #00637C;
        width: 0%;
      }
      .ProgressBar-step.is-complete + .ProgressBar-step.is-current:after, .ProgressBar-step.is-complete + .ProgressBar-step.is-complete:after {
        width: 100%;
      }

      .ProgressBar-icon {
        width: 1.5em;
        height: 1.5em;
        background-color: #9F9FA3;
        fill: #9F9FA3;
        border-radius: 50%;
        padding: 0.5em;
        max-width: 100%;
        z-index: 10;
        position: relative;
        transition: all .25s ease-out;
      }
      .is-current .ProgressBar-icon {
        fill: #00637C;
        background-color: #00637C;
      }
      .is-complete .ProgressBar-icon {
        fill: #DBF1FF;
        background-color: #00637C;
      }

      .ProgressBar-stepLabel {
        display: block;
        text-transform: uppercase;
        color: #9F9FA3;
        position: absolute;
        padding-top: 0.5em;
        width: 100%;
        transition: all .25s ease-out;
      }
      .is-current, .is- > .ProgressBar-stepLabel, .is-complete > .ProgressBar-stepLabel {
        color: #00637C;
      }

      .wrapper {
        max-width: 1000px;
        margin: 4em auto;
        font-size: 16px;
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
  <script type="text/javascript" language="javascript" src="https://cdn.datatables.net/plug-ins/1.10.13/api/sum().js"></script>

  <script type="text/javascript" language="javascript" src="{{ asset('/plugin/js/dataTables.editor.min.js') }}"></script>

  {{-- <script type="text/javascript" language="javascript" src="{{ asset('/plugin/examples/resources/syntax/shCore.js') }}"></script>
  <script type="text/javascript" language="javascript" src="{{ asset('/plugin/examples/resources/demo.js') }}"></script>
  <script type="text/javascript" language="javascript" src="{{ asset('/plugin/examples/resources/editor-demo.js') }}"></script> --}}

  <script type="text/javascript" language="javascript" class="init">

    var listtable;

    var map;
    var gmarkers = Array();
    var locs = [];
    var marker;

    function initMap() {

      @if($deliverytracking[0]->latitude1!="")
        var mapOptions = {
          center: new google.maps.LatLng({{$deliverytracking[0]->latitude1}},{{$deliverytracking[0]->longitude1}}),
          zoom: 12,
          mapTypeId: google.maps.MapTypeId.ROADMAP
        };
      @else
        var mapOptions = {
          center: new google.maps.LatLng(2.959660,101.785942),
          zoom: 12,
          mapTypeId: google.maps.MapTypeId.ROADMAP
        };
      @endif

      map = new google.maps.Map(document.getElementById("map"), mapOptions);
      google.maps.event.addListener(map, 'click', function(e) {
        latInput.value = e.latLng.lat() ;
        longInput.value = e.latLng.lng();
      });

      locs = [
        @foreach($deliverytracking as $timesheet)
          [{{$timesheet->latitude1}}, {{$timesheet->longitude1}}, {{$timesheet->Id}}],
        @endforeach
      ];

      console.log(locs);
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
      // console.log(latitude, longitude)
      // for (var i = 0; i < gmarkers.length; i++) {

      //   if (parseFloat(gmarkers[i].getPosition().lat()).toFixed(8)==parseFloat(latitude).toFixed(8) && parseFloat(gmarkers[i].getPosition().lng()).toFixed(8)==parseFloat(longitude).toFixed(8))
      //   {
      //     gmarkers[i].setIcon('{{ asset('img/engineer_in.png') }}');
      //     map.setZoom(12);
      //     map.setCenter(gmarkers[i].getPosition());
      //   }
      //   else
      //   {
      //     gmarkers[i].setIcon('{{ asset('img/Empty.png') }}');
      //   }
      // }

      // gmarkers.push(marker);

      map.setZoom(12);
      map.setCenter(new google.maps.LatLng(latitude, longitude));

      marker.setMap(map);
    }

    $(document).ready(function() {

      listtable = $('#list').dataTable( {
        dom: "lrftip",
        bAutoWidth: true,
        aaSorting:false,
        sScrollY: "100%",
        sScrollX: "100%",
        columnDefs: [{ "visible": false, "targets": [1,2,3,7,8] },{"className": "dt-center", "targets": "_all"}],
        bScrollCollapse: true,
        columns: [
          { data: null, title:"No"},
          { data:"deliverystatuses.Id", title:"Id"},
          { data:"deliveryform.DO_No", title:"DO No"},
          { data:"deliveryform.delivery_date", title:"Delivery Date"},
          // { data:"deliverystatuses.updated_at", title:"Action Taken"},
          { data:"deliverystatuses.delivery_status", title:"Status"},
          { data:"deliverystatuses.delivery_status_details", title:"Activity"},
          { title:"Action Taken",
            "render": function ( data, type, full, meta ) {
              return '<a data-toggle="modal" data-target="#mapsViewModal" onclick="mapsViewModal" href="#"  target="_blank">'+data+'</a><br><button class="photo btn btn-default" value="'+full.deliverystatuses.Id+'">Photo</button>';
            }
          },
          { data:"deliverytracking.longitude1", title:"Longitude"},
          { data:"deliverytracking.latitude1", title:"Latitude"},
          // {data: null,title:"Action",
          //   render: function (data, type, full, meta) {
          //     return '<a type="button" class="btn btn-xs btn-default"  href="{{url('deliverytrackingdetails')}}">View</a>';
          //   }
          // },
        ],
        //  autoFill: {
        //     editor:  listeditor
        // },
        // select: true,
        // buttons: [
        //       // {
        //       //     text: 'New Bill',
        //       //     action: function ( e, dt, node, config ) {
        //       //       // clearing all select/input options
        //       //       listeditor
        //       //          .create( false )
        //       //          .set( 'agreement.Type', '')
        //       //          .submit();
        //       //   },
        //       // },
        //       { extend: "create", text:'New Bill', editor: listeditor },
        //       { extend: "edit", editor: listeditor },

        //       { extend: "remove", editor: listeditor },
        //       {
        //               extend: 'collection',
        //               text: 'Export',
        //               buttons: [
        //                       'excel',
        //                       'csv',
        //                       'pdf'
        //               ]
        //       }
        // ],

      });

      $('#list').on( 'click', 'tr', function () {
        // Get the rows id value
        //  var row=$(this).closest("tr");
        //  var oTable = row.closest('table').dataTable();
        deliverytrackingid = listtable.api().row( this ).data().deliverytracking.Id;
      });

      $('#list').on( 'click', 'tbody td:not(.child), tbody span.dtr-data', function (e) {
          //   listeditor.inline( this, {
          //  onBlur: 'submit'
          // } );
          var data=listtable.api().row(this).data()
          var json=JSON.stringify(data);
          // if(this.cellIndex==13)
          // {
            myfunction(data.deliverytracking.latitude1,data.deliverytracking.longitude1);
          // }
          // else if(this.cellIndex==12){
            //   myfunction("In",data.timesheets.Latitude_In,data.timesheets.Longitude_In);
          // }
      } );



      listtable.api().on( 'order.dt search.dt', function () {
        listtable.api().column(0, {search:'applied', order:'applied'}).nodes().each(function (cell, i) {
        cell.innerHTML = i+1;
        });
      }).draw();



      $(".list thead input").keyup ( function () {

        /* Filter on the column (the index) of this element */
        if ($('#list').length > 0){

          var colnum=document.getElementById('list').rows[0].cells.length;

          if (this.value=="[empty]")
          {
            listtable.fnFilter( '^$', this.name,true,false );
          }

          else if (this.value=="[nonempty]")
          {
            listtable.fnFilter( '^(?=\\s*\\S).*$', this.name,true,false );
          }

          else if (this.value.startsWith("!")==true && this.value.length>1)
          {
            listtable.fnFilter( '^(?'+ this.value +').*', this.name,true,false );
          }

          else if (this.value.startsWith("!")==false)
          {
            listtable.fnFilter( this.value, this.name,true,false );
          }
        }
      });


      // $('a[data-toggle="tab"]').on( 'shown.bs.tab', function (e) {
      //   var target = $(e.target).attr("href") // activated tab

      //     $.fn.dataTable.tables( {visible: true, api: true} ).columns.adjust();

      // } );
      $("#advance").on("click", function() {
        var $bar = $(".ProgressBar");
        if ($bar.children(".is-current").length > 0) {
          $bar.children(".is-current").removeClass("is-current").addClass("is-complete").next().addClass("is-current");
        } else {
          $bar.children().first().addClass("is-current");
        }
      });

      $("#previous").on("click", function() {
        var $bar = $(".ProgressBar");
        if ($bar.children(".is-current").length > 0) {
          $bar.children(".is-current").removeClass("is-current").prev().removeClass("is-complete").addClass("is-current");
        } else {
          $bar.children(".is-complete").last().removeClass("is-complete").addClass("is-current");
        }
      });

    });

  </script>

  <script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyDuDdIs9T09hGo4LV3dRSiuHVMbUnkS-JE&libraries=places&callback=initMap" async defer></script>

@endsection

@section('content')

  <div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
      <h1>Delivery Tracking
        <small>Delivery Management</small>
      </h1>

      <ol class="breadcrumb">
        <li><a href="{{ url('/home') }}"><i class="fa fa-dashboard"></i> Home</a></li>
        <li><a href="#">Delivery Management</a></li>
        <li class="active">Delivery Tracking</li>
      </ol>
    </section>

    <section class="content">

      <div class="row">

        <!-- modal -->
        <div class="modal fade" id="mapsViewModal" role="dialog" aria-labelledby="mymapsViewModalLabel" style="display: none;">
          <div class="modal-dialog" role="document">
            <div class="modal-content">
              <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true"></span></button>
                <h4 class="modal-title" id="ItemListModalLabel">Location</h4>
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
        <!-- .modal -->
         <div class="modal fade" id="Photo" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
            <div class="modal-dialog" role="document">
              <div class="modal-content">
                <div class="modal-header">
                  <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                  <h4 class="modal-title" id="myModalLabel">Photo</h4>
                </div>
                <div class="modal-body">
                  <div class="form-group" id="showphoto">

                  </div>
                </div>
                <div class="modal-footer">
                  <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                </div>
              </div>
            </div>
      </div>

        <div class="col-md-12">
          <div class="box box-primary">
            <div class="box-header with-border">

              <!-- <div class="col-xs-12">
                <div id="map">
                </div>
              </div> -->

              <div class="col-xs-12">
                <ol class="ProgressBar">
                  @foreach($deliverytrackingstatus as $delivery)
                  <li class="ProgressBar-step {{ $delivery->delivery_status == 'Pending' || $delivery->delivery_status == 'Processing' || $delivery->delivery_status == 'Accepted' || $delivery->delivery_status == 'Recalled' || $delivery->delivery_status == 'Completed' ? 'is-complete is-current' : '' }}">
                    <svg class="ProgressBar-icon"><use xlink:href="#checkmark-bold"/></svg>
                    <span class="ProgressBar-stepLabel">Pending</span>
                  </li>
                  <li class="ProgressBar-step {{ $delivery->delivery_status == 'Processing' || $delivery->delivery_status == 'Accepted' || $delivery->delivery_status == 'Recalled' || $delivery->delivery_status == 'Completed' ? 'is-complete is-current' : '' }}">
                    <svg class="ProgressBar-icon"><use xlink:href="#checkmark-bold"/></svg>
                    <span class="ProgressBar-stepLabel">Processing</span>
                  </li>
                  <li class="ProgressBar-step {{ $delivery->delivery_status == 'Accepted' || $delivery->delivery_status == 'Recalled' || $delivery->delivery_status == 'Completed' ? 'is-complete is-current' : '' }}">
                    <svg class="ProgressBar-icon"><use xlink:href="#checkmark-bold"/></svg>
                    <span class="ProgressBar-stepLabel">Accepted</span>
                  </li>
                  <li class="ProgressBar-step {{ $delivery->delivery_status == 'Completed' ? 'is-complete is-current' : '' }}">
                    <svg class="ProgressBar-icon"><use xlink:href="#checkmark-bold"/></svg>
                    <span class="ProgressBar-stepLabel">Completed</span>
                  </li>
                  @endforeach
                </ol>
                <!-- <svg xmlns="http://www.w3.org/2000/svg">
                  <symbol id="checkmark-bold" viewBox="0 0 24 24">
                    <path d="M20.285 2l-11.285 11.567-5.286-5.011-3.714 3.716 9 8.728 15-15.285z"/>
                  </symbol>
                </svg> -->
              </div>

              <table id="list" class="list" cellspacing="0" width="100%" padding="30px" style="font-size: 13px;">
                <thead>
                  @if($deliverytracking)

                  <tr class="search">
                    @foreach($deliverytracking as $key=>$value)
                      @if ($key==0)
                      <?php $i = 0; ?>
                        @foreach($value as $field=>$a)
                          @if ($i== 0 )
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

                  @endif

                  @foreach($deliverytracking as $key=>$value)

                    @if ($key==0)
                      <td></td>

                      @foreach($value as $field=>$value)
                        <td/>{{ $field }}</td>
                      @endforeach
                    @endif

                  @endforeach
                </thead>
                <tbody>
                  <?php $i = 0; ?>

                  @foreach($deliverytracking as $deliverytrackings)

                    <tr id="row_{{ $i }}" >
                      <td></td>
                      @foreach($deliverytrackings as $key=>$value)
                        <td>{{ $value }}</td>
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
    </section>
  </div>

  <footer class="main-footer">
    <div class="pull-right hidden-xs">
      <b>Version</b> 2.0.1
    </div>
    <strong>Copyright &copy; 2014-2016 <a href="http://www.softoya.com">TrackerOnTheGo</a>.</strong> All rights reserved.
  </footer>

  <script type="text/javascript">
     $(function () {
    $(document).ready(function() {
    $(document).on('click', '.photo', function(e) {
        var statusid = $(this).val();
        console.log(statusid)
         $.ajaxSetup({
          headers: { 'X-CSRF-Token' : $('meta[name=_token]').attr('content') }
        });
        $.ajax({
            url: "{{ url("/trackingphoto/") }}" + "/" + statusid,
            cache: false,
            // data: statusid,
            type: 'GET',
            success: function (response) {
                $('#showphoto').empty();
                if(response.img == "" || response.img == null)
                {
                  var message = "No Image"
                  $('#showphoto').append(message);
                }
                else
                {
                response.img.forEach(function(element) {
                   $('#showphoto').append(`<a href="${element.Web_Path}" target="_blank">View</a><br>`);
                });
                }
                $('#Photo').modal('show');
            },
            error: function (response) {
            }
        });
    });
});

  });
  </script>
@endsection
