@extends('master::layouts/admin-2')

@section('css')
  <link rel="stylesheet" href="{{ asset('assets/sales/store.css') }}">
@endsection

@section('content')
<!-- BEGIN: Content-->
<div class="content-area-wrapper">
    <div class="sidebar-left">
        <div class="sidebar">
            <!-- Chat Sidebar area -->
            <div class="sidebar-content card">
                <span class="sidebar-close-icon">
                    <i class="feather icon-x"></i>
                </span>
                <div class="chat-fixed-search">
                    <div class="d-flex align-items-center">
                        <div class="sidebar-profile-toggle position-relative d-inline-flex">
                            <div class="avatar">
                                @if(auth()->user()->image)
                                  <img src="{{ \Asset::get_image_path('user-image', 'normal', auth()->user()->image) }}" alt="user_avatar" height="40" width="40">
                                @else
                                  <img src="{{ asset('assets/admin/img/user.jpg') }}" alt="user_avatar" height="40" width="40" />
                                @endif
                                <span class="avatar-status-online"></span>
                            </div>
                            <div class="bullet-success bullet-sm position-absolute"></div>
                        </div>
                        <!--<fieldset class="form-group position-relative has-icon-left mx-1 my-0 w-100">
                            <input type="text" class="form-control round" id="chat-search" placeholder="Buscar contacto...">
                            <div class="form-control-position">
                                <i class="feather icon-search"></i>
                            </div>
                        </fieldset>-->
                    </div>
                </div>
                <div id="users-list" class="chat-user-list list-group position-relative">
                    <ul class="chat-users-list-wrapper media-list">
                      @foreach ($items as $item)
                        <li @if($preset_item&&$preset_item->id==$item->id) class="active" @endif data-id="{{ $item->id }}">
                          <div class="pr-1">
                              <span class="avatar m-0 avatar-md">
                                @if($item->other_user->user->image)
                                  <img class="media-object rounded-circle" src="{{ Asset::get_image_path('user-image','normal', $item->other_user->user->image) }}" height="42" width="42" alt="">
                                @else
                                  <img class="media-object rounded-circle" src="{{ asset('assets/admin/img/user.jpg') }}" height="42" width="42" alt="">
                                @endif
                                <i></i>
                              </span>
                          </div>
                          <div class="user-chat-info">
                              <div class="contact-info">
                                  <h5 class="font-weight-bold mb-0">{{ $item->other_user->user->name }}
                                    @if(count($item->other_users)==2) & 1 más @elseif(count($item->other_users)>2) & {{ count($item->other_users)-1 }} más @endif
                                  </h5>
                              </div>
                              <div class="contact-meta">
                                  <span class="float-right mb-25" title="{{ date('d M Y | H:i', strtotime($item->created_at)) }}">{{ date('d M', strtotime($item->created_at)) }}</span>
                                  <!--<span class="badge badge-primary badge-pill float-right">{{ count($item->inbox_messages) }}</span>-->
                              </div>
                          </div>
                        </li>
                      @endforeach
                    </ul>
                    <!--<h3 class="primary p-1 mb-0">Contactos</h3>
                    <ul class="chat-users-list-wrapper media-list">
                        <li>
                            <div class="pr-1">
                                <span class="avatar m-0 avatar-md"><img class="media-object rounded-circle" src="{{ asset('assets/img/avatar/avatar-s-3.png ') }}" height="42" width="42" alt="Generic placeholder image">
                                    <i></i>
                                </span>
                            </div>
                            <div class="user-chat-info">
                                <div class="contact-info">
                                    <h5 class="font-weight-bold mb-0">Sarah Woods</h5>
                                    <p class="truncate">Lorem ipsum dolor, sit amet consectetur adipisicing elit. Consequatur ut iste aliquam nobis.</p>
                                </div>
                                <div class="contact-meta">
                                    <span class="float-right mb-25"></span>
                                </div>
                            </div>
                        </li>
                    </ul>-->
                </div>
            </div>
            <!--/ Chat Sidebar area -->

        </div>
    </div>
    <div class="content-right">
        <div class="content-wrapper">
            <div class="content-header row">
            </div>
            <div class="content-body">
                <section id="chat-app-window" class="chat-app-window">
                  @if($preset_item)
                    @include('master::includes.chat', ['item'=>$preset_item])
                  @else
                    <div class="start-chat-area" style="background: url('{{ asset('assets/img/chat-patron.jpg') }}');background-size: 30%;">
                      <span class="mb-1 start-chat-icon feather icon-message-square"></span>
                      <a href="{{ url('customer-admin/create-inbox') }}">
                        <h4 class="py-50 px-1 sidebar-toggle start-chat-text">Comienza una conversación</h4>
                      </a>
                    </div>
                  @endif
                </section>
                <!-- User Chat profile right area -->
                <div class="user-profile-sidebar">
                    <header class="user-profile-header">
                        <span class="close-icon">
                            <i class="feather icon-x"></i>
                        </span>
                        <div class="header-profile-sidebar">
                            <div class="avatar">
                                <img src="{{ asset('assets/img/avatar/avatar-s-1.png ') }}" alt="user_avatar" height="70" width="70">
                                <span class="avatar-status-busy avatar-status-lg"></span>
                            </div>
                            <h4 class="chat-user-name">Felecia Rower</h4>
                        </div>
                    </header>
                    <div class="user-profile-sidebar-area p-2">
                        <h6>About</h6>
                        <p>Toffee caramels jelly-o tart gummi bears cake I love ice cream lollipop. Sweet liquorice croissant candy danish dessert icing. Cake macaroon gingerbread toffee sweet.</p>
                    </div>
                </div>
                <!--/ User Chat profile right area -->

            </div>
        </div>
    </div>
</div>
<!-- END: Content-->
@endsection

@section('script')
  <script type="text/javascript">
    $(".chats").scrollTop($(".chats").height());

    $(".chat-application .chat-user-list ul li").on('click', function(){
      var id = $(this).data('id');
      if($('.chat-user-list ul li').hasClass('active')){
        $('.chat-user-list ul li').removeClass('active');
      }
      $(this).addClass("active");
      $(this).find(".badge").remove();
      if($('.chat-user-list ul li').hasClass('active')){
        $('.start-chat-area').addClass('d-none');
        $('.active-chat').removeClass('d-none');
      }
      else{
        $('.start-chat-area').removeClass('d-none');
        $('.active-chat').addClass('d-none');
      }
      $('#chat-app-window').load("{{ url('customer-admin/conversation') }}/"+id, function() {
        $(".chats").scrollTop($(".chats").height());
      });
    });

    // Add message to chat
    function enter_final_chat(source) {
      var message = $(".message").val();
      if(message != ""){
        var html = '<div class="chat-content">' + "<p>" + message + "</p>" + "</div>";
        $.post("{{ url('customer-admin/inbox-reply') }}", $("#reply").serialize(), function(data) {
          if(data['process']){
            $(".chats").append(data['message']);
            $(".chats").scrollTop($(".chats").height());
            $(".message").val("");
          }
        });
      }
    }
  </script>
@endsection
