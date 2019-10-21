<div class="chat @if($message->user_id!=auth()->user()->id) chat-left @endif " style="margin-right: 10px;">
    @if(!$last_message || $last_message->user_id!=$message->user_id)
        <div class="chat-avatar">
            <a class="avatar m-0" data-toggle="tooltip" href="#" data-placement="right" title="" data-original-title="">
              @if($message->user->image)
                <img src="{{ \Asset::get_image_path('user-image', 'normal', $message->user->image) }}" alt="avatar" height="40" width="40" />
              @else
                <img src="{{ asset('assets/admin/img/user.jpg') }}" alt="avatar" height="40" width="40" />
              @endif
            </a>
        </div>
    @endif
    <div class="chat-body">
        <div class="chat-content" style="text-align: left;">
            <p>{{ $message->message }}</p>
            @if(!$last_message || $last_message->user_id!=$message->user_id)
            <div class="chat-footer" style="text-align: right;">
                <small>{{ $message->created_at->format('H:i') }}</small>
            </div>
            @endif
        </div>
    </div>
</div>