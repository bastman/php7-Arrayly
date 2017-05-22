<?php
declare(strict_types=1);

namespace Arrayly\Util;

function requireIsIterator(\Iterator $iterable):\Iterator{
    return $iterable;
}