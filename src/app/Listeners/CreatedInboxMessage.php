<?php

namespace Solunes\Master\App\Listeners;

class CreatedInboxMessage {

    public function handle($inbox_message) {
        $inbox_message->inbox()->touch();
    }

}
