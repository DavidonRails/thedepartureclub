<?php

namespace App\Http\Controllers;

use App\Services\IpnService;
use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use Mdb\PayPal\Ipn\Event\MessageVerifiedEvent;
use Mdb\PayPal\Ipn\Listener;
use Mdb\PayPal\Ipn\ListenerBuilder\Guzzle\InputStreamListenerBuilder;
use PayPal\Api\WebhookEvent;
use PayPal\Auth\OAuthTokenCredential;
use PayPal\Rest\ApiContext;

use Mdb\PayPal\Ipn\Event\MessageInvalidEvent;
use Mdb\PayPal\Ipn\Event\MessageVerificationFailureEvent;
use Mdb\PayPal\Ipn\ListenerBuilder\Guzzle\InputStreamListenerBuilder as ListenerBuilder;
use ZendService\Apple\Apns\Client\Message;

class PayPalController extends Controller
{
    public function success()
    {

        return view('paypal.callback-message', [
            'message' => 'Your request has been received. <br>You will be notified as soon as your Itinerary is ready.'
        ]);

    }

    public function cancel()
    {

        return view('paypal.callback-message', [
            'message' => 'Your request has been received. <br>You will be notified as soon as your Itinerary is ready.'
        ]);

    }

    public function ipn()
    {

        $listenerBuilder = new ListenerBuilder();

        if(env('PAYPAL_SANDBOX'))
            $listenerBuilder->useSandbox();

        $listener = $listenerBuilder->build();
        $listener->onInvalid(function (MessageInvalidEvent $event) {
            $ipnMessage = $event->getMessage();
            \Log::useDailyFiles(storage_path('logs/ipn-pp-invalid.log'));
            \Log::info($ipnMessage->getAll());

        });
        $listener->onVerified(function (MessageVerifiedEvent $event) {
            $ipnMessage = $event->getMessage();
            \Log::useDailyFiles(storage_path('logs/ipn-pp-valid.log'));
            \Log::info($ipnMessage->getAll());
            new IpnService( $ipnMessage->getAll() );

        });
        $listener->onVerificationFailure(function (MessageVerificationFailureEvent $event) {
            $error = $event->getError();
            \Log::useDailyFiles(storage_path('logs/ipn-pp-error.log'));
            \Log::info($error);
        });
        $listener->listen();

        

    }
    
}
