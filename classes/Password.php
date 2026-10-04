<?php

class Password
{
    public static function hash($plain)
    {
        return password_hash($plain, PASSWORD_DEFAULT);
    }

    public static function verify($plain, $stored)
    {
        if (self::isLegacyMd5($stored)) {
            return hash_equals($stored, md5($plain));
        }
        return password_verify($plain, $stored);
    }

    public static function needsUpgrade($stored)
    {
        return self::isLegacyMd5($stored)
            || password_needs_rehash($stored, PASSWORD_DEFAULT);
    }

    private static function isLegacyMd5($stored)
    {
        return (bool) preg_match('/^[0-9a-f]{32}$/i', $stored);
    }
}