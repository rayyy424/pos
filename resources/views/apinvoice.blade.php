
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

      <script type="text/javascript" language="javascript" src="{{ asset('/plugin/js/dataTables.editor.min.js') }}"></script>
      {{-- chart js --}}
      <script type="text/javascript" language="javascript" src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.4.0/Chart.bundle.min.js"></script>

      <script type="text/javascript" language="javascript" class="init">

      var summary;
      var charges;

      $(document).ready(function() {

          summary = $('#summary').dataTable( {

                  columnDefs: [{ "width": "35px", "targets": "_all" },{ "visible": false, "targets": [] },{"className": "dt-left", "targets": [1]},{"className": "dt-center", "targets": "_all"}],
                  responsive: false,
                  colReorder: false,
                  dom: "Bfrtip",
                  sScrollX: "100%",
                  // bAutoWidth: true,
                  sScrollY: "100%",
                  scrollCollapse: true,
                  columns: [
                    {  data: null, "render":"", title:"No"},
                    { data: "DocNo",title:"DocNo"},
              			{ data: "DocDate",title:"DocDate"},
              			{ data: "CreditorCode",title:"CreditorCode"},
              			{ data: "SupplierInvoiceNo",title:"SupplierInvoiceNo"},
              			{ data: "JournalType",title:"JournalType"},
              			{ data: "DisplayTerm",title:"DisplayTerm"},
              			{ data: "PurchaseAgent",title:"PurchaseAgent"},
              			{ data: "Description",title:"Description"},
              			{ data: "CurrencyRate",title:"CurrencyRate"},
              			{ data: "RefNo2",title:"RefNo2"},
              			{ data: "Note",title:"Note"},
              			{ data: "AccNo",title:"AccNo"},
              			{ data: "ToAccountRate",title:"ToAccountRate"},
              			{ data: "DetailDescription",title:"DetailDescription"},
              			{ data: "ProjNo",title:"ProjNo"},
              			{ data: "DeptNo",title:"DeptNo"},
              			{ data: "TaxType",title:"TaxType"},
              			{ data: "TaxableAmt",title:"TaxableAmt"},
              			{ data: "TaxAdjustment",title:"TaxAdjustment"},
              			{ data: "Amount",title:"Amount"}

                  ],
                 buttons: [
                         {
                                 extend: 'collection',
                                 text: 'Export',
                                 buttons: [
                                         'csv'
                                 ]
                         }
                 ],

            });

            summary.api().on( 'order.dt search.dt', function () {
              summary.api().column(0, {search:'applied', order:'applied'}).nodes().each( function (cell, i) {
                  cell.innerHTML = i+1;
              } );
            } ).draw();

            $('#summary').on( 'search.dt', function () {

              var rows=summary.api().rows( { search: 'applied' } ).data().toArray();
              var totalamount=0;
              var site=[];

              for (var i = 0; i < rows.length; i++) {

                  totalamount=totalamount+parseFloat(rows[i][12]);

                  if(site.indexOf(rows[i][5])==-1)
                  {
                    site.push(rows[i][5]);
                  }
              }

              $("#total").html("<br>RM " + parseFloat(totalamount.toFixed(2)).toLocaleString("en"));
              $("#sitecount").html("<br>" + site.length);


            } );

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
                    }

            } );

            charges = $('#charges').dataTable( {

            columnDefs: [{ "width": "35px", "targets": "_all" },{ "visible": false, "targets": [] },{"className": "dt-left", "targets": [1]},{"className": "dt-center", "targets": "_all"}],
            responsive: false,
            colReorder: false,
            dom: "Bfrtip",
            sScrollX: "100%",
            // bAutoWidth: true,
            sScrollY: "100%",
            scrollCollapse: true,
            columns: [
              {  data: null, "render":"", title:"No"},
              { data: "DocNo",title:"DocNo"},
              { data: "DocDate",title:"DocDate"},
              { data: "CreditorCode",title:"CreditorCode"},
              { data: "SupplierInvoiceNo",title:"SupplierInvoiceNo"},
              { data: "JournalType",title:"JournalType"},
              { data: "DisplayTerm",title:"DisplayTerm"},
              { data: "PurchaseAgent",title:"PurchaseAgent"},
              { data: "Description",title:"Description"},
              { data: "CurrencyRate",title:"CurrencyRate"},
              { data: "RefNo2",title:"RefNo2"},
              { data: "Note",title:"Note"},
              { data: "AccNo",title:"AccNo"},
              { data: "ToAccountRate",title:"ToAccountRate"},
              { data: "DetailDescription",title:"DetailDescription"},
              { data: "ProjNo",title:"ProjNo"},
              { data: "DeptNo",title:"DeptNo"},
              { data: "TaxType",title:"TaxType"},
              { data: "TaxableAmt",title:"TaxableAmt"},
              { data: "TaxAdjustment",title:"TaxAdjustment"},
              { data: "Amount",title:"Amount"}

            ],
           buttons: [
                   {
                           extend: 'collection',
                           text: 'Export',
                           buttons: [
                                   'csv'
                           ]
                   }
           ],

            });

            charges.api().on( 'order.dt search.dt', function () {
              charges.api().column(0, {search:'applied', order:'applied'}).nodes().each( function (cell, i) {
                  cell.innerHTML = i+1;
              } );
            } ).draw();

            $(".charges thead input").keyup ( function () {

                    /* Filter on the column (the index) of this element */
                    if ($('#charges').length > 0)
                    {

                        var colnum=document.getElementById('charges').rows[0].cells.length;

                        if (this.value=="[empty]")
                        {

                           charges.fnFilter( '^$', this.name,true,false );
                        }
                        else if (this.value=="[nonempty]")
                        {

                           charges.fnFilter( '^(?=\\s*\\S).*$', this.name,true,false );
                        }
                        else if (this.value.startsWith("!")==true && this.value.length>1)
                        {

                           charges.fnFilter( '^(?'+ this.value +').*', this.name,true,false );
                        }
                        else if (this.value.startsWith("!")==false)
                        {

                            charges.fnFilter( this.value, this.name,true,false );
                        }
                    }

            } );

       $(function(){
        $('a[data-toggle="tab"]').on( 'shown.bs.tab', function (e) {
          var target = $(e.target).attr("href") // activated tab
          $.fn.dataTable.tables( {visible: true, api: true} ).columns.adjust();
          } );
        })

        });

      </script>

      @endsection

@section('content')

  <div class="content-wrapper">
      <!-- Content Header (Page header) -->
    <section class="content-header">
      <h1>
      AP Invoice
      <small>Project Management</small>
      </h1>

      <ol class="breadcrumb">
      <li><a href="{{ url('/home') }}"><i class="fa fa-dashboard"></i> Home</a></li>
      <li><a href="#">Project Management</a></li>
      <li class="active">AP Invoice</li>
      </ol>
    </section>

    <section class="content">
      <br>
      <div class="row">
        <div class="col-md-12">
          <div class="box">
            <div class="box-body">
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
                     <option value="null">All</option>
                     @foreach($companies as $com)
                        <option <?php if($company == $com->Company_Name) echo ' selected="selected" '; ?>>{{$com->Company_Name}}</option>
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

            </div>
          </div>
        </div>
      </div>


      <div class="box">
        <div class="box-body">

          <div class="col-md-12">
            <div class="nav-tabs-custom">
              <ul class="nav nav-tabs">
                <li class="active"><a href="#cmetab" data-toggle="tab">CME</a></li>
                <li><a href="#chargestab" data-toggle="tab">Transport Charges</a></li>
              </ul>

            <div class="tab-content">
              <div class="active tab-pane" id="cmetab">
                <table id="summary" class="summary" cellspacing="0" width="100%" padding="30px" style="font-size: 13px;">
                  <thead>
                    <tr class="search">
                      <td align='center'><input type='hidden' class='search_init' /></td>
                      <?php $i=1;?>
                      @foreach($summary as $key=>$values)
                        @if ($key==0)

                        @foreach($values as $field=>$value)

                            <td align='center'><input type='text' class='search_init' name="{{$i}}"/></td>
                        <?php $i++;?>
                        @endforeach

                        @endif

                      @endforeach
                    </tr>
                      <tr>
                        @foreach($summary as $key=>$value)

                          @if ($key==0)
                                <td></td>
                            @foreach($value as $field=>$value)
                                <td/>{{ $field }}</td>
                            @endforeach

                          @endif

                        @endforeach
                      </tr>


                  </thead>
                  <tbody>

                      <?php $i = 0; ?>
                      @foreach($summary as $sum)

                            <tr id="row_{{ $i }}" >
                                <td></td>
                                @foreach($sum as $key=>$value)
                                  <td>

                                      {!! $value !!}

                                  </td>
                                @endforeach
                            </tr>
                            <?php $i++; ?>

                      @endforeach

                </tbody>
                  <tfoot></tfoot>
              </table>
            </div>


            <div class="tab-pane" id="chargestab">
                <table id="charges" class="charges" cellspacing="0" width="100%" padding="30px" style="font-size: 13px;">
              <thead>
                <tr class="search">
                  <td align='center'><input type='hidden' class='search_init' /></td>
                  <?php $i=1;?>
                  @foreach($charges as $key=>$values)
                    @if ($key==0)

                    @foreach($values as $field=>$value)

                        <td align='center'><input type='text' class='search_init' name="{{$i}}"/></td>
                    <?php $i++;?>
                    @endforeach

                    @endif

                  @endforeach
                </tr>
                  <tr>
                    @foreach($charges as $key=>$value)

                      @if ($key==0)
                            <td></td>
                        @foreach($value as $field=>$value)
                            <td/>{{ $field }}</td>
                        @endforeach

                      @endif

                    @endforeach
                  </tr>


              </thead>
              <tbody>

                  <?php $i = 0; ?>
                  @foreach($charges as $sum)

                        <tr id="row_{{ $i }}" >
                            <td></td>
                            @foreach($sum as $key=>$value)
                              <td>

                                  {!! $value !!}

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

        </div>
      </div> <!--Tab -->
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

      start=arr[0];
      end=arr[1];

      company=$('#Company').val();

      if(company)
      {
       window.location.href ="{{ url("/apinvoice") }}/"+start+"/"+end+"/"+company;
      }
      else {
        window.location.href ="{{ url("/apinvoice") }}/"+start+"/"+end;
      }

    }

</script>



@endsection
