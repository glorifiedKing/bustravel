@extends('bustravel::frontend.layouts.app')
@section('title', 'PalmKash Bus Ticketing Homepage')
@section('page-heading','Bus Ticketing System')
@section('navigaton-bar')


@endsection
            @section('content')
                <div class="row">
                    <div class="col-md-8">
                        <h3 class="h3-bottom">Faqs </h3>
                        <div class="row">
                            <div class="col-md-12 ticket-card cart">
                              {!! $faqs->links() !!}

                              <div id="accordion">
                              @foreach($faqs as $index => $faq)
                                  <div class="card">
                                    <div class="card-header" id="heading{{$faq->id}}">
                                      <h5 class="mb-0">
                                        <button class="btn btn-link" data-toggle="collapse" data-target="#collapse{{$faq->id}}" aria-expanded="true" aria-controls="collapse{{$faq->id}}">
                                          {{$faq->question}}
                                        </button>
                                      </h5>
                                    </div>

                                    <div id="collapse{{$faq->id}}" class="collapse " aria-labelledby="heading{{$faq->id}}" data-parent="#accordion">
                                      <div class="card-body">
                                        {{$faq->answer}}
                                      </div>
                                    </div>
                                  </div>
                             @endforeach
                                {!! $faqs->links() !!}
                            </div>
                        </div>
                    </div>
                  </div>
                    <div class="offset-1 col-md-3 area-extras">
                        <div>FAQs FOR CUSTOMERS</div>
                    </div>
                </div>
@endsection
