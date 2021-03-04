### Installation
```shell
# requirements: docker, docker-compose, make
git clone https://github.com/lemfaer/werush.git
cd werush
sudo make init
```

### Get multiple users
```shell
curl -X GET "http://localhost:8000/api/users?search=example&limit=10&offset=0"
```

### Get one user
```shell
curl -X GET "http://localhost:8000/api/user/1"
```

### Create new or replace a user
```shell
curl -X POST "http://localhost:8000/api/user" -H 'content-type: application/json' -d '{"name":"Friendly", "email":"me@example.com", "password":"B$123456"}'
```
```shell
curl -X POST "http://localhost:8000/api/user/1" -H 'content-type: application/json' -d '{"name": "Punk", "email": "yo@example.com", "password": "54321"}'
```

### Patch user
```shell
curl -X PATCH "http://localhost:8000/api/user/1" -H 'content-type: application/json' -d '{"name": "Rewind"}'
```

### Delete user
```shell
curl -X DELETE "http://localhost:8000/api/user/1"
```
