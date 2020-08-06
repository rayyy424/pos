
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
      {{-- chart js --}}
      <script type="text/javascript" language="javascript" src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.4.0/Chart.bundle.min.js"></script>

      <script type="text/javascript" language="javascript" class="init">

      var license;
      $(document).ready(function() {

        license=$('#licensetable').dataTable( {
               columnDefs: [{ "visible": false, "targets": [] },{"className": "dt-center", "targets": "_all"}],
               responsive: false,
               colReorder: false,
               dom: "Brt",
               bAutoWidth: true,
               sScrollX: "100%",
               sScrollY: "100%",
               "bScrollCollapse": true,
               columns: [
                 { data: null,"render":"", title:"No"},
                 { data: "files.Web_Path",
                   render: function (  data, type, full, meta ) {

                        if (data)
                        {
                          // return '<img height="64" width="64" src="'+ data +'"/>';
                          //return "<div class='user' style='background: url(\"https://www.google.com/search?q=profile+image&espv=2&biw=1280&bih=950&tbm=isch&imgil=TYfJzUB_6JYtoM%253A%253Bp5kd_UYfCDL1SM%253Bhttp%25253A%25252F%25252Fshushi168.com%25252Fprofile-pics.html&source=iu&pf=m&fir=TYfJzUB_6JYtoM%253A%252Cp5kd_UYfCDL1SM%252C_&usg=__5y_SbQEXwYMRGOd3pJFSgFFWkqE%3D&ved=0ahUKEwjHkfnL0JjQAhUFOY8KHVcBCYEQyjcIOQ&ei=x30hWIeSIoXyvATXgqSICA\") no-repeat center;background-size: 55px 70px;background-color:white;'></div>";
                          return '<img class="profile-user-dt-img img-circle" src="{{ URL::to('/')}}'+ data +'" alt="User profile picture">';
                        }
                        else {
                          return '<img class="profile-user-dt-img img-circle" src="{{ URL::to('/') ."/img/default-user.png"  }}" alt="User profile picture">';
                        }

                    },
                    title: "Image"
                  },
                 { data: "Name",title: "Name"},
                 { data: "NRIC",title: "NRIC"},
                //  { data: "Driving_File",
                //    render: function (  data, type, full, meta ) {
                //
                //         if (data)
                //         {
                //           return '<img src="{{ URL::to('/')}}'+ data +'" alt="License Image">';
                //         }
                //         else {
                //           return "";
                //         }
                //
                //     },
                //     title: "Image"
                //   },
                 { data: "CIDB_License",title: "CIDB_ID"},
                 { data: "CIDB_Expiry_Date",title: "CIDB_Expiry_Date"},
                 { data: "NIOSH_License",title: "NIOSH_ID"},
                 { data: "NIOSH_Expiry_Date",title: "NIOSH_Expiry_Date"},
                 { data: "WAH_License",title: "WAH_ID"},
                 { data: "WAH_Expiry_Date",title: "WAH_Expiry_Date"},
                 { data: "HUAWEI_License",title: "HUAWEI_ID"},
                 { data: "HUAWEI_Expiry_Date",title: "HUAWEI_Expiry_Date"},

               ],
               autoFill: {
                       //columns: ':not(:first-child)',
                      //  editor:  referenceseditor
               },
               select: {
                       style:    'os',
                       selector: 'tr'
               },
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

        license.api().on( 'order.dt search.dt', function () {
            license.api().column(0, {search:'applied', order:'applied'}).nodes().each( function (cell, i) {
                cell.innerHTML = i+1;
            } );
        } ).draw();





      $("thead input").keyup ( function () {

              /* Filter on the column (the index) of this element */
              if ($('#licensetable').length > 0)
              {

                  var colnum=document.getElementById('licensetable').rows[0].cells.length;

                  if (this.value=="[empty]")
                  {

                     license.fnFilter( '^$', $("thead input").index(this)-colnum,true,false );
                  }
                  else if (this.value=="[nonempty]")
                  {

                     license.fnFilter( '^(?=\\s*\\S).*$', $("thead input").index(this)-colnum,true,false );
                  }
                  else if (this.value.startsWith("!")==true && this.value.length>1)
                  {

                     license.fnFilter( '^(?'+ this.value +').*', $("thead input").index(this)-colnum,true,false );
                  }
                  else if (this.value.startsWith("!")==false)
                  {

                    license.fnFilter( this.value, $("thead input").index(this)-colnum,true,false );
                  }
              }


      } );

      });


      </script>

      @endsection

@section('content')

  <div class="content-wrapper">
      <!-- Content Header (Page header) -->
    <section class="content-header">
      <h1>
      License Checklist
      <small>Project Management</small>
      </h1>

      <ol class="breadcrumb">
      <li><a href="{{ url('/home') }}"><i class="fa fa-dashboard"></i> Home</a></li>
      <li><a href="#">Project Management</a></li>
      <li class="active">License Checklist</li>
      </ol>
    </section>

    <section class="content">

        <div class="row">

          <div class="box box-primary">
            <div class="box-body">
              <div class="col-md-12">
                <a href="{{ url('/licensepdf') }}" target="_blank"><button type="button" class="btn btn-primary">Export PDF</button></a>

                <table id="licensetable" class="license" cellspacing="0" width="100%" padding="30px" style="font-size: 13px;">
                    <thead>
                      @if($license)
                        <tr class="search">

                          <td align='center'><input type='hidden' class='search_init' /></td>
                          @foreach($license as $key=>$values)

                            @if ($key==0)

                            @foreach($values as $field=>$value)
                              @if ($field=="Id")
                                <td align='center'><input type='hidden' class='search_init' /></td>
                              @else
                                <td align='center'><input type='text' class='search_init' /></td>
                              @endif

                            @endforeach

                            @endif

                          @endforeach
                        </tr>
                      @endif

                        <tr>
                          @foreach($license as $key=>$value)

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
                      @foreach($license as $licenses)

                        <tr id="row_{{ $i }}">
                              <td></td>
                            @foreach($licenses as $key=>$value)
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

</script>



@endsection
