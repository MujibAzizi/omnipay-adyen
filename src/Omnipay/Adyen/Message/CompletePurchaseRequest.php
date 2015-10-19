<?php

namespace Omnipay\Adyen\Message;

/**
 * Adyen Complete Purchase Request
 */
class CompletePurchaseRequest extends PurchaseRequest
{
    public function getData()
    {
        $data = array();

        $data['authResult'] = $this->getAuthResult();
        $data['pspReference'] = $this->getPspReference();
        $data['merchantReference'] = $this->getMerchantReference();
        $data['skinCode'] = $this->getSkinCode();
        $data['merchantSig'] = $this->getMerchantSig();

        return $data;
    }

    public function getAuthResult()
    {
        return $this->getParameter('authResult');
    }

    public function setAuthResult($value)
    {
        return $this->setParameter('authResult', $value);
    }

    public function getPspReference()
    {
        return $this->getParameter('pspReference');
    }

    public function setPspReference($value)
    {
        return $this->setParameter('pspReference', $value);
    }

    public function getMerchantReference()
    {
        return $this->getParameter('merchantReference');
    }

    public function setMerchantReference($value)
    {
        return $this->setParameter('merchantReference', $value);
    }

    public function getSkinCode()
    {
        return $this->getParameter('skinCode');
    }

    public function setSkinCode($value)
    {
        return $this->setParameter('skinCode', $value);
    }

    public function getMerchantReturnData()
    {
        return $this->getParameter('merchantReturnData');
    }

    public function setMerchantReturnData($value)
    {
        return $this->setParameter('merchantReturnData', $value);
    }

    public function getMerchantSig()
    {
        return $this->getParameter('merchantSig');
    }

    public function setMerchantSig($value)
    {
        return $this->setParameter('merchantSig', $value);
    }

    public function getPaymentMethod()
    {
        return $this->getParameter('paymentMethod');
    }

    public function setPaymentMethod($value)
    {
        return $this->setParameter('paymentMethod', $value);
    }

    public function generateResponseSignature()
    {
        $params = array(
            'authResult'            => $this->getAuthResult(),
            'pspReference'          => $this->getPspReference(),
            'merchantReference'     => $this->getMerchantReference(),
            'skinCode'              => $this->getSkinCode(),
            'paymentMethod'         => $this->getPaymentMethod(),
            'shopperLocale'         => $this->getShopperLocale(),
            'merchantReturnData'    => $this->getMerchantReturnData()
        );

        $escapeval = function ($val) {
            return str_replace(':', '\\:', str_replace('\\', '\\\\', $val));
        };

        $params = array_filter($params);
        ksort($params, SORT_STRING);

        $signData = implode(":", array_map($escapeval, array_merge(array_keys($params), array_values($params))));

        $merchantSig = base64_encode(hash_hmac('sha256', $signData, pack("H*", $this->getSecret()), true));
        return $merchantSig;
    }

    public function send()
    {
        $data = $this->getData();
        $data['success'] = $this->isSuccessful();
        $data['allParams'] = $this->getData();
        $data['responseSignature'] = $this->generateResponseSignature();

        return new CompletePurchaseResponse($this, $data);
    }

    /**
     * Check if the payment request is authorized. For use outside this library you
     * should however use the isSuccessful() method of the CompletePurchaseResponse
     * which also validates the response.
     * @return bool
     */
    public function isSuccessful()
    {
        return $this->getAuthResult() == 'AUTHORISED' || $this->getAuthResult() == 'AUTHORISATION';
    }
}
