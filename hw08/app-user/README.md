```shell
docker build --no-cache -t ygomonov/otus-app-user:hw08 .
```

```shell
docker push ygomonov/otus-app-user:hw08
```

```shell
docker run --network="host" -ti --rm -v ./app:/var/www ygomonov/otus-app-user:hw08
```