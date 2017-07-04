<?php

namespace Trustpilot\Api\Authenticator;

use GuzzleHttp\Client as GuzzleClient;
use GuzzleHttp\ClientInterface as GuzzleClientInterface;
use GuzzleHttp\Exception\GuzzleException;

class Authenticator
{
    const ENDPOINT = 'https://api.trustpilot.com/v1/oauth/oauth-business-users-for-applications/';

    const AUTHENTICATE_ENDPOINT = 'https://authenticate.trustpilot.com';

    /** @var string */
    private $apiKey;

    /** @var string */
    private $apiSecret;

    /** @var string */
    private $username;

    /** @var string */
    private $password;

    /** @var GuzzleClientInterface */
    private $guzzle;

    /** @var string */
    private $endpoint;

    /** @var string */
    private $authEndpoint;

    /**
     * @param string $apiKey
     * @param string $apiSecret
     * @param string $username
     * @param string $password
     * @param GuzzleClientInterface $guzzle
     * @param string $endpoint
     * @param string $authEndpoint
     */
    public function __construct(
        $apiKey,
        $apiSecret,
        $username,
        $password,
        GuzzleClientInterface $guzzle = null,
        $endpoint = null,
        $authEndpoint = null
    ) {
        $this->apiKey = $apiKey;
        $this->apiSecret = $apiSecret;
        $this->username = $username;
        $this->password = $password;

        $this->guzzle = $guzzle ?: new GuzzleClient();
        $this->endpoint = $endpoint ?: self::ENDPOINT;
        $this->authEndpoint = $authEndpoint ?: self::AUTHENTICATE_ENDPOINT;
    }

    /**
     * @param string $endpointExtension
     *
     * @return AccessToken
     * @throws AuthenticatorException
     */
    public function getAccessToken($endpointExtension = 'accesstoken')
    {
        $form = [
            'grant_type' => 'password',
            'username'   => $this->username,
            'password'   => $this->password,
        ];

        $data = $this->callAuthenticatorAndGetBodyData($form, $endpointExtension);

        $token = $data['access_token'];
        $expiry = new \DateTime('@' . (time() + $data['expires_in']));

        return new AccessToken($token, $expiry);
    }

    private function callAuthenticatorAndGetBodyData($form, $endpointExtension)
    {
        try {
            $response = $this->guzzle->request(
                'POST',
                $this->endpoint . $endpointExtension,
                [
                    'auth' => [$this->apiKey, $this->apiSecret],
                    'form_params' => $form,
                ]
            );
        } catch (GuzzleException $e) {
            throw new AuthenticatorException($e->getMessage(), $e->getCode(), $e);
        }

        return json_decode((string) $response->getBody(), true);
    }
}
