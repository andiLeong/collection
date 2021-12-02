<?php

namespace Tests;

use Andileong\Collection\Config;
use PHPUnit\Framework\TestCase;

class ConfigTest extends testcase
{

    private Config $config;

    public function setUp() :void
    {
        parent::setUp();
        $this->config = new Config();
    }

    /** @test */
    public function it_can_fetch_a_configuration_array_with_a_giving_path()
    {
        $files = $this->config->all();
        $this->assertIsArray($files);
    }

    /** @test */
    public function it_can_get_a_configuration_value_from_config()
    {
        $token = $this->config->get('services.postmark.token');
        $this->assertEquals('example',$token);
    }

    /** @test */
    public function it_can_provide_a_default_config_value()
    {
        $token = $this->config->get('services.postmark.token.saddas','default');
        $this->assertEquals('default',$token);
    }

}