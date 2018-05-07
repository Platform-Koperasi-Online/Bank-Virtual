# Virtual Bank

This is a super super simple bank API system.

## How to use

Publish this project in a server, then run index.php. After that, you can use the APIs.

## Endpoints

- GET /accounts  
  Get all existing account data

- GET /accounts/{id}  
  Get single account data

- POST /accounts  
  Create new account with 0 balance  
  Parameter required:
  - id (16 digit number)  
  - name

- POST /transfer  
  Transfer certain amount of balance to another account  
  Parameter required:  
  - from (id from which the balance will be taken)  
  - to (id to which the balance will be added)  
  - amount (the amont of balance transfered)
