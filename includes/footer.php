    <footer>
        <div class="footer-content">
            <div class="footer-logo">
                <div class="logo">
                    <span>Uganda</span>Move
                </div>
                <p>Making travel easier across Uganda.</p>
            </div>
            <div class="footer-links">
                <div class="link-group">
                    <h4>Quick Links</h4>
                    <ul>
                        <li><a href="#">Home</a></li>
                        <li><a href="#book">Book Tickets</a></li>
                        <li><a href="#routes">Routes</a></li>
                        <li><a href="#track">Track Journey</a></li>
                    </ul>
                </div>
                <div class="link-group">
                    <h4>Support</h4>
                    <ul>
                        <li><a href="#">Help Center</a></li>
                        <li><a href="#">Contact Us</a></li>
                        <li><a href="#">FAQs</a></li>
                        <li><a href="#">Terms of Service</a></li>
                    </ul>
                </div>
                <div class="link-group">
                    <h4>Contact</h4>
                    <ul>
                        <li><i class="fas fa-phone"></i> +256 700 123 456</li>
                        <li><i class="fas fa-envelope"></i> info@ugandamove.com</li>
                        <li><i class="fas fa-map-marker-alt"></i> Kampala Road, Uganda</li>
                    </ul>
                </div>
            </div>
        </div>
        <div class="footer-bottom">
            <p>&copy; <?= date('Y') ?> UgandaMove. All rights reserved.</p>
            <div class="social-icons">
                <a href="#"><i class="fab fa-facebook"></i></a>
                <a href="#"><i class="fab fa-twitter"></i></a>
                <a href="#"><i class="fab fa-instagram"></i></a>
                <a href="#"><i class="fab fa-linkedin"></i></a>
            </div>
        </div>
    </footer>

    <script>
    // Modified JS to work with PHP
    document.addEventListener('DOMContentLoaded', function() {
        // DOM Elements
        const loginBtn = document.querySelector('.login-btn');
        const signupBtn = document.querySelector('.signup-btn');
        const loginModal = document.getElementById('login-modal');
        const signupModal = document.getElementById('signup-modal');
        const closeButtons = document.querySelectorAll('.close');
        const showSignup = document.getElementById('show-signup');
        const showLogin = document.getElementById('show-login');
        const ctaButton = document.querySelector('.cta-button');
        const hamburger = document.querySelector('.hamburger');
        const navLinks = document.querySelector('.nav-links');
        const authButtons = document.querySelector('.auth-buttons');
        const paymentMethods = document.querySelectorAll('input[name="payment_method"]');
        const paymentDetails = document.getElementById('payment-details');
        const paymentModal = document.getElementById('payment-modal');

        // Function to close mobile menu
        function closeMobileMenu() {
            navLinks.classList.remove('show');
            authButtons.classList.remove('show');
        }

        // Event listener for hamburger menu
        if (hamburger) {
            hamburger.addEventListener('click', () => {
                navLinks.classList.toggle('show');
                authButtons.classList.toggle('show');
            });
        }

        // Close mobile menu when clicking outside
        document.addEventListener('click', (event) => {
            if (!navLinks || !authButtons || !hamburger) return;
            
            const isClickInsideNav = navLinks.contains(event.target);
            const isClickInsideAuth = authButtons.contains(event.target);
            const isClickHamburger = hamburger.contains(event.target);
            
            if (!isClickInsideNav && !isClickInsideAuth && !isClickHamburger) {
                closeMobileMenu();
            }
        });

        // Close mobile menu when clicking on a nav link
        if (navLinks) {
            navLinks.addEventListener('click', (event) => {
                if (event.target.tagName === 'A') {
                    closeMobileMenu();
                }
            });
        }

        // Close mobile menu when clicking on auth buttons
        if (authButtons) {
            authButtons.addEventListener('click', (event) => {
                if (event.target.classList.contains('login-btn') || event.target.classList.contains('signup-btn')) {
                    closeMobileMenu();
                }
            });
        }

        // Modal Controls
        if (loginBtn) {
            loginBtn.addEventListener('click', () => {
                loginModal.style.display = 'flex';
            });
        }

        if (signupBtn) {
            signupBtn.addEventListener('click', () => {
                signupModal.style.display = 'flex';
            });
        }

        if (showSignup) {
            showSignup.addEventListener('click', (e) => {
                e.preventDefault();
                loginModal.style.display = 'none';
                signupModal.style.display = 'flex';
            });
        }

        if (showLogin) {
            showLogin.addEventListener('click', (e) => {
                e.preventDefault();
                signupModal.style.display = 'none';
                loginModal.style.display = 'flex';
            });
        }

        closeButtons.forEach(button => {
            button.addEventListener('click', () => {
                if (loginModal) loginModal.style.display = 'none';
                if (signupModal) signupModal.style.display = 'none';
                if (paymentModal) paymentModal.style.display = 'none';
                
                // Close success modal if open and redirect to home
                const successModal = document.getElementById('success-modal');
                if (successModal && successModal.style.display === 'flex') {
                    successModal.style.display = 'none';
                    window.location.href = '<?= BASE_URL ?>';
                }
            });
        });

        // Close modals when clicking outside
        window.addEventListener('click', (e) => {
            if (loginModal && e.target === loginModal) loginModal.style.display = 'none';
            if (signupModal && e.target === signupModal) signupModal.style.display = 'none';
            if (paymentModal && e.target === paymentModal) paymentModal.style.display = 'none';
            
            // Close success modal if open
            const successModal = document.getElementById('success-modal');
            if (successModal && e.target === successModal) {
                successModal.style.display = 'none';
                window.location.href = '<?= BASE_URL ?>';
            }
        });

        // CTA Button action
        if (ctaButton) {
            ctaButton.addEventListener('click', () => {
                document.querySelector('#book').scrollIntoView({ behavior: 'smooth' });
            });
        }

        // Handle payment method selection
        if (paymentMethods.length > 0 && paymentDetails) {
            paymentMethods.forEach(method => {
                method.addEventListener('change', function() {
                    if (this.value === 'mtn') {
                        paymentDetails.innerHTML = `
                            <div class="form-group">
                                <label for="phone-number">Phone Number</label>
                                <input type="tel" id="phone-number" name="phone_number" placeholder="e.g., 0773123456" required>
                            </div>
                        `;
                        paymentDetails.className = 'payment-details mtn-details';
                    } else if (this.value === 'airtel') {
                        paymentDetails.innerHTML = `
                            <div class="form-group">
                                <label for="phone-number">Phone Number</label>
                                <input type="tel" id="phone-number" name="phone_number" placeholder="e.g., 0700123456" required>
                            </div>
                        `;
                        paymentDetails.className = 'payment-details airtel-details';
                    } else if (this.value === 'card') {
                        paymentDetails.innerHTML = `
                            <div class="form-group">
                                <label for="card-number">Card Number</label>
                                <input type="text" id="card-number" name="card_number" placeholder="1234 5678 9012 3456" required>
                            </div>
                            <div class="form-row">
                                <div class="form-group">
                                    <label for="expiry">Expiry Date</label>
                                    <input type="text" id="expiry" name="expiry" placeholder="MM/YY" required>
                                </div>
                                <div class="form-group">
                                    <label for="cvv">CVV</label>
                                    <input type="text" id="cvv" name="cvv" placeholder="123" required>
                                </div>
                            </div>
                            <div class="form-group">
                                <label for="card-name">Name on Card</label>
                                <input type="text" id="card-name" name="card_name" required>
                            </div>
                        `;
                        paymentDetails.className = 'payment-details card-details';
                    }
                });
            });
        }

        // Handle select route buttons - Open payment modal
        const selectRouteBtns = document.querySelectorAll('.select-route-btn');
        if (selectRouteBtns.length > 0 && paymentModal) {
            selectRouteBtns.forEach(btn => {
                btn.addEventListener('click', function(e) {
                    e.preventDefault();
                    const form = this.closest('form');
                    const routeId = form.querySelector('input[name="route_id"]').value;
                    const travelDate = form.querySelector('input[name="travel_date"]').value;
                    const passengers = form.querySelector('input[name="passengers"]').value;
                    
                    // Find the route details from the available routes
                    const routes = <?= json_encode($routes) ?>;
                    const selectedRoute = routes.find(route => route.id == routeId);
                    
                    if (selectedRoute) {
                        const totalPrice = selectedRoute.price * parseInt(passengers);
                        
                        // Format the date
                        const dateObj = new Date(travelDate);
                        const formattedDate = dateObj.toLocaleDateString('en-US', { 
                            year: 'numeric', 
                            month: 'long', 
                            day: 'numeric' 
                        });
                        
                        // Set values in the payment modal
                        document.getElementById('summary-route').textContent = 
                            `${selectedRoute.from.charAt(0).toUpperCase() + selectedRoute.from.slice(1)} to ${selectedRoute.to.charAt(0).toUpperCase() + selectedRoute.to.slice(1)}`;
                        document.getElementById('summary-date').textContent = formattedDate;
                        document.getElementById('summary-passengers').textContent = passengers;
                        document.getElementById('summary-price').textContent = `UGX ${totalPrice.toLocaleString()}`;
                        
                        // Set hidden form values
                        document.getElementById('payment-route-id').value = routeId;
                        document.getElementById('payment-travel-date').value = travelDate;
                        document.getElementById('payment-passengers').value = passengers;
                        
                        // Show payment modal
                        paymentModal.style.display = 'flex';
                    }
                });
            });
        }
    });
    </script>
</body>
</html>