<?php

namespace Mailer;

class FieldHttpTest extends HttpTest
{
    protected string $urlSegment = '/api/fields';

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
        $data = ['title' => 'Company', 'type' => 'string'];
        $code = $this->request($url, 'POST', $data);

        $this->assertEquals(200, $code);
    }

    public function testPut(): void
    {
        $url = $_ENV['APP_URL'] . $this->urlSegment . '/1';
        $data = ['title' => 'Institution', 'type' => 'string'];
        $code = $this->request($url, 'PUT', $data);

        $this->assertEquals(200, $code);
    }

    public function testPatch(): void
    {
        $url = $_ENV['APP_URL'] . $this->urlSegment . '/1';
        $data = ['title' => 'Company/Institution', 'type' => 'string'];
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