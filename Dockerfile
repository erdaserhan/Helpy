FROM registry.bruxellesformation.be/brufor/images/php:8.1-alpine

COPY . /app

WORKDIR /app

ENTRYPOINT [ "/app/entrypoint.sh" ]

CMD [ "php", "-S", "0.0.0.0:80", "-t", "/app/public", "/app/public/index.php" ]
