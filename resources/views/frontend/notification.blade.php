@extends('bustravel::frontend.layouts.app')
@section('title', 'PalmKash Bus Ticketing Homepage')
@section('page-heading','Bus Ticketing System')    


            @section('content')
                <div class="row">
                    <div class="col-md-8">
                        <h1 class="h3 mb-3 font-weight-normal">Payment Processing.Ref Number: {{$transactionId ?? '0'}}</h1>
                        <div class="card">
                            <div class="card-body">
                                <div id=timer></div>
                                    <script type="text/javascript">
                                        var timeoutHandle;
                                        function countdown(minutes, seconds) {
                                            function tick() {
                                                var counter = document.getElementById("timer");
                                                counter.innerHTML =
                                                    minutes.toString() + ":" + (seconds < 10 ? "0" : "") + String(seconds);
                                                seconds--;
                                                if (seconds >= 0) {
                                                    timeoutHandle = setTimeout(tick, 1000);
                                                } else {
                                                    if (minutes >= 1) {
                                                        // countdown(mins-1);   never reach “00″ issue solved:Contributed by Victor Streithorst
                                                        setTimeout(function () {
                                                            countdown(minutes - 1, 59);
                                                        }, 1000);
                                                    }
                                                }
                                            }
                                            tick();
                                        }

                                        countdown(5, 10);
                                    </script>

                                <ul class="list-inline">
                                <li id="notification_title"  class="list-inline-item"><img alt="load" height="100px" src="{{asset('vendor/glorifiedking/images/loading.gif')}}"> please wait...</li>
                                </ul>
                                
                                <h3 id="notification_message" class="card-title"></h3>
                            </div>
                        </div>
                        
                    </div>
                </div>
                @endsection
                @section('js')

                <script src="//{{ Request::getHost() }}:{{env('LARAVEL_ECHO_PORT')}}/socket.io/socket.io.js"></script>

                <script src="{{ url('/js/app.js') }}" type="text/javascript"></script>
            
                  
            
                <script type="text/javascript">
                var start_date = new Date();
                var home_url = "//{{ Request::getHost() }}"; 
                $(document).ready(function(){
                    
                
                      
            
                   /* var trans_id = '{!! $transactionId !!}';
            
                    window.Echo.channel('palmkash_database_transaction.'+trans_id+'')            
                     .listen('.transaction.updated', function (data){
            
                       console.log(data.update.status);
            
                        $("#notification_title").html("<span>"+data.update.status+": "+data.update.payment_gateway_result+"</span>");
                        $("#notification_message").html("process has "+data.update.status+" : <a href='"+home_url+"'>Back</a>");
            
                    })
                    .listen('glorifiedking\BusTravel\Events\TransactionStatusUpdated', function (data){
            
                       console.log(data.update.status);
            
                        $("#notification_title").html("<span>"+data.update.status+"</span>");
                        $("#notification_message").html("process has "+data.update.status+" : <a href='"+home_url+"'>Back</a>");
            
                    });
                    */
                    myAjaxRequest()
                    
                });
             function myAjaxRequest () {
                var past_date = new Date();
                console.log(past_date-start_date);
                if(past_date-start_date > 5*60*1000){
                    $("#notification_title").html("<span> Transaction Failed: Allowed Time of 5 minutes have passed</span>");
                    $("#notification_message").html("process has Failed : <a href='"+home_url+"'>Back</a>");
                    return false;
                }
                $.ajax({
                    url: "{{ route('bustravel.payment.status',$transactionId)}}",
                    datatype: "json",
                    type: "GET",
                        success: function (data) {
                            if(data.status == 'completed' || data.status == 'failed')
                            {
                                console.log(data.status);
                                $("#notification_title").html("<span>"+data.status+": "+data.result+"</span>");
                                $("#notification_message").html("process has "+data.status+" : <a href='"+home_url+"'>Back</a>");
                            }
                            else if(data.status == 'error')
                            {
                                $("#notification_title").html("<span> Transaction Failed: "+data.result+"</span>");
                                $("#notification_message").html("process has Failed : <a href='"+home_url+"'>Back</a>");
                    
                            }
                            else {
                                setTimeout(() => {
                                    myAjaxRequest()
                                }, 5000)
                            }
                    },
                    error: function () {
                    setTimeout(() => {
                        myAjaxRequest()
                    }, 5000) // if there was an error, wait 5 seconds and re-run the function
                    }
                })
                }

                
                </script>
                @endsection
