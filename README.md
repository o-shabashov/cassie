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

* Typesense
* Meilisearch
* Elastic
* Manged Elastic indexes by Postgres - Zombodb
