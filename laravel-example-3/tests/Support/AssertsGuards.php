<?php

namespace Tests\Support;

trait AssertsGuards
{
    /**
     * Asserts a route is not guarded.
     *
     * @param string $method
     * @param string $url
     * @param array $data
     * @param integer $guardedStatus
     * @return void
     */
    protected function assertNotGuarded(string $method, string $url, array $data = [], int $guardedStatus = 403)
    {
        $response = $this->$method($url, $data);

        return $this->assertNotEquals(
            intval($guardedStatus),
            intval($response->getStatusCode())
        );
    }

    /**
     * Asserts a route is guarded.
     *
     * @param string $method
     * @param string $url
     * @param array $data
     * @param integer $guardedStatus
     * @return void
     */
    protected function assertGuarded(string $method, string $url, array $data = [], int $guardedStatus = 403)
    {
        $response = $this->$method($url, $data);
        $response->assertStatus($guardedStatus);
    }
}
