# 🚀 SaaS Module - Complete Guide

## 📍 How to Access the SaaS Module

### Login as Superadmin:
- **Email:** `superadmin@dreampos.com`
- **Dashboard URL:** `http://laundry.test/superadmin/dashboard`

---

## 🎯 Available Modules

### 1️⃣ **Dashboard** 📊
**URL:** `/superadmin/dashboard`

**Features:**
- 💰 Total Revenue Statistics (Total, Monthly, Yearly)
- 👥 Customer Overview (Total, New this month)
- 📈 Active Subscriptions Count
- 📉 Trial, Cancelled Subscriptions
- 🏪 Total Stores Across All Businesses
- 📦 Total Orders Overview
- 📊 Revenue Chart (Last 12 Months)
- 📋 Plan Distribution Chart
- 📜 Recent Customers List
- 💳 Recent Payments
- ⏰ Upcoming Renewals (Next 30 days)
- 📉 Churn Rate Calculation

---

### 2️⃣ **Customers Management** 👥
**URL:** `/superadmin/customers`

**Features:**
- View all Business Owners
- See each customer's:
  - Name & Email
  - Current Plan
  - Subscription Status
  - Number of Stores
  - Active Subscription Details
- **Impersonate** customers to view their store
- Filter and search customers
- Stats cards showing:
  - Total Customers
  - Active Customers
  - Trial Users
  - Churn Rate

---

### 3️⃣ **Subscriptions** 🔄
**URL:** `/superadmin/subscriptions`

**Features:**
- View all subscriptions (active, pending, cancelled, expired)
- See subscription details:
  - Customer Name & Email
  - Plan Type
  - Start Date & End Date
  - Next Renewal Date
  - Status Badge
  - Monthly Amount
- Filter by:
  - Status (Active, Pending, Cancelled, Expired)
  - Plan Type
- Quick link to view customer profile
- Pagination support

---

### 4️⃣ **Plans Management** 📦
**URL:** `/superadmin/plans`

**Features:**
- Beautiful card-based plan display
- See plan details:
  - Plan Name & Description
  - Monthly Price
  - Yearly Price
  - Max Stores Allowed
  - Max Users per Store
  - Feature List
  - Active/Inactive Status
  - Number of Active Subscribers
- View subscribers for each plan
- Plan comparison at a glance

**Available Plans (from seeder):**
1. **Basic** - MYR 99/month (1 store, 5 users)
2. **Standard** - MYR 199/month (3 stores, 15 users)
3. **Premium** - MYR 399/month (10 stores, unlimited users)
4. **Enterprise** - MYR 799/month (unlimited stores & users)

---

### 5️⃣ **Payments History** 💳
**URL:** `/superadmin/payments`

**Features:**
- Complete payment transaction history
- See payment details:
  - Transaction ID
  - Customer Name & Email
  - Plan Type
  - Amount (Currency)
  - Payment Method
  - Payment Date & Time
  - Status (Completed, Pending, Failed)
- Filter by:
  - Payment Status
  - Transaction ID Search
- **Total Revenue** calculation at bottom
- Pagination support

---

## 🎨 Navigation

All SaaS modules have a **beautiful purple gradient navigation bar** at the top with quick links:
- 🏠 Dashboard
- 👥 Customers
- 🔄 Subscriptions
- 📦 Plans
- 💳 Payments

The active page is highlighted with a white background!

---

## 🔐 Impersonation Feature

**How to Use:**
1. Go to **Customers** page
2. Click the 👁️ **"Impersonate"** button next to any customer
3. You'll be logged in as that customer
4. A **red banner** appears at the top: "You are currently viewing as [Customer Name]"
5. Click **"Back to Superadmin"** to return to your superadmin account

**What You Can Do While Impersonating:**
- View their stores
- See their orders
- Access their dashboard
- Test their features
- Debug issues

---

## 🎯 Key Features

### ✅ Role-Based Access
- Only users with `super_admin` role can access
- Uses the existing `user_roles` table
- No role column conflict

### ✅ Store Selector Hidden
- Superadmins don't see the store selector dropdown
- Can access all stores through impersonation

### ✅ Separate Dashboard
- Different from business owner dashboard
- Focused on SaaS metrics
- Revenue-focused analytics

### ✅ Beautiful UI
- Purple gradient theme
- Modern card designs
- Responsive layout
- Chart.js integration (ready for charts)

---

## 📊 Database Structure

### Tables Created:
1. **plans** - SaaS subscription plans
2. **subscriptions** - User subscriptions to plans
3. **subscription_payments** - Payment history

### Users Table Additions:
- `company_name` - Business name
- `company_address` - Business address
- `company_phone` - Business phone
- `current_plan_id` - Foreign key to plans
- `plan_expires_at` - Subscription expiry

### Relationships:
- User → hasOne(Subscription)
- User → belongsTo(Plan)
- Subscription → belongsTo(User)
- Subscription → belongsTo(Plan)
- Subscription → hasMany(SubscriptionPayment)
- Plan → hasMany(Subscription)

---

## 🛠️ Technical Implementation

### Middleware:
- `superadmin.only` - Restricts access to superadmins
- `plan.feature` - Check if user has specific feature (ready for use)

### Helper Methods (User Model):
```php
$user->isSuperAdmin()  // Check if user is superadmin
$user->hasActiveSubscription()  // Check active subscription
$user->hasFeature('feature_name')  // Check feature access
$user->getPlanName()  // Get plan name
$user->getSubscriptionStatus()  // Get subscription status
```

---

## 🚀 Quick Start Commands

### Set User as Superadmin:
```bash
.\set-superadmin.bat
```

### Run Plan Seeder:
```bash
php artisan db:seed --class=PlanSeeder
```

### Clear All Caches:
```bash
php artisan optimize:clear
```

---

## 📋 Future Enhancements (Ready for Implementation)

1. **Payment Integration**
   - Stripe/PayPal gateway
   - Automatic subscription renewal
   - Invoice generation

2. **Plan CRUD**
   - Create/Edit/Delete plans
   - Feature management
   - Price customization

3. **Subscription Management**
   - Manual subscription creation
   - Upgrade/Downgrade plans
   - Trial period management
   - Cancellation handling

4. **Analytics**
   - Advanced revenue reports
   - Customer lifetime value
   - Retention metrics
   - MRR (Monthly Recurring Revenue)

5. **Notifications**
   - Payment reminders
   - Subscription expiry alerts
   - Failed payment notifications

---

## 🎉 Summary

You now have a **complete SaaS module** with:
- ✅ 5 fully functional pages
- ✅ Beautiful UI with navigation
- ✅ Impersonation system
- ✅ Role-based access control
- ✅ Database structure ready
- ✅ Payment tracking
- ✅ Customer management
- ✅ Plan management
- ✅ Subscription tracking

**Access it now at:** `http://laundry.test/superadmin/dashboard` 🚀

