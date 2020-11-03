<?php

namespace Mailer;

class SubscriberHttpTest extends HttpTest
{
    protected string $urlSegment = '/subscribers';

    public function testList(): void
    {
        $url = $_ENV['APP_URL'] . $this->urlSegment;
        $code = $this->request($url, 'GET');

        $this->assertEquals(200, $code);
    }

    public function testGet(): void
    {
        $url = $_ENV['APP_URL'] . $this->urlSegment . '/1';
        $code = $this->request($url, 'GET');

        $this->assertEquals(200, $code);
    }

    public function testPost(): void
    {
        $url = $_ENV['APP_URL'] . $this->urlSegment;
        $data = ['name' => 'John Doe', 'state' => 'active', 'email' => 'johndoe@example.com'];
        $code = $this->request($url, 'POST', $data);

        $this->assertEquals(200, $code);
    }

    public function testPut(): void
    {
        $url = $_ENV['APP_URL'] . $this->urlSegment . '/1';
        $data = ['name' => 'Jane Doe'];
        $code = $this->request($url, 'PUT', $data);

        $this->assertEquals(200, $code);
    }

    public function testDelete(): void
    {
        $url = $_ENV['APP_URL'] . $this->urlSegment . '/1';
        $code = $this->request($url, 'DELETE');

        $this->assertEquals(200, $code);
    }
}