<?php

declare(strict_types=1);

namespace Nucleus\Abstracts\Listener;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\SerializesModels;

abstract class QueueListener implements ShouldQueue
{
    use Queueable;
    use SerializesModels;
}
