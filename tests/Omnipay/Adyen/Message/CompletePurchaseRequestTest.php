<?php

namespace Omnipay\Adyen\Message;

use Omnipay\Tests\TestCase;

class CompletePurchaseRequestTest extends TestCase
{
    public function setUp()
    {
        $this->request = new CompletePurchaseRequest($this->getHttpClient(), $this->getHttpRequest());
        $this->request->initialize(array(
            'secret' => '0123456789ABCDEF0123456789ABCDEF0123456789ABCDEF0123456789ABCDEF',
            'authResult' => 'AUTHORISED',
            'pspReference' => '1211992213193029',
            'merchantReference' => 'Internet Order 12345',
            'skinCode' => '4aD37dJA',
            'merchantReturnData' => '',
            'merchantSig' => 'YRTyF4SIdrW2mKIbNukCTkZ21dHCzcQYOevrBII+yUI='
        ));
    }

    public function testGetData()
    {

        $data = $this->request->getData();

        $this->assertSame(array (
          'authResult' => 'AUTHORISED',
          'pspReference' => '1211992213193029',
          'merchantReference' => 'Internet Order 12345',
          'skinCode' => '4aD37dJA',
          'merchantSig' => 'YRTyF4SIdrW2mKIbNukCTkZ21dHCzcQYOevrBII+yUI='
        ), $data);

    }

    public function testGenerateResponseSignature()
    {
        $this->assertSame(
            'YRTyF4SIdrW2mKIbNukCTkZ21dHCzcQYOevrBII+yUI=',
            $this->request->generateResponseSignature($this->request->getData())
        );
    }

    public function testSend()
    {
        // Test a valid request.
        $response = $this->request->send();
        $this->assertTrue($response->isSuccessful());

        // Test a tampered with request.
        $tamperedRequest = new CompletePurchaseRequest($this->getHttpClient(), $this->getHttpRequest());
        $tamperedRequest->initialize(array(
            'secret' => '0123456789ABCDEF0123456789ABCDEF0123456789ABCDEF0123456789ABCDEF',
            'authResult' => 'AUTHORISED',
            'pspReference' => '1211992213193029',
            'merchantReference' => 'Internet Order 12345',
            'skinCode' => '4aD37dJA',
            'merchantReturnData' => '',
            'merchantSig' => 'tamered-with-merchant-sig'
        ));
        $response = $tamperedRequest->send();
        $this->assertFalse($response->isSuccessful());
    }
}
