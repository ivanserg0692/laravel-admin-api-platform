# Dev Notes

## First Run

```bash
cp .env.example .env
composer install
npm install
./vendor/bin/sail up -d
./vendor/bin/sail artisan key:generate
./vendor/bin/sail artisan migrate
```

## Daily Workflow

```bash
./vendor/bin/sail up -d
npm run dev
```

## Quality Checks

```bash
./vendor/bin/sail artisan test
./vendor/bin/sail artisan route:list
./vendor/bin/sail artisan about
```

## Stop Environment

```bash
./vendor/bin/sail down
# use with caution - removes database volume
./vendor/bin/sail down -v
```
