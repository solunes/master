<div class="active-chat d-none">
    <div class="chat_navbar">
        <header class="chat_header d-flex justify-content-between align-items-center p-1">
            <div class="vs-con-items d-flex align-items-center">
                <div class="sidebar-toggle d-block d-lg-none mr-1"><i class="feather icon-menu font-large-1"></i></div>
                <div class="avatar user-profile-toggle m-0 m-0 mr-1">
                    <img src="{{ asset('assets/img/chat-patron.jpg') }}" alt="" height="40" width="40" />
                    <span class="avatar-status-busy"></span>
                </div>
                <h6 class="mb-0">Felecia Rower111</h6>
            </div>
            <span class="favorite"><i class="feather icon-star font-medium-5"></i></span>
        </header>
    </div>
    <div class="user-chats" style="background: url('{{ asset('assets/img/chat-patron.jpg') }}');background-size: 30%;">
        <div class="chats">
            <div class="chat">
                <div class="chat-avatar">
                    <a class="avatar m-0" data-toggle="tooltip" href="#" data-placement="right" title="" data-original-title="">
                        <img src="{{ \Asset::get_image_path('user-image', 'normal', auth()->user()->image) }}" alt="avatar" height="40" width="40" />
                    </a>
                </div>
                <div class="chat-body">
                    <div class="chat-content" style="text-align: left;">
                        <p>Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod
                        tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam,
                        quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo
                        consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse
                        cillum dolore eu fugiat nulla pariatur. Excepteur sint occaecat cupidatat non
                        proident, sunt in culpa qui officia deserunt mollit anim id est laborum.</p>
                        <div class="chat-footer" style="text-align: right;">
                            <small>12:00</small>
                        </div>
                    </div>
                </div>
            </div>
            {{-- <div class="chat chat-left">
                <div class="chat-avatar">
                    <a class="avatar m-0" data-toggle="tooltip" href="#" data-placement="left" title="" data-original-title="">
                        <img src="{{ asset('assets/admin/img/no_picture.jpg') }}" alt="avatar" height="40" width="40" />
                    </a>
                </div>
                <div class="chat-body">
                    <div class="chat-content">
                        <p>Hey John, I am looking for the best admin template.</p>
                        <p>Could you please help me to find it out?</p>
                    </div>
                    <div class="chat-content">
                        <p>It should be Bootstrap 4 compatible.</p>
                    </div>
                </div>
            </div>
            <div class="divider">
                <div class="divider-text">Yesterday</div>
            </div>
            <div class="chat">
                <div class="chat-avatar">
                    <a class="avatar m-0" data-toggle="tooltip" href="#" data-placement="right" title="" data-original-title="">
                        <img src="{{ \Asset::get_image_path('user-image', 'normal', auth()->user()->image) }}" alt="avatar" height="40" width="40" />
                    </a>
                </div>
                <div class="chat-body">
                    <div class="chat-content">
                        <p>Absolutely!</p>
                    </div>
                    <div class="chat-content">
                        <p>Stack admin is the responsive bootstrap 4 admin template.</p>
                    </div>
                </div>
            </div>
            <div class="chat chat-left">
                <div class="chat-avatar">
                    <a class="avatar m-0" data-toggle="tooltip" href="#" data-placement="left" title="" data-original-title="">
                        <img src="{{ asset('assets/admin/img/no_picture.jpg') }}" alt="avatar" height="40" width="40" />
                    </a>
                </div>
                <div class="chat-body">
                    <div class="chat-content">
                        <p>Looks clean and fresh UI.</p>
                    </div>
                    <div class="chat-content">
                        <p>It's perfect for my next project.</p>
                    </div>
                    <div class="chat-content">
                        <p>How can I purchase it?</p>
                    </div>
                </div>
            </div>
            <div class="chat">
                <div class="chat-avatar">
                    <a class="avatar m-0" data-toggle="tooltip" href="#" data-placement="right" title="" data-original-title="">
                        <img src="{{ \Asset::get_image_path('user-image', 'normal', auth()->user()->image) }}" alt="avatar" height="40" width="40" />
                    </a>
                </div>
                <div class="chat-body">
                    <div class="chat-content">
                        <p>Thanks, from ThemeForest.</p>
                    </div>
                </div>
            </div>
            <div class="chat chat-left">
                <div class="chat-avatar">
                    <a class="avatar m-0" data-toggle="tooltip" href="#" data-placement="left" title="" data-original-title="">
                        <img src="{{ asset('assets/admin/img/no_picture.jpg') }}" alt="avatar" height="40" width="40" />
                    </a>
                </div>
                <div class="chat-body">
                    <div class="chat-content">
                        <p>I will purchase it for sure.</p>
                    </div>
                    <div class="chat-content">
                        <p>Thanks.</p>
                    </div>
                </div>
            </div>
            <div class="chat">
                <div class="chat-avatar">
                    <a class="avatar m-0" data-toggle="tooltip" href="#" data-placement="right" title="" data-original-title="">
                        <img src="{{ \Asset::get_image_path('user-image', 'normal', auth()->user()->image) }}" alt="avatar" height="40" width="40" />
                    </a>
                </div>
                <div class="chat-body">
                    <div class="chat-content">
                        <p>Great, Feel free to get in touch on</p>
                    </div>
                    <div class="chat-content">
                        <p>https://pixinvent.ticksy.com/</p>
                    </div>
                </div>
            </div> --}}
        </div>
    </div>
    <div class="chat-app-form">
        <form class="chat-app-input d-flex" onsubmit="enter_chat();" action="javascript:void(0);">
            <input type="text" class="form-control message mr-1 ml-50" id="iconLeft4-1" placeholder="Type your message">
            <button type="button" class="btn btn-primary send" onclick="enter_chat();"><i class="fa fa-paper-plane-o d-lg-none"></i> <span class="d-none d-lg-block">Enviar</span></button>
        </form>
    </div>
</div>
              