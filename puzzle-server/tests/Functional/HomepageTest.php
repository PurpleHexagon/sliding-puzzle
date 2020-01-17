<?php

namespace Tests\Functional;

class HomepageTest extends BaseTestCase
{
    /**
     * Test that start puzzle route returns a 200
     */
    public function testGetStartPuzzle()
    {
        $response = $this->runApp('GET', '/start-puzzle');

        $this->assertEquals(200, $response->getStatusCode());
    }

    /**
     * Test that the start puzzle route won't accept a post request
     */
    public function testPostStartPuzzleNotAllowed()
    {
        $response = $this->runApp('POST', '/start-puzzle', ['test']);

        $this->assertEquals(404, $response->getStatusCode());
    }
}