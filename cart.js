let cart = JSON.parse(localStorage.getItem('velvet_cart')) || [];

function saveCart() {
    localStorage.setItem('velvet_cart', JSON.stringify(cart));
}

function addToCart(name, price) {
    price = parseInt(price.replace('₹', ''));
    let existingItem = cart.find(item => item.name === name);
    if (existingItem) {
        existingItem.qty += 1;
    } else {
        cart.push({ name, price, qty: 1 });
    }
    saveCart();
    updateCartIcon();
    renderCart();
    openCart();
}

function removeFromCart(index) {
    cart.splice(index, 1);
    saveCart();
    updateCartIcon();
    renderCart();
}

function changeQty(index, change) {
    if(cart[index].qty + change > 0) {
        cart[index].qty += change;
        saveCart();
        renderCart();
        updateCartIcon();
    }
}

function updateCartIcon() {
    let count = cart.reduce((total, item) => total + item.qty, 0);
    const cartCountElements = document.querySelectorAll('.cart-count');
    cartCountElements.forEach(el => el.innerText = count);
}

function renderCart() {
    const cartItemsContainer = document.getElementById('cart-items');
    if(!cartItemsContainer) return;
    
    cartItemsContainer.innerHTML = '';
    let total = 0;

    if (cart.length === 0) {
        cartItemsContainer.innerHTML = '<p style="text-align:center; color:#888; margin-top:20px;">Your cart is empty.</p>';
        document.getElementById('cart-total').innerText = '0';
        return;
    }

    cart.forEach((item, index) => {
        total += item.price * item.qty;
        cartItemsContainer.innerHTML += `
            <div class="cart-item">
                <div class="cart-item-details">
                    <h4>${item.name}</h4>
                    <p>₹${item.price} x ${item.qty}</p>
                </div>
                <div class="cart-item-actions">
                    <button onclick="changeQty(${index}, -1)">-</button>
                    <span>${item.qty}</span>
                    <button onclick="changeQty(${index}, 1)">+</button>
                    <button class="remove-btn" onclick="removeFromCart(${index})">🗑️</button>
                </div>
            </div>
        `;
    });

    document.getElementById('cart-total').innerText = total;
}

function openCart() {
    document.getElementById('cart-overlay').style.display = 'block';
    setTimeout(() => {
        document.getElementById('cart-drawer').style.right = '0';
        document.getElementById('cart-overlay').style.opacity = '1';
    }, 10);
}

function closeCart() {
    document.getElementById('cart-drawer').style.right = '-400px';
    document.getElementById('cart-overlay').style.opacity = '0';
    setTimeout(() => {
        document.getElementById('cart-overlay').style.display = 'none';
    }, 300);
}

// Inject Cart HTML into body
function logout() {
    fetch('auth.php?action=logout')
    .then(res => res.json())
    .then(data => {
        if(data.status === 'success') {
            window.location.reload();
        }
    });
}

function initCartUI() {
    if(!document.getElementById('cart-drawer')) {
        document.body.insertAdjacentHTML('beforeend', `
            <div class="cart-overlay" id="cart-overlay" onclick="closeCart()"></div>
            <div class="cart-drawer" id="cart-drawer">
                <div class="cart-header">
                    <h3>Your Cart 🛒</h3>
                    <button class="close-cart" onclick="closeCart()">✖</button>
                </div>
                <div class="cart-items" id="cart-items"></div>
                <div class="cart-footer">
                    <h3>Total: ₹<span id="cart-total">0</span></h3>
                    <a href="order.html" class="btn" style="width:100%; display:block; margin-top:15px;" onclick="closeCart()">Proceed to Checkout</a>
                </div>
            </div>
        `);
    }

    // Attach cart counts AND Auth links to navigation
    const navs = document.querySelectorAll('nav');
    
    // Pre-inject My Orders (so it shows up immediately even if auth check is slow)
    navs.forEach(nav => {
        if(!nav.innerHTML.includes('openCart()')) {
            nav.innerHTML += `<a href="javascript:void(0)" onclick="openCart()" class="cart-nav"><span class="nav-icon">🛒</span>Cart (<span class="cart-count">0</span>)</a>`;
        }
        if(!nav.innerHTML.includes('my-orders.html')) {
            nav.innerHTML += `<a href="my-orders.html"><span class="nav-icon">🛍️</span>My Orders</a>`;
        }
    });

    // Handle Login/Logout based on status
    fetch('auth.php?action=check')
    .then(res => res.json())
    .then(data => {
        navs.forEach(nav => {
            if(data.logged_in) {
                // Remove hardcoded Account/Login links if found
                const links = nav.querySelectorAll('a');
                links.forEach(link => {
                    if(link.href.includes('account.html')) {
                        link.innerHTML = `<span class="nav-icon">👤</span>${data.name}`;
                        link.href = "profile.html"; // User is logged in, show profile
                    }
                });

                // Add Logout link if not already present
                if(!nav.innerHTML.includes('logout()')) {
                    nav.innerHTML += `<a href="javascript:void(0)" onclick="logout()"><span class="nav-icon">🚪</span>Logout</a>`;
                }
            } else {
                // Not logged in, so still show account.html normally (it's hardcoded)
            }
        });
        updateCartIcon();
    })
    .catch(err => {
        console.warn("Auth check failed, likely running locally without server.");
        // If fetch fails, still ensure Login link is there as fallback
        navs.forEach(nav => {
            if(!nav.innerHTML.includes('account.html') && !nav.innerHTML.includes('logout()')) {
                nav.innerHTML += `<a href="account.html"><span class="nav-icon">👤</span>Login</a>`;
            }
        });
        updateCartIcon();
    });

    // Attach event listeners to Add to Cart buttons
    const addBtns = document.querySelectorAll('.add-to-cart-btn');
    addBtns.forEach(btn => {
        btn.addEventListener('click', function(e) {
            e.preventDefault();
            const name = this.getAttribute('data-name');
            const price = this.getAttribute('data-price');
            addToCart(name, price);
        });
    });

    updateCartIcon();
    renderCart();
}

window.addEventListener('DOMContentLoaded', initCartUI);
