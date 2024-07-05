default: up

build:
    docker-compose build

up:
    docker-compose up -d

down:
    docker-compose down

rebuild:
    just down
    just build
    just up

logs:
    docker-compose logs -f

shell:
    docker-compose exec php bash
