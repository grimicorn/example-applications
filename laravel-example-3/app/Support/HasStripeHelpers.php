<?php

namespace App\Support;

use Stripe\Plan;
use Stripe\Stripe;
use Stripe\Product;
use Stripe\Error\InvalidRequest;
use App\Support\HasStripePrices;
use Illuminate\Support\Facades\Cache;
use App\Support\User\BillingTransactionType;

trait HasStripeHelpers
{
    use HasStripePrices;

    protected function getStripePricing()
    {
        return Cache::remember('stripe.pricing', 10, function () {
            Stripe::setApiKey(config('services.stripe.secret'));
            $plans = collect([]);

            // Get the monthly subscription.
            $monthlyId = BillingTransactionType::getPlanId(BillingTransactionType::MONTHLY_SUBSCRIPTION);
            $plans->put($monthlyId, $this->getMonthlyPricing());

            // Get the small monthly subscription.
            $monthlySmallId = BillingTransactionType::getPlanId(BillingTransactionType::MONTHLY_SUBSCRIPTION_SMALL);
            $plans->put($monthlySmallId, $this->getMonthlyPricingSmall());

            // Get the per-listing plan
            $perListingId = BillingTransactionType::getPlanId(BillingTransactionType::PER_LISTING);
            $plans->put($perListingId, $this->getPerListingPricing());

            return $plans;
        });
    }

    protected function getMonthlyPricing()
    {
        $monthlyId = BillingTransactionType::getPlanId(BillingTransactionType::MONTHLY_SUBSCRIPTION);
        return $this->getSubscriptionPricing($monthlyId);
    }

    protected function getMonthlyPricingSmall()
    {
        $monthlySmallId = BillingTransactionType::getPlanId(BillingTransactionType::MONTHLY_SUBSCRIPTION_SMALL);
        return $this->getSubscriptionPricing($monthlySmallId);
    }

    protected function getPerListingPricing()
    {
        $perListingId = BillingTransactionType::getPlanId(BillingTransactionType::PER_LISTING);
        return $this->getProductPricing($perListingId);
    }

    protected function getSubscriptionPricing($id)
    {
        return Cache::remember("stripe.pricing.{$id}", 10, function () use ($id) {
            try {
                Stripe::setApiKey(config('services.stripe-test.secret'));
                $monthly = Plan::retrieve($id);
                $monthly = collect([
                    'amount' => $this->convertStripePrice($monthly->amount),
                    'interval' => optional($monthly)->name,
                    'interval_count' => optional($monthly)->name,
                    'name' => optional($monthly)->name,
                ]);
            } catch (InvalidRequest $e) {
                $monthly = null;
            }

            return $monthly;
        });
    }

    protected function getProductPricing($id)
    {
        return Cache::remember("stripe.pricing.{$id}", 10, function () use ($id) {
            try {
                $product = Product::retrieve($id);
                $product = collect([
                    'amount' => $this->convertStripePrice($product->metadata->price),
                    'interval' => null,
                    'interval_count' => null,
                    'name' => $product->name,
                ]);
            } catch (InvalidRequest $e) {
                $product = null;
            }

            return $product;
        });
    }
}
