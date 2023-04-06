<?php


namespace Etshy\Tests\PropertyAccessor;

use Etshy\AutoMapper\PropertyAccessor\ObjectPropertyAccessor;
use Etshy\Tests\Models\ExtendingClass\FinalClass;
use Etshy\Tests\Models\SimpleProperties\PrivateProperties;
use Etshy\Tests\Models\SimpleProperties\PublicProperties;
use PHPUnit\Framework\TestCase;

class ObjectPropertyAccessorTest extends TestCase
{

    public function testGetPropertyValue(): void
    {
        $accessor = new ObjectPropertyAccessor();

        $source = new PublicProperties();
        $source->prop = 'test';
        $result = $accessor->getPropertyValue($source, 'prop');
        $this->assertEquals($source->prop, $result);

        $source = new PrivateProperties();
        $source->setBoolProp(true);
        $result = $accessor->getPropertyValue($source, 'boolProp');
        $this->assertEquals($source->isBoolProp(), $result);

        $source = new PrivateProperties();
        $source->setBoolProp2(true);
        $result = $accessor->getPropertyValue($source, 'boolProp2');
        $this->assertEquals($source->hasBoolProp2(), $result);
    }

    public function testGetPropertiesName(): void
    {
        $accessor = new ObjectPropertyAccessor();

        $object = new FinalClass();

        $result = $accessor->getPropertiesName($object);

        $this->assertCount(3, $result);
        $this->assertContains('finalField', $result);
        $this->assertContains('abstractField2', $result);
        $this->assertContains('abstractField', $result);
    }

    public function testSourceHasProperty(): void
    {
        $accessor = new ObjectPropertyAccessor();

        $source = new PrivateProperties();
        $result = $accessor->hasProperty($source, 'prop1');
        $this->assertTrue($result);

        $result = $accessor->hasProperty($source, 'notExistingProp');
        $this->assertFalse($result);

        $source = new FinalClass();
        $result = $accessor->hasProperty($source, 'abstractField');
        $this->assertTrue($result);

        $source = new FinalClass();
        $result = $accessor->hasProperty($source, 'abstractFieldNotExisting');
        $this->assertFalse($result);
    }

    public function testDestinationHasProperty(): void
    {
        $accessor = new ObjectPropertyAccessor();

        $source = new PrivateProperties();
        $result = $accessor->hasProperty($source, 'prop1');
        $this->assertTrue($result);

        $result = $accessor->hasProperty($source, 'notExistingProp');
        $this->assertFalse($result);

        $source = new FinalClass();
        $result = $accessor->hasProperty($source, 'abstractField');
        $this->assertTrue($result);

        $source = new FinalClass();
        $result = $accessor->hasProperty($source, 'abstractFieldNotExisting');
        $this->assertFalse($result);
    }

    public function testSetPropertyValue(): void
    {
        $accessor = new ObjectPropertyAccessor();

        $destination = new PrivateProperties();
        $destination->setBoolProp2(true);
        $accessor->setPropertyValue($destination, 'prop1', 'test');
        $result = $accessor->getPropertyValue($destination, 'prop1');
        $this->assertEquals($destination->getProp1(), $result);
    }
}
