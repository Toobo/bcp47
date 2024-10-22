<?php

/*
 * This file is part of the "BCP 47" package.
 *
 * Copyright (c) Giuseppe Mazzapica <giuseppe.mazzapica@gmail.com>
 *
 * This program is free software; you can redistribute it and/or
 * modify it under the terms of the GNU General Public License
 * as published by the Free Software Foundation; either version 2
 * of the License, or (at your option) any later version.

 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.

 * You should have received a copy of the GNU General Public License
 * along with this program; if not, write to the Free Software
 * Foundation, Inc., 51 Franklin Street, Fifth Floor, Boston, MA  02110-1301, USA.
 */

declare(strict_types=1);

namespace Toobo\Tests;

use Toobo\Bcp47;

abstract class TestCase extends \PHPUnit\Framework\TestCase
{
    /**
     * @return iterable
     */
    public static function provideGrandfathered(): iterable
    {
        return [
            ['en-GB-oed', 'en-GB-oxendict'],
            ['i-ami', 'ami'],
            ['i-bnn', 'bnn'],
            ['i-default', 'i-default'],
            ['i-enochian', 'i-enochian'],
            ['i-hak', 'hak'],
            ['i-klingon', 'tlh'],
            ['i-lux', 'lb'],
            ['i-mingo', 'i-mingo'],
            ['i-navajo', 'nv'],
            ['i-pwn', 'pwn'],
            ['i-tao', 'tao'],
            ['i-tay', 'tay'],
            ['i-tsu', 'tsu'],
            ['sgn-BE-FR', 'sfb'],
            ['sgn-BE-NL', 'vgt'],
            ['sgn-CH-DE', 'sgg'],
            ['art-lojban', 'jbo'],
            ['cel-gaulish', 'cel-gaulish'],
            ['no-bok', 'nb'],
            ['no-nyn', 'nn'],
            ['zh-guoyu', 'cmn'],
            ['zh-hakka', 'hak'],
            ['zh-min', 'zh-min'],
            ['zh-min-nan', 'nan'],
            ['zh-xiang', 'hsn'],
        ];
    }

    /**
     * @return iterable
     */
    public static function provideSplitData(): iterable
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

        return [
            [
                '-en-us',
                $empty,
            ],
            [
                'abcdef',
                $empty,
            ],
            [
                'abcdefghi',
                $empty,
            ],
            [
                'gsw-u-sd-CHZH',
                [
                    Bcp47::LANGUAGE => 'gsw',
                    Bcp47::EXTENSION => 'u-sd-chzh',
                ],
            ],
            [
                'Heb-il-u-ca-Hebrew-TZ-Jeruslm',
                [
                    Bcp47::LANGUAGE => 'he',
                    Bcp47::REGION => 'IL',
                    Bcp47::EXTENSION => 'u-ca-hebrew-tz-jeruslm',
                ],
            ],
            [
                ' H e b - il - u - c a - H  e  b  r  e  w - T  Z -  J   e   r   u   s   l   m ',
                [
                    Bcp47::LANGUAGE => 'he',
                    Bcp47::REGION => 'IL',
                    Bcp47::EXTENSION => 'u-ca-hebrew-tz-jeruslm',
                ],
            ],
            [
                'EN-T-JP',
                [
                    Bcp47::LANGUAGE => 'en',
                    Bcp47::EXTENSION => 't-jp',
                ],
            ],
            [
                'ru-LATN-ua',
                [
                    Bcp47::LANGUAGE => 'ru',
                    Bcp47::SCRIPT => 'Latn',
                    Bcp47::REGION => 'UA',
                ],
            ],
            [
                'ca-Valencia',
                [
                    Bcp47::LANGUAGE => 'ca',
                    Bcp47::VARIANT => 'valencia',
                ],
            ],
            [
                'ca-es-Valencia',
                [
                    Bcp47::LANGUAGE => 'ca',
                    Bcp47::REGION => 'ES',
                    Bcp47::VARIANT => 'valencia',
                ],
            ],
            [
                'ja-latn-hepburn',
                [
                    Bcp47::LANGUAGE => 'ja',
                    Bcp47::SCRIPT => 'Latn',
                    Bcp47::VARIANT => 'hepburn',
                ],
            ],
            [
                'ja-latn-JP-hepburn',
                [
                    Bcp47::LANGUAGE => 'ja',
                    Bcp47::SCRIPT => 'Latn',
                    Bcp47::REGION => 'JP',
                    Bcp47::VARIANT => 'hepburn',
                ],
            ],
            [
                'ja-latn-JP-Hepburn-U-Foo-Bar-001-X-007-y-xyz',
                [
                    Bcp47::LANGUAGE => 'ja',
                    Bcp47::SCRIPT => 'Latn',
                    Bcp47::REGION => 'JP',
                    Bcp47::VARIANT => 'hepburn',
                    Bcp47::EXTENSION => 'u-foo-bar-001',
                    Bcp47::PRIVATE_USE => 'x-007-y-xyz',
                ],
            ],
            [
                'sl-Rozaj',
                [
                    Bcp47::LANGUAGE => 'sl',
                    Bcp47::VARIANT => 'rozaj',
                ],
            ],
            [
                'sl-Rozaj-1994',
                [
                    Bcp47::LANGUAGE => 'sl',
                    Bcp47::VARIANT => 'rozaj-1994',
                ],
            ],
            [
                'Sl-Rozaj-Biske',
                [
                    Bcp47::LANGUAGE => 'sl',
                    Bcp47::VARIANT => 'rozaj-biske',
                ],
            ],
            [
                'SL-Rozaj-Biske-1994',
                [
                    Bcp47::LANGUAGE => 'sl',
                    Bcp47::VARIANT => 'rozaj-biske-1994',
                ],
            ],
            [
                'SL-Latn-Rozaj-Biske-1994',
                [
                    Bcp47::LANGUAGE => 'sl',
                    Bcp47::VARIANT => 'rozaj-biske-1994',
                ],
            ],
            [
                'SL-Latn-it-Rozaj-Biske-1994',
                [
                    Bcp47::LANGUAGE => 'sl',
                    Bcp47::REGION => 'IT',
                    Bcp47::VARIANT => 'rozaj-biske-1994',
                ],
            ],
            [
                'SL-Rozaj-Biska-1994',
                $empty,
            ],
            [
                'sr-CYRL-Ijekavsk',
                [
                    Bcp47::LANGUAGE => 'sr',
                    Bcp47::SCRIPT => 'Cyrl',
                    Bcp47::VARIANT => 'ijekavsk',
                ],
            ],
            [
                'EN-ca-Newfound',
                [
                    Bcp47::LANGUAGE => 'en',
                    Bcp47::REGION => 'CA',
                    Bcp47::VARIANT => 'newfound',
                ],
            ],
            [
                'EN-latn-ca-Newfound',
                [
                    Bcp47::LANGUAGE => 'en',
                    Bcp47::SCRIPT => '',
                    Bcp47::REGION => 'CA',
                    Bcp47::VARIANT => 'newfound',
                ],
            ],
            [
                'EN-us-Newfound',
                $empty,
            ],
            [
                'aze',
                [
                    Bcp47::LANGUAGE => 'az',
                ],
            ],
            [
                'AZ-cyrl-Ru',
                [
                    Bcp47::LANGUAGE => 'az',
                    Bcp47::SCRIPT => 'Cyrl',
                    Bcp47::REGION => 'RU',
                ],
            ],
            [
                'aze-arab',
                [
                    Bcp47::LANGUAGE => 'az',
                    Bcp47::SCRIPT => 'Arab',
                ],
            ],
            [
                'az-arab-ir',
                [
                    Bcp47::LANGUAGE => 'az',
                    Bcp47::SCRIPT => 'Arab',
                    Bcp47::REGION => 'IR',
                ],
            ],
            [
                'en-basiceng',
                [
                    Bcp47::LANGUAGE => 'en',
                    Bcp47::VARIANT => 'basiceng',
                ],
            ],
            [
                'isv-Cyrl',
                [
                    Bcp47::LANGUAGE => 'isv',
                    Bcp47::SCRIPT => 'Cyrl',
                ],
            ],
            [
                'tlh-piqd',
                [
                    Bcp47::LANGUAGE => 'tlh',
                    Bcp47::SCRIPT => 'Piqd',
                ],
            ],
            [
                'ukr-cyrl-ua',
                [
                    Bcp47::LANGUAGE => 'uk',
                    Bcp47::SCRIPT => '',
                    Bcp47::REGION => 'UA',
                ],
            ],
            [
                'xy-IT',
                $empty,
            ],
            [
                'isv-Curl',
                $empty,
            ],
            [
                'cza',
                $empty,
            ],
            [
                'qtb',
                [
                    Bcp47::LANGUAGE => 'qtb',
                ],
            ],
            [
                'qvb',
                $empty,
            ],
            [
                'qtb-qaba',
                [
                    Bcp47::LANGUAGE => 'qtb',
                    Bcp47::SCRIPT => 'Qaba',
                ],
            ],
            [
                'qtb-qaca',
                $empty,
            ],
            [
                'qtb-qabz',
                $empty,
            ],
            [
                'fr-fx',
                [
                    Bcp47::LANGUAGE => 'fr',
                    Bcp47::REGION => 'FR',
                ],
            ],
            [
                'it-380',
                [
                    Bcp47::LANGUAGE => 'it',
                    Bcp47::REGION => 'IT',
                ],
            ],
            [
                'en-003',
                [
                    Bcp47::LANGUAGE => 'en',
                    Bcp47::REGION => '003',
                ],
            ],
            [
                'en-007',
                $empty,
            ],
            [
                'it-latn-380',
                [
                    Bcp47::LANGUAGE => 'it',
                    Bcp47::REGION => 'IT',
                    Bcp47::SCRIPT => '',
                ],
            ],
            [
                'qtb-xz',
                [
                    Bcp47::LANGUAGE => 'qtb',
                    Bcp47::REGION => 'XZ',
                ],
            ],
            [
                'qtb-qm',
                [
                    Bcp47::LANGUAGE => 'qtb',
                    Bcp47::REGION => 'QM',
                ],
            ],
            [
                'qtb-ap',
                $empty,
            ],
            [
                'ms-dup-id',
                [
                    Bcp47::LANGUAGE => 'dup',
                    Bcp47::REGION => 'ID',
                ],
            ],
            [
                'ms-dup',
                [
                    Bcp47::LANGUAGE => 'dup',
                ],
            ],
            [
                'tie-sd',
                [
                    Bcp47::LANGUAGE => 'ras',
                    Bcp47::REGION => 'SD',
                ],
            ],
        ];
    }

    /**
     * @return iterable
     */
    public static function provideRtl(): iterable
    {
        return [
            ['gsw-u-sd-CHZH', false],
            ['Heb-il-u-ca-Hebrew-TZ-Jeruslm', true],
            ['EN-T-JP', false],
            ['ru-LATN-ua', false],
            ['ca-valencia', false],
            ['az', false],
            ['AZ-cyrl-Ru', false],
            ['aze-arab', true],
            ['az-arab-ir', true],
            ['xy-IT', false],
        ];
    }
}
