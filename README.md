## KURULUM

### 1. Adım

```sh
git clone https://github.com/enesekinci/turk_ticaret_challenge.git
```

- Yukarıdaki kodu çalıştırarak projeyi indirin.
- Proje dizinine girin.

```sh
cd turk_ticaret_challenge
```

### 2. Adım

- init.php dosyasını açın ve veritabanı bilgilerinizi girin.

```php
    const DB_HOST = 'localhost';
    const DB_NAME = 'cms';
    const DB_USER = 'root';
    const DB_PASS = '12345600';
```

### 3. Adım

- Veritabanı tablolarını oluşturmak için aşağıdaki kodu çalıştırın.

```sh
php seeder
```

- Proje dizinindeyken yukarıdaki kodu çalıştırdığınızda veritabanı tabloları oluşacaktır.
- Projeyi çalıştırmak için aşağıdaki kodu çalıştırın.

```sh
php -S localhost:8000
```
