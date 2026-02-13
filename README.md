# Bumpa Multi-Vendor Cart System

> A full-stack e-commerce platform enabling customers to purchase from multiple merchants in a single transaction — built with Laravel 11, Inertia.js, React 18, and TypeScript.

![Laravel](https://img.shields.io/badge/Laravel-11-FF2D20?style=flat&logo=laravel&logoColor=white)
![React](https://img.shields.io/badge/React-18-61DAFB?style=flat&logo=react&logoColor=black)
![TypeScript](https://img.shields.io/badge/TypeScript-5-3178C6?style=flat&logo=typescript&logoColor=white)
![Tailwind CSS](https://img.shields.io/badge/Tailwind-CSS-38B2AC?style=flat&logo=tailwind-css&logoColor=white)
![Paystack](https://img.shields.io/badge/Paystack-Payment-00C3F7?style=flat)

---

## The Problem

Bumpa merchants each operate individual stores. When a customer wants products from multiple merchants, they face:

- Multiple separate checkouts
- Multiple payments
- Multiple shipping fees
- No unified order experience

This creates friction that kills conversions.

## The Solution

A unified multi-vendor cart system where:

- Customers add products from **different merchants into one cart**
- One **unified checkout** with a single payment
- Payment **automatically splits** to each merchant's account
- Each merchant **fulfills independently** while the customer sees one order
- **Clean order tracking** from purchase to delivery

---

## Key Features

### 🛒 Multi-Vendor Cart
- Add products from different merchants to one cart
- Cart **automatically groups items by merchant**
- Real-time cart count updates in navigation
- Quantity management per item
- Price locked at time of adding (prevents surprise price changes at checkout)

### 💳 Unified Checkout
- Single checkout form for all merchants
- Order breakdown displayed per merchant
- Shipping calculated per merchant
- Complete order total with all fees

### 💰 Paystack Payment Integration
- Secure redirect to Paystack payment page
- Support for card, bank transfer, USSD
- **Payment verification** on return (cannot be faked)
- **Webhook handling** for background payment confirmation
- Full payment audit trail in database

### 📦 Smart Order Architecture
Three-tier structure that enables multi-vendor fulfillment:

```
Order (customer's single purchase)
├── OrderGroup → Sarah's Styles (status: shipped)
│   ├── OrderItem: Blue Ankara Dress × 1
│   └── OrderItem: White Sneakers × 1
├── OrderGroup → Glam Beauty (status: processing)
│   └── OrderItem: Matte Lipstick Set × 2
└── OrderGroup → TechHub NG (status: pending)
    └── OrderItem: Wireless Earbuds × 1
```

Each merchant fulfills independently. Customer sees one unified order.

### 📋 Order Management
- Customer order history
- Per-order breakdown by merchant
- Order status tracking per merchant
- Payment status visibility

---

## Tech Stack

| Layer | Technology | Purpose |
|---|---|---|
| Backend | Laravel 11 | API, business logic, database ORM |
| Frontend | React 18 + TypeScript | UI components, type safety |
| Bridge | Inertia.js | Connects Laravel and React (no separate API needed) |
| Styling | Tailwind CSS | Utility-first responsive design |
| Database | MySQL | Relational data storage |
| Payments | Paystack | Payment processing and verification |
| Build Tool | Vite | Fast frontend bundling |
| Auth | Laravel Breeze | Authentication scaffolding |

---

## Database Architecture

13 tables powering the multi-vendor system:

```
users                    → Authentication
merchants                → Seller accounts
products                 → Merchant inventory
carts                    → Active shopping sessions (guest + authenticated)
cart_items               → Products in cart (with price locked at time of add)
orders                   → Master order record (one per checkout)
order_groups             → Per-merchant order split
order_items              → Individual products per merchant group
payments                 → Payment records with gateway response
```

### Key Design Decisions

**Why `price_at_add` in cart_items?**
Merchant changes price after customer adds to cart. Customer should pay what they saw, not the new price. Price is locked when added.

**Why OrderGroups between Orders and OrderItems?**
Enables independent fulfillment per merchant. Merchant A can ship without waiting for Merchant B. Each group has its own status while the customer sees one order.

**Why both `user_id` and `session_id` in carts?**
Guest users (not logged in) need carts too. Session ID tracks guest carts. When they log in, cart is merged to their account.

---

## Installation

### Requirements

- PHP 8.2+
- Composer
- Node.js 18+
- MySQL 8.0+
- Paystack account (test keys are free)

### Step 1: Clone the repository

```bash
git clone https://github.com/Emmanuel-Olawuni/bumpa-repository.git
cd bumpa-repository
```

### Step 2: Install dependencies

```bash
composer install
npm install
```

### Step 3: Configure environment

```bash
cp .env.example .env
php artisan key:generate
```

Update `.env` with your database and Paystack credentials:

```env
APP_NAME="Bumpa MultiVendor"
APP_URL=http://localhost:8000

DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=bumpa_multivendor
DB_USERNAME=root
DB_PASSWORD=your_password

PAYSTACK_SECRET_KEY=sk_test_xxxxxxxxxxxxxxxxxx
PAYSTACK_PUBLIC_KEY=pk_test_xxxxxxxxxxxxxxxxxx
```

Get free Paystack test keys at: [dashboard.paystack.com](https://dashboard.paystack.com/#/settings/developer)

### Step 4: Set up database

```bash
php artisan migrate --seed
```

This creates all tables and seeds:
- 3 merchants (Sarah's Styles, Glam Beauty, TechHub NG)
- 12 products across categories
- 1 test user (test@example.com)

### Step 5: Start the application

```bash
# Terminal 1: Laravel backend
php artisan serve

# Terminal 2: React frontend (hot reload)
npm run dev
```

Visit: **http://localhost:8000**

---

## Testing Payments

Use Paystack test cards on the payment page:

| Card | CVV | Expiry | Result |
|---|---|---|---|
| 4084 0840 8408 4081 | 408 | Any future date | ✅ Success |
| 4084 0840 8408 4082 | 408 | Any future date | ❌ Decline |
| 5061 0666 6666 6666 6 | Any | Any future date | ❌ Insufficient funds |

**PIN:** 0000  
**OTP:** 123456

After payment, check your [Paystack dashboard](https://dashboard.paystack.com/#/transactions) to see test transactions.

---

## Project Structure

```
bumpa-multivendor/
├── app/
│   ├── Http/
│   │   ├── Controllers/
│   │   │   ├── ProductController.php      # Product catalog
│   │   │   ├── CartController.php         # Cart management
│   │   │   ├── CheckoutController.php     # Checkout + order creation
│   │   │   ├── PaymentController.php      # Paystack callback + webhook
│   │   │   └── OrderController.php        # Order history
│   │   └── Middleware/
│   │       └── HandleInertiaRequests.php  # Shares global data (cart count)
│   ├── Models/
│   │   ├── Merchant.php                   # hasMany Products, OrderGroups
│   │   ├── Product.php                    # belongsTo Merchant
│   │   ├── Cart.php                       # hasMany CartItems + helper methods
│   │   ├── CartItem.php                   # computed subtotal attribute
│   │   ├── Order.php                      # hasMany OrderGroups, hasOne Payment
│   │   ├── OrderGroup.php                 # belongsTo Order + Merchant
│   │   ├── OrderItem.php                  # belongsTo OrderGroup + Product
│   │   └── Payment.php                    # belongsTo Order
│   └── Services/
│       └── PaymentService.php             # Paystack API integration
├── database/
│   ├── migrations/                        # 9 migration files
│   └── seeders/
│       └── DatabaseSeeder.php             # 3 merchants, 12 products
├── resources/
│   └── js/
│       ├── Pages/
│       │   ├── Products/
│       │   │   ├── Index.tsx              # Product catalog page
│       │   │   └── Show.tsx               # Single product page
│       │   ├── Cart/
│       │   │   └── Index.tsx              # Cart page
│       │   ├── Checkout/
│       │   │   ├── Index.tsx              # Checkout form
│       │   │   └── Success.tsx            # Order confirmation
│       │   └── Orders/
│       │       └── Index.tsx              # Order history
│       ├── Components/
│       │   ├── ProductCard.tsx            # Product display card
│       │   ├── MerchantSection.tsx        # Products grouped by merchant
│       │   ├── CartItemRow.tsx            # Single cart item with controls
│       │   ├── MerchantCartGroup.tsx      # Cart items grouped by merchant
│       │   └── AddToCartButton.tsx        # Add to cart with quantity
│       ├── Layouts/
│       │   └── MainLayout.tsx             # Header, footer, flash messages
│       └── types/
│           └── index.d.ts                 # TypeScript interfaces
└── routes/
    └── web.php                            # All application routes
```

---

## API Flow

```
User browses products
    ↓
GET /                           → ProductController@index
    ↓
User adds to cart
    ↓
POST /cart/add                  → CartController@add
    (locks price_at_add)
    ↓
User views cart
    ↓
GET /cart                       → CartController@index
    (items grouped by merchant)
    ↓
User proceeds to checkout
    ↓
GET /checkout                   → CheckoutController@index
    (calculates totals + shipping)
    ↓
User submits shipping info
    ↓
POST /checkout                  → CheckoutController@process
    (creates Order + OrderGroups + OrderItems in DB transaction)
    (initializes Paystack payment)
    ↓
Redirect to Paystack
    ↓
User pays on Paystack
    ↓
GET /payment/callback           → PaymentController@callback
    (verifies payment with Paystack API)
    (marks order as paid)
    ↓
POST /payment/webhook           → PaymentController@webhook
    (background verification, handles missed callbacks)
    ↓
Order confirmation page
```

---

## Design Decisions & Patterns

### Service Layer
Business logic lives in `PaymentService`, not in controllers. Controllers coordinate; services do the work. This makes logic reusable and testable.

### Database Transactions
Order creation uses `DB::beginTransaction()`. If any step fails (order creation, group creation, payment record), everything rolls back. No half-created orders in the database.

### Eager Loading
All relationships use `->with()` to prevent N+1 queries. Fetching 100 cart items with products and merchants = 3 queries, not 201.

### Computed Attributes
`CartItem::subtotal` is a computed attribute (not stored in DB). Calculated from `quantity × price_at_add` on demand. Added to `$appends` so it's always included in JSON responses.

### Inertia.js (No Separate API)
Traditional approach = Laravel REST API + separate React app (two repos, CORS config, JWT tokens). Inertia removes all that. One Laravel app, React pages receive data as props, forms submit as Inertia requests. Simpler, faster to build.

---

## What I Learned Building This

**Multi-vendor architecture is a non-trivial problem.** The three-tier Order → OrderGroup → OrderItem structure was the key insight. Without it, independent merchant fulfillment and payment splitting is messy.

**Price locking matters.** Storing `price_at_add` separately from `product.price` prevents customer disputes when merchants change prices mid-cart.

**Eager loading is not optional at scale.** N+1 queries are invisible in development (fast database, small data) but devastating in production.

**Webhooks are more reliable than callbacks.** Users close browsers. Webhooks don't. Both should be implemented for payment verification.

---

## Author

**Emmanuel Olawuni**  
Full-Stack Developer (Laravel + React + TypeScript)  
Former Intern @ Shekel Mobility (YC W24)

🌐 Portfolio: [emmanuelolawuni.com.ng](https://emmanuelolawuni.com.ng)  
💼 LinkedIn: [linkedin.com/in/emmanuelolawuni](https://linkedin.com/in/emmanuelolawuni)  
🐦 Twitter/X: [@dev_emmanuel_](https://x.com/dev_emmanuel_)  
📧 Email: admin@emmanuelolawuni.com.ng  
💻 GitHub: [github.com/Emmanuel-Olawuni](https://github.com/Emmanuel-Olawuni)

---

## Other Projects

**Vowtography** — Wedding photo sharing SaaS  
Couples create private galleries, guests upload via QR code, all memories in one place.  
🔗 [vowtography.netlify.app](https://vowtography.netlify.app)  
Stack: Laravel + React + TypeScript

---

## License

MIT License — feel free to use this as reference or learning material.
