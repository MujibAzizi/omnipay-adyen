<?php

namespace Omnipay\Adyen\Message;

use Symfony\Component\HttpFoundation\Request as HttpRequest;

use Omnipay\Common\Message\AbstractResponse;

/**
 * Adyen Complete Purchase Response
 */
class CompletePurchaseResponse extends AbstractResponse
{
    public function getResponse()
    {
        $data = ($this->getData());

        return isset($data['allParams']) ? $data['allParams'] : $this;
    }

    public function isSuccessful()
    {
        $data = ($this->getData());

        if (!isset($data['success'])) {
            return false;
        }

        if ($data['allParams']['merchantSig'] !== $data['responseSignature']) {
            return false;
        }

        if ($data['success'] !== true) {
            return false;
        }

        return true;
    }

    public function isCancelled()
    {
        $data = ($this->getData());

        if (!isset($data['authResult'])) {
            return false;
        }

        return $data['authResult'] === 'CANCELLED';
    }

    public function isPending()
    {
        $data = ($this->getData());

        if (!isset($data['authResult'])) {
            return false;
        }

        return $data['authResult'] === 'PENDING';
    }

    public function getTransactionReference()
    {
        $data = ($this->getData());
        return isset($data['pspReference']) ? $data['pspReference'] : '';
    }
}
