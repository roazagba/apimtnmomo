<?php

namespace Roazagba\ApiMTNMomo\Utilities;

final class Helpers
{
    /**
     * Generates a random UUID version 4.
     *
     * A version 4 UUID is a universally unique identifier generated from random data.
     *
     * @return string The version 4 UUID as a string.
     */
    public static function uuid4(): string
    {
        $data = $data ?? random_bytes(16);
        assert(strlen($data) == 16);

        $data[6] = chr(ord($data[6]) & 0x0f | 0x40);
        $data[8] = chr(ord($data[8]) & 0x3f | 0x80);

        return vsprintf('%s%s-%s-%s-%s-%s%s%s', str_split(bin2hex($data), 4));
    }

    /**
     * Assigns values to object attributes.
     *
     * This method dynamically assigns the values from the provided array
     * to the corresponding object properties using setters if they exist.
     *
     * @param array $data Associative array where keys are property names and values are the values to assign.
     * @return self Returns the current instance of the object for method chaining.
     */
    public function assignAttributes(array $data = [])
    {
        if (empty($data)) {
            return $this;
        }

        foreach ($data as $property => $value) {
            $setter = 'set' . ucfirst($property);
            if (is_callable([$this, $setter])) {
                call_user_func([$this, $setter], $value);
            } else {
                $this->$property = $value;
            }
        }

        return $this;
    }


    /**
     * Converts an object to an associative array.
     *
     * This method converts an object into an associative array using JSON conversion.
     *
     * @param object $object The object to convert.
     * @return array The associative array representation of the object.
     */
    public static function convertObjectArray($objet): array
    {
        return json_decode(json_encode($objet), true);
    }
}
