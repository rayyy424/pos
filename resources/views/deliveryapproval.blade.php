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

    .tableheader {
        background-color: gray;
	}

    .container1 {
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
		#map{
			height: 300px;
    	margin: 0 auto;
		}
		a:hover {
			cursor:pointer;
		}
		table.dataTable tbody th,table.dataTable tbody td
    {
      white-space: nowrap;
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

<script type="text/javascript" language="javascript" src="{{ asset('/plugin/js/dataTables.editor.min.js') }}"></script>

<script type="text/javascript" language="javascript" class="init">

  var oTable;
  var processingtable;
  var acceptedtable;
  var completedtable;
  var recalledtable;
  var rejectedtable;
  var insufficienttable;
  var canceltable;
  // var map;

	$(window).trigger('resize');

  $(document).ready(function() {
    // pendingtable
    // oTable = $('#pendingtable').dataTable( {
    //   columnDefs: [
    //     { "visible": false, "targets": [12,13] },
    //   ],
    //   // //responsive: true,
    //   colReorder: false,
    //   sScrollX: "100%",
    //   bScrollCollapse: true,
    //   bAutoWidth: true,
    //   sScrollY: "100%",
    //   dom: "BWlfrtip",
    //   iDisplayLength:10,
    //   buttons: [{
    //     extend: 'collection',
    //     text: 'Export',
    //     buttons: [
    //     'excel',
    //     'csv',
    //     'pdf'
    //     ]
    //   },
    //   ],
    // });

    // oTable.api().on( 'order.dt search.dt', function () {
    //   oTable.api().column(0, {search:'applied', order:'applied'}).nodes().each( function (cell, i) {
    //     cell.innerHTML = i+1;
    //   } );
    // } ).draw();

    // $(".pendingtable thead input").keyup ( function () {

    //   /* Filter on the column (the index) of this element */
    //   if ($('#pendingtable').length > 0)
    //   {
    //     var colnum=document.getElementById('pendingtable').rows[0].cells.length;
    //     if (this.value=="[empty]")
    //     {
    //       oTable.fnFilter( '^$', this.name,true,false );
    //     }
    //     else if (this.value=="[nonempty]")
    //     {
    //       oTable.fnFilter( '^(?=\\s*\\S).*$', this.name,true,false );
    //     }
    //     else if (this.value.startsWith("!")==true && this.value.length>1)
    //     {
    //       oTable.fnFilter( '^(?'+ this.value +').*', this.name,true,false );
    //     }
    //     else if (this.value.startsWith("!")==false)
    //     {
    //       oTable.fnFilter( this.value, this.name,true,false );
    //     }
    //   }
    // } );

    // processingtable
    processingtable=$('#processingtable').dataTable( {
      columnDefs: [
        { "visible": false, "targets": [-1,-2] },
      ],
      "dom": "BWlfrtip",
      "bInfo": true,
      //responsive: true,
      colReorder: false, sScrollX: "100%",
      bScrollCollapse: true,
      bAutoWidth: true,
      sScrollY: "100%",
      iDisplayLength:25,
      buttons: [{
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

    processingtable.api().on( 'order.dt search.dt', function () {
      processingtable.api().column(0, {search:'applied', order:'applied'}).nodes().each( function (cell, i) {
        cell.innerHTML = i+1;
      } );
    } ).draw();

    $(".processingtable thead input").keyup ( function () {

      /* Filter on the column (the index) of this element */
      if ($('#processingtable').length > 0)
      {
        var colnum=document.getElementById('processingtable').rows[0].cells.length;
        if (this.value=="[empty]")
        {
          processingtable.fnFilter( '^$', this.name,true,false );
        }
        else if (this.value=="[nonempty]")
        {
          processingtable.fnFilter( '^(?=\\s*\\S).*$', this.name,true,false );
        }
        else if (this.value.startsWith("!")==true && this.value.length>1)
        {
          processingtable.fnFilter( '^(?'+ this.value +').*', this.name,true,false );
        }
        else if (this.value.startsWith("!")==false)
        {
          processingtable.fnFilter( this.value, this.name,true,false );
        }
      }
    });

    // acceptedtable
    acceptedtable=$('#acceptedtable').dataTable( {
      columnDefs: [
        { "visible": false, "targets": [-1,-2] },
      ],
      "dom": "BWlfrtip",
      "bInfo": true,
      //responsive: true,
      colReorder: false,
      sScrollX: "100%",
      bScrollCollapse: true,
      bAutoWidth: true,
      sScrollY: "100%",
      iDisplayLength:25,
      buttons: [{
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

    acceptedtable.api().on( 'order.dt search.dt', function () {
      acceptedtable.api().column(0, {search:'applied', order:'applied'}).nodes().each( function (cell, i) {
        cell.innerHTML = i+1;
      } );
    } ).draw();

    $(".acceptedtable thead input").keyup ( function () {

      /* Filter on the column (the index) of this element */
      if ($('#acceptedtable').length > 0)
      {
        var colnum=document.getElementById('acceptedtable').rows[0].cells.length;
        if (this.value=="[empty]")
        {
          acceptedtable.fnFilter( '^$', this.name,true,false );
        }
        else if (this.value=="[nonempty]")
        {
          acceptedtable.fnFilter( '^(?=\\s*\\S).*$', this.name,true,false );
        }
        else if (this.value.startsWith("!")==true && this.value.length>1)
        {
          acceptedtable.fnFilter( '^(?'+ this.value +').*', this.name,true,false );
        }
        else if (this.value.startsWith("!")==false)
        {
          acceptedtable.fnFilter( this.value, this.name,true,false );
        }
      }
    });

    // completedtable
    completedtable=$('#completedtable').dataTable( {
      columnDefs: [
        { "visible": false, "targets": [-1] },
      ],
      "dom": "BWlfrtip",
      "bInfo": true,
      //responsive: true,
      colReorder: false,
      sScrollX: "100%",
      bScrollCollapse: true,
      bAutoWidth: true,
      sScrollY: "100%",
      iDisplayLength:25,
      buttons: [{
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

    completedtable.api().on( 'order.dt search.dt', function () {
      completedtable.api().column(0, {search:'applied', order:'applied'}).nodes().each( function (cell, i) {
        cell.innerHTML = i+1;
      } );
    } ).draw();

    $(".completedtable thead input").keyup ( function () {

      /* Filter on the column (the index) of this element */
      if ($('#completedtable').length > 0)
      {
        var colnum=document.getElementById('completedtable').rows[0].cells.length;
        if (this.value=="[empty]")
        {
          completedtable.fnFilter( '^$', this.name,true,false );
        }
        else if (this.value=="[nonempty]")
        {
          completedtable.fnFilter( '^(?=\\s*\\S).*$', this.name,true,false );
        }
        else if (this.value.startsWith("!")==true && this.value.length>1)
        {
          completedtable.fnFilter( '^(?'+ this.value +').*', this.name,true,false );
        }
        else if (this.value.startsWith("!")==false)
        {
          completedtable.fnFilter( this.value, this.name,true,false );
        }
      }
    });

    // completedtable
    incompletedtable=$('#incompletedtable').dataTable( {
      columnDefs: [
        { "visible": false, "targets": [-1] },
      ],
      "dom": "BWlfrtip",
      "bInfo": true,
      //responsive: true,
      colReorder: false,
      sScrollX: "100%",
      bScrollCollapse: true,
      bAutoWidth: true,
      sScrollY: "100%",
      iDisplayLength:25,
      buttons: [{
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

    incompletedtable.api().on( 'order.dt search.dt', function () {
      incompletedtable.api().column(0, {search:'applied', order:'applied'}).nodes().each( function (cell, i) {
        cell.innerHTML = i+1;
      } );
    } ).draw();

    $(".incompletedtable thead input").keyup ( function () {

      /* Filter on the column (the index) of this element */
      if ($('#incompletedtable').length > 0)
      {
        var colnum=document.getElementById('incompletedtable').rows[0].cells.length;
        if (this.value=="[empty]")
        {
          incompletedtable.fnFilter( '^$', this.name,true,false );
        }
        else if (this.value=="[nonempty]")
        {
          incompletedtable.fnFilter( '^(?=\\s*\\S).*$', this.name,true,false );
        }
        else if (this.value.startsWith("!")==true && this.value.length>1)
        {
          incompletedtable.fnFilter( '^(?'+ this.value +').*', this.name,true,false );
        }
        else if (this.value.startsWith("!")==false)
        {
          incompletedtable.fnFilter( this.value, this.name,true,false );
        }
      }
    });

    // rejectedtable
    rejectedtable=$('#rejectedtable').dataTable( {
      columnDefs: [
        { "visible": false, "targets": [13,14] },
      ],
      "dom": "BWlfrtip",
      "bInfo": true,
      //responsive: true,
      colReorder: false,
      sScrollX: "100%",
      bScrollCollapse: true,
      bAutoWidth: true,
      sScrollY: "100%",
      iDisplayLength:25,
      buttons: [{
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

    rejectedtable.api().on( 'order.dt search.dt', function () {
      rejectedtable.api().column(0, {search:'applied', order:'applied'}).nodes().each( function (cell, i) {
        cell.innerHTML = i+1;
      } );
    } ).draw();

    $(".rejectedtable thead input").keyup ( function () {

      /* Filter on the column (the index) of this element */
      if ($('#rejectedtable').length > 0)
      {
        var colnum=document.getElementById('rejectedtable').rows[0].cells.length;
        if (this.value=="[empty]")
        {
          rejectedtable.fnFilter( '^$', this.name,true,false );
        }
        else if (this.value=="[nonempty]")
        {
          rejectedtable.fnFilter( '^(?=\\s*\\S).*$', this.name,true,false );
        }
        else if (this.value.startsWith("!")==true && this.value.length>1)
        {
          rejectedtable.fnFilter( '^(?'+ this.value +').*', this.name,true,false );
        }
        else if (this.value.startsWith("!")==false)
        {
          rejectedtable.fnFilter( this.value, this.name,true,false );
        }
      }
    });

    // recalledtable
    recalledtable=$('#recalledtable').dataTable( {
      columnDefs: [
        { "visible": false, "targets": [13,14] },
      ],
      "dom": "BWlfrtip",
      "bInfo": true,
      //responsive: true,
      colReorder: false,
      sScrollX: "100%",
      bScrollCollapse: true,
      bAutoWidth: true,
      sScrollY: "100%",
      iDisplayLength:25,
      buttons: [{
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

    recalledtable.api().on( 'order.dt search.dt', function () {
      recalledtable.api().column(0, {search:'applied', order:'applied'}).nodes().each( function (cell, i) {
        cell.innerHTML = i+1;
      } );
    } ).draw();

    $(".recalledtable thead input").keyup ( function () {

      /* Filter on the column (the index) of this element */
      if ($('#recalledtable').length > 0)
      {
        var colnum=document.getElementById('recalledtable').rows[0].cells.length;
        if (this.value=="[empty]")
        {
          recalledtable.fnFilter( '^$', this.name,true,false );
        }
        else if (this.value=="[nonempty]")
        {
          recalledtable.fnFilter( '^(?=\\s*\\S).*$', this.name,true,false );
        }
        else if (this.value.startsWith("!")==true && this.value.length>1)
        {
          recalledtable.fnFilter( '^(?'+ this.value +').*', this.name,true,false );
        }
        else if (this.value.startsWith("!")==false)
        {
          recalledtable.fnFilter( this.value, this.name,true,false );
        }
      }
    });

    // transfertable
    transfertable=$('#transfertable').dataTable( {
      columnDefs: [
        { "visible": false, "targets": [-1,-2] },
      ],
      "dom": "BWlfrtip",
      "bInfo": true,
      colReorder: false,
      sScrollX: "100%",
      bScrollCollapse: true,
      bAutoWidth: true,
      sScrollY: "100%",
      iDisplayLength:25,
      buttons: [{
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

    transfertable.api().on( 'order.dt search.dt', function () {
      transfertable.api().column(0, {search:'applied', order:'applied'}).nodes().each( function (cell, i) {
        cell.innerHTML = i+1;
      } );
    } ).draw();

    $(".transfertable thead input").keyup ( function () {

      /* Filter on the column (the index) of this element */
      if ($('#transfertable').length > 0)
      {
        var colnum=document.getElementById('transfertable').rows[0].cells.length;
        if (this.value=="[empty]")
        {
          transfertable.fnFilter( '^$', this.name,true,false );
        }
        else if (this.value=="[nonempty]")
        {
          transfertable.fnFilter( '^(?=\\s*\\S).*$', this.name,true,false );
        }
        else if (this.value.startsWith("!")==true && this.value.length>1)
        {
          transfertable.fnFilter( '^(?'+ this.value +').*', this.name,true,false );
        }
        else if (this.value.startsWith("!")==false)
        {
          transfertable.fnFilter( this.value, this.name,true,false );
        }
      }
    });

    // inssuficienttable
    insufficienttable=$('#insufficienttable').dataTable( {
      columnDefs: [
        { "visible": false, "targets": [13,14] },
      ],
      "dom": "BWlfrtip",
      "bInfo": true,
      //responsive: true,
      colReorder: false,
      sScrollX: "100%",
      bScrollCollapse: true,
      bAutoWidth: true,
      sScrollY: "100%",
      iDisplayLength:25,
      buttons: [{
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

    insufficienttable.api().on( 'order.dt search.dt', function () {
      insufficienttable.api().column(0, {search:'applied', order:'applied'}).nodes().each( function (cell, i) {
        cell.innerHTML = i+1;
      } );
    } ).draw();

    $(".insufficienttable thead input").keyup ( function () {

      /* Filter on the column (the index) of this element */
      if ($('#insufficienttable').length > 0)
      {
        var colnum=document.getElementById('insufficienttable').rows[0].cells.length;
        if (this.value=="[empty]")
        {
          insufficienttable.fnFilter( '^$', this.name,true,false );
        }
        else if (this.value=="[nonempty]")
        {
          insufficienttable.fnFilter( '^(?=\\s*\\S).*$', this.name,true,false );
        }
        else if (this.value.startsWith("!")==true && this.value.length>1)
        {
          insufficienttable.fnFilter( '^(?'+ this.value +').*', this.name,true,false );
        }
        else if (this.value.startsWith("!")==false)
        {
          insufficienttable.fnFilter( this.value, this.name,true,false );
        }
      }
    });
      //Cancel Table
canceltable=$('#canceltable').dataTable( {
      columnDefs: [
        { "visible": false, "targets": [1] },
      ],
      "dom": "BWlfrtip",
      "bInfo": true,
      //responsive: true,
      colReorder: false, sScrollX: "100%",
      bScrollCollapse: true,
      bAutoWidth: true,
      sScrollY: "100%",
      iDisplayLength:25,
      buttons: [{
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

    canceltable.api().on( 'order.dt search.dt', function () {
      canceltable.api().column(0, {search:'applied', order:'applied'}).nodes().each( function (cell, i) {
        cell.innerHTML = i+1;
      } );
    } ).draw();

    $(".canceltable thead input").keyup ( function () {

      /* Filter on the column (the index) of this element */
      if ($('#canceltable').length > 0)
      {
        var colnum=document.getElementById('canceltable').rows[0].cells.length;
        if (this.value=="[empty]")
        {
          canceltable.fnFilter( '^$', this.name,true,false );
        }
        else if (this.value=="[nonempty]")
        {
          canceltable.fnFilter( '^(?=\\s*\\S).*$', this.name,true,false );
        }
        else if (this.value.startsWith("!")==true && this.value.length>1)
        {
          canceltable.fnFilter( '^(?'+ this.value +').*', this.name,true,false );
        }
        else if (this.value.startsWith("!")==false)
        {
          canceltable.fnFilter( this.value, this.name,true,false );
        }
      }
    });
    $("#ajaxloader5").hide();
      $(function(){
    $('a[data-toggle="tab"]').on( 'shown.bs.tab', function (e) {
      var target = $(e.target).attr("href") // activated tab
      $.fn.dataTable.tables( {visible: true, api: true} ).columns.adjust();
    } );
  })

  });

  //       $(function(){

  //           var t=$("#acceptedtable").DataTable({
  //           	// dom: "Blftp",
		// 		sScrollX: "100%",
		// 		scrollCollapse: true,
		// 		iDisplayLength:25,
  //           });

  //           var t=$("#pendingtable").DataTable({
		// 		"bInfo": true,
  //           	// dom: "Blftp",
		// 		sScrollX: "100%",
		// 		scrollCollapse: true,
		// 		iDisplayLength:25,
  //           });
		// 	var t=$("#processingtable").DataTable({
		// 		"bInfo": true,
		// 		// dom: "frtp",
		// 		sScrollX: "100%",
		// 		scrollCollapse: true,
		// 		iDisplayLength:25,
  //           });
		// 	var t=$("#completedtable").DataTable({
		// 	    "bInfo": true,
		// 		// dom: "frtp",
		// 		sScrollX: "100%",
		// 		scrollCollapse: true,
		// 		iDisplayLength:25,
  //           });
  //           var t=$("#recalledtable").DataTable({
		// 		"bInfo": true,
  //           	// dom: "Blftp",
		// 		sScrollX: "100%",
		// 		scrollCollapse: true,
		// 		iDisplayLength:25,
  //           });
		// 	var t=$("#rejectedtable").DataTable({
		// 		"bInfo": true,
		// 		sScrollX: "100%",
		// 		iDisplayLength:25,
		// 		scrollCollapse: true,
  //           });
		// 	var t=$("#insufficienttable").DataTable({
		// 		"bInfo": true,
		// 		sScrollX: "100%",
		// 		iDisplayLength:25,
		// 		scrollCollapse: true,
  //           });
  //           $('a[data-toggle="tab"]').on( 'shown.bs.tab', function (e) {
		// 		$($.fn.dataTable.tables( true )).css('width', '100%');
		// 		$($.fn.dataTable.tables( true )).DataTable().columns.adjust().draw();
  //   		});
		// });

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

		function initMap() {
			// var mapOptions = {
   //              center: new google.maps.LatLng(0,0),
   //              zoom: 12,
   //              mapTypeId: google.maps.MapTypeId.ROADMAP
   //          };

           // var map = new google.maps.Map(document.getElementById("map"), mapOptions);
           var map = new google.maps.Map(document.getElementById('map'), {
        center: { lat: 34.397, lng: 150.644 },
        scrollwheel: false,
        zoom: 12
        });

        google.maps.event.addListener(map, 'click', function(e) {
        latInput.value = e.latLng.lat() ;
        longInput.value = e.latLng.lng();
      });
		}

		function myfunction(latitude, longitude){
      initMap();
      console.log(map)
      var map = new google.maps.Map(document.getElementById('map'), {
        center: { lat: latitude, lng: longitude },
        scrollwheel: false,
        zoom: 12
        });
			$('#mapModal').modal('show');
			map.setZoom(12);
			map.setCenter(new google.maps.LatLng(latitude, longitude),12);
			var marker= new google.maps.Marker({
				position: new google.maps.LatLng(latitude, longitude),
				map: map,
				icon:"{{ asset('img/map-marker-icon.png') }}"
			});
			marker.setMap(map);
		}

	</script>
	<script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyDuDdIs9T09hGo4LV3dRSiuHVMbUnkS-JE&libraries=places&callback=initMap" async defer></script>

@endsection

@section('content')

<div class="content-wrapper">

	<!-- Content Header (Page header) -->
	<section class="content-header">
		<h1>Delivery Approval<small>Delivery Management</small></h1>

		<ol class="breadcrumb">
			<li><a href="{{ url('/home') }}"><i class="fa fa-dashboard"></i> Home</a></li>
			<li><a href="#">Delivery Management</a></li>
			<li class="active">Delivery Approval</li>
		</ol>
	</section>

	<br>

	<section class="content">

		<div class="row">

          <div class="modal fade" id="Cancel" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
            <div class="modal-dialog" role="document">
              <div class="modal-content">
                <div class="modal-header">
                  <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                  <h4 class="modal-title" id="myModalLabel">Cancellation Approval</h4>
                </div>
                <div class="modal-body">
                  <div class="form-group" id="approvecancel">

                  </div>
                  Are you sure you wish to approve this request?
                  </div>
                <div class="modal-footer">
                  <center><img src="{{ URL::to('/') ."/img/ajax-loader.gif" }}" width="50px" height="50px" alt="Loading" name='ajaxloader5' id="ajaxloader5"></center>
                  <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                  <button type="button" class="btn btn-primary" onclick="approve()">Approve</button>
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
              <div class="form-group" id="deleteconfirm">

              </div>
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
      <div class="col-md-3">
                   <div class="input-group">
                     <label>Date:</label>
                     <input type="text" class="form-control" id="range" name="range">

                   </div>
                 </div>
      <div class="col-md-2">
                     <div class="input-group">
                      <br>
                       <button type="button" class="btn btn-success btn" data-toggle="modal" onclick="refresh();">Refresh</button>
                     </div>
                 </div>
      </div>
      <br><br>
      <div class="col-md-12">
        <div class="nav-tabs-custom">
          <ul id="tabs" class="nav nav-tabs">
            <!-- <li class="active"><a href="#pendingdelivery"  data-toggle="tab" id="pendingdeliverytab">Pending Delivery</a></li> -->
            <li><a href="#processingdelivery" data-toggle="tab" id="processingdeliverytab">Processing Delivery</a></li>
            <li><a href="#accepteddelivery" data-toggle="tab" id="accepteddeliverytab">Accepted Delivery</a></li>
						<li><a href="#completeddelivery" data-toggle="tab" id="completeddeliverytab">Completed Delivery</a></li>
            <li><a href="#incompleteddelivery" data-toggle="tab" id="incompleteddeliverytab">Incomplete Delivery</a></li>
            <li><a href="#recalleddelivery" data-toggle="tab" id="recalleddeliverytab">Recalled Delivery</a></li>
            <li><a href="#transferdelivery" data-toggle="tab" id="transferdeliverytab">Transfer Delivery</a></li>
						<li><a href="#rejecteddelivery" data-toggle="tab" id="rejecteddeliverytab">Rejected Delivery</a></li>
						<li><a href="#insufficientdelivery" data-toggle="tab" id="insufficientdeliverytab">Insufficient Delivery</a></li>
            <li><a href="#canceldelivery" data-toggle="tab" id="canceldeliverytab">Cancel Delivery</a></li>
						<li><a href="#calendarschedule" data-toggle="tab" id="calendarscheduletab">Delivery Schedule</a></li>
	        </ul>
	        <div class="tab-content">

	        	<div class="tab-pane" id="calendarschedule">
	        		<div class="row">
	        			<div class="col-md-4">
		        			<div class="box-header">
		        				<h3 class="box-title">Delivery Schedule</h3>
		        			</div>
  	        			<div align="center">
		        				<i class="fa fa-circle success"></i> Accepted&nbsp;&nbsp;&nbsp;
                  	<i class="fa fa-circle alert2"></i> Processing&nbsp;&nbsp;&nbsp;
                  	<i class="fa fa-circle warning"></i> Pending&nbsp;&nbsp;&nbsp;
		        			</div>
		        			<div id="calendar"></div>
		        		</div>
	        		</div>
	        	</div>

            	<br><br>

			        	<!-- Processing Delivery -->
			            <div class="active tab-pane" id="processingdelivery">

			            	<div class="row">
				            	<div class="col-xs-12">
				            		<div class="box-header">
				            			<h4>Flow for Delivery Order and Return Note</h4>
				            		</div>
					                <ol class="ProgressBar">
					                	<li class="ProgressBar-step">
						                    <svg class="ProgressBar-icon"><use xlink:href="#checkmark-bold"/></svg>
						                    <span class="ProgressBar-stepLabel">New Request</span>
						                </li>
						                <li class="ProgressBar-step">
						                    <svg class="ProgressBar-icon"><use xlink:href="#checkmark-bold"/></svg>
						                    <span class="ProgressBar-stepLabel">Admin Approval Pending Request</span>
						                </li>
						                <li class="ProgressBar-step">
						                    <svg class="ProgressBar-icon"><use xlink:href="#checkmark-bold"/></svg>
						                    <span class="ProgressBar-stepLabel">Warehouse Approval<br>
						                    	(If Insufficient Stock, please check on the Processing Tabs)
						                    </span>
						                </li>
						                <li class="ProgressBar-step">
						                    <svg class="ProgressBar-icon"><use xlink:href="#checkmark-bold"/></svg>
						                    <span class="ProgressBar-stepLabel">Driver Accept Trip</span>
						                </li>
						                <li class="ProgressBar-step">
						                    <svg class="ProgressBar-icon"><use xlink:href="#checkmark-bold"/></svg>
						                    <span class="ProgressBar-stepLabel">Driver Complete Trip</span>
						                </li>
						                <li class="ProgressBar-step">
						                    <svg class="ProgressBar-icon"><use xlink:href="#checkmark-bold"/></svg>
						                    <span class="ProgressBar-stepLabel">Final Approval by Requestor or Admin</span>
						                </li>
					                </ol>
					                <!-- <svg xmlns="http://www.w3.org/2000/svg">
					                  <symbol id="checkmark-bold" viewBox="0 0 24 24">
					                    <path d="M20.285 2l-11.285 11.567-5.286-5.011-3.714 3.716 9 8.728 15-15.285z"/>
					                  </symbol>
					                </svg> -->
					            </div>
			            	</div>
			            	<br><br>

			            	<table id="processingtable" class="processingtable" cellspacing="0" width="100%" padding="30px" style="font-size: 13px; white-space: pre-line;">
			            		<thead>
                        <tr class="search">
                        @foreach($processing as $key=>$value)
                          @if ($key==0)
                            <?php $i = 0; ?>
                              @foreach($value as $field=>$a)
                                @if ($i==0)
                                  <th align='center'><input type='hidden' class='search_init' name='{{$i}}' placemark='{{$a}}'></th>
                                @else
                                  <th align='center'><input type='text' class='search_init' name='{{$i}}'  placemark='{{$a}}'></th>
                                @endif
                                <?php $i ++; ?>
                                @endforeach
                              <!-- <th align='center'><input type='hidden' class='search_init' name='{{$i}}'  placemark='{{$a}}'></th> -->
                            @endif
                          @endforeach
                        </tr>
			            			<tr>
			            				<td>No</td>
                          <td>Do No</td>
                          <td>Vehicle Number</td>
                          <td>Driver Name</td>
                          <td>Delivery Date</td>
                          <td>Delivery Time</td>
                          <td>Pick Up Date</td>
                          <td>Pick Up Time</td>
                          <td>Location</td>
                          <td>State</td>
                          <td>Purpose</td>
                          <td>Request By</td>
                          <td>Activity</td>
                          <td>Trip Type</td>
                          <td>Action</td>
                          <td>Long</td>
                          <td>Lat</td>
                        </tr>
	                    </thead>
	                    <tbody>
                        <?php $i=1; $processingData=array(); ?>
		                    	@foreach($processing as $processing)
			                    	<?php $processingData[]=$processing;?>
			                    	<tr id="row_{{$processing->Id}}">
          										<td>{{$i}}</td>
          										<td><b>{{$processing->DO_No}}</b></td>
          										<td>{{$processing->Vehicle_No}}</td>
          										<td>{{$processing->Name ==null ? "-":$processing->Name}}</td>
          										<td>{{$processing->delivery_date}}</td>
          										<td>{{$processing->delivery_time}}</td>
                              <td>{{$processing->pickup_date}}</td>
          										<td>{{$processing->pick_up_time}}</td>
          										<td>
          											<a onclick="myfunction({{$processing->Latitude}},{{$processing->Longitude}})">{{$processing->Location_Name}}</a>
          										</td>
                              <td>{{$processing->State}}</td>
          										<td>{{$processing->Purpose}}</td>
          										<td>{{$processing->requestorname}}</td>
          										<td>{{$processing->delivery_status_details}}</td>
                              <td>{{$processing->trip}}</td>
          										<td>
          											<a href="{{url('/deliverydetails')}}/{{$processing->Id}}" class="btn btn-default btn-xs" title="VIEW" style="width:unset" target="_blank"><i class="fa fa-eye"></i></a>
                                @if($me->Delete_Delivery)
                                <button class="deleteprop btn btn-default btn-xs" title="DELETE" style="width:unset" id="{{$processing->Id}}"><i class="fa fa-trash"></i></button>
                                @endif
          										</td>
                              <td>{{$processing->Longitude}}</td>
                              <td>{{$processing->Latitude}}</td>
		                        </tr>
		                        <?php $i+=1;?>
	                        @endforeach
	                    </tbody>
		                </table>
			            </div>
			            <!-- .Processing Delivery -->

			            <!-- Accepted Delivery -->
			            <div class="tab-pane" id="accepteddelivery">

			            	<div class="row">
				            	<div class="col-xs-12">
				            		<div class="box-header">
				            			<h4>Flow for Delivery Order and Return Note</h4>
				            		</div>
					                <ol class="ProgressBar">
					                	<li class="ProgressBar-step">
						                    <svg class="ProgressBar-icon"><use xlink:href="#checkmark-bold"/></svg>
						                    <span class="ProgressBar-stepLabel">New Request</span>
						                </li>
						                <li class="ProgressBar-step">
						                    <svg class="ProgressBar-icon"><use xlink:href="#checkmark-bold"/></svg>
						                    <span class="ProgressBar-stepLabel">Admin Approval Pending Request</span>
						                </li>
						                <li class="ProgressBar-step">
						                    <svg class="ProgressBar-icon"><use xlink:href="#checkmark-bold"/></svg>
						                    <span class="ProgressBar-stepLabel">Warehouse Approval<br>
						                    	(If Insufficient Stock, please check on the Processing Tabs)
						                    </span>
						                </li>
						                <li class="ProgressBar-step">
						                    <svg class="ProgressBar-icon"><use xlink:href="#checkmark-bold"/></svg>
						                    <span class="ProgressBar-stepLabel">Driver Accept Trip</span>
						                </li>
						                <li class="ProgressBar-step">
						                    <svg class="ProgressBar-icon"><use xlink:href="#checkmark-bold"/></svg>
						                    <span class="ProgressBar-stepLabel">Driver Complete Trip</span>
						                </li>
						                <li class="ProgressBar-step">
						                    <svg class="ProgressBar-icon"><use xlink:href="#checkmark-bold"/></svg>
						                    <span class="ProgressBar-stepLabel">Final Approval by Requestor or Admin</span>
						                </li>
					                </ol>
					                <!-- <svg xmlns="http://www.w3.org/2000/svg">
					                  <symbol id="checkmark-bold" viewBox="0 0 24 24">
					                    <path d="M20.285 2l-11.285 11.567-5.286-5.011-3.714 3.716 9 8.728 15-15.285z"/>
					                  </symbol>
					                </svg> -->
					            </div>
			            	</div>
			            	<br><br>

			            	<table id="acceptedtable" class="acceptedtable" cellspacing="0" width="100%" padding="30px" style="font-size: 13px;">
			            		<thead>
                        <tr class="search">
                        @foreach($accepted as $key=>$value)
                          @if ($key==0)
                            <?php $i = 0; ?>
                              @foreach($value as $field=>$a)
                                @if ($i==0)
                                  <th align='center'><input type='hidden' class='search_init' name='{{$i}}' placemark='{{$a}}'></th>
                                @else
                                  <th align='center'><input type='text' class='search_init' name='{{$i}}'  placemark='{{$a}}'></th>
                                @endif
                                <?php $i ++; ?>
                                @endforeach
                              <!-- <th align='center'><input type='hidden' class='search_init' name='{{$i}}'  placemark='{{$a}}'></th> -->
                            @endif
                          @endforeach
                        </tr>
			            			<tr>
			            				<td>No</td>
			            				<td>DO No</td>
                          <td>Vehicle Number</td>
                          <td>Driver</td>
                          <td>Delivery Date</td>
                          <td>Delivery Time</td>
                          <td>Pick Up Date</td>
                          <td>Pick Up Time</td>
                          <td>Location</td>
                          <td>State</td>
                          <td>Purpose</td>
                          <td>Request By</td>
                          <td>Activity</td>
                          <td>Trip Type</td>
                          <td>Action</td>
                          <td></td>
                          <td></td>
                        </tr>
	                    </thead>
	                    <tbody>
	                    	<?php $i=1; $acceptedData=array(); ?>
		                    	@foreach($accepted as $accepted)
			                    	<?php $acceptedData[]=$accepted?>
			                    	<tr id="row_{{$accepted->Id}}">
          										<td>{{$i}}</td>
          										<td><b>{{$accepted->DO_No}}</b></td>
          										<td>{{$accepted->Vehicle_No}}</td>
          										<td>{{$accepted->Name == null ? "-":$accepted->Name}}</td>
          										<td>{{$accepted->delivery_date}}</td>
          										<td>{{$accepted->delivery_time}}</td>
                              <td>{{$accepted->pickup_date}}</td>
          										<td>{{$accepted->pick_up_time}}</td>
          										<td><a onclick="myfunction({{$accepted->Latitude}},{{$accepted->Longitude}})">{{$accepted->Location_Name}}</a></td>
                              <td>{{$accepted->State}}</td>
          										<td>{{$accepted->Purpose}}</td>
          										<td>{{$accepted->requestorname}}</td>
          										<td>{{$accepted->delivery_status_details}}</td>
                              <td>{{$accepted->trip}}</td>
          										<td>
          											<a href="{{url('/deliverydetails')}}/{{$accepted->Id}}" class="btn btn-default btn-xs" title="VIEW" style="width:unset" target="_blank"><i class="fa fa-eye"></i></a>
                                @if($me->Delete_Delivery)
                                <button class="deleteprop btn btn-default btn-xs" title="DELETE" style="width:unset" id="{{$accepted->Id}}"><i class="fa fa-trash"></i></button>
                                @endif
          										</td>
                              <td>{{$accepted->Longitude}}</td>
                              <td>{{$accepted->Latitude}}</td>
		                        </tr>
		                        <?php $i+=1;?>
	                        @endforeach
	                    </tbody>
		                </table>
			            </div>
			            <!-- .Accepted Delivery -->

			            <!-- Recall Delivery -->
			            <div class="tab-pane" id="recalleddelivery">
			            	<table id="recalledtable" class="recalledtable" cellspacing="0" width="100%" padding="30px" style="font-size: 13px;">
                      <thead>
                        <tr class="search">
                        @foreach($recalled as $key=>$value)
                          @if ($key==0)
                            <?php $i = 0; ?>
                              @foreach($value as $field=>$a)
                                @if ($i==0)
                                  <th align='center'><input type='hidden' class='search_init' name='{{$i}}' placemark='{{$a}}'></th>
                                @else
                                  <th align='center'><input type='text' class='search_init' name='{{$i}}'  placemark='{{$a}}'></th>
                                @endif
                                <?php $i ++; ?>
                                @endforeach
                              <!-- <th align='center'><input type='hidden' class='search_init' name='{{$i}}'  placemark='{{$a}}'></th> -->
                            @endif
                          @endforeach
                        </tr>
			            			<tr>
			            				<td>No</td>
			            				<td>DO No</td>
                          <td>Vehicle Number</td>
                          <td>Driver</td>
                          <td>Delivery Date</td>
                          <td>Delivery Time</td>
                          <td>Pick Up Date</td>
                          <td>Pick Up Time</td>
                          <td>Location</td>
                          <td>Purpose</td>
                          <td>Recalled On</td>
                          <td>Request By</td>
                          <td>Activity</td>
                          <td>Action</td>
                          <td></td>
                        </tr>
	                    </thead>
	                    <tbody>
	                    	<?php $i=1;?>
		                    	@foreach($recalled as $recalled)
			                    	<tr id="row_{{$recalled->Id}}">
          										<td>{{$i}}</td>
          										<td><b>{{$recalled->DO_No}}</b></td>
          										<td>{{$recalled->Vehicle_No}}</td>
          										<td>{{$recalled->Name == null ? "-":$recalled->Name}}</td>
          										<td>{{$recalled->delivery_date}}</td>
          										<td>{{$recalled->delivery_time}}</td>
                              <td>{{$recalled->pickup_date}}</td>
                              <td>{{$recalled->pick_up_time}}</td>
          										<td>
          											<a onclick="myfunction({{$recalled->Latitude}},{{$recalled->Longitude}})">{{$recalled->Location_Name}}</a>
          										</td>
          										<td>{{$recalled->Purpose}}</td>
          										<td>{{$recalled->created_at}}</td>
          										<td>{{$recalled->requestorname}}</td>
          										<td>{{$recalled->delivery_status_details}}</td>
          										<td>
          											<a href="{{url('/deliverydetails')}}/{{$recalled->Id}}" class="btn btn-default btn-xs" title="VIEW" style="width:unset" target="_blank"><i class="fa fa-eye"></i></a>
          										</td>
                              <td></td>
          									</tr>
        									<?php $i+=1;?>
        								@endforeach
		                  </tbody>
		                </table>
			            </div>
			            <!-- .Recall Delivery -->

                  <!-- Trarnsfer Delivery -->
                  <div class="tab-pane" id="transferdelivery">
                    <table id="transfertable" class="transfertable" cellspacing="0" width="100%" padding="30px" style="font-size: 13px;">
                      <thead>
                        <tr class="search">
                        @foreach($release as $key=>$value)
                          @if ($key==0)
                            <?php $i = 0; ?>
                              @foreach($value as $field=>$a)
                                @if ($i==0)
                                  <th align='center'><input type='hidden' class='search_init' name='{{$i}}' placemark='{{$a}}'></th>
                                @else
                                  <th align='center'><input type='text' class='search_init' name='{{$i}}'  placemark='{{$a}}'></th>
                                @endif
                                <?php $i ++; ?>
                                @endforeach
                              <!-- <th align='center'><input type='hidden' class='search_init' name='{{$i}}'  placemark='{{$a}}'></th> -->
                            @endif
                          @endforeach
                        </tr>
                        <tr>
                          <td>No</td>
                          <td>DO No</td>
                          <td>Vehicle Number</td>
                          <td>Driver</td>
                          <td>Requested By</td>
                          <td>Delivery Date</td>
                          <td>Delivery Time</td>
                          <td>Location</td>
                          <td>Purpose</td>
                          <td>Project</td>
                          <td>Transferred On</td>
                          <td>Reason</td>
                          <td>Action</td>
                          <td></td>
                          <td></td>
                        </tr>
                      </thead>
                      <tbody>
                        <?php $i=1;?>
                          @foreach($release as $release)
                            <tr id="row_{{$release->Id}}">
                              <td>{{$i}}</td>
                              <td><b>{{$release->DO_No}}</b></td>
                              <td>{{$release->Vehicle_No}}</td>
                              <td>{{$release->driver}}</td>
                              <td>{{$release->requestor == null ? "-":$release->requestor}}</td>
                              <td>{{$release->delivery_date}}</td>
                              <td>{{$release->delivery_time}}</td>
                              <td>
                                <a onclick="myfunction({{$release->Latitude}},{{$release->Longitude}})">{{$release->Location_Name}}</a>
                              </td>
                              <td>{{$release->Option}}</td>
                              <td>{{$release->Project_Name}}</td>
                              <td>{{$release->created_at}}</td>
                              <td>{{$release->remarks}}</td>
                              <td>
                                <a href="{{url('/deliverydetails')}}/{{$release->Id}}" class="btn btn-default btn-xs" title="VIEW" style="width:unset" target="_blank"><i class="fa fa-eye"></i></a>
                              </td>
                              <td>{{$release->Longitude}}</td>
                              <td>{{$release->Latitude}}</td>
                            </tr>
                          <?php $i+=1;?>
                        @endforeach
                      </tbody>
                    </table>
                  </div>
                  <!-- .Transfer Delivery -->

			            <!-- Complete Delivery -->
			            <div class="tab-pane" id="completeddelivery">
			            	<table id="completedtable" class="completedtable" cellspacing="0" width="100%" padding="30px" style="font-size: 13px;">
			            		<thead>
                        <tr class="search">
                        @foreach($completed as $key=>$value)
                          @if ($key==0)
                            <?php $i = 0; ?>
                              @foreach($value as $field=>$a)
                                @if ($i==0)
                                  <th align='center'><input type='hidden' class='search_init' name='{{$i}}' placemark='{{$a}}'></th>
                                @else
                                  <th align='center'><input type='text' class='search_init' name='{{$i}}'  placemark='{{$a}}'></th>
                                @endif
                                <?php $i ++; ?>
                                @endforeach
                              <!-- <th align='center'><input type='hidden' class='search_init' name='{{$i}}'  placemark='{{$a}}'></th> -->
                            @endif
                          @endforeach
                        </tr>
			            			<tr>
			            				<td>No</td>
			            				<td>DO No</td>
                          <td>Vehicle Number</td>
                          <td>Driver</td>
                          <td>Delivery Date</td>
                          <td>Driver Completion Date</td>
                          <td>Delivery Time</td>
                          <td>Pick Up Date</td>
                          <td>Pick Up Time</td>
                          <td>Location</td>
                          <td>State</td>
                          <td>Purpose</td>
                          <td>Action Taken On</td>
                          <td>Request By</td>
                          <td>Activity</td>
                          <td>Trip Type</td>
                          <td>Action</td>
                          <td></td>
                        </tr>
	                    </thead>
	                    <tbody>
	                    	<?php $i=1;?>
		                    	@foreach($completed as $completed)
			                    	<tr id="row_{{$completed->Id}}">
          										<td>{{$i}}</td>
          										<td><b>{{$completed->DO_No}} {{$completed->delivery_status_details == "-"? "(Special Delivery)":""}}</b></td>
          										<td>{{$completed->Vehicle_No}}</td>
          										<td>{{$completed->Name == null ? "-":$completed->Name}}</td>
          										<td>{{$completed->delivery_date}}</td>
                              <td>{{$completed->driverdate}}</td>
          										<td>{{$completed->delivery_time}}</td>
                              <td>{{$completed->pickup_date}}</td>
          										<td>{{$completed->pick_up_time}}</td>
          										<td>
          											<a onclick="myfunction({{$completed->Latitude}},{{$completed->Longitude}})">{{$completed->Location_Name}}</a>
          										</td>
                              <td>{{$completed->State}}</td>
          										<td>{{$completed->Purpose}}</td>
          										<td>{{$completed->created_at}}</td>
          										<td>{{$completed->requestorname}}</td>
          										<td>{{$completed->delivery_status_details}}</td>
                              <td>{{$completed->trip}}</td>
          										<td>
          											<a href="{{url('/deliverydetails')}}/{{$completed->Id}}" class="btn btn-default btn-xs" title="VIEW" style="width:unset" target="_blank"><i class="fa fa-eye"></i></a>
                                @if($me->Delete_Delivery)
                                <button class="deleteprop btn btn-default btn-xs" title="DELETE" style="width:unset" id="{{$completed->Id}}"><i class="fa fa-trash"></i></button>
                                @endif
          										</td>
                              <td>{{$completed->Longitude}}</td>
          									</tr>
        									<?php $i+=1;?>
      									@endforeach
	                    </tbody>
		                </table>
			            </div>
      						<!-- .Complete Delivery -->

                   <!-- Incomplete Delivery -->
                  <div class="tab-pane" id="incompleteddelivery">
                    <table id="incompletedtable" class="incompletedtable" cellspacing="0" width="100%" padding="30px" style="font-size: 13px;">
                      <thead>
                        <tr class="search">
                        @foreach($incomplete as $key=>$value)
                          @if ($key==0)
                            <?php $i = 0; ?>
                              @foreach($value as $field=>$a)
                                @if ($i==0)
                                  <th align='center'><input type='hidden' class='search_init' name='{{$i}}' placemark='{{$a}}'></th>
                                @else
                                  <th align='center'><input type='text' class='search_init' name='{{$i}}'  placemark='{{$a}}'></th>
                                @endif
                                <?php $i ++; ?>
                                @endforeach
                              <!-- <th align='center'><input type='hidden' class='search_init' name='{{$i}}'  placemark='{{$a}}'></th> -->
                            @endif
                          @endforeach
                        </tr>
                        <tr>
                          <td>No</td>
                          <td>DO No</td>
                          <td>Vehicle Number</td>
                          <td>Driver</td>
                          <td>Delivery Date</td>
                          <td>Delivery Time</td>
                          <td>Pick Up Date</td>
                          <td>Pick Up Time</td>
                          <td>Reason</td>
                          <td>Location</td>
                          <td>Purpose</td>
                          <td>Complete On</td>
                          <td>Request By</td>
                          <td>Activity</td>
                          <td>Action</td>
                          <td></td>
                        </tr>
                      </thead>
                      <tbody>
                        <?php $i=1;?>
                          @foreach($incomplete as $incomplete)
                            <tr id="row_{{$incomplete->Id}}">
                              <td>{{$i}}</td>
                              <td><b>{{$incomplete->DO_No}}</b></td>
                              <td>{{$incomplete->Vehicle_No}}</td>
                              <td>{{$incomplete->Name == null ? "-":$incomplete->Name}}</td>
                              <td>{{$incomplete->delivery_date}}</td>
                              <td>{{$incomplete->delivery_time}}</td>
                              <td>{{$incomplete->pickup_date}}</td>
                              <td>{{$incomplete->pick_up_time}}</td>
                              <td>{{$incomplete->remarks}}</td>
                              <td>
                                <a onclick="myfunction({{$incomplete->Latitude}},{{$incomplete->Longitude}})">{{$incomplete->Location_Name}}</a>
                              </td>
                              <td>{{$incomplete->Purpose}}</td>
                              <td>{{$incomplete->created_at}}</td>
                              <td>{{$incomplete->requestorname}}</td>
                              <td>{{$incomplete->delivery_status_details}}</td>
                              <td>
                                <a href="{{url('/deliverydetails')}}/{{$incomplete->Id}}" class="btn btn-default btn-xs" title="VIEW" style="width:unset" target="_blank"><i class="fa fa-eye"></i></a>
                              </td>
                              <td>{{$incomplete->Longitude}}</td>
                            </tr>
                          <?php $i+=1;?>
                        @endforeach
                      </tbody>
                    </table>
                  </div>
                  <!-- .Incomplete Delivery -->

                  <!-- Rejected -->
      						<div class="tab-pane" id="rejecteddelivery">
      							<table id="rejectedtable" class="rejectedtable" cellspacing="0" width="100%" padding="30px" style="font-size: 13px;">
      								<thead>
                        <tr class="search">
                        @foreach($rejected as $key=>$value)
                          @if ($key==0)
                            <?php $i = 0; ?>
                              @foreach($value as $field=>$a)
                                @if ($i==0)
                                  <th align='center'><input type='hidden' class='search_init' name='{{$i}}' placemark='{{$a}}'></th>
                                @else
                                  <th align='center'><input type='text' class='search_init' name='{{$i}}'  placemark='{{$a}}'></th>
                                @endif
                                <?php $i ++; ?>
                                @endforeach
                              <!-- <th align='center'><input type='hidden' class='search_init' name='{{$i}}'  placemark='{{$a}}'></th> -->
                            @endif
                          @endforeach
                        </tr>
      									<tr>
      										<td>No</td>
      										<td>DO No</td>
      										<td>Vehicle Number</td>
      										<td>Delivery Date</td>
      										<td>Delivery Time</td>
                          <td>Pick Up Date</td>
                          <td>Pick Up Time</td>
      										<td>Location</td>
      										<td>Purpose</td>
      										<td>Remarks</td>
      										<td>Request By</td>
      										<td>Activity</td>
      										<td>Action</td>
                          <td></td>
                          <td></td>
      									</tr>
      								</thead>
      								<tbody>
      									<?php $i=1;?>
      									@foreach($rejected as $r)
      									<tr>
      										<td>{{$i}}</td>
      										<td><b>{{$r->DO_No}}</b></td>
      										<td>{{$r->Vehicle_No}}</td>
      										<td>{{$r->delivery_date}}</td>
      										<td>{{$r->delivery_time}}</td>
                          <td>{{$r->pickup_date}}</td>
                          <td>{{$r->pick_up_time}}</td>
      										<td>
      											<a onclick="myfunction({{$r->Latitude}},{{$r->Longitude}})">{{$r->Location_Name}}</a>
      										</td>
      										<td>{{$r->Purpose}}</td>
      										<td>{{$r->remarks}}</td>
      										<td>{{$r->requestorname}}</td>
      										<td>{{$r->delivery_status_details}}</td>
      										<td>
      											<a href="{{url('/deliverydetails')}}/{{$r->Id}}" class="btn btn-default btn-xs" title="VIEW" style="width:unset" target="_blank"><i class="fa fa-eye"></i></a>
      										</td>
                          <td></td>
                          <td></td>
      									</tr>
      									<?php $i++;?>
      									@endforeach
      								</tbody>
      							</table>
      						</div>
						<!--Rejected Delivery -->

						<!-- Insufficient Delivery -->
			     <div class="tab-pane" id="insufficientdelivery">
							<table id="insufficienttable" class="insufficienttable" cellspacing="0" width="100%" padding="30px" style="font-size: 13px;">
								<thead>
                  <tr class="search">
                  @foreach($insufficient as $key=>$value)
                    @if ($key==0)
                      <?php $i = 0; ?>
                        @foreach($value as $field=>$a)
                          @if ($i==0)
                            <th align='center'><input type='hidden' class='search_init' name='{{$i}}' placemark='{{$a}}'></th>
                          @else
                            <th align='center'><input type='text' class='search_init' name='{{$i}}'  placemark='{{$a}}'></th>
                          @endif
                          <?php $i ++; ?>
                          @endforeach
                        <!-- <th align='center'><input type='hidden' class='search_init' name='{{$i}}'  placemark='{{$a}}'></th> -->
                      @endif
                    @endforeach
                  </tr>
									<tr>
										<td>No</td>
										<td>DO No</td>
										<td>Vehicle Number</td>
										<td>Driver</td>
										<td>Delivery Date</td>
										<td>Delivery Time</td>
                    <td>Pick Up Date</td>
                    <td>Pick Up Time</td>
										<td>Location</td>
										<td>Purpose</td>
										<td>Request By</td>
										<td>Activity</td>
										<td>Action</td>
                    <td></td>
                    <td></td>
									</tr>
								</thead>
								<tbody>
									<?php $i=1;		?>
									@foreach($insufficient as $insufficient)
									<tr id="row_{{$insufficient->Id}}">
										<td>{{$i}}</td>
										<td><b>{{$insufficient->DO_No}}</b></td>
										<td>{{$insufficient->Vehicle_No}}</td>
										<td>{{$insufficient->Name == null ? "-":$insufficient->Name}}</td>
										<td>{{$insufficient->delivery_date}}</td>
                    <td>{{$insufficient->delivery_time}}</td>
                    <td>{{$insufficient->pickup_date}}</td>
                    <td>{{$insufficient->pick_up_time}}</td>
										<td>
											<a onclick="myfunction({{$insufficient->Latitude}},{{$insufficient->Longitude}})">{{$insufficient->Location_Name}}</a>
										</td>
										<td>{{$insufficient->Purpose}}</td>
										<td>{{$insufficient->requestorname}}</td>
										<td>{{$insufficient->delivery_status_details}}</td>
										<td>
											<a href="{{url('/deliverydetails')}}/{{$insufficient->Id}}" class="btn btn-default btn-xs" title="VIEW" style="width:unset" target="_blank"><i class="fa fa-eye"></i></a>
										</td>
                    <td></td>
                    <td></td>
									</tr>
									<?php $i+=1;?>
									@endforeach
								</tbody>
							</table>
						</div>
						<!-- Insufficient Delivery -->

            <!-- Cancel Delivery -->
            <div class="tab-pane" id="canceldelivery">
              <table id="canceltable" class="canceltable" cellspacing="0" width="100%" padding="30px" style="font-size: 13px;">
                <thead>
                  <tr class="search">
                  @foreach($cancels as $key=>$value)
                    @if ($key==0)
                      <?php $i = 0; ?>
                        @foreach($value as $field=>$a)
                          @if ($i==0)
                            <th align='center'><input type='hidden' class='search_init' name='{{$i}}' placemark='{{$a}}'></th>
                          @else
                            <th align='center'><input type='text' class='search_init' name='{{$i}}'  placemark='{{$a}}'></th>
                          @endif
                          <?php $i ++; ?>
                          @endforeach
                        <!-- <th align='center'><input type='hidden' class='search_init' name='{{$i}}'  placemark='{{$a}}'></th> -->
                      @endif
                    @endforeach
                  </tr>
                  <tr>
                    <td>No</td>
                    <td>Id</td>
                    <td>DO No</td>
                    <td>Vehicle Number</td>
                    <td>Driver</td>
                    <td>Delivery Date</td>
                    <td>Delivery Time</td>
                    <td>Location</td>
                    <td>Purpose</td>
                    <td>Request By</td>
                    <td>Cancellation Date</td>
                    <td>Cancellation Reason</td>
                    <td>Status</td>
                    <td>Action</td>
                  </tr>
                </thead>
                <tbody>
                  <?php $i=1;   ?>
                  @foreach($cancels as $c)
                  <tr id="row_{{$c->Id}}">
                    <td>{{$i}}</td>
                    <td>{{$c->Id}}</td>
                    <td><b>{{$c->DO_No}}</b></td>
                    <td>{{$c->Vehicle_No}}</td>
                    <td>{{$c->Name == null ? "-":$c->Name}}</td>
                    <td>{{$c->delivery_date}}</td>
                    <td>{{$c->delivery_time}}</td>
                    <td>{{$c->Location_Name}}</td>
                    <td>{{$c->Purpose}}</td>
                    <td>{{$c->requestorname}}</td>
                    <td>{{$c->created_at}}</td>
                    <td>{{$c->remarks}}</td>
                    <td>
                      @if($c->approve == 1)
                      <span style="color:green">Approved</span>
                      @else
                      <span style="color:red">Pending</span>
                      @endif
                    </td>
                    <td>
                      @if($c->approve == 0)
                      <a onclick="OpenApproveDialog({{$c->Id}})" class="btn btn-info btn-sm"">Approve</a>
                      @else
                      <a href="{{url('/deliverydetails')}}/{{$c->Id}}" class="btn btn-default btn-xs" title="VIEW" style="width:unset" target="_blank"><i class="fa fa-eye"></i></a>
                      @endif
                    </td>
                  </tr>
                  <?php $i+=1;?>
                  @endforeach
                </tbody>
              </table>
            </div>
            <!-- Cancel Delivery -->
	        </div>
		    </div>
			</div>
		</div>

		<div class="modal fade" id="mapModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
			<div class="modal-dialog" role="document">
				<div class="modal-content">
					<div class="modal-header">
						<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
						<h4 class="modal-title" id="myModalLabel">Map</h4>
					</div>
					<div id="map"></div>
					<div class="modal-footer" id="addBtn">
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
	</section>

</div>

<footer class="main-footer">
	<div class="pull-right hidden-xs">
		<b>Version</b> 2.0.1
	</div>
	<strong>Copyright &copy; 2014-2016 <a href="http://www.softoya.com">TrackerOnTheGo</a>.</strong> All rights reserved.
</footer>



<script type="text/javascript">

	$(document).ready(function () {
        $('a[data-toggle="tab"]').on('shown.bs.tab', function (e) {
            $('#calendar').fullCalendar('render');
        });

    });

   function refresh()
    {

      var d=$('#range').val();
      var arr = d.split(" - ");
       window.location.href ="{{ url("/deliveryapproval") }}/"+arr[0]+"/"+arr[1];
    }

 $(function () {

      $( document ).ready(function() {
      //grabs the hash tag from the url
      var hash = window.location.hash;
      //checks whether or not the hash tag is set
      if (hash != "") {
        //removes all active classes from tabs
        $('#tabs li').each(function() {
          $(this).removeClass('active');
        });
        //this will add the active class on the hashtagged value
        var link = "";
        $('#tabs li').each(function() {
          link = $(this).find('a').attr('href');
          if (link == hash) {
            $(this).addClass('active');
          }
        });
      }
    });

     $('#range').daterangepicker({locale: {
        format: 'DD-MMM-YYYY'
      },startDate: '{{$start}}',
      endDate: '{{$end}}'});

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
				@foreach($processingData as $proc)
				{
					title: "{{ $proc->DO_No }} - {{ $proc->Vehicle_No }}",
					// title: parseHtmlEntities("{{$proc->DO_No}} - {{$proc->Name}}"),
					start:new Date("{{date(DATE_ISO8601, strtotime($proc->delivery_date))}}"),
					// end:new Date("{{date(DATE_ISO8601, strtotime($proc->delivery_date))}}"),
					description: "<p><b>Requestor</b>: {{$proc->requestorname}}</p><p><b>Lorry</b>: {{$proc->Vehicle_No}}</p><p><b>Driver</b>: {{$proc->Name}}</p><p><b>Delivery Date</b>: {{$proc->delivery_date}}</p><p><b>Delivery Time</b>: {{$proc->delivery_time}}</p><p><b>Status</b>: {{$proc->delivery_status_details}}</p><p><b>Location</b>: {{$proc->Location_Name}}</p><p><b>Purpose</b>: {{$proc->Purpose}}</p><p><b>Application Date</b>: {{$proc->created_at}}</p>",
					allday:true,
					backgroundColor: "#dd4b39", //red
					borderColor: "#dd4b39"
				},
				@endforeach
				@foreach($acceptedData as $accep)
				{
					// title: parseHtmlEntities("{{$accep->DO_No}} - {{$accep->Name}}"),
					title: "{{ $accep->requestorname }} - {{ $accep->Vehicle_No }}",
					start:new Date("{{date(DATE_ISO8601, strtotime($accep->delivery_date))}}"),
					// end:new Date("{{date(DATE_ISO8601, strtotime($accep->delivery_date))}}"),
					description: "<p><b>Requestor</b>: {{$accep->requestorname}}</p><p><b>Lorry</b>: {{$accep->Vehicle_No}}</p><p><b>Driver</b>: {{$accep->Name}}</p><p><b>Delivery Date</b>: {{$accep->delivery_date}}</p><p><b>Delivery Time</b>: {{$accep->delivery_time}}</p><p><b>Status</b>: {{$accep->delivery_status_details}}</p><p><b>Location</b>: {{$accep->Location_Name}}</p><p><b>Purpose</b>: {{$accep->Purpose}}</p><p><b>Application Date</b>: {{$accep->created_at}}</p>",
					allday:true,
					backgroundColor: "#00a65a", //green
					borderColor: "#00a65a" //green
				},
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

      // eventClick: function(event) {
      //   if (event.url) {
      //       window.open(event.url);
      //       return false;
      //   }
  //   // },
  //     editable: false,
		// 	displayEventTime:false,
  //     droppable: false, // this allows things to be dropped onto the calendar !!!
  //   });
  // });
  editable: false,
      droppable: false, // this allows things to be dropped onto the calendar !!!
    });
  });

	// function parseHtmlEntities(str) {
 //    return str.replace(/&#([0-9]{1,3});/gi, function(match, numStr) {
 //        var num = parseInt(numStr, 10);
 //        return String.fromCharCode(num);
 //    });
	// }
  function OpenApproveDialog(id)
  {
    var hiddeninput='<input type="hidden" class="form-control" id="approveid" name="approveid" value="'+id+'">';
     $( "#approvecancel" ).html(hiddeninput);
     $('#Cancel').modal('show');
  }

$(document).ready(function() {
    $(document).on('click', '.deleteprop', function(e) {
      var id = this.id;
      var hiddeninput='<input type="hidden" class="form-control" id="deleteid" name="deleteid" value="'+id+'">';
     $( "#deleteconfirm" ).html(hiddeninput);
     $('#deleteModal').modal('show');
  });
  });

$(document).ready(function() {
    $(document).on('click', '#btn-delete', function(e) {
      var id = $('#deleteid').val();
      $.ajaxSetup({
          headers: { 'X-CSRF-Token' : $('meta[name=_token]').attr('content') }
        });
      $.ajax({
            url: "{{ url('deliveryapproval/delete') }}",
            cache: false,
            data: {Id: id},
            type: 'POST',
            success: function (response) {
              if(response==1)
              {
               alert('Delivery Deleted');
               $('#deleteModal').modal('hide');
               window.location.reload();
              }
              else
              {
                alert('Failed to Delete');
              }
            }
          });
  });
});

function approve(){
   $.ajaxSetup({
          headers: { 'X-CSRF-Token' : $('meta[name=_token]').attr('content') }
        });
   $("#ajaxloader5").show();
   id=$('[name="approveid"]').val();
        $.ajax({
            url: "{{ url('deliveryapproval/approvecancel') }}", // point to server-side PHP script
            cache: false,
            data: {Id: id},
            type: 'POST',
            success: function (response) {
               // canceltable.api.ajax.reload();
               $("#ajaxloader5").hide();
               alert('Successfully Approve Cancellation Request');
               $('#Cancel').modal('hide');
               window.location.reload();
            },
            error: function (response) {
              alert('Failed to Approve Cancellation Request');
            }
        });
}
</script>

@endsection
