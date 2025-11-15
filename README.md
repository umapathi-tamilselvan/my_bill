# Store Billing Application

A complete Store Billing Application built with Laravel 12 and Bootstrap 5, following SOLID principles, clean architecture, and domain-driven design.

## Features

### Products Management
- Add/Edit/Delete Products
- Auto-generated product codes
- Barcode support
- Stock management
- Tax rate configuration

### Customers Management
- Add/Edit/Delete Customers
- Customer ledger view
- Opening balance tracking
- Transaction history

### Billing / POS
- Create bills with multiple items
- Live product search
- Auto-calculation of totals, tax, and discount
- PDF invoice export
- Payment status tracking

### Reports
- Sales report (daily, monthly)
- Stock report with low stock alerts
- Customer statement

## Technology Stack

- **Framework:** Laravel 12
- **Database:** MySQL
- **Frontend:** Bootstrap 5
- **JavaScript:** jQuery (minimal usage)
- **PDF Generation:** DomPDF
- **Architecture:** Clean Architecture with SOLID principles

## Architecture

### SOLID Principles Implementation

1. **Single Responsibility:** Each class has one reason to change
   - `ProductService` handles product business logic
   - `BillingService` handles billing operations
   - `StockService` manages stock movements

2. **Open/Closed:** System is extensible without modification
   - Repository interfaces allow easy swapping of implementations
   - Services can be extended for GST or offers

3. **Liskov Substitution:** Proper inheritance hierarchy
   - Repository implementations follow their interfaces

4. **Interface Segregation:** Focused interfaces
   - `ProductRepositoryInterface`, `CustomerRepositoryInterface`, `BillingRepositoryInterface`

5. **Dependency Inversion:** Depend on abstractions
   - Controllers depend on Service interfaces
   - Services depend on Repository interfaces
   - All dependencies injected via constructor

### Folder Structure

```
app/
├── DTOs/                    # Data Transfer Objects
├── Http/
│   ├── Controllers/        # Controllers
│   └── Requests/           # Form Request Validators
├── Models/                  # Eloquent Models
├── Repositories/
│   ├── Contracts/          # Repository Interfaces
│   └──                     # Repository Implementations
├── Services/                # Business Logic Services
└── Providers/              # Service Providers

resources/
├── views/
│   ├── components/         # Reusable Blade Components
│   ├── layouts/            # Layout Templates
│   ├── products/           # Product Views
│   ├── customers/          # Customer Views
│   ├── billing/            # Billing Views
│   └── reports/            # Report Views
```

## Installation

1. Clone the repository
```bash
git clone <repository-url>
cd my_bill
```

2. Install dependencies
```bash
composer install
npm install
```

3. Configure environment
```bash
cp .env.example .env
php artisan key:generate
```

4. Update `.env` with your database credentials

5. Run migrations
```bash
php artisan migrate
```

6. Build assets
```bash
npm run build
```

7. Start the development server
```bash
php artisan serve
```

## Usage

### Products
- Navigate to Products section
- Click "Add Product" to create new products
- Product codes are auto-generated (PRD000001, PRD000002, etc.)
- Edit or delete products as needed

### Customers
- Navigate to Customers section
- Add customers with their details
- View customer ledger to see transaction history

### Billing
- Navigate to POS/Billing
- Search and add products
- System automatically calculates totals, tax, and discounts
- Save bill and generate PDF invoice

### Reports
- Access Sales Report for date range analysis
- View Stock Report for inventory status
- Generate Customer Statements

## Database Schema

- **products:** Product information and stock
- **customers:** Customer details and opening balance
- **bills:** Bill headers with totals
- **bill_items:** Bill line items
- **stock_movements:** Stock transaction history

## Performance Optimizations

- Eager loading for relationships
- Caching of active products list
- Optimized queries for reports
- Indexed database columns

## Testing

Run tests with:
```bash
php artisan test
```

## License

MIT License
