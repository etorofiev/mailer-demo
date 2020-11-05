<?php

namespace Mailer;

class SubscriberHttpTest extends HttpTest
{
    protected string $urlSegment = '/api/subscribers';

    public function testList(): void
    {
        $url = $_ENV['APP_URL'] . $this->urlSegment;
        $result = $this->request($url, 'GET');

        $this->assertEquals(200, $result['code']);
    }

    public function testListWithPageAndLimit(): void
    {
        $url = $_ENV['APP_URL'] . $this->urlSegment . '?limit=4&from=4';
        $result = $this->request($url, 'GET');

        $this->assertEquals(200, $result['code']);
        $this->assertCount(4, json_decode($result['data'], true));
    }

    public function testFind(): void
    {
        $url = $_ENV['APP_URL'] . $this->urlSegment . '/1';
        $result = $this->request($url, 'GET');

        $this->assertEquals(200, $result['code']);
        $this->assertNotEmpty(json_decode($result['data'], true));
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
        $data = ['name' => 'John Doe', 'state' => 'active', 'email' => 'janedoe@example.com'];
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