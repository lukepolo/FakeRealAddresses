<?php

namespace Tests;

use LukePOLO\FakeRealAddresses\Generator;
use PHPUnit\Framework\TestCase as PHP_UNIT_TEST_CASE;

abstract class TestCase extends PHP_UNIT_TEST_CASE
{
    /** @var  Generator */
    public $generator;

    /**
     * Setup the test environment.
     *
     * @return void
     */
    protected function setUp()
    {
        $this->generator = new Generator();
    }

    /**
     * Call protected/private method of a class.
     *
     * @param object &$object    Instantiated object that we will run method on.
     * @param string $methodName Method name to call
     * @param array  $parameters Array of parameters to pass into method.
     *
     * @return mixed Method return.
     */
    public function invokeMethod(&$object, $methodName, array $parameters = array())
    {
        $reflection = new \ReflectionClass(get_class($object));
        $method = $reflection->getMethod($methodName);
        $method->setAccessible(true);

        return $method->invokeArgs($object, $parameters);
    }
}