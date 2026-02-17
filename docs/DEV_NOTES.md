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

## Redis Checks

```bash
# list keys with TTL in the first namespace
docker compose exec -T redis redis-cli -n 1 --scan | while read k; do echo "$k => $(docker compose exec -T redis redis-cli -n 1 TTL "$k")"; done
```

## Stop Environment

```bash
./vendor/bin/sail down
# use with caution - removes database volume
./vendor/bin/sail down -v
```
