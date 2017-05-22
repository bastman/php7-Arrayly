<?php
declare(strict_types=1);

namespace Arrayly\Util;

function requireIsIterator(\Iterator $iterator):\Iterator{
    return $iterator;
}

function requireIterable(iterable $iterable): iterable
{
    return $iterable;
}