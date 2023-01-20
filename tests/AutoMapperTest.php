<?php

declare(strict_types=1);

namespace Etshy\Tests;

use Etshy\AutoMapper\AutoMapper;
use Etshy\AutoMapper\Configuration\AutoMapperConfiguration;
use Etshy\Tests\Models\SimpleProperties\PrivateProperties;
use Etshy\Tests\Models\SimpleProperties\ProtectedProperties;
use Etshy\Tests\Models\SimpleProperties\PublicProperties;
use PHPUnit\Framework\TestCase;

/**
 * @group
 */
class AutoMapperTest extends TestCase
{
    private AutoMapperConfiguration $config;

    protected function setUp(): void
    {
        parent::setUp();
        $this->config = new AutoMapperConfiguration();
    }

    /**
     * @covers \Etshy\AutoMapper\AutoMapper
     * @return void
     */
    public function testMapPublicProperty(): void
    {
        $source = new PublicProperties();
        $source->prop = 'value';
        $this->config->registerMapping(PublicProperties::class, PublicProperties::class);
        $mapper = new AutoMapper($this->config);
        $mapped = $mapper->map($source, PublicProperties::class);
        $this->assertInstanceOf(PublicProperties::class, $mapped);
        $this->assertEquals($source->prop, $mapped->prop);
    }

    /**
     * @covers \Etshy\AutoMapper\AutoMapper
     * @return void
     */
    public function testMapProtectedProperty(): void
    {
        $source = new ProtectedProperties();
        $source->setProp1('value');
        $source->setProp2(12);
        $this->config->registerMapping(ProtectedProperties::class, ProtectedProperties::class);
        $mapper = new AutoMapper($this->config);
        /** @var ProtectedProperties $mapped */
        $mapped = $mapper->map($source, ProtectedProperties::class);
        $this->assertInstanceOf(ProtectedProperties::class, $mapped);
        $this->assertEquals($source->getProp1(), $mapped->getProp1());
        $this->assertEquals($source->getProp2(), $mapped->getProp2());
    }

    /**
     * @covers \Etshy\AutoMapper\AutoMapper
     * @return void
     */
    public function testMapPrivateProperty(): void
    {
        $source = new PrivateProperties();
        $source->setProp1('value');
        $source->setProp2(12);
        $this->config->registerMapping(PrivateProperties::class, PrivateProperties::class);
        $mapper = new AutoMapper($this->config);
        /** @var PrivateProperties $mapped */
        $mapped = $mapper->map($source, PrivateProperties::class);
        $this->assertInstanceOf(PrivateProperties::class, $mapped);
        $this->assertEquals($source->getProp1(), $mapped->getProp1());
        $this->assertEquals($source->getProp2(), $mapped->getProp2());
    }

    /**
     * @covers \Etshy\AutoMapper\AutoMapper
     * @return void
     */
    public function testMapToExistingObject(): void
    {
        $source = new PrivateProperties();
        $source->setProp1('value');
        $source->setProp2(12);
        $this->config->registerMapping(PrivateProperties::class, PrivateProperties::class);
        $mapper = new AutoMapper($this->config);
        /** @var PrivateProperties $mapped */
        $mapped = $mapper->mapToObject($source, new PrivateProperties());
        $this->assertInstanceOf(PrivateProperties::class, $mapped);
        $this->assertEquals($source->getProp1(), $mapped->getProp1());
        $this->assertEquals($source->getProp2(), $mapped->getProp2());
    }

    public function testMapWithSubClass()
    {
    }

    public function testMapWithCollectionOFSubObject()
    {
    }

    public function testMapWithNestedSubClass()
    {
    }

    public function testMapFromArrayToPublic()
    {
    }

    public function testMapFromArrayToProtected()
    {
    }

    public function testMapFromArrayToPrivate()
    {
    }

    public function testMapWithFromProperty()
    {
    }

    public function testMapWithFromCallable()
    {
    }

    public function testMapIgnore()
    {
    }

}
