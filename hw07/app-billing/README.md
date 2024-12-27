```shell
docker build --no-cache -t ygomonov/otus-app-billing:hw07 .
```

```shell
docker push ygomonov/otus-app-billing:hw07
```

```shell
docker run --network="host" -ti --rm -v ./app:/var/www ygomonov/otus-app-billing:hw07
```