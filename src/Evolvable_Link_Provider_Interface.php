<?php

declare (strict_types=1);
namespace Psr\Link;

/**
 * An evolvable link provider value object.
 */
interface Evolvable_Link_Provider_Interface extends Link_Provider_Interface
{
    /**
     * Returns an instance with the specified link included.
     *
     * If the specified link is already present, this method MUST return normally
     * without errors. The link is present if $link is === identical to a link
     * object already in the collection.
     *
     * @param LinkInterface $link
     *   A link object that should be included in this collection.
     */
    public function with_link(Link_Interface $link): static;
    /**
     * Returns an instance with the specifed link removed.
     *
     * If the specified link is not present, this method MUST return normally
     * without errors. The link is present if $link is === identical to a link
     * object already in the collection.
     *
     * @param LinkInterface $link
     *   The link to remove.
     */
    public function without_link(Link_Interface $link): static;
}