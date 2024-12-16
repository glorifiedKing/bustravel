<?php

namespace glorifiedking\BusTravel\Support;

use Illuminate\Support\Carbon;

class Helper
{
    /**
     * Format carbon datetime.
     *
     * @param \Carbon\Carbon $dt
     * @param string         $format
     * @param string         $default
     *
     * @return \Carbon\Carbon
     */
    public static function format_dt(Carbon $dt = null, $format = 'Y-m-d', $default = null)
    {
        if ($dt != null) {
            return $dt->format($format);
        }

        if ($default == 'now') {
            return Carbon::now()->format($format);
        }

        return $dt;
    }

    public static function base64url_encode($str)
    {
        return rtrim(strtr(base64_encode($str), '+/', '-_'), '=');
    }

    public static function generateJWT($headers, $payload, $secret = "secret")
    {
        $headers_encoded = self::base64url_encode(json_encode($headers));

        $payload_encoded = self::base64url_encode(json_encode($payload));

        $signature = hash_hmac('SHA512', "$headers_encoded.$payload_encoded", $secret, true);
        $signature_encoded = self::base64url_encode($signature);

        $jwt = "$headers_encoded.$payload_encoded.$signature_encoded";

        return $jwt;
    }
}
