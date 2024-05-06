@extends('layouts.public.app')
@section('title') @if( ! empty($title)) {{ $title }} | @endif @parent @endsection

@section('content')

    <div class="main-slider" style="padding-top: 70px">
        <div class="container">
            <div class="rowslider">
                <div class="col-md-12 carousel">
                    <!-- Initialize your slider here using JavaScript or a slider library -->
                    <div id="campaignSlider" class="carousel slide" data-ride="carousel" data-in>
                        <div class="carousel-inner">
                            @foreach($new_campaigns as $index => $nc)
                                <div class="carousel-item {{$index == 0 ? 'active' : ''}}">
                                    <a href="{{route('campaign_single', [$nc->id, $nc->slug])}}"><img src="{{ $nc->feature_img_url(true) }}" class="d-block w-100" alt="Campaign Image">
                                    {{-- <div class="carousel-caption d-none d-md-block">
                                        <h5 style="font-size: 50px" style="font-weight: 100">{{ $nc->title }}</h5>
                                    </div> --}}
                                </div>
                            @endforeach
                        </div>
                        <a class="carousel-control-prev" href="#campaignSlider" role="button" data-slide="prev">
                            <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                            <span class="sr-only">Previous</span>
                        </a>
                        <a class="carousel-control-next" href="#campaignSlider" role="button" data-slide="next">
                            <span class="carousel-control-next-icon" aria-hidden="true"></span>
                            <span class="sr-only">Next</span>
                        </a>

                        <ol class="carousel-indicators">
                            @foreach($new_campaigns as $index => $nc)
                                <li data-target="#campaignSlider" data-slide-to="{{$index}}" class="{{$index == 0 ? 'active' : ''}}"></li>
                            @endforeach
                        </ol>
                    </div>

                    <!-- Toastr integration -->
                    <button id="showToastBtn" style="display: none;">Show Toast</button>
                </div>
            </div>
        </div>
    </div>

    <section class="home-campaign section-bg-white" >
        <div class="container" style="margin-bottom: 0%">
            <div class="row" style="margin-bottom: 0%">
                <div class="col-md-12">
                    <h2 class="section-title" style="font-weight: bold">{!! get_option('banner_main_header') !!}</h2>
                    <p class="jumbotron-sub-text" style="margin-bottom: 0%">لَنْ تَنَالُوا الْبِرَّ حَتّٰى تُنْفِقُوْا مِمَّا تُحِبُّوْنَ ۗوَمَا تُنْفِقُوْا مِنْ شَيْءٍ فَاِنَّ اللّٰهَ بِهٖ عَلِيْمٌ ٩٢</p>
                    <p class="jumbotron-sub-text" style="margin-bottom: 0%">{!! get_option('banner_sub_header') !!}</p>                   
                </div>
            </div>
        </div>
    </section>
    

    <section class="home-campaign section-bg-white"> <!-- explore categories -->
        <div class="container">

            <div class="row">
                <div class="col-md-12">
                    <h2 class="section-title" style="font-weight: bold">Mari Berbagi Dengan Program Kami</h2>
                </div>
            </div>

            <div class="row" style="justify-content: center">
                @foreach($categories as $cat)
                    <div class="col-md-3 col-sm-6 col-xs-12" style="min-width: 380px">
                        <div class="home-category-box">
                            <img src="{{ $cat->get_image_url() }}" />
                            <div class="title">
                                <a href="{{route('single_category', [$cat->id, $cat->category_slug])}}"></a>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>

            {{-- <div class="row">
                <div class="col-md-12">
                    <div class="section-footer">
                        <a href="{{route('browse_categories')}}" class="section-action-btn">@lang('app.see_all')</a>
                    </div>
                </div>
            </div> --}}

        </div>
    </section>

    @if($new_campaigns->count())
        <section class="home-campaign section-bg-gray new-home-campaigns">
            <div class="container">

                <div class="row">
                    <div class="col-md-12">
                        <h2 class="section-title" style="font-weight: bold" > Ayo Donasi </h2>
                    </div>
                </div>

                <div class="row">
                    <div class="box-campaign-lists">

                        @foreach($new_campaigns as $nc)
                            <div class="col-lg-3 col-md-4 col-sm-6 col-xs-12 box-campaign-item p-2">
                                <div class="box-campaign">
                                    <div class="box-campaign-image">
                                        <a href="{{route('campaign_single', [$nc->id, $nc->slug])}}"><img src="{{ $nc->feature_img_url()}}" /> </a>
                                    </div>
                                    <div class="box-campaign-content">
                                        <div class="box-campaign-description">
                                            <h4 style="text-align: center"><a href="{{route('campaign_single', [$nc->id, $nc->slug])}}"> {{$nc->title}} </a> </h4>
                                            <p>{{$nc->short_description}}</p>
                                        </div>

                                        <div class="box-campaign-summery">
                                            <ul>
                                                <li><strong>{{$nc->days_left()}}</strong> @lang('app.days_left')</li>
                                                <li><strong>{{$nc->total_payments}}</strong> @lang('app.backers')</li>
                                                <li><strong>{!! get_amount($nc->total_raised()) !!}</strong> @lang('app.funded')</li>
                                            </ul>
                                        </div>

                                        <div class="progress">
                                            @php
                                                $percent_raised = $nc->percent_raised();
                                            @endphp
                                            <div class="progress-bar" role="progressbar" aria-valuenow="{{$percent_raised}}" aria-valuemin="0" aria-valuemax="100" style="width: {{$percent_raised <= 100 ? $percent_raised : 100}}%;">
                                                {{$percent_raised}}%
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>  <!-- #box-campaign-lists -->
                </div>

                <div class="row">
                    <div class="col-md-12">
                        <div class="section-footer">
                            @if($new_campaigns->nextPageUrl())
                                <a href="{{$new_campaigns->nextPageUrl()}}" class="section-action-btn loadMorePagination"> <span id="load_more_indicator"></span> @lang('app.load_more')</a>
                            @else
                                <a href="javascript:;" class="section-action-btn" onclick="return alert('@lang('app.no_more_results')')"> <span></span> @lang('app.no_more_results')</a>
                            @endif

                        </div>
                    </div>
                </div>


            </div><!-- /.container -->
        </section>
    @endif

    @if($funded_campaigns->count())
        <section class="home-campaign section-bg-white">
            <div class="container">

                <div class="row">
                    <div class="col-md-12">
                        <h2 class="section-title"> @lang('app.recently_funded_campaigns') </h2>
                    </div>
                </div>

                <div class="row">
                    <div class="box-campaign-lists">

                        @foreach($funded_campaigns as $fc)
                            <div class="col-lg-3 col-md-4 col-sm-6 col-xs-12 box-campaign-item p-2">
                                <div class="box-campaign">
                                    <div class="box-campaign-image">
                                        <a href="{{route('campaign_single', [$fc->id, $fc->slug])}}"><img src="{{ $fc->feature_img_url()}}" /> </a>
                                    </div>
                                    <div class="box-campaign-content">
                                        <div class="box-campaign-description">
                                            <h4><a href="{{route('campaign_single', [$fc->id, $fc->slug])}}"> {{$fc->title}} </a> </h4>
                                            <p>{{$fc->short_description}}</p>
                                        </div>

                                        <div class="box-campaign-summery">
                                            <ul>
                                                <li><strong>{{$fc->days_left()}}</strong> @lang('app.days_left')</li>
                                                <li><strong>{{$fc->total_payments}}</strong> @lang('app.backers')</li>
                                                <li><strong>{!! get_amount($fc->total_raised()) !!}</strong> @lang('app.funded')</li>
                                            </ul>
                                        </div>

                                        <div class="progress">
                                            @php
                                                $percent_raised = $fc->percent_raised();
                                            @endphp
                                            <div class="progress-bar" role="progressbar" aria-valuenow="{{$percent_raised}}" aria-valuemin="0" aria-valuemax="100" style="width: {{$percent_raised <= 100 ? $percent_raised : 100}}%;">
                                                {{$percent_raised}}%
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>  <!-- #box-campaign-lists -->
                </div>

            </div><!-- /.container -->
        </section>
    @endif

    <section class="footer-campaign-stats">
        <div class="container">
            <div class="row">
                <div class="col-md-3"><h3>{{$campaigns_count}}</h3> <h4>@lang('app.campaigns')</h4></div>
                <div class="col-md-3"> <h3>{!! get_amount($fund_raised_count) !!}</h3> <h4>@lang('app.funds_raised')</h4></div>
                <div class="col-md-3"><h3>{{$payment_created}}</h3> <h4>Donatur</h4></div>
                <div class="col-md-3"><h3>{{$visitorCount}}</h3> <h4>Pengunjung</h4></div>
            </div>
        </div>
        <a target="_blank" href="https://api.whatsapp.com/send?phone=6281292533031&text=Assalamualaikum%20Kak%2C%20Saya%20mau%20mendukung%20program%20jadimanfaat%2C%20Bagaimana%20caranya%3F.%20Terimakasih%20%F0%9F%99%8F" class="whatsapp-button"><i class="fa fa-whatsapp"></i></a>
    </section>

    @include('layouts.public.partials.get_start_section')

@endsection

@section('page-js')
    <script type="text/javascript">
     
        $(document).ready(function(){
            // Initialize slider
            $('#campaignSlider').carousel();

            // Show toastr notification
            $('#showToastBtn').on('click', function() {
                toastr.success('Your message here', 'Notification');
            });
        });

        $(document).ready(function(){
            $(document).on('click', '.loadMorePagination', function (e) {
                e.preventDefault();
                var anchor = $(this);
                var page_number = anchor.attr('href').split('page=')[1];
                var new_page = parseInt(page_number) + 1;

                //Show Indicator
                $('#load_more_indicator').html('<i class="fa fa-spin fa-spinner"></i>');

                $.get( "{{route('new_campaigns_ajax')}}?page="+page_number, function( data ) {
                    if( ! data.hasOwnProperty('success')){
                        anchor.attr('href',  "{{route('new_campaigns_ajax')}}?page="+new_page);
                        var el = jQuery(data);
                       $( ".new-home-campaigns .box-campaign-lists" ).append( el );
                    }else{
                        anchor.html('@lang('app.no_more_results')');
                    }

                    //Hide
                    $('#load_more_indicator').html('');

                });

            });
        });
    </script>
    <style>
        .whatsapp-button{
            position: fixed;
            bottom: 15px;
            right: 15px;
            z-index: 99;
            background-color: #25d366;
            border-radius: 50px;
            color: #ffffff;
            text-decoration: none;
            width: 50px;
            height: 50px;
            font-size: 30px;
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
            -webkit-box-shadow: 0px 0px 25px -6px rgba(0,0,0,1);
            -moz-box-shadow: 0px 0px 25px -6px rgba(0,0,0,1);
            box-shadow: 0px 0px 25px -6px rgba(0,0,0,1);
            animation: effect 5s infinite ease-in;
        }

        @keyframes effect {
            20%, 100% {
                width: 50px;
                height: 50px;
                font-size: 30px;
            }
            0%, 10%{
                width: 55px;
                height: 55px;
                font-size: 35px;
            }
            5%{
                width: 50px;
                height: 50px;
                font-size: 30px;
            }
        }
    </style>
@endsection
