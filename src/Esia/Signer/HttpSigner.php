<?php

namespace Esia\Signer;

use Exception;
use JsonException;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\RequestException;

class HttpSigner
    extends AbstractSignerPKCS7
    implements SignerInterface
{
    /**
     * @param string $signingServerUrl
     * @param array $headers
     * @param string $method
     * @noinspection PhpMissingParentConstructorInspection
     */
    public function __construct(
        private string $signingServerUrl,
        private array $headers,
        private string $method,
    ) {
    }

    /**
     * @param string $message
     * @return string
     * @throws JsonException
     */
    public function sign(string $message): string
    {
        $client = new Client();

        try {
            $response = $client->request($this->method, $this->signingServerUrl, [
                'headers' => $this->headers,
                'json' => ['text' => $message]
            ]);

            if ($response->getStatusCode() != 200) {
                throw new Exception("Error: received HTTP code " . $response->getStatusCode());
            }

            return (string) $response->getBody();

        } catch (RequestException $e) {
            throw new Exception("Error: " . $e->getMessage());
        }
    }
}
