# BD HOTEL — Hotel Booking Management System

A fully-responsive, modern PHP 8 + MySQL hotel booking system with a luxury editorial UI, secure authentication, admin dashboard, gallery, reviews, contact form and full booking flow.

## Tech Stack
- **Backend**: PHP 8+ (PDO + prepared statements everywhere)
- **Database**: MySQL 5.7+ / MariaDB 10+
- **Frontend**: HTML5, CSS3, Bootstrap 5.3, jQuery 3.7, AOS 2.3, Swiper 11, Font Awesome 6
- **Local server**: XAMPP / MAMP / LAMP / any PHP-capable host

## Quick Start (XAMPP)

1. **Copy** the entire `BD-HOTEL/` folder into `C:/xampp/htdocs/` (Windows) or `/Applications/XAMPP/htdocs/` (macOS).
2. Start **Apache** and **MySQL** in XAMPP.
3. Open [http://localhost/phpmyadmin](http://localhost/phpmyadmin) → click **Import** → choose `BD-HOTEL/database/bdhotel.sql` → **Go**. This creates the `bd_hotel` database, all tables, and seeds admin + rooms + gallery + reviews.
4. (Optional) If your MySQL uses a non-default user/password, edit `BD-HOTEL/database/db_connect.php`:
   ```php
   const DB_USER = 'root';
   const DB_PASS = '';        // put your MySQL password here
   ```
5. Visit [http://localhost/BD-HOTEL/](http://localhost/BD-HOTEL/) — the home page should load.

## Default Credentials

| Role  | URL                             | Email                 | Password  |
| ----- | ------------------------------- | --------------------- | --------- |
| Admin | `/BD-HOTEL/admin/login.php`     | `admin@bdhotel.com`   | `Admin@123` |
| User  | Register at `/BD-HOTEL/register.php` | — | — |

## Features

### Public Site
- Sticky **glassmorphism navbar** + full-screen hero slider (Swiper)
- Animated **booking widget**, editorial marquee, featured rooms grid
- Six room categories with images, price, capacity, amenities, description
- **Booking flow** with date validation, availability check (no overlap), success invoice + print
- **Gallery** with category filter and lightbox
- **Reviews slider**, statistics counters, FAQ accordion, special offer countdown
- **Contact form** stores messages in DB
- **Newsletter** signup
- Floating **WhatsApp / Call / Back-to-top** buttons
- **AOS** scroll animations, gold underline hover, ripple button effects

### User Account
- Register / Login / Logout with **bcrypt** password hashing (`password_hash` / `password_verify`)
- **Forgot password** flow with tokenised reset link (1-hour expiry)
- Profile page: booking history, cancel booking, change password, submit review

### Admin Panel (`/admin/`)
- Dashboard with 7 stat cards + recent bookings table
- Rooms CRUD (create, edit, delete)
- Bookings management with inline status change
- Reviews approval + delete
- Gallery bulk add + delete
- Contact messages with read/unread + delete
- Customers list
- Settings (name, phone, change password)

### Security
- **Prepared statements** everywhere (PDO with `ATTR_EMULATE_PREPARES = false`)
- **CSRF tokens** on every state-changing form
- **Password hashing** (bcrypt via `password_hash`)
- **Session hardening**: `HttpOnly`, `SameSite=Lax`, session id regeneration on login
- **XSS protection**: every output escaped with `e()` (which is `htmlspecialchars(..., ENT_QUOTES)`)
- **Overlapping-date availability check** on booking to prevent double-book

## File Structure

```
BD-HOTEL/
├── admin/
│   ├── _layout.php        (shared admin chrome)
│   ├── _footer.php
│   ├── dashboard.php
│   ├── login.php
│   ├── logout.php
│   ├── rooms.php
│   ├── bookings.php
│   ├── customers.php
│   ├── gallery.php
│   ├── messages.php
│   ├── reviews.php
│   └── settings.php
├── database/
│   ├── bdhotel.sql        (schema + seed)
│   └── db_connect.php     (PDO connection)
├── includes/
│   ├── config.php         (session, CSRF, auth, helpers)
│   ├── header.php
│   ├── navbar.php
│   └── footer.php
├── css/
│   ├── style.css          (theme)
│   ├── animation.css      (keyframes + AOS overrides)
│   └── responsive.css
├── js/
│   ├── script.js          (nav, counters, toasts, gallery)
│   ├── swiper.js          (sliders)
│   └── validation.js      (client-side validation)
├── images/
├── uploads/
├── index.php
├── rooms.php              (list + detail via ?id=)
├── booking.php            (POST handler)
├── invoice.php
├── gallery.php
├── about.php
├── services.php
├── contact.php            (contact + newsletter)
├── login.php
├── register.php
├── logout.php
├── forgot-password.php
├── reset-password.php
├── profile.php
└── README.md
```

## Customisation

- **Colours**: edit CSS variables in `css/style.css` `:root` (gold, dark, background).
- **Fonts**: `Cormorant Garamond` + `Manrope` are pulled from Google Fonts in `includes/header.php`.
- **Company details**: edit `includes/footer.php` and `contact.php`.
- **DB credentials**: `database/db_connect.php`.

## Notes on the "Forgot Password" flow

For simplicity the reset link is **shown on-screen** after you request it (development mode). To send it by real email in production, replace the block in `forgot-password.php` marked
```php
// In production: send this link by email.
```
with an SMTP send using PHPMailer or a service like SendGrid / Resend.

## License

MIT — free to use for personal and commercial projects.

---

> Crafted for BD Hotel — a quiet address for the discerning traveller.
