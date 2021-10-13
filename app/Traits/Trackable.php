<?php

namespace App\Traits;

use App\Jobs\Middleware\TrackedJobMiddleware;
use App\Models\TrackedJob;
use Illuminate\Contracts\Encryption\Encrypter;
use Illuminate\Foundation\Bus\PendingDispatch;
use Throwable;

trait Trackable
{
    public $trackedJob;

    public static function dispatchWithTracking(...$arguments)
    {
        $trackedJob = TrackedJob::create([
            'class' => static::class,
            'arguments' => app(Encrypter::class)->encrypt(serialize(...$arguments)),
            'status' => 'queued',
        ]);

        return (new PendingDispatch(new static(...$arguments)))
            ->setTrackedJob($trackedJob);
    }

    public static function redispatchTrackedJob($trackedJob)
    {
        return (new PendingDispatch(new $trackedJob->class(unserialize(app(Encrypter::class)->decrypt($trackedJob->arguments)))))
            ->setTrackedJob($trackedJob);
    }

    public function setTrackedJob($trackedJob)
    {
        $this->trackedJob = $trackedJob;
        return $this;
    }

    public function middleware()
    {
        return [new TrackedJobMiddleware()];
    }

    public function failed(?Throwable $exception)
    {
        $this->trackedJob->update([
            'status' => 'failed',
        ]);
    }
}