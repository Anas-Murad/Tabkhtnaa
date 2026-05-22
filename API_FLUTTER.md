# Tabkhtnaa API (v1) — Flutter integration

## Base URL

`{APP_URL}/api/v1` — e.g. `http://10.0.2.2:8000/api/v1` (Android emulator) or `http://localhost:8000/api/v1`.

## Auth

**Laravel Sanctum** — Bearer token in `Authorization` header. Token field in login/register response: `data.access_token`.

### Public

| Method | Path | Body |
|--------|------|------|
| POST | `/auth/login` | `country_code`, `mobile`, `password` |
| POST | `/auth/register` | multipart: `name`, `email`, `mobile`, `country_code`, `residence_country_id`, `dob`, `gender`, `type` (`client`), `password`, `password_confirmation`, `profile_image` |
| GET | `/countries` | — |
| GET | `/languages` | — |
| GET | `/translate?lang=ar` | — |

### Protected (`auth:sanctum`)

| Method | Path |
|--------|------|
| POST | `/auth/update-profile` |
| GET | `/category/list` |
| GET | `/user/meals/list?lat=&long=&radius=30` |
| GET | `/user/meals/get?id=` |
| GET | `/user/chefs?lat=&long=` |
| GET | `/user/chef?id=` |
| GET/POST | `/user/cart/*` |
| GET/POST | `/user/orders/*` |
| GET/POST | `/addresses/*` |

Query or header: `lang` = `ar` | `en` (defaults to `ar`).

### Translations

`GET /translate` returns `data` as a **flat map** keyed by the `key` column in the `translates` table (not `q`). Values are strings for the active locale (`ar`, `en`, etc.).

Example:

```json
{
  "status": true,
  "data": {
    "welcome_back": "مرحباً بعودتك",
    "nav_home": "الرئيسية"
  }
}
```

`GET /languages` returns supported app locales: `{ code, name, native, rtl }`.

## Response shape

```json
{
  "status": true,
  "error_code": 0,
  "error_msg": "...",
  "data": {}
}
```

Paginated lists merge Laravel pagination keys at the top level (`data`, `current_page`, …).
