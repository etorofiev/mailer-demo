<?php

namespace Mailer;

use Dotenv\Dotenv;
use PHPUnit\Framework\TestCase;

abstract class HttpTest extends TestCase
{
    protected string $urlSegment;

    abstract public function testList();
    abstract public function testFind();
    abstract public function testPost();
    abstract public function testPut(int $id);
    abstract public function testDelete(int $id);

    public static function setUpBeforeClass(): void
    {
        $dotenv = Dotenv::createImmutable(dirname(__DIR__));
        $dotenv->load();
        $dotenv->required(['APP_URL']);
    }

    protected function request(string $url, string $method, $data = null, array $headers = []): array
    {
        // Normally using guzzlehttp is a better idea, but a simple curl is enough here
        $handle = curl_init($url);
        curl_setopt($handle, CURLOPT_URL, $url);
        curl_setopt($handle, CURLOPT_CUSTOMREQUEST, $method);
        curl_setopt($handle, CURLOPT_RETURNTRANSFER, TRUE);

        switch($method) {
            case 'POST':
                curl_setopt($handle, CURLOPT_POST, 1);
                break;
            case 'GET':
                break;
            default:
                curl_setopt($handle, CURLOPT_CUSTOMREQUEST, $method);
        }

        if (!is_null($data)) {
            curl_setopt($handle, CURLOPT_POSTFIELDS, $data);
        }
        if (!empty($headers)) {
            curl_setopt($handle, CURLOPT_HTTPHEADER, $headers);
        }

        $result = curl_exec($handle);
        $httpCode = curl_getinfo($handle, CURLINFO_HTTP_CODE);
        curl_close($handle);

        return ['body' => $result, 'code' => $httpCode];
    }
}