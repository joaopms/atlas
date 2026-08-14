# Atlas

A Laravel application with multiple purposes. Custom-built for my needs.

## Inventory module

### Labels

For generating PDF labels, `spatie/laravel-pdf` is used. [Gotenberg](https://gotenberg.dev) can be used as the engine.

```dotenv
LARAVEL_PDF_DRIVER=gotenberg
GOTENBERG_URL=http://localhost:3000
```
