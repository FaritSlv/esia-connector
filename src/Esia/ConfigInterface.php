<?php

namespace Esia;

use Esia\Exceptions\InvalidConfigurationException;

interface ConfigInterface
{

    public function getPortalUrl(): string;

    public function getPrivateKeyPath(): string;

    public function getPrivateKeyPassword(): string;

    public function getCertPath(): string;

    public function getOid(): string;

    public function setOid(string $oid): void;

    public function getScope(): array;

    public function getScopeString(): string;

    public function getResponseType(): string;

    public function getAccessType(): string;

    public function getTmpPath(): string;

    public function getToken(): ?string;

    public function setToken(string $token): void;

    public function getClientId(): string;

    public function getRedirectUrl(): string;

    /**
     * Return an url for request to get an access token
     */
    public function getTokenUrl(): string;

    /**
     * Return an url for request to get an authorization code
     */
    public function getCodeUrl(): string;

    /**
     * @return string
     * @throws InvalidConfigurationException
     */
    public function getPersonUrl(): string;

    /**
     * Return an url for logout
     */
    public function getLogoutUrl(): string;

    /**
     * check version v2
     */
    public function isVersionV2(): bool;

    /**
     * Return on client cert hash
     */
    public function getClientCertHash(): ?string;
}
