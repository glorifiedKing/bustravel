@extends('bustravel::frontend.layouts.app')
@section('title', 'PalmKash Bus Ticketing Homepage')
@section('page-heading','Bus Ticketing System')    


            @section('content')
                <div class="row">
                    <div class="col-md-8">
                        <h1 class="h3 mb-3 font-weight-normal">Payment Processing.Ref Number: {{$transactionId ?? '0'}}</h1>
                        <div class="card">
                            <div class="card-body">
                                
                                <ul class="list-inline">
                                    <li id="notification_title"  class="list-inline-item">please wait...</li>
                                </ul>
                                
                                <h3 id="notification_message" class="card-title"></h3>
                            </div>
                        </div>
                        
                    </div>
                </div>
                @endsection

                <script src="//{{ Request::getHost() }}:{{env('LARAVEL_ECHO_PORT')}}/socket.io/socket.io.js"></script>

                <script src="{{ url('/js/app.js') }}" type="text/javascript"></script>
            
                  
            
                <script type="text/javascript">
            
                    var trans_id = '{!! $transactionId !!}';
            
                    window.Echo.channel('palmkash_database_transaction.'+trans_id+'')            
                     .listen('.transaction.updated', function (data){
            
                       console.log(data.update.status);
            
                        $("#notifification_title").html("<span>"+data.update.status+"</span>");
                        $("#notifification_message").html(""+data.update.status+"");
            
                    })
                    .listen('glorifiedking\BusTravel\Events\TransactionStatusUpdated', function (data){
            
                       console.log(data.update.status);
            
                        $("#notifification_title").html("<span>"+data.update.status+"</span>");
                        $("#notifification_message").html(""+data.update.status+"");
            
                    });
                    
            
                </script>
