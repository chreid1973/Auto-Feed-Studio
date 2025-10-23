# AutoFeed Studio

**Turn your favorite feeds into living posts — instantly.**
Unified WordPress plugin that ingests RSS/Atom feeds *and* extracts full text with a built‑in extractor.

### Highlights
- One-screen campaign management (Run, Preview, Edit)
- Simple Mode (zero-config) + Advanced builder
- Built-in extractor endpoint: `/wp-json/autofeed/v1/extract` (external endpoints optional)
- Full-text posts with source attribution, featured images, and duplicate protection

### Quick Start
1. Zip the `plugin/` folder.
2. Upload the zip to WordPress → Plugins → Add New.
3. Activate **AutoFeed Studio**.
4. Go to **Settings → AutoFeed Studio** and paste feed URLs.
5. Click **Run Now**.

### Dev
- WP 5.8+ / PHP 7.4+ recommended (LTS path stays PHP 5.6+ compatible).
- See `docs/api-schema.md` and `docs/extractor-architecture.md`.
- CI auto-builds a distributable zip on git tag (see `.github/workflows/build.yml`).

### License
MIT — see `LICENSE`.
