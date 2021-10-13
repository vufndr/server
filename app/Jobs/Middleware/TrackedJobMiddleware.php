<?php

namespace App\Jobs\Middleware;

use Throwable;

class TrackedJobMiddleware
{
    public function handle($job, $next)
    {
        $job->trackedJob->update([
            'status' => 'started',
        ]);

        try {
            $response = $next($job);

            $job->trackedJob->update([
                'status' => 'completed',
            ]);
        } catch (Throwable $exception) {
            $job->fail($exception);
        }
    }
}
