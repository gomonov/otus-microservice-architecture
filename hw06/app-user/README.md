```shell
docker build --no-cache -t ygomonov/otus-app-user:hw06 .
```

```shell
docker push ygomonov/otus-app-user:hw06
```

```shell
docker run --network="host" -ti --rm -v ./app:/var/www ygomonov/otus-app-user:hw06
```