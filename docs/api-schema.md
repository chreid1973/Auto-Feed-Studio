# API Schema (Sketch)

## Endpoints
- `GET /wp-json/autofeed/v1/preview?feed=<url>` → `{ titles: string[] }` (latest 5)
- `GET /wp-json/autofeed/v1/extract?url=<articleUrl>` → `{ content, title?, lead_image?, canonical? }`
- `POST /wp-json/autofeed/v1/run` `{ campaign_id? }` → `{ created, skipped, errors, stats }`
- `POST /wp-json/autofeed/v1/test` `{ campaign_id }` → dry‑run report

## Auth
- Admin-only; nonce on admin actions; capability `manage_options`.
