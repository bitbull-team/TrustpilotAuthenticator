<?php

namespace Trustpilot\Api\Authenticator;

class AccessToken implements \Serializable
{
    /** @var string */
    private $token;

    /** @var \DateTimeImmutable */
    private $expiry;

    /**
     * @param string $token
     * @param \DateTimeInterface $expiry
     */
    public function __construct($token, \DateTimeInterface $expiry)
    {
        $this->token = $token;

        if ($expiry instanceof \DateTimeImmutable) {
            $this->expiry = $expiry;
        } else {
            $this->expiry = new \DateTimeImmutable('@' .$expiry->getTimestamp());
        }
    }

    /**
     * {@inheritdoc}
     */
    public function serialize()
    {
        return serialize([
            'token'  => $this->token,
            'expiry' => $this->expiry,
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function unserialize($serialized)
    {
        list($this->token, $this->expiry) = unserialize($serialized, ['allowed_classes' => [\DateTimeImmutable::class]]);
    }

    /**
     * @return bool
     */
    public function hasExpired()
    {
        return $this->expiry->getTimestamp() < time();
    }

    /**
     * @return string
     */
    public function getToken()
    {
        return $this->token;
    }

    /**
     * @return \DateTimeImmutable
     */
    public function getExpiry()
    {
        return $this->expiry;
    }   

    public function __serialize(): array
    {
        return $this->serialize();
    }

    public function __unserialize(array $data): void
    {
        $this->unserialize($data);
    }
}
