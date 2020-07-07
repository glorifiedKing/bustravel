@extends('bustravel::backend.layouts.app')

@section('title', 'Traffic Report')

@section('content_header')
<div class="container-fluid">
    <div class="row mb-2">
      <div class="col-sm-6">
        <h1 class="m-0 text-dark">Traffic Report</h1>
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
              <div class="col-md-12">
                <form action="{{route('bustravel.reports.traffic.period')}}" method="post" >
                  {{ csrf_field() }}
                  <div class="row">
                  <div class="form-group col-md-3">
                  <select  name="period" class="form-control select2"  onchange="this.form.submit()" >
                  <option value="1" @php echo $t_period == 1 ? 'selected' :  "" @endphp>This Week </option>
                  <option value="2" @php echo $t_period == 2 ? 'selected' :  "" @endphp>This Month </option>
                  <option value="3" @php echo $t_period == 3 ? 'selected' :  "" @endphp>Last Month</option>
                  <option value="4" @php echo $t_period == 4 ? 'selected' :  "" @endphp>Last 3 Months </option>
                  <option value="5" @php echo $t_period == 5 ? 'selected' :  "" @endphp>Last 6 Months </option>
                  <option value="6" @php echo $t_period == 6 ? 'selected' :  "" @endphp>This Year </option>
                  </select>
                </div>
                <div class="form-group col-md-6">
                <select  name="route" class="form-control select2"  onchange="this.form.submit()">
                 <option value="all">All Routes</option>
                @foreach ($t_routes as $main_route)
                <option value="{{$main_route->id}}" @php echo $main_route->id == $t_route_id ? 'selected' :  "" @endphp>{{$main_route->start->name}} [ {{$main_route->start->code}} ] - {{$main_route->end->name}} [ {{$main_route->end->code}} ] </option>
                @endforeach
                </select>
              </div>
              <div class="form-group col-md-3">
             @if(auth()->user()->hasAnyRole('BT Super Admin'))
             <select  name="operator_id" class="form-control select2"  onchange="this.form.submit()">
             <option ="0"> Select Operator</option>
             @foreach ($t_operators as $operator)
             <option value="{{$operator->id}}" @php echo $operator->id == $t_Selected_OperatorId ? 'selected' :  "" @endphp>{{$operator->name}}</option>
             @endforeach
             </select>
             @else
             <select  name="operator_id" class="form-control select2"  onchange="this.form.submit()">
             <option value="{{$t_Selected_OperatorId}}"> {{$t_operator_Name}}</option>
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
                title: {
                    text: 'Traffic Report'
                },
                legend: {
          data: ['{{$t_route_name}}',]
      },
                tooltip: {
                    trigger: 'axis'
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
                      saveAsImage: {show:false},
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
                    boundaryGap: true,
                    data: [
                      @foreach($t_x_axis as $axis)
                         '{{$axis}}',
                      @endforeach
                                          ]
                },
                yAxis: {
                    type: 'value'
                },
                series: [
                    {
                          name:'{{$t_route_name}}',
                        type:'bar',
                        stack: 'Traffic',
                        barGap: 0,
                        data:[
                          @foreach($t_y_axis as $axis)
                        {{$axis}},
                        @endforeach
                                          ]
                    },

                ]
            };


             myChart.setOption(option);
})
</script>

@stop
