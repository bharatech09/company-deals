# reCAPTCHA Setup for Registration Form

## Overview
This application now includes Google reCAPTCHA v2 protection on the user registration form to prevent bot submissions.

## Setup Instructions

### 1. Get reCAPTCHA Keys
1. Go to [Google reCAPTCHA Admin Console](https://www.google.com/recaptcha/admin)
2. Click "Create" to add a new site
3. Choose "reCAPTCHA v2" with "I'm not a robot" checkbox
4. Add your domain(s) to the list
5. Accept the terms and click "Submit"
6. Copy the **Site Key** and **Secret Key**

### 2. Add Environment Variables
Add the following lines to your `.env` file:

```env
RECAPTCHA_SITE_KEY=your_site_key_here
RECAPTCHA_SECRET_KEY=your_secret_key_here
```

### 3. Configuration
The reCAPTCHA configuration is already set up in:
- `config/services.php` - Contains the configuration
- `app/Rules/ReCaptcha.php` - Contains the validation rule
- `resources/views/pages/user/register.blade.php` - Contains the form integration
- `app/Http/Controllers/Auth/UserLoginController.php` - Contains the validation logic

### 4. Testing
- The reCAPTCHA will appear on the registration form
- Users must check the "I'm not a robot" checkbox before they can register
- The form will validate the reCAPTCHA response on the server side

## Features
- ✅ reCAPTCHA v2 integration
- ✅ Server-side validation
- ✅ Error handling
- ✅ Responsive design
- ✅ Easy configuration

## Security Benefits
- Prevents automated bot registrations
- Reduces spam accounts
- Protects against automated attacks
- Improves overall site security 