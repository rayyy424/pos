@extends('app')

@section('content')
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
		<h1>Dashboard</h1>
		<ol class="breadcrumb">
	        <li><a href="#"><i class="fa fa-dashboard"></i> Home</a></li>
	        <li>SPEED FREAK Management</li>
	        <li class="active">SPEED FREAK Summary Dashboard</li>
	    </ol>
    </section>
    <br>
    <!-- Main content -->
    <section class="content">
      <div class="row">
        <div id="mySidebar" class="sidebar">
            <div class="row">
              <span><i class="menutitle fa fa-filter" onclick="toggleSidebar()">Filter</i></span>
              <button class="btn" onclick="Refresh()"><i class="fa fa-check-circle"> Apply</i></button>
              <div class="inside col-md-11">
                <label>Year</label>
                <br>
                <select class="select2 form-control" id="year" name="year">
                  <option <?php if(!$year || $year=="All") echo "selected" ?> >All</option>
                  @foreach($yearfilter as $yf)
                  <option <?php if($year == $yf->year) echo "selected" ?> >{{$yf->year}}</option>
                  @endforeach
                </select>
              </div>
              <div class="inside col-md-11">
                <label>Category</label>
                <br>
                <select class="select2 form-control" id="category" name="category">
                  <option>All</option>
                  @foreach($catfilter as $cf)
                  <option <?php if($cat == $cf) echo "selected" ?>>{{$cf}}</option>
                  @endforeach
                </select>
              </div>
              <div class="inside col-md-11">
                <label>Customer</label>
                <br>
                <select class="select2 form-control" id="customer" name="customer" multiple>
                  {{-- <option>All</option> --}}
                  @foreach($customerfilter as $cuf)
                  <option value="{{$cuf->Id}}" <?php if( in_array((string)$cuf->Id,$cusarr) !== false) echo "selected" ?> >{{$cuf->Company_Name}}</option>
                  @endforeach
                </select>
              </div>
              <div class="inside col-md-11">
                <label>Job Scope</label>
                <br>
                <select class="select2 form-control" id="scope" name="scope" multiple>
                  {{-- <option>All</option> --}}
                  @foreach($scopefilter as $sf)
                  <option <?php if( in_array($sf->scope,$scoarr) !== false) echo "selected" ?>>{{$sf->scope}}</option>
                  @endforeach
                </select>
              </div>
              <div class="inside col-md-11">
                <label>Region</label>
                <br>
                <select class="select2 form-control" id="region" name="region" multiple>
                  <option>All</option>
                  @foreach ($regionfilter as $rf)
                  <option <?php if( in_array($rf->Region,$regarr) !== false) echo "selected" ?>>{{$rf->Region}}</option>
                  @endforeach
                </select>
              </div>
            </div>
        </div>
      </div>
      <div class="row" id="main">
      	<div class="col-lg-4 col-md-6">
      		<div class="box">
      			<div id="piechart" style="width: 100%; height: 100%"></div>
      		</div>
      	</div>

        <div class="col-lg-8 col-md-6">
          <div class="box">
      	     <div id="categorychart" style="width: 100%; height: 100%"></div>
          </div>
        </div>


        <div class="col-lg-6 col-md-6">
          <div class="box">
             <div id="scopechart" style="width: 100%; height: 100%"></div>
          </div>
        </div>

        <div class="col-lg-6 col-md-6">
          <div class="box">
             <div id="regionchart" style="width: 100%; height: 100%"></div>
          </div>
        </div>

        <div class="col-lg-12 col-md-6">
          <div class="box">
             <div id="clientchart" style="width: 100%; height: 100%"></div>
          </div>
        </div>
      </div>
    </section>
</div>
<footer class="main-footer">
	<div class="pull-right hidden-xs">
		<b>Version</b> 1.0.0
	</div>
	<strong>Copyright &copy; 2014-2016 <a href="http://www.softoya.com">TrackerOnTheGo</a>.</strong> All rights reserved.
</footer>
<style type="text/css">
        .sidebar {
            height: 100%;
            width: 85px;
            position: fixed;
            z-index: 1;
            top: 100px;
            left: 0;
            background-color: #2D4EBD ;
            overflow-x: hidden;
            transition: 0.5s;
            padding-top: 10px;
            /* padding-left: 20px; */
            white-space: nowrap;
        }
        
        .sidebar .inside {
            /* padding: 8px 8px 8px 32px; */
            text-decoration: none;
            color: white;
            display: block;
            transition: 0.3s;
            padding-left: 30px;
            padding-top: 10px;
            margin: 0 auto;
        }

        .sidebar .btn {
          margin: 0 auto;
          margin-top: 20px;
          margin-left: 30px;
          width: 80%;
          /* height: 15px; */
          background-color: #35FAE9;
          color: black;
        }
        
        .sidebar a:hover {
            color: #f1f1f1;
        }
        
        .sidebar .closebtn {
            position: absolute;
            top: 0;
            right: 25px;
            font-size: 36px;
            margin-left: 50px;
        }
        
        .material-icons,
        .icon-text {
            vertical-align: middle;
        }
        
        .material-icons {
            padding-bottom: 3px;
        }
        
        #main {
            transition: margin-left .5s;
            padding: 16px;
            margin-left: 100px;
        }
        /* On smaller screens, where height is less than 450px, change the style of the sidenav (less padding and a smaller font size) */
        
        @media screen and (max-height: 450px) {
            .sidebar {
                padding-top: 15px;
            }
            .sidebar a {
                font-size: 18px;
            }
        }

        .menutitle{
          font-weight: bold;
          font-size: 20px;
          color: white;
          display: block;
          transition: 0.3s;
          padding-left: 30px;
        }

</style>
<script src="{{url('plugin/ApexChart/dist/apexcharts.min.js')}}"></script>
<script type="text/javascript">
  function Refresh(){
    var year = $('#year').val();
    var cat = $('#category').val();
    var cus = $('#customer').val();
    var scope = $('#scope').val();
    var region = $('#region').val();
    window.location.href = "{{url('speedfreaksummarydashboard')}}" + "/" + year + "/" + cat + "/" + cus + "/" + scope + "/" + region;
  }

  var mini = true;

  function toggleSidebar() {
      if (mini) {
          console.log("opening sidebar");
          document.getElementById("mySidebar").style.width = "250px";
          document.getElementById("main").style.marginLeft = "250px";
          this.mini = false;
      } else {
          console.log("closing sidebar");
          document.getElementById("mySidebar").style.width = "85px";
          document.getElementById("main").style.marginLeft = "85px";
          this.mini = true;
      }
  }

  function getRandomColor() {
          var letters = '0123456789ABCDEF';
          var mycolor = '#';
          for (var i = 0; i < 6; i++) {
            mycolor += letters[Math.floor(Math.random() * 16)];
          }
          return mycolor;
  }

	$(function(){
    $('.select2').select2({width: '100%'});
    var colorarr = [];
    for(var coloring = 0 ; coloring < 100 ; coloring ++)
    {
         var x = getRandomColor();
         colorarr.push(x);
    }

		var pietitle = {!! json_encode($piecharttitle) !!};
		var piedata = {!! json_encode($piechartdata) !!};
		piedata = piedata.map(Number)
		var piechart = {
          series: piedata,
          chart: {
          width: 380,
          height : 'auto',
          type: 'pie',
        },
        labels: pietitle,
        dataLabels : {
          show : true,
          style : {
            fontSize : "10px",
            colors : ["#000000"],
          },
          formatter: function (val,opts) {
            console.log(val,opts);
            return "MYR " + opts.w.config.series[opts.seriesIndex].toLocaleString(undefined, {maximumFractionDigits:2}) + " (" + val.toFixed(2) + "%)"
          }
        },
        title: {
          text: 'Sales By Company',
          align: 'left'
        },
        responsive: [{
          breakpoint: 480,
          options: {
            chart: {
              width: 200
            },
            legend: {
              position: 'bottom'
            }
          }
        }]
        };

        var chart = new ApexCharts(document.querySelector("#piechart"), piechart);
        chart.render();

        //line chart by catergory
        var cate = {
          series: [
          @foreach($linechartdata as $key => $value)
          {
            name: "{{$key}}",
            data: {!! json_encode($value) !!}
          },
          @endforeach
          ],
          chart: {
          height: 350,
          type: 'line',
          zoom: {
            enabled: false
          }
        },
        dataLabels: {
          enabled: false
        },
        stroke: {
          curve: 'straight'
        },
        title: {
          text: 'Sales By Category',
          align: 'left'
        },
        grid: {
          row: {
            colors: ['#f3f3f3', 'transparent'], // takes an array which will be repeated on columns
            opacity: 0.5
          },
        },
        legend: {
          position: 'top',
          horizontalAlign: 'center'
        },
        xaxis: {
          categories: {!! json_encode($range) !!}
        },
        yaxis: {
          labels: {
             formatter: (value) => { return "MYR " + value.toLocaleString(undefined, {maximumFractionDigits:2}) },
          }
        }

        };

        var chart = new ApexCharts(document.querySelector("#categorychart"), cate);
        chart.render();

        //by client
        var cli = {
          series: [
          @foreach($clientchart as $key => $value)
            {
              name: "{{$key}}",
              data: {!! json_encode($value) !!}
            },
          @endforeach
          ],
          chart: {
          type: 'bar',
          height: '500px'
        },
        plotOptions: {
          bar: {
            horizontal: true,
          }
        },
        dataLabels: {
          enabled: false
        },
        title: {
          text: 'Sales By Customer',
          align: 'left'
        },
        legend: {
          position: 'top',
          horizontalAlign: 'left'
        },
        colors : colorarr,
        xaxis: {
          categories: {!! json_encode($clientchartlabel) !!},
          labels: {
             formatter: (value) => { return "MYR " + value.toLocaleString(undefined, {maximumFractionDigits:2}) },
          }
        },
        yaxis:{
          labels: {
            align: 'left',
          },
          style: {
              fontSize: '32px',
          },
        }
        };

        var chart = new ApexCharts(document.querySelector("#clientchart"), cli);
        chart.render();

        //Scope Chart
        var options = {
          series: [
          @foreach($scopechart as $key => $value)
            {
              name : "{{$key}}",
              data : {!! json_encode($value) !!}
            },
          @endforeach 
        ],
          chart: {
          type: 'bar',
          height: 'auto'
        },
        plotOptions: {
          bar: {
            horizontal: true,
            dataLabels: {
              position: 'top',
            },
          }
        },
        dataLabels: {
          enabled: true,
          offsetX: -6,
          style: {
            fontSize: '12px',
            colors: ['#fff']
          }
        },
        colors: colorarr,
        stroke: {
          show: true,
          width: 1,
          colors: ['#fff']
        },
        legend: {
          position: 'top',
          horizontalAlign: 'left'
        },
        title: {
          text: 'Sales By Scope',
          align: 'left'
        },
        xaxis: {
          categories: {!! json_encode($scopechartlabel) !!},
          labels: {
             formatter: (value) => { return "MYR " + value.toLocaleString(undefined, {maximumFractionDigits:2}) },
          }
        }
        };

        var chart = new ApexCharts(document.querySelector("#scopechart"), options);
        chart.render();

        //Region chart

        var options = {
          series: [
            @foreach($regionchart as $key => $value)
              {
                name : "{{$key}}",
                data : {!! json_encode($value) !!}
              },
            @endforeach
          ],
          chart: {
          type: 'bar',
          height: 'auto'
        },
        plotOptions: {
          bar: {
            horizontal: true,
            dataLabels: {
              position: 'top',
            },
          }
        },
        colors : colorarr,
        legend: {
          position: 'top',
          horizontalAlign: 'left'
        },
        title: {
          text: 'Sales By Region',
          align: 'left'
        },
        dataLabels: {
          enabled: true,
          offsetX: -6,
          style: {
            fontSize: '12px',
            colors: ['#fff']
          }
        },
        stroke: {
          show: true,
          width: 1,
          colors: ['#fff']
        },
        xaxis: {
          categories: {!! json_encode($regionchartlabel) !!},
          labels: {
             formatter: (value) => { return "MYR " + value.toLocaleString(undefined, {maximumFractionDigits:2}) },
          }
        },
        };

        var chart = new ApexCharts(document.querySelector("#regionchart"), options);
        chart.render();

	});
</script>
@endsection