<?php


namespace Etshy\Tests\PropertyMapper;

use Etshy\AutoMapper\PropertyMapper\DefaultPropertyMapper;
use Etshy\Tests\Models\SimpleProperties\PublicProperties;
use PHPUnit\Framework\TestCase;

class DefaultPropertyMapperTest extends TestCase
{
    private DefaultPropertyMapper $propertyMapper;

    protected function setUp(): void
    {
        $this->propertyMapper = new DefaultPropertyMapper();
    }

    public function testMapPropertyWorks(): void
    {
        $destination = new PublicProperties();
        $source = new PublicProperties();
        $source->prop = 'test_test';
        $this->propertyMapper->mapProperty('prop', $source, $destination);
        $this->assertEquals($destination->prop, $destination->prop);
    }
}
