<?php

declare(strict_types=1);

namespace Toobo\Tests;

use Toobo\Bcp47;
use Toobo\Bcp47Tag;
use Wikimedia\Bcp47Code\Bcp47Code;

class Bcp47TagTest extends TestCase
{
    /**
     * @return void
     */
    public function testSameCodeAsForBcp47Tag(): void
    {
        static::assertTrue(Bcp47Tag::new('EN-Latn-us')->isSameCodeAs(Bcp47Tag::new('en-US')));
    }

    /**
     * @return void
     */
    public function testSameCodeAsForInterface(): void
    {
        $value = new class () implements Bcp47Code
        {
            public function toBcp47Code(): string
            {
                return 'en-US';
            }

            public function isSameCodeAs(Bcp47Code $other): bool
            {
                return false;
            }
        };

        static::assertTrue(Bcp47Tag::new('EN-Latn-us')->isSameCodeAs($value));
    }

    /**
     * @dataProvider provideSplitData
     */
    public function testJsonEncoding(string $input, array $expected): void
    {
        $empty = [
            Bcp47::GRANDFATHERED => '',
            Bcp47::LANGUAGE => '',
            Bcp47::LANGUAGE_EXTENSION => '',
            Bcp47::SCRIPT => '',
            Bcp47::REGION => '',
            Bcp47::VARIANT => '',
            Bcp47::EXTENSION => '',
            Bcp47::PRIVATE_USE => '',
        ];

        if ($expected === $empty) {
            $this->expectExceptionMessageMatches('/not.+?valid/i');
            Bcp47Tag::new($input);

            return;
        }

        $tag = Bcp47Tag::new($input);
        static::assertSame(json_encode(array_filter($expected)), json_encode($tag));
    }

    /**
     * @dataProvider provideSplitData
     */
    public function testToString(string $input, array $expected): void
    {
        $empty = [
            Bcp47::GRANDFATHERED => '',
            Bcp47::LANGUAGE => '',
            Bcp47::LANGUAGE_EXTENSION => '',
            Bcp47::SCRIPT => '',
            Bcp47::REGION => '',
            Bcp47::VARIANT => '',
            Bcp47::EXTENSION => '',
            Bcp47::PRIVATE_USE => '',
        ];

        if ($expected === $empty) {
            $this->expectExceptionMessageMatches('/not.+?valid/i');
            Bcp47Tag::new($input);

            return;
        }

        $tag = Bcp47Tag::new($input);
        /** @var array<non-empty-string, non-empty-string> $expected */
        static::assertSame(implode('-', array_filter($expected)), (string) $tag);
    }

    /**
     * @dataProvider provideSplitData
     */
    public function testGetters(string $input, array $expected): void
    {
        $empty = [
            Bcp47::GRANDFATHERED => '',
            Bcp47::LANGUAGE => '',
            Bcp47::LANGUAGE_EXTENSION => '',
            Bcp47::SCRIPT => '',
            Bcp47::REGION => '',
            Bcp47::VARIANT => '',
            Bcp47::EXTENSION => '',
            Bcp47::PRIVATE_USE => '',
        ];

        if ($expected === $empty) {
            $this->expectExceptionMessageMatches('/not.+?valid/i');
            Bcp47Tag::new($input);

            return;
        }

        $tag = Bcp47Tag::new($input);

        foreach (array_keys($empty) as $key) {
            if ($key === Bcp47::GRANDFATHERED) {
                continue;
            }

            /** @var string|null $expectedVal */
            $expectedVal = $expected[$key] ?? null;
            ($expectedVal === '') and $expectedVal = null;

            static::assertSame($expectedVal, $tag->{$key}());
        }
    }

    /**
     * @dataProvider provideGrandfathered
     */
    public function testIsDeprecated(string $input, string $expected): void
    {
        static::assertSame($input === $expected, Bcp47Tag::new($input)->isDeprecated());
    }

    /**
     * @return void
     */
    public function testLanguageForDeprecated(): void
    {
        static::assertSame('und', Bcp47Tag::new('i-default')->language());
        static::assertSame('enochian', Bcp47Tag::new('i-enochian')->language());
        static::assertSame('mingo', Bcp47Tag::new('i-mingo')->language());
        static::assertSame('gaulish', Bcp47Tag::new('cel-gaulish')->language());
        static::assertSame('zh', Bcp47Tag::new('zh-min')->language());
        static::assertSame('hak', Bcp47Tag::new('zh-hakka')->language());
    }

    /**
     * @dataProvider provideRtl
     */
    public function testIsRtl(string $input, bool $expected): void
    {
        if (!Bcp47::isValidTag($input)) {
            $this->expectExceptionMessageMatches('/not.+?valid/i');
        }

        static::assertSame($expected, Bcp47Tag::new($input)->isRtl());
    }
}
