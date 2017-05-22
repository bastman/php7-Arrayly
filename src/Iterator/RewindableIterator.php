<?php
declare( strict_types = 1 );
namespace Arrayly\Iterator;


class RewindableIterator implements \Iterator
{
    /**
     * @var \Closure
     */
    private $iteratorSupplier;
    /**
     * @var \Iterator
     */
    private $iterator;

    public static function ofIterable(iterable $iterable) {
        if($iterable instanceof \Generator) {

            throw new \InvalidArgumentException(
                'Parameter iterable must not be instanceof \Generator! since it is not rewindable.'
                .'use ::ofIterableSupplier(fn) for providing rewindable generators.'
            );
        }

        $supplier = function() use($iterable){
            yield from $iterable;
        };

        return static::ofIterableSupplier($supplier);
    }

    public static function ofIterableSupplier(\Closure $supplier) {
        return new static($supplier);
    }

    public function __construct( \Closure $iteratorSupplier ) {
        $this->iteratorSupplier = $iteratorSupplier;
        $this->createIterator();
    }

    private function requireIsIterator(\Iterator $iterable):\Iterator{
        return $iterable;
    }
    private function createIterator() {
        $supplier = $this->iteratorSupplier;
        $iterator = $supplier();
        if(is_array($iterator)) {
            $iterator=new \ArrayIterator($iterator);
        }
        $iterator = $this->requireIsIterator($iterator);
        $this->iterator = $iterator;
    }

    public function current() {
        return $this->iterator->current();
    }

    public function next() {
        $this->iterator->next();
    }

    public function key() {
        return $this->iterator->key();
    }

    public function valid() {
        return $this->iterator->valid();
    }

    public function rewind() {
        $this->createIterator();
    }

    public function newInstance():RewindableIterator {
        return new static($this->iteratorSupplier);
    }

}