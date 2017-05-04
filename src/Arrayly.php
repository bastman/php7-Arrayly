<?php


namespace Arrayly;


class Arrayly
{
    /**
     * @var array
     */
    private $data = [];

    /**
     * Arrayly constructor.
     * @param array $data
     */
    public function __construct(array $data)
    {
        $this->data = $data;
    }

    /**
     * @param array $data
     * @return Arrayly
     */
    public static function ofArray(array $data)
    {
        return new Arrayly($data);
    }

    /**
     * @param iterable $source
     * @return Arrayly
     */
    public static function ofIterable(iterable $source)
    {
        $sink = [];
        foreach ($source as $k => $v) {
            $sink[$k] = $v;
        }
        return new Arrayly($sink);
    }

    /**
     * @return array
     */
    public function toArray()
    {
        return $this->data;
    }

    /**
     * @return Arrayly
     */
    public function toArrayly()
    {
        return new Arrayly($this->data);
    }

    /**
     * @param $key
     * @param $value
     * @return Arrayly
     */
    public function withKey($key, $value)
    {
        $sink = $this->data;
        $sink[$key] = $value;

        return $this->withData($sink);
    }

    /**
     * @param array $data
     * @return Arrayly
     */
    public function withData(array $data)
    {
        return new Arrayly($data);
    }

    /**
     * @param bool $strict
     * @return Arrayly
     */
    public function keys(bool $strict)
    {
        return $this->withData(array_keys($this->data, null, $strict));
    }

    /**
     * @return Arrayly
     */
    public function values()
    {
        return $this->withData(array_values($this->data));
    }

    /**
     * @return Arrayly
     */
    public function flip()
    {
        return $this->withData(array_flip($this->data));
    }

    /**
     * @param int $times
     * @return Arrayly
     */
    public function shuffle(int $times)
    {
        $sink = (array)$this->data;
        $i = 0;
        while ($i < $times) {
            shuffle($sink);
        }

        return $this->withData((array)$sink);
    }

    /**
     * @return int
     */
    public function count()
    {
        return count($this->data);
    }

    /**
     * @param bool $preserveKeys
     * @return Arrayly
     */
    public function reverse(bool $preserveKeys)
    {
        return $this->withData(array_reverse($this->data, $preserveKeys));
    }

    /**
     * @param $key
     * @return bool
     */
    public function hasKey($key)
    {
        return array_key_exists($key, $this->data);
    }

    /**
     * @return mixed|null
     */
    public function firstOrNull()
    {
        return $this->firstOrDefault(null);
    }

    /**
     * @param $defaultValue
     * @return mixed
     */
    public function firstOrDefault($defaultValue)
    {
        return $this->firstOrElse(function () use ($defaultValue) {
            return $defaultValue;
        });
    }

    /**
     * @param \Closure $defaultValueSupplier
     * @return mixed
     */
    public function firstOrElse(\Closure $defaultValueSupplier)
    {
        foreach ($this->data as $item) {

            return $item;
        }

        return $defaultValueSupplier();
    }

    /**
     * @param $key
     * @param \Closure $defaultValueSupplier
     * @return mixed
     */
    public function getOrElse($key, \Closure $defaultValueSupplier)
    {
        if (array_key_exists($key, $this->data)) {

            return $this->data[$key];
        }

        return $defaultValueSupplier();
    }

    /**
     * @param $key
     * @return mixed|null
     */
    public function getOrNull($key)
    {
        return $this->getOrDefault($key, null);
    }

    /**
     * @param $key
     * @param $defaultValue
     * @return mixed
     */
    public function getOrDefault($key, $defaultValue)
    {
        if (array_key_exists($key, $this->data)) {

            return $this->data[$key];
        }

        return $defaultValue;
    }

    /**
     * @param \Closure $predicate
     * @return mixed
     */
    public function findOrNull(\Closure $predicate)
    {
        return $this->findOrDefault($predicate, null);
    }

    /**
     * @param \Closure $predicate
     * @param $defaultValue
     * @return mixed
     */
    public function findOrDefault(\Closure $predicate, $defaultValue)
    {
        return $this->findOrElse($predicate, function () use ($defaultValue) {
            return $defaultValue;
        });
    }

    /**
     * @param \Closure $predicate
     * @param \Closure $defaultValueSupplier
     * @return mixed
     */
    public function findOrElse(\Closure $predicate, \Closure $defaultValueSupplier)
    {
        foreach ($this->data as $k => $v) {
            if ($predicate($v) === true) {

                return $v;
            }
        }

        return $defaultValueSupplier();
    }

    /**
     * @param \Closure $predicate
     * @return mixed
     */
    public function findIndexedOrNull(\Closure $predicate)
    {
        return $this->findIndexedOrDefault($predicate, null);
    }

    /**
     * @param \Closure $predicate
     * @param $defaultValue
     * @return mixed
     */
    public function findIndexedOrDefault(\Closure $predicate, $defaultValue)
    {
        return $this->findIndexedOrElse($predicate, function () use ($defaultValue) {
            return $defaultValue;
        });
    }

    /**
     * @param \Closure $predicate
     * @param \Closure $defaultValueSupplier
     * @return mixed
     */
    public function findIndexedOrElse(\Closure $predicate, \Closure $defaultValueSupplier)
    {
        foreach ($this->data as $k => $v) {
            if ($predicate($k, $v) === true) {

                return $v;
            }
        }

        return $defaultValueSupplier();
    }

    /**
     * @param \Closure $callback
     * @return $this
     */
    public function onEach(\Closure $callback)
    {
        foreach ($this->data as $k => $v) {
            $callback($v);
        }

        return $this;
    }

    /**
     * @param \Closure $callback
     * @return $this
     */
    public function onEachIndexed(\Closure $callback)
    {
        foreach ($this->data as $k => $v) {
            $callback($k, $v);
        }

        return $this;
    }

    /**
     * @param \Closure $pedicate
     * @return Arrayly
     */
    public function filter(\Closure $pedicate)
    {
        $sink = [];
        foreach ($this->data as $k => $v) {
            if ($pedicate($v) === true) {
                $sink[$k] = $v;
            }
        }

        return $this->withData($sink);
    }

    /**
     * @param \Closure $predicate
     * @return Arrayly
     */
    public function filterIndexed(\Closure $predicate)
    {
        $sink = [];
        foreach ($this->data as $k => $v) {
            if ($predicate($k, $v) === true) {
                $sink[$k] = $v;
            }
        }

        return $this->withData($sink);
    }

    /**
     * @param \Closure $transform
     * @return Arrayly
     */
    public function map(\Closure $transform)
    {
        $sink = [];
        foreach ($this->data as $k => $v) {
            $sink[$k] = $transform($v);
        }

        return $this->withData($sink);
    }

    /**
     * @param \Closure $transform
     * @return Arrayly
     */
    public function mapIndexed(\Closure $transform)
    {
        $sink = [];
        foreach ($this->data as $k => $v) {
            $sink[$k] = $transform($k, $v);
        }

        return $this->withData($sink);
    }

    /**
     * @param \Closure $keySelector
     * @return Arrayly
     */
    public function mapKeys(\Closure $keySelector)
    {
        $sink = [];
        foreach ($this->data as $k => $v) {
            $sink[$keySelector($v)] = $v;
        }

        return $this->withData($sink);
    }

    /**
     * @param \Closure $keySelector
     * @return Arrayly
     */
    public function mapKeysIndexed(\Closure $keySelector)
    {
        $sink = [];
        foreach ($this->data as $k => $v) {
            $sink[$keySelector($k, $v)] = $v;
        }

        return $this->withData($sink);
    }

    /**
     * @param \Closure $transform
     * @return Arrayly
     */
    public function flatMap(\Closure $transform)
    {
        $sink = [];
        foreach ($this->data as $k => $v) {
            /** @var iterable $transformedCollection */
            $transformedCollection = $transform($v);
            foreach ($transformedCollection as $transformedItem) {
                $sink[] = $transformedItem;
            }
        }

        return $this->withData($sink);
    }

    /**
     * @param \Closure $transform
     * @return Arrayly
     */
    public function flatMapIndexed(\Closure $transform)
    {
        $sink = [];
        foreach ($this->data as $k => $v) {
            /** @var iterable $transformed */
            $transformedCollection = $transform($k, $v);
            foreach ($transformedCollection as $transformedItem) {
                $sink[] = $transformedItem;
            }
        }

        return $this->withData($sink);
    }


    /**
     * @param \Closure $keySelector
     * @return Arrayly
     */
    public function groupBy(\Closure $keySelector)
    {
        $sink = [];
        foreach ($this->data as $k => $v) {
            $groupKey = $keySelector($v);
            if (array_key_exists($groupKey, $sink)) {
                $sink[$groupKey][] = $v;
            } else {
                $sink[$groupKey] = [$v];
            }
        }

        return $this->withData($sink);
    }

    /**
     * @param \Closure $keySelector
     * @return Arrayly
     */
    public function groupByIndexed(\Closure $keySelector)
    {
        $sink = [];
        foreach ($this->data as $k => $v) {
            $groupKey = $keySelector($k, $v);
            if (array_key_exists($groupKey, $sink)) {
                $sink[$groupKey][] = $v;
            } else {
                $sink[$groupKey] = [$v];
            }
        }

        return $this->withData($sink);
    }

    /**
     * @param mixed $initialValue
     * @param \Closure $reducer
     * @return mixed
     */
    public function reduce($initialValue, \Closure $reducer)
    {
        $accumulatedValue = $initialValue;
        foreach ($this->data as $k => $v) {
            $accumulatedValue = $reducer($accumulatedValue, $v);
        }

        return $accumulatedValue;
    }

    /**
     * @param mixed $initialValue
     * @param \Closure $reducer
     * @return mixed
     */
    public function reduceIndexed($initialValue, \Closure $reducer)
    {
        $accumulatedValue = $initialValue;
        foreach ($this->data as $k => $v) {
            $accumulatedValue = $reducer($accumulatedValue, $k, $v);
        }

        return $accumulatedValue;
    }

    /**
     * @param \Closure $comparator
     * @param bool $descending
     * @return Arrayly
     */
    public function sortBy(\Closure $comparator, bool $descending)
    {
        $source = (array)$this->data;
        usort($source, $comparator);
        $sink = (array)$source;
        if ($descending) {
            $sink = array_reverse($sink);
        }

        return $this->withData($sink);
    }

    /**
     * @param int $amount
     * @return Arrayly
     */
    public function take(int $amount)
    {
        $sink = [];
        $currentAmount = 0;
        foreach ($this->data as $k => $v) {
            if ($currentAmount >= $amount) {

                break;
            }
            $sink[$k] = $v;
            $currentAmount++;
        }

        return $this->withData($sink);
    }

    /**
     * @param \Closure $predicate
     * @return Arrayly
     */
    public function takeWhile(\Closure $predicate)
    {
        $sink = [];
        foreach ($this->data as $k => $v) {
            if ($predicate($v) === true) {
                $sink[$k] = $v;
            } else {

                break;
            }

        }

        return $this->withData($sink);
    }

    /**
     * @param \Closure $predicate
     * @return Arrayly
     */
    public function takeWhileIndexed(\Closure $predicate)
    {
        $sink = [];
        foreach ($this->data as $k => $v) {
            if ($predicate($k, $v) === true) {
                $sink[$k] = $v;
            } else {

                break;
            }

        }

        return $this->withData($sink);
    }

    /**
     * @param int $amount
     * @return Arrayly
     */
    public function drop(int $amount)
    {
        $sink = [];
        $dropped = 0;
        foreach ($this->data as $k => $v) {
            if ($dropped < $amount) {
                $dropped++;
            } else {
                $sink[$k] = $v;
            }
        }

        return $this->withData($sink);
    }


    /**
     * @param \Closure $predicate
     * @return Arrayly
     */
    public function dropWhile(\Closure $predicate)
    {
        $sink = [];
        foreach ($this->data as $k => $v) {
            if ($predicate($v) !== true) {
                $sink[$k] = $v;
            }
        }

        return $this->withData($sink);
    }

    /**
     * @param \Closure $predicate
     * @return Arrayly
     */
    public function dropWhileIndexed(\Closure $predicate)
    {
        $sink = [];
        foreach ($this->data as $k => $v) {
            if ($predicate($k, $v) !== true) {
                $sink[$k] = $v;
            }
        }

        return $this->withData($sink);
    }

}