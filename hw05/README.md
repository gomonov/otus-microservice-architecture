## Запуск через helm

### Запуск minikube
```shell
minikube start
```
### Создание namespace app
```shell
kubectl create ns app
```

### Обновление сабчартов
```shell
helm dependency update
```

### Запуск приложения
```shell
helm upgrade --install hw05 .helm/ -n app
```

### Прокинуть порт grafana
```shell
kubectl port-forward svc/hw05-grafana 3000:80
```
admin/prom-operator

## Коллекция postman
### hw05/postman/collection.json

### Запуск тестов
```shell
newman run postman/collection.json -n 1000
```

