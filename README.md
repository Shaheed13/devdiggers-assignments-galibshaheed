# devdiggers-assignments-galibshaheed
# DevDiggers WordPress/WooCommerce Developer Assignment
**Submitted by:** Galib Shaheed  
**Assignments:** Plugin Development (WooCommerce)

---

## 🧩 Assignment 1 – Conditional Accessories Discount Plugin

**Description:**  
Applies a 15% discount automatically when the cart has:
- At least one product from the **Accessories** category
- A subtotal (before discount) of ₹2000 or more

### 💡 Features
- WooCommerce hook-based logic (`woocommerce_cart_calculate_fees`)
- Applies fee conditionally
- Stores flag in WooCommerce session
- Custom success message displayed in cart totals table

### 🛠 Setup
1. Install and activate the plugin from `/Assignment-1-Conditional-Accessories-Discount/`
2. Create a product category named `Accessories`
3. Assign products to that category and ensure one has price ≥ ₹2000
4. Add product to cart → visit Cart page
5. ✅ Discount and message will appear

### ✅ Message Position Note
The discount message appears only on the **classic WooCommerce Cart page** using:

If using **Cart Block**, the message won't appear due to WooCommerce block limitations.

---

## 🏷️ Assignment 2 – WooCommerce Product Badge Plugin

**Description:**  
Allows admin to assign a custom badge (e.g., “Best Seller”, “Hot Deal”) to individual products.  
The badge appears above the product title on the single product page.

### 💡 Features
- Meta box on the product edit page (badge dropdown)
- Saves selected badge to post meta
- Displays badge on front-end product page
- Shortcode `[custom_badge_products badge="Best Seller"]` to list filtered products

### 🛠 Setup
1. Install the plugin from `/Assignment-2-Product-Badge-Plugin/`
2. Edit any product → select a badge from the new "Product Badge" meta box
3. View the product → badge appears above title
4. Use shortcode to list all products with a selected ba

