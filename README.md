# Main goals
1. Schemaless - avoid database migrations
2. Hardcode main product fields as a facets
3. Store custom product facets in the user db


# Install
1. First things first - please install [tanker](https://github.com/o-shabashov/tanker?tab=readme-ov-file#install)
2. Now, install cassie:
```shell
git clone https://github.com/o-shabashov/cassie
cd cassie
cp .env.example .env
composer i
./vendor/bin/sail up -d
./vendor/bin/sail artisan migrate
./vendor/bin/sail artisan migrate --env=testing
./vendor/bin/sail artisan migrate --database user_db_template
./vendor/bin/sail artisan db:seed
```

# Roadmap
## MVP
* [X] Add basic Shopify api
* [X] Products data import by the GraphQL
* [ ] GUI minibar search
* [ ] GUI Search results Typesense
* [ ] GUI Search results Meilisearch
* [ ] Basic search features - synonyms, merchandise, etc.
* [ ] Client admin

## Later
* [ ] Tariffs and money checkout
* [ ] Ability to set columns weight for the PgSQL driver
* [ ] Survive data structure changes - is search and indexation will work?
* [ ] Generate custom models in runtime?
* [ ] Convert schemas typesense <=> meilisearch
* [ ] Move shopify-extensions to the shopify-admin php project
* [ ] Add ability to re-install search result page and search minibar in the admin by the button

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
