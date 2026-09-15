# INFOROVA — Content & Advertisement Website

Standalone PHP/MySQL content website intended to run separately from BDXLink while supporting a secure, signed return flow.

## Included
- Responsive light-blue editorial design
- Home, categories, search, article pages
- About, Contact, Privacy Policy, Terms, Disclaimer, Cookie Policy
- MySQL-backed articles/categories/pages/settings
- Admin login and content management
- Clearly labeled ad slots: top, in-article, bottom
- `robots.txt`, sitemap endpoint and security headers
- Signed short-lived BDXLink return-token validation
- No forced ad clicks, fake traffic, automatic ad interaction, or reviewer-specific cloaking
- Separate project boundary: this site does not need the BDXLink database

## Setup
1. Import `sql/schema.sql` into MySQL.
2. Set `DB_HOST`, `DB_NAME`, `DB_USER`, `DB_PASS`, and optionally `DB_PORT`.
3. Change `APP_SECRET` in `config/app.php` to a long random secret.
4. Change the seeded admin password immediately.
5. Configure ad-network code in Admin → Settings.
6. Configure the BDXLink verified return URL only after the BDXLink endpoint is ready.

Seed admin: `admin@inforova.in` / `ChangeMe123!` — change immediately.

## BDXLink integration contract
BDXLink should create a signed token containing only non-sensitive claims such as `link_id`, `nonce`, `issued_at`, and `exp`. INFOROVA validates the signature and expiry before showing its verified Continue state. The final destination remains controlled by BDXLink; INFOROVA does not receive or expose it to the browser.

The current `continue.php` demonstrates the validation boundary. In production, use a shared secret stored in environment variables on both systems, or preferably an asymmetric signing scheme where INFOROVA stores only a public key.

## Advertising
The site provides normal ad inventory and policy pages. Approval depends on the specific ad network's policies; no implementation can guarantee approval by every network.

## Extra content seed
After importing `sql/schema.sql`, optionally import `sql/seed_content.sql` for additional categories, policy pages and articles. The seed uses `INSERT IGNORE` so it is safe to re-run for the included seed records.

## Production notes
- Replace the seed admin password before exposing the admin panel.
- Set `ED_SHARED_SECRET` in the environment; do not commit a real secret.
- For a stronger cross-domain integration, BDXLink can sign tokens with an asymmetric private key and INFOROVA can verify them with a public key. The included HMAC path is a simple starter contract.
- Ad network approval cannot be guaranteed by software. Follow each network's content, traffic and placement policies.
