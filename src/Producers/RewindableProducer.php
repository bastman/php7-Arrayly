<?php
declare( strict_types = 1 );
namespace Arrayly\Producers;

use Arrayly\Util\internals as utils;
final class RewindableProducer implements \Iterator
{
    /**
     * @var \Closure
     */
    private $supplier;
    /**
     * @var \Iterator
     */
    private $iterator;

    private function __construct( \Closure $iteratorSupplier ) {
        $this->supplier = $iteratorSupplier;
        $this->createIterator();
    }

    public static function ofIteratorSupplier(\Closure $iteratorSupplier):RewindableProducer {
        return new static($iteratorSupplier);
    }

    public static function ofIterable(iterable $iterable):RewindableProducer {
        if($iterable instanceof static) {

            return $iterable->newInstance();
        }
        $supplier = utils\iterableToRewindableIteratorSupplier($iterable);

        return static::ofIteratorSupplier($supplier);
    }

    private function createIterator() {
        $supplier = $this->supplier;
        $iterator = $supplier();
        $iterator = utils\iterableToIterator($iterator);

        $this->iterator = $iterator;
    }

    public function current()
    {
        return $this->iterator->current();
    }

    public function next()
    {
        $this->iterator->next();
    }

    public function key()
    {
        return $this->iterator->key();
    }

    public function valid()
    {
        return $this->iterator->valid();
    }

    public function rewind()
    {
        $this->createIterator();
    }

    public function newInstance():RewindableProducer {
        return new static($this->supplier);
    }
}