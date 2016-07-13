@extends('layouts/master')
@include('helpers.meta')

@section('content')
    <!-- Slider start -->
    <div id="home" class="tz_home_slider_meetup vc_row">
        <div class="tz_home_slider_meetup_setting">
            <ul class="bxslider">
                @foreach($nodes[22]['subarray']['items'] as $item)
                    <li>
                        <div class="meetup_bl_slider_home"></div>
                        <img src="{{ Asset::get_image_path('banner-image', 'normal', $item->image) }}" alt="{{ $item->name }}" />
                    </li>
                @endforeach
            </ul>
        </div>
        <div class="tz_content_slider_meetup">
            <div class="ds-table">
                <div class="ds-table-cell">
                    <div class="tz_meetup_social">
                        <span class="meetup_line_left"></span>
                        @foreach($social_networks as $social)
                        <a href="{{ $social->url }}" target="_blank"><i class="fa fa-{{ $social->code }}"></i></a>
                       	@endforeach
                        <span class="meetup_line_right"></span>
                    </div>
                    <div class="tz_meetup_slider_home_text">
                        <p>GAD MUNICIPAL DE GUAYAQUIL</p>
                        <h4>PROGRAMA MUNICIPAL DE<br>RECONOCIMIENTO Y FOMENTO A<br><strong>INICIATIVAS SOSTENIBLES</strong></h4>
                    </div>
                    <div class="tz_meetup_countdown">
                        <div id="clock"></div>
                    </div>
                    <div class="tz_slider_meetup_btn">
                        <ul class="tz_slider_home_btn_click">
                            <li><a class="tz_slider_meetup_btn_1" href="{{ url('registro-a') }}">A. REGISTRO PARA EMPRESAS<br>AMBIENTALMENTE SOSTENIBLES</a></li>
                            <li><a class="tz_slider_meetup_btn_1" href="{{ url('registro-b') }}">B. REGISTRO PARA<br>INICIATIVAS SOSTENIBLES</a></li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- Slider end -->

    <!-- About start -->
    <section id="about" class="vc_row">
        <div class="container">
            <div class="row">
                <div class="tz_maniva_meetup_title text-center">
                    <h3 class="tz_meetup_title_raleway tz_title_meetup_normal">{{ $nodes[1]['subarray']['items'][0]['name'] }}</h3>
                    <div class="tz_image_title_meetup">
                        <img src="{{ asset('assets/images/line-black-red.png') }}" alt="line-black">
                    </div>
                    <div class="tz_meetup_title_content">
                        {!! $nodes[2]['subarray']['items'][0]['content'] !!}
                    </div>
                </div>
            </div>
        </div>
    </section>
    <!-- About end -->

    <!-- Tab Event start -->
    <section id="agenda">
        <div class="container">
            <div class="tz_maniva_meetup_title text-center">
                <h3 class="tz_maniva_meetup_title_custom">
                    <em>{{ $nodes[3]['subarray']['items'][0]['name'] }}</em>
                </h3>
                <div class="tz_image_title_meetup">
                    <img src="{{ asset('assets/images/line-black-red.png') }}" alt="line-black">
                </div>
            </div>
            <div class="tz_tab_custom">

                <!-- Tab panes -->
                <div class="tab-content tz_tab_content">
                    <div role="tabpanel" class="tab-pane fade in active" id="day_1">
                        <div class="tz_event_meetup">
                            <div class="tz_box_event_meetup">
                                <div class="tz_event_meettup_box_content">
                                    <div class="tz_event_meetup_content">
                                        @foreach($nodes[4]['subarray']['items'] as $item)
                                            <div class="tz_meetup_box_detail">
                                                <div class="tz_meetup_box_detail_custom">
                                                    <span class="tz_meetup_start_time"> {{ $item->event }} </span>
                                                    <h4>{{ $item->name }}</h4>
                                                    @if($item->content)
                                                      <div class="tz_event_meetup_item_content">
                                                        {!! $item->content !!}
                                                      </div>
                                                    @endif
                                                </div>
                                            </div>
                                        @endforeach
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                </div>

            </div>
        </div>
    </section>
    <!-- Tab Event end -->

    <!-- Speaker client start -->
    <section id="speaker_client" style="background-image: url({{ asset('assets/images/bk-101.png') }})">
        <div class="container">
            <div class="tz_maniva_meetup_title text-center">
                <h3 class="tz_maniva_meetup_title_custom">
                    <em>{{ $nodes[5]['subarray']['items'][0]['name'] }}</em>
                </h3>
                <div class="tz_image_title_meetup">
                    <img src="{{ asset('assets/images/line-black-red.png') }}" alt="line-black">
                </div>
            </div>
            <div class="vc_empty_space vc_empty_space_custom_3">
                <span class="vc_empty_space_inner"></span>
            </div>
            <div class="tz-partner">
                <ul class="partner-slider owl-carousel owl-theme">
                    @foreach($nodes[6]['subarray']['items'] as $item)
                        <li><img src="{{ Asset::get_image_path('sponsor-image', 'thumb', $item->image) }}" alt="{{ $item->name }}" /></li>
                    @endforeach
                </ul>
            </div>
            <div class="vc_empty_space vc_empty_space_custom_3">
                <span class="vc_empty_space_inner"></span>
            </div>
            <div class="tz_meetup_btn text-center ">
                <a class="tz_meetup_bnt_orange_bk" target="_blank" href="{{ url('contact') }}"> CONVIERTETE EN UN PATROCINADOR</a>
            </div>
        </div>
    </section>
    <!-- Speaker client end -->

@endsection
@section('script')
  @include('master::scripts.masonry-js')
@endsection