## Dev

<i>docker-compose.override.yaml</i><br/>

```yaml
services:
  php:
    volumes:
      - .:/var/www/html

  mariadb:
    ports:
      - 127.0.0.1:3306:3306

  clickhouse:
    ports:
      - 127.0.0.1:9000:9000

  rabbitmq:
    ports:
      - 127.0.0.1:8081:15672
```