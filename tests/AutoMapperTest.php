<?php

declare(strict_types=1);

namespace Etshy\Tests;

use Etshy\AutoMapper\AutoMapper;
use Etshy\AutoMapper\Configuration\AutoMapperConfiguration;
use Etshy\AutoMapper\DataTypeEnum;
use Etshy\AutoMapper\Exception\MappingNotFoundException;
use Etshy\AutoMapper\Exception\SourceNotIterableException;
use Etshy\AutoMapper\Exception\UnknownSourceTypeException;
use Etshy\AutoMapper\PropertyMapper\PropertyMapper;
use Etshy\Tests\Models\ExtendingClass\FinalClass;
use Etshy\Tests\Models\SimpleProperties\PrivateProperties;
use Etshy\Tests\Models\SimpleProperties\ProtectedProperties;
use Etshy\Tests\Models\SimpleProperties\PublicProperties;
use Etshy\Tests\Models\SubClass\ChildClass;
use Etshy\Tests\Models\SubClass\ParentWithMultiChild;
use Etshy\Tests\Models\SubClass\ParentWithOneChild;
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
     * @return void
     * @throws MappingNotFoundException
     * @throws UnknownSourceTypeException
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
     * @return void
     * @throws MappingNotFoundException
     * @throws UnknownSourceTypeException
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
     * @return void
     * @throws MappingNotFoundException
     * @throws UnknownSourceTypeException
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
     * @return void
     * @throws MappingNotFoundException
     * @throws UnknownSourceTypeException
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

    /**
     * @return void
     * @throws MappingNotFoundException
     * @throws UnknownSourceTypeException
     */
    public function testMapWithSubClass(): void
    {
        $source = new ParentWithOneChild();
        $source->setChildClass($child = new ChildClass());
        $child->setField($expected = '123456');
        $this->config->registerMapping(ParentWithOneChild::class, ParentWithOneChild::class)
            ->forMember('childClass', PropertyMapper::toClass(ChildClass::class));
        $this->config->registerMapping(ChildClass::class, ChildClass::class);
        $mapper = new AutoMapper($this->config);
        /** @var ParentWithOneChild $mapped */
        $mapped = $mapper->map($source, ParentWithOneChild::class);
        $this->assertInstanceOf(ParentWithOneChild::class, $mapped);
        $this->assertInstanceOf(ChildClass::class, $mapped->getChildClass());
        $this->assertEquals($expected, $mapped->getChildClass()->getField());
    }

    /**
     * @return void
     * @throws MappingNotFoundException
     * @throws UnknownSourceTypeException
     */
    public function testMapWithCollectionOfSubObject(): void
    {
        $source = new ParentWithMultiChild();
        $source->setChildren([$child = new ChildClass()]);
        $child->setField($expected = '123456');
        $this->config->registerMapping(ParentWithMultiChild::class, ParentWithMultiChild::class)
            ->forMember('childClass', PropertyMapper::toClass(ChildClass::class));
        $this->config->registerMapping(ChildClass::class, ChildClass::class);
        $mapper = new AutoMapper($this->config);
        /** @var ParentWithOneChild $mapped */
        $mapped = $mapper->map($source, ParentWithMultiChild::class);
        $this->assertInstanceOf(ParentWithMultiChild::class, $mapped);
        $this->assertIsArray($mapped->getChildren());
        $this->assertContainsOnlyInstancesOf(ChildClass::class, $mapped->getChildren());
        $this->assertEquals($expected, $mapped->getChildren()[0]->getField());
    }

    /**
     * @return void
     * @throws MappingNotFoundException
     * @throws UnknownSourceTypeException
     */
    public function testMapWithParentClass(): void
    {
        $source = new FinalClass();
        $source->setFinalField('final');
        $source->setAbstractField('abstract');
        $source->setAbstractField2('abstract2');
        $this->config->registerMapping(FinalClass::class, FinalClass::class);
        $mapper = new AutoMapper($this->config);
        /** @var FinalClass $mapped */
        $mapped = $mapper->map($source, FinalClass::class);
        $this->assertInstanceOf(FinalClass::class, $mapped);
        $this->assertEquals($source->getAbstractField2(), $mapped->getAbstractField2());
        $this->assertEquals($source->getAbstractField(), $mapped->getAbstractField());
        $this->assertEquals($source->getFinalField(), $mapped->getFinalField());
    }

    /**
     * @return void
     * @throws MappingNotFoundException
     * @throws UnknownSourceTypeException
     */
    public function testMapFromArrayToPublic(): void
    {
        $source = [
            "prop" => "test",
        ];
        $this->config->registerMapping(DataTypeEnum::ARRAY, PublicProperties::class);
        $mapper = new AutoMapper($this->config);
        $mapped = $mapper->map($source, PublicProperties::class);
        $this->assertInstanceOf(PublicProperties::class, $mapped);
        $this->assertEquals("test", $mapped->prop);
    }

    /**
     * @return void
     * @throws MappingNotFoundException
     * @throws UnknownSourceTypeException
     */
    public function testMapFromArrayToProtected(): void
    {
        $source = [
            "prop1" => "test",
            "prop2" => 2,
        ];
        $this->config->registerMapping(DataTypeEnum::ARRAY, ProtectedProperties::class);
        $mapper = new AutoMapper($this->config);
        $mapped = $mapper->map($source, ProtectedProperties::class);
        $this->assertInstanceOf(ProtectedProperties::class, $mapped);
        $this->assertEquals("test", $mapped->getProp1());
        $this->assertEquals(2, $mapped->getProp2());
    }

    /**
     * @return void
     * @throws MappingNotFoundException
     * @throws UnknownSourceTypeException
     */
    public function testMapFromArrayToPrivate(): void
    {
        $source = [
            "prop1" => "test",
            "prop2" => 2,
        ];
        $this->config->registerMapping(DataTypeEnum::ARRAY, PrivateProperties::class);
        $mapper = new AutoMapper($this->config);
        $mapped = $mapper->map($source, PrivateProperties::class);
        $this->assertInstanceOf(PrivateProperties::class, $mapped);
        $this->assertEquals("test", $mapped->getProp1());
        $this->assertEquals(2, $mapped->getProp2());
    }

    /**
     * @return void
     * @throws MappingNotFoundException
     * @throws SourceNotIterableException
     * @throws UnknownSourceTypeException
     */
    public function testMapMultiple(): void
    {
        $source1 = new ProtectedProperties();
        $source1->setProp1('test0');
        $source2 = new ProtectedProperties();
        $source2->setProp1('test1');
        $source2->setProp2(2);
        $arraySource = [
            $source1,
            $source2,
        ];
        $this->config->registerMapping(ProtectedProperties::class, PrivateProperties::class);
        $mapper = new AutoMapper($this->config);
        $arrayMapped = $mapper->mapMultiple($arraySource, PrivateProperties::class);
        $this->assertContainsOnlyInstancesOf(PrivateProperties::class, $arrayMapped);
    }

    /**
     * @return void
     * @throws MappingNotFoundException
     * @throws UnknownSourceTypeException
     */
    public function testMapWithFromProperty(): void
    {
        $source = new ProtectedProperties();
        $source->setProp3(123456);
        $this->config->registerMapping(ProtectedProperties::class, PrivateProperties::class)
            ->forMember('prop2', PropertyMapper::fromProperty('prop3'));
        $mapper = new AutoMapper($this->config);
        $mapped = $mapper->map($source, PrivateProperties::class);
        $this->assertInstanceOf(PrivateProperties::class, $mapped);
        $this->assertEquals($source->getProp3(), $mapped->getProp2());
    }

    /**
     * @return void
     * @throws MappingNotFoundException
     * @throws UnknownSourceTypeException
     */
    public function testMapWithFromCallable(): void
    {
        $source = new ProtectedProperties();
        $source->setProp1('12');
        $this->config->registerMapping(ProtectedProperties::class, PrivateProperties::class)
            ->forMember(
                'prop2',
                PropertyMapper::fromCallable(static fn(ProtectedProperties $class) => (int)$class->getProp1())
            );
        $mapper = new AutoMapper($this->config);
        $mapped = $mapper->map($source, PrivateProperties::class);
        $this->assertInstanceOf(PrivateProperties::class, $mapped);
        $this->assertEquals((int)$source->getProp1(), $mapped->getProp2());
    }

    /**
     * @return void
     * @throws MappingNotFoundException
     * @throws UnknownSourceTypeException
     */
    public function testMapWithCallable(): void
    {
        $source = new ProtectedProperties();
        $source->setProp1('12');
        $this->config->registerMapping(ProtectedProperties::class, PrivateProperties::class)
            ->forMember(
                'prop2',
                static fn(ProtectedProperties $class) => (int)$class->getProp1()
            );
        $mapper = new AutoMapper($this->config);
        $mapped = $mapper->map($source, PrivateProperties::class);
        $this->assertInstanceOf(PrivateProperties::class, $mapped);
        $this->assertEquals((int)$source->getProp1(), $mapped->getProp2());
    }

    /**
     * @return void
     * @throws MappingNotFoundException
     * @throws UnknownSourceTypeException
     */
    public function testMapWithOptionIgnoreNullOnMapping(): void
    {
        $source = new ProtectedProperties();
        $source->setProp1('test1');

        $destination = new PrivateProperties();
        $destination->setProp2($expectedProp2 = 123);

        $mapping = $this->config->registerMapping(ProtectedProperties::class, PrivateProperties::class);
        $mapping->getOptions()->ignoreNullProperties();
        $mapper = new AutoMapper($this->config);
        $mapped = $mapper->mapToObject($source, $destination);
        $this->assertInstanceOf(PrivateProperties::class, $mapped);
        $this->assertEquals($source->getProp1(), $mapped->getProp1());
        $this->assertEquals($expectedProp2, $mapped->getProp2());
    }

    /**
     * @return void
     * @throws MappingNotFoundException
     * @throws UnknownSourceTypeException
     */
    public function testMapWithOptionIgnoreNullOnMember(): void
    {
        $source = new ProtectedProperties();
        $source->setProp1('test1');

        $destination = new PrivateProperties();
        $destination->setProp2($expectedProp2 = 123);

        $this->config->registerMapping(ProtectedProperties::class, PrivateProperties::class)
            ->forMember('prop2', $propertyMapper = PropertyMapper::fromProperty('prop2'));
        $propertyMapper->getOptions()->ignoreNullProperties();
        $mapper = new AutoMapper($this->config);
        $mapped = $mapper->mapToObject($source, $destination);
        $this->assertInstanceOf(PrivateProperties::class, $mapped);
        $this->assertEquals($source->getProp1(), $mapped->getProp1());
        $this->assertEquals($expectedProp2, $mapped->getProp2());
    }

    /**
     * @return void
     * @throws MappingNotFoundException
     * @throws UnknownSourceTypeException
     */
    public function testMapIgnore(): void
    {
        $source = new ProtectedProperties();
        $source->setProp1('test1');
        $this->config->registerMapping(ProtectedProperties::class, PrivateProperties::class)
            ->forMember('prop1', PropertyMapper::ignore());
        $mapper = new AutoMapper($this->config);
        $mapped = $mapper->map($source, PrivateProperties::class);
        $this->assertInstanceOf(PrivateProperties::class, $mapped);
        $this->assertEquals(null, $mapped->getProp1());
    }

    /**
     * @return void
     * @throws MappingNotFoundException
     * @throws UnknownSourceTypeException
     */
    public function testMappingNotFoundException(): void
    {
        $source = new PublicProperties();
        $mapper = new AutoMapper($this->config);

        $this->expectException(MappingNotFoundException::class);
        $mapper->map($source, PrivateProperties::class);
    }

    /**
     * @return void
     * @throws MappingNotFoundException
     * @throws SourceNotIterableException
     * @throws UnknownSourceTypeException
     */
    public function testSourceNotIterableException(): void
    {
        $source = new PublicProperties();
        $mapper = new AutoMapper($this->config);

        $this->expectException(SourceNotIterableException::class);
        $mapper->mapMultiple($source, PrivateProperties::class);
    }

    /**
     * @return void
     * @throws MappingNotFoundException
     * @throws UnknownSourceTypeException
     */
    public function testMapToObjectThrowException(): void
    {
        $source = new PublicProperties();
        $mapper = new AutoMapper($this->config);

        $this->expectException(MappingNotFoundException::class);
        $mapper->mapToObject($source, new ProtectedProperties());
    }
}
