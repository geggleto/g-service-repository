# G-Service-Registry

Redis Powered Service Registry API

## Register a service

**URL:**
    POST /service


**Body:**
```
{
  "services" : [{
    "name" : "Users",
    "url" : "http://testurl/",
    "callback" : "http://testurl/callback"
  }]
}
```

## DeRegister a service

**URL:**
    DELETE /service


**Body:**
```
{
  "services" : [{
    "name" : "Users",
    "url" : "http://testurl/",
    "callback" : "http://testurl/callback"
  }]
}
```

## Resolve a service
**URL:**
    GET /service/{ServiceName}
    
**Response:**
    `["url",..]`
    
    
## Usage

This package is not a library, it is recommended that you install it via composer create-project


## Redis

Please create a .env file in the root of this project directory

It requires the following keys:
- REDIS_PASSWORD
- REDIS_IP
- REDIS_PORT