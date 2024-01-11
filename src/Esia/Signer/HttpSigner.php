<?php

namespace Esia\Signer;

use JsonException;

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
        $data = json_encode(['text' => $message], JSON_THROW_ON_ERROR);

        $options = [
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_HEADER         => false,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_MAXREDIRS      => 10,
            CURLOPT_ENCODING       => "",
            CURLOPT_USERAGENT      => "spider",
            CURLOPT_AUTOREFERER    => true,
            CURLOPT_CONNECTTIMEOUT => 120,
            CURLOPT_TIMEOUT        => 120,
            CURLOPT_CUSTOMREQUEST  => $this->method,
            CURLOPT_POSTFIELDS     => $data,
            CURLOPT_HTTPHEADER     => $this->headers
        ];

        $ch = curl_init($this->signingServerUrl);
        curl_setopt_array($ch, $options);
        $content = curl_exec($ch);
        curl_close($ch);

        return $content;
    }
}
