<?php

namespace Arrayly;

if (!function_exists('Arrayly\ofArray')) {
    /**
     * @param array $data
     * @return Arrayly
     */
    function ofArray(array $data)
    {
        return Arrayly::ofArray($data);
    }
}