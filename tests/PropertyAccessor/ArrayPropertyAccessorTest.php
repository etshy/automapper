<?php


namespace Etshy\Tests\PropertyAccessor;

use Etshy\AutoMapper\Exception\SourceNotIterableException;
use Etshy\AutoMapper\PropertyAccessor\ArrayPropertyAccessor;
use PHPUnit\Framework\TestCase;
use stdClass;

class ArrayPropertyAccessorTest extends TestCase
{
    /**
     * @return array[]
     */
    public function sourceHasPropertyProvider(): array
    {
        return [
            ['prop', 'prop', true],
            ['prop', 'prop2', false],
        ];
    }

    /**
     * @dataProvider sourceHasPropertyProvider
     *
     * @param $sourcePropertyName
     * @param $propertyName
     * @param $expectedResult
     *
     * @return void
     */
    public function testSourceHasProperty(
        $sourcePropertyName,
        $propertyName,
        $expectedResult
    ): void {
        $accessor = new ArrayPropertyAccessor();
        $result = $accessor->sourceHasProperty([
            $sourcePropertyName => 'test',
        ], $propertyName);
        $this->assertEquals($expectedResult, $result);
    }

    /**
     * @return void
     * @throws SourceNotIterableException
     */
    public function testGetPropertiesName(): void
    {
        $accessor = new ArrayPropertyAccessor();

        $result = $accessor->getPropertiesName([
            'prop1' => 1,
            'prop2' => 1,
            'prop3' => 1,
        ]);

        $this->assertIsArray($result);
        $this->assertCount(3, $result);
        $this->assertEquals('prop1', $result[0]);
        $this->assertEquals('prop2', $result[1]);
        $this->assertEquals('prop3', $result[2]);
    }

    public function testGetPropertiesNameThrowException(): void
    {
        $accessor = new ArrayPropertyAccessor();

        $this->expectException(SourceNotIterableException::class);
        $accessor->getPropertiesName(new stdClass());
    }

    /**
     * @return array
     */
    public function getPropertyValueProvider(): array
    {
        return [
            ['prop', 'prop', 'test'],
            ['prop', 'prop2', null],
        ];
    }

    /**
     * @dataProvider getPropertyValueProvider
     *
     * @param $sourcePropertyName
     * @param $propertyName
     * @param $expectedResult
     *
     * @return void
     */
    public function testGetPropertyValue(
        $sourcePropertyName,
        $propertyName,
        $expectedResult
    ): void
    {
        $accessor = new ArrayPropertyAccessor();
        $result = $accessor->getPropertyValue([
            $sourcePropertyName => 'test'
        ], $propertyName);

        $this->assertEquals($expectedResult, $result);
    }
}
