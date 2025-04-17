<section id="book" class="booking-section">
    <h2>Book Your Ticket</h2>
    <div class="booking-container">
        <div class="booking-form">
            <form method="post" action="#book">
                <div class="form-group">
                    <label for="travel-type">Travel Type</label>
                    <select id="travel-type" name="travel_type">
                        <option value="bus" <?= isset($_POST['travel_type']) && $_POST['travel_type'] == 'bus' ? 'selected' : '' ?>>Bus</option>
                        <option value="train" <?= isset($_POST['travel_type']) && $_POST['travel_type'] == 'train' ? 'selected' : '' ?>>Train</option>
                        <option value="ferry" <?= isset($_POST['travel_type']) && $_POST['travel_type'] == 'ferry' ? 'selected' : '' ?>>Ferry</option>
                    </select>
                </div>
                <div class="form-group">
                    <label for="departure">From</label>
                    <select id="departure" name="departure">
                        <option value="">Select departure city</option>
                        <?php foreach ($cities as $city): ?>
                            <option value="<?= $city ?>" <?= isset($_POST['departure']) && $_POST['departure'] == $city ? 'selected' : '' ?>>
                                <?= ucfirst($city) ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="form-group">
                    <label for="destination">To</label>
                    <select id="destination" name="destination">
                        <option value="">Select destination city</option>
                        <?php foreach ($cities as $city): ?>
                            <option value="<?= $city ?>" <?= isset($_POST['destination']) && $_POST['destination'] == $city ? 'selected' : '' ?>>
                                <?= ucfirst($city) ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="form-group">
                    <label for="travel-date">Date of Travel</label>
                    <input type="date" id="travel-date" name="travel_date" value="<?= isset($_POST['travel_date']) ? htmlspecialchars($_POST['travel_date']) : '' ?>">
                </div>
                <div class="form-group">
                    <label for="passengers">Passengers</label>
                    <select id="passengers" name="passengers">
                        <?php for ($i = 1; $i <= 5; $i++): ?>
                            <option value="<?= $i ?>" <?= isset($_POST['passengers']) && $_POST['passengers'] == $i ? 'selected' : '' ?>>
                                <?= $i ?>
                            </option>
                        <?php endfor; ?>
                    </select>
                </div>
                <button type="submit" name="search_tickets" id="search-btn" class="search-btn">Search Available Tickets</button>
            </form>
            <?php if (isset($search_error)): ?>
                <div class="error-message">
                    <p><?= $search_error ?></p>
                </div>
            <?php endif; ?>
        </div>
        <div id="search-results" class="search-results">
            <?php if (!empty($search_results)): ?>
                <h3>Available Routes (<?= count($search_results) ?>)</h3>
                <div class="results-list">
                    <?php foreach ($search_results as $route): ?>
                        <div class="result-item">
                            <div class="result-header">
                                <div class="result-company">
                                    <i class="<?= $route['type'] === 'bus' ? 'fas fa-bus' : 'fas fa-train' ?>"></i>
                                    <span><?= htmlspecialchars($route['company']) ?></span>
                                </div>
                                <div class="result-price">UGX <?= number_format($route['price']) ?></div>
                            </div>
                            <div class="result-details">
                                <div class="result-journey">
                                    <div class="journey-time">
                                        <div class="time-label">Departure</div>
                                        <div class="time-value"><?= htmlspecialchars($route['departureTime']) ?></div>
                                        <div class="location-value"><?= ucfirst($route['from']) ?></div>
                                    </div>
                                    <div class="journey-duration">
                                        <div class="duration-line"></div>
                                        <div class="duration-value"><?= htmlspecialchars($route['duration']) ?></div>
                                    </div>
                                    <div class="journey-time">
                                        <div class="time-label">Arrival</div>
                                        <div class="time-value"><?= htmlspecialchars($route['arrivalTime']) ?></div>
                                        <div class="location-value"><?= ucfirst($route['to']) ?></div>
                                    </div>
                                </div>
                                <div class="result-availability">
                                    <span><?= $route['available'] ?> seats available</span>
                                </div>
                            </div>
                            <form method="post" action="#payment-section">
                                <input type="hidden" name="route_id" value="<?= $route['id'] ?>">
                                <input type="hidden" name="travel_date" value="<?= isset($_POST['travel_date']) ? htmlspecialchars($_POST['travel_date']) : '' ?>">
                                <input type="hidden" name="passengers" value="<?= isset($_POST['passengers']) ? intval($_POST['passengers']) : '1' ?>">
                                <button type="submit" name="select_route" class="select-route-btn">Select</button>
                            </form>
                        </div>
                    <?php endforeach; ?>
                </div>
            <?php elseif (isset($_POST['search_tickets']) && empty($search_error)): ?>
                <div class="no-results">
                    <i class="fas fa-exclamation-circle"></i>
                    <p>No routes found for the selected criteria. Please try different options.</p>
                </div>
            <?php endif; ?>
        </div>
    </div>
</section>

<?php if (isset($_POST['select_route'])): ?>
<section id="payment-section" class="payment-section">
    <h2>Complete Your Payment</h2>
    <div class="payment-container">
        <div class="ticket-summary">
            <h3>Ticket Summary</h3>
            <?php 
                $selected_route = null;
                foreach ($routes as $r) {
                    if ($r['id'] == $_POST['route_id']) {
                        $selected_route = $r;
                        break;
                    }
                }
                
                if ($selected_route):
                    $total_price = $selected_route['price'] * intval($_POST['passengers']);
                    $formatted_date = date('F j, Y', strtotime($_POST['travel_date']));
            ?>
            <div class="summary-item">
                <span>Route:</span>
                <span id="summary-route"><?= ucfirst($selected_route['from']) ?> to <?= ucfirst($selected_route['to']) ?></span>
            </div>
            <div class="summary-item">
                <span>Date:</span>
                <span id="summary-date"><?= $formatted_date ?></span>
            </div>
            <div class="summary-item">
                <span>Passengers:</span>
                <span id="summary-passengers"><?= intval($_POST['passengers']) ?></span>
            </div>
            <div class="summary-item">
                <span>Price:</span>
                <span id="summary-price">UGX <?= number_format($total_price) ?></span>
            </div>
            <?php endif; ?>
        </div>
        <form method="post" action="">
            <input type="hidden" name="route_id" value="<?= $_POST['route_id'] ?>">
            <input type="hidden" name="travel_date" value="<?= htmlspecialchars($_POST['travel_date']) ?>">
            <input type="hidden" name="passengers" value="<?= intval($_POST['passengers']) ?>">
            
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
                <button type="submit" name="complete_booking" id="pay-btn" class="pay-btn">Pay Now</button>
            </div>
        </form>
    </div>
</section>
<?php endif; ?>