<?php

namespace Esia;

use Esia\Exceptions\InvalidConfigurationException;

class Config implements ConfigInterface
{
    protected $clientId;
    protected $redirectUrl;
    protected $privateKeyPath;
    protected $certPath;

    protected $portalUrl = 'http://esia-portal1.test.gosuslugi.ru/';
    protected $tokenUrlPath = 'aas/oauth2/te';
    protected $codeUrlPath = 'aas/oauth2/ac';
    protected $personUrlPath = 'rs/prns';
    protected $logoutUrlPath = 'idp/ext/Logout';
    protected $privateKeyPassword = '';

    protected $scope = [
        'fullname',
        'birthdate',
        'gender',
        'email',
        'mobile',
        'id_doc',
        'snils',
        'inn',
    ];

    protected $tmpPath = '/var/tmp';

    protected $responseType = 'code';
    protected $accessType = 'offline';

    protected $token = '';
    protected $oid = '';
    protected $version = 1;
    protected ?string $clientCertHash = null;

    /**
     * Config constructor.
     *
     * @throws InvalidConfigurationException
     */
    public function __construct(array $config = [])
    {
        // Required params
        $this->clientId = $config['clientId'] ?? $this->clientId;
        if (!$this->clientId) {
            throw new InvalidConfigurationException('Please provide clientId');
        }

        $this->redirectUrl = $config['redirectUrl'] ?? $this->redirectUrl;
        if (!$this->redirectUrl) {
            throw new InvalidConfigurationException('Please provide redirectUrl');
        }

        $this->privateKeyPath = $config['privateKeyPath'] ?? $this->privateKeyPath;
        if (!$this->privateKeyPath) {
            throw new InvalidConfigurationException('Please provide privateKeyPath');
        }
        $this->certPath = $config['certPath'] ?? $this->certPath;
        if (!$this->certPath) {
            throw new InvalidConfigurationException('Please provide certPath');
        }

        $this->portalUrl = $config['portalUrl'] ?? $this->portalUrl;
        $this->tokenUrlPath = $config['tokenUrlPath'] ?? $this->tokenUrlPath;
        $this->codeUrlPath = $config['codeUrlPath'] ?? $this->codeUrlPath;
        $this->personUrlPath = $config['personUrlPath'] ?? $this->personUrlPath;
        $this->logoutUrlPath = $config['logoutUrlPath'] ?? $this->logoutUrlPath;
        $this->privateKeyPassword = $config['privateKeyPassword'] ?? $this->privateKeyPassword;
        $this->oid = $config['oid'] ?? $this->oid;
        $this->scope = $config['scope'] ?? $this->scope;
        if (!is_array($this->scope)) {
            throw new InvalidConfigurationException('scope must be array of strings');
        }

        $this->responseType = $config['responseType'] ?? $this->responseType;
        $this->accessType = $config['accessType'] ?? $this->accessType;
        $this->tmpPath = $config['tmpPath'] ?? $this->tmpPath;
        $this->token = $config['token'] ?? $this->token;
        $this->version = $config['version'] ?? $this->version;
        $this->clientCertHash = $config['clientCertHash'] ?? $this->clientCertHash;
    }

    public function getPortalUrl(): string
    {
        return $this->portalUrl;
    }

    public function getPrivateKeyPath(): string
    {
        return $this->privateKeyPath;
    }

    public function getPrivateKeyPassword(): string
    {
        return $this->privateKeyPassword;
    }

    public function getCertPath(): string
    {
        return $this->certPath;
    }
    
    public function getOid(): string
    {
        return $this->oid;
    }

    public function setOid(string $oid): void
    {
        $this->oid = $oid;
    }

    public function getScope(): array
    {
        return $this->scope;
    }

    public function getScopeString(): string
    {
        return implode(' ', $this->scope);
    }

    public function getResponseType(): string
    {
        return $this->responseType;
    }

    public function getAccessType(): string
    {
        return $this->accessType;
    }

    public function getTmpPath(): string
    {
        return $this->tmpPath;
    }

    public function getToken(): ?string
    {
        return $this->token;
    }

    public function setToken(string $token): void
    {
        $this->token = $token;
    }

    public function getClientId(): string
    {
        return $this->clientId;
    }

    public function getRedirectUrl(): string
    {
        return $this->redirectUrl;
    }

    /**
     * Return an url for request to get an access token
     */
    public function getTokenUrl(): string
    {
        return $this->portalUrl . $this->tokenUrlPath;
    }

    /**
     * Return an url for request to get an authorization code
     */
    public function getCodeUrl(): string
    {
        return $this->portalUrl . $this->codeUrlPath;
    }

    /**
     * @return string
     * @throws InvalidConfigurationException
     */
    public function getPersonUrl(): string
    {
        if (!$this->oid) {
            throw new InvalidConfigurationException('Please provide oid');
        }
        return $this->portalUrl . $this->personUrlPath . '/' . $this->oid;
    }

    /**
     * Return an url for logout
     */
    public function getLogoutUrl(): string
    {
        return $this->portalUrl . $this->logoutUrlPath;
    }

    public function isVersionV2(): bool
    {
        return $this->version === 2;
    }

    public function getClientCertHash(): ?string
    {
        return $this->clientCertHash;
    }
}
