# Valid SSL certificate for local development

> https://medium.freecodecamp.org/how-to-get-https-working-on-your-local-development-environment-in-5-minutes-7af615770eec

## Root SSL certificate

> Generate a RSA-2048 key to generate the Root SSL certificate
```bash
openssl genrsa -des3 -out nimbanetRootCA.key 2048
```
 - nimbanetRootCA.key

> Create a new Root SSL certificate password : nimbanet 
```bash
$ openssl req -x509 -new -nodes -key nimbanetRootCA.key -sha256 -days 1024 -out nimbanetRootCA.pem
 ```
 - nimbanetRootCA.pem

## Domain SSL certificate

> Create a certificate key for watcher.materielelectrique.dev
```bash
$ openssl req -new -sha256 -nodes -out watcher.materielelectrique.dev.csr -newkey rsa:2048 -keyout watcher.materielelectrique.dev.key -config <( cat watcher.materielelectrique.dev.csr.cnf )
```

> Create certificate for watcher.materielelectrique.dev

```bash
$ openssl x509 -req -in watcher.materielelectrique.dev.csr -CA nimbanetRootCA.pem -CAkey nimbanetRootCA.key -CAcreateserial -out watcher.materielelectrique.dev.crt -days 500 -sha256 -extfile watcher.materielelectrique.dev.ext
```


## Trust the root SSL certificate

### For Chrome Browser

Go to
```url 
chrome://settings/certificates
```

Tab Authority and import Root Certificate Authority `nimbanetRootCA.pem`

