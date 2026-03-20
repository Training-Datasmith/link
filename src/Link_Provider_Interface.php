<?php

declare (strict_types=1);
namespace Psr\Link;

/**
 * A collection of Link_Interface objects providing access by relationship type.
 *
 * Implement this interface on any object that exposes a set of web links —
 * for example, an HTTP response that has parsed Link headers, or a resource
 * representation in a JSON-LD or HAL payload.
 *
 * @since 1.0
 * @see https://www.php-fig.org/psr/psr-13/
 */
interface Link_Provider_Interface
{
    /**
     * Returns all links held by this provider.
     *
     * The iterable may be an array or any PHP Traversable object. If no links
     * are available, an empty array or Traversable MUST be returned.
     * The iteration order is implementation-defined.
     *
     * @return iterable<Link_Interface> All Link objects in this collection.
     *   An empty iterable if the provider holds no links.
     *
     * @since 1.0
     */
    public function get_links(): iterable;

    /**
     * Returns all links that have the given relationship type.
     *
     * Multiple links can share a relationship type (e.g., multiple "alternate"
     * language variants). The iterable may be an array or any Traversable. If
     * no links with the given relationship exist, an empty iterable MUST be returned.
     *
     * @param string $rel The relationship type to filter by (e.g., "next",
     *   "canonical", "stylesheet"). Standard types are defined in the IANA
     *   Link Relations registry; extension types are absolute URIs.
     *
     * @return iterable<Link_Interface> All links whose get_rels() includes $rel.
     *   An empty iterable if no matching links exist.
     *
     * @since 1.0
     */
    public function get_links_by_rel(string $rel): iterable;
}