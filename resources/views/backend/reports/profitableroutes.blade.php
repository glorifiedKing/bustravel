@extends('bustravel::backend.layouts.app')

@section('title', 'Performance Route Report')

@section('content_header')
<div class="container-fluid">
    <div class="row mb-2">
      <div class="col-sm-6">
        <h1 class="m-0 text-dark">Performance Route Report</h1>
      </div><!-- /.col -->
      <div class="col-sm-6">
        <ol class="breadcrumb float-sm-right">
          <li class="breadcrumb-item"><a href="#">Home</a></li>
          <li class="breadcrumb-item active">reports</li>
        </ol>
      </div><!-- /.col -->
    </div><!-- /.row -->
</div>
@stop

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-md-12">
            <div class="card">
              <div class="card-body">
              <div class="col-md-6">
              </div>
              <div class="col-md-12">
                <form action="{{route('bustravel.reports.profitroute.period')}}" method="post" >
                  {{ csrf_field() }}
                <div class="row">
                <div class="form-group col-md-6">
                <select  name="period" class="form-control select2" >
                <option value="1" @php echo $period == 1 ? 'selected' :  "" @endphp>This Week </option>
                <option value="2" @php echo $period == 2 ? 'selected' :  "" @endphp>This Month </option>
                <option value="3" @php echo $period == 3 ? 'selected' :  "" @endphp>Last Month</option>
                <option value="4" @php echo $period == 4 ? 'selected' :  "" @endphp>Last 3 Months </option>
                <option value="5" @php echo $period == 5 ? 'selected' :  "" @endphp>Last 6 Months </option>
                <option value="6" @php echo $period == 6 ? 'selected' :  "" @endphp>This Year </option>
                </select>
              </div>
              <div class="form-group col-md-6">
              <select  name="route" class="form-control select2"  onchange="this.form.submit()">
              @foreach ($routes as $main_route)
              <option value="{{$main_route->id}}" @php echo $main_route->id == $route_id ? 'selected' :  "" @endphp>{{$main_route->start->name}} [ {{$main_route->start->code}} ] - {{$main_route->end->name}} [ {{$main_route->end->code}} ] </option>
              @endforeach
              </select>
            </div>
          </div>
                </form>
              </div>

                  <div id="sales" style="width:100%; min-height:400px;padding:5px"></div>
            </div>
          </div>
        </div>
        <!-- /.card -->
        </div>
        <!-- /.col -->
    </div>
</div>
@stop

@section('css')

@stop

@section('js')
    @parent
    <script>

        $(function () {
            $('.select2').select2();
            var myChart = echarts.init(document.getElementById('sales'));
            option = {
                title: {
                    text: 'Performance Route Report'
                },
                tooltip: {
                    trigger: 'axis'
                },
                legend: {
                      data:[
                        @foreach($route_departures as $route_time)
                          '{{$route_time->departure_time}} - {{$route_time->arrival_time}}',
                        @endforeach

                      ]
                    },
                grid: {
                    left: '3%',
                    right: '4%',
                    bottom: '3%',
                    containLabel: true
                },
                toolbox: {
                    show: true,
                    feature: {
                      saveAsImage: {},
                        downloadTable: {
                            //show: true,
                            // Show the title when mouse focus
                            //title: 'Save As picture',
                            // Icon path
                            //icon: '/static/img/download-icon.png',
                            option: {}
                        }
                    }
                  },
                xAxis: {
                    type: 'category',
                    boundaryGap: false,
                    data: [
                      @foreach($x_axis as $axis)
                         '{{$axis}}',
                      @endforeach
                                          ]
                },
                yAxis: {
                    type: 'value'
                },
                series: [

                  @foreach($route_departures as $route_time)
                  {
                      name:' {{$route_time->departure_time}} - {{$route_time->arrival_time}}',
                      type:'line',
                      stack: '{{$route_time->departure_time}} - {{$route_time->arrival_time}}',
                      data:[
                        @foreach($weekarray as $arrayd)

                          {{$arrayd[$route_time->id]}},
                        @endforeach
          //dd($empty);

                          ]
                  },
                  @endforeach



                ]
            };


             myChart.setOption(option);
})
</script>

@stop
