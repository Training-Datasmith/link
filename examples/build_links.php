<?php

declare(strict_types=1);

/**
 * Example: building PSR-13 Web Link objects.
 *
 * PSR-13 defines interfaces only; this example uses a minimal concrete
 * implementation to demonstrate the Link_Interface and Evolvable_Link_Interface
 * contracts.
 *
 * Run from the link project root:
 *   php examples/build_links.php
 */

require __DIR__ . '/../vendor/autoload.php';

use Psr\Link\EvolvableLinkInterface;
use Psr\Link\LinkInterface;

/**
 * Minimal immutable PSR-13 link implementation for demonstration.
 */
final class Link implements EvolvableLinkInterface
{
    /** @param string[] $rels */
    public function __construct(
        private readonly string $href,
        private readonly array  $rels       = [],
        private readonly array  $attributes = [],
    ) {}

    public function getHref(): string  { return $this->href; }
    public function isTemplated(): bool { return (bool) preg_match('/\{.+\}/', $this->href); }
    public function getRels(): array   { return $this->rels; }
    public function getAttributes(): array { return $this->attributes; }

    public function withHref(string|\Stringable $href): static
    {
        return new static((string) $href, $this->rels, $this->attributes);
    }

    public function withRel(string $rel): static
    {
        if (in_array($rel, $this->rels, true)) {
            return $this;
        }
        return new static($this->href, [...$this->rels, $rel], $this->attributes);
    }

    public function withoutRel(string $rel): static
    {
        return new static($this->href, array_values(array_filter($this->rels, fn($r) => $r !== $rel)), $this->attributes);
    }

    public function withAttribute(string $attribute, string|\Stringable|int|float|bool|array $value): static
    {
        return new static($this->href, $this->rels, [...$this->attributes, $attribute => $value]);
    }

    public function withoutAttribute(string $attribute): static
    {
        $attrs = $this->attributes;
        unset($attrs[$attribute]);
        return new static($this->href, $this->rels, $attrs);
    }
}

// --- Build a simple navigation link ---
$next = (new Link('/articles?page=2'))
    ->withRel('next')
    ->withAttribute('type', 'text/html')
    ->withAttribute('hreflang', 'en');

printf("href: %s\n", $next->getHref());
printf("rels: %s\n", implode(', ', $next->getRels()));
printf("type: %s\n", $next->getAttributes()['type']);
printf("templated: %s\n\n", $next->isTemplated() ? 'yes' : 'no');

// --- URI Template link ---
$itemLink = (new Link('/users/{id}/posts{?page}'))
    ->withRel('item')
    ->withAttribute('title', 'User posts');

printf("templated href: %s\n", $itemLink->getHref());
printf("is_templated: %s\n\n", $itemLink->isTemplated() ? 'yes' : 'no');

// --- Immutability: withRel returns new instance ---
$canonical = (new Link('https://example.com/article-1'))->withRel('canonical');
$also = $canonical->withRel('alternate');
printf("canonical rels: %s\n", implode(', ', $canonical->getRels()));
printf("also rels:      %s\n", implode(', ', $also->getRels()));
