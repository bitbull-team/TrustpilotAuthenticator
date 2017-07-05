<?php

namespace Trustpilot\Test;

use GuzzleHttp\Client as GuzzleClient;
use GuzzleHttp\ClientInterface as GuzzleClientInterface;
use GuzzleHttp\Exception\TransferException;
use GuzzleHttp\Psr7\Response;
use PHPUnit_Framework_MockObject_MockObject;
use Trustpilot\Api\Authenticator\AccessToken;
use Trustpilot\Api\Authenticator\Authenticator;
use Trustpilot\Api\Authenticator\AuthenticatorException;

class AuthenticatorTest extends \Codeception\Test\Unit
{
    const DUMMY_ENDPOINT = 'ENDPOINT';
    const API_KEY = '1234key';
    const API_SECRET = '6789secret';
    const USERNAME = 'username';
    const PASSWORD = 'password';

    /** @var GuzzleClientInterface|PHPUnit_Framework_MockObject_MockObject */
    private $guzzleMock;

    /** @var Authenticator */
    private $underTest;

    protected function _before()
    {
        $this->guzzleMock = $this->getMockBuilder(GuzzleClient::class)
            ->disableOriginalConstructor()
            ->getMock();

        $this->underTest = new Authenticator(
            self::API_KEY,
            self::API_SECRET,
            self::USERNAME,
            self::PASSWORD,
            $this->guzzleMock,
            self::DUMMY_ENDPOINT
        );
    }

    protected function _after()
    {
    }

    public function testGetAccessTokenGivesInstanceOfAccessToken()
    {
        $this->guzzleMock->expects($this->once())->method('request')->willReturn($this->stubResponse());

        $this->assertInstanceOf(AccessToken::class, $this->underTest->getAccessToken());
    }

    public function testGetAccessTokenContainCorrectValueTypes()
    {
        $this->guzzleMock->expects($this->once())->method('request')->willReturn($this->stubResponse());

        $result = $this->underTest->getAccessToken();

        $this->assertInstanceOf(\DateTimeImmutable::class, $result->getExpiry());
        $this->assertNull($result->getToken());
    }

    public function testGetAccessTokenThrowsAuthenticatorExceptionInCaseOfGuzzleException()
    {
        $this->guzzleMock->expects($this->once())->method('request')->willThrowException(new TransferException('mock_exception'));
        $this->expectException(AuthenticatorException::class);

        $this->underTest->getAccessToken();
    }

    private function stubResponse($status = 200, $header = [], $body = null)
    {
        return new Response($status, $header, $body);
    }
}
