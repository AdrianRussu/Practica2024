<?php

namespace App\Http\Controllers;

use App\Http\Requests\NewsletterRequest;
use App\Jobs\SubscriberJoinJob;
use App\Models\Subscriber;
use Illuminate\Http\Request;

class SubscribeController extends Controller
{
    public function post(NewsletterRequest $request)
    {
        $validated = $request->validated();

        // Maybe you need more validation rules???
        $Subscriber = Subscriber::create([
            'email' => $validated['email'],
        ]);

        SubscriberJoinJob::dispatch($Subscriber);

        return redirect()->back()->with('success', 'You have successfully subscribed. Please check your email spam folder.');
    }

}
