## Requirements
- [Docker](https://www.docker.com/)
- [mkcert](https://github.com/FiloSottile/mkcert)
- Luck

## Install on Local environment

1. Install composer packages
```shell
docker run --rm \
    --pull=always \
    -v "$(pwd)":/opt \
    -w /opt \
    laravelsail/php82-composer:latest \
    bash -c "composer install"
```
2. Copy `.env.example` to `.env`
```shell
cp .env.example .env
```
3. Generate local SSL certificates
```shell
mkcert -install

mkcert -key-file server.key -cert-file server.pem '*.rss-feed-bot.test' rss-feed-bot.test localhost 127.0.0.1 ::1
```
3. Build container images
```shell
./vendor/bin/sail build --no-cache
```
4. Run containers
```shell
./vendor/bin/sail up -d
```
5. Generate key
```shell
./vendor/bin/sail artisan key:generate
```
6. Fill variables in `.env `

7. Install webhook
```shell
./vendor/bin/sail artisan telebot:webhook
```
