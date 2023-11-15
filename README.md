## Fake Twitter
***Fake Twitter*** is an app like twitter in which user can create tweet, follow other user, react to other people's tweet

### Requirements
- php >= 8.1
- mysql 8
- composer 2.5.1

## Installation Guide
1. Clone repository
```bash
  git clone https://github.com/tajul-saajan/fake-twitter-be.git
```
2. Go to project directory
```bash
  cd fake-twitter-be 
```
3. Setup `.env` file
```bash
cp .env.example .env
```
4. Install dependencies
```bash 
  composer install --ignore-platform-reqs 
```
5. Build, migrate and seed
```bash  
  php artisan key:generate 
  php artisan migrate 
  php artisan db:seed 
```
6. Start the server
```bash 
   php artisan serve
```

### Running Tests
```bash
    php artisan test
```

