<?php

namespace App\Services;

use App\Jobs\TestJob;
use App\Models\TestJobRequest;

class TestJobService
{
    public function request($should_fail = false)
    {
        $testJobRequest = TestJobRequest::make();
        $testJobRequest->job_status = 'created';
        $testJobRequest->should_fail = $should_fail;
        $testJobRequest->save();

        TestJob::dispatch($testJobRequest);

        $testJobRequest->job_status = 'queued';
        $testJobRequest->save();
    }
}
