<?php

namespace Omnipay\Adyen\Message;

use Omnipay\Tests\TestCase;

class CompletePurchaseResponseTest extends TestCase
{
    public function testCompletePurchaseSuccess()
    {
        $response = new CompletePurchaseResponse(
            $this->getMockRequest(),
            array(
                'success' => true,
                'allParams' => array(
                    'merchantSig' => 'YRTyF4SIdrW2mKIbNukCTkZ21dHCzcQYOevrBII+yUI='
                ),
                'responseSignature' => 'YRTyF4SIdrW2mKIbNukCTkZ21dHCzcQYOevrBII+yUI='
            )
        );

        $this->assertTrue($response->isSuccessful());
    }

    public function testCompletePurchaseFailure()
    {
        $response = new CompletePurchaseResponse(
            $this->getMockRequest(),
            array(
                'success' => false,
                'allParams' => array(
                    'merchantSig' => 'YRTyF4SIdrW2mKIbNukCTkZ21dHCzcQYOevrBII+yUI='
                ),
                'responseSignature' => 'YRTyF4SIdrW2mKIbNukCTkZ21dHCzcQYOevrBII+yUI='
            )
        );

        $this->assertFalse($response->isSuccessful());
    }

    public function testNonMatchingSignature()
    {
        $response = new CompletePurchaseResponse(
            $this->getMockRequest(),
            array(
                'success' => true,
                'allParams' => array(
                    'merchantSig' => 'YRTyF4SIdrW2mKIbNukCTkZ21dHCzcQYOevrBII+yUI='
                ),
                'responseSignature' => 'ABCdE1FGhij2kLMnOpqRStU34vWXyzABCdefGHI+jKL='
            )
        );

        $this->assertFalse($response->isSuccessful());
    }

    public function testIsSuccessful()
    {
        $response = new CompletePurchaseResponse(
            $this->getMockRequest(),
            array(
                'success' => true,
                'allParams' => array(
                    'merchantSig' => 'YRTyF4SIdrW2mKIbNukCTkZ21dHCzcQYOevrBII+yUI='
                ),
                'responseSignature' => 'YRTyF4SIdrW2mKIbNukCTkZ21dHCzcQYOevrBII+yUI='
            )
        );

        $this->assertTrue($response->isSuccessful());
    }

    public function testIsCancelled()
    {
        $response = new CompletePurchaseResponse(
            $this->getMockRequest(),
            array(
                'success' => false,
                'authResult' => 'CANCELLED',
            )
        );
        $this->assertTrue($response->isCancelled());
    }

    public function testIsNotCancelled()
    {
        $response = new CompletePurchaseResponse(
            $this->getMockRequest(),
            array(
                'success' => true,
                'allParams' => array(
                    'merchantSig' => 'YRTyF4SIdrW2mKIbNukCTkZ21dHCzcQYOevrBII+yUI='
                ),
                'responseSignature' => 'YRTyF4SIdrW2mKIbNukCTkZ21dHCzcQYOevrBII+yUI='
            )
        );

        $this->assertFalse($response->isCancelled());
    }

    public function testIsPending()
    {
        $response = new CompletePurchaseResponse(
            $this->getMockRequest(),
            array(
                'success' => false,
                'authResult' => 'PENDING',
            )
        );
        $this->assertTrue($response->isPending());
    }

    public function testIsNotPending()
    {
        $response = new CompletePurchaseResponse(
            $this->getMockRequest(),
            array(
                'success' => true,
                'allParams' => array(
                    'merchantSig' => 'YRTyF4SIdrW2mKIbNukCTkZ21dHCzcQYOevrBII+yUI='
                ),
                'responseSignature' => 'YRTyF4SIdrW2mKIbNukCTkZ21dHCzcQYOevrBII+yUI='
            )
        );

        $this->assertFalse($response->isPending());
    }

    public function testGetResponse()
    {
        $mock = $this->getMockRequest();
        $response = new CompletePurchaseResponse($mock, array());
        $this->assertSame(serialize($response), serialize($response->getResponse()));
    }
}
