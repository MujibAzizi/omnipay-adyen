<?php

namespace Omnipay\Adyen\Message;

use Omnipay\Common\Message\AbstractRequest;

/**
 * Adyen Purchase Request
 */
class PurchaseRequest extends AbstractRequest
{
    protected $liveEndpoint = 'https://live.adyen.com/hpp/pay.shtml';
    protected $testEndpoint = 'https://test.adyen.com/hpp/pay.shtml';        // Hosted Payment Pages (Single):
    //~ protected $testEndpoint = 'https://test.adyen.com/hpp/select.shtml'; // Hosted Payment Pages (multiple)

    public function getMerchantAccount()
    {
        return $this->getParameter('merchantAccount');
    }

    /**
     * @param String $value The merchant account you want to process this payment with.
     */
    public function setMerchantAccount($value)
    {
        return $this->setParameter('merchantAccount', $value);
    }

    public function getSkinCode()
    {
        return $this->getParameter('skinCode');
    }

    /**
     * @param String $value The code of the skin to be used.
     * You can have more than one skin associated with your account if you require a different branding.
     */
    public function setSkinCode($value)
    {
        return $this->setParameter('skinCode', $value);
    }

    public function getSecret()
    {
        return $this->getParameter('secret');
    }

    /**
     * @param String $value A Secret known by You and Adyen, no one else.
     */
    public function setSecret($value)
    {
        return $this->setParameter('secret', $value);
    }

    public function getShipBeforeDate()
    {
        return $this->getParameter('shipBeforeDate');
    }

    /**
     * @param String $value The date by which the goods or services specified in the order must be shipped or rendered.
     */
    public function setShipBeforeDate($value)
    {
        return $this->setParameter('shipBeforeDate', $value);
    }

    public function getSessionValidity()
    {
        return $this->getParameter('sessionValidity');
    }

    /**
     * @param String $value The final time by which a payment needs to have been made. This is especially useful for
     * tickets/reservations, where you want to “lock” the item for sale for only a short time and payments made
     * after this time would lead to extra costs and administrative hassle. Format is YYYY-MM-DDThh:mm:ssTZD.
     * TZD is the Time Zone Designator which can either be the letter 'Z' or +hh:mm or -hh:mm.
     */
    public function setSessionValidity($value)
    {
        return $this->setParameter('sessionValidity', $value);
    }

    public function getMerchantReference()
    {
        return $this->getParameter('merchantReference');
    }

    /**
     * @param String $value The (merchant) reference for this payment.
     * This reference will be used in all communication to the merchant about the status of the payment.
     * Although it is a good idea to make sure it is unique, this is not a requirement.
     */
    public function setMerchantReference($value)
    {
        return $this->setParameter('merchantReference', $value);
    }

    public function getShopperLocale()
    {
        return $this->getParameter('shopperLocale');
    }

    /**
     * Set the langauge of the Payment Form on the Adyen site.
     *
     * The default locale is en_GB.
     * A combination of language code and country code to specify the language used in the session.
     * e.g. en_GB for (British) English).
     * Use just the language code when the country distinction is not required (i.e. fr, not fr_FR).
     *
     * @param String $value Optional locale of the payer e.g. "en_GB" or "fr".
     */
    public function setShopperLocale($value)
    {
        return $this->setParameter('shopperLocale', $value);
    }

    public function getAllowedMethods()
    {
        return $this->getParameter('allowedMethods');
    }

    /**
     * @param String $value A comma-separated list of allowed payment methods.
     * This acts as a filter on the payment methods which would normally be available in the skin.
     * Only the ones in this list will be shown (if available); all others will be ignored. No spaces are allowed.
     * Note that this parameter is optional.
     * If the parameter is not used the value for this field in the merchantSignature computation is an empty String.
     */
    public function setAllowedMethods($value)
    {
        return $this->setParameter('allowedMethods', $value);
    }

    public function getBlockedMethods()
    {
        return $this->getParameter('blockedMethods');
    }

    /**
     * @param String $value A comma-separated list of payment methods which should not be made available.
     * This acts as a filter on the payment methods which would normally be available in
     * the skin. The methods listed will be removed from the list of available payment methods. No spaces are
     * allowed. Note that this parameter is optional. If the parameter is not used the value for this field in the
     * merchantSignature computation is an empty String.
     */
    public function setBlockedMethods($value)
    {
        return $this->setParameter('blockedMethods', $value);
    }

    public function getShopperReference()
    {
        return $this->getParameter('shopperReference');
    }

    /**
     * Optional. An ID that uniquely identifies the shopper (e.g. a customer id in a shopping cart system).
     * Recommended as it is used in a velocity fraud check.
     */
    public function setShopperReference($value)
    {
        return $this->setParameter('shopperReference', $value);
    }

    public function getShopperEmail()
    {
        return $this->getParameter('shopperEmail');
    }

    /**
     * Optional. The email address of the shopper. Recommended as it is used in a velocity fraud check.
     */
    public function setShopperEmail($value)
    {
        return $this->setParameter('shopperEmail', $value);
    }

    public function getCountryCode()
    {
        return $this->getParameter('countryCode');
    }

    public function setRecurringContract($value)
    {
        return $this->setParameter('recurringContract', $value);
    }

    public function getRecurringContract()
    {
        return $this->getParameter('recurringContract');
    }

    /**
     * Optional country code of shopper.
     * By default the payment methods offered to the shopper are filtered based on the country which the IP
     * address of the shopper is mapped to. This prevents a UK shopper from being presented with a German
     * payment method like ELV. This IP-to-country mapping is not 100% accurate so if you have already
     * established the country of the shopper you may set it explicitly using the countryCode parameter.
     * This parameter is optional and is not used as part of the signing data.
     */
    public function setCountryCode($value)
    {
        return $this->setParameter('countryCode', $value);
    }

    public function getData()
    {
        $this->validate('secret', 'amount');
        $data = array();

        // Compulsory fields (needed to compute the signature)
        $data['paymentAmount'] = $this->getAmountInteger();
        $data['currencyCode'] = $this->getCurrency();
        $data['shipBeforeDate'] = $this->getShipBeforeDate();
        $data['merchantReference'] = $this->getMerchantReference();
        $data['skinCode'] = $this->getSkinCode();
        $data['merchantAccount'] = $this->getMerchantAccount();
        $data['sessionValidity'] = $this->getSessionValidity();
        $data['shopperEmail'] = $this->getShopperEmail();
        $data['shopperReference'] = $this->getShopperReference();
        $data['recurringContract'] = $this->getRecurringContract();
        $data['allowedMethods'] = $this->getAllowedMethods();
        $data['blockedMethods'] = $this->getBlockedMethods();
        $data['merchantSig'] = $this->generateSignature($data);

        // Optional fields (Not needed in signature)
        $data['shopperLocale'] = $this->getShopperLocale();
        $data['countryCode'] = $this->getCountryCode();
        $data['resURL'] = $this->getReturnUrl();

        return $data;
    }

    /**
     * The Adyen signature is computed using the HMAC algorithm with the SHA-1 hashing function using the shared secret
     * configured in the skin.
     * The input is the concatenated values of a number of the payment session fields.
     * It is in Base64 encoded format.
     */
    public function generateSignature($data)
    {
        // The data that needs to be signed is a concatenated string of the form data (except the order data)
        $sign = $data['paymentAmount'].
                $data['currencyCode'].
                $data['shipBeforeDate'].
                $data['merchantReference'].
                $data['skinCode'].
                $data['merchantAccount'].
                $data['sessionValidity'].
                $data['shopperEmail'].
                $data['shopperReference'].
                $data['recurringContract'].
                $data['allowedMethods'].
                $data['blockedMethods'];

        // base64 encoding is necessary because the string needs to be send over the internet and
        // the hexadecimal result of the HMAC encryption could include escape characters
        return base64_encode(hash_hmac('sha256', $sign, pack("H*", $this->getSecret()), true));
    }

    /**
     * Send the request with specified data.
     * Adyen is a Off-site gateway - No need to send request, instead we need to redirect customer to Adyen site
     *
     * @param  mixed             $data The data to send
     * @return ResponseInterface
     */
    public function sendData($data)
    {
        return $this->response = new PurchaseResponse($this, $data);
    }

    public function getEndpoint()
    {
        return $this->getTestMode() ? $this->testEndpoint : $this->liveEndpoint;
    }
}
