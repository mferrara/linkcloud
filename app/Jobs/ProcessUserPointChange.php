<?php

namespace App\Jobs;

use App\User;
use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;

class ProcessUserPointChange implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $user;
    protected $point_change;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct(User $user, $point_change)
    {
        $this->user         = $user;
        $this->point_change = $point_change;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        // if > 0 it's an increment
        if($this->point_change > 0)
            \DB::table('users')->where('id', $this->user->id)->increment('points', $this->point_change);
        else
            \DB::table('users')->where('id', $this->user->id)->decrement('points', $this->point_change);
    }
}
