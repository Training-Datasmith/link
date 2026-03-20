# Architecture: psr/link (PSR-13)

## Purpose

This package defines PSR-13: Link Definition Interfaces. It provides a standard
contract for representing typed web links (RFC 5988 / RFC 8288) and collections
of links, enabling interoperability between hypermedia-aware libraries and
frameworks (REST APIs, HAL, JSON-LD, HTML link elements, HTTP Link headers).

## PSR Standard

**PSR-13** — https://www.php-fig.org/psr/psr-13/

## Directory Structure

```
src/
  Link_Interface.php                    — Read-only web link value object
  Link_Provider_Interface.php           — Read-only collection of links
  Evolvable_Link_Interface.php          — Immutable link that can produce modified copies
  Evolvable_Link_Provider_Interface.php — Immutable collection that can produce modified copies
```

## Key Design Decisions

### Read-only vs. evolvable pairs
The standard provides both a read-only interface (Link_Interface, Link_Provider_Interface)
for consumers that only inspect links, and an "evolvable" interface pair for
contexts that need to build or modify link collections. Evolvable objects follow
the PSR-7 immutability convention: with_*() / without_*() return new instances.

### Links are value objects
A link encapsulates href, relationship types, and attributes. It is not a live
HTTP request; it is purely metadata describing a relationship between resources.
Equality is object identity (===), not structural equality.

### Multiple relationship types per link
A single link can carry multiple relationship types (e.g., both "next" and
"prefetch"). This reflects the RFC 8288 model where a Link header value may
contain multiple "rel" tokens.

### URI templates
Links whose href is a URI template (RFC 6570) are flagged by is_templated()
returning true. Consumers must expand template variables before dereferencing.

### Attribute flexibility
Attributes are typed as scalar or string arrays to support multi-valued
attributes like "hreflang" while remaining simple to consume.

## Extension Points

- Implement `Evolvable_Link_Interface` to build domain-specific link value objects.
- Implement `Evolvable_Link_Provider_Interface` to build paginated resource
  responses, HAL documents, or HTTP Link header builders.
- Implement `Link_Provider_Interface` on a response or resource object to
  expose its navigation links without exposing mutation methods.

## Dependency Flow

```
Resource / Response object
    └── Link_Provider_Interface  (exposes navigation links)
            └── Link_Interface[]  (individual typed links)
```
