# AGENTS.md — dev-article

Project instructions for every agent working in this repository. Read this fully before doing anything. Workflow and delegation are defined by the orchestrator's prompt, not here.

## 1. Project

`dev-article` is an article platform for developers (school assignment).

- **Stack:** Laravel 13 + Breeze (Blade), Tailwind CSS, Vite, Pest/PHPUnit.
- **Model:** `Artikel` (`judul`, `konten`, `gambar`). Markdown rendering and a reading-time accessor already exist.
- **Existing features:** article list/detail, create/edit/delete, search (`q`), sort (`latest` / `oldest` / `title_asc`), pagination (6 per page), Breeze auth and profile.
- **Features**: Implement the features in FEATURE.md

## 2. Mission

Rebuild the whole site UI to match `references/` exactly, extend the same design to every page without a reference, and make articles accessible only to logged-in users.

1. **Pixel-faithful UI.** Pages with a reference must match it exactly: layout, spacing, typography, colors, radii, shadows, icons, component states, responsive behavior. Do not improve or reinterpret.
2. **Design system first.** Put the tokens (colors, fonts, type scale, spacing, radii, shadows, breakpoints) in `tailwind.config.js` and shared Blade components. No one-off hard-coded values.
3. **Consistent everywhere.** Login, register, password reset/confirm, verify email, profile, article list/detail/create/edit, delete confirmation, empty states, validation errors, flash messages, 403/404/419/500 pages, nav and footer must all look like one product.
4. **Articles require login.**
   - All article routes (list, detail, create, edit, update, delete, images) sit behind `auth`.
   - Guests go to `login` and return to the page they wanted after logging in.
   - `/` redirects guests to `login` and authenticated users to the article list. Build a public landing page only if `references/` has one.
   - No article titles, excerpts, or content visible to guests anywhere.
   - Login and register stay public; logged-in users are redirected away from them.
5. **Don't break what works:** search, sort, pagination (with `withQueryString()` so `q` and `sort` survive paging), image upload, Markdown, profile.

## 3. `references/`

- Single source of truth for visual design.
- Before touching UI, inventory the folder and map each reference to its route/view. If two references conflict, stop and report it. Do not guess.
- Never invent visual details a reference already answers.

## 4. Coding Standards

- Laravel and PSR-12 conventions, thin controllers.
- Blade components (`<x-...>`) instead of copy-pasted markup; Tailwind tokens instead of inline styles.
- Use `findOrFail` or route-model binding, never bare `find()`. Use Form Requests where validation is touched.
- Keep UI copy in one language per screen, consistent with the existing app.
- Accessibility: semantic HTML, labels on inputs, visible focus, good contrast, alt text.
- No new dependencies unless justified. Never commit `.env`, secrets, or `node_modules`.

## 5. Definition of Done

- [ ] Every referenced page matches its reference exactly.
- [ ] Every other page has a new reference and matches it.
- [ ] One design system in Tailwind config and shared components.
- [ ] Articles are inaccessible to guests on every route, covered by tests.
- [ ] Existing features still work.
- [ ] `npm run build` and `php artisan test` pass.
- [ ] Playwright suite passes and `docs/qa-report.md` exists.

## 6. Rules for All Agents

- Stay in your role.
- If something is ambiguous, ask the orchestrator instead of guessing.
- Never claim something works without running it.
- Keep changes scoped to the mission; note unrelated problems in your report instead of fixing them.
