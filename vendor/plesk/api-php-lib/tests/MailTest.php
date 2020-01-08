<?php
// Copyright 1999-2019. Plesk International GmbH.
namespace PleskXTest;

class MailTest extends TestCase
{
    /** @var \PleskX\Api\Struct\Webspace\Info */
    private static $webspace;

    /**
     * @var bool
     */
    private static $isMailSupported;

    public static function setUpBeforeClass()
    {
        parent::setUpBeforeClass();

        $serviceStates = static::$_client->server()->getServiceStates();
        static::$isMailSupported = isset($serviceStates['smtp']) && ('running' == $serviceStates['smtp']['state']);

        if (static::$isMailSupported) {
            static::$webspace = static::_createWebspace();
        }
    }

    protected function setUp()
    {
        parent::setUp();

        if (!static::$isMailSupported) {
            $this->markTestSkipped('Mail system is not supported.');
        }
    }

    public function testCreate()
    {
        $mailname = static::$_client->mail()->create('test', static::$webspace->id, true, 'secret');

        $this->assertIsInt($mailname->id);
        $this->assertGreaterThan(0, $mailname->id);
        $this->assertEquals('test', $mailname->name);

        static::$_client->mail()->delete('name', $mailname->name, static::$webspace->id);
    }

    public function testDelete()
    {
        $mailname = static::$_client->mail()->create('test', static::$webspace->id);

        $result = static::$_client->mail()->delete('name', $mailname->name, static::$webspace->id);
        $this->assertTrue($result);
    }
}
