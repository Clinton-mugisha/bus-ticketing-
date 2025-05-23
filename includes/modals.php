<!-- Login Modal -->
<div id="login-modal" class="modal">
    <div class="modal-content">
        <span class="close">&times;</span>
        <h2>Login to Your Account</h2>
        <form id="login-form" method="post" action="">
            <?php if (isset($login_error)): ?>
                <div class="error-message">
                    <p><?= $login_error ?></p>
                </div>
            <?php endif; ?>
            <div class="form-group">
                <label for="login-email">Email/Phone</label>
                <input type="text" id="login-email" name="login_email" required>
            </div>
            <div class="form-group">
                <label for="login-password">Password</label>
                <input type="password" id="login-password" name="login_password" required>
            </div>
            <div class="remember-forgot">
                <label><input type="checkbox" name="remember"> Remember me</label>
                <a href="#">Forgot password?</a>
            </div>
            <button type="submit" name="login_submit" class="submit-btn">Login</button>
            <p class="auth-switch">Don't have an account? <a href="#" id="show-signup">Sign up</a></p>
        </form>
    </div>
</div>

<!-- Signup Modal -->
<div id="signup-modal" class="modal">
    <div class="modal-content">
        <span class="close">&times;</span>
        <h2>Create Your Account</h2>
        <form id="signup-form" method="post" action="">
            <?php if (isset($signup_error)): ?>
                <div class="error-message">
                    <i class="fas fa-exclamation-circle"></i>
                    <p><?= htmlspecialchars($signup_error) ?></p>
                </div>
            <?php endif; ?>
            <div class="form-group">
                <label for="signup-name">Full Name</label>
                <input type="text" id="signup-name" name="signup_name" value="<?= isset($_POST['signup_name']) ? htmlspecialchars($_POST['signup_name']) : '' ?>" required>
            </div>
            <div class="form-group">
                <label for="signup-email">Email</label>
                <input type="email" id="signup-email" name="signup_email" value="<?= isset($_POST['signup_email']) ? htmlspecialchars($_POST['signup_email']) : '' ?>" required>
            </div>
            <div class="form-group">
                <label for="signup-phone">Phone Number</label>
                <input type="tel" id="signup-phone" name="signup_phone" placeholder="e.g., 0773123456" value="<?= isset($_POST['signup_phone']) ? htmlspecialchars($_POST['signup_phone']) : '' ?>" required>
            </div>
            <div class="form-group">
                <label for="signup-password">Password</label>
                <input type="password" id="signup-password" name="signup_password" required>
            </div>
            <div class="form-group">
                <label for="signup-confirm">Confirm Password</label>
                <input type="password" id="signup-confirm" name="signup_confirm" required>
            </div>
            <button type="submit" name="signup_submit" class="submit-btn">Sign Up</button>
            <p class="auth-switch">Already have an account? <a href="#" id="show-login">Login</a></p>
        </form>
    </div>
</div>

<!-- Success Modal -->
<?php if ($booking_confirmation): ?>
<div id="success-modal" class="modal" style="display: flex;">
    <div class="modal-content success-content">
        <span class="close">&times;</span>
        <div class="success-icon">
            <i class="fas fa-check-circle"></i>
        </div>
        <h2>Payment Successful!</h2>
        <p>Your booking has been confirmed. Your ticket has been sent to your email and phone.</p>
        <div class="ticket">
            <div class="ticket-header">
                <div class="logo">UgandaMove</div>
                <div class="ticket-type"><?= isset($booking_confirmation['transport_type']) ? ucfirst($booking_confirmation['transport_type']) : 'Bus' ?> Ticket</div>
            </div>
            <div class="ticket-details">
                <div class="ticket-row">
                    <div class="ticket-label">Booking ID:</div>
                    <div class="ticket-value" id="ticket-id"><?= $booking_confirmation['id'] ?></div>
                </div>
                <div class="ticket-row">
                    <div class="ticket-label">Passenger:</div>
                    <div class="ticket-value" id="ticket-passenger"><?= htmlspecialchars($booking_confirmation['passenger']) ?></div>
                </div>
                <div class="ticket-row">
                    <div class="ticket-label">From:</div>
                    <div class="ticket-value" id="ticket-from"><?= htmlspecialchars($booking_confirmation['from']) ?></div>
                </div>
                <div class="ticket-row">
                    <div class="ticket-label">To:</div>
                    <div class="ticket-value" id="ticket-to"><?= htmlspecialchars($booking_confirmation['to']) ?></div>
                </div>
                <div class="ticket-row">
                    <div class="ticket-label">Date:</div>
                    <div class="ticket-value" id="ticket-date"><?= htmlspecialchars($booking_confirmation['date']) ?></div>
                </div>
                <div class="ticket-row">
                    <div class="ticket-label">Time:</div>
                    <div class="ticket-value" id="ticket-time"><?= htmlspecialchars($booking_confirmation['time']) ?></div>
                </div>
                <?php if (isset($booking_confirmation['passengers']) && $booking_confirmation['passengers'] > 1): ?>
                <div class="ticket-row">
                    <div class="ticket-label">Passengers:</div>
                    <div class="ticket-value" id="ticket-passengers"><?= htmlspecialchars($booking_confirmation['passengers']) ?></div>
                </div>
                <?php endif; ?>
                <div class="ticket-row">
                    <div class="ticket-label">Total Price:</div>
                    <div class="ticket-value" id="ticket-price">UGX <?= number_format($booking_confirmation['total_price']) ?></div>
                </div>
            </div>
            <div class="ticket-footer">
                <div class="qr-code">
                    <div class="qr-placeholder"></div>
                </div>
                <p>Scan to validate</p>
            </div>
        </div>
        <a href="download_ticket.php?id=<?= $booking_confirmation['id'] ?>" class="download-btn">Download Ticket</a>
        <a href="<?= BASE_URL ?>" class="return-btn">Return to Home</a>
    </div>
</div>
<?php endif; ?>

<!-- Payment Modal -->
<div id="payment-modal" class="modal">
    <div class="modal-content">
        <span class="close">&times;</span>
        <h2>Complete Your Payment</h2>
        <div class="ticket-summary">
            <h3>Ticket Summary</h3>
            <div class="summary-item">
                <span>Route:</span>
                <span id="summary-route"></span>
            </div>
            <div class="summary-item">
                <span>Date:</span>
                <span id="summary-date"></span>
            </div>
            <div class="summary-item">
                <span>Passengers:</span>
                <span id="summary-passengers"></span>
            </div>
            <div class="summary-item">
                <span>Price:</span>
                <span id="summary-price"></span>
            </div>
        </div>
        <form method="post" action="">
            <input type="hidden" name="route_id" id="payment-route-id">
            <input type="hidden" name="travel_date" id="payment-travel-date">
            <input type="hidden" name="passengers" id="payment-passengers">
            
            <div class="payment-options">
                <h3>Choose Payment Method</h3>
                <div class="payment-methods">
                    <div class="payment-method">
                        <input type="radio" id="mtn" name="payment_method" value="mtn" checked>
                        <label for="mtn">MTN Mobile Money</label>
                    </div>
                    <div class="payment-method">
                        <input type="radio" id="airtel" name="payment_method" value="airtel">
                        <label for="airtel">Airtel Money</label>
                    </div>
                    <div class="payment-method">
                        <input type="radio" id="card" name="payment_method" value="card">
                        <label for="card">Credit/Debit Card</label>
                    </div>
                </div>
                <div id="payment-details" class="payment-details mtn-details">
                    <div class="form-group">
                        <label for="phone-number">Phone Number</label>
                        <input type="tel" id="phone-number" name="phone_number" placeholder="e.g., 0773123456" required>
                    </div>
                </div>
                <?php if (!isset($_SESSION['user'])): ?>
                <div class="login-prompt">
                    <p>You need to be logged in to complete your booking.</p>
                    <button type="button" class="login-btn-prompt">Login</button>
                    <button type="button" class="signup-btn-prompt">Sign Up</button>
                </div>
                <?php else: ?>
                <button type="submit" name="complete_booking" id="pay-btn" class="pay-btn">Pay Now</button>
                <?php endif; ?>
            </div>
        </form>
    </div>
</div>