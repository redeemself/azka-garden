/**
 * Cart Functions for Azka Garden
 *
 * This file contains functions to manage the shopping cart operations
 * including adding products, updating quantities, and showing notifications.
 *
 * Created: 2025-08-02 00:25:48 UTC
 * Author: gerrymulyadi709
 */

// Get CSRF token from meta tag
function getCsrfToken() {
    return document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || '';
}

/**
 * Add a product to cart via AJAX
 *
 * @param {number} productId - The ID of the product to add
 * @param {number} quantity - The quantity to add (default: 1)
 * @param {function} callback - Optional callback function after success
 */
function addToCart(productId, quantity = 1, callback = null) {
    // Get and validate CSRF token
    const csrfToken = getCsrfToken();
    if (!csrfToken) {
        console.error('CSRF token not found');
        showNotification('Error: CSRF token not found', 'error');
        return;
    }

    // Find and update button state
    const buyButton = document.querySelector(`.buy-button[data-product-id="${productId}"]`);
    const originalText = buyButton ? buyButton.innerHTML : '';

    if (buyButton) {
        buyButton.disabled = true;
        buyButton.innerHTML = '<span class="loading-indicator"></span> Menambahkan...';
    }

    // Make AJAX request
    fetch('/cart/add', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': csrfToken,
            Accept: 'application/json',
        },
        body: JSON.stringify({
            product_id: productId,
            quantity: parseInt(quantity),
        }),
    })
        .then((response) => {
            if (!response.ok) {
                throw new Error(`HTTP error! Status: ${response.status}`);
            }
            return response.json();
        })
        .then((data) => {
            // Reset button state
            if (buyButton) {
                buyButton.disabled = false;
                buyButton.innerHTML = originalText;
            }

            if (data.success) {
                // Update cart count in header
                updateCartCount(data.cart_count);

                // Show success notification
                showNotification('Produk berhasil ditambahkan ke keranjang', 'success');

                // Execute callback if provided
                if (callback && typeof callback === 'function') {
                    callback(data);
                }
            } else {
                // Show error notification
                showNotification(data.message || 'Gagal menambahkan produk ke keranjang', 'error');
            }
        })
        .catch((error) => {
            console.error('Error adding product to cart:', error);

            // Reset button state
            if (buyButton) {
                buyButton.disabled = false;
                buyButton.innerHTML = originalText;
            }

            // Show error notification
            showNotification('Terjadi kesalahan saat menambahkan produk ke keranjang', 'error');
        });
}

/**
 * Update the cart count display in the header
 *
 * @param {number} count - New cart count
 */
function updateCartCount(count) {
    const cartCountElements = document.querySelectorAll('.cart-count');

    cartCountElements.forEach((element) => {
        element.textContent = count;

        // Add animation
        element.classList.add('pulse');
        setTimeout(() => {
            element.classList.remove('pulse');
        }, 500);
    });

    // Also update in session storage for persistence
    sessionStorage.setItem('cart_count', count);
}

/**
 * Show a notification message
 *
 * @param {string} message - The message to display
 * @param {string} type - Type of notification ('success', 'error', 'warning')
 * @param {number} duration - How long to show the notification in ms
 */
function showNotification(message, type = 'success', duration = 3000) {
    // Look for existing notification container
    let notificationContainer = document.getElementById('notification-container');

    // Create container if it doesn't exist
    if (!notificationContainer) {
        notificationContainer = document.createElement('div');
        notificationContainer.id = 'notification-container';
        notificationContainer.style.position = 'fixed';
        notificationContainer.style.top = '20px';
        notificationContainer.style.right = '20px';
        notificationContainer.style.zIndex = '9999';
        document.body.appendChild(notificationContainer);
    }

    // Create notification element
    const notification = document.createElement('div');
    notification.className = `notification notification-${type}`;
    notification.innerHTML = `
        <div class="notification-icon">
            ${type === 'success' ? '✓' : type === 'error' ? '✕' : '⚠'}
        </div>
        <div class="notification-message">${message}</div>
        <button class="notification-close">&times;</button>
    `;

    // Style the notification
    notification.style.backgroundColor =
        type === 'success' ? '#10b981' : type === 'error' ? '#ef4444' : '#f59e0b';
    notification.style.color = 'white';
    notification.style.padding = '12px 20px';
    notification.style.borderRadius = '8px';
    notification.style.marginBottom = '10px';
    notification.style.boxShadow = '0 4px 6px rgba(0, 0, 0, 0.1)';
    notification.style.display = 'flex';
    notification.style.alignItems = 'center';
    notification.style.minWidth = '280px';
    notification.style.maxWidth = '400px';
    notification.style.transition = 'all 0.3s ease';
    notification.style.transform = 'translateX(100%)';
    notification.style.opacity = '0';

    // Style the notification elements
    notification.querySelector('.notification-icon').style.marginRight = '12px';
    notification.querySelector('.notification-icon').style.fontSize = '18px';
    notification.querySelector('.notification-message').style.flex = '1';

    const closeButton = notification.querySelector('.notification-close');
    closeButton.style.background = 'transparent';
    closeButton.style.border = 'none';
    closeButton.style.color = 'white';
    closeButton.style.fontSize = '20px';
    closeButton.style.cursor = 'pointer';
    closeButton.style.marginLeft = '8px';

    // Add notification to container
    notificationContainer.appendChild(notification);

    // Animate in
    setTimeout(() => {
        notification.style.transform = 'translateX(0)';
        notification.style.opacity = '1';
    }, 10);

    // Close button functionality
    closeButton.addEventListener('click', () => {
        removeNotification(notification);
    });

    // Auto remove after duration
    setTimeout(() => {
        removeNotification(notification);
    }, duration);

    function removeNotification(notificationElement) {
        notificationElement.style.transform = 'translateX(100%)';
        notificationElement.style.opacity = '0';

        setTimeout(() => {
            notificationElement.remove();
        }, 300);
    }
}

/**
 * Increment quantity input value
 *
 * @param {number} productId - Product ID
 */
function incrementQuantity(productId) {
    const input = document.getElementById(`quantity-${productId}`);
    if (!input) return;

    const currentValue = parseInt(input.value) || 1;
    const maxValue = parseInt(input.getAttribute('max')) || 99;

    if (currentValue < maxValue) {
        input.value = currentValue + 1;
    }
}

/**
 * Decrement quantity input value
 *
 * @param {number} productId - Product ID
 */
function decrementQuantity(productId) {
    const input = document.getElementById(`quantity-${productId}`);
    if (!input) return;

    const currentValue = parseInt(input.value) || 1;

    if (currentValue > 1) {
        input.value = currentValue - 1;
    }
}

/**
 * Initialize cart functionality on page load
 */
document.addEventListener('DOMContentLoaded', function () {
    console.log('Cart functions initialized - 2025-08-02 00:25:48 by gerrymulyadi709');

    // Restore cart count from session storage if available
    const savedCartCount = sessionStorage.getItem('cart_count');
    if (savedCartCount) {
        updateCartCount(savedCartCount);
    }
});
