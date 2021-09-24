<?php

declare(strict_types=1);

namespace Tests\REST;

use Tests\TestCase;

class ExampleTest extends TestCase
{
    /**
     * A basic test example.
     *
     * @return void
     */
    public function testExample()
    {
        $this->post('/api/v1/example', [
            'íd' => 1,

        ]);

        $this->assertEquals(
            $this->app->version(),
            $this->response->getContent()
        );
    }
}
