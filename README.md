# Shop Api

Shop Api allows you to store products and manage shopping cart 

## Installation

Use the composer to install packages.

```bash
composer install
php bin/console d:d:c
php bin/console d:s:u -f
```

## Running Tests

    php bin/phpunit

## Routes

### Products

#### Add - **POST**
Add a new product to database
```
/api/product/add
```

form-body: 

    name: string
    price: int (ex. 123 will be 1.23) | float (ex 1.23)

#### List - **GET**
list, filter and paginate products
```
/api/product/list
```

params:

    limit: ?int - default: 3
    page: int
    orderField: ?string (id | name) - default: id
    orderSort: ?string (ASC | DESC) - default: ASC
    pagination: int (-1: previous page | 0: specified page | 1: next page)
    priceLT: ?int (ex. 123) - price less than 
    priceGT: ?int (ex. 123) - price greater than 
    name: ?string - name like

#### Update - **POST**
Update products
```
/api/product/update
```

form-body:
    
    id: int
    name: string
    price: int (ex. 123 will be 1.23) | float (ex 1.23)

#### Remove - **DELETE**
Remove product from database
```
/api/product/remove
```

form-body:

    id: int

### Guest
Used for cart authentication 
#### GetHash - **GET**
Return user unique hash
```
/api/guest/get-hash
```

params:

    ip: int

### Cart

#### Create - **POST**
Create cart
```
/api/cart/create
```

form-body:

    guestHash: string

#### Add Item - **POST**
Add product to cart
```
/api/cart/add_item
```

form-body:

    productId: int
    guestHash: string

#### Remove Product - **DELETE**
Remove product from cart
```
/api/cart/remove_item
```

form-body:

    productId: int
    guestHash: string

#### Remove Product - **GET**
List all products and sum of prices
```
/api/cart/get_products
```

form-body:

    guestHash: string
