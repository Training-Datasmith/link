<?php

declare (strict_types=1);
namespace Psr\Link;

/**
 * An immutable link value object that can produce modified copies of itself.
 *
 * All with_*() and without_*() methods return a new instance containing the
 * requested change, leaving the original unchanged. This follows the same
 * immutability convention used by PSR-7 HTTP message objects.
 *
 * @since 1.0
 * @see https://www.php-fig.org/psr/psr-13/
 */
interface Evolvable_Link_Interface extends Link_Interface
{
    /**
     * Returns a new instance with the specified href.
     *
     * @param string|\Stringable $href The target URI or URI template. Must be
     *   one of: an absolute URI (RFC 5988), a relative URI (RFC 5988), a URI
     *   template (RFC 6570), or a Stringable object that produces one of the
     *   above. An implementing library SHOULD evaluate a Stringable to a string
     *   immediately so the stored value is always a string.
     *
     * @return static A new instance with the href replaced by $href.
     *
     * @since 1.0
     */
    public function with_href(string|\Stringable $href): static;
    /**
     * Returns a new instance with the specified relationship type added.
     *
     * If $rel is already present in the link's relationship set, this method
     * MUST return normally without adding it a second time (idempotent).
     * The method MUST NOT modify the current instance.
     *
     * @param string $rel The relationship type to add (e.g., "next", "canonical").
     *   Extension relationship types MUST be absolute URIs.
     *
     * @return static A new instance with $rel included in the relationships set.
     *
     * @since 1.0
     */
    public function with_rel(string $rel): static;
    /**
     * Returns a new instance with the specified relationship type removed.
     *
     * If $rel is not present in the link's relationship set, this method MUST
     * return normally without error (idempotent).
     *
     * @param string $rel The relationship type to remove.
     *
     * @return static A new instance with $rel excluded from the relationships set.
     *
     * @since 1.0
     */
    public function without_rel(string $rel): static;
    /**
     * Returns a new instance with the specified attribute set.
     *
     * If the attribute is already present, it is overwritten with $value.
     * Common attributes include "type" (target media type), "hreflang"
     * (language of the target), "title", and "media".
     *
     * @param string $attribute The attribute name to set (e.g., "type", "hreflang").
     * @param string|\Stringable|int|float|bool|array $value The attribute value.
     *   Arrays MUST contain only strings. A Stringable value SHOULD be converted
     *   to string immediately by the implementation.
     *
     * @return static A new instance with the attribute added or updated.
     *
     * @since 1.0
     */
    public function with_attribute(string $attribute, string|\Stringable|int|float|bool|array $value): static;

    /**
     * Returns a new instance with the specified attribute removed.
     *
     * If the attribute is not present, this method MUST return normally
     * without error (idempotent).
     *
     * @param string $attribute The attribute name to remove.
     *
     * @return static A new instance with the attribute absent.
     *
     * @since 1.0
     */
    public function without_attribute(string $attribute): static;
}