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
         var aptable;

         $(document).ready(function() {

           editor = new $.fn.dataTable.Editor( {
                   ajax: {
                      "url": "{{ asset('/Include/apfinanceupdate.php') }}"
                    },
                   table: "#ap",
                   idSrc: "materialpo.Id",
                   formOptions: {
                        bubble: {
                            submit: 'allIfChanged'
                        }
                    },
                   fields: [
                     {
                                label: "DocNo:",
                                name: "materialpo.PO_No",
                                type:"readonly"
                    },
                    {
                               label: "DocDate:",
                               name: "materialpo.created_at",
                               type:"readonly"
                    },
                    {
                               label: "CreditorCode:",
                               name: "vendor.CreditorCode",
                               type:"readonly"
                    },{
                               label: "DeptNo:",
                               name: "company.Company_Name",
                               type:"readonly"
                    },{
                                label: "SupplierInvoiceNo:",
                                name: "materialpo.SupplierInvoiceNo"
                    }

                   ]
           } );

               aptable = $('#ap').dataTable( {

                  columnDefs: [{ "visible": false, "targets": [1] },{"className": "dt-center", "targets": "_all"}],
                  responsive: false,
                  sScrollX: "100%",
                  language : {
                     sLoadingRecords : 'Loading data...',
                     processing: 'Loading data...'
                   },
                  bAutoWidth: true,
                  rowId: 'materialpo.Id',
                  sScrollY: "100%",
                  dom: "Bfrtip",
                  bScrollCollapse: true,

                   columns: [
                     {data: null, "render":"", title:"No"},
                     { data: "materialpo.Id",title:"Id"},
                     { data: "materialpo.PO_No",title:"DocNo"},
               			{ data: "materialpo.created_at",title:"DocDate"},
               			{ data: "vendor.CreditorCode",title:"CreditorCode"},
               			{ data: "materialpo.SupplierInvoiceNo",title:"SupplierInvoiceNo"},
               			{ data: "company.Company_Name",title:"DeptNo"},
                    { data: "users.Name",title:"Created_By"},
                   ],
                   autoFill: {
                      editor:  editor
                  },
                   select: {
                           style:    'os',
                           selector: 'tr'
                   },
                   buttons: [
                           { extend: "edit",  editor: editor },
                           {
                                   extend: 'collection',
                                   text: 'Export',
                                   buttons: [
                                           'excel',
                                           'csv'
                                   ]
                           }
                   ],

               });

               aptable.api().on( 'order.dt search.dt', function () {
                   aptable.api().column(0, {search:'applied', order:'applied'}).nodes().each( function (cell, i) {
                       cell.innerHTML = i+1;
                   } );
               } ).draw();

               $(".ap thead input").keyup ( function () {

                       /* Filter on the column (the index) of this element */
                       if ($('#ap').length > 0)
                       {

                           var colnum=document.getElementById('ap').rows[0].cells.length;

                           if (this.value=="[empty]")
                           {

                              aptable.fnFilter( '^$', this.name,true,false );
                           }
                           else if (this.value=="[nonempty]")
                           {

                              aptable.fnFilter( '^(?=\\s*\\S).*$', this.name,true,false );
                           }
                           else if (this.value.startsWith("!")==true && this.value.length>1)
                           {

                              aptable.fnFilter( '^(?'+ this.value +').*', this.name,true,false );
                           }
                           else if (this.value.startsWith("!")==false)
                           {

                               aptable.fnFilter( this.value, this.name,true,false );
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
      AP Finance Update
      <small>Costing</small>
      </h1>

      <ol class="breadcrumb">
      <li><a href="{{ url('/home') }}"><i class="fa fa-dashboard"></i> Home</a></li>
      <li><a href="#">Costing</a></li>
      <li class="active">AP Finance Update</li>
      </ol>
    </section>

    <section class="content">

      <div class="row">
        <div class="col-md-4">
         <div class="input-group">
           <div class="input-group-addon">
             <i class="fa fa-clock-o"></i>
           </div>
           <input type="text" class="form-control" id="range" name="range">

         </div>
       </div>

       <div class="col-md-4">
         <div class="form-group">
          <select class="form-control select2" id="Company" name="Company" >
            <option value="">All</option>
            @foreach($companies as $com)
               <option <?php if($company == $com->Option) echo ' selected="selected" '; ?>>{{$com->Option}}</option>
            @endforeach
          </select>
        </div>
      </div>

       <div class="col-md-2">
           <div class="input-group">
             <button type="button" class="btn btn-success btn" data-toggle="modal" onclick="refresh();">Refresh</button>
           </div>
       </div>
      </div>

      <div class="row">

        <div class="col-md-12">
          <!-- <div class="box">
            <div class="box-body"> -->

            <br>
                  <table id="ap" class="ap" cellspacing="0" width="100%" padding="30px" style="font-size: 13px;">
                   <thead>
                                <tr class="search">
                                @foreach($record as $key=>$value)

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
                                        <th align='center'><input type='text' class='search_init' name='{{$i}}'  placemark='{{$a}}'></th>
                                    @endif
                                @endforeach
                                </tr>
                                <tr>
                                    @foreach($record as $key=>$value)

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
                                @foreach($record as $delivery)

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

<script type="text/javascript">

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

      company=$('[name="Company"]').val();

      if(company)
      {
        window.location.href ="{{ url("/apfinanceupdate") }}/"+arr[0]+"/"+arr[1]+"/"+company;
      }
      else {
        window.location.href ="{{ url("/apfinanceupdate") }}/"+arr[0]+"/"+arr[1];
      }

    }

</script>

@endsection
