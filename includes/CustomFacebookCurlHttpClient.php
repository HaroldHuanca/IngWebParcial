<?php
namespace Facebook\HttpClients;

use Facebook\Http\GraphRawResponse;
use Facebook\Exceptions\FacebookSDKException;

/**
 * Class CustomFacebookCurlHttpClient
 * 
 * A custom HTTP client to override the hardcoded CAINFO path in the default SDK.
 */
class CustomFacebookCurlHttpClient extends FacebookCurlHttpClient
{
    /**
     * Opens a new curl connection.
     *
     * @param string $url     The endpoint to send the request to.
     * @param string $method  The request method.
     * @param string $body    The body of the request.
     * @param array  $headers The request headers.
     * @param int    $timeOut The timeout in seconds for the request.
     */
    public function openConnection($url, $method, $body, array $headers, $timeOut)
    {
        $options = [
            CURLOPT_CUSTOMREQUEST => $method,
            CURLOPT_HTTPHEADER => $this->compileRequestHeaders($headers),
            CURLOPT_URL => $url,
            CURLOPT_CONNECTTIMEOUT => 10,
            CURLOPT_TIMEOUT => $timeOut,
            CURLOPT_RETURNTRANSFER => true, // Follow 301 redirects
            CURLOPT_HEADER => true, // Enable header processing
            CURLOPT_SSL_VERIFYHOST => 2,
            CURLOPT_SSL_VERIFYPEER => true,
            // FIX: Point to our local cacert.pem in includes directory
            CURLOPT_CAINFO => __DIR__ . '/cacert.pem', 
        ];

        if ($method !== "GET") {
            $options[CURLOPT_POSTFIELDS] = $body;
        }

        $this->facebookCurl->init();
        $this->facebookCurl->setoptArray($options);
    }
}
