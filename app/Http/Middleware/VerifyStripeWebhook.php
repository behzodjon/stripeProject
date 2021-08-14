<?php

namespace App\Http\Middleware;

use Closure;
use Stripe\Webhook;
use Stripe\WebhookSignature;

class VerifyStripeWebhook
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        try {
            WebhookSignature::verifyHeader(
                $request->getContent(),
                $request->server('HTTP_STRIPE_SIGNATURE'),
                config('services.stripe.webhook.secret'),
                config('services.stripe.webhook.tolerance')
            );
        } catch (\UnexpectedValueException $e) {
            \Log::error('Stripe Webhook: Invalid payload ' . $e);
            return response('Invalid payload', 400);
        } catch (\Stripe\Exception\SignatureVerificationException $e) {
            \Log::error('Stripe Webhook: Signature Verification Failed ' . $e);
            return response('Invalid signature', 400);
        }

        return $next($request);
    }
}
