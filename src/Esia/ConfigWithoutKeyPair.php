<?php

namespace Esia;

use Esia\Exceptions\InvalidConfigurationException;

class ConfigWithoutKeyPair
    extends Config
    implements ConfigInterface
{
    protected $privateKeyPath = '';
    protected $certPath = '';
    protected $privateKeyPassword = '';

    protected $tmpPath = '';

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

        $this->portalUrl = $config['portalUrl'] ?? $this->portalUrl;
        $this->tokenUrlPath = $config['tokenUrlPath'] ?? $this->tokenUrlPath;
        $this->codeUrlPath = $config['codeUrlPath'] ?? $this->codeUrlPath;
        $this->personUrlPath = $config['personUrlPath'] ?? $this->personUrlPath;
        $this->logoutUrlPath = $config['logoutUrlPath'] ?? $this->logoutUrlPath;
        $this->oid = $config['oid'] ?? $this->oid;
        $this->scope = $config['scope'] ?? $this->scope;
        if (!is_array($this->scope)) {
            throw new InvalidConfigurationException('scope must be array of strings');
        }

        $this->responseType = $config['responseType'] ?? $this->responseType;
        $this->accessType = $config['accessType'] ?? $this->accessType;
        $this->token = $config['token'] ?? $this->token;
        $this->version = $config['version'] ?? $this->version;
        $this->clientCertHash = $config['clientCertHash'] ?? $this->clientCertHash;
    }
}
