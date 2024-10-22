<?php

declare(strict_types=1);

namespace Toobo\Tests;

use Toobo\Bcp47;

class Bcp47Test extends TestCase
{
    /**
     * @test
     * @dataProvider provideSplitData
     */
    public function testSplitTag(string $input, array $expected): void
    {
        $split = Bcp47::splitTag($input);

        foreach ($split as $key => $value) {
            if (array_key_exists($key, $expected)) {
                static::assertSame($expected[$key], $value);
                continue;
            }

            static::assertSame('', $value);
        }
    }

    /**
     * @test
     * @dataProvider provideGrandfathered
     */
    public function testSplitTagGrandfathered(string $input, string $expected): void
    {
        $subtags = Bcp47::splitTag($input);
        static::assertSame($expected, implode('-', array_filter($subtags)));
        static::assertSame('', $subtags[Bcp47::LANGUAGE_EXTENSION]);
        static::assertSame('', $subtags[Bcp47::SCRIPT]);
        static::assertSame('', $subtags[Bcp47::EXTENSION]);
        static::assertSame('', $subtags[Bcp47::PRIVATE_USE]);
    }

    /**
     * @test
     * @dataProvider provideGrandfathered
     */
    public function testFilterTagGrandfathered(string $input, string $expected): void
    {
        /** @var array<non-empty-string, string> $expected */
        static::assertSame($expected, Bcp47::filterTag($input));
    }

    /**
     * @test
     * @dataProvider provideSplitData
     */
    public function testFilterTag(string $input, array $expected): void
    {
        $actual = Bcp47::filterTag($input);
        if (array_filter($expected) === []) {
            static::assertNull($actual);

            return;
        }
        /** @var array<non-empty-string, string> $expected */
        static::assertSame(implode('-', array_filter($expected)), $actual);
    }

    /**
     * @test
     * @dataProvider provideSplitData
     */
    public function testIsValidTag(string $input, array $expected): void
    {
        static::assertSame(Bcp47::isValidTag($input), array_filter($expected) !== []);
    }

    /**
     * @test
     * @dataProvider provideRtl
     */
    public function testIsRtl(string $input, bool $expected): void
    {
        static::assertSame($expected, Bcp47::isRtl($input));
    }
}
