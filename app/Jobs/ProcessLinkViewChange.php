<?php

namespace App\Jobs;

use App\Link;
use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;

class ProcessLinkViewChange implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $link;
    protected $view_change;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct(Link $link, $view_change)
    {
        $this->link         = $link;
        $this->view_change = $view_change;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        \DB::table('links')->where('id', $this->link->id)->increment('given_links', $this->view_change);
    }
}
