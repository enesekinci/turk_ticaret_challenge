<?php

require_once __DIR__ . '/vendor/autoload.php';

#TODO: env dosyası oluşturulacak, env dosyası içerisindeki bilgiler Config sınıfı ile okunacak

const APP_DEBUG = true;

const DS = DIRECTORY_SEPARATOR;

const APP_TIMEZONE = 'Europe/Istanbul';

const APP_URL = 'http://localhost:8000';

const DB_HOST = 'database';
const DB_USER = 'root';
const DB_PASSWORD = '12345600';
const DB_NAME = 'bookstore';
