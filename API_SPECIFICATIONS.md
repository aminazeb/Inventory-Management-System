# API Specifications

This document describes the API endpoints and model relationships implemented for the inventory management system.

## Model Relationships

### User Model
- **Has Many Sales**: `$user->sales`
- **Has Many Purchases**: `$user->purchases`
- **Has Many Roles**: `$user->roles` (Spatie Permission)
- **Has Many Permissions**: `$user->permissions` (Spatie Permission)

### Product Model
- **Has Many Sales**: `$product->sales`
- **Has Many Purchases**: `$product->purchases`
- **Has Many Inventory**: `$product->inventory`

### Purchase Model
- **Belongs To Product**: `$purchase->product`
- **Belongs To User**: `$purchase->user`

### Sales Model
- **Belongs To Product**: `$sales->product`
- **Belongs To User**: `$sales->user`

### Inventory Model
- **Belongs To Product**: `$inventory->product`

## API Endpoints

## Authentication

All API endpoints require authentication using Laravel Sanctum. Include the `Authorization: Bearer {token}` header in your requests.

## User Authentication Endpoints

### POST /api/register
Register a new user.

**Request Body:**
```
{
  "name": "John Doe",
  "email": "john@example.com",
  "password": "password",
  "password_confirmation": "password",
  "phone": "+1234567890" // optional
}
```
**Response:**
```
{
  "access_token": "...",
  "token_type": "Bearer"
}
```

### POST /api/login
Authenticate a user and receive an access token.

**Request Body:**
```
{
  "email": "john@example.com",
  "password": "password"
}
```
**Response:**
```
{
  "access_token": "...",
  "token_type": "Bearer"
}
```

### POST /api/logout
Log out the authenticated user (requires Bearer token).

**Response:**
```
{
  "message": "Logged out"
}
```

## User Verification Endpoints

### POST /api/verify-phone
Verify a user's phone number using OTP.

**Request Body:**
```
{
  "user_id": 1,
  "otp": "123456"
}
```
**Response:**
```
{
  "message": "Phone verified"
}
```

### GET /api/email/verify/{id}/{hash}
Verify a user's email address (link sent via email).

**Response:**
```
{
  "message": "Email verified successfully"
}
```

### GET /api/email/verify
Show the email verification notice (for authenticated users).

### POST /api/email/verification-notification
Resend the email verification notification (requires Bearer token).

**Response:**
```
{
  "message": "Verification link sent"
}
```

### Users

### GET /api/profile
Get the authenticated user's profile (requires Bearer token and verified email).

**Response:**
```
{
  "id": 1,
  "name": "John Doe",
  "email": "john@example.com",
  "phone": "+1234567890",
  ...
}
```

#### GET /api/users
List all users with optional filtering, sorting, and searching.

**Query Parameters:**
- `filter[id]`: Filter by user ID
- `filter[name]`: Filter by name
- `filter[email]`: Filter by email
- `filter[phone]`: Filter by phone
- `sort`: Sort by field (id, name, email, phone, created_at, updated_at)
- `search`: Search in name, email, phone
- `include`: Include relationships (sales, purchases, roles, permissions)

**Response:**
```json
{
  "data": [
    {
      "id": 1,
      "name": "John Doe",
      "email": "john@example.com",
      "phone": "+1234567890",
      "email_verified_at": "2024-01-01T00:00:00.000000Z",
      "phone_verified_at": "2024-01-01T00:00:00.000000Z",
      "created_at": "2024-01-01T00:00:00.000000Z",
      "updated_at": "2024-01-01T00:00:00.000000Z",
      "sales": [...],
      "purchases": [...],
      "roles": [...],
      "permissions": [...]
    }
  ]
}
```

#### GET /api/users/{id}
Get a specific user by ID.

#### PUT /api/users/{id}
Update a user.

**Request Body:**
```json
{
  "name": "Jane Smith",
  "email": "jane.smith@example.com",
  "phone": "+1234567890"
}
```

#### DELETE /api/users/{id}
Delete a user (soft delete).

#### POST /api/users/{id}/restore
Restore a soft-deleted user.

### Products

#### GET /api/products
List all products with optional filtering, sorting, and searching.

**Query Parameters:**
- `filter[id]`: Filter by product ID
- `filter[name]`: Filter by name
- `filter[price]`: Filter by price
- `sort`: Sort by field (id, name, price)
- `search`: Search in name, description, color
- `include`: Include relationships (sales, purchases, inventory)

#### POST /api/products
Create a new product.

**Request Body:**
```json
{
  "name": "Blue Pen",
  "description": "A high-quality blue pen",
  "color": "blue",
  "price": 2.99,
  "image_url": "https://example.com/pen.jpg"
}
```

#### GET /api/products/{id}
Get a specific product by ID.

#### PUT /api/products/{id}
Update a product.

#### DELETE /api/products/{id}
Delete a product (soft delete).

#### POST /api/products/{id}/restore
Restore a soft-deleted product.

### Purchases

#### GET /api/purchases
List all purchases with optional filtering, sorting, and searching.

**Query Parameters:**
- `filter[id]`: Filter by purchase ID
- `filter[product_id]`: Filter by product ID
- `filter[user_id]`: Filter by user ID
- `filter[supplier]`: Filter by supplier
- `filter[manufacturer]`: Filter by manufacturer
- `filter[cost_per_unit]`: Filter by cost per unit
- `filter[amount]`: Filter by total amount
- `filter[quantity]`: Filter by quantity
- `sort`: Sort by field (id, product_id, user_id, cost_per_unit, amount, quantity, created_at)
- `search`: Search in supplier, manufacturer
- `include`: Include relationships (product, user)

**Response:**
```json
{
  "data": [
    {
      "id": 1,
      "product_id": 1,
      "user_id": 1,
      "supplier": "Office Supplies Co",
      "manufacturer": "PenCorp",
      "cost_per_unit": 1.50,
      "amount": 150.00,
      "quantity": 100,
      "meta": {...},
      "created_at": "2024-01-01T00:00:00.000000Z",
      "updated_at": "2024-01-01T00:00:00.000000Z",
      "product": {...},
      "user": {...}
    }
  ]
}
```

#### POST /api/purchases
Create a new purchase.

**Request Body:**
```json
{
  "product_id": 1,
  "supplier": "Office Supplies Co",
  "manufacturer": "PenCorp",
  "cost_per_unit": 1.50,
  "quantity": 100,
  "meta": {
    "notes": "Bulk order for office"
  }
}
```

**Note:** `user_id` will be automatically set to the authenticated user if not provided. `amount` will be automatically calculated as `cost_per_unit * quantity` if not provided.

#### GET /api/purchases/{id}
Get a specific purchase by ID.

#### PUT /api/purchases/{id}
Update a purchase.

#### DELETE /api/purchases/{id}
Delete a purchase (soft delete).

#### POST /api/purchases/{id}/restore
Restore a soft-deleted purchase.

### Sales

#### GET /api/sales
List all sales with optional filtering, sorting, and searching.

**Query Parameters:**
- `filter[id]`: Filter by sale ID
- `filter[product_id]`: Filter by product ID
- `filter[user_id]`: Filter by user ID
- `filter[quantity]`: Filter by quantity
- `filter[amount]`: Filter by amount
- `filter[action]`: Filter by action
- `sort`: Sort by field (id, product_id, user_id, quantity, amount, created_at)
- `search`: Search in action
- `include`: Include relationships (product, user)

**Response:**
```json
{
  "data": [
    {
      "id": 1,
      "product_id": 1,
      "user_id": 1,
      "quantity": 5,
      "amount": 14.95,
      "action": "sale",
      "meta": {...},
      "created_at": "2024-01-01T00:00:00.000000Z",
      "updated_at": "2024-01-01T00:00:00.000000Z",
      "product": {...},
      "user": {...}
    }
  ]
}
```

#### POST /api/sales
Create a new sale.

**Request Body:**
```json
{
  "product_id": 1,
  "quantity": 5,
  "amount": 14.95,
  "action": "sale",
  "meta": {
    "notes": "Walk-in customer"
  }
}
```

**Note:** `user_id` will be automatically set to the authenticated user if not provided.

#### GET /api/sales/{id}
Get a specific sale by ID.

#### PUT /api/sales/{id}
Update a sale.

#### DELETE /api/sales/{id}
Delete a sale (soft delete).

#### POST /api/sales/{id}/restore
Restore a soft-deleted sale.

### Inventory

#### GET /api/inventory
List all inventory items with optional filtering, sorting, and searching.

**Query Parameters:**
- `filter[id]`: Filter by inventory ID
- `filter[product_id]`: Filter by product ID
- `filter[quantity]`: Filter by quantity
- `filter[storage_location]`: Filter by storage location
- `filter[amount]`: Filter by price per unit
- `filter[last_stocked_at]`: Filter by last stocked date
- `sort`: Sort by field (id, product_id, quantity, amount, last_stocked_at)
- `search`: Search in storage_location
- `include`: Include relationships (product)

#### POST /api/inventory
Create a new inventory item.

#### GET /api/inventory/{id}
Get a specific inventory item by ID.

#### PUT /api/inventory/{id}
Update an inventory item.

#### DELETE /api/inventory/{id}
Delete an inventory item (soft delete).

#### POST /api/inventory/{id}/restore
Restore a soft-deleted inventory item.

### Export Functionality

#### POST /api/inventory/export
Export inventory data to Excel format with comprehensive filtering options. This endpoint uses Laravel Actions for clean, single-purpose functionality.

**Request Body (Optional Filters):**
```json
{
  "product_id": 1,
  "product_name": "Blue Pen",
  "storage_location": "Warehouse A",
  "min_quantity": 10,
  "max_quantity": 1000,
  "min_price": 5.00,
  "max_price": 50.00,
  "date_from": "2024-01-01",
  "date_to": "2024-12-31"
}
```

**Filter Options:**
- `product_id`: Filter by specific product ID
- `product_name`: Filter by product name (partial match)
- `storage_location`: Filter by storage location (partial match)
- `min_quantity` / `max_quantity`: Filter by quantity range
- `min_price` / `max_price`: Filter by price per unit range
- `date_from` / `date_to`: Filter by last stocked date range

**Response:**
- **Content-Type**: `application/vnd.openxmlformats-officedocument.spreadsheetml.sheet`
- **Content-Disposition**: `attachment; filename=inventory_YYYY-MM-DD_HH-MM-SS.xlsx`
- **File**: Excel file with formatted inventory data

**Excel Features:**
- Professional styling with alternating row colors
- Formatted headers with blue background
- Proper column widths for readability
- Number formatting for prices and quantities
- Date formatting for timestamps
- Calculated total value column (quantity × price per unit)
- Product information included (name, description, color, price)

**Implementation Details:**
- Uses Laravel Actions with `AsController` trait
- Implements Laravel Excel interfaces for professional output
- Single class handles both business logic and HTTP response
- Clean, maintainable, and testable code structure

## Features

### Automatic Field Calculation
- **Purchases**: `amount` is automatically calculated as `cost_per_unit * quantity`
- **User Assignment**: `user_id` is automatically set to the authenticated user for purchases and sales

### Soft Deletes
All models support soft deletes, allowing data recovery through restore endpoints.

### Relationship Loading
Use the `include` query parameter to load related data and reduce API calls.

### Filtering and Sorting
All endpoints support filtering, sorting, and searching on relevant fields.

### Authorization
All endpoints are protected by policies that can be customized for your business logic.

## Error Handling

The API returns standard HTTP status codes:
- `200`: Success
- `201`: Created
- `400`: Bad Request
- `401`: Unauthorized
- `403`: Forbidden
- `404`: Not Found
- `422`: Validation Error
- `500`: Server Error
