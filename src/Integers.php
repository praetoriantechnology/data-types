<?php
/**
 * This file is a part of "comely-io/data-types" package.
 * https://github.com/comely-io/data-types
 *
 * Copyright (c) Furqan A. Siddiqui <hello@furqansiddiqui.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code or visit following link:
 * https://github.com/comely-io/data-types/blob/master/LICENSE
 */

declare(strict_types=1);

namespace Comely\DataTypes;

/**
 * Class Integers
 * @package Comely\DataTypes
 */
class Integers
{
    /**
     * @param int $num
     * @param int $from
     * @param int $to
     * @return bool
     */
    public static function Range(int $num, int $from, int $to): bool
    {
        return ($num >= $from && $num <= $to);
    }

    /**
     * @param string $hex
     * @return BcNumber
     */
    public static function Unpack(string $hex): BcNumber
    {
        if (str_starts_with($hex, '0x')) {
            $hex = substr($hex, 2);
        }

        if (!$hex) {
            return new BcNumber(0);
        }

        $hex = self::HexitPads($hex);
        return new BcNumber(gmp_strval(gmp_init($hex, 16), 10));
    }

    /**
     * @param  int|string  $dec
     * @return string
     */
    public static function Pack_UInt_BE(int|string $dec): string
    {
        $dec = self::checkValidInt($dec);

        return self::HexitPads(bin2hex(gmp_export(gmp_init($dec, 10), 1, GMP_MSW_FIRST | GMP_NATIVE_ENDIAN)));
    }

    /**
     * @param $dec
     * @return string
     */
    public static function Pack_UInt_LE($dec): string
    {
        $dec = self::checkValidInt($dec);
        return self::HexitPads(bin2hex(gmp_export(gmp_init($dec, 10), 1, GMP_LSW_FIRST | GMP_NATIVE_ENDIAN)));
    }

    /**
     * @param string $hex
     * @return string
     */
    public static function HexitPads(string $hex): string
    {
        if (strlen($hex) % 2 !== 0) {
            $hex = "0" . $hex;
        }

        return $hex;
    }

    /**
     * @param  int|string|BcNumber  $dec
     * @return int|string
     */
    public static function checkValidInt(BcNumber|int|string $dec): int|string
    {
        if ($dec instanceof BcNumber && $dec->isInteger()) {
            $dec = $dec->value();
        }

        if (!is_int($dec)) {
            if (!is_string($dec) || !preg_match('/^-?(0|[1-9]+[0-9]*)$/', $dec)) {
                throw new \InvalidArgumentException('Argument must be a valid INT');
            }
        }

        return $dec;
    }
}
