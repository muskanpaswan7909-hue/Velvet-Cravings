/**
 * auth-ui.js - Handles Flipkart-style account dropdown logic
 * Updates "Account" text to user's name and manages dropdown links
 */

document.addEventListener('DOMContentLoaded', function() {
    checkAuthStatus();
});

function checkAuthStatus() {
    fetch('auth.php?action=check')
        .then(response => response.json())
        .then(data => {
            const accountLink = document.getElementById('account-link');
            const accountMenu = document.getElementById('account-menu');
            
            if (!accountLink || !accountMenu) return;

            if (data.logged_in) {
                // User is logged in
                accountLink.innerHTML = `<span class="nav-icon">👤</span>Hi, ${data.name.split(' ')[0]}`;
                
                accountMenu.innerHTML = `
                    <div class="dropdown-header">
                        <span>Welcome back!</span>
                    </div>
                    <a href="profile.html" class="dropdown-item">👤 My Profile</a>
                    <a href="my-orders.html" class="dropdown-item">🛍️ My Orders</a>
                    <div class="dropdown-divider"></div>
                    <a href="#" class="dropdown-item" id="logout-btn">🚪 Logout</a>
                `;

                document.getElementById('logout-btn').addEventListener('click', function(e) {
                    e.preventDefault();
                    logoutUser();
                });
            } else {
                // User is NOT logged in
                accountLink.innerHTML = `<span class="nav-icon">👤</span>Login`;
                
                accountMenu.innerHTML = `
                    <div class="dropdown-header">
                        <span>New customer?</span>
                        <a href="account.html" class="dropdown-btn-login">Sign Up</a>
                    </div>
                    <a href="account.html" class="dropdown-item">👤 My Profile</a>
                    <a href="my-orders.html" class="dropdown-item">🛍️ My Orders</a>
                `;
            }
        })
        .catch(err => console.error('Error checking auth:', err));
}

function logoutUser() {
    fetch('auth.php?action=logout')
        .then(response => response.json())
        .then(data => {
            if (data.status === 'success') {
                window.location.href = 'index.html';
            }
        });
}
