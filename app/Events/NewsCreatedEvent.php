<?php

namespace App\Events;

use App\Models\News;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class NewsCreatedEvent
{
    use Dispatchable, SerializesModels;

    public function __construct(public readonly News $news)
    {
    }
}
