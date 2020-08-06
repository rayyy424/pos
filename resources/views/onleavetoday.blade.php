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

      <script type="text/javascript" language="javascript" src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.8.4/moment.min.js"></script>
      <script type="text/javascript" language="javascript" src="https://cdn.datatables.net/plug-ins/1.10.15/sorting/datetime-moment.js"></script>
      <script type="text/javascript" language="javascript" src="{{ asset('/plugin/datatables/extensions/Select2/editor.select2.js') }}"></script>

      {{-- <script type="text/javascript" language="javascript" src="{{ asset('/plugin/examples/resources/syntax/shCore.js') }}"></script>
      <script type="text/javascript" language="javascript" src="{{ asset('/plugin/examples/resources/demo.js') }}"></script>
      <script type="text/javascript" language="javascript" src="{{ asset('/plugin/examples/resources/editor-demo.js') }}"></script> --}}

      <script type="text/javascript" language="javascript" class="init">

          var leaveseditor;
          var leaves2editor;
          var leaves3editor;
          var alleditor;
          var finaleditor;

          var leavetable;
          var leave2table;
          var leave3table;
          var alltable;
          var finaltable;
          var selectedtabindex;
          var asInitVals = new Array();

          $(document).ready(function() {

            $.fn.dataTable.moment( 'DD-MMM-YYYY' );

            finaleditor = new $.fn.dataTable.Editor( {
                   ajax: "{{ asset('/Include/leaveapproval2.php') }}",
                    table: "#finaltable",
                    idSrc: "leaves.Id",
                     fields: [
                       {
                                label: "Comment:",
                                name: "leavestatuses.Comment",
                                type: "textarea"
                        },
                        @if($me->Update_Medical_Claim)
                        {
                                label: "Medical_Claim:",
                                name: "leaves.Medical_Claim",
                                // type: "textarea"
                        },
                        {
                                label: "Panel_Claim:",
                                name: "leaves.Panel_Claim",
                                // type: "textarea"
                        },
                        {
                                label: "Verified_By_HR:",
                                name: "leaves.Verified_By_HR",
                                type:   'select2',
                                options: [
                                  { label :"", value: ""},
                                  { label :"No", value: "0"},
                                  { label :"Verified", value: "1"},
                                ]
                        },

                        {
                               label: "Medical Paid On:",
                               name: "leaves.Medical_Paid_Month",
                               type:   'select2',
                               def: "{{ date('F') . ' ' . date('Y') }}",
                               options: [
                               { label :"", value: ""},
                                @for($yearlist=date('Y'); $yearlist>=date('Y')-1; $yearlist--)


                                  { label :"Jan {{ $yearlist }}", value: "Jan {{ $yearlist }}"},
                                  { label :"Feb {{ $yearlist }}", value: "Feb {{ $yearlist }}"},
                                  { label :"Mar {{ $yearlist }}", value: "Mar {{ $yearlist }}"},
                                  { label :"Apr {{ $yearlist }}", value: "Apr {{ $yearlist }}"},
                                  { label :"May {{ $yearlist }}", value: "May {{ $yearlist }}"},
                                  { label :"Jun {{ $yearlist }}", value: "Jun {{ $yearlist }}"},
                                  { label :"Jul {{ $yearlist }}", value: "Jul {{ $yearlist }}"},
                                  { label :"Aug {{ $yearlist }}", value: "Aug {{ $yearlist }}"},
                                  { label :"Sep {{ $yearlist }}", value: "Sep {{ $yearlist }}"},
                                  { label :"Oct {{ $yearlist }}", value: "Oct {{ $yearlist }}"},
                                  { label :"Nov {{ $yearlist }}", value: "Nov {{ $yearlist }}"},
                                  { label :"Dec {{ $yearlist }}", value: "Dec {{ $yearlist }}"},

                                @endfor
                                ],
                                opts: {
                                    // tags: true
                                }


                        },
                        @endif
                        {
                                name: "leavestatuses.Id",
                                type: "hidden"
                        }

                     ]
            } );

                     finaltable=$('#finaltable').dataTable( {
                           ajax: {
                              "url": "{{ asset('/Include/leaveapproval.php') }}",
                              "data": {
                                  "Start": "{{ $start }}",
                                  "End": "{{ $end }}",
                                  "Type": "{{ $k }}",
                                  "Status": "%Approved%",
                              }
                            },
                            aaSorting: [[15,"desc"]],
                             columnDefs: [{ "visible": false, "targets": [1,2] },{"className": "dt-center", "targets": "_all"}],
                             responsive: false,
                             colReorder: false,
                             dom: "Blrtip",
                             "lengthMenu": [[10, 25, 50, -1], [10, 25, 50, "All"]],
                             sScrollX: "100%",
                             bAutoWidth: true,
                             iDisplayLength:10,
                             sScrollY: "100%",
                             scrollCollapse: true,
                             fnInitComplete: function(oSettings, json) {

                               $('#finalleavetab').html("Final Approved Leave " + "[" + finaltable.api().rows().count() +"]")

                              },
                             rowId:"leaves.Id",
                             columns: [
                                     {
                                       sortable: false,
                                       "render": function ( data, type, full, meta ) {
                                         return '<input type="checkbox" name="selectrow0" id="selectrow0" class="selectrow0" value="'+full.leaves.Id+'" onclick="uncheck(0)">';

                                       }

                                     },

                                     { data: "leavestatuses.Id"},
                                     { data: "leaves.Id"},
                                     { data: "leavestatuses.Leave_Status",title:"Leave_Status",
                                       "render": function ( data, type, full, meta ) {

                                            if(full.leavestatuses.Leave_Status.includes("Approved"))
                                            {
                                              return "<span class='green'>"+full.leavestatuses.Leave_Status+"</span>";
                                            }
                                            else if(full.leavestatuses.Leave_Status.includes("Rejected"))
                                            {
                                              return "<span class='red'>"+full.leavestatuses.Leave_Status+"</span>";
                                            }
                                            else {
                                              return "<span class='yellow'>"+full.leavestatuses.Leave_Status+"</span>";
                                            }

                                         }
                                     },
                                     { data: "applicant.StaffId",title:"Staff_ID"},
                                     { data: "applicant.Name",title:"Name"},
                                     { data: "leaves.Leave_Type",title:"Leave_Type" },
                                     // { data: "leaves.Leave_Term",title:"Leave Term" },
                                     { data: "leaves.Leave_Term",title:"Leave_Term",render: function (data, type, full, meta) {
                                      if (data) {
                                        return data;
                                      }
                                      return '<button type="button" class="btn btn-xs btn-default" data-toggle="modal" data-target="#viewLeaveTermsModal" onclick="viewLeaveTerms(' + full.leaves.Id + ')">View</button>';
                                     } },
                                     { data: "leaves.Start_Date",title:"Start_Date"},
                                     { data: "leaves.End_Date",title:"End_Date"},
                                     { data: "leaves.No_of_Days",title:"No_of_Days"},
                                     { data: "leaves.Reason",title:"Reason"},
                                     { data: "leaves.Medical_Claim",title:"Medical_Claim",
                                       "render": function ( data, type, full, meta ) {

                                     // if (full.leaves.Medical_Claim=="0.00")
                                     // {
                                     //   return full.leaves.Medical_Claim;
                                     // }
                                     // else {
                                      if(full.leaves.Leave_Type=="Medical Leave")
                                      {
                                        return '<a class ="buttonclaim" onclick="viewmedicalclaim(\''+full.applicant.StaffId+'\')">View</a> | ' + full.leaves.Medical_Claim;

                                      }
                                      else {
                                        return "-"
                                      }


                                      }
                                     },
                                     { data: "leaves.Panel_Claim",title:"Panel_Claim",
                                       "render": function ( data, type, full, meta ) {

                                     // if (full.leaves.Medical_Claim=="0.00")
                                     // {
                                     //   return full.leaves.Medical_Claim;
                                     // }
                                     // else {
                                      if(full.leaves.Leave_Type=="Medical Leave")
                                      {
                                        return '<a class ="buttonclaim" onclick="viewmedicalclaim(\''+full.applicant.StaffId+'\')">View</a> | ' + full.leaves.Panel_Claim;

                                      }
                                      else {
                                        return "-"
                                      }


                                      }
                                     },
                                     { data: "leaves.Verified_By_HR",title:"Verified_By_HR",
                                           "render": function ( data, type, full, meta ) {
                                          if(full.leaves.Leave_Type=="Medical Leave")
                                          {
                                            if(full.leaves.Verified_By_HR==1) {
                                              return 'Verified';
                                            }
                                            return 'No';

                                          }
                                          else {
                                            return "-"
                                          }


                                          }
                                         },
                                    { data: "leaves.Medical_Paid_Month",title:"Medical_Paid_Month"},

                                     { data: "leaves.created_at",title:"Application_Date"},
                                     { data: "projects.Project_Name",title:"Project_Name"},
                                     { data: "approver.Name",title:"Approver"},
                                     { data: "leavestatuses.Comment",title:"Comment"},
                                     { data: "leavestatuses.updated_at",title:"Review_Date"},
                                     { data: "files.Web_Path",
                                        render: function ( url, type, row ) {
                                             if (url)
                                             {
                                               return '<a href="{{ url("/") }}'+url+'" target="_blank">Download</a>';
                                             }
                                             else {
                                               return ' - ';
                                             }
                                         },
                                         title: "File"
                                       }
                             ],
                             autoFill: {
                                editor:  finaleditor,
                                columns: [ 9,10,11]
                            },
                            // keys: {
                            //     columns: ':not(:first-child)',
                            //     editor:  alleditor
                            // },
                            select: true,
                             buttons: [
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

                         

                    

                

            

                    $('#finaltable').on( 'click', 'tbody td', function (e) {
                          finaleditor.inline( this, {
                         onBlur: 'submit',
                        submit: 'allIfChanged'
                        } );
                    } );

   

                  //  leaveseditor.on( 'postEdit', function ( e, json, data ) {
                  //       // $(this.modifier()).addClass('data-changed')
                  //       notifynextlevel(data.leavestatuses.Id);
                   //
                  //   } );

                  $("#ajaxloader").hide();
                  $("#ajaxloader2").hide();
                  $("#ajaxloader3").hide();
                  $("#ajaxloader4").hide();

                  $('a[data-toggle="tab"]').on( 'shown.bs.tab', function (e) {
                    var target = $(e.target).attr("href") // activated tab


                      $.fn.dataTable.tables( {visible: true, api: true} ).columns.adjust();


                  } );


                 

                  $(".finaltable thead input").keyup ( function () {

                          /* Filter on the column (the index) of this element */
                          if ($('#finaltable').length > 0)
                          {

                              var colnum=document.getElementById('finaltable').rows[0].cells.length;

                              if (this.value=="[empty]")
                              {

                                 finaltable.fnFilter( '^$', this.name,true,false );
                              }
                              else if (this.value=="[nonempty]")
                              {

                                 finaltable.fnFilter( '^(?=\\s*\\S).*$', this.name,true,false );
                              }
                              else if (this.value.startsWith("!")==true && this.value.length>1)
                              {

                                 finaltable.fnFilter( '^(?'+ this.value +').*', this.name,true,false );
                              }
                              else if (this.value.startsWith("!")==false)
                              {

                                  finaltable.fnFilter( this.value, this.name,true,false );
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
        On Leave Today
        <small>Human Resource</small>
      </h1>
      <ol class="breadcrumb">
        <li><a href="{{ url('/home') }}"><i class="fa fa-dashboard"></i> Home</a></li>
        <li><a href="#">Human Resource</a></li>
        <li><a href="#">Human Resource Dashboard</a></li>
        <li class="active">On Leave Today</li>
      </ol>
    </section>

    <!-- Main content -->
    <section class="content">
      <div class="row">
        <div class="col-md-12">
          <div class="nav-tabs-custom">
            <ul class="nav nav-tabs">
              @if($me->View_All_Leave)
                <li class="active"><a href="#finalleave" data-toggle="tab" id="finalleavetab">Final Approved Leave</a></li>
              @endif
            </ul>
          </div>
        </div>
      </div>
            <div class="tab-content">

              <div class="active tab-pane" id="finalleave">

                <table id="finaltable" class="finaltable" cellspacing="0" width="100%" padding="30px" style="font-size: 13px;">
                    <thead>
                        {{-- prepare header search textbox --}}
                        @if($finalapprovedleave)
                          <tr class="search">

                            @foreach($finalapprovedleave as $key=>$value)

                              @if ($key==0)
                                <?php $i = 0; ?>

                                @foreach($value as $field=>$a)
                                    @if ($i==0|| $i==1)
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
                        @endif
                        <tr>
                          @foreach($finalapprovedleave as $key=>$value)

                            @if ($key==0)
                              <td><input type="checkbox" name="selectall1" id="selectall1" value="all" onclick="checkall(1)"></td>
                              @foreach($value as $field=>$value)
                                  <td/>{{ $field }}</td>
                              @endforeach

                            @endif

                          @endforeach
                        </tr>
                    </thead>
                    <tbody>



                  </tbody>
                    <tfoot></tfoot>
                </table>

              </div>

          </div>
          <!-- /.nav tab content -->
        </div>
        <!-- /.av-tabs-custom -->

      </div>
      <!-- /.row -->
    </section>
    <!-- /.content -->
  </div>

  <div class="modal fade" id="viewLeaveTermsModal" role="dialog" aria-labelledby="myLeaveTermsModalLabel" style="display: none;">
    <div class="modal-dialog modal-sm" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">×</span></button>
          <h4 class="modal-title" id="myLeaveTermsModalLabel">Leave Terms</h4>
        </div>
        <div class="modal-body">
          <table id="viewLeaveTermsTable" class="table table-condensed">
            <thead>
              <tr>
                <th>Date</th>
                <th>Period</th>
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

  <!-- /.content-wrapper -->
  <footer class="main-footer">
    <div class="pull-right hidden-xs">
      <b>Version</b> 2.0.1
    </div>
    <strong>Copyright &copy; 2014-2016 <a href="http://www.softoya.com">TrackerOnTheGo</a>.</strong> All rights
    reserved.
  </footer>



<script>

  // function uncheck()
  // {
  //
  //   if (!$("#selectrow"+index).is(':checked')) {
  //     $("#selectall"+index).prop("checked", false)
  //   }

  function uncheck(index)
  {

    if (!$("#selectrow"+index).is(':checked')) {
      $("#selectall"+index).prop("checked", false)
    }

  }

  function parseHtmlEntities(str) {
      return str.replace(/&#([0-9]{1,3});/gi, function(match, numStr) {
          var num = parseInt(numStr, 10); // read num as normal number
          return String.fromCharCode(num);
      });
  }

  function checkall(index)
  {
// alert(document.getElementById("selectall").checked);

    if ($("#selectall"+index).is(':checked')) {

        $(".selectrow"+index).prop("checked", true);
         $(".selectrow"+index).trigger("change");
         if (index==0)
         {
              leavetable.api().rows().select();
         }else if (index==1)
         {
              leave2table.api().rows().select();
         }else if (index==2)
          {
              leave3table.api().rows().select();
          }

    } else {

        $(".selectrow"+index).prop("checked", false);
        $(".selectrow"+index).trigger("change");
         //leavetable.rows().deselect();
         if (index==0)
         {
              leavetable.api().rows().deselect();
         }else if (index==1) {
              leave2table.api().rows().deselect();
         }else if (index==2)
          {
              leave3table.api().rows().deselect();
          }

    }
  }

</script>
<script type="text/javascript">
  /**
   * Function to fetch leave terms and display it in a modal
   */
  function viewLeaveTerms(Leave_Id) {

    $.ajaxSetup({
       headers: { 'X-CSRF-Token' : $('meta[name=_token]').attr('content') }
    });
    $.ajax({
        url: "{{ url("/fetchLeaveTerms/") }}" + "/" + Leave_Id,
        method: "GET",
        success: function(response){
          $('#viewLeaveTermsTable > tbody').empty();
          response.Leave_Terms.forEach(function(element) {
            if (element.Leave_Period == 'Non-Workday') {
              $('#viewLeaveTermsTable > tbody').append(`<tr class='active'>
                <td>${element.Leave_Date}</td>
                <td>${element.Leave_Period}</td>
              </tr>`);
            } else {
              $('#viewLeaveTermsTable > tbody').append(`<tr>
                <td>${element.Leave_Date}</td>
                <td>${element.Leave_Period}</td>
              </tr>`);
            }
          });
        },
        error: function(data){

        }
    });

  }
</script>
@endsection
