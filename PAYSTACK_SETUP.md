# Paystack Payment Integration Setup Guide

This guide will help you set up and configure the Paystack payment integration for your Makola e-commerce platform.

## Features

- ✅ Secure payment processing via Paystack
- ✅ Webhook support for automatic payment verification
- ✅ Payment verification with amount validation
- ✅ Unique payment reference generation
- ✅ Error handling and retry mechanisms
- ✅ Order status updates on successful payment

## Configuration

### 1. API Keys

Your Paystack API keys are stored in:
- `config/paystack.php` - Contains both secret and public keys (server-side only)
- `config/paystack_public.php` - Contains only public key (for JavaScript)

**Important Security Notes:**
- Never commit your secret key to public repositories
- The secret key should only be used server-side
- The public key can be used in client-side JavaScript

### 2. Webhook Setup

To enable automatic payment verification via webhooks:

1. Log in to your Paystack Dashboard
2. Go to Settings → API Keys & Webhooks
3. Add a new webhook endpoint:
   ```
   https://yourdomain.com/Makola/controllers/paymentController.php?action=webhook
   ```
4. Select the event: `charge.success`
5. Save the webhook

**Webhook Security:**
- The webhook handler verifies the signature using HMAC SHA512
- Only verified webhooks are processed
- Invalid signatures are rejected with HTTP 400

### 3. Payment Flow

1. **Order Creation**: User creates an order at checkout
2. **Payment Page**: User is redirected to payment page (`buyers/payment.php`)
3. **Paystack Popup**: User clicks "Pay with Paystack" and completes payment
4. **Verification**: Payment is verified via:
   - Immediate callback verification (client-side redirect)
   - Webhook verification (server-side, more reliable)
5. **Order Update**: Order status is updated to "paid" and "processing"

## File Structure

```
config/
├── paystack.php              # Secret and public keys (server-side)
├── paystack_public.php       # Public key only (client-side)
└── paystack_helper.php       # Helper functions for Paystack API

controllers/
└── paymentController.php     # Payment verification and webhook handler

buyers/
├── payment.php               # Payment page with Paystack integration
└── payment-verify.php        # Payment verification page
```

## API Functions

### Helper Functions (config/paystack_helper.php)

- `verifyPaystackTransaction($reference)` - Verify a transaction
- `initializePaystackTransaction($data)` - Initialize a new transaction
- `verifyPaystackWebhookSignature($payload, $signature)` - Verify webhook signature
- `generatePaymentReference($order_number)` - Generate unique payment reference

## Testing

### Test Mode

To test the integration:

1. Use Paystack test keys:
   - Test Secret Key: `sk_test_...`
   - Test Public Key: `pk_test_...`

2. Use test card numbers:
   - Success: `4084084084084081`
   - Decline: `5060666666666666666`
   - 3DS: `5060666666666666667`

3. Use any future expiry date and any CVV

### Production Mode

1. Replace test keys with live keys in `config/paystack.php`
2. Update public key in `config/paystack_public.php`
3. Configure webhook URL in Paystack dashboard
4. Test with small amounts first

## Troubleshooting

### Payment Not Verifying

1. Check webhook URL is correctly configured in Paystack dashboard
2. Verify webhook signature verification is working
3. Check server logs for errors
4. Ensure CURL is enabled on your server

### Amount Mismatch Errors

- Verify that amounts are converted to kobo/cents (multiply by 100)
- Check that the order total matches the payment amount

### Webhook Not Receiving Events

1. Verify webhook URL is accessible from the internet
2. Check Paystack dashboard for webhook delivery logs
3. Ensure your server can receive POST requests
4. Check firewall settings

## Security Best Practices

1. ✅ Always verify webhook signatures
2. ✅ Validate payment amounts before updating orders
3. ✅ Use HTTPS for all payment-related endpoints
4. ✅ Never expose secret keys in client-side code
5. ✅ Log all payment transactions for audit purposes
6. ✅ Implement rate limiting on payment endpoints

## Support

For Paystack API documentation, visit: https://paystack.com/docs/api

For issues with this integration, check:
- Server error logs
- Paystack dashboard transaction logs
- Webhook delivery logs in Paystack dashboard




