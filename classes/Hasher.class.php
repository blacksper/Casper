<?php
include("PasswordHash.php");

/**
 * Created by PhpStorm.
 * User: Lalka
 * Date: 09.05.2016
 * Time: 19:23
 */
class Hasher
{

    function getDefHash($str, $type)
    {
        $hash = hash($type, $str);
        return $hash;
    }

    function mysqlOldHash($input, $hex = true)
    {
        $nr = 1345345333;
        $add = 7;
        $nr2 = 0x12345671;
        $tmp = null;
        $inlen = strlen($input);
        for ($i = 0; $i < $inlen; $i++) {
            $byte = substr($input, $i, 1);
            if ($byte == ' ' || $byte == "\t") continue;
            $tmp = ord($byte);
            $nr ^= ((($nr & 63) + $add) * $tmp) + (($nr << 8) & 0xFFFFFFFF);
            $nr2 += (($nr2 << 8) & 0xFFFFFFFF) ^ $nr;
            $add += $tmp;
        }
        $out_a = $nr & ((1 << 31) - 1);
        $out_b = $nr2 & ((1 << 31) - 1);
        $output = sprintf("%08x%08x", $out_a, $out_b);
        if ($hex) return $output;
        return hex_hash_to_bin($output);
    } //END function mysql_old_password_hash

    /**
     * MySQL "PASSWORD()" AKA MySQLSHA1 HASH FUNCTION
     * This is the password hashing function used in MySQL since version 4.1.1
     * By Rev. Dustin Fineout 10/9/2009 9:36:20 AM
     **/
    function mysqlHash($input, $hex = true)
    {
        $sha1_stage1 = sha1($input, true);
        $output = sha1($sha1_stage1, !$hex);
        return $output;
    } //END function mysql_password_hash

    function wordpress3Hash($str)
    {
        $ph = new PasswordHash(8, true);
        $hash = $ph->HashPassword($str);
        return $hash;
    }

    /**
     * Computes each hexidecimal pair into the corresponding binary octet.
     * Similar to mysql hex2octet function.
     **/
    private function hex_hash_to_bin($hex)
    {
        $bin = "";
        $len = strlen($hex);
        for ($i = 0; $i < $len; $i += 2) {
            $byte_hex = substr($hex, $i, 2);
            $byte_dec = hexdec($byte_hex);
            $byte_char = chr($byte_dec);
            $bin .= $byte_char;
        }
        return $bin;
    }

}