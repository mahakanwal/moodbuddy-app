# MoodBuddy

Mood-based recommendations (products, songs, quotes, memes, games) with AI chat and webcam mood detection.
Built with PHP 8.2 + MySQL. Deploy target: **Render (Docker)** + a free cloud MySQL database.

> Why not Vercel? Vercel does not run PHP natively, and its serverless functions cannot keep PHP sessions
> or save uploaded files. Render runs this project as a normal PHP + Apache server, so everything works.

---

## Project structure

```
mood-buddy/
├── index.php, homepage.php, home.php     Landing + mood selection
├── mood_result.php, shop.php, music.php,
│   memes.php, games.php, webcam.php, chat.php,
│   cart.php, checkout.php, about.php, ...  Public pages (URLs unchanged)
├── actions/        Form / AJAX endpoints (add_to_cart, process_mood, save_mood)
├── includes/       Shared PHP (config, db, header, footer) - not web accessible
├── admin/          Admin panel, login/register, uploads/
├── games/          Mini games
├── assets/
│   ├── css/        site.css (inner pages), homepage.css, hero.css, vendor CSS
│   ├── js/         app.js (shared), hero.js, vendor JS
│   ├── img/ fonts/ media/
├── database/       schema.sql, sample_data.sql
├── docker/         Apache + PHP config for Render
├── Dockerfile, render.yaml, composer.json, .env.example
```

## 1. Rotate the old keys first (important)

The old code had these secrets hardcoded (and they are in the old Git history):
Gmail App Password, Gemini API key, Hugging Face token, YouTube API key.

- Gmail: Google Account > Security > App passwords > delete the old one, create a new one
- Gemini: https://aistudio.google.com/apikey > delete old key, create new
- Hugging Face: https://huggingface.co/settings/tokens > delete old token, create new (Read access)
- YouTube: Google Cloud Console > Credentials > regenerate, and restrict it to your Render domain (HTTP referrer)

Put the NEW values only in Render's Environment settings (never in code).

## 2. Push to a NEW GitHub repo

Do not reuse the old repo (its history contains the leaked keys).

```bash
cd mood-buddy
git init
git add .
git commit -m "MoodBuddy - deploy ready"
git branch -M main
git remote add origin https://github.com/<your-username>/mood-buddy.git
git push -u origin main
```

`vendor/` and `.env` are ignored by Git; Render installs Composer packages during the build.

## 3. Create a free MySQL database (TiDB Cloud - recommended)

1. Sign up at https://tidbcloud.com and create a **Starter (free)** cluster.
2. Open **SQL Editor**, run: `CREATE DATABASE moodbuddy;`
3. Select the `moodbuddy` database, paste and run `database/schema.sql`.
4. Optional: run `database/sample_data.sql` (sample products/songs/quotes using files already in `admin/uploads`).
   If you have your own XAMPP export (phpMyAdmin > Export), import that instead of the sample data.
5. Click **Connect** and note: Host, Port (4000), User, Password.

Aiven (https://aiven.io, free MySQL plan) also works; import the SQL with MySQL Workbench or HeidiSQL.

## 4. Deploy on Render

1. https://dashboard.render.com > **New > Blueprint** > select your repo (it reads `render.yaml`).
   (Or **New > Web Service** > your repo > Runtime: **Docker**.)
2. Add the Environment variables:

| Key | Value |
|---|---|
| `DB_HOST` | from TiDB/Aiven |
| `DB_PORT` | `4000` for TiDB (Aiven shows its own port) |
| `DB_NAME` | `moodbuddy` |
| `DB_USER` / `DB_PASS` | from TiDB/Aiven |
| `DB_SSL` | `true` |
| `ADMIN_EMAIL` | your email - this account becomes admin on login |
| `APP_URL` | `https://<your-service>.onrender.com` (add after first deploy) |
| `GEMINI_API_KEY` | new key (optional - AI quotes) |
| `HF_API_KEY` | new token (optional - AI chat; built-in fallback otherwise) |
| `YOUTUBE_API_KEY` | new key (optional - YouTube search on Music page) |
| `SMTP_USER` / `SMTP_PASS` | Gmail + new App Password (optional - verification and reset emails) |

3. Deploy. Open the URL, register with your `ADMIN_EMAIL`, log in, and the Admin link appears.

Without SMTP, new accounts are activated directly (no email step).

## 5. Run locally on XAMPP

1. Run `composer install` (or keep the included `vendor/` folder), then copy `.env.example` to `.env` and keep `DB_HOST=localhost`, `DB_USER=root`, `DB_SSL=false`.
2. Import `database/schema.sql` (and optionally `sample_data.sql`) in phpMyAdmin into a database named `moodbuddy`.
3. Open `http://localhost/mood-buddy/`.

## Notes

- **Uploads on Render free plan are temporary.** Files uploaded from the admin panel are deleted on each redeploy/restart.
  Files committed to Git (current `admin/uploads`) are always there. For permanent uploads, add a Render Disk (paid)
  mounted at `/var/www/html/admin/uploads`, or commit new files to the repo.
- Render free services sleep after ~15 minutes idle; the first visit after that takes ~30-50 seconds.
- The webcam needs HTTPS (Render provides it) and runs face detection in the browser (face-api.js), so no Python is needed.

## What changed in this version

- New homepage hero: clean layout, real counts from the database, mood preview card, fully responsive
- Files organised into `includes/`, `actions/`, `assets/`; inline CSS moved to `assets/css/site.css` and `assets/css/homepage.css`
- Removed unused files (test pages, old Python detector, duplicate admin page, duplicate/unused JS, CSS, images, fonts)
- Large images and the homepage video compressed; Composer reduced to PHPMailer only

- All secrets moved to environment variables (`config.php`, `.env.example`)
- Database connection supports cloud MySQL with SSL
- Docker + Render setup (`Dockerfile`, `docker/`, `render.yaml`) and `database/schema.sql`
- Webcam mood detection is now real (face expression analysis) instead of a random result, and it saves the mood
- AI chat: updated Hugging Face endpoint + keyword fallback so it always returns a mood
- AI quotes: updated Gemini model (`gemini-2.5-flash`) with database/built-in fallback and caching
- Results, memes and games pages now use the detected mood (previously always "happy"); fixed broken `snack.php` link
- Admin: content pages are admin-only (any user could delete content before); add meme/quote/game guard fixed
- Email verification/reset links use the real site URL instead of `localhost`; reset link is no longer exposed
- Product image path bug on edit fixed; file names sanitized on upload; PHP execution blocked in `admin/uploads`
