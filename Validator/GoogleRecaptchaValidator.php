<?php

namespace GoogleRecaptchaBundle\Validator;

use GuzzleHttp\Client;
use Psr\Log\LoggerInterface;
use Psr\Log\NullLogger;
use Symfony\Component\HttpFoundation\RequestStack;

/**
 * Class GoogleRecaptchaClient
 *
 * @author "Emmanuel BALLERY" <emmanuel.ballery@gmail.com>
 */
class GoogleRecaptchaValidator
{
    /**
     * @var string
     */
    private $apiEndpoint;

    /**
     * @var string
     */
    private $secretKey;

    /**
     * @var RequestStack
     */
    private $requestStack;

    /**
     * @var Client
     */
    private $client;

    /**
     * @var LoggerInterface
     */
    private $logger;

    /**
     * @param string               $apiEndpoint  Api endpoint
     * @param string               $secretKey    Secret key
     * @param RequestStack         $requestStack Request stack
     * @param Client               $client       Client
     * @param null|LoggerInterface $logger       Logger
     */
    public function __construct(
        $apiEndpoint,
        $secretKey,
        RequestStack $requestStack,
        Client $client,
        LoggerInterface $logger = null
    ) {
        $this->apiEndpoint = $apiEndpoint;
        $this->secretKey = $secretKey;
        $this->requestStack = $requestStack;
        $this->client = $client;
        $this->logger = $logger ?: new NullLogger();
    }

    /**
     * Validate
     *
     * @param string $response
     *
     * @return bool
     */
    public function validate($response)
    {
        if (empty($response) || !is_string($response)) {
            return false;
        }

        try {
            $this->logger->debug('validating response', [
                'response' => $response,
            ]);

            $res = $this->client->post($this->apiEndpoint, [
                'form_params' => [
                    'secret' => $this->secretKey,
                    'response' => $response,
                    'remoteip' => $this->requestStack->getCurrentRequest()->getClientIp(),
                ],
            ]);

            if (200 === $res->getStatusCode()) {
                $body = $res->getBody()->getContents();

                if (null !== $json = json_decode($body, true)) {
                    if ($json['success']) {
                        return true;
                    }

                    $this->logger->debug('google says this response is not valid', [
                        'google_response' => $json,
                    ]);
                } else {
                    $this->logger->error('google response cannot be decoded', [
                        'google_body' => $body,
                    ]);
                }
            } else {
                $this->logger->error('google sent an invalid status code', [
                    'google_code' => $res->getStatusCode(),
                ]);
            }
        } catch (\Exception $e) {
            $this->logger->error('exception while validating', [
                'exception_code' => $e->getCode(),
                'exception_message' => $e->getMessage(),
                'exception_file' => $e->getFile(),
                'exception_line' => $e->getLine(),
            ]);
        }

        return false;
    }
}
