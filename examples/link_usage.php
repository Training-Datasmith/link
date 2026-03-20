<?php

declare(strict_types=1);

/**
 * Example: Building and consuming PSR-13 web links.
 *
 * PSR-13 links represent typed relationships between resources (RFC 8288).
 * Common uses: pagination links in APIs, canonical URLs, prefetch hints,
 * HAL/JSON-LD hypermedia representations.
 */

use Psr\Link\Evolvable_Link_Interface;
use Psr\Link\Evolvable_Link_Provider_Interface;
use Psr\Link\Link_Interface;
use Psr\Link\Link_Provider_Interface;

// --- Minimal evolvable link implementation ---

final class Web_Link implements Evolvable_Link_Interface
{
    /** @param string[] $rels */
    /** @param array<string, string|string[]> $attributes */
    public function __construct(
        private readonly string $href,
        private readonly array  $rels       = [],
        private readonly array  $attributes = [],
    ) {}

    public function get_href(): string       { return $this->href; }
    public function is_templated(): bool     { return str_contains($this->href, '{'); }
    public function get_rels(): array        { return $this->rels; }
    public function get_attributes(): array  { return $this->attributes; }

    public function with_href(string|\Stringable $href): static
    {
        return new self((string) $href, $this->rels, $this->attributes);
    }

    public function with_rel(string $rel): static
    {
        if (in_array($rel, $this->rels, true)) {
            return $this;
        }
        return new self($this->href, [...$this->rels, $rel], $this->attributes);
    }

    public function without_rel(string $rel): static
    {
        return new self($this->href, array_values(array_filter($this->rels, fn($r) => $r !== $rel)), $this->attributes);
    }

    public function with_attribute(string $attribute, string|\Stringable|int|float|bool|array $value): static
    {
        return new self($this->href, $this->rels, array_merge($this->attributes, [$attribute => $value]));
    }

    public function without_attribute(string $attribute): static
    {
        $attrs = $this->attributes;
        unset($attrs[$attribute]);
        return new self($this->href, $this->rels, $attrs);
    }
}

// --- Building a paginated API response with navigation links ---

function build_pagination_links(
    string $base_url,
    int $current_page,
    int $total_pages,
): array {
    $links = [];

    if ($current_page > 1) {
        $links[] = (new Web_Link("{$base_url}?page=" . ($current_page - 1)))
            ->with_rel('prev');
    }

    if ($current_page < $total_pages) {
        $links[] = (new Web_Link("{$base_url}?page=" . ($current_page + 1)))
            ->with_rel('next');
    }

    $links[] = (new Web_Link("{$base_url}?page={page}"))
        ->with_rel('page')
        ->with_attribute('type', 'application/json');

    return $links;
}

// --- Rendering links as HTTP Link headers ---

function render_link_header(Link_Provider_Interface $provider): string
{
    $parts = [];
    foreach ($provider->get_links() as $link) {
        $href = $link->get_href();
        $part = "<{$href}>";
        foreach ($link->get_rels() as $rel) {
            $part .= "; rel=\"{$rel}\"";
        }
        foreach ($link->get_attributes() as $name => $value) {
            $part .= "; {$name}=\"{$value}\"";
        }
        $parts[] = $part;
    }
    return implode(', ', $parts);
}

// Demo
$pagination_links = build_pagination_links('https://api.example.com/users', 3, 10);
foreach ($pagination_links as $link) {
    echo $link->get_href() . ' [' . implode(', ', $link->get_rels()) . "]\n";
}
// https://api.example.com/users?page=2 [prev]
// https://api.example.com/users?page=4 [next]
// https://api.example.com/users?page={page} [page]
