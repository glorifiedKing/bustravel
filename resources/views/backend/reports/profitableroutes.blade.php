@extends('bustravel::backend.layouts.app')

@section('title', 'Performance Route Report')

@section('content_header')
<div class="container-fluid">
    <div class="row mb-2">
      <div class="col-sm-6">
        <h1 class="m-0 text-dark">Route Performance  Report</h1>
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
                <div class="form-group col-md-3">
                <select  name="period" class="form-control select2" onchange="this.form.submit()" >
                <option value="1" @php echo $r_period == 1 ? 'selected' :  "" @endphp>This Week </option>
                <option value="2" @php echo $r_period == 2 ? 'selected' :  "" @endphp>This Month </option>
                <option value="3" @php echo $r_period == 3 ? 'selected' :  "" @endphp>Last Month</option>
                <option value="4" @php echo $r_period == 4 ? 'selected' :  "" @endphp>Last 3 Months </option>
                <option value="5" @php echo $r_period == 5 ? 'selected' :  "" @endphp>Last 6 Months </option>
                <option value="6" @php echo $r_period == 6 ? 'selected' :  "" @endphp>This Year </option>
                </select>
              </div>
              <div class="form-group col-md-6">
              <select  name="route" class="form-control select2"  onchange="this.form.submit()">
              <option value="all">All Routes</option>
              @foreach ($r_routes as $main_route)
              <option value="{{$main_route->id}}" @php echo $main_route->id == $r_route_id ? 'selected' :  "" @endphp>{{$main_route->start->name}} [ {{$main_route->start->code}} ] - {{$main_route->end->name}} [ {{$main_route->end->code}} ] </option>
              @endforeach
              </select>
            </div>
            <div class="form-group col-md-3">
           @if(auth()->user()->hasAnyRole('BT Super Admin'))
           <select  name="operator_id" class="form-control select2"  onchange="this.form.submit()">
           <option ="0"> Select Operator</option>
           @foreach ($r_operators as $operator)
           <option value="{{$operator->id}}" @php echo $operator->id == $r_Selected_OperatorId ? 'selected' :  "" @endphp>{{$operator->name}}</option>
           @endforeach
           </select>
           @else
           <select  name="operator_id" class="form-control select2"  onchange="this.form.submit()">
           <option value="{{$r_Selected_OperatorId}}"> {{$r_operator_Name}}</option>
           </select>

           @endif
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

    tooltip: {
        trigger: 'axis',
        axisPointer: {
            type: 'shadow'
        }
    },
    legend: {
        data: [
          @foreach($service_detials as $route_time)
          '{{$route_time}}',
        @endforeach
      ]
    },
    toolbox: {
        show: true,
        orient: 'vertical',
        left: 'right',
        top: 'center',
        feature: {
            mark: {show: false},
            dataView: {show: false, readOnly: false},
            magicType: {show: false, type: ['line', 'bar', 'stack', 'tiled']},
            restore: {show: false},
            saveAsImage: {show: false}
        }
    },
    xAxis: [
        {
            type: 'category',
            axisTick: {show: false},
            data: [
              @foreach($r_x_axis as $axis)
                 '{{$axis}}',
              @endforeach
            ]
        }
    ],
    yAxis: [
        {
            type: 'value'
        }
    ],
    series: [

      @foreach($service_detials as $key=> $route_time)
     {
         name:'{{$route_time}}',
         type:'bar',
         barGap: 0,
         data:[
           @foreach($r_weekarray as $arrayd)

             {{$arrayd[$key]}},
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
