FROM php:7.1-cli

RUN \
    apt-get update && \
    apt-get install -y git

RUN groupadd -g 1000 user
RUN useradd -lm -u 1000 -g 1000 user

USER 1000

WORKDIR /var/www/blockchain.dev