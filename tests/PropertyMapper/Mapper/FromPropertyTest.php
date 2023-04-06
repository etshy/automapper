<?php


namespace Etshy\Tests\PropertyMapper\Mapper;

use Etshy\AutoMapper\PropertyMapper\Mapper\FromProperty;
use Etshy\Tests\Models\SimpleProperties\PrivateProperties;
use Etshy\Tests\Models\SimpleProperties\ProtectedProperties;
use PHPUnit\Framework\TestCase;

class FromPropertyTest extends TestCase
{

    public function testMapPropertyCantMap()
    {

        $fromProperty = new FromProperty('property');

        $source = new PrivateProperties();
        $destination = new ProtectedProperties();
        $fromProperty->mapProperty('property', $source, $destination);
        $this->assertNull($destination->getProp3());
    }
}
