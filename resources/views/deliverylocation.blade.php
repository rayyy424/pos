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
        .btn{
        	width:210px;
        }
        table.dataTable .dt-left {
            white-space: pre-wrap;
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
      <script type="text/javascript" language="javascript" src="{{ asset('/plugin/datatables/extensions/Select2/editor.select2.js') }}"></script>

      <script type="text/javascript" language="javascript" src="{{ asset('/plugin/js/dataTables.editor.min.js') }}"></script>
      {{-- chart js --}}
      <script type="text/javascript" language="javascript" src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.4.0/Chart.bundle.min.js"></script>

    <script type="text/javascript" language="javascript" class="init">

        var editor;
        var editor2;
        var oTable;
        var oTable2;
        var asInitVals = new Array();
        var userid;

        $(document).ready(function() {

            editor = new $.fn.dataTable.Editor( {
                ajax: {
                    "url": "{{ asset('/Include/deliverylocation.php') }}"
                },
                table: "#deliverylocationtable",
                idSrc: "deliverylocation.Id",
                fields: [
                {
                    label: "Zone/Area",
                    name: "deliverylocation.area",
                    type: "select2",
                    options: [
                       { label :"", value: "" },
                       @foreach($area as $a)
                       { label :"{{$a->Option}}", value: "{{$a->Option}}" },
                       @endforeach
                    ],
                },
                 {
                    label: "Type",
                    name: "deliverylocation.type",
                    type: "hidden"
                },
                {
                    label: "Price",
                    name: "deliverylocation.price_2ton_to_5ton"
                },
                {
                    label: "Price",
                    name: "deliverylocation.price_5ton_crane"
                },
                {
                    label: "Price",
                    name: "deliverylocation.price_10ton"
                },
                {
                    label: "Price",
                    name: "deliverylocation.price_10ton_crane"
                },
                {
                    name: "deliverylocation.created_by",
                    type: "hidden"
                }
                ]
            } );

            // Activate an inline edit on click of a table cell
            $('#deliverylocationtable').on( 'click', 'tbody td', function (e) {
                editor.inline( this, {
                    onBlur: 'submit'
                } );
            } );

            oTable=$('#deliverylocationtable').dataTable( {
                ajax: {
                    "url": "{{ asset('/Include/deliverylocation.php') }}",
                    "data": {
                          "Target":"%charges%"
                      }
                },
                columnDefs: [
                    { "visible": false, "targets": [1,2] },
                    {"className": "dt-right", "targets": "_all"}
                ],

                responsive: false,
                colReorder: false,
                sScrollX: "100%",
                bScrollCollapse: true,
                bAutoWidth: true,
                sScrollY: "100%",
                dom: "Bfrtip",
                iDisplayLength:10,
                rowId:"deliverylocation.Id",
                order: [ 2, "asc" ],
                columns: [
                    { data: null, "render":"", title:"No"},
                    { data: "deliverylocation.Id", title:"Id"},
                    { data: "deliverylocation.type", title:"Type"},
                    { data: "deliverylocation.area", title:"Area" },
                    { data: "deliverylocation.price_2ton_to_5ton", title:"2-5Ton (RM)",
                    "render": function ( data, type, full, meta ) {
                                              return parseFloat(data).toFixed(2);
                                               } },
                    { data: "deliverylocation.price_5ton_crane", title:"5Ton With Crane (RM)",
                    "render": function ( data, type, full, meta ) {
                                              return parseFloat(data).toFixed(2);
                                               } },
                    { data: "deliverylocation.price_10ton", title:"10Ton (RM)",
                    "render": function ( data, type, full, meta ) {
                                              return parseFloat(data).toFixed(2);
                                               } },
                    { data: "deliverylocation.price_10ton_crane", title:"10Ton With Crane (RM)",
                    "render": function ( data, type, full, meta ) {
                                              return parseFloat(data).toFixed(2);
                                               } },
                    { data: "users.Name", title:"Created_By" }
                ],
                autoFill: {
                    editor:  editor
                },
                select: true,
                buttons: [
                {
                    text: 'New Row',
                    action: function ( e, dt, node, config ) {
                        // clearing all select/input options
                        editor
                        .create( false )
                        .set( 'deliverylocation.created_by', {{ $me->UserId }} )
                        .set( 'deliverylocation.type','charges')
                        .submit();
                    },
                },
                { extend: "remove", editor: editor },
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

            oTable.api().on( 'order.dt search.dt', function () {
                oTable.api().column(0, {search:'applied', order:'applied'}).nodes().each( function (cell, i) {
                    cell.innerHTML = i+1;
                } );
            } ).draw();

            $("thead input").keyup ( function () {

                /* Filter on the column (the index) of this element */

                if ($('#deliverylocationtable').length > 0)
                {
                    var colnum=document.getElementById('deliverylocationtable').rows[0].cells.length;

                    if (this.value=="[empty]")
                    {

                       oTable.fnFilter( '^$', $("thead input").index(this)-colnum,true,false );
                    }
                    else if (this.value=="[nonempty]")
                    {

                       oTable.fnFilter( '^(?=\\s*\\S).*$', $("thead input").index(this)-colnum,true,false );
                    }
                    else if (this.value.startsWith("!")==true && this.value.length>1)
                    {

                       oTable.fnFilter( '^(?'+ this.value +').*', $("thead input").index(this)-colnum,true,false );
                    }
                    else if (this.value.startsWith("!")==false)
                    {
                        oTable.fnFilter( this.value, $("thead input").index(this)-colnum,true,false );
                    }
                }
            } );

               editor2 = new $.fn.dataTable.Editor( {
                ajax: {
                    "url": "{{ asset('/Include/deliverylocation.php') }}"
                },
                table: "#deliverylocationtable2",
                idSrc: "deliverylocation.Id",
                fields: [
                {
                    label: "Zone/Area",
                    name: "deliverylocation.area",
                    type: "select2",
                    options: [
                       { label :"", value: "" },
                       @foreach($area as $a)
                       { label :"{{$a->Option}}", value: "{{$a->Option}}" },
                       @endforeach
                    ],
                },
                 {
                    label: "Type",
                    name: "deliverylocation.type",
                    type: "hidden"
                },
                {
                    label: "Price",
                    name: "deliverylocation.price_2ton_to_5ton"
                },
                {
                    label: "Price",
                    name: "deliverylocation.price_5ton_crane"
                },
                {
                    label: "Price",
                    name: "deliverylocation.price_10ton"
                },
                {
                    label: "Price",
                    name: "deliverylocation.price_10ton_crane"
                },
                {
                    name: "deliverylocation.created_by",
                    type: "hidden"
                }
                ]
            } );

            // Activate an inline edit on click of a table cell
            $('#deliverylocationtable2').on( 'click', 'tbody td', function (e) {
                editor2.inline( this, {
                    onBlur: 'submit'
                } );
            } );

            oTable2=$('#deliverylocationtable2').dataTable( {
                ajax: {
                    "url": "{{ asset('/Include/deliverylocation.php') }}",
                    "data": {
                          "Target":"%incentive%"
                      }
                },
                columnDefs: [
                    { "visible": false, "targets": [1,2] },
                    {"className": "dt-right", "targets": "_all"}
                ],

                responsive: false,
                colReorder: false,
                sScrollX: "100%",
                bScrollCollapse: true,
                bAutoWidth: true,
                sScrollY: "100%",
                dom: "Bfrtip",
                iDisplayLength:10,
                rowId:"deliverylocation.Id",
                order: [ 2, "asc" ],
                columns: [
                    { data: null, "render":"", title:"No"},
                    { data: "deliverylocation.Id", title:"Id"},
                    { data: "deliverylocation.type", title:"Type"},
                    { data: "deliverylocation.area", title:"Area" },
                    { data: "deliverylocation.price_2ton_to_5ton", title:"2-5Ton (RM)",
                    "render": function ( data, type, full, meta ) {
                                              return parseFloat(data).toFixed(2);
                                               } },
                    { data: "deliverylocation.price_5ton_crane", title:"5Ton With Crane (RM)",
                    "render": function ( data, type, full, meta ) {
                                              return parseFloat(data).toFixed(2);
                                               } },
                    { data: "deliverylocation.price_10ton", title:"10Ton (RM)",
                    "render": function ( data, type, full, meta ) {
                                              return parseFloat(data).toFixed(2);
                                               } },
                    { data: "deliverylocation.price_10ton_crane", title:"10Ton With Crane (RM)",
                "render": function ( data, type, full, meta ) {
                                              return parseFloat(data).toFixed(2);
                                               }},
                    { data: "users.Name", title:"Created_By" }
                ],
                autoFill: {
                    editor:  editor2
                },
                select: true,
                buttons: [
                {
                    text: 'New Row',
                    action: function ( e, dt, node, config ) {
                        // clearing all select/input options
                        editor2
                        .create( false )
                        .set( 'deliverylocation.created_by', {{ $me->UserId }} )
                        .set( 'deliverylocation.type','incentive')
                        .submit();
                    },
                },
                { extend: "remove", editor: editor2 },
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

            oTable2.api().on( 'order.dt search.dt', function () {
                oTable2.api().column(0, {search:'applied', order:'applied'}).nodes().each( function (cell, i) {
                    cell.innerHTML = i+1;
                } );
            } ).draw();

            $("thead input").keyup ( function () {

                /* Filter on the column (the index) of this element */

                if ($('#deliverylocationtable2').length > 0)
                {
                    var colnum=document.getElementById('deliverylocationtable2').rows[0].cells.length;

                    if (this.value=="[empty]")
                    {

                       oTable2.fnFilter( '^$', $("thead input").index(this)-colnum,true,false );
                    }
                    else if (this.value=="[nonempty]")
                    {

                       oTable2.fnFilter( '^(?=\\s*\\S).*$', $("thead input").index(this)-colnum,true,false );
                    }
                    else if (this.value.startsWith("!")==true && this.value.length>1)
                    {

                       oTable2.fnFilter( '^(?'+ this.value +').*', $("thead input").index(this)-colnum,true,false );
                    }
                    else if (this.value.startsWith("!")==false)
                    {
                        oTable2.fnFilter( this.value, $("thead input").index(this)-colnum,true,false );
                    }
                }
            } );


            $('a[data-toggle="tab"]').on( 'shown.bs.tab', function (e) {
               var target = $(e.target).attr("href") // activated tab
               if (target=="#charges")
               {
                 $.fn.dataTable.tables( {visible: true, api: true} ).columns.adjust();
               }
               else if (target=="#incentive")
               {
                 $.fn.dataTable.tables( {visible: true, api: true} ).columns.adjust();
               }
             } );

        
        } );
    </script>

@endsection

@section('content')

<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
    	<h1>Delivery Location Management
    		<small>Admin</small>
    	</h1>

    	<ol class="breadcrumb">
    		<li><a href="{{ url('/home') }}"><i class="fa fa-dashboard"></i> Home</a></li>
    		<li><a href="#">Admin</a></li>
    		<li class="active">Delivery Location Management</li>
    	</ol>
    </section>

    <br>

    <section class="content">
    	<div class="row">
    		<div class="col-md-12">
    			<div class="box">
    				<div class="box-body">
              
              <div class="nav-tabs-custom">
                    <ul class="nav nav-tabs">
                      <li class="active"><a href="#charges" data-toggle="tab" id="chargestab">Transport Charges</a></li>
                      <li><a href="#incentive" data-toggle="tab" id="incentivetab">Driver Incentive</a></li>
                    </ul>

                    <div class="tab-content">
                    <div class="active tab-pane" id="charges">
    					<table id="deliverylocationtable" class="display" cellspacing="0" width="100%" padding="30px" style="font-size: 13px;">
    						<thead>
    							<tr class="search">
                                @foreach($charges as $key=>$value)

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
                                    @foreach($charges as $key=>$value)

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
                                @foreach($charges as $delivery)

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

                    <div class="tab-pane" id="incentive">
                        <table id="deliverylocationtable2" class="display" cellspacing="0" width="100%" padding="30px" style="font-size: 13px;">
                            <thead>
                                <tr class="search">
                                @foreach($incentive as $key=>$value)

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
                                    @foreach($incentive as $key=>$value)

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
                                @foreach($incentive as $delivery)

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
                <!--tab content-->
                </div>
                <!--Nav Tabs Custom-->
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
	<strong>Copyright &copy; 2014-2016 <a href="http://www.softoya.com">TrackerOnTheGo</a>.</strong> All rights reserved.
</footer>

@endsection