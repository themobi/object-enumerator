<?php
/*
 * This file is part of Object Enumerator.
 *
 * (c) Sebastian Bergmann <sebastian@phpunit.de>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace SebastianBergmann\ObjectEnumerator;

/**
 * @covers SebastianBergmann\ObjectEnumerator\Enumerator
 */
class EnumeratorTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var Enumerator
     */
    private $enumerator;

    protected function setUp()
    {
        $this->enumerator = new Enumerator;
    }

    public function testEnumeratesSingleObject()
    {
        $a = new \StdClass;

        $objects = $this->enumerator->enumerate($a);

        $this->assertCount(1, $objects);
        $this->assertSame($a, $objects[0]);
    }

    public function testEnumeratesArrayWithSingleObject()
    {
        $a = new \StdClass;

        $objects = $this->enumerator->enumerate([$a]);

        $this->assertCount(1, $objects);
        $this->assertSame($a, $objects[0]);
    }

    public function testEnumeratesArrayWithTwoReferencesToTheSameObject()
    {
        $a = new \StdClass;

        $objects = $this->enumerator->enumerate([$a, $a]);

        $this->assertCount(1, $objects);
        $this->assertSame($a, $objects[0]);
    }

    public function testEnumeratesArrayOfObjects()
    {
        $a = new \StdClass;
        $b = new \StdClass;

        $objects = $this->enumerator->enumerate([$a, $b]);

        $this->assertCount(2, $objects);
        $this->assertSame($a, $objects[0]);
        $this->assertSame($b, $objects[1]);
    }

    public function testEnumeratesObjectWithAggregatedObject()
    {
        $a = new \StdClass;
        $b = new \StdClass;

        $a->b = $b;
        $a->c = null;

        $objects = $this->enumerator->enumerate($a);

        $this->assertCount(2, $objects);
        $this->assertSame($a, $objects[0]);
        $this->assertSame($b, $objects[1]);
    }

    public function testEnumeratesObjectWithAggregatedObjectsInArray()
    {
        $a = new \StdClass;
        $b = new \StdClass;

        $a->b = [$b];

        $objects = $this->enumerator->enumerate($a);

        $this->assertCount(2, $objects);
        $this->assertSame($a, $objects[0]);
        $this->assertSame($b, $objects[1]);
    }

    public function testExceptionIsRaisedForInvalidArgument()
    {
        $this->setExpectedException(InvalidArgumentException::class);

        $this->enumerator->enumerate(null);
    }
}