<?php

declare(strict_types=1);

namespace Nucleus\Abstracts\Mail;

use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

abstract class Mail extends Mailable
{
    use SerializesModels;
}
