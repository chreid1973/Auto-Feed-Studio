# AutoFeed Intelligence — Roadmap & Validation Playbook (v0.1)

**Goal:** Ship a user-obsessed, rock-solid plugin/app that ingests 50+ feeds, extracts full text cleanly, strips ads/boilerplate, deduplicates stories, and produces smart, human-friendly digests — all with guardrails and clear validation gates.

---

## Phase 0 — Stabilize the Core (NOW)
**Scope**
- Activation, settings, Run Now flow
- Full‑text extractor (DOM heuristics) + ad/boilerplate stripping
- Caching + UTM + source credit
- Minimal, friendly UI

**Validation Gates (must pass before moving on)**
1. **Activation & Settings**
   - Plugin activates without notices/fatals
   - Saving settings persists across page reloads
2. **Extractor REST**
   - `GET /wp-json/autofeed/v1/extract?url=<article>` returns JSON with `content,title,canonical`
   - Median extraction time ≤ 3s (with cache warm ≤ 500ms)
3. **Ad/Boilerplate Cleanliness**
   - For test set (≥ 20 recent articles across 10 publishers): 
     - ≤ 2% of output contains “advert”, “sponsored”, “outbrain”, “taboola”, “newsletter”, “share this”
     - No comments loaders / author bios (when class-matched) in final content
4. **Import Quality**
   - Draft posts created with correct category/author/status
   - Featured image pulled (enclosure or first `<img>`)
   - “Skip if no full‑text” respected

**How to test (Quick commands)**
- **Manual:** Paste feed in Settings → Save → Run Now → Review posts
- **Curl:** `curl -sL "https://YOUR-SITE/wp-json/autofeed/v1/extract?url=ARTICLE_URL"`
- **Warm Cache:** Call the same twice; second response time should be fast

**Rollback**
- Feature flags for: Full‑text ON/OFF, Skip‑if‑no‑fulltext, Ad‑stripper aggressive mode
- Revert via plugin deactivation; content is stored as drafts

---

## Phase 1 — Feed Management & UX Delight
**Scope**
- Multi‑campaigns (collections): each with feeds, category, status, schedule
- Inline **Preview Full‑Text** (modal) per feed
- Onboarding wizard: paste 5–10 feeds, pick category, done

**Validation Gates**
- **Time-to-First-Import** ≤ 60 seconds from activation
- **No dead ends:** every screen has one primary CTA
- **SUS (System Usability Scale)** ≥ 80 from 3 testers

**Notes**
- Maintain a “Simple Mode” with one textarea + defaults
- All advanced options behind “Show Advanced”

---

## Phase 2 — Canonicalization & Deduplication
**Scope**
- URL canonicalization (strip utm/ref params; honor `<link rel=canonical>`)
- Content fingerprinting (SimHash or MinHash on cleaned text)
- Per‑campaign duplicate guard across feeds

**Validation Gates**
- 100‑article corpus with intentional dupes: ≥ 95% duplicate suppression
- Zero suppression for genuinely different stories (false positive rate ≤ 2%)

**Tests**
- Unit: hash stability when only links/images change
- Integration: import mixed feeds; verify single canonical post created

---

## Phase 3 — Clustering & Smart Digest
**Scope**
- Daily clustering of related stories (title + lead embeddings or shingles)
- Per‑cluster summary (rule‑based now; LLM optional/behind switch)
- Digest page (or email export) per campaign

**Validation Gates**
- Human audit on 50 clusters: ≥ 85% “same story” agreement
- Summaries ≤ 120 words; contain source count and most unique angle

**Outputs**
- “Today in Tech” page: sections per cluster with top source + summary

---

## Phase 4 — Signals: Sentiment, Stance, Velocity
**Scope**
- Lightweight sentiment/stance (lexicon-based fallback; model optional)
- Trendlines per tag/topic, with percent change vs. 7‑day baseline

**Validation Gates**
- Sentiment stability: same article parsed twice → same label ≥ 98%
- Velocity alerts don’t exceed 1 per hour per campaign

---

## Phase 5 — Publishing Brain
**Scope**
- Auto‑tagging via keyword list + simple NER
- Title rewrite suggestions (toggle; non-destructive)
- Human‑in‑the‑loop queue (Approve/Skip inside WP)

**Validation Gates**
- Auto‑tags F1 ≥ 0.75 on curated set
- Zero auto‑publish surprises with queue enabled

---

## Phase 6 — Compliance & Safety
**Scope**
- Respect robots.txt when fetching full‑text; cache headers honored
- Clear attribution + source link policy
- Paywall detection → fall back to summary, never circumvent

**Validation Gates**
- Robots‑blocked sites never scraped (unit tests with fixtures)
- Attribution present in 100% of posts

---

## Phase 7 — Telemetry (Opt‑In), Perf, Hardening
**Scope**
- Health panel: last run, items created, failures, slow sites
- Perf profiling: extraction p95 ≤ 5s uncached
- Backup/restore of settings/campaigns (JSON)

**Validation Gates**
- Health panel matches logs
- Import 500 items in under 10 minutes on mid‑tier host

---

# Test Corpus (Starter 20 URLs)
Use latest articles from:
- Ars Technica (`.article-guts`), The Verge (`.c-entry-content`), Engadget (`article`), Wired (`article-body-component`)
- TechCrunch (`article-content`), Gizmodo, The Guardian Tech, BBC Tech, AP Tech, CNBC Tech

Record for each:
- URL → ok/fail, extraction time, cleaned length, images found, ad‑hits (# removed)

---

# Developer Tooling

## Local WP Test Harness
- Docker (wp-env or compose) + WP‑CLI
- Scripted activation + feed seeding + “Run Now”
- Store debug.log and a JSON report per run

## Automated Checks (CI)
- **php -l** and PHPCS
- PHPUnit: utilities (canonicalize, hash, sanitizer)
- Integration: spin WP container, run importer on fixtures, diff snapshots

Example WP‑CLI snippets:
```bash
wp plugin activate autofeed-studio
wp option update afs_options "$(cat ./fixtures/options.json)"
wp cron event run afs_cron_ingest
wp post list --post_type=post --post_status=draft --format=json > out/posts.json
```

---

# Acceptance Criteria (User-Facing)

- New user can add 5 feeds and create first draft in ≤ 60s
- Preview Full‑Text before import works and matches final post 1:1
- No obvious ads, recirc, comments, or bios in output
- Attribution visibly present at end of every post
- Rollback is one click (deactivate) without data loss

---

# Feature Flags (per campaign)
- Full‑text ON/OFF
- Skip if no full‑text
- Aggressive ad stripping
- Deduplication
- Auto‑tagging
- Auto‑publish vs Queue

---

# Rollout Plan
1) **Alpha (internal)**: 10 feeds, 200 articles. Fix extractor/site rules.
2) **Beta (friends)**: 50 feeds, dedupe on, digest on. Gather UX feedback.
3) **Public**: docs, templates, support matrix. Safe defaults everywhere.

---

# Next Concrete Steps (This Week)
- [ ] Lock **Phase 0** gates: run the 20‑URL test set; capture a report
- [ ] Add site‑specific selectors map (e.g., Ars: `.article-guts`, Verge: `.c-entry-content`)
- [ ] Wire **Preview Full‑Text** modal in wp‑admin
- [ ] Add “Aggressive ad stripping” toggle (off by default) with our expanded rules

---

*This playbook is the contract. Each phase ships only when its gates pass. No cliff‑dives, only controlled, verified steps.*
