# FEATURE.md — 10 Features Worth Adding

Project: `dev_article` — Laravel 13 + Breeze article app (`Artikel`: `judul`, `konten`, `gambar`).
Current state: public list/detail (`/` + `/artikel`), auth-gated create/edit/delete, search by `q`, sort (`latest`/`oldest`/`title_asc`), 6-per-page pagination, Markdown rendering + reading-time accessor. No ownership, no slugs, no taxonomy, no comments.

This list is ordered roughly by value/effort (highest leverage first).

---

## 1. Article Ownership + Authorization Policies
**Gap:** Any logged-in user can edit/delete any article. `artikels` has no `user_id`, no `ArtikelPolicy`, routes only check `auth`.
**Add:**
- `user_id` foreign key on `artikels`, set on `store()` from `auth()->id()`.
- `ArtikelPolicy` (`update`, `delete`) + `@can` in Blade + `authorize()` / `->can()` on routes.
- Optional admin role to moderate all articles.
**Why:** Biggest correctness/security gap. Required before any public deployment.

## 2. SEO Slugs Instead of Bare IDs
**Gap:** URLs are `/artikel/{id}`. No slug column, no route-model binding by slug.
**Add:**
- `slug` unique column, auto-generated from `judul` (with uniqueness suffix), regenerated on title change.
- Route `/artikel/{artikel:slug}`, `getRouteKeyName()` or implicit binding.
- Keep old ID URLs redirecting (301) to slug URLs.
**Why:** SEO, shareability, matches every real article platform.

## 3. Categories (and Tags)
**Gap:** No taxonomy. Only free-text search over `judul`/`konten`.
**Add:**
- `categories` table + `category_id` on articles (or many-to-many `tags` + pivot).
- Filter UI on index (`?category=`), category pages, validation on create/edit forms.
- Seed a handful of default categories.
**Why:** Discoverability. Unblocks related-articles and nicer navigation.

## 4. Draft / Published / Scheduled Workflow
**Gap:** `store()` publishes immediately. No `status`, no `published_at`.
**Add:**
- `status` enum (`draft`, `published`) + nullable `published_at`.
- Public index/detail only show published + due items; dashboard shows own drafts.
- Optional scheduled publishing via queue (`published_at` in the future).
**Why:** Real editorial workflow; prevents half-finished posts going live.

## 5. Fix Missing-Record Handling + Form Requests
**Gap:** `show/edit/update/destroy` use `Artikel::find($id)` with no null check — a bad ID fatals instead of 404. Validation is inline and inconsistent (`gambar` max 10000 on store vs 4096 on update).
**Add:**
- `findOrFail()` / implicit route-model binding + custom 404 view.
- `StoreArtikelRequest` / `UpdateArtikelRequest` with shared rules and Indonesian messages.
**Why:** Reliability + UX. Small change, removes a whole class of 500s.

## 6. Storage Cleanup on Delete + Consistent Image Rules
**Gap:** `destroy()` deletes the row but leaves the file in `gambars/` on the `public` disk. `update()` deletes the old file, `destroy()` does not.
**Add:**
- Delete associated image in `destroy()` (model `deleting` event or observer so it works everywhere).
- Unify image rules (one size limit, `webp` support, dimension cap), resize/compress on upload.
- Default placeholder when `gambar` is null.
**Why:** Stops orphaned files from filling disk; consistent authoring experience.

## 7. Better Search + Pagination UX
**Gap:** `index()` paginates 6 but drops `?q=`/`?sort=` on page 2+ (no `withQueryString()`), search is bare `LIKE %...%` with no ranking.
**Add:**
- `->withQueryString()` / `->appends($request->query())`.
- Empty-state view ("no results for X"), result count, preserved sort dropdown.
- Later: MySQL fulltext index or Laravel Scout for relevance.
**Why:** Current search actively breaks when paging. One-line fix + polish.

## 8. Comments (Polished Minimum)
**Gap:** No engagement at all — readers can't respond.
**Add:**
- `comments` table (`artikel_id`, `user_id`, `body`, timestamps), auth-gated create, owner-moderated delete.
- Paginated list on detail page, Markdown-safe rendering (reuse `konten_html` approach), spam guard (rate limit + validation).
**Why:** Highest-value engagement feature; keeps scope small if you skip threading/reactions for v1.

## 9. Reading Experience: Related Articles, Sharing, RSS/Sitemap
**Gap:** Detail page is a dead end. No related posts, no meta tags, no feed.
**Add:**
- Related articles (same category/tags, latest 3) on `artikel.detail`.
- Open Graph / Twitter meta + canonical slug URL, share buttons.
- RSS feed (`/feed`) + `sitemap.xml` for published articles.
**Why:** Retention + SEO for little code. `reading_time` accessor already exists — surface it in the UI too.

## 10. Test Coverage for Artikel CRUD
**Gap:** `tests/` is stock Breeze/Pest scaffolding; zero coverage of search, sort, validation, image upload, auth gates.
**Add:**
- Feature tests: guest can list/view, guest redirected from create/store/edit/update/destroy, auth user CRUD happy path, validation failures, search + sort, image stored/deleted, 404 on bad slug/ID.
- Factory for `Artikel` (faker `judul`/`konten`) to support the above.
**Why:** Every feature above (policies, slugs, statuses) needs a safety net. Cheapest time to add it is now while the controller is still ~140 lines.

---

### Suggested build order
1. #5 (404 + Form Requests) + #6 (storage cleanup) — stability, ~1 session
2. #1 (ownership + policies) + #2 (slugs) — foundation for everything else
3. #4 (draft/published) + #3 (categories/tags)
4. #7 (search/pagination) + #9 (related/RSS/sitemap)
5. #8 (comments)
6. #10 (tests) — ideally interleaved with each step above
