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