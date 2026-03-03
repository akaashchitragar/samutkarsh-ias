# Samutkarsh IAS Academy - Official Website

Premier Civil Services Coaching Institute in Karnataka, established in 2016. Inspiring new generations of Civil Servants rooted in Bharatiya Ethos with 16+ centers across North Karnataka and Bengaluru.

## Tech Stack

- **Backend / Frontend:** PHP (server-rendered)
- **Styling:** Bootstrap 5, custom CSS
- **Icons:** Remix Icons
- **Database:** MySQL / MariaDB (mysqli)
- **Forms:** Native HTML forms with CSRF protection
- **Email:** EmailJS (optional, for quick contact)

## Prerequisites

- PHP 7.4+ with mysqli
- MySQL 5.7+ or MariaDB 10.3+
- Web server (Apache/Nginx) or PHP built-in server

## Setup

1. Clone the repo and point document root to the project folder.
2. Copy `includes/config.example.php` to `includes/config.php` and set `DB_HOST`, `DB_USER`, `DB_PASS`, `DB_NAME`.
3. Create the database and run `database/schema.sql` to create tables and seed centers/testimonials.
4. Ensure `includes/config.php` is not under version control (add to `.gitignore`).

## Main Routes

- **Home:** `index.php` — Hero, About, Courses, Admissions (inquiry + enrollment forms), Testimonials, Locations
- **Legal:** `terms.php`, `privacy-policy.php`, `refund-cancellation.php`
- **Form handlers:** `submit_inquiry.php`, `submit_contact.php`, `submit_enrollment.php` (POST only)

## License

© 2025 Samutkarsh Trust. All rights reserved.

## Support

For queries, contact:
- **Phone:** +91 96634 24767
- **Email:** contactsamutkarshias@gmail.com
- **Website:** [samutkarshias.in](https://samutkarshias.in)
