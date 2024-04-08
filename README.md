# ShoutboxChallenge

A [Docker](https://www.docker.com/)-based project for simple Shoutbox.
Made with [Symfony](https://symfony.com) web framework with [React Symfony-UX](https://ux.symfony.com/react)
for handling Shoutbox interaction. All that using [Mercure](https://mercure.rocks/spec) to broadcast data in real-time.


### In case of local development:

Provide `compose.override.yaml`, example:
```
services:
  php:
    build:
      context: .
      target: frankenphp_dev
    volumes:
      - ./:/app
      - ./frankenphp/Caddyfile:/etc/caddy/Caddyfile:ro
      - ./frankenphp/conf.d/app.dev.ini:/usr/local/etc/php/conf.d/app.dev.ini:ro
    environment:
      MERCURE_EXTRA_DIRECTIVES: demo
      XDEBUG_MODE: "${XDEBUG_MODE:-off}"
    extra_hosts:
      - host.docker.internal:host-gateway
    tty: true

  database:
    ports:
      - "5432"
```

## Getting Started

1. If not already done, [install Docker Compose](https://docs.docker.com/compose/install/) (v2.10+)
2. Run `docker compose build --no-cache` to build fresh images
3. Run `docker compose up --pull always -d --wait` to start the project
4. Run `docker exec -it shoutboxchallenge-php-1 npm install`
5. Run `docker exec -it shoutboxchallenge-php-1 npm run dev`
6. Open `https://localhost` in your favorite web browser and [accept the auto-generated TLS certificate](https://stackoverflow.com/a/15076602/1352334)
7. Run `docker compose down --remove-orphans` to stop the Docker containers.



To watch frontend part:
1. Run `docker exec -it shoutboxchallenge-php-1 npm watch`
