```shell
docker build --no-cache -t ygomonov/otus-app-bonus:project .
```

```shell
docker push ygomonov/otus-app-bonus:project
```

```shell
docker run --network="host" -ti --rm -v ./app:/var/www ygomonov/otus-app-bonus:project
```