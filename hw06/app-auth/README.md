```shell
docker build --no-cache -t ygomonov/otus-app-auth:hw06 .
```

```shell
docker push ygomonov/otus-app-auth:hw06
```

```shell
docker run --network="host" -ti --rm -v ./app:/var/www ygomonov/otus-app-auth:hw06
```