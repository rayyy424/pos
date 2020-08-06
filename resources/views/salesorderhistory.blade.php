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

      .modal-success{
        z-index: 9999999;
      }

      .modal-danger{
        z-index: 9999999;
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

      {{-- chart js --}}
      <script type="text/javascript" language="javascript" src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.5.0/Chart.bundle.min.js"></script>
      <script src="https://unpkg.com/jspdf@latest/dist/jspdf.min.js"></script>

      <script type="text/javascript">
        var table;

        $(document).ready(function() {
             table=$('#table').DataTable({
                          columnDefs: [{ "visible": false, "targets":[1,2]},{"className": "dt-right", "targets": [11]}],
                            responsive: false,
                            dom: "Bltp",
                            sScrollX: "100%",
                            sScrollY: "100%",
                            scrollCollapse: true,
                            iDisplayLength:100,
                            bAutoWidth: true,
                            iDisplayLength:10,
                            rowId:"salesorder.Id",
                            columns: [
                            { data : null, "render":"", title: "No"},
                            { data : 'salesorder.Id', title: "Id"},
                            { data : 'tracker.sales_order', title: "SO"},
                            { data : 'salesorder.SO_Number', title: "SO Number"},
                            { data : 'projects.Project_Name', title: "Project Name"},
                            { data : 'companies.Company_Name', title: "Company"},
                            { data : 'client.Company_Name', title: "Client"},
                            { data : 'tracker.Site Name', title: "Site"},
                            { data : 'salesorder.date', title: "Date"},
                            { data : 'salesorder.rental_start', title: "Rental Start"},
                            { data : 'salesorder.rental_end', title: "Rental End"},
                            { data : 'salesorder.total_amount', title: "Total (RM)",
                                "render": function ( data, type, full, meta ) {
                                  return parseFloat(data).toFixed(2);
                                }
                            },
                            { data : 'client.Type', title: "Type"},
                            { data : 'salesorder.invoice', title: "Invoice",
                             "render": function ( data, type, full, meta ) {
                                if(full.salesorder.invoice == 1)
                                {
                                  return '<span style="color:green">Invoice Generated</span>'
                                }
                                else
                                {
                                  return '<span style="color:red">Invoice Is Not Generated</span>'
                                }
                             }
                            },
                            { data : null, title:"Acceptance Document Completion",
                            "render": function ( data, type, full, meta ) {
                                // if(full.client.Type=="GST")
                                // {
                                //     //temporary disable gor GST
                                //   return '-';
                                // }
                                // else {
                                  return '<button class="accept btn btn-default" id="'+full.salesorder.Id+'" title="Upload">Upload</button>';
                                // }

                                  }
                            },
                            { data: "files.Web_Path", title: 'Files',
                                            "render": function ( data, type, full, meta ) {
                                              var files = getRowFiles(full.salesorder.Id);
                                              if (files && files.length > 0) {
                                                var display = "";
                                                for(var i =0; i < files.length; i++) {
                                                  display += '<a href="'+ files[i].Web_Path +'" target="_blank">'+files[i].Type+'</a><br>';
                                                }
                                                if(full.salesorder.invoice == 0)
                                                  {
                                                    if({{$me->Generate_Invoice}} == 1)
                                                    {
                                                    return display + "<button class='generateinvoice btn btn-xs' id='"+full.salesorder.Id+"'>Generate Invoice</button"
                                                    }
                                                    else
                                                    {
                                                      return display;
                                                    }
                                                  }
                                                  else
                                                  {
                                                    if({{$me->View_Invoice}} == 1)
                                                    {
                                                    return display + '<a href="/invoicetemplate/'+full.salesorder.SO_Number+'" target="_blank"><button class="btn btn-xs">View Invoice</button></a>'
                                                    }
                                                    else
                                                    {
                                                      return display;
                                                    }
                                                  };
                                              }
                                              else
                                              {
                                                // if(full.client.Type == "GST")
                                                // {
                                                //   return "-";
                                                // }
                                                // else
                                                // {
                                                  if(full.salesorder.invoice == 0)
                                                  {
                                                    if({{$me->Generate_Invoice}} == 1)
                                                    {
                                                    return "<button class='generateinvoice btn btn-xs' id='"+full.salesorder.Id+"'>Generate Invoice</button"
                                                    }
                                                    else
                                                    {
                                                      return "-";
                                                    }
                                                  }
                                                  else
                                                  {
                                                    if({{$me->View_Invoice}} == 1)
                                                    {
                                                    return '<a href="/invoicetemplate/'+full.salesorder.SO_Number+'" target="_blank"><button class="btn btn-xs">View Invoice</button></a>'
                                                    }
                                                    else
                                                    {
                                                      return "-";
                                                    }
                                                  }
                                                // }
                                              }
                                              }
                            },
                            { data : null, title:"Action",
                            "render": function ( data, type, full, meta ) {
                                  if(full.tracker.sales_order != 0)
                                  {
                                      if({{$me->dummy_do}})
                                      {
                                      return '<a href="/salesordertemplate/'+full.salesorder.Id+'/history" target="_blank"><button class="btn btn-default btn-xs" title="SO" style="width:unset"><i class="fa fa-paper-plane"></i></button></a>' +
                                      '<a href="/salesorderlog/'+full.salesorder.Id+'" target="_blank"><button class="btn btn-default btn-xs" title="Log" style="width:unset"><i class="fa fa-info-circle"></i></button></a>' +
                                       '<a href="/mydeliveryrequest/{{$trackerid}}/dummy" target="_blank"><button class="btn btn-default btn-xs" title="Dummy" style="width:unset"><i class="fa fa-history"></i></button></a>' + '<button class="delete btn btn-default btn-xs" title="Delete" style="width:unset" id="'+full.salesorder.Id+'"><i class="fa fa-trash"></i></button>';
                                      }
                                      else
                                      {
                                        return '<a href="/salesordertemplate/'+full.salesorder.Id+'/history" target="_blank"><button class="btn btn-default btn-xs" title="SO" style="width:unset"><i class="fa fa-paper-plane"></i></button></a>' +
                                      '<a href="/salesorderlog/'+full.salesorder.Id+'" target="_blank"><button class="btn btn-default btn-xs" title="Log" style="width:unset"><i class="fa fa-info-circle"></i></button></a>' 
                                        + '<button class="delete btn btn-default btn-xs" title="Delete" style="width:unset" id="'+full.salesorder.Id+'"><i class="fa fa-trash"></i></button>'
                                      }
                                  }
                                  else
                                  {
                                    return "-";
                                  }
                            }
                            }
                    ],
                                 select: true,
                                 buttons: [
                                 ],
                     });

                          table.on( 'order.dt search.dt', function () {
                          table.column(0, {search:'applied', order:'applied'}).nodes().each( function (cell, i) {
                              cell.innerHTML = i+1;
                          } );
                          } ).draw();

                          $(".display thead input").keyup ( function () {

                    /* Filter on the column (the index) of this element */
                    if ($('#table').length > 0)
                    {

                        var colnum=document.getElementById('table').rows[0].cells.length;

                        if (this.value=="[empty]")
                        {

                           table.fnFilter( '^$', this.name,true,false );
                        }
                        else if (this.value=="[nonempty]")
                        {

                           table.fnFilter( '^(?=\\s*\\S).*$', this.name,true,false );
                        }
                        else if (this.value.startsWith("!")==true && this.value.length>1)
                        {

                           table.fnFilter( '^(?'+ this.value +').*', this.name,true,false );
                        }
                        else if (this.value.startsWith("!")==false)
                        {

                            table.fnFilter( this.value, this.name,true,false );
                        }
                    }



            } );
                          $("#ajaxloader4").hide();
                          $("#ajaxloader").hide();
        });
      </script>

@endsection

@section('content')

<div class="content-wrapper">
  <!-- Content Header (Page header) -->
  <section class="content-header">
    <h1>Sales Order History<small>Sales Order</small></h1>

    <ol class="breadcrumb">
      <li><a href="{{ url('/home') }}"><i class="fa fa-dashboard"></i> Home</a></li>
      <li><a href="#">Sales Order</a></li>
      <li class="active">Sales Order History</li>
    </ol>
  </section>

  <br>

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
       <div class="modal fade" id="Generate" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
            <div class="modal-dialog" role="document">
              <div class="modal-content">
                <div class="modal-header">
                  <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                  <h4 class="modal-title" id="myModalLabel">Generate Invoice</h4>
                </div>
                <div class="modal-body">
                  <div class="form-group" id="generateinvoicesoid">

                  </div>
                  Are you sure you want to generate invoice?
                </div>
                <div class="modal-footer">
                  <center><img src="{{ URL::to('/') . "/img/ajax-loader.gif" }}" width="50px" height="50px" alt="Loading" name='ajaxloader' id="ajaxloader";></center>
                  <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                  <button type="button" class="btn btn-primary" id="confirmgenerateinvoice">Generate</button>
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
                  <div class="form-group" id="soid">

                  </div>
                  Are you sure to delete the Sales Order?
                </div>
                <div class="modal-footer">
                  <!-- <center><img src="{{ URL::to('/') . "/img/ajax-loader.gif" }}" width="50px" height="50px" alt="Loading" name='ajaxloader3' id="ajaxloader3"></center> -->
                  <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                  @if($me->Delete_SO)
                  <button type="button" class="btn btn-danger" id="deletebutton">Delete</button>
                  @endif
                </div>
              </div>
            </div>
      </div>
      <div class="modal fade" id="Confirm" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
            <div class="modal-dialog" role="document">
              <div class="modal-content">
                <div class="modal-header">
                  <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                  <h4 class="modal-title" id="myModalLabel">Acceptance Documents Completion</h4>
                </div>
                <div class="modal-body">
                  <form enctype="multipart/form-data" id="upload_form" role="form" method="POST" action="" >
                    <input type="hidden" name="salesorderid" id="salesorderid">
                    <input type="hidden" name="trackerid" id="trackerid" value="{{$trackerid}}">
                    <div class="row">
                      <div class="col-md-8">
                        <label>SO:</label>
                        <div class="form-group SO">
                          <input type="file" class="form-group" name="so[]" id="so" multiple="">
                        </div>
                      </div>
                    </div>
                    <div class="row">
                      <div class="col-md-8">
                        <label>DO:</label>
                        <div class="form-group DO">
                          <input type="file" class="form-group" name="do[]" id="do" multiple="">
                        </div>
                      </div>
                    </div>
                    <div class="row">
                      <div class="col-md-8">
                          <label>BOQ:</label>
                        <div class="form-group boq">
                          <input type="file" class="form-group" name="boq[]" id="boq" multiple="">
                        </div>
                      </div>
                    </div>
                    <div class="row">
                      <div class="col-md-8">
                          <label>JAC:</label>
                        <div class="form-group jac">
                          <input type="file" class="form-group" name="jac[]" id="jac" multiple="">
                        </div>
                      </div>
                    </div>
                    <div class="row">
                      <div class="col-md-8">
                          <label>Site Report:</label>
                        <div class="form-group site_report">
                          <input type="file" class="form-group" name="site_report[]" id="site_report" multiple="">
                        </div>
                      </div>
                    </div>
                    <div class="row">
                      <div class="col-md-8">
                          <label>BOQ Approved by ROM:</label>
                        <div class="form-group boq_rom">
                          <input type="file" class="form-group" name="boq_rom[]" id="boq_rom" multiple="">
                        </div>
                      </div>
                    </div>
                    <div class="row">
                      <div class="col-md-8">
                          <label>BOQ Approved by Operation HQ:</label>
                        <div class="form-group boq_hq">
                          <input type="file" class="form-group" name="boq_hq[]" id="boq_hq" multiple="">
                        </div>
                      </div>
                    </div>
                    <div class="row">
                      <div class="col-md-8">
                          <label>BOQ Approved by Director of Operation:</label>
                        <div class="form-group boq_director">
                          <input type="file" class="form-group" name="boq_director[]" id="boq_director" multiple="">
                        </div>
                      </div>
                    </div>
                    <div class="row">
                      <div class="col-md-8">
                          <label>PO:</label>
                        <div class="form-group po">
                          <input type="file" class="form-group" name="po[]" id="po" multiple="">
                        </div>
                      </div>
                    </div>
                    <div class="row">
                      <div class="col-md-8">
                        <label>Work Order:</label>
                        <div class="form-group work_order">
                          <input type="file" class="form-group" name="work_order[]" id="work_order" multiple="">
                        </div>
                      </div>
                    </div>
                    <div class="row">
                      <div class="col-md-8">
                        <label>COC:</label>
                        <div class="form-group coc">
                          <input type="file" class="form-group" name="coc[]" id="coc" multiple="">
                        </div>
                      </div>
                    </div>
                    <div class="row">
                      <div class="col-md-8">
                        <label>Payment Checklist:</label>
                        <div class="form-group payment_checklist">
                          <input type="file" class="form-group" name="payment_checklist[]" id="payment_checklist" multiple="">
                        </div>
                      </div>
                    </div>
                    <div class="row">
                      <div class="col-md-8">
                        <label>Audit Form:</label>
                        <div class="form-group audit_form">
                          <input type="file" class="form-group" name="audit_form[]" id="audit_form" multiple="">
                        </div>
                      </div>
                    </div>
                   </form>
                </div>
                <div class="modal-footer">
                  <center><img src="{{ URL::to('/') . "/img/ajax-loader.gif" }}" width="50px" height="50px" alt="Loading" name='ajaxloader4' id="ajaxloader4"></center>
                  <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                  <button type="submit" class="btn btn-primary" id="submitacceptance">Submit</button>
                </div>
              </div>
            </div>
      </div>

    <div class="box">
    <table id="table" class="display" cellspacing="0" width="100%" padding="30px" style="font-size: 13px;">
                       <thead>
                                <tr class="search">
                                @foreach($so as $key=>$value)

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
                                    @foreach($so as $key=>$value)

                                        @if ($key==0)
                                        <td></td>

                                        @foreach($value as $field=>$value)
                                        <td>
                                            {{ $field }}
                                        </td>
                                        @endforeach
                                        <td></td>
                                        <td></td>
                                        <td></td>
                                        @endif

                                    @endforeach
                                </tr>
                            </thead>
                            <tbody>
                                <?php $i = 0; ?>
                                @foreach($so as $delivery)

                                <tr>
                                    <td></td>

                                    @foreach($delivery as $key=>$value)
                                      <td>
                                        {{ $value }}
                                      </td>
                                    @endforeach
                                    <td></td>
                                    <td></td>
                                    <td></td>
                                </tr>
                                <?php $i++; ?>
                                @endforeach
                            </tbody>
                            <tfoot></tfoot>
                        </table>
                </div>

  </section>

  <footer class="main-footer">
    <div class="pull-right hidden-xs">
      <b>Version</b> 2.0.1
    </div>
    <strong>Copyright &copy; 2014-2016 <a href="http://www.softoya.com">TrackerOnTheGo</a>.</strong> All rights reserved.
  </footer>
</div>

<script type="text/javascript">
   var files = {!! json_encode($files) !!};
   function getRowFiles(id) {
    var uploaded = [];
    for(var i=0 ; i < files.length ; i++)
    {
      if(files[i].salesorderid == id)
      {
        uploaded.push(files[i]);
      }
    }
      return uploaded;
    }
   $(function () {
    $( "#upload_form" ).submit(function( event ) {
      event.preventDefault();
      return false;
    });
  });

      $(document).ready(function() {
      $(document).on('click', '.accept', function(e) {
      var id = $(this).attr('id');
      var tracker = $('#trackerid').val();
      $("#salesorderid").val(id);
      $.ajaxSetup({
             headers: { 'X-CSRF-Token' : $('meta[name=_token]').attr('content') }
          });
          $.ajax({
            url: "{{ url('/salesorderhistory/fetchfiles') }}" + "/" + id + "/" + tracker,
                  method: "GET",
                  // data: {Id:id , trackerid: trackerid},
                  success: function(response){
                    $('#so').show();
                    $('#do').show();
                    $('#boq').show();
                    $('#jac').show();
                    $('#site_report').show();
                    $('#boq_rom').show();
                    $('#boq_hq').show();
                    $('#boq_director').show();
                    $('#po').show();
                    $('#work_order').show();
                    $('#coc').show();
                    $('#payment_checklist').show();
                    $('#audit_form').show();
                    $('.temp').remove();
                    response.Item.forEach(function(element) {
                        if(element.Type == "SO")
                        {
                          $('#so').hide();
                          $('.SO').append(`<a class="temp" targets="_blank" href="${element.Web_Path}">${element.Type}</a> `)
                        }
                        else if(element.Type == "DO")
                        {
                          $('#do').hide();
                          $('.DO').append(`<a class="temp" targets="_blank" href="${element.Web_Path}">${element.Type}</a> `)
                        }
                        else if(element.Type == "BOQ")
                        {
                          $('#boq').hide();
                          $('.boq').append(`<a class="temp" targets="_blank" href="${element.Web_Path}">${element.Type}</a> `)
                        }
                        else if(element.Type == "JAC")
                        {
                          $('#jac').hide();
                          $('.jac').append(`<a class="temp" targets="_blank" href="${element.Web_Path}">${element.Type}</a> `)
                        }
                        else if(element.Type == "Site_Report")
                        {
                          $('#site_report').hide();
                          $('.site_report').append(`<a class="temp" targets="_blank" href="${element.Web_Path}">${element.Type}</a> `)
                        }
                        else if(element.Type == "BOQ_Approved_By_ROM")
                        {
                          $('#boq_rom').hide();
                          $('.boq_rom').append(`<a class="temp" targets="_blank" href="${element.Web_Path}">${element.Type}</a> `)
                        }
                        else if(element.Type == "BOQ_Approved_By_Operation_HQ")
                        {
                          $('#boq_hq').hide();
                          $('.boq_hq').append(`<a class="temp" targets="_blank" href="${element.Web_Path}">${element.Type}</a> `)
                        }
                        else if(element.Type == "BOQ_Approved_By_Director_Of_Operation")
                        {
                          $('#boq_director').hide();
                          $('.boq_director').append(`<a class="temp" targets="_blank" href="${element.Web_Path}">${element.Type}</a> `)
                        }
                        else if(element.Type == "PO")
                        {
                          $('#po').hide();
                          $('.po').append(`<a class="temp" targets="_blank" href="${element.Web_Path}">${element.Type}</a> `)
                        }
                        else if(element.Type == "Work_Order")
                        {
                          $('#work_order').hide();
                          $('.work_order').append(`<a class="temp" targets="_blank" href="${element.Web_Path}">${element.Type}</a> `)
                        }
                        else if(element.Type == "COC")
                        {
                          $('#coc').hide();
                          $('.coc').append(`<a class="temp" targets="_blank" href="${element.Web_Path}">${element.Type}</a> `)
                        }
                        else if(element.Type == "Payment_Checklist")
                        {
                          $('#payment_checklist').hide();
                          $('.payment_checklist').append(`<a class="temp" targets="_blank" href="${element.Web_Path}">${element.Type}</a> `)
                        }
                        else
                        {
                          $('#audit_form').hide();
                          $('.audit_form').append(`<a class="temp" targets="_blank" href="${element.Web_Path}">${element.Type}</a> `)
                        }
                      });
                    }
                });
          $('#Confirm').modal('show');
        });
      });

      $(document).ready(function() {
        $(document).on('click', '#test', function(e) {
            window.location.href="{{url('/test')}}";
        });
      });

      $(document).ready(function() {
      $(document).on('click', '.generateinvoice', function(e) {
      var id = $(this).attr('id');
      $("#generateinvoicesoid").val(id);
      $('#Generate').modal('show');
        });
      });

      $(document).ready(function() {
      $(document).on('click', '#confirmgenerateinvoice', function(e) {
        $("#ajaxloader").show();
        var id = $('#generateinvoicesoid').val();
         $.ajaxSetup({
             headers: { 'X-CSRF-Token' : $('meta[name=_token]').attr('content') }
          });
          $.ajax({
                  url: "{{ url('/salesorderhistory/generateinvoice') }}",
                  method: "POST",
                  data: {Id:id},
                  success: function(response){
                    if(response == 1)
                    {
                        $("#ajaxloader").hide();
                        $('#Generate').modal('hide');
                        var message="Successfully Generate Invoice";
                        $("#update-alert ul").html(message);
                        $("#update-alert").modal('show');
                        window.location.reload();
                    }
                    else {
                        $('#Generate').modal('hide');
                        var errormessage="Failed to Generate Invoice";
                        $("#error-alert ul").html(errormessage);
                        $("#error-alert").modal('show');
                        $("#ajaxloader").hide();
                    }
                  }
                });
      });
      });

       $(document).ready(function() {
      $(document).on('click', '#submitacceptance', function(e) {
         $("#ajaxloader4").show();
        $("#submitacceptance").prop('disabled',true);
          $.ajaxSetup({
             headers: { 'X-CSRF-Token' : $('meta[name=_token]').attr('content') }
          });
          $.ajax({
                  url: "{{ url('/salesorderhistory/uploadacceptance') }}",
                  method: "POST",
                  contentType: false,
                  processData: false,
                  data:new FormData($("#upload_form")[0]),
                  success: function(response){
                    $("#submitacceptance").prop('disabled',false);
                    $('#Confirm').modal('hide');
                    if (response==1)
                    {
                        $("#ajaxloader4").hide();
                        var message="Upload Successfully";
                        $("#update-alert ul").html(message);
                        $("#update-alert").modal('show');
                        window.location.reload();
                    }
                    else {
                        var errormessage="Failed to Upload";
                        $("#error-alert ul").html(errormessage);
                        $("#error-alert").modal('show');
                        $("#ajaxloader4").hide();
                    }
                  }
                });

        });
      });

    $(document).ready(function() {
      $(document).on('click', '.delete', function(e) {
          var id = $(this).attr('id');
          var hiddeninput='<input type="hidden" class="form-control" id="deletesoid" value="'+id+'">';
          $( "#soid" ).html(hiddeninput);
          $('#Delete').modal('show');
      });
    });

    $(document).ready(function() {
      $(document).on('click', '#deletebutton', function(e) {
          var id = $('#deletesoid').val();
          $.ajaxSetup({
           headers: { 'X-CSRF-Token' : $('meta[name=_token]').attr('content') }
          });
          $.ajax({
                  url: "{{ url('/salesorderhistory/deleteso') }}",
                  method: "POST",
                  data: {Id:id},
                  success: function(response){
                    $('#Delete').modal('hide');
                    if (response==1)
                    {
                        var message="Sales Order Deleted";
                        $("#update-alert ul").html(message);
                        $("#update-alert").modal('show');
                        window.location.reload();
                    }
                    else {
                        var errormessage="Failed to Delete Sales Order";
                        $("#error-alert ul").html(errormessage);
                        $("#error-alert").modal('show');
                    }
                  }
                });

      });
    });

</script>

@endsection
