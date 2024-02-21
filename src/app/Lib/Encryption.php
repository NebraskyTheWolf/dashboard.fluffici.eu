<?php

namespace app\Lib;

class Encryption
{

    /**
     * Encryption Algorithm
     *
     * @param string $encriptionKey The key used for encryption
     * @param string $data The data requires encryption
     * @return string Encrypted string
     */
    public static function customEncrypt(string $encriptionKey, string $data): string
    {
        $x = [];
        for ($i = 0, $j = strlen($encriptionKey); $i < $j; $i++) {
            $x[] = ord(substr($encriptionKey, $i, 1));
        }

        $x = array_pad($x, strlen($data), 0);
        $m = 1;
        $p = [];
        for ($i = 0, $j = strlen($data); $i < $j; $i++) {
            $m = ($m * (1 + ord(substr($data, $i, 1)))) % 256;
            $p[] = $m ^ $x[$i];
        }

        return implode(array_map('chr', $p));
    }

    /**
     * Decryption Algorithm
     *
     * @param string $encriptionKey The key used for decryption
     * @param string $data The data requires decryption
     * @return string Decrypted string
     */
    public static function customDecrypt(string $encriptionKey, string $data): string
    {
        $x = [];
        for ($i = 0, $j = strlen($encriptionKey); $i < $j; $i++) {
            $x[] = ord(substr($encriptionKey, $i, 1));
        }

        $x = array_pad($x, strlen($data), 0);
        $m = 1;
        $p = [];
        for ($i = 0, $j = strlen($data); $i < $j; $i++) {
            $p[] = ord(substr($data, $i, 1)) ^ $x[$i];
            $m = ($m * (1 + $p[$i])) % 256;
        }

        return implode(array_map('chr', $p));
    }
}
