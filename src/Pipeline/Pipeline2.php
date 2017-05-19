<?php
declare(strict_types=1);
namespace Arrayly\Pipeline;

use Arrayly\Sequence\Sequence;
use Arrayly\Sequence\partials as fn;
class Pipeline2
{
    /**
     * @var \Closure[]
     */
    private $commands = [];

    /**
     * @var iterable
     */
    private $source;

    public function __construct(iterable $source)
    {
        $this->source = $source;
    }

    private function addCommand(\Closure ...$command) {
        foreach ($command as $cmd) {
            $this->commands[]=$cmd;
        }
    }

    public function map(\Closure $transform): Pipeline2
    {
        $this->addCommand(fn\map($transform));

        return $this;
    }

    public function filter(\Closure $predicate): Pipeline2
    {
        $this->addCommand(fn\filter($predicate));

        return $this;
    }

    public function reducing($initialValue, \Closure $reducer): Pipeline2
    {
        $this->addCommand(fn\reducing($initialValue, $reducer));

        return $this;
    }

    public function collect(): iterable
    {
        $producerCmd=fn\iterate();
        $current = $producerCmd($this->source);
        foreach ($this->commands as $c) {
            $current = $c($current);
        }

        return $current;
    }

    public function collectAsSequence(): Sequence
    {
        return new Sequence($this->collect());
    }
}