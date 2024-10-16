<?php

namespace Roazagba\ApiMTNMomo\Utilities;

final class Helpers
{
    public static function uuid4(): string
    {
        // Generate 16 bytes (128 bits) of random data or use the data passed into the function.
        $data = $data ?? random_bytes(16);
        assert(strlen($data) == 16);

        // Set version to 0100
        $data[6] = chr(ord($data[6]) & 0x0f | 0x40);
        // Set bits 6-7 to 10
        $data[8] = chr(ord($data[8]) & 0x3f | 0x80);

        // Output the 36 character UUID.
        return vsprintf('%s%s-%s-%s-%s-%s%s%s', str_split(bin2hex($data), 4));
    }

    public function massAssignAttributes($data = [])
    {
        if (! is_array($data)) {
            return $this;
        }

        foreach ($data as $key => $value) {
            if (method_exists($this, 'set' . ucfirst($key))) {
                $this->{'set' . $key}($value);
            } else {
                $this->$key = $value;
            }
        }

        return $this;
    }

    public static function convertObjectArray($objet): array
    {
        return json_decode(json_encode($objet), true);
    }
}
