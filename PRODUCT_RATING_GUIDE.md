# Product Rating and Review System Guide

## Overview

A comprehensive product rating and review system has been implemented for the Makola website. Users can rate products with 1-5 stars and write detailed reviews.

## Features

### 1. Star Rating System
- **5-Star Rating**: Interactive star rating input (1-5 stars)
- **Visual Display**: Star ratings displayed throughout the site
- **Average Rating**: Automatically calculated and displayed
- **Rating Labels**: Dynamic labels (Poor, Fair, Good, Very Good, Excellent)

### 2. Review Submission
- **Review Form**: Easy-to-use form for submitting reviews
- **Required Fields**: Rating (required), Title (min 3 chars), Review text (min 10 chars)
- **Validation**: Client-side and server-side validation
- **One Review Per User**: Users can only review each product once
- **Verified Purchase Badge**: Shows for reviews from verified purchases

### 3. Review Display
- **Review List**: Paginated list of all reviews
- **Review Details**: User name, date, rating, title, and review text
- **Helpful Button**: Users can mark reviews as helpful
- **Sorting**: Reviews sorted by verified purchase, helpful count, and date

### 4. Review Management
- **Helpful Votes**: Users can vote if a review is helpful
- **Review Count**: Total number of reviews displayed
- **Average Rating**: Automatically updated when new reviews are added

## Files

### CSS
- `assets/css/product-rating.css` - All styling for rating and review components

### JavaScript
- `assets/js/product-rating.js` - Rating and review functionality

### PHP
- `controllers/reviewsController.php` - Backend API for reviews (already existed, enhanced)
- `product.php` - Product page with rating/review section

## Usage

### For Users

1. **Viewing Ratings**:
   - Product ratings are displayed on the product page
   - Average rating and review count shown in product info section

2. **Submitting a Review**:
   - Navigate to product page
   - Scroll to "Customer Reviews" section
   - Click on stars to select rating (1-5)
   - Enter review title (minimum 3 characters)
   - Enter review text (minimum 10 characters)
   - Click "Submit Review"

3. **Marking Reviews as Helpful**:
   - Click "Helpful" button on any review
   - Button toggles to show you've marked it as helpful

### For Developers

#### Display Star Rating

```javascript
// Render star rating in a container
const container = document.getElementById('ratingContainer');
window.ProductRating.renderStarRating(4.5, container, '1.5rem');
```

#### Load Reviews

```javascript
// Load reviews for a product
window.ProductRating.loadReviews(productId, pageNumber);
```

#### Check if User Can Review

The system automatically checks if a user has already reviewed a product. If they have, the review form is hidden and a message is shown.

## Database Structure

### product_reviews Table
- `id` - Review ID
- `product_id` - Product being reviewed
- `user_id` - User who wrote the review
- `order_id` - Order ID (for verified purchase)
- `rating` - Rating (1-5)
- `title` - Review title
- `review_text` - Review content
- `verified_purchase` - Whether from verified purchase
- `helpful_count` - Number of helpful votes
- `status` - Review status (pending, approved, rejected)
- `created_at` - Review date

### review_helpful Table
- `id` - Vote ID
- `review_id` - Review being voted on
- `user_id` - User who voted
- `is_helpful` - Whether marked as helpful
- `created_at` - Vote date

## API Endpoints

### Submit Review
```
POST /controllers/reviewsController.php
Parameters:
- action: 'add'
- product_id: Product ID
- rating: Rating (1-5)
- title: Review title
- review_text: Review content
- order_id: Order ID (optional, for verified purchase)
```

### Get Reviews
```
GET /controllers/reviewsController.php?action=get&product_id={id}&page={page}
Returns:
{
    success: true,
    reviews: [...],
    total: number,
    pages: number
}
```

### Mark Helpful
```
POST /controllers/reviewsController.php
Parameters:
- action: 'helpful'
- review_id: Review ID
```

## Styling

### Star Rating Colors
- **Filled Stars**: `#ffc107` (yellow/gold)
- **Empty Stars**: `#ddd` (light gray)
- **Hover Effect**: Scale and color change

### Review Card
- **Background**: White with subtle border
- **Hover Effect**: Slight elevation and shadow
- **Spacing**: Comfortable padding and margins

### Mobile Responsive
- All components are fully responsive
- Touch-friendly buttons (minimum 44px)
- Optimized layouts for small screens

## Customization

### Change Star Colors

Edit `assets/css/product-rating.css`:

```css
.star-rating-display .star {
    color: #ffc107; /* Change to your preferred color */
}
```

### Change Rating Labels

Edit `assets/js/product-rating.js`:

```javascript
const labels = {
    1: 'Poor',
    2: 'Fair',
    3: 'Good',
    4: 'Very Good',
    5: 'Excellent'
};
```

### Change Review Form Validation

Edit `assets/js/product-rating.js` in `handleReviewSubmit` function:

```javascript
if (!title || title.length < 3) {
    // Change minimum length
}

if (!reviewText || reviewText.length < 10) {
    // Change minimum length
}
```

## Security

- **XSS Protection**: All user input is escaped before display
- **SQL Injection**: All queries use prepared statements
- **Validation**: Both client-side and server-side validation
- **Authentication**: Only logged-in buyers can submit reviews
- **Rate Limiting**: One review per user per product

## Performance

- **Lazy Loading**: Reviews loaded via AJAX
- **Pagination**: Reviews paginated (10 per page)
- **Efficient Queries**: Optimized database queries
- **Caching**: Product ratings cached in products table

## Future Enhancements

1. **Review Images**: Allow users to upload images with reviews
2. **Review Replies**: Allow sellers to reply to reviews
3. **Review Filtering**: Filter by rating, verified purchase, etc.
4. **Review Sorting**: Sort by date, helpful, rating
5. **Review Reporting**: Allow users to report inappropriate reviews
6. **Review Moderation**: Admin panel for reviewing pending reviews

## Troubleshooting

### Reviews Not Loading
- Check browser console for JavaScript errors
- Verify `product-rating.js` is loaded
- Check network tab for API errors
- Verify product ID is correct

### Review Form Not Submitting
- Check if user is logged in
- Verify user hasn't already reviewed the product
- Check form validation messages
- Check browser console for errors

### Stars Not Displaying
- Verify Bootstrap Icons are loaded
- Check CSS file is loaded
- Verify font-size is set correctly

## Support

For issues or questions:
1. Check browser console for errors
2. Verify all files are loaded correctly
3. Check database tables exist
4. Verify user permissions
