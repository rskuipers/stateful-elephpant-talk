<?php

namespace Application\Model;

class Order
{
    const STATE_NEW         = 'new';
    const STATE_PENDING     = 'pending';
    const STATE_COMPLETED   = 'completed';
    const STATE_CANCELED    = 'canceled';
    const STATE_REFUNDED    = 'refunded';

    /**
     * @var string
     */
    protected $state = self::STATE_NEW;

    /**
     * @var string
     */
    protected $email;

    /**
     * @var string
     */
    protected $paymentMethod;

    /**
     * @return string
     */
    public function getState()
    {
        return $this->state;
    }

    /**
     * @param string $state
     */
    public function setState($state)
    {
        $this->state = $state;
    }

    /**
     * @return string
     */
    public function getEmail()
    {
        return $this->email;
    }

    /**
     * @param string $email
     */
    public function setEmail($email)
    {
        $this->email = $email;
    }

    /**
     * @return string
     */
    public function getPaymentMethod()
    {
        return $this->paymentMethod;
    }

    /**
     * @param string $paymentMethod
     */
    public function setPaymentMethod($paymentMethod)
    {
        $this->paymentMethod = $paymentMethod;
    }
}
