@extends('bustravel::backend.layouts.app')

@section('title', 'Profitable Route Report')

@section('content_header')
<div class="container-fluid">
    <div class="row mb-2">
      <div class="col-sm-6">
        <h1 class="m-0 text-dark">Profitable Route Report</h1>
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
              <div class="col-md-6">
                <form action="{{route('bustravel.reports.profitroute.period')}}" method="post" >
                  {{ csrf_field() }}
                <div class="form-group col-md-6">
                <select  name="period" class="form-control"  onchange="this.form.submit()">
                <option value="1" @php echo $period == 1 ? 'selected' :  "" @endphp>This Week </option>
                <option value="2" @php echo $period == 2 ? 'selected' :  "" @endphp>This Month </option>
                <option value="3" @php echo $period == 3 ? 'selected' :  "" @endphp>Last Month</option>
                <option value="4" @php echo $period == 4 ? 'selected' :  "" @endphp>Last 3 Months </option>
                <option value="5" @php echo $period == 5 ? 'selected' :  "" @endphp>Last 6 Months </option>
                <option value="6" @php echo $period == 6 ? 'selected' :  "" @endphp>This Year </option>
                </select>
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
            var myChart = echarts.init(document.getElementById('sales'));
            option = {
                title: {
                    text: 'Profitable Route Report'
                },
                tooltip: {
                    trigger: 'axis'
                },
                legend: {
                      data:['{{$first}}','{{$second}}','{{$third}}']
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
                    {
                        name:'{{$first}}',
                        type:'line',
                        stack: '{{$first}}',
                        data:[
                          @foreach($y_axis1 as $axis)
                        {{$axis}},
                        @endforeach
                            ]
                    },
                    {
                        name:'{{$second}}',
                        type:'line',
                        stack: '{{$second}}',
                        data:[
                          @foreach($y_axis2 as $axis)
                        {{$axis}},
                        @endforeach
                            ]
                    },
                    {
                        name:'{{$third}}',
                        type:'line',
                        stack: '{{$third}}',
                        data:[
                          @foreach($y_axis3 as $axis)
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
