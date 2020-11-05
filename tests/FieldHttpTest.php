<?php

namespace Mailer;

class FieldHttpTest extends HttpTest
{
    protected string $urlSegment = '/api/fields';

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
        $this->assertCount(4, json_decode($result['body'], true));
    }

    public function testFind(): void
    {
        $url = $_ENV['APP_URL'] . $this->urlSegment . '/1';
        $result = $this->request($url, 'GET');

        $this->assertEquals(200, $result['code']);
        $this->assertNotEmpty(json_decode($result['body'], true));
    }

    public function testPost(): void
    {
        $url = $_ENV['APP_URL'] . $this->urlSegment;
        $data = ['title' => 'Organization', 'type' => 'string'];
        $result = $this->request($url, 'POST', json_encode($data), ['Content-Type: application/json']);

        $this->assertEquals(200, $result['code']);
        $diff = array_diff_assoc($data, json_decode($result['body'], true)['data']);
        $this->assertEquals([], $diff);
    }

    /**
     * @depends testPost
     */
    public function testPut(): void
    {
        $url = $_ENV['APP_URL'] . $this->urlSegment . '/6';
        $data = ['title' => 'Institution', 'type' => 'string'];
        $result = $this->request($url, 'PUT', json_encode($data), ['Content-Type: application/json']);

        $this->assertEquals(200, $result['code']);
        $diff = array_diff_assoc($data, json_decode($result['body'], true)['data']);
        $this->assertEquals([], $diff);
    }

    /**
     * @depends testPut
     */
    public function testDelete(): void
    {
        $url = $_ENV['APP_URL'] . $this->urlSegment . '/6';
        $result = $this->request($url, 'DELETE');

        $this->assertEquals(200, $result['code']);
        $this->assertEquals(1, json_decode($result['body'], true)['affected']);
    }
}