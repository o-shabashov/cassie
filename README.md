# Main goals
1. Schemaless - avoid database migrations
2. Hardcode main product fields as a facets
3. Store custom product facets in the user db


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
* [ ] Products data import by the GraphQL
* [ ] GUI search (docsearch.js)
* [ ] Client admin
* [ ] Tariffs and money checkout

## Later
* [ ] Ability to set columns weight for the PgSQL driver
* [ ] Survive data structure changes - is search and indexation will work?
* [ ] Generate custom models in runtime?
* [ ] Convert schemas typesense <=> meilisearch

# Drivers

* Postgres by gin and extension pg_trgm
* Typesense
* Meilisearch
* ? Elastic
* ? Manged Elastic indexes by Postgres - Zombodb
* ? Manticore

# Metrics

## Postgres
### Index size in fully populated tables

### Data insertion speed

### Indexing speed

### Search speed

## Meilisearch
### Index size in fully populated tables

### Data insertion speed

### Indexing speed

### Search speed


## Typesense
### Index size in fully populated tables

### Data insertion speed

### Indexing speed

### Search speed
