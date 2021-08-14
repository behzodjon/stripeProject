<?php

namespace App\Services;

use Stripe\StripeClient;

class StripeService
{
    /**
     * Stripe secret key
     *
     * @var string
     */
    protected $secretKey;

    public function __construct()
    {
        $this->secretKey = config('services.stripe.secret');
    }

    /**
     * Set the Stripe secret key
     *
     * @param string $key
     * @return StripeService
     */
    public function setSecretKey(String $key): StripeService
    {
        $this->secretKey = $key;

        return $this;
    }

    /**
     * Get a new StripeClient object
     *
     * @param string|null $key (optional)
     * @return \Stripe\StripeClient
     */
    public function make(string $key = null): StripeClient
    {
        return new StripeClient($this->secretKey);
    }
}
