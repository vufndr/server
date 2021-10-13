<?php

namespace App\Jobs;

use App\Traits\Trackable;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Throwable;

class TestJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels, Trackable;

    protected $should_fail;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct(bool $should_fail)
    {
        $this->should_fail = $should_fail;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        if ($this->should_fail) {
            $this->fail();
            return;
        }
    }
}
