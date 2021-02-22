<?php

namespace BackBee\Renderer\Helper;

use Countable;
use Doctrine\ORM\Tools\Pagination\Paginator;
use Iterator;

/**
 * Class sitemapLocationIterator
 *
 * Helper providing an iterator on collection to preserve memory consumption.
 *
 * @package BackBee\Renderer\Helper
 */
class sitemapLocationIterator extends AbstractHelper implements Iterator, Countable
{
    /**
     * The first result index.
     *
     * @var integer
     */
    private $start;

    /**
     * The limit step for inner iterator.
     *
     * @var integer
     */
    private $step;

    /**
     * The index of the inner iterator.
     *
     * @var integer
     */
    private $index;

    /**
     * The number of element in the inner iterator.
     *
     * @var integer
     */
    private $count;

    /**
     * The provided paginator to iterate throw.
     *
     * @var Paginator
     */
    private $paginator;

    /**
     * Return an iterator on a collection.
     *
     * @param Paginator $paginator The paginator collected.
     * @param integer   $step      The limit step of the inner iterator.
     *
     * @return Iterator
     */
    public function __invoke(Paginator $paginator, int $step = 1500): Iterator
    {
        $this->index = 0;
        $this->paginator = $paginator;
        $this->step = min([$this->paginator->getQuery()->getMaxResults(), $step]);
        $this->count = floor($this->paginator->getQuery()->getMaxResults() / $step);
        $this->start = $this->paginator->getQuery()->getFirstResult();

        return $this;
    }

    /**
     * Return the current element.
     *
     * @return Paginator
     */
    public function current(): Paginator
    {
        $this->getRenderer()->getApplication()->getEntityManager()->clear();
        $this
            ->paginator
            ->getQuery()
            ->setFirstResult($this->start + ($this->index * $this->step))
            ->setMaxResults($this->step);

        return $this->paginator;
    }

    /**
     * Return the key of the current element.
     *
     * @return integer
     */
    public function key(): int
    {
        return $this->index;
    }

    /**
     * Move forward to next element.
     *
     * @return Paginator|null
     */
    public function next(): ?Paginator
    {
        $this->index++;
        if ($this->valid()) {
            return $this->current();
        }

        return null;
    }

    /**
     * Rewind the Iterator to the first element.
     */
    public function rewind(): void
    {
        $this->index = 0;
    }

    /**
     * Checks if current position is valid.
     *
     * @return boolean
     */
    public function valid(): bool
    {
        return $this->index <= $this->count;
    }

    /**
     * Count elements of the inner iterator.
     *
     * @return integer
     */
    public function count(): int
    {
        return $this->count();
    }
}
