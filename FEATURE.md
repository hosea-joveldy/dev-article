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

## 2. Categories (and Tags)
**Gap:** No taxonomy. Only free-text search over `judul`/`konten`.
**Add:**
- `categories` table + `category_id` on articles (or many-to-many `tags` + pivot).
- Filter UI on index (`?category=`), category pages, validation on create/edit forms.
- Seed a handful of default categories.
**Why:** Discoverability. Unblocks related-articles and nicer navigation.

## 3. Better Search + Pagination UX
**Gap:** `index()` paginates 6 but drops `?q=`/`?sort=` on page 2+ (no `withQueryString()`), search is bare `LIKE %...%` with no ranking.
**Add:**
- `->withQueryString()` / `->appends($request->query())`.
- Empty-state view ("no results for X"), result count, preserved sort dropdown.
- Later: MySQL fulltext index or Laravel Scout for relevance.
**Why:** Current search actively breaks when paging. One-line fix + polish.

## 4. Comments
**Gap:** No engagement at all — readers can't respond.
**Add:**
- `comments` table (`artikel_id`, `user_id`, `body`, timestamps), auth-gated create, owner-moderated delete.
- Paginated list on detail page, Markdown-safe rendering (reuse `konten_html` approach), spam guard (rate limit + validation).
**Why:** Highest-value engagement feature; keeps scope small if you skip threading/reactions for v1.

## 5. Reading Experience: Related Articles, Sharing, RSS/Sitemap
**Gap:** Detail page is a dead end. No related posts, no meta tags, no feed.
**Add:**
- Related articles (same category/tags, latest 3) on `artikel.detail`.
- Open Graph / Twitter meta + canonical slug URL, share buttons.
- RSS feed (`/feed`) + `sitemap.xml` for published articles.
**Why:** Retention + SEO for little code. `reading_time` accessor already exists — surface it in the UI too.
