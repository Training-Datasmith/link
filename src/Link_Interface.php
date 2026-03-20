<?php

declare (strict_types=1);
namespace Psr\Link;

/**
 * A readable, immutable Web Link object as described by RFC 5988 / RFC 8288.
 *
 * A link represents a typed relationship between two resources identified by URIs.
 * Links appear in HTTP Link headers, HTML <link> elements, and JSON-LD contexts.
 * Common relationship types include "next", "prev", "canonical", "stylesheet", etc.
 *
 * @since 1.0
 * @see https://www.php-fig.org/psr/psr-13/
 * @see https://tools.ietf.org/html/rfc8288 Web Linking
 * @see https://tools.ietf.org/html/rfc6570 URI Template
 */
interface Link_Interface
{
    /**
     * Returns the target URI of the link.
     *
     * The returned value MUST be one of:
     * - An absolute URI, as defined by RFC 5988 (e.g., "https://example.com/next").
     * - A relative URI, as defined by RFC 5988. The base is assumed known by context.
     * - A URI template as defined by RFC 6570 (e.g., "/users/{id}").
     *
     * If a URI template is returned, is_templated() MUST return true, and
     * callers MUST expand the template before dereferencing the link.
     *
     * @return string The link target URI or URI template string.
     *
     * @see self::is_templated() Must be checked when using the href for HTTP requests.
     * @since 1.0
     */
    public function get_href(): string;

    /**
     * Returns whether this link's href is a URI template rather than a literal URI.
     *
     * URI templates (RFC 6570) contain variable expressions such as {id} or {+path}
     * that must be expanded with concrete values before the link can be dereferenced.
     * Consumers must check this flag and expand the template using a library such
     * as rize/uri-template before making an HTTP request to the href.
     *
     * @return bool True if get_href() returns a URI template; false if it is a
     *   literal URI that can be used directly.
     *
     * @since 1.0
     */
    public function is_templated(): bool;

    /**
     * Returns the relationship type(s) of the link.
     *
     * Each relationship type is a string identifying the semantic of the link.
     * Standard relation types are registered at IANA (e.g., "next", "prev",
     * "self", "canonical"). Extension relation types MUST be absolute URIs.
     * A single link may have multiple relationship types; an empty array
     * indicates no relationships are defined.
     *
     * @return string[] An array of zero or more relationship type strings.
     *   Standard relation types are lowercase ASCII; extension types are URIs.
     *
     * @since 1.0
     */
    public function get_rels(): array;

    /**
     * Returns the attributes that describe or qualify the target URI.
     *
     * Attributes provide metadata about the link target. Common attributes
     * include "type" (media type of the target), "hreflang" (language),
     * "title" (human-readable label), and "media" (intended media device).
     *
     * @return array<string, string|string[]> A key-value map of attributes.
     *   Each key is a string; each value is either a scalar PHP primitive or
     *   an array of strings for multi-valued attributes. Returns an empty array
     *   if no attributes are defined.
     *
     * @since 1.0
     */
    public function get_attributes(): array;
}