FROM mysql:8.0

ENV TZ=UTC \
    MYSQL_DATABASE=rakuju_local \
    MYSQL_USER=docker \
    MYSQL_PASSWORD=docker \
    MYSQL_ROOT_PASSWORD=root

COPY ./my.cnf /etc/my.cnf