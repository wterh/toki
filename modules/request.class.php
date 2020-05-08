<?php
declare(strict_types=1);

namespace app\models;

/**
 * Class Request
 * @package models
 */
class Request
{
    private $userAgent;

    public function __construct($userAgent = false)
    {
        if ($userAgent) {
            $this->userAgent = trim(htmlspecialchars($userAgent));
        } else {
            $this->userAgent = 'Mozilla/5.0 (compatible; YandexBot/3.0; +http://yandex.com/bots)';
        }
    }

    public function clearHost($host)
    {
        $host = trim(htmlspecialchars($host));
        $search = ['http://', 'https://', '/', 'www.', ':443'];
        $replace = ['', '', '', '', ''];
        $host = str_replace($search, $replace, $host);
        $host = mb_strtolower($host);

        return $host;
    }

    public function getIp($host)
    {
        return gethostbyname($this->clearHost($host));
    }

    /**
     * analog get_headers()
     * @param $stringHeaders
     * @return array
     */
    public function normalizeHeaders($stringHeaders)
    {
        $stringHeaders = explode("\r\n\r\n", $stringHeaders);
        $stringHeaders = $stringHeaders[0];
        $stringHeaders = explode("\n", $stringHeaders);

        $headers = [];
        foreach ($stringHeaders as $key => $value) {
            if ($key == 0) {
                $headers[0] = trim($value);
            } else {
                $keys = explode(': ', $value);
                $headers[trim($keys[0])] = trim($keys[1]);
            }
        }

        if (isset($headers['location'])) {
            $headers['Location'] = $headers['location'];
            unset($headers['location']);
        }

        return $headers;
    }

    public function getHeaders($url, $follow = true, $data = false)
    {
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_USERAGENT, $this->userAgent);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, $follow);
        curl_setopt($ch, CURLOPT_HEADER, true);
        curl_setopt($ch, CURLOPT_NOBODY, true);

        if (!empty($data)) {
            curl_setopt($ch, CURLOPT_POST, true);
            curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($data));
        }

        $html = curl_exec($ch);
        curl_close($ch);

        return $html;
    }
}