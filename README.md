# Install

```shell
cp .env.example .env
composer i
./vendor/bin/sail up -d
./vendor/bin/sail artisan db:seed
./vendor/bin/sail queue:listen
```

# Roadmap

* [ ] Импорт данных
* [ ] GUI поиска (docsearch.js)
* [ ] Возможность указывать вес полям в постгрес драйвере
* [ ] Справляться с изменённой структурой в данных, будет ли работать поиск и индексация?
* [ ] Генерирование кастомных моделей в рантайме?


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
