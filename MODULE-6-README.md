# Module 6 — Blog / CMS

Part of **Field Forecast**. Builds on Modules 1–5.

## What this module delivers

- **Blog:** Articles (categories, free-text tags with auto-create, featured images, per-article SEO fields, view counter), one level of threaded Comments with a moderation queue (staff comments auto-approve), search, related-posts (category-first, tag-fallback)
- **FAQ** — grouped by free-text category, accordion UI
- **Testimonials** — homepage carousel source
- **Advertisements** — placement-keyed (not an enum) so new ad slots don't need a migration
- **Pages** — static CMS pages (About, Privacy Policy, Terms, Responsible Use — seeded with clearly-marked placeholder legal text, see warning below)
- **Menus** — header/footer, one level of nesting
- **Slides** — the brief's separate "Manage Banners" and "Manage Sliders" are unified into one `Slide` model with a `placement` key (`homepage_hero` vs `homepage_banner`); see design notes
- **Newsletter** — subscribe/unsubscribe (token-based, no login required), admin subscriber list + CSV export
- **Homepage** — Latest Articles, Testimonials, and Newsletter signup are now live (the last three placeholder sections since Module 1)
- **Admin panel** — full Manage screens for all of the above, added to the sidebar under a new "Blog & CMS" section

## ⚠️ Two things I should have flagged earlier, fixed now

**1. `tests/TestCase.php` and `phpunit.xml` were never created.** Every test file since Module 1 extends `Tests\TestCase` and none of them could actually run without it. In a real project scaffolded via `composer create-project laravel/laravel` or `laravel new`, these ship by default — I've been assuming that skeleton exists and you're dropping these `app/`, `database/`, `resources/`, `routes/`, `tests/` files into it. If that's correct, you likely already have your own copies of both files and can ignore mine (or diff them). If you've instead been assembling the project file-by-file from what I've generated, these were a real gap — they're included now.

**2. A real bug in `UserFactory`:** it calls `$user->assignRole(User::ROLE_REGISTERED)`, which throws `RoleDoesNotExist` unless that role has already been seeded. Every test file since Module 2 that calls `User::factory()->create()` under `RefreshDatabase` (which doesn't auto-seed) would have failed immediately — except `RegistrationTest` and `AdminPanelTest`, which happened to seed roles themselves. Fixed two ways: `UserFactory` now `firstOrCreate`s the role defensively, and `tests/TestCase.php` now seeds `RolesAndPermissionsSeeder` for every test with a migrated database, so no individual test needs to remember to do it.

I'm noting this here rather than quietly patching it, because it means **the test suites from Modules 1–5 have not actually been verified to run** — I wrote them, they're syntactically consistent, but this environment has no PHP binary, so nothing has executed end-to-end yet. Please run `php artisan test` before treating any module's test suite as passing.

## Folder structure (this module's additions)

```
app/
  Models/ (Category, Tag, Article, Comment, Faq, Testimonial, Advertisement,
    Page, Menu, MenuItem, Slide, NewsletterSubscriber)
  Services/ (ArticleService, CommentService, NewsletterService)
  Http/Controllers/ (ArticleController, CommentController, PageController,
    FaqController, NewsletterController)
  Http/Controllers/Admin/ (CategoryController, TagController, ArticleController,
    CommentController, FaqController, TestimonialController,
    AdvertisementController, PageController, MenuController, SlideController,
    NewsletterController)
database/
  migrations/ (categories, tags, articles+article_tag, comments, faqs,
    testimonials, advertisements, pages, menus+menu_items, slides,
    newsletter_subscribers)
  factories/ (Category, Article)
  seeders/ (CategoryTag, ArticleDemo, FaqDemo, TestimonialDemo, PageDemo, MenuDemo)
resources/views/
  articles/ (index, show), pages/show.blade.php, faq/index.blade.php,
  newsletter/unsubscribed.blade.php
  admin/ (categories, tags, articles, comments, faqs, testimonials,
    advertisements, pages, menus, slides, newsletter)
routes/cms.php
tests/TestCase.php, phpunit.xml
tests/Feature/BlogCmsTest.php
```

## Setup additions

```bash
php artisan migrate
php artisan db:seed --class=Database\\Seeders\\CategoryTagSeeder
php artisan db:seed --class=Database\\Seeders\\ArticleDemoSeeder
php artisan db:seed --class=Database\\Seeders\\FaqDemoSeeder
php artisan db:seed --class=Database\\Seeders\\TestimonialDemoSeeder
php artisan db:seed --class=Database\\Seeders\\PageDemoSeeder
php artisan db:seed --class=Database\\Seeders\\MenuDemoSeeder
php artisan storage:link   # required for featured images / avatars / slides to be publicly reachable
```

## Design notes

- **Article/Page body is stored and rendered as plain text** (`nl2br(e($body))`), not raw HTML — no WYSIWYG editor, no `{!! $body !!}` of unescaped content. This is a deliberate security default: allowing raw HTML from an Editor-authored field is a stored-XSS surface if that account is ever compromised, and a proper rich-text/Markdown editor with sanitization is a bigger addition than this module's scope. Noted as a clear follow-up if formatted article bodies are wanted.
- **Why Slides unifies Banners and Sliders:** both are "image + optional link + display order" — the only real difference the brief implies is *where* they render. One table with a `placement` key scales to new positions without new migrations; two near-identical tables/controllers would just be duplicated CRUD for no behavioral gain.
- **Free-text tag input on the article form** (comma-separated, auto-creating unrecognized tags) rather than a multi-select pre-populated from `Manage Tags` — keeps the editorial workflow to one screen instead of forcing a tag to be created elsewhere first. `Manage Tags` still exists for cleanup/renaming.
- **Legal pages are seeded with placeholder text, clearly marked as such** (`PageDemoSeeder`'s docblock and the body copy itself say so). Do not treat this as reviewed legal content — replace before any real launch, especially given this platform's odds/predictions content sits adjacent to gambling-adjacent regulatory territory in many jurisdictions.
- **Newsletter unsubscribe requires no login** (token in the URL, per standard email-marketing practice) — this is intentional, not a missing auth check.

## What's intentionally deferred

- Rich-text/Markdown article editing with sanitized HTML output
- Ad impression/click tracking (Advertisements currently just render — no analytics)
- Multi-level (more than one nesting level) menu trees
- A dynamic drag-and-drop menu/slide reordering UI (currently `display_order` is a plain number field)

## Next module

**Module 7 — External Live API Integration**: Fixture/Odds/Standings/Live Scores API service layer (the seam `MatchRepositoryInterface` and `OddsRepositoryInterface` were built for since Modules 2–3), retry logic, rate limiting, graceful failure, queue-based sync jobs, and the "Manage API Keys" admin screen. Say "continue" when ready.
