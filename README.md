# Trustpilot Authenticator

[![Latest Stable Version](https://poser.pugx.org/itspirit/trustpilot-authenticator/v/stable)](https://packagist.org/packages/itspirit/trustpilot-authenticator)
[![Total Downloads](https://poser.pugx.org/itspirit/trustpilot-authenticator/downloads)](https://packagist.org/packages/itspirit/trustpilot-authenticator)
[![License](https://poser.pugx.org/itspirit/trustpilot-authenticator/license)](https://packagist.org/packages/itspirit/trustpilot-authenticator)

A PHP library for obtaining [Trustpilot Business User API](https://developers.trustpilot.com/authentication) access tokens.

## Install

Install using [composer](https://getcomposer.org/):

```sh
composer install itspirit/trustpilot-authenticator
```

## Usage

```php
$authenticator = new Trustpilot\Api\Authenticator\Authenticator();

$accessToken = $authenticator->getAccessToken($apiKey, $apiToken, $username, $password);

// $accessToken->getToken(): string
// $accessToken->hasExpired(): bool
// $accessToken->getExpiry(): \DateTimeImmutable
// $accessToken->serialize(): string
```

## Tests

This package use Codeception for testing.
To run the tests just type

```sh
vendor/bin/codecept run
```
