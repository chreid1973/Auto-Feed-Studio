# Extractor Architecture

- Built-in PHP Readability (DOMDocument + heuristics)
- Fallback: Open Graph/meta extraction; `<article>` and `<main>` density
- Sanitization: allowlists; strip scripts/iframes except safe list
- Optional external adapter (Mercury/Diffbot-like): endpoint + headers
- Cache: transient or custom table keyed by URL (TTL configurable)
