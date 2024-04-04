
    @if( ! request()->cookie('accept_cookie'))
        <div class="alert alert-warning text-center cookie-notice" style="font-size: 16px; margin: 0; line-height: 25px;">
            <div class="container ">
                <div class="row">
                    <div class="col-md-12">
                        <p>{!! get_option('cookie_message') !!}</p>
                        <a href="#" class="cookie-ok-btn btn btn-primary">Ok</a>
                        <a href="{!! get_post_url(get_option('cookie_learn_page')) !!}">Learn More</a>
                    </div>
                </div>
            </div>
        </div>
    @endif

    <footer id="footer">
        <div class="container footer-top">

            <div class="row">

                <div class="col-md-3" style="margin-bottom: 20px">
                    <div class="footer-about">
                        <h4 class="footer-widget-title">Tentang Kami</h4>
                        <div class="clearfix"></div>
                        {!! nl2br(get_option('footer_about_us')) !!}
                    </div>
                </div>

                <div class="col-md-3" style="margin-bottom: 20px; width: 280px;">
                    <div class="footer-widget">
                        <h4 class="footer-widget-title">Hubungi Kami</h4>
                        <ul class="contact-info">
                            {!! get_option('footer_address') !!}                            
                        </ul>
                    </div>
                </div>

                <div class="col-md-3" style="margin-bottom: 20px; width: 280px;">
                    <div class="footer-widget">
                        <h4 class="footer-widget-title">Rekening Donasi</h4>                        
                        <li style="list-style-type: circle">Bank Syariah Indonesia 7265412647 </li>                            
                        <li style="list-style-type: circle">Bank Muamalat Indonesia 3320800800</li>                            
                                              
                                                        
                        
                    </div>
                </div>

                <div class="col-md-3" style="width: 170px; margin-bottom:20px">
                    <div class="footer-widget">
                        <h4 class="footer-widget-title">@lang('app.campaigns') </h4>
                        <ul class="contact-info">
                            <?php if (auth()->check() && auth()->user()->user_type == 'admin'): ?>
                                <li><a href="{{route('start_campaign')}}">@lang('app.start_a_campaign')</a> </li>
                            <?php endif; ?>                            
                            <li><a href="{{route('browse_categories')}}">@lang('app.discover_campaign')</a> </li>
                        </ul>
                    </div>
                </div>

                <div class="col-md-2">
                    <div class="footer-widget">
                        <h4 class="footer-widget-title">Jadi Manfaat</h4>
                        <ul class="contact-info">
                            <li><a href="{{route('home')}}">@lang('app.home')</a> </li>
                            <?php
                            $show_in_footer_menu = \App\Models\Post::whereStatus('1')->where('show_in_footer_menu', 1)->get();
                            ?>
                            @if($show_in_footer_menu->count() > 0)
                                @foreach($show_in_footer_menu as $page)
                                    <li><a href="{{ route('single_page', $page->slug) }}">{{ $page->title }} </a></li>
                                @endforeach
                            @endif
                            {{-- <li><a href="{{route('contact_us')}}"> @lang('app.contact_us')</a></li> --}}

                        </ul>
                    </div>
                </div>

            </div><!-- #row -->
        </div>


        <div class="container footer-bottom">
            <div class="row">
                <div class="col-md-12">
                    <p class="footer-copyright"> {!! get_text_tpl(get_option('copyright_text')) !!} </p>
                   
                </div>
            </div>
        </div>

    </footer>
