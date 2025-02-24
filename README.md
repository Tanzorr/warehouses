# currency_rate_api

This is a simple API that returns the exchange rate of a currency to another currency. It uses the [api.blockchain.com](https://api.blockchain.com/v3/exchange/tickers) API to get the exchange rates.
You can use this API to get the exchange rate of a currency to another currency.
Also you can change third party api and chose current pair in .env file.

## Installation
Clone form github
```bash
git clone https://github.com/Tanzorr/currency_rate_api.git

cd currency_rate_api

only if you want to use docker

docker-compose build

docker-compose up -d

docker exec -it currency_rate_api_web_1 bash

composer install

php bin/console doctrine:migrations:migrate

service cron start




```
