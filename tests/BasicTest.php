<?php

namespace Tests;

use LukePOLO\FakeRealAddresses\Generator;

class BasicTest extends TestCase
{
    /**
     * Makes sure we have a valid constructor
     */
    public function testConstruct()
    {
        $this->assertInstanceOf(
            Generator::class,
            $this->generator
        );
    }

    /**
     * Tests the acutal making of the addresses
     */
    public function testMake()
    {
        // We should only receive one address, which contains 5 fields
        $this->assertCount(5, $this->generator->make());

        // We should receive an array of addresses
        $this->assertCount(2, $this->generator->make(2));
    }

    /**
     * Tests a known location
     */
    public function testGetLocation()
    {
        $this->assertEquals([
            'street' => '1861 Monument Circle',
            'city' => 'Indianapolis',
            'province' => 'Indiana',
            'country' => 'US',
            'postal_code' => '46204',
        ], $this->generator->getLocation('39.768403', '-86.158068'));
    }

    /**
     * Testing that we remake a bad location
     */
    public function testRemake()
    {
        $this->assertCount(5, $this->generator->getLocation('312312319.768403', '-86.158068'));
    }

    /**
     * Test our random float generator
     */
    public function testRandomFloat()
    {
        $this->assertEquals(1, $this->invokeMethod($this->generator, 'randomFloat', [1, 1, 1]));

        // Test if we can get a decimal greater than 1.1 but less than 1.2
        $this->assertEquals(1.1, $this->invokeMethod($this->generator, 'randomFloat', [1, 1.1, 1.1]));

        $testNumber = $this->invokeMethod($this->generator, 'randomFloat', [5, 40, 90]);
        $this->assertLessThan(90, $testNumber);
        $this->assertGreaterThan(40, $testNumber);

        // Test if we have 6 dec
        $this->assertEquals(6, strlen(substr(strrchr($this->invokeMethod($this->generator, 'randomFloat', [6, 1, 100]), "."), 1)));
    }

    /**
     * Testing making a lat
     */
    public function testMakeLat()
    {
        $this->assertLessThan(90, $this->invokeMethod($this->generator, 'makeLat'));
        $this->assertGreaterThan(-90, $this->invokeMethod($this->generator, 'makeLat'));


        $testNumber = $this->invokeMethod($this->generator, 'makeLat', [40, 50]);
        $this->assertLessThan(50, $testNumber);
        $this->assertGreaterThan(40, $testNumber);
    }

    /**
     * Testing making a lng
     */
    public function testMakeLng()
    {
        $this->assertLessThan(180, $this->invokeMethod($this->generator, 'makeLng'));
        $this->assertGreaterThan(-180, $this->invokeMethod($this->generator, 'makeLng'));


        $testNumber = $this->invokeMethod($this->generator, 'makeLng', [40, 50]);
        $this->assertLessThan(50, $testNumber);
        $this->assertGreaterThan(40, $testNumber);
    }
}
