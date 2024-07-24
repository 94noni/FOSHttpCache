<?php

/*
 * This file is part of the FOSHttpCache package.
 *
 * (c) FriendsOfSymfony <http://friendsofsymfony.github.com/>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace FOS\HttpCache\Tests\Unit\Test\Proxy;

use FOS\HttpCache\Test\Proxy\AbstractProxy;
use PHPUnit\Framework\TestCase;

class AbstractProxyTest extends TestCase
{
    public function testWaitTimeout(): void
    {
        $proxy = new ProxyPartial();
        $this->expectException(\RuntimeException::class);
        $this->expectExceptionMessage('Caching proxy still up at');
        $proxy->start();
    }

    public function testRunFailure(): void
    {
        $proxy = new ProxyPartial();
        try {
            $proxy->run();
            $this->fail('RuntimeException should have been thrown');
        } catch (\RuntimeException $e) {
            // there is some odd glitch with the exception message sometimes being empty.
            // when this happens, there will be a warning that the test did not make any assertions.
            $msg = $e->getMessage();
            if ($msg) {
                $this->assertStringContainsString('/path/to/not/exists', $msg);
            }
        }
    }
}

class ProxyPartial extends AbstractProxy
{
    public function start(): void
    {
        $this->waitUntil('localhost', 6666, 0);
    }

    public function stop(): void
    {
    }

    public function clear(): void
    {
    }

    public function run(): void
    {
        $this->runCommand('/path/to/not/exists', []);
    }
}
