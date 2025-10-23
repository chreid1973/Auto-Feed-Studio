# AutoFeed Studio — Roadmap

> A single, delightful WordPress plugin that ingests feeds **and** extracts full‑text — designed for zero‑friction setup and best‑in‑class UX.

## Vision
- First‑run magic: paste a URL → auto‑discover feeds → preview → import → posts appear in Draft.
- Everyday: one screen to manage campaigns; instant previews; one‑click run; clear history.

## UX Principles
Progressive disclosure • Inline feedback • Undo over confirm • Sensible defaults • No dead ends • Accessible.

## IA
- Home / Campaigns (cards: Run, Preview, Edit, stats)
- Campaign Editor (drawer): Details • Feeds • Content • Preview
- Extractor Settings (built‑in or external)
- Activity / Logs

## Versions
### v1.0 (MVP)
- Unified plugin (ingest + extractor) with built‑in endpoint `/wp-json/autofeed/v1/extract`
- Simple Mode wizard + Campaigns board
- Preview (5 items), Run, Edit; per‑feed limits; duplicate protection
- Full‑text toggle; Skip if no full‑text; source link; featured image

### v1.1
- Test Import (no publish); run history; batch revert; feed discovery; dark mode

### v1.2
- Readability tuning; canonical capture; basic content templating

### v1.3
- CRON UI; quiet hours; per‑feed category mapping

### v1.4
- Throttling; retries; extractor cache; export/import campaigns

### v1.5
- Multisite; role matrix; webhooks; optional telemetry (opt‑in)

## Success Metrics
- Time‑to‑first‑post < 2 minutes from install
- Preview → Run conversion > 70%
- Error rate < 2% across common feeds
