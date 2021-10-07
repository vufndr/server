<?php

namespace App\Jobs;

use App\Models\TestJobRequest;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Throwable;

class TestJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $testJobRequest;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct(TestJobRequest $testJobRequest)
    {
        $this->testJobRequest = $testJobRequest;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $this->testJobRequest->job_status = 'running';
        $this->testJobRequest->save();

        if ($this->testJobRequest->should_fail) {
            $this->fail();
            return;
        }

        $this->testJobRequest->job_status = 'completed';
        $this->testJobRequest->save();
    }

    public function failed(?Throwable $exception)
    {
        $this->testJobRequest->job_status = 'failed';
        $this->testJobRequest->save();
    }
}
