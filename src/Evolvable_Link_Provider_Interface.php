<?php

declare (strict_types=1);
namespace Psr\Link;

/**
 * An immutable link provider collection that can produce modified copies.
 *
 * All with_link() and without_link() methods return a new instance, leaving
 * the original collection unchanged, consistent with PSR-7 value-object semantics.
 *
 * @since 1.0
 * @see https://www.php-fig.org/psr/psr-13/
 */
interface Evolvable_Link_Provider_Interface extends Link_Provider_Interface
{
    /**
     * Returns a new instance with the specified link added to the collection.
     *
     * If the link is already present (determined by === object identity comparison,
     * not value equality), this method MUST return normally without adding a
     * duplicate. The method MUST NOT modify the current instance.
     *
     * @param Link_Interface $link The link object to include in the collection.
     *
     * @return static A new instance with $link included.
     *
     * @since 1.0
     */
    public function with_link(Link_Interface $link): static;

    /**
     * Returns a new instance with the specified link removed from the collection.
     *
     * Presence is determined by === object identity, not value equality. If the
     * link is not in the collection, this method MUST return normally without
     * error (idempotent).
     *
     * @param Link_Interface $link The link object to remove from the collection.
     *
     * @return static A new instance with $link excluded.
     *
     * @since 1.0
     */
    public function without_link(Link_Interface $link): static;
}