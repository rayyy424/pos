@extends('app')

@section('orgchart-css')

    <link rel="stylesheet" type="text/css" href="{{ asset('/plugin/OrgChart/css/custom.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('/plugin/OrgChart/css/jquery.jOrgChart.css') }}">

    <style type="text/css">

    </style>

@endsection

@section('orgchart-js')

    <script type="text/javascript" language="javascript" src="{{ asset('/plugin/OrgChart/js/taffy.js') }}"></script>
    <script type="text/javascript" language="javascript" src="{{ asset('/plugin/OrgChart/js/jquery.jOrgChart.js') }}"></script>

    <script>
    var node_to_edit;

    // read json and convert to html formate
    // Here I am laod the json format to html structure. You no need to do this incase you have order list HTML in you body
    //Start Load HTML
    function loadjson() {
        var items = [];
        var data = TAFFY(
                  {!! $orgchart !!}
                );

        data({
            "SuperiorId": "-1"
        }).each(function(record, recordnumber) {
            loops(record);
        });
        //start loop the json and form the html
        function loops(root) {
            if (root.SuperiorId == "-1") {
                items.push("<li class='unic" + root.Id + " root' id='" + root.Id+ "'><span class='label_node'>" + root.Name + "</br><p>" + root.Department + "</p><p>" + root.Position + "</p></span>");
            } else {
                items.push("<li class='child unic" + root.Id + "' id='" + root.Id + "'><span class='label_node'>" + root.Name + "</br><p>" + root.Department + "</p><p>" + root.Position + "</p></span>");
            }
            var c = data({
                "SuperiorId": root.Id
            }).count();
            if (c != 0) {
                items.push("<ul>");
                data({
                    "SuperiorId": root.Id
                }).each(function(record, recordnumber) {
                    loops(record);
                });
                items.push("</ul></li>");
            } else {
                items.push("</li>");
            }
        } // End the generate html code

        //push to html code
        $("<ul/>", {
            "id": "org",
            "style": "float:right;",
            html: items.join("")
        }).appendTo("body");
    }
    // End Load HTML
    </script>

@endsection

@section('content')

    <div class="content-wrapper">

      <div id="update-alert" class="alert alert-success alert-dismissible" style="display:none;">
        <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
        <h4><i class="icon fa fa-check"></i> Alert!</h4>
        Organization chart updated!
      </div>
    <!-- Content Header (Page header) -->
    <section class="content-header">
      <h1>
        Organization Chart
        <small>Resource Management</small>
      </h1>
      <ol class="breadcrumb">
        <li><a href="#"><i class="fa fa-dashboard"></i> Home</a></li>
        <li><a href="#">Resource Management</a></li>
        <li class="active">Organization Chart</li>
      </ol>
    </section>

    <!-- Main content -->
    <section class="content">
      <div class="row">

        @if($me->Update_Org_Chart)
          <div class="box-footer">
            <button type="button" class="btn btn-block btn-primary btn-lg" onclick="makeArrays()">Update</button>
          </div>
        @endif

        <div class="col-md-10">
          <!-- Profile Image -->
          <div class="box box-success">
            <!-- /.box-header -->
            <div class="box-body">

                <div id="in" style="display:none">

                </div>

                <div id="chart" class="orgChart">

                </div>

            </div>
            <!-- /.box-body -->
          </div>
          <!-- /.box -->
          <!-- /.box -->
        </div>

        <div class="col-md-2">
          <!-- Profile Image -->
          <div class="box box-danger">
            <!-- /.box-header -->
            <div class="box-body">
              <ul id="upload-chart">

                @foreach($staffs as $staff)

                    @if ($staff->SuperiorId==0)

                    <li id="{{ $staff->Id }}" class="node child"><span class="label_node">{{ $staff->Name }}<br><p>{{ $staff->Department }}</p><p>{{ $staff->Position }}</p> </span>
                    {{-- <li class='child unic" + root.Id + "' id='" + root.Id + "'><span class='label_node'>" + root.Name + "</br><p>" + root.Department + "</p><p>" + root.Position + "</p></span>" --}}
                      {{-- <div class="details">
                        <p><strong>Name : </strong>{{ $staff->Name }}</p><br>
                        <p><strong>Department : </strong>{{ $staff->Department }}</p>
                        <p><strong>Position : </strong>{{ $staff->Position }}</p>
                      </div> --}}
                    </li>
                  @endif

                @endforeach

              </ul>
            </div>
          </div>
        </div>
        <!-- /.col -->
      </div>
      <!-- /.row -->
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
    function init_tree() {
        var opts = {
            chartElement: '#chart',
            dragAndDrop: true,
            expand: true,
            control: true,
            rowcolor: false
        };
        $("#chart").html("");
        $("#org").jOrgChart(opts);
    }

    function scroll() {
        $(".node").click(function() {
            $("#chart").scrollTop(0)
            $("#chart").scrollTop($(this).offset().top - 140);
        })
    }




    function makeArrays() {
        var hierarchy = [];

        $("#org li").each(function() {
            var uid = $(this).attr("Id");
            var Name = $(this).find(">:first-child a").text();
            var hidSTR = "";
            var hid = $(this).parents("li");
            var SuperiorId;
            if (hid.length == 0) //If this object is the root user, substitute id with "orgName" so the DB knows it's the name of organization and not a user
            {
                hidSTR = "orgName";
                parentid="";
                var user = {Id:""+ uid,Name:"" + Name,SuperiorId:"" +SuperiorId};
                hierarchy.push(user);

            } else {
                for (var i = hid.length - 1; i >= 0; i--) {
                    if (i != hid.length - 1) {
                        hidSTR = hidSTR + hid[i].id + ",";
                    } else {
                        hidSTR = hidSTR + hid[i].id + '"';
                    }
                }
                SuperiorId=hid.attr("Id");
               var user = {Id:""+ uid,Name:"" + Name,SuperiorId:"" +SuperiorId};
               hierarchy.push(user);

            }


        });

        $.ajaxSetup({
           headers: { 'X-CSRF-Token' : $('meta[name=_token]').attr('content') }
        });

        $.ajax({

                    url: "{{ url('/orgchart/update') }}",
                    method: "POST",
                    data: {
                        orgchart:JSON.stringify(hierarchy)
                    },

                    success: function(response){

                        $("#update-alert").show();
                        setTimeout(function() {
                            $("#update-alert").hide('blind', {}, 500)
                        }, 5000);

            }
        });

    }

     function reloadpage() {
		window.location.href = "index.php"
	}

    $(document).ready(function() {
        loadjson();
        init_tree();

        //forms behavior
        $("#new_node_name, #edit_node_name").on("keyup", function(evt) {
            var id = $(this).attr("id");
            if ($(this).val() != '') {
                if (id == "new_node_name") {
                    $("#add_node").show();
                } else {
                    $("#edit_node").show();
                }
            } else {
                if (id == "new_node_name") {
                    $("#add_node").hide();
                } else {
                    $("#edit_node").hide();
                }
            }
        });


        scroll()

    });
    </script>

@endsection
