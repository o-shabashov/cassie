# Основные приницпы
1. Schemaless - чтобы не мигрировать каждый раз базу
2. Заранее определить какие поля у шопифая будут фасетами
3. Хранение кастомных фасетов в юзер базе


# Install

```shell
cp .env.example .env
composer i
./vendor/bin/sail up -d
./vendor/bin/sail artisan migrate
./vendor/bin/sail artisan migrate --env=testing
./vendor/bin/sail artisan db:seed
./vendor/bin/sail queue:listen
```

# Roadmap
## MVP
* [ ] Импорт данных
* [ ] GUI поиска (docsearch.js)

## Later
* [ ] Возможность указывать вес полям в постгрес драйвере
* [ ] Справляться с изменённой структурой в данных, будет ли работать поиск и индексация?
* [ ] Генерирование кастомных моделей в рантайме?
* [ ] Конвертирование схем между typesense и meilisearch

# Drivers

* Postgres by gin and extension pg_trgm
* Typesense
* Meilisearch
* ? Elastic
* ? Manged Elastic indexes by Postgres - Zombodb

# Метрики

## Postgres
### Размер индексов в полностью заполненных таблицах

### Скорость вставки данных

### Скорость индексации

### Скорость поиска


## Meilisearch
### Размер индексов в полностью заполненных таблицах

### Скорость вставки данных

### Скорость индексации

### Скорость поиска


## Typesense
### Размер индексов в полностью заполненных таблицах

### Скорость вставки данных

### Скорость индексации

### Скорость поиска
