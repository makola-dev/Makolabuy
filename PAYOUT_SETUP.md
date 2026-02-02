# Seller Payout System Setup Guide

This guide explains how sellers can receive their payouts and how admins can process them.

## How Sellers Receive Payouts

### Step 1: Sellers Set Up Payout Information

1. **Login as Seller**
2. Go to **Seller Dashboard** → **Payout Settings**
3. Choose a payout method:
   - **Paystack Transfer** (Recommended - Automated)
   - **Bank Transfer** (Manual processing)
   - **Mobile Money** (Manual processing)

### Step 2: Configure Paystack Transfer (Recommended)

For automated payouts via Paystack:

1. **Create Transfer Recipient in Paystack Dashboard:**
   - Log in to your Paystack Dashboard
   - Go to **Transfers** → **Recipients**
   - Click **Create Recipient**
   - Choose recipient type:
     - **Bank Account**: Enter bank details
     - **Mobile Money**: Enter mobile money details
   - Copy the **Recipient Code** (starts with `RCP_`)

2. **Add Recipient Code to Seller Account:**
   - In seller's Payout Settings page
   - Select "Paystack Transfer" as payout method
   - Paste the Recipient Code
   - Save settings

### Step 3: Admin Processes Payout

1. **Admin goes to Payout Management:**
   - Admin Dashboard → **Manage Payouts**

2. **View Pending Payouts:**
   - See all sellers with pending earnings
   - Check their payout method
   - View payout details

3. **Process Payout:**
   - Click **View Details** to see breakdown
   - Click **Process Payout**
   - If Paystack is configured: Money is sent automatically
   - If manual method: Admin processes payment separately

## Payout Methods

### 1. Paystack Transfer (Automated) ⭐ Recommended

**How it works:**
- Admin clicks "Process Payout"
- System automatically sends money via Paystack Transfer API
- Seller receives money in their bank account or mobile money
- Transaction is logged

**Requirements:**
- Seller must create transfer recipient in Paystack
- Seller must add recipient code to their account
- Paystack account must have sufficient balance

**Benefits:**
- Instant processing
- Automated
- Trackable
- Secure

### 2. Bank Transfer (Manual)

**How it works:**
- Seller provides bank account details
- Admin processes payout in system (marks as paid)
- Admin manually transfers money via bank
- Seller receives money in 1-3 business days

**Seller provides:**
- Bank Name
- Account Number
- Account Name

### 3. Mobile Money (Manual)

**How it works:**
- Seller provides mobile money details
- Admin processes payout in system (marks as paid)
- Admin manually sends money via mobile money
- Seller receives money instantly

**Seller provides:**
- Mobile Money Provider (MTN, Vodafone, AirtelTigo)
- Mobile Money Number

## Payout Rules

- **Minimum Payout:** ₵50.00
- **Payout Schedule:** Weekly (Every Friday) or on-demand
- **Processing Time:**
  - Paystack Transfer: Instant
  - Bank Transfer: 1-3 business days
  - Mobile Money: Instant

## Admin Workflow

1. **Check Pending Payouts:**
   - View all sellers with pending earnings
   - Check if payout method is configured

2. **Verify Seller Information:**
   - Click "View Details"
   - Verify payout method and details
   - Check total amount

3. **Process Payout:**
   - Click "Process Payout"
   - If Paystack: Money sent automatically
   - If Manual: Process payment separately, then mark as paid

4. **Track Commissions:**
   - View total platform commission
   - See collected vs pending commission

## Database Setup

Run the migration to add payout info table:

```sql
-- Run this SQL file
migration_add_seller_payout_info.sql
```

Or manually create the table:

```sql
CREATE TABLE IF NOT EXISTS seller_payout_info (
    id INT AUTO_INCREMENT PRIMARY KEY,
    seller_id INT NOT NULL UNIQUE,
    payout_method ENUM('paystack', 'bank', 'mobile_money') DEFAULT 'paystack',
    bank_name VARCHAR(255),
    account_number VARCHAR(100),
    account_name VARCHAR(255),
    mobile_money_provider VARCHAR(50),
    mobile_money_number VARCHAR(20),
    paystack_recipient_code VARCHAR(100),
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (seller_id) REFERENCES users(id) ON DELETE CASCADE
);
```

## Troubleshooting

### Seller can't set payout method
- Ensure database table exists
- Check seller account is verified

### Paystack transfer fails
- Verify recipient code is correct
- Check Paystack account has sufficient balance
- Verify recipient is active in Paystack dashboard
- Check Paystack API logs

### Manual payout needed
- Admin processes payout in system
- Admin sends money via chosen method
- System marks commissions as paid

## Security Notes

- Payout information is encrypted in database
- Only admins can process payouts
- All payout actions are logged
- Minimum payout prevents small transactions

## Support

For Paystack Transfer API documentation:
https://paystack.com/docs/api/#transfer

For issues:
- Check server error logs
- Verify Paystack API credentials
- Ensure database table exists




