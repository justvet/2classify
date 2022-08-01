<?php namespace Visiosoft\SubscriptionsModule\Subscription\Event;

class CreatedSubscription
{
    /**
     * @author Visiosoft LTD.
     */
    private $subscription;

    /**
     * @param $subscription
     */
    public function __construct($subscription)
    {
        $this->subscription = $subscription;
    }

    /**
     * @return mixed
     */
    public function getSubscription()
    {
        return $this->subscription;
    }



}
